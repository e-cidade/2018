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
$clconhistdoc = new cl_conhistdoc;
$clconhistdoc->rotulo->label();

$oRotuloConLancam = new rotulo('conlancam');
$oRotuloConLancam->label('c70_codlan');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script src="scripts/widgets/DBAncora.widget.js" type="text/javascript"></script>
<script src="scripts/widgets/dbtextField.widget.js" type="text/javascript"></script>
<?php
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
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >


<center>

<form name="form1" method="post" action="">

	<fieldset style="margin-top: 50px; width: 700px;">

	<legend><strong>Reprocessar Lançamentos de Saída de Materiais</strong></legend>

	<table border="0">

	  <tr>
	    <td nowrap title="Documento a ser reprocessado">
	      <strong>
	        <? db_ancora("Documento:","js_pesquisaDocumento(true);", 1); ?>
	      </strong>
	    </td>
	    <td nowrap>
	      <?
	      //js_ValidaCampos(this,1,'Reduzido','t','f',event);
	        db_input('iDocumento',10,"",true,'text',1,"onchange='js_pesquisaDocumento(false);'onkeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' ");
	        db_input('sDescricao',40,"",true,'text',3,'');
	      ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="Documento a ser reprocessado">
	      <strong>
	        Data:
	      </strong>
	    </td>
	    <td nowrap>
              <?php
                db_inputdata("dtInicial", "", "", "", true, "text", 1, "");
                echo "<strong> até </strong>";
                db_inputdata("dtFinal", "", "", "", true, "text", 1, "");
              ?>
	    </td>
	  </tr>
	  <tr>
	    <td nowrap title="Código do Lançamento Contábil">
	      <strong>
	        Código do Lançamento:
	      </strong>
	    </td>
	    <td nowrap>
        <?php
          db_input("c70_codlan", 10, $Ic70_codlan, true, 'text', 1);
        ?>
	    </td>
	  </tr>

	  <tr id="trLancadorRequisicao" style="display:none;">
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
var sUrlRPC = "con4_reprocessalancamentomaterial004.RPC.php";


function js_processar() {

	var iCodigoDocumento  = $F('iDocumento');
	var dtInicial         = js_formatar($F('dtInicial'), 'd');
	var dtFinal           = js_formatar($F('dtFinal')  , 'd');
	var aRequisicoes      = oLancadorRequisicao.getRegistros();

	if (iCodigoDocumento == '') {

		alert('Selecione um Documento.');
		return false;
  }

	if ($F('c70_codlan') == "") {

    if ((dtInicial == "" || dtFinal == "") && iCodigoDocumento == 404) {

      alert("Para o documento 404 - Saída Manual, é necessário informar um intervalo de datas.");
      return false;
    }

  	if (($F('dtInicial') != "") && js_comparadata($F('dtInicial'), '31/12/2012',"<")) {

  	   alert("Data informada inválida.\nPeriodo mínimo 01/01/2013.");
  	   return false;

  	}
  	if (($F('dtFinal') != "") && js_comparadata($F('dtFinal'), '31/12/2012',"<")) {

  		 alert("Data informada inválida.\nPeriodo mínimo 01/01/2013.");
  		 return false;
  	}

    if (($F('dtFinal') != "") &&  js_comparadata($F('dtInicial'), $F('dtFinal'),">")) {

      alert("Data informada inválida.\nData final menor que a data inicial.");
      return false;

     }

  	if (dtInicial == "" && dtFinal == "" && aRequisicoes.length == 0) {

  		alert("Necessário filtros além do Documento \n Verifique intervalo de datas ou selecione requisições.");
  		return false;
    }
	}

	var sMsgConfirm = "Confirma o reprocessamento das saídas de materiais?\n\n";
	sMsgConfirm    += "O procedimento poderá demorar dependendo da quantidade de registros encontrados.";

	if (!confirm(sMsgConfirm)) {
	  return false;
	}

	
	  var oParametros               = new Object();
	  var msgDiv                    = "Processando lançamentos, aguarde ...";
	  oParametros.exec              = 'reprocessarLancamentoMaterial';
	  oParametros.dtInicial         = dtInicial;
	  oParametros.dtFinal           = dtFinal;
	  oParametros.iCodigoDocumento  = iCodigoDocumento;
	  oParametros.aRequisicoes      = aRequisicoes;
	  oParametros.iCodigoLancamento = $F('c70_codlan');

	  js_divCarregando(msgDiv,'msgBox');

	  var oAjax = new Ajax.Request(sUrlRPC,
	                          {method: "post",
	                           parameters:'json='+Object.toJSON(oParametros),
	                           onComplete: js_retornoProcessamento
	                          });
}

function js_retornoProcessamento(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());
}

function js_pesquisaDocumento(mostra) {

  if (mostra == true) {

    var sUrl = 'func_conhistdocReprocessaLancamentoSaidaMaterial.php?funcao_js=parent.js_mostraaDocumento1|c53_coddoc|c53_descr';
    js_OpenJanelaIframe('',
                        'db_iframe_acordogrupo',
                        sUrl,
                        'Pesquisar Grupos de Acordo',
                        true);
  } else {

    if ($('iDocumento').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_acordogrupo',
                          'func_conhistdocReprocessaLancamentoSaidaMaterial.php?pesquisa_chave='+$('iDocumento').value+
                          '&funcao_js=parent.js_mostraDocumento',
                          'Pesquisar Grupos de Acordo',
                          false);
     } else {
       $('iDocumento').value = '';
       $('sDescricao').value = '';
     }
  }
  js_validarDocumento();
}

function js_mostraDocumento(chave,erro) {

  $('sDescricao').value = chave;
  if (erro == true) {

    $('iDocumento').focus();
    $('iDocumento').value = '';
  }
  js_validarDocumento();
}

function js_mostraaDocumento1(chave1,chave2) {

  $('iDocumento').value = chave1;
  $('sDescricao').value = chave2;
  $('iDocumento').focus();
  js_validarDocumento();
  db_iframe_acordogrupo.hide();
}

/**
 * Função que libera ou bloqueia o componente que permite ao usuário lançar requisições
 */
function js_validarDocumento() {

  if ($F('iDocumento') == 400 || $F('iDocumento') == 401) {

	  js_criaLancador();
    $('trLancadorRequisicao').style.display = '';
    
  } else {
	  js_limpaLancador();
    js_criaLancador();
    $('trLancadorRequisicao').style.display = 'none';
  }
  return true;
}

function js_criaLancador(){
	
	oLancadorRequisicao = new DBLancador("oLancadorRequisicao");
	oLancadorRequisicao.setNomeInstancia("oLancadorRequisicao");
	oLancadorRequisicao.setLabelAncora("Requisição: ");
	oLancadorRequisicao.setParametrosPesquisa("func_conlancam_material_estoque.php", ['m40_codigo', 'descrdepto'], "");
	oLancadorRequisicao.show($("ctnLancador"));
}

function js_limpaLancador(){

	  delete oLancadorRequisicao;
	  $("ctnLancador").innerHTML = '';
}


js_criaLancador();
</script>