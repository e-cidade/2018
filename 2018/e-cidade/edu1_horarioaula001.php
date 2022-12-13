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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>

<style type="text/css">
.tamanhoInputHora {

  width:   70px;
  margin:  0;
  padding: 0;
  text-align: right;
}

</style>
  <div class="container">

    <form>
      <fieldset>
        <legend>Horários de Aula</legend>
        <table class="form-container">
          <tr>
            <td nowrap="nowrap" class="bold">
              <label for="turnoEscola">Turno:</label>
            </td>
            <td>
              <select id='turnoEscola'>
                <option value="">Selecione</option>
              </select>
            </td>
          </tr>
        </table>

        <fieldset style='width:600px;'>
          <legend>Períodos</legend>
          <div id='ctnGridPeriodosAula'></div>
        </fieldset>

      </fieldset>

      <input type="button" id='processarHorarioAula'  value='Salvar'   acao    = 'I' />
      <input type="button" id='excluirVinculoPeriodo' value='Excluir'  style   = "display:none;" />
      <input type="button" id='cancelar'              value='Cancelar' onclick = "js_limpaGrade();" />

    </form>


  </div>
  <div class="subcontainer">

    <fieldset style='width:600px;'>
      <legend>Horários Inclusos</legend>
      <div id='cntGridHorariosInclusos'></div>
    </fieldset>

  </div>

<script type="text/javascript">

const MSG_HORARIOAULA = 'educacao.escola.edu1_horarioaula.';
const RPC_HORARIOAULA = 'edu4_horarioaula.RPC.php';

var aTurnosCadastrados         = [];
var aPeriodosEscolaCadastrados = []; // Períodos sem vínculo com a escola
var aPeriodosInclusos          = []; // Períodos de aula vínculados com a escola

var oGridPeriodosEscola          = new DBGrid('gridPeriodosEscola');
oGridPeriodosEscola.nameInstance = 'oGridPeriodosEscola';
oGridPeriodosEscola.setCheckbox(0);
oGridPeriodosEscola.setCellWidth( [ '0%', '35%', '15%', '15%', '15%', '0%', '20%' ] );
oGridPeriodosEscola.setHeader( [ 'codigo', 'Período', 'H. Início', 'H. Fim', 'Duração', 'periodo_aula', 'Referência' ] );
oGridPeriodosEscola.setCellAlign( [ 'left', 'left', 'left', 'left', 'left', 'left', 'left' ] );
oGridPeriodosEscola.setHeight(130);
oGridPeriodosEscola.aHeaders[1].lDisplayed = false;
oGridPeriodosEscola.aHeaders[6].lDisplayed = false;
oGridPeriodosEscola.show($("ctnGridPeriodosAula"));

var oGridHorariosInclusos          = new DBGrid('gridHorariosInclusos');
oGridHorariosInclusos.nameInstance = "oGridHorariosInclusos";
oGridHorariosInclusos.setCellWidth( [ '42.5%', '42.5%', '15%'] );
oGridHorariosInclusos.setHeader( [ 'Turno', 'Horário', 'Ação' ] );
oGridHorariosInclusos.setCellAlign( [ 'left', 'left', 'center' ] );
oGridHorariosInclusos.setHeight(130);
oGridHorariosInclusos.show($("cntGridHorariosInclusos"));

$(function () {

  js_divCarregando(_M(MSG_HORARIOAULA + "carregando_turnos"), "msgBox");
  js_buscaTurnos();
  js_buscaPeriodosAula();
  js_buscaPeriodosVinculados();
  js_removeObj("msgBox");

  for (var iTurno in aPeriodosInclusos) {

    for ( var iIndice in $('turnoEscola').options) {

      if ($('turnoEscola').options[iIndice].value == iTurno) {
        $('turnoEscola').options[iIndice].setAttribute('disabled', 'disabled');
      }
    }
  }
})();

/**
 * Busca os turnos cadastrados
 */
function js_buscaTurnos() {

  var oParametros       = { 'exec' : 'getTurnos' };
  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function (oAjax) {

    var oRetorno = eval('(' + oAjax.responseText + ')' );

    aTurnosCadastrados = oRetorno.aTurnos;
    js_montaTurnos();
  };

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
}


/**
 * Monta o combobox com os turnos cadastrados
 * @return
 */
