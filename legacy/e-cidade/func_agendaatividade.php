<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_agendaatividade_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clagendaatividade = new cl_agendaatividade;
$clagendaatividade->rotulo->label("ed129_codigo");
$clagendaatividade->rotulo->label("ed129_codigo");
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
          <td><label><?=$Led129_codigo?></label></td>
          <td><? db_input("ed129_codigo",10,$Ied129_codigo,true,"text",4,"","chave_ed129_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Led129_codigo?></label></td>
          <td><? db_input("ed129_codigo",10,$Ied129_codigo,true,"text",4,"","chave_ed129_codigo");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_agendaatividade.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_agendaatividade.php")==true){
             include("funcoes/db_func_agendaatividade.php");
           }else{
           $campos = "agendaatividade.*";
           }
        }
        if(isset($chave_ed129_codigo) && (trim($chave_ed129_codigo)!="") ){
	         $sql = $clagendaatividade->sql_query($chave_ed129_codigo,$campos,"ed129_codigo");
        }else if(isset($chave_ed129_codigo) && (trim($chave_ed129_codigo)!="") ){
	         $sql = $clagendaatividade->sql_query("",$campos,"ed129_codigo"," ed129_codigo like '$chave_ed129_codigo%' ");
        }else{
           $sql = $clagendaatividade->sql_query("",$campos,"ed129_codigo","");
        }
        $repassa = array();
        if(isset($chave_ed129_codigo)){
          $repassa = array("chave_ed129_codigo"=>$chave_ed129_codigo,"chave_ed129_codigo"=>$chave_ed129_codigo);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clagendaatividade->sql_record($clagendaatividade->sql_query($pesquisa_chave));
          if($clagendaatividade->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed129_codigo',false);</script>";
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
js_tabulacaoforms("form2","chave_ed129_codigo",true,1,"chave_ed129_codigo",true);
</script>
