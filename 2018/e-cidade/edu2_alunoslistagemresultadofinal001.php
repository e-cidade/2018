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

include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$escola = db_getsession("DB_coddepto");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load("scripts.js,
              prototype.js,
              strings.js,
              arrays.js,
              windowAux.widget.js,

              dbmessageBoard.widget.js,
              dbcomboBox.widget.js,
              dbtextField.widget.js,
              webseller.js
              ");

db_app::load("estilos.css");
?>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td>&nbsp;</td>
 </tr>
</table>
<form name="form1" method="post" action="">
<center>
<br>
<fieldset style="width:700px">
<legend><b>Alunos da Rede Municipal por Resultado Final</b></legend>
<table width="100%" border="0" align="left">
 <tr>
  <td colspan="3">
   <table border="0" align="left">

    <tr>
      <td nowrap title="" >
        <b>Escola : </b>
      </td>
      <td nowrap >
        <div id="ctnCboEscola"></div>
      </td>
    </tr>
    <tr>
     <td nowrap title="" >
        <b>Ano : </b>
      </td>
      <td nowrap id="ctnCboCalendario">
     </td>
    </tr>
    <tr>
      <td nowrap title="" >
        <b>Ensino : </b>
      </td>
      <td nowrap >
        <div id="ctnCboEnsino"></div>
      </td>
    </tr>
   </table>
  </td>
 </tr>

</table>

  <fieldset style="text-align: left; border:none;border-top:2px groove white;font-weight: bold">
    <legend>Etapas:</legend>
    <table style="padding-left: 70px;">
      <tr>
        <td><b>Etapas para Impressão:</b></td>
        <td id='ctnEtapas'>

        </td>
        <td>
         <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999; width: 40px'>&gt;</button><br>
         <button type='button' id='btnMoveAllRightToLeft' style='border:1px solid #999999;width: 40px'>&gt;&gt;</button><br>
         <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
         <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
        </td>
        <td id='ctnEtapasSelecionadas'>
        </td>
      </tr>
    </table>
 </fieldset>

</fieldset>
   <input name="pesquisar" type="button" id="pesquisar" value="Processar" onclick="js_pesquisa();">
</center>
</form>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>

var sUrlRpc        = "edu4_escola.RPC.php";
var aEtapasCompara = new Array();

init = function () {

  oCboEscola   = new DBComboBox("cboEscola", "oCboEscola",null, "100%");
  oCboEscola.addItem("0", "Todos");
  oCboEscola.addEvent("onChange", "js_pesquisarCalendario()");
  oCboEscola.show($('ctnCboEscola'));

  oCboCalendario = new DBComboBox("cboCalendario", "oCboCalendario", null, "100%");
  oCboCalendario.show($('ctnCboCalendario'));

  oCboEnsino = new DBComboBox("cboEnsino", "oCboEnsino", null, "100%");
  oCboEnsino.addItem("0", "Todos");
  oCboEnsino.addEvent("onChange", "js_pesquisarEtapas()");
  oCboEnsino.show($('ctnCboEnsino'));

  oCboEtapas  = new DBComboBox("cboEtapas", "oCboEtapas", null,"150px", 10);
  oCboEtapas.setMultiple(true);
  oCboEtapas.addEvent("onDblClick", "moveSelected(oCboEtapas, oCboEtapasSelecionadas)");
  oCboEtapas.show($('ctnEtapas'));

  oCboEtapasSelecionadas  = new DBComboBox("cboEtapasSelecionadas", "oCboEtapasSelecionadas",null,"150px", 10);
  oCboEtapasSelecionadas.setMultiple(true);
  oCboEtapasSelecionadas.addEvent("onDblClick", "moveSelected(oCboEtapasSelecionadas, oCboEtapas)");
  oCboEtapasSelecionadas.show($('ctnEtapasSelecionadas'));

  var oParametros          = new Object();
  oParametros.exec         = 'getEscola';
  oParametros.filtraModulo = true;

  js_divCarregando('Aguarde, pesquisando Escolas...<br>Esse procedimento pode levar algum tempo.', 'msgBox')
  var oAjax = new Ajax.Request(sUrlRpc ,
                                         {
                                           method:'post',
                                           parameters: 'json='+Object.toJSON(oParametros),
                                           onComplete: js_retornoPreencheEscolas
                                         }
                                      );

}

//Insere as escolas na respectiva combo.
js_retornoPreencheEscolas = function (oAjax) {

 js_removeObj('msgBox');
 var oRetorno = eval("("+oAjax.responseText+")");

   oCboEscola.clearItens();
   oCboEscola.addItem("0", "Todos");
   oRetorno.itens.each(function(oEscola, iSeq) {
      oCboEscola.addItem(oEscola.codigo_escola, oEscola.nome_escola.urlDecode());
   });

   js_pesquisarCalendario();
}

//Busca os cursos
js_pesquisarEnsino = function() {

  oCboEnsino.clearItens();
  oCboEnsino.addItem("0", "Todos");
  oCboEtapas.clearItens();
  oCboEtapasSelecionadas.clearItens();

  js_divCarregando('Aguarde, pesquisando ensino', 'msgBox');
  var oParametros               = new Object();
      oParametros.exec          = "PesquisaCurso";
      oParametros.iCodigoEscola = oCboEscola.getValue();
  var oAjax = new Ajax.Request(sUrlRpc ,
                               {
                                 method:'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoPesquisarEnsino
                               });
};

//Insere os cursos na respectiva combo
function js_retornoPesquisarEnsino(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  oRetorno.aResultCursoEscola.each(function(oEnsino, iSeq) {
    oCboEnsino.addItem(oEnsino.codigo_curso, oEnsino.nome_curso.urlDecode());
  });

  js_pesquisarEtapas();
}

//Busca as etapas
js_pesquisarEtapas = function() {

  oCboEtapas.clearItens();
  oCboEtapasSelecionadas.clearItens();

  js_divCarregando('Aguarde, pesquisando etapa', 'msgBox');
  var oParametros                   = new Object();
      oParametros.exec              = "getEtapas";
      oParametros.iEscola           = oCboEscola.getValue();
      oParametros.iCurso            = oCboEnsino.getValue();
      oParametros.lTurmasEncerradas = true;
  var oAjax = new Ajax.Request(sUrlRpc ,
                               {
                                 method:'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoPesquisarEtapa
                               });
};

//Insere as etapas na respectiva combo.
function js_retornoPesquisarEtapa(oResponse) {

  var aEtapas = new Array();

  js_removeObj('msgBox');
  var oRetorno   = eval("("+oResponse.responseText+")");
  aEtapasCompara = oRetorno.aResultado;

  oRetorno.aResultado.each(function(oEtapa, iSeq) {

    if (!js_search_in_array(aEtapas, oEtapa.ed11_i_codigo)) {

      aEtapas.push(oEtapa.ed11_i_codigo);
      oCboEtapas.addItem(oEtapa.ed11_i_codigo, oEtapa.ed11_c_descr.urlDecode());
    }
  });
}

//Busca os calendários
js_pesquisarCalendario = function() {

  oCboCalendario.clearItens();
  oCboEnsino.clearItens();
  oCboEnsino.addItem("0", "Todos");
  oCboEtapas.clearItens();
  oCboEtapasSelecionadas.clearItens();

  if (oCboEscola.getValue() == "") {
    return false;
  }
  js_divCarregando('Aguarde, pesquisando anos', 'msgBox');
  var oParametros               = new Object();
      oParametros.exec          = "PesquisaAnosCalendario";
      oParametros.escola        = oCboEscola.getValue();
  var oAjax = new Ajax.Request(sUrlRpc ,
                               {
                                 method:'post',
                                 parameters: 'json='+Object.toJSON(oParametros),
                                 onComplete: js_retornoPesquisarCalendario
                               });
};

//Insere os calendários no respectivo combo.
function js_retornoPesquisarCalendario(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");

  oRetorno.aResult.each(function(oCalendario, iSeq) {
   oCboCalendario.addItem(oCalendario.ed52_i_ano, oCalendario.ed52_i_ano);
  });

  js_pesquisarEnsino();
}

$('btnMoveOneRightToLeft').observe("click", function() {
  moveSelected(oCboEtapas, oCboEtapasSelecionadas);
});

$('btnMoveAllRightToLeft').observe("click", function() {
  moveAll(oCboEtapas, oCboEtapasSelecionadas);
});

$('btnMoveOneLeftToRight').observe("click", function() {
  moveSelected(oCboEtapasSelecionadas, oCboEtapas);
});

$('btnMoveAllLeftToRight').observe("click", function() {
  moveAll(oCboEtapasSelecionadas, oCboEtapas);
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
   oComboOrigin.clearItens();
}

init();

function js_pesquisa(){

  var aEtapasImpressao    = new Array();
  var aEtapasSelecionadas = oCboEtapasSelecionadas.aItens;

  aEtapasSelecionadas.each(function(oLinha, iSeq) {

    if (oLinha.id !== "") {
      aEtapasImpressao.push(aEtapasCompara[oLinha.id]);
    }
  });

  if (aEtapasImpressao == null || aEtapasImpressao.length == 0) {

    alert('Nenhuma Etapa selecionada');
    return false;
  }
  var aEtapas                 = new Array();
  aEtapasSelecionadas.each(function(oEtapa, id) {
    aEtapas.push(oEtapa.id);
  });

 var sEtapas = aEtapas.implode(",");
 jan = window.open('edu2_alunoslistagemresultadofinal002.php?iEscola='+document.form1.cboEscola.value+'&iAno='+document.form1.cboCalendario.value+'&iEnsino='+document.form1.cboEnsino.value+'&sEtapas='+sEtapas,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 jan.moveTo(0,0);
}
</script>