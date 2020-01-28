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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link rel="stylesheet" type="text/css" href="estilos.css" />
  <link rel="stylesheet" type="text/css" href="estilos/grid.style.css" />
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/time.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
</head>
<body bgcolor="#CCCCCC">

  <div class="container">

    <form id="turmaAcHorario">

      <fieldset>
        <legend>Horários da Turma</legend>
        <table class="form-container">

          <tr>
            <td nowrap='nowrap' colspan="4">
              <input class='diassemana' type="checkbox" value="1" id="domingo" />
              <label for="domingo"> Domingo </label>

              <input class='diassemana' type="checkbox" value="2" id="segunda" />
              <label for="segunda"> Segunda </label>

              <input class='diassemana' type="checkbox" value="3" id="terca" />
              <label for="terca"> Terça </label>

              <input class='diassemana' type="checkbox" value="4" id="quarta" />
              <label for="quarta"> Quarta </label>

              <input class='diassemana' type="checkbox" value="5" id="quinta" />
              <label for="quinta"> Quinta </label>

              <input class='diassemana' type="checkbox" value="6" id="sexta" />
              <label for="sexta"> Sexta </label>

              <input class='diassemana' type="checkbox" value="7" id="sabado" />
              <label for="sabado"> Sábado </label>

            </td>
          </tr>
          <tr>
            <td nowrap='nowrap'>Hora inicial:</td>
            <td nowrap='nowrap'>
              <input type="text" value="" name="horaInicial" id="horaInicial" maxlength="5" onblur="js_validaHora24Horas(this, event);"/>
            </td>
            <td nowrap='nowrap' class="bold" >Hora Final:</td>
            <td nowrap='nowrap'>
              <input type="text" value="" name="horaFinal" id="horaFinal" maxlength="5" onblur=" validaPeriodoHora(event);" />
            </td>
          </tr>

          <tr>
            <td nowrap='nowrap'>Função:</td>
            <td nowrap='nowrap' colspan="3">
              <select id="atividade" >
                <option selected="selected" value="">Selecione</option>
              </select>
            </td>
          </tr>

          <tr>
            <td nowrap='nowrap'>Profissional/Monitor:</td>
            <td nowrap='nowrap' colspan="3">
              <input type="hidden" value="" id="iRecHumano" name="iRecHumano" />
              <input type="text"   value="" id="sNomeRechumano" name="sNomeRechumano" size="53" />
            </td>
          </tr>

          <tr>
            <td nowrap='nowrap'></td>
            <td nowrap='nowrap' colspan="3">
            </td>
          </tr>
        </table>

      </fieldset>

      <input type="button" id="salvar" name="salvar" value="Salvar">

    </form>
  </div>

  <div class="subcontainer">

    <fieldset style="min-width: 800px;">
      <legend>Profissionais/Monitores vinculados</legend>
      <div id="ctnGrid"></div>
    </fieldset>

  </div>


