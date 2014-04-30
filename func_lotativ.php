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
include("classes/db_lotativ_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllotativ = new cl_lotativ;
$cllotativ->rotulo->label("r41_anousu");
$cllotativ->rotulo->label("r41_mesusu");
$cllotativ->rotulo->label("r41_lotac");
$cllotativ->rotulo->label("r41_rubric");
$cllotativ->rotulo->label("r41_lotac");
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
            <td width="4%" align="right" nowrap title="<?=$Tr41_mesusu?>">
              <?=$Lr41_mesusu?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r41_mesusu",2,$Ir41_mesusu,true,"text",4,"","chave_r41_mesusu");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr41_lotac?>">
              <?=$Lr41_lotac?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r41_lotac",4,$Ir41_lotac,true,"text",4,"","chave_r41_lotac");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr41_rubric?>">
              <?=$Lr41_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r41_rubric",4,$Ir41_rubric,true,"text",4,"","chave_r41_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr41_lotac?>">
              <?=$Lr41_lotac?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r41_lotac",4,$Ir41_lotac,true,"text",4,"","chave_r41_lotac");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_lotativ.hide();">
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
           if(file_exists("funcoes/db_func_lotativ.php")==true){
             include("funcoes/db_func_lotativ.php");
           }else{
           $campos = "lotativ.*";
           }
        }
        if(isset($chave_r41_mesusu) && (trim($chave_r41_mesusu)!="") ){
	         $sql = $cllotativ->sql_query(db_getsession('DB_anousu'),$chave_r41_mesusu,$chave_r41_lotac,$chave_r41_rubric,$campos,"r41_mesusu");
        }else if(isset($chave_r41_lotac) && (trim($chave_r41_lotac)!="") ){
	         $sql = $cllotativ->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r41_lotac"," r41_lotac like '$chave_r41_lotac%' ");
        }else{
           $sql = $cllotativ->sql_query(db_getsession('DB_anousu'),"","","",$campos,"r41_anousu#r41_mesusu#r41_lotac#r41_rubric","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cllotativ->sql_record($cllotativ->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($cllotativ->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r41_lotac',false);</script>";
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