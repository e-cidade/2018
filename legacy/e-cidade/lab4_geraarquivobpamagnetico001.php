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
require_once("libs/db_stdlibwebseller.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/JSON.php");

db_menu ( db_getsession ( "DB_id_usuario" ),
          db_getsession ( "DB_modulo" ),
          db_getsession ( "DB_anousu" ),
          db_getsession ( "DB_instit" )
        );

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
  db_app::load("scripts.js, strings.js, prototype.js, DBDownload.widget.js, widgets/DBToggleList.widget.js");
  db_app::load("estilos.css, grid.style.css");
  ?>
  <style type="text/css">
    .DBToggleListBox .toggleListActionButons {
      width:  30px;
      height: 50%;
      margin: 8% 0 10px 30px;
      float:  left;
    }
  </style>
</head>
<body bgcolor=#CCCCCC >
  <div class="container">
    <form method="post" action="" class="form-container">
      <fieldset>
        <legend>Gerador de Arquivo BPA - Laboratório</legend>
        <table>
          <tr>
            <td class="field-size4">
              <label class="bold">Tipo de BPA:</label>
            </td>
            <td>
              <select id="tipoBPA">
                <option value="02">Individual</option>
                <option value="01">Consolidado</option>
              </select>
            </td>
          </tr>
        </table>
        <fieldset class="separator">
          <legend>Competência</legend>
          <table>
            <tr>
              <td style="display: none;">
                <input id="codigoCompetencia" type="text" value="" readonly="readOnly" />
              </td>
              <td id="ancoraCompetência" class="field-size4"></td>
              <td>
                <input id="mesCompetencia" type="text" value="" readonly="readOnly" maxlength="2" />
                /
                <input id="anoCompetencia" type="text" value="" readonly="readOnly" maxlength="4" />
              </td>
            </tr>
            <tr>
              <td class="field-size4">
                <label class="bold">Período de Fechamento:</label>
              </td>
              <td>
                <input id="dataInicioFechamento" type="text" value="" readonly="readOnly" />
                à
                <input id="dataFimFechamento" type="text" value="" readonly="readOnly" />
              </td>
            </tr>
            <tr>
              <td class="field-size4">
                <label class="bold">Tipo de Financiamento:</label>
              </td>
              <td>
                <input id="tipoFinanciamento" type="text" value="" readonly="readOnly" />
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset class="separator">
          <legend>Laboratórios</legend>
          <div id="ctnToggleLaboratorios"></div>
        </fieldset>
        <fieldset class="separator">
          <legend>Órgão Responsável</legend>
          <table>
            <tr>
              <td class="field-size4">
                <label class="bold">Nome:</label>
              </td>
              <td>
                <input id="nomeOrgaoResponsavel" type="text" value="" readonly="readOnly" />
              </td>
            </tr>
            <tr>
              <td class="field-size4">
                <label class="bold">Sigla:</label>
              </td>
              <td>
                <input id="siglaOrgaoResponsavel" type="text" value="" readonly="readOnly" />
              </td>
            </tr>
            <tr>
              <td class="field-size4">
                <label class="bold">CNPJ:</label>
              </td>
              <td>
                <input id="cnpjOrgaoResponsavel" type="text" value="" readonly="readOnly" />
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset class="separator">
          <legend>Secretaria da Saúde de destino do(s) BPA(s)</legend>
          <table>
            <tr>
              <td class="field-size4">
                <label class="bold">Sec. de Destino:</label>
              </td>
              <td>
                <input id="secretariaDestino" type="text" value="" readonly="readOnly" />
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset class="separator">
          <legend>Arquivo de Produção</legend>
          <table>
            <tr>
              <td class="field-size4">
                <label class="bold">Arquivo:</label>
              </td>
              <td>
                PA
                <input id="nomeArquivo" type="text" value="" />
                .
                <label id="extensaoArquivo"></label>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>
      <input id="gerarArquivo" type="button" value="Gerar Arquivo" />
      <input id="gerarRecibo"  type="button" value="Gerar Recibo"  disabled="disabled" />
    </form>
  </div>
</body>
</html>
<script>
var sRpcConfiguracao            = 'tfd4_bpamagnetico.RPC.php';
var sRpcCompetencia             = 'lab4_fechacompetencia.RPC.php';
var aMes                        = new Array( 'JAN', 'FEV', 'MAR', 'ABR', 'MAI', 'JUN', 'JUL', 'AGO', 'SET', 'OUT', 'NOV', 'DEZ' );
var oDadoRecibo                 = new Object();
var sNomeArquivo                = '';
const MENSAGENS_BPA_LABORATORIO = 'saude.laboratorio.lab4_geraarquivobpamagnetico001.';

/**
 * Cria a âncora da competência
 */
var sTextoAncora       = document.createTextNode( "Competência:" );
var oAncoraCompetencia = document.createElement( "a" );
    oAncoraCompetencia.appendChild( sTextoAncora );
    oAncoraCompetencia.title     = "Competência";
    oAncoraCompetencia.href      = "#";
    oAncoraCompetencia.className = "bold";

