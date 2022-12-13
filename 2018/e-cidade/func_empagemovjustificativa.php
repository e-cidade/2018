<?
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_empagemovjustificativa_classe.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$clempagemovjustificativa = new cl_empagemovjustificativa;
$clempagemovjustificativa->rotulo->label("e09_codmov");
$clempagemovjustificativa->rotulo->label("e09_sequencial");
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
        <td><label><?=$Le09_sequencial?></label></td>
        <td><? db_input("e09_sequencial",10,$Ie09_sequencial,true,"text",4,"","chave_e09_sequencial");?></td>
      </tr>
      <tr>
        <td><label><?=$Le09_codmov?></label></td>
        <td><? db_input("e09_codmov",10,$Ie09_codmov,true,"text",4,"","chave_e09_codmov"); ?></td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_empagemovjustificativa.hide();">
</form>
<?
if(!isset($pesquisa_chave)){

  $campos = "empagemovjustificativa.*";

  if(isset($chave_e09_codmov) && (trim($chave_e09_codmov)!="") ){
    $sql = $clempagemovjustificativa->sql_query($chave_e09_codmov,$campos,"e09_codmov");
  }else if(isset($chave_e09_sequencial) && (trim($chave_e09_sequencial)!="") ){
    $sql = $clempagemovjustificativa->sql_query("",$campos,"e09_sequencial"," e09_sequencial like '$chave_e09_sequencial%' ");
  }else{
    $sql = $clempagemovjustificativa->sql_query("",$campos,"e09_codmov","");
  }
  $repassa = array();
  if(isset($chave_e09_sequencial)){
    $repassa = array("chave_e09_codmov"=>$chave_e09_codmov,"chave_e09_sequencial"=>$chave_e09_sequencial);
  }
  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
  db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
  echo '  </fieldset>';
  echo '</div>';
}else{
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
    $result = $clempagemovjustificativa->sql_record($clempagemovjustificativa->sql_query($pesquisa_chave));
    if($clempagemovjustificativa->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$e09_sequencial',false);</script>";
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
  js_tabulacaoforms("form2","chave_e09_sequencial",true,1,"chave_e09_sequencial",true);
</script>
