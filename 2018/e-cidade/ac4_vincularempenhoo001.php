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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
$oRotulo = new rotulocampo();
$oRotulo->label("ac16_sequencial");
$oRotulo->label("ac16_resumoobjeto");
$oRotulo->label("e60_codemp");
$Inumero_licitacao = 1;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/datagrid/plugins/DBHint.plugin.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>

  <div class="container">
  <fieldset style="width: 650px;">
    <legend>
      Vincular Contratos a Empenhos
    </legend>
    <table class="form-container">
      <tr>
        <td>
          <a id="sLabelContrato">Contrato:</a>
        </td>
        <td>
          <?php
            db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 1);
            db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset class="separator">
            <legend>Vincular Empenhos</legend>
            <table>

              <tr>

                <td>
                  <b>
                    <a id="sLabelEmpenho">Empenho:</a>
                  </b>
                </td>

                <td>
                  <?php
                  db_input('e60_codemp', 10, $Ie60_codemp, true, 'text', 1);
                  db_input('e60_numcgm', 10, '', true, 'hidden', 1);
                  ?>
                </td>

                <td>
                  <b>
                   <label for="numero_licitacao">Número Licitação:</label>
                  </b>
                </td>

                <td>
                  <?php
                  db_input('numero_licitacao', 10, null, true, 'text', 1, " placeholder='Número/Ano' ");
                  ?>
                </td>

                <td>
                  <input type="button" value="Adicionar" id="btnSalvar">
                </td>

              </tr>

            </table>
          </fieldset>

        </td>

      </tr>

      <tr>

        <td colspan="2" id="">
          <fieldset class="separator">
            <legend>Empenhos Vinculados ao Contrato</legend>
            <div id="container_empenhos"></div>
          </fieldset>
        </td>

      </tr>

    </table>
  </fieldset>
  </div>
  <?php db_menu(); ?>
