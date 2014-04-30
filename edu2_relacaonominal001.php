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
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, dbcomboBox.widget.js");
    db_app::load("estilos.css");
    ?>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
    <form name="form1" id='frmRelacaoNominal' method="post">
    <?php
      if (db_getsession("DB_modulo") == 1100747) {
        MsgAviso(db_getsession("DB_coddepto"),"escola");
      }
    ?>
      <center>
        <div style='display:table;'>
          <fieldset>
          <legend style="font-weight: bold">Relação Nominal</legend>
            <fieldset style='border: 0px;'>
            <table border='0' width="100%">
              <tr>
               <td nowrap title="" >
                  <b>Calendário: </b>
                </td>
                <td nowrap id="ctnCboCalendario">
               </td>
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
            <table border='0' width="100%">
              <tr>
                <td><b>Tipo: </b></td>
                <td nowrap id='ctnCboTipo'></td>
              </tr>
              <tr>
                <td><b>Modelo: </b></td>
                <td nowrap id='ctnCboModelo'></td>
              </tr>
              <tr id="preEscola">
                <td><b>Exibir coluna Pré-Escola: </b></td>
                <td nowrap id='ctnCboPreEscola'></td>
              </tr>
              <tr>
                <td><b>Diretor(a): </b></td>
                <td nowrap id='ctnCboDiretor'></td>
              </tr>
            </table>
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

var oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "330px");
oCboCalendario.addItem("", "Selecione o calendário");
oCboCalendario.addEvent("onChange", "js_pesquisarTurmas();");
oCboCalendario.show($('ctnCboCalendario'));

var oCboTipo = new DBComboBox("cboTipo", "oCboTipo", null, "330px");
oCboTipo.addItem("", "Selecione o tipo");
oCboTipo.addItem("1", "Matrícula Inicial");
oCboTipo.addItem("2", "Matrícula Final");
oCboTipo.addEvent("onChange", "js_mostraPreEscola()");
oCboTipo.show($('ctnCboTipo'));

var oCboModelo = new DBComboBox("cboModelo", "oCboModelo", null, "330px");
oCboModelo.addItem("", "Selecione o modelo");
oCboModelo.addItem("1", "Educação Infantil");
oCboModelo.addItem("2", "Demais Cursos");
oCboModelo.addEvent("onChange", "js_mostraPreEscola()");
oCboModelo.show($('ctnCboModelo'));

var oCboPreEscola = new DBComboBox("cboPreEscola", "oCboPreEscola", null, "330px");
oCboPreEscola.addItem("1", "Não");
oCboPreEscola.addItem("2", "Sim");
oCboPreEscola.show($('ctnCboPreEscola'));
oCboPreEscola.setDisable(true);

var oCboDiretor = new DBComboBox("cboDiretor", "oCboDiretor", null, "330px");
oCboDiretor.addItem("", "Selecione o Diretor(a)");
oCboDiretor.show($('ctnCboDiretor'));

var oCboTurmas = new DBComboBox("cboTurmas", "oCboTurmas", null, "220px", 10);
oCboTurmas.setMultiple(true);
oCboTurmas.addEvent("onDblClick", "moveSelected(oCboTurmas, oCboTurmasSelecionadas)");
oCboTurmas.show($('ctnTurmas'));

var oCboTurmasSelecionadas = new DBComboBox("cboTurmasSelecionadas", "oCboTurmasSelecionadas", null, "220px", 10);
oCboTurmasSelecionadas.setMultiple(true);
oCboTurmasSelecionadas.addEvent("onDblClick", "moveSelected(oCboTurmasSelecionadas, oCboTurmas)");
oCboTurmasSelecionadas.show($('ctnTurmasSelecionadas'));

/**
 * Busca os calendários da Escola logada
 */
