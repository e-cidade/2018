<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require ("libs/db_stdlib.php");
require ("libs/db_utils.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
require ("libs/db_app.utils.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
?>
</head>
<body bgcolor='#CCCCCC' leftmargin="0" id='body' topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()" >
  <fieldset>
    <legend><b>Movimentos Pagos</b></legend>
    <div style='position:fixed;
              border:2px outset white;
              background-color: #CCCCCC;
              z-index:0;
              visibility:hidden;' id='digitarHistorico'>
     <div style='padding:0px;text-align:right;border-bottom: 2px outset white;background-color: #2C7AFE;color:white'>
       <span style='float:left'><b>Histórico</b></span>
       <img src='imagens/jan_fechar_on.gif' border='0' onclick="$('digitarHistorico').style.visibility='hidden';">
     </div>
     <div style='padding:3px ;border: 1px inset white'         
       <textarea id='historicoAnulacao' rows="10" cols="30">
       </textarea>
       <center>
         <input value='Confirma' type='button' id='atualizarHistorico' onclick='js_atualizaHistorico'>
       </center>          
    </div> 
  </div>
    <div id='gridNotas' style='z-index:0'>
    </div>
  </fieldset>
  <div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

  </div>
  <input type='button' value='Anular Selecionados' onclick='js_anularEmpenhos()'>
</body>
</html>
<script>
function js_init() {
 
  gridNotas              = new DBGrid("gridNotas");
  gridNotas.nameInstance = "gridNotas";
  gridNotas.selectSingle = function (oCheckbox,sRow,oRow) {
   if (oRow.getClassName() == 'comMov') {
     oCheckbox.checked = false;
   }
    if (oCheckbox.checked) {
      
      oRow.isSelected    = true;
      $(sRow).className  = 'marcado';
      oRow.isSelected    = true;
      
    } else {
  
      $(sRow).className = oRow.getClassName();
      oRow.isSelected   = false;
    }
  }
  gridNotas.setCheckbox(0);
  gridNotas.allowSelectColumns(true);
  gridNotas.setCellAlign(new Array("right", "center", "right", "left", "left", "right","right","right"));
  gridNotas.setHeader(new Array("Mov.","Empenho", "Nota", "Conta Pagadora", "Nome",
                                "data Pag",
                                "Valor Nota",
                                "Retenção",
                                "Valor",
                                "Histórico" 
                                )
                     );
  gridNotas.aHeaders[1].lDisplayed = false;                     
  gridNotas.show(document.getElementById('gridNotas'));
  js_getNotas();
}
sUrlRPC = "emp4_anularpagamentoRPC.php";
function js_getNotas() {
  
   var sJson = '{"exec":"getNotas"}'; 
   var oAjax   = new Ajax.Request(
                         sUrlRPC, 
                         {
                          method    : 'post', 
                          parameters: 'json='+sJson, 
                          onComplete: js_retornoGetNotas
                          }
                        );
}

function js_retornoGetNotas(oAjax) {

  var oResponse = eval("("+oAjax.responseText+")");
  gridNotas.clearAll(true);
  var iRowAtiva = 0;
  
  if (oResponse.status == 1) {

    for (var iNotas = 0; iNotas < oResponse.aNotasLiquidacao.length; iNotas++) {
     
      with (oResponse.aNotasLiquidacao[iNotas]) {
       
         var nValor =  k12_valor;
         var lDisabled = false;
         var sDisabled = "";
         var aLinha  = new Array();
         aLinha[0]   = e81_codmov;
         aLinha[1]   = "<a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;' href='#'>";
         aLinha[1]  += e60_codemp+"/"+e60_anousu+"</a>";
         aLinha[2]   = e50_codord;
         aLinha[3]   = js_createComboContasPag(e81_codmov, aContasVinculadas, e85_codtipo, lDisabled);
         aLinha[4]   = z01_nome.urlDecode().substring(0,50);
         aLinha[5]   = js_formatar(k105_data,'d');
         aLinha[6]   = js_formatar(e81_valor,"f");
         aLinha[7]  = "<a href='#'  id='retencao"+e81_codmov+"'>";
         aLinha[7] += js_formatar(valorretencao,"f")+"</a>";
         aLinha[8]  = "<input type = 'text' id='valorrow"+e81_codmov+"' size='9' style='height:100%;text-align:right;border:1px inset'";
         aLinha[8] += " class='valores' onchange='js_calculaValor(this,"+nValor+")'";
         aLinha[8] += "                 onkeypress='return js_teclas(event)'"; 
         aLinha[8] += "       value = '"+nValor+"' id='valor"+e50_codord+"' "+sDisabled+">";
         aLinha[9]  = "<div style='overflow:hidden;width:50px'>";
         aLinha[9] += "<span><a href='#' onclick='js_showHistorico("+e81_codmov+")'><img src='imagens/edittext.png' border='0' ></a></span>";
         aLinha[9] += "<span id='historico"+e81_codmov+"' onmouseover='js_setAjuda(this.innerHTML,true)' ";
         aLinha[9] += "      onmouseout='js_setAjuda(\"\",false)'></span>...</div>";
         gridNotas.addRow(aLinha, false, lDisabled);
         gridNotas.aRows[iRowAtiva].aCells[5].sEvents  = "onmouseover='js_setAjuda(\""+z01_nome.urlDecode()+"\",true)'";
         gridNotas.aRows[iRowAtiva].aCells[5].sEvents += "onmouseOut='js_setAjuda(null,false)'";
         gridNotas.aRows[iRowAtiva].sValue  = e81_valor;
         iRowAtiva++;
          
      }
    }
    gridNotas.renderRows();
  }
  
}

function js_createComboContasPag(iCodMov, aContas, iContaConfig, lDisabled) {

  var sDisabled = "";
  if (lDisabled == null) {
   lDisabled = false;
  }
  if (lDisabled) {
    sDisabled = " disabled ";
  }
  var sCombo  = "<select id='ctapag"+iCodMov+"' class='ctapag' style='width:100%'";
  sCombo     += " onchange='js_getSaldos(this)' "+sDisabled+">";
  sCombo     += "<option value=''>Selecione</option>";
  if (aContas != null) {
    
    for (var i = 0; i < aContas.length; i++) {
       
      var sSelected = "";
      if (iContaConfig == aContas[i].e83_codtipo) {
        sSelected = " selected ";
      } 
      var sDescrConta =  aContas[i].e83_conta+" - "+aContas[i].e83_descr.urlDecode()+" - "+aContas[i].c61_codigo;
      sCombo += "<option "+sSelected+" value = "+aContas[i].e83_codtipo+">"+sDescrConta+"</option>";
      
    }
  }
  sCombo  += "</select>";
  return sCombo;
}
function js_setAjuda(sTexto,lShow) {

  if (lShow) {
  
    el =  $('gridNotas'); 
    var x = 0;
    var y = el.offsetHeight;
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

     x += el.offsetLeft;
     y += el.offsetTop;
     el = el.offsetParent;

   }
   x += el.offsetLeft;
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+10;
   $('ajudaItem').style.left    = x;
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
  
  
}
function js_calculaValor(oTextObj, nMaxVal) {
  
  if (new Number(oTextObj.value) > new Number(nMaxVal).toFixed(2)) {
     oTextObj.value  = nMaxVal;
  }
}

function js_showHistorico(iCodMov) {

  var el =  $('gridNotas'); 
  var x  = el.scrollWidth/2;
  var y  = el.offsetTop;
  
  
  $('digitarHistorico').style.top     = (y)+"px";
  $('digitarHistorico').style.left    = (x)+"px";
  $('digitarHistorico').style.visibility = 'visible';
  $('historicoAnulacao').value = $('historico'+iCodMov).innerHTML;
  $('historicoAnulacao').focus();
  $('atualizarHistorico').onclick = function() {
    
    $('digitarHistorico').style.visibility ='hidden';
    $('historico'+iCodMov).innerHTML    = $('historicoAnulacao').value;
    $('historicoAnulacao').value         = '';
  }
}

function js_objectToJson(oObject) { return JSON.stringify(oObject); 
  
   var sJson = oObject.toSource();
   sJson     = sJson.replace("(","");
   sJson     = sJson.replace(")","");
   return sJson;
   
}

function js_anularEmpenhos() {

  var aMovimentos =  gridNotas.getSelection("object");
  
  if (aMovimentos.length == 0) {
    
    alert('Nenhum Movimento Selecionado');
    return false;
    
  }
  var oRequisicao         = new Object();
  oRequisicao.exec        = "anularMovimentosPagos";
  oRequisicao.aMovimentos = new Array();
  for (var i = 0; i < aMovimentos.length; i++) {
  
    var oMovimento          = new Object(); 
    oMovimento.iCodMov      = aMovimentos[i].aCells[0].getValue();
    oMovimento.sHistorico   = encodeURIComponent($('historico'+oMovimento.iCodMov).innerHTML);
    oMovimento.iNotaLiq     = aMovimentos[i].aCells[3].getValue();
    oMovimento.nValorAnular = aMovimentos[i].aCells[9].getValue();
    oMovimento.iConta       = aMovimentos[i].aCells[4].getValue();
    
    oRequisicao.aMovimentos.push(oMovimento);
  }
  alert(js_objectToJson(oRequisicao)); 
}
</script>