function js_montaTurnos() {

  aTurnosCadastrados.each( function( oTurno ) {

    var oOption = new Option(oTurno.sDescricao.urlDecode(), oTurno.iCodigo);

    oOption.setAttribute('integral', 'false');
    oOption.setAttribute('turnoreferente', oTurno.aTurnosReferente);

    if ( oTurno.aTurnosReferente.length > 1 ) {
      oOption.setAttribute('integral', 'true');
    }

    $('turnoEscola').add(oOption);
  });
}


/**
 * Busca os períodos cadastrados na secretaria da educação
 * @return
 */
function js_buscaPeriodosAula () {

  if (aPeriodosEscolaCadastrados.length > 0 ) {

    js_renderizaPeriodos();
    return;
  }

  var oParametros = { 'exec' : 'getPeriodosEscola' };

  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function (oAjax) {

    var oRetorno = eval('(' + oAjax.responseText + ')' );

    aPeriodosEscolaCadastrados = oRetorno.aPeriodos;
    js_renderizaPeriodos();
  };

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
}


function js_renderizaPeriodos () {

  oGridPeriodosEscola.clearAll(true);
  aPeriodosEscolaCadastrados.each (function ( oPeriodo ) {

    var aLinha = [];
    aLinha.push(oPeriodo.iCodigo);
    aLinha.push(oPeriodo.sDescricao.urlDecode());
    aLinha.push('');
    aLinha.push('');
    aLinha.push('');
    aLinha.push('');
    aLinha.push('');

    oGridPeriodosEscola.addRow(aLinha);
  });

  oGridPeriodosEscola.renderRows();

  aPeriodosEscolaCadastrados.each (function ( oPeriodo, i ) {

    var sIdDuracao           = 'duracao_'+oPeriodo.iCodigo;
    var sIdCodigoVinculo     = 'codigo_vinculo_periodo_'+oPeriodo.iCodigo;
    var oInputHoraDuracao    = new Element('input' , {'type':'text', 'class' : 'tamanhoInputHora readonly', 'id':sIdDuracao});
    oInputHoraDuracao.setAttribute('disabled', 'disabled');

    var sIdTurnoReferente    = 'turno_referente_'+oPeriodo.iCodigo;
    var oComboTurnoReferente = new Element('select', {'type':'select', 'id': sIdTurnoReferente});
    oComboTurnoReferente.add( new Option('Selecione...', 'null') );
    oComboTurnoReferente.add( new Option('Manhã', '1') );
    oComboTurnoReferente.add( new Option('Tarde', '2') );
    oComboTurnoReferente.add( new Option('Noite', '3') );
    oComboTurnoReferente.add( new Option('Manhã/Tarde', '4') );
    oComboTurnoReferente.add( new Option('Tarde/Noite', '5') );

    oComboTurnoReferente.setAttribute('disabled', 'disabled');

    var oInputVinculo = new Element('input', {'type':'hidden', 'class' : 'readonly', 'id':sIdCodigoVinculo});
    var oHoraInicio   = js_generateInput (oPeriodo, 'inicio');
    var oHoraFim      = js_generateInput (oPeriodo, 'fim');

    oHoraInicio.show( $(oGridPeriodosEscola.aRows[i].aCells[3].sId) );
    oHoraFim.show( $(oGridPeriodosEscola.aRows[i].aCells[4].sId) );

    $(oGridPeriodosEscola.aRows[i].aCells[0].sId).setAttribute('ordem', oPeriodo.iOrdem );
    $(oGridPeriodosEscola.aRows[i].aCells[5].sId).appendChild( oInputHoraDuracao );
    $(oGridPeriodosEscola.aRows[i].aCells[6].sId).appendChild( oInputVinculo );
    $(oGridPeriodosEscola.aRows[i].aCells[7].sId).appendChild( oComboTurnoReferente );
  });
}


function js_generateInput (oDadosPeriodo, sTipo) {

  var sId    = sTipo + '_' +  oDadosPeriodo.iCodigo;
  var oInput = new DBInputHora( new Element('input', {'type':'text', 'id' : sId}) );
  oInput.getElement().setAttribute( 'tipo', sTipo );
  oInput.getElement().setAttribute( 'nome_periodo', oDadosPeriodo.sDescricao.urlDecode() );
  oInput.getElement().setAttribute( 'codigo_periodo', oDadosPeriodo.iCodigo );
  oInput.getElement().setAttribute( 'ordem', oDadosPeriodo.iOrdem );
  oInput.getElement().addClassName( 'tamanhoInputHora' );
  oInput.getElement().addClassName( 'readonly' );
  oInput.getElement().setAttribute('disabled', 'disabled');
  oInput.getElement().onchange = function (event) {
    js_calculaIntervalo(oInput.getElement(), event);
  };

  return oInput;
}


