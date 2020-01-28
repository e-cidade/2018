<?
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

$clportaria->rotulo->label();
$classenta->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("h12_descr");
$clrotulo->label("h12_assent");
$clrotulo->label("z01_nome");
?>
<center>
  <form name="form1" method="post" action="" onSubmit="return js_validaSubmit();">
    <fieldset>
     <Legend>
       <b>Dados da Portaria</b>
     </Legend>
	 <table border="0">
 	   <tr>
    	 <td>
    	   <b>Tipo de Portaria</b>
    	 </td>
         <td> 
		   <?
			 $aTipoPortaria = array("l"=>"Lote","c"=>"Coletiva");
			 db_select("selTipoPortaria",$aTipoPortaria,true,1,"");
			 db_input('h31_sequencial',10,$Ih31_sequencial,true,'hidden',3);
			 
			 db_input('porti',10,"",true,'hidden',3);
			 db_input('portf',10,"",true,'hidden',3);
			 
		   ?>
    	 </td>
  	   </tr>	 
       <tr>
    	 <td nowrap title="<?=@$Th31_portariatipo?>"><b>
    	   <? 
       		 db_ancora("Tipo de Assentamento","js_pesquisa_h31_portariatipo(true)",$db_opcao); 
    	   ?>
    	 </td>
    	 <td> 
		   <?
			 db_input('h31_portariatipo',10,$Ih31_portariatipo,true,'text',$db_opcao,"onchange='js_pesquisa_h31_portariatipo(false)';");
			 db_input("h12_descr",40,@$Ih12_descr,true,"text",3);
		   ?>
    	 </td> 
  	   </tr>
  	 
       <?
		 if (!isset($h31_usuario) && trim(@$h31_usuario)==""){
     	   $h31_usuario = db_getsession('DB_id_usuario');
		 }
		 db_input('h31_usuario',10,$Ih31_usuario,true,'hidden',3);
	   ?>
  	   <tr>
    	 <td nowrap title="<?=@$Th31_numero?>">
       	   <?=@$Lh31_numero?>
    	 </td>
    	 <td>  
		   <?
			 db_input('h31_numero',10,$Ih31_numero,true,'text',$db_opcao_numero,"")
		   ?>
		     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		 	
		     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;		     
			 <?=@$Lh31_anousu?>
	
		   <?
			
		     if (!isset($h31_anousu) && trim(@$h31_anousu)==""){
     		   $h31_anousu = db_getsession('DB_anousu');
		     }
			
		     db_input('h31_anousu',10,$Ih31_anousu,true,'text',$db_opcao,"");
		     
		   ?>
		 </td>  
  	   </tr>
  	   <tr>
    	 <td nowrap title="<?=@$Th31_dtportaria?>">
           <?=@$Lh31_dtportaria?>
         </td>
         <td> 
		   <?
			 db_inputdata('h31_dtportaria',@$h31_dtportaria_dia,@$h31_dtportaria_mes,@$h31_dtportaria_ano,true,'text',$db_opcao,"");
		   ?>
		   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
       	   <?=@$Lh31_dtinicio?>
   
  		   <?
			 db_inputdata('h31_dtinicio',@$h31_dtinicio_dia,@$h31_dtinicio_mes,@$h31_dtinicio_ano,true,'text',$db_opcao,"");
		   ?>
    	 </td>    	 
  	   </tr>	
       <tr>

  	   </tr>
  	   <tr>
    	 <td nowrap title="<?=@$Th31_amparolegal?>">
       	   <?=@$Lh31_amparolegal?>
    	 </td>
    	 <td> 
		   <?
			 db_textarea('h31_amparolegal',5,51,$Ih31_amparolegal,true,'text',$db_opcao,"")
		   ?>
    	 </td>
  	   </tr>
     </table>
   </fieldset>  
   <br>
   <fieldset>
     <Legend>
       <b>Dados de Assentamento</b>
     </Legend>
	 <table border="0">
  	   <?

      	 db_input('h16_assent',6,$Ih16_assent,true,'hidden',3,"");
      	 
  	   ?>
  	   <tr>
    	 <td nowrap title="<?=@$Th16_dtconc?>">
           <?=@$Lh16_dtconc?>
    	 </td>
    	 <td> 
      	   <?
      	     db_inputdata('h16_dtconc',@$h16_dtconc_dia,@$h16_dtconc_mes,@$h16_dtconc_ano,true,'text',$db_opcao,"onchange='js_somar_dias(document.form1.h16_quant.value, 0)'","","","parent.js_somar_dias(parent.document.form1.h16_quant.value, 0)")
      	   ?>
      		 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      		 <b>Quantidade:</b>
      	   <?
      	     db_input('h16_quant',10,$Ih16_quant,true,'text',$db_opcao,"onchange='js_somar_dias(this.value, 1);'","quantidade")
      	   ?>
	     </td>	
  	   </tr>
  	   <tr>
         <td nowrap title="<?=@$Th16_dtterm?>">
           <?=@$Lh16_dtterm?>
         </td>
         <td> 
           <?
             db_inputdata('h16_dtterm',@$h16_dtterm_dia,@$h16_dtterm_mes,@$h16_dtterm_ano,true,'text',$db_opcao,"onchange='js_somar_dias(0, 3)'","","","parent.js_somar_dias(0, 3)")
           ?>
         </td>
       </tr>
       <tr>
         <td nowrap title="<?=@$Th16_quant?>">
           <?=@$Lh16_quant?>
         </td>
         <td> 
           <?
             db_input('h16_quant',10,$Ih16_quant,true,'text',$db_opcao,"")
           ?>
           &nbsp;&nbsp;&nbsp;&nbsp;
           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      	   <?=@$Lh16_atofic?>
      	   <?
      		 db_input('h16_atofic',15,$Ih16_atofic,true,'text',$db_opcao,"")
      	   ?>
    	 </td>
  	   </tr>
  	   <tr>
    	 <td nowrap title="<?=@$Th16_histor?>">
      	   <?=@$Lh16_histor?>
    	 </td>
    	 <td> 
      	   <?
      		 db_textarea('h16_histor',5,47,$Ih16_histor,true,'text',$db_opcao,"");
      		 db_input('listaFuncionarios',40,"",true,'hidden',3,"");
      	   ?>
    	 </td>
  	   </tr>
	 </table>
   </fieldset>  
   <input name="<?=($db_opcao==1?"incluir":($db_opcao==2?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2?"Alterar":"Excluir"))?>" >
   <input name="imprimir"  type="button" id="imprimir"  value="Imprimir"  onclick="js_consultaPort();"        disabled>
   <input name='novo'      type='button' id='novo'      value='Novo'      onclick="js_reLoad();" style="display:<?=($db_opcao==3?'none':'')?>">
  </form>
