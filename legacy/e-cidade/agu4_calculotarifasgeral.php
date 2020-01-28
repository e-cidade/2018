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
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">

    <form action="" method="">

      <fieldset>
        <legend>Cálculo Geral</legend>

        <table>
          <tr>
            <td><label class="bold" for="ano">Ano:</label></td>
            <td><input type="text" name="ano" id="ano" value="<?php echo $iAno ?>" readonly="readonly" class="readonly field-size2"></td>
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

      <input type="button" id="calcular" value="Calcular">

    </form>

  </div>

  <?php db_menu(); ?>

  <script type="text/javascript">
    (function() {

      function calcular() {

        var oImplantacaoTarifa = new Date(2017, 6);
        var oAno        = $("ano");
        var oMesInicial = $("mes_inicial");
        var oMesFinal   = $("mes_final");

        if (parseInt(oMesInicial.value) > parseInt(oMesFinal.value)) {
          return alert("Mês Inicial não pode ser maior que Mês Final.");
        }

        var oPeriodoInicial = new Date(oAno.value, oMesInicial.value - 1);
        var oPeriodoFinal = new Date(oAno.value, oMesFinal.value - 1);
        if (oPeriodoInicial < oImplantacaoTarifa || oPeriodoFinal < oImplantacaoTarifa) {
          var mensagemAviso = "Somente é possível executar o cálculo de tarifas nesta rotina a partir do período de Julho/2017.\n\n";
          mensagemAviso += "Para executar o cálculo de taxas utilize a rotina:\nProcedimentos > Cálculo das Taxas > Cálculo Geral";
          return alert(mensagemAviso);
        }

        var sUrl = "agu4_calculotarifasgeral002.php";
        var oParametros = {
          "exec"        : "calculoGeral",
          "iAno"        : oAno.value,
          "iMesInicial" : oMesInicial.value,
          "iMesFinal"   : oMesFinal.value
        };

        js_OpenJanelaIframe(
          'CurrentWindow.corpo',
          'db_iframe_carne',
          sUrl + '?' + Object.toQueryString(oParametros),
          'Processando Cálculo Geral de Tarifas...',
          true
        );
      }

      $("calcular").observe("click", function () {
        calcular();
      });
    })();
  </script>
</body>
</html>
