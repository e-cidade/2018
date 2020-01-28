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
      <form name='form1' method="post">
      <div style="display: table; width: 50%">
        <fieldset>
          <legend><b>Geração de Arquivo Para Cartão de Indetificação</b></legend>
          <div id='ctnDataGridEscolas'>
          </div>
        </fieldset>
      </div>
      <input type='button' value="Gerar Arquivo" id='btnGerarArquivo'>
      </form>
    </center>      
  </body>
</html>  
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), 
        db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
<script>
var sUrlRPC = 'edu04_gerararquivocartaoaluno.RPC.php';
function js_init() {

  oDataGridEscolas              = new DBGrid('oGridEscolas');
  oDataGridEscolas.nameInstance = 'oDataGridEscolas';
  oDataGridEscolas.setCheckbox(0);
  oDataGridEscolas.setHeight(200);
  oDataGridEscolas.setCellWidth(new Array("10%", "95%"));
  oDataGridEscolas.setHeader(new Array("Código", "Escola"));
  oDataGridEscolas.show($('ctnDataGridEscolas'));
  js_pesquisaEscolas();
};
js_init();
function js_pesquisaEscolas() {
  
  js_divCarregando('Aguarde, Carregando Escolas', 'msgBox');
  var oParam  = new Object();
  oParam.exec = 'getEscolas'; 
  var oAjax   = new Ajax.Request(sUrlRPC, 
                                 {method: 'post',
                                  parameters:'json='+Object.toJSON(oParam),
                                  onComplete: js_retornoPesquisaEscolas
                                 }); 
}

function js_retornoPesquisaEscolas(oResponse) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  oDataGridEscolas.clearAll(true);
  oRetorno.aEscolas.each(function(oEscola, iSeq) {
  
     var aLinha = new Array(oEscola.escola, oEscola.nome.urlDecode());
     oDataGridEscolas.addRow(aLinha);
  });
  oDataGridEscolas.renderRows();
}

$('btnGerarArquivo').observe("click", function () {

  var aListaEscolas = oDataGridEscolas.getSelection("object");
  if (aListaEscolas.length == 0) {
  
    alert('Nenhuma escola Selecionada');
    return false;
  }
  aListaEscola = new Array();
  aListaEscolas.each(function(oEscola, iSeq) {
    aListaEscola.push(oEscola.aCells[0].getValue());
  });
  js_divCarregando('Aguarde, gerando Escolas', 'msgBox');
  
  var oParam      = new Object();
  oParam.exec     = 'exportarAlunosArquivo';
  oParam.aEscolas = aListaEscola; 
  var oAjax   = new Ajax.Request(sUrlRPC, 
                                 {method: 'post',
                                  parameters:'json='+Object.toJSON(oParam),
                                  onComplete: js_retornoGerarArquivo
                                 });

});

function js_retornoGerarArquivo(oAjax) {
  
  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 2) {
  
    alert(oRetorno.message.urlDecode());
  } else {
     
    alert('Arquivo Gerado com sucesso');   
    var sListagemArquivos = oRetorno.arquivo.urlDecode()+"#"+oRetorno.arquivo;
    js_montarlista(sListagemArquivos, 'form1');
  }
}
</script>