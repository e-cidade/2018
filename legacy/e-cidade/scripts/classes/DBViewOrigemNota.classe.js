/**
 * Classe para tratamento da origem da nota de uma disciplina em um periodo
 * @param object  oDadosTurma
 * @param integer iRegencia
 * @param string  sDisciplina
 * @param array   aPeriodos
 * @returns {DBViewOrigemNota}
 */
DBViewOrigemNota = function ( oDadosTurma, iRegencia, sDisciplina, aPeriodos ) {
  
  this.oDadosTurma  = oDadosTurma;
  this.iTurma       = oDadosTurma.iTurma;
  this.iEtapa       = oDadosTurma.iEtapa;
  this.iRegencia    = iRegencia;
  this.sDisciplina  = sDisciplina;
  this.aPeriodos    = aPeriodos;
  this.aAlunos      = new Array();
  this.oAluno       = '';
  this.sDisciplina  = '';
  this.sTipoDestino = '';
  this.oContainer   = '';
  this.sRPC         = 'edu4_origemnota.RPC.php';
  
  var iLargura      = document.body.getWidth() / 2.1;
  var iAltura       = document.body.clientHeight / 1.6;
  var iLarguraCombo = document.body.getWidth() / 2.5;
  
  this.oWindowOrigemNota          = new windowAux('wndOrigemNota', 'Origem da Nota', iLargura, iAltura);
  
  this.oCboPeriodos               = new DBComboBox('cboPeriodos', 'oCboPeriodos', '', iLarguraCombo);
  this.oCboAlunos                 = new DBComboBox('cboAlunos', 'oCboAlunos', '', iLarguraCombo);
  
  this.oTxtCodigoEscolaAtual      = new DBTextField('txtCodigoEscolaAtual', 'oTxtCodigoEscolaAtual', '', 5);
  this.oTxtDescricaoEscolaAtual   = new DBTextField('txtDescricaoEscolaAtual', 'oTxtDescricaoEscolaAtual', '', 60);
  this.oTxtTipoEscolaAtual        = new DBTextField('txtTipoEscolaAtual', 'oTxtTipoEscolaAtual', '', 85);
  this.oTxtMunicipioEscolaAtual   = new DBTextField('txtMunicipioEscolaAtual', 'oTxtMunicipioEscolaAtual', '', 69);
  this.oTxtEstadoEscolaAtual      = new DBTextField('txtEstadoEscolaAtual', 'oTxtEstadoEscolaAtual', '', 5);
  this.oTxtCodigoEscolaDestino    = new DBTextField('txtCodigoEscolaDestino', 'oTxtCodigoEscolaDestino', '', 5);
  this.oTxtDescricaoEscolaDestino = new DBTextField('txtDescricaoEscolaDestino', 'oTxtDescricaoEscolaDestino', '', 60);
  this.oTxtTipoEscolaDestino      = new DBTextField('txtTipoEscolaDestino', 'oTxtTipoEscolaDestino', '', 85);
  this.oTxtMunicipioEscolaDestino = new DBTextField('txtMunicipioEscolaDestino', 'oTxtMunicipioEscolaDestino', '', 69);
  this.oTxtEstadoEscolaDestino    = new DBTextField('txtEstadoEscolaDestino', 'oTxtEstadoEscolaDestino', '', 5);
  
  this.oCallBackCloseWindow = function() {
    return true;    
  };
};

DBViewOrigemNota.prototype.setContainer = function (oWindow) {
  this.oContainer = oWindow;
};

/**
 * Monta os dados a serem apresentados na tela
 */
