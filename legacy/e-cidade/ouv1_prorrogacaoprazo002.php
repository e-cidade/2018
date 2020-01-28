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
include("classes/db_procandam_classe.php");
include("classes/db_proctransfer_classe.php");
include("classes/db_protprocesso_classe.php");
include("classes/db_proctransand_classe.php");
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");
$db_opcao = 1;
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_GET_VARS);
$clprocandam = new cl_procandam;
$clproctransfer = new cl_proctransfer;
$clprotprocesso = new cl_protprocesso;
$clproctransand = new cl_proctransand;
$rotulo = new rotulocampo();
$rotulo->label("p58_codproc");
$rotulo->label("p58_requer");
$rotulo->label("p58_numcgm");
$rotulo->label("p58_id_usuario");
$rotulo->label("p58_coddepto");
$rotulo->label("z01_nome");
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
</head>
<script>
function js_mostra_andam(processo){ 
   js_OpenJanelaIframe('top.corpo','db_iframe','pro3_conspro002.php?codproc='+processo,'Pesquisa',true);
}

function js_frmListaAndamentos(){
		oDBGridListaAndamentos = new DBGrid('listaAdamentos');
		oDBGridListaAndamentos.nameInstance = 'oDBGridListaAndamentos';
		oDBGridListaAndamentos.setHeader(new Array('Depto','Descrição','Data Inicial','Data Final','difDatas','ov15_sequencial','alterado','p61_coddepto'));
		oDBGridListaAndamentos.setHeight(70);
		oDBGridListaAndamentos.setCellAlign(new Array('center','left','center','center','center'));
		//oDBGridListaAndamentos.setCellWidth(new Array(20,360,30,30));
		oDBGridListaAndamentos.aHeaders[4].lDisplayed = false;
		oDBGridListaAndamentos.aHeaders[5].lDisplayed = false;
		oDBGridListaAndamentos.aHeaders[6].lDisplayed = false;
		oDBGridListaAndamentos.aHeaders[7].lDisplayed = false;
		oDBGridListaAndamentos.show($('listaAndamentos'));
		
		//js_RenderGridEmails();
}

function js_pesquisaProcesso(chavepesquisa){

	oPesquisar = new Object();
	oPesquisar.chave 	= chavepesquisa;
	oPesquisar.acao		= 'pesquisar';
	
	var sDados = Object.toJSON(oPesquisar);
		
		js_divCarregando('Aguarde Carregando dados do Processo...','msgBox');
	
		sUrl = 'ouv1_prorrogacaoprazo.RPC.php';
		var sQuery = 'dados='+sDados;
		var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoPesquisaProcesso
                                          }
                                  );			
	
}

function js_retornoPesquisaProcesso(oAjax){
		
	js_removeObj("msgBox");
  
  var aRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
  
  if ( aRetorno.status == 0) {
    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
    parent.document.form1.p58_codproc.value = '';
  	parent.document.form1.p58_requer.value = '';
  	parent.db_iframe_pesquisa.hide()
    return false;
  }else{
  	
  	js_PreenchePesquisaProcesso(aRetorno.processo);
  	js_PreenchePesquisaAndamentos(aRetorno.andamentos);
		 	
  } 
	
}
function js_PreenchePesquisaProcesso(aProcesso){
	
	with (aProcesso[0])  {
	  $('p58_codproc').value 		= p58_codproc;
	  $('p58_id_usuario').value = p58_id_usuario.urlDecode();
	  $('p58_coddepto').value 	= p58_coddepto;
	  $('nomeDepto').value			= nomedepto.urlDecode();
	  $('p58_dtproc').value 		= js_dataFormat(p58_dtproc,'u');
	  $('p58_hora').value				=	p58_hora.urlDecode(); 
		$('p58_codigo').value			=	p58_codigo; 
		$('nomeProcesso').value		=	nomeprocesso.urlDecode(); 
		$('p58_requer').value			=	p58_requer.urlDecode(); 
		$('p58_numcgm').value			=	p58_numcgm.urlDecode(); 
		$('nomeTitular').value		=	nometitular.urlDecode(); 
//		$('ov02_compl').value			=	ov02_compl.urlDecode(); 
		$('nomeUsuario').value		=	nomeusuario.urlDecode(); 
	}
	
}

