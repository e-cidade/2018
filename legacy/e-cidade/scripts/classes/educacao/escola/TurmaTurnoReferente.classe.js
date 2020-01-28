require_once("scripts/classes/educacao/DBViewFormularioEducacao.classe.js");

/**
 * Monta as linhas com as vagas disponíveis/ocupadas referentes ao ensino e turno da turma
 * -> Para turmas onde o curso é infantil e o turno é integral, cria uma visão alitica separando
 *    as vagas dos turnos referente ao qual o turno integral é composto;
 * -> Para turma onde curso não é infantíl, cria uma visão sintética;
 *
 * @dependency Utiliza DBViewFormularioEducacao.classe.js
 * @autor Andrio Costa <andrio.costa@dbseller.com.br>
 * @autor Andre Mello <andre.mello@dbseller.com.br>
 * @version $Revision: 1.8 $
 * @package Educacao
 * @subpackage Escola
 * @example
 *
 *       var oTurmaTurno = new DBViewFormularioEducacao.TurmaTurnoReferente($('linhaAnterior'), 151);
 *       oTurmaTurno.setInputSize(4);
 *       oTurmaTurno.show();
 *
 * @returns {void}
 */

DBViewFormularioEducacao.TurmaTurnoReferente = function (oLinhaReferencia, iTurma) {

  /**
   * Linha na qual o turno referente deve ser inserido abaixo
   * @type {ElementHTML} oLinhaReferencia
   */
  this.oLinhaReferencia = oLinhaReferencia;

  /**
   * Código da turma
   * @type {integer} Turma
   */
  this.iTurma = iTurma;

  this.sRpc = '';

  /**
   * Linha dos checkbox
   * @type {ElementHTML} tr
   */
  this.oLinhaCheck   = new Element('tr');

  /**
   * Coluna referente ao label do checkbox
   * @type {ElementHTML} td
   */
  this.oColunaCheck1 = new Element("td");
  this.oColunaCheck1.appendChild(new Element("label", {'class' : 'bold'}).update("Selecione o turno:"));

  /**
   * Coluna referente as celulas do checkbox
   * @type {ElementHTML} td
   */
  this.oColunaCheck2 = new Element("td");

  /**
   * Objetos com as vagas da turma separado pelos turnos referente
   * @type {Object}
   */
  this.oVagasTurma = {};

  /**
   * Variável que verifica se ensino é infantil
   * @type {Boolean}
   */
  this.lEnsinoInfantil = false;

  /**
   * Variável que verifica se turno é integral
   * @type {Boolean}
   */
  this.lTurnoIntegral  = false;

  /**
   * Tamanho dos input
   * @type {Number}
   */
  this.iInputSize = 5;


  /**
   * Array contendo as linhas que já foram criadas
   * @type {Array}
   */
  this.aLinhasCriadas = [];


  /**
   * Array com uma legenda dos turnos de referência
   * @type {Object}
   */
  this.aLegendaTurno = {1:'manhã', 2:'tarde', 3:'noite'};

  /**
   * Verifica os dados do ensino e turno da turna informada e atualiza os valores
   * das variaveis lEnsinoInfantil e lTurnoIntegral
   * @return {void}
   */
  var buscaDadosEnsino = function (oSelf) {

    var oParametro        = {'exec':'getDadosEnsinoTurno', 'iTurma':oSelf.iTurma};
    var oRequest          = {};
    oRequest.method       = 'post';
    oRequest.asynchronous = false;
    oRequest.parameters   = 'json='+Object.toJSON(oParametro);
    oRequest.onComplete   = function(oAjax) {

      var oRetorno = eval('(' + oAjax.responseText + ')' );
      oSelf.lEnsinoInfantil = oRetorno.lEnsinoInfantil;
      oSelf.lTurnoIntegral  = oRetorno.lTurnoIntegral;

    };
    new Ajax.Request('edu4_turmas.RPC.php', oRequest);
  }

  buscaDadosEnsino(this);

  /**
   * Busca as vagas da turma separado pelo código do turno referente
   * @return {void}
   */
  var buscaVagasTurma = function (oSelf) {

    var oParametros    = {};
    oParametros.exec   = "buscaVagasPorTurno";
    oParametros.iTurma = oSelf.iTurma;

    var oRequest          = {};
    oRequest.method       = 'post';
    oRequest.asynchronous = false;
    oRequest.parameters   = 'json='+Object.toJSON(oParametros);
    oRequest.onComplete   = function (oAjax) {

      js_removeObj('msgBoxA');
      var oRetorno      = eval('(' + oAjax.responseText + ')' );
      oSelf.oVagasTurma = oRetorno.aVagasTurma;
    }

    js_divCarregando( "Aguarde, buscando as vagas da turma...", "msgBoxA" );
    new Ajax.Request("edu4_turmas.RPC.php", oRequest);
  }

  buscaVagasTurma(this);

}