DBViewOrigemNota.prototype.montaJanela = function () {
  
  var oSelf = this;
  
  oSelf.oWindowOrigemNota.setShutDownFunction(function () {
    
    oSelf.oCallBackCloseWindow();
    oSelf.oWindowOrigemNota.destroy();
  });
  
  var sConteudo  = '<fieldset>';
      sConteudo += '  <legend><b>Origem Atual</b></legend>';
      sConteudo += '  <table>';
      sConteudo += '    <tr>';
      sConteudo += '      <td>';
      sConteudo += '        <fieldset style="border:0px;">';
      sConteudo += '          <table>';
      sConteudo += '            <tr>';
      sConteudo += '              <td width="10%"><b>Período:</b></td>';
      sConteudo += '              <td id="ctnPeriodos"></td>';
      sConteudo += '            </tr>';
      sConteudo += '            <tr>';
      sConteudo += '              <td><b>Aluno:</b></td>';
      sConteudo += '              <td id="ctnAlunos"></td>';
      sConteudo += '            </tr>';
      sConteudo += '          </table>';
      sConteudo += '        </fieldset>';
      sConteudo += '      </td>';
      sConteudo += '    </tr>';
      sConteudo += '    <tr>';
      sConteudo += '      <td colspan="2">';
      sConteudo += '        <fieldset>';
      sConteudo += '          <legend><b>Escola Atual</b></legend>';
      sConteudo += '          <table>';
      sConteudo += '            <tr>';
      sConteudo += '              <td ><b>Escola:</b></td>';
      sConteudo += '              <td id="ctnCodigoEscolaAtual" colspan="2"></td>';
      sConteudo += '              <td id="ctnDescricaoEscolaAtual" ></td>';
      sConteudo += '            </tr>';
      sConteudo += '            <tr>';
      sConteudo += '              <td><b>Tipo:</b></td>';
      sConteudo += '              <td id="ctnTipoEscolaAtual" colspan="5"></td>';
      sConteudo += '            </tr>';
      sConteudo += '            <tr>';
      sConteudo += '              <td><b>Município:</b></td>';
      sConteudo += '              <td id="ctnMunicipioEscolaAtual" colspan="3"></td>';
      sConteudo += '              <td><b>Estado:</b></td>';
      sConteudo += '              <td id="ctnEstadoEscolaAtual"></td>';
      sConteudo += '            </tr>';
      sConteudo += '          </table>';
      sConteudo += '        </fieldset>';
      sConteudo += '      </td>';
      sConteudo += '    </tr>';
      sConteudo += '    <tr>';
      sConteudo += '      <td colspan="2">';
      sConteudo += '        <fieldset id="fieldEscolaDestino">';
      sConteudo += '          <legend><b>Escola de Destino</b></legend>';
      sConteudo += '          <table>';
      sConteudo += '            <tr>';
      sConteudo += '              <td ><b>Escola:</b></td>';
      sConteudo += '              <td id="ctnCodigoEscolaDestino" colspan="2"></td>';
      sConteudo += '              <td id="ctnDescricaoEscolaDestino" ></td>';
      sConteudo += '            </tr>';
      sConteudo += '            <tr>';
      sConteudo += '              <td><b>Tipo:</b></td>';
      sConteudo += '              <td id="ctnTipoEscolaDestino" colspan="5"></td>';
      sConteudo += '            </tr>';
      sConteudo += '            <tr>';
      sConteudo += '              <td><b>Município:</b></td>';
      sConteudo += '              <td id="ctnMunicipioEscolaDestino" colspan="3"></td>';
      sConteudo += '              <td><b>Estado:</b></td>';
      sConteudo += '              <td id="ctnEstadoEscolaDestino"></td>';
      sConteudo += '            </tr>';
      sConteudo += '          </table>';
      sConteudo += '        </fieldset>';
      sConteudo += '      </td>';
      sConteudo += '    </tr>';
      sConteudo += '  </table>';
      sConteudo += '</fieldset>';
      sConteudo += '<center>';
      sConteudo += '  <input type="button"                                               ';
      sConteudo += '         id="btnAlterarEscolaOrigem"                                 ';
      sConteudo += '         class="btnAlterar"                                          ';
      sConteudo += '         name="btnAlterarEscolaOrigem"                               '; 
      sConteudo += '         value="Alterar Escola de Origem" disabled />                ';
      sConteudo += '  <input type="button"                                               ';
      sConteudo += '         id="btnSalvarOrigem"                                        '; 
      sConteudo += '         name="btnSalvarOrigem"                                      '; 
      sConteudo += '         value="Salvar" disabled />                                  ';
      sConteudo += '  <input type="button"                                               ';
      sConteudo += '         id="btnFechar"                                              '; 
      sConteudo += '         name="btnFechar"                                            '; 
      sConteudo += '         value="Fechar"                                              ';
      sConteudo += '</center>';
  
      
  this.oWindowOrigemNota.setContent(sConteudo);
  
  oToogleEscolaDestino = new DBToogle('fieldEscolaDestino', false);
  
  new DBMessageBoard(
                      'msgOrigemNota', 
                      'Origem da nota atual para a disciplina '+this.sDisciplina, 
                      'Selecione o período e o aluno para definir a origem da nota', 
                      this.oWindowOrigemNota.getContentContainer()
                    );

  if (this.oContainer != '') {
    oSelf.oWindowOrigemNota.setChildOf(this.oContainer);
  }
  
  this.oWindowOrigemNota.show();
  
  $('btnAlterarEscolaOrigem').observe("click", function() {
    oSelf.executaAcao($('btnAlterarEscolaOrigem'));
  });
  
  $('btnSalvarOrigem').observe("click", function() {
    oSelf.executaAcao($('btnSalvarOrigem'));
  });
  
  $('btnFechar').observe("click", function() {
    oSelf.executaAcao($('btnFechar'));
  }); 
  
  this.oCboPeriodos.show($('ctnPeriodos'));
  this.oCboPeriodos.getElement().observe('change',  function() {
    oSelf.getAlunos();
  });
  
  this.oCboAlunos.addItem("", "");
  this.oCboAlunos.show($('ctnAlunos'));
  this.oCboAlunos.getElement().observe('change',  function() {
    oSelf.carregaDadosAlteracao();
  });
  
  /**
   * Inputs da escola atual
   */
  this.oTxtCodigoEscolaAtual.setReadOnly(true);
  this.oTxtCodigoEscolaAtual.show($('ctnCodigoEscolaAtual'));

  this.oTxtDescricaoEscolaAtual.setReadOnly(true);
  this.oTxtDescricaoEscolaAtual.show($('ctnDescricaoEscolaAtual'));

  this.oTxtTipoEscolaAtual.setReadOnly(true);
  this.oTxtTipoEscolaAtual.show($('ctnTipoEscolaAtual'));

  this.oTxtMunicipioEscolaAtual.setReadOnly(true);
  this.oTxtMunicipioEscolaAtual.show($('ctnMunicipioEscolaAtual'));

  this.oTxtEstadoEscolaAtual.setReadOnly(true);
  this.oTxtEstadoEscolaAtual.show($('ctnEstadoEscolaAtual'));
  
  /**
   * Inputs da escola de destino
   */ 
  this.oTxtCodigoEscolaDestino.setReadOnly(true);
  this.oTxtCodigoEscolaDestino.show($('ctnCodigoEscolaDestino'));

  this.oTxtDescricaoEscolaDestino.setReadOnly(true);
  this.oTxtDescricaoEscolaDestino.show($('ctnDescricaoEscolaDestino'));

  this.oTxtTipoEscolaDestino.setReadOnly(true);
  this.oTxtTipoEscolaDestino.show($('ctnTipoEscolaDestino'));
  
  this.oTxtMunicipioEscolaDestino.setReadOnly(true);
  this.oTxtMunicipioEscolaDestino.show($('ctnMunicipioEscolaDestino'));

  this.oTxtEstadoEscolaDestino.setReadOnly(true);
  this.oTxtEstadoEscolaDestino.show($('ctnEstadoEscolaDestino'));
  
};

