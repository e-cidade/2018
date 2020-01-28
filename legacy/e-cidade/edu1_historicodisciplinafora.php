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

db_postmemory( $_POST );

$oDaoHistMpsDiscFora  = new cl_histmpsdiscfora();
$oDaoHistorico        = new cl_historico();
$oDaoHistoricompsFora = new cl_historicompsfora();
$oDaoDisciplina       = new cl_disciplina();

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/classes/educacao/escola/HistoricoEscolar.classe.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <?php
  $campos = "ed100_i_codigo,
             ed232_c_descr,
             ed100_c_situacao,
             case when ed100_c_situacao!='CONCLUÍDO' OR ed100_t_resultobtido = '' then '&nbsp;' else ed100_t_resultobtido end as ed100_t_resultobtido,
             ed100_c_resultadofinal,
             ed100_i_qtdch,
             ed100_c_tiporesultado,
             ed100_i_historicompsfora,
             ed29_c_descr,
             ed11_c_descr,
             ed11_i_ensino,
             ed11_i_sequencia,
             ed100_i_ordenacao,
             ed100_c_termofinal,
             ed100_basecomum";

  $sOrdenacao             = " ed100_basecomum desc, ed100_i_ordenacao";
  $sWhere                 = " ed100_i_historicompsfora = {$ed100_i_historicompsfora}";
  $sSql                   = $oDaoHistMpsDiscFora->sql_query( "", $campos, $sOrdenacao, $sWhere );
  $result                 = db_query( $sSql );
  $iLinhasHistMpsDiscFora = pg_num_rows( $result );

  if( is_resource( $result ) && $iLinhasHistMpsDiscFora == 0 ) {

    $sCampos = "ed29_c_descr, serie.ed11_c_descr";
    $sWhere  = " ed99_i_codigo = {$ed100_i_historicompsfora}";
    $sSql    = $oDaoHistoricompsFora->sql_query( "", $sCampos, "", $sWhere );
    $result  = db_query( $sSql );
  }

  if( is_resource( $result ) ) {

    $sEtapaCurso = "";

    if( pg_num_rows( $result ) > 0 ) {

      $oDadosHistorico = db_utils::fieldsMemory( $result, 0 );
      $sEtapaCurso     = "{$oDadosHistorico->ed11_c_descr} - {$oDadosHistorico->ed29_c_descr}";
    }
    ?>
    <table width="100%" border="1" cellspacing="0" cellpadding="0">
      <tr class='titulo'>
        <td colspan="8"><?=$sEtapaCurso?>
          <input name="ordenar" type="button" value="Ordenar Disciplinas" onclick="js_abrir();" id="btnOrdenarDisciplinas">
        </td>
      </tr>
      <tr class='titulo'>
        <td>Tipo da Base</td>
        <td>Disciplina</td>
        <td>Situação</td>
        <td>Aprov.</td>
        <td>RF</td>
        <td>CH</td>
        <td>TR</td>
        <td>TF</td>
      </tr>
      <?php
      if( $iLinhasHistMpsDiscFora > 0 ) {

        $cor1 = "#f3f3f3";
        $cor2 = "#DBDBDB";
        $cor  = "";

        for( $x = 0; $x < $iLinhasHistMpsDiscFora; $x++ ) {

          db_fieldsmemory( $result, $x );

          if( $cor == $cor1 ) {
            $cor = $cor2;
          } else {
            $cor = $cor1;
          }

          $ed100_t_resultobtido = isset( $ed100_t_resultobtido ) ? $ed100_t_resultobtido : "";

          if( isset( $ed100_c_situacao ) && trim( $ed100_c_situacao ) == "AMPARADO" ) {
            $ed100_t_resultobtido = "&nbsp;";
          }

          $ed100_basecomum == 't' ? $sBaseComum = "BASE COMUM" : $sBaseComum = "DIVERSIFICADA";
          ?>
          <tr height="18"
              bgcolor="<?=$cor?>"
              onclick="parent.dados.location.href='edu1_histmpsdiscfora002.php?ed100_i_historicompsfora=<?=$ed100_i_historicompsfora?>'"
              onmouseover="bgColor='#DEB887';"
              onmouseout="bgColor='<?=$cor?>';">
            <td class='aluno'><?php echo $sBaseComum; ?></td>
            <td class='aluno'><?=$ed232_c_descr?></td>
            <td class='aluno'><?=$ed100_c_situacao?></td>
            <td class='aluno' align="<?=$ed100_c_tiporesultado=='N'?'right':'center'?>"><?=$ed100_t_resultobtido?></td>
            <td class='aluno'><?=$ed100_c_resultadofinal=="R"?"REPROVADO":"APROVADO"?></td>
            <td class='aluno' align="right"><?=$ed100_i_qtdch?></td>
            <td class='aluno' align="right"><?=trim($ed100_c_tiporesultado)?></td>
            <td class='aluno' align="right"><?=trim($ed100_c_termofinal)?></td>
          </tr>
          <?php
        }
      } else {

        ?>
        <tr height="18" bgcolor="#f3f3f3">
          <td colspan="8" class="aluno" align="center">Nenhuma disciplina cadastrada para esta etapa.</td>
        </tr>
        <?php
      }
      ?>
    </table>
  <?php
  }
  ?>
</body>
</html>
<script>

function js_abrir() {

  parent.js_OpenJanelaIframe(
                              '',
                              'db_iframe_ordenar',
                              'edu1_baseorddischistmpsfora001.php?ed100_i_historicompsfora=<?=$ed100_i_historicompsfora?>',
                              'Ordenar Disciplinas ',
                              true,60,400,400,230
                            );
}

(function () {

  var iEnsino                    = "<?php echo isset( $ed11_i_ensino ) ? $ed11_i_ensino : '';?>",
      iSequenciaEtapa            = "<?php echo isset( $ed11_i_sequencia ) ? $ed11_i_sequencia : '';?>",
      oDadosManutencaoHistorico  = CurrentWindow.corpo.oDadosManutencaoHistorico,
      iStatusManutencaoHistorico = oDadosManutencaoHistorico.iStatusAlteracaoHistorico,
      iOrdemEtapaAtual           = oDadosManutencaoHistorico.aSenquenciaEtapas[iEnsino],
      oHistoricoEscolar          = new HistoricoEscolar(iStatusManutencaoHistorico, iOrdemEtapaAtual, iSequenciaEtapa);

  if( !oHistoricoEscolar.permiteManutencao() ) {
    $('btnOrdenarDisciplinas').disabled = true;
  }

})();

</script>