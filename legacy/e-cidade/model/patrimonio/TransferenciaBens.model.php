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
 * model para realizar a transferencia de bens
 *
 * @author raphael.lopes  rafael.lopes@dbseller.com.br
 *
 */
class TransferenciaBens {

  /**
   *
   * @var integer id da transferencia
   */
  protected $iTransferencia;

  /**
   * departamento de destino
   * @var integer
   */
  protected $iDepartamentoDestino;

  /**
   * departamento de origem
   * @var integer
   */
  protected $iDepartamentoOrigem;

  /**
   * Usuario logado
   * @var integer
   */
  protected $iUsuario;

  /**
   * Obervaзгo da tranferencia
   * @var string
   */
  protected $sObservacao;

  /**
   * instituiзгo logada
   * @var integer
   */
  protected $iInstit;

  /**
   * classificaзгo do bem
   * @var integer
   */
  protected $iClabens;

  /**
   * divisao de destino
   * @var integer
   */
  protected $iDivisaoDestino;

  /**
   * data da transferencia
   * @var date
   */
  protected $dData;

  /**
   * id do bem a ser transferido
   * @var integer
   */
  protected $iBem;

  /**
   * situaзгo do bem a ser transferido
   * @var integer
   */
  protected $iSituacao;

  /**
   * historico para transferencia
   * @var string
   */
  protected $sHistorico;

  /**
   * divisao atual do bem
   * @var integer
   */
  protected $iDivisaoOrigem;

  /**
   * @todo refatorar classe, nome de metodo e variaveis
   * @param string $iTransferencia
   */
  function __construct($iTransferencia = null){

    if (isset($iTransferencia)) {

      $this->setTransferencia($iTransferencia);
    }

  }

  /**
   * metodo que realizarб o recebimento de uma
   * transferencia de bens
   */

  public function receberTransferencia(){

    if ( !db_utils::inTransaction() ) {
      throw new DBException("[ 1 - receberTransferencia ] - Nenhuma transaзгo encontrada!");
    }

    $oDaoBensTransfConf     = db_utils::getDao("benstransfconf");
    $oDaoHistbens           = db_utils::getDao("histbem");
    $oDaoHistBensOcorrencia = db_utils::getDao("histbensocorrencia");
    $oDaoBens               = db_utils::getDao("bens");
    $oDaoHistBemTrans       = db_utils::getDao("histbemtrans");
    $oDaoHistBemDiv         = db_utils::getDao("histbemdiv");
    $oDaoBensDiv            = db_utils::getDao("bensdiv");

    // incluimos dados na benstransfconf
    $oDaoBensTransfConf->t96_codtran    = $this->getTransferencia();
    $oDaoBensTransfConf->t96_id_usuario = $this->getUsuario();
    $oDaoBensTransfConf->t96_data       = $this->getData();
    $oDaoBensTransfConf->incluir($oDaoBensTransfConf->t96_codtran);
    if ($oDaoBensTransfConf->erro_status == "0") {
      throw new DBException("[ 2 - receberTransferencia ] Cуdigo do bem: {$this->getBem()} - " . $oDaoBensTransfConf->erro_msg);
    }

    // incluimos dados na histbem
    $oDaoHistbens->t56_codbem = $this->getBem();
    $oDaoHistbens->t56_data   = $this->getData();
    $oDaoHistbens->t56_depart = $this->getDepartamentoDestino();
    $oDaoHistbens->t56_situac = $this->getSituacao();
    $oDaoHistbens->t56_histor = $this->getHistorico();
    $oDaoHistbens->incluir(null);
    if ($oDaoHistbens->erro_status == "0") {
      throw new DBException(" [ 3 - receberTransferencia] Cуdigo do bem: {$this->getBem()} - " . $oDaoHistbens->erro_msg);
    }

    // inserir na histbensocorrencia
    $oDaoHistBensOcorrencia->t69_codbem           = $this->getBem();
    $oDaoHistBensOcorrencia->t69_ocorrenciasbens  = 1; // valor fixo = transferencia
    $oDaoHistBensOcorrencia->t69_obs              = $this->getObservacao();
    $oDaoHistBensOcorrencia->t69_dthist           = date("Y-m-d", db_getsession("DB_datausu"));
    $oDaoHistBensOcorrencia->t69_hora             = date("H:i");
    $oDaoHistBensOcorrencia->incluir(null);
    if ($oDaoHistBensOcorrencia->erro_status == "0") {
      throw new DBException(" [ 4 - receberTransferencia ] Cуdigo do bem: {$this->getBem()} - " . $oDaoHistBensOcorrencia->erro_msg);
    }

    // altera na tabela bens setando o novo departamento
    $oDaoBens->t52_bem    = $this->getBem();
    $oDaoBens->t52_depart = $this->getDepartamentoDestino();
    $oDaoBens->alterar($oDaoBens->t52_bem);
    if ($oDaoBens->erro_status == "0") {
      throw new DBException(" [ 5 - receberTransferencia ] Cуdigo do bem: {$this->getBem()} - " . $oDaoBens->erro_msg);
    }

    //incluir na tabela histbemtrans vinculando a transferencia com o bem
    $oDaoHistBemTrans->t97_histbem = $oDaoHistbens->t56_histbem;
    $oDaoHistBemTrans->t97_codtran = $this->getTransferencia();
    $oDaoHistBemTrans->incluir($oDaoHistBemTrans->t97_histbem, $oDaoHistBemTrans->t97_codtran);
    if ($oDaoHistBemTrans->erro_status == "0") {
      throw new DBException(" [ 6 - receberTransferencia ] Cуdigo do bem: {$this->getBem()} - " . $oDaoHistBemTrans->erro_msg);
    }

    // incluir histbemdiv
    $oDaoHistBemDiv->t32_histbem = $oDaoHistbens->t56_histbem;
    $oDaoHistBemDiv->t32_divisao = $this->getDivisaoDestino();
    $oDaoHistBemDiv->incluir(null);
    if ($oDaoHistBemDiv->erro_status == "0") {
       throw new DBException(" [ 7 - receberTransferencia ] Cуdigo do bem: {$this->getBem()} - " . $oDaoHistBemDiv->erro_msg);
    }

    // excluir bensdiv , divisao atual
    $oDaoBensDiv->excluir($this->getBem());
    if ($oDaoBensDiv->erro_status == "0") {
      throw new DBException(" [ 8 - receberTransferencia ] Cуdigo do bem: {$this->getBem()} - " . $oDaoBensDiv->erro_msg);
    }

    // incluir na bensdiv o bem com a nova divisao
    $oDaoBensDiv->t33_divisao = $this->getDivisaoDestino();
    $oDaoBensDiv->incluir($this->getBem());
    if ($oDaoBensDiv->erro_status == "0") {
      throw new DBException(" [ 9 - receberTransferencia ] Cуdigo do bem: {$this->getBem()} - " . $oDaoBensDiv->erro_msg);
    }

  }


