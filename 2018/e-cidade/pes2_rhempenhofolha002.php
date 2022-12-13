<?php
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

/**
 *
 * @author Iuri Guntchnigg
 * @revision $Author: dbandre.mello $
 * @version $Revision: 1.20 $
 */
require_once(modification("fpdf151/pdf.php"));
require_once(modification("fpdf151/impcarne.php"));

require_once(modification("libs/db_utils.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_libpessoal.php"));
require_once(modification("classes/db_cfpess_classe.php"));

$oDaoCfpess            = new cl_cfpess;

/**
 * Modelo de impressão de relatório empenho folha
 * Retorna false caso der erro na consulta
 */
$iTipoRelatorio = $oDaoCfpess->buscaCodigoRelatorio('empenhofolha', db_anofolha(), db_mesfolha());
if(!$iTipoRelatorio) {
  db_redireciona('db_erros.php?fechar=true&db_erro=Modelo de impressão invalido, verifique parametros.');
}

$oJson       = new services_json();
$oParam      = $oJson->decode(str_replace("\\","",$_GET["json"]));

if(isset($oParam->aMatriculas)){
  $sMatriculas = implode(',', $oParam->aMatriculas);
}

$sSqlEmpenhos   = "SELECT rh72_sequencial,                                                                          ";
$sSqlEmpenhos  .= "       rh72_coddot,                                                                              ";
$sSqlEmpenhos  .= "       o56_elemento,                                                                             ";
$sSqlEmpenhos  .= "       substr(o56_descr, 0,46) as o56_descr,                                                     ";
$sSqlEmpenhos  .= "       o40_descr,                                                                                ";
$sSqlEmpenhos  .= "       o15_descr,                                                                                ";
$sSqlEmpenhos  .= "       rh72_unidade,                                                                             ";
$sSqlEmpenhos  .= "       rh72_orgao,                                                                               ";
$sSqlEmpenhos  .= "       rh72_projativ,                                                                            ";
$sSqlEmpenhos  .= "       rh72_anousu,                                                                              ";
$sSqlEmpenhos  .= "       rh72_mesusu,                                                                              ";
$sSqlEmpenhos  .= "       rh72_recurso,                                                                             ";
$sSqlEmpenhos  .= "       rh72_siglaarq,                                                                            ";
$sSqlEmpenhos  .= "       rh72_siglaarq,                                                                            ";
$sSqlEmpenhos  .= "       rh72_concarpeculiar,                                                                      ";
$sSqlEmpenhos  .= "       e60_codemp,                                                                               ";
$sSqlEmpenhos  .= "       e60_anousu,                                                                               ";
$sSqlEmpenhos  .= "       pc01_descrmater,                                                                          ";
$sSqlEmpenhos  .= "       round(sum(case when rh73_pd = 2 then rh73_valor *-1 else rh73_valor end), 2) as rh73_valor";
$sSqlEmpenhos  .= "  from rhempenhofolha                                                                            ";
$sSqlEmpenhos  .= "       inner join rhempenhofolharhemprubrica on rh81_rhempenhofolha = rh72_sequencial            ";
$sSqlEmpenhos  .= "       inner join rhempenhofolharubrica      on rh73_sequencial     = rh81_rhempenhofolharubrica ";
$sSqlEmpenhos  .= "       inner join orctiporec                 on o15_codigo          = rh72_recurso               ";
$sSqlEmpenhos  .= "       inner join orcorgao                   on rh72_orgao          = o40_orgao                  ";
$sSqlEmpenhos  .= "                                            and rh72_anousu         = o40_anousu                 ";
$sSqlEmpenhos  .= "       inner join rhpessoalmov               on rh73_seqpes         = rh02_seqpes                ";
$sSqlEmpenhos  .= "                                            and rh73_instit         = rh02_instit                ";
$sSqlEmpenhos  .= "       inner join orcelemento                on rh72_codele         = o56_codele                 ";
$sSqlEmpenhos  .= "                                            and rh72_anousu         = o56_anousu                 ";
$sSqlEmpenhos  .= "       left join rhempenhofolhaempenho       on rh72_sequencial     = rh76_rhempenhofolha        ";
$sSqlEmpenhos  .= "       left join empempenho                  on rh76_numemp         = e60_numemp                 ";
$sSqlEmpenhos  .= "       LEFT JOIN empempitem                  on e60_numemp          = e62_numemp";
$sSqlEmpenhos  .= "       left join pcmater                     on e62_item            = pc01_codmater              ";
$sSqlEmpenhos  .= "   where rh72_tipoempenho = {$oParam->iTipo}                                                     ";
$sSqlEmpenhos  .= "     and rh73_tiporubrica = 1                                                                    ";
$sSqlEmpenhos  .= "     and rh72_anousu      = {$oParam->iAnoFolha}                                                 ";
$sSqlEmpenhos  .= "     and rh72_mesusu      = {$oParam->iMesFolha}                                                 ";
$sSqlEmpenhos  .= "     and rh72_siglaarq    = '{$oParam->sSigla}'                                                  ";
$sSqlEmpenhos  .= "     and rh73_instit      = ".db_getsession('DB_instit');

