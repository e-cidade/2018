<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\CobrancaRegistrada;

class Registro {

  /**
   * @var integer
   */
  private $iSequencialRegistro;

  /**
   * @var string
   */
  private $sNumeroDocumento;

  /**
   * @var string
   */
  private $sNossoNumero;

  /**
   * @var \DBDate
   */
  private $oDataVencimento;

  /**
   * @var float
   */
  private $nValor;

  /**
   * @var \DBDate
   */
  private $oDataEmissao;

  /**
   * @var integer
   */
  private $iCodigoJuros;

  /**
   * @var \DBDate
   */
  private $oDataJuros;

  /**
   * @var float
   */
  private $nTaxaJuros;

  /**
   * @var integer
   */
  private $iCodigoDesconto;

  /**
   * @var \DBDate
   */
  private $oDataDesconto;

  /**
   * @var float
   */
  private $nValorDesconto;

  /**
   * @var integer
   */
  private $iCodigoMoeda = 9;

  /**
   * @var \CgmBase
   */
  private $oCgm;

  /**
   * @return integer
   */
  public function getSequencialRegistro() {
    return $this->iSequencialRegistro;
  }

  /**
   * @return integer
   */
  public function setSequencialRegistro($iSequencialRegistro) {
    $this->iSequencialRegistro = $iSequencialRegistro;
  }

  /**
   * @return string
   */
  public function getNumeroDocumento() {
    return $this->sNumeroDocumento;
  }

  /**
   * @param string $sNumeroDocumento
   */
  public function setNumeroDocumento($sNumeroDocumento) {
    $this->sNumeroDocumento = $sNumeroDocumento;
  }

  /**
   * @return string
   */
  public function getNossoNumero() {
    return $this->sNossoNumero;
  }

  /**
   * @param string $sNossoNumero
   */
  public function setNossoNumero($sNossoNumero) {
    $this->sNossoNumero = $sNossoNumero;
  }

  /**
   * @return DBDate
   */
  public function getDataVencimento() {
    return $this->oDataVencimento;
  }

  /**
   * @param DBDate oDataVencimento
   */
  public function setDataVencimento(\DBDate $oDataVencimento) {
    $this->oDataVencimento = $oDataVencimento;
  }
  /**
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * @param float nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }
  /**
   * @return DBDate
   */
  public function getDataEmissao() {
    return $this->oDataEmissao;
  }

  /**
   * @param DBDate oDataEmissao
   */
  public function setDataEmissao(\DBDate $oDataEmissao) {
    $this->oDataEmissao = $oDataEmissao;
  }

  /**
   * @return integer
   */
  public function getCodigoJuros() {
    return $this->iCodigoJuros;
  }

  /**
   * @param integer iCodigoJuros
   */
  public function setCodigoJuros($iCodigoJuros) {
    $this->iCodigoJuros = $iCodigoJuros;
  }
  /**
   * @return \DBDate
   */
  public function getDataJuros() {
    return $this->oDataJuros;
  }

  /**
   * @param \DBDate oDataJuros
   */
  public function setDataJuros(\DBDate $oDataJuros) {
    $this->oDataJuros = $oDataJuros;
  }
  /**
   * @return float
   */
  public function getTaxaJuros() {
    return $this->nTaxaJuros;
  }

  /**
   * @param float nTaxaJuros
   */
  public function setTaxaJuros($nTaxaJuros) {
    $this->nTaxaJuros = $nTaxaJuros;
  }
  /**
   * @return integer
   */
  public function getCodigoDesconto() {
    return $this->iCodigoDesconto;
  }

  /**
   * @param integer iCodigoDesconto
   */
  public function setCodigoDesconto($iCodigoDesconto) {
    $this->iCodigoDesconto = $iCodigoDesconto;
  }
  /**
   * @return \DBDate
   */
  public function getDataDesconto() {
    return $this->oDataDesconto;
  }

  /**
   * @param \DBDate oDataDesconto
   */
  public function setDataDesconto(\DBDate $oDataDesconto) {
    $this->oDataDesconto = $oDataDesconto;
  }
  /**
   * @return float
   */
  public function getValorDesconto() {
    return $this->nValorDesconto;
  }

  /**
   * @param float nValorDesconto
   */
  public function setValorDesconto($nValorDesconto) {
    $this->nValorDesconto = $nValorDesconto;
  }

  /**
   * @return integer
   */
  public function getCodigoMoeda() {
    return $this->iCodigoMoeda;
  }

  /**
   * @param integer iCodigoMoeda
   */
  public function setCodigoMoeda($iCodigoMoeda) {
    $this->iCodigoMoeda = $iCodigoMoeda;
  }

  /**
   * @return \CgmBase
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * @param \CgmBase oCgm
   */
  public function setCgm(\CgmBase $oCgm) {
    $this->oCgm = $oCgm;
  }
}
