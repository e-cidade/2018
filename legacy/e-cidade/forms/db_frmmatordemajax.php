<?
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

//MODULO: empenho
$clempempenho->rotulo->label();
$clcgm->rotulo->label();
$clmatordem->rotulo->label();
$cldbdepart->rotulo->label();
$clmatordemanu->rotulo->label();
$clmatordemitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e62_item");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("e62_descr");
?>
<form name="form1" method="post">

<center>
<table border='0'>
  <tr align = 'left'>
    <td align="left">
    <fieldset><Legend><b>Dados</b></legend>
      <table border="0">
        <tr>
          <td nowrap align="left" title="<?=@$Te60_numcgm?>"><?=@$Le60_numcgm?></td>
          <td> 
            <?
              db_input('m51_numcgm',20,$Im51_numcgm,true,'text',3)
            ?>
          </td>
          <td nowrap align="left" title="<?=@$z01_nome?>"><?=@$Lz01_nome?></td>
          <td>
            <?
              db_input('z01_nome',45,$Iz01_nome,true,'text',3)
            ?>
          </td>
	</tr>
        <tr>
          <td nowrap align="left" title="<?=@$Tm51_codordem?>"><b>Ordem de Compra:</b></td>
          <td>
	    <?
              db_input('m51_codordem',20,$Im51_codordem,true,'text',3)
	    ?>
	  </td>
          <td></td>
          <td></td>
        </tr>
        <tr>
          <td nowrap align="left" title="<?=@$Tm53_data?>"><b>Data da anula&ccedil;&atilde;o:</b></td>
          <td> 
            <?
            $m53_data_dia =  date("d",db_getsession("DB_datausu"));
	          $m53_data_mes =  date("m",db_getsession("DB_datausu"));
		        $m53_data_ano =  date("Y",db_getsession("DB_datausu"));
            db_inputdata('m53_data',@$m53_data_dia,@$m53_data_mes,@$m53_data_ano,true,'text',3);
            ?>
          </td>
          <td nowrap align="left" title="<?=@$descrdepto?>">
             <?=@$Lcoddepto?>
          <td> 
             <?
             db_input('m51_depto',6,$Im51_depto,true,'text',3);
             db_input('descrdepto',36,$Idescrdepto,true,'text',3);
             ?>
          </td>
        </tr>
        <tr> 
         	<td align='left'><b>Obs:</b></td>
          <td colspan='3' align='left'>
       	 <? 
        	 db_textarea("m53_obs","","90",$Im53_obs,true,'text',1); 
       	 ?>
	  </td>
	</tr>  
  
  <tr style='display:<?=$sDisplay?>'>
     <td nowrap valign="top"><b>Solicitar a Anulação<br>do Empenho:</b></td>
     <td colspan='3'>
      <?
       $valoresanul = array (
                             "0" => "Não", 
                             "1" => "Valor",
                             "2" => "Item",
                            );
       db_select("anularemp",$valoresanul,true,1,"onchange='js_helpanula(this.value)'");
      ?>
       <span id='helpanula'>
       </span>
     </td>
  </tr>
  </table>
  </fieldset>
  </td>
  </tr>
    <tr>
  <td align='center' valign='top' colspan='3' align='center'>
   <fieldset>
  <div style='border:2px inset white'> 
 <table border='0' width='100%' cellspacing="0" cellpadding="0">   
   <tr>
     <th class='table_header'>
	     <input type='checkbox'  style='display:none' id='mtodos' onclick='js_marca()'>
    	<a onclick='js_marca()' style='cursor:pointer'>M</a></b></td>
	   <th class='table_header' align='center'><b><?=$RLe60_codemp?></b></th>
	   <th class='table_header' align='center'><b><?=$RLe60_numemp?></b></th>
	   <th class='table_header' align='center'><b><?=$RLe62_item?></b></th>
	   <th class='table_header' align='center'><b><?=$RLpc01_descrmater?></b></th>
	   <th class='table_header' align='center'><b><?=$RLm52_sequen?></b></th>
     <th class='table_header' align='center'><b><?=$RLm52_quant?></b></th>
     <th class='table_header' align='center'><b>Vl. Unitário</b></th>
     <th class='table_header' align='center'><b><?=$RLm52_valor?></b></th>
     <th class='table_header' align='center'><b>Saldo Itens</b></th>
     <th class='table_header' align='center'><b>Saldo Valor</b></th>
     <th class='table_header' align='center' width='20'><b>&nbsp;</b></th>
	 </tr>
    <tbody id='dados' style='height:150;width:100%;overflow:scroll;overflow-x:hidden;background-color:white'>
  </tbody>
 </table>
 </div>
  </td>
  </tr>
   </table>
   </fieldset>
  </td>
  </tr>
   <tr>
     <td colspan='4' align='center'>
	      <input name="anular"  id='anular' type="button" value="Anular" onclick='js_anularOrdem()'>
	      <input name="pesquisar" id='pesquisar' type="button" value="Pesquisar" onclick="js_pesquisa_matordem()" >
	   </td>
     </tr>
    </tr>
