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
require_once("libs/JSON.php");

$aTiposAlvara           = array();
$aAlvarasIndeterminados = array();
$oIssTipoAlvara         = db_utils::getDao('isstipoalvara');
$sWhereTipoAlvara       = "q98_instit = " . db_getsession('DB_instit') . " AND q98_permitetransformacao = 't'";
$sSqlTipoAlvara         = $oIssTipoAlvara->sql_query_file(null, 'q98_sequencial, q98_descricao, q98_tipovalidade', 'q98_sequencial', $sWhereTipoAlvara);
$rsTipoAlvara           = $oIssTipoAlvara->sql_record($sSqlTipoAlvara);

if ( $oIssTipoAlvara->numrows > 0 ) {

  $aTiposAlvara[0] = 'SELECIONE...';
  foreach ( db_utils::getCollectionByRecord($rsTipoAlvara) as $oTipoAlvara ) {

    /**
     * Verifica se o alvara pe do tipo indeterminado
     */
    if ($oTipoAlvara->q98_tipovalidade == 3) {
      $aAlvarasIndeterminados[] = $oTipoAlvara->q98_sequencial;
    }

    $aTiposAlvara[ $oTipoAlvara->q98_sequencial ] = $oTipoAlvara->q98_descricao;
  }
}

$oJSON                  = new services_json();
$sAlvarasIndeterminados = $oJSON->encode($aAlvarasIndeterminados);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
  db_app::load("scripts.js, prototype.js, arrays.js, strings.js, estilos.css");
?>
<style>
select {
  min-width:124px;
}
</style>
</head>
<body class="body-default">
  <div class="container">
    <fieldset style="width:300px;">
      <legend>Filtros:</legend>
      <table width="100%">
        <tr>
          <td nowrap title="Data de validade inicial" >
             <strong>Tipo de alvará origem:</strong>
          </td>
          <td>
            <?php db_select("tipoAlvara", $aTiposAlvara, true, 1, 'onChange="js_trocaTipo()"'); ?>
          </td>
        </tr>

        <tr id="dataInicial">
          <td nowrap title="Data de validade inicial" >
             <strong>Data validade inicial:</strong>
          </td>
          <td>
            <?php db_inputdata('dataValidadeInicial', null, null, null, true, 'text', 1); ?>
          </td>
        </tr>

        <tr id="dataFinal">
          <td nowrap title="Data de validade final" >
             <strong>Data validade final:</strong>
          </td>
          <td>
            <?php db_inputdata('dataValidadeFinal', null, null, null, true, 'text', 1); ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input type="button" value="Filtrar" onclick="js_processar();" />
  </div>
<?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>
</body>
</html>
<?php
  /** Extensao : Inicio [BloqueioManutencaoInscricaoSistemaExterno] */
  /** Extensao : Fim [BloqueioManutencaoInscricaoSistemaExterno] */
?>
<script type="text/javascript">

/**
 * Variaveia para processamento dos dados
 */
var iTipoAlvara;
var sDataValidadeInicial;
var sDataValidadeFinal;
var sNomeTipoAlvara;

/**
 * Variavel utilizada para ferificar se o Alvara é do tipo indeterminado.
 */
var lAlvaraInterderminado = false;

/**
 * Verifica se o Tipo do alvará tem o tipo de validade indeterminada,
 * e esconde a data final no filtro
 *
 * @access public
 * @return void.
 */
function js_trocaTipo() {

  var aAlvarasIndeterminados = <?=$sAlvarasIndeterminados?>;

  if ( aAlvarasIndeterminados.in_array($F('tipoAlvara')) ) {

    lAlvaraInterderminado = true;
    $('dataFinal').hide();
    $('dataInicial').hide();
    $('dataValidadeInicial').value = '';
    $('dataValidadeFinal').value = '';
  } else{

    lAlvaraInterderminado = false;
    $('dataFinal').show();
    $('dataInicial').show();
  }
}

/**
 * Processar
 * Redirecionad para o 002 passando por get os filtros
 *
 * @access public
 * @return void
 */
function js_processar() {

  /**
   * Valida se foir informado o tipo de alvara de origem
   */
  if( $('tipoAlvara').value == 0 ) {

    alert('Por favor, informe o Tipo de alvará origem.');
    return false;
  }

  /**
   * Valida se foi informado a Data de Validade Inicial
   */
  if ( $('dataValidadeInicial').value == '' && !lAlvaraInterderminado) {

    alert('Por favor, informe a Data validade inicial.');
    return false;
  }

  /**
   * Valida se foi informado a Data de Validade Final
   */
  if ( $('dataValidadeFinal').value == '' && !lAlvaraInterderminado) {

    alert('Por favor, informe a Data validade final.');
    return false;
  }

  iTipoAlvara          = $('tipoAlvara').value;
  sDataValidadeInicial = js_formatar( $('dataValidadeInicial').value, 'd' );
  sDataValidadeFinal   = js_formatar( $('dataValidadeFinal').value, 'd' );
  sNomeTipoAlvara      = $('tipoAlvara').options[$('tipoAlvara').selectedIndex].text;

  /**
   * Verifica se a data de validade final é menor que a data validade inicial,
   * se for exibe mensagem de erro
   */
  if ( js_diferenca_datas(sDataValidadeInicial, sDataValidadeFinal, 3) && js_diferenca_datas(sDataValidadeInicial, sDataValidadeFinal, 3) != 'i' && !lAlvaraInterderminado) {

    alert('Data final não pode ser menor que a Data inicial.');
    return false;
  }

  /**
   * Valida se existe alvaras para os filtros encontrados,
   * caso o resultado seja 0 exibe mensagem de erro.
   */
  var sUrl                   = 'iss4_tranformacaogeralalvara.RPC.php';
  var oQuery                 = {};
      oQuery.metodo          = 'consultaAlvaras';
      oQuery.iTipoAlvara     = iTipoAlvara;
      oQuery.sDataInicial    = sDataValidadeInicial;
      oQuery.sDataFinal      = sDataValidadeFinal;

  var oAjax = new Ajax.Request( sUrl, {
                                        method: 'post',
                                        parameters: "json=" + JSON.stringify(oQuery),
                                        onComplete: js_retornoAlvaras
                                      }
                              )
}

/**
 * Função responsavel por verificar se existe pelo menos um alvará para os filtros seleciondos,
 * se existir envia os dados para a proxima tela.
 *
 * @access public
 * @return void
 */
function js_retornoAlvaras( oAjax ){

  var oRetorno  = eval("("+oAjax.responseText+")");
  var sMensagem = oRetorno.sMensagem.urlDecode();

  /**
   * Verifica se existe pelo menos 1 alvaá para o filtro selecionado.
   */
  if ( oRetorno.iStatus > 1 ) {

    alert(sMensagem);
    return false;
  }

  var sQueryString  = '?iTipoAlvara=' + iTipoAlvara + '&sDataValidadeInicial=' + sDataValidadeInicial;
      sQueryString += '&sDataValidadeFinal=' + sDataValidadeFinal + '&sNomeTipoAlvara=' + sNomeTipoAlvara;

  document.location.href = 'iss4_tranformacaogeralalvara002.php' + sQueryString;
}

</script>