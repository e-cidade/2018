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

class LoteLicitacao {

  /**
   *
   * @var integer
   */
  private $iCodigo;

  /**
   *
   * @var integer
   */
  private $iCodigoItem;

  /**
   *
   * @var ItemLicitacao
   */
  private $oItem;

  /**
   *
   * @var string
   */
  private $sDescricao;

  /**
   *
   * @var integer
   */
  private $iTipoJulgamento;

  /**
   *
   * @param integer $iCodigoItem Código do item da licitação
   */
  public function __construct($iCodigoItem = null) {

    if (!empty($iCodigoItem)) {

      $oDaoLicitacaoLote = new cl_liclicitemlote;
      $sWhere  = "l04_liclicitem = {$iCodigoItem}";
      $sCampos = 'l04_codigo, l04_liclicitem, l04_descricao, l20_tipojulg';
      $sSql    = $oDaoLicitacaoLote->sql_query(null, $sCampos, null, $sWhere);
      $rsLote  = db_query($sSql);
      if ($rsLote === false || pg_num_rows($rsLote) === 0) {
        throw new DBException('Não foi possível encontrar o lote.');
      }

      $oStdLote = db_utils::fieldsMemory($rsLote, 0);
      $this->iCodigo         = $oStdLote->l04_codigo;
      $this->iCodigoItem     = $oStdLote->l04_liclicitem;
      $this->sDescricao      = $oStdLote->l04_descricao;
      $this->iTipoJulgamento = $oStdLote->l20_tipojulg;
    }
  }

  /**
   * Retorna verdadeiro se o lote é gerado automaticamente
   * @return boolean
   */
  public function automatico() {

    $aTiposLoteAutomatico = array(
      licitacao::TIPO_JULGAMENTO_POR_ITEM,
      licitacao::TIPO_JULGAMENTO_GLOBAL,
    );

    return in_array($this->iTipoJulgamento, $aTiposLoteAutomatico);
  }

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return integer
   */
  public function getCodigoItem() {
    return $this->iCodigoItem;
  }

  /**
   * @param integer $iCodigoItem
   */
  public function setCodigoItem($iCodigoItem) {
    $this->iCodigoItem = $iCodigoItem;
  }

  /**
   * @return ItemLicitacao
   */
  public function getItem() {

    if ($this->oItem === null && $this->iCodigoItem !== null) {
      $this->oItem = new ItemLicitacao($this->iCodigoItem);
    }

    return $this->oItem;
  }

  /**
   * @param ItemLicitacao $oItem
   */
  public function setItem(ItemLicitacao $oItem) {

    $this->oItem = $oItem;
    $this->iCodigoItem = $oItem->getCodigo();
  }

  /**
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Salva o lote
   *
   * @return integer Código do lote
   */
  public function salvar() {

    $oDaoLicitacaoLote = new cl_liclicitemlote;
    $oDaoLicitacaoLote->l04_codigo     = $this->iCodigo;
    $oDaoLicitacaoLote->l04_descricao  = $this->sDescricao;
    $oDaoLicitacaoLote->l04_liclicitem = $this->iCodigoItem;

    if (empty($this->iCodigo)) {

      $oDaoLicitacaoLote->incluir(null);
      $this->iCodigo = $oDaoLicitacaoLote->l04_codigo;
    } else {
      $oDaoLicitacaoLote->alterar($this->iCodigo);
    }

    if ($oDaoLicitacaoLote->erro_status == '0') {
      throw new DBException('Não foi possível salvar o lote.');
    }

    return $this->iCodigo;
  }

}