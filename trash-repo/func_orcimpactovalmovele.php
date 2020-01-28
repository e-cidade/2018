<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_orcimpactovalmovele_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcimpactovalmovele = new cl_orcimpactovalmovele;
$clorcimpactovalmovele->rotulo->label("o66_codseqimpmov");
$clorcimpactovalmovele->rotulo->label("o66_codele");
$clorcimpactovalmovele->rotulo->label("o66_codele");
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
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To66_codseqimpmov?>">
              <?=$Lo66_codseqimpmov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o66_codseqimpmov",5,$Io66_codseqimpmov,true,"text",4,"","chave_o66_codseqimpmov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To66_codele?>">
              <?=$Lo66_codele?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o66_codele",6,$Io66_codele,true,"text",4,"","chave_o66_codele");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To66_codele?>">
              <?=$Lo66_codele?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o66_codele",6,$Io66_codele,true,"text",4,"","chave_o66_codele");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcimpactovalmovele.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_orcimpactovalmovele.php")==true){
             include("funcoes/db_func_orcimpactovalmovele.php");
           }else{
           $campos = "orcimpactovalmovele.*";
           }
        }
        if(isset($chave_o66_codseqimpmov) && (trim($chave_o66_codseqimpmov)!="") ){
	         $sql = $clorcimpactovalmovele->sql_query($chave_o66_codseqimpmov,$chave_o66_codele,$campos,"o66_codseqimpmov");
        }else if(isset($chave_o66_codele) && (trim($chave_o66_codele)!="") ){
	         $sql = $clorcimpactovalmovele->sql_query("","",$campos,"o66_codele"," o66_codele like '$chave_o66_codele%' ");
        }else{
           $sql = $clorcimpactovalmovele->sql_query("","",$campos,"o66_codseqimpmov#o66_codele","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorcimpactovalmovele->sql_record($clorcimpactovalmovele->sql_query($pesquisa_chave));
          if($clorcimpactovalmovele->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o66_codele',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>