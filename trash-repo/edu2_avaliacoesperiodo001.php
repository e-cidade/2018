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

$db_opcao = 1;
$oRotulo  = new rotulocampo();

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
                  DBFormCache.js,
                  DBFormSelectCache.js,
                  windowAux.widget.js,
                  datagrid.widget.js,
                  dbtextField.widget.js,
                  dbcomboBox.widget.js"); 
    
    db_app::load("estilos.css, 
                  grid.style.css,
                  dbVisualizadorImpressaoTexto.style.css"
                );
    ?>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
  <form name="form1" id='frmAvaliacaoPeriodo' method="post">
      <center>      
        <div style='display:table;' id='ctnForm'>
          <fieldset>
          <legend style="font-weight: bold">Registro de Avalia��es por Per�odo </legend>
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
                  <b>Calend�rio: </b>
                </td>
                <td nowrap id="ctnCboCalendario">
               </td>
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Turma: </b>
                </td>
                <td nowrap id="ctnCboTurma">
                </td> 
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Per�odo: </b>
                </td>
                <td nowrap id="ctnCboPeriodo">
                </td> 
              </tr>              
              <tr>
                <td nowrap title="" >
                  <b>N� de Avalia��es: </b>
                </td>
                <td nowrap id="ctnCboNumeroAvaliacoes">
                <?php 
                  $aAvaliacoes = array(1=>1, 2=>2, 3=>3, 4=>4, 5=>5, 6=>6);
                  db_select('iNumeroAvaliacoes', $aAvaliacoes, true, $db_opcao, "style='width:100%;'");
                ?>
                </td> 
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Exibir Trocas de Turma:</b>
                </td>
                <td nowrap id="ctnCboTrocaTurma" >
                <?php 
                  $aTrocaTurma = array(1=>"N�o", 2=>"Sim");
                  db_select('trocaTurma', $aTrocaTurma, true, $db_opcao, "style='width:100%;'");
                ?>
                </td> 
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Modelo:</b>
                </td>
                <td nowrap id="ctnCboNumeroAvaliacoes">
                	<select id="modelo" name="modelo" onChange="js_validaModelo()" style="width: 100%;">
                		<option value="1">Modelo 1</option>
                		<option value="2">Modelo 2</option>
                	</select>
                </td> 
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Exibir somente alunos ativos:</b>
                </td>
                <td nowrap id="ctnCboNumeroAvaliacoes">
                	<select id="alunosAtivos" name="alunosAtivos" disabled="disabled" style="width: 100%;">
                		<option value="2">Sim</option>
                		<option value="1">N�o</option>
                	</select>
                </td> 
              </tr>
              <tr id="recuperacao" style="display: none;">
                <td nowrap title="" >
                  <b>Exibir recupera��o:</b>
                </td>
                <td nowrap id="ctnCboNumeroAvaliacoes">
                	<select id="exibirRecuperacao" name="exibirRecuperacao" style="width: 100%;">
                		<option value="2">Sim</option>
                		<option value="1">N�o</option>
                	</select>
                </td> 
              </tr>
            </table>
            </fieldset>
            <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Disciplinas para Impress�o</legend>
              <table>
                <tr>
                  <td id='ctnDisciplinas'></td>
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
          </fieldset>
        </div>
        <input name="btnProcessarRelatorio" id="btnProcessarRelatorio" type="button" value="Processar Relat�rio">
        <input type="hidden" id='sSessionNome'>
      </center>
    </form>
  </body>
</html>
<script type="text/javascript">
sUrlRPC = 'edu4_escola.RPC.php';

