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
<?MsgAviso(db_getsession("DB_coddepto"),"escola");?>
<center>
  <div style="display:table;margin-top: 25px; ">
    <form name="form1" id='frmDiarioClasse' method="post">
      <fieldset>
        <legend style="font-weight: bold">Alunos com Progressão Parcial</legend>
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
              <b>Ano : </b>
            </td>
            <td nowrap id="ctnAno">
           </td>
          </tr>
        </table>
      </fieldset>
      <center>
        <input name="btnImprimir" id="btnImprimir" disabled type="button" value="Imprimir">
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
oCboEscola.addEvent("onChange", "js_pesquisarAnosDisponiveis()");
oCboEscola.show($('ctnCboEscola'));

oCboAno      = new DBComboBox("cboAno", "oCboAno", null, "100%");
oCboAno.addItem("", "Selecione");
oCboAno.show($("ctnAno"));


function js_init() {

  var oParametros                           = new Object();
  oParametros.exec                          = 'pesquisaEscolaComProgressaoParcial';
  oParametros.lEscolasComAlunosEmProgressao = 1;
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

  if (!oRetorno.lPossuiProgressao) {
    
    var sMensagemSemProgressao = 'edu2_alunoscomprogressao001.nenhuma_escola_com_progressao_configurada';
    
    if (oRetorno.iEscolaAtual != '') {
      sMensagemSemProgressao = 'edu2_alunoscomprogressao001.escola_sem_progressao_configurada';
    }
    oCboEscola.addItem("", _M("educacao.escola."+sMensagemSemProgressao));
    return false;
  }
  if (oRetorno.iEscolaAtual == "") {
    oCboEscola.addItem("0", "Todos");
  }

  oRetorno.dados.each(function(oEscola, iSeq) {
     oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
  });

  if (oRetorno.iEscolaAtual == "") {
    oCboEscola.setValue(0);
  } else {
    oCboEscola.setValue(oRetorno.iEscolaAtual);
  }
  js_pesquisarAnosDisponiveis();
}

function js_pesquisarAnosDisponiveis() {

  oCboAno.clearItens();
	oCboAno.addItem("", "Selecione");

	var oParametros      = new Object();
  oParametros.exec     = 'buscaAnosDeTurmasDeProgressaoParcial';
  oParametros.iEscola  = oCboEscola.getValue();
  js_divCarregando('Aguarde, pesquisando Anos...', 'msgBox')
  var oAjax = new Ajax.Request(sUrlRpc ,
                                 {
                                   method:'post',
                                   parameters: 'json='+Object.toJSON(oParametros),
                                   onComplete: js_retornoAnosDisponiveis
                                 }
                              );
}

function js_retornoAnosDisponiveis (oAjax) {

	js_removeObj('msgBox');
	var oRetorno = eval("("+oAjax.responseText+")");

	oCboAno.clearItens();
	oCboAno.addItem("", "Selecione");

	if (oRetorno.status == 2) {

		alert(oRetorno.message.urlDecode());
		return false;
	}

	oRetorno.aAnos.each(function(oAno, iSeq) {
		oCboAno.addItem(iSeq, oAno.ed114_ano);
	});
	
	if (oRetorno.aAnos.length > 0) {
	  $('btnImprimir').disabled = false;
	}
}

js_init();


/**
 * Chama o programa para gerar o relatorio
 */
$('btnImprimir').observe('click', function() {

  if (oCboAno.getValue() == "") {

    alert(_M('educacao.escola.edu2_alunoscomprogressao001.sem_ano_selecionado'));
    return false;
  }

  var sUrl         = "edu2_alunoscomprogressao002.php";
  var sParametros  = "?iAno="+oCboAno.getLabel();
      sParametros += "&iEscola="+oCboEscola.getValue();

  jan = window.open(sUrl+sParametros, '',
                    'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
});
</script>