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

$iAnoUsu = db_getsession('DB_anousu');
$oGet = db_utils::postMemory($_GET);

$fluxoCaixaFactory = new FluxoCaixaFactory;
$sProgramaRelatorio = $fluxoCaixaFactory->obterProcessador();
$codigoRelatorio = $fluxoCaixaFactory->obterCodigoRelatorio();
$oRelatorio = new relatorioContabil($codigoRelatorio);
$clrotulo = new rotulocampo;
$clrotulo->label('DBtxt21');
$clrotulo->label('DBtxt22');
$sTitulo = $oRelatorio->getDescricao();
$aPeriodos = $oRelatorio->getPeriodos();
$aListaPeriodos = array();
$aListaPeriodos[0] = 'Selecione';

foreach ($aPeriodos as $oPeriodo) {
    $aListaPeriodos[$oPeriodo->o114_sequencial] = $oPeriodo->o114_descricao;
}

/**
 * Verifica se instituicao atual é prefeitura
 */
$oInstituicao = new Instituicao(db_getsession('DB_instit'));
$isPrefeitura = $oInstituicao->isPrefeitura() === 't';

/**
 * - verifica se deve exibir filtro "Imprimir valores do exercicio anterior:"
 * - caso ano anterior nao tinha PCASP valor padrao é não
 * - não exibe o filtro para os relatorios do balanço orçamentario
 */
$iAnoInicioPCASP = ParametroPCASP::getAnoInicioPCASP();
$imprimirValorExercicioAnterior =  $iAnoUsu - 1 >= $iAnoInicioPCASP;
?>
<html>
    <head>
        <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <link href="estilos.css" rel="stylesheet" type="text/css">
        <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
        <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/arrays.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
        <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
    </head>
    <body style="background-color: #CCCCCC; margin-top: 30px;">
        <div class="container">
            <table style="width: 445px;">
                <tr>
                    <td class='table_header'><?php echo $sTitulo; ?></td>
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
                            <td><label for="chkQuadroPrincipal"><b>Quadro Principal</b></label></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="chkReceitasDerivadas" checked /></td>
                            <td nowrap><label for="chkReceitasDerivadas"><b>Quadro Receitas Derivadas Originárias</b></label></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="chkTransferenciaRecebida" checked /></td>
                            <td><label for="chkTransferenciaRecebida"><b>Quadro Transfêrencias Recebidas e Concedidas</b></label></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="chkDesembolsoPessoal" checked /></td>
                            <td><label for="chkDesembolsoPessoal"><b>Quadro Desembolso de Pessoal e Demais Despesas Por Função</b></label></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" id="chkJurosEncargos" checked /></td>
                            <td><label for="chkJurosEncargos"><b>Quadro Juros e Encargos da Dívida</b></label></td>
                        </tr>
                    </table>
                </fieldset>
                <table align="center">
                    <tr>
                        <td><label for="o116_periodo"><b>Período:</b></label></td>
                        <td style="width: 180px"><?php db_select("o116_periodo", $aListaPeriodos, true, 1); ?></td>
                    </tr>
                    <tr id="spanValoresExercicio">
                        <td nowrap><label for="imprimirValorExercicioAnterior"><b>Imprimir Valores do Exercício Anterior:</b></label></td>
                        <td><?php db_select('imprimirValorExercicioAnterior', array(true => 'Sim', false => 'Não'), true, 1); ?></td>
                    </tr>
                </table>
            </fieldset>
            <input name="emite" id="emite" type="button" value="Gerar" onclick="js_emite();">
        </div>
    </body>
</html>
<script>
    var oComboboxExercicioAnterior = $('imprimirValorExercicioAnterior');
    var oComboBoxPeriodo = $('o116_periodo');
    oComboboxExercicioAnterior.style.width = '100%';
    oComboBoxPeriodo.style.width = '100%';
    var oSpanValoresExercicio = $('spanValoresExercicio');
    oSpanValoresExercicio.style.display = 'none';

    var sProgramaRelatorio    = '<?php echo $sProgramaRelatorio; ?>';
    var iAnoUsu               = '<?php echo $iAnoUsu; ?>';
    var iCodigoRelatorio      = '<?php echo $codigoRelatorio; ?>';
    var isPrefeitura          = <?php echo $isPrefeitura ? 'true' : 'false'; ?>;
    var iInstituicao          = <?php echo $oInstituicao->getCodigo(); ?>;

    oSpanValoresExercicio.style.display = '';
    oComboboxExercicioAnterior.value = <?php echo $imprimirValorExercicioAnterior ? 'true' : 'false'; ?>;


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
        return alert("Selecione ao menos uma Instituição.");
      }

      if (oViewInstituicao.getTotalInstituicoes() == aInstituicoesSelecionadas.length) {
        lConsolidado = true;
      }
      sInstituicao = aInstituicoesSelecionadas.implode("-");
    }

    var lQuadroPrincipal       = document.getElementById('chkQuadroPrincipal').checked ? 'true' : 'false';
    var lReceitasDerivadas     = document.getElementById('chkReceitasDerivadas').checked ? 'true' : 'false';
    var lTransferenciaRecebida = document.getElementById('chkTransferenciaRecebida').checked ? 'true' : 'false';
    var lDesembolsoPessoal     = document.getElementById('chkDesembolsoPessoal').checked ? 'true' : 'false';
    var lJurosEncargos         = document.getElementById('chkJurosEncargos').checked ? 'true' : 'false';
    if (!lQuadroPrincipal && !lReceitasDerivadas && !lTransferenciaRecebida && !lDesembolsoPessoal && !lJurosEncargos) {
      return alert("Selecione ao menos um Relatório.");
    }

    if (iCodigoPeriodo == "0") {
      return alert("O campo Período é de preenchimento obrigatório.");
    }

    var sQuery  = "?db_selinstit="                    + sInstituicao;
        sQuery += "&periodo="                         + iCodigoPeriodo;
        sQuery += "&consolidado="                     + (lConsolidado ? 'true' : 'false');
        sQuery += "&codrel="                          + iCodigoRelatorio;
        sQuery += "&imprimirValorExercicioAnterior="  + (oComboboxExercicioAnterior.value == 1 ? 'true' : 'false');
        sQuery += "&lQuadroPrincipal="                + lQuadroPrincipal;
        sQuery += "&lQuadroReceitas="                 + lReceitasDerivadas;
        sQuery += "&lQuadroTransferencias="           + lTransferenciaRecebida;
        sQuery += "&lQuadroDesembolsos="              + lDesembolsoPessoal;
        sQuery += "&lQuadroDivida="                   + lJurosEncargos;


    var jan = window.open(sProgramaRelatorio + sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);

  }
</script>
