/**
 * Criar uma view com uma TreeView com a �rvore das turmas. 
 * Conforme exemplo abaixo:
 *  
 * Escola
 * |_Calendario
 *    |_Ensino
 *      |_Base
 *        |_Etapa
 *          |_Turma
 *          
 * @param {string} sId Id que identifica o objeto 
 */
DBViewArvoreTurma = function(sId) {
  
  
  /**
   * ID do Objeto
   * @var String
   */
  this.id = sId;
  
  /**
   * RPC para executar as chamadas necess�rias para a cria��o da arvore
   * @var String
   */
  this.sRPC = 'edu4_lancamentoavaliacao.RPC.php';
  
  /**
   * propriedade de controle para a arvore possuir checkbox
   * @var Boolean
   */
  this.lTemCheckBox = false;
 
  /**
   * Propriedade de controle para retornar apenas turmas de progressao parcial
   * @var boolean
   */
  this.lTurmasProgressaoParcial = false;

  /**
   * Caso o login seja de um professor:
   * ... se TRUE, s� ir� trazer as turmas que o professor possuir vinculo
   * ... se FALSE, ir� trazer todas turmas da escola
   * @type {Boolean}
   */
  this.lValidarProfessorLogado = true;

  /**
   * Valida se devemos verificar se h� alunos matriculados na turma.
   * .. Matricula com situa��o = MATRICULADO
   * @type {Boolean}
   */
  this.lTemAlunosMatriculados = true;

  /**
   * Define se devemos filrar as turmas de acordo com o crit�rio de avalia��o informado
   * Obs.: Deve ser informado a propriedade: this.iCriterio
   * @type {Boolean}
   */
  this.lFiltrarCriterioAvaliacao = false;

  /**
   * C�digo do crit�rio de avalia��o
   * @type {integer}
   */
  this.iCriterio = null;

  /**
   * Funcao padrao para o clique do checkbox, onde ira selecionar todos os itens recursivamente da arvore.
   * 
   * @var {function} fCallBackCheckBox nome da variavel que guarda a funcao de callBack para o check box
   * 
   * @param DBNodeTree oNode no da TreeView
   * @param Event event evento HTML 
   */
  this.fCallBackCheckBox = function (oNode, event) {

    if (oNode.checkbox.checked) {
      oNode.checkAll(event);
    } else {
      oNode.uncheckAll(event);
    }
  };

  /**
   * Funcao callBack da turma.
   * @var {function} fCallBackTurma 
   */
  this.fCallBackTurma = function() {}; 
  
  /**
   * Callback executado apos o load dos dados
   */
  this.fCallBackAfterLoad = function() {};

  /**
   * Array das disciplinas para valida��o
   * @type {Array}
   */
  this.aDisciplinas = new Array();

  /**
   * Fun��o callback respons�vel por marcar as turmas vinculadas
   * @return {function} fCallbackMarcarTurma
   */
  this.fCallbackMarcarTurma = function() {
    return false;
  }

  /**
   * Array com os n�s que devem ser expandidos
   * @type {Array}
   */
  this.aNoExpandir = new Array();

  /**
   * Controla se os n�s pai de item selecionados, devem vir expandidos
   * @type {boolean}
   */
  this.lExpandirNoPaiSelecionado = false;
}

/**
 * Define se a arvore devera ter checkbox em cada nivel
 * A fun��o padr�o, caso tenha checkbox, esta definida pela var {this.fCallBackCheckBox}. 
 * @param boolean lMostrar   - true / false para exibi��o do check box 
 * @param function fCallBack - funcao para validacao se o checkbox devera estar desabilitado
 */
DBViewArvoreTurma.prototype.setCheckBox = function (lMostrar, fCallBack) {
  
  this.lTemCheckBox = lMostrar;
  if (lMostrar && typeof(fCallBack) == "function") {
    this.fCallBackCheckBox = fCallBack;
  }
};

/**
 * Define a fun��o de callback para o clique na turma. 
 * @param function fFunction funcao para acao no clique no n� da turma
 */
