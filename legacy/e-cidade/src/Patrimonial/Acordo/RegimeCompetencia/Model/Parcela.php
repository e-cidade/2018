<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 21/03/17
 * Time: 13:37
 */

namespace ECidade\Patrimonial\Acordo\RegimeCompetencia\Model;


class Parcela {

  /**
   * Codigo
   * @var integer
   */
  protected $codigo;
  
  /**
   * Competenca da parcela
   * @var \DBCompetencia;
   */
  protected $competencia;
  
  /**
   * Numero da parcela
   * @var integer
   */
  protected $numero;

  /**
   * Numero da parcela
   * @var float
   */
  protected $valor = 0;

  /**
   * @var bool
   */
  protected $reconhecida = false;

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
   * @return \DBCompetencia
   */
  public function getCompetencia() {

    return $this->competencia;
  }

  /**
   * @param \DBCompetencia $competencia
   */
  public function setCompetencia($competencia) {

    $this->competencia = $competencia;
  }

  /**
   * @return int
   */
  public function getNumero() {

    return $this->numero;
  }

  /**
   * @param int $numero
   */
  public function setNumero($numero) {

    $this->numero = $numero;
  }

  /**
   * @return float
   */
  public function getValor() {

    return $this->valor;
  }

  /**
   * @param float $valor
   */
  public function setValor($valor) {

    $this->valor = $valor;
  }

  /**
   * @return bool
   */
  public function isReconhecida() {
    return $this->reconhecida;
  }

  /**
   * @param bool $reconhecida
   */
  public function setReconhecida($reconhecida) {
    $this->reconhecida = $reconhecida;
  } 
 
  
}