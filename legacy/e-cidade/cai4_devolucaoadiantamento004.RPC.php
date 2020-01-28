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

//cai4_devolucaoadiantamento004.RPC.php
require_once(modification("std/DBDate.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/exceptions/BusinessException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/ordemPagamento.model.php"));
require_once(modification("classes/db_emppresta_classe.php"));
require_once(modification("model/empenho/EmpenhoFinanceiro.model.php"));
require_once(modification("model/recibo.model.php"));

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno               = new stdClass();
$oRetorno->iStatus      = 1;
$oRetorno->sMessage     = '';
$aDadosRetorno          = array();

define("URL_MENSAGEM", "financeiro.empenho.cai4_devolucaoadiantamento004.");

try {

  switch ($oParam->exec) {

  	case "gerarRecibo" :

          $iEmpenho           = $oParam->iEmpenho    ;
          $iEmpPresta         = $oParam->iEmpPresta  ;
          $iCodMov            = $oParam->iCodMov     ;
          $dtEmissao          = date("Y-m-d", db_getsession("DB_datausu"));
          $dtVencimento       = $oParam->dtVencimento;
          $aReceitas          = $oParam->aReceitas   ;
          $sHistorico         = addslashes(db_stdClass::normalizeStringJson($oParam->sHistorico));
          $oEmpenhoFinanceiro = new EmpenhoFinanceiro($iEmpenho);
  	  $oRecibo            = new recibo();

  	  $iCredor            = $oEmpenhoFinanceiro->getCgm()->getCodigo();

  	  db_inicio_transacao();

  	  $oRecibo = new recibo(1, $iCredor);
  	  $oRecibo->setDataRecibo($dtEmissao);
  	  $oRecibo->setDataVencimentoRecibo($dtVencimento );

  	  foreach ($aReceitas as $iIndiceReceita => $oDadosReceita) {

  	  	$iCodRec                 = $oDadosReceita->iReceita;
  	  	$nValorReceita           = $oDadosReceita->nValor;
  	  	$iCaracteristicaPeculiar = $oDadosReceita->iCaracteristica;
  	    $oRecibo->adicionarReceita($iCodRec, $nValorReceita, 0, $iCaracteristicaPeculiar);//($this->getCodigoReceita(), $this->getValor());
  	  }

  	  $oRecibo->setCodigoHistorico(11000);
  	  $oRecibo->setHistorico( $sHistorico );

  	  $oRecibo->emiteRecibo();

 	    $iNumpre = $oRecibo->getNumpreRecibo();

 	    /**
 	     * criamos vinculo com empprestarecibo
 	     */
 	    $oDaoEmpPrestaRecibo = db_utils::getDao("empprestarecibo");
 	    $oDaoEmpPrestaRecibo->e170_numpre     = $iNumpre;
 	    $oDaoEmpPrestaRecibo->e170_numpar     = 1;
 	    $oDaoEmpPrestaRecibo->e170_emppresta  = $iEmpPresta;
 	    $oDaoEmpPrestaRecibo->incluir(null);
 	    if ($oDaoEmpPrestaRecibo->erro_status == 0) {

 	    	throw new DBException($oDaoEmpPrestaRecibo->erro_msg);
 	    }

 	    db_fim_transacao(false);

 	    $oRetorno->iNumpre  = $iNumpre;



  	break;

  	case "verificaGrupoReceita":

  		$iReceita     = $oParam->iReceita;
  		$iAnoUsu      = db_getsession("DB_anousu");
  		$iInstituicao = db_getsession("DB_instit");
  		$oTabRec      = db_utils::getDao("tabrec");

  		$sWhere  = "tabrec.k02_codigo = {$iReceita} and ";
  		$sWhere .= "taborc.k02_anousu = {$iAnoUsu}  and ";
  		$sWhere .= "conplanoorcamentogrupo.c21_instit  = {$iInstituicao} ";

  		$sSqlGrupoReceita = $oTabRec->sql_query_verificaGrupoReceita( null, "distinct congrupo.c20_sequencial", null, $sWhere);
  		$rsReceita        = $oTabRec->sql_record($sSqlGrupoReceita);

      $iGrupoReceita = null;
      if ($oTabRec->numrows > 0) {

        $oReceita      = db_utils::fieldsMemory($rsReceita, 0);
        $iGrupoReceita = $oReceita->c20_sequencial;
      }

  		if ($iGrupoReceita == 11) {
        throw new BusinessException( _M(URL_MENSAGEM."receita_grupo_onze") );
      }
      break;

    case "verificaEventoEmpenho":

      $oOrdemPagamento    = new ordemPagamento($oParam->iOrdemPagamento);
      $iEmpenho           = $oOrdemPagamento->getDadosOrdem()->e60_numemp;
      $oEmpenhoFinanceiro = new EmpenhoFinanceiro($iEmpenho);
      $lPrestacao         = $oEmpenhoFinanceiro->isPrestacaoContas();
      $sEmpenho           = $oEmpenhoFinanceiro->getCodigo() . "/" . $oEmpenhoFinanceiro->getAnoUso();

      if ($lPrestacao && USE_PCASP) {

    	  $iCodigoTipoPrestacao = $oEmpenhoFinanceiro->getDadosPrestacaoContas()->e45_tipo;
        $oTipoPrestacaoConta = new TipoPrestacaoConta($iCodigoTipoPrestacao);
        $sCaminhoMensagem  = URL_MENSAGEM."validacao_tipo_evento_empenho";
        $oStdDadosMensagem = new stdClass();
        $oStdDadosMensagem->codigo_empenho = $sEmpenho;
        $oStdDadosMensagem->tipo_evento    = $oTipoPrestacaoConta->getDescricao();
    	  throw new BusinessException( _M($sCaminhoMensagem, $oStdDadosMensagem) );
			}
      break;

    case "getDadosEmpenho":

      $oEmpPresta = new cl_emppresta();

      $sSql      = $oEmpPresta->sql_query( null,
                                           "e45_numemp, e45_sequencial",
                                           null,
                                           "e45_codmov = {$oParam->iCodigoMovimentacao}" );
      $rsEmpenho = $oEmpPresta->sql_record($sSql);

      if ($oEmpPresta->numrows == 0) {
        throw new BusinessException( _M("financeiro.caixa.cai4_devolucaoadiantamento004.movimento_nao_encontrado") );
      }

      $oEmpenhoQuery      = db_utils::fieldsMemory($rsEmpenho, 0);
      $oEmpenhoFinanceiro = new EmpenhoFinanceiro($oEmpenhoQuery->e45_numemp);
      $oDataEmissao       = new DBDate($oEmpenhoFinanceiro->getDataEmissao());
      $oRetorno->iSequencialEmpPresta = $oEmpenhoQuery->e45_sequencial;
      $oRetorno->iCodigoEmpenho       = $oEmpenhoFinanceiro->getCodigo();
      $oRetorno->iNumeroEmpenho       = $oEmpenhoFinanceiro->getNumero();
      $oRetorno->iAnoEmpenho          = $oEmpenhoFinanceiro->getAnoUso();
      $oRetorno->dtEmissao            = $oDataEmissao->convertTo(DBDate::DATA_PTBR);
      $oRetorno->iCgmFornecedor       = $oEmpenhoFinanceiro->getFornecedor()->getCodigo();
      $oRetorno->sNomeFornecedor      = $oEmpenhoFinanceiro->getFornecedor()->getNome();
      $oRetorno->lHabilitaEstorno     = ($oDataEmissao->getAno() == db_getsession("DB_anousu"));

      break;

    default:
      throw new ParameterException("Nenhuma Opção Definida");
  }

  $oRetorno->sMessage = urlencode($oRetorno->sMessage);
  echo $oJson->encode($oRetorno);

} catch (Exception $eErro){

	db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

}catch (DBException $eErro){

	db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

}catch (ParameterException $eErro){

	db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);

}catch (BusinessException $eErro){

	db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = urlencode($eErro->getMessage());
  echo $oJson->encode($oRetorno);
}


?>
