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
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

$oGet                     = db_utils::postMemory($_GET);
$oRotuloInventario        = new rotulo("inventario");
$oRotuloDepartamento      = new rotulo("db_depart");
$oRotuloInstituicao       = new rotulo("db_config");
$oRotuloBens              = new rotulo("bens");
$oRotuloBensPlaca         = new rotulo("bensplaca");
$oRotuloConvenio          = new rotulo("benscadcedente");
$oRotuloClassificacaoBens = new rotulo("clabens");

$oRotuloInventario       ->label();
$oRotuloDepartamento     ->label();
$oRotuloInstituicao      ->label();
$oRotuloBens             ->label();
$oRotuloBensPlaca        ->label();
$oRotuloConvenio         ->label();
$oRotuloClassificacaoBens->label();
$oGet->db_opcao = 1;

$oParametro         = db_utils::getDao("cfpatri");
$sSQLBuscaParametro = $oParametro->sql_query();
$rsBuscaParametro   = $oParametro->sql_record($sSQLBuscaParametro);
$lPesquisaOrgao     = db_utils::fieldsMemory($rsBuscaParametro,0)->t06_pesqorgao;

$sDisplayOrgaoUnidade = '';
if ($lPesquisaOrgao == 'f') {
  $sDisplayOrgaoUnidade = 'none';
}

