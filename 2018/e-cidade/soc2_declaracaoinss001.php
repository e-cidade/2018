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
require_once ("libs/db_usuariosonline.php");
require_once ("libs/db_app.utils.php");
require_once ("dbforms/db_funcoes.php");
require_once ("std/db_stdClass.php");
$oDaoDocumento = db_utils::getDao('db_documentotemplate');
$sCampos       = " db82_sequencial, db82_descricao";

$sSqlDocumentoTemplate = $oDaoDocumento->sql_query_file(null, $sCampos, null, "db82_templatetipo = 28");
$rsDocumentoTemplate   = $oDaoDocumento->sql_record($sSqlDocumentoTemplate);

$lBloqueiaBotao = "";
if ($oDaoDocumento->numrows == 0) {
	$lBloqueiaBotao = "disabled";
}

?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <?
  db_app::load("scripts.js, prototype.js, strings.js, dates.js");
  db_app::load("estilos.css");
  ?>
</head>
<body bgcolor="#cccccc" style="margin-top: 25px" onload="">
  <center>
    <form name="form1" method="post" action="">
    <fieldset style="width:300px;">
      <legend><b>Declaração de INSS</b></legend>
      <table>
        <tr>
          <td nowrap="nowrap" style="font-weight: bold;">

            <? db_ancora("NIS : ","js_pesquisaNIS(true);",1);?>
          <td nowrap="nowrap">
            <?php
              db_input("nis", 13, '', true, "text", 1, "onchange='js_pesquisaNIS(false);'");
              db_input("favorecido", 25, '', true, "text", 3);
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
     <input type="button" id="btnImprimir" value="Imprimir" onclick="validaImpressao();"
            style="margin-top: 10px;" <?php echo $lBloqueiaBotao != ''?$lBloqueiaBotao:'';?> />
     </form>
  </center>
<?php
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script type="text/javascript">

function limpaForm() {

  $('nis').value        = '';
  $('favorecido').value = '';
}
limpaForm();

/**
 * Função para busca e validação do NIS
 */
function js_pesquisaNIS(lMostra) {

  if (lMostra) {
    js_OpenJanelaIframe('top.corpo',
                         'db_iframe_cadunico',
                         'func_cidadaocadastrounico.php?funcao_js=parent.js_mostraNIS|as02_nis|ov02_nome',
                         'Pesquisa NIS',true);
  } else {

    if ($F('nis') != '') {

      js_OpenJanelaIframe('top.corpo',
                             'db_iframe_cadunico',
                             'func_cidadaocadastrounico.php?lNis=true&pesquisa_chave='+$F('nis')+'&funcao_js=parent.js_mostraNIS2',
                             'Pesquisa NIS',
                             false);
      } else {
        $('nis').value        = "";
        $('favorecido').value = "";
      }
   }
}

function js_mostraNIS (iNis, sFavorecido) {

  if (iNis != "") {

    $('nis').value            = iNis;
    $('favorecido').value     = sFavorecido;
    $('btnImprimir').disabled = false;
  }
  db_iframe_cadunico.hide();
}

function js_mostraNIS2 (sFavorecido, lErro) {

  if (lErro) {
    $('nis').value        = "";
  }
  $('favorecido').value   = sFavorecido;
}


/**
 * Valida se a familia atende os requisitos para impressao da Declaração de Tarifa Social
 */
function validaImpressao() {

  if ($F('nis') == "") {

    alert("Selecione um Nis antes de imprimir o documento.")
    return false;
  }
  js_divCarregando("Aguarde... validando NIS do cidadão", "msgBox");
  var sRpc     = 'soc4_relatoriossociais.RPC.php';
  var oObject  = new Object();
  oObject.exec = "cidadaoAtendeCriteriosDeclaracaoINSS";
  oObject.iNis = $F('nis');

  var objAjax   = new Ajax.Request (sRpc,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject),
                                         onComplete:js_retornoAtendeCriteriosDeclaracaoINSS
                                        }
                                   );

}

function js_retornoAtendeCriteriosDeclaracaoINSS(oJson) {

  js_removeObj("msgBox");
  var oRetorno = eval("("+oJson.responseText+")");

  if (oRetorno.lAtendeCriterioDeclaracaoINSS) {
      js_imprime();
  } else {
    $('btnImprimir').disabled = true;
    alert(oRetorno.message.urlDecode());
  }

  limpaForm();
  return false;
}

/**
 * Função que emite o Documento
 */
function js_imprime() {

  var sUrl  = 'soc2_declaracaoinss002.php';
  sUrl     += '?iCodigoNis='+$F('nis');
  sUrl     += '&iModeloImpressao='+$F('documentotemplate');

  var jan = window.open(sUrl, '',
                        'location=0, width='+(screen.availWidth - 5)+'width='+(screen.availWidth - 5)+', scrollbars=1');
      jan.moveTo(0, 0);
}
</script>
</body>
</html>
<?
if ($oDaoDocumento->numrows == 0) {

  $sCaminhoMenu = db_stdClass::getCaminhoMenu(7782);
  $sMensagem  = "Não há templates cadastrados para esse documento.\\n";
  $sMensagem .= "Para cadastrar o arquivo para template acesse o menu:\\n";
  $sMensagem .= "{$sCaminhoMenu}, \\n";
  $sMensagem .= "e informe um template para o código 28 - Declaracao INSS.";
  db_msgbox($sMensagem);
}
?>