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

require_once("libs/db_stdlibwebseller.php");
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
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js, DBFormCache.js, DBFormSelectCache.js"); 
    db_app::load("estilos.css");
    ?>
  </head>
  <body class="body-default">
    <form name="form1" id='frmLivroNotas' method="post">
    <?php 
      if (db_getsession("DB_modulo") == 1100747) {
        MsgAviso(db_getsession("DB_coddepto"),"escola");
      }
    ?>
      <div class="container">
        <fieldset>
        <legend>Livro de Notas</legend>
          <table class="form-container">
            <tr>
              <td nowrap title="" >
                <label>Escola:</label>
              </td>
              <td nowrap id="ctnCboEscola"></td>
            </tr>
            <tr>
              <td>
                <label>Calendário:</label>
              </td>
              <td nowrap id="ctnCboCalendario"></td>
            </tr>
            <tr>
              <td>
                <label>Modelo do Relatório:</label>
              </td>
              <td>
                <select id='modeloRelatorio' name='modeloRelatorio' onchange='js_validaModelo();'>
                  <option value="1" selected="selected">Por Período</option>
                  <option value="2">Por Disciplina</option>
                </select>
              </td>
            </tr>
            <tr id='exibeAssinatura' style='display: none;'>
              <td>
                <label>Exibir Assinatura:</label>
              </td>
              <td>
                <select id='assinatura' name='assinatura'>
                  <option value="1" selected="selected">Não</option>
                  <option value="2">Sim</option>
                </select>
              </td>
            </tr>
            <tr id='ctnNumeroAvaliacaoPagina' style='display: none;'>
              <td>
                <label>Avaliações por Página:</label>
              </td>
              <td>
                <select id='avaliacaoPagina' name='avaliacaoPagina'>
                  <option value="4" selected="selected">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label>Exibir Trocas de Turma:</label>
              </td>
              <td>
                <select id='trocaTurma' name='trocaTurma'>
                  <option value="1" selected="selected">Não</option>
                  <option value="2">Sim</option>
                </select>
              </td>
            </tr>
          </table>
          <fieldset class="separator">
            <legend>Turmas</legend>
            <table>
              <tr>
                <td id='ctnTurmas'></td>
                <td>
                  <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999; width: 40px'>&gt;</button><br>
                  <button type='button' id='btnMoveAllRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
                  <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
                  <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
                </td>
                <td id='ctnTurmasSelecionadas'></td>
              </tr>
            </table>
          </fieldset>
        </fieldset>
        <input name="btnImprimir" id="btnImprimir" type="button" value="Imprimir">
        <input name="btnLimparDados" id="btnLimparDados" type="button" value="Limpar Dados" onClick='js_limparDadosTela();'>
      </div>
    </form>
  </body>
  <? 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script type="text/javascript">

var oCacheLivroNotas = new DBFormCache('oCacheLivroNotas', 'edu2_livronotas001.php');
    oCacheLivroNotas.setElements(new Array($('trocaTurma')));
    oCacheLivroNotas.load();
  
var sUrlRPC    = "edu_educacaobase.RPC.php";
var aTurmas    = new Array();

var oCboEscola = new DBComboBox("cboEscola", "oCboEscola", null, "370px");
oCboEscola.addItem("", "Selecione");
oCboEscola.addEvent("onChange", "js_pesquisarCalendarios();");
oCboEscola.show($('ctnCboEscola'));

var oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "370px");
oCboCalendario.addItem("", "Selecione");
oCboCalendario.addEvent("onChange", "js_pesquisarTurmas();");
oCboCalendario.show($('ctnCboCalendario'));

var oCboTurmas = new DBComboBox("cboTurmas", "oCboTurmas", null, "220px", 10);
oCboTurmas.setMultiple(true);
oCboTurmas.addEvent("onDblClick", "moveSelected(oCboTurmas, oCboTurmasSelecionadas)");
oCboTurmas.show($('ctnTurmas'));

var oCboTurmasSelecionadas = new DBComboBox("cboTurmasSelecionadas", "oCboTurmasSelecionadas", null, "220px", 10);
oCboTurmasSelecionadas.setMultiple(true);
oCboTurmasSelecionadas.addEvent("onDblClick", "moveSelected(oCboTurmasSelecionadas, oCboTurmas)");
oCboTurmasSelecionadas.show($('ctnTurmasSelecionadas'));

/**
 * Busca as escolas
 */
function js_pesquisarEscolas() {

  var oParametro  = new Object();
  oParametro.exec = 'pesquisaEscola';
  js_divCarregando("Aguarde, pesquisando escolas.", "msgBox");

  var oAjax = new Ajax.Request(
                               sUrlRPC,
                               {
                                method:     'post',
                                parameters: 'json='+Object.toJSON(oParametro),
                                onComplete: js_retornaPesquisarEscolas
                               }
                              );
}

/**
 * Retorna da busca pelas escolas
 */
function js_retornaPesquisarEscolas(oResponse) {

  oCboEscola.clearItens();
  oCboCalendario.clearItens();
  
  js_removeObj("msgBox");

  var oRetorno = eval('('+oResponse.responseText+')');
  oCboEscola.addItem("", "Selecione");
  oCboCalendario.addItem("", "Selecione");
  oRetorno.dados.each(function (oEscola, iSeq) {

    oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
    if (oRetorno.dados.length == 1) {
      
      oCboEscola.setValue(oEscola.codigo_escola);
      js_pesquisarCalendarios();
    }
  });
}

