<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 16/02/17
 * Time: 08:53
 */

namespace ECidade\Tributario\Grm\Repository;


use ECidade\Tributario\Grm\UnidadeGestora as UnidadeGestoraModel;
use Ecidade\Tributario\Grm\TipoRecolhimento AS TipoRecolhimentoModel;

class UnidadeGestora {

  /**
   * @return \ECidade\Tributario\Grm\UnidadeGestora[]
   * @throws \BusinessException
   */
  public function getAll() {
    
    $oDaoUnidadeGestora = new \cl_unidadegestora();
    $sSqlUnidades       = $oDaoUnidadeGestora->sql_query_file(null,"*", 'k171_nome');
    $rsUnidades         = db_query($sSqlUnidades);
    if (!$rsUnidades) {
      throw new \BusinessException("Erro ao pesquisar dados da Unidade gestora");
    } 
    $oInstancia = $this;
    $aUnidades  = \db_utils::makeCollectionFromRecord($rsUnidades, function ($oDados) use ($oInstancia) {       
       return $oInstancia->make($oDados);
    });
    return $aUnidades;
  }

  /**
   * Retorna a Unidade Gestora por Id
   * @param $codigo
   * @return \ECidade\Tributario\Grm\UnidadeGestora
   * @throws \BusinessException
   * @throws \ParameterException
   */
  public function getById($codigo) {

    if (empty($codigo)) {
      throw new \ParameterException('parâmetro $codigo não informado.');
    }
    $oDaoUnidadeGestora = new \cl_unidadegestora();
    $oDadosUnidade = $oDaoUnidadeGestora->findBydId($codigo);
    if (empty($oDadosUnidade)) {
      throw new \BusinessException("Unidade gestora {$codigo} não encontrada.");
    }
    return $this->make($oDadosUnidade);
    
  }

  /**
   * Constroi a isntancia da unidade gestora
   * @param $resource
   * @return \ECidade\Tributario\Grm\UnidadeGestora
   */
  public function make($resource) {
    
    $oUnidade = new UnidadeGestoraModel();
    $oUnidade->setCodigo($resource->k171_sequencial);
    $oUnidade->setNome($resource->k171_nome);
    $oUnidade->setDepartamento(\DBDepartamentoRepository::getDBDepartamentoByCodigo($resource->k171_departamento));
    return $oUnidade;
  }

  /**
   * @param \ECidade\Tributario\Grm\UnidadeGestora $unidadeGestora
   * @throws \BusinessException
   */
  public function persist(UnidadeGestoraModel $unidadeGestora) {    
    
    $oDaoUnidadeGestora             = new \cl_unidadegestora();
    $oDaoUnidadeGestoraRecolhimento = new \cl_unidadegestoratiporecolhimento();
    
    $oDaoUnidadeGestora->k171_sequencial   = $unidadeGestora->getCodigo();  
    $oDaoUnidadeGestora->k171_nome         = $unidadeGestora->getNome();  
    $oDaoUnidadeGestora->k171_departamento = $unidadeGestora->getDepartamento()->getCodigo();
    
    if (is_null($unidadeGestora->getCodigo())) {

      $oDaoUnidadeGestora->incluir();
      $unidadeGestora->setCodigo($oDaoUnidadeGestora->k171_sequencial);
    } else {
      $oDaoUnidadeGestora->alterar($unidadeGestora->getCodigo());      
    }
    
    if ($oDaoUnidadeGestora->erro_status == 0) {     
      throw new \BusinessException('Erro ao salvar dados da unidade gestora');
    }
    
    if (count($unidadeGestora->getRecolhimentos()) > 0) {
      
      $oDaoUnidadeGestoraRecolhimento->excluir(null, "k173_unidadegestora = {$unidadeGestora->getCodigo()}");
      if ($oDaoUnidadeGestoraRecolhimento->erro_status == 0) {
        throw new \BusinessException('Erro ao salvar dados da unidade gestora');
      }
    }    
    
    foreach ($unidadeGestora->getRecolhimentos() as $recolhimento => $oRecolhimeto) {
      
      $oDaoUnidadeGestoraRecolhimento->k173_unidadegestora   = $unidadeGestora->getCodigo();
      $oDaoUnidadeGestoraRecolhimento->k173_sequencial       = null;
      $oDaoUnidadeGestoraRecolhimento->k173_tiporecolhimento = $recolhimento;
      $oDaoUnidadeGestoraRecolhimento->k173_receita          = $oRecolhimeto->getReceita()->getCodigo();
      $oDaoUnidadeGestoraRecolhimento->incluir(null);
      if ($oDaoUnidadeGestoraRecolhimento->erro_status == 0) {
        throw new \BusinessException('Erro ao vincular o recolhimento a unidade gestora.');
      }
    }
    
  }

  /**
   * Exclui a Unidade Gestora
   * @param \ECidade\Tributario\Grm\UnidadeGestora $unidadeGestora
   * @throws \BusinessException
   */
  public function remove(UnidadeGestoraModel $unidadeGestora) {

    $oDaoUnidadeGestora             = new \cl_unidadegestora();
    
    foreach ($unidadeGestora->getRecolhimentos() as $recolhimento => $oRecolhimento) {
      $unidadeGestora->removerRecolhimento($oRecolhimento->getTipoRecolhimento());
    }
    $oDaoUnidadeGestora->excluir($unidadeGestora->getCodigo());
    if ($oDaoUnidadeGestora->erro_status == 0) {
      throw new \BusinessException('Erro ao remover a unidade gestora.');
    }
  }

  /**
   * @param \Ecidade\Tributario\Grm\TipoRecolhimento $tipoRecolhimento
   * @return array
   * @throws \DBException
   */
  public function getUnidadeGestoraTipoDeRecolhimento(TipoRecolhimentoModel $tipoRecolhimento) {
    
    $oDaoUnidadeGestora = new \cl_unidadegestoratiporecolhimento();
   
    $sSql = $oDaoUnidadeGestora->sql_query(NULL, '*', NULL, 'k173_tiporecolhimento = '.$tipoRecolhimento->getCodigo());

    $rsUnidadeGestora = db_query($sSql);
    if (!$rsUnidadeGestora) {
      throw new \DBException('Erro ao pesquisar Unidade Gestora do Tipo de Recolhimento');
    }

    $oInstancia = $this;
    $aUnidadesGestoras = \db_utils::makeCollectionFromRecord($rsUnidadeGestora, function($oDados) use ($oInstancia) {
      $oUnidadeGestora = $oInstancia->make($oDados);

      return $oUnidadeGestora;
    });
    return $aUnidadesGestoras;
  }
  
}