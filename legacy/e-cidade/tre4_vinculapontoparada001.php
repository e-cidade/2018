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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
    db_app::load("prototype.js, scripts.js, strings.js, prototype.maskedinput.js, datagrid.widget.js");
    db_app::load("estilos.css");
    ?>
  </head>
  <body bgcolor="#CCCCCC" style="margin-top: 25px;">
    <form id="frmVinculaPontoParada" class="container" method="post" action="">
      <fieldset>
        <legend class="bold">Vincular Ponto de Parada</legend>
        <table>
          <tr>
            <td id="ctnAncoraLinha" nowrap></td>
            <td id="ctnCodigoLinha" nowrap></td>
            <td id="ctnDescricaoLinha" nowrap></td>
          </tr>
          <tr>
            <td nowrap><label class="bold">Itinerário:</label></td>
            <td id="ctnTipoItinerario" nowrap></td>
          </tr>
          <tr>
            <td nowrap><label class="bold">Logradouros do Itinerário:</label></td>
            <td id="ctnLogradourosItinerario" colspan="2" nowrap></td>
          </tr>
          <tr>
            <td nowrap><label class="bold">Pontos de Parada:</label></td>
            <td id="ctnPontosParada" colspan="2" nowrap></td>
          </tr>
        </table>
      </fieldset>
      <input id="btnSalvar" name="btnSalvar" type="button" value="Salvar" />
    </form>
    <fieldset class="container" style="width: 80%;">
      <legend class="bold">Vínculos existentes</legend>
      <div id="ctnGridVinculos"></div>
    </fieldset>
  </body>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script>
var sUrlRpc = 'tre4_linhastransporte.RPC.php';

/**
 * Elemento ancora para as linhas 
 */
var oLinkLinha                  = document.createElement('a');
    oLinkLinha.innerHTML        = 'Linha:';
    oLinkLinha.style.fontWeight = 'bold';
    oLinkLinha.href             = '#';
    oLinkLinha.setAttribute('onclick', 'js_pesquisaLinhas(true);');
$('ctnAncoraLinha').appendChild(oLinkLinha);

/**
 * Elemento input do codigo da linha
 */
var oInputCodigoLinha             = document.createElement('input');
    oInputCodigoLinha.id          = 'oInputCodigoLinha';
    oInputCodigoLinha.name        = 'oInputCodigoLinha';
    oInputCodigoLinha.style.width = '80px';
$('ctnCodigoLinha').appendChild(oInputCodigoLinha);

$('oInputCodigoLinha').observe("change", function(event) {
  js_pesquisaLinhas(false);
});

/**
 * Elemento input da descricao da linha
 */
var oInputDescricaoLinha                       = document.createElement('input');
    oInputDescricaoLinha.id                    = 'oInputDescricaoLinha';
    oInputDescricaoLinha.name                  = 'oInputDescricaoLinha';
    oInputDescricaoLinha.style.width           = '500px';
    oInputDescricaoLinha.readOnly              = true;
    oInputDescricaoLinha.style.backgroundColor = '#DEB887';
$('ctnDescricaoLinha').appendChild(oInputDescricaoLinha);

/**
 * Elemento select para Ida ou Retorno
 */
var oCboTipoItinerario             = document.createElement('select');
    oCboTipoItinerario.id          = 'oCboTipoItinerario';
    oCboTipoItinerario.style.width = '100%';
    oCboTipoItinerario.add(new Option("Ida", 1));
    oCboTipoItinerario.add(new Option("Retorno", 2));
$('ctnTipoItinerario').appendChild(oCboTipoItinerario);

$('oCboTipoItinerario').observe("change", function(event) {
  
  js_buscaLogradouros();
  js_limpaLogradouros();
  js_limpaPontosParada();
});

/**
 * Elemento select dos logradouros vinculados ao itinerario de uma linha
 */
var oCboLogradouroItinerario             = document.createElement('select');
    oCboLogradouroItinerario.id          = 'oCboLogradouroItinerario';
    oCboLogradouroItinerario.style.width = '100%';
$('ctnLogradourosItinerario').appendChild(oCboLogradouroItinerario);

$('oCboLogradouroItinerario').observe("change", function(event) {
  js_buscaParadas();
});

/**
 * Elemento select dos pontos de parada existentes
 */
var oCboPontosParada             = document.createElement('select');
    oCboPontosParada.id          = 'oCboPontosParada';
    oCboPontosParada.style.width = '100%';
$('ctnPontosParada').appendChild(oCboPontosParada);

/**
 * Grid com os pontos de parada vinculados a um itinerario
 */
