<?php
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

/**
 * 
 * @author I
 * @revision $Author: dbfabio.esteves $
 * @version $Revision: 1.10 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("std/db_stdClass.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$iNumeroCasasDecimais = 2; 
$aParametrosEmpenho   = db_stdClass::getParametro("empparametro", array(db_getsession("DB_anousu")));
if (count($aParametrosEmpenho) > 0) {
  $iNumeroCasasDecimais = $aParametrosEmpenho[0]->e30_numdec;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js,widgets/messageboard.widget.js");
db_app::load("widgets/dbautocomplete.widget.js, widgets/windowAux.widget.js,widgets/dbtextField.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
<script type="text/javascript" src="scripts/resgistroPrecoJulgamento.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<style>
  .menorPreco {background-color: #d1f07c}
  .bloqueado {background-color: rgb(222, 184, 135);}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">
  <table>
    <tr height="25">
      <td>&nbsp;</td>
    </tr>
   </table>
  <center>
     <table width="80%">
        <tr>  
          <td>
            <fieldset>
              <legend>
                 <b>Registros de preço</b>
              </legend>
              <div id='ctngridSolicita'>
              </div>              
            </fieldset>
          </td>
        </tr>
     </table>
  </center>
</body>
</html>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;z-index: 100000' 
            id='ajudaItem'>

</div>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>

  var iNumeroCasasDecimais = <?=$iNumeroCasasDecimais?>;
  var sUrlRPC = "lic4_licitacao.RPC.php";
  function js_init() {
  
    ogridSolicita     = new DBGrid('gridSolicita');
    ogridSolicita.nameInstance = "ogridSolicita";
    ogridSolicita.setHeight(300);
    ogridSolicita.setCellAlign(new Array("right","right","Left","center","center"));
    ogridSolicita.setCellWidth(new Array("10%","10%","50%",'15%', "15%"));
    ogridSolicita.setHeader(new Array("Licitacao","Registro","Descrição","Data Inicial","Data Final"));
    ogridSolicita.show($('ctngridSolicita'));
    js_getRegistroPreco(); 
    
  }
  
  function js_getRegistroPreco() {
  
    js_divCarregando('Aguarde, pesquisando.', 'msgBox');
    var oParam  = new Object();
    oParam.exec = "getRegistrosdePreco";
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoGetRegistroPreco
                                   });
  }
  
  function js_retornoGetRegistroPreco(oAjax) {
  
    js_removeObj('msgBox'); 
    var oRetorno = eval("("+oAjax.responseText+")");
    ogridSolicita.clearAll(true);
    if (oRetorno.status == 1) {
    
      if (oRetorno.itens.length == 0) {
        ogridSolicita.setStatus('Não foram encontrados Registros');
      }
      for (var i = 0; i < oRetorno.itens.length; i++) {
      
        with(oRetorno.itens[i]) {
          
          var aLinha = new Array();
          aLinha[0]  = licitacao;                 
          aLinha[1]  = solicitacao;                 
          aLinha[2]  = resumo.urlDecode().substring(0,50);
          aLinha[3]  = datainicio;
          aLinha[4]  = datatermino;
          ogridSolicita.addRow(aLinha);
          ogridSolicita.aRows[i].sEvents += "onDblClick='js_openMovimento("+solicitacao+","+orcamento+")'";
        }
      }
      ogridSolicita.renderRows();
    }
  }
  
  /**
   * Abre a Janela com os Movimentos
   */
  function js_openMovimento(iSolicitacao, iOrcamento) {
    
    iGlobalSolicitacao = iSolicitacao; 
    iGlobalOrcamento   = iOrcamento; 
    windowItensRegistro = new windowAux('windowItensRegistro','Registro de Preço:'+iSolicitacao, document.body.getWidth() - 30);
    windowItensRegistro.allowCloseWithEsc(false);
    var sContent  = "<fieldset>";
        sContent += "  <div id='ctnGridItens'>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "  <br><center>";
        sContent += "   <input type='button' id='btnJulgar' value='Julgamento' style='display:none;'>";
        sContent += "  </center>";
    windowItensRegistro.setContent(sContent);
    $('windowwindowItensRegistro_btnclose').onclick= function () {
      windowItensRegistro.destroy();
    };
    
    /**
     * Adiciona a grid na janela
     */ 
    oGridItens     = new DBGrid('gridItens');
    oGridItens.nameInstance = "oGridItens";
    oGridItens.setHeight((document.body.scrollHeight/1.5)-50);
    //oGridItens.setCheckbox(1);
    oGridItens.setCellAlign(new Array("center", "center", "Left", "left", "left", "right", "right"));
    oGridItens.setCellWidth(new Array("5%","5%","30%",'10%',"35%","10%","10%"));
    oGridItens.setHeader(new Array("Seq", "Codigo", "Item", "Unidade", "Complemento","Quant. Min.", "Quant. Max.","iSol"));
    oGridItens.aHeaders[7].lDisplayed = false;
    oGridItens.show($('ctnGridItens'));
    
    //js_getRegistroPreco(); 
     var sMsgAjuda  = "Dois cliques no item para cotar os valores por fornecedor. ";
     var oMessageBoard = new messageBoard('msg1',
                                         'Reequilibrio de Valores Cotados',
                                         sMsgAjuda,
                                         $('windowwindowItensRegistro_content')
                                         );
    oMessageBoard.show();
    windowItensRegistro.show(25,0);
    //windowItensRegistro.setChildOf()  
    js_getItensRegistroPreco(iSolicitacao);  
    $('btnJulgar').onclick=function () {js_julgarOrcamento(iGlobalOrcamento);};
    
  }
  
  /**
   * Consulta os itens do registro de preco que o usuário selecionou
   */
  function js_getItensRegistroPreco(iSolicitacao) {
  
    js_divCarregando('Aguarde, Carregando itens.', 'msgBox');
    var oParam          = new Object();
    oParam.exec         = "getItensRegistro";
    oParam.iSolicitacao = iSolicitacao;
    var oAjax           = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoGetItensRegistroPreco
                                   });
  } 
  
  function js_retornoGetItensRegistroPreco(oAjax) {
   
    
    js_removeObj('msgBox');
    oGridItens.clearAll(true);
    var oRetorno = eval("("+oAjax.responseText+")");
    var aItens   = oRetorno.itens;
    for(var i = 0; i < aItens.length; i++) {
    
      with (aItens[i]) {
      
        var aLinha = new Array();
        aLinha[0]  = i+1;
        aLinha[1]  = codigoitem;
        aLinha[2]  = descricaoitem.urlDecode();
        aLinha[3]  = descrunidade.urlDecode();  
        //aLinha[4]  = eval("txt"+codigoitemsol+"= new DBTextInput('txt"+codigoitemsol+"','txt"+codigoitemsol+"', resumo.urlDecode(),25);");
        aLinha[4]  = resumo.urlDecode().substring(0,30).replace(/\+/g, ' ');
        aLinha[5]  = js_formatar(aItens[i].qtdemin,'f');
        aLinha[6]  = js_formatar(aItens[i].qtdemax,'f');
        aLinha[7]  = codigoitemsol;
        oGridItens.addRow(aLinha);
        oGridItens.aRows[i].aCells[0].sStyle +=";padding:2px;";
        oGridItens.aRows[i].aCells[1].sStyle +=";padding:2px;";
        oGridItens.aRows[i].aCells[1].sStyle +=";padding:2px;";
        oGridItens.aRows[i].aCells[1].sStyle +=";padding:2px;";
        oGridItens.aRows[i].sEvents += "onDblClick='js_getFornecedoresItens("+codigoitemsol+")'";
        
      }
    }
    oGridItens.renderRows();
  }
  
  /**
   * Retorna todos os fornecedores do item selecionado.
   *
   */
  function js_getFornecedoresItens(iItem) {
  
    js_divCarregando('Aguarde, pesquisando fornecedores', 'msgBox');
    var oParam                    = new Object();
    oParam.exec                   = "getFornecedoresItemRegistro";
    oParam.iSolicitacao           = iGlobalSolicitacao;
    oParam.iCodigoItemSolicitacao = iItem;
    
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoFornecedoresItens
                                   });
  
  }
  
  function  js_retornoFornecedoresItens(oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    windowFornecItensRegistro = new windowAux('windowFornecItensRegistro','Item Registro de Preço', 
                                              document.body.getWidth() - 100, 500);
    windowFornecItensRegistro.allowCloseWithEsc(false);
    
    var sContent  = "<fieldset>";
        sContent += "  <div id='ctnGridFornecItens'>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "  <br><center>";
        sContent += "   <input type='button' id='btnSalvarFornecedores' value='Salvar e Julgar'>";
        sContent += "  </center>";
        
    windowFornecItensRegistro.setContent(sContent);
    $('windowwindowFornecItensRegistro_btnclose').onclick= function () {
      windowFornecItensRegistro.destroy();
    };
    
    /**
     * Adiciona a grid na janela
     */ 
    var sMsgAjuda  = "Indique o valor informado pelo(s) fornecedor(es) e suas justificativas. ";
    sMsgAjuda     += "Após informar os valores necessários, clique em 'Salvar e Julgar'.";
    var oMessageBoard = new messageBoard('msgBoard2',
                                         'Fornecedores do item',
                                         sMsgAjuda,
                                         $('windowwindowFornecItensRegistro_content')
                                         );
    oMessageBoard.show();
    windowItensRegistro.show(25,0);
    windowFornecItensRegistro.show(40,10);
    
    windowFornecItensRegistro.setChildOf(windowItensRegistro); 
    oGridFornecedoresItens     = new DBGrid('gridItensFonecedores');
    oGridFornecedoresItens.nameInstance = "oGridFornecedoresItens";
    oGridFornecedoresItens.setHeight(300);
    oGridFornecedoresItens.setCheckbox(5);
    oGridFornecedoresItens.setCellAlign(new Array("center", "Left", "Left", "left", "left"));
    oGridFornecedoresItens.setCellWidth(new Array("5%","30%","10%","30%","20%"));
    oGridFornecedoresItens.setHeader(new Array("Codigo", "Nome", "Valor", "Justificativa","Tipo",'id', "iCodigoOrcamento"));
    oGridFornecedoresItens.aHeaders[6].lDisplayed = false;
    oGridFornecedoresItens.aHeaders[7].lDisplayed = false;
    oGridFornecedoresItens.show($('ctnGridFornecItens'));
    oGridFornecedoresItens.clearAll(true);
    var aItens   = oRetorno.itens;
    for(var i = 0; i < aItens.length; i++) {
    
      with (aItens[i]) {
      
        var aLinha = new Array();
        var idRow  = pc23_orcamitem+"_"+pc21_orcamforne; 
        aLinha[0]  = pc23_orcamitem;
        aLinha[1]  = z01_nome.urlDecode();
        aLinha[2]  = eval("txtValor"+idRow+"= new DBTextField('txtValor"+idRow+"','txtValor"+idRow+"',js_formatar(aItens[i].pc23_vlrun,'f', "+iNumeroCasasDecimais+"))");
        
        aLinha[2].addStyle("height","100%");
        aLinha[2].addStyle("width","100%");  
        aLinha[2].addStyle("text-align","right");  
        aLinha[2].addStyle("border","1px solid transparent;");  
        aLinha[2].addEvent("onBlur","js_bloqueiaDigitacao(this);");  
        aLinha[2].addEvent("onBlur","txtValor"+idRow+".sValue=this.value;");  
        aLinha[2].addEvent("onFocus","js_liberaDigitacao(this);");  
        aLinha[2].addEvent("onKeyPress","return js_mask(event,\"0-9|.|-\")");  
        aLinha[2].addEvent("onKeyDown","return js_verifica(this,event,false)");  
        
        aLinha[3]  = eval("txtJust"+idRow+"= new DBTextField('txtJust"+idRow+"','txtJust"+idRow+"')");
        aLinha[3].setExpansible(true,100, 350);
        aLinha[3].addStyle("height","100%");
        aLinha[3].addStyle("width","100%");
        aLinha[4]  = js_createComboMovimentos("cbo"+idRow);
        aLinha[5]  = idRow;
        aLinha[6]  = pc22_codorc;
        lBloquear  = false;
        lChecked   = true;
        if (bloqueio != "") {
          
          lBloquear  = true;
          lChecked   = false;
          
        }
        oGridFornecedoresItens.addRow(aLinha, false, lBloquear, lChecked);
        if (bloqueio != "") {
        
          oGridFornecedoresItens.aRows[i].setClassName('bloqueado');
          oGridFornecedoresItens.aRows[i].sEvents += "onMouseOver='js_setAjuda(\"Desistência:<b>"+bloqueio.urlDecode()+"</b>\",true)'";
          oGridFornecedoresItens.aRows[i].sEvents += "onMouseOut='js_setAjuda(null, false)'";
        }
      }
    }
    oGridFornecedoresItens.renderRows();
    $('btnSalvarFornecedores').onclick= js_salvarValoresItens;
  } 
  js_init(); 
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

  if (new Number(object.value) <=0) {
   
    alert('Informe um valor Válido');
    object.value = nValorObjeto;
     
  } 
  object.readOnly         = true;
  object.style.border     ='1px';
  object.style.fontWeight = "normal";
  if (iBold) {
    object.style.fontWeight = "bold";
  }
  object.value            = js_formatar(object.value,'f', iNumeroCasasDecimais);
  
  
   
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

