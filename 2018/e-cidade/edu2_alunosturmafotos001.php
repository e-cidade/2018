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
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, windowAux.widget.js, datagrid.widget.js,
                  dbtextField.widget.js, dbcomboBox.widget.js"); 
    db_app::load("estilos.css, grid.style.css,dbVisualizadorImpressaoTexto.style.css");
    ?>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
  <form name="form1" id='frmAvaliacaoPeriodo' method="post">
      <center>      
        <div style='display:table;' id='ctnForm'>
          <fieldset>
          <legend style="font-weight: bold">Alunos por Turma</legend>
            <fieldset style='border: 0px;'>
            <table border='0' width="100%">
              <tr> 
               <td nowrap title="" >
                  <b>Calendarios : </b>
                </td>
                <td nowrap id="ctnCboCalendario">
               </td>
              </tr>
            </table>
            </fieldset>
            <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Turmas do Calendário</legend>
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
        <input name="btnProcessarRelatorio" id="btnProcessarRelatorio" type="button" value="Processar Relatorio">
        <input type="hidden" id='sSessionNome'>
      </center>
    </form>
  </body>
</html>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">
                
sUrlRPC = 'edu2_alunosturmafotos.RPC.php';

oCboCalendario         = new DBComboBox("cboCalendario", "oCboCalendario", null, "100%");
oCboTurmas             = new DBComboBox("cboTurmas", "oCboTurmas", null, "150px", 10);
oCboTurmasSelecionadas = new DBComboBox("cboTurmasSelecionadas", "oCboTurmasSelecionadas", null, "150px", 10);

function js_init() {

	oCboCalendario.addItem("", "Selecione");
	oCboCalendario.addEvent("onChange", "js_pesquisarTurmas()");
	oCboCalendario.show($('ctnCboCalendario'));

	oCboTurmas.setMultiple(true);
	oCboTurmas.addEvent("onDblClick", "moveSelected(oCboTurmas, oCboTurmasSelecionadas)");
	oCboTurmas.show($('ctnTurmas'));
	
	oCboTurmasSelecionadas.setMultiple(true);
	oCboTurmasSelecionadas.addEvent("onDblClick", "moveSelected(oCboTurmasSelecionadas, oCboTurmas)");
	oCboTurmasSelecionadas.show($('ctnTurmasSelecionadas'));

	js_pesquisarCalendarios();
}

/**
 * Busca os calendários da Escola logada
 */
function js_pesquisarCalendarios() {

  oCboTurmas.clearItens();
	oCboTurmasSelecionadas.clearItens();
	
  var oParametro    = new Object();
  oParametro.exec   = 'PesquisaCalendario';
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

	var oRetorno = eval('('+oResponse.responseText+')');
	js_removeObj("msgBox");
	oCboCalendario.clearItens();
	oCboCalendario.addItem("", "Selecione");
	oCboTurmas.clearItens();
	oCboTurmasSelecionadas.clearItens();
	
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

	if(oCboCalendario.getValue() == '') {

		oCboTurmas.clearItens();
		oCboTurmasSelecionadas.clearItens();
		return false;
	}
	
	var oParametro         = new Object();
	oParametro.exec        = 'PesquisaTurmaEscola';
	oParametro.iCalendario = oCboCalendario.getValue();

	js_divCarregando("Aguarde, carregando as turmas.", "msgBox");
	var oAjax = new Ajax.Request(
			                         sUrlRPC,
			                         {
				                         method:     'post',
				                         parameters: 'json='+Object.toJSON(oParametro),
				                         onComplete: js_retornaPesquisarTurmas
			                         }
			                        ); 
}

function js_retornaPesquisarTurmas(oResponse) {

	var oRetorno = eval('('+oResponse.responseText+')');
	js_removeObj("msgBox");
	oCboTurmas.clearItens();
	oCboTurmasSelecionadas.clearItens();
	oRetorno.dados.each(function(oLinha, iContador) {
	  oCboTurmas.addItem(oLinha.codigo_turma, oLinha.nome_turma.urlDecode());
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


$('btnProcessarRelatorio').observe("click", function() {

	var aTurmas             = new Array();
	var aTurmasSelecionadas = oCboTurmasSelecionadas.aItens;

  if (aTurmasSelecionadas == null || aTurmasSelecionadas.length == 0) {
    
    alert('Nenhuma Turma selecionada');
    return false;
  }
  
	aTurmasSelecionadas.each(function(oLinha, id) {
		aTurmas.push(oLinha.id);
	});
	
	var sLocation  = "edu2_alunosturmafotos002.php?";
	sLocation     += "&iCalendario="+oCboCalendario.getValue();
	sLocation     += "&aTurmas="+aTurmas;
	jan            = window.open(sLocation, '', 
	  	             'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
});

js_init();
</script>