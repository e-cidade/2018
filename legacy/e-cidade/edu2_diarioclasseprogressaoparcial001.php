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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_libcontabilidade.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <?
    db_app::load("scripts.js, prototype.js, strings.js, arrays.js, windowAux.widget.js, datagrid.widget.js,
                  dbmessageBoard.widget.js, dbcomboBox.widget.js, dbtextField.widget.js, webseller.js,
                  DBVisualizadorImpressaoTexto.js, DBToogle.widget.js");

    db_app::load("estilos.css, grid.style.css, dbVisualizadorImpressaoTexto.style.css");
  ?>
<style type="text/css">
  div#ctnForm table tr td:FIRST-CHILD{width: 150px;}

  .prePagina {font-family: monospace;}
  .fieldhr {
    border : none;
    border-top : groove #FFF 2px;
  }
  button.btnMove {

    border:1px solid #999999;
    width: 40px
  }

</style>
</head>
<body bgcolor="#cccccc">
<center>
  <div style="display:table;margin-top: 25px; ">
    <form name="form1" id='frmDiarioClasse' method="post">
      <fieldset>
        <legend><b>Diário de Classe - Turmas com Alunos em Progressão Parcial / Dependência</b></legend>
        <div style='display:table;' id='ctnForm'>
          <fieldset class='fieldhr'>
            <legend><b>Filtros</b></legend>
            <table border='0' width="100%">
              <tr>
               <td nowrap title="" >
                  <b>Calendário:</b>
                </td>
                <td nowrap id="ctnCboCalendario">
               </td>
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Turma:</b>
                </td>
                <td nowrap id="ctnCboTurma">
                </td>
              </tr>
              <tr>
                <td nowrap title="" >
                  <b>Modelo:</b>
                </td>
                <td nowrap id="ctnCboModelo">
                </td>
              </tr>
              <tr>
                <td nowrap="nowrap">
                  <b>Quantidade de Colunas (Presenças):</b>
                </td>
                <td nowrap="nowrap" id='ctnNumeroColunas'>
                  <?php
                    $aColunas = array();
                    for ($i = 30; $i <= 70; $i++) {
                      $aColunas[$i] = $i;
                    }
                    db_select('numeroColunas', $aColunas, true, 1, "style='width:100%;'");
                  ?>
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
          <fieldset id='outrasConfiguracaes' class='fieldhr'>
            <legend><b>Outros Dados do Relatório</b></legend>
            <table style="font-weight: bold;">
              <tr>
                <td>
                  <input type="checkbox" id="sexo" />
                  <label for="sexo">Sexo</label>
                </td>
                <td>
                  <input type="checkbox" id="idade" />
                  <label for='idade'>Idade</label>
                </td>
              </tr>
              <tr>
                <td>
                  <input type="checkbox" id="codigoAluno" />
                  <label for='codigoAluno'>Código Aluno</label>
                </td>
                <td>
                  <input type="checkbox" id="nascimento" />
                  <label for="nascimento">Nascimento</label>
                </td>
              </tr>
            </table>
          </fieldset>
          <fieldset class='fieldhr' >
            <legend><b>Disciplinas</b></legend>
            <table>
              <tr>
                <td id='ctnDisciplinas'></td>
                <td>
                 <button type='button' id='btnMoveOneRightToLeft' class='btnMove' >&gt;</button><br>
                 <button type='button' id='btnMoveAllRightToLeft' class='btnMove' >&gt;&gt;</button><br>
                 <button type='button' id='btnMoveOneLeftToRight' class='btnMove' >&lt;</button><br>
                 <button type='button' id='btnMoveAllLeftToRight' class='btnMove' >&lt;&lt;</button>
                </td>
                <td id='ctnDisciplinasSelecionadas'></td>
              </tr>
            </table>
          </fieldset>
        </div>
      </fieldset>
      <center>
        <input type="button" id='btnImprimir' value='Imprimir' />
      </center>
    </form>
  </div>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script type="text/javascript">
var sUrlRpc = "edu_educacaobase.RPC.php";

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
oCboModelo.addItem("1", "Modelo 1");
oCboModelo.show($('ctnCboModelo'));

oCboDisciplinas  = new DBComboBox("cboDisciplinas",
                                  "oCboDisciplinas",
                                  null,
                                  "250px",
                                  10
                                 );
oCboDisciplinas.setMultiple(true);
oCboDisciplinas.addEvent("onDblClick", "moveSelected(oCboDisciplinas, oCboDisciplinasSelecionadas)");
oCboDisciplinas.show($('ctnDisciplinas'));

oCboDisciplinasSelecionadas  = new DBComboBox("cboDisciplinasSelecionadas",
                                              "oCboDisciplinasSelecionadas",
                                              null,
                                              "250px",
                                               10
                                             );
oCboDisciplinasSelecionadas.setMultiple(true);
oCboDisciplinasSelecionadas.addEvent("onDblClick", "moveSelected(oCboDisciplinasSelecionadas, oCboDisciplinas)");
oCboDisciplinasSelecionadas.show($('ctnDisciplinasSelecionadas'));

/**
 * Buscamos os calendario da escola
 */
function js_pesquisarCalendarios() {

  oCboCalendario.clearItens();
  oCboTurma.clearItens();
  oCboPeriodo.clearItens();
  oCboDisciplinas.clearItens();
  oCboDisciplinasSelecionadas.clearItens();
  oCboCalendario.addItem("", "Selecione");
  oCboTurma.addItem("", "Selecione");

  js_divCarregando('Aguarde, pesquisando calendarios', 'msgBox');
  var oParametros               = new Object();
      oParametros.exec          = "pesquisaCalendario";

  var oAjax = new Ajax.Request(sUrlRpc,
                               {
                                 method:'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoPesquisarCalendario
                               });
}

function js_retornoPesquisarCalendario(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  oRetorno.dados.each(function(oCalendario, iSeq) {
    oCboCalendario.addItem(oCalendario.ed52_i_codigo, oCalendario.ed52_c_descr.urlDecode());
  });

  if (oRetorno.aResult.length == 1) {

    oCboCalendario.setValue(oRetorno.aResult[0].ed52_i_codigo);
    js_pesquisarTurmas();
  }
}

/**
 * Buscamos as Turmas vinculadas ao Calendario
 */
function js_pesquisarTurmas() {

  oCboTurma.clearItens();
  oCboPeriodo.clearItens();
  oCboDisciplinas.clearItens();
  oCboDisciplinasSelecionadas.clearItens();
  oCboTurma.addItem("", "Selecione");

  if (oCboCalendario.getValue() == "") {
    return false;
  }

  js_divCarregando('Aguarde, pesquisando turmas', 'msgBox');

  var oParametros             = new Object();
      oParametros.exec        = "getTurmasProgressaoParcial";
      oParametros.iCalendario = oCboCalendario.getValue();

  var oAjax = new Ajax.Request(sUrlRpc ,
                             {
                               method:'post',
                               parameters: 'json='+Object.toJSON(oParametros),
                               onComplete: js_retornoGetTurmas
                             });
}

function js_retornoGetTurmas(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
    oRetorno.aTurmas.each(function(oTurma, iSeq) {
      oCboTurma.addItem(oTurma.ed57_i_codigo, oTurma.ed57_c_descr.urlDecode());
    });
    if (oRetorno.aTurmas.length == 1) {
      oCboTurma.setValue(oRetorno.aTurmas[0].codigo_turma);
    }
}

/**
 * Buscamos as Disciplinas das Turmas
 */
function js_pesquisarDisciplinas () {

  oCboDisciplinasSelecionadas.clearItens();
  oCboDisciplinas.clearItens();
  oCboPeriodo.clearItens();
  if (oCboTurma.getValue() == "") {
    return false;
  }
  js_divCarregando('Aguarde, pesquisando disciplinas', 'msgBox');
  var oParametros              = new Object();
      oParametros.exec         = "getDisciplinaTurma";
      oParametros.iCodigoTurma = oCboTurma.getValue();
  var oAjax = new Ajax.Request(sUrlRpc,
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
    oCboDisciplinas.addItem(oDisciplina.iRegencia, oDisciplina.sDescricaoDisciplina.urlDecode());
  });

  js_pesquisarPeriodos();
}

