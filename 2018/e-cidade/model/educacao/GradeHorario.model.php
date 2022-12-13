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
 * Grade de horário da turma
 * @package educacao
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.8 $
 */
class GradeHorario {

  /**
   * Instancia da Turma
   * @var Turma
   */
  private $oTurma;

  /**
   * Instância da Etapa
   * @var Etapa
   */
  private $oEtapa;

  /**
   * Instância do período de aula
   * @var PeriodoAula[]
   */
  private $aPeriodosAula = array();

  private $aLogConflito = array();


  public function __construct( Turma $oTurma, Etapa $oEtapa ) {

    $iTurma = $oTurma->getCodigo();
    $iEtapa = $oEtapa->getCodigo();

    if ( empty($iTurma) || empty($iEtapa) ) {
      throw new ParameterException( "Etapa e turma deve ser informada para montar a grade de horário." );
    }

    $this->oEtapa        = $oEtapa;
    $this->oTurma        = $oTurma;
    $this->aPeriodosAula = $this->buscarPeriodos();
  }

  /**
   * Retorna a Turma
   * @return Turma
   */
  public function getTurma() {
    return $this->oTurma;
  }

  /**
   * Retorna os Periodos de aula da turma e etapa informada
   * @return PeriodoAula[]
   */
  public function getPeriodosAula() {
    return $this->aPeriodosAula;
  }

  /**
   * Retorna a Etapa
   * @return Etapa
   */
  public function getOEtapa() {
    return $this->oEtapa;
  }

  /**
   * Retorna uma estrutura os dias que uma disciplina tem aula de acordo com o Período de avaliação do calendário da turma.
   *
   * @exemple [ aDatas : [ oData : DBDate,
   *                       aPeriodoAula : [PeriodoAula1, PeriodoAula2 ]
   *                    ]
   *          ]
   *
   * @param  Disciplina       $oDisciplina
   * @param  PeriodoAvaliacao $oPeriodoAvaliacao
   * @return $aDiasAula[]
   */
  public function getDiasDeAulaDaDisciplinaNoPeriodoDeAvaliacao(Disciplina $oDisciplina, PeriodoAvaliacao $oPeriodoAvaliacao) {

    $oPeriodoCalendario = $this->oTurma->getCalendario()->getPeriodoCalendarioPorPeriodoAvaliacao($oPeriodoAvaliacao);
    $aDiasSemenaComAula = array();

    $this->aPeriodosAula = $this->buscarPeriodos(false);

    foreach ( $this->aPeriodosAula as $oPeriodoAula ) {

      if ($oPeriodoAula->getDisciplina()->getCodigoDisciplina() != $oDisciplina->getCodigoDisciplina() ) {
        continue;
      }
      $aDiasSemenaComAula[$oPeriodoAula->getDiaSemana()] = $oPeriodoAula->getDiaSemana();
    }

    $aDatasNoIntervalo = DBDate::getDatasNoIntervalo( $oPeriodoCalendario->getDataInicio(), $oPeriodoCalendario->getDataTermino(), $aDiasSemenaComAula );
    foreach ($aDatasNoIntervalo as $key => $oData) {

      $lDataEstaPresente = false;
      foreach ($this->aPeriodosAula as $oPeriodoAula) {

        if ( DBDate::dataEstaNoIntervalo($oData, $oPeriodoAula->getDataInicio(), $oPeriodoAula->getDataFim()) ) {

          $lDataEstaPresente = true;
        }
      }

      if ( !$lDataEstaPresente) {
        unset($aDatasNoIntervalo[$key]);
      }
    }

    $aDiasAula = array();
    foreach ( $aDatasNoIntervalo as $oDiaAula ) {

      $oDia               = new stdClass();
      $oDia->oData        = $oDiaAula;
      $oDia->aPeriodoAula = array();
      foreach ( $this->aPeriodosAula as $oPeriodoAula ) {

        if ($oPeriodoAula->getDisciplina()->getCodigoDisciplina() != $oDisciplina->getCodigoDisciplina() ) {
          continue;
        }

        if (       $oPeriodoAula->getDiaSemana() == $oDiaAula->getDiaSemana()
             &&    DBDate::dataEstaNoIntervalo($oDiaAula, $oPeriodoAula->getDataInicio(), $oPeriodoAula->getDataFim()) ) {
          $oDia->aPeriodoAula[] = $oPeriodoAula;
        }
      }
      $aDiasAula[] = $oDia;
    }

    return $aDiasAula;
  }

  public function adicionarPeriodo(PeriodoAula $oPeriodoAula) {
    $this->aPeriodosAula[] = $oPeriodoAula;
  }

