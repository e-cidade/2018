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
include("classes/db_vtfdias_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clvtfdias = new cl_vtfdias;
$clvtfdias->rotulo->label("r63_anousu");
$clvtfdias->rotulo->label("r63_mesusu");
$clvtfdias->rotulo->label("r63_regist");
$clvtfdias->rotulo->label("r63_vale");
$clvtfdias->rotulo->label("r63_difere");
$clvtfdias->rotulo->label("r63_dia");
$clvtfdias->rotulo->label("r63_regist");
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
            <td width="4%" align="right" nowrap title="<?=$Tr63_mesusu?>">
              <?=$Lr63_mesusu?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r63_mesusu",2,$Ir63_mesusu,true,"text",4,"","chave_r63_mesusu");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr63_regist?>">
              <?=$Lr63_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r63_regist",6,$Ir63_regist,true,"text",4,"","chave_r63_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr63_vale?>">
              <?=$Lr63_vale?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r63_vale",4,$Ir63_vale,true,"text",4,"","chave_r63_vale");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr63_difere?>">
              <?=$Lr63_difere?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r63_difere",1,$Ir63_difere,true,"text",4,"","chave_r63_difere");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr63_dia?>">
              <?=$Lr63_dia?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r63_dia",8,$Ir63_dia,true,"text",4,"","chave_r63_dia");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr63_regist?>">
              <?=$Lr63_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r63_regist",6,$Ir63_regist,true,"text",4,"","chave_r63_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_vtfdias.hide();">
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
           if(file_exists("funcoes/db_func_vtfdias.php")==true){
             include("funcoes/db_func_vtfdias.php");
           }else{
           $campos = "vtfdias.*";
           }
        }
        if(isset($chave_r63_mesusu) && (trim($chave_r63_mesusu)!="") ){
	         $sql = $clvtfdias->sql_query(db_getsession('DB_anousu'),$chave_r63_mesusu,$chave_r63_regist,$chave_r63_vale,$chave_r63_difere,$chave_r63_dia,$campos,"r63_mesusu");
        }else if(isset($chave_r63_regist) && (trim($chave_r63_regist)!="") ){
	         $sql = $clvtfdias->sql_query(db_getsession('DB_anousu'),"","","","","",$campos,"r63_regist"," r63_regist like '$chave_r63_regist%' ");
        }else{
           $sql = $clvtfdias->sql_query(db_getsession('DB_anousu'),"","","","","",$campos,"r63_anousu#r63_mesusu#r63_regist#r63_vale#r63_difere#r63_dia","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clvtfdias->sql_record($clvtfdias->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clvtfdias->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r63_regist',false);</script>";
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