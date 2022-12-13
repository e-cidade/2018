<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

class ArquivoConsignadoManualParcelaRepository {

  /**
   * Instância da classe
   * @var ArquivoConsignadoManualParcelaRepository
   */
  public static $oInstance;

  /**
   * @var ArquivoConsignadoManualRepository[]
   */
  public $itens = array();

  private function __construct() {}

  private function __clone() {}

  /**
   * Retorna uma instância da classe
   * @return ArquivoConsignadoManualParcelaRepository
   */
  public static function getInstance() {

    if (self::$oInstance == null) {
      self::$oInstance = new ArquivoConsignadoManualParcelaRepository();
    }
    return self::$oInstance;
  }

  /**
   * @param stdClass
   * @return ArquivoConsignadoManualParcela
   */
  public function make($oDados) {

   $oParcela = new ArquivoConsignadoManualParcela();
   $oParcela->setCodigo($oDados->rh182_sequencial);
   $oParcela->setCodigoMovimentoServidor($oDados->rh152_sequencial);
   $oParcela->setCodigoMovimentoRubrica($oDados->rh153_sequencial);
   $oParcela->setCompetencia(new DBCompetencia($oDados->rh182_ano, $oDados->rh182_mes));
   $oParcela->setServidor(ServidorRepository::getInstanciaByCodigo($oDados->rh152_regist));
   $oParcela->setRubrica(RubricaRepository::getInstanciaByCodigo($oDados->rh153_rubrica));
   $oParcela->setMotivo($oDados->rh152_consignadomotivo);
   $oParcela->setProcessado($oDados->rh182_processado == 't');
   $oParcela->setParcela($oDados->rh153_parcela);
   $oParcela->setTotalDeParcelas($oDados->rh153_totalparcelas);
   $oParcela->setValor($oDados->rh153_valordescontar);
   $oParcela->setValorDescontado($oDados->rh153_valordescontado);
   $oParcela->setCodigoConsignado($oDados->rh151_sequencial);
   return $oParcela;

  }

