<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_utils.php");
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/JSON.php");

$oPost       = db_utils::postMemory($_REQUEST);
$oPost->json = str_replace("\\","",$oPost->json);
$oParametro  = JSON::create()->parse($oPost->json);
$oRetorno    = (object)array( 'erro' => false, 'mensagem'=> '');

try {

  db_inicio_transacao();
  
  switch ($oParametro->exec) {

    case "salvar":

      if (empty($oParametro->iBanco)) {
        throw new ParameterException('Banco deve ser informado.');
      }
      if (empty($oParametro->iMatricula)) {
        throw new ParameterException('Servidor deve ser informado.');
      }
      if (empty($oParametro->nValor)) {
        throw new ParameterException('Valor deve ser informado.');
      }
      if (empty($oParametro->iParcelas)) {
        throw new ParameterException('Número de parcelas deve ser informado.');
      }
      if (empty($oParametro->sRubrica)) {
        throw new ParameterException('Número de parcelas deve ser informado.');
      }

      $oBanco              = new Banco($oParametro->iBanco);
      $sSituacao           = ArquivoConsignadoManual::SITUACAO_NORMAL;
      $oConsignadoOrigem   = null;
      $oCompetenciaInicial = new DBCompetencia($oParametro->iAno,  $oParametro->iMes);
      if (!empty($oParametro->iCodigoOrigem)) {

        $oConsignadoOrigem = ArquivoConsignadoManualRepository::getByCodigo($oParametro->iCodigoOrigem);
        if ($oConsignadoOrigem->getCompetencia()->comparar($oCompetenciaInicial, DBCompetencia::COMPARACAO_MAIOR_IGUAL)) {
          throw new BusinessException("A competência de início não pode ser menor ou igual a competência do consignado de origem.");
        }
        if ($oConsignadoOrigem->getBanco()->getCodigo() != $oBanco->getCodigo()) {
          $sSituacao  = ArquivoConsignadoManual::SITUACAO_PORTADO;
        }
        if ($oConsignadoOrigem->getBanco()->getCodigo() == $oBanco->getCodigo()) {
          $sSituacao  = ArquivoConsignadoManual::SITUACAO_REFINANCIADO;
        }

        if($oConsignadoOrigem->getNumeroDeParcelas() == $oParametro->iParcelas) {

          if($oConsignadoOrigem->getValorDaParcela() == str_replace(",",".", $oParametro->nValor)) {
            throw new BusinessException("Informe um valor ou um número de parcelas diferente do contrato original");
          }
        }

        $oConsignadoOrigem->setSituacao(ArquivoConsignadoManual::SITUACAO_INATIVO);
        ArquivoConsignadoManualRepository::persist($oConsignadoOrigem);
      }

      $oConsignado = new ArquivoConsignadoManual();

      if (!empty($oParametro->iCodigoConsignado)) {
        $oConsignado = ArquivoConsignadoManualRepository::getByCodigo($oParametro->iCodigoConsignado);
      }


      $oConsignado->setConsignadoOrigem($oConsignadoOrigem);
      $oConsignado->setCodigo($oParametro->iCodigoConsignado);
      $oConsignado->setServidor(ServidorRepository::getInstanciaByCodigo($oParametro->iMatricula));
      $oConsignado->setRubrica(RubricaRepository::getInstanciaByCodigo($oParametro->sRubrica));
      $oConsignado->setBanco($oBanco);
      $oConsignado->setSituacao($sSituacao);
      $oConsignado->setParcelaInicial($oParametro->iParcelaInicial);
      $oConsignado->setNumeroDeParcelas($oParametro->iParcelas);
      $oConsignado->setValorDaParcela(str_replace(",",".", $oParametro->nValor));
      $oConsignado->setInstituicao(InstituicaoRepository::getInstituicaoSessao());
      $oConsignado->setCompetencia($oCompetenciaInicial);

      ArquivoConsignadoManualRepository::persist($oConsignado);

      $oRetorno->oContrato   = getContratos(null,  $oConsignado->getCodigo());
      $oRetorno->mensagem    = 'Consignado salvo com sucesso!';
      break;

    case 'cancelar' :
    case 'excluir' :

	    if (empty($oParametro->iCodigoConsignado)) {
	      throw new ParameterException('Consignado  deve ser informado.');
	    }
      $sTextoCancelamento = "excluído";
      if ($oParametro->exec == 'cancelar') {
        $sTextoCancelamento = "cancelado";
      }

	    $oConsignado = ArquivoConsignadoManualRepository::getByCodigo($oParametro->iCodigoConsignado);
	    if ($oConsignado->getSituacao() == ArquivoConsignadoManual::SITUACAO_CANCELADO) {
	      throw new BusinessException('O Consignado já está cancelado.');
      }
      
      $oRetorno->lProcessado = false;

      if ($oConsignado->temParcelasProcessadas() || $oConsignado->temMovimentacao()) {

        if($oParametro->exec == 'excluir') {
          $oRetorno->lProcessado = true;
          throw new Exception("O contrato selecionado não pode ser excluído pois possui parcelas descontadas, deseja CANCELAR?");
        }

        $oConsignado->setSituacao(ArquivoConsignadoManual::SITUACAO_CANCELADO);
        ArquivoConsignadoManualRepository::persist($oConsignado);

      } else {

        $oConsignadoOrigem = $oConsignado->getConsignadoOrigem();
        if (!empty($oConsignadoOrigem)) {

          $aParcelas = ArquivoConsignadoManualParcelaRepository::getParcelasDoFinanciamento($oConsignadoOrigem);
          if (count($aParcelas) > 0) {

            $oUltimaParcela = end($aParcelas);
            $aParcelasNovas = $oConsignadoOrigem->adicionarParcelas($oUltimaParcela->getParcela() +1, $oConsignado->getCompetencia());
            foreach ($aParcelasNovas as $oParcelaNova) {
             ArquivoConsignadoManualParcelaRepository::persist($oParcelaNova, $oConsignadoOrigem);
            }
          }
          $oConsignadoOrigem->setSituacao($oConsignadoOrigem->getSituacaoAnterior());
          ArquivoConsignadoManualRepository::persist($oConsignadoOrigem, true);
        }
        ArquivoConsignadoManualRepository::remove($oConsignado);
      }
      $oRetorno->mensagem  = "Consignado {$sTextoCancelamento} com sucesso!";
	  	break;

	  case "getContrato":

	  	if(empty($oParametro->iCodigoConsignado)) {
	  		throw new ParameterException("Informe o código do contrato.");
	  	}

	  	$oRetorno->oContrato = getContratos(null, $oParametro->iCodigoConsignado);
	  	$oRetorno->oContrato = $oRetorno->oContrato[0];
	  	break;
  	
	  case "getContratos":

	  	if(empty($oParametro->iMatricula)) {
	  		throw new ParameterException("Informe uma matrícula para buscar os contratos.");
	  	}
			$oRetorno->aContratos = getContratos($oParametro->iMatricula);
			break;
	  
    case "getBancos":
      $oRetorno->bancos = getBancosCompetencia();
      break;

	  case "getDados":

      /**
       * Processamos os consignados
       */
      $oCompetencia = DBPessoal::getCompetenciaFolha();
      $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
      $oImportacao  = new ImportacaoArquivoConsignadoManual($oCompetencia, $oInstituicao);
      $oImportacao->processar();

      /**
       * Retornamos os dados
       */
      $aParcelasNaCompetencia = ArquivoConsignadoManualParcelaRepository::getParcelasNaCompetencia($oCompetencia, $oInstituicao);
      $oRetorno->consignacoes = array();
      foreach ($aParcelasNaCompetencia as $oParcela) {

        if ($oParcela->getConsignado()->getBanco()->getCodigo() != $oParametro->banco) {
          continue;
        }

        if(isset($oParametro->matricula) && !empty($oParametro->matricula) && $oParametro->matricula != $oParcela->getServidor()->getMatricula()) {
          continue;
        }

        $oConsignado = new stdClass();
        $oConsignado->codigo           = $oParcela->getCodigo();
        $oConsignado->matricula        = $oParcela->getServidor()->getMatricula();
        $oConsignado->nome             = $oParcela->getServidor()->getCgm()->getNomeCompleto();
        $oConsignado->valor            = trim(db_formatar($oParcela->getValor(), 'f'));
        $oConsignado->parcela          = $oParcela->getParcela()."/".$oParcela->getTotalDeParcelas();
        $oConsignado->descricao_motivo = ArquivoConsignadoMotivo::getDescricaoMotivo($oParcela->getMotivo());
        $oConsignado->motivo           = $oParcela->getMotivo();
        $oConsignado->processado       = $oParcela->isProcessado();
        $oRetorno->consignacoes[]      = $oConsignado;
      }
			break;

    case 'salvarConferencia':

      if (empty($oParametro->codigo_registro)) {
        throw new ParameterException(_M('Parcela não informada'));
      }
      $oRetorno->codigo_registro = $oParametro->codigo_registro;
      $oRetorno->motivo          = '';

      $oParcela = ArquivoConsignadoManualParcelaRepository::getByCodigo($oParametro->codigo_registro);
      if (empty($oParcela)) {
        throw new BusinessException(_M('Nenhum registro encontrado'));
      }
      switch ($oParcela->getMotivo()) {

        case null:
          $oParcela->setMotivo(ArquivoConsignadoMotivo::MOTIVO_EXCLUIDO);
          break;

        case ArquivoConsignadoMotivo::MOTIVO_EXCLUIDO:
          $oParcela->setMotivo(null);
          break;
      }

      ArquivoConsignadoManualParcelaRepository::persist($oParcela, $oParcela->getConsignado());
      $iMotivo                    = ($oParcela->getMotivo() === null) ? '' : $oParcela->getMotivo();
      $oRetorno->motivo           = $oParcela->getMotivo();
      $oRetorno->descricao_motivo = urlencode(ArquivoConsignadoMotivo::getDescricaoMotivo($iMotivo));
      break;

  	default:
      return;  
  }
  
  db_fim_transacao(false);
    
} catch (Exception $eErro) {

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->mensagem = $eErro->getMessage();
}

