<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2017  DBSeller Servicos de Informatica
 *                      www.dbseller.com.br
 *                   e-cidade@dbseller.com.br
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
require_once(modification("dbforms/db_funcoes.php"));
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?php
    db_app::load("scripts.js");
    db_app::load("strings.js");
    db_app::load("prototype.js");
    db_app::load("estilos.css");
    db_app::load("DBFormularios.css");
    db_app::load("AjaxRequest.js");
    db_app::load("widgets/DBLookUp.widget.js");
    db_app::load("datagrid.widget.js");
    db_app::load("widgets/Collection.widget.js");
    db_app::load("widgets/DatagridCollection.widget.js");
    db_app::load("widgets/DBInputHora.widget.js");
    db_app::load("widgets/Input/DBInputDate.widget.js");
    db_app::load("widgets/datagrid/plugins/DBHint.plugin.js");
  ?>
  <style type="text/css"></style>
</head>
<body>
<div class="container">
  <form>
    <fieldset>
      <legend>Manutenção de débito em conta de contratos de água</legend>
      <table class="form-container">

        <tr>
          <td>
            <label class="bold" id="pedido_label" for="pedido">
              Código Pedido:
            </label>
          </td>
          <td>
            <input type="text" class="readonly field-size2" name="pedido" id="pedido" readonly="readonly" data="d63_codigo">
            <input type="hidden" name="pedido_descricao" id="pedido_descricao">
          </td>
        </tr>

        <tr>
          <td><a href="" id="contrato_label">Contrato:</a></td>
          <td>
            <input type="text" name="x54_sequencial" id="x54_sequencial" class="field-size2">
            <input type="text" name="contrato_descricao" id="contrato_descricao" data="z01_nome">
          </td>
        </tr>

        <tr>
          <td>
            <label class="bold" for="economia">
              <a id="economia_label">Economia:</a>
            </label>
          </td>
          <td>
            <input class="field-size2" type="text" name="economia" id="economia" data="x38_sequencial">
            <input class="field-size8" type="text" name="economia_descricao" id="economia_descricao" data="z01_nome">
          </td>
        </tr>

        <tr>
          <td>
            <label class="bold" for="banco">
              <a id="banco_label">Banco:</a>
            </label>
          </td>
          <td>
            <input class="field-size2" type="text" name="banco" id="banco" data="codbco">
            <input class="field-size8" type="text" name="banco_descricao" id="banco_descricao" data="nomebco">
          </td>
        </tr>

        <tr>
          <td>
            <label class="bold" for="agencia">
              <a id="agencia_label">Agência:</a>
            </label>
          </td>
          <td>
            <input class="field-size2" type="text" name="agencia" id="agencia" maxlength="4">
          </td>
        </tr>

        <tr>
          <td>
            <label class="bold" for="conta">
              <a id="conta_label">Conta:</a>
            </label>
          </td>
          <td>
            <input class="field-size2" type="text" name="conta" id="conta" maxlength="14">
          </td>
        </tr>

        <tr>
          <td>
            <label class="bold" for="status">
              <a id="status_label">Status:</a>
            </label>
          </td>
          <td>
            <select name="status" id="status" class="field-size2" rel="ignore-css">
              <option value="1">Pendente</option>
              <option value="2">Ativo</option>
              <option value="3">Inativo</option>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label class="bold" for="idempresa">
              <a id="idempresa_label">ID Empresa:</a>
            </label>
          </td>
          <td>
            <input class="field-size2" type="text" name="idempresa" id="idempresa" maxlength="25">
          </td>
        </tr>

      </table>
    </fieldset>

    <input type="button" value="Salvar" id="salvar">
    <input type="button" value="Pesquisar" id="pesquisar">

  </form>
