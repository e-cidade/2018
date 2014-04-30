<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

ini_set("ERROR_REPORTING","E_ALL & ~ E_NOTICE");


require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("std/db_stdClass.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/JSON.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_libcontabilidade.php");
require_once("libs/db_liborcamento.php");

require_once("classes/db_orcdotacao_classe.php");
require_once("classes/db_orctiporec_classe.php");
require_once("classes/db_empempenho_classe.php");
require_once("classes/db_empelemento_classe.php");
require_once("classes/db_pagordem_classe.php");
require_once("classes/db_pagordemele_classe.php");
require_once("classes/db_pagordemnota_classe.php");
require_once("classes/db_pagordemval_classe.php");
require_once("classes/db_pagordemrec_classe.php");
require_once("classes/db_pagordemtiporec_classe.php");
require_once("classes/db_empnota_classe.php");
require_once("classes/db_empnotaele_classe.php");
require_once("classes/db_tabrec_classe.php");
require_once("classes/db_conplanoreduz_classe.php");
require_once("classes/db_conlancam_classe.php");
require_once("classes/db_conlancamemp_classe.php");
require_once("classes/db_conlancamdoc_classe.php");
require_once("classes/db_conlancamele_classe.php");
require_once("classes/db_conlancamnota_classe.php");
require_once("classes/db_conlancamcgm_classe.php");
require_once("classes/db_conlancamdot_classe.php");
require_once("classes/db_conlancamval_classe.php");
require_once("classes/db_conlancamlr_classe.php");
require_once("classes/db_conlancamcompl_classe.php");
require_once("classes/db_conlancamord_classe.php");
require_once("classes/lancamentoContabil.model.php");

require_once("model/configuracao/Instituicao.model.php");
require_once("interfaces/ILancamentoAuxiliar.interface.php");
require_once("interfaces/IRegraLancamentoContabil.interface.php");
require_once("model/contabilidade/planoconta/interface/ISistemaConta.interface.php");

require_once("model/contabilidade/contacorrente/ContaCorrenteFactory.model.php");
require_once("model/contabilidade/contacorrente/ContaCorrenteBase.model.php");
require_once("model/financeiro/ContaBancaria.model.php");
require_once("model/contabilidade/planoconta/ContaPlano.model.php");
require_once("model/contabilidade/planoconta/ClassificacaoConta.model.php");
require_once("model/contabilidade/planoconta/ContaCorrente.model.php");
require_once("model/contabilidade/planoconta/ContaOrcamento.model.php");
require_once("model/contabilidade/planoconta/ContaPlanoPCASP.model.php");
require_once("model/agendaPagamento.model.php");
require_once("model/retencaoNota.model.php");
require_once("model/ordemCompra.model.php");

require_once("model/contabilidade/planoconta/SistemaContaCompensado.model.php");
require_once("model/contabilidade/planoconta/SistemaContaFinanceiroBanco.model.php");
require_once("model/contabilidade/planoconta/SistemaContaFinanceiroCaixa.model.php");
require_once("model/contabilidade/planoconta/SistemaContaFinanceiroExtraOrcamentaria.model.php");
require_once("model/contabilidade/planoconta/SistemaContaFinanceiro.model.php");
require_once("model/contabilidade/planoconta/SistemaContaPatrimonial.model.php");
require_once("model/contabilidade/planoconta/SistemaContaOrcamentario.model.php");
require_once("model/contabilidade/planoconta/SistemaContaNaoAplicado.model.php");

require_once("model/contabilidade/planoconta/SubSistemaConta.model.php");
require_once("model/contabilidade/planoconta/SistemaConta.model.php");

require_once("classes/empenho.php");
require_once("model/CgmFactory.model.php");

db_app::import("MaterialCompras");
db_app::import("configuracao.*");
db_app::import("contabilidade.*");
db_app::import("contabilidade.lancamento.*");
require_once("model/Dotacao.model.php");

db_app::import("empenho.*");
db_app::import("exceptions.*");

$objEmpenho       = new empenho();
$post             = db_utils::postmemory($_POST);
$json             = new services_json();
$objJson          = $json->decode(str_replace("\\","",$_POST["json"]));
$method           = $objJson->method;
$oAgendaPagamento = new agendaPagamento();
$item             = 0; //se deve trazer as notas, ou os itens do empenho.
$objEmpenho->setEmpenho($objJson->iEmpenho);
$objEmpenho->setEncode(true);


