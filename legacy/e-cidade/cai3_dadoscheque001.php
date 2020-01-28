<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_classesgenericas.php");
include("dbforms/db_funcoes.php");

include ("libs/db_app.utils.php");

db_postmemory($HTTP_GET_VARS);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
	db_app::load('strings.js,scripts.js,datagrid.widget.js,prototype.js');
	db_app::load('estilos.css,grid.style.css');
?>

<script>
function js_emite(){
  var query = "";
  query +="e91_cheque="+$F('e91_cheque');
  query +="&c63_banco="+$F('c63_banco');
  query +="&c63_agencia="+$F('c63_agencia');
  query +="&c63_conta="+$F('c63_conta');
  
  jan = window.open('cai3_dadoscheque002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_frmHistCheques(){

		oDBGridHistCheques = new DBGrid('cheques');
		oDBGridHistCheques.nameInstance = 'oDBGridHistCheques';
		oDBGridHistCheques.setHeader(new Array('OP / Slip','Data','Tipo de Movimentação','Usuário'));
		oDBGridHistCheques.setHeight(120);
		oDBGridHistCheques.setCellAlign(new Array('rigth','center','left','left'));
		oDBGridHistCheques.setCellWidth(new Array(30,30,60,60));
		//oDBGridListaCheques.aHeaders[3].lDisplayed = false;
		oDBGridHistCheques.show($('histcheques'));
		oDBGridHistCheques.renderRows();
		//js_RenderGridEmails();
		js_pesquisa();
}

function js_limpa(){
	oDBGridHistCheques.clearAll();
	oDBGridHistCheques.renderRows();
}

function js_pesquisa(){
	
	var oPesquisa = new Object();

	oPesquisa.exec			 	= 'getHistCheque';
	oPesquisa.e91_cheque	=	$('e91_cheque').value;
	oPesquisa.c63_agencia	=	$('c63_agencia').value;
	oPesquisa.c63_banco		=	$('c63_banco').value;
	oPesquisa.c63_conta		=	$('c63_conta').value;
	
	var sDados = Object.toJSON(oPesquisa);
//	alert(sDados);
	var msgDiv = "Aguarde pesquisando ...";
	js_divCarregando(msgDiv,'msgBox');
	
	sUrl = 'emp4_consultacheques.RPC.php';
	var sQuery = 'dados='+sDados;
	var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoPesquisaHistCheque
                                          }
                                  );
	
}

function js_retornoPesquisaHistCheque(oAjax){
	js_removeObj("msgBox");
	//alert(oAjax.responseText);
	
	var aRetorno = eval("("+oAjax.responseText+")");
	
	var sExpReg  = new RegExp('\\\\n','g');
  if(aRetorno.status == 2 ){
  	alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
  	return false;
  }
  
  js_preenche_dados_cheque(aRetorno.dados)
  js_RenderGridHistCheques(aRetorno.historico);
  
}

function js_preenche_dados_cheque(aDados){
	
	var iNumRows = aDados.length;
	if(iNumRows > 0){
		aDados.each(
			function (oDado,iInd){
				$('cheque').innerHTML 		= oDado.e91_cheque;
				$('empenho').innerHTML 	  = oDado.empenho.urlDecode().replace(', slip','');
				$('valor').innerHTML			= js_formatar(oDado.valor,'f','');
				$('numcgm').innerHTML			= oDado.numcgm;
				$('credor').innerHTML			= oDado.credor.urlDecode();
				//$('o15_codigo').innerHTML	= oDado.o15_codigo;
				$('c61_reduz').innerHTML	= oDado.c61_reduz;
				$('o15_descr').innerHTML	= oDado.o15_descr.urlDecode();
				$('e83_descr').innerHTML	= oDado.e83_descr.urlDecode();
				$('banco').innerHTML			= oDado.c63_banco;
				$('recurso').innerHTML	  = oDado.recurso.urlDecode();
				$('ordem').innerHTML	    = oDado.ordem.urlDecode();	
				//$('slip').innerHTML	    	= oDado.slip.urlDecode();	
				$('db90_descr').innerHTML	= oDado.db90_descr.urlDecode();	
				$('anulado').innerHTML		= oDado.anulado.urlDecode();	
				if(oDado.anulado.urlDecode() == 'Sim'){
					$('e86_data').innerHTML		= js_formatar(oDado.e86_data,'d');
				}	
			}
		);
	}	
}

