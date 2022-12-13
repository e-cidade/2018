<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\EmissaoGeral;

use \ECidade\Tributario\Arrecadacao\EmissaoGeral\EmissaoGeral;

class ParcelaUnica {

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var EmissaoGeral
   */
  private $oEmissaoGeral;

  /**
   * @var \DBDate
   */
  private $oDataOperacao;

  /**
   * @var \DBDate
   */
  private $oDataVencimento;

  /**
   * @var float
   */
  private $nPercentual;

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return EmissaoGeral
   */
  public function getEmissaoGeral() {
    return $this->oEmissaoGeral;
  }

  /**
   * @param EmissaoGeral oEmissaoGeral
   */
  public function setEmissaoGeral(EmissaoGeral $oEmissaoGeral) {
    $this->oEmissaoGeral = $oEmissaoGeral;
  }

  /**
   * @return \DBDate
   */
  public function getDataOperacao() {
    return $this->oDataOperacao;
  }

  /**
   * @param \DBDate oDataOperacao
   */
  public function setDataOperacao(\DBDate $oDataOperacao) {
    $this->oDataOperacao = $oDataOperacao;
  }

  /**
   * @return \DBDate
   */
  public function getDataVencimento() {
    return $this->oDataVencimento;
  }

  /**
   * @param \DBDate oDataVencimento
   */
  public function setDataVencimento(\DBDate $oDataVencimento) {
    $this->oDataVencimento = $oDataVencimento;
  }

  /**
   * @return float
   */
  public function getPercentual() {
    return $this->nPercentual;
  }

  /**
   * @param float nPercentual
   */
  public function setPercentual($nPercentual) {
    $this->nPercentual = $nPercentual;
  }
}
