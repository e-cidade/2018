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
 * Model para encapsulamento de um SLIP
 * @author Matheus Felini / Bruno Silva
 * @package caixa
 * @subpackage slip
 * @version $Revision: 1.27 $
 */
class TransferenciaFinanceira extends Transferencia {

  /**
   * Instituicao de destino
   * @var integer
   */
  private $iInstituicaoDestino;

  /**
   * Código da transferencia financeira
   * @var integer
   */
  private $iCodigoTransferencia = null;

  /**
   * Carrega os dados da transferencia, incluindo o codigo da instituicao de destino
   * @param integer $iCodigoSlip
   */
  public function __construct($iCodigoSlip = null) {

    parent::__construct($iCodigoSlip);
    if (!empty($iCodigoSlip)) {

      $oDaoSlipInstituicao = db_utils::getDao('transferenciafinanceira');


      $sSqlBuscaSlip       = $oDaoSlipInstituicao->sql_query(null, "*", null, "k150_slip = {$iCodigoSlip}");
      $rsBuscaSlip         = $oDaoSlipInstituicao->sql_record($sSqlBuscaSlip);
      if ($oDaoSlipInstituicao->numrows > 0) {

        $oDadoSlip                  = db_utils::fieldsMemory($rsBuscaSlip, 0);
        $this->iInstituicaoDestino  = $oDadoSlip->k150_instituicao;
        $this->iCodigoTransferencia = $oDadoSlip->k150_sequencial;
      }
    }
  }


  /**
   * Metodo que salva um slip do tipo transferencia bancaria
   * @see Transferencia::salvar()
   */
  public function salvar() {

    parent::salvar();

    $oDaoTransferenciaFinanceira = db_utils::getDao('transferenciafinanceira');

    $oDaoTransferenciaFinanceira->k150_slip        = $this->getCodigoSlip();
    $oDaoTransferenciaFinanceira->k150_instituicao = $this->iInstituicaoDestino;
    if (empty($this->iCodigoTransferencia)) {

      $oDaoTransferenciaFinanceira->incluir(null);
      $this->iCodigoTransferencia = $oDaoTransferenciaFinanceira->k150_sequencial;

    } else {

      $oDaoTransferenciaFinanceira->k150_sequencial  = $this->iCodigoTransferencia;
      $oDaoTransferenciaFinanceira->alterar($this->iCodigoTransferencia);
    }

    if ($oDaoTransferenciaFinanceira->erro_status == "0") {

      $sMensagemErro  = "Não foi possível víncular a instituicao de destino.\n\n";
      $sMensagemErro .= "Erro Técnico: {$oDaoTransferenciaFinanceira->erro_msg}";
      throw new Exception($sMensagemErro);
    }

    parent::vinculaSlipTipoDeOperacao();

    return true;
  }

  /**
   * Busca todas transferências dentro da instituição logada
   * @return array slip
   */
  static function getTransferenciasInstituicao() {

    $iInstituicaoSessao          = db_getsession("DB_instit");
    $sWhereInstituicao           = "k150_instituicao = {$iInstituicaoSessao}";
    $oDaoTransferenciaFinanceira = db_utils::getDao('transferenciafinanceira');
    $sSqlBuscaTransferencia      = $oDaoTransferenciaFinanceira->sql_query(null, "k17_codigo", null, $sWhereInstituicao);
    $rsBuscaTransferencia        = $oDaoTransferenciaFinanceira->sql_record($sSqlBuscaTransferencia);
    $iLinhasTransferencia        = $oDaoTransferenciaFinanceira->numrows;
    $aTransferencias             = array();

    if ($oDaoTransferenciaFinanceira->numrows > 0) {

      for ($iRowTransferencia = 0; $iRowTransferencia < $iLinhasTransferencia; $iRowTransferencia++) {

        $iCodigoSlip       = db_utils::fieldsMemory($rsBuscaTransferencia, $iRowTransferencia)->k12_codigo;
        $oSlip             = new slip($iCodigoSlip);
        $aTransferencias[] = $oSlip;
      }
    }
    return $aTransferencias;
  }

