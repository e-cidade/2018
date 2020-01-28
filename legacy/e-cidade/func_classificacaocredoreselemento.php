<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_classificacaocredoreselemento_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clclassificacaocredoreselemento = new cl_classificacaocredoreselemento;
$clclassificacaocredoreselemento->rotulo->label("cc32_sequencial");
$clclassificacaocredoreselemento->rotulo->label("cc32_classificacaocredores");
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
          <td><label><?=$Lcc32_sequencial?></label></td>
          <td><? db_input("cc32_sequencial",10,$Icc32_sequencial,true,"text",4,"","chave_cc32_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lcc32_classificacaocredores?></label></td>
          <td><? db_input("cc32_classificacaocredores",10,$Icc32_classificacaocredores,true,"text",4,"","chave_cc32_classificacaocredores");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_classificacaocredoreselemento.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_classificacaocredoreselemento.php")==true){
             include(modification("funcoes/db_func_classificacaocredoreselemento.php"));
           }else{
           $campos = "classificacaocredoreselemento.*";
           }
        }
        if(isset($chave_cc32_sequencial) && (trim($chave_cc32_sequencial)!="") ){
	         $sql = $clclassificacaocredoreselemento->sql_query($chave_cc32_sequencial,$campos,"cc32_sequencial");
        }else if(isset($chave_cc32_classificacaocredores) && (trim($chave_cc32_classificacaocredores)!="") ){
	         $sql = $clclassificacaocredoreselemento->sql_query("",$campos,"cc32_classificacaocredores"," cc32_classificacaocredores like '$chave_cc32_classificacaocredores%' ");
        }else{
           $sql = $clclassificacaocredoreselemento->sql_query("",$campos,"cc32_sequencial","");
        }
        $repassa = array();
        if(isset($chave_cc32_classificacaocredores)){
          $repassa = array("chave_cc32_sequencial"=>$chave_cc32_sequencial,"chave_cc32_classificacaocredores"=>$chave_cc32_classificacaocredores);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clclassificacaocredoreselemento->sql_record($clclassificacaocredoreselemento->sql_query($pesquisa_chave));
          if($clclassificacaocredoreselemento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$cc32_classificacaocredores',false);</script>";
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
js_tabulacaoforms("form2","chave_cc32_classificacaocredores",true,1,"chave_cc32_classificacaocredores",true);
</script>
