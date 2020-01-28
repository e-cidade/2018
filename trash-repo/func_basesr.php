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
include("classes/db_basesr_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbasesr = new cl_basesr;
$clbasesr->rotulo->label("r09_anousu");
$clbasesr->rotulo->label("r09_mesusu");
$clbasesr->rotulo->label("r09_base");
$clbasesr->rotulo->label("r09_rubric");
$clbasesr->rotulo->label("r09_base");
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
            <td width="4%" align="right" nowrap title="<?=$Tr09_mesusu?>">
              <?=$Lr09_mesusu?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r09_mesusu",2,$Ir09_mesusu,true,"text",4,"","chave_r09_mesusu");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr09_base?>">
              <?=$Lr09_base?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r09_base",4,$Ir09_base,true,"text",4,"","chave_r09_base");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr09_rubric?>">
              <?=$Lr09_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r09_rubric",4,$Ir09_rubric,true,"text",4,"","chave_r09_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr09_base?>">
              <?=$Lr09_base?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r09_base",4,$Ir09_base,true,"text",4,"","chave_r09_base");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_basesr.hide();">
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
           if(file_exists("funcoes/db_func_basesr.php")==true){
             include("funcoes/db_func_basesr.php");
           }else{
           $campos = "basesr.*";
           }
        }
        if(isset($chave_r09_mesusu) && (trim($chave_r09_mesusu)!="") ){
	         $sql = $clbasesr->sql_query(db_getsession('DB_anousu'),$chave_r09_mesusu,$chave_r09_base,$chave_r09_rubric,$campos,"r09_mesusu");
        }else if(isset($chave_r09_base) && (trim($chave_r09_base)!="") ){
	         $sql = $clbasesr->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r09_base"," r09_base like '$chave_r09_base%' ");
        }else{
           $sql = $clbasesr->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r09_anousu#r09_mesusu#r09_base#r09_rubric",db_getsession('DB_instit'),"");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clbasesr->sql_record($clbasesr->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clbasesr->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r09_base',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
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