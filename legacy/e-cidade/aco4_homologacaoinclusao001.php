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

$db_opcao = 1;

$clacordo->rotulo->label();
$clacordomovimentacao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("ac16_sequencial");
$clrotulo->label("ac16_resumoobjeto");
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" align="center" cellspacing="0" cellpadding="0" style="padding-top:40px;">
  <tr> 
    <td valign="top" align="center"> 
      <fieldset>
        <legend><b>Homologação do Acordo</b></legend>
	      <table align="center" border="0">
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
		        <td colspan="3">
		          <fieldset id="fieldsetobservacao" class="fieldsetinterno">
		            <legend>
		              <b>Observação</b>
		            </legend>
		              <?
		                db_textarea('ac10_obs',5,64,$Iac10_obs,true,'text',$db_opcao,"");
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
      <input id="incluir" name="incluir" type="button" value="Incluir" onclick="return js_homologarContrato();">
    </td>
  </tr>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<script>
$('ac16_sequencial').style.width   = "100%";
$('ac16_resumoobjeto').style.width = "100%";

var sUrl = 'con4_contratosmovimento.RPC.php';

/**
 * Pesquisa acordos
 */
function js_pesquisaac16_sequencial(lMostrar) {

  if (lMostrar == true) {
    
    var sUrl = 'func_acordo.php?funcao_js=parent.js_mostraacordo1|ac16_sequencial|ac16_resumoobjeto&iTipoFiltro=1';
    js_OpenJanelaIframe('top.corpo', 
                        'db_iframe_acordo', 
                        sUrl,
                        'Pesquisar Acordo',
                        true);
  } else {
  
    if ($('ac16_sequencial').value != '') { 
    
      var sUrl = 'func_acordo.php?descricao=true&pesquisa_chave='+$('ac16_sequencial').value+
                 '&funcao_js=parent.js_mostraacordo';
                 
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_acordo',
                          sUrl,
                          'Pesquisar Acordo',
                          false);
     } else {
       $('ac16_sequencial').value = ''; 
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

/**
 * Incluir homologacao
 */  
function js_homologarContrato() {
   
  if ($('ac16_sequencial').value == '') {
    
    alert('Acordo não informado!');
    return false;
  }
  
  js_divCarregando('Aguarde incluindo homologação...','msgBoxHomologacaoContrato');
   
  var oParam        = new Object();
  oParam.exec       = "homologarContrato";
  oParam.acordo     = $F('ac16_sequencial');
  oParam.observacao = encodeURIComponent(tagString($F('ac10_obs')));
    
  var oAjax   = new Ajax.Request( sUrl, {
                                          method: 'post', 
                                          parameters: 'json='+js_objectToJson(oParam), 
                                          onComplete: js_retornoDadosHomologacao
                                        }
                                );  
}
  
/**
 * Retorna os dados da homologacao
 */
function js_retornoDadosHomologacao(oAjax) {
  
  js_removeObj("msgBoxHomologacaoContrato");
  
  var oRetorno = eval("("+oAjax.responseText+")");
     
  $('ac16_sequencial').value   = "";
  $('ac16_resumoobjeto').value = "";
  $('ac10_obs').value          = "";

  if (oRetorno.status == 2) {
    
    alert(oRetorno.erro.urlDecode());
    return false;
  } else {
  
    alert("Inclusão efetuada com Sucesso.");
    return true;
  }
}
</script>
</html>
