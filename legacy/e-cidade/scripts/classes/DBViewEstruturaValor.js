function DBViewEstruturaValor(iEstrutura, sTitulo, sInstance, oNode) {

  var me          = this;
  this.lEnable    = true;    
  this.iEstrutura = iEstrutura;
  var iWidth      = document.width/2;
  this.view       = '';
  me.sRPC         = "con4_estruturas.RPC.php";
  me.instance     = sInstance;
  if (oNode != null) {
    this.view = oNode;
  }
  
  this.sTitulo = 'Cadastro de Estrutura';
  if (sTitulo != null) {
    this.sTitulo = sTitulo;
  }
  
  this.iDisableForm = false; // variavel opcional para desabilitar o form
  this.windowEstrutura = new windowAux('wndEstrutura'+me.iEstrutura, sTitulo, iWidth);
  var sContent  = '<form id="frmEstrutural'+me.iEstrutura+'">';
  sContent     += '<div style="height:80%"><center>';
  sContent     += ' <table style="" border="0">';
  sContent     += ' <tr><td>';
  sContent     += ' <fieldset>';
  sContent     += '  <legend>';
  sContent     += '    <b>'+me.sTitulo+'</b>';
  sContent     += '  </legend>';
  sContent     += '  <table id="tblDados">';
  sContent     += '    <tr>';
  sContent     += '      <td>';
  sContent     += '        <b>Mascara:</b>';
  sContent     += '      </td>';
  sContent     += '      <td id="ctnMascara">';
  sContent     += '      </td>';
  sContent     += '    </tr>';
  sContent     += '    <tr>';
  sContent     += '      <td>';
  sContent     += '        <b>Estrutural:</b>';
  sContent     += '      </td>';
  sContent     += '      <td id="ctnEstrutural">';
  sContent     += '      </td>';
  sContent     += '    </tr>';
  sContent     += '    <tr>';
  sContent     += '      <td>';
  sContent     += '        <b>Descrição:</b>';
  sContent     += '      </td>';
  sContent     += '      <td id="ctnDescricao">';
  sContent     += '      </td>';
  sContent     += '    </tr>';
  sContent     += '    <tr>';
  sContent     += '      <td>';
  sContent     += '        <b>Tipo:</b>';
  sContent     += '      </td>';
  sContent     += '      <td id="ctnTipoEstrutural">';
  sContent     += '      </td>';
  sContent     += '    </tr>'
  sContent     += '  </table>';
  sContent     += ' </fieldset>';  
  sContent     += ' </tr></td></table>';
  sContent     += ' <input type="button" id="btnSalvar"    value="Salvar">';
  sContent     += ' <input type="button" id="btnCancelar"  value="Cancelar">';
  sContent     += ' <input type="button" id="btnPesquisar" value="Pesquisar">';
  sContent     += ' </center>';
  sContent     += '</div>';
  sContent     += '<di id="xyz">';
  sContent     += '</div>';
  sContent     += '</form>';
  if (this.view == "") {
    this.windowEstrutura.setContent(sContent);
  } else {
  
    oNode.style.display = 'none';
    oNode.innerHTML     = sContent;
  }
  
  if (this.view == "") {
    this.oMessageBoard   = new DBMessageBoard('msgBoardEstrutura'+me.iEstrutura,
                                            me.sTitulo,
                                            '',
                                            $('windowwndEstrutura'+me.iEstrutura+'_content')
                                            );   
    this.oMessageBoard.show();
  }                                          
  this.windowEstrutura.setShutDownFunction(function (){
    me.windowEstrutura.destroy();
  });
  
  
  me.txtMascara = new DBTextField('txtMascara', me.instance+".txtMascara");
  me.txtMascara.setReadOnly(true);
  me.txtMascara.show($('ctnMascara'));
  
  me.txtEstrutural = new DBTextField('txtEstrutural', me.instance+".txtEstrutural");
  me.txtEstrutural.show($('ctnEstrutural'));
  
  me.txtDescricao = new DBTextField('txtDescricao', me.instance+".txtDescricao");
  me.txtDescricao.addStyle('width', '100%');
  me.txtDescricao.show($('ctnDescricao'));
  
  var aTiposEstrutural    = new Array(); 
  aTiposEstrutural[1] = 'Sintética'; 
  aTiposEstrutural[2] = 'Analítica'; 
  me.cboTipoEstrutural    = new DBComboBox('cboTipoEstrutural', 
                                           me.instance+".cboTipoEstrutural",
                                           aTiposEstrutural,
                                           '100%');
  me.cboTipoEstrutural.show($('ctnTipoEstrutural'));
                                             
  me.show = function() {
  
    if (this.view == "") {
      this.windowEstrutura.show();
    } else {
      oNode.style.display=  '';
    }
    
    me.getDadosEstrutura();
  }
  
  me.setAjuda = function (sAjuda) {
    if (me.view == "") {
      me.oMessageBoard.setHelp(sAjuda);
    }
  }
  
  me.getDadosEstrutura = function() {
    
    var oParam     = new Object();
    oParam.exec    = 'getDadosEstrutura'; 
    oParam.iCodigo = me.iEstrutura; 
    var oAjax      = new Ajax.Request(me.sRPC,
                                  {method: 'post',
                                   parameters: 'json='+Object.toJSON(oParam), 
                                   onComplete: function(oAjax) {
                                   
                                     var oRetorno = eval("("+oAjax.responseText+")");
                                     me.txtMascara.setValue(oRetorno.sMascara.urlDecode());
                                     me.txtEstrutural.setValue(oRetorno.sMascara.urlDecode());
                                     me.txtEstrutural.setMaxLength(me.txtMascara.getValue().length);
                                     new MaskedInput("#txtEstrutural",
                                                     oRetorno.sMascara.urlDecode().replace(/0/g,"*") ,
                                                     {placeholder:"0"});
                                  }
                                }) 
    
  }
  
}