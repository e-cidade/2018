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

const MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO = 'saude.ambulatorial.DBViewAdministracaoMedicamento.';

/**
 * Classe referente a administração de medicamentos feitos pela triagem
 * @constructor
 */
DBViewAdministracaoMedicamento = function( iProntuario ) {

  var oSelf = this;

  /**
   * Prontuário (FAA) do paciente ao qual será vínculado as administrações de medicamentos
   * @type {integer}
   */
  this.iProntuario = iProntuario;

  /**
   * Quantidade total do material
   * @type {integer}
   */
  this.iQuantidadeTotal = null;

  /**
   * Unidade referente ao conteúdo do medicamento, ou a própria unidade cadastrada para o meterial
   * @type {[type]}
   */
  this.iUnidade = null;

  /**
   * Janela contendo as informações da WindowsAux
   * @type {windowAux}
   */
  this.oWindowAdministracaoMedicamentos = null;

  /**
   * Chamada para o método que constroi a estrutura HTML pardrão
   */
  this.montaFormulario( oSelf );

  this.sRpc = 'sau4_administracaomedicamentos.RPC.php';

  this.fCallbackFechar = function() { return true };
};

/**
 * Monta toda a estrutura HTML contida no componente
 */
DBViewAdministracaoMedicamento.prototype.montaFormulario = function( oSelf ) {

  oSelf.oDivPrincipal = document.createElement("div");

  /**
   * Elemento que contém todos os elementos do formulário
   * @type {HTMLDivElement}
   */
  oSelf.oDivCorpo = document.createElement("div");
  oSelf.oDivCorpo.addClassName('container');

  oSelf.oFormularioMedicamentos = document.createElement("form");
  oSelf.oFormularioMedicamentos.addClassName('form-container');



  /**
   * Label para a ancora de medicamentos
   */
  oSelf.oLabelMedicamento = document.createElement("label");
  oSelf.oLabelMedicamento.setAttribute('for', 'inputCodigoMedicamento');
  oSelf.oLabelMedicamento.addClassName('bold');
  //oSelf.oLabelMedicamento.innerHTML = "Medicamento: ";

  /**
   * Ancora para buscar os medicamentos
   */
  oSelf.oInputCodigoMedicamento    = document.createElement("input");
  oSelf.oInputCodigoMedicamento.id = 'inputCodigoMedicamento';
  oSelf.oInputCodigoMedicamento.addClassName('field-size2');

  oSelf.oSpanAncoraMedicamento = document.createElement('span');

  oSelf.oInputDescricaoMedicamento    = document.createElement("input");
  oSelf.oInputDescricaoMedicamento.id = 'inputDescricaoMedicamento';
  oSelf.oInputDescricaoMedicamento.addClassName('field-size9');
  oSelf.oInputDescricaoMedicamento.setAttribute('readonly', 'readonly');
  oSelf.oInputDescricaoMedicamento.setStyle( { 'backgroundColor' : '#DEB887' } );


  /**
   * Campo referente a dosagem utilizada no paciente
   */
  oSelf.oLabelDosagem = document.createElement("label");
  oSelf.oLabelDosagem.setAttribute('for', 'inputDosagem');
  oSelf.oLabelDosagem.addClassName('bold');
  oSelf.oLabelDosagem.innerHTML = "Dosagem: ";

  oSelf.oSpanUnidadeDosagem = document.createElement("span");

  oSelf.oInputDosagem    = document.createElement("input");
  oSelf.oInputDosagem.id = 'inputDosagem';
  oSelf.oInputDosagem.addClassName('field-size2');

  /**
   * Formata a data e hora de acordo como deve ser exibido na tela
   */
  var oInputHora = new DBInputHora( document.createElement("input") );
  var oData      = new Date();
  var sHora      = js_strLeftPad( oData.getHours(),'2', '0' ) + ':' + js_strLeftPad( oData.getMinutes(),'2', '0' );
  var iMes       = js_strLeftPad( oData.getMonth() + 1, '2', '0');
  var iDia       = js_strLeftPad( oData.getDate(), '2', '0');
  var sData      = iDia + '/' + iMes + '/' + oData.getFullYear();

  /**
   * Campo com a informação da data atual
   */
  oSelf.oLabelDataAtual = document.createElement("label");
  oSelf.oLabelDataAtual.setAttribute('for', 'inputDataAtual');
  oSelf.oLabelDataAtual.addClassName('bold');
  oSelf.oLabelDataAtual.innerHTML = "Data: ";

  oSelf.oInputDataAtual = document.createElement('input');
  oSelf.oInputDataAtual.setAttribute('id', 'inputDataAtual');
  oSelf.oInputDataAtual.addClassName('field-size2');
  oSelf.oInputDataAtual.setAttribute('type', 'text');
  oSelf.oInputDataAtual.setAttribute('name', 'inputDataAtual');
  oSelf.oInputDataAtual.setAttribute('onkeyup', 'return js_mascaraData(this,event)');
  oSelf.oInputDataAtual.setAttribute('maxLength', '10');
  oSelf.oInputDataAtual.setAttribute('readonly', 'readonly');
  oSelf.oInputDataAtual.setStyle( { 'backgroundColor' : '#DEB887' } );
  oSelf.oInputDataAtual.value = sData;

  /**
   * Campo com a informação da hora da administração do medicamento
   */
  oSelf.oLabelHoraAdministracao = document.createElement('label');
  oSelf.oLabelHoraAdministracao.setAttribute('for', 'inputHoraAdministracao');
  oSelf.oLabelHoraAdministracao.addClassName('bold');
  oSelf.oLabelHoraAdministracao.addClassName('field-size2');
  oSelf.oLabelHoraAdministracao.innerHTML = 'Hora: ';

  oSelf.oInputHoraAdministracao = oInputHora.getElement();
  oSelf.oInputHoraAdministracao.addClassName('field-size1');
  oSelf.oInputHoraAdministracao.value = sHora;


  /**
   * Fieldset que contém a seleção de medicamentos e a dosagem utilizada
   */
  oSelf.oFieldsetMedicamentos         = document.createElement( 'fieldset' );
  oSelf.oLegendMedicamentos           = document.createElement( 'legend' );
  oSelf.oLegendMedicamentos.innerHTML = "Medicamento / Dosagem";

  /**
   * Tabela contendo os campos do formulário
   */
  oSelf.oTabelaMedicamentos = document.createElement( 'table' );
  oSelf.oLinhaMedicamento   = oSelf.oTabelaMedicamentos.insertRow(0);
  oSelf.oLinhaDosagem       = oSelf.oTabelaMedicamentos.insertRow(1);
  oSelf.oLinhaDataHora      = oSelf.oTabelaMedicamentos.insertRow(2);

  oSelf.oLinhaMedicamento.insertCell(0).addClassName('field-size2').appendChild(oSelf.oLabelMedicamento);
  oSelf.oLinhaMedicamento.insertCell(1).appendChild( oSelf.oInputCodigoMedicamento );
  oSelf.oLinhaMedicamento.insertCell(2).appendChild( oSelf.oInputDescricaoMedicamento );

  oSelf.oLinhaDosagem.insertCell(0).addClassName('field-size2').appendChild(oSelf.oLabelDosagem);
  oSelf.oLinhaDosagem.insertCell(1).appendChild(oSelf.oInputDosagem);
  oSelf.oLinhaDosagem.insertCell(2).appendChild(oSelf.oSpanUnidadeDosagem);

  oSelf.oLinhaDataHora.insertCell(0).addClassName('field-size2').appendChild(oSelf.oLabelDataAtual);
  oSelf.oLinhaDataHora.insertCell(1).appendChild(oSelf.oInputDataAtual);

  var oLinhaData = oSelf.oLinhaDataHora.insertCell(2);
      oLinhaData.setAttribute('colspan', 2);
      oLinhaData.appendChild(oSelf.oLabelHoraAdministracao)
      oLinhaData.appendChild(oSelf.oInputHoraAdministracao);

  /**
   * Botão para incluir a administração do medicamento
   */
  
  oSelf.oBotaoIncuirMedicamento = document.createElement( 'input' );
  oSelf.oBotaoIncuirMedicamento.setAttribute( 'id', 'btnIncluirMedicamento' );
  oSelf.oBotaoIncuirMedicamento.setAttribute( 'type', 'button' );
  oSelf.oBotaoIncuirMedicamento.setAttribute( 'value', 'Incluir' );

  oSelf.oFieldsetGridAdministrados             = document.createElement('fieldset');
  oSelf.oFieldsetGridAdministrados.style.width = '750px';

  oSelf.oLegendGridAdministrados = document.createElement('legend');
  oSelf.oLegendGridAdministrados.innerHTML = 'Medicamentos Administrados';

  /**
   * Container com as informações das administrações de medicamentos já cadastradas
   */
  oSelf.oContainerGridAdministrados    = document.createElement( 'div' );
  oSelf.oContainerGridAdministrados.id = 'ctnGridAdministracaoMedicamentos';
  oSelf.oContainerGridAdministrados.addClassName( 'container' );

  
  oSelf.oDivGridAdministrados    = document.createElement('div');
  oSelf.oDivGridAdministrados.id = 'divGridAdministrados';

  /**
   * Víncula os elementos
   */
  oSelf.oFieldsetMedicamentos.appendChild( oSelf.oLegendMedicamentos );
  oSelf.oFieldsetMedicamentos.appendChild( oSelf.oTabelaMedicamentos );
  oSelf.oFieldsetGridAdministrados.appendChild( oSelf.oLegendGridAdministrados );
  oSelf.oFieldsetGridAdministrados.appendChild( oSelf.oDivGridAdministrados );
  oSelf.oFormularioMedicamentos.appendChild( oSelf.oFieldsetMedicamentos );
  oSelf.oFormularioMedicamentos.appendChild( oSelf.oBotaoIncuirMedicamento );

  oSelf.oContainerGridAdministrados.appendChild( oSelf.oFieldsetGridAdministrados );
  oSelf.oDivCorpo.appendChild( oSelf.oFormularioMedicamentos );
  oSelf.oDivPrincipal.appendChild(oSelf.oDivCorpo);
  oSelf.oDivPrincipal.appendChild(oSelf.oContainerGridAdministrados);
};

