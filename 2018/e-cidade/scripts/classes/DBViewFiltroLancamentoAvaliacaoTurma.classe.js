require_once("scripts/classes/DBViewArvoreTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacaoTurma.classe.js");
require_once("scripts/classes/DBViewLancamentoAvaliacaoParecer.classe.js");
require_once("scripts/classes/DBViewLancamentoParecerDisciplina.classe.js");
require_once("scripts/classes/DBViewEncerramentoAvaliacoesFiltro.classe.js");
/**
 * Cria uma view com filtros para o lancamento das avaliacoes
 */
DBViewFiltroLancamentoAvaliacaoTurma = function (sId, nameInstance ) {

  /**
   * @var {string} id
   */
  this.id           = sId;
  this.nameInstance = nameInstance;

  this.oTurmaMarcada = '';

  this.iTurma = null;
  this.iEtapa = null;
  this.sTurma = '';

  this.lInstanciouViewEncerramento = false;

  /**
   * Controle se a viewDBLancamentoAvaliacaoAluno esta aberta;
   * @var {bool}
   */
  this.oJanelaFiltroAberta = false;
  /**
   * RPC para executar as chamadas necessárias para a criação da arvore
   * @var {String} sRPC
   */
  this.sRPC = 'edu4_lancamentoavaliacoesturma.RPC.php';

  /**
   * Instancia da ViewArvoreTurma
   * @var {DBViewArvoreTurma} oViewArvoreTurma
   */
  this.oViewArvoreTurma = null;

  /**
   * Instancia de DBGrid
   * @var {DBGrid} oGridAulasDadas
   */
  this.oGridAulasDadas = null;

  /**
   * Numero de Periodos na Turma selecionada.
   * @var {integer} iNumeroPeriodosTurma
   */
  this.iNumeroPeriodosTurma = 0;

  this.oCtnView               = document.createElement('div');
  this.oCtnView.style.height  = '90%';
  this.oCtnView.style.display = 'block';

  this.oCtnViewFieldSet        = document.createElement('fieldset');
  this.oCtnViewFieldSet.style.height = '100%';

  /**
   * Legenda do container
   * @var {Element} oLegendDadosEscola
   */
  this.oLegendView                  = document.createElement('legend');
  this.oLegendView.style.fontWeight = 'bold';
  this.oLegendView.innerHTML        = 'Lançamento de Avaliação por Turma';

  this.oCtnViewFieldSet.appendChild(this.oLegendView);

  /**
   * Cria um container para receber a arvore das turmas
   * @var {Element} oCtnTreeView
   */
  this.oCtnTreeView                = document.createElement('div');
  this.oCtnTreeView.id             = 'ctnTreeView';
  this.oCtnTreeView.style.cssFloat = 'left';
  this.oCtnTreeView.style.width    = '29%';
  this.oCtnTreeView.style.padding  = '0';
  this.oCtnTreeView.style.margin   = '0';

  /**
   * Cria um container para receber as informações das turmas.
   * @var {Element} oCtnDadosEscola
   */
  this.oCtnDadosEscola                = document.createElement('div');
  this.oCtnDadosEscola.id             = 'ctnDadosEscola';
  this.oCtnDadosEscola.style.cssFloat = 'right';
  this.oCtnDadosEscola.style.width    = '70%';
  this.oCtnDadosEscola.style.height   = '100%';
  this.oCtnDadosEscola.style.padding  = '0';
  this.oCtnDadosEscola.style.margin   = '0';

  /**
   * Cria um fieldset agrupando os dados da escola
   * @var {Element} oCtnFieldsetDadosEscola
   */
  this.oCtnFieldsetDadosEscola              = document.createElement('fieldset');

  /**
   * Legenda do dados da escola
   * @var {Element} oLegendDadosEscola
   */
  this.oLegendDadosEscola                  = document.createElement('legend');
  this.oLegendDadosEscola.style.fontWeight = 'bold';
  this.oLegendDadosEscola.innerHTML        = 'Dados da Escola';

  /**
   * Cria um fieldset agrupando os dados das disciplinas e periodos
   * @var {Element} oCtnFieldsetAulasDadas
   */
  this.oCtnFieldsetAulasDadas = document.createElement('fieldset');

  /**
   * Cria a legenda para o Fieldset de aulas dadas
   * @var {Element} oLegendAulasDadas
   */
  this.oLegendAulasDadas                  = document.createElement('legend');
  this.oLegendAulasDadas.style.fontWeight = 'bold';
  this.oLegendAulasDadas.innerHTML        = 'Aulas Dadas';

  /**
   * Cria a tabela com os dados da escola
   * @var {Element}
   */
  this.oTabelaDadosEscola             = document.createElement('table');
  this.oTabelaDadosEscola.style.width = '100%';
  this.oTabelaDadosEscola.cellspacing = '1';

  this.lProfessorLogado = false;
  /**
   * Estrutura da tabela dos dados da escola
   * (Linhas e colunas)
   * @var {string} sDadosEscola
   */
  var sDadosEscola  = "<tr>";
      sDadosEscola += "  <td style='width:150px;'><b>Escola:</b></td>";
      sDadosEscola += "  <td id='nomeEscola' style='background-color:#FFF;'></td>";
      sDadosEscola += "  <td style='width:100px;'><b>Calendário:</b></td>";
      sDadosEscola += "  <td id='nomeCalendario' style='background-color:#FFF;'></td>";
      sDadosEscola += "</tr>";
      sDadosEscola += "<tr>";
      sDadosEscola += "  <td><b>Curso:</b></td>";
      sDadosEscola += "  <td id='nomeCurso' style='background-color:#FFF;'></td>";
      sDadosEscola += "  <td><b>Base Currícular:</b></td>";
      sDadosEscola += "  <td id='nomeBase' style='background-color:#FFF;'></td>";
      sDadosEscola += "</tr>";
      sDadosEscola += "<tr>";
      sDadosEscola += "  <td><b>Proc. Avaliação:</b></td>";
      sDadosEscola += "  <td id='nomeAvaliacao' style='background-color:#FFF;'></td>";
      sDadosEscola += "  <td><b>Turno:</b></td>";
      sDadosEscola += "  <td id='nomeTurno' style='background-color:#FFF;'></td>";
      sDadosEscola += "</tr>";
      sDadosEscola += "<tr>";
      sDadosEscola += "  <td><b>Frequência por:</b></td>";
      sDadosEscola += "  <td id='nomeFrequencia' colspan='3' style='background-color:#FFF;'></td>";
      sDadosEscola += "</tr>";
      sDadosEscola += "<tr >";
      sDadosEscola += "  <td><b>Avaliações Encerradas: </b></td>";
      sDadosEscola += "  <td id='avalEncerradas' colspan='3' style='background-color:#FFF;'></td>";
      sDadosEscola += "</tr>";
      sDadosEscola += "<tr style='display:none'>";
      sDadosEscola += "  <td colspan='4'><span id='iTurma'></span><span id='iEtapa'></span></td>";
      sDadosEscola += "</tr>";

  this.oTabelaDadosEscola.innerHTML = sDadosEscola;

  /**
   * Botão para efetuar o lançamento das avaliações
   * @var {Element} oBtnLancaAvaliacao
   */
  this.oBtnLancaAvaliacao                   = document.createElement('input');
  this.oBtnLancaAvaliacao.type              = 'button';
  this.oBtnLancaAvaliacao.value             = 'Lançar Avaliações';
  this.oBtnLancaAvaliacao.name              = 'lancarAvaliacoes';
  this.oBtnLancaAvaliacao.id                = 'lancarAvaliacoes';
  this.oBtnLancaAvaliacao.style.marginRight = '5px';
  this.oBtnLancaAvaliacao.style.height      = '20px';
  this.oBtnLancaAvaliacao.disabled          = true;

  /**
   * Botao para encerramento das avalicoes de uma turma
   */
  this.oBtnEncerrarAvaliacoes                   = document.createElement('input');
  this.oBtnEncerrarAvaliacoes.type              = 'button';
  this.oBtnEncerrarAvaliacoes.value             = 'Encerrar Avaliações';
  this.oBtnEncerrarAvaliacoes.name              = 'encerrarAvaliacoes';
  this.oBtnEncerrarAvaliacoes.id                = 'encerrarAvaliacoes';
  this.oBtnEncerrarAvaliacoes.style.marginRight = '5px';
  this.oBtnEncerrarAvaliacoes.style.height      = '20px';
  this.oBtnEncerrarAvaliacoes.style.display     = 'none';
  this.oBtnEncerrarAvaliacoes.disabled          = true;

  /**
   * Botao para cancelamento do encerramento das avalicoes de uma turma
   */
  this.oBtnCancelarEncerramentoAvaliacoes                   = document.createElement('input');
  this.oBtnCancelarEncerramentoAvaliacoes.type              = 'button';
  this.oBtnCancelarEncerramentoAvaliacoes.value             = 'Cancelar Encerramento das Avaliações';
  this.oBtnCancelarEncerramentoAvaliacoes.name              = 'cancelarEncerramentoAvaliacoes';
  this.oBtnCancelarEncerramentoAvaliacoes.id                = 'cancelarEncerramentoAvaliacoes';
  this.oBtnCancelarEncerramentoAvaliacoes.style.marginRight = '5px';
  this.oBtnCancelarEncerramentoAvaliacoes.style.height      = '20px';
  this.oBtnCancelarEncerramentoAvaliacoes.style.display     = 'none';
  this.oBtnCancelarEncerramentoAvaliacoes.disabled          = false;

  /**
   * Container para os botões
   * @var {Element} oCtnBotao
   */
  this.oCtnBotao              = document.createElement('div');
  this.oCtnBotao.style.margin = '5px auto';
  this.oCtnBotao.style.width  = '550px';

  /**
   * Adiciona os elementos em sua devida estrutura
   */
  this.oCtnFieldsetDadosEscola.appendChild(this.oLegendDadosEscola);
  this.oCtnFieldsetAulasDadas.appendChild(this.oLegendAulasDadas);
  this.oCtnDadosEscola.appendChild(this.oCtnFieldsetDadosEscola);
  this.oCtnDadosEscola.appendChild(this.oCtnFieldsetAulasDadas);

  this.oCtnFieldsetDadosEscola.appendChild(this.oTabelaDadosEscola);

  this.oCtnViewFieldSet.appendChild(this.oCtnDadosEscola);
  this.oCtnViewFieldSet.appendChild(this.oCtnTreeView);
  this.oCtnView.appendChild(this.oCtnViewFieldSet);

  this.oCtnBotao.appendChild(this.oBtnLancaAvaliacao);
  this.oCtnBotao.appendChild(this.oBtnEncerrarAvaliacoes);
  this.oCtnBotao.appendChild(this.oBtnCancelarEncerramentoAvaliacoes);
  this.oCtnView.appendChild(this.oCtnBotao);
};

