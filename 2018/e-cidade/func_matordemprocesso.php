<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_matordemprocesso_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmatordemprocesso = new cl_matordemprocesso;
$clmatordemprocesso->rotulo->label("m08_sequencial");
$clmatordemprocesso->rotulo->label("m08_sequencial");
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
            <td width="4%" align="right" nowrap title="<?=$Tm08_sequencial?>">
              <?=$Lm08_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("m08_sequencial",10,$Im08_sequencial,true,"text",4,"","chave_m08_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tm08_sequencial?>">
              <?=$Lm08_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("m08_sequencial",10,$Im08_sequencial,true,"text",4,"","chave_m08_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_matordemprocesso.hide();">
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
           if(file_exists("funcoes/db_func_matordemprocesso.php")==true){
             include("funcoes/db_func_matordemprocesso.php");
           }else{
           $campos = "matordemprocesso.*";
           }
        }
        if(isset($chave_m08_sequencial) && (trim($chave_m08_sequencial)!="") ){
	         $sql = $clmatordemprocesso->sql_query($chave_m08_sequencial,$campos,"m08_sequencial");
        }else if(isset($chave_m08_sequencial) && (trim($chave_m08_sequencial)!="") ){
	         $sql = $clmatordemprocesso->sql_query("",$campos,"m08_sequencial"," m08_sequencial like '$chave_m08_sequencial%' ");
        }else{
           $sql = $clmatordemprocesso->sql_query("",$campos,"m08_sequencial","");
        }
        $repassa = array();
        if(isset($chave_m08_sequencial)){
          $repassa = array("chave_m08_sequencial"=>$chave_m08_sequencial,"chave_m08_sequencial"=>$chave_m08_sequencial);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmatordemprocesso->sql_record($clmatordemprocesso->sql_query($pesquisa_chave));
          if($clmatordemprocesso->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m08_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_m08_sequencial",true,1,"chave_m08_sequencial",true);
</script>
