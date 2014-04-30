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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_processoforoinicial_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);

$clprocessoforoinicial = new cl_processoforoinicial;
$clprocessoforoinicial->rotulo->label("v71_sequencial");

if (!isset($funcao_js)) {
  $funcao_js = '';
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
       <?
         if (!isset($oGet->detalhamento)) {
       ?>
  <tr> 
    <td height="63" align="center" valign="top">
       <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tv71_sequencial?>">
              <?=$Lv71_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		            db_input("v71_sequencial",10,$Iv71_sequencial,true,"text",4,"","chave_v71_sequencial");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_processoforoinicial.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
        <?
         }
        ?>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($pesquisa_chave)) {
      	
        if (isset($campos) == false) {
        	
          if(file_exists("funcoes/db_func_processoforoinicial.php")==true){
            include("funcoes/db_func_processoforoinicial.php");
          } else {
            $campos = "processoforoinicial.*";
          }
        }
        
        if (isset($v71_processoforo) && (trim($v71_processoforo) != "") ) {
        	
        	$sWhere = "v71_processoforo = {$v71_processoforo}";
          $sql    = $clprocessoforoinicial->sql_query(null, $campos, "v71_sequencial", $sWhere);
        } else if (isset($chave_v71_sequencial) && (trim($chave_v71_sequencial) != "")) {
	        $sql = $clprocessoforoinicial->sql_query($chave_v71_sequencial,$campos,"v71_sequencial");
        } else if(isset($chave_v71_sequencial) && (trim($chave_v71_sequencial)!="")) {
        	
        	$sWhere = "v71_sequencial like '$chave_v71_sequencial%'";
	        $sql    = $clprocessoforoinicial->sql_query(null,$campos,"v71_sequencial",$sWhere);
        } else {
          $sql = $clprocessoforoinicial->sql_query(null,$campos,"v71_sequencial","");
        }
        
        $repassa = array();
        if (isset($chave_v71_sequencial)) {
          $repassa = array("chave_v71_sequencial" => $chave_v71_sequencial);
        }
        
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa, false);
      } else {
      	
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
        	
          $result = $clprocessoforoinicial->sql_record($clprocessoforoinicial->sql_query($pesquisa_chave));
          if ($clprocessoforoinicial->numrows != 0) {
          	
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v71_sequencial',false);</script>";
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
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_v71_sequencial",true,1,"chave_v71_sequencial",true);
</script>