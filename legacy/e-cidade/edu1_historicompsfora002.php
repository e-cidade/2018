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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_utils.php"));

parse_str( $_SERVER["QUERY_STRING"] );
db_postmemory( $_POST );

$oDaoHistoricoMpsFora = new cl_historicompsfora();
$oDaoAlunoCurso       = new cl_alunocurso();
$oDaoHistMpsDiscFora  = new cl_histmpsdiscfora();
$oDaoMatricula        = new cl_matricula();

$db_opcao           = 2;
$db_opcao1          = 3;
$db_botao           = false;
$lConfirmouExclusao = false;
$lErro              = false;

if (!isset($ed61_i_aluno) && isset($ed99_i_historico)) {

  $oDaoHistorico = new cl_historico();
  $sSqlHistorico = $oDaoHistorico->sql_query_file($ed99_i_historico, 'ed61_i_aluno');
  $rsHistorico   = $oDaoHistorico->sql_record($sSqlHistorico);
  if ($oDaoHistorico->numrows == 1) {
    $ed61_i_aluno = db_utils::fieldsMemory($rsHistorico, 0)->ed61_i_aluno;
  }

  $oDaoAluno = new cl_aluno();
  $sSqlAluno = $oDaoAluno->sql_query_file($ed61_i_aluno, 'ed47_v_nome');
  $rsAluno   = $oDaoAluno->sql_record($sSqlAluno);
  if ($oDaoAluno->numrows == 1) {
    $ed47_v_nome = db_utils::fieldsMemory($rsAluno, 0)->ed47_v_nome;
  }
}

if (isset($alterar)) {

  $vinculo  = false;
  $db_botao = true;

  $sCampos       = " aluno.ed47_v_nome as nome, escola.ed18_c_nome as nomeescola, turma.ed57_c_descr as nometurma,  ";
  $sCampos      .= " serie.ed11_c_descr as nomeserie, calendario.ed52_c_descr as nomecal";
  $sWhere        = " ed60_i_aluno = {$ed61_i_aluno} AND matriculaserie.ed221_i_serie = {$ed99_i_serie} ";
  $sWhere       .= " AND ed60_c_situacao = 'MATRICULADO' AND ed60_c_concluida = 'N'";
  $sSqlMatricula = $oDaoMatricula->sql_query("", $sCampos, "", $sWhere);
  $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);

  if ($oDaoMatricula->numrows > 0 && $ed99_c_situacao != 'TRANSFERIDO' && $ed99_c_resultadofinal != 'R') {

    db_fieldsmemory($rsMatricula, 0);
    $sMsg  = "Alteração não permitida.\\nAluno {$nome} está cursando";
    $sMsg .= " a etapa {$nomeserie} \\nTurma {$nometurma} Calendário {$nomecal} \\nEscola {$nomeescola}";
    db_msgbox($sMsg);
    $lErro = true;
  } else {

    if ($ed99_c_situacao == "AMPARADO") {

      $ed99_c_resultadofinal = "A";

      if (DiscVinc($ed99_i_codigo) > 0) {
        $vinculo = true;
      }
    } else if ( $ed99_c_situacao == "CONCLUÍDO" || $ed99_c_situacao == "RECLASSIFICADO" ) {
      $ed99_c_resultadofinal = $ed99_c_resultadofinal;
    } else{

      $ed99_c_resultadofinal = "R";
      if (DiscVinc($ed99_i_codigo) > 0) {
        $vinculo = true;
      }
    }

    if ($vinculo == true) {
      db_msgbox("Etapa com situação diferente de CONCLUÍDO ou RECLASSIFICADO não deve ter disciplinas vinculadas!");
    } else {

      db_inicio_transacao();
      $db_opcao  = 2;
      $db_opcao1 = 3;
      $oDaoHistoricoMpsFora->ed99_percentualfrequencia = "{$ed99_percentualfrequencia}";
      $oDaoHistoricoMpsFora->ed99_c_resultadofinal     = $ed99_c_resultadofinal;
      $oDaoHistoricoMpsFora->ed99_observacao           = "{$ed99_observacao}";
      $oDaoHistoricoMpsFora->alterar($ed99_i_codigo);
      db_fim_transacao();
    }
  }
} else if (isset($chavepesquisa)) {

  $db_opcao             = 2;
  $db_opcao1            = 3;
  $sSqlHistoricoMpsFora = $oDaoHistoricoMpsFora->sql_query($chavepesquisa);
  $rsHistMpsFora        = $oDaoHistoricoMpsFora->sql_record($sSqlHistoricoMpsFora);
  db_fieldsmemory($rsHistMpsFora, 0);

  $sSqlAlunoCurso = $oDaoAlunoCurso->sql_query("", "ed56_c_situacao", "", " ed56_i_aluno = $ed61_i_aluno");
  $rsAlunoCurso   = $oDaoAlunoCurso->sql_record($sSqlAlunoCurso);

  if ($oDaoAlunoCurso->numrows > 0) {

    db_fieldsmemory($rsAlunoCurso, 0);
    $situacao = $ed56_c_situacao == "CONCLUÍDO" ? "CONCLUÍDO" : "EM ANDAMENTO";
  } else {
    $situacao = "CADASTRADO";
  }

  $db_botao = true;
}

