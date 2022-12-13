<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_codigoclienterefeisul_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcodigoclienterefeisul = new cl_codigoclienterefeisul;
$clcodigoclienterefeisul->rotulo->label("rh171_sequencial");
$clcodigoclienterefeisul->rotulo->label("rh171_codigocliente");
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
          <td><label><?=$Lrh171_sequencial?></label></td>
          <td><? db_input("rh171_sequencial",10,$Irh171_sequencial,true,"text",4,"","chave_rh171_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lrh171_codigocliente?></label></td>
          <td><? db_input("rh171_codigocliente",10,$Irh171_codigocliente,true,"text",4,"","chave_rh171_codigocliente");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_codigoclienterefeisul.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_codigoclienterefeisul.php")==true){
             include(modification("funcoes/db_func_codigoclienterefeisul.php"));
           }else{
           $campos = "codigoclienterefeisul.*";
           }
        }
        if(isset($chave_rh171_sequencial) && (trim($chave_rh171_sequencial)!="") ){
	         $sql = $clcodigoclienterefeisul->sql_query($chave_rh171_sequencial,$campos,"rh171_sequencial");
        }else if(isset($chave_rh171_codigocliente) && (trim($chave_rh171_codigocliente)!="") ){
	         $sql = $clcodigoclienterefeisul->sql_query("",$campos,"rh171_codigocliente"," rh171_codigocliente like '$chave_rh171_codigocliente%' ");
        }else{
           $sql = $clcodigoclienterefeisul->sql_query("",$campos,"rh171_sequencial","");
        }
        $repassa = array();
        if(isset($chave_rh171_codigocliente)){
          $repassa = array("chave_rh171_sequencial"=>$chave_rh171_sequencial,"chave_rh171_codigocliente"=>$chave_rh171_codigocliente);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcodigoclienterefeisul->sql_record($clcodigoclienterefeisul->sql_query($pesquisa_chave));
          if($clcodigoclienterefeisul->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh171_codigocliente',false);</script>";
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
js_tabulacaoforms("form2","chave_rh171_codigocliente",true,1,"chave_rh171_codigocliente",true);
</script>
