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
require_once("libs/exceptions/DBException.php");
require_once("libs/exceptions/BusinessException.php");

$oRotuloOrcobjetivo = new rotulo("orcobjetivo");
$oRotuloOrcobjetivo->label();

$oRotuloOrgao = new rotulo("orcorgao");
$oRotuloOrgao->label();

$oRotuloMeta = new rotulo("orcmeta");
$oRotuloMeta->label();

$oRotuloMeta = new rotulo("orciniciativa");
$oRotuloMeta->label();
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/datagrid.widget.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/widgets/DBAbas.widget.js"></script>
    <link href="estilos.css"             rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css"  rel="stylesheet" type="text/css">
    <link href="estilos/DBtab.style.css" rel="stylesheet" type="text/css">
  </head>
  <style>

  .divAbas {
    postion:relative;
    left:5px;
  }


  </style>

  <body bgcolor="#CCCCCC" style="margin-top: 25px" >

  <div id="ctnAbasObjetivo" class='divAbas'></div>

    <div id="ctnObjetivo">
      <center>
          <div  style="width: 560px;">
            <fieldset style=" height: 340px;">
              <legend>Cadastro de Objetivo</legend>
              <table border="0">

                <!-- C�digo Objetivo -->
                <tr>
                  <td nowrap>
                   <?php
                    db_ancora("<b>C�digo do Objetivo:</b>", "js_pesquisaObjetivo(true)", 1);
                    ?>
                  </td>

                  <td>
                    <?php
                    db_input('o143_sequencial', 10, $Io143_sequencial,  true, 'text', 3);
                    ?>
                  </td>
                </tr>
                <!-- Descri��o Objetivo -->

                <tr>
                  <td>
                    <b>Descri��o:</b>
                  </td>

                  <td>
                    <?php
                    db_input('o143_descricao', 60, $Io143_descricao, true, 'text',1);
                    ?>
                  </td>
                </tr>
                <!-- Orgao-->
                <tr>
                  <td>
                    <?php
                    db_ancora("<b>�rg�o:</b>", "js_pesquisaOrgao(true)", 1);
                    ?>
                  </td>

                  <td>
                    <?php
                    db_input("o40_orgao", 10, $Io40_orgao, true, 'text', 1, "onchange=js_pesquisaOrgao(false);");
                    db_input('o40_descr', 46, $Io40_descr, true, 'text', 3);
                    ?>
                  </td>
                </tr>

                <!-- Objetivo -->
                <tr>
                  <td colspan="3">
                    <fieldset style=" height: 240px;">
                      <legend>Objetivo</legend>
                        <?php
                        db_textarea("o143_objetivo", 15, 66, "", true, "text", 1);
                        ?>
                      </fieldset>
                  </td>
                </tr>
              </table>
            </fieldset>
          </div>
          <input type="button" id="btnObjetivoSalvar"  name="btnObjetivoSalvar"  value="Salvar" onclick="js_salvarObjetivo();"/>
          <input type="button" id="btnObjetivoExcluir" name="btnObjetivoExcluir" value="Excluir"  onclick="js_excluirObjetivo()"/>
          <input type="button" id="btnObjetivoNovo"    name="btnObjetivoNovo"    value="Novo" onclick="js_limpaTela()"/>
      </center>
    </div>

    <div id="ctnMetas">
      <center>
          <div  style="width: 560px;">
            <fieldset style=" height: 340px;">
              <legend>Cadastro de Metas</legend>
              <table border="0">

                <tr>
                  <td>
                    <b>C�digo do Objetivo:</b>
                  </td>

                  <!-- Iniciativa - Objetivo -->
                  <td>
                  <?php
                  db_input("meta_o143_sequencial", 10, $Io143_sequencial, true, 'text', 3);
                  ?>
                  </td>
                </tr>

                <!-- Meta-->
                <tr>
                  <td>
                    <b>C�digo da Meta:</b>
                  </td>

                  <td>
                    <?php
                    db_input("o145_sequencial", 10, $Io145_sequencial, true, 'text', 3);
                    ?>
                  </td>
                </tr>

                <tr>
                  <td>
                  <b>Descri��o:</b>
                  </td>

                  <td>
                    <?php
                    db_input('o145_descricao', 46, $Io145_descricao, true, 'text', 1);
                    ?>
                  </td>
                </tr>

                <tr>
                  <td colspan="3">
                    <fieldset style=" height: 240px;">
                      <legend>Meta:</legend>
                        <?php
                        db_textarea("o145_meta", 15, 66, "", true, "text", 1);
                        ?>
                      </fieldset>
                  </td>
                </tr>
              </table>
            </fieldset>
          </div>
          <input
            type    = "button"
            id      = "btnSalvar"
            name    = "btnSalvar"
            value   = "Salvar"
            onclick = "js_salvarMeta();"
          />
          <input type="button" id="btnMetaNovo" name="btnMetaNovo"    value="Novo" onclick="js_limpaTelaMeta()"/>
      </center>

      <div  style="width: 560px; margin:0 auto; margin-top:10px;">

        <fieldset style=" height:300px;">
          <legend><b>Metas vinculadas ao Objetivo</b></legend>
          <div id="ctnGridMetas"  >
          </div>
        </fieldset>

        <center>
        <input
          type    = "button"
          id      = "btnMetaExcluir"
          name    = "btnMetaExcluir"
          value   = "Excluir Selecionados"
          onclick = "js_excluirMetas()"
        />
        </center>
      </div>
    </div>

    <div id="ctnIniciativas">
      <center>
        <div id="ctnFormularioIniciativa">
          <div  style="width: 580px;">
            <fieldset style=" height: 360px;">
              <legend>Cadastro de Iniciativas</legend>
              <table border="0">

                <tr>
                  <td>
                    <b>C�digo do Objetivo:</b>
                  </td>

                  <!-- Iniciativa - Objetivo -->
                  <td>
                  <?php
                  db_input("iniciativa_o143_sequencial", 10, $Io143_sequencial, true, 'text', 3);
                  ?>
                  </td>
                </tr>

                <!-- Iniciativa - Meta-->
                <tr>
                  <td>
                    <?php
                    db_ancora("<b>C�digo da Meta:</b>", "js_pesquisaMeta(true);", 1);
                    ?>
                  </td>

                  <td>
                    <?php
                    db_input("iniciativa_o147_orcmeta", 10, $Io147_orcmeta, true, 'text', 1, "onchange = js_pesquisaMeta(false);");
                    db_input("iniciativa_o145_descricao", 32, $Io145_descricao, true, 'text', 3);
                    ?>
                  </td>
                </tr>

                <tr>

                  <td>
                    <b>C�digo da Iniciativa:</b>
                  </td>

                  <td>
                  <?php
                  db_input("iniciativa_o147_sequencial", 10, $Io147_sequencial, true, 'text', 3);
                  ?>
                  </td>
                </tr>

                <tr>
                  <td>
                    <b>Ano</b>
                  </td>

                  <td>
                    <?php db_input("iniciativa_o147_ano", 10, $Io147_ano, true, 'text', 1); ?>
                  </td>
                </tr>

                <tr>
                  <td>
                  <b>Descri��o:</b>
                  </td>

                  <td>
                    <?php
                    db_input('o147_descricao', 46, $Io147_descricao, true, 'text', 1);
                    ?>
                  </td>
                </tr>

                <tr>
                  <td colspan="3">
                    <fieldset style=" height: 220px;">
                      <legend>Iniciativa:</legend>
                        <?php
                        db_textarea("iniciativa_o147_iniciativa", 14, 66, "", true, "text", 1);
                        ?>
                      </fieldset>
                  </td>
                </tr>
              </table>
            </fieldset>
          </div>
          <input type="button" id="btnIniciativaSalvar"  name="btnIniciativaSalvar"  value="Salvar" onclick="js_salvarIniciativa();"/>
          <input type="button" id="btnIniciativaNovo"    name="btnIniciativaNovo"    value="Novo" onclick="js_limpaTelaIniciativa()"/>
        </div>

        <div  style="width: 560px; margin-top:10px;">
          <fieldset style=" height:300px;">
            <legend><b>Iniciativas vinculadas a Meta</b></legend>
            <div id="ctnGridIniciativa">
            </div>
          </fieldset>
          <input
            type    = "button"
            id      = "btnIniciativaExcluir"
            name    = "btnIniciativaExcluir"
            value   = "Excluir Selecionados"
            onclick = "js_excluirIniciativas()"/>
        </div>
      </center>
    </div>
  </body>
