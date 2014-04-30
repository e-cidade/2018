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
include("classes/db_pontofr_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clpontofr = new cl_pontofr;
$clpontofr->rotulo->label("r19_anousu");
$clpontofr->rotulo->label("r19_mesusu");
$clpontofr->rotulo->label("r19_regist");
$clpontofr->rotulo->label("r19_rubric");
$clpontofr->rotulo->label("r19_tpp");
$clpontofr->rotulo->label("r19_regist");
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
            <td width="4%" align="right" nowrap title="<?=$Tr19_mesusu?>">
              <?=$Lr19_mesusu?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r19_mesusu",2,$Ir19_mesusu,true,"text",4,"","chave_r19_mesusu");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr19_regist?>">
              <?=$Lr19_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r19_regist",6,$Ir19_regist,true,"text",4,"","chave_r19_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr19_rubric?>">
              <?=$Lr19_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r19_rubric",4,$Ir19_rubric,true,"text",4,"","chave_r19_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr19_tpp?>">
              <?=$Lr19_tpp?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r19_tpp",1,$Ir19_tpp,true,"text",4,"","chave_r19_tpp");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr19_regist?>">
              <?=$Lr19_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r19_regist",6,$Ir19_regist,true,"text",4,"","chave_r19_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pontofr.hide();">
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
           if(file_exists("funcoes/db_func_pontofr.php")==true){
             include("funcoes/db_func_pontofr.php");
           }else{
           $campos = "pontofr.*";
           }
        }
        if(isset($chave_r19_mesusu) && (trim($chave_r19_mesusu)!="") ){
	         $sql = $clpontofr->sql_query(db_getsession('DB_anousu'),$chave_r19_mesusu,$chave_r19_regist,$chave_r19_rubric,$chave_r19_tpp,$campos,"r19_mesusu");
        }else if(isset($chave_r19_regist) && (trim($chave_r19_regist)!="") ){
	         $sql = $clpontofr->sql_query(db_getsession('DB_anousu'),"","","","",$campos,"r19_regist"," r19_regist like '$chave_r19_regist%' ");
        }else{
           $sql = $clpontofr->sql_query(db_getsession('DB_anousu'),"","","","",$campos,"r19_anousu#r19_mesusu#r19_regist#r19_rubric#r19_tpp","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clpontofr->sql_record($clpontofr->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clpontofr->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r19_regist',false);</script>";
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