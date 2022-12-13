/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
require_once('scripts/widgets/dbtextField.widget.js');
require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');

/**
 * Cria os elementos de m�s e ano para exibi��o da competencia, � possivel escolher se quer que realize a busca da compet�ncia atual.
 *
 * @param lExibeDataAtual Valor logico, se true carrega a competencia atual caso contr�rio fica em branco.
 * @constructor
 */
DBViewFormularioFolha.CompetenciaFolha = function( lExibeDataAtual ) {
  
  this.oElementosHTML     = {};
  this.iAno               = '';
  this.iMes               = '';
 
  /**
   * Verifica se � necess�rio consultar o ano e o m�s atual da folha
   */
  if (lExibeDataAtual) {
    this.buscaMesAnoFolha();
  }
};

/**
 * Cria os elementos necess�rios para o HTML b�sico.
 * @returns oContainer, elemento principal do HTML.
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.criaFormularioCompetencia = function () {
  
  var oFormularioCompetencia                     = document.createElement('div');
  this.oElementosHTML.oSpanAno                   = document.createElement('span');
  this.oElementosHTML.oSpanMes                   = document.createElement('span');
  this.oElementosHTML.oTextoSeparador            = document.createTextNode('/');
  this.oElementosHTML.oSpanAno.style.marginRight = '5px';
  this.oElementosHTML.oSpanMes.style.marginLeft  = '5px';
  
  /**
   * Cria elementos para os inputs de mes e ano.
   */
  this.oAno = new DBTextField('ano', 'ano', this.iAno, 4);
  this.oMes = new DBTextField('mes', 'mes', this.iMes, 2);
  this.oMes.getElement().placeholder='M�s';
  this.oAno.getElement().placeholder='Ano';
  this.oAno.setMaxLength(4);
  this.oMes.setMaxLength(2);
  
  /**
   * Adiciona os elementos ao oContainer
   */
  oFormularioCompetencia.appendChild(this.oElementosHTML.oSpanAno);
  oFormularioCompetencia.appendChild(this.oElementosHTML.oTextoSeparador);
  oFormularioCompetencia.appendChild(this.oElementosHTML.oSpanMes);
  
  //js_ValidaCampos
  
  return oFormularioCompetencia;
};

/**
 * Cria o label para compet�ncia
 * @returns objeto com o Label
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.criaLabelCompetencia = function () {
 
  var oLabelCompetencia           = document.createElement('label');
  this.oElementosHTML.oTextoLabel = document.createTextNode('Compet�ncia: ');
  oLabelCompetencia.appendChild(this.oElementosHTML.oTextoLabel);
  return oLabelCompetencia;
};

/**
 * Busca o Ano e o M�s atual da folha
 * @returns void
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.buscaMesAnoFolha = function() {
  this
  var oParam = {
      sExecucao: 'BuscaAnoMesFolha'   
  }
  var oSelf = this;
  
  var oDadosRequisicao = {
    method      : 'post', 
    parameters  : 'json='+Object.toJSON(oParam),
    asynchronous: false,
    onComplete  : function( oRespostaAjax ) {
  
      var oRetorno = eval("(" + oRespostaAjax.responseText + ")");
      
      if (oRetorno.iStatus == 2) {
        throw oRetorno.sMensagem;
      }
      
      oSelf.iAno = oRetorno.iAno;
      oSelf.iMes = oRetorno.iMes;
    }
  }
  
  var oBusca = new Ajax.Request('pes4_formularioFolha.RPC.php',oDadosRequisicao);
  return;
};

/**
 * Retorna um objeto com o HTML contendo o Input das competencias
 * @return [HTMLElement DIV] - Com os elementos do componente
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.getElementosFormulario = function() {
  return this.criaFormularioCompetencia();
};

/**
 * Retorna um obejeto contendo o Label da competencia
 * @return label
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.getElementoLabel = function() {
  return this.criaLabelCompetencia();
};

/**
 * Adiciona o Formulario ao elemento informado como par�metro
 * @param oContainer Container onde o elemento deve ser adicionado
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.renderizaFormulario = function(oContainer) {

   
  if ( arguments.length == 1 ) {

    oContainer.appendChild(this.getElementosFormulario());
    this.oAno.show(this.oElementosHTML.oSpanAno);
    this.oMes.show(this.oElementosHTML.oSpanMes);

  } else {
   
    this.oMes = new DBTextField(arguments[1], 'mes', this.iMes, 2);
    this.oMes.show();
    
    this.oAno = new DBTextField(arguments[0], 'ano', this.iAno, 4);
    this.oAno.show();

  };
  /**
   * Valida ano 
   */
  this.oAno.getElement().onchange = function(event) { 
    if (js_ValidaCampos(this, 1, 'Ano da compet�ncia', true, false, event)) {

      /**
       * Valida se o ano � v�lido
       */
      if (this.value.length < 4) {
        alert('Informe um ano v�lido.');
        this.value = '';
      };
    };
  }; 

  /**
   * Valida mes
   */
  this.oMes.getElement().onchange = function(event) { 

    if (js_ValidaCampos(this, 1, 'M�s da compet�ncia', true, false, event)) {

      /**
       * Valida se o m�s � v�lido
       */
      if (this.value < 0 || this.value > 12) {
        alert('Informe um m�s v�lido.');
        this.value = '';
      };
    };
  };
  
  return;
};

