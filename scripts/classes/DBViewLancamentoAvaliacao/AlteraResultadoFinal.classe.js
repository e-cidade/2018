require_once("scripts/classes/DBViewLancamentoAvaliacao/DBViewLancamentoAvaliacao.classe.js");

/**
 * Funçao para alterar o resultado final do aluno
 * Aluno já deve possuir resultado final lançado
 * @param {Number} nNota Nota a ser Verificada
 * @param {Number} nVariacao variacao que a nota deve estar .
 * @return boolean
 */
DBViewAvaliacao.AlteraResultadoFinal = function (oTurma, iRegencia, sRegencia) {
  
  /**
   * Dados da turma que esta sendo alterado o resultado final
   * @exemple 
   * ({iTurma:"680", 
   *   iEtapa:"17", 
   *   sEscola:"EMEF BARAO DO RIO BRANCO", 
   *   sCalendario:"CALENDÁC1RIO 2013", 
   *   sCurso:"ENSINO FUNDAMENTAL 9 ANOS", 
   *   sBaseCurricular:"ANOS FINAIS", 
   *   sTurma:"8º ANO", 
   *   sEtapa:"8º ANO", 
   *   sProcedimentoAvaliacao:"NOTA", 
   *   sTurno:"MANHÃ"
   *  }
   * )
   */
  this.oTurma = oTurma;

  /**
   * Informações sobre a regencia que esta sendo trabalhada
   */
  this.iRegencia = iRegencia;
  this.sRegencia = sRegencia;

  /**
   * Variável para receber a intancia da grid 
   * @var {Object}
   */
  this.oGridResultados = {};
  
  /**
   * Rpc utilizado pela classe
   * @var {string}
   */
  this.sRpcPadrao = 'edu4_alteraresultadofinal.RPC.php';


  /** ********************************************************
   *  ----------     Componentes da interface     ------------
   * ********************************************************* */
  this.oCboAlunos    = document.createElement('select');
  this.oCboAlunos.id = 'alunosDisponiveis';
  this.oCboAlunos.add(new Option("Selecione", ""));
  this.oCboAlunos.style.width = '99%';

  this.oCboFormaAprovacao    = document.createElement('select');
  this.oCboFormaAprovacao.id = 'formaAprovacao';
  this.oCboFormaAprovacao.add(new Option('Selecione', ''));
  this.oCboFormaAprovacao.add(new Option('Aprovado pelo conselho', '1'));
  this.oCboFormaAprovacao.add(new Option('Reclassificação por baixa frequência', '2'));
  this.oCboFormaAprovacao.add(new Option('Aprovado Conforme Regimento Escolar', '3'));
  this.oCboFormaAprovacao.style.width = '99%';
  this.oCboFormaAprovacao.disabled    = true;

  this.oInputCodigoProfessor      = document.createElement('input');
  this.oInputCodigoProfessor.type = 'text';
  this.oInputCodigoProfessor.id   = 'codigoProfessor';
  this.oInputCodigoProfessor.name = 'codigoProfessor';
  this.oInputCodigoProfessor.size = 10;
  this.oInputCodigoProfessor.addClassName('readonly');

  this.oInputNomeProfessor      = document.createElement('input');
  this.oInputNomeProfessor.type = 'text';
  this.oInputNomeProfessor.id   = 'nomeProfessor';
  this.oInputNomeProfessor.name = 'nomeProfessor';
  this.oInputNomeProfessor.size = 86;  
  this.oInputNomeProfessor.addClassName('readonly');

  this.oAncoraProfessor      = document.createElement('a');
  this.oAncoraProfessor.href = '#';
  this.oAncoraProfessor.id   = 'ancoraProfessor';
  this.oAncoraProfessor.update('Professor');

  this.oCallBackCloseWindow = function() {
    return true;    
  };
  
};

