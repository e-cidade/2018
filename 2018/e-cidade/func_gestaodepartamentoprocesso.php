<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_gestaodepartamentoprocesso_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clgestaodepartamentoprocesso = new cl_gestaodepartamentoprocesso;
$clgestaodepartamentoprocesso->rotulo->label("p103_db_depart");
$clgestaodepartamentoprocesso->rotulo->label("p103_sequencial");
$iUsuario = db_getsession("DB_id_usuario");

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
                <td><label><?= $Lp103_db_depart ?></label></td>
                <td><? db_input("p103_db_depart", 10, $Ip103_db_depart, true, "text", 4, "",
                        "chave_p103_db_depart"); ?></td>
            </tr>
        </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar">
    <input name="Fechar" type="button" id="fechar" value="Fechar"
           onClick="parent.db_iframe_gestaodepartamentoprocesso.hide();">
</form>
<?
$sOrdenacao = 'p103_db_depart';

if (isset($campos) == false) {
    if (file_exists("funcoes/db_func_gestaodepartamentoprocesso.php") == true) {
        include(modification("funcoes/db_func_gestaodepartamentoprocesso.php"));
    } else {
        $campos = "gestaodepartamentoprocesso.*";
    }
}

if (isset($lSomenteDadosDepartamento)) {
    $campos = "distinct coddepto, descrdepto";
    $sOrdenacao = "descrdepto";
}


if (!isset($pesquisa_chave)) {
    $sWhere = "p103_db_usuarios = {$iUsuario}";
    if (!empty($pesquisa_chave)) {
        $sWhere .= " AND p103_db_depart = {$pesquisa_chave}";
    }

    if (isset($chave_p103_db_depart) && (trim($chave_p103_db_depart) != "")) {
        $sql = $clgestaodepartamentoprocesso->sql_query("", $campos, $sOrdenacao,
            "{$sWhere} AND p103_db_depart = {$chave_p103_db_depart} ");
    } else {
        $sql = $clgestaodepartamentoprocesso->sql_query("", $campos, $sOrdenacao, $sWhere);
    }

    $repassa = array();
    if (isset($chave_p103_db_depart)) {
        $repassa = array(
            "chave_p103_db_depart" => $chave_p103_db_depart,
            "chave_p103_db_depart" => $chave_p103_db_depart
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
        $sWhere = "p103_sequencial = {$pesquisa_chave}";
        if (isset($lSomenteDadosDepartamento)) {
            $campos = "distinct coddepto, descrdepto";
            $sOrdenacao = "descrdepto";
            $sWhere = "p103_db_depart = {$pesquisa_chave} AND p103_db_usuarios = {$iUsuario}";
        }

        $result = $clgestaodepartamentoprocesso->sql_record($clgestaodepartamentoprocesso->sql_query(null, $campos,
            $sOrdenacao, $sWhere));

        if ($clgestaodepartamentoprocesso->numrows != 0) {
            db_fieldsmemory($result, 0);

            if (isset($lSomenteDadosDepartamento)) {
                $sFuncaoRetorno = "<script>" . $funcao_js . "(false, $coddepto, '$descrdepto');</script>";
            } else {
                $sFuncaoRetorno = "<script>" . $funcao_js . "(false, '$p103_sequencial);</script>";
            }

            echo $sFuncaoRetorno;
        } else {
            echo "<script>" . $funcao_js . "('Chave(" . $pesquisa_chave . ") não Encontrado',true);</script>";
        }
    } else {
        echo "<script>" . $funcao_js . "('',false);</script>";
    }
}
?>
</body>
<?
if (!isset($pesquisa_chave)) {
    ?>
    <script>
    </script>
    <?
}
?>
<script>
    js_tabulacaoforms("form2", "chave_p103_sequencial", true, 1, "chave_p103_sequencial", true);
</script>
