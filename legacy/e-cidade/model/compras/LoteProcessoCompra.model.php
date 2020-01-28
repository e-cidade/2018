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

/**
 * Classe representa um lote do processo de compra 
 * @author $Author: dbmarcos $
 * @version $Revision: 1.3 $
 */
class LoteProcessoCompra {
 
  const ARQUIVO_MENSAGEM = 'patrimonial.compras.LoteProcessoCompra.';
  
  /**
   * Sequencial do lote
   * 
   * @access private
   * @var Integer
   */
  private $iCodigo;
  
  /**
   * Nome do lote
   * 
   * @access private
   * @var String 
   */
  private $sNome;  
  
  /**
   * Código do processo de compras
   * 
   * @access private
   * @var Integer
   */
  private $iCodigoProcessoCompras = null;
  
  /**
   * Objeto Processo de compras
   * 
   * @access private
   * @var ProcessoCompras
   */
  private $oProcessoCompra;
  
  /**
   * Itens que fazem parte do processo de compras
   * 
   * @access private
   * @var ItemProcessoCompra[]
   */
  private $aItens = array();
  
  /**
   * Construtor da classe
   * @param Integer $iCodigo
   * @return boolean
   * @throws BusinessException
   */
  function __construct($iCodigo = null) {
    
    if (empty($iCodigo)) {
      return;
    }
    
    $oDaoProcessoCompraLote = new cl_processocompralote();
    
    $sQueryLote             = $oDaoProcessoCompraLote->sql_query_file($iCodigo);
    $rsLote                 = $oDaoProcessoCompraLote->sql_record($sQueryLote);

    if (!$rsLote || $oDaoProcessoCompraLote->numrows == 0) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."lote_nao_encontrado"));
    }
    
    $oDadosLote = db_utils::fieldsMemory($rsLote, 0);
    
    $this->setCodigo($iCodigo);
    $this->setNome($oDadosLote->pc68_nome);
    $this->setCodigoProcessoCompras($oDadosLote->pc68_pcproc);
    
    return;
  }
  
  /**
   * Seta o sequencial do lote
   * 
   * @param Integer $iCodigo
   * @access private
   */
  private function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Seta o sequencial do processo de compras
   * 
   * @param Integer $iCodigoProcessoCompras
   * @acess private
   */
  private function setCodigoProcessoCompras($iCodigoProcessoCompras) {
    $this->iCodigoProcessoCompras = $iCodigoProcessoCompras;
  }
  
  /**
   * Seta o nome do lote
   * 
   * @param String $sNome
   * @access public
   */
  public function setNome($sNome) {
    $this->sNome = $sNome;
  }
  
  /**
   * Seta o processa de compras do lote
   * 
   * @param ProcessoCompras $oProcessoCompra
   * @acess public
   */
  public function setProcessoCompra(ProcessoCompras $oProcessoCompra) {
    
    $this->oProcessoCompra        = $oProcessoCompra;
    $this->iCodigoProcessoCompras = $oProcessoCompra->getCodigo();
  }
    
  /**
   * Retorna o sequencial do lote
   * 
   * @return Integer
   * @acess public
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o nome do lote
   * 
   * @return String
   * @access public
   */
  public function getNome() {
    return $this->sNome;
  }
  
  /**
   * Retorna o sequencial do processo de compras
   * 
   * @return Integer
   * @acess public
   */
  public function getCodigoProcessoCompras() {
    return $this->iCodigoProcessoCompras;
  }

  /**
   * Método "salva" e "altera" o lote do processode de compras
   * 
   * @access public
   * @param Integer $iCodigoProcessoCompras
   * @return boolean
   * @throws DBException
   * @throws BusinessException
   */
  public function salvar() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException(_M(self::ARQUIVO_MENSAGEM . "sem_transacao_ativa"));
    }
    
    $oDaoProcessoCompraLote                  = new cl_processocompralote();
    $oDaoProcessoCompraLote->pc68_nome       = $this->getNome();
    $oDaoProcessoCompraLote->pc68_sequencial = $this->getCodigo();
    $oDaoProcessoCompraLote->pc68_pcproc     = $this->getCodigoProcessoCompras();
   
    if (empty($this->iCodigo)) {
     
      $oDaoProcessoCompraLote->incluir(null);
      $this->setCodigo($oDaoProcessoCompraLote->pc68_sequencial); 
    } else {
     
      $oDaoProcessoCompraLote->alterar($this->getCodigo());   
    }
   
    if ($oDaoProcessoCompraLote->erro_status == 0) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."erro_salvar_objeto"));
    }
    
    foreach ($this->aItens as $oItem) {
      
      $oDaoProcessoCompraLoteItem                          = new cl_processocompraloteitem();
      $oDaoProcessoCompraLoteItem->pc69_pcprocitem         = $oItem->getCodigo();
      $oDaoProcessoCompraLoteItem->pc69_processocompralote = $this->getCodigo();
      
      $oDaoProcessoCompraLoteItem->incluir(null);
      if ($oDaoProcessoCompraLoteItem->erro_status == 0) {
        
        $oErro           = new stdCLass();
        $oErro->erro_msg = $oDaoProcessoCompraLoteItem->erro_msg;
        throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."erro_salvar_item_lote"), $oErro);
      }
    }
    return true;
  }
  
  /**
   * Remove os dados do Lote
   * 
   * @access public
   * @throws DBException
   * @throws BusinessException
   */
  public function remover() {
    
    if (!db_utils::inTransaction()) {
      throw new DBException(_M(self::ARQUIVO_MENSAGEM . "sem_transacao_ativa"));
    }
    
    $this->removerItens();
    
    $oDaoProcessoLote = new cl_processocompralote;
    $oDaoProcessoLote->excluir($this->getCodigo());
    if ($oDaoProcessoLote->erro_status == 0) {
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."erro_remover_lote"));
    }
  }
  
  /**
   * Remove itens vinculado no lote
   * Caso seja informado o item do processo de compra, apenas aquele item será removido do lote
   * 
   * @access public
   * @param ItemProcessoCompra $oItemProcessoCompra
   * @throws DBException
   * @throws BusinessException
   */
  public function removerItens(ItemProcessoCompra $oItemProcessoCompra = null) {
 
    if (!db_utils::inTransaction()) {
      throw new DBException(_M(self::ARQUIVO_MENSAGEM . "sem_transacao_ativa"));
    }
    
    $oDaoProcessoLote = new cl_processocompraloteitem();
    $sWhere           = "pc69_processocompralote = {$this->getCodigo()}";
    if (!empty($oItemProcessoCompra)) {
      $sWhere .= " AND pc69_pcprocitem = {$oItemProcessoCompra->getCodigo()}";
    }
    
    $oDaoProcessoLote->excluir(null, $sWhere);
    if ($oDaoProcessoLote->erro_status == 0) {
      
      $oErro           = new stdClass();
      $oErro->erro_msg = $oDaoProcessoLote->erro_msg;
      throw new BusinessException(_M(self::ARQUIVO_MENSAGEM."erro_remover_lote", $oErro));
    } 
    
  }
 
  /**
   * Retorna todos os itens do Lote
   * 
   * @access public
   * @return ItemProcessoCompra[]
   */
  public function getItens() {

    if (count($this->aItens) == 0) {
    
      $oDaoProcessoItemLote = new cl_processocompraloteitem;    
      $sQueryItemLote       = $oDaoProcessoItemLote->sql_query_file(null, 
                                                                   "pc69_sequencial", 
                                                                    null, 
                                                                   "pc69_processocompralote = {$this->getCodigo()}"
                                                                   );
                                                                   
      $rsDadosItem          = $oDaoProcessoItemLote->sql_record($sQueryItemLote);     
      if ($oDaoProcessoItemLote->numrows > 0) {
         
        for ($iItem = 0; $iItem < $oDaoProcessoItemLote->numrows; $iItem++) {
          
          $iCodigoItem    = db_utils::fieldsMemory($rsDadosItem, $iItem);
          $this->aItens[] = ItemProcessoCompraRepository::getItemByCodigo($iCodigoItem);
        }
      }  
    }
    return $this->aItens;
  }

  /**
   * Adiciona um item ao lote
   * @param ItemProcessoCompra $oItemProcessoCompra
   */
  public function adicionarItem(ItemProcessoCompra $oItemProcessoCompra) {
   
    foreach ($this->getItens() as $oItemNoLote) {
      if ($oItemProcessoCompra->getCodigo() == $oItemNoLote->getCodigo()) {
        return false;
      }
    }
    $this->aItens[] = $oItemProcessoCompra;
  }
}