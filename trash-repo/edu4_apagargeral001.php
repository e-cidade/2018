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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);
$oDaoAbonoFalta                  = db_utils::getdao("abonofalta");
$oDaoAlunoCurso                  = db_utils::getdao("alunocurso");
$oDaoAlunoPossib                 = db_utils::getdao("alunopossib");
$oDaoAlunoTransfTurma            = db_utils::getdao("alunotransfturma");
$oDaoAmparo                      = db_utils::getdao("amparo");
$oDaoAprovConselho               = db_utils::getdao("aprovconselho");
$oDaoAtestVaga                   = db_utils::getdao("atestvaga");
$oDaoAvalCompoeres               = db_utils::getdao("avalcompoeres");
$oDaoAvalFreqRes                 = db_utils::getdao("avalfreqres");
$oDaoBase                        = db_utils::getdao("base");
$oDaoBaseAto                     = db_utils::getdao("baseato");
$oDaoBaseAtoSerie                = db_utils::getdao("baseatoserie");
$oDaoBaseDiscGlob                = db_utils::getdao("basediscglob");
$oDaoBaseMpd                     = db_utils::getdao("basempd");
$oDaoBaseMps                     = db_utils::getdao("basemps");
$oDaoBaseRegimeMatDiv            = db_utils::getdao("baseregimematdiv");
$oDaoBaseSerie                   = db_utils::getdao("baseserie");
$oDaoCalendario                  = db_utils::getdao("calendario");
$oDaoCalendarioEscola            = db_utils::getdao("calendarioescola");
$oDaoDiario                      = db_utils::getdao("diario");
$oDaoDiarioAvaliacao             = db_utils::getdao("diarioavaliacao");
$oDaoDiarioFinal                 = db_utils::getdao("diariofinal");
$oDaoDiarioResultado             = db_utils::getdao("diarioresultado");
$oDaoEscola                      = db_utils::getdao("escola");
$oDaoEscolaBase                  = db_utils::getdao("escolabase");
$oDaoFeriado                     = db_utils::getdao("feriado");
$oDaoLogExcGeral                 = db_utils::getdao("logexcgeral");
$oDaoLogMatricula                = db_utils::getdao("logmatricula");
$oDaoMatricula                   = db_utils::getdao("matricula");
$oDaoMatriculaMov                = db_utils::getdao("matriculamov");
$oDaoParecerAval                 = db_utils::getdao("pareceraval");
$oDaoParecerResult               = db_utils::getdao("parecerresult");
$oDaoParecerTurma                = db_utils::getdao("parecerturma");
$oDaoPeriodoCalendario           = db_utils::getdao("periodocalendario");
$oDaoProcAvaliacao               = db_utils::getdao("procavaliacao");
$oDaoProcDiscFreqIndiv           = db_utils::getdao("procdiscfreqindiv");
$oDaoProcedimento                = db_utils::getdao("procedimento");
$oDaoProcEscola                  = db_utils::getdao("procescola");
$oDaoProcRecomendacao            = db_utils::getdao("procrecomendacao");
$oDaoProcresultado               = db_utils::getdao("procresultado");
$oDaoRegencia                    = db_utils::getdao("regencia");
$oDaoRegenciaHorario             = db_utils::getdao("regenciahorario");
$oDaoRegenciaPeriodo             = db_utils::getdao("regenciaperiodo");
$oDaoRegenteConselho             = db_utils::getdao("regenteconselho");
$oDaoResCompoEres                = db_utils::getdao("rescompoeres");
$oDaoSerie                       = db_utils::getdao("serie");
$oDaoTransfEscolaRede            = db_utils::getdao("transfescolarede");
$oDaoTransfEscolaFora            = db_utils::getdao("transfescolafora");
$oDaoTrocaSerie                  = db_utils::getdao("trocaserie");
$oDaoTurma                       = db_utils::getdao("turma");
$oDaoTurmaTurno                  = db_utils::getdao("turmaturno");
$oDaoTurmaSerieRegimeMat         = db_utils::getdao("turmaserieregimemat");
$oDaoMatriculaSerie              = db_utils::getdao("matriculaserie");
$oDaoEduNumAlunoBloqueado        = db_utils::getdao("edu_numalunobloqueado");
$oDaoTurmaLog                    = db_utils::getdao("turmalog");
$oDaoTurmaAc                     = db_utils::getdao("turmaac");
$oDaoTurmaAcMatricula            = db_utils::getdao("turmaacmatricula");
$oDaoLogexcGeral                 = db_utils::getdao("logexcgeral");
$oDaoTurmaLogAc                  = db_utils::getdao("turmalogac");
$oDaoTurmaAcAtiv                 = db_utils::getdao("turmaacativ");
$oDaoTurmaAcHorario              = db_utils::getdao("turmaachorario");
$oDaoDiarioClasseRegenciaHorario = db_utils::getDao("diarioclasseregenciahorario");
$oDaoDiarioClasseAlunoFalta      = db_utils::getDao("diarioclassealunofalta");
$oDaoDiarioClasse                = db_utils::getDao("diarioclasse");
$db_opcao                 = 1;
$db_botao                 = true;

