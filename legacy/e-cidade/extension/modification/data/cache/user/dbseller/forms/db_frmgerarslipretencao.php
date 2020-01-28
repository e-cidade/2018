<?
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
?>
<form name="form1" method="post" action="">
<table>
  <tr>
    <td>
      <fieldset>
        <legend><b>Periodo</b></legend>
        <table>
          <tr>
            <td>
               <b>Data Inicial:</b>
            </td>
            <td>
              <?
                db_inputdata("datainicial",null,null,null,true,"text", 1);
              ?>
            </td>
            <td>
              <b>Data Final:</b>
            </td>
            <td>
              <?
                db_inputdata("datafinal",null,null,null,true,"text", 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>  
               <b>Agrupar Por:</b>
            </td>
            <td>
              <?
               $aAgrupar = array(
                                 1 => 'Débito/Crédito',
                                 2 => 'Ordem Pagamento'
                                );
                db_select("agrupar", $aAgrupar, true,1);                
              ?>
            </td>
          </tr>
          <tr>
            <td>  
               <b>Ordem de Pagamento:</b>
            </td>
            <td>
              <?
                db_input("e50_codord",10,null,null,"text", 1, "onchange='js_mudaOrdem();'");
              ?>
            </td>
          </tr>          
          <tr>
            <td colspan="4" style='text-align:center'>
              <input type='button' value='Pesquisar' onclick="js_getMovimentosAgrupados()">
            </td>
          </tr>
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
     <td colspan="2" style='text-align:center'>
        <input type='button' value='Processar' onclick="js_gerarsLip();">
     </td>
  </tr>
</table>
<table >
  <fieldset style='width: 70%'>
    <legend><b>Valores a serem transferidos</b></legend>
    <div id='gridSlip' >
    </div>
  </fieldset>
</table>
</form>   

<script type="text/javascript">

/**
 * Iniciamos a construcao do aplicativo.
 */
function init() {

  iTipoGerarSlip         = 1;
  oGridSlip              = new DBGrid("gridSlip");
  oGridSlip.nameInstance = "oGridSlip";
  oGridSlip.setCheckbox(0);
  oGridSlip.setCellAlign(new Array("right", "Right", "left", "right", "left", "right"));
  var aHeaders = new Array(
                           "Seq",
                           "Cta Credito",  
                           "Descrica",  
                           "Cta Debito",  
                           "Descricao",  
                           "Valor"  
                          );
  oGridSlip.setHeader(aHeaders);
  oGridSlip.show($('gridSlip'));
  
  
}
sUrlRPC = "emp4_gerarslipRPC.php";

function js_mudaOrdem() {
  codigoordem = $F('e50_codord');
  
  if (codigoordem != "") {
    $('agrupar').value  = 2;
  }
}

function js_getMovimentosAgrupados() {

  dtInicial   = $F('datainicial');  
  dtFinal     = $F('datafinal');
  codigoordem = $F('e50_codord');
  
  if (dtInicial == "") {
  
    alert('Data Inicial nao Informada');
    $('datainicial').focus();
    return false;
    
  }
  
  if (dtFinal == "") {
    dtFinal = dtInicial;
  }

  if (codigoordem != "") {
    $('agrupar').value = 2;
  }

  js_divCarregando("Aguarde, consultando informações.","msgBox"); 
  js_controleBotoes(true); 
  var oRequisicao                 = new Object();
  oRequisicao.exec                = "getMovimentos";
  oRequisicao.options             = new Object();
  oRequisicao.options.dtIni       = dtInicial; 
  oRequisicao.options.dtFim       = dtFinal;
  oRequisicao.options.agrupar     = $F('agrupar');
  oRequisicao.options.codigoordem = $F('e50_codord');  
  
  var sJson = js_objectToJson(oRequisicao); 
  var oAjax = new Ajax.Request(
                           sUrlRPC, 
                           {
                            method    : 'post', 
                            parameters: 'json='+sJson, 
                            onComplete: js_retornoGetMovimentosAgrupados
                            }
                          );
}

function js_retornoGetMovimentosAgrupados(oAjax) {

  js_removeObj("msgBox");
  js_controleBotoes(false);
  var oRetorno = eval("("+oAjax.responseText+")");

  $('col2').innerHTML = 'Seq';
  if (oRetorno.agrupar == "2") {
    $('col2').innerHTML = 'OP';
  }
    
  iTipoGerarSlip = oRetorno.agrupar;
  oGridSlip.clearAll(true);  
  if (oRetorno.status == 1) {
  
    var iRowAtiva = 0;
    for (var i = 0; i < oRetorno.aSlips.length;i++ ){
      
      with (oRetorno.aSlips[i]) {
	  
	  	var aLinha = new Array();

	  	if( k107_valor > 0) {
	  	  aLinha[0] = (i + 1);
	  	  if (oRetorno.agrupar == 2) {
	  		aLinha[0] = e82_codord;
	  	  }
	  	  aLinha[1] = k107_ctacredito;
	  	  aLinha[2] = creditar.urlDecode();
	  	  aLinha[3] = k107_ctadebito
	  	  aLinha[4] = debitar.urlDecode();
	  	  aLinha[5] = js_formatar(k107_valor, "f");
	  	  oGridSlip.addRow(aLinha);
	    }
      }
    }
    oGridSlip.renderRows();
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_objectToJson(oObject) { return JSON.stringify(oObject); 
  
   var sJson = oObject.toSource();
   sJson     = sJson.replace("(","");
   sJson     = sJson.replace(")","");
   return sJson;
   
}

function js_gerarsLip() {

  var aItens = oGridSlip.getSelection("object");
  if (aItens.length == 0) {
  
    alert("Nenhum Registro Selecionado.");
    return false;
  }
  
  var oRequisicao             = new Object();
  oRequisicao.exec            = "gerarSlips";
  oRequisicao.options         = new Object();
  oRequisicao.options.dtIni   = dtInicial; 
  oRequisicao.options.dtFim   = dtFinal;
  oRequisicao.options.aSlips  = new Array();
  oRequisicao.options.lAgenda = true;
  oRequisicao.options.agrupar = iTipoGerarSlip;
  for (var i = 0; i < aItens.length;i++ ) {
     
    var oSlip = new Object();
    oSlip.iCtaCredito  = aItens[i].aCells[2].getValue();
    oSlip.iCtaDebito   = aItens[i].aCells[4].getValue();
    oSlip.nValor       = js_strToFloat(aItens[i].aCells[6].getValue()).valueOf();
    oSlip.iCodigoOrdem = '';
    if (iTipoGerarSlip == 2) {
      oSlip.iCodigoOrdem = js_strToFloat(aItens[i].aCells[1].getValue()).valueOf();
    }
    oRequisicao.options.aSlips.push(oSlip);
  }
  if (!confirm('Confirma a emissão dos slips?')) {
    return false;
  }
  js_divCarregando("Aguarde, Gerando slips.","msgBox");  
  var sJson = js_objectToJson(oRequisicao); 
  var oAjax = new Ajax.Request(
                           sUrlRPC, 
                           {
                            method    : 'post', 
                            parameters: 'json='+sJson, 
                            onComplete: js_retornoGerarSlips
                            }
                          );
}
function js_retornoGerarSlips(oAjax) {
  
  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1 ) {
    
    var sSlis = oRetorno.aSlipsRetorno.implode(',');
    if (confirm(('Splis ('+sSlis+') Gerados com Sucesso.\nDeseja Emiti-los?'))) {
      js_emiteSlips(sSlis);
    }
    $('e50_codord').value = "";
    js_getMovimentosAgrupados();
  }
}

function js_emiteSlips(sSlips) {
   window.open('cai3_emiteslips002.php?slips='+sSlips,'','location=0');
}
 
function js_controleBotoes(lDisabled) {
   
   var aItens = $$('input[type=submit], input[type=button], button');
   aItens.each(function(input,id) {
       
     input.disabled = lDisabled;
       
   });
}
init();
</script>