  /**
   *  Busca todas transferências pendentes, dentro da instituição logada
   * @return array slip
   */
  static function getTransferenciasPendentes() {

    $iInstituicaoSessao          = db_getsession("DB_instit");
    $sWhereInstituicao           = "k150_instituicao = {$iInstituicaoSessao}";
    $oDaoTransferenciaFinanceira = db_utils::getDao('transferenciafinanceira');
    $sSqlBuscaTransferencia      = $oDaoTransferenciaFinanceira->sql_query_recebimento(null, "k17_codigo", null, $sWhereInstituicao);
    $rsBuscaTransferencia        = $oDaoTransferenciaFinanceira->sql_record($sSqlBuscaTransferencia);
    $iLinhasTransferencia        = $oDaoTransferenciaFinanceira->numrows;
    $aTransferencias             = array();

    if ($oDaoTransferenciaFinanceira->numrows > 0) {

      for ($iRowTransferencia = 0; $iRowTransferencia < $iLinhasTransferencia; $iRowTransferencia++) {

        $iCodigoSlip       = db_utils::fieldsMemory($rsBuscaTransferencia, $iRowTransferencia)->k12_codigo;
        $oSlip             = new slip($iCodigoSlip);
        $aTransferencias[] = $oSlip;
      }
    }
    return $aTransferencias;
  }


  /**
   * Seta o código da instituicao de destino
   * @param integer $iInstituicao
   */
  public function setInstituicaoDestino($iInstituicao) {
    $this->iInstituicaoDestino = $iInstituicao;
  }

  /**
   * Retorna o código da instituicao de destino
   * @return integer
   */
  public function getInstituicaoDestino() {
  	return $this->iInstituicaoDestino;
  }

  /**
   * @param $iCodigoTransferencia
   * @return bool
   * @throws Exception
   */
  public function receberTransferencia($iCodigoTransferencia){

    $iCodigoSlipRecebido = $this->getCodigoSlip();
    $this->setCodigoSlip(null);
    parent::salvar();

    $this->executaAutenticacao();

    $oDaoTipoOperacaoVinculo                        = db_utils::getDao('sliptipooperacaovinculo');
    $oDaoTipoOperacaoVinculo->k153_slip             = $this->getCodigoSlip();
    $oDaoTipoOperacaoVinculo->k153_slipoperacaotipo = 3;
    $oDaoTipoOperacaoVinculo->incluir($this->getCodigoSlip());
    if ($oDaoTipoOperacaoVinculo->erro_status == "0") {
      throw new Exception("Impossível vincular o slip ao tipo de operação.\n\nErro Técnico:{$oDaoTipoOperacaoVinculo->erro_msg}");
    }

    /**
     * Salva os dados de recebimento na tabela 'transferenciafinanceirarecebimento'
     */
    $oDaoTransRecebimento = db_utils::getDao('transferenciafinanceirarecebimento');
    $oDaoTransRecebimento->k151_slip                    = $this->getCodigoSlip();
    $oDaoTransRecebimento->k151_db_usuario              = db_getsession("DB_id_usuario");
    $oDaoTransRecebimento->k151_transferenciafinanceira = $iCodigoTransferencia;
    $oDaoTransRecebimento->k151_hora                    = date("H:i:s", db_getsession("DB_datausu"));
    $oDaoTransRecebimento->k151_data                    = date("Y-m-d",db_getsession("DB_datausu"));
    $oDaoTransRecebimento->k151_estornado  ='false';
    $oDaoTransRecebimento->incluir(null);

    if ($oDaoTransRecebimento->erro_status == 0) {

      $sMensagemErro  = "Não foi possível receber a Transferencia.\n\n";
      $sMensagemErro .= "Erro Técnico: {$oDaoTransRecebimento->erro_msg}";
      throw new Exception($sMensagemErro);
    }

    $this->salvarVinculoComProcesso();

    $this->executarLancamentoContabil();
    return true;
  }

  /**
   * Verifica se existe recebimento da transferência
   * @return boolean
   */
  public function possuiRecebimento() {

    $iCodigoSlip        = $this->getCodigoSlip();
    $oDaoRecebimento    = db_utils::getDao('transferenciafinanceira');
    $sWhere             = "k150_slip = {$iCodigoSlip}";
    $sSqlRecebimento    = $oDaoRecebimento->sql_query_recebimento(null, "*", null, $sWhere);
    $rsRecebimento      = $oDaoRecebimento->sql_record($sSqlRecebimento);
    $oDadoTransferencia = db_utils::fieldsMemory($rsRecebimento, 0);

    if ($oDaoRecebimento->numrows == 0) {
      return false;
    }
    if (empty($oDadoTransferencia->k151_sequencial)) {
      return false;
    }
    return true;
  }

