<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once('libs/db_conn.php');
require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('libs/db_app.utils.php');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js"); 
  db_app::load("strings.js");
  db_app::load("dbmessageBoard.widget.js");
  db_app::load("datagrid.widget.js");
  db_app::load("estilos.css, grid.style.css");
  db_app::load("windowAux.widget.js");
?>
<style>
  body{
    -moz-user-select: none;
  }
  .desabilitados {
    background-color: rgb(222, 184, 135);
    color: #777777;
  }
  
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload=" getDados();">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="" id="base">
  <tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <fieldset>
        <legend><b>Recibos a emitir: </b></legend>
        <div id="ctnRecibo"></div>
        <div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='cancelado_motivo'>
        </div>        
      </fieldset>
      <fieldset>
      <legend><b> Carnês a Emitir: </b></legend>
      <div id="ctnCarne"></div>
      </fieldset>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>

 
  sUrl = window.location.search.urlDecode().replace("/\/i","");
  var dtDataVencimento = parent.$F('k00_dtoper').split('/').reverse().join('-');
  var lForcarVencimento = parent.$('forcarvencimento').checked;
  oRetorno = eval("("+sUrl.substr(6)+")");
  /**
   * 
  var oMessageBoard = new DBMessageBoard('msgboard1','Impressão de Recibos/ Carnês','Clique no botão emitir no carnê ou recibo selecionado. ',document.body);
      oMessageBoard.show();
  $('base').style.height  = document.body.clientHeight - $('msgboard1').clientHeight - 3;
  $('base').style.top     =  $('msgboard1').clientHeight;
  */
  function js_montaGridRecibo() {

    oGridRecibo              = new DBGrid('oGridRecibo');
    oGridRecibo.nameInstance = 'oGridRecibo';
    oGridRecibo.sName        = 'oGridRecibo';
    oGridRecibo.setCellAlign  (new Array("left","left", "left","center","center","right","center", "left"));
    aHeaders                 = new Array('Numpre','Numnov',"Parcela","Dt. Operação","Dt. Vencimento","Valor Total", "&nbsp; ", "&nbsp; ");
    oGridRecibo.aWidths      = new Array(7,3,3,5,5,7,3,3);
    oGridRecibo.setHeader(aHeaders);
    oGridRecibo.show($('ctnRecibo'));
  }
  function js_montaGridCarne() {

    oGridCarne              = new DBGrid('oGridCarne');
    oGridCarne.nameInstance = 'oGridCarne';
    oGridCarne.sName        = 'oGridCarne';
    oGridCarne.setCellAlign  (new Array("center", "center","center","right"));
    aHeaders                = new Array('Numpre',"Período de Parcelas","Data de Operação","Valor Total", "&nbsp;");
    oGridCarne.aWidths      = new Array(3,8,8,8,10);
    oGridCarne.setHeader(aHeaders);
    oGridCarne.show($('ctnCarne'));
  }

  function getDados(){
    js_montaGridRecibo();
    js_montaGridCarne();
    getDadosRecibos();
    getDadosCarnes();
    
  }
