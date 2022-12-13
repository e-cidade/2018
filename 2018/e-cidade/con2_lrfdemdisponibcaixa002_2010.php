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

$oPost             = db_utils::postMemory($_POST);
$oGet              = db_utils::postMemory($_GET);
$iAnoUsu           = db_getsession('DB_anousu');
$sInstituicoes     = str_replace('-', ',', $oGet->db_selinstit);
$iCodigoRelatorio  = 108;

$cldb_config       = new cl_db_config;
$oReltorioContabil = new relatorioContabil($iCodigoRelatorio, false);
$oAnexoV           = new AnexoVRGF($iAnoUsu, $iCodigoRelatorio, $oGet->periodo);
$oAnexoV->setInstituicoes($sInstituicoes);

$aDadosAnexoV = $oAnexoV->getDados();
$iNumRows = count($aDadosAnexoV);
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
$head4  = "DEMONSTRATIVO DA DISPONIBILIDADE DE CAIXA";
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

$nTotalDisponibilidadeI      = 0;
$nTotalObrigacaoFinanceiraI  = 0;
$nTotalDisponibilidadeCaixaI = 0;
foreach ($aDadosAnexoV->recursosVinculados as $oRecursoVinculado) {

  $oPdf->SetFont('arial', '', $iTamFonte);
  $oPdf->Cell(85, $iAltCell, "    ".$oRecursoVinculado->codigo.
                             " - ".substr($oRecursoVinculado->descricao, 0, 50),                     'R', 0, "L", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoVinculado->disponibilidadebruta, 2), 'f'),   'RL', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoVinculado->obrigacoesfinanceiras, 2), 'f'),  'RL', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoVinculado->disponibilidadeliquida, 2), 'f'), 'L', 1, "R", 0);

	$nTotalDisponibilidadeI      += round($oRecursoVinculado->disponibilidadebruta, 2);
	$nTotalObrigacaoFinanceiraI  += round($oRecursoVinculado->obrigacoesfinanceiras, 2);
	$nTotalDisponibilidadeCaixaI += round($oRecursoVinculado->disponibilidadeliquida, 2);

  imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, false);
  imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, false, false);
}

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(85, $iAltCell, 'TOTAL DOS RECURSOS VINCULADOS (I)',                           'TBR', 0, "L", 0);

$oPdf->SetFont('arial', '', $iTamFonte);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalDisponibilidadeI, 'f'),                         1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalObrigacaoFinanceiraI, 'f'),                     1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalDisponibilidadeCaixaI, 'f'),                'TBL', 1, "R", 0);

$nTotalDisponibilidadeII      = 0;
$nTotalObrigacaoFinanceiraII  = 0;
$nTotalDisponibilidadeCaixaII = 0;
foreach ($aDadosAnexoV->recursosNaoVinculados as $oRecursoNaoVinculado) {

  $oPdf->SetFont('arial', '', $iTamFonte);
  $oPdf->Cell(85, $iAltCell, "    ".$oRecursoNaoVinculado->codigo.
                             " - ".substr($oRecursoNaoVinculado->descricao, 0, 50),                     'R', 0, "L", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoNaoVinculado->disponibilidadebruta, 2), 'f'),   'RL', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoNaoVinculado->obrigacoesfinanceiras, 2), 'f'),  'RL', 0, "R", 0);
  $oPdf->Cell(35, $iAltCell, db_formatar(round($oRecursoNaoVinculado->disponibilidadeliquida, 2), 'f'), 'L', 1, "R", 0);

  $nTotalDisponibilidadeII      += round($oRecursoNaoVinculado->disponibilidadebruta, 2);
  $nTotalObrigacaoFinanceiraII  += round($oRecursoNaoVinculado->obrigacoesfinanceiras, 2);
  $nTotalDisponibilidadeCaixaII += round($oRecursoNaoVinculado->disponibilidadeliquida, 2);

  imprimirCabecalho($oPdf, $iAltCell, $iTamFonte, false);
  imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, false, false);
}

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(85, $iAltCell, 'TOTAL DOS RECURSOS NÃO VINCULADOS (II)',                    'TBR', 0, "L", 0);

