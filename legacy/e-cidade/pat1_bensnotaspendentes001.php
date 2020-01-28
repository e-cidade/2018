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

$clrotulo = new rotulocampo;
$clrotulo->label("t41_placa");
$clrotulo->label("t45_sequencial");
$clrotulo->label("t45_descricao");
$clrotulo->label("t52_descr");
$clrotulo->label("t52_dtaqu");
$clrotulo->label("t52_numcgm");
$clrotulo->label("t64_descr");
$clrotulo->label("t64_class");
$clrotulo->label("t04_sequencial");
$clrotulo->label("t44_vidautil");
$clrotulo->label("t56_situac");
$clrotulo->label("t70_descr");
$clrotulo->label("z01_nome");
$clrotulo->label("descrdepto");
?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBToogle.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/windowAux.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/classes/DBViewNotasPendentes.classe.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container">
      <form name="form-bem" method="post" action="">
        <fieldset>
          <legend>Inclusão de Bens</legend>

          <fieldset class="separator">
            <legend>Dados das Notas</legend>
            <table>
              <tr>
                <td>
                  <label class="bold" for="notas">Nota(s):</label>
                </td>
                <td>
                  <?php db_input("notas", 18, 1, true, "text", 3); ?>
                </td>
                <td>
                  <label class="bold" for="valor_notas">Valor Total:</label>
                </td>
                <td>
                  <?php db_input("valor_notas", 10, 1, true, "text", 3); ?>
                </td>
              </tr>
            </table>
          </fieldset>

          <fieldset class="separator">
            <legend>Informações do Bem</legend>
            <table>
              <tr>
                <td title="Quantidade">
                  <label class="bold" for="qunatidade">Quantidade:</label>
                </td>
                <td title="Quantidade">
                  <?php
                    $Squantidade = "Quantidade";
                    db_input("quantidade", 10, 1, true, "text", 1);
                  ?>
                </td>
              </tr>
              <tr>
                <td title="<?php echo $Tt41_placa; ?>">
                  <?php echo $Lt41_placa; ?>
                </td>
                <td>
                  <?php
                    $iOpcaoSequencialPlaca = 3;

                    $oBensParametroPlaca = BensParametroPlaca::getInstance();

                    /**
                     * Liberar para digitação sequencial da placa quando parametro de controle for 4 - sequencial digitado
                     */
                    if ($oBensParametroPlaca->getTipoConfiguracaoPlaca() == BensParametroPlaca::PLACA_SEQUENCIAL_DIGITADO) {
                      $iOpcaoSequencialPlaca = 1;
                    }

                  $Splaca = "Placa";
                    db_input('placa', 10, 1, true, "text", $iOpcaoSequencialPlaca, "onkeypress=\"return js_mask(event, '0-9')\"", "", "", "", 10);
                  ?>
                  <div class="text-right" style="float: right">
                    <label class="bold">Placa Impressa:</label>
                    <label id="impressa" class="bold">Não</label>
                  </div>
                </td>
              </tr>

              <tr>
                <td title="Data de Aquisição">
                  <label for="data_aquisicao" class="bold">Data de Aquisição:</label>
                </td>
                <td>
                  <?php db_input("data_aquisicao", 10, 1, true, 'text', 3); ?>
                </td>
              </tr>

              <tr>
                <td title="Descrição">
                  <label class="bold" for="descricao">Descrição:</label>
                </td>
                <td>
                  <?php db_input("descricao", 81, $It52_descr, true, 'text', 1, "", "", "", "", 100); ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tt64_class; ?>">
                  <label class="bold" for="classificacao"><?php db_ancora($Lt64_class, "pesquisa.classificacao.busca(true);", 1); ?></label>
                </td>
                <td>
                  <?php
                    db_input('codigo_classificacao', 10, "", true, 'hidden', 1);
                    db_input('classificacao', 10, $It64_class, true, 'text', 1, "onchange='pesquisa.classificacao.busca(false);'");
                    db_input('descricao_classificacao', 67, $It64_descr, true, 'text', 3, '');
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="<?php echo $Tt52_numcgm;?>">
                  <label class="bold" for="fornecedor"><?php echo $Lt52_numcgm; ?></label>
                </td>
                <td>
                  <?php
                    db_input('fornecedor', 10, $It52_numcgm, true, 'text', 3);
                    db_input('descricao_fornecedor', 67, $Iz01_nome, true, 'text', 3, '');
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="Descrição da Aquisição">
                  <label id="label_tipo_aquisicao" class="bold" for="tipo_aquisicao"><?php db_ancora("Tipo de Aquisição:", "pesquisa.aquisicao.busca(true);", 1); ?></label>
                </td>
                <td>
                  <?php
                    $Stipo_aquisicao = "Tipo de Aquisição";
                    db_input("tipo_aquisicao", 10, $It45_sequencial, true, 'text', 1, "onchange='pesquisa.aquisicao.busca(false);'");
                    db_input("descricao_aquisicao", 67, $It45_descricao, true, 'text', 3, '');
                  ?>
                </td>
              </tr>

              <tr style="display: none">
                <td title="Órgão">
                  <label class="bold" for="orgao">Órgão:</label>
                </td>
                <td>
                  <?php db_input("orgao", 81, "", true, 'text', 3); ?>
                </td>
              </tr>

              <tr style="display: none">
                <td>
                  <label class="bold" for="unidade">Unidade:</label>
                </td>
                <td>
                  <?php db_input("unidade", 81, "", true, 'text', 3); ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="Departamento">
                  <label class="bold" for="departamento"><?php db_ancora("Departamento:", "pesquisa.departamento.busca(true);", 1); ?></label>
                </td>
                <td>
                  <?php
                    $Sdepartamento = "Departamento";
                    db_input('departamento', 10, 1, true, 'text', 1, "onchange='pesquisa.departamento.busca(false);'");
                    db_input('descricao_departamento', 67, $Idescrdepto, true, 'text', 3, '');
                  ?>
                </td>
              </tr>

              <tr style="display: none">
                <td title="<?php echo $Tt52_dtaqu;?>">
                  <label class="bold" for="divisao">Divisão:</label>
                </td>
                <td>
                  <?php
                    db_select('divisao', array(), true, 1, "");
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="Convênio">
                  <label class="bold" for="convenio"><?php db_ancora("Convênio:","pesquisa.convenio.busca(true);", 1); ?></label>
                </td>
                <td nowrap>
                  <?php
                    $Sconvenio = "Convênio";
                    db_input("convenio", 10, $It04_sequencial, true, 'text', 1, "onchange='pesquisa.convenio.busca(false);'");
                    db_input("descricao_convenio", 67, '', true, 'text', 3, '');
                  ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="Situação">
                  <label class="bold" for="situacao"><?php db_ancora("Situação:","pesquisa.situacao.busca(true);",1); ?></label>
                </td>
                <td nowrap>
                  <?php
                    $Ssituacao = "Situação";
                    db_input("situacao", 10, $It56_situac, true, 'text', 1, " onchange='pesquisa.situacao.busca(false);'");
                    db_input("descricao_situacao",67,$It70_descr,true,'text',3,'');
                    db_input("tipo_inclui", 40, "0", true, "hidden", 3, "");
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>

          <fieldset class="separator">
            <legend class="bold">Dados Financeiros</legend>
            <table>
              <tr>
                <td>
                  <label class="bold" for="valor_aquisicao">Valor de Aquisição:</label>
                </td>
                <td>
                  <?php db_input("valor_aquisicao", 10, $It64_descr, true, 'text', 1, '', '', '', "text-align: right"); ?>
                </td>
                <td class="text-right">
                  <label class="bold" for="valor_residual">Valor Residual (%):</label>
                </td>
                <td>
                  <?php db_input("perc_residual", 5,$It64_descr,true,'text',1, '', '', '', "text-align: right"); ?>
                  <?php db_input("valor_residual",10,$It64_descr,true,'text',3, '', '', '', "text-align: right"); ?>
                </td>
                <td class="text-right">
                  <label class="bold" for="valor_depreciavel">Valor Depreciável:</label>
                </td>
                <td>
                  <?php db_input("valor_depreciavel",10,$It64_descr,true,'text',3,'', '', '', "text-align: right"); ?>
                </td>
              </tr>

              <tr>
                <td>
                  <label class="bold" for="valor_atual">Valor Atual:</label>
                </td>
                <td colspan="5">
                  <?php db_input("valor_atual", 10, $It64_descr, true, 'text', 3, '', '', '', "text-align: right"); ?>
                </td>
              </tr>

              <tr>
                <td nowrap title="Tipo de Depreciação">
                  <label class="bold" for="tipo_depreciacao"><?php db_ancora("Tipo de Depreciação:","pesquisa.depreciacao.busca(true);", 1); ?></label>
                </td>
                <td nowrap="nowrap" colspan="3">
                  <?php
                    $Stipo_depreciacao = "Tipo de Depreciação";
                    db_input("tipo_depreciacao", 10, 1, true, 'text', 1, "onchange='pesquisa.depreciacao.busca(false);'");
                    db_input("descricao_depreciacao", 35, $It64_descr, true, 'text', 3, '');
                  ?>
                </td>
                <td nowrap title="Vida útil do bem em anos." class="text-right">
                  <label class="bold" for="vida_util">Vida Útil:</label>
                </td>
                <td title="Vida útil do bem em anos.">
                  <?php
                    $Svida_util = "Vida Útil";
                    db_input('vida_util', 10, $It44_vidautil, true, 'text', 1, '');
                  ?>
                </td>
              </tr>
            </table>
          </fieldset>

          <fieldset class="separator">
            <legend>Observação</legend>
            <?php db_textarea('observacao', 3, 95, "", true, "text", 2, "rel=\"ignore-css\""); ?>
          </fieldset>
        </fieldset>

        <input name="lancar" type="button" id="lancar" value="Lançar" disabled/>
        <input name="pesquisa_notas" type="button" id="pesquisa_notas" value="Pesquisar Notas"/>
        <fieldset>
          <legend>Bens</legend>
          <div id="grid-bens"></div>
        </fieldset>
        <input name="salvar" type="button" id="salvar" value="Salvar" disabled/>
      </form>
    </div>
    <?php db_menu(); ?>
  </body>
  <script type="text/javascript">

    ;(function(exports) {

      const RPC = "pat1_bensnotaspendentes.RPC.php";
      const RPCConsulta = "pat1_bensnovo.RPC.php";
      const MENSAGENS = "patrimonial.patrimonio.pat1_bensnotaspendentes.";

      var oViewNotas  = new DBViewNotasPendentes("oViewNotas"),
          oGridBens   = new DBGrid("oGridBens"),
          iParametroPlaca = null,
          aItensLancados = [],
          oDadosNotas = {};

      oViewNotas.setHabilitarCheckbox(true);
      oViewNotas.setCallBackConfirmar(function(aLinhas) {

        if (!aLinhas.length) {
          return alert( _M(MENSAGENS + "notas_nao_selecionadas") );
        }

        var aNotasItem = aLinhas.map(function(aItem) {
          return aItem[8];
        });

        /**
         * Carrega os dados das notas selecionadas
         */
        new AjaxRequest("pat1_bensnovo.RPC.php", { exec : "getDadosItemNota", iCodigoItemNota : aNotasItem.join(',')}, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());
          }

          var lValido = oRetorno.aNotas.reduce(function(oNotaAnterior, oNotaAtual) {

            if (oNotaAnterior && oNotaAtual.pc01_codmater == oNotaAnterior.pc01_codmater
                              && oNotaAtual.e60_numcgm == oNotaAnterior.e60_numcgm
                              && oNotaAtual.e69_dtnota == oNotaAnterior.e69_dtnota ) {
              return oNotaAtual;
            }

            return false;
          });

          if (!lValido) {
            return alert(_M(MENSAGENS + "notas_incorretas"));
          }

          resetarFormulario();

          oDadosNotas = {
            notas : oRetorno.aNotas,
            aNotasItem : aNotasItem,
            fornecedor : oRetorno.aNotas[0].e60_numcgm,
            fornecedor_nome : oRetorno.aNotas[0].z01_nome,
            numero_notas : oRetorno.aNotas.map(function(oNota) {
                              return oNota.nota_fiscal;
                            }).join(", "),
            valorTotal : oRetorno.aNotas.reduce(function(iPrev, oNota) {
                            return iPrev + (new Number(oNota.e72_valor));
                          }, 0),
            data : oRetorno.aNotas[0].e69_dtnota.getDate()
          };

          oCampos.notas.value = oDadosNotas.numero_notas;
          oCampos.notas.title = oDadosNotas.numero_notas;
          oCampos.valor_notas.value = js_formatar(oDadosNotas.valorTotal, 'f', 2);
          oCampos.fornecedor.codigo.value = oDadosNotas.fornecedor;
          oCampos.fornecedor.descricao.value = oDadosNotas.fornecedor_nome;
          oCampos.data_aquisicao.value = oDadosNotas.data.getDateBR();

          oViewNotas.getWindowAux().hide();

          carregarDadosPlaca();
          liberaSalvar();

        }).setMessage("Aguarde, consultando notas.")
          .execute();
      });

      oViewNotas.show();

      oGridBens.setHeader(["Descrição", "Quantidade", "Valor", "Ações"]);
      oGridBens.setCellWidth(["64%", "13%", "13%", "10%"]);
      oGridBens.setCellAlign(["left", "center", "right", "center"]);
      oGridBens.setHeight(100);
      oGridBens.show($('grid-bens'));

      /**
       * Define os campos do formulario
       */
      var oCampos = {
        notas : $("notas"),
        valor_notas : $("valor_notas"),
        quantidade : $("quantidade"),
        placa : $("placa"),
        data_aquisicao : $("data_aquisicao"),
        descricao : $("descricao"),
        classificacao : {
          codigo : $("codigo_classificacao"),
          label : $("classificacao"),
          descricao : $("descricao_classificacao")
        },
        fornecedor : {
          codigo : $("fornecedor"),
          descricao : $("descricao_fornecedor")
        },
        aquisicao : {
          codigo : $("tipo_aquisicao"),
          descricao : $("descricao_aquisicao")
        },
        departamento : {
          codigo : $("departamento"),
          descricao : $("descricao_departamento")
        },
        divisao : {
          campo : $("divisao"),
          linha : $($("divisao").parentNode.parentNode)
        },
        orgao : {
          descricao : $("orgao"),
          linha : $($("orgao").parentNode.parentNode)
        },
        unidade : {
          descricao : $("unidade"),
          linha : $($("unidade").parentNode.parentNode)
        },
        convenio : {
          codigo : $("convenio"),
          descricao : $("descricao_convenio")
        },
        situacao : {
          codigo : $("situacao"),
          descricao : $("descricao_situacao")
        },
        depreciacao : {
          codigo : $("tipo_depreciacao"),
          descricao : $("descricao_depreciacao")
        },
        valor_aquisicao : $("valor_aquisicao"),
        perc_residual : $("perc_residual"),
        valor_residual : $("valor_residual"),
        valor_depreciavel : $("valor_depreciavel"),
        valor_atual : $("valor_atual"),
        vida_util : $("vida_util"),
        observacao : $("observacao")
      }

      /**
       * Consistência dos campos de valor
       */
      oCampos.valor_aquisicao.onfocus = function() {
        this.value = this.value.getNumber();
      }

      oCampos.valor_aquisicao.onblur = function() {

        if (isNaN(this.value.getNumber())) {
          this.value = 0;
        }

        this.value = js_formatar(this.value, 'f', 2);
      }

      oCampos.valor_aquisicao.oninput = function() {
        this.value = this.value.replace(/[^0-9\.]/g, '');
      }

      oCampos.valor_aquisicao.onchange = function() {
        calculaDadosFinanceiros();
      }

      oCampos.perc_residual.onfocus = function() {
        this.value = this.value.getNumber();
      }

      oCampos.perc_residual.onblur = function() {

        if (isNaN(this.value.getNumber())) {
          this.value = 0;
        }

        this.value = js_formatar(this.value, 'f', 2);
      }

      oCampos.perc_residual.oninput = function() {
        this.value = this.value.replace(/[^0-9\.]/g, '');
      }

      oCampos.perc_residual.onchange = function() {
        calculaDadosFinanceiros();
      }

      /**
       * Carrega os dados da placa
       */
      function carregarDadosPlaca() {

        new AjaxRequest(RPCConsulta, { exec : "carregaInclusao"}, function(oRetorno, lErro) {

          if (lErro) {

            alert(oRetorno.message.urlDecode());
            window.location.href = window.location.href;
            return false;
          }

          iParametroPlaca = oRetorno.dados.parametro;

          if (oRetorno.dados.parametro != 4 && oRetorno.dados.parametro != 2) {
            oCampos.placa.value = oRetorno.dados.t41_placa;
          }

          $("impressa").innerHTML = "Não";
          if (oRetorno.dados.lImpressa) {
            $("impressa").innerHTML = "Sim";
          }

        }).setMessage("Carregando dados da placa.")
          .execute();
      }

      /**
       * Carrega a placa pela classificação
       */
      function carregarPlacaClassificacao(sClassificacao) {

        var oParametros = {
          exec : "carregaPlacaClasse",
          iClasse : sClassificacao,
          iParametro : iParametroPlaca
        }

        new AjaxRequest(RPCConsulta, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());
          }

          (oRetorno.dados.parametro == 2) && (oCampos.placa.value = (oCampos.classificacao.label.value + '' + oRetorno.dados.t41_placa));

        }).setMessage("Carregando dados da placa.")
          .execute();
      }

      /**
       * Calcula os dados financeiros
       */
      function calculaDadosFinanceiros() {

        oCampos.valor_residual.value = '';
        oCampos.valor_depreciavel.value = '';
        oCampos.valor_atual.value = '';

        var nValor = oCampos.valor_aquisicao.value.getNumber(),
            nPerc = oCampos.perc_residual.value.getNumber();

        if (isNaN(nValor) || isNaN(nPerc)) {
          return false;
        }

        var nValorResidual = (nPerc/100)*nValor;

        oCampos.valor_depreciavel.value = js_formatar(nValor-nValorResidual, 'f', 2);
        oCampos.valor_atual.value = js_formatar(nValor, 'f', 2);
        oCampos.valor_residual.value = js_formatar(nValorResidual, 'f', 2);
      }

      function resetarFormulario() {

        clearForm();
        clearGridRows();
        bloqueiaSalvar();
        oDadosNotas = {};
        oCampos.notas.value = '';
        oCampos.valor_notas.value = '';
      }

      /**
       * Limpa os campos do formulario
       */
      function clearForm() {

        oCampos.quantidade.value = '';
        oCampos.placa.value = '';
        oCampos.data_aquisicao.value = '';
        oCampos.descricao.value = '';
        oCampos.classificacao.codigo.value = '';
        oCampos.classificacao.label.value = '';
        oCampos.classificacao.descricao.value = '';
        oCampos.fornecedor.codigo.value = '';
        oCampos.fornecedor.descricao.value = '';
        oCampos.aquisicao.codigo.value = '';
        oCampos.aquisicao.descricao.value = '';
        oCampos.departamento.codigo.value = '';
        oCampos.departamento.descricao.value = '';
        pesquisa.divisao.limpar();
        pesquisa.orgaounidade.limpar();
        pesquisa.aquisicao.habilitar();
        oCampos.convenio.codigo.value = '';
        oCampos.convenio.descricao.value = '';
        oCampos.situacao.codigo.value = '';
        oCampos.situacao.descricao.value = '';
        oCampos.depreciacao.codigo.value = '';
        oCampos.depreciacao.descricao.value = '';
        oCampos.valor_aquisicao.value = "0,00";
        oCampos.perc_residual.value = "0,00";
        oCampos.valor_residual.value = '';
        oCampos.valor_depreciavel.value = '';
        oCampos.valor_atual.value = '';
        oCampos.vida_util.value = '';
        oCampos.observacao.value = '';
      }

      /**
       * Limpa os itens da grid
       */
      function clearGridRows() {

        aItensLancados = [];
        oGridBens.clearAll(true);
      }

      function liberaSalvar() {

        $("lancar").disabled = false;
        $("salvar").disabled = false;
      }

      function bloqueiaSalvar() {

        $("lancar").disabled = true;
        $("salvar").disabled = true;
      }

      $("pesquisa_notas").observe("click", function() {
        oViewNotas.getWindowAux().show();
      });

      /**
       * Renderiza os bens na grid
       */
      function renderGrid() {

        oGridBens.clearAll(true);
        for (var iRow = 0; iRow < aItensLancados.length; iRow++) {

          var oButtonRemove = document.createElement("input");
          oButtonRemove.type = "button";
          oButtonRemove.id = "remover" + iRow;
          oButtonRemove.name = "remover" + iRow;
          oButtonRemove.value = "Remover";
          oButtonRemove.setAttribute("onclick", "js_removerBem(" + iRow + ")");

          oGridBens.addRow([
              undoTagString(aItensLancados[iRow].sDescricao),
              aItensLancados[iRow].iQuantidade,
              js_formatar(aItensLancados[iRow].nValorAquisicao, 'f', 2),
              oButtonRemove.outerHTML
            ]);
        }

        oGridBens.renderRows();
      }

      function js_removerBem(iBem) {

        if (confirm( _M(MENSAGENS + "confirmar_remover", { sDescricao : undoTagString(aItensLancados[iBem].sDescricao) }) )) {

          aItensLancados.splice(iBem, 1);
          renderGrid();

          if (!aItensLancados.length) {
            pesquisa.aquisicao.habilitar();
          }
        }
      }

      $("lancar").observe("click", function() {

        if (oDadosNotas.notas.length > 1 && aItensLancados.length > 0) {
          return alert( _M(MENSAGENS + "validacao_numero_notas_bens") );
        }

        var oBem = {
          iQuantidade        : oCampos.quantidade.value.getNumber(),
          sPlaca             : (iParametroPlaca == 2 ? oCampos.classificacao.label.value : ''),
          iSeqPlaca          : oCampos.placa.value,
          sDataAquisicao     : oDadosNotas.data.getDateBR(),
          sDescricao         : tagString(oCampos.descricao.value.trim()),
          iClassificacao     : oCampos.classificacao.codigo.value,
          iFornecedor        : oCampos.fornecedor.codigo.value,
          iTipoAquisicao     : oCampos.aquisicao.codigo.value,
          iDepartamento      : oCampos.departamento.codigo.value,
          iDivisao           : oCampos.divisao.campo.value,
          iConvenio          : oCampos.convenio.codigo.value,
          iSituacao          : oCampos.situacao.codigo.value,
          nValorAquisicao    : oCampos.valor_aquisicao.value.getNumber(),
          iVidaUtil          : oCampos.vida_util.value.getNumber(),
          nValorResidual     : oCampos.valor_residual.value.getNumber(),
          iCodigoDepreciacao : oCampos.depreciacao.codigo.value,
          sObservacao        : tagString(oCampos.observacao.value.trim())
        };

        if (isNaN(oBem.iQuantidade) || oBem.iQuantidade == 0) {
          return alert( _M(MENSAGENS + "quantidade_obrigatorio") );
        }

        if (empty(oBem.iSeqPlaca)) {
          return alert( _M(MENSAGENS + "seq_placa_obrigatorio") );
        }

        if (empty(oBem.sDescricao)) {
          return alert( _M(MENSAGENS + "descricao_obrigatorio") );
        }

        if (empty(oBem.iClassificacao)) {
          return alert( _M(MENSAGENS + "classificacao_obrigatorio") );
        }

        if (empty(oBem.iTipoAquisicao)) {
          return alert( _M(MENSAGENS + "tipo_aquisicao_obrigatorio") );
        }

        if (empty(oBem.iDepartamento)) {
          return alert( _M(MENSAGENS + "departamento_obrigatorio") );
        }

        if (empty(oBem.iSituacao)) {
          return alert( _M(MENSAGENS + "situacao_obrigatorio") );
        }

        if (isNaN(oBem.nValorAquisicao) || oBem.nValorAquisicao == 0) {
          return alert( _M(MENSAGENS + "valor_aquisicao_obrigatorio") );
        }

        if (isNaN(oBem.nValorResidual) || oBem.nValorResidual == 0) {
          return alert( _M(MENSAGENS + "valor_residual_obrigatorio") );
        }

        if (empty(oBem.iCodigoDepreciacao)) {
          return alert( _M(MENSAGENS + "tipo_depreciacao_obrigatorio") );
        }

        if (isNaN(oBem.iVidaUtil)) {
          return alert( _M(MENSAGENS + "vida_util_obrigatorio") );
        }

        if (empty(oBem.sObservacao)) {
          return alert( _M(MENSAGENS + "observacao_obrigatorio") );
        }

        if (oBem.nValorResidual >= oBem.nValorAquisicao) {
          return alert( _M(MENSAGENS + "residual_maior_aquisicao") );
        }

        aItensLancados.push(oBem);
        clearForm();

        oCampos.notas.value = oDadosNotas.numero_notas;
        oCampos.valor_notas.value = js_formatar(oDadosNotas.valorTotal, 'f', 2);
        oCampos.fornecedor.codigo.value = oDadosNotas.fornecedor;
        oCampos.fornecedor.descricao.value = oDadosNotas.fornecedor_nome;
        oCampos.data_aquisicao.value = oDadosNotas.data.getDateBR();

        pesquisa.aquisicao.desabilitar();
        oCampos.aquisicao.codigo.value = aItensLancados[aItensLancados.length-1].iTipoAquisicao;
        pesquisa.aquisicao.busca(false);

        carregarDadosPlaca();
        renderGrid();
      });

      $("salvar").observe("click", function() {

        var nValorBens = aItensLancados.reduce(function(iAnt, oBem) {
          return iAnt + (oBem.iQuantidade * oBem.nValorAquisicao);
        }, 0);

        if (oDadosNotas.valorTotal.toFixed(2) != nValorBens.toFixed(2)) {
          return alert( _M(MENSAGENS + "valor_bem_nota") );
        }

        var oParametros = {
          exec : "salvar",
          aNotas : oDadosNotas.aNotasItem,
          aBens : aItensLancados
        }

        new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message.urlDecode());
          }

          alert( _M(MENSAGENS + "bens_salvos", { sPlacas : oRetorno.aPlacas.join(", ") }) );
          resetarFormulario();

          oViewNotas.getWindowAux().show();
          oViewNotas.getNotasPendentes();

        }).setMessage("Aguarde, salvando dados.")
          .execute();
      });

      /**
       * Declara as funções de pesquisa
       */
      var pesquisa = {
        classificacao : {
          busca : function(lMostra) {

            var sFuncao = "func_clabens.php?analitica=true&funcao_js=parent.pesquisa.classificacao.";

            if (lMostra) {
              sFuncao += "preenche|t64_class|t64_descr|t64_codcla|t64_benstipodepreciacao|t46_descricao|t64_vidautil";
            } else {

              var sChave = oCampos.classificacao.label.value.replace(/[^0-9]/, '');

              if (sChave == '') {

                oCampos.classificacao.codigo.value = '';
                oCampos.classificacao.descricao.value = '';
                oCampos.classificacao.label.value = '';
                return false;
              }

              sFuncao += "completa&pesquisa_chave=" + sChave
            }

            js_OpenJanelaIframe( 'CurrentWindow.corpo',
                                 'db_iframe_clabens',
                                 sFuncao,
                                 'Pesquisa de Classificação',
                                 lMostra );
          },
          preenche : function(sLabel, sDescricao, iCodigo, iCodigoDepreciacao, sDescricaoDepreciacao, iVidaUtil) {

            oCampos.classificacao.label.value     = sLabel;
            oCampos.classificacao.descricao.value = sDescricao;
            oCampos.classificacao.codigo.value    = iCodigo;

            oCampos.depreciacao.codigo.value    = iCodigoDepreciacao;
            oCampos.depreciacao.descricao.value = sDescricaoDepreciacao;
            oCampos.vida_util.value             = iVidaUtil;

            db_iframe_clabens.hide();
            (iParametroPlaca == 2) && carregarPlacaClassificacao(oCampos.classificacao.label.value);
          },
          completa : function(sDescricao, lErro, iCodigo, iCodigoDepreciacao, sDescricaoDepreciacao, iVidaUtil) {

            oCampos.classificacao.descricao.value = sDescricao;

            if (!lErro) {

              oCampos.classificacao.descricao.value = sDescricao;
              oCampos.classificacao.codigo.value    = iCodigo;
              oCampos.depreciacao.codigo.value      = iCodigoDepreciacao;
              oCampos.depreciacao.descricao.value   = sDescricaoDepreciacao;
              oCampos.vida_util.value               = iVidaUtil;

              (iParametroPlaca == 2) && carregarPlacaClassificacao(oCampos.classificacao.label.value);
            }

            if (lErro) {

              oCampos.classificacao.codigo.value    = '';
              oCampos.classificacao.label.value     = '';
            }
          }
        },
        aquisicao : {
          busca : function(lMostra) {

            var sFuncao = "func_benstipoaquisicao.php?funcao_js=parent.pesquisa.aquisicao.";

            if (lMostra) {
              sFuncao += "preenche|t45_sequencial|t45_descricao";
            } else {

              if (oCampos.aquisicao.codigo.value == '') {

                oCampos.aquisicao.descricao.value = '';
                return false;
              }

              sFuncao += "completa&pesquisa_chave=" + oCampos.aquisicao.codigo.value;
            }

            js_OpenJanelaIframe( 'CurrentWindow.corpo',
                                 'db_iframe_aquisicao',
                                 sFuncao,
                                 "Pesquisa de Tipo de Aquisição",
                                 lMostra );
          },
          preenche : function(iCodigo, sDescricao) {

            oCampos.aquisicao.codigo.value = iCodigo;
            oCampos.aquisicao.descricao.value = sDescricao;

            db_iframe_aquisicao.hide();
          },
          completa : function(sDescricao, lErro) {

            oCampos.aquisicao.descricao.value = sDescricao;

            if (lErro) {
              oCampos.aquisicao.codigo.value = '';
            }
          },
          desabilitar : function() {

            oCampos.aquisicao.codigo.readOnly = true;
            oCampos.aquisicao.codigo.classList.add("readonly");

            var oLabel = $("label_tipo_aquisicao");

            oLabel.children[0].style.display = "none";
            oLabel.appendChild(document.createTextNode(oLabel.children[0].textContent));
          },
          habilitar : function() {

            oCampos.aquisicao.codigo.readOnly = false;
            oCampos.aquisicao.codigo.classList.remove("readonly");

            var oLabel = $("label_tipo_aquisicao"),
                a = oLabel.children[0];
            a.style.display = "block";
            oLabel.innerHTML = a.outerHTML;
          }
        },
        departamento : {
          busca : function(lMostra) {

            var sFuncao = "func_db_depart.php?funcao_js=parent.pesquisa.departamento.";

            if (lMostra) {
              sFuncao += "preenche|coddepto|descrdepto";
            } else {

              if (oCampos.departamento.codigo.value == '') {

                oCampos.departamento.descricao.value = '';
                pesquisa.divisao.limpar();
                pesquisa.orgaounidade.limpar();
                return false;
              }

              sFuncao += "completa&pesquisa_chave=" + oCampos.departamento.codigo.value;
            }

            js_OpenJanelaIframe( 'CurrentWindow.corpo',
                                 'db_iframe_db_depart',
                                 sFuncao,
                                 "Pesquisa de Departamentos",
                                 lMostra );
          },
          preenche : function(iCodigo, sDescricao) {

            oCampos.departamento.codigo.value    = iCodigo;
            oCampos.departamento.descricao.value = sDescricao;

            db_iframe_db_depart.hide();

            pesquisa.divisao.carregaDados(iCodigo);
            pesquisa.orgaounidade.carregaDados(iCodigo);
          },
          completa : function(sDescricao, lErro) {

            oCampos.departamento.descricao.value = sDescricao;

            if (lErro) {

              oCampos.departamento.codigo.value = '';
              pesquisa.divisao.limpar();
              pesquisa.orgaounidade.limpar();
            } else {

              pesquisa.divisao.carregaDados(oCampos.departamento.codigo.value);
              pesquisa.orgaounidade.carregaDados(oCampos.departamento.codigo.value);
            }
          }
        },
        divisao : {
          carregaDados : function(iDepartamento) {

            new AjaxRequest(RPCConsulta, { exec : "buscaDivisao", departamento : iDepartamento }, function(oRetorno, lErro) {

              pesquisa.divisao.limpar();

              if (lErro) {
                return alert(oRegistro.message.urlDecode());
              }

              if (oRetorno.departamento.length > 0) {

                oCampos.divisao.campo.appendChild(new Option("Selecione", '', true));

                for (var i = 0; i < oRetorno.departamento.length; i++) {

                  var oOption = new Option(oRetorno.departamento[i].t30_descr.urlDecode(), oRetorno.departamento[i].t30_codigo);
                  oCampos.divisao.campo.appendChild(oOption);
                }

                oCampos.divisao.linha.show();
              }

            }).setMessage("Carregando divisões.")
              .execute();
          },
          limpar : function() {

            oCampos.divisao.campo.options.length = 0;
            oCampos.divisao.linha.hide();
          }
        },
        orgaounidade : {
          carregaDados : function(iDepartamento) {

            new AjaxRequest(RPCConsulta, { exec : "buscaOrgaoUnidade", departamento : iDepartamento }, function(oRetorno, lErro) {

              pesquisa.orgaounidade.limpar();

              if (lErro) {
                return alert(oRegistro.message.urlDecode());
              }

              if (oRetorno.dados.libera == "t") {

                oCampos.orgao.descricao.value   = oRetorno.dados.o40_descr.urlDecode();
                oCampos.unidade.descricao.value = oRetorno.dados.o41_descr.urlDecode();

                oCampos.orgao.linha.show();
                oCampos.unidade.linha.show();
              }

            }).setMessage("Carregando Órgão e Unidade.")
              .execute();
          },
          limpar : function() {

            oCampos.orgao.descricao.value   = '';
            oCampos.unidade.descricao.value = '';

            oCampos.orgao.linha.hide();
            oCampos.unidade.linha.hide();
          }
        },
        convenio : {
          busca : function(lMostra) {

            var sFuncao = "func_benscadcedente.php?funcao_js=parent.pesquisa.convenio.";

            if (lMostra) {
              sFuncao += "preenche|t04_sequencial|z01_nome";
            } else {

              if (oCampos.convenio.codigo.value == '') {

                oCampos.convenio.descricao.value = '';
                return false;
              }

              sFuncao += "completa&pesquisa_chave=" + oCampos.convenio.codigo.value;
            }

            js_OpenJanelaIframe( 'CurrentWindow.corpo',
                                 'db_iframe_benscadcedente',
                                 sFuncao,
                                 "Pesquisa de Convênio",
                                 lMostra );
          },
          preenche : function(iCodigo, sDescricao) {

            oCampos.convenio.codigo.value    = iCodigo;
            oCampos.convenio.descricao.value = sDescricao;

            db_iframe_benscadcedente.hide();
          },
          completa : function(sDecricao, lErro) {

            oCampos.convenio.descricao.value = sDecricao;

            if (lErro) {
              oCampos.convenio.codigo.value = '';
            }
          }
        },
        situacao : {
          busca : function(lMostra) {

            var sFuncao = "func_situabens.php?funcao_js=parent.pesquisa.situacao.";

            if (lMostra) {
              sFuncao += "preenche|t70_situac|t70_descr";
            } else {

              if (oCampos.situacao.codigo.value == '') {

                oCampos.situacao.descricao.value = '';
                return false;
              }

              sFuncao += "completa&pesquisa_chave=" + oCampos.situacao.codigo.value;
            }

            js_OpenJanelaIframe( 'CurrentWindow.corpo',
                                 'db_iframe_situabens',
                                 sFuncao,
                                 "Pesquisa de Situação",
                                 lMostra );
          },
          preenche : function(iCodigo, sDescricao) {

            oCampos.situacao.codigo.value    = iCodigo;
            oCampos.situacao.descricao.value = sDescricao;
            db_iframe_situabens.hide();
          },
          completa : function(sDescricao, lErro) {

            oCampos.situacao.descricao.value = sDescricao;

            if (lErro) {
              oCampos.situacao.codigo.value = '';
            }
          }
        },
        depreciacao : {
          busca : function(lMostra) {

            var sFuncao = "func_benstipodepreciacao.php?limita=true&funcao_js=parent.pesquisa.depreciacao.";

            if (lMostra) {
              sFuncao += "preenche|t46_sequencial|t46_descricao";
            } else {

              if (oCampos.depreciacao.codigo.value == '') {

                oCampos.depreciacao.descricao.value = '';
                return false;
              }

              sFuncao += "completa&pesquisa_chave=" + oCampos.depreciacao.codigo.value;
            }

            js_OpenJanelaIframe( 'CurrentWindow.corpo',
                                 'db_iframe_deprecBem',
                                 sFuncao,
                                 "Pesquisa de Tipo de Depreciação",
                                 lMostra );
          },
          preenche : function(iCodigo, sDescricao) {

            oCampos.depreciacao.codigo.value    = iCodigo;
            oCampos.depreciacao.descricao.value = sDescricao;
            db_iframe_deprecBem.hide();
          },
          completa : function(sDescricao, lErro) {

            oCampos.depreciacao.descricao.value = sDescricao;

            if (lErro) {
              oCampos.depreciacao.codigo.value = '';
            }
          }
        }
      }

      exports.oViewNotas    = oViewNotas;
      exports.oGridBens     = oGridBens;
      exports.pesquisa      = pesquisa;
      exports.js_removerBem = js_removerBem;
    })(this);

  </script>
</html>
