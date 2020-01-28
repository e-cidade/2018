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

$oRotulo = new rotulocampo();
$oRotulo->label("tre04_sequencial");
$oRotulo->label("tre04_nome");
$oRotulo->label("tre04_abreviatura");
$oRotulo->label("tre04_cadenderbairrocadenderrua");
$oRotulo->label("db87_cadenderrua");
$oRotulo->label("db74_descricao");
$oRotulo->label("tre04_latitude");
$oRotulo->label("tre04_longitude");
$oRotulo->label("coddepto");
$oRotulo->label("descrdepto");
$oRotulo->label("tre04_pontoreferencia");
$oRotulo->label("db87_sequencial");
$oRotulo->label("ed82_i_codigo");
$oRotulo->label("ed82_c_nome");
?>
<form id="frmPontosParada" method="post" action="" class="container">
  <fieldset style="width: 25%">
    <legend class="bold">Ponto de Parada</legend>
    <table>
      <tr style="display: none;">
        <td><label class="bold" for="tre04_sequencial">Sequencial: </label></td>
        <td>
          <?php
            db_input("tre04_sequencial", 10, $Itre04_sequencial, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td><label class="bold" for="tre04_nome">Descrição: </label></td>
        <td colspan="2">

          <?php
            db_input("tre04_nome", 70, $Itre04_nome, true, 'text', $iOpcao);
          ?>
        </td>
      </tr>
      <tr>
        <td><label class="bold" for="tre04_abreviatura">Abreviatura: </label></td>
        <td colspan="2">
          <?php
            db_input("tre04_abreviatura", 10, $Itre04_abreviatura, true, 'text', $iOpcao);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label class="bold" for="db87_cadenderrua">
          <?php
            db_ancora("<b>Logradouro:</b>", "js_pesquisaLogradouro(true);", $iOpcao);
          ?>
            </label>
        </td>
        <td style="display: none;">
          <?php
            db_input("db87_sequencial", 10, $Idb87_sequencial, true, 'text', $iOpcao, "onChange='js_pesquisaLogradouro(false);'");
          ?>
        </td>
        <td>
          <?php
            db_input("db87_cadenderrua", 10, $Idb87_cadenderrua, true, 'text', $iOpcao, "onChange='js_pesquisaLogradouro(false);'");
          ?>
        </td>
        <td>
          <?php
            db_input("db74_descricao", 50, $Idb74_descricao, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td><label class="bold" for="tre04_latitude">Latitude: </label></td>
        <td>
          <?php
            db_input("tre04_latitude", 10, $Itre04_latitude, true, 'text', $iOpcao);
          ?>
        </td>
      </tr>
      <tr>
        <td><label class="bold" for="tre04_longitude">Longitude: </label></td>
        <td>
          <?php
            db_input("tre04_longitude", 10, $Itre04_longitude, true, 'text', $iOpcao);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label class="bold" for="tipo">Tipo: </label></td>
        <td colspan="3">
          <?php
            $aTipo = array(1 => "Departamento", 2 => "Parada", 3 => "Escola Procedência");
            db_select("tipo", $aTipo, "", 1);
          ?>
        </td>
      </tr>
      <tr id="trDepartamento">
        <td>
          <label class="bold" for="coddepto">
          <?php
            db_ancora("<b>Departamento:</b>", "js_pesquisaDepartamentos(true);", $iOpcao);
          ?>
          </label>
        </td>
        <td>
          <?php
            db_input("coddepto", 10, $Icoddepto, true, 'text', $iOpcao, "onChange='js_pesquisaDepartamentos(false);'");
          ?>
        </td>
        <td>
          <?php
            db_input("descrdepto", 50, $Idescrdepto, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr id="trEscolaProcedencia" style="display: none;">
        <td nowrap>
          <label class="bold" for="ed82_i_codigo">
          <?php
          db_ancora("<b>Escola de Procedência:</b>", "js_pesquisaEscolaProcedencia(true);", $iOpcao);
          ?>
          </label>
        </td>
        <td>
          <?php
          db_input("ed82_i_codigo", 10, $Ied82_i_codigo, true, 'text', $iOpcao, "onChange='js_pesquisaEscolaProcedencia(false);'");
          ?>
        </td>
        <td>
          <?php
          db_input("ed82_c_nome", 50, $Ied82_c_nome, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <fieldset>
          <legend><label for="tre04_pontoreferencia"><b>Ponto de Referência</b></label></legend>
            <?php
              db_textarea("tre04_pontoreferencia", 4, 78, $Itre04_pontoreferencia, true, "text", $iOpcao);
            ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <input id="btnAcao" type="button" value="" />
  <input id="btnPesquisar" type="button" value="Pesquisar" />
</form>
<script>
var iOpcao  = <?=$iOpcao;?>;
var sUrlRpc = 'tre4_pontoparada.RPC.php';

/**
 * Setamos o tamanho dos inputs
 */
$('db74_descricao').style.width         = '380px';
$('tre04_abreviatura').style.width      = '120px';
$('db87_cadenderrua').style.width       = '120px';
$('tre04_latitude').style.width         = '120px';
$('tre04_longitude').style.width        = '120px';
$('coddepto').style.width               = '120px';
$('descrdepto').style.width             = '380px';
$('ed82_i_codigo').style.width          = '120px';
$('ed82_c_nome').style.width            = '380px';
$('tre04_pontoreferencia').style.width  = '100%';
$('tre04_pontoreferencia').style.resize = 'none';

/**
 * Limitamos o tamanho da descricao e abreviatura
 */
$('tre04_nome').maxLength        = 70;
$('tre04_abreviatura').maxLength = 10;

/**
 * Valida o tipo do ponto de parada. Caso seja PARADA, ocultamos o campos departamento
 */
function js_validaTipo() {
   
  switch ($('tipo').value) {

    case '2':

      $('trDepartamento').style.display      = 'none';
      $('trEscolaProcedencia').style.display = 'none';
      $('coddepto').value                    = '';
      $('ed82_i_codigo').value               = '';
      break;

    case '3':

      $('trDepartamento').style.display      = 'none';
      $('trEscolaProcedencia').style.display = 'table-row';
      $('ed82_i_codigo').value               = '';
      $('coddepto').value                    = '';

      break;

    default:

      $('trDepartamento').style.display      = 'table-row';
      $('trEscolaProcedencia').style.display = 'none';
  }
}
$('tipo').observe("change", function(event) {
  js_validaTipo();
});

$('btnPesquisar').observe("click", function(event) {
  js_pesquisaPontosParada();
});

/**
 * Validamos se a requisicao eh inclusao, alteracao ou exclusao, alterando o valor do botao acao e chamando a pesquisa
 * quando for alteracao e exclusao
 */
function js_validaRequisicao(iOpcao) {

  switch(iOpcao) {

    case 1:

      $('btnAcao').value = 'Incluir';
      $('btnAcao').observe("click", function(event) {
        js_salvar();
      });
      break;

    case 2:

      $('btnAcao').value    = 'Alterar';
      $('btnAcao').disabled = true;
      $('btnAcao').observe("click", function(event) {
        js_salvar();
      });
      js_pesquisaPontosParada();
      break;

    case 3:

      $('btnAcao').value    = 'Excluir';
      $('btnAcao').disabled = true;
      $('btnAcao').observe("click", function(event) {
        js_excluir();
      });
      $('tipo').disabled = true;
      js_pesquisaPontosParada();
      break;
  }
}

/**
 * Pesquisamos os logradouros para vincular a uma parada
 */
function js_pesquisaLogradouro(lMostra) {

  var sUrl  = 'func_bairrologradouro.php?lRetornaDescricaoRua&funcao_js=parent.js_mostraLogradouro';

  if (lMostra) {
    sUrl += '|db87_cadenderrua|db74_descricao|db87_sequencial';
  } else {

    if ($('db87_cadenderrua').value != '') {
      sUrl += '&pesquisa_chave='+$('db87_cadenderrua').value;
    }
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_bairrologradouro', sUrl, 'Pesquisa Logradouro', lMostra);
}

/**
 * Retorno da pesquisa pelos logradouros
 */
function js_mostraLogradouro() {

  if (arguments[0] !== true && arguments[0] !== false) {

    $('db87_cadenderrua').value = arguments[0];
    $('db74_descricao').value   = arguments[1];
    $('db87_sequencial').value  = arguments[2];
  } else if (arguments[0] === true) {

    $('db87_sequencial').value  = '';
    $('db87_cadenderrua').value = '';
    $('db74_descricao').value   = arguments[1];
  } else {

    $('db87_sequencial').value = arguments[1];
    $('db74_descricao').value  = arguments[3];
  }
  db_iframe_bairrologradouro.hide();
}

/**
 * Pesquisamos os logradouros para vincular a uma parada
 */
function js_pesquisaDepartamentos(lMostra) {

  var sUrl  = 'func_db_depart.php?lSomenteAtivos&funcao_js=parent.js_mostraDepartamentos';

  if (lMostra) {

    sUrl += '|coddepto|descrdepto';
    js_OpenJanelaIframe('top.corpo', 'db_iframe_db_depart', sUrl, 'Pesquisa de Departamentos', true);
  } else {

    if ($('coddepto').value != '') {

      sUrl += '&pesquisa_chave='+$('coddepto').value;
      js_OpenJanelaIframe('top.corpo', 'db_iframe_db_depart', sUrl, 'Pesquisa de Departamentos', false);
    }
  }
}

/**
 * Retorno da pesquisa pelos logradouros
 */
function js_mostraDepartamentos() {

  if (arguments[1] != true && arguments[1] != false) {

    $('coddepto').value   = arguments[0];
    $('descrdepto').value = arguments[1];
  } else if (arguments[1] == true) {

    $('coddepto').value   = '';
    $('descrdepto').value = arguments[0];
  } else {
    $('descrdepto').value = arguments[0];
  }
  db_iframe_db_depart.hide();
}


/**
 * Pesquisamos as escolas de procedencia para vincular a uma parada
 */
function js_pesquisaEscolaProcedencia(lMostra) {

  var sUrl  = 'func_escolaprocedencia.php?funcao_js=parent.js_preencheEscolaProcedencia';


  if (lMostra) {

    sUrl += '|ed82_i_codigo|ed82_c_nome';
    js_OpenJanelaIframe('top.corpo', 'db_iframe_db_escolaproc', sUrl, 'Pesquisa Escolas de Procedência', true);
  } else {

    $('ed82_c_nome').value = '';
    if ($('ed82_i_codigo').value != '') {

      sUrl += '&pesquisa_chave='+$('ed82_i_codigo').value;
      js_OpenJanelaIframe('top.corpo', 'db_iframe_db_escolaproc', sUrl, 'Pesquisa Escolas de Procedência', false);
    }
  }
}

function js_preencheEscolaProcedencia() {

  if (arguments[1] != true && arguments[1] != false) {

    $('ed82_i_codigo').value = arguments[0];
    $('ed82_c_nome').value   = arguments[1];

  } else if (arguments[1] === true) {

    $('ed82_i_codigo').value = '';
    $('ed82_c_nome').value   = arguments[0];
    $('ed82_i_codigo').focus();

  } else {
    $('ed82_c_nome').value = arguments[0];
  }
  db_iframe_db_escolaproc.hide();
}
/**
 * Validamos o preenchimento dos campos obrigatorios
 */
function js_verifcaPreenchimentoCampos() {

  if (empty($('tre04_nome').value)) {

    alert(_M('educacao.transporteescolar.db_frmpontosparada.descricao_ponto_parada_vazio'));
    return false;
  }

  if (empty($('tre04_abreviatura').value)) {

    alert(_M('educacao.transporteescolar.db_frmpontosparada.abreviatura_ponto_parada_vazio'));
    return false;
  }

  if (empty($('db87_cadenderrua').value)) {

    alert(_M('educacao.transporteescolar.db_frmpontosparada.logradouro_ponto_parada_vazio'));
    return false;
  }

  if ($('tipo').value == 1 && empty($('coddepto').value)) {

    alert(_M('educacao.transporteescolar.db_frmpontosparada.departamento_ponto_parada_vazio'));
    return false;
  }

  if ($('tipo').value == 3 && empty($('ed82_i_codigo').value)) {

    alert(_M('educacao.transporteescolar.db_frmpontosparada.escola_procedencia_ponto_parada_vazio'));
    return false;
  }

  if (!empty($('tre04_latitude').value)) {

    var aLatitude = $('tre04_latitude').value.split('.');
    if (aLatitude.length == 1 || aLatitude[0].length > 3 || aLatitude[0].length == 0 || aLatitude[1].length == 0) {

      alert(_M('educacao.transporteescolar.db_frmpontosparada.formato_latitude_invalido'));
      return false;
    }
  }

if (!empty($('tre04_longitude').value)) {

    var aLongitude = $('tre04_longitude').value.split('.');
    if (aLongitude.length == 1 || aLongitude[0].length > 3 || aLongitude[0].length == 0 || aLongitude[1].length == 0) {

      alert(_M('educacao.transporteescolar.db_frmpontosparada.formato_longitude_invalido'));
      return false;
    }
  }
  return true;
}

/**
 * Limpa os campos apos salvar ou excluir e mostra a linha do departamento
 */
function js_limpaCampos() {

  $('tre04_sequencial').value            = '';
  $('tre04_nome').value                  = '';
  $('tre04_abreviatura').value           = '';
  $('db87_sequencial').value             = '';
  $('db87_cadenderrua').value            = '';
  $('db74_descricao').value              = '';
  $('tre04_latitude').value              = '';
  $('tre04_longitude').value             = '';
  $('coddepto').value                    = '';
  $('descrdepto').value                  = '';
  $('tre04_pontoreferencia').value       = '';
  $('db87_sequencial').value             = '';
  $('ed82_i_codigo').value               = '';
  $('ed82_c_nome').value                 = '';
  $('tipo').value                        = 1;
  $('trDepartamento').style.display      = 'table-row';
  $('trEscolaProcedencia').style.display = 'none';
}

/**
 * Salva o ponto de parada
 */
function js_salvar() {

  if (js_verifcaPreenchimentoCampos()) {

    var oParametro                     = new Object();
        oParametro.exec                = 'salvar';
        oParametro.iCodigoParada       = $F('tre04_sequencial');
        oParametro.sNome               = $F('tre04_nome');
        oParametro.sAbreviatura        = $F('tre04_abreviatura');
        oParametro.iCodigoRuaBairro    = $F('db87_sequencial');
        oParametro.nLatitude           = $F('tre04_latitude');
        oParametro.nLongitude          = $F('tre04_longitude');
        oParametro.iTipo               = $F('tipo');
        oParametro.iCodigoDepartamento = $F('coddepto');
        if (oParametro.iTipo == 3) {
          oParametro.iCodigoDepartamento = $F('ed82_i_codigo');
        }
        oParametro.sPontoReferencia    = $F('tre04_pontoreferencia');

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoSalvar;

    js_divCarregando(_M('educacao.transporteescolar.db_frmpontosparada.aguarde_salvar'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno do salvar
 */
function js_retornoSalvar(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {

    js_limpaCampos();
    if (iOpcao == 2) {
      js_pesquisaPontosParada();
    }
  }
}

/**
 * Exclui um ponto de parada
 */
function js_excluir() {

  if (confirm(_M('educacao.transporteescolar.db_frmpontosparada.confirma_exclusao'))) {

    var oParametro               = new Object();
        oParametro.exec          = 'remover';
        oParametro.iCodigoParada = $F('tre04_sequencial');

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoExcluir;

    js_divCarregando(_M('educacao.transporteescolar.db_frmpontosparada.aguarde_excluir'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno da exclusao do ponto de parada
 */
function js_retornoExcluir(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {

    js_limpaCampos();
    js_pesquisaPontosParada();
  }
}

/**
 * Pesquisa os veiculos cadastrados como transporte
 */
function js_pesquisaPontosParada() {

  var sUrl  = 'func_pontoparada.php?funcao_js=parent.js_mostraPontoParada';
      sUrl += '|tre04_sequencial';

  js_OpenJanelaIframe('top.corpo', 'db_iframe_pontoparada', sUrl, 'Pesquisa Ponto de Parada', true);
}

/**
 * Retorno da busca pelo veiculos cadastrados como transporte
 */
function js_mostraPontoParada() {

  db_iframe_pontoparada.hide();
  if (iOpcao == 1) {
    return true;
  }
  
  if (!empty(arguments[0])) {

    var oParametro               = new Object();
        oParametro.exec          = 'getDados';
        oParametro.iCodigoParada = arguments[0];

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_dadosVeiculoTransporte;

    js_divCarregando(_M('educacao.transporteescolar.db_frmpontosparada.buscando_dados_ponto_parada'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Preenche os campos com os dados cadastrados para o veiculo
 */
function js_dadosVeiculoTransporte(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  $('btnAcao').disabled = false;

  $('tre04_sequencial').value       = oRetorno.dados.iCodigoParada;
  $('tre04_nome').value             = oRetorno.dados.sNome.urlDecode();
  $('tre04_abreviatura').value      = oRetorno.dados.sAbreviatura.urlDecode();
  $('db87_sequencial').value        = oRetorno.dados.iCodigoRuaBairro;
  $('db87_cadenderrua').value       = oRetorno.dados.iCodigoRua;
  $('db74_descricao').value         = oRetorno.dados.sNomeRua.urlDecode();
  $('tre04_pontoreferencia').value  = oRetorno.dados.sPontoRefencia.urlDecode();
  $('tre04_latitude').value         = oRetorno.dados.nLatitude;
  $('tre04_longitude').value        = oRetorno.dados.nLongitude;
  $('tipo').value                   = oRetorno.dados.iTipo;
  js_validaTipo();
  switch (oRetorno.dados.iTipo) {

    case 1 :

      $('coddepto').value = oRetorno.dados.iCodigoDepartamento;
      $('descrdepto').value = oRetorno.dados.sDescricaoDepartamento.urlDecode();
    break;

    case 3:

      $('ed82_i_codigo').value = oRetorno.dados.iCodigoDepartamento;
      $('ed82_c_nome').value   = oRetorno.dados.sDescricaoDepartamento.urlDecode();
      break;

  }
}

js_validaRequisicao(iOpcao);
</script>