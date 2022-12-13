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
 * Horários de funcionamento da escola para cada turno
 * @package   educacao
 * @author    Andrio Costa - andrio.costa@dbseller.com.br
 * @version   $Revision: 1.2 $
 */
class HorarioEscola {

  const MSG_HORARIOESCOLA = "educacao.escola.HorarioEscola.";
  /**
   * Código
   * @var integer
   */
  private $iCodigo;

  /**
   * Código do turno de referencia onde:
   * 1 = MANHA
   * 2 = TARDE
   * 3 = NOITE
   * @var integer
   */
  private $iTurno;

  private $aTurnos = array(1 => "MANHÃ", 2 => "TARDE", 3 => "NOITE");

  /**
   * Escola vinculada
   * @var Escola
   */
  private $oEscola;

  /**
   * Hora inicial de funcionamento da escola no turno
   * @var timestamp
   */
  private $iHoraInicio;

  /**
   * Hora final de funcionamento da escola no turno
   * @var timestamp
   */
  private $iHoraFinal;


  public function __construct( $iCodigo = null ) {

    if (empty($iCodigo)) {
      return true;
    }

    $oDaoHorarioEscola = new cl_horarioescola();
    $rsHorarioEscola   = $oDaoHorarioEscola->sql_record( $oDaoHorarioEscola->sql_query_file($iCodigo) );

    if ($rsHorarioEscola && $oDaoHorarioEscola->numrows > 0) {

      $oDados            = db_utils::fieldsMemory($rsHorarioEscola, 0);
      $this->iCodigo     = $oDados->ed123_sequencial;
      $this->iTurno      = $oDados->ed123_turnoreferencia;
      $this->oEscola     = EscolaRepository::getEscolaByCodigo($oDados->ed123_escola);
      $this->iHoraInicio = $oDados->ed123_horainicio;
      $this->iHoraFinal  = $oDados->ed123_horafim;
    }
  }

  /**
   * Getter código
   * @return integer
   */
  public function getCodigo () {
    return $this->iCodigo;
  }


  /**
   * Setter turno
   * @throws Exception
   * @param integer
   */
  public function setTurno ($iTurno) {

    if (!array_key_exists($iTurno, $this->aTurnos)) {
      throw new Exception(_M(self::MSG_HORARIOESCOLA ."turno_inexistente"));
    }
    $this->iTurno = $iTurno;
  }

  /**
   * Getter turno
   * @return integer
   */
  public function getTurno () {
    return $this->iTurno;
  }



  /**
   * Setter Escola
   * @param Escola
   */
  public function setEscola ($oEscola) {
    $this->oEscola = $oEscola;
  }

  /**
   * Getter Escola
   * @return Escola
   */
  public function getEscola () {
    return $this->oEscola;
  }

  /**
   * Setter hora de inicio no formato 00:00
   * @param string
   */
  public function setHoraInicio ($sHora) {

    $this->iHoraInicio = $this->getTime($sHora);
  }

  /**
   * Getter hora de inicio
   * @param  $lFormatar se deve retornar valor formatado
   * @return string|timestamp
   */
  public function getHoraInicio ($lFormatar = false) {

    if ($lFormatar) {
      return date('H:i', $this->iHoraInicio);
    }
    return $this->iHoraInicio;
  }

  /**
   * Setter hora final no formato 00:00
   * @param string
   */
  public function setHoraFinal ($sHora) {

    $this->iHoraFinal = $this->getTime($sHora);
  }

  /**
   * Getter hora final
   * @param  $lFormatar se deve retornar valor formatado
   * @return string|timestamp
   */
  public function getHoraFinal ($lFormatar = false) {


    if ($lFormatar) {
      return date('H:i', $this->iHoraFinal);
    }
    return $this->iHoraFinal;
  }

  /**
   * Converte a hora informada em um timestamp
   * @param  string  $sHora hora no formato 00:00
   * @return integer        timestamp
   */
  private function getTime( $sHora ) {

    $aHora = explode(":", $sHora);
    return mktime($aHora[0], $aHora[1]);
  }

  /**
   * Salva ou altera um horário da escola
   * @throws DBException
   * @throws BusinessException
   * @return boolean
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException(_M(self::MSG_HORARIOESCOLA."sem_transacao"));
    }

    $oDaoHorarioEscola                        = new cl_horarioescola();
    $oDaoHorarioEscola->ed123_sequencial      = null;
    $oDaoHorarioEscola->ed123_turnoreferencia = $this->iTurno;
    $oDaoHorarioEscola->ed123_escola          = $this->oEscola->getCodigo();
    $oDaoHorarioEscola->ed123_horainicio      = $this->getHoraInicio(true);
    $oDaoHorarioEscola->ed123_horafim         = $this->getHoraFinal(true);

    $sWhere  = " ed123_turnoreferencia = {$this->iTurno} ";
    $sWhere .= " and ed123_escola = {$this->oEscola->getCodigo()} ";

    $sSqlValida = $oDaoHorarioEscola->sql_query_file(null, "1", null, $sWhere);
    $rsValida   = $oDaoHorarioEscola->sql_record($sSqlValida);

    if ($rsValida && $oDaoHorarioEscola->numrows > 0 && empty($this->iCodigo) ) {

      $oMsgErro         = new stdClass();
      $oMsgErro->escola = $this->oEscola->getNome();
      throw new BusinessException( _M(self::MSG_HORARIOESCOLA . "escola_possui_horario_turno", $oMsgErro) );
    }

    if ( !empty($this->iCodigo) ) {

      $oDaoHorarioEscola->ed123_sequencial = $this->iCodigo;
      $oDaoHorarioEscola->alterar( $this->iCodigo );
    } else {
      $oDaoHorarioEscola->incluir(null);
    }

    if ($oDaoHorarioEscola->erro_status == 0) {
      throw new DBException(_M(self::MSG_HORARIOESCOLA. "erro_salvar"));
    }

    return true;
  }

  /**
   * Remove o horáio da escola
   * @throws DBException
   * @throws BusinessException
   * @return boolea
   */
  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new DBException(_M(self::MSG_HORARIOESCOLA."sem_transacao"));
    }

    if ( empty($this->iCodigo) ) {
      throw new BusinessException( _M( self::MSG_HORARIOESCOLA . "sem_instancia"));
    }
    $oDaoHorarioEscola = new cl_horarioescola();
    $oDaoHorarioEscola->ed123_sequencial = $this->iCodigo;
    $oDaoHorarioEscola->excluir( $this->iCodigo );

    if ($oDaoHorarioEscola->erro_status == 0) {
      throw new DBException(_M(self::MSG_HORARIOESCOLA. "erro_excluir"));
    }

    return true;
  }
}