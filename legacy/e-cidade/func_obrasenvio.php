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
require_once("dbforms/db_funcoes.php");

require_once("classes/db_obrasenvio_classe.php");

db_postmemory($HTTP_POST_VARS);

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oDaoObrasEnvio = new cl_obrasenvio;
$oDaoObrasEnvio->rotulo->label("ob16_codobrasenvio");
$oDaoObrasEnvio->rotulo->label("ob16_login");
?>
<html>
 <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
 </head>
 
 <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
 
   <table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
   
     <tr> 
       <td height="63" align="center" valign="top">
       
   	     <form name="form2" method="post" action="" >
           <table width="35%" border="0" align="center" cellspacing="0">
           
             <tr> 
               <td width="4%" align="right" nowrap title="<?=$Tob16_codobrasenvio?>">
                 <?=$Lob16_codobrasenvio?>
               </td>
               <td width="96%" align="left" nowrap> 
                 <?
                  db_input("ob16_codobrasenvio", 10, $Iob16_codobrasenvio, true, "text", 4, "", "chave_ob16_codobrasenvio");
   		           ?>
               </td>
             </tr>
             
             <tr> 
               <td width="4%" align="right" nowrap title="<?=$Tob16_login?>">
                 <?=$Lob16_login?>
               </td>
               <td width="96%" align="left" nowrap> 
                 <?
   		            db_input("ob16_login", 10, $Iob16_login, true, "text", 4, "", "chave_ob16_login");
   		           ?>
               </td>
             </tr>
             
             <tr> 
               <td colspan="2" align="center"> 
                 <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
                 <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
                 <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_obrasenvio.hide();">
               </td>
             </tr>
             
           </table>
         </form>
         
       </td>
     </tr>
     
     <tr> 
       <td align="center" valign="top"> 
         <?
         if ( !isset($pesquisa_chave) ) {
            
           if ( isset($campos) == false) {
              
             if (file_exists("funcoes/db_func_obrasenvio.php") == true) {
               include("funcoes/db_func_obrasenvio.php");
             } else {
               $campos = "obrasenvio.*";
             }
           }
           
           if ( isset($chave_ob16_codobrasenvio) && trim($chave_ob16_codobrasenvio) != "" ) {
   	         $sSqlPesquisa = $oDaoObrasEnvio->sql_query($chave_ob16_codobrasenvio, $campos, "ob16_codobrasenvio");
           } else if ( isset($chave_ob16_login) && trim($chave_ob16_login)!="" ) {
   	         $sSqlPesquisa = $oDaoObrasEnvio->sql_query("", $campos, "ob16_login", " ob16_login like '$chave_ob16_login%' ");
           } else {
             $sSqlPesquisa = $oDaoObrasEnvio->sql_query("", $campos, "ob16_codobrasenvio", "");
           }
           db_lovrot($sSqlPesquisa, 15, "()", "", $funcao_js);
           
         } else {
           
           if ($pesquisa_chave!=null && $pesquisa_chave!="") {
             
             $result = $oDaoObrasEnvio->sql_record($oDaoObrasEnvio->sql_query($pesquisa_chave) );
             
             if ($oDaoObrasEnvio->numrows!=0) {
               
               db_fieldsmemory($result, 0);
               echo "<script>".$funcao_js."('$ob16_nomearq', false);</script>";
             } else {
               echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado', true);</script>";
             }
           } else {
             echo "<script>".$funcao_js."('', false);</script>";
           }
         }
         ?>
        </td>
      </tr>
   </table>
 </body>
</html>
<?
if ( !isset($pesquisa_chave) ) {
  ?>
  <script>
  </script>
  <?
}
?>