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

require_once("libs/db_stdlib.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");

db_postmemory($HTTP_POST_VARS);
$clalunocurso          = new cl_alunocurso;
$clalunopossib         = new cl_alunopossib;
$clserie               = new cl_serie;
$clmatricula           = new cl_matricula;
$clmatriculamov        = new cl_matriculamov;
$clhistoricomps        = new cl_historicomps;
$clregencia            = new cl_regencia;
$cldiarioavaliacao     = new cl_diarioavaliacao;
$cllogmatricula        = new cl_logmatricula;
$clmatriculaserie      = new cl_matriculaserie;
$clturmaserieregimemat = new cl_turmaserieregimemat;
$clserieequiv          = new cl_serieequiv;
$cledu_parametros      = new cl_edu_parametros;
$oDaoMatricula         = new cl_matricula;
$clrotulo              = new rotulocampo;

$db_opcao      = 1;
$db_botao      = true;
$ed56_i_escola = db_getsession("DB_coddepto");
$escola        = db_getsession("DB_coddepto");
$ed18_c_nome   = db_getsession("DB_nomedepto");
$resultedu     = eduparametros(db_getsession("DB_coddepto"));

$clrotulo->label("ed56_i_aluno");
$clrotulo->label("ed56_i_escola");
$clrotulo->label("ed60_i_turma");
$clrotulo->label("ed31_i_curso");
$clrotulo->label("ed57_i_base");
$clrotulo->label("ed57_i_calendario");
$clrotulo->label("ed223_i_serie");
$clrotulo->label("ed57_i_turno");
$clrotulo->label("ed60_d_datamatricula");
$clrotulo->label("ed57_i_nummatr");
$clrotulo->label("ed57_i_numvagas");
$clrotulo->label("ed334_tipo");

if (!isset($ano_matr)) {
  $ano_matr = date("Y");
}

$sSqlParametros    = $cledu_parametros->sql_query( "", "*", "", "ed233_i_escola = {$escola}" );
$sResultParametros = $cledu_parametros->sql_record( $sSqlParametros );

if ( $cledu_parametros->numrows > 0 ) {
  db_fieldsmemory( $sResultParametros, 0 );
}

if ( isset( $incluirmatricula ) ) {

  $sCampos          = "ed60_i_codigo as jatem,ed47_v_nome as nometem,turma.ed57_c_descr as turmatem, ";
  $sCampos         .= " calendario.ed52_c_descr as caltem,ed60_c_situacao as sitmatricula";
  $sWhere           = " ed60_i_aluno = {$ed56_i_aluno} AND turma.ed57_i_calendario = {$ed57_i_calendario}";
  $sSqlMatricula    = $clmatricula->sql_query( "", $sCampos, "", $sWhere );
  $sResultMatricula = $clmatricula->sql_record( $sSqlMatricula );

  $sSqlMatricula2 = "select fc_codetapaturma({$ed60_i_turma}) as etapasturma";
  $rsMatricula2   = $oDaoMatricula->sql_record( $sSqlMatricula2 );
  db_fieldsmemory( $rsMatricula2, 0 );

  if (
           VerUltimoRegHistorico( $ed56_i_aluno, $codetapaturma, $etapasturma ) == true
        && $ed233_c_consistirmat == 'S'
        && $ed233_reclassificaetapaanterior == 'f'
     ) {
    db_msgbox($msgequiv);// $msgequiv -> variável global da função VerUltimoRegHistorico
  } else {

    //db_query("begin");
    db_inicio_transacao();
    $sCampos            = " ed79_i_codigo as codalunopossib,ed56_i_codigo as codalunocurso, ";
    $sCampos           .= " ed56_c_situacao as sitanterior,ed79_c_resulant,ed79_i_turmaant ";
    $sSqlAlunoPossib    = $clalunopossib->sql_query( "", $sCampos, "", " ed56_i_aluno = {$ed56_i_aluno}" );
    $sResultAlunoPossib = $clalunopossib->sql_record( $sSqlAlunoPossib );

    if ( $clalunopossib->numrows == 0 ) {

      $clalunocurso->ed56_i_escola        = $ed56_i_escola;
      $clalunocurso->ed56_i_aluno         = $ed56_i_aluno;
      $clalunocurso->ed56_i_base          = $ed57_i_base;
      $clalunocurso->ed56_i_calendario    = $ed57_i_calendario;
      $clalunocurso->ed56_c_situacao      = "MATRICULADO";
      $clalunocurso->ed56_i_baseant       = null;
      $clalunocurso->ed56_i_calendarioant = null;
      $clalunocurso->ed56_c_situacaoant   = "";
      $clalunocurso->incluir(null);

      $ultimo                           = $clalunocurso->ed56_i_codigo;
      $clalunopossib->ed79_i_alunocurso = $ultimo;
      $clalunopossib->ed79_i_serie      = $codetapaturma;
      $clalunopossib->ed79_i_turno      = $ed57_i_turno;
      $clalunopossib->ed79_i_turmaant   = null;
      $clalunopossib->ed79_c_resulant   = "";
      $clalunopossib->ed79_c_situacao   = "A";
      $clalunopossib->incluir(null);

      $ed79_c_resulant = "";
      $ed79_i_turmaant = null;
      $sitanterior     = "CANDIDATO";

    } else {

      db_fieldsmemory( $sResultAlunoPossib, 0 );
      $ed79_i_turmaant = $ed79_i_turmaant == "0" ? "" : $ed79_i_turmaant;

      $sql1            = " UPDATE alunocurso SET ";
      $sql1           .= "        ed56_c_situacao   = 'MATRICULADO', ";
      $sql1           .= "        ed56_i_calendario = {$ed57_i_calendario}, ";
      $sql1           .= "        ed56_i_base       = {$ed57_i_base}, ";
      $sql1           .= "        ed56_i_escola     = {$ed56_i_escola} ";
      $sql1           .= "  WHERE ed56_i_codigo = {$codalunocurso} ";
      $query1          = db_query( $sql1 );

      $sql11           = " UPDATE alunopossib SET ";
      $sql11          .= "        ed79_i_serie  = {$codetapaturma}, ";
      $sql11          .= "        ed79_i_turno  = {$ed57_i_turno} ";
      $sql11          .= "  WHERE ed79_i_codigo = {$codalunopossib} ";
      $query11 = db_query( $sql11 );

    }

    $sql2    = " UPDATE historico SET ";
    $sql2   .= "        ed61_i_escola = {$ed56_i_escola} ";
    $sql2   .= " WHERE ed61_i_aluno = {$ed56_i_aluno} ";
    $query2  = db_query( $sql2 );

    $sSqlMat = $clmatricula->sql_query_file( "", "max(ed60_i_numaluno)", "", " ed60_i_turma = {$ed60_i_turma}" );
    $result2 = $clmatricula->sql_record( $sSqlMat );
    db_fieldsmemory( $result2, 0 );

    $max                               = $max == ""?"":($max+1);
    $ed79_i_turmaant                   = $ed79_i_turmaant == ""?"null":$ed79_i_turmaant;
    $ed60_i_codigo                     = "";
    $clmatricula->ed60_i_aluno         = $ed56_i_aluno;
    $clmatricula->ed60_i_turma         = $ed60_i_turma;
    $clmatricula->ed60_i_numaluno      = $max;
    $clmatricula->ed60_c_situacao      = "MATRICULADO";
    $clmatricula->ed60_c_concluida     = "N";
    $clmatricula->ed60_i_turmaant      = $ed79_i_turmaant;
    $clmatricula->ed60_c_rfanterior    = $ed79_c_resulant;
    $sDataMatricula                    = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes;
    $sDataMatricula                   .= "-".$ed60_d_datamatricula_dia;
    $clmatricula->ed60_d_datamatricula = $sDataMatricula;
    $sDataModif                        = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes;
    $sDataModif                       .= "-".$ed60_d_datamatricula_dia;
    $clmatricula->ed60_d_datamodif     = $sDataModif;
    $clmatricula->ed60_d_datamodifant  = null;
    $clmatricula->ed60_d_saida         = null;
    $clmatricula->ed60_t_obs           = "";
    $clmatricula->ed60_c_ativa         = "S";
    $clmatricula->ed60_c_tipo          = "N";
    $clmatricula->ed60_c_parecer       = "N";
    $clmatricula->ed60_tipoingresso    = $ed334_tipo;
    $clmatricula->incluir(null);

    $ultima                               = $clmatricula->ed60_i_codigo;
    $clmatriculamov->ed229_i_matricula    = $ultima;
    $clmatriculamov->ed229_i_usuario      = db_getsession("DB_id_usuario");
    $clmatriculamov->ed229_c_procedimento = "MATRICULAR ALUNO";
    $sDescr                               = "ALUNO MATRICULADO NA TURMA ".trim($ed57_c_descr);
    $sDescr                              .= ". SITUAÇÃO ANTERIOR: ".trim($sitanterior);
    $clmatriculamov->ed229_t_descr        = $sDescr;
    $sDataEvento                          = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes;
    $sDataEvento                         .= "-".$ed60_d_datamatricula_dia;
    $clmatriculamov->ed229_d_dataevento   = $sDataEvento;
    $clmatriculamov->ed229_c_horaevento   = date("H:i");
    $clmatriculamov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
    $clmatriculamov->incluir(null);

    $sSqlTurmaSerieRegimeMat    = $clturmaserieregimemat->sql_query( "", "ed223_i_serie", "", " ed220_i_turma = {$ed60_i_turma}" );
    $sResultTurmaSerieRegimeMat = $clturmaserieregimemat->sql_record( $sSqlTurmaSerieRegimeMat );

    for ( $r = 0; $r < $clturmaserieregimemat->numrows; $r++ ) {

      db_fieldsmemory( $sResultTurmaSerieRegimeMat, $r );

      if ( $codetapaturma == $ed223_i_serie ) {
        $origem = "S";
      } else {
        $origem = "N";
      }

      $clmatriculaserie->ed221_i_matricula = $ultima;
      $clmatriculaserie->ed221_i_serie     = $ed223_i_serie;
      $clmatriculaserie->ed221_c_origem    = $origem;
      $clmatriculaserie->incluir(null);
    }

    $sWhereMatri  = " ed60_i_turma = {$ed60_i_turma} AND ed60_c_situacao = 'MATRICULADO'";
    $sSqlMatri    = $clmatricula->sql_query_file( "", " count(*) as qtdmatricula", "", $sWhereMatri );
    $sResultMatri = $clmatricula->sql_record( $sSqlMatri );
    db_fieldsmemory( $sResultMatri, 0 );

    $qtdmatricula = $qtdmatricula == "" ? 0 : $qtdmatricula;
    $sql1         = " UPDATE turma SET ";
    $sql1        .= "        ed57_i_nummatr = {$qtdmatricula} ";
    $sql1        .= "  WHERE ed57_i_codigo = {$ed60_i_turma} ";
    $query1       = db_query( $sql1 );

    db_fim_transacao();

    /**
     * Verifica se o aluno possui progressão ativa na escola que está se transferindo
     */
    $lTemProgressaoParcial = false;
    $oAluno                = AlunoRepository::getAlunoByCodigo( $ed56_i_aluno );
    $sMensagem             = "O(A) aluno(a) possui a(s) seguinte(s) dependência(s):\n";

    foreach ( $oAluno->getProgressaoParcial() as $oProgressaoParcial ) {

      /**
       * Caso a progressão não esteja ativa, ou esteja concluída ou não seja da escola logada, não valida a progressão
       */
      if (    !$oProgressaoParcial->isAtiva()
           || $oProgressaoParcial->isConcluida()
           || $oProgressaoParcial->getEscola()->getCodigo() != $escola ) {
        continue;
      }

      $sMensagem .= " Etapa: {$oProgressaoParcial->getEtapa()->getNome()}";
      $sMensagem .= " - Disciplina: {$oProgressaoParcial->getDisciplina()->getNomeDisciplina()}";
      $sMensagem .= " - Ensino: {$oProgressaoParcial->getEtapa()->getEnsino()->getNome()};\n";

      $lTemProgressaoParcial = true;
    }

    $sMensagem .= "Acesse:\n";
    $sMensagem .= " Matrícula > Progressão Parcial > Ativar / Inativar: para alterar a situação da progressão parcial;\n";
    $sMensagem .= " Matrícula > Progressão Parcial > Vincular Aluno / Turma: para vincular a progressão do aluno em uma turma;";

    /**
     * Caso tenha sido encontrada alguma progressão válida, apresenta a mensagem
     */
    if ( $lTemProgressaoParcial ) {
      db_msgbox( $sMensagem );
    }

    db_msgbox("Matrícula efetuada com sucesso!");

    if ( $importaaprov == "S" ) {
      db_redireciona("edu1_matriculatransffora002.php?ed56_i_aluno={$ed56_i_aluno}&ed47_v_nome={$ed47_v_nome}".
                     "&desabilita&matricula={$matricula}&turmaorigem={$turmaorigem}&turmadestino={$ed60_i_turma}");
    } else {
      db_redireciona("edu1_matriculatransffora001.php");
    }
    exit;
  }
}

