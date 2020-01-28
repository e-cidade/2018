<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/db_classesgenericas.php"));
//$clcriaabas     = new cl_criaabas;

$oGet     = db_utils::postMemory($_GET);
$db_opcao = $oGet->db_opcao;

//global contrans c45
$oRotuloContrans = new rotulo("contrans");
$oRotuloContrans->label();

//global contranslan c46
$oRotuloContransLan = new rotulo("contranslan");
$oRotuloContransLan->label();


//global conhist c50
$oRotuloConhist = new rotulo("conhist");
$oRotuloConhist->label();

//global conhist c53
$oRotuloConhist = new rotulo("conhistdoc");
$oRotuloConhist->label();

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html" charset="iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 20px;" >
<center>
  <form name="form1">
  <fieldset  style="position:relative;  width:600px; ">
  <legend><b>Transações</b></legend>
    <table border="0" width="100%">

      <!-- Transação -->
      <tr>
        <td nowrap="nowrap">
          <b><?=$Lc45_seqtrans;?></b>
        </td>
        <td width="30">
          <? db_input('c45_seqtrans', 10, $Ic45_seqtrans, true, 'text', 3);?>
        </td>
        <td nowrap="nowrap" width="80">
          <b><?=$Lc46_seqtranslan;?></b>
        </td>
        <td>
          <? db_input('c46_seqtranslan', 10, $Ic46_seqtranslan, true,'text',3);?>
        </td>
      </tr>

      <!-- Ordem //constranlan-->
      <tr>
        <td>
          <b><?=$Lc46_ordem;?></b>
        </td>
        <td colspan="4">
          <? db_input('c46_ordem',10,$Ic46_ordem,true,'text', 3);?>
        </td>
      </tr>

      <!-- Descrição //constranlan -->
      <tr>
        <td><b><?=$Lc46_descricao;?></b></td>
        <td colspan="4"><? db_input('c46_descricao',69,'', true,'text',$db_opcao);?></td>
      </tr>

      <!-- Documento //conhistdoc -->
      <tr>
        <td width="100" id="tdDocumento">
          <b><? db_ancora($Lc45_coddoc, "js_pesquisaDocumento(true);", $db_opcao);?></b>
        </td>
        <td width="100" colspan="4">
          <?
            db_input('c45_coddoc',10,$Ic45_coddoc,true,'text',$db_opcao, "onchange='js_pesquisaDocumento(false);'");
            db_input('c53_descr',55,$Ic53_descr,true,'text',3);
          ?>
        </td>
      </tr>

     <!-- Histórico //conhist -->
      <tr>
        <td>
          <b><? db_ancora($Lc50_codhist, "js_pesquisaHistorico(true);", $db_opcao, "onchange='js_pesquisaDocumento(true);'");?></b>
        </td>
        <td colspan="4">
          <?
            db_input('c50_codhist', 10, $Ic50_codhist, true, 'text', $db_opcao, "onchange='js_pesquisaHistorico(false);'");
            db_input('c50_descr', 55, $Ic50_descr, true, 'text', 3);
          ?>
          </td>
      </tr>

       <!-- Obrigatorio //contranslan -->
      <tr>
        <td><b><?=$Lc46_obrigatorio;?></b></td>
        <td colspan="4">
          <?
            $aObrigatorio = array("f" => "Não", "t" => "Sim");
            db_select('c46_obrigatorio', $aObrigatorio, true, $db_opcao);
          ?>
        </td>
      </tr>

      <!-- Observações //contranslan -->
      <tr>
        <td colspan="5">
          <fieldset>
          <legend><b><?=$Lc46_obs ;?></b></legend>
          <? db_textarea('c46_obs',"5","80", null, true, 'text', $db_opcao);?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <br>
  <span id="spanBotaoSalvar">
  	<input type="button" name="btnSalvarEventoContabil" id="btnSalvarEventoContabil" value="Salvar" />
  </span>
  <span id="spanBotaoExcluir" style="display: none;">
  	<input type="button" name="btnExcluirLancamento" id="btnExcluirLancamento" value="Excluir" />
  </span>
  <span id="spanBotaoNovoLancamento">
  	<input type="button" name="btnNovoLancamentoInclusao" id="btnNovoLancamentoInclusao" value="Novo Lançamento" onclick="js_novoLancamento();"/>
  </span>
  <span id="spanBotaoPesquisarLancamento">
  	<input type="button" name="btnPesquisarLancamento" id="btnPesquisarLancamento" value="Pesquisar Lançamentos"  />
  </span>
  <span id="spanBotaoPesquisarDocumento">
  	<input type="button" name="btnPesquisarDocumento" id="btnPesquisarDocumento" value="Pesquisar Documentos"  />
  </form>
  </spam>
