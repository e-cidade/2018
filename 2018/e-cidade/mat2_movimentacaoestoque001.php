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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_utils.php"));

$iInstituicao  = db_getsession("DB_instit");

$oRotuloTipoGrupoVinculo = new rotulo("materialtipogrupovinculo");
$oRotuloTipoGrupoVinculo->label();
$oRotuloTipoGrupo = new rotulo("materialtipogrupo");
$oRotuloTipoGrupo->label();
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php

      db_app::load("estilos.css, grid.style.css");
      db_app::load("scripts.js, prototype.js, strings.js, arrays.js");
      db_app::load("widgets/windowAux.widget.js, widgets/dbtextField.widget.js");
      db_app::load("dbmessageBoard.widget.js, dbcomboBox.widget.js, datagrid.widget.js");
      db_app::load("widgets/DBLancador.widget.js, widgets/DBAncora.widget.js");
    ?>
    <style type="text/css">

    .inputdata {
      width:120px;
    }

    .select {
      width:150px;
    }

    .conta {
      display:"";
    }

    </style>

  </head>
  <body style="margin-top:30px;">
    <center>
      <fieldset style="width: 600px;">
        <legend><b>Relatório de Movimentação de Estoque</b></legend>
        <table style="width:550px">

          <tr>
            <td nowrap="nowrap" style="width: 80px">
              <b>Data Inicial:</b>
            </td>

            <td colspan="">
              <?php db_inputdata('dtInicial','','','',true,'text',1,"class='inputdata'");?>
            </td>
          </tr>

          <tr>
            <td nowrap="nowrap">
              <b>Data Final:</b>
            </td>

            <td>
              <?php db_inputdata('dtFinal','','','',true,'text',1,"class='inputdata'");?>
            </td>
          </tr>


          <tr>
            <td nowrap="nowrap">
              <b>Tipo de Agrupamento:</b>
            </td>
            <td>
              <?php
                $aTiposAgrupamento = array("0" => "Nenhum",
                                           "1" => "Grupo/SubGrupo",
                                           "2" => "Conta Despesa",
                                           "3" => "Conta Patrimonial");
                db_select("iAgrupamento", $aTiposAgrupamento, true, 1, "class='select'");
              ?>
            </td>
          </tr>

          <tr >
            <td nowrap="nowrap">
              <b>Tipo de Impressão:</b>
            </td>
            <td>
              <?php
                $aTiposImpressao = array("1" => "Analítica",
                                         "2" => "Sintética",
                                        );
                db_select("iTipoImpressao",$aTiposImpressao,true,1, "class='select' onchange='js_verificaTipoImpressao();'");
              ?>
            </td>
          </tr>

          <tr id="trOrdenacao">
            <td nowrap="nowrap">
              <b>Ordenação:</b>
            </td>
            <td>
              <?php
                $aOrdenacao = array("1" => "Código do Ítem",
                                    "2" => "Ordem alfabética"
                                   );
                db_select("iOrdem",$aOrdenacao,true,1, "class='select'");
              ?>
            </td>

            <tr class="conta">
              <td nowrap="nowrap"><?db_ancora('<b>Conta:</b>', 'js_pesquisaContaPCASP(true);', 1)?></td>
              <td nowrap="nowrap">
                <?php
                  db_input('c61_reduz', 17, @$Ic72_conplano, true, 'text', 1, " onchange='js_pesquisaContaPCASP(false);' ");
                  db_input('c60_descr', 26, @$Ic60_descrestrutcontabil, true, 'text', 3, "");
                ?>
              </td>
              <td>
                <input type="button" onClick="js_lancarConta()" value="Lançar" />
              </td>
            </tr>

            <tr class="conta">
              <td colspan=3>
                <fieldset style="width: 100%;">
                  <legend id="legendGridContas"></legend>
                  <div id="ctnGridContas"></div>
                </fieldset>
              </td>
            </tr>
        </table>
        <div id="divLancadorAlmoxarifado">
        </div>
      </fieldset>
      <input type="button" name="btnProcessar" id ="btnProcessar" value="Processar" onclick="js_processar();" >
    </center>
  </body>
</html>

<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script type="text/javascript">

