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
require_once ("std/DBDate.php");
/**
 * Class AcordoItemPrevisao
 */
class AcordoItemPrevisao {

  /**
   * Código sequencial
   * @var int
   */
  private $iCodigo;

  /**
   * Código do item
   * @var int
   */
  private $iCodigoItem;

  /**
   * Quantidade
   * @var numeric
   */
  private $nQuantidade;

  /**
   * Valor previsto
   * @var numeric
   */
  private $nValor;

  /**
   * Quantidade prevista para execução
   * @var numeric
   */
  private $nQuantidadePrevista;

  /**
   * Código do periodo
   * @var int
   */
  private $iCodigoPeriodo;

  /**
   * Data Inicial
   * @var DBDate
   */
  private $oDataInicial;

  /**
   * Data Final
   * @var DBDate
   */
  private $oDataFinal;

  /**
   * Valor unitário
   * @var numeric
   */
  private $nValorUnitario;


  /**
   * Constroi o objeto
   * @param $iCodigo
   * @throws Exception
   */
  public function __construct($iCodigo) {

    $oDaoItemPrevisao  = new cl_acordoitemprevisao();
    $sSqlBuscaPrevisao = $oDaoItemPrevisao->sql_query_file($iCodigo);
    $rsBuscaPrevisao   = $oDaoItemPrevisao->sql_record($sSqlBuscaPrevisao);
    if ($oDaoItemPrevisao->numrows == "0") {
      throw new Exception("Impossível carregar a previsão [{$iCodigo}].");
    }

    $oStdPrevisao              = db_utils::fieldsMemory($rsBuscaPrevisao, 0);
    $this->iCodigo             = $iCodigo;
    $this->iCodigoItem         = $oStdPrevisao->ac37_acordoitem;
    $this->iCodigoPeriodo      = $oStdPrevisao->ac37_acordoperiodo;
    $this->nQuantidade         = $oStdPrevisao->ac37_quantidade;
    $this->nValor              = $oStdPrevisao->ac37_valor;
    $this->nValorUnitario      = $oStdPrevisao->ac37_valorunitario;
    $this->nQuantidadePrevista = $oStdPrevisao->ac37_quantidadeprevista;;
    $this->oDataInicial        = new DBDate($oStdPrevisao->ac37_datainicial);
    $this->oDataFinal          = new DBDate($oStdPrevisao->ac37_datafinal);
    unset($oStdPrevisao);
  }

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigoItem
   */
  public function setCodigoItem($iCodigoItem) {
    $this->iCodigoItem = $iCodigoItem;
  }

  /**
   * @return int
   */
  public function getCodigoItem() {
    return $this->iCodigoItem;
  }

  /**
   * @param int $iCodigoPeriodo
   */
  public function setCodigoPeriodo($iCodigoPeriodo) {
    $this->iCodigoPeriodo = $iCodigoPeriodo;
  }

  /**
   * @return int
   */
  public function getCodigoPeriodo() {
    return $this->iCodigoPeriodo;
  }

  /**
   * @param numeric $nQuantidade
   */
  public function setQuantidade($nQuantidade) {
    $this->nQuantidade = $nQuantidade;
  }

  /**
   * @return numeric
   */
  public function getQuantidade() {
    return $this->nQuantidade;
  }

  /**
   * @param \numeric $nQuantidadePrevista
   */
  public function setQuantidadePrevista($nQuantidadePrevista) {
    $this->nQuantidadePrevista = $nQuantidadePrevista;
  }

  /**
   * @return numeric
   */
  public function getQuantidadePrevista() {
    return $this->nQuantidadePrevista;
  }

  /**
   * @param numeric $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * @return numeric
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * @param numeric $nValorUnitario
   */
  public function setValorUnitario($nValorUnitario) {
    $this->nValorUnitario = $nValorUnitario;
  }

  /**
   * @return numeric
   */
  public function getValorUnitario() {
    return $this->nValorUnitario;
  }

  /**
   * @param DBDate $oDataFinal
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   * @return DBDate
   */
  public function getDataFinal() {
    return $this->oDataFinal;
  }

  /**
   * @param DBDate $oDataInicial
   */
  public function setODataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   * @return DBDate
   */
  public function getDataInicial() {
    return $this->oDataInicial;
  }
}