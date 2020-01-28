<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

//MODULO: Ambulatorial
$clsau_tipoficha->rotulo->label();
?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset>
      <legend>Tipo de Ficha</legend>
      <table class="form-container">
        <tr>
          <td nowrap title="<?=$Tsd101_i_codigo?>">
            <label for="sd101_i_codigo">Código:</label>
          </td>
          <td>
            <?php
            db_input('sd101_i_codigo', 4, $Isd101_i_codigo, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=$Tsd101_c_descr?>">
            <label for="sd101_c_descr">Descrição:</label>
          </td>
          <td>
            <?php
            db_input('sd101_c_descr', 30, $Isd101_c_descr, true, 'text', $db_opcao);
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
    <?php
    if($db_opcao != 1) {
    ?>
      <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <?php
    }
    ?>
  </form>
</div>
<script>
function js_pesquisa() {

  js_OpenJanelaIframe(
    'CurrentWindow.corpo',
    'db_iframe_sau_tipoficha',
    'func_sau_tipoficha.php?funcao_js=parent.js_preenchepesquisa|sd101_i_codigo',
    'Pesquisa Tipo de Ficha',
    true
  );
}

function js_preenchepesquisa(chave) {

  db_iframe_sau_tipoficha.hide();
  <?php
  if($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}
</script>