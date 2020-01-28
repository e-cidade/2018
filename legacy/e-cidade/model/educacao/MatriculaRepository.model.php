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

define( 'MENSAGEM_MATRICULA_REPOSITORY', 'educacao.escola.MatriculaRepository.' );

/**
 * Repositoy para Matriculas
 * @package   Educacao
 * @author
 * @version   $Revision: 1.26 $
 */
class MatriculaRepository {

  private $aMatricula = array();
  private static $oInstance;

  private function __construct() {}
  private function __clone() {}

  /**
   * Retorna a instancia do Repositorio
   * @return MatriculaRepository
   */
  protected static function getInstance() {

    if(self::$oInstance == null) {
      self::$oInstance = new MatriculaRepository();
    }
    return self::$oInstance;
  }

  /**
   * Verifica se a Matricula  possui instancia, se não instancia e retorna a instancia de da Matricula
   *
   * @param integer $iMatricula
   * @return Matricula
   */
  public static function getMatriculaByCodigo($iMatricula) {

    if (!array_key_exists($iMatricula, MatriculaRepository::getInstance()->aMatricula)) {
      MatriculaRepository::getInstance()->aMatricula[$iMatricula] = new Matricula($iMatricula);
    }

    return MatriculaRepository::getInstance()->aMatricula[$iMatricula];
  }

  /**
   * Busca o aluno pela matricula
   * @deprecated
   *
   * @param $iMatricula
   * @return bool|Matricula
   * @throws DBException
   */
  public static function getAlunoByMatricula($iMatricula) {

    $oDaoMatricula = db_utils::getDao('matricula');
    $sSqlMatricula = $oDaoMatricula->sql_query_file($iMatricula, "ed60_i_codigo");
    $rsMatricula   = db_query($sSqlMatricula);

    if( !is_resource( $rsMatricula ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGEM_MATRICULA_REPOSITORY . 'erro_buscar_matricula', $oErro ) );
    }

