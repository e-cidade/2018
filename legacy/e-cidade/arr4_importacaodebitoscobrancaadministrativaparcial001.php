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
require_once(modification("dbforms/db_funcoes.php"));

$oRotulo = new rotulocampo();
$oRotulo->label("q02_inscr");
$oRotulo->label("z01_nome");
$oRotulo->label("z01_numcgm");
$oRotulo->label("z01_tipo_origem");
$oRotulo->label("j01_matric");


$get = db_utils::postMemory($_GET);
$importaDividaAtiva = false;
$ocultaOpcao        = "";
$legendaFieldset = 'Importação Parcial de Débitos para Cobrança Administrativa';
if (!empty($get->importaDividaAtiva)) {

    $importaDividaAtiva = true;
    $legendaFieldset = 'Importação Parcial de Débitos para Dívida Ativa';
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
    <script src="scripts/AjaxRequest.js" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInput.widget.js" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputInteger.widget.js" type="text/javascript"></script>
    <script src="scripts/widgets/Input/DBInputDate.widget.js" type="text/javascript"></script>
    <?php
    db_app::load("datagrid.widget.js, windowAux.widget.js, dbmessageBoard.widget.js, DBLookUp.widget.js");
    db_app::load("DBViewImportacaoDiversos.classe.js");
    ?>
</head>
<body class="body-default">
<div class="container">
    <form>
        <fieldset>
            <legend><?php echo $legendaFieldset; ?></legend>

            <table class="form-container">

                <tr>
                    <td>
                        <label for="z01_numcgm">
                            <a href="#" id="numCgm">Nome/Razão Social:</a>
                        </label>
                    </td>
                    <td>
                        <?php
                        db_input("z01_numcgm", 10, $Iz01_numcgm  , true, "text", 1);
                        db_input("z01_nome"  , 40, $Iz01_nome  , true, "text", 3);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="j01_matric">
                            <a href="#" id="matricula"><?=$Lj01_matric?></a>
                        </label>
                    </td>
                    <td>
                        <?php
                        db_input("j01_matric", 10, $Ij01_matric  , true, 'text', 1);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="q02_inscr">
                            <a href="#" id="inscricao"><?=$Lq02_inscr?></a>
                        </label>
                    </td>
                    <td>
                        <?php
                        db_input("q02_inscr", 10, $Iq02_inscr, true, "text", 1);
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <label for="tipoOrigem">Tipo de Origem:</label>
                    </td>
                    <td>
                        <select id="tipoOrigem"></select>
                    </td>
                </tr>

                <tr <?php echo $ocultaOpcao;?>>
                    <td>
                        <label for="tipoDestino">Tipo de Destino:</label>
                    </td>
                    <td>
                        <select id="tipoDestino">
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
        <input type="button" name="pesquisar" id="pesquisar" value="Visualizar Débitos" onclick="pesquisaDebitos()" />
    </form>
</div>
<?php db_menu(); ?>
<script type="text/javascript">

  var input = {
    "processoSistema" : $('processosistema'),
    "codigoProcesso" : new DBInputInteger($('codigo_processo')),
    "requerente" : $('requerente'),
    "codigoProcessoForaSistema" : new DBInput($('codigo_processo_fora_sistema')),
    "titularProcesso" : new DBInput($('titular_processo_fora_sistema')),
    "dataProcesso" : new DBInputDate($('data_processo_fora_sistema')),
    "unificarDebito"  : $('unificarDebitosNumpreReceita')
  };

  var tipoDebitoParcelamento = [];

  var oLookUpProcesso = new DBLookUp($('lblProcessoSistema'), $('codigo_processo'), $('requerente'), {
    "sArquivo" : "func_protprocesso.php",
    "sObjetoLookUp" : "db_iframe_processo",
    "sLabel" : "Pesquisar Processos",
    "aParametrosAdicionais" : ['requerente=1']
  });

  var oSelectTipoOrigem  = $("tipoOrigem");
  var oSelectTipoDestino = $("tipoDestino");

  oSelectTipoOrigem.disabled = true;
  oSelectTipoDestino.disabled = true;

  oSelectTipoOrigem.classList.add("readonly");
  oSelectTipoDestino.classList.add("readonly");

  var oLookupCgm = new DBLookUp($("numCgm"), $("z01_numcgm"), $("z01_nome"), {
    "sArquivo": "func_nome.php",
    "sLabel": "Pesquisa de Nome/Razão Social"
  });

  oLookupCgm.setCallBack("onClick", function() {
    carregaOpcoesSelect("z01_numcgm");
  });
  oLookupCgm.setCallBack("onChange", function() {
    carregaOpcoesSelect("z01_numcgm");
  });

  var oLookupMatricula = new DBLookUp($("matricula"), $("j01_matric"), $("z01_nome"), {
    "sArquivo": "func_iptubase.php",
    "sLabel": "Pesquisa de Matrícula do Imóvel"
  });

  oLookupMatricula.setCallBack("onClick", function() {
    carregaOpcoesSelect("j01_matric");
  });
  oLookupMatricula.setCallBack("onChange", function() {
    carregaOpcoesSelect("j01_matric");
  });

  var oLookupInscricao = new DBLookUp($("inscricao"), $("q02_inscr"), $("z01_nome"), {
    "sArquivo": "func_issbase.php",
    "sLabel": "Pesquisa de Inscrição Municipal"
  });

  oLookupInscricao.setCallBack("onClick", function() {
    carregaOpcoesSelect("q02_inscr");
  });
  oLookupInscricao.setCallBack("onChange", function() {
    carregaOpcoesSelect("q02_inscr");
  });

  var get = js_urlToObject();
  var importaDividaAtiva = false;

  if (get.importaDividaAtiva !== undefined) {
    importaDividaAtiva = get.importaDividaAtiva === 'true';
  }

  function validaCampos() {

    if (empty($F("z01_numcgm")) && empty($F("j01_matric")) && empty($F("q02_inscr"))) {
      alert("É necessário informar o campo Nome/Razão Social, Matrícula do Imóvel ou Inscrição Municipal.");
      return false;
    }

    if (empty($F("tipoOrigem"))) {
      alert("Campo Tipo de Origem é de preenchimento obrigatório.");
      return false;
    }

    if (empty($F("tipoDestino"))) {
      alert("Campo Tipo de Destino é de preenchimento obrigatório.");
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

  function limpaCampos(sElemento) {

    if (sElemento == "z01_numcgm" && !empty($F("z01_numcgm"))) {
      $("j01_matric").value = '';
      $("q02_inscr").value  = '';
    }

    if (sElemento == "j01_matric" && !empty($F("j01_matric"))) {
      $("z01_numcgm").value = "";
      $("q02_inscr").value  = "";
    }

    if (sElemento == "q02_inscr" && !empty($F("q02_inscr"))) {
      $("z01_numcgm").value = "";
      $("j01_matric").value = "";
    }
  }

  function carregaOpcoesSelect(sElemento) {

    tipoDebitoParcelamento = [];
    limpaCampos(sElemento);

    var iChavePesquisa = "";
    var iTipoPesquisa  = 0;

    if (!empty($F("z01_numcgm"))) {
      iChavePesquisa = $F("z01_numcgm");
      iTipoPesquisa  = 1;
    }

    if (!empty($F("j01_matric"))) {
      iChavePesquisa = $F("j01_matric");
      iTipoPesquisa  = 2;
    }

    if (!empty($F("q02_inscr"))) {
      iChavePesquisa = $F("q02_inscr");
      iTipoPesquisa  = 3;
    }

    oSelectTipoOrigem.disabled = true;
    oSelectTipoDestino.disabled = true;

    oSelectTipoOrigem.classList.add("readonly");
    oSelectTipoDestino.classList.add("readonly");

    oSelectTipoOrigem.innerHTML = "";
    oSelectTipoDestino.innerHTML = "";

    new AjaxRequest(
      "dvr3_importacaoiptu.RPC.php",
      {
        "sExec": "tiposDebitoParcial",
        "iChavePesquisa": iChavePesquisa,
        "iTipoPesquisa": iTipoPesquisa,
        "importaDividaAtiva": importaDividaAtiva,
      },
      function(oRetorno, lErro) {

        if (lErro === true) {

          alert(oRetorno.message.urlDecode());
          return;
        }

        oSelectTipoOrigem.disabled = false;
        oSelectTipoDestino.disabled = false;

        oSelectTipoOrigem.classList.remove("readonly");
        oSelectTipoDestino.classList.remove("readonly");

        oSelectTipoOrigem.length = 0;
        oSelectTipoOrigem.add(new Option("Selecione", ""));

        oSelectTipoDestino.length = 0;
        oSelectTipoDestino.add(new Option("Selecione", ""));
 
        // Fix para nao exibir o codigo 34 
        var opcao = document.createElement("option");
        opcao.value = 34;
        opcao.style.display = "none";
        oSelectTipoDestino.add(opcao);

        for (oTipoOrigem of oRetorno.aOpcoesTipoOrigem) {

          var oOpcaoSelectTipoOrigem       = document.createElement("option");
          oOpcaoSelectTipoOrigem.value     = oTipoOrigem.k00_tipo;
          oOpcaoSelectTipoOrigem.innerHTML = oTipoOrigem.k00_descr.urlDecode();
          oSelectTipoOrigem.appendChild(oOpcaoSelectTipoOrigem);

          if (Number(oTipoOrigem.k03_tipo) === 16) {
            tipoDebitoParcelamento.push(oTipoOrigem.k00_tipo);
          }

        }

        for (oTipoDestino of oRetorno.aOpcoesTipoDestino) {

          var oOpcaoSelectTipoDestino       = document.createElement("option");
          oOpcaoSelectTipoDestino.value     = oTipoDestino.k00_tipo;
          oOpcaoSelectTipoDestino.innerHTML = oTipoDestino.k00_descr.urlDecode();
          oSelectTipoDestino.appendChild(oOpcaoSelectTipoDestino);
        }
      }).execute();
  }

  function pesquisaDebitos() {

    if (importaDividaAtiva===true) {
      document.getElementById('tipoDestino').value=34;
    }

    if(!validaCampos()) {
      return false;
    }

    oImportacao = new DBViewImportacaoDiversos("oImportacao", "importacaoDebitosParaDiversos");
    oImportacao.setImportacaoParcial(true);
    oImportacao.setImportaDividaAtiva(importaDividaAtiva);
    oImportacao.setCallBackFunction(function () {
      window.location.reload();
    });

    var aChavesPesquisa = [];

    if ($F("q02_inscr")) {

      oImportacao.setTipoPesquisa(5); //inscricao
      aChavesPesquisa.push($F("q02_inscr"));

    } else if ($F("j01_matric")) {

      oImportacao.setTipoPesquisa(2); //matricula
      aChavesPesquisa.push($F("j01_matric"));

    } else if ($F("z01_numcgm")) {

      oImportacao.setTipoPesquisa(3); //CGM
      aChavesPesquisa.push($F("z01_numcgm"));
    }

    oImportacao.setChavePesquisa(aChavesPesquisa);
    oImportacao.setOrigemDebito($F("tipoOrigem"));
    oImportacao.setDestinoDebito($F("tipoDestino"));
    oImportacao.setProcedenciaTipoDebito(true);
    oImportacao.setUnificarDebitos($F('unificarDebitosNumpreReceita'));
    oImportacao.setTipoDataVencimento($F('dataVencimento'));

    oImportacao.show();
  }

  function verificaProcessoSistema() {

    if (!importaDividaAtiva) {

      $('ctnProcessosSistemas').style.display = 'none';
      $('trProcessoSistema').style.display = 'none';
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

  verificaProcessoSistema();
</script>
</body>
</html>
