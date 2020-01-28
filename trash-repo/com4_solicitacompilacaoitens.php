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

/**
 * 
 * @author I
 * @revision $Author: dbmatheus.felini $
 * @version $Revision: 1.4 $
 */
require("libs/db_stdlib.php");
require("std/db_stdClass.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("pc16_codmater");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("pc11_just");
$clrotulo->label("pc11_resum");
$clrotulo->label("pc11_pgto");
$clrotulo->label("pc11_prazo");
$oDaoUnidades = db_utils::getDao("matunid");
$sSqlUnid     = $oDaoUnidades->sql_query_file(null, "m61_codmatunid,substr(m61_descr,1,20) as m61_descr,
                                                     m61_usaquant,m61_usadec", "m61_descr");
$rsUnid             = $oDaoUnidades->sql_record($sSqlUnid);
$aUnidades          = db_utils::getColectionByRecord($rsUnid); 
$aParametrosCompras = db_stdClass::getParametro("pcparam",array(db_getsession("DB_anousu")));
$db_opcao           = 1; 
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js,datagrid.widget.js, widgets/dbautocomplete.widget.js");
db_app::load("widgets/windowAux.widget.js");
?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
<style>

 .fora {background-color: #d1f07c;}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_init()">
  <center>
    <table width="70%">
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Itens Cadastrados</b>
            </legend>
            <div id='gridItensSolicitacao'>
             
            </div>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center;">
          <input type="button" value="Salvar Itens" id='btnSalvarItens'>
        </td>
      </tr>
    </table>
  </center>
</body>
<div id='divOUtrasInf' style='display: none; text-align: center;' >
  <table width="100%">
    <tr>
       <td>
         <fieldset>
           <legend>
             <b>Dados Complementares</b>
           </legend>
           <table>
             <tr>
               <td nowrap title="<?=@$Tpc11_prazo?>">
                 <?=@$Lpc11_prazo?>
               </td>
               <td>
                 <?
                  db_textarea('pc11_prazo',3,30,$Ipc11_prazo,true,'text',$db_opcao)
                 ?>
               </td>
             </tr>
             <tr>
               <td nowrap title="<?=@$Tpc11_pgto?>">
                 <?=@$Lpc11_pgto?>
               </td>
               <td>
                 <?
                  db_textarea('pc11_pgto',3,30,$Ipc11_pgto, true, 'text', $db_opcao);
                 ?>
               </td>
             </tr> 
             <tr>
               <td nowrap title="<?=@$Tpc11_resum?>">
                 <?=@$Lpc11_resum?>
               </td>
               <td>
                 <?
                  db_textarea('pc11_resum',3,30,$Ipc11_resum,true,'text',$db_opcao)
                 ?>
               </td>
             </tr>
             <tr>
               <td nowrap title="<?=@$Tpc11_just?>">
                 <?=@$Lpc11_just?>
               </td>
               <td>
                 <?
                  db_textarea('pc11_just',3,30,$Ipc11_just,true,'text',$db_opcao)
                 ?>
               </td>
             </tr>
             <tr>
              <td>
                <b>Unidade:</b>
              </td>
              <td>
              <?
                db_select('pc17_unid',array(), true, 1, "style='width:150px' onchange='js_usaQuantidade(this)'");
                db_input('pc17_quant', 5, 0, true, 'text', 1, "style='display:none'");
              ?>  
              </td>
             </tr> 
           </table>
         </fieldset>
       </td>
    </tr>
    <tr>
      <td colspan="4" style='text-align: center'>
        <input type='button' value='Salvar Informações' id='btnFecharWindowAux' onclick='windowAuxiliar.hide()'>
      </td>
    </tr>
  </table>
</div>
</html>
<script>
var sUrlRC = 'com4_solicitacaoComprasRegistroPreco.RPC.php';
function js_init() {
  
  oGridItens     = new DBGrid('gridItens');
  oGridItens.nameInstance = "oGridItens";
  oGridItens.setCheckbox(0);
  oGridItens.setHeight(300);
  oGridItens.setCellAlign(new Array("right","right","Left","left","center","right","right", "right"));
  oGridItens.setCellWidth(new Array("4%","10%","45%",'10%', "5%","10%",'16%','16%','16%'));
  oGridItens.setHeader(new Array("Seq","Codigo","Descrição","Unidade(Qtd)","Out.Inf.","Qtde","Qtd. Min.","Qtd. Max.",
                                 "Indice"));
  oGridItens.aHeaders[9].lDisplayed=false;                                 
  oGridItens.show($('gridItensSolicitacao')); 
  js_makeWindow(); 
  $('btnSalvarItens').observe("click", js_salvarItens);
  js_parametros();
  
}

function js_pesquisapc16_codmater(mostra) {

  if (mostra==true) {
    js_OpenJanelaIframe('',
                        'db_iframe_pcmater', 
                        'func_pcmatersolicita.php?funcao_js=parent.js_mostrapcmater1|pc01_codmater|pc01_descrmater',
                        'Pesquisar Materias/Serviços',
                         true,
                         '0'
                        );
  } else {
  
    if ($F('pc16_codmater') != '') { 
      
      js_OpenJanelaIframe('',
                          'db_iframe_pcmater',
                          'func_pcmatersolicita.php?pesquisa_chave='+
                          $F('pc16_codmater')+
                          '&funcao_js=parent.js_mostrapcmater',
                          'Pesquisar Materiais/Serviços',
                          false,'0'
                      );
    } else {
      $('pc16_codmater').value = '';
    }
  }
}

function js_mostrapcmater1(iCodigoMaterial, sDescricaoMaterial) {
  
  $('pc16_codmater').value   = iCodigoMaterial;
  $('pc01_descrmater').value = sDescricaoMaterial;
  if (!$('chkVerificaAutomatico').checked) {
    
    js_adicionarItem();
    db_iframe_pcmater.hide();
    
  } else {
    $('quantidade').value = prompt("Informe a quantidade:");
    js_adicionarItem();
  }
}

/**
 * Adiciona o item a solicitacao
 */
function js_adicionarItem() {
 
  js_divCarregando('Aguarde, adicionando item',"msgBox");
  var iCodigoItem    = $F('pc16_codmater');
  var oParam         = new Object();
  oParam.exec        = "adicionarItemAbertura";
  oParam.iCodigoItem = iCodigoItem;
  oParam.quantidade  = $F('quantidade');
  oParam.sJustificativa = encodeURIComponent(tagString($F('pc11_just')));
  oParam.sResumo        = encodeURIComponent(tagString($F('pc11_resum')));
  oParam.sPrazo         = encodeURIComponent(tagString($F('pc11_prazo')));
  oParam.sPgto          = encodeURIComponent(tagString($F('pc11_pgto')));
  oParam.iUnidade       = $F('pc17_unid'); 
  oParam.nQuantUnidade  = $F('pc17_quant'); 
  var oAjax          = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoadicionarItem
                                         });
}

function js_retornoadicionarItem(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    js_preencheGrid(oRetorno.itens);
    js_limparForm();
    
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_preencheGrid(aItens) {
  
  oGridItens.clearAll(true);
  for(var i = 0; i < aItens.length; i++) {
    
    with (aItens[i]) {
    
      var aLinha = new Array();
      aLinha[0]  = i+1;
      aLinha[1]  = codigoitem;
      aLinha[2]  = descricaoitem.urlDecode();
      aLinha[3]  = "<span id='unidade"+indice+"'>"+unidade+"</span>";  
      aLinha[3] += "<span id='quantunid"+indice+"' style='display:none'>"+quantidadeunid+"</span>("+quantidadeunid+")";  
      aLinha[4]  = "<span id='justificativa"+indice+"' style='display:none'>"+justificativa.urlDecode()+"</span>";  
      aLinha[4] += "<span id='resumo"+indice+"'        style='display:none'>"+resumo.urlDecode()+"</span>";  
      aLinha[4] += "<span id='pgto"+indice+"'          style='display:none'>"+pagamento.urlDecode()+"</span>";  
      aLinha[4] += "<span id='prazo"+indice+"'         style='display:none'>"+prazo.urlDecode()+"</span>";  
      aLinha[4] += "<span><a href='#' onclick='js_showInfo("+indice+","+automatico+")'><img src='imagens/edittext.png' border='0' ></a>...</span>";
      aLinha[5]  = js_formatar(aItens[i].quantidade,'f');
      /*aLinha[6]  = "<input type='text' onKeyPress=\"return js_mask(event,'0-9|.|-')\"";
      aLinha[6] += "onfocus='js_liberaDigitacao(this)' onblur='js_bloqueiaDigitacao(this);";
      aLinha[6] += " js_salvarQuantidade("+indice+",this.value, 1)'";
      aLinha[6] += " onkeyDown='js_verifica(this,event,false)' "
      aLinha[6] += "style='width:100%;text-align:right;border:1px solid transparent;;height:100%'";
      aLinha[6] += "value='"+js_formatar(aItens[i].qtdemin,'f')+"'>";*/
      aLinha[6]  = js_formatar(aItens[i].qtdemin,'f');
      /*aLinha[7]  = "<input type='text' onKeyPress=\"return js_mask(event,'0-9|.|-')\"";
      aLinha[7] += "onfocus='js_liberaDigitacao(this)' onblur='js_bloqueiaDigitacao(this);";
      aLinha[7] += " js_salvarQuantidade("+indice+",this.value, 2)'";
      aLinha[7] += " onkeyDown='js_verifica(this,event,false)' "
      aLinha[7] += "style='width:100%;text-align:right;border:1px solid transparent;height:100%'";
      aLinha[7] += "value='"+js_formatar(aItens[i].qtdemax,'f')+"'>";*/
      aLinha[7] = js_formatar(aItens[i].qtdemax,'f');
      aLinha[8] = new String(indice).valueOf();
      oGridItens.addRow(aLinha,true, false, ativo);
      if (!automatico) {
         oGridItens.aRows[i].setClassName("fora");
      }
      //oGridItens.aRows[i].aCells[0].sStyle +="background-color:#DED5CB;font-weight:bold;padding:1px";
      
    }
  }
  oGridItens.renderRows();
}     

function js_salvarItens() {
 
 var aItensCompilacao = new Array();
 /**
  * Verificamos todos os itens e definimos se o usuário deixou o item ativo ou nao 
  */
  var aItens = oGridItens.aRows;
  aItens.each(function (oItem, id) {
    
    var oItemSolicitacao = new Object();
    oItemSolicitacao.iIndice = oItem.aCells[9].getValue();    
    oItemSolicitacao.lAtivo  = oItem.isSelected;    
    aItensCompilacao.push(oItemSolicitacao);
    
  });
  js_divCarregando('Aguarde, Salvando Itens',"msgBox");
  var oParam         = new Object();
  oParam.exec        = "salvarItensAbertura";
  oParam.aItens      = aItensCompilacao;
  var oAjax          = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoSalvarItem
                                         });

}