if ( isset( $novamatricula ) ) {

  $sSqlMatricula2 = "select fc_codetapaturma({$ed60_i_turma}) as etapasturma";
  $rsMatricula2   = $oDaoMatricula->sql_record($sSqlMatricula2);
  db_fieldsmemory($rsMatricula2, 0);

  if (
           VerUltimoRegHistorico( $ed56_i_aluno, $codetapaturma, $etapasturma ) == true
        && $ed233_c_consistirmat == 'S'
        && $ed233_reclassificaetapaanterior == 'f'
     ) {
    db_msgbox($msgequiv);// $msgequiv -> variável global da função VerUltimoRegHistorico
  } else {

    db_inicio_transacao();
    $sCamposAlunoPossib  = " ed79_i_codigo as codalunopossib, ed56_i_codigo as codalunocurso, ";
    $sCamposAlunoPossib .= " ed56_c_situacao as sitanterior, ed79_c_resulant, ed79_i_turmaant ";
    $sSqlAlunoPossib     = $clalunopossib->sql_query( "", $sCamposAlunoPossib, "", " ed56_i_aluno = $ed56_i_aluno" );
    $sResultAlunoPossib  = $clalunopossib->sql_record( $sSqlAlunoPossib );

    if ($clalunopossib->numrows == 0) {

      $clalunocurso->ed56_i_escola        = $ed56_i_escola;
      $clalunocurso->ed56_i_aluno         = $ed56_i_aluno;
      $clalunocurso->ed56_i_base          = $ed57_i_base;
      $clalunocurso->ed56_i_calendario    = $ed57_i_calendario;
      $clalunocurso->ed56_c_situacao      = "MATRICULADO";
      $clalunocurso->ed56_i_baseant       = null;
      $clalunocurso->ed56_i_calendarioant = null;
      $clalunocurso->ed56_c_situacaoant   = "";
      $clalunocurso->incluir(null);

      $ultimo                           = $clalunocurso->ed56_i_codigo;
      $clalunopossib->ed79_i_alunocurso = $ultimo;
      $clalunopossib->ed79_i_serie      = $codetapaturma;
      $clalunopossib->ed79_i_turno      = $ed57_i_turno;
      $clalunopossib->ed79_i_turmaant   = null;
      $clalunopossib->ed79_c_resulant   = "";
      $clalunopossib->ed79_c_situacao   = "A";
      $clalunopossib->incluir(null);

      $ed79_c_resulant = "";
      $ed79_i_turmaant = null;
      $sitanterior     = "CANDIDATO";
    } else {

      db_fieldsmemory($sResultAlunoPossib,0);
      $sSqlAlunoCurso     = " UPDATE alunocurso SET ";
      $sSqlAlunoCurso    .= "        ed56_c_situacao   = 'MATRICULADO', ";
      $sSqlAlunoCurso    .= "        ed56_i_calendario = {$ed57_i_calendario}, ";
      $sSqlAlunoCurso    .= "        ed56_i_base       = {$ed57_i_base}, ";
      $sSqlAlunoCurso    .= "        ed56_i_escola     = {$ed56_i_escola} ";
      $sSqlAlunoCurso    .= "        WHERE ed56_i_codigo = $codalunocurso ";
      $sResultAlunoCurso  = db_query($sSqlAlunoCurso);

      $sSqlAlunoPossib    = " UPDATE alunopossib SET ";
      $sSqlAlunoPossib   .= "         ed79_i_serie = {$codetapaturma}, ";
      $sSqlAlunoPossib   .= "         ed79_i_turno = {$ed57_i_turno} ";
      $sSqlAlunoPossib   .= "   WHERE ed79_i_codigo = {$codalunopossib} ";
      $sResultAlunoPossib = db_query($sSqlAlunoPossib);

    }

    $sSqlHistorico    = " UPDATE historico SET ";
    $sSqlHistorico   .= "        ed61_i_escola = {$ed56_i_escola} ";
    $sSqlHistorico   .= "  WHERE ed61_i_aluno = {$ed56_i_aluno} ";
    $sResultHistorico = db_query($sSqlHistorico);

    $sql4  = "UPDATE transfescolafora SET";
    $sql4 .= "       ed104_c_situacao = 'F'";
    $sql4 .= " WHERE ed104_i_codigo = {$ed104_i_codigo}";
    $result4 = db_query($sql4);

    $sCampos          = "ed60_i_turma as turmaanterior, ed60_i_numaluno as numanterior";
    $sSqlMatricula    = $clmatricula->sql_query_file( "", $sCampos, "", " ed60_i_codigo = {$matrant}" );
    $sResultMatricula = $clmatricula->sql_record( $sSqlMatricula );
    db_fieldsmemory($sResultMatricula,0);

    $sSqlMatricula    = " UPDATE matricula SET ";
    $sSqlMatricula   .= "        ed60_c_concluida = 'S', ";
    $sSqlMatricula   .= "        ed60_c_ativa = 'N' ";
    $sSqlMatricula   .= "  WHERE ed60_i_codigo = {$matrant} ";
    $sResultMatricula = db_query($sSqlMatricula);

    $sSqlMat    = $clmatricula->sql_query_file( "", "max(ed60_i_numaluno)", "", " ed60_i_turma = {$ed60_i_turma}");
    $sResultMat = $clmatricula->sql_record( $sSqlMat );
    db_fieldsmemory($sResultMat,0);

    $ed60_i_numaluno                   = $max == ""?"":($max+1);
    $ed79_i_turmaant                   = $ed79_i_turmaant == ""?"null":$ed79_i_turmaant;
    $clmatricula->ed60_i_aluno         = $ed56_i_aluno;
    $clmatricula->ed60_i_turma         = $ed60_i_turma;
    $clmatricula->ed60_i_numaluno      = $ed60_i_numaluno;
    $clmatricula->ed60_c_situacao      = "MATRICULADO";
    $clmatricula->ed60_c_concluida     = "N";
    $clmatricula->ed60_i_turmaant      = $ed79_i_turmaant;
    $clmatricula->ed60_c_rfanterior    = $ed79_c_resulant;
    $sDataMatricula                    = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes;
    $sDataMatricula                   .= "-".$ed60_d_datamatricula_dia;
    $clmatricula->ed60_d_datamatricula = $sDataMatricula;
    $sDataModif                        = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes;
    $sDataModif                       .= "-".$ed60_d_datamatricula_dia;
    $clmatricula->ed60_d_datamodif     = $sDataModif;
    $clmatricula->ed60_d_datamodifant  = null;
    $clmatricula->ed60_d_datasaida     = "null";
    $clmatricula->ed60_t_obs           = "";
    $clmatricula->ed60_c_ativa         = "S";
    $clmatricula->ed60_c_tipo          = "N";
    $clmatricula->ed60_c_parecer       = "N";
    $clmatricula->ed60_tipoingresso    = $ed334_tipo;
    $clmatricula->incluir(null);

    $ultima                               = $clmatricula->ed60_i_codigo;
    $clmatriculamov->ed229_i_matricula    = $ultima;
    $clmatriculamov->ed229_i_usuario      = db_getsession("DB_id_usuario");
    $clmatriculamov->ed229_c_procedimento = "MATRICULAR ALUNO";
    $sDescricao                           = "ALUNO MATRICULADO NA TURMA ".trim($ed57_c_descr);
    $sDescricao                          .= ". SITUAÇÃO ANTERIOR: ".trim($sitanterior);
    $clmatriculamov->ed229_t_descr        = $sDescricao;
    $sDataEvento                          = $ed60_d_datamatricula_ano."-".$ed60_d_datamatricula_mes;
    $sDataEvento                         .= "-".$ed60_d_datamatricula_dia;
    $clmatriculamov->ed229_d_dataevento   = $sDataEvento;
    $clmatriculamov->ed229_c_horaevento   = date("H:i");
    $clmatriculamov->ed229_d_data         = date("Y-m-d",db_getsession("DB_datausu"));
    $clmatriculamov->incluir(null);

    $sSqlTurmaSerieRegMat    = $clturmaserieregimemat->sql_query( "", "ed223_i_serie", "", " ed220_i_turma = {$ed60_i_turma}" );
    $sResultTurmaSerieRegMat = $clturmaserieregimemat->sql_record($sSqlTurmaSerieRegMat);

    for ( $r = 0; $r < $clturmaserieregimemat->numrows; $r++ ) {

       db_fieldsmemory($sResultTurmaSerieRegMat,$r);

       if ( $codetapaturma == $ed223_i_serie ) {
         $origem = "S";
       } else {
         $origem = "N";
       }

       $clmatriculaserie->ed221_i_matricula = $ultima;
       $clmatriculaserie->ed221_i_serie     = $ed223_i_serie;
       $clmatriculaserie->ed221_c_origem    = $origem;
       $clmatriculaserie->incluir(null);

    }

    $sWhereMat    = " ed60_i_turma = {$ed60_i_turma} AND ed60_c_situacao = 'MATRICULADO'";
    $sSqlMatri    = $clmatricula->sql_query_file( "", " count(*) as qtdmatricula", "", $sWhereMat );
    $sResultMatri = $clmatricula->sql_record( $sSqlMatri );
    db_fieldsmemory( $sResultMatri, 0 );

    $qtdmatricula = $qtdmatricula == "" ? 0 : $qtdmatricula;

    $sSqlTurma     = " UPDATE turma SET ";
    $sSqlTurma    .= "        ed57_i_nummatr = {$qtdmatricula} ";
    $sSqlTurma    .= "  WHERE ed57_i_codigo = {$ed60_i_turma} ";
    $sResultTurma  = db_query($sSqlTurma);

    if ($ed60_i_turma != $turmaanterior) {

      $sWhereMatr   = " ed60_i_turma = $turmaanterior AND ed60_c_situacao = 'MATRICULADO'";
      $sSqlMatri    = $clmatricula->sql_query_file( "", " count(*) as qtdmatricula", "", $sWhereMatr );
      $sResultMatri = $clmatricula->sql_record($sSqlMatri);

      db_fieldsmemory($sResultMatri,0);
      $qtdmatricula = $qtdmatricula == "" ? 0 : $qtdmatricula;

      $sSqlTurma    = " UPDATE turma SET ";
      $sSqlTurma   .= "        ed57_i_nummatr = {$qtdmatricula} ";
      $sSqlTurma   .= "  WHERE ed57_i_codigo = {$turmaanterior} ";
      $sResultTurma = db_query($sSqlTurma);
    } else {

      $sSqlDiario    = " UPDATE diario SET ";
      $sSqlDiario   .= "        ed95_c_encerrado = 'N' ";
      $sSqlDiario   .= "  WHERE ed95_i_aluno = {$ed56_i_aluno} ";
      $sSqlDiario   .= "    AND ed95_i_regencia in (select ed59_i_codigo from regencia ";
      $sSqlDiario   .= "                                                 where ed59_i_turma = {$ed60_i_turma}) ";
      $sResultDiario = db_query($sSqlDiario);

    }

    db_fim_transacao();

    /**
     * Verifica se o aluno possui progressão ativa na escola que está se transferindo
     */
    $lTemProgressaoParcial = false;
    $oAluno                = AlunoRepository::getAlunoByCodigo( $ed56_i_aluno );
    $sMensagem             = "O(A) aluno(a) possui a(s) seguinte(s) dependência(s):\n";

    foreach ( $oAluno->getProgressaoParcial() as $oProgressaoParcial ) {

      /**
       * Caso a progressão não esteja ativa, ou esteja concluída ou não seja da escola logada, não valida a progressão
       */
      if (    !$oProgressaoParcial->isAtiva()
      || $oProgressaoParcial->isConcluida()
      || $oProgressaoParcial->getEscola()->getCodigo() != $escola ) {
        continue;
      }

      $sMensagem .= " Etapa: {$oProgressaoParcial->getEtapa()->getNome()}";
      $sMensagem .= " - Disciplina: {$oProgressaoParcial->getDisciplina()->getNomeDisciplina()}";
      $sMensagem .= " - Ensino: {$oProgressaoParcial->getEtapa()->getEnsino()->getNome()}\n";

      $lTemProgressaoParcial = true;
    }

    $sMensagem .= "Acesse:\n";
    $sMensagem .= " Matrícula > Progressão Parcial > Ativar / Inativar: para alterar a situação da progressão parcial\n";
    $sMensagem .= " Matrícula > Progressão Parcial > Vincular Aluno / Turma: para vincular a progressão do aluno em uma turma";

    /**
     * Caso tenha sido encontrada alguma progressão válida, apresenta a mensagem
     */
    if ( $lTemProgressaoParcial ) {
      db_msgbox( $sMensagem );
    }

    db_msgbox("Matrícula efetuada com sucesso!");

    if ( $ed60_i_turma != $turmaanterior && $ed334_tipo != 3 ) {

      db_redireciona("edu1_matriculatransffora002.php?ed56_i_aluno={$ed56_i_aluno}&ed47_v_nome={$ed47_v_nome}".
                     "&desabilita&matricula={$matrant}&turmaorigem={$turmaanterior}&turmadestino={$ed60_i_turma}"
                    );

    } else {
      db_redireciona("edu1_matriculatransffora001.php");
    }
    exit;
  }
}

