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
include("classes/db_pontofa_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clpontofa = new cl_pontofa;
$clpontofa->rotulo->label("r21_anousu");
$clpontofa->rotulo->label("r21_mesusu");
$clpontofa->rotulo->label("r21_regist");
$clpontofa->rotulo->label("r21_rubric");
$clpontofa->rotulo->label("r21_regist");
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
            <td width="4%" align="right" nowrap title="<?=$Tr21_mesusu?>">
              <?=$Lr21_mesusu?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r21_mesusu",2,$Ir21_mesusu,true,"text",4,"","chave_r21_mesusu");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr21_regist?>">
              <?=$Lr21_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r21_regist",6,$Ir21_regist,true,"text",4,"","chave_r21_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr21_rubric?>">
              <?=$Lr21_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r21_rubric",4,$Ir21_rubric,true,"text",4,"","chave_r21_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr21_regist?>">
              <?=$Lr21_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r21_regist",6,$Ir21_regist,true,"text",4,"","chave_r21_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pontofa.hide();">
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
           if(file_exists("funcoes/db_func_pontofa.php")==true){
             include("funcoes/db_func_pontofa.php");
           }else{
           $campos = "pontofa.*";
           }
        }
        if(isset($chave_r21_mesusu) && (trim($chave_r21_mesusu)!="") ){
	         $sql = $clpontofa->sql_query(db_getsession('DB_anousu'),$chave_r21_mesusu,$chave_r21_regist,$chave_r21_rubric,$campos,"r21_mesusu");
        }else if(isset($chave_r21_regist) && (trim($chave_r21_regist)!="") ){
	         $sql = $clpontofa->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r21_regist"," r21_regist like '$chave_r21_regist%' ");
        }else{
           $sql = $clpontofa->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r21_anousu#r21_mesusu#r21_regist#r21_rubric","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clpontofa->sql_record($clpontofa->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clpontofa->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r21_regist',false);</script>";
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