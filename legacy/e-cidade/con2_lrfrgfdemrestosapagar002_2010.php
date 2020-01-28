<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once(modification("fpdf151/pdf.php"));
require_once(modification("libs/db_sql.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification('libs/db_app.utils.php'));
require_once(modification('libs/db_libcontabilidade.php'));
require_once(modification('libs/db_liborcamento.php'));
require_once(modification('classes/db_db_config_classe.php'));
require_once(modification("dbforms/db_funcoes.php"));

use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

db_app::import("linhaRelatorioContabil");
db_app::import("relatorioContabil");
db_app::import("contabilidade.relatorios.AnexoVRGF");
db_app::import("contabilidade.relatorios.AnexoVIRGF");

$oPost             = db_utils::postMemory($_POST);
$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession('DB_anousu');
$sInstituicoes     = str_replace('-', ',', $oGet->db_selinstit);

$cldb_config       = new cl_db_config;
$oReltorioContabil = new relatorioContabil(109, false);
$oAnexoV           = new AnexoVRGF($iAnoUsu, 108, $oGet->periodo);
$oAnexoV->setInstituicoes($sInstituicoes);

$oAnexoVI          = new AnexoVIRGF($iAnoUsu, 109, $oGet->periodo);
$oAnexoVI->setInstituicoes($sInstituicoes);
$oAnexoVI->setDadosAnexoV($oAnexoV);

$aDadosAnexoVI = $oAnexoVI->getDados();
$iNumRows      = count($aDadosAnexoVI);
if ($iNumRows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}

$sWhere        = "prefeitura is true";
$sSqlDbConfig  = $cldb_config->sql_query_file(null, "munic, codigo, nomeinst, nomeinstabrev", null, $sWhere);
$rsSqlDbConfig = $cldb_config->sql_record($sSqlDbConfig);
$oMunicipio    = db_utils::fieldsMemory($rsSqlDbConfig, 0);

$oPrefeitura = InstituicaoRepository::getInstituicaoPrefeitura();

$head2  = DemonstrativoFiscal::getEnteFederativo($oPrefeitura);

$aInstituicoes = explode(",", $sInstituicoes);

if (count($aInstituicoes) == 1) {

  $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);
  $head2  = DemonstrativoFiscal::getEnteFederativo($oInstituicao);

  if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
    $head2 .= "\n" . $oInstituicao->getDescricao();
  }
}

$head3  = "RELATÓRIO DE GESTÃO FISCAL";
$head4  = "DEMONSTRATIVO DOS RESTOS A PAGAR";
$head5  = "ORÇAMENTOS FISCAL E DA SEGURIDADE SOCIAL";
$head6  = "JANEIRO A DEZEMBRO DE {$iAnoUsu}";

$oPdf  = new PDF();
$oPdf->Open();
$oPdf->AliasNbPages();
$oPdf->SetAutoPageBreak(false);
$oPdf->AddPage("P");
$oPdf->SetFillColor(235);

$iTamFonte = 5;
$iAltCell  = 3;

imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, true);

$nTotalRestosPagarProcessadosExercicioAnteriorI    = 0;
$nTotalRestosPagarProcessadosExercicioI            = 0;
$nTotalRestosPagarNaoProcessadosExercicioAnteriorI = 0;
$nTotalRestosPagarNaoProcessadosExercicioI         = 0;
$nTotalDisponibilidadeDeCaixaI                     = 0;
$nTotalInsuficienciaFinanceiraI                    = 0;

