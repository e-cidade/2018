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
include("classes/db_procprocessodoc_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprocprocessodoc = new cl_procprocessodoc;
$clprocprocessodoc->rotulo->label("p81_codproc");
$clprocprocessodoc->rotulo->label("p81_coddoc");
$clprocprocessodoc->rotulo->label("p81_coddoc");
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
            <td width="4%" align="right" nowrap title="<?=$Tp81_codproc?>">
              <?=$Lp81_codproc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p81_codproc",8,$Ip81_codproc,true,"text",4,"","chave_p81_codproc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp81_coddoc?>">
              <?=$Lp81_coddoc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p81_coddoc",3,$Ip81_coddoc,true,"text",4,"","chave_p81_coddoc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tp81_coddoc?>">
              <?=$Lp81_coddoc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("p81_coddoc",3,$Ip81_coddoc,true,"text",4,"","chave_p81_coddoc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_procprocessodoc.hide();">
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
           if(file_exists("funcoes/db_func_procprocessodoc.php")==true){
             include("funcoes/db_func_procprocessodoc.php");
           }else{
           $campos = "procprocessodoc.*";
           }
        }
        if(isset($chave_p81_codproc) && (trim($chave_p81_codproc)!="") ){
	         $sql = $clprocprocessodoc->sql_query($chave_p81_codproc,$chave_p81_coddoc,$campos,"p81_codproc");
        }else if(isset($chave_p81_coddoc) && (trim($chave_p81_coddoc)!="") ){
	         $sql = $clprocprocessodoc->sql_query("","",$campos,"p81_coddoc"," p81_coddoc like '$chave_p81_coddoc%' ");
        }else{
           $sql = $clprocprocessodoc->sql_query("","",$campos,"p81_codproc#p81_coddoc","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clprocprocessodoc->sql_record($clprocprocessodoc->sql_query($pesquisa_chave));
          if($clprocprocessodoc->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$p81_coddoc',false);</script>";
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