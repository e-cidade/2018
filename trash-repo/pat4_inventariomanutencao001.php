<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
        <legend>Manuten��o de Invent�rio</legend>
        <table border='0' class="form-container">
          <!-- Invent�rio / inventario / t75-->
          <tr>
            <td width="30%">
              <?
                db_ancora("<b>Invent�rio:</b>", "js_pesquisaInventario(true)", $oGet->db_opcao);
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
              Itens Vinculados:
            </td>
            <td>
              <select id='filtros' onchange="js_exibeFiltros();">
                <option value = '1' >SIM</option>
                <option value = '0' >N�O</option>
              </select>
            </td>
          </tr>  
        </table>
        
        
        
        <!-- Filtro dos bens -->
        <div id='ctnFiltros' style="display: none ;">
        <table border='0' class="form-container">
          <!-- Org�o -->
          <tr id='filtroParametroAtivoOrgao'>
            <td width="30%">
              <?
                db_ancora("<b>Org�o:</b>", "js_pesquisaOrgao(true)", $oGet->db_opcao);
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
          <!-- Divis�o -->
          <tr id='filtroDivisao'>
            <td>
              <?
                db_ancora("<b>Divis�o:</b>", "js_pesquisaDivisao(true)", $oGet->db_opcao);
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
          
          <!--  Conv�nio / benscadcedente / t04 -->
          <tr id='filtroConvenio'>
            <td>
              <?
                db_ancora("<b>Conv�nio:</b>", "js_pesquisaConvenio(true)", $oGet->db_opcao);
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
          
          <!--  Classifica��o/ clabens / t64 -->
          <tr id='filtroClassificacao'>
            <td nowrap="nowrap" title="<?=@$Tt64_class?>">
            	<?
	              db_ancora("<b>Classifica��o</b>","js_pesquisaClassificacaoInicial(true);",1);
	            ?>
            </td>
            <td>
              <?
  		          db_input('sClassificacaoInicial',10,$It64_codcla,true,'',1);
  		          db_ancora("<b>at�</b>","js_pesquisaClassificacaoFinal(true);",1);
                db_input('sClassificacaoFinal', 10, $It64_codcla, true, 'text', $oGet->db_opcao, "");
              ?>
            </td>
          </tr>
          <!--  C�digo Bem / bens / t52-->
          <tr id='filtroCodigo'>
            <td>
              <?
	              db_ancora("<b>C�digo do Bem</b>","js_pesquisaBemInicial(true);",1);
	            ?>
            </td>
            <td>
              <?
                db_input('iBemInicial', 10, $It52_bem, true, 'text', $oGet->db_opcao, "");
                db_ancora("<b>at�</b>","js_pesquisaBemFinal(true);",1);
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
                db_ancora("<b>at�</b>","js_pesquisaPlacaFinal(true);",1);
                db_input('iPlacaFinal', 10, $It41_placa, true, 'text', $oGet->db_opcao, "");
              ?>
            </td>
          </tr>
          <!-- Intervalo de Valor / bens / nValorAquisicao-->
          <tr id='filtroIntervalo'>
            <td>
              Intervalo de Valor:
            </td>
            <td>
              <?
                db_input('nValorAquisicaoInicial', 10, $It52_valaqu, true, 'text', $oGet->db_opcao, "");
              ?>
              <b>at�</b>
              <?
                db_input('nValorAquisicaoFinal', 10, $It52_valaqu, true, 'text', $oGet->db_opcao, "");
              ?>
            </td>
          </tr>
          <!-- Periodo Aquisi��o -->
          <tr id='filtroPeriodoAquisicaoInicial'>
            <td >
              Periodo de Aquisi��o Inicial:
            </td>
            <td>
              <?
                db_inputdata('dtAquisicaoInicial', "", "", "", true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr id='filtroPeriodoAquisicaoFinal'>
            <td >
              Periodo de Aquisi��o Final:
            </td>
            <td>
              <?
                db_inputdata('dtAquisicaoFinal', "", "", "", true, 'text', 1);
              ?>
            </td>
          </tr>
           <tr id='filtroTipo'>
            <td>
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
var aBensHint               = new Array();
/**
 *	vari�vel para guardar o estado de um input
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
  var oRetorno = eval("("+oAjax.responseText+")");

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
      sContent += "    <td align='center' class='bensSemInventario' height='20px' width='150px'><b>Bens Sem Invent�rio</b></td>";
      sContent += "    <td align='center' class='bensAtualizados'   height='20px' width='150px'><b>Atualizados neste invent�rio</b></td>";
      sContent += "    <td align='center' class='bensComInventario' height='20px' width='150px'><b>Atualizados em outro invent�rio</b></td>";
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
      aHeader[2]  = "Descri��o";
      aHeader[3]  = "Departamento/Divis�o";
      aHeader[4]  = "Situa��o";
      aHeader[5]  = "Vlr. Atual";
      aHeader[6]  = "Vlr. Residual";
      aHeader[7]  = "Vlr. Depreci�vel";
      aHeader[8]  = "Departamento";
      aHeader[9]  = "Divis�o";
      aHeader[10]  = "Vida �til";
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
  oGridBens.aHeaders[12].lDisplayed = false; // C�digo do Bem no InventarioBem
  oGridBens.aHeaders[13].lDisplayed = false; // Codigo do Inventario
  oGridBens.aHeaders[2].lDisplayed  = false; // C�digo do Bem
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

  oGridBens.clearAll(true);
  aBens.each(function (oItem, iIndice) {

  /* INPUT VALOR Depreciavel */
    var oValorAtual = eval("oTxtValorAtual"+oItem.codigo_bem+" = new DBTextField('oTxtValorAtual"+oItem.codigo_bem+"', 'oTxtValorAtual"+oItem.codigo_bem+"', '"+js_formatar(oItem.valor_atual, 'f')+"', 10)");
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
    var oValorDepreciavel = eval("oTxtValorDepreciavel"+oItem.codigo_bem+" = new DBTextField('oTxtValorDepreciavel"+oItem.codigo_bem+"', 'oTxtValorDepreciavel"+oItem.codigo_bem+"', '"+js_formatar(oItem.valor_depreciavel, 'f')+"', 10)");
    oValorDepreciavel.addEvent("onKeyPress", "return js_mask(event,\"0-9|,|-\")");
    oValorDepreciavel.addStyle('width', '100%');
    oValorDepreciavel.addStyle('height', '100%');
    oValorDepreciavel.addStyle('text-align', 'right');
    oValorDepreciavel.addStyle('border', '1px solid transparent');
    //oValorDepreciavel.addEvent("onFocus", "js_liberaDigitacao(this);");
    //oValorDepreciavel.addEvent("onBlur", "js_bloqueiaDigitacao(this);js_atualizaValor(this, event);");
    //oValorDepreciavel.addEvent("onChange",";js_valorAtualizado(this,"+oItem.codigo_bem+","+iIndice+"); js_atualizaValor(this, event);");
    //oValorDepreciavel.addEvent("onKeyUp",';js_ValidaCampos(this, 4,"Valor Depreci�vel" , "f", "f", event)');
    oValorDepreciavel.setReadOnly(true);



    /* INPUT VALOR RESIDUAL */
    var oValorResidual = eval("oTxtValorResidual"+oItem.codigo_bem+" = new DBTextField('oTxtValorResidual"+oItem.codigo_bem+"', 'oTxtValorResidual"+oItem.codigo_bem+"', '"+js_formatar(oItem.valor_residual, 'f')+"', 10)");
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
    var oComboBoxSituacao = eval("oComboBoxSituacao"+oItem.codigo_bem+" = new DBComboBox ('oComboBoxSituacao"+oItem.codigo_bem+"', 'oComboBoxSituacao"+oItem.codigo_bem+"', null, null);");
    oComboBoxSituacao.addItem('0', 'Selecione');
    aSituacaoBens.each(function(oSituacao, iIndex) {

      var aParametros = new Array();
      if (oSituacao.t70_situac == oItem.situacao) {
        // configuro o valor
        var oParametro   = new Object();
        oParametro.nome  = "selected";
        oParametro.valor = "selected";
        aParametros.push(oParametro);
      }
      oComboBoxSituacao.addItem(oSituacao.t70_situac, oSituacao.t70_descr.urlDecode(), null, aParametros);
    });
    oComboBoxSituacao.addEvent("onChange",";js_alteraSituacaoBem(this,"+oItem.codigo_bem+","+iIndice+")");
    oComboBoxSituacao.setDisable();

    /* BOX DEPARTAMENTO */
    var oComboBoxDepartamento = eval("oComboBoxDepartamento"+oItem.codigo_bem+" = new DBComboBox ('oComboBoxDepartamento"+oItem.codigo_bem+"', 'oComboBoxDepartamento"+oItem.codigo_bem+"', null, null);");

    var iCodigoDepartamento = oItem.codigo_departamento_bem;
    if (oItem.codigo_bem_inventario != null) {
      iCodigoDepartamento = oItem.departamento_inventario;
    }
    aDepartamentos.each(function(oDepartamento, iIndex) {

      var aParametros = new Array();
      if (oDepartamento.coddepto == iCodigoDepartamento) {

        var oParametro   = new Object();
        oParametro.nome  = "selected";
        oParametro.valor = "selected";
        aParametros.push(oParametro);
      }
      oComboBoxDepartamento.addItem(oDepartamento.coddepto, oDepartamento.descrdepto.urlDecode(), null, aParametros);
    });
    oComboBoxDepartamento.addEvent("onChange", ";js_pesquisaDivisaoDepartamento(this.value, oComboBoxDivisao"+oItem.codigo_bem+"); js_alterarDepartamento(this, "+oItem.codigo_bem+", "+iIndice+");");
    oComboBoxDepartamento.setDisable();

    /* BOX DIVISAO */
    var oComboBoxDivisao = eval("oComboBoxDivisao"+oItem.codigo_bem+" = new DBComboBox ('oComboBoxDivisao"+oItem.codigo_bem+"', 'oComboBoxDivisao"+oItem.codigo_bem+"', null, null);");
    var aDivisoes = new Array();
    if (aDivisaoPorDepartamento[iCodigoDepartamento] != null) {
      aDivisoes = aDivisaoPorDepartamento[iCodigoDepartamento];
    }
    if (aDivisoes.length == 0) {
      oComboBoxDivisao.addItem(0,"Sem Divis�o");
    } else {

      var iDivisao = oItem.codigo_divisao_bem;
      if (oItem.codigo_bem_inventario != null) {
        iDivisao = oItem.divisao_inventario;
      }
      aDivisoes.each(function (oDivisao, iIndiceDivisao){
        var aParametros = new Array();
        if (oDivisao.t30_codigo == iDivisao) {

          var oParametro   = new Object();
          oParametro.nome  = "selected";
          oParametro.valor = "selected";
          aParametros.push(oParametro);
        }
        oComboBoxDivisao.addItem(oDivisao.t30_codigo, oDivisao.t30_descr.urlDecode(), null, aParametros);
      });
    }
    oComboBoxDivisao.addEvent("onChange", ";js_alterarDivisao(this, "+oItem.codigo_bem+", "+iIndice+");");
    oComboBoxDivisao.setDisable();

    var sDepartamentoDivisao = oItem.descricao_departamento_bem.urlDecode();
    if (oItem.descricao_divisao_bem != null && oItem.descricao_divisao_bem != "") {
      sDepartamentoDivisao += " / "+oItem.descricao_divisao_bem.urlDecode();
    }

    /* INPUT VIDA UTIL */
    var oVidaUtil = eval("oTxtVidaUtil"+oItem.codigo_bem+" = new DBTextField('oTxtVidaUtil"+oItem.codigo_bem+"', 'oTxtVidaUtil"+oItem.codigo_bem+"', '"+oItem.vida_util+"', 5)");
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
    var oBemInvetario = eval("oTxtBemInventario"+oItem.codigo_bem+" = new DBTextField('oTxtBemInventario"+oItem.codigo_bem+"', 'oTxtBemInventario"+oItem.codigo_bem+"', '"+oItem.codigo_bem_inventario+"', 5)");
    oBemInvetario.setReadOnly(true);

    var iCodigoBemInventario = oItem.codigo_bem_inventario;
    if (oItem.codigo_bem_inventario == null || oItem.codigo_bem_inventario == 'null') {
      iCodigoBemInventario = '0';
    }
    var iCodigoInventario = oItem.codigo_inventario;
    if (oItem.codigo_inventario == null || oItem.codigo_inventario == 'null') {
      iCodigoInventario = '0';
    }

    var aLinha     = new Array();
        aLinha[0]  = oItem.placa.urlDecode();
        aLinha[1]  = oItem.codigo_bem;
        aLinha[2]  = oItem.descricao.urlDecode();
        aLinha[3]  = sDepartamentoDivisao;
        aLinha[4]  = oComboBoxSituacao.toInnerHtml();
        aLinha[5]  = oValorAtual.toInnerHtml();  //atual
        aLinha[6]  = oValorResidual.toInnerHtml();    //residual
        aLinha[7]  = oValorDepreciavel.toInnerHtml();  //depreciavel
        aLinha[8]  = oComboBoxDepartamento.toInnerHtml();
        aLinha[9]  = oComboBoxDivisao.toInnerHtml();
        aLinha[10] = oVidaUtil.toInnerHtml();
        aLinha[11] = iCodigoBemInventario;
        aLinha[12] = iCodigoInventario;

    var lCheckBoxBloqueado = false;
    if (oItem.codigo_inventario != null && oItem.codigo_inventario != $F("iInventario")) {
      lCheckBoxBloqueado = true;
    }
    oGridBens.addRow(aLinha, false, lCheckBoxBloqueado);

    oItem.departamento_divisao = sDepartamentoDivisao;
    aBensHint[iIndice] = oItem;

    var oRowAdicionado = oGridBens.aRows[iIndice];

    if (iCodigoBemInventario != "0" && oItem.codigo_inventario == $F("iInventario")) {
      oRowAdicionado.setClassName('bensAtualizados');
    } else if (oItem.codigo_inventario != $F("iInventario") && iCodigoBemInventario != "0") {
      oRowAdicionado.setClassName('bensComInventario');
    }
  });
  oGridBens.renderRows();

  oGridBens.aRows.each(function (aRow, iIndice) {

    var sIdCellValorAtual       = aRow.aCells[5].sId;
    var sIdCellValorDepreciavel = aRow.aCells[6].sId;
    var sIdCellValorResidual    = aRow.aCells[7].sId;
    var sIdCellVidaUtil         = aRow.aCells[10].sId;
    var iCodigoBem              = aRow.aCells[2].getValue();

    if (aRow.aCells[12].getValue() != "0" && aRow.aCells[13].getValue() == $F("iInventario")) {

      $(sIdCellValorAtual).style.backgroundColor                  = sCorBensAtualizados;
      $("oTxtValorAtual"+iCodigoBem).style.backgroundColor        = sCorBensAtualizados;
      $(sIdCellValorDepreciavel).style.backgroundColor            = sCorBensAtualizados;
      $("oTxtValorDepreciavel"+iCodigoBem).style.backgroundColor  = sCorBensAtualizados;
      $(sIdCellValorResidual).style.backgroundColor               = sCorBensAtualizados;
      $("oTxtValorResidual"+iCodigoBem).style.backgroundColor     = sCorBensAtualizados;
      $(sIdCellVidaUtil).style.backgroundColor                    = sCorBensAtualizados;
      $("oTxtVidaUtil"+iCodigoBem).style.backgroundColor          = sCorBensAtualizados;

    } else if (aRow.aCells[13].getValue() != $F("iInventario") && aRow.aCells[13].getValue() != "0") {

      $(sIdCellValorAtual).style.backgroundColor                  = sCorBensComInventario;
      $("oTxtValorAtual"+iCodigoBem).style.backgroundColor        = sCorBensComInventario;
      $(sIdCellValorDepreciavel).style.backgroundColor            = sCorBensComInventario;
      $("oTxtValorDepreciavel"+iCodigoBem).style.backgroundColor  = sCorBensComInventario;
      $(sIdCellValorResidual).style.backgroundColor               = sCorBensComInventario;
      $("oTxtValorResidual"+iCodigoBem).style.backgroundColor     = sCorBensComInventario;
      $(sIdCellVidaUtil).style.backgroundColor                    = sCorBensComInventario;
      $("oTxtVidaUtil"+iCodigoBem).style.backgroundColor          = sCorBensComInventario;
    }
  });

  /**
   * Adicionamos o DBHINT em cada linha da grid
   */
  aBensHint.each(function(oBem, iIndice) {

    var sTextEvent  = "<b>Bem: </b>"+oBem.codigo_bem+" - "+oBem.descricao.urlDecode()+"<br>";
        sTextEvent += "<b>Departamento/Divis�o: </b>"+oBem.departamento_divisao+"<br>";
    if (oBem.codigo_inventario != null) {
      sTextEvent += "<b>Invent�rio: </b>"+oBem.codigo_inventario+"<br>";
    }

    var aEventsIn   = ["onmouseover"];
    var aEventsOut  = ["onmouseout"];
    var oDBHint     = eval("oDBHint_"+oBem.codigo_bem+" = new DBHint('oDBHint_"+oBem.codigo_bem+"')");
    oDBHint.setText(sTextEvent);
    oDBHint.setShowEvents(aEventsIn);
    oDBHint.setHideEvents(aEventsOut);
    oDBHint.make($(oGridBens.aRows[iIndice].sId));
  });
}

