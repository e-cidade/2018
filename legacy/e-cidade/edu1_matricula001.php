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

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

if ( !isset($ed60_d_datamatricula_dia) ) {

  $ed60_d_datamatricula_dia = date("d",db_getsession("DB_datausu"));
  $ed60_d_datamatricula_mes = date("m",db_getsession("DB_datausu"));
  $ed60_d_datamatricula_ano = date("Y",db_getsession("DB_datausu"));
}

db_postmemory($_POST);

$clmatricula                 = new cl_matricula;
$clmatriculamov              = new cl_matriculamov;
$clmatriculaserie            = new cl_matriculaserie;
$clcalendario                = new cl_calendario;
$clturma                     = new cl_turma;
$clturmaserieregimemat       = new cl_turmaserieregimemat;
$claluno                     = new cl_aluno;
$clbase                      = new cl_base;
$clserie                     = new cl_serie;
$clalunopossib               = new cl_alunopossib;
$clalunocurso                = new cl_alunocurso;
$clhistoricomps              = new cl_historicomps;
$cledu_parametros            = new cl_edu_parametros;
$oDaoMatricula               = new cl_matricula();
$oDaoTurmaTurnoReferente     = new cl_turmaturnoreferente();
$oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();

$db_opcao = 1;
$db_botao = false;
$escola   = db_getsession("DB_coddepto");

?>
<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/classes/educacao/escola/TurmaTurnoReferente.classe.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<?php

$result_parametros = $cledu_parametros->sql_record( $cledu_parametros->sql_query( "", "*", "", "ed233_i_escola = {$escola}" ) );

if ( $cledu_parametros->numrows > 0 ) {
  db_fieldsmemory( $result_parametros, 0 );
} else {

  echo "Erro! Parâmetros não informados";
  exit;
}