  public function transferenciaAutomatica(){

    $oBem = new Bem($this->getBem());
    $iDepartamentoAtual = $oBem->getDepartamento();

    /**
     *  primeiro verificamos se o departamento atual do bem й igual ao de destino
     *  se for o mesmo, й desnecessario a transferencia
     */
    if ($iDepartamentoAtual != $this->getDepartamentoDestino()) {

      if ( !db_utils::inTransaction() ) {
        throw new DBException("[ 1 - transferenciaAutomatica ] - Nenhuma transaзгo encontrada!");
      }

      $oDaoBensTransf              = db_utils::getDao("benstransf");
      $oDaoBensTransfdes           = db_utils::getDao("benstransfdes");
      $oDaoBensTransfDiv           = db_utils::getDao("benstransfdiv");
      $oDaoBensTransfCodigo        = db_utils::getDao("benstransfcodigo");
      $oDaoBensTransfOrigemDestino = db_utils::getDao("benstransforigemdestino");

      // inclusao BensTransf
      $oDaoBensTransf->t93_data       = $this->getData();
      $oDaoBensTransf->t93_depart     = $this->getDepartamentoDestino();
      $oDaoBensTransf->t93_id_usuario = $this->getUsuario();
      $oDaoBensTransf->t93_obs        = $this->getObservacao();
      $oDaoBensTransf->t93_instit     = $this->getInstit();
      $oDaoBensTransf->t93_clabens    = $this->getClabens();
      $oDaoBensTransf->t93_divisao    = $this->getDivisaoDestino();
      $oDaoBensTransf->incluir($this->getTransferencia(), $oDaoBensTransf->t93_depart);
      if ($oDaoBensTransf->erro_status == "0") {
        throw new DBException(" [ 2 -  transferenciaAutomatica ] Cуdigo do bem: {$this->getBem()} -  " . $oDaoBensTransf->erro_msg);
      }
      $this->setTransferencia($oDaoBensTransf->t93_codtran);

      // incluimos BensTransfdes
      $oDaoBensTransfdes->t94_codtran = $this->getTransferencia();
      $oDaoBensTransfdes->t94_depart  = $this->getDepartamentoDestino();
      $oDaoBensTransfdes->t94_divisao = $this->getDivisaoDestino();
      $oDaoBensTransfdes->incluir($oDaoBensTransfdes->t94_codtran, $oDaoBensTransfdes->t94_depart);
      if ($oDaoBensTransfdes->erro_status == "0") {
        throw new DBException(" [3 - transferenciaAutomatica] Cуdigo do bem: {$this->getBem()} - " . $oDaoBensTransfdes->erro_msg );
      }


      // incluimos BensTransfDiv
      $oDaoBensTransfDiv->t31_codtran = $this->getTransferencia();
      $oDaoBensTransfDiv->t31_bem     = $this->getBem();
      $oDaoBensTransfDiv->t31_divisao = $this->getDivisaoDestino();
      $oDaoBensTransfDiv->incluir(null);
      if ($oDaoBensTransfDiv->erro_status == "0"){
        throw new DBException("[ 4 - transferenciaAutomatica] Cуdigo do bem: {$this->getBem()} - " . $oDaoBensTransfDiv->erro_msg);
      }


      // incluimos benstransfcodigo
      $oDaoBensTransfCodigo->t95_codtran = $this->getTransferencia();
      $oDaoBensTransfCodigo->t95_codbem  = $this->getBem();
      $oDaoBensTransfCodigo->t95_situac  = $this->getSituacao();
      $oDaoBensTransfCodigo->t95_histor  = $this->getHistorico();
      $oDaoBensTransfCodigo->incluir($oDaoBensTransfCodigo->t95_codtran, $oDaoBensTransfCodigo->t95_codbem);
      if ($oDaoBensTransfCodigo->erro_status == "0") {
        throw new DBException( "[ 5 - transferenciaAutomatica ] Cуdigo do bem: {$this->getBem()} - " . $oDaoBensTransfCodigo->erro_msg );
      }

      // incluimos BensTransfOrigemDestino
      $oDaoBensTransfOrigemDestino->t34_transferencia       = $this->getTransferencia();
      $oDaoBensTransfOrigemDestino->t34_bem                 = $this->getBem();
      $oDaoBensTransfOrigemDestino->t34_divisaoorigem       = $this->getDivisaoOrigem();
      $oDaoBensTransfOrigemDestino->t34_divisaodestino      = $this->getDivisaoDestino();
      $oDaoBensTransfOrigemDestino->t34_departamentoorigem  = $this->getDepartamentoOrigem();
      $oDaoBensTransfOrigemDestino->t34_departamentodestino = $this->getDepartamentoDestino();
      $oDaoBensTransfOrigemDestino->incluir(null);
      if ($oDaoBensTransfOrigemDestino->erro_status == "0") {
        throw new DBException(" [6 - transferenciaAutomatica Cуdigo do bem: {$this->getBem()} - ]" . $oDaoBensTransfOrigemDestino->erro_msg);
      }
      $this->receberTransferencia();
    }

  }

