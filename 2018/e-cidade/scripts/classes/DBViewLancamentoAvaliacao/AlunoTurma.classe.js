require_once("scripts/classes/DBViewLancamentoAvaliacao/DBViewLancamentoAvaliacao.classe.js");

/**
 * Monta um select-multiple com os alunos da turma
 * @dependency Utiliza DBViewLancamentoAvaliacao.classe.js
 * @autor Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @version $Revision: 1.10 $
 *
 * @param {integer} iTurma código da turma
 * @param {integer} iEtapa código da etapa
 * @returns void
 */
DBViewAvaliacao.AlunoTurma = function (iTurma, iEtapa, lMultiple) {

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
   * define se vai buscar alunos encerrados
   * @type {Boolean}
   */
  this.lTrazerAlunosEncerrados = true;

  /**
   * Define se devem ser carregados somente alunos com de origem de fora da rede, ou seja, com matrícula posterior a
   * data de início do primeiro período do calendário
   * @type {boolean}
   */
  this.lSomenteAlunosOrigemForaRede = false;
  
  /**
   * Define se devem ser carregados somente alunos com data de matrícula maior que o primeiro período do calendário
   * @type {boolean}
   */
  this.lMatriculaMaiorPrimeiroPeriodoCalendario = false;

  /**
   * RPC para as requisições
   * @var {string}
   */
  this.sUrlRPC = 'edu4_turmas.RPC.php';

  /**
   * Coleção com os elementos selecionados
   * @type {Array}
   */
  this.aElementosSelecionados = [];

  if (lMultiple == null) {
    lMultiple = false;
  }

  /**
   * Elemento HTML select-multiple
   * @var {HTMLElement}
   */
  this.oCboAlunos              = document.createElement('select');
  this.oCboAlunos.id           = 'alunos#'+iTurma+'#'+iEtapa;
  this.oCboAlunos.multiple     = lMultiple;
  this.oCboAlunos.style.width  = '250px';
};

/**
 * Define a altura do comboBox
 * @exemple Deve ser informado a unidade de medida junto ao valor.
 *          DBViewAvaliacao.AlunoTurma.setAltura('100px');
 *          DBViewAvaliacao.AlunoTurma.setAltura('100%');
 * @param {string} sAltura
 * @returns void
 */
DBViewAvaliacao.AlunoTurma.prototype.setAltura = function (sAltura) {
  this.oCboAlunos.style.height = sAltura;
};

/**
 * Define a largura do comboBox
 * @exemple Deve ser informado a unidade de medida junto ao valor.
 *          DBViewAvaliacao.AlunoTurma.setLargura('100px');
 *          DBViewAvaliacao.AlunoTurma.setLargura('100%');
 * @param {string} sLargura
 * @returns void
 */
DBViewAvaliacao.AlunoTurma.prototype.setLargura = function (sLargura) {
  this.oCboAlunos.style.width = sLargura;
};

/**
 * Adiciona uma classe css ao Elemento
 * @exemple Deve ser informado o nome da classe.
 *          DBViewAvaliacao.AlunoTurma.adicionaClasseCSS('<nome_classe>');
 *          DBViewAvaliacao.AlunoTurma.adicionaClasseCSS('<nome_outra_classe>');
 * @param {string} sClass
 * @returns void
 */
DBViewAvaliacao.AlunoTurma.prototype.adicionaClasseCSS = function (sClass) {
  this.oCboAlunos.addClassName(sClass);
};

/**
 * Troca o ID do elemento pelo elemento informado
 * @exemple Deve ser informado a unidade de medida junto ao valor.
 *          DBViewAvaliacao.AlunoTurma.trocarID('<nome_id>');
 * @param {string} sID
 * @returns void
 */
DBViewAvaliacao.AlunoTurma.prototype.trocarID = function (sID) {
  this.oCboAlunos.id = sId;
};

/**
 * Adiciona uma função para ser executada no event onchange
 * @param fFunction Função que será executada
 * @param sScope    Escopo do objeto que esta setando o callBack
 */
DBViewAvaliacao.AlunoTurma.prototype.onChangeCallBack = function (fFunction, sScope) {

  this.oCboAlunos.observe('change',  function() {
    fFunction.call(sScope);
  });
};

/**
 * Busca os alunos para preenchimento do select
 */
