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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("classes/db_cissqn_classe.php");
require_once("dbforms/db_funcoes.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clcissqn = new cl_cissqn;
$db_opcao = 1;
$db_botao = true;
$lSqlErro = false;

if (isset($oPost->incluir)) {
	
  db_inicio_transacao();
  
  $clcissqn->q04_anousu          = $oPost->q04_anousu;
  $clcissqn->q04_inflat          = $oPost->q04_inflat;
  $clcissqn->q04_vbase           = $oPost->q04_vbase;
  $clcissqn->q04_dtbase          = implode("-", array_reverse(explode("/", $oPost->q04_dtbase)));
  $clcissqn->q04_proced          = $oPost->q04_proced;
  $clcissqn->q04_calfixvar       = $oPost->q04_calfixvar;
  $clcissqn->q04_diasvcto        = $oPost->q04_diasvcto;
  $clcissqn->q04_perccorrepadrao = $oPost->q04_perccorrepadrao;
  $clcissqn->incluir($oPost->q04_anousu);
  if ($clcissqn->erro_status == "0") {
  	$lSqlErro = true;
  }
  
  db_fim_transacao($lSqlErro);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
               dbmessageBoard.widget.js,dbcomboBox.widget.js,datagrid.widget.js,widgets/dbtextFieldData.widget.js");
  db_app::load("estilos.css,grid.style.css");
?>
<style>
td {
  white-space: nowrap;
}

.fildset table td:first-child {
  width: 30%;
  white-space: nowrap;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table border="0" cellspacing="0" cellpadding="0" align="center">
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
  </tr>
  <tr> 
    <td align="center" valign="top">
      <?
        include("forms/db_frmcissqn.php");
      ?>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if (isset($oPost->incluir)) {
	
  if ($clcissqn->erro_status == "0") {
  	
    $clcissqn->erro(true,false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($clcissqn->erro_campo != "") {
    	
      echo "<script> document.form1.".$clcissqn->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clcissqn->erro_campo.".focus();</script>";
    }
  } else {
    $clcissqn->erro(true,true);
  }
}
?>