/**
 * Ao fechar a janela, recarrega a tela anterior
 * @param {Function} fFunction
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.setCallBackWindow = function (fFunction) {
  this.oCallBackCloseWindow = fFunction;
};

/**
 * Seta em cima de qual container irá abrir
 * @param {Object} oWindow
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.setContainer = function (oWindow) {
  this.oWindowContainer = oWindow;
}; 

/**
 * Realia a chamada das funções para renderizar a View
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.show = function () {
  
  var oSelf = this; 
  this.montaJanela();
  $('ctnCboAluno').appendChild(this.oCboAlunos);
  $('ctnProfessor').appendChild(this.oInputCodigoProfessor);
  $('ctnProfessor').appendChild(this.oInputNomeProfessor);
  $('ctnFormaAprovacao').appendChild(this.oCboFormaAprovacao);
  $('ctnLinkProfessor').appendChild(this.oAncoraProfessor);

  $('ancoraProfessor').onclick = function () {
    oSelf.pesquisaProfessor();
  };


  this.getAlunosReprovados();
  this.montaGrid();

  /**
   * Seta funcção no onchange do combo dos alunos 
   */
  $('alunosDisponiveis').onchange = function () {
    oSelf.validaBloqueioOptionFormaAprovacao();
  };

  $('salvarResultado').onclick = function () {
    oSelf.salvarResultadoFinal();
  };
}; 


/**
 * Cria a instancia da View e renderiza
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.montaJanela = function () {
  
  var iAltura  = document.body.clientHeight / 1.4;
  var oSelf    = this;
  
  /**
   * Cria instancia da window
   */
  this.oWindowResultadoFinal = new windowAux("wndResultadoFinal", 
                                             "Alterar Resultado Final", 
                                             900,
                                             iAltura
                                            );
  this.oWindowResultadoFinal.allowCloseWithEsc(false);
  
  /**
   * Seta função para destruir a janela 
   */
  this.oWindowResultadoFinal.setShutDownFunction(function () {
    
    oSelf.oCallBackCloseWindow();
    oSelf.oWindowResultadoFinal.destroy();
  });
  
  var sConteudo  = "<div id='container' >";
      sConteudo += "  <center>";
      sConteudo += "    <div>";
      sConteudo += "      <fieldset>";
      sConteudo += "        <legend>Alterar Resultado Final</legend>";
      sConteudo += "        <table style='width:100%'>";
      sConteudo += "          <tr>";
      sConteudo += "            <td class='bold'>Aluno:</td>";
      sConteudo += "            <td id='ctnCboAluno' nowrap='nowrap'>";
      sConteudo += "            </td>";
      sConteudo += "          </tr>";
      sConteudo += "          </tr>"; 
      sConteudo += "          <tr>";
      sConteudo += "            <td class='bold' id='ctnLinkProfessor'></td>";
      sConteudo += "            <td id='ctnProfessor' nowrap='nowrap'>";
      sConteudo += "            </td>";
      sConteudo += "          </tr>";
      sConteudo += "          <tr nowrap='nowrap'>";
      sConteudo += "            <td class='bold' nowrap='nowrap'>Forma de Aprovação:</td>";
      sConteudo += "            <td id='ctnFormaAprovacao' nowrap='nowrap'>";
      sConteudo += "            </td>";
      sConteudo += "          </tr>";
      sConteudo += "          <tr>";
      sConteudo += "            <td colspan='2'>";
      sConteudo += "              <fieldset class='separator'>";
      sConteudo += "                <legend>Justificativa</legend>";
      sConteudo += "                <textarea id='justificativaResultado' rows='4' style='width:100%'></textarea>";
      sConteudo += "              </fieldset>";
      sConteudo += "            </td>";
      sConteudo += "        </table>";
      sConteudo += "      </fieldset>";
      sConteudo += "      <input type='button' id='salvarResultado' name='salvarResultado' value='Salvar' />";
      sConteudo += "    </div>";
      sConteudo += "    <div>";
      sConteudo += "      <fieldset style='width:97%'>";
      sConteudo += "        <legend>Alterar Resultado Final</legend>";
      sConteudo += "        <div id='ctnGridAlteraResultadoFinal'></div>";
      sConteudo += "      </fieldset>";
      sConteudo += "    </div>";
      sConteudo += "  </center>";
      sConteudo += "</div>";


  var sMsg  = 'Alterar o resultado final para a disciplina: ';
      sMsg += this.sRegencia;
      
  var sHelpMsgBox  = 'Selecione o aluno para realizar a alteração do resultado final';
      sHelpMsgBox += ' para a disciplina: ' + this.sRegencia;
  
  this.oWindowResultadoFinal.setContent(sConteudo);
  new DBMessageBoard('msgBoardAvaliacao', sMsg, sHelpMsgBox, this.oWindowResultadoFinal.getContentContainer());    


  
  if (this.oWindowContainer != '') {
    oSelf.oWindowResultadoFinal.setChildOf(this.oWindowContainer);
  }
  
  this.oWindowResultadoFinal.show();
};