/**
 * Valida o horário informado para período e calcula a duração do período
 * @param  HTMLInputElement oElement Elemento que disparou o change
 * @param  event            oEvent   Evento disparado
 * @return {void}
 */
function js_calculaIntervalo(oElement, oEvent) {

  var iCodigo = oElement.getAttribute('codigo_periodo');

  var sIdDuracao = 'duracao_'+iCodigo;
  var sIdInicio  = 'inicio_'+iCodigo;
  var sIdFim     = 'fim_'+iCodigo;

  if (oElement.getAttribute('tipo') == 'fim' && $F(sIdInicio) == '') {

    $(sIdInicio).focus();
    alert( _M( MSG_HORARIOAULA + 'informe_hora_inicio'));
    return false;
  }

  if ($F(sIdInicio) == '' && $F(sIdFim) == '') {

    alert( _M( MSG_HORARIOAULA + 'informe_hora_inicio_fim'));
    return false;
  }

  if ($F(sIdInicio) != '' && $F(sIdFim) != '') {

    var aHoraInicio    = $(sIdInicio).value.split(':');
    var aHoraFim       = $(sIdFim).value.split(':');
    var iMinutosInicio = new Number( ( aHoraInicio[0] * 60 ) + aHoraInicio[1] );
    var iMinutosFim    = new Number( ( aHoraFim[0] * 60 ) + aHoraFim[1] );
    var oDataInicio    = new Date();
    var oDataFim       = new Date();

    oDataInicio.setHours(aHoraInicio[0], aHoraInicio[1]);
    oDataFim.setHours(aHoraFim[0], aHoraFim[1]);

    if (iMinutosFim <= iMinutosInicio ) {

      alert(_M( MSG_HORARIOAULA +'hora_final_menor_igual'));

      $(sIdFim).value     = "";
      $(sIdDuracao).value = "";

      oEvent.stopImmediatePropagation();
      setTimeout(function (event) {
        $(sIdFim).focus();
      }, 1);
      return false;
    }

    /**
     * Calcura a duração do período
     */
    var oDuracao = new Date();
    oDuracao.setTime(oDataFim.getTime() - oDataInicio.getTime());

    var iHoras   = js_strLeftPad(oDuracao.getUTCHours(), 2, '0');
    var iMinutos = js_strLeftPad(oDuracao.getUTCMinutes(), 2, '0');

    $(sIdDuracao).value = iHoras + ':' + iMinutos;
  }

  js_validaConflitoEntrePeriodos(oElement, oEvent);
  return true;
}

/**
 * Valida o conflito entre os períodos informados
 * @param  {Element} oElementoAtual  Elemento alterado no momento
 * @param  {event}   oEvent
 * @return {Boolean}
 */
