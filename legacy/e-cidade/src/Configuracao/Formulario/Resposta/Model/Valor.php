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
namespace ECidade\Configuracao\Formulario\Resposta\Model;

/**
 * Class Resposta
 * @package ECidade\Configuracao\Formulario\Model
 */
class Valor {

  /**
   * @var int
   */
  private $codigo;
  
  /**
   * Pergunta
   * @var
   */
  private $pergunta;

  /**
   * @var string
   */
  private $valor;

  /**
   * codigo da opcao da resposta
   * @var \ECidade\Configuracao\Formulario\Model\Opcao
   */
  private $opcao;

  /**
   * @return \ECidade\Configuracao\Formulario\Model\Pergunta
   * 
   */
  public function getPergunta() {

    return $this->pergunta;
  }

  /**
   * @param mixed $pergunta
   */
  public function setPergunta($pergunta) {

    $this->pergunta = $pergunta;
  }

  /**
   * @return string
   */
  public function getValor() {

    return $this->valor;
  }

  /**
   * @param string $valor
   */
  public function setValor($valor) {

    $this->valor = $valor;
  }

  /**
   * @return \ECidade\Configuracao\Formulario\Model\Opcao
   */
  public function getOpcao() {

    return $this->opcao;
  }

  /**
   * @param \ECidade\Configuracao\Formulario\Model\Opcao $opcao
   */
  public function setOpcao($opcao) {

    $this->opcao = $opcao;
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
  
  
}