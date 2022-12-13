/*
 *      E-cidade Software Publico para Gestao Municipal
 *   Copyright (C) 2014  DBSeller Servicos de Informatica
 *                             www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *
 *   Este programa e software livre; voce pode redistribui-lo e/ou
 *   modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versao 2 da
 *   Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *   Este programa e distribuido na expectativa de ser util, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *   COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *   PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *   detalhes.
 *
 *   Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *   junto com este programa; se nao, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *
 *   Copia da licenca no diretorio licenca/licenca_en.txt
 *                 licenca/licenca_pt.txt
 */

require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");
require_once("scripts/strings.js");

/**
 * Renderiza uma windown com as legendas usadas no Lançamento de avaliação
 *
 * @param {int} iTurma codigo da turma
 * @param {int} iEtapa codigo da etapa
 * @example Exemplo de uso
 * oLegenda = new DBViewFormularioEducacao.LegendasLancamentoAvaliacao(139, 7);
 *
 *  var fCallBack = function () {
 *    alert('caiu');
 *  };
 *  oLegenda.setCallBackShutDown( fCallBack );
 *  oLegenda.getLegendas();
 *  oLegenda.show();
 *
 * @returns {void}
 */
DBViewFormularioEducacao.LegendasLancamentoAvaliacao = function (iTurma, iEtapa) {

  this.iTurma = iTurma;
  this.iEtapa = iEtapa;

  this.sTituloWindow    = "Legendas lançamento de avaliações";
  this.oWindowContainer = '';

  this.aLegendaSituacoesMatricula = [
    { 'abreviatura': 'AVAN',    'descricao' : 'Avançado'},
    { 'abreviatura': 'CANC',    'descricao' : 'Cancelado'},
    { 'abreviatura': 'CLASS',   'descricao' : 'Classificado'},
    { 'abreviatura': 'EVAD',    'descricao' : 'Evadido'},
    { 'abreviatura': 'FALEC',   'descricao' : 'Falecido'},
    { 'abreviatura': 'IN',      'descricao' : 'Matrícula Indeferida'},
    { 'abreviatura': 'MATR',    'descricao' : 'Matriculado'},
    { 'abreviatura': 'MI',      'descricao' : 'Matrícula Indevida'},
    { 'abreviatura': 'MT',      'descricao' : 'Matrícula Trancada'},
    { 'abreviatura': 'RECL',    'descricao' : 'Reclassificado'},
    { 'abreviatura': 'REMATR',  'descricao' : 'Rematriculado'},
    { 'abreviatura': 'TF',      'descricao' : 'Transferido Fora'},
    { 'abreviatura': 'TM',      'descricao' : 'Troca de Modalidade'},
    { 'abreviatura': 'TR',      'descricao' : 'Transferido Rede'},
    { 'abreviatura': 'TT',      'descricao' : 'Troca de Turma'}
  ];

  this.aLegendasResultadoFinal = [
    { 'class' : 'resultadoFinalPadrao',                        'descricao' : 'Aprovação/Reprovação Normal'},
    { 'class' : 'resultadoFinalAprovadoConselho',              'descricao' : 'Aprovado pelo conselho'},
    { 'class' : 'resultadoFinalReclassificadoBaixaFrequencia', 'descricao' : 'Reclassificação por baixa frequência'},
    { 'class' : 'resultadoFinalConformeRegimentoEscolar',      'descricao' : 'Aprovado Conforme Regimento Escolar'}
  ];

  this.aOutrasLegendas = [
    { 'abreviatura': 'NEE',   'descricao' : 'Necessidades Educacionais Especiais'},
    { 'abreviatura': 'AMP',   'descricao' : 'Amparado'},
    { 'abreviatura': 'FA',    'descricao' : 'Faltas abonadas'},
    { 'abreviatura': 'OBS',   'descricao' : 'Observações'},
    { 'abreviatura': 'PAR',   'descricao' : 'Parecer'},
    { 'abreviatura': 'AP/DP', 'descricao' : 'Aprovado com Progressão Parcial'},
    { 'abreviatura': 'REC',   'descricao' : 'Em Recuperação'}
  ];

  this.fCallBackShutDown = function () {};

  /**
   * Elementos HTML da View
   */
  this.oCtnElementos = new Element('div');
  this.oCtnElementos.setAttribute('style', 'display:block; width:100%; position:relative;');

  this.oCtnSituacaoEOutros = new Element('div');
  this.oCtnSituacaoEOutros.setAttribute('style', 'display:block; width:100%; clear:both; ');

  this.oCtnSituacao = new Element('div');
  this.oCtnSituacao.setAttribute('style', 'width:49%; float:left;');

  this.oCtnOutros   = new Element('div');
  this.oCtnOutros.setAttribute('style', 'width:50%; float:left;');


  this.oCtnSituacaoEOutros.appendChild(this.oCtnSituacao);
  this.oCtnSituacaoEOutros.appendChild(this.oCtnOutros);

  this.oFieldsetSituacoesMatricula = new Element('fieldset', {'class':'separator', 'id':'ctnLegendaSituacoes'});
  this.oFieldsetSituacoesMatricula.setAttribute('style', 'width:90%;');
  this.oFieldsetSituacoesMatricula .appendChild( new Element('legend').update('Situações da Matrícula'));
  this.oCtnSituacao.appendChild(this.oFieldsetSituacoesMatricula);

  this.oCtnOutrasLegendas = new Element('fieldset', {'class':'separator', 'id':'ctnLegendaOutros'});
  this.oCtnOutrasLegendas.appendChild( new Element('legend').update('Outras'));

  this.oCtnOutros.appendChild(this.oCtnOutrasLegendas);


  this.oFieldsetResultadoFinal = new Element('fieldset', {'class':'separator', 'id':'ctnLegendaRF'});
  this.oFieldsetResultadoFinal.setAttribute('style', 'display:block; clear:both; width: 95%;');
  this.oFieldsetResultadoFinal.appendChild( new Element('legend').update('Alterações do Resultado Final'));

  this.oCtnElementos.appendChild(this.oCtnSituacaoEOutros);
  this.oCtnElementos.appendChild(this.oFieldsetResultadoFinal);

  MSG_LEGENDASLANCAMENTOAVALIACAO = "educacao.escola.LegendasLancamentoAvaliacao.";
};

