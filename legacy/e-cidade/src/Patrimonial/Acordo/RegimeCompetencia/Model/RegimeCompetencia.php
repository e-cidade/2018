<?php
namespace ECidade\Patrimonial\Acordo\RegimeCompetencia\Model;

/**
 * Class RegimeCompetencia
 * @package ECidade\Patrimonial\Acordo\RegimeCompetencia\Model
 */
class RegimeCompetencia {

  /**
   * Codigo do regime 
   * @var integer
   */  
  private $codigo;
  
  /**
   * Acordo do regime
   * @var \Acordo
   */
  private $acordo;

  /**
   *  Tipo do Regime de competencia
   * @var  boolean
   */
  private $despesaAntecipada = false;

  /**
   * @var \ContaPlanoPCASP
   */
  private $Conplano;

  /**
   * @var Parcela[]
   */
  private $parcelas = array();

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
   * @return \Acordo
   */
  public function getAcordo() {
    return $this->acordo;
  }

  /**
   * @param \Acordo $acordo
   */
  public function setAcordo($acordo) {

    $this->acordo = $acordo;
  }

  /**
   * @return boolean
   */
  public function isDespesaAntecipada() {
    return $this->despesaAntecipada;
  }

  /**
   * @param boolean $despesaAntecipada
   */
  public function setDespesaAntecipada($despesaAntecipada) {

    $this->despesaAntecipada = $despesaAntecipada;
  }

  /**
   * @return \ContaPlanoPCASP
   */
  public function getConta() {

    return $this->Conplano;
  }

  /**
   * @param \ContaPlanoPCASP $Conplano
   */
  public function setConta(\ContaPlanoPCASP $Conplano) {

    $this->Conplano = $Conplano;
  }

  /**
   * Retorna todas as parcelas do regime
   * @return \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela[]
   */
  public function getParcelas() {
    
    if (!empty($this->parcelas)) {
      return $this->parcelas;
    };
    $oRegimeCompetenciaRepository = new \ECidade\Patrimonial\Acordo\RegimeCompetencia\Repository\RegimeCompetencia();
    $this->parcelas = $oRegimeCompetenciaRepository->getParcelasDoRegime($this);
    return $this->parcelas;
    
  }

  /**
   * Realiza o calculo dos valores das parcelas   
   * @param $numeroParcelas
   * @param $mesInicial
   * @param $ano
   * @param $valor
   * @return \ECidade\Patrimonial\Acordo\RegimeCompetencia\Model\Parcela[]
   * @internal param $item
   */
  public function processarParcelas ($numeroParcelas, $mesInicial, $ano, $valor) {    
    
    $nValorPorParcela = round($valor / $numeroParcelas, 2);
    $mesAtual         = $mesInicial;
    $aParcelas        = array();      
    $nValorTotalProcessado = 0;
    foreach (range(1, $numeroParcelas) as $parcela) {
      
      $oParcela = new Parcela();
      $oParcela->setValor($nValorPorParcela);
      $oParcela->setCompetencia(new \DBCompetencia($ano, $mesAtual));
      $oParcela->setNumero($parcela);      
      $nValorTotalProcessado += $nValorPorParcela;
      
      $aParcelas[] = $oParcela;
      $mesAtual++;
      if ($mesAtual > 12) {
        
        $mesAtual = 1;
        $ano++;
      }
    }
    if ($nValorTotalProcessado !== $valor) {
      $oParcela->setValor($oParcela->getValor() +($valor - $nValorTotalProcessado));
    }
    
    return $aParcelas;
  }

  /**
   * Retorna o saldo a programar do regime
   * @return float
   */
  public function getSaldoProgramar() {

    $nValorAcordo = $this->getAcordo()->getValoresItens()->valoratual;
    
    foreach ($this->getParcelas() as $parcela) {
      $nValorAcordo -= $parcela->getValor();
    }    
    return $nValorAcordo;
  }
}