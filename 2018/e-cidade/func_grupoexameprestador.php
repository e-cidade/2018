<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_grupoexameprestador_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clgrupoexameprestador = new cl_grupoexameprestador;
$clgrupoexameprestador->rotulo->label("age03_sequencial");
$clgrupoexameprestador->rotulo->label("age03_sequencial");
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
          <td><label><?=$Lage03_sequencial?></label></td>
          <td><? db_input("age03_sequencial",10,$Iage03_sequencial,true,"text",4,"","chave_age03_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lage03_sequencial?></label></td>
          <td><? db_input("age03_sequencial",10,$Iage03_sequencial,true,"text",4,"","chave_age03_sequencial");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_grupoexameprestador.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_grupoexameprestador.php")==true){
             include(modification("funcoes/db_func_grupoexameprestador.php"));
           }else{
           $campos = "grupoexameprestador.*";
           }
        }
        if(isset($chave_age03_sequencial) && (trim($chave_age03_sequencial)!="") ){
	         $sql = $clgrupoexameprestador->sql_query($chave_age03_sequencial,$campos,"age03_sequencial");
        }else if(isset($chave_age03_sequencial) && (trim($chave_age03_sequencial)!="") ){
	         $sql = $clgrupoexameprestador->sql_query("",$campos,"age03_sequencial"," age03_sequencial like '$chave_age03_sequencial%' ");
        }else{
           $sql = $clgrupoexameprestador->sql_query("",$campos,"age03_sequencial","");
        }
        $repassa = array();
        if(isset($chave_age03_sequencial)){
          $repassa = array("chave_age03_sequencial"=>$chave_age03_sequencial,"chave_age03_sequencial"=>$chave_age03_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clgrupoexameprestador->sql_record($clgrupoexameprestador->sql_query($pesquisa_chave));
          if($clgrupoexameprestador->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$age03_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_age03_sequencial",true,1,"chave_age03_sequencial",true);
</script>
