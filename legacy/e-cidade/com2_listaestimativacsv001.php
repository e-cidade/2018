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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_pcparam_classe.php");
require_once("libs/db_app.utils.php");
$clpcparam = new cl_pcparam;
$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$db_opcao = 1;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name='form1'>
<center>
	<div style='width: 400px; margin-top:50px;'>
		<fieldset>
			<legend><strong>Gerar Lista CSV de Estimativas</strong></legend>
		    <table>
					<tr>
				    <td nowrap title="Código da Abertura." style="width: 80">
				      <strong>
				        <? db_ancora("Abertura de Registro de Preço:", "js_pesquisaAbertura(true);", 1); ?>
				      </strong>
				    </td>
				    <td  style="width: 100%">
				      <?
				        db_input('iAbertura', 10, "", true, 'text', 3);
				      ?>
				    </td>
				  </tr>
		    </table>
		</fieldset>
		<input name='pesquisar' type='button' value='Gerar Arquivo' onclick='js_gerarCSV();' style="margin-top:10px;">
	</div>
</center>
</form>
    <?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>

var sUrlRPC = "com4_solicitacaoComprasRegistroPreco.RPC.php";


function js_gerarCSV(){

	var iAbertura = $F('iAbertura');

	if (iAbertura == '') {

		alert('Selecione uma estimativa.');
		return false;
	}

	var oParametros          = new Object();
	var msgDiv               = "Gerando CSV \n Aguarde ...";
	oParametros.exec         = 'gerarestimativaCSV';
	oParametros.iAbertura  = iAbertura;

	js_divCarregando(msgDiv,'msgBox');

	new Ajax.Request(sUrlRPC,
	                        {method: "post",
	                         parameters:'json='+Object.toJSON(oParametros),
	                         onComplete: js_retornoCSV
	                        });
}

function js_retornoCSV(oAjax) {

	var iAbertura = $F('iAbertura');
	js_removeObj('msgBox');
	var oRetorno = eval("("+oAjax.responseText+")");


  if (oRetorno.status == '2') {
	  alert(oRetorno.message.urlDecode());
    return false;
	}

  sLista = oRetorno.sArquivo+"#Arquivo - Resumo de Estimativas da Abertura: " + iAbertura;
  js_montarlista(sLista,"form1");

}

function js_pesquisaAbertura(mostra) {

  if (mostra == true) {

    var sUrl = 'func_solicitaregistropreco.php?funcao_js=parent.js_mostraAbertura1|pc54_solicita&departamento=false';
    js_OpenJanelaIframe('',
                        'db_iframe_abertura',
                        sUrl,
                        'Pesquisar Abertura',
                        true);
  }
}

function js_mostraAbertura(chave,erro) {

  if (erro == true) {

    $('iAbertura').focus();
    $('iAbertura').value = '';
  }
}

function js_mostraAbertura1(chave1,chave2) {

  $('iAbertura').value  = chave1;
  $('iAbertura').focus();
  db_iframe_abertura.hide();
}

$('iAbertura').value = '';
</script>