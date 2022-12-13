<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2015  DBSeller Servicos de Informatica             
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

$oJson                  = new services_json();
$oParam                 = $oJson->decode(str_replace("\\","",$_POST["json"]));
$oRetorno               = new stdClass();
$oRetorno->iStatus      = true;
$oRetorno->sMessage     = '';
$oRetorno->erro         = false;

define('MENSAGENS', 'recursoshumanos.pessoal.pes4_assentamentorraRPC.');

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "processarLancamentos":

        if(empty($oParam->iCodigoAssentamento)) {
          throw new ParameterException(_M(MENSAGENS."codigo_nao_informado"));
        }

        if(empty($oParam->nValorParcela)) {
          throw new ParameterException(_M(MENSAGENS."valor_parcela_nao_informado"));
        }

        if(empty($oParam->iTipoFolha)) {
          throw new ParameterException(_M(MENSAGENS."folha_nao_informada"));
        }

        $oInstituicao      = InstituicaoRepository::getInstituicaoSessao();
        $oAssentamento     = AssentamentoFactory::getByCodigo($oParam->iCodigoAssentamento);
        $oTipoAssentamento = TipoAssentamentoRepository::getInstanciaPorCodigo($oAssentamento->getTipoAssentamento());
        $oServidor         = ServidorRepository::getInstanciaByCodigo($oAssentamento->getMatricula());
        $oCompetencia      = DBPessoal::getCompetenciaFolha();
        $oFolha            = FolhaPagamentoFactory::construirPeloTipo($oParam->iTipoFolha);

        if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

          $oFolha = $oFolha->getUltimaFolha();

          if(!$oFolha->isAberto()) {
          throw new BusinessException(_M(MENSAGENS."folha_informada_fechada"));
          }
        }


        /**
         * Monta um objeto de lançamento de RRA para calcular previdencia e IRRF
         */
        $oLancamento = new LancamentoRRA($oParam->iCodigoLancamento);
        $oLancamento->setAssentamento($oAssentamento);
        $oLancamento->setValorLancado($oParam->nValorParcela);
        $oLancamento->setValorEncargos($oParam->nValorEncargos);
        $oLancamento->setValorPensao($oParam->nValorPensao);
        $oLancamento->setValorBasePrevidencia($oParam->nValorBasePrevidencia);
        $oLancamento->setValorBaseIrrf($oParam->nValorBaseIRRF);
        foreach ($oParam->aPensionistas as $oPensionista) {
           $oLancamento->adicionarPensionista(CgmRepository::getByCodigo($oPensionista->iNumcgm), $oPensionista->nValor);
        }

        /**
         * Monta um objeto de RRA e adiciona um lançamento
         * o método adicionar irá validar antes, caso o valor
         * exceda o saldo ira disparar exceção
         */
        $oRRA = new RRA($oAssentamento);
        $oRRA->adicionarLancamento($oLancamento);

        /**
         * Se deu tudo certo ao adicionar o lançamento
         * chama o método calcular, este por sua vez alimenta
         * as variáveis de valor calculado para IRRF e previdencia
         */
        $oLancamento->calcular();

        /**
         * Pega os valores calculados
         */
        $aValoresLancar['provento']      = $oLancamento->getValorLancado();
        $aValoresLancar['irrf']          = $oLancamento->getValorCalculadoIrrf();
        $aValoresLancar['previdencia']   = $oLancamento->getValorCalculadoPrevidencia();
        $aValoresLancar['pensao']        = $oLancamento->getValorPensao();
        $aValoresLancar['parcelaisenta'] = $oLancamento->getValorParcelaIsenta();
        $aValoresLancar['molestia']      = $oLancamento->getValorMolestia();

        /**
         * Persiste o RRA com seus lançamentos
         */
        $oLancamento = LancamentoRRARepository::persist($oLancamento);

        /**
         * Pega as rubricas configuradas para o RRA
         */
        $aRubricasLancar['provento']      = InformacoesFinanceirasTipoAssentamentoRRARepository::getRubricaProvento($oTipoAssentamento);
        $aRubricasLancar['irrf']          = InformacoesFinanceirasTipoAssentamentoRRARepository::getRubricaIrrf($oTipoAssentamento);
        $aRubricasLancar['previdencia']   = InformacoesFinanceirasTipoAssentamentoRRARepository::getRubricaPrevidencia($oTipoAssentamento);
        $aRubricasLancar['pensao']        = InformacoesFinanceirasTipoAssentamentoRRARepository::getRubricaPensao($oTipoAssentamento);
        $aRubricasLancar['parcelaisenta'] = InformacoesFinanceirasTipoAssentamentoRRARepository::getRubricaParcelaIsenta($oTipoAssentamento);
        $aRubricasLancar['molestia']      = InformacoesFinanceirasTipoAssentamentoRRARepository::getRubricaMolestia($oTipoAssentamento);

        if(!empty($oParam->iCodigoLancamento)) {
          cancelarLancamentoNoPonto($oLancamento, $oParam->iCodigoLancamento);
        }

        lancarNoPonto($oCompetencia, $oInstituicao, $oFolha, $aValoresLancar, $aRubricasLancar, $oServidor, $oLancamento);

        $oRetorno->sMessage = "RRA calculado e lançado no ponto com sucesso.\nCalcule a folha para consolidar os lançamentos.";

      break;

    case "getLancamentos":

      if(empty($oParam->iCodigoAssentamento)) {
        throw new ParameterException("Não foi possível buscar os lançamentos deste assentamento.\nTente novamente.");
      }

      $oRetorno->aLancamentos = array();
      $oRRA = RRARepository::getInstanciaByAssentamento(AssentamentoFactory::getByCodigo($oParam->iCodigoAssentamento));

      $oServidor               = $oRRA->getAssentamento()->getServidor();
      $oRetorno->aPensionistas = getPensionistasDoServidor($oServidor);
      if(count($oRRA->getLancamentos())) {

        foreach ($oRRA->getLancamentos() as $oLancamento) {
          
          $oLancamentoRRA = new \stdClass();
          $oLancamentoRRA->iCodigo          = $oLancamento->getCodigo();
          $oLancamentoRRA->sCompetencia     = $oLancamento->getLoteRegistroPonto()->getCompetencia()->getCompetencia();
          $oLancamentoRRA->nValorlancado    = $oLancamento->getValorlancado();
          $oLancamentoRRA->nEncargos        = $oLancamento->getValorEncargos();
          $oLancamentoRRA->nPensao          = $oLancamento->getValorPensao();
          $oLancamentoRRA->nBaseprevidencia = $oLancamento->getValorBaseprevidencia();
          $oLancamentoRRA->nBaseirrf        = $oLancamento->getValorBaseirrf();
          $oLancamentoRRA->lAtual           = false;
          $oLancamentoRRA->aPensionistas    = array();
          foreach ($oLancamento->getPensionistas() as $oPensionistas) {

            $oPensionistaRRA                 = new \stdClass();
            $oPensionistaRRA->iNumcgm        = $oPensionistas->getPensionista()->getCodigo();
            $oPensionistaRRA->sNome          = $oPensionistas->getPensionista()->getNome();
            $oPensionistaRRA->nValor         = $oPensionistas->getValor();
            $oLancamentoRRA->aPensionistas[] = $oPensionistaRRA;
          }
          if(isset($oParam->iTipoFolha) && !empty($oParam->iTipoFolha)) {

            $aRegistrosLote = $oLancamento->getLoteRegistroPonto()->getRegistroPonto();

            if($oParam->iTipoFolha == $aRegistrosLote[0]->getFolhaPagamento()->getTipoFolha()) {

              $oLancamentoRRA->lAtual       = $oLancamento->getLoteRegistroPonto()->getCompetencia()->comparar(DBPessoal::getCompetenciaFolha()) ? true : false;
            }
          }

          $oRetorno->aLancamentos[] = $oLancamentoRRA;
        }
      }
      break;

    case "getAssentamentosRRA":

      if(empty($oParam->iTipoAssentamento)) {
        throw new ParameterException("Tipo de assentamento não informado.");
      }

      $oTipoAssentamento        = TipoAssentamentoRepository::getInstanciaPorCodigo($oParam->iTipoAssentamento);
      $aAssentamentos           = $oTipoAssentamento->getAssentamentos();
      $oRetorno->aAssentamentos = array();

      uasort($aAssentamentos, function(Assentamento $oAssentamentoInicial, Assentamento $oAssentamentoFinal) {
         return $oAssentamentoInicial->getMatricula() > $oAssentamentoFinal->getMatricula();
      });
      if(count($aAssentamentos) > 0) {

        foreach ($aAssentamentos as $oAssentamento) {

          $oAssentamentoRRA = new stdClass();
          $oAssentamentoRRA->iCodigoAssentamento = $oAssentamento->getCodigo();
          $oAssentamentoRRA->iMatricula          = $oAssentamento->getMatricula();
          $oAssentamentoRRA->sNomeServidor       = ServidorRepository::getInstanciaByCodigo($oAssentamento->getMatricula())->getCgm()->getNome();
          $oAssentamentoRRA->sDataAssentamento   = $oAssentamento->getDataConcessao()->getDate(DBDate::DATA_PTBR);
          $oAssentamentoRRA->sObservacoes        = $oAssentamento->getHistorico();
          $oAssentamentoRRA->nValorDevido        = $oAssentamento->getValorTotalDevido();
          $oAssentamentoRRA->nNumeroMeses        = $oAssentamento->getNumeroDeMeses();
          $oAssentamentoRRA->nValorEncargos      = $oAssentamento->getValorDosEncargosJudiciais();

          $oRetorno->aAssentamentos[] = $oAssentamentoRRA;
        }

      } else {
        throw new BusinessException("Nenhum assentamento de RRA encontrado.");
      }

      $oRetorno->aAssentamentos;

      break;
  }
  
  db_fim_transacao(false);
    
  
} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->erro     = true;
  $oRetorno->iStatus  = false;
  $oRetorno->sMessage = $eErro->getMessage();
}

