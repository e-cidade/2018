<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
require_once modification("libs/db_utils.php");
require_once modification("std/db_stdClass.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oRotulo = new rotulocampo();
$oRotulo->label('pc20_codorc');
$oRotulo->label('pc21_validadorc');
$oRotulo->label('pc21_prazoent');

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <div class="container">
    <form method="post">
      <fieldset>
        <legend>
          Lançamento de Valores
        </legend>
        <fieldset class="separator">
          <legend>
            Dados do Orçamento da Licitação
          </legend>
          <table>
            <tr>
              <td>
                <label for="pc20_codorc">
                  <?=$Lpc20_codorc?>
                </label>
              </td>
              <td>
                <input type="text" class="readonly field-size2" id="pc20_codorc" readonly>
              </td>
            </tr>
            <tr>
              <td>
                <label for="cboFornecedores">
                  <b>Fornecedor:</b>
                </label>
              </td>
              <td>
                <select id="cboFornecedores" class="field-size5" onchange="LancaValores.getCotacoesDoFornecedor(this.value)">
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for='pc21_validadorc'>
                  <?=$Lpc21_validadorc;?>
                </label>
              </td>
              <td>
                <?php
                db_inputdata("pc21_validadorc",null, null, null , true, "text", 1); ?>
              </td>
            </tr>
            <tr>
              <td>
                <label for='pc21_prazoent'>
                  <?=$Lpc21_prazoent;?>
                </label>
              </td>
              <td>
                <?php
                db_inputdata("pc21_prazoent", null, null, null ,true, "text", 1); ?>
              </td>
            </tr>
          </table>
        </fieldset>
        <fieldset class="separator">
          <legend>
            Itens da Licitação
          </legend>
          <div id="ctnDataGridItensFornecedor" style="width: 800px"></div>
        </fieldset>
      </fieldset>
      <input type="button" id="btnVoltar" value="Voltar" onclick="LancaValores.voltar()">
      <input type="button" id="btnSalvar" value="Salvar" onclick="LancaValores.salvarCotacoes()">
      <input type="button" id="btnZerar" value="Zerar Valores" onclick="LancaValores.zerarValores()">
      <input type="button" id="btnJulgar" value="Julgar Licitação" onclick="LancaValores.julgar()" disabled="disabled">
    </form>
  </div>
</body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
  (function(exports) {

    const RPC       = "lic4_lancamentovalororcamento.RPC.php";
    const MENSAGENS = "patrimonial.licitacao.lic4_orcamentolicitacaoregistropreco001.";

    var oGridItens = new DBGrid("gridItens"),
        oGet = js_urlToObject();

    oGridItens.nameInstance = "oGridItens";
    oGridItens.setHeader(["Item", "Descrição", "Total", "Desconto (%)", "Nota Técnica", "BDI", "Encargos Sociais"]);
    oGridItens.setCellAlign(["center", "left", "right", "right", "right", "right", "right"]);
    oGridItens.setCellWidth(["7%", "33%", "12%", "12%", "12%", "12%", "15%"]);
    oGridItens.setHeight(400);

      /**
       * Parametros base para todas as requisicoes da rotina
       */
    oParametros = {

      codigo_licitacao: oGet.iLicitacao,
      codigo_orcamento: oGet.pc20_codorc,
      sExecucao       : ''
    };

    var aItensLicitacao = [];
    LancaValores = {

      codigo_licitacao:oGet.iLicitacao,
      codigo_orcamento:oGet.pc20_codorc,

      pesquisaDadosOrcamento:function() {

        $('btnJulgar').disabled = true;
        oParametros.sExecucao   = 'getDadosOrcamento';
        new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {

            alert(oRetorno.sMessage.urlDecode());
            LancaValores.voltar();
            return false;
          }
          oRetorno.fornecedores.each(function(oFornecedor) {

            var oCboFornecedores = $('cboFornecedores');
            var oOption = new Option(oFornecedor.nome.urlDecode(), oFornecedor.codigo);
            oCboFornecedores.add(oOption);
          });

          aItensLicitacao = oRetorno.itens;
          LancaValores.getCotacoesDoFornecedor($F('cboFornecedores'));
          $('pc20_codorc').value  = oGet.pc20_codorc;

          /**
           * Caso exista cotações para o orçamento, liberamos o botao de julgamento
           */
          $('btnJulgar').disabled = !oRetorno.temcotacao;

          if (!oRetorno.obrasengenharia) {

            oGridItens.aHeaders[5].lDisplayed = false;
            oGridItens.aHeaders[6].lDisplayed = false;
          }

          if (!oRetorno.temnotatecnica) {
            oGridItens.aHeaders[4].lDisplayed = false;
          }

          oGridItens.show($('ctnDataGridItensFornecedor'));
        }).execute();
      },

      /**
       * Retorna todas as cotacoes realizadas pelo Fornecedor
       * @param iFornecedor
       */
      getCotacoesDoFornecedor: function (iFornecedor) {

        oParametros.sExecucao  = 'getCotacoesFornecedor';
        oParametros.fornecedor = iFornecedor;
        oGridItens.clearAll(true);
        new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {

            alert(oRetorno.sMessage.urlDecode());
            return false;
          }

          /**
           * Caso exista cotações para o orçamento, liberamos o botao de julgamento
           */
          $('btnJulgar').disabled    = !oRetorno.temcotacao;
          $('pc21_prazoent').value   = oRetorno.prazoentrega;
          $('pc21_validadorc').value = oRetorno.validadeorcamento;

          aItensLicitacao.each(function(oItem) {

            var nPercentualDesconto = (oRetorno.cotacoes[oItem.ordem] ? oRetorno.cotacoes[oItem.ordem].percentual : 0);
            var nBdi = (oRetorno.cotacoes[oItem.ordem] ? oRetorno.cotacoes[oItem.ordem].bdi : 0);
            var nEncargosSociais = (oRetorno.cotacoes[oItem.ordem] ? oRetorno.cotacoes[oItem.ordem].encargossociais : 0);
            var nNotaTecnica = (oRetorno.cotacoes[oItem.ordem] ? oRetorno.cotacoes[oItem.ordem].notatecnica : 0);

            var sInputPercentual = "<input type='text' style='width:100%;text-align:right' class='valoresorcamento'";
            sInputPercentual    += " onkeyPress='return js_teclas(event)' maxlength='6'";
            sInputPercentual    += " onfocus='this.value = js_strToFloat(this.value);'";
            sInputPercentual    += " onblur=\"LancaValores.validaPercentualTotal(this);\"";
            sInputPercentual    += " onfocus='this.value = js_strToFloat(this.value);'";
            sInputPercentual    += "id='txtPercentual"+oItem.ordem+"' value='"+ js_formatar(nPercentualDesconto, 'f') +"'>";

            var sInputBdi = "<input type='text' style='width:100%;text-align:right' class='valoresorcamento'";
            sInputBdi    += " onkeyPress='return js_teclas(event)' maxlength='6'";
            sInputBdi    += " onfocus='this.value = js_strToFloat(this.value);'";
            sInputBdi    += "onblur='LancaValores.validaPercentualTotal(this);'";
            sInputBdi    += "id='txtBdi"+oItem.ordem+"' value='"+ js_formatar(nBdi, 'f') +"'>";

            var sNotaTecnica = "<input type='text' style='width:100%;text-align:right' class='valoresorcamento'";
            sNotaTecnica    += " onkeyPress='return js_teclas(event)' maxlength='6'";
            sNotaTecnica    += " onfocus='this.value = js_strToFloat(this.value);'";
            sNotaTecnica    += "onblur='LancaValores.validaPercentualTotal(this);'";
            sNotaTecnica    += "id='txtNota"+oItem.ordem+"' value='"+ js_formatar(nNotaTecnica, 'f') +"'>";

            var sInputEncargos = "<input type='text' style='width:100%;text-align:right' class='valoresorcamento'";
            sInputEncargos    += " onkeyPress='return js_teclas(event)' maxlength='6'";
            sInputEncargos    += "onblur='LancaValores.validaPercentualTotal(this);'";
            sInputEncargos    += " onfocus='this.value = js_strToFloat(this.value);'";
            sInputEncargos    += "id='txtEncargos"+oItem.ordem+"' value='"+ js_formatar(nEncargosSociais, 'f') +"'>";

            var aItems = [
              oItem.ordem,
              oItem.descricao.urlDecode(),
              js_formatar(oItem.valor, 'f'),
              sInputPercentual,
              sNotaTecnica,
              sInputBdi,
              sInputEncargos
            ];
            oGridItens.addRow(aItems);
          });
          oGridItens.renderRows();
        }).execute();
      },

      /**
       * Valida  o valor do campo percentual
       * @param oInput
       */
      validaPercentualTotal: function(oInput) {

        if (oInput.value == '.') {
          oInput.value = '0'
        };

        if (oInput.value > 100) {
          oInput.value = '100.00';
        }

        oInput.value = js_formatar(oInput.value, 'f', 2);
      },

      /**
       * salva os dados da Cotacao do fornecedor
       */
      salvarCotacoes:function() {

        oParametros.sExecucao         = 'salvarCotacoes';
        oParametros.cotacoes          = [];
        oParametros.prazoentrega      = $F('pc21_prazoent');
        oParametros.validadeorcamento = $F('pc21_validadorc');
        aItensLicitacao.each(function(oItem) {

           var oCotacao = {
             item : oItem.codigo,
             percentual : $F("txtPercentual" + oItem.ordem).getNumber(),
             notatecnica : $F("txtNota" + oItem.ordem).getNumber(),
             valor : oItem.valor,
             bdi : $F("txtBdi" + oItem.ordem).getNumber(),
             encargossociais : $F("txtEncargos" + oItem.ordem).getNumber()
           };
          oParametros.cotacoes.push(oCotacao);
        });

        new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {
          if (lErro) {

            alert(oRetorno.sMessage.urlDecode());
            return false;
          }
          alert(_M(MENSAGENS+"cotacoes_salvas"));
          var iIndiceFornecedores = $('cboFornecedores').selectedIndex + 1;
          if ($('cboFornecedores').options[iIndiceFornecedores]) {

            $('cboFornecedores').value = $('cboFornecedores').options[iIndiceFornecedores].value;
            LancaValores.getCotacoesDoFornecedor($F('cboFornecedores'));
          }
        }).execute();
      },

      julgar : function() {

        var sUrl  = "lic1_pcorcamtroca001.php?pc20_codorc="+oGet.pc20_codorc;
        sUrl     += "&pc21_orcamforne=&l20_codigo="+oGet.iLicitacao;
        document.location.href = sUrl;
      },

      voltar: function() {
        document.location.href = "lic1_lancavallic001.php";
      },

      zerarValores : function() {

        var aItens = document.getElementsByClassName('valoresorcamento');
        for (var iCampo = 0; iCampo < aItens.length; iCampo++) {
          aItens[iCampo].value = '0';
        };
      }

    }
    exports.pesquisaDadosOrcamento = LancaValores.pesquisaDadosOrcamento;
    exports.LancaValores           = LancaValores;
    LancaValores.pesquisaDadosOrcamento();
  })(this);
</script>