<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_planilhadistribuicao_classe.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$clplanilhadistribuicao = new cl_planilhadistribuicao;
$clplanilhadistribuicao->rotulo->label("pd01_sequencial");
$clplanilhadistribuicao->rotulo->label("pd01_descricao");
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
        <td><label><?=$Lpd01_sequencial?></label></td>
        <td><? db_input("pd01_sequencial",10,$Ipd01_sequencial,true,"text",4,"","chave_pd01_sequencial"); ?></td>
      </tr>
      <tr>
        <td><label><?=$Lpd01_descricao?></label></td>
        <td><? db_input("pd01_descricao",25,$Lpd01_descricao,true,"text",4,"","chave_pd01_descricao");?></td>
      </tr>
    </table>
  </fieldset>
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_planilhadistribuicao.hide();">
</form>
<?
$campos = "planilhadistribuicao.*";

if(!isset($pesquisa_chave)){
  if(isset($chave_pd01_sequencial) && (trim($chave_pd01_sequencial)!="") ){
    $sql = $clplanilhadistribuicao->sql_query($chave_pd01_sequencial,$campos,"pd01_sequencial");
  }else if(isset($chave_pd01_descricao) && (trim($chave_pd01_descricao)!="") ){
    $sql = $clplanilhadistribuicao->sql_query("",$campos,"pd01_sequencial"," pd01_descricao ilike '$chave_pd01_descricao%' ");
  }else{
    $sql = $clplanilhadistribuicao->sql_query("",$campos,"pd01_sequencial","");
  }
  $repassa = array();
  if(isset($chave_pd01_sequencial)){
    $repassa = array("chave_pd01_sequencial"=>$chave_pd01_sequencial,"chave_pd01_sequencial"=>$chave_pd01_sequencial);
  }
  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
  db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa, false);
  echo '  </fieldset>';
  echo '</div>';
}else{
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
    $result = $clplanilhadistribuicao->sql_record($clplanilhadistribuicao->sql_query($pesquisa_chave));
    if($clplanilhadistribuicao->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$pd01_descricao',false);</script>";
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
  js_tabulacaoforms("form2","chave_pd01_sequencial",true,1,"chave_pd01_sequencial",true);
</script>

<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
