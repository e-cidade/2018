<?
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
?>
<div style="margin-top: 20px;"  id='ctnAbas'></div>

<!-- CONTAINER DA ABA LINHA -->
<div id="ctnLinha">
  <form id="frmLinha" method="post" action="" class="container">
    <fieldset style="width: 25%;">
      <legend class="bold">Linha</legend>
      <table>
        <tr style="display: none;">
          <td><label class="bold">Sequencial:</label></td>
          <td id="ctnSequencial"></td>
        </tr>
        <tr>
          <td><label class="bold">Nome:</label></td>
          <td id="ctnNome"></td>
        </tr>
        <tr>
          <td><label class="bold">Abreviatura:</label></td>
          <td id="ctnAbreviatura"></td>
        </tr>
      </table>
    </fieldset>
    <input id="btnAcaoLinha" type="button" value="" />
    <input id="btnPesquisarLinha" type="button" value="Pesquisar" />
  </form>
</div>

<!-- CONTAINER DA ABA ITINERARIO -->
<div id="ctnItinerario">
  <fieldset style="width: 75%;" class="container">
    <legend class="bold">Itinerário</legend>

    <!-- CONTAINER DAS ABAS DE ITINERARIO -->
    <div id="ctnAbasItinerario"></div>

    <!-- CONTAINER DA ABA ITINERARIO-LOGRADOURO -->
    <div id="ctnItinerarioLogradouro">
      <form id="frmItinerarioLogradouro" method="post" action="" >
        <fieldset style="width: 85%;">
          <legend class="bold">Adicionar Logradouro</legend>
          <table>
            <tr style="display: none;">
              <td><label class="bold">Itinerário:</label></td>
              <td id="ctnCodigoItinerario"></td>
            </tr>
            <tr>
              <td><label class="bold">Itinerário:</label></td>
              <td id="ctnTipoItinerarioLogradouro"></td>
              <td id="ctnBtnReordenar"></td>
            </tr>
            <tr>
              <td id="ctnLinkLogradouro"></td>
              <td id="ctnBairroLogradouro" style="display: none;"></td>
              <td id="ctnCodigoLogradouro"></td>
              <td id="ctnDescricaoLogradouro"></td>
            </tr>
          </table>
        </fieldset>
        <div class="subcontainer">
          <input id="btnAdicionarLogradouro" type="button" value="Adicionar" />
          <input id="btnGerarRetorno"        type="button" value="Gerar Retorno Automático" />
        </div>
        <fieldset id="fieldLogradouroItinerario">
          <legend class="bold">Logradouros do Itinerário</legend>
          <div id="ctnGridItinerarioLogradouros"></div>
        </fieldset>
      </form>
    </div>

    <!-- CONTAINER DA ABA ITINERARIO-HORARIO -->
    <div id="ctnItinerarioHorario">
      <form id="grmItinerarioHorario" method="post" action="">
        <fieldset style="width: 99%;">
          <legend class="bold">Adicionar Horário</legend>
          <table>
            <tr>
              <td><label class="bold">Itinerário:</label></td>
              <td id="ctnTipoItinerarioHorario"></td>
            </tr>
            <tr>
              <td><label class="bold">Hora de partida:</label></td>
              <td id="ctnHoraPartida"></td>
            </tr>
            <tr>
              <td><label class="bold">Hora de chegada:</label></td>
              <td id="ctnHoraRetorno"></td>
            </tr>
          </table>
        </fieldset>
        <div style="padding-left: 700px;">
          <input id="btnAdicionarHorario" type="button" value="Adicionar" />
        </div>
        <fieldset id="fieldHorarioItinerario">
          <legend class="bold">Horários do Itinerário</legend>
          <div id="ctnGridItinerarioHorarios"></div>
        </fieldset>
      </form>
    </div>
  </fieldset>
</div>
<script>
require_once('scripts/classes/transporteescolar/DBViewReordenacaoItinerario.classe.js');

const MSG_FRMLINHASTRANSPORTE = 'educacao.transporteescolar.db_frmlinhastransporte.';
var sUrlRpc = 'tre4_linhastransporte.RPC.php';
var iOpcao  = <?=$iOpcao;?>;

/**
 * Validamos se a requisicao eh inclusao, alteracao ou exclusao, alterando o valor do botao acao e chamando a pesquisa
 * quando for alteracao e exclusao
 */
