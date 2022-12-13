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
require_once(modification("libs/db_utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oDaoHistoricoMps = new cl_historicomps();
$oDaoHistorico    = new cl_historico();
$oDaoMatricula    = new cl_matricula();

db_postmemory( $_POST );
db_postmemory( $_GET  );

$db_opcao         = 1;
$db_opcao1        = 1;
$ed62_i_historico = $ed62_i_historico;
$sCamposHistorico = "ed61_i_escola, ed10_i_codigo as ed11_i_ensino";
$sSqlHistorico    = $oDaoHistorico->sql_query("", $sCamposHistorico, "", " ed61_i_codigo = $ed62_i_historico");
$rsHistorico      = $oDaoHistorico->sql_record($sSqlHistorico);
db_fieldsmemory( $rsHistorico, 0 );

$db_botao = true;
$erro     = false;

if (isset($incluir)) {

  $sCampos       = " aluno.ed47_v_nome as nome,escola.ed18_c_nome as nomeescola,turma.ed57_c_descr as nometurma, ";
  $sCampos      .= " serie.ed11_c_descr as nomeserie,calendario.ed52_c_descr as nomecal";
  $sWhere        = " ed60_i_aluno = $ed61_i_aluno AND matriculaserie.ed221_i_serie = $ed62_i_serie";
  $sWhere       .= " AND ed60_c_situacao = 'MATRICULADO' AND ed60_c_concluida = 'N'";
  $sSqlMatricula = $oDaoMatricula->sql_query("",$sCampos,"",$sWhere);
  $rsMatricula   = $oDaoMatricula->sql_record($sSqlMatricula);

  if ($oDaoMatricula->numrows > 0 && $ed62_c_situacao != 'TRANSFERIDO' && $ed62_c_resultadofinal != 'R') {

    db_fieldsmemory($rsMatricula,0);
    $sMsg  = " Inclusão não permitida.\\nAluno $nome está cursando a etapa";
    $sMsg .= " $nomeserie \\nTurma $nometurma Calendário $nomecal \\nEscola $nomeescola";
    db_msgbox($sMsg);
    $erro = true;
  } else {

    $oEtapa = EtapaRepository::getEtapaByCodigo( $ed62_i_serie );
    $oAluno = AlunoRepository::getAlunoByCodigo( $ed61_i_aluno );
    $erro   = HistoricoEscolar::temInconsistenciaEtapaSelecionada( $oEtapa, $oAluno, $ed62_i_anoref, $ed62_c_resultadofinal );

    if( !$erro ) {

      $sCamposHistMps   = "ed62_i_codigo,ed62_i_anoref as anoref,ed62_i_periodoref as periodo,ed11_c_descr as nomeserie";
      $sWhereHistMps    = " ed61_i_aluno = $ed61_i_aluno AND ed62_i_serie = $ed62_i_serie ";
      $sWhereHistMps   .= " AND ed62_i_anoref = $ed62_i_anoref ";
      $sSqlHistoricoMps = $oDaoHistoricoMps->sql_query("",$sCamposHistMps,"",$sWhereHistMps);
      $rsHistoricoMps   = $oDaoHistoricoMps->sql_record($sSqlHistoricoMps);

      if ($oDaoHistoricoMps->numrows > 0) {

        db_fieldsmemory($rsHistoricoMps,0);
        db_msgbox("Já existe etapa $nomeserie com o ano $ed62_i_anoref \\npara o aluno $ed47_v_nome !");
        $erro = true;
      } else {

        if ($ed62_c_situacao == "AMPARADO") {
          $ed62_c_resultadofinal = "A";
        } else if ( $ed62_c_situacao == "CONCLUÍDO" || $ed62_c_situacao == "RECLASSIFICADO" ) {
          $ed62_c_resultadofinal = $ed62_c_resultadofinal;
        } else {
          $ed62_c_resultadofinal = "R";
        }

        db_inicio_transacao();
        $oDaoHistoricoMps->ed62_lancamentoautomatico = 'false';
        $oDaoHistoricoMps->ed62_percentualfrequencia = "{$ed62_percentualfrequencia}";
        $oDaoHistoricoMps->ed62_c_resultadofinal     = $ed62_c_resultadofinal;
        $oDaoHistoricoMps->ed62_observacao           = "{$ed62_observacao}";
        $oDaoHistoricoMps->incluir($ed62_i_codigo);
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
  <script language="JavaScript" type="text/javascript" src="scripts/classes/educacao/escola/HistoricoEscolar.classe.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
 </head>
 <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
   <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
     <center>
      <fieldset style="width:95%;"><legend><b>Etapa cursada na Rede Municipal</b></legend>
       <?php include(modification("forms/db_frmhistoricomps.php")); ?>
      </fieldset>
     </center>
    </td>
   </tr>
  </table>
 </body>
</html>
<script>
js_tabulacaoforms("form1","ed62_i_historico",true,1,"ed62_i_historico",true);
parent.disciplina.location.href = "edu1_historicodisciplina.php?ed65_i_historicomps=0";
</script>
<?php
if (isset($incluir) && $erro == false) {

  if ($oDaoHistoricoMps->erro_status == "0") {

    $oDaoHistoricoMps->erro(true,false);
    $db_botao=true;
    echo "<script> document.form1.db_opcao.disabled=false;</script>  ";

    if ($oDaoHistoricoMps->erro_campo != "") {

      echo "<script> document.form1.".$oDaoHistoricoMps->erro_campo.".style.backgroundColor='#99A9AE';</script>";
      echo "<script> document.form1.".$oDaoHistoricoMps->erro_campo.".focus();</script>";
    }
  } else {

    $oDaoHistoricoMps->erro(true,false);
    $result = db_query("select last_value from historicomps_ed62_i_codigo_seq");
    $ultimo = pg_result($result,0,0);
    ?>
    <script>
     location.href               = "edu1_histmpsdisc001.php?ed65_i_historicomps=<?=$ultimo?>&ed62_c_situacao="+document.getElementById('ed62_c_situacao').value;
     parent.arvore.location.href = "edu1_historicoarvore.php?ed61_i_aluno=<?=$ed61_i_aluno?>&ed47_v_nome=<?=$ed47_v_nome?>";
    </script>
    <?
  }
}
?>