function js_validaConflitoEntrePeriodos(oElementoAtual, oEvent) {

  var aPeriodosAnteriores  = [];
  var aPeriodosPosteriores = [];

  $$('#gridgridPeriodosEscola input[type="checkbox"]:checked').each ( function(oElemento) {

    if( oElemento.getAttribute('value') ) {

      var iOrdem      = parseInt( oElemento.parentNode.getAttribute('ordem') );
      var iOrdemAtual = parseInt( oElementoAtual.getAttribute('ordem') );

      if ( iOrdem < iOrdemAtual ) {
        aPeriodosAnteriores.push( oElemento );
      } else if (  iOrdem > iOrdemAtual  ) {
        aPeriodosPosteriores.push( oElemento );
      }
    }
  });

  var oHoraAtual = new Date();

  /**
   * Valida a hora inicial do período alterado com a hora final do anterior
   */
  if ( oElementoAtual.getAttribute('tipo') == 'inicio' && aPeriodosAnteriores.length > 0) {

    var iCodigoPeriodoAnterior = aPeriodosAnteriores[aPeriodosAnteriores.length - 1].value;
    var oElementoAnterior      = $('fim_' + iCodigoPeriodoAnterior);

    if (oElementoAnterior.value == '') {
      return;
    }

    var aHoraAtual       = oElementoAtual.value.split(':');
    var aHoraAnterior    = oElementoAnterior.value.split(":");
    var iMinutosAtual    = new Number( ( aHoraAtual[0] * 60 ) + aHoraAtual[1] );
    var iMinutosAnterior = new Number( ( aHoraAnterior[0] * 60 ) + aHoraAnterior[1] );
    var oHoraAnterior    = new Date();

    oHoraAtual.setHours( aHoraAtual[0], aHoraAtual[1] );
    oHoraAnterior.setHours( aHoraAnterior[0], aHoraAnterior[1] );

    if ( iMinutosAtual < iMinutosAnterior ) {

      var oMsgErro = {'sPeriodo' : oElementoAnterior.getAttribute('nome_periodo')};
      alert ( _M (MSG_HORARIOAULA + 'hora_inicio_conflita_periodo', oMsgErro) );

      oElementoAtual.value = '';
      oEvent.stopImmediatePropagation();
      setTimeout(function (event) {
        oElementoAtual.focus();
      }, 1);

      $('processarHorarioAula').setAttribute('disabled','disabled');
      return false;
    }
  }

  /**
   * Valida a hora final do período alterado com a hora inicial do próximo
   */
  if ( oElementoAtual.getAttribute('tipo') == 'fim' && aPeriodosPosteriores.length > 0) {

    var iCodigoPeriodoPosterior = aPeriodosPosteriores[0].value;
    var oElementoPosterior      = $('inicio_' + iCodigoPeriodoPosterior);

    if ( oElementoPosterior.value == '' ) {
      return;
    }

    var aHoraAtual        = oElementoAtual.value.split(':');
    var aHoraPosterior    = oElementoPosterior.value.split(":");
    var iMinutosAtual     = new Number( ( aHoraAtual[0] * 60 ) + aHoraAtual[1] );
    var iMinutosPosterior = new Number( ( aHoraPosterior[0] * 60 ) + aHoraPosterior[1] );
    var oHoraPosterior    = new Date();

    oHoraAtual.setHours( aHoraAtual[0], aHoraAtual[1] );
    oHoraPosterior.setHours( aHoraPosterior[0], aHoraPosterior[1] );

    if( iMinutosAtual > iMinutosPosterior ) {

      var oMsgErro = {'sPeriodo' : oElementoPosterior.getAttribute('nome_periodo')};
      alert ( _M (MSG_HORARIOAULA + 'hora_final_conflita_periodo', oMsgErro) );

      oElementoAtual.value = '';
      oEvent.stopImmediatePropagation();
      setTimeout(function (event) {
        oElementoAtual.focus();
      }, 1);

      $('processarHorarioAula').setAttribute('disabled','disabled');
      return false;
    }
  }
  oElementoAtual.style.backgroundColor = '';
  $('processarHorarioAula').removeAttribute('disabled');
  return true;
}

/**
 * Reescreve a função selectSingle da DBGrid
 */
var fGridSelectSingle = oGridPeriodosEscola.selectSingle;
oGridPeriodosEscola.selectSingle = function (oCheckbox, sRow, oRow) {


  var lIntegral       = $('turnoEscola').options[$('turnoEscola').selectedIndex].getAttribute('integral');
  var iTurnoReferente = $('turnoEscola').options[$('turnoEscola').selectedIndex].getAttribute('turnoreferente');

  var oInputInicio = $('inicio_'+oCheckbox.value);
  var oInputFim    = $('fim_'+oCheckbox.value);
  var oComboTurnoReferente = $('turno_referente_'+oCheckbox.value);


  oInputInicio.setAttribute('disabled', 'disabled');
  oInputFim.setAttribute('disabled', 'disabled');
  oComboTurnoReferente.setAttribute('disabled', 'disabled');
  oInputInicio.addClassName( 'readonly' );
  oInputFim.addClassName( 'readonly' );

  fGridSelectSingle.apply(this, arguments) ;

  oComboTurnoReferente.value = 'null';

  if ( oCheckbox.checked ) {

    oInputInicio.removeClassName('readonly');
    oInputFim.removeClassName('readonly');
    oInputInicio.removeAttribute('disabled');
    oInputFim.removeAttribute('disabled');

    bloqueiaTurnosReferencia(oCheckbox.value);
  }

  return true;
};


