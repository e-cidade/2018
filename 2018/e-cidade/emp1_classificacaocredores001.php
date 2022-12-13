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
require_once modification("libs/db_app.utils.php");
require_once modification("libs/db_utils.php");

$oGET   = db_utils::postMemory($_GET);
$iOpcao = (int) $oGET->opcao;

define("INCLUSAO",  1);
define("ALTERACAO", 2);
define("EXCLUSAO",  3);

$oRotuloLista = new rotulo("classificacaocredores");
$oRotuloLista->label();
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" content="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLancador.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
  <link rel="stylesheet" type="text/css" href="estilos.css">
  <link rel="stylesheet" type="text/css" href="estilos/grid.style.css">
  <link rel="stylesheet" type="text/css" href="estilos/DBtab.style.css">
</head>
<style>
  .divAbas {
    postion: relative;
    left: 5px;
  }
</style>

<body class="body-default abas">

<div id="ctnAbas" class='divAbas'></div>

<div id="ctnDados" class="container">

  <fieldset style="width: 500px;">
    <legend class="bold">Lista de Classificação de Credores</legend>
    <table>
      <tr>
        <td>
          <label class="bold" for="cc30_codigo">
            Código:
          </label>
        </td>
        <td>
          <?php db_input("cc30_codigo", 10, 1, true, 'text'); ?>
        </td>
      </tr>
      <tr>
        <td>
          <label class="bold" for="cc30_descricao">
            Descrição:
          </label>
        </td>
        <td>
          <?php db_input("cc30_descricao", 45, 0, true, 'text', 1); ?>
        </td>
      </tr>
      <tr>
        <td>
          <label class="bold" for="cc30_dispensa">
            Lista do Tipo Dispensa:
          </label>
        </td>
        <td>
          <?php
          $aDispensas = array(
            "0" => "Não",
            "1" => "Sim"
          );
          db_select("cc30_dispensa", $aDispensas, true, 1, "style='width: 54px;'");
          ?>
        </td>
      </tr>
    </table>

    <fieldset class="separator">
      <legend class="bold">Dados do Prazo de Vencimento</legend>
      <table>
        <tr>
          <td>
            <label class="bold" for="cc30_diasvencimento">
              Quantidade de Dias para o Vencimento:
            </label>
          </td>
          <td>
            <?php db_input("cc30_diasvencimento", 10, 1, true, 'text', 1, "style='width: 77px;'"); ?>
          </td>
        </tr>
        <tr>
          <td>
            <label class="bold" for="cc30_contagemdias">
              Vencimento em Dias:
            </label>
          </td>
          <td>
            <?php
            $aContagemDias = array(
              "0" => "Selecione",
              "1" => "Úteis",
              "2" => "Corridos"
            );
            db_select("cc30_contagemdias", $aContagemDias, true, 1, "style='width: 78px;'");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>

    <fieldset class="separator">
      <legend class="bold">Faixa de Valores</legend>
      <table>
        <tr>
          <td>
            <label class="bold" for="cc30_valorinicial"> De: </label><?php db_input("cc30_valorinicial", 10, 0, true, 'text', 1); ?>
            <label class="bold" for="cc30_valorfinal"> Até: </label><?php  db_input("cc30_valorfinal", 10, 0, true, 'text', 1); ?>
          </td>
        </tr>
      </table>
    </fieldset>

  </fieldset>
</div>

<div id="ctnElemento" class="container">
  <fieldset style="width: 600px;">
    <legend class="bold">Elementos de Despesa</legend>
    <div id="ctnElementoInclusao"></div>
    <div id="ctnElementoExclusao"></div>
  </fieldset>
</div>

<div id="ctnRecurso" class="container">
  <div style="width: 600px;">
    <fieldset>
      <legend class="bold">Recursos</legend>
      <div id="ctnLancadorRecursos"></div>
    </fieldset>
  </div>
</div>

<div id="ctnTipoCompra">
  <div id="lancadorTipoCompra" class="container"></div>
</div>

<div id="ctnEvento">
  <div id="lancadorEvento" class="container"></div>
</div>

