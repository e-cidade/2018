require_once('scripts/classes/DBViewFormularioFolha/DBViewFormularioFolha.classe.js');

DBViewFormularioFolha.ValidarFolhaPagamento = function(){

  this.TIPO_FOLHA_SALARIO      = 1;
  this.TIPO_FOLHA_RESCISAO     = 2;
  this.TIPO_FOLHA_COMPLEMENTAR = 3;
  this.TIPO_FOLHA_ADIANTAMENTO = 4;
  this.TIPO_FOLHA_13o_SALARIO  = 5;
};


DBViewFormularioFolha.ValidarFolhaPagamento.prototype.verificarFolhaPagamentoAberta = function (iTipoFolha, iAnoFolha, iMesFolha) {

  var lAberta = false;

  var oParam = {
      sExecucao : 'VerificarFolhaPagamentoAberto',
      iTipoFolha: iTipoFolha,
      iAnoFolha : iAnoFolha,
      iMesFolha : iMesFolha
  };
  
  var oDadosRequisicao = {
    method      : 'post', 
    parameters  : 'json='+Object.toJSON(oParam),
    asynchronous: false,
    onComplete  : function( oRespostaAjax ) {
      var oRetorno = eval("(" + oRespostaAjax.responseText + ")");
       
      if (oRetorno.iStatus == 2) {
        throw oRetorno.sMensagem;
      }

      lAberta = oRetorno.lFolhaAberta; 
    }
  };
  
  new Ajax.Request('pes4_formularioFolha.RPC.php', oDadosRequisicao);
  return lAberta;
};

/**
 * Verifica se a folha de pagamento esta aberta ou fechada conforme os parâmetros.
 * 
 * @param {Integer} iTipoFolha
 * @param {Integer} iAnoFolha
 * @param {Integer} iMesFolha
 * @param {Boolean} lStatus
 * @returns {Boolean}
 */
DBViewFormularioFolha.ValidarFolhaPagamento.prototype.verificarFolhaPagamento = function (iTipoFolha, iAnoFolha, iMesFolha, lStatus) {
  
  var lAberta = true;
  
  var oParam  = {
      sExecucao   : 'VerificarFolhaPagamento',
      iTipoFolha  : iTipoFolha,
      iAnoFolha   : iAnoFolha,
      iMesFolha   : iMesFolha,
      lStatus     : lStatus
  };
  
  var oDadosRequisicao = {
    method      : 'post', 
    parameters  : 'json='+Object.toJSON(oParam),
    asynchronous: false,
    onComplete  : function( oRespostaAjax ) {
      
      var oRetorno = eval("(" + oRespostaAjax.responseText + ")");
       
      if (oRetorno.iStatus == 2) {
        throw oRetorno.sMensagem;
      }

      lAberta = oRetorno.lFolhaAberta; 
    }
  };
  
  new Ajax.Request('pes4_formularioFolha.RPC.php', oDadosRequisicao);
  return lAberta;
};
