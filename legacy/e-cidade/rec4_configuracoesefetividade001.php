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
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/db_tipoasse_classe.php"));

$iInstituicao = db_getsession("DB_instit");
$iExercicio   = db_getsession('DB_anousu');
?>
<html>
<head>
  <?php
  db_app::load('scripts.js, strings.js, prototype.js, estilos.css, datagrid.widget.js, AjaxRequest.js');
  ?>
</head>
<body>
<form name="form1" id="form1">
  <div id="container" class="container">
    <fieldset>
      <legend>Configurações</legend>

      <label for="iExercicio" class="bold">Exercício:</label>
      <input id="iExercicio" type="text" value="<?=$iExercicio;?>" class="field-size2" maxlength="4" />
      <div id="grid_registros" style="margin-top: 10px; width:650px"></div>
      <div id="data" style="display: none;">
        <?php
        db_inputdata('data_modelo', null, null, null, true, 'text', 1);
        ?>
      </div>
    </fieldset>

    <input type="button" name="salvar" id="salvar" value="Salvar" onclick="js_salvar()" />

  </div>

  <?php
  db_menu();
  ?>

</form>
</body>
</html>

<script>

  var sUrl = "rec4_configuracoesefetividade.RPC.php";

  js_montaGrid();

  function js_montaGrid() {

    oGridRegistros              = new DBGrid("dataGridRegistros");
    oGridRegistros.sName        = "dataGridRegistros";
    oGridRegistros.nameInstance = "oGridRegistros";
    oGridRegistros.setHeader(["Competência","Data Início", "Data Fechamento", "Data Entrega"]);
    oGridRegistros.setCellWidth(["100px","150px", "150px", "150px"]);
    oGridRegistros.setCellAlign(["center","center", "center", "center"]);
    oGridRegistros.show( $('grid_registros') );
    oGridRegistros.showColumn(false, 4);
    js_carregarRegistros();
  }

  function js_carregarRegistros() {

    oGridRegistros.clearAll(true);

    if(empty($F('iExercicio'))) {
      return false;
    }

    var iExercicio   = $F('iExercicio');
    var oParametros  = { 'exec' : 'carregarConfiguracoes', 'iExercicio' : $F('iExercicio')};
    var oAjaxRequest = new AjaxRequest( sUrl, oParametros,

      function (oAjax, lResposta) {

        for (var iCompetencia = 1; iCompetencia <= 12; iCompetencia++) {

          oDatasCompetencia          = oAjax.aConfiguracoes[iCompetencia - 1];

          oDataModelo                = document.getElementById('data_modelo');
          oDataInicioEfetividade     = oDataModelo.cloneNode(true);
          oDataFechamentoEfetividade = oDataModelo.cloneNode(true);
          oDataEntregaEfetividade    = oDataModelo.cloneNode(true);

          if (!oDatasCompetencia) {

            oDatasCompetencia = new Object();
            oDatasCompetencia.dDataInicioEfetividade     = '';
            oDatasCompetencia.dDataFechamentoEfetividade = '';
            oDatasCompetencia.dDataEntregaEfetividade    = '';
          }

          oDataInicioEfetividade.setAttribute('id', 'data_inicio_efetividade_' + iCompetencia);
          oDataInicioEfetividade.setAttribute('value', oDatasCompetencia.dDataInicioEfetividade);

          oDataFechamentoEfetividade.setAttribute('id', 'data_fechamento_efetividade_' + iCompetencia);
          oDataFechamentoEfetividade.setAttribute('value', oDatasCompetencia.dDataFechamentoEfetividade);

          oDataEntregaEfetividade.setAttribute('id', 'data_entrega_efetividade_' + iCompetencia);
          oDataEntregaEfetividade.setAttribute('value', oDatasCompetencia.dDataEntregaEfetividade);

          if ( oDatasCompetencia.lProcessado ) {

            oDataInicioEfetividade.setAttribute('disabled', 'disabled');
            oDataFechamentoEfetividade.setAttribute('disabled', 'disabled');
            oDataEntregaEfetividade.setAttribute('disabled', 'disabled');
          }

          var aLinha = [];
          aLinha.push(iCompetencia);
          aLinha.push(oDataInicioEfetividade.outerHTML);
          aLinha.push(oDataFechamentoEfetividade.outerHTML);
          aLinha.push(oDataEntregaEfetividade.outerHTML);
          oGridRegistros.addRow( aLinha);
        }
        oGridRegistros.renderRows();
      }

    );

    oAjaxRequest.setMessage('Carregando configurações...');
    oAjaxRequest.execute();
  }

  function js_salvar() {

    var aSelecionados  = new Array;
    var oParametros    = new Object();
    var lTemTodasDatas = true;

    oGridRegistros.getRows().each( function (oLinha, iIndice) {

      dDataInicioEfetividade     = null;
      dDataFechamentoEfetividade = null;
      dDataEntregaEfetividade    = null;
      iCompetencia               = oLinha.aCells[0].content;

      var oDataInicio  = $('data_inicio_efetividade_' + iCompetencia);
      var oDataFim     = $('data_fechamento_efetividade_' + iCompetencia);
      var oDataEntrega = $('data_entrega_efetividade_' + iCompetencia);

      if (oDataInicio.disabled) {
        return;
      }

      if (oDataInicio.value == '' || oDataFim.value == '') {
        lTemTodasDatas = false;
      }

      if (oDataInicio.value != '') {
        dDataInicioEfetividade = oDataInicio.value;
      }

      if (oDataFim.value != '') {
        dDataFechamentoEfetividade = oDataFim.value;
      }

      if (oDataEntrega.value != '') {
        dDataEntregaEfetividade = oDataEntrega.value;
      }

      oDatas                            = new Object();
      oDatas.iCompetencia               = iCompetencia;
      oDatas.dDataInicioEfetividade     = dDataInicioEfetividade;
      oDatas.dDataFechamentoEfetividade = dDataFechamentoEfetividade;
      oDatas.dDataEntregaEfetividade    = dDataEntregaEfetividade;

      aSelecionados.push(oDatas);

    });

    if(!lTemTodasDatas) {

      alert('Informe todas as datas de Início e de Fechamento.');
      return;
    }

    oParametros.exec          = 'salvar';
    oParametros.iExercicio    = $F('iExercicio');
    oParametros.aSelecionados = aSelecionados;

    var oAjaxRequest = new AjaxRequest(sUrl, oParametros,
      function (oAjax, lErro) {
        alert(oAjax.message.urlDecode().replace(/\\n/g, '\n'));
      }
    );

    oAjaxRequest.setMessage("Salvando");
    oAjaxRequest.execute();
  }

  $('iExercicio').addEventListener('keyup', function(event) {
    js_ValidaCampos(this, 1, 'Exercício', false, true, event);
  });

  $('iExercicio').addEventListener('change', function() {
    js_carregarRegistros();
  });
</script>