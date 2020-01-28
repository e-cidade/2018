/**
 * Classe para salvar/excluir abonos de faltas dos alunos por disciplina e periodo
 * @param iTurma
 * @param iEtapa
 * @param sDisciplina
 * @returns {DBViewAbonoFalta}
 */
DBViewAbonoFalta = function( oDadosTurma, iRegencia, sDisciplina, aPeriodos ) {
	
  this.oDadosTurma  = oDadosTurma;
  this.iTurma       = oDadosTurma.iTurma;
  this.iEtapa       = oDadosTurma.iEtapa;
  this.iRegencia    = iRegencia;
  this.sDisciplina  = sDisciplina;
  this.aPeriodos    = aPeriodos;
  this.aAlunos      = new Array();
  this.oAluno       = null;
  this.sRPC         = 'edu4_abonofaltas.RPC.php';
  
  var iLargura      = document.body.getWidth() / 1.9;
  var iAltura       = document.body.clientHeight / 1.6;
  var iLarguraCombo = document.body.getWidth() / 2.5;
  
  this.oWindowAbonoFalta = new windowAux('wndAbonoFalta', 'Lançamento de Abono de Faltas', iLargura, iAltura);
  
  this.oCboPeriodos = new DBComboBox('cboPeriodos', 'oCboPeriodos', '', iLarguraCombo);
  this.oCboAlunos   = new DBComboBox('cboAlunos', 'oCboAlunos', '', iLarguraCombo);
  
  this.oTxtFaltasPeriodo          = new DBTextField('txtFaltasPeriodo', 'oTxtFaltasPeriodo', '', 5);
  
  this.oFaltasAbonadas             = document.createElement('input');
  this.oFaltasAbonadas.type        = 'text';
  this.oFaltasAbonadas.id          = 'oFaltasAbonadas';
  this.oFaltasAbonadas.name        = 'oFaltasAbonadas';
  this.oFaltasAbonadas.value       = '';
  this.oFaltasAbonadas.style.width = '57px';
  this.oFaltasAbonadas.setAttribute('value', "");
  this.oFaltasAbonadas.setAttribute('onkeyPress', 'return js_mask(event, \"0-9\")');
  
  this.oCodigoJustificativa             = document.createElement('input');
  this.oCodigoJustificativa.type        = 'text';
  this.oCodigoJustificativa.id          = 'oCodigoJustificativa';
  this.oCodigoJustificativa.name        = 'oCodigoJustificativa';
  this.oCodigoJustificativa.value       = '';
  this.oCodigoJustificativa.style.width = '57px';
  this.oCodigoJustificativa.setAttribute('value', "");
  this.oCodigoJustificativa.setAttribute('onblur', 'oInstanciaViewAbonoFaltaEscopoGlobal.pesquisaJustificativaLegal(false)');
  this.oCodigoJustificativa.setAttribute('onkeyPress', 'return js_mask(event, \"0-9\")');
  
  this.oTxtDescricaoJustificativa = new DBTextField('txtDescricaoJustificativa', 'oTxtDescricaoJustificativa', '', 78);
  
  this.oCallBackCloseWindow = function() {
    return true;    
  };
};
  
DBViewAbonoFalta.prototype.setContainer = function (oWindow) {
  this.oContainer = oWindow;
};

/**
 * Montamos a windowAux com os campos para abonar as faltas
 */