var aContas               = new Array();
var oGridContas           = js_montaGrid();
var oLancadorAlmoxarifado = null;



/**
 * Cria o lançador para os almoxarifados
 */
function js_criarLancadorAlmoxarifado() {

	oLancadorAlmoxarifado = new DBLancador("oLancadorAlmoxarifado");
	oLancadorAlmoxarifado.setNomeInstancia("oLancadorAlmoxarifado");
	oLancadorAlmoxarifado.setLabelAncora("Almoxarifado: ");
	oLancadorAlmoxarifado.setTextoFieldset("Almoxarifados Selecionados");
	oLancadorAlmoxarifado.setParametrosPesquisa("func_db_almox.php", ['m91_codigo', 'descrdepto'], "sDescricaoDepartamento=true");
	oLancadorAlmoxarifado.setGridHeight("400px");
	oLancadorAlmoxarifado.show($("divLancadorAlmoxarifado"));
}




function js_verificaTipoImpressao() {
  var iImpressao = $F("iTipoImpressao");

  if(iImpressao == 2) {
    $("trOrdenacao").style.display = "none";
    return;
  }

  $("trOrdenacao").style.display = "";
}

/**
 * Funções para busca da conta patrimonial
 */
function js_pesquisaContaPCASP(lMostra) {

  var sFuncao = 'func_conplano_pesquisareduz.php?funcao_js=parent.js_mostraContaPcasp|c61_reduz|c60_descr';

  if (lMostra == false) {

    var iConta = $F('c61_reduz');
    sFuncao = 'func_conplano_pesquisareduz.php?pesquisa_chave='+iConta+'&funcao_js=parent.js_completaContaPCASP';
  }

  js_OpenJanelaIframe('CurrentWindow.corpo', 'db_iframe_contaPcasp', sFuncao,'Pesquisar', lMostra, '10');
}

function js_completaContaPCASP(sDescricao, lErro) {

  $('c60_descr').value = sDescricao;

  if (lErro) {
    $('c61_reduz').value = '';
  }
}

function js_mostraContaPcasp (iCodigo, sDescricao) {

  $('c61_reduz').value = iCodigo;
  $('c60_descr').value = sDescricao;
  db_iframe_contaPcasp.hide();

}