function js_PreenchePesquisaAndamentos(aAndamentos){

	oDBGridListaAndamentos.clearAll(true);
	var btnData = false;
	var iNumRows = aAndamentos.length;
	//alert(iNumRows);
	if(iNumRows > 0){
		aAndamentos.each(
			function (oAndamentos,iInd){

				var aRow		= new Array();
	
				aRow[0] = oAndamentos.ov15_coddepto;
				aRow[1] = oAndamentos.descrdepto.urlDecode();
				if((oAndamentos.ov15_coddepto == oAndamentos.p61_coddepto) && iInd == 0){
					btnData = true;
				}
				aRow[2] = js_inputdata('dtini'+iInd,oAndamentos.ov15_dtini,btnData);
				btnData = false;
				if(oAndamentos.ov15_coddepto == oAndamentos.p61_coddepto){		
					btnData = true;
				}
				aRow[3] = js_inputdata('dtfim'+iInd,oAndamentos.ov15_dtfim,btnData);
				var difDatas = js_diferenca_datas(oAndamentos.ov15_dtini,oAndamentos.ov15_dtfim,'d');
				if (difDatas == 'i'){
					difDatas = '0';
				}
				aRow[4] = oAndamentos.difdatas;
				aRow[5] = oAndamentos.ov15_sequencial;
				aRow[6] = oAndamentos.alterado;
				aRow[7] = oAndamentos.p61_coddepto;
			
				oDBGridListaAndamentos.addRow(aRow);
				btnData = false;
	 		}	
				
			);
	}
	oDBGridListaAndamentos.renderRows();	
}

//Se o formato for b para o banco senao para usuario
function js_dataFormat(strData,formato){
	
	if(formato=='b'){
		aData = strData.split('/');
		return  aData[2]+'-'+aData[1]+'-'+aData[0];
	}else{
		aData = strData.split('-');
		return  aData[2]+'/'+aData[1]+'/'+aData[0];
	}
}

function js_inputdata(sNomeInput,strData,btnData){
  
  var aData = strData.split('-');
	var sValue = aData[2]+'/'+aData[1]+'/'+aData[0];
	var btnDataEnable = '';
  if(btnData==false){
  	btnDataEnable = 'disabled';
  }
  
  var sNumLinha = sNomeInput.substr(5);
  
	var	strData  = '<input value="'+sValue+'" type="text" id="'+sNomeInput+'" maxlength="10" size="10" autocomplete="off" onKeyUp="js_mascaraDataOuvidoria('+sNomeInput+',event,'+sNumLinha+');" onFocus="js_validaEntrada(this);" '+btnDataEnable+' onChange="js_validaOnChangeData('+sNomeInput+','+sNumLinha+')">';
			strData += '<input value="D" type="button" name="dtjs_'+sNomeInput+'" onclick="pegaPosMouse(event);show_calendar(\''+sNomeInput+'\',\'none\');" '+btnDataEnable+' >';
	    strData += '<input name="'+sNomeInput+'_dia"   type="hidden" title="" id="'+sNomeInput+'_dia" value="'+aData[2]+'" size="2"  maxlength="2" >';
			strData += '<input name="'+sNomeInput+'_mes"   type="hidden" title="" id="'+sNomeInput+'_mes" value="'+aData[1]+'" size="2"  maxlength="2" >'; 
  		strData += '<input name="'+sNomeInput+'_ano"   type="hidden" title="" id="'+sNomeInput+'_ano" value="'+aData[0]+'" size="4"  maxlength="4" >';
			//strData += '<input value="'+sValue+'" type="text" id="'+sNomeInput+'" maxlength="10" size="10" autocomplete="off" onBlur="js_validaDbData(this);" onKeyUp="js_mascaraData(this,event)" onFocus="js_validaEntrada(this);" >';

	//var sNumLinha = sNomeInput.substr(5);

  var sStringFunction  = "js_comparaDatas"+sNomeInput+" = function(dia,mes,ano){ \n";
 			sStringFunction += "  var objData        = document.getElementById('"+sNomeInput+"'); \n";
      sStringFunction += "	objData.value      = dia+'/'+mes+'/'+ano; \n";
      sStringFunction += "	js_validaPrazo("+sNumLinha+","+sNomeInput+"); \n";
  		sStringFunction += "} \n";  
  var script = document.createElement("SCRIPT");
  script.innerHTML = sStringFunction;
  document.body.appendChild(script);
    
	return strData;
}

