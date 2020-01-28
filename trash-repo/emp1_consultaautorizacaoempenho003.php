<?php
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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_empautoriza_classe.php");

$oGet            = db_utils::postMemory($_GET);
$oDaoEmpAutoriza = new cl_empautoriza;
$sWherePadrao    = " and e54_instit = ".db_getsession("DB_instit");
$sCamposPadrao   = "distinct(e54_autori), e54_emiss, e54_anulad, e54_numcgm, z01_nome, e54_instit";
$sSqlAutorizacao = null;
/*
 * Verifica se o usuário digitou alguma data
 * será utilizada em diversos pontos do filtro
 */

if (!empty($oGet->dtDataInicial)) {
  $dtDataInicial = implode("-", array_reverse(explode("/", $oGet->dtDataInicial))); 
}
if (!empty($oGet->dtDataFinal)) {
  $dtDataFinal  = implode("-", array_reverse(explode("/", $oGet->dtDataFinal)));
}

$sDataEmissao = "";
if (!empty($oGet->dtDataInicial) && empty($oGet->dtDataFinal)) {
  $sDataEmissao = " and e54_emiss >= {$dtDataInicial}";
} else if (empty($oGet->dtDataInicial) && !empty($oGet->dtDataFinal)) {
  $sDataEmissao = " and e54_emiss <= {$dtDataFinal}";
} else if (!empty($oGet->dtDataInicial) && !empty($oGet->dtDataFinal)) {
  $sDataEmissao = " and e54_emiss between {$dtDataInicial} and {$dtDataFinal}";
}

if (isset($oGet->iCodigoAutorizacao) && !empty($oGet->iCodigoAutorizacao)) {
  
  $sSqlAutorizacao = $oDaoEmpAutoriza->sql_query(null, $sCamposPadrao, null, "e54_autori = {$oGet->iCodigoAutorizacao} {$sWherePadrao}");
  
} else if (isset($oGet->iCodigoDotacao) && !empty($oGet->iCodigoDotacao)) {
  
  $sWhereDotacao    = "e54_autori in (select e56_autori";
  $sWhereDotacao   .= "                 from empautidot";
  $sWhereDotacao   .= "                where e56_coddot = {$oGet->iCodigoDotacao} ";
  $sWhereDotacao   .= "             order by e56_autori)";
  $sWhereDotacao   .= "and e54_anousu=".db_getsession("DB_anousu")." {$sDataEmissao} {$sWherePadrao}";
  $sSqlAutorizacao  = $oDaoEmpAutoriza->sql_query(null, $sCamposPadrao, null, $sWhereDotacao);
  
} else if (isset($oGet->iCodigoMaterial) && $oGet->iCodigoMaterial) {

  $sWhereMaterial  = "    e55_item = {$oGet->iCodigoMaterial}";
  $sWhereMaterial .= "and e54_anousu =".db_getsession("DB_anousu")." {$sDataEmissao} {$sWherePadrao}";
  $sSqlAutorizacao = $oDaoEmpAutoriza->sql_query_itemmaterial(null, $sCamposPadrao, null, $sWhereMaterial);

} else if (isset($oGet->iCodigoFornecedor) && $oGet->iCodigoFornecedor) {
  
  $sWhereFornecedor = "e54_numcgm = {$oGet->iCodigoFornecedor} {$sDataEmissao} {$sWherePadrao}";
  $sSqlAutorizacao  = $oDaoEmpAutoriza->sql_query(null, $sCamposPadrao, null, $sWhereFornecedor);  

} else if (!empty($sDataEmissao)) {
  
  $sWhereDataEmissao = substr($sDataEmissao, 4) ."{$sWherePadrao}";
  $sSqlAutorizacao   = $oDaoEmpAutoriza->sql_query(null, $sCamposPadrao, null, $sWhereDataEmissao);
}

if ($sSqlAutorizacao == null) {
  
  $sSqlAutorizacao  = "   select e54_autori,";
  $sSqlAutorizacao .= "          e54_emiss,";
  $sSqlAutorizacao .= "          e54_anulad,";
  $sSqlAutorizacao .= "          sum(e55_vltot) as e55_vltot,";
  $sSqlAutorizacao .= "          z01_nome,";
  $sSqlAutorizacao .= "          e54_instit";
  $sSqlAutorizacao .= "     from empautoriza ";
  $sSqlAutorizacao .= "          inner join cgm        on cgm.z01_numcgm         = empautoriza.e54_numcgm";
  $sSqlAutorizacao .= "          inner join empautitem on empautoriza.e54_autori = empautitem.e55_autori";
  $sSqlAutorizacao .= " group by e54_autori,e54_emiss,e54_anulad,z01_nome,e54_instit";
  $sSqlAutorizacao .= " order by e54_emiss desc,e54_autori";
}


?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body style="background-color: #CCCCCC; margin-top: 10px;">
<center>
<form id="formFiltrosAdicionais" name="formFiltrosAdicionais" method="GET">
<fieldset style="width: 350px">
  <legend><b>Filtros Adicionais</b></legend>
  <table>
    <tr>
      <td><b>Período:</b></td>
      <td>
        <?php 
          db_inputdata('dtDataInicial',"","","",true,'text',1,"");
          echo " <b>à</b> ";
          db_inputdata('dtDataFinal',"","","",true,'text',1,"");
        ?>
      </td>
    </tr>
  </table>
</fieldset>
  <br>
  <input type="submit" name="btnFiltrosAdicionais" id="btnFiltrosAdicionais" value="Filtrar" />
  <input type="button" name="btnFechar" id="btnFechar" value="Fechar" onclick="parent.db_iframe_consultaautorizacaoempenho003.hide()"/>
</form>

<fieldset style="width: 10px">
  <legend><b>Autorizações Encontradas</b></legend>
  <?php 
    db_lovrot($sSqlAutorizacao, 15, "()", "", "js_abreConsultaAutorizacao|e54_autori");
  ?>
</fieldset>
</center>
</body>
</html>

<script>
function js_abreConsultaAutorizacao(iCodigoAutorizacao) {

  var sUrlConsultaAutorizacao = "emp1_consultaautorizacaoempenho002.php?iCodigoAutorizacao="+iCodigoAutorizacao
  js_OpenJanelaIframe('top.corpo','db_iframe_consultaautorizacaoempenho002', sUrlConsultaAutorizacao, 'Consulta Autorização de Empenho: '+iCodigoAutorizacao, true);
}
</script>