if (isset($excluir)) {

  try {

    db_inicio_transacao();

    if (isset($turma)) {

      $lErroTurma = true;
      $codturma   = explode(",", $turma);

      for ($x = 0; $x < count($codturma); $x++) {

        $sOrder            = "ed60_i_aluno,ed60_c_ativa desc,ed60_i_codigo desc";
        $sWhere            = " ed60_i_turma = $codturma[$x]";
        $sSqlMatricula     = $oDaoMatricula->sql_query("", "ed60_i_codigo,ed60_i_aluno", $sOrder, $sWhere);
        $rsResultMatricula = $oDaoMatricula->sql_record($sSqlMatricula);
        $iLinhasMatricula  = $oDaoMatricula->numrows;

        for ($y = 0; $y < $iLinhasMatricula; $y++) {

          db_fieldsmemory($rsResultMatricula, $y);
          $sCamposDiarioAval   = "DISTINCT ed95_i_codigo as coddiario";
          $sWhereDiarioAval    = " ed95_i_aluno = $ed60_i_aluno ";
          $sWhereDiarioAval   .= " AND ed95_i_regencia in (select ed59_i_codigo from regencia ";
          $sWhereDiarioAval   .= " where ed59_i_turma = $codturma[$x])";
          $sSqlDiarioAvaliacao = $oDaoDiarioAvaliacao->sql_query_apagargeral("", $sCamposDiarioAval,
                                                                             "", $sWhereDiarioAval
                                                                            );
          $rsResultDiarioAvaliacao = $oDaoDiarioAvaliacao->sql_record($sSqlDiarioAvaliacao);
          $iLinhasDiarioAvaliacao  = $oDaoDiarioAvaliacao->numrows;

          for ($z = 0; $z < $iLinhasDiarioAvaliacao; $z++) {

            db_fieldsmemory($rsResultDiarioAvaliacao,$z);
            $oDaoAmparo->excluir("", "ed81_i_diario = $coddiario");

            if ($oDaoAmparo->erro_status == "0") {

              throw new Exception(" Erro da classe: ".$oDaoAmparo->erro_msg);
              $lErroTurma = false;

            }

            $oDaoDiarioFinal->excluir("", "ed74_i_diario = $coddiario");

            if ($oDaoDiarioFinal->erro_status == "0") {

              throw new Exception(" Erro da classe: ".$oDaoDiarioFinal->erro_msg);
              $lErroTurma = false;

            }

            $oDaoParecerResult->excluir("", " ed63_i_diarioresultado in (select ed73_i_codigo ".
                                        "from diarioresultado where ed73_i_diario = $coddiario)"
                                       );

            if ($oDaoParecerResult->erro_status == "0") {

              throw new Exception(" Erro da classe: ".$oDaoParecerResult->erro_msg);
              $lErroTurma = false;

            }

            $oDaoDiarioResultado->excluir("", " ed73_i_diario = $coddiario");

            if ($oDaoDiarioResultado->erro_status == "0") {

              throw new Exception(" Erro da classe: ".$oDaoDiarioResultado->erro_msg);
              $lErroTurma = false;

            }

            $oDaoParecerAval->excluir("", " ed93_i_diarioavaliacao in (select ed72_i_codigo from diarioavaliacao ".
                                      "where ed72_i_diario = $coddiario)"
                                     );

            if ($oDaoParecerAval->erro_status == "0") {

              throw new Exception(" Erro da classe: ".$oDaoParecerAval->erro_msg);
              $lErroTurma = false;

            }

            $oDaoAbonoFalta->excluir("", " ed80_i_diarioavaliacao in (select ed72_i_codigo from ".
                                     " diarioavaliacao where ed72_i_diario = $coddiario)"
                                    );

            if ($oDaoAbonoFalta->erro_status == "0") {

              throw new Exception(" Erro da classe: ".$oDaoAbonoFalta->erro_msg);
              $lErroTurma = false;

            }

            $oDaoDiarioAvaliacao->excluir("", " ed72_i_diario = $coddiario");

            if ($oDaoDiarioAvaliacao->erro_status == "0") {

              throw new Exception(" Erro da classe: ".$oDaoDiarioAvaliacao->erro_msg);
              $lErroTurma = false;

            }

            $oDaoAprovConselho->excluir("", " ed253_i_diario = $coddiario");

            if ($oDaoAprovConselho->erro_status == "0") {

              throw new Exception(" Erro da classe: ".$oDaoAprovConselho->erro_msg);
              $lErroTurma = false;

            }

            $oDaoDiario->excluir("", " ed95_i_codigo = $coddiario");

            if ($oDaoDiario->erro_status == "0") {

              throw new Exception(" Erro da classe: ".$oDaoDiario->erro_msg);
              $lErroTurma = false;

            }

          }//fecha o for $iLinhasDiarioAvaliacao

          $oDaoMatriculaMov->excluir("", " ed229_i_matricula = $ed60_i_codigo ");
          if ($oDaoMatriculaMov->erro_status == "0") {

            throw new Exception(" Erro da classe: ".$oDaoMatriculaMov->erro_msg);
            $lErroTurma = false;

          }

          $oDaoAlunoTransfTurma->excluir("", "ed69_i_matricula  = $ed60_i_codigo ");
          if ($oDaoAlunoTransfTurma->erro_status == "0") {

            throw new Exception(" Erro da classe: ".$oDaoAlunoTransfTurma->erro_msg);
            $lErroTurma = false;

          }

          $oDaoTransfEscolaRede->excluir("","ed103_i_matricula  = $ed60_i_codigo ");
          if ($oDaoTransfEscolaRede->erro_status == "0") {

            throw new Exception(" Erro da classe: ".$oDaoTransfEscolaRede->erro_msg);
            $lErroTurma = false;

          }

          $oDaoTransfEscolaFora->excluir("","ed104_i_matricula  = $ed60_i_codigo ");
          if ($oDaoTransfEscolaFora->erro_status == "0") {

            throw new Exception(" Erro da classe: ".$oDaoTransfEscolaFora->erro_msg);
            $lErroTurma = false;

          }

          $oDaoMatriculaSerie->excluir("","ed221_i_matricula  = $ed60_i_codigo ");
          if ($oDaoMatriculaSerie->erro_status == "0") {

            throw new Exception(" Erro da classe: ".$oDaoMatriculaSerie->erro_msg);
            $lErroTurma = false;

          }

          $oDaoMatricula->excluir($ed60_i_codigo);
          if ($oDaoMatricula->erro_status == "0") {

            throw new Exception(" Erro da classe: ".$oDaoMatricula->erro_msg);
            $lErroTurma = false;

          }
          $sWhereAlunoCurso   = "ed56_i_aluno = $ed60_i_aluno";
          $sSqlAlunoCurso     = $oDaoAlunoCurso->sql_query("", "ed56_i_codigo", "", $sWhereAlunoCurso);
          $rsResultAlunoCurso = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);
          $iLinhasAlunoCurso  = $oDaoAlunoCurso->numrows;

          if ($iLinhasAlunoCurso > 0) {

            db_fieldsmemory($rsResultAlunoCurso,0);
            $sCamposMat  = "ed60_i_codigo as matrant,ed57_i_escola as escolaant,ed57_i_base as baseant,";
            $sCamposMat .= "ed57_i_calendario as calant,ed60_c_situacao as sitant,ed60_c_concluida as concant,";
            $sCamposMat .= "ed60_c_ativa as ativaant,ed221_i_serie as serieant,ed57_i_turno as turnoant,";
            $sCamposMat .= "ed11_i_sequencia as seqant,ed11_i_ensino as ensinoant,ed60_i_turma as turmaant";
            $sOrderMat   = "ed60_d_datamatricula desc LIMIT 1";
            $sWhereMat   = "ed60_i_aluno = $ed60_i_aluno AND ed229_d_data is not null AND ed221_c_origem = 'S'";
            $sSqlMat     = $oDaoMatricula->sql_query_apagargeral("", $sCamposMat, $sOrderMat, $sWhereMat);
            $rsResultMat = $oDaoMatricula->sql_record($sSqlMat);
            $iLinhasMat  = $oDaoMatricula->numrows;

            if ($iLinhasMat > 0) {

              db_fieldsmemory($rsResultMat,0);

              if (trim($sitant) != "AVAN�ADO" && trim($sitant) != "CLASSIFICADO") {

                if ($concant == "S") {

                  if (trim($sitant) == "MATRICULADO") {

                    $resfinal = ResultadoFinal($matrant,$ed60_i_aluno,$turmaant,$sitant,$concant);
                    $sitant   = $resfinal=="REPROVADO"?"REPETENTE":"APROVADO";

                  } elseif (trim($sitant) == "TROCA DE MODALIDADE") {

                    $sUpdateMatricula  = " UPDATE matricula SET  ";
                    $sUpdateMatricula .= "                  ed60_c_ativa        = 'S', ";
                    $sUpdateMatricula .= "                  ed60_c_concluida    = 'N',";
                    $sUpdateMatricula .= "                  ed60_c_situacao     = 'MATRICULADO', ";
                    $sUpdateMatricula .= "                  ed60_d_datasaida    = null, ";
                    $sUpdateMatricula .= "                  ed60_d_datamodifant = null";
                    $sUpdateMatricula .= "            WHERE ed60_i_codigo       = $matrant";
                    $rsResultMatricula = db_query($sUpdateMatricula);

                    $sUpdateDiario  = " UPDATE diario SET ";
                    $sUpdateDiario .= "               ed95_c_encerrado = 'N' ";
                    $sUpdateDiario .= "         WHERE ed95_i_regencia in ";
                    $sUpdateDiario .= "                               (select ed59_i_codigo from regencia ";
                    $sUpdateDiario .= "                                       where ed59_i_turma = $turmaant)";
                    $sUpdateDiario .= "               AND ed95_i_aluno = $ed60_i_aluno ";
                    $rsResultDiario = db_query($sUpdateDiario);

                    $oDaoAlunoTransfTurma->excluir("","ed69_i_matricula  = $matrant AND ed69_i_turmaorigem = $turmaant");
                    if ($oDaoAlunoTransfTurma->erro_status == "0") {

                      throw new Exception(" Erro da classe: ".$oDaoAlunoTransfTurma->erro_msg);
                      $lErroTurma = false;

                    }

                    $sitant = "MATRICULADO";
                  }//fecha o elseif trim($sitant) == "TROCA DE MODALIDADE"

                }//fecha o if $concant == "S"

                if (trim($sitant) == "TRANSFERIDO REDE") {
                  $escolaant = $escola;
                }

                if (trim($ativaant) == "N") {

                  $sUpdateMat        = " UPDATE matricula SET ";
                  $sUpdateMat       .= "                  ed60_c_ativa  = 'S' ";
                  $sUpdateMat       .= "            WHERE ed60_i_codigo = $matrant";
                  $rsResultUpdateMat = db_query($sUpdateMat);

                }
                $sUpdateAlunoCurso  = " UPDATE alunocurso SET ";
                $sUpdateAlunoCurso .= "                   ed56_i_escola        = $escolaant, ";
                $sUpdateAlunoCurso .= "                   ed56_i_base          = $baseant, ";
                $sUpdateAlunoCurso .= "                   ed56_i_calendario    = $calant, ";
                $sUpdateAlunoCurso .= "                   ed56_c_situacao      = '$sitant', ";
                $sUpdateAlunoCurso .= "                   ed56_i_baseant       = null, ";
                $sUpdateAlunoCurso .= "                   ed56_i_calendarioant = null, ";
                $sUpdateAlunoCurso .= "                   ed56_c_situacaoant   = '' ";
                $sUpdateAlunoCurso .= "             WHERE ed56_i_codigo        = $ed56_i_codigo";
                $rsResultAlunoCurso = db_query($sUpdateAlunoCurso);

                if (trim($sitant) == "APROVADO") {

                  $sWhereTurmaSerieRegimeMat   = " ed220_i_turma = $turmaant";
                  $sSqlTurmaSerieRegimeMat     = $oDaoTurmaSerieRegimeMat->sql_query("",
                                                                                     "max(ed11_i_sequencia) as seqant",
                                                                                     "", $sWhereTurmaSerieRegimeMat
                                                                                    );
                  $rsResultTurmaSerieRegimeMat = $oDaoTurmaSerieRegimeMat->sql_record($sSqlTurmaSerieRegimeMat);
                  $iLinhasTurmaSerieRegimeMat  = $oDaoTurmaSerieRegimeMat->numrows;

                  if ($iLinhasTurmaSerieRegimeMat > 0) {
                    $proxseq = ($seqant+1);
                  }

                  $sCamposBaseSerie  = "si.ed11_i_sequencia as inicial,sf.ed11_i_sequencia as final";
                  $sSqlBaseSerie     = $oDaoBaseSerie->sql_query("", $sCamposBaseSerie, "", " ed87_i_codigo = $baseant");
                  $rsResultBaseSerie = $oDaoBaseSerie->sql_record($sSqlBaseSerie);
                  $iLinhasBaseSerie  = $oDaoBaseSerie->numrows;

                  if ($iLinhasBaseSerie > 0) {
                    db_fieldsmemory($rsResultBaseSerie,0);
                  }

                  if ($proxseq >= $inicial && $proxseq <= $final) {

                  	$sWhereSerie   = " ed11_i_ensino = $ensinoant AND ed11_i_sequencia = $proxseq";
                  	$sSqlSerie     = $oDaoSerie->sql_query("", "ed11_i_codigo as serieant", "", $sWhereSerie);
                    $rsResultSerie = $oDaoSerie->sql_record($sSqlSerie);
                    $iLinhasSerie  = $oDaoSerie->numrows;

                    if ($iLinhasSerie > 0) {
                      db_fieldsmemory($rsResultSerie,0);
                    }

                  } else {

                  	$sCamposEscolaBase  = "ed77_i_basecont as basecont,cursoeducont.ed29_i_codigo as cursocont";
                  	$sWhereEscolaBase   = " ed77_i_base = $baseant AND ed77_i_escola = $escolaant";
                  	$sSqlEscolaBase     = $oDaoEscolaBase->sql_query("", $sCamposEscolaBase, "", $sWhereEscolaBase);
                    $rsResultEscolaBase = $oDaoEscolaBase->sql_record($sSqlEscolaBase);
                    $iLinhasEscolaBase  = $oDaoEscolaBase->numrows;

                    if ($iLinhasEscolaBase > 0) {
                      db_fieldsmemory($rsResultEscolaBase,0);
                    }

                    if ($basecont != "") {

                      $sCampos           = " si.ed11_i_sequencia as inicial,sf.ed11_i_sequencia as final, ";
                      $sCampos          .= " si.ed11_i_ensino as ensino";
                      $sSqlBaseSerie     = $oDaoBaseSerie->sql_query("", $sCampos, "", " ed87_i_codigo = $basecont");
                      $rsResultBaseSerie = $oDaoBaseSerie->sql_record($sSqlBaseSerie);
                      $iLinhas           = $oDaoBaseSerie->numrows;

                      if ($iLinhas) {
                        db_fieldsmemory($rsResultBaseSerie,0);
                      }

                      $sWhere        = " ed11_i_ensino = $ensino AND ed11_i_sequencia = $inicial";
                      $sSqlSerie     = $oDaoSerie->sql_query("", "ed11_i_codigo as serieant", "", $sWhere);
                      $rsResultSerie = $oDaoSerie->sql_record($sSqlSerie);
                      $iLinhasSerie  = $oDaoSerie->numrows;

                      if ($iLinhasSerie > 0) {
                        db_fieldsmemory($rsResultSerie,0);
                      }

                    } else {
                      $serieant = $serieant;
                    }//fecha o else

                  }//fecha

                }
                $sUpdateAlunoPossib  = " UPDATE alunopossib SET ";
                $sUpdateAlunoPossib .= "                    ed79_i_serie      = $serieant, ";
                $sUpdateAlunoPossib .= "                    ed79_i_turno      = $turnoant,";
                $sUpdateAlunoPossib .= "                    ed79_i_turmaant   = null, ";
                $sUpdateAlunoPossib .= "                    ed79_c_resulant   = '', ";
                $sUpdateAlunoPossib .= "                    ed79_c_situacao   = 'A' ";
                $sUpdateAlunoPossib .= "              WHERE ed79_i_alunocurso = $ed56_i_codigo";
                $rsResultAlunoPossib = db_query($sUpdateAlunoPossib);

                if (trim($sitant) == "TRANSFERIDO REDE") {

                  $sUpdateTransfRede  = " UPDATE transfescolarede SET ";
                  $sUpdateTransfRede .= "                         ed103_c_situacao = 'A' ";
                  $sUpdateTransfRede .= "                   WHERE ed103_i_codigo = (select ed103_i_codigo from ";
                  $sUpdateTransfRede .= "                                                  transfescolarede where ";
                  $sUpdateTransfRede .= "                                                ed103_i_matricula = $matrant)";
                  $rsResultTransfRede = db_query($sUpdateTransfRede);

                }//fecha o if trim($sitant) == "TRANSFERIDO REDE"

              } else {

                $sUpAlunoCurso      = " UPDATE alunocurso SET ";
                $sUpAlunoCurso     .= "                   ed56_c_situacao = 'CANDIDATO' ";
                $sUpAlunoCurso     .= "             WHERE ed56_i_codigo   = $ed56_i_codigo";
                $rsResultAlunoCurso = db_query($sUpAlunoCurso);

              }//fecha o else

            } else { //else que fecha o if $iLinhasMat > 0

              $sUpdateAlCurso = " UPDATE alunocurso SET ";
              $sUpdateAlCurso .= "                  ed56_c_situacao    = 'CANDIDATO', ";
              $sUpdateAlCurso .= "                  ed56_c_situacaoant = '' ";
              $sUpdateAlCurso .= "            WHERE ed56_i_codigo      = $ed56_i_codigo";
              $rsResultAlCurso = db_query($sUpdateAlCurso);

           }//fecha o else

          }//fecha o if $iLinhasAlunoCurso > 0

        }//fecha for matricula

        $oDaoEduNumAlunoBloqueado->excluir("","ed289_i_turma = $codturma[$x]");
        if ($oDaoEduNumAlunoBloqueado->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoEduNumAlunoBloqueado->erro_msg);
          $lErroTurma = false;

        }
      /**
       * select na diarioclasseregenciahorario pelo codigo das regencias
       *  - excluir diarioclassealunofalta
       *  - excluir diarioclasseregenciahorario
       *  - diarioclasse
       */
      $sWhereDiarioClasse  = "ed59_i_turma = {$codturma[$x]}";
      $sSqlDiarioClasse    = $oDaoDiarioClasseRegenciaHorario->sql_query(null,
                                                                           "*",
                                                                           null,
                                                                           $sWhereDiarioClasse
                                                                          );

      $rsDiarioClasse = $oDaoDiarioClasseRegenciaHorario->sql_record($sSqlDiarioClasse);
      $iTotalLinhasDiario = $oDaoDiarioClasseRegenciaHorario->numrows;
      if ($iTotalLinhasDiario > 0) {

        $aDiarioClasseExcluidos = array();
        for ($iDiario = 0; $iDiario < $iTotalLinhasDiario; $iDiario++) {

          $oDadosDiarioClasse       = db_utils::fieldsMemory($rsDiarioClasse, $iDiario);
          $aDiarioClasseExcluidos[] = $oDadosDiarioClasse->ed302_diarioclasse;
          /**
           * Excluir diarioalunofalta
           *
           */
          $sWhereExcluirDiarioClasseAlunoFalta = "ed301_diarioclasseregenciahorario = {$oDadosDiarioClasse->ed302_sequencial}";
          $oDaoDiarioClasseAlunoFalta->excluir(null, $sWhereExcluirDiarioClasseAlunoFalta );
          if ($oDaoDiarioClasseAlunoFalta->erro_status == 0) {

            $sMensagemErro = "Erro ao excluir faltas do aluno.\\nErro t�cnico : {$oDaoDiarioClasseAlunoFalta->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }
          /**
           * Excluir da diarioclasseregenciahorario
           */
          $oDaoDiarioClasseRegenciaHorario->excluir($oDadosDiarioClasse->ed302_sequencial);
          if ($oDaoDiarioClasseRegenciaHorario->erro_status == 0) {

            $sMensagemErro  = "Erro ao excluir periodos de aula do aluno. \\n";
            $sMensagemErro .= "Erro t�cnico : {$oDaoDiarioClasseRegenciaHorario->erro_msg}";
            throw new BusinessException($sMensagemErro);
          }

          unset($oDadosDiarioClasse);
        }
        $sDiarioClasseExcluir = implode(",", $aDiarioClasseExcluidos);
        $oDaoDiarioClasse->excluir(null, "ed300_sequencial in ({$sDiarioClasseExcluir})");
        if ($oDaoDiarioClasse->erro_status == 0) {

          $sMensagemErro  = "Erro ao excluir dados do diario de classe do professor.\\n";
          $sMensagemErro .= "Erro t�cnico : {$oDaoDiarioClasse->erro_msg}";
          throw new BusinessException($sMensagemErro);
        }

      }
        $oDaoRegenciaHorario->excluir(""," ed58_i_regencia in (select ed59_i_codigo from ".
                                      "regencia where ed59_i_turma = $codturma[$x])"
                                     );
        if ($oDaoRegenciaHorario->erro_status == "0") {

          throw new Exception(" Erro da classeooo: ".$oDaoRegenciaHorario->erro_msg);
          $lErroTurma = false;

        }

        $oDaoRegenciaPeriodo->excluir(""," ed78_i_regencia  in (select ed59_i_codigo from ".
                                      "regencia where ed59_i_turma = $codturma[$x])"
                                     );
        if ($oDaoRegenciaPeriodo->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoRegenciaPeriodo->erro_msg);
          $lErroTurma = false;

        }

        $oDaoRegencia->excluir(""," ed59_i_turma = $codturma[$x]");
        if ($oDaoRegencia->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoRegencia->erro_msg);
          $lErroTurma = false;

        }

        $sUpdatePossib  = " UPDATE alunopossib SET ";
        $sUpdatePossib .= "                    ed79_i_turmaant = null ";
        $sUpdatePossib .= "              WHERE ed79_i_turmaant = $codturma[$x] ";
        $rsResultPossib = db_query($sUpdatePossib);

        $sUpMatri      = " UPDATE matricula SET";
        $sUpMatri     .= " ed60_i_turmaant = null";
        $sUpMatri     .= " WHERE ed60_i_turmaant = $codturma[$x] ";
        $rsResultMatri = db_query($sUpMatri);

        $oDaoAlunoTransfTurma->excluir("","ed69_i_turmaorigem = $codturma[$x] or ed69_i_turmadestino = $codturma[$x]");
        if ($oDaoAlunoTransfTurma->erro_status == "0") {

         throw new Exception(" Erro da classe: ".$oDaoAlunoTransfTurma->erro_msg);
         $lErroTurma = false;

        }

        $oDaoParecerTurma->excluir(""," ed105_i_turma = $codturma[$x]");
        if ($oDaoParecerTurma->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoParecerTurma->erro_msg);
          $lErroTurma = false;

        }

        $oDaoRegenteConselho->excluir("","ed235_i_turma = $codturma[$x]");
        if ($oDaoRegenteConselho->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoRegenteConselho->erro_msg);
          $lErroTurma = false;

        }

        $oDaoTrocaSerie->excluir("","ed101_i_turmaorig = $codturma[$x] or ed101_i_turmadest = $codturma[$x]");
        if ($oDaoTrocaSerie->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoTrocaSerie->erro_msg);
          $lErroTurma = false;

        }

        $oDaoTurmaTurno->excluir("","ed246_i_turma = $codturma[$x]");
        if ($oDaoTurmaTurno->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoTurmaTurno->erro_msg);
          $lErroTurma = false;

        }

        $oDaoTurmaSerieRegimeMat->excluir("","ed220_i_turma = $codturma[$x]");
        if ($oDaoTurmaSerieRegimeMat->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoTurmaSerieRegimeMat->erro_msg);
          $lErroTurma = false;

        }

        $oDaoEduNumAlunoBloqueado->excluir("","ed289_i_turma = $codturma[$x]");
        if ($oDaoEduNumAlunoBloqueado->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoEduNumAlunoBloqueado->erro_msg);
          $lErroTurma = false;

        }

        $oDaoTurmaLog->excluir("","ed287_i_turma = $codturma[$x]");
        if ($oDaoTurmaLog->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoTurmaLog->erro_msg);
          $lErroTurma = false;

        }

        $oDaoTurma->excluir($codturma[$x]);
        if ($oDaoTurma->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoTurma->erro_msg);
          $lErroTurma = false;

        }


    }//fecha o for $l = 0; $l < count($codturmaac); $l++
    if ($lErroTurma) {
      db_msgbox("Exclus�o da Turma Efetuada com sucesso!");
    }

 }//fecha isset turma

 if (isset($calendario)) {

   $lErroCalendario = true;
   $codcalendario   = explode(",", $calendario);
   for ($x = 0; $x < count($codcalendario); $x++) {

  	 $sSqlTurma     = $oDaoTurma->sql_query("", "ed52_i_codigo,ed52_c_descr", "", " ed57_i_calendario = $codcalendario[$x]");
     $rsResultTurma = $oDaoTurma->sql_record($sSqlTurma);
     $iLinhasTurma  = $oDaoTurma->numrows;

     if ($iLinhasTurma > 0) {

       db_fieldsmemory($rsResultTurma, 0);
       throw new Exception("Calend�rio ($ed52_i_codigo - $ed52_c_descr) n�o pode ser exclu�do pois".
                           " cont�m turmas vinculadas. Exclua antes as turmas vinculadas a este calend�rio."
                          );

     } else {

       $sUpdateCalendario  = " UPDATE calendario set ";
       $sUpdateCalendario .= "                   ed52_i_calendant = null ";
       $sUpdateCalendario .= "             where ed52_i_calendant = $codcalendario[$x]";
       $rsResultCalendario = db_query($sUpdateCalendario);
       $oDaoTransfEscolaRede->excluir("", " ed103_i_atestvaga in (select ed102_i_codigo from atestvaga where ".
                                      "ed102_i_calendario = $codcalendario[$x])"
                                     );
       if ($oDaoTransfEscolaRede->erro_status == "0") {

         throw new Exception(" Erro da classe: ".$oDaoTransfEscolaRede->erro_msg);
         $lErroCalendario = false;

       }

       $oDaoAtestVaga->excluir("", " ed102_i_calendario = $codcalendario[$x]");
       if ($oDaoAtestVaga->erro_status == "0") {

         throw new Exception(" Erro da classe: ".$oDaoAtestVaga->erro_msg);
         $lErroCalendario = false;

       }

       $oDaoAlunoPossib->excluir("", " ed79_i_alunocurso in (select ed56_i_codigo from alunocurso where ".
                                 "ed56_i_calendario = $codcalendario[$x])"
                                );
       if ($oDaoAlunoPossib->erro_status == "0") {

         throw new Exception(" Erro da classe: ".$oDaoAlunoPossib->erro_msg);
         $lErroCalendario = false;

       }

       $oDaoAlunoCurso->excluir("", " ed56_i_calendario = $codcalendario[$x]");
       if ($oDaoAlunoCurso->erro_status == "0") {

         throw new Exception(" Erro da classe: ".$oDaoAlunoCurso->erro_msg);
         $lErroCalendario = false;

       }

       $oDaoCalendarioEscola->excluir("", " ed38_i_calendario = $codcalendario[$x]");
       if ($oDaoCalendarioEscola->erro_status == "0") {

         throw new Exception(" Erro da classe: ".$oDaoCalendarioEscola->erro_msg);
         $lErroCalendario = false;

       }

       $oDaoFeriado->excluir("", " ed54_i_calendario = $codcalendario[$x]");
       if ($oDaoFeriado->erro_status == "0") {

         throw new Exception(" Erro da classe: ".$oDaoFeriado->erro_msg);
         $lErroCalendario = false;

       }

       $oDaoPeriodoCalendario->excluir("", " ed53_i_calendario = $codcalendario[$x]");
       if ($oDaoPeriodoCalendario->erro_status == "0") {

         throw new Exception(" Erro da classe: ".$oDaoPeriodoCalendario->erro_msg);
         $lErroCalendario = false;

       }

       $oDaoCalendario->excluir($codcalendario[$x]);
       if ($oDaoCalendario->erro_status == "0") {

         throw new Exception(" Erro da classe: ".$oDaoCalendario->erro_msg);
         $lErroCalendario = false;

       }

     }//fecha o else

   }//fecha o for $x = 0; $x < count($codcalendario); $x++

   if ($lErroCalendario) {
     db_msgbox("Exclus�o do Calend�rio Efetuada com sucesso!");
   }

  }//fecha o if isset calendario
  $lErroBase = true;
  if (isset($base)) {


    $codbase   = explode(",", $base);
    for ($x = 0; $x < count($codbase); $x++) {

      $sSqlTurma     = $oDaoTurma->sql_query("", "ed31_i_codigo,ed31_c_descr", "", " ed57_i_base = $codbase[$x]");
      $rsResultTurma = $oDaoTurma->sql_record($sSqlTurma);
      $iLinhas       = $oDaoTurma->numrows;

      if ($iLinhas > 0) {

        db_fieldsmemory($rsResultTurma, 0);
        throw new Exception("Base Curricular ($ed31_i_codigo - $ed31_c_descr) n�o pode ser exclu�da pois cont�m turmas ".
                            " vinculadas. Exclua antes as turmas vinculadas a esta base.");
        $lErroBase = false;

      } else {

        $oDaoTransfEscolaRede->excluir("", " ed103_i_atestvaga in (select ed102_i_codigo from atestvaga ".
                                       " where ed102_i_base = $codbase[$x])"
                                      );
        if ($oDaoTransfEscolaRede->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoTransfEscolaRede->erro_msg);
          $lErroBase = false;

        }

        $oDaoAtestVaga->excluir("", " ed102_i_base = $codbase[$x]");
        if ($oDaoAtestVaga->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoAtestVaga->erro_msg);
          $lErroBase = false;

        }

        $oDaoAlunoPossib->excluir("", " ed79_i_alunocurso in (select ed56_i_codigo from alunocurso ".
                                  " where ed56_i_base = $codbase[$x])"
                                 );
        if ($oDaoAlunoPossib->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoAlunoPossib->erro_msg);
          $lErroBase = false;

        }

        $oDaoBaseAtoSerie->excluir("", " ed279_i_baseato in (select ed278_i_codigo from baseato where ".
                                   " ed278_i_escolabase in (select ed77_i_codigo from escolabase ".
                                   "where ed77_i_base = $codbase[$x]))"
                                  );
        if ($oDaoBaseAtoSerie->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoBaseAtoSerie->erro_msg);
          $lErroBase = false;

        }

        $oDaoBaseAto->excluir("", " ed278_i_escolabase in (select ed77_i_codigo from escolabase where ".
                              "ed77_i_base = $codbase[$x])"
                             );
        if ($oDaoBaseAto->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoBaseAto->erro_msg);
          $lErroBase = false;

        }

        $oDaoBaseRegimeMatDiv->excluir("", " ed224_i_base = $codbase[$x]");
        if ($oDaoBaseRegimeMatDiv->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoBaseRegimeMatDiv->erro_msg);
          $lErroBase = false;

        }

        $oDaoAlunoCurso->excluir("", " ed56_i_base = $codbase[$x]");
        if ($oDaoAlunoCurso->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoAlunoCurso->erro_msg);
          $lErroBase = false;

        }

        $oDaoBaseDiscGlob->excluir("", " ed89_i_codigo = $codbase[$x]");
        if ($oDaoBaseDiscGlob->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoBaseDiscGlob->erro_msg);
          $lErroBase = false;

        }

        $oDaoBaseSerie->excluir("", " ed87_i_codigo = $codbase[$x]");
        if ($oDaoBaseSerie->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoBaseSerie->erro_msg);
          $lErroBase = false;

        }

        $oDaoBaseMpd->excluir("", " ed35_i_base = $codbase[$x]");
        if ($oDaoBaseMpd->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoBaseMpd->erro_msg);
          $lErroBase = false;

        }

        $oDaoBaseMps->excluir("", " ed34_i_base = $codbase[$x]");
        if ($oDaoBaseMps->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoBaseMps->erro_msg);
          $lErroBase = false;

        }

        $oDaoEscolaBase->excluir("", " ed77_i_base = $codbase[$x]");
        if ($oDaoEscolaBase->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoEscolaBase->erro_msg);
          $lErroBase = false;

        }

        $sUpdateEscolaBase  = " UPDATE escolabase SET ";
        $sUpdateEscolaBase .= "                   ed77_i_basecont = null ";
        $sUpdateEscolaBase .= "             WHERE ed77_i_basecont = $codbase[$x]";
        $rsResultEscolaBase = db_query($sUpdateEscolaBase);

        $oDaoBase->excluir($codbase[$x]);
        if ($oDaoBase->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoBase->erro_msg);
          $lErroBase = false;

        }

      }//fecha o else

    }//fecha o for $x = 0; $x < count($codbase); $x++

    if ($lErroBase) {
      db_msgbox("Exclus�o da Base Curricular Efetuada com sucesso!");
    }

  }//fecha o if isset base

  if (isset($procedimento)) {

  	$lErroProc = true;
    $codprocedimento = explode(",",$procedimento);

    for ($x = 0; $x < count($codprocedimento); $x++) {

      $sWhere        = " ed220_i_procedimento = $codprocedimento[$x]";
      $sSqlTurma     = $oDaoTurma->sql_query_turmaserie("", "ed40_i_codigo,ed40_c_descr", "", $sWhere);
      $rsResultTurma = $oDaoTurma->sql_record($sSqlTurma);
      $iLinhasTurma  = $oDaoTurma->numrows;

      if ($iLinhasTurma > 0) {

        db_fieldsmemory($rsResultTurma,0);
        throw new Exception("Procedimento de Avalia��o ($ed40_i_codigo - $ed40_c_descr) n�o pode ser exclu�do pois".
                            " cont�m turmas vinculadas. Exclua antes as turmas vinculadas a este procedimento."
                           );

      } else {

        $oDaoAvalCompoeres->excluir("", " ed44_i_procavaliacao in (select ed41_i_codigo from procavaliacao where ".
                                    " ed41_i_procedimento = $codprocedimento[$x])"
                                   );
        if ($oDaoAvalCompoeres->erro_status == "0") {

          throw new Exception(" Erro da classe Aval. Compoeres: ".$oDaoProcedimento->erro_msg);
          $lErroProc = true;

        }

        $oDaoAvalFreqRes->excluir("", " ed67_i_procavaliacao in (select ed41_i_codigo from procavaliacao where ".
                                  " ed41_i_procedimento = $codprocedimento[$x])"
                                 );
        if ($oDaoAvalFreqRes->erro_status == "0") {

          throw new Exception(" Erro da Classe Aval. Freq. Res.: ".$oDaoAvalFreqRes->erro_msg);
          $lErroProc = true;

        }

        $oDaoAvalCompoeres->excluir("", " ed44_i_procresultado in (select ed43_i_codigo from procresultado where ".
                                    " ed43_i_procedimento = $codprocedimento[$x])"
                                   );
        if ($oDaoAvalCompoeres->erro_status == "0") {

          throw new Exception(" Erro da classe Aval. Compoeres: ".$oDaoAvalCompoeres->erro_msg);
          $lErroProc = true;

        }

        $oDaoAvalFreqRes->excluir("", " ed67_i_procresultado in (select ed43_i_codigo from procresultado where ".
                                  " ed43_i_procedimento = $codprocedimento[$x])"
                                 );
        if ($oDaoAvalFreqRes->erro_status == "0") {

          throw new Exception(" Erro da classe Aval. Freq. Res.: ".$oDaoAvalFreqRes->erro_msg);
          $lErroProc = true;

        }

        $oDaoResCompoEres->excluir("", " ed68_i_procresultado in (select ed43_i_codigo from procresultado where ".
                                    " ed43_i_procedimento = $codprocedimento[$x])"
                                   );
        if ($oDaoResCompoEres->erro_status == "0") {

          throw new Exception(" Erro da classe Res. Compoeres: ".$oDaoResCompoEres->erro_msg);
          $lErroProc = true;

        }

        $oDaoProcAvaliacao->excluir("", " ed41_i_procedimento = $codprocedimento[$x]");
        if ($oDaoProcAvaliacao->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoProcAvaliacao->erro_msg);
          $lErroProc = true;

        }

        $oDaoProcDiscFreqIndiv->excluir("", " ed45_i_procedimento = $codprocedimento[$x]");
        if ($oDaoProcDiscFreqIndiv->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoProcDiscFreqIndiv->erro_msg);
          $lErroProc = false;

        }

        $oDaoProcEscola->excluir("", " ed86_i_procedimento = $codprocedimento[$x]");
        if ($oDaoProcEscola->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoProcEscola->erro_msg);
          $lErroProc = false;
        }

        $oDaoProcRecomendacao->excluir("", " ed51_i_procedimento = $codprocedimento[$x]");
        if ($oDaoProcRecomendacao->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoProcRecomendacao->erro_msg);
          $lErroProc = true;

        }

        $oDaoProcresultado->excluir("", " ed43_i_procedimento = $codprocedimento[$x]");
        if ($oDaoProcresultado->erro_status == "0") {

          throw new Exception(" Erro da classe: ".$oDaoProcresultado->erro_msg);
          $lErroProc = true;

        }

        $oDaoProcedimento->excluir($codprocedimento[$x]);
        if ($oDaoProcedimento->erro_status == "0") {

          throw new Exception(" Erro da classe Procedimentos: ".$oDaoProcedimento->erro_msg);
          $lErroProc = true;

        }

      }//fecha o else

    }//fecha o for $x = 0; $x < count($codprocedimento); $x++
    if ($lErroProc) {
      db_msgbox("Exclus�o do Procedimento Efetuada com sucesso!");
    }

  }//fecha o if isset procedimento

  if (isset($turma)) {

    $evento    = "EXCLUS�O GERAL DE TURMAS";
    $descricao = "C�digo(s) exclu�dos(s): $turma";

  }

  if (isset($base)) {

    $evento    = "EXCLUS�O GERAL DE BASES CURRICULARES";
    $descricao = "C�digo(s) exclu�dos(s): $base";

  }

  if (isset($calendario)) {

    $evento    = "EXCLUS�O GERAL DE CALEND�RIOS";
    $descricao = "C�digo(s) exclu�dos(s): $calendario";

  }

  if (isset($procedimento)) {

    $evento    = "EXCLUS�O GERAL DE PROCEDIMENTOS DE AVALIA��O";
    $descricao = "C�digo(s) exclu�dos(s): $procedimento";

  }

  $oDaoLogExcGeral->ed256_i_usuario = db_getsession("DB_id_usuario");
  $oDaoLogExcGeral->ed256_i_escola  = $escola;
  $oDaoLogExcGeral->ed256_d_data    = date("Y-m-d");
  $oDaoLogExcGeral->ed256_c_hora    = date("H:i");
  $oDaoLogExcGeral->ed256_c_evento  = $evento;
  $oDaoLogExcGeral->ed256_t_descr   = $descricao;
  $oDaoLogExcGeral->incluir(null);

  if ($oDaoLogExcGeral->erro_status == "0") {
    throw new Exception(" Erro da classe Procedimentos: ".$oDaoLogExcGeral->erro_msg);
  }

  db_fim_transacao();
  db_redireciona("edu4_apagargeral001.php?escola=$escola");
  exit;

 } catch (Exception $oE) {

   db_fim_transacao(true);
   db_msgbox($oE->getMessage());

 }//fecha o catch

}//fecha o excluir
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
   <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
   <meta http-equiv="Expires" CONTENT="0">
   <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
   <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
   <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
   </tr>
  </table>
  <form name="form1" method="POST">
   <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
     <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
      <br>
      <fieldset style="width:95%"><legend><b>Exclus�o Geral (Turmas,  Bases, Procedimento e Calend�rios)</b></legend>
       <table border="0" cellspacing="0" width="100%">
        <tr>
         <td>
          <b>Escola:</b>
          <?
            $sSqlEscola     = $oDaoEscola->sql_query("", "ed18_i_codigo,ed18_c_nome"," ed18_c_nome", "");
            $rsResultEscola = $oDaoEscola->sql_record($sSqlEscola);
            $iLinhasEscola  = $oDaoEscola->numrows;

            if ($iLinhasEscola == 0) {

              $x = array(''=>'NENHUM REGISTRO');
              db_select('escola', $x, true, 1, "style='width:300px;'");

            } else {

              echo "<select name='escola' id='escola' onchange=\"js_escola(this.value);\"
                            style='width:400px;font-size:9px;'>";
              echo "<option value=''></option>";

              for ($x = 0; $x < $oDaoEscola->numrows; $x++) {

                db_fieldsmemory($rsResultEscola,$x);
              ?>
                <option value="<?=$ed18_i_codigo?>" <?=@$escola==$ed18_i_codigo?"selected":""?>>
                        <?=$ed18_i_codigo?> - <?=$ed18_c_nome?>
              <?
                echo "</option>";
              }

              echo "</select>";
            }

            echo "</td>";
            echo "</tr>";

            if (isset($escola)) {

              echo "<tr>";
              echo "<td height='20'>";
              echo "<input type='radio' name='escolha' value='T' onclick=\"js_escolha('id_turma');\"> Turmas";
              echo "<input type='radio' name='escolha' value='B' onclick=\"js_escolha('id_base');\"> Bases Curriculares";
              echo "<input type='radio' name='escolha' value='P'
                           onclick=\"js_escolha('id_procedimento');\"> Procedimentos de Avalia��o";
              echo "<input type='radio' name='escolha' value='C' onclick=\"js_escolha('id_calendario');\"> Calend�rios";
              echo "<hr align='left' width='55%'>";
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td valign='top' height='350'>";
              echo "&nbsp;";
              echo "<span id='id_calendario' style='position:absolute;visibility:hidden;'> ";
              echo "<b>Calend�rios:</b><br>";
              $sCampos            = "ed52_i_codigo,ed52_c_descr";
              $sWhere             = " ed38_i_escola = $escola";
              $sSqlCalendario     = $oDaoCalendario->sql_query_calescola("", $sCampos, "ed52_i_ano desc", $sWhere);
              $rsResultCalendario = $oDaoCalendario->sql_record($sSqlCalendario);

              if ($oDaoCalendario->numrows == 0) {

                $x = array(''=>'NENHUM REGISTRO');
                db_select('calendario', $x, true, 1, "style='width:500px;font-size:9px;'");

              } else {

                echo "<select name='calendario' id='calendario' style='width:500px;font-size:9px;' multiple size='20'>";
                for ($iCont = 0; $iCont < $oDaoCalendario->numrows; $iCont++) {

                  db_fieldsmemory($rsResultCalendario, $iCont);
                  echo "<option value='$ed52_i_codigo'>($ed52_i_codigo) $ed52_c_descr</option>";

               }
               echo "</select>";
              }

              echo"   </span>";
              echo "<span id='id_base' style='position:absolute;visibility:hidden;'> ";
              echo "<b>Bases Curriculares:</b><br>";
              $sCamposBase  = " ed31_i_codigo,ed31_c_descr,ed10_c_abrev ";
              $sSqlBase     = $oDaoBase->sql_query_base("", $sCamposBase , " ed31_c_descr", " ed77_i_escola = $escola");
              $rsResultBase = $oDaoBase->sql_record($sSqlBase);

              if ($oDaoBase->numrows == 0) {

                $x = array(''=>'NENHUM REGISTRO');
                db_select('base', $x, true, 1, "style='width:500px;font-size:9px;'");

              } else {

                echo" <select name='base' id='base' style='width:500px;font-size:9px;' multiple size='20'>";
                for ($iCont = 0; $iCont < $oDaoBase->numrows; $iCont++) {

                  db_fieldsmemory($rsResultBase, $iCont);
                  echo " <option value='".$ed31_i_codigo."'>(".$ed31_i_codigo.")". $ed31_c_descr."-".$ed10_c_abrev."</option>";

                }
                echo "</select>";

              }
              echo "   </span>";
              echo "<span id='id_procedimento' style='position:absolute;visibility:hidden;'>";
              echo "<b>Procedimentos de Avalia��o:</b><br>";

              $sCamposProcedimento  = "ed40_i_codigo,ed40_c_descr";
              $sWhere               = " ed86_i_escola = $escola";
              $sSqlProcedimento     = $oDaoProcedimento->sql_query("", $sCamposProcedimento , "ed40_c_descr", $sWhere);
              $rsResultProcedimento = $oDaoProcedimento->sql_record($sSqlProcedimento);

              if ($oDaoProcedimento->numrows == 0) {

                $x = array(''=>'NENHUM REGISTRO');
                db_select('procedimento', $x, true, 1, "style='width:500px;font-size:9px;'");

              } else {

                echo "<select name='procedimento' id='procedimento'
                              style='font-size:9px;width:500px;' multiple size='20'>";
                for ($iCont = 0; $iCont < $oDaoProcedimento->numrows; $iCont++) {

                  db_fieldsmemory($rsResultProcedimento, $iCont);
                  echo "<option value='".$ed40_i_codigo."'>(".$ed40_i_codigo.")".$ed40_c_descr."</option>";

                }
               echo "</select>";
              }

              echo "  </span>";
              echo " <span id='id_turma' style='position:absolute;visibility:hidden;'>";
              echo "<b>Turmas:</b><br>";

              if (isset($ordenacao)) {
                $ordem = $ordenacao;
              } else {

                $ordem     = "ed52_i_ano desc";
                $ordenacao = "ed52_i_ano desc";

              }
              $sCamposTurma  = "DISTINCT ed57_i_codigo,ed57_c_descr,ed52_c_descr,ed31_c_descr, ";
              $sCamposTurma .= "ed40_c_descr,ed10_c_abrev,ed52_i_ano";
              $sWhereTurma   = " ed57_i_escola = $escola";
              $sSqlTurma     = $oDaoTurma->sql_query_turmaserie("", $sCamposTurma, $ordem.", ed57_c_descr", $sWhereTurma);
              $rsResultTurma = $oDaoTurma->sql_record($sSqlTurma);

              if ($oDaoTurma->numrows == 0) {

                $x = array(''=>'NENHUM REGISTRO');
                db_select('turma', $x,true,1,"style='width:500px;font-size:9px;'");

              } else {

                echo  "<select name='turma' id='turma' style='width:500px;font-size:9px;' multiple size='20'>";
                $temencerrado = false;
                $temavancado  = false;

                for($iCont = 0; $iCont < $oDaoTurma->numrows; $iCont++) {

                  db_fieldsmemory($rsResultTurma, $iCont);
                  $sWhereDiario   = " ed60_i_turma = ed59_i_turma AND ed95_c_encerrado = 'S' AND ed60_c_ativa = 'S'";
                  $sWhereDiario  .= " AND ed60_c_situacao = 'MATRICULADO' AND ed95_i_regencia ";
                  $sWhereDiario  .= " in (select ed59_i_codigo from regencia where ed59_i_turma = $ed57_i_codigo)";
                  $sSqlDiario     = $oDaoDiario->sql_query_matric("", "ed95_i_codigo", "", $sWhereDiario);
                  $rsResultDiario = $oDaoDiario->sql_record($sSqlDiario);

                  if ($oDaoDiario->numrows > 0) {

                    $temencerrado = true;
                    $disabled     = "disabled";
                    $obs          = "   *";

                  } else {

                    $sWhereDiario   = " ed60_i_turma = ed59_i_turma AND ";
                    $sWhereDiario  .= " (ed60_c_situacao = 'AVAN�ADO' OR ed60_c_situacao = 'CLASSIFICADO')";
                    $sWhereDiario  .= "  AND ed95_i_regencia in (select ed59_i_codigo from regencia ";
                    $sWhereDiario  .= "  where ed59_i_turma = $ed57_i_codigo)";
                    $sSqlDiario     = $oDaoDiario->sql_query_matric("", "ed95_i_codigo", "", $sWhereDiario);
                    $rsResultDiario = $oDaoDiario->sql_record($sSqlDiario);

                    if ($oDaoDiario->numrows > 0) {

                      $temavancado = true;
                      $disabled    = "disabled";
                      $obs         = "   **";

                    } else {

                      $disabled = "";
                      $obs      = "";

                    }

                  }

                  $sVerifica = $disabled != '' ? $obs : '';
                  echo " <option value='$ed57_i_codigo' $disabled>($ed57_i_codigo) $ed57_c_descr-";
                  echo "         $ed52_c_descr-$ed31_c_descr-$ed10_c_abrev - ";
                  echo "          $ed40_c_descr $sVerifica </option>";

                }

                echo "</select>";
                if ($temencerrado == true) {

                  echo "<br> * Turma cont�m aluno(s) j� encerrado(s). Para exclus�o geral desta turma, ";
                  echo " cancele o encerramento de avalia��es para todos os alunos da mesma.";

                }

                if ($temavancado == true) {

                  echo "<br> ** Turma cont�m aluno(s) progredido(s). Para exclus�o geral desta turma,  ";
                  echo " cancele a progress�o deste(s) aluno(s).";

                }

              }

              if ($oDaoTurma->numrows > 0) {

                echo "<br><br><b>Ordenar:</b><br>";
                echo "<select name='ordenacao' style='width:500px;font-size:9px;'
                              onchange='js_ordenacao(this.value);'>";
                echo "<option value='ed52_i_ano asc' $ordenacao=='ed52_i_ano asc'?'selected':''>
                       Ano Calend�rio Crescente</option>";
                echo "<option value='ed52_i_ano desc'$ordenacao=='ed52_i_ano desc'?'selected':>
                       Ano Calend�rio Decrescente</option>";
                echo "<option value='ed31_c_descr, ed10_c_abrev' $ordenacao=='ed31_c_descr,ed10_c_abrev'?'selected':''>
                       Base Curricular</option>";
                echo "<option value='ed40_c_descr' $ordenacao=='ed40_c_descr'?'selected':''>
                       Procedimento de Avalia��o</option>";
                echo "<option value='ed57_i_codigo' $ordenacao=='ed57_i_codigo'?'selected':''>C�digo da Turma</option>";
                echo "<option value='ed57_c_descr' $ordenacao=='ed57_c_descr'?'selected':''>Nome da Turma</option>";
                echo "</select>";

              }

              echo "</span>";
              echo"</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              echo "<hr align='left' width='55%'>";
              echo "<input type='button' value='Excluir' name='excluir'
                           onclick='js_prossegue();' style='visibility:hidden;'>";
              echo "<table width='300' height='50' id='tab_aguarde'
                           style='visibility:hidden;border:2px solid #444444;position:absolute;top:200px;left:400px;'
                           cellspacing='1' cellpading='2'>";
              echo "<tr>";
              echo "<td bgcolor='#DEB887' align='center' style='border:1px solid #444444;text-decoration:blink;'>";
              echo "<b>Aguarde...Processando exclus�o dos registros.</b>";
              echo "</td>";
              echo "</tr>";
              echo "</table>";
              echo "</td>";
              echo "</tr>";
              echo "<tr>";
              echo "<td>";
              echo " <br>";
              echo "<fieldset style='align:center'>";
              echo "Para selecionar mais de um �tem mantenha pressionada a tecla CTRL e clique sobre os �tens.";
              echo "</fieldset>";
              echo "</td>";
              echo "</tr>";

            } //fecha o  if (isset($escola)) {
    ?>
    </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
