/**
 * Cria uma interface para seleção das turmas que serão vinculadas ao critério de avaliação
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @package educacao
 * @version $Revision: 1.4 $
 * @param oCriterioAvaliacao dados do criterio de avaliação
 * @constructor
 * @returns {DBViewCriterioAvaliacaoTurma}
 */
DBViewCriterioAvaliacaoTurma = function( oCriterioAvaliacao ) {

  this.iCriterioAvaliacao = oCriterioAvaliacao.iCriterioAvaliacao;
  this.sCriterioAvaliacao = oCriterioAvaliacao.sCriterioAvaliacao;
  this.sCriterioAbreviado = oCriterioAvaliacao.sCriterioAbreviado;
  this.aDisciplinas       = oCriterioAvaliacao.aDisciplinas;

  this.oContainer      = new Element("div");
  this.oFieldContainer = new Element("fieldset", {'id':'DBViewCriterioAvaliacaoTurma', 'style':"height:500px; width:500px;"});

  this.oLegendContainer = new Element("legend").update("Turmas para o critério " + this.sCriterioAbreviado);
  this.oContainerTree   = new Element("div", {'id':'ctnTreeViewTurmas', 'style':'width:100%; padding:0px; text-align:left; '});

  this.oFieldContainer.appendChild(this.oLegendContainer);
  this.oFieldContainer.appendChild(this.oContainerTree);

  this.oBotao = new Element('input', {'id':'vincularTurma',
                                       'type'  : 'button',
                                       'name'  : 'vincularTurma',
                                       'value' : 'Vincular Turma'
                                     });

  this.oContainer.appendChild(this.oFieldContainer);
  this.oContainer.appendChild(this.oBotao);

};

/**
 * Busca todas as turmas que possuem vínculo com o critério de avaliação
 * @return {[type]} [description]
 */
DBViewCriterioAvaliacaoTurma.prototype.buscarTurmasVinculadas = function () {

  var aTurmasVinculadas = [];
  var oParametros                = {};
  oParametros.sExecucao          = 'getTurmasVinculadas';
  oParametros.iCriterioAvaliacao = this.iCriterioAvaliacao;

  var oRequest        = {};
  oRequest.method     = 'post';
  oRequest.parameters = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete = function ( oAjax ) {

    var oRetorno =  eval('('+ oAjax.responseText +')');
    aTurmasVinculadas = oRetorno.aTurmasVinculadas
    return aTurmasVinculadas;
  }

  new Ajax.Request( "edu4_criterioavaliacao.RPC.php", oRequest );

  return aTurmasVinculadas;
}

/**
 * Renderiza a view
 * @param {HTMLElment} oElement
 */
DBViewCriterioAvaliacaoTurma.prototype.show = function(oElement) {

  var oSelf             = this;
  var aTurmasVinculadas = this.buscarTurmasVinculadas();
  oElement.appendChild(this.oContainer);
  this.oTreeViewTurmas = new DBViewArvoreTurma('viewTurmasCriterioAvaliacao');
  this.oTreeViewTurmas.filtrarCriterioAvaliacao(true, this.iCriterioAvaliacao);

  this.aDisciplinas.each( function( iDisciplina ) {
    oSelf.oTreeViewTurmas.adicionarDisciplina(iDisciplina);
  });

  this.oTreeViewTurmas.temAlunosMatriculados(true);
  this.oTreeViewTurmas.lTurmasProgressaoParcial = false;
  this.oTreeViewTurmas.temAlunosMatriculados(false);
  this.oTreeViewTurmas.setCheckBox(true, this.oTreeViewTurmas.fCallBackCheckBox);
  this.oTreeViewTurmas.setCallbackMarcarTurma( function( oTurma, oEtapa ){

    var lMarcaCheckbox = false;

    aTurmasVinculadas.each( function( oTurmaVinculada ) {

      if ( oTurmaVinculada.iTurma == oTurma.iTurma ) {
        lMarcaCheckbox = true;
      }
    });

    return lMarcaCheckbox;
  });
  this.oTreeViewTurmas.show($('ctnTreeViewTurmas'));
  this.oBotao.onclick = function() {
    oSelf.vincularCriterioTurma();
  };

};

/**
 * Busca da treeView os nós selecionados retornand um array com as turmas e etapas
 * @return array
 */
DBViewCriterioAvaliacaoTurma.prototype.buscaTurmasSelecionadas = function () {

  var aTurma        = new Array();
  var aSelecionados = this.oTreeViewTurmas.oTreeViewAvaliacao.getNodesChecked();

  aSelecionados.each(function(oNode) {

    if (!oNode.checkbox.disabled && oNode.checkbox.checked && oNode.lProcessa) {
      aTurma.push( oNode.iTurma );
    }
  });

  return aTurma;
};

/**
 *
 * @returns {boolean}
 */
DBViewCriterioAvaliacaoTurma.prototype.vincularCriterioTurma = function () {

  var aTurmasSelecionadas = this.buscaTurmasSelecionadas();

  var oParametros = {};
  oParametros.sExecucao          = 'vincularTurmas';
  oParametros.iCriterioAvaliacao = this.iCriterioAvaliacao;
  oParametros.aTurmas            = aTurmasSelecionadas;

  var oRequest          = {};
  oRequest.method       = 'post';
  oRequest.parameters   = 'json='+Object.toJSON(oParametros);
  oRequest.asynchronous = false;
  oRequest.onComplete   = function( oAjax ){

    js_removeObj('sMsgA');
    var oRetorno = eval('('+ oAjax.responseText +')');

    alert( oRetorno.sMensagem.urlDecode() );

    if ( oRetorno.iStatus == 1 ) {
      location.href = 'edu1_criterioavaliacao001.php?db_opcao=2&lRedireciona=true&iCodigoCriterio='+oRetorno.iCriterioAvaliacao;
    }
  }

  js_divCarregando( _M("educacao.escola.DBViewCriterioAvaliacaoTurma.aguarde_vinculando_turmas"), "sMsgA");

  new Ajax.Request( "edu4_criterioavaliacao.RPC.php", oRequest );

};
