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

$oGet = db_utils::postMemory($_GET);

$oRotuloOrcobjetivo = new rotulo("orcobjetivo");
$oRotuloOrcobjetivo->label();

$oRotuloOrcProgramaVinculoObjetivo = new rotulo("orcprogramavinculoobjetivo");
$oRotuloOrcProgramaVinculoObjetivo->label();

$oRotuloPrograma = new rotulo("orcprograma");
$oRotuloPrograma->label();

if (!empty($oGet->codprograma)) {
  $o54_programa = $oGet->codprograma;
}

if (!empty($oGet->anousu)) {
  $o54_anousu = $oGet->anousu;
}
?>

<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
    <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
    <script src="scripts/widgets/DBLancador.widget.js" type="text/javascript"></script>
    <script src="scripts/widgets/DBAncora.widget.js" type="text/javascript"></script>
    <script src="scripts/datagrid.widget.js" type="text/javascript"></script>
    <script src="scripts/widgets/dbtextField.widget.js" type="text/javascript"></script>
    <link href="estilos.css"             rel="stylesheet" type="text/css">
    <link href="estilos/grid.style.css"  rel="stylesheet" type="text/css">

    <style>
    .lancador_objetivos {
      top:100px;
    }
    </style>
  </head>

  <body bgcolor="#CCCCCC" style="margin-top: 25px" >
    <div id="ctnObjetivo" class='lancador_objetivos'>
      <center>

        <fieldset style="width: 500px;">
          <legend>Objetivo</legend>
          <table>
            <tr>
              <td><b>Programa:</b></td>
              <td><?php db_input("o54_programa", 7, $Io54_programa, true, 'text', 3); ?></td>
              <td><?php db_input("o54_anousu", 7, $Io54_anousu, true, 'hidden', 3); ?></td>
            </tr>
            <tr>
              <td><?php db_ancora("<b>Objetivo:</b>", "js_pesquisaObjetivos(true)", 1);?></td>
              <td><?php db_input("o144_orcobjetivo", 7, $Io144_orcobjetivo, true, "text", 1, "onchange=js_pesquisaObjetivos(false);"); ?></td>
              <td><?php db_input("o143_descricao", 30, $Io143_descricao, true, "text", 3); ?></td>
              <td><input type="button" name="btnLancarObjetivo" id="btnLancarObjetivo" value="Vincular" onclick="js_vincularObjetivo();"/></td>
            </tr>
          </table>
        </fieldset>

        <fieldset style="width: 500px;">
          <legend>Objetivos Vinculados</legend>
          <div id="ctnGridObjetivos" ></div>
        </fieldset>
        <input type="button" id="btnObjetivoExcluir" name="btnObjetivoExcluir" value="Excluir Selecionados" onclick="js_excluirObjetivo()"/>
      </center>

    </div>

  </body>
</html>

<script>
var sUrl           = "orc1_programa.RPC.php";
var oGridObjetivos = new DBGrid("ctnGridObjetivos");
var aWidth         = new Array();
var aAlinhamentos  = new Array();
var aHeader        = new Array();

aHeader[0] = "Código";
aHeader[1] = "Descrição";

aAlinhamentos[0] = "right";
aAlinhamentos[1] = "left";

aWidth[0] = "20%";
aWidth[1] = "80%";


oGridObjetivos.sName        = "oGridObjetivos";
oGridObjetivos.nameInstance = "oGridObjetivos";
oGridObjetivos.setCheckbox(0);
oGridObjetivos.setCellWidth( aWidth );
oGridObjetivos.setCellAlign( aAlinhamentos );
oGridObjetivos.setHeader( aHeader );
oGridObjetivos.hasCheckbox = true;
oGridObjetivos.setSelectAll(true);
oGridObjetivos.allowSelectColumns(true);
oGridObjetivos.show( $('ctnGridObjetivos') );
oGridObjetivos.clearAll(true);

function js_vincularObjetivo() {

  if (!$F("o144_orcobjetivo")) {
    return false;
  }

  var oParametro             = new Object();
  oParametro.iCodigoObjetivo = $F("o144_orcobjetivo");
  oParametro.iCodigoPrograma = $F("o54_programa");
  oParametro.iAnoPrograma    = $F("o54_anousu");
  oParametro.exec            = "vincularObjetivoPrograma";

  js_divCarregando("Aguarde, vinculando objetivo...", "msgBox");

  var oAjax = new Ajax.Request(sUrl, {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParametro),
                                        onComplete: js_retornoVincularObjetivo
                                      }
                               );
}

