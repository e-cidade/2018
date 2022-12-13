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
require("libs/db_conecta.php");
require("libs/db_utils.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require("classes/empenho.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("e50_codord");
$clrotulo->label("e80_data");
$clrotulo->label("e83_codtipo");
$clrotulo->label("e80_codage");
$clrotulo->label("e50_codord");
$clrotulo->label("e50_numemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("e60_emiss");
$clrotulo->label("e82_codord");
$clrotulo->label("e87_descgera");
$clrotulo->label("o15_codigo");
$clrotulo->label("o15_descr");
$db_opcao = 1;
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <style>
    
    .MovAtualizada {
      background-color: #c97e73;
    }
    
    </style>
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
    <form name='form1' action=''>
     <table align="center" style="padding-top:23px;" width="70%">
       <tr>
         <td>
           
           <center> 
           <fieldset style="width: 40%">
             <legend>
               <b>Filtros</b>
             </legend>
            
    				 <table align="center">
						   <tr>
						     <td nowrap title="<?=@$Te82_codord?>">
						       <? 
						         db_ancora(@$Le82_codord,"js_pesquisae82_codord(true);",$db_opcao);  
						       ?>
						     </td>
						     <td nowrap> 
						       <? 
						         db_input('e82_codord',10,$Ie82_codord,true,'text',$db_opcao," onchange='js_pesquisae82_codord(false);'");  
						       ?>
						       </td>
						       <td>
						       <? 
						         db_ancora("<b>até:</b>","js_pesquisae82_codord02(true);",$db_opcao);  
						       ?>
						     </td>
						     <td nowrap align="left">
						       <?
						         db_input('e82_codord2',10,$Ie82_codord,true,'text',$db_opcao,
						                   "onchange='js_pesquisae82_codord02(false);'","e82_codord02");
						       ?>
						     </td>
						   </tr>   
						   <tr>
						     <td  nowrap title="<?=$Te60_numemp?>">
						       <?
						         db_ancora(@$Le60_codemp,"js_pesquisae60_codemp(true);",$db_opcao);  
						       ?>
						     </td>
						     <td nowrap> 
						        <input name="e60_codemp" id='e60_codemp' title='<?=$Te60_codemp?>' size="10" type='text'  onKeyPress="return js_mascara(event);" >
						     </td>
					     </tr>
						   <tr>
						     <td>
						       <b>Data Inicial:</b>
						     </td>
						     <td nowrap>
						       <?
						         db_inputdata("dataordeminicial",null,null,null,true,"text", 1);
						       ?>
						     </td>
						     <td>
						       <b>Data Final:</b>
						     </td>
						     <td nowrap align="">
						       <?
						         db_inputdata("dataordemfinal",null,null,null,true,"text", 1);
						       ?>
						     </td>
						   </tr>
					     <tr>
					       <td nowrap title="<?=@$Tz01_numcgm?>">
							     <?
							       db_ancora("<b>Credor:</b>","js_pesquisaz01_numcgm(true);",$db_opcao);
							     ?>        
					       </td>
					       <td  colspan='4' nowrap> 
								   <?
								     db_input('z01_numcgm',10,$Iz01_numcgm,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'");
								     db_input('z01_nome',40,$Iz01_nome,true,'text',3,'')
								   ?>
					       </td>
   					   </tr>
   					   <tr nowrap>
					       <td nowrap title="<?=@$To15_codigo?>">
					         <?
					           db_ancora(@$Lo15_codigo,"js_pesquisac62_codrec(true);",$db_opcao); 
					         ?>
					       </td>
							   <td colspan=3 nowrap>
							     <? 
							       db_input('o15_codigo',10,$Io15_codigo,true,'text',$db_opcao," onchange='js_pesquisac62_codrec(false);'");
							       db_input('o15_descr',40,$Io15_descr,true,'text',3,'');   
							     ?>
							   </td>
					     </tr>
             </table>
             </fieldset>
             </center>
             
         <center>   
         <table>   
	       <tr>
	         <td colspan='2' align='center'>
	          <!--<input name="entrar_codord" type="button" id="pesquisar" value="Entrar" onclick='js_lancarRetencao()'>-->
	           <input name="pesquisar" id="pesquisar" type="button" value="Pesquisar" onClick="js_pesquisarOrdens()"/>
	         </td>
	       </tr>
				 </table>
				 </center> 
				   
				     <fieldset>
				       <legend>
				         <b>Ordens</b>
			         </legend>
	   			     <table width="100%">
						     <tr>
						       <td  align="center">
						         <div id="gridNotas"></div>
						       </td>
						     </tr>      
	             </table>
	           </fieldset>
	  
	        </td>
	       </tr>
	       </table
	    </form>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisae82_codord(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem1|e50_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord01 != "" && ord02 != ""){
      alert("Selecione uma ordem menor que a segunda!");
      document.form1.e82_codord.focus(); 
      document.form1.e82_codord.value = ''; 
    }
  }
}

function js_mostrapagordem1(chave1){
  document.form1.e82_codord.value = chave1;
  db_iframe_pagordem.hide();
}

function js_pesquisae82_codord02(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_pagordem','func_pagordem.php?funcao_js=parent.js_mostrapagordem102|e50_codord','Pesquisa',true);
  }else{
    ord01 = new Number(document.form1.e82_codord.value);
    ord02 = new Number(document.form1.e82_codord02.value);
    if(ord01 > ord02 && ord02 != ""  && ord01 != ""){
      alert("Selecione uma ordem maior que a primeira");
      document.form1.e82_codord02.focus(); 
      document.form1.e82_codord02.value = ''; 
    }
  }
}

