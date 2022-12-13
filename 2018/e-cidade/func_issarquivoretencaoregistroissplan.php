<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_issarquivoretencaoregistroissplan_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clissarquivoretencaoregistroissplan = new cl_issarquivoretencaoregistroissplan;
$clissarquivoretencaoregistroissplan->rotulo->label("q137_sequencial");
$clissarquivoretencaoregistroissplan->rotulo->label("q137_issplan");
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
          <td><label><?=$Lq137_sequencial?></label></td>
          <td><? db_input("q137_sequencial",10,$Iq137_sequencial,true,"text",4,"","chave_q137_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lq137_issplan?></label></td>
          <td><? db_input("q137_issplan",10,$Iq137_issplan,true,"text",4,"","chave_q137_issplan");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_issarquivoretencaoregistroissplan.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_issarquivoretencaoregistroissplan.php")==true){
             include("funcoes/db_func_issarquivoretencaoregistroissplan.php");
           }else{
           $campos = "issarquivoretencaoregistroissplan.*";
           }
        }
        if(isset($chave_q137_sequencial) && (trim($chave_q137_sequencial)!="") ){
	         $sql = $clissarquivoretencaoregistroissplan->sql_query($chave_q137_sequencial,$campos,"q137_sequencial");
        }else if(isset($chave_q137_issplan) && (trim($chave_q137_issplan)!="") ){
	         $sql = $clissarquivoretencaoregistroissplan->sql_query("",$campos,"q137_issplan"," q137_issplan like '$chave_q137_issplan%' ");
        }else{
           $sql = $clissarquivoretencaoregistroissplan->sql_query("",$campos,"q137_sequencial","");
        }
        $repassa = array();
        if(isset($chave_q137_issplan)){
          $repassa = array("chave_q137_sequencial"=>$chave_q137_sequencial,"chave_q137_issplan"=>$chave_q137_issplan);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clissarquivoretencaoregistroissplan->sql_record($clissarquivoretencaoregistroissplan->sql_query($pesquisa_chave));
          if($clissarquivoretencaoregistroissplan->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$q137_issplan',false);</script>";
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
js_tabulacaoforms("form2","chave_q137_issplan",true,1,"chave_q137_issplan",true);
</script>
