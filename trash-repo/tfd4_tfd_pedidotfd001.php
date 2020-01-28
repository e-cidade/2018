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

//ABA PEDIDO
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

db_postmemory($HTTP_POST_VARS);

$oDaoCgsUnd        = db_utils::getdao('cgs_und');
$oDaoUnidades      = db_utils::getdao('unidades');
$oDaoTfdParametros = db_utils::getdao('tfd_parametros');
$db_opcao          = 1;

$sSqlUnid          = $oDaoUnidades->sql_query_file(db_getsession('DB_coddepto'));
$oDaoUnidades->sql_record($sSqlUnid);

if ($oDaoUnidades->numrows == 0) {
  die('<center><br><br><b><big>Departamento não está cadastrado como UPS no módulo Ambulatorial!</big></b></center>');
}

$rsParametros = $oDaoTfdParametros->sql_record($oDaoTfdParametros->sql_query_file());

if ($oDaoTfdParametros->numrows > 0) {
  $oParametros = db_utils::fieldsmemory($rsParametros, 0);
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<?
db_app::load("prototype.js, datagrid.widget.js, strings.js, webseller.js");
db_app::load(" grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
        <fieldset style='width: 92%;'> <legend><b>Pedido</b></legend>
      	  <?
	        require_once("forms/db_frmtfd_pedidotfdini.php");
	        ?>
        </fieldset>
      </center>
	  </td>
  </tr>
</table>
</center>

</body>
</html>
<script>
<?
switch ($oParametros->tf11_i_campofoco) {

  case 1:

    $sCampoFoco = 'tf01_i_cgsund';
    break;

  case 2:

    $sCampoFoco = 's115_c_cartaosus2';
    break;

  case 3:

    $sCampoFoco = 'tf30_i_encaminhamento';
    break;

  case 4:

    $sCampoFoco = 'tf29_i_prontuario';
    break;

   default:
     
     $sCampoFoco = 'tf01_i_cgsund';

}
?>
js_tabulacaoforms('form1', '<?=$sCampoFoco?>', true, 1, '<?=$sCampoFoco?>', true);
</script>