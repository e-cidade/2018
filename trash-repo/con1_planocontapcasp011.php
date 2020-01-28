<?
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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_libdicionario.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_classesgenericas.php");
require_once("classes/db_conparametro_classe.php");
$clrotulo = new rotulocampo;
$clrotulo->label("c52_descr");
$clrotulo->label("c61_reduz");
$clrotulo->label("c51_descr");
$clrotulo->label("c60_descr");
$clrotulo->label("codigo");
$clrotulo->label("c61_codigo");
$clrotulo->label("o15_descr");
$clrotulo->label("nomeinst");
$clrotulo->label("c90_estrutsistema");
$clrotulo->label("c60_estrut");
$clrotulo->label("c60_naturezasaldo");
$clrotulo->label("c64_descr");

$clrotulo->label("db89_db_bancos");
$clrotulo->label("db89_codagencia");
$clrotulo->label("db89_digito");
$clrotulo->label("db83_conta");
$clrotulo->label("db83_dvconta");
$clrotulo->label("db83_identificador");
$clrotulo->label("db83_codigooperacao");
$clrotulo->label("db83_tipoconta");
$GsTitulo        = 't';
$NsFuncionamento = 'style="background-color:#E6E4F1;"';
$NsFuncao        = 'style="background-color:#E6E4F1;"';

$oEstruturaSistema = new cl_estrutura_sistema();
$iOpcao = 1;
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1">


<form name="form1" id='form1'>
<center>
  <br />
  <fieldset style="width: 500px;">
    <legend><b>Plano de Contas PCASP</b></legend>
  	<table border="0" width="500px;">
  	  <tr>
  	    <td><b>C�digo:</b></td>
  	    <td>
  	      <?php
  	        db_input("iCodigoConta", 5, false, 3, "text", 3);
  	      ?>
  	    </td>
  	  </tr>
  	  <tr>
  	    <td nowrap="nowrap">
  	       <b>Estrutural Contabilidade</b>
  	    </td>
  	    <td>
           <?
           $mascara = '0.0.0.0.0.00.00.00.00.00';
           db_input('mascara', 30, $Ic60_estrut, true, 'text', 3, "", "","", "width:98%;");
           ?>
        </td>
  	  </tr>
  	  <tr>
        <td nowrap="nowrap">
           <b>Estrutural Contabilidade</b>
        </td>
        <td>
           <?
           db_input('c90_estrutcontabil', 30, $Ic60_estrut, true, 'text', $db_opcao, "", "","", "width:98%;");
           ?>
        </td>
      </tr>
  	  <tr>
  	    <td><b>T�tulo:</b></td>
  	    <td>
  	      <?php
  	        db_input("sTitulo", 50, "0", true, "text", $db_opcao,"", "","", "", 50);
  	      ?>
  	    </td>
  	  </tr>
  	  <tr>
  	    <td><b>Natureza de Saldo:</b></td>
  	    <td>
  	      <?php
  	        $aNaturezaSaldo = array(1 => "Saldo Devedor",
  	                                2 => "Saldo Credor",
  	                                3 => "Ambos");
            db_select("iNaturezaSaldo", $aNaturezaSaldo, true, $db_opcao);
  	      ?>
  	    </td>
  	  </tr>
  	  <tr>
  	    <td>
            <?php
              db_ancora("<b>Sistema:</b>", "js_lookupSistemaConta(true)", $db_opcao);
            ?>
          </td>
  	    <td>
            <?php
              db_input("iSistemaConta", 5, false, 3, "text", $db_opcao, "onchange='js_lookupSistemaConta(false);'");
              db_input("sDescricaoSistemaConta", 35, false, 3, "text", 3, "", "","", "width:81%;");
            ?>
          </td>
  	  </tr>
      <tr id="trIndicadorSuperavit" style="display: none;">
        <td nowrap="nowrap"><b>Indicador Superavit:</b></td>
        <td>
          <?php
            /*
             * Organiza um array com os valores padr�o cadastrado no dicion�rio de dados
             */
            $aIndicadorSuperavit = getValoresPadroesCampo("c60_identificadorfinanceiro");
            $aRecordSuperavit    = array();
            foreach ($aIndicadorSuperavit as $sSigla => $sDescricao) {
              $aRecordSuperavit[$sSigla] = "{$sSigla} - {$sDescricao}";
            }
            db_select("sIndicadorSuperavit", $aRecordSuperavit, true, $db_opcao);
          ?>
        </td>
      </tr>
      <tr id="trDetalhamentoSistema" style="display:none;">
        <td nowrap="nowrap">
          <?php
            db_ancora("<b>Detalhamento do Sistema:</b>", "js_lookupDetalhamentoSistema(true)", $db_opcao);
          ?>
        </td>
        <td>
          <?php
            db_input("iDetalhamentoSistema", 5, false, 3, "text", $db_opcao, "onchange='js_lookupDetalhamentoSistema(false);'");
            db_input("sDescricaoDetalhamentoSistema", 35, false, 3, "text", 3, "", "","", "width:81%;");
          ?>
        </td>
      </tr>
      <tr>
        <td><b>Tipo de Conta</b></td>
        <td>
          <?php
            $aTipoConta = array(0 => "Sint�tica", 1 => "Anal�tica");
            db_select("iTipoConta", $aTipoConta, true, $db_opcao);
          ?>
        </td>
      </tr>
      <tr id = 'conta-corrente' style='display: none;'>
        <td>
          <?php
              db_ancora("<b>Conta Corrente:</b>", "js_pesquisaContaCorrente(true)", 3);
          ?>
        </td>
        <td nowrap="nowrap">
          <?php
            db_input("iCodigoContaCorrente", 10, null, true, "text", 3, "onchange='js_pesquisaContaCorrente(false);'");
            db_input("sDescricaoContaCorrente", 35, null, true, "text", 3);
          ?>
        </td>
      </tr>
      <tr id='trdivContaBancaria' style='display: none'>
         <td>
            <?php
              db_ancora("<b>Conta Banc�ria:</b>", "js_abreContaBancaria(true)", $db_opcao);
            ?>
          </td>
        <td>
            <?php
              db_input("iContaBancaria", 5, false, 3, "text", 3);
              db_input("sDescricaoContaBancaria", 35, false, 3, "text", 3, "", "","", "width:81%;");
            ?>
          </td>
      </tr>
  	  <tr>
  	    <td colspan="2">
  	      <fieldset>
            <legend><b>Funcionamento</b></legend>
  	        <?php
  	          db_textarea("sFuncionamento", 3, 65, false, true, 'text', $db_opcao);
  	        ?>
          </fieldset>
  	    </td>
  	  </tr>
      <tr>
        <td colspan="2">
          <fieldset>
            <legend><b>Fun��o</b></legend>
            <?php
              db_textarea("sFuncao", 3, 65, false, true, 'text', $db_opcao);
            ?>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <br>
  <input type="button" name="btnIncluir" id="btnIncluir" value="Salvar"  />
  &nbsp;
  <input type="button" name="btnPesquisar" id="btnPesquisar" value="Pesquisar"  />
