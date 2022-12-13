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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");

$oRotulo = new rotulocampo();
$oRotulo->label("c70_codlan");
$oRotulo->label("ac16_sequencial");
$oRotulo->label("c36_sequencial");
$oRotulo->label("k17_codigo");

$aDocumentos          = array();
$aCodigoDocumentos    = array();
$sTitulo              = null;
$db_opcao             = 1;
$oGet                 = db_utils::postMemory($_GET);
$sTitulo              = 'Operações Extra-Orçamentárias';
$aCodigoDocumentos    = array( 120, 130, 140, 150, 151, 160, 161, 121, 131, 141, 152, 153, 162, 163);

$sDocumentos = implode(', ', $aCodigoDocumentos);

$oDaoConhistdoc       = db_utils::getDao('conhistdoc');
$sCamposDocumentos    = "c53_coddoc as codigo, c53_coddoc || ' - ' || c53_descr as descricao";
$sWhereDocumentos     = "c53_coddoc in($sDocumentos)";
$sOrdenacaoDocumentos = "c53_coddoc, c53_descr";
$sSqlDocumentos       = $oDaoConhistdoc->sql_query_file(null, $sCamposDocumentos, $sOrdenacaoDocumentos, $sWhereDocumentos);
$rsDocumentos         = $oDaoConhistdoc->sql_record($sSqlDocumentos);

