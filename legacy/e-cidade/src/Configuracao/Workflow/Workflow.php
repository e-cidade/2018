<?php
namespace ECidade\Configuracao\Workflow;

use ECidade\Configuracao\Workflow\Repository\Workflow as WorkflowRepository;

/**
 * Class Workflow
 * @package ECidade\Configuracao\Workflow
 */
class Workflow {

  /**
   * Código da Atividade
   * @var integer
   */
  protected $codigo;

  /**
   * Atividades que o workflow deve realizar
   * @var Atividade[]
   */
  protected $atividades;

  /**
   * Nome da atividade 
   * @var string
   */
  protected $nome;  
  
  protected $tipoProcesso;

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
   * @return Atividade[]
   */
  public function getAtividades() {

    if (empty($this->atividades)) {
      
      $oRepository      = new WorkflowRepository();
      $this->atividades = $oRepository->getAtividadesDoWorkflow($this);
    }
    return $this->atividades;
  }

  /**
   * @param array $atividades
   */
  public function setAtividades($atividades) {

    $this->atividades = $atividades;
  }

  /**
   * @return string
   */
  public function getNome() {

    return $this->nome;
  }

  /**
   * @param string $nome
   */
  public function setNome($nome) {

    $this->nome = $nome;
  }  
  
  public function getAtividadeNaOrdem($ordem) {
    
    foreach ($this->getAtividades() as $atividade) {
     
      if ($atividade->getOrdem() == $ordem) {
        return $atividade;
      }
    }
  }

  /**
   * @return mixed
   */
  public function getTipoProcesso() {

    return $this->tipoProcesso;
  }

  /**
   * @param mixed $tipoProcesso
   */
  public function setTipoProcesso($tipoProcesso) {

    $this->tipoProcesso = $tipoProcesso;
  }
    
}