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

class AvaliacaoQuestionarioInterno {
  
  protected $iCodigoQuestionarioInterno;  
  protected $iAvaliacao;
  protected $bTransmitido;
  protected $bAtivo;
  protected $aMenu;
  protected $aAvaliacao;
  
  function __construct($iCodigoQuestionarioInterno=null){
    
    if (!empty($iCodigoQuestionarioInterno) ){
      
      $oDaoQuestionarioInterno       = db_utils::getDao("avaliacaoquestionariointerno");
      $sSqlDadosQuestionarioInterno  = $oDaoQuestionarioInterno->sql_query($iCodigoQuestionarioInterno);
      $rsDadosQuestionarioInterno    = $oDaoQuestionarioInterno->sql_record($sSqlDadosQuestionarioInterno);

      if(!$rsDadosQuestionarioInterno){

        throw new DBException("Erro ao buscar questionario interno."); 
      }

      if ($oDaoQuestionarioInterno->numrows > 0){
        
        $oDadosQuestionarioInterno        = db_utils::fieldsMemory($rsDadosQuestionarioInterno, 0);
        $this->iCodigoQuestionarioInterno = $iCodigoQuestionarioInterno;
        $this->iAvaliacao                 = $oDadosQuestionarioInterno->db170_avaliacao;        
        $this->bTransmitido               = $oDadosQuestionarioInterno->db170_transmitido;
        $this->bAtivo                     = $oDadosQuestionarioInterno->db170_ativo;
        unset($oDadosQuestionarioInterno);
      }
    } else {
      $this->setTransmitido(false);
      $this->setAtivo(true);
    }
    $this->aMenu = array();
    $this->tipo  = __CLASS__;
  }

  function getQuestionarios($iMenu=0, $iModulo, $bReturn=false){

    $this->aAvaliacao = 0;

    if(!empty($iModulo)){

      $sSql = "
        select
          db170_avaliacao as avaliacao
        from  
          avaliacaoquestionariointerno
          inner join avaliacao on 
            db170_avaliacao =  db101_sequencial
          inner join avaliacaoquestionariointernomenu on 
            db171_questionario = db170_sequencial
        where 
          db101_avaliacaotipo = 6 and
          db101_ativo = 't' and 
          db170_ativo = 't' and 
       --   db170_transmitido = 't' and 
          db171_menu   = {$iMenu} and
          db171_modulo = {$iModulo}
        group by db170_avaliacao
      ";

      $oDaoQuestionarioInterno = db_utils::getDao("avaliacaoquestionariointerno");
      $rsResult                = db_query($sSql);   

      if(!$rsResult){

        return false;
        // throw new DBException("Erro ao buscar informações de Questionários");
      }                   
      
      $aItem                   = db_utils::getCollectionByRecord($rsResult, false, false, true);
      $this->aAvaliacao        = array();
      $this->aAvaliacao        = $aItem;
    }     
    if($bReturn){

      return $this->aAvaliacao;
    }
  }

  /**
   * Retorna o código da avaliação
   * @return int iCodigoAvaliacao
   */
  public function getAvaliacao(){
    return $this->iAvaliacao;
  }

  /**
   * Seta o código da Avaliação
   * @param int $iAvaliacao retorna Object $this
   */
  public function setAvaliacao($iAvaliacao){

    $this->iAvaliacao = $iAvaliacao;
    return $this;
  }

  /**
   * Retorna o boolean se foi ou não transmitido
   * @return boolean bTransmitido
   */
  public function getTransmitido(){
    return $this->bTransmitido;
  }

  /**
   * Alias da funcao getTransmitido
   * @return boolean bTransmitido
   */
  public function isTransmitido(){

    return $this.getTransmitido();
  }

  /**
   * Seta o transmistido
   * @param boolean $bTransmitido retorna Object $this
   */
  public function setTransmitido($bTransmitido){

    $this->bTransmitido = $bTransmitido;
    return $this;
  }

