<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

?>
<html>
  <head>
    <title>DBSeller Inform&aacute;tica Ltda</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Expires" CONTENT="0">
    <?php
      db_app::load("scripts.js");
      db_app::load("prototype.js");
      db_app::load("strings.js");
      db_app::load("arrays.js");
      db_app::load("datagrid.widget.js");
      db_app::load("grid.style.css");
      db_app::load("estilos.css");
      db_app::load("windowAux.widget.js");
      db_app::load("dbmessageBoard.widget.js");  
    ?>
  </head>
  <body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
    <BR>
    <BR>
    <BR>
    <center>
     <fieldset style="width:650px;">
       <legend><strong>Processar Recibos Baixados:</strong></legend>
       
       <table>
         <tr>
           <td><?php db_ancora("<B>Arquivo Retorno:</B>", "js_buscaArquivoRetorno();", 1)?></td>
           <td norwap>
             <?php 
               db_input("codret", 10, "I", true, "text", 3);
               db_input("arqret", 40, null, true, "text", 3);
                
             ?>
           </td>
         </tr>

         <tr>
           <td></td>
           <td></td>
         </tr>
       </table>     
     
     </fieldset>
     <input type="button" id="processar" value="Processar" onclick="js_processaArquivo()" >
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
 * Abre janela para pesquisar dados do
 * @return {void}
 */
function js_buscaArquivoRetorno() {

  var sUrl = 'func_disarq.php?lRetornoPagamentoTJ=1&funcao_js=parent.js_retornoPesquisa|codret|arqret';
  
  js_OpenJanelaIframe('',
                      'db_iframe_disarq',
                      sUrl,
                      'Pesquisa Arquivo de Retorno',
                      true);
}
/**
 * Preenche os campos do formulario com os dados da pesquisa
 * @return {void}
 */
function js_retornoPesquisa(iCodRet, sArqRet) {
 
  $('codret').value   = iCodRet;
  $('arqret').value   = sArqRet;
  db_iframe_disarq.hide();
  $('processar').disabled= false;
}

function js_processaArquivo() {

  if ( $F('codret') == "" ) {
    
    $('processar').disabled = true;
    return;
  }
  
  var oParam             = new Object();
  var oAjax              = new Object();
  
  oParam.sExec           = "incluiRemessa";
  oParam.iCodigoArquivo  = $F('codret');
  
  oAjax.method           = 'POST';
  oAjax.parameters       = 'json=' + Object.toJSON(oParam);
  oAjax.onComplete       =  js_retornoProcessamento;
                         
  js_divCarregando('Enviando Dados...', 'msgProcessamento');
  var oRequest           = new Ajax.Request("arr4_integracaotjpagamento.RPC.php", oAjax); 
}

function js_retornoProcessamento(oAjax){
  
  js_removeObj("msgProcessamento");
  
  var oRetorno = eval("("+oAjax.responseText+")");

  alert(oRetorno.sMessage.urlDecode().replace(/\\n/g, '\n') );
  if ( oRetorno.iStatus == 1 ) {
    window.location.href = "arr4_integracaotjpagamento001.php";
  } 
}
</script>