<?php
/**
 * - Plugin....................: ArquivoBancario13SalarioBanrisul
 * - Módulo....................: 952
 * - Versão Plugin.............: 1.0
 * - Versão Minima do e-Cidade.: 42
 */
require_once('libs/db_stdlib.php');
require_once('libs/db_conecta_plugin.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_utils.php');
require_once('libs/db_app.utils.php');
require_once('dbforms/db_funcoes.php');
?>
<html>
<head>
	<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<meta http-equiv="Expires" CONTENT="0">
	<?
	db_app::load('scripts.js, prototype.js, strings.js, AjaxRequest.js, DBLookUp.widget.js, DBViewFormularioFolha/CompetenciaFolha.js');
	db_app::load('DBDownload.widget.js, estilos.css');
	?>
</head>
<body>
<div class="container">
	<form name="frmGeracaoArquivo" id="frmGeracaoArquivo">
		<fieldset>
			<legend>
				Geração de arquivo Refeisul
			</legend>
			<table>
				<tr>
					<td>
						<span class="bold" id="lblCompetencia"></span>
					</td>
					<td id="ctnCompetencia">
					</td>
				</tr>
			</table>
		</fieldset>
		<input type="button" value="Gerar" id="btnGerarArquivo">
	</form>
</div>
</body>
</html>
<?php
db_menu();
?>
<script>
	
	(function(oEscopo) {
		
		var oCompetencia = new DBViewFormularioFolha.CompetenciaFolha(true);
		oCompetencia.renderizaLabel($('lblCompetencia'));
		oCompetencia.renderizaFormulario($('ctnCompetencia'));
		oCompetencia.desabilitarFormulario();
		
		$('btnGerarArquivo').observe('click', function(){
			
			new AjaxRequest('pes4_geracaoarquivorefeisul.RPC.php',{ exec:'gerarArquivo'}, function (oResponse, lErro) {
				
				if (lErro) {
					alert(oResponse.sMessage.urlDecode());
				}
				var oArquivoRefeisul = new DBDownload();
				oArquivoRefeisul.addFile(oResponse.sNomeArquivo.urlDecode(), "Download do Arquivo" );
				oArquivoRefeisul.show();
			}).setMessage('Aguarde, gerado arquivo').execute();
		});
		
	})(window);
</script>
