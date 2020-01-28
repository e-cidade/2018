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

$sTipoServidor = $oGet->ed20_i_tiposervidor == 1 ? 'Matrícula:' : 'CGM:';

?>


<div class="container">
  <form id='form1' name="form1" method="post" action="">
    <fieldset >
      <legend>Função Exercida</legend>
      <table class="form-container" >

        <tr >
          <td><?=$sTipoServidor?></td>
          <td colspan='3'>
            <?php
              db_input('identificacao', 10, '', true, 'text', 3);
              db_input('z01_nome',      50, '', true, 'text', 3);
              db_input('ed75_i_codigo', 10, $Ied75_i_codigo, true, 'hidden', 3);
              db_input('ed22_i_codigo', 10, $Ied22_i_codigo, true, 'hidden', 3);
              db_input('ed75_i_escola', 10, $Ied75_i_escola, true, 'hidden', 3);
            ?>
          </td>
        </tr>

        <tr>
          <td id='ctnAncoraFuncao'><?php db_ancora("Função/Atividade:", "js_pesquisaAtividade(true);", 1); ?></td>
          <td colspan='3' >
            <?php
              db_input('ed22_i_atividade', 10, $Ied22_i_atividade, true, 'text',   1, " onchange='js_pesquisaAtividade(false);'");
              db_input('ed01_c_descr',     50, $Ied01_c_descr,     true, 'text',   3,'');
              db_input('ed01_c_exigeato',  10, $Ied01_c_exigeato,  true, 'hidden', 3);
            ?>
          </td>
        </tr>
        <tr id="atolegal" style="display:none;" >
          <td id="ctnAtoLegal"><?php db_ancora(@$Led22_i_atolegal,"js_pesquisaAtoLegal(true);", 1);?></td>
          <td colspan='3' >
            <?php
              db_input('ed22_i_atolegal',   10, $Ied22_i_atolegal,   true, 'text', 1, " onchange='js_pesquisaAtoLegal(false);'");
              db_input('ed05_c_finalidade', 50, $Ied05_c_finalidade, true, 'text', 3, '');
            ?>
          </td>
        </tr>

        <tr>
          <td><?=@$Led129_turno?></td>
          <td >
            <select id='turno' style='color:#000;' onchange="js_validaExistenciaAgenda();">
              <?php
                foreach ($aTurnos as $iTurno => $oDados) {

                  if ( !$oDados->lAtivo ) {
                    continue;
                  }
                  echo "<option value= {$iTurno} hora_inicio='{$oDados->sHoraInicio}' hora_fim='{$oDados->sHoraFim}'> {$oDados->sDescricao} </option>";
                }
              ?>
            </select>
          </td>
          <td>
            Tipo de Hora:
          </td>
          <td title='Selecione uma atividade antes:'>
            <select id='tipo_hora' style='color:#000;' onchange="js_validaExistenciaAgenda();">
              <option value="">Selecione</option>
            </select>
          </td>
        </tr>
      </table>
      <fieldset class'separator'>
        <legend>Dia Semana</legend>
        <div id='ctnGridDiaSemana'> </div>
      </fieldset>
    </fieldset>

    <input type="button" value="Salvar"   name='salvar'   id='salvarAtividade'>
    <input type="button" value="Excluir"  name='excluir'  id='excluirAtividade' disabled="disabled">
    <input type="button" value="Cancelar" name='cancelar' id='cancelarAcao' >
  </form>

</div>

<div class="subcontainer">

  <fieldset style = 'width : 900px;'>
    <legend>Atividades do profissional </legend>
    <div id='gridAtividadesProfissional' ></div>
  </fieldset>
</div>

<script type="text/javascript">

var oBkpAncoraFuncao = $('ctnAncoraFuncao').childNodes[1];
var oBkpAtoLegal     = $('ctnAtoLegal').childNodes[1];

var sRPC = "edu4_rechumanoatividade.RPC.php";
const MSG_RECHUMANOATIVIDADE = "educacao.escola.edu_rechumanoatividade.";


/** ****************************************************************************************
 *  ******************************** Grid Atividades ***************************************
 ***************************************************************************************** */
var oGridAtividades           = new DBGrid('gridAtividadeProfissional');
oGridAtividades.nameInstance = 'oGridAtividades';

var aHeaders   = ['Função/Atividade', 'Turno', 'Tipo de Hora', 'Ação', 'codigo_atividade', 'codigos_agendas'];
var aCellWidth = ['40%', '15%', '30%', '15%'];
var aCellAlign = ['left', 'center', 'left', 'center'];

