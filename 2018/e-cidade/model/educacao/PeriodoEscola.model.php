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

define( 'MENSAGEM_PERIODOESCOLA_MODEL', 'educacao.escola.PeriodoEscola.' );

/**
 * Representa��o de um per�odo na escola
 *
 * @package    Educacao
 * @author     Andrio Costa - andrio.costa@dbseller.com.br
 * @version    $Revision: 1.8 $
 */
class PeriodoEscola {

  /**
   * C�digo sequencial de periodoescola
   * @var integer
   */
  private $iCodigo;

  /**
   * Inst�ncia de escola
   * @var Escola
   */
  private $oEscola;

  /**
   * Inst�ncia do Turno
   * @var Turno
   */
  private $oTurno;

  /**
   * C�digo do per�odo de aula
   * @var integer
   */
  private $iPeriodoAula;

  /**
   * Hora de inicio do per�odo
   * @var string
   */
  private $sHoraInicio;

  /**
   * Hora final do per�odo
   * @var string
   */
  private $sHoraFim;

  /**
   * Descri��o do per�odo
   * @var string
   */
  private $sDescricao;

  /**
   * Ordem do per�odo
   * @var integer
   */
  private $iOrdem;

  /**
   * Dura��o do per�odo
   * @var string
   */
  private $sDuracao;

  /**
   * Turno Referente ao periodo
   * @var array
   */
  private $aTurnoReferentePeriodo = array();
  /**
   * Constutor
   * @param int $iCodigo
   * @return bool
   * @throws DBException
   */
  public function __construct ( $iCodigo = null ) {

    if ( empty($iCodigo) ) {
      return true;
    }

    $sCampos  = "ed17_i_codigo, ed17_i_escola, ed17_i_turno, ed17_i_periodoaula, ed17_h_inicio, ed17_h_fim";
    $sCampos .= ", ed08_c_descr, ed08_i_sequencia, ed17_duracao";

    $oDaoPeriodoEscola = new cl_periodoescola();
    $sSqlPeriodo       = $oDaoPeriodoEscola->sql_query($iCodigo, $sCampos);
    $rsPeriodo         = db_query($sSqlPeriodo);

    if ( !$rsPeriodo ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( MENSAGEM_PERIODOESCOLA_MODEL . 'erro_buscar_periodo', $oErro ) );
    }

