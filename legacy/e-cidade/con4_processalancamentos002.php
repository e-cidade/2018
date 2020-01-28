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

define('PASSIVO_SEM_SUPORTE_ORCAMENTARIO', 1);
define('ACORDOS',                          2);
define('SUPRIMENTO_DE_FUNDOS',             3);
define('MOVIMENTACAO_PATRIMONIAL',         4);
define('RECONHECIMENTO_CONTABIL',          5);

$oRotulo = new rotulocampo();
$oRotulo->label("c70_codlan");
$oRotulo->label("e60_numemp");
$oRotulo->label("e60_codemp");
$oRotulo->label("z01_nome");
$oRotulo->label("ac16_sequencial");
$oRotulo->label("ac16_resumoobjeto");
$oRotulo->label("c36_sequencial");

$aDocumentos          = array();
$aCodigoDocumentos    = array();
$iTipoReprocessamento = null;
$sTitulo              = null;
$db_opcao             = 1;

$oGet = db_utils::postMemory($_GET);

if ( !empty($oGet->iTipoReprocessamento) ) {
  $iTipoReprocessamento = $oGet->iTipoReprocessamento;
}

try {

  /**
   * Documentos por tipo de reprocessamento
   */
  switch ( $iTipoReprocessamento ) {

    case PASSIVO_SEM_SUPORTE_ORCAMENTARIO :

      $sTitulo = 'Passivo sem suporte orçamentário';
      $aCodigoDocumentos = array(80, 81);
    break;

    case ACORDOS :

      $sTitulo = 'Acordos';
      $aCodigoDocumentos = array(900, 901, 903, 904);
    break;

    case SUPRIMENTO_DE_FUNDOS :

      $sTitulo = 'Suprimento de fundos';
      $aCodigoDocumentos = array(412, 413, 414, 415, 90, 91, 92);
    break;

    case MOVIMENTACAO_PATRIMONIAL :

      $sTitulo = 'Movimentação patrimonial';
      $aCodigoDocumentos = array(700, 701, 703);
    break;

    case RECONHECIMENTO_CONTABIL:

      $sTitulo = 'Reconhecimento contábil';
      $aCodigoDocumentos = array(508, 509, 510, 511, 513, 514);
     break;

    default :
      throw new Exception("Tipo de reprocessamento inválido: $iTipoReprocessamento");
    break;
  }

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

} catch(Exception $oErro) {

  db_msgbox($oErro->getMessage());
  db_redireciona('con4_processalancamentos001.php');
  exit;
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

        <tr>
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
            <?php db_select('iDocumento', $aDocumentos, true, 1); ?>
          </td>
        </tr>

        <tr>
          <td>
            <strong id="lPeriodo">Período: </strong>
          </td>
          <td>
            <?php db_inputdata('dataInicial', null, null, null, true, 'text', 1); ?>
            <span style="margin:0 12px;">Até</span>
            <?php db_inputdata('dataFinal', null, null, null, true, 'text', 1); ?>
          </td>
        </tr>

        <!-- FINAL - FILTROS PADROES -->


        <?php if ( $iTipoReprocessamento == PASSIVO_SEM_SUPORTE_ORCAMENTARIO ) : ?>

          <tr>
            <td nowrap title="<?php echo $Tc36_sequencial?>">
              <?php db_ancora($Lc36_sequencial, "js_buscarPassivo(true);", 1, null, 'aPassivo'); ?>
            </td>
            <td>
             <?php db_input('c36_sequencial', 10, $Ic36_sequencial, true, 'text', 1, " onchange='js_buscarPassivo(false);'");?>
             <?php db_input('z01_nome', 30, $Iz01_nome, true, 'text', 3);?>
            </td>
          </tr>

        <?php endif; ?><!-- PASSIVO SEM SUPORTE ORCAMENTARIO -->


        <?php if ( $iTipoReprocessamento == ACORDOS ) : ?>

          <tr>
            <td nowrap title="<?php echo $Te60_numemp?>">
              <?php db_ancora($Le60_codemp, "js_buscarEmpenho(true);", 1, null, 'aEmpenho'); ?>
            </td>
            <td>
             <?php db_input('e60_codemp', 10, $Ie60_codemp, true, 'text', 4, " onchange='js_buscarEmpenho(false);' onKeyPress='return js_mascara(event);' ");?>
             <?php db_input('e60_numemp', 10, $Ie60_numemp, true, 'hidden', 3);?>
             <?php db_input('z01_nome', 30, $Iz01_nome, true, 'text', 3);?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?php echo $Tac16_sequencial?>">
              <?php db_ancora($Lac16_sequencial, "js_buscarAcordo(true);", 1, 'aAcordo'); ?>
            </td>
            <td>
             <?php db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 1, " onchange='js_buscarAcordo(false);'");?>
             <?php db_input('ac16_resumoobjeto', 30, $Iac16_resumoobjeto, true, 'text', 3);?>
            </td>
          </tr>

        <?php endif; ?><!-- ACORDOS -->


        <?php if ( $iTipoReprocessamento == SUPRIMENTO_DE_FUNDOS ) : ?>

          <tr>
            <td nowrap title="<?php echo $Te60_numemp?>">
              <?php db_ancora($Le60_codemp, "js_buscarEmpenho(true);", 1, null, 'aEmpenho'); ?>
            </td>
            <td>
             <?php db_input('e60_codemp', 10, $Ie60_codemp, true, 'text', 4, " onchange='js_buscarEmpenho(false);' onKeyPress='return js_mascara(event);' ");?>
             <?php db_input('e60_numemp', 10, $Ie60_numemp, true, 'hidden', 3);?>
             <?php db_input('z01_nome', 30, $Iz01_nome, true, 'text', 3);?>
            </td>
          </tr>

        <?php endif; ?><!-- FIM - SUPRIMENTOS DE FUNDOS -->

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

