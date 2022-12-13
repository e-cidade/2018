require_once("scripts/strings.js");
require_once("estilos/grid.style.css");
require_once("scripts/datagrid.widget.js");
require_once("scripts/widgets/DBTreeView.widget.js");

const MSG_DBVIEWCLASSIFICACAOTURMA = "educacao.escola.DBViewClassificacaoTurma."; 

/**
 * Gera uma view para classificar ou remover classificação das turmas selecionadas (gerar/cancelar numeração dos alunos)  
 * 
 * @param {Boolean} lClassificar
 * @returns {DBViewClassificacaoTurma}
 */
DBViewClassificacaoTurma = function (lClassificar) {
  
  this.lClassificar = lClassificar;
  
  this.oContainer      = new Element("div");
  this.oFieldContainer = new Element("fieldset", {'id':'DBViewClassificarTurmas', 
                                                  'style':'height:600px; display:block; clear:both;'});
  
  /**
   * Elemento HTML de legenda
   * @var HTMLElement
   */
  var sLabel = "Gerar Numeração";
  if ( !this.lClassificar ) {
    sLabel = "Cancelar Numeração";
  }
  
  this.oLegendContainer = new Element("legend").update(sLabel);
  if ( !this.lClassificar ) {
    this.oLegendContainer = new Element("legend").update(sLabel);
  }
  
  this.oFieldContainer.appendChild(this.oLegendContainer);
  
  this.oBotao = new Element('input', {'id':'processarClassificacao',
                                      'type'  : 'button',
                                      'name'  : 'processarClassificacao',
                                      'value' : sLabel  
                                     }); 
  
  this.oContainer.appendChild(this.oFieldContainer);
  this.oContainer.appendChild(this.oBotao);
  
};

/**
 * Monta o container
 * @retun {HTMLElement} oConteinerDados
 */
DBViewClassificacaoTurma.prototype.geraContainer= function () {
  
  var sStyleTreeView      = 'padding:0px; text-align:left; height:528px; width:25%; float:left; ';
  var sStyleListaConteudo = 'padding:0px; height:80%; width: 73%; float:right; ';
  
  var oConteinerDados = new Element('div');
  var sConteudo  = '<div >                                                                   \n';
    sConteudo   += '  <fieldset class="separator text-left" >';
    sConteudo   += '    <legend><b>Opcões</b></legend>';
    sConteudo   += '    <label><b>Mostrar Trocas de Turma:</b></label>              ';
    sConteudo   += '    <select id="trocaTurma" name="TrocaTurma">                  ';
    sConteudo   += '      <option value="1" selected>Não</option>                   ';
    sConteudo   += '      <option value="2" >Sim</option>                           ';
    sConteudo   += '    </select>                                                   ';
    sConteudo   += '  </fielset>                                                    ';
    sConteudo   += '</div>                                                        ';
    sConteudo   += '<div id="ctnTreeView" style="'+sStyleTreeView+'">                                       ';
    sConteudo   += '</div>                                                        ';
    sConteudo   += '<div style="'+sStyleListaConteudo+'">                        ';
    sConteudo   += '  <fieldset class="text-left height=100%" >                    ';
    sConteudo   += '    <legend>Alunos Turma</legend>                            ';
    sConteudo   += '    <div id="ctnAlunosTurma">  </div>                              ';
    sConteudo   += '  </fielset>                                                 ';
    sConteudo   += '</div>                                                        ';
    
  oConteinerDados.innerHTML = sConteudo;
  return oConteinerDados; 
};

/**
 * Busca os dados da TreeView
 * @return {void}
 */
DBViewClassificacaoTurma.prototype.criarTreeView = function () {
  
  $('ctnTreeView').innerHTML = '';
  this.oTreeViewAvaliacao    = new DBTreeView('treeViewAvaliacao');
  this.oTreeViewAvaliacao.allowFind(true);
  this.oTreeViewAvaliacao.setFindOptions('matchedonly');
  this.oTreeViewAvaliacao.show($('ctnTreeView'));
  oNoPrincipal = this.oTreeViewAvaliacao.addNode("0", "Escola");
  
  var oSelf = this;
  
  var oParametro                = {};
  oParametro.exec               = 'getDadosDiario';
  oParametro.lProgressaoParcial = false;
  
  var oRequest        = {};
  oRequest.method     = 'post',
  oRequest.parameters = 'json='+Object.toJSON(oParametro),
  oRequest.onComplete = function (oAjax) {
    oSelf.montaTreeView(oAjax);
  };
  
  js_divCarregando( _M(MSG_DBVIEWCLASSIFICACAOTURMA+ "aguarde_buscando_turmas") , "msgBoxA");
  new Ajax.Request('edu4_lancamentoavaliacao.RPC.php', oRequest);
};