</html>
<?php
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>

<script>


$('iniciativa_o147_iniciativa').setAttribute("onblur",   "");
$('iniciativa_o147_iniciativa').setAttribute("onkeyup", "");
$('iniciativa_o147_iniciativa').setAttribute("style",   "");

$('o145_meta').setAttribute("onblur", "");
$('o145_meta').setAttribute("onkeyup", "");
$('o145_meta').setAttribute("style", "");

$('o143_objetivo').setAttribute("onblur", "");
$('o143_objetivo').setAttribute("onkeyup", "");
$('o143_objetivo').setAttribute("style", "");


var oDBAba          = new DBAbas( $('ctnAbasObjetivo') );
var oAbaObjetivo    = oDBAba.adicionarAba("Objetivo", $('ctnObjetivo') );
var oAbaMetas       = oDBAba.adicionarAba("Metas", $('ctnMetas'));
var oAbaIniciativas = oDBAba.adicionarAba("Iniciativas", $('ctnIniciativas'));
var sClickMeta      = $("Metas").onclick;

$("Metas").onclick = function() {

  if (js_liberaAba()) {

    sClickMeta();
    js_buscaMetas();
    $('meta_o143_sequencial').value = $F('o143_sequencial');
  }
};


var sClickIniciativa = $("Iniciativas").onclick;

