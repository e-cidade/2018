dbViewNotificaFornecedor = function (iNumCgm, iOrigem, iCodigoNotificaBloqueioFornecedor) {

  var me       = this;
  this.urlRPC  = 'com4_notificafornecedor.RPC.php';
  
  /**
   * Numcgm do fornecedor
   */
  this.iNumCgm = null;
  if (iNumCgm != null) {
    this.iNumCgm = new Number(iNumCgm);
  }
  
  /**
   * Origem do bloqueio
   */
  this.iOrigem = null;
  if (iOrigem != null) {
    this.iOrigem = new Number(iOrigem);
  }
  
  /**
   * Código da notificação bloqueada se já existir
   */
  this.iCodigoNotificaBloqueioFornecedor = null;
  if (iCodigoNotificaBloqueioFornecedor != null) {
    this.iCodigoNotificaBloqueioFornecedor = new Number(iCodigoNotificaBloqueioFornecedor);
  }
  
  /**
   * Código da origem do item
   */
  this.iCodigoOrigem = null;
  
  /**
   * Gerar notificacao de debitos
   */
  this.lGerarNotificacao = true;
  
  /**
   * Function para retorno do callback
   * @param integer oRetorno
   */
  var callBack = function (oRetorno) {
    return true;
  }
  
  /**
   * Variavel de function callback
   */
  var getCallBack = callBack; 
  
 /** 
  * Seta o código da origem do item
  * @param integer iCodigo
  * return void
  */
  this.setCodigoOrigem = function (iCodigo) {
    me.iCodigoOrigem = iCodigo;
  }
  
 /** 
  * Retorna o código da origem do item
  * return me.iCodigoOrigem
  */
  this.getCodigoOrigem = function () {
    return me.iCodigoOrigem;
  }
  
 /** 
  * Seta gerar notificacao dde debitos
  * @param boolean lGerar
  * return void
  */
  this.setGerarNotificacaoDebito = function (lGerar) {
    me.lGerarNotificacao = lGerar;
  }
  
 /** 
  * Retorno gerar notificacao dde debitos
  * return me.lGerarNotificacao
  */
  this.getGerarNotificacaoDebito = function () {
    return me.lGerarNotificacao;
  }
  
 /**
  * Monta windowAuxiliar
  */
  this.windowNotificacaoDebitos = new windowAux('wndNotificacaoDebitos', 'Notificação de Débitos', 520, 245);
  
  var sContent  = '<div>';
  sContent     += ' <fieldset>';
  sContent     += '  <legend>';
  sContent     += '    <b>Forma de Notificação</b>';
  sContent     += '  </legend>';
  sContent     += ' <table border="0" id="AlteraEmail" style="display: none;">';
  sContent     += '   <tr style="display: ">';
  sContent     += '     <td style="width:130px"><b>E-mail Destinatário:</b>&nbsp;</td>';
  sContent     += '     <td>';
  sContent     += '       <input type="text" title="E-mail Destinatário" name="z01_email" id="z01_email" ';
  sContent     += '              value="" size="30">';
  sContent     += '     </td>';
  sContent     += '     <td><input type="button" id="btnSalvarEmail" value="Salvar E-mail"></td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += ' <table border="0">';
  sContent     += '   <tr id="Email" style="display: none;">';
  sContent     += '     <td style="width:10px">';
  sContent     += '       <input type="checkbox" id="cbxEmail" value="">';
  sContent     += '     </td>';
  sContent     += '     <td style="width:10px"><b>E-mail</b></td>';
  sContent     += '   </tr>';
  sContent     += '   <tr id="Carta" style="display: none;">';
  sContent     += '     <td style="width:10px">';
  sContent     += '       <input type="checkbox" id="cbxCarta" value="">';
  sContent     += '     </td>';
  sContent     += '     <td style="width:10px"><b>Carta</b></td>';
  sContent     += '   </tr>';  
  sContent     += ' </table>';
  sContent     += ' </fieldset>';
  sContent     += ' <table border="0" align="center" cellpadding="4">';
  sContent     += '   <tr align="center">';
  sContent     += '     <td>';
  sContent     += '       <input type="button" id="btnNotificarFornecedor" value="Notificar Forncecedor">';
  sContent     += '     </td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += '</div>';
  
  this.windowNotificacaoDebitos.setContent(sContent);
  this.windowNotificacaoDebitos.setShutDownFunction(function () {
  
    me.windowNotificacaoDebitos.destroy();
    
    var oRetorno = eval('({"iParamFornecDeb":"3"})');
    return getCallBack(oRetorno);
  });
  
 /**
  * Mensagem de ajuda notificação de débitos
  */
  me.oMessageBoard   = new DBMessageBoard('msgBoardNotificacaoDebitos',
                                          'Notificar Fornecedor '+me.iNumCgm,
                                          'Escolha a forma de notificar o fornecedor',
                                          $('windowwndNotificacaoDebitos_content')
                                          );  
                                          
 /**
  * Mostra windowAux
  */
  this.show = function () {
    
    me.oMessageBoard.show();
    me.windowNotificacaoDebitos.show();
  }
  
 /**
  * Notificar fornecedor
  * @param boolean lValidarCampos
  * return void
  */
  this.notificar = function (lValidarCampos) {
    
    var iNumCgm                           = me.iNumCgm;
    var iOrigem                           = me.iOrigem;
    var iCodigoNotificaBloqueioFornecedor = me.iCodigoNotificaBloqueioFornecedor;
    var iCodigoOrigem                     = me.getCodigoOrigem();
    var lGerarNotificacaoDebito           = me.getGerarNotificacaoDebito();
    
    /**
     * Verifica se foi informado o numcgm do fornecedor
     */
    if (iNumCgm == '') {
    
      alert('Número CGM do fornecedor não informado! Verifique.');
      return false;
    }
    
    /**
     * Verifica validação da forma de notificação
     */
    if (lValidarCampos) {
    
	    if (!$('cbxEmail').checked && !$('cbxCarta').checked) {
	    
	      alert('Forma de notificação não informada! Verifique.');
	      return false;
	    }
    }
    
    js_divCarregando('Aguarde, processo de notificação do fornecedor...', "msgBoxProcessoNotificacao");
    
    if ($('btnNotificarFornecedor')) {
      $('btnNotificarFornecedor').disabled = true;
    }
    
    var oParam                               = new Object();
    oParam.sExecucao                         = 'processaNotificacao';
    oParam.iNumCgm                           = iNumCgm;
    oParam.iCodigoNotificaBloqueioFornecedor = iCodigoNotificaBloqueioFornecedor;
    oParam.iOrigem                           = iOrigem;
    oParam.iCodigoOrigem                     = iCodigoOrigem;
    oParam.lFormaNotifEmail                  = $('cbxEmail').checked;
    oParam.lFormaNotifCarta                  = $('cbxCarta').checked;
    oParam.lGerarNotificacaoDebito           = lGerarNotificacaoDebito;
    
    var oAjax           = new Ajax.Request (me.urlRPC,
                                            {
                                               method: 'post',  
                                               parameters:'json='+Object.toJSON(oParam),
                                               onComplete: function (oAjax) {
                                               
                                                 js_removeObj("msgBoxProcessoNotificacao");
                                               
																						     if ($('btnNotificarFornecedor')) {
																						       $('btnNotificarFornecedor').disabled = false;
																						     }
                                               
                                                /*
                                                 * Trata o retorno da function notificar()
                                                 */
                                                 var oRetorno = eval("("+oAjax.responseText+")");
                                                 if (oRetorno.iStatus == 1) {
                                                 
                                                   me.windowNotificacaoDebitos.destroy();                                                   
                                                   return getCallBack(oRetorno);
                                                 } else {
                                                 
                                                   alert(oRetorno.sMessage.urlDecode());
                                                   return false;
                                                 }
                                               }  
                                            });
  }
  
 /**
  * Verifica e-mai do fornecedor
  * return void
  */
  this.verificaEmail = function () {
  
    var iNumCgm = me.iNumCgm;

    js_divCarregando('Aguarde, verificando e-mail do fornecedor...', "msgBoxVerificaEmailFornecedor");
    
    if ($('btnNotificarFornecedor')) {
      $('btnNotificarFornecedor').disabled = true;
    }
  
    var oParam          = new Object();
    oParam.sExecucao    = 'verificaEmailFornecedor';
    oParam.iNumCgm      = iNumCgm;
    
    var oAjax           = new Ajax.Request (me.urlRPC,
                                            {
                                               method: 'post',  
                                               parameters:'json='+Object.toJSON(oParam),
                                               onComplete: function (oAjax) {
                                               
                                                 js_removeObj("msgBoxVerificaEmailFornecedor");
                                               
                                                 if ($('btnNotificarFornecedor')) {
                                                   $('btnNotificarFornecedor').disabled = false;
                                                 }
                                               
                                                /*
                                                 * Trata o retorno da function verificaEmail()
                                                 */
                                                 var oRetorno = eval("("+oAjax.responseText+")");
                                                 if (oRetorno.iStatus == 1) {
                                                 
                                                   if (oRetorno.lPermissaoMenu == true) {
                                                     
                                                     $('AlteraEmail').show();                                           
                                                     if (oRetorno.sEmailFornecedor != '') {
                                                       
                                                       $('z01_email').value = oRetorno.sEmailFornecedor;
                                                       if ($('cbxEmail')) {
                                                         $('cbxEmail').disabled = false;
                                                       }
                                                     } else {
                                                       
                                                       if ($('cbxEmail')) {
                                                       
                                                         $('cbxEmail').checked  = false;
                                                         $('cbxEmail').disabled = true;
                                                       }
                                                     }
                                                   } else {

                                                     $('AlteraEmail').hide();
                                                     if ($('cbxEmail')) {
                                                       
                                                         $('cbxEmail').checked  = false;
                                                         $('cbxEmail').disabled = true;
                                                     }
                                                   }
                                                 } else {
                                                 
                                                   alert(oRetorno.sMessage.urlDecode());
                                                   return false;
                                                 }
                                               }  
                                            });
  }
  
 /**
  * Seta forma de notificação configurada para os campos checkbox email e carta
  * @param array aFormaNotificacao
  * @param boolean lMostrarJanela
  * return void
  */
  this.setFormaNotificacao = function (aFormaNotificacao, lMostrarJanela) {
      
    /**
     * Percore aFormaNotificacao para exibir e marcar as formas de notificação
     */
    for (var i = 0; i < aFormaNotificacao.length; i++) {
      
      $(aFormaNotificacao[i]).style.display = '';
      $('cbx'+aFormaNotificacao[i]).checked = true;
      
      if ($(aFormaNotificacao[i]).id == 'Email') {
        
		    /**
		     * Verifica e-mail e permissão de menu do fornecedor
		     */
		    me.verificaEmail();
      }
    }
  
    /**
     * Verificação para montar a window conforme paramentros de notificação
     */
    if (!lMostrarJanela) {
      me.notificar(false);
    }
  }
  
  /**
   * Seta getCallBack() para function callback
   * @param string sFunction
   */
  this.setCallBack = function(sFunction) {
    getCallBack = sFunction;
  }
  
 /**
  * Adiciona evento ao botão btnNotificarFornecedor
  */
  $('btnNotificarFornecedor').observe('click', function () {
    me.notificar(true);
  });
  
 /**
  * Adiciona evento ao botão btnSalvarEmail
  */
  $('btnSalvarEmail').observe('click', function () {
  
    var sEmailFornecedor = $('z01_email').value;
    var iNumCgm          = me.iNumCgm;
    
    var expressaoEmail   = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}/;
    if (expressaoEmail.test(sEmailFornecedor) == false) {
        
      alert("Informe um e-mail válido!");
      return false;
    }
    
    js_divCarregando('Aguarde, salvando e-mail do fornecedor...', "msgBoxSalvarEmailFornecedor");
    
    if ($('btnNotificarFornecedor')) {
      $('btnNotificarFornecedor').disabled = true;
    }
  
    var oParam              = new Object();
    oParam.sExecucao        = 'salvarEmailFornecedor';
    oParam.iNumCgm          = iNumCgm;
    oParam.sEmailFornecedor = sEmailFornecedor;
    
    var oAjax               = new Ajax.Request (me.urlRPC,
                                                {
                                                   method: 'post',  
                                                   parameters:'json='+Object.toJSON(oParam),
                                                   onComplete: function (oAjax) {
                                               
                                                     js_removeObj("msgBoxSalvarEmailFornecedor");
                                               
                                                     if ($('btnNotificarFornecedor')) {
                                                       $('btnNotificarFornecedor').disabled = false;
                                                     }
                                               
                                                     /*
                                                      * Trata o retorno evento do btnSalvarEmail
                                                      */
                                                     var oRetorno = eval("("+oAjax.responseText+")");
                                                     if (oRetorno.iStatus == 1) {
                                                       
                                                       /**
                                                        * Verifica e-mail e permissão de menu do fornecedor
                                                        */
                                                       me.verificaEmail();
                                                     } else {
                                                     
                                                       alert(oRetorno.sMessage.urlDecode());
                                                       return false;
                                                     }
                                                   }  
                                                });
  });
}