/**
 * Cria o ToggleList para selecionar os laboratórios
 * @type {DBToggleList}
 */
var oToggleLaboratorios = new DBToggleList( [{ sId: 'sLaboratorio', sLabel: 'Laboratórios' }] );
    oToggleLaboratorios.closeOrderButtons();
    oToggleLaboratorios.show( $('ctnToggleLaboratorios') );
$('ancoraCompetência').appendChild( oAncoraCompetencia );

oAncoraCompetencia.onclick = function(){
  pesquisaCompetencia();
};

$('gerarArquivo').onclick = function() {
  gerarArquivo();
}

$('gerarRecibo').onclick = function() {
  gerarRecibo();
}

/**
 * Busca as informações referentes ao orgão responsável para geração do BPA
 */
function buscaDadosPadrao() {

  var oParametros      = new Object();
      oParametros.exec = 'dadosFormGerarArquivo';

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoBuscaDadosPadrao;

  js_divCarregando( _M( MENSAGENS_BPA_LABORATORIO + 'buscando_dados_padrao' ), "msgBox" );
  new Ajax.Request( sRpcConfiguracao, oDadosRequisicao );
}

/**
 * Retorno da busca dos dados do orgão responsável
 */
function retornoBuscaDadosPadrao( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  $('nomeOrgaoResponsavel').value  = oRetorno.sInstituicao.urlDecode();
  $('siglaOrgaoResponsavel').value = oRetorno.sBpaSigla.urlDecode();
  $('cnpjOrgaoResponsavel').value  = oRetorno.iCnpj;
  $('secretariaDestino').value     = oRetorno.sBpaDestino.urlDecode();
  $('extensaoArquivo').innerHTML   = aMes[ oRetorno.iMesAtual - 1 ];

  buscaLaboratorios();
}

/**
 * Busca os laboratórios cadastrados
 */
function buscaLaboratorios() {

  var oParametros      = new Object();
      oParametros.exec = 'buscaLaboratorios';

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoBuscaLaboratorios;

  js_divCarregando( _M( MENSAGENS_BPA_LABORATORIO + 'buscando_laboratorios' ), "msgBox" );
  new Ajax.Request( sRpcCompetencia, oDadosRequisicao );
}

/**
 * Retorno dos laboratórios cadastrados
 */
function retornoBuscaLaboratorios( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  oToggleLaboratorios.clearAll();
  oRetorno.aLaboratorios.each(function( oLaboratorio ) {

    var oDadosLaboratorio              = new Object();
        oDadosLaboratorio.iLaboratorio = oLaboratorio.iCodigo;
        oDadosLaboratorio.sLaboratorio = oLaboratorio.sDescricao.urlDecode();

    oToggleLaboratorios.addSelect( oDadosLaboratorio );
  });

  oToggleLaboratorios.show( $('ctnToggleLaboratorios') );
}

/**
 * Busca as competências que tenham sido fechadas
 */
function pesquisaCompetencia() {

  var sFuncao      = 'func_lab_fechamento.php?';
  var sParametros  = 'funcao_js=parent.mostraCompetencia|la54_i_compmes|la54_i_compano|la54_i_codigo|la54_d_ini';
      sParametros += '|la54_d_fim|la54_i_financiamento|sd65_c_nome';

  js_OpenJanelaIframe( '', 'db_iframe_lab_fechamento', sFuncao + sParametros, 'Pesquisa Competência', true );
}

/**
 * Mostra os dados retornados referente a competência
 */
function mostraCompetencia() {

  $('codigoCompetencia').value    = arguments[2];
  $('mesCompetencia').value       = arguments[0];
  $('anoCompetencia').value       = arguments[1];
  $('dataInicioFechamento').value = js_formatar( arguments[3], 'd' );
  $('dataFimFechamento').value    = js_formatar( arguments[4], 'd' );
  $('tipoFinanciamento').value    = arguments[6];
  $('extensaoArquivo').innerHTML  = aMes[ arguments[0] - 1 ];

  db_iframe_lab_fechamento.hide();
}

/**
 * Gera o arquivo, caso os campos tenham sido preenchidos
 */
function gerarArquivo() {

  if( !validaGeracao() ) {
    return;
  }

  sNomeArquivo                  = 'PA' + $F('nomeArquivo') + '.' + $('extensaoArquivo').innerHTML;
  var oParametros               = new Object();
      oParametros.exec          = 'gerarArquivo';
      oParametros.sTipo         = $F('tipoBPA');
      oParametros.iCompetencia  = $F('codigoCompetencia');
      oParametros.sNomeArquivo  = sNomeArquivo;
      oParametros.aLaboratorios = new Array();

  oToggleLaboratorios.getSelected().each(function( oLaboratorio ) {
    oParametros.aLaboratorios.push( oLaboratorio.iLaboratorio );
  });

  var oDadosRequisicao            = new Object();
      oDadosRequisicao.method     = 'post';
      oDadosRequisicao.parameters = 'json=' + Object.toJSON( oParametros );
      oDadosRequisicao.onComplete = retornoGerarArquivo;

  js_divCarregando( _M( MENSAGENS_BPA_LABORATORIO + 'gerando_arquivo' ), "msgBox" );
  new Ajax.Request( sRpcCompetencia, oDadosRequisicao );
}