function js_validaPrazo(linha,objeto){
	
	var sDataModificada = js_dataFormat($(objeto).value,'b');
	var lMaior = js_diferenca_datas($('datausu').value,sDataModificada,3);
	if (lMaior == true){
		alert('Usuário:\n\n Data inválida! \n A data deve ser maior ou igual da data de hoje.\n\nAdministrador:\n ');
		$(objeto).focus();
		//pegaPosMouse(event);
		show_calendar(objeto,'none');
		return false;
	}	
	
	var iNumRows 	= oDBGridListaAndamentos.getNumRows();
	var iIndice 	= 0;

	var oValidaDatas 			  = new Object();
			oValidaDatas.linhas = new Array();
			
	for(var iInd=0; iInd < iNumRows; iInd++){
				
		oValidaDatas.linhas[iIndice] = new Object();
			
		oValidaDatas.linhas[iIndice].dtini 						= $F('dtini'+iInd); 	
		oValidaDatas.linhas[iIndice].dtfim 						= $F('dtfim'+iInd); 	
		oValidaDatas.linhas[iIndice].difdatas 				= oDBGridListaAndamentos.aRows[iInd].aCells[4].getValue().trim(); 	
		oValidaDatas.linhas[iIndice].ov15_sequencial 	= oDBGridListaAndamentos.aRows[iInd].aCells[5].getValue().trim(); 	
		oValidaDatas.linhas[iIndice].ov15_coddepto 		= oDBGridListaAndamentos.aRows[iInd].aCells[0].getValue().trim(); 	
		oValidaDatas.linhas[iIndice].descrdepto 			= oDBGridListaAndamentos.aRows[iInd].aCells[1].getValue().trim(); 	
		oValidaDatas.linhas[iIndice].alterado 				= oDBGridListaAndamentos.aRows[iInd].aCells[6].getValue().trim(); 	
		oValidaDatas.linhas[iIndice].p61_coddepto			= oDBGridListaAndamentos.aRows[iInd].aCells[7].getValue().trim(); 	
		iIndice++;
	}
	oValidaDatas.linha = linha;
	$('divDesabilita').innerHTML = '&nbsp;';
	$('divDesabilita').style.width = '100%';
	$('divDesabilita').style.height = '100%';
	$('divDesabilita').style.visibility = '';
	
	//alert(Object.toJSON(oValidaDatas));
	js_validaDatas(oValidaDatas);
	
}

function js_validaDatas(oValidaDatas){

	var oValidaData = new Object();
	oValidaData = oValidaDatas;
	oValidaData.acao		= 'validaDatas';
	
	var sDados = Object.toJSON(oValidaData);
	//alert(sDados);	
		js_divCarregando('Aguarde validando a data(s) modificada(s)...','msgBox');
	
		sUrl = 'ouv1_prorrogacaoprazo.RPC.php';
		var sQuery = 'dados='+sDados;
		var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoValidaDatas
                                          }
                                  );			
	
}

function js_retornoValidaDatas(oAjax){
	
	$('divDesabilita').innerHTML = '';
	$('divDesabilita').style.width = '0%';
	$('divDesabilita').style.height = '0%';
	$('divDesabilita').style.visibility = 'none';
	
	js_removeObj("msgBox");
  //alert(oAjax.responseText);return false;	
  var aRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
  
  if ( aRetorno.status == 0) {
    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
    return false;
  }else{
  	js_PreenchePesquisaAndamentos(aRetorno.linhas);
  } 
	
}

