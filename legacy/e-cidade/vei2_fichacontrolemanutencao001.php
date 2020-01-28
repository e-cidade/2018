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
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

$clrotulo = new rotulocampo();
$clrotulo->label("ve01_codigo");
$clrotulo->label("ve01_placa");
$clrotulo->label("ve62_situacao");
$clrotulo->label("ve62_veiculos");
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

    <link href="estilos.css" rel="stylesheet" type="text/css">

  </head>

  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">

    <div class="container">
      <form id="frmFichaControle" method="post" action="">
        <table>
          <tr>
            <td align="center">
              <fieldset>
                <legend>Controle de Manutenção de Veículo</legend>
                <table>
                  <tr>
                    <td><label class="bold" for="periodo_inicio">Período:</label></td>
                    <td>
                      <?php
                      $Lperiodo_inicio = "Data Inicial";
                      db_inputdata("periodo_inicio", "", "", "", true, "text", 1, 'class="field-size2"');
                      ?> <b>até</b>
                      <?php
                      $Lperiodo_final = "Data Final";
                      db_inputdata("periodo_final", "", "", "", true, "text", 1, 'class="field-size2"');
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td><label class="bold" for="codigo_veiculo"><a id="buscarVeiculo"><?= $Lve62_veiculos?></a></label></td>
                    <td>
                      <?php
                      db_input("ve01_codigo", 10, $Ive01_codigo, true, "text", 1);
                      db_input("ve01_placa", 10, $Ive01_placa, true, "text");
                      ?>
                    </td>
                  </tr>
                  <tr>
                    <td><label class="bold" for="situacao"><?= $Lve62_situacao?></label> </td>
                    <td>
                      <?php
                      $aSituacoes = array(
                      ""                                    =>  "Todos",
                      VeiculoManutencao::SITUACAO_REALIZADO => "Realizado",
                      VeiculoManutencao::SITUACAO_PENDENTE  => "Pendente"
                      );
                      db_select("situacao", $aSituacoes, true, 2, 'class="field-size2"');
                      ?>
                    </td>
                  </tr>
                </table>
              </fieldset>
              <input type="button" id="btnEmitir" name="btnEmitir" value="Emitir" onclick="js_emitir()" />
            </td>
          </tr>
        </table>
      </form>
    </div>
    <?php
    db_menu();
    ?>
  <script>
    const URL_RELATORIO   = "vei2_fichacontrolemanutencao002.php";
    var oAncoraVeiculo    = $('buscarVeiculo');
    var oPeriodoInicio    = $('periodo_inicio');
    var oPeriodoInicioDia = $('periodo_inicio_dia');
    var oPeriodoInicioMes = $('periodo_inicio_mes');
    var oPeriodoInicioAno = $('periodo_inicio_ano');
    var oPeriodoFim       = $('periodo_final');
    var oPeriodoFimDia    = $('periodo_final_dia');
    var oPeriodoFimMes    = $('periodo_final_mes');
    var oPeriodoFimAno    = $('periodo_final_ano');
    var oCodigoVeiculo    = $('ve01_codigo');
    var oDescricaoVeiculo = $('ve01_placa');
    var oSituacao         = $('situacao');

    oLookupVeiculo = new DBLookUp(oAncoraVeiculo, oCodigoVeiculo, oDescricaoVeiculo, {
        "sArquivo"              : "func_veiculosalt.php",
        "sObjetoLookUp"         : "db_iframe_veiculos",
        "sLabel"                : "Pesquisar Veículo"
    });

    oDescricaoVeiculo.className  = "field-size4";
    oDescricaoVeiculo.className += " readOnly";

    function js_emitir() {

      var iVeiculo      = oCodigoVeiculo.value;
      var sPeriodoInico = oPeriodoInicioAno.value + "-" + oPeriodoInicioMes.value + "-" + oPeriodoInicioDia.value;
      var sPeriodoFim   = oPeriodoFimAno.value + "-" + oPeriodoFimMes.value + "-" + oPeriodoFimDia.value;
      var iSituacao     = oSituacao.value;

      var oDataInicial = new Date(oPeriodoInicioAno.value, oPeriodoInicioMes.value, oPeriodoInicioDia.value, 0, 0, 0);
      var oDataFinal   = new Date(oPeriodoFimAno.value, oPeriodoFimMes.value, oPeriodoFimDia.value, 0, 0, 0);

      if (empty(oPeriodoInicio.value)) {

        alert("O campo Data Inicial do Período é de preenchimento obrigatório.");
        return false;
      }

      if (empty(oPeriodoFim.value)) {

        alert("O campo Data Final do Período é de preenchimento obrigatório.");
        return false;
      }

      if (oDataInicial.valueOf() > oDataFinal.valueOf()) {

        alert('Data Final do Período deve ser maior ou igual a Data Inicial.');
        return false;
      }

      if (oPeriodoInicioAno.value != oPeriodoFimAno.value) {

        alert("O Período informado deve estar dentro do mesmo exercício.");
        return false;
      }

      if (empty(oCodigoVeiculo.value)) {

        alert("O campo Veículo é de preenchimento obrigatório.");
        return false;
      }

      var sQuery = '?';
      sQuery += 'periodo_inicial=' + sPeriodoInico;
      sQuery += '&periodo_final='   + sPeriodoFim;
      sQuery += '&iVeiculo='        + iVeiculo;
      if (iSituacao != 0 && iSituacao != "") {
        sQuery += '&iSituacao=' + iSituacao;
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
