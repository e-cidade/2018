<?PHP
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
require_once("classes/db_conhistdoc_classe.php");
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
db_app::load("classes/DBViewContaCorrenteDetalhe.js");
db_app::load("widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("dbcomboBox.widget.js");

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

<div style="margin-top: 50px; width: 450px;">

	<fieldset >
	
	<legend><strong>Autenticações de Recebimentos</strong></legend>
	
		<table border="0" align='left'>
		
		  <tr> 
		    <td nowrap title="Código do Slip">
		      <strong>
		        <? db_ancora("Slip:","js_pesquisaSlip(true);", 1); ?>
		      </strong>
		    </td>
		    <td nowrap>
		      <?
		        db_input('iSlip',10,"",true,'text',1,"onchange='js_pesquisaSlip(false);' onkeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' ");
		      ?>
		      <input name="consultaslip" type="button" id="consultaslip" value="Consulta Slip" onclick="js_consultaslip();">
		    </td>
		  </tr>  
		  
		  <tr id='ctnMotivo' style="display: none;">
		    <td colspan="2">
		      
		      <fieldset>
		        <legend><strong>Motivo para Estornar o Slip</strong></legend>
		        <?php db_textarea('sMotivo',5, 50, null, true, null, 1 )?>
		      </fieldset>
		      
		    </td>
		  </tr>
		  
	  </table>
	</fieldset>  
	
	
	<div style="margin-top: 10px;">
	  <input name="processar" type="button" id="processar" disabled="disabled" value="Autenticar"  onclick="js_processar(1);">
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
var sUrlRPC = "cai4_slipRPC.php";

function js_consultaslip(){

  var iCodigoSlip = $F('iSlip');
  
  if (iCodigoSlip == '') {

	  alert('Selecione um slip para consulta.');
	  return false;
  }
  js_OpenJanelaIframe('top.corpo','db_iframe_slip2','cai3_conslip003.php?slip='+iCodigoSlip,'Slip nº '+iCodigoSlip,true);
}


function js_processar(iAcao) {

	var sMotivo       = $F('sMotivo');
	var iCodigoSlip   = $F('iSlip');
	var sOperacao     = $F('processar');
  var sMsgConfirm   = "Procedimento a ser realizado: " + sOperacao  ;
      sMsgConfirm  += ".\nConfirma a operação?";

  	
  if (iCodigoSlip == '') {

	  alert('Selecione um slip para autenticação.');
	  return false;
	}

  if (sMotivo == '' && (iAcao == 2 || iAcao == '2' )) {

	  alert('Informe o motivo de estorno do slip.');
	  return false;
	}
	
	if (!confirm(sMsgConfirm)) {
	  return false;
	}

	var oParametros              = new Object();
	var msgDiv                   = "Autenticando slip.<br> Aguarde ...";
	oParametros.exec             = 'autenticarSlip';  
	oParametros.iCodigoSlip      = iCodigoSlip; 
	oParametros.iAcao            = iAcao;
	oParametros.sMotivo          = encodeURIComponent(tagString(sMotivo));

	js_divCarregando(msgDiv,'msgBox');
	 
	 var oAjaxLista  = new Ajax.Request(sUrlRPC,
	                                           {method: "post",
	                                            parameters:'json='+Object.toJSON(oParametros),
	                                            onComplete: js_retornoAutenticacao
	                                           }); 
}

function js_retornoAutenticacao(oAjax) {

	js_removeObj('msgBox');
	var oRetorno = eval("("+oAjax.responseText+")");

	alert(oRetorno.message.urlDecode());
	$('iSlip').value   = '';
	$('sMotivo').value = '';
}	

/*
 * função de esquisa para o Slip
 */
function js_pesquisaSlip(mostra) {

	$('processar').disabled = true;
	
  if (mostra == true) {

    var sUrl = 'func_slipAutenticacao.php?funcao_js=parent.js_mostraSlip1|k17_codigo|k17_situacao';
    js_OpenJanelaIframe('',
                        'db_iframe_slip',
                        sUrl,
                        'Pesquisar Slip',
                        true);
  } else {

    if ($('iSlip').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_slip',
                          'func_slipAutenticacao.php?pesquisa_chave='+$('iSlip').value+
                          '&funcao_js=parent.js_mostraSlip',
                          'Pesquisar Slip',
                          false);
     } else {
       $('iSlip').value = '';
       $('sMotivo').value = '';
     }
  }
}

function js_mostraSlip(chave,erro) {

	  if (erro == true) {

	    $('iSlip').focus();
	    $('iSlip').value = '';
	  } else {
		  js_alteraBotao(chave);
		  $('processar').disabled = false;
		}
	}

	function js_mostraSlip1(chave1, chave2) {

	  $('iSlip').value    = chave1;
    js_alteraBotao(chave2);
	  
	  $('iSlip').focus();
	  db_iframe_slip.hide();
	  $('processar').disabled = false;
	}


function js_alteraBotao(iSituacao) {

	switch (iSituacao) {

	  case "1":

	    $('processar').value = "Autenticar";
	    $('processar').onclick = function () {
	 	     js_processar(1);
		  }
	    $('ctnMotivo').style.display = "none";
		break;

	  case "2":
		  
		  $('processar').value = "Estornar";
	    $('processar').onclick= function () {
		 	     js_processar(2);
		  }		  
	    $('ctnMotivo').style.display = "table-row";
		break;
	}
	
}
$("iSlip"). value = '';
$('sMotivo').value = '';

$('iSlip').observe('focus', function () {
	
  $('processar').disabled = true;
});
$('iSlip').observe('blur', function () {
	
	  $('processar').disabled = false;
});

</script>