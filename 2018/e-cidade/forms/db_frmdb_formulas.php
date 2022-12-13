<?php
/**
 * MODULO: configuracoes
 */
$oDaoDb_formulas->rotulo->label();

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
    <?php 
      db_app::load('scripts.js, prototype.js, dbcomboBox.widget.js, dbtextField.widget.js, strings.js, DBHint.widget.js, DBLookUp.widget.js, AjaxRequest.js');
    ?>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      textarea#db148_descricao,
      textarea#db148_formula {
        padding: 3;
        width: 100%;
        resize: none;

      }

      textarea#db148_formula {
        font-family:monospace;
      }
      
    </style>
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="" onsubmit="return js_validaCampos();">
        <fieldset>
          <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Fórmulas </legend>
          <table>
            <tr>
              <td nowrap title="<?php echo $Tdb148_nome; ?>" >
                <label class="bold" for="db148_nome" id="lbl_db148_nome"><?php echo $Sdb148_nome; ?>:</label>
              </td>
              <td>
                <?php db_input('db148_sequencial', 20, $Idb148_sequencial, true, 'hidden', $db_opcao, ""); ?>
                <?php db_input('db148_nome', 70, $Idb148_nome, true, 'text', $db_opcao, "onChange='js_verificaNomeVariavel()'", "", "", "", 40); ?>
              </td>
              <td nowrap title="<?php echo $Tdb148_ambiente; ?>" style="display: none">
                <label class="bold" for="db148_ambiente" id="lbl_db148_ambiente"><?php echo $Sdb148_ambiente; ?>:</label>
              </td>
              <td style="display: none">
                <?php db_input('db148_ambiente', 10, $Idb148_ambiente, true, 'checkbox', $db_opcao, "", "", "", "", 10); ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend>
                    <label class="bold" for="db148_descricao" id="lbl_db148_descricao"><?php echo $Sdb148_descricao; ?>:</label>
                  </legend>
                  <?php db_textarea('db148_descricao',6, 0, $Idb148_descricao, true, 'text', $db_opcao, ""); ?>
                </fieldset>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend>
                    <label class="bold" for="db148_formula" id="lbl_db148_formula"><?php echo $Sdb148_formula; ?>:</label>
                  </legend>
                  <?php db_textarea('db148_formula',12, 0, $Idb148_formula, true, 'text', $db_opcao, " spellcheck='false' placeholder=\"Exemplo:\nselect fc_getsession('DB_anousu'); \""); ?>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <input name="pesquisar" type="<?= $db_opcao == 1 ? "hidden" : "button"?>" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
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
                           'db_iframe_db_formulas', 
                           'func_db_formulas.php?funcao_js=parent.js_preenchepesquisa|db148_sequencial', 
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_db_formulas.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    function js_verificaNomeVariavel() {

      var oParametersAjaxRequest = { 
        "exec"             : "verificaNomeVariavel",
        "sNomeVariavel"    : $F("db148_nome"),
        "db148_sequencial" : $F("db148_sequencial")
      };
      var lValido = true;

      var oAjaxRequest = new AjaxRequest("con1_db_formulas.RPC.php", oParametersAjaxRequest, function(oAjax, lErro){

        if (lErro) {

          alert(oAjax.message.urlDecode());
          lValido =  false;
        }
      });

      oAjaxRequest.setMessage("Verificando nome da variável...");
      oAjaxRequest.asynchronous(false);
      oAjaxRequest.execute();
      return lValido;
    }

    function js_validaCampos() {

      if($F("db148_nome") == '') {
        alert("Preencha o nome da variável.");
        return false;
      }

      if($F("db148_descricao") == '') {
        alert("Preencha a descrição da variável.");
        return false;
      }
      
      return js_verificaNomeVariavel();
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>
