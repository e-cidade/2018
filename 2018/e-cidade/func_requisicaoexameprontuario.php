<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_requisicaoexameprontuario_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrequisicaoexameprontuario = new cl_requisicaoexameprontuario;
$clrequisicaoexameprontuario->rotulo->label("sd103_codigo");
$clrequisicaoexameprontuario->rotulo->label("sd103_codigo");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label><?=$Lsd103_codigo?></label></td>
          <td><? db_input("sd103_codigo",10,$Isd103_codigo,true,"text",4,"","chave_sd103_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lsd103_codigo?></label></td>
          <td><? db_input("sd103_codigo",10,$Isd103_codigo,true,"text",4,"","chave_sd103_codigo");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_requisicaoexameprontuario.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_requisicaoexameprontuario.php")==true){
             include("funcoes/db_func_requisicaoexameprontuario.php");
           }else{
           $campos = "requisicaoexameprontuario.*";
           }
        }
        if(isset($chave_sd103_codigo) && (trim($chave_sd103_codigo)!="") ){
	         $sql = $clrequisicaoexameprontuario->sql_query($chave_sd103_codigo,$campos,"sd103_codigo");
        }else if(isset($chave_sd103_codigo) && (trim($chave_sd103_codigo)!="") ){
	         $sql = $clrequisicaoexameprontuario->sql_query("",$campos,"sd103_codigo"," sd103_codigo like '$chave_sd103_codigo%' ");
        }else{
           $sql = $clrequisicaoexameprontuario->sql_query("",$campos,"sd103_codigo","");
        }
        $repassa = array();
        if(isset($chave_sd103_codigo)){
          $repassa = array("chave_sd103_codigo"=>$chave_sd103_codigo,"chave_sd103_codigo"=>$chave_sd103_codigo);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrequisicaoexameprontuario->sql_record($clrequisicaoexameprontuario->sql_query($pesquisa_chave));
          if($clrequisicaoexameprontuario->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd103_codigo',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
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
js_tabulacaoforms("form2","chave_sd103_codigo",true,1,"chave_sd103_codigo",true);
</script>