if ( $oDaoConhistdoc->numrows > 0 ) {

  $aDadosDocumentos = db_utils::getCollectionByRecord($rsDocumentos);

  foreach ( $aDadosDocumentos as $oDadosDocumento ) {
    $aDocumentos[ $oDadosDocumento->codigo ] = $oDadosDocumento->descricao;
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

    <fieldset class="container" style="width:450px;">

      <legend id="lgdReprocessarLancamento"><?php echo $sTitulo; ?></legend>

      <table class="form-container">

        <!-- INICIO - FILTROS PADROES -->
<!--
        <tr>
          <td id="lCodigoLancamento" title="<?php echo $Tc70_codlan; ?>">
            <?php echo $Lc70_codlan; ?>
          </td>
          <td>
            <?php db_input('c70_codlan', 10, $Ic70_codlan, true, 'text', $db_opcao); ?>
          </td>
        </tr>
-->

        <tr>
          <td>
            <strong id="lCodigoLancamento">Documento: </strong>
          </td>
          <td>
            <?php db_select('iDocumento', $aDocumentos, true, 1, ""); ?>
          </td>
        </tr>

         <tr>
          <td colspan="1" nowrap title="<?=@$Tk17_codigo?>" align='left'>
            <? db_ancora(@$Lk17_codigo,"js_pesquisak17_codigo(true);",$db_opcao);  ?>
          </td>
          <td style="margin:0 12px;">
            <? db_input('k17_codigo',10,$Ik17_codigo,true,'text',$db_opcao,"onkeyup='js_ValidaCampos(this,1,\"Código Slip\",\"f\",\"f\",event)'; onchange='js_pesquisak17_codigo(false);'")  ?>
            <? db_ancora("<strong>Até:</strong>","js_pesquisak17_codigo02(true);",$db_opcao," margin-left:43px;");  ?>
            <? db_input('k17_codigo',10,$Ik17_codigo,true,'text',$db_opcao," onkeyup='js_ValidaCampos(this,1,\"Código Slip\",\"f\",\"f\",event)'; onchange='js_pesquisak17_codigo02(false);'","k17_codigo02",null," margin-left:5px;")?>
          </td>
        </tr>

        <tr>
          <td>
            <strong id="lPeriodo">Período: </strong>
          </td>
          <td>
            <?php db_inputdata('dataInicial', null, null, null, true, 'text', 1); ?>
            <span style="margin:0 8px;"><strong>Até:</strong></span>
            <?php db_inputdata('dataFinal', null, null, null, true, 'text', 1); ?>
          </td>
        </tr>

      </table>
    </fieldset>

    <br />
    <input type="button" value="Processar" onClick="js_processar();" />
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

  $("e60_codemp") .value = '';
  $("e60_numemp") .value = '';
  $("z01_nome")   .value = '';
  $("e69_codnota").value = '';

  if ( iDocumento == 210 || iDocumento == 211) {

    $('filtroEmpenho').style.display = 'table-row';
    $('filtroNota').style.display = 'table-row';
  }
}

/**
 * Tipos de reprocessamento
 */
const MOVIMENTACAO_PATRIMONIAL = 4;
const CAMINHO_MENSAGEM_TELA    = "financeiro.contabilidade.con4_operacoesextraorcamentarias001.";

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
function js_processar() {

  /**
   * Valida formulario
   * - obriga ususario preencher pelo menos um filtro obrigatorio
   */
  if ( !js_validarFormulario() ) {
    return false;
  }

  var iSlipInicial = $F("k17_codigo");
  var iSlipFinal   = $F("k17_codigo02");

  js_divCarregando('Processando...', 'msgBox');

  var oParametros = new Object();

  oParametros.sExec         = 'operacoesExtraOrcamentaria';
  oParametros.dtInicial     = js_formatar($('dataInicial').value, 'd');
  oParametros.dtFinal       = js_formatar($('dataFinal').value, 'd');
  oParametros.iDocumento    = $('iDocumento').value;
  oParametros.iSlipInicial  = iSlipInicial;
  oParametros.iSlipFinal    = iSlipFinal;

  var oAjax = new Ajax.Request(sRPC, {
                               method     : "post",
                               parameters : 'json='+Object.toJSON(oParametros),
                               onComplete : js_retornoProcessar
                              });
}

function js_retornoProcessar(oAjax) {

  js_removeObj('msgBox');
  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();

  alert(sMensagem);
  //document.location.href = "con4_operacoesextraorcamentarias001.php";
}

/**
 * Valida filtros obrigatorios do formulario
 *
 * @access public
 * @return boolean
 */
function js_validarFormulario() {

  var sMensagemPadrao = _M(CAMINHO_MENSAGEM_TELA + "mensagem_padrao_filtros_obrigatorios");

  /**
   * Codigo do lancamento
   */
  //if ( $('c70_codlan').value != '' ) {
    //return true;
  //}

   if ( $('k17_codigo').value !='' || $('k17_codigo02').value !=''){
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



//-----------------------------------------------------------
//---slip 01 ---


function js_pesquisak17_codigo(mostra){

  if( mostra == true){

    js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slipOperacaoExtraOrcamentaria.php?iDocumento=' + $F('iDocumento') + '&funcao_js=parent.js_mostraslip1|k17_codigo','Pesquisa',true);
  } else {

    var slip01 = $("k17_codigo").value;

    if (slip01 != "" ){
       js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slipOperacaoExtraOrcamentaria.php?iDocumento=' + $F('iDocumento') + '&pesquisa_chave=' + slip01 + '&funcao_js=parent.js_mostraslip','Pesquisa',false);
    }else{
        $("k17_codigo").value='';
    }
  }
}

function js_mostraslip(chave, erro) {

  if ( erro == true) {

    $("k17_codigo").focus();
    $("k17_codigo").value = '';
  }
}

function js_mostraslip1(chave1, chave2 ) {

  $("k17_codigo").value = chave1;
  db_iframe_slip.hide();
}
//-----------------------------------------------------------
//---slip 02 ---
function js_pesquisak17_codigo02(mostra){

  if (mostra == true) {
    js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slipOperacaoExtraOrcamentaria.php?iDocumento=' + $F('iDocumento') + '&funcao_js=parent.js_mostraslip12|k17_codigo','Pesquisa',true);
  } else {

    slip01 = $("k17_codigo02").value;
    if(slip01 != ""){
       js_OpenJanelaIframe('top.corpo','db_iframe_slip','func_slipOperacaoExtraOrcamentaria.php?iDocumento=' + $F('iDocumento') + '&pesquisa_chave='+slip01+'&funcao_js=parent.js_mostraslip2','Pesquisa',false);
    } else {
        $("k17_codigo02").value = '';
    }
  }
}

function js_mostraslip2(chave,erro){

  if( erro == true) {

    $("k17_codigo02").focus();
    $("k17_codigo02").value = '';
  }
}

function js_mostraslip12(chave1, chave2) {

  $("k17_codigo02").value = chave1;
  db_iframe_slip.hide();
}
//--------------------------------------------------------


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