function js_processar() {

  var oDataInicial = $('dtInicial');
  var oDataFinal   = $('dtFinal');
  if (oDataInicial.value == '') {
    return alert('Campo Data Inicial é de preenchimento obrigatório.');
  }
  if (oDataFinal.value == '') {
    return alert('Campo Data Final é de preenchimento obrigatório.');
  }

  var aAlmoxarifadosSelecionados = oLancadorAlmoxarifado.getRegistros();
  if (aAlmoxarifadosSelecionados.length == 0) {
    if (!confirm("Não foi selecionado nenhum almoxarifado, deseja continuar com o processamento?")) {
      return false;
    }
  }

  var sAlmoxarifados = "";
  var sVirgula       = "";
  aAlmoxarifadosSelecionados.each(function (oAlmoxarifado, iIndice) {
    sAlmoxarifados += sVirgula+oAlmoxarifado.sCodigo;
    sVirgula = ",";
  });



  var aReduzidoContas   = new Array();
  aContas.each(function (oConta, iIndice){
    aReduzidoContas.push(oConta.iConta);
  });

  var sContas           = aReduzidoContas.join();
  var dtInicial         = $F("dtInicial");
  var dtFinal           = $F("dtFinal");

  dtInicial             = js_formatar(dtInicial, "d");
  dtFinal               = js_formatar(dtFinal, "d");
  var iTipoImpressao    = $F("iTipoImpressao");
  var iAgrupamento      = $F("iAgrupamento");
  var iOrdenacao        = $F("iOrdem");

  var sQuery  = "sContas=" + sContas;
      sQuery += "&dtInicial="+dtInicial;
      sQuery += "&dtFinal="+dtFinal;
      sQuery += "&iAgrupamento="+iAgrupamento;
      sQuery += "&iTipoImpressao="+iTipoImpressao;
      sQuery += "&iOrdenacao="+iOrdenacao;
      sQuery += "&sAlmoxarifados="+sAlmoxarifados;

  oJanela = window.open('mat2_movimentacaoestoque002.php?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  oJanela.moveTo(0,0);

}

/**
 * Monta grid
 */
function js_montaGrid() {

  var aAlinhamentos = new Array();
  var aHeader       = new Array();
  var aWidth        = new Array();

  aHeader[0]       = 'Reduz';
  aHeader[1]       = 'Descrição';
  aHeader[2]       = 'Remover';

  aWidth[0]        = '10%';
  aWidth[1]        = '75%';
  aWidth[2]        = '15%';

  aAlinhamentos[0] = 'left';
  aAlinhamentos[1] = 'left';
  aAlinhamentos[2] = 'center';

  oGridContas              = new DBGrid('datagridContas');
  oGridContas.sName        = 'datagridContas';
  oGridContas.nameInstance = 'oGridContas';
  oGridContas.setCellWidth( aWidth );
  oGridContas.setCellAlign( aAlinhamentos );
  oGridContas.setHeader( aHeader );
  oGridContas.allowSelectColumns(true);
  oGridContas.show( $('ctnGridContas') );
  oGridContas.clearAll(true);
  return oGridContas;
}


function js_lancarConta() {

  var sDescricaoConta = $F('c60_descr');

  if ( sDescricaoConta == '' ) {
    return false;
  }

  oConta = new Object();
  oConta.iConta          = $F('c61_reduz');
  oConta.sDescricaoConta = sDescricaoConta;
  oConta.iIndice         = aContas.length;

  aContas.push(oConta);
  renderizarGrid(aContas);
}

function js_removeContaLancada(iIndice) {

  aContas.splice(iIndice, 1);
  renderizarGrid (aContas);
}

function renderizarGrid (aContas) {

    oGridContas.clearAll(true);

    for ( var iIndice = 0; iIndice < aContas.length; iIndice++ ) {

      oConta = aContas[iIndice];

      var aLinha = new Array();

      aLinha[0] = oConta.iConta;
      aLinha[1] = oConta.sDescricaoConta;

      sDisabled = '';

      aLinha[2] = '<input type="button" value="Remover" onclick="js_removeContaLancada(' + iIndice + ')" ' + sDisabled + ' />';

      oGridContas.addRow(aLinha, null, null, true);
    }

    oGridContas.renderRows();
  }

function js_verificaGrid () {

  var iIndex = $('iAgrupamento').selectedIndex;

  if (iIndex <= 1) {

    $$('.conta').each(function (oObjeto, iIndice){
        oObjeto.setAttribute("style", "display:none");
      });

  } else {

    $$('.conta').each(function (oObjeto, iIndice){
        oObjeto.setAttribute("style", "display:''");
      });
  }

  $("legendGridContas").innerHTML = $("iAgrupamento").options[iIndex].innerHTML;
}


/*
 * funcao para validar datas maiores e menores
 */
function js_comparaDatas(dtInicial, dtFinal) {

  if (dtInicial != "" && dtFinal != "") {

    var iAnoInicial = dtInicial.split("/")[2];
    var iAnoFinal   = dtFinal.split("/")[2];
    var dtInicial   = parseInt(dtInicial.split("/")[2].toString() + dtInicial.split("/")[1].toString() + dtInicial.split("/")[0].toString());
    var dtFinal     = parseInt(dtFinal.split("/")[2].toString() + dtFinal.split("/")[1].toString() + dtFinal.split("/")[0].toString());

    if (iAnoInicial != iAnoFinal) {
      alert("O ano das datas dieferem. Selecione um mesmo período anual.");
      return false;
    }

    if (dtFinal > dtInicial) {
      return true;
    }

    if (dtInicial == dtFinal) {

      alert('As datas de períodos inicial e final devem ser diferentes');
      return false;
    }

    if (dtInicial > dtFinal) {

      alert("Data período final é menor que a data período inicial.");
      return false;
    }


  } else {

    alert("Preencha um período.");
    return false;
  }

  return true;

}
$('iAgrupamento').setAttribute("onchange", "js_verificaGrid()");
js_verificaGrid ();
js_verificaTipoImpressao();
js_criarLancadorAlmoxarifado();
</script>