$oRetorno->sMessage = urlencode($oRetorno->sMessage);
echo $oJson->encode($oRetorno);


/**
 * Lança no ponto
 */
function lancarNoPonto(DBCompetencia $oCompetencia, Instituicao $oInstituicao, FolhaPagamento $oFolha, array $aValoresLancar, array $aRubricasLancar, Servidor $oServidor, LancamentoRRA $oLancamento) {

  /**
   * Criando um lote básico para o RRA
   */
  $oLoteRegistrosPonto = new LoteRegistrosPonto();
  $oLoteRegistrosPonto->setCompetencia($oCompetencia);
  $oLoteRegistrosPonto->setInstituicao($oInstituicao);
  $oLoteRegistrosPonto->setDescricao('Lote Registros de RRA');
  $oLoteRegistrosPonto->setRegistroPonto(array());
  $oLoteRegistrosPonto->setSituacao(LoteRegistrosPonto::ABERTO);
  $oLoteRegistrosPonto->setUsuario(UsuarioSistemaRepository::getPorCodigo(db_getsession("DB_id_usuario")));
  $oLoteRegistrosPonto->setTipoLancamentoPonto(LoteRegistrosPonto::SUBSTITUIR_RUBRICA);

  $oLoteRegistrosPonto = LoteRegistrosPontoRepository::persist($oLoteRegistrosPonto);

  /**
   * Percorre os valores montando registros para
   * adicionar ao lote e persistir os dados no ponto
   */
  $aRegistrosLoteRegistrosPonto = array();
  foreach ($aValoresLancar as $sTipo => $nValor) {

    if(empty($nValor)) {
      continue;
    }

    $oRegistroLoteRegistrosPonto  = new RegistroLoteRegistrosPonto();
    $oRegistroLoteRegistrosPonto->setCodigoLote($oLoteRegistrosPonto->getSequencial());
    $oRegistroLoteRegistrosPonto->setInstituicao($oInstituicao);
    $oRegistroLoteRegistrosPonto->setFolhaPagamento($oFolha);
    $oRegistroLoteRegistrosPonto->setCompetencia($oCompetencia->getAno().'/'.$oCompetencia->getMes());
    $oRegistroLoteRegistrosPonto->setRubrica($aRubricasLancar[$sTipo]);
    $oRegistroLoteRegistrosPonto->setServidor($oServidor);
    $oRegistroLoteRegistrosPonto->setQuantidade(1);
    $oRegistroLoteRegistrosPonto->setValor($nValor);

    $aRegistrosLoteRegistrosPonto[] = $oRegistroLoteRegistrosPonto;
  }

  /**
   * Persiste no ponto as rubricas com valor
   */
  $oLoteRegistrosPonto->setRegistroPonto($aRegistrosLoteRegistrosPonto);
  $oLoteRegistrosPonto->setSituacao(LoteRegistrosPonto::CONFIRMADO);
  $oLoteRegistrosPonto = LoteRegistrosPontoRepository::persist($oLoteRegistrosPonto);

  $oDaoLancamentoRRALoteRegistroPonto = new cl_lancamentorraloteregistroponto();
  $oDaoLancamentoRRALoteRegistroPonto->rh174_lancamentorra     = $oLancamento->getCodigo();
  $oDaoLancamentoRRALoteRegistroPonto->rh174_loteregistroponto = $oLoteRegistrosPonto->getSequencial();

  $oDaoLancamentoRRALoteRegistroPonto->incluir(null);

  if($oDaoLancamentoRRALoteRegistroPonto->erro_status == '0') {
    throw new DBException($oDaoLancamentoRRALoteRegistroPonto->erro_msg);
  }

  /**
   * Se utiliza estrutura suplementar vincula o lote a uma folha de pagamento
   */
  if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

    $oDaoLoteregistropontoRhfolhapagamento = new cl_loteregistropontorhfolhapagamento();
    $oDaoLoteregistropontoRhfolhapagamento->rh162_loteregistroponto = $oLoteRegistrosPonto->getSequencial();
    $oDaoLoteregistropontoRhfolhapagamento->rh162_rhfolhapagamento  = $oFolha->getSequencial();

    $oDaoLoteregistropontoRhfolhapagamento->incluir(null);

    if($oDaoLoteregistropontoRhfolhapagamento->erro_status == '0') {
      throw new DBException($oDaoLoteregistropontoRhfolhapagamento->erro_msg);
    }
  }
}