/**
 * Tipos de reprocessamento
 */
const PASSIVO_SEM_SUPORTE_ORCAMENTARIO = 1;
const ACORDOS                          = 2;
const SUPRIMENTO_DE_FUNDOS             = 3;
const MOVIMENTACAO_PATRIMONIAL         = 4;
const RECONHECIMENTO_CONTABIL          = 5;
const CAMINHO_MENSAGEM_TELA            = "financeiro.contabilidade.con4_processalancamentos002.";

/**
 * Tipo de reprocessamento selecionado na tela anterior
 *
 * @var string
 * @access public
 */
var iTipoReprocessamento = '<?php echo $iTipoReprocessamento; ?>';

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

  js_divCarregando('Processando...', 'msgBox');

  var oParametros = new Object();

  oParametros.sExec       = 'reprocessarLancamentos';
  oParametros.dtInicial   = js_formatar($('dataInicial').value, 'd');
  oParametros.dtFinal     = js_formatar($('dataFinal').value, 'd');
  oParametros.iLancamento = $('c70_codlan').value;
  oParametros.iDocumento  = $('iDocumento').value;
  oParametros.iPassivo    = "";
  oParametros.iAcordo     = "";
  oParametros.iEmpenho    = "";

  if ( iTipoReprocessamento == PASSIVO_SEM_SUPORTE_ORCAMENTARIO ) {
    oParametros.iPassivo = $('c36_sequencial').value;
  }

  if ( iTipoReprocessamento == ACORDOS ) {

    oParametros.iAcordo  = $('ac16_sequencial').value;
    oParametros.iEmpenho = $('e60_numemp').value;
  }

  if ( iTipoReprocessamento == SUPRIMENTO_DE_FUNDOS ) {
    oParametros.iEmpenho = $('e60_numemp').value;
  }

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

  /**
   * Erro no RPC
   */
  if ( oRetorno.iStatus > 1 ) {
    return alert(sMensagem);
  }

  alert(sMensagem);
  document.location.href = "con4_processalancamentos002.php?iTipoReprocessamento=" + iTipoReprocessamento;
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
   * Valida empenho
   * - quando for acordos ou suprimento de fundos
   * - retorna true caso for informado empenho
   */
  if  ( iTipoReprocessamento == ACORDOS || iTipoReprocessamento == SUPRIMENTO_DE_FUNDOS ) {

    if ( $('e60_numemp').value != '' ) {
      return true;
    }
  }

  /**
   * Valida acordos
   * - codigo do acordo
   */
  if  ( iTipoReprocessamento == ACORDOS ) {

    if ( $('ac16_sequencial').value == '' ) {

      alert(sMensagemPadrao + _M(CAMINHO_MENSAGEM_TELA + "mensagem_filstros_obrigatorios_acordo"));
      return false;
    }

    return true;
  }

  /**
   * Valida passivo sem suporte orcamentario
   * - codigo passivo
   */
  if  ( iTipoReprocessamento == PASSIVO_SEM_SUPORTE_ORCAMENTARIO ) {

    if ( $('c36_sequencial').value == '' ) {

      alert(sMensagemPadrao + _M(CAMINHO_MENSAGEM_TELA + "mensagem_filstros_obrigatorios_passivo_sem_suporte"));
      return false;
    }

    return true;
  }

  /**
   * Valida reprocessamento para suprimentos de fundos
   * - numero empenho
   */
  if  ( iTipoReprocessamento == SUPRIMENTO_DE_FUNDOS ) {

    if ( $('e60_numemp').value == '' ) {

      alert(sMensagemPadrao + _M(CAMINHO_MENSAGEM_TELA + "mensagem_filstros_obrigatorios_suprimento_fundos"));
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

/**
 * Busca empenho
 *
 * @param boolean $lMostra - mostra ou nao janela de pesquisa
 * @access public
 * @return boolean
 */
function js_buscarEmpenho(lMostra) {

  if ( lMostra ) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_empempenho',
                        'func_empempenho.php?funcao_js=parent.js_retornoBuscaEmpenhoAncora|e60_numemp|e60_codemp|z01_nome',
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
                        'func_empempenho.php?lPesquisaPorCodigoEmpenho=true' + sParametroAnoEmpenho +
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

  if ( lErro ) {

    $('e60_codemp').focus();
    $('e60_codemp').value = '';
    $('e60_numemp').value = '';
  }

  $('e60_numemp').value = iNumeroEmpenho;
  $('z01_nome').value   = sNome;
}

/**
 * Retorno da pesquisa de empenho pelo ancora(onclick)
 *
 * @param integer $iNumeroEmpenho
 * @param string $sNome
 * @access public
 * @return void
 */
function js_retornoBuscaEmpenhoAncora(iNumeroEmpenho, sCodigoEmpenho, sNome) {

  $('e60_codemp').value = sCodigoEmpenho;
  $('e60_numemp').value = iNumeroEmpenho;
  $('z01_nome').value   = sNome;
  db_iframe_empempenho.hide();
}

/**
 * Busca acordo
 * @param  boolean lMostra
 * @return boolean
 */
function js_buscarAcordo(lMostra) {

  if ( lMostra ) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_acordo',
                        'func_acordo.php?lLancamento=true&funcao_js=parent.js_retornoBuscaAcordoAncora|ac16_sequencial|ac16_resumoobjeto',
                        'Pesquisa',true);
    return true;
  }

  if ( $F('ac16_sequencial') != '' ) {

    js_OpenJanelaIframe('top.corpo','db_iframe_acordo',
                        'func_acordo.php?pesquisa_chave=' + $F('ac16_sequencial') + '&descricao=true&lLancamento=true&funcao_js=parent.js_retornoBuscaAcordoInput',
                        'Pesquisa', false);
  }
}