function js_init() {

	oCboEscola = new DBComboBox("cboEscola", "oCboEscola", null, "100%");
	oCboEscola.addItem("", "Selecione");
	oCboEscola.addEvent("onChange", "js_pesquisarCalendarios()");
	oCboEscola.show($('ctnCboEscola'));

	oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "100%");
	oCboCalendario.addItem("", "Selecione");
	oCboCalendario.addEvent("onChange", "js_pesquisarTurmas()");
	oCboCalendario.show($('ctnCboCalendario'));

	oCboTurma = new DBComboBox("cboTurma", "oCboTurma", null, "100%");
	oCboTurma.addItem("", "Selecione");
	oCboTurma.addEvent("onChange", "js_pesquisarPeriodos()");
	oCboTurma.show($('ctnCboTurma'));

	oCboPeriodo = new DBComboBox("cboPeriodo", "oCboPeriodo", null, "100%");
	oCboPeriodo.addItem("", "Selecione");
	oCboPeriodo.addEvent("onChange", "js_pesquisarDisciplinas()");
	oCboPeriodo.show($('ctnCboPeriodo'));

	oCboDisciplinas = new DBComboBox("cboDisciplinas", "oCboDisciplinas", null, "200px", 10);
	oCboDisciplinas.setMultiple(true);
	oCboDisciplinas.addEvent("onDblClick", "moveSelected(oCboDisciplinas, oCboDisciplinasSelecionadas)");
	oCboDisciplinas.show($('ctnDisciplinas'));

	oCboDisciplinasSelecionadas = new DBComboBox("cboDisciplinasSelecionadas", "oCboDisciplinasSelecionadas", null, "200px", 10);
	oCboDisciplinasSelecionadas.setMultiple(true);
	oCboDisciplinasSelecionadas.addEvent("onDblClick", "moveSelected(oCboDisciplinasSelecionadas, oCboDisciplinas)");
	oCboDisciplinasSelecionadas.show($('ctnDisciplinasSelecionadas'));


	js_validaModelo();
	
	js_pesquisaEscolas();
	oDBFormCache = new DBFormCache('oDBFormCache', 'edu2_avaliacoesperiodo001.php');
  oDBFormCache.setElements(new Array($('trocaTurma'), $('iNumeroAvaliacoes')));
  oDBFormCache.load();
}

function js_pesquisaEscolas() {

	var oParametro          = new Object();
	oParametro.exec         = 'getEscola';
	oParametro.filtraModulo = true;
	js_divCarregando("Aguarde, carregando as escolas.", "msgBox");
	var oAjax = new Ajax.Request(
			                         sUrlRPC,
			                         {
				                         method:     'post',
				                         parameters: 'json='+Object.toJSON(oParametro),
				                         onComplete: js_retornaPesquisaEscolas
			                         }
			                        ); 
}

function js_retornaPesquisaEscolas(oResponse) {

	var oRetorno = eval('('+oResponse.responseText+')');
	js_removeObj("msgBox");
	oCboEscola.clearItens();
	oCboEscola.addItem("", "Selecione");
	oCboCalendario.clearItens();
	oCboCalendario.addItem("", "Selecione");
	oCboTurma.clearItens();
	oCboTurma.addItem("", "Selecione");
	oCboPeriodo.clearItens();
  oCboPeriodo.addItem("", "Selecione");
  oCboDisciplinas.clearItens();
	oCboDisciplinasSelecionadas.clearItens();
	oRetorno.itens.each(function(oLinha, iContador) {
		oCboEscola.addItem(oLinha.codigo_escola, oLinha.nome_escola.urlDecode());
	});
	if (oRetorno.itens.length == 1) {

		oCboEscola.setValue(oRetorno.itens[0].codigo_escola);
		js_pesquisarCalendarios();
	}
}

