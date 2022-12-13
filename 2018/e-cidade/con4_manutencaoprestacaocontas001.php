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
require_once("libs/db_app.utils.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
$clrotulo = new rotulocampo;
$clrotulo->label("e60_codemp");

?>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <?php
    db_app::load("scripts.js, prototype.js, datagrid.widget.js, messageboard.widget.js, dbtextField.widget.js");
    db_app::load("windowAux.widget.js, strings.js, AjaxRequest.js");
    db_app::load("grid.style.css, estilos.css");
    ?>
  </head>
<body bgcolor="#CCCCCC">
  <?php
  if (db_getsession("DB_id_usuario") != 1) {

    echo "<br><center><br><H2>Essa rotina apenas poderá ser usada pelo usuario dbseller</h2></center>";
    exit;
  }
  ?>

  <div class="container">
    <fieldset style="width: 800px;">
      <legend class="bold">Empenho com mais de uma Prestação de Contas</legend>
      <div id="ctnGridEmpenho"></div>
    </fieldset>
    <input type="button" id="btnLimpar" value="Limpar Informações">
  </div>

  <div class="container">
    <fieldset style="width: 800px;">
      <legend class="bold">Prestação de Contas do Empenho</legend>
      <table>
        <tr>
          <td class='bold'>
            Empenho:
          </td>
          <td>
            <?php
            db_input("sequencial_empenho", 10, 1, true, 'text', 3);
            ?>
          </td>
          <td class='bold'>
            Código do Empenho:
          </td>
          <td>
            <?php
            db_input("codigo_empenho", 10, 1, true, 'text', 3);
            ?>
          </td>
        </tr>
      </table>
      <div id="ctnGridPrestacao"></div>
    </fieldset>
  </div>
</body>

<script>

  var oInputCodigo     = $('codigo_empenho');
  var oInputSequencial = $('sequencial_empenho');
  var sUrlRPC = 'con4_manutencaoprestacaocontas.RPC.php';
  var oGridPrestacao = new DBGrid('oGridPrestacao');
  oGridPrestacao.nameInstance = 'oGridPrestacao';
  oGridPrestacao.setHeader(['Código', 'Data', 'Tipo', 'Acerto', 'Conferência', 'Movimento', 'Ação']);
  oGridPrestacao.setCellAlign(['right', 'center', 'left', 'center', 'center', 'center', 'center']);
  oGridPrestacao.setCellWidth(['10%', '10%', '20%', '10%', '10%', '10%', '10%']);
  oGridPrestacao.show($('ctnGridPrestacao'));

  var oGridEmpenho = new DBGrid('oGridEmpenho');
  oGridEmpenho.nameInstance = 'oGridEmpenho';
  oGridEmpenho.setHeader(['Sequencial', 'Código', 'Fornecedor', 'Ação']);
  oGridEmpenho.setCellAlign(['center', 'center', 'left', 'center']);
  oGridEmpenho.setCellWidth(['20%', '20%', '50%', '10%']);
  oGridEmpenho.show($('ctnGridEmpenho'));


  function carregarEmpenhos () {

    new AjaxRequest(
      sUrlRPC,
      {exec:'getEmpenhos'},
      function (oRetorno, lErro) {

        oGridEmpenho.clearAll(true);
        if (lErro) {
          return alert(oRetorno.mensagem.urlDecode());
        }

        oRetorno.aEmpenhos.each(
          function (oEmpenho, iIndice) {

            var sBotao = "<input type='button' value='Carregar' onclick='carregarInformacoesPrestacao("+iIndice+")' />";

            oGridEmpenho.addRow([
              oEmpenho.sequencial,
              oEmpenho.codigo+"/"+oEmpenho.ano,
              oEmpenho.fornecedor.urlDecode(),
              sBotao
            ]);
          }
        );
        oGridEmpenho.renderRows();
      }
    ).setMessage('Aguarde, carregando empenhos...').execute();
  }


  function carregarInformacoesPrestacao(iRegistroGrid) {

    var oDadosLinha = oGridEmpenho.aRows[iRegistroGrid];
    oInputSequencial.value = oDadosLinha.aCells[0].getValue();
    oInputCodigo.value = oDadosLinha.aCells[1].getValue();

    var oParametro = {
      exec : 'getPrestacaoDeContas',
      sequencial : oInputSequencial.value
    };

    new AjaxRequest(
      sUrlRPC,
      oParametro,
      function (oRetorno, lErro) {

        oGridPrestacao.clearAll(true);
        if (lErro) {
          return alert(oRetorno.mensagem.urlDecode());
        }

        oRetorno.aPrestacao.each(
          function (oPrestacao) {

            var sBotao = "<input type='button' value='Remover' onclick='removerPrestacao("+oPrestacao.e45_sequencial+")' />";
            oGridPrestacao.addRow([
              oPrestacao.e45_sequencial,
              js_formatar(oPrestacao.e45_data, 'd'),
              oPrestacao.e44_descr.urlDecode(),
              js_formatar(oPrestacao.e45_acerta, 'd'),
              js_formatar(oPrestacao.e45_conferido, 'd'),
              oPrestacao.e45_codmov,
              sBotao
            ]);
          }
        );
        oGridPrestacao.renderRows();

      }
    ).setMessage('Aguarde, carregando informações...').execute();
  }

  function removerPrestacao(iSequencialPrestacao) {

    if (!confirm("Confirma a exclusão da prestação de contas?")) {
      return false;
    }

    if (!confirm("Este procedimento não poderá ser desfeito. Ainda assim deseja excluir?")) {
      return false;
    }

    new AjaxRequest(
      sUrlRPC,
      {exec : 'excluir', codigo : iSequencialPrestacao},
      function (oRetorno, lErro) {

        alert(oRetorno.mensagem.urlDecode());
        $('btnLimpar').click();

      }
    ).setMessage('Aguarde, excluindo registros...').execute();
  }


  $('btnLimpar').observe('click',
    function () {

      oInputSequencial.value = '';
      oInputCodigo.value     = '';
      oGridPrestacao.clearAll(true);
      oGridEmpenho.clearAll(true);
      carregarEmpenhos();
    }
  );

  carregarEmpenhos();
</script>
</html>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>