</table>
</center>
</form>
</body>
</html>
<script>
function js_pesquisa_matordem(){
  js_OpenJanelaIframe('top.corpo','db_iframe_matordem','func_matordem.php?funcao_js=parent.js_mostramatordem|m51_codordem|','Ordens de Compra Cadastradas',true);
}
function js_mostramatordem(chave){
   
   js_consultaOrdem(chave);   
   db_iframe_matordem.hide();
}

function js_marca(){
  
	 obj = document.getElementById('mtodos');
	 if (obj.checked){
		 obj.checked = false;
	}else{
		 obj.checked = true;
	}
   itens = js_getElementbyClass(form1,'chkmarca');
	 for (i = 0;i < itens.length;i++){
     if (itens[i].disabled == false){
        if (obj.checked == true){
					itens[i].checked=true;
          js_marcaLinha(itens[i]);
       }else{
					itens[i].checked=false;
          js_marcaLinha(itens[i]);
			 }
     }
	 }
}

function js_consultaOrdem(iOrdem){
   
   js_divCarregando("Aguarde, efetuando pesquisa","msgBox");
   strJson = '{"method":"getOrdem","m51_codordem":"'+iOrdem+'"}';
   $('dados').innerHTML    = '';
   $('pesquisar').disabled = true;
   url     = 'mat4_matordemRPC.php';
   oAjax   = new Ajax.Request(
                            url, 
                              {
                               method: 'post', 
                               parameters: 'json='+strJson, 
                               onComplete: js_retornoGetDados
                              }
                             );

}
function js_retornoGetDados(oAjax){

    js_removeObj("msgBox");
    $('pesquisar').disabled = false;;
    oJson  = eval("("+oAjax.responseText+")");
    $('m51_codordem').value   = oJson.m51_codordem;
    $('m51_numcgm').value     = oJson.z01_numcgm;
    $('m51_depto').value      = oJson.m51_depto;
    $('descrdepto').value     = js_urldecode(oJson.descrdepto);
    $('z01_nome').value       = js_urldecode(oJson.z01_nome);
    $('m53_obs').value        = '';
    $('anularemp').value      = 0;
    $('helpanula').innerHTML  = '';
    sErroMsg                  = '';
    $('dados').innerHTML      = '';
    lLiberar                  = false;
    sRow                      = '';
    iLinhasErro               = 0; //quantidades de linha com erro (sem saldo para anular)
    if (oJson.isRestoPagar){
      
        /*
         * caso o empenho seja RP, so podemos deixar o usuario anular a ordem, se solictar a anulaçao do 
         * empenho.
         */
        $('anularemp').options[1].disabled = true;
        $('anularemp').options[2].disabled = true;
    
    }
    
    if (oJson.m51_tipo == 2){

       alert('Nota gerada automaticamente pelo sistema. Não poderá ser anulada.');
       js_pesquisa_matordem();
       return ;
    }
    if (oJson.itens.length > 0){//3
     
       for (i = 0; i < oJson.itens.length; i++){
         
         sDisabled    = "";
         sClassName   = "normal";
         sDisabledQtd = '' //se desabilitamos a quantidade qto essa for = 0;
         //caso o item esteja com saldo ou valor zerado, desabilitamos ele.
         if (js_round(oJson.itens[i].saldovalor,2) == 0){

            sDisabled  = " disabled ";
            sClassName = " disabled ";
            iLinhasErro++;
         }

         if (js_round(oJson.itens[i].saldoitens,2) == 0) {
           sDisabledQtd  = " disabled ";
         }

         lFraciona = oJson.itens[i].pc01_fraciona == 'f' ? false : true;

         iCodLinha =  oJson.itens[i].m52_codlanc;
         sRow += "<tr  class='"+sClassName+"' id='trchk"+oJson.itens[i].m52_codlanc+"'>";
         sRow += "  <td class='linhagrid' style='text-align:right'>";
         sRow += "    <input type='checkbox' id='chk"+oJson.itens[i].m52_codlanc+"'"+sDisabled;
         sRow += "    onclick='js_marcaLinha(this)' value='"+oJson.itens[i].m52_codlanc+"' class='chkmarca' style='height:11px'>";
         sRow += "    <input type='hidden' id='iCodItem"+oJson.itens[i].m52_codlanc+"' value='"+oJson.itens[i].e62_sequencial+"'>";
         sRow += "  </td>";
         sRow += "  <td class='linhagrid' style='text-align:right'>"+oJson.itens[i].e60_codemp+"/"+oJson.itens[i].e60_anousu+"</td>";
         sRow += "  <td class='linhagrid' style='text-align:right' id='numemp"+oJson.itens[i].m52_codlanc+"'>"+oJson.itens[i].e62_numemp+"</td>";
         sRow += "  <td class='linhagrid' style='text-align:right'>"+oJson.itens[i].e62_item+"</td>";
         sRow += "  <td class='linhagrid' style='text-align:left'>"+js_urldecode(oJson.itens[i].pc01_descrmater)+"</td>";
         sRow += "  <td class='linhagrid' style='text-align:right'>"+oJson.itens[i].e62_sequen+"</td>";
         sRow += "  <td class='linhagrid' style='text-align:right'>"+oJson.itens[i].m52_quant+"</td>";
         sRow += "  <td class='linhagrid' style='text-align:right'>"+oJson.itens[i].e62_vlun+"</td>";
         sRow += "  <td class='linhagrid' style='text-align:right'>"+oJson.itens[i].m52_valor+"</td>";

         if (oJson.itens[i].pc01_servico == "t" && oJson.itens[i].lcontrolaquantidade == "f") {     

           sRow += "  <td class='linhagrid'>";
           sRow += "    <input style='text-align:right' id='saldoitens"+iCodLinha+"' type='text' size='10' value='"+oJson.itens[i].saldoitens+"'";
           sRow += "      onkeypress='return js_validaFracionamento(event,"+lFraciona+",this);' "+sDisabled + sDisabledQtd;
           sRow += "      disabled />";
           sRow += "  </td>";
           sRow += "  <td class='linhagrid'>";
           sRow += "    <input type='text' id='valoritem"+iCodLinha+"' style='text-align:right;' size='10'";
           sRow += "      value='"+js_round(oJson.itens[i].saldovalor,2)+"' onkeypress='return js_teclas(event)'";
           sRow += "      onblur='js_validaItens(this,event,"+js_round(oJson.itens[i].saldovalor,2)+",2,0,"+iCodLinha+");' " + sDisabled + "/>";
           sRow += "  </td>"; 

         } else {

           sRow += "  <td class='linhagrid'>";
           sRow += "    <input style='text-align:right' id='saldoitens"+iCodLinha+"' type='text' size='10' value='"+oJson.itens[i].saldoitens+"'";
           sRow += "      onkeypress='return js_validaFracionamento(event,"+lFraciona+",this);' "+sDisabled + sDisabledQtd;
           sRow += "      onblur='js_validaItens(this,event,"+oJson.itens[i].saldoitens+",1,"+oJson.itens[i].e62_vlun+","+iCodLinha+");' />";
           sRow += "  </td>";
           sRow += "  <td class='linhagrid'>";
           sRow += "    <input type='text' id='valoritem"+iCodLinha+"' style='text-align:right;' size='10'";
           sRow += "      value='"+js_round(oJson.itens[i].saldovalor,2)+"' disabled  onkeypress='return js_teclas(event)'";
           sRow += "      onblur='js_validaItens(this,event,"+js_round(oJson.itens[i].saldovalor,2)+",2,0,"+iCodLinha+");' />";
           sRow += "  </td>";
         }
         
         sRow += "</tr>";
       }
       sRow += "<tr style='height:auto'><td>&nbsp;</td></tr>";
       $('dados').innerHTML = sRow;
       $('anular').disabled = false;
       if (iLinhasErro == oJson.itens.length) {
         
         sMsgErro  = "Todos os itens da Ordem de Compra encontram-se em estoque.";
         sMsgErro += "\nNão será possível efetuar a anulação da ordem.";
         alert(sMsgErro);
         $('anular').disabled    = true;
       }
   }
}
function js_urldecode(str){
  
  str = str.replace(/\+/g," ");
  str = unescape(str);
  return str;
}
function js_marcaLinha(obj){
 
  if (obj.checked){
    
    $('tr'+obj.id).className='marcado';
  }else{

   $('tr'+obj.id).className='normal';

  }

}