  /**
   * Persiste os dados da parcela
   * @param \ArquivoConsignadoManualParcela $oArquivoConsignadoManualParcela
   * @param \ArquivoConsignadoManual        $oArquivoConsignadoManual
   * @return \ArquivoConsignadoManualParcela
   * @throws \BusinessException
   * @throws \DBException
   */
  public static function persist(ArquivoConsignadoManualParcela $oArquivoConsignadoManualParcela, ArquivoConsignadoManual $oArquivoConsignadoManual) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Nao existe transação com o banco de dados");
    }
    $oDaoConsignadoServidor = new cl_rhconsignadomovimentoservidor();
    $oDaoConsignadoServidor->rh152_regist              = $oArquivoConsignadoManual->getServidor()->getMatricula();
    $oDaoConsignadoServidor->rh152_nome                = $oArquivoConsignadoManual->getServidor()->getCgm()->getNome();
    $oDaoConsignadoServidor->rh152_consignadomotivo    = $oArquivoConsignadoManualParcela->getMotivo();
    $oDaoConsignadoServidor->rh152_consignadomovimento = $oArquivoConsignadoManual->getCodigo();
    $oDaoConsignadoServidor->rh152_consignadomotivo    = $oArquivoConsignadoManualParcela->getMotivo() === null ? 'null' : $oArquivoConsignadoManualParcela->getMotivo();
    $iCodigoMovimentoServidor = $oArquivoConsignadoManualParcela->getCodigoMovimentoServidor();
    if (empty($iCodigoMovimentoServidor)) {

      $oDaoConsignadoServidor->incluir(null);
      $oArquivoConsignadoManualParcela->setCodigoMovimentoServidor($oDaoConsignadoServidor->rh152_sequencial);
    } else {

      $oDaoConsignadoServidor->rh152_sequencial = $oArquivoConsignadoManualParcela->getCodigoMovimentoServidor();
      $oDaoConsignadoServidor->alterar($oArquivoConsignadoManualParcela->getCodigoMovimentoServidor());
    }
    if ($oDaoConsignadoServidor->erro_status == 0) {
      throw new BusinessException($oDaoConsignadoServidor->erro_msg);
    }

    $oDaoConsignadoManual = new cl_rhconsignadomovimentomanual();
    $oDaoConsignadoManual->rh182_ano                   = $oArquivoConsignadoManualParcela->getCompetencia()->getAno();
    $oDaoConsignadoManual->rh182_mes                   = $oArquivoConsignadoManualParcela->getCompetencia()->getMes();
    $oDaoConsignadoManual->rh182_processado            = $oArquivoConsignadoManualParcela->isProcessado() ? "true" : "false";
    $oDaoConsignadoManual->rh182_rhconsignadomovimento = $oArquivoConsignadoManual->getCodigo();
    $oDaoConsignadoManual->rh182_rhconsignadomovimentoservidor = $oDaoConsignadoServidor->rh152_sequencial;
    $iCodigoMovimento = $oArquivoConsignadoManualParcela->getCodigo();
    if (empty($iCodigoMovimento)) {

      $oDaoConsignadoManual->incluir(null);
      $oArquivoConsignadoManualParcela->setCodigo($oDaoConsignadoManual->rh182_sequencial);
    } else {

      $oDaoConsignadoManual->rh182_sequencial = $iCodigoMovimento;
      $oDaoConsignadoManual->alterar($iCodigoMovimento);
    }
    if ($oDaoConsignadoManual->erro_status == 0) {
      throw new BusinessException($oDaoConsignadoManual->erro_msg);
    }

    $oDaoConsignadoRubrica = new cl_rhconsignadomovimentoservidorrubrica;
    $oDaoConsignadoRubrica->rh153_consignadomovimentoservidor = $oDaoConsignadoServidor->rh152_sequencial;
    $oDaoConsignadoRubrica->rh153_parcela         = $oArquivoConsignadoManualParcela->getParcela();
    $oDaoConsignadoRubrica->rh153_instit          = $oArquivoConsignadoManual->getInstituicao()->getCodigo();
    $oDaoConsignadoRubrica->rh153_rubrica         = $oArquivoConsignadoManual->getRubrica()->getCodigo();
    $oDaoConsignadoRubrica->rh153_totalparcelas   = $oArquivoConsignadoManual->getNumeroDeParcelas();
    $oDaoConsignadoRubrica->rh153_valordescontado = $oArquivoConsignadoManualParcela->getValorDescontado();
    $oDaoConsignadoRubrica->rh153_valordescontar  = $oArquivoConsignadoManualParcela->getValor();
    $iCodigoMovimentoRubrica = $oArquivoConsignadoManualParcela->getCodigoMovimentoRubrica();
    if (empty($iCodigoMovimentoRubrica)) {

      $oDaoConsignadoRubrica->incluir(null);
      $oArquivoConsignadoManualParcela->setCodigoMovimentoRubrica($oDaoConsignadoRubrica->rh153_sequencial);
    } else {

      $oDaoConsignadoRubrica->rh153_sequencial = $iCodigoMovimentoRubrica;
      $oDaoConsignadoRubrica->alterar($iCodigoMovimentoRubrica);
    }
    if ($oDaoConsignadoRubrica->erro_status == 0) {
      throw new BusinessException($oDaoConsignadoRubrica->erro_msg);
    }
    return $oArquivoConsignadoManualParcela;
  }

  /**
   * Remove a parcela do financeiamento
   * @param \ArquivoConsignadoManualParcela $arquivoConsignadoManualParcela
   * @throws \BusinessException
   * @throws \DBException
   */
  public static function remove(ArquivoConsignadoManualParcela $arquivoConsignadoManualParcela) {

    if (!db_utils::inTransaction()) {
      throw new DBException("Nao existe transação com o banco de dados");
    }

    $oDaoConsignadoServidor = new cl_rhconsignadomovimentoservidor();
    $oDaoConsignadoManual   = new cl_rhconsignadomovimentomanual();
    $oDaoConsignadoRubrica  = new cl_rhconsignadomovimentoservidorrubrica;
    $oDaoConsignadoRubrica->excluir($arquivoConsignadoManualParcela->getCodigoMovimentoRubrica());
    if ($oDaoConsignadoRubrica->erro_status == 0) {
      throw new BusinessException($oDaoConsignadoRubrica->erro_msg);
    }

    $oDaoConsignadoManual->excluir($arquivoConsignadoManualParcela->getCodigo());
    if ($oDaoConsignadoManual->erro_status == 0) {
      throw new BusinessException($oDaoConsignadoManual->erro_msg);
    }
    $oDaoConsignadoServidor->excluir($arquivoConsignadoManualParcela->getCodigoMovimentoServidor());
    if ($oDaoConsignadoServidor->erro_status == 0) {
      throw new BusinessException($oDaoConsignadoServidor->erro_msg);
    }
    unset($arquivoConsignadoManualParcela);
  }

  /**
   * @param $iCodigo
   * @return ArquivoConsignadoManualParcela
   * @throws \BusinessException
   *
   */
  public static function getByCodigo($iCodigo) {

    if (empty(self::getInstance()->itens[$iCodigo])) {

      $oDaoConsignado    = new cl_rhconsignadomovimentomanual();
      $sWhere            = "rh182_sequencial = {$iCodigo}";
      $sSqlFinanciamento = $oDaoConsignado->sql_query_dados_financiamento(null, "*", null, $sWhere);
      $rsFinanciamento   = db_query($sSqlFinanciamento);
      if (!$rsFinanciamento) {
        throw  new BusinessException("Não foi possivel pesquisar financiamento manual com o codigo {$iCodigo}");
      }
      if (pg_num_rows($rsFinanciamento) == 0) {
        throw  new BusinessException("Não foi encontrado financiamento manual com o codigo {$iCodigo}");
      }
      self::getInstance()->itens[$iCodigo] = self::getInstance()->make(db_utils::fieldsMemory($rsFinanciamento, 0));
    }
    return self::getInstance()->itens[$iCodigo];
  }

  /**
   * Retorna todas as parcelas do financiamento
   * @param \ArquivoConsignadoManual $arquivoConsignadoManual
   * @return \ArquivoConsignadoManualParcela[]
   * @throws \BusinessException
   */
  public static function getParcelasDoFinanciamento(ArquivoConsignadoManual $arquivoConsignadoManual) {

    $oDaoConsignado    = new cl_rhconsignadomovimentomanual();
    $sWhere            = " rh182_rhconsignadomovimento = {$arquivoConsignadoManual->getCodigo()}";
    $sSqlFinanciamento = $oDaoConsignado->sql_query_dados_financiamento(null, "*", "rh153_parcela::int", $sWhere);
    $rsFinanciamento   = db_query($sSqlFinanciamento);
    if (!$rsFinanciamento) {
      throw  new BusinessException("NNãao foi possível pesquisar os dados da parcela para o financiamento {$arquivoConsignadoManual->getCodigo()}");
    }
    $iTotalLinhas = pg_num_rows($rsFinanciamento);
    $aParcelas = array();
    for ($iParcela = 0; $iParcela < $iTotalLinhas; $iParcela++) {

      $oDadosParcela = db_utils::fieldsMemory($rsFinanciamento, $iParcela);
      $iCodigo = $oDadosParcela->rh182_sequencial;
      if (empty(  self::getInstance()->itens[$iCodigo])) {
        self::getInstance()->itens[$iCodigo] = self::getInstance()->make($oDadosParcela);
      }
      $aParcelas[] = self::getInstance()->itens[$iCodigo];
    }
    return $aParcelas;
  }

  private static function getParcelasDoFinanciamentoFiltroCompetencia(ArquivoConsignadoManual $arquivoConsignadoManual, DBCompetencia $competencia, $filtro = DBCompetencia::COMPARACAO_MAIOR) {

    $aParcelas = self::getParcelasDoFinanciamento($arquivoConsignadoManual);
    $aParcelasCompetencia = array();
    foreach ($aParcelas as $parcela) {

      if (!$parcela->getCompetencia()->comparar($competencia, $filtro)) {
        continue;
      }
      $aParcelasCompetencia[] = $parcela;
    }
    return $aParcelasCompetencia;
  }

  /**
   * Retorna todas as parcelas do financiamento
   * @param \ArquivoConsignadoManual $arquivoConsignadoManual
   * @return \ArquivoConsignadoManualParcela[]
   * @throws \BusinessException
   */
  public static function getParcelasDoFinanciamentoApartirDaCompetencia(ArquivoConsignadoManual $arquivoConsignadoManual, DBCompetencia $competencia) {
    return self::getParcelasDoFinanciamentoFiltroCompetencia($arquivoConsignadoManual, $competencia, DBCompetencia::COMPARACAO_MAIOR_IGUAL);
  }

  /**
   * Retorna todas as parcelas do financiamento até a competencia informada
   * @param \ArquivoConsignadoManual $arquivoConsignadoManual
   * @return \ArquivoConsignadoManualParcela[]
   * @throws \BusinessException
   */
  public static function getParcelasDoFinanciamentoAteACompetencia(ArquivoConsignadoManual $arquivoConsignadoManual, DBCompetencia $competencia) {
    return self::getParcelasDoFinanciamentoFiltroCompetencia($arquivoConsignadoManual, $competencia, DBCompetencia::COMPARACAO_MENOR_IGUAL);

  }

  /**
   * @param \DBCompetencia $competencia
   * @param \Banco         $banco
   * @param \Instituicao   $instituicao
   * @return ArquivoConsignadoManualParcela[]
   * @throws \BusinessException
   */
  public static function getParcelasProcessadasNaCompetenciaPorBanco(DBCompetencia $competencia, Banco $banco, $instituicao = null) {

    if(empty($instituicao)) {
      $instituicao = InstituicaoRepository::getInstituicaoSessao();
    }

    return ArquivoConsignadoManualParcelaRepository::getParcelasProcessadasNaCompetencia($competencia, $instituicao, $banco);
  }

  /**
   * @param \DBCompetencia $competencia
   * @param \Instituicao   $instituicao
   * @param \Banco         $banco
   * @return ArquivoConsignadoManualParcela[]
   * @throws \BusinessException
   */
  public static function getParcelasProcessadasNaCompetencia(DBCompetencia $competencia, Instituicao $instituicao, $banco = null) {
    
    $aParcelasNaCompetencia              = array();
    $aWhererhconsignadomovimentomanual   = array();

    $oDaorhconsignadomovimentomanual     = db_utils::getDao('rhconsignadomovimentomanual');

    $aCamposrhconsignadomovimentomanual  = array('rh182_sequencial', 'rh152_sequencial', 'rh153_sequencial', 'rh182_ano', 'rh182_mes', 
                                                 'rh152_regist', 'rh153_rubrica', 'rh152_consignadomotivo', 'rh154_motivo', 'rh182_processado',
                                                 'rh153_parcela', 'rh153_totalparcelas', 'rh153_valordescontar', 'rh153_valordescontado', 'rh151_sequencial');
    $sCamposrhconsignadomovimentomanual  = implode(', ', $aCamposrhconsignadomovimentomanual);

    $aWhererhconsignadomovimentomanual[] = "rh151_instit     = ". $instituicao->getCodigo();

    if($banco instanceof Banco) {
      $aWhererhconsignadomovimentomanual[] = "rh151_banco      = '". $banco->getCodigo() ."'";
    }

    $aWhererhconsignadomovimentomanual[] = "rh182_processado is true";
    $aWhererhconsignadomovimentomanual[] = "rh182_ano = ". $competencia->getAno();
    $aWhererhconsignadomovimentomanual[] = "rh182_mes = ". $competencia->getMes();
    $sWhererhconsignadomovimentomanual   = implode(' AND ', $aWhererhconsignadomovimentomanual);

    $sSqlrhconsignadomovimentomanual     = $oDaorhconsignadomovimentomanual->sql_query_dados_financiamento(null, $sCamposrhconsignadomovimentomanual, null, $sWhererhconsignadomovimentomanual);
    $rsRhconsignadomovimentomanual       = db_query($sSqlrhconsignadomovimentomanual);

    $aParcelasNaCompetencia = db_utils::makeCollectionFromRecord($rsRhconsignadomovimentomanual, function($oParcela) {

      $oParcela->rh152_consignadomotivo = $oParcela->rh154_motivo;
      
      if (empty(ArquivoConsignadoManualParcelaRepository::getInstance()->itens[$oParcela->rh182_sequencial])) {
        ArquivoConsignadoManualParcelaRepository::getInstance()->itens[$oParcela->rh182_sequencial] = ArquivoConsignadoManualParcelaRepository::getInstance()->make($oParcela);
      }

      return ArquivoConsignadoManualParcelaRepository::getInstance()->itens[$oParcela->rh182_sequencial];
    });

    return $aParcelasNaCompetencia;
  }

  /**
   * @param \DBCompetencia $competencia
   * @param \Instituicao   $instituicao
   * @return ArquivoConsignadoManualParcela[]
   * @throws \BusinessException
   */
  public static function getParcelasNaCompetencia(DBCompetencia $competencia, Instituicao $instituicao) {

    $oDaoConsignado    = new cl_rhconsignadomovimentomanual();
    $sWhere            = " rh151_instit = {$instituicao->getCodigo()}";
    $sWhere           .= " and rh151_tipoconsignado = '".ArquivoConsignadoManual::TIPO_MANUAL."'";
    $sWhere           .= " and rh182_ano = {$competencia->getAno()} ";
    $sWhere           .= " and rh182_mes = {$competencia->getMes()} ";
    $sSqlFinanciamento = $oDaoConsignado->sql_query_dados_financiamento(null, "*", "rh153_parcela::int", $sWhere);
    $rsFinanciamento   = db_query($sSqlFinanciamento);
    if (!$rsFinanciamento) {
      throw  new BusinessException("Não foi possível pesquisar os dados da parcela para a competência {$competencia->getCompetencia()}");
    }
    $iTotalLinhas = pg_num_rows($rsFinanciamento);
    $aParcelas = array();
    for ($iParcela = 0; $iParcela < $iTotalLinhas; $iParcela++) {

      $oDadosParcela = db_utils::fieldsMemory($rsFinanciamento, $iParcela);
      $iCodigo = $oDadosParcela->rh182_sequencial;
      if (empty(ArquivoConsignadoManualParcelaRepository::getInstance()->itens[$iCodigo])) {
        ArquivoConsignadoManualParcelaRepository::getInstance()->itens[$iCodigo] = self::getInstance()->make($oDadosParcela);
      }
      $aParcelas[] = ArquivoConsignadoManualParcelaRepository::getInstance()->itens[$iCodigo];
    }
    return $aParcelas;
  }


  /**
   * @param \DBCompetencia $competencia
   * @param \Instituicao   $instituicao
   * @return ArquivoConsignadoManualParcela[]
   * @throws \BusinessException
   */
  public static function getParcelasParaProcessamentoNaCompetencia(DBCompetencia $competencia, Instituicao $instituicao) {

    $aParcelas = self::getParcelasNaCompetencia($competencia, $instituicao);
    $aParcelasProcessamento = array();
    foreach ($aParcelas as $oParcela) {

      if ($oParcela->isProcessado()) {
        continue;
      }
      $aParcelasProcessamento[] = $oParcela;
    }
    return $aParcelasProcessamento;
  }
}


