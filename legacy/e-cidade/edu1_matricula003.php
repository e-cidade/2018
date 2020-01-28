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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($_POST);

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

function reiniciaSequencia($oDao, $sQuery, $sCampoSeq, $sCampoCod, $sCondicao = '', $iInicioSeq = 1, $iIncremento = 1) {

  // Faço a busca dos registros da sequencia
  $sSql = $oDao->{$sQuery}(null, '*', $sCampoSeq.' asc ', $sCondicao);
  $rs   = $oDao->sql_record($sSql);
  if ($oDao->numrows <= 0) {
    return false;
  }

  $iNumRows = $oDao->numrows;
  for ($iCont = 0; $iCont < $iNumRows; $iCont++) {

    $oDados = db_utils::fieldsmemory($rs, $iCont);

    if ($oDados->{$sCampoSeq} != $iInicioSeq) { // Se o campo não estiver na sequência correta, altero

      $oDao->{$sCampoCod}  = $oDados->{$sCampoCod};
      $oDao->{$sCampoSeq}  = $iInicioSeq;
      $oDao->alterar($oDados->{$sCampoCod});

      if ($oDao->erro_status == '0') {
        return $oDao->erro_msg;
      }
    }

    $iInicioSeq += $iIncremento;
  }

  return '';
}

$resultedu = eduparametros(db_getsession("DB_coddepto"));

$clmatricula                 = new cl_matricula;
$clmatriculamov              = new cl_matriculamov;
$clmatriculaserie            = new cl_matriculaserie;
$clturma                     = new cl_turma;
$clturmaserieregimemat       = new cl_turmaserieregimemat;
$clpareceraval               = new cl_pareceraval;
$clparecerresult             = new cl_parecerresult;
$clabonofalta                = new cl_abonofalta;
$clalunocurso                = new cl_alunocurso;
$clalunopossib               = new cl_alunopossib;
$clalunotransfturma          = new cl_alunotransfturma;
$cltransfescolarede          = new cl_transfescolarede;
$cltransfescolafora          = new cl_transfescolafora;
$cldiario                    = new cl_diario;
$clamparo                    = new cl_amparo;
$cltransfaprov               = new cl_transfaprov;
$cldiarioavaliacao           = new cl_diarioavaliacao;
$cldiarioresultado           = new cl_diarioresultado;
$cldiariofinal               = new cl_diariofinal;
$cllogmatricula              = new cl_logmatricula;
$claprovconselho             = new cl_aprovconselho;
$cltrocaserie                = new cl_trocaserie;
$clserie                     = new cl_serie;
$clbaseserie                 = new cl_baseserie;
$clescolabase                = new cl_escolabase;
$oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();

$db_botao  = false;
$db_opcao  = 33;
$db_opcao1 = 3;

