<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

db_postmemory($_POST);

$oDaoRegencia            = new cl_regencia();
$oDaoTurmaSerieRegimeMat = new cl_turmaserieregimemat();
$oDaoMatricula           = new cl_matricula();
$oDaoMatriculaMov        = new cl_matriculamov();
$oDaoHistorico           = new cl_historico();
$oDaoHistoricoMpd        = new cl_historicompd();
$oDaoHistoricoMps        = new cl_historicomps();
$oDaoHistMpsDisc         = new cl_histmpsdisc();
$oDaoAlunoCurso          = new cl_alunocurso();
$oDaoDiarioFinal         = new cl_diariofinal();

$db_botao = true;
$iEscola  = db_getsession("DB_coddepto");

$sCamposRegencia  = " ed59_i_turma, ed57_c_descr, ed57_i_base, ed52_c_descr, ";
$sCamposRegencia .= " ed57_i_calendario as codcalend, ed52_i_ano as anocal, ed52_i_periodo as percal, ";
$sCamposRegencia .= " ed57_i_turno as codturno, ed29_i_codigo as codcurso,  ed29_i_avalparcial as parametro,ed12_i_codigo ";
$sWhereRegencia   = " ed59_i_turma = $turma AND ed59_i_serie = $codserieregencia ";
$sSqlRegencia     = $oDaoRegencia->sql_query("", $sCamposRegencia, "", $sWhereRegencia);
$rsRegencia       = $oDaoRegencia->sql_record($sSqlRegencia);
db_fieldsmemory($rsRegencia, 0);

