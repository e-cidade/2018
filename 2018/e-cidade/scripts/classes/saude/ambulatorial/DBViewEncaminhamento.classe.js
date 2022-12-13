/**
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 *
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */

const MENSAGENS_DBVIEWENCAMINHAMENTO = 'saude.ambulatorial.DBViewEncaminhamento.';
/**
 * View para encaminhamento de prontuários a outros setores do ambulatorio
 *
 *  @param {int} iLocalOrigem Local de origem onde o componente foi chamado...  Usar as constantes da classe.
 *                           DBViewEncaminhamento.RECEPCAO
 *                           DBViewEncaminhamento.TRIAGEM
 *                           DBViewEncaminhamento.CONSULTA_MEDICA
 *
 * @param {int} iProntuario  Prontuário (FAA) do paciente
 *
 * @example var oTeste = new DBViewEncaminhamento(DBViewEncaminhamento.RECEPCAO, 19);
 *          oTeste.show();
 *
 */
DBViewEncaminhamento = function ( iLocalOrigem, iProntuario ) {

  this.iLocalOrigem = iLocalOrigem;
  this.iProntuario  = iProntuario;
  this.sRpc         = 'sau4_fichaatendimento.RPC.php';

  this.fCallbackFechar = function() {
    return true;
  };

  this.fCallbackSalvar = function() {
    return true;
  };

  /**
   * Select com os setores disponiveis para encaminhamento
   * @type {HTMLSelectElement}
   */
  this.oCboSetores    = document.createElement('select');
  this.oCboSetores.id = 'setorEncaminhamento';
  this.oCboSetores.addClassName( 'field-size-max' );

  var oLegendContainerForm       = document.createElement('legend');
  oLegendContainerForm.innerHTML = "Encaminhamento";

  var oFieldsetContainerForm     = document.createElement('fieldset');
  oFieldsetContainerForm.appendChild(oLegendContainerForm);

  var oForm  = document.createElement("form");
  oForm.addClassName( 'form-container' );

  /**
   * CampoObservação
   * @type {HTMLTextAreaElement}
   */
  this.oInputObservacao  = document.createElement("textarea");
  this.oInputObservacao.id   = 'observacaoEncaminhamento';
  this.oInputObservacao.rows = 3;
  this.oInputObservacao.cols = 80;

  var oLegendaobservacao = document.createElement("legend");
  oLegendaobservacao.innerHTML = "Observação";

  var oFieldsetObservacao = document.createElement("fieldset");
  oFieldsetObservacao.addClassName('separator');
  oFieldsetObservacao.appendChild( oLegendaobservacao );
  oFieldsetObservacao.appendChild( this.oInputObservacao );

  var oLabelSetor          = document.createElement("label");
  oLabelSetor.setAttribute('for', 'setorEncaminhamento');
  oLabelSetor.addClassName('bold');
  oLabelSetor.innerHTML    = "Setor:";

  /**
   * Cria a tabela do formulário
   */
  var oTabela = document.createElement("table");
  var oLinha1 = oTabela.insertRow(0);
  var oLinha2 = oTabela.insertRow(1);
  var oLinha3 = oTabela.insertRow(2);
  var oLinha4 = oTabela.insertRow(3);

  oLinha1.insertCell(0).addClassName('field-size3').appendChild(oLabelSetor);
  oLinha1.insertCell(1).appendChild(this.oCboSetores);

  var oCelulaObservacao = oLinha4.insertCell(0);
  oCelulaObservacao.setAttribute('colspan', 2);
  oCelulaObservacao.appendChild(oFieldsetObservacao);

  oForm.appendChild(oTabela);
  oFieldsetContainerForm.appendChild(oForm);

  var oBotaoSalvar    = document.createElement("input");
  oBotaoSalvar.type   = "button";
  oBotaoSalvar.id     = "btnSalvarEncaminhamento";
  oBotaoSalvar.value  = "Salvar";

  /**
   * Container com todos elementos do formulário
   * @type {HTMLDivElement}
   */
  this.oDivContainer = document.createElement('div');
  this.oDivContainer.addClassName('container');
  this.oDivContainer.appendChild( oFieldsetContainerForm );
  this.oDivContainer.appendChild( oBotaoSalvar );


  /**
   * campos referentens ao encaminhamento do profissional
   */
  oLinha2.id = 'linha-profissional';
  oLinha3.id = 'linha-especialidade';
  oLinha2.style.display = "none";
  oLinha3.style.display = "none";

  oLinha2.addClassName('encaminhar-profissional');
  oLinha3.addClassName('encaminhar-profissional');

  // cria os links (ancora)
  this.oAncoraProfissional  = new Element('a', {href:'', id:'ancora-profissional'}).update('Profissional:');
  this.oAncoraEspecialidade = new Element('a', {href:'', id:'ancora-especialidade'}).update('Especialidade:');
  this.oAncoraProfissional.addClassName("DBAncora bold");
  this.oAncoraEspecialidade.addClassName("DBAncora bold");

  // inputs profissional
  this.oInputCodigoProfissional  = new Element ("input", {type:'text', id : 'iMedico', name : 'iMedico' });
  this.oInputNomeProfissional    = new Element ("input", {type:'text', id : 'sMedico', name : 'sMedico' });
  this.oInputCodigoProfissional.addClassName('field-size2');
  this.oInputNomeProfissional.addClassName('field-size9 readonly');
  this.oInputNomeProfissional.setAttribute('disabled', 'disabled');

  // inputs especialidade
  var oInputCodigoEspecialidade      = new Element ("input", {type:'hidden', id:'iSequencialCbo', name:'iSequencialCbo' });
  this.oInputEstruturalEspecialidade = new Element ("input", {type:'text', id:'iEstruturalCbo', name:'iEstruturalCbo' });
  this.oInputNomeEspecialidade       = new Element ("input", {type:'text', id:'sEspecialidade', name:'sEspecialidade' });
  this.oInputEstruturalEspecialidade.addClassName('field-size2');
  this.oInputNomeEspecialidade.addClassName('field-size9 readonly');
  this.oInputNomeEspecialidade.setAttribute('disabled', 'disabled');

  var oLabelProfissional   = document.createElement("label");
  var oLabelEspecialidade  = document.createElement("label");
  oLabelProfissional.appendChild(this.oAncoraProfissional);
  oLabelProfissional.setAttribute('for', 'iMedico');
  oLabelEspecialidade.appendChild(this.oAncoraEspecialidade);
  oLabelEspecialidade.setAttribute('for', 'iEstruturalCbo');
  oLabelProfissional.appendChild(this.oAncoraProfissional);
  oLabelEspecialidade.appendChild(this.oAncoraEspecialidade);

  var oCelulaLabelProfissional     = oLinha2.insertCell(0);
  var oCelulaConteudoProfissional  = oLinha2.insertCell(1);
  var oCelulaLabelEspecialidade    = oLinha3.insertCell(0);
  var oCelulaConteudoEspecialidade = oLinha3.insertCell(1);

  oCelulaLabelProfissional.appendChild(oLabelProfissional);
  oCelulaLabelEspecialidade.appendChild(oLabelEspecialidade);
  oCelulaConteudoProfissional.appendChild(this.oInputCodigoProfissional);
  oCelulaConteudoProfissional.appendChild(this.oInputNomeProfissional);
  oCelulaConteudoEspecialidade.appendChild(oInputCodigoEspecialidade);
  oCelulaConteudoEspecialidade.appendChild(this.oInputEstruturalEspecialidade);
  oCelulaConteudoEspecialidade.appendChild(this.oInputNomeEspecialidade);

};


