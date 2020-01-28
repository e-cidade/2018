<?php
/*
 *     E-cidade Software Público para Gestão Municipal                
 *  Copyright (C) 2014  DBseller Serviços de Informática             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa é software livre; você pode redistribuí-lo e/ou     
 *  modificá-lo sob os termos da Licença Pública Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versão 2 da      
 *  Licença como (a seu critério) qualquer versão mais nova.          
 *                                                                    
 *  Este programa e distribuído na expectativa de ser útil, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implícita de              
 *  COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM           
 *  PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Você deve ter recebido uma cópia da Licença Pública Geral GNU     
 *  junto com este programa; se não, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Cópia da licença no diretório licenca/licenca_en.txt 
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
</head>
<body style='margin-top: 25px' bgcolor="#cccccc">
<center>
  <div style="display:table;margin-top: 25px; ">
    <form name="form1" id='frmDiarioClasse' method="post">
      <fieldset>
        <legend style="font-weight: bold">Diário de Classe - Turmas de AC e AEE</legend>
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
        </table>
      </fieldset>
      <center>
        <input name="btnImprimir" id="btnImprimir" type="button" value="Imprimir">
      </center>
    </form>
  </div>
</center>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

sUrlRpc      = "edu_educacaobase.RPC.php";

oCboEscola   = new DBComboBox("cboEscola", "oCboEscola",null, "100%");
oCboEscola.addItem("", "Selecione");
oCboEscola.addEvent("onChange", "js_pesquisarCalendarios()");
oCboEscola.show($('ctnCboEscola'));

oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "100%");
oCboCalendario.addItem("", "Selecione");
oCboCalendario.addEvent("onChange", "js_pesquisarTurmas()");
oCboCalendario.show($('ctnCboCalendario'));

oCboTurma = new DBComboBox("cboTurma", "oCboTurma", null, "100%");
oCboTurma.addItem("", "Selecione");
oCboTurma.show($('ctnCboTurma'));

oCboModelo = new DBComboBox("cboModelo", "oCboModelo", null, "100%");
oCboModelo.addItem("1", "Modelo 1");
oCboModelo.show($('ctnCboModelo'));

function js_init() {

  var oParametros          = new Object();
  oParametros.exec         = 'pesquisaEscola';
  js_divCarregando('Aguarde, pesquisando Escolas...<br>Esse procedimento pode levar algum tempo.', 'msgBox')
  var oAjax = new Ajax.Request(sUrlRpc ,
                                 {
                                   method:'post',
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
  oRetorno.dados.each(function(oEscola, iSeq) {
     oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
  });

  oCboEscola.setValue(oRetorno.iEscolaAtual);
  js_pesquisarCalendarios();
}

/**
 * Buscamos os calendario da escola
 */
function js_pesquisarCalendarios() {

  oCboCalendario.clearItens();
  oCboTurma.clearItens();
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
  oCboCalendario.addItem("", "Selecione");
  oRetorno.dados.each(function(oCalendario, iSeq) {
    oCboCalendario.addItem(oCalendario.ed52_i_codigo, oCalendario.ed52_c_descr.urlDecode());
  });

  if (oRetorno.dados.length == 1) {

    oCboCalendario.setValue(oRetorno.aResult[0].ed52_i_codigo);
    js_pesquisarTurmas();
  }
}

/**
 * Buscamos as Turmas vinculadas ao Calendario
 */
function js_pesquisarTurmas() {

  oCboTurma.clearItens();
  oCboTurma.addItem("", "Selecione");

  if (oCboCalendario.getValue() == "") {
    return false;
  }

  js_divCarregando('Aguarde, pesquisando turmas', 'msgBox');

  var oParametros             = new Object();
      oParametros.exec        = "getTurmasEspecialEComplementar";
      oParametros.iCalendario = oCboCalendario.getValue();
      oParametros.iEscola     = oCboEscola.getValue();

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
      oCboTurma.addItem(oTurma.ed268_i_codigo, oTurma.ed268_c_descr.urlDecode());
    });
    if (oRetorno.aTurmas.length == 1) {
      oCboTurma.setValue(oRetorno.aTurmas[0].ed268_i_codigo);
    }
}

js_init();


/**
 * Chama o programa para gerar o relatorio
 */
$('btnImprimir').observe('click', function() {

  if (oCboTurma.getValue() == 0 || oCboTurma.getValue() == "") {

    alert('Selecione uma turma.');
    return false;
  }


  var sUrl         = "edu2_atividadecomplementarespecial002.php";
  var sParametros  = "?iCalendario="+oCboCalendario.getValue();
      sParametros += "&iEscola="+oCboEscola.getValue();
      sParametros += "&iTurma="+oCboTurma.getValue();
      sParametros += "&iModelo="+oCboModelo.getValue();
      sParametros += "&iNumeroColunas="+$F('numeroColunas');

  jan = window.open(sUrl+sParametros, '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
});
</script>