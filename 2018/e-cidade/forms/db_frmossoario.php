<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

$clossoario->rotulo->label();

$clrotulo = new rotulocampo;

$clrotulo->label("cm01_i_codigo");

db_postmemory($HTTP_POST_VARS);

$rsOssoario = $clossoario->sql_record($clossoario->sql_query(null, "*", null, "cm06_i_sepultamento = {$sepultamento}"));

if ($rsOssoario && pg_num_rows($rsOssoario) >= 0) {
	db_fieldsmemory($rsOssoario, 0);
} elseif (!isset($alterar) && !isset($incluir)) {

	$cm06_i_codigo   = '';
	$cm06_i_ossoario = '';
	$cm06_d_entrada  = '';
	$cm06_t_obs      = '';
}

if (!isset($lotecemit)) {
  $lotecemit = null;
}

?>
<form name="form1" method="post" action="">
<center>
<fieldset>
  <?php
    if (isset($local) && $local == 2) {
      echo '<legend>Ossário Geral</legend>';
    }
  ?>
<table border="0">
  <tr>
    <td nowrap title="<?php echo $Tcm06_i_codigo; ?>">
       <?php echo $Lcm06_i_codigo; ?>
    </td>
    <td>
      <?php
        db_input('cm06_i_codigo', 10, $Icm06_i_codigo, true, 'text', 3, "");
        db_input('lotecemit', 10, $lotecemit, true, 'hidden', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tcm06_i_ossoario; ?>">
      <?php echo $Lcm06_i_ossoario; ?>
    </td>
    <td>
      <?php
        db_input('cm06_i_ossoario', 10, $Icm06_i_ossoario, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tcm06_i_sepultamento; ?>">
      <?php
        db_ancora($Lcm06_i_sepultamento, "js_pesquisacm06_i_sepultamento(true);", 3);
      ?>
    </td>
    <td>
      <?php
        db_input('cm06_i_sepultamento', 10, $Icm06_i_sepultamento, true, 'text', 3, " onchange='js_pesquisacm06_i_sepultamento(false);'");
        db_input('nome_sepultamento', 50, $nome_sepultamento, true, 'text', 3, '');
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tcm06_d_entrada; ?>">
      <?php echo $Lcm06_d_entrada; ?>
    </td>
    <td>
      <?php
        db_inputdata('cm06_d_entrada', $cm06_d_entrada_dia, $cm06_d_entrada_mes, $cm06_d_entrada_ano, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
  <tr>
    <td nowrap title="<?php echo $Tcm06_t_obs; ?>">
      <?php echo $Lcm06_t_obs; ?>
    </td>
    <td>
      <?php
        db_textarea('cm06_t_obs', 3, 50, $Icm06_t_obs, true, 'text', $db_opcao, "");
      ?>
    </td>
  </tr>
</table>
</fieldset>
<center>
<?php if (@$antigo != "X" and $db_opcao != 3) { ?>
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
  <?php if (!isset($lPesquisar) || $lPesquisar) { ?>
		<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
	<?php } ?>
<?php } ?>
</form>
<script type="text/javascript">

function js_pesquisacm06_i_sepultamento(mostra) {

  if (mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_sepultamentos','func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|cm01_i_codigo','Pesquisa',true);
  } else {

    if (document.form1.cm06_i_sepultamento.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_sepultamentos', 'func_sepultamentos.php?pesquisa_chave='+document.form1.cm06_i_sepultamento.value+'&funcao_js=parent.js_mostrasepultamentos', 'Pesquisa', false);
    } else {
      document.form1.cm01_i_codigo.value = '';
    }
  }
}

function js_mostrasepultamentos(chave, erro) {

  document.form1.cm01_i_codigo.value = chave;

  if (erro == true) {
    document.form1.cm06_i_sepultamento.focus();
    document.form1.cm06_i_sepultamento.value = '';
  }
}

function js_mostrasepultamentos1(chave1, chave2) {

  document.form1.cm06_i_sepultamento.value = chave1;
  document.form1.cm01_i_codigo.value = chave2;
  db_iframe_sepultamentos.hide();
}

function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_a3', 'db_iframe_ossoario', 'func_ossoario.php?funcao_js=parent.js_preenchepesquisa|cm06_i_codigo', 'Pesquisa', true);
}

function js_preenchepesquisa(chave) {

  db_iframe_ossoario.hide();
  <?php
    if ($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>