$("Iniciativas").onclick = function() {

  if (js_liberaAba()) {

    sClickIniciativa();
    js_limpaTelaIniciativa();
    $('iniciativa_o143_sequencial').value = $F('o143_sequencial');
  }

  $('iniciativa_o147_orcmeta').value = "";
  $('iniciativa_o145_descricao').value = "";
  js_limpaTelaIniciativa();
  oGridIniciativas.clearAll(true);

};

function js_liberaAba() {

  if ($F("o143_sequencial")) {
    return true;
  }
  return false;
}

/**
 *  1 - Defini��es da Aba Objetivo
 **/

var sUrl = "orc1_programa.RPC.php";

function js_salvarObjetivo() {

  if (!$F('o143_descricao')) {

    alert("Descri��o sucinta do Objetivo n�o informada.");
    $('o143_descricao').focus();
    return false;
  }

  if(!$F('o40_orgao')) {

    alert("�rg�o do Objetivo n�o informado.");
    $('o40_orgao').focus();
    return false;
  }

  if (!$F('o143_objetivo')) {

    alert("Descri��o completa do Objetivo n�o informada.");
    $('o143_objetivo').focus();
    return false;
  }



  if(!confirm("Deseja Salvar o Objetivo?")) {
    return false;
  }

  var oParametro             = new Object();
  oParametro.exec            = "salvarObjetivo";
  oParametro.iCodigoObjetivo = $F('o143_sequencial');
  oParametro.sDescricao      = $F('o143_descricao');
  oParametro.sObjetivo       = encodeURIComponent(tagString($F('o143_objetivo')));
  oParametro.iOrgao          = $F('o40_orgao');

  js_divCarregando("Aguarde, salvando objetivo...", "msgBox");

  var oAjax = new Ajax.Request(sUrl, {
                                        method:'post',
                                			  parameters:'json='+Object.toJSON(oParametro),
                                			  onComplete: js_retornoSalvarObjetivo
                                			}
                               );
}

