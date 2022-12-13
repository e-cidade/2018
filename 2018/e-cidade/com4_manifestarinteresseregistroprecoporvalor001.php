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

require_once "libs/db_stdlib.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

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
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body class="body-default">
    <div class="container" style="width: 700px;">
      <form name="form1" id="manifestacaoInteresse" method="post" action="">
        <fieldset>
          <legend>Manifestação de Interesse</legend>
          <table>
            <tr>
              <td>
                <label for="estimativa" class="bold" id="lbl_estimativa">Código:</label>
              </td>
              <td>
                <?php db_input('estimativa', 10, 4, true, 'text', 3); ?>
              </td>
            </tr>
            <tr>
              <td>
                <label class="bold" for="abertura" id="lbl_abertura">
                  <?php db_ancora( 'Abertura:', "aberturaRegistroPreco.pesquisar();", 1); ?>
                </label>
              </td>
              <td>
                <?php db_input('abertura', 10, 4, true, 'text', 3); ?>
              </td>
            </tr>
          </table>

          <fieldset>
            <legend>Itens</legend>
            <div id="itens"></div>
          </fieldset>

        </fieldset>
        <input name="salvar" type="submit" id="salvar" value="Salvar"/>
        <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar"/>
      </form>
    </div>
    <?php db_menu( db_getsession("DB_id_usuario"),
                   db_getsession("DB_modulo"),
                   db_getsession("DB_anousu"),
                   db_getsession("DB_instit") ); ?>

    <script type="text/javascript">

      (function(exports) {

        const RPC = "com4_manifestarinteresseregistroprecoporvalor.RPC.php";
        const MENSAGENS = "patrimonial.compras.com4_manifestarinteresseregistroprecoporvalor.";

        var oGridItens = new DBGrid("gridItens"),
            oGet = js_urlToObject();

        oGridItens.nameInstance = "oGridItens";
        oGridItens.setCheckbox(0);
        oGridItens.setCellWidth(["0%", "50%", "25%", "25%"]);
        oGridItens.setCellAlign(["left", "left", "left", "right"]);
        oGridItens.setHeader(["&nbsp;", "Descrição", "Outras Informações", "Valor"]);
        oGridItens.show($('itens'));
        oGridItens.showColumn(false, 1);

        estimativaRegistroPreco = {
          pesquisar : function() {

            js_OpenJanelaIframe( 'top.corpo',
                                 'db_iframe_solicita',
                                 'func_solicitaestimativa.php?funcao_js=parent.estimativaRegistroPreco.preenche|pc10_numero&departamento=true&anuladas=1&comcompilacao=1&formacontrole=2',
                                 'Pesquisa de Estimativa de Registro de Preço por Valor', true );
          },
          preenche : function(iEstimativa) {

            if (oGet.acao == 1) {
              return false;
            }

            if (top.corpo.db_iframe_solicita) {
              db_iframe_solicita.hide();
            }

            var oParametros = {
              sExecucao : "buscarDadosEstimativa",
              iEstimativa : iEstimativa
            }

            new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

              if (lErro) {
                alert(oRetorno.sMessage.urlDecode());
                return false;
              }


              $('abertura').value   = oRetorno.iAbertura;
              $('estimativa').value = iEstimativa;

              estimativaRegistroPreco.pesquisaItens();
            }).execute();

          },
          pesquisaItens : function() {

            var oParametros = {
              sExecucao : "buscarItens",
              iEstimativa  : $F('estimativa')
            }

            oGridItens.clearAll(true);

            new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

              if (lErro) {
                alert(oRetorno.sMessage.urlDecode());
                return false;
              }

              aberturaRegistroPreco.preencheGrid(oRetorno.aItens);
            }).execute();
          }
        }

        aberturaRegistroPreco = {
          pesquisar : function() {

            js_OpenJanelaIframe( 'top.corpo',
                                 'db_iframe_solicitaregistropreco',
                                 'func_solicitaregistropreco.php?funcao_js=parent.aberturaRegistroPreco.preenche|pc54_solicita'
                                 + '&liberado=true&trazsemcompilacao=1&noperiodo=1&anuladas=1&formacontrole=2',
                                 'Pesquisa de Abertura de Registro de Preço por Valor', true );
          },
          preenche : function(iAbertura) {

            $('abertura').value = iAbertura;

            aberturaRegistroPreco.pesquisaItens();
          },

          pesquisaItens : function() {

            var oParametros = {
              sExecucao : "buscarItens",
              iAbertura : $F('abertura')
            }

            oGridItens.clearAll(true);

            new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

              if (lErro) {

                $('abertura').value = '';
                alert(oRetorno.sMessage.urlDecode());
                return false;
              }

              db_iframe_solicitaregistropreco.hide();
              aberturaRegistroPreco.preencheGrid(oRetorno.aItens);
            }).execute();
          },
          preencheGrid : function(aDados) {

            oGridItens.clearAll(true);

            aDados.each(function(oItem) {

              oGridItens.addRow([
                  oItem.codigo,
                  oItem.descricao.urlDecode(),
                  oItem.resumo.urlDecode(),
                  js_formatar(oItem.valor, 'f', 2)
                ],
                false,
                false,
                (oItem.marcado) );
            });

            oGridItens.renderRows();

            aDados.each(function(oItem, iLinha) {

              if (empty(oItem.resumo.urlDecode())) {
                return true;
              }

              var oHintResumo = eval("oDBHint_"+iLinha+"_2 = new DBHint('oDBHint_"+iLinha+"_2')");

              oHintResumo.setWidth(400);
              oHintResumo.setText(oItem.resumo.urlDecode());
              oHintResumo.setShowEvents(["onmouseover"]);
              oHintResumo.setHideEvents(["onmouseout"]);
              oHintResumo.setScrollElement($('body-container-oGridItens'));
              oHintResumo.setPosition('B', 'L');

              oHintResumo.make($(oGridItens.aRows[iLinha].aCells[3].sId));
            })
          }
        }

        $('pesquisar').observe('click', function() {
          estimativaRegistroPreco.pesquisar();
        });

        Event.observe('manifestacaoInteresse', 'submit', function(e) {
          e.stop();

          if (empty($F('abertura'))) {
            alert( _M(MENSAGENS + "campo_obrigatorio", {sCampo : "Abertura"}) );
            return false;
          }

          var oParametros = {
            sExecucao     : "salvar",
            iAbertura     : $F('abertura'),
            iEstimativa   : $F('estimativa'),
            aItens        : oGridItens.getSelection().map(function(oItem) {
                              return oItem[0];
                            })
          }

          if (empty(oParametros.aItens)) {

            alert( _M(MENSAGENS + "nenhum_item") );
            return false;
          }

          new AjaxRequest(RPC, oParametros, function(oRetorno, lErro) {

            if (lErro) {
              alert(oRetorno.sMessage.urlDecode());
              return false;
            }

            alert( _M(MENSAGENS + "salvo_sucesso") );

            if (oGet.acao == 1) {
              document.location.href = "com4_manifestarinteresseregistroprecoporvalor001.php?acao=2&estimativa=" + oRetorno.iCodigoEstimativa;
            } else {
              estimativaRegistroPreco.pesquisaItens();
            }

          }).execute();

          return false;
        });

        exports.aberturaRegistroPreco = aberturaRegistroPreco;
        exports.oGridItens            = oGridItens;

        if (oGet.acao == 2) {
          $('lbl_abertura').innerHTML = $('lbl_abertura').textContent;
        }

        if (!empty(oGet.estimativa)) {

          estimativaRegistroPreco.preenche(oGet.estimativa);
          return false;
        }

        if (oGet.acao == 1) {
          aberturaRegistroPreco.pesquisar();
        } else {
          estimativaRegistroPreco.pesquisar();
        }

      })(this);
    </script>
  </body>
</html>
