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
include("classes/db_rhpespadrao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhpespadrao = new cl_rhpespadrao;
$clrotulo = new rotulocampo;
$clrhpespadrao->rotulo->label("rh03_seqpes");
$clrhpespadrao->rotulo->label("rh03_padrao");
$clrotulo->label("r02_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Trh03_seqpes?>">
              <?=$Lrh03_seqpes?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh03_seqpes",6,$Irh03_seqpes,true,"text",4,"","chave_rh03_seqpes");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh03_padrao?>">
              <?=$Lrh03_padrao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh03_padrao",10,$Irh03_padrao,true,"text",4,"","chave_rh03_padrao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr02_descr?>">
              <?=$Lr02_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r02_descr",10,$Ir02_descr,true,"text",4,"","chave_r02_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhpespadrao.hide();">
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
           if(file_exists("funcoes/db_func_rhpespadrao.php")==true){
             include("funcoes/db_func_rhpespadrao.php");
           }else{
           $campos = "rhpespadrao.*";
           }
        }
        if(isset($chave_rh03_seqpes) && (trim($chave_rh03_seqpes)!="") ){
	         $sql = $clrhpespadrao->sql_query($chave_rh03_seqpes,$campos,"rh03_seqpes");
        }else if(isset($chave_rh03_padrao) && (trim($chave_rh03_padrao)!="") ){
	         $sql = $clrhpespadrao->sql_query("",$campos,"rh03_padrao"," r03_padrao = '$chave_rh03_padrao' ");
        }else if(isset($chave_r02_descr) && (trim($chave_r02_descr)!="") ){
	         $sql = $clrhpespadrao->sql_query("",$campos,"r02_descr"," r02_descr like '$chave_r02_descr%' ");
        }else{
           $sql = $clrhpespadrao->sql_query("",$campos,"rh03_seqpes","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhpespadrao->sql_record($clrhpespadrao->sql_query($pesquisa_chave));
          if($clrhpespadrao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh03_padrao',false);</script>";
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