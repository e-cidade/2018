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
include("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");
include ("classes/db_ouvidor_classe.php");
include ("classes/db_db_depart_classe.php");
$clouvidor 				= new cl_ouvidor();
$cldepartamento 	= new cl_db_depart();

db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;
$iGrupo		= 2; //2 proque esta na ouvidoria se protocolo = 1;

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
	<script type="text/javascript">
	
	function js_pesquisaov02_sequencial(mostra){
		if(document.getElementById('ov02_sequencial').value == "" && mostra == false){
			document.getElementById('ov02_sequencial').value = "";
			document.getElementById('ov02_nome').value = "";
		}else{
		  if(mostra==true){
		  	js_OpenJanelaIframe('','db_iframe_cidadao','func_cidadao.php?funcao_js=parent.js_mostracidadao1|0|1','Pesquisa',true);
		  }else{
		  	js_OpenJanelaIframe('','db_iframe_cidadao','func_cidadao.php?pesquisa_chave='+document.form1.ov02_sequencial.value+'&funcao_js=parent.js_mostracidadao','Pesquisa',false);
		  }
		}
	}
	
	function js_mostracidadao(chave,erro){
	  document.form1.ov02_nome.value = erro; 
	  if(erro==true){ 
	    document.form1.ov02_nome.value = 'Chave('+document.form1.ov02_sequencial.value+') não Encontrado';
	    document.form1.ov02_sequencial.focus(); 
	    document.form1.ov02_sequencial.value = ''; 
	    
	  }  
	}
	
	function js_mostracidadao1(chave1,chave2){
	  document.form1.ov02_sequencial.value 	= chave1;
	  document.form1.ov02_nome.value 				= chave2;
	  db_iframe_cidadao.hide();
	}
	
	function  js_frmListaCidadoes(){
		oDBGridListaCidadoes = new DBGrid('listaCidadoes');
		oDBGridListaCidadoes.nameInstance = 'oDBGridListaCidadoes';
		oDBGridListaCidadoes.setHeader(new Array('','Código','CGM','Nome','Situação','Situação CGM','Ações','ov02_seq'));
		oDBGridListaCidadoes.setHeight(200);
		oDBGridListaCidadoes.setCellWidth(new Array(5,5,5,150,35,20,100,5));
		oDBGridListaCidadoes.aHeaders[7].lDisplayed = false;
		oDBGridListaCidadoes.setCellAlign(new Array('center','center','center','left','center','center','center','center'));
		
		oDBGridListaCidadoes.show($('listaCidadoes'));
		js_pesquisa();
		
}

function js_dataFormat(strData,formato){
	
	if(formato=='b'){
		aData = strData.split('/');
		return  aData[2]+'-'+aData[1]+'-'+aData[0];
	}else{
		aData = strData.split('-');
		return  aData[2]+'/'+aData[1]+'/'+aData[0];
	}
}


function js_pesquisa(){

	var dtfim 					= $F('dt_fim');
	var dtini 					= $F('dt_inicio');
	var ov02_sequencial = $F('ov02_sequencial');
	/*
	if(ov02_sequencial == '' && (dtini == '' || dtfim == '')){
		alert('Usuário:\n\n Nenhum Cidadão selecionado ou Período de Criação\n\nAdministrador:\n\n');
		$('dt_inicio').focus();
		return false;
	}
	*/
	oPesquisar = new Object();
	
	oPesquisar.ov02_sequencial 	= ov02_sequencial;
	oPesquisar.dtfim					 	= dtfim == "" ? "" : js_dataFormat(dtfim,'b');
	oPesquisar.dtini					 	= dtini == "" ? "" : js_dataFormat(dtini,'b');
	oPesquisar.acao							= 'pesquisar';
	
	var sDados = Object.toJSON(oPesquisar);
		
		js_divCarregando('Aguarde Carregando dados da Pesquisa...','msgBox');
	
		sUrl = 'ouv4_atuatualizacaocgm.RPC.php';
		var sQuery = 'dados='+sDados;
		var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoPesquisaCidadao                                         
                                           }
                                  );			
	

}

