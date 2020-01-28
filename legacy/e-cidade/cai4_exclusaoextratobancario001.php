<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
  <head>
    <?php
    db_app::load("scripts.js");
    db_app::load("prototype.js");
    db_app::load("strings.js");
    db_app::load("webseller.js");

    db_app::load("widgets/windowAux.widget.js");
    db_app::load("DBtab.style.css, estilos.css");
    db_app::load("datagrid.widget.js");
    ?>
  </head>
  <body style='margin-top:25px'>

  <center>

  	<div style="width:800px">


  			<fieldset>
  				<legend>Dados da Conta</legend>
  				<table>

  				  <tr>
  				    <td>
  				      <?php db_ancora('Extrato:', 'js_pesquisaExtrato(true)', 1, ''); ?>
  				    </td>
  				    <td>
  				      <?php
  				        db_input('k85_sequencial',10, 1, true, 'text', 1, "onchange='js_pesquisaExtrato(false)'");
  				        db_input('k85_nomearq', 25, null, true, 'text', 3);
  				      ?>
  				    </td>
  				  </tr>

  				  <tr>
  				    <td><?php db_ancora('Conta Bancária:', 'js_pesquisaContabancaria(true)', 1, ''); ?></td>
  				    <td>
  				      <?php
  				        db_input('k86_contabancaria',10,1 ,true,'text', 1," onchange='js_pesquisaContabancaria(false);'");
  				        db_input('db83_descricao', 25, null, true, 'text', 3);
  				      ?>
  				    </td>
  				  </tr>

  				  <tr>
  				    <td><strong>Data Processamento:</strong></td>
  				    <td>
  				      <?php
  				        db_inputdata('dtInicial', null, null, null, true, 'text', 1); echo 'até';
  				        db_inputdata('dtFinal', null, null, null, true, 'text', 1);
  				      ?>
  				    </td>
  				  </tr>

  				  <tr>
  				    <td><strong>Data Arquivo:</strong></td>
  				    <td>
  				      <?php
  				        db_inputdata('dtInicialArquivo', null, null, null, true, 'text', 1); echo 'até';
  				        db_inputdata('dtFinalArquivo', null, null, null, true, 'text', 1);
  				      ?>
  				    </td>
  				  </tr>

  				</table>
  			</fieldset>

  			<input type="button" value="Pesquisar" onclick="js_pesquisarDadosExtrato()" />

  			<fieldset>
  			  <legend>Contas</legend>
  			  <div id="ctnContas"></div>
  			</fieldset>

  		<input type="button" value="Excluir Selecionados" onclick="js_processar()" />

  	</div>

  </center>

  </body>
  <?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script>
var sUrl = 'cai4_conciliacaobancaria.RPC.php';
function js_pesquisaExtrato(lMostra){

  if (lMostra) {
    js_OpenJanelaIframe('','db_iframe_extrato','func_extrato.php?funcao_js=parent.js_preenchePesquisaExtrato|k85_sequencial|k85_nomearq','Pesquisa',true);
  } else {

    if ($F('k85_sequencial') != '') {
      js_OpenJanelaIframe('','db_iframe_extrato','func_extrato.php?&pesquisa_chave='+$F('k85_sequencial')+'&funcao_js=parent.js_preenchePesquisaExtrato1','Pesquisa',false);
    }
  }
}

function js_preenchePesquisaExtrato(chave1, chave2){

  $('k85_sequencial').value = chave1;
  $('k85_nomearq').value    = chave2;
  db_iframe_extrato.hide();
}

function js_preenchePesquisaExtrato1(chave1, chave2){

  if (chave2) {
    $('k85_sequencial').value = '';
  }
  $('k85_nomearq').value = chave1;
}

function js_pesquisaContabancaria(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_contabancaria','func_contabancaria.php?funcao_js=parent.js_mostracontabancaria1|db83_sequencial|db83_descricao|db83_tipoconta','Pesquisa',true);
  }else{
     if($F('k86_contabancaria') != ''){
        js_OpenJanelaIframe('','db_iframe_contabancaria','func_contabancaria.php?tp=1&pesquisa_chave='+$F('k86_contabancaria')+'&funcao_js=parent.js_mostracontabancaria','Pesquisa',false);
     }else{
       $('db83_descricao').value = '';
     }
  }
}
function js_mostracontabancaria(erro,chave1, chave2, chave3, chave4, chave5, chave6){

  $('db83_descricao').value = chave1;
  if(erro==true){
    $('k86_contabancaria').focus();
    $('k86_contabancaria').value = '';
  }
}
function js_mostracontabancaria1(chave1,chave2,chave3){
  $('k86_contabancaria').value = chave1;
  $('db83_descricao').value = chave2;
  db_iframe_contabancaria.hide();
}

