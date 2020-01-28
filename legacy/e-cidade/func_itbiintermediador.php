<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_itbiintermediador_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clitbiintermediador = new cl_itbiintermediador;
$clitbiintermediador->rotulo->label("it35_sequencial");
$clitbiintermediador->rotulo->label("it35_sequencial");
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
          <td><label><?=$Lit35_sequencial?></label></td>
          <td><? db_input("it35_sequencial",10,$Iit35_sequencial,true,"text",4,"","chave_it35_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lit35_sequencial?></label></td>
          <td><? db_input("it35_sequencial",10,$Iit35_sequencial,true,"text",4,"","chave_it35_sequencial");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_itbiintermediador.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_itbiintermediador.php")==true){
             include(modification("funcoes/db_func_itbiintermediador.php"));
           }else{
           $campos = "itbiintermediador.*";
           }
        }
        if(isset($chave_it35_sequencial) && (trim($chave_it35_sequencial)!="") ){
	         $sql = $clitbiintermediador->sql_query($chave_it35_sequencial,$campos,"it35_sequencial");
        }else if(isset($chave_it35_sequencial) && (trim($chave_it35_sequencial)!="") ){
	         $sql = $clitbiintermediador->sql_query("",$campos,"it35_sequencial"," it35_sequencial like '$chave_it35_sequencial%' ");
        }else{
           $sql = $clitbiintermediador->sql_query("",$campos,"it35_sequencial","");
        }
        $repassa = array();
        if(isset($chave_it35_sequencial)){
          $repassa = array("chave_it35_sequencial"=>$chave_it35_sequencial,"chave_it35_sequencial"=>$chave_it35_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clitbiintermediador->sql_record($clitbiintermediador->sql_query($pesquisa_chave));
          if($clitbiintermediador->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$it35_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_it35_sequencial",true,1,"chave_it35_sequencial",true);
</script>
