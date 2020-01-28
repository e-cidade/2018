<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

/**
 * posicoes do acordo
 *@package Contratos
 */
class AcordoPosicao {
  
  /**
   * Codigo do acordo
   *
   * @var integer
   */
  protected $iAcordo;
  
  /**
   * Codigo sequencial da posicao 
   *
   * @var integer
   */
  protected $iCodigo;
  
  /**
   * Número da posição
   *
   * @var integer
   */
  protected $iNumero;
  
  /**
   * situacao da posicao
   *
   * @var integer
   */
  protected $iSituacao;
  
  /**
   * tipo da posição
   *
   * @var integer
   */
  protected $iTipo;
  
  /**
   * data do Movimento 
   *
   * @var string 
   */
  protected $dtData;
  
  /**
   * Posisao foi realizada emergencialmente
   *
   * @var bool
   */
  protected $lEmergencial;
   /**
    * itens da posição.
    *
    * @var AcordoItem collection  
    */
   protected $aItens = array();
  /**
   * 
   */
  function __construct($iCodigoPosicao = null) {
    
    if (!empty($iCodigoPosicao)) {
      $this->iCodigo = $iCodigoPosicao;
    }
  }
  /**
   * retorna o codigo do acordo
   * @return integer
   */
  public function getAcordo() {

    return $this->iAcordo;
  }
  
  /**
   * define  o co codigo do acordo
   * @param integer $iAcordo
   * @return AcordoPosicao
   */
  public function setAcordo($iAcordo) {

    $this->iAcordo = $iAcordo;
    return $this;
  }
  
  /**
   * retorna o codigo sequencial da posicao
   * @return integer
   * 
   */
  public function getCodigo() {

    return $this->iCodigo;
  }
  
  /**
   * retorna o numero da posicao
   * @return integer
   */
  public function getNumero() {

    return $this->iNumero;
  }
  
  /**
   * define o numero da posicao
   * @param integer $iNumero
   * @return AcordoPosicao
   */
  public function setNumero($iNumero) {

    $this->iNumero = $iNumero;
    return $this;
  }
  
  /**
   * retorna a situacao da posição
   * @return integer
   */
  public function getSituacao() {

    return $this->iSituacao;
  }
  
  /**
   * retorna a situação da posição
   * @param integer $iSituacao
   * @return AcordoPosicao
   */
  public function setSituacao($iSituacao) {

    $this->iSituacao = $iSituacao;
    return $this;
  }
  
  /**
   * retorna o tipo da posição
   * @return integer
   */
  public function getTipo() {

    return $this->iTipo;
  }
  
  /**
   * define o tipo da posicão
   * @param integer $iTipo
   * @return AcordoPosicao
   */
  public function setTipo($iTipo) {

    $this->iTipo = $iTipo;
    return $this;
  }
  /**
   * retorna a data da posicao
   * @return string
   */
  public function getData() {

    return $this->dtData;
  }
  
  /**
   * define a data da posição
   * @param string $dtData
   * @return AcordoPosicao
   */
  public function setData($dtData) {

    $this->dtData = $dtData;
    return $this;
  }
  
  /**
   * define se a posição foi realizada emergencialmente.
   *
   * @param bool $lEmergencial
   * @return AcordoPosicao
   */
  public function setEmergencial($lEmergencial) {
    
    if (is_bool($lEmergencial)) {
      $this->lEmergencial = $lEmergencial;
    }
    return $this;
  }
  
  /**
   * Verifica se a posição do contratado é emergencial
   *
   * @return bool
   */
  public function isEmergencial() {
    return $this->lEmergencial;
  }
  
  /**
   * @return AcordoItem
   */
  public function getItens() {
    
    if (count($this->aItens)  == 0) {
      
      $oDaoAcordoItem = db_utils::getDao("acordoitem");
      $sSqlAcordoitem = $oDaoAcordoItem->sql_query_file(null, 
                                                        "ac20_sequencial",
                                                        "ac20_ordem",
                                                        "ac20_acordoposicao={$this->getCodigo()}"
                                                       );
                                                       
      $rsItens = $oDaoAcordoItem->sql_record($sSqlAcordoitem);
      for ($i = 0; $i < $oDaoAcordoItem->numrows; $i++) {
        $this->aItens[] = (new AcordoItem(db_utils::fieldsMemory($rsItens, $i)->ac20_sequencial));
      }
      
    }
    return $this->aItens;
  }
   
