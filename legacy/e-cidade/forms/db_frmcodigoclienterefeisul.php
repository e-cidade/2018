<?php
/**
 * MODULO: pessoal
 */
$oDaoCodigoclienterefeisul->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("nomeinst");

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
          <legend>Parâmetros Refeisul</legend>
          <table>
            <tr>
              <td nowrap title="<?php echo $Trh171_sequencial; ?>" >
                <label class="bold" for="rh171_sequencial" id="lbl_rh171_sequencial"><?php echo $Srh171_sequencial; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('rh171_sequencial', 10, $Irh171_sequencial, true, 'text', 3, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh171_codigocliente; ?>" >
                <label class="bold" for="rh171_codigocliente" id="lbl_rh171_codigocliente">
									Código Empresa:
								</label>
              </td>
              <td>
                <?php
                  db_input('rh171_codigocliente', 15, $Irh171_codigocliente, true, 'text', $db_opcao,"");
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="Salvar" <?php echo (!$db_botao ? "disabled" : ""); ?> >
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"), 
                   db_getsession("DB_modulo"), 
                   db_getsession("DB_anousu"), 
                   db_getsession("DB_instit") ); ?>
  </body>
  <script>

    function js_pesquisarh171_instit(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                             'db_iframe_db_config', 
                             'func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst', 
                             'Pesquisa', true);
      } else {
        if (document.form1.rh171_instit.value != '') {
          js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                               'db_iframe_db_config', 
                               'func_db_config.php?pesquisa_chave=' + document.form1.rh171_instit.value + '&funcao_js=parent.js_mostradb_config', 
                               'Pesquisa', false);
        } else {
          document.form1.nomeinst.value = ''; 
        }
      }
    }

    function js_mostradb_config(sChave, lErro) {

      document.form1.nomeinst.value = sChave;
      if (lErro) {

        document.form1.rh171_instit.focus();
        document.form1.rh171_instit.value = '';
      }
    }

    function js_mostradb_config1(sChave, sDescricao) {

      document.form1.rh171_instit.value = sChave;
      document.form1.nomeinst.value = sDescricao;
      db_iframe_db_config.hide();
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                           'db_iframe_codigoclienterefeisul', 
                           'func_codigoclienterefeisul.php?funcao_js=parent.js_preenchepesquisa|rh171_sequencial', 
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_codigoclienterefeisul.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>
