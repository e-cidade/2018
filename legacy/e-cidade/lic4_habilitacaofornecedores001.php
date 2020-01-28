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
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbcomboBox.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">

  <form name="for" method="post">
    <fieldset>
      <legend>Habilitação dos Fornecedores da Licitação</legend>
      <table width="400px;">
        <tr>
          <td width="60px;">
            <label for="licitacao_codigo" class="bold">
              <a id="licitacao_ancora" >Licitação:</a>
            </label>
          </td>
          <td>
            <?php
            db_input("l20_codigo", 10, 0, true);
            db_input("l20_anousu", 10, 0, true, 'hidden');
            ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <div style="margin-top: 5px;" id="grid_fornecedores"></div>
          </td>
        </tr>
      </table>
    </fieldset>
    <input id="btnSalvar" type="button" value="Salvar" />
  </form>

</div>
<script type="text/javascript">

  var sRPC = 'lic4_habilitacaofornecedores.RPC.php';

  var oComboBox         = null;
  var oGridFornecedores = null;

  var oLicitacaoAncora    = $('licitacao_ancora');
  var oLicitacaoCodigo    = $('l20_codigo');
  var oLicitacaoDescricao = $('l20_anousu');
  var oBtnSalvar          = $('btnSalvar');

  oBtnSalvar.onclick = function() {

    var aFornecedores = oGridFornecedores.getRows().map(function(oRow) {
      return { codigo: oRow.aCells[0].getValue(), situacao: oRow.aCells[2].getValue()};
    });

    if (aFornecedores.length == 0) {
      return alert("Nenhum Fornecedor cadastrado para a Licitação selecionada.");
    }

    var oParametros = {
      exec         : "salvarHabilitacao",
      fornecedores : aFornecedores
    };

    new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

      alert(oRetorno.mensagem.urlDecode());

      if (!lErro) {
        buscaFornecedores();
      }

    }).setMessage("Salvando habilitação dos fornecedores da licitação. Aguarde...").execute();
  };

  var oLookUpLicitacao = new DBLookUp(oLicitacaoAncora, oLicitacaoCodigo, oLicitacaoDescricao, {
    "sArquivo"              : "func_liclicita.php",
    "sObjetoLookUp"         : "db_iframe_liclicita",
    "sLabel"                : "Pesquisar Licitação"
  });

  oLookUpLicitacao.setCallBack('onClick', buscaFornecedores);

  function buscaFornecedores() {

    oParametros = {
      exec             : "getFornecedoresLicitacao",
      codigo_licitacao : oLicitacaoCodigo.value
    };
    new AjaxRequest(sRPC, oParametros, function(oRetorno, lErro) {

      if (lErro) {
        return alert(oRetorno.mensagem.urlDecode());
      }

      oGridFornecedores.clearAll(true);

      if (oRetorno.aFornecedores.length == 0) {

        oBtnSalvar.disabled = true;
        return false;
      }

      var oFornecedor = null;
      var sComboBox   = "";
      for(var iFornecedor = 0; iFornecedor < oRetorno.aFornecedores.length; iFornecedor++) {

        oFornecedor = oRetorno.aFornecedores[iFornecedor];

        oComboBox.sName = "combo" + iFornecedor;
        oComboBox.setValue(oFornecedor.situacao);
        sComboBox = oComboBox.toInnerHtml();
        oGridFornecedores.addRow([oFornecedor.codigo, oFornecedor.nome.urlDecode(), sComboBox]);
      }
      oGridFornecedores.renderRows();
      oBtnSalvar.disabled = false;
    }).setMessage("Aguarde, carregando fornecedores da Licitação.").execute();
  }

  function iniciar() {

    oComboBox = new DBComboBox("combo1", "combo1", null);
    oComboBox.addItem("0", "Selecione");
    oComboBox.addItem("1", "Habilitado");
    oComboBox.addItem("2", "Inabilitado");
    oComboBox.addItem("3", "Não compareceu");

    oGridFornecedores = new DBGrid("oGridFornecedores");
    oGridFornecedores.nameInstace = "oGridFornecedores";
    oGridFornecedores.setCellWidth(["10px", "240px", "120px"]);
    oGridFornecedores.setHeader(["Código", "Fornecedor", "Situação"]);
    oGridFornecedores.aHeaders[0].lDisplayed = false;
    oGridFornecedores.show($('grid_fornecedores'));
    oGridFornecedores.clearAll(true);

    oBtnSalvar.disabled = true;
  }
  iniciar();

</script>
<?php db_menu(); ?>
</body>
</html>
