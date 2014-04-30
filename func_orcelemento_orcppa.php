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
include("classes/db_orcelemento_classe.php");
include("classes/db_orcparametro_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcelemento = new cl_orcelemento;
$clorcparametro = new cl_orcparametro;
$clorcelemento->rotulo->label("o56_codele");
$clorcelemento->rotulo->label("o56_elemento");
$clorcelemento->rotulo->label("o56_descr");
$result=$clorcparametro->sql_record($clorcparametro->sql_query_file("","o50_subelem"));
if ($clorcparametro->numrows > 0 ){    
   db_fieldsmemory($result,0);
}
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
            <td width="4%" align="right" nowrap title="<?=$To56_codele?>">
              <?=$Lo56_codele?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
	      db_input("o56_codele",6,$Io56_codele,true,"text",4,"","chave_o56_codele");
	      ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To56_elemento?>">
              <?=$Lo56_elemento?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
              db_input("o56_elemento",15,$Io56_elemento,true,"text",4,"","chave_o56_elemento");
	      ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$To56_descr?>">
              <?=$Lo56_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
              db_input("o56_descr",15,$Io56_descr,true,"text",4,"","chave_o56_descr");
	      ?>
            </td>
          </tr>

          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_orcelemento.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
       $dbwhere="   substr(o56_elemento,8,6)='000000' ";
      if(!isset($pesquisa_chave)){
        $campos = "orcelemento.o56_codele, substr(o56_elemento,1,7) as  o56_elemento,orcelemento.o56_descr,orcelemento.o56_finali";
        if(isset($chave_o56_codele) && (trim($chave_o56_codele)!="") ){
	  $sql = $clorcelemento->sql_query(null,null,$campos,"o56_elemento"," o56_anousu = ".db_getsession("DB_anousu")." and o56_codele = $chave_o56_codele and  $dbwhere");
        }else if(isset($chave_o56_elemento) && (trim($chave_o56_elemento)!="") ){
	  $sql = $clorcelemento->sql_query("","",$campos,"o56_elemento"," o56_anousu = ".db_getsession("DB_anousu")." and  o56_elemento like '$chave_o56_elemento%' and $dbwhere ");
        }else if(isset($chave_o56_descr) && (trim($chave_o56_descr)!="") ){
	  $sql = $clorcelemento->sql_query("","",$campos,"o56_elemento","  o56_anousu = ".db_getsession("DB_anousu")." and o56_descr like '$chave_o56_descr%' and $dbwhere ");
 
        }else{
           $sql = $clorcelemento->sql_query("","",$campos,"o56_elemento"," o56_anousu = ".db_getsession("DB_anousu")." and  $dbwhere");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
	  if(isset($tipo_pesquisa)){
            $result = $clorcelemento->sql_record($clorcelemento->sql_query(null,null,"*",''," o56_anousu = ".db_getsession("DB_anousu")." and o56_codele=$pesquisa_chave and $dbwhere "));
	  }else{
            $result = $clorcelemento->sql_record($clorcelemento->sql_query(null,null,"*","o56_descr"," o56_anousu = ".db_getsession("DB_anousu")." and  substr(o56_elemento,1,7) =  '$pesquisa_chave' and $dbwhere"));
	  }  
          if($clorcelemento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o56_descr',false);</script>";
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