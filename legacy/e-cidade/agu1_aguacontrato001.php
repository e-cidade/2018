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
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBDownload.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="estilos/DBtab.style.css">
</head>
<body class="body-default abas">

  <div id="abas"></div>

  <div id="aba_contrato" class="container">
    <form action="" method="post" id="form_contrato">
      <fieldset>
        <legend>Contrato</legend>
        <table>

          <tr>
            <td>
              <label for="x54_sequencial" class="bold">
                <a id="label_contrato">Código:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x54_sequencial" id="x54_sequencial" class="field-size2">
              <input type="hidden" id="contrato_descricao" name="contrato_descricao">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="x54_cgm">
                <a id="label_cgm">Nome/Razão Social:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x54_cgm" id="x54_cgm" class="field-size2" data="z01_numcgm">
              <input type="text" name="cgm_descricao" id="cgm_descricao" class="field-size8" data="z01_nome">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="x54_aguabase">
                <a id="label_matricula">Matrícula:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x54_aguabase" id="x54_aguabase" class="field-size2" data="x01_matric">
              <input type="text" name="matricula_descricao" id="matricula_descricao" class="field-size8" data="z01_nome">
            </td>
          </tr>

          <tr>
            <td>
              <label for="x54_datainicial" class="bold">Data Inicial:</label>
            </td>
            <td>
              <?php db_inputdata("x54_datainicial", null, null, null, true, 'text', $oParam->iOpcao, 'class="field-size2"'); ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="x54_datafinal" class="bold">Data Final:</label>
            </td>
            <td>
              <?php db_inputdata("x54_datafinal", null, null, null, true, 'text', $oParam->iOpcao, 'class="field-size2"'); ?>
            </td>
          </tr>

          <tr>
            <td>
              <label for="x54_diavencimento" class="bold">Dia de Vencimento:</label>
            </td>
            <td>
              <input type="text" name="x54_diavencimento" id="x54_diavencimento" class="field-size2" data="x01_matric">
            </td>
          </tr>

          <tr>
            <td>
              <label for="x54_aguahidromatric" class="bold">
                <a id="label_hidrometro">Hidrômetro:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x54_aguahidromatric" id="x54_aguahidromatric" class="field-size2" data="x04_codhidrometro">
              <input type="text" name="hidrometro_descricao" id="hidrometro_descricao" class="field-size8" data="x04_nrohidro">
            </td>
          </tr>

          <tr>
            <td>
              <label for="x54_aguatipocontrato" class="bold">
                <a id="label_tipocontrato">Tipo de Contrato:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x54_aguatipocontrato" id="x54_aguatipocontrato" class="field-size2" data="x39_sequencial">
              <input type="text" name="tipocontrato_descricao" id="tipocontrato_descricao" class="field-size8" data="x39_descricao">
            </td>
          </tr>

          <tr>
            <td>
              <label for="x54_condominio" class="bold">Contrato de Condomínio:</label>
            </td>
            <td>
              <select type="text" name="x54_condominio" id="x54_condominio" class="field-size2">
                <option value="0">Não</option>
                <option value="1">Sim</option>
              </select>
            </td>
          </tr>

          <tr id="linha_responsavelpagamento">
            <td>
              <label for="x54_responsavelpagamento" class="bold">Responsável pelo Pagamento:</label>
            </td>
            <td>
              <select type="text" name="x54_responsavelpagamento" id="x54_responsavelpagamento" class="field-size2">
                <option value="2">Condomínio</option>
                <option value="1">Economia</option>
              </select>
            </td>
          </tr>

          <tr id="linha_aguacategoriaconsumo">
            <td>
              <label for="x54_aguacategoriaconsumo" class="bold">
                <a id="label_categoriaconsumo">Categoria de Consumo:</a>
              </label>
            </td>
            <td>
              <input type="text" name="x54_aguacategoriaconsumo" id="x54_aguacategoriaconsumo" class="field-size2" data="x13_sequencial">
              <input type="text" name="categoria_descricao" id="categoria_descricao" class="field-size8" data="x13_descricao">
            </td>
          </tr>

          <tr id="linha_datavalidadecadastro">
            <td>
              <label for="x54_datavalidadecadastro" class="bold">Data de Validade do Cadastro:</label>
            </td>
            <td>
              <?php db_inputdata("x54_datavalidadecadastro", null, null, null, true, 'text', $oParam->iOpcao, 'class="field-size2"'); ?>
            </td>
          </tr>

          <tr id="linha_nis">
            <td><label for="x54_nis" class="bold">NIS:</label></td>
            <td>
              <input type="text" name="x54_nis" id="x54_nis" class="field-size3">
            </td>
          </tr>

        </table>
      </fieldset>
      <input type="button" value="Salvar" id="salvar">
      <input type="button" value="Emitir" id="emitir">
      <input type="button" value="Excluir" id="excluir">
      <input type="button" value="Pesquisar" id="pesquisar">
    </form>
  </div>

  <div id="aba_economias" class="container" style="width: 960px;">

    <form action="" method="post" id="form_economias">

      <fieldset>

        <legend>Dados da Economia</legend>

        <table>

          <tr>
            <td>
              <label class="bold" for="economia_sequencial">Código:</label>
            </td>

            <td>
              <input type="text" id="economia_sequencial" name="economia_sequencial" class="field-size2 readonly" readonly="readonly">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="economia_cgm">
                <a id="label_economia_cgm">Nome/Razão Social:</a>
              </label>
            </td>

            <td>
              <input type="text" id="economia_cgm" name="economia_cgm" class="field-size2" data="z01_numcgm">
              <input type="text" id="economia_cgm_descricao" name="economia_cgm_descricao" data="z01_nome" class="field-size4">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="economia_aguacategoriaconsumo">
                <a id="label_economia_categoria">Categoria de Consumo:</a>
              </label>
            </td>

            <td>
              <input type="text" id="economia_aguacategoriaconsumo" name="economia_aguacategoriaconsumo" class="field-size2" data="x13_sequencial">
              <input type="text" id="economia_aguacategoriaconsumo_descricao" name="economia_aguacategoriaconsumo_descricao" data="x13_descricao" class="field-size4">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="economia_datavalidadecadastro">Data de Validade do Cadastro:</label>
            </td>

            <td>
              <?php db_inputdata("economia_datavalidadecadastro", null, null, null, true, 'text', $oParam->iOpcao, 'class="field-size3"') ?>
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="economia_nis">NIS:</label>
            </td>

            <td>
              <input type="text" id="economia_nis" name="economia_nis" class="field-size3">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="economia_complemento">Complemento:</label>
            </td>

            <td>
              <input type="text" id="economia_complemento" name="economia_complemento" class="field-size3">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="economia_observacoes">Observações:</label>
            </td>

            <td>
              <input type="text" id="economia_observacoes" name="economia_observacoes" class="field-size3">
            </td>
          </tr>

        </table>

      </fieldset>

      <div class="sub-container">
        <input type="button" name="economia_salvar" id="economia_salvar" value="Salvar">
      </div>

      <fieldset>

        <legend>Economias Cadastradas</legend>

        <div id="grid_economias"></div>

      </fieldset>

      <div class="">

        <fieldset>
          <legend>Importar Economias</legend>
          <table>
            <td>
              <label for="categoriaconsumo_importar" class="bold">
                <a id="label_categoriaconsumo_importar">Categoria de Consumo:</a>
              </label>
            </td>
            <td>
              <input type="text" name="categoriaconsumo_importar" id="categoriaconsumo_importar" class="field-size2" data="x13_sequencial">
              <input type="text" name="categoriaconsumo_importar_descricao" id="categoriaconsumo_importar_descricao" class="field-size8" data="x13_descricao">
            </td>
          </table>
        </fieldset>

        <input type="button" name="economia_importar" id="economia_importar" value="Importar">

      </div>

    </form>

  </div>

  <?php db_menu() ?>

  <script type="text/javascript">

    var oGet    = js_urlToObject();
    var sUrlRPC = 'agu1_aguacontrato.RPC.php';

    const OPCAO_ALTERAR = 2;
    const OPCAO_EXCLUIR = 3;

    var oCodigo               = $('x54_sequencial');
    var oCgm                  = $('x54_cgm');
    var oNis                  = $('x54_nis');
    var oMatricula            = $('x54_aguabase');
    var oHidrometro           = $('x54_aguahidromatric');
    var oDataInicial          = $('x54_datainicial');
    var oDataFinal            = $('x54_datafinal');
    var oDiaVencimento        = $('x54_diavencimento');
    var oCategoriaConsumo     = $('x54_aguacategoriaconsumo');
    var oDataValidadeCadastro = $('x54_datavalidadecadastro');
    var oCgmDescricao         = $('cgm_descricao');
    var oMatriculaDescricao   = $('matricula_descricao');
    var oCategoriaDescricao   = $('categoria_descricao');
    var oHidrometroDescricao  = $('hidrometro_descricao');

    var oBtnSalvar    = $('salvar');
    var oBtnEmitir    = $('emitir');
    var oBtnPesquisar = $('pesquisar');
    var oBtnExcluir   = $('excluir');

    var oBtnEconomiaSalvar   = $('economia_salvar');
    var oBtnEconomiaImportar = $('economia_importar');

    var oAbas         = new DBAbas($('abas'));
    var oAbaContrato  = oAbas.adicionarAba('Contrato', $('aba_contrato'));
    var oAbaEconomias = oAbas.adicionarAba('Economias', $('aba_economias'));

    var oEconomiaCgm          = $('economia_cgm');
    var oEconomiaCgmDescricao = $('economia_cgm_descricao');

    var oEconomiaCategoriaConsumo          = $('economia_aguacategoriaconsumo');
    var oEconomiaCategoriaConsumoDescricao = $('economia_aguacategoriaconsumo_descricao');

    oBtnPesquisar.hide();
    oBtnEmitir.hide();
    oBtnExcluir.hide();
    oAbaEconomias.bloquear();

    var Contrato = function() {

      this.oCodigo                = new DBInputInteger($('x54_sequencial'));
      this.oCgm                   = new DBInputInteger($('x54_cgm'));
      this.oMatricula             = new DBInputInteger($('x54_aguabase'));
      this.oHidrometro            = new DBInputInteger($('x54_aguahidromatric'));
      this.oDiaVencimento         = new DBInputInteger($('x54_diavencimento'));
      this.oCategoriaConsumo      = new DBInputInteger($('x54_aguacategoriaconsumo'));
      this.oNis                   = new DBInput($('x54_nis'));
      this.oDataInicial           = new DBInput($('x54_datainicial'));
      this.oDataFinal             = new DBInput($('x54_datafinal'));
      this.oDataValidadeCadastro  = new DBInput($('x54_datavalidadecadastro'));
      this.oCgmDescricao          = new DBInput($('cgm_descricao'));
      this.oMatriculaDescricao    = new DBInput($('matricula_descricao'));
      this.oCategoriaDescricao    = new DBInput($('categoria_descricao'));
      this.oHidrometroDescricao   = new DBInput($('hidrometro_descricao'));
      this.oCondominio            = $('x54_condominio');
      this.oResponsavelPagamento  = $('x54_responsavelpagamento');
      this.oTipoContrato          = new DBInputInteger($('x54_aguatipocontrato'));
      this.oTipoContratoDescricao = new DBInputInteger($('tipocontrato_descricao'));
      this.oAbaEconomias          = null;

      var aplicarRegraOperacao = function () {

        if (oGet.iOpcao != OPCAO_EXCLUIR) {
          oBtnEmitir.show();
        }
      };

      var aplicarRegraCondominio = function () {

        if (this.oCondominio.value == '1') {

          $('linha_aguacategoriaconsumo').hide();
          $('linha_datavalidadecadastro').hide();
          $('linha_nis').hide();
          $('linha_responsavelpagamento').show();
        } else {

          $('linha_aguacategoriaconsumo').show();
          $('linha_datavalidadecadastro').show();
          $('linha_nis').show();
          $('linha_responsavelpagamento').hide();
        }
      }.bind(this);

      aplicarRegraCondominio();
      this.oCondominio.observe('change', aplicarRegraCondominio);

      this.setAbaEconomias = function(oAbaEconomias) {
        this.oAbaEconomias = oAbaEconomias;
      };

      /**
       * Limpa campos do formulário
       */
      this.limparDados = function() {

        $('form_contrato').reset();
        $('form_economias').reset();
        aplicarRegraCondominio();
      };

      /**
       * Carrega as informações do contrato
       *
       * @param {integer} iCodigo
       */
      this.carregarDados = function(iCodigo) {

        var oParametros = {
          'exec'   : 'carregarContrato',
          'iCodigo': iCodigo
        };

        new AjaxRequest(sUrlRPC, oParametros, function (oRetorno, lErro) {

          if (lErro) {

            alert(oRetorno.message);
            return false;
          }

          this.limparDados();

          var oContrato = oRetorno.contrato;
          this.oCodigo.value                = oContrato.iCodigo;
          this.oCgm.value                   = oContrato.iCgm;
          this.oNis.value                   = oContrato.sNis;
          this.oMatricula.value             = oContrato.iMatricula;
          this.oDataInicial.value           = oContrato.sDataInicial;
          this.oDataFinal.value             = oContrato.sDataFinal;
          this.oDiaVencimento.value         = oContrato.iDiaVencimento;
          this.oCategoriaConsumo.value      = oContrato.iCategoriaConsumo;
          this.oHidrometro.value            = oContrato.iHidrometro;
          this.oDataValidadeCadastro.value  = oContrato.sDataValidadeCadastro;
          this.oCgmDescricao.value          = oContrato.sCgmDescricao;
          this.oMatriculaDescricao.value    = oContrato.sMatriculaDescricao;
          this.oCategoriaDescricao.value    = oContrato.sCategoriaDescricao;
          this.oHidrometroDescricao.value   = oContrato.sHidrometroDescricao;
          this.oCondominio.value            = oContrato.lCondominio === true ? '1' : '0';
          this.oResponsavelPagamento.value  = !empty(oContrato.iResponsavelPagamento) ? oContrato.iResponsavelPagamento : 2;
          this.oTipoContrato.value          = oContrato.iTipoContrato;
          this.oTipoContratoDescricao.value = oContrato.sTipoContratoDescricao;

          aplicarRegraCondominio();
          aplicarRegraOperacao();
          this.oAbaEconomias.bloquear();
          if (this.oCondominio.value == 1) {
            this.oAbaEconomias.desbloquear();
          }
          oBtnExcluir.removeAttribute('disabled');
        }.bind(this))
          .setMessage("Carregando Informações do Contrato....")
          .execute();
      };

      this.salvar = function() {

        var oParametros = {
          'exec'                  : 'salvarContrato',
          'iCodigo'               : this.oCodigo.value,
          'iCgm'                  : this.oCgm.value,
          'sNis'                  : this.oNis.value,
          'iMatricula'            : this.oMatricula.value,
          'iHidrometro'           : this.oHidrometro.value,
          'sDataInicial'          : this.oDataInicial.value,
          'sDataFinal'            : this.oDataFinal.value,
          'sDataValidadeCadastro' : this.oDataValidadeCadastro.value,
          'iDiaVencimento'        : this.oDiaVencimento.value,
          'iCategoriaConsumo'     : this.oCategoriaConsumo.value,
          'lCondominio'           : this.oCondominio.value === '1',
          'iResponsavelPagamento' : this.oCondominio.value === '1' ? this.oResponsavelPagamento.value : '0',
          'iTipoContrato'         : this.oTipoContrato.value
        };

        new AjaxRequest(sUrlRPC, oParametros, function (oRetorno, lErro) {

          alert(oRetorno.message);
          if (lErro) {
            return false;
          }

          this.oCodigo.value = oRetorno.contrato.iCodigo;
          this.oAbaEconomias.bloquear();
          if (this.oCondominio.value == 1) {
            this.oAbaEconomias.desbloquear();
          }
          aplicarRegraOperacao();
        }.bind(this))
          .setMessage('Salvando Informações do Contrato...')
          .execute();
      };

      this.excluir = function() {

        if (!confirm('Confirma a exclusão do Contrato?')) {
          return false;
        }

        var oParametros = {
          'exec'    : 'excluirContrato',
          'iCodigo' : this.oCodigo.value
        };

        new AjaxRequest(sUrlRPC, oParametros, function (oRetorno, lErro) {

          alert(oRetorno.message);
          if (lErro) {
            return false;
          }

          this.limparDados();
          this.oAbaEconomias.bloquear();
          oBtnExcluir.setAttribute('disabled', 'disabled');
        }.bind(this)).setMessage('Excluindo Contrato...')
         .execute();
      };

      this.emitir = function () {

        var oParametros = {
          'exec' : 'emitirContrato',
          'iCodigo' : this.oCodigo.value
        };

        new AjaxRequest(sUrlRPC, oParametros, function (oRetorno, lErro) {

          alert(oRetorno.message);
          if (lErro) {
            return false;
          }

          var oDownload = new DBDownload();
          oDownload.addFile(oRetorno.sCaminhoArquivo, oRetorno.sNomeArquivo);
          oDownload.show();
        }.bind(this))
          .setMessage('Emitindo Contrato...')
          .execute();
      };
    };

    function descricaoObjeto(iCodigo, sDescricao) {
      return iCodigo + ' - ' + sDescricao;
    }

    var ContratoEconomia = function() {

      this.oContrato                  = new DBInputInteger($('x54_sequencial'));
      this.oCodigo                    = new DBInputInteger($('economia_sequencial'));
      this.oCgm                       = new DBInputInteger($('economia_cgm'));
      this.oCgmDescricao              = new DBInput($('economia_cgm_descricao'));
      this.oCategoriaConsumo          = new DBInputInteger($('economia_aguacategoriaconsumo'));
      this.oCategoriaConsumoDescricao = new DBInput($('economia_aguacategoriaconsumo_descricao'));
      this.oDataValidadeCadastro      = new DBInput($('economia_datavalidadecadastro'));
      this.oNis                       = new DBInputInteger($('economia_nis'));
      this.oComplemento               = new DBInput($('economia_complemento'));
      this.oObservacoes               = new DBInput($('economia_observacoes'));
      this.oDataGrid                  = null;

      this.setDataGrid = function(oDataGrid) {
        this.oDataGrid = oDataGrid;
      };

      /**
       * @param  {function} fCallbackSucesso Função executada em caso de sucesso na operação
       */
      this.salvar = function(fCallbackSucesso) {

        var oParametros = {
          'exec'                  : 'salvarEconomia',
          'iContrato'             : this.oContrato.value,
          'iCodigo'               : this.oCodigo.value,
          'iCgm'                  : this.oCgm.value,
          'iCategoriaConsumo'     : this.oCategoriaConsumo.value,
          'sDataValidadeCadastro' : this.oDataValidadeCadastro.value,
          'sNis'                  : this.oNis.value,
          'sComplemento'          : this.oComplemento.value,
          'sObservacoes'          : this.oObservacoes.value
        };
        new AjaxRequest(sUrlRPC, oParametros, function(oRetorno, lErro) {

          alert(oRetorno.message);
          if (lErro) {
            return false;
          }

          this.limparDados();
          fCallbackSucesso(oRetorno.oEconomia, oRetorno.lAdicionar);
        }.bind(this)).execute();

      };

      /**
       * @param  {integer}  iCodigo
       * @param  {function} fCallbackSucesso Função executada em caso de sucesso na operação
       */
      this.excluir = function(iCodigo, fCallbackSucesso) {

        var oParametros = {
          'exec'    : 'excluirEconomia',
          'iCodigo' : iCodigo
        };
        new AjaxRequest(sUrlRPC, oParametros, function(oRetorno, lErro) {

          alert(oRetorno.message);
          if (lErro) {
            return false;
          }

          fCallbackSucesso();
          this.limparDados();
        }.bind(this)).execute();
      };

      this.limparDados = function() {
        $('form_economias').reset();
      };

      /**
       * @param {integer} iCodigo
       */
      this.carregarDados = function(iCodigo) {

        var oParametros = {
          'exec'    : 'carregarEconomia',
          'iCodigo' : iCodigo
        };
        new AjaxRequest(sUrlRPC, oParametros, function(oRetorno, lErro) {

          this.oCodigo.value                    = oRetorno.oEconomia.iCodigo;
          this.oCgm.value                       = oRetorno.oEconomia.iCgmCodigo;
          this.oCgmDescricao.value              = oRetorno.oEconomia.sCgmDescricao;
          this.oCategoriaConsumo.value          = oRetorno.oEconomia.iCategoriaCodigo
          this.oCategoriaConsumoDescricao.value = oRetorno.oEconomia.sCategoriaDescricao;
          this.oDataValidadeCadastro.value      = oRetorno.oEconomia.sDataValidadeCadastro;
          this.oNis.value                       = oRetorno.oEconomia.sNis;
          this.oComplemento.value               = oRetorno.oEconomia.sComplemento;
          this.oObservacoes.value               = oRetorno.oEconomia.sObservacoes;
        }.bind(this)).execute();
      };

      this.preencherGrid = function() {

        var oParametros = {
          'exec'    : 'listarEconomias',
          'iCodigo' : this.oContrato.value
        };
        new AjaxRequest(sUrlRPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message);
          }

          var oCollection = this.oDataGrid.getCollection();
          this.oDataGrid.clear();
          for (var oEconomia of oRetorno.aEconomias) {

            var sCgm       = descricaoObjeto(oEconomia.iCgmCodigo, oEconomia.sCgmDescricao);
            var sCategoria = descricaoObjeto(oEconomia.iCategoriaCodigo, oEconomia.sCategoriaDescricao);

            oCollection.add({
              'codigo'           : oEconomia.iCodigo,
              'cgm'              : sCgm,
              'categoriaconsumo' : sCategoria,
              'complemento'      : oEconomia.sComplemento,
              'observacoes'      : oEconomia.sObservacoes
            });
          }
          this.oDataGrid.reload();

        }.bind(this)).execute();

      };

      this.inicializar = function() {

        /**
         * Botão editar
         */
        this.oDataGrid.addAction("Editar", null, function(oEvento, oRegistro) {
          this.carregarDados(oRegistro.codigo);
        }.bind(this));

        /**
         * Botão excluir
         */
        this.oDataGrid.addAction("Excluir", null, function(oEvento, oRegistro) {

          if (confirm('Confirma a exclusão da Estrutura Tarifária?')) {

            this.excluir(oRegistro.codigo, function() {

              this.oDataGrid.getCollection().remove(oRegistro.codigo);
              this.oDataGrid.reload()
            }.bind(this));
          }
        }.bind(this));

        this.oDataGrid.show($('grid_economias'));
      };

      /**
       * Importa as economias a partir das informações contidas no cadastro de condomínios
       */
      this.importarCadastroCondominios = function() {

        if (!confirm('Deseja importar as informações disponíveis no cadastro de condomínios?')) {
          return false;
        }

        var oParametros = {
          'exec'              : 'importarEconomias',
          'iCodigo'           : this.oContrato.value,
          'iCategoriaConsumo' : $('categoriaconsumo_importar').value
        };
        new AjaxRequest(sUrlRPC, oParametros, function(oRetorno, lErro) {

          alert(oRetorno.message)
          if (lErro) {
            return false;
          }

          this.preencherGrid();
        }.bind(this)).execute();
      };

    };

    var oContrato = new Contrato();
    oContrato.setAbaEconomias(oAbaEconomias);

    /**
     * Salva Informações do Contrato
     */
    oBtnSalvar.observe('click', function(){
      oContrato.salvar();
    });

    /**
     * Emite o contrato
     */
    oBtnEmitir.observe('click', function(){
      oContrato.emitir();
    });

    /**
     * Excluir Contrato
     */
    oBtnExcluir.observe('click', function() {
      oContrato.excluir();
    });

    var oLookupContrato = new DBLookUp($('label_contrato'), oCodigo, $('contrato_descricao'), {
      "sArquivo"      : "func_aguacontrato.php",
      "sObjetoLookUp" : "db_iframe_aguacontrato",
      "sLabel"        : "Pesquisar",
      "fCallBack"     : function () {

        oContrato.carregarDados(oCodigo.value);
        oContratoEconomia.preencherGrid();
      }
    });
    oLookupContrato.desabilitar();

    var oLookupCgm = new DBLookUp($('label_cgm'), oCgm, oCgmDescricao, {
      "sArquivo"      : "func_nome.php",
      "sObjetoLookUp" : "db_iframe_nome",
      "sLabel"        : "Pesquisar"
    });

    var oLookupMatricula = new DBLookUp($('label_matricula'), oMatricula, oMatriculaDescricao, {
      "sArquivo"      : "func_aguabase.php",
      "sObjetoLookUp" : "db_iframe_aguabase",
      "sLabel"        : "Pesquisar"
    });

    var oLookupCategoriaConsumo = new DBLookUp(
      $('label_categoriaconsumo'), oCategoriaConsumo, oCategoriaDescricao, {
      "sArquivo"      : "func_aguacategoriaconsumo.php",
      "sObjetoLookUp" : "db_iframe_aguacategoriaconsumo",
      "sLabel"        : "Pesquisar"
    });

    var oLookupCategoriaConsumoImportar = new DBLookUp(
      $('label_categoriaconsumo_importar'), $('categoriaconsumo_importar'), $('categoriaconsumo_importar_descricao'), {
      "sArquivo"      : "func_aguacategoriaconsumo.php",
      "sObjetoLookUp" : "db_iframe_aguacategoriaconsumo",
      "sLabel"        : "Pesquisar"
    });

    var oLookupHidrometro = new DBLookUp($('label_hidrometro'), oHidrometro, oHidrometroDescricao, {
      "sArquivo"      : "func_aguahidromatric.php",
      "sObjetoLookUp" : "db_iframe_hidrometro",
      "sLabel"        : "Pesquisar"
    });

    var oLookupTipoContrato = new DBLookUp($('label_tipocontrato'), $('x54_aguatipocontrato'), $('tipocontrato_descricao'), {
      "sArquivo"      : "func_aguatipocontrato.php",
      "sObjetoLookUp" : "db_iframe_aguatipocontrato",
      "sLabel"        : "Pesquisar"
    });

    oBtnPesquisar.on('click', function () {
      oLookupContrato.abrirJanela(true);
    });

    var oLookUpEconomiaCgm = new DBLookUp($('label_economia_cgm'), oEconomiaCgm, oEconomiaCgmDescricao, {
      "sArquivo"      : "func_nome.php",
      "sObjetoLookUp" : "db_iframe_nome",
      "sLabel"        : "Pesquisar"
    });
    var oLookUpEconomiaCategoriaConsumo = new DBLookUp(
      $('label_economia_categoria'), oEconomiaCategoriaConsumo, oEconomiaCategoriaConsumoDescricao, {
      "sArquivo"      : "func_aguacategoriaconsumo.php",
      "sObjetoLookUp" : "db_iframe_aguacategoriaconsumo",
      "sLabel"        : "Pesquisar"
    });

    var oCollectionEconomias = new Collection().setId('codigo');
    var oDataGridEconomias = new DatagridCollection(oCollectionEconomias).configure({
      'order'  : false,
      'height' : 200
    });
    oDataGridEconomias.addColumn("codigo", {
      'label' : "Código",
      'width' : "10%",
      'align' : "center"
    });
    oDataGridEconomias.addColumn("cgm", {
      'label' : "Nome/Razão Social",
      'align' : "left",
      'width' : "20%"
    });
    oDataGridEconomias.addColumn("categoriaconsumo", {
      'label' : "Categoria de Consumo",
      'align' : "left",
      'width' : "17%"
    });
    oDataGridEconomias.addColumn("complemento", {
      'label' : "Complemento",
      'align' : "left",
      'width' : "15%"
    });
    oDataGridEconomias.addColumn("observacoes", {
      'label' : "Observações",
      'align' : "left",
      'width' : "20%"
    });

    var oContratoEconomia = new ContratoEconomia();
    oContratoEconomia.setDataGrid(oDataGridEconomias);
    oContratoEconomia.inicializar();

    /**
     * Salvar economia
     */
    oBtnEconomiaSalvar.observe('click', function() {

      oContratoEconomia.salvar(function(oEconomia, lAdicionar) {

        var oCollection   = oContratoEconomia.oDataGrid.getCollection();
        var oEconomiaGrid = {};
        if (!lAdicionar) {
          var oEconomiaGrid = oCollection.get(oEconomia.iCodigo);
        }

        oEconomiaGrid.ID = undefined;
        oEconomiaGrid.codigo = oEconomia.iCodigo;
        oEconomiaGrid.categoriaconsumo = descricaoObjeto(oEconomia.iCategoriaCodigo, oEconomia.sCategoriaDescricao);
        oEconomiaGrid.cgm = descricaoObjeto(oEconomia.iCgmCodigo, oEconomia.sCgmDescricao);
        oEconomiaGrid.complemento = oEconomia.sComplemento;
        oEconomiaGrid.observacoes = oEconomia.sObservacoes;

        oCollection.add(oEconomiaGrid);
        oContratoEconomia.oDataGrid.reload();
      });
    });

    /**
     * Importar economias a partir das informações do cadastro de condomínios
     */
    oBtnEconomiaImportar.observe('click', function(){
      oContratoEconomia.importarCadastroCondominios();
    });

    if (oGet.iOpcao == OPCAO_EXCLUIR) {

      oLookupCgm.desabilitar();
      oLookupCategoriaConsumo.desabilitar();
      oLookupHidrometro.desabilitar();
      oLookupMatricula.desabilitar();
      oLookUpEconomiaCgm.desabilitar();
      oLookUpEconomiaCategoriaConsumo.desabilitar();
      oLookupTipoContrato.desabilitar();
      oLookupCategoriaConsumoImportar.desabilitar();
      $('x54_condominio').writeAttribute('disabled', 'disabled');

      var aElementosEconomias = $('form_economias').getElements();
      var aElementosContrato = $('form_contrato').getElements();
      for (oElemento of aElementosContrato) {

        if (oElemento.readAttribute('type') == 'button') {
          continue;
        }
        oElemento.addClassName('readonly');
        oElemento.writeAttribute('readonly', 'readonly');
      }
      for (oElemento of aElementosEconomias) {

        if (oElemento.readAttribute('type') == 'button') {

          oElemento.disable();
          continue;
        }
        oElemento.addClassName('readonly');
        oElemento.writeAttribute('readonly', 'readonly');
      }

      oDataGridEconomias.setEvent('onaftercreatebutton', function(oButton) {
        oButton.disable();
      });

      oBtnExcluir.show();
      oBtnSalvar.hide();
      oBtnPesquisar.show();

      oLookupContrato.abrirJanela(true);
    }

    if (oGet.iOpcao == OPCAO_ALTERAR) {

      oBtnSalvar.value = "Alterar";
      oBtnPesquisar.show();
      oLookupContrato.abrirJanela(true);
    }
  </script>
</body>
</html>
