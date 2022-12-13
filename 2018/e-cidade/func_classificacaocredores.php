<?php
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("classes/db_classificacaocredores_classe.php");

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$oPost = db_utils::postMemory($_POST);
$Lcc30_codigo = null;
$Icc30_codigo = null;
$Lcc30_descricao = null;
$Icc30_descricao = null;

$oDaoClassificacaoCredores = new cl_classificacaocredores;
$oDaoClassificacaoCredores->rotulo->label("cc30_codigo");
$oDaoClassificacaoCredores->rotulo->label("cc30_descricao");
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
        <td><label for="chave_cc30_codigo"><?php echo $Lcc30_codigo ?></label></td>
        <td><?php db_input("cc30_codigo", 10, $Icc30_codigo, true, "text", 4, "", "chave_cc30_codigo"); ?></td>
      </tr>
      <tr>
        <td><label for="cc30_descricao"><?php echo $Lcc30_descricao ?></label></td>
        <td>
          <?php
          $cc30_descricao = !empty($cc30_descricao) ? htmlentities(stripslashes($cc30_descricao), ENT_QUOTES, 'ISO-8859-1') : '';
          db_input("cc30_descricao", 50, $Icc30_descricao, true, "text", 4);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_classificacaocredores.hide();">
</form>
<?php
if(!isset($pesquisa_chave)) {

  $aCampos = array(
    "cc30_codigo",
    "cc30_descricao",
    "cc30_dispensa"
  );
  $campos = implode(',', $aCampos);

  if ( !empty($oPost->chave_cc30_codigo) ) {
    $sSql = $oDaoClassificacaoCredores->sql_query($oPost->chave_cc30_codigo, $campos, "cc30_codigo");
  } else if (!empty($oPost->cc30_descricao)) {
    $sSql = $oDaoClassificacaoCredores->sql_query(null, $campos, "cc30_codigo", " cc30_descricao ilike '$oPost->cc30_descricao%' ");
  }else{
    $sSql = $oDaoClassificacaoCredores->sql_query(null, $campos, "cc30_codigo");
  }

  $repassa = array();
  if(isset($chave_cc30_codigo)) {

    $repassa = array(
      "chave_cc30_codigo" => $chave_cc30_codigo,
      "cc30_descricao"    => $oPost->cc30_descricao,
    );
  }

  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
  db_lovrot($sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa);
  echo '  </fieldset>';
  echo '</div>';
} else {

  if($pesquisa_chave != null && $pesquisa_chave != ""){

    $sSql        = $oDaoClassificacaoCredores->sql_query(null, "*", null, "cc30_codigo = {$pesquisa_chave}");
    $rsResultado = $oDaoClassificacaoCredores->sql_record($sSql);
    if($oDaoClassificacaoCredores->numrows != 0){

      db_fieldsmemory($rsResultado, 0);
      echo "<script>" . $funcao_js . "('$cc30_descricao', false);</script>";
    } else {
      echo "<script>" . $funcao_js . "('Chave(".$pesquisa_chave.") não Encontrado', true);</script>";
    }
  } else {
    echo "<script>" . $funcao_js . "('',false);</script>";
  }
}
?>
<script>
  js_tabulacaoforms("form2","chave_cc30_codigo",true,1,"chave_cc30_codigo",true);
</script>
</body>
</html>