/**
 * Retorna todas legendas que provem da base de dados do cliente
 * @returns {undefined}
 */
DBViewFormularioEducacao.LegendasLancamentoAvaliacao.prototype.getLegendas = function () {

  js_divCarregando(_M("educacao.escola.LegendasLancamentoAvaliacao.pesquisando_legendas"), "msgBox");

  this.getLegendasPeriodos();
  this.getTermosResultadoFinal();

  js_removeObj('msgBox');
};

/**
 * Busca descrição para os períodos de avaliação
 * @returns {undefined}
 */
DBViewFormularioEducacao.LegendasLancamentoAvaliacao.prototype.getLegendasPeriodos = function () {

  var oParametros                  = new Object();
  oParametros.exec                 = "pesquisaPeriodosTurma";
  oParametros.iTipoBusca           = null;
  oParametros.iTurma               = this.iTurma;
  oParametros.iEtapa               = this.iEtapa;
  oParametros.lSomenteCargaHoraria = false;

  var oSelf = this;

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametros);
  oObjeto.onComplete = function(oAjax) {

    var oRetorno = eval("(" + oAjax.responseText + ")");

    if (oRetorno.aPeriodos.length == 0) {
      return;
    }
    oRetorno.aPeriodos.each( function (oPeriodo) {

      oSelf.aOutrasLegendas.push( { 'abreviatura': oPeriodo.sAbreviatura.urlDecode(),
                                    'descricao' : oPeriodo.sDescricao.urlDecode()});
    });

  };
  oObjeto.asynchronous = false;

  new Ajax.Request('edu4_turmas.RPC.php', oObjeto);

};

/**
 * Busca os termos do resultado final para o ano e ensino da turma
 * @returns {undefined}
 */
DBViewFormularioEducacao.LegendasLancamentoAvaliacao.prototype.getTermosResultadoFinal = function () {

  var oSelf = this;

  var oParametros                  = new Object();
  oParametros.exec                 = "getTermosResultadoFinal";
  oParametros.iTurma               = this.iTurma;

  var oObjeto        = {};
  oObjeto.method     = 'post';
  oObjeto.parameters = 'json='+Object.toJSON(oParametros);
  oObjeto.onComplete = function(oAjax) {

    var oRetorno = eval("(" + oAjax.responseText + ")");

    if (oRetorno.aTermos.length == 0) {
      return;
    }
    oRetorno.aTermos.each( function (oTermo) {
      oSelf.aOutrasLegendas.push( { 'abreviatura': oTermo.sAbreviatura.urlDecode(), 'descricao' : oTermo.sDescricao.urlDecode()});
    });

  };
  oObjeto.asynchronous = false;

  new Ajax.Request('edu4_turmas.RPC.php', oObjeto);

};

