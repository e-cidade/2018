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

//MODULO: patrim
$clcfpatriplaca->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("t40_descr");

$lBloquearAlteracao = false;
$iInstituicaoLogada = db_getsession("DB_instit");
$oDaoCfPatri        = db_utils::getDao("cfpatri");
$oDaoDbConfig       = db_utils::getDao("db_config");

$sSqlDbConfig = $oDaoDbConfig->sql_query_file (null, "codigo", null, "prefeitura is true");
$rsDbConfig   = $oDaoDbConfig->sql_record($sSqlDbConfig);

$iCodigoPrefeitura = db_utils::fieldsMemory($rsDbConfig, 0)->codigo;

$sSqlCfPatri = $oDaoCfPatri->sql_query_file(null, "*", null, null);
$rsCfPatri   = $oDaoCfPatri->sql_record($sSqlCfPatri);
$lControlaPlacaInstituicao = db_utils::fieldsMemory($rsCfPatri, 0)->t06_controlaplacainstituicao;

if ($lControlaPlacaInstituicao == 'f' && ($iInstituicaoLogada != $iCodigoPrefeitura)) {

  $lBloquearAlteracao = true;

  /**
   * Nao bloqueia inputs caso nao exista parametro para instituicao atual
   */
  if ( $db_opcao != 1 ) {
    $db_opcao = 3;
  }
}

?>
<style>
  
  #t07_obrigplaca {
    width: 82px;
  }
  
</style>
  <form class="container" name="form1" method="post" action="">
  <fieldset>
    <legend>Procedimentos - Parâmetros de Placa</legend>
    <table class="form-container">
      <?php
      db_input('t07_instit', 10, $It07_instit, true, 'hidden', 3, "");
      ?>
      <tr>
        <td title="<?=$Tt07_confplaca?>">
          <?php
          db_ancora($Lt07_confplaca, "js_pesquisat07_confplaca(true);", 1);
          ?>
        </td>
        <td> 
          <?php
          db_input('t07_confplaca', 10, $It07_confplaca, true, 'text', 1, " onchange='js_pesquisat07_confplaca(false);'");
          db_input('t40_descr', 40, $It40_descr, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=$Tt07_obrigplaca?>"><?=$Lt07_obrigplaca?></td>
        <td>
          <?php
          $disabled = "";

          if (isset($t07_confplaca) && $t07_confplaca != 4){
             $disabled = "disabled";
          }

          $x = array("t" => "SIM", "f" => "NAO");
          db_select("t07_obrigplaca", $x, true, $db_opcao, "$disabled");
          ?>
        </td>
      </tr>
      <tr>
        <td title="<?=$Tt07_digseqplaca?>">
          <?=$Lt07_digseqplaca?>
        </td>
        <td> 
          <?php
          db_input('t07_digseqplaca', 10, $It07_digseqplaca, true, 'text', $db_opcao);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="<?=$Tt07_sequencial?>" >
          <?=$Lt07_sequencial?>
        </td>
        <td> 
          <?php
          db_input('t07_sequencial', 10, $It07_sequencial, true, 'text', $db_opcao);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <input name="<?=($db_opcao == 1 ? "incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "alterar" : "alterar"))?>"
         type="submit"
         id="db_opcao"
         value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Alterar"))?>"
         <?=($db_botao == false ? "disabled" : "")?> >
          
</form>
<script>
function js_pesquisa() {

  js_OpenJanelaIframe(
    'CurrentWindow.corpo',
    'db_iframe_cfpatriplaca',
    'func_cfpatriplaca.php?funcao_js=parent.js_preenchepesquisa|t07_instit',
    'Pesquisa',
    true
  );
}

function js_preenchepesquisa(chave) {

  db_iframe_cfpatriplaca.hide();
  <?php
  if($db_opcao != 1) {
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_pesquisat07_confplaca(mostra) {

  if(mostra == true) {

    js_OpenJanelaIframe(
      'CurrentWindow.corpo',
      'db_iframe_bensconfplaca',
      'func_bensconfplaca.php?funcao_js=parent.js_mostrabensconfplaca1|t40_codigo|t40_descr',
      'Pesquisa',
      true
    );
  } else {

    if(document.form1.t07_confplaca.value != '') {

      js_OpenJanelaIframe(
        'CurrentWindow.corpo',
        'db_iframe_bensconfplaca',
        'func_bensconfplaca.php?pesquisa_chave='+document.form1.t07_confplaca.value+'&funcao_js=parent.js_mostrabensconfplaca',
        'Pesquisa',
        false
      );
    } else {
      document.form1.t40_descr.value = '';
    }
  }
}

function js_mostrabensconfplaca(chave, erro) {

  document.form1.t40_descr.value = chave;

  if(erro == true) {

    document.form1.t07_confplaca.focus();
    document.form1.t07_confplaca.value = '';
  } else {

    if(document.form1.t07_confplaca.value != 4) {

      document.form1.t07_obrigplaca.value    = "t";
      document.form1.t07_obrigplaca.disabled = true;
    } else {
      document.form1.t07_obrigplaca.disabled = false;
    }
  }
}

function js_mostrabensconfplaca1(chave1, chave2) {

  document.form1.t07_confplaca.value = chave1;
  document.form1.t40_descr.value     = chave2;

  if(document.form1.t07_confplaca.value != 4) {

    document.form1.t07_obrigplaca.value    = "t";
    document.form1.t07_obrigplaca.disabled = true;
  } else {
    document.form1.t07_obrigplaca.disabled = false;
  }

  db_iframe_bensconfplaca.hide();
}

$("t07_confplaca").addClassName("field-size2");
$("t40_descr").addClassName("field-size7");
$("t07_obrigplaca").setAttribute("rel","ignore-css");
$("t07_obrigplaca").addClassName("field-size2");
$("t07_digseqplaca").addClassName("field-size2");
$("t07_sequencial").addClassName("field-size2");

var sDigitosAtual = $F('t07_digseqplaca');

$('t07_digseqplaca').observe('blur', function() {

  if($F('t07_digseqplaca') > 10) {

    alert('Quantidade de dígitos da placa permitido: 10.');
    $('t07_digseqplaca').value = sDigitosAtual;

    return false;
  }

  return true;
});
</script>