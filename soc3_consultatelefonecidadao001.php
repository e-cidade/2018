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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("dbforms/verticalTab.widget.php");
require_once ("model/social/Cidadao.model.php");
require_once ("model/social/CidadaoTelefone.model.php");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, prototype.js, strings.js, dates.js, datagrid.widget.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
</head>
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
  <fieldset>
    <legend><b>Telefone(s) do Cidadão</b></legend>
    
     <div id='gridContainer'>
     </div>
     
  </fieldset>
  <div id='ajudaItem' style='position:absolute;border:1px solid #FFDD00; display:none; text-indent: 15px;
                             background-color: #FFFFCC;width: 70%; '>
</body>
<script type="text/javascript">

var oUrl                   = js_urlToObject();
var url                    = 'soc3_consultacidadao.RPC.php';
var oGridTelefone          = new DBGrid('gridTelefone');
oGridTelefone.nameInstance = "oGridTelefone"; 
aHeaders                   = new Array("DDD",
                                       "Telefone",
                                       "Ramal", 
                                       "Tipo",
                                       "Principal",
                                       "Observação");
oGridTelefone.setCellWidth(new Array('10%', '30%', '10%', '20%', '10%', '20%'));
//oGridTelefone.setCellAlign(new Array(''));
oGridTelefone.setHeader(aHeaders);
oGridTelefone.setHeight(150);
oGridTelefone.show($('gridContainer'));
oGridTelefone.clearAll(true);

js_carregaDados();
function js_carregaDados() { 

  var oObject         = new Object();
  oObject.exec        = "buscaTelefone";
  oObject.iCidadao    = oUrl.cidadao;
  js_divCarregando('Buscando ...','msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject), 
                                         onComplete:js_retornoCarregaDados
                                        }
                                   );
}

function js_retornoCarregaDados(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.status == 2) {
    oGridTelefone.setStatus(oRetorno.mesage.urlDecode());
  } else {
    
    //oGridTelefone.clearAll(true);
    oRetorno.dados.each(function (oDado, iInd) {
      
      var aLinha  = new Array();
      aLinha[0]   = oDado.ddd;
      aLinha[1]   = oDado.numero;
      aLinha[2]   = oDado.ramal
      aLinha[3]   = oDado.tipo.urlDecode();
      aLinha[4]   = oDado.principal.urlDecode();
      aLinha[5]   = oDado.observacao.urlDecode();

      oGridTelefone.addRow(aLinha);
      oGridTelefone.aRows[iInd].aCells[5].sEvents += " onmouseover='js_displayAjuda(\""+aLinha[5]+"\", true)'";
      oGridTelefone.aRows[iInd].aCells[5].sEvents += " onmouseout='js_displayAjuda(\"\", false)'";
      
    });
  }
  oGridTelefone.renderRows();
}

function js_displayAjuda(sTexto, lShow) {

  if (lShow) {
    
    el =  $('gridContainer'); 
    var x = 0;
    var y = el.offsetHeight;
    x += el.offsetLeft;
    y += el.offsetTop;
    $('ajudaItem').innerHTML     = sTexto;
    $('ajudaItem').style.display = '';
    $('ajudaItem').style.top     = $('gridContainer').scrollTop + 5;
    $('ajudaItem').style.left    = x;
   
  } else {
   $('ajudaItem').style.display = 'none';
  }
}
</script>