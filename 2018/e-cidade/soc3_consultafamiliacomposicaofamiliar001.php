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
    <legend><b>Composição Familiar</b></legend>
     <div id='gridContainer'> </div>
  </fieldset>
</center>
</body>
</html>
<script type="text/javascript">
var oUrl                   = js_urlToObject();
var url                    = 'soc3_consultafamilia.RPC.php';

var oGridFamiliares          = new DBGrid('gridComposicaoFamiliar');
oGridFamiliares.nameInstance = "oGridFamiliares"; 
aHeaders                     = new Array("NIS",
                                         "Nome",
                                         "Grau de Parentesco",
                                         "CodigoCidadao" 
                                        );
oGridFamiliares.setCellWidth(new Array('20%', '60%', '20%'));
oGridFamiliares.setCellAlign(new Array('center', 'left', 'left'));
oGridFamiliares.setHeader(aHeaders);
oGridFamiliares.setHeight(150);
oGridFamiliares.aHeaders[3].lDisplayed = false;
oGridFamiliares.show($('gridContainer'));
oGridFamiliares.clearAll(true);

js_carregaDados();

function js_carregaDados() { 

  var oObject         = new Object();
  oObject.exec        = "buscaComposicaoFamiliar";
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
    oGridFamiliares.setStatus(oRetorno.message.urlDecode());
  } else {

		oRetorno.dados.each(function (oDado, iInd) {

		  var sLinkCidadao = "<a href='#' onclick='js_consultarCidadao("+oDado.iCodigoCidadao+")'>"+oDado.sNome.urlDecode()+"</a>";
      var aLinha  = new Array();
      aLinha[0]   = oDado.iNis;
      aLinha[1]   = sLinkCidadao;
      aLinha[2]   = oDado.sGrauParentesco.urlDecode();
      aLinha[3]   = oDado.iCodigoCidadao;
      oGridFamiliares.addRow(aLinha);
      
    });
  }
  oGridFamiliares.renderRows();
}

/**
 * Abre a consulta do Cidadao
 */
function js_consultarCidadao(iCodigoCidadao){

    
  var sUrlPesquisa = 'soc3_consultacidadao003.php?codigoCidadao=' + iCodigoCidadao;
  js_OpenJanelaIframe('top.corpo',
                      'db_iframe_consulta_cidadao',
                      sUrlPesquisa,
                      'Consulta Cidadão',
                      true);
}
</script>