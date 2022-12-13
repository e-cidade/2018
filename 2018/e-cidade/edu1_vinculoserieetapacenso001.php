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
require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form>
      <fieldset>
        <legend>Vínculo Etapa Censo</legend>

        <table class="form-container">
          <tr>
            <td>
              <label for="etapa">Etapa:</label>
            </td>
            <td style="display: none;">
              <input id="codigoEtapa" type="text" value="" class="readonly field-size2" />
            </td>
            <td colspan="2">
              <input id="descricaoEtapa" type="text" value="" class="readonly field-size-max" readonly="readonly" />
            </td>
          </tr>

          <tr>
            <td>
              <a href="#" onclick="pesquisaEtapaCenso(true)">Etapa Censo:</a>
            </td>
            <td style="display: none;">
              <input id="codigoVinculo" type="text" value="" class="readonly field-size2" />
            </td>
            <td>
              <input id="codigoEtapaCenso" type="text" value="" class="field-size2" />
            </td>
            <td>
              <input id="descricaoEtapaCenso" type="text" value="" class="readonly field-size7" readonly="readonly" />
            </td>
          </tr>

          <tr>
            <td>
              <label for="ano">Ano:</label>
            </td>
            <td>
              <input id="ano" type="text" value="" class="field-size2 readonly" readonly="readonly" />
            </td>
          </tr>
        </table>

      </fieldset>

      <input id="salvarVinculo" type="button" value="Salvar" />
      <input id="limpar" type="button" value="Limpar" />
    </form>
  </div>
  <div class="subcontainer">
    <fieldset style='width:500px;'>
      <legend>Etapas vinculadas</legend>
      <div id='ctnGridEtapasCenso'></div>
    </fieldset>
  </div>
</body>

<script>
const MENSAGENS_EDU1_VINCULSERIEETAPACENSO001 = 'educacao.escola.edu1_vinculoserieetapacenso001.';

var sRpc = 'edu4_etapas.RPC.php';
var oGet = js_urlToObject();


var aHeaders    = ["Etapa", "Ano", "Ação"];
var aCellWidth  = ["70", "15%", "15%"];
var aCellAlign  = ["left", "center", "center"];
var oGridEtapasCenco = new DBGrid('gridEtapasCenco');

oGridEtapasCenco.nameInstance = 'oGridEtapasCenco';
oGridEtapasCenco.setCellWidth(aCellWidth);
oGridEtapasCenco.setCellAlign(aCellAlign);
oGridEtapasCenco.setHeader(aHeaders);
oGridEtapasCenco.setHeight(130);
oGridEtapasCenco.show($('ctnGridEtapasCenso'));

/**
 * Busca vínculos que possam existir da etapa, com etapas do censo
 */
function buscaEtapasCensoVinculadas() {

  var oDadosRequisicao        = {};
      oDadosRequisicao.exec   = 'buscarEtapaVinculadaEtapasCenso';
      oDadosRequisicao.iSerie = oGet.iEtapa;

  var oAjaxRequest = new AjaxRequest( sRpc, oDadosRequisicao, retornoBuscaEtapasCensoVinculadas );
      oAjaxRequest.setMessage( _M( MENSAGENS_EDU1_VINCULSERIEETAPACENSO001 + 'buscando_vinculos' ) );
      oAjaxRequest.execute();
}

/**
 * Retorna os vínculos com etapas do censo, caso existam, preenchendo a Grid
 */
function retornoBuscaEtapasCensoVinculadas( oRetorno, lErro ) {

  if ( lErro ) {
    alert(oRetorno.message.urlDecode());
  }

  oGridEtapasCenco.clearAll(true);
  oRetorno.aEtapasCenso.each( function(oVinculoCensoEtapa) {

    var sIdBtn = 'altera_' + oVinculoCensoEtapa.vinculo;
    var oBtnAlterar = new Element ('input', {'type':'button', 'value':'A', 'id':sIdBtn, 'name':sIdBtn});
    oBtnAlterar.setAttribute('onclick', 'carregaDadosAlterar('+oVinculoCensoEtapa.toSource()+')');

    var aLinha = [];
    aLinha.push( oVinculoCensoEtapa.descricao.urlDecode()) ;
    aLinha.push( oVinculoCensoEtapa.ano );
    aLinha.push( oBtnAlterar. outerHTML );
    oGridEtapasCenco.addRow(aLinha);

  });

  oGridEtapasCenco.renderRows();

}


