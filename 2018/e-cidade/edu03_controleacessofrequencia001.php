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
require_once("classes/db_matricula_classe.php");
require_once("classes/db_matriculamov_classe.php");
require_once("classes/db_logmatricula_classe.php");
require_once("classes/db_turma_classe.php");
require_once("classes/db_turmaserieregimemat_classe.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
     db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js, windowAux.widget.js, arrays.js");
     db_app::load("dbmessageBoard.widget.js, dbtextFieldData.widget.js, dbcomboBox.widget.js");
     db_app::load("estilos.css, grid.style.css");
    ?>
    <style type="">
    fieldset.fieldsetinterno {
	    border:0px;
	    border-top: 2px groove white;
    }
    </style>
  </head>
  <body bgcolor="#CCCCCC" style='margin-top: 25px'>
    <center>
      <div style="display: table; width: 90%">
        <fieldset>
          <legend>Controle Acesso Aluno/Frequencia</legend>
          <div style="display: table; width: 100%">
            <fieldset class='fieldsetinterno'>
              <legend><b>Filtros</b></legend>
              <table>
                <tr>
                  <td>
                     <b>Data:</b>
                  </td>
                  <td id='ctninputdata'>
                  </td>
                  <td>
                     <input type="button" value='filtrar' id='btnFiltrar'>
                  </td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend>
                <b>Lista de Turmas</b>
              </legend>
              <div id='ctnDataGridTurmas' style="width: 100%;">
              </div> 
            </fieldset>
          </div>
        </fieldset>
      </div>
      <input type="button" value='Dia Anterior' id='btnDiaAnterior'>
      <input type="button" value='Visualizar Alunos' id='btnPesquisarAlunos' onclick='js_visualizarAlunos()'>
      <input type="button" value='Proximo Dia' id='btnProximoDia'>
    </center>
  </body>
</html>  
<?db_menu(db_getsession("DB_id_usuario"),
          db_getsession("DB_modulo"),
          db_getsession("DB_anousu"),
          db_getsession("DB_instit")
         );
?>
<script>
  var sUrlRpc = 'edu04_controleacessofrequencia.RPC.php';
  function js_init() {
  
     oTxtData = new DBTextFieldData('oTxtData', 'oTxtData');
     oTxtData.show($('ctninputdata'));
     oGridTurmas              = new DBGrid('oGridTurmas');
     oGridTurmas.nameInstance = 'oGridTurmas';
     oGridTurmas.setCheckbox(0);
     oGridTurmas.setCellWidth(new Array("10%", "25%", "30%", "5%", "15%", "15%"));
     oGridTurmas.setCellAlign(new Array("Left", "left", "left", "center"));
     oGridTurmas.setHeight(document.body.clientHeight/1.6);
     var aHeaders = new Array("Turma", 
                              "Disciplina", 
                              "Professor", 
                              "Chamada",
                              "Presente Escola c/Falta",
                              "Presente Em Aula s/ Leitura",
                              "Codigo Turma"
                             );
     oGridTurmas.setHeader(aHeaders);
     oGridTurmas.aHeaders[7].lDisplayed = false;
     oGridTurmas.show($('ctnDataGridTurmas'));
     js_getTurmasDoDia();
  }
  js_init();
  
  
  /**
   * Cria a janela com os alunos faltantes
   */
  function js_visualizarAlunos() {
     
    var aTurmasSelecionadas = oGridTurmas.getSelection('object');
    if (aTurmasSelecionadas.length == 0) {
    
      alert('Selecione uma turma.');
      return false;
    } 
    var iWidth         = document.body.getWidth() - 10;
    var iHeight        = document.body.getHeight(); 
    oWindowListaAlunos = new windowAux('wndListaAlunos', 'Lista de Alunos' , iWidth, iHeight);
    oWindowListaAlunos.setShutDownFunction(function(){
      oWindowListaAlunos.destroy();
    }); 
    var sConteudo = '<div>';
        sConteudo += '<fieldset><legend><b>Alunos</b></legend>';
        sConteudo += '  <div id="ctnGridAlunos" style="width:100%">';
        sConteudo += '  </div>';
        sConteudo += '</fieldset>';
        sConteudo += '<center>';
        sConteudo += '  <div style="float:right;">';
        sConteudo += '    <b>PE.:</b>Presente na Escola';
        sConteudo += '    <b>PS.:</b>Presente em Sala de Aula';
        sConteudo += '  </div>';
        sConteudo += '  <div style="float:left;">';
        sConteudo += '     <span><b>Modelo:</b></span>';
        sConteudo += '     <span id="ctnCboModelos"></span>'; 
        sConteudo += '     <span><b>Mostrar Alunos:</b>';
        sConteudo += '     <span id="ctnCboMostrarAlunos"></span>'; 
        sConteudo += '  </div>'
        sConteudo += '    <input type="button" value="imprimir" onclick="js_impressaoAlunos();">';
        sConteudo += '  </center>'; 
        sConteudo += '</div>';
    oWindowListaAlunos.setContent(sConteudo);
    var sMessagemImpressao = 'Caso deseje imprimir a listagem dos alunos, escolha os filtros,  e clique em imprimir';
    var oMessageBoard      = new DBMessageBoard('msgBoardAlunos',
                                                'Listagem de alunos',
                                                sMessagemImpressao,
                                                oWindowListaAlunos.getContentContainer()
                                                );
   oWindowListaAlunos.show();
   
   oDataGridAlunos             = new DBGrid('dbGridAlunos');
   oDataGridAlunos.nameInstance = 'oDataGridAlunos';
   oDataGridAlunos.setHeight(iHeight/1.9); 
   var aWidths                  = new Array("5%", "21%", "5%", "22%", "22%", "10%", "10%");
   var aHeaders                 = new Array("cod.Aluno", 
                                           "Nome", 
                                           "Turma", 
                                           "Pai",
                                           "Resp. Legal",
                                           "Fone Cel.",
                                           "Fone Res.",
                                           "PS",
                                           "PE"
                             );
     oDataGridAlunos.setCellWidth(aWidths);                             
     oDataGridAlunos.setHeader(aHeaders);
     oDataGridAlunos.show($('ctnGridAlunos'));          
     
     
     oCboModelo = new DBComboBox('oCboModelo', 'oCboModelo');
     oCboModelo.addItem(1, 'Simplificado');
     oCboModelo.addItem(2, 'Completo');
     oCboModelo.addStyle("width", "150");
     oCboModelo.show($('ctnCboModelos'));
     
     oCboMostrarAlunos = new DBComboBox('oCboMostrarAlunos', 'oCboMostrarAlunos');
     oCboMostrarAlunos.addItem(1, 'Todos');
     oCboMostrarAlunos.addItem(2, 'Presentes na Escola com Falta');
     oCboMostrarAlunos.addItem(3, 'Sem Leitura/Presente em Aula');
     oCboMostrarAlunos.addStyle("width", "150");
     oCboMostrarAlunos.show($('ctnCboMostrarAlunos'));  
     
     js_getAlunosTurmas();                                            
  }
  
  /**
   * Pesquisa as turmas por dia
   */
  function js_getTurmasDoDia() {
  
    var oParametro     = new Object();
    oParametro.exec    = 'getControleAcessoFrequencia';
    oParametro.datadia = oTxtData.getValue(); 
    oParametro.mostrar = 'turma'; 
    js_divCarregando('Aguarde, carregando as turmas do dia', 'msgBox');
    var oAjax = new Ajax.Request(sUrlRpc, 
                                 {method:'post',
                                 parameters:'json='+Object.toJSON(oParametro),
                                 onComplete: js_retornogetTurmasDoDia
                                 }
                                ); 
  
  }
  
  /**
   * Preenche a lista de turmas
   */
  function js_retornogetTurmasDoDia(oResponse) {
  
    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
    var aDataPartes = oRetorno.datadia.split("/");
    oTxtData.setData(aDataPartes[0], aDataPartes[1], aDataPartes[2]);
    oGridTurmas.clearAll(true);
    oRetorno.linhas.each(function(oTurma, iSeq) {
      
      var lLinhaBloqueada = oTurma.chamadafechada?false:true; 
      var aLinha = new Array();
      aLinha[0]  = oTurma.ed57_c_descr.urlDecode();
      aLinha[1]  = oTurma.ed232_c_descr.urlDecode();
      aLinha[2]  = oTurma.professor.urlDecode();
      aLinha[3]  = oTurma.chamadafechada?" Sim ":" Não ";
      aLinha[4]  = oTurma.comLeiuturaFalta;
      aLinha[5]  = oTurma.semLeiuturaPresente;
      aLinha[6]  = oTurma.ed57_i_codigo;
      oGridTurmas.addRow(aLinha, false, lLinhaBloqueada);
    });
    oGridTurmas.renderRows();
  
  }
  
  
  $('btnFiltrar').observe("click", function() {
  
     if (oTxtData.getValue() != "") {
       js_getTurmasDoDia();       
     }
  });
  
  $('btnFiltrar').observe("click", function() {
  
     if (oTxtData.getValue() != "") {
       js_getTurmasDoDia();       
     }
  });
  
  $('btnProximoDia').observe("click", function (){
    
    var aDataPartes = oTxtData.getValue().split("/");
    var oNovaData   = new Date(aDataPartes[2], new Number(aDataPartes[1]) -1, new Number(aDataPartes[0])+1);
    var sMes = new String(oNovaData.getMonth()+1);
    if (sMes.length < 2) {
      sMes = "0"+sMes;
    }
    oTxtData.setData(oNovaData.getDate(), sMes, oNovaData.getFullYear());
    js_getTurmasDoDia(); 
  });
  
  
  $('btnDiaAnterior').observe("click", function (){
    
    var aDataPartes = oTxtData.getValue().split("/");
    var oNovaData   = new Date(aDataPartes[2], new Number(aDataPartes[1]) -1, new Number(aDataPartes[0])-1);
    var sMes = new String(oNovaData.getMonth()+1);
    if (sMes.length < 2) {
      sMes = "0"+sMes;
    }
    oTxtData.setData(oNovaData.getDate(), sMes, oNovaData.getFullYear());
    js_getTurmasDoDia(); 
  });
  
  /**
   * Realiza a pesquisa dos alunos faltantes nas turmas selecionadas
   */
  js_getAlunosTurmas = function() {
  
    var aTurmasSelecionadas = oGridTurmas.getSelection('object');
    var aListaTurmas        = new Array();
    aTurmasSelecionadas.each(function(oTurma, iSeq) {
       aListaTurmas.push(oTurma.aCells[7].getValue());
    });
     
    var oParam  = new Object();
    oParam.exec    = 'getControleAcessoFrequencia';
    oParam.mostrar = 'aluno'
    oParam.turmas  = aListaTurmas;
    oParam.datadia = oTxtData.getValue();
    js_divCarregando('Aguarde, carregando a lista de Alunos das turmas selecionadas', 'msgBox');
    var oAjax = new Ajax.Request(sUrlRpc, 
                                 {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete: js_retornoGetAlunosTurmas
                                 }
                                ); 
  }
  
  function js_retornoGetAlunosTurmas (oResponse) {
  
    js_removeObj('msgBox');
    var oRetorno = eval("("+oResponse.responseText+")");
    oDataGridAlunos.clearAll(true);
    oRetorno.linhas.each(function(oAluno, iSeq) {
      
      if (new Number(oAluno.semLeiuturaPresente) == 0 && new Number(oAluno.comLeiuturaFalta) == 0) {
       return false;
      }
      var aLinha = new Array();
      aLinha[0]  = oAluno.ed47_i_codigo;
      aLinha[1]  = oAluno.aluno.urlDecode();
      aLinha[2]  = oAluno.ed57_c_descr.urlDecode();
      aLinha[3]  = oAluno.pai.urlDecode().substring(0, 30);
      aLinha[4]  = oAluno.resplegal.urlDecode().substring(0, 30);
      aLinha[5]  = oAluno.celular.urlDecode();
      aLinha[6]  = oAluno.telefoneresidencial.urlDecode();
      aLinha[7]  = new Number(oAluno.semLeiuturaPresente) > 0 ?"Sim":"Não";
      aLinha[8]  = new Number(oAluno.comLeiuturaFalta)    > 0?"Sim":"Não";
      oDataGridAlunos.addRow(aLinha);
    });
    oDataGridAlunos.renderRows();
  }
  
  function js_impressaoAlunos() {
    
    var sUrl  = 'edu02_controleacessofrequencia002.php?iModelo='+oCboModelo.getValue();
    sUrl     += '&iFiltroAluno='+oCboMostrarAlunos.getValue();
    sUrl     += '&data='+oTxtData.getValue();
    
    var aTurmasSelecionadas = oGridTurmas.getSelection('object');
    var aListaTurmas        = new Array();
    aTurmasSelecionadas.each(function(oTurma, iSeq) {
       aListaTurmas.push(oTurma.aCells[7].getValue());
    });
    delete aTurmasSelecionadas;
    delete aListaTurmas;
    sUrl += '&sListaTurmas='+aListaTurmas.implode(","); 
    window.open(sUrl, '', 'location=0');
  }
  </script>