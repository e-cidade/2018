<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2017  DBSeller Servicos de Informatica
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
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("dbforms/db_funcoes.php"));

$clrotulo = new rotulocampo();
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_nome');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_tipo_origem');
$clrotulo->label('j01_matric');

$get = db_utils::postMemory($_GET);

$importaDividaAtiva = false;
$ocultaOpcao        = "";
$legendaFieldset = 'Importação Geral de Débitos para Cobrança Administrativa';
if (!empty($get->importaDividaAtiva)) {
    $importaDividaAtiva = true;
    $legendaFieldset = 'Importação Geral de Débitos para Dívida Ativa';
    $ocultaOpcao = "hidden='true'";
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="" quiv="Expires" CONTENT="0">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <link href="estilos/DBFormularios.css" rel="stylesheet" type="text/css">
  <link href="estilos/grid.style.css" rel="stylesheet" type="text/css">
  <script src="scripts/scripts.js" type="text/javascript"></script>
  <script src="scripts/strings.js" type="text/javascript"></script>
  <script src="scripts/prototype.js" type="text/javascript"></script>
  <script src="scripts/widgets/Input/DBInput.widget.js" type="text/javascript"></script>
  <script src="scripts/widgets/DBLookUp.widget.js" type="text/javascript"></script>
  <script src="scripts/widgets/Input/DBInputDate.widget.js" type="text/javascript"></script>
  <script src="scripts/widgets/Input/DBInputInteger.widget.js" type="text/javascript"></script>
  <script src="scripts/AjaxRequest.js" type="text/javascript"></script>
  <script src="scripts/widgets/windowAux.widget.js" type="text/javascript"></script>
  <script src="scripts/datagrid.widget.js" type="text/javascript"></script>
  <script src="scripts/widgets/Collection.widget.js" type="text/javascript"></script>
  <script src="scripts/widgets/DatagridCollection.widget.js" type="text/javascript"></script>
  <script src="scripts/classes/diversos/ImportacaoDiversos.js" type="text/javascript"></script>
</head>
<body class="body-default">
<div class="container">
  <form>
    <fieldset style="width: 650px;">
      <legend><?php echo $legendaFieldset; ?></legend>

      <table class="form-container">

        <tr>
          <td>
            <label for="tipoOrigem">Tipo de Origem:</label>
          </td>
          <td class="field-size10">
            <select id="tipoOrigem" onchange="validarOrigem()">
              <option value="">Selecione</option>
            </select>
          </td>
        </tr>
        <tr <?php echo $ocultaOpcao;?>>
          <td>
            <label for="tipoDestino">Tipo de Destino:</label>
          </td>
          <td>
            <select id="tipoDestino">
              <option value="">Selecione</option>
              <option value="34" hidden="true"></option>
            </select>
          </td>
        </tr>

        <tr>
          <td>
            <label for="unificarDebitosNumpreReceita">Unificar Débitos por Numpre e Receita:</label>
          </td>
          <td>
            <select id="unificarDebitosNumpreReceita">
              <option value="1">Não</option>
              <option value="2">Sim</option>
            </select>
          </td>
        </tr>
        <tr id="linhaDataVencimento" style="display: none">
          <td>
            <label for="dataVencimento">Data de Vencimento:</label>
          </td>
          <td>
            <select id="dataVencimento">
              <option value="">Selecione</option>
              <option value="1">Menor vencimento das parcelas abertas</option>
              <option value="2">Maior vencimento das parcelas abertas</option>
            </select>
          </td>
        </tr>
        <tr id="ctnProcessosSistemas">
          <td>
            <label for="processosistema">Processo Sistema:</label>
          </td>
          <td>
            <select id="processosistema" onchange="verificaProcessoSistema()">
              <option value="t">Sim</option>
              <option value="f">Não</option>
            </select>
          </td>
        </tr>
        <tr id="trProcessoSistema">
          <td>
            <label id="lblProcessoSistema">Processo:</label>
          </td>
          <td>
            <input id="codigo_processo" data="p58_codproc">
            <input id="requerente" data="p58_requer">
          </td>
        </tr>
        <tr class='processoforasistema' style="display: none">
          <td><label for="codigo_processo_fora_sistema">Processo:</label></td>
          <td><input id="codigo_processo_fora_sistema"/></td>
        </tr>
        <tr class='processoforasistema' style="display: none">
          <td><label for="titular_processo_fora_sistema">Titular do Processo:</label></td>
          <td><input id="titular_processo_fora_sistema" style="width: 100%"/></td>
        </tr>
        <tr class='processoforasistema' style="display: none">
          <td><label for="data_processo_fora_sistema">Data do Processo:</label></td>
          <td><input id="data_processo_fora_sistema"/></td>
        </tr>
      </table>
    </fieldset>
    <div id="divNotificacaoDebitosOrigem" style="display: none;" >

      <div style="background-color: #fcf8e3; border: 1px solid #fcc888; padding: 10px; margin: 5px;">
        Para importação de débitos de origem ISSQN Variável não é permitido unificar os débitos por Numpre e Receita.
      </div>
    </div>
    <input type="button" name="pesquisar" id="pesquisar" value="Visualizar Débitos" onclick="pesquisaDebitos()" />
  </form>
</div>

<?php db_menu(); ?>

<script type="text/javascript">

  var get = js_urlToObject();


  var importaDividaAtiva = false;
  if (get.importaDividaAtiva !== undefined) {
    importaDividaAtiva = get.importaDividaAtiva === 'true';
  }


  if (!importaDividaAtiva) {
    $('ctnProcessosSistemas').style.display = 'none';
    $('trProcessoSistema').style.display = 'none';
  }

  var input = {
    "tipoOrigem"  : $('tipoOrigem'),
    "tipoDestino" : $('tipoDestino'),
    "unificarDebito"  : $('unificarDebitosNumpreReceita'),
    "dataVencimento"  : $('dataVencimento'),
    "processoSistema" : $('processosistema'),
    "codigoProcesso" : new DBInputInteger($('codigo_processo')),
    "requerente" : $('requerente'),
    "codigoProcessoForaSistema" : new DBInput($('codigo_processo_fora_sistema')),
    "titularProcesso" : new DBInput($('titular_processo_fora_sistema')),
    "dataProcesso" : new DBInputDate($('data_processo_fora_sistema'))
  };


  function verificaProcessoSistema() {

    if (!importaDividaAtiva) {
      return false;
    }

      $$('.processoforasistema').each(
        function (elemento) {

          if (input.processoSistema.value === 'f') {

            $('trProcessoSistema').style.display = 'none';
            elemento.style.display = '';
          } else {

            elemento.style.display = 'none';
            $('trProcessoSistema').style.display = '';
          }
        }
      );
  }

  function carregaOpcoesSelect() {

    new AjaxRequest(
      'dvr3_importacaoiptu.RPC.php',
      {'sExec' : 'tiposDebitoGeral', 'importaDividaAtiva' : importaDividaAtiva},
      function(oRetorno, lErro) {

        if (lErro === true) {
          alert(oRetorno.message.urlDecode());
        }

        var oSelectTipoOrigem = $('tipoOrigem');
        for (oTipoOrigem of oRetorno.aOpcoesTipoOrigem) {

          var oOpcaoSelectTipoOrigem       = document.createElement('option');
          oOpcaoSelectTipoOrigem.value     = oTipoOrigem.k00_tipo;
          oOpcaoSelectTipoOrigem.innerHTML = oTipoOrigem.k00_descr.urlDecode();
          oSelectTipoOrigem.appendChild(oOpcaoSelectTipoOrigem);
        }

        var oSelectTipoDestino = $('tipoDestino');
        for (oTipoDestino of oRetorno.aOpcoesTipoDestino) {

          var oOpcaoSelectTipoDestino       = document.createElement('option');
          oOpcaoSelectTipoDestino.value     = oTipoDestino.k00_tipo;
          oOpcaoSelectTipoDestino.innerHTML = oTipoDestino.k00_descr.urlDecode();
          oSelectTipoDestino.appendChild(oOpcaoSelectTipoDestino);
        }

      }).execute();
  }

  function validaCampos() {

    if (empty($F('tipoOrigem'))) {
      alert('Campo Tipo de Origem é de preenchimento obrigatório.');
      return false;
    }

    if (empty($F('tipoDestino'))) {
      alert('Campo Tipo de Destino é de preenchimento obrigatório.');
      return false;
    }

    if ($F('unificarDebitosNumpreReceita') == 2) {

      if (empty($F('dataVencimento'))) {

        alert('Campo Data de Vencimento é de preenchimento obrigatório.');
        return false;
      }
    }

    return true;
  }

  function pesquisaDebitos() {

    if (importaDividaAtiva===true) {
      input.tipoDestino.value = 34;
    }

    if (!validaCampos()) {
      return false;
    }

    var tituloJanela = 'Receitas para Importação de Cobrança Administrativa';
    if (importaDividaAtiva === true) {
      tituloJanela = 'Receitas para Importação de Dívida Ativa';
    }

    var oDiv = document.createElement('div');
    oDiv.style.width = '780px';
    var oWindowImportacaoGeralDiversos = new windowAux('oWindowImportacaoGeralDiversos', tituloJanela, 800, 460);
    oWindowImportacaoGeralDiversos.setContent(oDiv);
    oWindowImportacaoGeralDiversos.setShutDownFunction(function(){oWindowImportacaoGeralDiversos.destroy()});
    oWindowImportacaoGeralDiversos.show();

    var oComponente = new ImportacaoDiversos(oDiv, $F('tipoOrigem'), $F('tipoDestino'), $F('unificarDebitosNumpreReceita') == 2);
    if (importaDividaAtiva === true) {
      oComponente.setImportacaoDividaAtiva(true);
    }

    if ($F('unificarDebitosNumpreReceita') == 2) {
      oComponente.iDataVencimento = $F('dataVencimento');
    }
    oComponente.setCallbackProcessar(
      function () {

        input.tipoOrigem.value = "";
        input.tipoDestino.value = "";
        input.unificarDebito.value = "1";
        input.dataVencimento.value = "";
        input.codigoProcesso.value = '';
        input.requerente.value = '';
        input.codigoProcessoForaSistema.value = '';
        input.titularProcesso.value = '';
        input.dataProcesso.value = '';
        input.processoSistema.value = 't';
        verificaProcessoSistema();
        $('linhaDataVencimento').setStyle({'display': 'none'});
        oWindowImportacaoGeralDiversos.destroy();
      }
    );
    oComponente.render();

  }

  var oLookUpProcesso = new DBLookUp($('lblProcessoSistema'), $('codigo_processo'), $('requerente'), {
    "sArquivo" : "func_protprocesso.php",
    "sObjetoLookUp" : "db_iframe_processo",
    "sLabel" : "Pesquisar Processos",
    "aParametrosAdicionais" : ['requerente=1']
  });
  /**
   * Carrega os campos select da tela conforme retorno do RPC.
   */
  carregaOpcoesSelect();

  /**
   * Controla o filtro selecionado e se o campo 'Data de Vencimento' deve ser exibido.
   */
  $('unificarDebitosNumpreReceita').observe('change', function(){

    $('linhaDataVencimento').setStyle({'display': 'none'});
    $('dataVencimento').value = '';

    if (this.value == 2) {
      $('linhaDataVencimento').setStyle({'display': 'table-row'});
    }
  });

  function validarOrigem() {

    $('divNotificacaoDebitosOrigem').style.display = 'none';
    input.unificarDebito.disabled = false;
    if ($F('tipoOrigem') === '3') {

      $('divNotificacaoDebitosOrigem').style.display = '';
      input.unificarDebito.value = '1';
      input.unificarDebito.disabled = true;
      input.dataVencimento.value = "";
      $('linhaDataVencimento').setStyle({'display': 'none'});
    }
  }

</script>
</body>
</html>