if (!empty($sMatriculas)) {
  $sSqlEmpenhos  .= "   and rh02_regist     in ( $sMatriculas )";
}

if (!empty($oParam->sSemestre)){
  $sSqlEmpenhos  .= "   and rh72_seqcompl    = '{$oParam->sSemestre}'";
}

if ( $oParam->sSigla == 'r48' ) {
	$sSqlEmpenhos    .= " and rh72_seqcompl <> '0' ";
}

if (isset($oParam->sPrevidencia) && $oParam->sPrevidencia != "") {
  $sSqlEmpenhos  .= "     and rh72_tabprev  in ($oParam->sPrevidencia)";
}

$sSqlEmpenhos  .= "   group by rh72_sequencial,                                               ";
$sSqlEmpenhos  .= "            rh72_coddot,                                                   ";
$sSqlEmpenhos  .= "            o56_elemento,                                                  ";
$sSqlEmpenhos  .= "            o56_descr,                                                     ";
$sSqlEmpenhos  .= "            o40_descr,                                                     ";
$sSqlEmpenhos  .= "            o15_descr,                                                     ";
$sSqlEmpenhos  .= "            rh72_unidade,                                                  ";
$sSqlEmpenhos  .= "            rh72_orgao,                                                    ";
$sSqlEmpenhos  .= "            rh72_projativ,                                                 ";
$sSqlEmpenhos  .= "            rh72_mesusu,                                                   ";
$sSqlEmpenhos  .= "            rh72_anousu,                                                   ";
$sSqlEmpenhos  .= "            rh72_recurso,                                                  ";
$sSqlEmpenhos  .= "            rh72_siglaarq,                                                 ";
$sSqlEmpenhos  .= "            rh72_concarpeculiar,                                           ";
$sSqlEmpenhos  .= "            e60_codemp,                                                    ";
$sSqlEmpenhos  .= "            e60_anousu,                                                    ";
$sSqlEmpenhos  .= "            pc01_descrmater                                                ";
$sSqlEmpenhos  .= " order by   rh72_recurso,rh72_orgao,rh72_unidade,rh72_projativ,o56_elemento";

$rsDadosEmpenho   = db_query($sSqlEmpenhos);
$aEmpenhos        = db_utils::getCollectionByRecord($rsDadosEmpenho);
$aLinhasRelatorio = array();

