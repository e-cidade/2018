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

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("o56_elemento");
$clrotulo->label("e69_numero");
$clorctiporec->rotulo->label();
$clempempenho->rotulo->label();
$db_opcao_inf = 1;
?>
<center>
<form name='frmAnularEmpenho' id='frmAnularEmpenho' action="" method="POST">
<table width='80%' cellspacing='0' style='padding:0px' border='0' style='empty-cells:show'>
<tr><td  style='padding:0px' valign="top" height='100%'>
 <fieldset><legend><b>&nbsp;Empenho&nbsp;</b></legend>
    <table>
          <tr>
            <td><?=db_ancora($Le60_codemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_codemp', 13, $Ie60_codemp, true, 'text', 3)?> </td>
            <td width='20'><?=db_ancora($Le60_numemp,"js_JanelaAutomatica('empempenho',\$F('e60_numemp'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numemp', 13, $Ie60_numemp, true, 'text', 3,"")?> </td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_numcgm,"js_JanelaAutomatica('cgm',\$F('e60_numcgm'))",$db_opcao_inf)?></td>
            <td><? db_input('e60_numcgm', 13, $Ie60_numcgm, true, 'text', 3); ?> </td>
            <td colspan=3><? db_input('z01_nome', 52, $Iz01_nome, true, 'text', 3, "");?></td>
          </tr>
          <tr>
            <td><?=db_ancora($Le60_coddot,"js_JanelaAutomatica('orcdotacao',\$F('e60_coddot'),'".@$e60_anousu."')",$db_opcao_inf)?></td>
            <td nowrap ><? db_input('e60_coddot', 13, $Ie60_coddot, true, 'text', 3); ?></td>
            <td width="20"><?=db_ancora($Lo15_codigo,"",3)?></td>
            <td nowrap><? db_input('o15_codigo', 5, $Io15_codigo, true, 'text', 3); db_input('o15_descr', 33, $Io15_descr, true, 'text', 3)?></td>
          </tr>
          <tr>
                <td nowrap>&nbsp;</td>
                <td colspan='4'>
                 <input type='checkbox' id='marcamig' >
                 <label for='marcamig'>Marcar como Migrado</label>
                </td> 
          </tr>
          <tr>
             <td colspan='4' nowrap>
             <fieldset><legend><b>Mostrar Ordens</b></legend>
               <table cellspacing=0 cellpadding=0>
                <tr>
                <td nowrap>
                 <input type='checkbox' id='mostraMig'  onclick="js_filter(this,'comitem');" checked>
                 <label for='mostraMig'>Ordens Migradas</label>
                </td> 
                <td nowrap>
                 <input type='checkbox' id='mostraNota' onclick="js_filter(this,'comnotasemitem')" checked >
                 <label for='mostraNota'>Com nota sem item</label>
                </td> 
                <td nowrap>
                 <input type='checkbox' id='mostraSem'  onclick="js_filter(this,'normal')" checked >
                 <label for='mostraSem'>Sem nota</label>
                </td> 
                <td nowrap>
                 <input type='checkbox' id='mostraCom'  onclick="js_filter(this,'comordemnota')" checked >
                 <label for='mostraCom'>Com nota e ordem de compra</label>
                </td> 
                </tr>
                <tr>
                  <td>
                  <input type='checkbox' id='mostraAnul'  onclick="js_filter2(this,'vlranu')" >
                  <label for='mostraAnul'>Anuladas</label>
                  </td>
                </tr>
               </table>  
             </fieldset>
            </td>
          </tr>
          </table>
      </fieldset>
     </td>
     <td valign='top' style='padding:0px'>
    <fieldset ><legend><b>&nbsp;Valores do Empenho&nbsp;</b></legend>
    <table style="width:200px;height:100%" >
          <tr><td nowrap><?=@$Le60_vlremp?></td><td align=right><? db_input('e60_vlremp', 12, $Ie60_vlremp, true, 'text', 3, '','','','text-align:right')?></td></tr>  
          <tr><td nowrap><?=@$Le60_vlranu?></td><td align=right><? db_input('e60_vlranu', 12, $Ie60_vlranu, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlrliq?></td><td align=right><? db_input('e60_vlrliq', 12, $Ie60_vlrliq, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr><td nowrap><?=@$Le60_vlrpag?></td><td align=right><? db_input('e60_vlrpag', 12, $Ie60_vlrpag, true, 'text', 3, '','','','text-align:right')?></td></tr>
          <tr>
            <td colspan='2' style='border-top:1px outset white'>
             <input type='button' id='btnLegenda' value='Legenda' onclick='js_toggleLegend(this,event)'>
            </td>
          </tr>  
       </table>
       </fieldset>
     </td>
     </tr>
     <tr>
     <td colspan='2' style='padding:0px;'>
     <fieldset><legend><b>&nbsp;Ordens de Pagamento&nbsp;</b></legend>
         
        <table id='dbgridordens'  cellspacing=0 cellpadding=0 width='100%' style='border:2px inset white'>
          <tr>
            <th class='table_header'><input type='checkbox'  style='display:none'
                id='mtodosnotas' onclick='js_marca()'>
           	<a onclick='js_marca("mtodosnotas","chkmarca","linha")' style='cursor:pointer'>M</a></b></th>
            <th class='table_header'>Código da Ordem</th>
            <th class='table_header'>Credor</th>
            <th class='table_header'>Data da Nota</th>
            <th class='table_header'>Valor</th>
            <th class='table_header'>Anulado</th>
            <th class='table_header'>Pago</th>
            <th class='table_header'>Nota</th>
            <th class='table_header' width='18px'>&nbsp;</td>
          </tr>
          <tbody id='dados' style='height:80;width:100%;overflow:scroll;overflow-x:hidden;background-color:white'>
          </tbody>
        </table>
        </fieldset>
        </td>
        </tr>
        <tr>
          <td colspan='2'>
            <fieldset><legend><b>Itens do Empenho</b></legend> 
            <table id='datagrid2' style='border:2px inset white;backgorund-color:white;' width='100%' cellspacing='0' cellpadding=0>
              <tr> 
                       <td class='table_header' nowrap style="width:17px">
                       <input type='checkbox'  style='display:none'
                        id='mtodositens' onclick='js_marca()'>
           	             <a onclick='js_marca("mtodositens", "chkmarcaItens","linhaItem")'
                          style='cursor:pointer' nowrap>M</a></b></td>
                       <td class='table_header' nowrap>Sequência</td>
                       <td class='table_header' nowrap>Item</td>
                       <td class='table_header' nowrap>Vlr Unitário</td>
                       <td class='table_header' nowrap>Saldo</td>
                       <td class='table_header' nowrap>Saldo Valor</td>
                       <td class='table_header' nowrap>Quantidade</td>
                       <td class='table_header' nowrap>Valor Total</td>
                       <td class='table_header' id='grid2Options' style='width:17px'>
                       &nbsp;
                   </td>
                   </tr>    
               <tbody id='dadosItens' style='height:80;width:100%;overflow:scroll;overflow-x:hidden;background-color:white'>
               <tr>
                 <td colspan='10'>&nbsp;</td>
               </tr>  
               </tbody>
               <tfoot>
                  <tr>
                    <td colspan='5' style='text-align:right;padding-top:0px' class='table_footer'>
                    <span ><b>Total Nota:</b></span></td>
                    <td class='table_footer'><span style='padding:8px' id='totalnota'>0.00</span></td>
                    <td class='table_footer'><span style='padding:8px' ><b>lançado:</b></span></td>
                    <td class='table_footer'><span style='padding:8px' id='totalizador'>0.00</span></td>
                    <td class='table_footer'><span >&nbsp;</td>
                   </tr>
               </tfoot>
           </table>
        </fieldset>
      </td>
   </tr>
  </td> 
</tr>
</table>  
<input name="gerarnota" type="button" id="gerarnota" value="Gerar Nota" onclick="return js_gerarNota()">
<input name="geraritensnota" type="button" id="geraritensnota" value="Gerar Itens da Nota" onclick="return js_gerarNotaItens()">
<input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >
</form>  
</center>
<div id='legenda' style='display:none;width:250px;position:absolute;border:1px outset white'>
  <span style="display:block; background-color:#d1f07c">Ordem Migrada</span>
  <span style="display:block; background-color:white">Ordem sem Nota</span>
  <span style="display:block; background-color:#FFCCCC">Ordem com Nota, sem itens</span>
  <span style="display:block; background-color:#FFFFCC">Ordem com Nota, com OC, sem itens</span>
</div>  
<script>
function js_pesquisa(){
     
 js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_preenchepesquisa|e60_numemp','Pesquisa',true);
 js_limpa();

}
function js_preenchepesquisa(chave) {

  db_iframe_empempenho.hide();
  js_consultaEmpenho(chave);
}
function js_consultaEmpenho(iEmpenho){
   
   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"exec":"getDadosMigra","iNumEmp":"'+iEmpenho+'"}';
   $('dados').innerHTML       = '';
   $('dadosItens').innerHTML  = '';
   $('totalizador').innerHTML = '0,00';
   $('totalnota').innerHTML   = '0,00';
   //$('pesquisar').disabled = true;
   var url     = 'emp4_migracaoNotasRPC.php';
   var oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_saida
                              }
                             );

}

function js_saida(oAjax) {

  var obj = eval("(" + oAjax.responseText + ")");
  if (obj.status && obj.status == 2){
    
     js_removeObj("msgBox");
     alert(obj.sMensagem.urlDecode());
     return false ;
  }
  $('e60_codemp').value = obj.e60_codemp+"/"+obj.e60_anousu;
  $('e60_numemp').value = obj.e60_numemp.urlDecode();
  $('e60_coddot').value = obj.e60_coddot.urlDecode();
  $('e60_numcgm').value = obj.e60_numcgm.urlDecode();
  $('z01_nome').value   = obj.z01_nome.urlDecode();
  $('o15_codigo').value = obj.o58_codigo.urlDecode();
  $('o15_descr').value  = obj.o15_descr.urlDecode();
  $('e60_vlremp').value = js_formatar(obj.e60_vlremp,'f',2);
  $('e60_vlranu').value = js_formatar(obj.e60_vlranu,'f',2);
  $('e60_vlrpag').value = js_formatar(obj.e60_vlrpag,'f',2);
  $('e60_vlrliq').value = js_formatar(obj.e60_vlrliq,'f',2);
  saida                 = '';
  $('dados').innerHTML  = '';
  var iErros    = new Number(0);
  var lDisabled = false;
  if (obj.aOrdensPagamento) {
    for (iInd = 0; iInd < obj.aOrdensPagamento.length; iInd++){
     
      sClassName  = 'normal';
      lHabilitado = '';
      display     = '';
      with (obj.aOrdensPagamento[iInd]) {
        
        //a notafiscal dessa nota já tem itens;nao pode ser migrada.
        if (notatemitem == 1) {

          sClassName  = 'comitem';   
          lHabilitado = ' disabled ';
          if ($('mostraMig').checked == false) {
            display = 'none';
          }
        } else if (e69_codnota != null && temordem == 1) {

          sClassName  = 'comordemnota';   
          if ($('mostraNota').checked == false) {
            display = 'none';
          }

        } else if (e69_codnota != '' && notatemitem == 0) {
          if ($('mostraSem').checked == false) {
            display = 'none';
          }
          sClassName  = 'comnotasemitem';   
        }
        var nValorAnu  = new Number(e53_vlranu); 
        var nValorNota = new Number(e53_valor); 
        if ((nValorNota - nValorAnu) == 0  && $('mostraAnul').checked == false) {
          display = "none";
        }
        saida += "<tr id='linhachk"+e50_codord+"' class='"+sClassName+"' style='display:"+display+";height:1em'>";
        saida += "  <td class='linhagrid'>";
        saida += "     <input type='checkbox' id='chk"+e50_codord+"'";
        saida += "      onclick=\"js_marcaLinha(this,'linha');js_setValorNota(this)\""; 
        saida += "      value='"+e50_codord+"' class='chkmarca' "+lHabilitado+"  style='height:12px'>";      
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='e50_codord"+e50_codord+"' style='text-align:right'>";
        saida +=       e50_codord;
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='z01_nome"+e50_codord+"' style='text-align:left'>";
        saida +=      z01_nome.urlDecode();
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='e50_data"+e50_codord+"'>";
        saida +=       js_formatar(e50_data,"d");
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='e53_valor"+e50_codord+"' style='text-align:right'>";
        saida +=       js_formatar(e53_valor,'f',2);
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='e53_vlranu"+e50_codord+"' style='text-align:right'>";
        saida +=       js_formatar(e53_vlranu,'f',2);
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='e53_vlrpag"+e50_codord+"' style='text-align:right'>";
        saida +=       js_formatar(e53_vlrpag,'f',2);
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='e69_numero"+e50_codord+"' style='text-align:center'>";
        saida += "     &nbsp;<a href='' onclick='js_consultaNota("+e69_codnota+");return false'><b>";
        saida +=      e69_numero.urlDecode(); 
        saida += "    </b><a/>";
        saida += "     <span id='e69_codnota"+e50_codord+"' style='display:none'>"+e69_codnota+"</span>";
        saida += "     <span id='tipoordem"+e50_codord+"' style='display:none'>"+sClassName+"</span>";
        saida += "  </td>";
        saida += "</tr>";
      
      }
    }
    saida += "<tr style='height:auto'><td colspan='8'>&nbsp;</td></tr>";
    $('dados').innerHTML    = saida;
  }
  if (obj.aItens) {
    
    var saida = '';
    for (var iInd = 0; iInd < obj.aItens.length; iInd++) {
      
      with (obj.aItens[iInd]) {
        
        sDisabled  = '';
        sClassName = 'normal';
        sLibera    = '';
        lFraciona  = pc01_fraciona == 'f'?false:true;
        lServico   = pc01_servico == 'f'?false:true;
        if (lServico) {
          sLibera = " disabled ";
        }
        saldovalor = new Number(saldovalor);
        saida += "<tr class='"+sClassName+"' style=';height:1em' id='linhaItemchkmarca"+e62_sequencial+"'>";
        saida += "  <td class='linhagrid' style='text-align:center;' >";
        saida += "     <input type='hidden' id='e62_sequencial"+e62_sequencial+"'";
        saida += "            value='"+e62_sequencial+"'>";
        saida += "     <input type='checkbox' onclick=\"js_marcaLinha(this,'linhaItem')\"";
        saida += "            class='chkmarcaItens' name='chkItem"+e62_sequencial+"'";
        saida += "            id='chkmarca"+e62_sequencial+"'"+sDisabled;
        saida += "            value='"+e62_sequencial+"' style='height:12px'>";
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='e62_sequen"+e62_sequencial+"' style='text-align:right'>";
        saida +=      e62_sequen;
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:left'>";
        saida +=     pc01_descrmater.urlDecode();
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='vlruni"+e62_sequencial+"'";
        saida += "      style='text-align:right'>";
        saida +=       e62_vlrun.replace(".", ",");
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='saldo"+e62_sequencial+"'";
        saida += "      style='text-align:right'>";
        saida +=       js_formatar(saldo,'f');
        saida += "  </td>";
        saida += "  <td class='linhagrid' id='saldovlr"+e62_sequencial+"'";
        saida += "      style='text-align:right'>";
        saida +=      js_formatar(saldovalor,'f');
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:center;'>";
        saida += "    <input type='text' name='qtdesol"+e62_sequencial+"'  "+sLibera+" id='qtdesol"+e62_sequencial+"'";
        saida += "           value='' style='text-align:right; width:100%'";
        saida += "           onblur='js_calculaValor("+e62_sequencial+",1,"+lServico+");'";
        saida += "           onkeypress='return js_validaFracionamento(event,"+lFraciona+",this)'>";
        saida += "  </td>";
        saida += "  <td class='linhagrid' style='text-align:center;'>";
        saida += "    <input type='text'  style='text-align:right;width:100%' name='vlrtot"+e62_sequencial+"'";
        saida += "           id='vlrtot"+e62_sequencial+"'";
        saida += "           value=''  class='valores'";
        saida += "    onblur='js_calculaValor("+e62_sequencial+",2,"+lServico+");' onkeypress='return js_teclas(event)'>";
        saida += "   </td>";
        saida += "</tr>";
        
        
      }
      lDisabled = false;  
    }
    saida += "<tr style='height:auto'><td colspan=8>&nbsp;</td></tr>";
    $('dadosItens').innerHTML = saida; 
  }
  js_removeObj("msgBox");
}

/**
 *gera a nota fiscal para a ordem de pagamento escolhida.
 */
function js_gerarNota() {

  //so podemos ter uma ordem de pagamento selecionada.
  var iTotItens   = new Number(0);
  var nTotalOrdem = new Number(0);
  var sJsonItens  = '';
  var nValorTotal = new Number(0);
  var aOrdens = js_getElementbyClass(document.frmAnularEmpenho, 'chkmarca',"checked==true");
  if (aOrdens.length != 1 ) {

    alert('Número de Ordens inválido.');
    return false;

  }
  for (var iItens = 0; iItens < aOrdens.length; iItens++) {
    if (aOrdens[iItens].checked) {

      iCodOrd         = aOrdens[iItens].value;
      dtOrdem         = $('e50_data'+iCodOrd).innerHTML;
      var nvalorOrdem = js_strToFloat($("e53_valor"+iCodOrd).innerHTML);
      var nTotalAnul  = js_strToFloat($("e53_vlranu"+iCodOrd).innerHTML);
      var nTotalOrdem = new Number(nvalorOrdem - nTotalAnul);

    }
  }

  /*
   * verificamos se o usuário marcou ao menos um item para compor a nota.
   * o total dos itens deve ser o mesmo valor da ordem.
   */
  
   var aItens = js_getElementbyClass(frmAnularEmpenho, 'chkmarcaItens',"checked==true");
   if (aItens.length > 0) {
     
     sV = '';
     for (iItens = 0; iItens < aItens.length; iItens++) {
       
       with (aItens[iItens]) {

         nValorItem   = new Number($F('vlrtot'+value));
         nQtdeItem    = new Number($F('qtdesol'+value));
         sSequen      = $('e62_sequen'+value).innerHTML;
         nValorUni    = new Number(nValorItem/nQtdeItem); 
         nValorTotal += nValorItem;
         sJsonItens  += sV+"{'e62_sequencial':"+value+",'vlrtot':"+nValorItem+",'quantidade':"+nQtdeItem;  
         sJsonItens  += ",'sequen':"+sSequen+",'vlruni':"+nValorUni+"}"; 
         sV           = ", ";
       }
     }

   } else {

     alert("Selecione ao menos um item.");
     return false;

   }

  /*
   * verificamos se o total dos itens Corresponde ao total marcado da ordem   
   * O valor total dos itens e da ordem deve sem iguais.
   *
   */

   if (nValorTotal.toFixed(4) == nTotalOrdem.toFixed(4)) {

      var lMigrar = false;
      if ($('marcamig').checked) {
        var lMigrar = true;
      }
      js_divCarregando("Aguarde, efetuando migração","msgBox");
      sJson  = '{"exec":"gerarNota","iOrdem":'+iCodOrd+',"nTotalOrdem":'+nTotalOrdem+',"iNumEmp":'+$F('e60_numemp')+',';
      sJson += '"dtOrdem":"'+dtOrdem+'","aItens":['+sJsonItens+'],"lMigrar":'+lMigrar+'}';
      var url     = 'emp4_migracaoNotasRPC.php';
      var oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+sJson, 
                               onComplete: js_saidaNota
                              }
                             );


   } else {

     alert('Os valores da ordem e itens devem ser iguais.');

   }
  
}

