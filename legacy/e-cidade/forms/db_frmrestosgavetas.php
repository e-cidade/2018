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

$clrestosgavetas->rotulo->label();

$clgavetas->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("cm01_i_codigo");
$clrotulo->label("cm28_i_proprietario");
$clrotulo->label("cm25_i_codigo");
$clrotulo->label("cm25_i_lotecemit");
$clrotulo->label("cm23_i_codigo");
$clrotulo->label("cm23_i_lotecemit");
$clrotulo->label("cm23_i_quadracemit");
$clrotulo->label("cm22_c_quadra");
$clrotulo->label("cm22_i_cemiterio");
$clrotulo->label("cm25_c_numero");
$clrotulo->label("z01_nome");

db_postmemory($HTTP_POST_VARS);

$sTipo           = $local == 3 ? 'O' : 'J';
$sWhere          = "cm26_i_sepultamento = {$sepultamento} and cm25_c_tipo = '{$sTipo}'";
$sSqlRestoGaveta = $clrestosgavetas->sql_query_dados_jazigo(null, "*, cgmpropri.z01_nome as proprietario, cgm3.z01_nome as nome_cemiterio	", null, $sWhere);
$rsRestoGaveta   = $clrestosgavetas->sql_record($sSqlRestoGaveta);

if (empty($cemiterio)) {
  $cemiterio = null;
}

if($rsRestoGaveta && pg_num_rows($rsRestoGaveta) >= 0) {

	db_fieldsmemory($rsRestoGaveta, 0);
	$lotecemit = $cm23_i_codigo;

} elseif (!isset($alterar) && !isset($incluir)) {

	$cm26_i_codigo         = '';
	#$cm26_i_sepultamento   = '';
	$cm26_i_ossoariojazigo = '';
	$cm25_c_numero         = '';
	$cm28_i_proprietario   = '';
	$proprietario          = '';
	$cm23_i_codigo         = '';
	$cm23_i_lotecemit      = '';
	$cm23_i_quadracemit    = '';
	$cm22_c_quadra         = '';
	$cm22_i_cemiterio      = '';
	$z01_nome              = '';
  $nome_cemiterio        = '';
	$cm26_d_entrada        = '';
	$cm27_i_restogaveta    = null;
	$cm27_d_exumprevista   = '';
	$cm27_d_exumfeita      = '';
	$cm27_c_ossoario       = 'N';
	$cm27_i_gaveta         = '';
}

