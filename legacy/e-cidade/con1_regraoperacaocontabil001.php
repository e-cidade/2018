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
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$oGet = db_utils::postMemory($_GET);

$oRotuloConhistdoctipo = new rotulo("conhistdoctipo");
$oRotuloConhistdoctipo->label();

$oRotuloConhistdoc = new rotulo("conhistdoc");
$oRotuloConhistdoc->label();

$oRotuloConhistdocRegra = new rotulo("conhistdocregra");
$oRotuloConhistdocRegra->label();

?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  </head>
  
  <body bgcolor="#CCCCCC" style="margin-top: 25px" >
  <center>
  <div style="display: table;">    
  <form id="form1" name="form1">
    <div id="ctnFormularioRegra">
    
      <div  style="width: 600; float:left">
      <fieldset style=" height:300;">  
        <legend><b>Regra Operação para o Documento</b></legend>
        <table border="0">
          
          <!-- Id Regra-->
          <tr>
            <td>
              <b>Código da Regra:</b>
            </td>
            
            <td>
              <?
              db_input('c92_sequencial', 10, $Ic92_sequencial, true, 'text', 3);
              ?>
            </td>
          </tr>
          <!-- Documento -->
          <tr>
            <td><b>Documento:</b></td>
            
            <td>
              <?
              $funcaoJsDocumento = "onchange = 'js_pesquisaDocumento(false);'";
              $c53_coddoc = $oGet->iCodigoDocumento;
              $c53_descr  = $oGet->sDescricaoDocumento;
              db_input('c53_coddoc', 10, $Ic53_coddoc, true, 'text',  3, $funcaoJsDocumento);
              db_input('c53_descr',  43, $Ic53_descr,  true, 'text' , 3);
              ?>
            </td>
          </tr>
         
         <!--  Descricao -->
         <tr>
            <td>
              <b>Descrição:</b>
            </td>
            
            <td>
              <?
              db_input('c92_descricao', 57, $Ic92_descricao, true, 'text', 1);
              ?>
            </td>
          </tr>   
        </table>  
      
        <!-- Regra Operacao Documento -->
        <fieldset>  
          <legend><b>Query</b></legend>
           <textarea id="c92_regra" name="c92_regra" rows="10" cols="70"></textarea>
        </fieldset>   
      </fieldset>
      </div>
      <div  style="width: 300; float:left">
        <fieldset style=" height:300;">
          <legend><b>Variáveis Cadastradas</b></legend>
          <div id="ctnGridVariavel">
          </div>
        </fieldset>
      </div>
      <center>
	      <input type='button' name="btnValidaRegra" value='Validar SQL' onclick = "js_validarSql();">
	      <input type='button' name="btnSalvar" value='Salvar' onclick = "js_salvar();">
        <input id="btnExcluir" type="button" name="btnExcluir" value='Excluir' onclick="js_excluir();">
      </center>
    </div>
  </form>    
  </div>
  </center>
  </body>
</html>
<script>

$("btnExcluir").disabled = true;
var sUrlDocumentoContabil = 'con4_regradocumentocontabil.RPC.php';

var aHeaders   = new Array('Variável', 'Descrição');
var aCellAlign = new Array("center", "left");
var aCellWidth = new Array("80%", "20%");


var oDataGridVariavel          = new DBGrid('ctnGridVariavel');
oDataGridVariavel.nameInstance = 'oDataGridVariavel';
oDataGridVariavel.setHeight(230);
oDataGridVariavel.setHeader(aHeaders);
oDataGridVariavel.setCellWidth(aCellWidth);
oDataGridVariavel.setCellAlign(aCellAlign);
oDataGridVariavel.show($('ctnGridVariavel'));

function js_pesquisaVariavel() {

  var iCodigoDocumento    = $F('c53_coddoc');
  var oParam              = new Object();
  oParam.exec             = 'getVariavel';
  oParam.iCodigoDocumento = iCodigoDocumento;

  if (iCodigoDocumento == "") {
    return false;
  }
    
  js_divCarregando("Aguarde, pesquisando variavel do Documento...", "msgBox");
  var oAjax = new Ajax.Request(sUrlDocumentoContabil,
      {
         method:'post',
			   parameters:'json='+Object.toJSON(oParam),
			   onComplete: js_preencheGridVariavel
			 });
}

function js_preencheGridVariavel(oAjax){

  js_removeObj("msgBox");
  var oRetornoVariavel = eval("("+oAjax.responseText+")");
  oDataGridVariavel.clearAll(true);
  
  oRetornoVariavel.aVariavel.each(function (oVariavel , iLinha) {

    var aRow = new Array();
    aRow[0]  = oVariavel.c93_variavel;
    aRow[1]  = oVariavel.c93_descricao.urlDecode();
    oDataGridVariavel.addRow(aRow);
  });  
  oDataGridVariavel.renderRows();
}

/**
 * Função que salva a Regra no banco
 */
