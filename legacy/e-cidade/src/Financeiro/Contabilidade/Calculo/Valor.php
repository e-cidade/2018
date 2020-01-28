<?php
namespace ECidade\Financeiro\Contabilidade\Calculo;
/**
 * Class Valor
 * ValueObject que mantem valores incluidos e estornados determinados na construção do objeto
 * @package ECidade\Financeiro\Contabilidade\Calculo
 */
class Valor {

  /**
   * @var float
   */
  private $valorInclusao = 0;

  /**
   * @var float
   */
  private $valorEstorno  = 0;

  /**
   * @return float
   */
  public function getValorInclusao() {
    return $this->valorInclusao;
  }

  /**
   * @param float $valorInclusao
   */
  public function setValorInclusao($valorInclusao) {
    $this->valorInclusao = $valorInclusao;
  }

  /**
   * @return float
   */
  public function getValorEstorno() {
    return $this->valorEstorno;
  }

  /**
   * @param float $valorEstorno
   */
  public function setValorEstorno($valorEstorno) {
    $this->valorEstorno = $valorEstorno;
  }

  /**
   * Retorna o valor incluso menos estornado
   * @return float
   */
  public function getValorInclusaoMenosEstorno() {
    return ($this->valorInclusao - $this->valorEstorno);
  }
}