$ed60_d_datamatricula_dia = date("d",db_getsession("DB_datausu"));
$ed60_d_datamatricula_mes = date("m",db_getsession("DB_datausu"));
$ed60_d_datamatricula_ano = date("Y",db_getsession("DB_datausu"));
$ed60_d_datamatricula     = $ed60_d_datamatricula_dia."/".$ed60_d_datamatricula_mes."/".$ed60_d_datamatricula_ano;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/strings.js"></script>
<?php
  require_once("libs/db_jsplibwebseller.php");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" onLoad="a=1" >
  <form name="form2" method="post" action="" class="container">
          <?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
          <fieldset>
            <legend><b>Matricular Alunos Transferidos (FORA)</b></legend>
            <table class="form-container">
              <tr>
                <td colspan="2">
                  <b>Ano do Calendario da matricula: </b>
                  <?db_input( 'ano_matr', 4, $ano_matr, true, 'text', 1, "onchange='js_anomatr(this.value)';" );?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted56_i_aluno?>">
                  <?db_ancora( @$Led56_i_aluno, "js_pesquisatransf();", $db_opcao );?>
                </td>
                <td>
                  <?php
                    db_input( 'ed56_i_aluno',   15, $Ied56_i_aluno,   true, 'text',   3, "");
                    db_input( 'ed47_v_nome',    50, @$Ied47_v_nome,   true, 'text',   3, '');
                    db_input( 'descrserie',     20, @$Idescrserie,    true, 'text',   3, '');
                    db_input( 'codserietransf', 10, @$codserietransf, true, 'hidden', 3, '');
                  ?>
                </td>
              </tr>
              <?php
                if ( isset( $ed56_i_aluno ) ) {

                  $sql    = "SELECT ARRAY(SELECT ed234_i_serieequiv FROM serieequiv WHERE ed234_i_serie in ({$codserietransf})) as seriesequivalentes";
                  $result = db_query($sql);

                  db_fieldsmemory($result,0);

                  $seriesequivalentes = str_replace( "{", "", $seriesequivalentes );
                  $seriesequivalentes = str_replace( "}", "", $seriesequivalentes );
                  $seriesequivalentes = $seriesequivalentes != "" ? $codserietransf.",".$seriesequivalentes : $codserietransf;

                  db_input( 'seriesequivalentes', 50, @$seriesequivalentes, true, 'hidden', 3 );

                  $datahj = date("Y-m-d");

                  if ( strstr( $datasaida, "/" ) ) {

                    $datasaida_dia  = substr( $datasaida, 0, 2 );
                    $datasaida_mes  = substr( $datasaida, 3, 2 );
                    $datasaida_ano  = substr( $datasaida, 6, 4 );
                    $matricula_data = substr( $matricula_data, 0, 2 )."/".substr( $matricula_data, 3, 2 )."/".substr( $matricula_data, 6, 4 );
                  } else {

                    $datasaida_dia  = substr( $datasaida, 8, 2 );
                    $datasaida_mes  = substr( $datasaida, 5, 2 );
                    $datasaida_ano  = substr( $datasaida, 0, 4 );
                    $matricula_data = substr( $matricula_data, 8, 2 )."/".substr( $matricula_data, 5, 2 )."/".substr( $matricula_data, 0, 4 );
                  }

                  $data_in    = mktime( 0, 0, 0, $datasaida_mes, $datasaida_dia, $datasaida_ano );
                  $data_out   = mktime( 0, 0, 0, substr( $datahj, 5, 2 ), substr( $datahj, 8, 2 ), substr( $datahj, 0, 4 ) );
                  $data_entre = $data_out - $data_in;
                  $dias       = ceil( $data_entre / 86400 );
              ?>
              <tr>
                <td>
                  <b>Escola:</b>
                </td>
                <td>
                  <?db_input( 'escola_trfora', 40, @$escola_trfora, true, 'text', 3 );?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Data Matrícula:</b>
                </td>
                <td>
                  <?db_input( 'matricula_data', 10, @$matricula_data, true, 'text', 3 );?>
                  <b>Data Saída:</b>
                  <?db_inputdata( 'datasaida', @$datasaida_dia, @$datasaida_mes, @$datasaida_ano, true, 'text', 3 );?>
                </td>
              </tr>
              <tr><td colspan="2"><hr></td></tr>
              <?
              $camposant  = "matricula.ed60_i_codigo as matrant, matricula.ed60_i_turma, matricula.ed60_c_concluida as concluidaant";
              $camposant .= ", matricula.ed60_c_situacao as situacaoant, matricula.ed60_d_datamatricula as datamatrant";
              $camposant .= ", turma.ed57_c_descr, base.ed31_i_curso, cursoedu.ed29_c_descr, turma.ed57_i_base";
              $camposant .= ", base.ed31_c_descr, turma.ed57_i_calendario, calendario.ed52_i_ano, calendario.ed52_c_descr";
              $camposant .= ", calendario.ed52_d_inicio, calendario.ed52_d_fim, fc_nomeetapaturma(ed60_i_turma) as ed11_c_descr";
              $camposant .= ", serie.ed11_i_sequencia, turma.ed57_i_turno, turno.ed15_c_nome, turma.ed57_i_numvagas";
              $camposant .= ", turma.ed57_i_nummatr, turma.ed57_i_numvagas-turma.ed57_i_nummatr as restantes";
              $camposant .= ", ed60_d_datasaida as datasaidaant";

              $sWhereMatricula  = "     ed60_i_aluno = {$ed56_i_aluno} AND calendario.ed52_i_ano = {$ano_matr}";
              $sWhereMatricula .= " AND turma.ed57_i_escola = ".db_getsession("DB_coddepto")." AND ed60_c_ativa = 'S'";

              $sSqlMatricula = $clmatricula->sql_query( "", $camposant, "", $sWhereMatricula );
              $result_verif  = $clmatricula->sql_record( $sSqlMatricula );
              $linhas_verif  = $clmatricula->numrows;

              if ( $clmatricula->numrows > 0 && !isset( $acesso ) ) {

                db_fieldsmemory( $result_verif, 0 );
                $tem_matrant = true;

                if ( $datasaidaant != "" ) {

                  $datasaidaant_dia = substr( $datasaidaant, 8, 2 );
                  $datasaidaant_mes = substr( $datasaidaant, 5, 2 );
                  $datasaidaant_ano = substr( $datasaidaant, 0, 4 );
                } else {

                  $datasaidaant_dia = substr( $datamatrant, 8, 2 );
                  $datasaidaant_mes = substr( $datamatrant, 5, 2 );
                  $datasaidaant_ano = substr( $datamatrant, 0, 4 );
                }

                $data_in    = mktime( 0, 0, 0, $datasaidaant_mes, $datasaidaant_dia, $datasaidaant_ano );
                $data_out   = mktime( 0, 0, 0, substr( $datahj, 5, 2 ), substr( $datahj, 8, 2 ), substr( $datahj, 0, 4 ) );
                $data_entre = $data_out - $data_in;
                $dias       = ceil( $data_entre / 86400 );

                if ( $concluidaant == "S" ) {

                  $db_botao         = false;
                  $datasaidaant_dia = "";
                  $datasaidaant_mes = "";
                  $datasaidaant_ano = "";
                  $datasaidaant     = "";
                }
              ?>
              <tr>
                <td colspan="2">
                  <br>
                  <font color="red"><b>Aluno (<?=$ed56_i_aluno?>) já possui matrícula em <?=$ano_matr?> nesta escola na turma abaixo relacionada, com situação<br> de <?=$situacaoant?> a <?=$dias?> dia<?=$dias>1?"(s)":""?>.</b></font>
                  <br><br>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Matrícula:</b>
                </td>
                <td>
                  <?db_input( 'matrant', 10, @$matrant, true, 'text', 3 );?>
                  <b>Situação:</b>
                  <?db_input( 'situacaoant', 20, @$situacaoant, true, 'text', 3 );?>
                </td>
              </tr>
              <tr>
                <td>
                  <b>Data Matrícula:</b>
                </td>
                <td>
                  <?db_inputdata( 'datamatrant', @$datamatrant_dia, @$datamatrant_mes, @$datamatrant_ano, true, 'text', 3 );?>
                  <b>Data Saida:</b>
                  <?db_inputdata( 'datasaidaant', @$datasaidaant_dia, @$datasaidaant_mes, @$datasaidaant_ano, true, 'text', 3 );?>
                </td>
              </tr>
              <?}?>
              <tr>
                <td>
                  <label class="bold">Tipo de Ingresso:</label>
                </td>
                <td>
                  <?php
                    $aTipoIngresso = array( 1 => "Normal", 2 => "Classificado", 3 => "Reclassificado", 4 => "Avanço" );
                    db_select( 'ed334_tipo', $aTipoIngresso, true, 1, "onChange='js_redireciona();'");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted60_i_turma?>">
                  <?$opcaoturma = $linhas_verif == 0 ? 1 : 3?>
                  <?db_ancora( @$Led60_i_turma, "js_pesquisaed60_i_turma();", 1 );?>
                </td>
                <td>
                  <?php
                    db_input( 'ed60_i_turma', 15, $Ied60_i_turma,  true, 'text', 3, '' );
                    db_input( 'ed57_c_descr', 20, @$Ied57_c_descr, true, 'text', 3, '' );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted31_i_curso?>">
                  <?=@$Led31_i_curso?>
                </td>
                <td>
                  <?php
                    db_input( 'ed31_i_curso', 15, @$Ied31_i_curso, true, 'text', 3, '' );
                    db_input( 'ed29_c_descr', 40, @$Ied29_c_descr, true, 'text', 3, '' );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?=@$Led57_i_base?>
                </td>
                <td>
                  <?
                    db_input( 'ed57_i_base',  15, @$Ied57_i_base,  true, 'text', 3, '' );
                    db_input( 'ed31_c_descr', 40, @$Ied31_c_descr, true, 'text', 3, '' );
                  ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?=@$Led57_i_calendario?>
                </td>
                <td>
                 <?php
                   db_input( 'ed57_i_calendario', 15, @$Ied57_i_calendario, true, 'text',   3, '' );
                   db_input( 'ed52_c_descr',      40, @$Ied52_c_descr,      true, 'text',   3, '' );
                   db_input( 'ed52_i_ano',        5,  @$Ied52_i_ano,        true, 'text',   3, '' );
                   db_input( 'ed52_d_inicio',     10, @$Ied52_d_inicio,     true, 'hidden', 3, '' );
                   db_input( 'ed52_d_fim',        10, @$Ied52_d_fim,        true, 'hidden', 3, '' );
                   db_input( 'ed104_i_codigo',    10, @$Ied104_i_codigo,    true, 'hidden', 3, '' );
                 ?>
                </td>
              </tr>
              <tr>
                <td>
                 <?=@$Led223_i_serie?>
                </td>
                <td>
                  <div id="div_etapa">
                  <?php
                    if ( isset( $ed60_i_turma ) ) {

                      $sSqlTurmaSerieRegiemMat = $clturmaserieregimemat->sql_query(
                                                                                    "",
                                                                                    "ed223_i_serie, ed11_c_descr as descretapa",
                                                                                    "ed223_i_ordenacao",
                                                                                    " ed220_i_turma = {$ed60_i_turma}"
                                                                                  );
                      $result_etp = $clturmaserieregimemat->sql_record($sSqlTurmaSerieRegiemMat  );

                      if ( $clturmaserieregimemat->numrows > 1 ) {

                      ?>
                        <select name="codetapaturma" id="codetapaturma">
                          <option value=""></option>
                          <?php
                            /* Obtenho as todas as etapas equivalentes a etapa em que o aluno estava matriculado */
                            $sSqlSerieEquiv = $clserieequiv->sql_query( "", "ed234_i_serieequiv", "", " ed234_i_serie = {$codserietransf}" );
                            $result_equiv   = $clserieequiv->sql_record( $sSqlSerieEquiv );

                            // Percorro as etapas da turma e verifico se alguma delas equivale a etapa da turma de origem
                            for ( $r = 0; $r < $clturmaserieregimemat->numrows; $r++ ) {

                              db_fieldsmemory( $result_etp, $r );
                              $selected = "";

                              if ( $clserieequiv->numrows > 0 ) {

                                for ( $w = 0; $w < $clserieequiv->numrows; $w++ ) {

                                  db_fieldsmemory( $result_equiv, $w );
                                  if ( $ed234_i_serieequiv == $ed223_i_serie || $codserietransf == $ed223_i_serie ) {

                                    $selected = "selected";
                                    break;
                                  }
                                }
                              }

                              /* Se consiste matricula, entao so deixa selecionar uma etapa igual
                                 a que o aluno estava matriculado na turma de origem
                              */
                              if ( $ed233_c_consistirmat == 'N' || !empty($selected) ) {
                                $disabled = '';
                              } else {
                              	$disabled = 'disabled';
                              }
                              ?>
                              <option value="<?=$ed223_i_serie?>" <?=$selected?> <?=$disabled?>><?=$descretapa?></option>
                              <?
                            }
                          ?>
                        </select>
                        <?php
                          db_input( 'ed11_c_descr',     40, @$Ied11_c_descr,     true, 'text',   3, '' );
                          db_input( 'ed11_i_sequencia', 10, @$Ied11_i_sequencia, true, 'hidden', 3, '' );
                      } else {

                        db_fieldsmemory($result_etp,0);
                        $codetapaturma = $ed223_i_serie;

                        db_input( 'codetapaturma',    15, @$Icodetapaturma,    true, 'text',   3, '' );
                        db_input( 'ed11_c_descr',     40, @$Ied11_c_descr,     true, 'text',   3, '' );
                        db_input( 'ed11_i_sequencia', 10, @$Ied11_i_sequencia, true, 'hidden', 3, '' );
                      }
                    } else {

                      db_input( 'codetapaturma',    15, @$Icodetapaturma,    true, 'text',   3, '' );
                      db_input( 'ed11_c_descr',     40, @$Ied11_c_descr,     true, 'text',   3, '' );
                      db_input( 'ed11_i_sequencia', 10, @$Ied11_i_sequencia, true, 'hidden', 3, '' );
                    }
                  ?>
                  </div>
                </td>
              </tr>
              <tr>
                <td>
                  <?=@$Led57_i_turno?>
                </td>
                <td>
                 <?php
                   db_input( 'ed57_i_turno', 15, @$Ied57_i_turno, true, 'text', 3, '');
                   db_input( 'ed15_c_nome',  20, @$Ied15_c_nome,  true, 'text', 3, '');
                 ?>
                </td>
              </tr>
              <tr>
                <td>
                  <?=@$Led57_i_numvagas?>
                </td>
                <td>
                  <?php
                    db_input( 'ed57_i_numvagas',15, @$Ied57_i_numvagas, true, 'text', 3, '' );
                    echo @$Led57_i_nummatr;
                    db_input( 'ed57_i_nummatr', 15, @$Ied57_i_nummatr, true, 'text', 3, '' );
                  ?>
                  <b>Vagas Restantes:</b>
                  <?php
                    db_input('restantes',15,@$Irestantes,true,'text',3,'');

                    if ( isset( $restantes ) && @$restantes < 1 ) {

                      $db_botao = false;
                      db_msgbox("Turma sem vagas disponíveis!");
                    }
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted56_i_escola?>" width="15%">
                  <?db_ancora( @$Led56_i_escola, "", 3 );?>
                </td>
                <td>
                  <?php
                    db_input( 'ed56_i_escola', 15, $Ied56_i_escola, true, 'text', 3, "" );
                    db_input( 'ed18_c_nome',   50, @$Ied18_c_nome,  true, 'text', 3, '' );
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=@$Ted60_d_datamatricula?>">
                  <?=@$Led60_d_datamatricula?>
                </td>
                <td>
                  <?db_inputdata( 'ed60_d_datamatricula', @$ed60_d_datamatricula_dia, @$ed60_d_datamatricula_mes, @$ed60_d_datamatricula_ano, true, 'text', $db_opcao );?>
               </td>
              </tr>
              <?php
              if ( ( isset( $ed60_i_turma ) && $linhas_verif == 0 ) || ( isset( $acesso ) && isset( $ed52_i_ano ) ) ) {

                $campos  = "ed18_c_nome as ed18_c_nomeorigem, matricula.ed60_i_codigo as matricula";
                $campos .= ", turma.ed57_i_codigo as turmaorigem, turma.ed57_c_descr as ed57_c_descrorigem";
                $campos .= ", fc_nomeetapaturma(ed57_i_codigo) as ed11_c_descrorigem";

                $sql_imp  = "SELECT {$campos}                                                                                  ";
                $sql_imp .= "  FROM transfescolafora                                                                           ";
                $sql_imp .= "       inner join escola     on escola.ed18_i_codigo     = transfescolafora.ed104_i_escolaorigem  ";
                $sql_imp .= "       inner join aluno      on aluno.ed47_i_codigo      = transfescolafora.ed104_i_aluno         ";
                $sql_imp .= "       inner join escolaproc on escolaproc.ed82_i_codigo = transfescolafora.ed104_i_escoladestino ";
                $sql_imp .= "       inner join matricula  on matricula.ed60_i_codigo  = transfescolafora.ed104_i_matricula     ";
                $sql_imp .= "       inner join turma      on turma.ed57_i_codigo      = matricula.ed60_i_turma                 ";
                $sql_imp .= "       inner join calendario on calendario.ed52_i_codigo = turma.ed57_i_calendario                ";
                $sql_imp .= " WHERE matricula.ed60_c_situacao = 'TRANSFERIDO FORA'                                             ";
                $sql_imp .= "   AND ed104_i_aluno = {$ed56_i_aluno}                                                            ";
                $sql_imp .= "   AND ed52_i_ano = {$ed52_i_ano}                                                                 ";
                $sql_imp .= "   AND ed60_c_ativa = 'S'                                                                         ";
                $sql_imp .= " ORDER BY ed104_d_data DESC                                                                       ";
                $sql_imp .= " LIMIT 1                                                                                          ";
                $result_imp = db_query($sql_imp);
                $linhas_imp = pg_num_rows($result_imp);

                if ( $linhas_imp > 0 ) {
                ?>
                  <tr>
                    <td colspan="2">
                     Este aluno foi transferido para fora da Rede Municipal neste ano.<br>
                     Caso queira importar o aproveitamento deste aluno na turma abaixo relacionada, informe no campo abaixo:
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <label class="bold">Importar aproveitamento:</label>
                    </td>
                    <td>
                      <select id="importaaprov" name="importaaprov">
                        <option value="S" selected>SIM</option>
                        <option value="N">NÃO</option>
                      </select>
                    </td>
                  </tr>
                  <?php
                  for ( $y = 0; $y < $linhas_imp; $y++ ) {

                    db_fieldsmemory( $result_imp, $y );
                    $checked = $y == 0 ? "checked" : "";
                  ?>
                    <tr>
                      <td style="text-decoration:underline;"
                          onmouseover="document.getElementById('aprov<?=$turmaorigem?>').style.visibility = 'visible'"
                          onmouseout="document.getElementById('aprov<?=$turmaorigem?>').style.visibility = 'hidden'">
                        <?db_input( 'turmaorigem', 15, @$Iturmaorigem, true, 'radio', 3, $checked );?>
                        Turma Anterior:
                      </td>
                      <td>
                        <?php
                          db_input( 'ed57_c_descrorigem', 10, @$Ied57_c_descrorigem, true, 'text', 3, '' );
                          db_input( 'ed11_c_descrorigem', 20, @$Ied11_c_descrorigem, true, 'text', 3, '' );
                          db_input( 'ed18_c_nomeorigem',  50, @$Ied18_c_nomeorigem,  true, 'text', 3, '' );
                        ?>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <label class="bold">Matrícula:</label>
                      </td>
                      <td>
                        <?db_input( 'matricula', 10, @$Imatricula, true, 'text', 3, '' );?>
                        <br>
                        <table border="1" cellspacing="0" cellpadding="0" id="aprov<?=$turmaorigem?>" style="position:absolute;visibility:hidden;">
                          <?php
                            $veraprovnulo = "";
                            $primeira     = "";

                            $sCamposDiarioAvaliacao  = "ed59_i_codigo as regenciaorigem, ed232_c_descr,ed232_c_abrev";
                            $sCamposDiarioAvaliacao .= ", ed09_c_abrev, ed72_i_valornota, ed72_c_valorconceito";
                            $sCamposDiarioAvaliacao .= ", ed72_t_parecer, ed37_c_tipo";

                            $sWhereDiarioAvaliacao   = "     ed95_i_aluno = {$ed56_i_aluno} AND ed59_i_turma = {$turmaorigem}";
                            $sWhereDiarioAvaliacao  .= " AND ed09_c_somach = 'S'";

                            $sSqlDiarioAvaliacao     = $cldiarioavaliacao->sql_query( "",
                                                                                      $sCamposDiarioAvaliacao,
                                                                                      "ed232_c_descr,ed41_i_sequencia ASC",
                                                                                      $sWhereDiarioAvaliacao
                                                                                    );
                            $result_diario           = $cldiarioavaliacao->sql_record( $sSqlDiarioAvaliacao );

                            if ( $cldiarioavaliacao->numrows == 0 ) {
                              echo "<tr><td width='160px' style='background:#f3f3f3;'>Nenhum registro de aproveitamento.</td></tr>";
                            } else {

                              for ( $t = 0; $t < $cldiarioavaliacao->numrows; $t++ ) {

                               db_fieldsmemory( $result_diario, $t );

                               if ($primeira != $regenciaorigem ) {

                                 echo "</tr><tr><td style='background:#444444;color:#DEB887'><b>$ed232_c_descr</b></td>";
                                 $primeira = $regenciaorigem;
                               }

                               if ( trim( $ed37_c_tipo ) == "NOTA" ) {

                                 if ( $resultedu == 'S' ) {
                                   $aproveitamento = $ed72_i_valornota != "" ? number_format( $ed72_i_valornota, 2, ",", "." ) : "";
                                 } else {
                                   $aproveitamento = $ed72_i_valornota != "" ? number_format( $ed72_i_valornota, 0 ) : "";
                                 }
                               } else if ( trim( $ed37_c_tipo ) == "NIVEL" ) {
                                 $aproveitamento = $ed72_c_valorconceito;
                               } else {
                                $aproveitamento = "";
                               }

                               $veraprovnulo .= $aproveitamento;
                               echo "<td style='background:#f3f3f3;'><b>$ed09_c_abrev:</b></td>
                                     <td width='50px' style='background:#f3f3f3;' align='center'>".($aproveitamento == "" ? "&nbsp;" : $aproveitamento )."</td>";
                              }
                            }
                          ?>
                        </table>
                      </td>
                    </tr>
                   <?
                  }
                }
              }
            }?>
            </table>
          </fieldset>
          <?php
            if ( ( isset( $linhas_verif ) && $linhas_verif == 0 ) || isset( $acesso ) ) {
          ?>
              <input name="incluirmatricula" type="submit" value="Matricular Aluno" disabled onclick="return js_validaturma();">
          <?php
            } else {
          ?>
              <input name="novamatricula" type="submit" value="Matricular Aluno" disabled onclick="return js_validaturma();">
          <?}?>
  </form>
  <?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
