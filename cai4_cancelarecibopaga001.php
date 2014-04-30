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

require_once('libs/db_conn.php');
require_once('libs/db_stdlib.php');
require_once('libs/db_conecta.php');
require_once('dbforms/db_funcoes.php');
require_once('libs/db_app.utils.php');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js"); 
  db_app::load("strings.js");
  db_app::load("prototype.js");
  db_app::load("estilos.css");
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center" style="" id="base">
  <tr> 
    <td align="center" valign="top" bgcolor="#CCCCCC"> 
    <center>
      <fieldset>
        <legend><b>Motivo do Cancelamento: </b></legend>
        <table>
         <tr>
          <td>
            <?db_textarea("motivo",5,80,null,true,"text",1,"","","",450)?>
          </td>
         </tr>
        </table>
      </fieldset>
      <input type="button" value="Confirmar" onclick="js_confirma()">
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<script>
 function js_confirma() {

	  if ($F("motivo") == "") {
		  alert("Informe o motivo do cancelamento da emissão do Recibo");
		  return false; 
	  }

	  var oParam         = new Object();
	      oParam.exec    = 'CancelaReciboPaga';
	      oParam.numnov  = <?=$numnov?>;
	      oParam.motivo  = tagString($F("motivo"));	      
    var oAjax  = new Ajax.Request("cai3_emitecarne.RPC.php",
	                                {method: 'post',
	                                 parameters: 'json='+Object.toJSON(oParam), 
	                                 onComplete: function(oAjax) { 
	                                   var oRetorno = eval("("+oAjax.responseText.urlDecode()+")");
	                                   alert(oRetorno.message);
	                                   parent.js_fechaJanelaCancelaRecibo();
                                   }
	                                } );
 }   
</script>