  private function buscarPeriodos($lSomenteAtivos = true) {

    $sWhere  = "     ed59_i_turma = {$this->oTurma->getCodigo()} ";
    $sWhere .= " and ed59_i_serie = {$this->oEtapa->getCodigo()} ";
    $sWhere .= " and ed58_datainicio is not null                 ";
    $sWhere .= " and ed58_datafim is not null                    ";

    if ($lSomenteAtivos) {
      $sWhere .= " and ed58_ativo is TRUE ";
    }

    $sOrdem = ' ed58_i_diasemana, ed08_i_sequencia ';
    $oDaoRegencia        = new cl_regenciahorario();
    $sSqlRegenciaHorario = $oDaoRegencia->sql_query_regencia_dia_semana(null, "regenciahorario.*", $sOrdem, $sWhere);
    $rsRegenciaHorario   = db_query( $sSqlRegenciaHorario );
    if ( !$rsRegenciaHorario ) {
      throw new DBException ( "Erro ao buscar grade horario. \n" . pg_last_error() );
    }

    $aPeriodosAula = array();
    $iLinhas       = pg_num_rows( $rsRegenciaHorario );

    for ( $i = 0; $i < $iLinhas; $i++ ) {

      $oDados       = db_utils::fieldsMemory( $rsRegenciaHorario, $i);
      $oPeriodoAula = new PeriodoAula();
      $oPeriodoAula->setDiaSemana( $oDados->ed58_i_diasemana - 1 );
      $oPeriodoAula->setRegencia( RegenciaRepository::getRegenciaByCodigo($oDados->ed58_i_regencia) );
      $oPeriodoAula->setPeriodoEscola( PeriodoEscolaRepository::getByCodigo($oDados->ed58_i_periodo) );
      $oPeriodoAula->setCodigo($oDados->ed58_i_codigo);
      $oPeriodoAula->setRegente($oDados->ed58_i_rechumano);
      $oPeriodoAula->setDataInicio(new DBDate($oDados->ed58_datainicio));
      $oPeriodoAula->setDataFim(new DBDate($oDados->ed58_datafim));
      $oPeriodoAula->setAtivo($oDados->ed58_ativo == 't');
      $oPeriodoAula->setTipoVinculo($oDados->ed58_tipovinculo);
      $aPeriodosAula[] = $oPeriodoAula;
    }
    return $aPeriodosAula;
  }



  private function validarPeriodos() {

    $aPeriodosValidar = array();
    $aTodosPeriodos   = $this->buscarPeriodos(false);

    /**
     * Identifica o período mais atual
     */
    foreach ($aTodosPeriodos as $oPeriodo) {

      $sHash = "{$oPeriodo->getDiaSemana()}#{$oPeriodo->getPeriodoEscola()->getCodigo()}";
      if ( !array_key_exists($sHash, $aPeriodosValidar) ) {
        $aPeriodosValidar[$sHash] = $oPeriodo;
      } else {

        if ( $aPeriodosValidar[$sHash]->getDataFim()->getTimeStamp() < $oPeriodo->getDataFim()->getTimeStamp()) {
          $aPeriodosValidar[$sHash] = $oPeriodo;
        }
      }
    }

    $this->aLogConflito = array();

    foreach( $this->aPeriodosAula as $oPeriodoSalvar) {

      // os periodos novos não tem código
      if ( $oPeriodoSalvar->getCodigo() != '' ) {
        continue;
      }

      foreach ($aPeriodosValidar as $oOutrosPeriodos) {

        if (    $oOutrosPeriodos->getDiaSemana() == $oPeriodoSalvar->getDiaSemana()
             && $oOutrosPeriodos->getPeriodoEscola()->getCodigo() == $oPeriodoSalvar->getPeriodoEscola()->getCodigo() ) {

          if ( $oPeriodoSalvar->getDataInicio()->getTimeStamp() <= $oOutrosPeriodos->getDataFim()->getTimeStamp() ) {


            $this->aLogConflito[] = array(
              'periodo'     => $oPeriodoSalvar->getPeriodoEscola()->getDescricao(),
              'diasemana'   => DBDate::getLabelDiaSemana($oPeriodoSalvar->getDiaSemana()),
              'data_fim'    => $oOutrosPeriodos->getDataFim()->adiantarPeriodo(1, 'd')->convertTo(DBDate::DATA_PTBR)
            );
          }
        }
      }
    }

    return count($this->aLogConflito) == 0;
  }

  public function salvar() {

    if ( !$this->validarPeriodos() ) {

      $sMsg = "Não é possível salvar a grade de horários, pois existem conflitos na Vigência do Período:\n";
      foreach ($this->aLogConflito as $aVariaveis) {
        $sMsg .= "Data disponível para incluir o {$aVariaveis['periodo']} período de {$aVariaveis['diasemana']}: a partir de {$aVariaveis['data_fim']}.\n";
      }
      $sMsg .= "Altere a data de Vigência do Período.";

      throw new Exception($sMsg);
    }


    foreach ($this->aPeriodosAula as $oPeriodo) {

      // como não altera... só inclui... realiza manutenção só nos registros novos
      if ( $oPeriodo->getCodigo() == null ) {
        $oPeriodo->salvar();
      }
    }

  }


}
