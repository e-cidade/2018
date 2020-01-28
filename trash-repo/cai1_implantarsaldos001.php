<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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
 * @author Luiz Marcelo Schmitt
 * @revision $Author: dbiuri $
 * @version $Revision: 1.2 $
 */
require("libs/db_stdlib.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");
include("classes/db_saltes_classe.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, widgets/dbtextFieldData.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js, widgets/dbtextField.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
  <center>
   <form name="form1" method="post">
    <table>
      <tr>
        <td>
          <fieldset>
            <legend>
              <b>Selecionar Contas</b>
            </legend>
            <table>
              <tr>
                <td>
                   <?
                     $aux = new cl_arquivo_auxiliar;
                     $aux->cabecalho = "<strong>Contas Selecionadas</strong>";
                     $aux->codigo = "k13_conta";
                     $aux->descr  = "k13_descr";
                     $aux->nomeobjeto = 'listasaltes';
                     $aux->funcao_js = 'js_mostra';
                     $aux->funcao_js_hide = 'js_mostra1';
                     $aux->sql_exec  = "";
                     $aux->func_arquivo = "func_saltes.php";
                     $aux->nomeiframe = "db_iframe_saltes";
                     $aux->localjan = "";
                     $aux->onclick = "";
                     $aux->db_opcao = 2;
                     $aux->tipo = 2;
                     $aux->top = 0;
                     $aux->linhas = 10;
                     $aux->vwhidth = 200;
                     $aux->funcao_gera_formulario();
                   ?> 
                </td>
              </tr>
            </table>
          </fieldset>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td colspan='2' style='text-align:center'>
           <input type='button' name="pesquisar" id="pesquisar" value='Pesquisar' onclick="return js_pesquisaSaldoContas(true);">
           <input type='button' name="limpar" id="limpar" value='Limpar' onclick="js_limpar();">
        </td>
      </tr>
    </table>
   </form>
  </center>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
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
<script>
var dtDia = '<?=date('d/m/Y', db_getsession("DB_datausu"))?>'; 
function js_limpar(){

  $('k13_conta').value            = '';
  $('k13_descr').value            = '';
  $('listasaltes').options.length = 0;
  
}
function js_pesquisaSaldoContas(lCreateWindow) {
   
   $('pesquisar').disabled = true;
   $('limpar').disabled    = true;
   
   var iItens    = $('listasaltes').options.length;
   var iItensSel = "";
   var sVrg      = "";
   for (i = 0; i < iItens; i++) {
     iItensSel = iItensSel+sVrg+$('listasaltes').options[i].value;
     sVrg     =',';
   }
   lGlobalCreateWindow = lCreateWindow;
   js_divCarregando("Aguarde.. Pesquisando","msgbox");
   var oParam      = new Object();
   oParam.exec     = "pesquisarSaldoContas";
   oParam.itenssel = iItensSel;
   var oAjax       = new Ajax.Request(
                                      "cai4_saltesRPC.php", 
                                      {
                                      method    : 'post', 
                                      parameters: 'json='+js_objectToJson(oParam), 
                                      onComplete: js_retornoPesquisa
                                      }
                                    );
}
function js_retornoPesquisa(oAjax) {
  
  js_removeObj("msgbox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
    if (lGlobalCreateWindow) {
      js_openPesquisaSaldoContas(oRetorno.aItens,oRetorno.aItens.length);
    } else {
      js_preencheGrid(oRetorno.aItens);
    }
  }
}
function js_openPesquisaSaldoContas(aSaldoContas,iRetornoSaldoContas) {

  windowSaldoContas = new windowAux('windowSaldoContas','Implantação de Saldo', document.width - 30);
  windowSaldoContas.allowCloseWithEsc(false);
  var sContent  = "<fieldset>";
      sContent += "  <div id='ctnGridSaldoContas'>";
      sContent += "  </div>";
      sContent += "</fieldset>";
      sContent += "<br>";
      sContent += "<center>";
      sContent += "  <table id='frmSaldoContas'>";
      sContent += "    <tr align='center'>";
      sContent += "      <td>";
      sContent += "        <input type='button' id='btnImplantarSaldo' value='Implantar Saldo' onclick='js_ImplantarSaldo();'>";
      sContent += "      </td>";
      sContent += "    </tr>";
      sContent += "  </table>";
      sContent += "</center>";
  windowSaldoContas.setContent(sContent);
  windowSaldoContas.show(20, 15);

  $('windowwindowSaldoContas_btnclose').onclick= function () {
    windowSaldoContas.destroy();
    $('pesquisar').disabled = false;
    $('limpar').disabled    = false;
  }
  
  /**
   * Adiciona a grid na janela
   */ 
   
  oGridSaldoContas     = new DBGrid('gridSaldoContas');
  oGridSaldoContas.nameInstance = "oGridSaldoContas";
  oGridSaldoContas.setHeight((document.
  body.scrollHeight/2)-50);
  oGridSaldoContas.setCheckbox(0);
  oGridSaldoContas.setCellAlign(new Array("center", "Left", "center", "right","right"));
  oGridSaldoContas.setCellWidth(new Array("10%","55%","11%","12%","12%"));
  oGridSaldoContas.setHeader(new Array('Conta','Descrição','Data Atualização','Saldo Inicial','Saldo Atual'));

  oGridSaldoContas.show($('ctnGridSaldoContas'));
  oGridSaldoContas.clearAll(true);
  
  if (iRetornoSaldoContas == 0) {
    oGridSaldoContas.setStatus('Não foram encontrados Registros');
  } else {
  
    js_preencheGrid(aSaldoContas);
    $('pesquisar').disabled = false;
  }
  
  $('limpar').disabled    = false;
  var sMsg  = 'Selecione as contas que deseja implantar o saldo. ';
      sMsg += 'Informe o saldo inicial e a data de atualização da conta. Após clique em processar.';
  var oMessageBoard = new messageBoard('msg1',
                                       'Implantação de saldos - Contas Bancárias',
                                       sMsg,
                                       $('windowwindowSaldoContas_content')
                                      );
  oMessageBoard.show();
}
//js_comparardata(1,ddtDia, ">")
function js_ImplantarSaldo() {

   var aItens  = oGridSaldoContas.getSelection("object");
   var sMsg    = "Está rotina irá processar os saldos das contas da tesouraria selecionados na lista. \n";
       sMsg   += "Deseja Continuar?";
   
   if (aItens.length == 0) {
   
     alert('Não existem contas selecionadas para processar.');
     return false;
   }
   if (!confirm(sMsg)){
     return false;
   }
   
   $('pesquisar').disabled         = true;
   $('limpar').disabled            = true;
   $('btnImplantarSaldo').disabled = true;
   
   var oParam           = new Object();
   oParam.exec          = "implantarSaldo";
   oParam.aSaldoContas  = new Array();
   
   for (var i = 0; i < aItens.length; i++) {
     
     if (js_comparadata(dtDia,aItens[i].aCells[3].getValue(),'<')) {
     
       var sMsgErro  = 'A conta '+aItens[i].aCells[1].getValue()+' está configurada com a data para ';
           sMsgErro += 'atualização maior que a data atual! \nProcedimento Cancelado.'; 
       $('btnImplantarSaldo').disabled = false;
       alert(sMsgErro);
       return false;
       
     }
     
     var oSaldoContas            = new Object();
         oSaldoContas.iNumconta  = aItens[i].aCells[1].getValue();
         oSaldoContas.dtData     = aItens[i].aCells[3].getValue();
         oSaldoContas.nSaldo     = js_strToFloat(aItens[i].aCells[4].getValue()).valueOf();
         oParam.aSaldoContas.push(oSaldoContas);
       
   }
   js_divCarregando("Aguarde.. Processando ","msgbox");   
   var oAjax = new Ajax.Request(
                                 "cai4_saltesRPC.php", 
                               {
                                 method    : 'post', 
                                 parameters: 'json='+js_objectToJson(oParam), 
                                 onComplete: js_retornoImplantarSaldo
                                }
                               );

}
function js_retornoImplantarSaldo(oAjax) {
  
  js_removeObj("msgbox");
  $('btnImplantarSaldo').disabled  = false;
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oRetorno.status == 1) {
  
    js_pesquisaSaldoContas(false);
  } else {
    alert(oRetorno.message.urlDecode());
  }
}
function js_setAjuda(sTexto,lShow) {

  if (lShow) {
   
    var el =  $('gridgridSaldoContas'); 
    var x  = 0;
    var y  = el.offsetHeight;

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

function js_preencheGrid(aSaldoContas) {
  
  oGridSaldoContas.clearAll(true);
  for (var i = 0; i < aSaldoContas.length; i++) {
  
      with(aSaldoContas[i]) {
      
        var aLinha     = new Array();
            aLinha[0]  = k13_conta;
            aLinha[1]  = k13_descr.urlDecode().substring(0,80);
            var sSaldoInicial = js_formatar(k13_saldo,'f');
            aLinha[2]  = eval("k13_datvlr"+i+"= new DBTextFieldData('k13_datvlr"+i+"','k13_datvlr"+i+"','"+k13_datvlr+"')");
            aLinha[3]  = eval("k13_saldo"+i+" = new DBTextField('k13_saldo"+i+"','k13_saldo"+i+"','"+sSaldoInicial+"')");
            aLinha[3].addStyle("text-align","right");
            aLinha[3].addStyle("height","100%");
            aLinha[3].addStyle("width","100%");
            aLinha[3].addStyle("border","1px solid transparent;");
            aLinha[3].addEvent("onBlur","js_bloqueiaDigitacao(this);");  
            aLinha[3].addEvent("onBlur","k13_saldo"+i+".sValue=this.value;");  
            aLinha[3].addEvent("onFocus","js_liberaDigitacao(this);");  
            aLinha[3].addEvent("onKeyPress","return js_mask(event,\"0-9|.|-\")");  
            aLinha[3].addEvent("onKeyDown","return js_verifica(this,event,false)");
            aLinha[4]  = js_formatar(k13_vlratu,'f');

            oGridSaldoContas.addRow(aLinha, false, false, false);
            oGridSaldoContas.aRows[i].aCells[2].sEvents += "onMouseOver='js_setAjuda(\""+k13_descr.urlDecode()+"\",true)'";
            oGridSaldoContas.aRows[i].aCells[2].sEvents += "onMouseOut='js_setAjuda(null, false)'";

        }
    }
  oGridSaldoContas.renderRows();
}
</script>