var sCaminhoMensagens = 'educacao.escola.edu1_matriculatransffora001.';

function js_pesquisatransf() {
  js_OpenJanelaIframe(
                       'top.corpo',
                       'db_iframe_transfescolafora',
                       'func_transfescolaforamatr.php?funcao_js=parent.js_mostraaluno|ed104_i_aluno|ed47_v_nome'
                                                                                   +'|dl_etapa|dl_codigo|ed104_d_data'
                                                                                   +'|matricula_data|ed18_c_nome|ed104_i_codigo',
                       'Pesquisa de alunos transferidos para fora da rede',
                       true
                     );
}

function js_mostraaluno( chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8 ) {

  db_iframe_transfescolafora.hide();
  location.href = 'edu1_matriculatransffora001.php?ed56_i_aluno='+chave1
                                                +'&ed47_v_nome='+chave2
                                                +'&descrserie='+chave3
                                                +'&codserietransf='+chave4
                                                +'&datasaida='+chave5
                                                +'&ano_matr='+document.form2.ano_matr.value
                                                +'&matricula_data='+chave6
                                                +'&escola_trfora='+chave7
                                                +'&ed104_i_codigo='+chave8;
}

function js_pesquisaed60_i_turma() {

  var sParametros = "";

  if ( $('ed334_tipo').value == 3 ) {
    sParametros += "&lReclassificacao";
  }

  if (document.form2.ano_matr.value == '') {

    alert( _M( sCaminhoMensagens + 'ano_calendario' ) );
    document.form2.ano_matr.style.backgroundColor = '#99A9AE';
    document.form2.ano_matr.focus();

  } else {

    js_OpenJanelaIframe(
                         'top.corpo',
                         'db_iframe_turma',
                         'func_turmamatrtransffora.php?codserietransf='+document.form2.codserietransf.value
                                                    +'&anocalendario='+document.form2.ano_matr.value
                                                    +'&aluno='+document.form2.ed56_i_aluno.value
                                                    +'&funcao_js=parent.js_mostraturma1|ed57_i_codigo|ed57_c_descr|ed11_c_descr|'
                                                    +'ed52_c_descr|ed29_c_descr|ed31_c_descr|ed15_c_nome|ed11_i_codigo|ed52_i_codigo|'
                                                    +'ed29_i_codigo|ed31_i_codigo|ed15_i_codigo|ed57_i_nummatr|ed57_i_numvagas|'
                                                    +'ed11_i_sequencia|ed52_i_ano|ed52_d_inicio|ed52_d_fim&lEliminarSeriesAnteriores=true'
                                                    +'&turmasprogressao=f'+sParametros,
                         'Pesquisa de Turmas',
                         true
                       );

  }
}
function js_mostraturma1( chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, chave9, chave10, chave11,
                          chave12, chave13, chave14, chave15, chave16, chave17, chave18 ) {

  document.form2.ed60_i_turma.value      = chave1;
  document.form2.ed57_c_descr.value      = chave2;
  document.form2.ed11_c_descr.value      = chave3;
  document.form2.ed52_c_descr.value      = chave4;
  document.form2.ed29_c_descr.value      = chave5;
  document.form2.ed31_c_descr.value      = chave6;
  document.form2.ed15_c_nome.value       = chave7;
  document.form2.codetapaturma.value     = chave8;
  document.form2.ed57_i_calendario.value = chave9;
  document.form2.ed31_i_curso.value      = chave10;
  document.form2.ed57_i_base.value       = chave11;
  document.form2.ed57_i_turno.value      = chave12;
  document.form2.ed57_i_nummatr.value    = chave13;
  document.form2.ed57_i_numvagas.value   = chave14;
  document.form2.ed11_i_sequencia.value  = chave15;
  document.form2.ed52_i_ano.value        = chave16;
  document.form2.ed52_d_inicio.value     = chave17;
  document.form2.ed52_d_fim.value        = chave18;
  document.form2.restantes.value         = parseInt(chave14) - parseInt(chave13);
  db_iframe_turma.hide();

  if ( parseInt( chave13 ) >= parseInt( chave14 ) ) {

    alert( _M( sCaminhoMensagens + 'turma_sem_vagas' ) );

    if ( document.form2.incluirmatricula ) {
      document.form2.incluirmatricula.disabled = true;
    }

    if ( document.form2.reativar ) {
      document.form2.reativar.disabled = true;
    }

    if ( document.form2.novamatricula ) {
      document.form2.novamatricula.disabled = true;
    }
  } else {

    if ( document.form2.incluirmatricula ) {
      document.form2.incluirmatricula.disabled = false;
    }

    if ( document.form2.reativar ) {
      document.form2.reativar.disabled = false;
    }

    if ( document.form2.novamatricula ) {
      document.form2.novamatricula.disabled = false;
    }

    <?if ( !isset( $tem_matrant ) ) {?>

        document.form2.submit();
    <?} else {?>
        js_montaetapa( chave8, chave3, chave15, document.form2.codserietransf.value, document.form2.seriesequivalentes.value );
    <?}?>
  }
}

