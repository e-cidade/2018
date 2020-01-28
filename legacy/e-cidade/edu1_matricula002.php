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

define( 'MENSAGENS_EDU1_MATRICULA002', 'educacao.escola.edu1_matricula002.' );

require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_jsplibwebseller.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str( $_SERVER["QUERY_STRING"]);

$ed60_d_datamodif_dia = date( "d", db_getsession("DB_datausu") );
$ed60_d_datamodif_mes = date( "m", db_getsession("DB_datausu") );
$ed60_d_datamodif_ano = date( "Y", db_getsession("DB_datausu") );

db_postmemory($_POST);

$clmatricula                 = new cl_matricula;
$clmatriculamov              = new cl_matriculamov;
$cllogmatricula              = new cl_logmatricula;
$clturma                     = new cl_turma;
$clturmaserieregimemat       = new cl_turmaserieregimemat;
$oDaoMatriculaTurnoReferente = new cl_matriculaturnoreferente();
$oDaoTurmaTurnoReferente     = new cl_turmaturnoreferente();

$db_opcao = 22;
$db_botao = false;

if ( isset($alterar) ) {

  $lErroTransacao = false;

  db_inicio_transacao();
  try {

    $iMatricula = $ed60_matricula;
    $oAluno     = AlunoRepository::getAlunoByCodigo( $ed60_i_aluno );
    $oTurma     = TurmaRepository::getTurmaByCodigo( $ed60_i_turma );

    if( $ed60_c_situacaoatual == 'MATRICULADO' ) {

      $oMatricula = MatriculaRepository::getMatriculaAtivaTurma( $oTurma, $oAluno );

      if( $oMatricula instanceof Matricula ) {
        $iMatricula = $oMatricula->getCodigo();
      }
    }

    if( $ed60_c_situacaoatual != 'MATRICULADO' ) {

      $aMatriculas = MatriculaRepository::getTodasMatriculasAluno( $oAluno, false, $oTurma );

      foreach( $aMatriculas as $oMatricula ) {

        if( trim( $oMatricula->getSituacao() ) == trim( $ed60_c_situacaoatual ) ) {
          $iMatricula = $oMatricula->getCodigo();
        }
      }
    }

    if ( MatriculaPosterior( $iMatricula ) ) {
      throw new Exception("Não é possível alterar a situação da matrícula pois o aluno possui matrícula posterior.");
    }

    /**
     * Retorna todas as matriculas do aluno
     */
    $sCamposMatriculaParaModificar  = "ed60_d_datamodif as datamodif, ed60_d_datamodifant as datamodifant, ";
    $sCamposMatriculaParaModificar .= "ed221_i_serie as etapaorigem, ed60_i_codigo, ed60_matricula";
    $sOrderMatriculaParaModificar   = "ed60_i_codigo desc";
    $sWhereMatriculaParaModificar   = "ed60_matricula = {$ed60_matricula} AND ed60_i_turma = {$ed60_i_turma}";
    $sSqlMatriculaParaModificar     = $clmatricula->sql_query("",
                                                              $sCamposMatriculaParaModificar,
                                                              $sOrderMatriculaParaModificar,
                                                              $sWhereMatriculaParaModificar
                                                             );
    $rsMatriculaModificar          = db_query($sSqlMatriculaParaModificar);

    if( !is_resource( $rsMatriculaModificar ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_EDU1_MATRICULA002 . 'erro_buscar_matriculas', $oErro ) );
    }

    if( pg_num_rows( $rsMatriculaModificar ) == 0 ) {
      throw new BusinessException( _M( MENSAGENS_EDU1_MATRICULA002 . 'nenhuma_matricula_encontrada' ) );
    }

    $oDadosMatricularParaModificar = db_utils::fieldsMemory($rsMatriculaModificar, 0);
    $db_opcao = 2;

    /**
     * Verifica a situacao selecionada foi 'MATRICULADO', atualizando as datas desta matricula
     */
    if (trim($ed60_c_situacao) == "MATRICULADO") {

      $clmatricula->ed60_d_datasaida    = "null";
      $clmatricula->ed60_d_datamodifant = null;

      if (isset($eliminamov)) {
       $clmatricula->ed60_d_datamodif = $oDadosMatricularParaModificar->datamodifant;
      }
      $clmatricula->ed60_d_datamodifant = null;
    } else {

      $clmatricula->ed60_d_datasaida    = $ed60_d_datamodif_ano."-".$ed60_d_datamodif_mes."-".$ed60_d_datamodif_dia;
      $clmatricula->ed60_d_datamodifant = $oDadosMatricularParaModificar->datamodif;
    }

    $clmatricula->ed60_matricula = $oDadosMatricularParaModificar->ed60_matricula;
    $clmatricula->ed60_i_codigo  = $oDadosMatricularParaModificar->ed60_i_codigo;
    $clmatricula->alterar($oDadosMatricularParaModificar->ed60_i_codigo);
    if ($clmatricula->erro_status == 0) {

      $sMsgErro = "Erro ao salvar Dados da Matrícula do aluno.\\n{$clmatricula->erro_msg}";
      throw new Exception($sMsgErro);
    }

    if (trim($ed60_c_situacao) == "MATRICULADO") {

      /**
       * reativa a matriculado aluno.
       * devemos pesquisar todas as disciplinas que a turma possui,
       * e reativamos os diarios de avaliação do aluno, caso ele possua algum.
       */
      $sSqlRegencias        = "SELECT ed59_i_codigo as regturma ";
      $sSqlRegencias       .= "  FROM regencia ";
      $sSqlRegencias       .= " WHERE ed59_i_turma = {$ed60_i_turma} ";
      $sSqlRegencias       .= "   AND ed59_i_serie = {$oDadosMatricularParaModificar->etapaorigem}";
      $rsRegencias          = db_query($sSqlRegencias);

      if( !is_resource( $rsRegencias ) ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();

        throw new DBException( _M( MENSAGENS_EDU1_MATRICULA002 . 'erro_buscar_regencias', $oErro ) );
      }

      $iTotalLinhasRegencia = pg_num_rows($rsRegencias);

      for ($iRegencia = 0; $iRegencia < $iTotalLinhasRegencia; $iRegencia++) {

        db_fieldsmemory($rsRegencias, $iRegencia);

        $sSqlAtualizaDiarioClasse  = "UPDATE diario ";
        $sSqlAtualizaDiarioClasse .= "   SET ed95_c_encerrado = 'N' ";
        $sSqlAtualizaDiarioClasse .= "  WHERE ed95_i_aluno    = {$ed60_i_aluno} ";
        $sSqlAtualizaDiarioClasse .= "    AND ed95_i_regencia = {$regturma} ";
        $rsAtualizaDiarioClasse    = db_query($sSqlAtualizaDiarioClasse);

        if (!$rsAtualizaDiarioClasse) {
          throw new Exception("Erro ao alterar situação do diário de avaliação do aluno");
        }
      }

      $clmatriculamov->ed229_t_descr  = "REATIVAÇÃO DA MATRÍCULA. SITUAÇÃO DA MATRÍCULA MODIFICADA DE ";
      $clmatriculamov->ed229_t_descr .= trim($ed60_c_situacaoatual)." PARA ".trim($ed60_c_situacao);

    } else if (trim($ed60_c_situacao) == "MATRICULA INDEVIDA") {

      if ($ed57_novaturma != "") {

        trocaTurma($oDadosMatricularParaModificar->ed60_i_codigo, $ed57_novaturma, false, $ed60_matricula, $sTurno, false);
        LimpaResultadofinal($oDadosMatricularParaModificar->ed60_i_codigo);
        $clmatriculamov->ed229_t_descr = "SITUAÇÃO DA MATRÍCULA MODIFICADA DE ".trim($ed60_c_situacaoatual)." PARA ".trim($ed60_c_situacao);
      }
    } else {

      $clmatriculamov->ed229_t_descr = "SITUAÇÃO DA MATRÍCULA MODIFICADA DE ".trim($ed60_c_situacaoatual)." PARA ".trim($ed60_c_situacao);
      LimpaResultadofinal($oDadosMatricularParaModificar->ed60_i_codigo);
    }

    if (!isset($eliminamov)) {

      $ed229_i_codigo = "";
      $clmatriculamov->ed229_i_matricula    = $oDadosMatricularParaModificar->ed60_i_codigo;
      $clmatriculamov->ed229_i_usuario      = db_getsession("DB_id_usuario");
      $clmatriculamov->ed229_c_procedimento = "ALTERAR SITUAÇÃO DA MATRÍCULA";
      $clmatriculamov->ed229_d_dataevento   = $ed60_d_datamodif_ano."-".$ed60_d_datamodif_mes."-".$ed60_d_datamodif_dia;
      $clmatriculamov->ed229_c_horaevento   = date("H:i");

      if (trim($clmatriculamov->ed229_t_descr) == "") {
        $clmatriculamov->ed229_t_descr = ' ';
      }

      $clmatriculamov->ed229_d_data = date( "Y-m-d", db_getsession("DB_datausu") );
      $clmatriculamov->incluir($ed229_i_codigo);

      if ($clmatriculamov->erro_status == 0) {
        throw new Exception("Erro ao incluir movimentação da matricula\\n{$clmatriculamov->erro_msg}");
      }
    } else {

      $sSqlRemoveHistoricoMatricula  = "DELETE FROM matriculamov ";
      $sSqlRemoveHistoricoMatricula .= " WHERE ed229_i_matricula = $oDadosMatricularParaModificar->ed60_i_codigo  ";
      $sSqlRemoveHistoricoMatricula .= "   AND ed229_c_procedimento = 'ALTERAR SITUAÇÃO DA MATRÍCULA'";
      $sSqlRemoveHistoricoMatricula .= "   AND ed229_t_descr like '%PARA ".trim($ed60_c_situacaoatual)."%'";
      $rsRemoveHistoricoMatricula    = db_query($sSqlRemoveHistoricoMatricula);

      if (!$rsRemoveHistoricoMatricula) {
        throw new Exception("Erro ao remover movimentações da matrícula");
      }

    	$sSqlUpdateMatMov = " UPDATE matricula SET ed60_d_datasaida = null WHERE ed60_i_codigo = {$oDadosMatricularParaModificar->ed60_i_codigo}";
     	$rsUpdateMatMov   = db_query($sSqlUpdateMatMov);

      if (!$rsUpdateMatMov) {
        throw new Exception("Erro ao retornar matricula do aluno");
      }

      $sDescricaoOrigem  = "Matrícula n°: {$ed60_matricula}\nTurma: {$ed57_c_descr}\nEscola: ";
      $sDescricaoOrigem .= db_getsession("DB_nomedepto")."\nCalendário: {$ed52_c_descr}\n RETORNO em ";
      $sDescricaoOrigem .= "{$ed60_d_datamodif_dia}/{$ed60_d_datamodif_mes}/{$ed60_d_datamodif_ano}";

      $cllogmatricula->ed248_i_usuario = db_getsession("DB_id_usuario");
      $cllogmatricula->ed248_i_motivo  = null;
      $cllogmatricula->ed248_i_aluno   = $ed60_i_aluno;
      $cllogmatricula->ed248_t_origem  = $sDescricaoOrigem;
      $cllogmatricula->ed248_t_obs     = "";
      $cllogmatricula->ed248_d_data    = date("Y-m-d",db_getsession("DB_datausu"));
      $cllogmatricula->ed248_c_hora    = date("H:i");
      $cllogmatricula->ed248_c_tipo    = "R";
      $cllogmatricula->incluir(null);

      if ($cllogmatricula->erro_status == 0) {
         throw new Exception("Erro ao incluir dados do log da matrícula.\\n{$cllogmatricula->erro_msg}");
      }
    }

    $sSqAtualizaCursoAluno  = "UPDATE alunocurso SET ";
    $sSqAtualizaCursoAluno .= "       ed56_c_situacao = '{$ed60_c_situacao}' ";
    $sSqAtualizaCursoAluno .= " WHERE ed56_i_aluno    = {$ed60_i_aluno}";

    if ($ed60_c_situacao == "MATRICULA INDEVIDA") {

      $sSqAtualizaCursoAluno  = "UPDATE alunocurso SET ";
      $sSqAtualizaCursoAluno .= "       ed56_c_situacao = 'MATRICULADO' ";
      $sSqAtualizaCursoAluno .= " WHERE ed56_i_aluno    = {$ed60_i_aluno}";
    }

    $rsAtualizaAlunoCurso     = db_query($sSqAtualizaCursoAluno);

    if (!$rsAtualizaAlunoCurso) {
      throw new Exception("Erro ao atualizar situação do curso do aluno.");
    }

    db_fim_transacao( $lErroTransacao );
    db_msgbox("Alterada situação da matrícula com sucesso!");
  } catch (Exception $eErro) {

    db_msgbox($eErro->getMessage());
    db_fim_transacao(true);
  }

  db_redireciona("edu1_matricula002.php?chavepesquisa=$ed60_i_turma");
  exit;
} else if (isset($chavepesquisa)) {

  try {

    $db_opcao = 2;
    $db_botao = false;
    $camp = "turma.*,
             calendario.*,
             base.*,
             cursoedu.*,
             turno.*,
             fc_nomeetapaturma(ed57_i_codigo) as nometapa,
             fc_codetapaturma(ed57_i_codigo) as codetapa
            ";

    $sSqlTurma = $clturma->sql_query( "", $camp, "", " ed57_i_codigo = {$chavepesquisa}" );
    $result    = db_query( $sSqlTurma );

    if( !is_resource( $result ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_EDU1_MATRICULA002 . 'erro_buscar_turma', $oErro ) );
    }

    if( pg_num_rows( $result ) == 0 ) {
      throw new BusinessException( _M( MENSAGENS_EDU1_MATRICULA002 . 'nenhuma_turma_encontrada' ) );
    }

    db_fieldsmemory( $result, 0 );

    $ed60_i_turma    = $ed57_i_codigo;
    $sWhereMatricula = " ed60_i_turma = $ed60_i_turma AND ed60_c_situacao = 'MATRICULADO'";
    $sSqlMatricula   = $clmatricula->sql_query_file( "", " count(*) ", "", $sWhereMatricula );
    $result1         = $clmatricula->sql_record( $sSqlMatricula );

    if( !is_resource( $result1 ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_EDU1_MATRICULA002 . 'erro_buscar_total_matriculas', $oErro ) );
    }

    $ed57_i_nummatr = 0;

    if( pg_num_rows( $result1 ) > 0 ) {

      db_fieldsmemory( $result1, 0 );
      $ed57_i_nummatr = $count;
    }
  } catch (Exception $eErro) {

    db_msgbox($eErro->getMessage());
    db_fim_transacao(true);
  }

  ?>
  <script>
   parent.document.formaba.a2.disabled    = false;
   parent.document.formaba.a2.style.color = "black";
   (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_a2.location.href='edu1_alunoturma001.php?ed60_i_turma=<?=$ed57_i_codigo?>&ed57_c_descr=<?=$ed57_c_descr?>&ed52_c_descr=<?=$ed52_c_descr?>';
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
<script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
<script type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script type="text/javascript" src="scripts/classes/educacao/DBViewFormularioEducacao.classe.js"></script>
<script type="text/javascript" src="scripts/classes/educacao/escola/TurmaTurnoReferente.classe.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default" >
  <?MsgAviso(db_getsession("DB_coddepto"), "escola");?>
     <?include(modification("forms/db_frmmatricula.php"));?>
</body>
</html>
<?
if ( isset( $alterar ) ) {

  if ( $clmatricula->erro_status == "0" ) {

    $clmatricula->erro(true,false);
    $db_botao = true;

    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
    if ( $clmatricula->erro_campo != "" ) {

      echo "<script> document.form1.".$clmatricula->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$clmatricula->erro_campo.".focus();</script>";
    }
  }
}

if ( $db_opcao == 22 ) {
  echo "<script>js_pesquisaed60_i_turma();</script>";
}
?>