;

var DBViewPreferenciaUsuario = function() {
  this.oDados = DBViewPreferenciaUsuario.getArquivoPreferencias();
};

DBViewPreferenciaUsuario.prototype.salvar = function() {
  
  var lRetorno   = false;
  var oParametro = {
    method       : 'post',
    parameters   : 'json=' + Object.toJSON({"sExecucao": "salvar", "oPreferencias": this.oDados}),
    asynchronous : false,
    onComplete   : function(oAjax) {

      var oRetorno = JSON.parse(oAjax.responseText);
      if (oRetorno.iStatus == 2) {
        throw "Erro ao processar a requisicao: " + oRetorno.sMessage;
      }

      lRetorno = true;
    }
  };

  new Ajax.Request("con4_preferenciausuario.RPC.php", oParametro);
  return lRetorno;
};

DBViewPreferenciaUsuario.getArquivoPreferencias = function() {

  var oPost = {
    sExecucao: "getPreferencias"
  };
  
  var oPreferencias;
  var oParametro = {
    method       : 'post',
    parameters   : 'json=' + Object.toJSON(oPost),
    asynchronous : false,
    onComplete   : function(oAjax) {
      var oRetorno = JSON.parse(oAjax.responseText);
      if (oRetorno.iStatus == 2) {
        throw "Erro ao processar a requisicao: " + oRetorno.sMessage;
      }
      oPreferencias = oRetorno.oPreferencias;
    }
  };
  
  new Ajax.Request("con4_preferenciausuario.RPC.php", oParametro);
  return oPreferencias;
};