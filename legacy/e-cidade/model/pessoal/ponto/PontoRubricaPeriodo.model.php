<?php

/**
 * Controle das rubrica que possuem controle de periodos no ponto de salario e fixo
 * Class PontoRubricaPeriodo
 */
class PontoRubricaPeriodo {

  /**
   * @var integer
   */
  private $codigo;

  /**
   * @var Rubrica
   */
  private $rubrica;

  /**
   * @var DBDate
   */
  private $dataInicio;

  /**
   * @var DBDate
   */
  private $dataFim;


  /**
   * @var Servidor
   */
  private $servidor;

  /**
   * @var float
   */
  private $quantidade = 0;

  /**
   * @var float
   */
  private $valor = 0;

  /**
   * @var Instituicao
   */
  private $instituicao;

  /**
   * PontoRubricaPeriodo constructor.
   */
  public function __construct() {

  }

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->codigo;
  }

  /**
   * @param int $codigo
   */
  public function setCodigo($codigo) {
    $this->codigo = $codigo;
  }

  /**
   * @return \Rubrica
   */
  public function getRubrica() {
    return $this->rubrica;
  }

  /**
   * @param \Rubrica $rubrica
   */
  public function setRubrica(Rubrica $rubrica) {

    $this->rubrica = $rubrica;
  }

  /**
   * @return \DBDate
   */
  public function getDataInicio() {

    return $this->dataInicio;
  }

  /**
   * @param \DBDate $dataInicio
   */
  public function setDataInicio(DBDate $dataInicio) {

    $this->dataInicio = $dataInicio;
  }

  /**
   * @return \DBDate
   */
  public function getDataFim() {

    return $this->dataFim;
  }

  /**
   * @param \DBDate $dataFim
   */
  public function setDataFim(DBDate $dataFim) {

    $this->dataFim = $dataFim;
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
  public function setServidor(Servidor $servidor) {
    $this->servidor = $servidor;
  }

  /**
   * @param Instituicao $instituicao
   */
  public function setInstituicao(Instituicao $instituicao) {
    $this->instituicao = $instituicao;
  }

  /**
   * @return instituicao
   */
  public function getInstituicao() {
    return $this->instituicao;
  }

  /**
   * @param integer $quantidade
   */
  public function setQuantidade($quantidade) {
    $this->quantidade = $quantidade;
  }

  /**
   * @return float
   */
  public function getQuantidade() {
    return $this->quantidade;
  }

  /**
   * @param integer $valor
   */
  public function setValor($valor) {
    $this->valor = $valor;
  }

  /**
   * @return float
   */
  public function getValor() {
    return $this->valor;
  }

  /**
   * Remove a rubrica do servidor dos pontos de salario e fixo
   * @param \Rubrica       $oRubrica
   * @param \Servidor      $oServidor
   * @param \DBCompetencia $oCompetenciaFolha
   * @throws \DBException
   * @internal param \DBCompetencia $getCompetenciaFolha
   */
  public static function removerRubricaDoPontosDoServidor(Rubrica $oRubrica, Servidor $oServidor, DBCompetencia $oCompetenciaFolha) {

    if (!db_utils::inTransaction()) {
      throw new DBException('Sem transação com o banco de dados');
    }

    $oDaoPontoFS = new cl_pontofs();
    $oDaoPontoFX = new cl_pontofx();
    $oDaoPontoFS->excluir($oCompetenciaFolha->getAno(), $oCompetenciaFolha->getMes(), $oServidor->getMatricula(), $oRubrica->getCodigo());
    if ($oDaoPontoFS->erro_status == 0) {
      throw new DBException("Erro ao remover rubrica {$oRubrica->getCodigo()} do ponto de saláriodo servidor {$oServidor->getMatricula()}");
    }
    $oDaoPontoFX->excluir($oCompetenciaFolha->getAno(), $oCompetenciaFolha->getMes(), $oServidor->getMatricula(), $oRubrica->getCodigo());
    if ($oDaoPontoFX->erro_status == 0) {
      throw new DBException("Erro ao remover rubrica {$oRubrica->getCodigo()} do ponto de saláriodo servidor {$oServidor->getMatricula()}");
    }

  }
}