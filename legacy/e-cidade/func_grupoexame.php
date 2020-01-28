<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_grupoexame_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clgrupoexame = new cl_grupoexame;
$clgrupoexame->rotulo->label("age02_sequencial");
$clgrupoexame->rotulo->label("age02_sequencial");
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
          <td><label><?=$Lage02_sequencial?></label></td>
          <td><? db_input("age02_sequencial",10,$Iage02_sequencial,true,"text",4,"","chave_age02_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lage02_sequencial?></label></td>
          <td><? db_input("age02_sequencial",10,$Iage02_sequencial,true,"text",4,"","chave_age02_sequencial");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_grupoexame.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_grupoexame.php")==true){
             include(modification("funcoes/db_func_grupoexame.php"));
           }else{
           $campos = "grupoexame.*";
           }
        }
        if(isset($chave_age02_sequencial) && (trim($chave_age02_sequencial)!="") ){
	         $sql = $clgrupoexame->sql_query($chave_age02_sequencial,$campos,"age02_sequencial");
        }else if(isset($chave_age02_sequencial) && (trim($chave_age02_sequencial)!="") ){
	         $sql = $clgrupoexame->sql_query("",$campos,"age02_sequencial"," age02_sequencial like '$chave_age02_sequencial%' ");
        }else{
           $sql = $clgrupoexame->sql_query("",$campos,"age02_sequencial","");
        }
        $repassa = array();
        if(isset($chave_age02_sequencial)){
          $repassa = array("chave_age02_sequencial"=>$chave_age02_sequencial,"chave_age02_sequencial"=>$chave_age02_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clgrupoexame->sql_record($clgrupoexame->sql_query($pesquisa_chave));
          if($clgrupoexame->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$age02_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_age02_sequencial",true,1,"chave_age02_sequencial",true);
</script>
