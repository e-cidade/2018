<?php
/**
 * E-cidade Software Publico para Gest�o Municipal
 *   Copyright (C) 2015 DBSeller Servi�os de Inform�tica Ltda
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
 * Classe para controle das execu��es dos itens dos contratos
 * Class AcordoItemExecucao
 */
class AcordoItemExecucao {

  /**
   * Codigo da execucao
   * @var integer
   */
  private $iCodigo;

  /**
   * @var AcordoItem
   */
  private $oItem;

  /**
   * Data inicial da execucao
   * @var DBDate
   */
  private $oDataInicial;

  /**
   * Data final da execu��o
   * @var DBDate
   */
  private $oDataFinal;

  /**
   * Valor da execucao
   * @var float
   */
  private $nValor;

  /**
   * quantidade da execu��o
   * @var float
   */
  private $nQuantidade;

  /**
   * Numero da Nota Fiscal
   * @var string
   */
  private $sNotaFiscal;

  /**
   * Numero Do processo
   * @var string
   */
  private $sProcesso;

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param int $codigo
   */
  public function setCodigo($codigo) {
    $this->iCodigo = $codigo;
  }

  /**
   * @return AcordoItem
   */
  public function getItem() {
    return $this->oItem;
  }

  /**
   * @param AcordoItem $oItem
   */
  public function setItem(AcordoItem $oItem) {
    $this->oItem = $oItem;
  }

  /**
   * @return DBDate
   */
  public function getDataInicial() {
    return $this->oDataInicial;
  }

  /**
   * @param DBDate $dataInicial
   */
  public function setDataInicial($dataInicial) {
    $this->oDataInicial = $dataInicial;
  }

  /**
   * @return DBDate
   */
  public function getDataFinal() {
    return $this->oDataFinal;
  }

  /**
   * @param DBDate $dataFinal
   */
  public function setDataFinal($dataFinal) {
    $this->oDataFinal = $dataFinal;
  }

  /**
   * @return float
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * @param float $valor
   */
  public function setValor($valor) {
    $this->nValor = $valor;
  }

  /**
   * @return float
   */
  public function getQuantidade() {
    return $this->nQuantidade;
  }

  /**
   * @param float $quantidade
   */
  public function setQuantidade($quantidade) {
    $this->nQuantidade = $quantidade;
  }

  /**
   * @return string
   */
  public function getNotaFiscal() {
    return $this->sNotaFiscal;
  }

  /**
   * @param string $notaFiscal
   */
  public function setNotaFiscal($notaFiscal) {
    $this->sNotaFiscal = $notaFiscal;
  }

  /**
   * @return string
   */
  public function getProcesso() {
    return $this->sProcesso;
  }

  /**
   * @param string $processo
   */
  public function setProcesso($processo) {
    $this->sProcesso = $processo;
  }

  /**
   * Persiste os dados da execuc��o
   */
  public function salvar() {

    $oDaoItemExecucao = new cl_acordoitemexecutado();
    $oDaoItemExecucao->ac29_acordoitem     = $this->getItem()->getCodigo();
    $oDaoItemExecucao->ac29_automatico     = "false";
    $oDaoItemExecucao->ac29_quantidade     = $this->getQuantidade();
    $oDaoItemExecucao->ac29_valor          = "{$this->getValor()}";
    $oDaoItemExecucao->ac29_tipo           = 2;
    $oDaoItemExecucao->ac29_numeroprocesso = $this->getProcesso();
    $oDaoItemExecucao->ac29_notafiscal     = $this->getNotaFiscal();
    $oDaoItemExecucao->ac29_observacao     = '';
    $oDaoItemExecucao->ac29_sequencial     = $this->getCodigo();
    $oDaoItemExecucao->ac29_datainicial    = $this->getDataInicial()->getDate();
    $oDaoItemExecucao->ac29_datafinal      = $this->getDataFinal()->getDate();
    if (empty($this->iCodigo)) {

      $oDaoItemExecucao->incluir(null);
      $this->setCodigo($oDaoItemExecucao->ac29_sequencial);
    } else {
      $oDaoItemExecucao->alterar($this->getCodigo());
    }

    if ($oDaoItemExecucao->erro_status == 0) {

      $sMessage = "Erro ao incluir dados da movimenta��o do item.\n{$oDaoItemExecucao->erro_msg}";
      throw new BusinessException($sMessage);
    }
  }

  /**
   * Remove a execu��o do Item do acordo
   * @return bool
   * @throws BusinessException
   * @throws ParameterException
   */
  public function remover() {

    if (empty($this->iCodigo)) {
      throw new ParameterException('O c�digo da execu��o deve ser informada!');
    }

    $oDaoItemExecucao = new cl_acordoitemexecutado();
    $oDaoItemExecucao->excluir($this->getCodigo());
    if ($oDaoItemExecucao->erro_status == 0) {

      $sMessage = "Erro ao remover movimenta��o do item.\n{$oDaoItemExecucao->erro_msg}";
      throw new BusinessException($sMessage);
    }
    return true;
  }
}