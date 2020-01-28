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

db_app::load("scripts.js");
db_app::load("dbtextField.widget.js");
db_app::load("prototype.js");
db_app::load("datagrid.widget.js");
db_app::load("DBLancador.widget.js");
db_app::load("strings.js");
db_app::load("grid.style.css");
db_app::load("estilos.css");
db_app::load("classes/DBViewContaCorrenteDetalhe.js");
db_app::load("classes/DBViewNovoDetalhamento.js");
db_app::load("widgets/windowAux.widget.js");
db_app::load("widgets/dbmessageBoard.widget.js");
db_app::load("dbcomboBox.widget.js");
db_app::load("widgets/DBAncora.widget.js");
?>
<style>

.iNovoDetalhe {

  width: 80px;

}

.sNovoDetalhe {

  width: 400px;
  background-color: #DEB887;


}

</style>

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

<div style="margin-top: 50px; width: 700px;">

	<fieldset >

	<legend><strong>Implanta��o de Saldo de Conta Corrente</strong></legend>

		<table border="0" align='left'>

		  <tr>
		    <td nowrap title="C�digo do Reduzido">
		      <strong>
		        <? db_ancora("Reduzido:","js_pesquisaReduzido(true);", 1); ?>
		      </strong>
		    </td>
		    <td nowrap>
		      <?
		        db_input('iReduzido',10,"",true,'text',1,"onchange='js_pesquisaReduzido(false);' onkeyup='js_ValidaCampos(this,1,\"\",\"\",\"\",event);' ");
		        db_input('sDescricao',60,"",true,'text',3,'');
		      ?>
		    </td>
		  </tr>


		  <tr>
		    <td nowrap title="Descri��o">
		      <strong>
		        Descri��o da Conta:
		      </strong>
		    </td>
		    <td nowrap>
		      <?
		        db_input('iCodigoDescricao',10,"",true,'text',3);
		        db_input('sDescricaoConta',60,"",true,'text',3,'');
		      ?>
		    </td>
		  </tr>

		  <tr id = 'saldocredito' style="display: none;">
		    <td nowrap title="Saldo a Cr�dito">
		      <strong>
		        Saldo a Cr�dito:
		      </strong>
		    </td>
		    <td nowrap>
		      <?
		        db_input('saldoCredito',10,"",true,'text',3);
		      ?>
		    </td>
		  </tr>

		  <tr id = 'saldodebito' style="display: none;">
		    <td nowrap title="Saldo a Debito">
		      <strong>
		        Saldo a D�bito:
		      </strong>
		    </td>
		    <td nowrap>
		      <?
		        db_input('saldoDebito',10,"",true,'text',3);
		      ?>
		    </td>
		  </tr>
	  </table>

	</fieldset>

	   <!-------------- Detalhamento da Conta Corrente ---------------->

	<fieldset style="margin-top: 10px;">

	  <legend>
	    <strong>
	      Detalhamento da Conta Corrente
	    </strong>
	  </legend>

	  <table border="0">
	    <tr>
	      <td>
	        <div id='ctnGridDetalhamento'></div>
	      </td>
	    </tr>
	  </table>
	</fieldset>

	<div style="margin-top: 10px;">
	  <input name="processar" type="button" id="processar" value="Processar" disabled="disabled" onclick="js_processar();">
	  <input name="novo" type="button" id="novo" value="Novo Detalhamento" onclick="js_NovoDetalhamento();">

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
var sUrlRPC = "con4_contacorrente.RPC.php";
var oGridDetalhamento = null;

