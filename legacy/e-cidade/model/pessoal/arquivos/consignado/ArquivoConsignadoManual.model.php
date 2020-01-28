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

/**
 * Controle de consignados manuais
 * @package Pessoal
 */
class ArquivoConsignadoManual extends ArquivoConsignado {


  /**
   * @var Servidor
   */
  private $servidor;

  /**
   * Número total de parcelas
   * @var integer
   */
  private $numeroParcelas;

  /**
   * Parcela Inicial
   * @var int
   */
  private $parcelaInicial = 1;

  /**
   * @var integer
   */
  private $situacao = 0;

  /**
   * @var float
   */
  private $valorParcela = 0;

  /**
   * Rubrica onde serta feito o pagamento
   * @var Rubrica
   */
  private $rubrica;

  /**
   * Competencia Inicial do desconto
   * @var DBCompetencia
   */
  private $competencia;

  /**
   * Lista de parcelas
   * @var ArquivoConsignadoManualParcela[]
   */
  private $parcelas = array();

  /**
   * @var ArquivoConsignadoManual
   */
  private $consignadoOrigem;

  /**
   * @var integer
   */
  private $codigoConsignadoOrigem;

  /**
   * Situacao normal do financiamento
   * @var integer
   */
  const SITUACAO_NORMAL = 'N';

  /**
   * Parcelamento foi refinanciado
   * @var integer
   */
  const SITUACAO_REFINANCIADO = 'R';

  /**
   * Parcelamento foi portado para outro Banco
   * @var integer
   */
  const SITUACAO_PORTADO = 'P';

  /**
   * Consignado foi cancelado
   */
  const SITUACAO_CANCELADO = 'C';

  /**
   * Consignado está Inativo
   */
  const SITUACAO_INATIVO = 'I';

  /**
   * ArquivoConsignadoManual constructor
   */
  public function __construct() {

  }

  /**
   * @return \Servidor
   */
  public function getServidor() {
    return $this->servidor;
  }

  /**
   * @param \Servidor $servidor
   */
  public function setServidor($servidor) {
    $this->servidor = $servidor;
  }

  /**
   * @return int
   */
  public function getNumeroDeParcelas() {

    return $this->numeroParcelas;
  }

  /**
   * @param int $numeroParcelas
   */
  public function setNumeroDeParcelas($numeroParcelas) {
    $this->numeroParcelas = $numeroParcelas;
  }

  /**
   * @return int
   */
  public function getSituacao() {
    return $this->situacao;
  }

  /**
   * Defina qual a situacao de origem
   * @param int $situacao
   */
  public function setSituacao($situacao) {

    $this->situacao = $situacao;
  }

  /**
   * Retorna o valor da parcela
   * @return float
   */
  public function getValorDaParcela() {
    return $this->valorParcela;
  }

  /**
   * Define o valor da parcela
   * @param float $valorParcela
   */
  public function setValorDaParcela($valorParcela) {

    $this->valorParcela = $valorParcela;
  }

  /**
   * Retorna a rubrica do financiamento
   * @return Rubrica
   */
  public function getRubrica() {

    return $this->rubrica;
  }

  /**
   * Define a rubrica do financiamento
   * @param Rubrica $rubrica
   */
  public function setRubrica(Rubrica $rubrica) {
    $this->rubrica = $rubrica;
  }

  /**
   * Retorna a competencia de inicio do financiamento
   * @return \DBCompetencia
   */
  public function getCompetencia() {

    return $this->competencia;
  }

  /**
   * Define a competencia de inicio do financiamento
   * @param \DBCompetencia $competencia
   */
  public function setCompetencia(DBCompetencia $competencia) {
    $this->competencia = $competencia;
  }

  /**
   * Retorna todas as parcelas do financiamento
   * @return \ArquivoConsignadoManualParcela[]
   */
  public function getParcelas() {

    if (empty($this->parcelas)) {
      $this->parcelas = ArquivoConsignadoManualParcelaRepository::getParcelasDoFinanciamento($this);
    }
    return $this->parcelas;
  }

  /**
   * @return \ArquivoConsignadoManual
   */
  public function getConsignadoOrigem() {

    if (empty($this->consignadoOrigem) && !empty($this->codigoConsignadoOrigem)) {
      $this->consignadoOrigem = ArquivoConsignadoManualRepository::getByCodigo($this->codigoConsignadoOrigem);
    }
    return $this->consignadoOrigem;
  }

  /**
   * @param \ArquivoConsignadoManual $consignadoOrigem
   */
  public function setConsignadoOrigem(ArquivoConsignadoManual $consignadoOrigem = null) {

    if (empty($consignadoOrigem)) {
      return;
    }

    $this->consignadoOrigem       = $consignadoOrigem;
    $this->codigoConsignadoOrigem = $consignadoOrigem->getCodigo();
  }

  /**
   * @return int
   */
  public function getCodigoConsignadoOrigem() {
    return $this->codigoConsignadoOrigem;
  }

  /**
   * @param int $codigoConsignadoOrigem
   */
  public function setCodigoConsignadoOrigem($codigoConsignadoOrigem) {
    $this->codigoConsignadoOrigem = $codigoConsignadoOrigem;
  }



