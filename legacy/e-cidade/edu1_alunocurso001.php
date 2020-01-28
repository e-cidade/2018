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

/* MODULO DA EDUCAÇÃO */
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification('libs/db_utils.php'));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory($_GET);
db_postmemory($_POST);

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/classes/educacao/DBViewFormularioEducacao.classe.js"></script>
    <script type="text/javascript" src="scripts/classes/educacao/escola/TurmaTurnoReferente.classe.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
<?

$oDaoAlunoCurso              = new cl_alunocurso();
$oDaoAlunoPossib             = new cl_alunopossib();
$oDaoEscola                  = new cl_escola();
$oDaoSerie                   = new cl_serie();
$oDaoSerieEquiv              = new cl_serieequiv();
$oDaoMatricula               = new cl_matricula();
$oDaoMatriculaMov            = new cl_matriculamov();
$oDaoHistoricoMps            = new cl_historicomps();
$oDaoHistorico               = new cl_historico();
$oDaoRegencia                = new cl_regencia();
$oDaoDiarioAvaliacao         = new cl_diarioavaliacao();
$oDaoTurma                   = new cl_turma();
$oDaoTurmaSerieRegimeMat     = new cl_turmaserieregimemat();
$oDaoMatriculaSerie          = new cl_matriculaserie();
$oDaoEduParametros           = new cl_edu_parametros();
$oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();
$oDaoTurmaTurnoReferente     = new cl_turmaturnoreferente();

$db_opcao      = 1;
$db_botao      = true;
$ed56_i_escola = db_getsession("DB_coddepto");
$ed18_c_nome   = db_getsession("DB_nomedepto");
$resultedu     = eduparametros(db_getsession("DB_coddepto"));

$sSqlParametros    = $oDaoEduParametros->sql_query( "", "*", "", "ed233_i_escola = {$ed56_i_escola}");
$sResultParametros = $oDaoEduParametros->sql_record( $sSqlParametros );

if ( $oDaoEduParametros->numrows > 0 ) {
  db_fieldsmemory( $sResultParametros, 0 );
}

$sCamposSqlAlunoCurso    = " ed56_i_escola as escolaatual, ed56_c_situacao as sitatual ";
$sOrderBySqlAlunoCurso   = " ed52_i_ano desc, ed56_i_codigo desc ";
$sWhereSqlAlunoCurso     = " ed56_i_aluno = {$ed56_i_aluno} ";
$sSqlAlunoCurso          = $oDaoAlunoCurso->sql_query("",
                                                      $sCamposSqlAlunoCurso,
                                                      $sOrderBySqlAlunoCurso,
                                                      $sWhereSqlAlunoCurso
                                                     );
$rsAlunoCurso            = $oDaoAlunoCurso->sql_record( $sSqlAlunoCurso );
$iLinhasAlunoCurso       = $oDaoAlunoCurso->numrows;

if ( $oDaoAlunoCurso->numrows == 0 ) {

  $lLiberaMatricula = true;
  $sitatual         = "CANDIDATO";
} else {

  db_fieldsmemory( $rsAlunoCurso, 0 );

  if ( $escolaatual != $ed56_i_escola ) {
    $lLiberaMatricula = false;
  } else if (    $sitatual == "MATRICULADO"
              || $sitatual == "TRANSFERIDO REDE"
              || $sitatual == "TRANSFERIDO FORA" ) {
    $lLiberaMatricula = false;
  } else {
    $lLiberaMatricula = true;
  }
}