function js_incluirDetalhamento(){

  $('novo').disabled     = false;
	var iCodigoReduzido    = $F('iReduzido');
  var iContaCorrente     = $F('iCodigoDescricao');
	var iTipoReceita       = $F('iTipoReceita');
	var iConcarPeculiar    = $F('iConcarPeculiar');
	var iContaBancaria     = $F('iContaBancaria');
	var iEmpenho           = $F('iEmpenho');
	var iNome              = $F('iNome');
	var iOrgao             = $F('iOrgao');
	var iUnidade           = $F('iUnidade');
	var iAcordo            = $F('iAcordo');


  switch (iContaCorrente) {

	  case '1':

		  if (iConcarPeculiar == '' || iTipoReceita == '') {
        alert('Necess�rio preenchimento de todos campos.');
        return false;
			}

		break;

	  case '2':

      if (iContaBancaria == '') {

        alert('Necess�rio preenchimento de todos campos.');
        return false;
      }

		break;

	  case '3':


	      if (iNome == '') {

	          alert('Necess�rio preenchimento de todos campos.');
	          return false;
	        }
		break;

	  case '19':

	      if (iEmpenho == '' || iOrgao == '' || iNome == '' ||  iUnidade == '') {

	          alert('Necess�rio preenchimento de todos campos.');
	          return false;
	        }
		break;

	  case '25':

	      if (iNome == '' || iAcordo == '') {

	          alert('Necess�rio preenchimento de todos campos.');
	          return false;
	        }
		break;

  }


	if (iCodigoReduzido == '') {

		alert('Selecione um reduzido para o novo detalhamento.');
		return false;
  }
	if (iContaCorrente == '') {

	  alert('Conta corrente n�o encontrada.\nSelecione um reduzido com uma conta corrente.');
	  return false;
  }

	var msgDiv                  = "Incluindo Detalhamentos \nAguarde ...";
	var oParametros             = new Object();

	oParametros.exec            = 'incluirDetalhamento';
	oParametros.iCodigoReduzido = iCodigoReduzido;
	oParametros.iContaCorrente  = iContaCorrente;

	oParametros.iTipoReceita    = iTipoReceita    ;
	oParametros.iConcarPeculiar = iConcarPeculiar ;
	oParametros.iContaBancaria  = iContaBancaria  ;
	oParametros.iEmpenho        = iEmpenho        ;
	oParametros.iNome           = iNome           ;
	oParametros.iOrgao          = iOrgao          ;
	oParametros.iUnidade        = iUnidade        ;
	oParametros.iAcordo         = iAcordo         ;


	js_divCarregando(msgDiv,'msgBox');

	var oAjaxLista  = new Ajax.Request(sUrlRPC,
	                                          {method: "post",
	                                           parameters:'json='+Object.toJSON(oParametros),
	                                           onComplete: js_retornoIncluirDetalhamento
	                                          });
}

/*
 * populamos a grid com os dados retornado
 */

function js_retornoIncluirDetalhamento(oAjax) {

	js_removeObj('msgBox');
	var oRetorno = eval("("+oAjax.responseText+")");

	alert(oRetorno.message.urlDecode());

	if (oRetorno.status == '2') {
    return false;
	}

	$('ctnTipoReceita')   . value = "" ;
	$('ctnConcarPeculiar'). value = "" ;
	$('ctnContaBancaria') . value = "" ;
	$('ctnEmpenho')       . value = "" ;
	$('ctnNome')          . value = "" ;
	$('ctnOrgao')         . value = "" ;
	$('ctnUnidade')       . value = "" ;
	$('ctnAcordo')        . value = "" ;
	windowNovoDetalhes.destroy();

	oGridDetalhamento.clearAll(true);
	js_getDetalhamento();

}


function js_NovoDetalhamento() {

  $('novo').disabled = true;
	var iContaCorrente = $F('iCodigoDescricao');
	var iReduzido      = $F('iReduzido');

	if (iReduzido == '') {

		alert('Selecione um reduzido para o novo detalhamento.');
		return false;
  }

	if (iContaCorrente == '') {

	  alert('Conta corrente n�o encontrada.\nSelecione um reduzido com uma conta corrente.');
	  return false;
  }


  if ($('sTituloWindowDetalhe') ) {

    messageBoardNovo.hide();
	}

	var oNovoDetalhamento = new DBViewNovoDetalhamento('oNovoDetalhamento');
      oNovoDetalhamento.show(iContaCorrente);

}




/*
 * fun��o de Pesquisa para Acordo
 */
