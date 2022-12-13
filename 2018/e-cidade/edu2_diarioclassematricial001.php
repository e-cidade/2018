<?
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
      div#ctnForm table tr td:FIRST-CHILD{width: 150px;}
      
     .prePagina {font-family: monospace;} 
    </style>
  </head>
  <body style='margin-top: 25px' bgcolor="#cccccc">
  <form name="form1" id='frmDiarioClasse' method="post">
      <center>      
        <div style='display:table;' id='ctnForm'>
          <fieldset>
          <legend style="font-weight: bold">Registro de Turma - Impressão Matricial </legend>
            <fieldset style='border: 0px;'>
            <table border='0' width="100%">
              <tr> 
                <td nowrap title="" >
                  <b>Escolas : </b>
                </td>
                <td nowrap id="ctnCboEscola">
                </td>
              </tr>           
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
                  <b>Modelo: </b>
                </td>
                <td nowrap id="ctnCboModelo">
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
            </fieldset>
            
            <fieldset style="border:none;border-top:2px groove white;font-weight: bold">
              <legend>Disciplinas</legend>
              <table>
                <tr>
                  <td><b>Disciplinas para Impressão:</b></td>
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
        <input name="btnImprimir" id="btnImprimir" type="button" value="Imprimir">
        <input type="hidden" id='sSessionNome'>
      </center>
    </form>
  </body>
  <script>

  sUrlRpc = "edu4_escola.RPC.php";
    
  init = function () { 
  
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
    oCboPeriodo.addItem("", "Selecione");
    oCboPeriodo.show($('ctnCboPeriodo'));
    
    oCboModelo = new DBComboBox("cboModelo", "oCboModelo", null, "100%");
    oCboModelo.addItem("1", "Educação Infantil");
    oCboModelo.addItem("2", "Etapas Iniciais");
    oCboModelo.addItem("3", "Etapas Finais");
    oCboModelo.show($('ctnCboModelo'));
    
    var oParametros          = new Object();
    oParametros.exec         = 'getEscola';
    oParametros.filtraModulo = true;

    oCboDisciplinas  = new DBComboBox("cboDisciplinas", "oCboDisciplinas", null,"150px", 10);
    oCboDisciplinas.setMultiple(true);
    oCboDisciplinas.addEvent("onDblClick", "moveSelected(oCboDisciplinas, oCboDisciplinasSelecionadas)");
    oCboDisciplinas.show($('ctnDisciplinas'));
    
    oCboDisciplinasSelecionadas  = new DBComboBox("cboDisciplinasSelecionadas", "oCboDisciplinasSelecionadas",null,"150px", 10);
    oCboDisciplinasSelecionadas.setMultiple(true);
    oCboDisciplinasSelecionadas.addEvent("onDblClick", "moveSelected(oCboDisciplinasSelecionadas, oCboDisciplinas)");
    oCboDisciplinasSelecionadas.show($('ctnDisciplinasSelecionadas'));



    
    js_divCarregando('Aguarde, pesquisando Escolas...<br>Esse procedimento pode levar algum tempo.', 'msgBox')
    var oAjax = new Ajax.Request(sUrlRpc ,
                                   { 
                                     method:'post', 
                                     parameters: 'json='+Object.toJSON(oParametros),
                                     onComplete: js_retornoPreencheEscolas
                                   }    
                                );
                                
                                
                                
  }

  js_retornoPreencheEscolas = function (oAjax) {
      
   js_removeObj('msgBox');
   var oRetorno = eval("("+oAjax.responseText+")");

     oCboEscola.clearItens(); 
     oCboEscola.addItem("", "Selecione");
     oRetorno.itens.each(function(oEscola, iSeq) {
        oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
     });
                   
     if (oRetorno.itens.length == 1) {
        
       oCboEscola.setValue(oRetorno.itens[0].codigo_escola);
       js_pesquisarCalendario();
     }
  }

  js_pesquisarCalendario = function() {

    oCboDisciplinas.clearItens();  
    oCboDisciplinasSelecionadas.clearItens();
    oCboCalendario.clearItens(); 
    oCboCalendario.addItem("", "Selecione");
    oCboTurma.clearItens(); 
    oCboTurma.addItem("", "Selecione");
    oCboPeriodo.clearItens(); 
    if (oCboEscola.getValue() == "") {
      return false;
    }
    js_divCarregando('Aguarde, pesquisando calendarios', 'msgBox');
    var oParametros               = new Object();
        oParametros.exec          = "PesquisaCalendario"; 
        oParametros.escola        = oCboEscola.getValue();     
    var oAjax = new Ajax.Request(sUrlRpc ,
                                 { 
                                   method:'post', 
                                   parameters: 'json='+Object.toJSON(oParametros),
                                   onComplete: js_retornoPesquisarCalendario
                                 });   
  };
  function js_retornoPesquisarCalendario(oResponse) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
    oRetorno.aResult.each(function(oCalendario, iSeq) {   
      oCboCalendario.addItem(oCalendario.ed52_i_codigo, oCalendario.ed52_c_descr.urlDecode());
    });
                     
    if (oRetorno.aResult.length == 1) {

      oCboCalendario.setValue(oRetorno.aResult[0].ed52_i_codigo);
      js_pesquisarTurmas();
    }

  }

  
  js_pesquisarTurmas = function() {
      
    oCboDisciplinas.clearItens();   
    oCboDisciplinasSelecionadas.clearItens();
    oCboTurma.clearItens(); 
    oCboTurma.addItem("", "Selecione");
    oCboPeriodo.clearItens(); 
    if (oCboCalendario.getValue() == "") {
      return false;
    }
    js_divCarregando('Aguarde, pesquisando turmas', 'msgBox');
    var oParametros             = new Object();
        oParametros.exec        = "getTurmas";
        oParametros.iEscola     = oCboEscola.getValue();
        oParametros.iCalendario = oCboCalendario.getValue();
    
    var oAjax = new Ajax.Request(sUrlRpc ,
                               { 
                                 method:'post', 
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoGetTurmas
                               });  
        
      
      
  };

  function js_retornoGetTurmas(oResponse) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
      oRetorno.aTurmas.each(function(oTurma, iSeq) {
        oCboTurma.addItem(oTurma.codigo_turma, oTurma.nome_turma.urlDecode());
      });
                     
      if (oRetorno.aTurmas.length == 1) {
        oCboTurma.setValue(oRetorno.aTurmas[0].codigo_turma);
      }
      js_pesquisarPeriodos();
  }
  
  function js_pesquisarDisciplinas () {
   
    oCboDisciplinasSelecionadas.clearItens();
    oCboDisciplinas.clearItens();
    if (oCboTurma.getValue() == "") {
      return false;
    } 
    js_divCarregando('Aguarde, pesquisando disciplinas', 'msgBox');
    var oParametros              = new Object();
        oParametros.exec         = "getDisciplinasTurma";
        oParametros.iTurma       = oCboTurma.getValue();
    var oAjax = new Ajax.Request(sUrlRpc ,
                               { 
                                 method:'post', 
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoGetDisciplinas
                               });  
        
      
      
  };

  function js_retornoGetDisciplinas(oResponse) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
    oCboDisciplinas.clearItens(); 
    oRetorno.aDisciplinas.each(function(oDisciplina, iSeq) {
      oCboDisciplinas.addItem(oDisciplina.codigo_disciplina, oDisciplina.nome_disciplina.urlDecode());
    });
  
  }

  function js_pesquisarPeriodos () {

    oCboDisciplinasSelecionadas.clearItens(); 
    js_divCarregando('Aguarde, pesquisando Periodos', 'msgBox');
    var oParametros              = new Object();
        oParametros.exec         = "getPeriodosAvaliacaoEscola";
        oParametros.iCalendario  = oCboCalendario.getValue();
    var oAjax = new Ajax.Request(sUrlRpc ,
                               { 
                                 method:'post', 
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoGetPeriodos
                               });  
        
      
      
  };

  function js_retornoGetPeriodos(oResponse) {
    
    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
    oRetorno.aPeriodos.each(function(oPeriodo, iSeq) {
      oCboPeriodo.addItem(oPeriodo.codigo_periodo, oPeriodo.descricao_periodo.urlDecode());
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


  
  $('btnImprimir').observe('click', function() {
	  
    var aDisciplinasSelecionadas = oCboDisciplinasSelecionadas.aItens; 
    if (aDisciplinasSelecionadas == null || aDisciplinasSelecionadas.length == 0) {
      
      alert('Nenhuma Disciplina selecionada');
      return false;
    }
    var aDisciplinas = new Array();
    aDisciplinasSelecionadas.each(function(oDisciplina, id) {
      aDisciplinas.push(oDisciplina.id);    
    });
    js_divCarregando('Aguarde, arquivo sendo processado', 'msgBox');
    var oParametros              = new Object();
        oParametros.exec         = "processarImpressaoDiarioClasse";
        oParametros.iTurma       = oCboTurma.getValue();
        oParametros.aDisciplinas = aDisciplinas;
        oParametros.iModelo      = oCboModelo.getValue();
        oParametros.sPeriodo     = encodeURIComponent(tagString(oCboPeriodo.getLabel()));
    var oAjax = new Ajax.Request('edu04_diarioclassematricial.RPC.php',
                               { 
                                 method:'post', 
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_openVisualizadorImpressao
                               });  
      
  });
  
  function js_openVisualizadorImpressao(oAjax) {
    
    js_removeObj('msgBox');
    var oRetorno            = eval("("+oAjax.responseText+")");
    $('sSessionNome').value = oRetorno.sSessionNome; 
    var iWidth  = document.body.getWidth() - 10;
    oWindow = new windowAux('oWindow', 'Visualizar Impressão', iWidth);
    oWindow.setShutDownFunction(function() {
      oWindow.destroy();
    });
    oWindow.setContent("<div style='width:100%; height:100%' id='visualizador'></div>");
    oVisualizador = new DBVisualizadorImpressaoTexto('visualizador', '80%', '100%');
    oVisualizador.setImpressoras(oRetorno.aImpressoraId, oRetorno.aImpressoraDescr);
    oVisualizador.iIpPadrao = oRetorno.iIpPadrao;
    oVisualizador.setDimensoes(66, 350);
    oVisualizador.gerarVisualizador();
    var iTam = oRetorno.aArquivo.length;
    for (i=0; i < iTam; i++) {
      oVisualizador.addArquivo(oRetorno.aArquivo[i]);
    }
    
    lResultado = oVisualizador.renderizarArquivos();
    $('fechar').onclick= function () {
      oWindow.destroy();
    };
    oWindow.show();
  }
  init();
 </script>
</html>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>