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
$oRotulo->label("ed34_i_disciplina");
$oRotulo->label("ed232_c_descr");
$oRotulo->label("ed34_i_qtdperiodo");

?>
<div id="ctnAbas"></div>

<div id="ctnDisciplinaEtapa" class="container">
</div>

<div class="container">

  <form name="form1" action="" method="post">

    <fieldset>
      <legend id="legendDisicplina"></legend>

      <table class="form-container">

        <tr>
          <td nowrap='nowrap'>
            <label for="ed34_i_disciplina">
              <?php
              db_ancora($Led34_i_disciplina,"js_buscaDisciplina(true);", 1);
              ?>
            </label>
          </td>
          <td nowrap='nowrap' colspan="3">
            <?php
              db_input('ed34_i_disciplina', 10, $Ied34_i_disciplina, true, 'text', 1, " onchange='js_buscaDisciplina(false);'");
              db_input('ed232_c_descr',     40, $Ied232_c_descr,     true, 'text', 1, '');
            ?>
          </td>
        </tr>


        <tr id="ctnDisciplinaGlobalBase">
          <td nowrap='nowrap'>
            <label for="disciplinaGlobal">Disciplina Global:</label>
          </td>
          <td nowrap='nowrap' colspan="3">
            <select id="disciplinaGlobal" onchange="validaDisciplinaGlobal()">
              <option value="N" selected="selected">Não</option>
              <option value="S">Sim</option>
            </select>
          </td>
        </tr>

        <tr id="ctnDisciplinaGlobalTurma">
          <td nowrap='nowrap'>
            <label for="tipoControleFrequencia">Controle de Frequência:</label>
          </td>
          <td nowrap='nowrap' colspan="3">
            <select id="tipoControleFrequencia">
              <option value="A" selected = "selected">SOMENTE AVALIAÇÃO</option>
              <option value="F">GLOBALIZADA (F)</option>
              <option value="FA">GLOBALIZADA (FA)</option>
            </select>
          </td>
        </tr>

        <tr>
          <td nowrap='nowrap'>
            <label for="tipoBase">Tipo de Base:</label>
          </td>
          <td nowrap='nowrap'>
            <select id="tipoBase">
              <option value="C" selected="selected">Comum</option>
              <option value="D">Diversificada</option>
            </select>
          </td>

          <td nowrap='nowrap'>
            <label for="caraterReprobatorio">Caráter Reprobatório:</label>
          </td>
          <td nowrap='nowrap'>
            <select id="caraterReprobatorio">
              <option value="S" selected="selected">Possui</option>
              <option value="N">Não Possui</option>
            </select>
          </td>
        </tr>

        <tr>
          <td nowrap='nowrap'>
            <label for="horasAula">Quantidade de horas-aula:</label>
          </td>
          <td nowrap='nowrap'>
            <?php
              db_input('horasAula', 10, $Ied34_i_qtdperiodo, true, 'text', 1, '');
            ?>
          </td>
        </tr>

        <tr>
          <td nowrap='nowrap'>
            <label for="tipoMatricula">Matrícula:</label>
          </td>
          <td nowrap='nowrap'>
            <select id="tipoMatricula" onchange="js_validaTipoMatricula()">
              <option value="OB" selected="selected">Obrigatória</option>
              <option value="OP">Opcional</option>
            </select>
          </td>

          <td nowrap='nowrap'>
            <label for="lancarDocumentacao">Lançar na Documentação:</label>
          </td>
          <td nowrap='nowrap'>
            <select id="lancarDocumentacao" disabled="disabled">
              <option value="S" selected="selected">Sim</option>
              <option value="N">Não</option>
            </select>
          </td>
        </tr>

      </table>
    </fieldset>
    <input type="hidden" name="iEtapaAtual"    id="iEtapaAtual"      />
    <input type="hidden" name="iCodigoVinculo" id="iCodigoVinculo"   />
    <input type="hidden" name="iTurma"         id="iTurma"           />
    <input type="button" name="salvar"         id="salvar"        value="Salvar"              onclick="js_salvar();" />
    <input type="button" name="atualizarBase"  id="atualizarBase" value="Atualizar pela base" style="display: none;" />
    <input type="button" name="cancelar"       id="cancelar"      value="Cancelar"            disabled="disabled" onclick="js_cancelar();" />
    <input type="button" name="ordenar"        id="ordenar"       value="Ordenar" />
  </form>
