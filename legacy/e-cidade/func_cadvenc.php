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
include("classes/db_cadvenc_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clcadvenc = new cl_cadvenc;
$clcadvenc->rotulo->label("q82_codigo");
$clcadvenc->rotulo->label("q82_parc");
$clcadvenc->rotulo->label("q82_desc");
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
	            <td width="4%" align="right" nowrap title="<?=$Tq82_codigo?>">
	              <?=$Lq82_codigo?>
	            </td>
	            <td width="96%" align="left" nowrap> 
	              <?
			            db_input("q82_codigo",4,$Iq82_codigo,true,"text",4,"","chave_q82_codigo");
			          ?>
	            </td>
	          </tr>
	          <tr> 
	            <td width="4%" align="right" nowrap title="<?=$Tq82_parc?>">
	              <?=$Lq82_parc?>
	            </td>
	            <td width="96%" align="left" nowrap> 
	              <?
			            db_input("q82_parc",4,$Iq82_parc,true,"text",4,"","chave_q82_parc");
			          ?>
	            </td>
	          </tr>
	          <tr> 
	            <td width="4%" align="right" nowrap title="<?=$Tq82_desc?>">
	              <?=$Lq82_desc?>
	            </td>
	            <td width="96%" align="left" nowrap> 
	              <?
			           db_input("q82_desc",20,$Iq82_desc,true,"text",4,"","chave_q82_desc");
			          ?>
	            </td>
	          </tr>
	          <tr> 
	            <td colspan="2" align="center"> 
	              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
	              <input name="limpar" type="reset" id="limpar" value="Limpar" >
	              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cadvenc.hide();">
	             </td>
	          </tr>
	        </table>
        </form>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($pesquisa_chave)) {
      	
        if (isset($campos) == false) {
        	
          if (file_exists("funcoes/db_func_cadvenc.php") == true) {
            include("funcoes/db_func_cadvenc.php");
          } else {
            $campos = "cadvenc.*";
          }
        }
        
        if (isset($chave_q82_codigo) && (trim($chave_q82_codigo) != "")) {
	        $sql = $clcadvenc->sql_query($chave_q82_codigo,$chave_q82_parc,$campos,"q82_codigo");
        } else if (isset($chave_q82_desc) && (trim($chave_q82_desc) != "")) {
	        $sql = $clcadvenc->sql_query("","",$campos,"q82_desc"," q82_desc like '$chave_q82_desc%' ");
        } else {
          $sql = $clcadvenc->sql_query("","",$campos,"q82_codigo#q82_parc","");
        }
        
        $repassa = array();
        if (isset($chave_q82_desc)) {
          $repassa = array("chave_q82_codigo" => $chave_q82_codigo,
                           "chave_q82_desc"   => $chave_q82_desc);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
      	
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
        	
          $result = $clcadvenc->sql_record($clcadvenc->sql_query($pesquisa_chave));
          if ($clcadvenc->numrows != 0) {
          	
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$q82_desc',false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
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
if (!isset($pesquisa_chave)) {
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_q82_desc",true,1,"chave_q82_desc",true);
</script>