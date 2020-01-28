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
include("classes/db_orcimpactomovpai_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcimpactomovpai = new cl_orcimpactomovpai;
$clorcimpactomovpai->rotulo->label("o86_codimpmovpai");
$clorcimpactomovpai->rotulo->label("o86_codimpmovfilho");
$clorcimpactomovpai->rotulo->label("o86_codimpmovpai");
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
            <td width="4%" align="right" nowrap title="<?=$To86_codimpmovpai?>">
              <?=$Lo86_codimpmovpai?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o86_codimpmovpai",8,$Io86_codimpmovpai,true,"text",4,"","chave_o86_codimpmovpai");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To86_codimpmovfilho?>">
              <?=$Lo86_codimpmovfilho?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o86_codimpmovfilho",8,$Io86_codimpmovfilho,true,"text",4,"","chave_o86_codimpmovfilho");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To86_codimpmovpai?>">
              <?=$Lo86_codimpmovpai?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("o86_codimpmovpai",8,$Io86_codimpmovpai,true,"text",4,"","chave_o86_codimpmovpai");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcimpactomovpai.hide();">
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
           if(file_exists("funcoes/db_func_orcimpactomovpai.php")==true){
             include("funcoes/db_func_orcimpactomovpai.php");
           }else{
           $campos = "orcimpactomovpai.*";
           }
        }
        if(isset($chave_o86_codimpmovpai) && (trim($chave_o86_codimpmovpai)!="") ){
	         $sql = $clorcimpactomovpai->sql_query($chave_o86_codimpmovpai,$chave_o86_codimpmovfilho,$campos,"o86_codimpmovpai");
        }else if(isset($chave_o86_codimpmovpai) && (trim($chave_o86_codimpmovpai)!="") ){
	         $sql = $clorcimpactomovpai->sql_query("","",$campos,"o86_codimpmovpai"," o86_codimpmovpai like '$chave_o86_codimpmovpai%' ");
        }else{
           $sql = $clorcimpactomovpai->sql_query("","",$campos,"o86_codimpmovpai#o86_codimpmovfilho","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clorcimpactomovpai->sql_record($clorcimpactomovpai->sql_query($pesquisa_chave));
          if($clorcimpactomovpai->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o86_codimpmovpai',false);</script>";
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