/**
 * 
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.getAlunosReprovados = function () {

  $('alunosDisponiveis').options.length = 0;
  $('alunosDisponiveis').add(new Option("Selecione", ""));
  var oParametro       = new Object();
  oParametro.exec      = 'getAlunosAlteraResultadoFinal';
  oParametro.iTurma    = this.oTurma.iTurma;
  oParametro.iEtapa    = this.oTurma.iEtapa;
  oParametro.iRegencia = this.iRegencia;
  
  var oSelf = this;
  
  js_divCarregando("Aguarde, alunos resultados reprovados.", "msgBox");
  new Ajax.Request(this.sRpcPadrao,
                   { method:     'post',
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: function(oAjax) {
                                   oSelf.retornoAlunosReprovados(oAjax);
                                 }
                   }
                  );
};

DBViewAvaliacao.AlteraResultadoFinal.prototype.retornoAlunosReprovados = function (oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');
  
  oRetorno.aAlunos.each( function (oAluno) {

    var oOption       = document.createElement('option');
    oOption.value     = oAluno.iMatricula;
    oOption.innerHTML = oAluno.sAluno.urlDecode();
    oOption.setAttribute('lReprovadoFrequencia', oAluno.lReprovadoFrequencia);
    oOption.setAttribute('lReprovadoNota', oAluno.lReprovadoNota);

    $('alunosDisponiveis').appendChild(oOption);
  });


};


DBViewAvaliacao.AlteraResultadoFinal.prototype.montaGrid = function () {

  this.oGridResultados              = new DBGrid('gridResultados');
  this.oGridResultados.nameInstance = 'oGridResultados';
  this.oGridResultados.setHeight(100);
  this.oGridResultados.setCellWidth(new Array('30%', '30%', '15%', '15', '10%'));
  this.oGridResultados.setCellAlign(new Array('left', 'left', 'center', 'left', 'center'));
  this.oGridResultados.setHeader(new Array('Aluno', 'Professor', 'Data/Hora', 'Forma Aprovação', 'Ação'));

  this.oGridResultados.show($('ctnGridAlteraResultadoFinal'));

  this.getAlunosAprovadosPeloConselho();
};

/**
 * Busca alunos aprovados pelo conselho
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.getAlunosAprovadosPeloConselho = function () {

  var oParametro       = new Object();
  oParametro.exec      = 'getAlunosAprovadosPeloConselhoPorRegencia';
  oParametro.iTurma    = this.oTurma.iTurma;
  oParametro.iEtapa    = this.oTurma.iEtapa;
  oParametro.iRegencia = this.iRegencia;
  
  var oSelf = this;
  
  this.oGridResultados.clearAll(true);
  js_divCarregando("Aguarde, buscando alunos com resultados alterados.", "msgBox");
  new Ajax.Request(this.sRpcPadrao,
                   { method:     'post',
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: function(oAjax) {
                                   oSelf.populaGrid(oAjax);
                                 }
                   }
                  );
};

/**
 * Renderiza as linhas da grid conforme resultado obtido pela chamada do metodo:
 * DBViewAvaliacao.AlteraResultadoFinal.prototype.buscaDadosGrid
 * @param {Object}
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.populaGrid = function (oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');
  var oSelf    = this;

  this.oGridResultados.clearAll(true);

  oRetorno.aResultados.each( function (oResultado) {

    var oExcluir  = document.createElement('input');
    oExcluir.type = 'button';
    oExcluir.name = 'excluirResultado' + oResultado.iAprovConselho;
    oExcluir.id   = 'excluirResultado' + oResultado.iAprovConselho;
    oExcluir.setAttribute('value', 'E');

    var oCtnAcao  = document.createElement('div');
    oCtnAcao.appendChild(oExcluir);

    var aLinha    = new Array();
        aLinha[0] = oResultado.sAluno.urlDecode();
        aLinha[1] = oResultado.sProfessor.urlDecode();
        aLinha[2] = oResultado.dtData + ' ' + oResultado.sHora;
        aLinha[3] = oResultado.sFormaAprovacao.urlDecode();
        aLinha[4] = oCtnAcao.outerHTML;

    oSelf.oGridResultados.addRow(aLinha);

  });
  
  this.oGridResultados.renderRows();

  /**
   * Seta as funções nos botões de ação em tempo de execução
   */
  oRetorno.aResultados.each( function (oResultado) {  

    $('excluirResultado'+oResultado.iAprovConselho).onclick = function() {
      oSelf.excluirResultadoFinal(oResultado.iMatricula);
    };
  });
};