DBViewAbonoFalta.prototype.montaJanela = function () {
  
  var oSelf = this;
  
  oInstanciaViewAbonoFaltaEscopoGlobal = this;
  
  oSelf.oWindowAbonoFalta.setShutDownFunction(function() {
    
    oSelf.oCallBackCloseWindow();
    oSelf.oWindowAbonoFalta.destroy();
  });
  
  var sConteudo  = "<fieldset style='width: 97%'>";
      sConteudo     += "  <legend><b>Abonar Falta</b></legend>";
      sConteudo     += "  <table>";
      sConteudo     += "    <tr>";
      sConteudo     += "      <td><b>Período: </b></td>";
      sConteudo     += "      <td id='ctnPeriodos' colspan='2'></td>";
      sConteudo     += "    </tr>";
      sConteudo     += "    <tr>";
      sConteudo     += "      <td><b>Aluno: </b></td>";
      sConteudo     += "      <td id='ctnAlunos' colspan='2'></td>";
      sConteudo     += "    </tr>";
      sConteudo     += "    <tr>";
      sConteudo     += "      <td><b>Faltas no Período: </b></td>";
      sConteudo     += "      <td id='ctnFaltasPeriodo'></td>";
      sConteudo     += "    <tr>";
      sConteudo     += "      <td><b>Nº de Faltas Abonadas: </b></td>";
      sConteudo     += "      <td id='ctnFaltasAbonadas'></td>";
      sConteudo     += "    </tr>";
      sConteudo     += "    <tr>";
      sConteudo     += "      <td>" +
      		             "        <b><a href='#' onClick='oInstanciaViewAbonoFaltaEscopoGlobal.pesquisaJustificativaLegal(true);'>" +
      				         "             Justificativa Legal: " +
      				         "        </a></b>" +
      				         "      </td>";
      sConteudo     += "      <td id='ctnCodigoJustificativa'></td>";
      sConteudo     += "      <td id='ctnDescricaoJustificativa'></td>";
      sConteudo     += "    </tr>";
      sConteudo     += "  </table>";
      sConteudo     += "</fieldset>";
      sConteudo     += "<center>";
      sConteudo     += "  <input id='btnSalvarAbono'    " +
      		             "         type='button'     " +
      		             "         name='btnSalvarAbono'  " +
      		             "         value='Salvar'  disabled />  ";
      sConteudo     += "  <input id='btnExcluir'    " +
                       "         type='button'      " +
                       "         name='btnExcluir'  " +
                       "         value='Excluir'  disabled />  ";
      sConteudo     += "  <input id='btnFechar'    " +
      		             "         type='button'     " +
      		             "         name='btnFechar'  " +
      		             "         value='Fechar'    ";
      sConteudo     += "</center>";
  
  this.oWindowAbonoFalta.setContent(sConteudo);
  
  var sMensagemAbonoFalta = 'Selecione o período e o aluno para lançar o abono.';
  new DBMessageBoard(
                      'msgAbonoFalta', 
                      'Abonar faltas da disciplina '+this.sDisciplina,
                      sMensagemAbonoFalta, 
                      oSelf.oWindowAbonoFalta.getContentContainer()
                     );
  
  this.oCboPeriodos.show($('ctnPeriodos'));
  this.oCboPeriodos.getElement().observe('change',  function() {
    oSelf.getAlunos();
  });
  
  this.oCboAlunos.addItem("", "");
  this.oCboAlunos.show($('ctnAlunos'));
  this.oCboAlunos.getElement().observe('change',  function() {
    oSelf.getFaltasNoPeriodoDoAluno();
  });
  
  this.oTxtFaltasPeriodo.setReadOnly(true);
  this.oTxtFaltasPeriodo.show($('ctnFaltasPeriodo'));
  
  $('ctnFaltasAbonadas').appendChild(this.oFaltasAbonadas);
  
  $('ctnCodigoJustificativa').appendChild(this.oCodigoJustificativa);
  
  this.oTxtDescricaoJustificativa.setReadOnly(true);
  this.oTxtDescricaoJustificativa.show($('ctnDescricaoJustificativa'));
  
  if (this.oContainer != '') {
    this.oWindowAbonoFalta.setChildOf(this.oContainer);
  }
  
  this.oWindowAbonoFalta.show();
  
  $('btnSalvarAbono').observe("click", function() {
    oSelf.executaAcao($('btnSalvarAbono'));
  });
  
  $('btnExcluir').observe("click", function() {
    oSelf.executaAcao($('btnExcluir'));
  });
  
  $('btnFechar').observe("click", function() {
    oSelf.executaAcao($('btnFechar'));
  });
};

/**
 * Pesquisamos as justificativas legais para abonar a falta
 */