function js_retornoPesquisaCidadao(oAjax){
		
	js_removeObj("msgBox");
  
  var aRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
  
  if ( aRetorno.status == 0) {
    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
    return false;
  }else{
  	
  	js_PreenchePesquisaCidadao(aRetorno.cidadoes);
  	//js_PreenchePesquisaAndamentos(aRetorno.andamentos);
		 	
  } 
	
}

function js_PreenchePesquisaCidadao(aCidadoes){

	oDBGridListaCidadoes.clearAll(true);
	
	var iNumRows = aCidadoes.length;
	//alert(iNumRows);
	if(iNumRows > 0){
		aCidadoes.each(
			function (oCidadoes,iInd){

				var aRow		= new Array();
				var ov03_numcgm = oCidadoes.ov03_numcgm == "" ? 0 : oCidadoes.ov03_numcgm; 
				aRow[0] = js_montaLinkMI(oCidadoes.ov02_sequencial,oCidadoes.ov02_seq,ov03_numcgm);
				aRow[1] = oCidadoes.ov02_sequencial;
				aRow[2] = oCidadoes.ov03_numcgm;
				aRow[3] = oCidadoes.ov02_nome.urlDecode();
				aRow[4] = oCidadoes.ov16_descricao.urlDecode();
				aRow[5] = oCidadoes.ov03_numcgm == '' ? 'Incluir no CGM' : 'Alterar o CGM';
				aRow[6] = js_montaBtnAcoes(oCidadoes.ov02_sequencial,oCidadoes.ov02_seq);
				aRow[7] = oCidadoes.ov02_seq;
						
				oDBGridListaCidadoes.addRow(aRow);
				
	 		}	
				
			);
	}
	oDBGridListaCidadoes.renderRows();	
}

function js_montaLinkMI(ov02_sequencial,ov02_seq,ov03_numcgm){
	return '<a onclick="js_MICidadao('+ov02_sequencial+','+ov02_seq+','+ov03_numcgm+')" style="cursor: pointer"><b>MI</b></a>';
}
function js_MICidadao(ov02_sequencial,ov02_seq,ov03_numcgm){
	var ov02_sequencial = ov02_sequencial;
	var ov02_seq				=	ov02_seq;
	js_OpenJanelaIframe('top.corpo','db_iframe','ouv4_cidadaocgmdetalhe.php?ov02_sequencial='+ov02_sequencial+'&ov02_seq='+ov02_seq+'&ov03_numcgm='+ov03_numcgm,'Pesquisa',true);
}

function js_montaBtnAcoes(ov02_sequencial,ov02_seq){
	
	var ov02_sequencial	= ov02_sequencial;
	var ov02_seq				= ov02_seq;
	var strAcoes = '';
	
	strAcoes 		+= '<input type="button" value="Rejeitar" onclick="js_RejeitaCidadao('+ov02_sequencial+','+ov02_seq+')">';	
	strAcoes 		+= '<input type="button" value="Liberar"  onclick="js_LiberaCidadao('+ov02_sequencial+','+ov02_seq+')">';
	
	return strAcoes;	

}

function js_RejeitaCidadao(ov02_sequencial,ov02_seq){
	
	var ov02_sequencial	= ov02_sequencial;
	var ov02_seq				= ov02_seq;
	var dtfim 					= $F('dt_fim');
	var dtini 					= $F('dt_inicio');
	
	oRejeitar = new Object();
	
	oRejeitar.ov02_sequencial	= ov02_sequencial;
	oRejeitar.ov02_seq				= ov02_seq;
	oRejeitar.dtfim					 	= dtfim == "" ? "" : js_dataFormat(dtfim,'b');
	oRejeitar.dtini					 	= dtini == "" ? "" : js_dataFormat(dtini,'b');
	oRejeitar.acao						= 'rejeitar';
	oDBGridListaCidadoes.getNumRows() == 1 ? oRejeitar.retorno = 0 : oRejeitar.retorno = 1;
	
	var sDados = Object.toJSON(oRejeitar);
	
		js_divCarregando('Aguarde Rejeitando Cadastro do Cidadão...','msgBox');
	
		sUrl = 'ouv4_atuatualizacaocgm.RPC.php';
		var sQuery = 'dados='+sDados;
		var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoRejeitaCidadao                                         
                                           }
                                  );			
	
}
function js_retornoRejeitaCidadao(oAjax){
		
	js_removeObj("msgBox");
  
  var aRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
  
  if ( aRetorno.status == 0) {
    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
    return false;
  }else{
  	$('ov02_sequencial').value 	= '';
  	$('ov02_nome').value 				= '';
  	alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
  	js_PreenchePesquisaCidadao(aRetorno.cidadoes);
  	
		 	
  } 
	
}

