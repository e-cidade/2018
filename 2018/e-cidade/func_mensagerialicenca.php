<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_mensagerialicenca_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmensagerialicenca = new cl_mensagerialicenca;
$clmensagerialicenca->rotulo->label("am14_sequencial");
$clmensagerialicenca->rotulo->label("am14_mensagem");
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
          <td><label><?=$Lam14_sequencial?></label></td>
          <td><? db_input("am14_sequencial",10,$Iam14_sequencial,true,"text",4,"","chave_am14_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lam14_mensagem?></label></td>
          <td><? db_input("am14_mensagem",10,$Iam14_mensagem,true,"text",4,"","chave_am14_mensagem");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_mensagerialicenca.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_mensagerialicenca.php")==true){
             include("funcoes/db_func_mensagerialicenca.php");
           }else{
           $campos = "mensagerialicenca.*";
           }
        }
        if(isset($chave_am14_sequencial) && (trim($chave_am14_sequencial)!="") ){
	         $sql = $clmensagerialicenca->sql_query($chave_am14_sequencial,$campos,"am14_sequencial");
        }else if(isset($chave_am14_mensagem) && (trim($chave_am14_mensagem)!="") ){
	         $sql = $clmensagerialicenca->sql_query("",$campos,"am14_mensagem"," am14_mensagem like '$chave_am14_mensagem%' ");
        }else{
           $sql = $clmensagerialicenca->sql_query("",$campos,"am14_sequencial","");
        }
        $repassa = array();
        if(isset($chave_am14_mensagem)){
          $repassa = array("chave_am14_sequencial"=>$chave_am14_sequencial,"chave_am14_mensagem"=>$chave_am14_mensagem);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmensagerialicenca->sql_record($clmensagerialicenca->sql_query($pesquisa_chave));
          if($clmensagerialicenca->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$am14_mensagem',false);</script>";
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
js_tabulacaoforms("form2","chave_am14_mensagem",true,1,"chave_am14_mensagem",true);
</script>