oGridAtividades.setCellWidth(aCellWidth);
oGridAtividades.setCellAlign(aCellAlign);
oGridAtividades.setHeader(aHeaders);
oGridAtividades.aHeaders[4].lDisplayed=false;
oGridAtividades.aHeaders[5].lDisplayed=false;
oGridAtividades.setHeight(150);
oGridAtividades.show($('gridAtividadesProfissional'));


/** ****************************************************************************************
 *  ******************************** Grid DiaSemana ****************************************
 ***************************************************************************************** */
var oGridDiaSemana          = new DBGrid('gridDiaSemana');
oGridDiaSemana.nameInstance = 'oGridDiaSemana';

var aHeaders   = ['Dia Semana', 'Hora Início', 'Hora Fim', 'dia_semana', 'input_dia_semana', 'codigo agenda'];
var aCellWidth = ['40%', '30%', '30%'];
var aCellAlign = ['left', 'center', 'center'];

oGridDiaSemana.nameInstance = 'oGridDiaSemana';
oGridDiaSemana.setCheckbox(3);
oGridDiaSemana.setCellWidth(aCellWidth);
oGridDiaSemana.setCellAlign(aCellAlign);
oGridDiaSemana.setHeader(aHeaders);
oGridDiaSemana.aHeaders[4].lDisplayed=false;
oGridDiaSemana.aHeaders[5].lDisplayed=false;
oGridDiaSemana.aHeaders[6].lDisplayed=false;
oGridDiaSemana.setHeight(150);
oGridDiaSemana.show($('ctnGridDiaSemana'));

oGridDiaSemana.clearAll(true);

var aDiasSemana = [
                    {codigo : 1 , descricao: 'DOMINGO'},
                    {codigo : 2 , descricao: 'SEGUNDA'},
                    {codigo : 3 , descricao: 'TERÇA'  },
                    {codigo : 4 , descricao: 'QUARTA' },
                    {codigo : 5 , descricao: 'QUINTA' },
                    {codigo : 6 , descricao: 'SEXTA'  },
                    {codigo : 7 , descricao: 'SÁBADO' }
                  ];

function js_montaGradeDiaSemana () {

  for ( var i in aDiasSemana) {

    if ( typeof aDiasSemana[i] == 'function' ) {
      continue;
    }

    var iDia   = aDiasSemana[i].codigo;
    var aLinha = [];
    aLinha.push(aDiasSemana[i].descricao);
    aLinha.push('');
    aLinha.push('');
    aLinha.push(iDia);
    aLinha.push("<input type='text' id='dia_semana_" + iDia + "' value='' />");
    aLinha.push("<input type='text' id='codigo_agenda_" + iDia + "' value='' />");

    oGridDiaSemana.addRow(aLinha);
  }

  oGridDiaSemana.renderRows();

  for ( var iIndice in aDiasSemana) {

    if ( typeof aDiasSemana[iIndice] == 'function' ) {
      continue;
    }

    var oHoraInicio = addImputHora( aDiasSemana[iIndice], 'inicio');
    var oHoraFim    = addImputHora( aDiasSemana[iIndice], 'fim');
    oHoraInicio.show( $(oGridDiaSemana.aRows[iIndice].aCells[2].sId) );
    oHoraFim.show( $(oGridDiaSemana.aRows[iIndice].aCells[3].sId) );

    var sIdCtnCheckBox = oGridDiaSemana.aRows[iIndice].aCells[0].sId;
    $(sIdCtnCheckBox).setAttribute('linha', iIndice);
  }
}


/**
 * Carga inicial dos dados
 * @return {void}
 */
(function(){

  /**
   * Todas as atividades que o profissional possui
   * @type {Array}
   */
  aAtividadesProfissional = [];
  js_montaGradeDiaSemana();
  js_buscaAtividadesProfissional();
})();


function addImputHora (oDiaSemana, sTipo) {

  var sId = 'hora_'+sTipo+'_'+oDiaSemana.codigo;

  var oInput = new DBInputHora( new Element('input', {'type':'text', 'id' : sId}) );
  oInput.getElement().addClassName('field-size2');
  oInput.getElement().addClassName('readonly');
  oInput.getElement().setAttribute('disabled', 'disabled');
  oInput.getElement().setAttribute('tipo', sTipo);
  oInput.getElement().setAttribute('dia_semana', oDiaSemana.codigo);
  oInput.getElement().onchange = function (event) {
    js_validaHora(oInput.getElement(), event);
  };

  return oInput;
}

