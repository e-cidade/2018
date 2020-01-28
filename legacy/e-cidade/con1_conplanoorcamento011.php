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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_libcontabilidade.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification('dbforms/db_funcoes.php'));
require_once(modification('classes/db_conparametro_classe.php'));

$clrotulo = new rotulocampo;
$clrotulo->label("c60_estrut");
$clrotulo->label("c60_estrut");
$clrotulo->label("c52_descr");
$clrotulo->label("c61_reduz");
$clrotulo->label("c51_descr");
$clrotulo->label("c60_descr");
$clrotulo->label("c60_finali");
$clrotulo->label("codigo");
$clrotulo->label("c61_codigo");
$clrotulo->label("o15_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("c90_estrutsistema");
$clrotulo->label("c60_estrut");
$clrotulo->label("c60_naturezasaldo");
$clrotulo->label("c64_descr");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js");
  db_app::load("prototype.js");
  db_app::load("strings.js");
  db_app::load("dbautocomplete.widget.js");
  db_app::load("DBViewContaBancaria.js");
  db_app::load("dbmessageBoard.widget.js");
  db_app::load("estilos.css");
  db_app::load("dbtextField.widget.js");
  db_app::load("dbcomboBox.widget.js");
  db_app::load("prototype.maskedinput.js");
  db_app::load("windowAux.widget.js");
?>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
  select {width: 98%;}
  textarea {width: 100%;}
  input#c90_estrutcontabil:disabled{background-color: #DEB887;
                                    color:black}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
	<table width="790" border="0" cellspacing="0" cellpadding="0">
	  <tr>
	    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	       <br>
		      <form name="form1" id="form1" method="post" action="">
	          <center>
					    <table border="0" cellspacing="0" cellpadding="0">
					      <tr>
					        <td>
					          <fieldset>
                      <legend><b>Conta Orçamentária</b></legend>
					            <table>
                        <tr>
                          <td><b>Código da Conta:</b></td>
                          <td>
                            <?
                              db_input("iCodigoConta", 10, null, true, 'text', 3);
                            ?>
                          </td>
                        </tr>
					              <tr>
                    	    <td>
                    	       <b>Estrutural Contabilidade:</b>
                    	    </td>
                    	    <td>
                             <?
                             $mascara = '0.0.0.0.0.00.00.00.00.00';
                             db_input('mascara', 25, $Ic60_estrut, true, 'text', 3);
                             ?>
                          </td>
                    	  </tr>
                    	  <tr>
                          <td>
                             <b>Estrutural Contabilidade:</b>
                          </td>
                          <td>
                             <?
                             db_input('c90_estrutcontabil', 25, $Ic60_estrut, true, 'text', $db_opcao);
                             ?>
                          </td>
                        </tr>
                        <tr>
					                <td nowrap="nowrap" title="Descrição do plano de contas"><b>Descrição da conta:</b></td>
					                <td>
					                  <?db_input('c60_descr', 60, @$Ic60_descr, true, 'text', $db_opcao)?>
					                </td>
					              </tr>
                        <tr>
                          <td nowrap="nowrap" title="Tipo da conta"><b>Tipo de conta:</b></td>
                          <td>
                            <?
                            $aTiposConta = array (
                              'analitica' => 'Analítica',
                              'sintetica' => 'Sintética'
                            );
                            db_select('sTipoConta', $aTiposConta, true, $db_opcao, 'onChange="js_selecionaTipoConta()"');
                            ?>
                          </td>
                        </tr>
					              <tr id="linha_vinculo_pcasp">
					                <td nowrap="nowrap">
					                  <?db_ancora('<b>Vinculo PCASP:</b>', 'js_pesquisaContaPCASP(true);', $db_opcao)?>
					                </td>
                          <td>
                            <?
                              db_input('c72_conplano', 10, @$Ic72_conplano, true, 'text', $db_opcao, " onchange='js_pesquisaContaPCASP(false);' ");
					                    db_input('c60_descrPcasp', 46, @$Ic60_descrestrutcontabil, true, 'text', 3, "");
                            ?>
                          </td>
					              </tr>
					              <tr id="linha_estrutura_vinculada">
					                <td nowrap="nowrap">
					                  <b>Estrutural Vinculado:</b>
					                </td>
                          <td>
                            <?
					                    db_input('c60_estrut_pcasp', 25, @$Ic60_estrut_pcasp, true, 'text', 3, "");
                            ?>
                          </td>
					              </tr>
					              <tr>
					                <td nowrap="nowrap"><b>Natureza de Saldo:</b></td>
					                <td>
					                  <?
					                    $aTiposNaturezaSaldo = array (
					                      "1" => "Saldo Devedor",
					                      "2" => "Saldo Credor",
					                      "3" => "Ambos"
					                    );
					                    db_select('c60_naturezasaldo', $aTiposNaturezaSaldo, true, $db_opcao);
					                  ?>
					                </td>
					              </tr>
                        <tr>
                          <td nowrap="nowrap" title="Finalidade do plano de contas" valign="top" colspan="2">
                            <fieldset>
                              <legend><b>Finalidade:</b></legend>
                              <?db_textarea('c60_finali', 0, 70, $Ic60_finali, true, 'text', $db_opcao, "");?>
                            </fieldset>
                          </td>
                        </tr>
					            </table>
					          </fieldset>
					          <br>
					          <center>
					            <input type="button" name="acao"   id="acao" onclick="js_acaoConta();" value="Salvar" />
					            <input type="button" name="pesquisarConta" id="pesquisarConta" onclick="js_pesquisarConta();" value="Pesquisar" />
					          </center>
					        </td>
					      </tr>
					    </table>
					  </center>
					</form>
	    </td>
	  </tr>
	</table>
