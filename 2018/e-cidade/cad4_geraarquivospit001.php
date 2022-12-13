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

	require_once ("libs/db_stdlib.php");
	require_once ("libs/db_conecta.php");
	require_once ("libs/db_sessoes.php");
	require_once ("libs/db_usuariosonline.php");
	require_once ("libs/db_app.utils.php");
	require_once ("libs/db_utils.php");
	require_once ("dbforms/db_funcoes.php");
?>
<html>
	<head>
		<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta http-equiv="Expires" CONTENT="0">
		<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
		<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
		<link href="estilos.css" rel="stylesheet" type="text/css">
	</head>

	<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

  <div class="container" style="width:500px !important;">

    <form method="post" name="form1">

	    <fieldset>

	      <legend>Geração de Arquivos IPTU/ITBI</legend>

	      <table class="form-container">

	        <tr>
	          <td width="80px">Exercício:</td>
	          <td><?php

	          	$iAnoUsu = db_getsession("DB_anousu");

	          	$aExercicios = array();
	          	for ($i = 0; $i < 5; $i++) {
	          		$aExercicios[$iAnoUsu-$i] = $iAnoUsu-$i;
	          	}

	          	db_select('exercicio', $aExercicios, true, 1);

	          ?></td>
	        </tr>

	        <tr>
	        	<td valign="top">Semestre:</td>
	        	<td>
	        		<input type="checkbox" id="primeiro_semestre" name="primeiro_semestre" value="1" /><strong>01/01 à 30/06</strong> <br />
	        		<input type="checkbox" id="segundo_semestre"  name="segundo_semestre"  value="2" /><strong>01/07 à 31/12</strong>
	        	</td>
	        </tr>

	 	    </table>

	 	    <br />

	 	    <fieldset>
			 	  <legend>Arquivos</legend>

			 	  <table class="form-container">

			 	  	<tr>
			 	  		<td>
								<input type="checkbox" value="<? echo GeracaoArquivoPit::IPTU ?>" id="iptu" name="iptu"  checked="true"/><strong>IPTU</strong>
			 	  		</td>
			 	  		<td>
								<input type="checkbox" value="<? echo GeracaoArquivoPit::ITBI_PVR ?>" id="itbi_pvr" name="itbi_pvr"  checked="true"/><strong>ITBI-PVR</strong>
			 	  		</td>
			 	  	</tr>

			 	  	<tr>
			 	  		<td>
								<input type="checkbox" value="<? echo GeracaoArquivoPit::ITBI_URBANO ?>" id="itbi_urbano" name="itbi_urbano"  checked="true"/><strong>ITBI-Urbano</strong>
			 	  		</td>
			 	  		<td>
								<input type="checkbox" value="<? echo GeracaoArquivoPit::ITBI_PVU ?>" id="itbi_pvu" name="itbi_pvu"  checked="true"/><strong>ITBI-PVU</strong>
			 	  		</td>
			 	  	</tr>

			 	  	<tr>
			 	  		<td>
								<input type="checkbox" value="<? echo GeracaoArquivoPit::ITBI_RURAL ?>" id="itbi_rural" name="itbi_rural"  checked="true"/><strong>ITBI-Rural</strong>
			 	  		</td>
			 	  		<td>
								<input type="checkbox" value="<? echo GeracaoArquivoPit::LOGRADOUROS ?>" id="logradouros" name="logradouros"  checked="true"/><strong>Logradouros</strong>
			 	  		</td>
			 	  	</tr>
			 	  </table>
	 	    </fieldset>

		  </fieldset>

		  <input type="button" name="enviar" value="Gerar Arquivos" onclick="js_processar();"/>

	  </form>

	</div>

		<?php
			db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
		?>
	</body>
</html>