/**
 * Buscamos os periodos do calendario
 */
function js_pesquisarPeriodos () {

  oCboDisciplinasSelecionadas.clearItens();
  js_divCarregando('Aguarde, pesquisando Periodos', 'msgBox');

  var oParametros        = new Object();
      oParametros.exec   = "getPeriodosDeAvaliacaoTurma";
      oParametros.iTurma = oCboTurma.getValue();
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
    oCboPeriodo.addItem(oPeriodo.iCodigo, oPeriodo.sDescricao.urlDecode());
  });

}

/**
 * Funcoes para movimentar as disciplinas de um selectmultiple para o outro
 */
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

/**
 * Configuracoes padrao do relatorio
 */
var oConfiguracoes       = new DBToogle('outrasConfiguracaes', false);
$('sexo').checked        = true;
$('idade').checked       = true;
$('codigoAluno').checked = true;
$('nascimento').checked  = true;

js_pesquisarCalendarios();

/**
 * Chama o programa para gerar o relatorio
 */
$('btnImprimir').observe('click', function() {

  var aDisciplinasSelecionadas = oCboDisciplinasSelecionadas.aItens;

  if (oCboTurma.getValue() == 0 || oCboTurma.getValue() == "") {

    alert('Selecione uma turma.');
    return false;
  }

  if (aDisciplinasSelecionadas == null || aDisciplinasSelecionadas.length == 0) {

    alert('Nenhuma Disciplina selecionada.');
    return false;
  }

  var aRegencias = new Array();
  aDisciplinasSelecionadas.each(function(oDisciplina, id) {
    aRegencias.push(oDisciplina.id);
  });


  var sUrl         = "edu2_diarioclasseprogressaoparcial002.php";
  var sParametros  = "?iCalendario="+oCboCalendario.getValue();
      sParametros += "&iTurma="+oCboTurma.getValue();
      sParametros += "&iPeriodo="+oCboPeriodo.getValue();
      sParametros += "&sRegencias="+aRegencias;
      sParametros += "&iModelo="+oCboModelo.getValue();
      sParametros += "&iNumeroColunas="+$F('numeroColunas');
      sParametros += "&sexo="+$('sexo').checked;
      sParametros += "&idade="+$('idade').checked;
      sParametros += "&codigoAluno="+$('codigoAluno').checked;
      sParametros += "&nascimento="+$('nascimento').checked;

  jan = window.open(sUrl+sParametros, '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
});
</script>