/**
 * Constantes do local de atendimento
 * @type {Number}
 */
DBViewEncaminhamento.RECEPCAO        = 1;
DBViewEncaminhamento.TRIAGEM         = 2;
DBViewEncaminhamento.CONSULTA_MEDICA = 3;

/**
 * Busca os setores do ambulatorio
 * @return {void}
 */
DBViewEncaminhamento.prototype.buscaSetores = function() {

  var oSelf = this;

  var oParametros                   = {'sExecucao' : 'buscaSetoresUnidade'}
  oParametros.lFiltrarUnidadeLogada = true;

  if  (this.iLocalOrigem != DBViewEncaminhamento.CONSULTA_MEDICA) {
    oParametros.aExcluirLocais = [this.iLocalOrigem];
  }

  var oObject          = {}
  oObject.method       = 'post';
  oObject.parameters   = 'json=' + Object.toJSON( oParametros );
  oObject.asynchronous = false;
  oObject.onComplete   = function( oAjax ) {

    js_removeObj( 'msgBox' );
    var oRetorno = eval ( '(' + oAjax.responseText + ')');

    if ( parseInt(oRetorno.iStatus) != 1 ) {

      alert ( oRetorno.sMensagem.urlDecode() );
      return
    }

    oRetorno.aSetores.each(function (oSetor) {

      var oOption = new Option(oSetor.sDescricao.urlDecode(), oSetor.iCodigo);
      oOption.setAttribute('local', oSetor.iLocal);
      oSelf.oCboSetores.add(oOption);
    });

  };

  js_divCarregando( _M( MENSAGENS_DBVIEWENCAMINHAMENTO + 'buscando_setores' ), 'msgBox' );
  new Ajax.Request( oSelf.sRpc, oObject );

};

