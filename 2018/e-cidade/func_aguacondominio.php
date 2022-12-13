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
include("classes/db_aguacondominio_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$claguacondominio = new cl_aguacondominio;
$claguacondominio->rotulo->label("x31_codcondominio");
$claguacondominio->rotulo->label("x31_matric");

$clrotulo = new rotulocampo;
$clrotulo->label("x01_numcgm");
$clrotulo->label("z01_nome");

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
            <td width="4%" align="right" nowrap title="<?=$Tx31_codcondominio?>">
              <?=$Lx31_codcondominio?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("x31_codcondominio",8,$Ix31_codcondominio,true,"text",4,"","chave_x31_codcondominio");
		       ?>
            </td>
          </tr>
	  
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tx31_matric?>">
              <?=$Lx31_matric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("x31_matric",10,$Ix31_matric,true,"text",4,"","chave_x31_matric");
		       ?>
            </td>
          </tr>
	  
	  <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tx31_matric?>">
              <?=$Lx01_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_x01_numcgm");
		       ?>
            </td>
          </tr>
  
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_aguacondominio.hide();">
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
           if(file_exists("funcoes/db_func_aguacondominio.php")==true){
             include("funcoes/db_func_aguacondominio.php");
           }else{
           $campos = "aguacondominio.*";
           }
        }
        if(isset($chave_x31_codcondominio) && (trim($chave_x31_codcondominio)!="") ){
	         $sql = $claguacondominio->sql_query($chave_x31_codcondominio,$campos,"x31_codcondominio");
        }else if(isset($chave_x31_matric) && (trim($chave_x31_matric)!="") ){
	         $sql = $claguacondominio->sql_query("",$campos,"x31_matric"," x31_matric like '$chave_x31_matric%' ");
        }else if(isset($chave_x01_numcgm) && (trim($chave_x01_numcgm)!="") ){
	         $sql = $claguacondominio->sql_query("",$campos,"z01_nome"," z01_nome like '$chave_x01_numcgm%' ");

        }else{
           $sql = $claguacondominio->sql_query("",$campos,"x31_codcondominio","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $claguacondominio->sql_record($claguacondominio->sql_query($pesquisa_chave));
          if($claguacondominio->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$x31_matric',false);</script>";
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