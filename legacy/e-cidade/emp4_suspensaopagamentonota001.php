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
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">

  <fieldset style="width: 600px;">
    <legend id="legenda_suspensao" class="bold">Suspensão/Liberação de Pagamento da Nota de Liquidação</legend>
    <table style="width: 100%" border="0">
      <tr>
        <td class="bold" style="width: 120px;">
          <label for="e69_codnota" class="bold">
            <a id="nota_ancora">Nota de Liquidação:</a>
          </label>
        </td>
        <td>
          <input type="text" id="e69_codnota" name="e69_codnota" class="field-size2 readonly"/>
        </td>
      </tr>
      <tr>
        <td class="bold"><label id="label_data" for="data">Data da Operação:</label></td>
        <td>
          <?php
          list($iDia, $iMes, $iAno) = explode("-", date('d-m-Y', db_getsession('DB_datausu')));
          db_inputdata('data', $iDia, $iMes, $iAno, true, 'text', 1);
          ?>
        </td>
      </tr>
      <tr>
        <td colspan="2">
          <fieldset style="width: 97%">
            <legend class="bold"><label for="justificativa">Justificativa</label></legend>
            <textarea style="width: 100%; height: 100px;" id="justificativa"></textarea>
          </fieldset>
        </td>
      </tr>
    </table>
  </fieldset>
  <p>
    <input id="btnSalvar" value="Suspender/Liberar" type="button"/>
  </p>
</div>

<?php db_menu(); ?>
</body>
</html>
<script type="text/javascript">

  const MENSAGENS = "financeiro.empenho.emp4_suspensaopagamentonota.";
  const URL_RPC   = 'emp4_suspensaopagamentonota.RPC.php';

  var oAncora    = $('nota_ancora');
  var oCodigo    = $('e69_codnota');
  var oLegenda   = $('legenda_suspensao');
  var oBtnSalvar = $('btnSalvar');
  var oLabelData = $('label_data');

  oBtnSalvar.onclick = salvar;

  function salvar() {

    var iNotaEmpenho   = oCodigo.value;
    var sData          = $F('data');
    var sJustificativa = $F('justificativa');

    if (empty(iNotaEmpenho)) {
      return alert(_M(MENSAGENS + 'codigo_nota_obrigatorio'));
    }

    if (empty(sData)) {
      return alert(_M(MENSAGENS + 'data_obrigatorio'));
    }

    if (empty(sJustificativa)) {
      return alert(_M(MENSAGENS + 'justificativa_obrigatorio'));
    }

    var oParametros = {
      exec           : "executar",
      iNotaEmpenho   : iNotaEmpenho,
      sData          : sData,
      sJustificativa : sJustificativa
    };

    new AjaxRequest(URL_RPC, oParametros, retornoSalvar)
      .setMessage("Realizando a suspensão/liberação da nota. Por favor, aguarde.")
      .execute();
  }

  function retornoSalvar(oRetorno, lErro) {

    if (lErro) {
      return alert(oRetorno.message);
    }

    alert("Operação efetuada com sucesso.");
    limparTela();
  }

  function preencheNota() {

    var iNotaEmpenho = oCodigo.value;
    if (empty(iNotaEmpenho)) {
      return false;
    }

    $('justificativa'). value = '';

    var oParametros = {
      exec         : "liberadoPagamento",
      iNotaEmpenho : iNotaEmpenho
    };

    new AjaxRequest(URL_RPC, oParametros, retornoBuscaLiberacao)
      .setMessage("Buscando informações. Por favor, aguarde.")
      .execute();
  }

  function retornoBuscaLiberacao(oRetorno, lErro) {

    if (lErro) {
      return alert(_M(MENSAGENS + 'erro_busca_nota'));
    }

    var sLegenda   = "Suspensão de Pagamento da Nota de Liquidação";
    var sBotao     = "Suspender";
    var sLabelData = "Data de Suspensão:";
    if (!oRetorno.lLiberadoEmpenho) {

      sLegenda   = "Liberação de Pagamento da Nota de Liquidação";
      sBotao     = "Liberar";
      sLabelData = "Data de Liberação:"
    }

    oLegenda.innerHTML   = sLegenda;
    oBtnSalvar.value     = sBotao;
    oLabelData.innerHTML = sLabelData;
  }

  function limparTela() {

    oCodigo.value    = '';
    $('justificativa').value = '';
    oLegenda.innerHTML   = "Suspensão/Liberação de Pagamento da Nota de Liquidação";
    oBtnSalvar.value     = "Suspender/Liberar";
    oLabelData.innerHTML = "Data da Operação:";
  }

  var oLookUpNota = new DBLookUp(oAncora, oCodigo, oCodigo, {
      "sArquivo"      : "func_empnota.php",
      "sObjetoLookUp" : "db_iframe_empnota",
      "sLabel"        : "Pesquisar Nota de Liquidação",
      "aParametrosAdicionais" : ["suspensao=true", "lNaoTrazerAnuladas=true"],
      "fCallBack"     : preencheNota
    }
  );

  limparTela();
</script>