/**
 * Retorno da geração do arquivo
 */
function retornoGerarArquivo( oResponse ) {

  js_removeObj( "msgBox" );
  var oRetorno = JSON.parse( oResponse.responseText );

  oDadoRecibo = oRetorno.oDadosBPA;
  if( !oRetorno.lTemInconsistencia && oRetorno.status == 1 ) {

    $('gerarRecibo').disabled = false;

    alert( _M( MENSAGENS_BPA_LABORATORIO + "arquivo_gerado" ) );

    var oArquivoBPA = new DBDownload();
        oArquivoBPA.addFile( oRetorno.sNomeArquivo.urlDecode(), "Download arquivo TXT (BPA Laboratório)" );
        oArquivoBPA.show();
  } else {

    if( oRetorno.status == 2 ) {

      alert( oRetorno.message.urlDecode() );
      return;
    }

    alert( _M( MENSAGENS_BPA_LABORATORIO + "erro_gerar_arquivo" ) );

    sUrl = "sau2_bpainconsistencia002.php";
    jan  = window.open(
                        sUrl,
                        '',
                        'width='+(screen.availWidth - 5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0'
                      );
    jan.moveTo(0, 0);
  }
}

/**
 * Valida se os campos necessário para geração do arquivo foram preenchidos
 */
function validaGeracao() {

  if( empty( $F('codigoCompetencia') ) ) {

    alert( _M( MENSAGENS_BPA_LABORATORIO + 'competencia_nao_informada' ) );
    return false;
  }

  if( oToggleLaboratorios.getSelected().length == 0 ) {

    alert( _M( MENSAGENS_BPA_LABORATORIO + 'nenhum_laboratorio_selecionado' ) );
    return false;
  }

  if( empty( $F('nomeArquivo') ) ) {

    alert( _M( MENSAGENS_BPA_LABORATORIO + 'nome_arquivo_nao_informado' ) );
    return false;
  }

  return true;
}

/**
 * Gera o recibo após geração do arquivo
 */
function gerarRecibo() {

  var sUrl  = 'sau2_recibobpa001.php?';
      sUrl += 'linhas='+oDadoRecibo.iLinhas;
      sUrl += '&sd97_i_compmes='+$F('mesCompetencia');
      sUrl += '&iLab=' + +oDadoRecibo.iLinhas;
      sUrl += '&sNomeorg='+$F('nomeOrgaoResponsavel');
      sUrl += '&sSigla='+$F('siglaOrgaoResponsavel');
      sUrl += '&iOrgao=1';
      sUrl += '&sNomearq='+sNomeArquivo;
      sUrl += '&iCnpj='+$F('cnpjOrgaoResponsavel');
      sUrl += '&sDestino='+$F('secretariaDestino');
      sUrl += '&iCntrl='+oDadoRecibo.nControle;
      sUrl += '&sd97_i_compano='+$F('anoCompetencia');

  jan = window.open(sUrl, '', 'width='+(screen.availWidth-5)+',height='+(screen.availHeight)+',scrollbars=1,location=0');
  jan.moveTo(0,0);

  limpaCampos();
}

/**
 * Limpa os campos após o arquivo e recibo terem sido gerados
 */
function limpaCampos() {

  $('tipoBPA').value              = "02";
  $('codigoCompetencia').value    = "";
  $('mesCompetencia').value       = "";
  $('anoCompetencia').value       = "";
  $('dataInicioFechamento').value = "";
  $('dataFimFechamento').value    = "";
  $('tipoFinanciamento').value    = "";
  $('nomeArquivo').value          = "";

  $('gerarRecibo').disabled = true;
  buscaLaboratorios();
}

/**
 * Seta as classes com os estilos dos campos
 * @type {string}
 */
$('tipoBPA').style.width             = '350px';
$('codigoCompetencia').className     = 'field-size1 readonly';
$('mesCompetencia').className        = 'field-size1 readonly';
$('anoCompetencia').className        = 'field-size1 readonly';
$('dataInicioFechamento').className  = 'field-size2 readonly';
$('dataFimFechamento').className     = 'field-size2 readonly';
$('tipoFinanciamento').className     = 'field-size8 readonly';
$('nomeOrgaoResponsavel').className  = 'field-size8 readonly';
$('siglaOrgaoResponsavel').className = 'field-size8 readonly';
$('cnpjOrgaoResponsavel').className  = 'field-size8 readonly';
$('secretariaDestino').className     = 'field-size8 readonly';
$('nomeArquivo').className           = 'field-size4';

buscaDadosPadrao();
</script>