function js_montaetapa ( codigoetapa, descretapa, seqetapa, etapaorigem, equiv ) {

  arr_etapa = codigoetapa.split(",");
  arr_equiv = equiv.split(",");

  if ( arr_etapa.length > 1 ) {

    arr_descr = descretapa.split("-");
    arr_descr = arr_descr[1].split("/");
    sHtml     = '<select name="codetapaturma" id="codetapaturma">';
    sHtml    += '<option value=""></option>';

    for ( var i = 0; i < arr_etapa.length; i++ ) {

      selectedd = "";
      disabledd = "disabled";

      for ( var w = 0; w < arr_equiv.length; w++ ) {

        if ( arr_etapa[i] == arr_equiv[w] ) {

          selectedd = "selected";
          disabledd = "";
          break;
        }
      }

      sHtml += '<option value="'+arr_etapa[i]+'" '+selectedd+' '+disabledd+'>'+arr_descr[i]+'</option>';
    }

    sHtml += '</select>';
    sHtml += '<input type="text" size="40" name="ed11_c_descr" readonly style="background:#DEB887" value="'+descretapa+'">';
    sHtml += '<input type="hidden" size="10" name="ed11_i_sequencia"  value="'+seqetapa+'">';
    document.getElementById("div_etapa").innerHTML = sHtml;
  } else {

    sHtml = '<input type="text" size="15" name="codetapaturma" readonly style="background:#DEB887" value="'+arr_etapa+'">';
    sHtml += '<input type="text" size="40" name="ed11_c_descr" readonly style="background:#DEB887" value="'+descretapa+'">';
    sHtml += '<input type="hidden" size="10" name="ed11_i_sequencia"  value="'+seqetapa+'">';
    document.getElementById("div_etapa").innerHTML = sHtml;
  }
}

