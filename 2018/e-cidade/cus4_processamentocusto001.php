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
 * @author I
 * @revision $Author: dbiuri $
 * @version $Revision: 1.1 $
 */
require("libs/db_stdlib.php");
require("std/db_stdClass.php");
require("libs/db_app.utils.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_custocriteriorateio_classe.php");
include("classes/db_custoplanilhaorigem_classe.php");
require_once("model/custoPlanilha.model.php");
include("dbforms/db_funcoes.php");
$aParamKeys = array(
                    db_getsession("DB_anousu")
                   );
$aParametrosCustos   = db_stdClass::getParametro("parcustos",$aParamKeys);
$iTipoControleCustos = 0; 
$db_opcao            = 1;
$oDaoCustoOrigem     = new cl_custoplanilhaorigem();
if (count($aParametrosCustos) > 0) {
  $iTipoControleCustos = $aParametrosCustos[0]->cc09_tipocontrole;
}
$oRotuloCampo = new rotulocampo();
$oRotuloCampo->label("cc15_anousu");
$oRotuloCampo->label("cc15_mesusu");
?>
<html>
  <head>
  
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, prototype.js, strings.js, widgets/windowAux.widget.js, datagrid.widget.js");
     db_app::load("estilos.css, grid.style.css");
    ?>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <table border="0" cellpadding="0" cellspacing="0">
      <tr> 
        <td width="360" height="18">&nbsp;</td>
        <td width="263">&nbsp;</td>
        <td width="25">&nbsp;</td>
        <td width="140">&nbsp;</td>
      </tr>
    </table>
    <center>
      <table>
        <tr>
          <td>
            <fieldset>
              <legend>
                <b>Processamento dos Custos</b>
              </legend>
              <table>
                <tr>
                  <td>
                    <b>Mês:</b>
                  </td>
                  <td>
                    <?
                      db_input("cc15_mesusu", 10, $Icc15_mesusu, true,"text", $db_opcao);
                    ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <b>Ano:</b>
                  </td>
                  <td>
                    <?
                      db_input("cc15_anousu", 10, $Icc15_anousu, true,"text", $db_opcao);
                    ?>
                  </td>
                </tr> 
                <tr>
                <td colspan="3">
                    <fieldset>
                      <legend><b>Processar:</b></legend>
                      <table>
                        <tr>
                          <td valign='top'>
                           <?
                             $sSqlOrigensCustos = $oDaoCustoOrigem->sql_query(null,"*", "cc14_sequencial");
                             $rsOrigensCustos   = $oDaoCustoOrigem->sql_record($sSqlOrigensCustos);
                             $aCustosOrigem     = db_utils::getColectionByRecord($rsOrigensCustos);
                             $i = 0;
                             
                             foreach ($aCustosOrigem as $oCustoOrigem) {
                              
                               if ($i >= 4) {
                                 echo "</td><td valign='top'>";
                                 $i = 0;
                               }
                               echo "<input class='nivelcusto' type='checkbox' id='c{$oCustoOrigem->cc14_sequencial}'";
                               echo "       value='{$oCustoOrigem->cc14_sequencial}' checked>\n";
                               echo "<label for='c{$oCustoOrigem->cc14_sequencial}'>";
                               echo "{$oCustoOrigem->cc14_descricao}</label><br>\n";
                               $i++; 
                             }
                           ?>
                         </td>
                       <tr>
                     </table>
                   </fieldset>
                  </td>
                </tr> 
              </table>
            </fieldset>
          </td>
        </tr>
        <tr>
          <td colspan="2" style="text-align: center">
            <input type="button" id="btnProcessarPlanilha" value="Processar Planilha">
            <input type="button" id="btnModificarPlanilha" value="Modificar Dados">
          </td>
        <tr>
      </table>
    </center>
  </body>
  <?
  db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
  ?>
  <div style='position:absolute;top: 200px; left:15px;
            border:1px solid black;
            width:600px;
            padding:3px;
            background-color: #FFFFCC;
            display:none;' id='ajudaItem'>

  </div>
