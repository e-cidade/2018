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

include("classes/db_iptubase_classe.php");
include("classes/db_issbase_classe.php");
?>

<script>
agrupadebrecibos 	= false;
debitos 					= false;

function js_MudaLink(nome) {
   if(navigator.appName == "Netscape") {
    TIPO = document.getElementById(nome).childNodes[1].firstChild.nodeValue;
  } else {
    TIPO = document.getElementById(nome).innerText;
  }
   for(i = 0;i < document.links.length;i++) {
    var L = document.links[i].id;
        if(L!=""){
          document.getElementById(L).style.backgroundColor = '#CCCCCC';
          document.getElementById(L).hideFocus = true;
        }
  }
  document.getElementById(nome).style.backgroundColor = '#E8EE6F';
  if(nome.indexOf("tiposemdeb") != -1) {
    document.getElementById('enviar').disabled = true;
    document.getElementById('btmarca').disabled = true;
  } else {
   document.getElementById('btmarca').disabled = false;
  }

  document.getElementById('valor1').innerHTML = "0.00";
  document.getElementById('valorcorr1').innerHTML = "0.00";
  document.getElementById('juros1').innerHTML = "0.00";
  document.getElementById('multa1').innerHTML = "0.00";
  document.getElementById('desconto1').innerHTML = "0.00";
  document.getElementById('total1').innerHTML = "0.00";

  document.getElementById('valor2').innerHTML = "0.00";
  document.getElementById('valorcorr2').innerHTML = "0.00";
  document.getElementById('juros2').innerHTML = "0.00";
  document.getElementById('multa2').innerHTML = "0.00";
  document.getElementById('desconto2').innerHTML = "0.00";
  document.getElementById('total2').innerHTML = "0.00";
                  
  document.getElementById('valor3').innerHTML = "0.00";
  document.getElementById('valorcorr3').innerHTML = "0.00";
  document.getElementById('juros3').innerHTML = "0.00";
  document.getElementById('multa3').innerHTML = "0.00";
  document.getElementById('desconto3').innerHTML = "0.00";
  document.getElementById('total3').innerHTML = "0.00";
}