?>
<form name="form1" method="post" action="">
<center>
<fieldset>
  <?php

    $sOssoarioJazigoLabel  = "Ossário Particular";
    $sOssoarioJazigoAncora = "Ossário:";

    if($tipo == "J") {
      $sOssoarioJazigoLabel  = "Jazigo";
      $sOssoarioJazigoAncora = "Jazigo:";
    }
  ?>
  <legend><?php echo $sOssoarioJazigoLabel; ?></legend>
  <table border="0">
    <tr>
      <td nowrap title="<?php echo $Tcm26_i_codigo; ?>">
        <?php echo $Lcm26_i_codigo; ?>
      </td>
      <td>
        <?php

          db_input('cm26_i_codigo', 10, $Icm26_i_codigo, true, 'text', 3, "");
          db_input('lotecemit', 10, $lotecemit, true, 'hidden', $db_opcao, "");
          db_input('cm26_i_sepultamento', 10, $Icm26_i_sepultamento, true, 'hidden', $db_opcao, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm26_i_ossoariojazigo; ?>">
        <?php
          db_ancora($sOssoarioJazigoAncora, "js_pesquisacm26_i_ossoariojazigo(true);", $db_opcao, "", $Lcm26_i_ossoariojazigo);
        ?>
      </td>
      <td>
        <?php

          db_input('cm26_i_ossoariojazigo', 10, $Icm26_i_ossoariojazigo, true, 'hidden', 3, "");
          db_input('cm25_c_numero', 10, $cm25_c_numero, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm28_i_proprietario; ?>">
         <?php echo $Lcm28_i_proprietario; ?>
      </td>
      <td>
        <?php
          db_input('cm28_i_proprietario', 10, $Icm28_i_proprietario, true, 'text', 3, "");
          db_input('proprietario', 40, $proprietario, true, 'text', 3, '');
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm25_i_lotecemit; ?>">
         <?php echo $Lcm25_i_lotecemit; ?>
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
          db_input('nome_cemiterio', 40, $Iz01_nome, true, 'text', 3, "");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?php echo $Tcm26_d_entrada; ?>">
         <?php echo $Lcm26_d_entrada; ?>
      </td>
      <td>
        <?php
          db_inputdata('cm26_d_entrada', $cm26_d_entrada_dia, $cm26_d_entrada_mes, $cm26_d_entrada_ano, true, 'text', $db_opcao, "");
        ?>
      </td>
    </tr>
    <?php if($tipo == "J") { ?>
      <tr>
        <td nowrap title="<?php echo $Tcm27_d_exumprevista; ?>">
          <?php
            echo $Lcm27_d_exumprevista;
            db_input('cm27_i_restogaveta', 10, $Icm27_i_restogaveta, true, 'hidden', $db_opcao, "");
          ?>
        </td>
        <td>
          <?php
            db_inputdata('cm27_d_exumprevista',@$cm27_d_exumprevista_dia,@$cm27_d_exumprevista_mes,@$cm27_d_exumprevista_ano,true,'text',$db_opcao,"")
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tcm27_d_exumfeita; ?>">
           <?php echo $Lcm27_d_exumfeita; ?>
        </td>
        <td>
          <?php
            db_inputdata('cm27_d_exumfeita', $cm27_d_exumfeita_dia, $cm27_d_exumfeita_mes, $cm27_d_exumfeita_ano, true, 'text', $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tcm27_c_ossoario; ?>">
           <?php echo $Lcm27_c_ossoario; ?>
        </td>
        <td>
          <?php
            $x = array('N' => 'Não', 'S' => 'Sim');
            db_select('cm27_c_ossoario', $x, true, $db_opcao, "");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?php echo $Tcm27_i_gaveta; ?>">
           <?php echo $Lcm27_i_gaveta; ?>
        </td>
        <td>
        <?php
          db_input('cm27_i_gaveta', 10, $Icm27_i_gaveta, true, 'text', $db_opcao, "");
        ?>
        </td>
      </tr>
    <?php } ?>
  </table>
</fieldset>
<center>
<?php if ($db_opcao != 3) { ?>
<input onclick="return js_validaFormulario();" name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>" <?=($db_botao==false?"disabled":"")?> >
<?php } ?>
</form>
<script>
function js_validaFormulario() {

  if(document.form1.cm26_d_entrada.value == '') {

    alert('Campo Entrada é de preenchimento obrigatório.');
    return false;
  }

  <?php if($db_opcao == 1 || $db_opcao == 2) { ?>
    if(document.form1.cm27_d_exumprevista_ano.value == '') {

      alert('Campo Exumação Prevista é de preenchimento obrigatório.');
      return false;
    }
  <?php } ?>

  if(document.form1.cm27_i_gaveta.value == '') {

    alert('Campo N da Gaveta é de preenchimento obrigatório.');
    return false;
  }

  return true;
}

function js_pesquisacm26_i_sepultamento(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_sepultamentos', 'func_sepultamentos.php?funcao_js=parent.js_mostrasepultamentos1|cm01_i_codigo|cm01_i_codigo', 'Pesquisa', true);
  } else {

    if(document.form1.cm26_i_sepultamento.value != '') {
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_sepultamentos', 'func_sepultamentos.php?pesquisa_chave='+document.form1.cm26_i_sepultamento.value+'&funcao_js=parent.js_mostrasepultamentos', 'Pesquisa', false);
    } else {
      document.form1.cm01_i_codigo.value = '';
    }
  }
}

function js_mostrasepultamentos(chave, erro) {

  document.form1.cm01_i_codigo.value = chave;

  if(erro == true) {

    document.form1.cm26_i_sepultamento.focus();
    document.form1.cm26_i_sepultamento.value = '';
  }
}

function js_mostrasepultamentos1(chave1, chave2) {

  document.form1.cm26_i_sepultamento.value = chave1;
  document.form1.cm01_i_codigo.value = chave2;
  db_iframe_sepultamentos.hide();
}

function js_pesquisacm26_i_ossoariojazigo(mostra) {

  if(mostra == true) {
    js_OpenJanelaIframe('', 'db_iframe_ossoariojazigo', 'func_ossoariojazigo.php?funcao_js=parent.js_mostraossoariojazigo1|cm25_i_codigo|cm25_c_numero|cm28_i_proprietario|proprietario|cm23_i_codigo|cm23_i_lotecemit|cm23_i_quadracemit|cm22_c_quadra|cm22_i_cemiterio|z01_nome|cm23_c_situacao&tp=2&tipo=<?php echo $tipo; ?>&cemiterio=<?php echo $cemiterio; ?>', 'Pesquisa', true);
  } else {
     if(document.form1.cm26_i_ossoariojazigo.value != '') {
        js_OpenJanelaIframe('', 'db_iframe_ossoariojazigo', 'func_ossoariojazigo.php?pesquisa_chave='+document.form1.cm26_i_ossoariojazigo.value+'&funcao_js=parent.js_mostraossoariojazigo&tp=2&tipo=<?php echo $tipo; ?>&cemiterio=<?php echo $cemiterio; ?>', 'Pesquisa', false);
     } else {
       document.form1.cm25_i_codigo.value = '';
     }
  }
}

function js_mostraossoariojazigo(chave, erro) {

  document.form1.cm25_i_codigo.value = chave;

  if(erro == true) {

    document.form1.cm26_i_ossoariojazigo.focus();
    document.form1.cm26_i_ossoariojazigo.value = '';
  }
}

function js_mostraossoariojazigo1(chave1, chave2, chave3, chave4, chave5, chave6, chave7, chave8, chave9, chave10, chave11) {

  db_iframe_ossoariojazigo.hide();

  if(chave11.substr(0, 1) == 'D' || ('<?php echo $tipo; ?>') == 'O') {

    document.form1.cm26_i_ossoariojazigo.value = chave1;
    document.form1.cm25_c_numero.value         = chave2;
    document.form1.cm28_i_proprietario.value   = chave3;
    document.form1.proprietario.value          = chave4;
    document.form1.cm23_i_codigo.value         = chave5;
    document.form1.cm23_i_lotecemit.value      = chave6;
    document.form1.cm23_i_quadracemit.value    = chave7;
    document.form1.cm22_c_quadra.value         = chave8;
    document.form1.cm22_i_cemiterio.value      = chave9;
    document.form1.nome_cemiterio.value        = chave10;
  } else {

    document.form1.cm26_i_ossoariojazigo.focus();
    document.form1.cm26_i_ossoariojazigo.value = '';
    document.form1.cm25_c_numero.value         = '';
    document.form1.cm28_i_proprietario.value   = '';
    document.form1.proprietario.value          = '';
    document.form1.cm23_i_codigo.value         = '';
    document.form1.cm23_i_lotecemit.value      = '';
    document.form1.cm23_i_quadracemit.value    = '';
    document.form1.cm22_c_quadra.value         = '';
    document.form1.cm22_i_cemiterio.value      = '';
    document.form1.nome_cemiterio.value        = '';

    alert("Ossário/Jazigo não esta disponível. Favor escolher outra seputura.");
  }

}

function js_pesquisa() {
  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_restosgavetas', 'func_restosgavetas.php?funcao_js=parent.js_preenchepesquisa|cm26_i_codigo', 'Pesquisa', true);
}

function js_preenchepesquisa(chave) {

  db_iframe_restosgavetas.hide();

  <?php

    if($db_opcao != 1) {
      echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
    }
  ?>
}
</script>