function js_createComboMovimentos(sName) {
  
  var sCombo  = "<select id='"+sName+"' style='width:100%'>";
  <?

    $oDaTipoMov   = db_utils::getDao("tipomovimentacaoregistropreco");
    $rsMovimentos = $oDaTipoMov->sql_record($oDaTipoMov->sql_query(null,"*","l33_sequencial"));
    $aItens       = db_utils::getColectionByRecord($rsMovimentos);
    foreach ($aItens as $oMovimento) {
      echo "sCombo += \"<option value='{$oMovimento->l33_sequencial}'>{$oMovimento->l33_descricao}</option>\"\n"; 
    }
    ?>
  sCombo += "</select>";
  return sCombo;    
}

function js_salvarValoresItens() {

  if (!confirm("Após salvar os dados informados o item será rejulgado. Confirma esta operação?")) {
    return false;
  }
  var aItens = oGridFornecedoresItens.getSelection("object");
  var oParam = new Object();
  oParam.exec = "saveValoresFornecedoresRegistro";
  
  oParam.iSolicitacao     = iGlobalSolicitacao;
  oParam.aItens           = new Array();
  oParam.iCodigoOrcamento = aItens[0].aCells[7].getValue();
  oParam.iCodigoItemOrcamento = aItens[0].aCells[1].getValue();
  
  for (var i = 0; i < aItens.length; i++) {
  
     var oItemFornecedor = new Object();
     var aChaves   = aItens[i].aCells[0].getValue().split("_");
     oItemFornecedor.iItemOrcamento   = aChaves[0];
     oItemFornecedor.iItemFornecedor  = aChaves[1];
     oItemFornecedor.sJustificativa   = tagString(aItens[i].aCells[4].getValue());
     oItemFornecedor.iTipoMovimento   = tagString(aItens[i].aCells[5].getValue());
     
     oItemFornecedor.nValor = js_formatar( aItens[i].aCells[3].getValue(), 'f', iNumeroCasasDecimais );
     oItemFornecedor.nValor = oItemFornecedor.nValor.replace( /\./g, "" );
     oItemFornecedor.nValor = oItemFornecedor.nValor.replace( ",", "." );
     
     oParam.aItens.push(oItemFornecedor);
  }
  js_divCarregando('Aguarde, salvando fornecedores', 'msgBox');
  var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoSalvarValoresItens
                                   }); 
}