function carregaDadosAlterar(oDadosVinculoCensoEtapa) {

  $('codigoVinculo').value       = oDadosVinculoCensoEtapa.vinculo;
  $('descricaoEtapaCenso').value = oDadosVinculoCensoEtapa.descricao.urlDecode();
  $('codigoEtapaCenso').value    = oDadosVinculoCensoEtapa.etapa_censo;
  $('ano').value                 = oDadosVinculoCensoEtapa.ano;

}


/**
 * Pesquisa as etapas do censo para vínculo
 * @param lMostra - Controle se deve ser carregada a função de pesquisa
 */
function pesquisaEtapaCenso( lMostra ) {

  var sUrl        = 'func_seriecensoetapa.php?iSerie=' + $F('codigoEtapa') + '&funcao_js=parent.retornoEtapaCenso';
  var sParametros = '|ed266_i_codigo|ed266_c_descr|ed131_ano';

  if( !lMostra ) {
    sParametros = '&pesquisa_chave='+ $F('codigoEtapaCenso');
  }

  js_OpenJanelaIframe( '', 'db_iframe_seriecensoetapa', sUrl + sParametros, 'Pesquisa Etapa Censo', lMostra );
}

/**
 * Preenche os campos de acordo com o retorno
 */
function retornoEtapaCenso() {

  db_iframe_seriecensoetapa.hide();

  if( arguments[1] !== true && arguments[1] !== false ) {

    $('codigoEtapaCenso').value    = arguments[0];
    $('descricaoEtapaCenso').value = arguments[1];
    $('ano').value                 = arguments[2];
  } else if( arguments[1] === false ) {

    $('codigoEtapaCenso').value    = arguments[3];
    $('descricaoEtapaCenso').value = arguments[0];
    $('ano').value                 = arguments[2];
  } else if( arguments[1] === true ) {

    $('codigoEtapaCenso').value    = '';
    $('descricaoEtapaCenso').value = arguments[0];
    $('ano').value                 = '';
  }
}

/**
 * Salva o vínculo conforme etapa do censo selecionada
 */
function salvarVinculo() {

  if( !validaCampos() ) {
    return false;
  }

  var oDadosRequisicao                = {};
      oDadosRequisicao.exec           = 'salvarEtapaCenso';
      oDadosRequisicao.iCodigoVinculo = $F('codigoVinculo');
      oDadosRequisicao.iEtapa         = $F('codigoEtapa');
      oDadosRequisicao.iEtapaCenso    = $F('codigoEtapaCenso');
      oDadosRequisicao.iAno           = $F('ano');

  var oAjaxRequest = new AjaxRequest( sRpc, oDadosRequisicao, retornoSalvarVinculo );
      oAjaxRequest.setMessage( _M( MENSAGENS_EDU1_VINCULSERIEETAPACENSO001 + 'salvando_vinculo' ) );
      oAjaxRequest.execute();
}

/**
 * Retorno do vínculo salvo
 */
function retornoSalvarVinculo( oRetorno, lErro ) {

  alert( oRetorno.message.urlDecode() );

  if( lErro ) {
    return false;
  }

  limparCampos();
  buscaEtapasCensoVinculadas();
}

/**
 * Valida se todos os campos foram preenchidos, para salvar o vínculo
 */
function validaCampos() {

  if( empty( $F('codigoEtapa') ) ) {

    alert( _M( MENSAGENS_EDU1_VINCULSERIEETAPACENSO001 + 'etapa_nao_informada' ) );
    return false;
  }

  if( empty( $F('codigoEtapaCenso') ) ) {

    alert( _M( MENSAGENS_EDU1_VINCULSERIEETAPACENSO001 + 'etapa_censo_nao_informada' ) );
    return false;
  }

  if( empty( $F('ano') ) ) {

    alert( _M( MENSAGENS_EDU1_VINCULSERIEETAPACENSO001 + 'ano_nao_informado' ) );
    return false;
  }

  return true;
}

/**
 * Limpa todos os campos, com exceção da etapa do ecidade
 */
function limparCampos() {

  $('codigoVinculo').value       = '';
  $('codigoEtapaCenso').value    = '';
  $('descricaoEtapaCenso').value = '';
  $('ano').value                 = '';

}

$('codigoEtapa').value    = oGet.iEtapa;
$('descricaoEtapa').value = oGet.sEtapa;

$('codigoEtapaCenso').onchange = function() {
  pesquisaEtapaCenso( false );
};

$('salvarVinculo').onclick = function() {
  salvarVinculo();
};

$('limpar').onclick = function() {
  limparCampos();
};

buscaEtapasCensoVinculadas();
</script>