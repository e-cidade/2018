<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_slipprocesso_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clslipprocesso = new cl_slipprocesso;
$clslipprocesso->rotulo->label("k145_sequencial");
$clslipprocesso->rotulo->label("k145_sequencial");
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
            <td width="4%" align="right" nowrap title="<?=$Tk145_sequencial?>">
              <?=$Lk145_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k145_sequencial",10,$Ik145_sequencial,true,"text",4,"","chave_k145_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk145_sequencial?>">
              <?=$Lk145_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k145_sequencial",10,$Ik145_sequencial,true,"text",4,"","chave_k145_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_slipprocesso.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_slipprocesso.php")==true){
             include("funcoes/db_func_slipprocesso.php");
           }else{
           $campos = "slipprocesso.*";
           }
        }
        if(isset($chave_k145_sequencial) && (trim($chave_k145_sequencial)!="") ){
	         $sql = $clslipprocesso->sql_query($chave_k145_sequencial,$campos,"k145_sequencial");
        }else if(isset($chave_k145_sequencial) && (trim($chave_k145_sequencial)!="") ){
	         $sql = $clslipprocesso->sql_query("",$campos,"k145_sequencial"," k145_sequencial like '$chave_k145_sequencial%' ");
        }else{
           $sql = $clslipprocesso->sql_query("",$campos,"k145_sequencial","");
        }
        $repassa = array();
        if(isset($chave_k145_sequencial)){
          $repassa = array("chave_k145_sequencial"=>$chave_k145_sequencial,"chave_k145_sequencial"=>$chave_k145_sequencial);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clslipprocesso->sql_record($clslipprocesso->sql_query($pesquisa_chave));
          if($clslipprocesso->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k145_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_k145_sequencial",true,1,"chave_k145_sequencial",true);
</script>