function js_validaturma() {

  if ( document.form2.codetapaturma.value == "" ) {

    alert( _M( sCaminhoMensagens + 'informe_etapa' ) );
    document.form2.codetapaturma.focus();
    document.form2.codetapaturma.style.backgroundColor = '#99A9AE';
    return false;
  }

  if ( document.form2.ed60_d_datamatricula.value == "" ) {

    alert( _M( sCaminhoMensagens + 'informe_data_matricular' ) );
    document.form2.ed60_d_datamatricula.focus();
    document.form2.ed60_d_datamatricula.style.backgroundColor = '#99A9AE';
    return false;
  } else {

    datamat  = document.form2.ed60_d_datamatricula_ano.value + "-" + document.form2.ed60_d_datamatricula_mes.value;
    datamat += "-" + document.form2.ed60_d_datamatricula_dia.value;
    dataini = document.form2.ed52_d_inicio.value;
    datafim = document.form2.ed52_d_fim.value;
    check   = js_validata( datamat, dataini, datafim );

    if ( check == false ) {

      data_ini = dataini.substr( 8, 2 ) + "/" + dataini.substr( 5, 2 ) + "/" + dataini.substr( 0, 4 );
      data_fim = datafim.substr( 8, 2 ) + "/" + datafim.substr( 5, 2 ) + "/" + datafim.substr( 0, 4 );
      alert( _M( sCaminhoMensagens + 'data_matricula_fora_periodo', {"iDataInicial" : data_ini, "iDataFinal" : data_fim} ) );

      document.form2.ed60_d_datamatricula.focus();
      document.form2.ed60_d_datamatricula.style.backgroundColor = '#99A9AE';
      return false;
    }

    datamat         = datamat.substr( 0, 4 ) + '' + datamat.substr( 5, 2 ) + '' + datamat.substr( 8, 2 );
    matricula_data  = document.form2.matricula_data.value.substr( 6, 4 ) + "" + document.form2.matricula_data.value.substr( 3, 2 );
    matricula_data += "" + document.form2.matricula_data.value.substr( 0, 2 );
    datasaida       = document.form2.datasaida.value.substr( 6, 4 ) + "" + document.form2.datasaida.value.substr( 3, 2 );
    datasaida      += "" + document.form2.datasaida.value.substr( 0, 2 );

    if ( matricula_data != "" ) {

      if ( parseInt( matricula_data ) > parseInt( datamat ) ) {

        alert( _M( sCaminhoMensagens + 'data_matricula_menor_data_matricula_anterior', {"iMatricula" : document.form2.matricula_data.value} ) );
        document.form2.ed60_d_datamatricula.focus();
        document.form2.ed60_d_datamatricula.style.backgroundColor = '#99A9AE';
        return false;
      }
    }

    if ( datasaida != "" ) {

      if ( parseInt( datasaida ) > parseInt( datamat ) ) {

        alert( _M( sCaminhoMensagens + 'data_matricula_menor_data_saida', {"iDataMatricula" : document.form2.datasaida.value} ) );
        document.form2.ed60_d_datamatricula.focus();
        document.form2.ed60_d_datamatricula.style.backgroundColor = '#99A9AE';
        return false;
      }
    }
  }

  return true;
}

