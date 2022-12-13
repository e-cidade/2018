<?php
/**
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

use ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\RegimeCompetencia as RegimeCompetenciaModel;
use ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\RegimeCompetencia;

require_once(modification("model/ProgramacaoFinanceira.model.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oJson               = JSON::create();
$oRetorno            = new stdClass();
$oParam              = $oJson->parse(str_replace("\\","",$_POST["json"]));
$oRetorno->erro      = false;
$oRetorno->aParcelas = array();
$iIdUsuario          = db_getsession('DB_id_usuario');

$oRegimeCompetenciaRepository = new RegimeCompetencia();

try {
    db_inicio_transacao();
    switch ($oParam->exec) {
        case "getDadosAcordo":
            $oAcordo                         = AcordoRepository::getByCodigo($oParam->acordo);
            $nValorAcordo                    = $oAcordo->getValoresItens();
            $oRetorno->programacao           = null;
            $oRetorno->despesa_antecipada    = false;
            $oRetorno->valor_acordo          = $nValorAcordo->valoratual;
            $oRetorno->parcelas_reconhecidas = false;
            $oRetorno->parcelas_processadas  = false;
            $oRetorno->saldo_programar       = $oRetorno->valor_acordo;
            $oProgramacao                    = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);
            $oRetorno->parcelas              = array();

            if (!empty($oProgramacao)) {
                $oRetorno->saldo_programar         = $oProgramacao->getSaldoProgramar();
                $dadosProgramacao                  = new \stdClass();
                $dadosProgramacao->codigo          = $oProgramacao->getCodigo();
                $dadosProgramacao->conta           = $oProgramacao->getConta()->getCodigoConta();
                $dadosProgramacao->descricao_conta = $oProgramacao->getConta()->getDescricao();
                $dadosProgramacao->tipo            = $oProgramacao->isDespesaAntecipada() ? 1 : 2;
                $oRetorno->programacao             = $dadosProgramacao;
                $aParcelas                         = $oRegimeCompetenciaRepository->getParcelasDoRegime($oProgramacao);
                $oRetorno->parcelas                = getParcelas($oRegimeCompetenciaRepository, $oProgramacao);
                $oRetorno->despesa_antecipada      = $oProgramacao->isDespesaAntecipada();

                foreach ($oRetorno->parcelas as $parcela) {
                    if ($parcela->reconhecida && $parcela->numero > 0) {
                        $oRetorno->parcelas_reconhecidas = true;
                        break;
                    }
                    if ($parcela->numero > 0) {
                        $oRetorno->parcelas_processadas = true;
                    }
                }
            }

            break;


        case "processar":
            $oAcordo              = AcordoRepository::getByCodigo($oParam->acordo);
            $oProgramacao         = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);
            $aParcelasProgramadas = array();

            if ($oProgramacao) {
                $aParcelasProgramadas = $oRegimeCompetenciaRepository->getParcelasDoRegime($oProgramacao);
            }

            if (empty($oProgramacao)) {
                $oProgramacao = new RegimeCompetenciaModel();
                $oProgramacao->setAcordo($oAcordo);
            }

            $oProgramacao->setDespesaAntecipada($oParam->tipo == 1);
            $oProgramacao->setConta(ContaPlanoPCASPRepository::getContaByCodigo($oParam->conta, db_getsession("DB_anousu")));
            $oRegimeCompetenciaRepository->persist($oProgramacao);
            $parcelas = $oProgramacao->processarParcelas($oParam->numero_parcelas, $oParam->mes_inicial, db_getsession("DB_anousu"), $oParam->valor);
            $oRetorno->parcelas = array();

            if (count($aParcelasProgramadas) > 0) {
                foreach ($aParcelasProgramadas as $oParcelaProgramada) {
                    foreach ($parcelas as $iParcela => $oParcela) {
                        if ($oParcelaProgramada->getCompetencia()->comparar($oParcela->getCompetencia()) && $oParcelaProgramada->getNumero() != 0) {
                            $nValor = $oParcelaProgramada->getValor() + $oParcela->getValor();
                            $oParcelaProgramada->setValor($nValor);
                            unset($parcelas[$iParcela]);
                            $parcelas[] = $oParcelaProgramada;
                        }
                    }
                }
            }

            foreach ($parcelas as $parcela) {
                $oRegimeCompetenciaRepository->persistirParcela($oProgramacao, $parcela);
            }

            $oRetorno->message = "Parcelas processadas com sucesso.";
            break;

        case 'salvarParcelas':
            $oAcordo      = AcordoRepository::getByCodigo($oParam->acordo);
            $oProgramacao = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);
            $oProgramacao->setDespesaAntecipada($oParam->tipo == 1);
            $oProgramacao->setConta(ContaPlanoPCASPRepository::getContaByCodigo($oParam->conta, db_getsession("DB_anousu")));
            $oRegimeCompetenciaRepository->persist($oProgramacao);

            foreach ($oParam->parcelas as $parcela) {
                $oCompetencia = DBCompetencia::createFromString($parcela->competencia);
                $oParcelaItem = new \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela();
                $oParcelaItem->setValor($parcela->valor);
                $oParcelaItem->setCodigo($parcela->codigo);
                $oParcelaItem->setCompetencia($oCompetencia);
                $oParcelaItem->setNumero($parcela->numero);
                $oRegimeCompetenciaRepository->persistirParcela($oProgramacao, $oParcelaItem);
            }

            $oRetorno->message = "Parcelas salvas com sucesso!";
            break;

        case 'excluirParcelas':
            $oAcordo       = AcordoRepository::getByCodigo($oParam->acordo);
            $oProgramacao  = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);
            $listaParcelas = array_map(function ($parcela) {

                $oParcela = new \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela();
                $oParcela->setCodigo($parcela);
                return $oParcela;
            }, $oParam->parcelas);
            $oRegimeCompetenciaRepository->removerParcelas($oProgramacao, $listaParcelas);
            $oRetorno->message = 'Parcelas selecionadas removidas com sucesso!';
            break;

        case 'adicionarParcela':
            $oAcordo      = AcordoRepository::getByCodigo($oParam->acordo);
            $oProgramacao = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);
            $oCompetencia = DBCompetencia::createFromString($oParam->parcela->competencia);
            $oParcelaItem = new \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela();
            $oParcelaItem->setNumero($oParam->parcela->numero);
            $oParcelaItem->setCompetencia($oCompetencia);
            $oParcelaItem->setValor($oParam->parcela->valor);
            $oRegimeCompetenciaRepository->persistirParcela($oProgramacao, $oParcelaItem);
            $oRetorno->message = "Parcela adicionada com sucesso!";
            break;

        case 'getParcelas':
            $oAcordo                   = AcordoRepository::getByCodigo($oParam->acordo);
            $oProgramacao              = $oRegimeCompetenciaRepository->getByAcordo($oAcordo);
            $oRetorno->parcelas        = getParcelas($oRegimeCompetenciaRepository, $oProgramacao);
            $oRetorno->saldo_programar = $oProgramacao->getSaldoProgramar();
            break;
    }

    db_fim_transacao(false);
} catch (Exception $e) {
    db_fim_transacao(true);
    $oRetorno->erro    = true;
    $oRetorno->message = $e->getMessage();
}

echo $oJson->stringify($oRetorno);


function getParcelas(RegimeCompetencia $oRegimeCompetenciaRepository, RegimeCompetenciaModel $oProgramacao)
{

    $aParcelas = $oRegimeCompetenciaRepository->getParcelasDoRegime($oProgramacao);
    $parcelas  = array();

    foreach ($aParcelas as $parcela) {
        $dadosParcela              = new \stdClass();
        $dadosParcela->codigo      = $parcela->getCodigo();
        $dadosParcela->numero      = $parcela->getNumero();
        $dadosParcela->competencia = $parcela->getCompetencia()->getCompetencia(DBCompetencia::FORMATO_MMAAAA);
        $dadosParcela->valor       = $parcela->getValor();
        $dadosParcela->reconhecida = $parcela->isReconhecida();
        $parcelas[]                = $dadosParcela;
    }
    return $parcelas;
}
