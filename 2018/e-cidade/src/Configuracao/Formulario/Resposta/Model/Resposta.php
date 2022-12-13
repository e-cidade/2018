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
use ECidade\Configuracao\Formulario\Model\Pergunta;

/**
 * Class Resposta
 * @package ECidade\Configuracao\Formulario\Model
 */
class Resposta {

  /**
   * @var \ECidade\Configuracao\Formulario\Model\Formulario
   */
  private $formulario;

  /**
   * Data de Preenchimento
   * @var \DBDate
   */
  private $data;

  /**
   * Codigo da Resposta
   * @var integer
   */
  private $codigo;

  /**
   * Valores de Respostas da pergunta
   * @var \ECidade\Configuracao\Formulario\Resposta\Model\Valor[]
   */
  private $respostas = array();

  /**
   * @return \ECidade\Configuracao\Formulario\Model\Formulario
   */
  public function getFormulario() {

    return $this->formulario;
  }

  /**
   * @param \ECidade\Configuracao\Formulario\Model\Formulario $formulario
   */
  public function setFormulario($formulario) {

    $this->formulario = $formulario;
  }

  /**
   * @return \DBDate
   */
  public function getData() {

    return $this->data;
  }

  /**
   * @param \DBDate $data
   */
  public function setData($data) {

    $this->data = $data;
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
   * Adiciona uma resposta ao grupo.
   * @param \ECidade\Configuracao\Formulario\Model\Pergunta $pergunta
   * @param                                                 $valor
   * @throws \ParameterException
   */
  public function adicionarRespostaParaPergunta(Pergunta $pergunta, $valor) {
    
    $resposta = $this->getValorDaPergunta($pergunta);
    $lAdicionarResposta = false;
    if (empty($resposta)) {
     
      $resposta = new Valor();
      $resposta->setPergunta($pergunta);
      $lAdicionarResposta = true;
    }
    $resposta->setValor($valor);
    $opcoesDaPergunta  = $pergunta->getOpcoes();
    
    switch ($pergunta->getTipoResposta()) {
      
      case \AvaliacaoPergunta::TIPO_RESPOSTA_DISSERTATIVA:
        
        $resposta->setOpcao($opcoesDaPergunta[0]);       
        break;
        
      case \AvaliacaoPergunta::TIPO_RESPOSTA_OBJETIVA:
        
        foreach ($opcoesDaPergunta as $opcao) {
          if ($opcao->getValorOpcao() == $valor || $opcao->getCodigo() == $valor) {
            $resposta->setOpcao($opcao);
            break;
          }
        }
        if ($resposta->getOpcao() == '') {
          
          $sMensagem = "Não foi encontrado nenhuma opção de resposta para a pergunta {$pergunta->getDescricao()} com o valor '{$valor}'.\n ";
          $sMensagem .= "Verifique a configuração da pergunta, ou a consulta da carga do formulário {$this->formulario->getNome()}.";
          throw new \ParameterException($sMensagem);
        }
        break;
    }
    
    if ($lAdicionarResposta) {
      $this->respostas[] = $resposta;
    }
  }

  /**
   * @return \ECidade\Configuracao\Formulario\Resposta\Model\Valor[]
   */
  public function getRespostas() {
    
    if (empty($this->respostas)) {
      $this->respostas = \ECidade\Configuracao\Formulario\Resposta\Repository\Resposta::getRespostasDaResposta($this);
    } 
      
    return $this->respostas;
  }

  /**
   * @param \ECidade\Configuracao\Formulario\Model\Pergunta $pergunta
   * @return \ECidade\Configuracao\Formulario\Resposta\Model\Valor|null
   */
  private function getValorDaPergunta(Pergunta $pergunta) {
    
    foreach ($this->getRespostas() as $resposta) {
      if ($resposta->getPergunta()->getCodigo() == $pergunta->getCodigo()) {
        return $resposta ;
      }
    }
    return null;
  }
  
}