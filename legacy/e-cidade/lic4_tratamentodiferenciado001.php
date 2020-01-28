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
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="post">
      <fieldset>
        <legend>Reserva de Itens para Tratamento Diferenciado de ME e EPP</legend>
          <table>
            <tr>
              <td>
                <label class="bold" for="l20_codigo">
                  <a id="licitacao_ancora">Licitação:</a>
                </label>
              </td>
              <td>
                <?php
                  db_input("l20_codigo", 10, 1, true);
                  db_input("l20_anousu", 10, 1, true, 'hidden');
                ?>
              </td>
            </tr>
          </table>
        <fieldset class="separator">
          <legend>Itens</legend>
          <div style="width: 800px" id="container-grid-itens"></div>
        </fieldset>
      </fieldset>
      <input type="button" id="salvar" value="Salvar"></input>
    </form>
  </div>
  <?php db_menu(); ?>
  <script type="text/javascript">

    function mascaraCampoPercentual(oObj) {

      oObj.value = oObj.value.replace(/[^0-9\,\.]/g, '').replace(/^(\,|\.)(\d*)$|(?:\,|\.)?(\d*)(\,|\.)?(\d{0,2})(\d*)(?:\,|\.)?/, '$1$2$3$4$5');
      return true;
    }

    (function(exports) {

      const RPC = "lic4_tratamentodiferenciado.RPC.php";
      const MENSAGEM = "patrimonial.licitacao.lic4_tratamentodiferenciado.";

      var oLicitacaoCodigo = $('l20_codigo');

      var oLookUpLicitacao = new DBLookUp($('licitacao_ancora'), oLicitacaoCodigo, $('l20_anousu'), {
        "sArquivo" : "func_liclicita.php",
        "sObjetoLookUp" : "db_iframe_liclicita",
        "sLabel" : "Pesquisar Licitação"
      });

      oLookUpLicitacao.setCallBack('onClick', function () {

        oCollectionItens.clear();
        oGridItens.reload();

        var oParametros = {
          'iCodigoLicitacao' : oLicitacaoCodigo.value,
          'exec'             : 'getItens'
        };

        new AjaxRequest(RPC, oParametros, function (oRetorno, lErro) {

          if (lErro) {
            return alert( oRetorno.mensagem.urlDecode() );
          }

          for (var oItem of oRetorno.aItens) {

            oCollectionItens.add({
              ordem : oItem.ordem,
              codigo : oItem.codigo,
              descricao : oItem.descricao.urlDecode(),
              quantidade : oItem.quantidade,
              reservado : oItem.reservado
            });
          }

          oGridItens.reload();

          var oGrid = oGridItens.getGrid()

          for (var iRow in oCollectionItens.itens) {
            oGrid.setHint(iRow, 1, oCollectionItens.itens[iRow].descricao);
          }

        }).setMessage("Carregando itens da licitação.")
          .execute();
      });

      var oCollectionItens = new Collection().setId('codigo');
      var oGridItens = new DatagridCollection(oCollectionItens).configure({ order : false, height : 250 });

      oGridItens.addColumn("ordem", { label : "Ordem", width : "40px", align : "center" });
      oGridItens.addColumn("descricao", { label : "Descrição", width : '' });
      oGridItens.addColumn("quantidade", { label : "Quantidade Original", width : "120px", align : "center" });

      oGridItens.addColumn("reservado", { label : "Reservado", width : "110px", align : "right" }).transform(function(sValue, oItem) {

        var oInput = document.createElement("input");
        oInput.type = "text";
        oInput.id = "quantidade" + oItem.ID;
        oInput.classList.add("field-size-max");
        oInput.classList.add("text-right");
        oInput.setAttribute("value", sValue || 0);
        oInput.setAttribute("oninput", "this.value = +this.value.replace(/[^0-9]/g, ''); controlePercentualReservado.setarPercentual(" + oItem.ID + ", this.value)");

        return oInput.outerHTML;
      });

      oGridItens.addColumn("percentual", { label : "Percentual (%)", width : "110px", align : "right" }).transform(function(sValue, oItem) {

        var oInput = document.createElement("input");
        oInput.type = "text";
        oInput.id = "percentual" + oItem.ID;
        oInput.classList.add("field-size-max");
        oInput.classList.add("text-right");
        oInput.setAttribute("onfocus", "this.value = this.value.getNumber()");
        oInput.setAttribute("onblur", "this.value = js_formatar(this.value.getNumber(), 'f', 2)");
        oInput.setAttribute("oninput", "mascaraCampoPercentual(this); controlePercentualReservado.setarQuantidade(" + oItem.ID + ", this.value.getNumber())");
        oInput.setAttribute("value", js_formatar( controlePercentualReservado.calcularPercentual(oItem.quantidade, oItem.reservado), 'f', 2) );

        return oInput.outerHTML;
      });

      oGridItens.show( $('container-grid-itens') )

      $("salvar").addEventListener("click", function() {

        if (oLicitacaoCodigo.value == '') {
          return alert( _M(MENSAGEM + "licitacao_nao_informada") );
        }

        var aItens = []
        for (var oItem of oCollectionItens.itens) {

          aItens.push({
            codigo : oItem.ID,
            quantidade : oItem.quantidade,
            reservado : $("quantidade" + oItem.ID).value.getNumber()
          })
        }

        var oParametros = {
          iCodigoLicitacao : oLicitacaoCodigo.value,
          exec : "salvar",
          aItens : aItens
        };

        new AjaxRequest(RPC, oParametros, function (oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.mensagem.urlDecode());
          }

          alert( _M(MENSAGEM + "salvo_sucesso") );

        }).setMessage("Salvando cotações.")
          .execute();
      });

      var controlePercentualReservado = {

        calcularPercentual : function (iQuantidade, iReservado) {
          if (iReservado == 0) {
            return 0;
          }

          return +((iReservado*100)/iQuantidade).toFixed(2)
        },

        setarPercentual : function(iLinha, iReservado) {

          $("percentual" + iLinha).value = js_formatar(controlePercentualReservado.calcularPercentual(oCollectionItens.get(iLinha).quantidade, iReservado), 'f', 2);
        },

        setarQuantidade : function (iLinha, nPercentual) {

          nPercentual = nPercentual > 100 ? 100 : nPercentual;
          $("quantidade" + iLinha).value = parseInt(nPercentual/100*oCollectionItens.get(iLinha).quantidade);
        }
      }

      exports.controlePercentualReservado = controlePercentualReservado;

      oLookUpLicitacao.abrirJanela(true);
    })(this)

  </script>
</body>