/**
 * Realiza a busca dos periodos de avaliacao.
 * @param {integer} iCodigoTurma
 * @param {integer} iCodigoEtapa
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.renderizaCabecalhoGrid = function (iCodigoTurma, iCodigoEtapa) {

  var oParametro    = new Object();
  oParametro.exec   = 'getPeriodosAvaliacao';
  oParametro.iEtapa = iCodigoEtapa;
  oParametro.iTurma = iCodigoTurma;

  var oSelf = this;

  js_divCarregando("Aguarde, carregando dados da escola.", "msgBox");
  new Ajax.Request(this.sRPC,
                   { method:     'post',
                     asynchronous: false,
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: function(oAjax) {
                                   oSelf.retornoCabecalhoGrig(oAjax);
                                 }
                   }
                  );
};

/**
 * Renderiza os dados do capaçalho.
 * @param {String} oAjax
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.retornoCabecalhoGrig = function (oAjax) {

  js_removeObj("msgBox");

  /**
   * limpa o container da grid e destrói a instância
   */
  this.oCtnFieldsetAulasDadas.innerHTML = '';
  delete this.oGridAulasDadas;

  this.oGridAulasDadas              = new DBGrid('gridAulasDadas');
  this.oGridAulasDadas.nameInstance = this.td + 'oGridAulasDadas';

  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.aPeriodos.length == 0) {
    alert('Não foi possivel carregar os períodos da turma.');
  }
  this.lProfessorLogado     = oRetorno.lProfessorLogado;
  this.iNumeroPeriodosTurma = oRetorno.aPeriodos.length;

  var aCellWidth = new Array("30%");
  var aCellAlign = new Array("left");
  var aHeader    = new Array('Disciplina');

  oRetorno.aPeriodos.each(function (oPeriodo, iSeq) {

    aHeader.push(oPeriodo.sAbreviatura.urlDecode());
    aCellAlign.push('center');
  });

  this.oGridAulasDadas.aHeaders = new Array();
  this.oGridAulasDadas.setCellWidth(aCellWidth);
  this.oGridAulasDadas.setCellAlign(aCellAlign);
  this.oGridAulasDadas.setHeader(aHeader);
  this.oCtnFieldsetAulasDadas.style.width = '96%';
  this.oGridAulasDadas.setHeight(this.oCtnFieldsetAulasDadas.getHeight() - 75);
  this.oGridAulasDadas.show(this.oCtnFieldsetAulasDadas);
  this.oCtnFieldsetAulasDadas.appendChild(this.oLegendAulasDadas);
};