/**
 * Cria as linhas contendo as vagas dos turnos referentes e os checkbox para selecionar
 * qual turno deve ser considerado
 * @return {void}
 */
DBViewFormularioEducacao.TurmaTurnoReferente.prototype.criaLinhasTurnoTurma = function() {

  var oSelf    = this;
  var sIdLinha = "";

  var aTurnoReferenteTurma = [];

  for (var iTurnoReferente in this.oVagasTurma) {

    aTurnoReferenteTurma.push(iTurnoReferente);

    var sIdVagas        = 'vagas'+iTurnoReferente;
    var sIdMatriculados = 'matriculados'+iTurnoReferente;
    var sIdDisponivel   = 'disponiveis'+iTurnoReferente;
    var sIdCheckbox     = 'check_turno' + iTurnoReferente;

    var oLinha   = new Element('tr');
    var oColuna1 = new Element('td');
    var oColuna2 = new Element('td');

    var oLabelVagas        = new Element('label', {'class':'bold'}).update('Vagas ' + this.aLegendaTurno[iTurnoReferente] + ': ');
    var oLabelMatriculados = new Element('label', {'class':'bold'}).update(' Alunos matriculados: ');
    var oLabelDisponivel   = new Element('label', {'class':'bold'}).update(' Vagas disponíveis: ');
    var oLabelCheckbox     = new Element('label', {'class':'bold', 'for' : sIdCheckbox}).update(this.aLegendaTurno[iTurnoReferente].toUpperCase());

    var oInputVagas        = new Element('input', {'type' : 'text', 'name' : sIdVagas ,  'id' : sIdVagas, 'size' : this.iInputSize,
                                                   'class' : 'readonly', 'readonly' : 'readonly', 'value' : this.oVagasTurma[iTurnoReferente].iVagas});
    var oInputMatriculados = new Element('input', {'type' : 'text', 'name' : sIdMatriculados , 'id' : sIdMatriculados, 'size' : this.iInputSize,
                                                   'class' : 'readonly', 'readonly' : 'readonly',
                                                   'value' : this.oVagasTurma[iTurnoReferente].iVagasOcupadas});
    var oInputDisponivel   = new Element('input', {'type' : 'text', 'name' : sIdDisponivel , 'id' : sIdDisponivel, 'size' : this.iInputSize,
                                                   'class' : 'readonly', 'readonly' : 'readonly',
                                                   'value' : this.oVagasTurma[iTurnoReferente].iVagasDisponiveis});
    var oInputCheckbox     = new Element('input', {'type':'checkbox', 'name':sIdCheckbox, 'id':sIdCheckbox, 'value':iTurnoReferente, 'class':'TurmaTurnoReferente'});

    oInputCheckbox.setAttribute("checked", "checked");

    oColuna1.appendChild(oLabelVagas);
    oColuna2.appendChild(oInputVagas);
    oColuna2.appendChild(oLabelMatriculados);
    oColuna2.appendChild(oInputMatriculados);
    oColuna2.appendChild(oLabelDisponivel);
    oColuna2.appendChild(oInputDisponivel);
    oLinha.appendChild(oColuna1);
    oLinha.appendChild(oColuna2);

    this.oColunaCheck2.appendChild(oInputCheckbox);
    this.oColunaCheck2.appendChild(oLabelCheckbox);

    /**
     * Pegamos o próximo nó irmão (ou seja a próxima tr)
     */
    var oNodeIrmao = this.oLinhaReferencia.nextSibling;

    /**
     * sIdLinha é uma variavel para garantir que estamos sempre colocando abaixo dos nós criados.
     * Assim sempre colocamos uma linha abaixo da outra.
     */
    if (sIdLinha != "") {
      oNodeIrmao = $(sIdLinha);
    }

    sIdLinha = "linhaTurnoReferente" + iTurnoReferente;
    oLinha.setAttribute("id", sIdLinha);

    this.aLinhasCriadas.push(oLinha);
    /**
     * Adiciona o nó especificado, antes de um elemento de referência, como filho do nó atual.
     * Onde oNodeIrmao.nextSibling é o elemento de referência
     */
    this.oLinhaReferencia.parentNode.insertBefore(oLinha, oNodeIrmao.nextSibling );

  }

  var oIntupTurnosReferencia = new Element('input', {'type':'hidden', 'id':'referenciaTurma', 'name':'referenciaTurma', 'value': aTurnoReferenteTurma.toString()});
  this.oColunaCheck2.appendChild(oIntupTurnosReferencia);

  this.oLinhaCheck.appendChild(this.oColunaCheck1);
  this.oLinhaCheck.appendChild(this.oColunaCheck2);
  this.oLinhaReferencia.parentNode.insertBefore(this.oLinhaCheck, $(sIdLinha).nextSibling);

};