foreach ($aDadosAnexoVI->recursosVinculados as $oRecursoVinculado) {

  $oPdf->SetFont('arial', '', $iTamFonte);
  $oPdf->Cell(60, $iAltCell, "    ".$oRecursoVinculado->codigo.
                             " - ".substr($oRecursoVinculado->descricao, 0, 50),                                         'R', 0, "L", 0);
  $oPdf->Cell(15, $iAltCell, db_formatar(round($oRecursoVinculado->restospagarprocessadosexercicioanterior, 2), 'f'),    'RL', 0, "R", 0);
  $oPdf->Cell(15, $iAltCell, db_formatar(round($oRecursoVinculado->restospagarprocessadosexercicio, 2), 'f'),            'RL', 0, "R", 0);
  $oPdf->Cell(15, $iAltCell, db_formatar(round($oRecursoVinculado->restospagarnaoprocessadosexercicioanterior, 2), 'f'), 'RL', 0, "R", 0);
  $oPdf->Cell(15, $iAltCell, db_formatar(round($oRecursoVinculado->restospagarnaoprocessadosexercicio, 2), 'f'),         'RL', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoVinculado->disponibilidadedecaixa, 2), 'f'),                     'RL', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoVinculado->insuficienciafinanceira, 2), 'f'),                    'L', 1, "R", 0);

$nTotalRestosPagarProcessadosExercicioAnteriorI    += round($oRecursoVinculado->restospagarprocessadosexercicioanterior, 2);
$nTotalRestosPagarProcessadosExercicioI            += round($oRecursoVinculado->restospagarprocessadosexercicio, 2);
$nTotalRestosPagarNaoProcessadosExercicioAnteriorI += round($oRecursoVinculado->restospagarnaoprocessadosexercicioanterior, 2);
$nTotalRestosPagarNaoProcessadosExercicioI         += round($oRecursoVinculado->restospagarnaoprocessadosexercicio, 2);
$nTotalDisponibilidadeDeCaixaI                     += round($oRecursoVinculado->disponibilidadedecaixa, 2);
$nTotalInsuficienciaFinanceiraI                    += round($oRecursoVinculado->insuficienciafinanceira, 2);

  imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, false);
  imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, false, false);
}

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(60, $iAltCell, 'TOTAL DOS RECURSOS VINCULADOS (I)', 'TBR', 0, "L", 0);

$oPdf->SetFont('arial', '', $iTamFonte);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalRestosPagarProcessadosExercicioAnteriorI, 'f'),    1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalRestosPagarProcessadosExercicioI, 'f'),            1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalRestosPagarNaoProcessadosExercicioAnteriorI, 'f'), 1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalRestosPagarNaoProcessadosExercicioI, 'f'),         1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalDisponibilidadeDeCaixaI, 'f'),                     1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalInsuficienciaFinanceiraI, 'f'),                    'TBL', 1, "R", 0);

$nTotalRestosPagarProcessadosExercicioAnteriorII    = 0;
$nTotalRestosPagarProcessadosExercicioII            = 0;
$nTotalRestosPagarNaoProcessadosExercicioAnteriorII = 0;
$nTotalRestosPagarNaoProcessadosExercicioII         = 0;
$nTotalDisponibilidadeDeCaixaII                     = 0;
$nTotalInsuficienciaFinanceiraII                    = 0;

