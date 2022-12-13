<?php
/**
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

require_once(modification('libs/db_stdlib.php'));
require_once(modification('libs/db_conecta.php'));
require_once(modification('libs/db_sessoes.php'));
require_once(modification('libs/db_usuariosonline.php'));
require_once(modification('libs/db_utils.php'));
require_once(modification('libs/db_stdlibwebseller.php'));
require_once(modification('dbforms/db_funcoes.php'));

$iUpssolicitante   = db_getsession('DB_coddepto');
$oSauConfig        = loadConfig('sau_config');
$oDepartamento     = DBDepartamentoRepository::getDBDepartamentoByCodigo($iUpssolicitante);
$descrdepto        = $oDepartamento->getNomeDepartamento();
$oAgendaParametros = loadConfig('sau_parametrosagendamento');

if ($oAgendaParametros != null) {
  $s165_formatocomprovanteagend = $oAgendaParametros->s165_formatocomprovanteagend; 
}

$db_opcao_cotas = 1;
$oResult        = getCotasAgendamento($iUpssolicitante, null, null, null, null);

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
<body class="body-default">
<?php
db_menu();
try {
  new UnidadeProntoSocorro(db_getsession("DB_coddepto"));
} catch(\Exception $e) {
  die("<div class='container'><h2>{$e->getMessage()}</h2></div>");
}
?>
<table align="center" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC">
      <br><br>
      <fieldset style='width: 70%;'> <legend><b>Agendamento</b></legend>
        <?php
        require_once(modification("forms/db_frmagendamentounificado.php"));
        ?>
      </fieldset>
    </td>
  </tr>
</table>
<?php
db_menu();
?>
</body>
</html>