if (isset($proximo)) {

  try {

    $tam = sizeof($alunos);

    /**
     * Variável de alunos com progressão parcial encerrada em que a progressão é da etapa que se deseja cancelar o encerramento
     */
    $aAlunosInconsistentes = array();

    for ($iCont = 0; $iCont < $tam; $iCont++) {

      db_inicio_transacao();

      /**
       * Valida alunos que possuem progressão com ano superior ao calendário da turma que se deseja
       * cancelar o encerremanto e a progressão esteja encerrada.
       */
      $oDaoProgressaoParcialAlunoMatricula   = db_utils::getDao('progressaoparcialalunomatricula');

      $sWhereProgressaoParcialAlunoMatricula  = "     ed114_aluno     = {$alunos[$iCont]} ";
      $sWhereProgressaoParcialAlunoMatricula .= " and ed114_serie     = {$codserieregencia} ";
      $sWhereProgressaoParcialAlunoMatricula .= " and ed114_ano       = {$anocal} ";
      $sWhereProgressaoParcialAlunoMatricula .= " and ed114_escola    = {$iEscola} ";
      $sWhereProgressaoParcialAlunoMatricula .= " and ed150_ano       > {$anocal} ";
      $sWhereProgressaoParcialAlunoMatricula .= " and ed150_encerrado is true ";

      $sSqlProgressaoParcialAlunoMatricula   = $oDaoProgressaoParcialAlunoMatricula->sql_query(null,
                                                                                               'progressaoparcialalunomatricula.*',
                                                                                               'ed150_sequencial',
                                                                                               $sWhereProgressaoParcialAlunoMatricula);

      $rsProgressaoParcialAlunoMatricula = $oDaoProgressaoParcialAlunoMatricula->sql_record($sSqlProgressaoParcialAlunoMatricula);

      //Caso encontre algum aluno com os critérios acima, passa para o próximo aluno e não cancela o encerramento do atual
      if ($oDaoProgressaoParcialAlunoMatricula->numrows > 0) {
        $aAlunosInconsistentes[] = $alunos[$iCont];
        continue;
      }

    	$sCamposAlunoCurso  = " ed56_i_codigo as codalunocurso, ed47_v_nome, ed56_c_situacao as sit_atual,  ";
    	$sCamposAlunoCurso .= " ed56_i_base as base_atual, ed56_i_calendario as cal_atual";
    	$sSqlAlunoCurso     = $oDaoAlunoCurso->sql_query("", $sCamposAlunoCurso, "", " ed56_i_aluno = $alunos[$iCont]");
      $rsAlunoCurso       = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);
      db_fieldsmemory($rsAlunoCurso, 0);

      if ($sit_atual == "CONCLUÍDO") {
        db_msgbox("Aluno $ed47_v_nome já concluiu o curso,  não sendo possível cancelar avaliações!");
      } else {

        db_fieldsmemory($rsAlunoCurso, 0);
        $sCamposReg = " ed12_i_codigo, ed59_i_codigo as regencia,  ed59_lancarhistorico";
        $sWhereReg  = " ed59_i_turma = $turma AND ed59_i_serie = $codserieregencia ";
        $sSqlReg    = $oDaoRegencia->sql_query("", $sCamposReg, "", $sWhereReg);
        $rsReg      = $oDaoRegencia->sql_record($sSqlReg);

        $sSqlMat  = " Update matricula set ";
        $sSqlMat .= "                  ed60_c_concluida = 'N', ";
        $sSqlMat .= "                  ed60_d_datamodif = '".date("Y-m-d", db_getsession("DB_datausu"))."' ";
        $sSqlMat .= "        WHERE ed60_i_aluno = $alunos[$iCont]";
        $sSqlMat .= "              AND ed60_i_turma = $turma AND ed60_c_ativa = 'S' ";
        $rsMat    = db_query($sSqlMat);
        if (!$rsMat) {

          $sErroMensagem = "Erro ao cancelar encerramento da turma.\\nNão foi possivel alterar situacao da matricula";
          throw new BusinessException($sErroMensagem);
        }

        $sCamposMatr  = " ed60_i_codigo as codmatricula, ed221_i_serie as etapainicial,";
        $sCamposMatr .= "    ed11_i_sequencia as seqetapainicial";
        $sWhereMatr   = " ed60_i_turma = $turma AND ed60_i_aluno = $alunos[$iCont] AND ed60_c_ativa = 'S'";
        $sSqlMatr     = $oDaoMatricula->sql_query("", $sCamposMatr, "", $sWhereMatr);
        $rsMatr       = $oDaoMatricula->sql_record($sSqlMatr);
        db_fieldsmemory($rsMatr, 0);

        $sDescricaoMovimento                    = "ENCERRAMENTO DE AVALIAÇÕES CANCELADO EM ";
        $sDescricaoMovimento                   .= date("d/m/Y", db_getsession("DB_datausu"));
        $oDaoMatriculaMov->ed229_i_matricula    = $codmatricula;
        $oDaoMatriculaMov->ed229_i_usuario      = db_getsession("DB_id_usuario");
        $oDaoMatriculaMov->ed229_c_procedimento = "CANCELAR ENCERRAMENTO DE AVALIAÇÕES";
        $oDaoMatriculaMov->ed229_t_descr        = $sDescricaoMovimento;
        $oDaoMatriculaMov->ed229_d_dataevento   = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoMatriculaMov->ed229_c_horaevento   = date("H:i");
        $oDaoMatriculaMov->ed229_d_data         = date("Y-m-d", db_getsession("DB_datausu"));
        $oDaoMatriculaMov->incluir(null);
        if ($oDaoMatriculaMov->erro_status == 0) {

          $sErroMensagem  = "Erro ao salvar dados da movimentação da matrícula";
          $sErroMensagem .= "Erro Técnico:{$oDaoMatriculaMov->erro_msg}";
          throw new BusinessException($sErroMensagem);
        }
        for ($y = 0; $y < $oDaoRegencia->numrows; $y++) {

          db_fieldsmemory($rsReg, $y);
          $sSqlUpReg = "UPDATE regencia SET ed59_c_encerrada = 'N', ed59_lancarhistorico = '{$ed59_lancarhistorico}' WHERE ed59_i_codigo = {$regencia} ";
          $rsUpReg   = db_query($sSqlUpReg);
          if (!$rsUpReg) {

            $sErroMensagem  = "Erro ao cancelar encerramento da Regencia da turma.";
            throw new BusinessException($sErroMensagem);
          }

          $sSqlUpDiario  = " UPDATE diario SET ed95_c_encerrado = 'N' ";
          $sSqlUpDiario .= " WHERE ed95_i_aluno = $alunos[$iCont] AND ed95_i_regencia = $regencia ";
          $rsUpDiario    = db_query($sSqlUpDiario);

          if (!$rsUpDiario) {

            $sErroMensagem  = "Erro ao cancelar encerramento do diário do aluno.";
            throw new BusinessException($sErroMensagem);
          }
          /**
           * cancela as progressoes aprovadas automaticamentes
           */
          $oDaoEncerramentoProgressaoParcial  = db_utils::getDao("progressaoparcialalunoencerradodiario");

          $sWhereProgressao           = " ed95_i_aluno = {$alunos[$iCont]} AND ed95_i_regencia = {$regencia}";
          $sSqlEncerramentoProgressao = $oDaoEncerramentoProgressaoParcial->sql_query_diariofinal(null,
                                                                                                  "ed151_sequencial,
                                                                                                  ed114_sequencial",
                                                                                                  null,
                                                                                                  $sWhereProgressao
                                                                                                );

          $rsProgessao = $oDaoEncerramentoProgressaoParcial->sql_record($sSqlEncerramentoProgressao);
          if ($oDaoEncerramentoProgressaoParcial->numrows > 0) {

            $oDadosProgressao = db_utils::fieldsMemory($rsProgessao, 0);

            $oDaoEncerramentoProgressaoParcial->excluir($oDadosProgressao->ed151_sequencial);
            if ($oDaoEncerramentoProgressaoParcial->erro_status == 0) {

              $sErroMensagem  = "Erro ao cancelar encerramento  dos dados de progressão parcial do aluno.";
              throw new BusinessException($sErroMensagem);
            }
            $oDaoProgressaoParcialAluno                         = db_utils::getDao("progressaoparcialaluno");
            $oDaoProgressaoParcialAluno->ed114_situacaoeducacao = ProgressaoParcialAluno::ATIVA;
            $oDaoProgressaoParcialAluno->ed114_tipoconclusao    = "null";
            $oDaoProgressaoParcialAluno->ed114_sequencial       = $oDadosProgressao->ed114_sequencial;
            $oDaoProgressaoParcialAluno->alterar($oDadosProgressao->ed114_sequencial);
            if ($oDaoProgressaoParcialAluno->erro_status == 0) {

              $sErroMensagem  = "Erro ao cancelar encerramento  dos dados de progressão parcial do aluno.";
              throw new BusinessException($sErroMensagem);
            }
          }
        }//fecha o for da regencia
        $sWhereTurmaSerieRegimeMat  = " ed220_i_turma = $turma AND ed11_i_sequencia >= $seqetapainicial ";
        $sSqlTurmaSerieRegimeMat    = $oDaoTurmaSerieRegimeMat->sql_query("",
                                                                          "ed223_i_serie",
                                                                          "",
                                                                          $sWhereTurmaSerieRegimeMat
                                                                         );
        $rsTurmaSerieRegimeMat      = $oDaoTurmaSerieRegimeMat->sql_record($sSqlTurmaSerieRegimeMat);
        $iLinhasTurmaSerieRegimeMat = $oDaoTurmaSerieRegimeMat->numrows;

        for ($tt = 0; $tt < $iLinhasTurmaSerieRegimeMat; $tt++) {

          db_fieldsmemory($rsTurmaSerieRegimeMat, $tt);

          if ($parametro == 2) {

            for ($y = 0; $y < $oDaoRegencia->numrows; $y++) {

              db_fieldsmemory($rsReg, $y);
              $sWhereHistMpsDisc  = " ed62_i_escola=$iEscola and ed61_i_aluno = $alunos[$iCont] ";
              $sWhereHistMpsDisc .= " AND ed61_i_curso = $codcurso AND ed62_i_serie = $ed223_i_serie ";
              $sWhereHistMpsDisc .= " and ed65_i_disciplina = $ed12_i_codigo ";
              $sSqlHistMpsDisc = $oDaoHistMpsDisc->sql_query("", "ed65_i_codigo as codhist", "", $sWhereHistMpsDisc);
              $rsHistMpsDisc   = $oDaoHistMpsDisc->sql_record($sSqlHistMpsDisc);

              if ($oDaoHistMpsDisc->numrows > 0) {

                db_fieldsmemory($rsHistMpsDisc,  0);
                $sSqlUpHist = "DELETE from histmpsdisc WHERE ed65_i_codigo = $codhist ";
                $rsUpHist   = db_query($sSqlUpHist);
                if (!$rsUpHist) {

                  $sErroMensagem  = "Erro ao cancelar dados do historico.";
                  throw new BusinessException($sErroMensagem);
                }

              }

            }

            $sWhereHistoricoMps  = " ed62_i_escola=$iEscola and ed61_i_aluno = $alunos[$iCont] ";
            $sWhereHistoricoMps .= " AND ed61_i_curso = $codcurso AND ed62_i_serie = $ed223_i_serie ";
            $sWhereHistoricoMps .= " AND not exists(select * ";
            $sWhereHistoricoMps .= "                  from histmpsdisc where ed65_i_historicomps = ed62_i_codigo)";
            $sSqlHistoricoMps    = $oDaoHistoricoMps->sql_query("",
                                                                "ed62_i_codigo as codhist",
                                                                "",
                                                                $sWhereHistoricoMps
                                                               );
            $rsHistoricoMps      = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);


            if ($oDaoHistoricoMps->numrows > 0) {

            	db_fieldsmemory($rsHistoricoMps, 0);
            	$sSqlHistMps = "DELETE from historicomps WHERE ed62_i_codigo = $codhist ";
              $rsHistMps   = db_query($sSqlHistMps);
              if (!$rsHistMps) {

                $sErroMensagem  = "Erro ao cancelar dados do historico.";
                throw new BusinessException($sErroMensagem);
              }

            } else {

             $sWhereHistoricoMps  = " ed62_i_escola=$iEscola and ed61_i_aluno = $alunos[$iCont] ";
             $sWhereHistoricoMps .= " AND ed61_i_curso = $codcurso AND ed62_i_serie = $ed223_i_serie ";
             $sSqlHistoricoMps    = $oDaoHistoricoMps->sql_query("",
                                                                 "ed62_i_codigo as codhist2",
                                                                 "",
                                                                 $sWhereHistoricoMps
                                                                );
             $rsHistoricoMps      = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);
             if ($oDaoHistoricoMps->numrows > 0) {

               db_fieldsmemory($rsHistoricoMps, 0);

               $sSqlUpHistMpsDisc   = "UPDATE histmpsdisc ";
               $sSqlUpHistMpsDisc  .= "   SET ed65_c_resultadofinal = 'P' WHERE ed65_i_historicomps = $codhist2 ";
               $rsUpHistMps         = db_query($sSqlUpHistMpsDisc);
               if (!$rsUpHistMps) {

                 $sErroMensagem  = "Erro ao cancelar dados das disciplinas do historico.";
                 throw new BusinessException($sErroMensagem);
               }
      	       $sSqlUpHistoricoMps  = "UPDATE historicomps ";
      	       $sSqlUpHistoricoMps .= "   SET ed62_c_resultadofinal = 'P' WHERE ed62_i_codigo = $codhist2 ";
               $rsUpHistoricoMps    = db_query($sSqlUpHistoricoMps);
               if (!$rsUpHistoricoMps) {

                 $sErroMensagem  = "Erro ao alterar dados  do historico.";
                 throw new BusinessException($sErroMensagem);
               }

             }

            }

          } else {

            $sWhereHistoricoMps  = " ed62_i_escola=$iEscola and ed61_i_aluno = $alunos[$iCont] ";
            $sWhereHistoricoMps .= " AND ed61_i_curso = $codcurso AND ed62_i_serie = $ed223_i_serie ";
            $sWhereHistoricoMps .= " AND ed62_i_anoref = $anocal ";
            $sSqlHistoricoMps    = $oDaoHistoricoMps->sql_query("",
                                                                "ed62_i_codigo as codhist",
                                                                "",
                                                                $sWhereHistoricoMps
                                                               );
            $rsHistoricoMps      = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);

            if ($oDaoHistoricoMps->numrows > 0) {

              db_fieldsmemory($rsHistoricoMps, 0);
              $sSqlDeleteDisc = "DELETE from histmpsdisc WHERE ed65_i_historicomps = $codhist ";
              $rsDeleteDisc   = db_query($sSqlDeleteDisc);
              if (!$rsDeleteDisc) {

                $sErroMensagem  = "Erro ao cancelar dados das disciplinas do historico.";
                throw new BusinessException($sErroMensagem);
              }
              $sSqlDeleteMps  = "DELETE from historicomps WHERE ed62_i_codigo = $codhist ";
              $rsDeleteMps    = db_query($sSqlDeleteMps);
              if (!$rsDeleteMps) {

                $sErroMensagem  = "Erro ao cancelar dados das disciplinas do historico.";
                throw new BusinessException($sErroMensagem);
              }

            }

          }

        }

        if ($codcalend == $cal_atual) {

        	$sCamposMatricula  = "ed60_i_codigo, ed60_c_situacao as sit_ant, ed60_i_turmaant as turma_ant, ";
        	$sCamposMatricula .= "ed60_c_rfanterior as rf_ant";

        	$sWhereMatricula  = "ed60_i_turma = $turma AND ed60_i_aluno = $alunos[$iCont] AND ed60_c_ativa = 'S'";
        	$sSqlMatricula    = $oDaoMatricula->sql_query("", $sCamposMatricula, "", $sWhereMatricula);
          $rsMatricula      = $oDaoMatricula->sql_record($sSqlMatricula);
          db_fieldsmemory($rsMatricula, 0);

          $turma_ant = $turma_ant == "" ? "null" : $turma_ant;

          $sSqlUpAlunoCurso  = " UPDATE alunocurso SET ";
          $sSqlUpAlunoCurso .= "                   ed56_i_base       = $ed57_i_base,  ";
          $sSqlUpAlunoCurso .= "                   ed56_i_calendario = $codcalend,  ";
          $sSqlUpAlunoCurso .= "                   ed56_c_situacao   = '$sit_ant' ";
          $sSqlUpAlunoCurso .= "        WHERE ed56_i_codigo = $codalunocurso ";
          $rsUpAlunoCurso    = db_query($sSqlUpAlunoCurso);
          if (!$rsUpAlunoCurso) {

            $sErroMensagem  = "Erro ao cancelar dados do curso do aluno.";
            throw new BusinessException($sErroMensagem);
          }
          $sSqlUpdAlunoPossib  = " UPDATE alunopossib SET ";
          $sSqlUpdAlunoPossib .= "                    ed79_i_serie    = $etapainicial,  ";
          $sSqlUpdAlunoPossib .= "                    ed79_i_turno    = $codturno,  ";
          $sSqlUpdAlunoPossib .= "                    ed79_i_turmaant = $turma_ant,  ";
          $sSqlUpdAlunoPossib .= "                    ed79_c_resulant = '$rf_ant' ";
          $sSqlUpdAlunoPossib .= "        WHERE ed79_i_alunocurso = $codalunocurso ";
          $rsUpAlunoPossib     = db_query($sSqlUpdAlunoPossib);
          if (!$rsUpAlunoPossib) {

            $sErroMensagem  = "Erro ao cancelar dados da pre matricula do aluno";
            throw new BusinessException($sErroMensagem);
          }
          $oMatricula    = new Matricula($ed60_i_codigo);
          $oDiarioClasse = $oMatricula->getDiarioDeClasse()->removerDisciplinasComProgressaParcial();
        }

      }//fecha o else

      db_fim_transacao(false);
    }
 
    $sWhere  = "     ed221_i_serie    = $codserieregencia ";
    $sWhere .= " and ed60_i_turma     = $turma ";
    $sWhere .= " and ed60_c_concluida = 'S' ";
    $sWhere .= " and ed60_c_situacao in ('TRANSFERIDO FORA', 'TRANSFERIDO REDE') ";

    $sSqlMatriculasTransferidas = $oDaoMatricula->sql_query_matriculaserie(null, "ed60_i_codigo", null, $sWhere);
    $rsMatriculasTransferidas   = db_query($sSqlMatriculasTransferidas);
    if ($rsMatriculasTransferidas && pg_num_rows($rsMatriculasTransferidas) > 0) {

      $iLinhas = pg_num_rows($rsMatriculasTransferidas);
      for ($i=0; $i < $iLinhas; $i++) { 

        $iCodigoMatricula    = db_utils::fieldsMemory($rsMatriculasTransferidas, $i)->ed60_i_codigo;
        $oDaoAlteraMatricula = new cl_matricula();

        $oDaoAlteraMatricula->ed60_c_concluida = 'N';
        $oDaoAlteraMatricula->ed60_i_codigo    = $iCodigoMatricula;
        $oDaoAlteraMatricula->alterar($iCodigoMatricula);
      }
    }

    $sMensagem = 'Cancelamento do Encerramento de Avaliações concluído!';

    if (count($aAlunosInconsistentes) > 0) {

      $sMensagem = '';
      /**
       * Percorre alunos com progressão parcial encerrada em que a progressão é da etapa que se deseja cancelar o encerramento
       * para exibir a mensagem ao usuário.
       */

      foreach ($aAlunosInconsistentes as $iAluno) {

        $oAluno = new Aluno($iAluno);

        $sMensagem .= "Aluno {$oAluno->getNome()} possui progressão parcial encerrada no calendário posterior a este.\n";
      }
      $sMensagem .= "É necessário cancelar o encerramento da progressão parcial e depois excluir o vínculo do aluno com a turma de progressão parcial.";
    }

    db_msgbox($sMensagem);

  } catch (BusinessException $eBussinesException) {

    db_msgbox($eBussinesException->getMessage());
    db_fim_transacao(true);
  }
  ?>
  <script>
   parent.dados.location.href = "edu1_diarioclasse004.php?turma=<?=$turma?>&ed57_c_descr=<?=$ed57_c_descr?>"+
                                "&ed52_c_descr=<?=$ed52_c_descr?>&codserieregencia=<?=$codserieregencia?>";
   parent.db_iframe_cancelar<?=$turma?>.hide();
  </script>
  <?
  exit;

}//fecha o if (isset($proximo ))
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style>
   .titulo {

     font-size: 11;
     color: #DEB887;
     background-color:#444444;
     font-weight: bold;

   }
   .cabec1 {

     font-size: 11;
     color: #000000;
     background-color:#999999;
     font-weight: bold;

   }

   .aluno {

     color: #000000;
     font-family : Tahoma;
     font-size: 10;

   }

   .alunopq {
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
  $sCamposMat = " DISTINCT ed60_i_aluno, ed47_v_nome, ed60_i_numaluno, ed60_c_situacao, ed52_i_ano, ed52_d_inicio,ed52_d_fim,ed52_i_codigo";
  $sWhereMat  = " ed60_i_turma = $ed59_i_turma AND ed221_i_serie = $codserieregencia AND ed60_c_concluida = 'S' ";
  $sWhereMat .= " AND ed95_c_encerrado = 'S' AND ed60_c_ativa = 'S' AND ed221_c_origem = 'S'";
  $sWhereMat .= " AND ed60_c_situacao != 'TROCA DE MODALIDADE' AND ed60_c_situacao != 'AVANÇADO' ";
  $sWhereMat .= " AND ed60_c_situacao != 'CLASSIFICADO' AND ed60_c_situacao != 'TRANSFERIDO REDE'";
  $sWhereMat .= " AND ed60_c_situacao != 'TRANSFERIDO FORA'";
  $sSqlMat    = $oDaoMatricula->sql_query_cancelaraval("",  $sCamposMat,  "ed47_v_nome",  $sWhereMat);
  $rsMat      = $oDaoMatricula->sql_record($sSqlMat);
  $iLinhasMat = $oDaoMatricula->numrows;

  if ($iLinhasMat == 0) {

  ?>
    <table border='1' width="100%" bgcolor="#cccccc" style="" cellspacing="0" cellpading="0">
     <tr>
      <td class='titulo'>
       Não existem alunos com avaliações encerradas.
      </td>
     </tr>
     <tr>
      <td align="center">
       <input type="button" name="fechar" value="Fechar" onclick="parent.db_iframe_cancelar<?=$turma?>.hide();">
      </td>
     </tr>
 <?

  } else {

 ?>
   <form name="form1" method="post" action="">
    <center>
     <table border="0" width="100%" align="center">
      <tr>
       <td valign="top" width="47%" align="center">
        <b>Alunos:</b><br>
        <select name="alunospossib" id="alunospossib" size="10" onclick="js_desabinc()"
                style="font-size:9px;width:320px;height:180px" multiple>
        <?
         if ($iLinhasMat > 0) {

           $alunos_nao = 0;

           for ($i = 0; $i < $iLinhasMat; $i++) {

             db_fieldsmemory($rsMat, $i);
             $sWhereCodMat  = " ed60_i_aluno = $ed60_i_aluno AND ed52_i_ano >= $ed52_i_ano ";
             $sWhereCodMat .= " AND ed52_d_inicio > '$ed52_d_fim' AND ed52_i_codigo != $ed52_i_codigo ";
             $sWhereCodMat .= " AND ed60_c_situacao != 'TROCA DE MODALIDADE'";
             $sSqlCodMat    = $oDaoMatricula->sql_query_cancelaravalmatricula("",  "ed60_i_codigo",  "",  $sWhereCodMat);
             $rsCodMat      = $oDaoMatricula->sql_record($sSqlCodMat);
             $iLinhasCodMat = $oDaoMatricula->numrows;

             if ($iLinhasCodMat > 0) {

               $color    = "red";
               $disabled = "disabled";
               $aster    = "**";
               $alunos_nao++;

             } else {

               $color    = "black";
               $disabled = "";
               $aster    = "";

             }

             echo "<option style='color:$color' $disabled value='$ed60_i_aluno'>
                    $aster $ed60_i_aluno - $ed47_v_nome ( $ed60_c_situacao )</option>\n";

           }

         }

        ?>
        </select>
       </td>
       <td align="center">
        <br>
        <table border="0">
         <tr>
          <td>
           <input name="incluirum" title="Incluir" type="button" value=">" onclick="js_alunospossib();"
                  style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;
                  background:#cccccc;font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
          </td>
         </tr>
         <tr><td height="1"></td></tr>
         <tr>
          <td>
           <input name="incluirtodos" title="Incluir Todos" type="button" value=">>" onclick="js_incluirtodos();"
                  style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                  font-size:15px;font-weight:bold;width:30px;height:20px;">
          </td>
         </tr>
         <tr><td height="8"></td></tr>
         <tr>
          <td>
           <hr>
          </td>
         </tr>
         <tr><td height="8"></td></tr>
         <tr>
          <td>
           <input name="excluirum" title="Excluir" type="button" value="<" onclick="js_excluir();"
                  style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                  font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
          </td>
         </tr>
         <tr><td height="1"></td></tr>
         <tr>
          <td>
           <input name="excluirtodos" title="Excluir Todos" type="button" value="<<" onclick="js_excluirtodos();"
                  style="border:1px outset;border-top-color:#f3f3f3;border-left-color:#f3f3f3;background:#cccccc;
                  font-size:15px;font-weight:bold;width:30px;height:20px;" disabled>
          </td>
         </tr>
        </table>
       </td>
       <td valign="top" width="47%" align="center">
        <b>Alunos para cancelar encerramento de avaliações:</b><br>
        <select name="alunos[]" id="alunos" size="10" onclick="js_desabexc()"
                style="font-size:9px;width:320px;height:180px" multiple>
       </select>
      </td>
     </tr>

   <?if ($alunos_nao > 0) {?>

       <tr>
        <td colspan="3">
         <font color="red">
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <b>**Aluno(s) já matriculado(s) em um calendário posterior a desta turma. Cancelamento não permitido.</b>
         </font>
        </td>
       </tr>

   <?}?>

     </table>
     <input name="proximo" type="submit" value="Confirmar" disabled onClick="js_selecionar();">
     <input name="fechar" type="button" value="Fechar" onclick="parent.db_iframe_cancelar<?=$turma?>.hide();">
     <input type="hidden" name="turma" value="<?=$turma?>">
     <input type="hidden" name="codserieregencia" value="<?=$codserieregencia?>">
   </form>
  </center>

<?}?>
 </body>
</html>
<script>
function js_alunospossib() {

  var Tam = document.form1.alunospossib.length;
  var F   = document.form1;

  for (x = 0; x < Tam; x++) {

    if (F.alunospossib.options[x].selected == true) {

      F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunospossib.options[x].text,
    	                                                                                F.alunospossib.options[x].value)
      F.alunospossib.options[x] = null;
      Tam--;
      x--;

    }

  }

  var Tam = document.form1.alunospossib.length;
  var F   = document.form1;


  for (x = 0; x < Tam; x++) {

    if (document.form1.alunospossib.options[x].disabled == false) {

      document.form1.alunospossib.options[x].selected = true;
      break;

    }

  }

  if (document.form1.alunospossib.length == 0) {

    document.form1.incluirum.disabled    = true;
    document.form1.incluirtodos.disabled = true;

  }

  document.form1.proximo.disabled      = false;
  document.form1.excluirtodos.disabled = false;
  document.form1.alunospossib.focus();

}