function js_helpanula(valor){

   switch (valor){

      case "1":
         $('helpanula').innerHTML  = 'Será solicitado a anulação dos valores dos itens no empenho. Geralmente usado para descontos.';
      break;
      case "2":
         $('helpanula').innerHTML  = 'Será solicitado a anulação da quantidade dos itens no empenho.';
         $('helpanula').innerHTML += '<br>Pode-se anular qualquer quantidade que ainda não esteja em estoque.';
      break;
     default:
         $('helpanula').innerHTML  = ''
      break;
   }

}
function js_validaItens(obj, event, nVlrMax, iTipo, nVlrUni,iLinha){

   var nSaldoItens =  new Number($('saldoitens'+iLinha).value);
   var nValorItem  =  new Number($('valoritem'+iLinha).value);
   if (iTipo == 1){

      if (nSaldoItens > 0 || $('anularemp').value != '1'){
         $('valoritem'+iLinha).disabled         = true;       
         //$('valoritem'+iLinha).style.background = '#DEB887';
      }else{

         $('valoritem'+iLinha).disabled         = false;       
         //$('valoritem'+iLinha).style.background = '#FFFFFF';
      }
      if (obj.value > nVlrMax || obj.value < 0){
         obj.value = nVlrMax;
         $('valoritem'+iLinha).value = js_round((obj.value*nVlrUni),2); 
      }else{
         $('valoritem'+iLinha).value = js_round((obj.value*nVlrUni),2); 
      }
   }else if (iTipo == 2){
     
     
     if (obj.value > nVlrMax || obj.value < 0){
        obj.value = nVlrMax;
     }
     nValorCorrente = new Number(obj.value);
     if (nValorCorrente.valueOf() > nVlrMax || nValorCorrente.valueOf() == 0){
        alert('O valor informado é igual ao valor total do item ou igual a zero.\nItem não poderá ser anulado.');
        obj.value = 0;
     }
     
   }
}

