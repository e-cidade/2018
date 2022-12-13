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


require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));

/**
 * Class LancamentoAuxiliarEncerramentoExercicio
 */
class LancamentoAuxiliarEncerramentoExercicio extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {


  /**
   * Complemento para o lan�amento cont�bil
   * @var string
   */
  protected $sComplemento;

  /**
   * Dados da tabela conhist
   * @var integer
   */
  private $iHistorico;

  /**
   * Empenho com restos a liquidar
   * @var EmpenhoFinanceiro
   */
  private $oEmpenho;

  /**
   *  Conta que deve ser
   * @var MovimentacaoContabil
   */
  private $oMovimentacao;


  /**
   * Conta de Referencia para o encerramento do documento 1010
   */
  private $iContaReferencia;

  /**
   * @see ILancamentoAuxiliar::setHistorico()
   */
  public function setHistorico($iHistorico) {
    $this->iHistorico = $iHistorico;
  }

  /**
   * @see ILancamentoAuxiliar::getHistorico()
   */
  public function getHistorico() {
    return $this->iHistorico;
  }

  /**
   * @see ILancamentoAuxiliar::setValorTotal()
   */
  public function setValorTotal($nValorTotal) {
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * @see ILancamentoAuxiliar::getValorTotal()
   */
  public function getValorTotal() {
    return $this->nValorTotal;
  }

  /**
   * Executa os lancamentos auxiliares
   * @param int  $iCodigoLancamento
   * @param date $dtLancamento
   * @return bool
   * @throws BusinessException
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento)  {

    $this->setCodigoLancamento($iCodigoLancamento);
    $this->setDataLancamento($dtLancamento);
    if ($this->getEmpenho() instanceof EmpenhoFinanceiro && $this->getEmpenho()->getNumero() != "") {

      $this->setNumeroEmpenho($this->getEmpenho()->getNumero());
      $this->salvarVinculoEmpenho();
    }

    parent::salvarVinculoComplemento();
    return true;
  }

  /**
   * Define o empenho
   * @param EmpenhoFinanceiro $oEmpenho
   */
  public function setEmpenho (EmpenhoFinanceiro $oEmpenho) {
    $this->oEmpenho = $oEmpenho;
  }

  /**
   * Retorna o empenho
   * @return EmpenhoFinanceiro
   */
  public function getEmpenho () {
   return  $this->oEmpenho;
  }

  /**
   * Retorna a movimentacao Contabil
   * @return MovimentacaoContabil
   */
  public function getMovimentacaoContabil() {
    return $this->oMovimentacao;
  }

  /**
   * @param MovimentacaoContabil $oMovimentacao
   */
  public function setMovimentacaoContabil(MovimentacaoContabil $oMovimentacao) {
    $this->oMovimentacao = $oMovimentacao;
  }

  /**
   * @return mixed
   */
  public function getContaReferencia() {
    return $this->iContaReferencia;
  }

  /**
   * @param mixed $iContaReferencia
   */
  public function setContaReferencia($iContaReferencia) {
    $this->iContaReferencia = $iContaReferencia;
  }


}