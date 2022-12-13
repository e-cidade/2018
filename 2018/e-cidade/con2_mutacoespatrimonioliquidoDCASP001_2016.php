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
require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$iCodigoRelatorio = MutacoesPatrimonioLiquidoDCASP::CODIGO_RELATORIO;
$aListaPeriodos   = array(1 => "Ano");

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
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/arrays.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script type="text/javascript" src="scripts/EmissaoRelatorio.js"></script>
  <script type="text/javascript" src="scripts/widgets/DBViewInstituicao.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>

  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #ccc; margin-top: 30px;">
<div class="container">

  <table style="width: 445px;">
    <tr>
      <td class='table_header'>
        Demonstração das Mutações do Patrimônio Líquido
      </td>
    </tr>
  </table>

  <fieldset style="width: 400px;">

    <legend><strong>Filtros</strong></legend>

    <div id="ctnGridInstituicao"></div>
    <table>
      <tr>
        <td>
          <label for="o116_periodo">
            <b>Período:</b>
          </label>
        </td>
        <td><?php db_select("o116_periodo", $aListaPeriodos, true, 1); ?></td>
      </tr>
    </table>

  </fieldset>

  <input name="emite" id="emite" type="button" value="Gerar" onclick="emitir();">
</div>
</body>
</html>
<script>

  var oComboBoxPeriodo = $('o116_periodo');
  oComboBoxPeriodo.style.width = '100%';

  var iCodigoRelatorio = '<?php echo $iCodigoRelatorio; ?>';
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

    var oParametros = {
      db_selinstit : sInstituicao,
      periodo      : iCodigoPeriodo,
      codrel       : iCodigoRelatorio
    };

    new EmissaoRelatorio('con2_mutacoespatrimonioliquido002.php', oParametros).open();
  }
</script>