<?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),db_getsession("DB_instit")
         );
?>
</body>
</html>
<script>
function js_escola(valor) {

  if (valor == "") {
    location.href = "edu4_apagargeral001.php";
  } else {
    location.href = "edu4_apagargeral001.php?escola="+valor;
  }

}

function js_escolha(valor) {

  document.getElementById("id_calendario").style.visibility   = "hidden";
  document.getElementById("id_base").style.visibility         = "hidden";
  document.getElementById("id_procedimento").style.visibility = "hidden";
  document.getElementById("id_turma").style.visibility        = "hidden";
  tam                                                         = document.form1.escolha.length;
  for (iCont = 0; iCont < tam; iCont++) {

    if (document.form1.escolha[iCont].checked == true) {
      document.getElementById(valor).style.visibility = "visible";
    }

  }

  tamCal = document.form1.calendario.length;
  tamBas = document.form1.base.length;
  tamPro = document.form1.procedimento.length;
  tamTur = document.form1.turma.length;

  for (i = 0; i < tamCal; i++) {
    document.form1.calendario[i].selected = false;
  }

  for(i = 0; i < tamBas; i++) {
    document.form1.base[i].selected = false;
  }
  for(i = 0; i < tamPro; i++) {
    document.form1.procedimento[i].selected = false;
  }
  for(i = 0; i < tamTur; i++) {
    document.form1.turma[i].selected = false;
  }
  document.form1.excluir.style.visibility = "visible";
}