function js_RenderGridHistCheques(aHist){
	
	oDBGridHistCheques.clearAll(true);
	
	var iNumRows = aHist.length;
	
		if(iNumRows > 0){
			aHist.each(
				function (oHist,iInd){
											
						var aRow	= new Array();
						
						aRow[0] 	= oHist.k12_codord.urlDecode();
						aRow[1] 	= js_formatar(oHist.k12_data,'d','');
						aRow[2] 	= oHist.situacao.urlDecode();
						aRow[3] 	= oHist.k11_tesoureiro.urlDecode();
												
	 					oDBGridHistCheques.addRow(aRow);
	 						 										
				}
			);
		}
		
	
	oDBGridHistCheques.renderRows();

}
</script>
<style>
	.td_right {
		background-color: #FFFFFF;
		text-align: right;
	}
	.td_left {
		background-color: #FFFFFF;
		text-align: left;
	}		
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc" 
  onLoad="js_frmHistCheques();">
<table width="100%" align="center" style="margin-top: 20px;"><tr align="center"><td>
<?
	db_input('e91_cheque'	,10,0,false,'hidden');
	db_input('c63_banco'	,10,0,false,'hidden');
	db_input('c63_agencia',10,0,false,'hidden');
	db_input('c63_conta'	,10,0,false,'hidden');
?>
 	<fieldset>
 		<legend><b>Dados do cheque</b></legend>
 		<table width="100%">
 			<tr>
 				<td width="50%" valign="top">
 					<table width="100%" border="0">
 						<tr>
 							<td width="30%"><b>Número do cheque:</b></td>
 							<td class="td_right" id="cheque">
 							  
 							</td>
 							<td>&nbsp;</td>
 							</tr>
 						<tr>
 							<td><b>Empenho(s)</b></td>
 							<td class="td_left" id="empenho" colspan="2">
 									
 							</td>
 							<td>&nbsp;</td>
 						</tr>
 						<tr>
 							<td><b>Ordem(s)/SLIP(S)</b></td>
 							<td class="td_left" id="ordem" colspan="2">
 									
 							</td>
 							<td>&nbsp;</td>
 						</tr>
 						<tr>
 							<td><b>Conta Pagadora</b></td>
 							<td class="td_right" id="c61_reduz"> 
 									
      				</td>
      				<td class="td_left" id="e83_descr"> 
 									
      				</td>
 						</tr>
 						<tr>
 							<td><b>Banco</b></td>
 							<td class="td_right" id="banco">
      				</td>
      				<td class="td_left" id="db90_descr"> 
 									
      				</td>
 						</tr>
 						<tr>
 							<td><b>Recurso</b></td>
 							<td class="td_right" id="recurso"> 
 									
      				</td>
      				<td class="td_left" id="o15_descr"> 
 									
      				</td>
 						</tr>
 						<tr>
 							<td><b>Credor</b></td>
 							<td class="td_right" id="numcgm"> 
 									
      				</td>
      				<td class="td_left" id="credor"> 
 									
      				</td>
 						</tr>
 						<tr>
 							<td><b>Valor</b></td>
 							<td class="td_right" id="valor">
      				</td>
      				<td>&nbsp;</td>
 						</tr>
 						<tr>
 							<td><b>Anulado</b></td>
 							<td class="td_left" id="anulado">
      				</td>
      				<td class="td_left" id="e86_data">
      					
      				</td>
 						</tr>
 					</table>
 				</td>
 				<td valign="top" width="50%">
 				<fieldset>
 					<legend><b>Histórico do Cheque</b></legend>
 					<div id="histcheques"></div>
 					</fieldset>
 				</td>
 			</tr>
 		</table>
 	</fieldset>
</td></tr>
<tr>
	<td colspan='2' align="center">
    <input  name="imprimir" id="imprimir" type="button" value="Imprimir" onclick="js_emite();" >
    <input  name="fechar" id="fechar" type="button" value="Fechar" onclick="parent.db_iframe_dados_cheque.hide();" >
  </td>
 </tr>
</table>
</body>
</html>