<?
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
?>
<div style="margin-top: 25px;">

  <form class="container">
    <fieldset><legend>Vincular Veículo ao Itinerário</legend>
      <table>

        <tr>
          <td class="bold">
            <?php
              db_ancora('Linha:', 'js_pesquisaLinhaTransporte(true)', $db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('tre06_sequencial', 8, '', true, 'text', $db_opcao, 'onblur="js_pesquisaLinhaTransporte(false)"');
              db_input('tre06_nome', 55, '', true);
            ?>
          </td>
        </tr>

        <tr>
          <td class="bold">Itinerário:</td>
          <td>
            <?php
              $aItinerario = array(1 => 'Ida', 2 => 'Retorno');
              db_select('iItinerario', $aItinerario, true, $db_opcao, 'onchange="js_limpaHorario()"');
            ?>
          </td>
        </tr>

        <tr>
          <td class="bold">
            <?php
              db_ancora('Hora:', 'js_pesquisaLinhaTransporteHorario(true)', $db_opcao);
              db_input('tre07_sequencial', 10, '', true, 'hidden');
            ?>
          </td>
          <td>
            <?php
              db_input('tre07_horasaida', 8, '', true);
              echo ' à ';
              db_input('tre07_horachegada', 8, '', true);
            ?>
          </td>
        </tr>

        <tr>
          <td class="bold">
            <?php
              db_ancora('Veículo:', 'js_pesquisaVeiculos(true)', $db_opcao);
            ?>
          </td>
          <td>
            <?php
              db_input('tre01_sequencial', 8, '', true);
              db_input('ve22_descr', 55, '', true);
            ?>
          </td>
        </tr>

        <tr>
          <td class="bold" nowrap>Capacidade:</td>
          <td>
            <?php
              db_input('tre01_numeropassageiros', 8, '', true);
            ?>
          </td>
        </tr>

      </table>
    </fieldset>
    <input type="button" name="btnSalvar" id="btnSalvar" value="Salvar" onclick="js_salvarVeiculoLinha()" />
  </form>

  <fieldset class="container" style="width: 70%;">
    <legend>Vínculo</legend>
    <div id="ctnVinculo"></div>
  </fieldset>

</div>

<script>

var sUrlRpc = 'tre4_veiculotransporte.RPC.php';

/**
 * Grid dos vínculos dos veículos com itinerários
 */
var oGridVinculo          = new DBGrid('oGridVinculo');
oGridVinculo.nameInstance = 'oGridVinculo';
oGridVinculo.setHeight   (250);
oGridVinculo.setCellAlign(new Array('center', 'left',       'left',    'left',    'center', 'center'));
oGridVinculo.setHeader   (new Array('Código', 'Itinerário', 'Horário', 'Veículo / Empresa', 'Capacidade', 'Ação'));
oGridVinculo.setCellWidth(new Array('8%', '10%', '13%', '50%', '%12', '7%'));
oGridVinculo.aHeaders[0].lDisplayed = false;
oGridVinculo.show        ($('ctnVinculo'));

function js_limpaHorario() {
  $('tre07_sequencial').value  = '';
  $('tre07_horasaida').value   = '';
  $('tre07_horachegada').value = '';
}

/**
 * Pesquisa as linhas
 */
function js_pesquisaLinhaTransporte(lMostra) {

  var sUrl = 'func_linhatransporte.php?funcao_js=parent.js_mostraLinhaTransporte';

  if (lMostra) {
    sUrl += '|tre06_sequencial|tre06_nome';
  } else {

    if ($F('tre06_sequencial') != '') {
      sUrl += '&pesquisa_chave='+$F('tre06_sequencial');
    }
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_linhatransporte', sUrl, 'Pesquisa Linha', lMostra);
}

/**
* Retorno da pesquisa das linhas
*/
function js_mostraLinhaTransporte() {

  if (arguments[1] === true) {

    $('tre06_sequencial').value = '';
    $('tre06_nome').value       = arguments[0];
  } else if (arguments[1] === false) {
    $('tre06_nome').value       = arguments[0];
  } else {

   $('tre06_sequencial').value = arguments[0];
   $('tre06_nome').value       = arguments[1];
  }

 db_iframe_linhatransporte.hide();
 js_buscaVeiculosHorario();
}

/**
 * Pesquisa os horários da linha
 */
function js_pesquisaLinhaTransporteHorario(lMostra) {

  if ($F('tre06_sequencial') == '') {

    alert(_M('educacao.transporteescolar.db_frmvinculoveiculolinha.linha_nao_informada'));
    return false;
  }

  var sUrl = 'func_linhatransportehorario.php?funcao_js=parent.js_mostraLinhaTransporteHorario';

  if (lMostra) {
    sUrl += '|tre07_sequencial|tre07_horasaida|tre07_horachegada';
    sUrl += '&iLinha='+$F('tre06_sequencial')+'&iTipo='+$F('iItinerario');
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_linhatransportehorario', sUrl, 'Pesquisa Horário', lMostra);
}

/**
* Retorno da pesquisa das linhas
*/
function js_mostraLinhaTransporteHorario() {

  if (arguments[1] === true) {

    $('tre07_sequencial').value  = '';
    $('tre07_horasaida').value   = arguments[0];
    $('tre07_horachegada').value = arguments[1];
  } else if (arguments[1] === false) {

    $('tre07_horasaida').value   = arguments[0];
    $('tre07_horachegada').value = arguments[1];
  } else {

     $('tre07_sequencial').value  = arguments[0];
     $('tre07_horasaida').value   = arguments[1];
     $('tre07_horachegada').value = arguments[2];

     js_buscaVeiculosHorario();
  }

  db_iframe_linhatransportehorario.hide();
}

/**
 * Pesquisa os veículos
 */
function js_pesquisaVeiculos(lMostra) {

  if ($F('tre06_sequencial') == '') {

    alert(_M('educacao.transporteescolar.db_frmvinculoveiculolinha.linha_nao_informada'));
    return false;
  }
  var sUrl = 'func_veiculotransportemunicipal.php?funcao_js=parent.js_mostraVeiculos';
  if (lMostra) {

    sUrl += '|tre01_sequencial|ve22_descr|tre01_numeropassageiros';
    sUrl += '&iLinha='+$F('tre06_sequencial');
  }
  js_OpenJanelaIframe('top.corpo', 'db_iframe_veiculos', sUrl, 'Pesquisa Veículos', lMostra);
}

/**
* Retorno da pesquisa dos veículos
*/
function js_mostraVeiculos() {

  if (arguments[1] === true) {

    $('tre01_sequencial').value        = '';
    $('ve22_descr').value              = arguments[0];
    $('tre01_numeropassageiros').value = '';
  } else if (arguments[1] === false) {

    $('tre01_sequencial').value        = arguments[0];
    $('ve22_descr').value              = arguments[1];
    $('tre01_numeropassageiros').value = arguments[2];
  } else {

     $('tre01_sequencial').value        = arguments[0];
     $('ve22_descr').value              = arguments[1];
     $('tre01_numeropassageiros').value = arguments[2];
  }

  db_iframe_veiculos.hide();
}

/**
 * Salva as informacoes do vínculo do veículo com a linha
 */
function js_salvarVeiculoLinha() {

   if ($F('tre06_sequencial') == '') {

     alert(_M('educacao.transporteescolar.db_frmvinculoveiculolinha.linha_nao_informada'));
     return false;
   }

   if ($F('tre07_sequencial') == '' ) {

     alert(_M('educacao.transporteescolar.db_frmvinculoveiculolinha.horario_nao_informado'));
     return false;
   }
   if ($F('tre01_sequencial') == '' ) {

     alert(_M('educacao.transporteescolar.db_frmvinculoveiculolinha.veiculo_nao_informado'));
     return false;
   }

  var oParametro              = new Object();
      oParametro.exec         = 'salvarVeiculoLinha';
      oParametro.iLinha       = $F('tre06_sequencial');
      oParametro.iItinerario  = $F('iItinerario');
      oParametro.iHorario     = $F('tre07_sequencial');
      oParametro.iVeiculo     = $F('tre01_sequencial');

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoSalvarVeiculoLinha;

  js_divCarregando(_M('educacao.transporteescolar.db_frmvinculoveiculolinha.aguardando_salvar'), "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno do salvar as informacoes do vínculo do veículo com a linha
 */
function js_retornoSalvarVeiculoLinha(oResponse) {

   js_removeObj("msgBox");
   var oRetorno = eval('('+oResponse.responseText+')');

   alert(oRetorno.message.urlDecode());
   if (oRetorno.status == 1) {

     js_limpaCampos();
     js_buscaVeiculosHorario();
   }
}

/**
* Busca os veículos do horário
*/
function js_buscaVeiculosHorario() {

 var oParametro              = new Object();
     oParametro.exec         = 'getVeiculosHorario';
     oParametro.iLinha       = $F('tre06_sequencial');
     oParametro.iItinerario  = $F('iItinerario');
     oParametro.iHorario     = $F('tre07_sequencial');

 var oDadosRequisicao            = new Object();
     oDadosRequisicao.method     = 'post';
     oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
     oDadosRequisicao.onComplete = js_retornoVeiculosHorario;

 js_divCarregando(_M('educacao.transporteescolar.db_frmvinculoveiculolinha.buscando_veiculoshorario'), "msgBox");
 new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
* Retorno dos veículos do horário
*/
function js_retornoVeiculosHorario(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');
  if (oRetorno.status == 1) {

    oGridVinculo.clearAll(true);
    oRetorno.aVeiculosHorario.each(function(oVeiculoHorario, iSeq) {

      var aLinha    = new Array();
          aLinha[0] = oVeiculoHorario.iVeiculoHorario;
          aLinha[1] = oVeiculoHorario.sItinerario.urlDecode();
          aLinha[2] = oVeiculoHorario.sHorario.urlDecode();
          aLinha[3] = oVeiculoHorario.sVeiculo.urlDecode();
          aLinha[4] = oVeiculoHorario.iNumeroPassageiros;
          aLinha[5] = '<input id="veiculoHorario_"'+oVeiculoHorario.iItinerarioHorario+
                              ' type="button" '+
                              ' value="E" '+
                              ' onClick="js_removerVeiculoHorario('+oVeiculoHorario.iItinerarioHorario+', '+oVeiculoHorario.iVeiculoHorario+');" />';
      oGridVinculo.addRow(aLinha);
    });

    oGridVinculo.renderRows();
  }
}

/**
 * Remove um vinculo existente entre um veículo e o horário
 */
function js_removerVeiculoHorario(iItinerarioHorario, iVeiculoHorario) {

  if (confirm(_M('educacao.transporteescolar.db_frmvinculoveiculolinha.confirma_remover_veiculohorario'))) {

    var oParametro                    = new Object();
        oParametro.exec               = 'removerVinculoVeiculo';
        oParametro.iItinerarioHorario = iItinerarioHorario;
        oParametro.iVeiculoHorario    = iVeiculoHorario;

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoRemoverVeiculo;

    js_divCarregando(_M('educacao.transporteescolar.db_frmvinculoveiculolinha.aguardando_remover_vinculo'), "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
}

/**
 * Retorno da remoção do vínculo.
 */
function js_retornoRemoverVeiculo(oResponse) {

   js_removeObj("msgBox");
   var oRetorno = eval('('+oResponse.responseText+')');

   alert(oRetorno.message.urlDecode());

   if (oRetorno.status == 1) {
     js_buscaVeiculosHorario();
   }
}

/**
 * Limpa os campos hora, veiculo e capacidade do formulario
 */
function js_limpaCampos() {

  $('tre07_horasaida').value         = '';
  $('tre07_horachegada').value       = '';
  $('tre01_sequencial').value        = '';
  $('ve22_descr').value              = '';
  $('tre01_numeropassageiros').value = '';
}
</script>