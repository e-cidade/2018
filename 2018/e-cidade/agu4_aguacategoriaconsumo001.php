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

$oParametros = db_utils::postMemory($_GET);
$iOpcao = empty($oParametros->iOpcao) ?: (int) $oParametros->iOpcao;
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/Collection.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/DatagridCollection.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
  <script type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
  <link rel="stylesheet" type="text/css" href="estilos.css">
  <link rel="stylesheet" type="text/css" href="estilos/grid.style.css">
  <link rel="stylesheet" type="text/css" href="estilos/DBtab.style.css">
</head>

<body class="body-default abas">

  <div id="abas"></div>

  <div id="aba_categoria_consumo" class="container">

    <form id="form_categoria_consumo" action="" method="post">

      <input type="hidden" name="opcao" id="opcao" value="<?php echo $iOpcao ?>">

      <fieldset>
        <legend>Categoria de Consumo</legend>
        <table>

          <tr>
            <td>
              <label for="codigo" class="bold">
                <a id="ancora_codigo">Código:</a>
              </label>
            </td>

            <td>
              <input type="text" name="codigo" id="codigo" class="field-size2 readonly" maxlength="10" readonly="readonly" data="x13_sequencial">
              <input type="hidden" name="codigo_descricao" id="codigo_descricao" data="x13_descricao">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="descricao">Descrição:</label>
            </td>

            <td>
              <input type="text" name="descricao" id="descricao" class="field-size6">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="exercicio">Exercício:</label>
            </td>

            <td>
              <input type="text" name="exercicio" id="exercicio" class="field-size2" maxlength="4">
            </td>
          </tr>

        </table>

      </fieldset>

      <input type="button" value="Salvar" id="salvar_categoria">
      <input type="button" value="Excluir" id="excluir_categoria">
      <input type="button" value="Pesquisar" id="pesquisar_categoria">

    </form>

  </div> <!-- /aba_categoria_consumo -->

  <div id="aba_estrutura_tarifaria" class="container" style="width: 800px;">

    <form id="form_estrutura_tarifaria" method="post" action="">

      <fieldset>

        <legend>Estrutura Tarifária</legend>

        <table>

          <tr>
            <td style="width: 120px;">
              <label for="codigo_estrutura" class="bold">
                <a id="label_codigo_estrutura">Código:</a>
              </label>
            </td>

            <td>
              <input type="text" name="codigo_estrutura" id="codigo_estrutura" class="field-size2 readonly" maxlength="10" readonly="readonly">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="tipo_consumo">
                <a id="ancora_tipo_consumo">Tipo de Consumo:</a>
              </label>
            </td>

            <td>
              <input type="text" name="tipo_consumo" id="tipo_consumo" class="field-size2 readonly" maxlength="10" readonly="readonly" data="x25_codconsumotipo">
              <input type="text" name="tipo_consumo_descricao" id="tipo_consumo_descricao" class="field-size6 readonly" readonly="readonly" data="x25_descr">
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold" for="tipo_estrutura">Tipo de Estrutura:</label>
            </td>

            <td>
              <select name="tipo_estrutura" id="tipo_estrutura" class="field-size4">
                <option value="">Selecione</option>
              </select>
            </td>
          </tr>

          <tr id="linha_faixa_consumo" style="display: none;">
            <td>
              <label class="bold" for="faixa_de">Faixa de Consumo:</label>
            </td>

            <td>
              <input type="text" name="faixa_de" id="faixa_de" class="field-size2" maxlength="10">
              <label class="bold" for="faixa_ate">Até:</label>
              <input type="text" name="faixa_ate" id="faixa_ate" class="field-size2" maxlength="10">
            </td>
          </tr>

          <tr id="linha_valor" style="display: none;">
            <td>
              <label class="bold" for="valor">Valor:</label>
            </td>

            <td>
              <input type="text" name="valor" id="valor" class="field-size2" maxlength="10">
            </td>
          </tr>

          <tr id="linha_percentual" style="display: none;">
            <td>
              <label class="bold" for="percentual">Percentual:</label>
            </td>

            <td>
              <input type="text" name="percentual" id="percentual" class="field-size2" maxlength="3">
            </td>
          </tr>

        </table>

      </fieldset>

      <input type="button" value="Salvar" id="salvar_estrutura" style="margin-bottom: 20px;">
      <fieldset>
        <legend>Estruturas Cadastradas</legend>
        <div id="grid_estrutura_tarifaria"></div>
      </fieldset>

    </form>

  </div> <!-- /aba_estrutura_tarifaria -->

  <?php db_menu() ?>

  <script type="text/javascript">
  (function(exports){

    var sCaminhoRPC = 'agu4_aguacategoriaconsumo.RPC.php';
    var iOpcao      = parseInt($('opcao').value);

    var TIPO_ESTRUTURA_VALOR_FIXO    = 1;
    var TIPO_ESTRUTURA_PERCENTUAL    = 2;
    var TIPO_ESTRUTURA_FAIXA_CONSUMO = 3;

    var OPCAO_INCLUSAO  = 1;
    var OPCAO_ALTERACAO = 2;
    var OPCAO_EXCLUSAO  = 3;

    /**
     * Representação da tela de categoria de consumo
     */
    var CategoriaConsumo = function() {

      this.oCodigo    = new DBInput($('codigo'));
      this.oDescricao = new DBInput($('descricao'));
      this.oExercicio = new DBInputInteger($('exercicio'));

      /**
       * Limpar dados em tela
       */
      this.limparDados = function() {
        $('form_categoria_consumo').reset();
      };

      /**
       * Carregar dados do registro em tela
       */
      this.carregarDados = function(iCodigo) {

        var oParametros = {
          'exec'    : 'carregarCategoriaConsumo',
          'iCodigo' : iCodigo
        };
        var oRequisicao = new AjaxRequest(sCaminhoRPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message);
          }

          this.oCodigo.value    = oRetorno.oCategoriaConsumo.iCodigo;
          this.oDescricao.value = oRetorno.oCategoriaConsumo.sDescricao;
          this.oExercicio.value = oRetorno.oCategoriaConsumo.iExercicio;

          oAbaEstruturaTarifaria.desbloquear();

        }.bind(this)).execute();
      };

      /**
       * Salvar registro
       */
      this.salvar = function() {

        var oParametros = {
          'exec'       : 'salvarCategoriaConsumo',
          'iCodigo'    : this.oCodigo.value,
          'sDescricao' : this.oDescricao.value,
          'iExercicio' : this.oExercicio.value
        };
        var oRequisicao = new AjaxRequest(sCaminhoRPC, oParametros, function(oRetorno, lErro) {

          alert(oRetorno.message);
          if (!lErro) {

            this.oCodigo.value = oRetorno.iCodigo;
            oAbaEstruturaTarifaria.desbloquear();
          }

        }.bind(this)).execute();
      };

      /**
       * Excluir de registro
       */
      this.excluir = function() {

        if (this.oCodigo.value === '') {
          return alert('Nenhum registro carregado.');
        }

        var oParametros = {
          'exec'    : 'excluirCategoriaConsumo',
          'iCodigo' : this.oCodigo.value
        };
        var oRequisicao = new AjaxRequest(sCaminhoRPC, oParametros, function(oRetorno, lErro) {

          alert(oRetorno.message);
          if (!lErro) {

            this.limparDados();
            oAbaEstruturaTarifaria.bloquear();
          }

        }.bind(this)).execute();
      };
    }

    /**
     * Representação da tela de estrutura tarifária
     */
    var EstruturaTarifaria = function() {

      this.oCodigo               = new DBInput($('codigo_estrutura'));
      this.oCodigoCategoria      = new DBInput($('codigo'));
      this.oTipoConsumo          = new DBInput($('tipo_consumo'));
      this.oTipoConsumoDescricao = new DBInput($('tipo_consumo_descricao'));
      this.oTipoEstrutura        = $('tipo_estrutura');
      this.oFaixaConsumoDe       = new DBInputInteger($('faixa_de'));
      this.oFaixaConsumoAte      = new DBInputInteger($('faixa_ate'));
      this.oValor                = new DBInputValor($('valor'));
      this.oPercentual           = new DBInputInteger($('percentual'));

      this.limparDados = function() {
        $('form_estrutura_tarifaria').reset();
      };

      /**
       * Salvar registro
       */
      this.salvar = function() {

        var oParametros = {
          'exec'                  : 'salvarEstruturaTarifaria',
          'iCodigo'               : this.oCodigo.value,
          'iCodigoCategoria'      : this.oCodigoCategoria.value,
          'iTipoConsumo'          : this.oTipoConsumo.value,
          'iTipoEstrutura'        : this.oTipoEstrutura.value,
          'iFaixaConsumoDe'       : this.oFaixaConsumoDe.value,
          'iFaixaConsumoAte'      : this.oFaixaConsumoAte.value,
          'nValor'                : this.oValor.value,
          'iPercentual'           : this.oPercentual.value
        };
        var oRequest = new AjaxRequest(sCaminhoRPC, oParametros, function(oRetorno, lErro) {

          alert(oRetorno.message);
          if (!lErro) {

            this.preencherGrid();
            this.limparDados();
          }

        }.bind(this)).execute();
      };

      /**
       * Excluir registro
       */
      this.excluir = function(iCodigo) {

        var oParametros = {
          'exec'    : 'excluirEstruturaTarifaria',
          'iCodigo' : iCodigo
        };
        var oRequest = new AjaxRequest(sCaminhoRPC, oParametros, function(oRetorno, lErro) {

          alert(oRetorno.message);
          if (!lErro) {
            this.preencherGrid();
          }

        }.bind(this)).execute();
      };

      /**
       * Carrega os dados em tela
       */
      this.carregarDados = function(iCodigo) {

        var oParametros = {
          'exec'    : 'carregarEstruturaTarifaria',
          'iCodigo' : iCodigo
        };
        var oRequest = new AjaxRequest(sCaminhoRPC, oParametros, function(oRetorno, lErro) {

          if (lErro) {
            return alert(oRetorno.message);
          }

          this.oCodigo.value               = oRetorno.oEstruturaTarifaria.iCodigo;
          this.oTipoConsumo.value          = oRetorno.oEstruturaTarifaria.iTipoConsumo;
          this.oTipoConsumoDescricao.value = oRetorno.oEstruturaTarifaria.sTipoConsumo;
          this.oTipoEstrutura.value        = oRetorno.oEstruturaTarifaria.iTipoEstrutura;
          this.oFaixaConsumoDe.value       = oRetorno.oEstruturaTarifaria.iFaixaConsumoDe;
          this.oFaixaConsumoAte.value      = oRetorno.oEstruturaTarifaria.iFaixaConsumoAte;
          this.oValor.value                = oRetorno.oEstruturaTarifaria.nValor;
          this.oPercentual.value           = oRetorno.oEstruturaTarifaria.iPercentual;

          aplicarRegraTipoEstrutura();

        }.bind(this)).execute();
      };

      /**
       * Preenchimento da grid
       */
      this.preencherGrid = function() {

        var oParametros = { 'exec' : 'listarEstruturasTarifarias', 'iCodigo' : this.oCodigoCategoria.value };
        var oRequisicao = new AjaxRequest(sCaminhoRPC, oParametros, function(oRetorno, lErro) {

          oCollectionEstruturaTarifaria.clear();
          for (var oRegistroRetorno of oRetorno.aEstruturasTarifarias) {

            var oRegistro = {
              'codigo'         : oRegistroRetorno.iCodigo,
              'tipo_consumo'   : oRegistroRetorno.sTipoConsumo,
              'tipo_estrutura' : oRegistroRetorno.sTipoEstrutura
            }
            oRegistro.valor = js_formatar(oRegistroRetorno.nValor, 'f');
            if (oRegistroRetorno.iTipoEstrutura === TIPO_ESTRUTURA_PERCENTUAL) {
              oRegistro.valor = oRegistroRetorno.iPercentual + '%';
            }
            oCollectionEstruturaTarifaria.add(oRegistro);
          }
          oGridEstruturaTarifaria.reload();

        }.bind(this)).execute();
      }

    };

    function aplicarRegraTipoEstrutura() {

      var sValor = $('tipo_estrutura').value;

      $('linha_percentual').show();
      $('linha_valor').show();
      $('linha_faixa_consumo').show();

      if (sValor != TIPO_ESTRUTURA_FAIXA_CONSUMO) {
        $('linha_faixa_consumo').hide();
      }

      if (sValor != TIPO_ESTRUTURA_PERCENTUAL) {
        $('linha_percentual').hide();
      } else {
        $('linha_valor').hide();
      }
    }

    var oCategoriaConsumo   = new CategoriaConsumo()
    var oEstruturaTarifaria = new EstruturaTarifaria();

    var oAbas                  = new DBAbas($('abas'));
    var oAbaCategoriaConsumo   = oAbas.adicionarAba('Categoria de Consumo', $('aba_categoria_consumo'));
    var oAbaEstruturaTarifaria = oAbas.adicionarAba('Estrutura Tarifária', $('aba_estrutura_tarifaria'));

    var oBotaoSalvarCategoria    = $('salvar_categoria');
    var oBotaoExcluirCategoria   = $('excluir_categoria');
    var oBotaoPesquisarCategoria = $('pesquisar_categoria');
    var oBotaoSalvarEstrutura    = $('salvar_estrutura');

    var oLookUpCategoria = new DBLookUp($('ancora_codigo'), $('codigo'), $('codigo_descricao'));
    var oLookUpTipoConsumo = new DBLookUp($('ancora_tipo_consumo'), $('tipo_consumo'), $('tipo_consumo_descricao'));

    oAbaEstruturaTarifaria.bloquear();

    /**
     * Lookups de pesquisa
     */
    oLookUpCategoria.setArquivo('func_aguacategoriaconsumo.php');
    oLookUpCategoria.setObjetoLookUp('db_iframe_aguacategoriaconsumo');
    oLookUpCategoria.desabilitar();
    oLookUpCategoria.setCallBack('onClick', function() {

      oCategoriaConsumo.carregarDados(this.oInputID.value);
      oEstruturaTarifaria.preencherGrid();
    }.bind(oLookUpCategoria));

    oLookUpTipoConsumo.setArquivo('func_aguaconsumotipo.php');
    oLookUpTipoConsumo.setObjetoLookUp('db_iframe_aguaconsumotipo');

    /**
     * Regras gerais
     */
    $('tipo_estrutura').observe('change', function() {
      aplicarRegraTipoEstrutura();
    });

    /**
     * Botões de ação
     */
    oBotaoSalvarCategoria.observe('click', function(){
      oCategoriaConsumo.salvar();
    });
    oBotaoExcluirCategoria.observe('click', function(){

      if (confirm('Confirma a exclusão da Categoria de Consumo?')) {
        oCategoriaConsumo.excluir();
      }
    });
    oBotaoPesquisarCategoria.observe('click', function() {
      oLookUpCategoria.abrirJanela(true);
    });
    oBotaoSalvarEstrutura.observe('click', function(){
      oEstruturaTarifaria.salvar();
    });

    /**
     * Inicialização e configuração da grid
     */
    var oCollectionEstruturaTarifaria = new Collection().setId("codigo");
    var oGridEstruturaTarifaria = new DatagridCollection(oCollectionEstruturaTarifaria).configure({
      'order'  : false,
      'height' : 200
    });
    oGridEstruturaTarifaria.addColumn("codigo", {
      'label' : "Código",
      'width' : "10%",
      'align' : "center"
    });
    oGridEstruturaTarifaria.addColumn("tipo_consumo", {
      'label' : "Tipo de Consumo",
      'align' : "center",
      'width' : "25%"
    });
    oGridEstruturaTarifaria.addColumn("tipo_estrutura", {
      'label' : "Tipo de Estrutura",
      'align' : "center",
      'width' : "30%"
    });
    oGridEstruturaTarifaria.addColumn("valor", {
      'label' : "Valor",
      'align' : "right",
      'width' : "10%"
    });
    oGridEstruturaTarifaria.addAction("Editar", null, function(oEvento, oRegistro) {
      oEstruturaTarifaria.carregarDados(oRegistro.codigo);
    });
    oGridEstruturaTarifaria.addAction("Excluir", null, function(oEvento, oRegistro) {

      if (confirm('Confirma a exclusão da Estrutura Tarifária?')) {
        oEstruturaTarifaria.excluir(oRegistro.codigo);
      }
    });

    if (iOpcao === OPCAO_INCLUSAO || iOpcao === OPCAO_ALTERACAO) {
      oBotaoExcluirCategoria.hide();
    }
    if (iOpcao === OPCAO_ALTERACAO || iOpcao === OPCAO_EXCLUSAO) {
      oLookUpCategoria.abrirJanela(true);
    }
    if (iOpcao === OPCAO_INCLUSAO) {
      oBotaoPesquisarCategoria.hide();
    }
    if (iOpcao === OPCAO_EXCLUSAO) {

      $('form_categoria_consumo').disable();
      $('form_estrutura_tarifaria').disable();
      var aElementosCategoriaConsumo = $('form_categoria_consumo').getElements();
      var aElementosEstruturaTarifaria = $('form_estrutura_tarifaria').getElements();
      for (var oElemento of aElementosCategoriaConsumo.concat(aElementosEstruturaTarifaria)) {

        oElemento.addClassName('readonly');
        oElemento.writeAttribute('readonly', 'readonly');
      }
      oGridEstruturaTarifaria.setEvent('onaftercreatebutton', function(oButton) {
        oButton.disable();
      });
      oBotaoSalvarCategoria.hide();
      oBotaoExcluirCategoria.enable();
      oBotaoPesquisarCategoria.enable();
    }

    new AjaxRequest(sCaminhoRPC, { 'exec' : 'listarTiposEstruturaTarifaria' }, function (oRetorno, lErro) {

      if (lErro) {
        return alert('Não foi possível carregar as opções de Estrutura Tarifária.');
      }

      for (var oTipo of oRetorno.aTipos) {

        var oOption = document.createElement('option');
        oOption.value = oTipo.iCodigo;
        oOption.innerHTML = oTipo.sDescricao;
        $('tipo_estrutura').appendChild(oOption);
      }
    }).execute();

    oGridEstruturaTarifaria.show($("grid_estrutura_tarifaria"));
  })(this);
  </script>
</body>
</html>
