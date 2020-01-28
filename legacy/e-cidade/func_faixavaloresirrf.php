<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_faixavaloresirrf_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clfaixavaloresirrf = new cl_faixavaloresirrf;
$clfaixavaloresirrf->rotulo->label("rh175_sequencial");
$clfaixavaloresirrf->rotulo->label("rh175_percentual");
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
          <td><label><?=$Lrh175_sequencial?></label></td>
          <td><? db_input("rh175_sequencial",10,$Irh175_sequencial,true,"text",4,"","chave_rh175_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lrh175_percentual?></label></td>
          <td><? db_input("rh175_percentual",10,$Irh175_percentual,true,"text",4,"","chave_rh175_percentual");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_faixavaloresirrf.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_faixavaloresirrf.php")==true){
             include(modification("funcoes/db_func_faixavaloresirrf.php"));
           }else{
           $campos = "faixavaloresirrf.*";
           }
        }
        if(isset($chave_rh175_sequencial) && (trim($chave_rh175_sequencial)!="") ){
	         $sql = $clfaixavaloresirrf->sql_query($chave_rh175_sequencial,$campos,"rh175_sequencial");
        }else if(isset($chave_rh175_percentual) && (trim($chave_rh175_percentual)!="") ){
	         $sql = $clfaixavaloresirrf->sql_query("",$campos,"rh175_percentual"," rh175_percentual like '$chave_rh175_percentual%' ");
        }else{
           $sql = $clfaixavaloresirrf->sql_query("",$campos,"rh175_sequencial","");
        }
        $repassa = array();
        if(isset($chave_rh175_percentual)){
          $repassa = array("chave_rh175_sequencial"=>$chave_rh175_sequencial,"chave_rh175_percentual"=>$chave_rh175_percentual);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clfaixavaloresirrf->sql_record($clfaixavaloresirrf->sql_query($pesquisa_chave));
          if($clfaixavaloresirrf->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh175_percentual',false);</script>";
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
js_tabulacaoforms("form2","chave_rh175_percentual",true,1,"chave_rh175_percentual",true);
</script>
