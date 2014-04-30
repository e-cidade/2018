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
include("classes/db_editalserv_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cleditalserv = new cl_editalserv;
$cleditalserv->rotulo->label("d04_contri");
$cleditalserv->rotulo->label("d04_tipos");
$cleditalserv->rotulo->label("d04_quant");
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
            <td width="4%" align="right" nowrap title="<?=$Td04_contri?>">
              <?=$Ld04_contri?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("d04_contri",4,$Id04_contri,true,"text",4,"","chave_d04_contri");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Td04_tipos?>">
              <?=$Ld04_tipos?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("d04_tipos",4,$Id04_tipos,true,"text",4,"","chave_d04_tipos");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Td04_quant?>">
              <?=$Ld04_quant?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("d04_quant",15,$Id04_quant,true,"text",4,"","chave_d04_quant");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_editalserv.hide();">
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
           if(file_exists("funcoes/db_func_editalserv.php")==true){
             include("funcoes/db_func_editalserv.php");
           }else{
           $campos = "editalserv.*";
           }
        }
        if(isset($chave_d04_contri) && (trim($chave_d04_contri)!="") ){
	         $sql = $cleditalserv->sql_query($chave_d04_contri,$chave_d04_tipos,$campos,"d04_contri");
        }else if(isset($chave_d04_quant) && (trim($chave_d04_quant)!="") ){
	         $sql = $cleditalserv->sql_query("","",$campos,"d04_quant"," d04_quant like '$chave_d04_quant%' ");
        }else{
           $sql = $cleditalserv->sql_query("","",$campos,"d04_contri#d04_tipos","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cleditalserv->sql_record($cleditalserv->sql_query($pesquisa_chave));
          if($cleditalserv->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$d04_quant',false);</script>";
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