var oGridVinculosPontoParada              = new DBGrid("gridVinculosPontoParada");
    oGridVinculosPontoParada.nameInstance = 'oGridVinculosPontoParada';
    oGridVinculosPontoParada.setHeader(new Array("Código", "Logradouro", "Bairro", "Ponto de Parada", "Itinerário", "Ação"));
    oGridVinculosPontoParada.setCellAlign(new Array("center", "left", "left", "left", "left", "center"));
    oGridVinculosPontoParada.setCellWidth(new Array("5%", "40%", "25%", "20%", "5%", "5%"));
    oGridVinculosPontoParada.setHeight(200);
    oGridVinculosPontoParada.show($('ctnGridVinculos'));

$('btnSalvar').observe("click", function(event) {
  js_vincularPontoParada();
});

/**
 * Pesquisa as linhas cadastradas
 */
function js_pesquisaLinhas(lMostra) {

  var sUrl  = 'func_linhatransporte.php?funcao_js=parent.js_mostraLinhas';

  if (lMostra) {
    sUrl += '|tre06_sequencial|tre06_nome';
  } else {

    if (!empty(oInputCodigoLinha.value)) {
      sUrl += '&pesquisa_chave='+oInputCodigoLinha.value;
    } else {
      
      oInputDescricaoLinha.value = '';
      js_limpaLogradouros();
      js_limpaPontosParada();
      oGridVinculosPontoParada.clearAll(true);
    }
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_linhatransporte', sUrl, 'Pesquisa Linhas de Transporte', lMostra);

}

/**
 * Retorna os valores referentes a linha selecionada
 * Desbloqueia o botao Alterar/Excluir
 */
function js_mostraLinhas() {

  if (arguments[1] !== true && arguments[1] !== false) {

    oInputCodigoLinha.value    = arguments[0];
    oInputDescricaoLinha.value = arguments[1];
  }

  if (arguments[1] === true) {

    oInputCodigoLinha.value    = '';
    oInputDescricaoLinha.value = arguments[0];
  }

  if (arguments[1] === false) {
    oInputDescricaoLinha.value = arguments[0];
  }

  db_iframe_linhatransporte.hide();
  js_buscaLogradouros();
  js_buscaParadasItinerario();
}

/**
 * Busca os logradouros vinculados ao itinerario de uma linha
 */
function js_buscaLogradouros() {

  if (oInputCodigoLinha.value == '') {

    js_limpaLogradouros();
    oCboLogradouroItinerario.add(new Option('Não há logradouros vinculados ao itinerário.', ''));

    js_limpaPontosParada();
    oCboPontosParada.add(new Option('Não há pontos de parada cadastrados para o logradouro selecionado.', ''));
    return false;
  }
  
  var oParametro              = new Object();
      oParametro.sExecucao    = 'getLogradouros';
      oParametro.iCodigoLinha = oInputCodigoLinha.value;
      oParametro.iItinerario  = oCboTipoItinerario.value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoBuscaLogradouros;

  js_divCarregando(_M('educacao.transporteescolar.tre4_vinculapontoparada.aguardando_logradouros'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno da busca pelos logradouros
 */
function js_retornoBuscaLogradouros(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  js_limpaLogradouros();

  if (oRetorno.aLogradouros.length == 0) {
    
    oCboLogradouroItinerario.add(new Option('Não há logradouros vinculados ao itinerário.', ''));
    js_limpaPontosParada();
    oCboPontosParada.add(new Option('Não há pontos de parada cadastrados para o logradouro selecionado.', ''));
  } else {

    oCboLogradouroItinerario.add(new Option('Selecione um logradouro.', ''));
    oRetorno.aLogradouros.each(function(oLogradouro, iSeq) {
      oCboLogradouroItinerario.add(new Option(oLogradouro.sNomeLogradouro.urlDecode(), oLogradouro.iCodigoLinhaLogradouro));
    });
  }
}

/**
 * Busca as paradas cadastradas para um logradouro
 */
function js_buscaParadas() {

  if (oCboLogradouroItinerario.value == '') {

    js_limpaPontosParada();
    oCboPontosParada.add(new Option('Não há pontos de parada cadastrados para o logradouro selecionado.', ''));
    return false;
  }
    
  var oParametro                       = new Object();
      oParametro.sExecucao             = 'getPontoParadaPorLogradouro';
      oParametro.iItinerarioLogradouro = oCboLogradouroItinerario.value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoBuscaParadas;

  js_divCarregando(_M('educacao.transporteescolar.tre4_vinculapontoparada.aguardando_pontos_parada'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno da busca pela paradas
 */
function js_retornoBuscaParadas(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  js_limpaPontosParada();

  if (oRetorno.aPontosParada.length == 0) {
    oCboPontosParada.add(new Option('Não há pontos de parada cadastrados para o logradouro selecionado.', ''));
  } else {

    oCboPontosParada.add(new Option('Selecione um ponto de parada.', ''));
    oRetorno.aPontosParada.each(function(oPontoParada, iSeq) {
      oCboPontosParada.add(new Option(oPontoParada.tre04_nome.urlDecode(), oPontoParada.tre04_sequencial));
    });
  }
}

/**
 * Busca as paradas vinculadas ao itinerario
 */
function js_buscaParadasItinerario() {

  var oParametro                  = new Object();
      oParametro.sExecucao        = 'getPontoParadaPorItinerario';
      oParametro.iLinhaTransporte = oInputCodigoLinha.value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoBuscaParadasItinerario;

  js_divCarregando(_M('educacao.transporteescolar.tre4_vinculapontoparada.aguardando_paradas_itinerario'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno da busca pelos pontos de parada vinculadas ao itinerario
 */
function js_retornoBuscaParadasItinerario(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.aPontosParada.length > 0) {

    oGridVinculosPontoParada.clearAll(true);
    oRetorno.aPontosParada.each(function(oPontoParada, iSeq) {

      var aLinha    = new Array();
          aLinha[0] = oPontoParada.iCodigo;
          aLinha[1] = oPontoParada.sLogradouro.urlDecode();
          aLinha[2] = oPontoParada.sBairro.urlDecode();
          aLinha[3] = oPontoParada.sPontoParada.urlDecode();
          aLinha[4] = oPontoParada.sItinerario.urlDecode();
          aLinha[5] = '<input id="pontoParada_"'+oPontoParada.iCodigo+ 
                              ' type="button" '+
                              ' value="E" '+
                              ' onClick="js_removerPontoParada('+oPontoParada.iCodigo+');" />';

      oGridVinculosPontoParada.addRow(aLinha);
    });

    oGridVinculosPontoParada.renderRows();
  }
}

/**
 * Vincula um ponto de parada ao logradouro do itinerario
 */
function js_vincularPontoParada() {

  if (js_verificaPreenchimentoCampos()) {

    var oParametro                       = new Object();
        oParametro.sExecucao             = 'vincularPontoParada';
        oParametro.iItinerarioLogradouro = oCboLogradouroItinerario.value;
        oParametro.iPontoParada          = oCboPontosParada.value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoVincularPontoParada;

    js_divCarregando(_M('educacao.transporteescolar.tre4_vinculapontoparada.aguardando_adicionar_vinculo'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno do vinculo do ponto de parada com o logradouro
 */
function js_retornoVincularPontoParada(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.sMensagem.urlDecode());
  if (oRetorno.iStatus == 1) {
    js_buscaParadasItinerario();
  }
}

/**
 * Remove um vinculo existente entre um ponto de parada e um logradouro do itinerario
 */
function js_removerPontoParada(iPontoParada) {

  if (confirm(_M('educacao.transporteescolar.tre4_vinculapontoparada.confirma_remover_ponto_parada'))) {

    var oParametro              = new Object();
        oParametro.sExecucao    = 'removerPontoParada';
        oParametro.iPontoParada = iPontoParada;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoRemoverPontoParada;

    js_divCarregando(_M('educacao.transporteescolar.tre4_vinculapontoparada.aguardando_remover_vinculo'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno da remocao do vinculo
 */
function js_retornoRemoverPontoParada(oResponse) {

   js_removeObj("msgBox");
   var oRetorno = eval('('+oResponse.responseText+')');

   alert(oRetorno.sMensagem.urlDecode());

   if (oRetorno.iStatus == 1) {
     js_buscaParadasItinerario();
   }
}

/**
 * Verifica se todos os campos obrigatorios para vinculo foram preenchidos
 */
function js_verificaPreenchimentoCampos() {

  if (empty(oInputCodigoLinha.value)) {

    alert(_M('educacao.transporteescolar.tre4_vinculapontoparada.codigo_linha_vazio'));
    return false;
  }

  if (empty(oCboLogradouroItinerario.value)) {

    alert(_M('educacao.transporteescolar.tre4_vinculapontoparada.logradouro_itinerario_vazio'));
    return false;
  }

  if (empty(oCboPontosParada.value)) {

    alert(_M('educacao.transporteescolar.tre4_vinculapontoparada.ponto_parada_vazio'));
    return false;
  }
  return true;
}

/**
 * Limpa o combo dos pontos de parada
 */
function js_limpaPontosParada() {

  if (oCboPontosParada.length > 0) {

    iTotalPontosParada = oCboPontosParada.length;
    for (var iContador = 0; iContador < iTotalPontosParada; iContador++) {
      oCboPontosParada.options.remove(iContador);
    }
  }
}

/**
 * Limpa o combo dos logradouros
 */
function js_limpaLogradouros() {

  if (oCboLogradouroItinerario.length > 0) {

    iTotalLogradouros = oCboLogradouroItinerario.length;
    for (var iContador = 0; iContador < iTotalLogradouros; iContador++) {
      oCboLogradouroItinerario.options.remove(iContador);
    }
  }
}

/**
 * Limpa os campos do formulário
 */
function js_limpaFormulario() {

  oInputCodigoLinha.value    = '';
  oInputDescricaoLinha.value = '';
  js_limpaPontosParada();
  js_limpaLogradouros();
  oGridVinculosPontoParada.clearAll(true);
}
</script>