function js_retornoSalvarObjetivo(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");

  alert(oRetorno.sMessage.urlDecode());
  $("o143_sequencial").value = oRetorno.iCodigoObjetivo;
}

function js_pesquisaOrgao(lMostra) {

  var sUrlOrgao = "func_orcorgao.php?lFiltraInstituicao=true&funcao_js=parent.js_preencheOrgao|o40_orgao|o40_descr";

  if (!lMostra) {
    var iCodigoOrgao = $F("o40_orgao");
    sUrlOrgao    = "func_orcorgao.php?lFiltraInstituicao=true&pesquisa_chave=" + iCodigoOrgao + "&funcao_js=parent.js_completaOrgao";
  }

  js_OpenJanelaIframe("", "db_iframe_orcorgao", sUrlOrgao, "Pesquisa �rg�o", lMostra);
}

function js_preencheOrgao(iOrgao, sDescricao) {

  $("o40_orgao").value = iOrgao;
  $("o40_descr").value = sDescricao;
  db_iframe_orcorgao.hide();
}

function js_completaOrgao(sDescricao, lErro) {

  $("o40_descr").value = sDescricao;
  if (lErro) {

    $("o40_orgao").value = "";
    $("o40_orgao").focus();
  }
}

function js_pesquisaObjetivo(lMostra) {

  js_limpaTela();
  var sCampos      = "o143_sequencial";
  var sUrlObjetivo = "func_orcobjetivo.php?funcao_js=parent.js_preencheObjetivo|"+sCampos;

  if (!lMostra) {

    var iCodigoObjetivo = $F("o143_sequencial");
    sUrlObjetivo        = "func_orcobjetivo.php?funcao_js=parent.js_completaObjetivo&pesquisa_chave=" + iCodigoObjetivo;
  }

  js_OpenJanelaIframe("", "db_iframe_orcobjetivo", sUrlObjetivo, "Pesquisa Objetivos", lMostra);
}

function js_preencheObjetivo(iObjetivo, sDescricao, sObjetivo, iOrgao, sOrgao) {

  $("o143_sequencial").value = iObjetivo;
  js_ajaxObjetivo();
  db_iframe_orcobjetivo.hide();
}

/**
 * Limpa a tela para a adi��o de um novo objetivo
 */
function js_limpaTela() {

  var aInputs     = $$('input[type=text], textarea');
  var iTotalInput = aInputs.length;
  for (var iRow = 0 ; iRow < iTotalInput; iRow++) {
    aInputs[iRow].value = "";
  }
  oGridMetas.clearAll(true);
  oGridIniciativas.clearAll(true);
}

/**
 * Exclui um objetivo
 */
function js_excluirObjetivo() {

  var sMensagem = "Este procedimento ir� excluir as metas vinculadas a este objetivo e tamb�m as suas iniciativas.\n";
     sMensagem += "Deseja realmente excluir o Objetivo " + $F('o143_sequencial') +  "?";
  if(!confirm(sMensagem)) {
    return false;
  }

  var oParametro             = new Object();
  oParametro.iCodigoObjetivo = $F('o143_sequencial');
  oParametro.exec            = "excluirObjetivo";

  js_divCarregando("Aguarde, excluindo Objetivo...", "msgBox");

  var oAjax = new Ajax.Request(sUrl,{
                                       method:'post',
                                		  parameters:'json='+Object.toJSON(oParametro),
                                		  onComplete: js_retornoExcluirObjetivo
                                		}
                               );
}

function js_retornoExcluirObjetivo(oAjax) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oAjax.responseText+")");
  js_limpaTela();
  alert(oRetorno.sMessage.urlDecode());

}



/**
 *  2 - Defini��es da Aba Metas
 **/


/**
 * Defini��es da Grid de Metas
 **/
var aHeaders   = new Array("C�digo", "Descri��o", "A��o");
var aCellAlign = new Array("center", "left","center");
var aCellWidth = new Array("20%", "70%", "10%");