function js_prossegue() {

  tam = document.form1.escolha.length;
  for (i = 0; i < tam; i++) {

    if (document.form1.escolha[i].checked == true) {
      escolha = document.form1.escolha[i].value;
    }

  }

  tamCal           = document.form1.calendario.length;
  tamBas           = document.form1.base.length;
  tamPro           = document.form1.procedimento.length;
  tamTur           = document.form1.turma.length;
  escolhido        = "";
  sepescolhido     = "";
  textescolhido    = "";
  septextescolhido = "";

  if (escolha == "C") {

    tipo = "calendario";

    if (tamCal < 2) {

      escolhido     = document.form1.calendario.value;
      textescolhido = document.form1.calendario.options[0].text;

    } else {

      for (i = 0; i < tamCal; i++) {

        if (document.form1.calendario[i].selected == true) {

          escolhido       += sepescolhido+document.form1.calendario[i].value;
          sepescolhido     = ", ";
          textescolhido   += septextescolhido+document.form1.calendario[i].text;
          septextescolhido = "\n";

        }

      }

    }

  }

  if (escolha == "B") {

    tipo = "base";

    if (tamBas < 2) {

      escolhido     = document.form1.base.value;
      textescolhido = document.form1.base.options[0].text;

    } else {

      for (i = 0; i < tamBas; i++) {

        if (document.form1.base[i].selected == true) {

          escolhido       += sepescolhido+document.form1.base[i].value;
          sepescolhido     = ", ";
          textescolhido   += septextescolhido+document.form1.base[i].text;
          septextescolhido = "\n";

        }

      }

    }

  }

  if (escolha == "P") {

    tipo = "procedimento";

    if (tamPro < 2) {

      escolhido     = document.form1.procedimento.value;
      textescolhido = document.form1.procedimento.options[0].text;

    } else {

      for (i = 0; i < tamPro; i++) {

        if (document.form1.procedimento[i].selected == true) {

          escolhido       += sepescolhido+document.form1.procedimento[i].value;
          sepescolhido     = ", ";
          textescolhido   += septextescolhido+document.form1.procedimento[i].text;
          septextescolhido = "\n";

        }

      }

    }

  }

  if (escolha == "T") {

    tipo = "turma";

    if (tamTur < 2) {

      escolhido     = document.form1.turma.value;
      textescolhido = document.form1.turma.options[0].text;

    } else {

      for (i = 0; i < tamTur; i++) {

        if (document.form1.turma[i].selected == true) {

          escolhido       += sepescolhido+document.form1.turma[i].value;
          sepescolhido     = ", ";
          textescolhido   += septextescolhido+document.form1.turma[i].text;
          septextescolhido = "\n";

        }

      }

    }

  }

  if (escolhido == "") {

    alert("Selecione algum �tem para prosseguir!");
    return false;

  }

  if (confirm("Confirmar exclus�o dos registros:\n\n"+tipo+":\n"+textescolhido)) {

    document.getElementById("tab_aguarde").style.visibility = "visible";
    document.form1.excluir.disabled                         = true;
    location.href                                           = "edu4_apagargeral001.php?excluir&escola="
                                                              +document.form1.escola.value+
                                                              "&"+tipo+"="+escolhido;

  }

}

function js_ordenacao(valor) {
  location.href = "edu4_apagargeral001.php?escola="+document.form1.escola.value+"&ordenacao="+valor;
}

<?
  if (isset($ordenacao)) {?>

    document.form1.escolha[0].checked                    = true;
    document.getElementById("id_turma").style.visibility = "visible";
    document.form1.excluir.style.visibility              = "visible";

<?}?>
</script>