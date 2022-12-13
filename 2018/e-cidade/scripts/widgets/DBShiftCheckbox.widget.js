/**
 * Função para Habilitar check com a tecla shift + click
 *
 * @constructor
 *
 * @author       Rafael Serpa Nery   - rafael.nery@dbseller.com.br
 * @param        aElementos   array  - array de elementos a selecionar
 * @param        instance     string - ultiliado internamente
 * @version      $Revision: 1.2 $
 * @revision     $Author: dbrafael.nery $
 * @returns      {Object}
 */
var DBShiftCheckBox = function(aElementos, instance){
 
 var me           = this;
 var lChecked     = null;
 me.sInstance     = instance;
 var iIndiceCheck = new Number();
 
 var callBackFunction = function(){};
 /**
  * Função para verificar existencia de um valor em um array
  * @param valor any   valor procurado
  * @param vetor array vetor a ser percorrido
  * @returns {void}
  */
  var in_array = function(valor,vetor){
    
    for (var i in vetor) {
      
      if (valor == vetor[i]) {
        return true;
      }
    }
    return false;
  };
  
  /**
  * Função de retorno quando registros forem setados pressionando a tecla shift
  * @param {function} 
  */
  this.setCallBackFunction = function(fFuncao){
    if (typeof(fFuncao) == "function") {
      callBackFunction = fFuncao;
    }
  };
  var iCountChecks = 0;
  aElementos.each(function(oElemento, iIndice){
    /**
    * Verifica funções onclick já setadas
    */
    var sOnClick = oElemento.getAttribute('onClick');
    if(sOnClick == null){
      sOnClick = "";
    }
    /**
    * Cria atributos necessários para funcionamento da função.
    */
    oElemento.setAttribute(instance+"_shiftCheckBox", iIndice);
    oElemento.setAttribute("onClick", instance+".seleciona(this,event);"+sOnClick);
    if(oElemento.checked){
      iCountChecks++;
      lChecked     = oElemento.checked;
      iIndiceCheck = oElemento.getAttribute(instance+"_shiftCheckBox");
    }
    if(iCountChecks == 0){
      lChecked     = true;
      iIndiceCheck = 0;
    }
  });
  
  this.seleciona = function(oElemento, oEvento){
    
    var aIntervaloSelecionar = new Array();
    /**
    * Caso o Click do mouse for com a tecla shift selecionada
    */
    if(oEvento.shiftKey){
      var iIndiceAntes = new Number(iIndiceCheck);
      var iIndiceAtual = new Number(oElemento.getAttribute(instance+"_shiftCheckBox"));
      
      if(iIndiceAtual > iIndiceAntes){
        
        for(var iI = iIndiceAntes; iI <= iIndiceAtual; iI++){
          aIntervaloSelecionar.push(iI);
        }
      }
      if(iIndiceAtual < iIndiceAntes){
        
        for(var iI = iIndiceAntes; iI >= iIndiceAtual; iI--){
          aIntervaloSelecionar.push(iI);
        }
      }
      /**
      * Percorre os elementos da classe comparando as opções
      */
      aElementos.each(function(oElemento, iIndice){
        
        if(in_array(oElemento.getAttribute(instance+"_shiftCheckBox"),aIntervaloSelecionar) && oElemento.disabled == false ){
          oElemento.checked = lChecked;
        }
      });
      lChecked     = oElemento.checked;
      iIndiceCheck = oElemento.getAttribute(instance+"_shiftCheckBox");
      callBackFunction();
      
    } else {
      
      lChecked     = oElemento.checked;
      iIndiceCheck = oElemento.getAttribute(instance+"_shiftCheckBox");
      
    }
  };
};
