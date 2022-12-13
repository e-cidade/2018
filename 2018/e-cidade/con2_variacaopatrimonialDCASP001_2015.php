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
require_once("libs/db_liborcamento.php");
require_once("model/relatorioContabil.model.php");
require_once("libs/db_utils.php");

$iAnoUsu         = db_getsession("DB_anousu");
$codigoRelatorio = VariacaoPatrimonialDCASP2015::CODIGO_RELATORIO;

$oRelatorio = new relatorioContabil($codigoRelatorio);
$clrotulo   = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');

$aPeriodos         = $oRelatorio->getPeriodos();
$aListaPeriodos    = array();
$aListaPeriodos[0] = "Selecione";
foreach ($aPeriodos as $oPeriodo) {
  $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
}

/**
 * Verifica se instituicao atual é prefeitura
 */
$iInstituicao = db_getsession('DB_instit');
$oInstituicao = new Instituicao($iInstituicao);
$isPrefeitura = $oInstituicao->isPrefeitura() === 't';
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">

  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
</head>
<body style="background-color: #CCCCCC; margin-top: 30px;">
<div class="container">

  <table style="width: 445px;">
    <tr>
      <td class='table_header'>
        <?php echo $oRelatorio->getDescricao(); ?>
      </td>
    </tr>
  </table>

  <fieldset style="width: 400px;">

    <legend><strong>Filtros</strong></legend>

    <div id="ctnGridInstituicao"></div>
    <table>
      <tr>
        <td><label for="o116_periodo"><b>Período:</b></label></td>
        <td><?php db_select("o116_periodo", $aListaPeriodos, true, 1); ?></td>
      </tr>
    </table>

    <fieldset style="border-bottom: 0; border-left: 0; border-right: 0; margin-top:10px;">
      <legend class="bold">Opções de Impressão</legend>
      <table style="width: 100%;" border="0">
        <tr>
          <td style="width:50%" nowrap><label for="imprimirValorExercicioAnterior"><b>Imprimir Valores do Exercício Anterior:</b></label></td>
          <td><?php db_select('imprimirValorExercicioAnterior', array(true => 'Sim', false => 'Não'), true, 1); ?></td>
        </tr>
        <tr>
          <td nowrap><label for="tipoImpressao"><b>Tipo de Impressão:</b></label></td>
          <td>
            <?php
            $aTipoImpressao = array("A" => "Analítico", "S" => "Sintético");
            db_select('tipoImpressao', $aTipoImpressao, true, 1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
  </fieldset>

  <input  name="emite" id="emite" type="button" value="Gerar" onclick="emitir();">
</div>
</body>
</html>
<script>

  var oComboboxExercicioAnterior = $('imprimirValorExercicioAnterior');
  var oComboBoxTipoImpressao     = $('tipoImpressao');
  var oComboBoxPeriodo           = $('o116_periodo');

  oComboboxExercicioAnterior.style.width = '100%';
  oComboBoxPeriodo.style.width           = '100%';
  oComboBoxTipoImpressao.style.width     = '100%';

  var iCodigoRelatorio = '<?php echo $codigoRelatorio; ?>';
  var isPrefeitura     = <?php echo $isPrefeitura ? 'true' : 'false'; ?>;
  var iInstituicao     = <?php echo $iInstituicao; ?>;

  /**
   * Instituicao logada é prefeitura
   * - exibe componente com todas as instituições
   */
  if (isPrefeitura) {

    var oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnGridInstituicao'));
    oViewInstituicao.setWidth(400);
    oViewInstituicao.setHeight(130);
    oViewInstituicao.show();
  }

  /**
   * Emite o relatório
   * @returns {boolean}
   */
  function emitir() {

    var iCodigoPeriodo = $F('o116_periodo');
    var sTipoImpressao = $F('tipoImpressao');
    var sInstituicao   = iInstituicao;

    /**
     * Busca as instituicoes selecionadas
     * - caso exista o componente com as instituicoes, exibido somente na prefeitura
     */
    if (typeof(oViewInstituicao) != 'undefined') {

      var aInstituicoesSelecionadas = oViewInstituicao.getInstituicoesSelecionadas(true);

      if (aInstituicoesSelecionadas.length == 0) {

        alert("Selecione ao menos uma Instituição.");
        return false;
      }

      sInstituicao = aInstituicoesSelecionadas.implode("-");
    }

    if (iCodigoPeriodo == "0") {

      alert("O campo Período é de preenchimento obrigatório.");
      return false;
    }

    var sProgramaRelatorio  = "con2_variacoespatrimoniais_2015.php?db_selinstit=" + sInstituicao;
    sProgramaRelatorio     += "&periodo=" + iCodigoPeriodo;
    sProgramaRelatorio     += "&codrel=" + iCodigoRelatorio;
    sProgramaRelatorio     += "&tipoImpressao=" + sTipoImpressao;
    sProgramaRelatorio     += "&imprimirValorExercicioAnterior=" + (oComboboxExercicioAnterior.value == 1 ? 'true' : 'false');

    var jan = window.open(sProgramaRelatorio,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
</script>