</center>
</body>
</html>
<script>
var sUrlRPC  = 'con1_conplanoorcamento.RPC.php';
var oGet     = js_urlToObject(window.location.search);

if (oGet.db_opcao != 1) {

  if (oGet.db_opcao == 3) {
    $("acao").value = "Excluir";
  }
  js_pesquisarConta();
}

new MaskedInput("#c90_estrutcontabil",
                $F('mascara'),
                {placeholder:"0"});


function js_mascaraEstrutural() {
  new MaskedInput("#c60_estrut_pcasp",
                  $F('mascara'),
                  {placeholder:"0"});
}

function js_selecionaTipoConta() {

  if ($('sTipoConta').value == 'analitica') {
    $('linha_vinculo_pcasp').show();
    $('linha_estrutura_vinculada').show();
  }

  if ($('sTipoConta').value == 'sintetica') {
    $('linha_vinculo_pcasp').hide();
    $('linha_estrutura_vinculada').hide();
  }
}

/**
 *
 */
function js_pesquisac60_codsis(mostra) {

  if (mostra === true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta',
                        'db_iframe_consistema',
                        'func_consistema.php?funcao_js=parent.js_mostraconsistema|c52_codsis|c52_descr',
                        'Pesquisa', true, '0');
  } else {

    var sValorCampo = document.form1.c60_codsis.value;
    if (sValorCampo !== '') {
      js_OpenJanelaIframe ('CurrentWindow.corpo.iframe_conta',
                           'db_iframe_consistema',
                           'func_consistema.php?pesquisa_chave='+sValorCampo+'&funcao_js=parent.js_mostraconsistema',
                           'Pesquisa', false);
    } else {
      document.form1.c60_codsis.value = '';
    }
  }
}

/**
 *
 */
function js_mostraconsistema () {

  if (arguments[1] === true) {

    document.form1.c60_codsis.value = '';
    document.form1.c52_descr.value = arguments[0];
    document.form1.c60_codsis.focus();
  } else if(arguments[1] === false) {
    document.form1.c52_descr.value = arguments[0];
  } else {

    document.form1.c60_codsis.value = arguments[0];
    document.form1.c52_descr.value = arguments[1];
  }
  db_iframe_consistema.hide();
  js_mostraContaBancaria();

}

function js_mostraContaBancaria() {

  if (document.form1.c60_codsis.value !== '6') {
    document.getElementById('CadastroBanco').style.display = "none";
  } else {
    document.getElementById('CadastroBanco').style.display = "block";
  }
}

/**
 *
 */
function js_pesquisac60_codcla(mostra) {

  if (mostra === true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta',
                        'db_iframe_conclass',
                        'func_conclass.php?funcao_js=parent.js_mostraconclass|c51_codcla|c51_descr',
                        'Pesquisa', true, '0');
  } else {

    var sValorCampo = document.form1.c60_codcla.value;
    if (sValorCampo !== '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta',
                          'db_iframe_conclass',
                          'func_conclass.php?pesquisa_chave='+sValorCampo+'&funcao_js=parent.js_mostraconclass',
                          'Pesquisa', false);
    } else {
      document.form1.c60_codcla.value = '';
    }
  }
}

/**
 *
 */
function js_mostraconclass() {

  if (arguments[1] === true) {

    document.form1.c60_codcla.value = '';
    document.form1.c51_descr.value = arguments[0];
    document.form1.c60_codcla.focus();
  } else if(arguments[1] === false) {
    document.form1.c51_descr.value = arguments[0];
  } else {

    document.form1.c60_codcla.value = arguments[0];
    document.form1.c51_descr.value = arguments[1];
  }
  db_iframe_conclass.hide();
}