var oGridMetas = new DBGrid("ctnGridMetas");
oGridMetas.setCheckbox(0);
oGridMetas.nameInstance = "oGridMetas";
oGridMetas.setHeight("240");
oGridMetas.setHeader(aHeaders);
oGridMetas.setCellWidth(aCellWidth);
oGridMetas.setCellAlign(aCellAlign);
oGridMetas.hasCheckbox = true;
oGridMetas.setSelectAll(true);
oGridMetas.show($("ctnGridMetas"));
$("tablectnGridMetasheader").style.width = "521px";


function js_salvarMeta() {


  if (!$F('o145_descricao')) {

    alert("Descri��o sucinta da Meta n�o informada.");
    $('o145_descricao').focus();
    return false;
  }

  if (!$F('o145_meta')) {

    alert("Descri��o completa da Meta n�o informada.");
    $('o145_meta').focus();
    return false;
  }


  var sConfirmacao           = "Deseja vincular a Meta?";
  var oParametro             = new Object();
  oParametro.iCodigoMeta     = $F('o145_sequencial');
  oParametro.sDescricao      = encodeURIComponent(tagString($F('o145_descricao')));
  oParametro.sMeta           = encodeURIComponent(tagString($F('o145_meta')));
  oParametro.iCodigoObjetivo = $F('o143_sequencial');
  oParametro.exec            = "adicionarMeta";

  if(oParametro.iCodigoMeta) {

    oParametro.exec = "alterarMeta";
    sConfirmacao    = "Deseja alterar a Meta?";
  }

  if(!confirm(sConfirmacao)) {
    return false;
  }

  js_divCarregando("Aguarde, salvando Meta...", "msgBox");

  var oAjax = new Ajax.Request(sUrl, {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParametro),
                                        onComplete: js_retornoSalvarMeta
                                      }
                               );
}


function js_retornoSalvarMeta(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());
  js_removeObj("msgBox");
  js_limpaTelaMeta();
  js_buscaMetas();
}


function js_excluirMetas() {

  var oParametro = new Object();
  var aMetas     = oGridMetas.getSelection( "object" );

  if(aMetas.length <= 0){
    return false;
  }

  var sMensagem  = "As metas selecionadas ser�o excluidas, juntamente com suas iniciativas. ";
  sMensagem     += "Deseja exclu�-las?";

  if (!confirm(sMensagem)) {
    return false;
  }

  oParametro.iCodigoObjetivo = $F("o143_sequencial");
  oParametro.exec            = "excluirMetas";
  oParametro.aMetas          = new Array();

  aMetas.each(function(oMeta, iCodigoMeta) {

    oParametro.aMetas.push(oMeta.aCells[1].getValue());
  });

  js_divCarregando("Aguarde, excluindo Metas selecionadas...", "msgBox");

  var oAjax = new Ajax.Request(sUrl, {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParametro),
                                        onComplete: js_retornoExcluirMetas
                                      }
                               );
}


function js_retornoExcluirMetas(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());
  js_removeObj("msgBox");
  js_buscaMetas();
}


function js_excluirMeta() {

  var oParametro             = new Object();
  oParametro.iCodigoObjetivo = $F("o143_sequencial");
  oParametro.exec            = "excluirMetaPorCodigo";
}

function js_limpaTelaMeta() {

  $("o145_sequencial").value = "";
  $("o145_descricao").value  = "";
  $("o145_meta").value       = "";
}

function js_buscaMetas() {

  var oParametro             = new Object();
  oParametro.iCodigoObjetivo = $F("o143_sequencial");
  oParametro.exec            = "buscaMetas";

  js_divCarregando("Aguarde, buscando metas...", "msgBox");

  var oAjax = new Ajax.Request(sUrl, {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParametro),
                                        onComplete: js_retornoBuscaMetas
                                      }
                               );
}