/**
 * Valida o horário informado
 * @param  HTMLInputElement oElement Elemento que disparou o change
 * @param  event            oEvent   Evento disparado
 * @return {void}
 */
function js_validaHora(oElement, oEvent) {

  var sIdInicio = 'hora_inicio_'+oElement.getAttribute('dia_semana');
  var sIdFim    = 'hora_fim_'+oElement.getAttribute('dia_semana');

  if (oElement.getAttribute('tipo') == 'fim' && $F(sIdInicio) == '') {

    $(sIdInicio).focus();
    alert( _M( MSG_RECHUMANOATIVIDADE + 'informe_hora_inicio'));
    return false;
  }

  if ($F(sIdInicio) == '' && $F(sIdFim) == '') {

    alert( _M( MSG_RECHUMANOATIVIDADE + 'informe_hora_inicio_fim'));
    return false;
  }


  /**
   * Valida conflito entre a hora incial digitada com a hora inicial do período
   */
  var aHoraInicio = [];
  if ($F(sIdInicio) != '') {

    aHoraInicio                      = $(sIdInicio).value.split(':');
    var aHoraInicioTurno             = $('turno').options[$('turno').selectedIndex].getAttribute('hora_inicio').split(':');
    var iMinutosInicioTurnoEscola    = new Number( ( aHoraInicioTurno[0] * 60 ) + aHoraInicioTurno[1] ) + 0;
    var iMinutosInicioTurnoInformado = new Number( ( aHoraInicio[0] * 60 ) + aHoraInicio[1] ) + 0;

    if ( iMinutosInicioTurnoEscola > iMinutosInicioTurnoInformado ) {

      alert(_M( MSG_RECHUMANOATIVIDADE +'hora_inicial_menor_hora_inicio_turno'));
      $(sIdInicio).value = '';
      js_retornaFoco(sIdInicio, oEvent);
      return false;
    }
  }

  /**
   * Valida conflito entre a hora final digitada com a hora final do período
   */
  var aHoraFim = [];
  if ( !empty($F(sIdFim)) ) {

    aHoraFim          = $(sIdFim).value.split(':');
    var aHoraFimTurno = $('turno').options[$('turno').selectedIndex].getAttribute('hora_fim').split(':');
    var iMinutosFimTurnoEscola    = new Number( ( aHoraFimTurno[0] * 60 ) + aHoraFimTurno[1] ) + 0;
    var iMinutosFimTurnoInformado = new Number( ( aHoraFim[0] * 60 ) + aHoraFim[1] ) + 0;

    if ( iMinutosFimTurnoInformado > iMinutosFimTurnoEscola ) {

      $(sIdFim).value = '';
      alert(_M( MSG_RECHUMANOATIVIDADE +'hora_final_maior_hora_final_turno'));
      js_retornaFoco(sIdFim, oEvent);
      return false;
    }
  }

  /**
   * Valida conflito entre a hora incial e final digitadas
   */
  if ($F(sIdInicio) != '' && $F(sIdFim) != '') {

    if ( iMinutosInicioTurnoInformado >= iMinutosFimTurnoInformado ) {

      $(sIdFim).value = "";
      alert(_M( MSG_RECHUMANOATIVIDADE +'hora_final_menor_igual'));
      js_retornaFoco(sIdFim, oEvent);
      return false;
    }
  }
}

function js_retornaFoco (sId, oEvent) {

  oEvent.stopImmediatePropagation();
  setTimeout(function (event) {
    $(sId).focus();
  }, 1);
}

/**
 * Reescreve a função de de seleção da grid
 */
var fGridSelectSingle = oGridDiaSemana.selectSingle;
oGridDiaSemana.selectSingle = function (oCheckbox, sRow, oRow) {

  var sIdHoraInicio = 'hora_inicio_' + oCheckbox.value;
  var sIdHoraFim    = 'hora_fim_' + oCheckbox.value;

  $(sIdHoraInicio).setAttribute('disabled', 'disabled');
  $(sIdHoraInicio).addClassName('readonly');
  $(sIdHoraFim).setAttribute('disabled', 'disabled');
  $(sIdHoraFim).addClassName('readonly');

  fGridSelectSingle.apply(this, arguments) ;

  if (oCheckbox.checked ) {

    $(sIdHoraInicio).removeAttribute('disabled');
    $(sIdHoraInicio).removeClassName('readonly');
    $(sIdHoraFim).removeAttribute('disabled');
    $(sIdHoraFim).removeClassName('readonly');
  }

  return true;
};