</center>
<script>

function js_validaSubmit() {
  
  var jsonFunctionarios = parent.iframe_funcionarios.js_retornaObjMatric();
  var aObjFunctionarios = jsonFunctionarios.evalJSON();
  
  if ( aObjFunctionarios.length == 0 ) {
    
    alert("Favor incluir algum funcionário!");
    return false; 
  }  
   
  document.form1.listaFuncionarios.value = jsonFunctionarios;
}

function js_imprimeConf(iPortInicial ,iPortFinal, iModelo) {
	
  document.form1.porti.value = iPortInicial;	
  document.form1.portf.value = iPortFinal;	
	
  if (document.form1.selTipoPortaria.value == 'l') {
    
    document.form1.imprimir.disabled = false;
       
    if (confirm('    Incluídas as Portarias de '+iPortInicial+' até '+iPortFinal+' \n\n          	Deseja imprimir? ')) {
	    js_envia(iPortInicial,iPortFinal);                 
    }
  } else {
    
    if (iModelo == "" || iModelo == null) {
     
  	  var sMsg  = '			Incluída a Portaria '+document.form1.h31_numero.value;
  	      sMsg += '\n\n Não exite de portaria coletiva para o tipo escolhido.';
  	      sMsg += '\n Cadastre em Cadastros>Assentamentos>Alteração';
  	      sMsg += '\n Imprima em Relatórios>Reemissão de Portarias';
  	      	      
  	  alert(sMsg);
  	  document.form1.imprimir.disabled = true;
    } else {
      
      document.form1.imprimir.disabled = false;
      if (confirm('    Incluída a Portaria '+document.form1.h31_numero.value+' \n\n        Deseja Imprimir? ')) {
		    js_envia(iPortInicial,iPortFinal);	
      }
    }  
  }
}

function js_consultaPort() {
  js_envia(document.form1.porti.value,document.form1.portf.value);	
}
	
function js_envia(iPortInicial, iPortFinal) {
   
  var sAcao   = "consultaPortarias";
  var sQuery  = "sAcao="+sAcao;
      sQuery += "&iPortariaInicial="+iPortInicial;
      sQuery += "&iPortariaFinal="+iPortFinal;
  		
  var url     = "rec1_portariasRPC.php";
  var oAjax   = new Ajax.Request( url, {
                                         method: 'post', 
                                         parameters: sQuery,
                                         onComplete: js_retornoEmite
                                       }
                                );
}

