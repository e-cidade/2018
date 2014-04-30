<?
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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("model/dbLayoutReader.model.php");
require_once("model/dbLayoutLinha.model.php");

$oDaoEscola = db_utils::getdao('escola');
$iEscola    = db_getsession("DB_coddepto");
$iAnoAtual  = date("Y",db_getsession("DB_datausu"));
$oPost      = db_utils::postMemory($_POST);
$oFile      = db_utils::postMemory($_FILES);

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
 <body style='margin-top: 25px' bgcolor="#cccccc">
  <center>
   <form name="form1" id='form1' enctype="multipart/form-data" method="post" action="" >
    <table border="0" cellspacing="0" cellpadding="0">
     <tr>
      <td height="100%" align="left" valign="top" bgcolor="#CCCCCC">
       <fieldset style="width:95%">
       <legend><b>Importação Codigo INEP - Docente/ Aluno</b></legend>
        <?
         $sSqlEscola       = $oDaoEscola->sql_query("","ed18_c_codigoinep","","ed18_i_codigo = $iEscola");
         $rsEscola         = $oDaoEscola->sql_record($sSqlEscola);
         $iCodigoInepBanco = db_utils::fieldsMemory($rsEscola,0)->ed18_c_codigoinep;
        ?>
         <table border="0" align="left">
          <tr>
           <td>
             <b>Ano:</b>
            </td>
            <td> 
             <select name="anoarquivo" id='anoarquivo' onchange="js_trocaano(this.value)">
              <option value="<?=$iAnoAtual?>" <?=@$ano_opcao==$iAnoAtual?"selected":""?>><?=$iAnoAtual?></option>
              <option value="<?=$iAnoAtual-1?>" <?=@$ano_opcao==$iAnoAtual-1?"selected":""?>><?=$iAnoAtual-1?></option>
             </select>
           </td>
          </tr>
          <tr>
           <td nowrap="nowrap">
             <b>Arquivo para Importação:</b>
           </td>
           <td>
             <?
               $aTipoArquivo = array(0 => "Selecione",
                                     1 => "Aluno", 
                                     2 => "Docente"
                                    );
               db_select("tipoarquivo", $aTipoArquivo, true, 1);                         
             ?>
           </td>
          </tr>
          <tr>
           <td nowrap="nowrap">
            <b>Arquivo de importação:</b>
           </td>
           <td>
            <?db_input('arquivo', 25,@$Iarquivo,true,'file',3,"");?>
             <input id='nomearquivo' type='hidden'>
           </td> 
          </tr>
         </table>
        </fieldset>
       </center>
      </td>
     </tr>
     <tr>
      <td align="center">
        <input type="button" name="importar" value="Importar" onclick="js_processarRetorno()"/>
      </td>
     </tr>
    </table>
   </form>
  </center>
  <?
   db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),
   db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
  <div id='uploadIframeBox' style='display:none'></div>
 </body>
</html>
<script>
var sURLRPC = 'edu4_censoalunosseminep.RPC.php';
function js_processarRetorno() {
  
  var iTipoRetorno = $F('tipoarquivo');
  var sArquivo     = $F('nomearquivo');
  if (iTipoRetorno == 0) {
  
    alert('Informe um tipo de arquivo para ser importado');
    return false;
  }
  if (sArquivo == '') {
   
    alert('Selecione um arquivo válido para a importação');
    return false;
  }
  js_divCarregando("Aguarde, importando dados...", "msgbox");
  var oParam         = new Object();
  oParam.exec        = 'processarArquivo';
  oParam.tiporetorno = iTipoRetorno;
  oParam.arquivo     = sArquivo;
  
  var oAjax = new Ajax.Request(sURLRPC, 
                               {
                                method: 'post',
                                parameters:'?json='+Object.toJSON(oParam),
                                onComplete: function (oResponse) {
                                
                                  js_removeObj('msgbox');
                                  var oRetorno = eval('('+oResponse.responseText+')');
                                  if (oRetorno.status == 1) {
                                  
                                    alert('Arquivo processado com sucesso.');
                                    $('form1').reset();
                                  } else {
                                    alert(oRetorno.message.urlDecode());
                                  }
                                }
                               });
}
function retornoUploadArquivo(sArquivo) {
  $('nomearquivo').value = sArquivo;
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
}
$('arquivo').observe('change', function() {
   js_criarIframeBox('arquivo', 'nomearquivo');
});

$('tipoarquivo').style.width = '200px';
$('anoarquivo').style.width  = '200px';
</script>