</html>
<script>
sUrlRPC = 'cus4_planilhacustos.RPC.php';
function js_processarPlanilha() {
  
  if ($F('cc15_mesusu') == "") {
    
     alert('Informe o mês.');
     $('cc15_mesusu').focus();
     return false
  }
  
  if ($F('cc15_anousu') == "") {
    
     alert('Informe o Ano.');
     $('cc15_anousu').focus();
     return false 
  }
  
  var aNiveisCollection = $$("input.nivelcusto");
  var aNiveisSelecionados = new Array();
  aNiveisCollection.each(function(oInput, iSeq) {
    if (oInput.checked) {
    
      var oNivel = new Object();
      oNivel.nivel = oInput.value;
      aNiveisSelecionados.push(oNivel);
       
    }
  });
  
  js_divCarregando('Aguarde, Processando Planilha',"msgBox");
  var oParam     = new Object();
  oParam.exec    = "processarPlanilha";
  oParam.iMesUsu = $F('cc15_mesusu');
  oParam.iAnoUsu = $F('cc15_anousu');
  oParam.aNiveis = aNiveisSelecionados;
  
  var oAjax     = new Ajax.Request(
                                   sUrlRPC,
                                   {
                                    method: 'post', 
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    onComplete: js_retornoProcessaPlanilha
                                   } 
                                  ); 
}

function js_retornoProcessaPlanilha(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
   alert('Processamento Efetuado com sucesso');
  } else {
    alert(oRetorno.message.urlDecode());
  }
}

function js_openJanelaCustos() {
 
 
  if ($F('cc15_mesusu') == "") {
    
     alert('Informe o mês.');
     $('cc15_mesusu').focus();
     return false
  }
  
  if ($F('cc15_anousu') == "") {
    
     alert('Informe o Ano.');
     $('cc15_anousu').focus();
     return false 
  }
  var iTamanhoJanela = document.width - 30;
  var iAlturaJanela  = 700;
   
  wndCustos    = new windowAux('wndCustos',"Custos do mês "+$F('cc15_mesusu')+"/"+$F('cc15_anousu'), iTamanhoJanela, iAlturaJanela);
  var sJanela  = "<div'><fieldset>";
  sJanela     += "  <b>Níveis:</b>";
  sJanela     += "  <select id='nivel' onchange ='js_getDadosCustos()'>";
  sJanela     += "    <option value='0' selected>Todos</option>";
  sJanela     += "    <option value='1'>Folha</option>";
  sJanela     += "    <option value='2'>Provisoes</option>";
  sJanela     += "    <option value='3'>Consumo Almoxarifado</option>";
  sJanela     += "    <option value='4'>Diárias</option>";
  sJanela     += "    <option value='5'>Serviços PF</option>";
  sJanela     += "    <option value='6'>Serviços PF</option>";
  sJanela     += "    <option value='7'>Outros</option>";
  sJanela     += "  </select>";
  sJanela     += "  <div id='grid' style='overflow:scroll;overflow-y:hidden'></div>";
  sJanela     += " </fieldset>";
  sJanela     += " <input type='button' id='btnSalvarPlanilha' value='Processar Planilha' onclick='js_salvarDados()'>";
  sJanela     += "</div>";
   
  wndCustos.setContent(sJanela);
  wndCustos.allowCloseWithEsc(false);
  oGridCustos = new DBGrid("oGridCustos"); 
  oGridCustos.nameInstace = 'oGridCustos'; 
  oGridCustos.setHeight(500);
  oGridCustos.setCellAlign(new Array("right", "right", "right","right","center","left","left"));
  oGridCustos.setHeader(new Array("Origem", "Valor", "Quantidade","Elemento","Conta","Descricão","Item"));
  oGridCustos.show($('grid'));
  js_getDadosCustos(); 
  wndCustos.show(30,0);
  $('windowwndCustos_btnclose').onclick=function (){wndCustos.destroy()};
   
}
function js_getDadosCustos() {

  var oParam     = new Object();
  oParam.exec    = "getDadosPlanilha";
  oParam.iMesUsu = $F('cc15_mesusu');
  oParam.iAnoUsu = $F('cc15_anousu');
  oParam.iNivel  = $F('nivel');
  js_divCarregando('Aguarde, Pesquisando custos.',"msgBox");
  var oAjax      = new Ajax.Request(
                                   sUrlRPC,
                                   {
                                    method: 'post', 
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    onComplete: js_retornoGetDadosPlanilha
                                   } 
                                  ); 
}