function js_retornoEmite(oAjax) {

  var aRetorno = eval("("+oAjax.responseText+")");
	
  if (aRetorno.erro == true) {
    
	  alert(aRetorno.msg);
	  return false;
  } else {
    
    if (document.form1.selTipoPortaria.value == 'l') {
      js_imprimeRelatorio(aRetorno.iModIndividual, js_downloadArquivo,aRetorno.aParametros.toSource());
    } else {
      js_imprimeRelatorio(aRetorno.iModColetiva, js_downloadArquivo,aRetorno.aParametros.toSource());
    }
  }
}



function js_pesquisa(iTipo) {
  
  if (iTipo == 1) {
  	js_OpenJanelaIframe('','db_iframe_portaria','func_portaria.php?funcao_js=parent.js_preenchepesquisa|h31_sequencial','Pesquisa',true);
  } else {
    js_OpenJanelaIframe('','db_iframe_portaria','func_portaria.php?lcoletiva=true&funcao_js=parent.js_preenchepesquisa|h31_sequencial','Pesquisa',true);
  }
}

function js_preenchepesquisa(chave) {
  
  db_iframe_portaria.hide();
  <?
  if($db_opcao!=1){
    echo " location.href = '".basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"])."?chavepesquisa='+chave";
  }
  ?>
}

function js_pesquisa_h31_portariatipo(mostra) {
  
  if (mostra == true) {
    js_OpenJanelaIframe('','db_iframe_portariatipo','func_portariatipodescrato.php?funcao_js=parent.js_mostrah31_portariatipo1|h30_sequencial|h12_descr|h30_amparolegal|h41_descr','Pesquisa',true);
  } else {
    
    if (document.form1.h31_portariatipo.value != '') { 
      js_OpenJanelaIframe('','db_iframe_portariatipo','func_portariatipodescrato.php?pesquisa_chave='+document.form1.h31_portariatipo.value+'&funcao_js=parent.js_mostrah31_portariatipo','Pesquisa',false);
    } else {
      document.form1.h31_portariatipo.value = ''; 
    }
  }
}

function js_mostrah31_portariatipo(chave1, erro, chave2, chave3, chave4, chave5) {
  
  if (erro == true) {
     
    document.form1.h16_atofic.value       = '';
    document.form1.h31_portariatipo.value = ''; 
    document.form1.h31_portariatipo.focus(); 
  } else {
    
    document.form1.h31_portariatipo.value = chave1; 
    document.form1.h12_descr.value        = chave2;
    document.form1.h16_atofic.value       = chave4;
     
    if (document.form1.h31_amparolegal.value == ""){
      document.form1.h31_amparolegal.value = chave3;
    }
  }
}

function js_mostrah31_portariatipo1(chave1, chave2, chave3, chave4) {
 
  document.form1.h31_portariatipo.value = chave1; 
  document.form1.h12_descr.value        = chave2;
  document.form1.h16_atofic.value       = chave4;
  
  if (document.form1.h31_amparolegal.value == "") {
    document.form1.h31_amparolegal.value = chave3;
  }
  db_iframe_portariatipo.hide();
}

