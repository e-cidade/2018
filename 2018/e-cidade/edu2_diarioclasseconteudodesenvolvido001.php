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

require("libs/db_stdlib.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("ed61_i_aluno");
$clrotulo->label("ed47_i_codigo");
$clrotulo->label("ed47_v_nome");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js,
                  prototype.js,
                  strings.js,
                  arrays.js,
                  windowAux.widget.js,
                  datagrid.widget.js,
                  dbmessageBoard.widget.js,
                  dbcomboBox.widget.js,
                  dbtextField.widget.js,
                  webseller.js,
                  DBVisualizadorImpressaoTexto.js");

    db_app::load("estilos.css,
                  grid.style.css,
                  dbVisualizadorImpressaoTexto.style.css"
                );
    ?>
    <style type="text/css">
      .tabela tr td:FIRST-CHILD {
        width: 150px;
      }

     .prePagina {font-family: monospace;}
     .bold {font-weight: bold;}
    </style>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
  <form name="form1" id='frmDiarioClasse' method="post">
      <center>
        <div style='display:table;' id='ctnForm'>
          <fieldset>
          <legend style="font-weight: bold">Diário de Classe - Conteúdos desenvolvidos</legend>
            <table class="tabela" border='0' width="100%">
              <tr>
                <td nowrap title="" >
                  <b>Escola : </b>
                </td>
                <td nowrap id="ctnCboEscola">
                </td>
              </tr>
              <tr>
               <td nowrap title="" >
                  <b>Calendário : </b>
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
                  <b>Período: </b>
                </td>
                <td nowrap id="ctnCboPeriodo">
                </td>
              </tr>
            </table>

            <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Disciplinas</legend>
              <table>
                <tr>
                  <td id='ctnDisciplinas' ></td>
                  <td>
                   <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999; width: 40px'>&gt;</button><br>
                   <button type='button' id='btnMoveAllRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
                   <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
                   <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
                  </td>
                  <td id='ctnDisciplinasSelecionadas'></td>
                </tr>
              </table>
            </fieldset>
            <table class="tabela">
            	<tr>
            		<td class="bold">
            			<label>Número de páginas</label>
            		</td>
            		<td>
            			<select id='numeroPaginas' >
            				<option value='1' selected="selected">1</option>
            				<option value='2'>2</option>
            				<option value='3'>3</option>
            				<option value='4'>4</option>
            				<option value='5'>5</option>
            			</select>
            		</td>
            	</tr>
            	<tr>
            		<td nowrap="nowrap" class="bold">
            			<label >Preenchimento:</label>
            		</td>
            		<td nowrap="nowrap" class="bold">
									<input type="radio" value='manual' name='preenchimento' id='manualPreenchimento' checked="checked" />
								  <label for = 'manualPreenchimento'>Registro Manual</label>
									<input type="radio" value='diario' name='preenchimento' id='diarioPreenchimento'/>
									<label for ='diarioPreenchimento'>Registro Frequência/Conteúdo</label>
            		</td>
            	</tr>
            </table>
          </fieldset>
        </div>
        <input name="btnImprimir" id="btnImprimir" type="button" value="Imprimir">
        <input type="hidden" id='sSessionNome'>
      </center>
    </form>
  </body>
<?
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
  <script type="text/javascript">

  var sUrlRpc  = "edu4_escola.RPC.php";
  var sRpcBase = "edu_educacaobase.RPC.php";

  oCboEscola   = new DBComboBox("cboEscola", "oCboEscola",null, "100%");
  oCboEscola.addItem("", "Selecione");
  oCboEscola.addEvent("onChange", "js_pesquisarCalendario()");
  oCboEscola.show($('ctnCboEscola'));

  oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "100%");
  oCboCalendario.addItem("", "Selecione");
  oCboCalendario.addEvent("onChange", "js_pesquisarTurmas()");
  oCboCalendario.show($('ctnCboCalendario'));

  oCboTurma = new DBComboBox("cboTurma", "oCboTurma", null, "100%");
  oCboTurma.addEvent("onChange", "js_pesquisarDisciplinas()");
  oCboTurma.addItem("", "Selecione");
  oCboTurma.show($('ctnCboTurma'));

  oCboPeriodo = new DBComboBox("cboPeriodo", "oCboPeriodo", null, "100%");
  oCboPeriodo.show($('ctnCboPeriodo'));

  oCboDisciplinas  = new DBComboBox("cboDisciplinas", "oCboDisciplinas", null,"200px", 10);
  oCboDisciplinas.setMultiple(true);
  oCboDisciplinas.addEvent("onDblClick", "moveSelected(oCboDisciplinas, oCboDisciplinasSelecionadas)");
  oCboDisciplinas.show($('ctnDisciplinas'));

  oCboDisciplinasSelecionadas  = new DBComboBox("cboDisciplinasSelecionadas", "oCboDisciplinasSelecionadas",null,"200px", 10);
  oCboDisciplinasSelecionadas.setMultiple(true);
  oCboDisciplinasSelecionadas.addEvent("onDblClick", "moveSelected(oCboDisciplinasSelecionadas, oCboDisciplinas)");
  oCboDisciplinasSelecionadas.show($('ctnDisciplinasSelecionadas'));



  function init() {

    carredaDadosEscola();
  }

	function carredaDadosEscola() {

	  var oParametros          = new Object();
    oParametros.exec         = 'getEscola';
    oParametros.filtraModulo = true;

    js_divCarregando('Aguarde, pesquisando Escolas...<br>Esse procedimento pode levar algum tempo.', 'msgBox');
    var oAjax = new Ajax.Request(sUrlRpc ,
                                 { method:'post',
                                   parameters: 'json='+Object.toJSON(oParametros),
                                   onComplete: js_retornoPreencheEscolas
                                 }
                                );

	}

	function js_retornoPreencheEscolas (oAjax) {

	  js_removeObj('msgBox');
	  var oRetorno = eval("("+oAjax.responseText+")");

	  oCboEscola.clearItens();
	  oCboEscola.addItem("", "Selecione");

	  oRetorno.itens.each(function(oEscola, iSeq) {
	     oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
	  });

	  if (oRetorno.itens.length == 1) {

	    oCboEscola.setValue(oRetorno.itens[0].codigo_escola);
	    oCboEscola.lDisabled = true;
	    oCboEscola.setDisable();
	    js_pesquisarCalendario();
	  }
  }

  function js_pesquisarCalendario() {

    oCboDisciplinas.clearItens();
    oCboDisciplinasSelecionadas.clearItens();
    oCboCalendario.clearItens();
    oCboCalendario.addItem("", "Selecione");
    oCboTurma.clearItens();
    oCboTurma.addItem("","Selecione");
    oCboPeriodo.clearItens();

    if (oCboEscola.getValue() == "") {
	    return false;
    }
	  js_divCarregando('Aguarde, pesquisando calendario', 'msgBox');

	  var oParametros    = new Object();
	  oParametros.exec	 = "PesquisaCalendario";
	  oParametros.escola = oCboEscola.getValue();

	  var oAjax = new Ajax.Request(sUrlRpc,
	                              {
													      		method: 'post',
													      		parameters: 'json='+Object.toJSON(oParametros),
													      		onComplete: js_retornoPesquesarCalendario
        												});


  }

  function js_retornoPesquesarCalendario(oResponse) {

		js_removeObj('msgBox');
		var oRetorno = eval("("+oResponse.responseText+")");

		oRetorno.aResult.each(function(oCalendario, iSeq){
			oCboCalendario.addItem(oCalendario.ed52_i_codigo, oCalendario.ed52_c_descr.urlDecode());
		});

		if (oRetorno.aResult.length == 1) {

			oCboCalendario.setValue(oCalendario.ed52_i_codigo);
			js_pesquisarTurmas();
		}
  }

  function js_pesquisarTurmas() {

    oCboDisciplinas.clearItens();
    oCboDisciplinasSelecionadas.clearItens();
    oCboTurma.clearItens();
    oCboTurma.addItem("", "Selecione");
    oCboPeriodo.clearItens();

    if (oCboCalendario.getValue() == "") {
      return false;
    }

    js_divCarregando('Aguarde, pesquisando turmas', 'msgBox');

    var oParametros         = new Object();
    oParametros.exec        = "getTurmas";
    oParametros.iEscola     = oCboEscola.getValue();
    oParametros.iCalendario = oCboCalendario.getValue();

    var oAjax = new Ajax.Request(sUrlRpc ,
                                  { method:'post',
                                    parameters: 'json='+Object.toJSON(oParametros),
                                    onComplete: js_retornoGetTurmas
                                  });
  }

  function js_retornoGetTurmas(oResponse) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");

    oRetorno.aTurmas.each(function(oTurma, iSeq) {
      oCboTurma.addItem(oTurma.codigo_turma, oTurma.nome_turma.urlDecode());
    });

    if (oRetorno.aTurmas.length == 1) {

      oCboTurma.setValue(oTurma.codigo_turma);
    }
    js_pesquisarPeriodos();
  }

  function js_pesquisarPeriodos () {

    oCboDisciplinasSelecionadas.clearItens();
    js_divCarregando('Aguarde, pesquisando Periodos', 'msgBox');

    var oParametros          = new Object();
    oParametros.exec         = "getPeriodosAvaliacaoEscola";
    oParametros.iCalendario  = oCboCalendario.getValue();

    var oAjax = new Ajax.Request(sUrlRpc ,
                                 { method:'post',
                                   parameters: 'json='+Object.toJSON(oParametros),
                                   onComplete: js_retornoGetPeriodos
                                 });
  }

  function js_retornoGetPeriodos(oResponse) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");

    oRetorno.aPeriodos.each(function(oPeriodo, iSeq) {
      oCboPeriodo.addItem(oPeriodo.codigo_periodo, oPeriodo.descricao_periodo.urlDecode());
    });
  }

  function js_pesquisarDisciplinas () {

    oCboDisciplinasSelecionadas.clearItens();
    oCboDisciplinas.clearItens();

    if (oCboTurma.getValue() == "") {
      return false;
    }
    js_divCarregando('Aguarde, pesquisando disciplinas', 'msgBox');

    var oParametros          = new Object();
    oParametros.exec         = "getDisciplinaTurma";
    oParametros.iCodigoTurma = oCboTurma.getValue();

    var oAjax = new Ajax.Request(sRpcBase ,
                                 { method:'post',
                                   parameters: 'json='+Object.toJSON(oParametros),
                                   onComplete: js_retornoGetDisciplinas
                                 });
  }

  function js_retornoGetDisciplinas(oResponse) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");

    oCboDisciplinas.clearItens();
    oRetorno.aDisciplinas.each(function(oDisciplina, iSeq) {
      oCboDisciplinas.addItem(oDisciplina.iRegencia, oDisciplina.sDescricaoDisciplina.urlDecode());
    });

  }

  $('btnMoveOneRightToLeft').observe("click", function() {
    moveSelected(oCboDisciplinas, oCboDisciplinasSelecionadas);
  });

  $('btnMoveAllRightToLeft').observe("click", function() {
    moveAll(oCboDisciplinas, oCboDisciplinasSelecionadas);
  });

  $('btnMoveOneLeftToRight').observe("click", function() {
    moveSelected(oCboDisciplinasSelecionadas, oCboDisciplinas);
  });

  $('btnMoveAllLeftToRight').observe("click", function() {
    moveAll(oCboDisciplinasSelecionadas, oCboDisciplinas);
  });

  function moveSelected(oComboOrigin, oComboDestiny) {

    if (oComboOrigin.getValue() != null) {

      var aItens = oComboOrigin.getValue();
      aItens.each(function(oItem, iSeq) {

        oItem = oComboOrigin.aItens[oItem];
        oComboDestiny.addItem(oItem.id, oItem.descricao);
        oComboOrigin.removeItem(oItem.id);
      });
    }

  }

  function moveAll(oComboOrigin, oComboDestiny) {

    oComboOrigin.aItens.each(function(oItem, iSeq) {

      oComboDestiny.addItem(oItem.id, oItem.descricao);
      oComboOrigin.removeItem(oItem.id);
    });
  }

	init();

	$('btnImprimir').observe('click', function () {

	  var iDisciplinasSelecionadas = $('cboDisciplinasSelecionadas').options.length;

    if (iDisciplinasSelecionadas == 0) {

      alert('Selecione ao menos uma disciplina para impressão do relatório');
      return false;
    }

    var aDisciplinas = new Array();
    oCboDisciplinasSelecionadas.aItens.each(function(oDisciplina, id) {
      aDisciplinas.push(oDisciplina.id);
    });

		var sPreenchimento = 'manual';

		if ($('diarioPreenchimento').checked) {
			sPreenchimento = 'diario';
		}

    var sUrlRelatorio = 'edu2_diarioclasseconteudodesenvolvido002.php';
    sUrlRelatorio    += '?escola='+oCboEscola.getValue();
    sUrlRelatorio    += '&calendario='+oCboCalendario.getValue();
    sUrlRelatorio    += '&turma='+oCboTurma.getValue();
    sUrlRelatorio    += '&periodo='+oCboPeriodo.getValue();
    sUrlRelatorio    += '&disciplinas='+aDisciplinas;
    sUrlRelatorio    += '&paginas='+$F('numeroPaginas');
    sUrlRelatorio    += '&preenchimento='+sPreenchimento;
    sUrlRelatorio    += '&lRegistroOcorrencia=false';

    jan = window.open(sUrlRelatorio, '',
                      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
    jan.moveTo(0,0);

	});

  </script>
</html>