<p style="text-align: center;">
  <?php $sVisibilidadeSalvar    = in_array($iOpcao, array(INCLUSAO, ALTERACAO)) ?: 'display: none' ?>
  <?php $sVisibilidadeExcluir   = $iOpcao === EXCLUSAO ?: 'display: none' ?>
  <?php $sVisibilidadePesquisar = in_array($iOpcao, array(ALTERACAO, EXCLUSAO)) ?: 'display: none' ?>
  <?php $sDesabilitarSalvar     = $iOpcao !== ALTERACAO ?: 'disabled="disabled"' ?>
  <?php $sVisibilidadeImportar  = $iOpcao === INCLUSAO ?: 'display: none' ?>

  <input type="button" id="btnSalvar" value="Salvar" <?php echo $sDesabilitarSalvar ?> style="<?php echo $sVisibilidadeSalvar ?>" />
  <input type="button" id="btnExcluir" value="Excluir" style="<?php echo $sVisibilidadeExcluir ?>" />
  <input type="button" id="btnPesquisar" value="Pesquisar" style="<?php echo $sVisibilidadePesquisar ?>" />
  <input type="button" id="btnImportar" value="Importar" style="<?php echo $sVisibilidadeImportar ?>" />
  <input type="hidden" id="opcao" name="opcao" value="<?php echo $iOpcao ?>" />
</p>

<?php db_menu() ?>