</center>
</form>
</body>
</html>



<script>

var sUrlRPC = "con4_conplanoPCASP.RPC.php"

$("btnPesquisar").observe("click", function () {

  var sUrl = 'func_conplanogeral.php?funcao_js=parent.js_preenchePlano|c60_codcon';
  js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_conta',sUrl,'Pesquisa',true,'0');
});

function js_preenchePlano(iCodigoConta) {

  db_iframe_conta.hide();
  var oUrl = js_urlToObject(window.location.search);
  if (oUrl.db_opcao == 1) {
    return true;
  }
  js_divCarregando("Aguarde, carregando plano de contas...", "msgBox");
  var oParam          = new Object();
  oParam.exec         = "getPlanoContasPCASP";
  oParam.iCodigoConta = iCodigoConta;

  var oAjax = new Ajax.Request(sUrlRPC,
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_preenchePlanoConta
                                }
                               );
}

function js_preenchePlanoConta(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  $("iCodigoConta").value                 = oRetorno.iCodigoConta;
  document.form1.c90_estrutcontabil.value = oRetorno.sEstrutural;
  var aFields = $('form1').elements;
  for (var iField = 0; iField < aFields.length; iField++) {

    with (aFields[iField]) {

      if (oRetorno.dados[id]) {

        if (oRetorno.dados[id].urlDecode) {
          oRetorno.dados[id] = oRetorno.dados[id].urlDecode();
        }
        value = oRetorno.dados[id];
      }
    }
  }
  $("c90_estrutcontabil").disabled = true;
  js_lookupDetalhamentoSistema(false);
  js_lookupSistemaConta(false);
  js_validaFinanceiroBanco();
  $('iTipoConta').value = oRetorno.dados.iTipoConta;
  if(oRetorno.dados.iTipoConta == 1) {
    $('conta-corrente').style.display = "";
  }

  var lAbaReduzidos     = oRetorno.dados.iTipoConta == 0?false:true;
  js_liberaAbasPlano(oRetorno.dados.iCodigoConta, lAbaReduzidos);

}

