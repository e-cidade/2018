<?php
/**
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

$clsepulta->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("cm01_i_codigo");
$clrotulo->label("cm05_i_campa");
$clrotulo->label("cm19_c_descr");
$clrotulo->label("cm05_i_lotecemit");

$clrotulo->label("cm23_i_codigo");
$clrotulo->label("cm23_i_lotecemit");
$clrotulo->label("cm23_i_quadracemit");
$clrotulo->label("cm22_c_quadra");
$clrotulo->label("cm22_i_cemiterio");
$clrotulo->label("z01_nome");

db_postmemory($HTTP_POST_VARS);

if (isset($sepultamento)) {
  $sSql = $clsepulta->sql_query(null, "*, cgmcemit.z01_nome as nome_cemit", null, "cm24_i_sepultamento = {$sepultamento}");
  $rsSepulta = $clsepulta->sql_record($sSql);
}

if (isset($rsSepulta) && $clsepulta->numrows > 0) {
  db_fieldsmemory($rsSepulta, 0);
  $lotecemit = $cm23_i_codigo;
} elseif (!isset($alterar) && !isset($incluir)) {

  $cm24_i_codigo      = '';
  $cm24_i_sepultura   = '';
  $cm05_c_numero      = '';
  $cm05_i_campa       = '';
  $cm19_c_descr       = '';
  $cm23_i_codigo      = '';
  $cm23_i_lotecemit   = '';
  $cm23_i_quadracemit = '';
  $cm22_c_quadra      = '';
  $cm22_i_cemiterio   = '';
  $z01_nome           = '';
  $cm24_d_entrada     = '';
}

if (empty($cemiterio)) {
  $cemiterio = null;
}

?>
<center>
<fieldset>
  <?php

    if(isset($local) && $local == 1) {
      echo '<legend>Sepultura</legend>';
    }
  ?>
  <table border="0">
    <tr>
      <td nowrap title="<?php echo $Tcm24_i_codigo; ?>">
        <?php echo $Lcm24_i_codigo; ?>
      </td>
      <td>
        <?php
          db_input('cm24_i_sepultamento', 10, $Icm24_i_sepultamento, true, 'hidden', $db_opcao, "");
          db_input('lotecemit', 10, $lotecemit, true, 'hidden', $db_opcao, "");
          db_input('cm24_i_codigo', 10, $Icm24_i_codigo, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm24_i_sepultura; ?>">
        <?php
          db_ancora($Lcm24_i_sepultura, "js_pesquisacm24_i_sepultura(true);", $db_opcao, "", $Lcm24_i_sepultura);
        ?>
      </td>
      <td>
        <?php
          db_input('cm24_i_sepultura', 10, $Icm24_i_sepultura, true, 'hidden', 3, "");
          db_input('cm05_c_numero', 10, $cm05_c_numero, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm05_i_campa; ?>">
         <?php echo $Lcm05_i_campa; ?>
      </td>
      <td>
        <?php
          db_input('cm05_i_campa', 10, $Icm05_i_campa, true, 'hidden', 3, "");
          db_input('cm19_c_descr', 40, $Icm19_c_descr, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm05_i_lotecemit; ?>">
         <?php echo $Lcm05_i_lotecemit; ?>
      </td>
      <td>
        <?php
          db_input('cm23_i_codigo', 10, $Icm23_i_codigo, true, 'hidden', 3, '');
          db_input('cm23_i_lotecemit', 10, $Icm23_i_lotecemit, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm23_i_quadracemit; ?>">
         <?php echo $Lcm23_i_quadracemit; ?>
      </td>
      <td>
        <?php
          db_input('cm23_i_quadracemit', 10, $Icm23_i_quadracemit, true, 'hidden', 3, "");
          db_input('cm22_c_quadra', 10, $Icm22_c_quadra, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm22_i_cemiterio; ?>">
        <?php echo $Lcm22_i_cemiterio; ?>
      </td>
      <td>
        <?php
          db_input('cm22_i_cemiterio', 10, $Icm22_i_cemiterio, true, 'text', 3, "");
          db_input('nome_cemit', 40, $Iz01_nome, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm24_d_entrada; ?>">
        <?php echo $Lcm24_d_entrada; ?>
      </td>
      <td>
      <?php db_inputdata('cm24_d_entrada', $cm24_d_entrada_dia, $cm24_d_entrada_mes, $cm24_d_entrada_ano, true, 'text', $db_opcao, ""); ?>
      </td>
    </tr>
  </table>
</fieldset>
  <center>
  <?php if ($db_opcao != 3) { ?>
    <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <?php } ?>
<script>

function js_pesquisacm24_i_sepultamento(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_sepultamentos', 'func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|cm01_i_codigo', 'Pesquisa', true);
  } else {
    if(document.form1.cm24_i_sepultamento.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_sepultamentos', 'func_sepultamentos.php?pesquisa_chave='+document.form1.cm24_i_sepultamento.value+'&funcao_js=parent.js_mostrasepultamentos', 'Pesquisa', false);
    } else {
      document.form1.cm01_i_codigo.value = '';
    }
  }
}

function js_mostrasepultamentos(chave, erro) {

  document.form1.cm01_i_codigo.value = chave;

  if (erro == true) {

    document.form1.cm24_i_sepultamento.focus();
    document.form1.cm24_i_sepultamento.value = '';
  }
}

function js_mostrasepultamentos1(chave1, chave2) {

  document.form1.cm24_i_sepultamento.value = chave1;
  document.form1.cm01_i_codigo.value = chave2;
  db_iframe_sepultamentos.hide();
}

function js_pesquisacm24_i_sepultura(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('', 'db_iframe_sepultamentos', 'func_sepulturas.php?funcao_js=parent.js_mostrasepulturas1|cm05_i_codigo|cm05_c_numero|cm05_i_campa|cm19_c_descr|cm23_i_codigo|cm23_i_lotecemit|cm23_i_quadracemit|cm22_c_quadra|cm22_i_cemiterio|z01_nome|cm23_c_situacao&cemiterio=<?php echo $cemiterio; ?>', 'Pesquisa', true);
  } else {
    if(document.form1.cm24_i_sepultura.value != '') {
      js_OpenJanelaIframe('', 'db_iframe_sepultament/db_frmsepulta.php', 'func_sepulturas.php?pesquisa_chave='+document.form1.cm24_i_sepultura.value+'&funcao_js=parent.js_mostrasepulturas&cemiterio=<?php echo $cemiterio; ?>', 'Pesquisa', false);
    } else {
      document.form1.cm24_i_codigo.value = '';
    }
  }
}

function js_mostrasepulturas(chave, erro) {

  document.form1.cm24_i_codigo.value = chave;

  if (erro == true) {

    document.form1.cm24_i_sepultura.focus();
    document.form1.cm24_i_sepultura.value = '';
  }
}

function js_mostrasepulturas1(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, chave9, chave10, chave11) {

  db_iframe_sepultamentos.hide();

  if (chave11 == 'D') {

       document.form1.cm24_i_sepultura.value   = chave1;
       document.form1.cm05_c_numero.value      = chave2;
       document.form1.cm05_i_campa.value       = chave3;
       document.form1.cm19_c_descr.value       = chave4;
       document.form1.cm23_i_codigo.value      = chave5;
       document.form1.cm23_i_lotecemit.value   = chave6;
       document.form1.cm23_i_quadracemit.value = chave7;
       document.form1.cm22_c_quadra.value      = chave8;
       document.form1.cm22_i_cemiterio.value   = chave9;
       document.form1.z01_nome.value           = chave10;
  } else {

    if (confirm('Aviso!\n\n Já existe um Sepultamento cadastrado para a sepultura!\nConfirma o cadastro?')) {
      document.form1.cm24_i_sepultura.value   = chave1;
      document.form1.cm05_c_numero.value      = chave2;
      document.form1.cm05_i_campa.value       = chave3;
      document.form1.cm19_c_descr.value       = chave4;
      document.form1.cm23_i_codigo.value      = chave5;
      document.form1.cm23_i_lotecemit.value   = chave6;
      document.form1.cm23_i_quadracemit.value = chave7;
      document.form1.cm22_c_quadra.value      = chave8;
      document.form1.cm22_i_cemiterio.value   = chave9;
      document.form1.z01_nome.value           = chave10;
    } else {

      document.form1.cm24_i_sepultura.focus();
      document.form1.cm24_i_sepultura.value   = '';
      document.form1.cm05_c_numero.value      = '';
      document.form1.cm05_i_campa.value       = '';
      document.form1.cm19_c_descr.value       = '';
      document.form1.cm23_i_codigo.value      = '';
      document.form1.cm23_i_lotecemit.value   = '';
      document.form1.cm23_i_quadracemit.value = '';
      document.form1.cm22_c_quadra.value      = '';
      document.form1.cm22_i_cemiterio.value   = '';
      document.form1.z01_nome.value           = '';
    }
  }
}

function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_sepulta', 'func_sepulta.php?funcao_js=parent.js_preenchepesquisa|cm24_i_codigo', 'Pesquisa', true);
}

function js_preenchepesquisa(chave) {
  db_iframe_sepulta.hide();

  <?php
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>