<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once "libs/db_stdlib.php";
require_once "libs/db_utils.php";
require_once "libs/db_app.utils.php";
require_once "libs/db_conecta.php";
require_once "libs/db_sessoes.php";
require_once "libs/db_usuariosonline.php";
require_once "dbforms/db_funcoes.php";

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <?php
    db_app::load("scripts.js, strings.js, prototype.js, estilos.css, widgets/DBLancador.widget.js");
  ?>
</head>
<body class="body-default">
  <div class="container">
    <form method="post">
      <fieldset>
        <legend class="bold">Emissão de Atas</legend>
        <table>
          <tr>
            <td>
              <label for="dtInicial" class="bold">Data de Vigência:</label>
            </td>
            <td>
              <?php
                $SdtInicial = "Data Inicial";
                db_inputdata('dtInicial', null, null, null, true, 'text', 1);
              ?>
            </td>
            <td>
              <label for="dtFinal" class="bold">Até:</label>
            </td>
            <td>
              <?php
                $SdtFinal = "Data Final";
                db_inputdata('dtFinal', null, null, null, true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="fornecedor" class="bold"><?php echo db_ancora('Fornecedor:', ' oFornecedor.pesquisa(true)', 1); ?></label>
            </td>
            <td colspan="3">
              <?php
                $Sfornecedor = "Fornecedor";
                db_input('fornecedor', 5, 1, true, 'text', 1, "onchange='oFornecedor.pesquisa(false)'");
                db_input('descricao_fornecedor', 30, 1, true, 'text', 3);
              ?>
            </td>
          </tr>
        </table>
        <div class="text-left" id="lancadorMaterial"></div>
      </fieldset>
      <input type="button" id="btnProcessar" value="Processar"/>
    </form>
  </div>
  <?php db_menu(); ?>
</body>
<script>
  (function(exports) {

    const MENSAGEM = 'patrimonial.compras.com2_relatorioatasvigentes.';

    var oLancadorMaterial = new DBLancador('material');

    oLancadorMaterial.setNomeInstancia('oLancadorMaterial');
    oLancadorMaterial.setLabelAncora('Material:');
    oLancadorMaterial.setLabelValidacao('Material');
    oLancadorMaterial.setParametrosPesquisa('func_pcmater.php', ['pc01_codmater', 'pc01_descrmater']);
    oLancadorMaterial.setGridHeight(100);
    oLancadorMaterial.setTextoFieldset('Materiais');
    oLancadorMaterial.setTituloJanela('Pesquisar Material');
    oLancadorMaterial.show($('lancadorMaterial'));

    var oFornecedor = {
      pesquisa : function(lMostra) {

        var sFuncao = (lMostra ? "ancora|z01_numcgm|z01_nome" : "tela&pesquisa_chave=" + $('fornecedor').value );

        if (!lMostra && empty($('fornecedor').value)) {
          $('descricao_fornecedor').value = '';
          return;
        }

        js_OpenJanelaIframe( 'top.corpo',
                             'db_iframe_nome',
                             'func_nome.php?funcao_js=parent.oFornecedor.preenche.' + sFuncao,
                             'Pesquisa de Fornecedor', lMostra);
      },
      preenche : {
        ancora : function(sCodigo, sNome) {

          db_iframe_nome.hide();
          $('fornecedor').value = sCodigo;
          $('descricao_fornecedor').value = sNome;
        },
        tela : function(lErro, sDescricao) {

          if (lErro) {
            $('fornecedor').value = '';
          }

          $('descricao_fornecedor').value = sDescricao;
        }
      }
    }

    $('btnProcessar').observe('click', function() {

      var oDataInicial = $('dtInicial');
      var oDataFinal   = $('dtFinal');

      if (oDataInicial.value == '') {
        return alert(_M(MENSAGEM+'data_inicial_vazio'));
      }

      if (oDataFinal.value == '') {
        return alert(_M(MENSAGEM+'data_final_vazio'));
      }

      if (js_comparadata(oDataInicial.value, oDataFinal.value, '>')) {
        return alert(_M(MENSAGEM+'data_inicial_final_conflito'));
      }

      var aMateriais = [];
      oLancadorMaterial.getRegistros().forEach(function(oRegistro) {
        aMateriais.push(oRegistro.sCodigo);
      });

      var sQueryString = 'dtInicial='+oDataInicial.value+'&dtFinal='+oDataFinal.value
                       + "&fornecedor=" + $('fornecedor').value + "&materiais=" + aMateriais.join(',');
      var oJanela = window.open('com2_relatorioatasvigentes002.php?'+sQueryString,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      oJanela.moveTo(0,0);
    });

    $('fornecedor').value = '';
    $('descricao_fornecedor').value = '';

    exports.oLancadorMaterial = oLancadorMaterial;
    exports.oFornecedor = oFornecedor;
  })(this);
</script>
</html>