<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_parjuridico_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clparjuridico = new cl_parjuridico;
$clparjuridico->rotulo->label("v19_anousu");
$clparjuridico->rotulo->label("v19_instit");
$clparjuridico->rotulo->label("v19_instit");
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
            <td width="4%" align="right" nowrap title="<?=$Tv19_instit?>">
              <?=$Lv19_instit?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v19_instit",10,$Iv19_instit,true,"text",4,"","chave_v19_instit");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tv19_instit?>">
              <?=$Lv19_instit?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v19_instit",10,$Iv19_instit,true,"text",4,"","chave_v19_instit");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_parjuridico.hide();">
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
           if(file_exists("funcoes/db_func_parjuridico.php")==true){
             include("funcoes/db_func_parjuridico.php");
           }else{
           $campos = "parjuridico.*";
           }
        }
        if(isset($chave_v19_instit) && (trim($chave_v19_instit)!="") ){
	         $sql = $clparjuridico->sql_query(db_getsession('DB_anousu'),$chave_v19_instit,$campos,"v19_instit");
        }else if(isset($chave_v19_instit) && (trim($chave_v19_instit)!="") ){
	         $sql = $clparjuridico->sql_query(db_getsession('DB_anousu'),"",$campos,"v19_instit"," v19_instit like '$chave_v19_instit%' ");
        }else{
           $sql = $clparjuridico->sql_query(db_getsession('DB_anousu'),"",$campos,"v19_anousu#v19_instit","");
        }
        $repassa = array();
        if(isset($chave_v19_instit)){
          $repassa = array("chave_v19_anousu"=>$chave_v19_anousu,"chave_v19_instit"=>$chave_v19_instit);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clparjuridico->sql_record($clparjuridico->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clparjuridico->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v19_instit',false);</script>";
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
js_tabulacaoforms("form2","chave_v19_instit",true,1,"chave_v19_instit",true);
</script>