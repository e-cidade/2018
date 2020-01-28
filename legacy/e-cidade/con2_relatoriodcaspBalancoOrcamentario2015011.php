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

use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Repository\BalancoOrcamentario as BalancoOrcamentarioRepository;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_liborcamento.php"));
require_once(modification("model/relatorioContabil.model.php"));
require_once(modification("libs/db_utils.php"));

$iAnoUsu                  = db_getsession("DB_anousu");
$oGet                     = db_utils::postMemory($_GET);
$oBalancoOrcamentarioRepo = BalancoOrcamentarioRepository::getInstance();
$iCodigoRelatorio         = $oBalancoOrcamentarioRepo->getCodigoRelatorioByAno($iAnoUsu);
$oRelatorio               = new relatorioContabil($iCodigoRelatorio);
$aPeriodos                = $oRelatorio->getPeriodos();
$aListaPeriodos           = array();

$aListaPeriodos[0] = "Selecione";
foreach ($aPeriodos as $oPeriodo) {
  $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
}

/**
 * Verifica se instituicao atual é prefeitura
 */
$oInstituicao = new Instituicao(db_getsession('DB_instit'));
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

  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
</head>
<body class="body-default">
  <div class="container">

    <table style="width: 445px;">
      <tr>
        <td class="table_header">
          Balanço Orçamentário DCASP a partir de 2015
        </td>
      </tr>
    </table>

    <fieldset style="width: 400px;">

      <legend><strong>Filtros</strong></legend>

      <div id="ctnGridInstituicao"></div>

      <fieldset id="fieldsetSelecaoQuadros">
        <legend class="bold">Relatórios</legend>

        <table id="tableSelecaoQuadros">
          <tr>
            <td><input type="checkbox" id="chkQuadroPrincipal" checked /></td>
            <td><label class="bold" for="chkQuadroPrincipal">Quadro Principal</label></td>
          </tr>

          <tr>
            <td><input type="checkbox" id="chkQuadroRestosNaoProcessados" checked /></td>
            <td><label class="bold" for="chkQuadroRestosNaoProcessados">Quadro da Execução de Restos a Pagar Não Processados</label></td>
          </tr>

          <tr>
            <td><input type="checkbox" id="chkQuadroRestosProcessadosLiquidados" checked /></td>
            <td><label class="bold" for="chkQuadroRestosProcessadosLiquidados">Quadro da Execução de Restos a Pagar Processados e Não Processados Liquidados</label></td>
          </tr>
        </table>
      </fieldset>

      <table>
        <tr>
          <td><label for="o116_periodo" class="bold">Período:</label></td>
          <td style="width: 180px"><?php db_select("o116_periodo", $aListaPeriodos, true, 1); ?></td>
        </tr>
      </table>
    </fieldset>

    <input name="emite" id="emite" type="button" value="Gerar" onclick="js_emite();">
  </div>

  <script>
    var oViewInstituicao;
    var oComboBoxPeriodo;
    var sProgramaRelatorio = 'con2_balancoorcamentario_2015.php';
    var iCodigoRelatorio   = '<?php echo $iCodigoRelatorio; ?>';
    var isPrefeitura       = <?php echo $isPrefeitura ? 'true' : 'false'; ?>;
    var iInstituicao       = <?php echo $oInstituicao->getCodigo(); ?>;

    document.observe('dom:loaded', function () {

      oComboBoxPeriodo           = $('o116_periodo');
      oComboBoxPeriodo.style.width = '100%';

      /**
       * Instituicao logada é prefeitura
       * - exibe componente com todas as instituições
       */
      if (isPrefeitura) {

        oViewInstituicao = new DBViewInstituicao('oViewInstituicao', $('ctnGridInstituicao'));
        oViewInstituicao.setWidth(400);
        oViewInstituicao.setHeight(130);
        oViewInstituicao.show();
      }
    });

    /**
     * Emite o relatório
     * @returns {boolean}
     */
    function js_emite() {

      var iCodigoPeriodo = $F('o116_periodo');
      var sInstituicoes  = iInstituicao;

      /**
       * Busca as instituicoes selecionadas
       * - caso exista o componente com as instituicoes, exibido somente na prefeitura
       */
      if (typeof(oViewInstituicao) != 'undefined') {

        var aInstituicoesSelecionadas = oViewInstituicao.getInstituicoesSelecionadas(true);

        if (aInstituicoesSelecionadas.length == 0) {
          return alert("Selecione ao menos uma Instituição.");
        }

        sInstituicoes = aInstituicoesSelecionadas.join(",");
      }

      var lQuadroPrincipal                   = document.getElementById('chkQuadroPrincipal').checked                   ? 'true' : 'false';
      var lQuadroRestosNaoProcessados        = document.getElementById('chkQuadroRestosNaoProcessados').checked        ? 'true' : 'false';
      var lQuadroRestosProcessadosLiquidados = document.getElementById('chkQuadroRestosProcessadosLiquidados').checked ? 'true' : 'false';

      if (lQuadroPrincipal == 'false' && lQuadroRestosNaoProcessados == 'false' && lQuadroRestosProcessadosLiquidados == 'false') {
        return alert("Selecione ao menos um Relatório.");
      }

      if (iCodigoPeriodo == "0") {
        return alert("O campo Período é de preenchimento obrigatório.");
      }

      var sOpcoes = 'width=' + (screen.availWidth-5) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0';
      var sQuery  = "?db_selinstit="                       + sInstituicoes;
          sQuery += "&periodo="                            + iCodigoPeriodo;
          sQuery += "&codrel="                             + iCodigoRelatorio;
          sQuery += "&lQuadroPrincipal="                   + lQuadroPrincipal;
          sQuery += "&lQuadroRestosNaoProcessados="        + lQuadroRestosNaoProcessados;
          sQuery += "&lQuadroRestosProcessadosLiquidados=" + lQuadroRestosProcessadosLiquidados;

      var oJanela = window.open(sProgramaRelatorio + sQuery, '', sOpcoes);
      oJanela.moveTo(0,0);
    }
  </script>
</body>
</html>
