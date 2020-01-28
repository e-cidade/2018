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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_acordo_classe.php");
require_once("classes/db_acordoparalisacao_classe.php");
require_once("classes/db_acordomovimentacao_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$oRotuloCampo = new rotulocampo();
$oDaoAcordo = new cl_acordo();
$oDaoAcordoParalisacao = new cl_acordoparalisacao();

$oDaoAcordo->rotulo->label();
$oDaoAcordoParalisacao->rotulo->label();
$oRotuloCampo->label("ac16_sequencial");
$oRotuloCampo->label("ac16_resumoobjeto");
$oRotuloCampo->label("ac10_obs");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php db_app::load("estilos.css, grid.style.css, scripts.js, strings.js, prototype.js, datagrid.widget.js"); ?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
  <div class="container">

    <fieldset>
    
      <legend><strong id="descricaoRotina">Reativar Acordo Paralisado</strong></legend>
      
      <table align="center" border="0">

        <tr>
          <td title="<?php echo $Tac16_sequencial; ?>">
            <?php 
              if ($dbopcao == 1 ) {
                db_ancora($Lac16_sequencial, "js_pesquisaParalisacao(true);", 1);
              } else {
                db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);", 1);
              }              
            ?>
          </td>
          <td>
            <?php
              //db_input('ac16_sequencial', 10, $Iac16_sequencial,true,'text', 1," onchange='js_pesquisaac16_sequencial(false);'");
              //db_input('ac16_sequencial', 10, $Iac16_sequencial,true,'text', 1," onchange='js_pesquisaac16_sequencial(false);'");
              
              if ($dbopcao == 1 ) {
                db_input('ac16_sequencial',10,$Iac16_sequencial,true,'text', 1," onchange='js_pesquisaParalisacao(false);'");
              } else {
                db_input('ac16_sequencial',10,$Iac16_sequencial,true,'text', 1," onchange='js_pesquisaac16_sequencial(false);' ");
              }              
              
              
              db_input('ac16_resumoobjeto', 40, $Iac16_resumoobjeto,true,'text',3);
            ?>
          </td>
        </tr>
        
        <tr>
          <td><strong>Data Paralisação:</strong></td>
          <td>
            <?php db_inputdata('ac47_datainicio', null, null, null, true, 'text', 3); ?>
          </td>
        </tr>

        <tr>
          <td><strong>Data Retorno:</strong></td>
          <td>
            <?php db_inputdata('ac47_datafim', null, null, null, true, 'text', $oGet->dbopcao == 2 ? 3 : $oGet->dbopcao, "onChange='js_buscarPeriodos();'","","","parent.js_buscarPeriodos();"); ?>
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <fieldset class="subcontainer">
              <legend><b>Observação</b></legend>
              <?php db_textarea('ac10_obs', 3, 64, $Iac10_obs, true, 'text', 1, ""); ?>
            </fieldset>
          </td>
        </tr> 

        <tr>
          <td colspan="2">
            <fieldset class="subcontainer">
              <legend><strong id="tituloPeriodos">Períodos Paralisados</strong></legend>
              <div id="ctnPeriodos"></div>
            </fieldset>
          </td>
        </tr> 

      </table>
      
    </fieldset>
          
    <input type="button" value="Processar" disabled id="processar" onClick="return js_processar();" />
    <!-- <input type="button" value="Pesquisar Acordos" id="pesquisar" onClick="return js_pesquisar();" />-->
    
    <input type="button" value="Consultar Acordo" id="pesquisar" onClick="return js_verAcordo();" />

  </div>

  <?php db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit")); ?>

</body>
</html>
<script type="text/javascript">

const MENSSAGENS = "patrimonial.contratos.aco4_acordoreativacao.";
const RPC        = 'aco4_acordo.RPC.php';
const DB_OPCAO   = js_urlToObject().dbopcao;

var oGridPeriodos = new DBGrid('gridPeriodos');
oGridPeriodos.nameInstance = 'oGridPeriodos';
oGridPeriodos.setCellAlign(['center', 'center', 'center', 'center', 'center']);
oGridPeriodos.setCellWidth(['1%', '14%', '35%', '25%', '25%']);
oGridPeriodos.setHeader(['codigo', 'Período', 'Descrição', 'Data inicial', 'Data final']);
oGridPeriodos.setHeight(100);
oGridPeriodos.aHeaders[0].lDisplayed = false;
oGridPeriodos.show($('ctnPeriodos'));
oGridPeriodos.clearAll(true); 