function js_pesquisarCalendarios() {

	if(oCboEscola.getValue() == '') {

		oCboCalendario.clearItens();
		oCboCalendario.addItem("", "Selecione");
		oCboTurma.clearItens();
		oCboTurma.addItem("", "Selecione");
		oCboPeriodo.clearItens();
		oCboPeriodo.addItem("", "Selecione");
		oCboDisciplinas.clearItens();
		oCboDisciplinasSelecionadas.clearItens();
		return false;
	}
  var oParametro    = new Object();
  oParametro.exec   = 'PesquisaCalendario';
  oParametro.escola = oCboEscola.getValue();
  js_divCarregando("Aguarde, pesquisando calend�rios.", "msgBox");
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
	oCboTurma.clearItens();
	oCboTurma.addItem("", "Selecione");
	oCboPeriodo.clearItens();
	oCboPeriodo.addItem("", "Selecione");
	oCboDisciplinas.clearItens();
	oCboDisciplinasSelecionadas.clearItens();
	oRetorno.aResult.each(function(oLinha, iContador) {
		oCboCalendario.addItem(oLinha.ed52_i_codigo, oLinha.ed52_c_descr.urlDecode());
	});
	if (oRetorno.aResult.length == 1) {

		oCboCalendario.setValue(oRetorno.aResult[0].ed52_i_codigo);
		js_pesquisarTurmas();
	}
}

function js_pesquisarTurmas() {

	if(oCboCalendario.getValue() == '') {

		oCboTurma.clearItens();
		oCboTurma.addItem("", "Selecione");
		oCboPeriodo.clearItens();
		oCboPeriodo.addItem("", "Selecione");
		oCboDisciplinas.clearItens();
		oCboDisciplinasSelecionadas.clearItens();
		return false;
	}
	
	var oParametro         = new Object();
	oParametro.exec        = 'getTurmas';
	oParametro.iEscola     = oCboEscola.getValue();
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
	oCboTurma.clearItens();
	oCboTurma.addItem("", "Selecione");
	oCboPeriodo.clearItens();
	oCboPeriodo.addItem("", "Selecione");
	oCboDisciplinas.clearItens();
	oCboDisciplinasSelecionadas.clearItens();
	oRetorno.aTurmas.each(function(oLinha, iContador) {
		oCboTurma.addItem(oLinha.codigo_turma, oLinha.nome_turma.urlDecode());
	});

	if (oRetorno.aTurmas.length == 1) {

		oCboTurma.setValue(oRetorno.aTurmas[0].codigo_turma);
		js_pesquisarPeriodos();
	}
}

function js_pesquisarPeriodos() {

	if(oCboTurma.getValue() == '') {

		oCboPeriodo.clearItens();
		oCboPeriodo.addItem("", "Selecione");
		oCboDisciplinas.clearItens();
		oCboDisciplinasSelecionadas.clearItens();
		return false;
	}
	
	var oParametro    = new Object();
	oParametro.exec   = 'getPeriodosAvaliacaoPorTurma';
	oParametro.iTurma = oCboTurma.getValue();

	js_divCarregando("Aguarde, carregando os per�odos da turma.", "msgBox");
	var oAjax = new Ajax.Request(
			                         sUrlRPC,
			                         {
				                         method:     'post',
				                         parameters: 'json='+Object.toJSON(oParametro),
				                         onComplete: js_retornaPesquisarPeriodos
			                         }
			                        );
}

function js_retornaPesquisarPeriodos(oResponse) {

	var oRetorno = eval('('+oResponse.responseText+')');
  js_removeObj("msgBox");
  oCboPeriodo.clearItens();
	oCboPeriodo.addItem("", "Selecione");
	oCboDisciplinas.clearItens();
	oCboDisciplinasSelecionadas.clearItens();
  oRetorno.aPeriodos.each(function(oLinha, iContador) {
    oCboPeriodo.addItem(oLinha.codigo_periodo, oLinha.descricao_periodo.urlDecode());
  });

  if(oRetorno.aPeriodos.length == 1) {
    
    oCboPeriodo.setValue(oRetorno.aPeriodos[0].codigo_periodo);
    js_pesquisarDisciplinas();
  }
}

function js_pesquisarDisciplinas() {

	if(oCboPeriodo.getValue() == '') {

		oCboDisciplinas.clearItens();
		oCboDisciplinasSelecionadas.clearItens();
		return false;
	}
	
	var oParametro    = new Object();
	oParametro.exec   = 'getDisciplinasTurma';
	oParametro.iTurma = oCboTurma.getValue();

	js_divCarregando("Aguarde, carregando as disciplinas.", "msgBox");
	var oAjax = new Ajax.Request(
			                         sUrlRPC,
			                         {
				                         method: 'post',
				                         parameters: 'json='+Object.toJSON(oParametro),
				                         onComplete: js_retornaPesquisarDisciplinas
			                         }
			                        );
}