/**
 * Pega os periodos que foram passados por parametro para o componente, e monta o comboBox
 */
DBViewOrigemNota.prototype.getPeriodos = function () {
  
  var oSelf = this;
  
  if ( oSelf.aPeriodos.length > 0 ) {

    oSelf.oCboPeriodos.clearItens();
    oSelf.oCboPeriodos.addItem('', 'Selecione um período');
    oSelf.aPeriodos.each(function ( oPeriodo, iPeriodo ) {
      
      if ( oPeriodo.sTipoAvaliacao == 'A' ) {
        
        oSelf.oCboPeriodos.addItem(oPeriodo.iCodigoAvaliacao, oPeriodo.sDescricaoPeriodo.urlDecode());
        if (oSelf.aPeriodos.length == 1 ) {
          oSelf.oCboPeriodos.setValue(oPeriodo.iCodigoAvaliacao);
        }
      }
    });
    
    this.getAlunos();
  } else {
    oSelf.oCboPeriodos.addItem('', 'Não há períodos vinculados para a turma');
  }
};

/**
 * Busca os alunos que tem nota lancada no periodo selecionado
 */
DBViewOrigemNota.prototype.getAlunos = function () {
  
  var oSelf = this;
  
  this.limparCampos();
  
  if ( this.oCboPeriodos.getValue() == '' ) {
  
    this.oCboAlunos.clearItens();
    this.oCboAlunos.addItem('', 'Não há alunos para o período selecionado');
    this.desabilitaBotoes();
    return false;
  }
  
  js_divCarregando( "Aguarde, buscando alunos com nota lançada no período selecionado.", "msgBox" );
  var oParametro            = new Object();
      oParametro.exec       = 'getAlunoComNotaNoPeriodo';
      oParametro.iTurma     = this.iTurma;
      oParametro.iEtapa     = this.iEtapa;
      oParametro.iRegencia  = this.iRegencia;
      oParametro.iAvaliacao = this.oCboPeriodos.getValue();
  
  var oDadosRequest            = new Object();
      oDadosRequest.method     = 'post';
      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequest.onComplete = function(oResponse) {
                                                      oSelf.retornaGetAlunos(oResponse, oSelf);
                                                     };
      
  new Ajax.Request( this.sRPC, oDadosRequest);
};