/**
 * Monta a grid contendo os medicamentos que já foram administrados na FAA
 */
DBViewAdministracaoMedicamento.prototype.montaGridMedicamentosAdministrados = function () {

  var oSelf = this;
  oGridMedicamentosAdministrados = new DBGrid('gridMedicamentosAdministrados');
  var aHeaders   = ['Codigo_Administrado','Medicamento', 'Descrição', 'Dosagem', 'Data/Hora', 'Ação'];
  var aCellWidth = ['1%', '13%', '42%', '15%', '19%', '10%'];
  var aCellAlign = ['center', 'center', 'left', 'center', 'center', 'center' ];

  oGridMedicamentosAdministrados.nameInstance = 'oGridMedicamentosAdministrados';
  oGridMedicamentosAdministrados.setCellWidth( aCellWidth );
  oGridMedicamentosAdministrados.setCellAlign( aCellAlign );
  oGridMedicamentosAdministrados.setHeader( aHeaders );
  oGridMedicamentosAdministrados.setHeight(150);
  oGridMedicamentosAdministrados.aHeaders[0].lDisplayed = false;
  oGridMedicamentosAdministrados.show( oSelf.oDivGridAdministrados );
};

/**
 * Inicializa o componente, montando a tela
 * @param oElemento
 */
DBViewAdministracaoMedicamento.prototype.criaJanela = function() {

  var oSelf    = this;
  this.oWindowAdministracaoMedicamentos = new windowAux( 'oWindowAdministracaoMedicamentos', 'Administração de Medicamentos', 900, 600 );

  var sMensagemTitulo = 'Registro de medicamentos administrados.';
  var sMensagemAjuda  = "Selecione os medicamentos que foram administrados no paciente.";

  this.oWindowAdministracaoMedicamentos.setContent( this.oDivPrincipal );
  this.oWindowAdministracaoMedicamentos.setShutDownFunction( function () {

    oSelf.fCallbackFechar();
    oSelf.oWindowAdministracaoMedicamentos.destroy();
  });

  this.oMessageBoard = new DBMessageBoard( 'messageBoardAdministracaoMedicamentos',
                                           sMensagemTitulo,
                                           sMensagemAjuda,
                                           this.oWindowAdministracaoMedicamentos.getContentContainer()
                                         );

  oSelf.oAncoraMedicamento = new DBAncora( 'Medicamento: ', "#", true );
  oSelf.oAncoraMedicamento.show( oSelf.oLabelMedicamento );

  oSelf.oWindowAdministracaoMedicamentos.show( null, null, false );
  oSelf.montaGridMedicamentosAdministrados();

  oSelf.oAncoraMedicamento.onClick( function () {
    oSelf.buscarMedicamentos(true);
  });

  oSelf.oInputCodigoMedicamento.onchange = function() {
    oSelf.buscarMedicamentos(false);
  };

  oSelf.oInputCodigoMedicamento.oninput = function() {
    js_ValidaCampos( this, 1, 'Medicamento', 't', 'f' );
  };

  oSelf.oInputDosagem.oninput = function() {
    js_ValidaCampos( this, 4, 'Dosagem', 't', 'f' );
  };

  oSelf.oBotaoIncuirMedicamento.onclick = function() {
    oSelf.salvarAdministracaoMedicamentos();
  };
};