function js_atualiza(){
	if(!confirm('Deseja realmente alterar prazo do processo?')){
		return false;
	}
	// Validar se alguma data foi modificada senao avisa usuario e aborta a missão.
	
	var oAtualiza = new Object();
	
	oAtualiza.ov15_motivo 			= $('ov15_motivo').value;
	oAtualiza.ov15_protprocesso = $('p58_codproc').value;
	
	oAtualiza.linhas = new Array();
	
	var iNumRows = oDBGridListaAndamentos.getNumRows();
				
	for(var iInd=0; iInd < iNumRows; iInd++){
		
		if(oDBGridListaAndamentos.aRows[iInd].aCells[6].getValue().trim() == 's'){		
			
			oAtualiza.linhas[iInd] = new Object();
				
			oAtualiza.linhas[iInd].ov15_dtini			= $F('dtini'+iInd); 	
			oAtualiza.linhas[iInd].ov15_dtfim			= $F('dtfim'+iInd); 	 	
			oAtualiza.linhas[iInd].ov15_sequencial	= oDBGridListaAndamentos.aRows[iInd].aCells[5].getValue().trim(); 	
			oAtualiza.linhas[iInd].ov15_coddepto 	= oDBGridListaAndamentos.aRows[iInd].aCells[0].getValue().trim(); 	
			
		}
	}
			
	oAtualiza.acao		= 'alterar';
	
	var sDados = Object.toJSON(oAtualiza);
	//alert(sDados);	
		js_divCarregando('Aguarde atualizando a prorrogação de prazo do processo...','msgBox');
	
		sUrl = 'ouv1_prorrogacaoprazo.RPC.php';
		var sQuery ='dados='+sDados;
		var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoAtualiza
                                          }
                                  );			
	 
}
function js_retornoAtualiza(oAjax){
	js_removeObj("msgBox");
  //alert(oAjax.responseText);return false;	
  var aRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
  
  if ( aRetorno.status == 0) {
    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
    return false;
  }else{
  	alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
  	parent.document.form1.p58_codproc.value = '';
  	parent.document.form1.p58_requer.value = '';
  	parent.db_iframe_pesquisa.hide()
  	//location.href = 'ouv1_prorrogacaoprazo001.php';
  }
}

function js_mascaraDataOuvidoria(campo,evt,numLinha){
	var sNumLinha 	= numLinha;
	var sNomeObjeto = campo.id; 
	$(sNomeObjeto).name = sNomeObjeto;
	$(sNomeObjeto+'_dia').name = sNomeObjeto+'_dia';
	$(sNomeObjeto+'_mes').name = sNomeObjeto+'_mes';
	$(sNomeObjeto+'_ano').name = sNomeObjeto+'_ano';
	 
	//return false;
	js_mascaraData(campo,evt);
	//var iLength = $(sNomeObjeto).value.length;
	//$('ov15_motivo').value = iLength;
	/*
	if($(sNomeObjeto).value.length == 10){
		var sDataModificada = js_dataFormat($(sNomeObjeto).value,'b');
		var lMaior = js_diferenca_datas($('datausu').value,sDataModificada,3);
		if (lMaior == true){
			alert('Usuário:\n\n Data inválida! \n A data deve ser maior ou igual da data de hoje.\n\nAdministrador:\n ');
		$(sNomeObjeto).value = '';
		$(sNomeObjeto).focus();
		return false;
		}
		js_validaPrazo(sNumLinha,sNomeObjeto);		
	}
	*/
}	

function js_validaOnChangeData(sNomeObjeto,sNumLinha){
	if($(sNomeObjeto).value.length == 10){
		var sDataModificada = js_dataFormat($(sNomeObjeto).value,'b');
		var lMaior = js_diferenca_datas($('datausu').value,sDataModificada,3);
		if (lMaior == true){
			alert('Usuário:\n\n Data inválida! \n A data deve ser maior ou igual da data de hoje.\n\nAdministrador:\n ');
		$(sNomeObjeto).value = '';
		$(sNomeObjeto).focus();
		return false;
		}
		js_validaPrazo(sNumLinha,sNomeObjeto);		
	}
}

