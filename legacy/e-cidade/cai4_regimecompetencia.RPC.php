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

use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\Reconhecimento;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("fpdf151/pdf.php"));

$oJson = JSON::create();
$oRetorno = new stdClass();
$oParam = $oJson->parse(str_replace("\\", "", $_POST["json"]));
$oRetorno->erro = false;
$oRetorno->arquivo = false;
$oPdf = null;

try {

    db_inicio_transacao();
    switch ($oParam->exec) {

        case 'gerarRelatorio' :

            $oCompetencia = null;

            if (!empty($oParam->sCompetencia)) {

                $oCompetencia = DBCompetencia::createFromString($oParam->sCompetencia);
                $oCompetenciaAtual = new DBDate(date('Y-m-d', db_getsession("DB_datausu")));
                $oCompetenciaAtual = $oCompetenciaAtual->getCompetencia();

                if ($oCompetencia->comparar($oCompetenciaAtual, DBCompetencia::COMPARACAO_MAIOR)) {
                    throw new BusinessException("Competência não pode ser maior que a atual.");
                }
            }

            $aReconhecimentos = Reconhecimento::getReconhecimentosFechados($oCompetencia, $oParam->iCredor, $oParam->iContrato);
            if (count($aReconhecimentos) == 0) {
                throw new BusinessException("Nenhum registro encontrado.");
            }

            $head1 = "Demonstrativo Reconhecimento de competência";

            if (!empty($oParam->iCredor)) {

                $oContrato = $aReconhecimentos[0];
                $head5 = "CREDOR: {$oContrato->getAcordo()->getContratado()->getNome()}";
            }

            if (!empty($oParam->iContrato)) {

                $oContrato = $aReconhecimentos[0];
                $head6 = "CONTRATO: {$oContrato->getAcordo()->getResumoObjeto()}";
            }

            if (!empty($oParam->sCompetencia)) {
                $head7 = "COMPETÊNCIA: {$oParam->sCompetencia}";
            }

            $oPdf = new PDF();
            $oPdf->Open();
            $oPdf->AliasNbPages();
            $oPdf->setfillcolor(235);

            $oPdf->setfont('arial', 'b', 8);
            $oPdf->addpage();

            $iAcordoAtual = 0;
            $nTotal = 0;
            $nTotalReconhecido = 0;
            $nTotalRealizado = 0;

            $lEscreveInformacaoParcelaModificada = false;

            foreach ($aReconhecimentos as $iReconhecimento => $oReconhecimento) {
                $lModificouParcela = false;

                $iAcordo = $oReconhecimento->getAcordo()->getCodigoAcordo();

                if ($iAcordoAtual != $iAcordo && $iAcordoAtual != 0) {
                    montaRodape($oPdf, $nTotal, $nTotalReconhecido, $nTotalRealizado);
                    $nTotal = 0;
                    $nTotalReconhecido = 0;
                    $nTotalRealizado = 0;
                }

                if ($iAcordoAtual != $iAcordo) {
                    $oPdf->cell(191, 6, 'Acordo: ' . $iAcordo . ' - ' . $oReconhecimento->getAcordo()->getResumoObjeto(), 0, 1, "L", 1);

                    $oPdf->setfont('arial', 'b', 8);
                    $oPdf->cell(40, 6, 'Competência', 0, 0, "L", 0);
                    $oPdf->cell(37, 6, 'Valor', 0, 0, "R", 0);
                    $oPdf->cell(37, 6, 'Valor Reconhecido (A)', 0, 0, "R", 0);
                    $oPdf->cell(37, 6, 'Valor Realizado (B)', 0, 0, "R", 0);
                    $oPdf->cell(37, 6, 'Saldo (B - A)', 0, 1, "R", 0);
                    $iAcordoAtual = $iAcordo;
                    $oPdf->setfont('arial', '', 8);
                }

                $nTotal += $oReconhecimento->getValor();
                $nTotalReconhecido += $oReconhecimento->getValorReconhecido();
                $nTotalRealizado += $oReconhecimento->getValorRealizado();

                /**
                 * 1 - Verifica se a parcela não foi liquidada e se é a ultima competencia do ano
                 * 2 - Verifica se o acordo esta vinculado a algum empenho
                 * 3 - Verifica se existe lançamento 4010 para estes empenhos
                 * 4 - Caso exista lançamentos 4010, soma o valor desses lançamentos ao total realizado
                 */
                $iValorRealizadoAtual   = $oReconhecimento->getValorRealizado();

                $oDaoProgramacaoFinanceira  = new cl_acordoprogramacaofinanceira();
                $sSqlMaiorCompetencia       = $oDaoProgramacaoFinanceira->sql_query_parcelas(null, 'k118_mes', 'k118_mes desc', "ac34_acordo = {$iAcordo} AND k118_ano = {$oReconhecimento->getCompetencia()->getAno()}", 1);
                $rsMaiorCompetencia         = db_query($sSqlMaiorCompetencia);

                if (!$rsMaiorCompetencia) {
                    throw new BusinessException("Não foi possível as parcelas do acordo {$iAcordo}");
                }

                $iMaiorCompetencia  = 0;
                $oMaiorCompetencia  = pg_fetch_object($rsMaiorCompetencia, 0);

                if (!empty($oMaiorCompetencia)) {
                    $iMaiorCompetencia = $oMaiorCompetencia->k118_mes;
                }

                if ( $oReconhecimento->getCompetencia()->getMes() == $iMaiorCompetencia){
                    $aEmpenhos = $oReconhecimento->getAcordo()->getEmpenhos();
                    foreach ($aEmpenhos as $oEmpenho) {

                        $oDaoEventosContabeis    = new cl_conhistdoc();
                        $sSqlEventosContabeis    = $oDaoEventosContabeis->sql_query_empenhos("c53_coddoc = 4010 and c75_numemp = {$oEmpenho->getNumero()}");
                        $rsEventosContabeis      = db_query($sSqlEventosContabeis);

                        if (!$rsEventosContabeis) {
                            throw new BusinessException("Não foi possível buscar os eventos contábeis vinculados ao empenho {$oEmpenho->getNumero()}");
                        }

                        $oCompetenciaAtual      = $oReconhecimento->getCompetencia();
                        $aEventosContabeis = \db_utils::makeCollectionFromRecord($rsEventosContabeis, function($dados) use (&$iValorRealizadoAtual, &$oCompetenciaAtual, &$lModificouParcela, &$lEscreveInformacaoParcelaModificada, &$nTotalRealizado){

                            /**
                             * Verifica se o ano do lancamento é mesmo ano da competencia que está sendo processada
                             */
                            $oDataEvento = new DBDate($dados->c70_data);
                            if ($oDataEvento->getAno() == $oCompetenciaAtual->getAno()) {

                                $lModificouParcela = true;
                                $lEscreveInformacaoParcelaModificada = true;
                                $iValorRealizadoAtual += $dados->c70_valor;
                                $nTotalRealizado += $dados->c70_valor;
                            }
                        });
                    }

                    $oReconhecimento->setValorRealizado($iValorRealizadoAtual);
                }

                $oPdf->cell(40, 6, DBDate::getMesExtenso($oReconhecimento->getCompetencia()->getMes()) . '/' . $oReconhecimento->getCompetencia()->getAno(), 0, 0, "L", 0);
                $oPdf->cell(37, 6, number_format($oReconhecimento->getValor(), 2, ',', '.'), 0, 0, "R", 0);
                $oPdf->cell(37, 6, number_format($oReconhecimento->getValorReconhecido(), 2, ',', '.'), 0, 0, "R", 0);

                $sReconhecimentoValorRealizado = number_format($oReconhecimento->getValorRealizado(), 2, ',', '.');
                if ($lModificouParcela) {
                    $sReconhecimentoValorRealizado = "* ".$sReconhecimentoValorRealizado;
                }

                $oPdf->cell(37, 6, $sReconhecimentoValorRealizado, 0, 0, "R", 0);
                $oPdf->cell(37, 6, number_format($oReconhecimento->getValorRealizado() - $oReconhecimento->getValorReconhecido(), 2, ',', '.'), 0, 1, "R", 0);
                if ($iReconhecimento == count($aReconhecimentos) - 1) {
                    montaRodape($oPdf, $nTotal, $nTotalReconhecido, $nTotalRealizado, $lEscreveInformacaoParcelaModificada);
                    $nTotal = 0;
                    $nTotalReconhecido = 0;
                    $nTotalRealizado = 0;
                }
            }

            $oPdf->Output('tmp/RegimeCompetencia.pdf', false, true);
            $oRetorno->arquivo = 'tmp/RegimeCompetencia.pdf';

            break;
    }
    db_fim_transacao(false);
} catch (Exception $e) {

    db_fim_transacao(true);
    $oRetorno->erro = true;
    $oRetorno->message = $e->getMessage();
}

echo $oJson->stringify($oRetorno);

function montaRodape($oPdf, $nTotal, $nTotalReconhecido, $nTotalRealizado, $lTemParcelaModificada = false)
{

    $oPdf->setfont('arial', 'b', 8);
    $oPdf->cell(40, 6, 'TOTAL', 0, 0, "L", 0);
    $oPdf->cell(37, 6, number_format($nTotal, 2, ',', '.'), 0, 0, "R", 0);
    $oPdf->cell(37, 6, number_format($nTotalReconhecido, 2, ',', '.'), 0, 0, "R", 0);
    $oPdf->cell(37, 6, number_format($nTotalRealizado, 2, ',', '.'), 0, 0, "R", 0);
    $oPdf->cell(37, 6, number_format($nTotalRealizado - $nTotalReconhecido, 2, ',', '.'), 0, 1, "R", 0);

    if($lTemParcelaModificada) {
        $oPdf->cell(40, 6, "* Parcelas reconhecidas e não liquidadas", 0, 1, "L", 0);
    }
}
