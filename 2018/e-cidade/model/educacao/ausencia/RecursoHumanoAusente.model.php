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
 * Representa uma ausencia de um profissional da Educação
 * @package    educacao
 * @subpackage ausencia
 * @author     Andrio Costa <andrio.costa@dbseller.com.br>
 * @version    $Revision: 1.8 $
 */
class RecursoHumanoAusente {

  const MSG_RECURSOHUMANOAUSENTE = 'educacao.escola.RecursoHumanoAusente.';

  /**
   * Código referente a ausência( rechumanoausente )
   * @var integer
   */
  private $iCodigo = null;

  /**
   * Instância de ProfissionalEscola
   * @var ProfissionalEscola
   */
  private $oProfissionalEscola;

  /**
   * Instância de TipoAusencia
   * @var TipoAusencia
   */
  private $oTipoAusencia;

  /**
   * Instância de UsuarioSistema
   * @var UsuarioSistema
   */
  private $oUsuario;

  /**
   * Instância de DBDate referente a data de início da ausência
   * @var DBDate
   */
  private $oInicio;

  /**
   * Instância de DBDate referente a data de fim da ausência
   * @var DBDate
   */
  private $oFinal;

  /**
   * Observação referente a ausência lançada para o rechumano
   * @var string
   */
  private $sObservacao;

  public function __construct( $iCodigo = null) {

    if ( empty($iCodigo) ) {
      return $this;
    }

    $oDaoRecHumanoAusente = new cl_rechumanoausente;
    $sSqlAusencia         = $oDaoRecHumanoAusente->sql_query_file($iCodigo);
    $rsAusencia           = db_query($sSqlAusencia);

    $oMsgErro = new stdClass();
    if ( !$rsAusencia ) {

      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M( self::MSG_RECURSOHUMANOAUSENTE . "erro_executar_query" ));
    }

