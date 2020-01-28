<?
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
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$sDataInicial = date("d/m/Y");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" style="margin-top: 30px;" >

<form>
	<center>
	  <fieldset style="width: 400;">
	    <legend><b>Devolvidos por Inconsist�ncia no Arquivo de Retorno</b></legend>
	    <table width="100%" border="0">
	    	<tr>
	    	  <td nowrap="nowrap" width="120">
	    	    <b>
	    	      <?php 
	    	        db_ancora("Arquivo Retorno:", "js_mostraArquivosRetorno(true)", 1);
	    	      ?>
	    	    </b>
	    	  </td>
	    	  <td>
	    	  	<?php 
	    	  	  db_input("iArquivoRetorno", 10, '', true, "text", 3);
	    	  	?>  
	    	  </td>
	    	</tr>
	    	<tr>
	    	  <td>
	    	    <b>Data:</b>
	    	  </td>
	    	  <td>
	    	    <?php 
	    	      db_inputdata("sDataInicial", "", "", "", true, "text", 1);
	    	      echo " / ";
	    	      db_inputdata("sDataFinal", "", "", "", true, "text", 1);
	    	    ?>
	    	  </td>
	    	</tr>
	    </table>
	  </fieldset>
	  <br>
	  <input type="button" name="btnImprimeRelatorio" id="btnImprimeRelatorio" value="Imprimir" />
	</center>
</form>
<? 
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>


<script>
  /**
   * Configura as vari�veis padr�es do sistema.
   */
  $("sDataInicial").value           = '<?=$sDataInicial;?>';
  $("sDataFinal").value             = '<?=$sDataInicial;?>';
	$("btnImprimeRelatorio").disabled = true;
	$("iArquivoRetorno").value        = '';

	/**
	 * Abre iframe com os arquivos de retorno j� importados
	 */
	function js_mostraArquivosRetorno(lMostrar) {
		js_OpenJanelaIframe('top.corpo','db_iframe_empagedadosret','func_empagedadosret.php?funcao_js=parent.js_completaArquivoRetorno|e75_codret','Pesquisa',true);
	}

	/**
	 * Preenche o input com o CODRET passado pelo iframe.
	 */
	function js_completaArquivoRetorno(iRetorno) {

		$("iArquivoRetorno").value        = iRetorno;
		$("btnImprimeRelatorio").disabled = false;
		db_iframe_empagedadosret.hide();
	}

	$("btnImprimeRelatorio").observe('click', function() {

		if ($("iArquivoRetorno").value == "") {

			alert("Nenhum arquivo de retorno informado.");
			return false;
		}

		var sDireciona  = "emp4_inconsistenciaarquivoretorno002.php?";
		    sDireciona += "iArquivoRetorno="+$("iArquivoRetorno").value;
		    sDireciona += "&sDataInicial="+$("sDataInicial").value;
		    sDireciona += "&sDataFinal="+$("sDataFinal").value;

    var oWinOpen = window.open(sDireciona,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	});
  
  
</script>