<script>
  const PATH_MENSAGENS = 'financeiro.empenho.emp1_classificacaocredores001.';

  var oLancadorElementoInclusao;
  var oLancadorElementoExclusao;
  var oLancadorTipoCompra;
  var oLancadorRecursos;
  var oLancadorEvento;

  var oDBAba         = new DBAbas( $('ctnAbas') );
  var oAbaDados      = oDBAba.adicionarAba("Dados da Lista", $('ctnDados') );
  var oAbaElemento   = oDBAba.adicionarAba("Elemento", $('ctnElemento') );
  var oAbaRecurso    = oDBAba.adicionarAba("Recurso", $('ctnRecurso') );
  var oAbaTipoCompra = oDBAba.adicionarAba("Tipo de Compra", $('ctnTipoCompra') );
  var oAbaEvento     = oDBAba.adicionarAba("Evento", $('ctnEvento') );

  var oBtnSalvar    = $('btnSalvar');
  var oBtnPesquisar = $('btnPesquisar');
  var oBtnExcluir   = $('btnExcluir');
  var oBtnImportar  = $('btnImportar');
  var iOpcao        = $('opcao').value;
  var oValorInicial = new DBInputValor($('cc30_valorinicial'));
  var oValorFinal   = new DBInputValor($('cc30_valorfinal'));

  var EXCLUSAO = 3;
  var INCLUSAO = 1;

  function pesquisar() {

    var oJanela = js_OpenJanelaIframe(
      'CurrentWindow.corpo',
      'db_iframe_classificacaocredores',
      'func_classificacaocredores.php?funcao_js=parent.carregarDados|cc30_codigo',
      'Pesquisa de Lista de Classificação de Credores',
      true
    );
  }

  function carregarDados(iCodigo) {

    var oParametros = {
      exec    : 'getDados',
      iCodigo : iCodigo
    };


    var fnRetorno = function(oRetorno, lErro) {

      var aRecursos;

      limpaCampos();
      if (iOpcao != INCLUSAO) {
        $('cc30_codigo').value        = oRetorno.iCodigo;
      }
      $('cc30_descricao').value       = oRetorno.sDescricao;
      $('cc30_diasvencimento').value  = oRetorno.iDiasVencimento;
      $('cc30_contagemdias').value    = oRetorno.iContagemDias;
      $('cc30_dispensa').value        = Number(oRetorno.lDispensa).toString();
      oValorInicial.setValue(oRetorno.nValorInicial);
      oValorFinal.setValue(oRetorno.nValorFinal);


      /*
        desativamos a renderização automatica pois carrega muitos registros de primeira
      */
      oLancadorElementoExclusao.lRenderizarAutomatico = false;
      oLancadorElementoInclusao.lRenderizarAutomatico = false;
      oLancadorTipoCompra.lRenderizarAutomatico = false;
      oLancadorRecursos.lRenderizarAutomatico = false;
      oLancadorEvento.lRenderizarAutomatico = false;
      

      for (var oConta of oRetorno.aContas) {

        if (oConta.lExclusao) {
          oLancadorElementoExclusao.adicionarRegistro(oConta.iCodigo, oConta.sDescricao);
        } else {
          oLancadorElementoInclusao.adicionarRegistro(oConta.iCodigo, oConta.sDescricao);
        }
      }
 
      aRecursos = [];
      for (var oRecurso of oRetorno.aRecursos) {
        aRecursos.push([oRecurso.iCodigo, oRecurso.sDescricao]);
      }
      oLancadorRecursos.carregarRegistros(aRecursos);

      for (var oTipoCompra of oRetorno.aTiposCompra) {
        oLancadorTipoCompra.adicionarRegistro(oTipoCompra.iCodigo, oTipoCompra.sDescricao);
      }

      for (var oEvento of oRetorno.aEventos) {
        oLancadorEvento.adicionarRegistro(oEvento.iCodigo, oEvento.sDescricao);
      }


      oLancadorElementoExclusao.renderizarRegistros();
      oLancadorElementoInclusao.renderizarRegistros();
      oLancadorTipoCompra.renderizarRegistros();
      oLancadorRecursos.renderizarRegistros();
      oLancadorEvento.renderizarRegistros();

      /*
        reativamos a renderização automatica apos carregas os registros iniciais,
        com isso ao selecionarmos um novo registro a incluir, sistema já o carrega na lista.
      */
      oLancadorElementoExclusao.lRenderizarAutomatico = true;
      oLancadorElementoInclusao.lRenderizarAutomatico = true;
      oLancadorTipoCompra.lRenderizarAutomatico = true;
      oLancadorRecursos.lRenderizarAutomatico = true;
      oLancadorEvento.lRenderizarAutomatico = true;
      

      
      /**
       * Ativa o botão
       */
      oBtnSalvar.removeAttribute('disabled');
      db_iframe_classificacaocredores.hide();
      
    };

    new AjaxRequest("emp1_classificacaocredores.RPC.php", oParametros, fnRetorno).execute();
  }

  function limpaCampos() {

    $('cc30_codigo').value          = '';
    $('cc30_descricao').value       = '';
    $('cc30_diasvencimento').value  = '';
    $('cc30_dispensa').value        = '0';
    $('cc30_contagemdias').value    = '0';
    oValorInicial.setValue(0);
    oValorFinal.setValue(0);

    oLancadorElementoInclusao.clearAll();
    oLancadorElementoExclusao.clearAll();
    oLancadorEvento.clearAll();
    oLancadorRecursos.clearAll();
    oLancadorTipoCompra.clearAll();
  }

  function salvar() {

    if (verificaElementosDuplicados().length > 0) {
      return alert(_M(PATH_MENSAGENS + 'elementos_duplicados'));
    }

    var lListaDispensa = $F('cc30_dispensa') == '1';
    /**
     * Campos obrigatórios
     */
    var oListaDescricao = $('cc30_descricao');
    if (empty(oListaDescricao.value)) {
      return alert(_M(PATH_MENSAGENS + 'descricao_obrigatorio'));
    }

    var oDiasVencimento = $('cc30_diasvencimento');
    if (empty(oDiasVencimento.value) && !lListaDispensa) {
      return alert(_M(PATH_MENSAGENS + 'quantidade_dias_obrigatorio'));
    }

    var oContagemDias = $('cc30_contagemdias');
    if (oContagemDias.value == "0" && !lListaDispensa) {
      return alert(_M(PATH_MENSAGENS + 'vencimento_em_dias_obrigatorio'));
    }
    /**
     * Valida se o valor final está maior que o valor inicial
     * e se um dos campos está preenchido e o outro vazio
     */
    var nValorInicial = parseFloat(oValorInicial.value);
    var nValorFinal   = parseFloat(oValorFinal.value);
    if (!empty(nValorInicial) && !empty(nValorFinal)) {

      if(nValorInicial > nValorFinal) {
        return alert("O Valor Inicial não pode ser maior que o Valor Final.");
      }
    }

    if( (empty(nValorInicial) && !empty(nValorFinal)) || (!empty(nValorInicial) && empty(nValorFinal)) ) {
      return alert("Para informar a Faixa de Valores é obrigatório informar os campos Valor Inicial e Valor Final.");
    }

    var oParametro = {
      exec               : 'salvar',
      iCodigo            : $('cc30_codigo').value,
      aElementosInclusao : configurarRegistroLancadores(oLancadorElementoInclusao.getRegistros()),
      aElementosExclusao : configurarRegistroLancadores(oLancadorElementoExclusao.getRegistros()),
      aRecursos          : configurarRegistroLancadores(oLancadorRecursos.getRegistros()),
      aTiposCompra       : configurarRegistroLancadores(oLancadorTipoCompra.getRegistros()),
      aEvento            : configurarRegistroLancadores(oLancadorEvento.getRegistros()),
      sDescricao         : $F('cc30_descricao'),
      iDiasVencimento    : $F('cc30_diasvencimento'),
      iContagemDias      : $F('cc30_contagemdias'),
      nValorInicial      : oValorInicial.getValue(),
      nValorFinal        : oValorFinal.getValue(),
      lDispensa          : $F('cc30_dispensa')
    };
    var fnRetorno = function(oRetorno, lErro) {

      alert(oRetorno.message.urlDecode());
      $('cc30_codigo').value = oRetorno.iCodigo;
      if (lErro) {
        return;
      }
    };
    new AjaxRequest("emp1_classificacaocredores.RPC.php", oParametro, fnRetorno).execute();
  }

  function excluir() {

    if ($F('cc30_codigo') == "") {
      return alert(_M(PATH_MENSAGENS + 'exclusao_codigo_vazio'));
    }

    var iUltimaPosicaoReservada = 100;
    if ($F('cc30_codigo') <= iUltimaPosicaoReservada) {
      alert("Não é possível excluir esta lista, pois a mesma é padrão do sistema.");
      return false;
    }

    if (!confirm(_M(PATH_MENSAGENS + 'confirma_exclusao'))) {
      return false;
    }

    var oParametros = {
      exec    : 'excluir',
      iCodigo : $F('cc30_codigo')
    };

    var fnRetorno = function(oRetorno, lErro) {

      alert(oRetorno.message.urlDecode());
      if (lErro) {
        return;
      }

      limpaCampos();
    };

    new AjaxRequest("emp1_classificacaocredores.RPC.php", oParametros, fnRetorno).execute();
  }

  /**
   * Verifica se existem um elemento ao mesmo tempo para inclusão e exclusão.
   * @returns {Array}
   */
  function verificaElementosDuplicados() {

    var aElementosInclusao = oLancadorElementoInclusao.getRegistros();
    var aElementosExclusao = oLancadorElementoExclusao.getRegistros();
    var aElementosDuplicados = [];

    aElementosExclusao.each(
      function (oContaExclusao) {

        aElementosInclusao.each(
          function (oContaInclusao) {

            if (oContaExclusao.sCodigo == oContaInclusao.sCodigo) {
              aElementosDuplicados.push(oContaInclusao.sCodigo);
            }
          }
        );
      }
    );

    return aElementosDuplicados;
  }

  function criarLancadoresElementoDespesa () {

    oLancadorElementoInclusao = new DBLancador('oLancadorElementoInclusao');
    oLancadorElementoInclusao.setNomeInstancia('oLancadorElementoInclusao');
    oLancadorElementoInclusao.setLabelAncora('Elemento:');
    oLancadorElementoInclusao.setLabelValidacao('Elemento');
    oLancadorElementoInclusao.setTituloJanela('Pesquisa de Elemento');
    oLancadorElementoInclusao.setParametrosPesquisa('func_conplanoorcamento.php', ['c60_codcon', 'c60_estrut', 'c60_descr'], "sSomenteEstrutural=3&lEstrutural=true");
    oLancadorElementoInclusao.setTextoFieldset('Elementos de Inclusão');
    oLancadorElementoInclusao.setCamposAdicionais(true);
    oLancadorElementoInclusao.setGridHeight(200);
    if (iOpcao == EXCLUSAO) {
      oLancadorElementoInclusao.setHabilitado(false);
    }
    oLancadorElementoInclusao.show($('ctnElementoInclusao'));

    oLancadorElementoExclusao = new DBLancador('oLancadorElementoExclusao');
    oLancadorElementoExclusao.setNomeInstancia('oLancadorElementoExclusao');
    oLancadorElementoExclusao.setLabelAncora('Elemento:');
    oLancadorElementoExclusao.setLabelValidacao('Elemento');
    oLancadorElementoExclusao.setTituloJanela('Pesquisa de Elemento');
    oLancadorElementoExclusao.setParametrosPesquisa('func_conplanoorcamento.php', ['c60_codcon', 'c60_estrut', 'c60_descr'], "sSomenteEstrutural=3&lEstrutural=true");
    oLancadorElementoExclusao.setTextoFieldset('Elementos de Exclusão');
    oLancadorElementoExclusao.setCamposAdicionais(true);
    oLancadorElementoExclusao.setGridHeight(200);
    if (iOpcao == EXCLUSAO) {
      oLancadorElementoExclusao.setHabilitado(false);
    }
    oLancadorElementoExclusao.show($('ctnElementoExclusao'));
  }

  function criarLancadorTipoCompra() {

    oLancadorTipoCompra = new DBLancador('oLancadorTipoCompra');
    oLancadorTipoCompra.setNomeInstancia('oLancadorTipoCompra');
    oLancadorTipoCompra.setLabelAncora('Tipo de Compra:');
    oLancadorTipoCompra.setLabelValidacao('Tipo de Compra');
    oLancadorTipoCompra.setTituloJanela('Pesquisa de Tipo de Compra');
    oLancadorTipoCompra.setParametrosPesquisa('func_pctipocompra.php', ['pc50_codcom', 'pc50_descr']);
    oLancadorTipoCompra.setTextoFieldset('Tipos de Compra');
    oLancadorTipoCompra.setGridHeight(200);
    if (iOpcao == EXCLUSAO) {
      oLancadorTipoCompra.setHabilitado(false);
    }
    oLancadorTipoCompra.show($('lancadorTipoCompra'));
  }

  function criarLancadorRecursos() {

    oLancadorRecursos = new DBLancador('oLancadorRecursos');
    oLancadorRecursos.setNomeInstancia('oLancadorRecursos');
    oLancadorRecursos.setLabelAncora('Recurso:');
    oLancadorRecursos.setLabelValidacao('Recurso');
    oLancadorRecursos.setTituloJanela('Pesquisa de Recurso');
    oLancadorRecursos.setParametrosPesquisa('func_orctiporec.php', ['o15_codigo', 'o15_descr']);
    oLancadorRecursos.setTextoFieldset('Recursos');
    oLancadorRecursos.setCamposAdicionais(true);
    oLancadorRecursos.setGridHeight(200);
    if (iOpcao == EXCLUSAO) {
      oLancadorRecursos.setHabilitado(false);
    }
    oLancadorRecursos.show($('ctnLancadorRecursos'));
  }

  function criarLancadorEvento() {

    oLancadorEvento = new DBLancador('oLancadorEvento');
    oLancadorEvento.setNomeInstancia('oLancadorEvento');
    oLancadorEvento.setLabelAncora('Evento:');
    oLancadorEvento.setLabelValidacao('Evento');
    oLancadorEvento.setTituloJanela('Pesquisa de Evento');
    oLancadorEvento.setParametrosPesquisa('func_empprestatip.php', ['e44_tipo', 'e44_descr']);
    oLancadorEvento.setTextoFieldset('Eventos');
    oLancadorEvento.setGridHeight(200);
    if (iOpcao == EXCLUSAO) {
      oLancadorEvento.setHabilitado(false);
    }
    oLancadorEvento.show($('lancadorEvento'));
  }

  function init() {

    $('cc30_codigo').style.width = '54px';
    criarLancadoresElementoDespesa();
    criarLancadorTipoCompra();
    criarLancadorRecursos();
    criarLancadorEvento();

    oBtnSalvar.observe('click', salvar);
    oBtnPesquisar.observe('click', pesquisar);
    oBtnExcluir.observe('click', excluir);
    oBtnImportar.observe('click', pesquisar);

    /**
     * Abre lookup de pesquisa se não é inclusão
     */
    if (iOpcao != INCLUSAO) {
      pesquisar();
    }
  }

  function configurarRegistroLancadores(aRegistrosLancador) {

    var aRetornoRegistro = [];
    aRegistrosLancador.each(
      function (oLinha) {
        aRetornoRegistro.push(
          {
            sCodigo : oLinha.sCodigo,
            sDescricao : encodeURIComponent(oLinha.sDescricao.trim())
          }
        );
      }
    );
    return aRetornoRegistro;
  }

  (function(){
    init();
  })();
</script>

<?php if ($iOpcao === EXCLUSAO) : ?>
  <script>
    (function(){

      var aElementos = [
        'cc30_codigo',
        'cc30_descricao',
        'cc30_diasvencimento',
        'cc30_contagemdias',
        'cc30_valorinicial',
        'cc30_valorfinal',
        'cc30_dispensa'
      ];
      aElementos.each(
        function (sCampo) {

          var oElemento         = $(sCampo);
          oElemento.style.color = '#000';
          oElemento.className   = 'readonly';
          oElemento.setAttribute('disabled', 'disabled');
        }
      );
    })();
  </script>
<?php endif ?>
</body>
</html>
