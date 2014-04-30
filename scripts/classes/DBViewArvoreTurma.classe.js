/**
 * Criar uma view com uma TreeView com a árvore das turmas. 
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
   * RPC para executar as chamadas necessárias para a criação da arvore
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
}

/**
 * Define se a arvore devera ter checkbox em cada nivel
 * A função padrão, caso tenha checkbox, esta definida pela var {this.fCallBackCheckBox}. 
 * @param boolean lMostrar   - true / false para exibição do check box 
 * @param function fCallBack - funcao para validacao se o checkbox devera estar desabilitado
 */
DBViewArvoreTurma.prototype.setCheckBox = function (lMostrar, fCallBack) {
  
  this.lTemCheckBox = lMostrar;
  if (lMostrar && typeof(fCallBack) == "function") {
    this.fCallBackCheckBox = fCallBack;
  }
};

/**
 * Define a função de callback para o clique na turma. 
 * @param function fFunction funcao para acao no clique no nó da turma
 */
DBViewArvoreTurma.prototype.setCallBackCliqueTurma = function (fFunction) {
  
  if (typeof(fFunction) == "function") {
    this.fCallBackTurma = fFunction;
  }
}

/**
 * Busca e exibe a árvore de Turmas
 * @param Element oContainer onde será renderizado a árvore 
 */
DBViewArvoreTurma.prototype.show = function(oContainer) {
  
  this.oTreeViewAvaliacao = new DBTreeView('treeViewAvaliacao'+this.id);
  this.oTreeViewAvaliacao.allowFind(true);
  this.oTreeViewAvaliacao.setFindOptions('matchedonly');
  this.oTreeViewAvaliacao.show(oContainer);
  
  var oSelf = this;
  
  var oParametro                = new Object();
  oParametro.exec               = 'getDadosDiario';
  oParametro.lProgressaoParcial = this.lTurmasProgressaoParcial;
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
 * Preenche os nós da TreeView conforme retorno dos dados
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
    
    var oCheckBox = '';
    if (this.lTemCheckBox) {
    
      oCheckBox          = new Object();
      oCheckBox.onClick  = this.fCallBackCheckBox;
      oCheckBox.checked  = false;
      oCheckBox.disabled = false;
    }
    
    if (this.lTurmasProgressaoParcial) {
      oCheckBox = '';
    }
    
    oRetorno.dados.each(function(oCalendario, iSeq) {
      
      oSelf.oTreeViewAvaliacao.addNode(oCalendario.sIdCalendario,
                                       oCalendario.sDescricaoCalendario.urlDecode(),
                                       '0',
                                       '',
                                       '',
                                       oCheckBox,
                                       null,
                                       {'lProcessa':false}
                                      );
      
      oCalendario.aEnsinos.each(function(oEnsino, iSeq) {

        oSelf.oTreeViewAvaliacao.addNode(oEnsino.sIdEnsino,
                                         oEnsino.sDescricaoEnsino.urlDecode(),
                                         oCalendario.sIdCalendario,
                                         '',
                                         '',
                                         oCheckBox,
                                         null,
                                         {'lProcessa':false}
                                        );
        
        oEnsino.aBases.each(function(oBase, iSeq) {
          
          oSelf.oTreeViewAvaliacao.addNode(oBase.sIdBase,
                                           oBase.sDescricaoBase.urlDecode(),
                                           oEnsino.sIdEnsino,
                                           '',
                                           '',
                                           oCheckBox,
                                           null,
                                           {'lProcessa':false}
                                          );
          
          oBase.aEtapas.each(function(oEtapa, iSeq) {
            
            oSelf.oTreeViewAvaliacao.addNode(oEtapa.sIdEtapa,
                                             oEtapa.sDescricaoEtapa.urlDecode(),
                                             oBase.sIdBase,
                                             '',
                                             '',
                                             oCheckBox,
                                             null,
                                             {'lProcessa':false}
                                            );
            
            oEtapa.aTurmas.each(function(oTurma, iSeq) {
              
              oSelf.oTreeViewAvaliacao.addNode(oTurma.sIdTurma,
                                               oTurma.sDescricaoTurma.urlDecode(),
                                               oEtapa.sIdEtapa,
                                               false,
                                               '',
                                               oCheckBox,
                                               function(oNoTurma, Evento) {
                                                 oSelf.fCallBackTurma(oNoTurma, oEtapa.iEtapa);
                                               },
                                               {'lProcessa':true,'iTurma':oTurma.iTurma.urlDecode(),
                                                'iEtapa':oEtapa.iEtapa
                                               }
                                              );
            });
          });
        });
      });
    });
    this.fCallBackAfterLoad(oRetorno);
  }
};

/**
 * Callback chamado apos o retorno da requisicao ajax
 * @param fFunction
 */
DBViewArvoreTurma.prototype.afterLoad = function(fFunction) {
  this.fCallBackAfterLoad = fFunction;
}