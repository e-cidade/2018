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

require_once(modification("interfaces/ILancamentoAuxiliar.interface.php"));
require_once(modification("model/contabilidade/lancamento/LancamentoAuxiliarBase.model.php"));
/**
 * Executa os lançamentos auxiliares para uma arrecadação de receita extra-orçamentária
 * @author Matheus Felini <matheus.felini@dbseller.com.br>
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.12 $
 *
 * @todo
 * verificar a possibilidade de refatorar este model e passá-lo para dentro do LancamentoAuxiliarArrecadacaoReceita
 */
class LancamentoAuxiliarArrecadacaoReceitaExtraOrcamentaria extends LancamentoAuxiliarBase implements ILancamentoAuxiliar {

  /**
   * Complemento para o lançamento contábil
   * @var string
   */
  protected $sComplemento;

  /**
   * Valor total do lançamento
   * @var float
   */
  protected $nValorTotal;

  /**
   * Código do histórico
   * @var integer
   */
  protected $iHistorico;

  /**
   * Código da Conta Crédito
   * @var integer
   */
  protected $iContaCredito;

  /**
   * Código da conta débito
   * @var integer
   */
  protected $iContaDebito;

  /**
   * Código do grupo de conta corrente
   * @var integer
   */
  protected $iCodigoGrupoCorrente;

  /**
   * Variável de controle para sabermos se o lançamento é um estorno
   * @var boolean
   */
  protected $lEstorno = false;

  /**
   * Característica Peculiar da Receita
   * @var string
   */
  protected $sCaracteristicaPeculiar;

  /**
   * Codigo do recurso
   * @var integer
   */
  protected $iCodigoRecurso;


  /**
   * Codigo autenticacao em determinado dia
   * @var integer
   */
  protected $iIdAutenticacao;

  /**
   * data autenticacao
   * @var date
   */
  protected $dtDataAutenticacao;

  /**
   * Codigo autenticadora
   * @var integer
   */
  protected $iAutenticadora;


  /**
   * Seta código da autenticação
   * @param integer $iIdAutenticacao - id da autenticacao
   */
  public function setAutenticacao($iIdAutenticacao) {
    $this->iIdAutenticacao = $iIdAutenticacao;
  }

  /**
   * Retorna código da autenticação
   * @return integer
   */
  public function getAutenticacao() {
    return $this->iIdAutenticacao;
  }

  /**
   * Seta data da autenticação
   * @param date $dtDataAutenticacao - data da autenticacao
   */
  public function setDataAutenticacao($dtDataAutenticacao) {
    $this->dtDataAutenticacao = $dtDataAutenticacao;
  }

  /**
   * Retorna data da autenticação
   * @return date
   */
  public function getDataAutenticacao() {
    return $this->dtDataAutenticacao;
  }

  /**
   * Retorna data da autenticação
   * @param integer - Codigo Autenticadora
   */
  public function setAutenticadora($iAutenticadora) {
    $this->iAutenticadora = $iAutenticadora;
  }

  /**
   * Retorna data da autenticação
   * @return integer
   */
  public function getAutenticadora() {
    return $this->iAutenticadora;
  }

  /**
   * Executa os lançamentos contábeis de uma arrrecadação de receita extra-orçamentária
   * @see ILancamentoAuxiliar::executaLancamentoAuxiliar()
   * @return boolean true
   */
  public function executaLancamentoAuxiliar($iCodigoLancamento, $dtLancamento) {

    parent::setCodigoLancamento($iCodigoLancamento);
    parent::setDataLancamento($dtLancamento);
    parent::salvarVinculoComplemento();
    $this->salvarVinculoCaracteristicaPeculiar();
    $this->salvarVinculoArrecadacao();
    if ( !empty($this->iCodigoGrupoCorrente) ) {
      $this->salvarVinculoGrupoContaCorrente();
    }
    if (!empty($this->iNumeroEmpenho)) {
      $this->salvarVinculoEmpenho();
    }
    return true;
  }

  /**
   * Salva o vínculo com o grupo da conta corrente
   * @throws BusinessException
   */
  private function salvarVinculoGrupoContaCorrente() {

    $oDaoGrupoCorrente = db_utils::getDao('conlancamcorgrupocorrente');
    $oDaoGrupoCorrente->c23_sequencial       = null;
    $oDaoGrupoCorrente->c23_conlancam        = $this->iCodigoLancamento;
    $oDaoGrupoCorrente->c23_corgrupocorrente = $this->iCodigoGrupoCorrente;
    $oDaoGrupoCorrente->incluir(null);
    if ($oDaoGrupoCorrente->erro_status == "0") {
      throw new BusinessException("Não foi possível víncular o grupo com o lançamento.");
    }
    return true;
  }

