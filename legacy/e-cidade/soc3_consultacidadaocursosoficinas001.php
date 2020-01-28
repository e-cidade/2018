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
    <legend><b>Cursos do Cidadão</b></legend>
     <div id='gridContainer'></div>
  </fieldset>
  <input id="btnImprimir" name="btnImprimir" type="button" value="Imprimir Relatório" class="container" />
</body>
<script type="text/javascript">
var oUrl     = js_urlToObject();
var sUrlRpc  = 'soc3_consultacidadao.RPC.php';
var aHeaders = new Array("Código", "Descrição", "Situação", "Data de Início", "Data de Fim");

var oGridCursos              = new DBGrid('gridCursos');
    oGridCursos.nameInstance = "oGridCursos"; 
    oGridCursos.setCellWidth(new Array('5%', '55%', '10%', '15%', '15%'));
    oGridCursos.setCellAlign(new Array('center', 'left', 'left', 'center', 'center'));
    oGridCursos.setHeader(aHeaders);
    oGridCursos.setHeight(150);
    oGridCursos.show($('gridContainer'));

/**
 * Busca os cursos/oficinas realizados pelo cidadao
 */
function js_carregaDados() {

  var oParametro          = new Object();
      oParametro.exec     = "buscaCursosOficinas";
      oParametro.iCidadao = oUrl.cidadao;

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json='+Object.toJSON(oParametro);
      oDadosRequisicao.onComplete = js_retornoCarregaDados;

  js_divCarregando('Buscando os cursos do cidadão...', 'msgBox');
  new Ajax.Request(sUrlRpc, oDadosRequisicao);
}

/**
 * Retorno da busca dos cursos/oficinas realizados pelo cidadao
 */
function js_retornoCarregaDados(oResponse) {

  js_removeObj("msgBox");
  var oRetorno = eval('('+oResponse.responseText+')');

  if (oRetorno.aCursosOficinas.length > 0) {

    oGridCursos.clearAll(true);
    oRetorno.aCursosOficinas.each(function(oCurso, iLinha) {

      var aLinha = new Array();
          aLinha[0] = oCurso.iCodigo;
          aLinha[1] = oCurso.sNome.urlDecode();
          aLinha[2] = oCurso.sSituacao.urlDecode();
          aLinha[3] = oCurso.sDataInicio.urlDecode();
          aLinha[4] = oCurso.sDataFim.urlDecode();

      oGridCursos.addRow(aLinha);
    });
    oGridCursos.renderRows();
  }
}

/**
 * Imprime o relatorio com os cursos realizados pelo cidadao
 */
$('btnImprimir').observe("click", function() {

  var sLocation  = "soc2_cursosoficinasporcidadao002.php?";
      sLocation += "&iCidadao="+oUrl.cidadao;

  jan = window.open(sLocation, 
                    '', 
                    'width='+(screen.availWidth-5)+
                    ',height='+(screen.availHeight-40)+
                    ',scrollbars=1,location=0');
  jan.moveTo(0,0);
});

js_carregaDados();
</script>