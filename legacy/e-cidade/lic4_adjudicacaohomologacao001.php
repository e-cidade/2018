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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo();

$oGet = db_utils::postMemory($_GET);
$sLegenda = $oGet->situacao == SituacaoLicitacao::SITUACAO_ADJUDICADA ? "Adjudicar Licitação" : "Homologar Licitação";

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">

  <fieldset style="width: 200px;">
    <legend class="bold"><?php echo $sLegenda; ?></legend>
    <table>
      <tr>
        <td class="bold">
          <label for="codigo_licitacao">
          <?php
          db_ancora('Licitação:', 'pesquisaLicitacao()', 1);
          ?>
          </label>
        </td>
        <td>
          <?php
          db_input('codigo_licitacao', 10, 1, true, 'text', 3);
          ?>
        </td>
      </tr>
      <tr>
        <td class="bold"><label for="data">Data:</label></td>
        <td>
          <?php
          db_inputdata('data', null, null, null, true, 'text', 1);
          ?>
        </td>
      </tr>
    </table>
  </fieldset>
  <br />
  <input type="button" id="btnSalvar" value="Salvar" onclick="salvar()"/>
  <input type="button" id="btnExcluir" value="Excluir" onclick="excluir()"/>

</div>
<?php db_menu(); ?>
</body>
</html>

<script type="text/javascript">

  var oCodigoLicitacao = $('codigo_licitacao');
  var oData = $('data');
  var oButtonExcluir = $('btnExcluir');
  oButtonExcluir.disabled = true;
  var oGet  = js_urlToObject();
  var sCaseBuscaDatas = null;
  const sUrlRPC = 'lic4_situacaolicitacao.RPC.php';
  const SITUACAO_HOMOLOGADA = 7;
  const SITUACAO_ADJUDICADA = 6;

  function salvar() {

    if (oCodigoLicitacao.value.trim() == "") {
      return alert("Campo Licitação é de preenchimento obrigatório.");
    }

    if (oData.value.trim() == "") {

      oData.focus();
      return alert("Campo Data é de preenchimento obrigatório.");
    }

    var oParametro = {
      exec : 'incluir',
      iCodigoLicitacao : oCodigoLicitacao.value,
      iTipoSituacao : oGet.situacao == SITUACAO_ADJUDICADA ? SITUACAO_ADJUDICADA : SITUACAO_HOMOLOGADA,
      data : oData.value
    };

    new AjaxRequest(
      sUrlRPC,
      oParametro,
      function (oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());
        if (!lErro) {
          limparCampos();
        }
      }
    ).setMessage('Aguarde, salvando informações...').execute();
  }


  function excluir() {

    if (oCodigoLicitacao.value == "") {
      return alert("Campo Licitação é de preenchimento obrigatório.");
    }

    var iTipoSituacao = oGet.situacao == SITUACAO_ADJUDICADA ? SITUACAO_ADJUDICADA : SITUACAO_HOMOLOGADA;
    new AjaxRequest(
      sUrlRPC,
      {exec : 'excluir', iCodigoLicitacao : oCodigoLicitacao.value, iSituacao : iTipoSituacao},
      function (oRetorno, lErro) {

        alert(oRetorno.message.urlDecode());
        if (!lErro) {
          oButtonExcluir.disabled = true;
          limparCampos();
        }
      }
    ).setMessage('Aguarde, excluindo registro...').execute();
  }

  function getDadosLicitacao() {

    if (oCodigoLicitacao.value == "") {
      return false;
    }

    new AjaxRequest(
      sUrlRPC,
      {
        exec : oGet.situacao == SITUACAO_ADJUDICADA ? 'getDataAjudicacao' : 'getDataHomologacao',
        iCodigoLicitacao: oCodigoLicitacao.value
      },
      function (oRetorno, lErro) {

        if (lErro) {
          return alert(oRetorno.mensagem.urlDecode());
        }

        oButtonExcluir.disabled = true;
        if (!oRetorno.data) {
          return;
        }
        oData.value = js_formatar(oRetorno.data, 'd');
        oButtonExcluir.disabled = false;
      }
    ).setMessage('Aguarde, carregando informações...').execute();
  }

  function pesquisaLicitacao() {

    var sSituacoes = "1";
    if (oGet.situacao == SITUACAO_ADJUDICADA) {
      sSituacoes = "1,6";
    }

    if (oGet.situacao == SITUACAO_HOMOLOGADA) {
      sSituacoes = "6,7";
    }

    var sUrlLicitacao = "func_liclicita.php?situacao=" + sSituacoes + "&funcao_js=parent.preencheDadosLicitacao|l20_codigo";
    js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_liclicitasituacao', sUrlLicitacao, 'Pesquisa Licitações', true);
  }

  function preencheDadosLicitacao(iCodigoLicitacao) {

    oCodigoLicitacao.value = iCodigoLicitacao;
    db_iframe_liclicitasituacao.hide();
    getDadosLicitacao();
  }

  function limparCampos() {

    oCodigoLicitacao.value = '';
    oData.value = '';
  }


</script>