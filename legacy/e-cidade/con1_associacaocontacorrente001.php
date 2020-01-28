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
require_once("libs/db_utils.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

$oGet = db_utils::postMemory($_GET);

$oRotuloContaCorrente = new rotulo('contacorrente');
$oRotuloContaCorrente->label();

$oRotuloContaCorrenteRegraVinculo = new rotulo('contacorrenteregravinculo');
$oRotuloContaCorrenteRegraVinculo->label();


$iDbUsuario = db_getsession("DB_id_usuario");
$sDbUsuario = db_getsession("DB_login");
$sStyle     = "";
/*
 * validamos o usuario e o login
* sendo a rotina exclusivamente para dbseller
*/
if ( ($iDbUsuario != 1) || ($sDbUsuario != 'dbseller') ) {
   
  db_msgbox("Rotina exclusiva DBSeller, contate administrador !!");
  $sStyle = "display:none;";
}

?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html" charset="iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="margin-top: 30px;" >


<div style="<?php echo $sStyle;?>" >

<center>
  <fieldset style="width: 550px;">
    <legend><b>Associação de Conta Corrente</b></legend>
    <table>
      <tr>
        <td>
          <?php
            db_ancora("<b>Conta Corrente:</b>", "js_pesquisaContaCorrente(true);", 1);
          ?>
        </td>
        <td>
          <?php
            db_input("c17_sequencial", 10, $Ic17_sequencial, true, 'text', 1, "onchange='js_pesquisaContaCorrente(false);'");
            db_input("c17_descricao", 40, $Ic17_descricao, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <b>Estrutural:</b>
        </td>
        <td>
          <?php
            db_input("c27_estrutural", 20, $Ic27_estrutural, true, 'text', 1, null, null, null, null, 15);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <p align="center">
    <input type="button" name="btnSalvar" id="btnSalvar" value="Salvar" />
  </p>
  <fieldset style="width: 800">
    <legend><b>Contas Associadas</b></legend>
    <div id="ctnGridContasAssociadas">
    </div>
  </fieldset>
  <p align="center">
    <input type="button" name="btnExcluirSelecionados" id="btnExcluirSelecionados" value="Excluir Selecionado(s)" />
  </p>
</center>
</div>



<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));?>
</body>
</html>


