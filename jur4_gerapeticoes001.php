<?php
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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oGet = db_utils::postMemory($_GET);


?>

<html>
<head>
	<?php 
	  db_app::load('scripts.js, prototype.js, datagrid.widget.js, strings.js');
	  db_app::load('estilos.css, grid.style.css');
	?>
</head>

<body>
<form name="form1" id="form1">
<fieldset style="margin: 25px auto; width: 800px;">

  <legend><strong><? echo $oGet->tp == 'parcelamento' ? 'Iniciais de Parcelamentos' : 'Iniciais Quitadas';?></strong></legend>

  <div id="gridResultados"></div>

</fieldset>

<div id="botao" style="text-align:center; ">
  <input type="button" name="processar" id="processar" value="Processar" onclick="js_processar()" />
</div>

<?php
  db_menu(db_getsession('DB_id_usuario'), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); 
?>
</form>

<script>

var sUrl = 'jur4_gerapeticoes.RPC.php';

function js_pesquisaIniciais() {
	
	var oParam = new Object();
	var oGet   = js_urlToObject();

	oParam.sTipo = oGet.tp;
	oParam.sExec = 'getIniciais';  
		
	js_divCarregando(_M('tributario.juridico.jur4_gerapeticoes001.pesquisando_iniciais'), 'msgbox');

	var oAjax = new Ajax.Request(sUrl, 
														  {
		  												 method    : 'POST',
															 parameters: 'json='+Object.toJSON(oParam),
															 onComplete: js_retornaIniciais
															});

}

function js_retornaIniciais(oAjax) {

	js_removeObj('msgbox');

	var oRetorno = eval("("+oAjax.responseText+")");

	oDataGrid.clearAll(true);

	if (oRetorno.iStatus == 1) {

		for (var iInicial = 0; iInicial < oRetorno.aIniciais.length; iInicial++) {

			with(oRetorno.aIniciais[iInicial]) {
				aRow    = new Array();
				aRow[0] = inicial;
				aRow[1] = situacao.urlDecode();
				aRow[2] = localizacao.urlDecode();
				aRow[3] = advogado.urlDecode();
				aRow[4] = processoforo.urlDecode(); 
				oDataGrid.addRow(aRow);
			}

		}
			
		oDataGrid.renderRows();
		
	} else {
		
		alert(oRetorno.sMessage.replace(/\\n/g,'\n'));
		
	}		
	
}

function js_initTable() {
	
  oDataGrid = new DBGrid('gridResultados');
  
  oDataGrid.nameInstance = 'oDataGrid';
  oDataGrid.setCheckbox (0);
  oDataGrid.setCellAlign(new Array('center', 'center', 'center', 'left', 'right'));
  oDataGrid.setCellWidth(new Array('10%', '20%', '20%', '30%', '20%'));
  oDataGrid.setHeader   (new Array('Inicial', 'Situação', 'Localização', 'Advogado', 'Processo Foro'));
  oDataGrid.setHeight(300);
  oDataGrid.show($('gridResultados'));

  js_pesquisaIniciais();
	oDataGrid.setBuscaConteudo();
}

function js_processar () {

	var oParam        = new Object();
	var oGet          = js_urlToObject();
	var sVirgula      = '';
	var sIniciais     = '';
	var iSelecionados = 0; 
	
	oDataGrid.getSelection().each(function (aRow) {

		iSelecionados++;

		sIniciais += sVirgula + aRow[0]; 
		sVirgula   = ', '; 
		
	});


	if (iSelecionados == 0) {
		alert(_M('tributario.juridico.jur4_gerapeticoes001.selecione_inicial'));
		return false;
	}			

	oParam.sIniciais = sIniciais;  
	oParam.sTipo     = oGet.tp;
	oParam.sExec     = 'salvarPeticoes';  
		
	js_divCarregando(_M('tributario.juridico.jur4_gerapeticoes001.processando_registros'), 'msgbox');

	var oAjax = new Ajax.Request(sUrl, 
														  {
		  												 method    : 'POST',
															 parameters: 'json='+Object.toJSON(oParam),
															 onComplete: js_retornoProcesso 
															});
	
}

function js_retornoProcesso(oAjax) {
  		 
	js_removeObj('msgbox');

	var oRetorno = eval("("+oAjax.responseText+")");
	var sListaArquivos = '';

	if (oRetorno.iStatus == 1) {
		
		sListaArquivos  = oRetorno.sArquivo;
		sListaArquivos += '#Download arquivo '+oRetorno.sArquivo+' |';
		
	  js_montarlista(sListaArquivos,'form1');
		
		js_pesquisaIniciais(); 
		
	} else {
		
		alert(oRetorno.sMessage.urlDecode().replace(/\\n/g,'\n'));
		
	}
	
}

DBGrid.prototype.setBuscaConteudo = function () {
	//alert(this.aWidths.toSource());
}
js_initTable();


</script>
</body>
</html>