/**
 * Limpa todas informações da grade dos dias da semana e desmarca os dias selecionados
 */
function js_limpaDadosGridDiaSemana() {

  $$('.checkboxgridDiaSemana').each( function(oElement) {

    oElement.checked  = false;
    var sIdHoraInicio = 'hora_inicio_' + oElement.value;
    var sIdHoraFim    = 'hora_fim_' + oElement.value;

    $(sIdHoraInicio).value = '';
    $(sIdHoraInicio).setAttribute('disabled', 'disabled');
    $(sIdHoraInicio).addClassName('readonly');
    $(sIdHoraFim).value = '';
    $(sIdHoraFim).setAttribute('disabled', 'disabled');
    $(sIdHoraFim).addClassName('readonly');

    $('codigo_agenda_'+oElement.value).value = '';

    var iLinha = oElement.parentNode.getAttribute('linha');
    $('gridDiaSemanarowgridDiaSemana'+iLinha).removeClassName('marcado');
    $('gridDiaSemanarowgridDiaSemana'+iLinha).addClassName('normal');

  });
}


/**
 * ************************************************************
 *  ******************  Funções de pesquisa *******************
 * ************************************************************
 */
function js_pesquisaAtividade ( lMostra ) {

  $("atolegal").style.display  = "none";
  $('ed22_i_atolegal').value   = "";
  $('ed05_c_finalidade').value = "";

  var sUrl = 'func_atividaderh.php';
  if( lMostra ) {

    sUrl += '?funcao_js=parent.js_mostraAtividade|ed01_i_codigo|ed01_c_descr|ed01_c_exigeato';
    js_OpenJanelaIframe('', 'db_iframe_atividaderh', sUrl, 'Pesquisa de Atividades', true);
  } else if ( !empty($F('ed22_i_atividade')) ) {

    sUrl += '?pesquisa_chave='+$F('ed22_i_atividade');
    sUrl += '&funcao_js=parent.js_mostraAtividade';
    js_OpenJanelaIframe('', 'db_iframe_atividaderh', sUrl, 'Pesquisa de Atividades', false);
  } else {

    $('ed01_c_descr').value  = '';
    $('ed22_i_codigo').value = '';
    js_limpaDadosGridDiaSemana();
  }
}

function js_mostraAtividade ( ) {

  $('ed22_i_codigo').value = '';
  js_limpaDadosGridDiaSemana();

  // quando digitada arguments[2] é um boolean
  if ( typeof arguments[2] == 'boolean') {

    $('ed01_c_descr').value    = arguments[0];
    $('ed01_c_exigeato').value = arguments[1];

    if ( arguments[1] == 'S' ) {

      $("atolegal").style.display  = "";
      js_pesquisaAtoLegal(true);
    }

    if ( arguments[2] ) {
      $('ed22_i_atividade').value = '';
      return;
    }
    js_buscaTipoHora();
  } else {
    // pesquisada
    $('ed22_i_atividade').value    = arguments[0];
    $('ed01_c_descr').value        = arguments[1];
        $('ed01_c_exigeato').value = arguments[2];
    if ( arguments[2] == 'S' ) {

      $("atolegal").style.display  = "";
      js_pesquisaAtoLegal(true);
    }
    js_buscaTipoHora();
    db_iframe_atividaderh.hide();
  }
}

function js_pesquisaAtoLegal( lMostra ) {

  var sUrl = 'func_atolegal.php';
  if ( lMostra ) {

    sUrl += '?funcao_js=parent.js_mostraAtoLegal|ed05_i_codigo|ed05_c_finalidade';
    js_OpenJanelaIframe('', 'db_iframe_atolegal', sUrl, 'Pesquisa de Ato Legal', true);
  } else if ( !empty($F('ed22_i_atolegal')) ) {

    sUrl += '?pesquisa_chave=' + $F('ed22_i_atolegal') + '&funcao_js=parent.js_mostraAtoLegal';
    js_OpenJanelaIframe('', 'db_iframe_atolegal', sUrl, 'Pesquisa de Ato Legal', false);
  } else {

    $('ed22_i_atolegal').value   = '';
    $('ed05_c_finalidade').value = '';
  }

}

