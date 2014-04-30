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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_usuariosonline.php');
require_once('libs/db_utils.php');
require_once('libs/db_stdlibwebseller.php');
require_once('dbforms/db_funcoes.php');

$oDaoUnidades   = db_utils::getdao('unidades');
$iUpssolicitante = db_getsession('DB_coddepto');
$descrdepto     = db_getsession('DB_nomedepto');
$oSauConfig     = loadConfig('sau_config');

$sSqlUnid       = $oDaoUnidades->sql_query_file($iUpssolicitante);
$oDaoUnidades->sql_record($sSqlUnid);

if ($oDaoUnidades->numrows == 0) {
  die('<center><br><br><b><big>Departamento não está cadastrado como UPS no módulo Ambulatorial!</big></b></center>');
}

$oAgendaParametros = loadConfig('sau_parametrosagendamento');
if ($oAgendaParametros != null) {
  $s165_formatocomprovanteagend = $oAgendaParametros->s165_formatocomprovanteagend; 
}

$db_opcao_cotas = 1;
$oResult = getCotasAgendamento($iUpssolicitante, null, null, null, null);
if ($oResult->lStatus != 1) {

  $sd02_i_codigo = $iUpssolicitante;
  $db_opcao_cotas = 3;
  
} else {
	 
  $sd02_i_codigo  = "";
  $descrdepto     = ""; 
  	
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/webseller.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table align="center" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
      <br><br>
      <fieldset style='width: 70%;'> <legend><b>Agendamento</b></legend>
        <?
        require_once("forms/db_frmagendamentounificado.php");
        ?>
      </fieldset>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession('DB_id_usuario'), db_getsession('DB_modulo'), 
        db_getsession('DB_anousu'), db_getsession('DB_instit')
       );
?>
</body>
</html>
<script>
  js_tabulacaoforms('form1', 'sd02_i_codigo', true, 1, 'sd02_i_codigo' ,true);
</script>