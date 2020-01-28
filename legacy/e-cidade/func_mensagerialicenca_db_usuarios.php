<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_mensagerialicenca_db_usuarios_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmensagerialicenca_db_usuarios = new cl_mensagerialicenca_db_usuarios;
$clmensagerialicenca_db_usuarios->rotulo->label("am16_sequencial");
$clmensagerialicenca_db_usuarios->rotulo->label("am16_usuario");
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
          <td><label><?=$Lam16_sequencial?></label></td>
          <td><? db_input("am16_sequencial",10,$Iam16_sequencial,true,"text",4,"","chave_am16_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lam16_usuario?></label></td>
          <td><? db_input("am16_usuario",10,$Iam16_usuario,true,"text",4,"","chave_am16_usuario");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_mensagerialicenca_db_usuarios.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_mensagerialicenca_db_usuarios.php")==true){
             include("funcoes/db_func_mensagerialicenca_db_usuarios.php");
           }else{
           $campos = "mensagerialicenca_db_usuarios.*";
           }
        }
        if(isset($chave_am16_sequencial) && (trim($chave_am16_sequencial)!="") ){
	         $sql = $clmensagerialicenca_db_usuarios->sql_query($chave_am16_sequencial,$campos,"am16_sequencial");
        }else if(isset($chave_am16_usuario) && (trim($chave_am16_usuario)!="") ){
	         $sql = $clmensagerialicenca_db_usuarios->sql_query("",$campos,"am16_usuario"," am16_usuario like '$chave_am16_usuario%' ");
        }else{
           $sql = $clmensagerialicenca_db_usuarios->sql_query("",$campos,"am16_sequencial","");
        }
        $repassa = array();
        if(isset($chave_am16_usuario)){
          $repassa = array("chave_am16_sequencial"=>$chave_am16_sequencial,"chave_am16_usuario"=>$chave_am16_usuario);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmensagerialicenca_db_usuarios->sql_record($clmensagerialicenca_db_usuarios->sql_query($pesquisa_chave));
          if($clmensagerialicenca_db_usuarios->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$am16_usuario',false);</script>";
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
js_tabulacaoforms("form2","chave_am16_usuario",true,1,"chave_am16_usuario",true);
</script>
