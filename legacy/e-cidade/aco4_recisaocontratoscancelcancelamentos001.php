<?
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
require_once("classes/db_acordomovimentacao_classe.php");

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clacordo             = new cl_acordo;
$clacordomovimentacao = new cl_acordomovimentacao;

$db_opcao = 3;

$clacordo->rotulo->label();
$clacordomovimentacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac10_sequencial");
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
$clrotulo->label("ac10_datamovimento");
$clrotulo->label("ac10_obs");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?   
  db_app::load("scripts.js, strings.js, prototype.js, datagrid.widget.js");
  db_app::load("widgets/messageboard.widget.js, widgets/windowAux.widget.js");
  db_app::load("estilos.css, grid.style.css");
?>
<style>
td {
  white-space: nowrap;
}

fieldset table td:first-child {
  width: 80px;
  white-space: nowrap;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="js_pesquisarCancelaCancelamento();">
<table border="0" align="center" cellspacing="0" cellpadding="0" style="padding-top:40px;">
  <tr> 
    <td valign="top" align="center"> 
      <fieldset>
        <legend><b>Desfazer Cancelamento da Rescisão do Acordo</b></legend>
        <table align="center" border="0">
          <tr>
            <td title="<?=@$Tac10_sequencial?>" align="left">
              <b>Código:</b>
            </td>
            <td align="left">
              <?
                db_input('ac10_sequencial',10,$Iac10_sequencial,true,'text',3,"");
              ?>
            </td>
            <td align="left">&nbsp;</td>
          </tr>
          <tr>
            <td title="<?=@$Tac16_sequencial?>" align="left">
              <?php db_ancora($Lac16_sequencial, "js_pesquisaac16_sequencial(true);",$db_opcao); ?>
            </td>
            <td align="left">
              <?
                db_input('ac16_sequencial',10,$Iac16_sequencial,true,'text',
                         $db_opcao," onchange='js_pesquisaac16_sequencial(false);'");
              ?>
            </td>
            <td align="left">
              <?
                db_input('ac16_resumoobjeto',40,$Iac16_resumoobjeto,true,'text',3);
              ?>
            </td>
          </tr>
          <tr>
            <td title="<?=@$Tac10_datamovimento?>" align="left">
              <b>Data:</b>
            </td>
            <td align="left">
              <?
                db_inputdata('ac10_datamovimento',@$ac10_datamovimento_dia, 
                                                  @$ac10_datamovimento_mes, 
                                                  @$ac10_datamovimento_ano, true, 'text', $db_opcao, "");
              ?>
            </td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="3">
              <fieldset>
                <legend>
                  <b>Observação</b>
                </legend>
                  <?
                    db_textarea('ac10_obs',5,64,$Iac10_obs,true,'text',1,"");
                  ?>
              </fieldset>
            </td>
          </tr> 
        </table>
      </fieldset>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center">
      <input id="cancelar" name="cancelar" type="button" value="Cancelar" 
             onclick="return js_cancelarCancelamentoRecisao();" disabled>
      <input id="pesquisar" name="pesquisar" type="button" value="Pesquisar" onclick="js_pesquisarCancelaCancelamento();">
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
$('ac10_sequencial').style.width    = "100%";
$('ac16_sequencial').style.width    = "100%";
$('ac16_resumoobjeto').style.width  = "100%";
$('ac10_datamovimento').style.width = "100%";

var sUrl = 'con4_contratosmovimento.RPC.php';

/**
 * Pesquisa recisão cancelamento
 */
function js_pesquisarCancelaCancelamento() {

  $('cancelar').disabled  = true;
  var sUrl  = 'func_acordomovimentacao.php?movimento=1&tipo=7';
      sUrl += '&funcao_js=parent.js_mostrarPesquisaRecisaoCancelamento|ac10_sequencial';
      
  js_OpenJanelaIframe('top.corpo', 
                      'db_iframe_cancelamentorecisao', 
                      sUrl,
                      'Pesquisar Cancelamento de Recisão',
                      true);
}

/**
 * Retorno da pesquisa recisão cancelamento
 */
function js_mostrarPesquisaRecisaoCancelamento(chave) {

  $('cancelar').disabled  = false;
  js_getDadosRecisaoCancelamento(chave);
  db_iframe_cancelamentorecisao.hide();
}

/**
 * Busca o dados cancelamento da recisão
 */
function js_getDadosRecisaoCancelamento(iCodigo) {

  js_divCarregando('Aguarde pesquisando cancelamentos...', 'msgBoxGetDadosRecisaoCancelamentos');
   
  var oParam        = new Object();
  oParam.exec       = "getDadosRescisao";
  oParam.codigo     = iCodigo;
    
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+js_objectToJson(oParam), 
                                          onComplete: js_retornoGetDadosRecisaoCancelamentos
                                        }
                                );
}

/**
 * Retorno dos dados cancelamento da recisão
 */
function js_retornoGetDadosRecisaoCancelamentos(oAjax) {
  
  js_removeObj("msgBoxGetDadosRecisaoCancelamentos");
  
  var oRetorno = eval("("+oAjax.responseText+")");
  
  if (oRetorno.status == 2) {
    
    alert(oRetorno.erro.urlDecode());
    $('ac16_sequencial').value     = "";
    $('ac16_resumoobjeto').value   = "";
    $('ac10_datamovimento').value  = "";
    $('ac10_obs').value            = "";
    return false;
  } else {

    $('ac10_sequencial').value     = oRetorno.codigo;
    $('ac16_sequencial').value     = oRetorno.acordo;
    $('ac10_datamovimento').value  = js_formatar(oRetorno.datamovimento,'d');
    $('ac16_resumoobjeto').value   = oRetorno.descricao.urlDecode();
    $('ac10_obs').value            = "";
    return true;
  }

}

/**
 * Cancelar cancelamento de recisão
 */  
function js_cancelarCancelamentoRecisao() {
   
  if ($('ac16_sequencial').value == '') {
    
    alert('Acordo não informado!');
    return false;
  }
  
  if ($('ac10_datamovimento').value == '') {
    
    alert('Data não informada!');
    return false;
  }  
  
  if ($('ac10_sequencial').value == '') {
    
    alert('Código da recisão não informado! Verifique a pesquisa.');
    return false;
  }
  
  js_divCarregando('Aguarde cancelando cancelamento...','msgBoxCancelarCancelamentoRecisao');
   
  var oParam        = new Object();
  oParam.exec       = "desfazerCancelarRecisao";
  oParam.codigo     = $F('ac10_sequencial');
  oParam.observacao = encodeURIComponent(tagString($F('ac10_obs')));
    
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+js_objectToJson(oParam), 
                                          onComplete: js_retornoCancelarCancelamentoRecisao
                                        }
                                );  
}
  
/**
 * Retorno dos dados cancelar cancelamento da recisão
 */
function js_retornoCancelarCancelamentoRecisao(oAjax) {
  
  js_removeObj("msgBoxCancelarCancelamentoRecisao");
  
  var oRetorno = eval("("+oAjax.responseText+")");   
     
  $('ac10_sequencial').value    = "";
  $('ac16_sequencial').value    = "";
  $('ac16_resumoobjeto').value  = "";
  $('ac10_datamovimento').value = "";
  $('ac10_obs').value           = "";
  $('cancelar').disabled        = true;

  if (oRetorno.status == 2) {
    alert(oRetorno.erro.urlDecode());
  } else {
    alert("Anulação de cancelamento efetuada com Sucesso.");
  }
  
  js_pesquisarCancelaCancelamento();
  
}
</script>
</html>