/**
 * Realiza a requisição dos dados da turma
 * @param {integer} iCodigoTurma
 * @param {integer} iCodigoEtapa
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.buscaDadosDaTurma = function(iCodigoTurma, iCodigoEtapa) {

  var oParametro    = new Object();
  oParametro.exec   = 'getDadosTurma';
  oParametro.iEtapa = iCodigoEtapa;
  oParametro.iTurma = iCodigoTurma;

  var oSelf = this;

  js_divCarregando("Aguarde, carregando dados da escola.", "msgBox");
  new Ajax.Request(this.sRPC,
                   { method:     'post',
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: function(oAjax) {
                                   oSelf.retornoDadosDaTurma(oAjax);
                                 }
                   }
                  );
};

/**
 * Renderiza os dados buscados preenchendo os campos:
 * -- Dados da Escola
 * -- Aulas Dadas
 * @param {string} oAjax
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.retornoDadosDaTurma = function(oAjax) {

  js_removeObj("msgBox");

  $('encerrarAvaliacoes').disabled             = true;

  /**
   * variável oSelf identifica a instância da classe DBViewFiltroLancamentoAvaliacaoTurma
   */
  var oSelf    = this;
  var oRetorno = eval('('+oAjax.responseText+')');

  oDadosTurmaSelecionada = oRetorno;

  $('iTurma').innerHTML         = oRetorno.iTurma;
  $('iEtapa').innerHTML         = oRetorno.iEtapa;
  $('nomeEscola').innerHTML     = oRetorno.sEscola.urlDecode();
  $('nomeCalendario').innerHTML = oRetorno.sCalendario.urlDecode();
  $('nomeCurso').innerHTML      = oRetorno.sCurso.urlDecode();
  $('nomeBase').innerHTML       = oRetorno.sBaseCurricular.urlDecode();
  $('nomeAvaliacao').innerHTML  = oRetorno.sProcedimentoAvaliacao.urlDecode();
  $('nomeTurno').innerHTML      = oRetorno.sTurno.urlDecode();
  $('nomeFrequencia').innerHTML = oRetorno.sFrequencia.urlDecode();
  $('avalEncerradas').innerHTML = oRetorno.lTurmaEncerrada ? "Sim" : "Não";
  this.iTurma                   = oRetorno.iTurma;
  this.iEtapa                   = oRetorno.iEtapa;
  this.sTurma                   = oRetorno.sTurma.urlDecode();

  if (!oRetorno.lBloqueiaEncerramento) {

    if (!oSelf.lProfessorLogado) {

      oSelf.oBtnCancelarEncerramentoAvaliacoes.style.display = '';
      oSelf.oBtnEncerrarAvaliacoes.style.display             = '';
    }

    if (oRetorno.lTurmaEncerradaParcial) {

      $('encerrarAvaliacoes').disabled             = false;
    } else if (oRetorno.lTurmaEncerrada && !oRetorno.lTurmaEncerradaParcial) {
      $('encerrarAvaliacoes').disabled             = false;
    }else {
      $('encerrarAvaliacoes').disabled = false;
    }
  }

  this.oGridAulasDadas.clearAll(true);

  /**
   * Utilizado para setar a função onchange em cada coluna dinâmicamente
   *
   * Array de objetos com os dados:
   * -- id: identificador de cada linha e coluna dos períodos
   * -- iDisciplina: regência da turma
   * -- iPeriodo: codigo do período da turma
   */
  var aIdDadosEventosDisciplina = new Array();

  oRetorno.aDisciplinas.each(function(oDisciplina, iSeq) {

    var aLinha = new Array();
    aLinha[0]  = oDisciplina.sDescricao.urlDecode();


    var sReadonly = '';
    var sStyle    = '';
    if (oDisciplina.lEncerrada || oDisciplina.lTratada) {

      sStyle    = "background-color:rgb(222, 184, 135); color:#000;";
      sReadonly = "readonly='readonly'";
    }

    oDisciplina.aPeriodos.each(function(oPeriodo, iPeriodo) {

      var sId                   = oDisciplina.iCodigo + oDisciplina.sAbrev.urlDecode() + iPeriodo;
      var oDadosPeriodo         = new Object();
      oDadosPeriodo.id          = sId;
      oDadosPeriodo.iDisciplina = oDisciplina.iCodigo;
      oDadosPeriodo.iPeriodo    = oPeriodo.iCodigo;

      aIdDadosEventosDisciplina.push(oDadosPeriodo);

      var sInput = " <input type='text' id='"+sId+"' name='"+sId+"' value='"+oPeriodo.iAulas+"' ";
      sInput    += "        onkeyPress='return js_mask(event, \"0-9\");' maxlength = '4'";
      sInput    += "        style='text-align:right;width: 100%; padding-left: 2px; " + sStyle +"' " + sReadonly + " />";
      aLinha[iPeriodo+1] = sInput;
    });

    oSelf.oGridAulasDadas.addRow(aLinha);
  });

  this.oGridAulasDadas.renderRows();

  this.setaFuncaoCelula( aIdDadosEventosDisciplina );

  $('lancarAvaliacoes').removeAttribute("disabled");

  $('lancarAvaliacoes').onclick = function() {

    oSelf.criarInstanciaLancamentoAvaliacoes(oRetorno);

    if (oSelf.oJanelaFiltroAberta) {
      return true;
    }
    oSelf.oInstanceAvaliacaoTurma.setCallBackCloseWindow(function() {
      oSelf.oJanelaFiltroAberta = false;
    });
    oSelf.oJanelaFiltroAberta = true;

    oSelf.oInstanceAvaliacaoTurma.show();
  };

  this.oCtnFieldsetAulasDadas.style.height = (this.oCtnView.getHeight() - this.oCtnFieldsetDadosEscola.getHeight()) - 30;
  this.oGridAulasDadas.setHeight(this.oCtnFieldsetAulasDadas.getHeight() - 75);
  this.oGridAulasDadas.show(this.oCtnFieldsetAulasDadas);
  this.oGridAulasDadas.renderRows();
  this.oCtnFieldsetAulasDadas.appendChild(this.oLegendAulasDadas);

  this.setaFuncaoCelula( aIdDadosEventosDisciplina );
};