switch ( DB_OPCAO ) {

  case '1' :
    js_pesquisaParalisacao(true);
  break;


  case '2' :

    $('descricaoRotina').innerHTML = 'Cancelar Reativação de Acordo';
    $('tituloPeriodos').innerHTML  = 'Períodos Reativados';
    $('processar').value           = 'Cancelar Reativação';
    js_pesquisaac16_sequencial(true);
  break;  
  
}

/**
 * Pesquisa acordos Homologados para serem paralisados
 */
function js_pesquisaac16_sequencial(lMostrar) {

  var sTituloJanela = 'Pesquisar Acordos Homologados';
  
  if (lMostrar == true) {
    
    var sUrl = 'func_acordo.php?funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto&iTipoFiltro=4';
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_acordo', 
                        sUrl,
                        sTituloJanela,
                        true);
  } else {
  
    if ($('ac16_sequencial').value != '') { 
    
      var sUrl = 'func_acordo.php?descricao=true&pesquisa_chave='+$('ac16_sequencial').value+
                 '&funcao_js=parent.js_mostraacordo&iTipoFiltro=4';
                 
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_acordo',
                          sUrl,
                          sTituloJanela,
                          false);
     } else {
       $('ac16_sequencial').value = ''; 
     };
  };
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo(chave1,chave2,erro) {
 
  if (erro == true) {
   
    $('ac16_sequencial')  .value = ''; 
    $('ac47_datainicio')  .value = '';
    $('ac47_datafim')     .value = '';
    $('ac16_resumoobjeto').value = chave1;
    $('ac16_sequencial')  .focus(); 
  } else {
  
    $('ac16_sequencial').value   = chave1;
    $('ac16_resumoobjeto').value = chave2;
    js_buscarDadosParalisacao();
  }
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraacordo1(chave1,chave2) {

  $('ac16_sequencial').value    = chave1;
  $('ac16_resumoobjeto').value  = chave2;
  js_buscarDadosParalisacao();
  db_iframe_acordo.hide();
}


/**
 * Pesquisa acordos Paralisados para serem reativados
 */
function js_pesquisaParalisacao(lMostrar) {

  var sTituloJanela = 'Pesquisar Acordos Paralisados';
  
  if (lMostrar == true) {
    
    var sUrl = 'func_acordo.php?funcao_js=parent.js_mostraParalisacao1|ac16_sequencial|ac16_resumoobjeto&iTipoFiltro=5';
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_acordoParalisado', 
                        sUrl,
                        sTituloJanela,
                        true);
  } else {
    
    if ($('ac16_sequencial').value != '') { 
      
      var sUrl = 'func_acordo.php?descricao=true&pesquisa_chave='+$('ac16_sequencial').value+
                 '&funcao_js=parent.js_mostraParalisacao&iTipoFiltro=5';
                 
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_acordoParalisado',
                          sUrl,
                          sTituloJanela,
                          false);
     } else {
       $('ac16_sequencial').value = ''; 
     };

  };
}

/**
 * Retorno da pesquisa acordos
 */
function js_mostraParalisacao(chave1,chave2,erro) {
 
  if (erro == true) {
    
    $('ac16_sequencial').value   = ''; 
    $('ac16_resumoobjeto').value = chave1;
    $('ac16_sequencial').focus(); 
    $('ac47_datainicio') . value = '';
    $('ac10_obs')        . value = '';
  } else {

    $('ac16_sequencial').value   = chave1;
    $('ac16_resumoobjeto').value = chave2;
    js_buscarDadosParalisacao();
  }
}

/**
 * Retorno da pesquisa acordos paralisados
 */
function js_mostraParalisacao1(chave1,chave2) {

  $('ac16_sequencial').value    = chave1;
  $('ac16_resumoobjeto').value  = chave2;
  db_iframe_acordoParalisado.hide();
  js_buscarDadosParalisacao();
}



function js_buscarDadosParalisacao() {

  js_divCarregando(_M(MENSSAGENS + 'buscando_dados_paralisacao'), 'msgBox');
  var oParametros = {exec: "getDadosParalisacao", iAcordo: $("ac16_sequencial").value};
  var oRequisicao = {method: 'post', parameters: 'json=' + js_objectToJson(oParametros), onComplete: retorno};
  var oAjax       = new Ajax.Request(RPC, oRequisicao);

  function retorno(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    var sMensagem = oRetorno.message.urlDecode();

    if (oRetorno.status > 1) {
      return alert(sMensagem);
    }

    $('ac47_datainicio').value = oRetorno.oDados.dtInicial;
    $('ac47_datafim').value = oRetorno.oDados.dtTermino; 
    js_buscarPeriodos();
  }
} 