/**
 * Função para emissão de recibo
 */
  function js_emite(sSessaoBusca,sTipo,iNumpre,dtDataUsu){
  
      if(sTipo == "carne" && dtDataUsu == null){
        var sUrl = 'cai3_gerfinanc033.php'+parent.debitos.location.search +'&sessao='+sSessaoBusca
      } else {
        var sUrl = 'cai3_gerfinanc003.php'+parent.debitos.location.search +
                   '&sessao='             + sSessaoBusca + 
                   '&forcarvencimento='   + 'true' +
                   '&reemite_recibo=true' +
                   '&k03_numpre='         + iNumpre +
                   '&k03_numnov='         + iNumpre + 
                   '&db_datausu='         + dtDataUsu;
  
      }
      window.open(sUrl,
                  'Emissao'      + sTipo,
                  'width='       + (screen.availWidth-5)   +','+
                  'height='      + (screen.availHeight-40) +','+
                  'scrollbars=1,'+
                  'location=0 '
                 );

  }

  /**
   * Função para o cancelamento dos recibos emitidos
   */
  function js_cancelaRecibo(numnov) {

    larguraJanela = (screen.availWidth/2 >= 600) ? screen.availWidth/2 : 600;
    CentroJanela  = (screen.availWidth/2 >= 600) ? screen.availWidth/4 :  (screen.availWidth - 600)/2;

    var oParam         = new Object();
        oParam.exec    = 'ValidaCancelaReciboPaga';
        oParam.numnov  = numnov;
    var oAjax  = new Ajax.Request("cai3_emitecarne.RPC.php",
                                   {method: 'post',
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    onComplete: function(oAjax) { 
                                    var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
                                       if (oRetorno.status == "2") {
                                         alert(oRetorno.message);
                                       } else {
                                    	   js_mostraJanelaCancelaRecibo(numnov);  
                                       }        
                                    }
                                   } );
  }

  function js_mostraJanelaCancelaRecibo(numnov) {
	    /**
	     * Cria Janela de cancelamento de recibo
	     */
	    var windowCancReciboPaga     = new windowAux('janelaCancelaReciboPaga','Cancelar Recibo',larguraJanela,350);                      
	        windowCancReciboPaga.setContent("<div id='messageCancelaReciboPaga'></div><div id='conteudoCancelaReciboPaga'></div>");
	        windowCancReciboPaga.setShutDownFunction(function(){  
	          document.body.removeChild(document.getElementById('janelaCancelaReciboPaga'));
	        });             
	        windowCancReciboPaga.show(25,CentroJanela); 
	    
	    var oMessageBoard = new DBMessageBoard('msgboard1','Cancelar emissão do recibo '+numnov,'Informe o motivo do cancelamento da emissão do recibo '+numnov+' e confirme a operação. ',$('messageCancelaReciboPaga'));
	        oMessageBoard.show();
	    var oIframeCancelaReciboPaga             = document.createElement("iframe");
	        oIframeCancelaReciboPaga.src         = "cai4_cancelarecibopaga001.php?numnov="+numnov;
	        oIframeCancelaReciboPaga.frameBorder = 0;
	        oIframeCancelaReciboPaga.id          = 'db_iframe_cancrecibopaga';
	        oIframeCancelaReciboPaga.name        = 'db_iframe_cancrecibopaga';
	        oIframeCancelaReciboPaga.scrolling   = 'auto';
	        oIframeCancelaReciboPaga.width       = (larguraJanela-50)+'px';
	        var Altura = $('janelaCancelaReciboPaga').clientHeight - $('msgboard1').clientHeight - 35;
	        oIframeCancelaReciboPaga.height      = Altura+'px';

	    $('conteudoCancelaReciboPaga').appendChild(oIframeCancelaReciboPaga);
  }    

  function js_fechaJanelaCancelaRecibo() {
	  document.body.removeChild(document.getElementById('janelaCancelaReciboPaga'));
	  getDadosRecibos();
  }  

  function getDadosRecibos(){
    
    var me                      = this;
    this.sRPC                   = 'cai3_emitecarne.RPC.php';
    var oParam                  = new Object();
    oParam.exec                 = 'getDadosRecibo';
    oParam.aNumpres             = oRetorno.Recibos;
    oParam.aSessoesRecibo       = oRetorno.aSessoesRecibo;
    oParam.dtVencimento         = dtDataVencimento;
    js_divCarregando('Carregando dados do Recibo...', 'msgBox');
    var oAjax  = new Ajax.Request(me.sRPC,
                               {method: 'post',
                               parameters: 'json='+Object.toJSON(oParam), 
                               onComplete: function(oAjax) {
                                 js_removeObj('msgBox');
                                 var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
                                 if (oRetorno.status== "2") {
                                   alert(oRetorno.message);
                                 } else {
                                   oGridRecibo.clearAll(true);
                                   if (oRetorno.aRecibos.length > 0) {
                                         
                                     for (i = 0; i < oRetorno.aRecibos.length; i++) {
                                     
                                       with (oRetorno.aRecibos[i]) {
                                       
                                         var aLinha = new Array();
                                         aLinha[0]     = k00_numpre; 
                                         aLinha[1]     = k00_numnov; 
                                         aLinha[2]     = k00_numpar; 
                                         aLinha[3]     = js_formatar(k00_dtoper,'d'); 
                                         aLinha[4]     = js_formatar(k00_dtpaga,'d');
                                         aLinha[5]     = js_formatar(k00_valor ,'f',2);
                                         sDesabilitado = "";
                                         if ( (k00_dtpaga < data_hoje) || cancelado != "" || diferenca_arrecad != 0) {
                                        	 sDesabilitado =  "disabled"
                                         } 

                                         if (cancelado == "") {
                                           aLinha[6] =  "<center><input type='button' onClick=\"js_emite('" + sessao + "','recibo',"+k00_numnov+",'"+k00_dtpaga+"')\" value = \"Emite Recibo\" "+sDesabilitado+"></center>";
                                           aLinha[7] =  "<center><input type='button' onClick=\"js_cancelaRecibo("+k00_numnov+")\" value = \"Cancelar Recibo\" "+sDesabilitado+"></center>";
                                         }else {
                                        	 aLinha[6] = "<b>Recibo Cancelado</b>";
                                           aLinha[7] = cancelado_motivo.substr(0,20)+"...";  
                                         }  
                                           
                                         if(sDesabilitado != ""){
                                           oGridRecibo.addRow(aLinha,null,true);             
                                           oGridRecibo.aRows[i].setClassName("desabilitados");
                               	           oGridRecibo.aRows[i].aCells[7].sEvents  = "onmouseover='js_showText(\"<b>Motivo do Cancelamento do Recibo "+k00_numnov+":</b><br>"+cancelado_motivo+"\",true);'";
                            	             oGridRecibo.aRows[i].aCells[7].sEvents += "onmouseOut='js_showText(null,false)'";  
                            	             oGridRecibo.aRows[i].aCells[7].sStyle   = "cursor: pointer";
                                         } else {
                                           oGridRecibo.addRow(aLinha,null,false);
                                         }
                                         
                                       }
                                     }
                                     oGridRecibo.renderRows();
                                   }
                                 }
                               }
                             }) ;

  }