/**
 * Retorno busca por acordo pelo ancora(onclick)
 *
 * @param iAcordo $iAcordo
 * @param sResumo $sResumo
 * @access public
 * @return void
 */
function js_retornoBuscaAcordoAncora(iAcordo, sResumo) {

  $('ac16_sequencial').value   = iAcordo;
  $('ac16_resumoobjeto').value = sResumo;
  db_iframe_acordo.hide();
}

/**
 * Retorno da busca pro acordo pelo input(onchange)
 *
 * @param string $sResumo
 * @param boolean $lErro
 * @access public
 * @return void
 */
function js_retornoBuscaAcordoInput(iAcordo, sResumo, lErro) {

  if ( lErro ) {

    $('ac16_sequencial').focus();
    $('ac16_sequencial').value = '';
  }

  $('ac16_resumoobjeto').value = sResumo;
}

/**
 * busca passivo
 *
 * @param boolean $lMostra
 * @access public
 * @return void
 */
function js_buscarPassivo(lMostra) {

  if ( lMostra ) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_conlancaminscricaopassivo',
                        'func_conlancaminscricaopassivo.php?funcao_js=parent.js_retornoBuscaPassivoAncora|c37_inscricaopassivo|z01_nome',
                        'Pesquisa',true);
    return true;
  }

  if ( $F('c36_sequencial') != '' ) {

    js_OpenJanelaIframe('top.corpo', 'db_iframe_conlancaminscricaopassivo',
                        'func_conlancaminscricaopassivo.php?pesquisa_chave=' + $F('c36_sequencial') + '&descricao=true&funcao_js=parent.js_retornoBuscaPassivoInput&lRetornoCgm=true',
                        'Pesquisa', false);
  }

}

/**
 * Retorno da busca por passivo pelo ancora(onclick)
 *
 * @param integer $iPassivo
 * @param string $sNome
 * @access public
 * @return void
 */
function js_retornoBuscaPassivoAncora(iPassivo, sNome) {

  $('c36_sequencial').value = iPassivo;
  $('z01_nome').value       = sNome;
  db_iframe_conlancaminscricaopassivo.hide();
}

/**
 * Retorno da busca por passivo pelo input(onchange)
 *
 * @param string $sNome
 * @param boolean $lErro
 * @access public
 * @return void
 */
function js_retornoBuscaPassivoInput(sNome, lErro) {

  if ( lErro ) {

    $('c36_sequencial').focus();
    $('c36_sequencial').value = '';
  }

  $('z01_nome').value = sNome;
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