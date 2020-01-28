/**
 * 
 * Componente para pesquisa de setor, quadra e lote
 * 
 * @author Alberto Ferri Neto alberto@dbseller.com.br	
 * @package ITBI
 * @revision $Author: dbalberto $
 * @version $Revision: 1.2 $
 *
 */
DBViewPesquisaSetorQuadraLote = function(sContainer, sInstancia) {
  me = this;
  
	var cboIdSetor;
	var cboSetor;
	var cboQuadra;
	var cboLote;
	var sUrl          = 'func_iptubase.RPC.php'; 
	
	var oFieldset     = document.createElement('fieldset');
	var oLegend       = document.createElement('legend');
  oLegend.innerHTML = '<strong>Setor/Quadra/Lote de Localização</strong>';
	  
  /**
   * Criação dos elementos html*/	  
	
	var oTable                            = document.createElement('table');
		var oLinhaSetor                     = document.createElement('tr');
		  var oCelulaLblSetor               = document.createElement('td');
		      oCelulaLblSetor.innerHTML     = '<strong>Setor: </strong>';
		  var oCelulaSetor                  = document.createElement('td');
		      oCelulaSetor.style.whiteSpace = 'nowrap';
		      oCelulaSetor.id               = 'cellSetor';
		      oCelulaSetor.innerHTML        = "<span id='ctnSetorCodigo'></span>&nbsp;<span id='ctnSetor'></span>";
		                                    
		var oLinhaQuadra                    = document.createElement('tr');
		  var oCelulaLblQuadra              = document.createElement('td');
		      oCelulaLblQuadra.innerHTML    = '<strong>Quadra:</strong>';
		  var oCelulaQuadra                 = document.createElement('td');
		      oCelulaQuadra.id              = 'ctnQuadra';
		                                    
		var oLinhaLote                      = document.createElement('tr');
		  var oCelulaLblLote                = document.createElement('td');
		      oCelulaLblLote.innerHTML      = '<strong>Lote:  </strong>';
		  var oCelulaLote                   = document.createElement('td');
		      oCelulaLote.id                = 'ctnLote';
		  
	oLinhaSetor.appendChild(oCelulaLblSetor);
	oLinhaSetor.appendChild(oCelulaSetor);
	
	oLinhaQuadra.appendChild(oCelulaLblQuadra);
	oLinhaQuadra.appendChild(oCelulaQuadra);
	
	oLinhaLote.appendChild(oCelulaLblLote);
	oLinhaLote.appendChild(oCelulaLote);
	
	oTable.appendChild(oLinhaSetor);
	oTable.appendChild(oLinhaQuadra);
	oTable.appendChild(oLinhaLote);
	
	oFieldset.appendChild(oLegend);
	oFieldset.appendChild(oTable);
	
	/**
	 *Criação dos componentes 
	 */
	
	me.oCboSetorCodigo          = new DBComboBox('setorCodigo' , sInstancia+'.oCboSetorCodigo' , Array(), '68');
	me.oCboSetorCodigo.onChange = sInstancia+".changeSetorCodigo(1);";
	me.oCboSetor                = new DBComboBox('setor' , sInstancia+'.oCboSetor'             , Array(), '200');
	me.oCboSetor.onChange       = sInstancia+".changeSetorCodigo(2)"; 
	me.oCboQuadra               = new DBComboBox('quadra', sInstancia+'.oCboQuadra'            , Array(), '270');
	me.oCboQuadra.onChange      = sInstancia+".js_loadLotes(setorCodigo.value, this.value)";
	me.oCboLote                 = new DBComboBox('lote'  , sInstancia+'.oCboLote'              , Array(), '270');
	this.loadInputs = function (){
	  
	  oThis = this;
	  var oParametro       = new Object();
	  this.aSetorloc       = new Array();
	  this.aSetorlocCodigo = new Array();

	  oParametro.sExec = 'getSetor';

	  var oAjax = new Ajax.Request(sUrl,
	                              { 
	                               method: 'POST',
	                               parameters: 'json='+Object.toJSON(oParametro), 
	                               onComplete: function(oAjax) {
	                                 var oRetorno = eval("("+oAjax.responseText+")");
	                                 
	                                 me.oCboSetorCodigo.clearItens();
	                                 me.oCboSetor.clearItens();
	                                 if (oRetorno.status != "2") {
	                                   me.oCboSetorCodigo.addItem('', 'Todos...');
	                                   me.oCboSetor.addItem('', 'Todos...');	                                   
	                                   for(var i = 0; i < oRetorno.oSetorloc.length; i++) {
	                                     with(oRetorno.oSetorloc[i]) {
	                                       me.oCboSetorCodigo.addItem(j05_codigoproprio, j05_codigoproprio);
	                                       me.oCboSetor.addItem(j05_codigoproprio, j05_descr);
	                                       
	                                     }
	                                   }
	                                 } 	                                 
	                                 oThis.load();
	                               }
	                              });
	  oThis.load = function(){
	    
	    me.oCboSetorCodigo          . show($('ctnSetorCodigo'));
	    me.oCboSetor                . show($('ctnSetor'      ));
	    me.oCboQuadra               . show($('ctnQuadra'     ));
	    me.oCboLote                 . show($('ctnLote'       ));
	  };
	};
	
                                              	
	me.changeSetorCodigo = function(iTipo) {
	  
	  if(iTipo == 1){
	    $('setor').value       = $('setorCodigo').value;
	  } else {
	    $('setorCodigo').value = $('setor').value;
	  }
	  me.js_loadQuadras($('setorCodigo').value);
	  
	};
	
	me.js_loadQuadras = function(iSetor, sSetQuadra) {
	  
	  
	  oThis                = this;
    var oParametro       = new Object();
    var oQuadras         = new Array();
    oParametro.iCodSetor = iSetor;
    oParametro.sExec     = 'getQuadraSetor';
    
    var oAjax = new Ajax.Request(sUrl,
                                { 
                                 method: 'POST',
                                 parameters: 'json='+Object.toJSON(oParametro), 
                                 onComplete: function(oAjax) {
                                   
                                   var oRetorno = eval("("+oAjax.responseText+")");
                                   
                                   me.oCboQuadra.clearItens();
                                   if (oRetorno.status != "2") {
                                     me.oCboQuadra.addItem('', 'Todos...');
                                     
                                     for(var i = 0; i < oRetorno.oQuadras.length; i++) {
                                       with(oRetorno.oQuadras[i]) {
                                         me.oCboQuadra.addItem(j06_quadraloc, j06_quadraloc);                                       
                                       }
                                     }
                                     if(sSetQuadra){
                                       me.oCboQuadra.setValue(sSetQuadra);
                                     } else {
                                       me.oCboLote.clearItens();
                                     }
                                     
                                   }
                                 }
                                });	  
	  
	  
	};
	
	
	me.js_loadLotes = function(iSetor, sQuadra, sSetLote) {
    
    oThis                = this;
    var oParametro       = new Object();
    var oQuadras         = new Array();
    oParametro.iSetor    = iSetor;
    oParametro.sQuadra   = sQuadra;
    oParametro.sExec     = 'getLote';
    
    var oAjax = new Ajax.Request(sUrl,
                                { 
                                 method: 'POST',
                                 parameters: 'json='+Object.toJSON(oParametro), 
                                 onComplete: function(oAjax) {
                                   
                                   var oRetorno = eval("("+oAjax.responseText+")");
                                   
                                   me.oCboLote.clearItens();
                                   if (oRetorno.status != "2") {
                                     me.oCboLote.addItem('', 'Todos...');
                                     for(var i = 0; i < oRetorno.oLotes.length; i++) {
                                       with(oRetorno.oLotes[i]) {
                                         me.oCboLote.addItem(j06_lote, j06_lote); 
                                       }
                                     }
                                     if(sSetLote){
                                       me.oCboLote.setValue(sSetLote);
                                     }
                                     
                                   }
                                 }
                                });   
    
    
  };
  
  this.appendElements = function (){
    document.forms[0].appendChild($('setorCodigo'));
    document.forms[0].appendChild($('setor'));
    document.forms[0].appendChild($('quadra'));
    document.forms[0].appendChild($('lote'));
  };
  me.show = function() {
    
    $(sContainer).appendChild(oFieldset);
    me.loadInputs();
    
  };	
  
  me.appendForm = function() {
    sOnSubmit = document.forms[0].getAttribute("onSubmit") == null ? "" : document.forms[0].getAttribute("onSubmit");
    document.forms[0].setAttribute("onSubmit", sInstancia+".appendElements(); " + sOnSubmit);    
  };
  
  this.setValues = function (sSetor, sQuadra, sLote){
    
    if(sSetor != null){
      me.oCboSetorCodigo.setValue(sSetor);
      me.oCboSetor      .setValue(sSetor);
    }
    if(sSetor != null && sQuadra != null){
      me.js_loadQuadras(sSetor,sQuadra);
    }
    if (sSetor != null && sQuadra != null && sLote != null ) {
      me.js_loadLotes(sSetor, sQuadra, sLote);
    }
  };
  
};