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
include("classes/db_bancoshistmov_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbancoshistmov = new cl_bancoshistmov;
$clbancoshistmov->rotulo->label("k66_sequencial");
$clbancoshistmov->rotulo->label("k66_descricao");
$oGet = db_utils::postMemory($_GET);
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
            <td width="4%" align="right" nowrap title="<?=$Tk66_sequencial?>">
              <?=$Lk66_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k66_sequencial",10,$Ik66_sequencial,true,"text",4,"","chave_k66_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk66_descricao?>">
              <?=$Lk66_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k66_descricao",50,$Ik66_descricao,true,"text",4,"","chave_k66_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_bancoshistmov.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhereBanco = "1=1";
      
      if (!empty($oGet->iCodigoBanco)) {
        $sWhereBanco = 'k66_codbco = ' . $oGet->iCodigoBanco;
      }
      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_bancoshistmov.php")==true){
             include("funcoes/db_func_bancoshistmov.php");
           }else{
           $campos = "bancoshistmov.*";
           }
        }
        if(isset($chave_k66_sequencial) && (trim($chave_k66_sequencial)!="") ){
	         $sql = $clbancoshistmov->sql_query($chave_k66_sequencial,$campos,"k66_sequencial", $sWhereBanco);
        }else if(isset($chave_k66_descricao) && (trim($chave_k66_descricao)!="") ){
	         $sql = $clbancoshistmov->sql_query("",$campos,"k66_descricao"," k66_descricao like '$chave_k66_descricao%' and {$sWhereBanco}");
        }else{
           $sql = $clbancoshistmov->sql_query("",$campos,"k66_sequencial", $sWhereBanco);
        }
        $repassa = array();
        if(isset($chave_k66_descricao)){
          $repassa = array("chave_k66_sequencial"=>$chave_k66_sequencial,"chave_k66_descricao"=>$chave_k66_descricao);
        }
        
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          
          $result = $clbancoshistmov->sql_record($clbancoshistmov->sql_query(null, "*", null, " k66_sequencial = {$pesquisa_chave} and " . $sWhereBanco));
         
          if($clbancoshistmov->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k66_descricao',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_k66_descricao",true,1,"chave_k66_descricao",true);
</script>