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
require_once(modification("libs/db_utils.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("classes/db_acordo_classe.php"));

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clacordo = new cl_acordo;
$clrotulo = new rotulocampo;

$clacordo->rotulo->label();
$clrotulo->label("ac16_sequencial");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBLookUp.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/AjaxRequest.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInput.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/dbmessageBoard.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputInteger.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputDate.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/widgets/Input/DBInputValor.widget.js"></script>
  <script language="JavaScript" type="text/javascript" src="ext/javascript/prototype.maskedinput.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
<style>
td {
  white-space: nowrap;
}
</style>
</head>
<body>
<div class="container">
  <table border="0" align="center" cellspacing="0" cellpadding="0" style="padding-top:40px;">
    <tr>
      <td valign="top" align="center">
        <fieldset>
          <legend><b>Programação do Regime de Competência</b></legend>
          <table align="center" border="0">
            <tr>
              <td title="<?=$Tac16_sequencial?>" align="left">
                <label id="pesquisar" for="ac16_sequencial">
                  <?php db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);",1); ?>
                </label>
              </td>
              <td align="left">
                <?
                  db_input('ac16_sequencial', 10, $Iac16_sequencial, true,
                           'text', 1, " onchange='js_pesquisaac16_sequencial(false);'");
                ?>
              </td>
              <td align="left">
                <?
                  db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto, true, 'text', 3);
                ?>
              </td>
            </tr>
          </table>
        </fieldset>
      </td>
    </tr>
  </table>
  <input type="button" value="Pesquisar" id="btnPesquisar">
</div>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>

const URL_RPC                      = 'con4_programacaoregimecompetencia.RPC.php';
$('ac16_sequencial').style.width   = "100%";
$('ac16_resumoobjeto').style.width = "100%";

/**
 * Pesquisa acordos
 */