if ( isset( $incluir ) ) {

  $oTurma          = TurmaRepository::getTurmaByCodigo($ed60_i_turma);
  $lEnsinoInfantil = $oTurma->getBaseCurricular()->getCurso()->getEnsino()->isInfantil();
  $lTurnoIntegral  = $oTurma->getTurno()->isIntegral();

  $aTurnosSelecionados = array();
  $iNumeroCandidatos   = count( $alunos );
  $lTurmaSemVagas      = false;

  if ( isset($check_turno1) ) {

    $aTurnosSelecionados[] = $check_turno1;
    if( $iNumeroCandidatos > $disponiveis1) {
      $lTurmaSemVagas = true;
    }
  }
  if ( isset($check_turno2) ) {

    $aTurnosSelecionados[] = $check_turno2;
    if ($iNumeroCandidatos > $disponiveis2) {
      $lTurmaSemVagas = true;
    }
  }
  if ( isset($check_turno3) ) {

    $aTurnosSelecionados[] = $check_turno3;
    if ($iNumeroCandidatos > $disponiveis3) {

      $lTurmaSemVagas = true;
    }
  }

  if ( $lTurmaSemVagas ) {

    db_msgbox("Número de alunos selecionados é maior que as vagas disponíveis");
    db_redireciona("edu1_matricula001.php?chavepesquisa={$ed60_i_turma}");
  } else {

    $lErroTransacao = false;

    /**
     * Busca os códigos dos turnos refenrentes da turma, com base no que foi selecionado;
     * Sempre que turma tivér um turno integral e não for de ensino infantil, será incluso a matrícula
     * para ambos turnos.
     */
    $sWhereTurmaTurnoReferente     = "     ed336_turma = {$ed60_i_turma} ";
    $sWhereTurmaTurnoReferente    .= " and ed336_turnoreferente in (" .implode(",", $aTurnosSelecionados) . ")";
    $sSqlBuscaTurnoReferentesTurma =  $oDaoTurmaTurnoReferente->sql_query_file(null, "ed336_codigo", null, $sWhereTurmaTurnoReferente);
    $rsTurmaTurnoReferente         = db_query( $sSqlBuscaTurnoReferentesTurma );
    if ( !$rsTurmaTurnoReferente ) {

      db_msgbox("Erro ao buscar as referências do turno da turma.");
      $lErroTransacao = true;
    }
    $iTotalTurmaTurnoReferente = pg_num_rows( $rsTurmaTurnoReferente );

    $msg_mat = "";
    db_inicio_transacao();

    for ( $i = 0; $i < $iNumeroCandidatos; $i++ ) {

      $erro_mat       = false;
      $sSqlMatricula2 = "select fc_codetapaturma($ed60_i_turma) as etapasturma";
      $rsMatricula2   = $oDaoMatricula->sql_record( $sSqlMatricula2 );
      db_fieldsmemory( $rsMatricula2, 0 );

      $sCamposMatricula  = "ed60_i_codigo as jatem, ed47_v_nome as nometem, turma.ed57_c_descr as turmatem";
      $sCamposMatricula .= ", calendario.ed52_c_descr as caltem";
      $sWhereMatricula   = " ed60_i_aluno = {$alunos[$i]} AND turma.ed57_i_calendario = {$ed57_i_calendario}";
      $sWhereMatricula  .= "AND ed60_c_situacao != 'AVANÇADO' AND ed60_c_situacao != 'CLASSIFICADO'";
      $sSqlMatricula     = $clmatricula->sql_query( "", $sCamposMatricula, "", $sWhereMatricula );
      $result_verif      = $clmatricula->sql_record( $sSqlMatricula );

      if ( $clmatricula->numrows > 0 ) {

       db_fieldsmemory( $result_verif, 0 );
       $msg_mat .= "ATENÇÃO:\\n\\nAluno(a) {$nometem} já está matriculado(a) na turma {$turmatem} no calendário {$caltem}!\\n\\n";
       $erro_mat = true;
      } else if ( VerUltimoRegHistorico($alunos[$i],$codetapa,$etapasturma) == true && $ed233_c_consistirmat == 'S' ) {

        $msg_mat  .= $msgequiv;// $msgequiv -> variável global da função VerUltimoRegHistorico
        $erro_mat  = true;
        unset( $msgequiv );
      }

      if ( $erro_mat == false ) {

        $sCamposAlunoPossib = "ed56_i_codigo, ed79_i_codigo, ed79_c_resulant, ed79_i_turmaant";
        $sSqlAlunoPossib    = $clalunopossib->sql_query( "", $sCamposAlunoPossib, "", " ed56_i_aluno = {$alunos[$i]}");
        $result1            = $clalunopossib->sql_record( $sSqlAlunoPossib );
        db_fieldsmemory( $result1, 0 );

        $ed79_i_turmaant = $ed79_i_turmaant == "0" ? "" : $ed79_i_turmaant;
        $sSqlMatricula   = $clmatricula->sql_query_file( "", "max(ed60_i_numaluno)", "", " ed60_i_turma = {$ed60_i_turma}" );
        $result2         = $clmatricula->sql_record( $sSqlMatricula );
        db_fieldsmemory( $result2, 0 );

        $max            = $max == "" ? "" : ( $max + 1 );
        $sSqlAlunoCurso = $clalunocurso->sql_query_file( "", "ed56_c_situacao as sitanterior", "", " ed56_i_aluno = {$alunos[$i]}" );
        $result3        = $clalunocurso->sql_record( $sSqlAlunoCurso );

        $sitanterior     = pg_result( $result3, 0, 0 );
        $sitmatricula    = trim( $sitanterior ) == "CANDIDATO" ? "MATRICULAR" : "REMATRICULAR";
        $sitmatricula1   = trim( $sitanterior ) == "CANDIDATO" ? "MATRICULADO" : "REMATRICULADO";
        $tipomatricula   = trim( $sitanterior ) == "CANDIDATO" ? "N" : "R";
        $ed79_i_turmaant = $ed79_i_turmaant == "" ? "null" : $ed79_i_turmaant;

        $clmatricula->ed60_i_numaluno     = $max;
        $clmatricula->ed60_i_aluno        = $alunos[$i];
        $clmatricula->ed60_c_situacao     = "MATRICULADO";
        $clmatricula->ed60_c_concluida    = "N";
        $clmatricula->ed60_t_obs          = "";
        $clmatricula->ed60_i_turmaant     = $ed79_i_turmaant;
        $clmatricula->ed60_c_rfanterior   = $ed79_c_resulant;
        $clmatricula->ed60_d_datamodif    = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".$ed60_d_datamatricula_dia;
        $clmatricula->ed60_d_datamodifant = null;
        $clmatricula->ed60_d_datasaida    = "null";
        $clmatricula->ed60_c_ativa        = "S";
        $clmatricula->ed60_c_tipo         = $tipomatricula;
        $clmatricula->ed60_c_parecer      = "N";
        $clmatricula->ed60_matricula      = null;
        $clmatricula->ed60_i_codigo       = null;
        $clmatricula->incluir( null );

        $ultima                               = $clmatricula->ed60_i_codigo;
        $clmatriculamov->ed229_i_matricula    = $ultima;
        $clmatriculamov->ed229_i_usuario      = db_getsession("DB_id_usuario");
        $clmatriculamov->ed229_c_procedimento = "$sitmatricula ALUNO";
        $clmatriculamov->ed229_t_descr        = "ALUNO {$sitmatricula1} NA TURMA {$ed57_c_descr}. SITUAÇÃO ANTERIOR: ".trim($sitanterior);
        $clmatriculamov->ed229_d_dataevento   = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".$ed60_d_datamatricula_dia;
        $clmatriculamov->ed229_c_horaevento   = date("H:i");
        $clmatriculamov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
        $clmatriculamov->incluir( null );

        $clmatriculaserie->ed221_i_matricula = $ultima;
        $clmatriculaserie->ed221_i_serie     = $codetapa;
        $clmatriculaserie->ed221_c_origem    = "S";
        $clmatriculaserie->incluir( null );

        $clalunocurso->ed56_c_situacao   = "MATRICULADO";
        $clalunocurso->ed56_i_calendario = $ed57_i_calendario;
        $clalunocurso->ed56_i_base       = $ed57_i_base;
        $clalunocurso->ed56_i_escola     = $ed57_i_escola;
        $clalunocurso->ed56_i_codigo     = $ed56_i_codigo;
        $clalunocurso->alterar( $ed56_i_codigo );

        $clalunopossib->ed79_i_serie  = $codetapa;
        $clalunopossib->ed79_i_turno  = $ed57_i_turno;
        $clalunopossib->ed79_i_codigo = $ed79_i_codigo;
        $clalunopossib->alterar( $ed79_i_codigo );

        $sql2    = "UPDATE historico ";
        $sql2   .= "   SET ed61_i_escola = {$ed57_i_escola} ";
        $sql2   .= " WHERE ed61_i_aluno  = {$alunos[$i]} ";
        $query2  = db_query( $sql2 );


        if ( $rsTurmaTurnoReferente && $iTotalTurmaTurnoReferente > 0 ) {

          /**
           * Percorre os turnos vinculados e inclui um novo registro na tabela matriculaturnoreferente, vinculando a
           * matrícula ao turno da turma
           */
          for ( $iContador = 0; $iContador < $iTotalTurmaTurnoReferente; $iContador++ ) {

            $iTurmaTurnoReferente = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador )->ed336_codigo;

            $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $iTurmaTurnoReferente;
            $oDaoMatriculaTurnoReferente->ed337_matricula           = $ultima;
            $oDaoMatriculaTurnoReferente->incluir( null );

            if ( $oDaoMatriculaTurnoReferente->erro_status == "0" ) {

              $lErroTransacao  = true;
              $sMensagem       = "Erro ao salvar dados do vínculo da matrícula com o turno:\n";
              $sMensagem      .= $oDaoMatriculaTurnoReferente->erro_msg;
              db_msgbox( $sMensagem );
            }
          }
        }
      }
    }

    db_fim_transacao( $lErroTransacao );

    if ( $msg_mat != "" ) {
      db_msgbox( $msg_mat );
    } else {

      if ( $clmatricula->erro_status != "0" ) {
        $clmatricula->erro( true, false );
      }
    }

    db_redireciona("edu1_matricula001.php?chavepesquisa={$ed60_i_turma}");
    exit;
  }
} else if ( isset( $chavepesquisa ) ) {

  $db_botao = false;
  $sCamposTurma  = "turma.*, calendario.*, base.*, cursoedu.*, turno.*, fc_nomeetapaturma(ed57_i_codigo) as nometapa";
  $sCamposTurma .= ", fc_codetapaturma(ed57_i_codigo) as codetapa";
  $sSqlTurma     = $clturma->sql_query( "", $sCamposTurma, "", "ed57_i_codigo = {$chavepesquisa}" );
  $result        = $clturma->sql_record( $sSqlTurma );
  db_fieldsmemory( $result, 0 );

  $ed60_i_turma     = $ed57_i_codigo;
  $sWhereMatricula3 = "ed60_i_turma = {$ed60_i_turma} AND ed60_c_situacao = 'MATRICULADO'";
  $sSqlMatricula3   = $clmatricula->sql_query_file( "", " count(*) ", "", $sWhereMatricula3 );
  $result1          = $clmatricula->sql_record( $sSqlMatricula3 );
  db_fieldsmemory( $result1, 0 );

  $ed57_i_nummatr = $count;
?>
<script>
  parent.document.formaba.a2.disabled    = false;
  parent.document.formaba.a2.style.color = "black";
  (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href      = 'edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>';
                                                                +'&ed57_c_descr=<?=$ed57_c_descr?>';
                                                                +'&ed52_c_descr=<?=$ed52_c_descr?>';
</script>
<?
}
?>

<body bgcolor="#CCCCCC">
   <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
    <?include(modification("forms/db_frmmatricula.php"));?>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed60_i_turma",true,1,"ed60_i_turma",true);
</script>
<?
if ( isset( $incluir ) ) {

  if ( $clmatricula->erro_status == "0" ) {

    $clmatricula->erro( true, false );
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ( $clmatricula->erro_campo != "" ) {

      echo "<script> document.form1.".$clmatricula->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatricula->erro_campo.".focus();</script>";
    }
  }
}
?>