<?php
/**
 * MODULO: ambulatorial
 */
$oDaoSetorambulatorial->rotulo->label();

if ($db_opcao == 1) {
  $sNameBotaoProcessar = "incluir";
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $sNameBotaoProcessar = "alterar";
} else {
  $sNameBotaoProcessar = "excluir";
}
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Setor ambulatorial</legend>
          <table>

            <tr>
              <td nowrap title="<?php echo $Tsd91_descricao; ?>" >
                <label class="bold" for="sd91_descricao" id="lbl_sd91_descricao"><?php echo $Ssd91_descricao; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('sd91_codigo', 10, $Isd91_codigo, true, 'hidden', $db_opcao, "");
                  db_input('sd91_unidades', 10, $Isd91_unidades, true, 'hidden', $db_opcao,"");
                  db_input('sd91_descricao', 60, $Isd91_descricao, true, 'text', $db_opcao, ""); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tsd91_local; ?>" >
                <label class="bold" for="sd91_local" id="lbl_sd91_local"><?php echo $Ssd91_local; ?>:</label>
              </td>
              <td>
                <?php
                  $x = array('1' => 'RECEPÇÃO', '2' => 'TRIAGEM', '3' => 'CONSULTA MÉDICA', '4' => 'EXTERNO');
                  db_select('sd91_local', $x, true, $db_opcao, "");
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>
  </body>
  <script>

    function js_pesquisa() {
      js_OpenJanelaIframe( 'top.corpo',
                           'db_iframe_setorambulatorial',
                           'func_setorambulatorial.php?funcao_js=parent.js_preenchepesquisa|sd91_codigo',
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_setorambulatorial.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>
