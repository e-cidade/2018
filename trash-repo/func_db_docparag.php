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
include("classes/db_db_docparag_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_docparag = new cl_db_docparag;
$cldb_docparag->rotulo->label("db04_docum");
$cldb_docparag->rotulo->label("db04_idparag");
$cldb_docparag->rotulo->label("db04_ordem");
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
            <td width="4%" align="right" nowrap title="<?=$Tdb04_docum?>">
              <?=$Ldb04_docum?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db04_docum",8,$Idb04_docum,true,"text",4,"","chave_db04_docum");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdb04_idparag?>">
              <?=$Ldb04_idparag?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db04_idparag",8,$Idb04_idparag,true,"text",4,"","chave_db04_idparag");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdb04_ordem?>">
              <?=$Ldb04_ordem?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db04_ordem",4,$Idb04_ordem,true,"text",4,"","chave_db04_ordem");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(isset($db04_docum) && $db04_docum != ""){
        $where = " db04_docum =  $db04_docum";
      }else{
        $where = "";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_docparag.php")==true){
             include("funcoes/db_func_db_docparag.php");
           }else{
           $campos = "db04_docum,db04_idparag,db02_descr";
           }
        }
        if(isset($chave_db04_docum) && (trim($chave_db04_docum)!="") ){
	  $sql = $cldb_docparag->sql_query($chave_db04_docum,$chave_db04_idparag,$campos,"db04_docum","$where");
        }else if(isset($chave_db04_ordem) && (trim($chave_db04_ordem)!="") ){
	  $sql = $cldb_docparag->sql_query("","",$campos,"db04_ordem"," db04_ordem like '$chave_db04_ordem%' ".($where != ""?"and":"")." $where ");
        }else{
          $sql = $cldb_docparag->sql_query("","",$campos,"db04_docum#db04_idparag","$where");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_docparag->sql_record($cldb_docparag->sql_query("","","db04_docum,db04_idparag,db02_descr","db04_docum#db04_idparag"," db04_idparag = $pesquisa_chave ".($where != ""?"and":"")." $where "));
          if($cldb_docparag->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$db02_descr',false);</script>";
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
document.form2.chave_db04_docum.focus();
document.form2.chave_db04_docum.select();
  </script>
  <?
}
?>