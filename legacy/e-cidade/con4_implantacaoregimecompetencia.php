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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));

$clacordo          = new cl_acordo;
$clacordocategoria = new cl_acordocategoria;
$clacordo->rotulo->label();
$clacordocategoria->rotulo->label();

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/numbers.js"></script>
    <link href="estilos.css" rel="stylesheet" type="text/css">
    <style>
      .grid {
        width: 800px;
      }

      .avisoLinha {
        display: none;
      }

      .avisoLinha span{
        width: 25px;
        height: 14px;
        background: #f78481;
        float: left;
        margin: 0 5px 0 0;
      }

      .notificacao {
        display:block;
        text-align: left;
        background-color: #fcf8e3;
        border: 1px solid #fcc888;
        padding: 5px;
        width: calc(100% - 10px);
        margin-bottom: 5px;
      }

      .notificacao  p {
        margin: 2px;
      }

    </style>
  </head>
  <body>
    <form action="" method="post" class="container">
      <fieldset>
        <legend>Implantação de Contratos em Execução</legend>
        <table class="form-container">
          <tr>
            <td>
              <label id="lblAcordo" for="ac16_sequencial">Acordo:</label>
            </td>
            <td colspan="3">
              <?php
                db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 1);
                db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label id="lblCategoria" for="ac50_sequencial">Categoria:</label>
            </td>
            <td colspan="3">
              <?php
                db_input('ac50_sequencial', 10, $Iac50_descricao, true, 'text', 1);
                db_input('ac50_descricao', 40, $Iac50_descricao, true, 'text', 3);
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="datainicial">Data de Início:</label>
            </td>
            <td>
              <?php
                db_inputdata('datainicial', "", "", "", true, 'text', 1);
              ?>
            </td>
            <td width="162">
              <label for="datafim">Data de Término:</label>
            </td>
            <td>
              <?php
                db_inputdata('datafim', "", "", "", true, 'text', 1);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input type="button" name="pesquisar" onclick="return buscarProcessos()" value="Pesquisar">
    </form>

    <fieldset class="container grid">

      <legend>Contratos</legend>

      <div class="notificacao">
          <p>A coluna "Valor Liquidado" refere-se a soma de todos os valores liquidados que possuem vinculação com o acordo em questão.</p>
      </div>
      <div class="notificacao avisoLinha">
          <p><span></span>Contrato com Valor Programado somado ao Valor implantado está diferente do valor total do contrato.</p>
        </div>
      </div>
      <div id="gridContratos"></div>

      <input type="button" name="salvar" onclick="return salvar()" value="Salvar">
    </fieldset>

    <?php
      db_menu();
    ?>
    <script type="text/javascript">

      var oCollectionContratos;
      var oGridContratos;

      (function() {

        var oAcordo    = new DBLookUp($('lblAcordo'), $('ac16_sequencial'), $('ac16_resumoobjeto'), {sArquivo: 'func_acordo.php', sQueryString:'&descricao=true&lDepartamento=true&iTipoFiltro=4'});
        var oCategoria = new DBLookUp($('lblCategoria'), $('ac50_sequencial'), $('ac50_descricao'), {sArquivo: 'func_acordocategoria.php'});
        montaGrid();
      })();

      function montaGrid() {

        oCollectionContratos = new Collection().setId('codigo');
        oGridContratos       = DatagridCollection.create(oCollectionContratos).configure("order", false);

        oGridContratos.addColumn("numero",          {label: "Número", align: "center", width: "80px"}).transform(function(sValor, oCollection) {
          return sValor;
        });
        oGridContratos.addColumn("resumo",          {label: "Resumo", align: "left", width: "300px"});
        oGridContratos.addColumn("valor_total",     {label: "Valor Total do Acordo", align: "right", width: "120px"}).transform('number');
        oGridContratos.addColumn("valor_liquidado", {label: "Valor Liquidado", align: "right", width: "120px"}).transform('number');
        oGridContratos.addColumn("valor_programado",{label: "Valor Programado", align: "right", width: "120px"}).transform('number');
        oGridContratos.addColumn("valor_implantado",{label: "Valor Implantado", align: "left", width: "100px"}).transform(function(nValor, oCollection) {
          return "<input type='text' onkeypress='return mascaraValor(event, this)' onblur='return verificar_valor()' id='valor_implantado_"+oCollection.ID+"' value='"+nValor+"' />";
        });

        oGridContratos.setEvent('onafterrenderrows', function(collection) {
          verificar_valor();
        });

        oGridContratos.show($('gridContratos'));
      }


      function verificar_valor() {

        var lExibeMensagem   = false;

        oGridContratos.getGrid().getRows().forEach(function(oRow, iItem) {

          var oDadosLinha      = oGridContratos.getCollection().get()[iItem],
              nValorImplantado = parseFloat(oRow.aCells[5].getValue()),
              nValorProgramado = parseFloat(oDadosLinha.valor_programado),
              nValorTotal      = parseFloat(oDadosLinha.valor_total);

          if (nValorProgramado > 0) {

            if (nValorProgramado + nValorImplantado < nValorTotal) {

              oRow.addClassName('error');
              lExibeMensagem = true;
              $$('.avisoLinha')[0].style.display = 'block';
            } else {
              oRow.removeClassName('error');
            }
          }

        });

        if (!lExibeMensagem) {
          $$('.avisoLinha')[0].style.display = 'none';
        }
      }

      function buscarProcessos() {

        var iAcordo     = $F('ac16_sequencial') || null;
        var iCategoria  = $F('ac50_sequencial') || null;
        var sDataInicio = $F('datainicial')     || null;
        var sDataFim    = $F('datafim')         || null;

        /**
         * Valida o campo data inicio e fim
         */
        if ((sDataInicio && sDataFim) && (js_diferenca_datas(js_formatar(sDataInicio, 'd'), js_formatar(sDataFim, 'd'), 3))) {

          alert('A data de início deve ser maior que a data de término.');
          return false;
        }

        var oDados = {};
            oDados.exec         = 'getAcordos';
            oDados.acordo       = iAcordo;
            oDados.categoria    = iCategoria;
            oDados.data_inicial = sDataInicio;
            oDados.data_final   = sDataFim;

        var oAjaxRequest = new AjaxRequest('con4_implantacaoregimecompetencia.RPC.php', oDados, function(oRetorno, lErro) {

          if (lErro) {
            alert(oRetorno.message);
          }

          oGridContratos.clear();

          oRetorno.acordos.forEach(function(acordo) {
            oCollectionContratos.add(acordo);
          });

          oGridContratos.reload();
        });

        oAjaxRequest.setMessage('Buscando Contratos...');
        oAjaxRequest.execute();

        return false;
      }

      function salvar() {


        var oDados = {};
            oDados.exec         = 'salvar';
            oDados.acordos      = new Array();

        oGridContratos.getGrid().aRows.forEach(function(oLinha, iItem) {

          var oContrato    = {}
          oContrato.codigo = oGridContratos.getCollection().get()[iItem].codigo;
          oContrato.valor  = oLinha.aCells[5].getValue().replace(',', '.');

          oDados.acordos.push(oContrato);
        });

        var oAjaxRequest = new AjaxRequest('con4_implantacaoregimecompetencia.RPC.php', oDados, function(oRetorno, lErro) {
          alert(oRetorno.message);
        });

        oAjaxRequest.setMessage('Salvando Contratos...');
        oAjaxRequest.execute();

        return false;
      }
    </script>
  </body>
</html>
