<?php
/**
 * MODULO: ambulatorial
 */
$oDaoDescartemedicamento->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("m60_descr");
$oRotulo->label("nome");

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
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/saude.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
      textarea{
        width: 100%;
        resize: none;
        height: 150px;
      }
    </style>
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" id= 'form1' method="post" action="" onsubmit="return validarDadosFormulario()">
        <fieldset>
          <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Descarte de Medicamentos</legend>
          <table>
            <tr style="display: none;">
              <td nowrap title="<?php echo $Tsd107_sequencial; ?>" >
                <label class="bold" for="sd107_sequencial" id="lbl_sd107_sequencial"><?php echo $Ssd107_sequencial; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('sd107_sequencial', 10, $Isd107_sequencial, true, 'text', 3, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tsd107_medicamento; ?>" >
                <label class="bold" for="sd107_medicamento" id="lbl_sd107_medicamento">
                  <?php
                    db_ancora( $Ssd107_medicamento . ':',
                               "js_pesquisasd107_medicamento(true);", $db_opcaomedicamento);
                  ?>
                </label>
              </td>
              <td>
                <?php
                  db_input('sd107_medicamento', 10, $Isd107_medicamento, true, 'text', $db_opcaomedicamento," onchange='js_pesquisasd107_medicamento(false);'");
                ?>
                <?php
                  db_input('m60_descr', 40, $Im60_descr, true, 'text', 3, '');
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tsd107_quantidade; ?>" >
                <label class="bold" for="sd107_quantidade" id="lbl_sd107_quantidade"><?php echo $Ssd107_quantidade; ?>:</label>
              </td>
              <td>
                <?php db_input('sd107_quantidade', 10, $Isd107_quantidade, true, 'text', $db_opcao, ""); ?>
              <span id="unidade_material">
                <? if (!empty($m61_abrev)) {
                  echo  $m61_abrev;
                }
                ?>
              </span>
              </td>
            </tr>
            <tr>

              <td nowrap title="<?php echo $Tsd107_motivo; ?>" colspan="2" >
                <fieldset>
                  <legend>
                    <label class="bold" for="sd107_motivo" id="lbl_sd107_motivo"><?php echo $Ssd107_motivo; ?></label>
                  </legend>
                  <?php db_textarea('sd107_motivo',0, 0, $Isd107_motivo, true, 'text', $db_opcao, ""); ?>
                </fieldset>
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

    const MSG_DB_FRMDESCARTEMEDICAMENTO = "saude.ambulatorial.db_frmdescartemedicamento."
    function js_pesquisasd107_medicamento(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( 'top.corpo',
                             'db_iframe_far_matersaude',
                             'func_medicamentosmaterial.php?funcao_js=parent.js_mostrafar_matersaude1|fa01_i_codigo|m60_descr|dl_unidade',
                             'Pesquisa', true);
      } else {
        if (document.form1.sd107_medicamento.value != '') {
          js_OpenJanelaIframe( 'top.corpo',
                               'db_iframe_far_matersaude',
                               'func_medicamentosmaterial.php?pesquisa_chave=' + document.form1.sd107_medicamento.value + '&funcao_js=parent.js_mostrafar_matersaude',
                               'Pesquisa', false);
        } else {
          document.form1.m60_descr.value = '';
          $('unidade_material').innerHTML    = '';
        }
      }
    }

    function js_mostrafar_matersaude(lErro, iCodigoUnidade, sNomeMedicamento, nQuantidade, sUnidade) {

      if (lErro) {

        document.form1.sd107_medicamento.focus();
        document.form1.sd107_medicamento.value = '';
        document.form1.m60_descr.value         = iCodigoUnidade;
        $('unidade_material').innerHTML        = '';
        return;
      }


      document.form1.m60_descr.value    = sNomeMedicamento;
      $('unidade_material').innerHTML    = sUnidade;
    }

    function js_mostrafar_matersaude1(sChave, sDescricao, sUnidade) {

      document.form1.sd107_medicamento.value = sChave;
      document.form1.m60_descr.value         = sDescricao;
      $('unidade_material').innerHTML        = sUnidade;
      db_iframe_far_matersaude.hide();
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( 'top.corpo',
                           'db_iframe_descartemedicamento',
                           'func_descartemedicamento.php?funcao_js=parent.js_preenchepesquisa|sd107_sequencial',
                           'Pesquisa de Descarte de Medicamentos', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_descartemedicamento.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    /**
     * Valida se departamento logado é um Ambulatorio
     * @return {void}
     */
    (function() {

      validarDepartamentoUPS($('form1'), true);
    })();

    /**
     * Valida os campos antes de salvar
     * @return {boolean}
     */
    function validarDadosFormulario() {

      if ( $F('sd107_medicamento') == '' ) {

        alert( _M(MSG_DB_FRMDESCARTEMEDICAMENTO + "medicamento_nao_informado" ) );
        return false;
      }
      if ( $F('sd107_quantidade') == '' ) {

        alert( _M(MSG_DB_FRMDESCARTEMEDICAMENTO + "quantidade_nao_informada" ) );
        return false;
      }

      if ( new Number($F('sd107_quantidade')).toString() == 'NaN') {

        alert( _M(MSG_DB_FRMDESCARTEMEDICAMENTO + "quantidade_nao_numerica" )  );
        return false;
      }

      if ( $F('sd107_quantidade') <= 0 ) {

        alert( _M(MSG_DB_FRMDESCARTEMEDICAMENTO + "quantidade_negativa" ) );
        return false;
      }

      if ( $F('sd107_motivo') == '' ) {

        alert( _M(MSG_DB_FRMDESCARTEMEDICAMENTO + "motivo_nao_informado" ) );
        return false;
      }

      if ($('db_opcao').value == 'Excluir') {

        if (!confirm('Confirma a exclusão do descarte?')) {
          return false;
        }
      }
      return true;
    }
    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>

  </script>
</html>