<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("libs/JSON.php");
require_once("std/db_stdClass.php");
require_once("dbforms/db_funcoes.php");
require_once("std/DBDate.php");
require_once 'libs/db_liborcamento.php';
require_once 'model/contabilidade/planoconta/ContaPlano.model.php';
require_once 'model/contabilidade/planoconta/ContaOrcamento.model.php';
require_once 'model/contabilidade/planoconta/ClassificacaoConta.model.php';
require_once 'model/contabilidade/planoconta/SistemaConta.model.php';
require_once 'model/contabilidade/planoconta/SubSistemaConta.model.php';
require_once 'model/CgmFactory.model.php';
require_once 'model/configuracao/InstituicaoRepository.model.php';
require_once 'model/caixa/PlanilhaArrecadacao.model.php';
require_once 'model/caixa/ReceitaPlanilha.model.php';
require_once 'model/caixa/AutenticacaoPlanilha.model.php';

db_app::import("exceptions.*");
db_app::import("configuracao.Instituicao");
db_app::import("configuracao.DBEstrutura");
db_app::import("CgmFactory");
db_app::import("contaTesouraria");
db_app::import("contabilidade.*");
db_app::import("orcamento.*");
db_app::import("configuracao.*");
db_app::import("contabilidade.lancamento.*");
db_app::import("contabilidade.planoconta.*");
db_app::import("financeiro.*");
db_app::import("contabilidade.contacorrente.*");
db_app::import("exceptions.*");

$oJson              = new services_json();
$oParam             = $oJson->decode(str_replace("\\","",$_POST["json"]));

$oRetorno           = new stdClass();
$oRetorno->dados    = array();
$oRetorno->status   = 1;

