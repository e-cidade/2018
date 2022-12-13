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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("libs/db_app.utils.php");
require_once modification("classes/db_tipoasse_classe.php");

$iInstituicao = db_getsession("DB_instit");
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
      <legend>Reabertura do Período de Efetividade</legend>
      <strong>Exercício:</strong>
      <?php
      $sSqlExercicios  = "select distinct rh186_exercicio::varchar as anousu";
      $sSqlExercicios .= "           from configuracoesdatasefetividade";
      $sSqlExercicios .= "          where rh186_instituicao = {$iInstituicao}";
      $sSqlExercicios .= "          order by anousu";
      $rsExercicios    = db_query($sSqlExercicios);

      $aExercicios[db_getsession('DB_anousu')] = db_getsession('DB_anousu');
      for($i = 0; $i < pg_num_rows($rsExercicios); $i++) {
        $iExercicio               = db_utils::fieldsmemory($rsExercicios, $i)->anousu;
        $aExercicios[$iExercicio] = $iExercicio;
      }

      $iExercicio = db_getsession('DB_anousu');
      db_select('iExercicio', $aExercicios, true, 1);

      ?>
      <div id="grid_registros" style="margin-top: 10px; width:650px"></div>
      <div id="data" style="display: none;">
        <?php
        db_inputdata('data_modelo', null, null, null, true, 'text', 1);
        ?>
      </div>
    </fieldset>

    <input type="button" name="btnSalvar" id="btnSalvar" value="Salvar" onclick="salvar()" />

  </div>

  <?php
  db_menu();
  ?>

</form>
</body>
<script type="text/javascript">

  var sUrl = "rec4_encerramentoefetividade.RPC.php";

  var oGridRegistros              = new DBGrid("dataGridRegistros");
  oGridRegistros.nameInstance = "oGridRegistros";
  oGridRegistros.setSelectAll(false);
  oGridRegistros.setCheckbox(0);
  oGridRegistros.setHeader(["Competência","Data Início", "Data Fechamento", "Data Entrega"]);
  oGridRegistros.setCellWidth(["30%","35%", "35%"]);
  oGridRegistros.setCellAlign(["center","center", "center", "center"]);
  oGridRegistros.aHeaders[4].lDisplayed = false;
  oGridRegistros.show( $('grid_registros') );

  function js_carregarRegistros() {

    var iExercicio   = $F('iExercicio');
    var oParametros  = { 'exec' : 'carregarConfiguracoes', 'iExercicio' : $F('iExercicio')};
    var oAjax = new AjaxRequest( sUrl, oParametros, function (oRetorno, lErro) {

      if ( lErro ) {
        alert(oRetorno.message);
      }

      oGridRegistros.clearAll(true);
      oRetorno.aConfiguracoes.each( function (oCompetencia) {

        var aLinha = [];
        aLinha.push(oCompetencia.sCompetencia);
        aLinha.push(oCompetencia.dDataInicioEfetividade);
        aLinha.push(oCompetencia.dDataFechamentoEfetividade);
        aLinha.push(oCompetencia.dDataEntregaEfetividade);

        var lLiberaLinha = !oCompetencia.lProcessado;
        oGridRegistros.addRow( aLinha, false, lLiberaLinha );
      });

      oGridRegistros.renderRows();
    });

    oAjax.setMessage('Carregando competências do exercício...');
    oAjax.execute();
  }

  js_carregarRegistros();

  function salvar() {

    var aLinhasSelecionadas = oGridRegistros.getSelection();

    if (aLinhasSelecionadas.length == 0 ) {

      alert('Nenhuma competência selecionada para o exercício informado.');
      return;
    }

    var aCompetenciasReabrir = [];
    aLinhasSelecionadas.each(function (aCells) {
      aCompetenciasReabrir.push(aCells[0]);
    });

    var iExercicio   = $F('iExercicio');
    var oParametros  = { 'exec' : 'reabrirCompetencia', 'iExercicio' : $F('iExercicio')};

    oParametros.aCompetencias = aCompetenciasReabrir;
    var oAjax = new AjaxRequest( sUrl, oParametros, function (oRetorno, lErro) {

      alert(oRetorno.message.replace(/\\n/g, '\n'));
      if (lErro) {
        return;
      }
      $$('input:checkbox:checked').forEach(function (element) {

        element.parentElement.parentElement.removeClassName('marcado');
        element.disabled = true;
        element.checked  = false;

      });
    });
    oAjax.setMessage('Reabrindo competências.');
    oAjax.execute();
  }
</script>
</html>