function js_retornoGetDadosPlanilha(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval('('+oAjax.responseText+')');
  oGridCustos.clearAll(true);
  if (oRetorno.status == 1) {
  
    for (var i = 0; i < oRetorno.itens.length; i++ ) {
    
      with (oRetorno.itens[i]) {
      
        var aLinha = new Array();
        aLinha[0]  = origem.urlDecode(); 
        aLinha[1]  = js_formatar(cc17_valor, "f"); 
        aLinha[2]  = js_formatar(cc17_quantidade, "f");
        if (o56_codele  == '' || cc19_automatico == 'f') {
        
          aLinha[3]  = "<a onclick='js_adicionaDesdobramento("+cc17_sequencial+"); return false' href='#'>";
          aLinha[3] += "<span class='desdobramento' sequencial='"+cc17_sequencial+"'";
          if (cc19_automatico == 'f') {
            aLinha[3] += "orcelemento= '"+o56_codele+"' id='descr"+cc17_sequencial+"'>"+o56_elemento.urlDecode()+"</span></a>";
          } else {
            aLinha[3] += "orcelemento= '' id='descr"+cc17_sequencial+"'>0000000000000</span></a>";
          }
          
        } else { 
          aLinha[3]  = o56_elemento.urlDecode();
        }
        aLinha[4]  = cc01_estrutural.urlDecode();
        aLinha[5]  = cc01_descricao.urlDecode();
        aLinha[6]  = material.urlDecode();
        oGridCustos.addRow(aLinha);
        var sStringAjuda = cc14_descricao.urlDecode();
        
        switch(cc17_custoplanilhaorigem) {
          
          case '1':
           
            sStringAjuda = z01_nome.urlDecode()+" Local de Trabalho: "+rh55_descr.urlDecode();
            break;
          
          case '2':
           
            sStringAjuda = z01_nome.urlDecode()+" Local de Trabalho: "+rh55_descr.urlDecode();
            break;
            
         case '3':
           
            sStringAjuda = " Tipo Consumo: "+m81_descr.urlDecode();
            break;     
        }
        
        oGridCustos.aRows[i].aCells[0].sEvents  = "onmouseover='js_setAjuda(\""+sStringAjuda+"\",true)'";
        oGridCustos.aRows[i].aCells[0].sEvents += "onmouseOut='js_setAjuda(null,false)'";
        oGridCustos.aRows[i].aCells[3].sEvents  = "onmouseover='js_setAjuda(\""+o56_descr.urlDecode()+"\",true)'";
        oGridCustos.aRows[i].aCells[3].sEvents += "onmouseOut='js_setAjuda(null,false)'";
         
      }
    }
    oGridCustos.renderRows();
  }
}

function js_adicionaDesdobramento(iConta) {

  iContaSemDesdobramento = iConta;  
  js_pesquisao08_elemento(true);
  
}

function js_pesquisao08_elemento(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('',
                        'db_iframe_orcelemento',
                        'func_orcelemento.php?funcao_js=parent.js_mostraorcelemento1|o56_codele|o56_elemento&analitica=2',
                        'Elementos da Despesa',
                        true);
  }
}

function js_mostraorcelemento1(chave1,chave2) {

  $("descr"+iContaSemDesdobramento).setAttribute('orcelemento', chave1);
  $("descr"+iContaSemDesdobramento).innerHTML   = chave2;
  db_iframe_orcelemento.hide();
  
}

function js_salvarDados() {
   
   
  var aCustosSemElemento = $$('span.desdobramento');
  var aCustosSalvar      = new Array();
  aCustosSemElemento.each(function (oCusto, id) {
     
     if (oCusto.getAttribute('orcelemento') != "") {
     
       var oCustoAdicionar          = new Object();
       oCustoAdicionar.iCodEle      = oCusto.getAttribute('orcelemento');   
       oCustoAdicionar.iCodigoCusto = oCusto.getAttribute('sequencial');
       aCustosSalvar.push(oCustoAdicionar);
          
     }
  });
  
  var oParam     = new Object();
  oParam.exec    = "salvarPlanilha";
  oParam.aCustosSalvar = aCustosSalvar;
  oParam.iMesUsu = $F('cc15_mesusu');
  oParam.iAnoUsu = $F('cc15_anousu');
  js_divCarregando("aguarde, salvado Custos.", 'msgBox');
  var oAjax      = new Ajax.Request(
                                   sUrlRPC,
                                   {
                                    method: 'post', 
                                    parameters: 'json='+Object.toJSON(oParam), 
                                    onComplete: js_retornoSalvarPlanilha
                                   } 
                                  ); 
}

function js_retornoSalvarPlanilha(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
  
    alert('Custos Salvos com sucesso!');
    js_getDadosCustos();
    
  } else {
    alert(oRetorno.message.urlDecode());
  }
  
}

function js_setAjuda(sTexto,lShow) {

  if (lShow) {
  
    el =  $('grid'); 
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
   $('ajudaItem').style.zIndex  = 100000;
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
  
  
}
$('btnProcessarPlanilha').observe("click",js_processarPlanilha);
$('btnModificarPlanilha').observe("click",js_openJanelaCustos);
</script>