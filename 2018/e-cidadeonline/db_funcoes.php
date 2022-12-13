<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

function db_inicio_transacao(){
  db_query('BEGIN');
}
function db_fim_transacao(){
  db_query('END');
}

// Parametros do $tipo
// 1  Bota as contas do plano que não existem no saltes
// 2  Bota as contas do saltes
// 3  Bota as contas do plano
function db_contas($nome,$valor="",$tipo=1) {
  if($tipo == 1) {
    $sql_redu = "select c01_reduz,c01_descr 
	             from plano 
				 where c01_reduz <> 0 and substr(c01_estrut,1,3) in ('111','112') and c01_anousu = ".db_getsession("DB_anousu")."  
				 order by c01_reduz";

    $sql_desc = "select c01_reduz,c01_descr 
	             from plano 
				 where c01_reduz <> 0 and substr(c01_estrut,1,3) in ('111','112') and c01_anousu = ".db_getsession("DB_anousu")." 
				 order by c01_descr";
  } else if($tipo == 2) {
    $sql_redu = "select p.k13_conta,l.c01_descr 
	             from saltes p 
				      inner join saltesplan s on s.k13_conta = p.k13_conta and s.c01_anousu = ".db_getsession("DB_anousu")."
					  inner join plano l on l.c01_anousu = ".db_getsession("DB_anousu")." and l.c01_reduz = s.c01_reduz
			     order by p.k13_conta";
    $sql_desc = "select p.k13_conta,l.c01_descr 
	             from saltes p 
				      inner join saltesplan s on s.k13_conta = p.k13_conta  and s.c01_anousu = ".db_getsession("DB_anousu")."
					  inner join plano l on l.c01_anousu = ".db_getsession("DB_anousu")." and l.c01_reduz = s.c01_reduz
				 order by c01_descr";
  } else if($tipo == 3) {
    $sql_redu = "select c01_reduz,c01_descr 
	             from plano 
				 where c01_anousu = ".db_getsession("DB_anousu")." and c01_reduz <> 0 order by c01_reduz";
    $sql_desc = "select c01_reduz,c01_descr 
	             from plano 
				 where c01_anousu = ".db_getsession("DB_anousu")." and c01_reduz <> 0 order by c01_descr";	
  }
  ?>
  <table border="0" cellpadding="0" cellspacing="0">
  <tr><td nowrap>
  <select name="<?=$nome?>" onChange="js_ProcCod('<?=$nome?>','<?=$nome."descr"?>')">
    <?
	$result_redu = db_query($sql_redu);
	$numrows = pg_numrows($result_redu);
	for($i = 0;$i < $numrows;$i++) {
	  echo "<option value=\"".pg_result($result_redu,$i,0)."\" >".pg_result($result_redu,$i,0)."</option>\n";
	}
	?>
  </select>&nbsp;&nbsp;
  <select name="<?=$nome."descr"?>" onChange="js_ProcCod('<?=$nome."descr"?>','<?=$nome?>')">
    <?
	$result_desc = db_query($sql_desc);
	for($i = 0;$i < $numrows;$i++) {
	  echo "<option value=\"".pg_result($result_desc,$i,0)."\">".pg_result($result_desc,$i,1)."</option>\n";
	}	
	?>
  </select>
  </td></tr>
  </table>
  <script>
  function js_ProcCod(proc,res) {
    var sel1 = document.form1.elements[proc];
    var sel2 = document.form1.elements[res];	
	for(var i = 0;i < sel1.options.length;i++) {
	  if(sel1.options[sel1.selectedIndex].value == sel2.options[i].value)
	    sel2.options[i].selected = true;
	}
  }
  document.form1.elements['<?=$nome?>'].options[0].selected = true;
  js_ProcCod('<?=$nome?>','<?=$nome."descr"?>');
  </script>
  <?
}
//////////////////////////////////////
function db_input($nome,$dbsize,$dbvalidatipo,$dbcadastro,$dbhidden='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="") { 


// ver erro no windows xp
/*
  if($dbvalidatipo==1||$dbvalidatipo==4){
     echo "<script>
	       if(DB_valida_campos_numerico!='')
		      DB_valida_campos_numerico = DB_valida_campos_numerico + '#';
	       DB_valida_campos_numerico = DB_valida_campos_numerico + '".($nomevar==""?$nome:$nomevar)."';
		   </script>";
  }else if($dbvalidatipo==2||$dbvalidatipo==3){
     if(@$GLOBALS['N'.$nome]==""){
       echo "<script>
	         if(DB_valida_campos_alfa!='')
		        DB_valida_campos_alfa = DB_valida_campos_alfa + '#';
	         DB_valida_campos_alfa = DB_valida_campos_alfa + '".($nomevar==""?$nome:$nomevar)."';
		     </script>";     
     }
  }
*/
  ?>    
  <input title="<?=@$GLOBALS['T'.$nome]?>" name="<?=($nomevar==""?$nome:$nomevar)?>"  type="<?=$dbhidden?>" 
    id="<?=($nomevar==""?$nome:$nomevar)?>"  value="<?=@$GLOBALS[($nomevar==""?$nome:$nomevar)]?>"  size="<?=$dbsize?>" 
	maxlength="<?=@$GLOBALS['M'.$nome]?>" 
  <?
  echo $js_script;
  if($dbcadastro == true){ 
    if ($db_opcao==3 || $db_opcao==22){
	   echo " readonly ";
    }
    if ($db_opcao==5){
	   echo " disabled ";
    }
  }
//  if(strstr(
  if($bgcolor==""){
    echo " ".@$GLOBALS['N'.$nome]." ";
  }else{
    echo " style=\"background-color:$bgcolor\" ";
  }
  if (($db_opcao!=3) && ($db_opcao!=5)){
  ?>
    onblur="js_ValidaMaiusculo(this,'<?=@$GLOBALS['G'.$nome]?>',<?=($dbvalidatipo==''?0:$dbvalidatipo)?>);" 
    onKeyUp="js_ValidaCampos(this,<?=($dbvalidatipo==''?0:$dbvalidatipo)?>,'<?=@$GLOBALS['S'.$nome]?>','<?=($db_opcao==4?"t":@$GLOBALS['U'.$nome])?>','<?=@$GLOBALS['G'.$nome]?>',event);"
    onKeyDown="return js_controla_tecla_enter(this,event);"
  <?
  }
  ?>
    autocomplete='<?=@$GLOBALS['A'.$nome]?>'>
  <?
}
/*************************************/ 
function db_textarea($nome,$dbsizelinha=1,$dbsizecoluna=1,$dbvalidatipo,$dbcadastro=true,$dbhidden='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="") { 
  ?>    
  <textarea title="<?=@$GLOBALS['T'.$nome]?>" name="<?=($nomevar==""?$nome:$nomevar)?>"  type="<?=$dbhidden?>" 
    id="<?=($nomevar==""?$nome:$nomevar)?>" rows="<?=$dbsizelinha?>" cols="<?=$dbsizecoluna?>" 
  <?
  echo $js_script;
  if($dbcadastro == true){ 
    if ($db_opcao==3 || $db_opcao==22){
	   echo " readonly ";
    }
    if ($db_opcao==5){
	   echo " disabled ";
    }
  }
  ?>
    onblur="js_ValidaMaiusculo(this,'<?=@$GLOBALS['G'.$nome]?>',event);" 
    onKeyUp="js_ValidaCampos(this,<?=($dbvalidatipo==''?0:$dbvalidatipo)?>,'<?=@$GLOBALS['S'.$nome]?>','<?=@$GLOBALS['U'.$nome]?>','<?=@$GLOBALS['G'.$nome]?>',event);"
    <?=@$GLOBALS['N'.$nome]?> autocomplete='<?=@$GLOBALS['A'.$nome]?>'><?=(!isset($GLOBALS[$nome])?"":$GLOBALS[$nome])?></textarea>  
  <?
}

