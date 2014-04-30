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
include("classes/db_matestoqueini_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatestoqueini = new cl_matestoqueini;
$clmatestoqueini->rotulo->label("m80_codigo");
$clmatestoqueini->rotulo->label("m80_matestoqueitem");
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
            <td width="4%" align="right" nowrap title="<?=$Tm80_codigo?>">
              <?=$Lm80_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("m80_codigo",10,$Im80_codigo,true,"text",4,"","chave_m80_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_matestoqueini.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where_parametro = "";
      if(isset($chave_m80_codtipo)){
	$where_parametro .= " and matestoqueini.m80_codtipo in ($chave_m80_codtipo) ";
      }
      if(isset($chave_m80_coddepto)){
	$where_parametro .= " and matestoqueini.m80_coddepto in ($chave_m80_coddepto) ";
      }
      if(isset($naoatendido)){
	$where_parametro .= " and m71_quant>m71_quantatend ";
      }
      if(isset($naoinill)){
	$where_parametro .= " and (b.m80_codtipo in ($naoinill) or b.m80_codigo is null) ";
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_matestoqueini.php")==true){
             include("funcoes/db_func_matestoqueini.php");
           }else{
           $campos = "matestoqueini.*";
           }
        }
        $campos = "matestoqueini.m80_codigo,nome,matestoqueini.m80_data,matestoqueini.m80_hora,matestoqueini.m80_obs";
	$campos = " distinct ".$campos;
        if(isset($chave_m80_codigo) && (trim($chave_m80_codigo)!="") ){
           $sql = $clmatestoqueini->sql_query_mater(null,$campos,"matestoqueini.m80_codigo desc"," matestoqueini.m80_codigo=$chave_m80_codigo $where_parametro");
        }else{
           $sql = $clmatestoqueini->sql_query_mater("",$campos,"matestoqueini.m80_codigo desc","1=1 $where_parametro");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmatestoqueini->sql_record($clmatestoqueini->sql_query_mater(null," distinct *","","matestoqueini.m80_codigo=$pesquisa_chave $where_parametro"));
          if($clmatestoqueini->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m80_matestoqueitem',false);</script>";
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