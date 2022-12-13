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
 * Classe representa o item do processo de compra
 * @author $Author: dbigor.cemim $
 * @version $Revision: 1.5 $
 */
class ItemProcessoCompra {

  /**
   * Código do item do processo de compra
   * @var integer
   */
  private $iCodigo;

  /**
   * Item da solicitação do processo de compra
   * @var itemSolicitacao
   */
  private $oItemSolicitacao;

  /**
   * Lote do processo de compra
   * @var LoteProcessoCompra
   */
  private $oLoteProcessoCompra;

  /**
   * Código do Processo de Compras
   * @var integer
   */
  private $iCodigoProcesso;

  /**
   * Construtor da classe
   */
  function __construct() {

  }

  /**
   * Retorna o código do item do processo de compra
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o objeto itemSolicitacao
   * @return itemSolicitacao
   */
  public function getItemSolicitacao() {
    return $this->oItemSolicitacao;
  }

  /**
   * Retorna o objeto LoteProcessoCompra
   * @return LoteProcessoCompra
   */
  public function getLote() {
    return $this->oLoteProcessoCompra;
  }

  /**
   * Seta o código do item do processo de compra
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Seta objeto itemSolicitacao
   * @param itemSolicitacao $oItemSolicitacao
   */
  public function setItemSolicitacao(itemSolicitacao $oItemSolicitacao) {
    $this->oItemSolicitacao = $oItemSolicitacao;
  }

  /**
   * Seta o objeto LoteProcessaCompra
   * @param LoteProcessoCompra $oLoteProcessoCompra
   */
  public function setLote(LoteProcessoCompra $oLoteProcessoCompra) {
    $this->oLoteProcessoCompra = $oLoteProcessoCompra;
  }

  /**
   * Seta o código do processo de compras
   * @param integer $iCodigoProcesso
   */
  public function setCodigoProcesso($iCodigoProcesso) {
    $this->iCodigoProcesso = $iCodigoProcesso;
  }

  /**
   * Salva o item do processo de compras
   * @return integer Código do item salvo
   */
  public function salvar() {

    $iCodigoItemSolicitacao = null;
    if ($this->oItemSolicitacao) {
      $iCodigoItemSolicitacao = $this->oItemSolicitacao->getCodigoItemSolicitacao();
    }

    $oDaoItemProcesso = new cl_pcprocitem;
    $oDaoItemProcesso->pc81_codprocitem = $this->iCodigo;
    $oDaoItemProcesso->pc81_codproc     = $this->iCodigoProcesso;
    $oDaoItemProcesso->pc81_solicitem   = $iCodigoItemSolicitacao;

    if ($this->iCodigo === null) {

      $oDaoItemProcesso->incluir(null);
      $this->iCodigo = $oDaoItemProcesso->pc81_codprocitem;
    } else {
      $oDaoItemProcesso->alterar($this->iCodigo);
    }

    if ($oDaoItemProcesso->erro_status == '0') {
      throw new DBException('Não foi possível salvar o item do processo de compras.');
    }

    return $this->iCodigo;
  }

  /**
   * Apaga o item do processo de compras
   * @return boolean
   * @throws DBException
   * @throws ParameterException
   */
  public function excluir() {

    if (empty($this->iCodigo)) {
      throw new ParameterException('Código do item do processo de compras não informado.');
    }

    $oDaoItemProcesso = new cl_pcprocitem;
    $oDaoItemProcesso->excluir($this->iCodigo);
    if ($oDaoItemProcesso->erro_status == '0') {
      throw new DBException('Não foi possível excluir o item do processo de compras.');
    }

    return true;
  }
}