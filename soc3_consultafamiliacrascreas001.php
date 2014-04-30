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
    <legend><b>Vínculos CRAS/CREAS</b></legend>
     <div id='gridContainer'></div>
  </fieldset>
</center>
</body>
</html>
<script type="text/javascript">
var oUrl                      = js_urlToObject();
var sUrlRpc                   = 'soc3_consultafamilia.RPC.php';
var oGridCrasCreas              = new DBGrid('gridCrasCreas');
    oGridCrasCreas.nameInstance = "oGridCrasCreas";

var aHeaders  = new Array("Identificador", "CRAS/CREAS", "Início", "Fim", "Situação");
oGridCrasCreas.setCellWidth(new Array('15%', '55%', '10%', '10%', '10%'));
oGridCrasCreas.setCellAlign(new Array('rightr', 'left', 'center', 'center', 'left'));
oGridCrasCreas.setHeader(aHeaders);
oGridCrasCreas.setHeight(220);
oGridCrasCreas.show($('gridContainer'));

/**
 * Busca o historico de vinculos realizados pela familia a um local de atendimento
 */
function js_buscaCrasCreas() {

  var oParametro          = new Object();
      oParametro.exec     = 'buscaCrasCreas';
      oParametro.iFamilia = oUrl.familia;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoBuscaCrasCreas;

  js_divCarregando("Aguarde, buscando histórico de vínculos", 'msgBox');
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorna os dados do historico de vinculo e monta na grid
 */
function js_retornoBuscaCrasCreas(oResponse) {

  js_removeObj('msgBox');
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.aHistorico.length > 0) {

    oGridCrasCreas.clearAll(true);
    oRetorno.aHistorico.each(function(oHistorico, iLinha) {

      var aLinha    = new Array();
          aLinha[0] = oHistorico.sIdentificador.urlDecode();
          aLinha[1] = oHistorico.sCrasCreas.urlDecode();
          aLinha[2] = oHistorico.dtInicio.urlDecode();
          aLinha[3] = oHistorico.dtFim.urlDecode();
          aLinha[4] = oHistorico.sSituacao.urlDecode();

      oGridCrasCreas.addRow(aLinha);
    });

    oGridCrasCreas.renderRows();
  }
}

js_buscaCrasCreas();
</script>