/**
 * Retorno da busca dos alunos com nota lancada no periodo
 */
DBViewOrigemNota.prototype.retornaGetAlunos = function ( oResponse, oSelf ) {
  
  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');
  
  oSelf.oCboAlunos.clearItens();
  if ( oRetorno.aAlunos.length > 0 ) {
    
    oSelf.oCboAlunos.addItem('', 'Selecione um aluno');
    oSelf.aAlunos = oRetorno.aAlunos;
    
    oRetorno.aAlunos.each(function ( oAluno, iAluno ) {
      
      oSelf.oCboAlunos.addItem( oAluno.iMatricula, oAluno.sNome.urlDecode() );
      if ( oRetorno.aAlunos.length == 1 ) {
        oSelf.oCboAlunos.setValue(oAluno.iMatricula);
      }
    });
    
    oSelf.carregaDadosAlteracao();
  } else {
    
    oToogleEscolaDestino.show(false);
    oSelf.oCboAlunos.addItem('', 'Não há alunos com nota lançada no período selecionado.');
    oSelf.desabilitaBotoes();
  }
};

/**
 * Preenche os dados do aluno selecionado nos demais campos
 */
DBViewOrigemNota.prototype.carregaDadosAlteracao = function () {
  
  $('btnAlterarEscolaOrigem').value     = 'Alterar Escola de Origem';
  $('btnAlterarEscolaOrigem').className = 'btnAlterar';
  $('btnSalvarOrigem').disabled               = true;
  oToogleEscolaDestino.show(false);
  this.limparCampos();
  
  if ( this.oCboAlunos.getValue() == '' ) {
    
    this.desabilitaBotoes();
    return false;
  }
  
  this.dadosAlunoSelecionado();
  
  this.oTxtCodigoEscolaAtual.setValue(this.oAluno.iEscolaAtual);
  this.oTxtDescricaoEscolaAtual.setValue(this.oAluno.sNomeEscolaAtual.urlDecode());
  this.oTxtTipoEscolaAtual.setValue(this.oAluno.sTipoAtual.urlDecode());
  this.oTxtMunicipioEscolaAtual.setValue(this.oAluno.sMunicipioAtual.urlDecode());
  this.oTxtEstadoEscolaAtual.setValue(this.oAluno.sUfAtual.urlDecode());
  
  if ( !this.oAluno.lEscolaRede ) {
    
    this.oTxtCodigoEscolaDestino.setValue(this.oAluno.iEscolaDestino);
    this.oTxtDescricaoEscolaDestino.setValue(this.oAluno.sNomeEscolaDestino.urlDecode());
    this.oTxtTipoEscolaDestino.setValue(this.oAluno.sTipoDestino.urlDecode());
    this.oTxtMunicipioEscolaDestino.setValue(this.oAluno.sMunicipioDestino.urlDecode());
    this.oTxtEstadoEscolaDestino.setValue(this.oAluno.sUfDestino.urlDecode());
    
    oToogleEscolaDestino.show(true);
    
    $('btnAlterarEscolaOrigem').value     = 'Retornar Origem da Nota';
    $('btnAlterarEscolaOrigem').className = 'btnRetornar';
    $('btnAlterarEscolaOrigem').disabled  = false;
  } else {
    $('btnAlterarEscolaOrigem').disabled = false;
  }
};

/**
 * Pega o aluno selecionado no combo e busca os dados deste no array salvo na memoria
 */
DBViewOrigemNota.prototype.dadosAlunoSelecionado = function () {
  
  var oSelf = this;
  
  if ( this.aAlunos.length > 0 ) {
    
    this.aAlunos.each(function ( oAluno, iAluno ) {
      
      if ( oAluno.iMatricula == oSelf.oCboAlunos.getValue() ) {
        oSelf.oAluno = oAluno;
      }
    });
  }
};

