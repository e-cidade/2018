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
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

if (trim($oGet->c53_coddoc) == "") {

  $sErro = "N�o h� documento informado para efetuar a pesquisa de transa��es.";
  db_redireciona("db_erros.php?db_erro={$sErro}");
}

$oDaoContrans    = db_utils::getDao('contrans');
$iAnoUsu         = db_getsession("DB_anousu");
$iInstituicao    = db_getsession("DB_instit");
$sWhereDocumento = " c45_coddoc = {$oGet->c53_coddoc} AND c45_anousu = {$iAnoUsu} AND c45_instit = {$iInstituicao} ";
$sSqlDocumento   = $oDaoContrans->sql_query_file(null, "*", null, $sWhereDocumento);
$rsDocumento     = $oDaoContrans->sql_record($sSqlDocumento);
if ($oDaoContrans->numrows == 0) {

  $sErro = "O documento informado n�o possui transa��es.";
  db_redireciona("db_erros.php?db_erro={$sErro}");
}
$iSeqTrans = db_utils::fieldsMemory($rsDocumento, 0)->c45_seqtrans;

$oDaoContranslan  = db_utils::getDao('contranslan');
$sWhereTransacoes = " c46_seqtrans = {$iSeqTrans} ";
$sSqlTransacoes   = $oDaoContranslan->sql_query_file(null, "*", " c46_ordem ", $sWhereTransacoes);
$rsTransacoes     = $oDaoContranslan->sql_record($sSqlTransacoes);
if ($oDaoContranslan->numrows == 0) {

  $sErro = "O documento informado n�o possui transa��es.";
  db_redireciona("db_erros.php?db_erro={$sErro}");
}
$aTransacoes = db_utils::getCollectionByRecord($rsTransacoes, false, false, true);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html" charset="iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
	<center>
		<fieldset style="margin-top:25px; margin-bottom:10px; width:340px;">
			<legend><strong>Ordenar Transa��es:</strong></legend>
			<table>
				<tr>
					<td>
						<form name="form1">
						  <select id="transacoes" style="width:250px; height: 300px;" multiple>
  							<?php
  							  foreach ($aTransacoes as $oTransacao) {

  							  	$sDescricao = trim($oTransacao->c46_descricao) == "" ? $oTransacao->c46_ordem : urldecode($oTransacao->c46_descricao);
  							    echo "<option value='{$oTransacao->c46_seqtranslan}'>{$sDescricao}</option>";
  							  }
  							?>
						  </select>
						</form>
					</td>
					<td>
						<img style="cursor:hand" onClick="js_sobeItemSelecionado();" src="imagens/seta_up.gif" width="20" height="20" border="0">
						<br><br>
						<img style="cursor:hand" onClick="js_desceItemSelecionado()" src="imagens/seta_down.gif" width="20" height="20" border="0">
						<br><br>
						<input type="button" value="Atualizar" onclick="js_salvarOrdenacao();" />
						<br><br>
						<input type="button" value="Fechar" onclick="js_cancelarOrdenacao();" />
					</td>
				</tr>
			</table>
		</fieldset>
	</center>
</body>
</html>
<script>

js_trocacordeselect();

/**
 * Fun��o do bot�o "UP", que desce o item selecionado no select
 */
function js_sobeItemSelecionado() {

  var ListaTransacoes = $('transacoes');
  if (ListaTransacoes.selectedIndex != -1 && ListaTransacoes.selectedIndex > 0) {

    var NovoIndex      = ListaTransacoes.selectedIndex - 1;
    var TextoNovoIndex = ListaTransacoes.options[NovoIndex].text.urlDecode();
    var ValorNovoIndex = ListaTransacoes.options[NovoIndex].value;
    ListaTransacoes.options[NovoIndex]          = new Option(ListaTransacoes.options[NovoIndex + 1].text.urlDecode(),
                                                             ListaTransacoes.options[NovoIndex + 1].value);
    ListaTransacoes.options[NovoIndex + 1]      = new Option(TextoNovoIndex, ValorNovoIndex);
    ListaTransacoes.options[NovoIndex].selected = true;
    js_trocacordeselect();
  }
}

/**
 * Fun��o do bot�o "DOWN", que desce o item selecionado do select
 */
function js_desceItemSelecionado() {

  var ListaTransacoes = $('transacoes');
  if (ListaTransacoes.selectedIndex != -1 && ListaTransacoes.selectedIndex < (ListaTransacoes.length - 1)) {

    var NovoIndex      = ListaTransacoes.selectedIndex + 1;
    var TextoNovoIndex = ListaTransacoes.options[NovoIndex].text.urlDecode();
    var ValorNovoIndex = ListaTransacoes.options[NovoIndex].value;
    ListaTransacoes.options[NovoIndex]          = new Option(ListaTransacoes.options[NovoIndex - 1].text.urlDecode(),
                                                             ListaTransacoes.options[NovoIndex - 1].value);
    ListaTransacoes.options[NovoIndex - 1]      = new Option(TextoNovoIndex, ValorNovoIndex);
    ListaTransacoes.options[NovoIndex].selected = true;
    js_trocacordeselect();
  }
}

/**
 * Fun��o que pega a ordem atual do componente e envia as informa��es para o RPC
 * Usa a f�rmula: Ordem no indice e c46_seqtranslan no conte�do
 */
function js_salvarOrdenacao() {

  var oPosicoesLista = $('transacoes').options;
  var aDadosOrdenacao = new Array();
  for (var i = 0;i < oPosicoesLista.length; i++) {
    aDadosOrdenacao.push(oPosicoesLista[i].value);
  }

	var oParam             = new Object();
	    oParam.aDadosOrdem = aDadosOrdenacao
	    oParam.exec        = 'ordenaTransacoes';

  js_divCarregando('Salvando a nova ordena��o','msgBox');

	var oAjax = new Ajax.Request('con4_cadastrotransacao.RPC.php',
	  	                         {method: 'post',
                                parameters: 'json='+Object.toJSON(oParam),
                                onComplete: js_retornoSalvarOrdenacao});
}

/**
 * Fun��o de retorno do RPC ap�s a tentativa de reordena��o das transa��es
 */
function js_retornoSalvarOrdenacao(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {

    alert("Altera��o da ordem das transa��es realizada com sucesso.");
    return false;
  } else {

    alert(oRetorno.message);
    return false;
  }
}

/**
 * Fun��o que fecha o frame atual.
 */
function js_cancelarOrdenacao() {
  parent.db_iframe_ordenatransacoes.hide();
}

</script>