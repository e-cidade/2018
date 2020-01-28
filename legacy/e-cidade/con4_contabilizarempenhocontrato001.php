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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$oGet = db_utils::postMemory($_GET);
$oRotuloAcordo = new rotulo('acordo');
$oRotuloAcordo->label('ac16_sequencial');
$oRotuloAcordo->label('ac16_resumoobjeto');

$sLabelLegend = "Processar Implantação de Contratos";
if ($oGet->processa == 'false') {
  $sLabelLegend = "Desprocessar Implantação de Contratos";
}

$oDataImplantacao  = new DBDate(date("Y-m-d", db_getsession('DB_datausu')));
$oInstituicao      = new Instituicao(db_getsession('DB_instit'));
$lPossuiIntegracao = ParametroIntegracaoPatrimonial::possuiIntegracaoContrato($oDataImplantacao, $oInstituicao);

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <?php
      db_app::load("estilos.css, grid.style.css");
      db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js, ");
    ?>
  </head>
  <body style="margin-top: 30px; background-color: #cccccc;">
    <center>

      <?php

      if (! $lPossuiIntegracao) {
        echo "<h1>Rotina bloqueada pois não está habilitada a integração do Financeiro com Contratos.</h1>";
      } else {

      ?>
      <fieldset style="width: 530px">
        <legend><b><?php echo $sLabelLegend;?></b></legend>
        <table style="width: 100%">
          <tr>
            <td nowrap="nowrap">
              <?php
                db_ancora("<b>Código do Acordo:</b>", "js_pesquisaAcordo(true);", 1);
              ?>
            </td>
            <td>
              <?php
                db_input('ac16_sequencial', 10, $Iac16_sequencial, true, 'text', 1, "onchange='js_pesquisaAcordo(false);'");
                db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <fieldset style="width: 700px; margin-top:10px;">
        <legend><b>Empenhos Vinculados</b></legend>
        <table id="tableDesvinculaEmpenho" style="width:100%; display:none;">
          <tr>
            <td nowrap="nowrap" width="150"><b>Desvincular Empenho:</b></td>
            <td>
              <select id="lDesvincularEmpenho">
                <option value="f">Não</option>
                <option value="t">Sim</option>
              </select>
            </td>
          </tr>
        </table>
        <div id="ctnGridEmpenhosVinculados">
        </div>
      </fieldset>
      <p align="center">
        <input type="button" id="btnProcessarImplantacao" value="Processar" />
      </p>
      <?php } ?>

    </center>
  </body>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</html>
