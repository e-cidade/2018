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
require_once("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);

$oDaoPedidoTFD = new cl_tfd_pedidotfd();
$sSqlAndamento = $oDaoPedidoTFD->sql_query_andamento_pedido($oGet->iPedido);
$aAndamentos = array();
$sMsgErro    = null;

try {

  $rsAndamento = db_query($sSqlAndamento);
  if (!$rsAndamento )  {
    throw new Exception(pg_last_error());
  }

  $iLinhas = pg_num_rows($rsAndamento);

  for ($i = 0; $i < $iLinhas; $i++ ) {

    $oDados = db_utils::fieldsMemory($rsAndamento, $i);
    if ( empty($oDados->usuario) ) {
      continue;
    }

    $oUsuario             = UsuarioSistemaRepository::getPorCodigo($oDados->usuario);
    $oDados->sNomeUsuario = utf8_encode($oUsuario->getCGM()->getNome());
    $oDados->observacao   = utf8_encode($oDados->observacao);
    $oDados->situacao     = utf8_encode($oDados->situacao);
    $aAndamentos[]        = $oDados;
  }

} catch (Exception $e) {
  $sMsgErro = $e->getMessage();
}

$aDadosAndamentoJson = json_encode($aAndamentos);

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
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
</head>
<body class='body-default'>

<div class="subcontainer">
  <fieldset style='width:1000px;'>
    <legend>Andamento</legend>
    <div id='ctnGridAndamento'> </div>
  </fieldset>
  <input type="button" value='Imprimir' name='imprimir' id='imprimirAndamento'>

</div>
</body>

<script type="text/javascript">

var oGet = js_urlToObject();

var aDadosAndamento = <? echo $aDadosAndamentoJson?>;
var oGridAndamento  = new DBGrid('gridAndamento');
oGridAndamento.nameInstance = 'oGridAndamento';
oGridAndamento.setCellWidth(['10%', '8%', '30%', '10%','42%']);
oGridAndamento.setCellAlign(['center', 'center', 'left', 'center', 'left']);
oGridAndamento.setHeader(['Data', 'Hora', 'Usuário', 'Situação', 'Observação']);
oGridAndamento.setHeight(150);
oGridAndamento.show($('ctnGridAndamento'));

oGridAndamento.clearAll(true);
aDadosAndamento.each(function (oAndamento, iLinha) {

  var aLinha = [];
  aLinha.push(js_formatar(oAndamento.data, 'd'));
  aLinha.push(oAndamento.hora);
  aLinha.push(oAndamento.sNomeUsuario.urlDecode());
  aLinha.push(oAndamento.situacao);
  aLinha.push(oAndamento.observacao.urlDecode());
  oGridAndamento.addRow(aLinha);

  oGridAndamento.aRows[ iLinha ].aCells[2].addClassName( 'elipse' );
  oGridAndamento.aRows[ iLinha ].aCells[4].addClassName( 'elipse' );

});

oGridAndamento.renderRows();

aDadosAndamento.each(function (oAndamento, iLinha) {

  oParametros = {iWidth:'250', oPosition : {sVertical : 'T', sHorizontal : 'L'}};

  oGridAndamento.setHint(iLinha, 2, oAndamento.sNomeUsuario.urlDecode(), oParametros);
  oGridAndamento.setHint(iLinha, 4, oAndamento.observacao.urlDecode(), oParametros);

});

$('imprimirAndamento').observe('click', function() {

  var sUrl = "tfd3_pedidoandamento003.php";
  sUrl    += "?iPedido=" + oGet.iPedido;

  oJan = window.open(sUrl, '', '');
  oJan.moveTo(0, 0);


});

</script>
</html>