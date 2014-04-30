require_once("scripts/classes/DBViewLancamentoAvaliacao/DBViewLancamentoAvaliacao.classe.js");

/**
 * Monta um select-multiple com os alunos da turma
 * @dependency Utiliza DBViewLancamentoAvaliacao.classe.js
 * @autor Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @version $Revision: 1.6 $
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
   * RPC para as requisições
   * @var {string}
   */
  this.sUrlRPC = 'edu4_turmas.RPC.php';
  
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
  
  var oSelf                 = this;
  var oParametros           = new Object();
      oParametros.exec      = 'pesquisaAlunosTurma';
      oParametros.iTurma    = this.iTurma;
      oParametros.iEtapa    = this.iEtapa;
      
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
  
  if (oRetorno.aAlunos.length > 0) {
  
    oSelf.oCboAlunos.add(new Option("Selecione", ""));
    oRetorno.aAlunos.each(function(oAluno) {
      oSelf.oCboAlunos.add(new Option(oAluno.sNome.urlDecode(), oAluno.iMatricula));
    });
  }
};

/**
 * Retorna o Value de todos options selecionados
 * @returns {Array}
 */
DBViewAvaliacao.AlunoTurma.prototype.getSelecionados = function() {
  
  var aSelecionados = new Array();
  var iOptions = this.oCboAlunos.options.length; 
  for (var i = 0; i < iOptions; i++) {
    
    if (this.oCboAlunos.options[i].selected) {
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