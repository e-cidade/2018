DBViewOrganograma = function(iOrganograma, sInstance, oNode) {

  var me                = this;
  this.sRPC             = 'con1_organograma.RPC.php';
  me.instance           = sInstance;
  me.iCodigoEstrutura   = 0;  
  me.iCodigoOrganograma = iOrganograma; 
  me.onSaveComplete     = function (oRetorno) {
  
  } 
  
  me.onBeforeSave = function() {
    return true;
  }
  /**
   * Pesquisamos os dados da estrutura que está configurado para ser utilizado no organograma
   */
  var oParam  = new Object();
  oParam.exec = 'getCodigoEstrutural';
  oParam.sUrl = 'config/configuracao.xml';
  var oAjax   = new Ajax.Request(this.sRPC,
                               {method: 'post',
                                asynchronous: false,
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: function(oAjax) {
                                  
                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  me.iCodigoEstrutura = oRetorno.iCodigoEstrutura;
                                }
                               }) ;
                               
  this.dbViewEstrutural = new DBViewEstruturaValor(me.iCodigoEstrutura,
                                               "Cadastro de Organograma", me.instance+".dbViewEstrutural", oNode);
  this.dbViewEstrutural.setAjuda("Informe os dados para composição do Organograma");
  /**
   *Adiciona os campos referentes ao departamentos do organograma
   */
  var oTabelaEstrutura              = $('tblDados');
  var oRowDepartamento              = document.createElement("TR");
  var oCelulaLblDepartamento        = document.createElement("TD");
  var sLabelDepartamento            = '<a onclick="'+me.instance+'.pesquisaDepartamento(true);"';
  sLabelDepartamento               += 'style="text-decoration: underline;" class="dbancora" href="#"><b>Departamento:</b></a>'; 
  oCelulaLblDepartamento.innerHTML  = sLabelDepartamento;
  var oCelulaCtnDepartamento        = document.createElement("TD"); 
  oCelulaCtnDepartamento.id         = 'ctnTxtConta';
  oCelulaCtnDepartamento.noWrap     = 'noWrap';
  
  /**
   *Adiciona os campos referentes ao associados ao organograma
   */
   
  var oRowAssociado             = document.createElement("TR");
  var oCelulaLblAssociado       = document.createElement("TD"); 
  oCelulaLblAssociado.innerHTML = '<b>Associado:</b>';
  var oCelulaCtnAssociado       = document.createElement("TD"); 
  oCelulaCtnAssociado.id        = 'ctnAssociado';
  
  
  /**
   * Cria conteiners para o codigo e descrição da Conta
   */
  var oSpanCodigoConta    = document.createElement("span");
  oSpanCodigoConta.id     ='ctntxtCodigoDepartamento';  
  
  var oSpanDescricaoConta = document.createElement("span");
  oSpanDescricaoConta.id  ='ctnTxtNomeDepartamento';
  
  oCelulaCtnDepartamento.appendChild(oSpanCodigoConta);
  oCelulaCtnDepartamento.appendChild(oSpanDescricaoConta);
  

  
  oRowDepartamento.appendChild(oCelulaLblDepartamento); 
  oRowDepartamento.appendChild(oCelulaCtnDepartamento)
  
  oRowAssociado.appendChild(oCelulaLblAssociado);
  oRowAssociado.appendChild(oCelulaCtnAssociado); 
  
  oTabelaEstrutura.appendChild(oRowDepartamento); 
  
  oTabelaEstrutura.appendChild(oRowAssociado); 
  
  /**
   * Criamos os compenentes para os campos especificos
   */
  
  
  me.txtCodigoDepartamento = new DBTextField('txtCodigoDepartamento', me.instance+".txtCodigoDepartamento", '', 10);
  me.txtCodigoDepartamento.addEvent("onChange", ";"+me.instance+".pesquisaDepartamento(false)");
  me.txtCodigoDepartamento.show($('ctntxtCodigoDepartamento'));
  
  me.txtNomeDepartamento = new DBTextField('txtNomeDepartamento', me.instance+".txtNomeDepartamento", '', 35);
  me.txtNomeDepartamento.show($('ctnTxtNomeDepartamento'));
  me.txtNomeDepartamento.setReadOnly(true);
  
  
  aAssociado      = new Array();
  aAssociado['f'] = 'Não'; 
  aAssociado['t'] = 'Sim'; 

  me.cboAssociado    = new DBComboBox('cboAssociado', 
                                           me.instance+".cboTipoEstrutural",
                                           aAssociado,
                                           '92');
  
  me.cboAssociado.show($('ctnAssociado'));
  this.dbViewEstrutural.show();
  
  me.salvar = function () {
    
    var oParam    = new Object();
    oParam.exec   = 'salvarOrganograma';
    oParam.oOrganograma = new Object();
    oParam.oOrganograma.sAssociado       = me.cboAssociado.getValue();
    oParam.oOrganograma.iCodigoEstrutura = me.iCodigoEstrutura;
    oParam.oOrganograma.sDescricao       = encodeURIComponent(tagString(me.dbViewEstrutural.txtDescricao.getValue()));
    oParam.oOrganograma.sEstrutural      = encodeURIComponent(tagString(me.dbViewEstrutural.txtEstrutural.getValue()));
    oParam.oOrganograma.iTipo            = me.dbViewEstrutural.cboTipoEstrutural.getValue();
    oParam.oOrganograma.iDepartamento    = me.txtCodigoDepartamento.getValue();
    oParam.iCodigoOrganograma            = me.iCodigoOrganograma;
    if( !me.onBeforeSave(oParam.oOrganograma)) {
      return false;
    }
    js_divCarregando('Aguarde, salvando informações do Organograma', 'msgBox');
    var oAjax  = new Ajax.Request(me.sRPC,
                                {method: 'post',
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: function(oAjax) {
                                  js_removeObj('msgBox');
                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  if (oRetorno.status== "2") {
                                    alert(oRetorno.message.urlDecode());
                                  } else {

                                    me.onSaveComplete(oRetorno);
                                  }
                                }
                               }) ;
  }
  
  $('btnSalvar').observe("click", me.salvar);  
  $('btnCancelar').style.display='none';  
  me.pesquisaDepartamento = function (mostra) {
   //alert("Chamou a função ");
    if (mostra) {
     js_OpenJanelaIframe('', 
                         'db_iframe_Departamentos',
                         'func_db_depart.php?funcao_js=parent.'+me.instance+'.completaDepartamento|coddepto|descrdepto',
                         'Selecione o departamento',
                          true);
    } else {
    
     if (me.txtCodigoDepartamento.getValue() != '') { 
        js_OpenJanelaIframe('', 
                            'db_iframe_Departamentos', 
                            'func_db_depart.php?pesquisa_chave='+me.txtCodigoDepartamento.getValue()+
                            '&funcao_js=parent.'+me.instance+'.completaDepartamento',
                            'Selecione o departamento',false);
      } else {
        me.txtNomeDepartamento.setValue(''); 
      }
    }
  }
  
  me.completaDepartamento = function() {
    
    if (typeof(arguments[1]) == "boolean") {
      if (arguments[1]) {
      
        me.txtNomeDepartamento.setValue(arguments[0]); 
        me.txtCodigoDepartamento.setValue(''); 
      } else {
        me.txtNomeDepartamento.setValue(arguments[0]);
      }
    } else {
    
      me.txtNomeDepartamento.setValue(arguments[1]); 
      me.txtCodigoDepartamento.setValue(arguments[0]);
      db_iframe_Departamentos.hide();
    }
  }
  
  me.getDados = function (iOrganograma) {

    js_divCarregando('Aguarde, pesquisando dados do Organograma', 'msgBox');
    var oParam              = new Object();
    oParam.exec             = 'getOrganograma';
    oParam.iCodigoEstrutura = iOrganograma;
    var oAjax           = new Ajax.Request(me.sRPC,
                                {   method: 'post',
                                parameters: 'json='+Object.toJSON(oParam), 
                                onComplete: function(oAjax) {
                                  
                                  js_removeObj('msgBox');
                                  var oRetorno = eval("("+oAjax.responseText+")");
                                  if (oRetorno.status == 2) {
                                    alert(oRetorno.message.urlDecode());
                                  } else {

	                                    me.dbViewEstrutural.txtDescricao.setValue(oRetorno.sDescricao.urlDecode());
																		  me.dbViewEstrutural.txtEstrutural.setValue(oRetorno.sEstrutural.urlDecode());
																		  me.dbViewEstrutural.cboTipoEstrutural.setValue(oRetorno.sTipoConta);
																		  me.txtCodigoDepartamento.setValue(oRetorno.iCodigoDepartamento);
																		  me.txtNomeDepartamento.setValue(oRetorno.sDescricaoDepartamento.urlDecode());
                                      me.iCodigoOrganograma = oRetorno.iCodigoOrganograma;
                                      me.cboAssociado.setValue(oRetorno.sAssociado.urlDecode());                                    
                                      
                                      var iFilhos = new Number(oRetorno.sFilhos);
                                      if (iFilhos != 0) {
                                      
                                        me.cboAssociado.setDisable();
                                        me.dbViewEstrutural.cboTipoEstrutural.setDisable();
                                      } else {
                                        
                                        me.cboAssociado.setEnable();
                                        me.dbViewEstrutural.cboTipoEstrutural.setEnable();
                                      }       
                                   }
                                }
                             }) ;
  }
}