function js_retornoSalvarItem(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    alert('Itens Salvos Com sucesso.')
  } else {
   alert(oRetorno.message.urlDecode());
  }
}

function js_limparForm() {

  $('pc16_codmater').value   = "";
  $('pc01_descrmater').value = "";
  $('pc11_resum').value      = ""; 
  $('pc11_just').value       = "";
  $('pc11_pgto').value       = "";
  $('pc11_prazo').value      = "";
  
}

function js_excluirLinha(iSeq) {
  
  js_divCarregando('Aguarde, removendo item',"msgBox"); 
  var oParam         = new Object();
  oParam.exec        = "excluirItens";
  oParam.iItemRemover = iSeq;
  var oAjax          = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoadicionarItem
                                         });

}
/**
 * Libera  o input passado como parametro para a digitacao.
 * é Retirado a mascara do valor e liberado para Edição
 * é Colocado a Variavel nValorObjeto no escopo GLOBAL
 */
function js_liberaDigitacao(object) {
  
  nValorObjeto        = object.value; 
  object.value        = js_strToFloat(object.value).valueOf();
  object.style.border = '1px solid black';
  object.readOnly     = false;
  object.style.fontWeight = "bold";
  object.select();
   
}

/**
 * bloqueia  o input passado como parametro para a digitacao.
 * É colocado  a mascara do valor e bloqueado para Edição
 */