  /**
   * adiciona um item atravez de um item da licitação
   *
   * @param integer $iLicitem codigo do item da licitacao
   * @return AcordoItem
   */
  public function adicionarItemDeLicitacao($iLicitem) {
    
    $oDaoLiclicitem = db_utils::getDao("liclicitem");
    $sSqlDadosItem  = $oDaoLiclicitem->sql_query_soljulg($iLicitem);
    $rsDadosItem    = $oDaoLiclicitem->sql_record($sSqlDadosItem);
    if ($oDaoLiclicitem->numrows == 1) {
      
      $oItemLicitacao = db_utils::fieldsMemory($rsDadosItem, 0);
      $oItem = new AcordoItem();
      $oItem->setMaterial(new MaterialCompras($oItemLicitacao->pc01_codmater));
      $oItem->setElemento($oItemLicitacao->pc18_codele);
      $oItem->setQuantidade($oItemLicitacao->pc23_quant);   
      $oItem->setUnidade($oItemLicitacao->pc17_unid);   
      if ($oItemLicitacao->pc17_unid == '') {
        $oItem->setUnidade(1);
      }
      $oItem->setValorUnitario($oItemLicitacao->pc23_vlrun);
      $oItem->setOrigem($oItemLicitacao->l21_codigo, 2);
      $oItem->setValorTotal($oItemLicitacao->pc23_quant*$oItemLicitacao->pc23_vlrun);
      /**
       * pesquisamos as dotacoes do item
       */
      $oDaoDotacoesItem = db_utils::getDao("pcdotac");
      $sSqlDotacoes     = $oDaoDotacoesItem->sql_query_file($oItemLicitacao->pc11_codigo);
      $rsDotacoes       = db_query($sSqlDotacoes);
      $aDotacoes        = db_utils::getColectionByRecord($rsDotacoes);
      
      foreach ($aDotacoes as $oDotacaoItem) {

        $oDotacao = new stdClass();
        $nValorTotal = ($oItemLicitacao->pc23_vlrun*$oDotacaoItem->pc13_quant);
        $nPercentualDiferenca = ($oDotacaoItem->pc13_valor*100)/$nValorTotal;
        
        if ($nPercentualDiferenca > 100) {
          $nPercentualDiferenca -= 100;
        } else {
          $nPercentualDiferenca = 100 - $nPercentualDiferenca; 
        }
        if ($nPercentualDiferenca > 100) {
          $oDotacao->valor      = $oDotacaoItem->pc13_valor+($nValorTotal*($nPercentualDiferenca/100));
        } else {
          $oDotacao->valor      = $oDotacaoItem->pc13_valor-($nValorTotal*($nPercentualDiferenca/100));
        }
        $oDotacao->ano        = $oDotacaoItem->pc13_anousu;
        $oDotacao->dotacao    = $oDotacaoItem->pc13_coddot;
        $oDotacao->quantidade = $oDotacaoItem->pc13_quant;
        $oItem->adicionarDotacoes($oDotacao);
        
      }
      $oItem->setCodigoPosicao($this->getCodigo());
      $oItem->save();
      $this->adicionarItens($oItem);
    }
  }
  
 /**
   * adiciona um item atravez de um item do processo de compras
   *
   * @param integer $iCodprocItem codigo do item do Processo
   * @return AcordoItem
   */
  public function adicionarItemDeProcesso($iCodprocItem) {
    
    $oDaoLiclicitem = db_utils::getDao("pcprocitem");
    $sSqlDadosItem  = $oDaoLiclicitem->sql_query_soljulg($iCodprocItem);
    $rsDadosItem    = $oDaoLiclicitem->sql_record($sSqlDadosItem);
    if ($oDaoLiclicitem->numrows == 1) {
      
      $oItemLicitacao = db_utils::fieldsMemory($rsDadosItem, 0);
      $oItem = new AcordoItem();
      $oItem->setMaterial(new MaterialCompras($oItemLicitacao->pc01_codmater));
      $oItem->setElemento($oItemLicitacao->pc18_codele);
      $oItem->setQuantidade($oItemLicitacao->pc23_quant);   
      $oItem->setUnidade($oItemLicitacao->pc17_unid);   
      if ($oItemLicitacao->pc17_unid == '') {
        $oItem->setUnidade(1);
      }
      $oItem->setValorUnitario($oItemLicitacao->pc23_vlrun);
      $oItem->setOrigem($oItemLicitacao->pc81_codprocitem, 1);
      $oItem->setValorTotal($oItemLicitacao->pc23_quant*$oItemLicitacao->pc23_vlrun);
      /**
       * pesquisamos as dotacoes do item
       */
      $oDaoDotacoesItem = db_utils::getDao("pcdotac");
      $sSqlDotacoes     = $oDaoDotacoesItem->sql_query_file($oItemLicitacao->pc11_codigo);
      $rsDotacoes       = db_query($sSqlDotacoes);
      $aDotacoes        = db_utils::getColectionByRecord($rsDotacoes);
      
      foreach ($aDotacoes as $oDotacaoItem) {

        $oDotacao = new stdClass();
        
        $nPercentualDiferenca = ($oDotacaoItem->pc13_valor*100)/$oItem->getValorTotal();
        if ($nPercentualDiferenca > 100) {
          $nPercentualDiferenca -= 100;
        } else {
          $nPercentualDiferenca = 100 - $nPercentualDiferenca; 
        }
        $oDotacao->valor      = $oDotacaoItem->pc13_valor+($oItem->getValorTotal()*($nPercentualDiferenca/100));
        $oDotacao->ano        = $oDotacaoItem->pc13_anousu;
        $oDotacao->dotacao    = $oDotacaoItem->pc13_coddot;
        $oDotacao->quantidade = $oDotacaoItem->pc13_quant;
        $oItem->adicionarDotacoes($oDotacao);
        
      }
      $oItem->setCodigoPosicao($this->getCodigo());
      $oItem->save();
      $this->adicionarItens($oItem);
    }
  }
  