function db_ancora($nome,$js_script,$db_opcao,$bgcolor=""){
  if(($db_opcao<3) || ($db_opcao==4)){
    ?>
    <a style='text-decoration:underline;cursor:hand' onclick="<?=$js_script?>" ><?=$nome?></a>
    <?
   }else{
      echo $nome;
   }
}
/*************************************/ 

function db_selectrecord($nome,$record,$dbcadastro,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$todos="",$onchange=""){
  if($nomevar!=""){
    $nome = $nomevar;
	$nomedescr = $nomevar."descr";
  }else{
	$nomedescr = $nome."descr";    
  }
  if($db_opcao != 3 && $db_opcao != 5 && $db_opcao !=22 && $db_opcao != 33){
    ?>
    <select name="<?=$nome?>" id="<?=$nome?>" 
	  onchange="js_ProcCod_<?=$nome?>('<?=$nome?>','<?=$nomedescr?>');<?=$onchange?>"
    <? 
    if($dbcadastro == true){ 
      if ($db_opcao==3 || $db_opcao==22){
  	     echo " readonly ";
      }
      if ($db_opcao==5){
	     echo " disabled ";
      }
    }
    echo $js_script;
    ?>
     >
    <?
    if($todos!=""){
	  ?>
	  <option value="<?=$todos?>" ><?=$todos?></option>
	  <?
	}
    for($sqli=0;$sqli<pg_numrows($record);$sqli++){
	  $sqlv = pg_result($record,$sqli,0);
  	  ?>
      <option value="<?=$sqlv?>" <?=(@$GLOBALS[$nome]==$sqlv?"selected":"")?>><?=$sqlv?></option>
      <?
    }
    ?>	
    </select>
    <?
    if(pg_numfields($record)>0){
      ?>
      <select name="<?=$nomedescr?>" id="<?=$nomedescr?>" 
	  onchange="js_ProcCod_<?=$nome?>('<?=$nomedescr?>','<?=$nome?>');<?=$onchange?>"
      <? 
      if($dbcadastro == true){ 
        if ($db_opcao==3 || $db_opcao==22){
          echo " readonly ";
        }
        if ($db_opcao==5){
 	     echo " disabled ";
        }
      }
      echo $js_script;
      ?>
       >
      <?
      if($todos!=""){
	  ?>
	  <option value="<?=$todos?>" >Todos ...</option>
	  <?
	}
      for($sqli=0;$sqli<pg_numrows($record);$sqli++){
 	  $sqlv = pg_result($record,$sqli,0);
	  $sqlv1 = pg_result($record,$sqli,1);
  	  ?>
      <option value="<?=$sqlv?>" ><?=$sqlv1?></option>
        <?
      }
      ?>	
      </select>
      <script>
      function js_ProcCod_<?=$nome?>(proc,res) {
       var sel1 = document.form1.elements[proc];
       var sel2 = document.form1.elements[res];		  
       for(var i = 0;i < sel1.options.length;i++) {
	     if(sel1.options[sel1.selectedIndex].value == sel2.options[i].value)
	       sel2.options[i].selected = true;
	   }
      }
      //document.form1.elements['<?=$nome?>'].options[0].selected = true;
      js_ProcCod_<?=$nome?>('<?=$nome?>','<?=$nomedescr?>');
      </script>
      <?
    }else{
      ?>
      <script>
      function js_ProcCod_<?=$nome?>(){
      }
      </script>
      <?
    }
  }else{
     $clrot = new rotulocampo;
     $clrot->label("$nome");
     $tamm = "M$nome";     
     db_input($nome,$GLOBALS[$tamm],'',$dbcadastro,'text',3,"",$nomevar,"");
     $nomec = "";
     for($sqli=0;$sqli<pg_numrows($record);$sqli++){
       if(pg_result($record,$sqli,0)==@$GLOBALS[$nome]){
	 $nomec = pg_fieldname($record,1);
         global $$nomec;
         $$nomec = pg_result($record,$sqli,1);
         $clrot->label($nomec);
         $tamm = "M".trim($nomec);     
	 break; 
       }
     }
     if(!empty($nomec)){
       db_input($nomec,$GLOBALS[$tamm],'',$dbcadastro,'text',3,"");
     }
  }
}
//////////////////////////////////////

