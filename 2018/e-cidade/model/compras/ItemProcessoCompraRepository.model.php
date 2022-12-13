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
 * Classe repository da classe ItemProcessoCompra 
 * @author $Author: dbiuri $
 * @version $Revision: 1.2 $
 */

class ItemProcessoCompraRepository {

  /**
   * Instância do repository
   * 
   * @static
   * @var ItemProcessoCompraRepository
   * @access private
   */
  private static $oInstance;
  
  /**
   * Itens instanciados
   * 
   * @var ItemProcessoCompra[]
   * @access private
   */
  private $aItens = array();
  
  /**
   * Retorna instância do repository
   *
   * @access private
   * @return ItemProcessoCompraRepository
   */
  private function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new ItemProcessoCompraRepository();
    }

    return self::$oInstance;
  }

  /**
   * Retorna item pelo código
   *
   * @param Integer $iCodigo
   * @static
   * @access public
   * @return ItemProcessoCompra
   */
  public static function getItemByCodigo($iCodigo) {
    
    if (!array_key_exists($iCodigo, ItemProcessoCompraRepository::getInstance()->aItens)) {
      
      $oDaoProcessoCompraItem        = new cl_pcprocitem();
      $sQueryDadosProcessoCompraItem = $oDaoProcessoCompraItem->sql_query_item_lote($iCodigo);
      $rsDadosProcessoCompraItem     = $oDaoProcessoCompraItem->sql_record($sQueryDadosProcessoCompraItem);
      if ($oDaoProcessoCompraItem->numrows > 0) {
         
        $oDadosProcessoCompraItem = db_utils::fieldsMemory($rsDadosProcessoCompraItem, 0);
        $oItemProcessoCompras     = new ItemProcessoCompra();
        $oItemProcessoCompras->setItemSolicitacao(new itemSolicitacao($oDadosProcessoCompraItem->pc81_solicitem));
        $oItemProcessoCompras->setCodigo($oDadosProcessoCompraItem->pc81_codprocitem);
        
        $iCodigoLote = $oDadosProcessoCompraItem->pc69_processocompralote;
        if (!empty($iCodigoLote)) {
          $oItemProcessoCompras->setLote(LoteProcessoCompraRepository::getLoteByCodigo($iCodigoLote));
        }
        
        ItemProcessoCompraRepository::getInstance()->aItens[$iCodigo] = $oItemProcessoCompras;
      }
    }

    return ItemProcessoCompraRepository::getInstance()->aItens[$iCodigo];
  }
  
}