function js_ImprimeProcesso(){
	
		
	if(oDBGridListaAndamentos.getNumRows() == 0){
		alert('Usuário:\n\n Não exitem andamentos para o processo para emissão de relatorio !\n\nAdministrador:\n\n');
		return false;
	}
	
	var p58_codproc = $F('p58_codproc');
		
	var query  = 'p58_codproc='+p58_codproc;
	
	jan = window.open('ouv1_prorrogacaoprazo003.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
</script>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_pesquisaProcesso(<?=$codproc;?>)" >
<form method="post" action="" name="form1">
<input type="hidden" name="datausu" id="datausu" value="<?=date('Y-m-d',db_getsession('DB_datausu')); ?>">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#cccccc">
  <tr> 
    <td width="360" height="40">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0" align="center">
  <tr align="center"> 
    <td valign="top" bgcolor="#CCCCCC">
    <center>
    <fieldset>
    	<legend><b>Consulta Processo:</b></legend>
    	
	    <table cellspacing = 0 align="left">
			  <tr>
			    <td align="left">
			       <b>Número:</b>
			    </td>
			    <td> 
						<?
						db_input('p58_codproc',10,0,true,'text',3,'')
			      ?>
			    </td>
			 	</tr>   
			 	<tr>
			    <td align="left">
			       <b>Usuário:</b>
			    </td>
			    <td> 
						<?
						db_input('p58_id_usuario',10,0,true,'text',3,'');
						echo "&nbsp";
						db_input('nomeUsuario',40,0,true,'text',3,'');
			      ?>
			    </td>
			 	</tr>
			 	<tr>
			    <td align="left">
			       <b>Departamento:</b>
			    </td>
			    <td> 
						<?
						db_input('p58_coddepto',10,0,true,'text',3,'');
						echo "&nbsp";
						db_input('nomeDepto',40,0,true,'text',3,'');
			      ?>
			    </td>
			 	</tr>     
			 	<tr>
			    <td align="left">
			       <b>Data Criação:</b>
			    </td>
			    <td> 
						<?
						db_input('p58_dtproc',10,0,true,'text',3,'');
			      ?>
			      &nbsp;&nbsp;<b>Hora Criação:</b>&nbsp;
			      <?
						db_input('p58_hora',10,0,true,'text',3,'')
			      ?>
			    </td>
			 	</tr>
			 	<tr>
			    <td align="left">
			       <b>Tipo de Processo:</b>
			    </td>
			    <td> 
						<?
						db_input('p58_codigo',10,0,true,'text',3,'');
						echo "&nbsp";
						db_input('nomeProcesso',40,0,true,'text',3,'');
			      ?>
			    </td>
			 	</tr>  
			 	<tr>
			    <td align="left">
			       <b>Requerente:</b>
			    </td>
			    <td> 
						<?
						db_input('p58_requer',54,0,true,'text',3,'')
			      ?>
			    </td>
			 	</tr>
			 	<tr>
			    <td align="left">
			       <b>Titular do Processo:</b>
			    </td>
			    <td> 
						<?
						db_input('p58_numcgm',10,0,true,'text',3,'');
						echo "&nbsp";
						db_input('nomeTitular',40,0,true,'text',3,'');
			      ?>
			    </td>
			 	</tr>   
			 	<tr>
			    <td align="left">
			       <b>Motivo:</b>
			    </td>
			    <td> 
						<? 
						db_textarea('ov15_motivo',5,52,'',true,'text',1);
						?>
			    </td>
			 	</tr>                  
	    </table>
	   
	   </fieldset>
	   </center> 
   </td>
  </tr>
  <tr>
  	<td>
  		<fieldset><legend><b>Andamento Padrão</b></legend>
  		<div id="listaAndamentos">
  		
  		</div>
  		</fieldset>
  	</td>
  </tr>
  <tr align="center">
  	<td height="40" valign="middle">
  		<input type="button" name="atualizar" value="Confirmar Atualização" onclick="js_atualiza();">
  		<input type="button" name="imprimir" value="Imprimir" onclick="js_ImprimeProcesso();">
  		<input type="button" name="atualizar" value="Voltar" onclick="parent.db_iframe_pesquisa.hide()">
  	</td>
  </tr>
</table>
</form>
<script type="text/javascript">
	js_frmListaAndamentos();
	

</script>
<div id="divDesabilita" style="visibility: none; width: 0px;height: 0px; background-color: transparent;top: 0px;left: 0px;z-index: 5000;position: absolute;"></div>
</body>
</html>