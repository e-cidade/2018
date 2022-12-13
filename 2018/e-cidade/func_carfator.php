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
include("classes/db_carfator_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcarfator = new cl_carfator;
$clcarfator->rotulo->label("j74_anousu");
$clcarfator->rotulo->label("j74_caract");
$clcarfator->rotulo->label("j74_fator");
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
            <td width="4%" align="right" nowrap title="<?=$Tj74_caract?>">
              <?=$Lj74_caract?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j74_caract",10,$Ij74_caract,true,"text",4,"","chave_j74_caract");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj74_fator?>">
              <?=$Lj74_fator?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j74_fator",15,$Ij74_fator,true,"text",4,"","chave_j74_fator");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_carfator.hide();">
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
           if(file_exists("funcoes/db_func_carfator.php")==true){
             include("funcoes/db_func_carfator.php");
           }else{
           $campos = "carfator.*";
           }
        }
        if(isset($chave_j74_caract) && (trim($chave_j74_caract)!="") ){
	         $sql = $clcarfator->sql_query(db_getsession('DB_anousu'),$chave_j74_caract,$campos,"j74_caract");
        }else if(isset($chave_j74_fator) && (trim($chave_j74_fator)!="") ){
	         $sql = $clcarfator->sql_query(db_getsession('DB_anousu'),"",$campos,"j74_fator"," j74_fator like '$chave_j74_fator%' ");
        }else{
           $sql = $clcarfator->sql_query(db_getsession('DB_anousu'),"",$campos,"j74_anousu#j74_caract","");
        }
        $repassa = array();
        if(isset($chave_j74_fator)){
          $repassa = array("chave_j74_anousu"=>$chave_j74_anousu,"chave_j74_fator"=>$chave_j74_fator);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcarfator->sql_record($clcarfator->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clcarfator->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j74_fator',false);</script>";
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
js_tabulacaoforms("form2","chave_j74_fator",true,1,"chave_j74_fator",true);
</script>