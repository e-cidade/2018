<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

//MODULO: saude
$clfamiliamicroarea->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("sd33_i_codigo");
$clrotulo->label("sd34_i_codigo");
$clrotulo->label("sd34_v_descricao");
$clrotulo->label("sd33_v_descricao");

$sAcao = "Inclusão";

if( $db_opcao == 2 || $db_opcao == 22 ) {
  $sAcao = "Alteração";
}

if( $db_opcao == 3 || $db_opcao == 33 ) {
  $sAcao = "Exclusão";
}
?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Família / Micro Área - <?=$sAcao?></legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?=$Tsd35_i_codigo?>">
            <label for="sd35_i_codigo">
              <?=$Lsd35_i_codigo?>
            </label>
          </td>
          <td>
            <?php
              db_input('sd35_i_codigo', 10, $Isd35_i_codigo, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tsd35_i_familia?>">
            <label for="sd35_i_familia">
              <?php
                db_ancora($Lsd35_i_familia, "js_pesquisasd35_i_familia(true);", $db_opcao);
              ?>
            </label>
          </td>
          <td>
            <?php
              $sScript = " onchange='js_pesquisasd35_i_familia(false);'";
              db_input('sd35_i_familia',   10, $Isd35_i_familia,   true, 'text', $db_opcao, $sScript);
              db_input('sd33_v_descricao', 60, $Isd33_v_descricao, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tsd35_i_microarea?>">
            <?php
              db_ancora($Lsd35_i_microarea, "js_pesquisasd35_i_microarea(true);", $db_opcao);
            ?>
          </td>
          <td>
            <?php
              $sScript = " onchange='js_pesquisasd35_i_microarea(false);'";
              db_input('sd35_i_microarea', 10, $Isd35_i_microarea, true, 'text', $db_opcao, $sScript);
              db_input('sd34_v_descricao', 60, $Isd34_v_descricao, true, 'text', 3);
            ?>
          </td>
        </tr>
      </table>

    </fieldset>
    <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir"))?>"
           type="submit"
           id="db_opcao"
           value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
           <?=($db_botao == false ? "disabled" : "")?> />
    <input name="pesquisar"
           type="button"
           id="pesquisar"
           value="Pesquisar"
           onclick="js_pesquisa();"
           <?=($db_opcao == 1 ? "disabled" : "")?> />
  </form>
</div>
<script>
function js_pesquisasd35_i_familia(mostra) {

  var sTituloPesquisa = 'Pesquisa Família';

  if(mostra == true) {

    js_OpenJanelaIframe(
                         'CurrentWindow.corpo',
                         'db_iframe_familia',
                         'func_familia.php?funcao_js=parent.js_mostrafamilia1|sd33_i_codigo|sd33_v_descricao',
                         sTituloPesquisa,
                         mostra
                       );
  } else {

    if(document.form1.sd35_i_familia.value != '') {

       js_OpenJanelaIframe(
                            'CurrentWindow.corpo',
                            'db_iframe_familia',
                            'func_familia.php?pesquisa_chave='+document.form1.sd35_i_familia.value
                                           +'&funcao_js=parent.js_mostrafamilia',
                            sTituloPesquisa,
                            mostra
                          );
    } else {
      document.form1.sd33_i_codigo.value = '';
    }
  }
}

function js_mostrafamilia(chave, erro) {

  document.form1.sd33_v_descricao.value = chave;

  if(erro == true) {

    document.form1.sd35_i_familia.focus(); 
    document.form1.sd35_i_familia.value = ''; 
  }
}

function js_mostrafamilia1(chave1, chave2) {

  document.form1.sd35_i_familia.value   = chave1;
  document.form1.sd33_v_descricao.value = chave2;
  db_iframe_familia.hide();
}

function js_pesquisasd35_i_microarea(mostra) {

  var sTituloPesquisa = 'Pesquisa Micro Área';

  if(mostra == true) {

    js_OpenJanelaIframe(
                         'CurrentWindow.corpo',
                         'db_iframe_microarea',
                         'func_microarea.php?funcao_js=parent.js_mostramicroarea1|sd34_i_codigo|sd34_v_descricao',
                         sTituloPesquisa,
                         mostra
                       );
  } else {

    if(document.form1.sd35_i_microarea.value != '') {

      js_OpenJanelaIframe(
                           'CurrentWindow.corpo',
                           'db_iframe_microarea',
                           'func_microarea.php?pesquisa_chave='+document.form1.sd35_i_microarea.value
                                            +'&funcao_js=parent.js_mostramicroarea',
                           sTituloPesquisa,
                           mostra
                         );
    } else {
      document.form1.sd34_i_codigo.value = '';
    }
  }
}

function js_mostramicroarea(chave, erro) {

  document.form1.sd34_v_descricao.value = chave;

  if(erro == true) {

    document.form1.sd35_i_microarea.focus(); 
    document.form1.sd35_i_microarea.value = ''; 
  }
}

function js_mostramicroarea1(chave1, chave2) {

  document.form1.sd35_i_microarea.value = chave1;
  document.form1.sd34_v_descricao.value = chave2;
  db_iframe_microarea.hide();
}

function js_pesquisa() {

  js_OpenJanelaIframe(
                       'CurrentWindow.corpo',
                       'db_iframe_familiamicroarea',
                       'func_familiamicroarea.php?funcao_js=parent.js_preenchepesquisa|sd35_i_codigo',
                       'Pesquisa Família / Micro Área',
                       true
                     );
}

function js_preenchepesquisa(chave) {

  db_iframe_familiamicroarea.hide();
  <?php
    if($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}

$('sd35_i_codigo').className    = 'field-size2';
$('sd35_i_familia').className   = 'field-size2';
$('sd33_v_descricao').className = 'field-size7';
$('sd35_i_microarea').className = 'field-size2';
$('sd34_v_descricao').className = 'field-size7';
</script>