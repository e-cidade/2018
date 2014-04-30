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
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_db_registrosinconsistentes_classe.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_classesgenericas.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$cldb_registrosinconsistentes = new cl_db_registrosinconsistentes;
$db_botao = false;
$db_opcao = 33;

if ( isset($chavepesquisa) ){

  $db_opcao = 3;
  $result = $cldb_registrosinconsistentes->sql_record($cldb_registrosinconsistentes->sql_query($chavepesquisa));
  db_fieldsmemory($result,0);
  $db_botao = true;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load("scripts.js, strings.js, prototype.js, dbcomboBox.widget.js, datagrid.widget.js");
db_app::load('estilos.css, grid.style.css')
?>
<style>

#ctnGridResultados td {
   padding: 0 4px;
}

</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">

  <fieldset style="margin: 25px auto auto auto; width:600px">
  
    <legend><strong>Exclusão de Seleção de Registros Inconsistentes</strong></legend>
    
    <table>
      <tr> 
        <td> 
        <center>
        	<?php
        	  require_once("forms/db_frmdb_registrosinconsistentes.php");
        	?>
        </center>
    	</td>
      </tr>
    </table>
    
    <fieldset>
      <legend><strong>Registros:</strong></legend>
      <div id="ctnGridResultados"></div>
    </fieldset>
    
  </fieldset>
  
  <br>
  
  <center>
    <input type="button" value="Excluir" onclick="js_excluir();" />
    <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" />
  </center>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<?
if ($db_opcao==33) {
  echo "<script>js_pesquisa();</script>";
}
?>
<script>

var sUrlRpc = 'con4_registrosinconsistentes.RPC.php';

js_pesquisaInconsistencia();


function js_excluir() {

	if ($F('db136_sequencial') == '') {
		alert('Não há registros para excluir');
		return false;
	}
	
	js_divCarregando("Excluindo Inconsistências", "msgBox");
	var oParam              = new Object();  
	oParam.sExec            = "excluir";
	oParam.iCodigoInconsistencia  = $F('db136_sequencial');
	
	var oAjax  = new Ajax.Request (sUrlRpc,
                                { 
                                method:'post',
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete:js_retornaExcluir
                                });
	
}

function js_retornaExcluir(oAjax) {

	js_removeObj("msgBox");

	var oRetorno = eval("("+oAjax.responseText+")");

	var sMensagem = oRetorno.sMessage.urlDecode().replace(/\\n/g,"\n");

	if (oRetorno.iStatus > 1) {
		
		alert(sMensagem);
		return;
	}

	alert(sMensagem);
	
	$('db136_sequencial').value              = '';
	$('db136_data').value                    = '';
	$('db136_usuario').value                 = '';
	$('nome').value                          = ''; 
	$('db136_tabela').value                  = '';
	$('nomearq').value                       = '';
	$('db136_processado_select_descr').value = '';
	
	oGridResultados.clearAll(true);
	
	js_pesquisa();	
}

function js_pesquisaInconsistencia() {
	
js_divCarregando("Consultando registros do sistema", "msgBox");

var oParam              = new Object();  
oParam.sExec            = "consultaInconsistencia";
oParam.iInconsistencia  = $F('db136_sequencial');
oParam.iCodigoTabela    = $F('db136_tabela');
oParam.sNomeTabela      = $F('nomearq');

var oAjax       = new Ajax.Request (sUrlRpc,
	                                  { 
                                    method:'post',
                                    parameters:'json='+Object.toJSON(oParam),
                                    onComplete:js_retornaRegistrosInconsistencia
                                    });
	
}

function js_retornaRegistrosInconsistencia (oAjax) {
	
	js_removeObj("msgBox");

	var oRetorno = eval("("+oAjax.responseText+")");

	if (oRetorno.aDadosInconsistentes.length == 0) {
		
		alert('Nenhum Registro Encontrado');
		return false;
	}

	oGridResultados              = new DBGrid('Resultados');
	oGridResultados.nameInstance = 'oGridResultados';
	
	oGridResultados = new DBGrid('Resultados');
	oGridResultados.nameInstance = 'oGridResultados';
	oGridResultados.allowSelectColumns(true);
	oGridResultados.setHeader   (oRetorno.aCamposTabela);
	

	oGridResultados.aHeaders.each(function (oHeader, iIndice) {
    if (iIndice > 3) {
      oGridResultados.aHeaders[iIndice].lDisplayed = false;
    }
  });
	
	oGridResultados.show($('ctnGridResultados'));
	oGridResultados.clearAll(true);
	oGridResultados.setStatus('<span style="border:1px solid #000; padding-right: 15px; background-color: #d1f07c;"></span> Registro Correto');
	
	var iTotalCampos = oRetorno.aCamposTabela.length;
	
	oRetorno.aDadosInconsistentes.each(function (oDados, iIndice) {

	  var aLinha = new Array();		
	  var iLinhaCorreta = null;
	  
	  if (oRetorno.iCorreto == oDados[oRetorno.aCamposTabela[0]]) {
		  iLinhaCorreta = iIndice;
		}
	  
		for ( iIndiceCampo = 0; iIndiceCampo < iTotalCampos; iIndiceCampo++ ) {
		  aLinha[ iIndiceCampo ] = oDados[ oRetorno.aCamposTabela[ iIndiceCampo ] ].urlDecode();					  
		}
		
		oGridResultados.addRow(aLinha);

		if (iLinhaCorreta != null) {
			oGridResultados.aRows[iLinhaCorreta].setClassName('destacado');
		}
	  
	 });	  

	oGridResultados.renderRows();
	
}

js_tabulacaoforms("form1", "excluir", true, 1, "excluir", true);
</script>