<?
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulo('acordo');
$oRotulo->label('ac16_sequencial');

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>

  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">

</head>
<body style="background-color: #CCCCCC; margin-top:30px;">

<div class="container">
  <fieldset style="width: 900px;">
    <legend class="bold">Execução dos Itens do Acordo</legend>

    <table style="width: 100%">
      <tr>
        <td class="bold" width="60" nowrap>
          <label for="ac16_sequencial">
            <?php
            db_ancora('Acordo:', "pesquisaAcordo(true);", 1);
            ?>
          </label>
        </td>
        <td>
          <?php
          db_input('ac16_sequencial', 10, 1, true, 'text', 1, 'onchange="pesquisaAcordo(false);"');
          db_input('ac16_resumoobjeto', 70, 1, true, 'text', 3, null, null, null, 'width:85%');
          ?>
        </td>
      </tr>
      <tr>
        <td class="bold" nowrap>
          <label for="percentual">
            Percentual Executado:
          </label>
        </td>
        <td>
          <?php
          db_input('percentual', 10, 1, true, 'text', 3);
          ?>
        </td>
      </tr>
    </table>

    <fieldset style="margin-top:10px;width: 97%; border-left: none; border-right: none; border-bottom: none; ">
      <legend class="bold">Itens</legend>
      <div id="ctnGridItensAcordo">
      </div>
    </fieldset>
  </fieldset>
</div>


<div id="ctnWindowExecucao" class="container" style="display:none;">

  <fieldset style="width: 98%;">
    <legend class="bold">Dados da Execução</legend>
    <input type="hidden" value="" id="codigo_item" />
    <input type="hidden" value="" id="ordem_item" />
    <input type="hidden" value="" id="codigo_execucao" />
    <input type="hidden" value="" id="servico" />
    <table>
      <tr>
        <td class="bold"><label for="quantidade">Quantidade:</label></td>
        <td>
          <?php
          $Squantidade = "Quantidade";
          db_input('quantidade', 10, 4, true, 'text', 1);
          ?>
        </td>
        <td class="bold" ><label for="valor">Valor:</label></td>
        <td>
          <?php
          $Svalor = "Valor";
          db_input('valor', 10, 4, true, 'text', 1);
          ?>
        </td>
      </tr>
      <tr>
        <td class="bold"><label for="data_inicial">De:</label></td>
        <td width="140">
          <?php
          $Sdata_inicial = "Data Inicial";
          db_inputdata('data_inicial', null, null, null, true, 'text', 1);
          ?>
        </td>
        <td class="bold"><label for="data_final">até:</label></td>
        <td>
          <?php
          $Svalor = "data_final";
          db_inputdata('data_final', null, null, null, true, 'text', 1);
          ?>
        </td>
      </tr>
    </table>

    <fieldset style="width: 96%; margin-top:10px; border-bottom: none; border-left: none; border-right: none;">
      <legend class="bold">Informações Adicionais</legend>
      <table border="0">
        <tr>
          <td class="bold" nowrap>
            <label for="nota_fiscal">Nota Fiscal:</label>
          </td>
          <td colspan="3">
            <?php
            db_input("nota_fiscal", 55, 0, true, 'text', 1, null, null, null, null, 60);
            ?>
          </td>
        </tr>
        <tr>
          <td class="bold" nowrap>
            <label for="numero_processo">Número do Processo:</label>
          </td>
          <td colspan="3">
            <?php
            db_input("numero_processo", 55, 0, true, 'text', 1, null, null, null, null, 60);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <p align="center">
      <input type="button" value="Salvar" id="btnSalvar" onclick="salvar()"/>
      <input type="button" value="Novo" id="btnNovo" onclick="limparCamposWindow(false); liberarAlteraExclui(true)"/>
    </p>
    <fieldset style="width: 96%; margin-top:10px;">
      <legend class="bold">Execuções Realizadas</legend>
      <div id="ctnGridExecucao"></div>
    </fieldset>
  </fieldset>

  <p align="center">
    <input type="button" value="Fechar" id="btnFecharWindow" onclick="fecharJanela()"/>
  </p>
</div>