function db_selectmultiple($nome,$record,$size,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$record_select,$onchange=""){
  if($nomevar!=""){
    $nome = $nomevar;
  }
  if($db_opcao != 3 && $db_opcao != 5 && $db_opcao!=33 && $db_opcao != 22){
    ?>
    <select multiple name="<?=$nome?>[]" size="<?=$size?>" id="<?=$nome?>" 
	  onchange="js_ProcCod_<?=$nome?>('<?=$nome?>','<?=$nome?>');<?=$onchange?>"
    <? 
    if ($db_opcao==3 || $db_opcao==22){
       echo " readonly ";
    }
    if ($db_opcao==5){
       echo " disabled ";
    }
    echo $js_script;
    ?>
     >
    <?
    for($sqli=0;$sqli<pg_numrows($record);$sqli++){
      if($sqli%2 == 0){
	$color = "#D7CC06";
      }else{
        $color = "#F8EC07"; 
      }	 	
      $sqlv = pg_result($record,$sqli,0);
      $sqlv1 = pg_result($record,$sqli,1);
      $esta_selecionado = "";
      if($db_opcao != 1 && $db_opcao!=22){
        for($sqls=0;$sqls<pg_numrows($record_select);$sqls++){
           $sqlsv = pg_result($record_select,$sqls,0);
  	   if($sqlsv == $sqlv)
             $esta_selecionado = " selected ";
        }
      }
      ?>
      <option value="<?=$sqlv?>" style="background-color:<?=$color?>" <?=$esta_selecionado?>><?=$sqlv1?></option>
      <?
    }
    ?> 
    </select>
    <?
  }else{
    if(!is_int($record_select) && $record_select!=false){
      if(pg_numrows($record_select)>0){
        db_selectrecord($nome,$record_select,true,($db_opcao==3?2:$db_opcao),"",$nomevar="",$bgcolor="",$todos="",$onchange="");
      }      
    }else{
       db_input($nome,5,'',true,'text',3,"");
    }
  }
}


