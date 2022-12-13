<?
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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);

$oDaoFarParametros = db_utils::getdao('far_parametros');
$oDaoFarClass      = db_utils::getdao('far_class');
$oDaoFarMaterSaude = db_utils::getdao('far_matersaude');
$db_opcao          = 1;
$db_botao          = true;
$db_opcao1         = 1;

if (isset($incluir)) {

  db_inicio_transacao();
  $oDaoFarParametros->incluir($fa02_i_codigo);
  db_fim_transacao($oDaoFarParametros->erro_status == '0' ? true : false);

} elseif (isset($alterar)) {

  db_inicio_transacao();

  if (empty($fa02_i_acaoprog)) {
    $oDaoFarParametros->fa02_i_acaoprog = 'null';
  }
  $oDaoFarParametros->fa02_i_dbestrutura = $fa02_i_dbestrutura;
  $oDaoFarParametros->fa02_c_descr       = $fa02_c_descr;
  $oDaoFarParametros->alterar($fa02_i_codigo);
  db_fim_transacao($oDaoFarParametros->erro_status == '0' ? true : false);

} else {

  $sSql = $oDaoFarParametros->sql_query2();
  $rs   = $oDaoFarParametros->sql_record($sSql); 
  if ($oDaoFarParametros->numrows == 0) {
    $db_opcao = 1;
  } else {

    $db_opcao = 2;
    db_fieldsmemory($rs, 0);

  }

  $sSql = $oDaoFarClass->sql_query();
  $rs   = $oDaoFarClass->sql_record($sSql);
  if ($oDaoFarClass->numrows > 0) {

    $db_opcao1 = 3;  
    db_fieldsmemory($rs, 0);

  } else {
    $db_opcao1 = 1;  
  }

  if ((!isset($fa02_i_avisoretirada)) || ($fa02_i_avisoretirada == '')) { 
    $fa02_i_avisoretirada = 0; 
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
fieldset .fieldsetSeparator {
 
   border:0px;
   border-top:2px groove white;
   
 }
 fieldset .fieldsetSeparator select {
 
   width:100%;
   
 }
 fieldset .fieldsetSeparator table tr td:first-child {
  	
  	width: 250px;
  	white-space: nowrap;
 }
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="top" bgcolor="#CCCCCC"> 
      <br>
      <center>
        <?
        require_once("forms/db_frmfar_parametros.php");
        ?>
      </center>
    </td>
  </tr>
</table>
<?
/*db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"), 
          db_getsession("DB_anousu"), db_getsession("DB_instit")
         );*/
?>
</body>
</html>
<script>
js_tabulacaoforms("form1", "fa02_i_dbestrutura", true, 1, "fa02_i_dbestrutura", true);
</script>
<?
if (isset($incluir) || isset($alterar)) {

  if ($oDaoFarParametros->erro_status == '0') {

    $oDaoFarParametros->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ($oDaoFarParametros->erro_campo != '') {

      echo "<script> document.form1.".$oDaoFarParametros->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoFarParametros->erro_campo.".focus();</script>";

    }

  } else {
    $oDaoFarParametros->erro(true, true);
  }

}
?>