function js_mostraAtoLegal() {

  if ( typeof arguments[1] == 'boolean') {

    $('ed05_c_finalidade').value = arguments[0] ;
    if ( arguments[1] ) {
      $('ed22_i_atolegal').value = '';
    }
  } else {

    $('ed22_i_atolegal').value   = arguments[0];
    $('ed05_c_finalidade').value = arguments[1].trim();
    db_iframe_atolegal.hide()
  }
}

/**
 * ************************************************************
 *  **************** Fim Funções de pesquisa ******************
 * ************************************************************
 */

/**
 * Busca os tipos de hora configurado para atividade selecionada
 */
function js_buscaTipoHora() {

  var oParamentros = {exec: 'buscaTipoHoraPorAtividade', iEscola : $F('ed75_i_escola'), iAtividade : $F('ed22_i_atividade')};

  var oAjaxRequest = new AjaxRequest(sRPC, oParamentros, js_retornoTipoHora);
  oAjaxRequest.asynchronous(false);
  oAjaxRequest.setMessage( _M(MSG_RECHUMANOATIVIDADE + "buscando_tipo_hora") );
  oAjaxRequest.execute();
}

function js_retornoTipoHora( oRetorno, lErro ) {

  if ( lErro ) {

    alert(oRetorno.sMessage.urlDecode());
    return;
  }

  $('tipo_hora').options.length = 0;
  $('tipo_hora').add(new Option( 'Selecione', '' ));
  oRetorno.aTiposHora.each( function (oTipoHora) {
    $('tipo_hora').add(new Option( oTipoHora.sDescricao.urlDecode(), oTipoHora.iCodigo ));
  });
}


function js_buscaAtividadesProfissional() {

  var oParamentros = {exec : 'buscaAtividadesProfissional', iVinculoEscola : $F('ed75_i_codigo')}
  var oAjaxRequest = new AjaxRequest(sRPC, oParamentros, js_retornoAtividadesProfissional);
  oAjaxRequest.setMessage( _M(MSG_RECHUMANOATIVIDADE + "buscando_tipo_hora") );
  oAjaxRequest.execute();
}


function js_retornoAtividadesProfissional ( oRetorno, lErro) {

  if ( lErro ) {

    alert(oRetorno.sMessage.urlDecode());
    return;
  }
  aAtividadesProfissional = oRetorno.aAtividades;

  oGridAtividades.clearAll(true);
  oRetorno.aAtividades.each( function (oAtividade) {

    var oBtnAlterar = new Element('input', {type:'button', value:'A', id:'alterar_'+oAtividade.iCodigo});
    var oBtnExcluir = new Element('input', {type:'button', value:'E', id:'excluir_'+oAtividade.iCodigo});
    oBtnAlterar.setAttribute('onclick', 'js_AlterarAtividadeSemAgenda('+oAtividade.iCodigo+')');
    oBtnExcluir.setAttribute('onclick', 'js_ExcluirAtividadeSemAgenda('+oAtividade.iCodigo+')');

    var aLinha = [];
    aLinha.push(oAtividade.sDescricao.urlDecode());
    aLinha.push('');
    aLinha.push('');
    aLinha.push(oBtnAlterar.outerHTML +' '+oBtnExcluir.outerHTML);
    aLinha.push(oAtividade.iCodigo);
    aLinha.push('');

    /**
     * Se a atividade tem agenda, monta um resumo com os dados do turno
     */
    if ( !empty(oAtividade.aResumoTurno) ) {

      for (var iIndice in oAtividade.aResumoTurno ) {

        var oResumo     = oAtividade.aResumoTurno[iIndice];
        var oBtnAlterar = new Element('input', {type:'button', value:'A', id:'alterar_'+oAtividade.iCodigo});
        var oBtnExcluir = new Element('input', {type:'button', value:'E', id:'excluir_'+oAtividade.iCodigo});
        oBtnAlterar.setAttribute('onclick', 'js_AlterarAtividadeComAgenda('+oAtividade.iCodigo +', ' + oResumo.iTurno + ', ' + oResumo.iTipoHoraTrabalho +')');
        oBtnExcluir.setAttribute('onclick', 'js_ExcluirAtividadeComAgenda('+oAtividade.iCodigo +', ' + oResumo.iTurno + ', ' + oResumo.iTipoHoraTrabalho +')');

        var aCloneLinha = aLinha;
        aCloneLinha[1]  = oResumo.sTurno.urlDecode();
        aCloneLinha[2]  = oResumo.sTipoHoraTrabalho.urlDecode();
        aCloneLinha[3]  = oBtnAlterar.outerHTML + ' ' + oBtnExcluir.outerHTML;

        oGridAtividades.addRow(aCloneLinha);
      }

    } else {
      oGridAtividades.addRow(aLinha);
    }

  });
  oGridAtividades.renderRows();

}