if ( isset( $incluir ) ) {

  db_inicio_transacao();

  $oDaoAlunoCurso->ed56_c_situacao = "CANDIDATO";
  $oDaoAlunoCurso->incluir(null);

  $iMax = $oDaoAlunoCurso->ed56_i_codigo;
  $oDaoAlunoPossib->ed79_i_alunocurso = $iMax;
  $oDaoAlunoPossib->ed79_c_situacao   = "A";
  $oDaoAlunoPossib->incluir(null);

  $sCamposHistorico = " ed61_i_codigo as codhistorico ";
  $sWhereHistorico  = " ed61_i_aluno = {$ed56_i_aluno} ";
  $sSqlHistorico    = $oDaoHistorico->sql_query( "", $sCamposHistorico, "", $sWhereHistorico );
  $rsHistorico      = $oDaoHistorico->sql_record( $sSqlHistorico );

  for ( $iCont = 0; $iCont < $oDaoHistorico->numrows; $iCont++ ) {

    db_fieldsmemory( $rsHistorico, $iCont );
    $oDaoHistorico->ed61_i_escola = $ed56_i_escola;
    $oDaoHistorico->ed61_i_codigo = $codhistorico;
    $oDaoHistorico->alterar( $codhistorico );
  }
}

if ( isset( $alterar ) ) {

  if ( $ed56_i_calendario == "" ) {

    $oDaoAlunoCurso->erro_status = "0";
    $oDaoAlunoCurso->erro_msg    = "Campo Calendário Não Informado.";
  } else {


    $sCamposSqlMatricula = " matricula.ed60_i_codigo, turma.ed57_c_descr as turdescr, calendario.ed52_c_descr as caldescr,ed60_c_situacao ";
    $sWhereSqlMatricula  = " ed60_i_aluno = {$ed56_i_aluno} AND turma.ed57_i_calendario = {$ed56_i_calendario} ";
    $sWhereSqlMatricula .= " AND ed60_c_situacao != 'AVANÇADO' AND ed60_c_situacao != 'CLASSIFICADO' ";
    $sSqlMatricula       = $oDaoMatricula->sql_query( "", $sCamposSqlMatricula, "", $sWhereSqlMatricula );
    $rsMatricula         = $oDaoMatricula->sql_record( $sSqlMatricula );

    $lAlteraAlunoCurso   = true;
    if ($oDaoMatricula->numrows > 0) {

      db_fieldsmemory($rsMatricula, 0);
      $oDadosMatricula = db_utils::fieldsMemory($rsMatricula, 0);

      $oCalendario            = CalendarioRepository::getCalendarioByCodigo($ed56_i_calendario);
      $sWhereUltimaMatricula  = " ed60_i_codigo > {$oDadosMatricula->ed60_i_codigo} ";
      $sWhereUltimaMatricula .= " and ed52_i_ano = {$oCalendario->getAnoExecucao()} ";
      $sWhereUltimaMatricula .= " and ed60_i_aluno = {$ed56_i_aluno} ";
      $sSqlUltimaMatricula    = $oDaoMatricula->sql_query_matriculaanual( "", '1', "", $sWhereUltimaMatricula );
      $rsUltimaMatricula      = $oDaoMatricula->sql_record( $sSqlUltimaMatricula );
      /**
       * Só devemos validar se aluno não possuir uma matrícula posterior
       */
      if ($oDaoMatricula->numrows == 0) {

        $lAlteraAlunoCurso = false;
        if (trim($ed60_c_situacao) == "TRANSFERIDO FORA") {

          db_msgbox("ATENÇÃO! Aluno(a) ".trim($ed47_v_nome)." já possui matrícula\\nno calendário ".trim($caldescr).
                    ", na turma ".trim($turdescr)." com situação de ".trim($ed60_c_situacao).
                    ".\\nPara reativar esta matrícula acesse:\\n  Procedimentos -> Transferências -> ".
                    "Matricular Alunos Transferidos (FORA)");
          ?>

            <script>parent.location.href = "edu1_matriculatransffora001.php";</script>;
          <?
          exit;

        } else {
          db_msgbox("ATENÇÃO! Aluno(a) ".trim($ed47_v_nome)." já possui matrícula\\nno calendário ".trim($caldescr).
                    ", na turma ".trim($turdescr)." com situação de ".trim($ed60_c_situacao).
                    ".\\nPara reativar esta matrícula acesse:\\n  Procedimentos -> Matrículas -> ".
                    "Alterar Situação da Matrícula");
        }
      }
    }

    if ( $lAlteraAlunoCurso ) {

      db_inicio_transacao();

      $db_opcao                        = 2;
      $oDaoAlunoCurso->ed56_c_situacao = "CANDIDATO";
      $oDaoAlunoCurso->alterar($ed56_i_codigo);

      $oDaoAlunoPossib->ed79_i_alunocurso = $ed56_i_codigo;
      $oDaoAlunoPossib->ed79_c_situacao   = "A";

      if ($ed79_i_codigo == "") {
        $oDaoAlunoPossib->incluir(@$ed79_i_codigo);
      } else {
        $oDaoAlunoPossib->alterar(@$ed79_i_codigo);
      }

      $sCamposSqlHistorico = " ed61_i_codigo as codhistorico ";
      $sWhereSqlHistorico  = " ed61_i_aluno = {$ed56_i_aluno} ";
      $sSqlHistorico       = $oDaoHistorico->sql_query("", $sCamposSqlHistorico, "", $sWhereSqlHistorico);
      $rsHistorico         = $oDaoHistorico->sql_record($sSqlHistorico);

      for ($iCont =0; $iCont < $oDaoHistorico->numrows; $iCont++) {

        db_fieldsmemory($rsHistorico, $iCont);
        $oDaoHistorico->ed61_i_escola = $ed56_i_escola;
        $oDaoHistorico->ed61_i_codigo = $codhistorico;
        $oDaoHistorico->alterar($codhistorico);
      } //Termina FOR
    }
  }
}