DBViewFiltroLancamentoAvaliacaoTurma.prototype.criarInstanciaLancamentoAvaliacoes = function (oDadosTurma) {
  this.oInstanceAvaliacaoTurma = new DBViewLancamentoAvaliacaoTurma(oDadosTurma);
};

/**
 * Salva o dado de um período para uma regência.
 * @param {integer} iRegencia código da regência
 * @param {integer} iPeriodoAvaliacao código do período de avaliação
 * @param {integer  } iTotalAulas número de aulas definidas para o período
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.salvarAulasDadas = function (iRegencia, iPeriodoAvaliacao, iTotalAulas) {

  var oParametro               = new Object();
  oParametro.exec              = 'salvarAulasDadas';
  oParametro.iRegencia         = iRegencia;
  oParametro.iPeriodoAvaliacao = iPeriodoAvaliacao;
  oParametro.iTotalAulas       = iTotalAulas;

  var oSelf = this;

  js_divCarregando("Aguarde, salvando...", "msgBox");

  new Ajax.Request(this.sRPC,
                   { method:     'post',
                     parameters: 'json='+Object.toJSON(oParametro),
                     onComplete: function(oAjax) {
                                   oSelf.retornoSalvarAulasDadas(oAjax);
                                 }
                   }
                  );
};

/**
 * Trata o retorno da pesistência dos dados.
 * @param {string} oAjax
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.retornoSalvarAulasDadas = function(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');

  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }
};

/**
 * Callback do onclik da turma
 * @param {integer} iCodigoTurma codigo da turma
 * @param {integer} iCodigoEtapa codigo da etapa
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.getDados = function(iCodigoTurma, iCodigoEtapa) {

  this.renderizaCabecalhoGrid(iCodigoTurma, iCodigoEtapa);
  this.buscaDadosDaTurma(iCodigoTurma, iCodigoEtapa);
};

/**
 * Renderiza a view.
 * @param {Element} oElement
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.show = function(oElement) {

  oElement.style.position = 'relative';
  oElement.style.height   = '100%';

  oElement.appendChild(this.oCtnView);

  /**
   * Calcula o tamanho do oCtnFieldsetAulasDadas
   */
  this.oCtnFieldsetAulasDadas.style.marginTop = '3px';
  this.oCtnFieldsetAulasDadas.style.height    = (this.oCtnView.getHeight() - this.oCtnFieldsetDadosEscola.getHeight()) - 30;

  var oSelf = this;

  /**
   * Intancia a DBViewArvoreTurma
   */
  this.oViewArvoreTurma = new DBViewArvoreTurma('ArvoreFiltroLancamento');
  this.oViewArvoreTurma.setCheckBox(false);
  this.oViewArvoreTurma.afterLoad(function(oDadosRequisicao) {

    oSelf.lProfessorLogado = oDadosRequisicao.lProfessorLogado;
    if (!oSelf.lProfessorLogado) {

      oSelf.oBtnCancelarEncerramentoAvaliacoes.style.display = '';
      oSelf.oBtnEncerrarAvaliacoes.style.display             = '';
    }
  });
  this.oViewArvoreTurma.setCallBackCliqueTurma(function(oTurma, iEtapa) {
    if (oSelf.oTurmaMarcada != '') {
      oSelf.oTurmaMarcada.select(false);
    }
    oTurma.select(true);
    oSelf.oTurmaMarcada = oTurma;
    oSelf.getDados(oTurma.iTurma, iEtapa);
  });
  this.oViewArvoreTurma.show(this.oCtnTreeView);

  $('encerrarAvaliacoes').observe("click", function(event) {

    if( oSelf.lInstanciouViewEncerramento ) {
      return;
    }

    var oParam = {exec:'validarEncerramentoDaTurma', iTurma:oSelf.iTurma, iEtapa:oSelf.iEtapa}
    js_divCarregando("Aguarde, carregando dados do encerramento...", "msgBox");
    new Ajax.Request(oSelf.sRPC,
                     { method:     'post',
                       parameters: 'json='+Object.toJSON(oParam),
                       onComplete: function(oAjax) {

                         js_removeObj('msgBox');
                         var oRetorno = eval("("+oAjax.responseText+")");
                         if (oRetorno.status == 1) {
                           oSelf.encerramentoAvaliacoes('1');
                         } else {
                           alert(oRetorno.message.urlDecode());
                         }
                       }
                     }
                    );

  });

  $('cancelarEncerramentoAvaliacoes').observe("click", function(event) {
    oSelf.encerramentoAvaliacoes('2');
  });
};