/**
 *
 */
function js_pesquisaSubsistema(mostra) {

  if (mostra === true) {
    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta',
                        'db_iframe_subsistema',
                        'func_consistemaconta.php?funcao_js=parent.js_mostraSubsistema|c65_sequencial|c65_descricao',
                        'Pesquisa', true, '10');
  } else {

    var sValorCampo = document.form1.c60_consistemaconta.value;
    if (sValorCampo !== '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta',
                          'db_iframe_subsistema',
                          'func_consistemaconta.php?pesquisa_chave='+sValorCampo+'&funcao_js=parent.js_mostraSubsistema',
                          'Pesquisa', false);
    } else {
      document.form1.c65_descricao.value = '';
    }
  }
}

/**
 *
 */
function js_mostraSubsistema() {

  if (arguments[1] === true) {

    document.form1.c60_consistemaconta.value = '';
    document.form1.c65_descricao.value = arguments[0];
    document.form1.c60_consistemaconta.focus();
  } else if (arguments[1] === false) {
    document.form1.c65_descricao.value = arguments[1];
  } else {

    document.getElementById('c60_consistemaconta').value = arguments[0];
    document.form1.c65_descricao.value = arguments[1];
  }
  db_iframe_subsistema.hide();
}

/**
 *
 */
function js_validaDadosFormulario() {

  var lRetorno = true;

  if ($("c90_estrutcontabil").value === '') {

    alert('O campo Estrutural é de preenchimento obrigatório.');
    return false;
  } else if ($("c90_estrutcontabil").value.substring(0, 1) == "0") {

    alert("O campo Estrutural não pode iniciar com zero.");
    return false;
  } else if (document.form1.c60_descr.value === '') {

    alert('O campo Descrição é de preenchimento obrigatório.');
    return false;
  } else if ($('sTipoConta').value == 'analitica' && $('c72_conplano').value === '') {

    alert('O campo Vínculo PCASP é de preenchimento obrigatório para contas do tipo Analítica.');
    return false;
  } else {
    return true;
  }
}

/**
 *
 */
function js_acaoConta() {


  var oParam = new Object();

  if (oGet.db_opcao == 3) {

    js_divCarregando('Aguarde, excluindo conta', 'msgBox');
    oParam.exec         = "excluirConta";
    oParam.iCodigoConta = $F("iCodigoConta");

  } else {

    if (js_validaDadosFormulario() === false) {
      return false;
    }
    js_divCarregando('Aguarde, incluindo conta', 'msgBox');

    oParam.exec                     = "incluirConta";
    oParam.iEstruturalPlano         = $F("c90_estrutcontabil");
    oParam.sDescricaoPlano          = encodeURIComponent(tagString($F('c60_descr')));
    oParam.sFinalidadePlano         = encodeURIComponent(tagString($F('c60_finali')));
    oParam.iSistemaContaPlano       = "0";
    oParam.iSubsistemaConta         = "0";
    oParam.iClassificacaoContaPlano = "1";
    oParam.iNaturezaDeSaldo         = $F('c60_naturezasaldo');
    oParam.sIdentificadorFinanceiro = 'N';
    oParam.iContaPcasp              = $F('c72_conplano');
    oParam.sTipoConta               = $F("sTipoConta");
    oParam.iCodigoConta             = $F("iCodigoConta");
  }
  var oAjax = new Ajax.Request(sUrlRPC,
                               {method: 'POST',
                                parameters:'json='+Object.toJSON(oParam),
                                onComplete:js_retornoAcao});
}

/**
 *
 */
function js_retornoAcao(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");

  if (oGet.db_opcao == 3) {

    if (oRetorno.status == 1) {

      $("form1").reset();
      js_pesquisarConta();
    }
    alert(oRetorno.message.urlDecode());
  } else {

    if (oRetorno.status == 1) {

      alert('Conta salva com sucesso.');
      $("iCodigoConta").value = oRetorno.iSequencialDaInsercao;

      if ($('sTipoConta').value == 'sintetica') {

        $('c72_conplano').value = '';
        $('c60_descrPcasp').value = '';
        $('c60_estrut_pcasp').value = '';
      }

      var lAbaReduzidos = oRetorno.sTipoConta=='sintetica'?false:true;
      js_liberaAbasPlano(oRetorno.iSequencialDaInsercao, lAbaReduzidos);
    } else {
      alert(oRetorno.message.urlDecode());
    }
  }
}

