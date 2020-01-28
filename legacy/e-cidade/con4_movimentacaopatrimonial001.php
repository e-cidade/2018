<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

define('MOVIMENTACAO_PATRIMONIAL', 4);

$oRotulo = new rotulocampo();
$oRotulo->label("c70_codlan");
$oRotulo->label("e60_numemp");
$oRotulo->label("e60_codemp");
$oRotulo->label("z01_nome");
$oRotulo->label("ac16_sequencial");
$oRotulo->label("ac16_resumoobjeto");
$oRotulo->label("c36_sequencial");
$oRotulo->label("e69_codnota");
$oRotulo->label("e69_numero");


$aDocumentos          = array();
$aCodigoDocumentos    = array();
//$iTipoReprocessamento = null;
$sTitulo              = null;
$db_opcao             = 1;
$oGet                 = db_utils::postMemory($_GET);
$sTitulo              = 'Movimentação Patrimonial';
$aCodigoDocumentos    = array(
    204, 205, 206, 207, 208, 210, 211, 212, 213,
    400, 401, 402, 403, 404,
    700, 701, 702, 703, 704
  );

$sDocumentos = implode(', ', $aCodigoDocumentos);

$oDaoConhistdoc       = db_utils::getDao('conhistdoc');
$sCamposDocumentos    = "c53_coddoc as codigo, c53_coddoc || ' - ' || c53_descr as descricao";
$sWhereDocumentos     = "c53_coddoc in($sDocumentos)";
$sOrdenacaoDocumentos = "c53_coddoc, c53_descr";
$sSqlDocumentos       = $oDaoConhistdoc->sql_query_file(null, $sCamposDocumentos, $sOrdenacaoDocumentos, $sWhereDocumentos);
$rsDocumentos         = $oDaoConhistdoc->sql_record($sSqlDocumentos);