/**
 * Abre o encerramento/cancelamento das avaliacoes
 * @param integer iAcao
 * 1 - Encerrar
 * 2 - Cancelar
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.encerramentoAvaliacoes = function( iAcao ) {

  var oSelf = this;

  if( oSelf.lInstanciouViewEncerramento ) {
    return;
  }

  oSelf.lInstanciouViewEncerramento = true;

  var sTitulo         = 'Encerramento / Cancelamento de Avaliações';
  oJanelaEncerramento = new windowAux('wndEncerramento', sTitulo, 1026, 764);

  var sConteudo  = "<div id='ctnEncerramento2'></div>";
  oJanelaEncerramento.setContent(sConteudo);
  oEncerramento = new DBViewEncerramentoAvaliacoesFiltro("oEncerramento", iAcao);
  oEncerramento.setTurmaEtapa(oSelf.iTurma, oSelf.iEtapa, oSelf.sTurma);
  oEncerramento.setCallbackProcessar(function(aTurmas) {

    aTurmas.each(function(oTurma){
      oSelf.buscaDadosDaTurma(oTurma.iTurma, oTurma.iEtapa);
    });
  });

  oJanelaEncerramento.setShutDownFunction(function() {

    oJanelaEncerramento.destroy();
    oSelf.lInstanciouViewEncerramento = false;
  });

  oJanelaEncerramento.show();
  oEncerramento.show($('ctnEncerramento2'));
};

/**
 * Seta a função para as células de aulas dadas
 * @param aIdDadosEventosDisciplina
 */
DBViewFiltroLancamentoAvaliacaoTurma.prototype.setaFuncaoCelula = function( aIdDadosEventosDisciplina ) {

  var oSelf = this;

  aIdDadosEventosDisciplina.each(function(oEvento) {

    $(oEvento.id).onchange = function(){
      oSelf.salvarAulasDadas(oEvento.iDisciplina, oEvento.iPeriodo, $F(oEvento.id));
    };
  });
};