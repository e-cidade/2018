<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

$oRotulo = new rotulocampo;
$oRotulo->label("ve01_codigo");
$oRotulo->label("ve22_descr");
$oRotulo->label("z01_numcgm");
$oRotulo->label("z01_nome");
$oRotulo->label("tre01_identificacao");
$oRotulo->label("tre01_numeropassageiros");
$oRotulo->label("tre01_tipotransportemunicipal");
$oRotulo->label("tre01_sequencial");
?>
<form id="frmVeiculoTransporte" class="container" method="post">
  <fieldset>
    <legend class="bold">Veículo Transporte</legend>
    <table>
      <tr style="display: none;">
        <td><label class="bold">Veículo Transporte: </label></td>
        <td>
          <?php
            db_input("tre01_sequencial", 6, $Itre01_sequencial, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td><label class="bold">Veículo da Prefeitura: </label></td>
        <td>
          <?php
            $aOpcoes = array(0 => "NÃO", 1 => "SIM");
            db_select("veiculoPrefeitura", $aOpcoes, "", 1, "onChange='js_tipoVeiculo();'");
          ?>
        </td>
      </tr>
      <tr id="trVeiculo">
        <td>
          <?php
            db_ancora("<b>Veículo:</b>", "js_pesquisaVeiculos(true);", $iOpcao); 
          ?>
        </td>
        <td>
          <?php
            db_input("ve01_codigo", 6, $Ive01_codigo, true, 'text', $iOpcao, "onChange='js_pesquisaVeiculos(false);'");
          ?>
        </td>
        <td>
          <?php
            db_input("ve22_descr", 40, $Ive22_descr, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr id="trEmpresa" style="display: none;">
        <td>
          <?php
            db_ancora("<b>Empresa:</b>", "js_pesquisaCgm(true);", $iOpcao); 
          ?>
        </td>
        <td>
          <?php
            db_input("z01_numcgm", 6, $Iz01_numcgm, true, 'text', $iOpcao, "onChange='js_pesquisaCgm(false);'");
          ?>
        </td>
        <td>
          <?php
            db_input("z01_nome", 40, $Iz01_nome, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td><label class="bold">Tipo de Veículo:</label></td>
        <td colspan="2">
          <?php
            $aTiposTransporteMunicipal   = array("" => "Selecione");
            $oDaoTipoTransporteMunicipal = new cl_tipotransportemunicipal();
            $sSqlTipoTransporteMunicipal = $oDaoTipoTransporteMunicipal->sql_query_file();
            $rsTipoTransporteMunicipal   = $oDaoTipoTransporteMunicipal->sql_record($sSqlTipoTransporteMunicipal);
            $iTotalTransporteMunicipal   = $oDaoTipoTransporteMunicipal->numrows;
            
            for ($iContador = 0; $iContador < $iTotalTransporteMunicipal; $iContador++) {

              $oDadosTransporteMunicipal = db_utils::fieldsMemory($rsTipoTransporteMunicipal, $iContador);
              $aTiposTransporteMunicipal[$oDadosTransporteMunicipal->tre00_sequencial] = $oDadosTransporteMunicipal->tre00_descricao;
            }
            db_select("tipoTransporteMunicipal", $aTiposTransporteMunicipal, "", 1);
          ?>
        </td>
      </tr>
      <tr>
        <td><?=$Ltre01_identificacao?></td>
        <td colspan="2">
          <?php
            db_input("tre01_identificacao", 6, $Itre01_identificacao, true, 'text', $iOpcao); 
          ?>
        </td>
      </tr>
      <tr>
        <td><?=$Ltre01_numeropassageiros?></td>
        <td>
          <?php
            db_input("tre01_numeropassageiros", 6, $Itre01_numeropassageiros, true, 'text', $iOpcao); 
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <input id="btnAcao" type="button" value=""  />
  <input id="btnPesquisar" type="button" value="Pesquisar"  />
</form>
<script>
var iOpcao  = <?=$iOpcao;?>;
var sUrlRpc = 'tre4_veiculotransporte.RPC.php';

$('veiculoPrefeitura').style.width = '100%';
$('veiculoPrefeitura').value       = 1;

$('tre01_identificacao').maxLength   = 25;
$('tre01_identificacao').style.width = '370px';
$('tre01_identificacao').disabled    = true;

$('tipoTransporteMunicipal').style.width = '100%';

$('btnPesquisar').observe("click", function(event) {
  js_pesquisaVeiculoTransporte();
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
      js_pesquisaVeiculoTransporte();
      break;

    case 3:

      $('btnAcao').value                    = 'Excluir';
      $('btnAcao').disabled                 = true;
      $('veiculoPrefeitura').disabled       = true;
      $('tipoTransporteMunicipal').disabled = true;
      $('btnAcao').observe("click", function(event) {
        js_excluir();
      });
      js_pesquisaVeiculoTransporte();
      break;
  }
}

/**
 * Valida o tipo de veiculo selecionado, para bloqueio do campo de identificacao
 */
function js_tipoVeiculo() {

  js_alteraTipoVeiculo(1);
  $('tre01_identificacao').value     = '';
  $('tre01_numeropassageiros').value = '';
  $('tre01_identificacao').disabled  = true;
  
  if ($('veiculoPrefeitura').value == 0) {

    js_alteraTipoVeiculo(0);
    $('tre01_identificacao').disabled = false;
  }
}

/**
 * Busca os veiculos para cadastro
 */
function js_pesquisaVeiculos(lMostra) {

  var sUrl  = 'func_veiculos.php?funcao_js=parent.js_mostraVeiculo';

  if (lMostra) {
    
    sUrl += '|ve01_codigo|ve22_descr|ve01_placa';
    js_OpenJanelaIframe('top.corpo', 'db_iframe_veiculos', sUrl, 'Pesquisa Veículos', true);
  } else {

    if ($('ve01_codigo').value != '') {

      sUrl += '&pesquisa_chave='+$('ve01_codigo').value;
      js_OpenJanelaIframe('top.corpo', 'db_iframe_veiculos', sUrl, 'Pesquisa Veículos', false);
    }
  }
}

/**
 * Retorno da busca dos veiculos
 */
function js_mostraVeiculo() {

  if (arguments[0] !== true && arguments[0] !== false) {

    $('ve01_codigo').value             = arguments[0];
    $('ve22_descr').value              = arguments[1];
    $('tre01_identificacao').value     = arguments[2];
    $('tre01_numeropassageiros').value = '';
  } else if (arguments[0] === true) {

    $('ve01_codigo').value             = '';
    $('ve22_descr').value              = arguments[1];
    $('tre01_identificacao').value     = '';
    $('tre01_numeropassageiros').value = '';
  } else {

    $('ve01_codigo').value             = arguments[1];
    $('ve22_descr').value              = arguments[3];
    $('tre01_identificacao').value     = arguments[2];
    $('tre01_numeropassageiros').value = '';
  }

  db_iframe_veiculos.hide();
}

/**
 * Pesquisa a empresa para vinculo
 */
function js_pesquisaCgm(lMostra) {

  var sUrl = 'func_cgm.php?funcao_js=parent.js_mostraCgm';

  if (lMostra) {

    sUrl += '|z01_numcgm|z01_nome';
    js_OpenJanelaIframe('top.corpo', 'func_nome', sUrl, 'Pesquisa Empresa', true);
  } else {

    if ($('z01_numcgm').value != '') {

      sUrl += '&pesquisa_chave='+$('z01_numcgm').value;
      js_OpenJanelaIframe('top.corpo', 'func_nome', sUrl, 'Pesquisa Empresa', false);
    }
  }
}

/**
 * Retorno da pesquisa pela empresa
 */
function js_mostraCgm() {

  if (arguments[0] !== true && arguments[0] !== false) {

    $('z01_numcgm').value = arguments[0];
    $('z01_nome').value   = arguments[1];
  } else if (arguments[0] === true) {
    
    $('z01_numcgm').value = '';
    $('z01_nome').value   = arguments[1];
  } else {
    $('z01_nome').value  = arguments[1];
  }
  func_nome.hide();
}

/**
 * Pesquisa os veiculos cadastrados como transporte
 */
function js_pesquisaVeiculoTransporte() {

  var sUrl  = 'func_veiculotransportemunicipal.php?funcao_js=parent.js_mostraVeiculoTransporte';
      sUrl += '|tre01_sequencial&iLinha=1';
    
  js_OpenJanelaIframe('top.corpo', 'db_iframe_veiculotransportemunicipal', sUrl, 'Pesquisa Veículo Transporte', true);
}

/**
 * Retorno da busca pelo veiculos cadastrados como transporte
 */
function js_mostraVeiculoTransporte() {

  db_iframe_veiculotransportemunicipal.hide();
  if (iOpcao == 1) {
    return;
  }
  
  if (!empty(arguments[0])) {
    
    var oParametro                = new Object();
        oParametro.exec           = 'getDados';
        oParametro.iCodigoVeiculo = arguments[0];
  
    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_dadosVeiculoTransporte;
  
    js_divCarregando(_M('educacao.transporteescolar.db_frmveiculotransporte.buscando_dados_veiculo'), "msgBox");
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
  
  $('tre01_sequencial').value        = oRetorno.dados.iCodigoVeiculo;
  $('tipoTransporteMunicipal').value = oRetorno.dados.iTipoTransporte;
  $('tre01_numeropassageiros').value = oRetorno.dados.iNumeroPassageiros;
  $('tre01_identificacao').value     = oRetorno.dados.sIdentificacao.urlDecode();

  if (!empty(oRetorno.dados.iVinculoVeiculo)) {
    
    $('ve01_codigo').value       = oRetorno.dados.iVinculoVeiculo;
    $('ve22_descr').value        = oRetorno.dados.sMarcaVinculoTransporte.urlDecode();
    $('veiculoPrefeitura').value = 1;
    js_alteraTipoVeiculo(1);
  } else {

    $('z01_numcgm').value        = oRetorno.dados.iVinculoCgm;
    $('z01_nome').value          = oRetorno.dados.sNomeEmpresa.urlDecode();
    $('veiculoPrefeitura').value = 0;
    js_alteraTipoVeiculo(0);
  }
}

/**
 * Trata os campos de acordo com o tipo de veiculo selecionado
 */
function js_alteraTipoVeiculo(iOpcao) {

  switch(iOpcao) {

    case 0:

      $('trVeiculo').style.display      = 'none';
      $('trEmpresa').style.display      = 'table-row';
      $('ve01_codigo').value            = '';
      $('ve22_descr').value             = '';
      $('tre01_identificacao').disabled = false;
      break;

    case 1:

      $('trVeiculo').style.display      = 'table-row';
      $('trEmpresa').style.display      = 'none';
      $('z01_numcgm').value             = '';
      $('z01_nome').value               = '';
      $('tre01_identificacao').disabled = true;
      break;
  }
}

/**
 * Salva um veiculo como transporte
 */
function js_salvar() {

  if (js_verificaCampos()) {
    
    var oParametro                    = new Object();
        oParametro.exec               = 'salvar';
        oParametro.iCodigoVeiculo     = $('tre01_sequencial').value;
        oParametro.sIdentificacao     = $('tre01_identificacao').value;
        oParametro.iNumeroPassageiros = $('tre01_numeropassageiros').value;
        oParametro.iTipoTransporte    = $('tipoTransporteMunicipal').value;
        oParametro.iVinculoVeiculo    = $('ve01_codigo').value;
        oParametro.iVinculoCgm        = $('z01_numcgm').value;
  
    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoSalvar;
  
    js_divCarregando(_M('educacao.transporteescolar.db_frmveiculotransporte.salvando_veiculo'), "msgBox");
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
      js_pesquisaVeiculoTransporte();
    }
  }
}

/**
 * Exclui um veiculo como transporte
 */
function js_excluir() {

  if (confirm(_M('educacao.transporteescolar.db_frmveiculotransporte.confirmacao_exclusao'))) {
    
    var oParametro                = new Object();
        oParametro.exec           = 'excluir';
        oParametro.iCodigoVeiculo = $('tre01_sequencial').value;
  
    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoExcluir;
  
    js_divCarregando(_M('educacao.transporteescolar.db_frmveiculotransporte.excluindo_veiculo'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno do excluir
 */
function js_retornoExcluir(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {
    
    js_limpaCampos();
    js_pesquisaVeiculoTransporte();
  }
}

/**
 * Verifica se os campos foram preenchidos
 */
function js_verificaCampos() {

  if ($('ve01_codigo').value == '' && $('veiculoPrefeitura').value == 1) {

    alert(_M('educacao.transporteescolar.db_frmveiculotransporte.codigo_veiculo_vazio'));
    return false;
  }

  if ($('z01_numcgm').value == '' && $('veiculoPrefeitura').value == 0) {

    alert(_M('educacao.transporteescolar.db_frmveiculotransporte.codigo_empresa_vazio'));
    return false;
  }

  if ($('tipoTransporteMunicipal').value == '') {

    alert(_M('educacao.transporteescolar.db_frmveiculotransporte.tipo_transporte_municipal_vazio'));
    return false;
  }

  if ($('tre01_identificacao').value == '') {

    alert(_M('educacao.transporteescolar.db_frmveiculotransporte.identificacao_vazio'));
    return false;
  }

  if ($('tre01_numeropassageiros').value == '') {

    alert(_M('educacao.transporteescolar.db_frmveiculotransporte.numero_passageiros_vazio'));
    return false;
  }

  if ($('tre01_numeropassageiros').value == '') {

    alert(_M('educacao.transporteescolar.db_frmveiculotransporte.numero_passageiros_zero'));
    return false;
  }
  return true;
}

/**
 * Limpa os campos apos salvar e excluir
 */
function js_limpaCampos() {

  $('tre01_sequencial').value        = '';
  $('tipoTransporteMunicipal').value = '';
  $('tre01_numeropassageiros').value = '';
  $('tre01_identificacao').value     = '';
  $('ve01_codigo').value             = '';
  $('ve22_descr').value              = '';
  $('z01_numcgm').value              = '';
  $('z01_nome').value                = '';
  $('veiculoPrefeitura').value       = 1;
  js_alteraTipoVeiculo(1);
}

js_validaRequisicao(iOpcao);
</script>