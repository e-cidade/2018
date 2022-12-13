require_once("scripts/classes/DBViewLancamentoAvaliacao/DBViewLancamentoAvaliacao.classe.js");

const AVALIACAO_RESULTADO       = 1;
const AVALIACAO                 = 2;
const RESULTADO                 = 3;
const AVALIACAO_RESULTADO_FINAL = 4;

/**
 * Monta um select-multiple com os periodos da turma
 * @dependency Utiliza DBViewLancamentoAvaliacao.classe.js
 * @autor Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @version $Revision: 1.9 $
 *
 * @param {integer} iTurma código da turma
 * @param {integer} iEtapa código da etapa
 * @returns void
 */
DBViewAvaliacao.PeriodoTurma = function (iTurma, iEtapa, lMultiple) {

  /**
   * código da turma
   * @var {integer}
   */
  this.iTurma = iTurma;

  /**
   * código da etapa
   * @var {integer}
   */
  this.iEtapa = iEtapa;

  /**
   * RPC para as requisições
   * @var {string}
   */
  this.sUrlRPC = 'edu4_turmas.RPC.php';

  if (lMultiple == null) {
    lMultiple = false;
  }
  
  /**
   * Define o tipo de busca dos períodos de avaliacao
   */
  this.iTipoBusca = AVALIACAO;
  

  /**
   * Elemento HTML select-multiple
   * @var {HTMLElement}
   */
  this.oCboPeriodos              = document.createElement('select');
  this.oCboPeriodos.id           = 'periodos#'+iTurma+'#'+iEtapa;
  this.oCboPeriodos.multiple     = lMultiple;
  this.oCboPeriodos.style.width  = '250px';

};

/**
 * Define a altura do comboBox
 * @exemple Deve ser informado a unidade de medida junto ao valor.
 *          DBViewAvaliacao.PeriodoTurma.setAltura('100px');
 *          DBViewAvaliacao.PeriodoTurma.setAltura('100%');
 * @param {string} sAltura
 * @returns void
 */
DBViewAvaliacao.PeriodoTurma.prototype.setAltura = function (sAltura) {

  this.oCboPeriodos.style.height = sAltura;
};


/**
 * Define a largura do comboBox
 * @exemple Deve ser informado a unidade de medida junto ao valor.
 *          DBViewAvaliacao.PeriodoTurma.setLargura('100px');
 *          DBViewAvaliacao.PeriodoTurma.setLargura('100%');
 * @param {string} sLargura
 * @returns void
 */
DBViewAvaliacao.PeriodoTurma.prototype.setLargura = function (sLargura) {

  this.oCboPeriodos.style.width = sLargura;
};



/**
 * Adiciona uma classe css ao Elemento
 * @exemple Deve ser informado o nome da classe.
 *          DBViewAvaliacao.PeriodoTurma.adicionaClasseCSS('<nome_classe>');
 *          DBViewAvaliacao.PeriodoTurma.adicionaClasseCSS('<nome_outra_classe>');
 * @param {string} sClass
 * @returns void
 */
DBViewAvaliacao.PeriodoTurma.prototype.adicionaClasseCSS = function (sClass) {

  this.oCboPeriodos.addClassName(sClass);
};

/**
 * Troca o ID do elemento pelo elemento informado
 * @exemple Deve ser informado a descricao do novo id.
 *          DBViewAvaliacao.PeriodoTurma.trocarID('<nome_id>');
 * @param {string} sID
 * @returns void
 */
DBViewAvaliacao.PeriodoTurma.prototype.trocarID = function (sID) {

  this.oCboPeriodos.id = sId;
};

/**
 * Adiciona uma função para ser executada no event onchange
 * @param fFunction Função que será executada
 * @param sScope    Escopo do objeto que esta setando o callBack
 */
DBViewAvaliacao.PeriodoTurma.prototype.onChangeCallBack = function (fFunction, sScope) {

  this.oCboPeriodos.observe('change',  function() {
    fFunction.call(sScope);
  });
};

/**
 * Remove a propriedade disabled do elemento
 * @returns void
 */
DBViewAvaliacao.PeriodoTurma.prototype.removeDisable = function () {

  this.oCboPeriodos.removeAttribute("disabled");
};

/**
 * Adiciona a propriedade disabled do elemento
 * @returns void
 */
DBViewAvaliacao.PeriodoTurma.prototype.setDisable = function () {

  this.oCboPeriodos.setAttribute("disabled", "disabled");
};


/**
 * Busca os periodos de avaliacao da turma
 */
DBViewAvaliacao.PeriodoTurma.prototype.buscaPeriodos = function () {

  var oSelf                  = this;
  var oParametros            = new Object();
      oParametros.exec       = 'pesquisaPeriodosTurma';
      oParametros.iTurma     = this.iTurma;
      oParametros.iEtapa     = this.iEtapa;
      oParametros.iTipoBusca = this.iTipoBusca;
      

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete = function(oResponse) {
                                      oSelf.retornoBuscaPeriodos(oResponse);
                                    };

  js_divCarregando("Aguarde, pesquisando os períodos de avaliação.", "msgBox");
  new Ajax.Request(this.sUrlRPC, oDadosRequisicao);
};

