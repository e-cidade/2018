require_once("scripts/arrays.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/DBViewLancamentoAvaliacao.classe.js");

/**
 * Monta um select-multiple com as disiciplinas da turma
 * @dependency Utiliza DBViewLancamentoAvaliacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.4 $
 *
 * @param {integer} iTurma código da turma 
 * @param {integer} iEtapa código da etapa
 * @param {boolean} lDisciplinasProfessor valida se o usuário logado é professor e trás somente as disciplinas que
 *                                        este leciona na turma.
 * @returns void
 */
DBViewAvaliacao.DisciplinaTurma = function (iTurma, iEtapa, lDisciplinasProfessor) {
  
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
   * valida se o usuário logado é professor e trás somente as disciplinas que este leciona na turma.
   * @var {boolean}
   */
  this.lDisciplinasProfessor = lDisciplinasProfessor;
  
  /**
   * RPC para as requisições
   * @var {string}
   */
  this.sUrlRPC = 'edu4_turmas.RPC.php';
  
  /**
   * Lista de disciplinas que não devem ser listadas no select
   * @var {Array}
   */
  this.aNaoListar = [];
  
  /**
   * Elemento HTML select-multiple
   * @var {HTMLElement}
   */
  this.oCboDisciplinas              = document.createElement('select');
  this.oCboDisciplinas.id           = 'disciplinas#'+iTurma+'#'+iEtapa;
  this.oCboDisciplinas.multiple     = true;
  this.oCboDisciplinas.style.height = '100px';
  this.oCboDisciplinas.style.width  = '250px';

  this.mSomenteDisciplinasParecer = null;
};

/**
 * Define a altura do comboBox
 * @exemple Deve ser informado a unidade de medida junto ao valor.
 *          DBViewAvaliacao.DisciplinaTurma.setAltura('100px');
 *          DBViewAvaliacao.DisciplinaTurma.setAltura('100%');
 * @param {string} sAltura  
 * @returns void          
 */
DBViewAvaliacao.DisciplinaTurma.prototype.setAltura = function (sAltura) {
  this.oCboDisciplinas.style.height = sAltura;
};


/**
 * Define a largura do comboBox
 * @exemple Deve ser informado a unidade de medida junto ao valor.
 *          DBViewAvaliacao.DisciplinaTurma.setLargura('100px');
 *          DBViewAvaliacao.DisciplinaTurma.setLargura('100%');
 * @param {string} sLargura  
 * @returns void          
 */
DBViewAvaliacao.DisciplinaTurma.prototype.setLargura = function (sLargura) {
  this.oCboDisciplinas.style.width = sLargura;
};



/**
 * Adiciona uma classe css ao Elemento
 * @exemple Deve ser informado o nome da classe. 
 *          DBViewAvaliacao.DisciplinaTurma.adicionaClasseCSS('<nome_classe>');
 *          DBViewAvaliacao.DisciplinaTurma.adicionaClasseCSS('<nome_outra_classe>');
 * @param {string} sClass
 * @returns void
 */
DBViewAvaliacao.DisciplinaTurma.prototype.adicionaClasseCSS = function (sClass) {
  this.oCboDisciplinas.addClassName(sClass);
};

/**
 * Troca o ID do elemento pelo elemento informado
 * @exemple Deve ser informado a unidade de medida junto ao valor.
 *          DBViewAvaliacao.DisciplinaTurma.trocarID('<nome_id>');
 * @param {string} sID
 * @returns void
 */
DBViewAvaliacao.DisciplinaTurma.prototype.trocarID = function (sID) {
  this.oCboDisciplinas.id = sId;
};


/**
 * Realiza uma requisição para buscar as Disciplinas da Turma e Etapa
 */
DBViewAvaliacao.DisciplinaTurma.prototype.buscaDisciplinas = function() {
  
  var oSelf = this;
  
  var oParametro                   = new Object();
  oParametro.exec                  = 'getRegencias';
  oParametro.iTurma                = this.iTurma;
  oParametro.iEtapa                = this.iEtapa;
  oParametro.lDisciplinasProfessor = this.lDisciplinasProfessor;

  if( this.mSomenteDisciplinasParecer !== null ) {
    oParametro.lSomenteDisciplinasParecer = this.mSomenteDisciplinasParecer;
  }

  var oObjeto        = new Object();
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                         oSelf.retornoDisciplinas(oAjax);
                       };
  js_divCarregando("Aguarde, carregando disciplinas.", "msgBox");
  new Ajax.Request(this.sUrlRPC, oObjeto);
};

/**
 * Cria os options dentro do select conforme retorno de buscaDisciplinas()
 * @param oAjax
 * @returns void
 */
DBViewAvaliacao.DisciplinaTurma.prototype.retornoDisciplinas = function (oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')'); 
  var oSelf    = this;
  
  oRetorno.aRegencias.each( function (oRegencia) {
    
    var oOption       = document.createElement('option');
    oOption.value     = oRegencia.iRegencia;
    oOption.innerHTML = oRegencia.sDisciplina.urlDecode();
    
    /**
     * valida se existe alguma disciplina que não deve ser listada
     */
    if (oSelf.aNaoListar.length == 0) {
      oSelf.oCboDisciplinas.appendChild(oOption);
    } else if (!oSelf.aNaoListar.in_array(oRegencia.iRegencia)) {
      oSelf.oCboDisciplinas.appendChild(oOption);
    }
  }); 
};

/**
 * Retorna o Value de todos options selecionados
 * @returns {Array}
 */
DBViewAvaliacao.DisciplinaTurma.prototype.getSelecionados = function() {
  
  var aSelecionados = [];
  var iOptions = this.oCboDisciplinas.options.length; 
  for (var i = 0; i < iOptions; i++) {
    
    if (this.oCboDisciplinas.options[i].selected) {
      aSelecionados.push(this.oCboDisciplinas.options[i].value);
    } 
  } 

  return aSelecionados;
};

/**
 * Renderiza o elemento html
 * @param oElement
 * @returns void
 */
DBViewAvaliacao.DisciplinaTurma.prototype.show = function(oElement) {
  
  this.buscaDisciplinas();
  oElement.appendChild(this.oCboDisciplinas);
  
};

/**
 * Define uma lista de disciplinas que não devem serem listadas
 * @param {Array} aRegencia
 */
DBViewAvaliacao.DisciplinaTurma.prototype.naoListarAsRegencias = function (aRegencia) {
  this.aNaoListar = aRegencia;
};

/**
 * Seta um valor para o select
 * @param sValor
 */
DBViewAvaliacao.DisciplinaTurma.prototype.setValor = function (sValor) {
  this.oCboDisciplinas.value = sValor;
};

/**
 * Percorre os itens do select, removendo a selecao
 */
DBViewAvaliacao.DisciplinaTurma.prototype.removerSelecao = function() {
  
  var iOptions = this.oCboDisciplinas.options.length; 
  for (var i = 0; i < iOptions; i++) {
    this.oCboDisciplinas.options[i].selected = false;
  }
};

/**
 * Seta se devem ser apresentadas somente disciplinas com procedimento de avaliação do tipo PARECER
 * @param {bool} lSomenteDisciplinasParecer
 */
DBViewAvaliacao.DisciplinaTurma.prototype.somenteDisciplinasParecer = function( lSomenteDisciplinasParecer ) {
  this.mSomenteDisciplinasParecer = lSomenteDisciplinasParecer;
};