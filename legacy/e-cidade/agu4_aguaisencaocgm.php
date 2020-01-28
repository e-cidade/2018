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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");

$oParam = db_utils::postMemory($_GET);

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form id="form_isencao">
      <fieldset>
        <legend>Isenção</legend>
        <table>
          <tr>
            <td>
              <label for="x56_sequencial" class="bold">
                <a id="ancora_isencao">Código:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x56_sequencial" id="x56_sequencial">
              <input type="hidden" name="isencao_descricao" id="isencao_descricao">
            </td>
          </tr>
          <tr>
            <td>
              <label for="x56_cgm" class="bold">
                <a id="ancora_cgm">Nome/Razão Social:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x56_cgm" id="x56_cgm" data="z01_numcgm">
              <input type="text" name="cgm_descricao" id="cgm_descricao" data="z01_nome">
            </td>
          </tr>
          <tr>
            <td>
              <label for="x56_aguaisencaotipo" class="bold">
                <a id="ancora_tipo">Tipo de Isenção:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x56_aguaisencaotipo" id="x56_aguaisencaotipo" data="x29_codisencaotipo">
              <input type="text" name="tipo_descricao" id="tipo_descricao" data="x29_descr">
            </td>
          </tr>
          <tr>
            <td>
              <label for="x56_datainicial" class="bold">
                Data Inicial:
              </label>
            </td>
            <td>
              <?php db_inputdata('x56_datainicial', null, null, null, true, 'text', $oParam->iOpcao); ?>
            </td>
          </tr>
          <tr>
            <td>
              <label for="x56_datafinal" class="bold">
                Data Final:
              </label>
            </td>
            <td>
              <?php db_inputdata('x56_datafinal', null, null, null, true, 'text', $oParam->iOpcao); ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="x56_processo" class="bold">Número do Processo:</label>
            </td>
            <td>
              <input type="text" name="x56_processo" id="x56_processo" placeholder="Número/Ano" maxlength="30">
            </td>
          </tr>

          <tr>
            <td>
              <label for="x56_observacoes" class="bold">Observações:</label>
            </td>
            <td>
              <textarea name="x56_observacoes" cols="55" id="x56_observacoes"></textarea>
            </td>
          </tr>

        </table>
      </fieldset>
    </form>

    <input type="button" value="Salvar" id="salvar">
    <input type="button" value="Excluir" id="excluir">
    <input type="button" value="Pesquisar" id="pesquisar">
  </div>

  <?php db_menu() ?>

  <script type="text/javascript">

    const ARQUIVO_RPC = 'agu4_aguaisencaocgm.RPC.php';
    const OPCAO_INCLUIR = 1;
    const OPCAO_ALTERAR = 2;
    const OPCAO_EXCLUIR = 3;

    var oGet           = js_urlToObject();
    var oCodigo        = $('x56_sequencial');
    var oDescricao     = $('isencao_descricao');
    var oCgm           = $('x56_cgm');
    var oCgmDescricao  = $('cgm_descricao');
    var oDataInicial   = $('x56_datainicial');
    var oDataFinal     = $('x56_datafinal');
    var oProcesso      = $('x56_processo');
    var oObservacao    = $('x56_observacoes');
    var oTipo          = $('x56_aguaisencaotipo');
    var oTipoDescricao = $('tipo_descricao');

    var oIsencaoAncora = $('ancora_isencao');
    var oCgmAncora     = $('ancora_cgm');
    var oTipoAncora    = $('ancora_tipo');

    var oBtnSalvar    = $('salvar');
    var oBtnExcluir   = $('excluir');
    var oBtnPesquisar = $('pesquisar');

    oBtnPesquisar.hide();
    oBtnExcluir.hide();

    oProcesso.addEventListener('input', function () {
      this.value = this.value.replace(/[^0-9\/]/g, '').replace(/(\/?)([0-9]*)(\/?)([0-9]{0,4})(.*)(\/?)/, '$2$3$4')
    });

    oObservacao.setStyle({
      'resize': 'vertical'
    });

    var oIsencaoLookup = new DBLookUp(oIsencaoAncora, oCodigo, oDescricao, {
      'sArquivo': 'func_aguaisencaocgm.php',
      'sObjetoLookUp': 'db_iframe_aguaisencaocgm',
      'fCallBack': function () {
        carregarIsencao(oCodigo.value);
      }
    });
    oIsencaoLookup.desabilitar();

    var oCgmLookup = new DBLookUp(oCgmAncora, oCgm, oCgmDescricao, {
      'sArquivo': 'func_nome.php',
      'sObjetoLookUp': 'db_iframe_nome'
    });

    var oTipoIsencaoLookup = new DBLookUp(oTipoAncora, oTipo, oTipoDescricao, {
      'sArquivo': 'func_aguaisencaotipo.php',
      'sObjetoLookUp': 'db_iframe_aguaisencaotipo'
    });

    /**
     * Limpar Campos do Formulário
     */
    function limparIsencao() {
      $('form_isencao').reset();
    }

    /**
     * Carregar Isenção
     */
    function carregarIsencao(iCodigo) {

      var oParametros = {
        'exec': 'carregar',
        'iCodigo': iCodigo
      };

      new AjaxRequest(ARQUIVO_RPC, oParametros, function (oRetorno, lErro) {

        if (lErro) {

          alert(oRetorno.message);
          return false;
        }

        limparIsencao();
        oCgm.value           = oRetorno.isencao.iCgm;
        oTipo.value          = oRetorno.isencao.iTipo;
        oCodigo.value        = oRetorno.isencao.iCodigo;
        oProcesso.value      = oRetorno.isencao.sProcesso;
        oDataFinal.value     = oRetorno.isencao.sDataFinal;
        oObservacao.value    = oRetorno.isencao.sObservacao;
        oDataInicial.value   = oRetorno.isencao.sDataInicial;
        oCgmDescricao.value  = oRetorno.isencao.sCgmDescricao;
        oTipoDescricao.value = oRetorno.isencao.sTipoDescricao;

      }).execute();
    }

    /**
     * Salvar Isenção
     */
    oBtnSalvar.on('click', function () {

      var oParametros = {
        'exec': 'salvar',
        'iCodigo': oCodigo.value,
        'iCgm': oCgm.value,
        'iTipo': oTipo.value,
        'sDataInicial': oDataInicial.value,
        'sDataFinal': oDataFinal.value,
        'sObservacao': oObservacao.value,
        'sProcesso': oProcesso.value
      };

      new AjaxRequest(ARQUIVO_RPC, oParametros, function (oRetorno, lErro) {

        alert(oRetorno.message);
        if (lErro) {
          return false;
        }

        oCodigo.value = oRetorno.isencao.iCodigo;

      }).execute();
    });

    /**
     * Excluir Isenção
     */
    oBtnExcluir.on('click', function () {

      if (!confirm('Confirma a exclusão da Isenção?')) {
        return false;
      }

      var oParametros = {
        'exec': 'excluir',
        'iCodigo': oCodigo.value
      };

      new AjaxRequest(ARQUIVO_RPC, oParametros, function (oRetorno, lErro) {

        alert(oRetorno.message);
        if (lErro) {
          return false;
        }

        limparIsencao();
        oIsencaoLookup.abrirJanela(true);
      }).execute();
    });

    oBtnPesquisar.on('click', function () {
      oIsencaoLookup.abrirJanela(true);
    });

    if (oGet.iOpcao == OPCAO_ALTERAR) {
      oBtnPesquisar.show();
    }

    if (oGet.iOpcao == OPCAO_EXCLUIR) {

      oTipoIsencaoLookup.desabilitar();
      oCgmLookup.desabilitar();
      oProcesso.addClassName('readonly');
      oProcesso.writeAttribute('readonly', 'readonly');
      oObservacao.addClassName('readonly');
      oObservacao.writeAttribute('readonly', 'readonly');
      oBtnSalvar.hide();
      oBtnExcluir.show();
      oBtnPesquisar.show();
    }

    if (oGet.iOpcao == OPCAO_ALTERAR || oGet.iOpcao == OPCAO_EXCLUIR) {
      oIsencaoLookup.abrirJanela(true);
    }
  </script>
</body>
</html>