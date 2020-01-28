<?
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

require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("std/DBDate.php");
require_once("classes/db_cursoedu_classe.php");
db_app::import("exceptions.*");
db_app::import("educacao.avaliacao.iFormaObtencao");
db_app::import("educacao.avaliacao.iElementoAvaliacao");
db_app::import("educacao.*");
db_app::import("educacao.avaliacao.*");
db_app::import("educacao.progressaoparcial.*");
db_postmemory($HTTP_POST_VARS);

$oDaoProcedimento        = db_utils::getdao("procedimento");
$oDaoDiarioFinal         = db_utils::getdao("diariofinal");
$oDaoDiario              = db_utils::getdao("diario");
$oDaoRegencia            = db_utils::getdao("regencia");
$oDaoRegenciaPeriodo     = db_utils::getdao("regenciaperiodo");
$oDaoTurma               = db_utils::getdao("turma");
$oDaoTurmaSerieRegimeMat = db_utils::getdao("turmaserieregimemat");
$oDaoSerieRegimeMat      = db_utils::getdao("serieregimemat");
$oDaoMatricula           = db_utils::getdao("matricula");
$oDaoMatriculaMov        = db_utils::getdao("matriculamov");
$oDaoHistorico           = db_utils::getdao("historico");
$oDaoHistoricoMpd        = db_utils::getdao("historicompd");
$oDaoHistoricoMps        = db_utils::getdao("historicomps");
$oDaoHistMpsDisc         = db_utils::getdao("histmpsdisc");
$oDaoAlunoPossib         = db_utils::getdao("alunopossib");
$oDaoBaseMps             = db_utils::getdao("basemps");
$oDaoEscolaBase          = db_utils::getdao("escolabase");
$oDaoSerie               = db_utils::getdao("serie");
$clcurso                 = new cl_curso;
$db_botao                = true;
$escola                  = db_getsession("DB_coddepto");
$resultedu               = eduparametros(db_getsession("DB_coddepto"));
$sCamposRegencia         = " ed59_i_turma,ed57_c_descr,ed52_c_descr,ed57_i_calendario as calend, ";
$sCamposRegencia        .= " fc_codetapaturma(ed59_i_turma) as serie1";
$sSqlRegencia            = $oDaoRegencia->sql_query("",$sCamposRegencia,"","ed59_i_turma = $turma");
$rsResultRegencia        = $oDaoRegencia->sql_record($sSqlRegencia);
db_fieldsmemory($rsResultRegencia,0);

$sSqlCurso = $clcurso->sql_query("","*", "", "ed29_i_codigo = $curso");
$rsCurso   = $clcurso->sql_record($sSqlCurso);

db_fieldsmemory($rsCurso,0);
$iCodigoEnsino               = $ed29_i_ensino;
$oParametroProgressaoParcial = ProgressaoParcialParametroRepository::getProgressaoParcialParametroByCodigo($escola);
$iQuantidadeDisciplinas = 0;
$sLabelAprovado        = 'APROVADO';
$sLabelReprovado       = 'REPROVADO';
$sLabelAprovadoParcial = 'APROVADO PARCIAL';
if ($oParametroProgressaoParcial->getQuantidadeDisciplina() != null) {
  $iQuantidadeDisciplinas = $oParametroProgressaoParcial->getQuantidadeDisciplina();
}
$aTermosAprovado       = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'A');
if (count($aTermosAprovado) > 0) {
  $sLabelAprovado = $aTermosAprovado[0]->sDescricao;
}

$aTermosReprovado = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'R');
if (count($aTermosReprovado) > 0) {
  $sLabelReprovado = $aTermosReprovado[0]->sDescricao;
}

