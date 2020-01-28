/**
 * @param {HTMLElement} oContainer
 */
var ImportacaoDiversos = function(oContainer, iTipoOrigem, iTipoDestino, lUnificarDebitos) {

  this.oGridReceitas = null;
  this.oContainer = oContainer;
  this.importaDividaAtiva = false;

  this.oInputObservacoes = document.createElement('textarea');
  this.oInputObservacoes.setAttribute('id', 'observacoes');
  this.oInputObservacoes.setAttribute('name', 'observacoes');
  this.oInputObservacoes.setAttribute('style', 'width: 100%');

  this.iTipoOrigem      = iTipoOrigem;
  this.iTipoDestino     = iTipoDestino;
  this.lUnificarDebitos = lUnificarDebitos;
  this.iDataVencimento  = null;
  this.fnCallbackProcessar = function () {};

  var oCollection = new Collection().setId('codigo');
  this.oGridReceitas = DatagridCollection.create(oCollection)
    .configure({
      'order'  : false,
      'height' : 200
    });

  this.oGridReceitas.addColumn('receita', {
    'label' : 'Receita'
  });
  this.oGridReceitas.addColumn("procedencia", {
    'label' : 'Procedência'
  });

  this.oBotaoProcessar = document.createElement('input');
  this.oBotaoProcessar.setAttribute('type', 'button');
  this.oBotaoProcessar.setAttribute('value', 'Processar');
  this.oBotaoProcessar.addEventListener('click', this.processar.bind(this));

  this.oContainerGrid = document.createElement('div');
};

ImportacaoDiversos.prototype = {
  _prepararParametros: function () {

    var inputObservacoes = $('observacoes');
    var aDados = [];
    for (var oItem of this.oGridReceitas.getCollection().build()) {

      var iCodigoReceita = oItem.codigo;
      var comboProcedencia = $('procedencia-receita-' + iCodigoReceita);
      var iCodigoProcedencia = comboProcedencia.value;

      if (iCodigoProcedencia !== '0') {

        aDados.push({
          'iCodigoReceita' : iCodigoReceita,
          'iCodigoProcedencia' : iCodigoProcedencia
        });
      }
    }

    var unificarDebitos = $('unificarDebitosNumpreReceita').value === "2";
    var tipoDataVencimento = null;
    if (unificarDebitos) {
      tipoDataVencimento = $F('dataVencimento');
    }

    var codigoProcesso = '';
    var titularProcesso = '';
    var dataProcesso    = '';
    var processoSistema = $('processosistema').value === 't';

    if (processoSistema === true) {
      codigoProcesso = $F('codigo_processo');
    }

    if (processoSistema === false) {
      codigoProcesso  = $F('codigo_processo_fora_sistema');
      titularProcesso = $F('titular_processo_fora_sistema');
      dataProcesso    = $F('data_processo_fora_sistema');
    }

    return {
      'iTipoDebitoOrigem' : this.iTipoOrigem,
      'aDados' : aDados,
      'sObservacoes' : inputObservacoes.value,
      'lUnificarDebitos' : unificarDebitos,
      'iTipoDataVencimento' : tipoDataVencimento,
      'processoSistema' : processoSistema,
      'codigoProcesso' : codigoProcesso,
      'titularProcesso' : titularProcesso,
      'dataProcesso' : dataProcesso
    };
  },

  /**
   * Processamento de receitas
   */
  processar: function() {

    var oParametros = this._prepararParametros();

    var self = this;

    if (oParametros) {

      oParametros['sExec'] = 'importacaoGeralDiversosCobrancaAdministrativa';
      if (this.importaDividaAtiva === true) {
        oParametros['sExec'] = 'importacaoGeralDividaParaDividaAtiva';
      }

      new AjaxRequest("dvr3_importacaoiptu.RPC.php", oParametros, function (oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());
        if (lErro) {
          return false;
        }
        self.fnCallbackProcessar();


      }).execute();
    }
  },

  /**
   * Renderiza os resultados retornados pelo RPC
   */
  _renderResultados: function (oRetorno) {

    this.oGridReceitas.clear();
    for (var oReceita of oRetorno.aReceitas) {

      /**
       * Monta as opções de Procedência
       */
      var oSelectProcedencia = document.createElement('select');
      oSelectProcedencia.setAttribute('id', 'procedencia-receita-' + oReceita.k00_receit);
      oSelectProcedencia.addClassName('field-size-max');

      var elementoSelecione = document.createElement('option');
      elementoSelecione.innerHTML = "Selecione...";
      elementoSelecione.value = "0";
      oSelectProcedencia.appendChild(elementoSelecione);
      for (var oProcedencia of oRetorno.aProcedencias) {

        var oElemento = document.createElement('option');

        var sDescricao  = oProcedencia.codigo + ' - ' + oProcedencia.descricao.urlDecode();
            sDescricao += ' - ' + oProcedencia.receita.urlDecode();

        var oDescricao = document.createTextNode(sDescricao);

        oElemento.setAttribute('value', oProcedencia.codigo);
        oElemento.appendChild(oDescricao);
        oSelectProcedencia.appendChild(oElemento);
      }

      /**
       * Adiciona linha na grid
       */
      this.oGridReceitas.getCollection().add({
        'codigo' : oReceita.k00_receit,
        'receita' : oReceita.k00_receit + ' - ' + oReceita.k02_descr.urlDecode(),
        'procedencia' : oSelectProcedencia.outerHTML
      });
    }
    this.oGridReceitas.reload();
  },

  /**
   * Pesquisa das receitas e preenchimento a grid
   */
  pesquisar: function () {

    var oParametros = {
      'sExec' : 'getReceitasProcedencias',
      'tipoDebitoOrigem' : this.iTipoOrigem,
      'tipoDebitoDestino' : this.iTipoDestino,
      'importaDividaAtiva' : this.importaDividaAtiva
    };

    new AjaxRequest("dvr3_importacaoiptu.RPC.php", oParametros, function (oRetorno, lErro) {

      if (lErro) {
        return alert(oRetorno.message.urlDecode());
      }

      this._renderResultados(oRetorno);
    }.bind(this)).execute();
  },

  /**
   * Renderização do componente
   */
  render: function() {

    var oFieldsetReceitas = document.createElement('fieldset');
    oFieldsetReceitas.setAttribute('class', 'separator');

    var oLegendReceitas = document.createElement('legend');
    oLegendReceitas.appendChild(document.createTextNode('Receitas Encontradas'));

    oFieldsetReceitas.appendChild(oLegendReceitas);
    oFieldsetReceitas.appendChild(this.oContainerGrid);

    var oFieldsetObservacoes = document.createElement('fieldset');
    oFieldsetObservacoes.setAttribute('class', 'separator');

    var oLegendObservacoes = document.createElement('legend');
    oLegendObservacoes.appendChild(document.createTextNode('Observações'));

    oFieldsetObservacoes.appendChild(oLegendObservacoes);
    oFieldsetObservacoes.appendChild(this.oInputObservacoes);

    this.oContainer.innerHTML = '';
    this.oContainer.style.textAlign = 'center';
    this.oContainer.appendChild(oFieldsetReceitas);
    this.oContainer.appendChild(oFieldsetObservacoes);
    this.oContainer.appendChild(this.oBotaoProcessar);
    this.oGridReceitas.show(this.oContainerGrid);

    this.pesquisar();
  },

  setCallbackProcessar : function (fnCallback) {
    this.fnCallbackProcessar = fnCallback;
  },

  setImportacaoDividaAtiva : function (dividaAtiva) {
    this.importaDividaAtiva = dividaAtiva;
  }
}