DBViewAbonoFalta.prototype.pesquisaJustificativaLegal = function ( lMostra ) {
  
  var oSelf = this;
  
  var sDadosFuncao   = '';
  var lExecutaIframe = true;
  
  if ( lMostra ) {
    sDadosFuncao = 'funcao_js=parent.oInstanciaViewAbonoFaltaEscopoGlobal.mostraJustificativa|ed06_i_codigo|ed06_c_descr';
  } else {
    
    if ( oSelf.oCodigoJustificativa.value != '' ) {
      
      sDadosFuncao  = 'pesquisa_chave='+oSelf.oCodigoJustificativa.value;
      sDadosFuncao += '&funcao_js=parent.oInstanciaViewAbonoFaltaEscopoGlobal.mostraJustificativa';
    } else {
      
      lExecutaIframe = false;
      this.oTxtDescricaoJustificativa.setValue("");
    }
  }
  
  if ( lExecutaIframe ) {
    
    js_OpenJanelaIframe(
                          '',
                          'db_iframe_justificativa', 
                          'func_justificativa.php?'+sDadosFuncao,
                          'Pesquisa Justificativa Legal',
                          lMostra 
                       );
    
    if ( lMostra ) {
      $('Jandb_iframe_justificativa').style.zIndex = 10000;
    }
  }
};

/**
 * Retorno da pesquisa por uma justificativa legal
 */
DBViewAbonoFalta.prototype.mostraJustificativa = function () {
  
  var oSelf = this;
  
  switch (arguments[1]) {
  
    case true:
      
      oSelf.oCodigoJustificativa.value = "";
      oSelf.oTxtDescricaoJustificativa.setValue(arguments[0]);
      break;
    
    case false:
      
      oSelf.oTxtDescricaoJustificativa.setValue(arguments[0]);
      break;
      
    default:
      
      oSelf.oCodigoJustificativa.value = arguments[0];
      oSelf.oTxtDescricaoJustificativa.setValue(arguments[1]);
      break;
  };
  db_iframe_justificativa.hide();
};

/**
 * Buscamos os periodos da turma que permitem lancamento de falta 
 */
DBViewAbonoFalta.prototype.getPeriodos = function () {
  
  var oSelf = this;
  
  $('btnExcluir').disabled = true;
  this.limpaCampos();
  this.oCboPeriodos.clearItens();
  this.oCboAlunos.clearItens();
  this.oCboPeriodos.addItem("", "Selecione um período de avaliação.");
  this.oCboAlunos.addItem("", "Não há alunos com faltas no período selecionado.");
  this.aPeriodos.each(function( oPeriodo, iPeriodo ) {
    
    if (oPeriodo.lControlaFrequencia ) {
      oSelf.oCboPeriodos.addItem(oPeriodo.iCodigoAvaliacao, oPeriodo.sDescricaoPeriodo.urlDecode());
    }
  });
};

/**
 * Buscamos os alunos que possuem falta dentro do periodo e disciplinas selecionados
 */
DBViewAbonoFalta.prototype.getAlunos = function () {

  var oSelf = this;
  
  if ( oSelf.oCboPeriodos.getValue() == '' ) {
    
    oSelf.oCboAlunos.clearItens();
    oSelf.oCboAlunos.addItem("", "Não há alunos com faltas no período selecionado.");
    oSelf.limpaCampos();
    $('btnSalvarAbono').disabled  = true;
    $('btnExcluir').disabled      = true;
    return false;
  }
  
  this.limpaCampos();
  
  var oParametro            = new Object();
      oParametro.exec       = 'getAlunosComFaltaNoPeriodo';
      oParametro.iTurma     = this.iTurma;
      oParametro.iEtapa     = this.iEtapa;
      oParametro.iRegencia  = this.iRegencia;
      oParametro.iAvaliacao = oSelf.oCboPeriodos.getValue();
  
  var oDadosRequest            = new Object();
      oDadosRequest.method     = 'post';
      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequest.onComplete = function(oResponse) {
                                                       oSelf.retornoGetAlunos(oResponse, oSelf);
                                                     };
  
  js_divCarregando("Aguarde, pesquisando os alunos que possuem falta no período e disciplina selecionados.", "msgBox");
  new Ajax.Request( this.sRPC, oDadosRequest );
};

/**
 * Retornamos os alunos que possuem falta no periodo e disciplina selecionado, adicionando no comboBox
 */