/**
 * Inicializa o componente, montando a tela
 * @param oElemento
 */
DBViewAdministracaoMedicamento.prototype.show = function() {
 
 if ($('oWindowAdministracaoMedicamentos') != null) {    
    return true;
  }

  this.criaJanela();
  this.buscarMedicamentosAdministrados();
};

/**
 * Chama a função de pesquisa dos medicamentos
 * @param  {boolean} lMostra Define se deve abrir a janela de pesquisa ou não
 */
DBViewAdministracaoMedicamento.prototype.buscarMedicamentos = function( lMostra ) {

  oInstancia = this;

  var sUrl    = 'func_medicamentosmaterial.php';
  var sIframe = 'db_iframe_medicamentosmaterial';
  var sGet    = '?funcao_js=parent.oInstancia.retornoBuscarMedicamentos';

  if ( lMostra ) {

    sGet += '|fa01_i_codigo|m60_descr|m08_quantidade|dl_unidade|m61_codmatunid'
    js_OpenJanelaIframe('', sIframe, sUrl + sGet, 'Pesquisa Medicamentos', true);
    $('Jandb_iframe_medicamentosmaterial').style.zIndex = 10000;
    return;
  }

  if ( this.oInputCodigoMedicamento.value == '' ) {

    this.oInputDescricaoMedicamento.value = '';
    this.oSpanUnidadeDosagem.innerHTML    = '';
    return;
  }

  sGet += "&pesquisa_chave=" + this.oInputCodigoMedicamento.value;
  js_OpenJanelaIframe('', sIframe, sUrl + sGet, 'Pesquisa Medicamentos', false);
};