DBViewArvoreTurma.prototype.setCallBackCliqueTurma = function (fFunction) {
  
  if (typeof(fFunction) == "function") {
    this.fCallBackTurma = fFunction;
  }
}

/**
 * Busca e exibe a �rvore de Turmas
 * @param {ElementHTML} oContainer onde ser� renderizado a �rvore
 */
DBViewArvoreTurma.prototype.show = function(oContainer) {
  
  this.oTreeViewAvaliacao = new DBTreeView('treeViewAvaliacao'+this.id);
  this.oTreeViewAvaliacao.allowFind(true);
  this.oTreeViewAvaliacao.setFindOptions('matchedonly');
  this.oTreeViewAvaliacao.show(oContainer);
  
  var oSelf = this;
  
  var oParametro                       = new Object();
  oParametro.exec                      = 'getDadosDiario';
  oParametro.lProgressaoParcial        = this.lTurmasProgressaoParcial;
  oParametro.lValidarProfessorLogado   = this.lValidarProfessorLogado;
  oParametro.lTemAlunosMatriculados    = this.lTemAlunosMatriculados;
  oParametro.lFiltrarCriterioAvaliacao = this.lFiltrarCriterioAvaliacao;
  oParametro.iCriterio                 = this.iCriterio;
  oParametro.aDisciplinas              = this.aDisciplinas;

  js_divCarregando("Aguarde, carregando ...", "msgBox");

  new Ajax.Request('edu4_lancamentoavaliacao.RPC.php',
                   { method:     'post',
                     asynchronous:true,
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: function(oAjax) {
                                   oSelf.retornoDadosArvore(oAjax);
                                 }
                   }
                  );
};

/**
 * Preenche os n�s da TreeView conforme retorno dos dados
 * @param oAjax
 * @private
 */
DBViewArvoreTurma.prototype.retornoDadosArvore = function(oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');
  
  this.oTreeViewAvaliacao.addNode("0", "Escola");
  
  var oSelf = this;
  
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  } else {


    var fCheckBoxPadrao = function(oNode, event) {
      if (oNode.checkbox.checked) {
        oNode.checkAll(event);
      } else {
        oNode.uncheckAll(event);
      }
    }

    /**
     * OBS.: 1 - Refatorado l�gica que proibia Turmas de progress�o parcial com checkbox habilitado
     *       2 - Movido a cria��o do objeto Checkbox para dentro de cada each, pois estava se perdendo nas referencias
     */
    oRetorno.dados.each(function(oCalendario, iSeq) {

      oSelf.oTreeViewAvaliacao.addNode(oCalendario.sIdCalendario,
                                       oCalendario.sDescricaoCalendario.urlDecode(),
                                       '0',
                                       '',
                                       '',
                                       oSelf.novoObjetoCheckbox(),
                                       null,
                                       {'lProcessa':false}
                                      );

      oCalendario.aEnsinos.each(function(oEnsino, iSeq) {

        oSelf.oTreeViewAvaliacao.addNode(oEnsino.sIdEnsino,
                                         oEnsino.sDescricaoEnsino.urlDecode(),
                                         oCalendario.sIdCalendario,
                                         '',
                                         '',
                                         oSelf.novoObjetoCheckbox(),
                                         null,
                                         {'lProcessa':false}
                                        );

        oEnsino.aBases.each(function(oBase, iSeq) {

          oSelf.oTreeViewAvaliacao.addNode(oBase.sIdBase,
                                           oBase.sDescricaoBase.urlDecode(),
                                           oEnsino.sIdEnsino,
                                           '',
                                           '',
                                           oSelf.novoObjetoCheckbox(),
                                           null,
                                           {'lProcessa':false}
                                          );

          oBase.aEtapas.each(function(oEtapa, iSeq) {

            oSelf.oTreeViewAvaliacao.addNode(oEtapa.sIdEtapa,
                                             oEtapa.sDescricaoEtapa.urlDecode(),
                                             oBase.sIdBase,
                                             '',
                                             '',
                                             oSelf.novoObjetoCheckbox(),
                                             null,
                                             {'lProcessa':false}
                                            );

            oEtapa.aTurmas.each(function(oTurma, iSeq) {

              var oCheckBoxTurma = oSelf.novoObjetoCheckbox();

              oCheckBoxTurma.checked = oSelf.fCallbackMarcarTurma( oTurma, oEtapa );

              if ( oTurma.lEncerrada ) {
                oCheckBoxTurma.disabled = true;
              }

              var oNode = oSelf.oTreeViewAvaliacao.addNode(oTurma.sIdTurma,
                                                           oTurma.sDescricaoTurma.urlDecode(),
                                                           oEtapa.sIdEtapa,
                                                           false,
                                                           '',
                                                           oCheckBoxTurma,
                                                           function(oNoTurma, Evento) {
                                                             oSelf.fCallBackTurma(oNoTurma, oEtapa.iEtapa);
                                                           },
                                                           {'lProcessa':true,'iTurma':oTurma.iTurma.urlDecode(),
                                                            'iEtapa':oEtapa.iEtapa
                                                           }
                                                          );

              if ( oCheckBoxTurma.checked ) {
                oSelf.aNoExpandir.push( oNode.parentNode );
              }
            });
          });
        });
      });
    });

    this.fCallBackAfterLoad(oRetorno);

    if ( oSelf.lExpandirNoPaiSelecionado ) {

      oSelf.aNoExpandir.each(function( oItem ) {
        oItem.expand( null, true );
      });
    }
  }
};

