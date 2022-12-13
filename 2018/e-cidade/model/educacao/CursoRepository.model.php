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
   * Classe repository para classes Curso
   * @author Iuri Guntchnigg <iuri@dbseller.com.br>
   * @package
   */
  class CursoRepository {

    /**
     * Collection de Curso
     * @var array
     */
    private $aCurso = array();

    /**
     * Instancia da classe
     * @var CursoRepository
     */
    private static $oInstance;

    private function __construct() {

    }
    private function __clone() {

    }

    /**
     * Retorno uma instancia do Curso pelo Codigo
     * @param integer $iCodigo Codigo do Curso
     * @return Curso
     */
    public static function getByCodigo($iCodigoCurso) {

      if (!array_key_exists($iCodigoCurso, CursoRepository::getInstance()->aCurso)) {
        CursoRepository::getInstance()->aCurso[$iCodigoCurso] = new Curso($iCodigoCurso);
      }
      return CursoRepository::getInstance()->aCurso[$iCodigoCurso];
    }

    /**
     * Retorna a instancia da classe
     * @return CursoRepository
     */
    protected static function getInstance() {

      if (self::$oInstance == null) {

        self::$oInstance = new CursoRepository();
      }
      return self::$oInstance;
    }

    /**
     * Adiciona um Curso dao repositorio
     * @param Curso $oCurso Instancia do Curso
     * @return boolean
     */
    public static function adicionarCurso(Curso $oCurso) {

      if(!array_key_exists($oCurso->getCodigo(), CursoRepository::getInstance()->aCurso)) {
        CursoRepository::getInstance()->aCurso[$oCurso->getCodigo()] = $oCurso;
      }
      return true;
    }

    /**
     * Remove o Curso passado como parametro do repository
     * @param Curso $oCurso
     * @return boolean
     */
    public static function removerCurso(Curso $oCurso) {
       /**
        *
        */
      if (array_key_exists($oCurso->getCodigo(), CursoRepository::getInstance()->aCurso)) {
        unset(CursoRepository::getInstance()->aCurso[$oCurso->getCodigo()]);
      }
      return true;
    }

    /**
     * Retorna o total de cidadoes existentes no repositorio;
     * @return integer;
     */
    public static function getTotalCurso() {
      return count(CursoRepository::getInstance()->aCurso);
    }

    /**
     * Retorna uma coleção de cursos que o aluno concluiu, ou seja, que tem ano de conclusão informado no histórico.
     * @param Aluno $oAluno
     * @return Curso[]
     */
    public static function getCursosConcluidosPorAluno(Aluno $oAluno ) {

      $aCursos       = array();
      $oDaoHistorico = new cl_historico();
      $sWhere        = " ed61_i_aluno = {$oAluno->getCodigoAluno()} AND ed61_i_anoconc is not null";
      $sSqlHistorico = $oDaoHistorico->sql_query_file(null, 'ed61_i_curso', null, $sWhere);
      $rsHistorico   = db_query( $sSqlHistorico );

      if ( !$rsHistorico ) {
        throw new DBException("Erro ao buscar os cursos concluídos.");
      }

      for ( $iContador=0; $iContador < pg_num_rows($rsHistorico); $iContador++ ) {

        $iCurso = db_utils::fieldsMemory($rsHistorico, $iContador)->ed61_i_curso;
        $aCursos[] = CursoRepository::getByCodigo($iCurso);
      }

      return $aCursos;
    }
  }