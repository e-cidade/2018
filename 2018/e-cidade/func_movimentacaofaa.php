<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link rel="stylesheet" type="text/css" href="estilos.css">
  <link rel="stylesheet" type="text/css" href="estilos/grid.style.css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>

</head>
<body class='body-default'>
  <div style="width: 98%;">
    <fieldset style="width: 100%;">
      <legend>Movimentações da Ficha de Atendimento</legend>
      <div id="ctnGridMovimentacoes">

      </div>
    </fieldset>
  </div>
</body>

<script type="text/javascript">

var oGet = js_urlToObject();

var oGridMovimentacao   = new DBGrid('gridContasLancamentos');
var aHeaders   = ['Usuário', 'Situação', 'Data', 'Hora', 'Setor', 'Observação', 'codigo'];
var aCellWidth = [ '27%', '11%', '7%', '5%', '15%', '35%'];
var aCellAlign = ['left', 'left', 'center', 'center', 'left', 'left' ];

oGridMovimentacao.nameInstance = 'oGridMovimentacao';
oGridMovimentacao.setCellWidth(aCellWidth);
oGridMovimentacao.setCellAlign(aCellAlign);
oGridMovimentacao.setHeader(aHeaders);
oGridMovimentacao.setHeight(150);
oGridMovimentacao.aHeaders[6].lDisplayed = false;
oGridMovimentacao.show($('ctnGridMovimentacoes'));

var oParametro    = {'sExecucao': 'buscarMovimentacoes','iProntuario': oGet.iProntuario};
var oAjaxRequest  = new AjaxRequest('sau4_fichaatendimento.RPC.php', oParametro, callBackRetorno);
oAjaxRequest.setMessage('Buscando departamentos...');
oAjaxRequest.execute();

function callBackRetorno(oRetorno, lErro) {

  if (lErro) {
    alert ( oRetorno.sMensagem.urlDecode() );
    return false;
  }

  oGridMovimentacao.clearAll(true);

  oRetorno.aMovimentacoes.each(function(oMovimentacao) {

    var aLinha = [];
    aLinha.push( oMovimentacao.sUsuario.urlDecode() );
    aLinha.push( oMovimentacao.sSituacao.urlDecode() );
    aLinha.push( oMovimentacao.dtMovimentacao );
    aLinha.push( oMovimentacao.sHoraMovimentacao.urlDecode() );
    aLinha.push( oMovimentacao.sSetorAmbulatorial.urlDecode() );
    aLinha.push( oMovimentacao.sObservacao.urlDecode() );
    aLinha.push( oMovimentacao.iCodigo );

    oGridMovimentacao.addRow(aLinha);
  });

  oGridMovimentacao.renderRows();

  oRetorno.aMovimentacoes.each(function(oMovimentacao, iLinha) {

    oGridMovimentacao.aRows[iLinha].aCells[0].addClassName( "elipse" );
    if ( !empty( oMovimentacao.sObservacao ) ) {

      var sIdLinha        = oGridMovimentacao.aRows[iLinha].aCells[5].sId;
      $(sIdLinha).style.textOverflow = "ellipsis";
      oGridMovimentacao.setHint(iLinha, 5, oMovimentacao.sObservacao.urlDecode());
    }
  });
}

</script>
</html>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  if(input != null) {
    input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
  }
})();
</script>