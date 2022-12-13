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

//MODULO: cadastro
$clsetor->rotulo->label();
?>
  <div class="container">
    <form name="form1" method="post" action="">
      <fieldset>
        <legend>Cadastro de Setor</legend>

        <table class="form-container">
          <tr>
            <td nowrap title="<?=$Tj30_codi?>">
              <label for="j30_codi"><?=$Lj30_codi?></label>
            </td>
            <td>
              <?php
              $val          = $Ij30_codi;
              $result_param = $clcfiptu->sql_record($clcfiptu->sql_query(db_getsession("DB_anousu"),"j18_formatsetor"));

              if($clcfiptu->numrows > 0) {

                db_fieldsmemory($result_param, 0);
                $val = 1;

                if($j18_formatsetor == 1) {
                  $val = 3;
                }
              }

              db_input('j30_codi', 4, $val, true, 'text', $db_opcao);
              ?>
            <td>
          <tr>
          <tr>
            <td nowrap title="<?=$Tj30_descr?>">
              <label for="j30_descr"><?=$Lj30_descr?></label>
            </td>
            <td>
              <?php
              db_input('j30_descr', 40, $Ij30_descr, true, 'text', $db_opcao);
              ?>
            <td>
          <tr>
          <tr>
            <td nowrap title="<?=$Tj30_alipre?>">
              <label for="j30_alipre"><?=$Lj30_alipre?></label>
            </td>
            <td>
              <?php
              db_input('j30_alipre', 15, $Ij30_alipre, true, 'text', $db_opcao);
              ?>
            <td>
          <tr>
          <tr>
            <td nowrap title="<?=$Tj30_aliter?>">
              <label for="j30_aliter"><?=$Lj30_aliter?></label>
            </td>
            <td>
              <?php
              db_input('j30_aliter', 15, $Ij30_aliter, true, 'text', $db_opcao);
              ?>
            <td>
          <tr>
        </table>
      </fieldset>
      <input name="db_opcao"
             type="submit"
             id="db_opcao"
             value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 ? "Alterar" : "Excluir"))?>"
        <?=($db_botao == false ? "disabled" : "")?>
             onclick="return js_verifica_campos_digitados();" />

      <?php
      if($db_opcao != 1) {
        ?>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
        <?php
      }
      ?>

    </form>
  </div>
  <script>

    function js_pesquisa() {

      var sArquivo = 'func_setor.php?funcao_js=parent.js_preenchepesquisa|0';
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe', sArquivo, 'Pesquisar Setor', true);
    }

    function js_preenchepesquisa(chave) {

      db_iframe.hide();
      location.href = '<?=basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])?>'+"?chavepesquisa="+chave;
    }

    $('j30_codi').addClassName('field-size2');
    $('j30_descr').addClassName('field-size7');
    $('j30_alipre').addClassName('field-size2');
    $('j30_aliter').addClassName('field-size2');
  </script>