$("btnIncluir").observe("click", function() {

  var iCodigoConta         = $("iCodigoConta").value;
  var sEstrutural          = document.form1.c90_estrutcontabil.value;
  var sTitulo              = encodeURIComponent(tagString($("sTitulo").value));
  var iNaturezaSaldo       = $("iNaturezaSaldo").value;
  var sFuncionamento       = encodeURIComponent(tagString($("sFuncionamento").value));
  var iSistemaConta        = $("iSistemaConta").value;
  var iDetalhamentoSistema = $("iDetalhamentoSistema").value;
  var sSuperavitFinanceiro = 'N';
  var iClassificacao       = 1;
  var iTipoConta           = $("iTipoConta").value;

  var sFuncao              = encodeURIComponent(tagString($("sFuncao").value));

  /**
   * Valida��es dos campos
   */
  if (sEstrutural == "") {

    alert("Informe a estrutura cont�bil do plano de contas.");
    return false;
  }

  if (sTitulo == "") {

    alert("Informe o t�tulo do plano de contas.");
    return false;
  }

  if (sTitulo.length > 50) {

	    alert("O T�tulo do plano de contas excede o tamanho m�ximo de caracteres (50).");
	    return false;
	}

  if (iSistemaConta == "") {

    alert("Informe o sistema de contas.");
    return false;
  }

  /*
   * Valida se o sistema de contas � "Informa��es Patrimoniais - 2" caso seja, o indicador
   * de superavit n�o pode ser "N�O SE APLICA"
   */
  if (iSistemaConta == 2) {

    if ($("sIndicadorSuperavit").value == "N") {

      alert("Selecione uma op��o para c�lculo de superavit.");
      return false;
    } else {
      sSuperavitFinanceiro = $("sIndicadorSuperavit").value;
    }
  } else {
    iDetalhamentoSistema = "0";
  }


  if (iDetalhamentoSistema == 6) {

    if ($('iContaBancaria').value == "") {
      alert("Informe uma conta banc�ria.");
      return false;
    }
  }

  js_divCarregando("Cadastrando plano de contas, aguarde...", "msgBox");
  var oParam                  = new Object();
  oParam.exec                 = "salvarPlanoConta";
  oParam.iCodigoConta         = iCodigoConta;
  oParam.sEstrutural          = sEstrutural;
  oParam.sTitulo              = sTitulo;
  oParam.iNaturezaSaldo       = iNaturezaSaldo;
  oParam.sFuncionamento       = sFuncionamento;
  oParam.iSistemaConta        = iSistemaConta;
  oParam.sIndicadorSuperavit  = sSuperavitFinanceiro;
  oParam.iDetalhamentoSistema = iDetalhamentoSistema;
  oParam.iClassificacao       = 1;
  oParam.iContaBancaria       = $('iContaBancaria').value;
  oParam.iTipoConta           = iTipoConta;
  oParam.sFuncao              = sFuncao;

  if (iTipoConta == 1 ) {
    oParam.iContaCorrente = $F('iCodigoContaCorrente');
  }

  var oAjax                   = new Ajax.Request(sUrlRPC,
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoSalvarPlanoConta
                                }
                               );

});

function js_retornoSalvarPlanoConta(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.message.urlDecode());

  if (oRetorno.status == 1) {

    $("iCodigoConta").value                    = oRetorno.iCodigoConta;

    if ($F("iTipoConta") == 1) {

      alert("Aba 'Reduzidos' liberada.");
      parent.mo_camada('reduzido');
      js_liberaAbasPlano(oRetorno.iCodigoConta, true);
    }
  }
}



function js_validaFinanceiroBanco() {

  var iDetalhamentoSistema = $("iDetalhamentoSistema").value;
  if (iDetalhamentoSistema == 6) {
    $("trdivContaBancaria").style.display = '';

  } else {

    $("trdivContaBancaria").style.display = 'none';
  }
}

/**
 * Valida o Subsistema de contas escolhido e mostra a TR do indicador do superavit.
 * Isso s� acontecer� caso o sub-sistema de contas escolhidos seja 2.
 */
