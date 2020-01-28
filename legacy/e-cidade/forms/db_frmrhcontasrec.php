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

$oDaoRhcontasrec->rotulo->label();
$oRotulo = new rotulocampo;
$oRotulo->label("k13_descr");
$oRotulo->label("o15_descr");
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
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form1" method="post" action="">
        <fieldset>
          <legend>Contas por Recurso</legend>
          <table>            
            <tr>
              <td nowrap title="<?php echo $Trh41_anousu; ?>" >
                <label class="bold" for="rh41_anousu" id="lbl_rh41_anousu"><?php echo $Srh41_anousu; ?>:</label>
              </td>
              <td>
                <?php
                  $rh41_anousu = db_getsession('DB_anousu');
                  db_input('rh41_anousu', 5, $Irh41_anousu, true, 'text', 3, "");
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh41_conta; ?>" >
                <label class="bold" for="rh41_conta" id="lbl_rh41_conta">
                  <?php
                    db_ancora( $Srh41_conta . ':', 
                               "js_pesquisarh41_conta(true);", $db_opcao);
                  ?>
                </label>
              </td>
              <td>
                <?php
                  db_input('rh41_conta', 5, $Irh41_conta, true, 'text', $db_opcao, " onchange='js_pesquisarh41_conta(false);'");
                ?>
                <?php 
                  db_input('k13_descr', 40, $Ik13_descr, true, 'text', 3, ''); 
                ?>
              </td>
            </tr>
            <tr>
              <td nowrap title="<?php echo $Trh41_codigo; ?>" >
                <label class="bold" for="rh41_codigo" id="lbl_rh41_codigo">
                  <?php
                    db_ancora( $Srh41_codigo . ':', 
                               "js_pesquisarh41_codigo(true);", $db_opcao);
                  ?>
                </label>
              </td>
              <td>
                <?php
                  db_input('rh41_codigo', 5, $Irh41_codigo, true, 'text', $db_opcao, " onchange='js_pesquisarh41_codigo(false);'");
                ?>
                <?php 
                  db_input('o15_descr', 40, $Io15_descr, true, 'text', 3, ''); 
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <input name="<?php echo $sNameBotaoProcessar; ?>" type="submit" id="db_opcao" value="<?php echo ucfirst($sNameBotaoProcessar); ?>" 
               <?php if ($db_opcao == 1): ?>
                 onclick="return js_validaFormulario()" 
               <?php endif; ?>
             <?php echo (!$db_botao ? "disabled" : ""); ?> >
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"), 
                   db_getsession("DB_modulo"), 
                   db_getsession("DB_anousu"), 
                   db_getsession("DB_instit") ); ?>
  </body>
  <script>
    var sMensagens = "recursoshumanos.pessoal.pes1_rhcontasrec.";

    function js_validaFormulario() {

      if ($F('rh41_conta') == '') {
        alert( _M(sMensagens + "campo_nao_informado", { sCampo : "Código Conta"}) );
        return false;
      }

      if ($F('rh41_codigo') == '') {
        alert( _M(sMensagens + "campo_nao_informado", { sCampo : "Recurso"}) );
        return false;
      }

      if ( isNaN($F('rh41_conta')) ) {
        alert( _M(sMensagens + "campo_valor_inteiro", { sCampo : "Código Conta"}) );
        return false;
      }

      if ( isNaN($F('rh41_codigo')) ) {
        alert( _M(sMensagens + "campo_valor_inteiro", { sCampo : "Código Conta"}) );
        return false;
      }

      return true;
    }

    function js_pesquisarh41_conta(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                             'db_iframe_saltes', 
                             'func_saltes.php?funcao_js=parent.js_mostrasaltes1|k13_conta|k13_descr&lFiltroContaBanco=true',
                             'Pesquisa', true);
      } else {
        if (document.form1.rh41_conta.value != '') {
          js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                               'db_iframe_saltes', 
                               'func_saltes.php?pesquisa_chave=' + document.form1.rh41_conta.value + '&funcao_js=parent.js_mostrasaltes&lFiltroContaBanco=true',
                               'Pesquisa', false);
        } else {
          document.form1.k13_descr.value = ''; 
        }
      }
    }

    function js_mostrasaltes(sChave, lErro) {

      document.form1.k13_descr.value = sChave;
      if (lErro) {

        document.form1.rh41_conta.focus();
        document.form1.rh41_conta.value = '';
      }
    }

    function js_mostrasaltes1(sChave, sDescricao) {

      document.form1.rh41_conta.value = sChave;
      document.form1.k13_descr.value = sDescricao;
      db_iframe_saltes.hide();
    }

    function js_pesquisarh41_codigo(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                             'db_iframe_orctiporec', 
                             'func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr', 
                             'Pesquisa', true);
      } else {
        if (document.form1.rh41_codigo.value != '') {
          js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                               'db_iframe_orctiporec', 
                               'func_orctiporec.php?pesquisa_chave=' + document.form1.rh41_codigo.value + '&funcao_js=parent.js_mostraorctiporec', 
                               'Pesquisa', false);
        } else {
          document.form1.o15_descr.value = ''; 
        }
      }
    }

    function js_mostraorctiporec(sChave, lErro) {

      document.form1.o15_descr.value = sChave;
      if (lErro) {

        document.form1.rh41_codigo.focus();
        document.form1.rh41_codigo.value = '';
      }
    }

    function js_mostraorctiporec1(sChave, sDescricao) {

      document.form1.rh41_codigo.value = sChave;
      document.form1.o15_descr.value = sDescricao;
      db_iframe_orctiporec.hide();
    }

    function js_pesquisarh41_instit(lExibeJanela) {

      if (lExibeJanela) {
        js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                             'db_iframe_db_config', 
                             'func_db_config.php?funcao_js=parent.js_mostradb_config1|codigo|nomeinst', 
                             'Pesquisa', true);
      } else {
        if (document.form1.rh41_instit.value != '') {
          js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                               'db_iframe_db_config', 
                               'func_db_config.php?pesquisa_chave=' + document.form1.rh41_instit.value + '&funcao_js=parent.js_mostradb_config', 
                               'Pesquisa', false);
        } else {
          document.form1.nomeinst.value = ''; 
        }
      }
    }

    function js_mostradb_config(sChave, lErro) {

      document.form1.nomeinst.value = sChave;
      if (lErro) {

        document.form1.rh41_instit.focus();
        document.form1.rh41_instit.value = '';
      }
    }

    function js_mostradb_config1(sChave, sDescricao) {

      document.form1.rh41_instit.value = sChave;
      document.form1.nomeinst.value = sDescricao;
      db_iframe_db_config.hide();
    }

    function js_pesquisa() {
      js_OpenJanelaIframe( 'CurrentWindow.corpo', 
                           'db_iframe_rhcontasrec', 
                           'func_rhcontasrec.php?funcao_js=parent.js_preenchepesquisa|k13_conta|rh41_codigo|rh41_anousu', 
                           'Pesquisa', true);
    }

    function js_preenchepesquisa(iConta, iCodigo, iAnousu) {

      db_iframe_rhcontasrec.hide();
      <?php
        if ($db_opcao != 1) {
          echo "location.href = '" . basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]) 
               . "?chavepesquisa=' + iConta + '&chavepesquisa1=' + iCodigo + "
               . "'&chavepesquisa3=' + iAnousu";
        }
      ?>
    }

    <?php echo (isset($sPosScripts) ? $sPosScripts : ""); ?>
  </script>
</html>
