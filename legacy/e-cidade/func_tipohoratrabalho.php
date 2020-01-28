<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_tipohoratrabalho_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltipohoratrabalho = new cl_tipohoratrabalho;
$cltipohoratrabalho->rotulo->label("ed128_codigo");
$cltipohoratrabalho->rotulo->label("ed128_descricao");
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
          <td><label><?=$Led128_codigo?></label></td>
          <td><? db_input("ed128_codigo",10,$Ied128_codigo,true,"text",4,"","chave_ed128_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Led128_descricao?></label></td>
          <td><? db_input("ed128_descricao",10,$Ied128_descricao,true,"text",4,"","chave_ed128_descricao");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tipohoratrabalho.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_tipohoratrabalho.php")==true){
             include("funcoes/db_func_tipohoratrabalho.php");
           }else{
           $campos = "tipohoratrabalho.*";
           }
        }
        if(isset($chave_ed128_codigo) && (trim($chave_ed128_codigo)!="") ){
	         $sql = $cltipohoratrabalho->sql_query($chave_ed128_codigo,$campos,"ed128_codigo");
        }else if(isset($chave_ed128_descricao) && (trim($chave_ed128_descricao)!="") ){
	         $sql = $cltipohoratrabalho->sql_query("",$campos,"ed128_descricao"," ed128_descricao like '$chave_ed128_descricao%' ");
        }else{
           $sql = $cltipohoratrabalho->sql_query("",$campos,"ed128_codigo","");
        }
        $repassa = array();
        if(isset($chave_ed128_descricao)){
          $repassa = array("chave_ed128_codigo"=>$chave_ed128_codigo,"chave_ed128_descricao"=>$chave_ed128_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cltipohoratrabalho->sql_record($cltipohoratrabalho->sql_query($pesquisa_chave));
          if($cltipohoratrabalho->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed128_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_ed128_descricao",true,1,"chave_ed128_descricao",true);
</script>
