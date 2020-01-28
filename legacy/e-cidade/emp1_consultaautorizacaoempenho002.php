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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/verticalTab.widget.php");
require_once("model/AutorizacaoEmpenho.model.php");
require_once("model/Dotacao.model.php");
require_once("model/CgmFactory.model.php");

$oGet               = db_utils::postMemory($_GET);
$oDaoEmpAutoriza    = db_utils::getDao("empautoriza");
$oDaoEmpEmpenho     = db_utils::getDao("empempenho");
$oDaoEmpAutDot      = db_utils::getDao("empautidot");
$oDaoCGM            = db_utils::getDao("cgm");
$oDaoDbDepart       = db_utils::getDao("db_depart");
$oDaoEmpAutHist     = db_utils::getDao("empauthist");

/*
 * Buscamos os dados da autorização de empenho
 */
$sSqlEmpAutoriza    = $oDaoEmpAutoriza->sql_query($oGet->iCodigoAutorizacao);
$rsBuscaEmpAutoriza = $oDaoEmpAutoriza->sql_record($sSqlEmpAutoriza);
$oDadoAutorizacao   = db_utils::fieldsMemory($rsBuscaEmpAutoriza, 0);
$oAutorizacao       = new AutorizacaoEmpenho($oGet->iCodigoAutorizacao);

switch ($oDadoAutorizacao->e54_codtipo) {
  case "1":
    $sDescriaoTipoEmpenho = "1 - ORDINÁRIO";
  break;
  case 2:
    $sDescriaoTipoEmpenho = "2 - GLOBAL";
  break;
  case 3:
    $sDescriaoTipoEmpenho = "3 - ESTIMATIVA";
  break;
  default:
    $sDescriaoTipoEmpenho = "";
}

/*
 * Buscamos os dados da dotação do empenho
 */
$sCamposDotacao     = "e56_coddot,o56_elemento,o56_descr,e56_orctiporec, o15_descr,";
$sCamposDotacao    .= "fc_estruturaldotacao(o58_anousu,o58_coddot) as o58_estrutdespesa";
$sSqlBuscaDotacao   = $oDaoEmpAutDot->sql_query_dotacao($oGet->iCodigoAutorizacao, $sCamposDotacao);
$rsBuscaDotacao     = $oDaoEmpAutDot->sql_record($sSqlBuscaDotacao);
if ($oDaoEmpAutDot->numrows > 0) {
  $oDadoDotacao       = db_utils::fieldsMemory($rsBuscaDotacao, 0);
}

/*
 * Buscamos os dados histórico da autorização de empenho 
 */
$sSqlBuscaHistorico = $oDaoEmpAutHist->sql_query($oGet->iCodigoAutorizacao);
$rsBuscaHistorico   = $oDaoEmpAutHist->sql_record($sSqlBuscaHistorico);
$sHistoricoEmpenho  = "&nbsp;";
if ($oDaoEmpAutHist->numrows > 0) {
  $oDadoHistorico    = db_utils::fieldsMemory($rsBuscaHistorico, 0);
  $sHistoricoEmpenho = "{$oDadoHistorico->e57_codhist} - {$oDadoHistorico->e40_descr}"; 
}


