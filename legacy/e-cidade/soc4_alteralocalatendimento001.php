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
  <body >
    <form action="" method="post" class='container'>
      <fieldset>
        <legend class="bold">Alterar Local de Atendimento</legend>
        <table>
          <tr>
            <td>
              <fieldset>
                <legend class="bold">Dados da Família</legend>
                <table>
                  <tr>
                    <td style="width: 25%">
                      <?
                        db_ancora("<b>Família:</b>", "js_pesquisaFamilia(true);", 1);
                      ?>
                    </td>
                    <td>
                      <?
                        db_input("as04_sequencial", 6, $Ias04_sequencial, true, "text", 1, "onchange='js_pesquisaFamilia(false);'");
                        db_input("ov02_nome", 46, $Iov02_nome, true, "text", 3);
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td style="width: 25%"><label class="bold">Local de Atendimento: </label></td>
                    <td>
                      <?
                        db_input("iLocalAtendimentoAtual", 6, "iLocalAtendimentoAtual", true, "hidden", 3);
                        db_input("sLocalAtendimentoAtual", 56, "localAtendimentoAtual", true, "text", 3);
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
          <tr>
            <td>
              <fieldset>
                <legend class="bold">Destino</legend>
                <table>
                  <tr>
                    <td style="width: 25%"><label class="bold">Local de Atendimento:</label></td>
                    <td id="localAtendimentoDestino"></td>
                  </tr>
                  <tr>
                    <td colspan="2">
                      <fieldset>
                        <legend class="bold">Observação</legend>
                        <?
                          db_textarea("as23_observacao", 5, 70, $Ias23_observacao, true, "text", 1);
                        ?>
                      </fieldset>
                    </td>
                  </tr>
                </table>
              </fieldset>
            </td>
          </tr>
        </table>
      </fieldset>
      <input id='btnSalvar' name='btnSalvar' type='button' value='Salvar' onClick='js_salvar();' />
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

$('as23_observacao').style.backgroundColor = '#E6E4F1';

/**
 * Ajuste manual do tamanho dos inputs para ficarem no mesmo tamanho
 */
$('ov02_nome').style.width              = '347px';
$('sLocalAtendimentoAtual').style.width = '415px';

/**
 * Cria o elemento select com os locais de atendimento cadastrados
 */
var oCboLocalAtendimentoDestino             = document.createElement('select');
    oCboLocalAtendimentoDestino.id          = 'localAtendimentoDestino';
    oCboLocalAtendimentoDestino.name        = 'localAtendimentoDestino';
    oCboLocalAtendimentoDestino.style.width = '100%';

oCboLocalAtendimentoDestino.add(new Option('Selecione', ''));
$('localAtendimentoDestino').appendChild(oCboLocalAtendimentoDestino);

/**
 * Pesquisa uma familia pelo seu codigo, retornado o nome do responsavel
 */
function js_pesquisaFamilia(lMostra) {

  var sUrl = 'func_cidadaofamiliacompleto.php?lSomenteResponsavel&lFamilia';

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
  } else if (oRetorno.iStatus != 1 || !oRetorno.lTemLocalVinculado) {

    js_limpaCampos(false);
    alert(oRetorno.sMensagem.urlDecode());
  }
}

/**
 * Busca os locais de atendimento cadastrados como CRAS/CREAS
 */
function js_locaisAtendimento() {

  var oParametro           = new Object();
      oParametro.sExecucao = 'getLocaisAtendimento';

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoLocaisAtendimento;

  js_divCarregando("Aguarde, pesquisando os locais de atendimento cadastrados.", "msgBox");
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno dos locais de atendimento cadastrados como CRAS/CREAS
 */
function js_retornoLocaisAtendimento(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.lTemLocaisAtendimento) {

    oRetorno.aLocaisAtendimento.each(function(oLocal, iLinha) {
      oCboLocalAtendimentoDestino.add(new Option(oLocal.sDescricao.urlDecode(), oLocal.iDepartamento));
    });
  } else {
    alert(oRetorno.sMensagem.urlDecode());
  }
}

/**
 * Limpa os campos na tela. Recebe um boolean como parametro
 * @param boolean lTodos - Controla se devem ser limpos todos os campos ou mantem codigo e responsavel da familia
 */
function js_limpaCampos(lTodos) {

  $('iLocalAtendimentoAtual').value = '';
  $('sLocalAtendimentoAtual').value = '';
  oCboLocalAtendimentoDestino.value = '';
  $('as23_observacao').value        = '';

  if (lTodos) {

    $('as04_sequencial').value = '';
    $('ov02_nome').value       = '';
  }
}

/**
 * Verifica se os campos necessarios foram preenchidos
 */
function js_verificaPreenchimento() {

  if ($('as04_sequencial').value == '') {

    alert('Informe o código da família.');
    return false;
  }

  if (oCboLocalAtendimentoDestino.value == '') {

    alert('Selecione um local de atendimento de destino.');
    return false;
  }

  return true;
}

/**
 * Salva o vinculo da familia com um local de atendimento
 * 1º Valida se o local de atendimento atual nao eh o mesmo que o de destino
 * 2º Valida se os campos foram preenchidos
 */
function js_salvar() {

  if ($('iLocalAtendimentoAtual').value != '' && oCboLocalAtendimentoDestino.value != '' &&
      $('iLocalAtendimentoAtual').value == oCboLocalAtendimentoDestino.value) {

    alert('O local de atendimento de destino é o mesmo vinculado atualmente. Informe um local de destino diferente.');
    return false;
  }

  if (js_verificaPreenchimento()) {

    var oParametro               = new Object();
        oParametro.sExecucao     = 'salvar';
        oParametro.iFamilia      = $F('as04_sequencial');
        oParametro.iDepartamento = oCboLocalAtendimentoDestino.value;
        oParametro.sObservacao   = encodeURIComponent(tagString($F('as23_observacao')));

    var oDadosRequisicao            = new Object();
        oDadosRequisicao.method     = 'post';
        oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
        oDadosRequisicao.onComplete = js_retornoSalvar;

    js_divCarregando("Aguarde, salvando o vínculo da família com o local de atendimento selecionado.", "msgBox");
    new Ajax.Request(sUrlRpc, oDadosRequisicao);
  }
};

/**
 * Retorno do salvar
 */
function js_retornoSalvar(oResponse) {

   js_removeObj("msgBox");
   var oRetorno = eval('('+oResponse.responseText+')');

   alert(oRetorno.sMensagem.urlDecode());
   if (oRetorno.iStatus == 1) {
     js_limpaCampos(true);
   }
}

js_locaisAtendimento();
</script>