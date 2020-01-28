<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_levantamentopatrimonial_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllevantamentopatrimonial = new cl_levantamentopatrimonial;
$cllevantamentopatrimonial->rotulo->label("p13_sequencial");
$cllevantamentopatrimonial->rotulo->label("p13_departamento");
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
          <td><label><?=$Lp13_sequencial?></label></td>
          <td><? db_input("p13_sequencial",10,$Ip13_sequencial,true,"text",4,"","chave_p13_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lp13_departamento?></label></td>
          <td><? db_input("p13_departamento",10,$Ip13_departamento,true,"text",4,"","chave_p13_departamento");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_levantamentopatrimonial.hide();">
  </form>
      <?
      if (!isset($pesquisa_chave)) {

        if (isset($campos) == false) {

          if(file_exists("funcoes/db_func_levantamentopatrimonial.php") == true) {
            include("funcoes/db_func_levantamentopatrimonial.php");
          } else {
            $campos = "p13_sequencial, p13_departamento, descrdepto, p13_data";
          }
        }
        if (isset($chave_p13_sequencial) && (trim($chave_p13_sequencial) != "")) {
          $sql = $cllevantamentopatrimonial->sql_query($chave_p13_sequencial, $campos, "p13_sequencial");
        } else if (isset($chave_p13_departamento) && (trim($chave_p13_departamento) != "")) {
          $sql = $cllevantamentopatrimonial->sql_query("", $campos, "p13_sequencial", " p13_departamento = {$chave_p13_departamento} ");
        } else {
          $sql = $cllevantamentopatrimonial->sql_query("", $campos, "p13_sequencial", "");
        }
        $repassa = array();
        if (isset($chave_p13_sequencial)) {
          $repassa = array("chave_p13_sequencial" => $chave_p13_sequencial, "chave_p13_sequencial" => $chave_p13_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {

        if ($pesquisa_chave != null && $pesquisa_chave != "") {

          $sWhere = " p13_departamento = {$pesquisa_chave} ";
          $result = $cllevantamentopatrimonial->sql_record($cllevantamentopatrimonial->sql_query(null, "descrdepto", null, $sWhere));
          if ($cllevantamentopatrimonial->numrows != 0) {

            db_fieldsmemory($result, 0);
            echo "<script>".$funcao_js."('$descrdepto', false);</script>";
          } else {
            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
          echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_p13_sequencial",true,1,"chave_p13_sequencial",true);
</script>
