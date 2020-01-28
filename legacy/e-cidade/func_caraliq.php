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
require_once("dbforms/db_funcoes.php");
require_once("classes/db_caraliq_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clcaraliq = new cl_caraliq;
$clcaraliq->rotulo->label("j73_anousu");
$clcaraliq->rotulo->label("j73_caract");
$clcaraliq->rotulo->label("j73_aliq");
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
            <td width="4%" align="right" nowrap title="<?=$Tj73_caract?>">
              <?=$Lj73_caract?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		            db_input("j73_caract",10,$Ij73_caract,true,"text",4,"","chave_j73_caract");
		          ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj73_aliq?>">
              <?=$Lj73_aliq?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		            db_input("j73_aliq",15,$Ij73_aliq,true,"text",4,"","chave_j73_aliq");
		          ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_caraliq.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($pesquisa_chave)) {
      	
        if (isset($campos) == false) {
        	
          if (file_exists("funcoes/db_func_caraliq.php") == true) {
            include("funcoes/db_func_caraliq.php");
          } else {
            $campos = "caraliq.*";
          }
        }
        
        if (isset($chave_j73_caract) && (trim($chave_j73_caract) != "")) {
	        $sql = $clcaraliq->sql_query(db_getsession('DB_anousu'),$chave_j73_caract,$campos,"j73_caract");
        } else if (isset($chave_j73_aliq) && (trim($chave_j73_aliq) != "")) {
	        $sql = $clcaraliq->sql_query(db_getsession('DB_anousu'),"",$campos,"j73_aliq"," j73_aliq like '{$chave_j73_aliq}%' ");
        } else {
          $sql = $clcaraliq->sql_query(db_getsession('DB_anousu'),"",$campos,"j73_anousu#j73_caract","");
        }
        
        $repassa = array();
        if(isset($chave_j73_aliq)){
          $repassa = array("chave_j73_anousu" => $chave_j73_anousu,
                           "chave_j73_aliq"   => $chave_j73_aliq);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {
      	
        if ($pesquisa_chave != null && $pesquisa_chave != "") {
        	
          $result = $clcaraliq->sql_record($clcaraliq->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if ($clcaraliq->numrows != 0) {
          	
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j73_aliq',false);</script>";
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
js_tabulacaoforms("form2","chave_j73_aliq",true,1,"chave_j73_aliq",true);
</script>