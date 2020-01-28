require_once("scripts/classes/DBViewLancamentoAvaliacao/DBViewLancamentoAvaliacao.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/DisciplinaTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/AlunoTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacao/PeriodoTurma.classe.js");
require_once("scripts/object.js");

/**
 * Renderiza View de lançamento de Observação
 * @dependency Utiliza DBViewLancamentoAvaliacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.13 $
 *
 * @param {Object}  oDadosTurma Objeto com as informações da turma
 *                  oDadosTurma.iRegencia
 *                  oDadosTurma.iTurma
 *                  oDadosTurma.iEtapa
 * @param {Object}  oDadosAluno Objeto com as informações do aluno 
 *                              (Não é necessário ser informado desde que lExibeFiltros = true)
 *                  oDadosAluno.iMatricula
 *                  oDadosAluno.iPeriodo
 * @param {boolean} lExibeFiltros se true o componente apresentará campos para seleção do aluno e da turma    
 * @param {HTMLElement} oNode  onde será renderizado, se não informado, uma nova janela é criada
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao = function (oDadosTurma, oDadosAluno, lExibeFiltros, oNode) {

  /**
   * Dados da Turma
   * @var {Object}
   */
  this.oDadosTurma = oDadosTurma;
  
  this.lFiltroAluno   = false;
  
  this.lFiltroPeriodo = false;
  
  this.oAlunoSelecionado = {iMatricula:'', iPeriodo:''};
  /**
   * Dados do Aluno
   * @var {Object}
   */
  this.oDadosAluno = {};
  
  if (!empty(oDadosAluno)) {
    
    this.oDadosAluno = oDadosAluno;
    this.oAlunoSelecionado.iMatricula  = oDadosAluno.iMatricula;
    this.oAlunoSelecionado.iPeriodo    = oDadosAluno.iPeriodo;

    if (oDadosAluno.sTipoAvaliacao) {
      this.oAlunoSelecionado.sTipoAvaliacao = oDadosAluno.sTipoAvaliacao;
    }
    if (empty(oDadosAluno.iMatricula)) {
      
      this.lFiltroAluno = true;
      
    }
    
    if (empty(oDadosAluno.iPeriodo)) {
      this.lFiltroPeriodo = true;
    }
  } 
  
  /**
   * Se true o componente apresentará campos para seleção do aluno e da turma e utilizará dos dados selecionados 
   * Se false usaremos os dados informados pelo Objeto oDadosAluno (matricula e periodo) 
   * @var {boolean}
   */
  this.lExibeFiltros = lExibeFiltros;
  if (lExibeFiltros) {
    
    this.lFiltroAluno   = true;
    this.lFiltroPeriodo = true;
  }
  
  /**
   * Largura da janela
   * @var {integer}
   */
  this.iTamanhoJanela = document.body.getWidth() - 20;
  this.iAlturaJanela  = document.body.getHeight() / 1.1;
  
  this.sUrlRPC = "edu4_lancaobservacao.RPC.php";
  
  /**
   * Onde será renderizado a windown. Se vazio, cria uma janela.
   */
  this.oView = '';
  if (oNode != null) {
    this.oView = oNode;
  }
  
  this.oWindowContainer = null;
  
  this.oTextObservacao             = document.createElement('textarea');
  this.oTextObservacao.id          = 'observacaoLancada';
  this.oTextObservacao.rows        = '4';
  this.oTextObservacao.style.width = '100%';
  
  this.oBtnSalvar          = document.createElement('input');
  this.oBtnSalvar.type     = 'button';
  this.oBtnSalvar.value    = 'Salvar';
  this.oBtnSalvar.name     = 'salvar';
  this.oBtnSalvar.id       = 'btnSalvarObservacao';
  this.oBtnSalvar.disabled = true;
  
  /**
   * Variável para setar uma função a ser excutada ao fechar a janela
   * @var {function}
   */
  this.oCallBackCloseWindow = function() {
    return true;    
  };
};


/**
 * Seta quem é o container pai. Sobre o qual abrirá
 * var {object} windowAux
 */
DBViewAvaliacao.LancamentoObservacao.prototype.setContainerPai = function (oWindow, oTamanho) {
  
  this.oWindowContainer = oWindow;
  this.iTamanhoJanela   = oWindow.getWidth() - 50;
  this.iAlturaJanela    = oWindow.getHeight() / 1.1;
  if (!empty(oTamanho)) {
    
    this.iTamanhoJanela = oTamanho.iLargura;
    this.iAlturaJanela  = oTamanho.iAltura;
  };
};

