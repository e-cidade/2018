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
require_once ("libs/db_utils.php");
require_once ("libs/db_app.utils.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
$oGet     = db_utils::postMemory($_POST);

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
  db_app::load("scripts.js, prototype.js");
  db_app::load("estilos.css");
?>
</head>
<body style='margin-top: 25px; background-color: #CCCCCC;' >
  <form name="form1" id='form1' method="post" action="" enctype="multipart/form-data">
    <center>
      <div style="display: table;">
        <fieldset>
          <legend><b>Importa base municipal do cadastro único</b></legend>
        <table border="0" align="left">
         <tr>
          <td colspan="2">
           <b>Arquivo Base:</b>
           <input name="flArquivo" id='flArquivo' type="file" size='50'>
           <input name="nomearquivoservidor" id='nomearquivoservidor' type="hidden" size='10'>
          </td>
         </tr>
        </table>
        </fieldset>
      </div>
      <input name="processar" type="button" id="arquivo" value="Processar" onclick="js_processar()">
    </center>
  </form>
  <div style='position:absolute;top: 200px; left:15px;
              border:1px solid black;
              width:500px;
              text-align: left;
              padding:3px;
              z-index:1000000;
              background-color: #FFFFCC;
              display:none;' id='ajudaItem'>
  
  </div>
  <div id='uploadIframeBox' style="display: none;"></div>
</body>
<?php db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</html>
<script type="text/javascript">

$('flArquivo').observe('change', function() {
  js_criarIframeBox('flArquivo', 'nomearquivoservidor');
});

function retornoUploadArquivo(sArquivo) {
 $('nomearquivoservidor').value = sArquivo;
}

function js_criarIframeBox(sIdCampo, sCampoRetorno) {

 js_divCarregando('Aguarde... carregando arquivo...', 'msgbox');

 var iFrame      = document.createElement("iframe");
 var sParametros = "clone=form1&idcampo="+sIdCampo+"&function=retornoUploadArquivo&camporetorno="+sCampoRetorno;
 iFrame.src      = "func_iframeupload.php?"+sParametros;
 iFrame.id       = 'uploadIframe';
 iFrame.width    = '100%';
  
 $('uploadIframeBox').appendChild(iFrame);
}

function js_endloading() {

  js_removeObj('msgbox');
  $('uploadIframeBox').removeChild($('uploadIframe'));
  $('arquivo').removeAttribute('disabled');
  if (confirm('Confirma o processamento da Base de Dados Municipal?')) {
    js_processar();
  }
}

/**
 * Processa o arquivo
 */
function js_processar() { 

  var url             = 'soc4_importabasemunicipio.RPC.php';
  var oObject         = new Object();
  oObject.exec        = "importaBase";
  oObject.arquivo     = encodeURIComponent($F('nomearquivoservidor'));
  js_divCarregando('Importando Base! Aguarde...','msgBox');
  var objAjax   = new Ajax.Request (url,{
                                         method:'post',
                                         parameters:'json='+Object.toJSON(oObject), 
                                         onComplete:js_retornoImportacao
                                        }
                                   );
}
/**
 * Retorno da Importação
 */
function js_retornoImportacao(oJson) {

  js_removeObj("msgBox");  
  var oRetorno = eval("("+oJson.responseText+")");
  alert(oRetorno.message);
}
</script>