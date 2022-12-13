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

use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\FluxoCaixaFactory;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/relatorioContabil.model.php"));
require_once(modification("libs/db_utils.php"));

$iAnoUsu = db_getsession("DB_anousu");
$oGet = db_utils::postMemory($_GET);
$sProgramaRelatorio = $oGet->sProgramaRelatorio;
$sProgramaRelatorio = "con2_{$sProgramaRelatorio}_2014.php";
$codigoRelatorio = $oGet->codigoRelatorio;
$factory = new FluxoCaixaFactory;

/**
 * Direciona o usuário para outro programa para não continuar implementando lógica aqui
 */
if ($codigoRelatorio == $factory->obterCodigoRelatorio()) {
    header('Location: con2_fluxocaixaDCASP001_2015.php');
    exit;
}

if ($iAnoUsu >= 2015 && $codigoRelatorio == VariacaoPatrimonialDCASP2015::CODIGO_RELATORIO) {
    header("Location: con2_variacaopatrimonialDCASP001_2015.php");
    exit;
}

$aRelatoriosPorQuadro = array(
    BalancoPatrimonialDCASP2015::CODIGO_RELATORIO,
    BalancoPatrimonialDCASP2017::CODIGO_RELATORIO
);

$sStyleDisplayBalancoPatrimonial = 'none';

if (in_array($codigoRelatorio, $aRelatoriosPorQuadro)) {
    $sStyleDisplayBalancoPatrimonial = '';
}

$oRelatorio = new relatorioContabil($codigoRelatorio);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$sTitulo    = $oRelatorio->getDescricao();

$aPeriodos = $oRelatorio->getPeriodos();
$aListaPeriodos = array();
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

/**
 * - verifica se deve exibir filtro "Imprimir valores do exercicio anterior:"
 * - caso ano anterior nao tinha PCASP valor padrao é não
 * - não exibe o filtro para os relatorios do balanço orçamentario
 */
$iAnoInicioPCASP = ParametroPCASP::getAnoInicioPCASP();
$aCodigosBalancoOrcamentario = array(130, 137, 138);
$imprimirValorExercicioAnterior =  $iAnoUsu - 1 >= $iAnoInicioPCASP;
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
        <?php echo $sTitulo; ?>
      </td>
    </tr>
  </table>

  <fieldset style="width: 400px;">

    <legend><strong>Filtros</strong></legend>

    <div id="ctnGridInstituicao"></div>

    <fieldset id="fieldsetSelecaoQuadros" style="display: <?=$sStyleDisplayBalancoPatrimonial;?>;">
      <legend class="bold">Relatórios</legend>

      <table id="tableSelecaoQuadros">
        <tr>
          <td><input type="checkbox" id="quatroprincipal" checked /></td>
          <td><label for="quatroprincipal"><b>Quadro Principal</b></label></td>
        </tr>
        <tr>
          <td><input type="checkbox" id="quadroAtivoPassivo" checked /></td>
          <td nowrap><label for="quadroAtivoPassivo"><b>Quadro dos Ativos e Passivos Financeiros e Permanentes</b></label></td>
        </tr>
        <tr>
          <td><input type="checkbox" id="quadroContasCompensacao" checked /></td>
          <td><label for="quadroContasCompensacao"><b>Quadro das Contas de Compensação</b></label></td>
        </tr>
        <tr>
          <td><input type="checkbox" id="quadroSuperavitDeficit" checked /></td>
          <td><label for="quadroSuperavitDeficit"><b>Quadro do Superávit / Déficit Financeiro</b></label></td>
        </tr>
      </table>
    </fieldset>

    <table align="center">
      <tr>
        <td style=""><label for="o116_periodo"><b>Período:</b></label></td>
        <td style="width: 180px"><?php db_select("o116_periodo", $aListaPeriodos, true, 1); ?></td>
      </tr>
      <tr id="spanValoresExercicio">
        <td nowrap><label for="imprimirValorExercicioAnterior"><b>Imprimir Valores do Exercício Anterior:</b></label></td>
        <td><?php db_select('imprimirValorExercicioAnterior', array(true => 'Sim', false => 'Não'), true, 1); ?></td>
      </tr>
    </table>
  </fieldset>

  <input  name="emite" id="emite" type="button" value="Gerar" onclick="js_emite();">