foreach ($aDadosAnexoVI->recursosNaoVinculados as $oRecursoNaoVinculado) {

  $oPdf->SetFont('arial', '', $iTamFonte);
  $oPdf->Cell(60, $iAltCell, "    ".$oRecursoNaoVinculado->codigo.
                             "-".substr($oRecursoNaoVinculado->descricao, 0, 50),                                           'R', 0, "L", 0);
  $oPdf->Cell(15, $iAltCell, db_formatar(round($oRecursoNaoVinculado->restospagarprocessadosexercicioanterior, 2), 'f'),    'RL', 0, "R", 0);
  $oPdf->Cell(15, $iAltCell, db_formatar(round($oRecursoNaoVinculado->restospagarprocessadosexercicio, 2), 'f'),            'RL', 0, "R", 0);
  $oPdf->Cell(15, $iAltCell, db_formatar(round($oRecursoNaoVinculado->restospagarnaoprocessadosexercicioanterior, 2), 'f'), 'RL', 0, "R", 0);
  $oPdf->Cell(15, $iAltCell, db_formatar(round($oRecursoNaoVinculado->restospagarnaoprocessadosexercicio, 2), 'f'),         'RL', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoNaoVinculado->disponibilidadedecaixa, 2), 'f'),                     'RL', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoNaoVinculado->insuficienciafinanceira, 2), 'f'),                    'L', 1, "R", 0);

	$nTotalRestosPagarProcessadosExercicioAnteriorII    += round($oRecursoNaoVinculado->restospagarprocessadosexercicioanterior, 2);
	$nTotalRestosPagarProcessadosExercicioII            += round($oRecursoNaoVinculado->restospagarprocessadosexercicio, 2);
	$nTotalRestosPagarNaoProcessadosExercicioAnteriorII += round($oRecursoNaoVinculado->restospagarnaoprocessadosexercicioanterior, 2);
	$nTotalRestosPagarNaoProcessadosExercicioII         += round($oRecursoNaoVinculado->restospagarnaoprocessadosexercicio, 2);
	$nTotalDisponibilidadeDeCaixaII                     += round($oRecursoNaoVinculado->disponibilidadedecaixa, 2);
	$nTotalInsuficienciaFinanceiraII                    += round($oRecursoNaoVinculado->insuficienciafinanceira, 2);

  imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, false);
  imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, false, false);
}

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(60, $iAltCell, 'TOTAL DOS RECURSOS NÃO VINCULADOS (II)', 'TBR', 0, "L", 0);

$oPdf->SetFont('arial', '', $iTamFonte);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalRestosPagarProcessadosExercicioAnteriorII, 'f'),    1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalRestosPagarProcessadosExercicioII, 'f'),            1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalRestosPagarNaoProcessadosExercicioAnteriorII, 'f'), 1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalRestosPagarNaoProcessadosExercicioII, 'f'),         1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalDisponibilidadeDeCaixaII, 'f'),                     1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalInsuficienciaFinanceiraII, 'f'),                    'TBL', 1, "R", 0);

$nSomaTotalProcessadosExercicioAnterior    = ($nTotalRestosPagarProcessadosExercicioAnteriorI
                                              + $nTotalRestosPagarProcessadosExercicioAnteriorII);
$nSomaTotalProcessadosExercicio            = ($nTotalRestosPagarProcessadosExercicioI
                                              + $nTotalRestosPagarProcessadosExercicioII);
$nSomaTotalNaoProcessadosExercicioAnterior = ($nTotalRestosPagarNaoProcessadosExercicioAnteriorI
                                              + $nTotalRestosPagarNaoProcessadosExercicioAnteriorII);
$nSomaTotalNaoProcessadosExercicio         = ($nTotalRestosPagarNaoProcessadosExercicioI
                                              + $nTotalRestosPagarNaoProcessadosExercicioII);
$nSomaTotalDisponibilidadeCaixa            = ($nTotalDisponibilidadeDeCaixaI
                                              + $nTotalDisponibilidadeDeCaixaII);
$nSomaTotalInsuficienciaFinanceira         = ($nTotalInsuficienciaFinanceiraI
                                              + $nTotalInsuficienciaFinanceiraII);

$nTotalGeralProcessadosExercicioAnterior    = round($nSomaTotalProcessadosExercicioAnterior, 2);
$nTotalGeralProcessadosExercicio            = round($nSomaTotalProcessadosExercicio, 2);
$nTotalGeralNaoProcessadosExercicioAnterior = round($nSomaTotalNaoProcessadosExercicioAnterior, 2);
$nTotalGeralNaoProcessadosExercicio         = round($nSomaTotalNaoProcessadosExercicio, 2);
$nTotalGeralDisponibilidadeCaixa            = round($nSomaTotalDisponibilidadeCaixa, 2);
$nTotalGeralInsuficienciaFinanceira         = round($nSomaTotalInsuficienciaFinanceira, 2);

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(60, $iAltCell, 'TOTAL (III)=(I+II)',      'TBR', 0, "L", 0);

