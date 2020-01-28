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

//MODULO: educação
$clserie->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ed10_i_tipoensino");

$sLegenda = "Inclusão de Etapa";

if( $db_opcao == 2 || $db_opcao == 22 ) {
  $sLegenda = "Alteração de Etapa";
}

if( $db_opcao == 3 || $db_opcao == 3 ) {
  $sLegenda = "Exclusão de Etapa";
}

?>
<div class="container">
  <form name="form1" method="post" action="">
    <fieldset class="form-container">
      <legend><?php echo $sLegenda; ?></legend>
      <table>
        <tr>
          <td nowrap title="<?=@$Ted11_i_codigo?>">
            <?=@$Led11_i_codigo?>
          </td>
          <td>
            <?php
            db_input( 'ed11_i_codigo', 10, $Ied11_i_codigo, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted11_i_ensino?>">
            <?php
            db_ancora( @$Led11_i_ensino, "js_pesquisaed11_i_ensino(true);", ( $db_opcao == 1 ? 1 : 3 ) );
            ?>
          </td>
          <td>
            <?php
            db_input( 'ed11_i_ensino', 10, $Ied11_i_ensino, true, 'text', 3 );
            db_input( 'ed10_c_descr',  60, @$Ied10_c_descr, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted10_i_tipoensino?>">
            <?php
            db_ancora( @$Led10_i_tipoensino, "", 3 );
            ?>
          </td>
          <td>
            <?php
            db_input( 'ed36_c_descr', 50, @$Ied36_c_descr, true, 'text', 3 );
            db_input( 'ed36_c_abrev',  2, @$Ied36_c_abrev, true, 'text', 3 );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted11_c_descr?>">
            <?=@$Led11_c_descr?>
          </td>
          <td>
            <?php
            db_input( 'ed11_c_descr', 20, $Ied11_c_descr, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap title="<?=@$Ted11_c_abrev?>">
            <?=@$Led11_c_abrev?>
          </td>
          <td>
            <?php
            db_input( 'ed11_c_abrev', 10, $Ied11_c_abrev, true, 'text', $db_opcao );
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="<?=( $db_opcao == 1 ? "incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "alterar" : "excluir" ) )?>"
           type="submit"
           id="db_opcao"
           value="<?=( $db_opcao == 1 ? "Incluir" : ( $db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir" ) )?>"
           <?=( $db_botao == false ? "disabled" : "" )?> >
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
    <input name="novo" type="button" id="novo" value="Novo Registro" onclick="js_novo()" >
  </form>
</div>
<script>
function js_pesquisaed11_i_ensino( mostra ) {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_ensino',
                       'func_ensino.php?funcao_js=parent.js_mostraensino1|ed10_i_codigo|ed10_c_descr|ed10_i_tipoensino|ed36_c_abrev',
                       'Pesquisa de Ensinos',
                       true
                     );
}

function js_mostraensino1( chave1, chave2, chave3, chave4 ) {

  document.form1.ed11_i_ensino.value = chave1;
  document.form1.ed10_c_descr.value  = chave2;
  document.form1.ed36_c_descr.value  = chave3;
  document.form1.ed36_c_abrev.value  = chave4;
  db_iframe_ensino.hide();
}

function js_pesquisa() {

  js_OpenJanelaIframe(
                       '',
                       'db_iframe_serie',
                       'func_serie0.php?funcao_js=parent.js_preenchepesquisa|ed11_i_codigo',
                       'Pesquisa de Etapas',
                       true
                     );
}

function js_preenchepesquisa( chave ) {

  db_iframe_serie.hide();
  <?php
  if( $db_opcao != 1 ) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_novo() {
  location.href = "edu1_serie001.php";
}

$('ed11_i_codigo').className = 'field-size2';
$('ed11_i_ensino').className = 'field-size2';
$('ed10_c_descr').className  = 'field-size7';
$('ed36_c_descr').className  = 'field-size8';
$('ed36_c_abrev').className  = 'field-size1';
$('ed11_c_descr').className  = 'field-size-max';
$('ed11_c_abrev').className  = 'field-size2';
</script>