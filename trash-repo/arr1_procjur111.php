<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_procjurjudicialadvog_classe.php");
include("dbforms/db_classesgenericas.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$clprocjurjudicialadvog   = new cl_procjurjudicialadvog();
$cliframe_alterar_excluir = new cl_iframe_alterar_excluir;

$clprocjurjudicialadvog->rotulo->label();
$lSqlErro = false;

if(isset($oPost->incluir)){
	
  db_inicio_transacao();

  $clprocjurjudicialadvog->incluir(null);
  
  if ($clprocjurjudicialadvog->erro_status == 0){
  	$lSqlErro = true;  
  }
  
  $sErroMsg = $clprocjurjudicialadvog->erro_msg;
  
  db_fim_transacao($lSqlErro);
    
}else if(isset($oPost->alterar)){
	
  db_inicio_transacao();
  
  $rsVerificaPrincipal = $clprocjurjudicialadvog->sql_record($clprocjurjudicialadvog->sql_query_file($oPost->v65_sequencial));  
  
  if ($clprocjurjudicialadvog->numrows > 0) {
  	
    $oVerificaPrincipal  = db_utils::fieldsMemory($rsVerificaPrincipal,0);
  
    if ( $oVerificaPrincipal->v65_principal == "t" ) {
  	
  	  if ( $oPost->v65_principal == "false" ) {
  	  	
  	   	$rsAltera = $clprocjurjudicialadvog->sql_record($clprocjurjudicialadvog->sql_query_file(null,"*",null," v65_principal is false and v65_procjurjudicial = {$oPost->v65_procjurjudicial}"));
	  
	    if ($clprocjurjudicialadvog->numrows > 0) {
	      $oAltera  = db_utils::fieldsMemory($rsAltera,0);
	      $clprocjurjudicialadvog->v65_sequencial		= $oAltera->v65_sequencial;
  	      $clprocjurjudicialadvog->v65_advog			= $oAltera->v65_advog;
  	      $clprocjurjudicialadvog->v65_procjurjudicial  = $oAltera->v65_procjurjudicial;
	      $clprocjurjudicialadvog->v65_principal 		= "true";
	      $clprocjurjudicialadvog->alterar($oAltera->v65_sequencial);
	    }
	  
  	  }
  	
    } else {
      if ( $oPost->v65_principal == "true" ) {
      	
        $rsAltera = $clprocjurjudicialadvog->sql_record($clprocjurjudicialadvog->sql_query_file(null,"*",null," v65_principal is true and v65_procjurjudicial = {$oPost->v65_procjurjudicial} "));
	  
	    if ($clprocjurjudicialadvog->numrows > 0) {
	      $oAltera  = db_utils::fieldsMemory($rsAltera,0);
	      $clprocjurjudicialadvog->v65_sequencial		= $oAltera->v65_sequencial;
  	      $clprocjurjudicialadvog->v65_advog			= $oAltera->v65_advog;
  	      $clprocjurjudicialadvog->v65_procjurjudicial  = $oAltera->v65_procjurjudicial;
	      $clprocjurjudicialadvog->v65_principal 		= "false";
	      $clprocjurjudicialadvog->alterar($oAltera->v65_sequencial);
	    }  		
	  
  	  }    	
    	
    }
    
  }
  $clprocjurjudicialadvog->v65_sequencial		= $oPost->v65_sequencial;
  $clprocjurjudicialadvog->v65_advog			= $oPost->v65_advog;
  $clprocjurjudicialadvog->v65_procjurjudicial  = $oPost->v65_procjurjudicial;
  $clprocjurjudicialadvog->v65_principal		= $oPost->v65_principal;  
  $clprocjurjudicialadvog->alterar($oPost->v65_sequencial);
  
  if ($clprocjurjudicialadvog->erro_status == 0){
  	$lSqlErro = true;  
  }
  
  $sErroMsg = $clprocjurjudicialadvog->erro_msg;
  
  db_fim_transacao($lSqlErro);
  
}else if(isset($oPost->excluir)){

  db_inicio_transacao();

  $rsVerificaPrincipal = $clprocjurjudicialadvog->sql_record($clprocjurjudicialadvog->sql_query_file(null,"*",null," v65_sequencial = {$oPost->v65_sequencial} and v65_principal is true "));  
  
  if ( $clprocjurjudicialadvog->numrows > 0 ) {
  		
    $rsAltera = $clprocjurjudicialadvog->sql_record($clprocjurjudicialadvog->sql_query_file(null,"*",null," v65_principal is false and v65_procjurjudicial = {$oPost->v65_procjurjudicial}"));
	if ($clprocjurjudicialadvog->numrows > 0) {
      $oAltera  = db_utils::fieldsMemory($rsAltera,0);
	  $clprocjurjudicialadvog->v65_sequencial		= $oAltera->v65_sequencial;
  	  $clprocjurjudicialadvog->v65_advog			= $oAltera->v65_advog;
  	  $clprocjurjudicialadvog->v65_procjurjudicial  = $oAltera->v65_procjurjudicial;
	  $clprocjurjudicialadvog->v65_principal 		= "true";
	  $clprocjurjudicialadvog->alterar($oAltera->v65_sequencial);
	}
  }  
  
  $clprocjurjudicialadvog->v65_sequencial		= $oPost->v65_sequencial;
  $clprocjurjudicialadvog->v65_advog			= $oPost->v65_advog;
  $clprocjurjudicialadvog->v65_procjurjudicial  = $oPost->v65_procjurjudicial;
  $clprocjurjudicialadvog->v65_principal		= $oPost->v65_principal;  
  $clprocjurjudicialadvog->excluir($oPost->v65_sequencial);
  
  if ($clprocjurjudicialadvog->erro_status == 0){
  	$lSqlErro = true;  
  }
  
  $sErroMsg = $clprocjurjudicialadvog->erro_msg;  

  db_fim_transacao($lSqlErro);	
	
	
}else if(isset($opcao)){
	
   $rsAdvog = $clprocjurjudicialadvog->sql_record($clprocjurjudicialadvog->sql_query($oPost->v65_sequencial));	
   if($clprocjurjudicialadvog->numrows > 0){
     db_fieldsmemory($rsAdvog,0);
   }
   
} else if (isset($oGet->codProcjur)){
  
  $v65_procjurjudicial = $oGet->codProcjur;
  
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?



if(isset($oPost->opcao) && $oPost->opcao=="alterar"){
	
  $db_botao			 = true;
  $db_opcao 		 = 2;
  $db_opcaoPrincipal = 2;
  
}else if(isset($oPost->opcao) && $oPost->opcao=="excluir"){
	
  $db_botao			 = true;
  $db_opcao 		 = 3;
  $db_opcaoPrincipal = 3;
  
}else{
	
  $rsPrincipal = $clprocjurjudicialadvog->sql_record($clprocjurjudicialadvog->sql_query_file(null,"*",null," v65_principal is true and v65_procjurjudicial = {$v65_procjurjudicial}"));
  
  if ($clprocjurjudicialadvog->numrows > 0) {
    $db_opcaoPrincipal = 3;
    $v65_principal	   = "false";
  } else {
    $db_opcaoPrincipal = 3;
    $v65_principal	   = "true";  	
  }
  	
  $db_opcao 		 = 1;
  $db_botao 		 = true;
  	
  if(isset($oPost->novo) || isset($oPost->alterar) ||   isset($oPost->excluir) || (isset($oPost->incluir) && $lSqlErro==false ) ){
    $v65_sequencial = "";    
    $v65_advog	    = "";
    $z01_nome	    = "";
  }
} 
?>
<table  align="center" style="padding-top:15px;">
  <tr>
    <td>
      <form name="form1" method="post" action="">
        <table border="0" align="center">
  		<tr>
		  <td nowrap title="<?=@$Tv65_advog?>">
		    <?
			  db_ancora("<b>Código do CGM que identifica o advogado</b>","js_advog(true);",$db_opcao);		    
		    ?>
		  </td>
		  <td> 
		    <?
		      db_input('v65_sequencial',10,"",true,'hidden',3,'');
		      db_input('v65_procjurjudicial',10,"",true,'hidden',3,'');
		      db_input('v65_advog',10,$Iv65_advog,true,'text',$db_opcao," onchange='js_advog(false);'");
		      db_input('z01_nome',40,"",true,'text',3,'');
		    ?>
		  </td>
	    </tr>		
  		<tr>
  		  <td>
  		    <b>Principal</b>		  
  		  </td>
		  <td> 
		    <?
		    
		      if (isset($oPost->v65_principal) && $oPost->v65_principal == "t"){
		      	$v65_principal = "true";
		      } else if (isset($oPost->v65_principal) && $oPost->v65_principal == "f"){
		      	$v65_principal = "false";
		      }
		      
		      $aPrincipal = array("true"=>"Sim","false"=>"Não");
		      db_select("v65_principal",$aPrincipal,true,$db_opcaoPrincipal,"style='width:80px;'");
		    ?>
		  </td>		  
	    </tr>		
  		<tr>
    	  <td colspan="2" align="center">
			<input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?>  >
		    <input name="novo" type="button" id="cancelar" value="Novo" onclick="js_cancelar();" <?=($db_opcao==1?"style='visibility:hidden;'":"")?> >
		  </td>
  		</tr>
  	  </table>
 	  <table>
		<tr>
		  <td valign="top"  align="center">  
		    <?
			  $aChavePri = array("v65_sequencial"=>@$v65_sequencial,"v65_advog"=>@$v65_advog,"v65_principal"=>@$v65_principal);
			  
			  $cliframe_alterar_excluir->chavepri= $aChavePri;
			  $cliframe_alterar_excluir->sql     = $clprocjurjudicialadvog->sql_query(null,"*","v65_sequencial"," v65_procjurjudicial = {$oGet->codProcjur}");
			  $cliframe_alterar_excluir->campos  ="v65_advog,z01_nome,v65_principal";
			  $cliframe_alterar_excluir->legenda ="Advogados";
			  $cliframe_alterar_excluir->iframe_height ="160";
			  $cliframe_alterar_excluir->iframe_width ="700";
			  $cliframe_alterar_excluir->iframe_alterar_excluir($db_opcao);
		    ?>
		  </td>
   		</tr>
   	    </table>
        </form>
      </td>
    </tr>
  </table>   
</body>
</html>
<?
if(isset($oPost->alterar) || isset($oPost->excluir) || isset($oPost->incluir)){
    db_msgbox($sErroMsg);
    if($clprocjurjudicialadvog->erro_campo!=""){
        echo "<script> document.form1.".$clprocjurjudicialadvog->erro_campo.".style.backgroundColor='#99A9AE';</script>";
        echo "<script> document.form1.".$clprocjurjudicialadvog->erro_campo.".focus();</script>";
    }
}

?>
<script>

function js_advog(mostra){
  if(mostra == true){
    js_OpenJanelaIframe('','db_iframe_advog','func_advog.php?funcao_js=parent.js_preencheadvog|v57_numcgm|z01_nome','pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_advog','func_advog.php?funcao_js=parent.js_preencheadvog1&pesquisa_chave='+document.form1.v65_advog.value,'pesquisa',false);
  }
}

function js_preencheadvog(chave,chave1){
  document.form1.v65_advog.value = chave;
  document.form1.z01_nome.value  = chave1;
  db_iframe_advog.hide();
}

function js_preencheadvog1(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro == true){
    document.form1.v65_advog.focus();
    document.form1.v65_advog.value='';
  }
  db_iframe_advog.hide();
}

function js_cancelar(){
  var opcao = document.createElement("input");
  opcao.setAttribute("type","hidden");
  opcao.setAttribute("name","novo");
  opcao.setAttribute("value","true");
  document.form1.appendChild(opcao);
  document.form1.submit();
}
</script>