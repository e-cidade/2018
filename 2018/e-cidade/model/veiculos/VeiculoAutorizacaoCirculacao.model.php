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

class VeiculoAutorizacaoCirculacao {

  /**
   *
   * @var integer
   */
  private $iCodigo;

  /**
   *
   * @var Instituicao
   */
  private $oInstituicao;

  /**
   *
   * @var integer
   */
  private $iCodigoInstituicao;

  /**
   *
   * @var Veiculo
   */
  private $oVeiculo;

  /**
   *
   * @var integer
   */
  private $iCodigoVeiculo;

  /**
   *
   * @var VeiculoMotorista
   */
  private $oMotorista;

  /**
   *
   * @var integer
   */
  private $iCodigoMotorista;

  /**
   *
   * @var DBDate
   */
  private $oDataInicial;

  /**
   *
   * @var DBDate
   */
  private $oDataFinal;

  /**
   *
   * @var DBDate
   */
  private $oDataEmissao;

  /**
   *
   * @var string
   */
  private $sObservacao;

  /**
   *
   * @var integer
   */
  private $iCodigoDepartamento;

  /**
   *
   * @var DBDepartamento
   */
  private $oDepartamento;

  /**
   *
   * @param  integer $iCodigo
   * @throws Exception
   */
  public function __construct($iCodigo = null) {

    if ($iCodigo !== null) {

      $oDaoAutorizacao = new cl_autorizacaocirculacaoveiculo;
      $sSql            = $oDaoAutorizacao->sql_query($iCodigo);
      $rsAutorizacao   = $oDaoAutorizacao->sql_record($sSql);

      if ($oDaoAutorizacao->numrows > 0) {
        $oAutorizacao = db_utils::fieldsMemory($rsAutorizacao, 0);
      } else {
        throw new Exception("Autorização para Circulação não encontrada.");
      }

      $this->iCodigo             = $oAutorizacao->ve13_sequencial;
      $this->iCodigoInstituicao  = $oAutorizacao->ve13_instituicao;
      $this->iCodigoVeiculo      = $oAutorizacao->ve13_veiculo;
      $this->iCodigoMotorista    = $oAutorizacao->ve13_motorista;
      $this->iCodigoDepartamento = $oAutorizacao->ve13_departamento;
      $this->oDataInicial        = new DBDate($oAutorizacao->ve13_datainicial);
      $this->oDataFinal          = new DBDate($oAutorizacao->ve13_datafinal);
      $this->oDataEmissao        = new DBDate($oAutorizacao->ve13_dataemissao);
      $this->sObservacao         = $oAutorizacao->ve13_observacao;
    }
  }

  public function salvar() {

    $oDaoAutorizacao = new cl_autorizacaocirculacaoveiculo;

    $oDaoAutorizacao->ve13_sequencial   = $this->iCodigo;
    $oDaoAutorizacao->ve13_instituicao  = $this->iCodigoInstituicao;
    $oDaoAutorizacao->ve13_veiculo      = $this->iCodigoVeiculo;
    $oDaoAutorizacao->ve13_motorista    = $this->iCodigoMotorista;
    $oDaoAutorizacao->ve13_departamento = $this->iCodigoDepartamento;
    $oDaoAutorizacao->ve13_observacao   = $this->sObservacao;

    if ($this->oDataInicial !== null) {
      $oDaoAutorizacao->ve13_datainicial = $this->oDataInicial->getDate();
    }
    if ($this->oDataFinal !== null) {
      $oDaoAutorizacao->ve13_datafinal = $this->oDataFinal->getDate();
    }
    if ($this->oDataEmissao !== null) {
      $oDaoAutorizacao->ve13_dataemissao = $this->oDataEmissao->getDate();
    }

    if($this->iCodigo === null) {
      $oDaoAutorizacao->incluir();
    } else {
      $oDaoAutorizacao->alterar($this->iCodigo);
    }

    if ($oDaoAutorizacao->erro_status == "0") {

      $sMensagemErro  = "Não foi possível salvar a Autorização para Circulação.\n";
      $sMensagemErro .= str_replace("\\n", "\n", $oDaoAutorizacao->erro_msg);
      throw new Exception($sMensagemErro);
    }

    $this->iCodigo = $oDaoAutorizacao->ve13_sequencial;

    return true;
  }