function js_mascara(evt){
  var evt = (evt) ? evt : (window.event) ? window.event : "";
  
  if((evt.charCode >46 && evt.charCode <58) || evt.charCode ==0){//8:backspace|46:delete|190:.
    return true;
  }else{
    return false;
  }  
}

function js_mostrapagordem102(chave1,chave2){
  document.form1.e82_codord02.value = chave1;
  db_iframe_pagordem.hide();
}

function js_pesquisae60_codemp(mostra){
  js_OpenJanelaIframe('top.corpo','db_iframe_empempenho','func_empempenho.php?funcao_js=parent.js_mostraempempenho2|e60_codemp','Pesquisa',true);
}

function js_mostraempempenho2(chave1){
  document.form1.e60_codemp.value = chave1;
  db_iframe_empempenho.hide();
}

function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','func_nome','func_nome.php?funcao_js=parent.js_mostracgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
     if(document.form1.z01_numcgm.value != ''){ 
        js_OpenJanelaIframe('','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostracgm','Pesquisa',false);
     }else{
       document.form1.z01_nome.value = ''; 
     }
  }
}

function js_mostracgm(erro,chave){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}

function js_mostracgm1(chave1,chave2){
  
  document.form1.z01_numcgm.value = chave1; 
  document.form1.z01_nome.value   = chave2;
  func_nome.hide();
   
}

function js_pesquisac62_codrec(mostra){
   if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?funcao_js=parent.js_mostraorctiporec1|o15_codigo|o15_descr','Pesquisa',true);
   }else{
       if(document.form1.o15_codigo.value != ''){ 
           js_OpenJanelaIframe('top.corpo','db_iframe_orctiporec','func_orctiporec.php?pesquisa_chave='+document.form1.o15_codigo.value+'&funcao_js=parent.js_mostraorctiporec','Pesquisa',false);
       }else{
           document.form1.o15_descr.value = ''; 
       }
   }
}

function js_mostraorctiporec(chave,erro){
   document.form1.o15_descr.value = chave; 
   if(erro==true){ 
      document.form1.o15_codigo.focus(); 
      document.form1.o15_codigo.value = ''; 
   } 
}

function js_mostraorctiporec1(chave1,chave2){
    document.form1.o15_codigo.value = chave1;
    document.form1.o15_descr.value = chave2;
    db_iframe_orctiporec.hide();
}

function js_pesquisarOrdens() {

  js_divCarregando("Aguarde, pesquisando Movimentos.","msgBox");
  js_liberaBotoes(false);
  
  //Criamos um objeto que tera a requisicao
  
  var oParam           = new Object();
  oParam.iOrdemIni     = $F('e82_codord');
  oParam.iOrdemFim     = $F('e82_codord02');
  oParam.dtDataIni     = $F('dataordeminicial');
  oParam.dtDataFim     = $F('dataordemfinal');
  oParam.iCodEmp       = $F('e60_codemp');
  oParam.iNumCgm       = $F('z01_numcgm');
  oParam.iRecurso      = $F('o15_codigo');
  oParam.sDtAut        = '';
  oParam.iOPauxiliar   = '';
  oParam.iAutorizadas  = 1;
  oParam.iOPManutencao = '';
  oParam.lVinculadas   = false;
  
  var sParam           = js_objectToJson(oParam);
  
  url       = 'emp4_manutencaoPagamentoRPC.php';
  
  var sJson = '{"exec":"getMovimentos","params":['+sParam+']}'; 
  var oAjax   = new Ajax.Request(
                         url, 
                         {
                          method    : 'post', 
                          parameters: 'json='+sJson, 
                          onComplete: js_retornoConsultaMovimentos
                          }
                        );
   
}

