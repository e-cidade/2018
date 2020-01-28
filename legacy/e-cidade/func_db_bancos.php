<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_db_bancos_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_bancos = new cl_db_bancos;
$cldb_bancos->rotulo->label("db90_codban");
$cldb_bancos->rotulo->label("db90_descr");
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
          <td><label><?=$Ldb90_codban?></label></td>
          <td><? db_input("db90_codban",10,$Idb90_codban,true,"text",4,"","chave_db90_codban"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ldb90_descr?></label></td>
          <td><? db_input("db90_descr",10,$Idb90_descr,true,"text",4,"","chave_db90_descr");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_bancos.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_bancos.php")==true){
             include(modification("funcoes/db_func_db_bancos.php"));
           }else{
           $campos = "db_bancos.*";
           }
        }
        if(isset($chave_db90_codban) && (trim($chave_db90_codban)!="") ){
	         $sql = $cldb_bancos->sql_query($chave_db90_codban,$campos,"db90_codban");
        }else if(isset($chave_db90_descr) && (trim($chave_db90_descr)!="") ){
	         $sql = $cldb_bancos->sql_query("",$campos,"db90_descr"," db90_descr like '$chave_db90_descr%' ");
        }else{
           $sql = $cldb_bancos->sql_query("",$campos,"db90_codban","");
        }
        $repassa = array();
        if(isset($chave_db90_descr)){
          $repassa = array("chave_db90_codban"=>$chave_db90_codban,"chave_db90_descr"=>$chave_db90_descr);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_bancos->sql_record($cldb_bancos->sql_query($pesquisa_chave));
          if($cldb_bancos->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$db90_descr',false);</script>";
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
js_tabulacaoforms("form2","chave_db90_descr",true,1,"chave_db90_descr",true);
</script>
