<?php
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
 

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

require_once("classes/db_obrasalvara_classe.php");

$oDaoObrasAlvara = new cl_obrasalvara;
$oGet            = db_utils::postMemory($_GET);

$oDaoParProjetos = db_utils::getDao('parprojetos');
$sSqlParametros  = $oDaoParProjetos->sql_query_pesquisaParametros( db_getsession('DB_anousu') ); 
$rsParametros    = $oDaoParProjetos->sql_record($sSqlParametros);
if ($oDaoParProjetos->erro_status != "0") {
    $oParametros   = db_utils::fieldsMemory($rsParametros, 0);
      $db_opcao      = 3;
} else {
   db_msgbox(_M('tributario.projetos.pro3_consultaobra002_alvara.paremetros_nao_configurados'));
} 

$iTipoRelatorio = $oParametros->ob21_tipocartaalvara;


/**
 * Solicitação alvara
 */   
$rsObrasAlvara = $oDaoObrasAlvara->sql_record($oDaoObrasAlvara->sql_query(null, "*", "", "ob04_codobra = {$oGet->parametro}"));

if($oDaoObrasAlvara->numrows > 0){

  $oObrasAlvara = db_utils::fieldsMemory($rsObrasAlvara, 0, true);
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <style>
    #elemento_principal {
      width: 100%;
    } 
    #elemento_principal tr td:first-child {
      width: 150px;
    }
  </style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <br />
  <br />
	<fieldset style="width:95%; margin: 0 auto;">
	  <legend><B>Dados do Alvará: </B></legend>
	  <table id="elemento_principal">
    <tr> 
      <td nowrap><strong>Cod. Alvará:</strong></td>
      <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasAlvara->ob04_alvara; ?></td>
    </tr>
    <tr>
      <td nowrap><strong>Data:</strong></td>
      <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasAlvara->ob04_data; ?></td>
    </tr>
  </table>
</fieldset>
<center>
<input name="emite2" id="emite2" type="button" value="Emitir Carta de Alvará" onclick="js_emite(<?=$iTipoRelatorio; ?>);" > 
</center>
<?

/**
 * Se não existir habite-se
 */   
} else { 
	 
	echo "<br /><br />                                              ";
	echo "<center>                                                  ";
	echo "  <strong>Nenhum alvará liberado para está obra.</strong> ";
	echo "</center>                                                 ";
	echo "<br /><br />                                              ";
}
?> 
  <script>
function js_emite(iTipoRelatorio) {

  /**
   * Verifica qual relatório abrir, 0 pdf, 1 office
   */   
  if(iTipoRelatorio == 0) {
    sTipoArquivoRelatorio = "pro2_execobra002.php";
  } else {
    sTipoArquivoRelatorio = "pro2_execobra003.php";
  }

  jan = window.open(sTipoArquivoRelatorio+'?codigo=<?=$oGet->parametro?>',
    '',
    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
  </script>
</body>
</html>