/**
 * @param integer $iMatricula
 * @param integer $iCodigoConsignado
 * @return \ArquivoConsignadoManual[]
 * @throws \BusinessException
 * @throws \Exception
 */
function getContratos($iMatricula = null, $iCodigoConsignado = null) {
	
	$aContratos = array();

	if(!empty($iCodigoConsignado)) {
		$aArquivoConsignadoManual = array(ArquivoConsignadoManualRepository::getByCodigo($iCodigoConsignado));
	} else {
		$oServidor                = ServidorRepository::getInstanciaByCodigo($iMatricula);
		$aArquivoConsignadoManual = ArquivoConsignadoManualRepository::getContratosAtivosByServidor($oServidor);
	}
		
	if(empty($aArquivoConsignadoManual) && !empty($iMatricula)) {
		throw new BusinessException("Não foram encontrados contratos para a matrícula informada.");
	}

	if(empty($aArquivoConsignadoManual) && !empty($iCodigoConsignado)) {
		throw new BusinessException("Não foi encontrado contrato para o código informado.");
	}

	foreach ($aArquivoConsignadoManual as $oArquivoConsignadoManual) {

		$oContrato                        = new StdClass();
		$aParcelasArquivoConsignadoManual = $oArquivoConsignadoManual->getParcelas();

		$iParcela      = 0;
		$iTotalParcela = 0;
		foreach ($aParcelasArquivoConsignadoManual as $oParcela) {
			
			$iParcela      = $oParcela->getParcela();
			$iTotalParcela = $oParcela->getTotalDeParcelas();

			if(!$oParcela->isProcessado()) {
				break;
			}
		}

		if(empty($iParcela) || empty($iTotalParcela)) {
			throw new Exception("Não foi possível recuperar as parcelas do contrato.");
		}

		$oContrato->iCodigoConsignado = $oArquivoConsignadoManual->getCodigo();
		$oContrato->iMatricula        = $oArquivoConsignadoManual->getServidor()->getMatricula();
		$oContrato->sServidor         = $oArquivoConsignadoManual->getServidor()->getCgm()->getNome();
		$oContrato->iBanco            = $oArquivoConsignadoManual->getBanco()->getCodigo();
		$oContrato->sBanco            = $oArquivoConsignadoManual->getBanco()->getNome();
		$oContrato->sSituacao         = $oArquivoConsignadoManual->getSituacao();
		$oContrato->sRubrica          = $oArquivoConsignadoManual->getRubrica()->getCodigo();
		$oContrato->sDescricaoRubrica = $oArquivoConsignadoManual->getRubrica()->getDescricao();
		$oContrato->iParcela          = $iParcela;
		$oContrato->iTotalParcela     = $iTotalParcela;
		$oContrato->iCodigoOrigem     = $oArquivoConsignadoManual->getCodigoConsignadoOrigem();
		$oContrato->iAno              = $oArquivoConsignadoManual->getCompetencia()->getAno();
		$oContrato->iMes              = $oArquivoConsignadoManual->getCompetencia()->getMes();
		$oContrato->nValor            = trim(db_formatar($oArquivoConsignadoManual->getValorDaParcela(), 'f'));
		$oContrato->lHistorico        = !$oArquivoConsignadoManual->temMovimentacao();
		$oContrato->iConsignadoOrigem = !$oArquivoConsignadoManual->getCodigoConsignadoOrigem();

		$aContratos[]	= $oContrato;
	}

	return $aContratos;
}

function getBancosCompetencia() {

  $oDaoConsignado = new cl_rhconsignadomovimentomanual();
  $sWhere         = "     rh182_ano =".DBPessoal::getAnoFolha();
  $sWhere        .= " and rh182_mes =".DBPessoal::getMesFolha();
  $sWhere        .= " and rh151_instit = ".InstituicaoRepository::getInstituicaoSessao()->getCodigo();

  $sSqlBancos = $oDaoConsignado->sql_query_dados_financiamento_banco(null, "distinct db90_codban as banco, db90_descr as descricao", "db90_codban", $sWhere);
  $rsBancos   = db_query($sSqlBancos);
  if (!$rsBancos) {
    throw new DBException("Não foi posível pesquisar os bancos.");

  }
  $aBancos = db_utils::getCollectionByRecord($rsBancos);
  return $aBancos;
}

echo JSON::create()->stringify($oRetorno);
