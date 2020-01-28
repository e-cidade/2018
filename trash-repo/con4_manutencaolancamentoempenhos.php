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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("e60_codemp");

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, prototype.js, datagrid.widget.js, messageboard.widget.js, dbtextField.widget.js");
     db_app::load("windowAux.widget.js, strings.js,dbtextFieldData.widget.js");
     db_app::load("classes/infoLancamentoContabil.classe.js");
     db_app::load("grid.style.css, estilos.css");
    ?>
    <style>
      .temdesconto {background-color: #D6EDFF}
    </style>
  </head>
  <body bgcolor="#CCCCCC">
  <?
    if (db_getsession("DB_id_usuario") != 1) {
      
      echo "<br><center><br><H2>Essa rotina apenas poder� ser usada pelo usuario dbseller</h2></center>";
    } else {
  ?>  
    <table width="790" border="0" cellpadding="0" cellspacing="0">
      <tr height="40"><td>&nbsp;</td></tr>
    </table>
    <form name='form1'>
      <center>
        <table>
          <tr>
            <td>
               <fieldset>
                  <legend><b>Manuten��o Lan�amento de Empenhos</b></legend>
                  <table>
                    <tr> 
                      <td  align="left" nowrap title="<?=$Te60_numemp?>">
                       <? db_ancora(@$Le60_codemp,"js_pesquisa_empenho(true);",1);  ?>
                      </td>
                      <td  nowrap> 
                        <input name="e60_codemp" id='e60_codemp' readonly title='<?=$Te60_codemp?>' size="12" type='text'  
                               onKeyPress="return js_mascara(event);" >
                      </td>
                    </tr>
                  </table>   
               </fieldset>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="text-align: center;">
               <input type="button" id='btnVisualizarLancamento' value='Visualizar Lan�amentos'>
            </td>
          </tr>  
        </table>
      </center>
    </form>  
  </body>
</html>
<div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:400px;
            text-align: left;
            padding:3px;
            z-index:10000;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

</div>
<script>
sUrlRPC = 'con4_lancamentoscontabeisempenho.RPC.php';
function js_pesquisa_empenho(mostra) {

  if (mostra == true) {
    
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_empempenho', 
                        'func_empempenho.php?funcao_js=parent.js_mostraempenho1|e60_codemp|e60_anousu|e60_numemp',
                        'Pesquisa',
                        true);
                        
    }
}

function js_mostraempenho1(chave1, chave2, chave3) {

  document.form1.e60_codemp.value = chave1+"/"+chave2;
  db_iframe_empempenho.hide();
  js_visualizarEmpenhos(chave3);
  
}


function js_visualizarEmpenhos(iEmpenho) {
   
   $('btnVisualizarLancamento').blur();
   iEmpenho = iEmpenho;
   oWindowLancamentos  = new windowAux('wndLancamentos', 'Lancamentos Cont�beis', '750', '500');
   sContent            = "<div style='text-align:center;padding:2px'>";
   sContent           += "<fieldset style='text-align:center'><legend><b>Lan�amentos</b></legend>";
   sContent           += "<div style='width:100%' id='ctnDataGrid'>";
   sContent           += "</div>";
   sContent           += "</fieldset>";
   sContent           += "<input type='button' accessky='s' id='btnAlterar' value='Alterar Lan�amento' onclick='js_alterarLancamento("+iEmpenho+")'> ";
   sContent           += "<input type='button' id='btnExcluir' value='Excluir Lan�amento' onclick='js_excluirLancamento("+iEmpenho+")'> ";
   sContent           += "<input type='button' id='btnInfo' value='Informa��es Empenho'> ";
   sContent           += "</div>";
   oWindowLancamentos.setContent(sContent);
   oWindowLancamentos.addEvent('keydown', function(event) {
   
     if (event.ctrlKey) {
     
       switch (event.which) {
       
       case 65:
        
         $('btnAlterar').click();
         event.preventDefault();
         event.stopPropagation();
         break;
         
       case 69:
        
         $('btnExcluir').click();
         event.preventDefault();
         event.stopPropagation();
         break;    
       }
     }
   });
   oWindowLancamentos.show(25,0);
   
   $('btnInfo').observe('click', function (){js_JanelaAutomatica("empempenho", iEmpenho)})
   oMessage   = new messageBoard('msgboard1', 
                                 'Manuten��o de Lan�amentos Cont�beis de Empenho - '+$F('e60_codemp'),
                                 'Selecione os itens que deseja alterar',
                                 $("windowwndLancamentos_content"));
   oMessage.show();
   oWindowLancamentos.setShutDownFunction(function (){
     
     oWindowLancamentos.destroy();
     });

    /*
     *Monta a Grid;
     */
     oGridLancamentos = new DBGrid('gridLancamentos');
     oGridLancamentos.nameInstance = 'oGridLancamentos';
     oGridLancamentos.setCheckbox(0);
     oGridLancamentos.setHeight((oWindowLancamentos.getHeight()/2)-30);
     oGridLancamentos.setCellWidth(new Array('5%','20%','12%', '12%',"41%","5%",'5%'));
     oGridLancamentos.setCellAlign(new Array("left","left", "center","right","left", "center", "center"))
     oGridLancamentos.setHeader(new Array('C�digo', "Documento",'Data','Valor', 'Observa��o',"Desconto","OP."));
     oGridLancamentos.aHeaders[6].lDisplayed = false;
     oGridLancamentos.allowSelectColumns(true);
     
     oGridLancamentos.show($('ctnDataGrid'));
     oGridLancamentos.resizeCols();
     js_getLancamentos(iEmpenho);

     

} 

function js_getLancamentos(iEmpenho) {

  
  var oParam     = new Object();
  oParam.exec    = "getLancamentosEmpenho";
  oParam.iNumEmp = iEmpenho;
  var oAjax      = new Ajax.Request(sUrlRPC,
                                {
                                 method: "post",
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoGetLancamentos
                                 });
}


function js_retornoGetLancamentos(oAjax) {

    var oRetorno = eval("("+oAjax.responseText+")");
    oGridLancamentos.clearAll(true);
    if (oRetorno.status == 1) {
    
      if (oRetorno.itens.length == 0) {
       //oGridLancamentos.setStatus('N�o foram encontrados Registros');
      }
      for (var i = 0; i < oRetorno.itens.length; i++) {
        
        var lBloqueia = false;      
        with(oRetorno.itens[i]) {
          
          var aLinha    = new Array();
          aLinha[0]     = codigo; 
          aLinha[1]     = "("+tipo+")"+descricaotipo.urlDecode()
          if (temretencao =='f' && temretencaonota == 'f') {
            aLinha[2]  =  eval("data"+i+"= new DBTextFieldData('data"+i+"','data"+i+"','"+js_formatar(data,'d')+"')");
          } else {
          
            aLinha[2]  =  js_formatar(data,'d');
            lBloqueia  = true;
          }
          aLinha[3]  = js_formatar(valor, 'f');
          aLinha[4]  = observacao.urlDecode().substring(0,50);
          aLinha[5]  = desconto;
          aLinha[6]  = ordempagamento;
          oGridLancamentos.addRow(aLinha, false, lBloqueia);
          if (desconto != '') {
            oGridLancamentos.aRows[i].setClassName('temdesconto');
          }
          if (lBloqueia) {
            oGridLancamentos.aRows[i].setClassName('disabled');
          }
          oGridLancamentos.aRows[i].aCells[1].sEvents +="ondblclick='js_infoLancamento("+codigo+")'";
           if (temretencao == 't' || temretencaonota == 't') {
            
            var sMsgLinha = "Lan�amento possui lan�amento de reten��es.<br> N�o � possivel fazer manuten��o nesse Registro.";
            oGridLancamentos.aRows[i].sEvents +="onmouseover='js_ajuda(\""+sMsgLinha+"\", true)'";
            oGridLancamentos.aRows[i].sEvents +="onmouseout='js_ajuda(\"\", false)'";
          }
        }
      }
      oGridLancamentos.renderRows();
    }
    if (oRetorno.aviso != "") {
    
      oMessage.setHelp("<span style='color:red'>"+oRetorno.aviso.urlDecode()+"</span>");
      alert(oRetorno.aviso.urlDecode());
      
    }
    
}

function js_alterarLancamento(iEmpenho) {

  /**
   * Verificamos quantos lan�amentos o usu�rio selecionou. 
   * � permitido apenas escolher um lan�amento por vez (controle de Seguran�a)
   */
   
  var sMsgConfirma  = "Voc� est� executando a altera��o de data de um lan�amento cont�bil e deve estar ciente ";
  sMsgConfirma     += "de que isto implicar� em altera��o em todos os registros a ele relacionados, como por exemplo,";
  sMsgConfirma     += "emiss�o de empenho, notas, ordens de pagamento, reten��es de tributos e";
  sMsgConfirma     += "autentica��es na Tesouraria. Voc� realmente tem certeza de que deseja confirmar a opera��o?";
   if (!confirm(sMsgConfirma)) {
    return false;
  }
  var aLancamentosSelecionados = oGridLancamentos.getSelection("object");
  if (aLancamentosSelecionados.length > 1) {
  
    alert('Para a manuten��o dos lan�amentos, � permitido selecionar apenas um lan�amento por vez.');
    return false;
  }
  
  if (aLancamentosSelecionados.length == 0) {
  
    alert('Nenhum lan�amento selecionado.');
    return false;
  }
  var aLancamento       = aLancamentosSelecionados[0];
  var iCodigoLancamento = aLancamento.aCells[0].getValue();
  var iCodigoDesconto   = aLancamento.aCells[6].getValue();
  var oParam      = new Object();
  oParam.exec     = 'alterarLancamento';
  oParam.iNumEmp  = iEmpenho;
  oParam.dtData   = aLancamento.aCells[3].getValue();  
  oParam.iCodigo  = aLancamento.aCells[0].getValue();
  oParam.iCodigoDesconto = '';
  if (iCodigoDesconto.trim() != "") {
    
    var sOutroLancamento  = 'O(s) Lan�amento(s) : ';
    aOutrosLancamentos = oGridLancamentos.aRows;
    sVirgula = "";
    for (var iLanc = 0; iLanc < aOutrosLancamentos.length; iLanc++) {
      
      with(aOutrosLancamentos[iLanc]) {
        
        /**
         *verificamos se o lancamento � o mesmo
         */
        if (aCells[6].getValue() == iCodigoDesconto) {
      
          sOutroLancamento += sVirgula+aCells[1].getValue(); 
          sVirgula = ", ";
        }
      }
    }
    
    oParam.exec     = 'alterarLancamentoComDesconto';
    sOutroLancamento += " ira(am) ser(am) alterado(s), pois o lan�amento selecionado ";
    sOutroLancamento += "originou(aram)-se de um desconto na Nota fiscal.\nConfirma a opera��o?";
    if (!confirm(sOutroLancamento)) {
      return false; 
    }
    oParam.iCodigoDesconto = iCodigoDesconto; 
  }
  
  //return false;   
  js_divCarregando('Aguarde, Alterando data','msgBox');
  $('btnAlterar').disabled = true;
  $('btnExcluir').disabled = true;
  $('btnAlterar').blur();
  //return false;
  
  var oAjax       = new Ajax.Request(sUrlRPC,
                                {
                                 method: "post",
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoAlterarLancamento
                                 });
}

function js_retornoAlterarLancamento(oAjax) {
  
  js_removeObj('msgBox');
  $('btnAlterar').disabled = false;
  $('btnExcluir').disabled = false;
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
     alert(oRetorno.message.urlDecode().replace(/\\n/g,'\n'));
  } else {
  
    js_getLancamentos(oRetorno.iNumEmp);
    alert('Lan�amento Alterado com sucesso!');
    
    
  }  
}