if ( isset( $excluir ) ) {

  $lErro     = false;
  $db_opcao  = 3;
  $db_opcao1 = 1;

  $sWhereTrocaSerie = "ed101_i_aluno = {$ed60_i_aluno} AND ed101_i_turmadest = {$ed60_i_turma}";
  $sSqlTrocaSerie   = $cltrocaserie->sql_query( "", "ed101_i_codigo", "", $sWhereTrocaSerie );
  $result_prog      = $cltrocaserie->sql_record( $sSqlTrocaSerie );

  if ($cltrocaserie->numrows > 0) {

    $lErro = true;
    $clmatricula->erro_status = "0";
    $clmatricula->erro_msg = "Aluno selecionado foi progredido para esta turma.
                              \\nPara excluir sua matrícula, esta progressão deve ser cancelada.
                              \\nAcesse Procedimentos -> Progressão de Aluno -> Cancelar Progressão";
  } else {

    db_inicio_transacao();
    $sql_exc = "SELECT DISTINCT ed95_i_codigo as coddiario
                  FROM diarioavaliacao
                       inner join diario on ed95_i_codigo = ed72_i_diario
                 WHERE ed95_i_aluno = $ed60_i_aluno
                   AND ed95_i_regencia in (select ed59_i_codigo from regencia
                                            where ed59_i_turma in (SELECT ed60_i_turma
                                                                     from matricula
                                                                    where ed60_matricula = {$oPost->ed60_matricula}))";

    $result_exc = db_query($sql_exc);
    if ($result_exc == false) {

      $lErro = true;
      $sMsg  = pg_last_error();
    }

    if (!$lErro) {

      $linhas_exc = pg_num_rows($result_exc);
      for ($z = 0; $z < $linhas_exc; $z++) {

        db_fieldsmemory($result_exc, $z);
        $clamparo->excluir( "", " ed81_i_diario = {$coddiario}" );
        if ($clamparo->erro_status == '0') {

          $lErro = true;
          $sMsg  = $clamparo->erro_msg;
          break;
        }

        $cldiariofinal->excluir(""," ed74_i_diario = $coddiario");
        if ($cldiariofinal->erro_status == '0') {

          $lErro = true;
          $sMsg  = $cldiariofinal->erro_msg;
          break;
        }

        $result5 = db_query("select ed73_i_codigo from diarioresultado where ed73_i_diario = {$coddiario}");
        if ($result5 == false) {

          $lErro = true;
          $sMsg  = pg_last_error();
          break;
        }

        $linhas5 = pg_num_rows($result5);
        for ($t = 0; $t < $linhas5; $t++) {

          db_fieldsmemory($result5,$t);
          $clparecerresult->excluir( ""," ed63_i_diarioresultado = {$ed73_i_codigo}" );
          if ($clparecerresult->erro_status == '0') {

            $lErro = true;
            $sMsg  = $clparecerresult->erro_msg;
            break 2;
          }

          $oDaoDiarioResultadoRecuperacao   = new cl_diarioresultadorecuperacao();
          $sWhereDiarioResultadoRecuperacao = "ed116_diarioresultado = {$ed73_i_codigo}";
          $oDaoDiarioResultadoRecuperacao->excluir("", $sWhereDiarioResultadoRecuperacao);

          if ( $oDaoDiarioResultadoRecuperacao->erro_status == '0') {

            $lErro = true;
            $sMsg  = $oDaoDiarioResultadoRecuperacao->erro_msg;
            break;
          }
        }

        $cldiarioresultado->excluir(""," ed73_i_diario = $coddiario");
        if ($cldiarioresultado->erro_status == '0') {

          $lErro = true;
          $sMsg  = $cldiarioresultado->erro_msg;
          break;
        }

        $result6 = db_query("select ed72_i_codigo from diarioavaliacao where ed72_i_diario = $coddiario");
        if ($result6 == false) {

          $lErro = true;
          $sMsg  = pg_last_error();
          break;
        }

        $linhas6 = pg_num_rows($result6);
        for ($t=0;$t<$linhas6;$t++) {

          db_fieldsmemory($result6,$t);

          /**
           * Deleta da tabela transfaprov
           */
          $lResultadoTransfaProv = db_query("delete from transfaprov where ed251_i_diariodestino = $ed72_i_codigo or ed251_i_diarioorigem = $ed72_i_codigo");

          if ( !$lResultadoTransfaProv ) {

            $lErro = true;
            $sMsg  = pg_last_error();
          }

          $clpareceraval->excluir(""," ed93_i_diarioavaliacao = $ed72_i_codigo");
          if ($clpareceraval->erro_status == '0') {

            $lErro = true;
            $sMsg  = $clpareceraval->erro_msg;
            break 2;
          }


          $clabonofalta->excluir(""," ed80_i_diarioavaliacao = $ed72_i_codigo");
          if ($clabonofalta->erro_status == '0') {

            $lErro = true;
            $sMsg  = $clabonofalta->erro_msg;
            break 2;
          }
        }

        $sSqlDiarioAvaliacao    = $cldiarioavaliacao->sql_query_file(null, 'ed72_i_codigo', null, "ed72_i_diario = {$coddiario}");
        $rsDiarioAvaliacao      = $cldiarioavaliacao->sql_record($sSqlDiarioAvaliacao);
        $iCodigoDiarioAvaliacao = db_utils::fieldsMemory($rsDiarioAvaliacao, 0)->ed72_i_codigo;
        $sWhereTransfAprov      = "ed251_i_diarioorigem = {$iCodigoDiarioAvaliacao} or ed251_i_diariodestino = {$iCodigoDiarioAvaliacao}";
        $cltransfaprov->excluir(null, $sWhereTransfAprov);

        $cldiarioavaliacao->excluir(""," ed72_i_diario = $coddiario");
        if ($cldiarioavaliacao->erro_status == '0') {

          $lErro = true;
          $sMsg  = $cldiarioavaliacao->erro_msg;
          break;
        }

        $claprovconselho->excluir(""," ed253_i_diario = $coddiario");
        if ($claprovconselho->erro_status == '0') {

          $lErro = true;
          $sMsg  = $claprovconselho->erro_msg;
          break;
        }

        $oDaoDiarioRegraCalculo = new cl_diarioregracalculo();
        $oDaoDiarioRegraCalculo->excluir(""," ed125_diario = {$coddiario}");
        if ($oDaoDiarioRegraCalculo->erro_status == '0') {

          $lErro = true;
          $sMsg  = $oDaoDiarioRegraCalculo->erro_msg;
          break;
        }

        $oDaoDiarioAvaliacaoAlternativa = new cl_diarioavaliacaoalternativa();
        $oDaoDiarioAvaliacaoAlternativa->excluir(null, " ed136_diario = {$coddiario} " );
        if ( $oDaoDiarioAvaliacaoAlternativa->erro_status == '0') {

          $lErro = true;
          $sMsg  = $oDaoDiarioAvaliacaoAlternativa->erro_msg;
          break 2;
        }

        $cldiario->excluir(""," ed95_i_codigo = $coddiario");
        if ($cldiario->erro_status == '0') {

          $lErro = true;
          $sMsg  = $cldiario->erro_msg;
          break;
        }
      }
    }

    $oDaoMatricula   = db_utils::getDao('matricula');
    $sWhereMatricula = " ed60_matricula = {$oPost->ed60_matricula} ";
    $sSqlMatricula   = $oDaoMatricula->sql_query_file(null, "ed60_i_codigo", null, $sWhereMatricula);
    $rsMatricula     = $oDaoMatricula->sql_record($sSqlMatricula);
    $iLinhas         = $oDaoMatricula->numrows;

    for ($i = 0; $i < $iLinhas; $i++ ) {

      $ed60_i_codigo = db_utils::fieldsMemory($rsMatricula, $i)->ed60_i_codigo;

      if (!$lErro) {

        $clmatriculamov->excluir(""," ed229_i_matricula = $ed60_i_codigo ");
        if ($clmatriculamov->erro_status == '0') {

          $lErro = true;
          $sMsg  = $clmatriculamov->erro_msg;
        }
      }

      if (!$lErro) {

        $clalunotransfturma->excluir("","ed69_i_matricula  = $ed60_i_codigo ");
        if ($clalunotransfturma->erro_status == '0') {

          $lErro = true;
          $sMsg  = $clalunotransfturma->erro_msg;
        }
      }

      if (!$lErro) {

        $cltransfescolarede->excluir("","ed103_i_matricula  = $ed60_i_codigo ");
        if ($cltransfescolarede->erro_status == '0') {

          $lErro = true;
          $sMsg  = $cltransfescolarede->erro_msg;
        }
      }

      if (!$lErro) {

        $cltransfescolafora->excluir("","ed104_i_matricula  = $ed60_i_codigo ");
        if ($cltransfescolafora->erro_status == '0') {

          $lErro = true;
          $sMsg  = $cltransfescolafora->erro_msg;
        }
      }

      if (!$lErro) {

        $clmatriculaserie->excluir("","ed221_i_matricula  = $ed60_i_codigo ");
        if ($clmatriculaserie->erro_status == '0') {

          $lErro = true;
          $sMsg  = $clmatriculaserie->erro_msg;
        }
      }

      if ( !$lErro ) {

        $oDaoMatriculaTurnoReferente->excluir( null, "ed337_matricula = {$ed60_i_codigo}" );
        if ( $oDaoMatriculaTurnoReferente->erro_status == '0' ) {

          $lErro = true;
          $sMsg  = $oDaoMatriculaTurnoReferente->erro_msg;
        }
      }

      if ( !$lErro ) {

        $clmatricula->excluir($ed60_i_codigo);
        if ($clmatricula->erro_status == '0') {

          $lErro = true;
          $sMsg  = $clmatricula->erro_msg;
        }
      }
    }

    if (!$lErro) {

      $sql1 = "SELECT ed56_i_codigo FROM alunocurso
                WHERE ed56_i_aluno = {$ed60_i_aluno}";
      $query1 = db_query($sql1);

      if ($query1 == false) {

        $lErro = true;
        $sMsg  = pg_last_error();
      }

      if (!$lErro) {

        $linhas1 = pg_num_rows($query1);

        if ($linhas1 > 0) {

          db_fieldsmemory($query1,0);
          $sql0 = "SELECT ed60_i_codigo as matrant,
                          ed57_i_escola as escolaant,
                          ed57_i_base as baseant,
                          ed57_i_calendario as calant,
                          ed60_c_situacao as sitant,
                          ed60_c_concluida as concant,
                          ed60_c_ativa as ativaant,
                          ed221_i_serie as serieant,
                          ed57_i_turno as turnoant,
                          ed11_i_sequencia as seqant,
                          ed11_i_ensino as ensinoant,
                          ed60_i_turma as turmaant
                     FROM matricula
                          inner join turma          on ed57_i_codigo     = ed60_i_turma
                          inner join matriculaserie on ed221_i_matricula = ed60_i_codigo
                          inner join serie          on ed11_i_codigo     = ed221_i_serie
                          left  join matriculamov   on ed229_i_matricula = ed60_i_codigo
                    WHERE ed60_i_aluno   = {$ed60_i_aluno}
                      AND ed229_d_data   is not null
                      AND ed221_c_origem = 'S'
                    ORDER BY ed60_d_datamatricula desc LIMIT 1";
          $result0 = db_query($sql0);
          if ($result0 == false) {

            $lErro = true;
            $sMsg  = pg_last_error();
          }

          if (!$lErro) {

            $linhas0 = pg_num_rows($result0);
            if ($linhas0 > 0) {

              db_fieldsmemory($result0, 0);
              if ($sitant == "MATRICULA INDEVIDA") {

                $sitant = "MATRICULADO";
                $sql01 = "UPDATE matricula SET
                                 ed60_c_ativa        = 'S',
                                 ed60_c_concluida    = 'N',
                                 ed60_c_situacao     = 'MATRICULADO',
                                 ed60_d_datasaida    = null,
                                 ed60_d_datamodifant = null
                           WHERE ed60_i_codigo = {$matrant}";
                $result01 = db_query($sql01);

                if ($result01 == false) {

                  $lErro = true;
                  $sMsg  = pg_last_error();
                }

                $sDescricaoMovimentacao               = "SITUAÇÃO DA MATRÍCULA MODIFICADA DE MATRICULA INDEVIDA ";
                $sDescricaoMovimentacao              .= "PARA MATRICULADO";
                $clmatriculamov->ed229_i_matricula    = $matrant;
                $clmatriculamov->ed229_i_usuario      = db_getsession("DB_id_usuario");
                $clmatriculamov->ed229_c_procedimento = "ALTERAR SITUAÇÃO DA MATRÍCULA";
                $clmatriculamov->ed229_d_dataevento   = date("Y-m-d", db_getsession("DB_datausu"));
                $clmatriculamov->ed229_c_horaevento   = date("H:i");
                $clmatriculamov->ed229_t_descr        = $sDescricaoMovimentacao;
                $clmatriculamov->ed229_d_data = date("Y-m-d",db_getsession("DB_datausu"));
                $clmatriculamov->incluir($ed229_i_codigo);

                if ($clmatriculamov->erro_status == 0) {

                  $lErro = true;
                  $sMsg  = $clmatriculamov->erro_msg;
                }
              }

              if (trim($sitant)!="AVANÇADO" && trim($sitant)!="CLASSIFICADO") {

                if ($concant=="S") {

                  if (trim($sitant)=="MATRICULADO") {

                    $resfinal = ResultadoFinal($matrant,$ed60_i_aluno,$turmaant,$sitant,$concant);
                    $sitant   = $resfinal=="REPROVADO"?"REPETENTE":$resfinal;

                  } elseif (trim($sitant)=="TROCA DE MODALIDADE") {

                    $sql01 = "UPDATE matricula SET
                                     ed60_c_ativa        = 'S',
                                     ed60_c_concluida    = 'N',
                                     ed60_c_situacao     = 'MATRICULADO',
                                     ed60_d_datasaida    = null,
                                     ed60_d_datamodifant = null
                               WHERE ed60_i_codigo = {$matrant}";
                    $result01 = db_query($sql01);

                    if ($result01 == false) {

                      $lErro = true;
                      $sMsg  = pg_last_error();
                    }

                    if (!$lErro) {

                      $sql02 = "UPDATE diario SET
                                       ed95_c_encerrado = 'N'
                                 WHERE ed95_i_regencia in (select ed59_i_codigo from regencia where ed59_i_turma = {$turmaant})
                                   AND ed95_i_aluno = {$ed60_i_aluno}";
                      $result02 = db_query($sql02);
                      if ($result02 == false) {

                        $lErro = true;
                        $sMsg  = pg_last_error();
                      }
                    }

                    if (!$lErro) {

                      $sql02 = "DELETE FROM alunotransfturma
                                 WHERE ed69_i_matricula   = {$matrant}
                                   AND ed69_i_turmaorigem = {$turmaant}";
                      $result02 = db_query($sql02);
                      if ($result02 == false) {

                        $lErro = true;
                        $sMsg  = pg_last_error();
                      }

                      $sitant = "MATRICULADO";
                    }
                  }
                }

                if (trim($sitant)=="TRANSFERIDO REDE") {
                  $escolaant = $ed57_i_escola;
                }

                if (trim($ativaant) == "N" && !$lErro) {

                  $sql0 = "UPDATE matricula SET
                                  ed60_c_ativa     = 'S',
                                  ed60_c_concluida = 'N'
                            WHERE ed60_i_codigo = {$matrant}";
                  $result0 = db_query($sql0);
                  if ($result0 == false) {

                    $lErro = true;
                    $sMsg  = pg_last_error();
                  }
                }

                if ($sitant == "APROVADO COM PROGRESSAO PARCIAL /DEPENDÊNCIA") {
                  $sitant = 'APROVADO';
                }

                if (!$lErro) {

                  $sql1 = "UPDATE alunocurso SET
                                  ed56_i_escola        = {$escolaant},
                                  ed56_i_base          = {$baseant},
                                  ed56_i_calendario    = {$calant},
                                  ed56_c_situacao      = '{$sitant}',
                                  ed56_i_baseant       = null,
                                  ed56_i_calendarioant = null,
                                  ed56_c_situacaoant   = ''
                            WHERE ed56_i_codigo = {$ed56_i_codigo}";

                  $result1 = db_query($sql1);

                  if ($result1 == false) {

                    $lErro = true;
                    $sMsg  = pg_last_error();
                  }

                  if (!$lErro) {

                    if ( trim($sitant) == "APROVADO" ) {

                      $sSqlTurmaSerieRegimeMat = $clturmaserieregimemat->sql_query(
                                                                                    "",
                                                                                    "max(ed11_i_sequencia) as seqant",
                                                                                    "",
                                                                                    "ed220_i_turma = {$ed60_i_turma}"
                                                                                  );
                      $result_maxseq           = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat );

                      $proxseq          = ($seqant + 1);
                      $sCamposBaseSerie = "si.ed11_i_sequencia as inicial, sf.ed11_i_sequencia as final";
                      $sSqlBaseSerie    = $clbaseserie->sql_query( "", $sCamposBaseSerie, "", "ed87_i_codigo = {$baseant}" );
                      $result_baseserie = $clbaseserie->sql_record( $sSqlBaseSerie );
                      db_fieldsmemory($result_baseserie, 0);

                      if ( $proxseq >= $inicial && $proxseq <= $final ) {

                        $sWhereSerie  = " ed11_i_ensino = {$ensinoant} AND ed11_i_sequencia = {$proxseq}";
                        $sSqlSerie    = $clserie->sql_query( "", "ed11_i_codigo as serieant", "", $sWhereSerie );
                        $result_serie = $clserie->sql_record( $sSqlSerie );
                        db_fieldsmemory( $result_serie, 0 );
                      } else {

                        $sCamposEscolaBase = "ed77_i_basecont as basecont, cursoeducont.ed29_i_codigo as cursocont";
                        $sWhereEscolaBase  = "ed77_i_base = {$baseant} AND ed77_i_escola = {$escolaant}";
                        $sSqlEscolaBase    = $clescolabase->sql_query( "", $sCamposEscolaBase, "", $sWhereEscolaBase );
                        $result_basecont   = $clescolabase->sql_record( $sSqlEscolaBase );
                        db_fieldsmemory( $result_basecont, 0 );

                        if ( $basecont != "" ) {

                          $sCamposBaseSerie  = "si.ed11_i_sequencia as inicial, sf.ed11_i_sequencia as final";
                          $sCamposBaseSerie .= ", si.ed11_i_ensino as ensino";
                          $sSqlBaseSerie     = $clbaseserie->sql_query( "", $sCamposBaseSerie, "", "ed87_i_codigo = {$basecont}" );
                          $result_baseserie  = $clbaseserie->sql_record( $sSqlBaseSerie );
                          db_fieldsmemory( $result_baseserie, 0 );

                          $sWhereSerie  = "ed11_i_ensino = {$ensino} AND ed11_i_sequencia = {$inicial}";
                          $sSqlSerie    = $clserie->sql_query( "", "ed11_i_codigo as serieant", "", $sWhereSerie );
                          $result_serie = $clserie->sql_record( $sSqlSerie );
                          db_fieldsmemory( $result_serie, 0 );

                         } else {
                           $serieant = $serieant;
                         }
                       }
                    } else if (trim($sitant) == "APROVADO PARCIAL" ) {
                      $serieant = $serieant;
                    }
                  }

                  if (!$lErro) {

                    $sql1 = "UPDATE alunopossib SET
                                    ed79_i_serie    = {$serieant},
                                    ed79_i_turno    = {$turnoant},
                                    ed79_i_turmaant = null,
                                    ed79_c_resulant = '',
                                    ed79_c_situacao = 'A'
                              WHERE ed79_i_alunocurso = {$ed56_i_codigo}";
                    $result1 = db_query($sql1);

                    if ($result1 == false) {

                      $lErro = true;
                      $sMsg  = pg_last_error();

                    }

                    if ( trim( $sitant ) == "TRANSFERIDO REDE" && !$lErro ) {

                      $sql2 = "UPDATE transfescolarede SET
                                      ed103_c_situacao = 'A'
                                WHERE ed103_i_codigo in (select ed103_i_codigo
                                                          from transfescolarede
                                                         where ed103_i_matricula = {$matrant})";

                        $result2 = db_query($sql2);

                      if ($result2 == false) {

                        $lErro = true;
                        $sMsg  = pg_last_error();
                      }
                    }

                    if ( trim( $sitant ) == "TRANSFERIDO FORA" && !$lErro ) {

                      $sql2 = "UPDATE transfescolafora SET
                                      ed104_c_situacao = 'A'
                                WHERE ed104_i_codigo = (select ed104_i_codigo
                                                          from transfescolafora
                                                         where ed104_i_matricula = {$matrant})";
                      $result2 = db_query($sql2);

                      if ($result2 == false) {

                        $lErro = true;
                        $sMsg  = pg_last_error();
                      }
                    }
                  }
                }
              } else {

                $sql1 = "UPDATE alunocurso SET
                                ed56_c_situacao = 'CANDIDATO'
                          WHERE ed56_i_codigo = {$ed56_i_codigo}";
                $result1 = db_query($sql1);

                if ($result1 == false) {

                  $lErro = true;
                  $sMsg  = pg_last_error();
                }
              }
            } else {

              $sql1 = "UPDATE alunocurso SET
                              ed56_c_situacao    = 'CANDIDATO',
                              ed56_c_situacaoant = ''
                        WHERE ed56_i_codigo = {$ed56_i_codigo}";
              $result1 = db_query($sql1);

              if ($result1 == false) {

                $lErro = true;
                $sMsg  = pg_last_error();
              }
            }
          }

          if (!$lErro) {

            $descr_origem = "Matrícula n°: $ed60_matricula\nTurma: $ed57_c_descr\nEscola: ".db_getsession("DB_nomedepto")."\nCalendário: $ed52_c_descr";
            $cllogmatricula->ed248_i_usuario = db_getsession("DB_id_usuario");
            $cllogmatricula->ed248_i_motivo  = $ed248_i_motivo;
            $cllogmatricula->ed248_i_aluno   = $ed60_i_aluno;
            $cllogmatricula->ed248_t_origem  = $descr_origem;
            $cllogmatricula->ed248_t_obs     = $ed248_t_obs;
            $cllogmatricula->ed248_d_data    = date("Y-m-d",db_getsession("DB_datausu"));
            $cllogmatricula->ed248_c_hora    = date("H:i");
            $cllogmatricula->ed248_c_tipo    = "E";
            $cllogmatricula->incluir(null);

            if ($cllogmatricula->erro_status == '0') {

              $lErro = true;
              $sMsg  = $cllogmatricula->erro_msg;
            }

            if (!$lErro) {

              $sMsgTmp = $clmatricula->erro_msg;

              // Verifico se a numeração (sequência) dos alunos já foi gerada.
              $sSql = $clmatricula->sql_query_file( null, 'max(ed60_i_codigo) as max', '', "ed60_i_turma = {$ed60_i_turma}" );

              if ( !empty( $oMax->max ) && is_int( $oMax->max ) ) { // Se não retornar um número, não foi gerada a seqência ainda

                $iTurmaAluno = $ed60_i_turma;
                $iAluno      = $ed60_i_aluno;

                // Deleção das variáveis globais devido a problemas que elas causam na alteração
                unset($GLOBALS['HTTP_POST_VARS']['ed60_i_turma']);
                unset($GLOBALS['HTTP_POST_VARS']['ed60_i_aluno']);
                $sMsg = reiniciaSequencia($clmatricula, 'sql_query_file', 'ed60_i_numaluno', 'ed60_i_codigo',
                                          'ed60_i_turma = '.$iTurmaAluno
                                         );

                // Seto novamente as variáveis globais para que possam continuar sendo usadas
                $GLOBALS['HTTP_POST_VARS']['ed60_i_turma'] = $iTurmaAluno;
                $GLOBALS['HTTP_POST_VARS']['ed60_i_aluno'] = $iAluno;

                if (!empty($sMsg)) {
                  $lErro = true;
                }
              }

              $clmatricula->erro_msg = $sMsgTmp;
            }
          }
        }
      }

      if (!$lErro) {

        /**
         * Verificamos se a matrícula anterior do aluno é uma transferencia. Caso seja uma transferencia,
         * cancelamos a conclusão da matricula, e a turma fica como o estado de encerrada parcial.
         */
        $oUltimaMatriculaAluno = MatriculaRepository::getUltimaMatriculaAluno(new Aluno($ed60_i_aluno));
        if (!empty($oUltimaMatriculaAluno) && $oUltimaMatriculaAluno->getSituacao() == 'TRANSFERIDO REDE') {

          $oUltimaMatriculaAluno->setConcluida(false);
          $oUltimaMatriculaAluno->salvar();

          /**
           * Abrir o encerramento da turma em que o aluno estava matriculado (o aluno não está mais encerrado)
           */
          $oDaoRegencia   = new cl_regencia();
          $oTurmaAnterior = $oUltimaMatriculaAluno->getTurma();
          if (!empty($oTurmaAnterior)) {

            $sSqlRegenciasDaTurma = $oDaoRegencia->sql_query_file(null,
                                                                  "ed59_i_codigo, ed59_c_encerrada",
                                                                  null,
                                                                  "ed59_i_turma = {$oTurmaAnterior->getCodigo()}"
                                                                 );
            $rsRegenciasDaTurma   = db_query($sSqlRegenciasDaTurma);
            if (!$rsRegenciasDaTurma) {

              $lErro = true;
              $sMsg  = "Erro ao consultar regências da turma de origem do aluno";
            }

            if (!$lErro) {

              $aRegencias = db_utils::getCollectionByRecord($rsRegenciasDaTurma);
              foreach ($aRegencias as $oRegencia) {

                $oDaoRegencia->ed59_i_codigo    = $oRegencia->ed59_i_codigo;
                $oDaoRegencia->ed59_c_encerrada = 'N';
                $oDaoRegencia->alterar($oRegencia->ed59_i_codigo);
                if ($oDaoRegencia->erro_status == 0) {

                  $lErro = true;
                  $sMsg  = $oDaoRegencia->erro_msg;
                }
              }
            }
          }
        }
      }
    }

    //$lErro = true;
    db_fim_transacao( $lErro );
    if ($lErro) {

      $clmatricula->erro_status = '0';
      $clmatricula->erro_msg    = $sMsg;
    }
  }
} else if ( isset( $chavepesquisa ) ) {

  $db_opcao  = 3;
  $db_opcao1 = 1;

  $sCampos  = "turma.*, calendario.*, base.*, cursoedu.*, turno.*, fc_nomeetapaturma(ed57_i_codigo) as nometapa";
  $sCampos .= ", fc_codetapaturma(ed57_i_codigo) as codetapa";
  $result   = $clturma->sql_record( $clturma->sql_query( "", $sCampos, "", "ed57_i_codigo = {$chavepesquisa}" ) );
  db_fieldsmemory( $result, 0 );

  $ed60_i_turma    = $ed57_i_codigo;
  $sWhereMatricula = "ed60_i_turma = $ed60_i_turma AND ed60_c_situacao = 'MATRICULADO'";
  $sSqlMatricula   = $clmatricula->sql_query_file( "", "count(*) ", "", $sWhereMatricula );
  $result1         = $clmatricula->sql_record( $sSqlMatricula );
  db_fieldsmemory( $result1, 0 );
  ?>
   <script>
     parent.document.formaba.a2.disabled    = false;
     parent.document.formaba.a2.style.color = "black";
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href      = 'edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>'
                                                                   +'&ed57_c_descr=<?=$ed57_c_descr?>'
                                                                   +'&ed52_c_descr=<?=$ed52_c_descr?>';
   </script>
  <?
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC">
    <?require_once(modification("forms/db_frmmatricula.php"));?>
</body>
</html>
<?
if ( isset( $excluir ) ) {

  if ( $clmatricula->erro_status == "0" ) {
    $clmatricula->erro( true, false );
  } else {

   ?>
   <script>
     parent.document.formaba.a2.disabled    = false;
     parent.document.formaba.a2.style.color = "black";
     (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href      = 'edu1_alunoturma001.php?ed60_i_turma=<?=$ed60_i_turma?>'
                                                                   +'&ed57_c_descr=<?=$ed57_c_descr?>'
                                                                   +'&ed52_c_descr=<?=$ed52_c_descr?>';
   </script>
   <?
   $clmatricula->erro(true,true);
  }
}

if ( $db_opcao == 33 ) {
  echo "<script>js_pesquisaed60_i_turma();</script>";
}
?>
<script>
js_tabulacaoforms("form1","excluir",true,1,"excluir",true);
</script>