function js_anomatr ( valor ) {

  if ( valor == "" ) {
    location.href = "edu1_matriculatransffora001.php";
  } else {

    if ( document.form2.ed56_i_aluno.value != "" ) {
      location.href = 'edu1_matriculatransffora001.php?ed56_i_aluno='+document.form2.ed56_i_aluno.value
                                                    +'&ed47_v_nome='+document.form2.ed47_v_nome.value
                                                    +'&descrserie='+document.form2.descrserie.value
                                                    +'&datasaida='+document.form2.datasaida.value
                                                    +'&ano_matr='+valor
                                                    +'&codserietransf='+document.form2.codserietransf.value
                                                    +'&matricula_data='+document.form2.matricula_data.value
                                                    +'&escola_trfora='+document.form2.escola_trfora.value
                                                    +'ed104_i_codigo'+document.form2.ed104_i_codigo.value;
    }
  }
}

<?if ( ( isset( $ed60_i_turma ) && $linhas_verif == 0 ) || isset( $acesso ) ) {?>
    document.form2.incluirmatricula.disabled = false;
<?} else {

    if ( isset( $linhas_verif ) && $linhas_verif > 0 ) {?>
      document.form2.novamatricula.disabled = false;
  <?}

    if ( $db_botao == false ) {?>
      document.form2.novamatricula.disabled = true;
  <?}
  }?>