DBViewAbonoFalta.prototype.retornoGetAlunos = function ( oResponse, oSelf ) {
  
  js_removeObj("msgBox");
  oSelf.oCboAlunos.clearItens();
  
  var oRetorno = eval('('+oResponse.responseText+')');
  
  if (oRetorno.aAlunos.length > 0) {
  
    /**
     * Atribuimos o array dos alunos a um array da classe
     */
    oSelf.aAlunos = oRetorno.aAlunos;
    
    oSelf.oCboAlunos.addItem("", "Selecione um aluno");
    
    oRetorno.aAlunos.each( function( oLinha, iSeq ) {
      oSelf.oCboAlunos.addItem(oLinha.iMatricula, oLinha.sNome.urlDecode());
      
      if (oRetorno.aAlunos.length == 1) {
        
        oSelf.oCboAlunos.setValue(oLinha.iMatricula);
        oSelf.getFaltasNoPeriodoDoAluno();
      }
    });
  } else {
    oSelf.oCboAlunos.addItem("", "Não há alunos com faltas no período selecionado.");
  }
};

/**
 * Buscamos o total de faltas do aluno dentro do periodo selecionado e adicionamos ao input
 */
DBViewAbonoFalta.prototype.getFaltasNoPeriodoDoAluno = function () {
  
  this.limpaCampos();
  this.buscaAlunoMemoria();
  this.oTxtFaltasPeriodo.setValue(this.oAluno.iNumeroFaltas);
  $('btnSalvarAbono').disabled = false;
  
  if ( this.oCboAlunos.getValue() == '' ) {
    
    this.oTxtFaltasPeriodo.setValue("");
    $('btnSalvarAbono').disabled  = true;
    $('btnExcluir').disabled = true;
  }
  this.getFaltasAbonadas();
};

/**
 * Buscamos o numero de faltas abonadas para o aluno
 */
DBViewAbonoFalta.prototype.getFaltasAbonadas = function () {
  
  var oSelf = this;
  
  this.buscaAlunoMemoria();
  $('btnExcluir').disabled = true;
  
  if ( oSelf.oAluno.iFaltasAbonadas > 0 ) {
    
    $('btnExcluir').disabled         = false;
    oSelf.oFaltasAbonadas.value      = oSelf.oAluno.iFaltasAbonadas;
    oSelf.oCodigoJustificativa.value = oSelf.oAluno.iJustificativa;
    oSelf.oTxtDescricaoJustificativa.setValue(oSelf.oAluno.sDescricaoJustificativa.urlDecode());
    
    if ( oSelf.oCboAlunos.getValue() == '' ) {
      oSelf.oFaltasAbonadas.value = oSelf.oAluno.iFaltasAbonadas;
    }
  }
};

/**
 * Salvamos os dados do abono
 */
DBViewAbonoFalta.prototype.salvar = function () {
  
  var oSelf = this;
  
  this.buscaAlunoMemoria();
  
  if ( this.validaDados() ) {
    
    var oParametro                  = new Object();
        oParametro.exec             = 'salvarAbonoFalta';
        oParametro.iDiarioAvaliacao = this.oAluno.iDiarioAvaliacao;
        oParametro.iMatricula       = this.oAluno.iMatricula;
        oParametro.iRegencia        = this.iRegencia;
        oParametro.iAvaliacao       = oSelf.oCboPeriodos.getValue();
        oParametro.iJustificativa   = this.oCodigoJustificativa.value;
        oParametro.iFaltasAbonadas  = this.oFaltasAbonadas.value;
    
    var oDadosRequest            = new Object();
        oDadosRequest.method     = 'post';
        oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequest.onComplete = function(oResponse) {
                                                         oSelf.retornoSalvar(oResponse, oSelf);
                                                       };
    
    js_divCarregando("Aguarde, salvando o abono de falta para o aluno selecionado.", "msgBox");
    new Ajax.Request( this.sRPC, oDadosRequest );
  }
};

/**
 * Retorno do salvar abono
 */