/**
 * Define os valores do código e descrição do medicamento de acordo com os dados retornados da função de pesquisa
 */
DBViewAdministracaoMedicamento.prototype.retornoBuscarMedicamentos = function() {

  this.oInputCodigoMedicamento.value    = arguments[0];
  this.oInputDescricaoMedicamento.value = arguments[1];
  this.oSpanUnidadeDosagem.innerHTML    = arguments[2] + ' ' + arguments[3];
  this.iQuantidadeTotal                 = arguments[2];
  this.iUnidade                         = arguments[4];

  if ( typeof(arguments[0]) == "boolean" ) {

    if ( arguments[0] == false ) {

      this.oInputCodigoMedicamento.value    = arguments[1];
      this.oInputDescricaoMedicamento.value = arguments[2];
      this.oSpanUnidadeDosagem.innerHTML    = arguments[3] + ' ' + arguments[4];
      this.iQuantidadeTotal                 = arguments[3];
      this.iUnidade                         = arguments[5];
    } else {

      this.oInputCodigoMedicamento.value    = '';
      this.oInputDescricaoMedicamento.value = arguments[1];
      this.oSpanUnidadeDosagem.innerHTML    = '';
      this.iQuantidadeTotal                 = null;
      this.iUnidade                         = null;
    }
  }

  db_iframe_medicamentosmaterial.hide();
  delete oInstancia;
};

