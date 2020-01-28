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
include("classes/db_calendf_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcalendf = new cl_calendf;
$clcalendf->rotulo->label("r62_calend");
$clcalendf->rotulo->label("r62_data");
$clcalendf->rotulo->label("r62_data");
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
            <td width="4%" align="right" nowrap title="<?=$Tr62_calend?>">
              <?=$Lr62_calend?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r62_calend",2,$Ir62_calend,true,"text",4,"","chave_r62_calend");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr62_data?>">
              <?=$Lr62_data?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r62_data",8,$Ir62_data,true,"text",4,"","chave_r62_data");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr62_data?>">
              <?=$Lr62_data?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r62_data",8,$Ir62_data,true,"text",4,"","chave_r62_data");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_calendf.hide();">
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
           if(file_exists("funcoes/db_func_calendf.php")==true){
             include("funcoes/db_func_calendf.php");
           }else{
           $campos = "calendf.*";
           }
        }
        if(isset($chave_r62_calend) && (trim($chave_r62_calend)!="") ){
	         $sql = $clcalendf->sql_query($chave_r62_calend,$chave_r62_data,$campos,"r62_calend");
        }else if(isset($chave_r62_data) && (trim($chave_r62_data)!="") ){
	         $sql = $clcalendf->sql_query("","",$campos,"r62_data"," r62_data like '$chave_r62_data%' ");
        }else{
           $sql = $clcalendf->sql_query("","",$campos,"r62_calend#r62_data","");
        }
        $repassa = array();
        if(isset($chave_r62_data)){
          $repassa = array("chave_r62_calend"=>$chave_r62_calend,"chave_r62_data"=>$chave_r62_data);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcalendf->sql_record($clcalendf->sql_query($pesquisa_chave));
          if($clcalendf->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r62_data',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_r62_data",true,1,"chave_r62_data",true);
</script>