<?php

namespace ECidade\Tributario\Grm;
use ECidade\Tributario\Grm\Repository\TipoRecolhimento as TipoRecolhimentoRepository;
use ECidade\Tributario\Grm\TipoRecolhimento;

/**
 * Unidade Gestora 
 * Class UnidadeGestora
 * @package Ecidade\Tributario\Grm
 */
class UnidadeGestora {

  /**
   * Código da Unidde Gestora
   * @var integer
   */
  protected $codigo;

  /**
   * Nome da Unidade Gestora
   * @var string
   */
  protected $nome;

  /**
   * @var \DBDepartamento
   */
  protected $departamento;

  /**
   * Recolhimentos da Unidade gestora
   * @var
   */
  protected $recolhimentos = array();

  /**
   * Instancia uma Unidade Gestora
   * UnidadeGestora constructor.
   */
  public function __construct() {
    
  }

  /**
   * Retorna o Código da Unidade Gestora
   * @return integer
   */
  public function getCodigo() {
    return $this->codigo;
  }

  /**
   * Define o código da Unidade Gestora
   * @param integer $codigo
   */
  public function setCodigo($codigo) {
    $this->codigo = $codigo;
  }

  /**
   * Nome da Unidade Gestora
   * @return string
   */
  public function getNome() {    
    return $this->nome;
  }

  /**
   * Retorno o nome da unidade gestora
   * @param mixed $nome
   */
  public function setNome($nome) {
    $this->nome = $nome;
  }

  /**
   * @return \DBDepartamento
   */
  public function getDepartamento() {

    return $this->departamento;
  }

  /**
   * @param \DBDepartamento $departamento
   */
  public function setDepartamento(\DBDepartamento $departamento) {
    $this->departamento = $departamento;
  }

  /**
   * @param \ECidade\Tributario\Grm\RecolhimentoUnidadeGestora $recolhimentoUnidadeGestora   
   */
  public function adicionarRecolhicomento(RecolhimentoUnidadeGestora $recolhimentoUnidadeGestora) {    
    
    $this->getRecolhimentos();
    $this->recolhimentos[$recolhimentoUnidadeGestora->getTipoRecolhimento()->getCodigo()] = $recolhimentoUnidadeGestora;
  }

  /**
   * @return RecolhimentoUnidadeGestora[]
   */
  public function getRecolhimentos() {

    if (empty($this->recolhimentos)) {

      $oTipoRecolhimentoRepository = new TipoRecolhimentoRepository();
      $aRecolhimentos = $oTipoRecolhimentoRepository->getTiposRecolhimentoDaUnidadeGestora($this);
      foreach ($aRecolhimentos as $oRecolhimento) {
        $this->recolhimentos[$oRecolhimento->getTipoRecolhimento()->getCodigo()] = $oRecolhimento;
      }
    }    
    return $this->recolhimentos;        
  }

  /**
   * @param \ECidade\Tributario\Grm\TipoRecolhimento $oTipoRecolhimento
   */
  public function removerRecolhimento(TipoRecolhimento $oTipoRecolhimento) {
    
    $oTipoRecolhimentoRepository = new TipoRecolhimentoRepository();
    $oTipoRecolhimentoRepository->removerRecolhimentoDaUnidade($oTipoRecolhimento, $this);
  }

  /**
   * @param \ECidade\Tributario\Grm\TipoRecolhimento $oTipoRecolhimento
   * @return \ECidade\Financeiro\Tesouraria\Receita|null
   */
  public function getReceitaDoTipoDeRecolhimento(TipoRecolhimento $oTipoRecolhimento) {
    
    foreach ($this->getRecolhimentos() as $oRecolhimento) {
      if ($oRecolhimento->getTipoRecolhimento()->getCodigo() === $oTipoRecolhimento->getCodigo()) {
        return $oRecolhimento->getReceita();
      }
    }
    return null;
  }
}