function js_emiterecibo(){
	
	var emissao = false;
	
  if(document.getElementById('enviar').value != 'Agrupar'){
	 
    var dia_vcto = document.getElementById('dia_vcto').value;
    var mes_vcto = document.getElementById('mes_vcto').value;
    var ano_vcto = document.getElementById('ano_vcto').value;
    if(dia_vcto==""||mes_vcto==""||ano_vcto==""){
     alert("Informe o vencimento.");
     return false;
    }
    //verifica data vcto
    data_vcto = ano_vcto+mes_vcto+dia_vcto;
    data_hoje = "<?=date('Ymd',db_getsession('DB_datausu'))?>";
    if(data_vcto < data_hoje){
     alert("Vencimento deve ser maior ou igual a data atual.");
     document.getElementById('dia_vcto').focus();
     return false;
    }
    //Aqui testo se emite aviso sim ou não
    //se true fazer chamada ajax para verificar se existem debitos e emitir alerta
    js_verificaDebitos(); 
    
    if(agrupadebrecibos){
      if(debitos){
       emissao = false;
			 if(!confirm('\nExistem Débitos Vencidos que serão Agrupados a esse recibo! \n\n Emitir mesmo assim?\n')){
				return false;
			 }
			}		
		}
		
		if(emissao){
			alert('\nParcela não Liberada para Emissão de Recibos!\n');
			return false;		
		}
	    	
	  iframe.document.form1.var_vcto.value = data_vcto;
	  //
	  var F = iframe.document.form1.elements;
	  jan = window.open('','reciboweb2','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	  jan.moveTo(0,0);
	  iframe.document.form1.submit();
	  if((elem = iframe.document.getElementById("geracarne")))
	  elem.parentNode.removeChild(elem);
		
  }else{
	    var tab = iframe.document.getElementById('tabdebitos');
      for(i = 1;i < tab.rows.length;i++) {
        var num = new Number(tab.rows[i].cells[10].childNodes[1].nodeValue);
            num = Math.abs(num);
      }
    var cor = "";
    for(i = 1;i < tab.rows.length;i++) {  
      cor = (cor=="#E4F471")?"#EFE029":"#E4F471";
      tab.rows[i].bgColor = cor;
      if(tab.rows[i].cells[12].childNodes[0].attributes["type"].nodeValue == "submit") {
        var elem = iframe.document.getElementById(tab.rows[i].cells[12].childNodes[0].attributes["id"].nodeValue);
            elem.parentNode.removeChild(elem);
      }
      if(tab.rows[i].cells[12].childNodes[0].attributes["type"].nodeValue == "hidden") {
        var inp = iframe.document.createElement("INPUT");
            inp.setAttribute("type","checkbox");
            inp.setAttribute("name",tab.rows[i].cells[12].childNodes[0].attributes["name"].nodeValue);
            inp.setAttribute("id",tab.rows[i].cells[12].childNodes[0].attributes["id"].nodeValue);
            inp.setAttribute("value",tab.rows[i].cells[12].childNodes[0].attributes["value"].nodeValue);
        if(navigator.appName == "Netscape")
          inp.addEventListener("click",iframe.js_soma,false);
                else
                  inp.onclick = iframe.js_soma;
            tab.rows[i].cells[12].appendChild(inp);
        var elem = iframe.document.getElementById(tab.rows[i].cells[12].childNodes[0].attributes["id"].nodeValue);
            elem.parentNode.removeChild(elem);
          }
    }
    document.getElementById("enviar").value = 'Emite Recibo';
    document.getElementById("enviar").disabled = true;
  }
  
}
function js_verificaDebitos(){

	var G = iframe.document.getElementById('tabdebitos').rows;
 	var check = "";
 	var virgula = "";
 	var numpres = "";
 	
 	for(i = 0;i < G.length;i++) {
 	 	
 		check = 'CHECK'+i;
 		if(iframe.document.getElementById(check) && iframe.document.getElementById(check).checked == true ) {
 	 		if(iframe.document.getElementById(check).value != "") {
   	 		numpres += virgula + iframe.document.getElementById(check).value;
   	 	  virgula = ",";
 	 		}
 	 	}
 	 	  
  }
  
	var sUrlRPC = 'cai3_gerfinanc003RPC.php';  
  var sQuery = "";
  sQuery += 'numcgm='+'<?=$numcgm?>'+'&matric='+'<?=$matric?>'+'&inscr='+'<?=@$inscr?>'+'&tipo='+'<?=$tipo?>';
	var datausu = iframe.document.form1.dt_agrupadebitos.value;
	sQuery += '&db_datausu='+datausu;
	sQuery += '&ver_matric='+iframe.document.form1.ver_matric.value;
	sQuery += '&ver_inscr='+iframe.document.form1.ver_inscr.value;
	sQuery += '&ver_numcgm='+iframe.document.form1.ver_numcgm.value;
	sQuery += '&numpre_unica='+iframe.document.form1.numpre_unica.value;
	<? if (isset($inicial)) { ?>
	  sQuery += '&inicial=true';
	<? } ?>
	sQuery += '&num_pres='+numpres;
	
	//alert("antes ajax : "+sQuery);
	js_divCarregando('Aguarde, verificando os dados.', "msgBox");
  var oAjax       = new Ajax.Request(
          sUrlRPC, 
          {
        	 asynchronous : false,
           method    : 'post', 
           parameters: sQuery, 
           onComplete: js_retornoAgrupaDebitos
           }
         );
}

function js_retornoAgrupaDebitos(oAjax) {

		js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");
	  emissao = '';
	  if (oRetorno.debitos == 1) { debitos = true; } else { debitos = false; }
	  if (oRetorno.emissao == 1) { emissao = true; } else { emissao = false; }
}

function js_debito(iss,iv){
	var numpres = document.getElementById('numpres').value;
	
	location.href='debito.php?numcgm='+<?=$numcgm?>+'&'+iv+'='+iss+'&tipo='+<?=$tipo?>+'&numpres='+numpres;
}
<?
echo " 
function js_voltar(){
  location.href = 'opcoesdebitospendentes.php?matricula=".trim(@$matric).
                                            "&inscricao=".trim(@$inscr).
                                            "&id_usuario=".trim(@$id_usuario).
                                            "&opcao=".trim(@$opcao).
                                            "&cgccpf=".trim(@$cgccpf).
                                            "&cgc=".trim(@$cgccpf).
                                            "&lVoltar=true';
}";

/*
  location.href = 'opcoesdebitospendentes.php?".base64_encode("matricula1=$matric&opcao=m&id_usuario=$id_usuario")."';

*/
?>
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<Table width="100%" border="0" cellpadding="0" cellspacing="0">
 <tr>
  <td align="center" valign="middle" id="processando" onclick="document.getElementById('processando').style.visibility='hidden'"></td>
 </tr>
</Table>
<table width="100%" border="0" cellspacing="0" cellpadding="3" align="center">
 <tr>
  <td align="center">
  	<font size="1"> <b>Obs: Valores abaixo calculados sem considerar a(s) parcela(s) única(s). </b></font>
   <table class="tab" width="100%">
    <tr>
     <th width="20% style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight: bold;">Valor</th>
     <th width="15% style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight: bold;">Valor Corr.</th>
     <th width="15% style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight: bold;">Juros</th>
     <th width="15% style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight: bold;">Multa</th>
     <th width="15% style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight: bold;">Desconto</th>
     <th width="20% style="font-family: Verdana, Arial, Helvetica, sans-serif;font-size:10px;font-weight: bold;">Total</th>
    </tr>
    <tr>
     <td class="tabcols1"><font id="valor1"><?=number_format(@$total_valor,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="valorcorr1"><?=number_format(@$total_valorcor,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="juros1"><?=number_format(@$total_juros,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="multa1"><?=number_format(@$total_multa,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="desconto1"><?=number_format(@$total_desconto,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="total1"><?=number_format(@$total_total,2,'.','')?></font>&nbsp;</td>
    </tr>
    <tr>
     <td class="tabcols1"><font id="valor2">0.00</font>&nbsp;</td>
     <td class="tabcols1"><font id="valorcorr2">0.00</font>&nbsp;</td>
     <td class="tabcols1"><font id="juros2">0.00</font>&nbsp;</td>
     <td class="tabcols1"><font id="multa2">0.00</font>&nbsp;</td>
     <td class="tabcols1"><font id="desconto2">0.00</font>&nbsp;</td>
     <td class="tabcols1"><font id="total2">0.00</font>&nbsp;</td>
    </tr>
    <tr>
     <td class="tabcols1"><font id="valor3"><?=number_format(@$total_valor,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="valorcorr3"><?=number_format(@$total_valorcor,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="juros3"><?=number_format(@$total_juros,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="multa3"><?=number_format(@$total_multa,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="desconto3"><?=number_format(@$total_desconto,2,'.','')?></font>&nbsp;</td>
     <td class="tabcols1"><font id="total3"><?=number_format(@$total_total,2,'.','')?></font>&nbsp;</td>
    </tr>
   </table>
  </td>
 </tr>
 <tr>
  <td height="24" align="center" class="texto">
   <input type="hidden" name="confirm_guia_nro" id="confirm_guia_nro" value="">
   <input type="hidden" name="confirm_guia" id="confirm_guia" value="false">
   Vencimento:
   <input type="text" name="dia_vcto" id="dia_vcto" size="2" maxlength="2" value="<?=date('d')?>">
   <input type="text" name="mes_vcto" id="mes_vcto" size="2" maxlength="2" value="<?=date('m')?>">
   <input type="text" name="ano_vcto" id="ano_vcto" size="4" maxlength="4" value="<?=date('Y')?>">
   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
   <input type="button" value="Voltar" onclick="js_voltar();" class="botao">
   <input type="button" name="enviar" id="enviar" value="Emite Recibo" class="botao" onClick="return js_emiterecibo();" disabled >
   <input type='hidden' name='debito' id='debito' value=''>
   <input type="hidden" value="" name="numpres" id ="numpres" size="60">
  </td>
 </tr>
</table>
</center>
<?php 
require_once ("classes/db_configdbpref_classe.php");
$clconfigdbpref = new cl_configdbpref();
$rs_agrupadebitos = $clconfigdbpref->sql_record($clconfigdbpref->sql_query_file(db_getsession('DB_instit'),"w13_agrupadebrecibos"));
if($clconfigdbpref->numrows > 0){
 	db_fieldsmemory($rs_agrupadebitos,0);
 	if($w13_agrupadebrecibos == 't'){
 		?>
 			<script type="text/javascript">
 				agrupadebrecibos = true;
 			</script>
 		<?php 
 	}
 
} 
?>