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

$oDaoPedidoTFD    = new cl_tfd_procpedidotfd();
$sCampos          = " sd63_c_procedimento, sd63_c_nome, tf04_c_descr ";
$sWhere           = " tf01_i_codigo = {$oGet->iPedido} ";
$sSqlTratamentos  = $oDaoPedidoTFD->sql_query2(null, $sCampos, null, $sWhere);
$rsTratamentos    = db_query($sSqlTratamentos);

$aTratamentos = array();
if ( $rsTratamentos && pg_num_rows($rsTratamentos) > 0) {

  $iLinha = pg_num_rows($rsTratamentos);

  for ($i = 0; $i < $iLinha; $i++) {

    $oDados = db_utils::fieldsMemory($rsTratamentos, $i);
    $oTratamento = new stdClass();
    $oTratamento->sCodigo       = $oDados->sd63_c_procedimento;
    $oTratamento->sProcedimento = utf8_encode($oDados->sd63_c_nome);
    $oTratamento->sTipo         = $oDados->tf04_c_descr;

    $aTratamentos[] = $oTratamento;
  }
}

$aJsonTratamentos = json_encode($aTratamentos);

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
    <legend>Tratamentos</legend>
    <div id="gridTratamentos" ></div>
  </fieldset>
</body>
</html>
<script>

var oGet = js_urlToObject();
var o

/**
 * Monta a grid coms os dados dos Tratamentos do Pedido TFD
 * @type {DBGrid}
 */
var aDadosTratamentos = <? echo $aJsonTratamentos?>;
var oGridTratamentos  = new DBGrid( 'oGridTratamentos' );

var aHeaders    = [ 'Tipo', 'Código', 'Tratamento' ];
var aCellAlign  = [ 'left', 'left', 'left' ];

oGridTratamentos.nameInstance = 'oGridTratamentos';
oGridTratamentos.setCellWidth( [ "20%", "15%", "65%"] );
oGridTratamentos.setCellAlign(aCellAlign);
oGridTratamentos.setHeader(aHeaders);
oGridTratamentos.setHeight(150);
oGridTratamentos.show($('gridTratamentos'));

oGridTratamentos.clearAll(true);
aDadosTratamentos.each( function( oTratamento, iLinha ) {

  aLinhas = new Array();
  aLinhas.push( oTratamento.sTipo );
  aLinhas.push( oTratamento.sCodigo );
  aLinhas.push( oTratamento.sProcedimento.urlDecode() );
  oGridTratamentos.addRow( aLinhas );
  oGridTratamentos.aRows[ iLinha ].aCells[2].addClassName( 'elipse' );
});

oGridTratamentos.renderRows();

</script>