function js_bloqueiaDigitacao(object, iBold) {

  
  object.readOnly         = true;
  object.style.border     ='1px';
  object.style.fontWeight = "normal";
  if (iBold) {
    object.style.fontWeight = "bold";
  }
  object.value            = js_formatar(object.value,'f');
  
  
   
}
/**
 * Verifica se  o usuário cancelou a digitação dos valores.
 * Caso foi cancelado, voltamos ao valor do objeto, e 
 * bloqueamos a digitação
 */
function js_verifica(object,event,iBold) {

  var teclaPressionada = event.which;
  if (teclaPressionada == 27) {
      object.value = nValorObjeto;
     js_bloqueiaDigitacao(object,iBold);
  }
}

function js_salvarQuantidade(iIndice, nQuantidade, iTipo) {

  js_divCarregando('Aguarde, Salvando informação',"msgBox");
  var oParam         = new Object();
  oParam.exec        = "salvarItensValor";
  oParam.iIndice     = iIndice;
  oParam.iTipo       = iTipo;
  oParam.quantidade  = js_strToFloat(nQuantidade).valueOf();
  var oAjax          = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoSalvarQuantidade
                                         });
}

function js_retornoSalvarQuantidade(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  }
}

function js_usaQuantidade(oSelect) {

  if (oSelect.options[oSelect.selectedIndex].getAttribute("usaquantidade") == "t") {
    $('pc17_quant').style.display = ''; 
  } else {
    $('pc17_quant').style.display = 'none';
  }
    
}
function js_parametros() {
   
  var oParam         = new Object();
  oParam.exec        = "getParametros";
  oParam.sleep       = 5;
  oParam.aParametros = new Array();
  var oParamCompras  = new Object();
  oParamCompras.sParam = "pcparam";
  oParamCompras.aKeys = new Array();
  oParam.aParametros.push(oParamCompras);
  var oParamOrcam  = new Object();
  oParamOrcam.sParam = "orcparametro";
  oParamOrcam.aKeys = new Array();
  oParamOrcam.aKeys.push(2009);
  oParam.aParametros.push(oParamOrcam);
  var oAjax          = new Ajax.Request('sys4_parametros.RPC.php',
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoparametro,
                                          assynchronous: false
                                         });
}