function js_CarregaDadosSemAgenda(oAtividade) {

  js_limpaDadosGridDiaSemana();
  $('ed22_i_codigo').value    = oAtividade.iCodigo;
  $('ed01_c_descr').value     = oAtividade.sDescricao.urlDecode();
  $('ed22_i_atividade').value = oAtividade.iCodigoAtividade;
  $('atolegal').style.display = 'none';

  if ( !empty(oAtividade.iCodigoAto) ) {

    $('atolegal').style.display  = '';
    $('ed22_i_atolegal').value   = oAtividade.iCodigoAto;
    $('ed05_c_finalidade').value = oAtividade.sDescricaoAto.urlDecode();
  }

  $('tipo_hora').value = '';
  $('turno').value     = 1;
}

/**
 * Carraga os dados de uma atividade sem agenda na grade para ALTERAÇÃO.
 * @param  {integer} iCodigoAgenda
 * @return {void}
 */
function js_AlterarAtividadeSemAgenda(iCodigoAgenda) {

  setFormReadOnly($('form1'), false);
  $('ctnAncoraFuncao').innerHTML = '';
  $('ctnAtoLegal').innerHTML     = '';

  $('ctnAncoraFuncao').appendChild(oBkpAncoraFuncao);
  $('ctnAtoLegal').appendChild(oBkpAtoLegal);
  $('identificacao').addClassName('readonly');
  $('z01_nome').addClassName('readonly');
  $('ed01_c_descr').addClassName('readonly');

  aAtividadesProfissional.each( function (oAtividade) {

    if ( iCodigoAgenda == oAtividade.iCodigo ) {

      js_CarregaDadosSemAgenda(oAtividade);
      throw $break;
    }
  });

  $('excluirAtividade').setAttribute('disabled', 'disabled');
  js_buscaTipoHora();
}

/**
 * Carraga os dados de uma atividade sem agenda na grade para EXCLUSÃO.
 * @param  {integer} iCodigoAgenda
 * @return {void}
 */
function js_ExcluirAtividadeSemAgenda(iCodigoAgenda) {

  aAtividadesProfissional.each( function (oAtividade) {

    if ( iCodigoAgenda == oAtividade.iCodigo ) {

      js_CarregaDadosSemAgenda(oAtividade);
      throw $break;
    }
  });

  setFormReadOnly($('form1'), true);
  $('excluirAtividade').removeAttribute('disabled');
  $('cancelarAcao').removeAttribute('disabled');
}

/**
 * Preenche os dias e horarios na grid dos dias da semana
 * @param  {Object} oAgenda dados da agenda para atividade selecionada
 * @return {void}
 */
function js_carregaDadosAgendaAtividade(oAgenda) {

  var iDia = oAgenda.iDiaSemana;
  var oChkElemento     = $('chkgridDiaSemana'+iDia);
  oChkElemento.checked = true;

  var oInputHoraInicio           = $('hora_inicio_'+iDia);
  var oInputHoraFim              = $('hora_fim_'+iDia);
  oInputHoraInicio.value         = oAgenda.sHoraInicio;
  oInputHoraFim.value            = oAgenda.sHoraFim;
  $('dia_semana_'+iDia).value    = iDia;
  $('codigo_agenda_'+iDia).value = oAgenda.iCodigo;

  oInputHoraInicio.removeAttribute('disabled');
  oInputHoraInicio.removeClassName('readonly');
  oInputHoraFim.removeAttribute('disabled');
  oInputHoraFim.removeClassName('readonly');

  var iLinha = oChkElemento.parentNode.getAttribute('linha');
  $('gridDiaSemanarowgridDiaSemana'+iLinha).removeClassName('normal');
  $('gridDiaSemanarowgridDiaSemana'+iLinha).addClassName('marcado');
}

