<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
namespace ECidade\Configuracao\Formulario\Model;

use ECidade\Configuracao\Formulario\Repository\Pergunta;

/**
 * Class Formulario
 * @package ECidade\Configuracao\Formulario\Model
 */
class Formulario {

  /**
   *    * Codigo do Formulário
   * @var integer
   */
  protected $codigo;

  /**
   * NOme do formulario
   * @var string
   */
  protected $nome;

  /**
   * Tipo do Formulário
   * @var integer
   */
  protected $tipo;

  /**
   * 
   * @var bool
   */
  protected $ativo = false;

  /**
   * Identificador do formulário
   * @var string
   */
  protected $identificador;

  /**
   * Comnsulta SQL da carga
   * @var string
   */
  protected $carga;

  /**
   * @var \ECidade\Configuracao\Formulario\Model\Pergunta[]
   */
  protected $perguntas = array();

  /**
   * Formulario constructor.
   * @param int $codigo
   */
  public function __construct($codigo = null) {
    $this->codigo = $codigo;
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
  public function getTipo() {

    return $this->tipo;
  }

  /**
   * @param int $tipo
   */
  public function setTipo($tipo) {

    $this->tipo = $tipo;
  }

  /**
   * @return bool
   */
  public function isAtivo() {

    return $this->ativo;
  }

  /**
   * @param bool $ativo
   */
  public function setAtivo($ativo) {
    $this->ativo = $ativo;
  }

  /**
   * @return string
   */
  public function getIdentificador() {

    return $this->identificador;
  }

  /**
   * @param string $identificador
   */
  public function setIdentificador($identificador) {
    $this->identificador = $identificador;
  }

  /**
   * @return string
   */
  public function getCarga() {

    return $this->carga;
  }

  /**
   * @param string $carga
   */
  public function setCarga($carga) {
    $this->carga = $carga;
  }

  /**
   * @param \ECidade\Configuracao\Formulario\Model\Pergunta[] $perguntas
   */
  public function setPerguntas(array $perguntas) {
    $this->perguntas = $perguntas;
  }

  /**
   * @return \ECidade\Configuracao\Formulario\Model\Pergunta[]
   */
  public function getPerguntas() {
    
    if (empty($this->perguntas)) {
      $this->perguntas = Pergunta::getPerguntasDoFormulario($this);
    }
    return $this->perguntas;
  }

  /**
   * Retorna quais Perguntas sao Identifadores
   * @return \ECidade\Configuracao\Formulario\Model\Pergunta[]
   */
  public function getPerguntasIdentificadoras() {
    
    $perguntas = $this->getPerguntas();
    $perguntasIdentificadoras = array();
    foreach ($perguntas as $pergunta) {
      if ($pergunta->isPerguntaIdentificadora()) {
        
        $perguntasIdentificadoras[] = $pergunta;
        continue;
      }
    }
    return $perguntasIdentificadoras;
  }

}
