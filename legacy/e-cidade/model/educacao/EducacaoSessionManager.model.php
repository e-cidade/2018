<?php

use \ECidade\V3\Extension\Registry;
use \ECidade\Window\Session;

/**
 * Busca os dados da sess�o
 *
 * @package  educacao
 * @author   Andrio Costa <andrio.costa@dbseller.com.br>
 * @version   $Revision: 1.5 $
 */
class EducacaoSessionManager {

  /**
   * grava na session a vari�vel DIAS_MANUTENCAO_HISTORICO com os dias que o aluno
   */
  const DIAS_MANUTENCAO_HISTORICO = "DIAS_MANUTENCAO_HISTORICO";

  /**
   * carrega a turma da sess�o se houver sen�o cosntr�i o objeto da turma
   *
   * @param  integer $iTurma
   * @return Turma
   */
  static public function carregarTurma($iTurma = null) {

    $sTurma = "oTurma" . db_getsession("DB_id_usuario");

    if (isset($_SESSION[$sTurma]) && $_SESSION[$sTurma] instanceof Turma) {
      return $_SESSION[$sTurma];
    }

    if ( empty($iTurma) ) {
      throw new Exception("N�o foi encontrada uma turma na sess�o e n�o foi informado o c�digo da turma a ser carregado.");
    }
    return TurmaRepository::getTurmaByCodigo($iTurma);
  }

  /**
   * Carrega a etapa da sess�o se houver, sen�o cosntr�i o objeto da etapa
   * @param  integer $iEtapa
   * @return Etapa
   */
  static public function carregarEtapa($iEtapa = null) {

    $sEtapa = "oEtapa" . db_getsession("DB_id_usuario");

    if (isset( $_SESSION[$sEtapa] )  && $_SESSION[$sEtapa] instanceof Etapa ) {
      return $_SESSION[$sEtapa];
    }

    if ( empty($iEtapa) ) {
      throw new Exception("N�o foi encontrada uma etapa na sess�o e n�o foi informado o c�digo da etapa a ser carregado.");
    }
    return EtapaRepository::getEtapaByCodigo($iEtapa);
  }

  /**
   * Carrega a matr�cula
   * @param  integer   $iMatricula
   * @return Matricula
   */
  static public function carregarMatricula($iMatricula) {

    $sTurma = "oTurma" . db_getsession("DB_id_usuario");
    $sEtapa = "oEtapa" . db_getsession("DB_id_usuario");

    if (   isset( $_SESSION[$sTurma] ) && $_SESSION[$sTurma] instanceof Turma
        && isset( $_SESSION[$sEtapa] ) && $_SESSION[$sEtapa] instanceof Etapa) {

      $oTurma = $_SESSION[$sTurma];
      $oEtapa = $_SESSION[$sEtapa];

      foreach ($oTurma->getAlunosMatriculadosNaTurmaPorSerie($oEtapa) as $oMatriculaAluno) {

        if ($oMatriculaAluno->getCodigo() == $iMatricula) {
          return $oMatriculaAluno;
        }
      }
    }

    return MatriculaRepository::getMatriculaByCodigo($iMatricula);
  }

  /**
   * Adiciona o objeto da turma na sess�o
   * @param  Turma  $oTurma
   */
  static public function registrarTurma(Turma $oTurma) {

    $_SESSION["oTurma".db_getsession("DB_id_usuario")] = $oTurma;
  }

  /**
   * Registro o objeto da etapa na sess�o
   * @param  Etapa  $oEtapa
   */
  static public function registrarEtapa(Etapa $oEtapa) {
    $_SESSION["oEtapa".db_getsession("DB_id_usuario")] = $oEtapa;
  }


  /**
   * Remove a turma e a etapa da sessao
   */
  static public function limpar() {

    unset( $_SESSION["oTurma".db_getsession("DB_id_usuario")] );
    unset( $_SESSION["oEtapa".db_getsession("DB_id_usuario")] );
  }


  /**
   * Registra na sess�o os dias configurado para manuten��o do hist�rico escolar
   * @param  integer $iDias
   */
  static public function registrarDiasManutencaoHistorico( $iDias ) {

    /**
     * Se utilizado e-Cidade 3, adiciona na SESSION GLOBAL, para ser clonado em todas janelas
     */
    if (Registry::get('app.container')->get('ECIDADE_DESKTOP')) {
      Session::update(Session::MAIN_NAME, array(self::DIAS_MANUTENCAO_HISTORICO => $iDias));
    }

    $_SESSION[self::DIAS_MANUTENCAO_HISTORICO] = $iDias;
  }

  /**
   * Retorna os dias configurado para manuten��o do hist�rico escolar
   * @throws DBException
   * @return integer
   */
  static public function diasManutencaoHistorico() {

    if ( !isset($_SESSION[self::DIAS_MANUTENCAO_HISTORICO]) ) {


      $oDaoParametros = new cl_sec_parametros;
      $sSqlParametros = $oDaoParametros->sql_query_file();
      $rsParametros   = db_query($sSqlParametros);

      if ( !$rsParametros ) {
        throw new DBException("N�o foi poss�vel buscar os par�metros configurados na Secretaria da Educa��o.\n" . pg_last_error());
      }
      $iDias = db_utils::fieldsMemory($rsParametros, 0)->ed290_diasmanutencaohistorico;
      self::registrarDiasManutencaoHistorico($iDias);
    }


    return $_SESSION[self::DIAS_MANUTENCAO_HISTORICO];
  }
}
