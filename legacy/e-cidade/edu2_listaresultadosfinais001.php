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
  <body style='margin-top: 25px' bgcolor="#cccccc">
    <form name="form1" id='frmListaResultadosFinais' method="post">
    <?php
      if (db_getsession("DB_modulo") == 1100747) {
        MsgAviso(db_getsession("DB_coddepto"),"escola");
      }
    ?>
      <center>
        <div style='display:table;'>
          <fieldset>
          <legend style="font-weight: bold">Lista de Resultados Finais</legend>
            <fieldset style='border: 0px;'>
            <table border='0' width="100%">
              <tr>
               <td nowrap title="" >
                  <b>Escola: </b>
                </td>
                <td nowrap id="ctnCboEscola">
               </td>
              </tr>
              <tr>
               <td nowrap title="" >
                  <b>Calendário: </b>
                </td>
                <td nowrap id="ctnCboCalendario">
               </td>
              </tr>
              <tr>
              <td nowrap>
                <b>Exibir coluna Turma de Destino: </b>
              </td>
              <td nowrap id="ctnCboTurmaDestino"></td>
            </tr>
            <tr>
              <td nowrap>
                <b>Ordenação: </b>
              </td>
              <td nowrap id="ctnCboOrdenacao"></td>
            </tr>
            <tr>
              <td nowrap>
                <b>Exibir somente alunos ativos: </b>
              </td>
              <td nowrap id="ctnCboAlunosAtivos"></td>
            </tr>
            <tr>
              <td nowrap>
                <b>Exibir trocas de turmas: </b>
              </td>
              <td nowrap id="ctnCboTrocaTurma"></td>
            </tr>
            </table>
            <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Turmas: </legend>
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
        </div>
        <input name="btnImprimir" id="btnImprimir" type="button" value="Imprimir">
        <input name="btnLimparDados" id="btnLimparDados" type="button" value="Limpar Dados" onClick='js_limparDadosTela();'>
      </center>
    </form>
  </body>
  <?
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script type="text/javascript">

var sUrlRPC    = "edu_educacaobase.RPC.php";
var aTurmas    = new Array();

var oDBFormCache = new DBFormCache("oDBFormCache", "edu2_listaresultadosfinais001.php");

var oCboEscola = new DBComboBox("cboEscola", "oCboEscola", null, "330px");
oCboEscola.addItem("", "Selecione");
oCboEscola.addEvent("onChange", "js_pesquisarCalendarios();");
oCboEscola.show($('ctnCboEscola'));

var oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "330px");
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

var oCboTurmaDestino = new DBComboBox("cboTurmaDestino", "oCboTurmaDestino", null, "330px");
oCboTurmaDestino.addItem("1", "Sim");
oCboTurmaDestino.addItem("2", "Não");
oCboTurmaDestino.show($('ctnCboTurmaDestino'));

var oCboOrdenacao = new DBComboBox("cboOrdenacao", "oCboOrdenacao", null, "330px");
oCboOrdenacao.addItem("1", "Alfabética");
oCboOrdenacao.addItem("2", "Diário");
oCboOrdenacao.addItem("3", "Resultado Final");
oCboOrdenacao.show($('ctnCboOrdenacao'));

var oCboAlunosAtivos = new DBComboBox("cboAlunosAtivos", "oCboAlunosAtivos", null, "330px");
oCboAlunosAtivos.addItem("1", "Não");
oCboAlunosAtivos.addItem("2", "Sim");
oCboAlunosAtivos.show($('ctnCboAlunosAtivos'));

var oCboTrocaTurma = new DBComboBox("cboTrocaTurma", "oCboTrocaTurma", null, "330px");
oCboTrocaTurma.addItem("1", "Não");
oCboTrocaTurma.addItem("2", "Sim");
oCboTrocaTurma.show($('ctnCboTrocaTurma'));

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

  js_armazenaCache();
}

/**
 * Busca os calendários da Escola logada
 */
function js_pesquisarCalendarios() {

  var oParametro     = new Object();
  oParametro.exec    = 'PesquisaCalendarioEncerrado';
  oParametro.escola  = oCboEscola.getValue();
  js_divCarregando("Aguarde, pesquisando calendários.", "msgBox");
  var oAjax = new Ajax.Request(
  	                           'edu4_escola.RPC.php',
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
  oCboTurmasSelecionadas.clearItens();

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

  	oRetorno.aResult.each(function(oLinha, iContador) {
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
	oParametro.lEncerrada  = true;

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

  oDBFormCache.save();
  var sUrl = 'edu2_listaresultadosfinais002.php?';

	var sLocation  = sUrl;
	sLocation     += "&iEscola="+oCboEscola.getValue();
	sLocation     += "&iCalendario="+oCboCalendario.getValue();
	sLocation     += "&iTurmaDestino="+oCboTurmaDestino.getValue();
	sLocation     += "&iOrdenacao="+oCboOrdenacao.getValue();
	sLocation     += "&iAlunosAtivos="+oCboAlunosAtivos.getValue();
	sLocation     += "&iTrocaTurma="+oCboTrocaTurma.getValue();
	sLocation     += "&aTurmas="+Object.toJSON(aTurmasImpressao);
	jan            = window.open(sLocation, '',
	  	                         'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
});

function js_limparDadosTela() {

  oCboEscola.clearItens();
  oCboCalendario.clearItens();
  oCboTurmas.clearItens();
  oCboTurmasSelecionadas.clearItens();
  js_pesquisarEscolas();
}

function js_armazenaCache() {

  oDBFormCache.setElements(new Array(
                                     $('cboTurmaDestino'),
                                     $('cboOrdenacao'),
                                     $('cboAlunosAtivos'),
                                     $('cboTrocaTurma')
                                    ));
  oDBFormCache.load();
}

js_pesquisarEscolas();
</script>