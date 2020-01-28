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
require_once modification("dbforms/db_funcoes.php");

$iAno = db_getsession("DB_anousu");

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form id="form_emissao_parcial">
      <fieldset>
        <legend>Emissão Parcial</legend>

        <table>
          <tr>
            <td>
              <label for="x54_sequencial" class="bold">
                <a id="label_contrato">Contrato:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x54_sequencial" id="x54_sequencial" class="field-size2">
              <input type="text" name="descricao_contrato" id="descricao_contrato" data="z01_nome">
            </td>
          </tr>

          <tr>
            <td><label for="ano" class="bold">Ano:</label></td>
            <td><input type="text" name="ano" id="ano" value="<?= $iAno ?>" readonly="readonly" class="readonly field-size2"></td>
          </tr>

          <tr>
            <td><label class="bold" for="mes_inicial">Mês Inicial:</label></td>
            <td>
              <select name="mes_inicial" id="mes_inicial" class="field-size2">
                <?php foreach (DBDate::getMesesExtenso() as $iMes => $sMes): ?>
                  <option value="<?php echo $iMes ?>">
                    <?php echo $sMes ?>
                  </option>
                <?php endforeach ?>
              </select>
            </td>
          </tr>

          <tr>
            <td><label class="bold" for="mes_final">Mês Final:</label></td>
            <td>
              <select name="mes_final" id="mes_final" class="field-size2">
                <?php foreach (DBDate::getMesesExtenso() as $iMes => $sMes): ?>
                  <option value="<?php echo $iMes ?>">
                    <?php echo $sMes ?>
                  </option>
                <?php endforeach ?>
              </select>
            </td>
          </tr>
        </table>
      </fieldset>

      <input type="button" id="emitir" value="Emitir">
    </form>
  </div>

  <?php db_menu(); ?>

  <script type="text/javascript">
    (function () {

      var oContrato   = $("x54_sequencial");
      var oAno        = $("ano");
      var oMesInicial = $("mes_inicial");
      var oMesFinal   = $("mes_final");

      var oContratoLookup = new DBLookUp($('label_contrato'), oContrato, $('descricao_contrato'), {
        "sObjetoLookUp": "db_iframe_aguacontrato",
        "sArquivo": "func_aguacontrato.php",
        "sLabel": "Pesquisar"
      });

      $("emitir").observe("click", function () {

        if (empty(oContrato.value)) {
          return alert('Contrato não informado.');
        }

        if (parseInt(oMesInicial.value) > parseInt(oMesFinal.value)) {
          return alert('Mês Inicial não pode ser maior que Mês Final.');
        }

        var sUrl = 'agu4_emissaocarnes.RPC.php';
        var oParametros = {
          'exec'        : 'emissaoParcial',
          'iAno'        : oAno.value,
          'iMesInicial' : oMesInicial.value,
          'iMesFinal'   : oMesFinal.value,
          'iContrato'   : oContrato.value
        };

        new AjaxRequest(sUrl, oParametros, function (oRetorno, lErro) {

          alert(oRetorno.mensagem);
          if (lErro) {
            return false;
          }

          var oDownload = new DBDownload();
          for (var oArquivo of oRetorno.aArquivos) {
            oDownload.addFile(oArquivo.link, oArquivo.nome);
          }
          oDownload.show();
        }).execute();
      });
    })();
  </script>
</body>
</html>