function js_validaPeriodosSelecionados() {

  if ($F('turnoEscola') == '') {

    alert(_M(MSG_HORARIOAULA + "selecione_um_turno"));
    return false;
  }

  if ($$('#gridgridPeriodosEscola input[type="checkbox"]:checked').length == 0) {

    alert(_M(MSG_HORARIOAULA + "selecione_um_periodo"));
    return false;
  }

  var lErro = false;
  var aPeriodosSelecionados = [];

  $$('#gridgridPeriodosEscola input[type="checkbox"]:checked').each ( function(oElemento) {

    if( oElemento.id != 'mtodositensgridPeriodosEscola' ) {

      var iCodigoPeriodo = oElemento.value;

      var oHoraInicio            = $('inicio_'+iCodigoPeriodo);
      var oHoraFim               = $('fim_'+iCodigoPeriodo);
      var oHoraDuracao           = $('duracao_'+iCodigoPeriodo);
      var oHoraVinculo           = $('codigo_vinculo_periodo_'+iCodigoPeriodo);
      var oComboTurnoReferente   = $('turno_referente_'+iCodigoPeriodo);
      var aTurnoReferentePeriodo = [];

      var sNomePeriodo = oHoraInicio.getAttribute('nome_periodo');

      var oMsgErro     = {};
      oMsgErro.periodo = sNomePeriodo;
      if (oHoraInicio.value == '') {

        alert( _M( MSG_HORARIOAULA + "hora_inicio_nao_informada", oMsgErro ) );
        lErro = true;
        throw $break;
      }

      if (oHoraFim.value == '') {

        alert( _M( MSG_HORARIOAULA + "hora_final_nao_informada", oMsgErro) );
        lErro = true;
        throw $break;
      }

      if ( oHoraDuracao.value == '' ) {

        var event = new Event('change');

        if ( !js_calculaIntervalo( oHoraFim, event ) ){

          lErro = true;
          throw $break;
        }
      }

      if ( oComboTurnoReferente.value == 4 ) {

        aTurnoReferentePeriodo.push(1);
        aTurnoReferentePeriodo.push(2);
      } else if (oComboTurnoReferente.value == 5) {

        aTurnoReferentePeriodo.push(2);
        aTurnoReferentePeriodo.push(3);
      } else {
        aTurnoReferentePeriodo.push(oComboTurnoReferente.value);
      }

      var oPeriodoSelecionado = {'iPeriodo': iCodigoPeriodo, 'iCodigoVinculo' : oHoraVinculo.value,
        'sHoraInicio' : oHoraInicio.value, 'sHoraFim' : oHoraFim.value,
        'sDuracao' : oHoraDuracao.value, 'aTurnoReferentePeriodo' : aTurnoReferentePeriodo};
      aPeriodosSelecionados.push(oPeriodoSelecionado);
    }
  });

  if (lErro) {
    return false;
  }
  return aPeriodosSelecionados;

}

$('processarHorarioAula').observe('click', function() {

  /**
   * Valida inconsistencias entre horarios de toda a grade
   * @type object
   */
  var oRetornoValidaGradeHorario = js_validaGradeHorarios();

  if ( oRetornoValidaGradeHorario.iError === true ) {

    alert( oRetornoValidaGradeHorario.sMessage );
    return;
  }

  var aPeriodosValidados = js_validaPeriodosSelecionados();
  var aPeriodosExcluidos = [];

  if (typeof aPeriodosValidados == 'boolean') {
    return false;
  }

  var acao = $('processarHorarioAula').getAttribute('acao');

  if ( acao == 'A' ) {

    var aPeriodosSelecionado = aPeriodosInclusos[$F('turnoEscola')].aPeriodos;
    for ( var iIndice in aPeriodosSelecionado ) {

      if (typeof aPeriodosSelecionado[iIndice] == 'function') {
        continue;
      }

      var lPeriodoEncontrado = false;
      aPeriodosValidados.each(function (oPeriodo) {

        if (oPeriodo.iCodigoVinculo == aPeriodosSelecionado[iIndice].iCodigoVinculo) {

          lPeriodoEncontrado = true;
          throw $break;
        }
      });

      if ( !lPeriodoEncontrado ) {
        aPeriodosExcluidos.push(aPeriodosSelecionado[iIndice].iCodigoVinculo);
      }
    }
  }

  var iTurnoSelecionado              = $F('turnoEscola');
  var oParametros                    = {'exec' : 'salvarPeriodoAula'};
  oParametros.iTurno                 = iTurnoSelecionado;
  oParametros.aPeriodos              = aPeriodosValidados;
  oParametros.aPeriodosExcluidos     = aPeriodosExcluidos;

  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros)
  oRequest.asynchronous = false;
  oRequest.onComplete   = function (oAjax) {

    var oRetorno = eval( "(" + oAjax.responseText + ")");

    alert(oRetorno.sMessage.urlDecode());
    if ( parseInt(oRetorno.iStatus) == 2) {
      return false;
    }
    js_limpaGrade();
    js_atualizaSituacaoComboTurno(iTurnoSelecionado, true);
  };

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
});


