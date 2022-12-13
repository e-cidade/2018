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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("dbforms/verticalTab.widget.php"));

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
   <style type="text/css">
   .valores {
      background-color: #FFFFFF;
   }

   .tabela-cabecalho {
      width: 100%;
      border-spacing: 1;
   }
   </style>
</head>
<body>

  <div class="subcontainer" style="width: 99%;">

      <!-- Container dados do paciente  -->
      <div style="float: left;display: inline;width: 50%;">
        <fieldset>
          <legend>Dados do Pedido</legend>
          <table class="tabela-cabecalho">

            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%">
                Pedido:
              </td>
              <td nowrap="nowrap" class="valores"  style="width: 10%">
                <span id='pedido'></span>
              </td>

              <td nowrap="nowrap" class="bold text-left" style="width: 10%">
                Data do Pedido:
              </td>
              <td nowrap="nowrap" class="valores"  colspan="3"  style="width: 65%">
              <span id='dataPedido'></span>
              </td>
            </tr>

            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%">
                CGS:
              </td>
              <td nowrap="nowrap" colspan="3" class="valores"  style="width: 85%">
                <span id='cgs'></span>
              </td>
            </tr>

            <tr>
              <td nowrap="nowrap"class="bold text-left"  style="width: 15%">
                CPF:
              </td>
              <td nowrap="nowrap" class="valores" style="width: 37.5%">
                <span id='cpf'></span>
              </td>
              <td nowrap="nowrap" class="bold text-left" style="width: 10%">
                Identidade:
              </td>
              <td nowrap="nowrap" class="valores" style="width: 37.5%">
                <span id='identidade'></span>
              </td>
            </tr>


          </table>
        </fieldset>
      </div>

      <!-- Container dos dados do endereço  -->
      <div style="float: left;width: 50%">
        <fieldset >
          <legend>Informações do Solicitante</legend>
          <table class="tabela-cabecalho">
            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%;">Profissional:</td>
              <td nowrap="nowrap" colspan="3" class="valores">
                <span id="profissional"></span>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%;">CBO:</td>
              <td nowrap="nowrap" colspan="3" class="valores">
                <span id="cbo"></span>
              </td>
            </tr>
            <tr>
              <td nowrap="nowrap" class="bold text-left" style="width: 15%;">Data de Preferência:</td>
              <td nowrap="nowrap" class="valores" style="width: 35.5%">
                <span id="dataPreferencia"></span>
              </td>
              <td nowrap="nowrap" class="bold text-left" style="width: 5%">Emergência:</td>
              <td nowrap="nowrap" class="valores" style="width: 35.5%">
                <span id="emergencia"></span>
              </td>
            </tr>
          </table>
        </fieldset>
      </div>

  </div>
  <!-- Container das abas  -->
  <div class="subcontainer" style="width: 99%;">
    <fieldset>
      <legend>Opções</legend>
      <?php
        $oTabDetalhes = new verticalTab("detalhesemp",300);
        $oTabDetalhes->add("abaAcompanhante", "Acompanhantes" , "tfd3_consultaacompanhantes001.php?iPedido={$oGet->iPedido}");
        $oTabDetalhes->add("abaAjudaCusto"  , "Ajuda de Custo", "tfd3_consultaajudacustopedido001.php?iPedido={$iPedido}");
        $oTabDetalhes->add("abaAndamento"   , "Andamento"     , "tfd3_pedidoandamento002.php?iPedido={$oGet->iPedido}");
        $oTabDetalhes->add("abaPrestadora"  , "Prestadora"    , "tfd3_pedidoprestadora002.php?iPedido={$oGet->iPedido}");
        $oTabDetalhes->add("abaRegulador"   , "Regulador"     , "tfd3_consultaregulador001.php?iPedido={$oGet->iPedido}");
        $oTabDetalhes->add("abaSaida"       , "Saída"         , "tfd3_pedidosaida002.php?iPedido={$oGet->iPedido}");
        $oTabDetalhes->add("abaTratamento"  , "Tratamento"    , "tfd3_pedidotratamento002.php?iPedido={$oGet->iPedido}");
        $oTabDetalhes->show();
      ?>
    </fieldset>
  </div>

  <div class="center">
    <input id="btnImprimir" type="button" value="Imprimir Pedido" >
  </div>
</body>
</html>
<script>
var oGet = js_urlToObject();

var oParametro   = {'exec': 'getPedidoTfd','iPedido': oGet.iPedido}
var oAjaxRequest  = new AjaxRequest('tfd4_pedidotfd.RPC.php', oParametro, callBackRetorno);
oAjaxRequest.setMessage('Buscando departamentos...');
oAjaxRequest.execute();

function callBackRetorno(oRetorno, lErro) {

  if (lErro) {

    alert ( oRetorno.sMessage.urlDecode() );
    return false;
  }

  $('pedido').innerHTML          = oRetorno.oDadosPedido.iPedido ;
  $('dataPedido').innerHTML      = oRetorno.oDadosPedido.sDataPedido.urlDecode();
  $('cgs').innerHTML             = oRetorno.oDadosPedido.sCgs.urlDecode();
  $('cpf').innerHTML             = oRetorno.oDadosPedido.sCpf.urlDecode();
  $('identidade').innerHTML      = oRetorno.oDadosPedido.sIdentidade.urlDecode();
  $('profissional').innerHTML    = oRetorno.oDadosPedido.sProfissional.urlDecode();
  $('cbo').innerHTML             = oRetorno.oDadosPedido.sCbo.urlDecode();
  $('dataPreferencia').innerHTML = oRetorno.oDadosPedido.sDataPreferencia.urlDecode();
  $('emergencia').innerHTML      = oRetorno.oDadosPedido.sEmergencia.urlDecode();
}


$('btnImprimir').observe('click', function() {

  var sUrl = 'tfd2_protocolopedidotfd002.php?';
  sUrl    += "tf01_i_pedidotfd=" + oGet.iPedido;

  oJan = window.open(sUrl, '', '');
  oJan.moveTo(0, 0);

});
</script>