function js_LiberaCidadao(ov02_sequencial,ov02_seq){

	var ov02_sequencial	= ov02_sequencial;
	var ov02_seq				= ov02_seq;
	var dtfim 					= $F('dt_fim');
	var dtini 					= $F('dt_inicio');
	
	oLiberar = new Object();
	
	oLiberar.ov02_sequencial	= ov02_sequencial;
	oLiberar.ov02_seq					= ov02_seq;
	oLiberar.dtfim					 	= dtfim != "" ? js_dataFormat(dtfim,'b') : '';
	oLiberar.dtini					 	= dtini != "" ? js_dataFormat(dtini,'b') : '';
	oLiberar.acao							= 'liberar';
	oDBGridListaCidadoes.getNumRows() == 1 ? oLiberar.retorno = 0 : oLiberar.retorno = 1;
	
	var sDados = Object.toJSON(oLiberar);
	
		js_divCarregando('Aguarde Liberando Cadastro do Cidadão...','msgBox');
	
		sUrl = 'ouv4_atuatualizacaocgm.RPC.php';
		var sQuery = 'dados='+sDados;
		var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoLiberaCidadao                                         
                                           }
                                  );			
	
}
function js_retornoLiberaCidadao(oAjax){
		
	js_removeObj("msgBox");
  
  var aRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
  
  if ( aRetorno.status == 0) {
    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
    return false;
  }else{
  	$('ov02_sequencial').value 	= '';
  	$('ov02_nome').value 				= '';
  	alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
  	
  	if (aRetorno.retorno == 0){
 			js_limpar(); 		
  	}else{
  		js_limpar();
  		js_PreenchePesquisaCidadao(aRetorno.cidadoes);
  	}
  	//js_PreenchePesquisaAndamentos(aRetorno.andamentos);
		 	
  } 
	
}


function js_limpar(){
	$('dt_fim').value 					= '';
	$('dt_inicio').value				= '';
	$('ov02_sequencial').value 	= '';
	$('ov02_nome').value 				= '';
	oDBGridListaCidadoes.clearAll(true);
	oDBGridListaCidadoes.renderRows();
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_frmListaCidadoes()">
<table width="100%" border="0" cellspacing="0" cellpadding="0"
	style="margin-top: 20px;">
	<tr align="center">
		<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
		<center>
		<form action="" name="form1">
		<table width="790" style="margin-top: 20px;">
			<tr>
				<td>
				<fieldset><legend><b>Autoriza Atualização de CGM</b></legend>

				<table>
					<tr>
						<td align="left"><b>Data de Criação:</b></td>
						<td align="left">
						<? 
							db_inputdata('dt_inicio','','','',true,'text',1);
							echo "&nbsp;à&nbsp;";
							db_inputdata('dt_fim','','','',true,'text',1);
						?>
						</td>
					</tr>
					<tr>
						<td  align="right">
		      	<?
		      		db_ancora('<b>Cidadao:</b>',"js_pesquisaov02_sequencial(true);","");
		       	?>
		    		</td>
						<td> 
						<?
							$ov02_sequencial	= null;
							$ov02_nome			 	= '';
							db_input('ov02_sequencial',3,1,true,'text',1," onchange='js_pesquisaov02_sequencial(false);'");
							db_input('ov02_nome',50,0,true,'text',3,'');
						?>
		    		</td>
					</tr>					
				</table>

				</fieldset>
				</td>
			</tr>
			<tr align="center">
				<td>
				 <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
				 <input name="novapesquisa" type="button" id="novapesquisa" value="Limpar" onclick="js_limpar();">
				</td>
			</tr>
			<tr>
				<td>
				<fieldset><legend><b>Cidadões</b></legend>
				<div id="listaCidadoes"></div>
				</fieldset>
				</td>
			</tr>
		</table>
		</form>
		</center>
		</td>
	</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>