/**
 * Mostra as linhas contendo as vagas referente a turma
 * @return {void}
 */
DBViewFormularioEducacao.TurmaTurnoReferente.prototype.show = function() {

  this.criaLinhasTurnoTurma();
  if ( !this.lEnsinoInfantil || !this.lTurnoIntegral) {

    this.escondeLinhasTurnoTurma();
    this.criaLinhaTurnoTurmaAgrupado();
  }

  this.getVagasDisponiveis();
};

/**
 * Destroi as instâncias das linhas criadas
 * @return {void}
 */
DBViewFormularioEducacao.TurmaTurnoReferente.prototype.limpaLinhasCriadas = function () {

  if ( this.oLinhaCheck.parentNode == null) {
    return;
  }
  this.oLinhaCheck.parentNode.removeChild(this.oLinhaCheck);
  this.aLinhasCriadas.each( function (oElement) {

    var oPai = oElement.parentNode;
    oPai.removeChild(oElement);
  });
}

/**
 * Oculta as linhas criadas como uma visão analítica das vagas da turma
 * @return {void}
 */
DBViewFormularioEducacao.TurmaTurnoReferente.prototype.escondeLinhasTurnoTurma = function () {

  this.oLinhaCheck.style.display = 'none';
  this.aLinhasCriadas.each( function (oLinha) {
    oLinha.style.display = 'none';
  });
};


/**
 * Cria uma visão sintética das vagas da turma considerando o turno como um todo
 * @return {void}
 */
