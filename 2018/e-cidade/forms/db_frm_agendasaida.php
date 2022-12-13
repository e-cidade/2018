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
$oRotulo = new rotulocampo;
$oRotulo->label('tf25_i_destino');
$oRotulo->label('tf02_i_lotacao');
$oRotulo->label('tf10_i_prestadora');
$oRotulo->label('z01_nome');
$oRotulo->label('tf03_c_descr');
$oRotulo->label('tf10_i_centralagend');
$oRotulo->label('tf17_c_localsaida');
$oRotulo->label('tf18_i_veiculo');
$oRotulo->label('tf18_i_motorista');
$oRotulo->label('tf38_valorunitario');
$oRotulo->label('tf38_valortotal');
$oRotulo->label('tf17_tiposaida');
?>

<form name="form1">
  <div class='container'>
    <fieldset>
      <legend>Agendamento de Saída</legend>
      <table class="form-container">
        <tr style="display: none;">
          <td class="field-size2 bold" nowrap="nowrap">
            <label for="iPedidoTFD">Pedido:</label>
          </td>
          <td nowrap="nowrap">
            <?php
            db_input('iCodigoAgenda', 10, "", true, 'hidden', 3); //tf17_i_codigo código da tfd_agendasaida
            db_input('iPedidoTFD',    10, "", true, 'text',   3);
            ?>
          </td>
        </tr>
        <tr>
          <td class="field-size2 bold" nowrap="nowrap" style="padding-left: 8px;">
            <label for="iCgsPaciente">Paciente:</label>
          </td>
          <td nowrap="nowrap">
            <?php
            db_input('iCgsPaciente',  10, "", true, 'text', 3);
            db_input('sNomePaciente', 56, "", true, 'text', 3);
            ?>
          </td>
        </tr>
      </table>

      <fieldset class='separator'>
        <legend>Destino</legend>
        <table class="form-container">
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              <label for="iCodigoPrestadora">
                <?php
                db_ancora( $Ltf10_i_prestadora, "js_pesquisaPrestadora(true);",  1 );
                ?>
              </label>
            </td>
            <td nowrap="nowrap">
              <?php
              $sScript = "onchange='js_pesquisaPrestadora(false);'";
              db_input( 'iCodigoPrestadora',         10, $Itf10_i_prestadora, true, 'text',   1, $sScript );
              db_input( 'sNomePrestadora',           56, '',                  true, 'text',   3, '' );
              db_input( 'iCodigoCentral',            10, $Itf10_i_prestadora, true, 'hidden', 3 );
              db_input( 'iVinculoCentralPrestadora', 10, "",                  true, 'hidden', 3 );
              ?>
            </td>
          </tr>
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              <label for="sDestinoPrestadora">Destino:</label>
            </td>
            <td nowrap="nowrap">
              <?php
              db_input('sDestinoPrestadora', 70, $Itf03_c_descr, true, 'text',   3 );
              db_input('iDestinoPrestadora', 10, '',             true, 'hidden', 3 );
              ?>
            </td>
          </tr>
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              <label for="dtAgendamentoPrestadora">Agendamento:</label>
            </td>
            <td nowrap="nowrap">
              <?php
              db_inputdata('dtAgendamentoPrestadora', '', '', '', true, 'text', 1, "onchange='js_validaDataPedido();'",
                           '', '', '', '', '', 'js_validaDataPedido()');
              db_input('horaAgendamentoPrestadora', 5, '', true, 'text', 1, '');
              ?>
            </td>
          </tr>
        </table>

      </fieldset>

      <fieldset class='separator'>
        <legend>Agendamento</legend>
        <table class="form-container">
          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              <label for="dtSaida">Data Saída:</label>
            </td>
            <td nowrap="nowrap">
              <?php
                db_inputdata('dtSaida', '', '', '', true, 'text', 1, "onchange='js_validaDataSaida();'",
                             '', '', '', '', '', 'js_validaDataSaida()');
              ?>
              <label for="horaSaida">Hora Saída:</label>
              <?php
              $sScript = "style='display:none; width: 60px;' onchange='js_atualizaHoraSaida();'";
              db_input('horaSaida', 5, '', true, 'text', 1);
              db_select('selectHoraSaida', array(), true, 1, $sScript );
              ?>
            </td>
          </tr>

          <tr>
            <td class='field-size2 bold'>
              <label for="sLocalSaida">Local:</label>
            </td>
            <td>
              <?php
              $sScript = "onkeyup='js_ValidaMaiusculo(this, \"t\", event);' style='text-transform:uppercase;'";
              db_input('sLocalSaida', 67, $Itf17_c_localsaida, true, 'text', 1, $sScript, "", "", "", 50);
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="tipoSaida">
                <label for="tipoSaida"><?=$Ltf17_tiposaida?></label>
              </label>
            </td>
            <td class="field-size4">
              <select id="tipoSaida">
                <option value="1">VEÍCULO</option>
                <option value="2">TRANSPORTE COLETIVO</option>
              </select>
            </td>
          </tr>
        </table>

      </fieldset>

      <fieldset id="fieldsetVeiculo" class='separator' style="display: none;">
        <legend>Veículo</legend>
        <table class="form-container">

          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              <label for="iCodigoVeiculo">
                <?php
                db_ancora($Ltf18_i_veiculo, "js_pesquisaVeiculo(true);", 1);
                ?>
              </label>
            </td>
            <td nowrap="nowrap">
              <?php
              db_input('iCodigoVeiculo', 10, $Itf18_i_veiculo, true, 'text', 1, " onchange='js_pesquisaVeiculo(false);'");
              db_input('sPlacaVeiculo',  56, '',               true, 'text', 3, '');
              ?>
            </td>
          </tr>

          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              <label for="iCodigoMotorista">
                <?php
                db_ancora($Ltf18_i_motorista, "js_pesquisaMotorista(true);", 1);
                ?>
              </label>
            </td>
            <td nowrap="nowrap">
              <?php
              $sScript = " onchange='js_pesquisaMotorista(false);'";
              db_input('iCodigoMotorista', 10, $Itf18_i_motorista, true, 'text', 1, $sScript );
              db_input('sNomeMotorista',   56, '' ,                true, 'text', 3 );
              ?>
            </td>
          </tr>

          <tr>
            <td class='field-size2 bold' nowrap="nowrap">
              <label for="dtRetorno">Data Retorno:</label>
            </td>
            <td nowrap="nowrap">
              <?php
              db_inputdata('dtRetorno', '', '', '', true, 'text', 1, "onchange='js_validaDataRetorno();'",
                           '', '', '', '', '', 'js_validaDataRetorno()');
              ?>
            </td>
          </tr>
          <tr>
            <td class='bold text-right' nowrap="nowrap">
              <label for="horaRetorno">Hora Retorno:</label>
            </td>
            <td nowrap="nowrap">
              <?php
              db_input('horaRetorno', 5, '', true, 'text', 1);
              ?>
            </td>
          </tr>    
        </table>

        <fieldset class='separator'>
          <legend>Lotação</legend>
          <table class="form-container">
            <tr>
              <td class='bold' nowrap="nowrap">
                <label for="totalLugares">Total de Lugares:</label>
              </td>
              <td nowrap="nowrap">
                <input type="text" size="3" id='totalLugares' class="readonly" value = '0' />
              </td>
              <td class='bold' nowrap="nowrap">
                <label for="numeroPacientes"> - Pacientes </label>
              </td>
              <td nowrap="nowrap">
                <input type="text" size="3" id='numeroPacientes' class="readonly" value = '0' />
              </td>
              <td class='bold' nowrap="nowrap">
                <label for="numeroAcompanhantes"> - Acompanhantes </label>
              </td>
              <td nowrap="nowrap">
                <input type="text" size="3" id='numeroAcompanhantes' class="readonly" value = '0' />
              </td>
              <td class='bold' nowrap="nowrap">
                <label for="totalLugaresDisponiveis"> = Lugares Disponíveis: </label>
              </td>
              <td nowrap="nowrap">
                <input type="text" size="3" id='totalLugaresDisponiveis' class="readonly" value = '0' />
              </td>
            </tr>
          </table>

        </fieldset>
      </fieldset>

      <fieldset id="fieldsetPassagem" class="separator" style="display: none;">
        <legend>Transporte Coletivo</legend>
        <table class="form-container">

          <tr>
            <td class="field-size2">
              <label for="tf38_valorunitario">
                <?=$Ltf38_valorunitario?>
              </label>
            </td>
            <td class="field-size2">
              <?php
              db_input( 'tf38_valorunitario', 10, $Itf38_valorunitario, true, 'text', 3 );
              ?>
            </td>
            <td>
              <span id="mensagemValorPassagem" style="float: left"></span>
            </td>
          </tr>

          <tr>
            <td class="field-size2">
              <label for="valorTotal">
                Valor Total:
              </label>
            </td>
            <td>
              <input id="valorTotal" type="text" value="" disabled="disabled" class="readonly field-size2" />
            </td>
          </tr>

        </table>
      </fieldset>

    </fieldset>
  </div>

  <div class="container">
    <fieldset style ='width:1000px;'>
        <legend>Passageiros</legend>
        <div id='ctnGridPassageiros'></div>
    </fieldset>

    <input name="salvar"  type="button" id="salvar"  value="Incluir"  />
    <input name="excluir" type="button" id="excluir" value="Excluir" style="display: none" />
    <input name="fechar"  type="button" id="fechar"  value="Fechar" onclick="parent.db_iframe_saida.hide();" />
  </div>