function js_pesquisaAcordo(mostra) {

  if (mostra == true) {

	  js_escondeNovoDetalhamento();
    var sUrl = 'func_acordo.php?lNovoDetalhe=1&funcao_js=parent.js_mostraAcordo1|ac16_sequencial';
    js_OpenJanelaIframe('',
                        'db_iframe_acordo',
                        sUrl,
                        'Pesquisar Acordo',
                        true);
  } else {

    if ($('iAcordo').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_acordo',
                          'func_acordo.php?lNovoDetalhe=1&pesquisa_chave='+$('iAcordo').value+
                          '&funcao_js=parent.js_mostraAcordo',
                          'Pesquisar Acordo',
                          false);
     } else {
       $('iAcordo').value = '';
     }
  }
}
function js_mostraAcordo(chave,erro) {

	  $('iAcordo').value = chave;
	  if (erro == true) {

	    $('iAcordo').focus();
	    $('iAcordo').value = '';
	  } else {
	  }
	}
	function js_mostraAcordo1(chave1,chave2) {

		js_exibeNovoDetalhamento();
	  $('iAcordo').value  = chave1;
	  //$('sAcordo').value = chave2 ;
	  $('iAcordo').focus();
	  db_iframe_acordo.hide();
	}



/*
 * fun��o de Pesquisa para Uidade
 */
function js_pesquisaUnidade(mostra) {

	var iOrgao = $F('iOrgao');
	if (iOrgao == '') {

		alert('Selecione um org�o.');
		return false;
  }

  if (mostra == true) {

	  js_escondeNovoDetalhamento();
    var sUrl = 'func_orcunidade.php?orgao='+iOrgao+'&funcao_js=parent.js_mostraUnidade1|o41_unidade|o41_descr';
    js_OpenJanelaIframe('',
                        'db_iframe_unidade',
                        sUrl,
                        'Pesquisar Unidade',
                        true);
  } else {

    if ($('iUnidade').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_unidade',
                          'func_orcunidade.php?orgaos='+iOrgao+'&lNovoDetalhe=1&pesquisa_chave='+$('iUnidade').value+
                          '&funcao_js=parent.js_mostraUnidade',
                          'Pesquisar Unidade',
                          false);
     } else {
       $('iUnidade').value = '';
     }
  }
}
function js_mostraUnidade(chave,erro) {

	  $('sUnidade').value = chave;
	  if (erro == true) {

	    $('iUnidade').focus();
	    $('iUnidade').value = '';
	  } else {
	  }
	}
	function js_mostraUnidade1(chave1,chave2) {

		js_exibeNovoDetalhamento();
	  $('iUnidade').value  = chave1;
	  $('sUnidade').value = chave2 ;
	  $('iUnidade').focus();
	  db_iframe_unidade.hide();
	}

/*
 * fun��o de Pesquisa para Org�o
 */
function js_pesquisaOrgao(mostra) {

  if (mostra == true) {

	  js_escondeNovoDetalhamento();
    var sUrl = 'func_orcorgao.php?funcao_js=parent.js_mostraOrgao1|o40_orgao|o40_descr';
    js_OpenJanelaIframe('',
                        'db_iframe_orgao',
                        sUrl,
                        'Pesquisar Org�o',
                        true);
  } else {

    if ($('iOrgao').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_nome',
                          'func_orcorgao.php?lNovoDetalhe=1&pesquisa_chave='+$('iOrgao').value+
                          '&funcao_js=parent.js_mostraOrgao',
                          'Pesquisar Org�o',
                          false);
     } else {
       $('iOrgao').value = '';
     }
  }
}
function js_mostraOrgao(chave,erro) {

	  $('sOrgao').value = chave;
	  if (erro == true) {

	    $('iOrgao').focus();
	    $('iOrgao').value = '';
	  } else {
	  }
	}
	function js_mostraOrgao1(chave1,chave2) {

		js_exibeNovoDetalhamento();
	  $('iOrgao').value  = chave1;
	  $('sOrgao').value = chave2 ;
	  $('iOrgao').focus();
	  db_iframe_orgao.hide();
	}


/*
 * fun��o de esquisa para o Nome
 */
function js_pesquisaNome(mostra) {

  if (mostra == true) {

	  js_escondeNovoDetalhamento();
    var sUrl = 'func_cgm.php?funcao_js=parent.js_mostraNome1|z01_numcgm|z01_nome';
    js_OpenJanelaIframe('',
                        'db_iframe_nome',
                        sUrl,
                        'Pesquisar Nome',
                        true);
  } else {

    if ($('iNome').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_nome',
                          'func_cgm.php?lNovoDetalhe=1&pesquisa_chave='+$('iNome').value+
                          '&funcao_js=parent.js_mostraNome',
                          'Pesquisar Nome',
                          false);
     } else {
       $('iNome').value = '';
     }
  }
}
function js_mostraNome(chave,erro) {

	  $('sNome').value = chave;
	  if (erro == true) {

	    $('iNome').focus();
	    $('iNome').value = '';
	  } else {
	  }
	}
	function js_mostraNome1(chave1,chave2) {

		js_exibeNovoDetalhamento();
	  $('iNome').value  = chave1;
	  $('sNome').value = chave2 ;
	  $('iNome').focus();
	  db_iframe_nome.hide();
	}