/**
 * Grid Contas
 */
var aHeaderConta = new Array ( 'Código', 'Extrato', 'Conta Bancária', 'Data', 'Valor', 'Tipo', 'Histórico do Documento');
oGridConta   = new DBGrid('oGridConta');
oGridConta.setCellWidth(new Array('0%', '10%', '35%', '10%', '10%', '5%', '30%'));
oGridConta.setCellAlign(new Array('center', 'center', 'center', 'center', 'center', 'center', 'left'));
oGridConta.nameInstance = 'oGridConta';
oGridConta.setCheckbox(0);
oGridConta.setHeader(aHeaderConta);
oGridConta.aHeaders[1].lDisplayed = false;
oGridConta.show($('ctnContas'));

/**
 * Pesquisa os extratos para exclusão
 */
function js_pesquisarDadosExtrato() {

  js_divCarregando(_M('financeiro.caixa.cai4_exclusaoextratobancario001.buscando_contas'), "msgBox");

  var oParametro                    = new Object();
  oParametro.exec                   = 'GetDadosExtrato';
  oParametro.iCodigoExtrato         = $F('k85_sequencial');
  oParametro.iCodigoContaBancaria   = $F('k86_contabancaria');
  oParametro.dtProcessamentoInicial = $F('dtInicial');
  oParametro.dtProcessamentoFinal   = $F('dtFinal');
  oParametro.dtArquivoInicial       = $F('dtInicialArquivo');
  oParametro.dtArquivoFinal         = $F('dtFinalArquivo');

  var oAjax = new Ajax.Request(sUrl,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornoDadosExtrato
                               }
                              );

}

/**
 * Retorno para a grid contas para selecionar quais serão excluídas.
 */
function js_retornoDadosExtrato(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.status == 1) {

    oGridConta.clearAll(true);
    oRetorno.aLinhasExtrato.each(function (oExtrato) {

   		var aLinha    = new Array();
        	aLinha[0] = oExtrato.codigo_linha;
        	aLinha[1] = oExtrato.codigo_extrato;
        	aLinha[2] = oExtrato.descricao_conta_bancaria.urlDecode();
        	aLinha[3] = js_formatar(oExtrato.data, 'd');
        	aLinha[4] = oExtrato.valor;
        	aLinha[5] = oExtrato.tipo;
        	aLinha[6] = oExtrato.historico.urlDecode();

        	oGridConta.addRow(aLinha);
     	});
    oGridConta.renderRows();
  } else {
    alert(_M('financeiro.caixa.cai4_exclusaoextratobancario001.sem_linhas_para_exclusao'));
    oGridConta.clearAll(true);
  }
}

/**
 * Exclusão dos extratos selecionados na grid
 */
function js_processar() {

  if (oGridConta.getSelection().length == 0) {
    alert(_M('financeiro.caixa.cai4_exclusaoextratobancario001.sem_registros_selecionados'));
    return false;
  }

  if (!confirm(_M('financeiro.caixa.cai4_exclusaoextratobancario001.confirma_exclusao'))) {
    return false;
  }

  aLinhasExtrato = new Array();

  oGridConta.getSelection().each(function(aLinha){
    aLinhasExtrato.push(aLinha[0]);
  });

  js_divCarregando(_M('financeiro.caixa.cai4_exclusaoextratobancario001.excluindo_contas'), "msgBox");

  var oParametro                    = new Object();
  oParametro.exec                   = 'Processar';
  oParametro.aLinhasExtrato         = aLinhasExtrato;

  var oAjax = new Ajax.Request(sUrl,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornoProcessar
                               }
                              );

}

function js_retornoProcessar(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oResponse.responseText+')');

  var oMessage = new Object();
  oMessage.sMessage = oRetorno.message.urlDecode();

  if (oRetorno.status == 1) {
    alert(_M('financeiro.caixa.cai4_exclusaoextratobancario001.exclusao_com_sucesso'));
    js_pesquisarDadosExtrato();
  } else {
    alert(_M('financeiro.caixa.cai4_exclusaoextratobancario001.erro_processar_dados',oMessage));
  }
}
</script>