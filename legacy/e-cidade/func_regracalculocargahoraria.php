<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_regracalculocargahoraria_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clregracalculocargahoraria = new cl_regracalculocargahoraria;
$clregracalculocargahoraria->rotulo->label("ed127_codigo");
$clregracalculocargahoraria->rotulo->label("ed127_codigo");
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
          <td><label><?=$Led127_codigo?></label></td>
          <td><? db_input("ed127_codigo",10,$Ied127_codigo,true,"text",4,"","chave_ed127_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Led127_codigo?></label></td>
          <td><? db_input("ed127_codigo",10,$Ied127_codigo,true,"text",4,"","chave_ed127_codigo");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_regracalculocargahoraria.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_regracalculocargahoraria.php")==true){
             include("funcoes/db_func_regracalculocargahoraria.php");
           }else{
           $campos = "regracalculocargahoraria.*";
           }
        }
        if(isset($chave_ed127_codigo) && (trim($chave_ed127_codigo)!="") ){
	         $sql = $clregracalculocargahoraria->sql_query($chave_ed127_codigo,$campos,"ed127_codigo");
        }else if(isset($chave_ed127_codigo) && (trim($chave_ed127_codigo)!="") ){
	         $sql = $clregracalculocargahoraria->sql_query("",$campos,"ed127_codigo"," ed127_codigo like '$chave_ed127_codigo%' ");
        }else{
           $sql = $clregracalculocargahoraria->sql_query("",$campos,"ed127_codigo","");
        }
        $repassa = array();
        if(isset($chave_ed127_codigo)){
          $repassa = array("chave_ed127_codigo"=>$chave_ed127_codigo,"chave_ed127_codigo"=>$chave_ed127_codigo);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clregracalculocargahoraria->sql_record($clregracalculocargahoraria->sql_query($pesquisa_chave));
          if($clregracalculocargahoraria->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed127_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_ed127_codigo",true,1,"chave_ed127_codigo",true);
</script>