function js_retornaPesquisarDisciplinas(oResponse) {

	var oRetorno = eval('('+oResponse.responseText+')');
	oCboDisciplinas.clearItens();
	oCboDisciplinasSelecionadas.clearItens();
	js_removeObj("msgBox");
	oRetorno.aDisciplinas.each(function(oLinha, iContador) {
		oCboDisciplinas.addItem(oLinha.codigo_disciplina, oLinha.nome_disciplina.urlDecode());
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

function js_verificaCampos() {

	var iEscola                  = oCboEscola.getValue();
	var iCalendario              = oCboCalendario.getValue();
	var iTurma                   = oCboTurma.getValue();
	var iPeriodo                 = oCboPeriodo.getValue();
	var iDisciplinasSelecionadas = $('cboDisciplinasSelecionadas').options.length;
  var aDisciplinasSelecionadas = oCboDisciplinasSelecionadas.aItens; 

  if(iEscola == '') {

  	alert('Nenhuma escola foi selecionada');
    return false;
  }
  if(iCalendario == '') {

  	alert('Nenhum calend�rio foi selecionado');
    return false;
  }
  if(iTurma == '') {

  	alert('Nenhuma turma foi selecionada');
    return false;
  }
  if(iPeriodo == '') {

  	alert('Nenhum per�odo foi selecionado');
    return false;
  }
  if (iDisciplinasSelecionadas == 0) {
    
    alert('Nenhuma Disciplina selecionada');
    return false;
  }
}

$('btnMoveOneRightToLeft').observe("click", function() {
	moveSelected(oCboDisciplinas, oCboDisciplinasSelecionadas);
});

$('btnMoveOneLeftToRight').observe("click", function() {
	moveSelected(oCboDisciplinasSelecionadas, oCboDisciplinas);
});

$('btnMoveAllRightToLeft').observe("click", function() {
	moveAll(oCboDisciplinas, oCboDisciplinasSelecionadas);
});

$('btnMoveAllLeftToRight').observe("click", function() {
	moveAll(oCboDisciplinasSelecionadas, oCboDisciplinas);
});

$('btnProcessarRelatorio').observe("click", function() {
  
  oDBFormCache.save();
	if (js_verificaCampos() == false) {
		return false;
	}	
	var aDisciplinasSelecionadas = oCboDisciplinasSelecionadas.aItens;
	var aDisciplinas = new Array();
	aDisciplinasSelecionadas.each(function(oLinha, id) {
		
		aDisciplinas.push(oLinha.id);
	});
	var sLocation = "edu2_avaliacoesperiodo002.php?";

	if ($F('modelo') == 2) {
	  sLocation = "edu2_avaliacoesperiodo003.php?";
	}
	
	sLocation += "iEscola="+oCboEscola.getValue();
	sLocation += "&iCalendario="+oCboCalendario.getValue();
	sLocation += "&iTurma="+oCboTurma.getValue();
	sLocation += "&iPeriodo="+oCboPeriodo.getValue();
	sLocation += "&iAvaliacoes="+$F('iNumeroAvaliacoes');
	sLocation += "&aDisciplinas="+aDisciplinas;
	sLocation += "&trocaTurma="+$F('trocaTurma');
	sLocation += "&iAlunosAtivos="+$F('alunosAtivos');
	sLocation += "&iExibirRecuperacao="+$F('exibirRecuperacao');
	
	
	jan = window.open(sLocation,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
});

js_init();

function js_validaModelo() {

  
  switch ($F('modelo')) {
    case '1':

      $('recuperacao').style.display = 'none';
      $('alunosAtivos').setAttribute('disabled', 'disabled');
      break;
    case '2':

      $('alunosAtivos').removeAttribute('disabled');
      $('recuperacao').style.display = 'table-row';
      break;
  }
  
}
</script>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>