function js_anularOrdem(){
   
   iOrdem = $F('m51_codordem');
   //percorremos  todos os itens selecionados e criamos uma string json para enviar o servidor.
   aItens        = js_getElementbyClass(form1,"chkmarca");
   sJsonItens    = '';
   itensAnulados = 0; 
   sV            = '';
   for (i = 0; i < aItens.length; i++){
     
      if (aItens[i].checked == true){
           
           iItemAtual = aItens[i].value;
           nValor = $F('valoritem' + iItemAtual);
           nItens = $F('saldoitens' + iItemAtual);
           iCodItem = $F('iCodItem' + iItemAtual);
           iNumEmp = new Number($('numemp' + iItemAtual).innerHTML);
         if (nValor > 0) {
          
           sJsonItens += sV + "{'iCodItemOrdem':'" + iItemAtual + "','nVlrAnu':'" + nValor + "','nQtdeAnu':'" + nItens + "','iNumEmp':'" + iNumEmp + "','iCodItem':'" + iCodItem + "'}";
           sV = ",";
           itensAnulados++;
         }
      }
   }
   if (itensAnulados > 0){

     if (confirm('Confirma a anulação da Ordem De Compra?')){

        js_divCarregando("Aguarde, anulando itens selecionados","msgBox");
        strJson  = '{"method":"anularOrdem","m51_codordem":"'+iOrdem+'","itensAnulados":['+sJsonItens+'],"sMotivo":"'+encodeURIComponent(tagString( $F('m53_obs')))+'",';
        strJson += '"empanula":"'+$F('anularemp')+'"}';
    
        //$('pesquisar').disabled = true;
        //$('anular').disabled    = true;
        url     = 'mat4_matordemRPC.php';
        oAjax   = new Ajax.Request(
                                 url, 
                                 {
                                  method: 'post', 
                                  parameters: 'json='+strJson, 
                                  onComplete: js_retornoAnularOrdem
                                  }
                                 );
     }
   }else{

      alert('Selecione um item para anular.');
      $('pesquisar').disabled = false;
      $('anular').disabled    = false;
   }

}

function js_retornoAnularOrdem(oAjax){

    js_removeObj("msgBox");
    oJson  = eval("("+oAjax.responseText+")");
    $('pesquisar').disabled = false;
    $('anular').disabled    = false;
    if (oJson.status == 1) {

         alert('Itens anulados com sucesso.');
         js_reset();
         js_pesquisa_matordem();

    }else{
      alert(js_urldecode(oJson.mensagem))
    }
}
js_pesquisa_matordem();
function js_reset() {

  document.form1.reset();
  $('dados').innerHTML = '';

}
</script>