function js_retornoSalvarValoresItens(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {

    alert("Item salvo e rejulgado com sucesso.");
    windowFornecItensRegistro.destroy();
    js_showVencedores(iGlobalSolicitacao, iGlobalOrcamento);
  } else {
   alert(oRetorno.message.urlDecode());
  }
}

function js_julgarOrcamento(iOrcamento) {

  if (!confirm('Confirma o rejulgamento do Registro de preco?')) {
    return false;
  }
  
  js_divCarregando('Aguarde, julgando registro de preço', 'msgBox');
  var oParam                    = new Object();
  oParam.exec                   = "julgarRegistroPreco";
  oParam.iSolicitacao           = iGlobalSolicitacao;
  oParam.iOrcamento             = iOrcamento;
  var oAjax   = new Ajax.Request(sUrlRPC,
                                {
                                 method: "post",
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoJulgarOrcamento
                                }); 
}

function js_retornoJulgarOrcamento(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if  (oRetorno.status == 1) {
    js_showVencedores(iGlobalSolicitacao, iGlobalOrcamento);
  } else {
    alert(oRetorno.message.urlDecode());
  }  
}
function js_setAjuda(sTexto,lShow) {

  if (lShow) {
   
    var el    =  $('gridgridItensFonecedores'); 
    var x = 0;
    var y = el.offsetHeight;

    //Walk up the DOM and add up all of the offset positions.
    while (el.offsetParent && el.tagName.toUpperCase() != 'BODY')
    {
     // if (el.className != "windowAux12") { 
      
        x += el.offsetLeft;
        y += el.offsetTop;
        
     // }
      el = el.offsetParent;
    }
   x += el.offsetLeft;
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+"px";
   $('ajudaItem').style.left    = x+"px";
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
}
</script>