function js_saidaNota(oAjax) {

  js_removeObj("msgBox");
  oRetorno =  eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 1) {
    
    alert(oRetorno.sMensagem.urlDecode());
    js_consultaEmpenho($F('e60_numemp'));
  } else {
    alert(oRetorno.sMensagem.urlDecode());
  }
}

function js_gerarNotaItens() {

  //so podemos ter uma ordem de pagamento selecionada.
  var iTotItens   = new Number(0);
  var nTotalOrdem = new Number(0);
  var sJsonItens  = '';
  var nValorTotal = new Number(0);
  var aOrdens = js_getElementbyClass(document.frmAnularEmpenho, 'chkmarca',"checked==true");
  if (aOrdens.length != 1 ) {

    alert('Número de Ordens inválido.');
    return false;

  }
  for (var iItens = 0; iItens < aOrdens.length; iItens++) {
    if (aOrdens[iItens].checked) {

      iCodOrd         = aOrdens[iItens].value;
      dtOrdem         = $('e50_data'+iCodOrd).innerHTML;
      var nvalorOrdem = js_strToFloat($("e53_valor"+iCodOrd).innerHTML);
      var nTotalAnul  = js_strToFloat($("e53_vlranu"+iCodOrd).innerHTML);
      var nTotalOrdem = new Number(nvalorOrdem - nTotalAnul);
    }
  }

  /*
   * verificamos se o usuário marcou ao menos um item para compor a nota.
   * o total dos itens deve ser o mesmo valor da ordem.
   */
  
   var aItens = js_getElementbyClass(frmAnularEmpenho, 'chkmarcaItens',"checked==true");
   if (aItens.length > 0) {
     
     sV = '';
     for (iItens = 0; iItens < aItens.length; iItens++) {
       
       with (aItens[iItens]) {

         nValorItem   = new Number($F('vlrtot'+value));
         nQtdeItem    = new Number($F('qtdesol'+value));
         sSequen      = $('e62_sequen'+value).innerHTML;
         nValorUni    = new Number(nValorItem/nQtdeItem); 
         nValorTotal += nValorItem.toFixed(2);
         nValorTotal =  nValorTotal.toFixed(2);
         sJsonItens  += sV+"{'e62_sequencial':"+value+",'vlrtot':"+nValorItem+",'quantidade':"+nQtdeItem;  
         sJsonItens  += ",'sequen':"+sSequen+",'vlruni':"+nValorUni+"}"; 
         sV           = ", ";
       }
     }

   } else {

     alert("Selecione ao menos um item.");
     return false;

   }

  /*
   * verificamos se o total dos itens Corresponde ao total marcado da ordem   
   * O valor total dos itens e da ordem deve sem iguais.
   *
   */
    if (nValorTotal.toFixed(2) == nTotalOrdem.toFixed(2)) {

      if ($('marcamig').checked) {
        var lMigrar = true;
      }
      js_divCarregando("Aguarde, efetuando migração","msgBox");
      sTipo       = $('tipoordem'+iCodOrd).innerHTML;   
      iCodNota    = $('e69_codnota'+iCodOrd).innerHTML;
      var sJson   = '{"exec":"gerarItensNota","iOrdem":'+iCodOrd+',"nTotalOrdem":'+nTotalOrdem+',"iNumEmp":'+$F('e60_numemp')+',';
      sJson      += '"dtOrdem":"'+dtOrdem+'","aItens":['+sJsonItens+'],"sTipo":"'+sTipo+'","iCodNota":'+iCodNota+',"lMigrar":'+lMigrar+'}';
      var url     = 'emp4_migracaoNotasRPC.php';
      var oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+sJson, 
                               onComplete: js_saidaNota
                              }
                             );


   } else {

     alert('Os valores da ordem e itens devem ser iguais.');
    

   }
  
}