function js_validaRequisicao(iOpcao) {

  switch(iOpcao) {

    case 1:

      $('btnAcaoLinha').value   = 'Incluir';
      $('btnAcaoLinha').observe("click", function(event) {
        js_salvarLinha();
      });
      break;

    case 2:

      $('btnAcaoLinha').value    = 'Alterar';
      $('btnAcaoLinha').disabled = true;
      $('btnAcaoLinha').observe("click", function(event) {
        js_salvarLinha();
      });
      js_pesquisaLinhas();
      break;

    case 3:

      $('btnAcaoLinha').value    = 'Excluir';
      $('btnAcaoLinha').disabled = true;
      $('btnAcaoLinha').observe("click", function(event) {
        js_removerLinha();
      });
      js_pesquisaLinhas();
      break;
  }
}

js_validaRequisicao(iOpcao);

/**********************************************************************************************************/
/*######################################## ABA LINHA #####################################################*/
/**********************************************************************************************************/

$('btnPesquisarLinha').observe("click", function(event) {
  js_pesquisaLinhas();
});

/**
 * Elemento input do sequencial
 */
var oInputSequencial                       = document.createElement('input');
    oInputSequencial.id                    = 'oInputSequencial';
    oInputSequencial.name                  = 'oInputSequencial';
    oInputSequencial.style.width           = '50px';
    oInputSequencial.disabled              = true;
    oInputSequencial.style.backgroundColor = '#DEB887';
$('ctnSequencial').appendChild(oInputSequencial);

/**
 * Elemento input do nome
 */
var oInputNome             = document.createElement('input');
    oInputNome.id          = 'oInputNome';
    oInputNome.name        = 'oInputNome';
    oInputNome.style.width = '500px';
    oInputNome.maxLength   = 60;
$('ctnNome').appendChild(oInputNome);

$('oInputNome').observe("keyup", function(event) {
  $('oInputNome').value = $('oInputNome').value.toUpperCase();
});


/**
 * Elemento input da abreviatura
 */
var oInputAbreviatura             = document.createElement('input');
    oInputAbreviatura.id          = 'oInputAbreviatura';
    oInputAbreviatura.name        = 'oInputAbreviatura';
    oInputAbreviatura.style.width = '500px';
    oInputAbreviatura.maxLength   = 10;
$('ctnAbreviatura').appendChild(oInputAbreviatura);

$('oInputAbreviatura').observe("keyup", function(event) {
  $('oInputAbreviatura').value = $('oInputAbreviatura').value.toUpperCase();
});

if (iOpcao == 3) {

  oInputNome.readOnly              = true;
  oInputNome.style.backgroundColor = '#DEB887';

  oInputAbreviatura.readOnly              = true;
  oInputAbreviatura.style.backgroundColor = '#DEB887';
}

/**
 * Valida o preenchimento dos campos obrigatorios da aba Linha
 */
function js_verificaPreenchimentoAbaLinha() {

  if (oInputNome.value == '') {

    alert(_M(MSG_FRMLINHASTRANSPORTE + 'input_nome_linha_vazio'));
    return false;
  }
  return true;
}

/**
 * Salva as informacoes de uma nova linha
 */