$oPdf->SetFont('arial', '', $iTamFonte);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalGeralProcessadosExercicioAnterior, 'f'),    1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalGeralProcessadosExercicio, 'f'),            1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalGeralNaoProcessadosExercicioAnterior, 'f'), 1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($nTotalGeralNaoProcessadosExercicio, 'f'),         1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalGeralDisponibilidadeCaixa, 'f'),            1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalGeralInsuficienciaFinanceira, 'f'),         'TBL', 1, "R", 0);

$oPdf->Cell(190, $iAltCell-2, '', 0, 1, "C", 0);

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(60, $iAltCell, 'REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES¹', 'TBR', 0, "L", 0);

$oPdf->SetFont('arial', '', $iTamFonte);
$oPdf->Cell(15, $iAltCell, db_formatar($aDadosAnexoVI->regimerpps->restospagarprocessadosexercicioanterior, 'f'),    1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($aDadosAnexoVI->regimerpps->restospagarprocessadosexercicio, 'f'),            1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($aDadosAnexoVI->regimerpps->restospagarnaoprocessadosexercicioanterior, 'f'), 1, 0, "R", 0);
$oPdf->Cell(15, $iAltCell, db_formatar($aDadosAnexoVI->regimerpps->restospagarnaoprocessadosexercicio, 'f'),         1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($aDadosAnexoVI->regimerpps->disponibilidadedecaixa, 'f'),                     1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($aDadosAnexoVI->regimerpps->insuficienciafinanceira, 'f'),                    'TBL', 1, "R", 0);

$oPdf->Cell(190, $iAltCell-2, '', 0, 1, "C", 0);

if ($oPdf->GetY() > $oPdf->h - 50) {
	$oPdf->AddPage("P");
}

$oReltorioContabil->getNotaExplicativa($oPdf, $oGet->periodo);

$oReltorioContabil->assinatura($oPdf, 'GF');

$oPdf->Output();

/**
 * Impime cabecalho do relatorio
 *
 * @param Object  type $oPdf
 * @param Integer type $iAltCell
 * @param Boolean type $lImprime
 */
function imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, $lImprime) {

  if ( $oPdf->GetY() > $oPdf->h - 30 || $lImprime ) {

    $oPdf->SetFont('arial', 'b', $iTamFonte);
    if ( !$lImprime ) {

      $oPdf->AddPage("P");
      imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, true);
    } else {

      $oPdf->Cell(130, $iAltCell, 'RGF - ANEXO VI(LRF, art. 55, Inciso III, alínea "b")', 'B', 0, "L", 0);
      $oPdf->Cell(60, $iAltCell, 'R$ 1,00',                                              'B', 1, "R", 0);
    }

    $iPosicaoX = $oPdf->GetX();
    $iPosicaoY = $oPdf->GetY();

    $oPdf->Cell(60, $iAltCell+16, 'DESTINAÇÃO DE RECURSOS', 'TR', 0, "C", 0);
    $oPdf->Cell(60, $iAltCell,    'RESTOS A PAGAR',         1, 0, "C", 0);
    $oPdf->Cell(35, $iAltCell+16, '',                       'TRL', 0, "C", 0);
    $oPdf->Cell(35, $iAltCell+16, '',                       'TL', 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+60, $iPosicaoY+3);
    $oPdf->Cell(30, $iAltCell+3, '', 1, 0, "C", 0);
    $oPdf->Cell(30, $iAltCell+3, '', 1, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+60, $iPosicaoY+9);
    $oPdf->Cell(15, $iAltCell+7, '', 'TRL', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell+7, '', 'TRL', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell+7, '', 'TRL', 0, "C", 0);
    $oPdf->Cell(15, $iAltCell+7, '', 'TRL', 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+60, $iPosicaoY+4);
    $oPdf->Cell(30, $iAltCell-2, 'Liquidados e não', 0, 0, "C", 0);
    $oPdf->Cell(30, $iAltCell-2, 'Empenhados e não', 0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+60, $iPosicaoY+7);
    $oPdf->Cell(30, $iAltCell-2, 'pagos (processados)',          0, 0, "C", 0);
    $oPdf->Cell(30, $iAltCell-2, 'liquidados (não processados)', 0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+60, $iPosicaoY+10);
    $oPdf->Cell(15, $iAltCell-2, 'De', 0, 0, "C", 0);
    $oPdf->Cell(15, $iAltCell-2, 'Do', 0, 0, "C", 0);
    $oPdf->Cell(15, $iAltCell-2, 'De', 0, 0, "C", 0);
    $oPdf->Cell(15, $iAltCell-2, 'Do', 0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+60, $iPosicaoY+13);
    $oPdf->Cell(15, $iAltCell-2, 'Exercícios', 0, 0, "C", 0);
    $oPdf->Cell(15, $iAltCell-2, 'Exercício',  0, 0, "C", 0);
    $oPdf->Cell(15, $iAltCell-2, 'Exercícios', 0, 0, "C", 0);
    $oPdf->Cell(15, $iAltCell-2, 'Exercício',  0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+60, $iPosicaoY+16);
    $oPdf->Cell(15, $iAltCell-2, 'Anteriores', 0, 0, "C", 0);
    $oPdf->Cell(15, $iAltCell-2, '',           0, 0, "C", 0);
    $oPdf->Cell(15, $iAltCell-2, 'Anteriores', 0, 0, "C", 0);
    $oPdf->Cell(15, $iAltCell-2, '',           0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+120, $iPosicaoY+1);
    $oPdf->Cell(35, $iAltCell-2, 'DISPONIBILIDADE DE', 0, 0, "C", 0);
    $oPdf->Cell(35, $iAltCell-2, 'EMPENHOS NÃO',       0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+120, $iPosicaoY+4);
    $oPdf->Cell(35, $iAltCell-2, 'CAIXA LÍQUIDA', 0, 0, "C", 0);
    $oPdf->Cell(35, $iAltCell-2, 'LIQUIDADOS',    0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+120, $iPosicaoY+7);
    $oPdf->Cell(35, $iAltCell-2, '(ANTES DA',       0, 0, "C", 0);
    $oPdf->Cell(35, $iAltCell-2, 'CANCELADOS (NÃO', 0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+120, $iPosicaoY+10);
    $oPdf->Cell(35, $iAltCell-2, 'INSCRIÇÃO EM',  0, 0, "C", 0);
    $oPdf->Cell(35, $iAltCell-2, 'INSCRITOS POR', 0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+120, $iPosicaoY+13);
    $oPdf->Cell(35, $iAltCell-2, 'RESTOS A PAGAR',            0, 0, "C", 0);
    $oPdf->Cell(35, $iAltCell-2, 'INSUFICIÊNCIA FINANCEIRA)', 0, 1, "C", 0);

    $oPdf->SetXY($iPosicaoX+120, $iPosicaoY+16);
    $oPdf->Cell(35, $iAltCell-2, 'NÃO PROCESSADOS DO EXERCÍCIO)', 0, 0, "C", 0);
    $oPdf->Cell(35, $iAltCell-2, '',                              0, 1, "C", 0);

    $iPosicaoY = $oPdf->GetY();

    $oPdf->SetXY($iPosicaoX, $iPosicaoY+1);
    $oPdf->Cell(190, $iAltCell-2, '', 'B', 1, "C", 0);
  }
}

/**
 * Impime informacao da proxima pagina no relatorio
 *
 * @param Object type $oPdf
 * @param Integer type $iAltCell
 * @param Boolean type $lImprime
 * @param Boolean type $lCabecalho default true
 */
function imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, $lImprime, $lCabecalho=true) {

  if ( $oPdf->GetY() > $oPdf->h - 31 || $lImprime ) {

    $oPdf->SetFont('arial', 'b', $iTamFonte);
    if ( $lCabecalho ) {
      $oPdf->Cell(190, ($iAltCell*2), 'Continuação '.($oPdf->PageNo())."/{nb}",             'T', 1, "R", 0);
    } else {

      $oPdf->Cell(190, ($iAltCell*3), 'Continua na página '.($oPdf->PageNo()+1)."/{nb}",    'T', 1, "R", 0);
      imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, false);
    }
  }
}
?>
