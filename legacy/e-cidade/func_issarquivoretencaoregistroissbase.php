<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_issarquivoretencaoregistroissbase_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clissarquivoretencaoregistroissbase = new cl_issarquivoretencaoregistroissbase;
$clissarquivoretencaoregistroissbase->rotulo->label("q128_sequencial");
$clissarquivoretencaoregistroissbase->rotulo->label("q128_sequencial");
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
          <td><label><?=$Lq128_sequencial?></label></td>
          <td><? db_input("q128_sequencial",10,$Iq128_sequencial,true,"text",4,"","chave_q128_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lq128_sequencial?></label></td>
          <td><? db_input("q128_sequencial",10,$Iq128_sequencial,true,"text",4,"","chave_q128_sequencial");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_issarquivoretencaoregistroissbase.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_issarquivoretencaoregistroissbase.php")==true){
             include("funcoes/db_func_issarquivoretencaoregistroissbase.php");
           }else{
           $campos = "issarquivoretencaoregistroissbase.*";
           }
        }
        if(isset($chave_q128_sequencial) && (trim($chave_q128_sequencial)!="") ){
	         $sql = $clissarquivoretencaoregistroissbase->sql_query($chave_q128_sequencial,$campos,"q128_sequencial");
        }else if(isset($chave_q128_sequencial) && (trim($chave_q128_sequencial)!="") ){
	         $sql = $clissarquivoretencaoregistroissbase->sql_query("",$campos,"q128_sequencial"," q128_sequencial like '$chave_q128_sequencial%' ");
        }else{
           $sql = $clissarquivoretencaoregistroissbase->sql_query("",$campos,"q128_sequencial","");
        }
        $repassa = array();
        if(isset($chave_q128_sequencial)){
          $repassa = array("chave_q128_sequencial"=>$chave_q128_sequencial,"chave_q128_sequencial"=>$chave_q128_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clissarquivoretencaoregistroissbase->sql_record($clissarquivoretencaoregistroissbase->sql_query($pesquisa_chave));
          if($clissarquivoretencaoregistroissbase->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$q128_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_q128_sequencial",true,1,"chave_q128_sequencial",true);
</script>
