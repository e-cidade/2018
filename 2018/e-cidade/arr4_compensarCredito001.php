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

$clcgm = new cl_cgm;
$clcgm->rotulo->label();

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load(array(
      'scripts.js',
      'prototype.js',
      'strings.js',
      'datagrid.widget.js',
      'AjaxRequest.js',
      'Collection.widget.js',
      'DatagridCollection.widget.js',
      'estilos.css',
      'grid.style.css'
    ));
  ?>
</head>
<body class="body-default" onload="js_pesquisaAbatimento()">
  <div class="container">
    <form name="form1" method="POST">
      <fieldset>
        <legend>Compensação</legend>
        <table>
          <tr>
           <td>
             <?php db_ancora("Crédito:", "js_pesquisaAbatimento();", 1); ?>
           </td>
           <td>
             <?php db_input("abatimento", 10, "1", true, 'text', 3); ?>
           </td>
            <td colspan=4 style="visibility:hidden;" id="MI">
              <?php db_ancora('Consultar Origens do Crédito', "js_consultaOrigemCredito()", 1, ''); ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="saldo" class="bold">Saldo Disponível:</label>
            </td>
            <td>
              <?php db_input("saldo", 10, "1", true, 'text', 3); ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="opcao" class="bold">Operação:</label>
            </td>
            <td>
              <select name="operacao" id="opcao">
                <option value="1">Compensação</option>
                <option value="2">Devolução</option>
              </select>
            </td>
          </tr>

          <tr id="regraCompensacao">
            <td>
              <label for="regra_compensacao" class="bold">Regra de Compensação:</label>
            </td>
            <td>
              <select name="regra_compensacao" id="regra_compensacao">
                <option value="1">Valor Proporcional</option>
                <option value="2">Data de Vencimento</option>
              </select>
            </td>
          </tr>
        </table>

        <fieldset class="separator">
          <legend>Destino</legend>
          <table>
            <tr>
              <td title="<?=$Tz01_nome?>">
                <label for="z01_numcgm" class="bold">Proprietário:</label>
              </td>
              <td>
                <?php
                  db_input('z01_numcgm', 7, $Iz01_numcgm, true, 'text', 3, '');
                  db_input('z01_nome', 40, 0, true, 'text', 3, "", "z01_nome");
                ?>
              </td>
            </tr>

            <tr id="grid">
              <td colspan="2">
                <div id="oGridDebitos"></div>
                <input type="button" id="adicionar-debito" disabled="disabled" value="Adicionar débito" style="display:block; float: right; margin: 5px;">
                <input type="button" id="remover-selecionados" disabled="disabled" value="Remover selecionados" style="display:block; float: right; margin: 5px;">
              <td>
            </tr>

            <tr>
              <td>
                <label for="txtValor" class="bold">Valor:</label>
              </td>
              <td>
                <?php db_input('txtValor', 15, 4, true, 'text', 2); ?>
              </td>
            </tr>

            <tr>
              <td>
                <label for="txtObservacao" class="bold">Observação:</label>
              </td>
              <td>
                <?php db_textarea('txtObservacao', 5, 70, null, true, 'text', 2); ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </fieldset>

      <input type="button" name="btnProcessar" id="btnProcessar" value="Processar" disabled />

    </form>
  </div>

  <?php db_menu() ?>

  <script type="text/javascript">

    var sUrlRPC = 'arr4_manutencaoAbatimento.RPC.php';
    var iSelecionado = 0;
    var sCheckSelecionado = '';

    function js_pesquisaDebitos(iCgm) {

      var sCampos = '|k00_numpre|k00_numpar|k00_tipo|k00_descr|k00_dtvenc|k00_valor';
      var aQueryString = [
        'cgm=' + iCgm,
        'funcao_js=parent.js_mostraDebitos' + sCampos,
      ];
      var sLookup = 'func_compensacaodebitos.php';
      var aDebitosSelecionados = oGridDebitos.getRows();

      var aDebitosFiltrar = [];
      aDebitosSelecionados.each(function(aDebito) {

        var sDebitoNumpar = aDebito.getCells()[1].getValue()  + '/' +  aDebito.getCells()[2].getValue();
        aDebitosFiltrar.push(sDebitoNumpar);
      });

      var sFiltroDebitosSelecionados = 'selecionados=' + aDebitosFiltrar.join('|');
      aQueryString.push(sFiltroDebitosSelecionados);

      var sUrl = sLookup + '?' + aQueryString.join('&');

      js_OpenJanelaIframe('', 'db_iframe_compensacaodebitos', sUrl, 'Pesquisa de débitos', true);
    }

    function js_removerSelecionados() {

      var aSelecionados = oGridDebitos.getSelection('object');
      var aLinhasRemover = [];

      aSelecionados.each(function(oLinha) {
        aLinhasRemover.push(oLinha.getRowNumber());
      });

      oGridDebitos.removeRow(aLinhasRemover);
      oGridDebitos.renderizar();
    }

    function js_mostraDebitos(iNumpre, iNumpar, iTipoDebito, sDescricaoTipoDebito, sDataVencimento, nValor) {

      db_iframe_compensacaodebitos.hide();

      var aLinha = [];
      aLinha[0] = iNumpre;
      aLinha[1] = iNumpar;
      aLinha[2] = sDescricaoTipoDebito;
      aLinha[3] = js_formatar(nValor, 'f');
      aLinha[4] = js_formatar(sDataVencimento, 'd');

      oGridDebitos.addRow(aLinha);
      oGridDebitos.renderRows();
    }

    oGridDebitos = new DBGrid('oGridDebitos');
    oGridDebitos.nameInstance = 'oGridDebitos';
    oGridDebitos.setSelectAll(false);
    oGridDebitos.setHeight(150);
    oGridDebitos.setCheckbox(0);
    oGridDebitos.setCellAlign(['center', 'center', 'center', 'right' , 'center']);
    oGridDebitos.setCellWidth(['15%', '10%', '35%', '15%', '15%']);
    oGridDebitos.setHeader(['Numpre', 'Parcela', 'Tipo de Débito', 'Valor', 'Data Venc.']);
    oGridDebitos.show($('oGridDebitos'));

    $('txtObservacao').value = '';

    $('btnProcessar').observe("click", function() {
      js_ProcessarCredito();
    });

    $('adicionar-debito').observe('click', function () {
      js_pesquisaDebitos($('z01_numcgm').value);
    });

    $('remover-selecionados').observe('click', function () {
      js_removerSelecionados();
    });

    /**
     * Tipo de Operações
     */
    $("opcao").observe("change", function () {

      if (this.value == 2) {

        oGridDebitos.clearAll(true);
        $('grid', 'tipo', 'regraCompensacao', 'remover-selecionados', 'adicionar-debito').invoke('hide');
        $('txtValor').value = js_strToFloat($('saldo').value);

      } else {

        $('grid', 'tipo', 'regraCompensacao', 'remover-selecionados', 'adicionar-debito').invoke('show');
        $('txtValor').value = '';
        js_buscaDadosPortador($("abatimento").value, 0);
      }
    });

    function js_verificaValor(fValorGrid) {

      if (js_strToFloat(fValorGrid) > js_strToFloat($('saldo').value)) {
        $('txtValor').value = $('saldo').value;
      } else {
        $('txtValor').value = fValorGrid;
      }
    }

    /**
     * Pesquisa Créditos
     */
    function js_pesquisaAbatimento() {

      js_OpenJanelaIframe('',
        'db_iframe_abatimento',
        'func_abatimentocredito.php?tipo=3&funcao_js=parent.js_mostraAbatimento1|k125_sequencial|k125_valordisponivel',
        'Pesquisa',
        true
      );
    }

    /**
     * Monstra Abatimento
     */
    function js_mostraAbatimento1(chave1, chave2) {

      $("abatimento").value = chave1;

      var oParam = {
        "exec": "getCreditoCorrigido",
        "iCodigoCredito": chave1
      };

      new AjaxRequest(sUrlRPC, oParam, function (oRetorno, lErro) {

        if (lErro) {

          alert("Não possível obter o Valor do Crédito Corrigido.");
          return false;
        }

        $("saldo").value = js_formatar(oRetorno.valor_corrigido, 'f');
      })
        .setMessage("Aguarde, buscando saldo de crédito atualizado...")
        .execute();

      db_iframe_abatimento.hide();

      $('btnProcessar').disabled = false;
      $('btnProcessar').disabled = false;
      $('remover-selecionados').disabled = false;
      $('adicionar-debito').disabled = false;
      $("MI").style.visibility = 'visible';

      /**
       * pesquisa os dados do contribuinte
       */
      js_buscaDadosPortador($("abatimento").value, 0);
    }

    /**
     * Consulta Origem do Crédito
     */
    function js_consultaOrigemCredito() {

      var sUrl = 'func_origemabatimento.php?iAbatimento=' + $("abatimento").value;
      js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_abatimento', sUrl, 'Origem Crédito', true);
    }

    /**
     * Transfere o Abatimento (crédito) para algum débito
     */
    function js_AbatimentoCredito() {

      return confirm("Confirma Abatimento do crédito " + $("abatimento").value + "?");
    }

    /**
     * Busca Dados do Proprietário
     *
     * @param string|integer iAbatimento
     * @param string|integer iTipo
     */
    function js_buscaDadosPortador(iAbatimento, iTipo) {

      oGridDebitos.clearAll(true);
      $('txtValor').value = '';
      $('grid').style.display = '';

      var oParam         = new Object();
      oParam.iAbatimento = iAbatimento;
      oParam.iTipo       = iTipo;
      oParam.exec        = "getDadosPortadorCredito";

      js_divCarregando("Aguarde, processando informações do portador de crédito...", "msgBox");

      new Ajax.Request(sUrlRPC, {
        method    : 'post',
        parameters: 'json=' + Object.toJSON(oParam),
        onComplete: js_retornoDadosPortador
      });
    }

    /**
     * Popula Grid com Dados do Proprietário
     *
     * @param object oAjax
     */
    function js_retornoDadosPortador(oAjax) {

      js_removeObj("msgBox");

      var oRetorno = eval("(" + oAjax.responseText + ")")

      if (oRetorno.lErro == true) {
        alert('Pesquisa Apresentou erros, favor contate o suporte!');
        return false;
      }

      $('z01_numcgm').value = oRetorno.oDadosPortador.z01_numcgm;
      $('z01_nome').value   = oRetorno.oDadosPortador.z01_nome;
      $('opcao').value      = 1;

      oGridDebitos.clearAll(true);
      $('txtValor').value = '';
      $('grid').style.display = '';
    }

    /**
     * Processar Compensação/Devolução do Crédito
     */
    function js_ProcessarCredito() {

      var nValorCreditoUtilizado = js_strToFloat(js_formatar($("txtValor").value, 'f'))
      var nValorCreditoDisponivel = js_strToFloat($("saldo").value);

      if (nValorCreditoUtilizado > nValorCreditoDisponivel) {
        alert('Valor informado é maior que o saldo disponível.');
        return false;
      }

      var oParam = {
        "exec": "pagamentoCredito",
        "iCgm": $F('z01_numcgm'),
        "nValor": nValorCreditoUtilizado,
        "aDebitos": [],
        "iAbatimento": $F("abatimento"),
        "txtObservacao": $F("txtObservacao").urlEncode(),
        "iRegraCompensacao": $F("regra_compensacao")
      };

      oParam.aDebitos = oGridDebitos.getRows().map(function (oDebito) {

        return {
          "valor" : js_strToFloat(oDebito.getCells()[4].getValue()),
          "numpar" : oDebito.getCells()[2].getValue(),
          "numpre" : oDebito.getCells()[1].getValue(),
          "data_vencimento" : oDebito.getCells()[5].getValue()
        };
      });

      if ($F('opcao') == 1) {
        oParam.exec = "compensacaoCredito";
      }

      if ($F("opcao") == 1 && oParam.aDebitos.length == 0) {

        alert("Nenhum débito foi selecionado.");
        return false;
      }

      if (nValorCreditoUtilizado <= 0) {

        alert("Valor da Compensação/Devolução é de preenchimento obrigatório.");
        return false;
      }

      if (!confirm("Confirma utilização do crédito " + $F("abatimento") + "?")) {
        return false;
      }

      new AjaxRequest(sUrlRPC, oParam, function (oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());

        if (lErro) {
          return false;
        }

        location.reload();
      })
        .setMessage("Aguarde, processando operação...")
        .execute();
    }
  </script>
</body>
</html>
