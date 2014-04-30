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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?
    db_app::load("scripts.js, prototype.js, strings.js, datagrid.widget.js");
    db_app::load("estilos.css, grid.style.css");
    ?>
  </head>
  <body>
   <center>
     <div style="width: 80%">
       <fieldset>
         <legend>
           <b>Necessidades Especiais</b>
         </legend>
         <div style="width: 100%" id='ctnDataGridNecessidadeEspeciais'>
         </div>
       </fieldset>
       <input type="button" value='Salvar' id='btnSalvar' onclick="js_salvar()">
     </div>
   </center>
  </body>
</html>

<script>
var sUrlRPC = 'edu4_recursohumano.RPC.php';
var oGet    = js_urlToObject(location.search);
function js_init() {
   
   oDataGridNecessidadesEspeciais              = new DBGrid("gridNecessidadesEspeciais");
   oDataGridNecessidadesEspeciais.nameInstance = 'oDataGridNecessidadesEspeciais';
   oDataGridNecessidadesEspeciais.setCheckbox(0);
   oDataGridNecessidadesEspeciais.setHeight(300);
   oDataGridNecessidadesEspeciais.setCellWidth(new Array("5%", "95%"));
   oDataGridNecessidadesEspeciais.setHeader(new Array("Código", "Necessidade Especial"));
   
   oDataGridNecessidadesEspeciais.show($('ctnDataGridNecessidadeEspeciais'));
   js_getNecessidades();
}


function js_getNecessidades() {
  
  var oParametro            = new Object();
  oParametro.exec           = "getNecessidadesEspeciais";
  oParametro.iRecursoHumano = oGet.iRecursoHumano
  js_divCarregando('Aguarde, carregando dados...', 'msgBox'); 
  var oAjax                 =  new Ajax.Request(sUrlRPC, 
                                                {
                                                 method: 'post',
                                                 parameters: 'json='+Object.toJSON(oParametro),
                                                 onComplete: js_carregarDados 
                                                });

}
function js_carregarDados(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  oDataGridNecessidadesEspeciais.clearAll(true);
  oRetorno.aNecessidades.each(function(oNecessidade, iSeq) {
     
     var lSelecionado = oNecessidade.possui == 'f'?false:true;
     var aLinha       = new Array(oNecessidade.codigo,oNecessidade.necessidade.urlDecode());
     oDataGridNecessidadesEspeciais.addRow(aLinha, false, false, lSelecionado);
     
  });
  oDataGridNecessidadesEspeciais.renderRows();
}

function js_salvar() {

  var aSelecionados = oDataGridNecessidadesEspeciais.getSelection("object");
  var aNecessidadesEspeciais = new Array();
  aSelecionados.each(function(oNecessidade, iSeq) {
    aNecessidadesEspeciais.push(oNecessidade.aCells[0].getValue());  
  });
  var oParametro                    = new Object();
  oParametro.exec                   = "salvarNecessidadesEspeciais";
  oParametro.iRecursoHumano         = oGet.iRecursoHumano
  oParametro.aNecessidadesEspeciais = aNecessidadesEspeciais;
  js_divCarregando('Aguarde, salvando dados...', 'msgBox'); 
  var oAjax                 =  new Ajax.Request(sUrlRPC, 
                                                {
                                                 method: 'post',
                                                 parameters: 'json='+Object.toJSON(oParametro),
                                                 onComplete: js_retornoSalvar
                                                });
}

function js_retornoSalvar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.status == 1) {
     alert('Alterações realizadas com sucesso');
  } else {
    alert(oRetorno.message.UrlDecode());
  }
}
js_init();
</script>