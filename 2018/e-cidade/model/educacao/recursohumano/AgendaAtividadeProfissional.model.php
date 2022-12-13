<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 * Representa uma agenda de uma Atividade do profissional na escola
 * @package    educacao
 * @subpackage recursohumano
 * @author     Andrio Costa - andrio.costa@dbseller.com.br
 * @version    $Revision: 1.3 $
 */
class AgendaAtividadeProfissional {

  const MSG_AGENDAATIVIDADEPROFISSIONAL = "educacao.escola.AgendaAtividadeProfissional.";

  private $iCodigo = null;

  /**
   * @var TipoHoraTrabalho
   */
  private $oTipoHoraTrabalho;

  /**
   * @var AtividadeProfissionalEscola
   */
  private $oAtividadeProfissional;

  /**
   * Código do dia da semana dentro no ecidade nos módulos da educação.
   *  -- referente a tabela diasemana
   * @var [type]
   */
  private $iDiaSemana;
  private $iTurnoReferente;
  private $sHoraInicio;
  private $sHoraFim;

  static $aTurnos = array(1 => "Manhã", 2 => "Tarde", 3 => "Noite");

  function __construct( $iCodigo = null ) {

    if ( empty($iCodigo) ) {
      return true;
    }

    $oDao = new cl_agendaatividade();
    $sSql = $oDao->sql_query_file($iCodigo);
    $rs   = db_query($sSql);
    if (!$rs) {

      $oMsgErro->sErro = pg_last_error();
      throw new DBException( _M(self::MSG_ATIVIDADEPROFISSIONALESCOLA . "erro_buscar_agenda", $oMsgErro) );
    }

    if (pg_num_rows($rs) == 0) {
      return true;
    }

    $oDados                       = db_utils::fieldsMemory($rs, 0);
    $this->iCodigo                = $oDados->ed129_codigo;
    $this->oTipoHoraTrabalho      = TipoHoraTrabalhoRepository::getByCodigo( $oDados->ed129_tipohoratrabalho );
    $this->iDiaSemana             = $oDados->ed129_diasemana;
    $this->iTurnoReferente        = $oDados->ed129_turno;
    $this->oAtividadeProfissional = AtividadeProfissionalEscolaRepository::getByCodigo( $oDados->ed129_rechumanoativ );
    $this->sHoraInicio            = $oDados->ed129_horainicio;
    $this->sHoraFim               = $oDados->ed129_horafim;

  }

  /**
   * Getter código
   * @param integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }


  /**
   * Setter tipo de hora de trabalho
   * @param TipoHoraTrabalho
   */
  public function setTipoHoraTrabalho(TipoHoraTrabalho $oTipoHoraTrabalho) {
    $this->oTipoHoraTrabalho = $oTipoHoraTrabalho;
  }

  /**
   * Getter tipo de hora de trabalho
   * @param TipoHoraTrabalho
   */
  public function getTipoHoraTrabalho() {
    return $this->oTipoHoraTrabalho;
  }


  /**
   * Setter codigo dia semana
   * @param integer
   */
  public function setDiaSemana ($iDiaSemana) {
    $this->iDiaSemana = $iDiaSemana;
  }

  /**
   * Getter codigo dia semana
   *  -- código interno do ecidade na escola ( tabelha diasemana)
   * @param integer
   */
  public function getDiaSemana() {
    return $this->iDiaSemana;
  }

  /**
   * Setter turno referente
   * @param integer
   */
  public function setTurnoReferente ($iTurnoReferente) {
    $this->iTurnoReferente = $iTurnoReferente;
  }

  /**
   * Getter turno referente
   * @param integer
   */
  public function getTurnoReferente() {
    return $this->iTurnoReferente;
  }


  /**
   * Setter atividade do profissional
   * @param AtividadeProfissionalEscola
   */
  public function setAtividadeProfissional(AtividadeProfissionalEscola $oAtividadeProfissional) {
    $this->oAtividadeProfissional = $oAtividadeProfissional;
  }

  /**
   * Getter atividade do profissional
   * @param AtividadeProfissional
   */
  public function getAtividadeProfissional() {
    return $this->oAtividadeProfissional;
  }


  /**
   * Setter hora de inicio
   * @param string
   */
  public function setHoraInicio($sHora) {
    $this->sHoraInicio = $sHora;
  }

  /**
   * Getter hora de inicio
   * @param string
   */
  public function getHoraInicio() {
    return $this->sHoraInicio;
  }

  /**
   * Setter hora de fim
   * @param string
   */
  public function setHoraFim($sHora) {
    $this->sHoraFim = $sHora;
  }

  /**
   * Getter hora de fim
   * @param string
   */
  public function getHoraFim() {
    return $this->sHoraFim;
  }

  /**
   * Retorna o nome do dia da semana
   * @return string
   */
  public function getNomeDiaSemana() {
    return DBDate::getLabelDiaSemana($this->iDiaSemana - 1);
  }

  /**
   * Retorna a descrição do turno referente
   * @return string
   */
  public function getDescricaoTurno() {

    return self::$aTurnos[$this->iTurnoReferente] ;
  }


  /**
   * Salva os dados da Agenda de uma atividade
   * @return boolean
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException( _M(self::MSG_AGENDAATIVIDADEPROFISSIONAL . "sem_transacao", $oErro) );
    }

    $oDaoAgenda = new cl_agendaatividade();

    $oDaoAgenda->ed129_tipohoratrabalho = $this->oTipoHoraTrabalho->getCodigo();
    $oDaoAgenda->ed129_diasemana        = $this->iDiaSemana;
    $oDaoAgenda->ed129_turno            = $this->iTurnoReferente;
    $oDaoAgenda->ed129_rechumanoativ    = $this->oAtividadeProfissional->getCodigo();
    $oDaoAgenda->ed129_horainicio       = $this->sHoraInicio;
    $oDaoAgenda->ed129_horafim          = $this->sHoraFim;
    $oDaoAgenda->ed129_codigo           = $this->iCodigo;

    if ( empty($this->iCodigo) ) {
      $oDaoAgenda->incluir(null);
    } else {
      $oDaoAgenda->alterar($this->iCodigo);
    }


    $oMsgErro = new stdClass();
    if ( $oDaoAgenda->erro_status == 0 ) {

      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M(self::MSG_AGENDAATIVIDADEPROFISSIONAL . "erro_salvar", $oMsgErro) );
    }
    $this->iCodigo = $oDaoAgenda->ed129_codigo;

    return true;
  }

  public function excluir() {

    if (!db_utils::inTransaction()) {
      throw new DBException( _M(self::MSG_AGENDAATIVIDADEPROFISSIONAL . "sem_transacao", $oErro) );
    }


    $oDaoAgenda               = new cl_agendaatividade();
    $oDaoAgenda->ed129_codigo = $this->iCodigo;
    $oDaoAgenda->excluir($this->iCodigo);

    $oMsgErro = new stdClass();
    if ( $oDaoAgenda->erro_status == 0 ) {

      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M(self::MSG_AGENDAATIVIDADEPROFISSIONAL . "erro_excluir", $oMsgErro) );
    }

    AgendaAtividadeProfissionalRepository::removerAgenda($this);

    return true;
  }
}
