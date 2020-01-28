<?
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
define("OPT_INCLUSAO",  "1");
define("OPT_ALTERACAO", "2");

$oGet = db_utils::postMemory($_GET);

$iOpcao = 1;
if ($oGet->opcao == OPT_INCLUSAO) {
  $iOpcao = 3;
}

?>
<html>
<head>
  <title>DBSeller</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBHint.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <style>
  .gridContainer .destaque {
    background: #ddd;
    color: rgb(173, 169, 165);
  }
  </style>
</head>
<body class="container">

  <div class="container">
    <fieldset style="width: 600px;">
      <legend class="bold">Planilha de Distribuição</legend>
      <table style="width: 100%;">
        <tr>
          <td class="bold" width="10%"><label for="codigo_planilha">Código:</label></td>
          <td>
            <?php
            $Scodigo_planilha = "Código";
            db_input('codigo_planilha', 10, 1, true, 'text', 3);
            ?>
          </td>
        </tr>
        <tr>
          <td class="bold"><label for="descricao_planilha">Descrição:</label></td>
          <td>
            <?php
            $Sdescricao_planilha = "Descrição";
            db_input('descricao_planilha', 70, 3, true, 'text', 1, null, null, null, null, 100);
            ?>
          </td>
        </tr>
      </table>

      <div id="ctnLancadorMaterial"></div>
      <p id="msgMateriais" style="display: none; text-align: left;">Materiais marcados em cinza estão desativados e não serão incluídos na planilha gerada.</p>
      <div id="ctnLancadorDepartamento"></div>
      <p id="msgDepartamentos" style="display: none; text-align: left;">Departamentos marcados em cinza não são atendidos por nenhum almoxarifado e não serão incluídos na planilha gerada.</p>

    </fieldset>

    <input type="button" id="btnSalvar" value="Salvar" onclick="salvar()"/>
    <input type="button" id="btnNovaPlanilha" value="Nova Planilha" onclick="novaPlanilha()" />
    <input type="button" id="btnGerarPlanilha" value="Gerar Planilha" onclick="gerarPlanilha()" disabled/>
    <input type="button" id="btnPesquisar" value="Pesquisar" onclick="pesquisar()" />
  </div>

<?php db_menu() ?>
</body>
</html>

