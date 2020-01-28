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
require_once('libs/db_utils.php');
require_once('libs/db_app.utils.php');
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));

$oDaoLabEntrega = new cl_lab_entrega();
$oDaoLabEntrega->rotulo->label();

/**
 * Função para descobrir o laboratorio que o usuario esta logado
 * @return inteiro Codigo do laboratorio logado
 */
function laboratorioLogado(){

  $iUsuario = db_getsession('DB_id_usuario');
  $iDepto   = db_getsession('DB_coddepto');

  $oLab_labusuario = new cl_lab_labusuario();
  $oLab_labdepart  = new cl_lab_labdepart();

  $sWhere  = "la05_i_usuario = {$iUsuario}";
  $sql     = $oLab_labusuario->sql_query( null, 'la02_i_codigo, la02_c_descr', "la02_i_codigo", $sWhere );
  $rResult = $oLab_labusuario->sql_record( $sql );

  if ($oLab_labusuario->numrows == 0) {

    $sWhere  = "la03_i_departamento = {$iDepto}";
    $sql     = $oLab_labdepart->sql_query( null, 'la02_i_codigo, la02_c_descr', "la02_i_codigo", $sWhere );
    $rResult = $oLab_labdepart->sql_record( $sql );

    if ($oLab_labdepart->numrows == 0) {
      return 0;
    }
  }

  $oLab = db_utils::getCollectionByRecord($rResult);
  return $oLab[0]->la02_i_codigo;
}

$iLaboratorioLogado = laboratorioLogado();
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load( "scripts.js, prototype.js, strings.js, datagrid.widget.js" );
  db_app::load( "estilos.css" );
  ?>
</head>
<body bgcolor=#CCCCCC>
  <div class="container">
    <form class="form-container">
      <fieldset>
        <legend>Entrega Resultado</legend>
        <table>
          <tr>
            <td id="ancoraRequisicao">
            </td>
            <td>
              <input id="codigoRequisicao" type="text" value="" />
            </td>
            <td style="display: none;">
              <input id="cgsPaciente" type="text" value="" />
            </td>
            <td>
              <input id="pacienteRequisicao" type="text" value="" />
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Retirado por:</label>
            </td>
            <td colspan="2">
              <input id="retiradoPor" type="text" maxlength="100" value="" />
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Tipo de Documento:</label>
            </td>
            <td colspan="2">
              <select id="tipoDocumento">
                <option value="" selected>Selecione</option>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Documento:</label>
            </td>
            <td colspan="2">
              <input id="documento" type="text" value="" maxlength="20" />
            </td>
          </tr>
        </table>
      </fieldset>
      <input id="salvar" type="button" value="Salvar" />
    </form>
  </div>
  <center>
  <div>
    <fieldset style="width: 50%;">
      <legend>Exames</legend>
      <div id="gridExames"></div>
    </fieldset>
  </div>
  </center>
</body>
<script>
const MENSAGENS_ENTREGARESULTADONOVO = 'saude.laboratorio.lab4_entregaresultadonovo001.';
var sRpc = 'lab4_resultadoexame.RPC.php';

/**
 * Seta as classes referentes ao tamanho dos campos
 */
$('codigoRequisicao').className   = 'field-size2';
$('pacienteRequisicao').className = 'field-size7';
$('retiradoPor').className        = 'field-size-max';
$('tipoDocumento').className      = 'field-size-max';
$('documento').className          = 'field-size-max';

/**
 * Estilos para o nome do paciente da requisição
 */
$('pacienteRequisicao').readOnly              = true;
$('pacienteRequisicao').style.backgroundColor = '#DEB887';

/**
 * Cria a âncora da requisição
 */
var sTextoAncora      = document.createTextNode( "Requisição:" );
var oAncoraRequisicao = document.createElement( "a" );
    oAncoraRequisicao.appendChild( sTextoAncora );
    oAncoraRequisicao.title     = "Requisição";
    oAncoraRequisicao.href      = "#";
    oAncoraRequisicao.className = "bold";

$('ancoraRequisicao').appendChild( oAncoraRequisicao );

/**
 * Cria a grid dos exames de uma requisição
 * - Habilita a coluna de checkbox
 * - Oculta a coluna de código do item referente ao exame
 */
var oGridExames              = new DBGrid( 'gridExames' );
    oGridExames.nameInstance = 'oGridExames';
    oGridExames.setCheckbox( 0 );
    oGridExames.setHeader( new Array( 'Código Item', 'Código', 'Exame' ) );
    oGridExames.setCellAlign( new Array( 'right', 'right', 'left' ) );
    oGridExames.setCellWidth( new Array( '5%', '10%', '85%' ) );
    oGridExames.aHeaders[1].lDisplayed = false;
    oGridExames.show( $('gridExames') );

/**
 * Chamadas de eventos dos elementos
 */
oAncoraRequisicao.onclick = function() {
  pesquisaRequisicao( true );
}

$('codigoRequisicao').onchange = function() {
  pesquisaRequisicao( false );
}

$('salvar').onclick = function() {
  salvarEntregaResultado();
}

/**
 * Busca os tipos de documentos cadastrados
 */