function js_excluirLancamento(iEmpenho) {
 
  /**
   * Verificamos quantos lan�amentos o usu�rio selecionou. 
   * � permitido apenas escolher um lan�amento por vez (controle de Seguran�a)
   */
  var aLancamentosSelecionados = oGridLancamentos.getSelection("object");
  if (aLancamentosSelecionados.length > 1) {
  
    alert('Para a exclus�o dos lan�amentos, � permitido selecionar apenas um lan�amento por vez.');
    return false;
  }
  
  if (aLancamentosSelecionados.length == 0) {
  
    alert('Nenhum lan�amento selecionado.');
    return false;
  }
  
  var sMsgConfirma = "Voc� est� executando a exclus�o de um lan�amento cont�bil e deve estar ciente de que isto ";
  sMsgConfirma    += "implicar� em remo��o de todos os registros a ele relacionados, como por exemplo, ";
  sMsgConfirma    += "emiss�o de empenho, notas, ordens de pagamento, reten��es de tributos e ";
  sMsgConfirma    += "autentica��es na Tesouraria.\nVoc� realmente tem certeza de que deseja confirmar a opera��o?";
  if (!confirm(sMsgConfirma)) {
    return false;
  }
  
  var aLancamento       = aLancamentosSelecionados[0];
  var iCodigoLancamento = aLancamento.aCells[0].getValue();
  var iCodigoDesconto   = aLancamento.aCells[6].getValue();
  
  var oParam             = new Object();
  oParam.exec            = 'excluirLancamento';
  oParam.iNumEmp         = iEmpenho;
  oParam.iCodigo         = aLancamento.aCells[0].getValue();
  oParam.iCodigoDesconto = '';
  if (iCodigoDesconto.trim() != "") {
    
    var sOutroLancamento  = 'O(s) Lan�amento(s) : ';
    aOutrosLancamentos = oGridLancamentos.aRows;
    sVirgula = "";
    for (var iLanc = 0; iLanc < aOutrosLancamentos.length; iLanc++) {
      
      with(aOutrosLancamentos[iLanc]) {
        
        /**
         *verificamos se o lancamento � o mesmo
         */
        if (aCells[6].getValue() == iCodigoDesconto) {
      
          sOutroLancamento += sVirgula+aCells[1].getValue();; 
          sVirgula = ", ";
        }
      }
    }
    
    oParam.exec       = 'excluirLancamentoComDesconto';
    sOutroLancamento += " ira(am) ser(am) excluido(s), pois o lan�amento selecionado "; 
    sOutroLancamento += "originou(aram)-se de um desconto na Nota fiscal.\nConfirma a opera��o?";
    if (!confirm(sOutroLancamento)) {
      return false; 
    }
    oParam.iCodigoDesconto = iCodigoDesconto; 
  }
  js_divCarregando('Aguarde, excluindo lan�amento','msgBox');
  $('btnAlterar').disabled = true;
  $('btnExcluir').disabled = true;
  $('btnExcluir').blur();
  //return false;
  
  var oAjax       = new Ajax.Request(sUrlRPC,
                                {
                                 method: "post",
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoExcluirLancamento
                                 });
}

function js_retornoExcluirLancamento(oAjax) {
  
  js_removeObj('msgBox');
  $('btnAlterar').disabled = false;
  $('btnExcluir').disabled = false;
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
  
    alert(oRetorno.message.urlDecode().replace(/\\n/g,'\n'));
  } else {
  
    alert('Lancamento excluido com sucesso!');
    js_getLancamentos(oRetorno.iNumEmp);
  }
}

function js_infoLancamento(iLancamento) {

  var oLancamentoInfo = new infoLancamentoContabil(iLancamento, oWindowLancamentos);
}
function js_ajuda(sTexto,lShow) {

  if (lShow) {
  
    el =  $('gridgridLancamentos'); 
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
$('btnVisualizarLancamento').observe("click", function (){js_pesquisa_empenho(true)});
js_pesquisa_empenho(true);
</script>
<?
  }
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>