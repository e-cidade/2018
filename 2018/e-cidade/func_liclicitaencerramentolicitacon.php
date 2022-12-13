<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_liclicitaencerramentolicitacon_classe.php"));
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$clliclicitaencerramentolicitacon = new cl_liclicitaencerramentolicitacon;
$clliclicitaencerramentolicitacon->rotulo->label("l18_sequencial");
$clliclicitaencerramentolicitacon->rotulo->label("l18_sequencial");
?>
<html>
<head>
  <meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
  <link href='estilos.css' rel='stylesheet' type='text/css'>
  <script language='JavaScript' type='text/javascript' src='scripts/scripts.js'></script>
</head>
<body>
<form name="form2" method="post" action="" class="container">
</form>
<?
$whereliclicitaencerramentolicitacon = '';

if(!empty($manutencaolicitacoesenviadas)) {
  $whereliclicitaencerramentolicitacon = 'l20_instit = '. db_getsession("DB_instit");
}

if(!isset($pesquisa_chave)){

  $campos = 'distinct l18_data';

  $sql = $clliclicitaencerramentolicitacon->sql_query(null,$campos, "l18_data desc",$whereliclicitaencerramentolicitacon);
  $repassa = array();
  if(isset($chave_l18_sequencial)){
    $repassa = array("chave_l18_sequencial"=>$chave_l18_sequencial,"chave_l18_sequencial"=>$chave_l18_sequencial);
  }
  echo '<div class="container">';
  echo '  <fieldset>';
  echo '    <legend>Resultado da Pesquisa</legend>';
  db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
  echo '  </fieldset>';
  echo '</div>';

}else{
  
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
    
    $whereliclicitaencerramentolicitacon =  "l18_sequencial = {$pesquisa_chave}";
    if(!empty($manutencaolicitacoesenviadas)) {
      $whereliclicitaencerramentolicitacon .=  'l20_instit = '. db_getsession("DB_instit");
    }

    $result = $clliclicitaencerramentolicitacon->sql_record($sql = $clliclicitaencerramentolicitacon->sql_query(null, "*", null, $whereliclicitaencerramentolicitacon));

    if($clliclicitaencerramentolicitacon->numrows!=0){
      db_fieldsmemory($result,0);
      echo "<script>".$funcao_js."('$l18_data',false);</script>";
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
  js_tabulacaoforms("form2","chave_l18_sequencial",true,1,"chave_l18_sequencial",true);
</script>