switch ($oParam->exec) {


	case "excluirPlanilha" :


		$iPlanilha       = $oParam->iPlanilha;

		db_inicio_transacao();

		try {

      $oPlanilha = new PlanilhaArrecadacao($iPlanilha);

      if ($oPlanilha->excluir()){

			  $oRetorno->message = urlencode("Planilha {$iPlanilha} exclu�da com sucesso.");

      }
			db_fim_transacao(false);

		} catch (Exception $oExceptionErro) {

    	db_fim_transacao(true);
    	$oRetorno->status  = 2;
    	$oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }


	break;




  case 'salvarPlanilha':

    db_inicio_transacao();
    try {

      $oPlanilhaArrecadacao = new PlanilhaArrecadacao();

      $dtArrecadacao = date('Y-m-d', db_getsession('DB_datausu'));

      $oPlanilhaArrecadacao->setDataCriacao($dtArrecadacao);
      $oPlanilhaArrecadacao->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo(db_getsession('DB_instit')));

      foreach ($oParam->aReceitas as $oReceitas) {

        $iNumeroCgm = buscaCgmOrigem($oReceitas);
        $iInscricao = empty($oReceitas->iInscricao) ? '' : $oReceitas->iInscricao;
        $iMatricula = empty($oReceitas->iMatricula) ? '' : $oReceitas->iMatricula;

        $oReceitaPlanilha = new ReceitaPlanilha();
        $oReceitaPlanilha->setCaracteristicaPeculiar(new CaracteristicaPeculiar($oReceitas->iCaracteriscaPeculiar));
        $oReceitaPlanilha->setCGM(CgmFactory::getInstanceByCgm($iNumeroCgm));
        $oReceitaPlanilha->setContaTesouraria(new contaTesouraria($oReceitas->iContaTesouraria));
        $oReceitaPlanilha->setDataRecebimento(new DBDate($oReceitas->dtRecebimento));
        $oReceitaPlanilha->setInscricao($iInscricao);
        $oReceitaPlanilha->setMatricula($iMatricula);
        $oReceitaPlanilha->setObservacao(pg_escape_string(db_stdClass::normalizeStringJson($oReceitas->sObservacao)));
        $oReceitaPlanilha->setOperacaoBancaria($oReceitas->sOperacaoBancaria);
        $oReceitaPlanilha->setOrigem($oReceitas->iOrigem);
        $oReceitaPlanilha->setRecurso(new Recurso($oReceitas->iRecurso));
        $oReceitaPlanilha->setTipoReceita($oReceitas->iReceita);
        $oReceitaPlanilha->setValor($oReceitas->nValor);
        $oPlanilhaArrecadacao->adicionarReceitaPlanilha($oReceitaPlanilha);
      }

      $oPlanilhaArrecadacao->salvar();
      db_fim_transacao(false);

      $sMensagemRetorno  = "Planilha {$oPlanilhaArrecadacao->getCodigo()} inclusa com sucesso.\n\n";
      $sMensagemRetorno .= "Deseja imprimir o documento gerado?";
      $oRetorno->message         = urlencode($sMensagemRetorno);
      $oRetorno->iCodigoPlanilha = $oPlanilhaArrecadacao->getCodigo();


    } catch (Exception $oExceptionErro) {

    	db_fim_transacao(true);
    	$oRetorno->status  = 2;
    	$oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }
    break;

  case 'estornarPlanilha':

    db_inicio_transacao();
    try {

      $oPlanilhaArrecadacao = new PlanilhaArrecadacao($oParam->iPlanilha);
      $oPlanilhaArrecadacao->estornar();
      db_fim_transacao(false);
      $oRetorno->message = urlencode("Planilha {$oPlanilhaArrecadacao->getCodigo()} estornada com sucesso.");

    } catch (Exception $oExceptionErro) {

    	db_fim_transacao(true);
    	$oRetorno->status  = 2;
    	$oRetorno->message = urlencode($oExceptionErro->getMessage());
    }

    break;

  case 'importarPlanilha':
  case 'getDadosPlanilhaArrecadacao':

    $oPlanilhaArrecadacao     = new PlanilhaArrecadacao($oParam->iPlanilha);
    $aReceitasPlanilha        = $oPlanilhaArrecadacao->getReceitasPlanilha();

    $oPlanilha                = new stdClass();
    $oPlanilha->aReceitas     = array();
    $oPlanilha->iPlanilha     = $oPlanilhaArrecadacao->getCodigo();
    $oData                    = $oPlanilhaArrecadacao->getDataCriacao();
    $oPlanilha->dtDataCriacao = $oData->getDate(DBDate::DATA_PTBR);

    if (count($aReceitasPlanilha) > 0) {

      foreach ($aReceitasPlanilha as $oReceitaPlanilha) {

        $oReceita                        = new stdClass();
        $oReceita->iCodigo               = $oReceitaPlanilha->getCodigo();
        $oReceita->iReceita              = $oReceitaPlanilha->getTipoReceita();
        $oReceita->sDescricaoReceita     = urlencode($oReceitaPlanilha->getDescricaoReceita());

        $oReceita->iOrigem               = $oReceitaPlanilha->getOrigem();
        $oReceita->iCgm                  = $oReceitaPlanilha->getCGM()->getCodigo();
        $oReceita->iInscricao            = $oReceitaPlanilha->getInscricao();
        $oReceita->iMatricula            = $oReceitaPlanilha->getMatricula();
        $oReceita->iCaracteriscaPeculiar = $oReceitaPlanilha->getCaracteristicaPeculiar()->getSequencial();

        $oContaTesouraria                = $oReceitaPlanilha->getContaTesouraria();
        $oReceita->iContaTesouraria      = $oContaTesouraria->getCodigoConta();
        $oReceita->sDescricaoConta       = urlencode($oContaTesouraria->getDescricao());

        $oReceita->dtRecebimento         = $oReceitaPlanilha->getDataRecebimento()->convertTo(DBDate::DATA_PTBR);
        $oReceita->sObservacao           = urlencode($oReceitaPlanilha->getObservacao());
        $oReceita->sOperacaoBancaria     = $oReceitaPlanilha->getOperacaoBancaria();
        $oReceita->iRecurso              = $oReceitaPlanilha->getRecurso()->getCodigoRecurso();
        $oReceita->nValor                = $oReceitaPlanilha->getValor();

        $oPlanilha->aReceitas[]          = $oReceita;
        unset($oReceita);
      }
    }

    $oRetorno->oPlanilha = $oPlanilha;
    break;

  case 'autenticarPlanilha':

    db_inicio_transacao();
    try {

      $oPlanilhaArrecadacao = new PlanilhaArrecadacao($oParam->iPlanilha);
      $oPlanilhaArrecadacao->getReceitasPlanilha();
      $oPlanilhaArrecadacao->autenticar();

      db_fim_transacao(false);

      $sMensagemRetorno    = "Planilha {$oPlanilhaArrecadacao->getCodigo()} autenticada com sucesso.\n\n";
      $sMensagemRetorno   .= "Deseja imprimir o documento gerado?";
      $oRetorno->message   = urlencode($sMensagemRetorno);
      $oRetorno->iPlanilha = $oPlanilhaArrecadacao->getCodigo();

    } catch (Exception $oExceptionErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }
    break;

  case 'alterarPlanilha':

    db_inicio_transacao();
    try {

      $oPlanilhaArrecadacao = new PlanilhaArrecadacao($oParam->iCodigoPlanilha);
      $oPlanilhaArrecadacao->excluirReceitas();

      foreach ($oParam->aReceitas as $oReceitas) {

        $iNumeroCgm       = buscaCgmOrigem($oReceitas);
        $iInscricao       = empty($oReceitas->iInscricao) ? '' : $oReceitas->iInscricao;
        $iMatricula       = empty($oReceitas->iMatricula) ? '' : $oReceitas->iMatricula;

        $oReceitaPlanilha = new ReceitaPlanilha(null);
        $oReceitaPlanilha->setCaracteristicaPeculiar(new CaracteristicaPeculiar($oReceitas->iCaracteriscaPeculiar));
        $oReceitaPlanilha->setCGM(CgmFactory::getInstanceByCgm($iNumeroCgm));
        $oReceitaPlanilha->setContaTesouraria(new contaTesouraria($oReceitas->iContaTesouraria));
        $oReceitaPlanilha->setDataRecebimento(new DBDate($oReceitas->dtRecebimento));
        $oReceitaPlanilha->setInscricao($iInscricao);
        $oReceitaPlanilha->setMatricula($iMatricula);
        $oReceitaPlanilha->setObservacao(pg_escape_string(db_stdClass::normalizeStringJson($oReceitas->sObservacao)));
        $oReceitaPlanilha->setOperacaoBancaria($oReceitas->sOperacaoBancaria);
        $oReceitaPlanilha->setOrigem($oReceitas->iOrigem);
        $oReceitaPlanilha->setRecurso(new Recurso($oReceitas->iRecurso));
        $oReceitaPlanilha->setTipoReceita($oReceitas->iReceita);
        $oReceitaPlanilha->setValor($oReceitas->nValor);

        $oPlanilhaArrecadacao->adicionarReceitaPlanilha($oReceitaPlanilha);
      }

      $oPlanilhaArrecadacao->salvar();
      db_fim_transacao(false);

      $sMensagemRetorno          = "Planilha {$oPlanilhaArrecadacao->getCodigo()} salva com sucesso.\n\n";
      $sMensagemRetorno         .= "Deseja imprimir o documento gerado?";
      $oRetorno->message         = urlencode($sMensagemRetorno);
      $oRetorno->iCodigoPlanilha = $oPlanilhaArrecadacao->getCodigo();

    } catch (Exception $oExceptionErro) {

      db_fim_transacao(true);
      $oRetorno->status  = 2;
      $oRetorno->message = str_replace("\n", "\\n", urlencode($oExceptionErro->getMessage()));
    }
  break;
}

