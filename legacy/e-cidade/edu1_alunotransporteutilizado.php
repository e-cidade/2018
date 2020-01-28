<?
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

require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_stdlib.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_utils.php");
require_once ("dbforms/db_funcoes.php");

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
                  dbcomboBox.widget.js"
                  );
    
    db_app::load("estilos.css");
    ?>
  </head>
  <body>
   <center>
     <div style="display: table;">
       <fieldset>
         <legend>
           <b>Transportes Utilizados</b>
         </legend>
          <table>
            <tr>
               <td><b>Meios de Transportes:</b></td>
               <td id='ctnTransportes'></td>
               <td>
                 <button type='button' id='btnMoveOneRightToLeft' style='border:1px solid #999999; width: 40px'>&gt;</button><br>
                 <button type='button' id='btnMoveOneLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;</button><br>
                 <button type='button' id='btnMoveAllLeftToRight' style='border:1px solid #999999;width: 40px'>&lt;&lt;</button>
                </td>
                <td id='ctnTransportesSelecionados'></td>
              </tr>
            </table>
         </fieldset>  
     </div>
     <div id='mensagem' style="font-size: 14px; display: none;" >
       <br><b>Aluno(a) informou que não utiliza Transporte Escola Público, não sendo possível selecionar nenhum tipo de transporte.</b><br>
     </div>
     <input type="button" value='Salvar' id='btnSalvar' onclick="js_salvar()">
   </center>
  </body>
</html>

<script>
var sUrlRPC                  = 'edu_dadosaluno.RPC.php';
var oGet                     = js_urlToObject(location.search);
var iTransportesSelecionados = 0;

function js_init() {

    oCboTransporte  = new DBComboBox("cboTransporte", "oCboTransporte", null,"500px", 10);
    oCboTransporte.setMultiple(true);
    oCboTransporte.addEvent("onDblClick", "moveSelected(oCboTransporte, oCboTransporteSelecionados)");
    oCboTransporte.show($('ctnTransportes'));
    
    oCboTransporteSelecionados  = new DBComboBox("cboTransporteSelecionados", "oCboTransporteSelecionados",null,"500px", 10);
    oCboTransporteSelecionados.setMultiple(true);
    oCboTransporteSelecionados.addEvent("onDblClick", "moveSelected(oCboTransporteSelecionados, oCboTransporte)");
    oCboTransporteSelecionados.show($('ctnTransportesSelecionados'));

    if (oGet.iUtilizaTransporte == 0) {

      oCboTransporte.setDisable(true);
      oCboTransporteSelecionados.setDisable(true);
      $('btnSalvar').disabled     = true;
      $('mensagem').style.display = 'inline';
    }
    
    js_pesquisar();
}
 $('btnMoveOneRightToLeft').observe("click", function() {
    moveSelected(oCboTransporte, oCboTransporteSelecionados);
  });
  
  $('btnMoveOneLeftToRight').observe("click", function() {
    moveSelected(oCboTransporteSelecionados, oCboTransporte);
  });
  
  $('btnMoveAllLeftToRight').observe("click", function() {
    moveAll(oCboTransporteSelecionados, oCboTransporte);
  });
  
  function moveSelected(oComboOrigin, oComboDestiny) {

    if (oComboDestiny.sName == 'cboTransporteSelecionados') {
      iTransportesSelecionados += oComboOrigin.getValue().length;
    } else {
      iTransportesSelecionados -= oComboOrigin.getValue().length;
    }
    
    if (js_verificaTransportesSelecionados(oComboOrigin)) {

      if (oComboOrigin.getValue() != null) {
        
        var aItens = oComboOrigin.getValue();

        aItens.each(function(oItem, iSeq) {      
          
          oItem = oComboOrigin.aItens[oItem];
          oComboDestiny.addItem(oItem.id, oItem.descricao);
          oComboOrigin.removeItem(oItem.id);
        });
      }
    }
  }
  
  function moveAll(oComboOrigin, oComboDestiny) {

    iTransportesSelecionados = 0;
     oComboOrigin.aItens.each(function(oItem, iSeq) {

        oComboDestiny.addItem(oItem.id, oItem.descricao);
        oComboOrigin.removeItem(oItem.id);
      });
  }

function js_pesquisar() {

  var oParametro  = new Object();
  
  oParametro.exec         = 'getTransportesAluno';
  oParametro.iCodigoAluno = oGet.iAluno;
  js_divCarregando('Aguarde, carregando os tipos de transporte público', 'msgBox');
  var oAjax = new Ajax.Request (
                                sUrlRPC,
                                {
                                 method     : 'post',
                                 parameters : 'json='+js_objectToJson(oParametro),
                                 onComplete : js_retornaTransporte
                                }
                               );

}

function js_retornaTransporte (oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval("("+oResponse.responseText+")");
  oCboTransporte.clearItens();
  oCboTransporteSelecionados.clearItens();
  oRetorno.aTransportes.each(function(oTransporte, iSeq) {
     
     if (oTransporte.possui == 'f') {
      oCboTransporte.addItem(oTransporte.codigo, oTransporte.descricao.urlDecode());
     } else {
       
      oCboTransporteSelecionados.addItem(oTransporte.codigo, oTransporte.descricao.urlDecode());
      iTransportesSelecionados++;
     }
  });
}

js_salvar = function() {

  var oParametro = new Object();
  
  oParametro.exec         = 'inserirTransporteAluno';
  oParametro.aTransporte  = new Array();
  oParametro.iCodigoAluno = oGet.iAluno;
  oCboTransporteSelecionados.aItens.each(function (oItem, iSeq) {
     
     if (oItem.descricao != "") {
       oParametro.aTransporte.push(oItem.id);
     }
  
  });
  js_divCarregando('Aguarde, salvando os dados', 'msgBox');
  var oAjax = new Ajax.Request (
                                sUrlRPC,
                                {
                                 method     : 'post',
                                 parameters : 'json='+Object.toJSON(oParametro),
                                 onComplete : js_retornaInclusao
                                }
                               );

}
function js_retornaInclusao (oResponse) {

  js_removeObj('msgBox') ;
  var oRetorno = eval("("+oResponse.responseText+")");
  if (oRetorno.status == 1) {
    alert('Transportes salvos com sucesso');
  } else {
    alert(oRetorno.message.urlDecode());
  }

}

function js_verificaTransportesSelecionados(oComboOrigin) {

  var lErro = false;

  if (oComboOrigin.getValue().length > 3) {
    lErro = true;
  } else if (iTransportesSelecionados > 3) {
    lErro = true;
  }
  
  if (lErro) {
    
    alert('É permitido selecionar no máximo 3 tipos de transportes.');
    iTransportesSelecionados -= oComboOrigin.getValue().length;
    return false;
  }
  
  return true;
}
js_init();
</script>