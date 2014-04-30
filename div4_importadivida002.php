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
require_once ("libs/JSON.php");

db_postmemory($HTTP_SERVER_VARS);

$oPost          = db_utils::postMemory($_POST);
$oGet           = db_utils::postMemory($_GET);
$oJson          = new services_json();
$oParam         = $oJson->decode(str_replace("\\","",$_GET["oProcesso"]));

$oProcesso = new stdClass();
$oProcesso->lProcessoSistema = $oParam->lProcessoSistema ;   
$oProcesso->iProcesso        = $oParam->iProcesso        ;
$oProcesso->sTitular         = $oParam->sTitular         ;
$oProcesso->dDataProcesso    = implode("-", array_reverse(explode("/",$oParam->dDataProcesso)));

db_putsession("oDadosProcesso", $oProcesso);

 


?>
<html>
  <body style="background-color: #CCC;">
   <form name="form1">
    <center>
    
    <fieldset style="margin-top: 20px; width: 600px;">
      <legend>
        <strong>
          Selecione a procedência para cada receita
        </strong>
      </legend>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <!-- 
        <tr>
	        <td align="center" valign="top" bgcolor="#CCCCCC" width="100%">
            <strong>Selecione a procedência para cada receita</strong>
	          <br><br>
	       </td>
	     </tr>
       -->
	     
	     

	     <tr> 
	       <td height="350" width="100%" align="center" valign="top" bgcolor="#CCCCCC"> 
            <br>
	         <iframe name="iframe" id="iframe"  
	                 marginwidth="0" 
	                 marginheight="0" 
	                 frameborder="0" 
	                 src="div4_importadivida003.php?chave_origem=<?=$k00_tipo_or?>&chave_destino=<?=$k00_tipo_des?>&tipoparc=<?=$tipoparc?>&uni=<?=$uni?>&datavenc=<?=$datavenc?>" width="850" height="300"></iframe>
	       </td>
	     </tr>
	     
       <tr>
	       <td width="100%" align="center" valign="top" bgcolor="#CCCCCC">
            <div id='process' style='visibility:visible'><b><blink>Processando...</blink></b></div>
            <br><br>
	       </td>
	     </tr>	     
	     
	     
     </table>
    </fieldset>    
    
	  <input disabled name="gerar" style="margin-top: 20px;" type="button" id="gerar" value="Gerar Dados" onClick="js_verificar()">
     
     
    </center>
   </form>
  </body>
</html>
<script>
function js_verificar(){
  cont=0;
  pass = 'f';
  for (i = 0; i < iframe.document.form1.length; i++) {
    
    if (iframe.document.form1.elements[i].type == "select-one") {
      
      if (iframe.document.form1.elements[i].value != 0) {
        //alert(" Usuário: \n\n Uma ou mais procedências não foram corretamente \n informadas.");
	      cont++;
        pass = 't';
	//break;
      }
    }
  }
  if (pass == 't') {

    iframe.document.form1.procreg.value = 't';
    iframe.document.form1.submit();
    document.getElementById('process').style.visibility='visible';    
  }
}
</script>