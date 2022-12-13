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
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

define('MENSAGEM', 'patrimonial.compras.com4_aberturaregistroprecoporvalor.');
define('ABERTURA_INCLUSAO',  '1');
define('ABERTURA_ALTERACAO', '2');
define('ABERTURA_ANULACAO',  '3');

$oRotulo = new rotulocampo();
$oRotulo->label('pc11_vlrun');
$Svalor = "Valor";
$oGet = db_utils::postMemory($_GET);



?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <script type="text/javascript" src="scripts/strings.js"></script>
    <script type="text/javascript" src="scripts/prototype.js"></script>
    <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
    <link href="estilos/windowAux.css" rel="stylesheet" type="text/css">
    <style>
      .textareaItem {
        width: 100%;
        height: 60px;
      }
    </style>
  </head>
  <body style="background-color: #CCCCCC; margin-top: 25px;">

    <div class="container" id="ctnContainer">
      <div id="ctnDadosSolicitacao">
        <fieldset style="width: 600px;">
          <legend class="bold">
            <?php
            echo _M(MENSAGEM.'label_aba_DadosDaAbertura');
            ?>
          </legend>
          <table style="width: 100%;">
            <tr>
              <td class="bold" style="width: 180px;" id="tdAncoraAbertura">
                <?php
                db_ancora('Código: ', 'pesquisaAbertura(true)', 1);
                ?>
              </td>
              <td>
                <?php
                db_input('codigo_solicitacao', 10, 1, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td class="bold">Data de Vigência:</td>
              <td>
                <?php
                db_inputdata('data_inicial', '', '', '', true, 'text', 1);
                echo " <b>até</b> ";
                db_inputdata('data_final', '', '', '', true, 'text', 1);
                ?>
              </td>
            </tr>
            <tr>
              <td class="bold">Disponibilizar para Utilização:</td>
              <td>
                <?php
                $aLiberado = array('f' => 'Não', 't' => 'Sim');
                db_select('liberado', $aLiberado, true, 1);
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <fieldset>
                  <legend class="bold">Resumo</legend>
                  <label for="resumo">
                    <textarea id="resumo" style="width: 100%; height: 60px;"></textarea>
                  </label>
                </fieldset>
              </td>
            </tr>
          </table>
        </fieldset>
        <p align="center">
          <input type="button" id="btnSalvarAbertura" value="Salvar" />
        </p>
      </div>

      <!-- INICIO ABA ITEM -->
      <div id="ctnDadosItem">
        <fieldset>
        <fieldset style="width: 600px;">
          <legend class="bold">Dados dos Itens</legend>
          <table width="100%">
            <tr>
              <td class="bold" style="width: 55px;">
                <div id="labelServico" style="display: none;">
                  Serviço:
                </div>

                <div id="ancoraServico">
                <?php
                db_ancora('Serviço: ', 'pesquisaServico(true);', 1);
                ?>
                </div>
              </td>
              <td>
                <?php
                $Scodigo_servico = "Serviço";
                db_input('codigo_item_solicitacao', 10, 1, true, 'hidden', 3);
                db_input('codigo_servico', 10, 1, true, 'text', 1, 'onchange="pesquisaServico(false);"');
                db_input('descricao_servico', 59, 1, true, 'text', 3);
                ?>
              </td>
            </tr>
            <tr>
              <td class="bold">Valor:</td>
              <td>
                <?php
                db_input('valor', 10, $Ipc11_vlrun, true, 'text', 1);
                ?>
                <input type="button" id="btnMaisInformacoes" value="Mais Informações" />
              </td>
            </tr>
          </table>
          <div id="ctnMaisInformacoes" style="display: none;">
            <table width="100%" >
              <tr>
                <td colspan="2">
                  <fieldset id="fieldsetResumoItem">
                    <legend class="bold">Resumo</legend>
                    <label>
                      <textarea id="sResumoItem" class="textareaItem"></textarea>
                    </label>
                  </fieldset>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <fieldset id="fieldsetJustificativaItem">
                    <legend class="bold">Justificativa</legend>
                    <label>
                      <textarea id="sJustificativaItem" class="textareaItem"></textarea>
                    </label>
                  </fieldset>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <fieldset id="fieldsetPagamentoItem">
                    <legend class="bold">Informações de Pagamento</legend>
                    <label>
                      <textarea id="sPagamentoItem" class="textareaItem"></textarea>
                    </label>
                  </fieldset>
                </td>
              </tr>
              <tr>
                <td colspan="2">
                  <fieldset id="fieldsetPrazoItem">
                    <legend class="bold">Prazo</legend>
                    <label>
                      <textarea id="sPrazoItem" class="textareaItem"></textarea>
                    </label>
                  </fieldset>
                </td>
              </tr>
            </table>
            <p align="center">
              <input type="button" id=btnFecharJanela value="Salvar" onclick='oWindowInformacoes.hide();'/>
              <input type="button" id=btnLimparInformacoes value="Limpar" onclick="limparMaisInformacoes();"/>
            </p>
          </div>
        </fieldset>
        <p align="center">
          <input type="button" id="btnAdicionarItem" value="Incluir" />
        </p>
        <fieldset style="width: '100%";>
          <legend class="bold">Itens Adicionados</legend>
          <div id="ctnGridItens"></div>
        </fieldset>
      </div>
      <!-- FIM ABA ITEM -->
    </div>

    <?php
    db_menu(
      db_getsession("DB_id_usuario"),
      db_getsession("DB_modulo"),
      db_getsession("DB_anousu"),
      db_getsession("DB_instit")
    );
    ?>

  <script>

    const MENSAGEM         = 'patrimonial.compras.com4_aberturaregistroprecoporvalor.';
    var oWindowInformacoes = "";
    var sUrlRPC            = 'com4_aberturaregistroprecoporvalor.RPC.php';
    var oCodigoSolicitacao = $('codigo_solicitacao');
    var oDataInicial       = $('data_inicial');
    var oDataFinal         = $('data_final');
    var oResumo            = $('resumo');
    var oLiberado          = $('liberado');
    var oMaterial          = $('codigo_servico');
    var oMaterialDescricao = $('descricao_servico');
    var oValor             = $('valor');
    var oBotaoInformacoes  = $('btnMaisInformacoes');
    var oBotaoSalvarItem   = $('btnAdicionarItem');
    var oGet = js_urlToObject();

    oBotaoInformacoes.disabled = true;

    /**
     * Busca os itens vinculados na solicitacao e os adiciona na grid
     */
    function getItensVinculados() {

      var oParametro = {
        'exec'    : 'getItens',
        'iCodigoSolicitacao' : oCodigoSolicitacao.value
      };

      new AjaxRequest(sUrlRPC, oParametro,
        function (oRetorno, lErro) {

          oGridItem.clearAll(true);
          oRetorno.aItens.each(
            function (oItem, iIndice) {

              var sBotaoAlterar = "<input type='button' value='A' onclick='carregarInformacoesItem("+oItem.codigo_item+", "+oItem.possuiEstimativa+");'>";
              var sBotaoExcluir = "<input type='button' value='E' onclick='excluirItem("+oItem.codigo_item+", "+oItem.possuiEstimativa+");'>";
              var aLinha = [];
              aLinha[0]  = oItem.descricao.urlDecode();
              aLinha[1]  = js_formatar(oItem.valor, 'f');
              aLinha[2]  = sBotaoAlterar+" "+sBotaoExcluir;
              aLinha[3]  = oItem.codigo_servico;
              oGridItem.addRow(aLinha);
            }
          );
          oGridItem.renderRows();
        }
      ).setMessage(_M(MENSAGEM+"carregar_itens_solicitacao")).execute();
    }

    /**
     * Carrega as informações do item para a alteração dos mesmos
     */
    function carregarInformacoesItem(iCodigoItemSolicitacao, lEstimativa) {

      if (lEstimativa) {

        alert(_M(MENSAGEM+'possui_manifestacao_alterar'));
        return false;
      }

      var oParametro = {
        'exec' : 'getInformacoesItem',
        'iCodigoItemSolicitacao' : iCodigoItemSolicitacao
      };

      new AjaxRequest(sUrlRPC, oParametro,
        function (oRetorno, lErro) {

          $('codigo_item_solicitacao').value = oRetorno.oItem.codigo;
          oMaterial.value                    = oRetorno.oItem.codigo_material;
          oMaterialDescricao.value           = oRetorno.oItem.descricao_material.urlDecode();
          oValor.value                       = oRetorno.oItem.valor;
          $('sResumoItem').value             = oRetorno.oItem.resumo.urlDecode();
          $('sJustificativaItem').value      = oRetorno.oItem.justificativa.urlDecode();
          $('sPagamentoItem').value          = oRetorno.oItem.pagamento.urlDecode();
          $('sPrazoItem').value              = oRetorno.oItem.prazo.urlDecode();
          oBotaoInformacoes.disabled         = false;
          oBotaoSalvarItem.value = 'Alterar';

          document.getElementById('labelServico').style.display  = '';
          document.getElementById('ancoraServico').style.display = 'none';
          oMaterial.className = 'readonly';
          oMaterial.disabled  = true;
        }
      ).setMessage(_M(MENSAGEM+"carregando_informacao_item")).execute();
    }

    /**
     * Exclui um item da solicitacao
     */
    function excluirItem(iCodigoItemSolicitacao, lEstimativa) {

      if (lEstimativa){

        alert(_M(MENSAGEM+'possui_manifestacao_excluir'));
        return false;
      }

      if (!confirm(_M(MENSAGEM+'confirma_exclusao'))) {
        return false;
      }
      var oParametro = {
        'exec' : 'excluirItem',
        'iCodigoItemSolicitacao' : iCodigoItemSolicitacao,
        'iCodigoSolicitacao' : oCodigoSolicitacao.value
      };
      new AjaxRequest(sUrlRPC, oParametro,
        function (oRetorno, lErro) {

          alert(oRetorno.mensagem.urlDecode());
          limparDadosItem();
          limparMaisInformacoes();
          getItensVinculados();
        }
      ).setMessage('Aguarde, excluindo item...').execute();
    }

    /**
     * Salva os dados da abertura
     */
    $('btnSalvarAbertura').observe('click',
      function () {

        if (oDataInicial.value == '') {
          return alert(_M(MENSAGEM+'informe_data_inicial'));
        }

        if (oDataFinal.value == '') {
          return alert(_M(MENSAGEM+'informe_data_final'));
        }

        if (js_comparadata(oDataInicial.value, oDataFinal.value, '>')) {
          return alert(_M(MENSAGEM+'conflito_data'));
        }

        if (oResumo.value.trim() == '') {
          return alert(_M(MENSAGEM+'resumo_obrigatorio'));
        }

        var oParametro = {
          'exec'      : 'salvarAbertura',
          'iCodigo'   : oCodigoSolicitacao.value,
          'dtInicial' : oDataInicial.value,
          'dtFinal'   : oDataFinal.value,
          'lLiberado' : oLiberado.value,
          'sResumo'   : encodeURIComponent(tagString(oResumo.value))
        };

        new AjaxRequest(sUrlRPC, oParametro,
          function (oRetorno, lErro) {

            alert(oRetorno.mensagem.urlDecode());
            if (!lErro) {
              oCodigoSolicitacao.value = oRetorno.iCodigoSolicitacao;
              oAbaItem.getSeletor().click();
            }
          }
        ).setMessage(_M(MENSAGEM+'salvando_informacoes_abertura')).execute();
      }
    );

    oBotaoSalvarItem.observe('click',
      function () {


        if (oMaterial.value == "") {
          return alert(_M(MENSAGEM+'servico_obrigatorio'));
        }

        if (oValor.value == "") {
          return alert(_M(MENSAGEM+'valor_obrigatorio'));
        }

        var lPararInclusao = false;
        if ($F('codigo_item_solicitacao') == "") {

          oGridItem.aRows.each(
            function (oRow, iIndice) {

              if (oRow.aCells[3].getValue() == oMaterial.value) {

                lPararInclusao = true;
                return alert(_M(MENSAGEM+'servico_ja_incluso'));
              }
            }
          );
          if (lPararInclusao) {
            return false;
          }
        }

        var oParametro = {
          'exec'           : 'salvarItem',
          'iCodigoSolicitacao' : oCodigoSolicitacao.value,
          'iCodigoItemSolicitacao' : $F('codigo_item_solicitacao'),
          'iCodigoItem'    : oMaterial.value,
          'nValor'         : oValor.value,
          'sResumo'        : encodeURIComponent(tagString($F('sResumoItem'))),
          'sJustificativa' : encodeURIComponent(tagString($F('sJustificativaItem'))),
          'sPagamento'     : encodeURIComponent(tagString($F('sPagamentoItem'))),
          'sPrazo'         : encodeURIComponent(tagString($F('sPrazoItem')))
        };

        new AjaxRequest(sUrlRPC, oParametro,
          function (oRetorno, lErro) {

            alert(oRetorno.mensagem.urlDecode());
            oBotaoSalvarItem.value = 'Incluir';
            getItensVinculados();
            limparDadosItem();
            limparMaisInformacoes();

            document.getElementById('labelServico').style.display = 'none';
            document.getElementById('ancoraServico').style.display = '';
            oMaterial.className = '';
            oMaterial.disabled  = false;
          }
        ).setMessage(_M(MENSAGEM+'salvando_item')).execute();
      }
    );

    oBotaoInformacoes.observe('click',
      function() {

        var oMaisInformacoes = $('ctnMaisInformacoes');

        if (oWindowInformacoes == '') {

          oWindowInformacoes = new windowAux('oWindowInformacoes', 'Informações de ' + oMaterialDescricao.value, 500, 500);
          oWindowInformacoes.setContent(oMaisInformacoes);
          oMaisInformacoes.style.display = '';
          oWindowInformacoes.allowCloseWithEsc(true);
          oWindowInformacoes.show();
        } else {
          oWindowInformacoes.setTitle('Informações de ' + oMaterialDescricao.value);
          oWindowInformacoes.show();
        }
      }
    );

    function getDadosSolicitacao() {

      var oParametro = {
        'exec' : 'getDadosSolicitacao',
        'iCodigoSolicitacao' : oCodigoSolicitacao.value
      };

      new AjaxRequest(sUrlRPC, oParametro,
        function (oRetorno, lErro) {

          oDataInicial.value = oRetorno.oSolicitacao.dtInicial;
          oDataFinal.value   = oRetorno.oSolicitacao.dtFinal;
          oResumo.value      = oRetorno.oSolicitacao.sResumo.urlDecode();
          oLiberado.value    = oRetorno.oSolicitacao.lLiberado ? 't' : 'f';

        }
      ).setMessage(_M(MENSAGEM+'carregando_informacoes_solicitacao')).execute();
    }


    function limparDadosItem() {

      oValor.value = '';
      $('codigo_item_solicitacao').value = '';
      oMaterial.value = '';
      oMaterialDescricao.value = '';
      oBotaoInformacoes.disabled = true;
      oMaterial.className = '';
      oMaterial.readOnly  = '';
      oMaterial.disabled  = false;
      document.getElementById('labelServico').style.display  = 'none';
      document.getElementById('ancoraServico').style.display = '';
    }

    function limparMaisInformacoes() {

      $('sResumoItem').value = '';
      $('sJustificativaItem').value = '';
      $('sPagamentoItem').value = '';
      $('sPrazoItem').value = '';
    }


    function start() {

      oAba     = new DBAbas($('ctnContainer'));
      oAbaAbertura = oAba.adicionarAba('Abertura', $('ctnDadosSolicitacao'), true);
      oAbaItem     = oAba.adicionarAba('Itens', $('ctnDadosItem'), false);
      fCallBackAbaItem = oAbaItem.getSeletor().onclick;
      oAbaItem.getSeletor().onclick = function() {

        if (oCodigoSolicitacao.value == "") {
          return alert(_M(MENSAGEM+"liberar_aba_item"));
        }
        if (oGet.opcao == 3) {
          return false;
        }
        fCallBackAbaItem();
        getItensVinculados();
      };
      $('liberado').style.width = '100px';
      oGridItem = new DBGrid('oGridItem');
      oGridItem.nameInstance = 'oGridItem';
      oGridItem.setHeader(['Descrição', 'Valor', 'Ação', 'CodigoMaterial']);
      oGridItem.setCellAlign(['left', 'right', 'center', 'center']);
      oGridItem.setCellWidth(['65%', '25%', '10%', '0%']);
      oGridItem.aHeaders[3].lDisplayed = false;
      oGridItem.show($('ctnGridItens'));


      switch (oGet.opcao) {

        case '1':
          $('tdAncoraAbertura').innerHTML = "Código:";
          break;

        case '2':
          pesquisaAbertura();
          break;

        case '3':

          bloquearCampos();
          pesquisaAbertura();
          break;

        default:
      }
    }

    function pesquisaAbertura() {

      js_OpenJanelaIframe(
        'top.corpo',
        'db_iframe_solicitaregistropreco',
        'func_solicitaregistropreco.php?formacontrole=2&funcao_js=parent.preencheAbertura|0|1&anuladas=1&estimativas=1',
        'Pesquisa de Abertura de Registro de Preço por Valor',
        true
      );
    }

    function preencheAbertura(iCodigoAbertura, iCodigoSolicitacao) {

      oCodigoSolicitacao.value = iCodigoSolicitacao;
      db_iframe_solicitaregistropreco.hide();
      getDadosSolicitacao();
    }

    function pesquisaServico(lMostra) {

      var sCaminho = 'func_pcmatersolicita.php?lServico=true&funcao_js=parent.preencheServico|pc01_codmater|pc01_descrmater';
      if ( ! lMostra) {

        if (oMaterial.value == "") {
          limparDadosItem();
          return false;
        }
        sCaminho = "func_pcmatersolicita.php?lServico=true&pesquisa_chave="+oMaterial.value+"&funcao_js=parent.completaServico";
      }

      js_OpenJanelaIframe('top.corpo',
        'db_iframe_pcmater',
        sCaminho,
        'Pesquisa de Serviços',
        lMostra
      );
    }

    function completaServico(sDescricao, lErro) {

      oMaterialDescricao.value = sDescricao;
      oBotaoInformacoes.disabled = false;
      if (lErro) {

        oMaterial.value = '';
        oBotaoInformacoes.disabled = true;
        return false;
      }
    }

    function preencheServico(iCodigo, sDescricao) {

      oBotaoInformacoes.disabled = false;
      oMaterial.value = iCodigo;
      oMaterialDescricao.value = sDescricao;
      db_iframe_pcmater.hide();
    }

    function bloquearCampos() {

      oDataInicial.className = 'readonly';
      oDataInicial.disabled  = true;

      oDataFinal.className = 'readonly';
      oDataFinal.disabled  = true;

      oLiberado.className = 'readonly';
      oLiberado.disabled  = true;

      oResumo.className = 'readonly';
      oResumo.disabled  = true;
    }

    start();
  </script>
  </body>
</html>