</body>
</html>
<script type="text/javascript">

  new MaskedInput("#horaInicial", "00:00", {placeholder:"0"});
  new MaskedInput("#horaFinal",   "00:00", {placeholder:"0"});

  const MSG_TURMAACHORARIO = 'educacao.escola.edu1_turmaachorarioprofissional001.'

  var oGet  = js_urlToObject();
  var oGrid = new DBGrid('gridProfissionais')
  oGrid.nameInstance = 'oGrid';
  oGrid.setCellAlign(['left', 'center', 'left', 'left', 'center']);
  oGrid.setCellWidth(['10%', '13%', '25%', '47%', '5%']);
  oGrid.setHeader(['Dia', 'Horário', 'Atividade', 'Profissional/Monitor', 'Ação']);
  oGrid.setHeight(150);
  oGrid.show($('ctnGrid'));


  ( function () {

    js_carregaProfissionaisVinculados();
    js_carregaAtividades();

  })();

  /**
   * Busca os profissionais vinculados a turma
   */
  function js_carregaProfissionaisVinculados() {

    var oParametros       = {};
    oParametros.sExecutar = 'getProfessoresVinculados';
    oParametros.iTurmaAc  = oGet.ed270_i_turmaac;

    var oRequest          = {};
    oRequest.asynchronous = false;
    oRequest.method       = 'post';
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete   = function( oAjax ) {

      js_removeObj('msgBoxA');
      var oRetorno = eval('(' + oAjax.responseText + ')');

      oGrid.clearAll(true);
      if (oRetorno.aVinculados.length == 0) {
        return;
      }

      oRetorno.aVinculados.each( function (oProfissional, i) {

        var oBtn   = new Element ('input', {'type': 'button', 'value':'E', 'rechumano':oProfissional.iRecHumano});
        oBtn.setAttribute("onclick", 'desvincluarProfissional('+ oProfissional.iCodigo + ')')

        var sHorario = oProfissional.sHoraInicial + ' às ' + oProfissional.sHoraFinal;
        var aLinha = [];
        aLinha.push(oProfissional.sDia.urlDecode());
        aLinha.push(sHorario);
        aLinha.push(oProfissional.sAtividade.urlDecode());
        aLinha.push(oProfissional.sRecHumano.urlDecode());
        aLinha.push(oBtn.outerHTML);

        oGrid.addRow( aLinha );

      });
      oGrid.renderRows();

    };

    js_divCarregando( _M( MSG_TURMAACHORARIO + "buscando_vinculados" ) , 'msgBoxA');
    new Ajax.Request('edu4_turmaac.RPC.php', oRequest);
  }

  function js_carregaAtividades() {

    var oParametros          = {};
    oParametros.sExecutar    = 'getAtividades';
    oParametros.iAtendimento = oGet.ed268_i_tipoatend;

    var oRequest          = {};
    oRequest.asynchronous = false;
    oRequest.method       = 'post';
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete   = function( oAjax ) {

      js_removeObj('msgBoxB');
      var oRetorno = eval('(' + oAjax.responseText + ')');



      if (oRetorno.aFuncoes.length == 0) {
        return;
      }
      $('atividade').options.length = 0;
      $('atividade').add( new Option("Selecione", '') );
      oRetorno.aFuncoes.each( function (oAtividade) {
        $('atividade').add( new Option(oAtividade.ed119_descricao.urlDecode(), oAtividade.ed119_sequencial) );
      });

    };

    js_divCarregando( _M( MSG_TURMAACHORARIO + "busca_atividades" ) , 'msgBoxB');
    new Ajax.Request('edu4_turmaac.RPC.php', oRequest);
  }


  $('atividade').observe('change', function() {

    $('iRecHumano').value     = '';
    $('sNomeRechumano').value = '';

    if ( $F('atividade') != '' ) {

      /**
       * Declaração do auto complete para Profissional
       */
      $('sNomeRechumano').onkeydown = '';
      var sRpcAutoComplete = 'edu4_pesquisarechumano.RPC.php?lFiltraAtividade=true&iAtividade='+$F('atividade');
      var oAutoComplete    = new dbAutoComplete( $('sNomeRechumano'), sRpcAutoComplete);
      oAutoComplete.setTxtFieldId( $('iRecHumano') );
      oAutoComplete.show();
      oAutoComplete.setMinLength(2);
    }
  });



  $('sNomeRechumano').observe( 'keyup', function() {

    if ($F('sNomeRechumano') == '') {
      $('iRecHumano').value = '';
    }

    if ( $F('atividade') == '') {

      $('sNomeRechumano').value = '';
      $('atividade').focus();
      alert( _M(MSG_TURMAACHORARIO + "selecione_atividade" ) );
      return;
    }

  });


  /**
   * Valida se a hora inicial é menor que a hora final
   * @param oEvent
   * @returns {boolean}
   */
  function validaPeriodoHora(oEvent) {

    if (!js_validaHora24Horas($('horaFinal'), oEvent)) {
      return
    }

    if ( empty($F('horaInicial')) ) {

      alert( _M(MSG_TURMAACHORARIO+ "hora_inicial_nao_informado") );

      oEvent.preventDefault();
      oEvent.stopPropagation();
      setTimeout(function(){
        $('horaInicial').focus();
      }, 10);
      return false;
    }

    var iHoraInicial    = $F('horaInicial').substr(0, 2);
    var iMinutosInicial = $F('horaInicial').substr(3, 2);
    var iHoraFinal      = $F('horaFinal').substr(0, 2);
    var iMinutosFinal   = $F('horaFinal').substr(3, 2);

    var oDataAtual   = new Date();
    var oHoraInicial = new Date(oDataAtual.getFullYear(), oDataAtual.getMonth(), oDataAtual.getDate(), iHoraInicial, iMinutosInicial );
    var oHoraFinal   = new Date(oDataAtual.getFullYear(), oDataAtual.getMonth(), oDataAtual.getDate(), iHoraFinal, iMinutosFinal );

    if (    (oHoraInicial.getHours() > oHoraFinal.getHours())
         || (oHoraInicial.getHours() == oHoraFinal.getHours() && oHoraInicial.getMinutes() > oHoraFinal.getMinutes()))  {

      alert( _M(MSG_TURMAACHORARIO + "conflito_entre_horas") );
      $('horaFinal').value = '00:00';
      oEvent.preventDefault();
      oEvent.stopPropagation();
      setTimeout(function(){
        $('horaFinal').focus();
      }, 10);
      return false;
    }

    return true;
  }

  function js_getDiasSemanaSelecionado() {

    var aDiasSemanaSelecionado = [];

    $$('.diassemana').each( function ( oElement ) {

      if (oElement.checked) {
        aDiasSemanaSelecionado.push(oElement.value);
      }
    });
    return aDiasSemanaSelecionado;
  };

  function js_validaCamposObrigatorios() {

    if ( empty( $F('horaInicial') ) ) {

      alert( _M(MSG_TURMAACHORARIO+ "hora_inicial_nao_informado") );
      return false;
    }

    if ( empty( $F('horaFinal') ) ) {

      alert( _M(MSG_TURMAACHORARIO+ "hora_final_nao_informado") );
      return false;
    }

    if ( empty( $F('iRecHumano') ) ) {

      alert( _M(MSG_TURMAACHORARIO+ "profissional_nao_informado") );
      return false;
    }

    return true;
  }

  $('salvar').observe( 'click', function() {

    var aDiasSemana = js_getDiasSemanaSelecionado();
    if ( empty(aDiasSemana) ) {

      alert( _M(MSG_TURMAACHORARIO+ "selecione_dia_semana") );
      return false;
    }

    if ( !js_validaCamposObrigatorios() ) {
      return false;
    }

    var oParametros              = {};
    oParametros.sExecutar        = 'vincularProfissional';
    oParametros.iTurmaAc         = oGet.ed270_i_turmaac;
    oParametros.iFuncaoAtividade = $F('atividade');
    oParametros.iRecHumano       = $F('iRecHumano');
    oParametros.aDiaSemana       = aDiasSemana;
    oParametros.sHoraInicial     = $F('horaInicial');
    oParametros.sHoraFinal       = $F('horaFinal');

    var oRequest          = {};
    oRequest.asynchronous = false;
    oRequest.method       = 'post';
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete   = function( oAjax ) {

      js_removeObj('msgBoxB');
      var oRetorno = eval('(' + oAjax.responseText + ')');

      alert(oRetorno.sMensagem.urlDecode());
      if ( parseInt(oRetorno.iStatus) == 2 ) {
        return false;
      }

      $('turmaAcHorario').reset();
      js_carregaProfissionaisVinculados();

    };

    js_divCarregando( _M( MSG_TURMAACHORARIO + "salvando" ) , 'msgBoxB');
    new Ajax.Request('edu4_turmaac.RPC.php', oRequest);

  });


  /**
   * Revome o profissional da TurmaAC
   * @param iCodigoVinculo
   */
  function desvincluarProfissional ( iCodigoVinculo ) {

    var oParametros       = {};
    oParametros.sExecutar = 'removerVinculo';
    oParametros.iVinculo  = iCodigoVinculo;

    var oRequest          = {};
    oRequest.asynchronous = false;
    oRequest.method       = 'post';
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete   = function( oAjax ) {

      js_removeObj('msgBoxB');
      var oRetorno = eval('(' + oAjax.responseText + ')');

      alert(oRetorno.sMensagem.urlDecode());
      if ( parseInt(oRetorno.iStatus) == 2 ) {
        return false;
      }
      js_carregaProfissionaisVinculados();
    };

    js_divCarregando( _M( MSG_TURMAACHORARIO + "removendo_vinculo" ) , 'msgBoxB');
    new Ajax.Request('edu4_turmaac.RPC.php', oRequest);
  }

</script>