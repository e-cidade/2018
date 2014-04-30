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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$db_opcao = 1;
$oRotulo  = new rotulocampo();
$oRotulo->label('ed57_c_descr');

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
      db_app::load("scripts.js, strings.js, datagrid.widget.js, prototype.js, arrays.js, dbtextFieldData.widget.js");
      db_app::load("dbcomboBox.widget.js, windowAux.widget.js, dbmessageBoard.widget.js,dbtextField.widget.js");
      db_app::load("estilos.css, grid.style.css");
    ?>
    <style type="text/css">
      .fieldset-hr {
        border:none;
        border-top: 2px groove #CCCCCC;
      }
    </style>
  </head>
  <body style="margin-top: 25px; background-color: #CCCCCC;">
    <div>
      <center>
        <form action="" name="form1">
         <div style="display: table; width: 90%">
            <fieldset>
              <legend><b>Frequência</b></legend>
              <fieldset class="fieldset-hr">
                <legend><b>Filtros</b></legend>
                <table>
                  <tr>
                    <td>
                      <?=$Led57_c_descr?>
                    </td>
                    <td id='ctnTurmas'>
                    </td>
                    <td></td>
                    <td>
                      <b>Situação</b>
                    </td>
                    <td>
                      <?
                        $aSituacao = array("0" => "Todas", "1" => "Vermelho", "2" => "Amarelo");
                        db_select('situacao', $aSituacao, true, 1);
                      ?>
                    </td>
                    <td></td>
                    <td>
                      <b>Data:</b>
                    </td>
                    <td id='ctnInputData'>
                    </td>
                    <td>
                      <input type="button" value='Filtrar' id='btnFiltrar'>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <fieldset class="fieldset-hr">
                <legend><b>Alunos</b></legend>
                <div id="ctnAlunos">
                </div>
              </fieldset>
            </fieldset>
          </div>
          <input type="button" value="Atualizar" name='atualizar' id='btnAtualizar'>
          <input type="button" value="Enviar Mensagens" name='enviarMensagens' id='btnEnviarMensagens'>
        </form>
      </center>
    </div>
    <?php
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
  </body>
</html>
<script>
var sUrlRPC = 'edu04_controleacessofrequencia.RPC.php';

function js_init() {

  aAlunos         = new Array(); 
	lCarregouTurmas = false;
	oTxtData        = new DBTextFieldData('oTxtDataFiltro', 'oTxtData');
	oTxtData.show($('ctnInputData'));
  
  js_atualizarDadosLeitura();
	js_gridAlunos();
	js_montaComboTurmas();
	if (!lCarregouTurmas) {
		js_pesquisarTurmas();
	}
}

function js_atualizarDadosLeitura() {
  
  var oParametros = new Object();
  oParametros.exec = 'atualizarDados';

  js_divCarregando('Aguarde, Atualizando leituras...<br>Esse procedimento pode levar algum tempo.', 'msgBox')
  var oAjax = new Ajax.Request('edu03_consultaacessoalunos.RPC.php' ,
                             { 
                               method:'post', 
                               parameters: 'json='+Object.toJSON(oParametros),
                               onComplete: js_pesquisarAlunos
                             }

              );
    
  }
/**
 * Cria a grid dos alunos presentes na escola
 */
function js_gridAlunos() {

	var iHeight                  = document.body.getHeight();
	
	oDataGridAlunos              = new DBGrid("gridAlunos");
	oDataGridAlunos.nameInstance = "oDataGridAlunos";
	oDataGridAlunos.setCheckbox(0);
	oDataGridAlunos.setCellAlign(new Array("center", "left", "left", "left"));
	oDataGridAlunos.setHeader(new Array("Matrícula", "Aluno", "Turma/Sala", "Status", "Mensagem"));
	oDataGridAlunos.setCellWidth(new Array("5%", "50%", "20%", "3%"));
	oDataGridAlunos.setHeight(iHeight/0.5);
	oDataGridAlunos.show($('ctnAlunos'));
}

/**
 * Combobox das turmas da escola, no calendario atual
 */
function js_montaComboTurmas() {

	oComboTurmas = new DBComboBox("cboTurmas", "oComboTurmas");
	oComboTurmas.addItem("0", "Todas");
	oComboTurmas.show($('ctnTurmas'));
}

/**
 * Pesquisa as turmas da escola, no calendario atual
 */