function js_salvar() {

  if($F('c53_coddoc') == "") {
    alert("Tipo Documento não informado");
    return false;
  }

  if($F('c92_descricao') == "") {
    alert("Descrição da Regra não informada");
    return false;
  }

  if($F('c92_regra') == ""){
    alert("Regra SQL não informada");
    return false;
  }

  var sMsgConfirm  = "Confirma a alteração da regra do documento contábil?\n\n";
  sMsgConfirm     += "Esta ação implicará diretamente nos lançamentos contábeis do sistema.";
  if (!confirm(sMsgConfirm)) {
    return false;
  }
  
  var oParam                = new Object();
  oParam.exec               = 'salvarRegra';
  oParam.c92_sequencial     = $F('c92_sequencial');
  oParam.c92_conhistdoc     = $F('c53_coddoc');
  oParam.c92_descricao      = $F('c92_descricao');
  oParam.c92_regra          = $F('c92_regra');

  js_divCarregando("Aguarde, salvando dados da regra...", "msgBox");
  var oAjax = new Ajax.Request(sUrlDocumentoContabil,
                              {method:'post',
		                           parameters:'json='+Object.toJSON(oParam),
		                           onComplete: js_finalizaSalvarRegra});
}

function js_finalizaSalvarRegra(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
	alert(oRetorno.message.urlDecode());
  $('btnExcluir').disabled = false;
  $('c92_sequencial').value = oRetorno.iCodigoRegra;
}

/**
 * Função que exclui a Regra do banco
 */
function js_excluir(){
  
  var oParam                = new Object();
  oParam.exec               = 'excluirRegra';
  oParam.c92_sequencial     = $F('c92_sequencial');

  js_divCarregando("Aguarde, excluindo regra...", "msgBox");
  var oAjax = new Ajax.Request(sUrlDocumentoContabil,{
                               method:'post',
										           parameters:'json='+Object.toJSON(oParam),
										           onComplete: js_finalizaExcluirRegra});
  
}

function js_finalizaExcluirRegra(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
	alert(oRetorno.message.urlDecode());
	$('form1').reset();
	js_pesquisaVariavel();
}

/**
 * Valida o SQL de uma Regra
 */
function js_validarSql(){
  
  if($F('c92_regra') != "") {
    
    var oParam        = new Object();
    oParam.exec       = 'validaSQL';
    oParam.c92_regra  = $F('c92_regra');
    
    js_divCarregando("Aguarde, validando SQL...", "msgBox");
    var oAjax = new Ajax.Request(sUrlDocumentoContabil,{
                                 method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_finalizaValidacaoRegra});
  }
}

function js_finalizaValidacaoRegra(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
	alert(oRetorno.message.urlDecode()); 
}

function js_pesquisaRegraDocumentoCadastrado() {

  var oParam              = new Object();
  oParam.exec             = 'getRegra';
  oParam.iCodigoDocumento = $F('c53_coddoc');

  if ($F('c53_coddoc') == "") {
    return false;
  }
  js_divCarregando("Aguarde, buscando regra existente ...", "msgBox");
  var oAjax = new Ajax.Request(sUrlDocumentoContabil,{
                               method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete: js_preencheFormularioAlteracao});
  
}

function js_preencheFormularioAlteracao(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
	$("c92_sequencial").value = oRetorno.c92_sequencial;
	$("c92_descricao").value  = oRetorno.c92_descricao.urlDecode();
	$("c92_regra").value      = oRetorno.c92_regra;	

	if ($F("c92_sequencial") != "") {
		$("btnExcluir").disabled = false;
	}
	js_pesquisaVariavel();
}




/* Funções de pesquisa do Documento específico */
function js_pesquisaDocumento(lMostra) {

  if ($F('c57_sequencial') == "") {
    alert("Selecione o tipo de documento.");
    return false;
  } 
  
  var sUrlDocumento = "";
  if (lMostra) {
    sUrlDocumento = "func_conhistdoc.php?iCodigoTipoDocumento="+$F('c57_sequencial')+"&funcao_js=parent.js_preencheDocumento|c53_coddoc|c53_descr";
  } else {                                          
    sUrlDocumento = "func_conhistdoc.php?iCodigoTipoDocumento="+$F('c57_sequencial')+"&pesquisa_chave="+$F("c53_coddoc")+"&funcao_js=parent.js_completaDocumento";
  }
  js_OpenJanelaIframe("", "db_iframe_conhistdoc", sUrlDocumento, "Pesquisa Documento", lMostra);
}

function js_preencheDocumento(iCodigoDocumento, sDescricaoDocumento) {

  $("c53_coddoc").value = iCodigoDocumento;
  $("c53_descr").value = sDescricaoDocumento;
  db_iframe_conhistdoc.hide();
  js_pesquisaRegraDocumentoCadastrado();
}

function js_completaDocumento(sDescricao, lErro) {

  if (lErro) {
    $("c53_coddoc").value = "";
    $("c53_descr").value = sDescricao;
  } else {
	  $("c53_descr").value = sDescricao;
    js_pesquisaRegraDocumentoCadastrado();
  }
}

js_pesquisaVariavel();
js_pesquisaRegraDocumentoCadastrado();
</script>