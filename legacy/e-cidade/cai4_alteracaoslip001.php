<?PHP
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
db_postmemory($HTTP_POST_VARS);

db_app::load("scripts.js");
db_app::load("dbtextField.widget.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("DBLancador.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("classes/DBViewSlipPagamento.classe.js");
db_app::load("widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("dbcomboBox.widget.js");
db_app::load("widgets/DBToogle.widget.js");


$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$sDescricaoFieldSet  = "Alteração";
$lComponenteReadOnly = "false";
$sLabelBotao         = "Alterar";
if ($oGet->db_opcao == 3) {

  $sLabelBotao         = "Excluir";
  $lComponenteReadOnly = "true";
	$sDescricaoFieldSet  = "Exclusão";
}




?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script src="scripts/widgets/DBAncora.widget.js" type="text/javascript"></script>
<script src="scripts/widgets/dbtextField.widget.js" type="text/javascript"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >

<center>

<form name="form1" method="post" action="">

<div style="margin-top: 50px; width: 698px;">

	<fieldset >

	<legend><strong><?php echo "{$sDescricaoFieldSet} de Slip Não Autenticado" ?> </strong></legend>

		<table border="0" align='left'>

		  <tr>
		    <td nowrap >
		      <strong>
		        <? db_ancora("Slip:","js_pesquisaSlip(true);", 1); ?>
		      </strong>
		    </td>
		    <td nowrap>
		      <?
		        db_input('iSlip',10,"",true,'text',1,"onchange='js_pesquisaSlip(false);' onkeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' ");
		      ?>
		      <!--  <input name="consultaslip" type="button" id="consultaslip" value="Consulta Slip" onclick="js_consultaslip();"> -->
		    </td>
		  </tr>

	  </table>

	</fieldset>

	<div id='ctnDadosSlip' style="display: none;">
    <div id="ctnSlipPagamento"> </div>
  </div>




	<div style="margin-top: 10px;">
	  <input name="processar" type="button" id="processar" value="<?php echo $sLabelBotao; ?>" onclick="js_processar(<?php echo $oGet->db_opcao?>);">
	</div>

</div>

</form>
</center>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>


var lComponenteReadOnly = <?php echo $lComponenteReadOnly; ?>;
var oDBViewSlipPagamento = null;
var sUrlRPC = "cai4_transferencia.RPC.php";
var oGet    = js_urlToObject();


function js_getDadosSlip(iSlip){

	var oParametros              = new Object();
	oParametros.exec             = 'getDadosSlip';
	oParametros.iCodigoSlip      = iSlip;

	js_divCarregando("Aguarde, carregando dados do slip...",'msgBox');

	 var oAjaxLista  = new Ajax.Request(sUrlRPC,
	                                           {method: "post",
                                              async: false,
	                                            parameters:'json='+Object.toJSON(oParametros),
	                                            onComplete: js_retornoGetDados
	                                           });

}
function js_retornoGetDados(oAjax) {

	js_removeObj('msgBox');
	var oRetorno = eval("("+oAjax.responseText+")");


  if (oRetorno.status == "2") {
	  alert(oRetorno.message.urlDecode());
	  return false;
	}

  $('ctnSlipPagamento').innerHTML = "";
  oDBViewSlipPagamento = new DBViewSlipPagamento("oDBViewSlipPagamento", oRetorno.iTipoOperacao, 1, $('ctnSlipPagamento'), null, lComponenteReadOnly);
  oDBViewSlipPagamento.show();
  oDBViewSlipPagamento.setPCASPAtivo('<?php echo db_getsession('DB_use_pcasp');?>');
  oDBViewSlipPagamento.start();
  oDBViewSlipPagamento.setAlteracao(true);
  oDBViewSlipPagamento.oTxtCodigoSlip.setValue(oRetorno.iCodigoSlip);
  oDBViewSlipPagamento.getDadosTransferencia();

  oDBViewSlipPagamento.pesquisaCaracteristicaPeculiarCredito(false);
  oDBViewSlipPagamento.pesquisaCaracteristicaPeculiarDebito(false);

  oDBViewSlipPagamento.oTxtProcessoInput.setValue(oRetorno.k145_numeroprocesso.urlDecode());

  oDBViewSlipPagamento.oButtonSalvar.style.display   = 'none';
  oDBViewSlipPagamento.oButtonImportar.style.display = 'none';

  // completa campos
  $('tr_InstituicaoDestino_oDBViewSlipPagamento').style.display = 'none';
  if (oRetorno.iInstituicaoDestino != 'null') {

	  $('tr_InstituicaoDestino_oDBViewSlipPagamento').style.display = 'table-row';
	  $('oTxtInstituicaoDestinoCodigo').value                       = oRetorno.iInstituicaoDestino;
	  $('sDescricaoInstituicaoDestino').value                       = oRetorno.sInstituicaoDestino.urlDecode();
	}

  oDBViewSlipPagamento.verificaRecursoContaCredito();

  js_divCarregando("Aguarde, buscando finalidade de pagamento do FUNDEB...", "msgBox");

  var oParam         = new Object();
  oParam.exec        = "getFinalidadePagamentoTransferencia";
  oParam.iCodigoSlip = oDBViewSlipPagamento.oTxtCodigoSlip.getValue();

  new Ajax.Request(oDBViewSlipPagamento.sUrlRpc,
                  {method: "post",
                   parameters:'json='+Object.toJSON(oParam),
                   onComplete: function (oAjax) {

                     js_removeObj("msgBox");
                     var oRetorno = eval("("+oAjax.responseText+")");

                     if (oRetorno.lPossuiFinalidadePagamento) {

                       oDBViewSlipPagamento.oTxtCodigoFinalidadeFundeb.setValue(oRetorno.oFinalidadePagamentoFundeb.e151_codigo);
                       oDBViewSlipPagamento.oTxtDescricaoFinalidadeFundeb.setValue(oRetorno.oFinalidadePagamentoFundeb.e151_descricao.urlDecode());
                     };
                   }});
}



$('iSlip').observe('keyup', function() {

	$('processar').disabled = true;
})

function js_processar(iAcao) {

  var sMsgConfirm                       = "Confirma <?php echo $sDescricaoFieldSet ?> do slip?";
  if (oDBViewSlipPagamento.oTxtCodigoSlip.getValue() == '') {

	  alert('Selecione um slip para alteração.');
	  return false;
	}


	if (!confirm(sMsgConfirm)) {
	  return false;
	}

  if (iAcao == 3) {

    oDBViewSlipPagamento.excluir();

  } else {

    oDBViewSlipPagamento.oButtonSalvar.click();
	  js_getDadosSlip(oDBViewSlipPagamento.oTxtCodigoSlip.getValue());
  }
}

function js_retornoProcessamento(oAjax) {

	js_removeObj('msgBox');
	var oRetorno = eval("("+oAjax.responseText+")");

	alert(oRetorno.message.urlDecode());

	$('iSlip').value   = '';
	$('ctnDadosSlip').style.display = 'none';
	oDBViewSlipPagamento = null;
	location.href = 'cai4_alteracaoslip001.php?db_opcao='+oGet.db_opcao;
}

/*
 * função de esquisa para o Slip
 */
function js_pesquisaSlip(mostra) {

	$('processar').disabled = true;

  if (mostra == true) {

    var sUrl = 'func_slipAutenticacao.php?lAltera=1&funcao_js=parent.js_mostraSlip1|k17_codigo|k17_situacao';
    js_OpenJanelaIframe('',
                        'db_iframe_slip',
                        sUrl,
                        'Pesquisar Slip',
                        true);
  } else {

    if ($('iSlip').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_slip',
                          'func_slipAutenticacao.php?lAltera=1&pesquisa_chave='+$('iSlip').value+
                          '&funcao_js=parent.js_mostraSlip',
                          'Pesquisar Slip',
                          false);
     } else {

       $('iSlip').value   = '';
       $('sMotivo').value = '';
     }
  }
}

/**
 * quando digita
 */
function js_mostraSlip(chave,erro) {

  if (erro == true) {

    $('iSlip').focus();
    $('iSlip').value = '';
    $('ctnDadosSlip').style.display = 'none';
    $('processar').disabled = true;
  } else {

    js_alteraComponentes();
    $('ctnDadosSlip').style.display = 'inLine';
	  js_getDadosSlip($F('iSlip'));
	  $('processar').disabled = false;
  }
}

/**
 * quando clica
 */
function js_mostraSlip1(chave1, chave2) {

  $('iSlip').value    = chave1;

  js_alteraComponentes();

  $('ctnDadosSlip').style.display = 'inLine';
  js_getDadosSlip(chave1);

  $('iSlip').focus();
  db_iframe_slip.hide();
  $('processar').disabled = false;
}


$('processar').disabled = true;

/**
 * Essa função só limpa os campos
 */
function js_alteraComponentes() {

  //Dados do Slip
  if (oDBViewSlipPagamento != null) {

    $('legend_oDBViewSlipPagamento').innerHTML = 'Dados do Slip';
    $('btnImportar').style.display             = 'none';
    $('btnSalvar').style.display             = 'none';

    $('fieldset_motivo_anulacao_oDBViewSlipPagamento').style.display = 'none';

    $('oTxtFavorecidoInputCodigo').value    = '';
    $('oTxtFavorecidoInputDescricao').value = '';
    $('oTxtInstituicaoDestinoCodigo').value = '';
  	$('sDescricaoInstituicaoDestino').value = '';
  	$('oTxtContaDebitoCodigo').value    = '';
  	$('oTxtContaDebitoDescricao').value = '';
  	$('oTxtCaracteristicaDebitoInputCodigo').value     = '';
  	$('oTxtCaracteristicaDebitoInputDescricao').value  = '';
  	$('oTxtContaCreditoCodigo').value    = '';
  	$('oTxtContaCreditoDescricao').value = '';
  	$('oTxtCaracteristicaCreditoInputCodigo').value    = '';
  	$('oTxtCaracteristicaCreditoInputDescricao').value = '';
  	$('oTxtHistoricoInputCodigo').value    = '';
  	$('oTxtHistoricoInputDescricao').value = '';
  	$('oTxtValorInput').value = '';
  	$('observacao_oDBViewSlipPagamento').value = '';
  }
}

js_alteraComponentes();

/*
 * Lookup de pesquisa do slip
 */
function js_consultaslip(){

	  var iCodigoSlip = $F('iSlip');

	  if (iCodigoSlip == '') {

		  alert('Selecione um slip para consulta.');
		  return false;
	  }
	  js_OpenJanelaIframe('top.corpo','db_iframe_slip2','cai3_conslip003.php?slip='+iCodigoSlip,'Slip nº '+iCodigoSlip,true);
	}

$("iSlip"). value = '';

</script>