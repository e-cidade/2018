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
 * Classe para controle de itens de uma nota de liquidacao
 * @author Iuri Guntchnigg
 * @author Matheus Felini
 */
final class NotaLiquidacaoItem {

  /**
   * Codigo da nota
   * @var integer
   */
  private $iCodigoNota;

  /**
   * Material da nota
   * @var MaterialCompras
   */
  private $oItem;

  /**
   * Quantidade do item
   * @var float
   */
  private $nQuantidade;

  /**
   * Valor total do item
   * @var float
   */
  private $nValorTotal = 0;

  /**
   * Valor total liquidado do item
   * @var float
   */
  private $nValorLiquidado = 0;

  /**
   * Método construtor da classe
   * @param integer $iCodigoNota Código da Nota
   */
  public function __construct($iCodigoNota = null) {

    $this->iCodigoNota = $iCodigoNota;
    if (!empty($iCodigoNota)) {

      $oDaoEmpnotaItem = new cl_empnotaitem();
      $sSqlDadosNota   = $oDaoEmpnotaItem->sql_query_ordemCompra($iCodigoNota);
      $rsDadosNota     = $oDaoEmpnotaItem->sql_record($sSqlDadosNota);
      if ($oDaoEmpnotaItem->numrows > 0) {

        $oDadoNotaItem = db_utils::fieldsMemory($rsDadosNota, 0);
        $this->setItem(new MaterialCompras($oDadoNotaItem->e62_item));
        $this->setQuantidade($oDadoNotaItem->e72_qtd);
        $this->setValorLiquidado($oDadoNotaItem->e72_vlrliq);
        $this->setValorTotal($oDadoNotaItem->e72_valor);
        unset($oDadoNotaItem);
      }
    }
  }

  /**
   * Retorna o codigo do item
   * @return integer o Codigo do item
   */
  public final function getCodigoNota() {
    return $this->iCodigoNota;
  }

  /**
   * Retorna o material que foi comprado
   * @return MaterialCompras
   */
  public final function getItem() {
    return $this->oItem;
  }

  /**
   * Define o material comprado
   * @param $oItem
   */
  public final function setItem($oItem) {
    $this->oItem = $oItem;
  }

  /**
   * Retorna a quantidade comprada do item
   * @return float
   */
  public final function getQuantidade() {
    return $this->nQuantidade;
  }

  /**
   * Define a quantidade comprada do item
   * @param $nQuantidade
   */
  public final function setQuantidade($nQuantidade) {
    $this->nQuantidade = $nQuantidade;
  }

  /**
   * Retorna o valor total do item
   * @return float
   */
  public final function getValorTotal() {
    return $this->nValorTotal;
  }

  /**
   * Define o valor total do item
   * @param $nValorTotal
   */
  public final function setValorTotal($nValorTotal) {
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * Retorna o valor liquidado do item
   * @return float
   */
  public final function getValorLiquidado() {
    return $this->nValorLiquidado;
  }

  /**
   * Define o valor liquidado
   * @param $nValorLiquidado
   */
  public final function setValorLiquidado($nValorLiquidado) {
    $this->nValorLiquidado = $nValorLiquidado;
  }

  /**
   * Retorna os Bens vinculados ao item da nota
   * Retorna os bens que foram tombados através da nota fiscal;
   * @return Bem[] Colecao de bens vinculadaos
   */
  public function getBensVinculados() {

    $aBensVinculados     = array();
    $oDaoBensEmpNotaItem = new cl_bensempnotaitem();
    $sWhereItem          = "e136_empnotaitem = {$this->getCodigoNota()}";
    $sSqlBuscaBensNota   = $oDaoBensEmpNotaItem->sql_query_file(null, "e136_bens", null, $sWhereItem);
    $rsBuscaBensNota     = $oDaoBensEmpNotaItem->sql_record($sSqlBuscaBensNota);

    if ($oDaoBensEmpNotaItem->numrows > 0) {

      for ($iRowBem = 0; $iRowBem < $oDaoBensEmpNotaItem->numrows; $iRowBem++) {

        $iCodigoBem = db_utils::fieldsMemory($rsBuscaBensNota, $iRowBem)->e136_bens;
        $oBem       = new Bem($iCodigoBem);

        $aBensVinculados[] = $oBem;
      }
    }
    return $aBensVinculados;
  }
}