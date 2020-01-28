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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oGet   = db_utils::postMemory($_GET);

$sWhere = "e60_numemp = {$oGet->e60_numemp}";

$sCampos    = "ac16_sequencial, ac16_numero, ac16_anousu, ac16_resumoobjeto, ac16_datainicio, ac16_datafim, descrdepto ,z01_nome";
$oDaoAcordo = db_utils::getDao("acordo");
$sSql       = $oDaoAcordo->sql_query_empenho(null, $sCampos, null, $sWhere);

/* 
$sSqlAcordo  = " select distinct
                        ac16_sequencial,
                        ac16_datainicio,
                        ac16_datafim,
                        ac16_objeto
                   from acordo
             inner join empempenhocontrato on acordo.ac16_sequencial = empempenhocontrato.e100_acordo
                  where e100_numemp = {$e60_numemp} "; 
 */


$sSqlAcordo  = "select     distinct             ";
$sSqlAcordo .= "           ac16_sequencial,     ";
$sSqlAcordo .= "           ac16_datainicio,     ";
$sSqlAcordo .= "           ac16_datafim,        ";
$sSqlAcordo .= "           ac16_objeto          ";
$sSqlAcordo .= "      from acordo               ";
$sSqlAcordo .= " left join acordoempautoriza on acordo.ac16_sequencial = acordoempautoriza.ac45_acordo ";
$sSqlAcordo .= " left join empautoriza       on acordoempautoriza.ac45_empautoriza = empautoriza.e54_autori ";
$sSqlAcordo .= " left join empempaut         on empautoriza.e54_autori = empempaut.e61_autori ";
$sSqlAcordo .= "inner join empempenhocontrato on acordo.ac16_sequencial = empempenhocontrato.e100_acordo" ;
$sSqlAcordo .= " where e100_numemp = {$e60_numemp} ";


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name=form1  action="" method=POST>
	<table border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
	  <tr> 
	    <td align="center" valign="top"> 
	      <?
	        $totalizacao = array();
	        
	       // echo "<br><br>{$sSqlAcordo}<br>";
	        
	        db_lovrot($sSqlAcordo,15,"()","","js_mostraContrato|ac16_sequencial","","NoMe", array(),false, $totalizacao);
	      ?>
	     </td>
	  </tr>
	</table>
</form>
</body>
</html>

</html>
<script>
	      
  function js_mostraContrato(iAcordo) {

     js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_pesquisacontrato',
                        'con4_consacordos003.php?lEmpenho=1&ac16_sequencial='+iAcordo,
                        //'func_empempenho001.php?e60_numemp='+iEmpenho,
                        'Dados do Contrato',
                        true
                       );  
  }
</script>