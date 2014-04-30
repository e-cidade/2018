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
include("classes/db_inicialmov_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clinicialmov = new cl_inicialmov;
$clinicialmov->rotulo->label("v56_codmov");
$clinicialmov->rotulo->label("v56_codmov");
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
            <td width="4%" align="right" nowrap title="<?=$Tv56_codmov?>">
             <?=$Lv56_codmov?>
            </td>
            <td width="96%" align="left" nowrap> 
             <? db_input("v56_codmov",6,$Iv56_codmov,true,"text",4,"","chave_v56_codmov"); ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tv56_codmov?>">
              <?=$Lv56_codmov?>
            </td>
            <td width="96%" align="left" nowrap>
	    <?  db_input("v56_codmov",6,$Iv56_codmov,true,"text",4,"","chave_v56_codmov"); ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_inicialmov.hide();">
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
           $campos = "inicialmov.*";
        }
	$campos ="inicialmov.*,v52_descr";
        if(isset($chave_v56_codmov) && (trim($chave_v56_codmov)!="") ){
	         $sql = $clinicialmov->sql_query($chave_v56_codmov,$campos,"v56_codmov");
        }else if(isset($chave_v56_codmov) && (trim($chave_v56_codmov)!="") ){
	         $sql = $clinicialmov->sql_query("",$campos,"v56_codmov"," v56_codmov like '$chave_v56_codmov%' ");
        }else{
           $sql = $clinicialmov->sql_query("",$campos,"v56_codmov","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clinicialmov->sql_record($clinicialmov->sql_query($pesquisa_chave));
          if($clinicialmov->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v56_codmov',false);</script>";
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
document.form2.chave_v56_codmov.focus();
document.form2.chave_v56_codmov.select();
  </script>
  <?
}
?>
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_inicialmov_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clinicialmov = new cl_inicialmov;
$clinicialmov->rotulo->label("v56_codmov");
$clinicialmov->rotulo->label("v56_codmov");
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
            <td width="4%" align="right" nowrap title="<?=$Tv56_codmov?>">
              <?=$Lv56_codmov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v56_codmov",6,$Iv56_codmov,true,"text",4,"","chave_v56_codmov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tv56_codmov?>">
              <?=$Lv56_codmov?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v56_codmov",6,$Iv56_codmov,true,"text",4,"","chave_v56_codmov");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
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
           $campos = "inicialmov.*";
        }
        if(isset($chave_v56_codmov) && (trim($chave_v56_codmov)!="") ){
	         $sql = $clinicialmov->sql_query($chave_v56_codmov,$campos,"v56_codmov");
        }else if(isset($chave_v56_codmov) && (trim($chave_v56_codmov)!="") ){
	         $sql = $clinicialmov->sql_query("",$campos,"v56_codmov"," v56_codmov like '$chave_v56_codmov%' ");
        }else{
           $sql = $clinicialmov->sql_query("",$campos,"v56_codmov","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clinicialmov->sql_record($clinicialmov->sql_query($pesquisa_chave));
          if($clinicialmov->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v56_codmov',false);</script>";
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
document.form2.chave_v56_codmov.focus();
document.form2.chave_v56_codmov.select();
  </script>
  <?
}
?>