</div>

<div class="subcontainer">
  <fieldset style="width: 1200px;">
    <legend>Disciplinas</legend>

    <div id="ctnGridDisciplinas"></div>
  </fieldset>
</div>

<script type="text/javascript">

  const MSG_DB_FRMDISCIPLINAETAPA = "educacao.escola.db_frmdisciplinaetapa.";

  var oGet = js_urlToObject();
  var sRpc = 'edu4_vinculodisciplinaetapa.RPC.php'

  /**
   * Array com os dados das disciplinas da etapa selencionada
   * @var Array
   */
  var aDisciplinasEtapa  = [];

  /**
   * Array com o código das disciplinas inclusas na etapa
   */
  var aCodigoDisciplinas = [];

  /**
   * Verifica se estamos acessando o cadastro pela rotina de :
   *  - Cadastro > Bases Currículares
   *  - Cadastro > Turmas
   */
  var lVisaoCadastroBase          = oGet.cadastroBase == 'S';
  $('legendDisicplina').innerHTML = "Disciplina da base curricular: " + oGet.sBase;
  if (!lVisaoCadastroBase) {
    $('legendDisicplina').innerHTML = "Disciplina da base curricular: " + oGet.sTurma;
  }

  /**
   * Função de carga inicial
   */
  ( function() {

    if ( lVisaoCadastroBase ) {

      $('ctnDisciplinaGlobalTurma').style.display = 'none';
      if (oGet.sDisciplinaGlobal == 'N') {
        $('ctnDisciplinaGlobalBase').style.display = 'none';
      }
    } else {

      $('atualizarBase').style.display = '';
      $('iTurma').value                = oGet.iTurma;
        $('ctnDisciplinaGlobalBase').style.display = 'none';
      if (oGet.sDisciplinaGlobal == 'N') {
        $('ctnDisciplinaGlobalTurma').style.display = 'none';
      }
    }

  })();


  var sDescricaoColunaGrid = 'Contr. Frequência';
  if (lVisaoCadastroBase) {
    sDescricaoColunaGrid = 'Disciplina Global';
  }

  /**
   * Grid disciplinas
   * @type {DBGrid}
   */
  var oGridDisciplina = new DBGrid('ctnGridDisciplinas');
  var aHeadersGrid    = new Array();
  var aCellWidthGrid  = new Array();
  var aCellAlign      = new Array();

  if ( lVisaoCadastroBase ) {

    aHeadersGrid.push( "Tipo Base",
                       "Disciplina",
                       sDescricaoColunaGrid,
                       "Horas-Aula",
                       "Matrícula",
                       "Caráter Reprobatório",
                       "Documentação",
                       "Ação");
    aCellWidthGrid.push("10%", "27%", "12%", "8%", "7%", "12%", "10%", "9%");
    aCellAlign.push("left", "left", "left", "center", "left", "left", "left", "center");
  } else {

    aHeadersGrid.push( "Tipo Base",
                       "Disciplina",
                       sDescricaoColunaGrid,
                       "Horas-Aula",
                       "Matrícula",
                       "Caráter Reprobatório",
                       "Documentação",
                       "Proc. Avaliação",
                       "Ação");
    aCellWidthGrid.push("10%", "20%", "12%", "8%", "7%", "12%", "8%", "17%", "6%");
    aCellAlign.push("left", "left", "left", "center", "left", "left", "left", "left", "center")
  }

  oGridDisciplina.nameInstance = 'oGridDisciplina';
  oGridDisciplina.setCellWidth(aCellWidthGrid);
  oGridDisciplina.setCellAlign(aCellAlign);
  oGridDisciplina.setHeader(aHeadersGrid);
  oGridDisciplina.setHeight(130);
  oGridDisciplina.show($('ctnGridDisciplinas'));

  var oDisiciplinaEtapa = new DBViewDisciplinasEtapa(oGet.iBase, oGet.iTurma, lVisaoCadastroBase, oGet.lModuloEscola);

  function js_buscaDisciplina(lMostra) {

    var sUrl  = 'func_disciplina.php';
        sUrl += '?curso='+oGet.iCurso;

    var sFiltroDisciplina = '&disciplinas=0';
    if ( aCodigoDisciplinas.length > 0) {
      sFiltroDisciplina = '&disciplinas=' + aCodigoDisciplinas.implode(',');
    }
    sUrl += sFiltroDisciplina;
    if (lMostra) {

      sUrl += '&funcao_js=parent.js_mostraDisciplina|ed12_i_codigo|ed232_c_descr';
      js_OpenJanelaIframe('', 'db_iframe_disciplina', sUrl, 'Pesquisa de Disciplinas', true);
    } else if ($F('ed34_i_disciplina')) {

      sUrl += '&pesquisa_chave='+$F('ed34_i_disciplina');
      sUrl += '&funcao_js=parent.js_mostraDisciplina';
      js_OpenJanelaIframe('', 'db_iframe_disciplina', sUrl, 'Pesquisa de Disciplinas', false);
    } else{
      $('ed232_c_descr').value = '';
    }

  }

  function js_mostraDisciplina() {

    if ( typeof arguments[1] == 'boolean' ) {

      $('ed232_c_descr').value = arguments[0];
      if (arguments[1]){
        $('ed34_i_disciplina').value = '';
      }
    } else {
      $('ed34_i_disciplina').value = arguments[0];
      $('ed232_c_descr').value     = arguments[1];
    }
    db_iframe_disciplina.hide();
  }


  function buscaDisciplinasEtapa(iEtapa) {

    oGridDisciplina.clearAll(true);
    var oParametros    = {};
    oParametros.exec   = "getDisciplinasVinculadasEtapaTurma";
    if (lVisaoCadastroBase) {
      oParametros.exec   = "getDisciplinasVinculadasEtapaBase";
    }
    oParametros.iBase  = oGet.iBase;
    oParametros.iTurma = oGet.iTurma;
    oParametros.iEtapa = iEtapa;

    var oRequest          = {};
    oRequest.asynchronous = false;
    oRequest.method       = 'post';
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete   = function(oAjax) {

      js_removeObj("msgBoxB");
      var oRetorno = eval ( "(" + oAjax.responseText + ")" );

      if ( parseInt(oRetorno.sStatus) == 2) {

        alert(oRetorno.sMessage.urlDecode());
        return;
      }

      /**
       * Estrutura com os tipos de constrole de frequência para Base e Turma
       */
      var aTiposControleFrequencia = {'S':'SIM', 'N':'NÃO', 'F':'GLOBALIZADA (F)',
                                      'FA': 'GLOBALIZADA (FA)', 'A': 'SOMENTE AVALIAÇÃO', 'I': 'INDIVIDUAL'};

      $('disciplinaGlobal').removeAttribute('disabled');
      $('tipoBase').removeAttribute( "disabled" );
      $('tipoMatricula').removeAttribute( "disabled" );
      if (oRetorno.lTemDisciplinaGlobalizada) {

        $('tipoControleFrequencia').value = 'A';
        $('disciplinaGlobal').value       = 'N';

        $('tipoControleFrequencia').setAttribute('disabled', 'disabled');
        $('disciplinaGlobal').setAttribute('disabled', 'disabled');
      }

      aCodigoDisciplinas = [];
      oRetorno.aDisciplinas.each( function (oDisciplina, id) {

        var sControleFrequencia = '';
        sControleFrequencia     = aTiposControleFrequencia[oDisciplina.sTipoControleFrequencia];
        if (lVisaoCadastroBase) {

          var sTipo = oDisciplina.lGlobalizada ? 'S' : 'N';
          sControleFrequencia = aTiposControleFrequencia[sTipo];
        }

        var oBtnExcluir = new Element('input', {'type':'button', 'value':'E' , 'id':'excluir'+oDisciplina.iCodigo});
        var oBtnAlterar = new Element('input', {'type':'button', 'value':'A' , 'id':'excluir'+oDisciplina.iCodigo});

        oBtnExcluir.setAttribute('onclick', 'js_excluirDisciplina('+oDisciplina.iCodigo+', '+oDisciplina.lEncerrada+')');
        oBtnAlterar.setAttribute('onclick', 'js_alterarDisciplina('+oDisciplina.iCodigo+')');

        var sTipoBase             = oDisciplina.lBaseComum ? 'Base Comum' : 'Base Diversificada';
        var sTipoMatricula        = oDisciplina.lObrigatoria ? 'Obrigatória' : 'Opcional';
        var sCaracterReprobatorio = oDisciplina.lCaracterReprobatorio ? 'Possui' : 'Não Possui';
        var sDocumentacao         = oDisciplina.lLancarDocumentacao ? 'Lançar' : 'Não Lançar';
        var aLinha                = [];
        aLinha.push(sTipoBase);
        aLinha.push(oDisciplina.sDisciplina.urlDecode());
        aLinha.push(sControleFrequencia);
        aLinha.push(oDisciplina.iQtdPeriodo);
        aLinha.push(sTipoMatricula);
        aLinha.push(sCaracterReprobatorio);
        aLinha.push(sDocumentacao);

        if ( !lVisaoCadastroBase ) {
          aLinha.push(oDisciplina.sProcedimentoAvalicao.urlDecode());
        }

        aLinha.push( oBtnAlterar.outerHTML + ' ' + oBtnExcluir.outerHTML);

        aCodigoDisciplinas.push(oDisciplina.iDisicplina);
        oGridDisciplina.addRow(aLinha);

        oGridDisciplina.aRows[id].sEvents += "onmouseover='js_sinalizarLinhaGrid(this, true);'";
        oGridDisciplina.aRows[id].sEvents += "onmouseout='js_sinalizarLinhaGrid(this, false);'";

      });
      aDisciplinasEtapa = oRetorno.aDisciplinas;
      oGridDisciplina.renderRows();
      js_autocompleteDisciplina();
    }

    js_divCarregando( _M(MSG_DB_FRMDISCIPLINAETAPA+"verificando_disciplinas"), "msgBoxB" );
    new Ajax.Request(sRpc, oRequest);
  }

  /**
   * Remove a disciplina da base/turma
   * @param iCodigoVinculo
   */
  function js_excluirDisciplina(iCodigoVinculo, lEncerrada) {

    var sMsgConfirm = _M(MSG_DB_FRMDISCIPLINAETAPA+'confirma_exclusao');

    if (!lVisaoCadastroBase) {

      sMsgConfirm = _M(MSG_DB_FRMDISCIPLINAETAPA+'confirma_exclusao_turma');
      if (lEncerrada) {

        alert(_M(MSG_DB_FRMDISCIPLINAETAPA+'disciplina_encerrada'));
        return false;
      }
    }
    if ( !confirm( sMsgConfirm ) ) {
      return false;
    }

    var oParametros    = {};
    oParametros.exec   = "excluirDisciplinaTurma";
    if (lVisaoCadastroBase) {
      oParametros.exec = "excluirDisciplinaBase";
    }
    oParametros.iCodigoVinculo = iCodigoVinculo
    oParametros.iBase          = oGet.iBase;
    oParametros.iTurma         = $F('iTurma');

    var oRequest          = {};
    oRequest.asynchronous = false;
    oRequest.method       = 'post';
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete   = function(oAjax) {

      js_removeObj("msgBox");
      var oRetorno = eval ( "(" + oAjax.responseText + ")" );

      alert(oRetorno.sMessage.urlDecode());
      if ( parseInt(oRetorno.sStatus) == 2) {
        return;
      }
      document.form1.reset();
      buscaDisciplinasEtapa($F('iEtapaAtual'));
    };

    js_divCarregando( _M(MSG_DB_FRMDISCIPLINAETAPA+"excluir_vinculo"), "msgBox" );
    new Ajax.Request(sRpc, oRequest);
  }

  function js_alterarDisciplina(iCodigoVinculo) {

    aDisciplinasEtapa.each ( function (oDisciplina) {

      if (oDisciplina.iCodigo != iCodigoVinculo) {
        return;
      }

      $('tipoControleFrequencia').options[0].removeAttribute('disabled');
      if ( oDisciplina.lGlobalizada ){
        $('tipoControleFrequencia').options[0].setAttribute('disabled', 'disabled');
      }

      $('iCodigoVinculo').value         = iCodigoVinculo;
      $('ed34_i_disciplina').value      = oDisciplina.iDisicplina;
      $('ed232_c_descr').value          = oDisciplina.sDisciplina.urlDecode();
      $('tipoBase').value               = oDisciplina.lBaseComum ? 'C' : 'D';
      $('caraterReprobatorio').value    = oDisciplina.lCaracterReprobatorio ? 'S' : 'N';
      $('horasAula').value              = oDisciplina.iQtdPeriodo
      $('tipoMatricula').value          = oDisciplina.lObrigatoria ? 'OB' : 'OP';
      $('lancarDocumentacao').value     = oDisciplina.lLancarDocumentacao ? 'S' : 'N';
      $('disciplinaGlobal').value       = oDisciplina.lGlobalizada ? 'S' : 'N';
      $('tipoControleFrequencia').value = oDisciplina.sTipoControleFrequencia;

    });

    validaDisciplinaGlobal();
    $('cancelar').removeAttribute('disabled');
  }


  var sMsgConfirmReplicarDisciplina = _M(MSG_DB_FRMDISCIPLINAETAPA+"deseja_replicar");

  /**
   * Salva a disciplina na Base / Turma
   */
  function js_salvar() {

    var oParametros    = {};
    oParametros.exec   = "salvarDisciplinaTurma";
    if (lVisaoCadastroBase) {
      oParametros.exec = "salvarDisciplinaBase";
    }

    if( !js_validaDados() ) {
      return;
    }

    /**
     * Variável resrevada para turma
     */
    var sTipoControleFrequencia = 'I';
    if (oGet.sDisciplinaGlobal == 'S') {
      sTipoControleFrequencia = $F('tipoControleFrequencia');
    }

    oParametros.iBase                   = oGet.iBase;
    oParametros.iTurma                  = $F('iTurma');
    oParametros.iEtapa                  = $F('iEtapaAtual');
    oParametros.iCodigoVinculo          = $F('iCodigoVinculo');
    oParametros.iDisicplina             = $F('ed34_i_disciplina');
    oParametros.iQtdPeriodo             = $F('horasAula');
    oParametros.lObrigatoria            = $F('tipoMatricula')       == 'OB';
    oParametros.lLancarDocumentacao     = $F('lancarDocumentacao')  == 'S';
    oParametros.lCaracterReprobatorio   = $F('caraterReprobatorio') == 'S';
    oParametros.lBaseComum              = $F('tipoBase')            == 'C';
    oParametros.lGlobalizada            = $F('disciplinaGlobal')    == 'S';
    oParametros.sTipoControleFrequencia = sTipoControleFrequencia;

    var oRequest          = {};
    oRequest.asynchronous = false;
    oRequest.method       = 'post';
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete   = function(oAjax) {

      js_removeObj("msgBox");
      var oRetorno = eval ( "(" + oAjax.responseText + ")" );

      if ( parseInt(oRetorno.iStatus) == 2) {

        alert(oRetorno.sMessage.urlDecode());
        return;
      }

      /**
       * Antes estava somente replicando para inclusao, mais Tiago solicitou que as alterações também se replicassem
       */
      if (oDisiciplinaEtapa.aEtapas.length > 1) {

        var sMsgConfirm  = oRetorno.sMessage.urlDecode() + "\n";
            sMsgConfirm += sMsgConfirmReplicarDisciplina
        if (lVisaoCadastroBase && oParametros.lGlobalizada) {
          sMsgConfirm += "\n" + _M( MSG_DB_FRMDISCIPLINAETAPA+"aviso_disciplina_globalizada" );
        }

        if (confirm(sMsgConfirm)) {

          oParametros.sDisciplina = $F('ed232_c_descr');
          oDisiciplinaEtapa.replicarDisciplinaEtapas(oParametros);
        }
      }

      document.form1.reset();

      $('cancelar').setAttribute('disabled', 'disabled');
      $('iCodigoVinculo').value = '';
      buscaDisciplinasEtapa($F('iEtapaAtual'));
      validaDisciplinaGlobal();

    };

    js_divCarregando( _M(MSG_DB_FRMDISCIPLINAETAPA+"salvando_vinculo"), "msgBox" );
    new Ajax.Request(sRpc, oRequest);
  }

  /**
   * Valida informações obrigatória do formulário antes de salvar
   */
  function js_validaDados() {

    if ( $F('ed34_i_disciplina') == '' ) {

      alert(_M(MSG_DB_FRMDISCIPLINAETAPA+"informe_disciplina"));
      return false;
    }

    if ( $F('horasAula') == '' ) {

      alert(_M(MSG_DB_FRMDISCIPLINAETAPA+"informe_hora_aula"));
      return false;
    }

    return true;

  }

  /**
   * Altera o estado do combobox
   */
  function js_validaTipoMatricula() {

    /**
     * Atenção deixar no else o código, pois dependendo de como definimos o valor do tipo da matricula, não podemos
     * atribuir o value 'S'
     */
    if ( $F('tipoMatricula') == 'OP' ) {
      $('lancarDocumentacao').removeAttribute('disabled');
    } else {

      $('lancarDocumentacao').setAttribute('disabled', 'disabled');
      $('lancarDocumentacao').value = 'S';
    }
  }

  function js_cancelar() {

    document.form1.reset();
    $('cancelar').setAttribute('disabled', 'disabled');
    $('iCodigoVinculo').value     = '';
    $('lancarDocumentacao').value = 'S';
    $('tipoBase').removeAttribute( "disabled" );
    $('tipoMatricula').removeAttribute( "disabled" );
    $('lancarDocumentacao').setAttribute('disabled', 'disabled');
  }


  function js_sinalizarLinhaGrid (oObjeto, lPintar) {

    if (oObjeto.nodeName == 'TR') {
      oLinha = oObjeto;
    }
    if (oObjeto.nodeName == 'INPUT') {
      oLinha = oObjeto.parentNode.parentNode;
    }
    var sCor      = 'white';
    var sCorFonte = 'black';
    if (lPintar) {
      sCor       = 'rgb(240, 240, 240)';
    }
    oLinha.style.backgroundColor = sCor;
    oLinha.style.color           = sCorFonte;
  }

  /**
   * Atualiza as disciplinas da turma com a base curricular
   */
  $('atualizarBase').observe( 'click', function() {

    if ( !confirm( _M(MSG_DB_FRMDISCIPLINAETAPA+"confirma_atualizar_base", {'sNomeBase' : oGet.sBase}) ) ) {
      return;
    }

    var oParametros    = {};
    oParametros.exec   = 'atualizarBase';
    oParametros.iTurma = $F('iTurma');
    oParametros.iEtapa = $F('iEtapaAtual');
    oParametros.iBase  = oGet.iBase;

    var oRequest          = {};
    oRequest.asynchronous = false;
    oRequest.method       = 'post';
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete   = function(oAjax) {

      js_removeObj("msgBox");
      var oRetorno = eval( '(' + oAjax.responseText + ')');

      alert(oRetorno.sMessage.urlDecode());
      if (parseInt(oRetorno.sStatus) == 2 ){
        return;
      }

      document.form1.reset();
      buscaDisciplinasEtapa($F('iEtapaAtual'));
    };

    js_divCarregando( _M(MSG_DB_FRMDISCIPLINAETAPA+"atualizar_base"), "msgBox" );
    new Ajax.Request(sRpc, oRequest);
  });


  function js_autocompleteDisciplina() {

    /**
     * Função AutoComplete
     */
    var sUrlAutoComplete  = 'edu4_disciplinaautocomplete.RPC.php?iBase='+oGet.iBase;
    sUrlAutoComplete += '&iCurso='+oGet.iCurso+'&iEtapa='+$F('iEtapaAtual')+'&iTurma='+$F('iTurma');
    sUrlAutoComplete += '&sFiltroExclusive=turma';

    if (lVisaoCadastroBase) {
      sUrlAutoComplete += '&sFiltroExclusive=base';
    }

    $('ed232_c_descr').onkeydown = '';
    var oDisciplinaAutoComplete = new dbAutoComplete( $('ed232_c_descr'), sUrlAutoComplete );
    oDisciplinaAutoComplete.setTxtFieldId( $('ed232_c_descr') );
    oDisciplinaAutoComplete.setHeightList(300);
    oDisciplinaAutoComplete.show();
    oDisciplinaAutoComplete.setCallBackFunction( function(id, label) {

      $('ed34_i_disciplina').value = id;
      $('ed232_c_descr').value     = label;
    });
  }

  js_autocompleteDisciplina();


  $('ordenar').observe('click', function() {

    var oWindow = new windowAux("wndOrdenarDisciplina", "Ordenar disciplinas", 500, 500);

    var sConteudo  = " <div > ";
        sConteudo += "   <table style='width: 450px;'> ";

        sConteudo += "     <tr id='cntOrderBaseComum' style='display: none;'> ";
        sConteudo += "       <td> ";
        sConteudo += "         <fieldset > ";
        sConteudo += "           <legend>Base Comum</legend>";
        sConteudo += "           <div id='cntGridDisciplinasBaseComum'> </div>";
        sConteudo += "         </fieldset> ";
        sConteudo += "       </td> ";
        sConteudo += "       <td> ";
        sConteudo += "         <input type='button' id='btnMoveUpComum'  value='^'>";
        sConteudo += "         <br>";
        sConteudo += "         <input type='button' id='btnMoveDownComum' value='v'>";
        sConteudo += "       </td> ";
        sConteudo += "     </tr> ";

        sConteudo += "     <tr id='cntOrderBaseDiversificada' style='display: none;'> ";
        sConteudo += "       <td> ";
        sConteudo += "         <fieldset > ";
        sConteudo += "           <legend>Base Diversificada</legend>";
        sConteudo += "           <div id='cntGridDisciplinasBaseDiversificada'> </div>";
        sConteudo += "         </fieldset> ";
        sConteudo += "       </td> ";
        sConteudo += "       <td> ";
        sConteudo += "         <input type='button' id='btnMoveUpDiversificada'  value='^'>";
        sConteudo += "         <br>";
        sConteudo += "         <input type='button' id='btnMoveDownDiversificada' value='v'>";
        sConteudo += "       </td> ";
        sConteudo += "     </tr> ";

        sConteudo += "   </table> ";
        sConteudo += "   <center><input type='button' name='ordenarDisciplinas' value='Salvar' id='btnOrdenarDisciplinas'  /></center>";
        sConteudo += " </div> ";

    oWindow.setShutDownFunction( function() {
      oWindow.destroy();
    });

    var sMsg        = 'Ordenar disciplinas';
    var sHelpMsgBox = 'Selecione a disciplina na grade e utilize os botões para ordenar as disciplinas.';

    oWindow.setContent(sConteudo);
    oMessageBoard = new DBMessageBoard('msgBoardReplica', sMsg, sHelpMsgBox, oWindow.getContentContainer());
    oWindow.show();

    $('cntOrderBaseComum').style.display = 'table-row';
    $('cntOrderBaseComum').style.width   = '100%';

    var aDisciplinasDiversificadas = [];
    oDisciplinasComum.show($('cntGridDisciplinasBaseComum'));
    oDisciplinasComum.clearAll(true);
    aDisciplinasEtapa.each (function (oDisciplina) {

      if (oDisciplina.lBaseComum) {

        var aLinha = [];
        aLinha.push(oDisciplina.sDisciplina.urlDecode());
        aLinha.push(oDisciplina.iCodigo);
        oDisciplinasComum.addRow(aLinha);
      } else {
        aDisciplinasDiversificadas.push(oDisciplina);
      }
    });
    oDisciplinasComum.renderRows();
    oDisciplinasComum.enableOrderRows({btnMoveUp:$('btnMoveUpComum'), btnMoveDown:$('btnMoveDownComum')});

    if (aDisciplinasDiversificadas.length > 0) {

      $('cntOrderBaseDiversificada').style.display = 'table-row';

      oDisciplinasDiversificada.show($('cntGridDisciplinasBaseDiversificada'));
      oDisciplinasDiversificada.clearAll(true);
      aDisciplinasDiversificadas.each(function (oDisciplina) {

        var aLinha = [];
        aLinha.push(oDisciplina.sDisciplina.urlDecode());
        aLinha.push(oDisciplina.iCodigo);
        oDisciplinasDiversificada.addRow(aLinha);
      });
      oDisciplinasDiversificada.renderRows();
      oDisciplinasDiversificada.enableOrderRows({btnMoveUp:$('btnMoveUpDiversificada'), btnMoveDown:$('btnMoveDownDiversificada')});
    }

    $('btnOrdenarDisciplinas').observe('click', function() {

      var aDisciplinasComumOrdenada         = [];
      var aDisciplinasDiversificadaOrdenada = [];
      oDisciplinasComum.aRows.each(function(aRow, iSeq) {
        aDisciplinasComumOrdenada.push({ iCodigo : aRow.aCells[1].getValue(), iOrdem :  iSeq+1});
      });

      if ( oDisciplinasDiversificada.aRows.length > 0 ) {

        oDisciplinasDiversificada.aRows.each(function(aRow, iSeq) {
          aDisciplinasDiversificadaOrdenada.push({ iCodigo : aRow.aCells[1].getValue(), iOrdem :  iSeq+1});
        });
      }

      var oParametros  = {};
      oParametros.exec = 'reordenarDisciplinasTurma';

      if (lVisaoCadastroBase) {
        oParametros.exec = 'reordenarDisciplinasBase';
      }
      oParametros.aDisciplinasComumOrdenada         = aDisciplinasComumOrdenada;
      oParametros.aDisciplinasDiversificadaOrdenada = aDisciplinasDiversificadaOrdenada;

      var oRequest = {};
      oRequest.asynchronous = false;
      oRequest.method       = 'post';
      oRequest.parameters   = 'json='+Object.toJSON(oParametros);
      oRequest.onComplete   = function(oAjax) {

        js_removeObj("msgBox");
        var oRetorno = eval( '(' + oAjax.responseText + ')');

        alert(oRetorno.sMessage.urlDecode());
        if (parseInt(oRetorno.sStatus) == 2 ){
          return;
        }

        oWindow.destroy();
        buscaDisciplinasEtapa( $F('iEtapaAtual') );
      };

      js_divCarregando( _M(MSG_DB_FRMDISCIPLINAETAPA+"salvando_ordenacao"), "msgBox" );
      new Ajax.Request(sRpc, oRequest);

    });

  });

  /**
   * Grid com as disciplinas de base comum
   * @type {DBGrid}
   */
    var oDisciplinasComum          = new DBGrid('gridDisciplinaComum');
    oDisciplinasComum.nameInstance = 'oDisciplinasComum';
    oDisciplinasComum.setHeader( ["Disciplina", "Código"] );
    oDisciplinasComum.aHeaders[1].lDisplayed = false;
    oDisciplinasComum.setHeight(100);


  /**
   * Grid com as disciplinas de base diversificada
   * @type {DBGrid}
   */
    var oDisciplinasDiversificada          = new DBGrid('gridDisciplinaDiversificada');
    oDisciplinasDiversificada.nameInstance = 'oDisciplinasDiversificada';
    oDisciplinasDiversificada.setHeader( ["Disciplina", "Código"]);
    oDisciplinasDiversificada.aHeaders[1].lDisplayed = false;
    oDisciplinasDiversificada.setHeight(100);

  /**
   * Valida para desabilitar campos Tipo de Base e Matrícula quando Disciplina Global for SIM
   */
  function validaDisciplinaGlobal() {

    if ( $F('disciplinaGlobal') == "S") {

      $('tipoBase').value = 'C';
      $('tipoBase').setAttribute('disabled', 'disabled');
      $('tipoMatricula').value = 'OB';
      $('tipoMatricula').setAttribute('disabled', 'disabled');

    } else {

      $('tipoBase').removeAttribute('disabled');
      $('tipoMatricula').removeAttribute('disabled');
    }

    js_validaTipoMatricula();
  }

$('ed34_i_disciplina').className   = 'field-size2';
$('ed232_c_descr').className       = 'field-size7';
$('tipoBase').className            = 'field-size-max';
$('caraterReprobatorio').className = 'field-size-max';
$('horasAula').className           = 'field-size2';
$('tipoMatricula').className       = 'field-size-max';
$('lancarDocumentacao').className  = 'field-size-max';
</script>