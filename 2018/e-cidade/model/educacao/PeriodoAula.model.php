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
 * Representação de um período de aula
 *
 * @package    Educacao
 * @author     Andrio Costa - andrio.costa@dbseller.com.br
 * @version    $Revision: 1.3 $
 */
class PeriodoAula {

  /**
   * Periodo da escola
   * @var PeriodoEscola
   */
  private $oPeriodoEscola;

  /**
   * Dia da semana no padrão da ISO ISO-8601
   * @var int
   */
  private $iDiaSemana;

    /**
   * Regência do período
   * @var Regencia
   */
  private $oRegencia;

  /**
   * Código do período
   * @var integer código pk
   */
  private $iCodigo;

  /**
   * Código do rechumano
   * @var integer
   */
  private $iRegente;

  /**
   * Data inicial em que o regente iniciou a regencia do período
   * @var DBDate
   */
  private $oDataInicio;

  /**
   * Data final em que o regente encerra a regencia do período
   * @var DBDate
   */
  private $oDataFim;

  private $lAtivo = false;

  /**
   * Tipo de vínculo
   * 1 - Vincular professor discisplina
   * 2 - Criar grade de horário
   * @var integer
   */
  private $iTipoVinculo;

  private $sMessagens = "educacao.escola.PeriodoAula.";

  public function __construct() {

  }

  /**
   * Atribui o dia da semanna no padrão da ISO ISO-8601
   * @param int $iDiaSemana
   */
  public function setDiaSemana($iDiaSemana) {
    $this->iDiaSemana = $iDiaSemana;
  }

  /**
   * Retorna  o dia da semanna no padrão da ISO ISO-8601
   * @return int
   */
  public function getDiaSemana() {
    return $this->iDiaSemana;
  }


  /**
   * Retona a disciplina para o Período
   * @return Disciplina
   */
  public function getDisciplina() {
    return $this->oRegencia->getDisciplina();
  }

  /**
   * Atribui o Periodo
   * @param PeriodoEscola $oPeriodoEscola
   */
  public function setPeriodoEscola($oPeriodoEscola) {
    $this->oPeriodoEscola = $oPeriodoEscola;
  }

  /**
   * Retorna o período
   * @return PeriodoEscola
   */
  public function getPeriodoEscola() {
    return $this->oPeriodoEscola;
  }

  /**
   * Define o código do período
   * @param integer
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * Define a regencia
   * @param Regencia
   */
  public function setRegencia (Regencia $oRegencia) {
    $this->oRegencia = $oRegencia;
  }

  /**
   * Define o código do rechumano
   * @param integer
   */
  public function setRegente($iRegente) {
    $this->iRegente = $iRegente;
  }

  /**
   *  Define a data inicial em que o regente iniciou a regencia do período
   * @param DBDate
   */
  public function setDataInicio(DBDate $oDataInicio) {
    $this->oDataInicio = $oDataInicio;
  }

  /**
   * Define a data final em que o regente encerra a regencia do período
   * @param DBDate
   */
  public function setDataFim(DBDate $oDataFim) {
    $this->oDataFim = $oDataFim;
  }

  /**
   * Código do período
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a regencia
   * @return Regencia
   */
  public function getRegencia () {
    return $this->oRegencia;
  }

  /**
   * Código do rechumano
   * @return integer
   */
  public function getRegente() {
    return $this->iRegente;
  }

  /**
   * Data inicial em que o regente iniciou a regencia do período
   * @return DBDate
   */
  public function getDataInicio() {
    return $this->oDataInicio;
  }

  /**
   * Data final em que o regente encerra a regencia do período
   * @return DBDate
   */
  public function getDataFim() {
    return $this->oDataFim;
  }


  /**
   * Setter ativo
   * @param boolean
   */
  public function setAtivo($lAtivo) {
    $this->lAtivo = $lAtivo;
  }

  /**
   * verifica se esta ativo o periodo de aula
   * @return boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }


  /**
   * Setter tipo de vínculo
   * @param integer
   */
  public function setTipoVinculo ($iTipo) {
    $this->iTipoVinculo = $iTipo;
  }

  /**
   * Getter tipo de vínculo
   * @param integer
   */
  public function getTipoVinculo () {
    return $this->iTipoVinculo;
  }