function pesquisaTiposDocumento() {

  var oParametros           = new Object();
      oParametros.sExecucao = 'tiposDocumento';

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoPesquisaTiposDocumento;

  js_divCarregando( _M( MENSAGENS_ENTREGARESULTADONOVO + "buscando_tipos_documento" ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorno da busca dos tipos de documento
 */
function retornoPesquisaTiposDocumento( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  if( oRetorno.aTiposDocumento.length == 0 ) {

    alert( oRetorno.sMensagem.urlDecode() );
    return;
  }

  limpaSelect( $('tipoDocumento') );
  oRetorno.aTiposDocumento.each(function( oTipoDocumento ) {
    $('tipoDocumento').add( new Option( oTipoDocumento.la33_c_descr.urlDecode(), oTipoDocumento.la33_i_codigo ) );
  });
}

/**
 * Limpa um elemento select
 */
function limpaSelect( oElemento ) {

  oElemento.options.length = 0;
  oElemento.add( new Option( "Selecione", "" ) );
}

/**
 * Pesquisa uma requisição
 */
function pesquisaRequisicao( lMostra ) {

  var sFuncaoPesquisa  = 'func_lab_requisicao.php?iLaboratorioLogado=<?=$iLaboratorioLogado?>';
      sFuncaoPesquisa += '&autoriza=2&lSomenteConferidos&funcao_js=parent.retornoPesquisaRequisicao';

  if( lMostra ) {
    sFuncaoPesquisa += '|la22_i_codigo|z01_v_nome|z01_i_cgsund';
  } else {
    sFuncaoPesquisa += '&pesquisa_chave=' + $F('codigoRequisicao');
  }

  js_OpenJanelaIframe( '', 'db_iframe_lab_requisicao', sFuncaoPesquisa, 'Pesquisa', lMostra );
}

/**
 * Retorna os dados da requisição e preenche o código, nome do paciente e quem retirou
 */
function retornoPesquisaRequisicao() {

  if( arguments[1] !== true && arguments[1] !== false ) {

    $('codigoRequisicao').value   = arguments[0];
    $('pacienteRequisicao').value = arguments[1];
    $('retiradoPor').value        = arguments[1];
    $('cgsPaciente').value        = arguments[2];
  } else if( arguments[1] === false ) {

    $('pacienteRequisicao').value = arguments[0];
    $('retiradoPor').value        = arguments[0];
    $('cgsPaciente').value        = arguments[2];
  } else if( arguments[1] === true ) {

    $('codigoRequisicao').value   = '';
    $('cgsPaciente').value        = '';
    $('pacienteRequisicao').value = arguments[0];
    $('retiradoPor').value        = arguments[0];
  }

  oGridExames.clearAll( true );
  db_iframe_lab_requisicao.hide();
  pesquisaExames();
}

/**
 * Busca os exames da requisição selecionada, que estejam com situação '7 - Conferido'
 */
function pesquisaExames() {

  var oParametros             = new Object();
      oParametros.sExecucao   = 'examesRequisicao';
      oParametros.iRequisicao = $F('codigoRequisicao');

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoPesquisaExames;

  js_divCarregando( _M( MENSAGENS_ENTREGARESULTADONOVO + "buscando_exames" ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorna os exames da requisição selecionada
 */
function retornoPesquisaExames( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  oGridExames.clearAll( true );
  if( oRetorno.aExames.length == 0 ) {
    return;
  }

  oRetorno.aExames.each(function( oExame ) {

    var aLinha    = new Array();
        aLinha[0] = oExame.iCodigoItem;
        aLinha[1] = oExame.iExame;
        aLinha[2] = oExame.sExame.urlDecode();

    oGridExames.addRow( aLinha );
  });

  oGridExames.renderRows();
}

/**
 * Salva as informações referentes a entrega do resultado
 */
function salvarEntregaResultado() {

  if( !validaCampos() ) {
    return;
  }

  var aExames             = new Array();
  var aExamesSelecionados = oGridExames.getSelection("object");

  if( aExamesSelecionados.length == 0 ) {

    alert( _M( MENSAGENS_ENTREGARESULTADONOVO + "nenhum_exame_selecionado" ) );
    return;
  }

  aExamesSelecionados.each(function( oExame ) {
    aExames.push( oExame.aCells[0].getValue() );
  });

  var oParametros                = new Object();
      oParametros.sExecucao      = 'salvarEntregaResultado';
      oParametros.aExames        = aExames;
      oParametros.iCgs           = $F('cgsPaciente');
      oParametros.iTipoDocumento = $F('tipoDocumento');
      oParametros.sDocumento     = encodeURIComponent( tagString( $F('documento') ) );
      oParametros.sRetirado      = encodeURIComponent( tagString( $F('retiradoPor') ) );

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoSalvarEntregaResultado;

  js_divCarregando( _M( MENSAGENS_ENTREGARESULTADONOVO + "salvando_entrega_resultado" ), "msgBox" );
  new Ajax.Request( sRpc, oDadosRequisicao );
}

/**
 * Retorno do salvar as informações da entrega
 */
function retornoSalvarEntregaResultado( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  alert( oRetorno.sMensagem.urlDecode() );
  if( oRetorno.iStatus == 1 ) {
    pesquisaExames();
  }
}

/**
 * Valida os campos obrigatórios para salvar a entrega
 */
function validaCampos() {

  if( empty( $F('codigoRequisicao') ) ) {

    alert( _M( MENSAGENS_ENTREGARESULTADONOVO + "requisicao_nao_informada" ) );
    return false;
  }

  if( empty( $F('retiradoPor') ) ) {

    alert( _M( MENSAGENS_ENTREGARESULTADONOVO + "retirado_nao_informado" ) );
    return false;
  }

  if( empty( $F('tipoDocumento') ) ) {

    alert( _M( MENSAGENS_ENTREGARESULTADONOVO + "tipo_documento_nao_informado" ) );
    return false;
  }

  if( empty( $F('documento') ) ) {

    alert( _M( MENSAGENS_ENTREGARESULTADONOVO + "documento_nao_informado" ) );
    return false;
  }

  return true;
}

pesquisaTiposDocumento();
</script>