function js_alterarDepartamento(oComboBoxDepartamento, iCodigoBem, iCodigoLinha) {

  js_divCarregando(_M('patrimonial.patrimonio.pat4_inventariomanutencao001.salvando_informacoes'), "msgBox");
  var oParam                 = new Object();
  oParam.exec                = "alterarDepartamento";
  oParam.iCodigoDepartamento = oComboBoxDepartamento.getValue();
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
  oParam.iCodigoDivisao      = oComboBoxDivisao.getValue();
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

  // js_divCarregando("Aguarde, salvando informa��o...", "msgBox");
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
  oParam.iSituacao         = oComboBoxSituacao.getValue();
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
                                   oObjetoDivisao.clearItens();
                                   oObjetoDivisao.addItem('0', 'Sem Divis�o', null, aParametros);
                                   if (oRetorno.aDivisaoDepartamento.length == 0) {
                                     oObjetoDivisao.setValue(0);
                                     return;
                                   }

                                   var oParametros   = new Object();
                                   oParametros.nome  = "selected";
                                   oParametros.valor = "selected";
                                   var aParametros = new Array(oParametros);
                                   oRetorno.aDivisaoDepartamento.each(function (oDivisao, iIndice){
                                     oObjetoDivisao.addItem(oDivisao.t30_codigo, oDivisao.t30_descr.urlDecode());
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
 * Pesquisa de Invent�rio
 */
function js_pesquisaInventario(lMostra){

  var sUrlLookUp = "func_inventario.php?situacao=1&funcao_js=parent.js_mostraInventario|t75_sequencial";
  js_OpenJanelaIframe('', 'db_iframe_inventario', sUrlLookUp, 'Pesquisa Inventar�rio', lMostra);
}

function js_mostraInventario(iInventario) {
  db_iframe_inventario.hide();
  $('iInventario').value = iInventario;
}

/**
 * Pesquisa de Org�o
 */
function js_pesquisaOrgao(lMostra) {

    var sUrlLookUp = "func_db_departorg_orcorgao.php?";

    if (lMostra) {
      sUrlLookUp += "lBuscaCampoOrgao=true&funcao_js=parent.js_mostraOrgao|db01_orgao|o40_descr";
    } else {

      sValorPesquisa = $F('iOrgao');
      sUrlLookUp    += "lBuscaCampoOrgao=true&pesquisa_chave="+sValorPesquisa+"&funcao_js=parent.js_preencheOrgao";
    }
    js_OpenJanelaIframe('', 'db_iframe_db_departorg', sUrlLookUp, 'Pesquisa Tipo de Org�o', lMostra);
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
  js_OpenJanelaIframe('', 'db_iframe_departdiv', sUrlLookup, 'Pesquisa Divis�o', lMostra);
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
 * Classifica��o do Bem
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
 * Pesquisa Conv�nio
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