/**
 * Adiciona o Label ao elemento informado como par�metro
 * @param oContainer container onde o label deve ser adicionado
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.renderizaLabel = function(oContainer) {
  
  oContainer.appendChild(this.getElementoLabel());
  return;
};

/**
 * Desabilita o formulario.
 * @param lOpcao - Valor l�gico, se true desabilita os campos e false habilita. 
 * @return void
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.desabilitarFormulario = function() {
  
  this.oAno.setReadOnly(true);
  this.oMes.setReadOnly(true);
  return;
};

/**
 * Habilitar o formulario.
 * @param lOpcao - Valor l�gico, se true desabilita os campos e false habilita. 
 * @return void
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.habilitarFormulario = function() {

  this.oAno.setReadOnly(false);
  this.oMes.setReadOnly(false);
  return;
};

/**
 * Adiciona a fun��o enviada como par�metro no evendo onChange dos inputs do formul�rio
 * @param fFuncao fun��o a ser executada no onChange de qualquer campo do formul�rio
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.setCallBack = function( fFuncao ){
  
  if ( fFuncao == null) {
    fFuncao =  DBViewFormularioFolha.CompetenciaFolha.callBackPadrao;   
  }
  this.oAno.getElement().observe( "change", fFuncao );
  this.oMes.getElement().observe( "change", fFuncao );
  return;
};

/**
 * Valida se a competencia � igual a informada
 *
 * @param {Competencia} oCompetencia
 * @returns {Boolean}
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.equalsTo = function (oCompetencia) {

  this.buscaMesAnoFolha();
  
  var iAnoAtual = parseInt(this.iAno);
  var iMesAtual = parseInt(this.iMes);

  if (oCompetencia != 'undefined' || oCompetencia != undefined) {
    if (iAnoAtual == oCompetencia.iAno && iMesAtual == oCompetencia.iMes) {
      return true;
    }
  }

  return false;
}

/**
 * V�lida se a compet�ncia informada � maior que a compet�ncia atual.
 * 
 * @param {Integer} iAno
 * @param {Integer} iMes
 * @returns {Boolean}
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.isCompetenciaValida = function (iAno, iMes) {
  
  this.buscaMesAnoFolha();
  
  var iAnoAtual = parseInt(this.iAno);
  var iMesAtual = parseInt(this.iMes);
  
  if (iAnoAtual < iAno) {
    return false;
  }
  
  if (iMes < 1 || iMes > 12) {
    return false;
  }

  if (iAnoAtual == iAno && iMesAtual < iMes) {
    return false;
  }
  
  return true;
};


/**
 * CallBack padr�o para o Componente
 */
DBViewFormularioFolha.CompetenciaFolha.callBackPadrao = function() {};
