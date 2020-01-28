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
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("model/CgmFactory.model.php"));
require_once(modification("model/patrimonio/depreciacao/PlanilhaCalculo.model.php"));
require_once(modification("std/DBNumber.php"));

db_app::import("patrimonio.*");
db_app::import("patrimonio.depreciacao.*");

$oJson             = new services_json();
$oParam            = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno          = new stdClass();
$oRetorno->status  = 1;
$oRetorno->message = '';

$iInstituicao = db_getsession("DB_instit");
$iAnoSessao   = db_getsession("DB_anousu");

switch ($oParam->exec) {

  case "getMesesDepreciados":

    try {

      $oPlanilhaCalculo         = new PlanilhaCalculo();
      $oPlanilhaCalculo->setTipoProcessamento($oParam->iTipoProcessamento);
      $oPlanilhaCalculo->setAno($iAnoSessao);
      $iMesDisponivel           = $oPlanilhaCalculo->getMesDisponivelParaProcessamento(1);
      $dtInicioDepreciacao      = $oPlanilhaCalculo->getDataInicioDepreciacao();
      $aDataInicioDepreciacao   = explode("-", $dtInicioDepreciacao);
      if ($aDataInicioDepreciacao[0] != $iAnoSessao) {
        $oPlanilhaCalculo->validaAnosProcessados();
      }
      $oRetorno->iMesDisponivel = $iMesDisponivel;

    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
  break;

  case "getItensProcessamentoManual":

    try {

      $oPlanilhaCalculo = new PlanilhaCalculo();
      $oPlanilhaCalculo->setTipoCalculo(1);
      $oPlanilhaCalculo->setTipoProcessamento(2);
      $oPlanilhaCalculo->setMes($oParam->iMesProcessar);
      $oPlanilhaCalculo->setAno(db_getsession("DB_anousu"));
      $aBens        = $oPlanilhaCalculo->getBensPorTipoDeProcessamento();
      $aRetornoBens = array();
      foreach ($aBens as $oBem) {

        $oDadoBem                    = new stdClass();
        $oDadoBem->iCodigoBem        = $oBem->getCodigoBem();
        $oDadoBem->sDescricao        = $oBem->getDescricao();
        $oDadoBem->nValorAquisicao   = $oBem->getValorAquisicao();
        $oDadoBem->nValorResidual    = $oBem->getValorResidual();
        $oDadoBem->nValorDepreciavel = $oBem->getValorDepreciavel();
        $oDadoBem->nValorAtualizado  = $oBem->getValorAtual();
        $aRetornoBens[]              = $oDadoBem;
      }
      $oRetorno->aBens = $aRetornoBens;

    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
  break;

  case "executaDepreciacaoManual":

    try {

      db_inicio_transacao();
      $oPlanilhaCalculo = new PlanilhaCalculo();
      $oPlanilhaCalculo->setMes($oParam->iMesProcessar);
      $oPlanilhaCalculo->setAno($oParam->iAnoSessao);
      $oPlanilhaCalculo->setTipoCalculo(1);
      $oPlanilhaCalculo->setTipoProcessamento(2);
      $oPlanilhaCalculo->setUsuario(db_getsession("DB_id_usuario"));

      foreach ($oParam->aBensDepreciados as $oDadosBem) {

        $oCalculoBem = new CalculoBem();
        $oCalculoBem->setBem(new Bem($oDadosBem->iCodigoBem));
        $oCalculoBem->setPercentualDepreciado($oDadosBem->nPercentual);
        $oCalculoBem->calcular();
        $oPlanilhaCalculo->adicionarCalculo($oCalculoBem);
      }
      $oPlanilhaCalculo->processarCalculo();
      $oPlanilhaCalculo->salvar();
      $oRetorno->message = _M('patrimonial.patrimonio.pat4_processamentodepreciacao.processamento_executado');
      db_fim_transacao(false);

    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }
  break;

  case "executaDepreciacaoAutomatico":

    try {

      db_inicio_transacao();
      ini_set('memory_limit', '1024M');
      $oPlanilhaCalculo = new PlanilhaCalculo();
      $oPlanilhaCalculo->setMes($oParam->iMesProcessar);
      $oPlanilhaCalculo->setAno($oParam->iAnoSessao);
      $oPlanilhaCalculo->setTipoProcessamento(1);
      $oPlanilhaCalculo->setTipoCalculo(1);
      $oPlanilhaCalculo->setUsuario(db_getsession("DB_id_usuario"));
      $oPlanilhaCalculo->processarCalculo();
      $oPlanilhaCalculo->salvar();
      $oRetorno->message = _M('patrimonial.patrimonio.pat4_processamentodepreciacao.processamento_executado');
      db_fim_transacao(false);
    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
      db_fim_transacao(true);
    }
  break;

  case "verificaBensSemDepreciacao":

      $oDaoBens  = db_utils::getDao("bens");
      $sWhere    = "t44_bens is null and t55_codbem is null ";
      $sWhere   .= " and t52_instit = ".db_getsession("DB_instit");

      $sBensSemDepreciacao  = $oDaoBens->sql_query_left_depreciacao(null, "*", null, $sWhere);
      $rsBensSemDepreciacao = $oDaoBens->sql_record($sBensSemDepreciacao);

      if ($oDaoBens->numrows > 0 ) {

        $sMsg = _M('patrimonial.patrimonio.pat4_processamentodepreciacao.procedimento_abortado');

        $oRetorno->status  = 2;
        $oRetorno->message = urlencode($sMsg);
      }

    break;

  case "getMesesDepreciadosParaCancelamento":

    try {

      $oPlanilhaCalculo         = new PlanilhaCalculo();
      if (!$oPlanilhaCalculo->hasParametroDepreciacaoHabilitado()) {
        throw new Exception(_M('patrimonial.patrimonio.pat4_processamentodepreciacao.informe_parametro'));
      }
      $aDataInicioDepreciacao   = $oPlanilhaCalculo->getDataInicioDepreciacao();
      $oPlanilhaCalculo->setTipoProcessamento($oParam->iTipoProcessamento);
      $oPlanilhaCalculo->setAno(db_getsession("DB_anousu"));
      $iMesDisponivel           = $oPlanilhaCalculo->getMesDisponivelParaProcessamento(1);
      if ($iMesDisponivel == 0) {
        $iMesDisponivel = 12;
      } else {
        $iMesDisponivel -= 1;
      }

      list($iAno, $iMes, $iDia) = explode("-", $aDataInicioDepreciacao);
      $oRetorno->iMesDisponivel = $iMesDisponivel;

    } catch (Exception $eErro) {

      $oRetorno->status  = 2;
      $oRetorno->message = urlencode($eErro->getMessage());
    }
   break;

   case 'cancelarProcessamento':

     try {

       if (empty($oParam->iMesDesprocessar)) {
         throw new Exception(_M('patrimonial.patrimonio.pat4_processamentodepreciacao.informe_mes'));
       }
       if (empty($oParam->iTipoProcessamento)) {
         throw new Exception(_M('patrimonial.patrimonio.pat4_processamentodepreciacao.informe_processamento'));
       }
       db_inicio_transacao();
       $iAnoSessao   = db_getsession("DB_anousu");
       $iInstituicao = db_getsession("DB_instit");

       /**
        * Verifica se a depreciaчуo jс nуo foi contabilizada para a competъncia mes/ano
        * pela contabilidade
        * Caso foi nуo deixa desprocessar a depreciaчуo
        */
       $oDaoBensDepreciacaoLancamento = db_utils::getDao("bensdepreciacaolancamento");
       $sWhere                        = "     t78_ano       = {$iAnoSessao}";
       $sWhere                       .= " and t78_instit    = {$iInstituicao}";
       $sWhere                       .= " and t78_mes       = {$oParam->iMesDesprocessar}";
       $sWhere                       .= " and t78_estornado is false";
       $sSqlVerificaContabilizacao    = $oDaoBensDepreciacaoLancamento->sql_query_file(null, "1", null, $sWhere);
       $rsVerificaContabilizacao      = $oDaoBensDepreciacaoLancamento->sql_record($sSqlVerificaContabilizacao);

       if($oDaoBensDepreciacaoLancamento->numrows > 0) {
         throw new Exception("Depreciaчуo jс contabilizada para a competъncia {$oParam->iMesDesprocessar}/{$iAnoSessao} na contabilidade.");
       }

       $oDaoBensHistoricoCalculo  = db_utils::getDao("benshistoricocalculo");
       $sWhere                    = " t57_ano                   = {$iAnoSessao} ";
       $sWhere                   .= " and t57_mes               = {$oParam->iMesDesprocessar} ";
       $sWhere                   .= " and t57_tipoprocessamento = {$oParam->iTipoProcessamento} ";
       $sWhere                   .= " and t57_instituicao       = {$iInstituicao} ";
       $sWhere                   .= " and t57_ativo is true ";
       $sWhere                   .= " and t57_processado is true ";
       $sSqlCodigoPlanilha        = $oDaoBensHistoricoCalculo->sql_query_file(null, "t57_sequencial", null, $sWhere);
       $rsCodigoProcessamento     = $oDaoBensHistoricoCalculo->sql_record($sSqlCodigoPlanilha);
       if ($oDaoBensHistoricoCalculo->numrows == 0) {
         throw new Exception(_M('patrimonial.patrimonio.pat4_processamentodepreciacao.nenhum_mes_disponivel'));
       }
       $iCodigoPlanilha      = db_utils::fieldsMemory($rsCodigoProcessamento, 0)->t57_sequencial;
       $oPlanilhaDepreciacao = new PlanilhaCalculo($iCodigoPlanilha);
       $oPlanilhaDepreciacao->cancelar();
       db_fim_transacao(false);
     } catch (Exception $eErro) {

       db_fim_transacao(true);
       $oRetorno->status  = 2;
       $oRetorno->message = urlencode($eErro->getMessage());

     }
     break;



}
echo $oJson->encode($oRetorno);
?>