if ( $oDaoConhistdoc->numrows > 0 ) {

  $aDocumentos = array();
  for ($iRowDocumento = 0; $iRowDocumento < $oDaoConhistdoc->numrows; $iRowDocumento++) {

    $oStdDadoDocumento = db_utils::fieldsMemory($rsDocumentos, $iRowDocumento);
    $aDocumentos[$oStdDadoDocumento->codigo] = $oStdDadoDocumento->descricao;
  }
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
  <meta http-equiv="expires" content="0" />
  <link href="estilos.css" rel="stylesheet" type="text/css" />
  <?php db_app::load('prototype.js, scripts.js, strings.js'); ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <center>

    <fieldset class="container" style="width:600px;">

      <legend id="lgdReprocessarLancamento"><?php echo $sTitulo; ?></legend>

      <table class="form-container">

        <!-- INICIO - FILTROS PADROES -->

        <tr id="filtroCodigoLancamento">
          <td id="lCodigoLancamento" title="<?php echo $Tc70_codlan; ?>">
            <?php echo $Lc70_codlan; ?>
          </td>
          <td>
            <?php db_input('c70_codlan', 10, $Ic70_codlan, true, 'text', $db_opcao); ?>
          </td>
        </tr>

        <tr>
          <td>
            <strong id="lCodigoLancamento">Documento: </strong>
          </td>
          <td>
            <?php db_select('iDocumento', $aDocumentos, true, 1, "onchange='js_mostraCampos();' "); ?>
          </td>
        </tr>

        <tr>
          <td>
            <strong id="lPeriodo">Período: </strong>
          </td>
          <td>
            <?php db_inputdata('dataInicial', null, null, null, true, 'text', 1); ?>
            <span style="margin:0 12px;"><strong>Até:<strong></span>
            <?php db_inputdata('dataFinal', null, null, null, true, 'text', 1); ?>
          </td>
        </tr>

          <tr id='filtroEmpenho' style="display: none;" >
            <td nowrap title="<?php echo $Te60_numemp?>">
              <?php db_ancora($Le60_codemp, "js_buscarEmpenho(true);", 1, null, 'aEmpenho'); ?>
            </td>
            <td>
             <?php db_input('e60_codemp', 10, $Ie60_codemp, true, 'text', 4, " onchange='js_buscarEmpenho(false);' onKeyPress='return js_mascara(event);' ");?>
             <?php db_input('e60_numemp', 10, $Ie60_numemp, true, 'hidden', 3);?>
             <?php db_input('z01_nome', 50, $Iz01_nome, true, 'text', 3);?>
            </td>
          </tr>

          <tr id='filtroNota' style="display: none;">
            <td nowrap title="Nota Fiscal">
              <strong><?php db_ancora("Sequencial da Nota:", "js_buscarNota(true);", 1, 'aAcordo'); ?></strong>
            </td>
            <td>
             <?php db_input('e69_codnota', 10, $Ie69_codnota, true, 'text', 1, " onchange='js_buscarNota(false);'");?>
            </td>
          </tr>

      </table>
    </fieldset>

    <br />
    <input type="button" value="Processar" onClick="js_processar();" id='btnProcessar'/>
    <input type="button" value="Voltar" onClick="js_voltar();" />

  </center>

  <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">


function js_mostraCampos(){

  var iDocumento = $F('iDocumento');

  $('filtroEmpenho').style.display = 'none';
  $('filtroNota').style.display    = 'none';
  $('filtroCodigoLancamento').style.display = 'table-row';

  $("e60_codemp") .value = '';
  $("e60_numemp") .value = '';
  $("z01_nome")   .value = '';
  $("e69_codnota").value = '';


  switch (iDocumento) {

    case "204" :
    case "205" :
    case "206" :
    case "207" :
    case "208" :
    case "209" :
    case "210" :
    case "211" :
    case "212" :
    case "213" :

      $('filtroEmpenho').style.display = 'table-row';
      $('filtroNota').style.display = 'table-row';

    break;

  }

  if (iDocumento == '210' || iDocumento == '211') {
    $('filtroCodigoLancamento').style.display = 'none';
  }

}

js_mostraCampos();

/**
 * Tipos de reprocessamento
 */
const MOVIMENTACAO_PATRIMONIAL         = 4;
const CAMINHO_MENSAGEM_TELA            = "financeiro.contabilidade.con4_movimentacaopatrimonial001.";

/**
 * Tipo de reprocessamento selecionado na tela anterior
 *
 * @var string
 * @access public
 */
//var iTipoReprocessamento = '4';

var sRPC = 'con4_reprocessalancamentos001.RPC.php';

/**
 * Processar reprocessamento
 *
 * @access public
 * @return boolean
 */

$('e60_codemp').observe('focus', function(){

  $("btnProcessar").disabled = true;
});
$('e60_codemp').observe('blur', function(){

  $("btnProcessar").disabled = false;
});

function js_processar() {

  /**
   * Valida formulario
   * - obriga ususario preencher pelo menos um filtro obrigatorio
   */
  if ( !js_validarFormulario() ) {
    return false;
  }

  js_divCarregando('Processando...', 'msgBox');

  var oParametros = new Object();
  var sExecutar = 'reprocessarLancamentosMovimentacaoPatrimonial';

  oParametros.sExec       = sExecutar;
  oParametros.dtInicial   = js_formatar($('dataInicial').value, 'd');
  oParametros.dtFinal     = js_formatar($('dataFinal').value, 'd');
  oParametros.iLancamento = $('c70_codlan').value;
  oParametros.iDocumento  = $('iDocumento').value;
  oParametros.iEmpenho    = $F('e60_numemp');
  oParametros.iNota       = $F('e69_codnota');

  if ($F('iDocumento') == '210' || $F('iDocumento') == '211') {

    oParametros.sExec = 'recriarLancamentosMovimentacaoPatrimonial';
    delete oParametros.iLancamento;
  }

  new Ajax.Request(sRPC, {
               method     : "post",
               asynchronous : false,
               parameters : 'json='+Object.toJSON(oParametros),
               onComplete : js_retornoProcessar
              });
}

function js_retornoProcessar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();

  alert(sMensagem);

  if ($('iDocumento').value == 208 && oRetorno.iStatus == 2 && oRetorno.iLote != null) {

    if ( confirm(_M(CAMINHO_MENSAGEM_TELA + "relatorio_lote")) ) {
      jan = window.open('pat2_bensporlote002.php?lote='+ oRetorno.iLote, '', 'width=' + (screen.availWidth-5) + ',height=' + (screen.availHeight-40) + ',scrollbars=1,location=0' );
      jan.moveTo(0,0);
    }
  } else {
    document.location.href = "con4_movimentacaopatrimonial001.php";
  }
}

/**
 * Valida filtros obrigatorios do formulario
 *
 * @access public
 * @return boolean
 */
function js_validarFormulario() {

  var sMensagemPadrao = _M(CAMINHO_MENSAGEM_TELA + "mensagem_padrao_filtros_obrigatorios");
  var iEmpenho        = $F("e60_numemp") ;
  var iNota           = $F("e69_codnota");

  /**
   * Codigo do lancamento
   */
  if ( $('c70_codlan').value != '' ) {
    return true;
  }

  /**
   * Periodo
   * - datas iguais, retorna true
   * - data inicial maior que final, retorna false
   */
  if ( $('dataInicial').value != '' && $('dataFinal').value != '' ) {

    var sDataInicial    = js_formatar($('dataInicial').value, 'd');
    var sDataFinal      = js_formatar($('dataFinal').value, 'd');
    var mDiferencaDatas = js_diferenca_datas(sDataInicial, sDataFinal, 3);

    /**
     * Datas iguais
     */
    if ( mDiferencaDatas == 'i' ) {
      return true;
    }
    /**
     * Data inicial maior que a final
     */
    if ( mDiferencaDatas ) {

      alert(_M(CAMINHO_MENSAGEM_TELA + "data_inicial_maior_data_final"));
      return false;
    }

    return true;
  }

  /**
   * Se empenho ou nota forem preenchidas
   */
   if ( iEmpenho != '' || iNota != ''){
     return true;
   }

  /**
   * Nenhum filtro padrao informado
   */
  alert(sMensagemPadrao);
  return false;
}

