DBViewFavorecido = function(iCodigoFavorecido, sInstance) {
   
  this.setDataPost = function(sUrlRPC,sExec, oDados){
    
    var oParam      = new Object();
    var sRpc        = "";
    
    if (sUrlRPC  == "" || sUrlRPC == null) {
      me.sRPC = 'con1_contabancaria.RPC.php';
    } else {
      me.sRPC = sUrlRPC;
    }
    
    if (sExec  == "" || sExec == null) {
      oParam.exec = 'salvar';
    } else {
      oParam.exec = sExec;
    }
    
    if (typeof(oDados) == "object") {
      
      oParam.oDados = oDados;
    } else {
      oParam.oDados = $('form1').serialize(true);;
    } 
    
    oParam.iSequencialAgencia = $F('inputSequencialAgencia');
    oParam.iSequencialBanco   = $F('inputSequencialBanco');
    
    js_divCarregando('Salvando dados do Favorecido...', 'msgBox');
    var oAjax = new Ajax.Request("jur1_favorecido.RPC.php",
                                 {method    : 'post',
                                  parameters: 'json='+Object.toJSON(oParam), 
                                  onComplete: 
                                    function(oAjax) {
        
                                      js_removeObj('msgBox');
                                      var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
                                      alert(oRetorno.message.urlDecode());
                                      js_liberaAbas(oRetorno.registroSalvo,false);
                                      parent.mo_camada('favorecidotaxas');
                                      
                                    }
                                 }
                                ) ;
  }
  
  this.salvar  = function(){
    "";
  }

}