function js_limpaGrade() {

  $('processarHorarioAula').style.display  = '';
  $('excluirVinculoPeriodo').style.display = 'none';
  $('processarHorarioAula').setAttribute('acao', 'I');

  $('turnoEscola').value = '';
  $('turnoEscola').removeAttribute('disabled');

  js_renderizaPeriodos ();

  $$('#gridgridPeriodosEscola input[type="checkbox"]').each( function(oElement) {
    oElement.removeAttribute('disabled');
  });
  js_buscaPeriodosVinculados();
}


function js_buscaPeriodosVinculados() {

  var oParametros       = {'exec' : 'getPeriodosVinculados'};

  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = js_retornoPeriodosVinculados;

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
}

/**
 * Função criada para reordenar o array de Periodos Inclusos pois foi alterado o index no rpc para trazer ordenado
 * conforme configurado na secretaria de educação
 * @param  {Array} aPeriodosEscola
 * @return {void}
 */
function js_reIndexaArrayDosPeriodos( aPeriodosEscola ) {

  for (var sIndex in aPeriodosEscola) {

    if (typeof aPeriodosEscola[sIndex] == 'function') {
      continue;
    }

    var iTurno = sIndex.split('#')[1];
    aPeriodosInclusos[iTurno] = aPeriodosEscola[sIndex];
  }
}


function js_retornoPeriodosVinculados(oAjax) {

  var oRetorno = eval('(' + oAjax.responseText + ')');

  oGridHorariosInclusos.clearAll(true);

  js_reIndexaArrayDosPeriodos(oRetorno.aPeriodosEscola);

  var aCodigoTurno = [];
  for (var sHash in oRetorno.aPeriodosEscola ) {

    var iTurno = sHash.split('#')[1];

    var aLinha   = [];
    var sHorario = oRetorno.aPeriodosEscola[sHash].sHoraInicio + ' às ' + oRetorno.aPeriodosEscola[sHash].sHoraFim;

    var oBtnAlterar = new Element('input', {'type':'button', 'id':'alertar'+iTurno, 'value': 'A'});
    var oBtnExcluir = new Element('input', {'type':'button', 'id':'excluir'+iTurno, 'value': 'E'});
    oBtnAlterar.setAttribute('codigo_turno', iTurno);
    oBtnExcluir.setAttribute('codigo_turno', iTurno);
    oBtnAlterar.setAttribute('descricao_turno', oRetorno.aPeriodosEscola[sHash].sTurno.urlDecode());
    oBtnExcluir.setAttribute('descricao_turno', oRetorno.aPeriodosEscola[sHash].sTurno.urlDecode());

    aLinha.push(oRetorno.aPeriodosEscola[sHash].sTurno.urlDecode());
    aLinha.push(sHorario);
    aLinha.push(oBtnAlterar.outerHTML+ ' ' + oBtnExcluir.outerHTML);

    aCodigoTurno.push(iTurno);
    oGridHorariosInclusos.addRow(aLinha);
  }
  oGridHorariosInclusos.renderRows();

  aCodigoTurno.each( function (iTurno){

    $('excluir'+iTurno).observe('click', function () {
      js_atualizaExclusao(iTurno);
    });

    $('alertar'+iTurno).observe('click', function () {
      js_atualizaAlteracao(iTurno);
    });
  });
}


function js_atualizaAlteracao (iTurno) {

  $('turnoEscola').value = iTurno;
  $('turnoEscola').setAttribute('disabled', 'disabled');
  js_renderizaPeriodos ();

  js_atualizaGradePeriodos(iTurno, true);

  $('processarHorarioAula').setAttribute('acao', 'A');
  $('processarHorarioAula').style.display  = '';
  $('excluirVinculoPeriodo').style.display = 'none';
}


function js_atualizaExclusao (iTurno) {

  $('turnoEscola').value = iTurno;
  $('turnoEscola').setAttribute('disabled', 'disabled');
  js_renderizaPeriodos ();

  js_atualizaGradePeriodos(iTurno, false);
  $('processarHorarioAula').style.display  = 'none';
  $('excluirVinculoPeriodo').style.display = '';

  $$('#gridgridPeriodosEscola input[type="checkbox"]').each( function(oElement) {
    oElement.setAttribute('disabled', 'disabled');
  });
}


