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
include("classes/db_rhlota_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhlota = new cl_rhlota;
$clrhlota->rotulo->label("r70_codigo");
$clrhlota->rotulo->label("r70_estrut");
$clrhlota->rotulo->label("r70_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tr70_codigo?>">
              <?=$Lr70_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r70_codigo",4,$Ir70_codigo,true,"text",4,"","chave_r70_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr70_estrut?>">
              <?=$Lr70_estrut?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r70_estrut",20,$Ir70_estrut,true,"text",4,"","chave_r70_estrut");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tr70_descr?>">
              <?=$Lr70_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("r70_descr",40,$Ir70_descr,true,"text",4,"","chave_r70_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhlota.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = "and r70_instit = ".db_getsession("DB_instit");
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhlota.php")==true){
             include("funcoes/db_func_rhlota.php");
           }else{
           $campos = "rhlota.*";
           }
        }
        if(isset($chave_r70_codigo) && (trim($chave_r70_codigo)!="") ){
	         $sql = $clrhlota->sql_query(null,$campos,"r70_codigo"," r70_codigo = $chave_r70_codigo $dbwhere ");
        }else if(isset($chave_r70_descr) && (trim($chave_r70_descr)!="") ){
	         $sql = $clrhlota->sql_query(null,$campos,"r70_descr"," r70_descr like '$chave_r70_descr%' $dbwhere ");
        }else if(isset($chave_r70_estrut) && (trim($chave_r70_estrut)!="") ){
	         $sql = $clrhlota->sql_query(null,$campos,"r70_estrut"," r70_estrut like '$chave_r70_estrut%' $dbwhere ");
        }else{
           $sql = $clrhlota->sql_query(null,$campos,"r70_codigo"," 1=1 $dbwhere ");
        }
        // echo $sql;
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
//        	die($clrhlota->sql_query(null,"*","r70_codigo"," r70_codigo = $pesquisa_chave $dbwhere "));
          $result = $clrhlota->sql_record($clrhlota->sql_query(null,"*","r70_codigo"," r70_estrut = '$pesquisa_chave' $dbwhere "));
          if($clrhlota->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$r70_descr',false);</script>";
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