function js_marca(idObjeto, sClasse, sLinha){
  
	 obj = document.getElementById(idObjeto);
	 if (obj.checked){
		 obj.checked = false;
	 }else{
		 obj.checked = true;
	 }
   itens = js_getElementbyClass(frmAnularEmpenho, sClasse);
	 for (i = 0;i < itens.length;i++){
     
     if (itens[i].disabled == false){
        if (obj.checked == true){
	
          itens[i].checked=true;
          js_marcaLinha(itens[i],sLinha);
          
       }else{
         
					itens[i].checked=false;
          js_marcaLinha(itens[i],sLinha);
          
			 }
     }
	 }
}

function js_marcaLinha(obj, linha) {
 
  if (obj.checked) {
    $(linha+obj.id).className='marcado';
  } else {
    if ($("tipoordem"+obj.value)){
      $(linha+obj.id).className= $("tipoordem"+obj.value).innerHTML;
    } else {
      $(linha+obj.id).className= "normal";
    }
  }
}
//controle dos valores digitados no empenho.
function js_calculaValor(id,tipo,servico){

   nVlrUni        = js_strToFloat($('vlruni'+id).innerHTML); 
   nQtde          = $F('qtdesol'+id);
   nVlrTotal      = $F('vlrtot'+id);
   //consideramos como saldo valido os saldos do empenho menos o saldo solicitado.
   iSaldo         = (js_strToFloat($('saldo'+id).innerHTML));
   iSaldovlr      = (js_strToFloat($('saldovlr'+id).innerHTML));
   if (tipo == 1){
      nTotal = new Number (nVlrUni*nQtde); 
      if ((nQtde <= iSaldo)){
        if (nTotal > 0){
           $('vlrtot'+id).value    = js_round(nTotal,2);
           if ($('chkmarca'+id).checked == false ){
              $('chkmarca'+id).click();
          }
        }
      }else{
//        alert("Valor total maior que o saldo restante.");
      }
   }else if(tipo == 2) {
      nTotal = (nVlrTotal/nVlrUni); 
      if ((nVlrTotal <= iSaldovlr)) {
        if (!servico) {
         $('qtdesol'+id).value = nTotal.toFixed(2);
        } else {
         $('qtdesol'+id).value = 1;

        }
        if ($('chkmarca'+id).checked == false ) {
           $('chkmarca'+id).click();
          }
       } else {

  //        alert("Valor total maior que o saldo restante.");

       }
     } else {
       if ((nVlrTotal > iSaldovlr)) {
         
    //      alert("Valor total maior que o saldo restante.");
          
       }
     }
   setTotal(); 
}