function js_pesquisarTurmas() {

	var oParametro  = new Object();
	oParametro.exec = 'getTurmas';

	var oAjax = new Ajax.Request(sUrlRPC,
			                         {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaPesquisarTurmas
			                         }
			                        );
}

/**
 * Retorna a busca das turmas da escola no calendario atual
 */
function js_retornaPesquisarTurmas(oResponse) {

	var oRetorno = eval('('+oResponse.responseText+')');

	if (oRetorno.status == 1) {

		oRetorno.aTurmas.each(function(oTurma, iSeq) {
			
			oComboTurmas.addItem(oTurma.iCodigoTurma, oTurma.sDescricao.urlDecode());
			lCarregouTurmas = true;
		});
	}
}

/**
 * Pesquisa os alunos presentes na escola
 */
function js_pesquisarAlunos() {

  js_removeObj('msgBox');
	var oParametro       = new Object();
	oParametro.exec      = 'getAlunos';
	oParametro.dataDia   = oTxtData.getValue();
	oParametro.iTurma    = oComboTurmas.getValue();
	oParametro.iSituacao = $('situacao').value;

  js_divCarregando("Aguarde, carregando a lista dos alunos.", "msgBox");
	var oAjax = new Ajax.Request(sUrlRPC,
			                         {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaPesquisarAlunos
			                         }
			                        );
}

/**
 * Retorna a busca dos alunos presentes na escola
 */
function js_retornaPesquisarAlunos(oResponse) {

  js_removeObj("msgBox");
  aAlunos      = new Array(); 
	var oRetorno = eval('('+oResponse.responseText+')');

	if (oRetorno.status == 1) {
    
    aAlunos      = oRetorno.aAlunos;   
		oDataGridAlunos.clearAll(true);
		oRetorno.aAlunos.each(function(oAluno, iSeq) {
      
      var iMatricula = oAluno.iMatricula;
      oAluno.aPeriodos = new Array(); 
			var aLinha = new Array();
			aLinha[0]  = '<a href="#" onclick="js_dadosaAlunos('+oAluno.iCodigoAluno+');return false;">'+oAluno.iMatricula+'</a>';
			aLinha[1]  = oAluno.sDescricao.urlDecode();
			var sColorBackground  = 'red';
		  if (oAluno.lAmarelo) {
		    sColorBackground  = 'yellow';
		  }
			
			aLinha[2]  = oAluno.sTurma.urlDecode()+" / "+oAluno.sSala.urlDecode();
			aLinha[3]  = "<div style='background-color:"+sColorBackground+"; width:70%; height: 70%; margin: 0 auto;' ";
			aLinha[3] += " id="+oAluno.iMatricula+" onclick=\"js_montaWindowPeriodos("+oAluno.iMatricula+",";
			aLinha[3] += "                                                           '"+oAluno.sDescricao.urlDecode()+"',";
			aLinha[3] += "                                                           '"+oAluno.dtPesquisa+"',";
			aLinha[3] += "                                                            "+iSeq+")\">";
			aLinha[3] += " </div>";
			aLinha[4]  = eval("oTextoSMS"+iMatricula+" = new DBTextField('oTextoSMS"+iMatricula+"','oTextoSMS"+iMatricula+"','')");
      aLinha[4].addStyle("height","100%");
      aLinha[4].addStyle("width","100%");
      aLinha[4].setMaxLength(160);
      aLinha[4].setReadOnly(true);
			oDataGridAlunos.addRow(aLinha);
		});
		oDataGridAlunos.renderRows();
	}
}

function js_dadosaAlunos(iCodigoAluno) {

    js_OpenJanelaIframe('','db_iframe_aluno',
                       'edu3_alunos001.php?chavepesquisa='+iCodigoAluno+'&fc_close=parent.closeDadosAluno',
                       'Consulta de Alunos',true);
}

function closeDadosAluno() {
  db_iframe_aluno.hide();
}
/**
 * Carrega os periodos do aluno no dia
 */
