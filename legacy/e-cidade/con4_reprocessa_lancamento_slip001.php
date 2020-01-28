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
require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/db_stdClass.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/DBDate.php");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body style="background-color: #cccccc; margin-top: 30px;">

  <div class="container">
    <fieldset>
      <legend class="bold">Reprocessa Lançamentos Slip (c/cheque)</legend>
      <table>
        <tr>
          <td class="bold">Data Inicial:</td>
          <td>
            <?php
            db_inputdata('dtInicial', null, null, null, true, 'text', 1);
            ?>
          </td>
          <td class="bold">Data Final:</td>
          <td>
            <?php
            db_inputdata('dtFinal', null, null, null, true, 'text', 1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <p>
      <input type="button" id="btnProcessar" value="Processar" />
    </p>
  </div>
</body>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</html>
<script>

  function processar() {

    var oParametro = {
      exec      : "reprocessarSlip",
      dtInicial : $F('dtInicial'),
      dtFinal   : $F('dtFinal')
    };

    requisicaoAjax(oParametro, callbackProcessar);
  }

  function callbackProcessar(oRetorno) {

    $('btnProcessar').disabled = false;
    alert(oRetorno.mensagem.urlDecode());
  }

  $('btnProcessar').observe('click',
    function () {

      var oDataInicial = $('dtInicial');
      var oDataFinal   = $('dtFinal');
      if (oDataInicial.value == "") {
        return alert("Data inicial não informada.");
      }
      if (oDataFinal.value == "") {
        return alert("Data final não informada.");
      }

      if (js_comparadata(oDataInicial.value, oDataFinal.value, ">")) {
        return alert("Data inicial maior que a data final.");
      }

      if (!confirm("Este procedimento irá excluir os lançamentos contábeis e recriá-los com base na tesouraria.\n\nConfirma o procedimento?")) {
        return false;
      }

      $('btnProcessar').disabled = true;
      processar();
    }
  );

  function requisicaoAjax(oParametro, fCallBack) {

    js_divCarregando("Aguarde, processando informações...", "msgBox");

    new Ajax.Request(
      'con4_reprocessaslip004.RPC.php',
      {
        method: 'post',
        parameters: 'json='+Object.toJSON(oParametro),

        onComplete: function (oAjax) {

          js_removeObj("msgBox");
          var oRetorno = eval("("+oAjax.responseText+")");
          fCallBack(oRetorno);
        }
      }
    );

  }
</script>