  /**
   * Retorna o codigo do Questionario Interno
   *
   * @return integer
   */
  public function getCodigoQuestionarioInterno(){
    return $this->iCodigoQuestionarioInterno;
  }
  
  /**
   * Define Código do Questionario Interno
   *
   * @param integer $iCodigoQuestionarioInterno
   */
  public function setCodigoQuestionarioInterno($iCodigoQuestionarioInterno){
    
    $this->iCodigoQuestionarioInterno = $iCodigoQuestionarioInterno;
    return $this;
  }

  /**
   * Forca o campo transmitido como verdadeiro
   * @return Object $this
   */
  public function transmitidoOn(){

    $this->transmitido = true;
    return $this;
  }

  /**
   * Forca o campo transmitido como falso
   * @return Object $this
   */
  public function transmitidoOff(){

    $this->transmitido = false;
    return $this;
  }

  /**
   * Verifica se a avaliacao esta ou nao ativada
   * @return boolean bAtivo
   */
  public function getAtivo(){

    return $this->bAtivo;
  }

  /**
   * Seta o valor bAtivo
   * @param boolean $bAtivo retorna Object $this
   */
  public function setAtivo($bAtivo){

    $this->bAtivo = $bAtivo;
    return $this;
  }

  /**
   * Alias da funcao getAtivo
   * @return boolean bAtivo
   */
  public function isAtivo(){

    return $this.getAtivo();
  }

  /**
   * persiste os dados do Questionario Interno
   *
   * @return Questionario Interno
   */
  function salvar(){
  
    $oDaoQuestionarioInterno = db_utils::getDao("avaliacaoquestionariointerno");
    $oDaoQuestionarioInterno->db170_avaliacao   = $this->iAvaliacao;
    $oDaoQuestionarioInterno->db170_transmitido = $this->bTransmitido; 
    $oDaoQuestionarioInterno->db170_ativo       = $this->bAtivo;

    if (empty($this->iCodigoQuestionarioInterno)) {

      $oDaoQuestionarioInterno->incluir(null);
      $this->iCodigoQuestionarioInterno  = $oDaoQuestionarioInterno->db170_sequencial;
    } else {

      $oDaoQuestionarioInterno->db170_sequencial= $this->getCodigoQuestionarioInterno();
      $oDaoQuestionarioInterno->alterar($this->getCodigoQuestionarioInterno());
    }
    if ($oDaoQuestionarioInterno->erro_status == 0) {
      throw new Exception($oDaoQuestionarioInterno->erro_msg);
    }
    $this->resetMenus();
    return $this;
  }

  public function getMenus(){

    if(empty($this->aMenu)){

      return $this->_getMenus();
    }
    return $this->aMenu;
  }

  public function _getMenus(){

    if(!empty($this->aMenu)){

      unset($this->aMenu);
      $this->aMenu = array();
    }

    if(!empty($this->iCodigoQuestionarioInterno)){

      $sSqlMenu = "
        select 
          db171_sequencial   as sequencial,
          db171_menu         as menu,
          db171_questionario as questionario,
          db171_modulo       as modulo,
        from 
          avaliacaoquestionariointernomenu
        where 
          db171_questionario = {$this->iCodigoQuestionarioInterno}
      ";
      $rsResult = db_query($sSqlMenu);              
      
      if(!$rsResult){

        throw new DBException("Erro ao buscar informações do menu.");
      }        

      if(!$rsResult){
        return false;
      }

      // Itens 
      $aItem = db_utils::getCollectionByRecord($rsResult, false, false, true);
  
      if(!$aItem){

        return false;
      }

      foreach ($aItem as $oItem) {

        $this->aMenu[] = $oItem;
      }
    } else {
      return false;
    }
  }

  public function resetMenus(){

    $sSql = "     
      delete 
      from 
        avaliacaoquestionariointernomenu 
      where 
        db171_questionario = ". $this->iCodigoQuestionarioInterno;
    $result = db_query($sSql);

    if (!$result) {

      throw new Exception("Erro ao resetar menus");     
    }

    return $result;
  }

  function __destruct(){

  }
}
?>