function getDadosCarnes(){
    
    var me                      = this;
    this.sRPC                   = 'cai3_emitecarne.RPC.php';
    var oParam                  = new Object();
    oParam.exec                 = 'getDadosCarne';
    oParam.aSessoesCarne        = oRetorno.aSessoesCarne;
    oParam.dtVencimento         = dtDataVencimento;
    
    js_divCarregando('Carregando dados do Carne...', 'msgBox');
    var oAjax  = new Ajax.Request(me.sRPC,
                               {method: 'post',
                               parameters: 'json='+Object.toJSON(oParam), 
                               onComplete: function(oAjax) {
                                 js_removeObj('msgBox');
                                 var oRetorno = eval("("+oAjax.responseText+")");
                                 if (oRetorno.status== "2") {
                                   alert(oRetorno.message.urlDecode());
                                 } else {
                                   oGridCarne.clearAll(true);
                                   if (oRetorno.aCarnes.length > 0) {
                                         
                                     for (i = 0; i < oRetorno.aCarnes.length; i++) {
                                     
                                       with (oRetorno.aCarnes[i]) {
                                       
                                         var aLinha = new Array();
                                         aLinha[0]  = k00_numpre; 
                                         aLinha[1]  = k00_numpar; 
                                         aLinha[2]  = js_formatar(k00_dtoper,'d'); 
                                         aLinha[3]  = js_formatar(k00_valor ,'f'); 
                                         aLinha[4]  = "<center><input type='button' onClick=\"js_emite('" + oRetorno.sSessao + "','carne')\" value = 'Emite Carnê'></center>"; 
                                         oGridCarne.addRow(aLinha);             
                                       }
                                     }
                                     oGridCarne.renderRows();
                                   }
                                 }
                               }
                             }) ;

  }

function js_showText(sTexto, lShow) {

	  if (lShow) {
	  
	    el =  $('ctnRecibo');
	    var x = 0;
	    var y = el.offsetHeight;
	    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

	     x += el.offsetLeft;
	     y += el.offsetTop;
	     el = el.offsetParent;

	   }
	   x += el.offsetLeft;
	   y += el.offsetTop;
	   $('cancelado_motivo').innerHTML     = sTexto;
	   $('cancelado_motivo').style.display = '';
	   $('cancelado_motivo').style.top     = y+10;
	   $('cancelado_motivo').style.left    = x;
	   $('cancelado_motivo').style.zIndex  = 100000;
	   
	  } else {
	   $('cancelado_motivo').style.display = 'none';
	  }
	}
</script>