</center>
</body>
</html>


<script>

	var oGet = js_urlToObject(window.location.search);

	$('btnSalvarEventoContabil').observe("click", function() {

		if ($('c46_descricao').value == "") {

			alert("Informe a descrição.");
			return false;
		}

		if ($('c45_coddoc').value == "") {

			alert("Selecione um documento.");
			return false;
		}

		if ($('c50_codhist').value == "") {

			alert("Selecione o histórico.");
			return false;
		}

		var oParam             = new Object();
		oParam.exec            = 'salvarTransacao';
		oParam.c45_seqtrans    = $F('c45_seqtrans');
		oParam.c46_seqtranslan = $F('c46_seqtranslan');
		oParam.c46_descricao   = $F('c46_descricao');
		oParam.c45_coddoc      = $F('c45_coddoc');
		oParam.c46_codhist     = $F('c50_codhist');
		oParam.c46_obrigatorio = $F('c46_obrigatorio');
		oParam.c46_obs         = $F('c46_obs');
		oParam.c46_ordem       = $F('c46_ordem');

		js_divCarregando("Aguarde, salvando dados...", "msgBox");
    var oAjax = new Ajax.Request("con4_cadastrotransacao.RPC.php",
                                {method:'post',
         											   parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_finalizaSalvarTransacao});


  });

	function js_finalizaSalvarTransacao(oAjax) {

	  js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");

		alert(oRetorno.message.urlDecode());
		if (oRetorno.status == 1) {

			$('c45_seqtrans').value               = oRetorno.iSequencialTransacao;
		  $('c46_seqtranslan').value            = oRetorno.iSequencialLancamento;
	    $('c46_ordem').value                  = oRetorno.iOrdem;
	    $('tdDocumento').innerHTML            = "<b>Documento:</b>";
	    $('c45_coddoc').readOnly              = true;
	    $('c45_coddoc').style.backgroundColor = "#DEB887";
	    $('c45_coddoc').style.color           = "#000000";

	    parent.document.formaba.contranslr.disabled = false;
	    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_contranslr.location.href   = "con1_regraeventocontabil001.php?iCodigoLancamento="+oRetorno.iSequencialLancamento;
		}
	}

	$('btnExcluirLancamento').observe('click', function() {

	  var iCodigoLancamento = $F('c46_seqtranslan');
	  if (!confirm("Confirma a exclusão do lançamento "+iCodigoLancamento+"?")) {
			return false;
	  }

	  js_divCarregando("Aguarde, excluindo evento contábil...", "msgBox");
	  var oParam               = new Object();
	  oParam.exec              = 'excluirEventoContabil';
	  oParam.iCodigoLancamento = iCodigoLancamento;
    oParam.iCodigoTransacao  = $F('c45_seqtrans');
    oParam.iCodigoDocumento  = $F('c45_coddoc');

    var oAjax = new Ajax.Request("con4_cadastrotransacao.RPC.php",
												        {method:'post',
																   parameters:'json='+Object.toJSON(oParam),
												         onComplete: js_finalizaExcluirTransacao});
  });

	function js_finalizaExcluirTransacao(oAjax) {

	  js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");
		alert(oRetorno.message.urlDecode());
		js_buscaHistoricoAlteracaoExclusao();
	}

	/**
	 * Função que monta a window contendo os lançamentos de uma transação
	 */
  function js_windowLancamentoEventoContabil(iCodigoDocumento) {

    db_iframe_conhistdoc.hide();
    var iCodigoDocumento     = iCodigoDocumento;
    var sTituloWindowAux     = "Lançamentos do Evento Contábil";
		    oWindowAuxLancamento = new windowAux("oWindowAuxLancamento_"+iCodigoDocumento, sTituloWindowAux, 800, 500);
		var sConteudoWindow      = "<fieldset>";
		    sConteudoWindow     += "<legend><b>Lançamentos Cadastrados</b></legend>";
		    sConteudoWindow     += "<div id='ctnGridLancamentos'></div>";
		    sConteudoWindow     += "</fieldset>";
		    if (oGet.db_opcao != 3) {
		    	sConteudoWindow     += "<p align='center'><input type='button' value='Novo Lançamento' id='btnNovoLancamento' onclick='js_novoLancamento();'></p>";
		    }
    oWindowAuxLancamento.setContent(sConteudoWindow);
    oWindowAuxLancamento.show();

    oWindowAuxLancamento.setShutDownFunction(function () {
      oWindowAuxLancamento.destroy();
    });

    var sTituloMsgBoard     = "Lançamentos do Evento Contábil";
    var sHelpMsgBoard       = "Dê um clique duplo sob a linha para alterar um lançamento ou clique em fechar para incluir um novo.";
    var oMsgBoardLancamento = new DBMessageBoard("oMsgBoardLancamento_"+iCodigoDocumento, sTituloMsgBoard,
                                                 sHelpMsgBoard, oWindowAuxLancamento.getContentContainer());
    oMsgBoardLancamento.show();
		oGridLancamentos              = new DBGrid('ctnGridLancamentos');
		oGridLancamentos.nameInstance = 'oGridLancamentos';
		var aHeaders = new Array("Transação", "Documento", "Lançamento", "Descrição", "Ordem");
		var aAlign   = new Array("right", "right", "right","left", "right");
		var aWidth   = new Array("10%", "10%", "10%", "60%", "10%");
		oGridLancamentos.setCellAlign(aAlign);
		oGridLancamentos.setCellWidth(aWidth);
		oGridLancamentos.setHeader(aHeaders);
		oGridLancamentos.setHeight(200);
		oGridLancamentos.show($('ctnGridLancamentos'));

		/**
		 * Chama a função que executa um RPC para buscar os lançamentos de uma transação
		 */
    js_loadLancamentosEventoContabil(iCodigoDocumento);
  }

	/**
	 * Função que executa o ajax e preenche a grid na windowAux com os lançamentos encontrados
	 */
  function js_loadLancamentosEventoContabil(iCodigoDocumento) {

    var oParam 							= new Object();
    oParam.exec             = "getLancamentosEventoContabil";
    oParam.iCodigoDocumento = iCodigoDocumento;

    $('c45_coddoc').value = iCodigoDocumento;
	  js_pesquisaDocumento(false);

		js_divCarregando("Aguarde, buscando lançamentos...", "msgBox");
    var oAjax = new Ajax.Request("con4_cadastrotransacao.RPC.php",
                                {method:'post',
         											   parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_preencheGridLancamentos});
  }

	/**
	 * Preenche a grid de lançamentos
	 */
  function js_preencheGridLancamentos(oAjax) {

    js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");

		if (oRetorno.status == 2) {

		  alert(oRetorno.message.urlDecode());
		  return false;
		}

		$('c45_seqtrans').value               = oRetorno.iTransacao;
		$('c45_coddoc').value                 = oRetorno.iCodigoDocumento;
		$('c45_coddoc').style.backgroundColor = "#DEB887";
		$('c45_coddoc').readOnly              = true;
		$('tdDocumento').innerHTML            = "<b>Documento</b>";
		oGridLancamentos.clearAll(true);
		if (oRetorno.aLancamentos.length > 0) {

		  oRetorno.aLancamentos.each(function (oLancamento, iLinha) {

		    var aLinha = new Array();
				aLinha[0]  = oLancamento.c46_seqtrans;
		    aLinha[1]  = oLancamento.c45_coddoc;
				aLinha[2]  = oLancamento.c46_seqtranslan;
			  aLinha[3]  = oLancamento.c46_descricao.urlDecode();
		    aLinha[4]  = oLancamento.c46_ordem;
		    oGridLancamentos.addRow(aLinha);
		    oGridLancamentos.aRows[iLinha].sEvents = "ondblclick='js_loadInformacoesLancamento("+oLancamento.c46_seqtranslan+");'";
		  });
		  oGridLancamentos.renderRows();
		} else {

			alert("Não existe lançamento cadastrado para o documento selecionado.");
			oWindowAuxLancamento.destroy();
		  js_buscaHistoricoAlteracaoExclusao();
		}
  }

  function js_loadInformacoesLancamento(iCodigoLancamento) {

    var oParam               = new Object();
    oParam.exec              = "getInformacaoLancamento";
    oParam.iCodigoLancamento = iCodigoLancamento;

		js_divCarregando("Aguarde, buscando informações...", "msgBox");
    var oAjax = new Ajax.Request("con4_cadastrotransacao.RPC.php",
                                {method:'post',
         											   parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_preencheFormularioLancamento});
  }

  function js_preencheFormularioLancamento(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    oWindowAuxLancamento.destroy();
    $('c45_seqtrans').value    = oRetorno.c45_seqtrans;
  	$('c46_seqtranslan').value = oRetorno.c46_seqtranslan;
  	$('c46_ordem').value       = oRetorno.c46_ordem;
  	$('c46_descricao').value   = oRetorno.c46_descricao.urlDecode();
  	$('c45_coddoc').readOnly   = true;
  	$('c45_coddoc').style.backgroundColor = '#DEB887';
  	$('c50_codhist').value     = oRetorno.c50_codhist;
  	$('c46_obrigatorio').value = 'f';
  	if (oRetorno.c46_obrigatorio == true || oRetorno.c46_obrigatorio == 'true') {
  	  $('c46_obrigatorio').value = 't';
    }
  	$('c46_obs').value         = oRetorno.c46_obs.urlDecode();
  	js_pesquisaHistorico(false);
  	(window.CurrentWindow || parent.CurrentWindow).corpo.iframe_contranslr.location.href      = "con1_regraeventocontabil001.php?iCodigoLancamento="+oRetorno.c46_seqtranslan;
  }

  /* Funções de pesquisa do Histórico */
  function js_pesquisaDocumento(lMostra) {

    var sUrlDocumento = "";
    if (lMostra) {
      sUrlDocumento = "func_conhistdoc.php?lTransacaoCadastrada=true&funcao_js=parent.js_preencheDocumento|c53_coddoc|c53_descr";
    } else {
      sUrlDocumento = "func_conhistdoc.php?lTransacaoCadastrada=true&pesquisa_chave="+$F("c45_coddoc")+"&funcao_js=parent.js_completaDocumento";
    }
    js_OpenJanelaIframe("", "db_iframe_conhistdoc", sUrlDocumento, "Pesquisa Documento", lMostra);
  }

  function js_preencheDocumento(iCodigoDocumento, sDescricaoDocumento) {

    $("c45_coddoc").value = iCodigoDocumento;
    $("c53_descr").value = sDescricaoDocumento;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_conhistdocregra.location.href = "con1_regraoperacaocontabil001.php?iCodigoDocumento="+iCodigoDocumento+"&sDescricaoDocumento="+sDescricaoDocumento;
    db_iframe_conhistdoc.hide();
  }

  function js_completaDocumento(sDescricao, lErro) {

    $("c53_descr").value = sDescricao;
    (window.CurrentWindow || parent.CurrentWindow).corpo.iframe_conhistdocregra.location.href = "con1_regraoperacaocontabil001.php?iCodigoDocumento="+$F('c45_coddoc')+"&sDescricaoDocumento="+sDescricao;
    if (lErro) {
      $("c45_coddoc").value = "";
    }
  }


  /* Funções de pesquisa do Histórico */
  function js_pesquisaHistorico(lMostra){

    var sUrlHistorico = "";

    if(lMostra){
      sUrlHistorico  =  "func_conhist.php?funcao_js=parent.js_preencheHistorico|c50_codhist|c50_descr";
    }else{
      sUrlHistorico = "func_conhist.php?pesquisa_chave="+$F("c50_codhist")+"&funcao_js=parent.js_completaHistorico";
    }
    js_OpenJanelaIframe("", "db_iframe_conhist", sUrlHistorico, "Pesquisa Histórico", lMostra);

  }

  function js_preencheHistorico(iCodigoHistorico, sDescricaoHistorico) {

    $("c50_codhist").value = iCodigoHistorico;
    $("c50_descr").value   = sDescricaoHistorico;
    db_iframe_conhist.hide();
  }

  function js_completaHistorico(sDescricao, lErro) {

    $("c50_descr").value = sDescricao;
    if (lErro) {
      $("c50_codhist").value = "";
    }
  }

  function js_pesquisaLancamento() {
    var sUrlLancamento = "func_contranslan.php";
    js_OpenJanelaIframe("", "db_iframe_contranslan", sUrlLancamento, "Pesquisa Lancamento", true);
  }

  function js_buscaHistoricoAlteracaoExclusao() {

    var sUrlEventoContabil = "func_conhistdoc.php?$lEventoContabil=true&funcao_js=parent.js_windowLancamentoEventoContabil|c53_coddoc";
    js_OpenJanelaIframe("", "db_iframe_conhistdoc", sUrlEventoContabil, "Pesquisa Documento", true);
  }

  if (oGet.db_opcao != 1) {
    js_buscaHistoricoAlteracaoExclusao();
  }

  /**
   * Quando a opção acessada for exclusão, é escondido o botão de Novo Lançamento e Incluir
   * E é mostrado o botão excluir
   */
  if (oGet.db_opcao == 3) {

		js_buscaHistoricoAlteracaoExclusao();
		$('spanBotaoSalvar').style.display         = 'none';
		$('spanBotaoNovoLancamento').style.display = 'none';
		$('spanBotaoExcluir').style.display        = '';
		parent.document.formaba.contranslr.disabled = true;
  }

  function js_novoLancamento() {

    if ($F('c45_seqtrans') != "") {

      parent.document.formaba.contranslr.disabled = true;
      js_limpaFormulario(false);

	    if (oWindowAuxLancamento != null) {
				oWindowAuxLancamento.destroy();
	    }
    }
  };



  $('btnPesquisarLancamento').observe('click', function() {
    js_windowLancamentoEventoContabil($F('c45_coddoc'));
  });

  /**
   * Chama a janela para pesquisar documentos novamente
   */
  $('btnPesquisarDocumento').observe('click', function() {

    js_limpaFormulario(true);
    js_buscaHistoricoAlteracaoExclusao();
  });

  /**
   * Limpa os dados do formulario
   */
  function js_limpaFormulario(lPesquisaDocumento = false) {

    $('c46_seqtranslan').value = '';
    $('c46_ordem').value       = '';
    $('c46_descricao').value   = '';
    $('c46_obs').value         = '';
    $('c50_codhist').value     = '';
    $('c45_coddoc').style.backgroundColor = '#DEB887';
    $('c45_coddoc').readOnly   = true;
    if(lPesquisaDocumento) {
      $('c45_seqtrans').value    = '';
    }
    $('c46_obrigatorio').value = 'f';
    $('c50_descr').value       = '';
    $('tdDocumento').innerHTML = "<b>Documento</b>";
  }
</script>