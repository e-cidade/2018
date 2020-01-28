<?php
/*
 *     E-cidade Software P�blico para Gest�o Municipal                
 *  Copyright (C) 2014  DBseller Servi�os de Inform�tica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa � software livre; voc� pode redistribu�-lo e/ou     
 *  modific�-lo sob os termos da Licen�a P�blica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a vers�o 2 da      
 *  Licen�a como (a seu crit�rio) qualquer vers�o mais nova.          
 *                                                                    
 *  Este programa e distribu�do na expectativa de ser �til, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia impl�cita de              
 *  COMERCIALIZA��O ou de ADEQUA��O A QUALQUER PROP�SITO EM           
 *  PARTICULAR. Consulte a Licen�a P�blica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU     
 *  junto com este programa; se n�o, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  C�pia da licen�a no diret�rio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * 
 * @author Iuri Guntchnigg
 * @revision $Author: dbiuri $
 * @version $Revision: 1.2 $
 */
require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_app.utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
$oPost     = db_utils::postMemory($_POST);
$sFileName = "";
$sPcpasp   = '';
$sUrl      = 'con4_importarplanocontatce002.php';
if (USE_PCASP) {

  $sPcpasp = ' do PCASP';
  $sUrl    = 'con4_importarplanocontapcasp002.php';
}
if (isset($oPost->btnEnviar)) {
  
  move_uploaded_file($_FILES["arquivotce"]["tmp_name"], "tmp/{$_FILES["arquivotce"]["name"]}");
  $sFileName  = $_FILES["arquivotce"]["name"];
}
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
  <body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
    <table >
      <tr height="25">
        <td>
          &nbsp;
        </td>
      </tr>
     </table>
     <center>
       <form name='form1' method="post" enctype="multipart/form-data">
       <table>
         <tr>
           <td>
             <fieldset>
               <legend>
                 <b>
                   Importar Plano de Contas <?=$sPcpasp?>
                 </b>
                </legend>
                <table>
                  <tr>  
                    <td>
                      <b>Informe o Arquivo:</b>
                    </td>
                    <td>
                      <input type="file" name='arquivotce' id='arquivotce'>
                    </td>
                  </tr>
                </table>
             </fieldset>
           </td>
         </tr>
         <tr>
           <td colspan="2" style="text-align: center">
             <input type="submit" name='btnEnviar' value='Importar Arquivo'>
           </td>
         </tr>
       </table>
       </form>
     </center>
  </body>
</html>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
<script>
  var sFileName  = '<?=$sFileName?>';
  
  if (sFileName  != "") {
  
    js_OpenJanelaIframe('','iframe_importar',
                        '<?=$sUrl?>?sFileName='+sFileName
                        );
  
  }
</script>