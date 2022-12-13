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
db_postmemory($HTTP_POST_VARS);
$clconhistdoc = new cl_conhistdoc;
$clconhistdoc->rotulo->label();

db_app::load("scripts.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("DBLancador.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("classes/dbViewAvaliacoes.classe.js");
db_app::load("widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("dbcomboBox.widget.js");
//c60_descr
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

	<fieldset style="margin-top: 50px; width: 700px;">

	<legend><strong>Reprocessar Conta Corrente</strong></legend>

	<table border="0">

	  <tr>
	    <td nowrap title="Conta a ser reprocessada">
	      <strong>
	        <? db_ancora("Conta Corrente:","js_pesquisaConta(true);", 1); ?>
	      </strong>
	    </td>
	    <td nowrap>
	      <?
	        db_input('iContaCorrente',10,"",true,'text',1,"onchange='js_pesquisaConta(false);'onkeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);'");
	        db_input('sDescricao',40,"",true,'text',3,'');
	      ?>
	    </td>
	  </tr>

	  <tr>
	    <td nowrap>
	      <strong>
	        Mês:
	      </strong>
	    </td>
	    <td nowrap>
	      <?php
	        $aMeses = array("00" => "Selecione",
                          "01" => "Janeiro",
	                        "02" => "Fevereiro",
                          "03" => "Março",
                          "04" => "Abril",
                          "05" => "Maio",
                          "06" => "Junho",
                          "07" => "Julho",
                          "08" => "Agosto",
                          "09" => "Setembro",
                          "10" => "Outubro",
                          "11" => "Novembro",
                          "12" => "Dezembro");
	        db_select("iMes", $aMeses, true, 1);
	      ?>
	    </td>
	  </tr>

	  <tr>
	    <td colspan="3">
	      <div id='ctnLancador'></div>
	    </td>
	  </tr>

	  </table>

</fieldset>

<div style="margin-top: 10px;">
  <input name="processar" type="button" id="processar" value="Processar" onclick="js_processar();">
</div>

</form>

</center>


<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>
var sUrlRPC = "con4_contacorrente.RPC.php";



function js_processar() {

	var iCodigoContaCorrente  = $F('iContaCorrente');
	var iMes                  = $F('iMes');
	var aContaCorrente        = oLancadorConta.getRegistros();

	if (iCodigoContaCorrente == '' || iCodigoContaCorrente == null) {

		alert('Selecione uma conta corrente.');
		return false;
  }

  if (iMes == '00') {

    alert("Selecione o mês à ser processado.");
    return false;
  }

  if (aContaCorrente.length == 0) {

    var sMsgConfirm  = "Nenhum detalhamento selecionado, por este motivo o procedimento poderá demandar algum tempo.\n\n";
        sMsgConfirm += "Confirma a operação?";
    if (!confirm(sMsgConfirm)) {
      return false;
    }
  } else {

    if (!confirm("Confirma o reprocessamento do saldo dos detalhamentos da conta corrente selecionada?")) {
      return false;
    }
  }


	var oParametros                   = new Object();
	var msgDiv                        = "Reprocessando conta corrente.<br>Aguarde ...";
	oParametros.exec                  = 'reprocessarContaCorrente';
	oParametros.iMes                  = iMes;
	oParametros.iCodigoContaCorrente  = iCodigoContaCorrente;
	oParametros.aContaCorrente        = aContaCorrente;

	js_divCarregando(msgDiv,'msgBox');

	 var oAjaxLista  = new Ajax.Request(sUrlRPC,
	                                           {method: "post",
	                                            parameters:'json='+Object.toJSON(oParametros),
	                                            onComplete: js_retornoContaCorrente
	                                           });
}

function js_retornoContaCorrente(oAjax) {

	  js_removeObj('msgBox');
	  var oRetorno = eval("("+oAjax.responseText+")");
	  alert(oRetorno.message.urlDecode());
	}


function js_pesquisaConta(mostra) {

  if (mostra == true) {

    var sUrl = 'func_contacorrente.php?funcao_js=parent.js_mostraConta1|c17_sequencial|c17_descricao';
    js_OpenJanelaIframe('',
                        'db_iframe_contacorrente',
                        sUrl,
                        'Pesquisar Conta Corrente',
                        true);
  } else {

    if ($('iContaCorrente').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_contacorrente',
                          'func_contacorrente.php?lReprocessa=1&pesquisa_chave=' + $('iContaCorrente').value +
                          '&funcao_js=parent.js_mostraConta',
                          'Pesquisar Conta Corrente',
                          false);
     } else {

    	 js_limpaLancador();
    	 $('iContaCorrente').value = '';
       $('sDescricao').value     = '';
     }
  }
}


function js_mostraConta(chave,erro) {

	  $('sDescricao').value = chave;
	  if (erro == true) {

		  js_limpaLancador();
	    $('iContaCorrente').focus();
	    $('iContaCorrente').value = '';

	  } else {
		  js_criaLancador();
		}
	}

	function js_mostraConta1(chave1,chave2) {

	  $('iContaCorrente').value = chave1;
	  $('sDescricao').value     = chave2;
	  $('iContaCorrente').focus();

	  db_iframe_contacorrente.hide();
	  js_criaLancador();
	}

function js_criaLancador(){

	oLancadorConta = new DBLancador("oLancadorConta");
	oLancadorConta.setNomeInstancia("oLancadorConta");
	oLancadorConta.setLabelAncora("Detalhamento: ");
	oLancadorConta.setParametrosPesquisa("func_contacorrentedetalhe.php", ['c19_sequencial', 'dl_descrição'], "c17_sequencial=" + $F('iContaCorrente'));
	oLancadorConta.show($("ctnLancador"));

}

function js_limpaLancador(){

  delete oLancadorConta;
  $("ctnLancador").innerHTML = '';
}

$('iContaCorrente').value = '';
$('sDescricao').value     = '';
js_criaLancador();
</script>