/**
 * Salva a quantidade de medicamento que foi administrado na FAA
 */
DBViewAdministracaoMedicamento.prototype.salvarAdministracaoMedicamentos = function() {
  
  if( !this.validaCampos() ) {
    return;
  }

  var oSelf = this;

  var oParametros                         = {};
      oParametros.sExecuta                = 'salvar';
      oParametros.iMedicamento            = this.oInputCodigoMedicamento.value;
      oParametros.iUnidade                = this.iUnidade;
      oParametros.sData                   = this.oInputDataAtual.value;
      oParametros.sHora                   = this.oInputHoraAdministracao.value;
      oParametros.nQuantidadeAdministrada = this.oInputDosagem.value;
      oParametros.nQuantidadeEmbalagem    = this.iQuantidadeTotal;
      oParametros.iProntuario             = this.iProntuario;


  var oDadosRequisicao            = {};
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete = function( oResponse ) {
        oSelf.retornoSalvarAdministracaoMedicamentos( oResponse, oSelf );
      };

  js_divCarregando( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'salvando_administracao_medicamentos' ), 'msgBoxSalvandoMedicamento' );
  new Ajax.Request( this.sRpc, oDadosRequisicao );
};

DBViewAdministracaoMedicamento.prototype.retornoSalvarAdministracaoMedicamentos = function( oResponse, oSelf ) {

  js_removeObj('msgBoxSalvandoMedicamento');

  var oRetorno = JSON.parse( oResponse.responseText );
  
  alert( oRetorno.sMensagem.urlDecode() );

  if( oRetorno.erro == false ) {
    
    oSelf.limpaCampos();
    oSelf.buscarMedicamentosAdministrados();
  }
};

/**
 * Busca todos os medicamentos que já foram administrados na FAA e os adiciona a grid
 */
DBViewAdministracaoMedicamento.prototype.buscarMedicamentosAdministrados= function () {

  var oSelf = this;

  if ( empty(this.iProntuario) ) {

    alert( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'prontuario_nao_informado' ) );
    return false;
  }

  var oParametros             = {};
      oParametros.sExecuta    = 'buscarMedicamentosAdministrados';
      oParametros.iProntuario = this.iProntuario;

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete = function( oResponse ) {
        oSelf.retornoBuscarMedicamentosAdministrados( oResponse, oSelf );
      }

  js_divCarregando( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'buscando_medicamentos_administrados' ), 'msgBuscandoMedicamentosAdministrados' );
  new Ajax.Request( this.sRpc, oDadosRequisicao );
};


DBViewAdministracaoMedicamento.prototype.retornoBuscarMedicamentosAdministrados = function ( oResponse, oSelf  ) {

  js_removeObj( 'msgBuscandoMedicamentosAdministrados' );

  var oRetorno = JSON.parse( oResponse.responseText );

  if ( oRetorno.erro == true ) {
    
    alert( oRetorno.sMensagem.urlDecode() );
    return false;
  }

  oInstancia = oSelf;
  oGridMedicamentosAdministrados.clearAll( true );

  oRetorno.aMedicamentos.each(function( oMedicamento, iSeq ) {

    var oElementoInput  = "<input type='button' id='excluir" + oMedicamento.iCodigoAdministracao + "'"; 
        oElementoInput += " value='E' onclick='oInstancia.excluirMedicamentoAdministrado( "+ oMedicamento.iCodigoAdministracao +" )' />";

    var sHora = oMedicamento.sHora.urlDecode().substring( 0, 5);

    var aLinha = [];
        aLinha.push( oMedicamento.iCodigoAdministracao );
        aLinha.push( oMedicamento.iMedicamento );
        aLinha.push( oMedicamento.sMedicamento.urlDecode() );
        aLinha.push( oMedicamento.sDosagem.urlDecode() );
        aLinha.push( oMedicamento.sData.urlDecode() + ' ' + sHora );
        aLinha.push( oElementoInput );

    oGridMedicamentosAdministrados.addRow( aLinha );
    oGridMedicamentosAdministrados.aRows[ iSeq ].aCells[2].addClassName( 'elipse' );
  });

  oGridMedicamentosAdministrados.renderRows();

  /**
   * Adiciona o Hint a descrição de medicamentos
   */
  oRetorno.aMedicamentos.each(function( oMedicamento, iSeq ) {

    var sMedicamento = oMedicamento.sMedicamento.urlDecode();
    oGridMedicamentosAdministrados.setHint(iSeq, 2, sMedicamento);
  });

};