$oPdf->SetFont('arial', '', $iTamFonte);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalDisponibilidadeII, 'f'),                      1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalObrigacaoFinanceiraII, 'f'),                  1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalDisponibilidadeCaixaII, 'f'),             'TBL', 1, "R", 0);

$nTotalGeralDisponibilidade      = round(($nTotalDisponibilidadeI + $nTotalDisponibilidadeII), 2);
$nTotalGeralObrigacaoFinanceira  = round(($nTotalObrigacaoFinanceiraI + $nTotalObrigacaoFinanceiraII), 2);
$nTotalGeralDisponibilidadeCaixa = round(($nTotalDisponibilidadeCaixaI + $nTotalDisponibilidadeCaixaII), 2);

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(85, $iAltCell, 'TOTAL (III)=(I+II)',                                      'TBR', 0, "L", 0);

$oPdf->SetFont('arial', '', $iTamFonte);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalGeralDisponibilidade, 'f'),                 1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalGeralObrigacaoFinanceira, 'f'),             1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($nTotalGeralDisponibilidadeCaixa, 'f'),        'TBL', 1, "R", 0);

$oPdf->Cell(190, $iAltCell-2, '', 0, 1, "C", 0);

$oPdf->SetFont('arial', 'b', $iTamFonte);
$oPdf->Cell(85, $iAltCell, 'REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES¹', 'TBR', 0, "L", 0);

$oPdf->SetFont('arial', '', $iTamFonte);
$oPdf->Cell(35, $iAltCell, db_formatar($aDadosAnexoV->regimerpps->disponibilidadebruta, 'f'),   1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($aDadosAnexoV->regimerpps->obrigacoesfinanceiras, 'f'),  1, 0, "R", 0);
$oPdf->Cell(35, $iAltCell, db_formatar($aDadosAnexoV->regimerpps->disponibilidadeliquida, 'f'), 'TBL', 1, "R", 0);

$oPdf->Cell(190, $iAltCell-2, '', 0, 1, "C", 0);

if ( $oPdf->GetY() > $oPdf->h - 50 ) {
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

  if ( $oPdf->GetY() > $oPdf->h - 28 || $lImprime ) {

		$oPdf->SetFont('arial', 'b', $iTamFonte);
    if ( !$lImprime ) {

    	$oPdf->AddPage("P");
      imprimeInfoProxPagina($oPdf, $iAltCell, $iTamFonte, true);
    } else {

			$oPdf->Cell(130, $iAltCell, 'RGF - ANEXO V(LRF, art. 55, Inciso III, alínea "a")', 'B', 0, "L", 0);
			$oPdf->Cell(60, $iAltCell, 'R$ 1,00',                                              'B', 1, "R", 0);
    }

		$oPdf->Cell(85, $iAltCell+9, 'DESTINAÇÃO DE RECURSOS',       'TBR', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, 'DISPONIBILIDADE',                'TRL', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, 'OBRIGAÇÕES',                     'TRL', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, 'DISPONIBILIDADE',                'TL', 1, "C", 0);

		$oPdf->Cell(85, $iAltCell, '',                  'R', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, 'DE CAIXA',          'RL', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, 'FINANCEIRAS',       'RL', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, 'DE CAIXA',          'L', 1, "C", 0);

		$oPdf->Cell(85, $iAltCell, '',         'R', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, 'BRUTA',    'RL', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, '',         'RL', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, 'LÍQUIDA',  'L', 1, "C", 0);

		$oPdf->Cell(85, $iAltCell, '',         'BR', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, '(a)',        'BRL', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, '(b)',        'BRL', 0, "C", 0);
		$oPdf->Cell(35, $iAltCell, '(c)=(a-b)',  'B', 1, "C", 0);
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

	if ( $oPdf->GetY() > $oPdf->h - 29 || $lImprime ) {

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
