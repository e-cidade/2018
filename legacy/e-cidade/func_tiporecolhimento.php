<?php
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_tiporecolhimento_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltiporecolhimento = new cl_tiporecolhimento;
$cltiporecolhimento->rotulo->label("k172_sequencial");
$cltiporecolhimento->rotulo->label("k172_nome");
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
          <td><label><?=$Lk172_sequencial?></label></td>
          <td><? db_input("k172_sequencial",10,$Ik172_sequencial,true,"text",4,"","chave_k172_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lk172_nome?></label></td>
          <td><? db_input("k172_nome",10,$Ik172_nome,true,"text",4,"","chave_k172_nome");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tiporecolhimento.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_tiporecolhimento.php")==true){
             include(modification("funcoes/db_func_tiporecolhimento.php"));
           }else{
           $campos = "tiporecolhimento.*";
           }
        }
        if(isset($chave_k172_sequencial) && (trim($chave_k172_sequencial)!="") ){
	         $sql = $cltiporecolhimento->sql_query($chave_k172_sequencial,$campos,"k172_sequencial");
        }else if(isset($chave_k172_nome) && (trim($chave_k172_nome)!="") ){
	         $sql = $cltiporecolhimento->sql_query("",$campos,"k172_nome"," k172_nome like '$chave_k172_nome%' ");
        }else{
           $sql = $cltiporecolhimento->sql_query("",$campos,"k172_sequencial","");
        }
        $repassa = array();
        if(isset($chave_k172_nome)){
          $repassa = array("chave_k172_sequencial"=>$chave_k172_sequencial,"chave_k172_nome"=>$chave_k172_nome);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cltiporecolhimento->sql_record($cltiporecolhimento->sql_query($pesquisa_chave));
          if($cltiporecolhimento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k172_nome',false);</script>";
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
js_tabulacaoforms("form2","chave_k172_nome",true,1,"chave_k172_nome",true);
</script>
