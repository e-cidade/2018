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

require_once("classes/db_obrasconstr_classe.php");
require_once("classes/db_obrashabite_classe.php");

$clobrasconstr  = new cl_obrasconstr;
$clobrashabite  = new cl_obrashabite;
$oGet           = db_utils::postMemory($_GET);

$oDaoParProjetos = db_utils::getDao('parprojetos');
$sSqlParametros  = $oDaoParProjetos->sql_query_pesquisaParametros( db_getsession('DB_anousu') ); 
$rsParametros    = $oDaoParProjetos->sql_record($sSqlParametros);
if ($oDaoParProjetos->erro_status != "0") {
  $oParametros   = db_utils::fieldsMemory($rsParametros, 0);
  $db_opcao      = 3;
} else {
 db_msgbox(_M('tributario.projetos.pro3_consultaobra002_habite.parametros_nao_configurados'));
}

$iTipoRelatorio = $oParametros->ob21_tipocartahabite;

/**
 * Sql tabela obrascontr
 */
$sqlObrasConstr = $clobrasconstr->sql_query(null, "*", "", "ob08_codobra = $parametro");
$rsObrasConstr  = $clobrasconstr->sql_record($sqlObrasConstr);

if($clobrasconstr->numrows > 0) {

	$oObrasConstr = db_utils::fieldsMemory($rsObrasConstr, 0);

	/**
	 * Sql tabela obrashabite
	 */
	$sqlObrasHabite = $clobrashabite->sql_query_file(null, "*", "", "ob09_codconstr = $oObrasConstr->ob08_codconstr");
	$rsObrasHabite  = $clobrashabite->sql_record($sqlObrasHabite);

	/**
	 * Verifica se existe dados na tabela obrashabite
	 */
	if($clobrashabite->numrows > 0) {
		$oObrasHabite = db_utils::fieldsMemory($rsObrasHabite, 0);
	}
}

if($clobrashabite->numrows > 0) {

	if($oObrasHabite->ob09_parcial == "t") {
		$tipo = "Parcial";
	}else {
		$tipo = "Total";
	}
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
    <legend><B>Dados do Habite-se: </B></legend>
  
	  <table id="elemento_principal">
	    <tr>
	      <td nowrap><b>Habite-se:</b></td>
	      <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasHabite->ob09_habite; ?></td>
	    </tr>
	    <tr>
	      <td nowrap><b>Data do habite-se:</b></td>
	      <td nowrap bgcolor="#FFFFFF"><?php echo db_formatar($oObrasHabite->ob09_data, "d")?></td>
	    </tr>
	    <tr>
	      <td nowrap><strong>Área:</strong></td>
	      <td nowrap bgcolor="#FFFFFF"><?php echo $oObrasHabite->ob09_area; ?></td>
	    </tr>
	    <tr>
	      <td nowrap><strong>Tipo de habite-se:</strong></td>
	      <td nowrap bgcolor="#FFFFFF"><?php echo $tipo; ?></td>
	    </tr>    
	    <tr>
	      <td nowrap><strong>Observações:</strong></td>
	      <td colspan=3 align="left" nowrap bgcolor="#FFFFFF"><?php echo $oObrasHabite->ob09_obs?></td>
	    </tr>
	  </table>
  </fieldset>
  <center>
  <input name="emite2" id="emite2" type="button" value="Emitir Carta de Habite-se" onclick="js_emite(<?=$iTipoRelatorio?>);" > 
  </center>
<?

  /**
   * Se não existir habite-se
   */
} else {

	echo "<br />                                              ";
	echo "<br />                                              ";
	echo "<center>                                            ";
	echo "  <strong>Construção não possui habite-se.</strong> ";
	echo "</center>                                           ";
} 
?>
</body>
</html>
<script>
function js_emite(iTipoRelatorio) {
 
   /**
    * Verifica qual relatório abrir, 0 pdf, 1 office
    */   
   if(iTipoRelatorio == 0) {
     sTipoArquivoRelatorio = "pro2_cartahabite002.php";
   } else {
     sTipoArquivoRelatorio = "pro2_cartahabite003.php";
   }

   jan = window.open(sTipoArquivoRelatorio+'?codigo=<?=$oGet->parametro?>',
                      '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
}
</script>