/**
 *
 */
function js_pesquisaContaPCASP(mostra) {

  if (mostra === true) {

    js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta',
                        'db_iframe_contaPcasp',
                        'func_conplano.php?funcao_js=parent.js_mostraContaPcasp|c60_codcon|c60_descr|c60_estrut',
                        'Pesquisar', true, '10');
  } else {
    var sValorCampo = $F('c72_conplano');
    if (sValorCampo !== '') {
      js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta',
                          'db_iframe_contaPcasp',
                          'func_conplano.php?pesquisa_chave='+sValorCampo+'&funcao_js=parent.js_mostraContaPcasp',
                          'Pesquisa', false);
    } else {
      $('c72_conplano').value = '';
    }
  }
}

function js_mostraContaPcasp () {

  if(arguments[1] === false) {

    $("c60_descrPcasp").value = arguments[0];
    $("c60_estrut_pcasp").value = arguments[2];
  } else if (arguments[1] === true) {

    $("c72_conplano").value = "";
    $("c60_descrPcasp").value = arguments[0];
    $("c60_estrut_pcasp").value = arguments[2];
  } else {

    $("c72_conplano").value   = arguments[0];
    $("c60_descrPcasp").value = arguments[1];
    $("c60_estrut_pcasp").value = arguments[2];
  }

  js_mascaraEstrutural();
  db_iframe_contaPcasp.hide();
}


/**
 * Função para liberação de abas após cadastro da conta
 */
function js_liberaAbasPlano(iCodigoConta, lAbaReduzidos) {

  parent.document.formaba.reduzido.disabled = true;
  if (lAbaReduzidos) {

   var iCodConPcasp = $F('c72_conplano');
   parent.document.formaba.reduzido.disabled = false;
   parent.iframe_reduzido.location.href      = "con1_conplanoorcamento004.php?iCodConPcasp="+iCodConPcasp+"&iCodigoConta="+iCodigoConta;
  }
  parent.document.formaba.grupos.disabled = false;
  parent.iframe_grupos.location.href      = "con1_conplanoorcamentogrupo004.php?iCodigoConta="+iCodigoConta;

}

function js_pesquisarConta() {

  js_OpenJanelaIframe('CurrentWindow.corpo.iframe_conta',
                      'db_iframe_conta',
      'func_conplanoorcamento.php?funcao_js=parent.js_carregadadosconta|c60_codcon',
      'Pesquisar Plano Orçamentário', true, '10');
}

function js_carregadadosconta(iConta) {

   if (oGet.db_opcao != 1) {

     $("form1").reset();
     js_divCarregando('Aguarde, pesquisando dados da conta', 'msgbox');

     var oParam          = new Object();
     oParam.exec         = 'getDadosContaOrcamento';
     oParam.iCodigoConta = iConta;
     var oAjax           = new Ajax.Request(sUrlRPC,
                                           {method:'post',
                                            parameters:'json='+Object.toJSON(oParam),
                                            onComplete: js_preencheDadosConta
                                           });
   }
   db_iframe_conta.hide();
}

function js_preencheDadosConta(oResponse) {

  js_removeObj('msgbox');

  var oRetorno = eval("("+oResponse.responseText+")");
  /**
   * Pega todos os elementos do formulário, itera sobre eles procurando o id dos elementos
   */
  var aFields  = $("form1").elements;

  for (var iField = 0; iField < aFields.length; iField++) {

    with(aFields[iField]) {

      if (oRetorno.dados[id]) {

        if (oRetorno.dados[id].urlDecode) {
          oRetorno.dados[id] = oRetorno.dados[id].urlDecode();
        }
        value = oRetorno.dados[id];
      }
    }
  }
  if (oGet.db_opcao == 3) {

    $('sTipoConta_select_descr').value        = oRetorno.dados.lReduzido?'Analítica':'Sintética';
    $('sTipoConta_select_descr').size         = 10;
    $('c60_naturezasaldo_select_descr').value = oRetorno.dados.c60_naturezasaldo;
    $('c60_naturezasaldo_select_descr').size  = 10;
  } else {
    $('sTipoConta').value = oRetorno.dados.lReduzido?'analitica':'sintetica';
    js_selecionaTipoConta();
  }

  /**
   * Testamos se há reduzidos e liberamos as abas se nescessário
   */
  js_liberaAbasPlano(oRetorno.dados.iCodigoConta, oRetorno.dados.lReduzido);
  $("c90_estrutcontabil").disabled = true;
  js_pesquisaContaPCASP(false);
}
$('sTipoConta').value = 'sintetica';
js_selecionaTipoConta();
</script>
