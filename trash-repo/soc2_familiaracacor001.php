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

require_once("libs/db_app.utils.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js"); 
    db_app::load("estilos.css");
    ?>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
     <div style="display: table;margin: auto;text-align: center;" >
       <form>
          <fieldset>
            <legend style="font-weight: bold;">
               Famílias por Raça/Cor
            </legend>
            <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Cor/Raça</legend>
              <table>
                <tr>
                  <td id='ctnCorRaca'></td>
                  <td>
                    <button type='button' id='btnMoveOneRightToLeft' 
                            style='border:1px solid #999999; width: 40px'>&gt;</button><br>
                    <button type='button' id='btnMoveAllRightToLeft' 
                            style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
                    <button type='button' id='btnMoveOneLeftToRight' 
                            style='border:1px solid #999999;width: 40px'>&lt;</button><br>
                    <button type='button' id='btnMoveAllLeftToRight' 
                            style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
                  </td>
                  <td id='ctnCorRacaSelecionados'>
                  </td>
                </tr>
              </table>
             </fieldset>
            </table>
          </fieldset>
          <input type='button' value='imprimir' id='btnImprimir'>
       </form>
     </div>
  </body>
</html>
<? 
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
var sUrlRPC = "soc4_relatoriossociais.RPC.php";

var oCboCorRaca = new DBComboBox("cboCorRaca", "oCboCorRaca", null, "200px", 10);
oCboCorRaca.setMultiple(true);
oCboCorRaca.addEvent("onDblClick", "moveSelected(oCboCorRaca, oCboCorRacaSelecionados)");
oCboCorRaca.show($('ctnCorRaca'));

var oCboCorRacaSelecionados = new DBComboBox("oCboCorRacaSelecionados", "oCboCorRacaSelecionados", null, "200px", 10);
oCboCorRacaSelecionados.setMultiple(true);
oCboCorRacaSelecionados.addEvent("onDblClick", "moveSelected(oCboCorRacaSelecionados, oCboCorRaca)");
oCboCorRacaSelecionados.show($('ctnCorRacaSelecionados'));

var aCorRacas = new Array();
function js_listaCorRacas() {
  
  var oObjeto   = new Object();
  oObjeto.exec  = 'getCorRacas';
  js_divCarregando("Aguarde, pesquisando Cor/Racas disponiveis para pesquisa.", "msgBox");
  var oAjax = new Ajax.Request(
  	                           sUrlRPC,
  	                           {
    	                           method:     'post',
    	                           parameters: 'json='+Object.toJSON(oObjeto),
    	                           onComplete: js_retornaCorRacas
  	                           }
  	                          );
}

function js_retornaCorRacas(oAjax) {

  oCboCorRaca.clearItens();
  oCboCorRacaSelecionados.clearItens();
  var oRetorno = eval('('+oAjax.responseText+')');
	js_removeObj("msgBox");

	aCorRacas = oRetorno.dados;
	oRetorno.dados.each(function(oCorRaca, iContador) {
	  oCboCorRaca.addItem(iContador, oCorRaca.descricao.urlDecode());
	});
}


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
	moveSelected(oCboCorRaca, oCboCorRacaSelecionados);
});

$('btnMoveOneLeftToRight').observe("click", function() {
	moveSelected(oCboCorRacaSelecionados, oCboCorRaca);
});

$('btnMoveAllRightToLeft').observe("click", function() {
	moveAll(oCboCorRaca, oCboCorRacaSelecionados);
});

$('btnMoveAllLeftToRight').observe("click", function() {
	moveAll(oCboCorRacaSelecionados, oCboCorRaca);
});

$('btnImprimir').observe('click', function() {

  if ($('oCboCorRacaSelecionados').length == 0) {

    alert("Nenhuma Raca/Cor  foi selecionada.");
    return false;
  } 
  var aCorRacasSelecionadas = new Array();
  oCboCorRacaSelecionados.aItens.each(function(oLinha, iContador) {
     
     if (aCorRacas[oLinha.id]) {

       aCorRacasSelecionadas.push(aCorRacas[oLinha.id].identificador);
     }
  });
  var sLocation  = "soc2_familiaracacor002.php?";
	sLocation     += "&sCorRacas="+aCorRacasSelecionadas;
	jan            = window.open(sLocation, '', 
	  	             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	jan.moveTo(0,0);
});
js_listaCorRacas();
var sUrlRPC = 'soc4_importabasemunicipio.RPC.php';
/**
 * Pesquisamos se existem familias sem avaliacao processada
 */
(function js_pesquisaFamiliasSemAvaliacao() {

  var oParametro  = new Object();
  oParametro.exec = 'getTotalCidadoesFamiliasSemAvaliacao';

  var oAjax = new Ajax.Request(sUrlRPC,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaPesquisaFamiliasSemAvaliacao
                               }
                              );
})();

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
</script>