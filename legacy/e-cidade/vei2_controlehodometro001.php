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
require_once("libs/db_stdlib.php");
require_once("libs/db_utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("dbforms/db_funcoes.php");
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">

    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>

    <link href="estilos.css" rel="stylesheet" type="text/css">

  </head>

  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

    <div class="container">
      <form id="frmControleHodometro" method="post" action="">
        <table>
          <tr>
            <td align="center">
              <fieldset>
                <legend>Controle de Hodômetro de Veículos</legend>
                <table style="width: 100%;">
                  <tr>
                    <td><label class="bold" for="periodo_inicio">Período:</label></td>
                    <td>
                      <?php
                      db_inputdata("periodo_inicio", "", "", "", true, "text", 1);
                      ?> <b>até</b>
                      <?php
                      db_inputdata("periodo_fim", "", "", "", true, "text", 1);
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td width="110px"><label class="bold" for="tipo_relatorio">Tipo de Impressão:</label></td>
                    <td>
                      <?php
                      $aOpcoes = array(0 => "PDF", 1 => "CSV");
                      db_select("tipo_relatorio", $aOpcoes, true, 1, 'style="width:100px;"');
                      ?>
                    </td>
                  </tr>
                </table>

                <!-- Lancador veículos -->
                <div id="lancadorVeiculos"></div>

              </fieldset>
              <input type="button" name="btnEmitir" id="btnEmitir" value="Emitir" onclick="js_emitir()">
            </td>
          </tr>
        </table>
      </form>
    </div>
    <?php db_menu(); ?>
  <script>

    const URL_RELATORIO   = "vei2_controlehodometro002.php";
    var oPeriodoInicio    = $('periodo_inicio');
    var oPeriodoInicioDia = $('periodo_inicio_dia');
    var oPeriodoInicioMes = $('periodo_inicio_mes');
    var oPeriodoInicioAno = $('periodo_inicio_ano');
    var oPeriodoFim       = $('periodo_fim');
    var oPeriodoFimDia    = $('periodo_fim_dia');
    var oPeriodoFimMes    = $('periodo_fim_mes');
    var oPeriodoFimAno    = $('periodo_fim_ano');
    var oDivLancadorVeic  = $('lancadorVeiculos');
    var oTipoRelatorio    = $('tipo_relatorio');

    oLancadorVeiculos = new DBLancador('lancadorVeiculos');
    oLancadorVeiculos.setNomeInstancia('oLancadorVeiculos');
    oLancadorVeiculos.setLabelAncora('Veículo:');
    oLancadorVeiculos.setTextoFieldset('Seleção de Veículos');
    oLancadorVeiculos.setParametrosPesquisa('func_veiculos.php', ['0','1']);
    oLancadorVeiculos.setTituloJanela("Pesquisa de Veículos");
    oLancadorVeiculos.setGridHeight(100);
    oLancadorVeiculos.show(oDivLancadorVeic);

    function js_emitir() {

      var sPeriodoInico = "";
      var sPeriodoFim   = "";
      var aVeiculos     = [];

      if (!empty(oPeriodoInicio.value)) {
        sPeriodoInico = oPeriodoInicioAno.value + "-" + oPeriodoInicioMes.value + "-" + oPeriodoInicioDia.value;
      }

      if (!empty(oPeriodoFim.value)) {
        sPeriodoFim = oPeriodoFimAno.value + "-" + oPeriodoFimMes.value + "-" + oPeriodoFimDia.value;
      }
      var oDataInicial = new Date(oPeriodoInicioAno.value, oPeriodoInicioMes.value, oPeriodoInicioDia.value, 0, 0, 0);
      var oDataFinal   = new Date(oPeriodoFimAno.value, oPeriodoFimMes.value, oPeriodoFimDia.value, 0, 0, 0);

      if (empty(oPeriodoInicio.value)) {

        alert("A Data Inicial do campo Período é de preenchimento obrigatório.");
        return false;
      }

      if (empty(oPeriodoFim.value)) {

        alert("A Data Final do campo Período é de preenchimento obrigatório.");
        return false;
      }

      if (oDataInicial.valueOf() > oDataFinal.valueOf()) {

        alert("A Data Final do campo Período deve ser maior ou igual a Data Inicial.");
        return false;
      }

      if (oPeriodoFimAno.value != oPeriodoInicioAno.value || oPeriodoFimMes.value != oPeriodoInicioMes.value) {

        alert("A data inicial e final do Período devem estar dentro da mesma competência.");
        return false;
      }

      var aVeiculosLancador = oLancadorVeiculos.getRegistros(false);
      for (var iVeiculo = 0; iVeiculo < aVeiculosLancador.length; iVeiculo++) {
        aVeiculos.push(aVeiculosLancador[iVeiculo].sCodigo);
      }

      var iTipoRelatorio = oTipoRelatorio.value;
      if (iTipoRelatorio == 1) {

        oParametros = {
          periodo_inicial : sPeriodoInico,
          periodo_final   : sPeriodoFim,
          aVeiculos       : aVeiculos.join(","),
          iTipoRelatorio  : iTipoRelatorio
        };

        new AjaxRequest(URL_RELATORIO, oParametros, function(oRetorno, lErro) {

          if (lErro || oRetorno.erro) {

            alert(oRetorno.mensagem.urlDecode());
            return false;
          }

          var oDownload = new DBDownload();
          oDownload.setHelpMessage('Clique no link abaixo para fazer download do relatório.');
          oDownload.addFile(oRetorno.caminho_relatorio, 'Relatório de Controle de Hodômetro');
          oDownload.show();
        }).setMessage("Gerando relatório, aguarde...").execute();
          return false;
      }

      var sQuery = '?';
      sQuery += 'periodo_inicial=' + sPeriodoInico;
      sQuery += '&periodo_final='  + sPeriodoFim;
      sQuery += '&iTipoRelatorio=' + iTipoRelatorio;
      if (aVeiculos.length > 0) {
        sQuery += '&aVeiculos=' + aVeiculos.join(",");
      }

      var iHeight = (screen.availHeight - 40);
      var iWidth  = (screen.availWidth - 5);
      var sOpcoes = 'width=' + iWidth + ',height=' + iHeight + ',scrollbars=1,location=0';
      var oJanela = window.open(URL_RELATORIO + sQuery, '', sOpcoes);
      oJanela.moveTo(0, 0);
    }

  </script>
  </body>
</html>
