<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
$oGet = db_utils::postMemory($_GET);

if ($oGet->iForma == 1) {

  $sCaption = "Pagamento em Dinheiro";
} else if ($oGet->iForma == 2){
  $sCaption = "Pagamento em Cheque";
}else if ($oGet->iForma == 4){
  $sCaption = "Débito em Conta";
}
$iTipoControleRetencaoMesAnterior = 0;
$aParametrosEmpenho = db_stdClass::getParametro("empparametro",array(db_getsession("DB_anousu")));
if (count($aParametrosEmpenho) > 0) {
  $iTipoControleRetencaoMesAnterior = $aParametrosEmpenho[0]->e30_retencaomesanterior;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
?>
<style>
.soRetencao{
    background-color: #d1f07c;
}
</style>
</head>
<body bgcolor='#CCCCCC' leftmargin="0" id='body' topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()" >

  <fieldset>
    <legend><b><?=$sCaption?></b></legend>
    <div style='position:fixed;
              border:2px outset white;
              background-color: #CCCCCC;
              z-index:0;
              visibility:hidden;' id='digitarHistorico'>

       <div style='padding:0px;text-align:right;border-bottom: 2px outset white;background-color: #2C7AFE;color:white'>
         <span style='float:left'><b>Histórico</b></span>
         <img src='imagens/jan_fechar_on.gif' border='0' onclick="$('digitarHistorico').style.visibility='hidden';">
       </div>

       <div style='padding:3px ;border: 1px inset white'>
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
  <input type='button' value='Pagar Selecionados' onclick='js_pagarEmpenhos()'>
  <input type="checkbox" id='autenticar' checked><label for="autenticar">Autenticar no documento</label>
  <input type="checkbox" id='emiterelatorio'><label for="emiterelatorio">Emite Relatório de Pagamento</label>
  <input type="checkbox" id='emiterecibo'><label for="emiterecibo">Emite Reciboderetencoes</label>
</body>
</html>
<script>
iForma                           = <?=$oGet->iForma?>;
iTipoControleRetencaoMesAnterior = <?=$iTipoControleRetencaoMesAnterior?>;
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

    /**
     * Atualiza inputs com total e registros
     * com total dos itens selecionados
     */
    var aSelecionados = this.getSelection('object');
    var iSelecionados = aSelecionados.length;
    var nTotal        = 0;

    for ( var iLinha = 0; iLinha < iSelecionados; iLinha++ ) {

      var oLinha = aSelecionados[iLinha];
      var nValor = js_strToFloat(oLinha.aCells[11].content);
      nTotal += nValor;
    }

    parent.document.form1.tot.value = js_formatar(nTotal, 'f');
    parent.document.form1.registros.value = iSelecionados;
  }

  gridNotas.setCheckbox(0);
  gridNotas.allowSelectColumns(true);
  gridNotas.setCellAlign(new Array("right",
                                    "center",
                                    "left",
                                    "Right",
                                    "left",
                                    "left",
                                    "left",
                                    "right",
                                    "right",
                                    "right",
                                    "right",
                                    "left",
                                    "left"));
  gridNotas.setHeader(new Array("Mov.", "Cod. Cheque", "Empenho", "Recurso", "Ordem", "Conta Pagadora", "Nome",
                                "data Pag",
                                "Valor nota",
                                "Retenção",
                                "Pagar",
                                "Histórico",
                                "Cheque"
                                )
                     );
  gridNotas.aHeaders[2].lDisplayed  = false;
  if (iForma != 2) {
   gridNotas.aHeaders[13].lDisplayed  = false;
  }
  gridNotas.show(document.getElementById('gridNotas'));
  js_getNotas(iForma);

}
sUrlRPC = "emp4_pagarpagamentoRPC.php";
function js_getNotas(iForma) {

  parent.js_divCarregando("Aguarde, consultando Movimentos.","msgBox");
  var oParam           = new Object();
  oParam.iOrdemIni     = parent.$F('e50_codord');
  oParam.iOrdemFim     = parent.$F('e50_codord02');
  oParam.iCodEmp       = parent.$F('e60_codemp');
  oParam.iCodEmp2      = parent.$F('e60_codemp2');
  oParam.dtDataIni     = parent.$F('dataordemini');
  oParam.dtDataFim     = parent.$F('dataordemfim');
  oParam.dtChequeIni   = parent.$F('dtchequeini');
  oParam.dtChequeFim   = parent.$F('dtchequefim');
  oParam.iCtaPagadora  = parent.$F('e83_codtipo');
  oParam.iNumCgm       = parent.$F('z01_numcgm');
  oParam.iRecurso      = parent.$F('o15_codigo');
  oParam.sNumeroCheque = parent.$F('cheque');
  oParam.sDtAut        = parent.$F('e42_dtpagamento');
  oParam.iOPauxiliar   = parent.$F('e42_sequencial');
  oParam.iForma        = iForma;
  var sParam           = js_objectToJson(oParam);
  var sJson = '{"exec":"getNotas","params":['+sParam+']}';
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

  parent.js_removeObj("msgBox");
  var oResponse = eval("("+oAjax.responseText+")");
  gridNotas.clearAll(true);
  var iRowAtiva = 0;
  $('gridNotasstatus').innerHTML = "";
  if (oResponse.status == 1) {

    for (var iNotas = 0; iNotas < oResponse.aNotasLiquidacao.length; iNotas++) {

      with (oResponse.aNotasLiquidacao[iNotas]) {

         var nValor = 0;
         if (iForma == 1 || iForma == 4) {
           if (e81_valor == 0) {
             nValor = valorretencao;
           } else {
             nValor   =  (e81_valor - valorretencao - e53_vlranu);
           }
         } else {
           nValor = e91_valor;
         }
         var lDisabled = false;
         var sDisabled = "";
         var aLinha  = new Array();
         aLinha[0]   = e81_codmov;
         aLinha[1]   = e91_codcheque;
         aLinha[2]   = "<a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;' href='#'>";
         aLinha[2]  += e60_codemp+"/"+e60_anousu+"</a>";
         aLinha[3]    = o15_codigo,
         aLinha[4]   = e50_codord ;
         aLinha[5]   = e83_conta+"<span> - "+e83_descr.urlDecode().substring(0,15)+"</span>";
         aLinha[6]   = z01_nome.urlDecode().substring(0,30);
         aLinha[7]   = js_formatar(e50_data,'d');
         aLinha[8]   = js_formatar(e81_valor ,"f");
         aLinha[9]   = js_formatar(valorretencao,"f");
         aLinha[10]  = js_formatar(nValor,"f");
         aLinha[11]  = "<div style='overflow:hidden;width:50px'>";
         aLinha[11] += "<span><a href='#' onclick='js_showHistorico("+e81_codmov+")'><img src='imagens/edittext.png' border='0' ></a></span>";
         aLinha[11] += "<span id='historico"+e81_codmov+"' onmouseover='js_setAjuda(this.innerHTML,true)' ";
         aLinha[11] += "      onmouseout='js_setAjuda(\"\",false)'></span>...";
         aLinha[11] += "<span style='display:none' id='validarretencao"+e81_codmov+"'>"+validaretencao+"</span></div>";
         aLinha[12]  = e91_cheque;
         gridNotas.addRow(aLinha, false, lDisabled);
         gridNotas.aRows[iRowAtiva].aCells[7].sEvents  = "onmouseover='js_setAjuda(\""+z01_nome.urlDecode()+"\",true)'";
         gridNotas.aRows[iRowAtiva].aCells[7].sEvents += "onmouseOut='js_setAjuda(null,false)'";
         gridNotas.aRows[iRowAtiva].aCells[6].sEvents  = "onmouseover='js_setAjuda(\""+e83_conta+"<span> - "+e83_descr.urlDecode()+"</span>\",true)'";
         gridNotas.aRows[iRowAtiva].aCells[6].sEvents += "onmouseOut='js_setAjuda(null,false)'";
         gridNotas.aRows[iRowAtiva].sValue  = e81_valor;
         if (e81_valor == 0) {
           gridNotas.aRows[iRowAtiva].setClassName("soRetencao");
         }
         iRowAtiva++;

      }
    }
    gridNotas.renderRows();
  } else if (oResponse.status == 2) {
     $('gridNotasstatus').innerHTML = "&nbsp;<b>Não foram encontrados movimentos.</b>";
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
    $('historico'+iCodMov).innerHTML       = $('historicoAnulacao').value;
    $('historicoAnulacao').value           = '';
  }
}

