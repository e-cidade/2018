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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_empnotasuspensao_classe.php"));
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempnotasuspensao = new cl_empnotasuspensao;
$clempnotasuspensao->rotulo->label("cc36_sequencial");
$clempnotasuspensao->rotulo->label("cc36_sequencial");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
<form name="form2" method="post" action="" class="container">
  <fieldset>
    <legend>Dados para Pesquisa</legend>
    <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
      <tr>
        <td><label><?= $Lcc36_sequencial ?></label></td>
        <td><? db_input("cc36_sequencial", 10, $Icc36_sequencial, true, "text", 4, "", "chave_cc36_sequencial"); ?></td>
      </tr>
      <tr>
        <td><label><?= $Lcc36_sequencial ?></label></td>
        <td><? db_input("cc36_sequencial", 10, $Icc36_sequencial, true, "text", 4, "", "chave_cc36_sequencial"); ?></td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar">
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empnotasuspensao.hide();">
</form>
<?php
if (!isset($pesquisa_chave)) {
  if (isset($campos) == false) {
    if (file_exists("funcoes/db_func_empnotasuspensao.php") == true) {
      include(modification("funcoes/db_func_empnotasuspensao.php"));
    } else {
      $campos = "empnotasuspensao.*";
    }
  }
  if (isset($chave_cc36_sequencial) && (trim($chave_cc36_sequencial) != "")) {
    $sql = $clempnotasuspensao->sql_query($chave_cc36_sequencial, $campos, "cc36_sequencial");
  } else if (isset($chave_cc36_sequencial) && (trim($chave_cc36_sequencial) != "")) {
    $sql = $clempnotasuspensao->sql_query("",
                                          $campos,
                                          "cc36_sequencial",
                                          " cc36_sequencial like '$chave_cc36_sequencial%' ");
  } else {
    $sql = $clempnotasuspensao->sql_query("", $campos, "cc36_sequencial", "");
  }
  $repassa = array();
  if (isset($chave_cc36_sequencial)) {
    $repassa = array(
      "chave_cc36_sequencial" => $chave_cc36_sequencial, "chave_cc36_sequencial" => $chave_cc36_sequencial
    );
  }
  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
  db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
  echo '  </fieldset>';
  echo '</div>';
} else {
  if ($pesquisa_chave != null && $pesquisa_chave != "") {
    $result = $clempnotasuspensao->sql_record($clempnotasuspensao->sql_query($pesquisa_chave));
    if ($clempnotasuspensao->numrows != 0) {
      db_fieldsmemory($result, 0);
      echo "<script>" . $funcao_js . "('$cc36_sequencial',false);</script>";
    } else {
      echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
    }
  } else {
    echo "<script>" . $funcao_js . "('',false);</script>";
  }
}
?>
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
  js_tabulacaoforms("form2", "chave_cc36_sequencial", true, 1, "chave_cc36_sequencial", true);
</script>