function js_somar_dias(valor, opcao) {

  diai = new Number(document.form1.h16_dtconc_dia.value);
  mesi = new Number(document.form1.h16_dtconc_mes.value);
  anoi = new Number(document.form1.h16_dtconc_ano.value);

  diaf = new Number(document.form1.h16_dtterm_dia.value);
  diaf++; 
  mesf = new Number(document.form1.h16_dtterm_mes.value);
  anof = new Number(document.form1.h16_dtterm_ano.value);

  if (diai != 0 && mesi != 0 && anoi != 0 && valor != "" && opcao != 3) {
  
    valor = new Number(valor);
    data  = new Date(anoi , (mesi - 1), (diai + valor - 1));

    dia = data.getDate();
    mes = data.getMonth() + 1;
    ano = data.getFullYear();

    document.form1.h16_quant.value      = valor;
    document.form1.h16_dtterm_dia.value = dia < 10 ? "0" + dia : dia;
    document.form1.h16_dtterm_mes.value = mes < 10 ? "0" + mes : mes;
    document.form1.h16_dtterm_ano.value = ano;
    
    document.form1.h16_dtterm.value = document.form1.h16_dtterm_dia.value+'/'+document.form1.h16_dtterm_mes.value+'/'+document.form1.h16_dtterm_ano.value;
	  document.form1.h16_dtterm.value = (dia < 10 ? "0" + dia : dia)+'/'+(mes < 10 ? "0" + mes : mes)+'/'+ano;
  
  } else if (diai != 0 && mesi != 0 && anoi != 0 && diaf != 0 && mesf != 0 && anof != 0 && opcao == 3) {
  
    datai  = new Date(anoi , (mesi - 1), diai);
    dataf  = new Date(anof , (mesf - 1), diaf);

    datad                           = (dataf - datai) / 86400000;
    document.form1.h16_quant.value  = datad.toFixed();
    document.form1.quantidade.value = datad.toFixed();

  	if (datad.toFixed() <= 0) {
    	
  	  alert('A data final nao pode ser menor que a data inicial');			
      document.form1.h16_dtterm_dia.value = '';
      document.form1.h16_dtterm_mes.value = '';
      document.form1.h16_dtterm_ano.value = '';
      document.form1.h16_dtterm.value     = '';
      document.form1.h16_dtterm.focus();
      document.form1.h16_quant.value      = '';
      document.form1.quantidade.value     = '';
  	  return false;
  	}

    ano = datad / 365;
    ano = ano.toFixed();
    mes = (datad - (ano * 365)) / 30;
    mes = mes.toFixed();
    dia = datad - (ano * 365) - (mes * 30);
    dia = dia.toFixed();

    if (document.form1.valor_dia) {
      
      document.form1.valor_dia.value = dia;
      document.form1.valor_mes.value = mes;
      document.form1.valor_ano.value = ano;
      document.form1.valor.value     = dia+'/'+mes+'/'+ano;
    }
  } else if (opcao == 2) {
    
    alert("Informe a data inicial!");
    document.form1.h16_dtconc.focus();
    document.form1.h16_dtconc.select();
    document.form1.quantidade.value = "";
  }

  if (document.form1.h16_dtterm.value == '') {
    
    document.form1.quantidade.value = "0";
    document.form1.h16_quant.value  = "0";
  }
  
  quant_dias = new Number(document.form1.quantidade.value);
  
  if (quant_dias == 0) {
    
    document.form1.h16_dtterm_dia.value = '';
    document.form1.h16_dtterm_mes.value = '';
    document.form1.h16_dtterm_ano.value = '';
    document.form1.h16_dtterm.value     = '';
  }
}

function js_somar_dias_ant(valor, opcao) {
  
  diai = new Number(document.form1.h16_dtconc_dia.value);
  mesi = new Number(document.form1.h16_dtconc_mes.value);
  anoi = new Number(document.form1.h16_dtconc_ano.value);
  diaf = new Number(document.form1.h16_dtterm_dia.value);
  mesf = new Number(document.form1.h16_dtterm_mes.value);
  anof = new Number(document.form1.h16_dtterm_ano.value);
  
  if (diai != 0 && mesi != 0 && anoi != 0 && valor != "" && opcao != 3) {
  
    valor = new Number(valor);
    data  = new Date(anoi , (mesi - 1), (diai + valor - 1));

    dia = data.getDate();
    mes = data.getMonth() + 1;
    ano = data.getFullYear();

    document.form1.h16_quant.value      = valor;
    document.form1.h16_dtterm_dia.value = dia < 10 ? "0" + dia : dia;
    document.form1.h16_dtterm_mes.value = mes < 10 ? "0" + mes : mes;
    document.form1.h16_dtterm_ano.value = ano;
    
  } else if(diai != 0 && mesi != 0 && anoi != 0 && diaf != 0 && mesf != 0 && anof != 0 && opcao == 3) {
  
    datai = new Date(anoi , (mesi - 1), diai);
    dataf = new Date(anof , (mesf - 1), diaf);
    datad = (dataf - datai) / 86400000;
    
    document.form1.h16_quant.value  = datad.toFixed();
    document.form1.quantidade.value = datad.toFixed();

    ano = datad / 365;
    ano = ano.toFixed();
    mes = (datad - (ano * 365)) / 30;
    mes = mes.toFixed();
    dia = datad - (ano * 365) - (mes * 30);
    dia = dia.toFixed();

    if (document.form1.valor_dia) {
      
      document.form1.valor_dia.value = dia;
      document.form1.valor_mes.value = mes;
      document.form1.valor_ano.value = ano;
    }
    
  } else if(opcao == 2) {
  
    alert("Informe a data inicial!");
    document.form1.h16_dtconc_dia.focus();
    document.form1.h16_dtconc_dia.select();
    document.form1.quantidade.value = "";
  }
}

function js_reLoad(){
  parent.document.location.href = 'rec1_portlotecol001.php';
}
</script>