if ( isset($excluir) || ( isset ( $lExcluir ) && $lExcluir ) ) {

  db_inicio_transacao();

  $db_opcao           = 3;
  $lConfirmouExclusao = true;

  $oDaoHistMpsDiscFora->excluir( null, "ed100_i_historicompsfora = {$ed99_i_codigo}" );
  $oDaoHistoricoMpsFora->excluir($ed99_i_codigo);

  db_fim_transacao();
}
?>
<html>
 <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/classes/educacao/escola/HistoricoEscolar.classe.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <center>
      <fieldset style="width:95%;"><legend><b>Etapa cursada fora da Rede Municipal</b></legend>
       <?include(modification("forms/db_frmhistoricompsfora.php"));?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<?php
if (isset($alterar) && !$lErro) {

  if ($oDaoHistoricoMpsFora->erro_status == "0") {

    $oDaoHistoricoMpsFora->erro(true, false);
    $db_botao = true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoHistoricoMpsFora->erro_campo != "") {

      echo "<script> document.form1.".$oDaoHistoricoMpsFora->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoHistoricoMpsFora->erro_campo.".focus();</script>";
    }
  } else {

    $oDaoHistoricoMpsFora->erro(true, false);
    ?>
    <script>
     location.href = "edu1_histmpsdiscfora002.php?ed100_i_historicompsfora=<?=$ed99_i_codigo?>";
     parent.arvore.location.href = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
    </script>
    <?
  }
}

if ( isset($excluir) || $lConfirmouExclusao ) {

  if ($oDaoHistoricoMpsFora->erro_status == "0") {
    $oDaoHistoricoMpsFora->erro(true, false);
  } else {

    $oDaoHistoricoMpsFora->erro(true, false);
    ?>
    <script>
     location.href               = "edu1_historicompsfora001.php?ed99_i_historico=<?=$ed99_i_historico?>"+
                                   "&ed29_c_descr=<?=$ed29_c_descr?>&ed29_i_codigo=<?=$ed29_i_codigo?>"+
                                   "&ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
     parent.arvore.location.href = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>"+
                                   "&ed47_v_nome=<?=$ed47_v_nome?>";
    </script>
    <?
  }
}

function DiscVinc($ed99_i_codigo) {

  $sSql          = " SELECT ed100_i_codigo FROM histmpsdiscfora WHERE ed100_i_historicompsfora = $ed99_i_codigo ";
  $rsHistMpsFora = db_query($sSql);
  $iLinhas       = pg_num_rows($rsHistMpsFora);
  return $iLinhas;
}