if (isset($excluir)) {

  db_inicio_transacao();

  $db_opcao = 3;
  $oDaoAlunoPossib->excluir("", " ed79_i_alunocurso = {$ed56_i_codigo}");
  $oDaoAlunoCurso->excluir($ed56_i_codigo);

  db_fim_transacao();
}

if (isset($incluirmatricula)) {

  $lErroTransacao = false;

  $sCamposSqlMatricula  = " ed60_i_codigo as jatem,ed47_v_nome as nometem,turma.ed57_c_descr as turmatem, ";
  $sCamposSqlMatricula .= " calendario.ed52_c_descr as caltem,ed60_c_situacao as sitmatricula";
  $sWhereSqlMatricula   = " ed60_i_aluno = {$ed56_i_aluno} and turma.ed57_i_calendario = {$ed57_i_calendario} ";
  $sWhereSqlMatricula  .= " and ed60_c_situacao != 'AVANÇADO' AND ed60_c_situacao != 'CLASSIFICADO' ";
  $sSqlMatricula        = $oDaoMatricula->sql_query("", $sCamposSqlMatricula,"", $sWhereSqlMatricula);
  $rsMatricula          = $oDaoMatricula->sql_record($sSqlMatricula);
  $lErroMat             = false;

  $lAlteraAlunoCurso   = true;
  if ($oDaoMatricula->numrows > 0) {

    db_fieldsmemory($rsMatricula, 0);
    $oDadosMatricula = db_utils::fieldsMemory($rsMatricula, 0);

    $oCalendario            = CalendarioRepository::getCalendarioByCodigo($ed57_i_calendario);
    $sWhereUltimaMatricula  = " ed60_i_codigo > {$oDadosMatricula->jatem} ";
    $sWhereUltimaMatricula .= " and ed52_i_ano = {$oCalendario->getAnoExecucao()} ";
    $sWhereUltimaMatricula .= " and ed60_i_aluno = {$ed56_i_aluno} ";
    $sSqlUltimaMatricula    = $oDaoMatricula->sql_query_matriculaanual( "", '1', "", $sWhereUltimaMatricula );
    $rsUltimaMatricula      = $oDaoMatricula->sql_record( $sSqlUltimaMatricula );
    /**
     * Só devemos validar se aluno não possuir uma matrícula posterior
     */
    if ($oDaoMatricula->numrows == 0) {

      $lAlteraAlunoCurso = true;
      if (trim($sitmatricula) == "TRANSFERIDO FORA") {

        db_msgbox("Aluno(a) ".trim($nometem)." já possui matrícula na turma $turmatem no calendário $caltem,\\ncom".
                  " situação de $sitmatricula!\\nPara reativar esta matrícula acesse:\\n  Procedimentos -> ".
                  "Transferências -> Matricular Alunos Transferidos (FORA)");

        ?>

          <script>parent.location.href = "edu1_matriculatransffora001.php";</script>;

        <?
        exit;

      } else {

        db_msgbox("Aluno(a) ".trim($nometem)." já possui matrícula na turma $turmatem no calendário $caltem,\\ncom".
                  " situação de $sitmatricula!\\nPara reativar esta matrícula acesse:\\n  Procedimentos -> ".
                  "Matrículas -> Alterar Situação da Matrícula");
        db_redireciona("edu1_alunocurso001.php?ed56_i_aluno=$ed56_i_aluno&ed47_v_nome=$ed47_v_nome");
        exit;
      }
    }

    $lErroMat = true;
  }

  if ($lAlteraAlunoCurso) {

    $sSqlMatricula2        = " select fc_codetapaturma($ed60_i_turma) as etapasturma";
    $rsMatricula2          = $oDaoMatricula->sql_record($sSqlMatricula2);
    db_fieldsmemory($rsMatricula2, 0);
    $errohist = false;
    $oParams  = loadConfig('edu_parametros', ' ed233_i_escola = '.$ed56_i_escola);

    if ($oParams == null) {
      $oParams->ed233_c_consistirmat = 'N';
    }

    if (
            $oParams->ed233_c_consistirmat == 'S'
         && VerUltimoRegHistorico($ed56_i_aluno, $codetapaturma,$etapasturma) == true
         && $oParams->ed233_reclassificaetapaanterior == 'f'
       ) {

      db_msgbox($msgequiv);// $msgequiv -> variável global da função VerUltimoRegHistorico
      db_redireciona("edu1_alunocurso001.php?ed56_i_aluno=$ed56_i_aluno&ed47_v_nome=$ed47_v_nome");
      exit;
    }

    if ($errohist == false) {

      db_inicio_transacao();

      $lTrocaModalidade    = false;
      $sCamposAlunoPossib  = " ed79_i_codigo as codalunopossib,ed56_i_codigo as codalunocurso, ";
      $sCamposAlunoPossib .= " ed56_c_situacao as sitanterior,ed79_c_resulant,ed79_i_turmaant, ed56_i_base ";
      $sWhereAlunoPossib   = " ed56_i_aluno = $ed56_i_aluno ";
      $sSqlAlunoPossib     = $oDaoAlunoPossib->sql_query("", $sCamposAlunoPossib,"", $sWhereAlunoPossib);
      $rsAlunoPossib       = $oDaoAlunoPossib->sql_record($sSqlAlunoPossib);

      if ($oDaoAlunoPossib->numrows == 0) {

        $oDaoAlunoCurso->ed56_i_escola        = $ed56_i_escola;
        $oDaoAlunoCurso->ed56_i_aluno         = $ed56_i_aluno;
        $oDaoAlunoCurso->ed56_i_base          = $ed57_i_base;
        $oDaoAlunoCurso->ed56_i_calendario    = $ed57_i_calendario;
        $oDaoAlunoCurso->ed56_c_situacao      = "MATRICULADO";
        $oDaoAlunoCurso->ed56_i_baseant       = null;
        $oDaoAlunoCurso->ed56_i_calendarioant = null;
        $oDaoAlunoCurso->ed56_c_situacaoant   = "";
        $oDaoAlunoCurso->incluir(null);

        $oDaoAlunoPossib->ed79_i_alunocurso = $oDaoAlunoCurso->ed56_i_codigo;
        $oDaoAlunoPossib->ed79_i_serie      = $codetapaturma;
        $oDaoAlunoPossib->ed79_i_turno      = $ed57_i_turno;
        $oDaoAlunoPossib->ed79_i_turmaant   = null;
        $oDaoAlunoPossib->ed79_c_resulant   = "";
        $oDaoAlunoPossib->ed79_c_situacao   = "A";
        $oDaoAlunoPossib->incluir(null);

        $ed79_c_resulant = "";
        $ed79_i_turmaant = null;
        $sitanterior     = "CANDIDATO";
      } else {

        db_fieldsmemory($rsAlunoPossib, 0);
        $ed79_i_turmaant   = $ed79_i_turmaant == "0" ? "" : $ed79_i_turmaant;

        $oDaoAlunoCurso->ed56_c_situacao   = "MATRICULADO";
        $oDaoAlunoCurso->ed56_i_calendario = $ed57_i_calendario;
        $oDaoAlunoCurso->ed56_i_base       = $ed57_i_base;
        $oDaoAlunoCurso->ed56_i_escola     = $ed56_i_escola;
        $oDaoAlunoCurso->ed56_i_codigo     = $codalunocurso;
        $oDaoAlunoCurso->alterar($codalunocurso);

        $oDaoAlunoPossib->ed79_i_serie  = $codetapaturma;
        $oDaoAlunoPossib->ed79_i_turno  = $ed57_i_turno;
        $oDaoAlunoPossib->ed79_i_codigo = $codalunopossib;
        $oDaoAlunoPossib->alterar($codalunopossib);

        $oBase = BaseCurricularRepository::getBaseCurricularByCodigo( $ed56_i_base );

        if ( $oBase->getCurso()->getCodigo() != $ed31_i_curso ) {
          $lTrocaModalidade = true;
        }
      }

      $sql2    = "UPDATE historico";
      $sql2   .= "   SET ed61_i_escola = {$ed56_i_escola} ";
      $sql2   .= " WHERE ed61_i_aluno = {$ed56_i_aluno} ";
      $query2  = db_query($sql2);

      $sCamposMax     = " max(ed60_i_numaluno) ";
      $sWhereMax      = " ed60_i_turma = {$ed60_i_turma} ";
      $sSqlMax        = $oDaoMatricula->sql_query_file("" , $sCamposMax, "", $sWhereMax);
      $rsMaxMatricula = $oDaoMatricula->sql_record($sSqlMax);

      db_fieldsmemory($rsMaxMatricula, 0);
      $max = $max == "" ? "" : ($max+1);

      $ed79_i_turmaant                     = $ed79_i_turmaant == "" ? "null" : $ed79_i_turmaant;
      $tipomatricula                       = trim($sitanterior) == "CANDIDATO" || $lTrocaModalidade ? "N" : "R";
      $oDaoMatricula->ed60_i_aluno         = $ed56_i_aluno;
      $oDaoMatricula->ed60_i_turma         = $ed60_i_turma;
      $oDaoMatricula->ed60_i_numaluno      = $max;
      $oDaoMatricula->ed60_c_situacao      = "MATRICULADO";
      $oDaoMatricula->ed60_c_concluida     = "N";
      $oDaoMatricula->ed60_i_turmaant      = $ed79_i_turmaant;
      $oDaoMatricula->ed60_c_rfanterior    = $ed79_c_resulant;
      $oDaoMatricula->ed60_d_datamatricula = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".
                                             $ed60_d_datamatricula_dia;
      $oDaoMatricula->ed60_d_datamodif     = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".
                                             $ed60_d_datamatricula_dia;
      $oDaoMatricula->ed60_d_datamodifant  = null;
      $oDaoMatricula->ed60_d_datasaida     = "null";
      $oDaoMatricula->ed60_t_obs           = "";
      $oDaoMatricula->ed60_c_ativa         = "S";
      $oDaoMatricula->ed60_c_tipo          = $tipomatricula;
      $oDaoMatricula->ed60_c_parecer       = "N";
      $oDaoMatricula->ed60_tipoingresso    = $ed334_tipo;
      $oDaoMatricula->incluir(null);

      $sitmatricula  = trim($sitanterior) == "CANDIDATO" ? "MATRICULAR" : "REMATRICULAR";
      $sitmatricula1 = trim($sitanterior) == "CANDIDATO" ? "MATRICULADO" : "REMATRICULADO";

      $iUltimaMatricula                       = $oDaoMatricula->ed60_i_codigo;

      $oDaoMatriculaMov->ed229_i_matricula    = $iUltimaMatricula;
      $oDaoMatriculaMov->ed229_i_usuario      = db_getsession("DB_id_usuario");
      $oDaoMatriculaMov->ed229_c_procedimento = "$sitmatricula ALUNO";
      $oDaoMatriculaMov->ed229_t_descr        = "ALUNO $sitmatricula1 NA TURMA ".trim($ed57_c_descr).
                                              ". SITUAÇÃO ANTERIOR: ".trim($sitanterior);
      $oDaoMatriculaMov->ed229_d_dataevento   = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes."-".
                                              $ed60_d_datamatricula_dia;
      $oDaoMatriculaMov->ed229_c_horaevento   = date("H:i");
      $oDaoMatriculaMov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
      $oDaoMatriculaMov->incluir(null);

      $sCamposSql = " ed223_i_serie ";
      $sWhereSql  = " ed220_i_turma = {$ed60_i_turma} ";
      $sSql       = $oDaoTurmaSerieRegimeMat->sql_query("" , $sCamposSql, "", $sWhereSql);
      $rsEtapa    = $oDaoTurmaSerieRegimeMat->sql_record($sSql);

      for ($iCont = 0; $iCont < $oDaoTurmaSerieRegimeMat->numrows; $iCont++) {

        $oDadosTSRM = db_utils::fieldsmemory($rsEtapa, $iCont);

        if ($codetapaturma == $oDadosTSRM->ed223_i_serie) {
          $sOrigem = "S";
        } else {
          $sOrigem = "N";
        }

        $oDaoMatriculaSerie->ed221_i_matricula = $iUltimaMatricula;
        $oDaoMatriculaSerie->ed221_i_serie     = $oDadosTSRM->ed223_i_serie;
        $oDaoMatriculaSerie->ed221_c_origem    = $sOrigem;
        $oDaoMatriculaSerie->incluir(null);
      }

      /**
       * Busca os turnos referentes vinculados a turma
       */
      $aTurnosReferente = array();
      if ( isset($check_turno1) ) {
        $aTurnosReferente[] = $check_turno1;
      }
      if ( isset($check_turno2) ) {
        $aTurnosReferente[] = $check_turno2;
      }
      if ( isset($check_turno3) ) {
        $aTurnosReferente[] = $check_turno3;
      }

      if ( count($aTurnosReferente) == 0)  {

        $lErroTransacao  = true;
        $sMensagem       = "Não foi informado o turno para matricula.";
        db_msgbox( $sMensagem );
      }

      $sWhereTurmaTurnoReferente  = "     ed336_turma = {$ed60_i_turma}";
      $sWhereTurmaTurnoReferente .= " and ed336_turnoreferente in (" .implode(", ", $aTurnosReferente). ")";
      $sSqlTurmaTurnoReferente    = $oDaoTurmaTurnoReferente->sql_query_file(null,
                                                                             "ed336_codigo",
                                                                             null,
                                                                             $sWhereTurmaTurnoReferente
                                                                            );
      $rsTurmaTurnoReferente     = db_query( $sSqlTurmaTurnoReferente );
      $iTotalTurmaTurnoReferente = pg_num_rows( $rsTurmaTurnoReferente );

      if ( $rsTurmaTurnoReferente && $iTotalTurmaTurnoReferente > 0 ) {

        /**
         * Percorre os turnos vinculados e inclui um novo registro na tabela matriculaturnoreferente, vinculando a
         * matrícula ao turno da turma
         */
        for ( $iContador = 0; $iContador < $iTotalTurmaTurnoReferente; $iContador++ ) {

          $iTurmaTurnoReferente = db_utils::fieldsMemory( $rsTurmaTurnoReferente, $iContador )->ed336_codigo;

          $oDaoMatriculaTurnoReferente->ed337_turmaturnoreferente = $iTurmaTurnoReferente;
          $oDaoMatriculaTurnoReferente->ed337_matricula           = $iUltimaMatricula;
          $oDaoMatriculaTurnoReferente->incluir( null );

          if ( $oDaoMatriculaTurnoReferente->erro_status == "0" ) {

            $lErroTransacao  = true;
            $sMensagem       = "Erro ao salvar dados do vínculo da matrícula com o turno:\n";
            $sMensagem      .= $oDaoMatriculaTurnoReferente->erro_msg;
            db_msgbox( $sMensagem );
          }
        }
      }

      db_fim_transacao( $lErroTransacao );

      $lProgressaoAtiva = false;

      $oAluno         = AlunoRepository::getAlunoByCodigo($ed56_i_aluno);
      $sMsgProgressao = "Aluno ". $oAluno->getNome() ." possui as seguintes dependências:\n";
      $sMsgPadrao     = "Matrícula efetuada com sucesso!";

      if ($lErroTransacao) {
        $sMsgPadrao = "";
      }

      foreach ($oAluno->getProgressaoParcial() as $oProgressaoParcial) {

        if ($oProgressaoParcial->isAtiva()) {

          $sMsgProgressao  .= "Etapa: " . $oProgressaoParcial->getEtapa()->getNome();
          $sMsgProgressao  .= " - Disciplina: " . $oProgressaoParcial->getDisciplina()->getNomeDisciplina() . ".";
          $sMsgProgressao  .= " - Ensino: " . $oProgressaoParcial->getEtapa()->getEnsino()->getNome() . ".\n";
          $lProgressaoAtiva = true;
        }
      }

      if ($lProgressaoAtiva) {

        $sMsgProgressao .= "\nAcesse: \n";
        $sMsgProgressao .= "Matrícula > Progressão Parcial > Ativar / Desativar: para desativar a progressão parcial.\n";
        $sMsgProgressao .= "Matrícula > Progressão Parcial > Vincular Aluno / Turma: para vincular a progressão do aluno em uma turma";
        $sMsgPadrao     .= "\n{$sMsgProgressao}";
      }
      if ( !empty($sMsgPadrao) ) {
        db_msgbox($sMsgPadrao);
      }

      if (isset($importaaprov) && $importaaprov == "S") {
        db_redireciona("edu1_alunocurso002.php?ed56_i_aluno=$ed56_i_aluno&ed47_v_nome=$ed47_v_nome&desabilita".
                       "&matricula=$matricula&turmaorigem=$turmaorigem&turmadestino=$ed60_i_turma");
      } else {
        db_redireciona("edu1_alunocurso001.php?ed56_i_aluno=$ed56_i_aluno&ed47_v_nome=$ed47_v_nome&desabilita");
      }

      exit;
    }
  }
}

