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
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo;
$oRotulo->label('tf01_i_cgsund');
$oRotulo->label('z01_v_nome');

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
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
</head>
<body class='body-default'>

  <div class="container">

    <table>
      <tr>
        <td nowrap title="<?php echo $Ttf01_i_cgsund;?>">
          <?php db_ancora($Ltf01_i_cgsund, "js_pesquisaCgs(true);", 1); ?>
        </td>
        <td nowrap="nowrap">
          <?php
            db_input('tf01_i_cgsund', 10, $Itf01_i_cgsund, true, 'text', 1, ' onchange="js_pesquisaCgs(false); "');
            db_input('z01_v_nome', 50, $Iz01_v_nome, true, 'text', 3, '');
          ?>
        </td>
      </tr>
    </table>
    <input type='button' id='pesquisa_pedidos' name='pesquisa_pedidos' value="Pesquisar"  />
  </div>
  <div style ='width:1000px;' class="subcontainer" >
    <fieldset >
      <legend> Pedidos </legend>
      <div id='cntGridPedido'></div>
    </fieldset>
  </div>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>

<script type="text/javascript">

const MGS_TFD3_CONSULTAPEDIDOTFD001 = 'saude.tfd.tfd3_consultapedidotfd001.';

var oGridPedidos            = new DBGrid('gridPedido');
oGridPedidos.nameInstance   = 'oGridPedidos';
oGridPedidos.setHeight(120);
oGridPedidos.allowSelectColumns(false);
oGridPedidos.setCellWidth( ['5%', '10%', '10%', '30%', '25%', '10%','10%'] );
oGridPedidos.setHeader(    ['Pedido', 'Entrada', 'Saída', 'Prestadora', 'Cidade', 'Situação']);
oGridPedidos.setCellAlign( ['center', 'center', 'center', 'center', 'center', 'center']);
oGridPedidos.show($('cntGridPedido'));


function js_pesquisaCgs( lMostra ) {

  var sUrl = 'func_cgs_und.php?';
  if (lMostra) {

    sUrl += 'funcao_js=parent.js_mostracgs|z01_i_cgsund|z01_v_nome'
    js_OpenJanelaIframe('', 'db_iframe_cgs_und', sUrl, 'Pesquisa CGS', true);
  } else if ( $F('tf01_i_cgsund') != '' ) {

    sUrl += 'pesquisa_chave='+ $F('tf01_i_cgsund');
    sUrl += '&funcao_js=parent.js_mostracgs';

    js_OpenJanelaIframe('', 'db_iframe_cgs_und', sUrl, 'Pesquisa CGS', false);
  } else {

    $('tf01_i_cgsund').value = '';
    $('z01_v_nome').value    = '';
  }
}



function js_mostracgs( chave, erro ){

  if ( typeof arguments[1] == 'boolean') {

    $('z01_v_nome').value = arguments[0];
    if ( arguments[1] ) {

      $('tf01_i_cgsund').focus();
      $('tf01_i_cgsund').value = '';
    }
  } else {

    $('tf01_i_cgsund').value = arguments[0];
    $('z01_v_nome').value    = arguments[1];
    db_iframe_cgs_und.hide();
  }
}


$('pesquisa_pedidos').observe( 'click', function() {

  if ( $F('tf01_i_cgsund') == '' ) {

    alert(_M(MGS_TFD3_CONSULTAPEDIDOTFD001 + 'cgs_nao_informado'));
    return;
  }

  var oParametros = { exec : 'getPedidosTfdCgs', iCgs :  $F('tf01_i_cgsund') };
  var oAjax = new AjaxRequest('tfd4_pedidotfd.RPC.php', oParametros, js_retornoBuscaPedidos);
  oAjax.setMessage( _M(MGS_TFD3_CONSULTAPEDIDOTFD001 + 'buscando_pedidos') );
  oAjax.execute();

});


function js_retornoBuscaPedidos(oRetorno, lErro) {

  if (lErro) {

    alert( oRetorno.sMessage.urlDecode());
    return false;
  }

  oGridPedidos.clearAll(true);

  oRetorno.oPedidos.each( function(oPedido, i) {

    var aLinha = [];
    aLinha.push(oPedido.tf01_i_codigo);
    aLinha.push(js_formatar(oPedido.tf16_d_dataagendamento, 'd'));
    aLinha.push(js_formatar(oPedido.tf17_d_datasaida, 'd'));
    aLinha.push(oPedido.z01_nomeprestadora.urlDecode());
    aLinha.push(oPedido.tf03_c_descr.urlDecode());
    aLinha.push(oPedido.tf26_c_descr.urlDecode());
    oGridPedidos.addRow(aLinha);



  });
  oGridPedidos.renderRows();

  oRetorno.oPedidos.each( function(oPedido, i) {

    $(oGridPedidos.aRows[i].sId).observe('click', function() {
      js_abreConsulta(oPedido.tf01_i_codigo);
    });
  });
}

function js_abreConsulta(iPedido) {

  var sUrl = 'tfd3_consultapedidotfd002.php?iPedido=' + iPedido;
  js_OpenJanelaIframe('', 'iframe_consultatfd', sUrl, 'Consulta do pedido TFD', true);
}


</script>
</html>