function js_incluirtodos() {

  var Tam = document.form1.alunospossib.length;
  var F   = document.form1;

  for (i = 0; i < Tam; i++) {

    if (F.elements['alunospossib'].options[0].disabled == true) {

      F.elements['alunospossib'].options[F.elements['alunospossib'].options.length] = new Option(F.alunospossib.options[0].text,
    	                                                                                         F.alunospossib.options[0].value
    	                                                                                        );
      F.alunospossib.options[0] = null;

    } else {

      F.elements['alunos[]'].options[F.elements['alunos[]'].options.length] = new Option(F.alunospossib.options[0].text,
    	                                                                               F.alunospossib.options[0].value);
      F.alunospossib.options[0] = null;

    }

  }

  var Tam = document.form1.alunospossib.length;
  var F   = document.form1;

  for (i = 0; i < Tam; i++) {

    F.elements['alunospossib'].options[i].disabled    = true;
    F.elements['alunospossib'].options[i].style.color = "red";

  }

  document.form1.incluirum.disabled    = true;
  document.form1.incluirtodos.disabled = true;
  document.form1.excluirtodos.disabled = false;
  document.form1.proximo.disabled      = false;
  document.form1.alunos.focus();

}

function js_excluir() {

  var F = document.getElementById("alunos");
  Tam   = F.length;

  for (x = 0; x < Tam; x++) {

    if (F.options[x].selected == true) {

      document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[x].text,
    	                                                                                   F.options[x].value
    	                                                                                  );
      F.options[x] = null;
      Tam--;
      x--;

    }

  }

  if (document.form1.alunos.length > 0) {
    document.form1.alunos.options[0].selected = true;
  }

  if (F.length == 0) {

    document.form1.proximo.disabled      = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;

  }

  document.form1.incluirtodos.disabled = false;
  document.form1.alunos.focus();

}