DBViewFormularioEducacao.TurmaTurnoReferente.prototype.criaLinhaTurnoTurmaAgrupado = function () {

  var oSelf    = this;
  var sIdLinha = "";

  for (var iTurnoReferente in this.oVagasTurma) {

    var sIdVagas        = 'vagasTurma';
    var sIdMatriculados = 'matriculados';
    var sIdDisponivel   = 'disponiveis';

    var oLinha   = new Element('tr');
    var oColuna1 = new Element('td');
    var oColuna2 = new Element('td');

    var oLabelVagas        = new Element('label', {'class':'bold'}).update('Vagas turma: ');
    var oLabelMatriculados = new Element('label', {'class':'bold'}).update(' Alunos matriculados: ');
    var oLabelDisponivel   = new Element('label', {'class':'bold'}).update(' Vagas disponíveis: ');

    var oInputVagas        = new Element('input', {'type' : 'text', 'name' : sIdVagas ,  'id' : sIdVagas, 'size' : this.iInputSize,
                                                   'class' : 'readonly', 'readonly' : 'readonly', 'value' : this.oVagasTurma[iTurnoReferente].iVagas});
    var oInputMatriculados = new Element('input', {'type' : 'text', 'name' : sIdMatriculados , 'id' : sIdMatriculados, 'size' : this.iInputSize,
                                                   'class' : 'readonly', 'readonly' : 'readonly',
                                                   'value' : this.oVagasTurma[iTurnoReferente].iVagasOcupadas});
    var oInputDisponivel   = new Element('input', {'type' : 'text', 'name' : sIdDisponivel , 'id' : sIdDisponivel, 'size' : this.iInputSize,
                                                   'class' : 'readonly', 'readonly' : 'readonly',
                                                   'value' : this.oVagasTurma[iTurnoReferente].iVagasDisponiveis});

    oColuna1.appendChild(oLabelVagas);
    oColuna2.appendChild(oInputVagas);
    oColuna2.appendChild(oLabelMatriculados);
    oColuna2.appendChild(oInputMatriculados);
    oColuna2.appendChild(oLabelDisponivel);
    oColuna2.appendChild(oInputDisponivel);
    oLinha.appendChild(oColuna1);
    oLinha.appendChild(oColuna2);

    sIdLinha = "linhaTurnoTurma";
    oLinha.setAttribute("id", sIdLinha);

    this.aLinhasCriadas.push(oLinha);
    this.oLinhaReferencia.parentNode.insertBefore(oLinha, this.oLinhaReferencia.nextSibling );

    return;
  }
};


/**
 * Atribui um novo tamanho aos inputs
 * @param {Number} iTamanho
 */
DBViewFormularioEducacao.TurmaTurnoReferente.prototype.setInputSize = function (iTamanho) {

  if (iTamanho > 0) {
    this.iInputSize = iTamanho;
  }
};


DBViewFormularioEducacao.TurmaTurnoReferente.prototype.temVagasDisponiveis = function() {
  return this.getVagasDisponiveis().length > 0;
}

DBViewFormularioEducacao.TurmaTurnoReferente.prototype.getVagasDisponiveis = function(iReferencia) {

  var oSelf            = this;
  var aVagas           = [];
  var aTurnoReferencia = new Array();
  var lTurmaSemVagas   = false;

  $$("input.TurmaTurnoReferente:checked").each( function(oElement) {

    if (oElement == 'function') {
      return;
    }

    var iTurnoReferente = oElement.value;

    // Se informado um turno de referência, so verificamos as vagas do turno informado
    if ( iReferencia && iReferencia != iTurnoReferente ) {
      return;
    }

    $("check_turno"+iTurnoReferente).setAttribute('disabled', 'disabled');
    $("check_turno"+iTurnoReferente).checked = false;

    // Se há vagas, libera o checkbox do turno para selecão e desbloqueio o mesmo
    if ($("disponiveis"+iTurnoReferente) && $F("disponiveis"+iTurnoReferente) > 0 ) {

      $("check_turno"+iTurnoReferente).removeAttribute('disabled');
      $("check_turno"+iTurnoReferente).checked = true;

      aVagas.push( {iTurnoReferente:iTurnoReferente, iVagasDisponiveis:$F('disponiveis'+iTurnoReferente)});
    }
  });

  return aVagas;
};

DBViewFormularioEducacao.TurmaTurnoReferente.prototype.temTurnoSelecionado = function() {
  return $$("input.TurmaTurnoReferente:checked" ).length > 0;
};