/**
 * Exclui a linha da grid
 * @param {int} iAprovConselho código sequencial da aprovconselho
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.excluirResultadoFinal = function (iMatricula) {

  if (confirm('Tem certeza que deseja excluir?')) {

    var oParametro        = new Object();
    oParametro.exec       = 'excluirAlteracaoResultadoFinal';
    oParametro.iTurma     = this.oTurma.iTurma;
    oParametro.iEtapa     = this.oTurma.iEtapa;
    oParametro.iRegencia  = this.iRegencia;
    oParametro.iMatricula = iMatricula;

    var oSelf = this;
  
    js_divCarregando("Aguarde, excluindo alteração do resultado.", "msgBox");
    new Ajax.Request(this.sRpcPadrao,
                     { method:     'post',
                       parameters: 'json='+Object.toJSON(oParametro),
                       onComplete: function(oAjax) {
                                     oSelf.retornoExcluirResultadoFinal(oAjax);
                                   }
                     }
                    );
  }
};

DBViewAvaliacao.AlteraResultadoFinal.prototype.retornoExcluirResultadoFinal = function (oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {

     this.getAlunosAprovadosPeloConselho();
     this.getAlunosReprovados();
  }

};


DBViewAvaliacao.AlteraResultadoFinal.prototype.validaFormulario = function () {

  if ($F('alunosDisponiveis') == '') {

    alert('Selecione um aluno.');
    return false;
  }

  if ($F('formaAprovacao') == '') {

    alert('Selecione a forma de aprovação.');
    return false; 
  }

  if ($F('justificativaResultado') == '') {

    alert('Justificativa não informada.');
    return false;
  }

  return true;  
};

/**
 * Inclui uma aprovação pelo conselho 
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.salvarResultadoFinal = function (iAprovConselho) {

  if (!this.validaFormulario()) {
    return false;
  }

  var oParametro             = new Object();
  oParametro.exec            = 'salvarAlteracaoResultadoFinal';
  oParametro.iTurma          = this.oTurma.iTurma;
  oParametro.iEtapa          = this.oTurma.iEtapa;
  oParametro.iRegencia       = this.iRegencia;
  oParametro.iMatricula      = $F('alunosDisponiveis');
  oParametro.iProfessor      = $F('codigoProfessor');
  oParametro.iFormaAprovacao = $F('formaAprovacao');
  oParametro.sJustificativa  = encodeURIComponent(tagString($F('justificativaResultado')));
  
  var oSelf = this;
  
  js_divCarregando("Aguarde, salvando alteração do resultado.", "msgBox");
  new Ajax.Request(this.sRpcPadrao,
                   { method:     'post',
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: function(oAjax) {
                                   oSelf.retornoSalvarResultadoFinal(oAjax);
                                 }
                   }
                  );

};

/**
 * Retorno do: DBViewAvaliacao.AlteraResultadoFinal.prototype.salvarResultadoFinal
 * @param {Object} oAjax
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.retornoSalvarResultadoFinal = function (oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {

    this.getAlunosAprovadosPeloConselho();
    this.getAlunosReprovados();
    this.limpaFormulario();
  }
};

/**
 * Lookup para pesquisar um professor
 */ 