DBViewAbonoFalta.prototype.retornoSalvar = function ( oResponse, oSelf ) {
  
  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');
  
  if (oRetorno.status == 1) {
    
    alert('Abono salvo com sucesso!');
    this.show();
  } else {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
};

/**
 * Excluimos um abono de falta de um aluno
 */
DBViewAbonoFalta.prototype.excluir = function () {
  
  var oSelf = this;
  
  this.buscaAlunoMemoria();
  
  var sMensagem  = 'Deseja realmente excluir o abono para o aluno '+this.oCboAlunos.getLabel();
      sMensagem +=' no '+this.oCboPeriodos.getLabel()+' ?';
  if ( confirm(sMensagem) ) {
    
    var oParametro                  = new Object();
        oParametro.exec             = 'excluirAbonoFalta';
        oParametro.iDiarioAvaliacao = this.oAluno.iDiarioAvaliacao;
        oParametro.iMatricula       = this.oAluno.iMatricula;
        oParametro.iRegencia        = this.iRegencia;
        oParametro.iAvaliacao       = this.oCboPeriodos.getValue();
    
    var oDadosRequest            = new Object();
        oDadosRequest.method     = 'post';
        oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequest.onComplete = function(oResponse) {
                                                         oSelf.retornoExcluir(oResponse, oSelf);
                                                       };
    
    js_divCarregando("Aguarde, excluindo o abono de falta para o aluno selecionado.", "msgBox");
    var oAjax = new Ajax.Request( oSelf.sRPC, oDadosRequest );
    delete oAjax;
  }
};

/**
 * Retorno da exclusao do abono da falta
 */
DBViewAbonoFalta.prototype.retornoExcluir = function ( oResponse, oSelf ) {
  
  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');
  
  if (oRetorno.status == 1) {
    
    alert('Abono excluído com sucesso!');
    oSelf.show();
  } else {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
};

/**
 * Percorre os alunos salvos na memoria, atribuindo a propriedade oAluno os dados do aluno selecionado
 */
DBViewAbonoFalta.prototype.buscaAlunoMemoria = function () {
  
  var oSelf = this;
  
  if (this.aAlunos.length > 0) {
    
    this.aAlunos.each( function( oLinha, iSeq ) {
      
      if (oLinha.iMatricula == oSelf.oCboAlunos.getValue()) {
        oSelf.oAluno = oLinha;
      }
    });
  }
};

/**
 * Chama o metodo a ser executado de acordo com o button clicado
 */
DBViewAbonoFalta.prototype.executaAcao = function ( oElemento ) {
  
  var oSelf = this;
  
  switch( oElemento.id ) {
  
    case 'btnSalvarAbono':
      
      oSelf.salvar();
      break;
      
    case 'btnExcluir':
      
      oSelf.excluir();
      break;
      
    case 'btnFechar':
      
      delete oInstanciaViewAbonoFaltaEscopoGlobal;
      oSelf.oCallBackCloseWindow();
      oSelf.oWindowAbonoFalta.destroy();
      break;
  }
};

/**
 * Limpamos todos os campos na tela
 */
DBViewAbonoFalta.prototype.limpaCampos = function () {
  
  var oSelf = this;
  
  oSelf.oTxtFaltasPeriodo.setValue("");
  oSelf.oFaltasAbonadas.value = '';
  oSelf.oCodigoJustificativa.value = '';
  oSelf.oTxtDescricaoJustificativa.setValue("");
};

/**
 * Validamos se todos os campos foram preenchidos para salva-los
 */
DBViewAbonoFalta.prototype.validaDados = function() {
  
  if ( this.oCboPeriodos.getValue() == '' ) {
    
    alert('Selecione um período de avaliação.');
    return false;
  }
  
  if ( this.oCboAlunos.getValue() == '' ) {
    
    alert('Selecione um aluno para abonar falta.');
    return false;
  }
  
  if ( this.oFaltasAbonadas.value == '' ) {
    
    alert('Informe o número de faltas a serem abonadas.');
    return false;
  }
  
  if ( parseInt(this.oTxtFaltasPeriodo.getValue()) < parseInt(this.oFaltasAbonadas.value) ) {
    
    alert('Número de faltas a serem abonadas é maior que o número de faltas no período.');
    return false;
  }
  
  return true;
};

/**
 * Ao fechar a janela, recarrega a tela anterior
 */
DBViewAbonoFalta.prototype.setCallBackWindow = function (fFunction) {
  this.oCallBackCloseWindow = fFunction;
};

/**
 * Executamos os metodos de inicializacao da windowAux
 */
DBViewAbonoFalta.prototype.show = function () {
  
  this.montaJanela();
  this.getPeriodos();
};