function js_verAcordo() {

  var iAcordo  = $("ac16_sequencial").value;

  if (empty(iAcordo)) {
    return alert(_M(MENSSAGENS + 'acordo_nao_selecionado'));
  }

  js_OpenJanelaIframe('top.corpo', 'db_iframe_consultaacordo', 'con4_consacordos003.php?ac16_sequencial=' + iAcordo, 'Consulta Dados Acordo', true);
}



function js_buscarPeriodos() {

  /**
   * Limpa grid dos periodos
   */
  oGridPeriodos.clearAll(true);

  var dtInicial = js_formatar($('ac47_datainicio').value, 'd');
  var dtTermino = js_formatar($('ac47_datafim').value, 'd');

  if ( empty(dtInicial) || empty(dtTermino) ) {
    return false;
  }
  
  /**
   * Valida se data de retorno e maior que a de inicio da paralisacao
   * retorn 'i' quando datas são iguais
   */
  var mDiferenca = js_diferenca_datas(dtInicial, dtTermino, 3);

  if (mDiferenca && mDiferenca != 'i') {

    alert(_M(MENSSAGENS + 'data_retorno_menor_inicial'));
    $('ac47_datafim').value = '';
    return false;
  }

  var oParametros       = new Object();
  oParametros.exec      =  DB_OPCAO == 1 ? "buscarPeriodos" : "getPeriodosReativados";
  oParametros.iAcordo   = $("ac16_sequencial").value;
  oParametros.dtInicial = js_formatar($("ac47_datainicio").value, 'd');
  oParametros.dtTermino = js_formatar($("ac47_datafim").value, 'd');

  js_divCarregando(_M(MENSSAGENS + 'buscando_periodos'), 'msgBox');

  var oRequisicao = {method: 'post', parameters: 'json=' + js_objectToJson(oParametros), onComplete: retorno};
  var oAjax       = new Ajax.Request(RPC, oRequisicao);

  function retorno(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    var sMensagem = oRetorno.message.urlDecode();

    if (oRetorno.status > 1) {
      return alert(sMensagem + '\nOu sem reativação a cancelar. ');
    } 
    
    oRetorno.aPeriodos.each(function(oPeriodo, iIndice) {

      var aColunas = [];
      aColunas[0] = oPeriodo.iCodigo;
      aColunas[1] = oPeriodo.iNumero;
      aColunas[2] = oPeriodo.sDescricao.urlDecode();
      aColunas[3] = oPeriodo.dtInicial.urlDecode();
      aColunas[4] = oPeriodo.dtTermino.urlDecode();
      oGridPeriodos.addRow(aColunas, null, false, true);
    });

    $('processar').disabled = false;
    oGridPeriodos.renderRows();
  }
}

function js_processar() {

  var iAcordo = $("ac16_sequencial").value;
  var sObservacao = $("ac10_obs").value;

  if (empty(iAcordo)) {
    return alert(_M(MENSSAGENS + 'paralisacao_nao_selecionada'));
  }

  if (empty($('ac47_datafim').value)) {
    return alert(_M(MENSSAGENS + 'data_termino_paralisacao_nao_informada'));
  }

  if (empty(sObservacao)) {
    return alert(_M(MENSSAGENS + 'observacao_nao_informada'));
  }

  var aCodigoPeriodos = [];

  oGridPeriodos.aRows.each(function(oLinha, iLinha) {
    aCodigoPeriodos.push(oLinha.aCells[0].getValue());
  });

  if (aCodigoPeriodos.length == 0) {
    return alert(_M(MENSSAGENS + 'nenhum_periodo_processar'));
  }

  var oParametros         = new Object();
  oParametros.exec        = DB_OPCAO == 1 ? "reativarAcordo" : "cancelarReativacao";
  oParametros.iAcordo     = iAcordo;
  oParametros.dtRetorno   = js_formatar($("ac47_datafim").value, 'd');
  oParametros.sObservacao = sObservacao; 
  oParametros.aPeriodos   = aCodigoPeriodos;

  js_divCarregando(_M(MENSSAGENS + (DB_OPCAO == 1 ? 'reativando_acordo' : 'cancelando_reativacao')), 'msgBox');
  
  var oRequisicao = {method: 'post', parameters: 'json=' + js_objectToJson(oParametros), onComplete: retorno};
  var oAjax       = new Ajax.Request(RPC, oRequisicao);

  function retorno(oAjax) {

    js_removeObj('msgBox');
    var oRetorno = eval("("+oAjax.responseText+")");
    var sMensagem = oRetorno.message.urlDecode();

    if (oRetorno.status > 1) {
      return alert(sMensagem);
    }

    alert(sMensagem);
    document.location.href = document.location.href;
  }
}

</script>