/*************************************/ 

function db_select($nome,$db_matriz,$dbcadastro,$db_opcao=3,$js_script="",$nomevar="",$bgcolor=""){
  
  if($db_opcao != 3 && $db_opcao != 5 && $db_opcao!=22 && $db_opcao !=33){
    ?>
    <select name="<?=$nome?>" id="<?=$nome?>" 
    <? 
    if($dbcadastro == true){ 
      if ($db_opcao==3 || $db_opcao==22){
  	     echo " readonly ";
      }
      if ($db_opcao==5){
	     echo " disabled ";
      }
    }
    echo $js_script;
    ?>
     >
    <?
    //x = array("a"=>"1","2")
    reset($db_matriz);
    for($i=0;$i<sizeof($db_matriz);$i++){
  	  ?>
      <option value="<?=key($db_matriz)?>" <?=(@$GLOBALS[$nome]==key($db_matriz)?"selected":"")?>><?=$db_matriz[key($db_matriz)]?></option>
      <?
	  next($db_matriz);
    }
    ?>	
    </select>
    <?
  }else{
     global $$nome ;
	 $$nome = @$db_matriz["$GLOBALS[$nome]"];
	 db_input($nome,5,'',$dbcadastro,'text',3,"","","");
  }
}
//////////////////////////////////////

function db_inputdata($nome,$dia="",$mes="",$ano="",$dbcadastro=true,$dbtype='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="") {
  global $DataJavaScript;
  if(!isset($DataJavaScript)) {
    $DataJavaScript = new janela("DataJavaScript","");
    $DataJavaScript->posX=1;
    $DataJavaScript->posY=1;
    $DataJavaScript->largura=140;
    $DataJavaScript->altura=210;
    $DataJavaScript->titulo="Calendário";
    $DataJavaScript->iniciarVisivel = false;
    $DataJavaScript->scrollbar = "no";
    $DataJavaScript->janBotoes = "001";
    $DataJavaScript->mostrar();
  }
  ?>
  <input name="<?=($nomevar==""?$nome:$nomevar)."_dia"?>" onKeyDown="return js_controla_tecla_enter(this,event)" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="<?=$dbtype?>" title="<?=@$GLOBALS['T'.$nome]?>" <?=($db_opcao==3?'readonly':($db_opcao==5?'disabled':''))?> id="<?=($nomevar==""?$nome:$nomevar)."_dia"?>" value="<?=$dia?>" size="2" maxlength="2" autocomplete="off">
  <input name="<?=($nomevar==""?$nome:$nomevar)."_mes"?>" onKeyDown="return js_controla_tecla_enter(this,event)" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="<?=$dbtype?>" title="<?=@$GLOBALS['T'.$nome]?>" <?=($db_opcao==3?'readonly':($db_opcao==5?'disabled':''))?> id="<?=($nomevar==""?$nome:$nomevar)."_mes"?>" value="<?=$mes?>" size="2" maxlength="2" autocomplete="off">
  <input name="<?=($nomevar==""?$nome:$nomevar)."_ano"?>" onKeyDown="return js_controla_tecla_enter(this,event)" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="<?=$dbtype?>" title="<?=@$GLOBALS['T'.$nome]?>" <?=($db_opcao==3?'readonly':($db_opcao==5?'disabled':''))?> id="<?=($nomevar==""?$nome:$nomevar)."_ano"?>" value="<?=$ano?>" size="4" maxlength="4" autocomplete="off">
  <?
  if(($db_opcao < 3) || ($db_opcao==4)){
  ?>
  <input value="D" type="button" name="acessadatajavascript" onclick="pegaPosMouse(event);show_calendar('form1.<?=$nome?>')">
  <?
  }
}
/*************************************/