function js_resetForm() {

  setFormReadOnly($('form1'), false);
  $('ctnAncoraFuncao').innerHTML = '';
  $('ctnAtoLegal').innerHTML     = '';
  $('ed01_c_descr').value        = '';
  $('ed22_i_atividade').value    = '';
  $('ed01_c_exigeato').value     = '';
  $('ed05_c_finalidade').value   = '';
  $('ed22_i_atolegal').value     = '';
  $('tipo_hora').value           = '';
  $('ed22_i_codigo').value       = '';

  $('atolegal').style.display = 'none';
  $('ctnAncoraFuncao').appendChild(oBkpAncoraFuncao);
  $('ctnAtoLegal').appendChild(oBkpAtoLegal);
  $('identificacao').addClassName('readonly');
  $('z01_nome').addClassName('readonly');
  $('ed01_c_descr').addClassName('readonly');
  $('excluirAtividade').setAttribute('disabled', 'disabled');
}

function js_AlterarAtividadeComAgenda(iCodigoAgenda, iTurno, iTipoHora) {

  js_resetForm();

  aAtividadesProfissional.each( function (oAtividade) {

    if ( iCodigoAgenda == oAtividade.iCodigo ) {

      js_CarregaDadosSemAgenda(oAtividade);

      oAtividade.aAgendas.each( function(oAgenda) {

        if ( oAgenda.iTurno == iTurno & oAgenda.iTipoHoraTrabalho == iTipoHora ) {
          js_carregaDadosAgendaAtividade(oAgenda);
        }
      });
      throw $break;
    }
  });

  js_buscaTipoHora();

  $('turno').value     = iTurno;
  $('tipo_hora').value = iTipoHora;

}

function js_ExcluirAtividadeComAgenda(iCodigoAgenda, iTurno, iTipoHora) {

  aAtividadesProfissional.each( function (oAtividade) {

    if ( iCodigoAgenda == oAtividade.iCodigo ) {

      js_CarregaDadosSemAgenda(oAtividade);

      oAtividade.aAgendas.each( function(oAgenda) {

        if ( oAgenda.iTurno == iTurno & oAgenda.iTipoHoraTrabalho == iTipoHora ) {
          js_carregaDadosAgendaAtividade(oAgenda);
        }

      });
      throw $break;
    }
  });

  js_buscaTipoHora();

  $('turno').value     = iTurno;
  $('tipo_hora').value = iTipoHora;

  setFormReadOnly($('form1'), true);
  $('excluirAtividade').removeAttribute('disabled');
  $('cancelarAcao').removeAttribute('disabled');
}


$('excluirAtividade').observe('click', function () {

  var oParamentros = {exec: 'excluirAtividade', iCodigo: $F('ed22_i_codigo'), iVinculoEscola: $F('ed75_i_codigo')};
  oParamentros.aAgendas = js_getDiasSalvar();

  var oAjaxRequest = new AjaxRequest(sRPC, oParamentros, js_retornoExcluirAtividade);
  oAjaxRequest.asynchronous(false);
  oAjaxRequest.setMessage( _M(MSG_RECHUMANOATIVIDADE + "excluindo_agenda") );
  oAjaxRequest.execute();

});

function js_retornoExcluirAtividade (oRetorno, lErro) {

  alert(oRetorno.sMessage.urlDecode());
  if ( lErro ) {
    return;
  }
  js_resetForm();
  js_limpaDadosGridDiaSemana();
  js_buscaAtividadesProfissional();
  js_reloadAbas();

}

/**
 * Valida os dados antes de enviar para salvar
 * @return {boolean}
 */
function js_validaDadosSalvar() {

  if ( empty($F('ed22_i_atividade')) ) {

    alert(_M(MSG_RECHUMANOATIVIDADE + "selecione_uma_atividade"));
    return false;
  }

  if ( empty($F('turno')) ) {

    alert(_M(MSG_RECHUMANOATIVIDADE + "selecione_turno"));
    return false;
  }
  if ( $F('tipo_hora') == '' ) {

    alert(_M(MSG_RECHUMANOATIVIDADE + "selecione_tipo_hora"));
    return false;
  }

  if ( $F('ed01_c_exigeato') == 'S' && empty($F('ed22_i_atolegal')) ) {

    alert(_M(MSG_RECHUMANOATIVIDADE + "selecione_ato_legal"));
    return false;
  }

  if ($$('#gridgridDiaSemana input[type="checkbox"]:checked').length == 0) {

    alert(_M(MSG_RECHUMANOATIVIDADE + "selecione_um_dia"));
    return false;
  }

  var lErro = false;

  $$('#gridgridDiaSemana input[type="checkbox"]:checked').each(function( oElemento ) {

    if( oElemento.getAttribute( 'value' ) ) {

      var iDia = oElemento.value;
      if ( empty($F('hora_inicio_'+iDia)) || empty($F('hora_fim_'+iDia)) ) {

        alert( _M( MSG_RECHUMANOATIVIDADE + "dia_selecionado_sem_hora" ) );
        lErro = true;
        throw $break;
      }
    }
  });

  if (lErro) {
    return false;
  }
  return true;
}

