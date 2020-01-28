<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 08/03/17
 * Time: 09:24
 */

namespace ECidade\Configuracao\Workflow;


class Atividade {

  /**
   * Código da Atividade
   * @var integer
   */
  protected $codigo;

  /**
   * Nome da Atividade
   * @var string
   */
  protected $nome;

  /**
   * Ordem de Execução da atividade
   * @var integer
   */
  protected $ordem;

  /**
   * Código do Grupo de 
   * @var integer
   */
  protected $grupoAtributos = '';

  /**
   * Departamento da atividade
   * @var \DBDepartamento
   */
  protected $departamento;

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

  /**
   * @return int
   */
  public function getOrdem() {

    return $this->ordem;
  }

  /**
   * @param int $ordem
   */
  public function setOrdem($ordem) {

    $this->ordem = $ordem;
  }

  /**
   * @return integer
   */
  public function getGrupoAtributos() {

    return $this->grupoAtributos;
  }

  /**
   * @param integer $grupoAtributos
   */
  public function setGrupoAtributos($grupoAtributos) {    
    $this->grupoAtributos = $grupoAtributos;
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

  

}