/**
 * Seta uma funcção para ser executada ao se fechar a janela
 * @param {function} fFunction
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao.prototype.setCallBackWindow = function (fFunction) {
  this.oCallBackCloseWindow = fFunction;
};


/**
 * Cria a estrutura da interface
 * Renderiza a Window, montando a estrutura básica (HTML)
 */
DBViewAvaliacao.LancamentoObservacao.prototype.criarWindow = function() {
 
  var oSelf = this;
  
  this.oWindowObservacao = new windowAux('wndObservacao','Lança Observação', this.iTamanhoJanela, this.iAlturaJanela);

  this.oWindowObservacao.setShutDownFunction(function() {
    
    oSelf.oCallBackCloseWindow();
    oSelf.oWindowObservacao.destroy();
  });
  
  
  var sConteudo  = "<div id='ctnLancamentoObservacao' style=' width:99%'>              ";
      sConteudo += "  <div id='ctnFormularioObservacao' style='overflow:scroll;overflow-x:hidden'> ";
      sConteudo += "    <fieldset id='filtrosDadosAluno'>          ";
      sConteudo += "      <legend>Selecione o Aluno e o Período</legend>              ";
      sConteudo += "      <table style='width:100%'>                                  ";
      sConteudo += "        <tr id='ctnFiltroAluno' style='display:none'>             ";
      sConteudo += "          <td class='bold'>Aluno:</td>                            ";
      sConteudo += "          <td id='ctnAlunoTurma'></td>                            ";
      sConteudo += "        </tr>                                                     ";
      sConteudo += "        <tr id='ctnFiltroPeriodo' style='display:none'>                                ";
      sConteudo += "          <td style='width:10%' nowrap class='bold'>Período:</td>        ";
      sConteudo += "          <td style='width:90%'id='ctnPeriodoTurma'></td>                          ";
      sConteudo += "        </tr>                                                     ";
      sConteudo += "      </table>                                                    ";
      sConteudo += "    </fieldset>                                                   ";
      sConteudo += "    <fieldset style='width:99%' id='ctnObservacoes'>              ";
      sConteudo += "      <legend>Lançar Observação</legend>                          ";
      sConteudo += "      <fieldset id='cntTextObservacao' class='separator'>         ";
      sConteudo += "        <legend>Observação</legend>                               ";
      sConteudo += "      </fieldset>                                                 ";
      sConteudo += "      <fieldset id='ctnReplicaDisciplinas' class='separator'>     ";
      sConteudo += "        <legend>Replicar observação para disciplinas</legend>     ";
      sConteudo += "        <div id='replicaDisciplinas' class='container'> </div>    ";
      sConteudo += "      </fieldset>                                                 ";
      sConteudo += "    </fieldset>                                                   ";
      sConteudo += "  </div>                                                     ";
      sConteudo += "    <center id='ctnSalvarObservacao'></center>                    ";
      sConteudo += "</div>                                                            ";  
  
  //this.oWindowObservacao.setContent(sConteudo);
  
  var sMsg  = 'Lançar Observação';
  
  var sHelpMsgBox  = 'No quadro "Replicar observação para disciplinas", selecione as disciplinas que deseja replicar ';
      sHelpMsgBox += 'a observação lançada. Caso tenha selecionado uma disciplina e deseja remover a seleção, aperte ';
      sHelpMsgBox += 'Ctrl e clique na disciplina selecionada.';
    
  if (this.oView != "") {
    this.oView.innerHTML = sConteudo;
  } else {
    
    this.oWindowObservacao.setContent(sConteudo);
    this.oMessageBoard = new DBMessageBoard('msgBoardLancamentoObservacao', 
                                            sMsg,
                                            sHelpMsgBox,
                                            this.oWindowObservacao.getContentContainer()
                                           );
    
    if (this.oWindowContainer != null) {
      this.oWindowObservacao.setChildOf(this.oWindowContainer);
    }
    this.oWindowObservacao.show();
    
  } 
};

/**
 * Monta o combobox das disciplinas
 * @returns void 
 */
DBViewAvaliacao.LancamentoObservacao.prototype.montaComboDisciplinas = function () {
  
  this.oReplicaDisciplina = new DBViewAvaliacao.DisciplinaTurma(this.oDadosTurma.iTurma, this.oDadosTurma.iEtapa, true);
  this.oReplicaDisciplina.naoListarAsRegencias([this.oDadosTurma.iRegencia]);
  this.oReplicaDisciplina.setAltura('200px');
  this.oReplicaDisciplina.setLargura('250px');
  this.oReplicaDisciplina.show($('replicaDisciplinas'));
  
};