/**
 * define uma função de callback para ser executada ao fechar a janela
 * @param {function} fFunction
 */
DBViewEncaminhamento.prototype.setCallbackFechar = function( fFunction ) {
  this.fCallbackFechar = fFunction;
};

/**
 * define uma função de callback para ser executada ao salvar a movimentação
 * @param {function} fFunction
 */
DBViewEncaminhamento.prototype.setCallbackSalvar = function( fFunction ) {
  this.fCallbackSalvar = fFunction;
};

/**
 * Cria o componente da window
 * @return {}
 */
DBViewEncaminhamento.prototype.criaJaneja = function() {

  var oSelf    = this;
  this.oWindow = new windowAux( 'oWindowEncaminhamento', 'Encaminhamento de Paciente', 800, 400 );

  var sMensagemTitulo = 'Encaminha o paciente para um setor.';
  var sMensagemAjuda  = "Selecione o setor para encaminhamento do paciente";

  this.oWindow.setContent( this.oDivContainer );
  this.oWindow.setShutDownFunction( function () {

    oSelf.oWindow.destroy();
    oSelf.fCallbackFechar();
  });

  this.oMessageBoard = new DBMessageBoard( 'messageBoardMotivosAlta',
                                           sMensagemTitulo,
                                           sMensagemAjuda,
                                           this.oWindow.getContentContainer()
                                         );

  $('btnSalvarEncaminhamento').onclick = function() {
    oSelf.encaminharProntuario();
  };

  this.oWindow.show( null, null, false );

  this.oCboSetores.onchange = function () {

    var iLocal = $('setorEncaminhamento').options[$('setorEncaminhamento').selectedIndex].getAttribute('local');

    if ( oSelf.iLocalOrigem == DBViewEncaminhamento.CONSULTA_MEDICA &&
         iLocal             == DBViewEncaminhamento.CONSULTA_MEDICA) {

      $('linha-profissional').style.display  = "table-row";
      $('linha-especialidade').style.display = "table-row";
    } else {

      $('linha-profissional').style.display  = "none";
      $('linha-especialidade').style.display = "none";
      $('iMedico').value                     = '';
      $('sMedico').value                     = '';
      $('iSequencialCbo').value              = '';
      $('iEstruturalCbo').value              = '';
      $('sEspecialidade').value              = '';

    }
  }

  this.oAncoraProfissional.onclick            = function() {oSelf.criaAncoraProfissional(true);   return false;};
  this.oAncoraEspecialidade.onclick           = function() {oSelf.criaAncoraEspecialidade(true);  return false;};
  this.oInputCodigoProfissional.onchange      = function() {oSelf.criaAncoraProfissional(false);  return false;};
  this.oInputEstruturalEspecialidade.onchange = function() {oSelf.criaAncoraEspecialidade(false); return false;};

};

/**
 * Salva o encaminhamento do prontuário ao setor selecionado
 * @return {}
 */
DBViewEncaminhamento.prototype.encaminharProntuario = function() {

  var oSelf = this;

  var oParametros         = {'sExecucao' : 'encaminharProntuario'}
  oParametros.iProntuario = this.iProntuario;

  if ( this.oCboSetores.value == '' ) {

    alert( _M( MENSAGENS_DBVIEWENCAMINHAMENTO + 'setor_vazio' ) );
    return false;
  }

  var iLocalEncaminhado = $('setorEncaminhamento').options[$('setorEncaminhamento').selectedIndex].getAttribute('local');

  if ( this.iLocalOrigem == DBViewEncaminhamento.CONSULTA_MEDICA &&
       iLocalEncaminhado == DBViewEncaminhamento.CONSULTA_MEDICA ) {

    if ( $F('iEstruturalCbo') == '') {

      alert(_M( MENSAGENS_DBVIEWENCAMINHAMENTO + 'informe_especialidade' ) );
      return false;
    }
  }

  oParametros.iMedico        = $F('iMedico');
  oParametros.iEspecialidade = $F('iSequencialCbo');
  oParametros.iSetorDestino  = this.oCboSetores.value;
  oParametros.sObservacao    = encodeURIComponent(tagString( this.oInputObservacao.value ));

  var oObject          = {}
  oObject.method       = 'post';
  oObject.parameters   = 'json=' + Object.toJSON( oParametros );
  oObject.asynchronous = false;
  oObject.onComplete   = function( oAjax ) {

    js_removeObj('msgBox');
    var oRetorno = eval(' (' + oAjax.responseText + ') ');

    alert( oRetorno.sMensagem.urlDecode() );

    if( oRetorno.iStatus != 1) {
      return;
    }
    oSelf.oWindow.destroy();
    oSelf.fCallbackSalvar();
  }

  js_divCarregando( _M( MENSAGENS_DBVIEWENCAMINHAMENTO + 'encaminhando_prontuario' ), 'msgBox' );
  new Ajax.Request( this.sRpc, oObject );
};