function js_salvarLinha() {

  if (js_verificaPreenchimentoAbaLinha()) {

    var oParametro              = new Object();
        oParametro.sExecucao    = 'salvarLinha';
        oParametro.iCodigo      = oInputSequencial.value;
        oParametro.sNome        = encodeURIComponent(tagString(oInputNome.value));
        oParametro.sAbreviatura = encodeURIComponent(tagString(oInputAbreviatura.value));

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoSalvarLinha;

    js_divCarregando(_M(MSG_FRMLINHASTRANSPORTE + 'aguardando_salvar'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno do salvar as informacoes da linha
 */
function js_retornoSalvarLinha(oResponse) {

   js_removeObj("msgBox");
   var oRetorno = eval('('+oResponse.responseText+')');

   alert(oRetorno.sMensagem.urlDecode());
   if (oRetorno.iStatus == 1) {

     oInputSequencial.value = oRetorno.iCodigo;
     oAbaItinerario.lBloqueada = false;
   }
}

/**
 * Pesquisa as linhas cadastradas
 */
function js_pesquisaLinhas() {

  var sUrl  = 'func_linhatransporte.php';
      sUrl += '?funcao_js=parent.js_mostraLinhas|tre06_sequencial|tre06_nome|tre06_abreviatura';

  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_linhatransporte', sUrl, 'Pesquisa Linhas de Transporte', true);
}

/**
 * Retorna os valores referentes a linha selecionada
 * Desbloqueia o botao Alterar/Excluir
 */
function js_mostraLinhas() {

  if (iOpcao == 1) {

    db_iframe_linhatransporte.hide();
    return false;
  }

  oInputSequencial.value     = arguments[0];
  oInputNome.value           = arguments[1];
  oInputAbreviatura.value    = arguments[2];
  $('btnAcaoLinha').disabled = false;
  db_iframe_linhatransporte.hide();
  js_buscaLogradourosItinerarios();
  js_buscaHorariosItinerarios();
}

/**
 * Exclui uma linha
 */
function js_removerLinha() {

  var oLinha            = new Object();
      oLinha.sNomeLinha = oInputNome.value;

  if (confirm(_M(MSG_FRMLINHASTRANSPORTE + 'confirma_exclusao_linha', oLinha))) {

    var oParametro              = new Object();
        oParametro.sExecucao    = 'removerLinha';
        oParametro.iCodigoLinha = oInputSequencial.value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoRemoverLinha;

    js_divCarregando(_M(MSG_FRMLINHASTRANSPORTE + 'aguardando_remover_linha'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno da exclusao da linha
 */
function js_retornoRemoverLinha(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');
  alert(oRetorno.sMensagem.urlDecode());

  if (oRetorno.iStatus == 1) {

    js_limpaCamposLinha();
    js_pesquisaLinhas();
  }
}

/**
 * Limpa os campos da aba Linha
 */
function js_limpaCamposLinha() {

  oInputSequencial.value  = '';
  oInputNome.value        = '';
  oInputAbreviatura.value = '';
}

/**********************************************************************************************************/
/*################################### ABA ITINERARIO => LOGRADOURO #######################################*/
/**********************************************************************************************************/

$('fieldLogradouroItinerario').style.width = '1370px';

/**
 * Elemento input com o codigo do itinerario
 */
var oInputCodigoItinerario                       = document.createElement('input');
    oInputCodigoItinerario.id                    = 'oInputCodigoItinerario';
    oInputCodigoItinerario.name                  = 'oInputCodigoItinerario';
    oInputCodigoItinerario.style.width           = '100px';
    oInputCodigoItinerario.readOnly              = true;
    oInputCodigoItinerario.style.backgroundColor = '#DEB887';
$('ctnCodigoItinerario').appendChild(oInputCodigoItinerario);

/**
 * Elemento select do campo Tipo de Itinerario
 */
var oCboTipoItinerarioLogradouro             = document.createElement('select');
    oCboTipoItinerarioLogradouro.id          = 'oCboTipoItinerarioLogradouro';
    oCboTipoItinerarioLogradouro.style.width = '100%';
    oCboTipoItinerarioLogradouro.add(new Option("Ida", 1));
    oCboTipoItinerarioLogradouro.add(new Option("Retorno", 2));
$('ctnTipoItinerarioLogradouro').appendChild(oCboTipoItinerarioLogradouro);

/**
 * Elemento input button para abrir uma janela e reordenar os logradouros.
 */
var oBtnReordenar         = document.createElement('input');
    oBtnReordenar.id      = 'btnReordenar';
    oBtnReordenar.type    = 'button';
    oBtnReordenar.value   = 'Reordenar';
    oBtnReordenar.title   = 'Reordena os logradouros';
    oBtnReordenar.setAttribute('onclick', 'js_abreViewReordenar()');
$('ctnBtnReordenar').appendChild(oBtnReordenar);

function js_abreViewReordenar() {

  if (oGridItinerarios.aRows.length > 1) {

    var oDBViewReordenacao = new DBViewReordenacaoItinerario(
                                                             'oDBViewReordenacao',
                                                             oInputSequencial.value,
                                                             oCboTipoItinerarioLogradouro.value
                                                            );
    oDBViewReordenacao.show();
    oDBViewReordenacao.setCallbackSalvar(function() {

      oDBViewReordenacao.fechar()
      js_buscaLogradourosItinerarios();
    });
  } else {
    alert(_M(MSG_FRMLINHASTRANSPORTE + 'nenhum_logradouro_para_reordenar'));
  }
}

/**
 * Elemento a href do Logradouro
 */
var oLinkLogradouro                  = document.createElement('a');
    oLinkLogradouro.innerHTML        = 'Logradouro:';
    oLinkLogradouro.style.fontWeight = 'bold';
    oLinkLogradouro.href             = '#';
    oLinkLogradouro.addClassName('DBAncora');
    oLinkLogradouro.addClassName('dbancora');

    oLinkLogradouro.setAttribute('onclick', 'js_pesquisaLogradouro(true);');
$('ctnLinkLogradouro').appendChild(oLinkLogradouro);

/**
 * Elemento input do codigo de cadenderbairrocadenderrua
 */
var oInputBairroLogradouro             = document.createElement('input');
    oInputBairroLogradouro.id          = 'oInputBairroLogradouro';
    oInputBairroLogradouro.name        = 'oInputBairroLogradouro';
    oInputBairroLogradouro.style.width = '100px';
$('ctnBairroLogradouro').appendChild(oInputBairroLogradouro);

/**
 * Elemento input do codigo do logradouro
 */
var oInputCodigoLogradouro             = document.createElement('input');
    oInputCodigoLogradouro.id          = 'oInputCodigoLogradouro';
    oInputCodigoLogradouro.name        = 'oInputCodigoLogradouro';
    oInputCodigoLogradouro.style.width = '100px';
$('ctnCodigoLogradouro').appendChild(oInputCodigoLogradouro);

/**
 * Elemento input da descricao do logradouro
 */
var oInputDescricaoLogradouro                       = document.createElement('input');
    oInputDescricaoLogradouro.id                    = 'oInputDescricaoLogradouro';
    oInputDescricaoLogradouro.name                  = 'oInputDescricaoLogradouro';
    oInputDescricaoLogradouro.style.width           = '1187px';
    oInputDescricaoLogradouro.readOnly              = true;
    oInputDescricaoLogradouro.style.backgroundColor = '#DEB887';
$('ctnDescricaoLogradouro').appendChild(oInputDescricaoLogradouro);

/**
 * Grid dos Itinerarios
 */
var oGridItinerarios              = new DBGrid("gridItinerarios");
    oGridItinerarios.nameInstance = 'oGridItinerarios';
    oGridItinerarios.setCellAlign(new Array("center", "left", "left", "left", "center", "center"));
    oGridItinerarios.setCellWidth(new Array("5%", "53%", "25%", "7%", "5%", "5%"));
    oGridItinerarios.setHeader(new Array("Código", "Logradouro", "Bairro", "Ida/Retorno", "Ordem", "Ação"));
    oGridItinerarios.setHeight(200);
    oGridItinerarios.aHeaders[0].lDisplayed = false;
    oGridItinerarios.show($('ctnGridItinerarioLogradouros'));

/**
 * Observamos o click do botao Adicionar, chamando o metodo js_adicionarRua, salvando o logradouro selecionado
 */
$('btnAdicionarLogradouro').observe("click", function(event) {
  js_adicionarLogradouro();
});


/**
 * Observamos o change do codigo do logradouro, chamando o metodo js_pesquisaLogradouro para retornar a descricao
 */
$('oInputCodigoLogradouro').observe("change", function(event) {
  js_pesquisaLogradouro(false);
});

/**
 * Pesquisa os logradouros a serem vinculados ao itinerario
 */
function js_pesquisaLogradouro(lMostra) {

  var sUrl = 'func_bairrologradouro.php?funcao_js=parent.js_mostraLogradouro';

  if (lMostra) {
    sUrl += '|db87_cadenderrua|db74_descricao|db87_sequencial';
  } else {

    if (oInputCodigoLogradouro.value != '') {
      sUrl += '&pesquisa_chave='+oInputCodigoLogradouro.value;
    }
  }
  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_bairrologradouro', sUrl, 'Pesquisa Logradouro', lMostra);
}

/**
* Retorno da pesquisa pelos logradouros
*/
function js_mostraLogradouro() {

 if (arguments[0] !== true && arguments[0] !== false) {

   oInputCodigoLogradouro.value    = arguments[0];
   oInputDescricaoLogradouro.value = arguments[1];
   oInputBairroLogradouro.value    = arguments[2];
 } else if (arguments[0] === true) {

   oInputBairroLogradouro.value    = '';
   oInputCodigoLogradouro.value    = '';
   oInputDescricaoLogradouro.value = arguments[1];
 } else {

   oInputBairroLogradouro.value    = arguments[1];
   oInputDescricaoLogradouro.value = arguments[3];
 }
 db_iframe_bairrologradouro.hide();
}

/**
 * Adicionamos o logradouro ao itinerario
 */
function js_adicionarLogradouro() {

  if (empty(oInputCodigoLogradouro.value)) {

    alert(_M(MSG_FRMLINHASTRANSPORTE + 'codigo_logradouro_vazio'));
    return false;
  }

  var oParametro                         = new Object();
      oParametro.sExecucao               = 'adicionarItinerarioLogradouro';
      oParametro.iCodigoLinha            = oInputSequencial.value;
      oParametro.iCodigoBairroLogradouro = oInputBairroLogradouro.value;
      oParametro.iTipo                   = oCboTipoItinerarioLogradouro.value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoAdicionarLogradouro;

  js_divCarregando(_M(MSG_FRMLINHASTRANSPORTE + 'aguardando_adicionar_logradouro'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno do adicionar o logradouro
 */
function js_retornoAdicionarLogradouro(oResponse) {

   js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.sMensagem.urlDecode());
  if (oRetorno.iStatus == 1) {

    oInputCodigoItinerario.value    = oRetorno.iCodigoItinerario;
    oInputBairroLogradouro.value    = '';
    oInputCodigoLogradouro.value    = '';
    oInputDescricaoLogradouro.value = '';
    js_buscaLogradourosItinerarios();
  }
}

/**
 * Busca os logradouros vinculados ao itinerario
 */
function js_buscaLogradourosItinerarios() {

  var oParametro              = new Object();
      oParametro.sExecucao    = 'getLogradouros';
      oParametro.iCodigoLinha = oInputSequencial.value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoBuscaLogradourosItinerarios;

  js_divCarregando(_M(MSG_FRMLINHASTRANSPORTE + 'aguardando_buscar_logradouros'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno da busca pelos logradouros vinculados ao itinerario
 */
function js_retornoBuscaLogradourosItinerarios(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  oGridItinerarios.clearAll(true);
  if (oRetorno.aLogradouros.length > 0) {

    oRetorno.aLogradouros.each(function(oLogradouro, iSeq) {

      var aLinha    = new Array();
          aLinha[0] = oLogradouro.iCodigoLinhaLogradouro;
          aLinha[1] = oLogradouro.sNomeLogradouro.urlDecode();
          aLinha[2] = oLogradouro.sBairro.urlDecode();
          aLinha[3] = "Ida";
          if (oLogradouro.iTipo == 2) {
            aLinha[3] = "Retorno";
          }
          aLinha[4] = oLogradouro.iOrdem;
          aLinha[5] = '<input id="removerLogradouro_'+oLogradouro.iCodigoLinhaLogradouro+'"'+
                              'type="button"'+
                              'value="E"'+
                              'onClick="js_removerLogradouro('+oLogradouro.iCodigoLinhaLogradouro+')"';

      oGridItinerarios.addRow(aLinha);
    });

    oGridItinerarios.renderRows();
  }
}

/**
 * Remove um logradouro vinculado ao itinerario
 */
function js_removerLogradouro(iCodigoLinhaLogradouro) {

  if (confirm(_M(MSG_FRMLINHASTRANSPORTE + 'remover_logradouro'))) {

    var oParametro                        = new Object();
        oParametro.sExecucao              = 'removerLogradouro';
        oParametro.iCodigoLinhaLogradouro = iCodigoLinhaLogradouro;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoRemoverLogradouro;

    js_divCarregando(_M(MSG_FRMLINHASTRANSPORTE + 'aguardando_remover_logradouro'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno da remocao do logradouro
 */
function js_retornoRemoverLogradouro(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.sMensagem.urlDecode());
  if (oRetorno.iStatus == 1) {
    js_buscaLogradourosItinerarios();
  }
}

/**********************************************************************************************************/
/*################################### ABA ITINERARIO => HORARIOS #########################################*/
/**********************************************************************************************************/

$('fieldHorarioItinerario').style.width = '1370px';

$('btnAdicionarHorario').observe("click", function(event) {
  js_adicionarHorario();
});

/**
 * Elemento select do campo Tipo de Itinerario
 */
var oCboTipoItinerarioHorario             = document.createElement('select');
    oCboTipoItinerarioHorario.id          = 'oCboTipoItinerarioHorario';
    oCboTipoItinerarioHorario.style.width = '100%';
    oCboTipoItinerarioHorario.add(new Option("Ida", 1));
    oCboTipoItinerarioHorario.add(new Option("Retorno", 2));
$('ctnTipoItinerarioHorario').appendChild(oCboTipoItinerarioHorario);

/**
 * Elemento input da hora de partida
 */
var oInputHoraPartida             = document.createElement('input');
    oInputHoraPartida.id          = 'oInputHoraPartida';
    oInputHoraPartida.name        = 'oInputHoraPartida';
    oInputHoraPartida.style.width = '80px';
$('ctnHoraPartida').appendChild(oInputHoraPartida);

/**
 * Elemento input da hora de retorno
 */
var oInputHoraChegada             = document.createElement('input');
    oInputHoraChegada.id          = 'oInputHoraChegada';
    oInputHoraChegada.name        = 'oInputHoraChegada';
    oInputHoraChegada.style.width = '80px';
$('ctnHoraRetorno').appendChild(oInputHoraChegada);

/**
 * Grid dos horarios do itinerario
 */
var oGridHorarios              = new DBGrid("gridHorarios");
    oGridHorarios.nameInstance = 'oGridHorarios';
    oGridHorarios.setHeader(new Array('Código', 'Hora de Partida', 'Hora de Chegada', 'Itinerário', 'Ação'));
    oGridHorarios.setCellAlign(new Array('center', 'left', 'left', 'left', 'center'));
    oGridHorarios.setCellWidth(new Array('10%', '35%', '35%', '10%', '10%'));
    oGridHorarios.setHeight(200);
    oGridHorarios.aHeaders[0].lDisplayed = false;
    oGridHorarios.show($('ctnGridItinerarioHorarios'));

/**
 * Formata o campo da hora
 */
function js_formataHora() {

  new MaskedInput("#oInputHoraPartida", "00:00", {placeholder:"0"});
  new MaskedInput("#oInputHoraChegada", "00:00", {placeholder:"0"});
}

/**
 * Validamos se as horas foram preenchidas e se estas sao validas
 */
function js_validaHora() {

  if (empty(oInputHoraPartida.value)) {

    alert(_M(MSG_FRMLINHASTRANSPORTE + 'hora_partida_vazio'));
    return false;
  }

  var iHoraPartida    = $F('oInputHoraPartida').substr(0, 2);
  var iMinutosPartida = $F('oInputHoraPartida').substr(3, 2);

  if (iHoraPartida > 23 || iMinutosPartida > 59) {

    alert(_M(MSG_FRMLINHASTRANSPORTE + 'formato_hora_partida_invalido'));
    $('oInputHoraPartida').value = "00:00";
    $('oInputHoraPartida').focus();
    return false;
  }

  if (empty(oInputHoraChegada.value)) {

    alert(_M(MSG_FRMLINHASTRANSPORTE + 'hora_chegada_vazio'));
    return false;
  }

  var iHoraChegada    = $F('oInputHoraChegada').substr(0, 2);
  var iMinutosChegada = $F('oInputHoraChegada').substr(3, 2);

  if (iHoraChegada > 23 || iMinutosChegada > 59) {

    alert(_M(MSG_FRMLINHASTRANSPORTE + 'formato_hora_chegada_invalido'));
    $('oInputHoraChegada').value = "00:00";
    $('oInputHoraChegada').focus();
    return false;
  }

  if (iHoraPartida > iHoraChegada ||
      (iHoraChegada == iHoraPartida) && (iMinutosPartida > iMinutosChegada)
      ) {

    alert(_M(MSG_FRMLINHASTRANSPORTE + 'hora_partida_maior'));
    return false;
  }

  return true;
}

/**
 * Adiciona um horario ao itinerario
 */
function js_adicionarHorario() {

  if (js_validaHora()) {

    var oParametro                  = new Object();
        oParametro.sExecucao        = 'adicionarItinerarioHorario';
        oParametro.iItinerario      = oCboTipoItinerarioHorario.value;
        oParametro.sHoraPartida     = oInputHoraPartida.value;
        oParametro.sHoraChegada     = oInputHoraChegada.value;
        oParametro.iLinhaTransporte = oInputSequencial.value;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoAdicionarHorario;

    js_divCarregando(_M(MSG_FRMLINHASTRANSPORTE + 'aguardando_adicionar_horario'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno da adicao do novo horario
 */
function js_retornoAdicionarHorario(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.sMensagem.urlDecode());
  if (oRetorno.iStatus == 1) {

    js_limpaCamposHorario();
    js_buscaHorariosItinerarios();
  }
}

/**
 * Limpa os campos da aba horario
 */
function js_limpaCamposHorario() {

  oInputHoraPartida.value = '';
  oInputHoraChegada.value = '';
}

/**
 * Busca os horarios do itinerario
 */
function js_buscaHorariosItinerarios() {

  var oParametro              = new Object();
      oParametro.sExecucao    = 'getHorariosItinerarios';
      oParametro.iCodigoLinha = oInputSequencial.value;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoBuscaHorariosItinerarios;

  js_divCarregando(_M(MSG_FRMLINHASTRANSPORTE + 'aguardando_buscar_horarios'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno da busca pelos horarios do itinerario
 */
function js_retornoBuscaHorariosItinerarios(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  oGridHorarios.clearAll(true);
  if (oRetorno.aHorariosItinerario.length > 0) {

    oRetorno.aHorariosItinerario.each(function(oHorario, iSeq) {

      var aLinha    = new Array();
          aLinha[0] = oHorario.iCodigoHorario;
          aLinha[1] = oHorario.sHoraPartida.urlDecode();
          aLinha[2] = oHorario.sHoraRetorno.urlDecode();
          aLinha[3] = "Ida";
          if (oHorario.iItinerario == 2) {
            aLinha[3] = "Retorno";
          }
          aLinha[4] = '<input id="removeHorario_"'+oHorario.iCodigoHorario+
                              ' type="button"'+
                              ' value="E"'+
                              ' onClick="js_removerHorario('+oHorario.iCodigoHorario+')" />';

      oGridHorarios.addRow(aLinha);
    });

    oGridHorarios.renderRows();
  }
}

/**
 * Remove um horario do itinerario
 */
function js_removerHorario(iCodigoHorario) {

  if (confirm(_M(MSG_FRMLINHASTRANSPORTE + 'remover_itinerario'))) {

    var oParametro                = new Object();
        oParametro.sExecucao      = 'removerHorario';
        oParametro.iCodigoHorario = iCodigoHorario;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoRemoverHorario;

    js_divCarregando(_M(MSG_FRMLINHASTRANSPORTE + 'aguardando_remover_horario'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno da remocao do horario do itinerario
 */
function js_retornoRemoverHorario(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  alert(oRetorno.sMensagem.urlDecode());
  if (oRetorno.iStatus == 1) {
    js_buscaHorariosItinerarios();
  }
}

js_formataHora();

/**********************************************************************************************************/
/*###################################### CRIACAO DAS ABAS ################################################*/
/**********************************************************************************************************/

/**
 * Cria as abas Linha e Itinerario
 */
var oDBAba         = new DBAbas($('ctnAbas'));
var oAbaLinha      = oDBAba.adicionarAba("Linha", $('ctnLinha'));
var oAbaItinerario = oDBAba.adicionarAba("Itinerário", $('ctnItinerario'));

if (iOpcao == 1) {
  oAbaItinerario.lBloqueada = true;
}

/**
 * Cria as abas filhas de Itinerario (Ruas e Horarios)
 */
var oDBAbaItinerario         = new DBAbas($('ctnAbasItinerario'));
var oAbaItinerarioLogradouro = oDBAbaItinerario.adicionarAba("Logradouro", $('ctnItinerarioLogradouro'));
var oAbaItinerarioHorario    = oDBAbaItinerario.adicionarAba("Horários", $('ctnItinerarioHorario'));


/**********************************************************************************************************/
/*########################## GERA O ITINERÁRIO DE RETORNO ATRAVÉS DA IDA #################################*/
/**********************************************************************************************************/
$('btnGerarRetorno').addEventListener('click', function () {

  var oParametro = {
    'sExecucao'    : 'gerarRetorno',
    'iCodigoLinha' : oInputSequencial.value
  };

  new AjaxRequest(sUrlRpc, oParametro, function(oRetorno, lErro){

    alert(oRetorno.sMensagem.urlDecode());
    if ( lErro ) {
      return;
    }
    js_buscaLogradourosItinerarios();
  }).setMessage(_M(MSG_FRMLINHASTRANSPORTE + 'aguarde_gerando_retorno' )).execute();
});
</script>