/*
 * fun��o de Pesquisa para o empenho
 */
function js_pesquisaEmpenho(mostra) {

  if (mostra == true) {

	  js_escondeNovoDetalhamento();
    var sUrl = 'func_empempenho.php?funcao_js=parent.js_mostraEmpenho1|e60_numemp|e60_codemp|e60_anousu';
    js_OpenJanelaIframe('',
                        'db_iframe_empenho',
                        sUrl,
                        'Pesquisar Empenhos',
                        true);
  } else {

    if ($('iEmpenho').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_empenho',
                          'func_empempenho.php?lNovoDetalhe=1&pesquisa_chave='+$('iEmpenho').value+
                          '&funcao_js=parent.js_mostraEmpenho',
                          'Pesquisar Empenhos',
                          false);
     } else {
       $('iEmpenho').value = '';
     }
  }
}
function js_mostraEmpenho(chave,erro) {

	  $('sEmpenho').value = chave;
	  if (erro == true) {

	    $('iEmpenho').focus();
	    $('iEmpenho').value = '';
	  } else {
	  }
	}
	function js_mostraEmpenho1(chave1,chave2, chave3) {

		js_exibeNovoDetalhamento();
	  $('iEmpenho').value  = chave1;
	  $('sEmpenho').value = chave2 + " / " + chave3;
	  $('iEmpenho').focus();
	  db_iframe_empenho.hide();
	}




/*
 * fun��o de Pesquisa para Conta bancaria
 */
function js_pesquisaContaBancaria(mostra) {

  if (mostra == true) {

	  js_escondeNovoDetalhamento();
    var sUrl = 'func_contabancaria.php?funcao_js=parent.js_mostraContaBancaria1|db83_sequencial|db83_descricao';
    js_OpenJanelaIframe('',
                        'db_iframe_ContaBancaria',
                        sUrl,
                        'Pesquisar Conta Bancaria',
                        true);
  } else {

    if ($('iContaBancaria').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_tiporeceita',
                          'func_contabancaria.php?lImplantacao=1&tp=1&pesquisa_chave='+$('iContaBancaria').value+
                          '&funcao_js=parent.js_mostraContaBancaria',
                          'Pesquisar Conta Bancaria',
                          false);
     } else {
       $('iContaBancaria').value = '';
     }
  }
}

function js_mostraContaBancaria(chave,erro) {


	  $('sContaBancaria').value = chave;
	  if (erro == true) {

	    $('iContaBancaria').focus();
	    $('iContaBancaria').value = '';
	  } else {

	  }
	}
	function js_mostraContaBancaria1(chave1,chave2) {

		js_exibeNovoDetalhamento();
	  $('iContaBancaria').value  = chave1;
	  $('sContaBancaria').value = chave2;
	  $('iContaBancaria').focus();
	  db_iframe_ContaBancaria.hide();
	}

//---------------------------------------------------------------



/*
 * fun��o de Pesquisa para Caracteristica Peculiar
 */
function js_pesquisaCaracteristicaPeculiar(mostra) {

  if (mostra == true) {

	  js_escondeNovoDetalhamento();
    var sUrl = 'func_concarpeculiar.php?funcao_js=parent.js_mostraCaracteristicaPeculiar1|c58_sequencial|c58_descr';
    js_OpenJanelaIframe('',
                        'db_iframe_CaracteristicaPeculiar',
                        sUrl,
                        'Pesquisar Caracteristica Peculiar',
                        true);
  } else {

    if ($('iConcarPeculiar').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_tiporeceita',
                          'func_concarpeculiar.php?pesquisa_chave='+$('iConcarPeculiar').value+
                          '&funcao_js=parent.js_mostraCaracteristicaPeculiar',
                          'Pesquisar Caracteristica Peculiar',
                          false);
     } else {
       $('iConcarPeculiar').value = '';
     }
  }
}