DBViewAvaliacao.AlteraResultadoFinal.prototype.pesquisaProfessor = function () {

  /**
   * ATENÇÃO: a variável 'oInstanciaAlteraResultadoFinal' foi colocado no escopo global 
   * para atender o retorno da lookup. Após receber os dados ela será deletada
   */
  oInstanciaAlteraResultadoFinal = this;
  var sUrl = 'func_rechumano.php?';
  sUrl += 'funcao_js=parent.oInstanciaAlteraResultadoFinal.retornoProfessor|ed20_i_codigo|z01_nome';
  js_OpenJanelaIframe('','db_iframe_rechumano', sUrl, 'Pesquisa Professor', true);
  
  /**
   * Muda o zIndex do iframe de pesquisa para aparecer em cima dos outros frames abertos
   */
  $('Jandb_iframe_rechumano').style.zIndex = 10000;

};

DBViewAvaliacao.AlteraResultadoFinal.prototype.retornoProfessor = function () {

  $('codigoProfessor').setAttribute('value', arguments[0]);
  $('nomeProfessor').setAttribute('value', arguments[1]);
  db_iframe_rechumano.hide();
  delete oInstanciaAlteraResultadoFinal;
};
  

/**
 * Adiciona/Remove bloqueio nos options do select da Forma de Aprovação 
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.validaBloqueioOptionFormaAprovacao = function () {

  $('formaAprovacao').disabled = false;
  var oOptionSelecionado = $('alunosDisponiveis').options[$('alunosDisponiveis').selectedIndex];

  if (oOptionSelecionado.value == '') {
    
    $('formaAprovacao').value    = '';
    $('formaAprovacao').disabled = true;
  }
  
  /**
   * option[1] == Aprovado pelo conselho
   * option[2] == Reclassificação por baixa frequência
   * option[3] == Aprovado por regimento escolar
   */
  $('formaAprovacao').options[1].removeAttribute('disabled');
  $('formaAprovacao').options[2].removeAttribute('disabled');
  $('formaAprovacao').options[3].removeAttribute('disabled');

  if (oOptionSelecionado.getAttribute('lreprovadonota') == 'false') {
    $('formaAprovacao').options[1].setAttribute('disabled', 'disabled');
  }
  if (oOptionSelecionado.getAttribute('lreprovadofrequencia') == 'false') {
    $('formaAprovacao').options[2].setAttribute('disabled', 'disabled');
  }
};

/**
 * Limpa os dados do Formulário
 */
DBViewAvaliacao.AlteraResultadoFinal.prototype.limpaFormulario = function() {

  $('alunosDisponiveis').value      = '';
  $('codigoProfessor').value        = '';
  $('nomeProfessor').value          = '';
  $('formaAprovacao').value         = '';
  $('justificativaResultado').value = '';
  $('formaAprovacao').disabled      = true;
};