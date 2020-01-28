<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

include("classes/db_cgm_classe.php");

//MODULO: protocolo
$clcgm     = new cl_cgm;
$clrotulo  = new rotulocampo;
$clprotpro = new cl_protprocesso;

$clprotprocesso->rotulo->label();
$clrotulo->label("p51_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("descrdepto");

$sInstit      = db_getsession("DB_instit");
$sDbIdUsuario = db_getsession("DB_id_usuario");

$result_param=$clprotparam->sql_record($clprotparam->sql_query_file(null,"*",null,"p90_instit=".$sInstit));
if ($clprotparam->numrows>0){
  db_fieldsmemory($result_param,0);
} else {
  $p90_emiterecib='f';
  $p90_alteracgmprot=='f';
}
?>
<script>
function js_testa(opcao) {
  var passa = true;
  document.form1.btnincluir.value = opcao;
  if (opcao == 1) {
    if (document.form1.p90_alteracgmprot.value == 't') {
      if (document.form1.alterou.value != '1') {
	       passa = false;
      }
    }
  }
  if (passa == true) {
    document.form1.submit();
  } else {
    alert('Atualize o cgm do contribuinte!');
    js_AlteraCGM(document.form1.p58_numcgm.value);
  }
}
</script>
<fieldset>
<legend><b>Dados Processo</b></legend>
<center>
<table border="0">
  <tr>
    <td nowrap title="Usu�rio">
      <b>Usu�rio:</b> 
    </td>
    <td> 
     <?
       $sql = "select nome from db_usuarios where id_usuario = ".$sDbIdUsuario;
       echo pg_result(db_query($sql),0,"nome");  
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="Usu�rio">
      <b>Departamento:</b> 
    </td>
    <td> 
     <?
       $sql = "select descrdepto from db_depart where coddepto = ".db_getsession("DB_coddepto");
       echo pg_result(db_query($sql),0,"descrdepto");  
     ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp58_codproc?>">
       <?=@$Lp58_codproc; ?>
    </td>
    <td> 
		<?
		  db_input('p58_codproc',12,$Ip58_codproc,true,'text',3,"");
		?>
  </td>
</tr>
  <tr>
    <td nowrap title="<?=@$Tp58_dtproc;?>">
    <?=@$Lp58_dtproc;?>
    </td>
    <td>
  <?     
      db_inputdata('p58_dtproc',@$p58_dtproc_dia,@$p58_dtproc_mes,@$p58_dtproc_ano,false,'text',3,"","p58_dtproc");
  ?>
   </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp58_hora;?>">
    <?=@$Lp58_hora;?>
    </td>
    <td>
  <?     
  if($db_opcao == 1){
    $p58_hora = db_hora();
    db_input('p58_hora',7,@$Ip58_hora,true,'text','3','');
  }else
      db_input('p58_hora',7,$Ip58_hora,true,'text',3);
  ?>
   </td>
  </tr>
<?
  $op_tip = 1;
  $pesq_p58_codigo1 = "js_pesquisap58_codigo(true)"; 
  $pesq_p58_codigo2 = "js_pesquisap58_codigo(false)";
  
  if($db_opcao==2){
    $op_tip = 2;
    
    if(isset($p58_codproc) && trim($p58_codproc)!=""){
      $sql_tipo = " select p61_codproc as processo1, 
                           p63_codproc as processo2, 
                           p67_codproc as processo3
		                  from protprocesso 
			                     left join procandam        on procandam.p61_codproc        = protprocesso.p58_codproc 
		                       left join proctransferproc on proctransferproc.p63_codproc = protprocesso.p58_codproc 
		                       left join procarquiv       on procarquiv.p67_codproc       = protprocesso.p58_codproc 
	                   where protprocesso.p58_codproc=$p58_codproc 
			                 and procandam.p61_codproc is null
			                 and proctransferproc.p63_codproc is null
			                 and procarquiv.p67_codproc is null";
      $result_tipo = $clprotpro->sql_record($sql_tipo);
      
      if($clprotpro->numrows==0){
        $op_tip = 3;
      }
    }
  }else if($db_opcao==3){
    $op_tip = 3;
  }
?>
  <tr>
    <td nowrap title="<?=@$Tp58_codigo?>">
       <?=db_ancora(@$Lp58_codigo,"$pesq_p58_codigo1",$op_tip);?>
    </td>
    <td> 
<?
  db_input('p58_codigo',5,$Ip58_codigo,true,'text',$op_tip," onchange='$pesq_p58_codigo2'");
?>
<?
  db_input('p51_descr',40,$Ip51_descr,true,'text',$op_tip,'');
if($db_opcao == 1){
  $p58_hora = db_hora();
  db_input('p58_hora',60,@$Ip58_hora,true,'hidden','','');
}
       ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp58_numcgm?>">
       <?
       db_ancora(@$Lp58_numcgm,"js_pesquisap58_numcgm(true);",$db_opcao);
       ?>
    </td>
    <td nowrap> 
<?
$msg_debito="";
if (isset($p58_numcgm)&&$p58_numcgm != ""){
$result_param = $clprotparam->sql_record($clprotparam->sql_query_file());
if ($clprotparam->numrows>0){
	db_fieldsmemory($result_param,0);
	
  	if (@$p90_debiaber=='t'){
  		$data_atual = date('Y-m-d', db_getsession("DB_datausu"));
  		$sWhere = "arrenumcgm.k00_numcgm = {$p58_numcgm} and k00_dtvenc < '{$data_atual}' ";
  		$result_debito = $clarrenumcgm->sql_record($clarrenumcgm->sql_query_deb(null,null,"z01_nome",null,$sWhere));
  		
  		if ($clarrenumcgm->numrows>0){
  			db_fieldsmemory($result_debito,0);
  			$msg_debito="Contribuinte com debito(s) em aberto!!";
  		}  		
 	}
}
}
  db_input('p58_numcgm',5,$Ip58_numcgm,true,'text',3," onchange='js_pesquisap58_numcgm(false);'");

  db_input('z01_nome',40,$Iz01_nome,true,'text',3,'');
?>
    <input name="Alterar CGM" type="button" id="alterarcgm" value="Alterar CGM" 
           onclick="js_AlteraCGM(document.form1.p58_numcgm.value);" <?=($db_botao == false ? "disabled" : "")?>>
    
    </td>
  </tr>
  <tr>
  <td>
  </td>
  <td  >
  <font color='red'>
    <b>    
    <?=@$msg_debito?>    
    </b>
    </font>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp58_requer?>">
       <?=@$Lp58_requer?>
    </td>
    <td> 
<?
  db_input('p58_requer',50,$Ip58_requer,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?=@$Tp58_obs?>">
       <?=@$Lp58_obs?>
    </td>
    <td> 
<?
 db_textarea('p58_obs',10,80,$Ip58_obs,true,'text',$db_opcao,"");
?>
    </td>
  </tr>
 <tr>
  <td colspan=2>
   <fieldset>
    <table>
     <tr>
      <td> 
        <b>CAMPOS COMPLEMENTARES</b>
      </td>
     </tr>
     <tr>
      <td>   
<? 
if ($db_opcao != 22 ){ 
$funcaojava    = null;
$clprocvar     = new cl_procvar;
$cldb_syscampo = new cl_db_syscampo;

$result_andpadrao = $clandpadrao->sql_record($clandpadrao->sql_query(@$p58_codigo));
if($clandpadrao->numrows == 0) {
  echo "<script> \n
            alert('Tipo de processo nao configurado corretamente! Contate suporte!');
            location.href = 'pro4_aba1protprocesso001.php'; \n
	      </script>";
}

$result = $clprocvar->sql_record($clprocvar->sql_query(@$p58_codigo));
if ($clprocvar->numrows > 0) { 
   for ($i = 0 ; $i < $clprocvar->numrows;$i++){
       db_fieldsmemory($result,$i);
       $rscampo = $cldb_syscampo->sql_record($cldb_syscampo->sql_query($p54_codcam));
       db_fieldsmemory($rscampo,0);
       $clrotulo->label("$nomecam");
       
       if ($db_opcao == 2){
          $sql1 = "select p55_conteudo from proctipovar where p55_codproc = $p58_codproc and p55_codcam = $p54_codcam";
          $rsq = db_query($sql1);
          if (pg_num_rows($rsq) > 0){ 
              $$nomecam = pg_result($rsq,0,"p55_conteudo");
          }
       }
       $jl = "L".$nomecam;
       echo "<tr>";
       echo "<td>".$$jl."</td>";
       $xc = $conteudo; 
       $ji = "I$nomecam";
       if (substr($xc,0,4)!="date"){  
          if ( (substr($xc,0,3)=="cha") || ( substr($xc,0,3)=="var") || (substr($xc,0,3)=="flo") ){
            echo "<td>";
            db_input("$nomecam",$tamanho,$$ji,true,'text',$db_opcao,$funcaojava);
            echo "</td></tr>";
          }else if (substr($xc,0,3)=="boo"){
		      $x = array("f"=>"NAO","t"=>"SIM");
              echo "<td>";
		      db_select("$nomecam",$x,true,$db_opcao,$funcaojava);
              echo "</td></tr>"; 
          }else if (substr($xc,0,3)=="tex"){
             echo "<td>";
             db_textarea("$nomecam",0,0,$$ji,true,'text',$db_opcao,$funcaojava); 
             echo "</td></tr>";
	  }else{
               echo "<td>";
               db_input("$nomecam",$tamanho,$$ji,true,'text',$db_opcao,$funcaojava);
               echo "</td></tr>";
          }

       }else{
          $dia = @substr($$nomecam,0,2);
          $mes = @substr($$nomecam,3,2);
          $ano = @substr($$nomecam,6,4);
          echo "<td>";
	         db_inputdata("$nomecam",@$dia,@$mes,@$ano,true,'text',$db_opcao,$funcaojava);
          echo "</td></tr>";
       }
    }
  }
  $chamacgm = true;
}

?> 
    </table>
   </td>
  </tr> 
  <tr>
    <td colspan="3" valign='top'>
       <?
//    include("classes/db_procdoctipo_classe.php");
    $cldoc = new cl_procdoctipo;
    $res = $cldoc->sql_record($cldoc->sql_query(@$p58_codigo,"","p56_coddoc,p56_descr"));
    
    if ($cldoc->numrows > 0) {      
      echo "<fieldset>";
        if ($db_opcao == 1) {
          if (@$p58_codigo != "") {
//            include("classes/db_procdoctipo_classe.php");
//            $cldoc = new cl_procdoctipo;
//            $res = $cldoc->sql_record($cldoc->sql_query($p58_codigo,"","p56_coddoc,p56_descr"));
            $ndocs = "";

            if ($cldoc->numrows > 0) {
              echo "<b>DOCUMENTOS</b><br>";
              
              for ($x = 0; $x < $cldoc->numrows; $x++) {
                db_fieldsmemory($res,$x);
                echo "<input type='checkbox' name='doc$x' onClick='js_valor()' value='$p56_coddoc'><b>$p56_descr</b><br>";
                $ndocs .= $p56_coddoc . "#";
              }
              
            }
          }
        } else if ($db_opcao == 2) {
          if (@$p58_codproc != "") {
            $cldoc   = new cl_procprocessodoc;
            $sqldoc  = "select coalesce(p81_doc, false) as p81_doc,                      ";
            $sqldoc .= "       p56_coddoc,                                               ";
            $sqldoc .= "       p56_descr                                                 ";
            $sqldoc .= "  from procdoctipo                                               ";
            $sqldoc .= "       inner join procdoc          on p56_coddoc  = p57_coddoc   ";
            $sqldoc .= "       left  join procprocessodoc  on p81_coddoc  = p57_coddoc   ";
            $sqldoc .= "                                  and p81_codproc = $p58_codproc ";
            $sqldoc .= " where p57_codigo = $p58_codigo                                  ";
    
            $res = $cldoc->sql_record($cldoc->sql_query($sqldoc));
            
            if ($cldoc->numrows > 0) {
              echo "<b>DOCUMENTOS</b><br>";
              $docs = "";
              $ndocs = "";
              
              for ($x = 0; $x < $cldoc->numrows; $x++) {
                db_fieldsmemory($res,$x);
                echo "<input type='checkbox' name='doc$x' ".($p81_doc == 't'?'checked':'')." 
                             onClick='js_valor()' value='$p56_coddoc'><b>$p56_descr</b><br>";
                
                if ($p81_doc == 't') {
                  $docs .= $p56_coddoc."#";
                } else {
                  $ndocs .= $p56_coddoc."#";
                }
              }
            }
          }
        }
        echo "</fieldset>";
    }
        
        db_input('docs',50,$Ip58_codproc,true,'hidden',3,"");
        db_input('ndocs',50,$Ip58_codproc,true,'hidden',3,"");
        db_input('alterou',10,'',true,'hidden',3);
        db_input('p90_emiterecib',10,$p90_emiterecib,true,'hidden',3);
        db_input('p90_alteracgmprot',10,$p90_alteracgmprot,true,'hidden',3);
        //alterado pro Robson
        db_input('btnincluir',10,"",true,'hidden',3);
        ?>     
    </td>
  </tr>
 </table>
</center>
<?
if ($db_opcao == 1) {
	$sName  = "incluir";
	$sValue = "Incluir";
} else if ($db_opcao == 2) {
	$sName  = "alterar";
	$sValue = "Alterar";
} else {
	$sName  = "excluir";
	$sValue = "Excluir";
}
?>

<input name="<?=$sName?>" type="button" id="<?=$sName?>" value="<?=$sValue?>" <?=($db_botao == false ? "disabled" : "")?> 
       onclick="js_testa(<?=$db_opcao?>)">

<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisadpto();" 
       <?=($db_opcao == 1 ? "disabled" : "")?> >

<input type="button" id="btnAnexarDocumento" value="Anexar Documento" />

</fieldset>
<script>
function js_valor(){
  var cods  = '';
  var ncods = '';
  var iTam  = document.form1.length;
  
  for(i = 0; i < iTam ; i++){
     if(document.form1.elements[i].type == "checkbox"){
       if(document.form1.elements[i].checked == true){
          cods += document.form1.elements[i].value + "#";
       } else {
       ncods += document.form1.elements[i].value + '#';
       }
     }
  }
  
document.form1.docs.value  = cods;
document.form1.ndocs.value = ncods;

}

function js_AlteraCGM(cgm) {
     var sUrl = "prot1_cadcgm002.php?chavepesquisa="+cgm+"&testanome=true&autoprot=true";
     
     db_iframe.jan.location.href = sUrl;
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
     parent.document.formaba.dadosprocesso.disabled      = false;
     parent.document.formaba.processosapensados.disabled = true;
}

function js_pesquisap58_codigo(mostra){
  if(mostra==true){
     var sUrl = "func_tipoproc.php?grupo=1&funcao_js=parent.js_mostratipoproc1|0|1";
     
     db_iframe.jan.location.href = sUrl;
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
     parent.document.formaba.dadosprocesso.disabled      = false;
     parent.document.formaba.processosapensados.disabled = true;
     
  } else {
     var p58_codigo = document.form1.p58_codigo.value;
     var sUrl = "func_tipoproc.php?grupo=1&pesquisa_chave="+p58_codigo+"&funcao_js=parent.js_mostratipoproc";
     
     db_iframe.jan.location.href = sUrl;
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
     parent.document.formaba.dadosprocesso.disabled      = false;
     parent.document.formaba.processosapensados.disabled = true;
  }
}

function js_mostratipoproc(chave,erro){
  document.form1.p51_descr.value = chave; 
  if(erro==true){ 
    document.form1.p58_codigo.focus(); 
    document.form1.p58_codigo.value = ''; 
  }
}

function js_mostratipoproc1(chave1,chave2){
  document.form1.p58_codigo.value = chave1;
  document.form1.p51_descr.value = chave2;
  db_iframe.hide();
}

function js_pesquisap58_numcgm(mostra){
  var permissao_cancelar = <?=db_permissaomenu(db_getsession("DB_anousu"),604,1306)?>;
  if (permissao_cancelar == false) {
  	<? if($p90_alteracgmprot=='t') { 
	       echo "alert('AVISO:\\nUsu�rio sem permiss�o para alterar CGM !\\nCadastro de processo n�o ser� efetuado!');"; 
			   echo "document.form1.alterarcgm.disabled = true;";
			   echo "return false;";
		   }
	  ?>
  }
  
  if(mostra==true){
     var sUrl = "func_nome.php?funcao_js=parent.js_mostracgm1|0|1&testanome=true&incproc=true";
     
     db_iframe.jan.location.href = sUrl;
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
     parent.document.formaba.dadosprocesso.disabled      = false;
     parent.document.formaba.processosapensados.disabled = true;
     
  } else {
     var sUrl = "func_nome.php?pesquisa_chave="+document.form1.p58_numcgm.value+"&funcao_js=parent.js_mostracgm";
     
     db_iframe.jan.location.href = sUrl;
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
     parent.document.formaba.dadosprocesso.disabled      = false;
     parent.document.formaba.processosapensados.disabled = true;
     
  }
}

function js_mostracgm(erro,chave){
  document.form1.z01_nome.value                       = chave;
  document.form1.p58_requer.value                     = chave2;
  parent.document.formaba.dadosprocesso.disabled      = false;
  parent.document.formaba.processosapensados.disabled = true; 
  
  if(erro==true){ 
    document.form1.p58_numcgm.focus(); 
    document.form1.p58_numcgm.value = ''; 
  }else{
  	document.form1.submit();
  }
}

function js_mostracgm1(chave1,chave2){
  document.form1.p58_numcgm.value                     = chave1;
  document.form1.z01_nome.value                       = chave2;
  document.form1.p58_requer.value                     = chave2;
  parent.document.formaba.dadosprocesso.disabled      = false;
  parent.document.formaba.processosapensados.disabled = true; 
  db_iframe.hide();
}

function js_pesquisap58_coddepto(mostra){
  if(mostra==true){
     var sUrl = "func_db_depart.php?funcao_js=parent.js_mostradb_depart1|0|z01_nome";
     
     db_iframe.jan.location.href = sUrl;
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
     parent.document.formaba.dadosprocesso.disabled      = false;
     parent.document.formaba.processosapensados.disabled = true;
     
  } else {
     var p58_coddepto = document.form1.p58_coddepto.value;
     var sUrl = "func_db_depart.php?pesquisa_chave="+p58_coddepto+"&funcao_js=parent.js_mostradb_depart";
     
     db_iframe.jan.location.href = sUrl;
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
     parent.document.formaba.dadosprocesso.disabled      = false;
     parent.document.formaba.processosapensados.disabled = true;
  }
}

function js_mostradb_depart(chave,erro){
  document.form1.descrdepto.value = chave; 
  if(erro==true){ 
    document.form1.p58_coddepto.focus(); 
    document.form1.p58_coddepto.value = ''; 
  }
  parent.document.formaba.dadosprocesso.disabled      = false;
  parent.document.formaba.processosapensados.disabled = true;  
}

function js_mostradb_depart1(chave1,chave2){
  document.form1.p58_coddepto.value = chave1;
  document.form1.descrdepto.value = chave2;
  db_iframe.hide();
  parent.document.formaba.dadosprocesso.disabled      = false;
  parent.document.formaba.processosapensados.disabled = true;  
}

function js_pesquisadpto(){
     var sUrl = "func_protprocessodeptoatual.php?grupo=1&funcao_js=parent.js_preenchepesquisa|0";
     
     db_iframe.jan.location.href = sUrl;
     db_iframe.mostraMsg();
     db_iframe.show();
     db_iframe.focus();
     parent.document.formaba.dadosprocesso.disabled      = false;
     parent.document.formaba.processosapensados.disabled = true;
}

function js_preenchepesquisa(chave){
  location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
  parent.document.formaba.dadosprocesso.disabled      = false;
  parent.document.formaba.processosapensados.disabled = true; 
}



$("btnAnexarDocumento").observe("click", function () {
  
  if ($F("p58_codproc").trim() == "") {
    alert("N�mero do processo n�o informado."); return false;
  }
  js_OpenJanelaIframe("", "iframe_processo_documento", "prot4_processodocumento001.php?iCodigoProcesso="+$F("p58_codproc"), "Anexar Documento", true);
});

</script>
<?
$func_iframe = new janela('db_iframe','');
$func_iframe->posX    = 0;
$func_iframe->posY    = 2;
$func_iframe->largura = 780;
$func_iframe->altura  = 430;
$func_iframe->titulo  = 'Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();

if (isset($incpro) && $incpro == false) {
  if($db_opcao == 22){
    echo "<script>
            onload = js_pesquisadpto();    
          </script>";
    $chamacgm = false;  
  }
  
  if(@$chamacgm == true && $db_opcao != 2 and !isset($p58_numcgm)||$p58_numcgm == ""){  
    echo "<script>
            js_pesquisap58_numcgm(true);       
          </script>";
  }
}

?>