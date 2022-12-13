<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include("classes/db_clabens_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clclabens = new cl_clabens;
$clclabens->rotulo->label("t64_codcla");
$clclabens->rotulo->label("t64_descr");
$oRotulo = new rotulocampo();
$oRotulo->label("c60_estrut");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tt64_codcla?>">
              <?=$Lt64_codcla?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("t64_codcla",10,$It64_codcla,true,"text",4,"","chave_t64_codcla");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tt64_descr?>">
              <?=$Lt64_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		           db_input("t64_descr",50,$It64_descr,true,"text",4,"","chave_t64_descr");
		         ?>
		         </td>
		         </tr>
		         <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tc60_estrut?>">
              <?=$Lc60_estrut?>
            </td>
            <td>
		           <?
               db_input("c60_estrut",20 ,$Ic60_estrut,true,"text",4,"","chave_c60_estrut");
              ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_clabens.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(isset($campos)==false){
				 if(file_exists("funcoes/db_func_clabens.php")==true){
				   include("funcoes/db_func_clabens.php");
				 }else{
				    $campos = "clabens.*";
				 }
      }
      $param = "";
      $campos = "distinct c60_codcon, c60_estrut, c60_descr";
      if(isset($analitica)){
				if($analitica==true){
				  $param = " t64_analitica <> 'f' ";
				  if(isset($pesquisa_chave) && trim($pesquisa_chave)!="" || isset($chave_t64_codcla) && trim($chave_t64_codcla)!="" || isset($chave_t64_class) && trim($chave_t64_class)!="" || isset($chave_t64_descr) && trim($chave_t64_descr)!=""){
				    $param = " and ".$param;	  
				  }
				}
      }
      
      if (!isset($pesquisa_chave)) {
        
        if (isset($chave_t64_codcla) && (trim($chave_t64_codcla)!="") ) {
	         $sql = $clclabens->sql_query(null,$campos,"c60_estrut", " t64_codcla=$chave_t64_codcla $param");
        } else if(isset($chave_t64_class) && (trim($chave_t64_class)!="")) {
	         $sql = $clclabens->sql_query("",$campos,"c60_estrut","t64_class like '$chave_t64_class%' $param");
        } else if(isset($chave_t64_descr) && (trim($chave_t64_descr)!="")) {
	         $sql = $clclabens->sql_query("",$campos,"c60_estrut"," t64_descr like '$chave_t64_descr%' $param ");
	      } else if(isset($chave_c60_estrut) && (trim($chave_c60_estrut) != "")) {
           $sql = $clclabens->sql_query("",$campos,"c60_estrut"," c60_estrut like '$chave_c60_estrut%' $param ");
        } else {
		       if($param!=""){
	            $sql = $clclabens->sql_query("",$campos,"c60_estrut"," $param");
		  		 }else{
	            $sql = $clclabens->sql_query("",$campos,"c60_estrut");
		  		 }
        }
        db_lovrot($sql,15,"()","",$funcao_js);
        
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clclabens->sql_record($clclabens->sql_query(null,"*",null,"c60_codcon like '$pesquisa_chave%' $param"));
          if($clclabens->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c60_descr',false);</script>";
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