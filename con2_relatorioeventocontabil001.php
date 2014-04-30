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
require_once("libs/db_liborcamento.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");


$oRotuloConHistDoc  = new rotulo('conhistdoc');
$oRotuloConHistDoc->label();
$oRotuloContranslan = new rotulo('contranslan');
$oRotuloContranslan->label();

/*
 * Buscamos os anos das transações cadastrados em contrans
 */
$oDaoContrans          = db_utils::getDao('contrans');
$sSqlBuscaAnoTransacao = $oDaoContrans->sql_query_file(null, "distinct c45_anousu", "c45_anousu desc");
$rsBuscaAnoTransacao   = $oDaoContrans->sql_record($sSqlBuscaAnoTransacao);
$aAnosConfigurados     = array();
for ($iRowAno = 0; $iRowAno < $oDaoContrans->numrows; $iRowAno++) {

	$iAnoLocalizado                     = db_utils::fieldsMemory($rsBuscaAnoTransacao, $iRowAno)->c45_anousu;
	$aAnosConfigurados[$iAnoLocalizado] = $iAnoLocalizado;
}


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<body style="background-color: #cccccc; margin-top: 25px;">

<center>
	<form>
		<fieldset style="width: 600px;">
			<legend><b>Relatório de Eventos Contábeis</b></legend>
			<table width="100%">
				<tr>
					<td>
						<?php 
							db_ancora("<b>Documento:</b>", "js_pesquisaDocumento(true);", 1);
						?>
					</td>
					<td>
						<?php
						  db_input('c53_coddoc', 10, $Ic53_coddoc, true, 'text', 1, "onchange='js_pesquisaDocumento(false);'");
						  db_input('c53_descr', 50, $Ic53_descr, true, 'text', 3)
						?>
					</td>
				</tr>
				<tr id="trLancamentos">
					<td>
						<?php 
							db_ancora("<b>Lançamento:</b>", "js_pesquisaLancamento(true);", 1);
						?>
					</td>
					<td>
						<?php
						  db_input('c46_seqtranslan', 10, $Ic46_seqtranslan, true, 'text', 1, "onchange='js_pesquisaLancamento(false);'");
						  db_input('c46_descricao', 50, $Ic46_descricao, true, 'text', 3)
						?>
					</td>
				</tr>
			</table>
		</fieldset>
		<br>
		<input type="button" name="btnEmiteRelatorio" id="btnEmiteRelatorio" value="Emitir" />
	</form>
</center>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

	$('btnEmiteRelatorio').observe('click', function() {

	  if ($F('c53_coddoc') == "") {

			alert("Informe o documento.");
			return false;
	  }

	  if ($F('c46_seqtranslan') == "") {

		  var sMsgConfirm  = "Não foi selecionado nenhum lançamento.\nSerá emitido o relatório com todos";
		  sMsgConfirm     += " os lançamentos do documento selecionado.\n\nConfirma esta operação?";
		  if (!confirm(sMsgConfirm)) {
			  return false;
		  }
	  }

	  var sUrlRelatorio  = "con2_relatorioeventocontabil002.php?";
	  	  sUrlRelatorio += "iCodigoDocumento="+$F('c53_coddoc');
	  	  sUrlRelatorio += "&iCodigoLancamento="+$F('c46_seqtranslan');
//	  	  sUrlRelatorio += "&iAno="+$F('iAno');

    var oJanela = window.open(sUrlRelatorio, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJanela.moveTo(0,0);
	  
	});


	function js_pesquisaLancamento(lMostra) {

		var sUrlLancamento = "func_contranslan.php?iCodigoDocumento="+$F('c53_coddoc')+"&pesquisa_chave="+$F("c46_seqtranslan")+"&funcao_js=parent.js_completaLancamento";
		if (lMostra) {
		  sUrlLancamento = "func_contranslan.php?iCodigoDocumento="+$F('c53_coddoc')+"&funcao_js=parent.js_preencheLancamento|c46_seqtranslan|c46_descricao";
		}
		js_OpenJanelaIframe("", "db_iframe_contranslan", sUrlLancamento, "Pesquisa Lançamento", lMostra);
	}

	function js_preencheLancamento(iSequencialLancamento, sDescricaoLancamento) {

	  $('c46_seqtranslan').value = iSequencialLancamento;
    $('c46_descricao').value   = sDescricaoLancamento;
    db_iframe_contranslan.hide();
	}

	function js_completaLancamento(iCodigoHistorico, lErro, sDescricaoLancamento) {

	  if (sDescricaoLancamento == null) {
	    $("c46_descricao").value = iCodigoHistorico;
	  } else {
	  	$("c46_descricao").value = sDescricaoLancamento;
	  }
	  if (lErro) {
	    $("c46_seqtranslan").value = "";
	  }
	}



















	function js_pesquisaDocumento(lMostra) {
	
	  var sUrlDocumento = "";
	  if (lMostra) {
	    sUrlDocumento = "func_conhistdoc.php?funcao_js=parent.js_preencheDocumento|c53_coddoc|c53_descr";
	  } else {
	    sUrlDocumento = "func_conhistdoc.php?pesquisa_chave="+$F("c53_coddoc")+"&funcao_js=parent.js_completaDocumento";
	  }
	  js_OpenJanelaIframe("", "db_iframe_conhistdoc", sUrlDocumento, "Pesquisa Documento", lMostra);
	}
	
	function js_preencheDocumento(iCodigoDocumento, sDescricaoDocumento) {
	
	  $("c53_coddoc").value = iCodigoDocumento;
	  $("c53_descr").value  = sDescricaoDocumento;
	  db_iframe_conhistdoc.hide();
	}
	
	function js_completaDocumento(sDescricao, lErro) {

	  $("c53_descr").value = sDescricao;
	  if (lErro) {
	    $("c53_coddoc").value = "";
	  }
	}

</script>