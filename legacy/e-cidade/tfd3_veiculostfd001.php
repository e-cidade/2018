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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oRotulo = new rotulocampo;
$oRotulo->label("tf03_i_codigo");
$oRotulo->label("tf03_c_descr");

$sDataAtual = date( 'd/m/Y', db_getsession("DB_datausu") );
$oDataAtual = new DBDate( $sDataAtual );
$iDia       = $oDataAtual->getDia();
$iMes       = $oDataAtual->getMes();
$iAno       = $oDataAtual->getAno();
?>
<html>
  <head>
    <title>DBSeller Informática Ltda - Página Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script type="text/javascript" src="scripts/widgets/dbautocomplete.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <form action="#" class="container">
      <fieldset>
        <legend>Veículos TFD</legend>
        <table class="form-container">
          <tr>
            <td>
              <label for="dataSaida">Data da Saída:</label>
            </td>
            <td>
              <?php
              db_inputdata( 'dataSaida', $iDia, $iMes, $iAno, true, 'text', 1 );
              ?>
          </tr>
          <tr>
            <td title="Destino dos pedidos.">
              <?php
              db_ancora("<b>Destino:</b>", "pesquisaDestino();", 1);
              ?>
            </td>
            <td>
              <?php
              db_input( 'tf03_i_codigo', 10, $Itf03_i_codigo, true, 'hidden', 3 );
              db_input( 'tf03_c_descr',  65, $Itf03_c_descr,  true, 'text',   1 );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input id="btnPesquisar" type="button" value="Pesquisar" onclick="pesquisaVeiculos();" />
    </form>
    <div>
      <fieldset style="width: 85%; margin: 25px auto 0 auto;">
        <legend>Veículos vinculados a pedido(s)</legend>
        <div id="gridVeiculosComVinculo"></div>
      </fieldset>
      <fieldset style="width: 85%; margin: 25px auto 0 auto;">
        <legend>Veículos sem vínculo a pedido(s)</legend>
        <div id="gridVeiculosSemVinculo"></div>
      </fieldset>
    </div>
  </body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
const MENSAGENS_TFD3_VEICULOSTFD001 = 'saude.tfd.tfd3_veiculostfd001.';
var sRpc = 'tfd4_veiculos.RPC.php';

/* AUTOCOMPLETE DESTINO */
$('tf03_c_descr').onkeydown = '';
oAutoCompleteDestino  = new dbAutoComplete($('tf03_c_descr'), 'sau4_autocompletesaude.RPC.php');
oAutoCompleteDestino.setTxtFieldId($('tf03_c_descr'));
oAutoCompleteDestino.setHeightList(180);
oAutoCompleteDestino.show();
oAutoCompleteDestino.setCallBackFunction(function(iId, sLabel) {

                                          $('tf03_i_codigo').value = iId;
                                          $('tf03_c_descr').value  = sLabel;
                                         });
oAutoCompleteDestino.setQueryStringFunction(function() {

                                              $('tf03_i_codigo').value  = '';
                                              var oParamComplete        = new Object();
                                                  oParamComplete.exec   = 'DesinoPedidoTFD';
                                                  oParamComplete.string = $('tf03_c_descr').value;
                                              return 'json='+Object.toJSON(oParamComplete);
                                            });
/* FIM AUTOCOMPLETE DESTINO */

function pesquisaDestino() {

  js_OpenJanelaIframe(
                      '',
                      'db_iframe_tfd_destino',
                      'func_tfd_destino.php?funcao_js=parent.retornoPesquisaDestino|tf03_i_codigo|tf03_c_descr',
                      'Pesquisa',
                      true
                     );
}

function retornoPesquisaDestino() {

  $('tf03_i_codigo').value = arguments[0];
  $('tf03_c_descr').value  = arguments[1];

  db_iframe_tfd_destino.hide();
}


/**
 * ****************************************************
 * CRIAÇÃO DA GRID DOS VEÍCULOS VINCULADOS A UM PEDIDO
 * ****************************************************
 */
var aHeaderVeiculosVinculados = [ 'Horário', 'Destino', 'Modelo', 'Placa', 'Motorista', 'Vagas Totais', 'Livres', 'Ocupadas' ];
var aAlignVeiculosVinculados  = [ 'center', 'left', 'left', 'left', 'left', 'center', 'center', 'center' ];
var aWidthVeiculosVinculados  = [ '5%', '30%', '20%', '4%', '28%', '5%', '4%', '4%' ];