function js_atualizaGradePeriodos (iTurno, lAlteracao) {

  var aPeriodosSelecionado = aPeriodosInclusos[iTurno].aPeriodos;
  for (var iIndice in aPeriodosSelecionado) {

    if (typeof aPeriodosSelecionado[iIndice] == 'function') {
      continue;
    }

    var iCodigoPeriodo = aPeriodosSelecionado[iIndice].iCodigoPeriodo;

    $('chkgridPeriodosEscola'+iCodigoPeriodo).checked  = true;

    var oInputInicio         = $('inicio_'+iCodigoPeriodo);
    var oInputFim            = $('fim_'+iCodigoPeriodo);
    var oInputDuracao        = $('duracao_'+iCodigoPeriodo);
    var oInputVinculo        = $('codigo_vinculo_periodo_'+iCodigoPeriodo);
    var lIntegral            = $('turnoEscola').options[$('turnoEscola').selectedIndex].getAttribute('integral');
    var iTurnoReferente      = $('turnoEscola').options[$('turnoEscola').selectedIndex].getAttribute('turnoreferente');
    var oComboTurnoReferente = $('turno_referente_'+iCodigoPeriodo);
    var aTurnosReferentePeriodo = aPeriodosSelecionado[iIndice].aTurnosReferentesPeriodo;
    var iTurnoReferentePeriodo  = null;


    oInputInicio.value  = aPeriodosSelecionado[iIndice].sHoraInicio;
    oInputFim.value     = aPeriodosSelecionado[iIndice].sHoraFim;
    oInputDuracao.value = aPeriodosSelecionado[iIndice].sDuracao;
    oInputVinculo.value = aPeriodosSelecionado[iIndice].iCodigoVinculo;

    oInputInicio.setAttribute('disabled', 'disabled');
    oInputFim.setAttribute('disabled', 'disabled');

    oInputInicio.addClassName( 'readonly' );
    oInputFim.addClassName( 'readonly' );

    iTurnoReferentePeriodo = 'null';

    if ( aTurnosReferentePeriodo.join() == '1,2' ) {
      iTurnoReferentePeriodo = 4;
    } else if (aTurnosReferentePeriodo.join() == '2,3') {
      iTurnoReferentePeriodo = 5;
    } else if ( aTurnosReferentePeriodo.join() != "" ){
      iTurnoReferentePeriodo = aTurnosReferentePeriodo.join();
    }

    oComboTurnoReferente.value = iTurnoReferentePeriodo;

    if (lAlteracao) {

      oInputInicio.removeAttribute('disabled', 'disabled');
      oInputFim.removeAttribute('disabled', 'disabled');

      oInputInicio.removeClassName( 'readonly' );
      oInputFim.removeClassName( 'readonly' );

      bloqueiaTurnosReferencia(iCodigoPeriodo);
    }
  }

  return true;
}


$('excluirVinculoPeriodo').observe('click', function() {

  var iTurnoSelecionado    = $F('turnoEscola');
  var aPeriodosVinculados  = [];
  var aPeriodosSelecionado = aPeriodosInclusos[iTurnoSelecionado].aPeriodos;
  for ( var iIndice in aPeriodosSelecionado ) {

    if (typeof aPeriodosSelecionado[iIndice] == 'function') {
      continue;
    }
    aPeriodosVinculados.push(aPeriodosSelecionado[iIndice].iCodigoVinculo);
  }

  var oParametros       = {'exec' : 'removerPeriodoAula'};
  oParametros.aPeriodos = aPeriodosVinculados;
  oParametros.iTurno    = $F('turnoEscola');

  var oRequest          = {'method' : 'post'};
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function (oAjax) {

    var oRetorno = eval( "(" + oAjax.responseText + ")");

    alert(oRetorno.sMessage.urlDecode());
    if ( parseInt(oRetorno.iStatus) == 2) {
      return false;
    }

    js_limpaGrade();
    js_atualizaSituacaoComboTurno(iTurnoSelecionado, false);
  };

  new Ajax.Request(RPC_HORARIOAULA, oRequest);
});


function js_atualizaSituacaoComboTurno(iTurno, lBloqueia) {

  for ( var iIndice in $('turnoEscola').options) {

    if (lBloqueia && $('turnoEscola').options[iIndice].value == iTurno) {

      $('turnoEscola').options[iIndice].setAttribute('disabled', 'disabled');
      break;
    }

    if ( !lBloqueia && $('turnoEscola').options[iIndice].value == iTurno) {

      $('turnoEscola').options[iIndice].removeAttribute('disabled');
      break;
    }
  }
}