function js_excluirtodos() {

  var Tam = document.form1.alunos.length;
  var F   = document.getElementById("alunos");

  for (i = 0; i < Tam; i++) {

    document.form1.alunospossib.options[document.form1.alunospossib.length] = new Option(F.options[0].text,
    	                                                                                 F.options[0].value
    	                                                                                );
    F.options[0] = null;

  }

  if (F.length == 0) {

    document.form1.proximo.disabled      = true;
    document.form1.excluirum.disabled    = true;
    document.form1.excluirtodos.disabled = true;
    document.form1.incluirtodos.disabled = false;

  }

  document.form1.alunospossib.focus();

}


function js_selecionar() {

  var F = document.getElementById("alunos").options;

  for (var i = 0; i < F.length; i++)  {
    F[i].selected = true;
  }

  return true;

}

function js_desabinc() {

  for (i = 0; i<document.form1.alunospossib.length;i++){

    if(document.form1.alunospossib.length>0 && document.form1.alunospossib.options[i].selected){

      if(document.form1.alunos.length>0){
        document.form1.alunos.options[0].selected = false;
      }

      document.form1.incluirum.disabled = false;
      document.form1.excluirum.disabled = true;

    }

  }

}

function js_desabexc() {

  for (i = 0; i < document.form1.alunos.length; i++) {

    if (document.form1.alunos.length > 0 && document.form1.alunos.options[i].selected) {

      if (document.form1.alunospossib.length > 0) {
        document.form1.alunospossib.options[0].selected = false;
      }

      document.form1.incluirum.disabled = true;
      document.form1.excluirum.disabled = false;

    }

  }

}
</script>