db_app::load('scripts.js,estilos.css,prototype.js, dbmessageBoard.widget.js, windowAux.widget.js');
db_app::load('dbtextField.widget.js, dbcomboBox.widget.js, DBViewGeracaoAutorizacao.classe.js, grid.style.css');
db_app::load('datagrid.widget.js, strings.js, arrays.js, DBHint.widget.js, ');
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script type="text/javascript" src="scripts/scripts.js"></script>
	  <script type="text/javascript" src="scripts/prototype.js"></script>

    <style>

      .bensAtualizados {
       background-color: #D1F07C;
      }
      .bensComInventario {
       background-color: #C0BFFF;
      }
      .bensSemInventario {
        background-color: #FFF;
      }
      .bensSemInventario {
        background-color: #FFF;
      }

      .filtros {
        display : none;
      }
      #Filtros {
        width:90px;
      }
      #iTipoBem{
        width:120px;
      }
    </style>
  </head>

  <body bgcolor="#CCCCCC">

    <form class="container" id="form1" name="form1">
      <fieldset style="width: 600px;">
        <legend>Manutenção de Inventário</legend>
        <table border='0' class="form-container">
          <!-- Inventário / inventario / t75-->
          <tr>
            <td width="30%">
              <?
                db_ancora("<b>Inventário:</b>", "js_pesquisaInventario(true)", $oGet->db_opcao);
              ?>
            </td>
            <td>
              <?
                //$funcaoJsIventario = "onchange = 'js_pesquisaInventario(false)'";
                db_input('iInventario', 10, $It75_sequencial, true, 'text',3);
              ?>
            </td>
          </tr>
          <!-- Combo criado para poder trazer inventario com seus itens sem necessidade de tantos filtros -->
          <tr>
            <td>
              <b>Itens Vinculados:</b>
            </td>
            <td>
              <select id='filtros' onchange="js_exibeFiltros();">
                <option value = '1' >SIM</option>
                <option value = '0' >NÃO</option>
              </select>
            </td>
          </tr>
        </table>



        <!-- Filtro dos bens -->
        <div id='ctnFiltros' style="display: none ;">
        <table border='0' class="form-container">
          <!-- Orgão -->
          <tr id='filtroParametroAtivoOrgao'>
            <td width="30%">
              <?
                db_ancora("<b>Orgão:</b>", "js_pesquisaOrgao(true)", $oGet->db_opcao);
              ?>
            </td>
            <td>
              <?
                $funcaoJsOrgao = "onchange = 'js_pesquisaOrgao(false)'";
                db_input('iOrgao', 10, $Icoddepto, true, 'text', $oGet->db_opcao, $funcaoJsOrgao);
                db_input('sOrgao', 35, $Idescrdepto, true, 'text',3);
              ?>
            </td>
          </tr>
          <!-- Unidade -->
          <tr id='filtroParametroAtivoUnidade'>
            <td>
              <?
                db_ancora("<b>Unidade:</b>", "js_pesquisaUnidade(true)", $oGet->db_opcao);
              ?>
            </td>
            <td>
              <?
                $funcaoJsUnidade = "onchange = 'js_pesquisaUnidade(false)'";
                db_input('iUnidade', 10, $Icodigo, true, 'text', $oGet->db_opcao, $funcaoJsUnidade);
                db_input('sUnidade', 35, $Inomeinst, true, 'text',3);
              ?>
            </td>
          </tr>
          <!-- Departamento -->
          <tr  id='filtroDepartamento'>
            <td>
              <?
                db_ancora("<b>Departamento:</b>", "js_pesquisaDepartamento(true)", $oGet->db_opcao);
              ?>
            </td>
            <td>
              <?
                $funcaoJsDepartamento = "onchange = 'js_pesquisaDepartamento(false)'";
                db_input('iDepartamento', 10, $Icodigo, true, 'text', $oGet->db_opcao, $funcaoJsDepartamento);
                db_input('sDepartamento', 35, $Inomeinst, true, 'text',3);
              ?>
            </td>
          </tr>
          <!-- Divisão -->
          <tr id='filtroDivisao'>
            <td>
              <?
                db_ancora("<b>Divisão:</b>", "js_pesquisaDivisao(true)", $oGet->db_opcao);
              ?>
            </td>
            <td>
              <?
                $funcaoJsDivisao = "onchange = 'js_pesquisaDivisao(false)'";
                db_input('iDivisao', 10, $Icodigo, true, 'text', $oGet->db_opcao, $funcaoJsDivisao);
                db_input('sDivisao', 35, $Inomeinst, true, 'text',3);
              ?>
            </td>
          </tr>

          <!--  Convênio / benscadcedente / t04 -->
          <tr id='filtroConvenio'>
            <td>
              <?
                db_ancora("<b>Convênio:</b>", "js_pesquisaConvenio(true)", $oGet->db_opcao);
              ?>
            </td>
            <td>
              <?
                $funcaoJsConvenio = "onchange = 'js_pesquisaConvenio(false)'";
                db_input('iConvenio', 10, $It04_sequencial, true, 'text', $oGet->db_opcao, $funcaoJsConvenio);
                db_input('sConvenio', 35, $It04_numcgm, true, 'text',3);
              ?>
            </td>
          </tr>

          <!--  Classificação/ clabens / t64 -->
          <tr id='filtroClassificacao'>
            <td nowrap="nowrap" title="<?=@$Tt64_class?>">
            	<?
	              db_ancora("<b>Classificação</b>","js_pesquisaClassificacaoInicial(true);",1);
	            ?>
            </td>
            <td>
              <?
  		          db_input('sClassificacaoInicial',10,$It64_codcla,true,'',1);
  		          db_ancora("<b>até</b>","js_pesquisaClassificacaoFinal(true);",1);
                db_input('sClassificacaoFinal', 10, $It64_codcla, true, 'text', $oGet->db_opcao, "");
              ?>
            </td>
          </tr>
          <!--  Código Bem / bens / t52-->
          <tr id='filtroCodigo'>
            <td>
              <?
	              db_ancora("<b>Código do Bem</b>","js_pesquisaBemInicial(true);",1);
	            ?>
            </td>
            <td>
              <?
                db_input('iBemInicial', 10, $It52_bem, true, 'text', $oGet->db_opcao, "");
                db_ancora("<b>até</b>","js_pesquisaBemFinal(true);",1);
                db_input('iBemFinal', 10, $It52_bem, true, 'text', $oGet->db_opcao, "");
              ?>
            </td>
          </tr>
          <!--  Placa / bensplaca /t41-->
          <tr id='filtroPlaca'>
            <td>
              <?php
                db_ancora("<b>Placa</b>","js_pesquisaPlacaInicial(true);",1);
              ?>
            </td>
            <td>
              <?
                db_input('iPlacaInicial', 10, $It41_placa, true, 'text', $oGet->db_opcao, "");
                db_ancora("<b>até</b>","js_pesquisaPlacaFinal(true);",1);
                db_input('iPlacaFinal', 10, $It41_placa, true, 'text', $oGet->db_opcao, "");
              ?>
            </td>
          </tr>
          <!-- Intervalo de Valor / bens / nValorAquisicao-->
          <tr id='filtroIntervalo'>
            <td class="bold">
              Intervalo de Valor:
            </td>
            <td>
              <?
                db_input('nValorAquisicaoInicial', 10, $It52_valaqu, true, 'text', $oGet->db_opcao, "");
              ?>
              <b>até</b>
              <?
                db_input('nValorAquisicaoFinal', 10, $It52_valaqu, true, 'text', $oGet->db_opcao, "");
              ?>
            </td>
          </tr>
          <!-- Periodo Aquisição -->
          <tr id='filtroPeriodoAquisicaoInicial'>
            <td class="bold">
              Periodo de Aquisição Inicial:
            </td>
            <td>
              <?
                db_inputdata('dtAquisicaoInicial', "", "", "", true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr id='filtroPeriodoAquisicaoFinal'>
            <td class="bold">
              Periodo de Aquisição Final:
            </td>
            <td>
              <?
                db_inputdata('dtAquisicaoFinal', "", "", "", true, 'text', 1);
              ?>
            </td>
          </tr>
           <tr id='filtroTipo'>
            <td class="bold">
              Tipo:
            </td>
            <td>
              <?
                $aTipo = array("1"=>"Todos","2"=>"Imoveis","3"=>"Materiais","4"=>"Semoventes");
                db_select("iTipoBem", $aTipo, true, 2);
              ?>
            </td>
          </tr>
        </table>
        </div>
      </fieldset>
	    <input type='button' name="btnExibirBens" value='Exibir' onclick = "js_exibirBens();">
    </form>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>


<script>

var sUrlRpc                 = "pat4_inventario.RPC.php";
var aSituacaoBens           = new Array();
var aDepartamentos          = new Array();
var aDivisaoPorDepartamento = new Array();
var sCorBensComInventario   = "#C0BFFF";
var sCorBensAtualizados     = "#D1F07C";
/**
 *	variável para guardar o estado de um input
 *	guarda o estado anterior a cada focus no elemento html
 */

var nValorAntigo            = "";

$('filtroParametroAtivoOrgao').style.display   = '<?php echo $sDisplayOrgaoUnidade; ?>';
$('filtroParametroAtivoUnidade').style.display = '<?php echo $sDisplayOrgaoUnidade; ?>';



function js_exibeFiltros() {

  var lExibe = $F('filtros');

  if (lExibe == 1) {

    $("ctnFiltros").style.display = 'none';

  } else {
    $("ctnFiltros").style.display = '';
  }

}


function js_exibirBens() {


  var lFiltros = $F('filtros');

  if ($F("iInventario") == "") {

    alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.selecione_inventario'));
    return false;
  }

  if($F("dtAquisicaoInicial") != "" && $F("dtAquisicaoFinal") != ""){
    if(js_comparadata($F("dtAquisicaoInicial"), $F("dtAquisicaoFinal"), ">")){
      alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.data_inicial_maior_data_final'));
      return false;
    }
  }

  if ($F("iOrgao")                 == "" &&
      $F("iUnidade")               == "" &&
      $F("iDepartamento")          == "" &&
      $F("iDivisao")               == "" &&
      $F("sClassificacaoInicial")  == "" &&
      $F("sClassificacaoFinal")    == "" &&
      $F("iBemInicial")            == "" &&
      $F("iBemFinal")              == "" &&
      $F("iPlacaInicial")          == "" &&
      $F("iPlacaFinal")            == "" &&
      $F("iConvenio")              == "" &&
      $F("nValorAquisicaoInicial") == "" &&
      $F("nValorAquisicaoFinal")   == "" &&
      $F("dtAquisicaoInicial")     == "" &&
      $F("dtAquisicaoFinal")       == "" &&
      lFiltros == 0 ) {

    if (!confirm(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.nenhum_filtro_selecionado'))) {
      return false;
    }
  }

  var oParam                     = new Object();
  oParam.exec                    = 'getBensFiltroManutencao';
  oParam.iInventario             = $F("iInventario");
  oParam.iOrgao                  = $F("iOrgao");
  oParam.iUnidade                = $F("iUnidade");
  oParam.iDepartamento           = $F("iDepartamento");
  oParam.iDivisao                = $F("iDivisao");
  oParam.iClassificacaoInicial   = $F("sClassificacaoInicial");
  oParam.iClassificacaoFinal     = $F("sClassificacaoFinal");
  oParam.iBemInicial             = $F("iBemInicial");
  oParam.iBemFinal               = $F("iBemFinal");
  oParam.iPlacaInicial           = $F("iPlacaInicial");
  oParam.iPlacaFinal             = $F("iPlacaFinal");
  oParam.iConvenio               = $F("iConvenio");
  oParam.nValorAquisicaoInicial  = $F("nValorAquisicaoInicial");
  oParam.nValorAquisicaoFinal    = $F("nValorAquisicaoFinal");
  oParam.dtAquisicaoInicial      = $F("dtAquisicaoInicial");
  oParam.dtAquisicaoFinal        = $F("dtAquisicaoFinal");
  oParam.iTipoBem                = $F("iTipoBem");
  oParam.lFiltros                = lFiltros;

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.realizando_consulta'), "msgBox");
  var oAjax = new Ajax.Request(sUrlRpc,
                              {method:'post',
		                           parameters:'json='+Object.toJSON(oParam),
		                           onComplete: js_montaWindowGridItens});
}

/**
 * Carrega em um array as divisoes e departamentos disponiveis no sistema
 */
function js_pesquisaDivisoesDepartamentos() {

  var oParam      = new Object();
      oParam.exec = "getDivisaoDepartamentos";

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.carregando_departamento_divisoes'), "msgBox");
  var oAjax = new Ajax.Request(sUrlRpc,
                              {method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete: function (oAjax) {

                                 js_removeObj("msgBox");
                                 var oRetorno            = eval("("+oAjax.responseText+")");
                                 aDepartamentos          = oRetorno.aDepartamentos;
                                 aDivisaoPorDepartamento = oRetorno.aDivisoes;
                               }});
}

function js_pesquisaSituacoes() {

  var oParam  = new Object();
  oParam.exec = "getSituacoes";

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.carregando_departamento_divisoes'), "msgBox");
  var oAjax = new Ajax.Request(sUrlRpc,
                            {method:'post',
                             parameters:'json='+Object.toJSON(oParam),
                             onComplete: function (oAjax) {

                               js_removeObj("msgBox");
                               var oRetorno  = eval("("+oAjax.responseText+")");
                               aSituacaoBens = oRetorno.aSituacaoBens;
                             }});
}

function js_montaWindowGridItens (oAjax) {

  js_removeObj("msgBox");
  var oRetorno = JSON.parse(oAjax.responseText);

  if (oRetorno.iStatus == 2) {

    alert(oRetorno.sMessage.urlDecode());
    return false;
  }

  if (oRetorno.aBensEncontrados.length == 0) {

    alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.bens_nao_encontados'));
    return false;
  }

  var iHeight   = document.body.clientHeight-100;
  var iWidth    = document.body.clientWidth-50;
  var iWidthContainer = (iWidth-30);
  oWindowAux    = new windowAux('oWindowAux', 'Vincular Bens', iWidth, iHeight);
  var sContent  = "<div style='width: "+iWidthContainer+"px;' id='cntGrid'></div>";
      sContent += "<fieldset style='width: 400px'>";
      sContent += "<legend><b>Legenda</b></legend>";
      sContent += "<table colspan='0'>";
      sContent += "  <tr>";
      sContent += "    <td align='center' class='bensSemInventario' height='20px' width='150px'><b>Bens Sem Inventário</b></td>";
      sContent += "    <td align='center' class='bensAtualizados'   height='20px' width='150px'><b>Atualizados neste inventário</b></td>";
      sContent += "    <td align='center' class='bensComInventario' height='20px' width='150px'><b>Atualizados em outro inventário</b></td>";
      sContent += "  </tr>";
      sContent += "</table>";
      sContent += "</fieldset>";
      sContent += "<p align='center'><input type='button' value='Fechar' onclick='js_fecharWindow();' /></p>";
  oWindowAux.setContent(sContent);

  var sHelpMsgBoardBens = _M('patrimonial.patrimonio.pat4_inventariomanutencao001.marque_registros');
  var oMessageBoardBens = new DBMessageBoard('msg_boardBens',
		  _M('patrimonial.patrimonio.pat4_inventariomanutencao001.marque_registros', {intervalo: $F('iInventario')}),
                                             sHelpMsgBoardBens,
                                             oWindowAux.getContentContainer());


  var aHeader     = new Array();
      aHeader[0]  = "Placa";
      aHeader[1]  = "Bem";
      aHeader[2]  = "Descrição";
      aHeader[3]  = "Departamento/Divisão";
      aHeader[4]  = "Situação";
      aHeader[5]  = "Vlr. Atual";
      aHeader[6]  = "Vlr. Residual";
      aHeader[7]  = "Vlr. Depreciável";
      aHeader[8]  = "Departamento";
      aHeader[9]  = "Divisão";
      aHeader[10]  = "Vida Útil";
      aHeader[11] = "IB";
      aHeader[12] = "II";

  var aCellWidth     = new Array();
      aCellWidth[0]  = "20";
      aCellWidth[1]  = "20";
      aCellWidth[2]  = "80";
      aCellWidth[3]  = "80";
      aCellWidth[4]  = "40";
      aCellWidth[5]  = "40";
      aCellWidth[6]  = "40";
      aCellWidth[7]  = "40";
      aCellWidth[8]  = "40";
      aCellWidth[9]  = "40";
      aCellWidth[10] = "20";
      aCellWidth[11] = "1";
      aCellWidth[12] = "1";

  var aCellAlign     = new Array();
      aCellAlign[0]  = "center";
      aCellAlign[1]  = "center";
      aCellAlign[2]  = "left";
      aCellAlign[3]  = "left";
      aCellAlign[4]  = "left";
      aCellAlign[5]  = "left";
      aCellAlign[6]  = "left";
      aCellAlign[7]  = "left";
      aCellAlign[8]  = "left";
      aCellAlign[9]  = "left";
      aCellAlign[10] = "center";
      aCellAlign[11] = "center";
      aCellAlign[12] = "center";

  oGridBens = new DBGrid('cntGrid');
  oGridBens.nameInstance = 'oGridBens';
  oGridBens.setCheckbox(0);
  oGridBens.allowSelectColumns(true);
  oGridBens.setHeader(aHeader);
  oGridBens.setCellWidth(aCellWidth);
  oGridBens.setCellAlign(aCellAlign);
  oGridBens.setHeight(300);
  oGridBens.aHeaders[12].lDisplayed = false; // Código do Bem no InventarioBem
  oGridBens.aHeaders[13].lDisplayed = false; // Codigo do Inventario
  oGridBens.aHeaders[2].lDisplayed  = false; // Código do Bem
  oGridBens.show($('cntGrid'));

  /**
   * Seta tamanho para a coluna "M" (Marcar Todos)
   */
  $('col1').style.width='7px';


  /**
   * Funcoes de acao ao selecionar uma linha da grid
   */
  oGridBens.selectSingle = function (oCheckbox,sRow,oRow) {

    var iCodigoBem              = oRow.aCells[2].getValue();
    var sIdCellValorDepreciavel = oRow.aCells[6].sId;
    var sIdCellValorResidual    = oRow.aCells[7].sId;
    var sIdCellValorVidaUtil    = oRow.aCells[10].sId;

    oCheckbox.disabled = true;
    $(sRow).className = 'bensAtualizados';
    $("oComboBoxSituacao"+iCodigoBem).disabled     = false;
    $("oTxtValorAtual"+iCodigoBem).disabled        = false;
    //$("oTxtValorDepreciavel"+iCodigoBem).disabled  = false;
    $("oTxtValorResidual"+iCodigoBem).disabled     = false;
    $("oComboBoxDepartamento"+iCodigoBem).disabled = false;
    $("oComboBoxDivisao"+iCodigoBem).disabled      = false;
    $("oTxtVidaUtil"+iCodigoBem).disabled          = false;
    js_styleLiberaDigitacao(oRow, $("oTxtValorDepreciavel"+iCodigoBem));
    js_styleLiberaDigitacao(oRow, $("oTxtValorAtual"+iCodigoBem));
    js_styleLiberaDigitacao(oRow, $("oTxtValorResidual"+iCodigoBem));
    js_styleLiberaDigitacao(oRow, $("oTxtVidaUtil"+iCodigoBem));
    $("oTxtVidaUtil"+iCodigoBem).style.textAlign = 'center';
    js_salvaDadosLinhaSelecionada(oRow);

  };

  oWindowAux.show();
  oMessageBoardBens.show();
  oWindowAux.setShutDownFunction(function(){
    js_fecharWindow();
  });
  js_preencheGrid(oRetorno.aBensEncontrados);

}

function js_salvaDadosLinhaSelecionada(oRow) {

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.salvando_informacoes'), "msgBox");
  var oParam                 = new Object();
  oParam.exec                = "salvarDadosBem";
  oParam.iCodigoBem          = oRow.aCells[2].getValue();
  oParam.iSituacao           = oRow.aCells[5].getValue();
  oParam.nValorResidual      = oRow.aCells[7].getValue();
  oParam.nValorDepreciavel   = oRow.aCells[8].getValue();
  oParam.nValorAtual         = oRow.aCells[6].getValue();
  oParam.iCodigoDepartamento = oRow.aCells[9].getValue();
  oParam.iCodigoDivisao      = oRow.aCells[10].getValue();
  oParam.iVidaUtil           = oRow.aCells[11].getValue();
  oParam.iInventarioBem      = oRow.aCells[12].getValue();


  oParam.iCodigoInventario   = $F('iInventario');

  var oAjax = new Ajax.Request(sUrlRpc,
                                {method:'post',
                                 parameters:'json='+Object.toJSON(oParam),
                                 onComplete:
                                   function (oAjax) {

                                   js_removeObj("msgBox");
                                   var oRetorno =  eval("("+oAjax.responseText+")");
                                   if (oRetorno.status == 2) {
                                     alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.nao_foi_possivel_salvar_bem'));
                                   }
                                   var sId          = oRow.aCells[12].sId;
                                   $(sId).innerHTML = (oRetorno.iCodigoBemInventario);

                                   var nValorResidual    = js_strToFloat(oRow.aCells[7].getValue());
                                   var nValorAtual       = js_strToFloat(oRow.aCells[6].getValue());
                                   var nValorDepreciavel = nValorAtual - nValorResidual;
                                   $("oTxtValorDepreciavel"+oParam.iCodigoBem).setValue(js_formatar(nValorDepreciavel, "f"));

                                 }
                            });
}

/**
 * Libera digitacao na linha selecionada
 * @param oRom linha da grid
 * @param oObject o objeto que sera liberado
 */
function js_styleLiberaDigitacao(oRow, oObject) {

  oObject.style.backgroundColor = "#D1F07C";
  oObject.style.border          = "0px solid transparent";
  oObject.style.width           = "100%";
  oObject.style.height          = "100%";
  oObject.style.textAlign       = "right";
  oObject.style.color           = "#000";
  $(oRow.aCells[0].sId).style.backgroundColor = "#D1F07C";
  $(oRow.aCells[1].sId).style.backgroundColor = "#D1F07C";
  $(oRow.aCells[2].sId).style.backgroundColor = "#D1F07C";
  $(oRow.aCells[3].sId).style.backgroundColor = "#D1F07C";
  $(oRow.aCells[4].sId).style.backgroundColor = "#D1F07C";
}

/**
 * Funcao que bloqueia os dados da linha quando desmarcado o checkbox
 * @param oRom
 * @param oCellGrid celula que contem o input
 * @param oInputCell o input dentro da celula
 */
function js_bloqueiaLinhaCheckbox(oRow, oCellGrid, oInputCell) {

  var sBloqueioPadrao = "rgb(222, 184, 135)";
  if (oRow.aCells[11].getValue() != "0" && oRow.aCells[11].getValue() != null) {

    sBloqueioPadrao = sCorBensComInventario;
    $(oRow.aCells[0].sId).style.backgroundColor = sBloqueioPadrao;
    $(oRow.aCells[1].sId).style.backgroundColor = sBloqueioPadrao;
    $(oRow.aCells[2].sId).style.backgroundColor = sBloqueioPadrao;
    $(oRow.aCells[3].sId).style.backgroundColor = sBloqueioPadrao;
    $(oRow.aCells[4].sId).style.backgroundColor = sBloqueioPadrao;
  } else {

    $(oRow.aCells[0].sId).style.backgroundColor = "#FFFFFF";
    $(oRow.aCells[1].sId).style.backgroundColor = "#FFFFFF";
    $(oRow.aCells[2].sId).style.backgroundColor = "#FFFFFF";
    $(oRow.aCells[3].sId).style.backgroundColor = "#FFFFFF";
    $(oRow.aCells[4].sId).style.backgroundColor = "#FFFFFF";
  }

  oCellGrid.style.backgroundColor  = sBloqueioPadrao;
  oInputCell.style.backgroundColor = sBloqueioPadrao;
  oInputCell.style.color           = "#58463D";
}

function js_preencheGrid(aBens) {

  js_divCarregando("Carregando dados...", "oDivDados");

  var iInventario = document.getElementById('iInventario').value;
  oGridBens.clearAll(true);
  aItensGrid = {};
  aBens.each(function (oItem, iIndice) {

    /* INPUT VALOR Depreciavel */
    var oValorAtual = window["oTxtValorAtual" + oItem.codigo_bem] = new DBTextField("oTxtValorAtual"+oItem.codigo_bem, "oTxtValorAtual"+oItem.codigo_bem , js_formatar(oItem.valor_atual, 'f'), 10);
    oValorAtual.addEvent("onKeyPress", "return js_mask(event,\"0-9|,|-\")");
    oValorAtual.addStyle('width', '100%');
    oValorAtual.addStyle('height', '100%');
    oValorAtual.addStyle('text-align', 'right');
    oValorAtual.addStyle('border', '1px solid transparent');
    oValorAtual.addEvent("onFocus", "js_liberaDigitacao(this);");
    oValorAtual.addEvent("onBlur", "js_bloqueiaDigitacao(this);js_atualizaValor(this, event);");
    oValorAtual.addEvent("onChange",";js_valorAtualizado(this,"+oItem.codigo_bem+","+iIndice+"); js_atualizaValor(this, event);");
    oValorAtual.addEvent("onKeyUp",';js_ValidaValor(this, event);');
    oValorAtual.setReadOnly(true);

    /* INPUT VALOR Depreciavel */
    var oValorDepreciavel = window["oTxtValorDepreciavel"+oItem.codigo_bem] = new DBTextField("oTxtValorDepreciavel" + oItem.codigo_bem, "oTxtValorDepreciavel" + oItem.codigo_bem, js_formatar(oItem.valor_depreciavel, 'f'), 10);
    oValorDepreciavel.addEvent("onKeyPress", "return js_mask(event,\"0-9|,|-\")");
    oValorDepreciavel.addStyle('width', '100%');
    oValorDepreciavel.addStyle('height', '100%');
    oValorDepreciavel.addStyle('text-align', 'right');
    oValorDepreciavel.addStyle('border', '1px solid transparent');
    oValorDepreciavel.setReadOnly(true);

    /* INPUT VALOR RESIDUAL */
    var oValorResidual = window["oTxtValorResidual" + oItem.codigo_bem] = new DBTextField("oTxtValorResidual"+oItem.codigo_bem, "oTxtValorResidual" + oItem.codigo_bem, js_formatar(oItem.valor_residual, 'f'), 10);
    oValorResidual.addEvent("onKeyPress", "return js_mask(event,\"0-9|,|-\")");
    oValorResidual.addStyle('width', '100%');
    oValorResidual.addStyle('height', '100%');
    oValorResidual.addStyle('text-align', 'right');
    oValorResidual.addStyle('border', '1px solid transparent');
    oValorResidual.addEvent("onFocus", "js_liberaDigitacao(this);");
    oValorResidual.addEvent("onBlur", "js_bloqueiaDigitacao(this);js_atualizaValor(this, event);");
    oValorResidual.addEvent("onChange",";js_valorResidual(this,"+oItem.codigo_bem+","+iIndice+");js_atualizaValor(this,event);");
    oValorResidual.addEvent("onKeyUp",';js_ValidaValor(this, event);');
    oValorResidual.setReadOnly(true);


    /* BOX SITUACAO */
    var oOptionSelecione = new Option("Selecione", 0);

    var oComboBoxSituacao = document.createElement("select");
    oComboBoxSituacao.id = "oComboBoxSituacao" + oItem.codigo_bem;
    oComboBoxSituacao.add( oOptionSelecione );

    for (var iOption = 0; iOption < aSituacaoBens.length; iOption++) {

      var oSituacao = aSituacaoBens[iOption],
          oOption = new Option(oSituacao.t70_descr.urlDecode(), oSituacao.t70_situac, (oSituacao.t70_situac == oItem.situacao));

      oComboBoxSituacao.add(oOption);
    }

    oComboBoxSituacao.onchange = function () {
      js_alteraSituacaoBem(oComboBoxSituacao, oItem.codigo_bem, iIndice);
    }
    oComboBoxSituacao.style.width = "100%";
    oComboBoxSituacao.disabled = true;

    /* BOX DEPARTAMENTO */
    var oComboBoxDivisao = document.createElement("select");
    var oComboBoxDepartamento = document.createElement("select");
    oComboBoxDepartamento.id = "oComboBoxDepartamento" + oItem.codigo_bem;
    oComboBoxDepartamento.add( oOptionSelecione );

    var iCodigoDepartamento = oItem.codigo_departamento_bem;
    if (oItem.codigo_bem_inventario != null) {
      iCodigoDepartamento = oItem.departamento_inventario;
    }

    for (var iOpcao = 0; iOpcao < aDepartamentos.length; iOpcao++) {

      var oDepartamento = aDepartamentos[iOpcao],
          oOption = new Option(oDepartamento.descrdepto.urlDecode(), oDepartamento.coddepto, (oDepartamento.coddepto == iCodigoDepartamento));

      oComboBoxDepartamento.add( oOption );
    }

    oComboBoxDepartamento.onchange = function () {

      js_pesquisaDivisaoDepartamento(oComboBoxDepartamento.value, oComboBoxDivisao);
      js_alterarDepartamento(oComboBoxDepartamento, oItem.codigo_bem, iIndice);
    }

    oComboBoxDepartamento.style.width = "100%";
    oComboBoxDepartamento.disabled = true;

    /* BOX DIVISAO */
    oComboBoxDivisao.id = "oComboBoxDivisao" + oItem.codigo_bem;

    var aDivisoes = new Array();
    if (aDivisaoPorDepartamento[iCodigoDepartamento] != null) {
      aDivisoes = aDivisaoPorDepartamento[iCodigoDepartamento];
    }

    if (aDivisoes.length == 0) {
      oComboBoxDivisao.add(new Option("Sem Divisão", 0, true));
    } else {

      var iDivisao = oItem.codigo_divisao_bem;
      if (oItem.codigo_bem_inventario != null) {
        iDivisao = oItem.divisao_inventario;
      }

      for (var iOption = 0; iOption < aDivisoes.length; iOption++) {

        var oDivisao = aDivisoes[iOption],
            oOption = new Option(oDivisao.t30_descr.urlDecode(), oDivisao.t30_codigo, (oDivisao.t30_codigo == iDivisao));

        oComboBoxDivisao.add( oOption );
      }
    }

    oComboBoxDivisao.onchange = function () {

      js_alterarDivisao(oComboBoxDivisao, oItem.codigo_bem, iIndice);
    }

    oComboBoxDivisao.style.width = "100%";
    oComboBoxDivisao.disabled = true;

    var sDepartamentoDivisao = oItem.descricao_departamento_bem.urlDecode();
    if (oItem.descricao_divisao_bem != null && oItem.descricao_divisao_bem != "") {
      sDepartamentoDivisao += " / "+oItem.descricao_divisao_bem.urlDecode();
    }

    /* INPUT VIDA UTIL */
    var oVidaUtil = window["oTxtVidaUtil"+oItem.codigo_bem] = new DBTextField("oTxtVidaUtil"+oItem.codigo_bem, "oTxtVidaUtil"+oItem.codigo_bem, oItem.vida_util, 5);
    oVidaUtil.addEvent("onKeyPress", "return js_mask(event,\"0-9\")");
    oVidaUtil.addStyle('width', '100%');
    oVidaUtil.addStyle('height', '100%');
    oVidaUtil.addStyle('text-align', 'center');
    oVidaUtil.addStyle('border', '1px solid transparent');
    oVidaUtil.addEvent("onFocus", "js_liberaDigitacao(this);");
    oVidaUtil.addEvent("onBlur", "js_bloqueiaDigitacao(this);");
    oVidaUtil.addEvent("onChange",";js_vidaUtil(this,"+oItem.codigo_bem+","+iIndice+")");
    oVidaUtil.setReadOnly(true);

    /* INPUT   BEM-INVETARIO */
    var oBemInvetario = window["oTxtBemInventario"+oItem.codigo_bem] = new DBTextField("oTxtBemInventario"+oItem.codigo_bem, "oTxtBemInventario"+oItem.codigo_bem, +oItem.codigo_bem_inventario, 5);
    oBemInvetario.setReadOnly(true);

    var iCodigoBemInventario = oItem.codigo_bem_inventario;
    if (oItem.codigo_bem_inventario == null || oItem.codigo_bem_inventario == 'null') {
      iCodigoBemInventario = '0';
    }
    var iCodigoInventario = oItem.codigo_inventario;
    if (oItem.codigo_inventario == null || oItem.codigo_inventario == 'null') {
      iCodigoInventario = '0';
    }

    if (iCodigoBemInventario != "0" && iCodigoBemInventario == iInventario) {

      oValorAtual.addStyle("backgroundColor", sCorBensAtualizados);
      oValorDepreciavel.addStyle("backgroundColor", sCorBensAtualizados);
      oValorResidual.addStyle("backgroundColor", sCorBensAtualizados);
      oVidaUtil.addStyle("backgroundColor", sCorBensAtualizados);
    } else if (iCodigoInventario != "0" && iCodigoInventario == iInventario) {

      oValorAtual.addStyle("backgroundColor", sCorBensComInventario);
      oValorDepreciavel.addStyle("backgroundColor", sCorBensComInventario);
      oValorResidual.addStyle("backgroundColor", sCorBensComInventario);
      oVidaUtil.addStyle("backgroundColor", sCorBensComInventario);
    }

    aItensGrid[iIndice] = {
      situacao : oComboBoxSituacao,
      valorAtual : oValorAtual,
      valorResidual : oValorResidual,
      valorDepreciavel : oValorDepreciavel,
      departamento : oComboBoxDepartamento,
      divisao : oComboBoxDivisao,
      vidaUtil : oVidaUtil,
      codigoBem : iCodigoBemInventario,
      codigoInventario : iCodigoInventario
    };

    var aLinha     = new Array();
        aLinha[0]  = oItem.placa.urlDecode();
        aLinha[1]  = oItem.codigo_bem;
        aLinha[2]  = oItem.descricao.urlDecode();
        aLinha[3]  = sDepartamentoDivisao;
        aLinha[4]  = '';
        aLinha[5]  = '';
        aLinha[6]  = '';
        aLinha[7]  = '';
        aLinha[8]  = '';
        aLinha[9]  = '';
        aLinha[10] = '';
        aLinha[11] = iCodigoBemInventario;
        aLinha[12] = iCodigoInventario;

    var lCheckBoxBloqueado = false;
    if (oItem.codigo_inventario != null && oItem.codigo_inventario != iInventario) {
      lCheckBoxBloqueado = true;
    }
    oGridBens.addRow(aLinha, false, lCheckBoxBloqueado);

    oItem.departamento_divisao = sDepartamentoDivisao;

    var oRowAdicionado = oGridBens.aRows[iIndice];

    if (iCodigoBemInventario != "0" && oItem.codigo_inventario == iInventario) {
      oRowAdicionado.setClassName('bensAtualizados');
    } else if (oItem.codigo_inventario != iInventario && iCodigoBemInventario != "0") {
      oRowAdicionado.setClassName('bensComInventario');
    }
  });

  oGridBens.renderRows();

  for (var iIndice = 0; iIndice < oGridBens.aRows.length; iIndice++) {

    var oItem = aItensGrid[iIndice],
        oRow  = oGridBens.aRows[iIndice],
        tdSituacao = document.getElementById(oRow.aCells[5].sId),
        tdValorAtual = document.getElementById(oRow.aCells[6].sId),
        tdValorResidual = document.getElementById(oRow.aCells[7].sId),
        tdValorDepreciavel = document.getElementById(oRow.aCells[8].sId),
        tdDepartamento = document.getElementById(oRow.aCells[9].sId),
        tdDivisao = document.getElementById(oRow.aCells[10].sId),
        tdVidaUtil = document.getElementById(oRow.aCells[11].sId);

    tdSituacao.innerHTML = '';
    tdSituacao.appendChild( oItem.situacao );
    oItem.valorAtual.show( tdValorAtual );
    oItem.valorResidual.show( tdValorResidual );
    oItem.valorDepreciavel.show( tdValorDepreciavel );
    tdDepartamento.innerHTML = '';
    tdDepartamento.appendChild( oItem.departamento );
    tdDivisao.innerHTML = '';
    tdDivisao.appendChild( oItem.divisao );
    oItem.vidaUtil.show( tdVidaUtil );

    if (oItem.codigoBem != "0" && oItem.codigoBem == iInventario) {

      tdValorAtual.style.backgroundColor       = sCorBensAtualizados;
      tdValorResidual.style.backgroundColor    = sCorBensAtualizados;
      tdValorDepreciavel.style.backgroundColor = sCorBensAtualizados;
      tdVidaUtil.style.backgroundColor         = sCorBensAtualizados;
    } else if (oItem.iCodigoInventario != "0" && oItem.iCodigoInventario == iInventario) {

      tdValorAtual.style.backgroundColor       = sCorBensComInventario;
      tdValorResidual.style.backgroundColor    = sCorBensComInventario;
      tdValorDepreciavel.style.backgroundColor = sCorBensComInventario;
      tdVidaUtil.style.backgroundColor         = sCorBensComInventario;
    }
  }

  js_removeObj("oDivDados");
}

function js_alterarDepartamento(oComboBoxDepartamento, iCodigoBem, iCodigoLinha) {

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.salvando_informacoes'), "msgBox");
  var oParam                 = new Object();
  oParam.exec                = "alterarDepartamento";
  oParam.iCodigoDepartamento = oComboBoxDepartamento.value;
  oParam.iCodigoBem          = iCodigoBem;
  oParam.iCodigoInventario   = $F('iInventario');
  oParam.iInventarioBem      = oGridBens.aRows[iCodigoLinha].aCells[12].getValue();

  var oAjax = new Ajax.Request(sUrlRpc,
                              {method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete:
                                 function (oAjax) {

                                   js_removeObj("msgBox");
                                   var oRetorno =  eval("("+oAjax.responseText+")");
                                   if(oRetorno.status == 2) {
                                     alert(oRetorno.message.urlDecode());
                                   }
                                   var sSid         = oGridBens.aRows[iCodigoLinha].aCells[12].sId;
                                   $(sId).innerHTML = (oRetorno.iCodigoBemInventario);
                               }
                          });
}

function js_alterarDivisao(oComboBoxDivisao, iCodigoBem, iCodigoLinha) {

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.salvando_informacoes'), "msgBox");
  var oParam                 = new Object();
  oParam.exec                = "alterarDivisao";
  oParam.iCodigoDivisao      = oComboBoxDivisao.value;
  oParam.iCodigoBem          = iCodigoBem;
  oParam.iCodigoInventario   = $F('iInventario');
  oParam.iInventarioBem      = oGridBens.aRows[iCodigoLinha].aCells[12].getValue();

  var oAjax = new Ajax.Request(sUrlRpc,
                              {method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete:
                                 function (oAjax) {

                                   js_removeObj("msgBox");
                                   var oRetorno =  eval("("+oAjax.responseText+")");
                                   if(oRetorno.status == 2) {
                                     alert(oRetorno.message.urlDecode());
                                   }
                                   var sSid         = oGridBens.aRows[iCodigoLinha].aCells[12].sId;
                                   $(sId).innerHTML = (oRetorno.iCodigoBemInventario);
                               }
                          });
}

/**
 * Atualiza o campo VALOR DEPRECIAVEL do bem
 */
function js_valorAtualizado(oInputValorAtualizado, iCodigoBem, iCodigoLinha) {

  var iValorResidual = js_strToFloat($F('oTxtValorResidual'+iCodigoBem)).valueOf();
  var iValorAtual    = parseFloat(oInputValorAtualizado.getValue());

  if (iValorAtual < 0) {

    alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.valor_atual_negativo'));
    $("oTxtValorAtual"+iCodigoBem).value = nValorAntigo;
    return false;
  }

  if (iValorAtual < iValorResidual) {

    alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.valor_atual_menor_valor_residual'));
    $("oTxtValorAtual"+iCodigoBem).value = nValorAntigo;
    return false;
  }

  js_salvaDadosLinhaSelecionada(oGridBens.aRows[iCodigoLinha]);


}


function js_valorResidual(oInputValorResidual, iCodigoBem, iCodigoLinha) {


  var iValorResidual = parseFloat(oInputValorResidual.getValue());
  var iValorAtual    = js_strToFloat($F('oTxtValorAtual'+iCodigoBem)).valueOf();


  if (iValorResidual < 0){

    alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.valor_residual_negativo'));
    $("oTxtValorResidual"+iCodigoBem).value = nValorAntigo;
    return false;
  }

  if (iValorAtual < iValorResidual  ) {

    alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.valor_residual_maior_valor_atual'));
    $("oTxtValorResidual"+iCodigoBem).value = nValorAntigo;
    return false;
  }

  js_salvaDadosLinhaSelecionada(oGridBens.aRows[iCodigoLinha]);

  // js_divCarregando("Aguarde, salvando informação...", "msgBox");
  // var oParam               = new Object();
  // oParam.exec              = "alteraValorResidual";
  // oParam.nValorResidual    = parseFloat(oInputValorResidual.getValue());
  // oParam.iCodigoBem        = iCodigoBem;
  // oParam.iCodigoInventario = $F('iInventario');
  // oParam.iInventarioBem    = oGridBens.aRows[iCodigoLinha].aCells[12].getValue();

  // var oAjax = new Ajax.Request(sUrlRpc,
  //                             {method:'post',
  //                              parameters:'json='+Object.toJSON(oParam),
  //                              onComplete:
  //                                function (oAjax) {

  //                                  js_removeObj("msgBox");
  //                                  var oRetorno =  eval("("+oAjax.responseText+")");
  //                                  if(oRetorno.status == 2) {
  //                                    alert(oRetorno.message.urlDecode());
  //                                  }
  //                                  var sSid         = oGridBens.aRows[iCodigoLinha].aCells[12].sId;
  //                                  $(sId).innerHTML = (oRetorno.iCodigoBemInventario);

  //                                 var nValorDepreciavelAtualizado = iValorAtual - iValorResidual;
  //                                 $("oTxtValorDepreciavel"+iCodigoBem).setValue(nValorDepreciavelAtualizado);
  //                              }
  //                         });
}

function js_vidaUtil (oInputVidaUtil, iCodigoBem, iCodigoLinha) {

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.salvando_informacoes'), "msgBox");
  var oParam               = new Object();
  oParam.exec              = "alteraVidaUtil";
  oParam.iVidaUtil         = oInputVidaUtil.getValue();
  oParam.iCodigoBem        = iCodigoBem;
  oParam.iCodigoInventario = $F('iInventario');
  oParam.iInventarioBem    = oGridBens.aRows[iCodigoLinha].aCells[12].getValue();

  var oAjax = new Ajax.Request(sUrlRpc,
                              {method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete:
                                 function (oAjax) {

                                   js_removeObj("msgBox");
                                   var oRetorno =  eval("("+oAjax.responseText+")");
                                   if(oRetorno.status == 2) {
                                     alert(oRetorno.message.urlDecode());
                                   }
                                   var sSid         = oGridBens.aRows[iCodigoLinha].aCells[12].sId;
                                   $(sId).innerHTML = (oRetorno.iCodigoBemInventario);

                               }
                          });
}




function js_alteraSituacaoBem (oComboBoxSituacao, iCodigoBem, iCodigoLinha) {

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.salvando_informacoes'), "msgBox");
  var oParam               = new Object();
  oParam.exec              = "alteraSituacao";
  oParam.iSituacao         = oComboBoxSituacao.value;
  oParam.iCodigoBem        = iCodigoBem;
  oParam.iCodigoInventario = $F('iInventario');
  oParam.iInventarioBem    = oGridBens.aRows[iCodigoLinha].aCells[12].getValue();

  var oAjax = new Ajax.Request(sUrlRpc,
                              {method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete:

                                 function (oAjax) {

                                   js_removeObj("msgBox");
                                   var oRetorno =  eval("("+oAjax.responseText+")");
                                   if(oRetorno.status == 2) {
                                     alert(oRetorno.message.urlDecode());
                                   }
                                   var sSid         = oGridBens.aRows[iCodigoLinha].aCells[12].sId;
                                   $(sId).innerHTML = (oRetorno.iCodigoBemInventario);
                                   }
                              });
}



function js_pesquisaDivisaoDepartamento(iCodigoDepartamento, oObjetoDivisao) {

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.buscando_divisao_do_departamento'), "msgBox");

  var oParam                 = new Object();
  oParam.exec                = "getDivisaoPorDepartamento";
  oParam.iCodigoDepartamento = iCodigoDepartamento;

  var oAjax = new Ajax.Request(sUrlRpc,
                              {method:'post',
                               parameters:'json='+Object.toJSON(oParam),
                               onComplete:

                                 function (oAjax) {

                                   js_removeObj("msgBox");
                                   var oRetorno =  eval("("+oAjax.responseText+")");
                                   oObjetoDivisao.options.length = 0;
                                   oObjetoDivisao.add( new Option('Sem Divisão', 0, true));

                                   if (oRetorno.aDivisaoDepartamento.length == 0) {
                                     return;
                                   }

                                   oRetorno.aDivisaoDepartamento.each(function (oDivisao, iIndice){
                                     oObjetoDivisao.add( new Option(oDivisao.t30_descr.urlDecode(), oDivisao.t30_codigo) );
                                   });
                                 }
                               });
}

function js_bloqueiaDigitacao(oObject) {

  oObject.readOnly         = true;
  oObject.style.border     ='0px';
  oObject.style.fontWeight = "normal";
  oObject.value            = oObject.value;
}

function js_liberaDigitacao (object) {

  nValorObjeto            = js_strToFloat(object.value).valueOf();
  object.value            = nValorObjeto;
  object.style.border     = '1px solid black';
  object.readOnly         = false;

  object.style.fontWeight = "bold";
  object.select();

  nValorAntigo = object.value;
}


function js_atualizaValor(object, event) {

  object.value  = js_formatar(object.value,'f');
  var teclaPressionada = event.which;

  if (teclaPressionada == 27) {
    object.value = nValorAntigo;
  }
}

function js_fecharWindow() {
  oWindowAux.destroy();
}

/**
 * Pesquisa de Inventário
 */
function js_pesquisaInventario(lMostra){

  var sUrlLookUp = "func_inventario.php?situacao=1&funcao_js=parent.js_mostraInventario|t75_sequencial";
  js_OpenJanelaIframe('', 'db_iframe_inventario', sUrlLookUp, 'Pesquisa Inventarário', lMostra);
}

function js_mostraInventario(iInventario) {
  db_iframe_inventario.hide();
  $('iInventario').value = iInventario;
}

/**
 * Pesquisa de Orgão
 */
function js_pesquisaOrgao(lMostra) {

    var sUrlLookUp = "func_db_departorg_orcorgao.php?";

    if (lMostra) {
      sUrlLookUp += "lBuscaCampoOrgao=true&funcao_js=parent.js_mostraOrgao|db01_orgao|o40_descr";
    } else {

      sValorPesquisa = $F('iOrgao');
      sUrlLookUp    += "lBuscaCampoOrgao=true&pesquisa_chave="+sValorPesquisa+"&funcao_js=parent.js_preencheOrgao";
    }
    js_OpenJanelaIframe('', 'db_iframe_db_departorg', sUrlLookUp, 'Pesquisa Tipo de Orgão', lMostra);
  }

function js_mostraOrgao(iOrgao, sDescricao) {

  $("iOrgao").value = iOrgao;
  $("sOrgao").value = sDescricao;

  $("iUnidade").value = '';
  $("sUnidade").value = '';
  db_iframe_db_departorg.hide();
}

function js_preencheOrgao(sDescricao, lErro) {

  $('sOrgao').value   = sDescricao;
  $("iUnidade").value = '';
  $("sUnidade").value = '';
  if (lErro) {
    $('iOrgao').value = "";
  }
}


/**
 * Pesquisa de Unidade
 */
function js_pesquisaUnidade(lMostra) {

  if ($F("iOrgao") == "") {

    alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.selecione_orgao'));
    return false;
    }

  var sUrlLookUp = "func_orcunidade.php?orgao="+$F("iOrgao");

  if(lMostra) {
    sUrlLookUp += "&funcao_js=parent.js_mostraUnidade|o41_unidade|o41_descr";
  } else {

    sUrlLookUp += "&pesquisa_chave="+$F('iUnidade')+"&funcao_js=parent.js_preencheUnidade";
  }
  js_OpenJanelaIframe('', 'db_iframe_orcunidade', sUrlLookUp, 'Pesquisa Unidade', lMostra);
}

function js_mostraUnidade(iUnidade, sUnidade) {

  $("iUnidade").value = iUnidade;
  $("sUnidade").value = sUnidade;
  db_iframe_orcunidade.hide();
}


function js_preencheUnidade(sUnidade, lErro) {

  $("sUnidade").value = sUnidade;
  if(lErro){
    $("iUnidade").value = "";
  }
}


function js_pesquisaDepartamento(lMostra) {

  var sQueryString = "";
  if ($("iUnidade").value != "") {
    sQueryString += "&unidades="+$F('iUnidade');
  }
  if ($("iOrgao").value != "") {
    sQueryString += "&orgaos="+$F('iOrgao');
  }

  if ($F("iDepartamento") == "" && lMostra == false){
    $('iDepartamento').value = "";
    $('sDepartamento').value = "";

    return false;
  }

  $("iDivisao").value = "";
  $("sDivisao").value = "";

  sUrlLookup = "func_db_departorg.php?"+sQueryString+"&pesquisa_chave="+$F('iDepartamento')+"&funcao_js=parent.js_preencheDepartamento";
  if (lMostra) {
    var sUrlLookup = "func_db_departorg.php?"+sQueryString+"&funcao_js=parent.js_mostraDepartamento|coddepto|descrdepto";
  }
  js_OpenJanelaIframe('', 'db_iframe_db_departorg', sUrlLookup, 'Pesquisa Departamentos', lMostra);
}

function js_mostraDepartamento(iCodigoDepartamento, sDescricao) {

  $('iDepartamento').value = iCodigoDepartamento;
  $('sDepartamento').value = sDescricao;
  db_iframe_db_departorg.hide();
}

function js_preencheDepartamento(sDescricao, lErro) {

  $('sDepartamento').value = sDescricao;
  if (lErro) {
    $('iDepartamento').value = "";
  }
}

/**
 * Divisao
 */
function js_pesquisaDivisao(lMostra) {

  var sQueryString = "";
  if ($F('iDepartamento') != "") {
    sQueryString += "&departamentos="+$('iDepartamento').value;
  }

  sUrlLookup = "func_departdiv.php?"+sQueryString+"&pesquisa_chave="+$F('iDivisao')+"&funcao_js=parent.js_PreencheDivisao";
  if (lMostra) {
    var sUrlLookup = "func_departdiv.php?"+sQueryString+"&funcao_js=parent.js_mostraDivisao|t30_codigo|t30_descr";
  }
  js_OpenJanelaIframe('', 'db_iframe_departdiv', sUrlLookup, 'Pesquisa Divisão', lMostra);
}
function js_mostraDivisao(iCodigo, sDescricao) {

  $('iDivisao').value = iCodigo;
  $('sDivisao').value = sDescricao;
  db_iframe_departdiv.hide();
}
function js_PreencheDivisao(sDescricao, lErro) {

  $('sDivisao').value = sDescricao;
  if (lErro) {
    $('iDivisao').value = '';
  }
}

/**
 * Classificação do Bem
 */
function js_pesquisaClassificacaoInicial(lMostra) {

  var sUrlLookup = "func_clabens.php?funcao_js=parent.js_mostraClassificacaoInicial|t64_class";
  js_OpenJanelaIframe('', 'db_iframe_departdiv', sUrlLookup, 'Pesquisa Classificao Inicial', lMostra);

}
function js_mostraClassificacaoInicial(sClassificacaoInicial) {
  $('sClassificacaoInicial').value = sClassificacaoInicial;
  db_iframe_departdiv.hide();
}


function js_pesquisaClassificacaoFinal(lMostra) {

  var sUrlLookup = "func_clabens.php?funcao_js=parent.js_mostraClassificacaoFinal|t64_class";
  js_OpenJanelaIframe('', 'db_iframe_departdiv', sUrlLookup, 'Pesquisa Classificao Final', lMostra);

}
function js_mostraClassificacaoFinal(sClassificacaoFinal) {
  $('sClassificacaoFinal').value = sClassificacaoFinal;
  db_iframe_departdiv.hide();
}



/**
 * Pesquisa Bem Inicial
 */
function js_pesquisaBemInicial(lMostra) {

  var sUrlLookup = "func_bens.php?funcao_js=parent.js_mostraBemInicial|t52_bem";
  js_OpenJanelaIframe('', 'db_iframe_bens', sUrlLookup, 'Pesquisa Bem Inicial', lMostra);
}

function js_mostraBemInicial(iBemInicial) {
  $('iBemInicial').value = iBemInicial;
  db_iframe_bens.hide();
}


/**
 * Pesquisa Bem Final
 */
function js_pesquisaBemFinal(lMostra) {

  var sUrlLookup = "func_bens.php?funcao_js=parent.js_mostraBemFinal|t52_bem";
  js_OpenJanelaIframe('', 'db_iframe_bens', sUrlLookup, 'Pesquisa Bem Final', lMostra);
}

function js_mostraBemFinal(iBemFinal) {
  $('iBemFinal').value = iBemFinal;
  db_iframe_bens.hide();
}




/**
 * Pesquisa Placa
 */
function js_pesquisaPlacaInicial(lMostra) {

  var sUrlLookup = "func_bens.php?lRetornoPlaca=true&funcao_js=parent.js_mostraPlacaInicial|t52_ident";
  js_OpenJanelaIframe('', 'db_iframe_bensplacainicial', sUrlLookup, 'Pesquisa Placa Inicial', lMostra);
}

function js_mostraPlacaInicial(iPlacaInicial) {

  if (iPlacaInicial == "") {
    alert(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.bem_sem_placa'));
  }
  $('iPlacaInicial').value = iPlacaInicial;
  db_iframe_bensplacainicial.hide();
}

function js_pesquisaPlacaFinal(lMostra) {

  var sUrlLookup = "func_bens.php?lRetornoPlaca=true&funcao_js=parent.js_mostraPlacaFinal|t52_ident";
  js_OpenJanelaIframe('', 'db_iframe_bensplacafinal', sUrlLookup, 'Pesquisa Placa Final', lMostra);
}

function js_mostraPlacaFinal(iPlacaFinal) {
  $('iPlacaFinal').value = iPlacaFinal;
  db_iframe_bensplacafinal.hide();
}


/**
 * Pesquisa Convênio
 */
function js_pesquisaConvenio(lMostra) {


  var sUrlLookup = "func_benscadcedente.php?pesquisa_chave="+$F("iConvenio")+"&funcao_js=parent.js_preencheConvenio";

  if(lMostra){
    sUrlLookup = "func_benscadcedente.php?funcao_js=parent.js_mostraConvenio|t04_sequencial|z01_nome";
  }

  js_OpenJanelaIframe('', 'db_iframe_benscadcedente', sUrlLookup, 'Pesquisa Placa Final', lMostra);
}

function js_preencheConvenio(sDescricao,lErro){

  $("sConvenio").value = sDescricao;

  if(lErro){
    $("iConvenio").value = "";
  }


}

function js_mostraConvenio(iCodigo, sDescricao) {

  $('iConvenio').value = iCodigo;
  $('sConvenio').value      = sDescricao;
  db_iframe_benscadcedente.hide();
}
js_pesquisaSituacoes();
js_pesquisaDivisoesDepartamentos();
js_exibeFiltros();

Event.observe(document.body, 'keypress', teste);
function teste (event) {
  if (event.keyCode == 13) {
    return false;
  }
}

function js_ValidaValor(obj, event) {

  if ( js_countOccurs(obj.value, '.') > 1 ) {

    obj.value = js_getInputValue(obj.name);
    obj.focus();
    return false;
  }
}

</script>
<script>

$("iInventario").addClassName("field-size2");
$("iOrgao").addClassName("field-size2");
$("sOrgao").addClassName("field-size7");
$("iUnidade").addClassName("field-size2");
$("sUnidade").addClassName("field-size7");
$("iDepartamento").addClassName("field-size2");
$("sDepartamento").addClassName("field-size7");
$("iDivisao").addClassName("field-size2");
$("sDivisao").addClassName("field-size7");
$("sClassificacaoInicial").addClassName("field-size2");
$("sClassificacaoFinal").addClassName("field-size2");
$("iBemInicial").addClassName("field-size2");
$("iBemFinal").addClassName("field-size2");
$("iPlacaInicial").addClassName("field-size2");
$("iPlacaFinal").addClassName("field-size2");
$("iConvenio").addClassName("field-size2");
$("sConvenio").addClassName("field-size7");
$("nValorAquisicaoInicial").addClassName("field-size2");
$("nValorAquisicaoFinal").addClassName("field-size2");
$("dtAquisicaoInicial").addClassName("field-size2");
$("dtAquisicaoFinal").addClassName("field-size2");

</script>