/**
 * Callback chamado apos o retorno da requisicao ajax
 * @param fFunction
 */
DBViewArvoreTurma.prototype.afterLoad = function(fFunction) {
  this.fCallBackAfterLoad = fFunction;
};


/**
 * Define se devemos validar usu�rio logado como professor
 * ... se TRUE, s� ir� trazer as turmas que o professor possuir vinculo
 * ... se FALSE, ir� trazer todas turmas da escola
 *
 * @default true
 * @param {Boolean} lSim
 */
DBViewArvoreTurma.prototype.validarProfessorLogado = function (lSim) {

  this.lValidarProfessorLogado = lSim;
};


/**
 * Define se devemos verificar se h� alunos matriculados na turma
 *
 * @default true
 * @param {Boolean} lSim
 */
DBViewArvoreTurma.prototype.temAlunosMatriculados = function (lSim) {

  this.lTemAlunosMatriculados = lSim;
};

/**
 * Se devemos filtar turmas compativeis com um crit�rio de avalia��o especifico
 * @param {Booleam} lSim
 * @param {integer} iCriterio c�digo do criterio
 */
DBViewArvoreTurma.prototype.filtrarCriterioAvaliacao = function (lSim, iCriterio) {

  this.lFiltrarCriterioAvaliacao = lSim;
  if ( lSim ) {
    this.iCriterio = iCriterio;
  }
};

/**
 * Cria o objeto para controle dos checbox na �rvore
 * @return {}
 */
DBViewArvoreTurma.prototype.novoObjetoCheckbox = function () {

  var oCheckBox = '';

  if ( this.lTemCheckBox && !this.lTurmasProgressaoParcial ) {

    oCheckBox          = new Object();
    oCheckBox.onClick  = this.fCallBackCheckBox;
    oCheckBox.checked  = false;
    oCheckBox.disabled = false;
  }

  return oCheckBox;
}

/**
 * Adiciona uma disciplina ao array
 * @param integer iDisciplina
 */
DBViewArvoreTurma.prototype.adicionarDisciplina = function( iDisciplina ) {

  if( !js_search_in_array( this.aDisciplinas, iDisciplina ) ) {
    this.aDisciplinas.push( iDisciplina );
  }
};

/**
 * Valida as turmas que devem vir marcadas
 * @param  Turma oTurma
 * @param  Etapa oEtapa
 */
DBViewArvoreTurma.prototype.setCallbackMarcarTurma = function(sFunction) {
  this.fCallbackMarcarTurma = sFunction;
};

DBViewArvoreTurma.prototype.expandirNoPaiSelecionado = function( lExpandirNoPaiSelecionado ) {
  this.lExpandirNoPaiSelecionado = lExpandirNoPaiSelecionado;
};