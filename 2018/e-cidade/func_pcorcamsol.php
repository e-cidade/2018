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
include("classes/db_pcorcam_classe.php");
include("classes/db_pcorcamitemsol_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clpcorcam = new cl_pcorcam;
$clpcorcamitemsol = new cl_pcorcamitemsol;
$clpcorcam->rotulo->label("pc20_codorc");
$clpcorcam->rotulo->label("pc20_dtate");
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
            <td width="4%" align="right" nowrap title="<?=$Tpc20_codorc?>">
              <?=$Lpc20_codorc?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("pc20_codorc",10,$Ipc20_codorc,true,"text",4,"","chave_pc20_codorc");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tpc20_dtate?>">
              <?=$Lpc20_dtate?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("pc20_dtate",10,$Ipc20_dtate,true,"text",4,"","chave_pc20_dtate");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_pcorcam.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(isset($sol)){
	$result_itemorc = $clpcorcamitemsol->sql_record($clpcorcamitemsol->sql_query_solicitem(null,null,"pc22_codorc as chave_pc20_codorc",""," pc11_numero=$sol"));
	if($clpcorcamitemsol->numrows>0){
	  db_fieldsmemory($result_itemorc,0);
	}
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_pcorcam.php")==true){
             include("funcoes/db_func_pcorcam.php");
           }else{
           $campos = "pcorcam.*";
           }
        }
        if(isset($chave_pc20_codorc) && (trim($chave_pc20_codorc)!="") ){
	         $sql = $clpcorcam->sql_query($chave_pc20_codorc,$campos,"pc20_codorc");
        }else if(isset($chave_pc20_dtate) && (trim($chave_pc20_dtate)!="") ){
	         $sql = $clpcorcam->sql_query("",$campos,"pc20_dtate"," pc20_dtate like '$chave_pc20_dtate%' ");
        }else{
           $sql = $clpcorcam->sql_query("",$campos,"pc20_codorc","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clpcorcam->sql_record($clpcorcam->sql_query($pesquisa_chave));
          if($clpcorcam->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$pc20_dtate',false);</script>";
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