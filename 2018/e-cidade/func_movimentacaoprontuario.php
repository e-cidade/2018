<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_movimentacaoprontuario_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmovimentacaoprontuario = new cl_movimentacaoprontuario;
$clmovimentacaoprontuario->rotulo->label("sd102_codigo");
$clmovimentacaoprontuario->rotulo->label("sd102_codigo");
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
          <td><label><?=$Lsd102_codigo?></label></td>
          <td><? db_input("sd102_codigo",10,$Isd102_codigo,true,"text",4,"","chave_sd102_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lsd102_codigo?></label></td>
          <td><? db_input("sd102_codigo",10,$Isd102_codigo,true,"text",4,"","chave_sd102_codigo");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_movimentacaoprontuario.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_movimentacaoprontuario.php")==true){
             include("funcoes/db_func_movimentacaoprontuario.php");
           }else{
           $campos = "movimentacaoprontuario.*";
           }
        }
        if(isset($chave_sd102_codigo) && (trim($chave_sd102_codigo)!="") ){
	         $sql = $clmovimentacaoprontuario->sql_query($chave_sd102_codigo,$campos,"sd102_codigo");
        }else if(isset($chave_sd102_codigo) && (trim($chave_sd102_codigo)!="") ){
	         $sql = $clmovimentacaoprontuario->sql_query("",$campos,"sd102_codigo"," sd102_codigo like '$chave_sd102_codigo%' ");
        }else{
           $sql = $clmovimentacaoprontuario->sql_query("",$campos,"sd102_codigo","");
        }
        $repassa = array();
        if(isset($chave_sd102_codigo)){
          $repassa = array("chave_sd102_codigo"=>$chave_sd102_codigo,"chave_sd102_codigo"=>$chave_sd102_codigo);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmovimentacaoprontuario->sql_record($clmovimentacaoprontuario->sql_query($pesquisa_chave));
          if($clmovimentacaoprontuario->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd102_codigo',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
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
js_tabulacaoforms("form2","chave_sd102_codigo",true,1,"chave_sd102_codigo",true);
</script>