/**
 * "Factory method" para retornar o cgm de acordo com a origem
 * @param object $oParam
 * @throws BusinessException
 */
function buscaCgmOrigem($oParam) {

  $iNumeroCmg = "";
  switch ($oParam->iOrigem) {

    case 1:

      $iNumeroCmg = $oParam->iCgm;
      break;

    case 2:

      $iNumeroCmg = buscaCgmInscricao($oParam->iInscricao);
      break;

    case 3:

      $iNumeroCmg = buscaCgmMatricula($oParam->iMatricula);
      break;
    default:
      throw new BusinessException("Origem n�o identificada.");
  }

  return $iNumeroCmg;
}

/**
 * Busca o cgm da inscricao
 * @param integer $iInscricao
 * @throws BusinessException
 */
function buscaCgmInscricao($iInscricao) {

  if (empty($iInscricao)) {

    $sMsgErro = "N�mero da inscricao vazio ou n�o informado.";
    throw new BusinessException($sMsgErro);
  }

  $oDaoIssBase   = db_utils::getDao('issbase');
  $sSqlInscricao = $oDaoIssBase->sql_query_file($iInscricao);
  $rsInscricao   = $oDaoIssBase->sql_record($sSqlInscricao);

  if ($rsInscricao && $oDaoIssBase->numrows == 1) {

    return db_utils::fieldsMemory($rsInscricao, 0)->q02_numcgm;
  }

  $sMsgErro  = "Erro ao buscar cgm da inscricao.\n";
  $sMsgErro .= "Inscri��o: {$iInscricao}";
  $sMsgErro .= $oDaoIssBase->erro_msg;

  throw new BusinessException($sMsgErro);

}

/**
 * Busca o cgm da matricula
 * @param integer $iMatricula
 * @throws BusinessException
 */
function buscaCgmMatricula($iMatricula) {

  if (empty($iMatricula)) {

    $sMsgErro = "N�mero da matricula vazio ou n�o informado.";
    throw new BusinessException($sMsgErro);
  }

  $oDaoIptuBase   = db_utils::getDao('iptubase');
  $sSqlMatricula  = $oDaoIptuBase->sql_query_file($iMatricula);
  $rsMatricula    = $oDaoIptuBase->sql_record($sSqlMatricula);

  if ($rsMatricula && $oDaoIptuBase->numrows == 1) {

    return db_utils::fieldsMemory($rsMatricula, 0)->j01_numcgm;
  }

  $sMsgErro  = "Erro ao buscar cgm da matricula.\n";
  $sMsgErro .= "Matricula: {$iMatricula}.\n";
  $sMsgErro .= $oDaoIptuBase->erro_msg;

  throw new BusinessException($sMsgErro);

}

echo $oJson->encode($oRetorno);