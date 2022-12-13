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
namespace ECidade\Configuracao\Formulario\Processamento;
use ECidade\Configuracao\Formulario\Model\Formulario as FormularioModel;
use ECidade\Configuracao\Formulario\Resposta\Model\Resposta;
use ECidade\Configuracao\Formulario\Resposta\Repository\Resposta as RespostaRepository;


/**
 * Class Carga
 * @package ECidade\Configuracao\Formulario\Processamento
 */
class Carga {

  /**
   * @var \ECidade\Configuracao\Formulario\Model\Formulario
   */
  private $formulario;

  /**
   * Carga constructor.
   * @param \ECidade\Configuracao\Formulario\Model\Formulario $formulario
   */
  public function __construct(FormularioModel $formulario) {
    $this->formulario = $formulario;
  }

  /**
   * executa o processamento da Carga 
   */
  public function executar() {
    
    $resource = $this->rodarConsultar();
    $iTotalLinhas = pg_num_rows($resource);
    for ($iLinha = 0; $iLinha < $iTotalLinhas; $iLinha++) {
      
      $oDadosConsulta = \db_utils::fieldsMemory($resource, $iLinha);
      $this->salvarFormulario($this->formulario, $oDadosConsulta);
    }   
    
  }

  /**
   * Prepara os dados da consulta e retorna o seu resource
   * @throws \BusinessException
   */
  private function rodarConsultar() {
    
    $consulta = $this->formulario->getCarga();
    if (empty($consulta)) {
      throw new \BusinessException("Formulario {$this->formulario->getNome()} não possui carga configurada.");
    }
    
    $rsCarga = db_query($consulta);
    if (!$rsCarga) {
      throw new \DBException("Não foi possivel rodar a carga do formulario {$this->formulario->getNome()}. Verifique o código da carga.");
    }
    return $rsCarga;
  }

  /**
   * Adiciona as Respostas para a pergunta 
   * @param \ECidade\Configuracao\Formulario\Model\Formulario $formulario
   * @param                                                   $oDadosConsulta
   */
  private function salvarFormulario(FormularioModel $formulario, $oDadosConsulta) {
    
    $oResposta = $this->pesquisarRespostaDoFormularioComOsCamposChave($oDadosConsulta);
    if (empty($oResposta)) {
      
      $oResposta = new Resposta();
      $oResposta->setFormulario($formulario);
      $oResposta->setData(new \DBDate(date('Y-m-d'))); 
    }    
    foreach ($formulario->getPerguntas() as $pergunta) {      
      
      if ($pergunta->getCampoCarga() != '' && isset($oDadosConsulta->{$pergunta->getCampoCarga()})) {
        $oResposta->adicionarRespostaParaPergunta($pergunta, $oDadosConsulta->{$pergunta->getCampoCarga()});
      }
    }
    
    /**
     * Salvar os dados
     */
    RespostaRepository::persist($oResposta);    
  }

  /**
   * @param $dados
   * @return Resposta|null
   * @throws \Exception
   */
  protected function pesquisarRespostaDoFormularioComOsCamposChave($dados) {
    
    $aPerguntasChaves = $this->formulario->getPerguntasIdentificadoras();
    
    if (!empty($aPerguntasChaves) ) {
      
      $aCampos = array();
      foreach ($aPerguntasChaves as $pergunta) {
        
        if ($pergunta->getCampoCarga() != '' && isset($dados->{$pergunta->getCampoCarga()})) {
          $aCampos[] = array("pergunta" => $pergunta, "resposta" => $dados->{$pergunta->getCampoCarga()});
        }
      }
      if (count($aCampos) == 0) {
        return;
      }
      $oResposta = RespostaRepository::getPorFormularioECampos($this->formulario, $aCampos);
      if (count($oResposta) > 1) {             
        throw new \Exception("Foram encontrados mais de uma resposta para o formulário {$this->formulario->getNome()}.");
      }      
      if (count($oResposta) == 1) {
        return $oResposta[0];
      }
    }
    return null;    
  }
}