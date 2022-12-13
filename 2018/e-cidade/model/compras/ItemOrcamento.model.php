<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2014 DBSeller Servi�os de Inform�tica Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa � software livre; voc� pode redistribu�-lo e/ou
 *   modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a vers�o 2 da
 *   Licen�a como (a seu crit�rio) qualquer vers�o mais nova.
 *   Este programa e distribu�do na expectativa de ser �til, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia impl�cita de
 *   COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM
 *   PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais
 *   detalhes.
 *   Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU
 *   junto com este programa; se n�o, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   C�pia da licen�a no diret�rio licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

/**
 * Classe VO representa um Item do Or�amento de Compras
 * Class ItemOrcamento
 * @author $Author: dbmatheus.felini $
 * @version $Revision: 1.6 $
 */

class ItemOrcamento {

  /**
   * C�digo do item do or�amento
   *
   * @access private
   * @var Integer
   */
  private $iCodigo;

  /**
   * Objeto item da solicita��o
   * @access private
   * @var itemSolicitacao
   */
  private $oItemSolicitacao;


  /**
   * Defini��o do item que originou o or�amento
   *
   * @access private
   * @var itemSolicitacao|ItemProcessoCompra|ItemLicitacao
   */
  private $oItemOrigem;

  /**
   * Conjunto de cota��o deste item do or�amento
   *
   * @var CotacaoItem[]
   */
  private $aCotacoes = array();

  /**
   * Construto da classe
   *
   * @param Integer $iCodigo
   */
  public function __construct($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o sequencial
   *
   * @access public
   * @return Integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta o sequencial
   *
   * @access private
   * @param Integer $iCodigo
   */
  private function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Retorna o origem do or�amento
   *
   * @access public
   * @return ItemLicitacao|ItemProcessoCompra|itemSolicitacao
   */
  public function getItemOrigem() {
    return $this->oItemOrigem;
  }

  /**
   * Seta o origem do or�amento
   *
   * @access public
   * @param ItemLicitacao|ItemProcessoCompra|itemSolicitacao $oItemOrigem
   */
  public function setItemOrigem($oItemOrigem) {
    $this->oItemOrigem = $oItemOrigem;
  }

  /**
   * Retorna o item da Solicitacao
   *
   * @access public
   * @return itemSolicitacao
   */
  public function getItemSolicitacao() {
    return $this->oItemSolicitacao;
  }

  /**
   * Seta o item da solicitacao
   *
   * @access public
   * @param itemSolicitacao $oItemSolicitacao
   */
  public function setItemSolicitacao(itemSolicitacao $oItemSolicitacao) {
    $this->oItemSolicitacao = $oItemSolicitacao;
  }

  /**
   * Retorna o lote do item vinculado
   *
   * @todo deixar o retorno mais generico
   * @return LoteProcessoCompra|null
   */
  public function getLote(){
    return $this->getItemOrigem()->getLote();
  }

  /**
   * Retorna Todas as Cotacoes do orcamento
   * @return CotacaoItem[]
   */
   public function getCotacoes() {

     if (count($this->aCotacoes) > 0) {
       return $this->aCotacoes;
     }

     $oDaoCotacoes  = new cl_pcorcamval();
     $sQueryCotacao = $oDaoCotacoes->sql_query_fornec(null,
                                                      $this->getCodigo(),
                                                      "pc21_numcgm, pc23_quant, pc23_vlrun, pc23_percentualdesconto, pc23_bdi, pc23_encargossociais, pc23_notatecnica"
                                                      );
     $rsCotacao = $oDaoCotacoes->sql_record($sQueryCotacao);

     $iTotalCotacoes = $oDaoCotacoes->numrows;
     if ($rsCotacao || $iTotalCotacoes > 0) {

       for ($iCotacao = 0; $iCotacao < $iTotalCotacoes; $iCotacao++) {

         $oDadosCotacao = db_utils::fieldsMemory($rsCotacao, $iCotacao);
         $oCotacao      = new CotacaoItem();
         $oCotacao->setFornecedor(CgmRepository::getByCodigo($oDadosCotacao->pc21_numcgm));
         $oCotacao->setItem($this);
         $oCotacao->setValorUnitario($oDadosCotacao->pc23_vlrun);
         $oCotacao->setQuantidade($oDadosCotacao->pc23_quant);
         $oCotacao->setValorDesconto($oDadosCotacao->pc23_percentualdesconto);
         $oCotacao->setBdi($oDadosCotacao->pc23_bdi);
         $oCotacao->setEncargosSociais($oDadosCotacao->pc23_encargossociais);
         $oCotacao->setNotaTecnica($oDadosCotacao->pc23_notatecnica);

         $this->aCotacoes[] = $oCotacao;
       }
     }

     return $this->aCotacoes;
   }

  /**
   * Retorna todas as cota��es do fornecedor informado
   *
   * @param CgmBase $oFornecedor
   * @return CotacaoItem|bool
   */
   public function getCotacaoDoFornecedor(CgmBase $oFornecedor) {

     foreach($this->getCotacoes() as $oCotacaoItem) {

       if ($oCotacaoItem->getFornecedor()->getCodigo() == $oFornecedor->getCodigo()) {
         return $oCotacaoItem;
       }
     }
     return false;
   }
}