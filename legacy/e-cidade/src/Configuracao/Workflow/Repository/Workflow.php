<?php

namespace ECidade\Configuracao\Workflow\Repository;

use ECidade\Configuracao\Workflow\Atividade;
use ECidade\Configuracao\Workflow\Workflow as WorkflowModel;

class Workflow {
  
  protected static $itens = array();

  /**
   * @param $codigo
   * @return \ECidade\Configuracao\Workflow\Workflow
   * @throws \BusinessException
   */
  public function getById($codigo) {
    
    if (empty(self::$itens[$codigo])) {
     
      $oDao = new \cl_workflow();
      $sSqlWorkFlow = $oDao->sql_query_tipoproc($codigo);
      $rsDadosWorkflow = db_query($sSqlWorkFlow);
      if (!$rsDadosWorkflow || pg_num_rows($rsDadosWorkflow) == 0) {        
        throw new \BusinessException('Workflow de código '.$codigo.' não cadastrado no sistema.');
      } 
      $oDados = \db_utils::fieldsMemory($rsDadosWorkflow, 0);      
      return $this->make($oDados);
    }
  }

  /**
   * @param $oDados
   *
   * @return \ECidade\Configuracao\Workflow\Workflow
   */
  public function make($oDados) {
    
    $oWorkflow = new WorkflowModel();
    $oWorkflow->setCodigo($oDados->db112_sequencial);
    $oWorkflow->setNome($oDados->db112_descricao);
    $oWorkflow->setTipoProcesso($oDados->db116_tipoproc);
    return $oWorkflow;
  }

  /**
   * Retorna as ativades do workflow
   * @param \ECidade\Configuracao\Workflow\Workflow $workflow
   * @return Atividade[]
   * @throws \DBException
   */
  public function getAtividadesDoWorkflow(WorkflowModel $workflow) {
    
    $oDaoWorkFlowAtiv = new \cl_workflowativ();
    $sSqlAtividades   = $oDaoWorkFlowAtiv->sql_query_atributos(null, "*",'db114_ordem', 'db114_workflow='.$workflow->getCodigo());
    $rsAtividades     = db_query($sSqlAtividades);
    if (!$rsAtividades) {
      throw new \DBException('Erro ao pesquisar as atividades do workflow '.$workflow->getNome());
    }    
    
    $atividades = \db_utils::makeCollectionFromRecord($rsAtividades, function($dados) {
        
      $atividade = new Atividade();
      $atividade->setCodigo($dados->db114_sequencial);
      $atividade->setNome($dados->db114_descricao);
      $atividade->setOrdem($dados->db114_ordem);      
      $atividade->setDepartamento(\DBDepartamentoRepository::getDBDepartamentoByCodigo($dados->p53_coddepto));      
      $atividade->setGrupoAtributos($dados->db117_db_cadattdinamico == null?'':$dados->db117_db_cadattdinamico);
      return $atividade;
    }); 
    return $atividades;
  }
}