/**
 * Retorno da busca pelos periodos de avaliacao da turma
 * @param oResponse
 */
DBViewAvaliacao.PeriodoTurma.prototype.retornoBuscaPeriodos = function (oResponse) {

  js_removeObj("msgBox");
  var oSelf    = this;
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.aPeriodos.length > 0) {

    oSelf.oCboPeriodos.add(new Option("Selecione", ""));
    oRetorno.aPeriodos.each(function(oPeriodo, iSeq) {

      var sDescricao    = oPeriodo.sDescricao.urlDecode();
      var tipoAvaliacao = 'A';
      
      tipoAvaliacao = oPeriodo.lResultado ? 'R' : 'A';
      if (oPeriodo.lResultado && oPeriodo.lResultadoFinal) {
        
        tipoAvaliacao = "RF";
        sDescricao    = "Resultado Final"
      }
      
      var oOption = new Option(sDescricao, oPeriodo.iPeriodo);
      oOption.setAttribute('ordem', oPeriodo.iOrdem);
      oOption.setAttribute('tipo', tipoAvaliacao);
      oOption.setAttribute('periodoavaliacao', oPeriodo.iPeriodoAvaliacao);
      oSelf.oCboPeriodos.add(oOption);
      
    });
  }
};

/**
 * Retorna o atributo ordem de todos options selecionados
 * @returns {Array}
 */
DBViewAvaliacao.PeriodoTurma.prototype.getOrdemSelecionados = function() {

  var aSelecionados = new Array();
  var iOptions = this.oCboPeriodos.options.length;
  for (var i = 0; i < iOptions; i++) {

    if (this.oCboPeriodos.options[i].selected) {
      aSelecionados.push(this.oCboPeriodos.options[i].getAttribute('ordem'));
    }
  }

  return aSelecionados;
};

/**
 * Retorna o atributo tipo de todos options selecionados
 * @returns {Array}
 */
DBViewAvaliacao.PeriodoTurma.prototype.getTipoPeriodoSelecionados = function() {

  var aSelecionados = new Array();
  var iOptions = this.oCboPeriodos.options.length;
  for (var i = 0; i < iOptions; i++) {

    if (this.oCboPeriodos.options[i].selected) {
      aSelecionados.push(this.oCboPeriodos.options[i].getAttribute('tipo'));
    }
  }

  return aSelecionados;
};

/**
 * Retorna um array com o atributo periodoavaliacao dos períodos selecionados
 * @returns {Array}
 */
DBViewAvaliacao.PeriodoTurma.prototype.getPeriodoAvaliacaoSelecionados = function() {

  var aSelecionados = new Array();
  var iOptions = this.oCboPeriodos.options.length;
  for (var i = 0; i < iOptions; i++) {

    if (this.oCboPeriodos.options[i].selected) {
      aSelecionados.push(this.oCboPeriodos.options[i].getAttribute('periodoavaliacao'));
    }
  }

  return aSelecionados;
};

/**
 * Retorna o Value de todos options selecionados
 * @returns {Array}
 */
DBViewAvaliacao.PeriodoTurma.prototype.getSelecionados = function() {

  var aSelecionados = new Array();
  var iOptions = this.oCboPeriodos.options.length;
  for (var i = 0; i < iOptions; i++) {

    if (this.oCboPeriodos.options[i].selected) {
      aSelecionados.push(this.oCboPeriodos.options[i].value);
    }
  }

  return aSelecionados;
};

/**
 * Renderiza o elemento html
 * @param oElement
 * @returns void
 */
DBViewAvaliacao.PeriodoTurma.prototype.show = function(oElement) {

  this.buscaPeriodos();
  oElement.appendChild(this.oCboPeriodos);
};

/**
 * Seta um valor do select
 * @param string sValor
 */
DBViewAvaliacao.PeriodoTurma.prototype.setValor = function(sValor) {
  this.oCboPeriodos.value = sValor;
};

/**
 * Percorre os itens do select, removendo a selecao
 */
DBViewAvaliacao.PeriodoTurma.prototype.removerSelecao = function() {

  var iOptions = this.oCboPeriodos.options.length;
  for (var i = 0; i < iOptions; i++) {
    this.oCboPeriodos.options[i].selected = false;
  }
};

/**
 * Define os tipos de períodos que seram apresentados. 
 * Possíveis valores
 * 1 - AVALIACAO_RESULTADO       -> Todos Períodos de avaliação + resultados (todos)
 * 2 - AVALIACAO                 -> Somente Períodos de avaliação 
 * 3 - RESULTADO                 -> Somente Resultados
 * 4 - AVALIACAO_RESULTADO_FINAL -> Todos Períodos de avaliação + resultado final
 * @param {type} iTipo
 * @returns {undefined}
 */
DBViewAvaliacao.PeriodoTurma.prototype.defineTipoBusca = function(iTipo) {
  this.iTipoBusca = iTipo;
}