$aTermosAprovadoParcial = DBEducacaoTermo::getTermoEncerramento($iCodigoEnsino, 'P');
if (count($aTermosAprovadoParcial) > 0) {
  $sLabelAprovadoParcial = $aTermosAprovadoParcial[0]->sDescricao;
}
if (isset($confirmar)) {

  //db_query('begin');
  db_inicio_transacao();
  $sCamposTurma  = "ed57_i_codigo as codturma,ed57_i_tipoturma as tipoturma,ed31_i_curso as curso,ed31_i_regimemat, ";
  $sCamposTurma .= "ed57_i_escola as escola,ed57_i_calendario as calendario,ed57_i_base as base, ";
  $sCamposTurma .= "ed57_c_medfreq as medfrequencia,ed57_i_turno as turno,ed52_i_ano as ano, ed10_i_codigo,";
  $sCamposTurma .= "ed52_i_periodo as periodo,ed52_i_diasletivos as diasletivos,ed52_i_semletivas as semletivas";
  $sSqlTurma     = $oDaoTurma->sql_query("",$sCamposTurma,""," ed57_i_codigo = $ed59_i_turma");
  $rsResultTurma = $oDaoTurma->sql_record($sSqlTurma);
  db_fieldsmemory($rsResultTurma,0);

  if ($tipoturma == 1 || $tipoturma == 3) {

    $sCamposTurmaSerieRegimeMat  = "max(ed11_i_sequencia) as maiorseqturma,ed11_i_codigo as maiorserieturma, ";
    $sCamposTurmaSerieRegimeMat .= "ed11_i_ensino as ensinoturma";
    $sWhereTurmaSerie            = " ed220_i_turma = $ed59_i_turma AND ed223_i_serie = $codserieregencia ";
    $sWhereTurmaSerie           .= "GROUP BY ed11_i_codigo,ed11_i_ensino,ed223_i_ordenacao";
    $sSqlTurmaSerieRegimeMat     = $oDaoTurmaSerieRegimeMat->sql_query("",$sCamposTurmaSerieRegimeMat,"",$sWhereTurmaSerie);
    $rsResultMaxSeq              = $oDaoTurmaSerieRegimeMat->sql_record($sSqlTurmaSerieRegimeMat);

  } else {//fecha o if $tipoturma == 1 || $tipoturma == 3

    $sCamposTurmaSerieReg  = "max(ed11_i_sequencia) as maiorseqturma,ed11_i_codigo as maiorserieturma,";
    $sCamposTurmaSerieReg .= " ed11_i_ensino as ensinoturma";
    $sWhereTurmaReg        = " ed220_i_turma = $ed59_i_turma GROUP BY ed11_i_codigo,ed11_i_ensino,ed223_i_ordenacao ";
    $sWhereTurmaReg       .= " ORDER BY ed223_i_ordenacao DESC LIMIT 1";
    $sSqlTurmaSerieReg     = $oDaoTurmaSerieRegimeMat->sql_query("",$sCamposTurmaSerieReg,"",$sWhereTurmaReg);
    $rsResultMaxSeq        = $oDaoTurmaSerieRegimeMat->sql_record($sSqlTurmaSerieReg);

  }//fecha o else

  db_fieldsmemory($rsResultMaxSeq,0);
  $sCamposSerieRegimeMat = " ed11_i_codigo as codproximaserie";
  $sWhereSerieRegMat     = " ed223_i_regimemat = $ed31_i_regimemat AND ed11_i_sequencia > $maiorseqturma ";
  $sWhereSerieRegMat    .= " AND ed11_i_ensino = $ensinoturma";
  $sOrder                = " ed223_i_ordenacao limit 1";
  $sSqlSerieRegimeMat    = $oDaoSerieRegimeMat->sql_query("",$sCamposSerieRegimeMat,$sOrder,$sWhereSerieRegMat);
  $rsResultProxSerie     = $oDaoSerieRegimeMat->sql_record($sSqlSerieRegimeMat);
  $iLinhasSerieRegimeMat = $oDaoSerieRegimeMat->numrows;

  if ($iLinhasSerieRegimeMat > 0) {

    db_fieldsmemory($rsResultProxSerie,0);
    $temproximaserie = true;

  } else {

   	$sCamposEscolaBase  = "cursoedu.ed29_i_ensino as ensinoatual,cursoeducont.ed29_i_ensino as ensinoseguinte,";
    $sCamposEscolaBase .= " basecont.ed31_i_regimemat as regimeseguinte";
  	$sWhereEscolaBase   = " ed77_i_base = $base AND ed77_i_escola = $escola";
  	$sSqlEscolaBase     = $oDaoEscolaBase->sql_query("",$sCamposEscolaBase,"",$sWhereEscolaBase);
    $rsResultEscolaBase = $oDaoEscolaBase->sql_record($sSqlEscolaBase);
    db_fieldsmemory($rsResultEscolaBase,0);

    if ($ensinoseguinte != "" && $ensinoseguinte != $ensinoatual) {

      $sCamposSerieRegMat = "ed11_i_codigo as codproximaserie";
  	  $sWhereSerieRegMat  = " ed223_i_regimemat = $regimeseguinte";
  	  $sWhereSerieRegMat .= " AND ed11_i_ensino = $ensinoseguinte";
  	  $sSqlSerieRegMat    = $oDaoSerieRegimeMat->sql_query("",$sCamposSerieRegMat,"ed223_i_ordenacao limit 1",
  	                                                       $sWhereSerieRegMat
  	                                                      );
      $rsResultProxSerie  = $oDaoSerieRegimeMat->sql_record($sSqlSerieRegMat);
      db_fieldsmemory($rsResultProxSerie,0);
      $temproximaserie = true;

    } else { //fecha o if $ensinoseguinte != "" && $ensinoseguinte != $ensinoatual

      $codproximaserie = $maiorserieturma;
      $temproximaserie = false;

    }//fecha o else do if $ensinoseguinte != "" && $ensinoseguinte != $ensinoatual

  }//fecha o else

  $sCamposMatricula  = " ed47_v_nome,ed60_i_codigo,ed60_i_aluno,ed60_c_situacao,ed60_c_parecer, ed60_matricula, ";
  $sCamposMatricula .= " ed221_i_serie as etapainicial,ed11_i_sequencia as seqetapainicial";
  $sWhereMatricula   = " ed60_c_ativa = 'S' AND ((ed60_i_turma = $ed59_i_turma AND ed221_i_serie = $codserieregencia ";
  $sWhereMatricula  .= " AND ed60_i_codigo in ($alunos) AND ed60_c_concluida = 'N') OR (ed60_i_turma = $ed59_i_turma ";
  $sWhereMatricula  .= " AND ed221_i_serie = $codserieregencia AND ed60_c_situacao != 'MATRICULADO'))";
  $sSqlMatricula     = $oDaoMatricula->sql_query("",$sCamposMatricula,"",$sWhereMatricula);
  $rsResultMatricula = $oDaoMatricula->sql_record($sSqlMatricula);
  $iLinhasMat        = $oDaoMatricula->numrows;

  for ($x = 0; $x < $iLinhasMat ; $x++) {

    $aDisciplinasComReprovacao = array();
    db_fieldsmemory($rsResultMatricula,$x);
    $sWhereHistoricoMps  = "ed11_i_codigo = $codserieregencia and ed47_i_codigo = $ed60_i_aluno ";
    $sWhereHistoricoMps .= " and ed29_i_codigo = $curso and ed62_c_resultadofinal ='P' ";
    $sSqlHistoricoMps    = $oDaoHistoricoMps->sql_query("", "*", "", $sWhereHistoricoMps);
    $rsHistoricoMps      = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);
    $iLinhasHistoricoMps = $oDaoHistoricoMps->numrows;


    $sWhereHistorico     = " ed61_i_aluno = $ed60_i_aluno AND ed61_i_curso = $curso";
    $sSqlHistorico       = $oDaoHistorico->sql_query_file("","ed61_i_codigo as codigohistorico","",$sWhereHistorico);
    $rsResultHistorico   = $oDaoHistorico->sql_record($sSqlHistorico);
    $iLinhasHistorico    = $oDaoHistorico->numrows;
    db_fieldsmemory($rsResultHistorico, 0);

    if (trim($ed60_c_situacao) == "MATRICULADO") {

      if ($iLinhasHistorico == 0 && $ed29_c_historico!='N') {

      	$oDaoHistorico->ed61_i_escola = $escola;
        $oDaoHistorico->ed61_i_aluno  = $ed60_i_aluno;
        $oDaoHistorico->ed61_i_curso  = $curso;
        $oDaoHistorico->incluir(null);
        $codigohistorico = $oDaoHistorico->ed61_i_codigo;

      } else {
        db_fieldsmemory($rsResultHistorico,0);
      }//fecha o else

    }//fecha o if trim($ed60_c_situacao) == "MATRICULADO"

    $sSqlDiario     = " SELECT ed95_i_codigo ";
    $sSqlDiario    .= "        FROM diario ";
    $sSqlDiario    .= "             inner join aluno on ed47_i_codigo = ed95_i_aluno ";
    $sSqlDiario    .= "             inner join matricula on ed60_i_aluno = ed47_i_codigo ";
    $sSqlDiario    .= "             inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
    $sSqlDiario    .= "             inner join regencia on ed59_i_codigo = ed95_i_regencia ";
    $sSqlDiario    .= "        WHERE ed95_i_aluno = $ed60_i_aluno ";
    $sSqlDiario    .= "              AND ed95_i_calendario = $calend ";
    $sSqlDiario    .= "              AND ed95_i_serie = $etapainicial ";
    $sSqlDiario    .= "              AND ed60_i_codigo = $ed60_i_codigo ";
    $sSqlDiario    .= "              AND ed59_i_turma = ed60_i_turma ";
    $sSqlDiario    .= "              AND ed74_c_resultadofinal != 'A' ";
    $sSqlDiario    .= "              AND ed59_c_condicao = 'OB' ";
    $rsResultDiario = db_query($sSqlDiario);
    $iLinhasDiario  = pg_num_rows($rsResultDiario);

    if (trim($ed60_c_situacao == "MATRICULADO")) {

      if (MatriculaPosterior($ed59_i_turma,$ed60_i_aluno) == "NAO") {

        if ($iLinhasDiario == 0) {

          $resultadoserie     = "A";
          $sCamposAlunoPossib = " ed56_i_codigo,ed79_i_codigo,ed56_i_calendario";
          $sWhereAlunoPossib  = " ed56_i_escola = $escola AND ed56_i_aluno = $ed60_i_aluno";
          $sSqlAlunoPossib    = $oDaoAlunoPossib->sql_query("",$sCamposAlunoPossib,"",$sWhereAlunoPossib);
          $rsResultPossib     = $oDaoAlunoPossib->sql_record($sSqlAlunoPossib);
          $iLinhasAlunoPoss   = $oDaoAlunoPossib->numrows;

          if ($iLinhasAlunoPoss > 0) {

            db_fieldsmemory($rsResultPossib,0);
            $sWhereBaseMps   = " ed34_i_base = $base AND ed34_i_serie = $codproximaserie";
            $sSqlBaseMps     = $oDaoBaseMps->sql_query("","ed34_i_codigo","",$sWhereBaseMps);
            $rsResultBaseMps = $oDaoBaseMps->sql_record($sSqlBaseMps);

            if ($oDaoBaseMps->numrows > 0 && $temproximaserie == true) {

              	if ($ed29_i_avalparcial == 2) {

              	  if ($iLinhasHistoricoMps > 0) {

              	    $situacao       = "APROVADO";

              	  }	else {

                    $situacao       = "APROVADO PARCIAL";

              	  }

              	} else {
                  $situacao = "APROVADO";
              	}
                $codbaseant     = "null";
                $codproximabase = $base;

            } else { //fecha o if $clbasemps->numrows > 0 && $temproximaserie == true

              $sWhereEscolaBase   = " ed77_i_base = $base AND ed77_i_escola = $escola";
      	      $sSqlEscolaBase     = $oDaoEscolaBase->sql_query("","ed77_i_basecont as basecont","",$sWhereEscolaBase);
              $rsResultEscolaBase = $oDaoEscolaBase->sql_record($sSqlEscolaBase);
              db_fieldsmemory($rsResultEscolaBase,0);

              if ($basecont != "") {


              	if ($ed29_i_avalparcial == 2) {

              	  if ($iLinhasHistoricoMps > 0) {

              	    $situacao       = "APROVADO";
              	    $codbaseant     = $base;
              	    $codproximabase = $basecont;

              	  }	else {

              	  	$situacao       = "APROVADO PARCIAL";
              	  	$codbaseant     = $base;
              	  	$codproximabase = $base;

              	  }

              	} else {
              	  $situacao = "APROVADO";

              	  $codbaseant     = $base;
              	  $codproximabase = $basecont;
              	}


              } else { //fecha o if $basecont != ""

                if ($etapainicial != $codproximaserie) {

              	  if ($ed29_i_avalparcial == 2) {

              	    if ($iLinhasHistoricoMps > 0) {
              	      $situacao = "APROVADO";
              	    } else {
              	  	  $situacao = "APROVADO PARCIAL";
              	    }
              	  } else {
              	  	$situacao = "APROVADO";
              	  }

                } else {
                  $situacao = "ENCERRADO";
                }

                $codbaseant     = "null";
                $codproximabase = $base;

              }//fecha o else do if $basecont != ""

            }//fecha o else

            $sSqlAlunoCurso     = " UPDATE alunocurso SET ";
            $sSqlAlunoCurso    .= "                   ed56_c_situacao      = '$situacao', ";
            $sSqlAlunoCurso    .= "                   ed56_i_escola        = $escola, ";
            $sSqlAlunoCurso    .= "                   ed56_i_base          = $codproximabase, ";
            $sSqlAlunoCurso    .= "                   ed56_i_calendario    = $ed56_i_calendario, ";
            $sSqlAlunoCurso    .= "                   ed56_i_baseant       = $codbaseant, ";
            $sSqlAlunoCurso    .= "                   ed56_i_calendarioant = null, ";
            $sSqlAlunoCurso    .= "                   ed56_c_situacaoant   = '$ed60_c_situacao' ";
            $sSqlAlunoCurso    .= "        WHERE ed56_i_codigo = $ed56_i_codigo ";
            $rsResultAlunoCurso = db_query($sSqlAlunoCurso);

            if ($ed29_i_avalparcial == 2) {

              if ($iLinhasHistoricoMps > 0) {
                $codproximaserie = $codproximaserie;
              } else {
            	$codproximaserie = 	$etapainicial;
              }
            }
            $sSqlAlunoPossib     = " UPDATE alunopossib SET ";
            $sSqlAlunoPossib    .= "                    ed79_i_serie    = $codproximaserie, ";
            $sSqlAlunoPossib    .= "                    ed79_i_turno    = $turno, ";
            $sSqlAlunoPossib    .= "                    ed79_i_turmaant = $ed59_i_turma, ";
            $sSqlAlunoPossib    .= "                    ed79_c_resulant = '$resultadoserie', ";
            $sSqlAlunoPossib    .= "                    ed79_c_situacao = 'A' ";
            $sSqlAlunoPossib    .= "        WHERE ed79_i_alunocurso = $ed56_i_codigo ";
            $rsResultAlunoPossib = db_query($sSqlAlunoPossib);

          }//fecha o if $clalunopossib->numrows > 0

        } else { //fecha o if iLinhasDiario ==0

          $resultadoserie    = "R";
          $sWhereAlunoPossib = " ed56_i_escola = $escola AND ed56_i_aluno = $ed60_i_aluno";
          $sSqlAlunoPossib   = $oDaoAlunoPossib->sql_query("","ed56_i_codigo,ed79_i_codigo","",$sWhereAlunoPossib);
          $rsResultPossib    = $oDaoAlunoPossib->sql_record($sSqlAlunoPossib);
          $iLinhasAluno      = $oDaoAlunoPossib->numrows;

          if ($iLinhasAluno > 0) {

            if ($iLinhasHistoricoMps > 0 && $ed29_i_avalparcial == 2) {
              $resultadoseriep = "A";
            }else{
              $resultadoseriep = "R";
            }
            db_fieldsmemory($rsResultPossib,0);
            $sSqlAlunoPoss     = " UPDATE alunopossib SET ";
            $sSqlAlunoPoss    .= "                    ed79_c_resulant = '$resultadoseriep', ";
            $sSqlAlunoPoss    .= "                    ed79_i_turmaant = $ed59_i_turma ";
            $sSqlAlunoPoss    .= "        WHERE ed79_i_codigo = $ed79_i_codigo ";
            $rsResultAlunoPoss = db_query($sSqlAlunoPoss);

            //altera situação do curso para candidato a mesma Etapa
            if (trim($ed60_c_situacao) == "MATRICULADO") {

              if ($iLinhasHistoricoMps > 0 && $ed29_i_avalparcial == 2) {
                $situacao_rep = "APROVADO PARCIAL";
              } else {
              	$situacao_rep = "REPETENTE";
              }

            } else {
              $situacao_rep = trim($ed60_c_situacao);
            }

            $sSqlAlunoCurso     = " UPDATE alunocurso SET ";
            $sSqlAlunoCurso    .= "                   ed56_c_situacao    = '$situacao_rep', ";
            $sSqlAlunoCurso    .= "                   ed56_c_situacaoant = '$ed60_c_situacao' ";
            $sSqlAlunoCurso    .= "        WHERE ed56_i_codigo = $ed56_i_codigo ";
            $rsResultAlunoCurso = db_query($sSqlAlunoCurso);

          }//fecha o if $clalunopossib->numrows > 0

        }//fecha o else

      }//fecha o if MatriculaPosterior($ed59_i_turma,$ed60_i_aluno) == "NAO"

    }//fecha o if trim($ed60_c_situacao == "MATRICULADO"
    /**
     * Inclusao dos dados do Historico
     */
    $situacaoserie = trim($ed60_c_situacao)=="MATRICULADO"?"CONCLUÍDO":$ed60_c_situacao;

    if (trim($ed60_c_situacao == "MATRICULADO")) {

      if (@$resultadoserie == "A" && $tipoturma == 2) {
        $condicao = " AND ed11_i_sequencia >= $seqetapainicial";
      } else {
        $condicao = " AND ed11_i_sequencia = $seqetapainicial";
      }

      $sWhereTurmaRegMat   = " ed220_i_turma = $ed59_i_turma $condicao";
      $sCampos             = "ed223_i_serie, ed220_i_procedimento";
      $sSqlTurmaRegMat     = $oDaoTurmaSerieRegimeMat->sql_query("",$sCampos,"",$sWhereTurmaRegMat);
      $rsResultTurmaRegMat = $oDaoTurmaSerieRegimeMat->sql_record($sSqlTurmaRegMat);
      $iLinhasTurmaReg     = $oDaoTurmaSerieRegimeMat->numrows;

      for ($tt = 0; $tt < $iLinhasTurmaReg; $tt++) {

        db_fieldsmemory($rsResultTurmaRegMat,$tt);
        $ed37_c_minimoaprov = '';

        if (!empty($ed220_i_procedimento)) {

          $sSql = $oDaoProcedimento->sql_query_formaavaliacao($ed220_i_procedimento, 'ed37_c_minimoaprov');
          $rs   = $oDaoProcedimento->sql_record($sSql);

          if ($oDaoProcedimento->numrows > 0) {

            db_fieldsmemory($rs, 0);
            if (empty($ed37_c_minimoaprov) || $ed37_c_minimoaprov <= 0 || !is_numeric($ed37_c_minimoaprov)) {
              $ed37_c_minimoaprov = '';
            }

          }//fecha o if $oDaoProcedimento->numrows > 0

        }//fecha o if !empty($ed220_i_procedimento


        $aprovadoaprovacaoparcial = false;
        $sWhereHistMps  = "ed11_i_codigo = $ed223_i_serie and ed47_i_codigo = $ed60_i_aluno ";
        $sWhereHistMps .= " and ed29_i_codigo = $curso and ed62_c_resultadofinal ='P' ";
        $sCamposHist    = "ed62_i_diasletivos,ed62_i_codigo as icodhistmps";
        $sSqlHistMps    = $oDaoHistoricoMps->sql_query("", $sCamposHist, "", $sWhereHistMps);
        $rsHistMps      = $oDaoHistoricoMps->sql_record($sSqlHistMps);
        $iLinhasHistMps = $oDaoHistoricoMps->numrows;

        ///aqui vai entrar quando é turma normal

        if ($ed29_i_avalparcial == 1 && $ed29_c_historico != "N") {

          $aprovadoaprovacaoparcial                    = true;
          $oDaoHistoricoMps->ed62_i_historico          = $codigohistorico;
          $oDaoHistoricoMps->ed62_i_escola             = $escola;
          $oDaoHistoricoMps->ed62_i_serie              = $ed223_i_serie;
          $oDaoHistoricoMps->ed62_i_turma              = $ed57_c_descr;
          $oDaoHistoricoMps->ed62_i_anoref             = $ano;
          $oDaoHistoricoMps->ed62_i_periodoref         = $periodo;
          $oDaoHistoricoMps->ed62_c_resultadofinal     = @$resultadoserie;
          $oDaoHistoricoMps->ed62_c_situacao           = $situacaoserie;
          $oDaoHistoricoMps->ed62_i_diasletivos        = $diasletivos;
          $oDaoHistoricoMps->ed62_i_qtdch              = 0;
          $oDaoHistoricoMps->ed62_c_minimo             = ArredondamentoNota::formatar($ed37_c_minimoaprov, $ano);
          $oDaoHistoricoMps->ed62_lancamentoautomatico = 't';


          $oDaoHistoricoMps->incluir(null);
          $ed62_i_codigo = $oDaoHistoricoMps->ed62_i_codigo;

        } else {

          ///vai entrar aqui se o aluno terminou o 2 semestre dai da update de P pra A
          if ($iLinhasHistMps > 0)  {

          	if (@$resultadoserie == "A") {
              db_fieldsmemory($rsHistMps, 0);
          	  $iDias                    = $ed62_i_diasletivos+$diasletivos;
              $sSqlUpHistMps            = " update historicomps set ed62_i_turma = '$ed57_c_descr', ";
              $sSqlUpHistMps           .= " ed62_c_resultadofinal = 'A' ,ed62_i_diasletivos = $iDias, ";
              $sSqlUpHistMps           .= " ed62_i_anoref = {$ano}, ed62_lancamentoautomatico = 't' ";
              $sSqlUpHistMps           .= " where ed62_i_codigo = $icodhistmps";
              $rsUpHistMps              = db_query($sSqlUpHistMps);
              $aprovadoaprovacaoparcial = true;
              $ed62_i_codigo            = $icodhistmps;
            } else {
        	  $aprovadoaprovacaoparcial = false;
            }
          } else {

        	///vai entrar somente se o aluno estiver no 1 semestre, ou seja nao tem nenhum registro no historico.
        	if (@$resultadoserie == "A" && $ed29_c_historico!='N') {

        	  $aprovadoaprovacaoparcial                      = true;
              $oDaoHistoricoMps->ed62_i_historico          = $codigohistorico;
              $oDaoHistoricoMps->ed62_i_escola             = $escola;
              $oDaoHistoricoMps->ed62_i_serie              = $ed223_i_serie;
              $oDaoHistoricoMps->ed62_i_turma              = $ed57_c_descr;
              $oDaoHistoricoMps->ed62_i_anoref             = $ano;
              $oDaoHistoricoMps->ed62_i_periodoref         = $periodo;
              $oDaoHistoricoMps->ed62_c_resultadofinal     = 'P';
              $oDaoHistoricoMps->ed62_c_situacao           = $situacaoserie;
              $oDaoHistoricoMps->ed62_i_diasletivos        = $diasletivos;
              $oDaoHistoricoMps->ed62_i_qtdch              = 0;
              $oDaoHistoricoMps->ed62_c_minimo             = ArredondamentoNota::formatar($ed37_c_minimoaprov, $ano);;
              $oDaoHistoricoMps->ed62_lancamentoautomatico = 't';
              $oDaoHistoricoMps->incluir(null);
              $ed62_i_codigo = $oDaoHistoricoMps->ed62_i_codigo;

            } else {
        	  $aprovadoaprovacaoparcial = false;
        	}
          }

        }

        $sCamposRegencia  = " ed59_i_codigo as codregencia,ed59_i_disciplina as discregencia, ";
        $sCamposRegencia .= " ed59_c_freqglob as freqregencia,ed59_i_qtdperiodo as qtdperiodo, ";
        $sCamposRegencia .= " ed59_c_condicao as tipocondicao,ed59_i_ordenacao, ed59_lancarhistorico as lancarhistorico";
        $sWhereRegencia   = "ed59_i_turma = $ed59_i_turma AND ed59_i_serie = $codserieregencia";
        $sSqlRegencia     = $oDaoRegencia->sql_query_file("",$sCamposRegencia,"",$sWhereRegencia);
        $rsResultRegencia = $oDaoRegencia->sql_record($sSqlRegencia);
        $iLinhasRegencia  = $oDaoRegencia->numrows;
        $somacargah       = 0;

        //if ($aprovadoaprovacaoparcial == true) {
          for ($w = 0; $w < $iLinhasRegencia; $w++) {

            db_fieldsmemory($rsResultRegencia,$w);
            $sSqlDiario     = " SELECT ed74_i_diario, ";
            $sSqlDiario    .= "        ed74_c_resultadofinal, ";
            $sSqlDiario    .= "        ed74_c_valoraprov, ";
            $sSqlDiario    .= "        ed74_i_codigo, ";
            $sSqlDiario    .= "        substr(ed37_c_tipo,1,1) as ed37_c_tipo, ";
            $sSqlDiario    .= "        case when ed81_c_todoperiodo = 'S' then 'AMPARADO' ";
            $sSqlDiario    .= "             else 'CONCLUÍDO' end as situacaodisc, ";
            $sSqlDiario    .= "        ed81_i_justificativa, ";
            $sSqlDiario    .= "        ed81_c_aprovch ";
            $sSqlDiario    .= "        FROM diario ";
            $sSqlDiario    .= "             inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
            $sSqlDiario    .= "             left join amparo on ed81_i_diario = ed95_i_codigo ";
            $sSqlDiario    .= "             left join procresultado on ed43_i_codigo = ed74_i_procresultadoaprov ";
            $sSqlDiario    .= "             left join formaavaliacao on ed37_i_codigo = ed43_i_formaavaliacao ";
            $sSqlDiario    .= "        WHERE ed95_i_regencia = $codregencia ";
            $sSqlDiario    .= "              AND ed95_i_serie = $codserieregencia ";
            $sSqlDiario    .= "              AND ed95_i_aluno = $ed60_i_aluno ";
            $rsResultDiario = db_query($sSqlDiario);
            db_fieldsmemory($rsResultDiario,0);

            if ($ed60_c_parecer == "S") {
              $ed37_c_tipo = "P";
            }

            /**
             * Verificamos se existe alguma progressao para a disciplina em aberto, com a situcao de reprovado,
             * e aprovamos mesma, caso o aluno tenha passado na disciplina no curso normal
             */
            if ($oParametroProgressaoParcial->disciplinaAprovadaEliminaProgressao() && $ed74_c_resultadofinal == "A") {

              $oDaoProgressaoParcialAluno  = db_utils::getDao("progressaoparcialaluno");
              $sWhereProgressao            = " ed114_disciplina = {$discregencia} ";
              $sWhereProgressao           .= " and ed114_aluno  = {$ed60_i_aluno}";
              $sWhereProgressao           .= " and ed121_resultadofinal = 'R'";
              $sWhereProgressao           .= " and ed114_situacaoeducacao  = " . ProgressaoParcialAluno::ATIVA;
              $sWhereProgressao           .= " and ed150_encerrado is true";
              $sSqlProgressao              = $oDaoProgressaoParcialAluno->sql_query_resultado_final(null,
                                                                                                   "ed114_sequencial,
                                                                                                    ed121_sequencial",
                                                                                                    null,
                                                                                                    $sWhereProgressao
                                                                                                  );

              $rsProgressao = $oDaoProgressaoParcialAluno->sql_record($sSqlProgressao);
              if ($oDaoProgressaoParcialAluno->numrows > 0) {

                $oDadosProgressaoDiario = db_utils::fieldsMemory($rsProgressao, 0);
                $oDaoProgressaoDiario   = db_utils::getDao("progressaoparcialalunoencerradodiario");

                $oDaoProgressaoDiario->ed151_diariofinal            = $ed74_i_codigo;
                $oDaoProgressaoDiario->ed151_progressaoparcialaluno = $oDadosProgressaoDiario->ed114_sequencial;
                $oDaoProgressaoDiario->incluir(null);

                $oDaoProgressaoParcialAluno->ed114_situacaoeducacao = ProgressaoParcialAluno::CONCLUIDA;
                $oDaoProgressaoParcialAluno->ed114_tipoconclusao    = 2;
                $oDaoProgressaoParcialAluno->ed114_sequencial       = $oDadosProgressaoDiario->ed114_sequencial;
                $oDaoProgressaoParcialAluno->alterar($oDadosProgressaoDiario->ed114_sequencial);

              }
            }
            $ed74_c_resultadofinal = $situacaodisc == "AMPARADO" ? "A" : $ed74_c_resultadofinal;
            $ed74_c_valoraprov     = $situacaodisc == "AMPARADO" ? "" : $ed74_c_valoraprov;
            $ed37_c_tipo           = $situacaodisc == "AMPARADO" ? "A" : $ed37_c_tipo;

            $sWhereRegPeriodo    = " ed78_i_regencia = $codregencia AND ed09_c_somach = 'S'";
            $sSqlRegenciaPeriodo = $oDaoRegenciaPeriodo->sql_query("","sum(ed78_i_aulasdadas) as aulas",
                                                                   "",$sWhereRegPeriodo
                                                                  );
            $rsResultRegPeriodo  = $oDaoRegenciaPeriodo->sql_record($sSqlRegenciaPeriodo);
            db_fieldsmemory($rsResultRegPeriodo,0);
            $aulas = $aulas == "" ? 0 : $aulas;
            if ($ed74_c_resultadofinal == "R") {
              $aDisciplinasComReprovacao[] = $ed74_i_diario;
            }
            if ($situacaodisc == "AMPARADO" && $ed81_c_aprovch == "N") {
              $cargah = 0;
            } else {
              $cargah = $aulas;
            }

            $lancarhistorico = $lancarhistorico == 't' ? true : false;
            if ($lancarhistorico && $aprovadoaprovacaoparcial == true) {
              /**
               * Verificamos se o aluno passou na disciplina
               */
              if ($ed29_i_avalparcial == 2) {

                if ($iLinhasHistoricoMps > 0) {

                  $ed74_c_resultadofinal = 'A';
                  $sSqlUp                = " UPDATE histmpsdisc SET ed65_c_resultadofinal = 'A' ";
                  $sSqlUp               .= " where ed65_i_historicomps = $ed62_i_codigo";
                  $rsResultUp            = db_query($sSqlUp);

                } else {
              	  $ed74_c_resultadofinal='P';
                }

              }

              $sResultadoObtido = ArredondamentoNota::formatar($ed74_c_valoraprov, $ano);
              if ($tipocondicao == 'OP') {

                $ed74_c_resultadofinal = 'A';
                if (trim($ed74_c_valoraprov) == '') {
                  $sResultadoObtido = '-';
                }
              }

              $oDaoHistMpsDisc->ed65_i_historicomps       = $ed62_i_codigo;
              $oDaoHistMpsDisc->ed65_i_disciplina         = $discregencia;
              $oDaoHistMpsDisc->ed65_i_justificativa      = $ed81_i_justificativa;
              $oDaoHistMpsDisc->ed65_i_qtdch              = $cargah;
              $oDaoHistMpsDisc->ed65_c_resultadofinal     = $ed74_c_resultadofinal;
              $oDaoHistMpsDisc->ed65_t_resultobtido       = $sResultadoObtido;
              $oDaoHistMpsDisc->ed65_c_situacao           = $situacaodisc;
              $oDaoHistMpsDisc->ed65_c_tiporesultado      = $ed37_c_tipo;
              $oDaoHistMpsDisc->ed65_i_ordenacao          = $ed59_i_ordenacao;
              $oDaoHistMpsDisc->ed65_lancamentoautomatico = 't';
              $oDaoHistMpsDisc->ed65_opcional             = $tipocondicao == 'OP' ? 'true' : 'false';
              $oDaoHistMpsDisc->incluir(null);

            }//fecha if $tipocondicao == "OB"

            //finaliza diario,se situacao for MATRICULADO
            $sSqlUpDiario     = "UPDATE diario SET ed95_c_encerrado = 'S' where ed95_i_codigo = $ed74_i_diario";
            $rsResultUpDiario = db_query($sSqlUpDiario);
            $somacargah      += $cargah;

          }//fecha o for $clregencia->numrows

        //}//if aprovado e aprovacao parcial

        if ($medfrequencia == "DIAS LETIVOS") {
          $somacargah = $diasletivos*4;
        } else {
          $somacargah = $somacargah;
        }
        if (@$resultadoserie == "A" && $ed29_c_historico != 'N') {

          $sSqlMps     = "UPDATE historicomps SET ed62_i_qtdch = '$somacargah' where ed62_i_codigo = $ed62_i_codigo";
          $rsResultMps = db_query($sSqlMps);
        }

      }//fecha o for $clturmaserieregimemat->numrows

    } else { ///fecha o if trim($ed60_c_situacao == "MATRICULADO"

      //finaliza diario,se situacao for diferente de MATRICULADO
      $sSqlUpDiario     = " UPDATE diario SET ";
      $sSqlUpDiario    .= "               ed95_c_encerrado = 'S' ";
      $sSqlUpDiario    .= "        where ed95_i_aluno = $ed60_i_aluno ";
      $sSqlUpDiario    .= "              AND ed95_i_regencia in (select ed59_i_codigo from regencia ";
      $sSqlUpDiario    .= "                  where ed59_i_turma = $ed59_i_turma ";
      $sSqlUpDiario    .= "                        AND ed59_i_serie = $codserieregencia)";
      $rsResultUpDiario = db_query($sSqlUpDiario);

    }//fecha o else do if trim($ed60_c_situacao == "MATRICULADO"

    //finaliza matricula
    $sSqlUpMat     = " UPDATE matricula SET ";
    $sSqlUpMat    .= "                  ed60_c_concluida = 'S', ";
    $sSqlUpMat    .= "                  ed60_d_datamodif = '".date("Y-m-d",db_getsession("DB_datausu"))."' ";
    $sSqlUpMat    .= "        where ed60_i_codigo = $ed60_i_codigo";
    $rsResultUpMat = db_query($sSqlUpMat);

    if (trim($ed60_c_situacao) == "MATRICULADO") {

      if (trim($ed60_c_situacao) != "MATRICULADO") {
        $situacaomov = trim($ed60_c_situacao);
      } else {

        if (@$resultadoserie == "A") {

         if ($ed29_i_avalparcial == 2) {

  	       if ($iLinhasHistoricoMps > 0) {
  	         $situacaomov =  $sLabelAprovado;
  	       } else {
  	        $situacaomov=	$sLabelAprovadoParcial;
  	       }
 	     } else {
 	       $situacaomov =  $sLabelAprovado;
 	     }

        } else {
          $situacaomov = $sLabelReprovado;
        }

      }//fecha o else

      $oDaoMatriculaMov->ed229_i_matricula    = $ed60_i_codigo;
      $oDaoMatriculaMov->ed229_i_usuario      = db_getsession("DB_id_usuario");
      $oDaoMatriculaMov->ed229_c_procedimento = "ENCERRAR AVALIAÇÕES";
      $sDescricao                           = "MATRÍCULA ENCERRADA EM ".date("d/m/Y",db_getsession("DB_datausu"));
      $sDescricao                          .= " COM SITUAÇÃO DE ".$situacaomov;
      $oDaoMatriculaMov->ed229_t_descr        = $sDescricao;
      $oDaoMatriculaMov->ed229_d_dataevento   = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoMatriculaMov->ed229_c_horaevento   = date("H:i");
      $oDaoMatriculaMov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoMatriculaMov->incluir(null);

    }//fecha o if trim($ed60_c_situacao) == "MATRICULADO"

   /**
    * Verificamos se o aluno está em progressao parcial, e incluimos os dados da progressao.
    */
    if ($oParametroProgressaoParcial->isHabilitada() && count($aDisciplinasComReprovacao) <= $iQuantidadeDisciplinas) {

      $oMatricula    = new Matricula($ed60_i_codigo);
      $oDiarioClasse = $oMatricula->getDiarioDeClasse();
      $oDiarioClasse->adicionarDisciplinasReprovadasComoProgressaoParcial();
      if ($oDiarioClasse->aprovadoComProgressaoParcial()) {

        $sSqlAlunoPossib     = " UPDATE alunopossib SET ";
        $sSqlAlunoPossib    .= "                    ed79_i_serie    = $codproximaserie, ";
        $sSqlAlunoPossib    .= "                    ed79_c_resulant = 'A', ";
        $sSqlAlunoPossib    .= "                    ed79_c_situacao = 'A' ";
        $sSqlAlunoPossib    .= "        WHERE ed79_i_alunocurso = $ed56_i_codigo ";
        $rsResultAlunoPossib = db_query($sSqlAlunoPossib);

        $sSqlAlunoCurso     = " UPDATE alunocurso SET ";
        $sSqlAlunoCurso    .= "                   ed56_c_situacao = 'APROVADO' ";
        $sSqlAlunoCurso    .= "        WHERE ed56_i_codigo = $ed56_i_codigo ";
        $rsResultAlunoCurso = db_query($sSqlAlunoCurso);

        /**
         * Corrigimos a movimentacao do aluno
         */
         if ($oDaoMatriculaMov->ed229_i_codigo != "") {

           $sDescricao                      = "MATRÍCULA ENCERRADA EM ".date("d/m/Y",db_getsession("DB_datausu"));
           $sDescricao                     .= " COM SITUAÇÃO DE {$sLabelAprovado} COM PROGRESSÃO PARCIAL/DEPENDÊNCIA";
           $oDaoMatriculaMov->ed229_t_descr = $sDescricao;
           $oDaoMatriculaMov->ed229_i_codigo = $oDaoMatriculaMov->ed229_i_codigo;
           $oDaoMatriculaMov->alterar($oDaoMatriculaMov->ed229_i_codigo);
         }
      }
    }


  }//fecha o for matricula

  //finaliza regencia
  $sWhereReg   = "ed59_i_turma = $ed59_i_turma AND ed59_i_serie = $codserieregencia";
  $sSqlReg     = $oDaoRegencia->sql_query_file("","ed59_i_codigo as codregencia","",$sWhereReg);
  $rsResultReg = $oDaoRegencia->sql_record($sSqlReg);
  $iLinhasRege = $oDaoRegencia->numrows;

  for ($i = 0; $i < $oDaoRegencia->numrows; $i++) {

    db_fieldsmemory($rsResultReg,$i);
    $sWhereDiario = "ed95_i_regencia = $codregencia AND ed95_c_encerrado = 'N'";
    $sSqlDiario   = $oDaoDiario->sql_query_file("","ed95_i_regencia as fimregencia","",$sWhereDiario);
    $result_dia   = $oDaoDiario->sql_record($sSqlDiario);

    if ($oDaoDiario->numrows == 0) {

      //se todos diarios foram encerrados , finaliza regencia
      $sSqlUpReg     = "UPDATE regencia SET ed59_c_encerrada = 'S' where ed59_i_codigo = $codregencia";
      $rsResultUpReg = db_query($sSqlUpReg);

    }

  }//fecha o for regencia

  $sWhereRegencia   = "ed59_i_turma = $ed59_i_turma AND ed59_i_serie = $codserieregencia AND ed59_c_encerrada = 'N'";
  $sSqlRegencia     = $oDaoRegencia->sql_query_file("","ed59_i_codigo as codregencia","",$sWhereRegencia);
  $rsResultRegVerif = $oDaoRegencia->sql_record($sSqlRegencia);
  $iLinhasReg       = $oDaoRegencia->numrows;

  if ($iLinhasReg == 0) {
    $msgalert = "Encerramento de avaliações concluído para toda esta turma!";
  } else {
    $msgalert = "Encerramento de avaliações concluído parcialmente para esta turma!";
  }
  ?>
  <script>
    alert("<?=$msgalert?>");
    parent.dados.location.href = " edu1_diarioclasse004.php?turma=<?=$turma?>&ed57_c_descr=<?=$ed57_c_descr?>"+
                                 "&ed52_c_descr=<?=$ed52_c_descr?>&codserieregencia=<?=$codserieregencia?>";
    parent.db_iframe_encerrar<?=$turma?>.hide();

  </script>
  <?
  db_fim_transacao();
  //db_query("rollback");
  exit;

}//fecha o confirmar

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;

}
.alunopq{
 color: #000000;
 font-family : Tahoma;
 font-size: 9;
 padding-top: 0px;
 padding-bottom: 0px;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<?
$sCamposRegPer       = "ed78_i_regencia,ed78_i_procavaliacao,ed78_i_aulasdadas,ed09_c_descr,ed232_c_descr,ed59_i_ordenacao";
$sWhereRegPer        = " ed59_i_turma = $ed59_i_turma AND ed59_i_serie = $codserieregencia AND ed59_c_freqglob!='A' ";
$sWhereRegPer       .= " AND ed09_c_somach = 'S' AND ed59_c_condicao = 'OB'";
$sSqlRegenciaPeriodo = $oDaoRegenciaPeriodo->sql_query("",$sCamposRegPer,"ed59_i_ordenacao",$sWhereRegPer);
$rsResultRegPeriodo  = $oDaoRegenciaPeriodo->sql_record($sSqlRegenciaPeriodo);
$embranco            = "";
$mensagem            = "";
$sep                 = "";
$faltaaprov          = false;
$mudaregencia        = "";

for ($x = 0; $x < $oDaoRegenciaPeriodo->numrows; $x++) {

  db_fieldsmemory($rsResultRegPeriodo,$x);
  if ($mudaregencia != $ed78_i_regencia) {

    $mensagem    .= "<hr>";
    $mudaregencia = $ed78_i_regencia;

  }

  if ($ed78_i_aulasdadas == "") {

    $embranco .= "S";
    $mensagem .= $sep." * Falta informar aulas dadas no período $ed09_c_descr para disciplina $ed232_c_descr";
    $sep       = "|";

  }

}

if (strstr($embranco,"S")) {

  $mensagens = explode("|",$mensagem);
  ?>
  <table border='0' width="100%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
   <tr>
    <td class='titulo'>
      Não foi possível encerrar as avaliações da turma <?=$ed57_c_descr?>
    </td>
   </tr>
   <?
   for ($x = 0; $x < count($mensagens); $x++) {

     ?>
     <tr>
      <td class='aluno'>
       <?=$mensagens[$x]?>
      </td>
     </tr>
   <?

   }
 ?></table><?

} else {

  $sSqlMatricula     = " SELECT DISTINCT ed60_i_codigo,to_ascii(ed47_v_nome) as ed47_v_nome,ed60_i_numaluno ";
  $sSqlMatricula    .= "       FROM matricula ";
  $sSqlMatricula    .= "            inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
  $sSqlMatricula    .= "            inner join aluno on ed47_i_codigo = ed60_i_aluno ";
  $sSqlMatricula    .= "            inner join diario on ed95_i_aluno = ed47_i_codigo ";
  $sSqlMatricula    .= "            inner join regencia on ed59_i_codigo = ed95_i_regencia ";
  $sSqlMatricula    .= "            inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
  $sSqlMatricula    .= "            inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
  $sSqlMatricula    .= "            inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
  $sSqlMatricula    .= "       WHERE ed60_i_turma = $ed59_i_turma ";
  $sSqlMatricula    .= "             AND ed221_i_serie = $codserieregencia ";
  $sSqlMatricula    .= "             AND ed60_c_situacao = 'MATRICULADO' ";
  $sSqlMatricula    .= "             AND ed60_c_concluida = 'N' ";
  $sSqlMatricula    .= "             AND ed95_c_encerrado = 'N' ";
  $sSqlMatricula    .= "             AND ed59_c_condicao = 'OB' ";
  $sSqlMatricula    .= "             AND ed221_c_origem = 'S' ";
  $sSqlMatricula    .= "             AND not exists (select 1 from alunonecessidade where ed214_i_aluno = ed47_i_codigo)";
  $sSqlMatricula    .= "       ORDER BY ed60_i_numaluno,to_ascii(ed47_v_nome) ";

  $rsResultMatricula = db_query($sSqlMatricula);
  $iLinhasMatricula  = pg_num_rows($rsResultMatricula);
  $naopode           = 0;
  $sep               = "";
  if ($iLinhasMatricula > 0) {

    db_inicio_transacao();
    $aPendenciasGeral  = array();
    for ($x = 0; $x < $iLinhasMatricula; $x++) {

      $oDadosAluno = db_utils::fieldsMemory($rsResultMatricula, $x);
      $oMatricula  = new Matricula($oDadosAluno->ed60_i_codigo);
      $oDiario     = $oMatricula->getDiarioDeClasse();
      $aPendencias = $oDiario->getPendenciasEncerramento();
      if (count($aPendencias) > 0) {

        $oPendencia            = new stdClass();
        $oPendencia->aluno     = $oDadosAluno->ed47_v_nome;
        $oPendencia->matricula = $oDadosAluno->ed60_i_codigo;
        $oPendencia->ordem     = $oDadosAluno->ed60_i_numaluno;
        $oPendencia->detalhe   = implode("<br>", $aPendencias);
        $aPendenciasGeral[]    = $oPendencia;
      }
      unset($oMatricula);
    }
    db_inicio_transacao(true);
    $faltaaprov = true;
    if (count($aPendenciasGeral) > 0) {
      ?>

      <table border='1' width="100%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
       <tr>
        <td class='titulo' colspan="3">
         Não foi possível encerrar as avaliações dos seguintes alunos:
        </td>
       </tr>
       <tr>
        <td class='cabec1'>N°</td>
        <td class='cabec1'>Aluno</td>
        <td class='cabec1'>Detalhes</td>
       </tr>
       <?
       $cor1 = "#f3f3f3";
       $cor2 = "#DBDBDB";
       $cor  = "";
       foreach ($aPendenciasGeral as $oPendencia) {

         $naopode .= $sep.$oPendencia->matricula;
         $sep      = ",";

         if ($cor == $cor1) {
           $cor = $cor2;
         } else {
           $cor = $cor1;
         }
         ?>
         <tr bgcolor="<?=$cor?>">
          <td class='aluno'>
           <?=$oPendencia->matricula==""||$oPendencia->ordem==null?"&nbsp;":$oPendencia->ordem?>
          </td>
          <td class='aluno'>
           <?=$oPendencia->aluno?>
          </td>
          <td class='aluno'>
          <?=$oPendencia->detalhe?>
      </td>
     </tr>
     <?
    }
    ?></table><br><?
    }
 }
 $sSqlMatri     = " SELECT DISTINCT ";
 $sSqlMatri    .= "        ed60_i_codigo,  ";
 $sSqlMatri    .= "        ed60_i_numaluno, ";
 $sSqlMatri    .= "        ed60_i_aluno, ";
 $sSqlMatri    .= "        ed60_c_situacao, ";
 $sSqlMatri    .= "        to_ascii(ed47_v_nome) as ed47_v_nome, ";
 $sSqlMatri    .= "        ed221_i_serie as etapaorigem ";
 $sSqlMatri    .= "        FROM matricula ";
 $sSqlMatri    .= "             inner join aluno on ed47_i_codigo = ed60_i_aluno ";
 $sSqlMatri    .= "             inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
 $sSqlMatri    .= "             inner join diario on ed95_i_aluno = ed47_i_codigo ";
 $sSqlMatri    .= "             inner join regencia on ed59_i_codigo = ed95_i_regencia ";
 $sSqlMatri    .= "             inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
 $sSqlMatri    .= "             inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
 $sSqlMatri    .= "             inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
 $sSqlMatri    .= "        WHERE ed60_i_turma = $ed59_i_turma ";
 $sSqlMatri    .= "              AND ed59_i_serie = $codserieregencia ";
 $sSqlMatri    .= "              AND ed221_i_serie = $codserieregencia ";
 $sSqlMatri    .= "              AND ed60_i_codigo not in ($naopode) ";
 $sSqlMatri    .= "              AND ed60_c_situacao = 'MATRICULADO' ";
 $sSqlMatri    .= "              AND ed60_c_ativa = 'S' ";
 $sSqlMatri    .= "              AND ed60_c_concluida = 'N' ";
 $sSqlMatri    .= "              AND ed95_c_encerrado = 'N' ";
 $sSqlMatri    .= "              AND ed59_c_condicao = 'OB' ";
 $sSqlMatri    .= "              AND ed221_c_origem = 'S' ";
 $sSqlMatri    .= "              AND ed74_c_resultadofreq != '' ";
 $sSqlMatri    .= "              AND ed74_c_resultadoaprov != ''";
 $sSqlMatri    .= " UNION ";
 $sSqlMatri    .= " SELECT DISTINCT ";
 $sSqlMatri    .= "        ed60_i_codigo, ";
 $sSqlMatri    .= "        ed60_i_numaluno, ";
 $sSqlMatri    .= "        ed60_i_aluno, ";
 $sSqlMatri    .= "        ed60_c_situacao, ";
 $sSqlMatri    .= "        to_ascii(ed47_v_nome) as ed47_v_nome, ";
 $sSqlMatri    .= "        ed221_i_serie as etapaorigem ";
 $sSqlMatri    .= "        FROM matricula ";
 $sSqlMatri    .= "             inner join aluno on ed47_i_codigo = ed60_i_aluno ";
 $sSqlMatri    .= "             inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
 $sSqlMatri    .= "             inner join turma on ed57_i_codigo = ed60_i_turma ";
 $sSqlMatri    .= "             inner join regencia on ed59_i_turma = ed57_i_codigo ";
 $sSqlMatri    .= "             inner join disciplina on ed12_i_codigo = ed59_i_disciplina ";
 $sSqlMatri    .= "             inner join caddisciplina on ed232_i_codigo= ed12_i_caddisciplina ";
 $sSqlMatri    .= "        WHERE ed60_i_turma = $ed59_i_turma ";
 $sSqlMatri    .= "              AND ed59_i_serie = $codserieregencia ";
 $sSqlMatri    .= "              AND ed221_i_serie = $codserieregencia ";
 $sSqlMatri    .= "              AND ed60_c_situacao != 'MATRICULADO' ";
 $sSqlMatri    .= "              AND ed60_c_situacao != 'AVANÇADO' ";
 $sSqlMatri    .= "              AND ed60_c_situacao != 'CLASSIFICADO' ";
 $sSqlMatri    .= "              AND ed60_c_ativa = 'S' ";
 $sSqlMatri    .= "              AND ed60_c_concluida = 'N' ";
 $sSqlMatri    .= "              AND ed59_c_condicao = 'OB' ";
 $sSqlMatri    .= "              AND ed221_c_origem = 'S' ";
 $sSqlMatri    .= "              ORDER BY ed60_i_numaluno,ed47_v_nome ";

 $rsResultMatri = db_query($sSqlMatri);
 $iLinhasMatri  = pg_num_rows($rsResultMatri);

  if ($iLinhasMatri > 0) {

  ?>
  <table border='1' width="100%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
  <tr>
   <td class='titulo' colspan="3">
    O sistema vai encerrar as matrículas dos seguintes alunos desta turma:
   </td>
  </tr>
  <tr>
   <td class='cabec1'>N°</td>
   <td class='cabec1'>Aluno</td>
   <td class='cabec1'>Resultado Final</td>
  </tr>
  <?
  $cor1   = "#f3f3f3";
  $cor2   = "#DBDBDB";
  $cor    = "";
  $alunos = "";
  $sep    = "";
  for ($x = 0; $x < $iLinhasMatri; $x++) {

    db_fieldsmemory($rsResultMatri,$x);
    $alunos .= $sep.$ed60_i_codigo;
    $sep     = ",";

    if ($cor == $cor1) {
      $cor = $cor2;
    } else {
      $cor = $cor1;
    }
   ?>
   <tr bgcolor="<?=$cor?>">
    <td class='aluno'><?=$ed60_i_numaluno==""||$ed60_i_numaluno==null?"&nbsp;":$ed60_i_numaluno?></td>
    <td class='aluno'><?=$ed60_i_aluno?> - <?=$ed47_v_nome?></td>
    <td class='aluno'>
     <?
     if ($ed60_c_situacao != "MATRICULADO") {
       echo trim($ed60_c_situacao);
     } else {

       $sSqlDia     = " SELECT ed95_i_codigo ";
       $sSqlDia    .= "        FROM diario  ";
       $sSqlDia    .= "             inner join aluno on ed47_i_codigo = ed95_i_aluno ";
       $sSqlDia    .= "             inner join matricula on ed60_i_aluno = ed47_i_codigo ";
       $sSqlDia    .= "             inner join matriculaserie on ed221_i_matricula = ed60_i_codigo ";
       $sSqlDia    .= "             inner join diariofinal on ed74_i_diario = ed95_i_codigo ";
       $sSqlDia    .= "             inner join regencia on ed59_i_codigo = ed95_i_regencia ";
       $sSqlDia    .= "        WHERE ed95_i_aluno = $ed60_i_aluno ";
       $sSqlDia    .= "              AND ed95_i_calendario = $calend ";
       $sSqlDia    .= "              AND ed95_i_serie = $etapaorigem ";
       $sSqlDia    .= "              AND ed59_i_serie = $codserieregencia ";
       $sSqlDia    .= "              AND ed221_i_serie = $codserieregencia ";
       $sSqlDia    .= "              AND ed60_i_codigo = $ed60_i_codigo ";
       $sSqlDia    .= "              AND ed59_c_condicao = 'OB' ";
       $sSqlDia    .= "              AND ed221_c_origem = 'S' ";
       $sSqlDia    .= "              AND ed60_i_turma = ed59_i_turma ";
       $sSqlDia    .= "              AND (case when ed59_c_freqglob <> 'F' then ed74_c_resultadofinal != 'A' ";
       $sSqlDia    .= "                        else ed74_c_resultadofreq <> 'A' end)";
       $sSqlDia    .= "              ORDER BY to_ascii(ed47_v_nome) ";
       $rsResultDia = db_query($sSqlDia);
       $iLinhasDia  = pg_num_rows($rsResultDia);

       if ($iLinhasDia == 0) {

         $sSqlCursoEdu =  $clcurso->sql_query("","*","","ed29_i_codigo = $curso");
         $rsCursoEdu   = $clcurso->sql_record($sSqlCursoEdu);
         db_fieldsmemory($rsCursoEdu,0);

         if ($ed29_i_avalparcial == 2) {


  	       $sWhereHistoricoMps  = "ed11_i_codigo = $codserieregencia and ed47_i_codigo = $ed60_i_aluno ";
           $sWhereHistoricoMps .= " and ed29_i_codigo = $curso and ed62_c_resultadofinal='P'";
           $sSqlHistoricoMps    = $oDaoHistoricoMps->sql_query("", "*", "", $sWhereHistoricoMps);
           $rsHistoricoMps      = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);
           $iLinhasHistoricoMps = $oDaoHistoricoMps->numrows;


  	       if ($iLinhasHistoricoMps > 0) {
  	         $sResultadoFinal = $sLabelAprovado;
  	       } else {
  	       	 $sResultadoFinal = "$sLabelAprovadoParcial";
  	       }

 	     } else {
 	       $sResultadoFinal = "$sLabelAprovado";
 	     }

       } else  {

       	/*if ($iLinhasHistoricoMps > 0) {
  	         echo "APROVADO ";
  	       } else {
  	       	 echo "APROVADO PARCIAL";
  	       }*/
         $sResultadoFinal = "$sLabelReprovado";
       }

       /**
        * Verificamos o total de disciplinas em que o Aluno foi reprovado
        */
       if ($oParametroProgressaoParcial->isHabilitada()) {

         db_inicio_transacao();
         $oMatricula    = new Matricula($ed60_i_codigo);
         $oDiarioClasse = $oMatricula->getDiarioDeClasse();
         db_fim_transacao(false);
         if ($oDiarioClasse->aprovadoComProgressaoParcial()) {
           $sResultadoFinal = " {$sLabelAprovado} (Progressão Parcial / Dependência)";
         }
       }
       echo $sResultadoFinal;
     }
     ?>
    </td>
   </tr>
   <?
  }
  ?>
  <tr bgcolor="#f3f3f3">
   <td align="center" class='aluno' colspan="3">
    <form name="form1" method="post" action="">
    <input type="submit" name="confirmar" value="Confirmar">
    <input name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_encerrar<?=$turma?>.hide();">
    <input type="hidden" name="alunos" value="<?=$alunos?>">
    <input type="hidden" name="turma" value="<?=$turma?>">
    <input type="hidden" name="ed57_c_descr" value="<?=$ed57_c_descr?>">
    <input type="hidden" name="codserieregencia" value="<?=$codserieregencia?>">
    </form>
   </td>
  </tr>
  </table><?
 } else if ($iLinhasMatri == 0 && $faltaaprov == false) {
  ?>
  <table border='1' width="100%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
  <tr>
   <td class='titulo'>
    Todos os alunos já possuem avaliações encerradas.
   </td>
  </tr>
  <tr>
   <td align="center">
    <input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_encerrar<?=$turma?>.hide();">
   </td>
  </tr>
  <?
 }
}
?>
</body>
</html>