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
include("classes/db_db_depart_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_depart = new cl_db_depart;
$cldb_depart->rotulo->label("coddepto");
$cldb_depart->rotulo->label("descrdepto");
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
            <td width="4%" align="right" nowrap title="<?=$Tcoddepto?>">
              <?=$Lcoddepto?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("coddepto",5,$Icoddepto,true,"text",4,"","chave_coddepto");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdescrdepto?>">
              <?=$Ldescrdepto?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("descrdepto",40,$Idescrdepto,true,"text",4,"","chave_descrdepto");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
		<input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_depart.hide();">
             </td>
          </tr>
        </form>
        </table>

  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $param = "";
      $and = "";
     
      if(isset($chave_t93_depart) && trim($chave_t93_depart) != ""){
	$param = "and  coddepto<>$chave_t93_depart ";
      }      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "distinct db_depart.*, db_config.nomeinst";
        }
        if(isset($chave_coddepto) && trim($chave_coddepto)!=""){
	         $sql = $cldb_depart->sql_query_div(null,$campos,"coddepto"," coddepto = $chave_coddepto $param");
        }else if(isset($chave_descrdepto) && (trim($chave_descrdepto)!="") ){
	         $sql = $cldb_depart->sql_query_div("",$campos,"descrdepto"," descrdepto like '$chave_descrdepto%' $param");
        }else{	 
	  if(isset($param) && trim($param)!=""){
            $sql = $cldb_depart->sql_query_div("",$campos,"coddepto","$param");
	  }else{
            $sql = $cldb_depart->sql_query("",$campos,"coddepto");
	  }
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_depart->sql_record($cldb_depart->sql_query(null,"*",""," coddepto=$pesquisa_chave $param"));
          if($cldb_depart->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$descrdepto',false);</script>";
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
document.form2.chave_coddepto.focus();
document.form2.chave_coddepto.select();
  </script>
  <?
}
?>