function js_carregaMetaParaAlteracao(iCodigoMeta) {

  var oParametro             = new Object();
  oParametro.iCodigoObjetivo = $F("o143_sequencial");
  oParametro.iCodigoMeta     = iCodigoMeta;
  oParametro.exec            = "buscaMetaPorCodigo";

  js_divCarregando("Aguarde, buscando dados da meta "+iCodigoMeta+"...", "msgBox");

  var oAjax = new Ajax.Request(sUrl, {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParametro),
                                        onComplete: js_retornoBuscaMetaPorCodigo
                                      }
                               );
}

function js_retornoBuscaMetaPorCodigo(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");

  $("o145_sequencial").value = oRetorno.oMeta.iCodigoMeta;
  $("o145_descricao").value  = oRetorno.oMeta.sDescricaoMeta.urlDecode();
  $("o145_meta").value       = oRetorno.oMeta.sMeta.urlDecode();
  $("o145_meta").value       = oRetorno.oMeta.sMeta.urlDecode();

  js_removeObj('msgBox');
}

function js_retornoBuscaMetas(oAjax) {

  oGridMetas.clearAll(true);
  var oRetorno = eval("("+oAjax.responseText+")");

  oRetorno.aMetas.each(function (oMeta, iLinha) {

    var aLinha = new Array();
    aLinha[0]  = oMeta.iCodigoMeta;
    aLinha[1]  = oMeta.sDescricaoMeta.urlDecode();
    aLinha[2]  = "<input type='button' name='btnMetaAlterar"+oMeta.iCodigoMeta+"' onclick='js_carregaMetaParaAlteracao("+oMeta.iCodigoMeta+");' value='A' title='Alterar'/>";

    oGridMetas.addRow(aLinha, false, false);
  });

  oGridMetas.renderRows();
  js_removeObj('msgBox');
}