function js_mostraCaracteristicaPeculiar(chave,erro) {

	  $('sConcarPeculiar').value = chave;
	  if (erro == true) {

	    $('iConcarPeculiar').focus();
	    $('iConcarPeculiar').value = '';
	  } else {

	  }
	}
	function js_mostraCaracteristicaPeculiar1(chave1,chave2) {

		js_exibeNovoDetalhamento();
	  $('iConcarPeculiar').value  = chave1;
	  $('sConcarPeculiar').value = chave2;
	  $('iConcarPeculiar').focus();
	  db_iframe_CaracteristicaPeculiar.hide();
	}

//---------------------------------------------------------------



/*
 * fun��o de Pesquisa para o tipo de receita
 */
function js_pesquisaTipoReceita(mostra) {

  if (mostra == true) {

	  js_escondeNovoDetalhamento();
    var sUrl = 'func_orctiporec.php?funcao_js=parent.js_mostraTipoReceita1|o15_codigo|o15_descr';
    js_OpenJanelaIframe('',
                        'db_iframe_tiporeceita',
                        sUrl,
                        'Pesquisar Tipo de Receita',
                        true);
  } else {

    if ($('iTipoReceita').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_tiporeceita',
                          'func_orctiporec.php?pesquisa_chave='+$('iTipoReceita').value+
                          '&funcao_js=parent.js_mostraTipoReceita',
                          'Pesquisar Tipo de Receita',
                          false);
     } else {
       $('iTipoReceita').value = '';
     }
  }
}

function js_mostraTipoReceita(chave,erro) {

	  $('sTipoReceita').value = chave;
	  if (erro == true) {

	    $('iTipoReceita').focus();
	    $('iTipoReceita').value = '';
	  } else {

	  }
	}
	function js_mostraTipoReceita1(chave1,chave2) {

		js_exibeNovoDetalhamento();
	  $('iTipoReceita').value  = chave1;
	  $('sTipoReceita').value = chave2;
	  $('iTipoReceita').focus();
	  db_iframe_tiporeceita.hide();
	}


//---    WINDOW AUX para exibi��o dos dados da contacorrentedetalhe ======================//

function js_viewDetalhamentos(iCodigo){

  var oDetalhamento = new DBViewContaCorrenteDetalhe('oDetalhamento');
  if (oDetalhamento !== false) {
    oDetalhamento.show(iCodigo);
  }

}


function js_processar() {

	var iCodigoReduzido         = $F('iReduzido');
	var nValorCredito           = parseFloat($F("saldoCredito"));
	var nValorDebito            = parseFloat($F("saldoDebito") );
	var sTipoImplantacao        = 'credito';

	if (nValorDebito > 0 && nValorCredito == 0) {
		sTipoImplantacao = "debito";
	}

	var oParametros              = new Object();
	var msgDiv                   = "Implantando Saldo \n Aguarde ...";
	oParametros.exec             = 'implantarSaldoContaCorrente';
	oParametros.iCodigoReduzido  = iCodigoReduzido;
	oParametros.sTipoImplantacao = sTipoImplantacao;
	oParametros.aValores         = js_getValoresDetalhamento();

	js_divCarregando(msgDiv,'msgBox');

	 var oAjaxLista  = new Ajax.Request(sUrlRPC,
	                                           {method: "post",
	                                            parameters:'json='+Object.toJSON(oParametros),
	                                            onComplete: js_retornoImplantacaoSaldo
	                                           });
}

function js_retornoImplantacaoSaldo(oAjax) {

	js_removeObj('msgBox');
	var oRetorno = eval("("+oAjax.responseText+")");

	alert(oRetorno.message.urlDecode());
  if (oRetorno.status == 1) {

    $('iReduzido').value            = "";
    $('sDescricao').value           = "";
    $('iCodigoDescricao').value     = "";
    $('sDescricaoConta').value      = "";
    $('saldoCredito').value         = "";
  	$('saldocredito').style.display = "none";
  	$('saldoDebito').value          = "";
  	$('saldodebito').style.display  = "none";
  	$('TotalForCol2').innerHTML     = "0,00";
	  oGridDetalhamento.clearAll(true);
	}
}