    if( pg_num_rows( $rsMatricula ) > 0 ) {
      return MatriculaRepository::getInstance()->getMatriculaByCodigo(db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_codigo);
    }
    return false;
  }

  /**
   * Busca os Alunos de uma turma
   * @param Turma $oTurma
   * @return Matricula[]
   * @throws DBException
   */
  public static function getMatriculasByTurma(Turma $oTurma) {

    $oDaoMatricula = new cl_matricula();
    $sWhere        = " ed60_i_turma = {$oTurma->getCodigo()}";
    $sCampos       = "ed60_i_codigo, ed60_i_aluno, ed60_i_numaluno";
    $sOrdenacao    = "ed60_i_numaluno, ed47_v_nome";
    $sSqlMatricula = $oDaoMatricula->sql_query_aluno_matricula( null, $sCampos, $sOrdenacao, $sWhere );
    $rsMatricula   = db_query($sSqlMatricula);

    if( !is_resource( $rsMatricula ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGEM_MATRICULA_REPOSITORY . 'erro_buscar_matriculas_turma', $oErro ) );
    }

    $iTotalLinhas     = pg_num_rows( $rsMatricula );
    $aMatriculasTurma = array();

    if ($iTotalLinhas > 0) {

      for ($i = 0; $i < $iTotalLinhas; $i++) {

       $iCodigoMatricula   = db_utils::fieldsMemory($rsMatricula, $i)->ed60_i_codigo;
       $aMatriculasTurma[] = MatriculaRepository::getMatriculaByCodigo($iCodigoMatricula);
      }
    }

    return $aMatriculasTurma;
  }

  /**
   * Busca os Alunos de uma turma
   * @param Turma $oTurma
   * @throws DBException
   *
   * @return Matricula[]
   */
  public static function getMatriculasByTurmaOrdemAlfabetica(Turma $oTurma) {

    $oDaoMatricula = db_utils::getDao('matricula');
    $sWhere        = " ed60_i_turma = {$oTurma->getCodigo()}";
    $sCampos       = "ed60_i_codigo, ed60_i_aluno, ed60_i_numaluno";
    $sOrdenacao    = "ed47_v_nome, ed60_i_numaluno";
    $sSqlMatricula = $oDaoMatricula->sql_query_aluno_matricula( null, $sCampos,$sOrdenacao, $sWhere );
    $rsMatricula   = db_query($sSqlMatricula);

    if( !is_resource( $rsMatricula ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGEM_MATRICULA_REPOSITORY . 'erro_buscar_matriculas_turma', $oErro ) );
    }

    $iTotalLinhas     = pg_num_rows( $rsMatricula );
    $aMatriculasTurma = array();

    if ($iTotalLinhas > 0) {

      for ($i = 0; $i < $iTotalLinhas; $i++) {

        $iCodigoMatricula   = db_utils::fieldsMemory($rsMatricula, $i)->ed60_i_codigo;
        $aMatriculasTurma[] = MatriculaRepository::getMatriculaByCodigo($iCodigoMatricula);
      }
    }
    return $aMatriculasTurma;
  }

  /**
   * Adiciona um Aluno ao repositorio
   *
   * @param Matricula $oMatricula
   * @return boolean
   */
  public static function adicionarMatricula(Matricula $oMatricula) {

    if (!array_key_exists($oMatricula->getCodigo(), MatriculaRepository::getInstance()->aMatricula)) {
      MatriculaRepository::getInstance()->aMatricula[$oMatricula->getCodigo()] = $oMatricula;
    }
    return true;
  }

  /**
   * Remove uma Matricula do repository
   *
   * @param Matricula $oMatricula
   * @return boolean
   */
  public static function removerMatricula(Matricula $oMatricula) {

    if (array_key_exists($oMatricula->getCodigo(), MatriculaRepository::getInstance()->aMatricula)) {
      unset(MatriculaRepository::getInstance()->aMatricula[$oMatricula->getCodigo()]);
    }
    return true;
  }

  public static function removeAll() {

    unset(MatriculaRepository::getInstance()->aMatricula);
    MatriculaRepository::getInstance()->aMatricula = array();
    return true;
  }

  /**
   * Retorna a matrícula ativa do aluno
   * @param Aluno $oAluno
   * @return Matricula|null
   * @throws DBException
   */
  public static function getMatriculaAtivaPorAluno(Aluno $oAluno) {

    $sWhere  = "     ed60_i_aluno = {$oAluno->getCodigoAluno()} ";
    $sWhere .= " and ed60_c_concluida = 'N' ";
    $sWhere .= " and ed60_c_situacao = 'MATRICULADO'";

    $oDaoMatricula = new cl_matricula();
    $sSqlMatricula = $oDaoMatricula->sql_query_file(null, "ed60_i_codigo", null, $sWhere);
    $rsMatricula   = db_query($sSqlMatricula);

    if( !is_resource( $rsMatricula ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGEM_MATRICULA_REPOSITORY . 'erro_buscar_matricula_ativa', $oErro ) );
    }

    if( pg_num_rows( $rsMatricula ) == 0 ) {
    	return null;
    }

    return MatriculaRepository::getInstance()->getMatriculaByCodigo(db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_codigo);
  }

  /**
   * Retorna a ultima matricula de um aluno
   * @param Aluno   $oAluno
   * @param integer $iAno   filtra a ultima matrícula de um ano especifico
   * @return Matricula|null
   * @throws DBException
   */
  public static function getUltimaMatriculaAluno(Aluno $oAluno, $iAno = null) {

    $sWhere    = " ed60_i_aluno = {$oAluno->getCodigoAluno()} ";
    if ( !is_null($iAno) ) {
      $sWhere .= "and extract (year from ed60_d_datamatricula) = {$iAno}";
    }


    $oDaoMatricula = new cl_matricula();
    $sSqlMatricula = $oDaoMatricula->sql_query_file(null, "max(ed60_i_codigo) as ed60_i_codigo", null, $sWhere);
    $rsMatricula   = db_query($sSqlMatricula);

    if( !is_resource( $rsMatricula ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGEM_MATRICULA_REPOSITORY . 'erro_buscar_ultima_matricula', $oErro ) );
    }

    if (pg_num_rows( $rsMatricula ) == 0 || empty(db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_codigo)) {
      return null;
    }

    return MatriculaRepository::getInstance()->getMatriculaByCodigo(db_utils::fieldsMemory($rsMatricula, 0)->ed60_i_codigo);
  }

  /**
   * @param Aluno $oAluno
   * @param bool  $lSomenteSituacaoMatriculado
   * @param Turma $oTurma
   * @return Matricula[]
   * @throws DBException
   */
  public static function getTodasMatriculasAluno( Aluno $oAluno, $lSomenteSituacaoMatriculado = true, $oTurma = null, $sOrdem = null ) {

    $aMatriculas     = array();
    $oDaoMatricula   = new cl_matricula();
    $sWhereMatricula = "ed60_i_aluno = {$oAluno->getCodigoAluno()}";

    if( $lSomenteSituacaoMatriculado ) {
      $sWhereMatricula .= " and ed60_c_situacao = 'MATRICULADO' ";
    }

    if( $oTurma != null && $oTurma instanceof Turma ) {
      $sWhereMatricula .= " and ed60_i_turma = {$oTurma->getCodigo()} ";
    }

    $sSqlMatricula = $oDaoMatricula->sql_query_file( null, 'ed60_i_codigo', $sOrdem, $sWhereMatricula );
    $rsMatricula   = db_query( $sSqlMatricula );

    if( !$rsMatricula ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGEM_MATRICULA_REPOSITORY . 'erro_buscar_matriculas', $oErro ) );
    }

    $iTotalMatriculas = pg_num_rows( $rsMatricula );
    if( $iTotalMatriculas == 0 ) {
      return $aMatriculas;
    }

    for( $iContador = 0; $iContador < $iTotalMatriculas; $iContador++ ) {

      $iCodigoMatricula = db_utils::fieldsMemory( $rsMatricula, $iContador)->ed60_i_codigo;
      $aMatriculas[]    = MatriculaRepository::getInstance()->getMatriculaByCodigo( $iCodigoMatricula );
    }

    return $aMatriculas;
  }

  /**
   * Retorna a matrícula ativa de um aluno em determinada turma
   * @param Turma $oTurma
   * @param Aluno $oAluno
   * @return Matricula|null
   * @throws DBException
   */
  public static function getMatriculaAtivaTurma( Turma $oTurma, Aluno $oAluno ) {

    $aMatriculas = MatriculaRepository::getTodasMatriculasAluno( $oAluno, true, $oTurma );

    return isset( $aMatriculas[0] ) ? $aMatriculas[0] : null;
  }

  /**
   * Busca a ultima de TODOS os ALUNOS,
   * em cima destas matrículas filtra a escola e etapa informada
   * @param  Escola $oEscola
   * @param  Etapa  $oEtapa
   * @return Matricula[]
   */
  public static function getTodasMatriculasEncerradasPorEtapa(Escola $oEscola, Etapa $oEtapa, $sFiltroAdicional = '') {

    $sSql  = " select matricula.ed60_i_codigo, matricula.ed60_i_aluno, trim(ed47_v_nome) as nome ";
    $sSql .= "   from ( select max(ed60_i_codigo) , ed60_i_aluno ";
    $sSql .= "            from matricula  ";
    $sSql .= "           group by ed60_i_aluno) as x ";
    $sSql .= "   join matricula       on ed60_i_codigo     = x.max ";
    $sSql .= "   join aluno           on ed47_i_codigo     = matricula.ed60_i_aluno ";
    $sSql .= "   join turma           on ed57_i_codigo     = ed60_i_turma ";
    $sSql .= "   join matriculaserie  on ed221_i_matricula = ed60_i_codigo ";
    $sSql .= "                       and ed221_c_origem    = 'S'  ";
    $sSql .= "  where ed57_i_escola    = {$oEscola->getCodigo()} ";
    $sSql .= "    and ed221_i_serie    = {$oEtapa->getCodigo()} ";
    $sSql .= "    and ed60_c_situacao  = 'MATRICULADO' ";
    $sSql .= "    and ed60_c_concluida = 'S' ";
    $sSql .= "    {$sFiltroAdicional} ";
    $sSql .= "  order by nome";

    $rsMatriculasEncerradas = db_query( $sSql );

    if ( !$rsMatriculasEncerradas ) {
      throw new DBException( _M(MENSAGEM_MATRICULA_REPOSITORY . "erro_buscar_matriculas_encerradas") );
    }

    $aMatriculasEncerradas = array();

    $iLinhas = pg_num_rows($rsMatriculasEncerradas);
    for( $i = 0; $i < $iLinhas; $i++) {

      $oDados                  = db_utils::fieldsMemory($rsMatriculasEncerradas, $i);
      $aMatriculasEncerradas[] = self::getMatriculaByCodigo($oDados->ed60_i_codigo);
    }

    return $aMatriculasEncerradas;
  }

}