</form>
<script type="text/javascript">
var sRPC                = 'tfd4_agendasaida.RPC.php';
var oGet                = js_urlToObject();
var oPedido             = {};
var lUsaGradeHorario    = false;
var lTemValorCadastrado = true;

const MSG_AGENDASAIDA = 'saude.tfd.db_frm_agendasaida.';

var oHoraAgendamento = new DBInputHora($('horaAgendamentoPrestadora'));
var oHoraSaida       = new DBInputHora($('horaSaida'));
var oHoraRetorno     = new DBInputHora($('horaRetorno'));

var oGridPassageiros = new DBGrid('gridPassageiros');
oGridPassageiros.nameInstance = 'oGridPassageiros';
oGridPassageiros.setCheckbox(0);
oGridPassageiros.setCellWidth( ['10%', '80%', '10%'] );
oGridPassageiros.setCellAlign( ['right', 'left', 'center'] );
oGridPassageiros.setHeader( ['CGS', 'Passageiro', 'Fica'] );
oGridPassageiros.show($('ctnGridPassageiros'));

(function () {

  js_buscaParametros();
  js_buscaDadosPedido(oGet.tf17_i_pedidotfd);
})();

function js_buscaParametros() {

  var oParametros = {'sExecucao' : 'getParametros'};

  js_divCarregando(_M( MSG_AGENDASAIDA + "vericando_parametros"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');
    lUsaGradeHorario = oRetorno.lUtilizaGradeHorario;

    if ( lUsaGradeHorario ) {

      $('horaSaida').style.display       = 'none';
      $('selectHoraSaida').style.display = '';
    }
  };

  new Ajax.Request(sRPC, oObjeto);
}

function js_buscaDadosPedido (iPedido) {

  $('totalLugares').value            = 0;
  $('numeroPacientes').value         = 0;
  $('numeroAcompanhantes').value     = 0;
  $('totalLugaresDisponiveis').value = 0;

  var oParametros = {'sExecucao' : 'getDadosPedido', 'iPedido' : iPedido, 'iCgs' : oGet.tf01_i_cgsund};

  js_divCarregando(_M( MSG_AGENDASAIDA + "vericando_pedido"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    if (parseInt(oRetorno.iStatus) == 2) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    js_atualizaDadosPedido(oRetorno.oPedido);
    return true
  };

  new Ajax.Request(sRPC, oObjeto);
}

/**
 * Atualiza os dados do pedido
 * @param  {Object} oDadosPedido
 * @return {void}
 */
function js_atualizaDadosPedido(oDadosPedido) {

  oPedido         = oDadosPedido;
  oPedido.iPedido = oGet.tf17_i_pedidotfd;

  if (oPedido.iCodigoAgenda != '') {

    $('salvar').value          = 'Alterar';
    $('excluir').style.display = '';
  }

  $('iCodigoAgenda').value             = oPedido.iCodigoAgenda;
  $('iPedidoTFD').value                = oPedido.iPedido;
  $('iCgsPaciente').value              = oPedido.iCgs;
  $('sNomePaciente').value             = oPedido.sCgs.urlDecode();
  $('iCodigoPrestadora').value         = oPedido.iPrestadora;
  $('sNomePrestadora').value           = oPedido.sPrestadora.urlDecode();
  $('iCodigoCentral').value            = oPedido.iCentralAgendamento;
  $('sDestinoPrestadora').value        = oPedido.sDestino.urlDecode();
  $('iDestinoPrestadora').value        = oPedido.iDestino;
  $('dtAgendamentoPrestadora').value   = oPedido.dtAgendamento;
  $('horaAgendamentoPrestadora').value = oPedido.sHoraAgendamento;
  $('dtSaida').value                   = oPedido.dtSaida;

  /**
   * Sempre que existir data de saida e estiver configurado para usar grade de horario, devemos buscar
   * os horarios da data de saida
   */
  $('horaSaida').value = oPedido.sHoraSaida;

  if (oPedido.dtSaida !='' && lUsaGradeHorario) {

    js_buscaHorariosData();
    $('selectHoraSaida').value = oPedido.sHoraSaida;
  }

  $('sLocalSaida').value      = oPedido.sLocalSaida.urlDecode();
  $('dtRetorno').value        = oPedido.dtRetorno;
  $('horaRetorno').value      = oPedido.sHoraRetorno;
  $('iCodigoVeiculo').value   = oPedido.iVeiculo;
  $('sPlacaVeiculo').value    = oPedido.sPlaca.urlDecode();
  $('iCodigoMotorista').value = oPedido.iMotorista;
  $('sNomeMotorista').value   = oPedido.sMotorista.urlDecode();

  js_buscaAcompanhantes();

  $('tipoSaida').value = oPedido.iTipoSaida;
  trataTipoSaida();

  if (oPedido.iVeiculo != '' && oPedido.iLotacaoVeiculo != '' && oPedido.dtSaida != '' && oPedido.sHoraSaida != '') {

    $('totalLugares').value            = oPedido.iLotacaoVeiculo;
    $('totalLugaresDisponiveis').value = oPedido.iLotacaoVeiculo;
    js_buscaVagasOcupadasVeiculo();
  }

  $('tf38_valorunitario').value        = oPedido.sValorUnitario;
  $('valorTotal').value                = js_formatar( oPedido.sValorUnitario, 'f' );
  $('mensagemValorPassagem').innerHTML = '';

  if( oPedido.sValorUnitario.trim() == '' ) {

    var oMensagem          = [];
        oMensagem.sDestino = $F('sDestinoPrestadora');

    var sMensagem = _M( MSG_AGENDASAIDA + "valor_passagem_nao_cadastrada", oMensagem );

    $('mensagemValorPassagem').innerHTML = sMensagem;

    lTemValorCadastrado = false;
  }

  if( $F('tipoSaida') == 2 && oPedido.sValorUnitario.trim() != '' ) {
    calculaValorTotalPassagens();
  }

  $('tf38_valorunitario').value = js_formatar( oPedido.sValorUnitario, 'f' );
}

/**
 * Busca os acompanhates do paciente
 * @return {void}
 */
function js_buscaAcompanhantes() {

  var oParametros = {
    'sExecucao'    : 'getAcompanhantes',
    'iPedido'      : oPedido.iPedido
  };

  js_divCarregando(_M( MSG_AGENDASAIDA + "vericando_acompanhantes"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    oGridPassageiros.clearAll(true);

    var aLinha = [];
        aLinha.push(oPedido.iCgs);
        aLinha.push(oPedido.sCgs.urlDecode());
        aLinha.push( js_criaCheckBoxFica( oPedido.iCgs, oPedido.iFica ).outerHTML );

    oGridPassageiros.addRow(aLinha, false, false, true);

    oRetorno.aAcompanhantes.each( function (oAcompanhante ) {

      var lMarcaLinha = oAcompanhante.lVinculadoCarro || oAcompanhante.lPossuiPassagem;
      var aLinha      = [];
          aLinha.push(oAcompanhante.iAcompanhante);
          aLinha.push(oAcompanhante.sAcompanhante.urlDecode());
          aLinha.push( js_criaCheckBoxFica( oAcompanhante.iAcompanhante, oAcompanhante.iFica ).outerHTML );

      oGridPassageiros.addRow(aLinha, false, false, lMarcaLinha);
    });

    oGridPassageiros.renderRows();

    document.getElementById(oGridPassageiros.aRows[0].aCells[0].getId() ).firstChild.setAttribute('disabled', 'disabled');

    for( var elemento of $$("#gridPassageirosbody input[type='checkbox']") ) {
      elemento.observe('click', calculaValorTotalPassagens);
    }

    var iLinhas = oGridPassageiros.aRows.length;
    /**
     * Acrescenta função a partir da segunda linha da grid
     */
    for (var i = 1; i < iLinhas; i++) {

      var oCheckGrid = document.getElementById(oGridPassageiros.aRows[i].aCells[0].getId() ).firstChild;
      oCheckGrid.observe('click', function() {

        if( $F('tipoSaida') == 1 && $F('iCodigoVeiculo') == '' ) {

          alert( _M(MSG_AGENDASAIDA + "veiculo_nao_informado") );
          this.checked = false;
          return false;
        }

        if ( this.checked ) {

          $('numeroAcompanhantes').value     = parseInt($F('numeroAcompanhantes')) + 1;
          $('totalLugaresDisponiveis').value = parseInt($F('totalLugaresDisponiveis')) - 1;
        } else {

          $('numeroAcompanhantes').value     = parseInt($F('numeroAcompanhantes')) - 1;
          $('totalLugaresDisponiveis').value = parseInt($F('totalLugaresDisponiveis')) + 1;
        }
      });
    }
  };

  new Ajax.Request(sRPC, oObjeto);
}

/**
 * Cria o checbox para grid, marcado sigifica que o paciente ficará no destino
 * @param  {int} iCgs
 * @param  {int} iFica
 * @return {HTMLINPUTCHECKBOX}
 */
function js_criaCheckBoxFica(iCgs, iFica) {

  var oCheckBox           = document.createElement('input');
      oCheckBox.type      = 'checkbox';
      oCheckBox.id        = 'fica#'+iCgs;
      oCheckBox.value     = iCgs;
      oCheckBox.className = 'checkFica';

  if ( parseInt(iFica) == 1) {
    oCheckBox.setAttribute('checked','checked');
  }

  return oCheckBox;
}

/**
 * Busca prestadora
 * @param  {boolean} lMostra
 * @return {void}
 */
function js_pesquisaPrestadora(lMostra) {

  /**
   * Variavel iCodigoPrestadora guarda o codigo digitado da prestadora, para que quando seja executado: js_removerAgendamento()
   * Após recarregar os dados, ainda termos o código digitado pelo usuário, e buscar os dados da prestadora digitada
   */
  var iCodigoPrestadora = $F('iCodigoPrestadora');
  if (oPedido.iCodigoAgenda != '') {

    if (confirm( _M(MSG_AGENDASAIDA + "pedido_possui_agenda_trocar_prestadora_excluir_agenda") ) ) {
      js_removerAgendamento();
    } else {

      if (!lMostra) {
        $('iCodigoPrestadora').value = oPedido.iPrestadora;
      }
      return false;
    }
  }

  var sUrl    = 'func_tfd_prestadoracentralagend.php';
  var sIframe = 'db_iframe_tfd_prestadoracentralagend';

  if($F('iCodigoCentral') == '') {

    alert(_M( MSG_AGENDASAIDA + "pedido_sem_central_atendimento") );
    return false;
  }

  sUrl += '?lRetornaDadosPassagem&chave_tf10_i_centralagend='+$F('iCodigoCentral');
  sUrl += '&funcao_js=parent.js_mostraPrestadora|tf10_i_prestadora|z01_nome|z01_munic|tf25_i_destino|tf10_i_codigo|tf37_valor';

  if (lMostra) {
    js_OpenJanelaIframe('', sIframe, sUrl, 'Pesquisa Prestadora', true);
  } else {

    if( $F('iCodigoPrestadora') != '') {

      sUrl += '&chave_tf10_i_prestadora='+iCodigoPrestadora+'&nao_mostra=true';
      js_OpenJanelaIframe('', sIframe, sUrl, 'Pesquisa Prestadora',false);

    } else {

      $('iCodigoPrestadora').value         = '';
      $('sNomePrestadora').value           = '';
      $('sDestinoPrestadora').value        = '';
      $('iDestinoPrestadora').value        = '';
      $('iVinculoCentralPrestadora').value = '';
      $('tf38_valorunitario').value        = '';
      $('valorTotal').value                = '';
    }
  }
}

function js_mostraPrestadora(iPrestadora, sPrestadora, sMunicipio, iDestino, iVinculoCentralPrestadora, sValor) {

  if (iPrestadora == '') {

    $('sNomePrestadora').value = sPrestadora;
    return false;
  }

  $('iVinculoCentralPrestadora').value = iVinculoCentralPrestadora;
  $('iCodigoPrestadora').value         = iPrestadora;
  $('sNomePrestadora').value           = sPrestadora;
  $('sDestinoPrestadora').value        = sMunicipio;
  $('iDestinoPrestadora').value        = iDestino;
  $('tf38_valorunitario').value        = sValor;

  db_iframe_tfd_prestadoracentralagend.hide();

  oPedido.iPrestadora = iPrestadora;
  oPedido.sPrestadora = sPrestadora;
  oPedido.sDestino    = sMunicipio;

  if( sValor.trim() != '' ) {

    calculaValorTotalPassagens();
    $('tf38_valorunitario').value = js_formatar( $F('tf38_valorunitario'), 'f' );
  }
}

/**
 * Valida as informações do agendamento
 * @return {boolean}
 */
function js_validaDadosAgendamento() {

  if ($F('dtSaida') == '' ) {

    alert( _M(MSG_AGENDASAIDA + "data_saida_nao_informada") );
    return false;
  }

  if ($F('horaSaida') == '' ) {

    alert( _M(MSG_AGENDASAIDA + "hora_saida_nao_informada") );
    return false;
  }

  if ($F('sLocalSaida') == '') {

    alert( _M(MSG_AGENDASAIDA + "local_saida_nao_informado") );
    return false;
  }

  return true;
}

var lVeiculoAlterado = false;

/**
 * Pesquisa os veículos disponíveis para o módulo
 * @param  {boolean} lMostra
 * @return {void}
 */
function js_pesquisaVeiculo(lMostra) {

  if (!js_validaDadosAgendamento()) {

    $('iCodigoVeiculo').value = '';
    return false;
  }

  if ( $F('iCodigoAgenda') != '' && oPedido.iVeiculo != '' && !lVeiculoAlterado) {

    if ( confirm( _M(MSG_AGENDASAIDA + "confirma_troca_veiculo") ) ) {

      js_desmarcaPassageiros();
      js_removePassageirosVeiculo();
    } else {

      if (!lMostra) {
        $('iCodigoVeiculo').value = oPedido.iVeiculo;
      }
      return false;
    }
  }

  var sUrl = 'func_veiculosalt.php';

  if (lMostra) {

    sUrl += '?funcao_js=parent.js_mostraVeiculo1|ve01_codigo|ve01_quantcapacidad|ve01_placa'
    js_OpenJanelaIframe('', 'db_iframe_veiculos', sUrl, 'Pesquisa Veículos', true);
  } else {

    if ($F('iCodigoVeiculo') != '') {

      sUrl += '?funcao_js=parent.js_mostraVeiculo&iParam=1';
      sUrl += '&pesquisa_chave=' + $F('iCodigoVeiculo');

      js_OpenJanelaIframe('', 'db_iframe_veiculos', sUrl, 'Pesquisa Veículos', false);
    } else {

      $('iCodigoVeiculo').value = '';
      $('sPlacaVeiculo').value = '';
    }
  }
}

function js_mostraVeiculo(sPlaca, iCapacidade, lErro) {

  $('sPlacaVeiculo').value           = sPlaca;
  $('totalLugares').value            = iCapacidade;
  $('totalLugaresDisponiveis').value = iCapacidade;
  if ( lErro ) {

    $('iCodigoVeiculo').focus();
    $('iCodigoVeiculo').value          = '';
    $('totalLugares').value            = 0;
    $('totalLugaresDisponiveis').value = 0;
    $('numeroPacientes').value         = 0;
    $('numeroAcompanhantes').value     = 0;
    return false;
  }

  js_buscaVagasOcupadasVeiculo();
}

function js_mostraVeiculo1(iVeiculo, iCapacidade, sPlaca) {

  $('iCodigoVeiculo').value          = iVeiculo;
  $('sPlacaVeiculo').value           = sPlaca;
  $('totalLugares').value            = iCapacidade;
  $('totalLugaresDisponiveis').value = iCapacidade;
  db_iframe_veiculos.hide();
  js_buscaVagasOcupadasVeiculo();
}

/**
 * Busca as vagas ocupadas pelo veículo
 * @return {void}
 */
function js_buscaVagasOcupadasVeiculo () {

  var oParametros         = {'sExecucao' : 'getVagasVeiculo'};
  oParametros.dtSaida     = $F('dtSaida');
  oParametros.dtRetorno   = $F('dtRetorno');
  oParametros.horaSaida   = $F('horaSaida');
  oParametros.horaRetorno = $F('horaRetorno');
  oParametros.iVeiculo    = $F('iCodigoVeiculo');
  oParametros.iDestino    = $F('iDestinoPrestadora');

  js_divCarregando( _M( MSG_AGENDASAIDA + "vericando_vagas_veiculo"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    if (parseInt(oRetorno.iStatus) == 2) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    /**
     * Quando o temos a informação do código da agenda, o paciente já esta contabilizado na soma dos passageiros
     * por isso zeramos a variavel: iPacienteAtual
     */
    var iPacienteAtual = 1;

    if ($F('iCodigoAgenda') != '' && $F('iCodigoVeiculo') == oPedido.iVeiculo) {
      iPacienteAtual = 0;
    }

    $('numeroPacientes').value         = oRetorno.oVagas.iPassageiros + iPacienteAtual;
    $('numeroAcompanhantes').value     = oRetorno.oVagas.iAcompanhantes;
    $('totalLugaresDisponiveis').value = (parseInt($F('totalLugaresDisponiveis') - oRetorno.oVagas.iTotal)) - iPacienteAtual;

    return true
  };

  new Ajax.Request(sRPC, oObjeto);
}

function js_desmarcaPassageiros () {

  var iLinhas = oGridPassageiros.aRows.length;

  /**
   * Desmarca os acompanhantes informados na grid
   */
  for (var i = 1; i < iLinhas; i++) {

    $(oGridPassageiros.aRows[i].sId).removeClassName('marcado');
    $(oGridPassageiros.aRows[i].sId).addClassName('normal');
    var oCheckGrid = document.getElementById(oGridPassageiros.aRows[i].aCells[0].getId() ).firstChild;
    oCheckGrid.checked = false
  }
}

function js_pesquisaMotorista(lMostra) {

  var sUrl = 'func_veicmotoristasalt.php';
  if (lMostra) {

    sUrl += '?funcao_js=parent.js_mostraMotorista1|ve05_codigo|z01_nome';
    js_OpenJanelaIframe('', 'db_iframe_veicmotoristas', sUrl, 'Pesquisa Motorista', true);

  } else if ($F('iCodigoMotorista') != '') {

    sUrl += '?funcao_js=parent.js_mostraMotorista';
    sUrl += '&pesquisa_chave=' + $F('iCodigoMotorista');
    js_OpenJanelaIframe('', 'db_iframe_veicmotoristas', sUrl, 'Pesquisa Motorista', false );
  } else {

    $('iCodigoMotorista').value = '';
    $('sNomeMotorista').value   = '';
  }
}

function js_mostraMotorista(sNomeMotorista, sErro) {

  $('sNomeMotorista').value = sNomeMotorista;
  if (sErro) {

    $('iCodigoMotorista').value = '';
    $('iCodigoMotorista').focus();
  }
}

function js_mostraMotorista1(iCodigoMotorista, sNomeMotorista) {

  $('iCodigoMotorista').value = iCodigoMotorista;
  $('sNomeMotorista').value   = sNomeMotorista;
  db_iframe_veicmotoristas.hide();
}

function js_validaDataPedido() {

  if (js_comparadata($F('dtAgendamentoPrestadora'), oGet.dataPedido, '<')) {

    alert(_M(MSG_AGENDASAIDA + "data_saida_menor_data_pedido"));
    $('dtAgendamentoPrestadora').value = '';
    return false;
  }

  if ($F('dtSaida') != '' && js_comparadata($F('dtAgendamentoPrestadora'), $F('dtSaida'), '<') ) {

    alert(_M(MSG_AGENDASAIDA + "data_agendamento_menor_data_saida"));
    $('dtAgendamentoPrestadora').value = '';
    return false;
  }

  return true;
}

/**
 * Valida se a data de saída é maior que a data agendada
 * @return {[type]} [description]
 */
function js_validaDataSaida() {

  if (js_comparadata($F('dtSaida'), $F('dtAgendamentoPrestadora'), '>')) {

    alert( _M(MSG_AGENDASAIDA + "data_saida_maior_data_agenda_prestadora") );
    $('dtSaida').value = '';
    return false;
  }

  if (lUsaGradeHorario) {
    js_buscaHorariosData();
  }
  return true;
}

function js_validaDataRetorno() {

  if ( $F('dtSaida') == '' && $F('dtRetorno') == '') {
    return false;
  }

  if (js_comparadata($F('dtRetorno'), $F('dtSaida'), '<')) {

    alert(_M(MSG_AGENDASAIDA + "data_retorno_menor_data_saida"));
    $('dtSaida').value = '';
    return false;
  }

  return true;
}

function js_buscaHorariosData() {

  var oParametros = {'sExecucao' : 'getHorasGradeHorario', 'dtSaida' : $F('dtSaida'), 'iDestino' : $F('iDestinoPrestadora') };

  js_divCarregando(_M( MSG_AGENDASAIDA + "buscando_horarios_grade"), "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    if (parseInt(oRetorno.iStatus) == 2) {

      alert(oRetorno.sMensagem.urlDecode());
      return false;
    }

    $('selectHoraSaida').style.display = '';
    $('selectHoraSaida').add(new Option(' ', ''));
    oRetorno.aHorarios.each(function(sHora) {
      $('selectHoraSaida').add(new Option(sHora, sHora));
    });
  };

  new Ajax.Request(sRPC, oObjeto);
}

/**
 * Quando estiver configurado para utilizar grade de horarios, ao selecionar um horario
 * atualiza o input horaSaida. Assim não há nescessidade de alterar todas as funções para validar a hora de saida
 * de um input e de um select
 * @return {void}
 */
function js_atualizaHoraSaida() {
  $('horaSaida').value =  $('selectHoraSaida').value;
}

/**
 * Realiza a validação dos campos obrigatórios
 * @return {boolean}
 */
function validaCamposObrigatorios () {

  if ($F('iCodigoPrestadora') == '') {

    alert( _M(MSG_AGENDASAIDA + "prestadora_nao_informada") );
    return false;
  }

  if ($F('dtAgendamentoPrestadora') == '') {

    alert( _M(MSG_AGENDASAIDA + "data_agendamento_nao_informada") );
    return false;
  }

  if ($F('horaAgendamentoPrestadora') == '') {

    alert( _M(MSG_AGENDASAIDA + "hora_agendamento_nao_informada") );
    return false;
  }

  if ( !js_validaDadosAgendamento() ) {
    return false;
  }

  return true;
}

$('salvar').observe('click', function() {
  js_salvar();
});

function js_salvar() {

  if( $F('tipoSaida') == 2 && !lTemValorCadastrado ) {

    alert( _M( MSG_AGENDASAIDA + 'inclusao_sem_valor' ) );
    return false;
  }

  if (!validaCamposObrigatorios()) {
    return false;
  }

  /*aqui vai um Plugin SMSTFD*/


  /* INFORMA SE O VEICULO JA ESTA LOTADO */
  if( $F('tipoSaida') == 1 && !empty( $F('iCodigoVeiculo') ) && parseInt($F('totalLugaresDisponiveis')) < 0 ) {

    alert( _M( MSG_AGENDASAIDA + "veiculo_sem_vagas_selecione_outro_veiculo", {sCampo : $F('sPlacaVeiculo')}) );
    return false;
  }

  var sMensagemDivCarregando = _M( MSG_AGENDASAIDA + "incluindo_agenda" );
  var oParametros            = { 'sExecucao' : 'salvar'};

  oParametros.iCodigoAgendamentoPrestadora = oPedido.iCodigoAgendamentoPrestadora;
  oParametros.iCodigoAgenda                = $F('iCodigoAgenda');
  oParametros.iPedidoTFD                   = $F('iPedidoTFD');
  oParametros.iCgsPaciente                 = $F('iCgsPaciente');
  oParametros.iCodigoPrestadora            = $F('iCodigoPrestadora');
  oParametros.iCodigoCentral               = $F('iCodigoCentral');
  oParametros.iVinculoCentralPrestadora    = $F('iVinculoCentralPrestadora');
  oParametros.iDestinoPrestadora           = $F('iDestinoPrestadora');
  oParametros.dtAgendamentoPrestadora      = $F('dtAgendamentoPrestadora');
  oParametros.horaAgendamentoPrestadora    = $F('horaAgendamentoPrestadora');
  oParametros.dtSaida                      = $F('dtSaida');
  oParametros.horaSaida                    = $F('horaSaida');
  oParametros.sLocalSaida                  = encodeURIComponent(tagString($F('sLocalSaida')));
  oParametros.dtRetorno                    = $F('dtRetorno');
  oParametros.horaRetorno                  = $F('horaRetorno');
  oParametros.iCodigoVeiculo               = $F('iCodigoVeiculo');
  oParametros.iCodigoMotorista             = $F('iCodigoMotorista');
  oParametros.iTipoSaida                   = $F('tipoSaida');
  oParametros.sValorUnitario               = $F('tf38_valorunitario');
  oParametros.aPassageiros                 = [];

  var aSelecionadosGrade = oGridPassageiros.getSelection();
  aSelecionadosGrade.each( function(aLinha) {

    var iCgsGrade    = aLinha[0];
    var lFicaDestino = $('fica#'+iCgsGrade).checked;
    var oPassageiro  = {iCgs : iCgsGrade, lPaciente : iCgsGrade == oParametros.iCgsPaciente, lFica : lFicaDestino};

    oParametros.aPassageiros.push(oPassageiro);
  });

  js_divCarregando( sMensagemDivCarregando , "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    alert(oRetorno.sMensagem.urlDecode());
    if (parseInt(oRetorno.iStatus) == 2) {
      return false;
    }

    /**
     * Para não recarregar os dados, seta algumas variáveis com os dados salvos
     */
    $('iCodigoAgenda').value   = oRetorno.iCodigoAgenda;
    oPedido.iCodigoAgenda      = oRetorno.iCodigoAgenda;
    oPedido.iVeiculo           = $F('iCodigoVeiculo');
    $('salvar').value          = "Alterar";
    $('excluir').style.display = '';
    lVeiculoAlterado           = false;
    parent.db_iframe_saida.hide();
    return true
  };

  new Ajax.Request(sRPC, oObjeto);
}

$('excluir').observe('click', function () {
  js_removerAgendamento();
});

/**
 * @todo implementar... exluir tudo vinculado  tabela tfd_agendasaida e vínculo do pedido com o carro
 */
function js_removerAgendamento() {

  var oParametros            = { 'sExecucao' : 'removerAgendamento'};
  oParametros.iCodigoAgenda  = $F('iCodigoAgenda');
  oParametros.iPedidoTFD     = $F('iPedidoTFD');

  js_divCarregando( _M( MSG_AGENDASAIDA + "removendo_agenda" ) , "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    alert(oRetorno.sMensagem.urlDecode());
    if (parseInt(oRetorno.iStatus) == 2) {
      return false;
    }

    $('iCodigoAgenda').value   = '';
    $('salvar').value          = "Incluir";
    $('excluir').style.display = 'none';
    js_buscaDadosPedido($F('iPedidoTFD'));

    return true
  };

  new Ajax.Request(sRPC, oObjeto);
}


function js_removePassageirosVeiculo() {

  var oParametros        = { 'sExecucao' : 'removerPassageirosVeiculo'};
  oParametros.iPedidoTFD = $F('iPedidoTFD');

  js_divCarregando( _M( MSG_AGENDASAIDA + "removendo_passageiros" ) , "msgBox");

  var oObjeto          = {};
  oObjeto.method       = 'post';
  oObjeto.parameters   = 'json='+Object.toJSON(oParametros);
  oObjeto.asynchronous = false;
  oObjeto.onComplete   = function(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval('(' + oAjax.responseText + ')');

    alert(oRetorno.sMensagem.urlDecode());
    if (parseInt(oRetorno.iStatus) == 2) {
      return false;
    }

    lVeiculoAlterado = true;
    return true
  };

  new Ajax.Request(sRPC, oObjeto);
}

/**
 * Controla os fieldsets de acordo com o tipo de saída selecionado
 */
function trataTipoSaida() {

  $('fieldsetVeiculo').setStyle( { 'display': '' } );
  $('fieldsetPassagem').setStyle( { 'display': 'none' } );

  if( $F('tipoSaida') == 2 ) {

    $('fieldsetVeiculo').setStyle( { 'display': 'none' } );
    $('fieldsetPassagem').setStyle( { 'display': '' } );
  }
}

/**
 * Responsável por calcular o valor total das passagens
 */
function calculaValorTotalPassagens() {

  var nValorTotal    = 0.00;
  var nValorUnitario = $F('tf38_valorunitario').getNumber();
  var iMultiplicador;

  for (var linha of $$("#gridPassageirosbody tr.marcado")) {

    iMultiplicador = !!$$('#'+ linha.id + ' .checkFica:checked').length ? 1 : 2;
    nValorTotal   += nValorUnitario * iMultiplicador;
  }

  $('valorTotal').value = js_formatar(nValorTotal, 'f');
}

$('tipoSaida').onchange = function() {
  trataTipoSaida();
};

$('iCodigoVeiculo').className     = 'field-size2';
$('iCodigoMotorista').className   = 'field-size2';
$('dtRetorno').className          = 'field-size2';
$('horaRetorno').className        = 'field-size2';
$('tf38_valorunitario').className = 'field-size2';

calculaValorTotalPassagens();
</script>