/**
 * Cancela o lançamento no ponto
 */
function cancelarLancamentoNoPonto(LancamentoRRA $oLancamento, $iCodigoLancamento) {

  if(empty($iCodigoLancamento)) {
    throw new ParameterException("Codigo do lancamento não informado");
  }

  $oDaoLancamentoRRALoteRegistroPonto   = new cl_lancamentorraloteregistroponto();
  $sWhereLancamentoRRALoteRegistroPonto = " rh174_lancamentorra = ".$iCodigoLancamento;
  $sSqlLancamentoRRALoteRegistroPonto   = $oDaoLancamentoRRALoteRegistroPonto->sql_query_file(null, "*", null, $sWhereLancamentoRRALoteRegistroPonto);
  $rsLancamentoRRALoteRegistroPonto     = db_query($sSqlLancamentoRRALoteRegistroPonto);

  if(!$rsLancamentoRRALoteRegistroPonto) {
    throw new DBException("Ocorreu um erro ao buscar os vínculos entre lançamentos e lotes.");
  }

  if(pg_num_rows($rsLancamentoRRALoteRegistroPonto) > 0) {    

    $iCodigoLote = db_utils::fieldsMemory($rsLancamentoRRALoteRegistroPonto, 0)->rh174_loteregistroponto;
    $oDaoLancamentoRRALoteRegistroPonto->excluir(db_utils::fieldsMemory($rsLancamentoRRALoteRegistroPonto, 0)->rh174_sequencial);

    if($oDaoLancamentoRRALoteRegistroPonto->erro_status == '0') {
      throw new DBException($oDaoLancamentoRRALoteRegistroPonto->erro_msg);
    }
  }

  if(!empty($iCodigoLote)) {

    $oLoteRegistrosPonto = LoteRegistrosPontoRepository::getInstanceByCodigo($iCodigoLote);

    /**
     * Se utiliza estrutura suplementar vincula o lote a uma folha de pagamento
     */
    if(DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {

      $oDaoLoteregistropontoRhfolhapagamento   = new cl_loteregistropontorhfolhapagamento();
      $sWhereLoteregistropontoRhfolhapagamento = " rh162_loteregistroponto = ".$oLoteRegistrosPonto->getSequencial();
      $sSqlLoteregistropontoRhfolhapagamento   = $oDaoLoteregistropontoRhfolhapagamento->sql_query_file(null, "*", null, $sWhereLoteregistropontoRhfolhapagamento);
      $rsLoteregistropontoRhfolhapagamento     = db_query($sSqlLoteregistropontoRhfolhapagamento);

      if(!$rsLoteregistropontoRhfolhapagamento) {
        throw new DBException("Ocorreu um erro ao buscar os vínculos entre lote e folha de pagamento.");
      }

      if(pg_num_rows($rsLoteregistropontoRhfolhapagamento) > 0) {

        $oDaoLoteregistropontoRhfolhapagamento->excluir(db_utils::fieldsMemory($rsLoteregistropontoRhfolhapagamento, 0)->rh162_sequencial);

        if($oDaoLoteregistropontoRhfolhapagamento->erro_status == '0') {
          throw new DBException($oDaoLoteregistropontoRhfolhapagamento->erro_msg);
        }
      }
    }

    LoteRegistrosPontoRepository::cancelarConfirmacao($oLoteRegistrosPonto);
    LoteRegistrosPontoRepository::remover($oLoteRegistrosPonto);
  }
}

/**
 * @param \Servidor $oServidor
 * @return array
 * @throws \DBException
 */
function getPensionistasDoServidor(Servidor $oServidor) {

  $oDaoPensao = new cl_pensao();
  $sCampos    = "cgm.z01_numcgm, cgm.z01_nome";
  $sSqlPensao = $oDaoPensao->sql_query_dados(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha(), $oServidor->getMatricula(), null, $sCampos);
  $rsPensao   = db_query($sSqlPensao);
  if (!$rsPensao) {
    throw new DBException('Não foi possível pesquisar os dados de pensão');
  }
  $aPensionistas = \db_utils::makeCollectionFromRecord($rsPensao, function($oDadosPensao) {

    $oPensionistaRRA          = new \stdClass();
    $oPensionistaRRA->iNumcgm = $oDadosPensao->z01_numcgm;
    $oPensionistaRRA->sNome   = $oDadosPensao->z01_nome;
    $oPensionistaRRA->nValor  = "0";
    return $oPensionistaRRA;
  });
  return $aPensionistas;
}