DBViewAvaliacao.AlunoTurma.prototype.buscaAlunos = function () {

  var oSelf                                               = this;
  var oParametros                                         = new Object();
      oParametros.exec                                    = 'pesquisaAlunosTurma';
      oParametros.iTurma                                  = this.iTurma;
      oParametros.iEtapa                                  = this.iEtapa;
      oParametros.lTrazerAlunosEncerrados                 = this.lTrazerAlunosEncerrados;
      oParametros.lSomenteAlunosOrigemForaRede            = this.lSomenteAlunosOrigemForaRede;
      oParametros.lMatriculaMaiorPrimeiroPeriodoCalendario = this.lMatriculaMaiorPrimeiroPeriodoCalendario;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametros);
      oDadosRequisicao.onComplete = function(oResponse) {
                                      oSelf.retornoBuscaAlunos(oResponse);
                                    };

  js_divCarregando("Aguarde, pesquisando os alunos.", "msgBoxA");
  new Ajax.Request(this.sUrlRPC, oDadosRequisicao);
};

/**
 * Retorno da busca dos alunos
 * @param oResponse
 */
DBViewAvaliacao.AlunoTurma.prototype.retornoBuscaAlunos = function (oResponse) {

  js_removeObj("msgBoxA");
  var oSelf    = this;
  var oRetorno = eval('('+oResponse.responseText+')');

  oSelf.oCboAlunos.add(new Option("Selecione", ""));

  if (oRetorno.aAlunos.length > 0) {

    oSelf.oCboAlunos.options[0].setAttribute( 'data_matricula', '' );

    oRetorno.aAlunos.each(function(oAluno, iSeq) {

      oSelf.oCboAlunos.add(new Option(oAluno.sNome.urlDecode(), oAluno.iMatricula));
      oSelf.oCboAlunos.options[ iSeq + 1 ].setAttribute( 'data_matricula', oAluno.dtMatricula.urlDecode() );
    });
  }
};

/**
 * Retorna o Value de todos options selecionados
 * @returns {Array}
 */
DBViewAvaliacao.AlunoTurma.prototype.getSelecionados = function() {

  var aSelecionados                  = new Array();
  var iOptions                       = this.oCboAlunos.options.length;
  this.aElementosSelecionados.length = 0;

  for (var i = 0; i < iOptions; i++) {

    if (this.oCboAlunos.options[i].selected) {

      this.aElementosSelecionados.push( this.oCboAlunos.options[i] );
      aSelecionados.push(this.oCboAlunos.options[i].value);
    }

  }

  return aSelecionados;
};

/**
 * Renderiza o elemento html
 * @param oElement
 * @returns void
 */
DBViewAvaliacao.AlunoTurma.prototype.show = function(oElement) {

  this.buscaAlunos();
  oElement.appendChild(this.oCboAlunos);
};

/**
 * Seta um valor do select
 * @param string sValor
 */
DBViewAvaliacao.AlunoTurma.prototype.setValor = function(sValor) {
  this.oCboAlunos.value = sValor;
};

/**
 * Percorre os itens do select, removendo a selecao
 */
DBViewAvaliacao.AlunoTurma.prototype.removerSelecao = function() {

  var iOptions = this.oCboAlunos.options.length;
  for (var i = 0; i < iOptions; i++) {
    this.oCboAlunos.options[i].selected = false;
  }
};

/**
 * Retorna um array com os elementos selecionados
 * @returns {Array}
 */
DBViewAvaliacao.AlunoTurma.prototype.getElementosSelecionados = function() {

  this.getSelecionados();
  return this.aElementosSelecionados;
};

/**
 * Seta se devem ser apresentados somente alunos com origem fora da rede, ou seja, com matrícula posterior a data de
 * início ao primeiro período do calendário
 * @param lSomenteAlunosOrigemForaRede
 */
DBViewAvaliacao.AlunoTurma.prototype.somenteAlunosOrigemForaRede = function( lSomenteAlunosOrigemForaRede ) {
  this.lSomenteAlunosOrigemForaRede = lSomenteAlunosOrigemForaRede;
};

DBViewAvaliacao.AlunoTurma.prototype.matriculaMaiorPrimeiroPeriodoCalendario = function( lMatriculaMaiorPrimeiroPeriodoCalendario ) {
  this.lMatriculaMaiorPrimeiroPeriodoCalendario = lMatriculaMaiorPrimeiroPeriodoCalendario;
};