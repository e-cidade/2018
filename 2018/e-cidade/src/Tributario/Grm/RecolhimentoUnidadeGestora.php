<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 01/03/17
 * Time: 17:10
 */

namespace ECidade\Tributario\Grm;


use ECidade\Financeiro\Tesouraria\Receita;

class RecolhimentoUnidadeGestora {

  /**
   * @var TipoRecolhimento
   */
  protected $tipoRecolhimento;
  /**
   * @var Receita
   */
  protected $receita;

  /**
   * @return \ECidade\Tributario\Grm\TipoRecolhimento
   */
  public function getTipoRecolhimento() {

    return $this->tipoRecolhimento;
  }

  /**
   * @param \ECidade\Tributario\Grm\TipoRecolhimento $tipoRecolhimento
   */
  public function setTipoRecolhimento($tipoRecolhimento) {
    $this->tipoRecolhimento = $tipoRecolhimento;
  }

  /**
   * @return Receita
   */
  public function getReceita() {    
    return $this->receita;
  }

  /**
   * @param Receita $receita
   */
  public function setReceita($receita) {
    $this->receita = $receita;
  }  
  
}