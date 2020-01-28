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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

$oGet    = db_utils::postMemory($_GET);
$oRotulo = new rotulocampo;
$oRotulo->label("as04_sequencial");
$oRotulo->label("ov02_nome");
$oRotulo->label("as23_datavinculo");
$oRotulo->label("as23_fimatendimento");
$oRotulo->label("as23_observacao");
?>
<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link rel="stylesheet" type="text/css" href="estilos/grid.style.css"/>
  <?php
      db_app::load("prototype.js, scripts.js, strings.js, arrays.js");
      db_app::load("estilos.css");
  ?>
  </head>
  <body>
    <form action="" method="post" class='container'>
      <fieldset>
        <legend class="bold">Encerramento de Vínculo</legend>
        <table>
          <tr>
            <td style="width: 25%" nowrap>
              <?
                db_ancora("<b>Família:</b>", "js_pesquisaFamilia(true);", 1);
              ?>
            </td>
            <td colspan="3">
              <?
                db_input("as04_sequencial", 10, $Ias04_sequencial, true, "text", 1, "onchange='js_pesquisaFamilia(false);'");
                db_input("ov02_nome", 42, $Iov02_nome, true, "text", 3);
              ?>
            </td>
          </tr>
          <tr>
            <td style="width: 25%" nowrap><label class="bold">Local de Atendimento: </label></td>
            <td colspan="3">
              <?
                db_input("iLocalAtendimentoAtual", 6, "iLocalAtendimentoAtual", true, "hidden", 3);
                db_input("sLocalAtendimentoAtual", 56, "localAtendimentoAtual", true, "text", 3);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap><?=$Las23_datavinculo?></td>
            <td nowrap>
              <?
                db_inputdata("as23_datavinculo", "", "", "", true, "text", 3);
              ?>
            </td>
            <td nowrap><?=$Las23_fimatendimento?></td>
            <td nowrap>
              <?
                db_inputdata("as23_fimatendimento", "", "", "", true, "text", 1);
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="4">
              <fieldset>
                <legend class="bold">Motivo</legend>
                <?
                  db_textarea("as23_observacao", 5, 72, $Ias23_observacao, true, "text", 1);
                ?>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <input id='btnEncerrar' name='btnEncerrar' type='button' value='Encerrar' onClick='js_encerrar();' />
    </form>
  </body>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script>
/**
 * RPC para as requisicoes
 */
var sUrlRpc = 'soc4_localatendimentofamilia.RPC.php';

/**
 * Ajuste manual do tamanho dos inputs para ficarem no mesmo tamanho
 */
$('ov02_nome').style.width              = '320px';
$('sLocalAtendimentoAtual').style.width = '415px';

/**
 * Pesquisa uma familia pelo seu codigo, retornado o nome do responsavel
 */
function js_pesquisaFamilia(lMostra) {

  var sUrl = 'func_cidadaofamiliacompleto.php?lSomenteResponsavel&lFamilia&lSomenteFamiliaVinculada';

  if (lMostra == true) {

    sUrl += '&funcao_js=parent.js_mostracidadaofamilia1|as04_sequencial|ov02_nome';
  	js_OpenJanelaIframe('', 'db_iframe_cidadao', sUrl, 'Pesquisar Código da Família', true);
  } else {

   sUrl += '&funcao_js=parent.js_mostracidadaofamilia&pesquisa_chave='+$F('as04_sequencial');
  	js_OpenJanelaIframe('', 'db_iframe_cidadao', sUrl, 'Pesquisar Código da Família', false);
  }
}

/**
 * Retorno do nome do responsavel da familia quando informado o codigo
 */
function js_mostracidadaofamilia() {

  if (arguments[0] === true) {

    $('as04_sequencial').value = '';
    $('ov02_nome').value       = arguments[1];
    return false;
  }

	$('ov02_nome').value = arguments[2];

	db_iframe_cidadao.hide();
	js_localAtendimentoFamilia();
}

/**
 * Retorno do codigo e nome do responsavel quando clicado na ancora
 */
function js_mostracidadaofamilia1(iSequencial, sNome) {

	$('as04_sequencial').value = iSequencial;
	$('ov02_nome').value       = sNome;

	db_iframe_cidadao.hide();
	js_localAtendimentoFamilia();
}