var oInputAcordo = $('ac16_sequencial');
function js_pesquisaac16_sequencial(lMostrar) {

  if (lMostrar == true) {

    var sUrl = 'func_acordo.php?iTipoFiltro=4&funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto';
    oJanela = js_OpenJanelaIframe('CurrentWindow.corpo',
                        'db_iframe_acordo',
                        sUrl,
                        'Pesquisar Acordo',
                        true, 25);

  } else {

    if ($('ac16_sequencial').value != '') {

      var sUrl = 'func_acordo.php?descricao=true&pesquisa_chave='+$('ac16_sequencial').value+
                 '&iTipoFiltro=4'+
                 '&funcao_js=parent.js_mostraacordo';

      js_OpenJanelaIframe('CurrentWindow.corpo',
                          'db_iframe_acordo',
                          sUrl,
                          'Pesquisar Acordo',
                          false);
     } else {
       $('ac16_sequencial').value   = '';
       $('ac16_resumoobjeto').value = '';
     }
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo(chave1,chave2,erro) {

  if (erro == true) {

    $('ac16_sequencial').value   = '';
    $('ac16_resumoobjeto').value = chave1;
    $('ac16_sequencial').focus();
    return false;
  } else {

    $('ac16_sequencial').value   = chave1;
    $('ac16_resumoobjeto').value = chave2;

  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo1(chave1,chave2) {

  $('ac16_sequencial').value    = chave1;
  $('ac16_resumoobjeto').value  = chave2;
  db_iframe_acordo.hide();

}
function acordoProgramacaoFinanceira() {

  /**
   * Monda windowAuxiliar
   */
  windowProgramacaoFinanceira = new windowAux('wndProgramacaoRegime', 'Regime de Competência', 800);

  var sContent  = '<div>';
  sContent     += ' <fieldset>';
  sContent     += '  <legend>';
  sContent     += '    <b>Dados do Acordo</b>';
  sContent     += '  </legend>';
  sContent     += ' <table border="0">';
  sContent     += '   <tr>';
  sContent     += '    <td>';
  sContent     += '     <label for="cboTipoProgramacao"><b>Despesa Antecipada:</b></label>';
  sContent     += '    </td>';
  sContent     += '    <td>';
  sContent     += '      <select id="cboTipoProgramacao" onchange="alteraprogramacao(this.value);">';
  sContent     += '      <option value="2">Não</option>';
  sContent     += '      <option value="1">Sim</option>';
  sContent     += '   </select>'
  sContent     += '    </td>';
  sContent     += '   </tr>';
  sContent     += '    <td>';
  sContent     += '     <label id="lblConta" for="contacontabil"><b>Conta:</b></label>';
  sContent     += '    </td>';
  sContent     += '    <td>';
  sContent     += '      <input type="text" id="contacontabil"  data="c60_codcon" class="field-size2" >';
  sContent     += '      <input type="text" id="descricaoconta" data="c60_descr"  readonly class="readonly field-size6">';
  sContent     += '    </td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += ' </fieldset>';
  sContent     += ' <fieldset>';
  sContent     += '   <legend>Dados da Programação</legend>';
  sContent     += ' <table>';
  sContent     += '   <tr>';
  sContent     += '     <td><label for="txtValor"><b>Valor Total do Acordo:</b></label></td>';
  sContent     += '     <td>';
  sContent     += '      <input type="text" id="txtValor" readonly disabled class="field-size4 readonly">';
  sContent     +=     '</td>';
  sContent     += '     <td><label for="txtSaldoProgramar"><b>Saldo a Programar:</b></label></td>';
  sContent     += '     <td>';
  sContent     += '      <input type="text" id="txtSaldoProgramar" readonly disabled class="field-size4 readonly">';
  sContent     +=     '</td>'
  sContent     += '   </tr>';
  sContent     += '   <tr>';
  sContent     += '     <td><label for="txtNumeroParcelas"><b>Número de Parcelas:</b></label></td>';
  sContent     += '     <td>';
  sContent     += '      <input type="text" id="txtNumeroParcelas" class="field-size4">';
  sContent     +=     '</td>';
  sContent     += '   </tr>';
  sContent     += '   <tr>';
  sContent     += '     <td><b><label for="CboMesInicial">Mês Inicial:</b></label></td>';
  sContent     += '     <td>';
  sContent     += '     <select id="CboMesInicial">';
  sContent     += '       <option value="1">Janeiro</option>';
  sContent     += '       <option value="2">Fevereiro</option>';
  sContent     += '       <option value="3">Março</option>';
  sContent     += '       <option value="4">Abril</option>';
  sContent     += '       <option value="5">Maio</option>';
  sContent     += '       <option value="6">Junho</option>';
  sContent     += '       <option value="7">Julho</option>';
  sContent     += '       <option value="8">Agosto</option>';
  sContent     += '       <option value="9">Setembro</option>';
  sContent     += '       <option value="10">Outubro</option>';
  sContent     += '       <option value="11">Novembro</option>';
  sContent     += '       <option value="12">Dezembro</option>';
  sContent     += '     </select>';
  sContent     +  '    </td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += ' </fieldset>';
  sContent     += ' <div class="container">';
  sContent     += '   <input type="button" id="btnProcessar" value="Processar" onclick="processarParcelas()">';
  sContent     += ' </div>';
  sContent     += ' <fieldset>';
  sContent     += ' <legend>Parcelas</legend>';
  sContent     += ' <div id="ctnGridParcelas"></div>';
  sContent     += ' </fieldset>';
  sContent     += ' <table border="0" align="center" cellpadding="3">';
  sContent     += '   <tr align="center">';
  sContent     += '     <td><input type="button" id="btnNovaParcela" value="Nova Parcela" onclick="incluirNovaParcela()"></td>';
  sContent     += '     <td><input type="button" id="btnSalvar" value="Salvar" onclick="salvar()"></td>';
  sContent     += '     <td><input type="button" id="btnExcluirParcela" value="Excluir" onclick="excluirParcela()"></td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += '</div>';

  windowProgramacaoFinanceira.setContent(sContent);
  windowProgramacaoFinanceira.setShutDownFunction(function () {
    windowProgramacaoFinanceira.destroy();
  });
  windowProgramacaoFinanceira.show();
  var oInputParcelas    = new DBInputInteger($('txtNumeroParcelas'));
  var oInputCodigoConta = new DBInputInteger($('contacontabil'));
  var oInputCodigoConta = new DBInput($('descricaoconta'));
  var sTipoProgramacao  = $('cboTipoProgramacao').value;


  oLookupPlano  = new DBLookUp($('lblConta'), $('contacontabil'), $('descricaoconta'), {
    "sArquivo"                : "func_conplano_pesquisareduz.php",
    "sObjetoLookUp"           : "db_iframe_conta",
    "sLabel"                  : "Pesquisar Contas",
    "sQueryString"            : "&lPesquisaCodigo=true&regimeCompetencia=true",
    "zIndex"                  : 9999999,
    "aParametrosAdicionais"   : ["tipoProgramacao="+sTipoProgramacao]
  });

  oMessageBoard = new DBMessageBoard('msgBoardDadosContrato',
    'Acordo:'+$F('ac16_sequencial')+ " - "+$F('ac16_resumoobjeto'),
    'Realizar a programação da competência do itens do acordo',
    windowProgramacaoFinanceira.getContentContainer()
  );
  oMessageBoard.show();
  oDataGridParcelas              = new DBGrid('gridParcelas');
  oDataGridParcelas.nameInstance = 'oDataGridParcelas';
  oDataGridParcelas.setCheckbox(0)  ;
  oDataGridParcelas.setHeight(300);
  oDataGridParcelas.setCellWidth(['10%', '10%', '40%', '40%']);
  oDataGridParcelas.setCellAlign(['left', 'center', 'center', 'center']);
  oDataGridParcelas.setHeader(['Codigo', 'Parcela','Competência', 'Valor']);
  oDataGridParcelas.aHeaders[1].lDisplayed = false;
  oDataGridParcelas.show($('ctnGridParcelas'));
}

/**
 * Window adicionar nova parcela
 */
incluirNovaParcela = function () {

  if ($('wndIncluirParcela')) {
    return false;
  }

  /**
   * Monta window auxiliar para incluir nova parcela
   */
  windowIncluirParcela = new windowAux('wndIncluirParcela', 'Incluir Parcela', 300, 200);
  var sContent  = '<div style="">';
  sContent     += ' <fieldset>';
  sContent     += ' <table border="0">';
  sContent     += '   <tr>';
  sContent     += '     <td><b>Competência:</b></td>';
  sContent     += '     <td id="ctnCompetencia"><input type="text" value="" id="txtCompetencia" class="field-size3"></td>';
  sContent     += '   </tr>';
  sContent     += '   <tr>';
  sContent     += '     <td><b>Valor:</b></td>';
  sContent     += '     <td><input type="text" value="" id="txtValorParcela"></td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += ' </fieldset>';
  sContent     += ' <table border="0" align="center" cellpadding="3">';
  sContent     += '   <tr align="center">';
  sContent     += '     <td><input type="button" id="btnSalvarParcela" value="Salvar"></td>';
  sContent     += '   </tr>';
  sContent     += ' </table>';
  sContent     += '</div>';
  windowIncluirParcela.setContent(sContent);

  windowIncluirParcela.setShutDownFunction(function (){
    windowIncluirParcela.destroy();
  });
 windowIncluirParcela.setChildOf(windowProgramacaoFinanceira);
 var oInputData       = new MaskedInput($('txtCompetencia'), '99/9999', {placeholder:' '});
 var oTxtValorParcela = new DBInputValor($('txtValorParcela'));


  oMessageBoard = new DBMessageBoard('msgBoardIncluirParcela',
    'Nova Parcela',
    'Informe o número da parcela, e sua data de <br>pagamento.',
    $('windowwndIncluirParcela_content')
  );
  oMessageBoard.show();

  windowIncluirParcela.show(50, 0, true);

  $('btnSalvarParcela').observe('click', function() {

    var oCompetencia = $('txtCompetencia');
    var oValor       = $('txtValorParcela');
    if (empty(oCompetencia.value)) {

      alert('Competência deve ser informada.');
      return;
    }
    var aPartesCompetencia = oCompetencia.value.split("/");
    if (aPartesCompetencia[0].trim() < 1 || aPartesCompetencia[0].trim() > 12) {

      alert('A competência informada é inválida.');
      return;
    }

    if (empty(oValor.value)) {
      alert('Informe um valor para a parcela.');
      return;
    }
    var saldoProgramar = $F('txtSaldoProgramar').getNumber().valueOf()
    if (saldoProgramar < oValor.value.getNumber().valueOf()) {

      alert('Valor da Parcela é maior que o saldo a programar (R$ '+$F('txtSaldoProgramar')+").");
      return false;
    }
    var aParcelas = oDataGridParcelas.aRows;
    numeroParcela = 0;
    for (parcela of aParcelas) {

      if (parcela.aCells[3].getValue() == oCompetencia.value) {

        alert('Competência'+oCompetencia.value+' já cadastrada.');
        return false;
      }
      var numero = parcela.aCells[2].getValue();
      if (numero.trim() == '-') {
        numero = 0;
      }
      numeroParcela = new Number(numero)+1;
    }

    if (empty(oInputAcordo.value)) {

      alert('Acordo deve ser informado.');
      return false;
    }

    var oParametro = {

      exec   : 'adicionarParcela',
      acordo : $F('ac16_sequencial'),
      parcela: {
        codigo: '',
        competencia : oCompetencia.value,
        numero      : numeroParcela,
        valor       : oValor.value.getNumber().valueOf()
      }
    }

    new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {

      alert(oResponse.message);
      if (lErro) {
        return;
      }
      windowIncluirParcela.destroy();
      getParcelas();
    }).setMessage('Aguarde, adicionado nova parcela...').execute();

  });
}

/**
 *
 * Retorna os dados do acordo
 */
function getDadosDoAcordo() {

  var oInputAcordo = $('ac16_sequencial');
  if (empty(oInputAcordo.value)) {

    alert('Acordo deve ser informado.');
    return false;
  }

  var oParametro = {

    exec   : 'getDadosAcordo',
    acordo : $F('ac16_sequencial')

  }

  new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {

    if (lErro) {
      return;
    }
    acordoProgramacaoFinanceira();
    preencherGridParcelas(oResponse.parcelas);
    oLookupPlano.habilitar();
    $('txtSaldoProgramar').value = js_formatar(oResponse.saldo_programar, 'f');
    $('cboTipoProgramacao').disabled = false;
    $('txtValor').value           = js_formatar(oResponse.valor_acordo, 'f');
    if ( oResponse.programacao != null) {
      $('cboTipoProgramacao').value = oResponse.programacao.tipo;
      $('contacontabil').value = oResponse.programacao.conta;
      $('descricaoconta').value = oResponse.programacao.descricao_conta;
    }

    // if (oResponse.parcelas_processadas) {
      // $('btnProcessar').disabled = true;
    // }

    if (oResponse.saldo_programar == 0) {
      $('btnProcessar').disabled = true;
    }


    if (oResponse.parcelas_reconhecidas) {

      oLookupPlano.desabilitar();
      $('cboTipoProgramacao').disabled = true;
    }

  }).setMessage('Aguarde, pesquisando dados do acordo...').execute();

}

/**
 * Realiza o processamento do valor das parcelas do item
 */
function processarParcelas() {

  var oInputAcordo = $('ac16_sequencial');
  if (empty(oInputAcordo.value)) {

    alert('Acordo deve ser informado.');
    return false;
  }

  if (empty($F('contacontabil'))) {

    alert('Conta deve ser informada.');
    return false;
  }

  if (empty($F('txtNumeroParcelas'))) {

    alert('Numero Parcelas deve ser Informado.');
    return false;
  }

  var oParametro = {

    exec            : 'processar',
    acordo          : $F('ac16_sequencial'),
    tipo            : $('cboTipoProgramacao').value,
    conta           : $('contacontabil').value,
    mes_inicial     : $F('CboMesInicial'),
    numero_parcelas : $F('txtNumeroParcelas'),
    valor           : $F('txtSaldoProgramar').getNumber().valueOf()
  }

  new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {

    alert(oResponse.message);
    oDataGridParcelas.clearAll(true);
    if (lErro) {
      return;
    }
    getParcelas();
  }).setMessage('Aguarde, processando parcelas do item...').execute();

}
/**
 *
 * @returns {boolean}
 */
function getParcelas() {

  $('btnProcessar').disabled = false;
  var oInputAcordo = $('ac16_sequencial');
  if (empty(oInputAcordo.value)) {

    alert('Acordo deve ser informado.');
    return false;
  }

  var oParametro = {

    exec   : 'getParcelas',
    acordo : $F('ac16_sequencial'),

   }

  new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {

    if (lErro) {
      return;
    }

    if (oResponse.parcelas.length > 0 && oResponse.saldo_programar == 0) {
      $('btnProcessar').disabled = true;
    }

    preencherGridParcelas(oResponse.parcelas);
    $('txtSaldoProgramar').value = js_formatar(oResponse.saldo_programar, 'f');
  }).setMessage('Aguarde, pesquisando dados do acordo...').execute();

}