switch ($objJson->method) {

  case "getEmpenhos":

      $lValidaNotasEmpenho = false;

      $oDaoPatriInst   = db_utils::getDao('cfpatriinstituicao');
      $sWherePatriInst = " t59_instituicao = " . db_getsession('DB_instit');
      $sSqlPatriInst   = $oDaoPatriInst->sql_query_file(null, "t59_dataimplanatacaodepreciacao", null, $sWherePatriInst);
      $rsPatriInst     = $oDaoPatriInst->sql_record($sSqlPatriInst);

      if ($oDaoPatriInst->numrows > 0) {

        $dtImplantacao = db_utils::fieldsMemory($rsPatriInst, 0)->t59_dataimplanatacaodepreciacao;
        if (!empty($dtImplantacao)) {
          $lValidaNotasEmpenho = true;
        }
      }

      $oDaoEmpNota      = db_utils::getDao('empnota');
      $sWhereBuscaNotas = " e69_numemp = {$objJson->iEmpenho} ";
      $sSqlBuscaNotas   = $oDaoEmpNota->sql_query_elemento_patrimonio(null, "empnota.* ", null, $sWhereBuscaNotas);
      $rsBuscaNotas     = $oDaoEmpNota->sql_record($sSqlBuscaNotas);
      $aBuscaNotas      = db_utils::getCollectionByRecord($rsBuscaNotas);
      $aNotasEmpenho    = array();
      $oGrupoElemento   = new stdClass();

      if (count($aBuscaNotas) > 0 && $lValidaNotasEmpenho) {

        foreach ($aBuscaNotas as $oNota) {

          $oDaoEmpNotaItemBensPendente      = db_utils::getDao('empnotaitembenspendente');
          $sWhereBuscaItensForaDoPatrimonio = " e69_codnota = {$oNota->e69_codnota} and e136_sequencial is null and e139_sequencial is null ";
          $sSqlBuscaItensForaDoPatrimonio   = $oDaoEmpNotaItemBensPendente->sql_query_patrimonio(null, "e69_codnota", null,
                                                                                                 $sWhereBuscaItensForaDoPatrimonio);
          $rsBuscaItensForaDoPatrimonio = $oDaoEmpNotaItemBensPendente->sql_record($sSqlBuscaItensForaDoPatrimonio);
          if ($oDaoEmpNotaItemBensPendente->numrows > 0) {
            $aNotasEmpenho[] = $oNota->e69_codnota;
          }
        }
      }

      $objEmpenho->operacao = $objJson->operacao;
      if (isset($objJson->itens)) {
        $item = 1;
      }
      $objEmpenho->setEmpenho($objJson->iEmpenho);

      if (count($aNotasEmpenho) > 0 && $lValidaNotasEmpenho) {

        //echo $objEmpenho->empenho2Json('',$item, $aNotasEmpenho);
        $oEmpenho                 = json_decode($objEmpenho->empenho2Json('',$item, $aNotasEmpenho));
        $oGrupoElemento->iGrupo   = "";
        $oGrupoElemento->sGrupo   = "";
        $oEmpenho->oGrupoElemento = $oGrupoElemento;
        echo $json->encode($oEmpenho);

      } else {

        $oEmpenho             = json_decode($objEmpenho->empenho2Json('',$item));
        $oGrupoContaOrcamento = GrupoContaOrcamento::getGrupoConta($oEmpenho->e64_codele, db_getsession("DB_anousu"));

        $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oEmpenho->e60_numemp);
        if ($oGrupoContaOrcamento && !$oEmpenhoFinanceiro->isEmpenhoPassivo()) {

	        $iGrupo     = $oGrupoContaOrcamento->getCodigo();
	        $sDescricao = $oGrupoContaOrcamento->getDescricao();

	        /**
	         * Caso o empennho seja dos grupos abaixo, nao devemos permitir a liquidacao
	         * do mesmo atraves da rotina de liquidacao sem ordem de compra
	         */
	        if ($iGrupo != "") {

	          if (in_array($iGrupo, array(7,8,9))) {


	            $oGrupoElemento->iGrupo   = $iGrupo;
	            $oGrupoElemento->sGrupo   = urlencode($sDescricao);
	            $oEmpenho->oGrupoElemento = $oGrupoElemento;
	            echo $json->encode($oEmpenho);


	          } else {

	            //echo $objEmpenho->empenho2Json('',$item);
	            $oEmpenho                 = json_decode($objEmpenho->empenho2Json('',$item));
	            $oGrupoElemento->iGrupo   = "";
	            $oGrupoElemento->sGrupo   = "";
	            $oEmpenho->oGrupoElemento = $oGrupoElemento;
	            echo $json->encode($oEmpenho);
	          }
	        }
        } else {

         // echo $objEmpenho->empenho2Json('',$item);
          $oEmpenho                 = json_decode($objEmpenho->empenho2Json('',$item));
          $oGrupoElemento->iGrupo   = "";
          $oGrupoElemento->sGrupo   = "";
          $oEmpenho->oGrupoElemento = $oGrupoElemento;
          echo $json->encode($oEmpenho);
        }

      }
    break;

  case "liquidarAjax":

      $objEmpenho->setCredor($objJson->z01_credor);
      echo $objEmpenho->liquidarAjax($objJson->iEmpenho,$objJson->notas, addslashes(stripslashes(utf8_decode($objJson->historico))));
    break;

  case "geraOC":

      $objEmpenho->setEmpenho($objJson->iEmpenho);
      $objEmpenho->setCredor($objJson->z01_credor);
      /**
       * Pode ser que o método gerarOrdemCompra retorne false ou um JSON
       */
      $oRetorno = $objEmpenho->gerarOrdemCompra($objJson->e69_nota,
                                         $objJson->valorTotal,
                                         $objJson->notas,
                                         true,
                                         $objJson->e69_dtnota,
                                         addslashes(stripslashes(utf8_decode($objJson->historico))),
                                         true,
                                         $objJson->oInfoNota);

      if ($oRetorno !== false) {
        echo $oRetorno;
      } else {

        $retorno = array("erro" => 2, "mensagem" => urlencode($objEmpenho->sMsgErro), "e50_codord" => null);
        echo $json->encode($retorno);
      }

    break;

  case "anularEmpenho":

      $objEmpenho->setRecriarSaldo($objJson->lRecriarReserva);
      $objEmpenho->anularEmpenho($objJson->itensAnulados,
                                 $objJson->nValor,
                                 $objJson->sMotivo,
                                 $objJson->aSolicitacoes,
                                 $objJson->iTipoAnulacao);
      if ($objEmpenho->lSqlErro) {

        $nMensagem = urlencode($objEmpenho->sErroMsg);
        $iStatus   = 2;
      } else {

        $nMensagem = '';
        $iStatus   = 1;
      }
      echo $json->encode(array("mensagem" => $nMensagem, "status" => $iStatus));
    break;

  case "getDadosRP":

      if ($objEmpenho->getDadosRP($objJson->iTipoRP)){
        echo $json->encode($objEmpenho->dadosEmpenho);
      }else{
        echo $json->encode(array("status" => 2 ,"sMensagem" => urlencode($objEmpenho->sErroMsg)));
      }
    break;

  case "estornarRP":

      try {

        db_inicio_transacao();
        $objEmpenho->estornarRP($objJson->iTipo,
        $objJson->aNotas,
        $objJson->sValorEstornar,
        $objJson->sMotivo,
        $objJson->aItens,
        $objJson->tipoAnulacao);
        db_fim_transacao(false);
        $iStatus   = 1;
        $sMensagem = "Empenho estornado com sucesso";

      }
      catch (Exception $e){

        $iStatus   = 2;
        $sMensagem = urlencode($e->getMessage());
        db_fim_transacao(true);
      }
      echo $json->encode(array("sMensagem" => $sMensagem, "iStatus" => $iStatus));
    break;

  case "getDadosRP":

      if ($objEmpenho->getDados($objJson->iEmpenho)) {

        $rsNotas  = $objEmpenho->getNotas($objJson->iEmpenho);
        if ($rsNotas) {

          for ($iNotas = 0; $iNotas <  $objEmpenho->iNumRowsNotas; $iNotas++ ) {

            $oNota                            = db_utils::fieldsMemory($rsNotas, $iNotas);
            $oNota->temMovimentoConfigurado   = false;
            $oNota->temRetencao               = false;
            $oNota->VlrRetencao               = 0;
            /**
             * Pesquisamos se existem algum movimento para essa nota.
             */
            $sWhereIni  =  "e50_codord = {$oNota->e50_codord} and e97_codforma is not null";
            $sWhereIni .= " and corempagemov.k12_codmov is null and e81_cancelado is null";
            $sJoin      = " left join empagenotasordem on e81_codmov         = e43_empagemov  ";
            $sJoin     .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
            $aMOvimentos = $oAgendaPagamento->getMovimentosAgenda($sWhereIni, $sJoin,false,false) ;
            if (count($aMOvimentos) > 0) {

              $oNota->temMovimentoConfigurado   = true;
            }

            //Verifica se a nota possui retenções lançadas
            $oRetencao = new retencaoNota($oNota->e69_codnota);
            if ( $oNota->e50_codord != "" && $oRetencao->getValorRetencao($oNota->e50_codord) > 0) {

              $oNota->temRetencao   = true;
              $oNota->VlrRetencao   = $oRetencao->getValorRetencao($oNota->e50_codord);
            }
            $objEmpenho->dadosEmpenho->aNotas[] = $oNota;
          }
        }

        echo $json->encode($objEmpenho->dadosEmpenho);

      } else {
        echo $json->encode(array("status" => 2 ,"sMensagem" => urlencode($objEmpenho->sErroMsg)));
      }
    break;

   case "getDadosNotas" :

   if ($objEmpenho->getDados($objJson->iEmpenho)) {

     $rsNotas  = $objEmpenho->getNotas($objJson->iEmpenho);
      if ($rsNotas) {

        for ($iNotas = 0; $iNotas <  $objEmpenho->iNumRowsNotas; $iNotas++ ) {

          $oNota                            = db_utils::fieldsMemory($rsNotas, $iNotas);
          $oNota->temMovimentoConfigurado   = false;
          $oNota->temRetencao               = false;
          $oNota->VlrRetencao               = 0;
          /**
           * Pesquisamos se existem algum movimento para essa nota.
           */
          $sWhereIni  =  "e50_codord = {$oNota->e50_codord} and e97_codforma is not null";
          $sWhereIni .= " and corempagemov.k12_codmov is null and e81_cancelado is null";
          $sJoin      = " left join empagenotasordem on e81_codmov         = e43_empagemov  ";
          $sJoin     .= " left join empageordem      on e43_ordempagamento = e42_sequencial ";
          $aMOvimentos = $oAgendaPagamento->getMovimentosAgenda($sWhereIni, $sJoin,false,false) ;
          if (count($aMOvimentos) > 0) {

            $oNota->temMovimentoConfigurado   = true;
          }

          //Verifica se a nota possui retenções lançadas
          $oRetencao = new retencaoNota($oNota->e69_codnota);
          if ( $oNota->e50_codord != "" && $oRetencao->getValorRetencao($oNota->e50_codord) > 0) {
            $oNota->temRetencao   = true;
            $oNota->VlrRetencao   = $oRetencao->getValorRetencao($oNota->e50_codord);
          }

          $objEmpenho->dadosEmpenho->aNotas[] = $oNota;
        }
      }
   }
   echo $json->encode($objEmpenho->dadosEmpenho);
   break;

  case "getItensNota":

      /**
      * Busca os ITENS da nota
      */
      $oDadosRetorno = new stdClass();
      $objEmpenho->setEncode(true);
      $aItens        = $objEmpenho->getItensNota($objJson->iCodNota);

      if ( !$aItens ) {

        $oDadosRetorno->status    = 1;
        $oDadosRetorno->sMensagem = "Não foi possível recuperar os itens da nota!";
      } else {

        $oDadosRetorno->status   = 2;
        $oDadosRetorno->iCodNota = $objJson->iCodNota;
        $oDadosRetorno->iEmpenho = $objJson->iEmpenho;
        $oDadosRetorno->aItens   = $aItens;
      }

      echo $json->encode($oDadosRetorno);
    break;

  default:
      echo $objEmpenho->$method($objJson->iEmpenho,$objJson->notas, $objJson->historico);
    break;
}