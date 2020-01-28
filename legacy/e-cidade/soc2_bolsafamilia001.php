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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_utils.php");
require_once ("std/db_stdClass.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");

$oDaoDocumento = db_utils::getDao('db_documentotemplate');
$sCampos       = " db82_sequencial, db82_descricao";

$sSqlDocumentoTemplate = $oDaoDocumento->sql_query_file(null, $sCampos, null, "db82_templatetipo = 29");
$rsDocumentoTemplate   = $oDaoDocumento->sql_record($sSqlDocumentoTemplate);

$lBloqueiaBotao = '';
if ($oDaoDocumento->numrows == 0) {
  $lBloqueiaBotao = 'disabled';	
}

$oRotuloCampos = new rotulocampo();
$oRotuloCampos->label("as15_codigofamiliarcadastrounico");
$oRotuloCampos->label("as02_nis");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
  <center>
    <form name="form1" method="post" action="">
    <fieldset style="width:300px;">
      <legend><b>Declaração da Família</b></legend>
      <table>
        <tr>
          <td nowrap="nowrap" style="font-weight: bold;">
          
            <? db_ancora("Código da Familia : ","js_pesquisaCidadaoFamilia(true, false);",1);?>
          <td nowrap="nowrap">
            <?php
              db_input("iFamilia", 10, '', true, "hidden", 1);
              db_input("as15_codigofamiliarcadastrounico", 10, $Ias15_codigofamiliarcadastrounico, true,
              		     "text", 1, "onchange='js_pesquisaCidadaoFamilia(false, false);'");
              db_input("favorecido", 25, '', true, "text", 3);
            ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" style="font-weight: bold;">
            <?php
              db_ancora("NIS do Responsável da Família:", "js_pesquisaCidadaoFamilia(true, true);", 1);
            ?>
          </td>
          <td>
          <?php 
            db_input("as02_nis", 10, $Ias02_nis, true, "text", 1, "onchange='js_pesquisaCidadaoFamilia(false, true);'");
          ?>
          </td>
        </tr>
        <tr>
          <td nowrap="nowrap" style="font-weight: bolder;">
            <b>Documento Template:</b>
          </td>
          <td nowrap="nowrap">
            <?
             db_selectrecord('documentotemplate', $rsDocumentoTemplate, true, 1, '');
            ?>
          </td>
        </tr>
      </table>
     </fieldset>
     <input type="button" id="btnImprimir" value="Imprimir" 
            style="margin-top: 10px;" <?php echo $lBloqueiaBotao != ''?$lBloqueiaBotao:'';?>/>
     </form>
  </center>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

$('btnImprimir').disabled = true;
function limpaForm() {
  
  $('iFamilia').value   											= '';
  $('favorecido').value 											= '';
  $('as02_nis').value                         = '';
  $('as15_codigofamiliarcadastrounico').value = '';
  $('btnImprimir').disabled                   = true;
}

limpaForm();

/**
 * Função para busca e validação do NIS 
 */
 function js_pesquisaCidadaoFamilia(lMostra, lNis) {

   var sUrl = 'func_cidadaofamilia.php?';
   
   if (lMostra == true) {

     sUrl += 'funcao_js=parent.js_mostraFamilia|as04_sequencial|ov02_nome|as15_codigofamiliarcadastrounico|as02_nis';
   	js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisar Código da Família', true);
   } else {
     
     sUrl += 'funcao_js=parent.js_mostraFamilia2';
     sUrl += '&sTipoRetorno=relatorio';
     sUrl += '&sNIS='+lNis;

     if ($F('as15_codigofamiliarcadastrounico') == '' && $F('as02_nis') == "") {
       
       $('as04_sequencial').value = '';
       return false;
     }
     
   	if (!lNis && $F('as15_codigofamiliarcadastrounico') != '') {
   	  sUrl += '&pesquisa_chave='+$F('as15_codigofamiliarcadastrounico');
   	} else if (lNis && $F('as02_nis') != "") {
   	  sUrl += '&pesquisa_chave='+$F('as02_nis');
     }

   	js_OpenJanelaIframe('top.corpo', 'db_iframe_cidadaofamilia', sUrl, 'Pesquisar Código da Família', false);
   }
 }
 
function js_mostraFamilia (iSeqFamilia, sFavorecido, iCodigoFamilia, iNis) {

  if (iSeqFamilia != "") {
    
    $('iFamilia').value   											= iSeqFamilia;
    $('favorecido').value 											= sFavorecido;
    $('as15_codigofamiliarcadastrounico').value = iCodigoFamilia;
    $('as02_nis').value                         = iNis;
  }
  db_iframe_cidadaofamilia.hide();
  validaImpressao();
}

function js_mostraFamilia2 (iCodigoFamilia, lErro, sFavorecido, iSeqFamilia, iNis) {

  if (lErro) {
    
    $('iFamilia').value                         = "";
    $('as15_codigofamiliarcadastrounico').value = "";
    $('iNis').value                             = "";
  }
  $('favorecido').value                       = sFavorecido;
  $('iFamilia').value   	                    = iSeqFamilia;
  $('as02_nis').value                         = iNis
  $('as15_codigofamiliarcadastrounico').value = iCodigoFamilia;
  validaImpressao();
}

/**
 * Valida se a familia atende os requisitos para impressao da Declaração de Tarifa Social
 */
function validaImpressao() {

  if ($F('as15_codigofamiliarcadastrounico') == "") {

    alert("Selecione uma família antes de imprimir o documento.")
    return false;
  }
  js_divCarregando("Aguarde... validando família", "msgBox");
  var sRpc            = 'soc4_relatoriossociais.RPC.php';
  var oObject         = new Object();
  oObject.exec        = "familiaAtendeCriterioTarifaSocial";
  oObject.iFamilia    = $F('iFamilia');
  
  var objAjax   = new Ajax.Request (sRpc,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject), 
                                         onComplete:js_retornoAtendeCriterioTarifaSocial
                                        }
                                   );
  
}

function js_retornoAtendeCriterioTarifaSocial(oJson) {

  $('btnImprimir').disabled = false;
  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  if (!oRetorno.lAtendeCriterioTarifaSocial) {
    
    alert(oRetorno.message.urlDecode());
    $('btnImprimir').disabled = true;
    return false;
  }
}

$('btnImprimir').observe('click', function() {
  js_imprime();
});

/**
 * Função que emite o Documento 
 */
function js_imprime() {

  var sUrl  = 'soc2_bolsafamilia002.php';
  sUrl     += '?iCodigoFamilia='+$F('iFamilia');
  sUrl     += '&iModeloImpressao='+$F('documentotemplate');

  var jan = window.open(sUrl, '',
                        'location=0, width='+(screen.availWidth - 5)+'width='+(screen.availWidth - 5)+', scrollbars=1');
      jan.moveTo(0, 0);

  limpaForm();
}
</script>
<?php
if ($oDaoDocumento->numrows == 0) {
  
  $sCaminhoMenu = db_stdClass::getCaminhoMenu(7782);
  $sMensagem  = "Não há templates cadastrados para esse documento.\\n";
  $sMensagem .= "Para cadastrar o arquivo para template acesse o menu:\\n";
  $sMensagem .= "{$sCaminhoMenu}, \\n";
  $sMensagem .= "e informe um template para o código 29 - Declaracao Tarifa Social."; 
  db_msgbox($sMensagem);
}
?>