$rsCfPess        = $oDaoCfpess->sql_record($oDaoCfpess->sql_query_file(db_anofolha(), db_mesfolha(), db_getsession("DB_instit"), "r11_geraretencaoempenho"));
$lMostraRetencao = db_utils::fieldsMemory($rsCfPess,0)->r11_geraretencaoempenho;

  foreach($aEmpenhos as $oEmpenho) {

      $sSqlDadosRetencao   = "SELECT rh73_rubric,                                                                                         ";
      $sSqlDadosRetencao  .= "       rh27_descr,                                                                                          ";
      $sSqlDadosRetencao  .= "       round(sum(rh73_valor), 2) as valorretencao                                                           ";
      $sSqlDadosRetencao  .= "  from rhempenhofolha                                                                                       ";
      $sSqlDadosRetencao  .= "       inner join rhempenhofolharhemprubrica     on rh81_rhempenhofolha        = rh72_sequencial            ";
      $sSqlDadosRetencao  .= "       inner join rhempenhofolharubrica          on rh73_sequencial            = rh81_rhempenhofolharubrica ";
      $sSqlDadosRetencao  .= "       inner join rhpessoalmov                   on rh73_seqpes                = rh02_seqpes                ";
      $sSqlDadosRetencao  .= "                                                and rh73_instit                = rh02_instit                ";
      $sSqlDadosRetencao  .= "       inner join  rhempenhofolharubricaretencao on rh78_rhempenhofolharubrica = rh73_sequencial            ";
      $sSqlDadosRetencao  .= "       inner join rhrubricas                     on rh27_rubric                = rh73_rubric                ";
      $sSqlDadosRetencao  .= "                                                and rh73_instit                = rh27_instit                ";
      $sSqlDadosRetencao  .= "   where rh72_sequencial  = {$oEmpenho->rh72_sequencial}                                                    ";
      $sSqlDadosRetencao  .= "     and rh72_tipoempenho = {$oParam->iTipo}                                                                ";
      $sSqlDadosRetencao  .= "     and rh73_tiporubrica = 2                                                                               ";
      $sSqlDadosRetencao  .= "     and rh73_pd          = 2                                                                               ";
      $sSqlDadosRetencao  .= "     and rh73_instit      = ".db_getsession('DB_instit');
      $sSqlDadosRetencao  .= "   group by rh73_rubric,                                                                                    ";
      $sSqlDadosRetencao  .= "            rh27_descr                                                                                      ";
      $sSqlDadosRetencao  .= "   order by rh73_rubric                                                                                     ";

      $rsDadosEmpenho     = db_query($sSqlDadosRetencao);
      $aRetencoes         = db_utils::getCollectionByRecord($rsDadosEmpenho);

      $oEmpenho->aDescontos = $aRetencoes;
      $aLinhasRelatorio[]   = $oEmpenho;
  }

switch ($oParam->sSigla){
	case "r14":
		$sPonto = "Salário";
	break;
  case "r48":
  	$sPonto = "Complementar";
  break;
  case "r35":
    $sPonto = "13o Salário";
  break;
  case "r20":
  	$sPonto = "Rescisão";
  break;
  case "r22":
  	$sPonto = "Adiantamento";
  break;
}

switch ($oParam->iTipo) {
	case 1:
	  $sTipoPonto = "Salário";
	break;
  case 2:
    $sTipoPonto = "Previdência";
  break;
  case 3:
    $sTipoPonto = "FGTS";
  break;
}

$head2 = "Empenhos para a Folha";
$head3 = "Mês: {$oParam->iMesFolha}";
$head4 = "Ano: {$oParam->iAnoFolha}";
$head5 = "Ponto: {$sPonto}";
$head6 = "Tipo: {$sTipoPonto}";

$pdf = new PDF("L");
$pdf->Open();
$pdf->AliasNbPages();
$pdf->SetAutoPageBreak(false,1);
$pdf->AddPage();
$pdf->setfillcolor(244);
$sFonte = "arial";
$iFonte = 7;
$iAlt   = 4;

$oDocumentoPDF = new db_impcarne($pdf, $iTipoRelatorio);
$oDocumentoPDF->lRetencao        = $oParam->lRetencao;
$oDocumentoPDF->aLinhasRelatorio = $aLinhasRelatorio;
$oDocumentoPDF->imprime();
$oDocumentoPDF->objpdf->output();