function js_pesquisarCalendarios() {

  var oParametro     = new Object();
  oParametro.exec    = 'pesquisaCalendario';
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

function js_retornaPesquisarCalendarios(oResponse) {

  oCboCalendario.clearItens();
  oCboTurmas.clearItens();

	js_removeObj("msgBox");
	var oRetorno = eval('('+oResponse.responseText+')');
	oCboCalendario.clearItens();
	oCboCalendario.addItem("", "Selecione o calendário");

	oRetorno.dados.each(function(oLinha, iContador) {
		oCboCalendario.addItem(oLinha.ed52_i_codigo, oLinha.ed52_c_descr.urlDecode());
	});

	if (oRetorno.aResult.length == 1) {

		oCboCalendario.setValue(oRetorno.aResult[0].ed52_i_codigo);
		js_pesquisarTurmas();
	}
}

/**
 * Busca as Turmas do calendário selecionado
 */
function js_pesquisarTurmas() {

  if (oCboCalendario.getValue() == "") {

    oCboTurmas.clearItens();
    return true;
  }
	var oParametro         = new Object();
	oParametro.exec        = 'pesquisaTurmaEtapa';
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

function js_retornaPesquisarTurmas(oResponse) {

	js_removeObj("msgBox");
	var oRetorno = eval('('+oResponse.responseText+')');
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

  if (js_validaCampos()) {

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

    var sUrl = 'edu2_relacaonominal002.php?';

  	var sLocation  = sUrl;
  	sLocation     += "&iTipo="+oCboTipo.getValue();
  	sLocation     += "&iModelo="+oCboModelo.getValue();
  	sLocation     += "&iCalendario="+oCboCalendario.getValue();
  	sLocation     += "&iDiretor="+oCboDiretor.getValue();
  	sLocation     += "&iPreEscola="+oCboPreEscola.getValue();
  	sLocation     += "&aTurmas="+Object.toJSON(aTurmasImpressao);
  	jan            = window.open(sLocation, '',
  	  	                         'width='+(screen.availWidth-5)+
  	  	                         ',height='+(screen.availHeight-40)+
  	  	                         ',scrollbars=1,location=0');
    jan.moveTo(0,0);
  }
});

/**
 * Limpa os campos da tela
 */
function js_limparDadosTela() {

  oCboCalendario.clearItens();
  oCboTurmas.clearItens();
  oCboTurmasSelecionadas.clearItens();
  js_pesquisarCalendarios();
}

/**
 * Busca os diretores da escola
 */
function js_pesquisaDiretores() {

  var oParametro  = new Object();
  oParametro.exec = 'pesquisaDiretores';

  var oAjax = new Ajax.Request(sUrlRPC,
                               {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaPesquisaDiretores
                               }
                              );
}

/**
 * Retorno da busca pelos diretores
 */
function js_retornaPesquisaDiretores(oResponse) {

  oCboDiretor.clearItens();
  oCboDiretor.addItem("", "Selecione o diretor(a)");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.dados.length > 0) {

    oRetorno.dados.each(function(oDiretor, iSeq) {
      oCboDiretor.addItem(oDiretor.iCodigo, oDiretor.sNome.urlDecode());
    });

    if (oRetorno.dados.length == 1) {
      oCboDiretor.setValue(oRetorno.dados[0].iCodigo);
    }
  }
}

/**
 * Validamos se os filtros foram selecionados
 */
function js_validaCampos() {

  if (oCboTipo.getValue() == '') {

    alert('Informe um tipo de relatório a ser impresso.');
    return false;
  }

  if (oCboModelo.getValue() == '') {

    alert('Informe um modelo de relatório a ser impresso.');
    return false;
  }

  return true;
}

/**
 * Verificamos o modelo escolhido, para habilitar o campo para exibir pre-escola
 */
function js_mostraPreEscola() {

  oCboPreEscola.setValue(1);
  oCboPreEscola.setDisable();
  if (oCboTipo.getValue() == 1 && oCboModelo.getValue() == 2) {
    oCboPreEscola.setEnable();
  }
}

js_pesquisaDiretores();
js_pesquisarCalendarios();
</script>