<script>

  const PATH_RPC = 'mat4_planilhadistribuicao.RPC.php';
  const OPT_INCLUSAO  = 1;
  const OPT_ALTERACAO = 2;
  var oGet = js_urlToObject();
  var oInputCodigoPlanilha    = $('codigo_planilha');
  var oInputDescricaoPlanilha = $('descricao_planilha');
  var oButtonPlanilha         = $('btnGerarPlanilha');
  var oButtonPesquisar        = $('btnPesquisar');
  var oButtonNovaPlanilha     = $('btnNovaPlanilha');

  oButtonPesquisar.style.display = 'none';
  oButtonNovaPlanilha.style.display = '';
  if (oGet.opcao == OPT_ALTERACAO) {
    oButtonPesquisar.style.display = '';
    oButtonNovaPlanilha.style.display = 'none';
    oButtonPesquisar.click();
  }

  function novaPlanilha() {

    oButtonPlanilha.disabled = true;
    oInputCodigoPlanilha.value = '';
    oInputDescricaoPlanilha.value = '';
    oLancadorDepartamento.clearAll();
    oLancadorMaterial.clearAll();
    limpaStatusLancador();
  }

  var oLancadorMaterial = new DBLancador('oLancadorMaterial');
  oLancadorMaterial.setNomeInstancia('oLancadorMaterial');
  oLancadorMaterial.setLabelAncora('Material: ');
  oLancadorMaterial.setTextoFieldset("Adicionar Materiais");
  oLancadorMaterial.setGridHeight(200);
  oLancadorMaterial.setParametrosPesquisa('func_matmater.php', ['m60_codmater','m60_descr']);
  oLancadorMaterial.show($('ctnLancadorMaterial'));

  var oLancadorDepartamento = new DBLancador('oLancadorDepartamento');
  oLancadorDepartamento.setNomeInstancia('oLancadorDepartamento');
  oLancadorDepartamento.setLabelAncora('Departamento: ');
  oLancadorDepartamento.setTextoFieldset("Adicionar Departamentos");
  oLancadorDepartamento.setGridHeight(200);
  oLancadorDepartamento.setParametrosPesquisa('func_db_depart.php', ['0','1'], 'lDepartamentosAtendidos=1');
  oLancadorDepartamento.show($('ctnLancadorDepartamento'));

  function limpaStatusLancador() {

    $('txtCodigooLancadorDepartamento').value = '';
    $('txtCodigooLancadorMaterial').value = '';
    $('txtDescricaooLancadorDepartamento').value = '';
    $('txtDescricaooLancadorMaterial').value = '';
  }

  function salvar() {

    if (oInputDescricaoPlanilha.value == "") {

      alert("O campo Descrição é de preenchimento obrigatório.");
      return false;
    }

    var aMaterialSelecionado = oLancadorMaterial.getRegistros(false);
    if (aMaterialSelecionado.length == 0) {
      alert('É necessário selecionar ao menos um Material.');
      return false;
    }

    var aDepartamentoSelecionado = oLancadorDepartamento.getRegistros(false);
    if (aDepartamentoSelecionado.length == 0) {
      alert('É necessário selecionar ao menos um Departamento.');
      return false;
    }

    var oParametro = {
      exec          : 'salvar',
      codigo        : oInputCodigoPlanilha.value,
      descricao     : encodeURIComponent(tagString(oInputDescricaoPlanilha.value)),
      materiais     : aMaterialSelecionado,
      departamentos : aDepartamentoSelecionado
    };

    new AjaxRequest(
      PATH_RPC,
      oParametro,
      function (oRetorno, lErro) {

        alert(oRetorno.mensagem.urlDecode());
        oButtonPlanilha.disabled = lErro;
        oInputCodigoPlanilha.value = oRetorno.codigo;
        limpaStatusLancador();
      }
    ).setMessage('Aguarde, salvando informações...').execute();
  }

  function gerarPlanilha() {

    var oParametro = {
      exec   : 'gerarPlanilha',
      codigo : oInputCodigoPlanilha.value
    };

    new AjaxRequest(
      PATH_RPC,
      oParametro,
      function (oRetorno, lErro) {

        if (lErro) {

          alert(oRetorno.mensagem.urlDecode());
          return false;
        }

        var oDownload = new DBDownload();
        oDownload.addFile(oRetorno.nome_arquivo.urlDecode(), 'Planilha de Distribuição');
        oDownload.show();

      }
    ).setMessage('Aguarde, gerando planilha...').execute();
  }


  function getPlanilha() {

    $('msgMateriais').hide();
    $('msgDepartamentos').hide();
    limpaStatusLancador();
    oLancadorMaterial.clearAll(true);
    oLancadorDepartamento.clearAll(true);
    new AjaxRequest(
      PATH_RPC,
      {exec: 'getPlanilha', codigo: oInputCodigoPlanilha.value},
      function (oRetorno, lErro) {

        oButtonPlanilha.disabled = lErro;
        if (lErro) {

          alert(oRetorno.mensagem.urlDecode());
          return false;
        }

        oInputCodigoPlanilha.value    = oRetorno.codigo;
        oInputDescricaoPlanilha.value = oRetorno.descricao.urlDecode();

        oRetorno.materiais.each(
          function(oMaterial, iIndice) {

            var sClasse = (!oMaterial.ativo ? 'destaque' : null);
            if (!oMaterial.ativo) {
              $('msgMateriais').show();
            }
            oLancadorMaterial.adicionarRegistro(oMaterial.codigo, oMaterial.descricao.urlDecode(), sClasse);
          }
        );

        oRetorno.departamentos.each(
          function(oDepartamento) {

            var sClasse = (!oDepartamento.atendido ? 'destaque' : null);
            if (!oDepartamento.atendido) {
              $('msgDepartamentos').show();
            }
            oLancadorDepartamento.adicionarRegistro(oDepartamento.codigo, oDepartamento.descricao.urlDecode(), sClasse);
          }
        );
      }
    ).setMessage('Aguarde, buscando informações...').execute();
  }

  function pesquisar() {

    var sQuery = "funcao_js=parent.completaPlanilha|pd01_sequencial|pd01_descricao";
    js_OpenJanelaIframe('','db_iframe_planilhadistribuicao','func_planilhadistribuicao.php?'+sQuery, 'Pesquisa Planilha de Distribuição', true);
  }

  function completaPlanilha(iCodigo) {
    oInputCodigoPlanilha.value = iCodigo;
    db_iframe_planilhadistribuicao.hide();
    getPlanilha();
  }
</script>