var oGridVeiculosVinculados = new DBGrid( 'oGridVeiculosVinculados' );
    oGridVeiculosVinculados.setHeader( aHeaderVeiculosVinculados );
    oGridVeiculosVinculados.setCellAlign( aAlignVeiculosVinculados );
    oGridVeiculosVinculados.setCellWidth( aWidthVeiculosVinculados );
    oGridVeiculosVinculados.setHeight( 300 );
    oGridVeiculosVinculados.show( $('gridVeiculosComVinculo') );

/**
 * *****************************************************
 * CRIAÇÃO DA GRID DOS VEÍCULOS SEM VÍNCULOS A UM PEDIDO
 * *****************************************************
 */
var oGridVeiculosSemVinculo = new DBGrid( 'oGridVeiculosSemVinculo' );
    oGridVeiculosSemVinculo.setHeader( [ 'Modelo', 'Placa', 'Vagas Totais' ] );
    oGridVeiculosSemVinculo.setCellAlign( [ 'left', 'left', 'center' ] );
    oGridVeiculosSemVinculo.setCellWidth( [ '70%', '20%', '10%' ] );
    oGridVeiculosSemVinculo.show( $('gridVeiculosSemVinculo') );

/**
 * Busca as informações dos veículos, referentes as 2 grids
 */
function pesquisaVeiculos() {

  if( empty( $F('dataSaida') ) ) {

    alert( _M( MENSAGENS_TFD3_VEICULOSTFD001 + 'data_nao_informada' ) );
    return false;
  }

  if( empty( $F('tf03_c_descr') ) ) {
    $('tf03_i_codigo').value = '';
  }

  var oParametros          = {};
      oParametros.exec     = 'buscaVericulosTFD';
      oParametros.dtSaida  = $F('dataSaida');
      oParametros.iDestino = $F('tf03_i_codigo');

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoPesquisaVeiculos );
      oAjaxRequest.setMessage( _M( MENSAGENS_TFD3_VEICULOSTFD001 + 'buscando_dados_veiculos' ) );
      oAjaxRequest.execute();
}

/**
 * Retorno dos dados para preenchimento das Grids
 * @param oRetorno
 * @param lErro
 */
function retornoPesquisaVeiculos( oRetorno, lErro ) {

  if( lErro ) {

    alert( oRetorno.sMessage.urlDecode() );
    return false;
  }

  oGridVeiculosVinculados.clearAll( true );
  oRetorno.aVeiculosComPedido.each(function( oVeiculosComPedido, iLinha ) {

    var iVagasLivres = oVeiculosComPedido.vagas - oVeiculosComPedido.passageiros;

    var aLinhas = [];
        aLinhas.push( oVeiculosComPedido.hora_saida );
        aLinhas.push( oVeiculosComPedido.destino.urlDecode() );
        aLinhas.push( oVeiculosComPedido.modelo.urlDecode() );
        aLinhas.push( oVeiculosComPedido.placa );
        aLinhas.push( oVeiculosComPedido.motorista.urlDecode() );
        aLinhas.push( oVeiculosComPedido.vagas );
        aLinhas.push( iVagasLivres );
        aLinhas.push( oVeiculosComPedido.passageiros );

    oGridVeiculosVinculados.addRow( aLinhas );

    if( iVagasLivres <= 0 ) {
      oGridVeiculosVinculados.aRows[iLinha].setClassName( 'disabled' );
    }
  });

  oGridVeiculosVinculados.renderRows();

  oGridVeiculosSemVinculo.clearAll( true );
  oRetorno.aVeiculosSemPedido.each(function( oVeiculosSemPedido ) {

    var aLinhas = [];
        aLinhas.push( oVeiculosSemPedido.modelo.urlDecode() );
        aLinhas.push( oVeiculosSemPedido.placa );
        aLinhas.push( oVeiculosSemPedido.vagas );

    oGridVeiculosSemVinculo.addRow( aLinhas );
  });

  oGridVeiculosSemVinculo.renderRows();
}
</script>