function js_validaSistemaConta() {
  var iSistemaConta = $("iSistemaConta").value;
  if (iSistemaConta == 2 ) {

    $("trIndicadorSuperavit").style.display  = '';
    $("trDetalhamentoSistema").style.display = '';
  } else {

    $("trIndicadorSuperavit").style.display  = 'none';
    $("trDetalhamentoSistema").style.display = 'none';
  }
}

/**
* Fun��es de Pesquisa da Classifica��o do Sistema
*/

/**
 * Fun��es de Pesquisa do Detalhamento do Sistema de contas
 */
function js_lookupDetalhamentoSistema(lMostra) {

  if (lMostra == true) {
    var sUrl = 'func_consistema.php?funcao_js=parent.js_mostraDetalhamentoSistema|c52_codsis|c52_descr';
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_consistemaconta',sUrl,'Pesquisa',true,'0');
  } else {
    if($("iDetalhamentoSistema").value != ''){
      var sUrl = 'func_consistema.php?pesquisa_chave='+$("iDetalhamentoSistema").value+'&funcao_js=parent.js_completaDetalhamentoSistema';
      js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_consistemaconta',sUrl,'Pesquisa',false);
    } else {
      $("sDescricaoDetalhamentoSistema").value = '';
    }
  }
}
function js_mostraDetalhamentoSistema(iCodigo, sDescricao) {

  $("iDetalhamentoSistema").value          = iCodigo;
  $("sDescricaoDetalhamentoSistema").value = sDescricao;
  js_validaFinanceiroBanco();
  db_iframe_consistemaconta.hide();
}
function js_completaDetalhamentoSistema(sDescricao, lErro) {

  if (!lErro) {
    $("sDescricaoDetalhamentoSistema").value = sDescricao;
    js_validaFinanceiroBanco();
  } else {
    $("iDetalhamentoSistema").value          = '';
    $("sDescricaoDetalhamentoSistema").value = sDescricao;
  }
}

/**
 * Fun��es de Pesquisa do Sistema de Contas (Sub-Sistema)
 */
function js_lookupSistemaConta(lMostra) {

  if (lMostra == true) {
    var sUrl = 'func_consistemaconta.php?funcao_js=parent.js_mostraSistemaConta|c65_sequencial|c65_descricao';
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_consistemaconta',sUrl,'Pesquisa',true,'0');
  } else {
    if($("iSistemaConta").value != ''){
      var sUrl = 'func_consistemaconta.php?pesquisa_chave='+$("iSistemaConta").value+'&funcao_js=parent.js_completaSistemaConta';
      js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_consistemaconta',sUrl,'Pesquisa',false);
    } else {
      $("sDescricaoSistemaConta").value = '';
    }
  }
}
function js_mostraSistemaConta(iCodigo, sDescricao) {

  $("iSistemaConta").value          = iCodigo;
  $("sDescricaoSistemaConta").value = sDescricao;
  js_validaSistemaConta();
  db_iframe_consistemaconta.hide();
}
function js_completaSistemaConta(sDescricao, lErro) {

  if (!lErro) {

    js_validaSistemaConta();
    $("sDescricaoSistemaConta").value = sDescricao;
  } else {

    $("iSistemaConta").value          = '';
    $("sDescricaoSistemaConta").value = sDescricao;
  }
}

function js_liberaAbasPlano(iCodigoConta, lAbaReduzidos) {

  parent.document.formaba.reduzido.disabled  = true;
  if (lAbaReduzidos) {

   parent.document.formaba.reduzido.disabled  = false;
   parent.iframe_reduzido.location.href       = "con1_planocontapcasp004.php?iCodigoConta="+iCodigoConta;
  }

  parent.document.formaba.vinculo.disabled = false;
  parent.iframe_vinculo.location.href      = "con1_planocontapcasp005.php?iCodigoConta="+iCodigoConta;

}

js_main = function() {

  new MaskedInput("#c90_estrutcontabil",
                  $F('mascara'),
                  {placeholder:"0"}
                 );

  var oUrl = js_urlToObject(window.location.search);

  switch (oUrl.db_opcao) {

     case '3':

      $("btnPesquisar").click();
      $('btnIncluir').value='excluir';
      $('btnIncluir').stopObserving('click');
      $('btnIncluir').observe('click', function() {
        js_removerConta();
      });

      break;

    case '2':
       $("btnPesquisar").click();
     break;
  }
}