/**
 * cria a window
 * @return {void}
 */
DBViewEncaminhamento.prototype.show = function () {

  this.buscaSetores();
  this.criaJaneja();
};


DBViewEncaminhamento.prototype.criaAncoraProfissional = function(lMostra){

  var sUrl  = 'func_medicos.php?';
      sUrl += 'lFiltraDptoLogado=true';
  if( !empty( $F('iSequencialCbo') ) ) {
    sUrl += '&iRhcboSequencial=' + $F('iSequencialCbo');
  }

  if( lMostra ) {

    sUrl += '&funcao_js=parent.DBViewEncaminhamento.retornoMedicos|sd03_i_codigo|z01_nome';
    js_OpenJanelaIframe('', 'db_iframe_medicos', sUrl, 'Pesquisa Profissional', true);
    $('Jandb_iframe_medicos').style.zIndex  = '510';

  } else if ( $F('iMedico') != '') {

    sUrl += '&pesquisa_chave='+ $F('iMedico');
    sUrl += '&funcao_js=parent.DBViewEncaminhamento.retornoMedicos';
    js_OpenJanelaIframe('', 'db_iframe_medicos', sUrl, 'Pesquisa Profissional', false);

  } else {

    $('iMedico').value = '';
    $('sMedico').value = '';
  }
};

DBViewEncaminhamento.retornoMedicos = function () {

  if (typeof arguments[1] == 'boolean') {

    $('sMedico').value = arguments[0];
    if ( arguments[1] ) {

      $('iMedico').value = '';
      return;
    }
  } else {

    $('iMedico').value = arguments[0];
    $('sMedico').value = arguments[1];

    db_iframe_medicos.hide();
  }

  if ( $F('iSequencialCbo') != '' ) {
    return;
  }
  $('ancora-especialidade').click();

}



DBViewEncaminhamento.prototype.criaAncoraEspecialidade  = function(lMostra){

  var sUrl  = 'func_especialidade_recepcao.php?';
      sUrl += 'chave_sd04_i_medico=' + $F('iMedico');

  var sCampos = '|rh70_estrutural|rh70_descr|rh70_sequencial';

  if( lMostra ) {

    sUrl += '&funcao_js=parent.DBViewEncaminhamento.retornoEspecialidade' + sCampos;
    js_OpenJanelaIframe('', 'db_iframe_especmedico', sUrl + sCampos, 'Pesquisa Especialidade', true );
    $('Jandb_iframe_especmedico').style.zIndex  = '510';

  } else if ( $F('iEstruturalCbo') != '') {

    sUrl += '&pesquisa_chave=' + $F('iEstruturalCbo');
    sUrl += '&funcao_js=parent.DBViewEncaminhamento.retornoEspecialidade';

    js_OpenJanelaIframe('', 'db_iframe_especmedico', sUrl, 'Pesquisa Especialidade', false );
  } else {

    $('iSequencialCbo').value = '';
    $('iEstruturalCbo').value = '';
    $('sEspecialidade').value = '';
  }

};


DBViewEncaminhamento.retornoEspecialidade = function () {

  if (typeof arguments[0] == 'boolean') {

    if ( arguments[0] ) {

      $('sEspecialidade').value = arguments[1];
      $('iSequencialCbo').value = '';
      $('iEstruturalCbo').value = '';

      return;
    } else {

      $('iEstruturalCbo').value = arguments[2];
      $('sEspecialidade').value = arguments[3];
      $('iSequencialCbo').value = arguments[4];
    }

  } else {

    $('iEstruturalCbo').value = arguments[0];
    $('sEspecialidade').value = arguments[1];
    $('iSequencialCbo').value = arguments[2];
    db_iframe_especmedico.hide();
  }
}