<script>

  var sUrlRPC = "con4_contacorrente.RPC.php";
	var oGridContas          = new DBGrid('ctnGridContasAssociadas');
	oGridContas.nameInstance = 'oGridContas';
	var aHeaders             = new Array("Reduzido", "Estrutural", "Descrição");
	var aAlign               = new Array("center", "center", "left");
	var aWidth               = new Array("10%", "25%", "65%");
	oGridContas.setCheckbox(0);
	oGridContas.setCellAlign(aAlign);
	oGridContas.setCellWidth(aWidth);
	oGridContas.setHeader(aHeaders);
	oGridContas.setHeight(200);
	oGridContas.show($('ctnGridContasAssociadas'));

	/**
	 * Função executada quando o btnExcluirSelecionados for clicado
	 */
	$('btnExcluirSelecionados').observe('click', function() {

	  var aLinhasSelecionadas = oGridContas.getSelection("object");
		if (aLinhasSelecionadas.length == 0) {

		  alert("Nenhuma conta selecionada. Verifique.");
	    return false;
		} else {

			if (!confirm("Confirma a exclusão do vínculo das contas selecionadas com a conta corrente "+$F('c17_descricao')+"?")) {
			  return false;
			}
		}

	  var aContasExcluir      = new Array();
	  aLinhasSelecionadas.each(function (oRow, iLinha) {

		  var iCodigoConta = oRow.aCells[1].getValue();
		  aContasExcluir.push(iCodigoConta);
		});

	  js_divCarregando("Aguarde, excluindo vínculo...", "msgBox");
	  var oParam                  = new Object();
	  oParam.exec                 = "excluirVinculo";
	  oParam.iCodigoContaCorrente = $F('c17_sequencial');
	  oParam.aContas              = aContasExcluir;

    var oAjax = new Ajax.Request(sUrlRPC,
                                 {method: 'post',
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: js_concluirExclusao
                                 });
	});

	/**
	 * Mensagens chamadas de rotinas para preenchimento da grid
	 */
	function js_concluirExclusao(oAjax) {

		js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");
		alert(oRetorno.message.urlDecode());
		if (oRetorno.status == 1) {
			js_buscaDadosGrid();
		}
	}

	/**
	 * Função responsável por salvar o vínculo com a conta corrente de acordo com o estrutural informado
	 */
	$('btnSalvar').observe("click", function () {

		if ($F('c17_sequencial') == "") {

		  alert("Informe a conta corrente.");
	    return false;
		}

		if ($F('c27_estrutural').trim() == "") {

		  alert("Estrutural não informado.");
      return false;
		}

		var oParam                  = new Object();
		oParam.exec                 = "salvarVinculo";
		oParam.iCodigoContaCorrente = $F('c17_sequencial');
		oParam.sEstrutural          = $F('c27_estrutural');
		js_divCarregando("Aguarde, vinculando contas contábeis...", "msgBox");

    var oAjax = new Ajax.Request(sUrlRPC,
                                {method: 'post',
                                 parameters: 'json='+Object.toJSON(oParam),
                                 onComplete: js_completarVinculo
                                });

  });

	/**
	 * Mensagens de sucesso e rotina para buscar os dados da grid
	 */
	function js_completarVinculo(oAjax) {

		js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");

	  alert(oRetorno.message.urlDecode());
		if (oRetorno.status == 2) {
		  return false;
		}
		$('c27_estrutural').value = '';
		js_buscaDadosGrid();
	}

	/**
	 * Chamada para buscar os dados que devem ser preenchidos na grid
	 */
	function js_buscaDadosGrid() {

		js_divCarregando("Aguarde, buscando contas...", "msgBox");

		var oParam                  = new Object();
		oParam.exec                 = "getContasVinculadas";
		oParam.iCodigoContaCorrente = $F('c17_sequencial');

    var oAjax = new Ajax.Request(sUrlRPC,
                                 {method: 'post',
                                  parameters: 'json='+Object.toJSON(oParam),
                                  onComplete: js_preencherGrid
                                 });

	}

	/**
	 * Preenche os dados da grid
	 * @param oAjax
	 */
	function js_preencherGrid(oAjax) {

		js_removeObj("msgBox");
		var oRetorno = eval("("+oAjax.responseText+")");

		oGridContas.clearAll(true);
		oRetorno.aContas.each(function (oConta, iIndice) {

			var aLinha = new Array();
			aLinha[0]  = oConta.iCodigoConta;
			aLinha[1]  = oConta.sEstrutural;
			aLinha[2]  = oConta.sDescricao.urlDecode();
			oGridContas.addRow(aLinha);
		});
		oGridContas.renderRows();
	}


	/**
	 * Funções referente a lookup de pesquisa
	 */
  function js_pesquisaContaCorrente(lMostra) {

    var sUrlOpen = "func_contacorrente.php?funcao_js=parent.js_preencheContaCorrente|c17_sequencial|c17_contacorrente|c17_descricao";
    if (!lMostra) {
      sUrlOpen = "func_contacorrente.php?pesquisa_chave="+$F('c17_sequencial')+"&funcao_js=parent.js_completaContaCorrente";
    }
    js_OpenJanelaIframe('', "db_iframe_contacorrente", sUrlOpen, "Pesquisa Conta Corrente", lMostra);
  }

  function js_preencheContaCorrente(iCodigoSequencia, sSigla, sDescricao) {

	  $('c17_sequencial').value = iCodigoSequencia;
	  $('c17_descricao').value  = sSigla+" - "+sDescricao;
	  db_iframe_contacorrente.hide();
	  js_buscaDadosGrid();
  }
  function js_completaContaCorrente(sDescricao, lErro) {

	  $('c17_descricao').value = sDescricao;
	  if (lErro) {
		  oGridContas.clearAll(true);
		  $('c17_sequencial').value = '';
		  $('c27_estrutural').value = '';
	  }
	  if ($F('c17_sequencial') != "") {
		  js_buscaDadosGrid();
	  }
  }

</script>