/**
 * Monta a treeView 
 * @return {void}
 */
DBViewClassificacaoTurma.prototype.montaTreeView = function(oAjax) {
  
  js_removeObj("msgBoxA");
  var oRetorno = eval('('+oAjax.responseText+')');
  var oSelf    = this;
  
  if(oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
  } else {
    
    oCheck = function (oNode, event) {

      if (oNode.checkbox.checked) {
        oNode.checkAll(event);
        
      } else {
        oNode.uncheckAll(event);
      }
    };
    
    var oCheckBox      = new Object();
    oCheckBox.onClick  = oCheck;
    oCheckBox.checked  = false;
    oCheckBox.disabled = false;
    
    if (oSelf.lProgressaoParcial) {
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

        oSelf.oTreeViewAvaliacao.addNode(oEnsino.sIdEnsino.urlDecode(),
                                      oEnsino.sDescricaoEnsino.urlDecode(),
                                      oCalendario.sIdCalendario,
                                      '',
                                      '',
                                      oCheckBox,
                                      null,
                                      {'lProcessa':false}
                                     );
        
        oEnsino.aBases.each(function(oBase, iSeq) {
          
          oSelf.oTreeViewAvaliacao.addNode(oBase.sIdBase.urlDecode(),
                                        oBase.sDescricaoBase.urlDecode(),
                                        oEnsino.sIdEnsino,
                                        '',
                                        '',
                                        oCheckBox,
                                        null,
                                        {'lProcessa':false}
                                       );
          
          oBase.aEtapas.each(function(oEtapa, iSeq) {
            
            oSelf.oTreeViewAvaliacao.addNode(oEtapa.sIdEtapa.urlDecode(),
                                          oEtapa.sDescricaoEtapa.urlDecode(),
                                          oBase.sIdBase,
                                          '',
                                          '',
                                          oCheckBox,
                                          null,
                                          {'lProcessa':false}
                                         );
            
            oEtapa.aTurmas.each(function(oTurma, iSeq) {
              
              var oCheckBoxTurma      = new Object();
              oCheckBoxTurma.disabled = oCheckBox.disabled;
              oCheckBoxTurma.checked  = oCheckBox.checked;
              
              if (oTurma.lEncerrada && oSelf.iEncerra == 1) {
                
                oCheckBoxTurma.disabled = true;
                oCheckBoxTurma.checked  = false;
              } else if (!oTurma.lEncerrada && oSelf.iEncerra == 2) {
                
                if (!oTurma.lEncerradaParcial) {
                  oCheckBoxTurma.disabled = true;
                }
                oCheckBoxTurma.checked  = false;
              }
              
              if (oSelf.lProgressaoParcial) {
                oCheckBoxTurma = '';
              }
              
              if (( oSelf.lClassificar && oTurma.lClassificada ) || ( !oSelf.lClassificar && !oTurma.lClassificada )) {
                oCheckBoxTurma.disabled = true;
              } 
              
              
              oSelf.oTreeViewAvaliacao.addNode(oTurma.sIdTurma.urlDecode(),
                                            oTurma.sDescricaoTurma.urlDecode(),
                                            oEtapa.sIdEtapa,
                                            false,
                                            '',
                                            oCheckBoxTurma,
                                            function(oTurma, Evento) {
                                              oSelf.setCallBackVisualizaDados(oTurma, oEtapa.iEtapa);
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
  }
};


var oTurmaAnterior = '';
var iEtapaAnterior = null;

/**
 * CallBack acionado quando clicado sobre uma turma.
 * @return {void}
 */
DBViewClassificacaoTurma.prototype.setCallBackVisualizaDados = function (oTurma, iEtapa) {
  
  var oParametro          = {};
  oParametro.exec         = 'getAlunosMatriculados';
  oParametro.iCodigoTurma = oTurma.iTurma;
  oParametro.iEtapa       = iEtapa;
  oParametro.lTrocaTurma  = $('trocaTurma').value == 2;
  
  if (oTurmaAnterior != "") {
    oTurmaAnterior.select(false);
  }
  oTurma.select(true);
  oTurmaAnterior = oTurma;
  iEtapaAnterior = iEtapa;
  
  
  var oSelf = this;
  
  var oRequest        = {};
  oRequest.method     = 'post',
  oRequest.parameters = 'json='+Object.toJSON(oParametro),
  oRequest.onComplete = function (oAjax) {
    oSelf.montaGridAlunosTurma(oAjax);
  };
  
  js_divCarregando( _M(MSG_DBVIEWCLASSIFICACAOTURMA+ "aguarde_buscando_alunos"), "msgBoxB");
  
  new Ajax.Request('edu4_encerramentoavaliacao.RPC.php', oRequest);
};

/**
 * Monta o corpo da grid
 * @return {void}
 */
DBViewClassificacaoTurma.prototype.montaGridAlunosTurma = function (oAjax) {
  
  js_removeObj("msgBoxB");
  var oRetorno = eval ("(" + oAjax.responseText + ")");
  
  var oSelf = this;
  this.oGridAlunosTurma.clearAll(true);
  
  oRetorno.aDadosAlunos.each( function (oDadosAluno) {

    var aLinha = [];
    aLinha[0] = oDadosAluno.iCodigoAluno
    aLinha[1] = oDadosAluno.iOrdemAluno
    aLinha[2] = oDadosAluno.sNomeAluno.urlDecode();
    aLinha[3] = oDadosAluno.sSituacao.urlDecode();
    oSelf.oGridAlunosTurma.addRow(aLinha);
  });
  
  oSelf.oGridAlunosTurma.renderRows();
  
};

/**
 * Cria o cabeçalho da grid
 * @return {void}
 */
DBViewClassificacaoTurma.prototype.montaCabecalhoGrid = function () {
  
  this.oGridAlunosTurma = new DBGrid('gridAlunosTurma');
  var aHeadersGrid    = new Array("Código", "Nº", "Nome", "Situação");
  var aCellWidthGrid  = new Array("10%", "8%", "57%", "25%");
  var aCellAlign      = new Array("rigth", "center", "left", "left");

  this.oGridAlunosTurma.nameInstance = 'oGridAlunosTurma';
  this.oGridAlunosTurma.setCellWidth(aCellWidthGrid);
  this.oGridAlunosTurma.setCellAlign(aCellAlign);
  this.oGridAlunosTurma.setHeader(aHeadersGrid);
  this.oGridAlunosTurma.setHeight(450);
  this.oGridAlunosTurma.show($('ctnAlunosTurma'));
  
};

/**
 * Estrutura a visualização da view
 * @param {HTMLElement} oElement   elemento onde será renderizado 
 */
DBViewClassificacaoTurma.prototype.show = function (oElement) {
  
  oElement.appendChild(this.oContainer);
  this.oFieldContainer.appendChild(this.geraContainer());
  this.montaCabecalhoGrid();
  this.criarTreeView();
  
  var oSelf = this;
  this.oBotao.onclick = function () {
    oSelf.processar();
  };
};

/**
 * Busca da treeView os nós selecionados retornand um array com as turmas e etapas
 * @return {aTurma[]} 
 */
DBViewClassificacaoTurma.prototype.buscaTurmasSelecionadas = function () {
  
  var aTurma        = new Array();
  var aSelecionados = this.oTreeViewAvaliacao.getNodesChecked();
  
  aSelecionados.each(function(oNode) {
    
    var oTurma = new Object();
    if (!oNode.checkbox.disabled && oNode.lProcessa) {
      
      oTurma.iTurma = oNode.iTurma;
      oTurma.iEtapa = oNode.iEtapa;
      aTurma.push(oTurma);
    }
  });
  
  return aTurma;
  
};

/**
 * Processa as turmas selecionada 
 *  gerando / cancelando a classificação numerérica da turma
 * @return {void}  
 */
DBViewClassificacaoTurma.prototype.processar = function () {
  
  var oParametros         = {};
  oParametros.aTurmas     = this.buscaTurmasSelecionadas();
  oParametros.iTrocaTurma = $F('trocaTurma');

  if ( oParametros.aTurmas.length == 0 ) {
    
    alert( _M(MSG_DBVIEWCLASSIFICACAOTURMA+ "sem_turma_selecionada") );
    
    return false;
  }
  
  oParametros.exec = 'gerarNumeracao';
  if ( !this.lClassificar ) {
    
    oParametros.exec = 'cancelarNumeracao';
    
    if (!confirm(_M(MSG_DBVIEWCLASSIFICACAOTURMA+ "confirma_cancelamento_numeracao"))) {
      return false;
    }
  }  

  var oSelf           = this;
  var oRequest        = {};
  oRequest.method     = 'post',
  oRequest.parameters = 'json='+Object.toJSON(oParametros),
  oRequest.onComplete = function (oAjax) {

    js_removeObj("msgBoxC"); 
    var oRetorno = eval("(" +oAjax.responseText+ ")");
    alert(oRetorno.message.urlDecode());
    oSelf.oGridAlunosTurma.clearAll(true);
    oSelf.criarTreeView();
    
  };
  
  js_divCarregando(  _M(MSG_DBVIEWCLASSIFICACAOTURMA+ "aguarde_processando") , "msgBoxC");
  new Ajax.Request('edu4_turmas.RPC.php', oRequest);  
};