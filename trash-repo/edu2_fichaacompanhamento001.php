<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
    <form name="form1" id='frmFichaAcompanhamento' method="post">
      <center>      
        <div style='display:table;'>
          <fieldset>
          <legend style="font-weight: bold">Ficha de Acompanhamento</legend>
            <fieldset style='border: 0px;'>
            <table border='0' width="100%">
              <tr> 
               <td nowrap title="" >
                  <b>Calendarios : </b>
                </td>
                <td nowrap id="ctnCboCalendario">
               </td>
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Turma : </b>
                </td>
                <td nowrap id="ctnCboTurma">
                </td> 
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Período Avaliação: </b>
                </td>
                <td nowrap id="ctnCboPeriodoAvaliacao">
                </td> 
              </tr>              
            </table>
            <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Alunos</legend>
              <table>
                <tr>
                  <td id='ctnAlunos'></td>
                  <td>
                   <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999; width: 40px'>&gt;</button><br>
                   <button type='button' id='btnMoveAllRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
                   <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
                   <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
                  </td>
                  <td id='ctnAlunosSelecionados'></td>
                </tr>
              </table>
            </fieldset>
          </fieldset>
        </div>
        <input name="btnImprimirFicha" id="btnImprimirFicha" type="button" value="Imprimir Ficha">
      </center>
    </form>
  </body>
  <? 
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script type="text/javascript">
  
var sUrlRPC = "edu_educacaobase.RPC.php";
var iEscola = "";

var oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "330px");
oCboCalendario.addItem("", "Selecione");
oCboCalendario.addEvent("onChange", "js_pesquisarTurmas(); js_pesquisarPeriodos();");
oCboCalendario.show($('ctnCboCalendario'));

var oCboTurma = new DBComboBox("cboTurma", "oCboTurma", null, "330px");
oCboTurma.addItem("", "Selecione");
oCboTurma.addEvent("onChange", "js_pesquisarAlunos()");
oCboTurma.show($('ctnCboTurma'));

var oCboPeriodoAvaliacao = new DBComboBox("cboPeriodoAvaliacao", "oCboPeriodoAvaliacao", null, "330px");
oCboPeriodoAvaliacao.addItem("", "Selecione");
oCboPeriodoAvaliacao.addEvent("onChange", "");
oCboPeriodoAvaliacao.show($('ctnCboPeriodoAvaliacao'));

var oCboAlunos = new DBComboBox("cboAlunos", "oCboAlunos", null, "200px", 10);
oCboAlunos.setMultiple(true);
oCboAlunos.addEvent("onDblClick", "moveSelected(oCboAlunos, oCboAlunosSelecionados)");
oCboAlunos.show($('ctnAlunos'));

var oCboAlunosSelecionados = new DBComboBox("cboAlunosSelecionados", "oCboAlunosSelecionados", null, "200px", 10);
oCboAlunosSelecionados.setMultiple(true);
oCboAlunosSelecionados.addEvent("onDblClick", "moveSelected(oCboAlunosSelecionados, oCboAlunos)");
oCboAlunosSelecionados.show($('ctnAlunosSelecionados'));

js_pesquisarCalendarios();
/**
 * Busca os calendários da Escola logada
 */
function js_pesquisarCalendarios() {

  oCboTurma.clearItens();
  var oParametro   = new Object();
  oParametro.exec  = 'pesquisaCalendario';
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

	var oRetorno = eval('('+oAjax.responseText+')');
	js_removeObj("msgBox");
	oCboCalendario.clearItens();
	oCboCalendario.addItem("", "Selecione");
	oCboTurma.clearItens();
	oCboTurma.addItem("", "Selecione");
	iEscola = oRetorno.iEscola;
	
	oRetorno.dados.each(function(oLinha, iContador) {
		oCboCalendario.addItem(oLinha.ed52_i_codigo, oLinha.ed52_c_descr.urlDecode());
	});
	if (oRetorno.aResult.length == 1) {

		oCboCalendario.setValue(oRetorno.aResult[0].ed52_i_codigo);
	}
}

/**
 * Busca as Turmas do calendário selecionado 
 */
