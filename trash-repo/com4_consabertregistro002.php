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
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);


$sWhere = "";

$and = $sWhere != "" ? " and " : "";

if (trim($oGet->pc10_numero) != "") {
	
	$sWhere .= $and." pc10_numero = ".$oGet->pc10_numero;
}

$and = $sWhere != "" ? " and " : "";
 
if (trim($oGet->dtini) != "" && trim($oGet->dtfim) != "") {
  
  $sWhere .= $and." pc10_data between '".$oGet->dtini."' and '".$oGet->dtfim."'";
  
} else if (trim($oGet->dtini) != "") {
	
	$sWhere .= $and." pc10_data >= '".$oGet->dtini."'";
	
} else if (trim($oGet->dtfim) != "") {
  
  $sWhere .= $and." pc10_data =< '".$oGet->dtfim."'";
  
}

$and = $sWhere != "" ? " and " : "";

if (trim($oGet->pc10_depto) != "") {
  
  $sWhere .= $and." pc10_depto = ".$oGet->pc10_depto;
}

$and     = $sWhere != "" ? " and " : "";
$sWhere .= $and." pc10_solicitacaotipo  = 3";
if ($oGet->pc54_estimativa != "") {
  
  $and     = $sWhere != "" ? " and " : "";  
  $sWhere .= "{$and} pc10_numero in (select pc53_solicitapai ";
  $sWhere .= "                         from solicitavinculo ";
  $sWhere .= "                              inner join solicita on pc10_numero = pc53_solicitafilho ";
  $sWhere .= "                        where pc53_solicitafilho = {$oGet->pc54_estimativa}";
  $sWhere .= "                          and pc10_solicitacaotipo = 4)";
}
if ($oGet->pc54_compilacao != "") {
  
  $and     = $sWhere != "" ? " and " : "";  
  $sWhere .= "{$and} pc10_numero in (select pc53_solicitapai ";
  $sWhere .= "                         from solicitavinculo ";
  $sWhere .= "                              inner join solicita on pc10_numero = pc53_solicitafilho ";
  $sWhere .= "                        where pc53_solicitafilho = {$oGet->pc54_compilacao}";
  $sWhere .= "                          and pc10_solicitacaotipo = 6)";
}
$sCampos = "pc10_numero, pc10_data, pc10_depto, descrdepto ";
$oDaoSolicitaRegistroPreco = db_utils::getDao("solicitaregistropreco");

$sSql = $oDaoSolicitaRegistroPreco->sql_query(null, $sCampos, null, $sWhere);

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="1"  align="center" cellspacing="0" bgcolor="#CCCCCC">
   <!--- filtro --->
  <form name=form1  action="" method=POST>
  <tr><td valign=top>
  <table border=0 align=center>
     <tr> 
       <td align="center" >
        <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_consulta.hide();">
        
       </td>
     </tr>  
    </form>
  </table>
  </td>
  </tr>
  
  <tr> 
    <td align="center" valign="top"> 
      <?
       $totalizacao = array();
       db_lovrot($sSql,15,"()","",$oGet->funcao_js,"","NoMe", array(),false, $totalizacao);
       /*      
       if(isset($newsql) && $newsql=="true"){
         db_lovrot($sql1,15,"()","","js_abre|o58_coddot","","NoMe", array(),false, $totalizacao);
       }else{
         db_lovrot($sql,15,"()","",$funcao_js,"","NoMe", array(),false, $totalizacao);
       }
       */
      ?>
     </td>
   </tr>
</table>
</body>
</html>