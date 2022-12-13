<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

require_once("classes/db_caracter_classe.php");
require_once("classes/db_obras_classe.php");
require_once("classes/db_obrasalvara_classe.php");
require_once("classes/db_obrasconstr_classe.php");
require_once("classes/db_obrasender_classe.php");
require_once("classes/db_obrasiptubase_classe.php");
require_once("classes/db_parprojetos_classe.php");

$oPost             = db_utils::postMemory($HTTP_POST_VARS);
$oGet              = db_utils::postMemory($HTTP_GET_VARS);

$oDaoObrasAlvara   = new cl_obrasalvara;
$oDaoObrasConstr   = new cl_obrasconstr;
$oDaoObrasIPTUBase = new cl_obrasiptubase;
$oDaoObrasEnder    = new cl_obrasender;
$oDaoParProjetos   = new cl_parprojetos;
$oDaoCaracter      = new cl_caracter;
$oDaoObras         = new cl_obras;
                   
$iDBOpcao          = 1;
$db_botao          = true;
$sqlerro           = false;

if(isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"]=="Incluir"){
	db_inicio_transacao();
	$clobrasconstr->incluir($ob08_codconstr);
	if($clobrasconstr->erro_status == "0"){
		$sqlerro = true;
		$erro_msg = $clobrasconstr->erro_msg;
	}else{
		$ok_msg = $clobrasconstr->erro_msg;
	}
	$ob08_codconstr = $clobrasconstr->ob08_codconstr;
	$clobrasender->ob07_codobra = $ob08_codobra;
	$clobrasender->ob07_codconstr = $ob08_codconstr;
	if($sqlerro == false){
		$clobrasender->incluir($ob08_codconstr);
		if($clobrasender->erro_status == "0"){
			$sqlerro = true;
			$erro_msg = $clobrasender->erro_msg;
		}
	}
	db_fim_transacao($sqlerro);
}

if ($iDBOpcao == 1) {
	$ob07_unidades   = 1;
	$ob07_pavimentos = 1;
}

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
		<? 
		 db_app::load("scripts.js");
		 db_app::load("strings.js");
		 db_app::load("estilos.css"); 
		 db_app::load("prototype.js");
		 db_app::load("datagrid.widget.js");
		 db_app::load("dbmessageBoard.widget.js");
		 db_app::load("grid.style.css");
		 db_app::load("windowAux.widget.js");
		 db_app::load("dbcomboBox.widget.js");
		 	
		 ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
      <tr> 
        <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
        <center>
		    	<?
		        include("forms/db_frmobrasconstr.php");
		    	?>
        </center>
    	</td>
      </tr>
    </table>
  </body>
</html>
<?
if ( isset($HTTP_POST_VARS["db_opcao"]) && $HTTP_POST_VARS["db_opcao"] == "Incluir") {
	
	if ( $sqlerro == true ) {
		
		db_msgbox($erro_msg);
		
		if($clobrasconstr->erro_campo!=""){
			
			echo "<script> document.form1.".$clobrasconstr->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clobrasconstr->erro_campo.".focus();</script>";
		};
	} else {

		db_msgbox($ok_msg);
        if(isset($func_alvara)){
      echo "<script>location.href='pro1_obrasconstr001.php?func_alvara=1&ob08_codconstr=".$clobrasconstr->ob08_codconstr."&ob08_codobra=$ob08_codobra&abas=1'</script>";
    }else{
		echo "<script>parent.iframe_constr.location.href='pro1_obrasconstr001.php?ob08_codconstr=".$clobrasconstr->ob08_codconstr."&ob08_codobra=$ob08_codobra&abas=1'</script>";
	}
  };
};
?>