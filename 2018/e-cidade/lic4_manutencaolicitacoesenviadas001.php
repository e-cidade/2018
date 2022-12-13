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

require_once('libs/db_stdlib.php');
require_once('libs/db_conecta_plugin.php');
require_once('libs/db_sessoes.php');
require_once('libs/db_utils.php');
require_once('libs/db_app.utils.php');
require_once('dbforms/db_funcoes.php');

$oRotulo = new rotulocampo();
$oRotulo->label("ac16_sequencial");
$oRotulo->label("ac16_resumoobjeto");
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
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">

<div class="container">

  <form name="form1" method="post">

    <fieldset>
      <legend>Manutenção de Licitações Enviadas</legend>
      <table>

        <tr>
          <td>
            <label class="bold" for="data_confirmacao">
              <?php
              db_ancora('Data da Confirmação: ', 'pesquisarData()', 1);
              ?>
            </label>
          </td>

          <td>
            <?php
            db_input('data_confirmacao', 10, null, true, 'text', 3);
            ?>
          </td>
        </tr>

      </table>

      <fieldset style="margin-top: 10px;">
        <legend>Licitações Encontradas</legend>
        <div style="width: 600px" id="container-licitacoes"></div>
      </fieldset>
    </fieldset>

    <input type="button" value="Limpar" onclick="oDadosLicitacoes.reset();" />
  </form>

</div>

<?php db_menu(); ?>
<script type="text/javascript">

  var oDadosLicitacoes = (function() {

    var oInputData = $('data_confirmacao');
    var oContainerCollection = $('container-licitacoes');
    const PATH_RPC = 'lic4_manutencaoencerramentolicitacon.RPC.php';

    var oCollectionLicitacao = new Collection().setId("codigo");
    var oGridLicitacoes      = new DatagridCollection(oCollectionLicitacao).configure({
      order : false,
      height : 200
    });
    oGridLicitacoes.addColumn("codigo", {
      label: 'Código',
      width: '20%',
      align: 'center'
    });
    oGridLicitacoes.addColumn("numero", {
      label: 'Número',
      width: '20%',
      align: 'center'
    });
    oGridLicitacoes.addColumn("lancamento_encerramento", {
      label: 'Evento de Encerramento',
      width: '40%',
      align: 'center'
    });

    oGridLicitacoes.addAction("Excluir", null, function(oEvento, oLicitacao) {

      var sMensagemConfirmacao = "Ao confirmar a exclusão deste registro a licitação voltará a ser enviada nos arquivos do LicitaCon.\n\nConfirma a exclusão da licitação "+oLicitacao.numero+"?";
      if (!confirm(sMensagemConfirmacao)) {
        return false;
      }

      new AjaxRequest(
        PATH_RPC,
        {"exec" : "excluirEncerramento", "codigo" : oLicitacao.l18_sequencial},
        function (oRetorno, lErro) {

          alert(oRetorno.mensagem.urlDecode());
          if (lErro) {
            return false;
          }
          oCollectionLicitacao.remove(oLicitacao.codigo);
          oGridLicitacoes.reload();
        }
      ).setMessage('Aguarde, excluindo registro...').execute();

    });
    oGridLicitacoes.show(oContainerCollection);
    oGridLicitacoes.reload();


    function carregarLicitacoes() {

      if (oInputData.value == "") {
        return false;
      }

      new AjaxRequest(
        PATH_RPC,
        {"exec" : 'getLicitacoes' , "dtProcessamento" : oInputData.value },
        function (oRetorno, lErro) {

          oCollectionLicitacao.clear();

          oRetorno.licitacoes.each(
            function (oItem, iIndice) {

              var sColor = '#000';
              var sLabel = "Sim";
              if ( ! oItem.possui_encerramento) {
                sColor = 'red';
                sLabel = "Não";
              }

              oCollectionLicitacao.add(
                {
                  "codigo" : oItem.l20_codigo,
                  "numero" : oItem.l20_numero +'/'+oItem.l20_anousu,
                  "lancamento_encerramento" : "<span style='color: "+sColor+"'>"+sLabel+"</span>",
                  "l18_sequencial" : oItem.l18_sequencial
                }
              );
            }
          );
          oGridLicitacoes.reload();
        }
      ).setMessage('Aguarde, carregando licitações...').execute();
    }

    return {
      "carregarLicitacoes" : function() {

        oCollectionLicitacao.clear();
        oGridLicitacoes.reload();
        carregarLicitacoes();
      },

      "reset" : function () {

        oCollectionLicitacao.clear();
        oGridLicitacoes.reload();
        oInputData.value = '';
      }
    };
  })();

  function pesquisarData() {

    var sCaminhoBusca = "func_liclicitaencerramentolicitacon.php?manutencaolicitacoesenviadas=true&funcao_js=parent.preencheData|l18_data";
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_manutencaolicitacon', sCaminhoBusca, 'Pesquisar Datas de Confirmação', true);
  }

  function preencheData(sDataConfirmacao) {
    $('data_confirmacao').value = js_formatar(sDataConfirmacao, 'd');
    oDadosLicitacoes.carregarLicitacoes();
    db_iframe_manutencaolicitacon.hide();
  }


</script>
</body>
</html>