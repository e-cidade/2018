<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
 * Classe para gerar a grade de horario de uma matricula do aluno, disponibilizaod como serviços
 * para o portal do aluno
 * @author dbseller
 *
 */
final class HorarioAulaMatriculaAlunoWebservice {
  
  /**
   * Matricula do aluno
   * @var Matricula
   */
  protected $oMatricula;
  
  /**
   * Regencias que possuem professor lecionando
   */
  protected $aRegenciasComProfessor = array();
  
  /**
   * Instancia uma classe do servico
   * @param unknown $iCodigoMatricula
   * @throws ParameterException
   */
  public function __construct($iCodigoMatricula) {
    
    if (empty($iCodigoMatricula)) {
      throw new ParameterException('Matricula não informada');
    }
    $this->oMatricula = MatriculaRepository::getMatriculaByCodigo($iCodigoMatricula);
    $this->getRegenciasComProfessor();
  }
  
  public function getHorario() {
      
    $oDadosHorario        = new stdClass();
    $oDadosHorario->turma = $this->getHorariosTurma();
    return $oDadosHorario;
  }
  
  /**
   * Retorna os Horarios da Semana
   * @return stdClass
   */
  protected function getHorariosTurma() {
    
    $oTurma           = $this->oMatricula->getTurma();
    $aTurnos[]        = $oTurma->getTurno();
    /**
     * Adicionamos o turno adicional da turma, se existir.
     */
    if ($oTurma->temTurnoAdicional() != "") {
      
      $aTurnos[] = $oTurma->getTurnoAdicional() ;
    }
    $oGrade         = new stdClass();
    $oGrade->turnos = array();
    $aDiasDaSemana = $this->getDiasDaSemana();
    foreach ($aTurnos as $oTurno) {
      
      $oTurnoGrade    = new stdClass();
      $sNomeAdicional = '(Turno Adicional)';
      if ($oTurno->getCodigoTurno() == $oTurma->getTurno()->getCodigoTurno()) {
        $sNomeAdicional = '(Turno Principal)';
      }
      $oTurnoGrade->nome        = utf8_encode($oTurno->getDescricao()." {$sNomeAdicional}");
      $oTurnoGrade->dias_semana = array();
      
      $oDaoPeriodoEscola = new cl_periodoescola();
      $sWherePeriodos    = "ed17_i_escola = {$oTurma->getEscola()->getCodigo()} ";
      $sWherePeriodos   .= " AND ed17_i_turno = {$oTurno->getCodigoTurno()}";
      $sSqlPeriodos      = $oDaoPeriodoEscola->sql_query(null, "*", "ed15_i_sequencia,ed08_i_sequencia", $sWherePeriodos);
      $rsPeriodos        = $oDaoPeriodoEscola->sql_record($sSqlPeriodos);
      $iTotalLinhas      = $oDaoPeriodoEscola->numrows;
      /**
       * Montamos uma matriz onde contem os dados de aula, para cada dia da semana e periodo
       */
      if ($rsPeriodos &&  $iTotalLinhas > 0) {
        
        foreach ($aDiasDaSemana as $oDiaSemana) {
          
          $oDiaSemanaGrade           = new stdClass();
          $oDiaSemanaGrade->nome     = utf8_encode($oDiaSemana->ed32_c_descr);
          $oDiaSemanaGrade->periodos = array();
          for ($iPeriodo = 0; $iPeriodo < $iTotalLinhas; $iPeriodo++) {

            $oDadosPeriodo = db_utils::fieldsMemory($rsPeriodos, $iPeriodo);
          
            $oPeriodo                  = new stdClass();
            $oPeriodo->nome_periodo    = utf8_encode($oDadosPeriodo->ed08_c_descr);
            $oPeriodo->inicio_periodo  = utf8_encode($oDadosPeriodo->ed17_h_inicio);
            $oPeriodo->termino_periodo = utf8_encode($oDadosPeriodo->ed17_h_fim);
            $oPeriodo->disciplina      = '';
            $oPeriodo->professor       = '';
                 
            $oRegencia = $this->getRegenciaNoPeriodo($oDadosPeriodo->ed17_i_codigo, $oDiaSemana->ed32_i_codigo);
            if ($oRegencia) {
              
              $oPeriodo->disciplina = utf8_encode($oRegencia->disciplina);
              $oPeriodo->professor  = utf8_encode($oRegencia->professor);
            }
            $oDiaSemanaGrade->periodos[] = $oPeriodo;;
          }
          $oTurnoGrade->dias_semana[]    = $oDiaSemanaGrade;
        }
      }
      $oGrade->turnos[] = $oTurnoGrade;
    }
    return $oGrade;
  }
  
  /**
   * Retorna a disciplina e o professor que está no período.
   * @param integer $iPeriodo codigo do periodo de aula
   * @param integer $iDiaSemana codigo do Dia da semana
   * @return boolean | stdClass
   */
  protected function getRegenciaNoPeriodo($iPeriodo, $iDiaSemana) {

    foreach ($this->aRegenciasComProfessor as $oRegencia) {
      
      if ($oRegencia->periodo == $iPeriodo && $oRegencia->dia_semana == $iDiaSemana) {
        
        return $oRegencia;
        break;
      }
    }
    return false;
  }
  
  /**
   * Retorna os dados do dia da semana
   * @return array
   */
  protected function getDiasDaSemana() {
    
    $iCodigoEscola = $this->oMatricula->getTurma()->getEscola()->getCodigo();
    $aDiasemana    = array();
    $oDaoDiaSemana = new cl_diasemana();
    $sSqlDiaSemana = $oDaoDiaSemana->sql_query_rh("", "*",
                                                  "ed32_i_codigo",
                                                  " ed04_c_letivo = 'S'
                                                   AND ed04_i_escola = {$iCodigoEscola}"
                                                  );
    $rsDiaSemana = $oDaoDiaSemana->sql_record($sSqlDiaSemana);
    if ($rsDiaSemana) {
      $aDiasemana = db_utils::getColectionByRecord($rsDiaSemana);
    }
    return $aDiasemana;
  }
  
  /**
   * Retorna todas as regencias da turma.
   */
  protected function getRegenciasComProfessor() {

    $iSerie = $this->oMatricula->getEtapaDeOrigem()->getCodigo();
    $iTurma = $this->oMatricula->getTurma()->getCodigo();
    
    $oDaoRegenciaHorario = new cl_regenciahorario();
    
    $sWhere .= " ed58_ativo       is true ";
    $sWhere .= " and ed59_i_serie     =  {$iSerie}";
    $sWhere .= " and ed59_i_turma     =  {$iTurma}";
    
    $sSqlRegente = $oDaoRegenciaHorario->sql_query_quadro_horario(null, "z01_nome as professor,
                                                                         ed58_i_periodo  as periodo,
                                                                        ed58_i_diasemana as dia_semana,
                                                                        ed232_c_descr as disciplina",
                                                                        null,
                                                                        $sWhere
    );
    $rsRegencia = $oDaoRegenciaHorario->sql_record($sSqlRegente);
    if ($rsRegencia && $oDaoRegenciaHorario->numrows > 0) {
      $this->aRegenciasComProfessor = db_utils::getColectionByRecord($rsRegencia);
    }
  }
}