    if ( pg_num_rows($rsPeriodo) > 0 ) {

      $oDados             = db_utils::fieldsMemory( $rsPeriodo, 0 );
      $this->iCodigo      = $oDados->ed17_i_codigo;
      $this->oEscola      = EscolaRepository::getEscolaByCodigo( $oDados->ed17_i_escola );
      $this->oTurno       = TurnoRepository::getTurnoByCodigo( $oDados->ed17_i_turno );
      $this->iPeriodoAula = $oDados->ed17_i_periodoaula;
      $this->sHoraInicio  = $oDados->ed17_h_inicio;
      $this->sHoraFim     = $oDados->ed17_h_fim;
      $this->sDescricao   = $oDados->ed08_c_descr;
      $this->iOrdem       = $oDados->ed08_i_sequencia;
      $this->sDuracao     = $oDados->ed17_duracao;
    }
  }

  /**
   * Retorna o c�digo do per�odo na escola
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Seta a ordem do per�odo
   * @param integer $iOrdem
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Retorna a ordem do per�odo
   * @return integer
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Seta o c�digo do per�odo de aula
   * @param integer $iPeriodoAula
   */
  public function setPeriodoAula($iPeriodoAula) {
    $this->iPeriodoAula = $iPeriodoAula;
  }

  /**
   * Retorna o c�digo do per�odo de aula
   * @return integer
   */
  public function getPeriodoAula() {
    return $this->iPeriodoAula;
  }

  /**
   * Seta uma inst�ncia de Escola
   * @param Escola $oEscola
   */
  public function setEscola( Escola $oEscola ) {
    $this->oEscola = $oEscola;
  }

  /**
   * Retorna uma inst�ncia de Escola
   * @return Escola
   */
  public function getEscola() {
    return $this->oEscola;
  }

  /**
   * Seta uma inst�ncia do Turno
   * @param Turno $oTurno
   */
  public function setTurno(Turno $oTurno) {
    $this->oTurno = $oTurno;
  }

  /**
   * Retorna uma inst�ncia do Turno
   * @return Turno
   */
  public function getTurno() {
    return $this->oTurno;
  }

  /**
   * Atribui uma Descri��o para o per�odo
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna a Descri��o do per�odo
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta o hor�rio de encerramento do per�odo
   * @param string $sHoraFim
   */
  public function setHoraFim($sHoraFim) {
    $this->sHoraFim = $sHoraFim;
  }

  /**
   * Retorna o hor�rio de encerramento do per�odo
   * @return string
   */
  public function getHoraFim() {
    return $this->sHoraFim;
  }

  /**
   * Seta o hor�rio de in�cio do per�odo
   * @param string $sHoraInicio
   */
  public function setHoraInicio($sHoraInicio) {
    $this->sHoraInicio = $sHoraInicio;
  }

  /**
   * Retorna o hor�rio de in�cio do per�odo
   * @return string
   */
  public function getHoraInicio() {
    return $this->sHoraInicio;
  }

  /**
   * Retorna a dura��o do per�odo
   * @return string '00:00'
   */
  public function getDuracao() {
    return $this->sDuracao;
  }

  /**
   * Seta a dura��o do per�odo
   * @param string $sDuracao
   */
  public function setDuracao( $sDuracao ) {
    $this->sDuracao = $sDuracao;
  }

  /**
   * Salva as informa��es referentes a periodoescola
   * @throws DBException
   */
  public function salvar() {

    if( !db_utils::inTransaction() ) {
      throw new DBException( _M( MENSAGEM_PERIODOESCOLA_MODEL . 'sem_transacao' ) );
    }


    $oDaoPeriodoEscola                     = new cl_periodoescola();
    $oDaoPeriodoEscola->ed17_i_escola      = $this->oEscola->getCodigo();
    $oDaoPeriodoEscola->ed17_i_turno       = $this->oTurno->getCodigoTurno();
    $oDaoPeriodoEscola->ed17_i_periodoaula = $this->iPeriodoAula;
    $oDaoPeriodoEscola->ed17_h_inicio      = $this->sHoraInicio;
    $oDaoPeriodoEscola->ed17_h_fim         = $this->sHoraFim;
    $oDaoPeriodoEscola->ed17_duracao       = $this->sDuracao;

    if ( empty($this->iCodigo) ) {
      $oDaoPeriodoEscola->incluir(null);
    } else {

      $oDaoPeriodoEscola->ed17_i_codigo = $this->iCodigo;
      $oDaoPeriodoEscola->alterar($this->iCodigo);
    }


    if( $oDaoPeriodoEscola->erro_status == '0' ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoPeriodoEscola->erro_msg;
      throw new DBException( _M( MENSAGEM_PERIODOESCOLA_MODEL . 'erro_salvar', $oErro ) );
    }



    $oDaoPeriodoEscolaTurnoReferente = new cl_periodoescolaturnoreferente();
    $oDaoPeriodoEscolaTurnoReferente->excluir(null, "ed143_periodoescola = {$oDaoPeriodoEscola->ed17_i_codigo}");

    if ( $oDaoPeriodoEscolaTurnoReferente->erro_status == 0 ) {
      throw new DBException( "Erro ao excluir a refer�ncia do turno do per�do da escola." );
    }

    foreach ($this->aTurnoReferentePeriodo as $iTurnoReferentePeriodo ) {

      $oDaoPeriodoEscolaTurnoReferente->ed143_sequencial     = null;
      $oDaoPeriodoEscolaTurnoReferente->ed143_periodoescola  = $oDaoPeriodoEscola->ed17_i_codigo;
      $oDaoPeriodoEscolaTurnoReferente->ed143_turnoreferente = $this->oTurno->getCodigoTurnoReferente($iTurnoReferentePeriodo);
      $oDaoPeriodoEscolaTurnoReferente->incluir(null);

      if($oDaoPeriodoEscolaTurnoReferente->erro_status == 0) {
        throw new DBException("Erro ao incluir a refer�ncia do turno do per�do da escola.");
      }

    }
  }

  /**
   * Remove o Per�odo da Escola caso o mesmo n�o possua nenhum v�nculo
   * @throws DBException
   */
  public function remover() {

    if ( empty($this->iCodigo) ) {
      return;
    }

    if( !db_utils::inTransaction() ) {
      throw new DBException( _M( MENSAGEM_PERIODOESCOLA_MODEL . 'sem_transacao' ) );
    }

    $sCampo  = "  (select distinct 1                                                                          ";
    $sCampo .= "     from rechumanohoradisp                                                                   ";
    $sCampo .= "    where rechumanohoradisp.ed33_i_periodo       = ed17_i_codigo) as rechumanohoradisp        ";
    $sCampo .= " ,(select distinct 1                                                                          ";
    $sCampo .= "     from regenciahorario                                                                     ";
    $sCampo .= "    where regenciahorario.ed58_i_periodo         = ed17_i_codigo) as regenciahorario          ";
    $sCampo .= " ,(select distinct 1                                                                          ";
    $sCampo .= "     from regenciahorariohistorico                                                            ";
    $sCampo .= "    where regenciahorariohistorico.ed323_periodo = ed17_i_codigo) as regenciahorariohistorico ";
    $sCampo .= " ,(select distinct 1                                                                          ";
    $sCampo .= "     from turmaachorario                                                                      ";
    $sCampo .= "    where turmaachorario.ed270_i_periodo         = ed17_i_codigo) as turmaachorario           ";

    $oDaoPeriodoEscola        = new cl_periodoescola();
    $sWhereVinculos           = "ed17_i_codigo = {$this->iCodigo} ";
    $sSqlVinculoPeriodoEscola = $oDaoPeriodoEscola->sql_query_file( null, $sCampo, null, $sWhereVinculos );
    $rsVinculoPeriodoEscola   = db_query( $sSqlVinculoPeriodoEscola );

    if ( pg_num_rows( $rsVinculoPeriodoEscola ) > 0 ) {

      $oVinculos       = db_utils::fieldsMemory( $rsVinculoPeriodoEscola, 0);
      $oErro           = new stdClass();
      $oErro->sPeriodo = $this->getDescricao();
      if ( !empty($oVinculos->rechumanohoradisp) ) {
        throw new DBException( _M( MENSAGEM_PERIODOESCOLA_MODEL . 'periodoescola_possui_com_professor', $oErro ) );
      }

      if ( !empty($oVinculos->regenciahorario) ) {
        throw new DBException( _M( MENSAGEM_PERIODOESCOLA_MODEL . 'periodoescola_vinculado_turmas', $oErro ) );
      }

      if ( !empty($oVinculos->regenciahorariohistorico) ) {
        throw new DBException( _M( MENSAGEM_PERIODOESCOLA_MODEL . 'periodoescola_possui_historico_turmas', $oErro ) );
      }

      if ( !empty($oVinculos->turmaachorario) ) {
        throw new DBException( _M( MENSAGEM_PERIODOESCOLA_MODEL . 'periodoescola_vinculado_turmasac', $oErro ) );
      }

    }

    $oDaoPeriodoEscolaTurnoReferente = new cl_periodoescolaturnoreferente();
    $oDaoPeriodoEscolaTurnoReferente->excluir(null, "ed143_periodoescola = {$this->iCodigo}");

    if ( $oDaoPeriodoEscolaTurnoReferente->erro_status == 0 ) {
      throw new DBException( "Erro ao excluir a refer�ncia do turno do per�odo da escola." );
    }

    $oDaoPeriodoEscola->excluir( $this->iCodigo );

    if ( $oDaoPeriodoEscola->erro_status == 0 ) {

      $oErro        = new stdClass();
      $oErro->sErro = $oDaoPeriodoEscola->erro_msg;
      throw new DBException( _M( MENSAGEM_PERIODOESCOLA_MODEL . 'erro_remover', $oErro) );
    }
  }

  public function setTurnoReferentePeriodo($aTurnoReferentePeriodo) {
    $this->aTurnoReferentePeriodo = $aTurnoReferentePeriodo;
  }

  public function getTurnoReferentePeriodo() {

    if ( count($this->aTurnoReferentePeriodo) > 0 ) {
      return $this->aTurnoReferentePeriodo;
    }

    $oDaoPeriodoEscolaTurnoReferente = new cl_periodoescolaturnoreferente();
    $sWherePeriodoEscola             = "ed143_periodoescola = {$this->iCodigo}";
    $sSqlPeriodoEscolaTurnoReferente = $oDaoPeriodoEscolaTurnoReferente->sql_query(null, "ed231_i_referencia", null, $sWherePeriodoEscola);
    $rsPeriodoEscolaTurnoReferente   = db_query($sSqlPeriodoEscolaTurnoReferente);

    if ( !$rsPeriodoEscolaTurnoReferente ) {
      throw new DBException("Erro ao buscar a(s) refer�ncia(s) do turno do per�odo da escola.");
    }

    for ( $iTurnosReferentes = 0; $iTurnosReferentes < pg_num_rows( $rsPeriodoEscolaTurnoReferente ); $iTurnosReferentes ++ ) {

      $iReferencia = db_utils::fieldsMemory($rsPeriodoEscolaTurnoReferente, $iTurnosReferentes)->ed231_i_referencia;
      $this->aTurnoReferentePeriodo[] = $iReferencia;
    }

    return $this->aTurnoReferentePeriodo;
  }
}