    $oDadosAusencia = db_utils::fieldsMemory($rsAusencia, 0);
    $oEscola        = EscolaRepository::getEscolaByCodigo($oDadosAusencia->ed348_escola);
    $oDtFinal       = null;
    if ( !empty($oDadosAusencia->ed348_final) ) {
      $oDtFinal = new DBDate($oDadosAusencia->ed348_final);
    }
    $this->iCodigo             = $oDadosAusencia->ed348_sequencial;
    $this->oProfissionalEscola = ProfissionalEscolaRepository::getUltimoVinculoByRecHumanoEscola($oDadosAusencia->ed348_rechumano, $oEscola);
    $this->oTipoAusencia       = new TipoAusencia($oDadosAusencia->ed348_tipoausencia);
    $this->oUsuario            = UsuarioSistemaRepository::getPorCodigo($oDadosAusencia->ed348_usuario);
    $this->oInicio             = new DBDate($oDadosAusencia->ed348_inicio);
    $this->oFinal              = $oDtFinal;
    $this->sObservacao         = $oDadosAusencia->ed348_observacao;
  }

  /**
   * Getter codigo
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Setter profissional da escola
   * @param ProfissionalEscola
   */
  public function setProfissionalEscola(ProfissionalEscola $oProfissionalEscola) {
    $this->oProfissionalEscola = $oProfissionalEscola;
  }

  /**
   * Getter profissional da escola
   * @return ProfissionalEscola
   */
  public function getProfissionalEscola() {
    return $this->oProfissionalEscola;
  }

  /**
   * Setter Tipo Ausencia
   * @param TipoAusencia
   */
  public function setTipoAusencia( TipoAusencia $oTipoAusencia) {
    $this->oTipoAusencia = $oTipoAusencia;
  }

  /**
   * Getter Tipo Ausencia
   * @return TipoAusencia
   */
  public function getTipoAusencia() {
    return $this->oTipoAusencia;
  }

  /**
   * Setter o usuário do sistema
   * @param UsuarioSistema
   */
  public function setUsuarioSistema(UsuarioSistema $oUsuarioSistema) {
    $this->oUsuario = $oUsuarioSistema;
  }

  /**
   * Getter o usuário do sistema
   * @return UsuarioSistema
   */
  public function getUsuarioSistema() {
    return $this->oUsuario;
  }


  /**
   * Setter data de inicio da ausência
   * @param DBDate
   */
  public function setDataInicio(DBDate $oData) {
    $this->oInicio = $oData;
  }

  /**
   * Getter data de inicio da ausência
   * @return DBDate
   */
  public function getDataInicio() {
    return $this->oInicio;
  }

  /**
   * Setter data de fim da ausência
   * @param DBDate|null
   */
  public function setDataFim($oData) {
    $this->oFinal = $oData;
  }

  /**
   * Getter data de fim da ausência
   * @return DBDate
   */
  public function getDataFim() {
    return $this->oFinal;
  }

  /**
   * Setter observação
   * @param string
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }

  /**
   * Getter observação
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * - Primeiramente busca as datas do período da efetividade
   * - Acrescenta as datas da efetividade no formato PT:BR em um array
   * - Verifica se a data de ausência possui data de fim informada. Caso possua, utiliza a mesma, senão,
   *   utiliza como base a última data do período da efetividade
   * - Acrescenta as datas do período de ausência no formato PT:BR em um array
   * - Faz a intersecção das datas, verificando as em comum para contabilizar nas faltas abonadas
   * @param $oDataInicioEfetividade
   * @param $oDataTerminoEfetividade
   * @return int
   * @throws ParameterException
   */
  public function getTotalFaltas( DBDate $oDataInicioEfetividade, DBDate $oDataTerminoEfetividade ) {

    $aDatasEfetividade          = DBDate::getDatasNoIntervalo( $oDataInicioEfetividade, $oDataTerminoEfetividade );
    $aDatasEfetividadeFormatada = array();

    foreach( $aDatasEfetividade as $oDataEfetividade ) {
      $aDatasEfetividadeFormatada[] = $oDataEfetividade->getDate( DBDate::DATA_PTBR );
    }

    $iTotalDatas      = count( $aDatasEfetividadeFormatada );
    $oDataFimAusencia = $this->getDataFim() != null ? $this->getDataFim() : new DBDate($aDatasEfetividadeFormatada[ $iTotalDatas - 1 ]);

    $aDatasAusencia          = DBDate::getDatasNoIntervalo( $this->getDataInicio(), $oDataFimAusencia );
    $aDatasAusenciaFormatada = array();

    foreach( $aDatasAusencia as $oDataAusencia ) {
      $aDatasAusenciaFormatada[] = $oDataAusencia->getDate( DBDate::DATA_PTBR );
    }

    $aDatasEmComum   = array_intersect( $aDatasEfetividadeFormatada, $aDatasAusenciaFormatada );

    return count( $aDatasEmComum );
  }

  /**
   * Salva as informações da ausência da rechumano
   * @return bool
   * @throws BusinessException
   */
  public function salvar() {

    $oDaoRecHumanoAusente                     = new cl_rechumanoausente;
    $oDaoRecHumanoAusente->ed348_sequencial   = $this->iCodigo;
    $oDaoRecHumanoAusente->ed348_rechumano    = $this->oProfissionalEscola->getCodigoProfissional();
    $oDaoRecHumanoAusente->ed348_tipoausencia = $this->oTipoAusencia->getCodigo();
    $oDaoRecHumanoAusente->ed348_usuario      = $this->oUsuario->getCodigo();
    $oDaoRecHumanoAusente->ed348_escola       = $this->oProfissionalEscola->getEscola()->getCodigo();
    $oDaoRecHumanoAusente->ed348_observacao   = $this->sObservacao;
    $oDaoRecHumanoAusente->ed348_inicio       = $this->oInicio->getDate();
    $oDaoRecHumanoAusente->ed348_final        = null;
    if ( !is_null($this->oFinal) ) {
      $oDaoRecHumanoAusente->ed348_final      = $this->oFinal->getDate();
    }

    if ( empty($this->iCodigo) ) {
      $oDaoRecHumanoAusente->incluir( null );
    } else {
      $oDaoRecHumanoAusente->alterar( $this->iCodigo );
    }

    if ( $oDaoRecHumanoAusente->erro_status == 0 ) {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = pg_last_error();
      throw new BusinessException( _M( self::MSG_RECURSOHUMANOAUSENTE . "erro_salvar_ausencia" ) );
    }

    $this->iCodigo = $oDaoRecHumanoAusente->ed348_sequencial;
    return true;
  }

  /**
   * Retorna se o rechumano é um docente
   * @return bool
   */
  public function isDocente() {

    foreach ($this->oProfissionalEscola->getAtividades() as $oAtividadeProfissional ) {

      if ($oAtividadeProfissional->getAtividadeEscolar()->isDocente() ) {
        return true;
      }
    }

    return false;
  }

  /**
   * Exclui a ausência do rechumano
   * @return bool
   * @throws DBException
   */
  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new DBException( _M( self::MSG_RECURSOHUMANOAUSENTE . "sem_transacao" ) );
    }

    $oDaoRecHumanoAusente                     = new cl_rechumanoausente;
    $oDaoRecHumanoAusente->ed348_sequencial   = $this->iCodigo;
    $oDaoRecHumanoAusente->excluir($this->iCodigo);

    if ($oDaoRecHumanoAusente->erro_status == '0') {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = str_replace('\\n', "\n", $oDaoRecHumanoAusente->erro_msg);
      throw new DBException( _M(self::MSG_RECURSOHUMANOAUSENTE . "erro_excluir", $oMsgErro) );
    }
    return true;
  }
}