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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oGet = db_utils::postMemory($_GET);

$sComplementoLegenda = "";
$sLegendaFieldset = "Provisão de Férias";
if (isset($oGet->tipofolha) && $oGet->tipofolha == 1) {
  $sLegendaFieldset = "Provisão do Décimo Terceiro";
}

if (isset($oGet->lEstorno) && $oGet->lEstorno == "true") {
  $sComplementoLegenda = "Estorno da ";
}

$iAnoSessao = db_getsession("DB_anousu");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js");
    db_app::load("strings.js, prototype.js, estilos.css, ");
  ?>
  <style>
    .tdDescricao{
      width: 130px;
    }
  </style>
</head>
<body bgcolor="#CCCCCC" style="margin-top:30px;">
  <center>
    <form name='form1'>
      <fieldset style="width: 550px">
        <legend><b><?php echo $sComplementoLegenda.$sLegendaFieldset;?></b></legend>
        <table width="100%">
          <tr>
            <td class="tdDescricao"><b>Ano:</b></td>
            <td>
              <?php
                db_input("iAnoSessao", 10, null, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Mês de Competência:</b></td>
            <td>
              <?php
                $aMesesDisponiveis = array("0"  => "Selecione",
                                           "1"  => "Janeiro",
                                           "2"  => "Fevereiro",
                                           "3"  => "Março",
                                           "4"  => "Abril",
                                           "5"  => "Maio",
                                           "6"  => "Junho",
                                           "7"  => "Julho",
                                           "8"  => "Agosto",
                                           "9"  => "Setembro",
                                           "10" => "Outubro",
                                           "11" => "Novembro",
                                           "12" => "Dezembro");
                db_select("iMesDisponivel", $aMesesDisponiveis, false, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Conta Débito:</b></td>
            <td>
              <?php
                db_input('iContaDebito', 10, null, true, 'text', 3);
                db_input('sContaDebito', 40, null, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td><b>Conta Crédito:</b></td>
            <td>
              <?php
                db_input('iContaCredito', 10, null, true, 'text', 3);
                db_input('sContaCredito', 40, null, true, 'text', 3);
              ?>
            </td>
          </tr>
          
	         
	          <tr class="estorno">
	            <td><b>Valor Anterior:</b></td>
	            <td>
	              <?php
	                db_input('nValorAnterior', 10, null, true, 'text', 3);
	              ?>
	            </td>
	          </tr>
	          
	          <tr class="estorno">
	            <td><b>Valor da Provisão:</b></td>
	            <td>
	              <?php
	                db_input('nSaldoProvisao', 10, null, true, 'text', 3);
	              ?>
	            </td>
	          </tr>
          
          <tr class="estorno">
            <td><b>Valor do Lançamento:</b></td>
            <td>
              <?php
                db_input('nValorLancamento', 10, null, true, 'text', 3);
              ?>
            </td>
          </tr>
          
          <tr class="estorno">
            <td colspan="2">
              <fieldset>
                <legend><b>Observações</b></legend>
                <textarea name="sObservacao" id="sObservacao" style="width:100%; height: 100px" ></textarea>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <br />
      <input type="button" name="btnProcessar" id="btnProcessar" value="Processar" disabled="disabled"/>
    </form>
  </center>
  <?
	  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>

<script>

var oGet          = js_urlToObject();
var sUrlRpc       = 'con4_contabilizarprovisaofolha004.RPC.php';
var lEstorno      = oGet.lEstorno == 'true' ? true       : false;
var sCaseExecutar = oGet.lEstorno == 'true' ? 'estornar' : 'processar';
var sLocation     = document.location.href;

/**
 * Em caso de estorno deve mostrar apenas o valor do lançamento
 */
if (lEstorno) {
  
	$$('.estorno')[0].style.display = 'none';
	$$('.estorno')[1].style.display = 'none';
}

$('btnProcessar').observe('click', function() {

  if($F('iMesDisponivel') == 0) {

    alert('Selecione o mês de competência.');
    return false;
  }

  if (new Number($F('nValorLancamento')) <= 0) {

    alert("O valor do lançamento é igual e/ou menor que zero. Procedimento abortado.");
    return false;
  }

  if ($F('sObservacao').trim() == "") {

    alert("O campo observação é obrigatório.");
    return false;
  } 

  js_divCarregando('Aguarde... Processando.', 'msgBox');
  
  var oParam              = new Object();
  oParam.exec             = sCaseExecutar;
  oParam.iMes             = $F('iMesDisponivel');
  oParam.iAno             = $F('iAnoSessao');
  oParam.nValor           = $F('nValorLancamento');
  oParam.sObservacao      = $F('sObservacao');
  oParam.iCodigoDocumento = js_getCodigoDocumento();
  
  var oAjax = new Ajax.Request (sUrlRpc, { method:'post',
                                           parameters:'json=' + Object.toJSON(oParam),
                                           onComplete:js_retornoProcessar
                                         });
});

function js_retornoProcessar(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  if(oRetorno.status == 2) {
    
    alert(oRetorno.message.urlDecode());
    return false;
  }
  
  alert('Lançamento processado com sucesso.');

	document.location.href = sLocation;
}

/**
 * Busca as contas credito e debito do lancamento
 */
function js_getContasCreditoDebito() {

   js_divCarregando("Aguarde, buscando as contas crédito/débito...", "msgBox");
   var iCodigoDocumento    = js_getCodigoDocumento();
   var oParam              = new Object();
   oParam.exec             = "getContasCreditoDebito";
   oParam.iCodigoDocumento = iCodigoDocumento;

   var oAjax = new Ajax.Request (sUrlRpc,{ method:'post',
                                           parameters:'json='+Object.toJSON(oParam),
                                           onComplete:js_concluirBuscaContaCreditoDebito});
}

function js_concluirBuscaContaCreditoDebito(oAjax) {

  js_removeObj("msgBox");
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.status == 2) {

    alert(oRetorno.message.urlDecode());
    return false;
  }

  $('iContaCredito').value = oRetorno.iContaCredito;
  $('sContaCredito').value = oRetorno.sContaCredito.urlDecode();
  $('iContaDebito').value  = oRetorno.iContaDebito;
  $('sContaDebito').value  = oRetorno.sContaDebito.urlDecode();
}

/**
 * Descobrimos o codigo do documento que deve ser usado para lancamento contabil
 * @return {integer}
 */
function js_getCodigoDocumento() {

  var iCodigoDocumento = null;
  if (oGet.tipofolha == 2) {

    iCodigoDocumento = 300;
    if (lEstorno) {
      iCodigoDocumento = 301;
    }
  } else if (oGet.tipofolha == 1) {

    iCodigoDocumento = 302;
    if (lEstorno) {
      iCodigoDocumento = 303;
    }
  }
  return iCodigoDocumento;
}

function js_tipoProvisao() {

  var sTipoProvisao = null;
  if (oGet.tipofolha == 2) {

    sTipoProvisao = 'provisaoferias'; 
  } else if (oGet.tipofolha == 1) {

    sTipoProvisao = 'provisaodecimoterceiro';
  }

  return sTipoProvisao;
}  

function js_getMesDispinivel() {

  var oParam  			   = new Object();
  oParam.exec          = "getMesesProvisaoDisponivel";
  oParam.sTipoProvisao = js_tipoProvisao();
  oParam.lProcessado   = oGet.lEstorno == 'true' ? false : true;

  js_divCarregando("Carregando meses, aguarde...", "msgBox");
  
  var oAjax = new Ajax.Request (sUrlRpc,{ method:'post',
                                          parameters:'json='+Object.toJSON(oParam),
                                          asynchronous: false,
                                          onComplete:js_preencheMesDispinivel
                                        });
 }
 
 /**
  * Preenche o combobox com os meses que o usuário pode depreciar.
  */
function js_preencheMesDispinivel(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if ( oRetorno.status == 2 ) {

    $('sObservacao').disabled = true;
    $('sObservacao').style.backgroundColor = '#DEB887';
    $('iMesDisponivel').disabled = true;
    $('btnProcessar').disabled = true;
    
 	 alert(oRetorno.message.urlDecode());
 	 	  
 	 return false;
  } 

  $('iAnoSessao').value = oRetorno.iAnoDisponivel;

  /**
   * Percorre o array de meses bloqueando os meses que nao estao disponiveis
   */
  for (var i = 1; i <= 12; i++) {

    if (i != oRetorno.iMesDisponivel) {

    	$('iMesDisponivel').options[i].disabled = true;
    }
  }

  $('iMesDisponivel').value = oRetorno.iMesDisponivel;

  js_getDadosProvisao(oRetorno.iMesDisponivel);
}

 /**
  * Busca os valores da previsao
  */
function js_getDadosProvisao(iMesDisponivel) {

  var oParam  			      = new Object();
  oParam.exec             = "getDadosProvisao";
  oParam.iCodigoDocumento = js_getCodigoDocumento();
  oParam.iMes             = iMesDisponivel;
  oParam.iAno             = $F('iAnoSessao');
	oParam.lEstorno         = lEstorno;
	oParam.sTipoLancamento  = 'provisaoDecimoTerceiro';
	
	if (oGet.tipofolha == 2) {
		oParam.sTipoLancamento  = 'provisaoFerias';
	} 
	
  js_divCarregando("Carregando da, aguarde...", "msgBox");
  
  var oAjax = new Ajax.Request (sUrlRpc,{ method:'post',
                                          parameters:'json='+Object.toJSON(oParam),
                                          asynchronous: false,
                                          onComplete:js_retornoDadosPrevisao
                                        });
}
 
function js_retornoDadosPrevisao(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("(" + oAjax.responseText + ")");

  if (oRetorno.status == 2) {
    alert(oRetorno.urlDecode());
  }

  $('nValorAnterior').value   = oRetorno.nSaldoAnterior;
  $('nSaldoProvisao').value   = oRetorno.nValorProvisao;  
  $('nValorLancamento').value = oRetorno.nValorLancamento;
} 

/**
 * Verificamos se o campo observacao foi devidamente preenchido
 */
$('sObservacao').observe('keyup', function() {

  if ($F('sObservacao').trim() == "") {
    $('btnProcessar').disabled = true;
  } else {
    $('btnProcessar').disabled = false;
  }
});

js_getContasCreditoDebito();
js_getMesDispinivel();
</script>