?>

  <body bgcolor="#CCCCCC">
  <div class="container">
    <fieldset>
      <? if ($lLiberaMatricula == true) { ?>

        <legend>
          <select id="escolha" name="escolha" style="font-weight:bold;font-size:9px;"
                  onchange="js_escolha(this.value);">
            <option value="C">Cursos do Aluno</option>
            <option value="M">Matricular Aluno</option>
          </select>
        </legend>
        <?include(modification("forms/db_frmalunocursomatr.php"));?>
        <?include(modification("forms/db_frmalunocurso.php"));?>

        <? if ($sitatual == "CANDIDATO" && !isset($opcao) && !isset($incluir)) { ?>
          <script>
            $('escolha').value                = "M";
            $('alunomatricula').style.display = "";
            $('alunocurso').style.display     = "none";
          </script>
        <? } else { ?>
          <script>
            $('escolha').value                = "C";
            $('alunomatricula').style.display = "none";
            $('alunocurso').style.display = "";
          </script>
        <? } ?>

      <? } else { ?>
        <legend><b>Cursos do Aluno</b></legend>
        <?include(modification("forms/db_frmalunocurso.php"));?>
      <? } ?>
    </fieldset>
    </div>
  </body>
</html>

<script>

function js_escolha(valor) {

  if (valor == "C") {

    $('alunomatricula').style.display = "none";
    $('alunocurso').style.display     = "";
  } else {

    $('alunomatricula').style.display = "";
    $('alunocurso').style.display     = "none";
  }
}
</script>

