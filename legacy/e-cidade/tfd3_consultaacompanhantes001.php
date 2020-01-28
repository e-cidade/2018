<?php
/*
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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);
?>

<html>
  <head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
   <link href="estilos.css" rel="stylesheet" type="text/css">
   <link href="estilos/tab.style.css" rel="stylesheet" type="text/css">
   <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
   <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  </head>
<body>
  <fieldset>
    <legend>Acompanhantes</legend>
    <div id="gridAcompanhantes" ></div>
  </fieldset>
</body>
</html>
<script>
const MENSANGEM_SAU3_ACOMPANHANTES001 = 'saude.ambulatorial.sau3_acompanhantes001.';

var oGet = js_urlToObject();

/**
 * Monta a grid coms os dados dos Acompanhantes do Pedido TFD
 * @type {DBGrid}
 */
var oGridAcompanhantes = new DBGrid( 'oGridAcompanhantes' );
'CGS', 'Nome', 'Motivo', 'RG', 'CPF', 'Contato'

var aHeaders    = [ 'CGS', 'Nome', 'Motivo', 'RG', 'CPF', 'Contato' ];
var aCellAlign  = [ 'right', 'left', 'left', 'right', 'right', 'right' ];

oGridAcompanhantes.nameInstance = 'oGridAcompanhantes';
oGridAcompanhantes.setCellWidth( [ "5%", "45%", "15%", "10%", "10%", "15%"] );
oGridAcompanhantes.setCellAlign(aCellAlign);
oGridAcompanhantes.setHeader(aHeaders);
oGridAcompanhantes.setHeight(150);
oGridAcompanhantes.show($('gridAcompanhantes'));

/**
 * Busca os dados dos Acompanhates do Pedido TFD e os adiciona a Grid
 */
(function() {

var oParametros         = {};
    oParametros.exec    = 'getAcompanhantesPedidoTfd';
    oParametros.iPedido = oGet.iPedido;

var oAjaxRequest = new AjaxRequest( 'tfd4_pedidotfd.RPC.php', oParametros, retornoBuscaAcompanhantes);
    oAjaxRequest.setMessage( "Aguarde, buscando acompanhantes do pedido..." );
    oAjaxRequest.execute();
})();

function retornoBuscaAcompanhantes( oRetorno, erro ) {

  oGridAcompanhantes.clearAll(true);

  if ( !erro ) {

    oRetorno.oAcompanhantes.each( function( oAcompanhante, iLinha ) {

      aLinhas = new Array();
      aLinhas.push( oAcompanhante.z01_i_cgsund );
      aLinhas.push( oAcompanhante.z01_v_nome.urlDecode() );
      aLinhas.push( oAcompanhante.sMotivo.urlDecode() );
      aLinhas.push( oAcompanhante.z01_v_ident.urlDecode() );
      aLinhas.push( oAcompanhante.z01_v_cgccpf.urlDecode() );

      var sContato = '';

      if ( !empty( oAcompanhante.sTelefone ) ) {
        sContato += oAcompanhante.sTelefone.urlDecode();
      }

      if ( !empty( sContato ) && !empty( oAcompanhante.sCelular ) ) {
        sContato += " / " + oAcompanhante.sCelular.urlDecode();
      } else if ( !empty(oAcompanhante.sCelular) ) {
        sContato = oAcompanhante.sCelular.urlDecode();
      }

      aLinhas.push( sContato );

      oGridAcompanhantes.addRow( aLinhas );
      oGridAcompanhantes.aRows[ iLinha ].aCells[1].addClassName( 'elipse' );
    });
  }

  oGridAcompanhantes.renderRows();
}

</script>