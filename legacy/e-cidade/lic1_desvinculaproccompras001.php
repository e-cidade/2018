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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("classes/db_orcparametro_classe.php");
require_once("dbforms/db_funcoes.php");
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, prototype.js, widgets/windowAux.widget.js,strings.js,widgets/dbtextField.widget.js,
                   dbmessageBoard.widget.js, datagrid.widget.js");
      db_app::load("estilos.css,grid.style.css");
    ?>
    
  </head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 25px;">

<center>
	<form>
		<fieldset style="width: 300px;">
			<legend><b>Licitação</b></legend>
			<table>
				<tr>
					<td>
						<b>
  						<?php 
  						  db_ancora("Licitação:", "js_selecionaLicitacao(true)", 1);
  						?>
						</b>
					</td>
					<td>
						<?php 
						  db_input("iLicitacao", 10, '', 1, 'text', 3);
						?>						
					</td>
				</tr>
			</table>
		</fieldset>
		<br>
		<input type="button" name="btnConsultaProcesso" id="btnConsultaProcesso" value="Consulta Processo" onclick="js_buscaProcessoCompras();">
	</form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>


<script>

	var sUrlRPC = "lic4_desvinculaprocessodecompras.RPC.php";
	function js_buscaProcessoCompras() {

		var iLicitacao = $F("iLicitacao");
		if (iLicitacao == "") {
			
			alert("Selecione uma licitação.");
			return false;
		}

		/**
		 * Monta o conteúdo da windowAux que mostra os processos de compra de uma licitação
		 */
		var sContentProcCompras  = "<center>";
				sContentProcCompras += "<fieldset>";
				sContentProcCompras += "  <legend><b>Processo de Compras</b></legend>";
				sContentProcCompras += "  <div id='ctnProcCompras'></div>";
				sContentProcCompras += "</fieldset>";
				sContentProcCompras += "<br><input type='button' name='btnConfirmar' id='btnConfirmar' value='Confirmar'>";
				sContentProcCompras += "&nbsp;<input type='button' name='btnFechar' id='btnFechar' value='Fechar'>";
				sContentProcCompras += "</center>";

		/**
		 * Monta o WINDOWAUX
		 */
		var oWinProcCompras = new windowAux("winAuxProcCompras_"+iLicitacao, "Processos de Compras", 600, 400);
		oWinProcCompras.setContent(sContentProcCompras);
		oWinProcCompras.allowCloseWithEsc(false);

		/**
		 * Destrói a janela caso seja clicado em FECHAR
		 */
    oWinProcCompras.setShutDownFunction(function () {
    	oWinProcCompras.destroy();
    });
		/**
		 * Exclui um processo de compras de uma licitacao
		 */
		$('btnConfirmar').observe('click', function () {
			js_excluiProcessoCompra(iLicitacao);
	  });
	  
	  $('btnFechar').observe('click', function() {
	    oWinProcCompras.destroy();
	  });

		/**
		 * MsgBoard com um help da windowAux
		 */
    var sHelpMsgBoardProcCompras = "Processos de compras da licitação";
    var oMessageBoardItens = new DBMessageBoard("msgBoardCompras_"+iLicitacao, 
                                                "Licitação "+iLicitacao,
                                                sHelpMsgBoardProcCompras,
                                                oWinProcCompras.getContentContainer()
                                                );
    oMessageBoardItens.show();
		oWinProcCompras.show();

		/**
		 * Monta a grid com os processo de compras de uma licitação
		 */
		oGridProcCompras = new DBGrid('ctnProcCompras');
		oGridProcCompras.nameInstance = "oGridProcCompras";
		oGridProcCompras.setCheckbox(0);
		oGridProcCompras.setHeight(160);
		oGridProcCompras.setCellAlign(new Array("center", "center", "right"));
		oGridProcCompras.setCellWidth(new Array("50%", "25%","25%"));
		oGridProcCompras.setHeader(new Array("Processo de Compras", "Data", "Total de Itens"));
		oGridProcCompras.show($('ctnProcCompras'));
		oGridProcCompras.setStatus(' *Duplo clique sob o processo de compras visualizar os itens.');
		js_getProcessos(iLicitacao);
}
  
  function js_getProcessos(iLicitacao) { 
 

    oGridProcCompras.clearAll(true);
		var oParam 				= new Object();
		oParam.exec       = "getProcessoCompras";
		oParam.iLicitacao = iLicitacao;

		js_divCarregando("Aguarde... Buscando processo de compras...", "msgBox");
    var oAjax = new Ajax.Request(sUrlRPC,
              										{method: 'post',
               									   parameters: 'json='+Object.toJSON(oParam),
               										 onComplete: js_preencheGridPC
              										});
	}

	function js_preencheGridPC(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    if (oRetorno.status == 2) {
        
      alert(oRetorno.message.urlDecode());
      return false;
    }

		if (oRetorno.aProcCompras.length == 0 ){
			alert("Nenhum processo de compras foi localizado para a licitação: "+oRetorno.iLicitacao);
			return false;
		}

		oRetorno.aProcCompras.each(
	    function(oProcesso, iIdLinha) {

	    	var iProcessoCompras = oProcesso.pc80_codproc;
  		  var aLinha    = new Array();
	          aLinha[0] = iProcessoCompras;
	          aLinha[1] = js_formatar(oProcesso.pc80_data, "d");                 
		        aLinha[2] = oProcesso.iTotalItens;
		        oGridProcCompras.addRow(aLinha);
		        oGridProcCompras.aRows[iIdLinha].sEvents = "onDblClick='js_mostrarItens("+iProcessoCompras+", "+oRetorno.iLicitacao+");'";
			});
		oGridProcCompras.renderRows();
	}


	function js_mostrarItens(iProcessoCompras, iLicitacao) {

		var sContentItensProcCompras  = "<center>";
		  	sContentItensProcCompras += "<fieldset>";
		    sContentItensProcCompras += "  <legend><b>Itens do Processo de Compras</b></legend>";
		    sContentItensProcCompras += "  <div id='ctnItensProcCompras'></div>";
		    sContentItensProcCompras += "</fieldset>";
		    sContentItensProcCompras += "<br><input type='button' name='btnFecharItens' id='btnFecharItens' value='Fechar'>";
		    sContentItensProcCompras += "</center>";


		/**
		 * Monta o WINDOWAUX
		 */
		var oWinItensProcCompras = new windowAux("winAuxItensProcCompras_"+iProcessoCompras, "Itens do Processo de Compras", 600, 400);
		oWinItensProcCompras.setContent(sContentItensProcCompras);
		oWinItensProcCompras.allowCloseWithEsc(false);

		/**
		 * Destrói a janela caso seja clicado em FECHAR
		 */
		$('btnFecharItens').observe('click', function () {
			oWinItensProcCompras.destroy();
	  });

		/**
		 * MsgBoard com um help da windowAux
		 */
    var sHelpMsgBoardProcCompras = "Itens do Processo de Compras "+iProcessoCompras;
    var oMessageBoardItensPC     = new DBMessageBoard("msgBoardCompras_"+iProcessoCompras, 
                                                      "Processo de Compras "+iProcessoCompras,
                                                      sHelpMsgBoardProcCompras,
                                                      oWinItensProcCompras.getContentContainer()
                                                		 );
    oMessageBoardItensPC.show();
    oWinItensProcCompras.show();


  	oGridItensProcCompras = new DBGrid('ctnItensProcCompras');
  	oGridItensProcCompras.nameInstance = "oGridProcCompras";
  	oGridItensProcCompras.setHeight(160);
  	oGridItensProcCompras.setCellAlign(new Array("right", "center", "left", "right"));
  	oGridItensProcCompras.setCellWidth(new Array("5", "20%","55%", "20%"));
  	oGridItensProcCompras.setHeader(new Array("Seq.", "Codigo", "Descrição", "Quantidade"));
  	oGridItensProcCompras.show($('ctnItensProcCompras'));

  	var oParam 						 	= new Object();
  	oParam.exec       			= "getItensProcessoCompras";
  	oParam.iProcessoCompras = iProcessoCompras;
  	oParam.iLicitacao       = iLicitacao;

		js_divCarregando("Aguarde... Buscando itens...", "msgBox1");
		var oAjax = new Ajax.Request(sUrlRPC,
          										{method: 'post',
           									   parameters: 'json='+Object.toJSON(oParam),
           										 onComplete: js_preencheGridItens
          										});
	}


	function js_excluiProcessoCompra(iLicitacao) {

		var aProcessos = new Array();
		oGridProcCompras.getSelection("object").each(
			function (aObjeto, iLinha) {
				aProcessos.push(aObjeto.aCells[0].getValue());
			});

		if (aProcessos.length == 0) {
			alert("Selecione um processo.");
			return false;
		}
		
		var oParam 				= new Object();
		oParam.exec 			= "excluiProcessoCompras";
		oParam.aProcessos = aProcessos;
		oParam.iLicitacao = iLicitacao;

		js_divCarregando("Aguarde... processando", "msgBox");
		var oAjax = new Ajax.Request(sUrlRPC,
          											{method: 'post',
           									     parameters: 'json='+Object.toJSON(oParam),
           										   onComplete: js_concluiExclusao
          											});
	}
	

	function js_concluiExclusao(oAjax) {
	
		 js_removeObj("msgBox");
	   var oRetorno = eval("("+oAjax.responseText+")");
		 alert(oRetorno.message.urlDecode());
	   if (oRetorno.status == 1) {
	     js_getProcessos($F('iLicitacao'));
	   }
	}

	
	function js_preencheGridItens(oAjax) {

    js_removeObj("msgBox1");
    var oRetorno = eval("("+oAjax.responseText+")");

		if (oRetorno.aItens.length == 0) {

			alert("Nenhum item localizado. Contate o suporte.");
			return false;
		}
		/**
		 *  Preenche os dados da GRID dos ITENS
		 */
		oGridItensProcCompras.clearAll(true);
		oRetorno.aItens.each(
		  function (oItem, iLinha) {

			  var aLinha = new Array();
			  aLinha[0]  = iLinha+1;
			  aLinha[1]  = oItem.iCodigo;
			  aLinha[2]  = oItem.sDescricao.urlDecode();
			  aLinha[3]  = oItem.iQuantidade;
			  oGridItensProcCompras.addRow(aLinha);
			  oGridItensProcCompras.aRows[iLinha].aCells[0].sStyle = "background-color:#DED5CB;font-weight:bold;padding:1px";
		  });

		oGridItensProcCompras.renderRows();
	}


	function js_selecionaLicitacao(lMostra) {

		var sLocation   = "";
		var lOpenIframe = ""
		if (lMostra) {
			
			sLocation   = "func_liclicita.php?tipo=1&funcao_js=parent.js_preencheLicitacao|l20_codigo";
			lOpenIframe = true;
		}
		js_OpenJanelaIframe('top.corpo', 'db_iframe_liclicita', sLocation, 'Pesquisa', lOpenIframe);
	}

	function js_preencheLicitacao(iCodLicita) {

		$("iLicitacao").value = iCodLicita;
		db_iframe_liclicita.hide();
	}
</script>
</body>
</html>