/**
 *  3 - Defini��es da Aba Iniciativa
 **/
 /**
  * Defini��es da Grid de Metas
  */
 var aHeaders   = new Array("C�digo", "Descri��o", "A��o");
 var aCellAlign = new Array("center", "left","center");
 var aCellWidth = new Array("20%", "70%", "10%");

 var oGridIniciativas = new DBGrid("ctnGridIniciativa");
 oGridIniciativas.setCheckbox(0);
 oGridIniciativas.nameInstance = "oGridIniciativas";
 oGridIniciativas.setHeight("240");
 oGridIniciativas.setHeader(aHeaders);
 oGridIniciativas.setCellWidth(aCellWidth);
 oGridIniciativas.setCellAlign(aCellAlign);
 oGridIniciativas.hasCheckbox = true;
 oGridIniciativas.setSelectAll(true);
 oGridIniciativas.show($("ctnGridIniciativa"));


 function js_salvarIniciativa() {

   var sConfirmacao             = "Deseja vincular a Iniciativa?";
   var oParametro               = new Object();
   oParametro.iCodigoIniciativa = $F('iniciativa_o147_sequencial');
   oParametro.iAno              = $F('iniciativa_o147_ano');
   oParametro.sDescricao        = encodeURIComponent(tagString($F('o147_descricao')));
   oParametro.sIniciativa       = encodeURIComponent(tagString($F('iniciativa_o147_iniciativa')));
   oParametro.iCodigoObjetivo   = $F('iniciativa_o143_sequencial');
   oParametro.iCodigoMeta       = $F('iniciativa_o147_orcmeta');
   oParametro.exec              = "adicionarIniciativa";

   if (!oParametro.iCodigoMeta) {

     alert("Por favor selecione a meta.");
     return false;
   }

   if ($F('iniciativa_o147_iniciativa') == "") {
     alert("Informe a iniciativa.");
     return false;
   }


   if(oParametro.iCodigoIniciativa) {

     oParametro.exec = "alterarIniciativa";
     sConfirmacao    = "Deseja alterar a Iniciativa?";
   }

   if(!confirm(sConfirmacao)) {
     return false;
   }

   js_divCarregando("Aguarde, salvando Iniciativa...", "msgBox");

   var oAjax = new Ajax.Request(sUrl, {
                                         method:'post',
                                 			  parameters:'json='+Object.toJSON(oParametro),
                                 			  onComplete: js_retornoSalvarIniciativa
                                 			}
                                );
 }

 function js_pesquisaMeta(lMostra) {

   var sCampos   = "o145_sequencial|o145_descricao";
   var iObjetivo = $F("iniciativa_o143_sequencial");
   var sUrlMeta  = "func_orcmeta.php?iObjetivo="+iObjetivo+"&funcao_js=parent.js_preencheMeta|"+sCampos;

   if (!lMostra) {

     var iCodigoMeta = $F("iniciativa_o147_orcmeta");
     sUrlMeta        = "func_orcmeta.php?funcao_js=parent.js_completaMeta&iObjetivo="+iObjetivo+"&pesquisa_chave=" + iCodigoMeta;
   }

   js_OpenJanelaIframe("", "db_iframe_orcmeta", sUrlMeta, "Pesquisa Meta", lMostra);
 }



 function js_preencheMeta(iCodigoMeta, sDescricao) {

   $('iniciativa_o147_orcmeta').value   = iCodigoMeta;
   $('iniciativa_o145_descricao').value = sDescricao;
   js_buscaIniciativas();
   db_iframe_orcmeta.hide();
 }

 function js_completaMeta(sDescricao, lErro) {

   $('iniciativa_o145_descricao').value = sDescricao;
   console.log(arguments);

   if(lErro) {

     $('iniciativa_o147_orcmeta').value   = "";
     $('iniciativa_o147_orcmeta').focus();
   }
   js_buscaIniciativas();
 }



 function js_retornoSalvarIniciativa(oAjax) {

   var oRetorno = eval("("+oAjax.responseText+")");
   alert(oRetorno.sMessage.urlDecode());
   js_removeObj("msgBox");
   js_limpaTelaIniciativa();
   js_buscaIniciativas();
 }


 function js_excluirIniciativas() {

   var oParametro   = new Object();
   var aIniciativas = oGridIniciativas.getSelection( "object" );

   if(aIniciativas.length <= 0){
     return false;
   }

   var sMensagem  = "As iniciativas selecionadas ser�o excluidas, juntamente com seus v�nculos com Projetos / Atividades. ";
   sMensagem     += "Deseja exclu�-las?";

   if (!confirm(sMensagem)) {
     return false;
   }

   oParametro.iCodigoMeta     = $F('iniciativa_o147_orcmeta');
   oParametro.iCodigoObjetivo = $F("iniciativa_o143_sequencial");
   oParametro.exec            = "excluirIniciativas";
   oParametro.aIniciativas    = new Array();

   aIniciativas.each(function(oIniciativa, iCodigoIniciativa) {

     oParametro.aIniciativas.push(oIniciativa.aCells[1].getValue());
   });

   js_divCarregando("Aguarde, excluindo Iniciativas selecionadas...", "msgBox");

   var oAjax = new Ajax.Request(sUrl, {
                                         method:'post',
                                 			  parameters:'json='+Object.toJSON(oParametro),
                                 			  onComplete: js_retornoExcluirIniciativas
                                 			}
                                );
 }


 function js_retornoExcluirIniciativas(oAjax) {

   var oRetorno = eval("("+oAjax.responseText+")");
   alert(oRetorno.sMessage.urlDecode());
   js_removeObj("msgBox");
   js_buscaIniciativas();
 }


 function js_limpaTelaIniciativa() {

   $("iniciativa_o147_sequencial").value = "";
   $("o147_descricao").value  = "";
   $("iniciativa_o147_iniciativa").value = "";
   $("iniciativa_o147_ano").value = "";
 }

 function js_buscaIniciativas() {

   var oParametro             = new Object();
   oParametro.iCodigoObjetivo = $F("iniciativa_o143_sequencial");
   oParametro.iCodigoMeta     = $F('iniciativa_o147_orcmeta');
   oParametro.exec            = "buscaIniciativas";

   js_limpaTelaIniciativa();

   if (!oParametro.iCodigoMeta) {

     oGridIniciativas.clearAll(true);
     return false;
   }

   js_divCarregando("Aguarde, buscando iniciativas...", "msgBox");

   var oAjax = new Ajax.Request(sUrl, {
                                         method:'post',
                                 			  parameters:'json='+Object.toJSON(oParametro),
                                 			  onComplete: js_retornoBuscaIniciativas
                                 			}
                                );
 }

 function js_carregaIniciativaParaAlteracao(iCodigoIniciativa) {

   var oParametro               = new Object();
   oParametro.iCodigoObjetivo   = $F("iniciativa_o143_sequencial");
   oParametro.iCodigoMeta       = $F("iniciativa_o147_orcmeta");
   oParametro.iCodigoIniciativa = iCodigoIniciativa;
   oParametro.exec              = "buscaIniciativaPorCodigo";

   js_divCarregando("Aguarde, buscando dados da iniciativa "+iCodigoIniciativa+"...", "msgBox");

   var oAjax = new Ajax.Request(sUrl, {
                                         method:'post',
                                 			  parameters:'json='+Object.toJSON(oParametro),
                                 			  onComplete: js_retornoBuscaIniciativaPorCodigo
                                 			}
                                );
 }

 function js_retornoBuscaIniciativaPorCodigo(oAjax) {

   var oRetorno = eval("("+oAjax.responseText+")");

   $("iniciativa_o147_sequencial").value = oRetorno.oIniciativa.iCodigoIniciativa;
   $("o147_descricao").value             = oRetorno.oIniciativa.sDescricaoIniciativa.urlDecode();
   $("iniciativa_o147_iniciativa").value = oRetorno.oIniciativa.sIniciativa.urlDecode();
   $("iniciativa_o147_ano").value        = oRetorno.oIniciativa.iAno.urlDecode();

   js_removeObj('msgBox');
 }

 function js_retornoBuscaIniciativas(oAjax) {

   oGridIniciativas.clearAll(true);
   var oRetorno = eval("("+oAjax.responseText+")");

   oRetorno.aIniciativas.each(function (oIniciativa, iLinha) {

     var aLinha = new Array();
     aLinha[0]  = oIniciativa.iCodigoIniciativa;
     aLinha[1]  = oIniciativa.sDescricaoIniciativa.urlDecode();
     aLinha[2]  = "<input type='button' name='btnIniciativaAlterar"+oIniciativa.iCodigoIniciativa+"' onclick='js_carregaIniciativaParaAlteracao("+oIniciativa.iCodigoIniciativa+");' value='A' title='Alterar'/>";

     oGridIniciativas.addRow(aLinha, false, false);
   });

   oGridIniciativas.renderRows();
   js_removeObj('msgBox');
 }

 js_buscaIniciativas();


 /**
  * Fun��o que busca os dados do objetivo via AJAX
  */
 function js_ajaxObjetivo() {


   var oParametro             = new Object();
   oParametro.exec            = "pesquisaObjetivo";
   oParametro.iCodigoObjetivo = $F("o143_sequencial");

   js_divCarregando("Aguarde, carregando dados...", "msgBox");

   var oAjax = new Ajax.Request(sUrl, {
                                         method:'post',
                                 			  parameters:'json='+Object.toJSON(oParametro),
                                 			  onComplete: function (oAjax) {

                                   			  js_removeObj("msgBox");
                                          var oRetorno = eval("("+oAjax.responseText+")");
                                          $('o143_objetivo').value   = oRetorno.o143_objetivo.urlDecode();
                                          $("o143_descricao").value  = oRetorno.o143_descricao.urlDecode();
                                          $("o40_orgao").value       = oRetorno.o143_orcorgaoorgao;
                                          js_pesquisaOrgao(false);
                                 			  }
                                 			}
                                );
 }

</script>