/**
 * Exclui um medicamento que já foi administrado na FAA
 * @param  {integer} iCodigoAdministracao
 */
DBViewAdministracaoMedicamento.prototype.excluirMedicamentoAdministrado = function( iCodigoAdministracao ) {

  if ( !confirm( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'confirmar_exclusao' ) ) ) {
    return false;
  }

  var oSelf = this;

  var oParametros             = {};
      oParametros.sExecuta    = 'remover';
      oParametros.iCodigo     = iCodigoAdministracao;
      oParametros.iProntuario = this.iProntuario;

  var oDadosRequisicao              = {};
      oDadosRequisicao.method       = 'post';
      oDadosRequisicao.parameters   = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.asynchronous = false;
      oDadosRequisicao.onComplete = function( oResponse ) {
        oSelf.retornoExcluirMedicamentoAdministrado( oResponse, oSelf );
      }
  js_divCarregando( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'excluindo_administracao' ), 'msgExcluindoMedicamentosAdministrados' );
  new Ajax.Request( this.sRpc, oDadosRequisicao );
};

DBViewAdministracaoMedicamento.prototype.retornoExcluirMedicamentoAdministrado = function( oResponse, oSelf ) {

  js_removeObj('msgExcluindoMedicamentosAdministrados');

  var oRetorno = JSON.parse( oResponse.responseText );
  
  alert( oRetorno.sMensagem.urlDecode() );
  if( oRetorno.erro == false ) {
    oSelf.buscarMedicamentosAdministrados();
  }
};

/**
 * Verifica se todos os campos obrigatórios da tela foram preenchidos
 */
DBViewAdministracaoMedicamento.prototype.validaCampos = function() {

  if( empty( this.oInputCodigoMedicamento.value ) ) {

    alert( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'medicamento_nao_informado' ) );
    return false;
  }

  if( empty( this.oInputDosagem.value ) ) {

    alert( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'dosagem_nao_informada' ) );
    return false;
  }

  if ( this.oInputDosagem.value <= 0 ) {

    alert( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'valor_negativo_dosagem' ) );
    return false;
  }

  if( empty( this.oInputHoraAdministracao.value ) ) {

    alert( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'hora_nao_informada' ) );
    return false;
  }

  if( empty( this.oInputDataAtual.value ) ) {

    alert( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'valor_da_data_nao_preenchida' ) );
    return false;
  }

  if( empty( this.iUnidade ) ) {

    alert( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'unidade_nao_preenchida' ) );
    return false;
  }

  if( empty( this.iQuantidadeTotal ) ) {

    alert( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'quantidade_total_nao_preenchida' ) );
    return false;
  }

  if( empty( this.iProntuario ) ) {

    alert( _M( MENSAGENS_DBVIEWADMINISTRACAOMEDICAMENTO + 'prontuario_nao_informado' ) );
    return false;
  }

  return true;
};

DBViewAdministracaoMedicamento.prototype.setCallbackFechar = function( fFuncao ) {
  this.fCallbackFechar = fFuncao;
};

/**
 * Limpa todos os valores dos campos do formulário
 */
DBViewAdministracaoMedicamento.prototype.limpaCampos = function() {

  this.oInputCodigoMedicamento.value    = '';
  this.oInputDescricaoMedicamento.value = '';
  this.oInputDosagem.value              = '';
  this.iUnidade                         = null;
  this.iQuantidadeTotal                 = null;
  this.oSpanUnidadeDosagem.innerHTML    = '';
};