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
               Pessoas com Deficiência
            </legend>
            <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Escolha as Deficiências</legend>
              <table>
                <tr>
                  <td id='ctnDeficiencia'></td>
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
                  <td id='ctnDeficienciaSelecionados'>
                  </td>
                </tr>
              </table>
             </fieldset>
             <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Outros Filtros</legend>
              <table style="width: 100%">
                <tr>
                 <td style="font-weight: bold; width: 30px; white-space: nowrap;">
                    Cuidado Familiar:
                 </td>
                 <td id='ctnCuidadoFamiliar'>
                 </td>
                </tr> 
            </table>
          </fieldset>
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

var oCboDeficiencia = new DBComboBox("cboDeficiencia", "oCboDeficiencia", null, "300px", 10);
oCboDeficiencia.setMultiple(true);
oCboDeficiencia.addEvent("onDblClick", "moveSelected(oCboDeficiencia, oCboDeficienciaSelecionados)");
oCboDeficiencia.show($('ctnDeficiencia'));

var oCboDeficienciaSelecionados = new DBComboBox("cboDeficienciaSelecionados", "oCboDeficienciaSelecionados", null, "300px", 10);
oCboDeficienciaSelecionados.setMultiple(true);
oCboDeficienciaSelecionados.addEvent("onDblClick", "moveSelected(oCboDeficienciaSelecionados, oCboDeficiencia)");
oCboDeficienciaSelecionados.show($('ctnDeficienciaSelecionados'));

oCboCuidadosFamiliares = new DBComboBox("oCboCuidadosFamiliares", "oCboCuidadosFamiliares", null, '100%');
oCboCuidadosFamiliares.show($('ctnCuidadoFamiliar'));
oCboCuidadosFamiliares.addItem(0, 'Todos');
oCboCuidadosFamiliares.addItem(1, 'Sim');
oCboCuidadosFamiliares.addItem(2, 'Não');

var aDeficiencias = new Array();
function js_listaDeficiencias() {
  
  var oObjeto   = new Object();
  oObjeto.exec  = 'getDeficiencias';
  js_divCarregando("Aguarde, pesquisando Deficiencias.", "msgBox");
  var oAjax = new Ajax.Request(
  	                           sUrlRPC,
  	                           {
    	                           method:     'post',
    	                           parameters: 'json='+Object.toJSON(oObjeto),
    	                           onComplete: js_retornaDeficiencias
  	                           }
  	                          );
}

function js_retornaDeficiencias(oAjax) {

  oCboDeficiencia.clearItens();
  oCboDeficienciaSelecionados.clearItens();
  var oRetorno = eval('('+oAjax.responseText+')');
	js_removeObj("msgBox");

	aDeficiencias = oRetorno.deficiencias;
	oRetorno.deficiencias.each(function(oDeficiencia, iContador) {
	  oCboDeficiencia.addItem(iContador, oDeficiencia.descricao.urlDecode());
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
	moveSelected(oCboDeficiencia, oCboDeficienciaSelecionados);
});

$('btnMoveOneLeftToRight').observe("click", function() {
	moveSelected(oCboDeficienciaSelecionados, oCboDeficiencia);
});

$('btnMoveAllRightToLeft').observe("click", function() {
	moveAll(oCboDeficiencia, oCboDeficienciaSelecionados);
});

$('btnMoveAllLeftToRight').observe("click", function() {
	moveAll(oCboDeficienciaSelecionados, oCboDeficiencia);
});

$('btnImprimir').observe('click', function() {

  if ($('cboDeficienciaSelecionados').length == 0) {

    alert("Nenhuma deficiência foi selecionada.");
    return false;
  } 
  var aDeficienciasSelecionadas = new Array();
  oCboDeficienciaSelecionados.aItens.each(function(oLinha, iContador) {
     
     if (aDeficiencias[oLinha.id]) {

       aDeficienciasSelecionadas.push(aDeficiencias[oLinha.id].identificador);
     }
  });
  var sLocation  = "soc2_pessoasdeficiencia002.php?";
	sLocation     += "&sDeficiencias="+aDeficienciasSelecionadas+'&iCuidadoFamiliar='+oCboCuidadosFamiliares.getValue();
	jan            = window.open(sLocation, '', 
	  	             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
	jan.moveTo(0,0);
});
js_listaDeficiencias();
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