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
<center>
  <fieldset>
    <legend><b>Lista de Beneficios</b></legend>
     <div id='gridContainer'></div>
  </fieldset>
</center>
</body>
</html>
<script type="text/javascript">
var oUrl                     = js_urlToObject();
var url                      = 'soc3_consultafamilia.RPC.php';

var oGridBeneficios          = new DBGrid('gridBeneficios');
oGridBeneficios.nameInstance = "oGridBeneficios"; 
aHeaders                     = new Array("Benefício",
                                         "Situação",
                                         "Quantidade" 
                                        );
oGridBeneficios.setCellWidth(new Array('75%', '15%', '10%'));
oGridBeneficios.setCellAlign(new Array('left', 'left', 'center'));
oGridBeneficios.setHeader(aHeaders);
oGridBeneficios.setHeight(150);
oGridBeneficios.show($('gridContainer'));
oGridBeneficios.clearAll(true);

js_carregaDados();

function js_carregaDados() { 

  var oObject         = new Object();
  oObject.exec        = "buscaBeneficiosFamilia";
  oObject.iFamilia    = oUrl.familia;
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
    oGridBeneficios.setStatus(oRetorno.message.urlDecode());
  } else {

		oRetorno.dados.each(function (oDado, iInd) {

      var aLinha  = new Array();
      aLinha[0]   = oDado.beneficio.urlDecode();  
      aLinha[1]   = oDado.situacao.urlDecode();
      aLinha[2]   = oDado.quantidade ;
      oGridBeneficios.addRow(aLinha);
      
    });
  }
  oGridBeneficios.renderRows();
}
</script>