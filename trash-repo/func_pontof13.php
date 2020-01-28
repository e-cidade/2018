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
include("classes/db_pontof13_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clpontof13 = new cl_pontof13;
$clpontof13->rotulo->label("r34_anousu");
$clpontof13->rotulo->label("r34_mesusu");
$clpontof13->rotulo->label("r34_regist");
$clpontof13->rotulo->label("r34_rubric");
$clpontof13->rotulo->label("r34_regist");
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
            <td width="4%" align="right" nowrap title="<?=$Tr34_mesusu?>">
              <?=$Lr34_mesusu?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r34_mesusu",2,$Ir34_mesusu,true,"text",4,"","chave_r34_mesusu");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr34_regist?>">
              <?=$Lr34_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r34_regist",6,$Ir34_regist,true,"text",4,"","chave_r34_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr34_rubric?>">
              <?=$Lr34_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r34_rubric",4,$Ir34_rubric,true,"text",4,"","chave_r34_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr34_regist?>">
              <?=$Lr34_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r34_regist",6,$Ir34_regist,true,"text",4,"","chave_r34_regist");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pontof13.hide();">
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
           if(file_exists("funcoes/db_func_pontof13.php")==true){
             include("funcoes/db_func_pontof13.php");
           }else{
           $campos = "pontof13.*";
           }
        }
        if(isset($chave_r34_mesusu) && (trim($chave_r34_mesusu)!="") ){
	         $sql = $clpontof13->sql_query(db_getsession('DB_anousu'),$chave_r34_mesusu,$chave_r34_regist,$chave_r34_rubric,$campos,"r34_mesusu");
        }else if(isset($chave_r34_regist) && (trim($chave_r34_regist)!="") ){
	         $sql = $clpontof13->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r34_regist"," r34_regist like '$chave_r34_regist%' ");
        }else{
           $sql = $clpontof13->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r34_anousu#r34_mesusu#r34_regist#r34_rubric","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clpontof13->sql_record($clpontof13->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clpontof13->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r34_regist',false);</script>";
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