function js_montaWindowPeriodos(iMatricula, sNome, dtPesquisa, iLinha) {

   if ($('wndPeriodos')) {
     return false;
   }
	 var iTamanhoJanela = document.body.getWidth()/2.5;
	 var iAlturaJanela  = document.body.getHeight()/1.1;
	 oWindowPeriodos    = new windowAux('wndPeriodos', 'Consulta faltas por período', iTamanhoJanela, iAlturaJanela);
	 var sConteudo      = "<div id='conteudo'>";
	 sConteudo         += "  <fieldset>";
	 sConteudo         += "    <legend><b>Consulta dos Períodos</b></legend>";
	 sConteudo         += "    <div id='ctnGridPeriodos'>";
	 sConteudo         += "    </div>";
	 sConteudo         += "  </fieldset>";
	 sConteudo         += "  <center>";
	 sConteudo         += "    <input type='button' value='Salvar' id='btnSalvarPeriodos' ";
	 sConteudo         += "           onclick='js_salvarPeriodos("+iMatricula+", "+iLinha+");'>";
	 sConteudo         += "  </center>"; 
	 sConteudo         += "</div>";
   
	 oWindowPeriodos.setContent(sConteudo);
	 oWindowPeriodos.setShutDownFunction(function() {
		 
		 oWindowPeriodos.destroy();
		
	 });

	 var sHelp = '<b>Matricula:</b> '+iMatricula+'<b> Aluno:</b> '+sNome;
   var oMessageBoardPeriodo = new DBMessageBoard('messageBoardPeriodos',
  	                                             'Consultar períodos do aluno no dia:',
  	                                             sHelp,
  	                                             oWindowPeriodos.getContentContainer()
  	                                            );
   oMessageBoardPeriodo.show();
	 oWindowPeriodos.show();
	 js_gridPeriodos();
	 js_carregaPeriodos(iMatricula, dtPesquisa);
}

/**
 * Monta a grid com os periodos do dia
 */
function js_gridPeriodos() {

	oDataGridPeriodos              = new DBGrid("gridPeriodos");
	oDataGridPeriodos.nameInstance = "oDataGridPeriodos";
	oDataGridPeriodos.setCheckbox(0);
	oDataGridPeriodos.setCellAlign(new Array("center", "left"));
	oDataGridPeriodos.setCellWidth(new Array("15%", "40%", "5%", "40%"));
	oDataGridPeriodos.setHeader(new Array("Período", "Disciplina", "Codigo Falta", "Situação"));
  oDataGridPeriodos.aHeaders[3].lDisplayed = false;
	oDataGridPeriodos.show($('ctnGridPeriodos'));
}

/**
 * Busca os periodos com falta para o aluno selecionado
 * coloca no escopo global um objeto com os dados do aluno.
 */
function js_carregaPeriodos(iMatricula, dtPesquisa) {

  oAlunoCorrente        = getAlunoPorMatricula(iMatricula);
	var oParametro        = new Object();
	oParametro.exec       = 'getPeriodos';
	oParametro.iMatricula = iMatricula;
	oParametro.dtPesquisa = dtPesquisa;

	var oAjax = new Ajax.Request(sUrlRPC,
			                         {
                                 method:     'post',
                                 parameters: 'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornaCarregaPeriodos
			                         }
			                        );
}

/**
 * Retorna a busca dos periodos com falta para o aluno selecionado
 */
function js_retornaCarregaPeriodos(oResponse) {

	var oRetorno = eval('('+oResponse.responseText+')');

	if (oRetorno.status == 1) {

		oDataGridPeriodos.clearAll(true);
		oRetorno.aPeriodosDia.each(function(oPeriodo, iSeq) {

			var aLinha = new Array();
			aLinha[0]  = oPeriodo.sDescricao.urlDecode();
			aLinha[1]  = oPeriodo.sDisciplina.urlDecode();
			aLinha[2]  = oPeriodo.iCodigoFalta;
			aLinha[3]  = oPeriodo.sMensagemRetorno.urlDecode();
			lMarcado   = false;
			lBloqueado = false;
			if (!oPeriodo.lFaltou || oPeriodo.iOcorrencia != "") {
			  lBloqueado = true;
			}
			var sCor = 'green';
			if (oPeriodo.lFaltou) {
			  sCor = 'red';
			}

			oAlunoCorrente.aPeriodos.each(function(iCodigoPeriodoMarcado, iSeqPeriodo) {
			  if (iCodigoPeriodoMarcado == oPeriodo.iCodigoFalta) {
			  
			    lMarcado = true;
			    throw $break;  
			  }
			}); 
		  oDataGridPeriodos.addRow(aLinha, false, lBloqueado, lMarcado);
		  oDataGridPeriodos.aRows[iSeq].aCells[1].sStyle += ";background-color:"+sCor+"';font-weight:bold;";
    });
		oDataGridPeriodos.renderRows();
	}
}

