<?
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


require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));


?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <style>
    /**
     * Aqui faz o alinhamento na mão para que o botão do lançador não quebre de linha.
     */
    #ctnLancadorListaClassificacao #divacoes{
      white-space: nowrap;
    }
  </style>
</head>
<body class="container">

<fieldset style="width: 640px;">
  <legend class="bold">Notas de Liquidação Suspensas</legend>
  <table>
    <tr>
      <td>
        <label for="numero_empenho">
          <?php
          db_ancora('Empenho:', 'pesquisarEmpenho(true)', 1);
          ?>
        </label>
      </td>
      <td>
        <input type="text" size="10" name="numero_empenho" id="numero_empenho" onchange="pesquisarEmpenho(false);"  style="width: 83px;" />
      </td>
    <tr>
      <td>
        <label for="codigo_credor">
          <a id="ancoraCredor">Credor:</a>
        </label>
      </td>
      <td>
        <?php
        $Se60_numcgm = "Credor";
        db_input('e60_numcgm', 10, 1, true, 'text', 1, 'onchange="pesquisaCredor(false);"');
        db_input('z01_nome', 50, 2, true, 'text', 3, "style='width: 355px;'");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap=""><label for="data_inicial"><b>Data de Suspensão:</b></label></td>
      <td>
        <?php
        db_inputdata('data_inicial', null, null, null, true, 'text', 1, "style='width:83px;'");
        echo " <label for='data_final'><b>até</b></label> ";
        db_inputdata('data_final', null, null, null, true, 'text', 1, "style='width:83px;'");
        ?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <div id="ctnLancadorListaClassificacao"></div>
      </td>
    </tr>
    <tr>
      <td nowrap=""><b><label for="situacao_notas">Situação:</label></b></td>
      <td>
        <select id="situacao_notas" style="width:83px;">
          <option value="<?php echo RelatorioNotaLiquidacaoSuspensaoPagamento::SITUACAO_TODAS; ?>">Todas</option>
          <option value="<?php echo RelatorioNotaLiquidacaoSuspensaoPagamento::SITUACAO_SUSPENSAS; ?>">Suspensas</option>
        </select>
      </td>
    </tr>
  </table>
</fieldset>
<p>
  <input type="button" id="btnEmitir" value="Emitir" onclick="emitir();"/>
</p>
<script>

  $("numero_empenho").addEventListener("input", function() {
    this.value = this.value.replace(/[^0-9\/]/g, '').replace(/(\/?)([0-9]*)(\/?)([0-9]{0,4})(.*)(\/?)/, '$2$3$4')
  });

  const CAMINHO_MENSAGENS = 'financeiro.caixa.cai3_notaliquidacaosuspensa001.';

  var oInput = {
    oNumeroEmpenho : $('numero_empenho'),
    oCodigoCredor  : $('e60_numcgm'),
    oNomeCredor    : $('z01_nome'),
    oDataInicial   : $('data_inicial'),
    oDataFinal     : $('data_final'),
    oSituacaoNota  : $('situacao_notas')
  };

  var oLancadorLista = new DBLancador('oLancadorLista');
  oLancadorLista.setNomeInstancia('oLancadorLista');
  oLancadorLista.setTextoFieldset('Lista de Classificação de Credores');
  oLancadorLista.setLabelAncora('Lista de Classificação de Credores:');
  oLancadorLista.setLabelValidacao('Lista de Classificação de Credores');
  oLancadorLista.setParametrosPesquisa('func_classificacaocredores.php', ['cc30_codigo','cc30_descricao']);
  oLancadorLista.setTituloJanela('Pesquisa de Lista de Classificação de Credores');
  oLancadorLista.setGridHeight(100);
  oLancadorLista.show($('ctnLancadorListaClassificacao'));

  var oLookUpCredor = new DBLookUp($('ancoraCredor'), oInput.oCodigoCredor, oInput.oNomeCredor, {
    "sArquivo" : "func_cgm_empenho.php",
    "sObjetoLookUp" : "db_iframe_cgm",
    "sLabel" : "Pesquisa de Credor do Empenho"
  });


  function emitir() {

    if (formularioVazio() && !confirm(_M(CAMINHO_MENSAGENS+'formulario_sem_filtro'))) {
      return false;
    }

    if (js_comparadata(oInput.oDataInicial.value,oInput.oDataFinal.value, '>')) {
      return alert(_M(CAMINHO_MENSAGENS+'data_invalida'));
    }

    var aListaClassificacao = [];
    oLancadorLista.getRegistros().each(
      function (oLista) {
        aListaClassificacao.push(oLista.sCodigo);
      }
    );

    var sCaminhoRelatorio = 'cai3_notaliquidacaosuspensa002.php?';
    sCaminhoRelatorio += "&numero_empenho="+oInput.oNumeroEmpenho.value;
    sCaminhoRelatorio += "&codigo_credor="+oInput.oCodigoCredor.value;
    sCaminhoRelatorio += "&data_inicial="+oInput.oDataInicial.value;
    sCaminhoRelatorio += "&data_final="+oInput.oDataFinal.value;
    sCaminhoRelatorio += "&situacao="+oInput.oSituacaoNota.value;
    sCaminhoRelatorio += "&lista_classificacao="+aListaClassificacao.join(",");

    var oJanela = window.open(
      sCaminhoRelatorio,
      '',
      'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    oJanela.moveTo(0,0);
  }

  function formularioVazio() {

    return oInput.oNumeroEmpenho.value.trim()   == "" &&
           oInput.oCodigoCredor.value.trim()    == "" &&
           oInput.oDataInicial.value.trim()     == "" &&
           oLancadorLista.getRegistros().length == 0;
  }

  function pesquisarEmpenho(lMostrar) {

    var sCaminhoArquivo = '';
    if (lMostrar) {
      sCaminhoArquivo = 'func_empempenho.php?funcao_js=parent.preencheEmpenho|e60_codemp|e60_anousu';
    } else {

      if (oInput.oNumeroEmpenho.value.trim() == "") {
        oInput.oNumeroEmpenho.value = "";
        return;
      }

      var aEmpenho = oInput.oNumeroEmpenho.value.split('/');
      var iAnoEmpenho = '';
      if (aEmpenho[1]) {
        iAnoEmpenho = aEmpenho[1];
      }
      sCaminhoArquivo = 'func_empempenho.php?funcao_js=parent.validarEmpenho&lNovoDetalhe=1&lPesquisaPorCodigoEmpenho=true&pesquisa_chave='+aEmpenho[0]+'&iAnoEmpenho='+iAnoEmpenho;
    }
    js_OpenJanelaIframe('CurrentWindow.corpo','db_iframe_empempenho', sCaminhoArquivo, 'Pesquisa de Empenho', lMostrar);
  }

  function preencheEmpenho(iCodigo, iAno) {

    oInput.oNumeroEmpenho.value = iCodigo+'/'+iAno;
    db_iframe_empempenho.hide();
  }

  function validarEmpenho(sNumeroEmpenho, lErro) {
    if (lErro) {
      oInput.oNumeroEmpenho.value = "";
    }
  }

</script>
<?php db_menu(); ?>
</body>
</html>