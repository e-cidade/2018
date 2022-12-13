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

//#00#//documentacao
//#10#//Como documentar ou funcao ou classe
//#99#////#10#//
//#99#//Descrição da função ou método gerada no *//#00#//*
//#99#////#15#//
//#99#//Sintaxe da função ou método
//#99#////#20#//
//#99#//Parâmetros fornecidos para a função ou método, como mostra a sintaxe *//#10#//*
//#99#////#30#//
//#99#//Propriedades ou variaveis de função
//#99#////#40#//
//#99#//Retorno da função ou método
//#99#////#99#//
//#99#//Observação sobre a função ou método









//#00#//db_opcao
//#10#//Opção do sistema para inclusão, alteração ou exclusão em formulários
//#99#// 1 - Inclusão
//#99#// 2 - Alteração
//#99#//22 - Inicio do formulário antes de selecionar um ítem para alterar
//#99#// 3 - Exclusão
//#99#//33 - Inicio do formulário antes de selecionar um ítem para excluir
//#99#// 5 - Objeto desabilitado no formulário ( disabled )

function db_inicio_transacao(){
//#00#//db_inicio_transacao
//#10#//função para abrir uma transação
//#15#//db_inicio_transacao();
//#99#//Uma transação é um conjunto de execuções no banco de dados que deverão ser gravadas somente
//#99#//se todas as execuções tiverem sucesso, caso contrário, nenhuma das execuções deverá ser
//#99#//confirmada
  db_query('BEGIN');
}
function db_fim_transacao($erro=false){
//#00#//db_fim_transacao
//#10#//função para finalizar uma transação
//#15#//db_fim_transacao($erro=false);
//#20#//false : Finaliza transação com sucesso (commit)
//#20#//true  : Transação com erro, desfaz os procedimentos executados (rollback)
  if($erro==true){
      db_query('ROLLBACK');
  }else{
     db_query('COMMIT');
  }
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
function db_input($nome, $dbsize, $dbvalidatipo, $dbcadastro, $dbhidden = 'text', $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "", $css="") {
		//#00#//db_input
		//#10#//Função para montar um input na tela, utilizando a documentação do sistema
		//#15#//db_input($nome,$dbsize,$dbvalidatipo,$dbcadastro,$dbhidden='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="");
		//#20#//Nome            : Nome do campo da documentacao do sistema ou do arquivo
		//#20#//Tamanho         : Tamanho do objeto na tela (default tamanho na documentação)
		//#20#//Validáção       : Tipo de validação JAVASCRIPT para o campo, retirado da documentação 
		//#20#//Cadastro        : True se cadastro ou false se nao cadastro Padrão: true )
		//#20#//Type            : Tipo do objeto INPUT a ser mostrado na tela (text,hidden,file,submit,button,...) Padrão: text
		//#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclusão(1) alteração(2) exclusão(3) 
		//#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os métodos
		//#20#//Nome Secundário : Nome do input que será gerado, assumindo somente as características do campo Nome
		//#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 será "#DEB887"
?>    
  <input title="<?=@$GLOBALS['T'.$nome]?>" name="<?=($nomevar==""?$nome:$nomevar)?>"  type="<?=$dbhidden?>" <?=($dbhidden=="checkbox"?(@$GLOBALS[($nomevar==""?$nome:$nomevar)]=="t"?"checked":""):"")?>
    id="<?=($nomevar==""?$nome:$nomevar)?>"  value="<?=@$GLOBALS[($nomevar==""?$nome:$nomevar)]?>"  size="<?=$dbsize?>" 
	maxlength="<?=@$GLOBALS['M'.$nome]?>" 
  <?



	echo $js_script;
	if ($dbcadastro == true) {
		/*
		    if ($db_opcao==3 || $db_opcao==22 || $db_opcao == 33){ só coloquei a opcao 11...  dia 28-10-2004
		*/
		if ($db_opcao == 3 || $db_opcao == 22 || $db_opcao == 33 || $db_opcao == 11) {
			echo " readonly ";
			if ($bgcolor == "")
				$bgcolor = "#CCCCCC";
		}
		if ($db_opcao == 5) {
			echo " disabled ";
		}
	}
	$db_style = '';
	if ($bgcolor == "") {
		echo " ".@ $GLOBALS['N'.$nome]." ";
	} else {
		//echo " style=\"background-color:$bgcolor\" ";
		$db_style .= "background-color:$bgcolor;";
	}

	if (isset ($GLOBALS['G'.$nome]) && $GLOBALS['G'.$nome] == 't') {
		$db_style .= "text-transform:uppercase;";
	}

	if ($db_style != '') {
	    if ($css!="")
		echo " style=\"$db_style;$css\" ";
            else		
    	        echo " style=\"$db_style\" ";

	} else {
	     if ($css != "")
		echo " style=\"$css\" ";  

	}  

	
	if (($db_opcao != 3) && ($db_opcao != 5)) {
?>
    onblur="js_ValidaMaiusculo(this,'<?=@$GLOBALS['G'.$nome]?>',event);" 
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
//#00#//db_textarea
//#10#//Função para montar um textarea na tela do programa
//#15#//db_textarea($nome,$dbsizelinha=1,$dbsizecoluna=1,$dbvalidatipo,$dbcadastro=true,$dbhidden='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="");
//#20#//Nome            : Nome do campo da documentacao do sistema ou do arquivo
//#20#//Numero Linhas   : Número de linhas do objeto textarea
//#20#//Numero Colunas  : Número de Coluna do objeto textarea
//#20#//Validáção       : Tipo de validação JAVASCRIPT para o campo, retirado da documentação
//#20#//Cadastro        : True se cadastro ou false se nao cadastro Padrão: true )
//#20#//Type            : Tipo do objeto INPUT a ser mostrado na tela (text,hidden,type,submit,...) Padrão: text
//#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclusão(1) alteração(2) exclusão(3)
//#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os métodos
//#20#//Nome Secundário : Nome do input que será gerado, assumindo somente as características do campo Nome
//#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 será "#DEB887"

  ?>
  <textarea title="<?=@$GLOBALS['T'.$nome]?>" name="<?=($nomevar==""?$nome:$nomevar)?>"  type="<?=$dbhidden?>"
    id="<?=($nomevar==""?$nome:$nomevar)?>" rows="<?=$dbsizelinha?>" cols="<?=$dbsizecoluna?>"
  <?
  echo $js_script;
  if($dbcadastro == true){
    if ($db_opcao==3 || $db_opcao==22){
           echo " readonly ";
        if($bgcolor=="")
           $bgcolor= "#DEB887";
    }
    if ($db_opcao==5){
           echo " disabled ";
    }
  }
  if($bgcolor!="")
    echo ' style="background-color:'.$bgcolor.'" ';
  ?>
    onblur="js_ValidaMaiusculo(this,'<?=@$GLOBALS['G'.$nome]?>',event);"
    onKeyUp="js_ValidaCampos(this,<?=($dbvalidatipo==''?0:$dbvalidatipo)?>,'<?=@$GLOBALS['S'.$nome]?>','<?=@$GLOBALS['U'.$nome]?>','<?=@$GLOBALS['G'.$nome]?>',event);"
    <?=@$GLOBALS['N'.$nome]?> autocomplete='<?=@$GLOBALS['A'.$nome]?>'><?=(!isset($GLOBALS[$nome])?"":$GLOBALS[$nome])?></textarea>
  <?
}
function db_ancora($nome, $js_script, $db_opcao, $style = "") {
  //#00#//db_ancora
  //#10#//Coloca uma âncora no Label do campo e executa uma função JAVASCRIPT para pesquisa do arquivo em referencia
  //#15#//db_ancora($nome,$js_script,$db_opcao,$style="");
  //#20#//Nome : Nome do campo da documentação do sistema ou do arquivo
  //#20#//Script : Função JAVASCRIPT que será executado no onclik do objeto label
  //#20#//Opcao : *db_opcao* do programa a ser executado neste objeto input, inclusão(1) alteração(2) exclusão(3)
  //#20#//Style : Opção para programador mudar o estilo da âncora
  if (($db_opcao < 3) || ($db_opcao == 4)) {
  ?>
    <a href='#' class="titulo" onclick="<?=$js_script?>"><?=$nome?></a>
  <?
  } else {
    echo $nome;
  }
}

/*************************************/

function db_selectrecord($nome,$record,$dbcadastro,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$todos="",$onchange="",$numcol=2){
//#00#//db_selectrecord
//#10#//Função para montar um ou dois objetos select na tela, recebendo dados de um recordset
//#15#//db_selectrecord($nome,$record,$dbcadastro,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$todos="",$onchange="",$numcol=2);
//#20#//Nome            : Nome do ca po da documentacao do sistema ou do arquivo
//#20#//Record Set      : Recordset que gerará os objetos select, sendo o primeiro campo do recordset o campo chave
//#20#//                  e o segundo campo a descricao.
//#20#//Cadastro        : True se cadastro ou false se nao cadastro Padrão: true )
//#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclusão(1) alteração(2) exclusão(3)
//#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os métodos
//#20#//Nome Secundário : Nome do input que será gerado, assumindo somente as características do campo Nome
//#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 será "#DEB887"
//#20#//Todos           : Indica de será colocado um ítem inicial com opção de todos "Todos ..." com valor zero (0)
//#20#//OnChange        : Função que será incluída no método onchange dos objetos select, além das funçõe ja incluídas
//#20#//                  que servem para movimentar os select. Sempre que alterar um deles, o sistema altera o outro
//#20#//Numero Select   : Número de select que serão mostrados na tela. O padrão é dois, caso seja indicado este
//#20#//                  parâmetro, o sistema mostrará somente o select do segundo campo (descrição) e retornará o
//#20#//                  código do ítem, o valor do primeiro campo
//#99#//Quando o parâmetro *db_opcao* for de alteração (Opcao = 22) ou exclusão (Opção = 33) o sistema
//#99#//não mostrará os objetos desta função e sim executará o objeto INPUT com as opções deste
//#99#//objeto. Isto faz com que o usuário não movimente um select enquanto não selecionar um
//#99#//código de registro para alterar ou excluir
//#99#//
//#99#//O tamanho do objeto na tela dependerá do tamanho do campo inserido no select
//#99#//
//#99#//Após montar o select, sistema executa uma função javascript para selecionar o elemento
//#99#//do select que possui o mesmo valor do campo indicado na variável Nome
  if($nomevar!=""){
    $nome = $nomevar;
        $nomedescr = $nomevar."descr";
  }else{
        $nomedescr = $nome."descr";
  }
  if($db_opcao != 3 && $db_opcao != 5 && $db_opcao !=22 && $db_opcao != 33){
    if($numcol==2){
    ?>
    <select name="<?=$nome?>" id="<?=$nome?>"
    <?
    if($numcol==2)
      echo "onchange=\"js_ProcCod_$nome('$nome','$nomedescr');$onchange\"";
    else
      echo "onchange=\"$onchange\"";
    if($dbcadastro == true){
      if ($db_opcao==3 || $db_opcao==22){
            echo " readonly ";
          if($bgcolor=="")
            $bgcolor = "#DEB887";
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
      if(strpos($todos,"-")>0)
        $todos=split("-",$todos);
      else
        $todos= array("0"=>$todos,"1"=>"Todos ...");
      ?>
      <option value="<?=$todos[0]?>" ><?=$todos[0]?></option>
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
    }else{
      $nomedescr = $nome;
    }
    if($record!=false && pg_numrows($record)>0 && pg_numfields($record)>0){
      ?>
      <select name="<?=$nomedescr?>" id="<?=$nomedescr?>"
          onchange="js_ProcCod_<?=$nome?>('<?=$nomedescr?>','<?=$nome?>');<?=$onchange?>"
      <?
      if($dbcadastro == true){
        if ($db_opcao==3 || $db_opcao==22){
          echo " readonly ";
          if($bgcolor=="")
            $bgcolor = "#DEB887";

        }
        if ($db_opcao==5){
              echo " disabled ";
        }
      }
      echo $js_script;
      ?>
       >
      <?
      if(is_array($todos) || $todos!=""){
          ?>
          <option value="<?=$todos[0]?>" ><?=$todos[1]?></option>
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
      <?
      if(isset($GLOBALS[$nome])){
        if($GLOBALS[$nome]!=""){
          echo "var sel1 = document.form1.$nome;\n";
          echo "for(var i = 0;i < sel1.options.length;i++) {\n";
          echo "  if(sel1.options[i].value == '".$GLOBALS[$nome]."')\n";
          echo "  sel1.options[i].selected = true;\n";
          echo "}\n";
        }else{
          echo "document.form1.".$nome.".options[0].selected = true;";
        }
      }else{
         echo "document.form1.".$nome.".options[0].selected = true;";
      }
      ?>
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
       if($GLOBALS[$tamm]>40)
         $GLOBALS[$tamm] = 60;
       db_input($nomec,$GLOBALS[$tamm],'',$dbcadastro,'text',3,"");
     }
  }
}
//////////////////////////////////////

function db_selectmultiple($nome,$record,$size,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$record_select="",$onchange=""){
//#00#//db_selectmultiple
//#10#//Função para montar um objeto select do tipo multiple (multiplas linhas) na tela, recebendo dados de um recordset
//#15#//db_selectmultiple($nome,$record,$size,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$record_select="",$onchange="");
//#20#//Nome            : Nome do ca po da documentacao do sistema ou do arquivo
//#20#//Record Set      : Recordset que gera o objeto select, sendo o primeiro campo do recordset o campo chave
//#20#//                  e o segundo campo a descricao que aparecerá na tela
//#20#//Tamanho         : Número de linhas que o objeto ocupará na tela
//#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclusão(1) alteração(2) exclusão(3)
//#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os métodos
//#20#//Nome Secundário : Nome do input que será gerado, assumindo somente as características do campo Nome
//#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 será "#DEB887"
//#20#//Record Set      : Este recordset enviado para a função terá os valores que serão habilitados no objeto select
//#20#//                  multiple, colocandos-os com a propriedade selected habilidata
//#20#//OnChange        : Função ou funções que serão incluídas no método onchange dos objetos select.
//#99#//Quando o parâmetro Opção for de alteração (Opcao = 22) ou exclusão (Opção = 33) o sistema
//#99#//não mostrará os objetos desta função e sim executará o objeto SELECT com as opções do
//#99#//segundo recordset, mostrando somente os dados cadastrados no código de registro para alterar
//#99#//ou excluir
if($nomevar!=""){
    $nome = $nomevar;
  }
  if($db_opcao != 3 && $db_opcao != 5 && $db_opcao!=33 && $db_opcao != 22){
          /*change="js_ProcCod_<?=$nome?>('<?=$nome?>','<?=$nome?>');<?=$onchange?>"tava assim dae eu mudei pra : (ze)*/
    ?>
    <select multiple name="<?=$nome?>[]" size="<?=$size?>" id="<?=$nome?>"
          onchange="<?=$js_script?>"
    <?
    if ($db_opcao==3 || $db_opcao==22){
       echo " readonly ";
       if($bgcolor=="")
         $bgcolor = "#DEB887";
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
//#00#//db_select
//#10#//Função para montar um objeto select na tela, recebendo dados de uma matriz
//#15#//db_select($nome,$db_matriz,$dbcadastro,$db_opcao=3,$js_script="",$nomevar="",$bgcolor="");
//#20#//Nome            : Nome do campo da documentacao do sistema ou do arquivo
//#20#//Matriz          : Matriz com os dados a serem colocados no select, sendo a chave (key) da matriz o valor
//#20#//                  a ser retornado e o conteúdo da matriz o valor a ser mostrado na tela
//#20#//                  ex: $x = array("1"=>"um") 1=key e um=conteúdo;
//#20#//Cadastro        : True se cadastro ou false se nao cadastro Padrão: true )
//#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclusão(1) alteração(2) exclusão(3)
//#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os métodos
//#20#//Nome Secundário : Nome do input que será gerado, assumindo somente as características do campo Nome
//#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 será "#DEB887"
//#99#//Quando o parâmetro Opção for de alteração (Opcao = 22) ou exclusão (Opção = 33) o sistema
//#99#//não mostrará o objeto desta função e sim executará o objeto INPUT e colocará o valor do
//#99#//conteúdo para este bjeto
//#99#//
//#99#//O sistema verifica o valor do campo Nome (conteúdo do campo) e verifica se algum dos
//#99#//campos key da matriz é igual a ele, então coloca a propriedade SELECTED habilitada
//#99#//para este elemento, deixando-o selecionado na tela
  if($db_opcao != 3 && $db_opcao != 5 && $db_opcao!=22 && $db_opcao !=33){
    ?>
    <select name="<?=$nome?>" id="<?=$nome?>"
    <?
    if($dbcadastro == true){
      if ($db_opcao==3 || $db_opcao==22){
           echo " readonly ";
         if($bgcolor=="")
           $bgcolor = "#DEB887";
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
     $nome_select_descr = $nome."_select_descr";
     global $$nome_select_descr,$$nome ;
         $$nome = $GLOBALS[$nome];

         reset($db_matriz);
         for($matsel=0;$matsel<sizeof($db_matriz);$matsel++){
           if(key($db_matriz)==$$nome){
             $$nome_select_descr = $db_matriz[key($db_matriz)];
             $$nome = key($db_matriz);
           }
           next($db_matriz);
         }
         if(strlen($$nome_select_descr)>8){
           if(strlen($$nome_select_descr)>40){
                $tamanho=60;
           }else{
             $tamanho=strlen($$nome_select_descr);
           }
         }else{
           $tamanho=strlen($$nome_select_descr);
         }
         $Mtam = "M$nome";
         global $$Mtam;
         $$Mtam = $tamanho;
         db_input($nome_select_descr,$tamanho+4,'',$dbcadastro,'text',3,"","","");
         db_input($nome,$tamanho+4,'',$dbcadastro,'hidden',3,"","","");
  }
}


function db_inputdata($nome, $dia = "", $mes = "", $ano = "", $dbcadastro = true, $dbtype = 'text', $db_opcao = 3, $js_script = "", $nomevar = "", $bgcolor = "",$shutdown_function="none",$onclickBT="", $onfocus="", $jsRetornoCal=""){
    //#00#//db_inputdata
    //#10#//Função para montar um objeto tipo data. Serão três objetos input na tela mais um objeto input tipo button para 
    //#10#//acessar o calendário do sistema
    //#15#//db_inputdata($nome,$dia="",$mes="",$ano="",$dbcadastro=true,$dbtype='text',$db_opcao=3,$js_script="",$nomevar="",$bgcolor="",$shutdown_funcion="none",$onclickBT="",$onfocus"");
    //#20#//Nome            : Nome do campo da documentacao do sistema ou do arquivo
    //#20#//Dia             : Valor para o objeto |db_input| do dia
    //#20#//Mês             : Valor para o objeto |db_input| do mês
    //#20#//Ano             : Valor para o objeto |db_input| do ano
    //#20#//Cadastro        : True se cadastro ou false se nao cadastro Padrão: true 
    //#20#//Type            : Tipo a ser incluido para a data Padrão: text
    //#20#//Opcao           : *db_opcao* do programa a ser executado neste objeto input, inclusão(1) alteração(2) exclusão(3)
    //#20#//Script          : JAVASCRIPT  a ser executado juntamento com o objeto, indicando os métodos
    //#20#//Nome Secundário : Nome do input que será gerado, assumindo somente as características do campo Nome
    //#20#//Cor Background  : Cor de fundo da tela, no caso de *db_opcao*=3 será "#DEB887"
    //#20#//shutdown_funcion : função que será executada apos o retorno do calendário
    //#20#//onclickBT       : Função que será executada ao clicar no botão que abre o calendário
    //#20#//onfocus         : Função que será executada ao focar os campos
    //#99#//Quando o parâmetro Opção for de alteração (Opcao = 22) ou exclusão (Opção = 33) o sistema
    //#99#//colocará a sem acesso ao calendário
    //#99#//Para *db_opcao* 3 e 5 o sistema colocará sem o calendário e com readonly
    //#99#//
    //#99#//Os três input gerados para a data terão o nome do campo acrescido do [Nome]_dia, [Nome]_mes e
    //#99#//[Nome]_ano os quais serão acessados pela classe com estes nome.
    //#99#//
    //#99#//O sistema gerá para a primeira data incluída um formulário, um objeto de JanelaIframe do nosso
    //#99#//sistema para que sejá mostrado o calendário.

  global $DataJavaScript;
  //  if(!isset($DataJavaScript)) {
  //    $DataJavaScript = new janela("DataJavaScript","");
  //    $DataJavaScript->posX=400;
  //    $DataJavaScript->posY=200;
  //    $DataJavaScript->largura=140;
  //    $DataJavaScript->altura=210;
  //    $DataJavaScript->titulo="Calendário";
  //    $DataJavaScript->iniciarVisivel = false;
  //    $DataJavaScript->scrollbar = "no";
  //    $DataJavaScript->janBotoes = "001";
  //    $DataJavaScript->mostrar();
  //  }
  
  if ($db_opcao == 3 || $db_opcao == 22) {
    $bgcolor = "style='background-color:#DEB887'";
  }

  if ($bgcolor == "") {
    $bgcolor = @$GLOBALS['N'.$nome];
  } 
  
  
  if(isset($dia) && $dia != "" && isset($mes) && $mes != '' && isset($ano) && $ano != ""){
    $diamesano = $dia."/".$mes."/".$ano;
    $anomesdia = $ano."/".$mes."/".$dia;
  }

  $sButtonType = "button";

?>

  <input name="<?=($nomevar==""?$nome:$nomevar).""?>" <?=$bgcolor?>   type="<?=$dbtype?>" id="<?=($nomevar==""?$nome:$nomevar).""?>" <?=($db_opcao==3 || $db_opcao==22 ?'readonly':($db_opcao==5?'disabled':''))?> value="<?=@$diamesano?>" size="10" maxlength="10" autocomplete="off" onBlur='js_validaDbData(this);' onKeyUp="return js_mascaraData(this,event)"  onFocus="js_validaEntrada(this);" <?=$js_script?> >
  <input name="<?=($nomevar==""?$nome:$nomevar)."_dia"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_dia"?>" value="<?=@$dia?>" size="2"  maxlength="2" > 
  <input name="<?=($nomevar==""?$nome:$nomevar)."_mes"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_mes"?>" value="<?=@$mes?>" size="2"  maxlength="2" >
  <input name="<?=($nomevar==""?$nome:$nomevar)."_ano"?>"   type="hidden" title="" id="<?=($nomevar==""?$nome:$nomevar)."_ano"?>" value="<?=@$ano?>" size="4"  maxlength="4" >
  <?
  if (($db_opcao < 3) || ($db_opcao == 4)) {
  ?>
  <script>
  var PosMouseY, PosMoudeX;

  function js_comparaDatas<?=($nomevar==""?$nome:$nomevar).""?>(dia,mes,ano){
    var objData        = document.getElementById('<?=($nomevar==""?$nome:$nomevar).""?>');
    objData.value      = dia+"/"+mes+'/'+ano;
    <?=$jsRetornoCal?>
  } 

  </script>
  <?
   if (isset($dbtype) && strtolower($dbtype) == strtolower('hidden')) {
     $sButtonType = "hidden";
   }
  
  ?>

  <input value="D" type="<?=$sButtonType?>" name="dtjs_<?=($nomevar==""?$nome:$nomevar)?>" onclick="<?=$onclickBT?>pegaPosMouse(event);show_calendar('<?=($nomevar==""?$nome:$nomevar)?>','<?=$shutdown_function?>')"  >

  <?

  }

}
/*************************************/


//////////////////////////////////////
/*function db_data($nome,$dia="",$mes="",$ano="") {
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
  <input name="<?=$nome."_dia"?>" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="text" id="<?=$nome."_dia"?>" value="<?=$dia?>" size="2" maxlength="2" autocomplete="off"><strong>/</strong>
  <input name="<?=$nome."_mes"?>" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="text" id="<?=$nome."_mes"?>" value="<?=$mes?>" size="2" maxlength="2" autocomplete="off"><strong>/</strong>
  <input name="<?=$nome."_ano"?>" onFocus="ContrlDigitos=0" onKeyUp="js_Passa(this.name,<?=date("j")?>,<?=(date("n") - 1)?>,<?=date("Y")?>)" type="text" id="<?=$nome."_ano"?>" value="<?=$ano?>" size="4" maxlength="4" autocomplete="off">
  <input value="D" type="button" name="acessadatajavascript" onclick="pegaPosMouse(event);show_calendar('form1.<?=$nome?>')">
  <?
}*/
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