/**
 * Criar o combobox dos alunos da turma
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao.prototype.montaComboAlunos = function () {
  
  this.oAlunoTurma = new DBViewAvaliacao.AlunoTurma(this.oDadosTurma.iTurma, this.oDadosTurma.iEtapa);
  this.oAlunoTurma.lTrazerAlunosEncerrados = false;
  this.oAlunoTurma.onChangeCallBack(this.validaFiltrosSelecionados, this);
  this.oAlunoTurma.setLargura('100%');
  this.oAlunoTurma.show($('ctnAlunoTurma'));
};

/**
 * Criar o combobox dos períodos de avaliação da turma
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao.prototype.montaComboPeriodos = function () {
  
  /**
   * Não é possível lançar Observação para Resultados que não sejam RF (Resultado Final)
   * Só pode ser usado como filtro para tipo de busca: 1 e 4 
   */
  this.oPeriodoTurma = new DBViewAvaliacao.PeriodoTurma(this.oDadosTurma.iTurma, this.oDadosTurma.iEtapa, false);
  this.oPeriodoTurma.defineTipoBusca(4);
  this.oPeriodoTurma.setDisable();
  this.oPeriodoTurma.setLargura('100%');
  this.oPeriodoTurma.onChangeCallBack(this.validaFiltrosSelecionados, this);
  this.oPeriodoTurma.show($('ctnPeriodoTurma'));
};

/**
 * Valida a seleção dos filtros Aluno e Periodo
 * Somente quando this.lExibeFiltros == true
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao.prototype.validaFiltrosSelecionados = function () {
    
  var iMatricula = this.oAlunoSelecionado.iMatricula;
  var iPeriodo   = this.oAlunoSelecionado.iPeriodo;
  if (this.lFiltroAluno) {
    iMatricula = this.oAlunoTurma.getSelecionados()[0];
  }
  
  if (this.lFiltroPeriodo) {
    iPeriodo = this.oPeriodoTurma.getSelecionados()[0];
  }
  if (iMatricula == '') {
    
    this.oPeriodoTurma.setValor('');
    this.oPeriodoTurma.setDisable();
    this.oTextObservacao.value = '';
    this.oBtnSalvar.disabled   = true;
  }
  
  if (iPeriodo == '') {
    
    this.oTextObservacao.value = '';
    this.oBtnSalvar.disabled   = true;
  }
  
  if (iMatricula != '') {
    this.oPeriodoTurma.removeDisable();
  }
  
  
  if (iPeriodo != '' && iMatricula != '') {
    this.buscaObservacaoLancada();
  }
};


/**
 * Valida os dados do aluno conforme parâmetro this.lExibeFiltros
 * Se o filtros de aluno e período estão presente na interface, só podemos deixar salvar uma observação
 * após selecionar o aluno e o período
 * @returns Object
 */
DBViewAvaliacao.LancamentoObservacao.prototype.validaAlunoConformeInterface = function () {
  
  if ( this.lFiltroAluno && this.oAlunoTurma.getSelecionados().length > 0 ) {      
    this.oAlunoSelecionado.iMatricula = this.oAlunoTurma.getSelecionados()[0];
  }
  if ( this.lFiltroPeriodo && this.oPeriodoTurma.getSelecionados().length > 0 ) {     

    this.oAlunoSelecionado.iPeriodo       = this.oPeriodoTurma.getSelecionados()[0];
    this.oAlunoSelecionado.sTipoAvaliacao = this.oPeriodoTurma.getTipoPeriodoSelecionados()[0];
  }
  
  if ( this.oAlunoSelecionado.iMatricula == '' || this.oAlunoSelecionado.iPeriodo == '') {
    return false;
  }
  
  return this.oAlunoSelecionado;
};