function js_maisInformacoes() {
  
  windowAuxiliar.show(10,10); 
  $('pc11_prazo').focus();
  
}

function js_makeWindow() {
  
  windowAuxiliar = new windowAux('wndAuxiliar', 'Dados Complementares', 600, 500);
  windowAuxiliar.setObjectForContent($('divOUtrasInf'));
  windowAuxiliar.hide();
  //$('divOUtrasInf').style.display= '';
  
}

function js_showInfo(iIndice, lAutomatico) {

  iIndice = iIndice; 
  js_bloqueiaCamposWindowAux(lAutomatico);
  $('pc11_resum').value = $('resumo'+iIndice).innerHTML; 
  $('pc11_just').value  = $('justificativa'+iIndice).innerHTML;
  $('pc11_pgto').value  = $('pgto'+iIndice).innerHTML;
  $('pc11_prazo').value = $('prazo'+iIndice).innerHTML;
  $('pc17_unid').value  = $('unidade'+iIndice).innerHTML;
  $('pc17_quant').value = $('quantunid'+iIndice).innerHTML;
  $('btnFecharWindowAux').onclick  = function () {
  
    $('resumo'+iIndice).innerHTML        = $('pc11_resum').value; 
    $('justificativa'+iIndice).innerHTML = $('pc11_just').value ;
    $('pgto'+iIndice).innerHTML          = $('pc11_pgto').value;
    $('prazo'+iIndice).innerHTML         = $('pc11_prazo').value;
    $('unidade'+iIndice).innerHTML       = $('pc17_unid').value;
    $('quantunid'+iIndice).innerHTML     = $('pc17_quant').value;
     
    $('pc11_resum').value = ""; 
    $('pc11_just').value  = "";
    $('pc11_pgto').value  = "";
    $('pc11_prazo').value = "";
    $('pc17_unid').value  = "";
    $('pc17_quant').value = "";
    $('btnFecharWindowAux').onclick  =  function() {
      windowAuxiliar.hide();
    }
    js_alterarDados(iIndice);
    windowAuxiliar.hide();
    
  }
   
  windowAuxiliar.show(10,10);
  
}

function js_alterarDados(iIndice) {
  
  js_divCarregando('Aguarde, alterando item',"msgBox");
  var oParam            = new Object();
  oParam.iIndice        = iIndice;
  oParam.sJustificativa = encodeURIComponent(tagString($('justificativa'+iIndice).innerHTML));
  oParam.sResumo        = encodeURIComponent(tagString($('resumo'+iIndice).innerHTML)); 
  oParam.sPrazo         = encodeURIComponent(tagString($('prazo'+iIndice).innerHTML));
  oParam.sPgto          = encodeURIComponent(tagString($('pgto'+iIndice).innerHTML));
  oParam.iUnidade       = $('unidade'+iIndice).innerHTML;
  oParam.nQuantUnidade  = $('quantunid'+iIndice).innerHTML;
  oParam.exec           = "alterarItem";
  var oAjax          = new Ajax.Request(sUrlRC,
                                         {
                                          method: "post",
                                          parameters:'json='+Object.toJSON(oParam),
                                          onComplete: js_retornoadicionarItem
                                         });
}
function js_retornoparametro(oAjax) {
  
  var oRetorno = eval("("+oAjax.responseText+")");
  for (var iParam = 0; iParam < oRetorno.itens.length; iParam++) {
    
     with(oRetorno.itens[iParam]) {
       eval("o"+name.valueOf()+"=fields");
     }
  }
  $('pc17_unid').value = opcparam[0].pc30_unid;
}

function js_bloqueiaCamposWindowAux(lDisabled) {
  
  var aDiv = $$('textarea');
  aDiv.each(function (oElemento, id) {
      oElemento.disabled = lDisabled;    
  });
  $('pc17_unid').disabled = lDisabled;
  $('pc17_quant').disabled = lDisabled;
}
/**
 * Adicionamos as unidades ao combo pc17_unid
 */
 <?
 
  foreach ($aUnidades as $oUnidade) {
    
    echo "var oOption = new Option('{$oUnidade->m61_descr}',{$oUnidade->m61_codmatunid});\n";
    echo "oOption.setAttribute('usadecimal', '{$oUnidade->m61_usadec}');\n";
    echo "oOption.setAttribute('usaquantidade', '{$oUnidade->m61_usaquant}');\n";
    echo "\$('pc17_unid').add(oOption, null);\n";
    
  }
 ?>
</script>