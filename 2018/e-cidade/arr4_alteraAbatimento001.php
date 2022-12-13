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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
include_once("libs/db_sessoes.php");
include_once("libs/db_usuariosonline.php");
include_once("dbforms/db_funcoes.php");
include_once("libs/db_utils.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">

<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/md5.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script type="text/javascript" src="scripts/datagrid.widget.js"></script>

<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" onload="js_pesquisaAbatimento()">
<br><br>
<?php
  if (db_getsession("DB_id_usuario") != 1) {
  	?>
  	 <div align="center">
  	 <fieldset style="width: 300px;">
  	   <b>Procedimento indisponível</b>
  	 </fieldset>
  	 </div>
  	<?
  	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  	exit;
  } 
?>
<fieldset>
<legend> <b>Alterar Origens de Abatimento</b> </legend>
<form name="form1" method="POST">
 <table align="center" border=0 width="100%">
  <tr>
  <tr>
   <td>
     <fieldset>
       <legend> <b>Abatimento</b> </legend>
       <table>
        <tr>
         <td width="100px;">
          <?
           db_ancora("Abatimento:","js_pesquisaAbatimento();",1);
          ?> 
         </td>
         <td>
          <?
           db_input("abatimento",10,"1",true,'text',3);
          ?>
         </td>
        </tr>
        <tr>
         <td>
           <b>Valor Abatido:</b>
         </td>
         <td>
           <?
             db_input("vlrAbatimento",10,null,true,'text',3);
           ?>
         </td>
        </tr>
        <tr>
         <td>
           <b>Valor Origens:</b>
         </td>
         <td>
           <?
             db_input("vlrOrigens",10,null,true,'text',3);
           ?>
         </td>
        </tr>
        <tr>
          <td colspan=2 style="visibility:hidden;" id ="MI">
            <?
             db_ancora('Consultar Origens Atuais do Abatimento',"js_consultaOrigemCredito()",1,'');
            ?>
          </td>
        </tr>        
       </table>
     </fieldset>
     <fieldset>
       <legend> <b>Origens</b> </legend>
       <table width="100%" border=1 cellpadding="0" cellspacing="0">
        <tr>
          <td id="frmOrigensAbatimento"> </td>
        </tr>
      </table>
     </fieldset>
   </td>   
  </tr>
 </table>   
</form>
</fieldset>
<br>
<div align="center">
  <input type="button" name="btnSalvar" id="btnSalvar" value="Salvar" disabled />
</div>
<?
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>


  var sUrlRPC = "arr4_manutencaoAbatimento.RPC.php";

  var oDBGridOrigensAbatimento          = new DBGrid('frmOrigensAbatimento');
  oDBGridOrigensAbatimento.nameInstance = 'oDBGridOrigensAbatimento';

  var aHeadersGrid           = new Array("Numpre", "Parcela", "Receita", "Descrição da Receita", "Histórico", "Descrição do Histórico", "Tipo", "Descrição do Tipo","Valor Abatido");
  var aCellWidthGrid         = new Array("5%", "5%", "5%", "20%", "5%", "20%", "5%", "20%","15%");
  var aCellAlign             = new Array("center", "center", "center", "left", "center", "left", "center", "left","center");

  oDBGridOrigensAbatimento.setCellWidth(aCellWidthGrid);
  oDBGridOrigensAbatimento.setCellAlign(aCellAlign);
  oDBGridOrigensAbatimento.setHeader(aHeadersGrid);
  oDBGridOrigensAbatimento.setHeight(200);

  oDBGridOrigensAbatimento.show($('frmOrigensAbatimento'));

	$('btnSalvar').observe("click", function() {
		js_alterarOrigensAbatimento();
	});  

  function js_pesquisaAbatimento() {
	    js_OpenJanelaIframe('','db_iframe_abatimento','func_abatimento.php?funcao_js=parent.js_mostraAbatimento1|k125_sequencial|k125_valor','Pesquisa',true);
	}
	
	function js_mostraAbatimento1(chave1,chave2) {
		
		js_limpaTela();
		
	  $("abatimento").value    = chave1;
	  $("vlrAbatimento").value = chave2;
	  db_iframe_abatimento.hide();

	  $("MI").style.visibility = 'visible';
	  js_buscaOrigensAbatimento(chave1);
	}

	function js_consultaOrigemCredito() {
		
		var sUrl = 'func_origemabatimento.php?iAbatimento='+$("abatimento").value;
	  js_OpenJanelaIframe('top.corpo','db_iframe_abatimento',sUrl,'Origem Crédito',true);
	  
	}	

	function js_buscaOrigensAbatimento(iAbatimento) {

		 var oRequisicao             = new Object();
	       oRequisicao.exec        = "getOrigensAbatimento";
	       oRequisicao.iAbatimento = iAbatimento;
	        
	   var sJson = js_objectToJson(oRequisicao);
	   js_divCarregando("Buscando origens do abatimento, aguarde... ","msgBox");
	   var oAjax = new Ajax.Request( sUrlRPC, 
	                                          { 
	                                            method    : 'post', 
	                                            parameters: 'json='+sJson, 
	                                            onComplete: js_retornoOrigensAbatimento
	                                          }
	                                        );		
		
	}

	function js_retornoOrigensAbatimento(oAjax) {
		
		js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");
		if (oRetorno.lErro == true) {
			alert("Nenhuma origem para o abatimento válida encontrada");
			js_limpaTela();
			return false;
		}	

		$('btnSalvar').disabled = false;
		
		oDBGridOrigensAbatimento.clearAll(true);
		
		oRetorno.aOrigens.each(function (oOrigem, iLinha) {

		      var aLinha = new Array();
		      aLinha[0]  = oOrigem.k00_numpre;
		      aLinha[1]  = oOrigem.k00_numpar;
		      aLinha[2]  = oOrigem.k00_receit;
		      aLinha[3]  = oOrigem.k02_descr;
		      aLinha[4]  = oOrigem.k00_hist;
		      aLinha[5]  = oOrigem.k01_descr;
		      aLinha[6]  = oOrigem.k00_tipo;
		      aLinha[7]  = oOrigem.k00_descr;
		      aLinha[8]  = "<input type=\"text\" id=\"VlrAbatido"+iLinha+"\" ";
		      aLinha[8] += " onKeyUp=\"js_ValidaCampos(this, 4, 'Valor Abatido', false, false, event);\" ";
		      aLinha[8] += " onChange=\"js_somaOrigens(); \" ";
		      aLinha[8] += " size=\"5\" /> ";
		      oDBGridOrigensAbatimento.addRow(aLinha);
		    });
	    
		oDBGridOrigensAbatimento.renderRows();
			
	}

	function js_somaOrigens() {

    var nValorTotal = 0;
    
		for (iInd = 0; iInd < oDBGridOrigensAbatimento.getNumRows() ; iInd++ ) {

			if ($("VlrAbatido"+iInd).value != "") {

				nValorTotal += new Number(oDBGridOrigensAbatimento.aRows[iInd].aCells[8].getValue());
				
			}
			
		}

		$("vlrOrigens").value = nValorTotal;
		if (nValorTotal > $("vlrAbatimento").value) {
		  alert("Valor lançados para as origens do abatimento maior que o valor abatido!");
		  $('btnSalvar').disabled = true;
		} else {
			$('btnSalvar').disabled = false;
		}	
	     		
	}	

	function js_alterarOrigensAbatimento() {

		var aOrigens    = new Array();
    var nValorTotal = 0;
		for (iInd = 0; iInd < oDBGridOrigensAbatimento.getNumRows() ; iInd++ ) {

			if ($("VlrAbatido"+iInd).value != "") {

		    var aDados = new Object();
		    aDados.numpre = oDBGridOrigensAbatimento.aRows[iInd].aCells[0].getValue();   
		    aDados.numpar = oDBGridOrigensAbatimento.aRows[iInd].aCells[1].getValue();
		    aDados.receit = oDBGridOrigensAbatimento.aRows[iInd].aCells[2].getValue();
		    aDados.hist   = oDBGridOrigensAbatimento.aRows[iInd].aCells[4].getValue();
		    aDados.tipo   = oDBGridOrigensAbatimento.aRows[iInd].aCells[6].getValue();
		    aDados.valor  = oDBGridOrigensAbatimento.aRows[iInd].aCells[8].getValue();

		    nValorTotal += new Number(aDados.valor);  
		    aOrigens.push(aDados);	
      }
		             
    }

	  if (nValorTotal != $("vlrAbatimento").value) {
		  alert("O valor das Origens do Abatimento deverá ser igual ao valor abatido!");
		  return false;
	  }    

		var oParam  				   = new Object();
		    oParam.iAbatimento = $("abatimento").value;
		    oParam.aOrigens    = aOrigens; 
		    oParam.exec 		   = "alterarOrigensAbatimento";

		  js_divCarregando("Aguarde, processando alteração das origens do abatimento...", "msgBox");

		  var oAjax = new Ajax.Request(sUrlRPC,
		                                {
		                                  method:'post',
		                                  parameters:'json='+Object.toJSON(oParam),
		                                  onComplete: js_retornoAlterarOrigensAbatimento
		                                }
		                              );		
		
	}

	function js_retornoAlterarOrigensAbatimento(oAjax){

		  js_removeObj("msgBox");
		  var oRetorno = eval("("+oAjax.responseText+")")
		  alert(oRetorno.message.urlDecode());

		  if (oRetorno.status == 1) {
		    js_limpaTela();
		  }
  }

	function js_limpaTela() {
		
		$("abatimento").value    = ""; 
		$("vlrAbatimento").value = "";
		$("vlrOrigens").value    = "";
		oDBGridOrigensAbatimento.clearAll(true);
		$("MI").style.visibility = 'hidden';
		js_pesquisaAbatimento();
		 
	}
		
</script>