  /**
   *
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   *
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   *
   * @return Instituicao
   */
  public function getInstituicao() {

    if ($this->oInstituicao === null && $this->iCodigoInstituicao !== null) {
      $this->oInstituicao = new Instituicao($this->iCodigoInstituicao);
    }

    return $this->oInstituicao;
  }

  /**
   *
   * @param integer $iCodigoInstituicao
   */
  public function setCodigoInstituicao($iCodigoInstituicao) {

    $this->iCodigoInstituicao = $iCodigoInstituicao;
    $this->oInstituicao = null;
  }

  /**
   *
   * @param Instituicao $oInstituicao
   */
  public function setInstituicao(Instituicao $oInstituicao) {

    $this->iCodigoInstituicao = $oInstituicao->getCodigo();
    $this->oInstituicao = $oInstituicao;
  }

  /**
   *
   * @return Veiculo
   */
  public function getVeiculo() {

    if ($this->oVeiculo === null && $this->iCodigoVeiculo !== null) {
      $this->oVeiculo = new Veiculo($this->iCodigoVeiculo);
    }

    return $this->oVeiculo;
  }

  /**
   *
   * @param integer $iCodigoVeiculo
   */
  public function setCodigoVeiculo($iCodigoVeiculo) {

    $this->iCodigoVeiculo = $iCodigoVeiculo;
    $this->oVeiculo = null;
  }

  /**
   *
   * @param Veiculo $oVeiculo
   */
  public function setVeiculo(Veiculo $oVeiculo)
  {

    $this->iCodigoVeiculo = $oVeiculo->getCodigo();
    $this->oVeiculo = $oVeiculo;
  }

  /**
   *
   * @return DBDepartamento
   */
  public function getDepartamento() {

    if ($this->oDepartamento === null && $this->iCodigoDepartamento !== null) {
      $this->oDepartamento = new DBDepartamento($this->iCodigoDepartamento);
    }

    return $this->oDepartamento;
  }

  /**
   *
   * @param integer $iCodigoDepartamento
   */
  public function setCodigoDepartamento($iCodigoDepartamento) {

    $this->iCodigoDepartamento = $iCodigoDepartamento;
    $this->oDepartamento = null;
  }

  /**
   *
   * @param DBDepartamento $oDepartamento
   */
  public function setDepartamento(DBDepartamento $oDepartamento) {

    $this->iCodigoDepartamento = $oDepartamento->getCodigo();
    $this->oDepartamento = $oDepartamento;
  }

  /**
   *
   * @return VeiculoMotorista
   */
  public function getMotorista() {

    if ($this->oMotorista === null && $this->iCodigoMotorista !== null) {
      $this->oMotorista = VeiculoMotorista::getInstanciaPorCodigo($this->iCodigoMotorista);
    }

    return $this->oMotorista;
  }

  /**
   *
   * @param integer $iCodigoMotorista
   */
  public function setCodigoMotorista($iCodigoMotorista) {

    $this->iCodigoMotorista = $iCodigoMotorista;
    $this->oMotorista = null;
  }

  /**
   *
   * @param VeiculoMotorista $oMotorista
   */
  public function setMotorista(VeiculoMotorista $oMotorista) {

    $this->iCodigoMotorista = $oMotorista->ve05_codigo;
    $this->oMotorista = $oMotorista;
  }

  /**
   *
   * @return DBDate
   */
  public function getDataInicial() {
    return $this->oDataInicial;
  }

  /**
   *
   * @param DBDate $oDataInicial
   */
  public function setDataInicial(DBDate $oDataInicial) {
    $this->oDataInicial = $oDataInicial;
  }

  /**
   *
   * @return DBDate
   */
  public function getDataFinal() {
    return $this->oDataFinal;
  }

  /**
   *
   * @param DBDate $oDataFinal
   */
  public function setDataFinal(DBDate $oDataFinal) {
    $this->oDataFinal = $oDataFinal;
  }

  /**
   *
   * @return DBDate
   */
  public function getDataEmissao() {
    return $this->oDataEmissao;
  }

  /**
   *
   * @param DBDate $oDataEmissao
   */
  public function setDataEmissao(DBDate $oDataEmissao) {
    $this->oDataEmissao = $oDataEmissao;
  }

  /**
   *
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   *
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

}