/**
 * Pesquisa a escola para setar como nota de origem
 * CUIDADO!!! A VARIAVEL oInstanciaViewOrigemNotaEscopoGlobal está no escopo Global!!!!
 */
DBViewOrigemNota.prototype.pesquisaEscola = function () {
  
  oInstanciaViewOrigemNotaEscopoGlobal = this;
  
  var sParametros  = 'funcao_js=parent.oInstanciaViewOrigemNotaEscopoGlobal.mostraEscolaFora|ed18_i_codigo|ed18_c_nome';
      sParametros += '|ed261_c_nome|ed260_c_sigla|tipoescoladescr|tipoescola';
      
  js_OpenJanelaIframe(
                       '',
                       'db_iframe_escolafora',
                       'func_escolaorigemnota.php?'+sParametros,
                       'Pesquisa de Escolas Fora da Rede',
                       true
                     );
  
  $('Jandb_iframe_escolafora').style.zIndex = 10000;
};

/**
 * Retorna os dados da pesquisa da escola. Caso seja uma escola fora da rede, chama o metodo buscaDadosEscolaFora
 * para buscar o nome do municipio e o estado
 */
DBViewOrigemNota.prototype.mostraEscolaFora = function () {
  
  this.oTxtCodigoEscolaDestino.setValue(arguments[0]);
  this.oTxtDescricaoEscolaDestino.setValue(arguments[1]);
  this.oTxtTipoEscolaDestino.setValue(arguments[4]);
  this.oTxtMunicipioEscolaDestino.setValue(arguments[2]);
  this.oTxtEstadoEscolaDestino.setValue(arguments[3]);
  
  if ( arguments[5] == 'F' ) {
    this.buscaDadosEscolaFora(arguments);
  }
  
  $('btnSalvarOrigem').disabled = false;
  this.sTipoDestino      = arguments[5];
  db_iframe_escolafora.hide();
  oToogleEscolaDestino.show(true);
  delete oInstanciaViewOrigemNotaEscopoGlobal;
};

/**
 * Busca o municipio e estado da escola quando esta nao for da rede
 */
DBViewOrigemNota.prototype.buscaDadosEscolaFora = function ( aArgumentos ) {
  
  var oSelf = this;
  
  var oParametro         = new Object();
      oParametro.exec    = 'buscaDadosEscolaFora';
      oParametro.iCodigo = aArgumentos[0];
      
  var oDadosRequest            = new Object();
      oDadosRequest.method     = 'post';
      oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequest.onComplete = function(oResponse) {
                                                       oSelf.retornoBuscaDadosEscolaFora(oResponse, oSelf);
                                                     };
      
  new Ajax.Request( this.sRPC, oDadosRequest );
};

/**
 * Retorno da busca do municipio e estado da escola fora da rede
 */
DBViewOrigemNota.prototype.retornoBuscaDadosEscolaFora = function ( oResponse, oSelf ) {
  
  var oRetorno = eval('('+oResponse.responseText+')');
  oSelf.oTxtMunicipioEscolaDestino.setValue(oRetorno.sMunicipio.urlDecode());
  oSelf.oTxtEstadoEscolaDestino.setValue(oRetorno.sUf.urlDecode());
};

/**
 * Salva os dados da alteração da origem da nota
 */
DBViewOrigemNota.prototype.salvar = function () {
  
  var oSelf      = this;
  var sMensagem  = 'A data da matrícula é anterior a data final do período ('+this.oAluno.dtInicioPeriodo+' a ';
      sMensagem += this.oAluno.dtFinalPeriodo+'). \nDescartar aviso e confirmar alteração ?';
  var lSalvarOrigem = true;
  
  this.dadosAlunoSelecionado();
  
  if ( this.oAluno.lAnteriorFinalPeriodo && !confirm(sMensagem) ) {
    lSalvarOrigem = false;
  }
  
  if ( lSalvarOrigem ) {
    
    var oParametro                  = new Object();
        oParametro.exec             = 'salvarOrigemNota';
        oParametro.iMatricula       = this.oAluno.iMatricula;
        oParametro.iRegencia        = this.iRegencia;
        oParametro.iAvaliacao       = this.oCboPeriodos.getValue();
        oParametro.iDiarioAvaliacao = this.oAluno.iDiarioAvaliacao;
        oParametro.iEscola          = this.oAluno.iEscolaTurma;
        oParametro.sTipo            = 'M';
    
    if ( this.oTxtCodigoEscolaDestino.getValue() != '' ) {
      
      oParametro.iEscola = this.oTxtCodigoEscolaDestino.getValue();
      oParametro.sTipo   = this.sTipoDestino;
    }
    
    var oDadosRequest            = new Object();
        oDadosRequest.method     = 'post';
        oDadosRequest.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequest.onComplete = function(oResponse) {
      oSelf.retornoSalvar(oResponse, oSelf);
    };
    
    js_divCarregando("Aguarde, salvando alterações na origem da nota.", "msgBox");
    new Ajax.Request( this.sRPC, oDadosRequest );
  }
};