function js_getDiasSalvar() {

  var aDiasSelecionados = [];
  $$('#gridgridDiaSemana input[type="checkbox"]:checked').each(function( oElemento ) {

    if( oElemento.getAttribute( 'value' ) ) {

      var oDados = {};
      var iDia   = oElemento.value;

      oDados.sHoraInicio   = $('hora_inicio_'+iDia).value
      oDados.sHoraFim      = $('hora_fim_'+iDia).value
      oDados.iDiaSemana    = iDia
      oDados.iCodigoAgenda = $('codigo_agenda_'+iDia).value
      aDiasSelecionados.push(oDados);
    }
  });

  return aDiasSelecionados;
}

function js_getDiasExcluir() {

  var aDiasExcluir = [];
  $$('#gridgridDiaSemana input[type="checkbox"]').each(function( oElemento ) {

    var iDia = oElemento.value;
    if (oElemento.id != 'mtodositensgridDiaSemana' && !oElemento.checked && !empty($F('codigo_agenda_'+iDia))) {

      var oDados = {};

      oDados.sHoraInicio   = $('hora_inicio_'+iDia).value;
      oDados.sHoraFim      = $('hora_fim_'+iDia).value;
      oDados.iDiaSemana    = iDia;
      oDados.iCodigoAgenda = $('codigo_agenda_'+iDia).value;
      aDiasExcluir.push(oDados);

    }
  });
  return aDiasExcluir;
}

$('salvarAtividade').observe('click', function () {

  if( !js_validaDadosSalvar() ) {
    return;
  }

  var oParamentros = {exec: 'salvarAtividade', iCodigo: $F('ed22_i_codigo'), iVinculoEscola: $F('ed75_i_codigo')};
  oParamentros.iAtividade     = $F('ed22_i_atividade');
  oParamentros.iTurno         = $F('turno');
  oParamentros.iTipoHora      = $F('tipo_hora');
  oParamentros.iAtoLegal      = $F('ed22_i_atolegal');
  oParamentros.aSalvarAgenda  = js_getDiasSalvar();
  oParamentros.aExcluirAgenda = js_getDiasExcluir();

  var oAjaxRequest = new AjaxRequest(sRPC, oParamentros, js_retornoSalvar);
  oAjaxRequest.asynchronous(false);
  oAjaxRequest.setMessage( _M(MSG_RECHUMANOATIVIDADE + "salvar_atividade") );
  oAjaxRequest.execute();

});

function js_retornoSalvar(oRetorno, lErro) {

  alert(oRetorno.sMessage.urlDecode());
  if ( lErro ) {
    return;
  }

  js_resetForm();
  js_limpaDadosGridDiaSemana();
  js_buscaAtividadesProfissional();
  js_reloadAbas();

}

/**
 * @todo implementar
 */
function js_validaExistenciaAgenda() {

  js_limpaDadosGridDiaSemana();

  if ( !empty($F('turno')) && !empty($F('tipo_hora')) && !empty($F('ed22_i_atividade')) ) {

    aAtividadesProfissional.each( function (oAtividade) {

      if ( $F('ed22_i_atividade') == oAtividade.iCodigoAtividade ) {

        $('ed22_i_codigo').value = oAtividade.iCodigo;
        oAtividade.aAgendas.each( function(oAgenda) {

          if ( oAgenda.iTurno == $F('turno') && oAgenda.iTipoHoraTrabalho == $F('tipo_hora') ) {
            js_carregaDadosAgendaAtividade(oAgenda);
          }

        });
        throw $break;
      }
    });

  }
}

$('cancelarAcao').observe('click', function () {

  js_resetForm();
  js_limpaDadosGridDiaSemana();

});

function js_reloadAbas() {

  top.corpo.iframe_a5.location.href = top.corpo.iframe_a5.location.href;
  top.corpo.iframe_a6.location.href = top.corpo.iframe_a6.location.href;
}

</script>