$oDaoEmpAutoriza->rotulo->label();
$oDaoEmpEmpenho->rotulo->label();
$oDaoEmpAutDot->rotulo->label();
$oDaoDbDepart->rotulo->label();
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
<style>
.tdTitle {
  font-weight: bold;
}
.valores {
  background-color: #FFF;
}
</style>
</head>
<body style="background-color: #CCCCCC;">
  <fieldset>
    <legend><b>Autorização de Empenho</b></legend>
    <table border="0">
      <tr>
        <td class="tdTitle" width="80"><?= $Le54_autori; ?></td>
        <td class="valores" align="right"><?= $oGet->iCodigoAutorizacao; ?></td>
        <td class="tdTitle">Acordo</td>
        <td class="valores" align="right"><?= $oAutorizacao->getContrato(); ?></td>
        <td class="tdTitle"><?= $Le54_destin; ?></td>
        <td class="valores" width="400px"><?= $oDadoAutorizacao->e54_destin ?></td>
      </tr>
      <tr>
        <td class="tdTitle" nowrap="nowrap"><?= $Le60_numemp; ?></td>
        <td class="valores" align="right" width="100"><?= $oDadoAutorizacao->e60_numemp; ?></td>
        <td class="tdTitle" nowrap="nowrap"><?= $Le60_codemp; ?></td>
        <td class="valores" align="right" width="100"><?= $oDadoAutorizacao->e60_codemp; ?></td>
        <td class="tdTitle">Departamento:</td>
        <td class="valores"><?= "{$oDadoAutorizacao->coddepto} - {$oDadoAutorizacao->descrdepto}"   ?></td>
      </tr>
      <tr>
        <td class="tdTitle" nowrap="nowrap"><?= $Le54_emiss; ?></td>
        <td class="valores" align="right" width="100"><?= db_formatar($oDadoAutorizacao->e54_emiss, 'd'); ?></td>
        <td class="tdTitle" nowrap="nowrap"><?= $Le54_anulad; ?></td>
        <td class="valores" align="right" width="100"><?= db_formatar($oDadoAutorizacao->e54_anulad, 'd'); ?></td>
        <td class="tdTitle">Fornecedor (CGM):</td>
        <td class="valores"><?= "{$oDadoAutorizacao->z01_numcgm} - {$oDadoAutorizacao->z01_nome}"   ?></td>
      </tr>
      <tr>
        <td class="tdTitle" nowrap="nowrap"><?=$Le54_valor; ?></td>
        <td class="valores" align="right"><?= db_formatar($oDadoAutorizacao->e54_valor, 'f'); ?></td>
        <td class="tdTitle" nowrap="nowrap">Tipo do Empenho:</td>
        <td class="valores"><?= $sDescriaoTipoEmpenho; ?></td>
        <td class="tdTitle" nowrap="nowrap">Usuário:</td>
        <td class="valores"><?= "{$oDadoAutorizacao->id_usuario} - {$oDadoAutorizacao->login}"; ?></td>
      </tr>
      <tr>
        <td class="tdTitle" nowrap="nowrap">Histórico:</td>
        <td class="valores" colspan="5"><?= $sHistoricoEmpenho; ?></td>
      </tr>
      <tr>
        <td class="tdTitle" nowrap="nowrap">Dotação:</td>
        <td class="valores" colspan="5">
          <?= "{$oDadoDotacao->e56_coddot} - {$oDadoDotacao->o58_estrutdespesa}<br>
               {$oDadoDotacao->o56_elemento} - {$oDadoDotacao->o56_descr}"; ?>
        </td>
      </tr>
      <tr>
        <td class="tdTitle" nowrap="nowrap">Resumo:</td>
        <td class="valores" colspan="5">
          <textarea style="width:100%; border:0; resize: none;" rows="4" readonly><?= $oDadoAutorizacao->e54_resumo; ?></textarea>
        </td>
      </tr>
    </table>
  </fieldset>
  <fieldset>
    <legend><b>Informações da Autorização</b></legend>
    <?php 
      $oVerticalTab = new verticalTab('detalhesAutorizacaoEmpenho', 350);
      $sQueryString = "e55_autori={$oGet->iCodigoAutorizacao}";
      
      $oVerticalTab->add('dadosItensAutorizacao', 'Itens', "emp1_empempitem005.php?{$sQueryString}&e60_numemp={$oDadoAutorizacao->e60_numemp}");
      $oVerticalTab->add('dadosItensAutorizacao', 'Solicitação de Compras', "func_solicita001.php?{$sQueryString}&lNovaConsulta=true");
      $oVerticalTab->add('dadosItensAutorizacao', 'Processo de Compras', "func_pcproc001.php?{$sQueryString}&lNovaConsulta=true");
      $oVerticalTab->show();
    ?>
  </fieldset>
</body>
</html>