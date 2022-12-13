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

$sSqlExercicios  = "select distinct rh186_exercicio::varchar as anousu";
$sSqlExercicios .= "           from configuracoesdatasefetividade";
$sSqlExercicios .= "          where rh186_instituicao = {$iInstituicao}";
$sSqlExercicios .= "          order by anousu";
$rsExercicios    = db_query($sSqlExercicios);

if(!$rsExercicios) {
  throw new DBException("Ocorreu um erro ao consultar os execícios configurados para efetividade.\nContate o suporte.");
}

$aExercicios[db_getsession('DB_anousu')] = db_getsession('DB_anousu');

for($i = 0; $i < pg_num_rows($rsExercicios); $i++) {

  $iExercicio               = db_utils::fieldsmemory($rsExercicios, $i)->anousu;
  $aExercicios[$iExercicio] = $iExercicio;
}
?>
<html>
<head>
  <?php
  db_app::load('scripts.js, strings.js, prototype.js, estilos.css, datagrid.widget.js, AjaxRequest.js, ProgressBar.widget.js');
  ?>
</head>
<body>
<form name="form1" id="form1">
  <div id="container" class="container">
    <fieldset>
      <legend>Encerramento do Período de Efetividade</legend>
      <table>
        <tr>
          <td>
            <strong>Exercício:</strong>
          </td>
          <td>
            <?php
            $iExercicio = db_getsession('DB_anousu');
            db_select('iExercicio', $aExercicios, true, 1, 'onchange="js_carregarRegistros()"');
            ?>
          </td>
        </tr>
        <tr>
          <td><strong>Gerar Assentamentos:</strong></td>
          <td>
            <?php
              $aGerarAssentamentos = array(0=>'Não', 1=>'Sim');
              db_select('iGerarAssentamentos', $aGerarAssentamentos, true, 1);
            ?>
          </td>
        </tr>
      </table>
      <div id="grid_registros" style="margin-top: 10px; width:650px"></div>
      <div id="data" style="display: none;">
        <?php
        db_inputdata('data_modelo', null, null, null, true, 'text', 1);
        ?>
      </div>
    </fieldset>

    <input type="button" name="salvar" id="salvar" value="Salvar" onclick="js_salvar()" />

  </div>
</form>
<?php db_menu(); ?>
</body>
</html>

<script>

  var sUrl = "rec4_encerramentoefetividade.RPC.php";
  $('iGerarAssentamentos').value=1;

  js_montaGrid();

  function js_montaGrid() {

    oGridRegistros              = new DBGrid("dataGridRegistros");
    oGridRegistros.sName        = "dataGridRegistros";
    oGridRegistros.nameInstance = "oGridRegistros";
    oGridRegistros.setSelectAll(false);
    oGridRegistros.setCheckbox(0);
    oGridRegistros.setHeader(["Competência","Data Início", "Data Fechamento", "Data Entrega"]);
    oGridRegistros.setCellWidth(["100px","150px", "150px", "150px"]);
    oGridRegistros.setCellAlign(["center","center", "center", "center"]);
    oGridRegistros.show( $('grid_registros') );
    oGridRegistros.showColumn(false, 4);
    js_carregarRegistros();
  }

  function js_carregarRegistros() {

    var iExercicio   = $F('iExercicio');
    var oParametros  = { 'exec' : 'carregarConfiguracoes', 'iExercicio' : $F('iExercicio')};
    var oAjaxRequest = new AjaxRequest( sUrl, oParametros,

      function (oAjax, lResposta) {

        oGridRegistros.clearAll(true);

        for (var iCompetencia = 1; iCompetencia <= 12; iCompetencia++) {

          oDatasCompetencia = oAjax.aConfiguracoes[iCompetencia - 1];

          lProcessado = false;

          if (oDatasCompetencia.lProcessado) {
            lProcessado = true;
          }

          oGridRegistros.addRow( [iCompetencia, oDatasCompetencia.dDataInicioEfetividade, oDatasCompetencia.dDataFechamentoEfetividade, oDatasCompetencia.dDataEntregaEfetividade], false, lProcessado, lProcessado );
        }

        oGridRegistros.renderRows();
      }
    );

    oAjaxRequest.setMessage('Carregando configurações...');
    oAjaxRequest.execute();
  }

  function js_salvar() {

    var aSelecionados = new Array;
    var oParametros   = new Object();

    $$('input:checkbox:checked:enabled').forEach(function (element) {
      aSelecionados.push(element.value);
    });

    if (aSelecionados.length == 0) {

      alert ('Selecione ao menos uma Competência.');
      return;
    }

    if(!!parseInt($F('iGerarAssentamentos'))) {
      if (!confirm("Assentamentos de Efetividade e Eventos financeiros serão lançados, deseja continuar?")) {
        return false;
      }
    }
        
    var queryString  = 'rec4_encerramentoefetividade002.php?';
        queryString += 'iExercicio=' +$F('iExercicio');;
        queryString += '&aSelecionados=' +aSelecionados.join('-');
        queryString += '&gerarAssentamentos=' +parseInt($F('iGerarAssentamentos'));

    var oJanela        = js_OpenJanelaIframe(
      '',
      'db_encerramento_efetividade',
      queryString,
      'Encerrando Efetividade',
      true
    );
  }

  function js_mostrarMensagem(erro) {
    
    db_encerramento_efetividade.hide();

    $$('input:checkbox:checked').forEach(function (element) {
      element.disabled = true;
    });
    
    alert(erro.urlDecode());
  }
</script>