<script type="text/javascript">

	var sUrlRPC    = 'cad4_geracaoarquivospit.RPC.php';
	var MENSAGENS  = 'tributario.cadastro.cad4_geraarquivospit001.';

	function js_processar() {

		var sIdSemestres = "#primeiro_semestre, #segundo_semestre",
				lCheckboxSemestres = false;

		/**
		 * Pega todos os valores dos chebox de semestres.
		 */
		var aSemestre = new Array();
		lCheckboxSemestres = $$(sIdSemestres).map(function(oCheckbox) {

			if (oCheckbox.checked) {
				aSemestre.push(oCheckbox.value);
			}
 			return oCheckbox.checked;
		}).filter(function(lValue) {
			/**
			 * Filtra todos que sejam true
			 */
			return lValue;
		})
		/**
		 * Verifica se tem ao menos um selecionado
		 */
		.length == 0;

		if (lCheckboxSemestres) {
			alert( _M( MENSAGENS + 'selecione_semestre' ) );
			return false;
		}

		var sIdArquivos = "#iptu, #itbi_rural, #itbi_urbano, #itbi_pvr, #itbi_pvu, #logradouros",
				lCheckboxArquivos = false;

		/**
		 * Pega todos os valores dos checkbox dos arquivos.
		 */
		var aArquivos = new Array();
		lCheckboxArquivos = $$(sIdArquivos).map(function(oCheckbox) {

			if (oCheckbox.checked) {
				aArquivos.push(oCheckbox.value);
			}
			return oCheckbox.checked;
		}).filter(function(lValue){
			/**
			 * Filtra todos que sejam true
			 */
			return  lValue;
		})
		/**
		 * Verifica se tem ao menos um selecionado
		 */
		.length == 0;

		if (lCheckboxArquivos) {
			alert(_M(MENSAGENS + 'selecione_arquivo'));
			return false;
		}

		/**
		 * Parametros a setem enviados para o RPC.
		 */
		var oParametros 			 = new Object();
		oParametros.sExecucao  = 'gerarArquivos';
		oParametros.sExercicio = $F('exercicio');
		oParametros.aSemestre  = aSemestre;
		oParametros.aArquivos  = aArquivos;

		/**
		 * Envia os dados para o RPC, retornando um array com os arquivos.
		 */
		js_divCarregando('Aguarde, gerando arquivos...', 'msgbox');
		var oAjax = new Ajax.Request(
																   sUrlRPC,
																   {
																     method    : 'POST',
																     parameters: 'json='+Object.toJSON(oParametros),
																     onSuccess : abrirDownload
																   }
																);
	}

	/**
	 * Abrir Janela para Download do arquivo
	 * @param aArquivos - Array de objetos {url: '', nome: ''}
	 */
	function abrirDownload( oAjax ) {

		var oRetorno = eval("("+oAjax.responseText+")");
		js_removeObj('msgbox');

		if (oRetorno.iStatus == 2){

			alert(oRetorno.sMensagem.urlDecode());
			return false;
		}

		var aArquivos 			 = oRetorno.aArquivos;
		var aInconsistencias = oRetorno.aInconsistencias;
	  var oDownload 			 = new DBDownload();

	  // Verifica se já existe o aux aberto, se existir apaga-o
		if( $('window01') ){
			$('window01').outerHTML = '';
		}

	  if ( aArquivos.length ) {
	    /**
	     * Adiciona arquivos xml
	     */
			oDownload.addGroups( 'xml', 'Arquivo Xml');

			for (var i = 0; i < aArquivos.length; i++) {

				if( aArquivos[i] != ''){

					var sNomeArquivo = aArquivos[i].split('/')[1];
				  oDownload.addFile( aArquivos[i], sNomeArquivo, 'xml' );
				}

			}
		}


    if ( aInconsistencias.length ) {
			/**
	     * Adiciona arquivos pdf
	     */
			oDownload.addGroups( 'pdf', 'Relatórios de Inconsistências');

			for (var i = 0; i < aInconsistencias.length; i++) {

				if( aInconsistencias[i] != ''){

					var sNomeArquivo = aInconsistencias[i].split('/')[1];
				  oDownload.addFile( aInconsistencias[i], sNomeArquivo, 'pdf' );
				}
			}
		}

		if (aArquivos.length || aInconsistencias.length ) {
	  	oDownload.show();
		}
	}

</script>