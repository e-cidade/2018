<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_lab_entrega_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllab_entrega = new cl_lab_entrega;
$cllab_entrega->rotulo->label("la31_i_codigo");
$cllab_entrega->rotulo->label("la31_i_codigo");
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
            <td width="4%" align="right" nowrap title="<?=$Tla31_i_codigo?>">
              <?=$Lla31_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("la31_i_codigo",10,$Ila31_i_codigo,true,"text",4,"","chave_la31_i_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tla31_i_codigo?>">
              <?=$Lla31_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("la31_i_codigo",10,$Ila31_i_codigo,true,"text",4,"","chave_la31_i_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_lab_entrega.hide();">
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
           if(file_exists("funcoes/db_func_lab_entrega.php")==true){
             include("funcoes/db_func_lab_entrega.php");
           }else{
           $campos = "lab_entrega.*";
           }
        }
        if(isset($chave_la31_i_codigo) && (trim($chave_la31_i_codigo)!="") ){
	         $sql = $cllab_entrega->sql_query($chave_la31_i_codigo,$campos,"la31_i_codigo");
        }else if(isset($chave_la31_i_codigo) && (trim($chave_la31_i_codigo)!="") ){
	         $sql = $cllab_entrega->sql_query("",$campos,"la31_i_codigo"," la31_i_codigo like '$chave_la31_i_codigo%' ");
        }else{
           $sql = $cllab_entrega->sql_query("",$campos,"la31_i_codigo","");
        }
        $repassa = array();
        if(isset($chave_la31_i_codigo)){
          $repassa = array("chave_la31_i_codigo"=>$chave_la31_i_codigo,"chave_la31_i_codigo"=>$chave_la31_i_codigo);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cllab_entrega->sql_record($cllab_entrega->sql_query($pesquisa_chave));
          if($cllab_entrega->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$la31_i_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_la31_i_codigo",true,1,"chave_la31_i_codigo",true);
</script>
