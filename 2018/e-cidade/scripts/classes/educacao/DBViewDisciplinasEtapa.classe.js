/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

const MSG_DISCIPLINAS_ETAPA = "educacao.escola.DBViewDisciplinasEtapa.";

/**
 *
 * @param iBase
 * @param iTurma
 * @param lVisaoCadastroBase
 * @constructor
 */
DBViewDisciplinasEtapa = function(iBase, iTurma, lVisaoCadastroBase, lFiltarEscola) {

  /**
   * Código da base
   * @type {int}
   */
  this.iBase = iBase;

  /**
   * Código da Turma
   * @type {*}
   */
  this.iTurma = iTurma;

  /**
   * Verifica se estamos acessando o cadastro pela rotina de :
   *  - Cadastro > Bases Currículares
   *  - Cadastro > Turmas
   * @type {boolean}
   */
  this.lVisaoCadastroBase = lVisaoCadastroBase;

  /**
   * Se cadastro de base foi acessado da Secretaria de Educação ou da Escola, acessando da Secretaria, não devemos
   * filtrar a escola
   * @type {Boolean}
   */
  this.lFiltarEscola = false;
  if ( !!lFiltarEscola ) {
    this.lFiltarEscola = lFiltarEscola == 'true' ? true : false;
  }

  // sempre que informado a turma esta acessando da escola
  if ( this.iTurma != '' ) {
    this.lFiltarEscola = true;
  }

  /**
   * Array com as etapas da base ou turma selecionada
   * De acordo com a visão
   * @type {Array}
   */
  this.aEtapas    = [];

  this.oDBAba = new DBAbas($('ctnAbas'));
  this.sRPC   = 'edu4_vinculodisciplinaetapa.RPC.php'

  this.getAbas();
};

DBViewDisciplinasEtapa.iQuantidadeAbas = 0;
DBViewDisciplinasEtapa.aAbas           = [];
DBViewDisciplinasEtapa.oGridEpatas     = new DBGrid('ctnGridEtapas');
DBViewDisciplinasEtapa.oGridEpatas.nameInstance = 'DBViewDisciplinasEtapa.oGridEpatas';
DBViewDisciplinasEtapa.oGridEpatas.setCheckbox(0);
DBViewDisciplinasEtapa.oGridEpatas.setCellWidth(['100%']);
DBViewDisciplinasEtapa.oGridEpatas.setCellAlign(['left','center']);
DBViewDisciplinasEtapa.oGridEpatas.setHeader(['codigo','Etapa']);
DBViewDisciplinasEtapa.oGridEpatas.setHeight(100);
DBViewDisciplinasEtapa.oGridEpatas.aHeaders[1].lDisplayed = false;

/**
 * Busca as etapas e cria as abas
 */
DBViewDisciplinasEtapa.prototype.getAbas = function() {

  var oParametros   = {
    'iBase'         : this.iBase,
    'iTurma'        : this.iTurma,
    'lFiltarEscola' : this.lFiltarEscola
  };

  oParametros.exec  = "getEtapasTurma";
  if (this.lVisaoCadastroBase) {
    oParametros.exec  = "getEtapasBase";
  }
  oParametros.teste = false;

  oSelf        = this;
  var oRequest = {};
  oRequest.asynchronous = false;
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.onComplete   = function(oAjax) {

    js_removeObj("msgBoxA");
    var oRetorno = eval ( "(" + oAjax.responseText + ")" );
    oRetorno.aEtapas.each( function (oEtapa) {

      oEtapa.sDescricao   = oEtapa.sDescricao.urlDecode();
      oEtapa.sAbreviatura = oEtapa.sAbreviatura.urlDecode();
      oSelf.aEtapas.push(oEtapa);
      oSelf.criarContainerAba(oEtapa);

    });

    $('iEtapaAtual').value = oRetorno.aEtapas[0].iCodigo;
    buscaDisciplinasEtapa(oRetorno.aEtapas[0].iCodigo);

  };

  js_divCarregando( _M(MSG_DISCIPLINAS_ETAPA+"verificando_etapas"), "msgBoxA" );
  new Ajax.Request(this.sRPC, oRequest);

};

/**
 * Cria o container das abas
 * @param oEtapa
 */