function js_retornoVincularObjetivo(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  if (oRetorno.iStatus == 1) {

    var aRow = new Array();
    aRow[0]  = $F("o144_orcobjetivo");
    aRow[1]  = $F("o143_descricao");

    $("o144_orcobjetivo").value = "";
    $("o143_descricao").value   = "";

    oGridObjetivos.addRow(aRow);
    oGridObjetivos.renderRows();
  }

  alert(oRetorno.sMessage.urlDecode());
  js_removeObj("msgBox");
}

function js_excluirObjetivo() {

  var aObjetivos = oGridObjetivos.getSelection("object");

  if (aObjetivos.length <= 0) {
    return false;
  }

  var sMensagem  = "Deseja desvincular os objetivos selecionados?";
  if (!confirm(sMensagem)) {
    return false;
  }

  var oParametro = new Object();

  oParametro.exec            = "desvincularObjetivoPrograma";
  oParametro.iCodigoPrograma = $F("o54_programa");
  oParametro.iAnoPrograma    = $F("o54_anousu");
  oParametro.aObjetivos      = new Array();

  aObjetivos.each(function(oObjetivo, iCodigoObjetivo) {
    oParametro.aObjetivos.push(oObjetivo.aCells[1].getValue());
  });

  js_divCarregando("Aguarde, desvinculando objetivos...", "msgBox");
  var oAjax = new Ajax.Request(sUrl, {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParametro),
                                        onComplete: js_retornoDesvincularObjetivo
                                      }
                               );
}

function js_retornoDesvincularObjetivo(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  alert(oRetorno.sMessage.urlDecode());

  js_removeObj("msgBox");
  js_buscaObjetivosVinculados();
}

function js_retornoBuscaObjetivosVinculados(oAjax) {

  var oRetorno = eval("("+oAjax.responseText+")");
  var aLinha   = new Array();

  oGridObjetivos.clearAll(true);
  oRetorno.aObjetivos.each(function (oObjetivo, iObjetivo) {

    aLinha[0] = oObjetivo.iCodigoObjetivo;
    aLinha[1] = oObjetivo.sDescricaoObjetivo.urlDecode();
    oGridObjetivos.addRow(aLinha);
  });
  oGridObjetivos.renderRows();
  js_removeObj("msgBox");
}

function js_buscaObjetivosVinculados() {

  var oParametro = new Object();
  oParametro.exec = "buscaObjetivosVinculadosPrograma";

  oParametro.iCodigoPrograma = $F("o54_programa");
  oParametro.iAnoPrograma    = $F("o54_anousu");

  js_divCarregando("Aguarde, buscando objetivos...", "msgBox");
  var oAjax = new Ajax.Request(sUrl, {
                                        method:'post',
                                        parameters:'json='+Object.toJSON(oParametro),
                                        onComplete: js_retornoBuscaObjetivosVinculados
                                      }
                               );
}

function js_pesquisaObjetivos(lMostra) {

  var sUrlObjetivo = "func_orcobjetivo.php?lVinculado=false&funcao_js=parent.js_preencheObjetivo|o143_sequencial|o143_descricao";

  if (!lMostra) {

    var iCodigoObjetivo = $F("o144_orcobjetivo");
    sUrlObjetivo        = "func_orcobjetivo.php?lVinculado=false&pesquisa_chave=" + iCodigoObjetivo + "&funcao_js=parent.js_completaObjetivo";
  }

  js_OpenJanelaIframe("", "db_iframe_orcobjetivo", sUrlObjetivo, "Pesquisa Objetivo", lMostra);
}

function js_preencheObjetivo(iCodigo, sDescricao) {

  $("o144_orcobjetivo").value = iCodigo;
  $("o143_descricao").value   = sDescricao;
  db_iframe_orcobjetivo.hide();
}

function js_completaObjetivo(sDescricao, lErro) {

  $("o143_descricao").value = sDescricao;

  if (lErro) {

    $("o144_orcobjetivo").value = "";
    $("o144_orcobjetivo").focus();
  }
}

</script>