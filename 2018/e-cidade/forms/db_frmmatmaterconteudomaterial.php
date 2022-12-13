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

$oDaoConteudoMaterial->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("m61_descr");
$oRotulo->label("m60_descr");

$iOpcaoSelecaoMaterial = 3;

if ($db_opcao == 1) {

  $sNameBotaoProcessar   = "incluir";
  $iOpcaoSelecaoMaterial = 1;
} else if ($db_opcao == 2 || $db_opcao == 22) {
  $sNameBotaoProcessar = "alterar";
} else {
  $sNameBotaoProcessar = "excluir";
}

$oDaoUnidades = new cl_matunid();
$sSqlUnidades = $oDaoUnidades->sql_query(null, "m61_codmatunid, m61_descr");
$rsUnidades   = db_query($sSqlUnidades);

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="" onsubmit="return validarDadosFormulario()">
        <fieldset>
          <legend><?php echo ucfirst($sNameBotaoProcessar); ?> Conteúdo de Medicamento</legend>
          <table>
            <tr>
              <td nowrap title="Medicamento" >
                <label class="bold" for="m08_matmater" id="lbl_m08_matmater">
                  <?php
                    db_input('m08_codigo', 10, $Im08_codigo, true, 'hidden', $db_opcao, "");
                    db_ancora('Medicamento:', "pesquisaMaterial(true);", $iOpcaoSelecaoMaterial);
                  ?>
                </label>
              </td>
              <td>
                <?php
                  db_input('m08_matmater', 10, $Im08_matmater, true, 'text', $iOpcaoSelecaoMaterial," onchange='pesquisaMaterial(false);'");
                  db_input('m60_descr', 50, $Im60_descr, true, 'text', 3, '');
                ?>
              </td>
            </tr>
            <tr>
              <td><label for="m08_unidade_material" class="bold">Unidade: </label></td>
              <td>
                <?php db_input('m08_unidade_material', 28, "", true, 'text', 3); ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Tm08_quantidade; ?>" >
                <label class="bold" for="m08_quantidade" id="lbl_m08_quantidade"><?php echo $Sm08_quantidade; ?>:</label>
              </td>
              <td>
                <?php
                  db_input('m08_quantidade', 10, $Im08_quantidade, true, 'text', $db_opcao, "");
                  db_selectrecord("m08_unidade", $rsUnidades, null, $db_opcao, "", "", "", "", "", 1);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao"
               value="<?php echo ucfirst($sNameBotaoProcessar); ?>" <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      </form>
    </div>
  <?php db_menu( db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit") ); ?>
  </body>
  <script>

    var MSG_DB_FRMMATMATERCONTEUDOMATERIAL = "saude.ambulatorial.db_frmmatmaterconteudomaterial.";
    function pesquisaMaterial(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( 'top.corpo',
                             'db_iframe_matmater',
                             'func_matmateralt.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr|m61_descr&lOutrosCampos=true',
                             'Pesquisa de Medicamentos', true);
      } else {
        if (document.form1.m08_matmater.value != '') {
          js_OpenJanelaIframe( 'top.corpo',
                               'db_iframe_matmater',
                               'func_matmateralt.php?pesquisa_chave=' + document.form1.m08_matmater.value + '&funcao_js=parent.js_mostramatmater',
                               'Pesquisa de Medicamentos', false);
        } else {
          document.form1.m60_descr.value = '';
        }
      }
    }

    function js_mostramatmater(sChave, lErro, sUnidadeMaterial) {

      document.form1.m60_descr.value   = sChave;
      if (lErro) {

        document.form1.m08_matmater.focus();
        document.form1.m08_matmater.value = '';
        $('m08_unidade_material').value   = '';
        return;
      }
      $('m08_unidade_material').value = sUnidadeMaterial;
    }

    function js_mostramatmater1(sChave, sDescricao) {

      document.form1.m08_matmater.value = sChave;
      document.form1.m60_descr.value    = sDescricao;
      $('m08_unidade_material').value  = arguments[2];
      db_iframe_matmater.hide();
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( 'top.corpo',
                           'db_iframe_matmaterconteudomaterial',
                           'func_matmaterconteudomaterial.php?funcao_js=parent.js_preenchepesquisa|m08_codigo',
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(sChave) {

      db_iframe_matmaterconteudomaterial.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) . "?chavepesquisa=' + sChave;";
        }
      ?>
    }

    if ( $('m08_unidade').type != 'select-one' ) {
      $('m08_unidade').style.display = 'none';
    }

    function validarDadosFormulario() {

      if ($F('m08_matmater') == '') {

        alert( _M(MSG_DB_FRMMATMATERCONTEUDOMATERIAL + "informe_medicamento") );
        return false;
      }
      if ($F('m08_quantidade') == '') {

        alert( _M(MSG_DB_FRMMATMATERCONTEUDOMATERIAL + "informe_quantidade") );
        return false;
      }
      return true;
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>