require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');
require_once('scripts/widgets/dbtextField.widget.js');

/**
 * Cria os elementos de mês e ano para exibição da competencia, 
 * é impossivel escolher se quer que realize a busca da competência atual.
 * @param lExibeDataAtual Valor logico, se true carrega a competencia atual caso contrário fica em branco.
 * @constructor
 */
DBViewFormularioFolha.CompetenciaFolha = function( lExibeDataAtual ) {
  
  this.oElementosHTML     = {};
  this.iAno               = '';
  this.iMes               = '';
 
  /**
   * Verifica se é necessário consultar o ano e o mês atual da folha
   */
  if (lExibeDataAtual) {
    this.buscaMesAnoFolha();
  }
};

/**
 * Cria os elementos necessários para o HTML básico.
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
 * Cria o label para competência
 * @returns objeto com o Label
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.criaLabelCompetencia = function () {
 
  var oLabelCompetencia           = document.createElement('label');
  this.oElementosHTML.oTextoLabel = document.createTextNode('Competência: ');
  oLabelCompetencia.appendChild(this.oElementosHTML.oTextoLabel);
  return oLabelCompetencia;
};

/**
 * Busca o Ano e o Mês atual da folha
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
 * Adiciona o Formulario ao elemento informado como parâmetro
 * @param oContainer Container onde o elemento deve ser adicionado
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.renderizaFormulario = function(oContainer) {
  
  oContainer.appendChild(this.getElementosFormulario());
  this.oAno.show(this.oElementosHTML.oSpanAno);
  this.oMes.show(this.oElementosHTML.oSpanMes);
  
  /**
   * Valida ano 
   */
  this.oAno.getElement().onchange = function(event){ 

    if ( this.value.length < 4 || this.value == '0000' ) {

      alert('Ano inválido.');
      this.value = '';
      return false;
    }

    js_ValidaCampos(this, 1, 'Ano Competência', true, false, event);
  }; 

  /**
   * Valida mes
   */
  this.oMes.getElement().onchange = function(event){ 

    if ( this.value == '0' || this.value == '00' || this.value > 12 ) {

      alert('Mês inválido.');
      this.value = '';
      this.focus();
      return false;
    }

    js_ValidaCampos(this, 1, 'Mês Competência', true, false, event);
  };
  
  return;
};

/**
 * Adiciona o Label ao elemento informado como parâmetro
 * @param oContainer container onde o label deve ser adicionado
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.renderizaLabel = function(oContainer) {
  
  oContainer.appendChild(this.getElementoLabel());
  return;
};

/**
 * Desabilita o formulario.
 * @param lOpcao - Valor lógico, se true desabilita os campos e false habilita. 
 * @return void
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.desabilitarFormulario = function() {
  
  this.oAno.setReadOnly(true);
  this.oMes.setReadOnly(true);
  return;
};

/**
 * Habilitar o formulario.
 * @param lOpcao - Valor lógico, se true desabilita os campos e false habilita. 
 * @return void
 */
DBViewFormularioFolha.CompetenciaFolha.prototype.habilitarFormulario = function() {

  this.oAno.setReadOnly(false);
  this.oMes.setReadOnly(false);
  return;
};

/**
 * Adiciona a função enviada como parâmetro no evendo onChange dos inputs do formulário
 * @param fFuncao função a ser executada no onChange de qualquer campo do formulário
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
 * CallBack padrão para o Componente
 */
DBViewFormularioFolha.CompetenciaFolha.callBackPadrao = function() {};
