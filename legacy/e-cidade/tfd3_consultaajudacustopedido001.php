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
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  </head>
<body>
  <fieldset>
    <legend>Ajudas de Custo</legend>
    <div>
      <table>
        <tr>
          <td>
            <label class="bold">Retirado Por:</label>
          </td>
          <td>
            <input id="codigoCgs" type="text" value="" disabled="disabled" />
          </td>
          <td>
            <input id="nomeCgs" type="text" value="" disabled="disabled" />
          </td>
        </tr>
      </table>
    </div>
    <div id="gridAjudasCusto"></div>
  </fieldset>
</body>
</html>
<script>
$('codigoCgs').className             = 'field-size2 readonly';
$('codigoCgs').style.backgroundColor = '#DEB887';

$('nomeCgs').className             = 'field-size9 readonly';
$('nomeCgs').style.backgroundColor = '#DEB887';

const MENSAGENS_TFD3_CONSULTAAJUDACUSTOPEDIDO001 = 'saude.tfd.tfd3_consultaajudacustopedido001.';

var oGet = js_urlToObject();
var sRpc = 'tfd4_ajudacusto.RPC.php';

var oGridAjudaCusto = new DBGrid( 'oGridAjudaCusto' );
    oGridAjudaCusto.setHeader( [ "Codigo",  "CGS", "Beneficiado", "Ajuda", "Valor", "Observação" ] );
    oGridAjudaCusto.setCellAlign( [ "right", "right", "left", "left", "center", "left" ] );
    oGridAjudaCusto.setCellWidth( [ "1%", "5%", "36%", "20%", "5%", "33%" ] );
    oGridAjudaCusto.aHeaders[0].lDisplayed = false;
    oGridAjudaCusto.show( $('gridAjudasCusto') );

function buscaAjudasCusto() {

  var oParametros         = {};
      oParametros.exec    = 'ajudaCustoPorPedido';
      oParametros.iPedido = oGet.iPedido;

  var oAjaxRequest = new AjaxRequest( sRpc, oParametros, retornoPesquisaAjudasCusto );
      oAjaxRequest.setMessage( _M( MENSAGENS_TFD3_CONSULTAAJUDACUSTOPEDIDO001 + 'buscando_ajudas_custo' ) );
      oAjaxRequest.execute();
}

function retornoPesquisaAjudasCusto( oRetorno, lErro ) {

  oGridAjudaCusto.clearAll( true );

  if ( !lErro ) {

    $('codigoCgs').value = oRetorno.iCgsRetirante;
    $('nomeCgs').value   = oRetorno.sCgsRetirante.urlDecode();


    oRetorno.aAjudasCusto.each(function( oAjudaCusto ){

      var aLinhas = [];
          aLinhas.push( oAjudaCusto.iCodigoAjuda );
          aLinhas.push( oAjudaCusto.iCgsBeneficiado );
          aLinhas.push( oAjudaCusto.sCgsBeneficiado.urlDecode() );
          aLinhas.push( oAjudaCusto.sDescricaoAjuda.urlDecode() );
          aLinhas.push( js_formatar( oAjudaCusto.fValor.urlDecode(), 'f' ) );
          aLinhas.push( oAjudaCusto.sObservacao.urlDecode() );

      oGridAjudaCusto.addRow( aLinhas );
    });

    oGridAjudaCusto.renderRows();

    oRetorno.aAjudasCusto.each(function( oAjudaCusto, iLinha ){

      if( !empty( oAjudaCusto.sObservacao ) ) {

        var oParametros  = {iWidth : '462', oPosition : {sVertical : 'T', sHorizontal : 'R'}};
        var sIdLinha     = oGridAjudaCusto.aRows[iLinha].aCells[5].sId;

        $(sIdLinha).style.textOverflow = "ellipsis";
        oGridAjudaCusto.setHint( iLinha, 5, oAjudaCusto.sObservacao.urlDecode(), oParametros );
      }
    });
  }
}

buscaAjudasCusto();
</script>