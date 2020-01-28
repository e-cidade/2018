<?
/*
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oGet   = db_utils::postMemory($_GET);

$sWhere = "";
$sAnd   = $sWhere != "" ? " and " : "";
if (trim($oGet->ac16_sequencial) != "") {
	$sWhere .= "{$sAnd} ac16_sequencial = ".$oGet->ac16_sequencial;
}

$sAnd = $sWhere != "" ? " and " : "";
if (trim($oGet->ac16_origem) != "" && $oGet->ac16_origem != 0) {
  $sWhere .= "{$sAnd} ac16_origem = ".$oGet->ac16_origem;
}

$sAnd = $sWhere != "" ? " and " : "";
if (trim($oGet->ac16_contratado) != "") {
  $sWhere .= "{$sAnd} ac16_contratado = ".$oGet->ac16_contratado;
}

$sAnd = $sWhere != "" ? " and " : "";
if (trim($oGet->ac16_coddepto) != "") {

	$sWhere .= "{$sAnd} ( ac16_coddepto = {$oGet->ac16_coddepto}";
	$sWhere .= "          or ac16_deptoresponsavel = {$oGet->ac16_coddepto} )";
}

$sCampos    = "ac16_sequencial, (ac16_numeroacordo || '/' || ac16_anousu)::varchar as ac16_numeroacordo, ";
$sCampos   .= "ac16_resumoobjeto, ac16_datainicio, ac16_datafim, descrdepto, z01_nome";
$oDaoAcordo = db_utils::getDao("acordo");
$sSql       = $oDaoAcordo->sql_query(null, $sCampos, null, $sWhere);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<table border="1"  align="center" cellspacing="0" bgcolor="#CCCCCC">
	  <tr>
	    <td valign=top>
		    <table border=0 align=center>
		      <tr> 
		        <td align="center" >
		          <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_consulta.hide();">
		        </td>
		      </tr>  
		    </table>
	    </td>
	  </tr>
	  <tr> 
	    <td align="center" valign="top"> 
	      <?php db_lovrot($sSql, 15, "()", "", $oGet->funcao_js, "", "NoMe", array()); ?>
	     </td>
	  </tr>
	</table>
</body>
</html>