DBViewDisciplinasEtapa.prototype.criarContainerAba = function (oEtapa) {

  var oSelf = this;
  DBViewDisciplinasEtapa.iQuantidadeAbas ++;
  var oContainer = $('ctnDisciplinaEtapa');

  /**
   * Cria um elemento qualquer para informar para as abas.
   * foi alterado o comportamento das abas para dar a impressão de estar alterando os formulários
   * @type {HTMLElement}
   */
  var oNovoElemento   = new Element('div');
  oNovoElemento.id    = 'etapa#'+oEtapa.iCodigo;

  oContainer.appendChild(oNovoElemento);

  var oAba = this.oDBAba.adicionarAba(oEtapa.sDescricao, oNovoElemento);

  oAba.getSeletor().setAttribute('etapa', oEtapa.iCodigo);
  oAba.getSeletor().observe('click', function() {
    oSelf.atualizaEtapa(this);
  });

  DBViewDisciplinasEtapa.aAbas.push(oAba);

};

/**
 * Função executada ao clicar na aba
 * @param oElement
 */
DBViewDisciplinasEtapa.prototype.atualizaEtapa = function(oElement) {

  document.form1.reset();
  $('iEtapaAtual').value = oElement.getAttribute('etapa');
  buscaDisciplinasEtapa(oElement.getAttribute('etapa'));

};

/**
 * Permite replicar uma disciplina de uma etapa para as demais
 * @param oDadosDisciplina
 */
DBViewDisciplinasEtapa.prototype.replicarDisciplinaEtapas = function (oDadosDisciplina) {

  var oSelf = this;
  this.oWindow = new windowAux("wndReplicaDisciplina",
                               "Replica a Disciplina para as etapas selecionadas",
                               700, 400
                              );
  var sConteudo  = " <div class=''> ";
      sConteudo += "   <fieldset> ";
      sConteudo += "     <legend> Selecione as etapas</legend>";
      sConteudo += "     <div id='cntGridEtapas'> </div>";
      sConteudo += "   </fieldset> ";
      sConteudo += "   <center><input type='button' name='reclicar' value='Salvar' id='btnReplicar'  /></center>";
      sConteudo += " </div> ";


  this.oWindow.setShutDownFunction( function() {
    oSelf.oWindow.destroy();
  });

  var sMsg         = 'Disciplina: ' +oDadosDisciplina.sDisciplina;
  var sHelpMsgBox  = 'Replica a Disciplina para as etapas selecionadas.<br>';
  if (oDadosDisciplina.lGlobalizada) {
    sHelpMsgBox += ' Disciplinas globalizadas não serão replicadas para etapas que já possuem uma disciplina Globalizada.'
  }

  this.oWindow.setContent(sConteudo);
  this.oMessageBoard = new DBMessageBoard('msgBoardReplica', sMsg, sHelpMsgBox, this.oWindow.getContentContainer() );
  this.oWindow.show();

  DBViewDisciplinasEtapa.oGridEpatas.show($('cntGridEtapas'));

  DBViewDisciplinasEtapa.oGridEpatas.clearAll(true);
  this.aEtapas.each( function (oEtapa) {

    if ( oDadosDisciplina.iEtapa == oEtapa.iCodigo) {
      return;
    }
    var aLinha = [];
    aLinha.push(oEtapa.iCodigo);
    aLinha.push(oEtapa.sDescricao.urlDecode());
    DBViewDisciplinasEtapa.oGridEpatas.addRow(aLinha);
  });

  DBViewDisciplinasEtapa.oGridEpatas.renderRows();

  $('btnReplicar').onclick  = function () {
    oSelf.salvarReplicar(oDadosDisciplina);
  };

};

/**
 * Salva a disciplina nas etapas selecionadas
 * @param oDadosDisciplina
 */
DBViewDisciplinasEtapa.prototype.salvarReplicar = function(oDadosDisciplina) {

  var oSelf = this;
  if (DBViewDisciplinasEtapa.oGridEpatas.getSelection().length == 0 ) {

    oSelf.oWindow.destroy();
    return;
  }

  oDadosDisciplina.exec = 'replicarDisciplinasTurma';
  if (this.lVisaoCadastroBase) {
    oDadosDisciplina.exec = 'replicarDisciplinasBase';
  }

  var aEtapasSelecionadas = [];

  DBViewDisciplinasEtapa.oGridEpatas.getSelection().each( function (aSelecao) {
    aEtapasSelecionadas.push(aSelecao[0]);
  });

  oDadosDisciplina.aEtapas = aEtapasSelecionadas;

  var oRequest          = {};
  oRequest.asynchronous = false;
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oDadosDisciplina);
  oRequest.onComplete   = function(oAjax) {

    js_removeObj("msgBoxC");
    var oRetorno = eval ( "(" + oAjax.responseText + ")" );

    alert(oRetorno.sMessage.urlDecode());
    if ( parseInt(oRetorno.iStatus) == 2) {
      return;
    }
    oSelf.oWindow.destroy();
  }

  js_divCarregando( _M(MSG_DISCIPLINAS_ETAPA + 'replicar_disciplinas'), "msgBoxC" );
  new Ajax.Request(sRpc, oRequest);

};