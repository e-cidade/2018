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
 * @revision $Author: dbiuri $
 * @version $Revision: 1.3 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<?
db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/messageboard.widget.js");
db_app::load("widgets/dbautocomplete.widget.js, widgets/windowAux.widget.js,widgets/dbtextField.widget.js");
db_app::load("widgets/dbtextFieldData.widget.js");
db_app::load("estilos.css, grid.style.css");
?>
<script type="text/javascript" src="scripts/resgistroPrecoJulgamento.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<style>
/*  .bloqueado {background-color: #d1f07c}*/
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
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:300px;
            text-align: left;
            padding:3px;
            background-color: #FFFFCC;
            display:none;z-index: 100000' 
            id='ajudaItem'>

</div>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
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
          ogridSolicita.aRows[i].sEvents += "onDblClick='js_getFornecedores("+solicitacao+","+orcamento+")'";
        }
      }
      ogridSolicita.renderRows();
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
 /**
   * Abre a Janela com os Movimentos
   */
  function js_showItens(iRow) {
    
    oGlobalRowSelected = oGridFornecedoresItens.aRows[iRow];
    oGlobalRowSelected.isSelected = true;
    oGlobalRowSelected.setClassName("marcado");
    $(oGlobalRowSelected.sId).className = 'marcado';
    var iFornecedor     = oGlobalRowSelected.aCells[0].getValue()
    var sNomeFornecedor = oGlobalRowSelected.aCells[2].getValue();
    
    /** 
     * Criamos uma nova Janela para os itens
     */
    windowItensRegistro = new windowAux('windowItensRegistro','Itens Fornecedor: '+sNomeFornecedor, 
                                        document.body.getWidth() - 100,500);
    windowItensRegistro.allowCloseWithEsc(false);
    var sContent  = "<fieldset>";
        sContent += "  <div id='ctnGridItens' style='width:100%'>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "  <br><center>";
        sContent += "   <input type='button' id='btnSalvar' value='Salvar Itens'>";
        sContent += "  </center>";
    windowItensRegistro.setContent(sContent);
    $('windowwindowItensRegistro_btnclose').onclick= function () {
      
      oGlobalRowSelected.isSelected       = false;
      $(oGlobalRowSelected.sId).className = 'normal';
      oGlobalRowSelected.setClassName("normal");
      windowItensRegistro.destroy();
      
    }
    windowItensRegistro.show(40,10);
    windowItensRegistro.setChildOf(windowFornecItensRegistro);  
    
    /**
     * Adiciona a grid na janela
     */ 
    oGridItens     = new DBGrid('gridItens');
    oGridItens.nameInstance = "oGridItens";
    oGridItens.setHeight(300);
    oGridItens.setCheckbox(9);
    oGridItens.setCellAlign(new Array("center", "center", "Left", "left", "left", "right", "right"));
    oGridItens.setCellWidth(new Array("5%","5%","30%",'10%',"35%","10%","12%"));
    oGridItens.setHeader(new Array("Seq", "Codigo", "Item", "Unidade", "Complemento","Quant. Min.", "Quant. Max.","iSol", "iOrc"));
    oGridItens.aHeaders[8].lDisplayed = false;
    oGridItens.aHeaders[9].lDisplayed = false;
    oGridItens.show($('ctnGridItens'));
    
    js_getRegistroPreco(); 
    var oMessageBoard = new messageBoard('msg1',
                                         'Itens Disponíveis',
                                         'Marque os itens que o fornecedor desistiu.',
                                         $('windowwindowItensRegistro_content')
                                         );
    oMessageBoard.show();
    js_getItensFornec(oGlobalRowSelected.aCells[0].getValue());  
    $('btnSalvar').onclick=function () {js_SalvarItens(iFornecedor)};
    
  }
  
  /**
   * Consulta os itens do registro de preco que o usuário selecionou
   */
  function js_getItensFornec(iFornecedor) {
  
    js_divCarregando('Aguarde, Carregando itens.', 'msgBox');
    var oParam          = new Object();
    oParam.exec         = "getItensRegistro";
    oParam.iSolicitacao = iGlobalSolicitacao;
    oParam.iOrcamento   = iGlobalOrcamento;
    oParam.iFornecedor  = iFornecedor;
    var oAjax           = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoGetItensRegistroPreco
                                   });
  }

  /**
   * Preenche a grid com os itens que o fornecedor Cotou 
   */ 
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
        aLinha[4]  = resumo.urlDecode().substring(0,30).replace(/\+/g, ' ');
        aLinha[5]  = js_formatar(aItens[i].qtdemin,'f');
        aLinha[6]  = js_formatar(aItens[i].qtdemax,'f');
        aLinha[7]  = codigoitemsol;
        aLinha[8]  = codigoitemorca;
        oGridItens.addRow(aLinha, false, bloqueado, marcado);
        oGridItens.aRows[i].aCells[0].sStyle +=";padding:2px;";
        oGridItens.aRows[i].aCells[1].sStyle +=";padding:2px;";
        oGridItens.aRows[i].aCells[1].sStyle +=";padding:2px;";
        oGridItens.aRows[i].aCells[1].sStyle +=";padding:2px;";
        if (bloqueado) {
          oGridItens.aRows[i].setClassName('bloqueado');
          oGridItens.aRows[i].sEvents += "onMouseOver='js_setAjuda(\""+legenda.urlDecode()+"\",true)'";
          oGridItens.aRows[i].sEvents += "onMouseOut='js_setAjuda(null, false)'";
        }
        
      }
    }
    oGridItens.renderRows();
  }

  /**
   * Retorna todos os fornecedores do item selecionado.
   *
   */
  function js_getFornecedores(iSolicitacao, iOrcamento) {
  
    iGlobalSolicitacao = iSolicitacao; 
    iGlobalOrcamento   = iOrcamento; 
    js_divCarregando('Aguarde, pesquisando fornecedores', 'msgBox');
    var oParam                    = new Object();
    oParam.exec                   = "getFornecedores";
    oParam.iSolicitacao           = iGlobalSolicitacao;
    oParam.iOrcamento             = iOrcamento;
    var oAjax   = new Ajax.Request(sUrlRPC,
                                   {
                                    method: "post",
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete: js_retornoFornecedores
                                   });
  
  }
  
  /**
   * Monta a janela com os fornecedores
   */
  function  js_retornoFornecedores(oAjax) {
    
    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    windowFornecItensRegistro = new windowAux('windowFornecItensRegistro','Fornecedores ', document.body.getWidth() - 50);
    windowFornecItensRegistro.allowCloseWithEsc(false);
    
    var sContent  = "<fieldset><legend><b>Fornecedores</b></legend>";
        sContent += "  <div id='ctnGridFornecItens' style='width:100%'>";
        sContent += "  </div>";
        sContent += "</fieldset>";
        sContent += "<fieldset>";
        sContent += "  <table>";
        sContent += "    <tr>";
        sContent += "      <td>";
        sContent += "        <b>Justificativa:</b>";
        sContent += "      </td>";
        sContent += "      <td>";
        sContent += "        <textarea rows='5' cols=50 id='justificativa'></textarea>";
        sContent += "      </td>";
        sContent += "    </tr>";
        sContent += "      <td>";
        sContent += "        <b>Tipo:</b>";
        sContent += "      </td>";
        sContent += "      <td>";
        sContent +=        js_createComboMovimentos('pc54_tipo');        
        sContent += "      </td>";
        sContent += "    </tr>";
        sContent += "    </tr>";
        sContent += "      <td>";
        sContent += "        <b>Período:</b>";
        sContent += "      </td>";
        sContent += "      <td>";
        oTxtDataInicial = new DBTextFieldData('datainicial', 'oTxtDataInicial');
        oTxtDataFinal   = new DBTextFieldData('datafinal', 'oTxtDataFinal');
        sContent +=         oTxtDataInicial.toInnerHtml();       
        sContent +=         "&nbsp;&nbsp; <b> a</b>&nbsp;&nbsp;";       
        sContent +=         oTxtDataFinal.toInnerHtml();       
        sContent += "      </td>";
        sContent += "  </table>";
        sContent += "  </fieldset>";
        sContent += "  <br><center>";
        sContent += "   <input type='button' id='btnSalvarFornecedores' disabled  value='Salvar'>";
        sContent += "  </center>";
        
    windowFornecItensRegistro.setContent(sContent);
    $('windowwindowFornecItensRegistro_btnclose').onclick= function () {
      windowFornecItensRegistro.destroy();
    }
    windowFornecItensRegistro.allowDrag(false);
    windowFornecItensRegistro.show(25,10);
    
    /**
     * Adiciona a grid na janela
     */ 
    oGridFornecedoresItens     = new DBGrid('gridItensFonecedores');
    oGridFornecedoresItens.nameInstance = "oGridFornecedoresItens";
    oGridFornecedoresItens.setHeight(200);
    oGridFornecedoresItens.setCellAlign(new Array("center", "Left", "Left"));
    oGridFornecedoresItens.setCellWidth(new Array("50%","30%","100%"));
    oGridFornecedoresItens.setHeader(new Array("Código","CGM", "Nome"));
    oGridFornecedoresItens.show($('ctnGridFornecItens'));
    oGridFornecedoresItens.clearAll(true);
    var aItens   = oRetorno.itens;
    for(var i = 0; i < aItens.length; i++) {
    
      with (aItens[i]) {
      
        var aLinha = new Array();
        var idRow  = codigofornecedor; 
        aLinha[0]  = codigofornecedor;
        aLinha[1]  = codigocgm;
        aLinha[2]  = nome.urlDecode();
        oGridFornecedoresItens.addRow(aLinha);
        oGridFornecedoresItens.aRows[i].sEvents += "onDblClick='js_showItens("+i+")'";
        
      }
    }
    
    oGridFornecedoresItens.renderRows();
    var sMsg  = "Dois cliques no fornecedor para selecionar os itens. ";
        sMsg += "Após terminar a seleção, informe os dados solicitados e clique em 'Salvar'.";
    var oMessageBoardForne = new messageBoard('msg2',
                                         'Desistência de Registro de Preço',
                                         sMsg,
                                         $('windowwindowFornecItensRegistro_content')
                                         );
    oMessageBoardForne.show();
    $('btnSalvarFornecedores').onclick= function () {js_julgarOrcamento(iGlobalOrcamento)};
  } 
  
  function js_SalvarItens(iFornecedor) {
  
    var aItens          = oGridItens.getSelection("object");
    var oParam          = new Object();
    oParam.exec         = "saveItensDesistenciaFornecedor";
    oParam.iSolicitacao = iGlobalSolicitacao;
    oParam.iFornecedor  = iFornecedor;
    oParam.aItens       = new Array();
  
    for (var i = 0; i < aItens.length; i++) {
  
      var oItemFornecedor = new Object();
      oItemFornecedor.iItemOrcamento  = aItens[i].aCells[9].getValue()
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
  
    oGlobalRowSelected.isSelected       = false;
    $(oGlobalRowSelected.sId).className = 'normal';
    oGlobalRowSelected.setClassName("normal"); 
    windowItensRegistro.destroy();
    if (oRetorno.lHabilitarBotao) {
      $('btnSalvarFornecedores').disabled = false;
    } else {
      $('btnSalvarFornecedores').disabled = true;
    }
  } else {
    alert(oRetorno.message.urlDecode());
    $('btnSalvarFornecedores').disabled = true;
  }
}