  /**
   * @return bool
   * @throws Exception
   */
  public function possuiEstornoRecebimento() {

    $iCodigoSlip     = $this->getCodigoSlip();
    $oDaoRecebimento = db_utils::getDao('transferenciafinanceirarecebimento');
    $sWhere          = "k150_slip = {$iCodigoSlip}";
    $sSqlRecebimento = $oDaoRecebimento->sql_query(null, "*", null, $sWhere);
    $rsRecebimento   = $oDaoRecebimento->sql_record($sSqlRecebimento);
    $oRecebimento    = db_utils::fieldsMemory($rsRecebimento,0);

    if ($oDaoRecebimento->erro_status == '0') {

      $sMensagemUsuario  = "Não foi possível validar a transferência financeira.\n\nErro técnico: ";
      $sMensagemUsuario .= "{$oDaoRecebimento->erro_msg}";
      throw new Exception ($sMensagemUsuario);
    }

    /**
     * verifica se foi estornado
     * caso esteja, é possível anular
     * return true
     */
    if ($oDaoRecebimento->numrows > 0 ) {

      $oRecebimento = db_utils::fieldsMemory($rsRecebimento, 0);
      if ($oRecebimento->k151_estornado == 't') {
        return true;
      }
    }
    return false;
  }

  /**
   * @param string $sMotivo
   *
   * @return bool
   * @throws BusinessException
   * @throws Exception
   */
  public function anular($sMotivo) {

    /**
     * Verifica se existe não existe recebimento ou se existe mas está estornado
     */
    $lExcluirCheque = true;
    if ($this->getTipoOperacao() == 4) {
      $lExcluirCheque = false;
    }

    if ($this->getTipoOperacao() != 3 && $this->getTipoOperacao() != 4) {
      if ($this->possuiRecebimento() && !$this->possuiEstornoRecebimento()) {
        throw new BusinessException("Essa transferência possui recebimento e não está estornada.");
      }
    }

    /**
     * marcamos a transferencia como estornada, quando a operacao for o estorno do recebimento
     */
    if ($this->getTipoOperacao() == 4) {
      $this->mudaStatusAnulado();
    }
    $this->oSlip->anular($sMotivo, $lExcluirCheque);
    return true;
  }

  /**
   * @throws Exception
   */
  public function mudaStatusAnulado() {

    $oDaoRecebimento   = db_utils::getDao('transferenciafinanceirarecebimento');
    $sWhere            = "k151_slip = {$this->getCodigoSlip()}";
    $sSqlRecebimento   = $oDaoRecebimento->sql_query_file(null, "k151_sequencial", null, $sWhere);
    $rsRecebimento     = $oDaoRecebimento->sql_record($sSqlRecebimento);

    if ($oDaoRecebimento->erro_status == "0") {
      $sMsg  = "Erro na mudança de status da transferência. \n\n Erro técnico: query tabela transferenciafinanceirarecebimento | ";
      $sMsg .= " {$oDaoRecebimento->erro_msg}";
      throw new Exception($sMsg);
    }
    $oRecebimento      = db_utils::fieldsMemory($rsRecebimento,0);
    $oDaoRecebimentoAlterado = db_utils::getDao('transferenciafinanceirarecebimento');
    $oDaoRecebimentoAlterado->k151_estornado  = "true";
    $oDaoRecebimentoAlterado->k151_sequencial  = $oRecebimento->k151_sequencial;
    $oDaoRecebimentoAlterado->alterar($oRecebimento->k151_sequencial);
    if ($oDaoRecebimentoAlterado->erro_status == "0") {
      throw new Exception("Ocorreu um erro ao alterar o status do slip de recebimento.");
    }
    return true;
  }

  /**
   * @param $sMotivo
   *
   * @throws Exception
   */
  public function anularRecebimento($sMotivo) {

    $this->oSlip->anular($sMotivo);
    $this->mudaStatusAnulado();
  }
}