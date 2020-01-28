<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_rhcontasrec_classe.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clrhcontasrec = new cl_rhcontasrec();
$clrhcontasrec->rotulo->label("rh41_conta");
$clrhcontasrec->rotulo->label("rh41_codigo");
$clrhcontasrec->rotulo->label("rh41_instit");
$clrhcontasrec->rotulo->label("rh41_anousu");
$clrhcontasrec->rotulo->label("rh41_codigo");

if (isset($chave_rh41_conta) && !DBNumber::isInteger($chave_rh41_conta)) {
  $chave_rh41_conta = '';
}

if (isset($chave_rh41_codigo) && !DBNumber::isInteger($chave_rh41_codigo)) {
  $chave_rh41_codigo = '';
}

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
      <table width="35%" border="0" align="center" cellspacing="0">
        <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Trh41_conta; ?>">
              <?php echo $Lrh41_conta; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_input("rh41_conta", 5, $Irh41_conta, true, "text", 4, "", "chave_rh41_conta");
              ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?php echo $Trh41_codigo; ?>">
              <?php echo $Lrh41_codigo; ?>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_input("rh41_codigo", 5, $Irh41_codigo, true, "text", 4, "", "chave_rh41_codigo");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhcontasrec.hide();">
            </td>
          </tr>
        </form>
      </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php

      if (!isset($pesquisa_chave)) {

        if (!isset($campos)) {

          if (file_exists("funcoes/db_func_rhcontasrec.php")) {
            include(modification("funcoes/db_func_rhcontasrec.php"));
          } else {
            $campos = "rhcontasrec.*";
          }
        }

        if (isset($chave_rh41_conta) && (trim($chave_rh41_conta) != "")) {
           $sql = $clrhcontasrec->sql_query_contas($chave_rh41_conta,$chave_rh41_codigo, db_getsession("DB_instit"), db_getsession("DB_anousu"),$campos, "rh41_conta");
        } else if (isset($chave_rh41_codigo) && (trim($chave_rh41_codigo)!="") ){
          $sql = $clrhcontasrec->sql_query_contas("","", db_getsession("DB_instit"), db_getsession("DB_anousu"), $campos, "rh41_codigo"," rh41_codigo like '$chave_rh41_codigo%' ");
        } else {
           $sql = $clrhcontasrec->sql_query_contas("","", db_getsession("DB_instit"), db_getsession("DB_anousu"), $campos, "rh41_conta#rh41_codigo#rh41_instit#rh41_anousu","");
        }

        $repassa = array();
        if (isset($chave_rh41_codigo)) {
          $repassa = array("chave_rh41_conta" => $chave_rh41_conta, "chave_rh41_codigo" => $chave_rh41_codigo);
        }
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {
          $pesquisa_chave2 = "";
          if(isset($recurso)){
            $pesquisa_chave2 = $pesquisa_chave;
            $pesquisa_chave = "";
          }

          $sSql   = $clrhcontasrec->sql_query_contas($pesquisa_chave2,$pesquisa_chave, db_getsession("DB_instit"), db_getsession("DB_anousu"),"*", "");
          $result = $clrhcontasrec->sql_record($sSql);
          if ($clrhcontasrec->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$o15_descr', false);</script>";
          } else {
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }

        } else {
          echo "<script>".$funcao_js."('',false);</script>";
        }
      }

      ?>
    </td>
  </tr>
</table>
</body>
</html>
<?php if (!isset($pesquisa_chave)) { ?>
  <script>
  </script>
<?php } ?>
<script>
  js_tabulacaoforms("form2", "chave_rh41_codigo", true, 1, "chave_rh41_codigo", true);
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
