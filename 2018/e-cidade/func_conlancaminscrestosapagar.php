<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_conlancaminscrestosapagar_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconlancaminscrestosapagar = new cl_conlancaminscrestosapagar;
$clconlancaminscrestosapagar->rotulo->label("c108_sequencial");
$clconlancaminscrestosapagar->rotulo->label("c108_sequencial");
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
        <td><label><?=$Lc108_sequencial?></label></td>
        <td><? db_input("c108_sequencial",10,$Ic108_sequencial,true,"text",4,"","chave_c108_sequencial"); ?></td>
      </tr>
      <tr>
        <td><label><?=$Lc108_sequencial?></label></td>
        <td><? db_input("c108_sequencial",10,$Ic108_sequencial,true,"text",4,"","chave_c108_sequencial");?></td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conlancaminscrestosapagar.hide();">
</form>
<?
if(!isset($pesquisa_chave)){

  $campos = "conlancaminscrestosapagar.*";
  if(isset($chave_c108_sequencial) && (trim($chave_c108_sequencial)!="") ){
    $sql = $clconlancaminscrestosapagar->sql_query($chave_c108_sequencial,$campos,"c108_sequencial");
  }else if(isset($chave_c108_sequencial) && (trim($chave_c108_sequencial)!="") ){
    $sql = $clconlancaminscrestosapagar->sql_query("",$campos,"c108_sequencial"," c108_sequencial like '$chave_c108_sequencial%' ");
  }else{
    $sql = $clconlancaminscrestosapagar->sql_query("",$campos,"c108_sequencial","");
  }
  $repassa = array();
  if(isset($chave_c108_sequencial)){
    $repassa = array("chave_c108_sequencial"=>$chave_c108_sequencial,"chave_c108_sequencial"=>$chave_c108_sequencial);
  }
  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
  db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
  echo '  </fieldset>';
  echo '</div>';
}else{
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
    $result = $clconlancaminscrestosapagar->sql_record($clconlancaminscrestosapagar->sql_query($pesquisa_chave));
    if($clconlancaminscrestosapagar->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$c108_sequencial',false);</script>";
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
  js_tabulacaoforms("form2","chave_c108_sequencial",true,1,"chave_c108_sequencial",true);
</script>
