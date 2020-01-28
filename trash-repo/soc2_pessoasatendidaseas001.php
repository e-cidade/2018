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
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, prototype.js, strings.js, dbcomboBox.widget.js");
      db_app::load("estilos.css");
    ?>
    <style type="text/css">
      .fieldset-hr {
        border:none;
        border-top: 1px outset #000;
      }
    </style>
  </head>
  <body style="margin-top: 25px; background-color: #CCCCCC;">
  <div>
    <center>
      <fieldset style="width: 350px;">
        <legend><b>Pessoas atendidas pelo Estabelecimento de Assistência à Saúde</b></legend>
        <table>
          <tr>
            <td id='ctnEAS'></td>
             <td>
             <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999; width: 40px'>&gt;</button><br>
             <button type='button' id='btnMoveAllRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
             <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
             <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
            </td>
            <td id='ctnEASSelecionados'></td>
          </tr>
        </table>
      </fieldset>
      <input type="button" value="Processar" name='processar' id='btnProcessar'>
    </center>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  </body>
</html>
<script>
/**
 * Pesquisamos se existem familias sem avaliacao processada
 */
function js_pesquisaFamiliasSemAvaliacao() {

  var oParametro  = new Object();
  oParametro.exec = 'getTotalCidadoesFamiliasSemAvaliacao';

  var oAjax = new Ajax.Request('soc4_importabasemunicipio.RPC.php',
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaPesquisaFamiliasSemAvaliacao,
                                 asynchronous:false
                               }
                              );
}

/**
 * Caso existam familias ou cidadaos com avaliacoes nao processadas, apresenta a mensagem ao usuario
 */
function js_retornaPesquisaFamiliasSemAvaliacao(oResponse) {

  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.qtdFamiliaSemAvaliacao > 0 || oRetorno.qtdCidadaoSemAvaliacao > 0) {

    sMsg  = 'Existem avaliações ainda não processadas.';
    sMsg += '\nAvaliações de Famílias: '+oRetorno.qtdFamiliaSemAvaliacao;
    sMsg += '\nAvaliações de Cidadãos: '+oRetorno.qtdCidadaoSemAvaliacao;
    sMsg += '\nPara um relatório completo, processe as demais avaliações em: ';
    sMsg += '\nProcedimentos -> Cadastro Único -> Processar Avaliação Sócio Econômica';
    alert(sMsg);
  }
}

var oCboEas = new DBComboBox("cboAes", "oCboEas", null, "350px", 5);
oCboEas.setMultiple(true);
oCboEas.addEvent("onDblClick", "moveSelected(oCboEas, oCboEasSelecionados)");
oCboEas.show($('ctnEAS'));

var oCboEasSelecionados = new DBComboBox("cboAesSelecionados", "oCboEasSelecionados", null, "350px", 5);
oCboEasSelecionados.setMultiple(true);
oCboEasSelecionados.addEvent("onDblClick", "moveSelected(oCboEasSelecionados, oCboEas)");
oCboEasSelecionados.show($('ctnEASSelecionados'));

function js_pesquisaAES() {

  var oParametro  = new Object();
  oParametro.exec = 'getEstabelecimentoDeAssistenciaSaude';
  js_divCarregando('Aguarde, pesquisando...', 'msgBox')
  var oAjax = new Ajax.Request('soc4_relatoriossociais.RPC.php',
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornoAES,
                               }
                              );
}

function js_retornoAES(oAjax) {
  
  oCboEas.clearItens();
  oCboEasSelecionados.clearItens();
	js_removeObj("msgBox");
  var oRetorno = eval('('+oAjax.responseText+')');
  
  if(oRetorno.status == 2 ) {
  
    alert(oRetorno.message.urlDecode());
    return false;
  }

	oRetorno.aAes.each(function(oAES, iContador) {
	
	  
	  oCboEas.addItem(iContador, oAES.db106_resposta.urlDecode());
	});
}

/**
 * Controla ações de movimentos entre os select box
 */
function moveSelected(oCboOrigem, oCboDestino) {

 	if(oCboOrigem.getValue() != null) {

 		var aItens = oCboOrigem.getValue();
 		aItens.each(function(oLinha, iContador) {

 			oLinha = oCboOrigem.aItens[oLinha];
 			oCboDestino.addItem(oLinha.id, oLinha.descricao);
 			oCboOrigem.removeItem(oLinha.id);
 		});
 	}
}

function moveAll(oCboOrigem, oCboDestino) {

	oCboOrigem.aItens.each(function(oLinha, iContador) {

		oCboDestino.addItem(oLinha.id, oLinha.descricao);
		oCboOrigem.removeItem(oLinha.id);
	});
}

$('btnMoveOneRightToLeft').observe("click", function() {
	moveSelected(oCboEas, oCboEasSelecionados);
});

$('btnMoveOneLeftToRight').observe("click", function() {
	moveSelected(oCboEasSelecionados, oCboEas);
});

$('btnMoveAllRightToLeft').observe("click", function() {
	moveAll(oCboEas, oCboEasSelecionados);
});

$('btnMoveAllLeftToRight').observe("click", function() {
	moveAll(oCboEasSelecionados, oCboEas);
});

/**
 * Envia os dados para impressao do relatorio
 */
$('btnProcessar').observe("click", function() {

  var aEas = new Array();

  if (oCboEasSelecionados.aItens.length == 0 ) {

    alert("Selecione um Estabelecimento de Assistência à Saúde");
    return false;
  }
  
  oCboEasSelecionados.aItens.each(function(oLinha, id) {

    aEas.push(oLinha.descricao);
	});

  var sLocation  = "soc2_pessoasatendidaseas002.php?";
	sLocation     += "sEas="+aEas;
	jan            = window.open(sLocation, '', 
	  	             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	jan.moveTo(0,0);
});
 
js_pesquisaFamiliasSemAvaliacao();
js_pesquisaAES();
</script>