/**
 * Define uma função para ser executada após fechar a view
 * @param {function} fFunction
 * @returns {void}
 */
DBViewFormularioEducacao.LegendasLancamentoAvaliacao.prototype.setCallBackShutDown = function(fFunction) {

  if (typeof(fFunction) != 'function') {
    throw exception('parametro fFunction deve ser uma função!');
  }

  this.fCallBackShutDown = fFunction;
};

DBViewFormularioEducacao.LegendasLancamentoAvaliacao.prototype.criaWindow = function () {

  var oSelf      = this;
  var oContainer = new Element('div', {'id':'ctnLegendaEscola'});
  oContainer.setAttribute('style', 'position:relative; width:100%;');

  this.oWindowLegenda = new windowAux('cntLegenda', this.sTituloWindow, 800, 400);
  this.oWindowLegenda.setContent(oContainer.outerHTML);

  this.oWindowLegenda.setShutDownFunction( function () {

    oSelf.fCallBackShutDown();
    oSelf.oWindowLegenda.destroy();
  });

  if (this.oWindowContainer != '') {
    oSelf.oWindowLegenda.setChildOf(this.oWindowContainer);
  }

  this.oWindowLegenda.show();
};

/**
 * Monta as tabelas de legendas
 * @returns {void}
 */
DBViewFormularioEducacao.LegendasLancamentoAvaliacao.prototype.montaEstrutura = function () {

  var oTableSituacao = new Element('table', {'style': 'border-collapse: collapse;'} );
  this.aLegendaSituacoesMatricula.each( function (oSituacao, i) {

    var oRow = oTableSituacao.insertRow(i);
    oRow.style.backgroundColor = '#CCC';
    if (i % 2 == 0) {
      oRow.style.backgroundColor = '#FFF';
    }

    var oCell0 = oRow.insertCell(0);

    oCell0.style.paddingRight    = "20px";
    oCell0.innerHTML             = oSituacao.abreviatura
    oRow.insertCell(1).innerHTML = oSituacao.descricao;

  });

  this.oFieldsetSituacoesMatricula.appendChild(oTableSituacao);

  var oTableRF  = new Element('table', {'style': 'border-collapse: collapse;'} );
  this.aLegendasResultadoFinal.each (function (oRF, i) {

    var oDiv = new Element('div').update('&nbsp;');
    oDiv.style.width  = "40px";
    oDiv.addClassName(oRF.class);

    var oRowRF    = oTableRF.insertRow(i);
    oRowRF.insertCell(0).appendChild(oDiv);
    oRowRF.insertCell(1).innerHTML = '&nbsp;&nbsp;&nbsp;' + oRF.descricao;
  });

  this.oFieldsetResultadoFinal.appendChild(oTableRF);

  var oTableOutras = new Element('table', {'style': 'border-collapse: collapse;'} );
  this.aOutrasLegendas.each( function (oOutras, i) {

    var oRow = oTableOutras.insertRow(i);
    oRow.style.backgroundColor = '#CCC';
    if (i % 2 == 0) {
      oRow.style.backgroundColor = '#FFF';
    }

    var oCell0 = oRow.insertCell(0);

    oCell0.style.paddingRight    = "20px";
    oCell0.innerHTML             = oOutras.abreviatura
    oRow.insertCell(1).innerHTML = oOutras.descricao;
  });

  this.oCtnOutrasLegendas.appendChild(oTableOutras);
};

DBViewFormularioEducacao.LegendasLancamentoAvaliacao.prototype.setContainerPai = function( oWindow ) {
  this.oWindowContainer = oWindow;
};

DBViewFormularioEducacao.LegendasLancamentoAvaliacao.prototype.show = function()  {

  this.criaWindow();
  this.montaEstrutura();

  $('ctnLegendaEscola').appendChild(this.oCtnElementos);
};