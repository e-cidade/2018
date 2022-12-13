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

require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$dtInicial = '01/01/'.db_getsession('DB_anousu');
list($iDiaInicial, $iMesInicial, $iAnoInicial) = explode("/", $dtInicial);

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
  db_app::load("scripts.js, strings.js, prototype.js");
  db_app::load("estilos.css, grid.style.css");
  db_app::load('widgets/DBDownload.widget.js');
  ?>
</head>
<body style="background-color: #CCCCCC; margin-top:25px">
<div class="container">
  <form name="formArquivosAC" id="formArquivosAC">
    <fieldset style="width: 500px;">
      <legend class="bold">Geração dos arquivos para o TCE/AC</legend>
      <table style="width: 100%;">
        <tr>
          <td style="width: 70px;"><b>Data Inicial:</b></td>
          <td>
            <?php
            db_inputdata('dtInicial', $iDiaInicial, $iMesInicial, $iAnoInicial, true, 'text', 1);
            ?>
          </td>
          <td style="width: 70px;"><b>Data Final:</b></td>
          <td>
            <?php
            db_inputdata('dtFinal', null, null, null, true, 'text', 1);
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <p>
      <input type="button" id="btnGerar" value="Gerar" />
    </p>
  </form>
</div>

<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>

<script>

  $('btnGerar').observe('click',
    function() {

      var oDataInicial = $('dtInicial');
      var oDataFinal   = $('dtFinal');

      if (oDataInicial.value == "") {
        return alert('O campo Data Inicial é de preenchimento obrigatório.');
      }

      if (oDataFinal.value == "") {
        return alert('O campo Data Final é de preenchimento obrigatório.');
      }

      if (js_comparadata(oDataFinal.value, oDataInicial.value, "<")) {
        return alert("Data Final deve ser maior ou igual a Data inicial.");
      }

      var oParametro = {
        sExecucao : 'gerarArquivo',
        dtInicial : oDataInicial.value,
        dtFinal   : oDataFinal.value
      };
      requisicaoAjax(oParametro, callbackGerarArquivo);
    }
  );

  function callbackGerarArquivo(oRetorno) {

    alert(oRetorno.sMessage.urlDecode());
    if (oRetorno.iStatus == 2) {
      return false;
    }

    var oDownload = new DBDownload();
    oDownload.addGroups("zip", "Arquivo");
    oDownload.addFile(oRetorno.sCaminhoArquivo.urlDecode(), oRetorno.sNomeArquivo.urlDecode(), "zip");
    oDownload.show();
  }


  function requisicaoAjax(oParametro, fnRetorno) {

    js_divCarregando('Aguarde, processando...', 'msgBox');

    new Ajax.Request(
      'con4_tceAC.RPC.php',
      {
        method: 'post',
        parameters: 'json='+Object.toJSON(oParametro),
        onComplete: function (oAjax) {

          js_removeObj('msgBox');
          var oRetorno = eval("("+oAjax.responseText+")");
          fnRetorno(oRetorno);
        }
      }
    );
  }

</script>
