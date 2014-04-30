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
include("classes/db_cargrup_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcargrup = new cl_cargrup;
$clcargrup->rotulo->label("j32_grupo");
$clcargrup->rotulo->label("j32_descr");
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
	<form name="form2" method="post" action="" >
        <table width="35%" border="0" align="center" cellspacing="0">
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj32_grupo?>">
              <?=$Lj32_grupo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j32_grupo",4,$Ij32_grupo,true,"text",4,"","chave_j32_grupo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj32_descr?>">
              <?=$Lj32_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j32_descr",40,$Ij32_descr,true,"text",4,"","chave_j32_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cargrup.hide();">
             </td>
          </tr>
        </table>
        </form>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           $campos = "cargrup.*";
        }
        if(isset($chave_j32_grupo) && (trim($chave_j32_grupo)!="") ){
	         $sql = $clcargrup->sql_query($chave_j32_grupo,$campos,"j32_grupo");
        }else if(isset($chave_j32_descr) && (trim($chave_j32_descr)!="") ){
	         $sql = $clcargrup->sql_query("",$campos,"j32_descr"," j32_descr like '$chave_j32_descr%' ");
        }else{
           $sql = $clcargrup->sql_query("",$campos,"j32_grupo","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        $result = $clcargrup->sql_record($clcargrup->sql_query($pesquisa_chave));
        if($clcargrup->numrows!=0){
          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$j32_descr',false);</script>";
        }else{
	       echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
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
document.form2.chave_j32_grupo.focus();
document.form2.chave_j32_grupo.select();
  </script>
  <?
}
?>