  /**
   * Seta valor para o complemento do lançamento contábil
   * @see LancamentoAuxiliarBase::setObservacaoHistorico()
   */
  public function setObservacaoHistorico($sObservacaoHistorico) {
    $this->sComplemento = $sObservacaoHistorico;
  }

  /**
   * Retorna o complemento do lançamento contábil
   * @see LancamentoAuxiliarBase::getObservacaoHistorico()
   */
  public function getObservacaoHistorico() {
    return $this->sComplemento;
  }

  /**
   * Seta o valor total
   * @param float $nValorTotal
   * @see ILancamentoAuxiliar::setValorTotal()
   */
  public function setValorTotal($nValorTotal){
    $this->nValorTotal = $nValorTotal;
  }

  /**
   * Retorna o valor total
   * @return float $nValorTotal
   */
  public function getValorTotal(){
    return $this->nValorTotal;
  }

  /**
   * Retorna o histórico da operação
   * @return integer
   */
  public function getHistorico(){
    return $this->iHistorico;
  }


  /**
   * Seta o histórico da operação
   * @param integer $iHistorico
   */
  public function setHistorico($iHistorico){
    $this->iHistorico = $iHistorico;
  }

  /**
   * Seta a conta credito
   * @param integer $iContaCredito
   */
  public function setContaCredito ($iContaCredito) {
    $this->iContaCredito = $iContaCredito;
  }

  /**
   * Retorna a conta credito
   * @return integer
   */
  public function getContaCredito () {
    return $this->iContaCredito;
  }

  /**
   * Seta a conta debito
   * @param integer $iContaDebito
   */
  public function setContaDebito ($iContaDebito) {
    $this->iContaDebito = $iContaDebito;
  }

  /**
   * Retorna o codigo da conta debito
   * @return integer
   */
  public function getContaDebito () {
    return $this->iContaDebito;
  }

  /**
   * Seta se o lançamento é um estorno
   * @param boolean $lEstorno
   */
  public function setEstorno($lEstorno) {
    $this->lEstorno = $lEstorno;
  }

  /**
   * Retorna se o lançamento é um estorno
   * @return boolean
   */
  public function isEstorno() {
    return $this->lEstorno;
  }

  /**
   * Característica Peculiar
   * @param string $sCaracteristicaPeculiar
   */
  public function setCaracteristicaPeculiar($sCaracteristicaPeculiar) {
    $this->sCaracteristicaPeculiar = $sCaracteristicaPeculiar;
  }

  /**
   * Retorna a Característica peculiar
   * @string string
   */
  public function getCaracteristicaPeculiar() {
    return $this->sCaracteristicaPeculiar;
  }
  /**
   * Vincula a característica peculiar com o lançamento contábil.
   * @throws BusinessException
   * @return boolean
   */
  protected function salvarVinculoCaracteristicaPeculiar() {

    if (!empty($this->sCaracteristicaPeculiar)) {

      $oDaoConLancamConCarPeculiar = db_utils::getDao('conlancamconcarpeculiar');
      $oDaoConLancamConCarPeculiar->c08_sequencial     = null;
      $oDaoConLancamConCarPeculiar->c08_codlan         = $this->iCodigoLancamento;
      $oDaoConLancamConCarPeculiar->c08_concarpeculiar = $this->getCaracteristicaPeculiar();
      $oDaoConLancamConCarPeculiar->incluir(null);

      if ($oDaoConLancamConCarPeculiar->erro_status == "0") {
        throw new BusinessException("Não foi possível vincular o lançamento com a característica peculiar.");
      }
    }
    return true;
  }
  /**
   * Retorna o código do recurso
   * @return integer
   */
  public function getCodigoRecurso() {
    return $this->iCodigoRecurso;
  }

  /**
   * Seta o código do recurso
   * @param integer $iCodigoRecurso
   */
  public function setCodigoRecurso($iCodigoRecurso) {
    $this->iCodigoRecurso = $iCodigoRecurso;
  }

  public function salvarVinculoArrecadacao() {

    if (!empty($this->iIdAutenticacao) && !empty($this->dtDataAutenticacao) && !empty($this->iAutenticadora)) {

      $oDAOConlancamcorrente = db_utils::getDao('conlancamcorrente');
      $oDAOConlancamcorrente->c86_id        = $this->iIdAutenticacao;
      $oDAOConlancamcorrente->c86_data      = $this->dtDataAutenticacao;
      $oDAOConlancamcorrente->c86_autent    = $this->iAutenticadora;
      $oDAOConlancamcorrente->c86_conlancam = $this->iCodigoLancamento;
      $oDAOConlancamcorrente->incluir(null);

      if ($oDAOConlancamcorrente->erro_status == "0") {
        throw new BusinessException("Não foi possível criar o vínculo do lançamento com corrente.");
      }
    }
  }
}