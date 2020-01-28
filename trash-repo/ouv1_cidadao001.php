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

require("libs/db_utils.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cidadao_classe.php");
include("classes/db_cidadaoemail_classe.php");
include("classes/db_cidadaotelefone_classe.php");
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);
//$clcidadao 					= new cl_cidadao;
//$clcidadaoemail 		= new cl_cidadaoemail;
//$clcidadaotelefone 	= new cl_cidadaotelefone;
$db_opcao = 1;
$db_botao = true;
if(isset($incluir)){
  db_inicio_transacao();
  //$clcidadao->incluir($ov02_sequencial,$ov02_seq);
  db_fim_transacao();
}

$oGet                    = db_utils::postMemory($_GET);
$lHabilitaMenu           = true;
$sStyleProcessado        = "";
$sStylelTelaSocial       = "display:none;";
$iLocalatendimentosocial = '0';

if (isset($oGet->lOrigemLeitor) && $oGet->lOrigemLeitor) {

  $lHabilitaMenu    = false;
  $sStyleProcessado = "display:none;";
}

if (isset($oGet->lTelaSocial) && $oGet->lTelaSocial) {

  $sStylelTelaSocial = "display:block;";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
	db_app::load('prototype.js, strings.js, scripts.js, DBAbas.widget.js, DBAbasItem.widget.js, datagrid.widget.js,
                widgets/windowAux.widget.js, widgets/dbtextField.widget.js, dbViewAvaliacoes.classe.js,
                dbmessageBoard.widget.js,dbautocomplete.widget.js,dbcomboBox.widget.js');
	db_app::load('estilos.css, grid.style.css, DBtab.style.css');
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr align="center">
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
    <center>
    	<?
    	  require_once ("forms/db_frmcidadao.php");
    	?>
    </center>
	</td>
  </tr>
</table>
<?
  if ($lHabilitaMenu) {
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  }
?>
</body>
</html>
<script>
//js_tabulacaoforms("form1","ov02_seq",true,1,"ov02_seq",true);
</script>
<?
if (isset($incluir)) {

  if ($clcidadao->erro_status == "0") {

    $clcidadao->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($clcidadao->erro_campo != "") {

      echo "<script> document.form1.".$clcidadao->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcidadao->erro_campo.".focus();</script>";
    }
  } else {
    $clcidadao->erro(true,true);
  }
}
?>