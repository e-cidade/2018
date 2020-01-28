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
include("classes/db_itbimatric_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clitbimatric = new cl_itbimatric;
$clitbimatric->rotulo->label("it06_guia");
$clitbimatric->rotulo->label("it06_matric");
$clitbimatric->rotulo->label("it06_matric");
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
            <td width="4%" align="right" nowrap title="<?=$Tit06_guia?>">
              <?=$Lit06_guia?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("it06_guia",10,$Iit06_guia,true,"text",4,"","chave_it06_guia");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tit06_matric?>">
              <?=$Lit06_matric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("it06_matric",8,$Iit06_matric,true,"text",4,"","chave_it06_matric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tit06_matric?>">
              <?=$Lit06_matric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("it06_matric",8,$Iit06_matric,true,"text",4,"","chave_it06_matric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframeitbimatric.hide();">
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
           if(file_exists("funcoes/db_func_itbimatric.php")==true){
             include("funcoes/db_func_itbimatric.php");
           }else{
           $campos = "itbimatric.*";
           }
        }
        if(isset($chave_it06_guia) && (trim($chave_it06_guia)!="") ){
	         $sql = $clitbimatric->sql_query($chave_it06_guia,$chave_it06_matric,$campos,"it06_guia");
        }else if(isset($chave_it06_matric) && (trim($chave_it06_matric)!="") ){
	         $sql = $clitbimatric->sql_query("","",$campos,"it06_matric"," it06_matric like '$chave_it06_matric%' ");
        }else{
           $sql = $clitbimatric->sql_query("","",$campos,"it06_guia#it06_matric","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clitbimatric->sql_record($clitbimatric->sql_query($pesquisa_chave));
          if($clitbimatric->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$it06_matric',false);</script>";
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