function setTotal(){

   aListaValores = js_getElementbyClass(frmAnularEmpenho,'valores');
    var nTotal = 0;
    for (var i = 0; i < aListaValores.length; i++){
       
       nTotal += new Number(aListaValores[i].value);

    }
    $('totalizador').innerHTML = js_formatar(nTotal,'f');
}

function js_consultaNota(iCodNota) {
  js_OpenJanelaIframe('top.corpo', 'db_iframe_nota', 'emp2_consultanotas002.php?e69_codnota='+iCodNota, 'Pesquisa Dados da Nota', true);
}  
function js_filter(obj,sClassNeed) {
  if (obj.checked) {
    
    itens = js_getElementbyClass($('dados').rows, sClassNeed);
	  for (i = 0;i < itens.length;i++){
      if ($('mostraAnul').checked == false ){
        if (js_strToFloat(itens[i].cells[4].innerHTML) - js_strToFloat(itens[i].cells[5].innerHTML) > 0) {
          itens[i].style.display="";
        }
      } else {
        itens[i].style.display="";
      }  
    }
  } else {

    itens = js_getElementbyClass($('dados').rows, sClassNeed);
	  for (i = 0;i < itens.length;i++){
       itens[i].style.display = "none";
    }
  }
}

function js_filter2(obj,sClassNeed) {

  if (obj.checked) {
    
    itens = $('dados').rows;
	  for (i = 0;i < itens.length;i++){
      if (js_strToFloat(itens[i].cells[4].innerHTML) - js_strToFloat(itens[i].cells[5].innerHTML) == 0) {

        if ($('mostraNota').checked && itens[i].className == 'comnotasemitem') {
          itens[i].style.display="";
        }else if ($('mostraSem').checked && itens[i].className == 'normal') {
          itens[i].style.display="";
        }else if ($('mostraMig').checked && itens[i].className == 'comitem') {
          itens[i].style.display="";
        }else if ($('mostraCom').checked && itens[i].className == 'comordemnota') {
          itens[i].style.display="";
        }
      }
    }
  } else {

    itens = $('dados').rows;
	  for (i = 0;i < itens.length;i++){
      if ($('mostraAnul').checked == false ) {

        if (js_strToFloat(itens[i].cells[4].innerHTML) - js_strToFloat(itens[i].cells[5].innerHTML) == 0) {
          itens[i].style.display="none";
        }
      }
       //itens[i].style.display = "none";
    }
  }
}
function js_toggleLegend(obj, event) {

  el =  $('btnLegenda'); 
  var x = 0;
  var y = el.offsetHeight;
  while (el.offsetParent && el.tagName.toUpperCase() != 'BODY') {

    x += el.offsetLeft;
    y += el.offsetTop;
    el = el.offsetParent;

  }
  x += el.offsetLeft;
  y += el.offsetTop;

  $('legenda').style.left = x;
  $('legenda').style.top  = y;
  if ($('legenda').style.display == '') {
    $('legenda').style.display = 'none'
  } else if ($('legenda').style.display == 'none') {
    $('legenda').style.display = '';
  }
}

function js_setValorNota(obj) {

  if (obj.checked) {
    
    var nValorOrdem = js_strToFloat($('e53_valor'+obj.value).innerHTML,"f");
    var nValorAnul  = js_strToFloat($('e53_vlranu'+obj.value).innerHTML,"f");
    var nTotalOrdem = new Number(nValorOrdem - nValorAnul); 
    $('totalnota').innerHTML = js_formatar(nTotalOrdem,'f');
  } else {
    $('totalnota').innerHTML = js_formatar(0,'f');
  }

}

function js_limpa() {

  document.frmAnularEmpenho.reset();
  $('dadosItens').innerHTML  = '';
  $('dados').innerHTML  = '';
  $('totalizador').innerHTML = '0,00';
  $('totalnota').innerHTML   = '0,00';

}
js_pesquisa();
</script>