function js_retornoConsultaMovimentos(oAjax) {

  js_removeObj("msgBox");
  js_liberaBotoes(true);
  var oResponse = eval("("+oAjax.responseText+")");
  gridNotas.clearAll(true);
  gridNotas.setStatus("");
  var iTotalizador  = 0;
  
  if (oResponse.status == 1) {
  
    for (var iNotas = 0; iNotas < oResponse.aNotasLiquidacao.length; iNotas++) {
           
      with (oResponse.aNotasLiquidacao[iNotas]) {
          
          var nValor =  e81_valor;
         
          if (e43_valor > 0 && e43_valor >= e81_valor) {
            nValor = e43_valor;  
          }
          
          nValorTotal  = new Number(nValor - valorretencao).toFixed(2);

          if (nValor == 0) {
           continue;
          }
          
          if ( e97_codmov != '' && e97_codforma != '' ) {
            lAtualizada = true;
          } else {
            lAtualizada = false;
          } 
          
          
          iTotalizador++;
          
          var aLinha  = new Array();
          aLinha[0]   = "<a onclick='js_JanelaAutomatica(\"empempenho\","+e60_numemp+");return false;' href='#'>";
          aLinha[0]  += e60_codemp+"/"+e60_anousu+"</a>";
          aLinha[1]   = o15_codigo;
          aLinha[2]   = e50_codord;
          aLinha[3]   = z01_nome.urlDecode().substring(0,20);
          aLinha[4]   = js_formatar( (e53_valor-e53_vlranu),"f");
          aLinha[5]   = "<span id='valoraut"+e81_codmov+"'>"+js_formatar(nValor, "f")+"</span>";
          aLinha[6]   = js_formatar(e81_valor,"f");
          
          aLinha[7]  = "<a href='#'  id='retencao"+e81_codmov+"'"
            
          if ( lAtualizada ) {  
            aLinha[7] += "onclick='js_msgAtualizada();'>";
          } else {
            aLinha[7] += "onclick='js_lancarRetencao("+e71_codnota+","+e50_codord+","+e60_numemp+","+e81_codmov+");return false'>";
          }
            
          aLinha[7] += js_formatar(valorretencao,"f")+"</a>";  
          aLinha[7] += "<span style='display:none' id='validarretencao"+e81_codmov+"'>"+validaretencao+"</span>";  
          
          aLinha[8]  = "<span id='valorrow"+e81_codmov+"'>"+js_formatar(nValorTotal, "f")+"</span>";
          
          gridNotas.addRow(aLinha, false,false);
 
          if ( lAtualizada ) {           
            gridNotas.aRows[iNotas].setClassName('MovAtualizada');
          }          
          
      }
    }
    gridNotas.renderRows();
    gridNotas.setNumRows(iTotalizador);
    $('gridNotasstatus').innerHTML = "&nbsp;<span style='color:blue' id ='total_selecionados'>0</span> Selecionados";
  } else if (oResponse.status == 2) {
    gridNotas.setStatus("<b>Não foram encontrados movimentos.</b>");
  }
}

function js_lancarRetencao(iCodNota, iCodOrd, iNumEmp, iCodMov){
  
   var lSession     = "false";
   var nValor       = new Number(js_strToFloat($('valorrow'+iCodMov).innerHTML));
   var nValorRetido = js_strToFloat($('retencao'+iCodMov).innerHTML);
   
   js_OpenJanelaIframe('top.corpo', 'db_iframe_retencao',
                       'emp4_lancaretencoes.php?iNumNota='+iCodNota+'&nValorBase='+(nValor+nValorRetido)+
                       '&iNumEmp='+iNumEmp+'&iCodOrd='+iCodOrd+"&lSession="+lSession
                       +'&iCodMov='+iCodMov+'&callback=true',
                       'Lancar Retenções', true);   

}



function js_atualizaValorRetencao(iCodMov, nValor, iNota, iCodOrdem, lValidar) {

   $('valorrow'+iCodMov).innerHTML = new Number(js_strToFloat($('valoraut'+iCodMov).innerHTML) - new Number(nValor)).toFixed(2);
   $('retencao'+iCodMov).innerHTML = js_formatar(nValor,'f');
   
   if (lValidar != null) {
     $('validarretencao'+iCodMov).innerHTML = lValidar;
   }
   
   db_iframe_retencao.hide();
   
}

function js_msgAtualizada(){
    
  var sMsg  = "    Não é possível incluir retenções para esta Nota Fiscal porque já está configurada para pagamento."
      sMsg += "    As retenções poderão ser lançadas ou alteradas através da rotina de Manutenção de Pagamentos ou  ";
      sMsg += "mediante cancelamento de sua configuração através da mesma rotina";  
  
  alert(sMsg);
  return false;

}

function js_liberaBotoes(lLiberar) {

  if (lLiberar) {
  
    $('pesquisar').disabled = false;
  
  } else {

    $('pesquisar').disabled = true;
      
  }
}


function js_init() {
 
  gridNotas              = new DBGrid("gridNotas");
  gridNotas.nameInstance = "gridNotas";
  gridNotas.setCellWidth(new Array("10%","5%", "10%", "35%","10%","10%","10%","10%","10%"));
  gridNotas.setCellAlign(new Array("center", "center","center", "left", "right","right","right","right","right"));
  gridNotas.setHeader( new Array("Empenho", 
                                 "Recurso",
                                 "OP", 
                                 "Nome",
                                 "Valor OP",
                                 "Valor Aut",
                                 "Valor Mov",
                                 "Valor Retido",
                                 "Valor Liquido" 
                                 ));
  gridNotas.show(document.getElementById('gridNotas'));

}


js_init();

</script>