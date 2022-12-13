<?
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_concilia_classe.php"));
db_postmemory($_POST);
$clconcilia = new cl_concilia();
$clconcilia->rotulo->label("k68_sequencial");
$clconcilia->rotulo->label("k68_contabancaria");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="">
          <tr>
            <td width="4%" align="right" nowrap title="<?= $Tk68_sequencial ?>">
              <?= $Lk68_sequencial ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
              db_input("k68_sequencial", 10, $Ik68_sequencial, true, "text", 4, "", "chave_k68_sequencial");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?= $Tk68_contabancaria ?>">
              <?= $Lk68_contabancaria ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php

              $sCamposContas = "distinct db83_sequencial , db83_descricao || '  Conta:' || db83_conta ||'-'|| db83_dvconta";
              $sWhereContas  = "db83_contaplano is true and c61_instit = " . db_getsession('DB_instit');
              $sOrderContas  = "db83_sequencial";

              $oDaoContas = new cl_contabancaria();
              $sql_contas = $oDaoContas->sql_query_planocontas(null, $sCamposContas, $sOrderContas, $sWhereContas);
              $record_contas = pg_query($sql_contas);
              db_selectrecord("db83_sequencial", $record_contas, true, 2, "", "", "", "", "document.form2.submit()");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_concilia.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

      $iInstituicao = db_getsession('DB_instit');

      $aWhere   = array();
      $aWhere[] = "c61_instit = {$iInstituicao}";

      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

          if (file_exists("funcoes/db_func_concilia.php") == true) {
            include(modification("funcoes/db_func_concilia.php"));
          } else {
            $campos = "concilia.*";
          }
        }

        $campos .= ", case when k68_data = (select min(x.k68_data) from concilia as x where x.k68_contabancaria = k68_contabancaria)
                          then 'S'
                          else 'N'
                      end as implantacao ";

        $sOrder = "k68_sequencial";
        if (isset($chave_k68_sequencial) && (trim($chave_k68_sequencial) != "")) {
          $aWhere[] = "k68_sequencial = {$chave_k68_sequencial}";
        } else if (isset($db83_sequencial) && $db83_sequencial != 0) {

          $sOrder = "k68_data desc";
          $sWhere[] = "k68_contabancaria = {$db83_sequencial}";
        }

        $sCampos = "distinct {$campos}";
        $sWhere = implode(" and ", $aWhere);
        $sql    = $clconcilia->sql_query(null, $sCampos, $sOrder, $sWhere);

        $repassa = array();

        if (isset($db83_sequencial)) {
          $repassa = array("k68_sequencial" => $k68_sequencial, "db83_sequencial" => $db83_sequencial);
        }

        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $aWhere[] = "k68_sequencial = {$chave_k68_sequencial}";
          $sWhere   = implode(" and ", $aWhere);

          $result = $clconcilia->sql_record($clconcilia->sql_query(null, "*", null, $sWhere));
          if ($clconcilia->numrows != 0) {
            db_fieldsmemory($result, 0);
            echo "<script>" . $funcao_js . "('$k68_data',false);</script>";
          } else {
            echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
          }

        } else {
          echo "<script>" . $funcao_js . "('',false);</script>";
        }
      }
      ?>
    </td>
  </tr>
</table>
</body>
</html>
<?
if (!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>
<script>
  js_tabulacaoforms("form2", "chave_k68_data", true, 1, "chave_k68_data", true);
</script>
<script type="text/javascript">
  (function () {
    var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector(
      'input[value="Fechar"]'
    );
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  })();
</script>