function js_objectToJson(oObject) { return JSON.stringify(oObject); 

   var sJson = oObject.toSource();
   sJson     = sJson.replace("(","");
   sJson     = sJson.replace(")","");
   return sJson;

}

function js_pagarEmpenhos() {

  var aMovimentos =  gridNotas.getSelection("object");

  if (aMovimentos.length == 0) {

    alert('Nenhum Movimento Selecionado');
    return false;

  }
  oRequisicao             = new Object();
  oRequisicao.exec            = "pagarMovimento";
  oRequisicao.aMovimentos     = new Array();
  var lMostraMsgErroRetencao  = false;
  var sMsgRetencaoMesAnterior = "Atenção:\n";
  var sVirgula                = "";

  for (var i = 0; i < aMovimentos.length; i++) {

    var oMovimento           = new Object();
    oMovimento.iCodMov       = aMovimentos[i].aCells[0].getValue();
    oMovimento.sHistorico    = encodeURIComponent($('historico'+oMovimento.iCodMov).innerHTML);
    oMovimento.iNotaLiq      = aMovimentos[i].aCells[5].getValue();
    var lRetencaoMesAnterior = $('validarretencao'+oMovimento.iCodMov).innerHTML;

    if (js_strToFloat(aMovimentos[i].aCells[9].getValue()) == 0 ) {
      oMovimento.nValorPagar = 0;
    } else {
     oMovimento.nValorPagar  = js_strToFloat(aMovimentos[i].aCells[11].getValue()).valueOf();
    }

    if (lRetencaoMesAnterior == "true") {

      lMostraMsgErroRetencao   = true;
      sMsgRetencaoMesAnterior += sVirgula+"Movimento "+oMovimento.iCodMov+" da OP ";
      sMsgRetencaoMesAnterior += oMovimento.iNotaLiq+" possui retenções configuradas em meses anteriores.\n";
      sVirgula = ", ";

    }

    oMovimento.iConta       = aMovimentos[i].aCells[6].getValue();
    oMovimento.iCodCheque   = aMovimentos[i].aCells[2].getValue().trim();
    oMovimento.iCheque      = new Number(aMovimentos[i].aCells[13].getValue()).valueOf();
    oRequisicao.aMovimentos.push(oMovimento);

  }
  /**
     * verificamos o parametro para controle de retencões em meses anteriores.
     * caso seje 0 - não faz nenhuma critica ao usuário. apenas realiza o pagamento.
     *           1 - Avisa ao usuário e pede uma confirmação para realizar o pagamento.
     *           2 - Avisa ao usuário e cancela o pagamento do movimento
     */
  var sMsgConfirmaPagamento = "Deseja realmente efetuar pagamento para os movimentos selecionados?";
  if (iTipoControleRetencaoMesAnterior == 1) {

    if (lMostraMsgErroRetencao) {

      sMsgConfirmaPagamento  =  sMsgRetencaoMesAnterior;
      sMsgConfirmaPagamento += "É Recomendável recalcular as retenções.\n";
      sMsgConfirmaPagamento += "Deseja realmente efetuar pagamento para os movimentos selecionados?";
      if (!confirm(sMsgConfirmaPagamento)) {
         return false;
      }
    }
  } else if (iTipoControleRetencaoMesAnterior == 2) {

    if (lMostraMsgErroRetencao) {

      sMsgConfirmaPagamento    =  sMsgRetencaoMesAnterior;
      sMsgRetencaoMesAnterior += "Recalcule as Retenções do movimento.";
      alert(sMsgRetencaoMesAnterior);
      return false;

    }
  }

  parent.js_divCarregando("Aguarde, pagando movimentos.","msgBox")
  var sJson = js_objectToJson(oRequisicao);
  var oAjax = new Ajax.Request(
                         sUrlRPC,
                         {
                          method    : 'post',
                          parameters: 'json='+sJson,
                          onComplete: js_retornoPagarEmpenho
                          }
                        );
  return false;
}