/**
 * Popula a grid com as parcelas
 *
 */
function preencherGridParcelas(parcelas) {

  oDataGridParcelas.clearAll(true);
   for (parcela of parcelas) {

    var sCamposDesabilitado = parcela.reconhecida ? ' disabled ': '';
    if (parcela.numero == 0) {
      parcela.competencia = 'SALDO ANTERIOR';
      parcela.numero      = ' - ';
    }
    var linha = [
      parcela.codigo,
      parcela.numero,
      parcela.competencia,
      "<input type='text' class='valormensal' "+sCamposDesabilitado+" id='txtValor"+parcela.codigo+"' value='"+js_formatar(parcela.valor, 'f')+"' style='width:100%'>"
    ];

    oDataGridParcelas.addRow(linha, true, parcela.reconhecida);
  }

  oDataGridParcelas.renderRows();
  var inputs = $$('input.valormensal');
  for (inputValor of inputs) {
    new DBInputValor(inputValor);
  }
}

/**
 * Remove a parcela do itme
 */
function excluirParcela() {

  var aParcelas = oDataGridParcelas.getSelection('object');
  if (aParcelas.length == 0) {

    alert('Selecione ao menos uma Parcela.');
    return false;
  }

  var aListaParcelasExcluir = []
  for (parcela of aParcelas) {
    aListaParcelasExcluir.push(parcela.aCells[1].getValue());
  }

  if (!confirm('Confirma a exclusão das parcelas selecionadas?')) {
    return;
  }
  var oParametro = {

    exec     : 'excluirParcelas',
    acordo   : $F('ac16_sequencial'),
    parcelas : aListaParcelasExcluir,
  }

  new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {

    alert(oResponse.message);
    if (lErro) {
      return;
    }
    getParcelas();

   }).setMessage('Aguarde, removendo parcelas selecionadas...').execute();

}