/**
 * Salva as alteracoes realizadas nos periodos do dia do aluno
 */
function js_salvarPeriodos(iMatricula, iLinha) {
  
  var oPeriodosNotificar = oDataGridPeriodos.getSelection("object");
  oAluno                 = getAlunoPorMatricula(iMatricula);
  oAluno.aPeriodos       = new Array();
  
  var sTextoPeriodos = '';
  var sVirgula       = '';
  oPeriodosNotificar.each(function(oPeriodo, iSeq) {
    
    sTextoPeriodos += sVirgula+oPeriodo.aCells[1].getValue();
    sVirgula        = ", ";
    oAluno.aPeriodos.push(oPeriodo.aCells[3].getValue());
  });
  sMensagem  = oAluno.sMensagemVermelha.urlDecode().replace('#periodos#', sTextoPeriodos);  
  if (oAluno.lAmarelo) {
    sMensagem  = oAluno.sMensagemAmarela.urlDecode().replace('#periodos#', sTextoPeriodos);
  }
  if (oPeriodosNotificar.length > 0) {
  
    oDataGridAlunos.aRows[iLinha].select(true);
    oDataGridAlunos.aRows[iLinha].aCells[5].content.setValue(sMensagem);
    oDataGridAlunos.aRows[iLinha].aCells[5].content.setReadOnly(false);
  }
  oWindowPeriodos.destroy();
}

function getAlunoPorMatricula(iMatricula) {
   
   var oAlunoRetorno = '';
   aAlunos.each(function(oAluno, iSeq) {
    
     if (oAluno.iMatricula == iMatricula) {
     
       oAlunoRetorno = oAluno;
       throw $break
     }
   });
   return oAlunoRetorno;
}

 
$('btnFiltrar').observe("click", function() {

	js_atualizarDadosLeitura();
});

$('btnAtualizar').observe("click", function() {

	js_atualizarDadosLeitura();
});

$('btnEnviarMensagens').observe("click", function() {

	/**
	 * Pegamos os alunos selecionados e enviamos para o RPC
	 */
	var aAlunos             = new Array();
	var oAlunosSelecionados = oDataGridAlunos.getSelection("object");
	var lErro               = false;
	if (oAlunosSelecionados.length == 0) {
	
	  alert('Informe ao menos um aluno!');
	  return false;
	}
	oAlunosSelecionados.each(function(oLinha, iSeq) {
	  
	    var oAluno = new Object();
	    oAluno.iMatricula = oLinha.aCells[1].getValue();
	    oAluno.sMensagem  = encodeURIComponent(tagString(oLinha.aCells[5].getValue()));
	    oAluno.aFaltas    = getAlunoPorMatricula(oAluno.iMatricula).aPeriodos;
	    if (oAluno.aFaltas.length == 0) {
	    
	      alert('Não foi selecionado nenhuma falta para notificar o aluno '+oLinha.aCells[2].getValue()+".");
  	    lErro = true;
	      throw $break;
	    }
	    aAlunos.push(oAluno); 
	});
	if (lErro) {
	  return false;
	} 
	if (!confirm('Confirma a emissão de emails/SMS para os responsáveis dos alunos selecionados?')) {
	  return false;
	}
	
	var oParametros     = new Object();
	oParametros.exec    = 'enviarNotificacao';
	oParametros.aAlunos = aAlunos;
	
	js_divCarregando('Aguarde, enviando notificacões.', 'msgBox');
	var oAjax = new Ajax.Request(sUrlRPC,
	                             {method:'post',
	                              parameters:'?json='+Object.toJSON(oParametros),
	                              onComplete:function (oResponse) {
	                                 
	                                 js_removeObj('msgBox');
	                                 var oRetorno = eval("("+oResponse.responseText+")");
	                                 if (oRetorno.status == 1) {
	                                 
	                                   alert('Notificacoes Enviadas com sucesso!');
	                                   js_atualizarDadosLeitura();
	                                 } else {
	                                   alert(oRetorno.message.urlDecode());
	                                 }
	                              }
	                             });   
});

js_init();
</script>