function js_retornoPagarEmpenho(oAjax) {

  parent.js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.iItipoAutent != 3 && oRetorno.status == 1) {

    if ($('autenticar').checked) {

      aAutenticacoes = oRetorno.aAutenticacoes;
      iIndice     = 0;
      js_autenticar(oRetorno.aAutenticacoes[0],false);
    } else {

      js_emiterelpagamento();
      js_emiteRecibo();
      js_getNotas(iForma);

    }

  } else {
   alert(oRetorno.message.urlDecode());
  }

}
function js_autenticar(oAutentica, lReautentica) {

    var sPalavra = 'Autenticar';
    if (lReautentica) {
      var sPalavra = "Autenticar novamente";
    }
    if (confirm(sPalavra+' a Nota '+oAutentica.iNota+'?')) {

      var oRequisicaoAut      = new Object();
      oRequisicaoAut.exec     = "Autenticar";
      oRequisicaoAut.sString  = oAutentica.sAutentica;
      var sJson            = js_objectToJson(oRequisicaoAut);
      var oAjax = new Ajax.Request(
                         sUrlRPC,
                         {
                          method    : 'post',
                          parameters: 'json='+sJson,
                          onComplete: js_retornoAutenticacao
                          }
                        );

    } else {

      iIndice++;
      if (aAutenticacoes[iIndice]) {
        js_autenticar(aAutenticacoes[iIndice],false);
      } else {

        js_emiterelpagamento();
        js_emiteRecibo();
        js_getNotas(iForma);

      }
    }

}
function js_retornoAutenticacao(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    js_autenticar(aAutenticacoes[iIndice], true);

  } else {

    js_emiterelpagamento();
    js_emiteRecibo();
    js_getNotas(iForma);

  }
}

function js_emiterelpagamento() {

  var sData = "<?=date("Y-m-d",db_getsession("DB_datausu")) ?>";
  if ($('emiterelatorio').checked) {
    jan = window.open('cai2_emppago002.php?filtraemp=0&quebra=s&ordem=a&cod=&data='+sData+'&data1='+sData+'&z01_numcgm=',
                      '',
                      'height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
}

function js_emiteRecibo() {

  var aListaRequisicao = "";
  var sVirgula = "";
  for (var i =  0;  i < oRequisicao.aMovimentos.length; i++) {

    with (oRequisicao.aMovimentos[i]) {

      aListaRequisicao += sVirgula+iCodMov;
      sVirgula = ",";

    }
  }
  if ($('emiterecibo').checked) {
     jan = window.open('emp4_emitereciboretencao002.php?listaordens='+aListaRequisicao,
                      '',
                      'height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  }
}
</script>