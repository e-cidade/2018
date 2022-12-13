<?php
/**
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
 * Histórico da movimentação da ficha de atendimento do paciente
 * @package    Ambulatorial
 * @author     Andrio Costa - andrio.costa@dbseller.com.br
 *             Eduardo Sirangelo - eduardo.sirangelo@dbseller.com.br
 * @version    $Revision: 1.5 $
 */
class MovimentacaoFichaAtendimento {

  const MENSAGEM                    = 'saude.ambulatorial.MovimentacaoFichaAtendimento.';
  const SITUACAO_ENTRADA            = 1;
  const SITUACAO_ENCAMINHADA        = 2;
  const SITUACAO_FINALIZADA         = 3;
  const SITUACAO_ATESTADO_EM_BRANCO = 4;

  /**
   * Codigo
   * @var integer
   */
  private $iCodigo;

  /**
   * Ficha de atendimento do paciente
   * @var integer
   */
  private $iFichaAtendimento;

  /**
   * Usuário do sistema que realizou a movimentação
   * @var UsuarioSistema
   */
  private $oUsuarioSistema;

  /**
   * Setor ambulatorial
   * @var SetorAmbulatorial
   */
  private $oSetorAmbulatorial;

  /**
   * Data que foi realizada a movimentação
   * @var DBDate
   */
  private $oData;

  /**
   * Hora que foi realizada a movimentação
   * @var string
   */
  private $sHora;

  /**
   * Situacao da movimentação do prontuario
   * @var integer
   */
  private $iSituacao;

  /**
   * Observações lançadas
   * @var string
   */
  private $sObservacao;

  static $aSituacoes = array(
    1 => "ENTRADA",
    2 => "ENCAMINHADA",
    3 => "FINALIZADA",
    4 => "ATESTADO EM BRANCO"
  );

  public function __construct ($iCodigo = null) {

    if ( !empty($iCodigo) ) {

      $oDaoMovimentacao = new cl_movimentacaoprontuario();
      $sSqlMovimentacao = $oDaoMovimentacao->sql_query_file($iCodigo);
      $rsMovimentacao   = db_query($sSqlMovimentacao);

      $oErro = new stdClass();
      if ( !$rsMovimentacao ) {

        $oErro->sErro = pg_last_error();
        throw new DBException( _M(MovimentacaoFichaAtendimento::MENSAGEM."erro_buscar_movimentacao", $oErro) );
      }

      if ( pg_num_rows($rsMovimentacao) > 0 ) {

        $oDados = db_utils::fieldsMemory($rsMovimentacao, 0);

        $this->iCodigo            = $oDados->sd102_codigo;
        $this->iFichaAtendimento  = $oDados->sd102_prontuarios;
        $this->oUsuarioSistema    = UsuarioSistemaRepository::getPorCodigo( $oDados->sd102_db_usuarios );
        $this->oSetorAmbulatorial = SetorAmbulatorialRepository::getPorCodigo( $oDados->sd102_setorambulatorial );
        $this->oData              = new DBDate( $oDados->sd102_data );
        $this->sHora              = $oDados->sd102_hora;
        $this->iSituacao          = $oDados->sd102_situacao;
        $this->sObservacao        = $oDados->sd102_observacao;
      }

    }
    return true;
  }

  /**
   * Salva os dados da movimentacao
   */
  public function salvar() {

    $oDaoMovimentacao = new cl_movimentacaoprontuario();

    $oDaoMovimentacao->sd102_prontuarios       = $this->iFichaAtendimento;
    $oDaoMovimentacao->sd102_db_usuarios       = $this->oUsuarioSistema->getIdUsuario();
    $oDaoMovimentacao->sd102_setorambulatorial = $this->oSetorAmbulatorial->getCodigo();
    $oDaoMovimentacao->sd102_data              = $this->oData->getDate();
    $oDaoMovimentacao->sd102_hora              = $this->sHora;
    $oDaoMovimentacao->sd102_situacao          = $this->iSituacao;
    $oDaoMovimentacao->sd102_observacao        = $this->sObservacao;

    if ( empty($this->iCodigo) ) {

      $oDaoMovimentacao->sd102_codigo = null;
      $oDaoMovimentacao->incluir(null);
    } else {
      $oDaoMovimentacao->sd102_codigo = $this->iCodigo;
      $oDaoMovimentacao->alterar($this->iCodigo);
    }

    $this->iCodigo = $oDaoMovimentacao->sd102_codigo;
    if( $oDaoMovimentacao->erro_status == 0 ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoMovimentacao->erro_msg;
      throw new DBException(  _M( MovimentacaoFichaAtendimento::MENSAGEM . "erro_salvar_movimentacao", $oErro) );
    }
  }

  /**
   * Getter código da movimentacao
   * @return int
   */
  public function getCodigo () {
    return $this->iCodigo;
  }

  /**
   * Setter Ficha de atendimento
   * @param integer
   */
  public function setFichaAtendimento ($iFichaAtendimento) {
    $this->iFichaAtendimento = $iFichaAtendimento;
  }

  /**
   * Getter Ficha de atendimento
   * @return int
   */
  public function getFichaAtendimento () {
    return $this->iFichaAtendimento;
  }

  /**
   * Setter o usuário do sistema
   * @param UsuarioSistema
   */
  public function setUsuarioSistema (UsuarioSistema $oUsuarioSistema) {
    $this->oUsuarioSistema = $oUsuarioSistema;
  }

  /**
   * Getter o usuário do sistema
   * @return UsuarioSistema
   */
  public function getUsuarioSistema () {
    return $this->oUsuarioSistema;
  }

 /**
  * Setter setor do ambulatorio
  * @param SetorAmbulatorial
  */
  public function setSetorAmbulatorial (SetorAmbulatorial $oSetorAmbulatorial) {
    $this->oSetorAmbulatorial = $oSetorAmbulatorial;
  }

  /**
   * Getter setor do ambulatorio
   * @return SetorAmbulatorial
   */
  public function getSetorAmbulatorial() {
    return $this->oSetorAmbulatorial;
  }

  /**
   * Setter data de inclusão da movimentação
   * @param DBDate
   */
  public function setData (DBDate $oData) {
    $this->oData = $oData;
  }

  /**
   * Getter data de inclusão da movimentação
   * @return DBDate
   */
  public function getData () {
    return $this->oData;
  }

  /**
   * Setter hora de inclusao da movimentacao
   * @param string
   */
  public function setHora ($sHora) {
    $this->sHora = $sHora;
  }

  /**
   * Getter hora de inclusao da movimentacao
   * @return string
   */
  public function getHora () {
    return $this->sHora;
  }

  /**
   * Setter situacao
   * @param string
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }

  /**
   * Getter situacao
   * @return string
   */
  public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * Setter observação da movimentação
   * @param string
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Getter observação da movimentação
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Getter nome da situacao
   * @return string
   */
  public function getDescricaoSituacao () {

    if (!empty($this->iCodigo) ) {
      return self::$aSituacoes[$this->iSituacao];
    }

    return null;
  }
}