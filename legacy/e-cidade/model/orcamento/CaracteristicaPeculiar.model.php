<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


require_once ('model/configuracao/DBEstruturaValor.model.php');

class CaracteristicaPeculiar extends DBEstruturaValor  {

  protected $iTipoClassificacao;
  protected $sSequencial;
  
  /**
   * Seta o Tipo de Classificaзгo
   *
   * @param integer $iTipoClassificacao
   * @return CaracteristicaPeculiar
   */
  function setTipoClassificacao($iTipoClassificacao) {

    $this->iTipoClassificacao = $iTipoClassificacao;
    return $this;
  }

  /**
   * Retorna o Tipo de Classificaзгo
   *
   * @return integer
   */
  function getTipoClassificacao() {
    return $this->iTipoClassificacao;
  }
  
  /**
   * Seta Sequencial
   *
   * @param string $sSequencial
   * @return CaracteristicaPeculiar
   */
  function setSequencial($sSequencial) {
    $this->sSequencial = $sSequencial;
    return $this;
  }
  
  /**
   * Retorna Sequencial
   *
   * @return string
   */
  function getSequencial() {
    return $this->sSequencial;
  }
  
  
  
  /**
   * Mйtodo Construtor do Model
   *
   * @param string $sEstrutural
   */
  function __construct($sEstrutural='') {

    if ( !empty($sEstrutural) ) {

      $oDaoCarPeculiar = db_utils::getDao("concarpeculiar");
      $sSqlCarPeculiar = $oDaoCarPeculiar->sql_query_file($sEstrutural);
      $rsDadosPeculiar = $oDaoCarPeculiar->sql_record($sSqlCarPeculiar);

      if ($oDaoCarPeculiar->numrows > 0) {

        $oDadosCarPeculiar        = db_utils::fieldsMemory($rsDadosPeculiar, 0);
        $this->sSequencial        = $sEstrutural;
        $this->sDescricao         = $oDadosCarPeculiar->c58_descr;
        $this->iTipoClassificacao = $oDadosCarPeculiar->c58_tipo;
        $this->iEstruturaValor    = $oDadosCarPeculiar->c58_db_estruturavalor;
        $this->iEstrutural        = $oDadosCarPeculiar->c58_estrutural;
        parent::__construct($this->iEstruturaValor);
        unset($oDadosCarPeculiar);
      }
    }
    $this->tipo = __CLASS__;
  }


  /**
   * Mйtodo Salvar
   * Utilizado para salvar os dados da caracteristica na classe pai e classe filha
   *
   * @return CaracteristicaPeculiar
   */
  function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new Exception('Sem transaзгo com o banco de dados. procedimento abortado');
    }
    
    parent::salvar();
    $oDaoCarPeculiar                        = db_utils::getDao("concarpeculiar");
    $oDaoCarPeculiar->c58_descr             = $this->getDescricao();
    $oDaoCarPeculiar->c58_tipo              = $this->getTipoClassificacao();
    $oDaoCarPeculiar->c58_db_estruturavalor = $this->getCodigo();
    $oDaoCarPeculiar->c58_estrutural        = $this->getEstrutural();
    $oDaoCarPeculiar->c58_sequencial        = $this->getEstrutural();

    if (empty($this->sSequencial)) {
      
      $oDaoCarPeculiar->incluir($oDaoCarPeculiar->c58_sequencial);
      $this->sSequencial = $oDaoCarPeculiar->c58_sequencial;
     
    } else {
      
      $oDaoCarPeculiar->c58_sequencial = $this->getEstrutural();
      $oDaoCarPeculiar->alterar($oDaoCarPeculiar->c58_sequencial);
    }

    if ( $oDaoCarPeculiar->erro_status == "0" ) {
      throw new Exception($oDaoCarPeculiar->erro_msg);
    }

    return $this;
  }
  

  /**
   * Mйtodo Remover
   * Este mйtodo remove os dados da tabela concarpeculiar caso a remoзгo da classe pai (DBEstruturaValor)
   * tenha como retorno 'true'.
   * 
   * @return boolean
   */
  function remover() {
    
    $lRetornoExcluir = false;
    
    if (!db_utils::inTransaction()) {
      throw new Exception('Sem transaзгo com o banco de dados. Procedimento abortado');
    }
    
    /**
     * Exclui os dados na tabela concarpeculiar
     */
    $oDaoRemCaracteristica = db_utils::getDao('concarpeculiar');
    $oDaoRemCaracteristica->excluir($this->getSequencial());
    
    if ($oDaoRemCaracteristica->numrows_excluir > 0) {
      $lRetornoExcluir = true;
    }
    
    /**
     * Caso o procedimento acima tenha ocorrido com sucesso, serб excluнdo tambйm da tabela
     * db_estruturavalor
     */
    if ( !$lRetornoExcluir ) {
      
      if (!parent::remover()) {
        $lRetornoExcluir = false;
        throw new Exception("Nгo foi possнvel remover os dados em db_estruturavalor. Procedimento abortado.");
      }
    }
    return $lRetornoExcluir;
  }
  
  
  function __destruct() {

  }
  
  static public function getCodigoByEstrutura($iCodigoEstrutura) {
    
    $sCodigoEstrutural  = null;
    $oDaoConCarPeculiar = db_utils::getDao("concarpeculiar");
    $sSqlEstrutural     = $oDaoConCarPeculiar->sql_query_file(null, 
                                                            'c58_estrutural',
                                                             null,
                                                             "c58_db_estruturavalor={$iCodigoEstrutura}"
                                                             );
                                                             
    $rsEstrutural  = $oDaoConCarPeculiar->sql_record($sSqlEstrutural);
    if ($oDaoConCarPeculiar->numrows > 0) {
      $sCodigoEstrutural = db_utils::fieldsMemory($rsEstrutural, 0)->c58_estrutural;                                                             
    }
    return $sCodigoEstrutural;
  }


}


?>