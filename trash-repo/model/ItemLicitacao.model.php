<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


class ItemLicitacao {
  
  protected $iCodigo;
  protected $iProcessoCompras;
  protected $iOrdem;
  protected $oItemSolicitacao;
  protected $iItemProcessoCompras;
  
  public function __construct($iCodigo=null) {
    
    if (!empty($iCodigo)) {
      
      $oDaoLiclicitem = new cl_liclicitem;
      $sSqlDadosItem  = $oDaoLiclicitem->sql_query($iCodigo);
      $rsDadosItem    = $oDaoLiclicitem->sql_record($sSqlDadosItem);
      if ($oDaoLiclicitem->numrows  == 1) {
        
        $oDadosItem = db_utils::fieldsMemory($rsDadosItem, 0);
        $this->setCodigo($oDadosItem->l21_codigo);
        $this->oItemSolicitacao = new itemSolicitacao($oDadosItem->pc81_solicitem);
        $this->setItemProcessoCompras($oDadosItem->pc81_codprocitem);
        $this->iOrdem               = $oDadosItem->l21_ordem;
        $this->iProcessoCompras     = $oDadosItem->pc81_codproc;
        $this->iItemProcessoCompras = $oDadosItem->pc81_codprocitem;
        unset($oDadosItem);
        unset($oDaoLiclicitem);
      }
    }
  }
  
  /**
   * Exclui os itens de uma licitaзгo.
   * @param  integer $iItemLicitacao
   * @throws Exception
   */
  public function remover($iItemLicitacao) {
    
    if (empty($iItemLicitacao)) {
      throw new Exception("Cуdigo do item da licitaзгo nгo informado.");
    }
    $oDaoLiclicitem     = db_utils::getDao("liclicitem");
    $oDaoLiclicitemLote = db_utils::getDao("liclicitemlote");
    $oDaoLiclicitemAnu  = db_utils::getDao("liclicitemanu");

    $oDaoLiclicitemLote->excluir(null, "l04_liclicitem = {$iItemLicitacao}");
    $oDaoLiclicitemAnu->excluir(null, "l07_liclicitem = {$iItemLicitacao}");
    $oDaoLiclicitem->excluir($iItemLicitacao);

    if ($oDaoLiclicitem->erro_status == "0" || $oDaoLiclicitemLote->erro_status == "0") {
      throw new Exception("Nгo foi possнvel excluir os itens da licitaзгo.");
    }
    return true;
  }
  
  
  /**
   * Setter Codigo Item
   * @param integer $iCodigo
   */
  protected function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }
  
  /**
   * Getter Codigo Item
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  public function setItemProcessoCompras($iItemProcessoCompras) {
    
    if ($this->oItemSolicitacao == null) {
      
      $oDaoPcProcitem    = new cl_pcprocitem;
      $sSqlDadosProcesso = $oDaoPcProcitem->sql_query_file($iItemProcessoCompras); 
      $rsDadosProcesso   = $oDaoPcProcitem->sql_record($sSqlDadosProcesso);
      if ($oDaoPcProcitem->numrows == 1) {
        
        $oDadosProcesso             = db_utils::fieldsMemory($rsDadosProcesso, 0);
        $this->iProcessoCompras     = $oDadosProcesso->pc81_codproc;
        $this->iItemProcessoCompras = $oDadosProcesso->pc81_codprocitem;
        unset($oDadosProcesso);
        unset($oDaoPcProcitem);
      }
    }
  }
  
  public function setProcessoCompra($iProcessoCompras) {
    $this->iProcessoCompras = $iProcessoCompras;
  }
  
  public function getProcessoCompra() {
    return $this->iProcessoCompras;
  }
  
  public function getItemSolicitacao() {
    return $this->oItemSolicitacao;
  }
  public function getItemProcessoCompras() {
    return $this->iItemProcessoCompras;
  }
}
?>