<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

  const MENSAGENS = 'patrimonial.contratos.ac04_movimentacaomanual001.';
  const sPathRPC  = 'aco4_acordoexecucao.RPC.php';
  var oInputCodigoacordo = $('ac16_sequencial');
  var oInputResumoObjeto = $('ac16_resumoobjeto');
  var oWindow = null;


  var oGridItens = new DBGrid('oGridItens');
  oGridItens.nameInstance = 'oGridItens';
  oGridItens.setCellWidth(['10%', '35%', '10%', '15%', '15%', '15%', '0%']);
  oGridItens.setCellAlign(['center', 'left', 'left', 'right', 'right', 'center', 'center']);
  oGridItens.setHeader(['Ordem', 'Descrição', 'Unidade', 'Quantidade', 'Valor', 'Ação', 'CódigoItem']);
  oGridItens.aHeaders[6].lDisplayed = false;
  oGridItens.setHeight(300);
  oGridItens.show($('ctnGridItensAcordo'));


  var oGridExecucao          = new DBGrid('oGridExecucao');
  oGridExecucao.nameInstance = 'oGridExecucao';
  oGridExecucao.setCellAlign(['center', 'center', 'right', 'right', 'center', 'left', 'left']);
  oGridExecucao.setCellWidth(['20%', '20%', '20%', '20%', '20%', '0%', '0%']);
  oGridExecucao.setHeader(['Data Inicial', 'Data Final', 'Quantidade', 'Valor', 'Ação', 'NotaFiscal', 'Processo']);
  oGridExecucao.aHeaders[5].lDisplayed = false;
  oGridExecucao.aHeaders[6].lDisplayed = false;
  oGridExecucao.hasTotalizador = true;
  oGridExecucao.show($('ctnGridExecucao'));


  function getItensAcordo() {

    var oParametro = {
      exec : 'getItens',
      iCodigoAcordo : oInputCodigoacordo.value
    };

    new AjaxRequest(
      sPathRPC,
      oParametro,
      function (oRetorno, lErro) {

        oGridItens.clearAll(true);
        if (lErro) {

          alert(oRetorno.mensagem.urlDecode());
          limparCampos();
          return false;
        }

        $('percentual').value = js_formatar(oRetorno.percentual_executado, 'f');

        oRetorno.itens.each(
          function (oLinha, iIndice) {

            var sButton = "<input type='button' class='btnManutencao' value='Manutenção' onclick='executarManutencao("+iIndice+");' />";
            var aLinha = [
              oLinha.ordem,
              oLinha.descricao.urlDecode(),
              oLinha.unidade.urlDecode(),
              oLinha.quantidade,
              js_formatar(oLinha.valor, 'f'),
              sButton,
              oLinha.codigo
            ];
            oGridItens.addRow(aLinha);
          }
        );
        oGridItens.renderRows();


        oRetorno.itens.each(
          function (oLinha, iIndice) {

            var sMensagem = "<b>Elemento:</b> "+oLinha.elemento+" - "+oLinha.descricao_elemento.urlDecode()+"<br>";
            sMensagem += "<b>Resumo:</b> "+oLinha.resumo.urlDecode();
            oGridItens.setHint(iIndice, 1, sMensagem);
          }
        );

      }
    ).setMessage('Aguarde, carregando itens...').execute();
  }


  function executarManutencao(iIndiceItem) {

    liberarManutencao(false);
    var oRow           = oGridItens.aRows[iIndiceItem];
    var sDescricaoItem = oRow.aCells[1].getValue();
    $('ordem_item').value  = oRow.aCells[0].getValue();
    $('codigo_item').value = oRow.aCells[6].getValue();

    var oContainerWindow = $('ctnWindowExecucao');

    if (oWindow == null) {

      oContainerWindow.style.display = '';
      oWindow = new windowAux('oWindow', 'Execução dos Itens do Contrato', 750, 600);
      oWindow.setContent(oContainerWindow);
      oWindow.setShutDownFunction(
        function() {
          fecharJanela();
        }
      );

      var sTitulo = 'Manutenção das Execuções dos Itens do Contrato';
      var sAjuda  = 'Informe abaixo os dados de execução do item: <span class="bold" id="spanDescricaoItem">'+sDescricaoItem+'</span>.';
      oMessageBoard = new DBMessageBoard('oMessageBoard', sTitulo, sAjuda, oWindow.getContentContainer());
    }

    oWindow.setIndex(5);
    oWindow.show();

    $('spanDescricaoItem').innerHTML = sDescricaoItem;
    carregarExecucoes();
  }


  function carregarExecucoes() {

    var oParametro = {
      exec : 'getExecucoesPorItem',
      codigo_acordo : oInputCodigoacordo.value,
      codigo_item : $F('codigo_item'),
      ordem_item  : $F('ordem_item')
    };

    new AjaxRequest(
      sPathRPC,
      oParametro,
      function (oRetorno, lErro) {

        var oButtonSalvar = $('btnSalvar');
        oButtonSalvar.disabled = false;
        oGridExecucao.clearAll(true);

        if (lErro) {
          oButtonSalvar.disabled = true;
          alert(oRetorno.mensagem.urlDecode());
        }

        var oInputQuantidade = $('quantidade');
        oInputQuantidade.disabled = false;
        oInputQuantidade.className = '';
        $('servico').value = oRetorno.servico;
        if (oRetorno.servico) {

          oInputQuantidade.disabled  = true;
          oInputQuantidade.value     = 0;
          oInputQuantidade.className = 'readonly';
        }

        var nQuantidadeTotal = Number(0);
        var nValorTotal      = Number(0);
        oRetorno.execucoes.each(
          function (oExecucao, iIndiceExecucao) {


            nQuantidadeTotal += Number(oExecucao.quantidade);
            nValorTotal      += Number(oExecucao.valor);
            var sAcao = "<input type='button' value=' A ' class='btnAlteraExclui' onclick='alterar("+iIndiceExecucao+", "+oExecucao.codigo_execucao+");' />";
            sAcao    += "<input type='button' value=' E ' class='btnAlteraExclui' onclick='excluir("+oExecucao.codigo_execucao+");' />";

            var aLinha = [
              js_formatar(oExecucao.data_inicial, 'd'),
              js_formatar(oExecucao.data_final, 'd'),
              oExecucao.quantidade,
              js_formatar(oExecucao.valor, 'f'),
              sAcao,
              oExecucao.nota_fiscal.urlDecode(),
              oExecucao.processo.urlDecode()
            ];
            oGridExecucao.addRow(aLinha);
          }
        );
        oGridExecucao.renderRows();

        var oColunaQuantidade = $('TotalForCol2');
        var oColunaValor      = $('TotalForCol3');
        oColunaQuantidade.innerHTML   = nQuantidadeTotal;
        oColunaValor.innerHTML        = js_formatar(nValorTotal, 'f');
        oColunaQuantidade.style.width = '20%';
        oColunaValor.style.width      = '20%';
      }
    ).setMessage('Aguarde, carregando informações...').execute();

    liberarAlteraExclui(true);
  }

  function alterar(iIndice, iCodigoExecucao) {

    var oRow = oGridExecucao.aRows[iIndice];
    liberarAlteraExclui(false);
    $('codigo_execucao').value = iCodigoExecucao;
    $('data_inicial').value    = oRow.aCells[0].getValue();
    $('data_final').value      = oRow.aCells[1].getValue();
    $('quantidade').value      = oRow.aCells[2].getValue();
    $('valor').value           = js_strToFloat(oRow.aCells[3].getValue());
    $('nota_fiscal').value     = oRow.aCells[5].getValue().trim();
    $('numero_processo').value = oRow.aCells[6].getValue().trim();
  }

  function salvar() {

    if (!validarFormulario()) {
      return false;
    }

    var oParametro = {
      exec : 'salvarExecucao',
      codigo_execucao : $F('codigo_execucao'),
      codigo_item     : $F('codigo_item'),
      quantidade      : $F('quantidade'),
      valor           : $F('valor'),
      data_inicial    : $F('data_inicial'),
      data_final      : $F('data_final'),
      nota_fiscal     : encodeURIComponent(tagString($F('nota_fiscal'))),
      processo        : encodeURIComponent(tagString($F('numero_processo')))
    };

    new AjaxRequest(
      sPathRPC,
      oParametro,
      function (oRetorno, lErro) {

        if (lErro) {

          alert(oRetorno.mensagem.urlDecode());
          return false;
        }
        limparCamposWindow(false);
        carregarExecucoes();
      }
    ).setMessage('Aguarde, salvando execução...').execute();
  }

  function validarFormulario() {

    if ($F('quantidade') == "") {

      alert(_M(MENSAGENS+'quantidade_obrigatoria'));
      return false;
    }

    var lServico = $('servico').value == "true";
    if ($F('quantidade') <= 0 && !lServico) {

      alert(_M(MENSAGENS+'quantidade_obrigatoria_menor_zero'));
      return false;
    }

    if ($F('valor') == "") {

      alert(_M(MENSAGENS+'valor_obrigatorio'));
      return false;
    }

    if ($F('valor') <= 0) {

      alert(_M(MENSAGENS+'valor_obrigatorio_menor_zero'));
      return false;
    }

    if ($F('data_inicial') == '') {

      alert(_M(MENSAGENS+'data_inicial_obrigatorio'));
      return false;
    }
    if ($F('data_final') == '') {

      alert(_M(MENSAGENS+'data_final_obrigatorio'));
      return false;
    }

    if (js_comparadata($F('data_final'), $F('data_inicial'), "<")) {

      alert(_M(MENSAGENS+'data_final_menor_data_inicial'));
      return false;
    }
    return true;
  }

  function excluir(iCodigoExecucao) {

    if (!confirm(_M(MENSAGENS+'confirma_exclusao'))) {
      return false;
    }

    var oParametro = {
      exec : 'excluirExecucao',
      codigo_execucao : iCodigoExecucao
    };

    new AjaxRequest(
      sPathRPC,
      oParametro,
      function (oRetorno, lErro) {

        if (lErro) {
          return alert(oRetorno.mensagem.urlDecode());
        }
        carregarExecucoes();
      }
    ).setMessage('Aguarde, excluindo execução...').execute();

  }

  function liberarManutencao(lLiberar) {

    var aButtons = document.getElementsByClassName('btnManutencao');
    for (var iButton = 0; iButton < aButtons.length; iButton++) {
      var oButton = aButtons[iButton];
      oButton.disabled = !lLiberar;
    }
  }

  function liberarAlteraExclui(lLiberar) {

    var aButtons = document.getElementsByClassName('btnAlteraExclui');
    for (var iButton = 0; iButton < aButtons.length; iButton++) {
      var oButton = aButtons[iButton];
      oButton.disabled = !lLiberar;
    }
  }

  function pesquisaAcordo(lMostrar) {

    var sQueryString = "func_acordo.php?funcao_js=parent.preencherCamposAcordo|ac16_sequencial|ac16_resumoobjeto&iTipoFiltro=4";
    if (!lMostrar) {

      if (oInputCodigoacordo.value == "") {

        oGridItens.clearAll(true);
        limparCampos();
        return false;
      }
      sQueryString = "func_acordo.php?descricao=true&pesquisa_chave="+oInputCodigoacordo.value+"&funcao_js=parent.validarCamposAcordo&iTipoFiltro=4";
    }

    js_OpenJanelaIframe(
      'top.corpo',
      'db_iframe_acordo',
      sQueryString,
      'Pesquisa de Acordo',
      lMostrar
    );
  }

  function preencherCamposAcordo(iCodigoAcordo, sResumoObjeto) {

    oInputCodigoacordo.value = iCodigoAcordo;
    oInputResumoObjeto.value = sResumoObjeto;
    db_iframe_acordo.hide();
    getItensAcordo();
  }

  function validarCamposAcordo(iCodigoAcordo, sResumoObjeto, lErro) {

    if (lErro) {

      oGridItens.clearAll(true);
      oInputCodigoacordo.value = "";
      oInputResumoObjeto.value = iCodigoAcordo;
      $('percentual').value = '';
      return false;
    }

    oInputCodigoacordo.value = iCodigoAcordo;
    oInputResumoObjeto.value = sResumoObjeto;
    getItensAcordo();
  }

  function limparCampos() {

    oInputCodigoacordo.value = "";
    oInputResumoObjeto.value = "";
    $('percentual').value    = '';
  }

  function limparCamposWindow(lLimparCodigoItem) {

    $('codigo_execucao').value = '';
    if (lLimparCodigoItem) {

      $('codigo_item').value = '';
      $('ordem_item').value = '';
    }
    $('quantidade').value      = '';
    $('valor').value           = '';
    $('data_inicial').value    = '';
    $('data_final').value      = '';
    $('nota_fiscal').value     = '';
    $('numero_processo').value = '';
  }

  function fecharJanela() {

    limparCamposWindow(true);
    liberarManutencao(true);
    getItensAcordo();
    oWindow.hide();
  }

</script>