  public function inativarAte(DBDate $oData) {

    $oMsgErro = new stdClass();
    $oMsgErro->dataInicio = $this->oDataInicio->convertTo(DBDate::DATA_PTBR);

    if ($oData->getTimeStamp() < $this->oDataInicio->getTimeStamp()) {
      throw new Exception(_M($this->sMessagens . "data_final_menor_que_inicio", $oMsgErro));
    }

    $oCalendario              = $this->oRegencia->getTurma()->getCalendario();
    $oMsgErro->dataCalendario = $oCalendario->getDataFinal()->convertTo(DBDate::DATA_PTBR);
    if ( $oData->getTimeStamp() > $oCalendario->getDataFinal()->getTimeStamp() ) {
      throw new Exception(_M($this->sMessagens . "data_final_maior_que_data_calendario", $oMsgErro));
    }

    $this->setDataFim($oData);
    $this->setAtivo(false);

    $this->salvar();
  }

  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new Exception(_M($this->sMessagens, "erro_transacao_bd"));
    }
    $oDaoRegenciaHorario = new cl_regenciahorario();

    $oDaoRegenciaHorario->ed58_i_codigo    = $this->iCodigo;
    $oDaoRegenciaHorario->ed58_i_regencia  = $this->oRegencia->getCodigo();
    $oDaoRegenciaHorario->ed58_i_diasemana = $this->iDiaSemana + 1;
    $oDaoRegenciaHorario->ed58_i_periodo   = $this->oPeriodoEscola->getCodigo();
    $oDaoRegenciaHorario->ed58_i_rechumano = $this->iRegente;
    $oDaoRegenciaHorario->ed58_ativo       = $this->lAtivo ? "true" : "false";
    $oDaoRegenciaHorario->ed58_tipovinculo = $this->iTipoVinculo;
    $oDaoRegenciaHorario->ed58_datainicio  = $this->oDataInicio->getDate();
    $oDaoRegenciaHorario->ed58_datafim     = $this->oDataFim->getDate();

    if ( empty($this->iCodigo) )  {
      $oDaoRegenciaHorario->incluir(null);
    } else {
      $oDaoRegenciaHorario->alterar($this->iCodigo);
    }

    if ($oDaoRegenciaHorario->erro_status == 0 ) {
      throw new Exception( _M($this->sMessagens, "erro_salvar") );
    }
  }


  /**
   * @todo   não foi visto os professores substituto.
   * @return [type] [description]
   */
  public function remover() {

    if (!db_utils::inTransaction()) {
      throw new Exception(_M($this->sMessagens, "erro_transacao_bd"));
    }

    $this->removerVinculoLancamentoFalta();

    $oDaoRegenciaHorario = new cl_regenciahorario();
    $oDaoRegenciaHorario->excluir($this->iCodigo, null);

    if ($oDaoRegenciaHorario->erro_status == 0) {
      throw new DBException( _M($this->sMessagens . "erro_remover_regencia_horario") );
    }

    return true;
  }



  /**
   * Remove os vínculos do período com o lançamento de frequência / conteúdo
   *   DB:EDUCAÇÃO > Escola > Procedimentos > Diário de Classe > Lançamentos - Frequência/Conteúdo
   */
  public function removerVinculoLancamentoFalta() {

    $this->removerOcorrenciaFalta();
    $this->removerFaltas();
    $this->removerVinculoPeriodoAulaDesenvolvida();
    $this->removerAulasDesenvolvidas();
  }

  /**
   * Remove as ocorrencias de falta
   * @throws DBException
   * @return boolean
   */
  private function removerOcorrenciaFalta() {

    $sSql  = " select ed301_sequencial ";
    $sSql .= "    from diarioclassealunofalta ";
    $sSql .= "    join diarioclasseregenciahorario on diarioclassealunofalta.ed301_diarioclasseregenciahorario = diarioclasseregenciahorario.ed302_sequencial ";
    $sSql .= "   where diarioclasseregenciahorario.ed302_regenciahorario = {$this->iCodigo} ";

    $sWhere = " ed104_diarioclassealunofalta in ( {$sSql} ) " ;
    $oDao   = new cl_ocorrenciafalta();
    $oDao->excluir(null, $sWhere);

    if ($oDao->erro_status == 0) {
      throw new DBException( _M($this->sMessagens . "erro_remover_ocorrencia") );
    }

    return true;
  }

  /**
   * Remove as faltas lançadas
   * @throws DBException
   * @return boolean
   */
  private function removerFaltas() {

    $sSql = " select ed302_sequencial from diarioclasseregenciahorario where ed302_regenciahorario = {$this->iCodigo}";

    $sWhere = "ed301_diarioclasseregenciahorario in ( {$sSql} )";
    $oDao   = new cl_diarioclassealunofalta();
    $oDao->excluir(null, $sWhere);

    if ($oDao->erro_status == 0) {
      throw new DBException( _M($this->sMessagens . "erro_remover_falta") );
    }

    return true;
  }

  /**
   * Remove as aulas desenvolvidas
   * @throws DBException
   * @return boolean
   */
  private function removerAulasDesenvolvidas() {

    $oDaoDiarioClasseRegenciaHorario   = new cl_diarioclasseregenciahorario();
    $sSql   = $oDaoDiarioClasseRegenciaHorario->sql_query_file(null, "ed302_diarioclasse", null, "ed302_regenciahorario = {$this->iCodigo}");
    $rsDias = db_query($sSql);

    if (!$rsDias) {
      throw new DBException( _M($this->sMessagens . "falha_buscar_dias") );
    }

    if ( pg_num_rows($rsDias) == 0 ) {
      return;
    }

    $iLinhas = pg_num_rows($rsDias);
    for ( $i = 0; $i < $iLinhas; $i++ ) {

      $iCodigoDiaDiario = db_utils::fieldsMemory($rsDias, $i)->ed302_diarioclasse;

      /**
       * Valida se pode remover o dia. Ele pode estar vínculado a mais de um período
       */
      $sSqlValida = $oDaoDiarioClasseRegenciaHorario->sql_query_file(null, "1", null, " ed302_diarioclasse = {$iCodigoDiaDiario} ");
      $rsValida   = db_query($sSqlValida);
      if ( !$rsValida ) {
        throw new DBException( _M($this->sMessagens . "falha_buscar_dias") );
      }

      if ( pg_num_rows($rsValida) > 1 ) {
        continue;
      }

      $oDaoDiarioClasse   = new cl_diarioclasse();
      $oDaoDiarioClasse->excluir($iCodigoDiaDiario);

      if ($oDaoDiarioClasse->erro_status == 0) {
        throw new DBException( _M($this->sMessagens . "erro_remover_aulas") );
      }
    }

    return true;
  }

  /**
   * Remove vínculo da regenciahorario com as aulas desenvolvidas
   * @throws DBException
   * @return boolean
   */
  private function removerVinculoPeriodoAulaDesenvolvida() {

    $oDao = new cl_diarioclasseregenciahorario();
    $oDao->excluir(null, " ed302_regenciahorario = {$this->iCodigo} ");

    if ($oDao->erro_status == 0) {
      throw new DBException( _M($this->sMessagens . "erro_remover_vinculo_aulas_desenvolvidas") );
    }

    return true;
  }
}