/**
 * Salva os dados da parcela já processadas
 */
function salvar() {

  if (empty(oInputAcordo.value)) {

    alert('Acordo deve ser informado.');
    return false;
  }

  var aParcelas = oDataGridParcelas.aRows;
  if (aParcelas.length == 0) {

    alert('Não existem parcelas para serem salvas.');
    return false;
  }
  var valorTotal      = 0;
  var parcelasAlterar = [];
  for (linha of aParcelas) {

    var nValorParcela = linha.aCells[4].getValue().getNumber().valueOf();
    var parcela = {
       codigo      : linha.aCells[1].getValue(),
       numero      : linha.aCells[2].getValue(),
       competencia : linha.aCells[3].getValue(),
       valor       : nValorParcela
    };
    valorTotal   += js_round(nValorParcela, 2);
    if ($(linha.aCells[0].sId).childNodes[0].disabled) {
      continue;
    };
    parcelasAlterar.push(parcela);
  }

  if (js_round(valorTotal, 2)  != js_round($F('txtValor').getNumber().valueOf(), 2)) {

    alert('O valor das parcelas ('+js_formatar(valorTotal, 'f')+') está diferente do valor total do item ('+$F('txtValor')+').');
    return;
  }

  var oParametro = {

    exec     : 'salvarParcelas',
    acordo   : $F('ac16_sequencial'),
    conta    : $F('contacontabil'),
    tipo     : $F('cboTipoProgramacao'),
    parcelas : parcelasAlterar,
  }

  new AjaxRequest(URL_RPC, oParametro, function (oResponse, lErro) {

    alert(oResponse.message);
    if (lErro) {
      return;
    }
    getParcelas();

  }).setMessage('Aguarde, alterando parcelas do item ...').execute();

}

function alteraprogramacao() {

  var sTipoProgramacao  = $('cboTipoProgramacao').value;
  oLookupPlano.setParametrosAdicionais(["tipoProgramacao="+sTipoProgramacao]);
  $('contacontabil').value  = "";
  $('descricaoconta').value = "";
}

$('btnPesquisar').observe('click', getDadosDoAcordo);
js_pesquisaac16_sequencial(true);
</script>
</html>