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
include("classes/db_conplanosis_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconplanosis = new cl_conplanosis;
$clconplanosis->rotulo->label("c64_codpla");
$clconplanosis->rotulo->label("c64_estrut");
$clconplanosis->rotulo->label("c64_descr");

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
            <td width="4%" align="right" nowrap title="<?=$Tc64_codpla?>">
              <?=$Lc64_codpla?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	       db_input("c64_codpla",6,$Ic64_codpla,true,"text",4,"","chave_c64_codpla");
              ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc64_estrut ?>">
              <?=$Lc64_estrut ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	       db_input("c64_estrut",20,$Ic64_descr,true,"text",4,"","chave_c64_estrut");
	      ?>
            </td>
          </tr>
	  
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc64_descr?>">
              <?=$Lc64_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	       db_input("c64_descr",60,$Ic64_descr,true,"text",4,"","chave_c64_descr");
	      ?>
            </td>
          </tr>
	  
	  <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conplanosis.hide();">
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
           if(file_exists("funcoes/db_func_conplanosis.php")==true){
             include("funcoes/db_func_conplanosis.php");
           }else{
           $campos = "conplanosis.*";
           }
        }
        if(isset($chave_c64_codpla) && (trim($chave_c64_codpla)!="") ){
	         $sql = $clconplanosis->sql_query($chave_c64_codpla,$campos,"c64_estrut");
        }else if(isset($chave_c64_estrut) && (trim($chave_c64_estrut)!="") ){
	         $sql = $clconplanosis->sql_query("",$campos,"c64_estrut"," c64_estrut like '$chave_c64_estrut%' ");
        }else if(isset($chave_c64_descr) && (trim($chave_c64_descr)!="") ){
	         $sql = $clconplanosis->sql_query("",$campos,"c64_descr"," c64_descr like '$chave_c64_descr%' ");
        }else{
           $sql = $clconplanosis->sql_query("",$campos,"c64_estrut","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{

        if( $pesquisa_chave!=null && $pesquisa_chave!=""){
             $result = $clconplanosis->sql_record($clconplanosis->sql_query(null,"*",''," c64_estrut like '$pesquisa_chave%' " ));
          if($clconplanosis->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c64_descr',false);</script>";
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