function js_validaTipo() {

  if ( !$('importaaprov') ) {
    return;
  }

  $('importaaprov').value    = 'S';
  $('importaaprov').disabled = false;
  $('importaaprov').removeClassName("readonly");

  if ( $F('ed334_tipo') == 3 ) {

    $('importaaprov').addClassName("readonly");
    $('importaaprov').value    = 'N';
    $('importaaprov').disabled = true;
  }

};

js_validaTipo();

if ( $('ed334_tipo') != null ) {
  var iValorAnterior = $F('ed334_tipo');
}

function js_redireciona() {

  var iValorAtual       = $F('ed334_tipo');

  if ( iValorAnterior == 3 || iValorAtual == 3 ) {

    var aRedirecionamento = [];
    var oGet              = js_urlToObject();

    for ( var sAtributo in oGet ) {

      var sValor = oGet[sAtributo];
      aRedirecionamento.push(sAtributo + "=" + sValor.trim());
    }

    aRedirecionamento.push("ed334_tipo="+$F('ed334_tipo'));
    aRedirecionamento.push("acesso="+ Math.random());

    window.location.href = '?' + aRedirecionamento.join("&");
  }

  return;
};

$("ano_matr").addClassName("field-size1");
$("ed56_i_aluno").addClassName("field-size2");
$("ed47_v_nome").addClassName("field-size7");
$("descrserie").addClassName("field-size2");

if( $("escola_trfora") ){

	  $("escola_trfora").addClassName("field-size-max");
    $("matricula_data").addClassName("field-size2");
    $("datasaida").addClassName("field-size2");
    $("ed60_i_turma").addClassName("field-size2");
    $("ed57_c_descr").addClassName("field-size9");
    $("ed31_i_curso").addClassName("field-size2");
    $("ed29_c_descr").addClassName("field-size9");
    $("ed57_i_base").addClassName("field-size2");
    $("ed31_c_descr").addClassName("field-size9");
    $("ed57_i_calendario").addClassName("field-size2");
    $("ed52_c_descr").addClassName("field-size7");
    $("ed52_i_ano").addClassName("field-size2");
    $("codetapaturma").addClassName("field-size2");
    $("ed11_c_descr").addClassName("field-size9");
    $("ed57_i_turno").addClassName("field-size2");
    $("ed15_c_nome").addClassName("field-size9");
    $("ed56_i_escola").addClassName("field-size2");
    $("ed18_c_nome").addClassName("field-size9");
    $("ed57_i_numvagas").addClassName("field-size2");
    $("ed57_i_nummatr").style.width = "76px";
    $("restantes").style.width      = "76px";
    $("ed60_d_datamatricula").addClassName("field-size2");

    if( $("ed57_c_descrorigem") ){
    	  $("ed57_c_descrorigem").addClassName("field-size2");
    }
    if( $("ed11_c_descrorigem") ){
    	  $("ed11_c_descrorigem").addClassName("field-size2");
    }
    if( $("ed18_c_nomeorigem") ){
    	  $("ed18_c_nomeorigem").addClassName("field-size7");
    }
    if( $("matricula") ){
    	  $("matricula").addClassName("field-size2");
    }
    if( $("matrant") ){
    	  $("matrant").addClassName("field-size2");
    }
    if( $("situacaoant") ){
    	  $("situacaoant").style.width = "325px";
    }
    if( $("datamatrant") ){
    	  $("datamatrant").addClassName("field-size2");
    }
    if( $("datasaidaant") ){
    	  $("datasaidaant").addClassName("field-size2");
    }
}
</script>