function js_validaGradeHorarios() {

  var aElementos = new Array();
  var oElementos = "";

  $$('#gridgridPeriodosEscola input[type="checkbox"]:checked').each ( function(oElemento) {

    if( oElemento.getAttribute('value') ) {

      var iOrdem        = parseInt( oElemento.parentNode.getAttribute('ordem') );
      if ( iOrdem != '' && iOrdem != undefined ) {
        var iCodigoPeriodo  = oElemento.value;
        var sHoraInicio     = $F('inicio_'+iCodigoPeriodo);
        var sHoraFim        = $F('fim_'+iCodigoPeriodo);
        var sNomePeriodo    = $('inicio_'+iCodigoPeriodo).getAttribute('nome_periodo');

        if( sHoraInicio != '' && sHoraFim != '' ) {
          oElementos = {
            "ordem"         : iOrdem,
            "nomePeriodo"   : sNomePeriodo,
            "horaInicio"    : sHoraInicio,
            "horaFim"       : sHoraFim
          }
          aElementos.push( oElementos );
        }
      }
    }
  });

  aElementos   = js_sortByKey( aElementos, 'ordem' );

  var iError   = false;
  var sMessage = "";

  var oHoraInicio         = new Date();
  var oHoraFim            = new Date();
  var oHoraInicioProximo  = new Date();

  aElementos.each( function( oItem, iKey ) {

    var aHoraInicio    = oItem.horaInicio.split(':');
    var aHoraFim       = oItem.horaFim.split(":");
    var iMinutosInicio = new Number( ( aHoraInicio[0] * 60 ) + aHoraInicio[1] );
    var iMinutosFim    = new Number( ( aHoraFim[0] * 60 ) + aHoraFim[1] );

    oHoraInicio.setHours( aHoraInicio[0], aHoraInicio[1] );
    oHoraFim.setHours( aHoraFim[0], aHoraFim[1] );

    if ( iMinutosInicio > iMinutosFim ) {

      iError  = true;
      sMessage   += "Hora inicial maior que hora final no periodo: " + oItem.nomePeriodo;
    }

    if ( typeof aElementos[(iKey+1)] !== 'undefined' ) {

      var sNomePeriodoProximo = aElementos[(iKey+1)].nomePeriodo;
      var aHoraInicioProximo  = aElementos[(iKey+1)].horaInicio.split(':');
      oHoraInicioProximo.setHours( aHoraInicioProximo[0], aHoraInicioProximo[1] );

      if ( oHoraFim.getTime() > oHoraInicioProximo.getTime() ) {

        iError  = true;
        sMessage   += "Hora final no periodo " + oItem.nomePeriodo;
        sMessage   += " maior que hora inicial do periodo " + sNomePeriodoProximo;
      }
    }
  });

  var oReturn = {
    'iError'   : iError,
    'sMessage' : sMessage
  }

  return oReturn;
}

function js_sortByKey(array, key) {
    return array.sort(function(a, b) {
        var x = a[key]; var y = b[key];
        return ((x < y) ? -1 : ((x > y) ? 1 : 0));
    });
}

$('turnoEscola').onchange = function() {
  js_renderizaPeriodos();
};

function bloqueiaTurnosReferencia( iCheckbox ) {

  var lIntegral            = $('turnoEscola').options[$('turnoEscola').selectedIndex].getAttribute('integral');
  var iTurnoReferente      = $('turnoEscola').options[$('turnoEscola').selectedIndex].getAttribute('turnoreferente');
  var oComboTurnoReferente = $('turno_referente_'+iCheckbox);
  var optionsTurnos        = oComboTurnoReferente.getElementsByTagName("option");

    if ( lIntegral == 'true' ) {

      oComboTurnoReferente.removeAttribute('disabled');

      for (var i = 0; i < optionsTurnos.length; i++) {

        if ( iTurnoReferente == '1,2' ) {
          (optionsTurnos[i].value == "3" || optionsTurnos[i].value == "5") ? optionsTurnos[i].disabled = true : optionsTurnos[i].disabled = false ;
        }

        if ( iTurnoReferente == '2,3' ) {
          (optionsTurnos[i].value == "1" || optionsTurnos[i].value == "4") ? optionsTurnos[i].disabled = true : optionsTurnos[i].disabled = false ;
        }
      }
    } else {
      oComboTurnoReferente.value = iTurnoReferente;
    }

}

</script>