/**
 * Busca os calendários da Escola logada
 */
function js_pesquisarCalendarios() {

  var oParametro     = new Object();
  oParametro.exec    = 'pesquisaCalendario';
  oParametro.iEscola = oCboEscola.getValue();
  js_divCarregando("Aguarde, pesquisando calendários.", "msgBox");
  var oAjax = new Ajax.Request(
  	                           sUrlRPC,
  	                           {
    	                           method:     'post',
    	                           parameters: 'json='+Object.toJSON(oParametro),
    	                           onComplete: js_retornaPesquisarCalendarios
  	                           }
  	                          );
}

function js_retornaPesquisarCalendarios(oAjax) {

  oCboCalendario.clearItens();
  oCboTurmas.clearItens();
  
	js_removeObj("msgBox");
	var oRetorno = eval('('+oAjax.responseText+')');
	oCboCalendario.clearItens();
	oCboCalendario.addItem("", "Selecione");

  if (oCboEscola.getValue() == '') {

    oCboCalendario.clearItens();
    oCboCalendario.addItem("", "Selecione");
    oCboTurmas.clearItens();
    oCboTurmasSelecionadas.clearItens();
  } else {

  	oRetorno.dados.each(function(oLinha, iContador) {
  		oCboCalendario.addItem(oLinha.ed52_i_codigo, oLinha.ed52_c_descr.urlDecode());
  	});
  	
  	if (oRetorno.aResult.length == 1) {
  
  		oCboCalendario.setValue(oRetorno.aResult[0].ed52_i_codigo);
  		js_pesquisarTurmas();
  	}
  }
}

/**
 * Busca as Turmas do calendário selecionado 
 */
function js_pesquisarTurmas() {

	if(oCboEscola.getValue() == '') {

		oCboTurmas.clearItens();
		return false;
	}
	
	var oParametro         = new Object();
	oParametro.exec        = 'pesquisaTurmaEtapa';
	oParametro.iEscola     = oCboEscola.getValue();
	oParametro.iCalendario = oCboCalendario.getValue();

	js_divCarregando("Aguarde, carregando as turmas.", "msgBox");
	var oAjax = new Ajax.Request(
			                         sUrlRPC,
			                         {
				                         method:       'post',
				                         parameters:   'json='+Object.toJSON(oParametro),
				                         asynchronous: false,
				                         onComplete:   js_retornaPesquisarTurmas
			                         }
			                        ); 
}

function js_retornaPesquisarTurmas(oAjax) {

	js_removeObj("msgBox");
	var oRetorno = eval('('+oAjax.responseText+')');
	oCboTurmas.clearItens();
	aTurmas = oRetorno.dados; 
	oCboTurmasSelecionadas.clearItens();

	oRetorno.dados.each(function(oLinha, iContador) {

	  oCboTurmas.addItem(iContador, oLinha.ed57_c_descr.urlDecode());
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
	moveSelected(oCboTurmas, oCboTurmasSelecionadas);
});

$('btnMoveOneLeftToRight').observe("click", function() {
	moveSelected(oCboTurmasSelecionadas, oCboTurmas);
});

$('btnMoveAllRightToLeft').observe("click", function() {
	moveAll(oCboTurmas, oCboTurmasSelecionadas);
});

$('btnMoveAllLeftToRight').observe("click", function() {
	moveAll(oCboTurmasSelecionadas, oCboTurmas);
});

$('btnImprimir').observe("click", function() {
  
	var aTurmasImpressao    = new Array();
	var aTurmasSelecionadas = oCboTurmasSelecionadas.aItens;

	aTurmasSelecionadas.each(function(oItem, iSeq) {

     if (oItem.id !== "") {
       aTurmasImpressao.push(aTurmas[oItem.id]);
     }
  });

  if (aTurmasImpressao == null || aTurmasImpressao.length == 0) {
    
    alert('Nenhuma turma selecionada');
    return false;
  }

  var sUrl = 'edu2_livronotas002.php?';
  if ($('modeloRelatorio').value == '2') {
    sUrl = 'edu2_livronotas003.php?';
  }

  oCacheLivroNotas.save();
	var sLocation  = sUrl;
	sLocation     += "&iEscola="+oCboEscola.getValue();
	sLocation     += "&iCalendario="+oCboCalendario.getValue();
	sLocation     += "&iTrocaTurma="+$('trocaTurma').value;
	sLocation     += "&aTurmas="+Object.toJSON(aTurmasImpressao);
	sLocation     += "&iExibeAssinatura="+$F('assinatura');
	sLocation     += "&iAvaliacaoPagina="+$F('avaliacaoPagina');
	
	jan            = window.open(sLocation, '', 
	  	                         'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
});

function js_limparDadosTela() {

  oCboEscola.clearItens();
  oCboCalendario.clearItens();
  oCboTurmas.clearItens();
  oCboTurmasSelecionadas.clearItens();
  $('modeloRelatorio').value = '1';
  $('trocaTurma').value      = '1';
  js_pesquisarEscolas();
  js_validaModelo();
}

function js_validaModelo() {

  $('exibeAssinatura').style.display          = 'none';
  $('ctnNumeroAvaliacaoPagina').style.display = 'none';

  if ($F('modeloRelatorio') == 2) {

    $('exibeAssinatura').style.display          = 'table-row';
    $('ctnNumeroAvaliacaoPagina').style.display = 'table-row';
  } 
}

js_pesquisarEscolas();
js_validaModelo();
</script>