/**
 * funcao que retorna um array de objetos
   contendo o sequencial e o valor editado pelo usuario
 */
function js_getValoresDetalhamento(){

  var nValorLinha;
  var iTotalLinhas  = oGridDetalhamento.aRows.length;
  var aValores      = new Array();
  var iPosicaoValor = 0;

  for (iLinha = 0; iLinha < iTotalLinhas; iLinha++) {

    nValorLinha = parseFloat(oGridDetalhamento.aRows[iLinha].aCells[2].getValue());
    iSequencial = oGridDetalhamento.aRows[iLinha].aCells[0].getValue();

    if ( !empty(nValorLinha) && nValorLinha > 0 ) {

    	var oValores              = new Object();
    	    oValores.iSequencial  = iSequencial;
    	    oValores.nValor       = nValorLinha;

    	aValores[iPosicaoValor] = oValores;
    	iPosicaoValor++;
    }
  }
  return aValores;
}

function js_somavalores() {

  var nValorLinha;
  var iTotalLinhas = oGridDetalhamento.aRows.length;

  var nTotal  = 0;

  for (iLinha = 0; iLinha < iTotalLinhas; iLinha++) {

    nValorLinha  = oGridDetalhamento.aRows[iLinha].aCells[2].getValue();

	  nTotal      += parseFloat(nValorLinha).toFixed(2);
  }

  $('TotalForCol2').innerHTML = js_formatar(parseFloat(nTotal).toFixed(2), 'f');
  js_habilitaBotao();

}

/*
 * fun��o contendo as valida��es para habilitar o bot�o de processar
 */

function js_habilitaBotao(){

	var nValorCredito     = $("saldoCredito").value;
	var nValorDebito      = $("saldoDebito") .value;
	var nValorDistribuido = $("TotalForCol2").innerHTML;
	var nValorSaldo       = nValorCredito;
	$('processar').disabled = true;

	if (parseFloat(nValorDebito) > 0) {
		nValorSaldo = nValorDebito;
	}

	if (nValorSaldo == nValorDistribuido) {

	  $('processar').disabled = false;
  }
}

/*
 * funcao responsavel pelo preenchimento da grid
 */
function js_getDetalhamento(){

	var iCodigoReduzido    = $F('iReduzido');
	var msgDiv             = "Buscando Detalhamentos \nAguarde ...";
	var oParametros        = new Object();

	oParametros.exec            = 'getDetalhamento';
	oParametros.iCodigoReduzido = iCodigoReduzido;


	js_divCarregando(msgDiv,'msgBox');

	if (oGridDetalhamento.hasTotalizador == true) {
	   $('TotalForCol2').innerHTML = '0.00';
	}

	var oAjaxLista  = new Ajax.Request(sUrlRPC,
	                                          {method: "post",
	                                           parameters:'json='+Object.toJSON(oParametros),
	                                           onComplete: js_retornoDetalhamento
	                                          });
}

/*
 * populamos a grid com os dados retornado
 */

function js_retornoDetalhamento(oAjax) {

	js_removeObj('msgBox');
	var oRetorno = eval("("+oAjax.responseText+")");


	if (oRetorno.status == 2) {

	  alert(oRetorno.message.urlDecode());
	  return false;
	}

  oGridDetalhamento.clearAll(true);

  $("saldoCredito").value = 0;
	$("saldoDebito") .value = 0;

  if (parseFloat(oRetorno.nSaldoCredito) > 0) {

    $("saldoCredito").value = js_formatar(oRetorno.nSaldoCredito, 'f');
    $("saldodebito").style.display = 'none';
    $("saldocredito").style.display = 'table-row';
	}

	if (parseFloat(oRetorno.nSaldoDebito)) {

    $("saldoDebito").value = js_formatar(oRetorno.nSaldoDebito, 'f');
    $("saldocredito").style.display = 'none';
    $("saldodebito").style.display  = 'table-row';
  }

  $('iCodigoDescricao').value = oRetorno.iCodigoDescricao;
  $('sDescricaoConta') .value = oRetorno.sDescricaoContaCorrente.urlDecode();

  if (oRetorno.aDados.length > 0) {

    oRetorno.aDados.each(function (oDado, iInd) {

      var aRow    = new Array();

        var nValor = oDado.nValorImplantado;

        aRow[0] = oDado.iCodigo;
        aRow[1] = oDado.sConta.urlDecode();
        aRow[2] = eval("qtditem"+iInd+" = new DBTextField('qtditem"+iInd+"','qtditem"+iInd+"','"+ nValor +"')");
        aRow[2].addStyle("text-align","right");
        aRow[2].addStyle("height","100%");
        aRow[2].addStyle("width","100px");
        aRow[2].addEvent("onBlur","qtditem"+iInd+".sValue=this.value;");
        aRow[2].addEvent("onKeyPress"," return js_teclas(event,this); ");
        aRow[2].addEvent("onChange",";js_somavalores();");
        oGridDetalhamento.addRow(aRow);
        oGridDetalhamento.aRows[iInd].aCells[0].sEvents = "ondblclick='js_viewDetalhamentos("+oDado.iCodigo+");'";
        oGridDetalhamento.aRows[iInd].aCells[1].sEvents = "ondblclick='js_viewDetalhamentos("+oDado.iCodigo+");'";

    });
    oGridDetalhamento.renderRows();
    js_somavalores();
  }
}