function js_pesquisarTurmas() {

	if(oCboCalendario.getValue() == '') {

		oCboTurma.clearItens();
		return false;
	}
	
	var oParametro         = new Object();
	oParametro.exec        = 'pesquisaTurma';
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

	var oRetorno = eval('('+oAjax.responseText+')');
	js_removeObj("msgBox");
	oCboTurma.clearItens();
	oCboTurma.addItem("", "Selecione");
	oRetorno.dados.each(function(oLinha, iContador) {
	  oCboTurma.addItem(oLinha.ed57_i_codigo, oLinha.ed57_c_descr.urlDecode());
	});

}

function js_pesquisarPeriodos () {

  js_divCarregando('Aguarde, pesquisando Periodos', 'msgBox');
  var oParametros              = new Object();
      oParametros.exec         = "buscaPeriodosAvaliacaoEscola";
      oParametros.iCalendario  = oCboCalendario.getValue();
  var oAjax = new Ajax.Request(sUrlRPC,
                               { 
                                 method:       'post', 
                                 parameters:   'json='+Object.toJSON(oParametros),
                                 asynchronous: false,
                                 onComplete:   js_retornoGetPeriodos
                               });  
      
    
    
};

function js_retornoGetPeriodos(oResponse) {

  js_removeObj('msgBox');
  oCboPeriodoAvaliacao.clearItens();
  var oRetorno = eval("("+oResponse.responseText+")");
  oRetorno.dados.each(function(oPeriodo, iSeq) {
    oCboPeriodoAvaliacao.addItem(oPeriodo.codigo_periodo, oPeriodo.descricao_periodo.urlDecode());
  });

}

function js_pesquisarAlunos() {

  js_divCarregando('Aguarde, pesquisando Alunos', 'msgBox');
  var oParametros           = new Object();
      oParametros.exec      = "buscaAlunosPorTurma";
      oParametros.iTurma    = oCboTurma.getValue();
      oParametros.sSituacao = "MATRICULADO";
  var oAjax = new Ajax.Request(sUrlRPC,
                               { 
                                 method:       'post', 
                                 parameters:   'json='+Object.toJSON(oParametros),
                                 asynchronous: false,
                                 onComplete:   js_pesquisarAlunosRetorno
                               });  
      
    
    
};

function js_pesquisarAlunosRetorno(oAjax) {

  js_removeObj('msgBox');
  oCboAlunos.clearItens();
  oCboAlunosSelecionados.clearItens();
  var oRetorno = eval("("+oAjax.responseText+")");
  
  oRetorno.dados.each(function(oAluno, iSeq) {
    oCboAlunos.addItem(oAluno.iCodigo, oAluno.sNome.urlDecode());
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
	moveSelected(oCboAlunos, oCboAlunosSelecionados);
});

$('btnMoveOneLeftToRight').observe("click", function() {
	moveSelected(oCboAlunosSelecionados, oCboAlunos);
});

$('btnMoveAllRightToLeft').observe("click", function() {
	moveAll(oCboAlunos, oCboAlunosSelecionados);
});

$('btnMoveAllLeftToRight').observe("click", function() {
	moveAll(oCboAlunosSelecionados, oCboAlunos);
});

$('btnImprimirFicha').observe("click", function() {

	var aAlunos             = new Array();
  var aPeriodos           = new Array(); 
	var aAlunosCombo        = oCboAlunosSelecionados.aItens;
  var aPeriodosAvaliacao  = $$('#cboPeriodoAvaliacao option');
  var iPeriodoSelecionado = $('cboPeriodoAvaliacao').selectedIndex;

  aPeriodosAvaliacao.each(function (oElemento, iSeq) {

    if (oElemento.value <= $F('cboPeriodoAvaliacao') ) {
      aPeriodos.push(oElemento.value);
    }
  });
	
  aAlunosCombo.each(function(oItem, iSeq) {
    
     if (oItem.id.trim() != "") {
       aAlunos.push(oItem.id);
     }
  });
  if (aAlunos == null || aAlunos.length == 0) {
    
    alert('Nenhum aluno selecionado');
    return false;
  }

	var sLocation  = "edu2_fichaacompanhamento002.php?";
	sLocation     += "&iEscola="+iEscola;
	sLocation     += "&iCalendario="+oCboCalendario.getValue();
	sLocation     += "&iTurma="+oCboTurma.getValue();
	sLocation     += "&aPeriodo="+aPeriodos;
	sLocation     += "&aAlunos="+aAlunos;
	jan            = window.open(sLocation, '', 
	  	             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
});

</script>