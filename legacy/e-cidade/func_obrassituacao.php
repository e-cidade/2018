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
require_once("libs/db_utils.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_obrassituacao_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$oGet = db_utils::postMemory($HTTP_GET_VARS);

$clobrassituacao = new cl_obrassituacao;
$clobrassituacao->rotulo->label("ob28_sequencial");
$clobrassituacao->rotulo->label("ob28_descricao");
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
            <td nowrap title="<?=$Tob28_sequencial?>">
              <?=$Lob28_sequencial?>
            </td>
            <td nowrap> 
              <?
		       db_input("ob28_sequencial",10,$Iob28_sequencial,true,"text",4,"","chave_ob28_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td nowrap title="<?=$Tob28_descricao?>">
              <?=$Lob28_descricao?>
            </td>
            <td nowrap> 
              <?
		       db_input("ob28_descricao",55,$Iob28_descricao,true,"text",4,"","chave_ob28_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_obrassituacao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?      
      if ( isset($oGet->exclusaoSituacao) ) {
        $sWhere = " not exists(select 1 from obrassituacaolog where ob29_obrassituacao = obrassituacao.ob28_sequencial )";
      }else {
        $sWhere = null;
      }
   
      if (!isset($pesquisa_chave)) {
        
        if (isset($campos)==false) {
          
          if ( file_exists("funcoes/db_func_obrassituacao.php") ) {
            include("funcoes/db_func_obrassituacao.php");
          }else{
            $campos = "obrassituacao.*";
          }
        }
        
        if ( isset($chave_ob28_sequencial) && (trim($chave_ob28_sequencial) != "" ) ) {
          
          $sql = $clobrassituacao->sql_query($chave_ob28_sequencial,$campos,null,$sWhere);
          
        } elseif (isset($chave_ob28_descricao) && (trim($chave_ob28_descricao) != "") ){
          
          $sql = $clobrassituacao->sql_query("",$campos,"ob28_descricao"," ob28_descricao like '$chave_ob28_descricao%' and ".$sWhere);
          
        } else {
          
          $sql = $clobrassituacao->sql_query("", $campos, null, $sWhere);
          
        }
        
        $repassa = array();
        
        if(isset($chave_ob28_descricao)){
          $repassa = array("chave_ob28_sequencial"=>$chave_ob28_sequencial,"chave_ob28_descricao"=>$chave_ob28_descricao);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        
      }else{
        
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {
          
          $result = $clobrassituacao->sql_record($clobrassituacao->sql_query($pesquisa_chave));
          
          if($clobrassituacao->numrows!=0){
            
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ob28_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_ob28_descricao",true,1,"chave_ob28_descricao",true);
</script>