/**
 * Verifica se existe Observação lançada para regencia selecionada
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao.prototype.buscaObservacaoLancada = function () {
  
  var oSelf = this;
  
  if (!this.validaAlunoConformeInterface()) {
     
    this.oBtnSalvar.disabled = true;
    alert('Aluno ou período não foi informado.\nInforme um aluno e um período.');
    return false;  
  };
  
  this.oBtnSalvar.removeAttribute("disabled");
  
  var oParametro            = new Object();
  oParametro.exec           = 'getObservacaoAluno';
  oParametro.iMatricula     = this.oAlunoSelecionado.iMatricula;
  oParametro.iPeriodo       = this.oAlunoSelecionado.iPeriodo;
  oParametro.iRegencia      = this.oDadosTurma.iRegencia;
  oParametro.sTipoAvaliacao = this.oAlunoSelecionado.sTipoAvaliacao;

  var oObjeto        = new Object();
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                         oSelf.retornoObservacaoLancada(oAjax);
                       };
  js_divCarregando("Aguarde, verificando observações lançadas.", "msgBox");
  new Ajax.Request(this.sUrlRPC, oObjeto);
};

/**
 * Retorno da busca pela observação.
 * @param oAjax
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao.prototype.retornoObservacaoLancada = function (oAjax) {

  var oSelf = this;
  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')'); 
  
  oSelf.oTextObservacao.innerHTML = '';
  oSelf.oTextObservacao.value = '';
  if (oRetorno.sObservacao != '') {
    
    oSelf.oTextObservacao.innerHTML = oRetorno.sObservacao.urlDecode();
    oSelf.oTextObservacao.value     = oRetorno.sObservacao.urlDecode();
  }
};

/**
 * Chama as funções responsáveis para renderizar a view
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao.prototype.show = function() {
  
  var oSelf = this;
  this.criarWindow();
  $('filtrosDadosAluno').style.display = "";

  /**
   * Validamos se devemos buscar visualizar os filtros (Aluno e Período)
   */
  $('filtrosDadosAluno').style.display = "none";
  if (this.lFiltroAluno) {
        
    $('filtrosDadosAluno').style.display = "";
    $('ctnFiltroAluno').style.display    = "";
    this.montaComboAlunos();
  }
  
  if (this.lFiltroPeriodo) {
    
    $('filtrosDadosAluno').style.display = "";    
    $('ctnFiltroPeriodo').style.display = "";
    this.montaComboPeriodos(); 
    this.oPeriodoTurma.removeDisable();
  }
  if (this.validaAlunoConformeInterface()) {
    this.buscaObservacaoLancada();
  }
  this.montaComboDisciplinas();
  $('cntTextObservacao').appendChild(this.oTextObservacao);
  $('ctnSalvarObservacao').appendChild(this.oBtnSalvar);
  $('btnSalvarObservacao').onclick = function () {
    oSelf.salvar();
  };
  /**
   * Calculamos o tamanho da DIV com o formulário
   */
  var iAlturaDivFormulario = ($('ctnLancamentoObservacao').offsetHeight) ;
  if (iAlturaDivFormulario > this.iAlturaJanela) {
    
    iAlturaDivFormulario = (80*(iAlturaDivFormulario) /100) - 30;
    $('ctnFormularioObservacao').style.borderBottom = '2px groove white';
  }
  $('ctnFormularioObservacao').style.height = iAlturaDivFormulario+"px";
};


/**
 * Salva a observação lançada para regencia selecionada e replica a mesma observação para as regencias selecionadas
 * no comboBox "Replicar observação para disciplinas"
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao.prototype.salvar = function () {
  
  var oSelf = this;
  
  if (!this.validaAlunoConformeInterface()) {
  
    alert('Selecione um aluno e um período para lançamento da observação.');
    return false;
  }
  
  var oParametro = new Object();
  var aRegencias = this.oReplicaDisciplina.getSelecionados();
  aRegencias.push(this.oDadosTurma.iRegencia);
  
  oParametro.exec           = 'salvarObservacao';
  oParametro.iMatricula     = this.oAlunoSelecionado.iMatricula;
  oParametro.iPeriodo       = this.oAlunoSelecionado.iPeriodo;
  oParametro.sTipoAvaliacao = this.oAlunoSelecionado.sTipoAvaliacao;
  oParametro.aRegencias     = aRegencias;
  oParametro.sObservacao    = encodeURIComponent(tagString($F('observacaoLancada')));
  
  var oObjeto        = new Object();
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametro);
  oObjeto.onComplete = function(oAjax) {
                         oSelf.retornoSalvar(oAjax);
                       };
  js_divCarregando("Aguarde, salvando observação.", "msgBox");
  new Ajax.Request(this.sUrlRPC, oObjeto);
};

/**
 * Retorno do salvar
 * @param oAjax
 * @returns void
 */
DBViewAvaliacao.LancamentoObservacao.prototype.retornoSalvar = function (oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')'); 
  
  alert(oRetorno.message.urlDecode());
  this.oReplicaDisciplina.removerSelecao();
  this.oTextObservacao.innerHTML = '';
  this.oTextObservacao.value     = '';
  this.oBtnSalvar.disabled       = true;
  
  if (this.lExibeFiltros) {
    
    this.oAlunoTurma.setValor('');
    this.oPeriodoTurma.setValor('');
    this.oPeriodoTurma.setDisable();
  }
};