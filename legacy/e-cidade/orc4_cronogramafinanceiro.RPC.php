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

require_once(modification("model/cronogramaFinanceiro.model.php"));
require_once(modification("model/cronogramaBaseReceita.model.php"));
require_once(modification("model/cronogramaMetaReceita.model.php"));
require_once(modification("model/cronogramaMetaDespesa.model.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));

$oJson       = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';
$oRetorno->itens   = '';

switch (trim($oParam->exec)) {

  case "processarBase" :

    try {

      $oCronograma = new cronogramaFinanceiro($oParam->iPerspectiva);
      $oCronograma->CalcularBases(1);

      db_fim_transacao(false);

    } catch (Exception $eErro ) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());

    }

    break;

  case "getDados" :

    try {

      $iNumRegPagina = 16;
      db_inicio_transacao();
      $oRetorno->perspectiva_bloqueada = false;
      if (!isset($_SESSION["cronogramabases"]) || $oParam->lClearSession) {


        /**
         * Verificamos se a Perspectiva já possui um acompanhamento
         */
        if ($oParam->iTipo == 1 || $oParam->iTipo == 2) {

          $oCronograma     = new cronogramaFinanceiro($oParam->iPerspectiva);
          $oCronograma->setInstituicoes(array(db_getsession("DB_instit")));
          if ($oParam->iTipo == 1) {
            $aDados       = $oCronograma->getBaseReceitas($oParam->sEstrutural, $oParam->iRecurso);
          } else if ($oParam->iTipo == 2) {
            $aDados       = $oCronograma->getMetasReceita($oParam->sEstrutural, $oParam->iRecurso);
          }


        } else if ($oParam->iTipo == 3) {

          $oCronograma = new cronogramaFinanceiro($oParam->iPerspectiva);
          $oCronograma->setInstituicoes(array(db_getsession("DB_instit")));
          $aDados      = $oCronograma->getMetasDespesa($oParam->iAgrupa, $oParam->sEstrutural, $oParam->iRecurso);

        }
        $_SESSION["cronogramaPossuiAcompanhamento"] = $oCronograma->temAcompanhamento();
        $_SESSION["cronogramabases"]      = $aDados;
        $_SESSION["cronogramabasestotal"] = count($aDados);
        $_SESSION["cronogramabasespages"] = ceil(count($aDados)/$iNumRegPagina);
      } else {

        $aDados = $_SESSION["cronogramabases"];
        $_SESSION["cronogramabasespages"] = ceil(count($aDados)/$iNumRegPagina);

      }
      $oRetorno->perspectiva_bloqueada  = $_SESSION["cronogramaPossuiAcompanhamento"];

      $iFetch = 0;
      if ($oParam->iPagina > 1) {
        $iFetch = ($oParam->iPagina*$iNumRegPagina) - $iNumRegPagina;
      }

      $iLimit = $iFetch + $iNumRegPagina;

      $iRegistrosUltimasPagina = count($aDados) % $iNumRegPagina;
      $oRetorno->totalPaginas = $_SESSION["cronogramabasespages"];
      $oRetorno->fecth        = $iFetch;
      if ($oParam->iPagina == $_SESSION["cronogramabasespages"]) {

         $oRetorno->pagina = $_SESSION["cronogramabasespages"];
         $iLimit = $_SESSION["cronogramabasestotal"];
         if ($_SESSION["cronogramabasestotal"] == $iNumRegPagina) {
           $iFetch = 0;
         }
      } else {
       $oRetorno->pagina = $oParam->iPagina;
      }
      $aDadosFetch = array();
      for ($i = $iFetch; $i < $iLimit; $i++) {

        if ($oParam->iTipo != 3 && (isset($aDados[$i]) && $aDados[$i]->o70_codrec == "" && count($aDados[$i]->aDesdobramentos) == 0)) {
          continue;
        }

        if(!isset($aDados[$i])) {
          $aDados[$i] = new stdClass();
        }

        $aDados[$i]->index = $i;
        $aDadosFetch[]     = $aDados[$i];

      }

      $oRetorno->itens = $aDadosFetch;
      db_fim_transacao(false);
    } catch (Exception $eErro ) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());

    }
    break;

  case "getDadosAnosBaseReceitaAno":

    /**
     * Retornamos as Bases de Calculo do mes selecionado
     */
    if (isset($_SESSION["cronogramabases"][$oParam->iIndiceReceita])) {

      $aAnos = $_SESSION["cronogramabases"][$oParam->iIndiceReceita]->aBases->getAnos();

      $oRetorno->itens          = $aAnos;
      $oRetorno->iSequencial    = $oParam->iCodigoBaseCalculo;
      $oRetorno->iIndiceReceita = $oParam->iIndiceReceita;

    }
    break;

  case "salvarAnos" :

    if (isset($_SESSION["cronogramabases"][$oParam->iIndiceReceita])) {

      $oBaseCalculo = $_SESSION["cronogramabases"][$oParam->iIndiceReceita]->aBases;
      foreach ($oParam->aAnos as $oAno) {

        $oBaseCalculo->setAnoNaMedia($oAno->ano,$oAno->usarmedia);
        $oBaseCalculo->setValorAno($oAno->ano, $oAno->valor);

      }
      /**
       * Ajustamos os valores dos meses, da receita
       */
      $aMeses = array();
      foreach ($oBaseCalculo->dados as $oMes) {

        $nNovoValor = ($oBaseCalculo->getValorMedia()*$oMes->percentual)/100;
        $oBaseCalculo->setValorMes($oMes->mes, $nNovoValor);
        $oBaseCalculo->dados[$oMes->mes-1]->valormedia = $oBaseCalculo->getValorMedia();


        $oMesRetornar                   = new stdClass();
        $oMesRetornar->nValorPercentual = $oMes->percentual;
        $oMesRetornar->nValorMedia      = $oBaseCalculo->getValorMedia();
        $oMesRetornar->nValor           = $nNovoValor;
        $oMesRetornar->iMes             = $oMes->mes;
        $aMeses[]  = $oMesRetornar;

      }
      $oRetorno->nValorMedia =  $oBaseCalculo->getValorMedia();
      $oRetorno->iCodRec     =  $oBaseCalculo->getReceita();
      $oRetorno->itens       = $aMeses;
    }
    break;

  case "salvarReceita" :

    if (isset($_SESSION["cronogramabases"])) {

      if ( $_SESSION["cronogramaPossuiAcompanhamento"]) {
        break;
      }
      try {

        db_inicio_transacao();
        foreach ($_SESSION["cronogramabases"] as $oBase) {

          if ($oParam->iTipo == 1) {
            if ($oBase->o70_codrec == "") {
              continue;
            }
            $oBase->aBases->save();
          } else {
            if ($oParam->iTipo == 2 && $oBase->o70_codrec == "") {
              continue;
            }
            $oBase->aMetas->save();
          }
        }
        db_fim_transacao(false);
      } catch (Exception $eErro){

        db_fim_transacao(true);
        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($eErro->getMessage());

      }
    }
    break;

  case "alterarValorMes" :

    if ( $_SESSION["cronogramaPossuiAcompanhamento"]) {
      break;
    }

    /**
     * Altera o valor do mes da previsaoou meta
     */
    $nPercentual = 0;
    $oRetorno->iCodRec           = 0;
    $oRetorno->lDesdobra         = false;
    $oRetorno->nPercentualAjuste =  0;
    $oRetorno->nValorAjuste      =  0;
    $oRetorno->nValor            =  0;
    $oRetorno->nPercentual       =  0;
    $oRetorno->iMes              =  $oParam->iIndiceMes;
    $nValorTotalDezembro         = 0;
    $nValorTotal                 = 0;

    for ($iIndiceReceita = 0; $iIndiceReceita < count($oParam->iIndiceReceita); $iIndiceReceita++) {

      $iReceita = $oParam->iIndiceReceita[$iIndiceReceita];
      if (isset($_SESSION["cronogramabases"][$iReceita])) {

        if ($oParam->iTipo == 1) {
          $oBaseCalculo = $_SESSION["cronogramabases"][$iReceita]->aBases;
        } else {
          $oBaseCalculo = $_SESSION["cronogramabases"][$iReceita]->aMetas;
        }

        /**
         * Verificamos o valor digitado pelo usuário. se For maior que o valor do mes, cancelamos a operação;
         */
        $nValor = $oParam->valor;
        if (($oParam->iTipo == 2) && $oBaseCalculo->getPercentualDesbramento() > 0) {
          $nValor = round(($oParam->valor * $oBaseCalculo->getPercentualDesbramento()) / 100, 2);
        }

        $sMensagemValorMaior = urlencode("Você não pode prever para o mês um valor maior que o orçado para o ano.");
        if ($oParam->iTipo == 1 || $oParam->iTipo == 2) {
          if ($oBaseCalculo->isDeducao() && $nValor < $oBaseCalculo->getValorTotal() ||
             !$oBaseCalculo->isDeducao() && $nValor > $oBaseCalculo->getValorTotal()) {

            $oRetorno->status          = 2;
            $oRetorno->iMes            = $oParam->iIndiceMes;
            $oRetorno->message         = $sMensagemValorMaior;
            $oRetorno->iCodRec         = $oBaseCalculo->getReceita();
            $oRetorno->sConcarpeculiar = '';
            if ($oParam->iTipo == 2) {

              $oRetorno->sConcarpeculiar = $_SESSION["cronogramabases"][$oParam->iSender]->o70_concarpeculiar;
              $oRetorno->iCodRec = $oBaseCalculo->getConta();
            }
            if (count($_SESSION["cronogramabases"][$oParam->iSender]->aDesdobramentos) > 0) {

             $oRetorno->sConcarpeculiar = $_SESSION["cronogramabases"][$oParam->iSender]->o70_concarpeculiar;
             $oRetorno->iCodRec  = $_SESSION["cronogramabases"][$oParam->iSender]->o57_codfon;
            }
          }

        } else {

          if ($nValor > $oBaseCalculo->getValorTotal()) {

            $oRetorno->status   = 2;
            $oRetorno->iMes     =  $oParam->iIndiceMes;
            $oRetorno->iCodDesp =  $oBaseCalculo->getDespesa();
            $oRetorno->message  = $sMensagemValorMaior;

          }
        }
        if ($oRetorno->status == 1) {

          $nPercentual  = $oBaseCalculo->setValorMes($oParam->iIndiceMes, $nValor);
          $oBaseCalculo->dados[$oParam->iIndiceMes-1]->valor      = $nValor;
          $oBaseCalculo->dados[$oParam->iIndiceMes-1]->percentual = $nPercentual;

          /**
           * Devemos fazer o ajuste em Dezembro, a maior/menor caso for o alteração da meta
           */
          if ($oParam->iTipo == 2 ||$oParam->iTipo == 3) {

            $nValorTotalCalculado = 0;
            $nPercentualTotal = 0;
            foreach ($oBaseCalculo->dados as $oMeses) {

              $nValorTotalCalculado += $oMeses->valor;
              $nPercentualTotal     += $oMeses->percentual;

            }

            /**
             * Fizemos o arredondamento , caso necessário em Dezembro;
             */
            $nValorPrevisto = $oBaseCalculo->getValorTotal();

            if ((round($nPercentualTotal,2) < 100) || ($nValorTotalCalculado < $nValorPrevisto)) {

              $nPercentualDiferenca                 = (100 - $nPercentualTotal);
              $oBaseCalculo->dados[11]->valor      += round(($nValorPrevisto-$nValorTotalCalculado));
              $oBaseCalculo->dados[11]->percentual += $nPercentualDiferenca;

            } else if ((round($nPercentualTotal, 2) > 100) || ($nValorTotalCalculado > $nValorPrevisto)) {

              $nPercentualDiferenca                 = ($nPercentualTotal - 100);
              $oBaseCalculo->dados[11]->valor      -= round($nValorTotalCalculado - $nValorPrevisto);
              $oBaseCalculo->dados[11]->percentual -= round(($nPercentualDiferenca),2);

            }

            $oBaseCalculo->setValorMes(12, $oBaseCalculo->dados[11]->valor);
            $oDadosRetorno = new stdClass();

            $oDadosRetorno->nPercentualAjuste =  $oBaseCalculo->dados[11]->percentual;
            $oDadosRetorno->nValorAjuste      =  $oBaseCalculo->dados[11]->valor;
          }
          if ($oParam->iTipo == 1 || $oParam->iTipo == 2) {
            /*
             * Retornamos os valores para a aplicação
             */
            $oDadosRetorno->iCodRec  =  $oBaseCalculo->getReceita();
            if ($oParam->iTipo == 2) {

              $oDadosRetorno->iCodRec         = $oBaseCalculo->getConta();
              $oDadosRetorno->sConcarpeculiar = $oBaseCalculo->getCaracteristicaPeculiar();
            }
            $oDadosRetorno->iMes              =  $oParam->iIndiceMes;
            $oDadosRetorno->nValor            =  $nValor;
            $oDadosRetorno->nPercentual       =  $nPercentual;
            $iSender                          = $oParam->iSender;
            if (count($_SESSION["cronogramabases"][$iSender]->aDesdobramentos) > 0) {

              $oRetorno->nPercentual       = $nPercentual;
              $oRetorno->nValor            += $nValor;
              $oRetorno->nPercentualAjuste  =  $oBaseCalculo->dados[11]->percentual;
              $oRetorno->nValorAjuste      +=  $oBaseCalculo->dados[11]->valor;

            }
            if (($oBaseCalculo->isDeducao() && $oBaseCalculo->dados[11]->valor > 0) ||
                (!$oBaseCalculo->isDeducao() && $oBaseCalculo->dados[11]->valor < 0)) {

              $sMSg  = "Você está fazendo ajustes nas previsões mensais que ultrapassariam o total do orçamento. ";
              $sMSg .= "Se não reavaliar suas previsões ficará com meta negativa no mês de Dezembro";
              $oRetorno->message = urlencode($sMSg);
            }
            if (count($_SESSION["cronogramabases"][$iSender]->aDesdobramentos) > 0) {

              $_SESSION["cronogramabases"][$iSender]->aMetas->dados[11]->percentual  = $oRetorno->nPercentualAjuste;
              $_SESSION["cronogramabases"][$iSender]->aMetas->dados[11]->valor       = $oRetorno->nValorAjuste;
              $_SESSION["cronogramabases"][$iSender]->aMetas->dados[$oParam->iIndiceMes-1]->percentual  = $oRetorno->nPercentual;
              $_SESSION["cronogramabases"][$iSender]->aMetas->dados[$oParam->iIndiceMes-1]->valor       = $oRetorno->nValor;
              $oRetorno->iCodRec   = $_SESSION["cronogramabases"][$iSender]->o57_codfon;
              $oRetorno->lDesdobra = true;
            }
            $oRetorno->itens[]  = $oDadosRetorno;
          } else if ($oParam->iTipo == 3) {

            /*
             * Retornamos os valores para a aplicação
             */
            $oRetorno->iCodDesp          =  $oBaseCalculo->getDespesa();
            $oRetorno->iMes              =  $oParam->iIndiceMes;
            $oRetorno->nPercentual       =  $nPercentual;
            $oRetorno->nValorAjuste      =  $oBaseCalculo->dados[11]->valor;
            $oRetorno->nPercentualAjuste =  $oBaseCalculo->dados[11]->percentual;
            if ($oBaseCalculo->dados[11]->valor < 0) {

              $sMSg  = "Você está fazendo ajustes nas previsões mensais que ultrapassariam o total do orçamento. ";
              $sMSg .= "Se não reavaliar suas previsões ficará com meta negativa no mês de Dezembro";
              $oRetorno->message = urlencode($sMSg);

            }
          }
        }

        $oRetorno->sConcarpeculiar = '';
        if ($oParam->iTipo == 2) {
          $oRetorno->sConcarpeculiar = $_SESSION["cronogramabases"][$oParam->iSender]->o70_concarpeculiar;
        }
      }
    }

    break;

  case "processarMeta":

    try {

      db_inicio_transacao();
      $oCronograma = new cronogramaFinanceiro($oParam->iPerspectiva);
      $oCronograma->CalcularBases(2);
      $oRetorno->aReceitasInconsistente = $oCronograma->getReceitasComValorNegativa('', '', true);
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao (true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());

    }
    break;

  case "processarMetaDespesa":

    try {

      db_inicio_transacao();
      $oCronograma = new cronogramaFinanceiro($oParam->iPerspectiva);
      $oCronograma->CalcularBases(3);
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao (true);
      $oRetorno->status  = 2;
      $oRetorno->recurso = $eErro->getCode();
      $oRetorno->message = urlencode($eErro->getMessage());

    }
    break;

  case "getReceitasNegativas":

    try {

      db_inicio_transacao();
      $oCronograma     = new cronogramaFinanceiro($oParam->iPerspectiva);
      $oRetorno->itens = $oCronograma->getReceitasComValorNegativa($oParam->iRecurso, '', $oParam->lNegativas);

      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao (true);
      $oRetorno->status  = 2;
      $oRetorno->recurso = $eErro->getCode();
      $oRetorno->message = urlencode($eErro->getMessage());

    }
    break;

  case "realizaAjusteReceita":

    try {

      db_inicio_transacao();
      $oCronograma     = new cronogramaFinanceiro($oParam->iPerspectiva);

      foreach ($oParam->aReceitas as $oReceita) {

        $oCronograma->corrigeReceita($oReceita->iCodigo,
                                     $oReceita->nValorMes,
                                     $oReceita->nPercentual,
                                     $oReceita->nAjusteDezembro,
                                     $oReceita->nPercentualAjuste
                                     );
      }
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      db_fim_transacao (true);
      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());

    }
    break;
}
echo $oJson->encode($oRetorno);
?>