 /**
   * Adiciona um item a posição
   * @param AcordoItem $aItens
   * @return Acordo
   */
  
  public function adicionarItens(AcordoItem $oItem) {
    
    foreach ($this->aItens as $oItemAcordo) {
      
      if ($oItemAcordo->getMaterial()->getMaterial() == $oItem->getMaterial()->getMaterial()) {
        throw new Exception("Material ({$oItemAcordo->getMaterial()->getMaterial()}) já cadastrado.");
      }
    }
    
    $this->aItens[] = $oItem;
    return $this;
  }
  
  public function save() {
    
    
    $oDaoPosicao                         = db_utils::getDao("acordoposicao");
    $oDaoPosicao->ac26_acordo            = $this->getAcordo();
    $oDaoPosicao->ac26_acordoposicaotipo = $this->getTipo();
    $oDaoPosicao->ac26_numero            = $this->getNumero();
    $oDaoPosicao->ac26_situacao          = $this->getSituacao();
    $oDaoPosicao->ac26_data              = implode("-", array_reverse(explode("/", $this->getData())));
    $oDaoPosicao->ac26_emergencial       = $this->isEmergencial()?"true":"false";
    $iCodigo = $this->getCodigo(); 
    if (empty($iCodigo)) {
      
      $oDaoPosicao->incluir(null);
      $this->iCodigo = $oDaoPosicao->ac26_sequencial;       
    } else {

      
      $oDaoPosicao->ac26_sequencial = $this->getCodigo() ;
      $oDaoPosicao->alterar($this->getCodigo());
    }
    
    if ($oDaoPosicao->erro_status == 0) {
      throw new Exception("Não foi possivel salvar posição do acordo!\nErro: {$oDaoPosicao->erro_msg}");
    }
  }
  
  public function getDotacoesItemOrigem($iCodigo, $iTipoOrigem) {
    
    $iNumRows = 0;
    if ($iTipoOrigem == 2) {
     
      $oDaoLiclicitem = db_utils::getDao("liclicitem");
      $sSqlDadosItem  = $oDaoLiclicitem->sql_query_soljulg($iCodigo);
      $rsDadosItem    = $oDaoLiclicitem->sql_record($sSqlDadosItem);
      $iNumRows       = $oDaoLiclicitem->numrows; 
    } else if ($iTipoOrigem == 1) {
      
      $oDaoLiclicitem = db_utils::getDao("pcprocitem");
      $sSqlDadosItem  = $oDaoLiclicitem->sql_query_soljulg($iCodigo);
      $rsDadosItem    = $oDaoLiclicitem->sql_record($sSqlDadosItem);
      $iNumRows       = $oDaoLiclicitem->numrows; 
    }
    $aDotacoes   = array();
    if ($iNumRows == 1) {

      $oItemOrigem      = db_utils::fieldsMemory($rsDadosItem, 0);
      $oDaoDotacoesItem = db_utils::getDao("pcdotac");
      $sSqlDotacoes     = $oDaoDotacoesItem->sql_query_file($oItemOrigem->pc11_codigo);
      $rsDotacoes       = db_query($sSqlDotacoes);
      $aDotacoesOrigem  = db_utils::getColectionByRecord($rsDotacoes);
      foreach ($aDotacoesOrigem as $oDotacaoItem) {

        $oDotacao             = new stdClass();
        $oDotacao->valor      = $oDotacaoItem->pc13_valor;
        $oDotacao->ano        = $oDotacaoItem->pc13_anousu;
        $oDotacao->dotacao    = $oDotacaoItem->pc13_coddot;
        $oDotacao->quantidade = $oDotacaoItem->pc13_quant;
        $aDotacoes[] = $oDotacao;
        
      }
    }
    $oItemOrigem->dotacoes = $aDotacoes;
    return $oItemOrigem;
  }
}

?>