</body>
</html>
<script>

  /**
   * Licitacação LookUp
   */
  const URL_RPC        = "ac4_vincularempenho.RPC.php";
  var oAcordo          = $('ac16_sequencial');
  var oEmpenho         = $('e60_codemp');
  var oNumeroLicitacao = $('numero_licitacao');

  $("numero_licitacao").addEventListener("input", function() {
    this.value = this.value.replace(/[^0-9\/]/g, '').replace(/(\/?)([0-9]*)(\/?)([0-9]{0,4})(.*)(\/?)/, '$2$3$4')
  });

  var oLookUpContratos = new DBLookUp($('sLabelContrato'), $('ac16_sequencial'), $('ac16_resumoobjeto'), {
    "sArquivo" : "func_acordo.php",
    "sObjetoLookUp" : "db_iframe_acordo",
    "sLabel" : "Pesquisar Contratos",
    "aParametrosAdicionais" :["descricao=true"]
  });

  var oLookUpEmpenho = new DBLookUp($('sLabelEmpenho'), $('e60_codemp'), $('e60_numcgm'), {
    "sArquivo" : "func_empempenho.php",
    "sObjetoLookUp" : "db_iframe_empempenho",
    "sLabel" : "Pesquisar Empenhos",

  });

  oLookUpEmpenho.callBackClick = function() {

    oLookUpEmpenho.oInputID.value = arguments[0]+"/"+arguments[2];

    var oObjetoLookUp = eval(oLookUpEmpenho.oParametros.sObjetoLookUp);
    oObjetoLookUp.hide();
    pesquisarLicitacaoEmpenho();

    return;
  }

  oLookUpEmpenho.getQueryStringChange = function() {

    var iEmpenho = oLookUpEmpenho.oInputID.value;
    var iAnoUsu  = 0;
    if (oLookUpEmpenho.oInputID.value.indexOf("/") !== false) {

      var aPartesEmpenho = iEmpenho.split("/");
      iEmpenho = aPartesEmpenho[0];
      iAnoUsu  = aPartesEmpenho[1];
    }
    var sQuery  = "";
    sQuery += oLookUpEmpenho.oParametros.sArquivo;
    sQuery += "?";
    sQuery += "pesquisa_chave=" + iEmpenho;
    sQuery += "&lPesquisaPorCodigoEmpenho=1";
    if (iAnoUsu > 0) {
      sQuery += "&iAnoEmpenho=" + iAnoUsu;
    }
    sQuery += "&";

    if ( oLookUpEmpenho.oParametros.aParametrosAdicionais.length > 0 ){
      sQuery += oLookUpEmpenho.oParametros.aParametrosAdicionais.join("&");
      sQuery += "&";
    }

    sQuery += "funcao_js=parent.DBLookUp.repository.getInstance("+oLookUpEmpenho.iReferencia+").callBackChange";
    sQuery += oLookUpEmpenho.oParametros.sQueryString;

    return sQuery;
  }

  oLookUpEmpenho.setCamposAdicionais(['e60_anousu']);

  oLookUpContratos.callBackChange  = function() {

    var aArgumentos = arguments,
      lErro       = null,
      sDescricao  = arguments[1];
     if (aArgumentos[2] === true) {

       oAcordo.value = '';
       sDescricao    = arguments[0];
     }
    $('ac16_resumoobjeto').value = sDescricao ;
    pesquisarEmpenhos();
  };

  pesquisarLicitacaoEmpenho = function() {

    var oParametro = {

      exec: 'getLicitacaoDoEmpenho',
      iNumeroEmpenho: oEmpenho.value
    }

    new AjaxRequest(URL_RPC, oParametro, function(oRetorno, lErro) {

      if (lErro) {

        alert(oRetorno.message.urlDecode());
        return false;
      }

      $('numero_licitacao').value = oRetorno.iNumeroLicitacao;
    })
      .setMessage("Aguarde, pesquisando empenhos vinculados ao acordo.")
      .execute();
  }

  pesquisarEmpenhos = function() {

    if (empty(oAcordo.value)) {
      return false;
    }

    var oParametro = {
      exec: 'getEmpenhos',
      iCodigoAcordo: oAcordo.value
    }

    new AjaxRequest(URL_RPC, oParametro, function(oRetorno, lErro) {

      if (lErro) {

        alert(oRetorno.message.urlDecode());
        return false;
      }
      oEmpenhosCollection.clear();
      for (var oEmpenho of oRetorno.aEmpenhos) {

        oEmpenhosCollection.add({
          codigo           : oEmpenho.sCodigo.urlDecode(),
          data             : oEmpenho.sDataEmissao.urlDecode(),
          credor           : oEmpenho.sNomeCredor.urlDecode(),
          numero_licitacao : oEmpenho.iNumeroLicitacao.urlDecode(),
          numero_empenho   : oEmpenho.iNumeroEmpenho
        });
      }
      oGridEmpenhos.reload();
      })
      .setMessage("Aguarde, pesquisando empenhos vinculados ao acordo.")
      .execute();
  }

  oLookUpContratos.setCallBack("onChange", pesquisarEmpenhos);
  oLookUpContratos.setCallBack("onClick", pesquisarEmpenhos);


  oLookUpEmpenho.setCallBack("onChange", pesquisarLicitacaoEmpenho);
  oLookUpEmpenho.setCallBack("onClick", pesquisarLicitacaoEmpenho);

  var oEmpenhosCollection = new Collection().setId('codigo');
  var oGridEmpenhos = DatagridCollection.create(oEmpenhosCollection).configure("order", false);

  oGridEmpenhos.addColumn("codigo",   {label : "Empenho",   "width" : "100px"});
  oGridEmpenhos.addColumn("data", {label : "Emissão", "width" : "60px"});
  oGridEmpenhos.addColumn("credor",  {label : "Credor",  "width" : "280px"});
  oGridEmpenhos.addColumn("numero_licitacao",   {label : "Licitação",   "width" : "70px", "align" : "center"});

  /**
   * Salvar Vínculo de Empenho com o Acordo
   */
  var salvar = function () {

    var sNumeroLicitacao = $('numero_licitacao').value;

    if (empty(oAcordo.value)) {

      alert('Informe um  acordo ');
      return false;
    }

    if (empty(oEmpenho.value)) {

      alert('Informe o número do empenho!');
      return false;
    }

    if (!empty(sNumeroLicitacao) && !sNumeroLicitacao.match(/([0-9]+)\/([0-9]{4})/)) {

      alert("O Número da Licitação é inválido. Informe Número/Ano da Licitacação.");
      return false;
    }

    var oParametro = {
      exec: 'salvar',
      iCodigoAcordo:    oAcordo.value,
      iNumeroEmpenho:   oEmpenho.value,
      iNumeroLicitacao: sNumeroLicitacao
    };

    new AjaxRequest(URL_RPC, oParametro, function(oRetorno, lErro) {

      alert(oRetorno.message.urlDecode());

      if (lErro) {
        return false;
      }
      oEmpenho.value            = '';
      oNumeroLicitacao.value    = '';
      oNumeroLicitacao.readOnly = false;
      pesquisarEmpenhos();
    })
      .setMessage("Aguarde, salvando os dados vinculados ao acordo.")
      .execute();
  };

  $('btnSalvar').observe('click', salvar);

  oGridEmpenhos.addAction("Excluir", null, function(oEvento, oItem) {

    if (!confirm("Deseja remover o empenho do contrato?")) {
      return false;
    }

    var oParametros = {
      "exec"         : "excluirVinculo",
      iCodigoAcordo  :    oAcordo.value,
      iNumeroEmpenho : oItem.numero_empenho
    };

    new AjaxRequest(URL_RPC, oParametros, function(oRetorno, lErro) {

      alert(oRetorno.message.urlDecode());
      if (lErro) {
        return false;
      }
      pesquisarEmpenhos();
    }).setMessage("Excluindo empenho.").execute();
  });
  oGridEmpenhos.show($("container_empenhos"));

</script>

