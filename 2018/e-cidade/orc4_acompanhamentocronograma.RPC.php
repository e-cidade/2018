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

require_once(modification("model/cronogramaFinanceiro.model.php"));
require_once(modification("model/cronogramaBaseReceita.model.php"));
require_once(modification("model/cronogramaMetaReceita.model.php"));
require_once(modification("model/cronogramaMetaDespesa.model.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

define('PATH_MENSAGEM_ACOMPANHAMENTO', 'financeiro.orcamento.orc4_acompanhamentocronograma.');

$oParam             = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->status   = 1;
$oRetorno->message  = '';
$oRetorno->itens    = '';
$oRetorno->erro     = false;
$oRetorno->mensagem = '';

$iInstituicaoSessao = db_getsession('DB_instit');
$iAnoSessao         = db_getsession('DB_anousu');

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "salvar":

      $oAcompanhamento = new AcompanhamentoCronograma();
      $oAcompanhamento->setDescricao(db_stdClass::normalizeStringJsonEscapeString($oParam->descricao));
      $oAcompanhamento->setMes($oParam->mes);
      $oAcompanhamento->setAno($oParam->ano);
      $oAcompanhamento->setDataCadastro(new DBDate(date("Y-m-d", db_getsession("DB_datausu"))));
      $oAcompanhamento->setUsuario(new UsuarioSistema(db_getsession("DB_id_usuario")));
      $oAcompanhamento->incluirAbertura(new cronogramaFinanceiro($oParam->perspectiva));

      $oRetorno->mensagem = _M(PATH_MENSAGEM_ACOMPANHAMENTO.'abertura_incluida');
      $oRetorno->codigo   = $oAcompanhamento->getPerspectiva();

      break;

    case "alterarValorMes" :

      /**
       * Altera o valor do mes da previsao meta
       */
      $nPercentual = 0;
      $oRetorno->iCodRec           = 0;
      $oRetorno->lDesdobra         = false;
      $oRetorno->nPercentualAjuste = 0;
      $oRetorno->nValorAjuste      = 0;
      $oRetorno->nValor            = 0;
      $oRetorno->nPercentual       = 0;
      $oRetorno->iMes              = $oParam->iIndiceMes;
      $nValorTotalDezembro         = 0;
      $nValorTotal                 = 0;
      $iStatusOk                   = 1;
      $iStatusErro                 = 2;
      $iSender                     = $oParam->iSender;

      for ($iIndiceReceita = 0; $iIndiceReceita < count($oParam->iIndiceReceita); $iIndiceReceita++) {

        $iReceita      = $oParam->iIndiceReceita[$iIndiceReceita];
        $oDadosRetorno = new stdClass();

        if (isset($_SESSION["cronogramabases"][$iReceita])) {

          $oBaseCalculo = $_SESSION["cronogramabases"][$iReceita]->aMetas;
          /**
           * Verificamos o valor digitado pelo usuário. Se é maior que o valor do mes, cancelamos a operação;
           */
          $nValor = $oParam->valor;

          if (($oParam->iTipo == cronogramaFinanceiro::TIPO_RECEITA) && $oBaseCalculo->getPercentualDesbramento() > 0) {

            /**
             * Ajusta o valor de acordo com o percentual do desdobramento
             */
            $nValor = round(($oParam->valor * $oBaseCalculo->getPercentualDesbramento()) / 100, 0);
          }

          if ($oParam->iTipo == cronogramaFinanceiro::TIPO_RECEITA) {

            if ($oBaseCalculo->isDeducao() && $nValor < $oBaseCalculo->getValorTotal() ||
              !$oBaseCalculo->isDeducao() && $nValor > $oBaseCalculo->getValorTotal()) {

              $oRetorno->sConcarpeculiar = $_SESSION["cronogramabases"][$oParam->iSender]->o70_concarpeculiar;
              $oRetorno->iCodRec = $oBaseCalculo->getConta();

              if (count($_SESSION["cronogramabases"][$oParam->iSender]->aDesdobramentos) > 0) {

                $oRetorno->sConcarpeculiar = $_SESSION["cronogramabases"][$oParam->iSender]->o70_concarpeculiar;
                $oRetorno->iCodRec  = $_SESSION["cronogramabases"][$oParam->iSender]->o57_codfon;
              }
            }

          }

          if ($oRetorno->status == $iStatusOk) {

            /**
             * Recalcula o percentual
             */
            $iIndiceCorrente  = $oRetorno->iMes - 1;
            $nTotalValorgrupo = $oParam->valor;
            $aMeses           = $_SESSION["cronogramabases"][$iSender]->aMetas->dados;
            /**
             * Calculo dos valores desdobrados por mes
             */
            $aValorNoMes                   = array();
            $aValorNoMes[$iIndiceCorrente] = $oParam->valor;
            foreach ($aMeses as $key => $oMes) {

              // Pula caso seja o mês que foi modificado
              if ($key == $iIndiceCorrente) {
                continue;
              }
              $aValorNoMes[$key] = $oMes->valor;
              $nTotalValorgrupo += $oMes->valor;
            }

            $aPercentuais                                        = array();
            $oBaseCalculo->dados[$oParam->iIndiceMes - 1]->valor = $nValor;
            $nPercentualTotal                                    = 0;
            foreach ($aMeses as $iIndice => $oMes) {

              $nPercentual                               = round(($aValorNoMes[$iIndice] * 100) / $nTotalValorgrupo, 2);
              $nPercentualTotal                         += $nPercentual;
              $oBaseCalculo->dados[$iIndice]->percentual = $nPercentual;
              $aPercentuais[$iIndice]                    = $nPercentual;
            }

            if ($oParam->iTipo == cronogramaFinanceiro::TIPO_RECEITA) {

              /**
               * Retornamos os valores para a aplicação
               */
              $oDadosRetorno->iCodRec           = $oBaseCalculo->getConta();
              $oDadosRetorno->sConcarpeculiar   = $oBaseCalculo->getCaracteristicaPeculiar();
              $oDadosRetorno->iMes              = $oParam->iIndiceMes;
              $oDadosRetorno->nValor            = $nValor;
              $oDadosRetorno->nPercentual       = $nPercentual;

              if (count($_SESSION["cronogramabases"][$iSender]->aDesdobramentos) > 0) {

                $oRetorno->nPercentual        = $nPercentual;
                $oRetorno->nValor            += $nValor;
              }

              if (count($_SESSION["cronogramabases"][$iSender]->aDesdobramentos) > 0) {

                $_SESSION["cronogramabases"][$iSender]->aMetas->dados[$oParam->iIndiceMes-1]->valor = $oRetorno->nValor;
                $oRetorno->iCodRec   = $_SESSION["cronogramabases"][$iSender]->o57_codfon;
                $oRetorno->lDesdobra = true;
              }

              $oRetorno->itens[]  = $oDadosRetorno;
            } else if ($oParam->iTipo == cronogramaFinanceiro::TIPO_DESPESA) {

              /*
               * Retornamos os valores para a aplicação
               */
              $oRetorno->iCodDesp          =  $oBaseCalculo->getDespesa();
              $oRetorno->iMes              =  $oParam->iIndiceMes;
              $oRetorno->nPercentual       =  $nPercentual;
              $oRetorno->nValorAjuste      =  $oBaseCalculo->dados[11]->valor;
              $oRetorno->nPercentualAjuste =  $oBaseCalculo->dados[11]->percentual;
            }

            // Calcula diferença para fazer o ajuste
            $nDiferencaPercentual                 = 100 - $nPercentualTotal;
            $oBaseCalculo->dados[11]->percentual += $nDiferencaPercentual;
            // Faz o ajuste no retorno de percentuais
            $aPercentuais[11]                     = $oBaseCalculo->dados[11]->percentual;
            $oRetorno->aPercentuais               = $aPercentuais;

          }

          $oRetorno->sConcarpeculiar = '';
          if ($oParam->iTipo == cronogramaFinanceiro::TIPO_RECEITA) {
            $oRetorno->sConcarpeculiar = $_SESSION["cronogramabases"][$oParam->iSender]->o70_concarpeculiar;
          }
        }
      }
      break;

    case "getInformacoesDespesa":

      if (!empty($_SESSION["cronogramabases"][$oParam->iIndice])) {

        $oStdDespesa = $_SESSION["cronogramabases"][$oParam->iIndice];

        $oDataInicial = new DBDate("{$oParam->iAno}-{$oParam->iMes}-01");
        $oDataFinal   = new DBDate("{$oParam->iAno}-{$oParam->iMes}-".cal_days_in_month(CAL_GREGORIAN, $oParam->iMes, $oParam->iAno));
        $oInformacaoDespesa = cronogramaMetaDespesa::getInformacaoDespesa($oStdDespesa, $oParam->iNivel, $oDataInicial, $oDataFinal);
        if ($oParam->iMes > 1) {
          $oInformacaoDespesa->setValorReestimado($_SESSION["cronogramabases"][$oParam->iIndice]->aMetas->dados[$oParam->iMes -1]->valor);
        }
        $oStdInformacaoDespesa = new stdClass();
        $oStdInformacaoDespesa->nPago              = $oInformacaoDespesa->getValorPago();
        $oStdInformacaoDespesa->nPrevisto          = $oInformacaoDespesa->getValorPrevisto();
        $oStdInformacaoDespesa->nCotaMensal        = $oInformacaoDespesa->getValorCotaMensal();
        $oStdInformacaoDespesa->nPrevistoMenosPago = $oInformacaoDespesa->getDiferenca();
        $oRetorno->valores = $oStdInformacaoDespesa;
      }

      $oRetorno->erro = false;
      break;

    case 'getInformacoesReceita':

      $oStdValor = new stdClass();
      $oStdValor->nPrevisto = 0;
      $oStdValor->nRealizado = 0;
      $oStdValor->nPrevistoMenosRealizado = 0;

      if (!empty($_SESSION["cronogramabases"][$oParam->iIndice])) {

        $oStdReceita  = $_SESSION["cronogramabases"][$oParam->iIndice];
        $oStdReceita->instituicao = db_getsession('DB_instit');
        $oDataInicial = new DBDate("{$oParam->iAno}-{$oParam->iMes}-01");
        $oDataFinal   = new DBDate("{$oParam->iAno}-{$oParam->iMes}-" . cal_days_in_month(CAL_GREGORIAN, $oParam->iMes, $oParam->iAno));
        $oInformacaoReceita = cronogramaMetaReceita::getInformacaoReceita($oStdReceita, $oDataInicial, $oDataFinal);

        $oStdValor = new stdClass();
        $oStdValor->nPrevisto = $oInformacaoReceita->getValorPrevisto();
        $oStdValor->nRealizado = $oInformacaoReceita->getValorRealizado();
        $oStdValor->nPrevistoMenosRealizado = $oInformacaoReceita->getRealizadoMenosPrevisto();
        if ($oParam->iMes > 1) {

          $nValorReestimado = $_SESSION["cronogramabases"][$oParam->iIndice]->aMetas->dados[$oParam->iMes -1]->valor;
          $oStdValor->nPrevistoMenosRealizado = $oInformacaoReceita->getValorRealizado() - $nValorReestimado;
        }
      }
      $oRetorno->valores = $oStdValor;

      break;

    default:
      throw new Exception(_M(PATH_MENSAGEM_ACOMPANHAMENTO . "opcao_indefinida", (object)array('exec' => $oParam->exec)));
  }

  db_fim_transacao(false);

} catch (Exception $e) {

  db_fim_transacao(true);
  $oRetorno->mensagem = $e->getMessage();
  $oRetorno->erro     = true;
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo JSON::create()->stringify($oRetorno);