  /**
   * Adiciona as parcelas do financiamento
   * @return ArquivoConsignadoManualParcela[]
   */
  public function adicionarParcelas($iParcelaInicial = 1, DBCompetencia $oCompetenciaInicial = null) {

    /**
     * a classe DBCompetencia, cquando retorna a proxima competencia, altera a instancia atual da competencia também;
     */
    $this->parcelas = array();
    $oCompetencia   = $this->competencia;
    if (!empty($oCompetenciaInicial)) {
      $oCompetencia = $oCompetenciaInicial;
    }
    for ($i = $iParcelaInicial; $i <= $this->getNumeroDeParcelas(); $i++) {

      $lProcessado = false;
      $sMotivo     = null;
      if ($oCompetencia->comparar(DBPessoal::getCompetenciaFolha(), DBCompetencia::COMPARACAO_MENOR)) {

        $lProcessado = true;
        $sMotivo     = $this->getSituacaoDaParcelaNaCompetencia($oCompetencia);
      }
      $oParcela = new ArquivoConsignadoManualParcela();
      $oParcela->setRubrica($this->getRubrica());
      $oParcela->setServidor($this->getServidor());
      $oParcela->setCompetencia($oCompetencia);
      $oParcela->setParcela($i);
      $oParcela->setProcessado($lProcessado);
      $oParcela->setMotivo($sMotivo);
      $oParcela->setTotalDeParcelas($this->getNumeroDeParcelas());
      $oParcela->setValor($this->getValorDaParcela());
      $oParcela->setValorDescontado("0");
      $this->parcelas[] = $oParcela;
      $oCompetencia = $oCompetencia->getProximaCompetencia();
    }
    return $this->parcelas;
  }

  /**
   * Verifica se o financiamento foi feito na mesma competencia
   * @param \ArquivoConsignadoManual $oArquivo
   * @return bool
   */
  public static function temFinanciamentoNoMesmoBancoECompetencia(ArquivoConsignadoManual $oArquivo) {

    $oFinanciamentoBanco = ArquivoConsignadoManualRepository::getByServidorBancoRubrica($oArquivo->getServidor(), $oArquivo->getRubrica(), $oArquivo->getBanco());
    if (!empty($oFinanciamentoBanco) && $oFinanciamentoBanco->getCodigo() != $oArquivo->getCodigo() &&
      $oArquivo->getCompetencia()->comparar($oFinanciamentoBanco->getCompetencia(), DBCompetencia::COMPARACAO_MENOR_IGUAL)) {
       return true;
    }
    return false;
  }

  /**
   * Verifica se o consignado foi portado ou parcelado
   * @return bool
   * @throws \BusinessException
   */
  public function temMovimentacao() {

    $oDaoConsignado = new cl_rhconsignadomovimento;
    $sSqlMovimento  = $oDaoConsignado->sql_query_file(null, "rh151_sequencial", null, "rh151_consignadoorigem = {$this->getCodigo()}");
    $rsConsignadoVinculo = db_query($sSqlMovimento);
    if (!$rsConsignadoVinculo) {
      throw new BusinessException('Não foi possível pesquisar se o consignado foi refinanciado ou portado.');
    }
    return pg_num_rows($rsConsignadoVinculo) > 0;
  }
  /**
   * Verifica se o consignado foi portado ou parcelado
   * @return bool
   * @throws \BusinessException
   */
  public function temParcelasProcessadas() {

    $oDaoConsignado = new cl_rhconsignadomovimentomanual();
    $sWhere         = "rh182_rhconsignadomovimento = {$this->getCodigo()}";
    $sWhere        .= "and rh182_processado is true";

    $sSqlMovimento  = $oDaoConsignado->sql_query_file(null, "rh182_sequencial", "rh182_sequencial limit 1", $sWhere);
    $rsConsignadoVinculo = db_query($sSqlMovimento);
    if (!$rsConsignadoVinculo) {
      throw new BusinessException('Não foi possível pesquisar se o consignado possui parcelas processadas.');
    }
    return pg_num_rows($rsConsignadoVinculo) > 0;
  }

  /**
   * @return int
   */
  public function getParcelaInicial() {
    return $this->parcelaInicial;
  }

  /**
   * @param int $parcelaInicial
   */
  public function setParcelaInicial($parcelaInicial) {
    $this->parcelaInicial = $parcelaInicial;
  }

  /**
   * Verifica se a parcela já foi paga anteriormente
   * @param \DBCompetencia $competencia
   * @return int|null
   * @throws \BusinessException
   */
  private function getSituacaoDaParcelaNaCompetencia(DBCompetencia $competencia) {


    $oDaoCalculo = new cl_gerfsal();
    $sSqlRubrica = $oDaoCalculo->sql_query_file($competencia->getAno(),
                                                $competencia->getMes(),
                                                $this->getServidor()->getMatricula(),
                                                $this->getRubrica()->getCodigo()
                                               );
    $rsDadosRubrica = db_query($sSqlRubrica);
    if (!$rsDadosRubrica) {
      throw new BusinessException('Não foi possível verificar se existe pagamento para essa rubrica.');
    }
    if (pg_num_rows($rsDadosRubrica) > 0) {
      return '';
    }
    return ArquivoConsignadoMotivo::MOTIVO_OUTROS_MOTIVOS;
  }

  public function getSituacaoAnterior() {

    $sSituacaoAnterior = ArquivoConsignadoManual::SITUACAO_NORMAL;
    $oOrigem = $this->getConsignadoOrigem();
    if (empty($oOrigem)) {
      return $sSituacaoAnterior;
    }

    $sSituacaoAnterior = ArquivoConsignadoManual::SITUACAO_PORTADO;
    if ($oOrigem->getBanco()->getCodigo() ==  $this->getBanco()->getCodigo()) {
      $sSituacaoAnterior = ArquivoConsignadoManual::SITUACAO_REFINANCIADO;
    }
    return $sSituacaoAnterior;
  }
}