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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification(Modification::getFile('model/agendaPagamento.model.php')));
require_once(modification("model/slip.model.php"));
require_once(modification("model/caixa/slip/TransferenciaFactory.model.php"));
require_once(modification("model/caixa/slip/Transferencia.model.php"));
require_once(modification("model/caixa/slip/Transferencia.model.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarSlip.model.php"));
require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));


db_app::import("CgmFactory");
db_app::import("MaterialCompras");
db_app::import("configuracao.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("Dotacao");
db_app::import("empenho.*");
db_app::import("exceptions.*");

$oJson    = new services_json();
$oParam   = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = "";

switch ($oParam->exec) {

  case "getMovimentos" :

    $oAgendaPagamento = new agendaPagamento();
    $oAgendaPagamento->setUrlEncode(true);
    $oRetorno = new stdClass();
    $oRetorno->status  = 1;
    $oRetorno->message = "";
    $aSlips = $oAgendaPagamento->getMovimentosSlip(
                                                   $oParam->options->dtIni,
                                                   $oParam->options->dtFim,
                                                   $oParam->options->agrupar,
                                                   $oParam->options->codigoordem
                                                  );
    if (count($aSlips) > 0) {

      $oRetorno->aSlips  = $aSlips;
      $oRetorno->agrupar = $oParam->options->agrupar;

    } else {


      $oRetorno->status  = 2;
      $oRetorno->message = urlencode("Não foi encontrados slips");

    }
    echo $oJson->encode($oRetorno);
    break;

  case "gerarSlips" :

    db_inicio_transacao();
    $oRetorno          = new stdClass();
    $oRetorno->status  = 1;
    $oRetorno->message = "";
    $aSlipsRetorno     = array();
    try {

      $oAgendaPagamento = new agendaPagamento();
      $oAgendaPagamento->setUrlEncode(true);
      if (count($oParam->options->aSlips) > 0) {

        foreach ($oParam->options->aSlips as $oSlip) {

          $iSlip = $oAgendaPagamento->gerarSlip($oSlip->iCtaDebito,
                                                $oSlip->iCtaCredito,
                                                $oSlip->nValor,
                                                $oParam->options->dtIni,
                                                $oParam->options->dtFim,
                                                false,
                                                $oParam->options->agrupar,
                                                $oSlip->iCodigoOrdem
                                                );
          $aSlipsRetorno[] = $iSlip;
        }
      }

      /**
       * Vinculamos o slip gerado ao tipo Transferencia Financeira Recebimento
       */
      if (USE_PCASP) {

        foreach ($aSlipsRetorno as $iCodigoSlip) {

        	$oDaoTipoOperacaoVinculo = db_utils::getDao('sliptipooperacaovinculo');
        	$oDaoTipoOperacaoVinculo->k153_slip             = $iCodigoSlip;
        	$oDaoTipoOperacaoVinculo->k153_slipoperacaotipo = 5;
        	$oDaoTipoOperacaoVinculo->incluir($iCodigoSlip);

        	if ($oDaoTipoOperacaoVinculo->erro_status == 0) {

        		$sMensagemErro  = "Não foi possível víncular o tipo de slip ao slip.\n\n";
        		$sMensagemErro .= "Erro Técnico: {$oDaoTipoOperacaoVinculo->erro_msg}";
        		throw new Exception($sMensagemErro);
        	}
        }
      }
      $oRetorno->aSlipsRetorno = $aSlipsRetorno;
      db_fim_transacao(false);
    }
    catch (Exception $eErro) {

      $oRetorno->status = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);

    }
    echo $oJson->encode($oRetorno);
    break;

  case "getArrecExtra" :

    $sJoin  = "";
    $sWhere = "";

    if ($oParam->isFolha) {

      $sJoin   = " inner join rhempenhofolhaempenho    on rh76_numemp         = e60_numemp      ";
      $sJoin  .= " inner join rhempenhofolha           on rh76_rhempenhofolha = rh72_sequencial ";

      $sWhere  = " and rh72_mesusu      = {$oParam->paramFolha->iMesFolha}";
      $sWhere .= " and rh72_anousu      = {$oParam->paramFolha->iAnoFolha}";
      $sWhere .= " and rh72_tipoempenho = 1";
      $sWhere .= " and rh72_siglaarq    = '{$oParam->paramFolha->sSigla}'";
      if ($oParam->paramFolha->sSigla <> 'r20'){
        $sWhere .= " and rh72_seqcompl    = {$oParam->paramFolha->sSemestre}";
      }
      $sWhere .= " and e21_retencaotiporecgrupo  = 2";

    } else {

      $sWhere .= " and e21_retencaotiporecgrupo  = 1";
      if ($oParam->dtIni != "" && $oParam->dtFim == "") {

        $sWhere     .= " and corrente.k12_data >= '".implode("-",array_reverse(explode("/",$oParam->dtIni)))."'";

      } else if ($oParam->dtIni != "" && $oParam->dtFim != "") {

        $dtDataIni   = implode("-",array_reverse(explode("/",$oParam->dtIni)));
        $dtDataFim   = implode("-",array_reverse(explode("/",$oParam->dtFim)));
        $sWhere     .= " and corrente.k12_data between '{$dtDataIni}' and '{$dtDataFim}'";

      } else if ($oParam->dtIni == "" && $oParam->dtFim != "") {

         $dtDataFim   = implode("-",array_reverse(explode("/",$oParam->dtFim)));
         $sWhere     .= " and corrente.k12_data <= '{$dtDataFim}'";
      }
    }

    /**
     * Busca CGM vinculada a retenção
     */
    if ( $oParam->iNumCgm != '' ) {
      $sWhere     .= " and cgm_credor.z01_numcgm = {$oParam->iNumCgm} ";
    }


    if ($oParam->iRecurso != "") {
      $sWhere .= " and k00_recurso = {$oParam->iRecurso}";
    }

    if ($oParam->iReceita != "") {
      $sWhere .= " and tabrec.k02_codigo = {$oParam->iReceita}";
    }

    /* [Extensão] Filtro da Despesa */

    $sSqlArrecadacoesExtra  = "SELECT cornump.k12_numpre, ";
    $sSqlArrecadacoesExtra .= "       corrente.k12_valor, ";
    $sSqlArrecadacoesExtra .= "       corrente.k12_conta as credito, ";
    $sSqlArrecadacoesExtra .= "       k13_descr          as descrcredito, ";
    $sSqlArrecadacoesExtra .= "       k02_reduz          as debito, ";
    $sSqlArrecadacoesExtra .= "       c60_descr          as descrdebito, ";
    $sSqlArrecadacoesExtra .= "       e60_numemp, ";
    $sSqlArrecadacoesExtra .= "       k00_recurso, ";
    $sSqlArrecadacoesExtra .= "       cgm_credor.z01_nome, ";
    $sSqlArrecadacoesExtra .= "       cgm_credor.z01_numcgm, ";
    $sSqlArrecadacoesExtra .= "       e50_codord, ";
    $sSqlArrecadacoesExtra .= "       tabrec.k02_codigo, ";
    $sSqlArrecadacoesExtra .= "       k02_drecei ";
    $sSqlArrecadacoesExtra .= "  from retencaoreceitas ";
    $sSqlArrecadacoesExtra .= "       inner join retencaocorgrupocorrente on e47_retencaoreceita = e23_sequencial ";
    $sSqlArrecadacoesExtra .= "       inner join corgrupocorrente       on k105_sequencial     = e47_corgrupocorrente ";
    $sSqlArrecadacoesExtra .= "       inner join corrente                 on k105_data           = corrente.k12_data ";
    $sSqlArrecadacoesExtra .= "                                          and k105_id             = corrente.k12_id ";
    $sSqlArrecadacoesExtra .= "                                          and k105_autent         = k12_autent ";
    $sSqlArrecadacoesExtra .= "       inner join cornump                  on corrente.k12_data   = cornump.k12_data ";
    $sSqlArrecadacoesExtra .= "                                          and corrente.k12_id     = cornump.k12_id ";
    $sSqlArrecadacoesExtra .= "                                          and corrente.k12_autent = cornump.k12_autent ";
    $sSqlArrecadacoesExtra .= "       inner join retencaotiporec          on e23_retencaotiporec = e21_sequencial ";

    /**
     * Busca CGM vinculada a retenção
     */
    $sSqlArrecadacoesExtra .= "       inner join retencaotiporeccgm       on retencaotiporeccgm.e48_retencaotiporec = retencaotiporec.e21_sequencial ";
    $sSqlArrecadacoesExtra .= "       inner join cgm as cgm_credor        on cgm_credor.z01_numcgm = retencaotiporeccgm.e48_cgm ";

    $sSqlArrecadacoesExtra .= "       inner join tabrec                   on e21_receita         = tabrec.k02_codigo ";
    $sSqlArrecadacoesExtra .= "       inner join tabplan                  on tabrec.k02_codigo   = tabplan.k02_codigo ";
    $sSqlArrecadacoesExtra .= "                                   and tabplan.k02_anousu  = ".db_getsession("DB_anousu");
    $sSqlArrecadacoesExtra .= "       inner join retencaopagordem         on e20_sequencial      = e23_retencaopagordem ";
    $sSqlArrecadacoesExtra .= "       inner join pagordem                 on e50_codord          = e20_pagordem ";
    $sSqlArrecadacoesExtra .= "       left  join pagordemconta            on e50_codord          = e49_codord ";
    $sSqlArrecadacoesExtra .= "       inner join empempenho               on e50_numemp          = e60_numemp ";
    $sSqlArrecadacoesExtra .= "       inner join cgm                      on cgm.z01_numcgm      = e60_numcgm ";
    $sSqlArrecadacoesExtra .= "       inner join saltes                   on corrente.k12_conta  = k13_conta ";
    $sSqlArrecadacoesExtra .= "       inner join conplanoreduz            on c61_reduz           = k02_reduz ";
    $sSqlArrecadacoesExtra .= "                                          and tabplan.k02_anousu  = c61_anousu ";
    $sSqlArrecadacoesExtra .= "       inner join conplano                 on c61_codcon          = c60_codcon ";
    $sSqlArrecadacoesExtra .= "                                          and c60_anousu          = c61_anousu ";
    $sSqlArrecadacoesExtra .= "       inner join reciborecurso            on k12_numpre          = k00_numpre ";
    $sSqlArrecadacoesExtra .= "       inner join orcdotacao               on e60_coddot          = o58_coddot ";
    $sSqlArrecadacoesExtra .= "                                          and e60_anousu          = o58_anousu ";
    $sSqlArrecadacoesExtra .= "      {$sJoin}                                    ";
    $sSqlArrecadacoesExtra .= " where e23_recolhido is true ";
    $sSqlArrecadacoesExtra .= "   and k02_tipo = 'E' and corrente.k12_instit =  ".db_getsession("DB_instit");
    $sSqlArrecadacoesExtra .= "   and not exists(select * from slipcorrente where k112_data = corrente.k12_data ";
    $sSqlArrecadacoesExtra .= "                                               and k112_id             = corrente.k12_id ";
    $sSqlArrecadacoesExtra .= "                                               and k112_autent         = corrente.k12_autent ";
    $sSqlArrecadacoesExtra .= "                                               and k112_ativo is true ) ";
    $sSqlArrecadacoesExtra .= "   and (k12_estorn is false) ";
    $sSqlArrecadacoesExtra .= "   and (e23_ativo is true) ";
    $sSqlArrecadacoesExtra .= "   {$sWhere}";
    $sSqlArrecadacoesExtra .= " order by e21_receita, k00_recurso ";
    $rsArrecadacaoExtra     = db_query($sSqlArrecadacoesExtra);
    $aArrecadacoesExtra     = db_utils::getCollectionByRecord($rsArrecadacaoExtra,false, false, true);
    $oRetorno->itens        = $aArrecadacoesExtra;
    echo $oJson->encode($oRetorno);
    break;

  case "gerarSlipsExtra" :

    /**
     * Percorremos as arrecadacoes e  agrupamos por cta credito, ctadebito, recurso
     * cada grupo ira compor um slip
     */
    db_inicio_transacao();
    try {

      $oDaoOPAuxiliar  = db_utils::getDao("empageordem");
      $oDaoOPAuxiliar->e42_dtpagamento = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoOPAuxiliar->incluir(null);
      $aSlips = array();
      require_once(modification("model/slip.model.php"));
      foreach ($oParam->aSlips as $oArrecadacao) {

        $sIndex = $oArrecadacao->iCtaCredito.$oArrecadacao->iCtaDebito.$oArrecadacao->iRecurso;
        if (isset($aSlips[$sIndex])) {

          $aSlips[$sIndex]->addRecurso($oArrecadacao->iRecurso, $oArrecadacao->nValor);
          $aSlips[$sIndex]->setValor($aSlips[$sIndex]->getValor()+$oArrecadacao->nValor);
          $aSlips[$sIndex]->addArrecadacao($oArrecadacao->iArrecadacao);

        } else {

          $aSlips[$sIndex] = new slip();

          $aSlips[$sIndex]->addRecurso($oArrecadacao->iRecurso, $oArrecadacao->nValor);
          $aSlips[$sIndex]->setContaCredito($oArrecadacao->iCtaCredito);
          $aSlips[$sIndex]->setCaracteristicaPeculiarCredito("000");
          $aSlips[$sIndex]->setContaDebito($oArrecadacao->iCtaDebito);
          $aSlips[$sIndex]->setCaracteristicaPeculiarDebito("000");
          $aSlips[$sIndex]->setValor($oArrecadacao->nValor);
          $aSlips[$sIndex]->setTipoPagamento(2);
          $aSlips[$sIndex]->setSituacao(1);
          $aSlips[$sIndex]->addArrecadacao($oArrecadacao->iArrecadacao);
          $aSlips[$sIndex]->setHistorico(9017);
          $aSlips[$sIndex]->setNumCgm($oArrecadacao->iCGM);
          $oDaoNotaOrdem = db_utils::getDao("empagenotasordem");

          $sObservacao  = "";
          if ($oParam->isFolha) {

            $sObservacao .= "Referente as consignações da folha de ";
            switch ($oParam->paramFolha->sSigla) {

              case "r14" :

                $sObservacao .= "Salário ";
                break;

              case "r48" :

                $sObservacao .= "Complementar {$oParam->paramFolha->sSemestre} ";
                break;

              case "r35" :

                $sObservacao .= "13o. Salário ";
                break;

              case "r20" :

                $sObservacao .= "Rescisão ";
                break;

              case "r22" :

                $sObservacao .= "Adiantamento ";
                break;
            }
            $sObservacao .= "da competência {$oParam->paramFolha->iMesFolha}/{$oParam->paramFolha->iAnoFolha} 0 ";

          } else {

            $sObservacao = "Referente ao pagamento das retenções geradas para o ";
          }

          $sObservacao  .= "recurso {$oArrecadacao->iRecurso}";
          $sObservacao  .= ", cujo pagamento será agendado na OP auxiliar nº {$oDaoOPAuxiliar->e42_sequencial}";
          $sObservacao  .= "\nOP: {$oArrecadacao->iOrdem}";
          $aSlips[$sIndex]->setObservacoes($sObservacao);

        }
      }
      /**
       * incluimos os slips gerados na base
       */
      foreach ($aSlips as $oSlip) {

         $oSlip->save();
         /**
          * Incluimos o slip na base de dados
          */
         $oDaoNotaOrdem->e43_ordempagamento = $oDaoOPAuxiliar->e42_sequencial;
         $oDaoNotaOrdem->e43_empagemov      = $oSlip->getMovimento();
         $oDaoNotaOrdem->e43_autorizado     = "true";
         $oDaoNotaOrdem->e43_valor          = $oSlip->getValor();
         $oDaoNotaOrdem->incluir(null);
         $oRetorno->aSlipsRetorno[] = $oSlip->getSlip();
      }




      /**
       * Vinculamos o slip gerado ao tipo Depósito de Diversos - Pagamento
       */
      if (USE_PCASP) {

        foreach ($oRetorno->aSlipsRetorno as $iCodigoSlip) {

        	$oDaoTipoOperacaoVinculo = db_utils::getDao('sliptipooperacaovinculo');
        	$oDaoTipoOperacaoVinculo->k153_slip             = $iCodigoSlip;
        	$oDaoTipoOperacaoVinculo->k153_slipoperacaotipo = 13;
        	$oDaoTipoOperacaoVinculo->incluir($iCodigoSlip);

        	if ($oDaoTipoOperacaoVinculo->erro_status == 0) {

        		$sMensagemErro  = "Não foi possível víncular o tipo de slip ao slip.\n\n";
        		$sMensagemErro .= "Erro Técnico: {$oDaoTipoOperacaoVinculo->erro_msg}";
        		throw new Exception($sMensagemErro);
        	}
        }
      }
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      db_fim_transacao(true);
      $oRetorno->message = $eErro->getMessage()."\nTrace:\n".$eErro->getTrace();
      $oRetorno->status  = 2;

    }
    echo $oJson->encode($oRetorno);
    break;

  /** [AutorizacaoRepasse] - Inicio */

  /** [CancelamentoRepasse] - Inicio */

  /** [DevolucaoRepasse] - Inicio */

}