<?

if (isset($incluir)) {

  $temerro = false;

  if ($oDaoAlunoCurso->erro_status == "0") {

    $oDaoAlunoCurso->erro(true, false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoAlunoCurso->erro_campo != "") {

      echo "<script> document.form1.".$oDaoAlunoCurso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAlunoCurso->erro_campo.".focus();</script>";
    }

    $temerro = true;
  }

  if ($oDaoAlunoPossib->erro_status == "0") {

    $oDaoAlunoPossib->erro(true, false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoAlunoPossib->erro_campo != "") {

      echo "<script> document.form1.".$oDaoAlunoPossib->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAlunoPossib->erro_campo.".focus();</script>";
    }

    $temerro = true;
  }

  if ($temerro == true) {
    db_fim_transacao($temerro);
  } else {

    db_fim_transacao();
    $oDaoAlunoCurso->erro(true, true);
  }
}

if (isset($alterar)) {

  $temerro = false;

  if ($oDaoAlunoCurso->erro_status == "0") {

    $oDaoAlunoCurso->erro(true, false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoAlunoCurso->erro_campo != "") {

      echo "<script> document.form1.".$oDaoAlunoCurso->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAlunoCurso->erro_campo.".focus();</script>";
    }

    $temerro = true;
  }

  if ($oDaoAlunoPossib->erro_status == "0") {

    $oDaoAlunoPossib->erro(true, false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if($oDaoAlunoPossib->erro_campo != "") {

      echo "<script> document.form1.".$oDaoAlunoPossib->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoAlunoPossib->erro_campo.".focus();</script>";
    }

    $temerro = true;
  }

  if ($temerro == true) {
    db_fim_transacao($temerro);
  } else {
    db_fim_transacao();
  ?>

  <script>
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a1.location.href='edu1_alunodados002.php?chavepesquisa=<?=$ed56_i_aluno?>';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href='edu1_aluno002.php?chavepesquisa=<?=$ed56_i_aluno?>';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a4.location.href='edu1_docaluno001.php?ed49_i_aluno=<?=$ed56_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a5.location.href='edu1_alunonecessidade001.php?ed214_i_aluno=<?=$ed56_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>';
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a6.location.href='edu1_historico000.php?ed61_i_aluno=<?=$ed56_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>';
  </script>
  <?

  $oDaoAlunoCurso->erro(true, true);
  }
}

if (isset($excluir)) {

  if($oDaoAlunoCurso->erro_status == "0") {
    $oDaoAlunoCurso->erro(true,false);
  } else {
    $oDaoAlunoCurso->erro(true,true);
  }
}

if (isset($cancelar)) {
  echo "<script>location.href='".$oDaoAlunoCurso->pagina_retorno."'</script>";
}

if (isset($incluirmatricula)) {

  if ($lErroMat == true) {
  ?>
    <script>
      js_OpenJanelaIframe('','db_iframe_movimentos',
                          'edu1_matricula005.php?matricula=<?=$jatem?>',
                          'Movimentação da Matrícula',true);
      $('incluirmatricula').disabled    = false;
      $('escolha').value                = "M";
      $('alunomatricula').style.display = "";
      $('alunocurso').style.display     = "none";
      $('ed31_i_curso').value           = "";
      $('ed29_c_descr').value           = "";
      $('ed31_c_descr').value           = "";
      $('ed52_c_descr').value           = "";
      $('ed11_c_descr').value           = "";
      $('ed15_c_nome').value            = "";
    </script>
  <?
 }
}

if (isset($ed60_i_turma) && $ed60_i_turma != "") {
 ?>
  <script>
    $('escolha').value                = "M";
    $('alunomatricula').style.display = "";
    $('alunocurso').style.display     = "none";
    $('ed31_i_curso').value           = "";
    $('ed29_c_descr').value           = "";
    $('ed31_c_descr').value           = "";
    $('ed52_c_descr').value           = "";
    $('ed11_c_descr').value           = "";
    $('ed15_c_nome').value            = "";
  </script>
 <?
}
?>