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
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");

$oGet   = db_utils::postMemory($_GET);
$iOpcao = $oGet->opcao;

$oRotulo = new rotulocampo();
$oRotulo->label("ve13_sequencial");
$oRotulo->label("ve13_veiculo");
$oRotulo->label("ve13_motorista");
$oRotulo->label("ve13_datainicial");
$oRotulo->label("ve13_datafinal");
$oRotulo->label("ve13_observacao");

$lPessoal      = "";
$oDaoVeicParam = new cl_veicparam();

$sCamposVeicParam = " ve50_integrapessoal ";
$sWhereVeicParam  = " ve50_instit = " . db_getsession("DB_instit");
$sSqlVeicParam    = $oDaoVeicParam->sql_query_file(null, $sCamposVeicParam, null, $sWhereVeicParam);
$rsVeicParam      = $oDaoVeicParam->sql_record($sSqlVeicParam);
if ($oDaoVeicParam->numrows > 0) {

  $oVeicParam = db_utils::fieldsMemory($rsVeicParam, 0);
  if ($oVeicParam->ve50_integrapessoal == 1) {
    $lPessoal = "true";
  }

  if ($oVeicParam->ve50_integrapessoal == 2) {
    $lPessoal = "false";
  }
} else {
  db_msgbox("Parâmetros não configurados. Verifique.");
}
?>
<html xmlns="http://www.w3.org/1999/html">
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
<div class="container">
  <form id="form1" name="form1">
    <input type="hidden" id="iOpcao" name="iOpcao" value="<?=$iOpcao?>">
    <fieldset>
      <legend>Autorização para Circulação de Veículo</legend>
      <table>
        <tr>
          <td><label class="bold" for=""><?=$Lve13_sequencial?></label></td>
          <td>
            <?php
            db_input("ve13_sequencial", 10, $Ive13_sequencial, true, 'text', 3, "");
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label class="bold" for="">
              <?php db_ancora($Lve13_veiculo, "buscarVeiculo(true);", $iOpcao); ?>
            </label>
          </td>
          <td>
            <?php
            db_input("ve13_veiculo", 10, $Ive13_veiculo, true, "text", $iOpcao, 'onChange="buscarVeiculo(false);"');
            db_input("descricao_veiculo", 10, 0, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td>
            <label class="bold" for="">
              <?php db_ancora($Lve13_motorista, "buscarMotorista(true);", $iOpcao); ?>
            </label>
          </td>
          <td>
            <?php
            db_input("ve13_motorista", 10, $Ive13_motorista, true, "text", $iOpcao, 'onChange="buscarMotorista(false);"');
            db_input("descricao_motorista", 45, 0, true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td><label class="bold" for=""><?=$Lve13_datainicial?></label></td>
          <td>
            <?php db_inputdata("ve13_datainicial", "", "", "", true, 'text', $iOpcao); ?>
          </td>
        </tr>
        <tr>
          <td><label class="bold" for=""><?=$Lve13_datafinal?></label></td>
          <td>
            <?php db_inputdata("ve13_datafinal", "", "", "", true, 'text', $iOpcao); ?>
          </td>
        </tr>
        <tr>
          <td colspan="2">
            <fieldset>
              <legend><?=$Lve13_observacao?></legend>
              <?php db_textarea("ve13_observacao", 3, 64, $Ive13_observacao, true, 'text', $iOpcao); ?>
            </fieldset>
          </td>
        </tr>
      </table>
    </fieldset>
    <input id="btnSalvar" type="button" value="Salvar" onclick="salvar()">
    <?php if ($iOpcao != 1) { ?>
    <input id="btnPesquisar" type="button" value="Pesquisar" onclick="buscarAutorizacao()">
    <input id="btnEmitir" type="button" value="Emitir" onclick="emitir()">
    <?php } ?>
  </form>
</div>
<?php db_menu(); ?>
<script>

  var oCodigoAutorizacao  = $('ve13_sequencial');
  var oCodigoVeiculo      = $('ve13_veiculo');
  var oDescricaoVeiculo   = $('descricao_veiculo');
  var oCodigoMotorista    = $('ve13_motorista');
  var oDescricaoMotorista = $('descricao_motorista');
  var oDataInicial        = $('ve13_datainicial');
  var oDataFinal          = $('ve13_datafinal');
  var oObservacao         = $('ve13_observacao');
  var oFormulario         = $('form1');
  var oOpcao              = $('iOpcao');
  var oBtnSalvar          = $('btnSalvar');
  var oBtnEmitir          = $('btnEmitir');
  oFormulario.reset();

  /**
   * Função para salvar as informações do formulário.
   */
  function salvar() {

    if (oCodigoVeiculo.value == "") {

      alert("O campo Veículo é de preenchimento obrigatório.");
      return false;
    }

    if (oCodigoMotorista.value == "") {

      alert("O campo Motorista é de preenchimento obrigatório.");
      return false;
    }

    if (oDataInicial.value == "") {

      alert("O campo Data Inicial é de preenchimento obrigatório.");
      return false;
    }

    if (oDataFinal.value == "") {

      alert("O campo Data Final é de preenchimento obrigatório.");
      return false;
    }

    if (js_comparadata(oDataInicial.value, oDataFinal.value, ">")) {

      alert("A Data Final deve ser maior ou igual a Data Inicial.");
      return false;
    }

    var sArquivo    = "vei4_autorizacaocirculacaoveiculo.RPC.php";
    var oParametros = {
      exec               : 'salvar',
      iCodigoAutorizacao : oCodigoAutorizacao.value,
      iCodigoVeiculo     : oCodigoVeiculo.value,
      iCodigoMotorista   : oCodigoMotorista.value,
      sDataInicial       : oDataInicial.value,
      sDataFinal         : oDataFinal.value,
      sObservacao        : encodeURIComponent(tagString((oObservacao.value)))
    };
    var fnRetorno   = retornoSalvar;
    new AjaxRequest(sArquivo, oParametros, fnRetorno).setMessage("Salvando autorizaçao, aguarde...").execute();
  }

  /**
   * Função de retorno após salvar uma autorização de circulação de veículo.
   * @param {object}  oRetorno Objeto com as informações de retorno.
   * @param {boolean} lErro    Se houve erro na requisição.
   */
  function retornoSalvar(oRetorno, lErro) {

    if (lErro) {

      alert("Não foi possível salvar a Autorização de Circulação de Veículo.");
      return false;
    }

    if (oRetorno.erro) {

      alert(oRetorno.mensagem.urlDecode());
      return false;
    }

    if (confirm("Autorização de Circulação de Veículo salva com sucesso.\nGostaria de emitir o documento?")) {
      emitirDocumento(oRetorno.iCodigoAutorizacao);
    }

    oFormulario.reset();
    oBtnSalvar.disabled = false;
    if (oOpcao.value != 1) {

      oBtnSalvar.disabled = true;
      oBtnEmitir.disabled = true;
      buscarAutorizacao(true);
    }
  }

  /**
   * Chamada da emissão do documento pelo botão.
   */
  function emitir() {
    emitirDocumento(oCodigoAutorizacao.value);
  }

  /**
   * Faz a emissão do documento de autorização de circulação de veículo.
   * @param {int} iCodigoAutorizacao Código da autorização de circulação de veículos para emissão.
   */
  function emitirDocumento(iCodigoAutorizacao) {

    if (iCodigoAutorizacao == "") {

      alert("Autorização de Circulação de Veículo não informada.");
      return false;
    }

    var sUrl = "vei4_emiteautorizacaocirculacaoveiculo.php?iCodigoAutorizacao=" + iCodigoAutorizacao;
    jan = window.open(sUrl,'','width=' + (screen.availWidth - 5 ) + ',height=' + (screen.availHeight - 40) + ',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }

  /**
   * Busca de autorização de circulação de veículo.
   */
  function buscarAutorizacao() {

    var sArquivo     = "func_autorizacaocirculacaoveiculos.php";
    var sTituloTela  = "Pesquisar Autorização de Circulação de Veículo";
    var sQueryString = "funcao_js=parent.retornoAutorizacao|ve13_sequencial";
    js_OpenJanelaIframe('', 'db_iframe_autorizacaocirculacaoveiculo', sArquivo + '?' + sQueryString, sTituloTela, true);
  }

  /**
   * Função de retorno da busca de autorização de circulação de veículos.
   * @param {int} iCodigo Código da autorização selecionada.
   */
  function retornoAutorizacao(iCodigo) {

    oCodigoAutorizacao.value = iCodigo;
    carregaAutorizacao();
    db_iframe_autorizacaocirculacaoveiculo.hide();
  }

  /**
   * Carrega as informações da autorização de circulação de veículo selecionada.
   */
  function carregaAutorizacao() {

    if (oCodigoAutorizacao.value == "") {
      return false;
    }

    var sArquivo    = "vei4_autorizacaocirculacaoveiculo.RPC.php";
    var oParametros = {
      exec               : 'buscar',
      iCodigoAutorizacao : oCodigoAutorizacao.value
    };
    var fnRetorno   = preencheAutorizacao;
    new AjaxRequest(sArquivo, oParametros, fnRetorno).setMessage("Carregando autorização, aguarde...").execute();
  }

  /**
   * Função para preenchar os campos do formulário após a busca.
   * @param {object}  oRetorno Objeto retorno com as informações retornadas.
   * @param {boolean} lErro    Se houve erro na requisição.
   */
  function preencheAutorizacao(oRetorno, lErro) {

    if (lErro)  {

      alert("Houve um problema na busca pela Autorização de Circulação de Veículo");
      return false;
    }

    if (oRetorno.erro) {

      alert(oRetorno.mensagem.urlDecode());
      return false;
    }

    oCodigoMotorista.value    = oRetorno.iMotorista;
    oDescricaoMotorista.value = oRetorno.sMotorista.urlDecode();
    oCodigoVeiculo.value      = oRetorno.iVeiculo;
    oDescricaoVeiculo.value   = oRetorno.sVeiculo.urlDecode();
    oDataInicial.value        = oRetorno.sDataInicial;
    oDataFinal.value          = oRetorno.sDataFinal;
    oObservacao.value         = oRetorno.sObservacao.urlDecode();
    oBtnSalvar.disabled       = false;
    oBtnEmitir.disabled       = false;
  }

  /**
   * Função para busca de veículos.
   * @param {boolean} lMostrar Se deve buscar visualmente abrindo a tela ou fazer a busca em background.
   */
  function buscarVeiculo(lMostrar) {

    var sArquivo     = "func_veiculosalt.php";
    var sTituloTela  = "Pesquisar Veículos";
    var sQueryString = "funcao_js=parent.retornoVeiculos|ve01_codigo|ve01_placa";

    if (!lMostrar) {
      sQueryString = 'pesquisa_chave=' + oCodigoVeiculo.value + '&funcao_js=parent.retornoVeiculosChave';
    }

    js_OpenJanelaIframe('', 'db_iframe_veiculos', sArquivo + '?' + sQueryString, sTituloTela, lMostrar);
  }

  /**
   * Função de retorno para a busca de veículos ao clicar na âncora.
   * @param {int}    iCodigo Código do veículo selecionado.
   * @param {string} sPlaca  Placa do veículo selecionado.
   */
  function retornoVeiculos(iCodigo, sPlaca) {

    oCodigoVeiculo.value    = iCodigo;
    oDescricaoVeiculo.value = sPlaca;
    db_iframe_veiculos.hide();
  }

  /**
   * Função de retorno para busca de veículo digitando na âncora.
   * @param {string } sPlaca Placa do veículo encontrado.
   * @param {boolean} lErro  Caso não tenha encontrado registro para o código dado.
   */
  function retornoVeiculosChave(sPlaca, lErro) {

    var iCodigo = oCodigoVeiculo.value;
    if (lErro) {
      iCodigo = '';
    }
    retornoVeiculos(iCodigo, sPlaca);
  }

  /**
   * Função para buscar motoristas.
   * @param {boolean} lMostrar Se deve buscar visualmente abrindo a tela ou fazer a busca em background.
   */
  function buscarMotorista(lMostrar) {

    var sArquivo     = "func_veicmotoristasalt.php";
    var sTituloTela  = "Pesquisar Motoristas";
    var sQueryString = "pessoal=<?=$lPessoal?>&funcao_js=parent.retornoMotorista|ve05_codigo|z01_nome";

    if (!lMostrar) {
      sQueryString = 'pesquisa_chave=' + oCodigoMotorista.value + '&pessoal=<?=$lPessoal?>&funcao_js=parent.retornoMotoristaChave';
    }

    js_OpenJanelaIframe('', 'db_iframe_veicmotoristas', sArquivo + '?' + sQueryString, sTituloTela, lMostrar);
  }

  /**
   * Função de retorno para a busca de motorista ao clicar na âncora.
   * @param {int}    iCodigo Código do motorista.
   * @param {string} sNome   Nome do motorista.
   */
  function retornoMotorista(iCodigo, sNome) {

    oCodigoMotorista.value    = iCodigo;
    oDescricaoMotorista.value = sNome;
    db_iframe_veicmotoristas.hide();
  }

  /**
   * Função de retorno para busca de motorista digitando na âncora.
   * @param {string}  sNome Nome do motorista.
   * @param {boolean} lErro Caso não tenha encontrado registro para o código dado.
   */
  function retornoMotoristaChave(sNome, lErro) {

    var iCodigo = oCodigoMotorista.value;
    if (lErro) {
      iCodigo = '';
    }
    retornoMotorista(iCodigo, sNome);
  }

  if (oOpcao.value != 1) {

    oBtnSalvar.disabled = true;
    oBtnEmitir.disabled = true;
    buscarAutorizacao();
  }
</script>
</body>
</html>