/**
 * Retorno do salvar a origem da nota
 */
DBViewOrigemNota.prototype.retornoSalvar = function ( oResponse, oSelf ) {
  
  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');
  
  if ( oRetorno.status == 1 ) {
    
    alert('Origem da nota alterado com sucesso.');
    oSelf.show();
  } else {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
  
  return true;
};

/**
 * Recebe o elemento em que foi executado o evento click e trata de acordo com este elemento
 */
DBViewOrigemNota.prototype.executaAcao = function ( oElemento ) {

  switch ( oElemento.id ) {
  
    /**
     * Verifica qual o valor do class do elemento no momento da acao, para tratar da maneira correta
     * btnAlterar: chama a func de pesquisa da escola, para alterar a origem da nota para externa
     * btnRetornar: limpa os campos da escola de destino, e ao salvar, retorna como uma nota interna
     */
    case 'btnAlterarEscolaOrigem':

      if ( oElemento.className == 'btnAlterar' ) {
        this.pesquisaEscola();
      } else {
        
        oToogleEscolaDestino.show(false);
        this.oTxtCodigoEscolaAtual.setValue(this.oTxtCodigoEscolaDestino.getValue());
        this.oTxtDescricaoEscolaAtual.setValue(this.oTxtDescricaoEscolaDestino.getValue());
        this.oTxtTipoEscolaAtual.setValue(this.oTxtTipoEscolaDestino.getValue());
        this.oTxtMunicipioEscolaAtual.setValue(this.oTxtMunicipioEscolaDestino.getValue());
        this.oTxtEstadoEscolaAtual.setValue(this.oTxtEstadoEscolaDestino.getValue());
        
        this.oTxtCodigoEscolaDestino.setValue('');
        this.oTxtDescricaoEscolaDestino.setValue('');
        this.oTxtTipoEscolaDestino.setValue('');
        this.oTxtMunicipioEscolaDestino.setValue('');
        this.oTxtEstadoEscolaDestino.setValue('');
        
        $('btnSalvarOrigem').disabled              = false;
        $('btnAlterarEscolaOrigem').disabled = true;
      }
      break;
    
    case 'btnSalvarOrigem':
      
      this.salvar();
      break;
      
    case 'btnFechar':
      
      this.oCallBackCloseWindow();
      this.oWindowOrigemNota.destroy();
      break;
  };
};

/**
 * Limpa os inputs
 */
DBViewOrigemNota.prototype.limparCampos = function () {
    
  var oSelf = this;
  oSelf.oTxtCodigoEscolaAtual.setValue('');
  oSelf.oTxtDescricaoEscolaAtual.setValue('');
  oSelf.oTxtTipoEscolaAtual.setValue('');
  oSelf.oTxtMunicipioEscolaAtual.setValue('');
  oSelf.oTxtEstadoEscolaAtual.setValue('');
  
  oSelf.oTxtCodigoEscolaDestino.setValue('');
  oSelf.oTxtDescricaoEscolaDestino.setValue('');
  oSelf.oTxtTipoEscolaDestino.setValue('');
  oSelf.oTxtMunicipioEscolaDestino.setValue('');
  oSelf.oTxtEstadoEscolaDestino.setValue('');
};

/**
 * Desabilita os botoes do componente, com excecao do Fechar
 */
DBViewOrigemNota.prototype.desabilitaBotoes = function () {
  
  $('btnAlterarEscolaOrigem').disabled = true;
  $('btnSalvarOrigem').disabled              = true;
};

/**
 * Ao fechar a janela, recarrega a tela anterior
 */
DBViewOrigemNota.prototype.setCallBackWindow = function (fFunction) {
  this.oCallBackCloseWindow = fFunction;
};

/**
 * Inicializamos o componente na tela
 */
DBViewOrigemNota.prototype.show = function () {
  
  this.montaJanela();
  this.getPeriodos();
};
