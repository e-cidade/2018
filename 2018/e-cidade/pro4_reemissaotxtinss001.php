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
require_once("libs/db_app.utils.php");
require_once("libs/db_utils.php");

require_once("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

$oRotulo  = new rotulocampo;
$oRotulo->label('ob16_codobrasenvio');
?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("estilos.css");
      db_app::load("scripts.js");
      db_app::load("strings.js");
      db_app::load("prototype.js"); 
    ?>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <BR>
    <BR>
    <BR>
    <BR>
    <center>
      <form name="form1" id="form1">
      
        <fieldset style="width: 500px;">
          <legend><strong>Reemissão de Arquivo do INSS: </strong></legend>
	        <BR>
		      <table border="0" cellspacing="0" cellpadding="0">
          
		        <tr> 
		          <td>     
		            <?
		             db_ancora($Lob16_codobrasenvio,' js_pesquisa(true); ',1);
		            ?>
              </td>
                
		          <td> 
		            <?
		             db_input('ob16_codobrasenvio',  6, $Iob16_codobrasenvio, true, 'text', 1, "onchange='js_pesquisa(false)'");
		             db_input('ob16_nomearq'      , 40, 0                   , true, 'text', 3, "");
		            ?>
		          </td>
		        </tr>
            
		      </table>
          
	        <BR>
        </fieldset>
      </form>
      <br>
      <input name="reemissao"   type="button"  value="Processsar"  onclick="js_salvarRPC(1, $('ob16_codobrasenvio'));">      
    </center>
    <?php
    db_menu(db_getsession("DB_id_usuario"),
            db_getsession("DB_modulo"),
            db_getsession("DB_anousu"),
            db_getsession("DB_instit")
           );
    ?>
  </body>
</html>
<script>
/**
 * Abre lookup de pesquisa dos arquivos txt gerados
 */
function js_pesquisa(lMostra) {
  
  if ( lMostra ) {
    
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe',
                        'func_obrasenvio.php?funcao_js=parent.js_preencheDadosLookUp|ob16_codobrasenvio|ob16_nomearq',
                        'Pesquisa Arquivos TXT do INSS',
                        true);
  } else {
    
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe',
                        'func_obrasenvio.php?pesquisa_chave=' + $F('ob16_codobrasenvio') + '&funcao_js=parent.js_preencheDadosDigitacao',
                        'Pesquisa Arquivos TXT do INSS',
                        false);
  }
}
/**
 * Retorna dados quando item da lookup for selecionado
 */
function js_preencheDadosLookUp(iCodigoEnvio, sNomeArquivo) {
  
    $('ob16_codobrasenvio').value = iCodigoEnvio;
    $('ob16_nomearq')      .value = sNomeArquivo;
    db_iframe.hide();
}
/**
 * Retorna dados quando houver valor no campo de pesquisa
 */
function js_preencheDadosDigitacao(sNomeArquivo, lErro) {
  
  $('ob16_nomearq').value = sNomeArquivo;
  
  if ( lErro == true ) {
    
    $('ob16_codobrasenvio').value = '';
    $('ob16_codobrasenvio').focus();
  }
}

/**
 * Funcao para salvar os dados
 * @param integer iAcao | Tipo de ação a ser executada
 *                      +->  1 - Reemissao 
 *                      +->  2 - Exclusão
 */
function js_salvarRPC(iAcao, oCodigoTxt) {

  var sExecucao   = null;
  var sArquivoRPC = "pro4_txtINSS.RPC.php";
  var iCodigoTxt  = oCodigoTxt.value;
  
  if (!oCodigoTxt || iCodigoTxt == "") {
    
    alert(_M('tributario.projetos.pro4_reemissaotxtinss001.preencha_codigo'));
    oCodigoTxt.focus();
    return false;
  }  
  switch (iAcao) {
    case 1:
      sExecucao = "reemitirTXT";
    break;
    case 2:
      sExecucao = "excluirTXT";
    break;
    default:
      alert(_M('tributario.projetos.pro4_reemissaotxtinss001.defina_opcao'));
      return false;
    break; 
  }
  
  js_divCarregando(_M('tributario.projetos.pro4_reemissaotxtinss001.processando_operacao'), 'msgBox');

	/**
	 * Objeto que o RPC irá interpretar
	 */
  var oParam                 = new Object();
  oParam.exec                = sExecucao;
  oParam.iCodigoTxt          = iCodigoTxt;
	/**
	 * Define os Parametros para a Conexão com RPC
	 */
  var oAjaxParameters        = new Object();
  oAjaxParameters.parameters = "json="+Object.toJSON(oParam);
  oAjaxParameters.onComplete = js_retornoRequisicao;
  
	/**
	 * Executa a Requisição
	 */
  var oAjax                  = new Ajax.Request(sArquivoRPC, oAjaxParameters);
};

/**
 * Funcao para tratar retorno do rpc
 */
 function js_retornoRequisicao(oRetornoAjax) {
   
   var oRetorno = eval("("+ oRetornoAjax.responseText+")");
   js_removeObj('msgBox');
   
   if (oRetorno.iStatus == "2") {
     alert(oRetorno.sMessage.urlDecode());
   } else {
     
     var listagem  = oRetorno.sArquivoTXT + "# Download do Arquivo - " + oRetorno.sArquivoTXT;
     js_montarlista(listagem,'form1');  
	   window.location = window.location; 
   }
 }
</script>