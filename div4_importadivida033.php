<?
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
require_once("libs/db_utils.php");


$oPost          = db_utils::postMemory($_POST);
$oGet           = db_utils::postMemory($_GET);

$oProcesso      = db_getsession("oDadosProcesso");
  

?>
<html>
  <body>
   <form name="form1">
    <center>
    
      <fieldset style="margin-top: 50px; width: 400px;">
        <legend>
          <strong>
            Selecione a Procedência 
          </strong>
        </legend>
      
        <table width="600" height="350" border="0" cellspacing="0" cellpadding="0">
          <tr>
        	  <td align="center" valign="top" >
              <div id='process' style='visibility:visible'><b><blink>Processando...</blink></b></div>
        	  </td>
        	</tr>
        	<tr> 
        	  <td height="350" align="center" valign="top" > 
             <br>
        	    <iframe name="iframe" id="iframe"  marginwidth="0" marginheight="0" frameborder="0" src="div4_importadivida044.php?chave_origem=<?=$k00_tipo_or?>&chave_destino=<?=$k00_tipo_des?>" width="600" height="300"></iframe>
        	  </td>
        	</tr>
        </table>
      
      </fieldset>     
 	    <input style="margin-top: 20px;" disabled name="gerar" type="button" id="gerar" value="Gerar Dados" onClick="js_verificar()">
      
    </center>
   </form>
  </body>
</html>
<script>
function js_verificar(){
  cont=0;
  for(i=0;i<iframe.document.form1.length;i++){
    if(iframe.document.form1.elements[i].type=="select-one"){
      if(iframe.document.form1.elements[i].value==0){
        alert(" Usuário: \n\n Uma ou mais procedências não foram corretamente \n informadas.");
	cont++;
	break;
      }
    }
  }
  if(cont==0){
    iframe.document.form1.submit();
    document.getElementById('process').style.visibility='visible';    
  }
}
</script>