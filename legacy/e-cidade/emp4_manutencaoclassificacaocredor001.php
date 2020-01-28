<?php
/**
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo();
$oRotulo->label("cc31_classificacaocredores");
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
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextFieldData.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbtextField.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewManutencaoEmpenho.classe.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style>
    .tdWidth{
      width: 20%;
    }
  </style>
</head>
<body>
<div class="container">
  <fieldset style="width: 630px;">
    <legend class="bold">Pesquisa de Empenho</legend>
    <table style="width: 100%">
      <tr>
        <td class="tdWidth">
          <label for="numero_empenho">
            <?php
            db_ancora("Número do Empenho:", 'pesquisaEmpenho(true)', 1);
            ?>
          </label>
        </td>
        <td>
          <?php
          db_input('sequencial_empenho', 10, 1, true, 'hidden', 1);
          db_input('numero_empenho', 10, 3, true, 'text', 1, 'onchange="pesquisaEmpenho(false)"');
          ?>
          <label for="numero_empenho_final">
            <?php db_ancora("até:", 'pesquisaEmpenho(true, true)', 1); ?>
          </label>
          <?php
          db_input('sequencial_empenho_final', 10, 1, true, 'hidden', 1);
          db_input('numero_empenho_final', 10, 3, true, 'text', 1, 'onchange="pesquisaEmpenho(false, true)"');
          ?>
        </td>
      </tr>
      <tr>
        <td class="bold"><label for="data_inicial">Data de Emissão:</label></td>
        <td>
          <?php
          db_inputdata('data_inicial', null, null, null, true, 'text', 1);
          echo "<b><label for='data_final'> até </label></b>";
          db_inputdata('data_final', null, null, null, true, 'text', 1);
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap="nowrap">
          <label for="codigo_classificacao">
            <?php
            db_ancora("{$Lcc31_classificacaocredores}", 'pesquisaClassificacaoCredor(true)', 1);
            ?>
          </label>
        </td>
        <td>
          <?php
          $Scodigo_classificacao = "O campo Classificação de Credor";
          db_input('codigo_classificacao', 10, 1, true, 'text', 1, 'onchange="pesquisaClassificacaoCredor(false)"');
          db_input('descricao_classificacao', 40, 1, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label for="codigo_credor">
            <?php
            db_ancora('Credor:', 'pesquisarCredor(true)', 1);
            ?>
          </label>
        </td>
        <td>
          <?php
          $Scodigo_credor = "O campo Credor";
          db_input('codigo_credor', 10, 1, true, 'text', 1, 'onchange="pesquisarCredor(false)"');
          db_input('nome_credor', 40, 1, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td>
          <label class="bold" for="iSituacao">Situaçao</label>
        </td>
        <td>
          <?php
          $aOpcoes = array(
              0 => "Todos",
              RelatorioEmpenhoClassificacaoCredores::SITUACAO_PAGOS => "Pagos",
              RelatorioEmpenhoClassificacaoCredores::SITUACAO_APAGAR => "A Pagar"
          );
          db_select('iSituacao', $aOpcoes, true, 1, "style='width: 95;'");
          ?>
        </td>
      </tr>
    </table>
  </fieldset>

  <p>
    <input type="button" value="Pesquisar" id="btnPesquisar" onclick="pesquisar()" />
    <input type="button" value="Limpar" id="btnLimpar" onclick="limpar()" />
  </p>
</div>

<div class="subcontainer">
  <fieldset style="width: 800px">
    <legend class="bold">Empenhos Encontrados</legend>
    <div id="ctnGridEmpenho">
    </div>
  </fieldset>
</div>

<?php db_menu(); ?>
</body>
</html>

<script>

  const sPathRPC  = 'emp4_manutencaoclassificacao.RPC.php';
  const MENSAGENS = "financeiro.empenho.emp4_manutencaoclassificacaocredor.";

  var oGridEmpenho = new DBGrid('oGridEmpenho');
  oGridEmpenho.nameInstance = 'oGrid';
  oGridEmpenho.setHeader(['Número', 'Credor', 'Lista de Classificação', 'Ações']);
  oGridEmpenho.setCellWidth(['20%', '40%', '20%', '15%']);
  oGridEmpenho.setCellAlign(['center', 'left', 'left', 'center']);
  oGridEmpenho.setHeight(250);
  oGridEmpenho.show($('ctnGridEmpenho'));

  var oManutencaoEmpenho = new DBViewManutencaoEmpenho();

  function pesquisar () {

    var oInputEmpenho       = $('sequencial_empenho');
    var oInputEmpenhofinal  = $('sequencial_empenho_final');
    var oInputDataInicial   = $('data_inicial');
    var oInputDataFinal     = $('data_final');
    var oInputClassificacao = $('codigo_classificacao');
    var oInputCredor        = $('codigo_credor');
    var oSituacao           = $('iSituacao');

    if (oInputEmpenho.value == ""
        && oInputDataInicial.value == ""
        && oInputDataFinal.value == ""
        && oInputClassificacao.value == ""
        && oInputCredor.value == ""
        && oInputEmpenhofinal.value == "") {
      return alert("É preciso preencher algum dos filtros disponíveis.");
    }

    if (oInputDataInicial.value != "" && oInputDataFinal.value != "") {

      if (js_comparadata(oInputDataInicial.value, oInputDataFinal.value, ">")) {
        return alert("Data Inicial não pode ser superior a Data Final.");
      }
    }

    var oParametro = {
      exec : 'pesquisar',
      filtros : {
        sequencial_empenho       : oInputEmpenho.value,
        sequencial_empenho_final : oInputEmpenhofinal.value,
        data_inicial             : oInputDataInicial.value,
        data_final               : oInputDataFinal.value,
        codigo_classificacao     : oInputClassificacao.value,
        codigo_credor            : oInputCredor.value,
        iSituacao                : oSituacao.value
      }
    };


    new AjaxRequest(
      sPathRPC,
      oParametro,
      function (oRetorno, lErro) {

        oGridEmpenho.clearAll(true);
        if (lErro) {
          return alert(oRetorno.mensagem.urlDecode());
        }

        oRetorno.empenhos.each(
          function (oEmpenho, iIndice) {

            var oConsultaEmpenho = document.createElement("a");
            var oBotao = document.createElement('input');
            oBotao.setAttribute('type', 'button');
            oBotao.setAttribute('onclick', 'oManutencaoEmpenho.show(' + oEmpenho.sequencial + ', pesquisar)');
            oBotao.setAttribute('value', 'Manutenção');

            oConsultaEmpenho.innerHTML = oEmpenho.numero.urlDecode();
            oConsultaEmpenho.href = "javascript:;";
            oConsultaEmpenho.setAttribute("onclick", "js_JanelaAutomatica(\"empempenho\"," + oEmpenho.sequencial + ")");

            var aLinha = [
              oConsultaEmpenho.outerHTML,
              oEmpenho.credor.urlDecode(),
              oEmpenho.classificacao.urlDecode(),
              oBotao.outerHTML
            ];
            oGridEmpenho.addRow(aLinha);
          }
        );
        oGridEmpenho.renderRows();
      }
    ).setMessage('Aguarde, carregando informações...').execute();
  }

  function limpar () {

    $('sequencial_empenho').value   = "";
    $('numero_empenho').value       = "";
    $('data_inicial').value         = "";
    $('data_final').value           = "";
    $('codigo_classificacao').value = "";
    $('codigo_credor').value        = "";
    $('descricao_classificacao').value = "";
    $('nome_credor').value             = "";
    $('numero_empenho_final').value             = "";
    $('sequencial_empenho_final').value             = "";
    oGridEmpenho.clearAll(true);
  }

  function pesquisarCredor(lMostrar) {

    var sPath = 'func_cgm_empenho.php?funcao_js=parent.preencheCredor|e60_numcgm|z01_nome';
    if (!lMostrar) {

      if ($F('codigo_credor') == "") {
        $('nome_credor').value = '';
        return;
      }

      sPath = "func_cgm_empenho.php?funcao_js=parent.completarCredor&pesquisa_chave="+$F('codigo_credor');
    }
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_cgm', sPath, 'Pesquisar Credores do Empenho', lMostrar);
  }

  function preencheCredor(iCodigo, sNome) {

    $('codigo_credor').value = iCodigo;
    $('nome_credor').value = sNome;
    db_iframe_cgm.hide();
  }

  function completarCredor(sNome, lErro) {

    $('nome_credor').value = sNome;
    if (lErro) {
      $('codigo_credor').value = '';
    }
  }

  function pesquisaListaClassificacao(lMostrar) {

    var sPath = "func_classificacaocredores.php?funcao_js=parent.preencheListaClassificacao|cc30_codigo|cc30_descricao";
    if (!lMostrar) {

      if ($F('cc30_codigo') == "") {
        $('cc30_descricao').value = '';
        return;
      }
      sPath = "func_classificacaocredores.php?funcao_js=parent.completaListaClassificacao&pesquisa_chave="+$F('cc30_codigo');
    }
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_classificacaocredores', sPath, 'Pesquisar Lista de Classificaçao de Credores', lMostrar);
  }

  function preencheListaClassificacao(iCodigo, sDescricao) {

    $('cc30_codigo').value = iCodigo;
    $('cc30_descricao').value = sDescricao;
    db_iframe_classificacaocredores.hide();
  }

  function completaListaClassificacao(sDescricao, lErro) {

    $('cc30_descricao').value = sDescricao;
    if (lErro) {
      $('cc30_codigo').value = '';
    }
  }

  function pesquisaClassificacaoCredor(lMostrar) {

    var sPath = "func_classificacaocredores.php?funcao_js=parent.preencheClassificacaoCredor|cc30_codigo|cc30_descricao";
    if (!lMostrar) {

      if ($F('codigo_classificacao') == "") {
        $('descricao_classificacao').value = '';
        return;
      }
      sPath = "func_classificacaocredores.php?funcao_js=parent.completaClassificacaoCredor&pesquisa_chave="+$F('codigo_classificacao');
    }
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_classificacaocredores', sPath, 'Pesquisar Lista de Classificaçao de Credores', lMostrar);
  }

  function preencheClassificacaoCredor(iCodigo, sDescricao) {

    $('codigo_classificacao').value = iCodigo;
    $('descricao_classificacao').value = sDescricao;
    db_iframe_classificacaocredores.hide();
  }

  function completaClassificacaoCredor(sDescricao, lErro) {

    $('descricao_classificacao').value = sDescricao;
    if (lErro) {
      $('codigo_classificacao').value = '';
    }
  }

  function pesquisaEmpenho(lMostrar, lFinal) {

    var sFunction = "",
      oNumeroEmpenho = $('numero_empenho'),
      oSequencialEmpenho = $('sequencial_empenho');

    if (lFinal) {

      sFunction = "Final";
      oNumeroEmpenho = $('numero_empenho_final');
      oSequencialEmpenho = $('sequencial_empenho_final');
    }

    var sPath = "func_empempenho.php?funcao_js=parent.preencherEmpenho" + sFunction + "|e60_numemp|e60_codemp|e60_anousu";
    if (!lMostrar) {

      if (oNumeroEmpenho.value == "") {
        oSequencialEmpenho.value = '';
        return false;
      }
      var aEmpenho = oNumeroEmpenho.value.split('/');
      var iNumeroEmpenho = aEmpenho[0];
      var iAnoEmpenho    = '';
      if (aEmpenho.length == 2) {
        iAnoEmpenho = aEmpenho[1];
      }
      sPath = "func_empempenho.php?funcao_js=parent.completarEmpenho" + sFunction + "&lPesquisaPorCodigoEmpenho=true&pesquisa_chave="+iNumeroEmpenho+"&iAnoEmpenho="+iAnoEmpenho;
    }

    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_empempenho', sPath, 'Pesquisar Empenho', lMostrar);
  }

  function preencherEmpenho(iSequencial, iCodigo, iAno) {

    $('sequencial_empenho').value = iSequencial;
    $('numero_empenho').value = iCodigo+"/"+iAno;

    if ($('numero_empenho_final').value == '') {

      $('sequencial_empenho_final').value = iSequencial;
      $('numero_empenho_final').value = iCodigo+"/"+iAno;
    }

    db_iframe_empempenho.hide();
  }

  function completarEmpenho(iSequencial, sNome, lErro) {

    var oSequencialEmpenho = $('sequencial_empenho');
    var oNumeroEmpenho = $('numero_empenho');

    oSequencialEmpenho.value = iSequencial;

    if (sNome === true) {

      oSequencialEmpenho.value = '';
      oNumeroEmpenho.value = '';
    } else if ($('numero_empenho_final').value == '') {

      $('sequencial_empenho_final').value = iSequencial;
      $('numero_empenho_final').value = oNumeroEmpenho.value;
    }
  }

  function preencherEmpenhoFinal(iSequencial, iCodigo, iAno) {

    $('sequencial_empenho_final').value = iSequencial;
    $('numero_empenho_final').value = iCodigo+"/"+iAno;

    if ($('numero_empenho').value == "") {

      $('sequencial_empenho').value = iSequencial;
      $('numero_empenho').value = iCodigo+"/"+iAno;
    }
    db_iframe_empempenho.hide();
  }

  function completarEmpenhoFinal(iSequencial, sNome, lErro) {

    var oSequencialEmpenho = $('sequencial_empenho_final');
    var oNumeroEmpenho = $('numero_empenho_final');
    oSequencialEmpenho.value = iSequencial;
    if (sNome === true) {

      oSequencialEmpenho.value = '';
      oNumeroEmpenho.value = '';
    }

    if ($('numero_empenho').value == "") {

      $('sequencial_empenho').value = iSequencial;
      $('numero_empenho').value = oNumeroEmpenho.value;
    }
  }
</script>