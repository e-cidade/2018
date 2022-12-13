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
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
  <fieldset>
    <legend><b>Benefícios do Cidadão</b></legend>
     <div id='gridContainer'></div>
  </fieldset>
</body>
<script type="text/javascript">
var oUrl                    = js_urlToObject();
var url                     = 'soc3_consultacidadao.RPC.php';
var oGridBeneficio          = new DBGrid('gridBeneficio');
oGridBeneficio.nameInstance = "oGridBeneficio"; 
aHeaders                    = new Array("Benefício", "Data Concessão", "Situação", "Data Situação");
oGridBeneficio.setCellWidth(new Array('25%', '25%', '25%', '25'));
oGridBeneficio.setHeader(aHeaders);
oGridBeneficio.setHeight(150);
oGridBeneficio.show($('gridContainer'));
oGridBeneficio.clearAll(true);

js_carregaDados();
function js_carregaDados() { 

  var oObject         = new Object();
  oObject.exec        = "buscaBeneficio";
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
    oGridBeneficio.setStatus(oRetorno.mesage.urlDecode());
  } else {
    
    oRetorno.dados.each(function (oDado, iInd) {
      
      var aLinha  = new Array();
      aLinha[0]   = oDado.sBeneficio;
      aLinha[1]   = oDado.dtConcessao;
      aLinha[2]   = oDado.sSituacao;
      aLinha[3]   = oDado.dtSituacao;
      
      oGridBeneficio.addRow(aLinha);
    });
  }
  oGridBeneficio.renderRows();
}
</script>