</div>
<?php db_menu(); ?>
<script type="text/javascript">

  var RESPONSAVEL_PAGAMENTO_ECONOMIA = '<?php echo AguaContrato::RESPONSAVEL_PAGAMENTO_ECONOMIA ?>';

  var oGet = js_urlToObject();

  var oPedido = $("pedido");
  var oPedidoLabel = $("pedido_label");
  var oPedidoDescricao = $("pedido_descricao");

  var oContrato = $("x54_sequencial");
  var oContratoLabel = $("contrato_label");
  var oContratoDescricao = $("contrato_descricao");

  var oEconomia = $("economia");
  var oEconomiaLabel = $("economia_label");
  var oEconomiaDescricao = $("economia_descricao");

  var oBanco = $("banco");
  var oBancoLabel = $("banco_label");
  var oBancoDescricao = $("banco_descricao");

  var oAgencia = $("agencia");
  var oConta = $("conta");
  var oStatus = $("status");
  var oIdEmpresa = $("idempresa");

  var oBtnPesquisar = $("pesquisar");
  var oBtnSalvar = $("salvar");

  var oLookupPedido = new DBLookUp(oPedidoLabel, oPedido, oPedidoDescricao, {
    "sArquivo": "func_debcontapedido.php",
    "sObjetoLookUp": "db_iframe_pedido",
    "sLabel": "Pesquisar",
    "fCallBack": function (iCodigoPedido) {

      var oParametros = {
        'exec': 'carregar',
        'iCodigo': iCodigoPedido
      };

      new AjaxRequest('cai4_debcontapedidoaguacontratoeconomia.RPC.php', oParametros, function (oRetorno, lErro) {

        if (lErro) {
          return false;
        }

        oContrato.value = oRetorno.oPedido.iContrato;
        oContratoDescricao.value = oRetorno.oPedido.sContratoDescricao;
        oEconomia.value = oRetorno.oPedido.iEconomia;
        oEconomiaDescricao.value = oRetorno.oPedido.sEconomiaDescricao;
        oBanco.value = oRetorno.oPedido.iBanco;
        oBancoDescricao.value = oRetorno.oPedido.sBancoDescricao;
        oAgencia.value = oRetorno.oPedido.iAgencia;
        oConta.value = oRetorno.oPedido.iConta;
        oStatus.value = oRetorno.oPedido.iStatus;
        oIdEmpresa.value = oRetorno.oPedido.iIdEmpresa;

        oLookupEconomia.desabilitar();

        if (oEconomia.value) {

          oLookupEconomia.setParametrosAdicionais([
              'filtro_contrato=' + oContrato.value
          ]);

          oLookupEconomia.habilitar();
        }

      }).execute();

      oBtnSalvar.removeAttribute('disabled');
    }
  });

  oLookupPedido.setParametrosAdicionais(['sTipo=AGUA']);

  var oLookupContrato = new DBLookUp(oContratoLabel, oContrato, oContratoDescricao, {
    "sArquivo": "func_aguacontrato.php",
    "sObjetoLookUp": "db_iframe_aguacontrato",
    "sLabel": "Pesquisar",
    "fCallBack": onContratoChange
  });

  var oLookupEconomia = new DBLookUp(oEconomiaLabel, oEconomia, oEconomiaDescricao, {
    "sObjetoLookUp" : "db_iframe_aguacontratoeconomia",
    "sArquivo" : "func_aguacontratoeconomia.php",
    "sLabel" : "Pesquisar Economia"
  });

  var oLookupBanco = new DBLookUp(oBancoLabel, oBanco, oBancoDescricao, {
    "sObjetoLookUp" : "db_iframe_bancos",
    "sArquivo" : "func_bancos.php",
    "sLabel" : "Pesquisar Bancos"
  });

  oLookupPedido.desabilitar();
  oLookupEconomia.desabilitar();

  function onContratoChange(iCodigo, lErro) {

    oLookupEconomia.desabilitar();
    oEconomia.value = '';
    oEconomiaDescricao.value = '';

    oLookupEconomia.setParametrosAdicionais([
        'filtro_contrato=' + oContrato.value
    ]);

    if (lErro === true) {
      return false;
    }

    var oParametros = {
      'exec' : 'carregarContrato',
      'iCodigo' : oContrato.value
    };

    new AjaxRequest('agu1_aguacontrato.RPC.php', oParametros, function (oRetorno, lErro) {

      if (lErro) {
        return false;
      }

      if (oRetorno.contrato.lCondominio && oRetorno.contrato.iResponsavelPagamento == RESPONSAVEL_PAGAMENTO_ECONOMIA) {
        oLookupEconomia.habilitar();
      }
    }).execute();
  }

  oBtnPesquisar.on('click', function () {
    oLookupPedido.abrirJanela(true);
  });

  oBtnSalvar.on('click', function () {

      var oParametros = {
        'exec': 'salvar',
        'iCodigo': oPedido.value,
        'iContrato': oContrato.value,
        'iEconomia': oEconomia.value,
        'iBanco': oBanco.value,
        'sAgencia': oAgencia.value,
        'sConta': oConta.value,
        'iStatus': oStatus.value,
        'sIdEmpresa': oIdEmpresa.value
      };
      new AjaxRequest('cai4_debcontapedidoaguacontratoeconomia.RPC.php', oParametros, function (oRetorno, lErro) {

        alert(oRetorno.message);
        if (lErro) {
          return false;
        }

        oPedido.value = oRetorno.iCodigo;
      }).execute();
  });

  if (typeof oGet.lAlteracao == 'undefined') {
    oBtnPesquisar.hide();
  } else {
    oLookupPedido.abrirJanela(true);
    oBtnSalvar.setAttribute('disabled', 'disabled');
  }
</script>
</body>
</html>