<script>

  var lProcessar = <?php echo $oGet->processa;?>;
  if (!lProcessar) {
    $('tableDesvinculaEmpenho').style.display = "none";
  }

  /**
   * Dados da Grid
   */
  var aHeaders = new Array('Seq. Empenho', 'Código', 'Emissão', 'Valor');
  var aWidth   = new Array('25%', '25%', '25%', '25%');
  var aAlign   = new Array('center', 'center', 'center', 'right');
  var oGridEmpenhos          = new DBGrid('ctnGridEmpenhosVinculados');
  oGridEmpenhos.nameInstance = 'oGridEmpenhos';
  oGridEmpenhos.setCheckbox(0);
  oGridEmpenhos.setHeight(200);
  oGridEmpenhos.setHeader(aHeaders);
  oGridEmpenhos.setCellWidth(aWidth);
  oGridEmpenhos.setCellAlign(aAlign);
  oGridEmpenhos.show($("ctnGridEmpenhosVinculados"));
  oGridEmpenhos.setStatus("Dois cliques para consultar o empenho.");

  $('btnProcessarImplantacao').observe('click', function() {

    if ($F('ac16_sequencial') == "") {

      alert("Selecione um acordo para iniciar o processamento.");
      return false;
    }

    if (!confirm("Confirma o processamento dos lançamentos contábeis para o contrato selecionado?")) {
      return false;
    }

    js_divCarregando("Aguarde, este procedimento poderá demandar algum tempo...", "msgBox");
    var oParam           = {};
    oParam.exec          = "processarLancamentosContabeisContratos";
    oParam.iCodigoAcordo = $F('ac16_sequencial');

    if (!lProcessar) {

      oParam.exec        = "desprocessarLancamentosContabeisContratos";
      oParam.lDesvinculaEmpenho = 'f';
      var aEmpenhos = oGridEmpenhos.getSelection("object");
      var aCodigosEmpenho = [];
      var sVirgula = "";
      aEmpenhos.each(function(oDado, iIndice) {
        aCodigosEmpenho.push(oDado.aCells[0].getValue());
      });

      oParam.sEmpenhos = aCodigosEmpenho;
    }

    new Ajax.Request("con4_processarLancamentoContratos004.RPC.php",
                     {method: 'post',
                      parameters: 'json='+Object.toJSON(oParam),
                      onComplete: js_concluirProcessamento
                     });

  });

  /**
   * Função que conclui o processamento da rotina
   */
  function js_concluirProcessamento(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");

    alert(oRetorno.message.urlDecode());
    if (oRetorno.status == 1) {
      js_limpaDadosFormulario();
    }
  }

  /**
   * Pesquisa os empenhos vinculados ao acordo selecionado pelo usuário
   */
  function js_pesquisaEmpenhosVinculados() {

    if ($F('ac16_sequencial') == "") {
      return false;
    }

    js_divCarregando("Aguarde, carregando empenhos vinculados ao acordo...", "msgBox");

    var oParam     = new Object();
    oParam.exec    = "getEmpenhosVinculadosAcordo";
    oParam.iAcordo = $F('ac16_sequencial');
    oParam.lImplantacaoContatos = true;

    new Ajax.Request("ac4_acordoinclusao.rpc.php",
                     {method: 'post',
                      parameters: 'json='+Object.toJSON(oParam),
                      onComplete: js_preencheGridEmpenhos
                     });

  }

  /**
   * Preenche a grid com os empenhos vinculados ao contrato
   */
  function js_preencheGridEmpenhos(oAjax) {

    js_removeObj("msgBox");
    var oRetorno = eval("("+oAjax.responseText+")");
    var aEmpenhosVinculados = oRetorno.aDadosRetorno;

    if (aEmpenhosVinculados.length == 0) {

      alert("O acordo "+$F('ac16_sequencial')+" não possui nenhum empenho vinculado.");
      js_limpaDadosFormulario();
      return false;
    }

    oGridEmpenhos.clearAll(true);
    aEmpenhosVinculados.each(function (oEmpenho, iIndice) {

      var aRow = new Array();
      aRow[0] = oEmpenho.e60_numemp;
      aRow[1] = oEmpenho.e60_codemp+"/"+oEmpenho.e60_anousu;
      aRow[2] = oEmpenho.e60_emiss;
      aRow[3] = js_formatar(oEmpenho.e60_vlremp, "f");
      oGridEmpenhos.addRow(aRow, false, lProcessar, lProcessar);
      oGridEmpenhos.aRows[iIndice].sEvents = "onDblClick='js_consultaEmpenho("+oEmpenho.e60_numemp+");'";


    });
    oGridEmpenhos.renderRows();
  }

  /**
   * Função de consulta de empenho
   */
  function js_consultaEmpenho(iSequencialEmpenho) {

    var sUrlConsulta = "func_empempenho001.php?e60_numemp="+iSequencialEmpenho;
    js_OpenJanelaIframe("", 'db_iframe_empempenho001', sUrlConsulta, "Empenho "+iSequencialEmpenho, true);
  }


  function js_pesquisaAcordo(lMostra) {

    if ($F('ac16_sequencial') == "") {
      js_limpaDadosFormulario();
    }
    var sUrlOpenAcordo = "func_acordo.php?sListaOrigens=6&funcao_js=parent.js_preencheAcordo|ac16_sequencial|ac16_resumoobjeto";
    if (!lMostra) {
      sUrlOpenAcordo = "func_acordo.php?sListaOrigens=6&descricao=true&pesquisa_chave="+$F('ac16_sequencial')+"&funcao_js=parent.js_completaAcordo";
    }
    js_OpenJanelaIframe('', 'db_iframe_acordo', sUrlOpenAcordo, 'Pesquisa Acordos', lMostra);
  }

  function js_preencheAcordo(iCodigoAcordo, sResumoAcordo) {

    $('ac16_sequencial').value   = iCodigoAcordo;
    $('ac16_resumoobjeto').value = sResumoAcordo;
    db_iframe_acordo.hide();
    js_pesquisaEmpenhosVinculados();
  }

  function js_completaAcordo(iCodigoAcordo, sResumoObjeto, lErro) {

    $('ac16_resumoobjeto').value = sResumoObjeto;
    if (lErro) {

      $('ac16_resumoobjeto').value = "Nenhum acordo encontrado ["+$F('ac16_sequencial')+"].";
      $('ac16_sequencial').value   = '';
    }
    js_pesquisaEmpenhosVinculados();
  }

  /**
   * Limpa os dados do formulário, incluindo a grid.
   */
  function js_limpaDadosFormulario() {

    $('ac16_sequencial').value   = "";
    $('ac16_resumoobjeto').value = "";
    oGridEmpenhos.clearAll(true);
  }
</script>