/*
 * fun��o de esquisa para o reduzido
 */
function js_pesquisaReduzido(mostra) {

  if (mostra == true) {

    var sUrl = 'func_conplanoreduz.php?funcao_js=parent.js_mostraReduzido1|c61_reduz|c60_descr';
    js_OpenJanelaIframe('',
                        'db_iframe_acordogrupo',
                        sUrl,
                        'Pesquisar reduzidos de contas',
                        true);
  } else {

    if ($('iReduzido').value != '') {

      js_OpenJanelaIframe('',
                          'db_iframe_acordogrupo',
                          'func_conplanoreduz.php?pesquisa_chave_reduz='+$('iReduzido').value+
                          '&funcao_js=parent.js_mostraReduzido',
                          'Pesquisar reduzidos de contas',
                          false);
     } else {
       $('iReduzido').value = '';
     }
  }
}

function js_mostraReduzido(chave,erro) {

	  js_limpaCampos();

	  $('sDescricao').value = chave;
	  if (erro == true) {

	    $('iReduzido').focus();
	    $('iReduzido').value = '';
	  } else {
		  js_getDetalhamento();
	  }
	}

	function js_mostraReduzido1(chave1,chave2) {

		js_limpaCampos();

	  $('iReduzido').value  = chave1;
	  $('sDescricao').value = chave2;
	  $('iReduzido').focus();
	  db_iframe_acordogrupo.hide();
	  js_getDetalhamento();
	}

function js_limpaCampos(){

  $('saldoDebito').value = '';
  $('saldoCredito').value = '';
  $("saldodebito").style.display = 'none';
  $("saldocredito").style.display = 'none';


  oGridDetalhamento.clearAll(true);

}



///////////////////////////////////////////////////////////

/*
 * func�ao para renderizar a grid, sem os registros
 */
function js_criaGridDetalhamento() {

  oGridDetalhamento = new DBGrid('Detalhamento');
  oGridDetalhamento.nameInstance = 'oGridDetalhamento';
  oGridDetalhamento.setCellWidth(new Array( '100px' ,
                                            '400px',
                                            '150px'
                                           ));
  oGridDetalhamento.setCellAlign(new Array( 'left'  ,
                                            'left'  ,
                                            'center'
                                           ));
  oGridDetalhamento.setHeader(new Array( 'C�digo',
                                         'Descri��o',
                                         'Saldo a Implantar'
                                        ));
  oGridDetalhamento.hasTotalizador = true;
  oGridDetalhamento.setHeight(350);
  oGridDetalhamento.show($('ctnGridDetalhamento'));
  oGridDetalhamento.clearAll(true);
}
function js_exibeNovoDetalhamento() {
	$('windowNovoDetalhes').style.display = 'inLine';
}
function js_escondeNovoDetalhamento() {

	$('windowNovoDetalhes').style.display = 'none';
}
js_criaGridDetalhamento();

$("iReduzido")       . value = '';
$("sDescricao")      . value = '';
$("iCodigoDescricao"). value = '';
$("sDescricaoConta") . value = '';
</script>