/**
 * Busca o local de atendimento da familia selecionada
 */
function js_localAtendimentoFamilia() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'getLocalAtendimentoFamilia';
      oParametro.iFamilia  = $F('as04_sequencial');

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoLocalAtendimentoFamilia;

  js_divCarregando("Aguarde, pesquisando o local de atendimento vinculado a família selecionada", "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno da busca pelo local de atendimento da familia selecionada
 */
function js_retornoLocalAtendimentoFamilia(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.lTemLocalVinculado) {

    $('iLocalAtendimentoAtual').value = oRetorno.iLocalAtendimento;
    $('sLocalAtendimentoAtual').value = oRetorno.sLocalAtendimento.urlDecode();
    $('as23_datavinculo').value       = oRetorno.dtVinculo.urlDecode();
  } else if (oRetorno.iStatus != 1 || !oRetorno.lTemLocalVinculado) {

    js_limpaCampos(false);
    alert("Não foi encontrado vínculo da família com um local de atendimento.");
  }
}

/**
 * Limpa os campos na tela
 */
function js_limpaCampos() {

  $('as04_sequencial').value        = '';
  $('ov02_nome').value              = '';
  $('iLocalAtendimentoAtual').value = '';
  $('sLocalAtendimentoAtual').value = '';
  $('as23_datavinculo').value       = '';
  $('as23_fimatendimento').value    = '';
  $('as23_observacao').value        = '';
}

/**
 * Verifica se os campos necessarios foram preenchidos
 */
function js_verificaPreenchimento() {

  if ($('as04_sequencial').value == '') {

    alert('Informe uma família para encerramento do vínculo.');
    return false;
  }

  if ($('as23_fimatendimento').value == '') {

    alert('Informe a data do fim do atendimento.');
    return false;
  }

  if ($('as23_observacao').value == '') {

    alert('Informe o motivo do encerramento do vínculo.');
    return false;
  }

  return true;
}

/**
 * Validamos o intervalo entre as datas selecionadas
 */
function js_validaData() {

  if ($('as23_datavinculo').value != '' && $('as23_fimatendimento').value != '') {

    var aDataInicial = new Array();
    var aDataFinal   = new Array();

    aDataInicial[0]      = $F('as23_datavinculo').substr(0, 2);
    aDataInicial[1]      = $F('as23_datavinculo').substr(3, 2);
    aDataInicial[2]      = $F('as23_datavinculo').substr(6, 4);
    var sNovaDataInicial = aDataInicial[2]+'-'+aDataInicial[1]+'-'+aDataInicial[0];

    aDataFinal[0]      = $F('as23_fimatendimento').substr(0, 2);
    aDataFinal[1]      = $F('as23_fimatendimento').substr(3, 2);
    aDataFinal[2]      = $F('as23_fimatendimento').substr(6, 4);
    var sNovaDataFinal = aDataFinal[2]+'-'+aDataFinal[1]+'-'+aDataFinal[0];

    if (js_diferenca_datas(sNovaDataInicial, sNovaDataFinal, 3) == true) {

      alert('Data de fim do atendimento não pode ser menor que a data de vínculo.');
      return false;
    }
  }
  return true;
}

/**
 * Encerra o vinculo da familia com um local de atendimento
 * Valida se os campos foram preenchidos
 */
function js_encerrar() {

  var sMensagem = "Confirma o encerramento do vínculo para a família selecionada?";
  if (js_verificaPreenchimento() && js_validaData() && confirm(sMensagem)) {

    var oParametro                  = new Object();
        oParametro.sExecucao        = 'encerrarAtendimento';
        oParametro.iFamilia         = $F('as04_sequencial');
        oParametro.dtFimAtendimento = $F('as23_fimatendimento');
        oParametro.sMotivo          = encodeURIComponent(tagString($F('as23_observacao')));

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoEncerrar;

    js_divCarregando("Aguarde, encerramento o vínculo da família com o local de atendimento.", "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
};

/**
 * Retorno do encerrar
 */
function js_retornoEncerrar(oResponse) {

   js_removeObj("msgBox");
   var oRetorno = eval('('+oResponse.responseText+')');

   alert(oRetorno.sMensagem.urlDecode());

   if (oRetorno.iStatus == 1) {
     js_limpaCampos();
   }
}
</script>