$('iTipoConta').observe('change', function() {

  $('conta-corrente').style.display = "none";
  if ($F("iTipoConta") == 1) {
    $('conta-corrente').style.display = "";
  }
});


/**
 *  Abre uma WINDOW com para preencher uma conta banc�ria ou cadastrar uma nova caso n�o exista
 */
function js_abreContaBancaria() {

  var iWidth           = 650;
  var iHeight          = 400;
  oWindowContaBancaria = new windowAux('wndContaBAncaria', 'Infomar conta banc�ria', iWidth, iHeight);
  var sContent   = "<div id='msgContaBancaria' style='text-align:center;'>";
      sContent  += "  <div id='divContaBancaria'>";
      sContent  += "  </div>";
      sContent  += "  <input type='button' id='btnSalvarContaBancaria' name='btnSalvarContaBancaria' value='Salvar'>";
      sContent  += "</div>";
  oWindowContaBancaria.setContent(sContent);
  oWindowContaBancaria.setShutDownFunction(function (){
    oWindowContaBancaria.destroy();
  });

  var sMsgHelp    = 'Informe os dados abaixo, caso a conta n�o exista, � necess�rio acessar as rotinas de cadastro.';
  oMessageBoard   = new DBMessageBoard('msgBoard1',
                                            'Vinculo com Conta Banc�ria',
                                            sMsgHelp,
                                            oWindowContaBancaria.getContentContainer()
                                             );
  oContaBancaria       = new DBViewContaBancaria($F('iContaBancaria'), 'oContaBancaria',false);
  oContaBancaria.setContaPlano(true);
  oContaBancaria.show($('divContaBancaria'));
  if ($F('iContaBancaria') != "") {

    oContaBancaria.getDados($F('iContaBancaria'));
    $('sDescricaoContaBancaria').value = oContaBancaria.getDadosConta();
  }
  oContaBancaria.onAfterSave(function() {

    $('iContaBancaria').value          = oContaBancaria.iSequencialContaBancaria;
    $('sDescricaoContaBancaria').value = oContaBancaria.getDadosConta();
    oWindowContaBancaria.destroy();
  });

  oWindowContaBancaria.show();
  $('btnSalvarContaBancaria').observe("click", function () {
    oContaBancaria.salvar();
  });
}

/**
 * Fun��o que remove uma conta banc�ria do sistema
 */
function js_removerConta() {

  var oParam          = new Object();
  oParam.exec         = 'removerConta';
  oParam.iCodigoConta =  $F('iCodigoConta');
  js_divCarregando('Aguarde. excluindo dados da Conta..', 'msgBox');
  var oAjax           = new Ajax.Request(sUrlRPC,
                                        {method:'post',
                                         parameters:'json='+Object.toJSON(oParam),
                                         onComplete: js_retornoRemoverConta
                                       }
                                  );
}
function js_retornoRemoverConta(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
    alert(oRetorno.message.urlDecode());
  } else {

    alert('Conta excluida com sucesso!');
    $('form1').reset();
    $("btnPesquisar").click();
  }
}
function js_pesquisaContaCorrente(lMostraWindow) {

  if (lMostraWindow) {
    var sUrl = 'func_contacorrente.php?funcao_js=parent.js_preencheContaCorrente|c17_sequencial|c17_descricao';
    js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_contacorrente',sUrl,'Pesquisa',true,'0');
  } else {

    if ($("iCodigoContaCorrente").value != '') {
      var sUrl  = 'func_contacorrente.php?pesquisa_chave='+$F("iCodigoContaCorrente");
          sUrl +='&funcao_js=parent.js_completaContaCorrente';
      js_OpenJanelaIframe('top.corpo.iframe_conta','db_iframe_contacorente',sUrl,'Pesquisa',false);
    } else {
      $("sDescricaoRecurso").value = '';
    }
  }
}
function js_preencheContaCorrente(iCodigoContaCorrente, sDescricaoContaCorrente) {

  $('iCodigoContaCorrente').value    = iCodigoContaCorrente;
  $('sDescricaoContaCorrente').value = sDescricaoContaCorrente;
  db_iframe_contacorrente.hide();
}
function js_completaContaCorrente(sDescricaoContaCorrente, lErro) {

  if (!lErro) {
    $('sDescricaoContaCorrente').value = sDescricaoContaCorrente;
  } else {
    $('iCodigoContaCorrente').value    = '';
    $('sDescricaoContaCorrente').value = sDescricaoContaCorrente;
  }
}

js_main();
</script>