function js_julgarOrcamento(iOrcamento) {

  if (!confirm('salvando a Desistência, o registro de preço sera julgado novamente.\nConfirma? ')) {
    return false;
  }
  
  if ($F('justificativa') == "") {
    
    alert('informe a justificativa da desistência.');
    $('justificativa').focus;
    return false;
  }
  js_divCarregando('Aguarde, salvando dados...', 'msgBox');
  var oParam                    = new Object();
  oParam.exec                   = "salvarDesistencia";
  oParam.iSolicitacao           = iGlobalSolicitacao;
  oParam.iOrcamento             = iOrcamento;
  oParam.sJustificativa         = $F('justificativa');
  oParam.iTipoDesistencia       = $F('pc54_tipo');
  oParam.dtDataFinal            = $F('datafinal');
  oParam.dtDataInicial          = $F('datainicial');
  oParam.iTipoDesistencia       = $F('pc54_tipo');
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
    
    $('justificativa').value = '';
    $('pc54_tipo').value     = '';
    $('datainicial').value   = '';
    $('datafinal').value     = '';
    $('btnSalvarFornecedores').disabled = true;
    
  } else {
    alert(oRetorno.message.urlDecode());
  }  
}
function js_setAjuda(sTexto,lShow) {

  if (lShow) {
   
    var el    =  $('gridgridItens'); 
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
   x += el.offsetLeft
   y += el.offsetTop;
   $('ajudaItem').innerHTML     = sTexto;
   $('ajudaItem').style.display = '';
   $('ajudaItem').style.top     = y+"px";
   $('ajudaItem').style.left    = x+"px";
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
}
  js_init(); 
</script>