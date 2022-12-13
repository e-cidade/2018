<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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


require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
</head>
<body class='body-default'>

<div class="container">

  <fieldset style='width:1000px;'>
    <legend>Procedimentos Realizados</legend>
    <div class='field-size-max' id='ctnGridProcedimentos'></div>
  </fieldset>

</div>


</body>
<script type="text/javascript">

var oGet = js_urlToObject();

var oGridProcedimentos = new DBGrid( 'gridProcedimentos' );
var aHeaders    = [ 'Código', 'Procedimento', 'Profissional', 'Data', 'Hora' ];
var aCellAlign  = [ 'left', 'left', 'left', 'center', 'center' ];
var aCellWidth  = [ '10%', '39%', '35', '8%', '8%']

oGridProcedimentos.nameInstance = 'oGridProcedimentos';
oGridProcedimentos.setCellAlign(aCellAlign);
oGridProcedimentos.setHeader(aHeaders);
oGridProcedimentos.setHeight(150);
oGridProcedimentos.setCellWidth(aCellWidth);
oGridProcedimentos.show($('ctnGridProcedimentos'));


if ( !empty(oGet.iProntuario) ) {

  var sRPC = 'sau4_fichaatendimento.RPC.php';
  var oAjaxRequest = new AjaxRequest(sRPC, {sExecucao: 'getProcedimentos', iProntuario : oGet.iProntuario, lBuscaProcedimentosLote : true }, js_callBackProcedimentos);
  oAjaxRequest.setMessage('Buscando procedimentos...');
  oAjaxRequest.execute();

}

function js_callBackProcedimentos(oRetorno, lErro) {

  if (lErro) {

    alert( oRetorno.sMensagem.urlDecode() );
    return false;
  }
  oGridProcedimentos.clearAll(true);
  oRetorno.aProcedimentos.each( function ( oProcedimento ) {

    var aLinha = [];
    aLinha.push(oProcedimento.sProcedimento);
    aLinha.push(oProcedimento.sNomeProcedimento.urlDecode());
    aLinha.push(oProcedimento.sProfissional.urlDecode());
    aLinha.push(oProcedimento.sData);
    aLinha.push(oProcedimento.sHora);
    oGridProcedimentos.addRow(aLinha);
  });

  oGridProcedimentos.renderRows();

  oRetorno.aProcedimentos.each( function ( oProcedimento, iLinha ) {

    oGridProcedimentos.aRows[iLinha].aCells[1].addClassName('elipse');

    var aHint = [];

    if ( !empty(oProcedimento.sCid) ) {
      aHint.push( "<b>CID:</b> " + oProcedimento.sCid.urlDecode() + " - " + oProcedimento.sNomeCid.urlDecode() );
    }
    if ( ! empty(oProcedimento.sTratamento) ) {
      aHint.push( "<b>Prescrição:</b> " + oProcedimento.sTratamento.urlDecode() );
    }

    if ( aHint.length > 0) {

      var sHint = aHint.implode("<br>");
      var oParametros = {iWidth:'250', oPosition : {sVertical : 'B', sHorizontal : 'R'}};
      oGridProcedimentos.setHint(iLinha, 1, sHint);
    }
  });
}


</script>
</html>