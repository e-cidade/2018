<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_issarquivoretencaoregistrodisbanco_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clissarquivoretencaoregistrodisbanco = new cl_issarquivoretencaoregistrodisbanco;
$clissarquivoretencaoregistrodisbanco->rotulo->label("q94_sequencial");
$clissarquivoretencaoregistrodisbanco->rotulo->label("q94_issarquivoretencaoregistro");
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
          <td><label><?=$Lq94_sequencial?></label></td>
          <td><? db_input("q94_sequencial",10,$Iq94_sequencial,true,"text",4,"","chave_q94_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lq94_issarquivoretencaoregistro?></label></td>
          <td><? db_input("q94_issarquivoretencaoregistro",10,$Iq94_issarquivoretencaoregistro,true,"text",4,"","chave_q94_issarquivoretencaoregistro");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_issarquivoretencaoregistrodisbanco.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_issarquivoretencaoregistrodisbanco.php")==true){
             include("funcoes/db_func_issarquivoretencaoregistrodisbanco.php");
           }else{
           $campos = "issarquivoretencaoregistrodisbanco.*";
           }
        }
        if(isset($chave_q94_sequencial) && (trim($chave_q94_sequencial)!="") ){
	         $sql = $clissarquivoretencaoregistrodisbanco->sql_query($chave_q94_sequencial,$campos,"q94_sequencial");
        }else if(isset($chave_q94_issarquivoretencaoregistro) && (trim($chave_q94_issarquivoretencaoregistro)!="") ){
	         $sql = $clissarquivoretencaoregistrodisbanco->sql_query("",$campos,"q94_issarquivoretencaoregistro"," q94_issarquivoretencaoregistro like '$chave_q94_issarquivoretencaoregistro%' ");
        }else{
           $sql = $clissarquivoretencaoregistrodisbanco->sql_query("",$campos,"q94_sequencial","");
        }
        $repassa = array();
        if(isset($chave_q94_issarquivoretencaoregistro)){
          $repassa = array("chave_q94_sequencial"=>$chave_q94_sequencial,"chave_q94_issarquivoretencaoregistro"=>$chave_q94_issarquivoretencaoregistro);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clissarquivoretencaoregistrodisbanco->sql_record($clissarquivoretencaoregistrodisbanco->sql_query($pesquisa_chave));
          if($clissarquivoretencaoregistrodisbanco->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$q94_issarquivoretencaoregistro',false);</script>";
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
js_tabulacaoforms("form2","chave_q94_issarquivoretencaoregistro",true,1,"chave_q94_issarquivoretencaoregistro",true);
</script>