/**
 * Voltar para pagina dos tipos de reprocessamento
 *
 * @access public
 * @return void
 */
function js_voltar() {

  document.location.href = 'con4_processalancamentos001.php';
}

/**
 * Busca empenho
 *
 * @param boolean $lMostra - mostra ou nao janela de pesquisa
 * @access public
 * @return boolean
 */
function js_buscarEmpenho(lMostra) {

  var iDocumento = $F('iDocumento');

  if ( lMostra ) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_empempenho',
                        'func_empempenhoMovimentacaoPatrimonial.php?iDocumento='+ iDocumento +'&funcao_js=parent.js_retornoBuscaEmpenhoAncora|e60_numemp|e60_codemp|z01_nome',
                        'Pesquisa',true);
    return true;
  }

  if ( $F('e60_codemp') != '' ) {

    var aCodigoEmpenho = $F('e60_codemp').split('/');
    var iCodigoEmpenho = aCodigoEmpenho[0];
    var sParametroAnoEmpenho = null;

    if ( aCodigoEmpenho.length > 1 ) {
      sParametroAnoEmpenho = '&iAnoEmpenho=' + aCodigoEmpenho[1];
    }

    js_OpenJanelaIframe('top.corpo','db_iframe_empempenho',
                        'func_empempenhoMovimentacaoPatrimonial.php?iDocumento=' + iDocumento + '&lPesquisaPorCodigoEmpenho=true' + sParametroAnoEmpenho +
                        '&pesquisa_chave=' + iCodigoEmpenho +
                        '&funcao_js=parent.js_retornoBuscaEmpenhoInput',
                        'Pesquisa', false);
  }
}

/**
 * Retorno da pesquisa de empenho pelo campo input(onchange)
 *
 * @param string $sNome
 * @param boolean $lErro
 * @access public
 * @return void
 */
function js_retornoBuscaEmpenhoInput(iNumeroEmpenho, sNome, lErro) {

  $('e60_numemp') .value = iNumeroEmpenho;
  $('z01_nome')   .value = sNome;
  $('e69_codnota').value = '';

  if ( lErro ) {

    $('e60_codemp').focus();
    $('e60_codemp').value = '';
    $('z01_nome')  .value = iNumeroEmpenho;
    $('e60_numemp').value = '';
  }

}

/**
 * caso o usuario limpe o numero do empenho para buscar qualquer nota,
   deve limpar o numemp que é hidden, pois senao ele trará somente notas do empenho selecionado antes
 */
$('e60_codemp'). observe("change", function(){

  if ($F('e60_codemp') == '' ) {

    $('e60_numemp').value  = '';
    $('z01_nome').value    = "";
  }

});

/**
 * Retorno da pesquisa de empenho pelo ancora(onclick)
 *
 * @param integer $iNumeroEmpenho
 * @param string $sNome
 * @access public
 * @return void
 */
function js_retornoBuscaEmpenhoAncora(iNumeroEmpenho, sCodigoEmpenho, sNome) {

  $('e60_codemp').value  = sCodigoEmpenho;
  $('e60_numemp').value  = iNumeroEmpenho;
  $('z01_nome').value    = sNome;
  $('e69_codnota').value = '';
  db_iframe_empempenho.hide();
}


/**
 * Busca acordo
 * @param  boolean lMostra
 * @return boolean
 */
function js_buscarNota(lMostra) {

  if ( lMostra ) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_nota',
                        'func_empnotaMovimentacaoPatrimonial.php?iDocumento='+$F('iDocumento')+'&chave_e60_numemp=' + $F('e60_numemp') + '&funcao_js=parent.js_retornoBuscaNotaAncora|e69_codnota',
                        'Pesquisa',true);
    return true;
  }

  if ( $F('e69_codnota') != '' ) {

    js_OpenJanelaIframe('top.corpo','db_iframe_nota',
                        'func_empnotaMovimentacaoPatrimonial.php?iDocumento' + $F('iDocumento') + '&chave_e60_numemp=' + $F('e60_numemp') + 'pesquisa_chave=' + $F('e69_codnota') + '&descricao=true&lLancamento=true&funcao_js=parent.js_retornoBuscaNotaInput',
                        'Pesquisa', false);
  }
}

function js_retornoBuscaNotaAncora(iNota) {

  $('e69_codnota').value   = iNota;
  db_iframe_nota.hide();
}
function js_retornoBuscaNotaInput(iNota, lErro) {

  if ( lErro ) {

    $('e69_codnota').focus();
    $('e69_codnota').value = '';
  }
}


/**
 * Mascara para o campo codigo do empenho
 *
 * @param Object $evt
 * @access public
 * @return boolean
 */
function js_mascara(evt) {

  var evt = (evt) ? evt : (window.event) ? window.event : "";

  if( (evt.charCode > 46 && evt.charCode < 58) || evt.charCode == 0 ) {
    return true;
  }

  return false;
}
</script>
