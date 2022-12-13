<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_classificacaorisco_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clclassificacaorisco = new cl_classificacaorisco;
$clclassificacaorisco->rotulo->label("sd78_codigo");
$clclassificacaorisco->rotulo->label("sd78_descricao");
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
          <td><label><?=$Lsd78_codigo?></label></td>
          <td><? db_input("sd78_codigo",10,$Isd78_codigo,true,"text",4,"","chave_sd78_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lsd78_descricao?></label></td>
          <td><? db_input("sd78_descricao",10,$Isd78_descricao,true,"text",4,"","chave_sd78_descricao");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_classificacaorisco.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_classificacaorisco.php")==true){
             include("funcoes/db_func_classificacaorisco.php");
           }else{
           $campos = "classificacaorisco.*";
           }
        }
        if(isset($chave_sd78_codigo) && (trim($chave_sd78_codigo)!="") ){
	         $sql = $clclassificacaorisco->sql_query($chave_sd78_codigo,$campos,"sd78_codigo");
        }else if(isset($chave_sd78_descricao) && (trim($chave_sd78_descricao)!="") ){
	         $sql = $clclassificacaorisco->sql_query("",$campos,"sd78_descricao"," sd78_descricao like '$chave_sd78_descricao%' ");
        }else{
           $sql = $clclassificacaorisco->sql_query("",$campos,"sd78_codigo","");
        }
        $repassa = array();
        if(isset($chave_sd78_descricao)){
          $repassa = array("chave_sd78_codigo"=>$chave_sd78_codigo,"chave_sd78_descricao"=>$chave_sd78_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clclassificacaorisco->sql_record($clclassificacaorisco->sql_query($pesquisa_chave));
          if($clclassificacaorisco->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd78_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_sd78_descricao",true,1,"chave_sd78_descricao",true);
</script>