  public function setTransferencia($iTransferencia) {
    $this->iTransferencia = $iTransferencia;
  }

  public function getTransferencia() {
    return $this->iTransferencia;
  }

  public function setDepartamentoDestino($iDepartamentoDestino) {
    $this->iDepartamentoDestino = $iDepartamentoDestino;
  }

  public function getDepartamentoDestino() {
    return $this->iDepartamentoDestino;
  }

  public function setUsuario($iUsuario) {
    $this->iUsuario = $iUsuario;
  }

  public function getUsuario() {
    return $this->iUsuario;
  }

  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  public function getObservacao() {
    return $this->sObservacao;
  }

  public function setInstit($iInstit) {
    $this->iInstit = $iInstit;
  }

  public function getInstit() {
    return $this->iInstit;
  }

  public function setClabens($iClabens) {
    $this->iClabens = $iClabens;
  }

  public function getClabens() {
    return $this->iClabens;
  }

  public function setDivisaoDestino($iDivisaoDestino) {
    $this->iDivisaoDestino = $iDivisaoDestino;
  }

  public function getDivisaoDestino() {

    if ($this->iDivisaoDestino == "") {
      $this->iDivisaoDestino = "0";
    }
    return $this->iDivisaoDestino;
  }

  public function setData($dData) {
    $this->dData = $dData;
  }

  public function getData() {
    return $this->dData;
  }

  public function setBem($iBem) {
    $this->iBem = $iBem;
  }

  public function getBem() {
    return $this->iBem;
  }

  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  public function getSituacao() {
    return $this->iSituacao;
  }

  public function setHistorico($sHistorico) {
    $this->sHistorico = $sHistorico;
  }

  public function getHistorico() {
    return $this->sHistorico;
  }

  public function setDivisaoOrigem($iDivisaoOrigem) {
    $this->iDivisaoOrigem = $iDivisaoOrigem;
  }

  public function getDivisaoOrigem() {

    if (!isset($this->iDivisaoOrigem)) {

      $iDivisaoOrigem = "0";
      $oDaoBensDiv    = db_utils::getDao("bensdiv");
      $sSqlBensDiv    = $oDaoBensDiv->sql_query_file($this->getBem());
      $rsBensdiv      = $oDaoBensDiv->sql_record($sSqlBensDiv);
      if ($oDaoBensDiv->numrows > 0) {
        $iDivisaoOrigem = db_utils::fieldsMemory($rsBensdiv, 0)->t33_divisao;
        $this->setDivisaoOrigem($iDivisaoOrigem);
      }

    }

      return $this->iDivisaoOrigem;
  }

  public function setDepartamentoOrigem($iDepartamentoOrigem) {
    $this->iDepartamentoOrigem = $iDepartamentoOrigem;
  }

  public function getDepartamentoOrigem() {
    return $this->iDepartamentoOrigem;
  }
}
?>