//////////////////////////////////////
/*************************************/
function db_label_blur($tab,$label,$campo="",$campoaux="") {

$campo = ($campo=="")?$label:$campo;

?>
  <strong>
  <label for="db_<?=$campo?>">
  <a href="" class="rotulos" onClick="js_lista_blur('dbforms/db_<?=$tab?>.php',document.form1.db_<?=$campo?>.value,'<?=$campo?>',100,50,600,420,document.form1.db_<?=$campoaux?>.value,'<?=$campoaux?>');return false">
    <?=ucwords($label)?>:
  </a>
  </label>
  </strong>
<?
}

function db_text_blur($tab,$campo,$campoaux,$tamanho,$max,$db_nome="",$dbh_nome="") {
?>
  <input name="db_<?=$campo?>" id="db_<?=$campo?>" <?=@$read_only?> value="<?=$db_nome?>" type="text" size="<?=$tamanho?>" maxlength="<?=$max?>" onChange="if(this.value!='') js_lista_blur('dbforms/db_<?=$tab?>.php','db_<?=$campo?>' + '==' + document.form1.db_<?=$campo?>.value,'<?=$campo?>',100,50,600,420,'db_<?=$campoaux?>' + '==' + document.form1.db_<?=$campoaux?>.value,'<?=$campoaux?>','')" autocomplete="off">
  <input name="dbh_<?=$campo?>" type="hidden" value="<?=$dbh_nome?>">
<?
}

function db_label($tab,$label,$campo="") {
$campo = ($campo=="")?$label:$campo;
?>
  <strong>
  <label for="db_<?=$campo?>">
  <a href="" class="rotulos" onClick="js_lista('dbforms/db_<?=$tab?>.php','db_<?=$campo?>' + '==' + document.form1.db_<?=$campo?>.value,'<?=$campo?>',05,50,780);return false">
    <?=ucwords($label)?>:
  </a>
  </label>
  </strong>
<?
}
/************************************/
// Parametro $validacao
// 0 Aceita qualquer coisa
// 1 Aceita apenas numeros
// 2 Aceita apenas letras
function db_text($campo,$tamanho,$max,$db_nome="",$dbh_nome="",$validacao = 0) {
?>
  <input name="db_<?=$campo?>" onBlur="js_ValidaCamposText(this,<?=$validacao?>)" id="db_<?=$campo?>" <?=@$readonly?> value="<?=$db_nome?>" type="text" size="<?=$tamanho?>" maxlength="<?=$max?>" autocomplete="off">
  <input name="dbh_<?=$campo?>" type="hidden" value="<?=$dbh_nome?>">
<?
}


/************************************/
function db_file($campo,$tamanho,$max,$dbh_nome="",$db_nome="") {
?>
  <input onChange="js_preencheCampo(this.value,this.form.dbh_<?=$campo?>.name)" name="db_<?=$campo?>" id="db_<?=$campo?>" value="<?=$db_nome?>" type="file" size="<?=$tamanho?>" maxlength="<?=$max?>" autocomplete="off"><br>
  <input name="dbh_<?=$campo?>" type="text" value="<?=$dbh_nome?>" size="<?=$tamanho?>" maxlength="<?=$max?>" autocomplete="off">
<?
}
/************************************/
function db_getfile($arq,$text,$funcao="0") {
  db_postmemory($GLOBALS["_FILES"][$arq]);
  $DB_FILES = $GLOBALS["DB_FILES"];
  $tmp_name = $GLOBALS["tmp_name"];
  $name = $GLOBALS["name"];
  $size = $GLOBALS["size"];
  if($funcao != "0") {
    if($name != "") {
      system("rm -f $DB_FILES/$funcao");
      copy($tmp_name,"$DB_FILES/$text");
	  return $text;
    } else if($text != "") {
      if($text != $funcao) {
	    system("mv $DB_FILES/$funcao $DB_FILES/$text");
		return $text;
	  } else
	    return $text;
    } else if($text == "") {
	  system("rm -f $DB_FILES/$funcao");
	  return "";
	}
  } else if($name != "" && $size == 0) {
      db_erro("O arquivo $name não foi encontrado ou ele está vazio. Verifique o seu caminho e o seu tamanho e tente novamente.");
  } else {
    copy($tmp_name,"$DB_FILES/$text");
    return $text;
  }
}
?>