</div>
</body>
</html>
<script>


  var oComboboxExercicioAnterior = $('imprimirValorExercicioAnterior');
  var oComboBoxPeriodo           = $('o116_periodo');
  oComboboxExercicioAnterior.style.width = '100%';
  oComboBoxPeriodo.style.width = '100%';
  var oSpanValoresExercicio = $('spanValoresExercicio');
  oSpanValoresExercicio.style.display = 'none';

  var sProgramaRelatorio    = '<?php echo $sProgramaRelatorio; ?>';
  var iAnoUsu               = '<?php echo $iAnoUsu; ?>';
  var iCodigoRelatorio      = '<?php echo $codigoRelatorio; ?>';
  var isPrefeitura          = <?php echo $isPrefeitura ? 'true' : 'false'; ?>;
  var iInstituicao          = <?php echo $iInstituicao; ?>;
  var lPcaspNoAnoAnterior   = <?php echo $imprimirValorExercicioAnterior ? 'true' : 'false'; ?>;
  if (lPcaspNoAnoAnterior) {
    oSpanValoresExercicio.style.display = '';
  }


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
  function js_emite() {

    if (empty(sProgramaRelatorio)) {
      return alert('Relatório não disponível para o exercício ' + iAnoUsu);
    }

    var iCodigoPeriodo = $F('o116_periodo');
    var lConsolidado = false;
    var sInstituicao = iInstituicao;

    /**
     * Busca as instituicoes selecionadas
     * - caso exista o componente com as instituicoes, exibido somente na prefeitura
     */
    if (typeof(oViewInstituicao) != 'undefined') {

      var aInstituicoesSelecionadas = oViewInstituicao.getInstituicoesSelecionadas(true);

      if (aInstituicoesSelecionadas.length == 0) {
        return alert("Selecione ao menos uma Instituição."); return false;
      }

      if (oViewInstituicao.getTotalInstituicoes() == aInstituicoesSelecionadas.length) {
        lConsolidado = true;
      }

      sInstituicao = aInstituicoesSelecionadas.implode("-");
    }

    if (
      iAnoUsu >= 2015 &&
      (
        [<?=BalancoPatrimonialDCASP2015::CODIGO_RELATORIO . ',' . BalancoPatrimonialDCASP2017::CODIGO_RELATORIO?>].indexOf(parseInt(iCodigoRelatorio)) != -1
      )
    ) {

      var lQuatroPrincipal         = document.getElementById('quatroprincipal').checked;
      var lQuadroAtivoPassivo      = document.getElementById('quadroAtivoPassivo').checked;
      var lQuadroContasCompensacao = document.getElementById('quadroContasCompensacao').checked;
      var lQuadroSuperavitDeficit  = document.getElementById('quadroSuperavitDeficit').checked;
      if (!lQuatroPrincipal && !lQuadroAtivoPassivo && !lQuadroContasCompensacao && !lQuadroSuperavitDeficit) {
        return alert("Selecione ao menos um Relatório."); return false;
      }
    }

    if (iCodigoPeriodo == "0") {
      return alert("O campo Período é de preenchimento obrigatório.");
    }

    var query  = "?db_selinstit=" + sInstituicao;
    query += "&periodo=" + iCodigoPeriodo;
    query += "&consolidado=" + (lConsolidado ? 'true' : 'false');
    query += "&codrel=" + iCodigoRelatorio;
    query += "&imprimirValorExercicioAnterior=" + (oComboboxExercicioAnterior.value == 1 ? 'true' : 'false');

    if (iCodigoRelatorio == 129) {

      var oParametros = {
        exec              : "getRecursosNaoConfigurados",
        aCodigosInstituicao      : aInstituicoesSelecionadas,
        iCodigoPeriodo           : iCodigoPeriodo,
        iCodigoRelatorio         : iCodigoRelatorio,
        imprimirValorExercicioAnterior : (oComboboxExercicioAnterior.value == 1 ? 't' : 'f')
      };

      new AjaxRequest("con2_relatoriosdcasp.RPC.php", oParametros, function(oRetorno, lErro) {

        if (lErro) {
          alert(oRetorno.mensagem.urlDecode());
          return false;
        }

        if (oRetorno.lEmiteLista && confirm( _M("financeiro.contabilidade.con2_relatoriosdcasp.relatorio_recursos_balanco_financeiro") ) ) {

          var oDownload = new DBDownload();
          oDownload.addFile( oRetorno.sArquivo, 'Recursos não configurados' );
          oDownload.show();
        }

        if (oRetorno.lEmiteLista && !confirm( _M("financeiro.contabilidade.con2_relatoriosdcasp.emitir_balanco") )) {
          return false;
        }

        jan = window.open(sProgramaRelatorio + query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
        jan.moveTo(0,0);

      }).setMessage("Aguarde, validando configuração.").execute();

    } else {

      if (iAnoUsu >= 2015 && iCodigoRelatorio == <?=BalancoPatrimonialDCASP2015::CODIGO_RELATORIO;?>) {

        sProgramaRelatorio = 'con2_balancopatrimonial_2015.php';
        query += "&lQuadroPrincipal="+lQuatroPrincipal;
        query += "&lQuadroAtivoPassado="+lQuadroAtivoPassivo;
        query += "&lQuadroCompensacao="+lQuadroContasCompensacao;
        query += "&lQuadroSuperavitDeficit="+lQuadroSuperavitDeficit;
      }

      if (iAnoUsu >= 2017 && iCodigoRelatorio == <?=BalancoPatrimonialDCASP2017::CODIGO_RELATORIO;?>) {

        sProgramaRelatorio = 'con2_balancopatrimonial_2017.php';
        query += "&lQuadroPrincipal="+lQuatroPrincipal;
        query += "&lQuadroAtivoPassado="+lQuadroAtivoPassivo;
        query += "&lQuadroCompensacao="+lQuadroContasCompensacao;
        query += "&lQuadroSuperavitDeficit="+lQuadroSuperavitDeficit;
      }

      jan = window.open(sProgramaRelatorio + query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
    }

  }
</script>
