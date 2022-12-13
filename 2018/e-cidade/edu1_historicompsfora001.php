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

db_postmemory( $_POST );

$oDaoHistoricoMpsFora = new cl_historicompsfora();
$oDaoHistorico        = new cl_historico();
$oDaoMatricula        = new cl_matricula();

$db_opcao             = 1;
$db_opcao1            = 1;
$sSqlHistorico        = $oDaoHistorico->sql_query("", "ed61_i_escola", "", " ed61_i_codigo = {$ed99_i_historico}");
$rsHistorico          = $oDaoHistorico->sql_record($sSqlHistorico);

db_fieldsmemory($rsHistorico, 0);
$db_botao = true;
$erro     = false;

if (isset($incluir)) {

  $sCampos       = " aluno.ed47_v_nome as nome, escola.ed18_c_nome as nomeescola, turma.ed57_c_descr as nometurma,  ";
  $sCampos      .= " serie.ed11_c_descr as nomeserie, calendario.ed52_c_descr as nomecal";
  $sWhere        = " ed60_i_aluno = {$ed61_i_aluno} AND matriculaserie.ed221_i_serie = {$ed99_i_serie} ";
  $sWhere       .= " AND ed60_c_situacao = 'MATRICULADO' AND ed60_c_concluida = 'N'";
  $sSqlMatricula = $oDaoMatricula->sql_query("", $sCampos, "", $sWhere);
  $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);

  if ($oDaoMatricula->numrows > 0 && $ed99_c_situacao != 'TRANSFERIDO' && $ed99_c_resultadofinal != 'R') {

    db_fieldsmemory($rsMatricula, 0);
    $sMsg  = "Inclusão não permitida.\\nAluno {$nome} está cursando";
    $sMsg .= " a etapa {$nomeserie} \\nTurma {$nometurma} Calendário {$nomecal} \\nEscola {$nomeescola}";
    db_msgbox($sMsg);
    $erro = true;
  } else {

    $oEtapa = EtapaRepository::getEtapaByCodigo( $ed99_i_serie );
    $oAluno = AlunoRepository::getAlunoByCodigo( $ed61_i_aluno );
    $erro   = HistoricoEscolar::temInconsistenciaEtapaSelecionada( $oEtapa, $oAluno, $ed99_i_anoref, $ed99_c_resultadofinal );

    if( !$erro ) {

      $sCamposHistMpsFora   = " ed99_i_codigo, ed99_i_anoref as anoref, ed99_i_periodoref as periodo, ";
      $sCamposHistMpsFora  .= " ed11_c_descr as nomeserie";
      $sWhereHistMpsFora    = " ed61_i_aluno = {$ed61_i_aluno} AND ed99_i_serie = {$ed99_i_serie} ";
      $sWhereHistMpsFora   .= " AND ed99_i_anoref = {$ed99_i_anoref} ";
      $sSqlHistoricoMpsFora = $oDaoHistoricoMpsFora->sql_query("", $sCamposHistMpsFora, "", $sWhereHistMpsFora);
      $rsHistoricoMpsFora   = $oDaoHistoricoMpsFora->sql_record($sSqlHistoricoMpsFora);

      if ($oDaoHistoricoMpsFora->numrows > 0) {

        db_fieldsmemory($rsHistoricoMpsFora, 0);
        db_msgbox("Já existe etapa {$nomeserie} com o ano {$ed99_i_anoref} \\npara o aluno {$ed47_v_nome} !");
        $erro = true;
      } else {

        if ($ed99_c_situacao == "AMPARADO") {
          $ed99_c_resultadofinal = "A";
        } elseif ( $ed99_c_situacao == "CONCLUÍDO" || $ed99_c_situacao == "RECLASSIFICADO" ) {
          $ed99_c_resultadofinal = $ed99_c_resultadofinal;
        } else {
          $ed99_c_resultadofinal = "R";
        }

        db_inicio_transacao();
        $oDaoHistoricoMpsFora->ed99_percentualfrequencia = "{$ed99_percentualfrequencia}";
        $oDaoHistoricoMpsFora->ed99_c_resultadofinal     = $ed99_c_resultadofinal;
        $oDaoHistoricoMpsFora->ed99_observacao           = "{$ed99_observacao}";
        $oDaoHistoricoMpsFora->incluir($ed99_i_codigo);
        db_fim_transacao();
      }
    }
  }
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
<script>
js_tabulacaoforms("form1", "ed99_i_serie", true, 1, "ed99_i_serie", true);
parent.disciplina.location.href = "edu1_historicodisciplinafora.php?ed100_i_historicompsfora=0";
</script>
<?php
if (isset($incluir) && $erro == false) {

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
     location.href               = "edu1_histmpsdiscfora001.php?ed100_i_historicompsfora=<?=$oDaoHistoricoMpsFora->ed99_i_codigo?>&ed99_c_situacao="+document.getElementById('ed99_c_situacao').value;
     parent.arvore.location.href = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
    </script>
    <?
  }
}