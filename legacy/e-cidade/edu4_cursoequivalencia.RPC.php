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
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_utils.php"));
require_once (modification("libs/db_app.utils.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("libs/JSON.php"));
require_once (modification("classes/db_cursoedu_classe.php"));

define('ARQUIVO_MENSAGEM_EQUIVALENCIA', 'educacao.secretariaeducacao.edu4_cursoequivalenciaRPC.');

$oJson              = new services_json();
$oParam             = JSON::create()->parse(str_replace("\\","",$_POST["json"]));
$oRetorno           = new stdClass();
$oRetorno->iStatus  = 1;
$oRetorno->sMessage = '';

try {

  db_inicio_transacao();

  switch ($oParam->exec) {

    case "buscarCursos":

      $oRetorno->aCursos = array();

      $oDaoCurso = new cl_curso;
      $sSqlCurso = $oDaoCurso->sql_query_file( null, 'ed29_i_codigo, ed29_c_descr', 'ed29_c_descr', null);
      $rsCurso   = db_query( $sSqlCurso );

      if ( !$rsCurso ) {
        throw new DBException( _M(ARQUIVO_MENSAGEM_EQUIVALENCIA . "erro_buscar_cursos") );
      }

      $iTotalCursos = pg_num_rows($rsCurso);
      if ( $iTotalCursos == 0 ) {
        throw new BusinessException( _M(ARQUIVO_MENSAGEM_EQUIVALENCIA . "nenhum_curso_encontrado") );
      }

      for ( $iContador = 0; $iContador < $iTotalCursos; $iContador++  ) {

        $oDadosCurso = db_utils::fieldsMemory( $rsCurso, $iContador );

        $oCurso              = new stdClass();
        $oCurso->iCurso      = $oDadosCurso->ed29_i_codigo;
        $oCurso->sCurso      = $oDadosCurso->ed29_c_descr;
        $oRetorno->aCursos[] = $oCurso;
      }
      break;

    case "buscarCursosEquivalentes":

      if ( empty($oParam->iCurso) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM_EQUIVALENCIA . "informe_curso") );
      }

      $oRetorno->aCursosEquivalentes = array();

      $oDaoCursoEquivalencia = new cl_cursoequivalencia();
      $sCampos               = "cursoequivalente.ed29_i_codigo, cursoequivalente.ed29_c_descr";
      $sWhere                = "ed140_cursoedu = {$oParam->iCurso}";
      $sOrdem                = "cursoequivalente.ed29_c_descr";
      $sSqlCursoEquivalencia = $oDaoCursoEquivalencia->sql_query(null, $sCampos, $sOrdem, $sWhere);
      $rsCursoEquivalencia   = db_query( $sSqlCursoEquivalencia );

      if ( !$rsCursoEquivalencia ) {
        throw new DBException( _M(ARQUIVO_MENSAGEM_EQUIVALENCIA . "erro_buscar_cursos_equivalentes") );
      }

      $iTotalCursos = pg_num_rows($rsCursoEquivalencia);

      for ( $iContador = 0; $iContador < $iTotalCursos; $iContador++ ) {

        $oDadosCursoEquivalencia = db_utils::fieldsMemory( $rsCursoEquivalencia, $iContador );

        $oCursoEquivalente               = new stdClass();
        $oCursoEquivalente->iCurso       = $oDadosCursoEquivalencia->ed29_i_codigo;
        $oCursoEquivalente->sCurso       = $oDadosCursoEquivalencia->ed29_c_descr;
        $oRetorno->aCursosEquivalentes[] = $oCursoEquivalente;
      }
      break;

    case "salvar":

      if ( empty($oParam->iCurso) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM_EQUIVALENCIA . "informe_curso") );
      }

      if ( !isset($oParam->aCursosEquivalentes) ) {
        throw new ParameterException( _M(ARQUIVO_MENSAGEM_EQUIVALENCIA . "informe_curso_equivalente") );
      }

      $oDaoCursoEquivalencia = new cl_cursoequivalencia();
      $oDaoCursoEquivalencia->excluir(null, "ed140_cursoedu = {$oParam->iCurso}");

      foreach ($oParam->aCursosEquivalentes as $iCursoEquivalente) {

        $oDaoCursoEquivalencia->ed140_sequencial       = null;
        $oDaoCursoEquivalencia->ed140_cursoedu         = $oParam->iCurso;
        $oDaoCursoEquivalencia->ed140_cursoequivalente = $iCursoEquivalente;
        $oDaoCursoEquivalencia->incluir(null);
      }

      $oRetorno->sMessage =  _M(ARQUIVO_MENSAGEM_EQUIVALENCIA . "curso_equivalente_salvo");
      break;

    case "buscarCursosConcluidosPorAluno":

      if ( empty($oParam->iAluno) ) {
        throw new ParameterException(ARQUIVO_MENSAGEM_EQUIVALENCIA . "informe_aluno");
      }

      $oRetorno->aCursos = array();
      $oAluno            = new Aluno($oParam->iAluno);
      $aCursos           = CursoRepository::getCursosConcluidosPorAluno($oAluno);

      foreach ( $aCursos as $oCurso ) {

        $oStdCurso           = new stdClass();
        $oStdCurso->iCurso   = $oCurso->getCodigo();
        $oStdCurso->sCurso   = $oCurso->getNome();
        $oRetorno->aCursos[] = $oStdCurso;
      }
      break;
  }

  db_fim_transacao(false);


} catch (Exception $eErro){

  db_fim_transacao(true);
  $oRetorno->iStatus  = 2;
  $oRetorno->sMessage = $eErro->getMessage();
}
$oRetorno->erro = $oRetorno->iStatus == 2;
echo JSON::create()->stringify($oRetorno);