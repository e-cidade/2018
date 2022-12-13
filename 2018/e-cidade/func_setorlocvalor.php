<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_setorlocvalor_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsetorlocvalor = new cl_setorlocvalor;
$clsetorlocvalor->rotulo->label("j05_sequencial");
$clsetorlocvalor->rotulo->label("j05_setorloc");
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
          <td><label><?=$Lj05_sequencial?></label></td>
          <td><? db_input("j05_sequencial",11,$Ij05_sequencial,true,"text",4,"","chave_j05_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lj05_setorloc?></label></td>
          <td><? db_input("j05_setorloc",11,$Ij05_setorloc,true,"text",4,"","chave_j05_setorloc");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_setorlocvalor.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_setorlocvalor.php")==true){
             include("funcoes/db_func_setorlocvalor.php");
           }else{
           $campos = "setorlocvalor.*";
           }
        }
        if(isset($chave_j05_sequencial) && (trim($chave_j05_sequencial)!="") ){
	         $sql = $clsetorlocvalor->sql_query($chave_j05_sequencial,$campos,"j05_sequencial");
        }else if(isset($chave_j05_setorloc) && (trim($chave_j05_setorloc)!="") ){
	         $sql = $clsetorlocvalor->sql_query("",$campos,"j05_setorloc"," j05_setorloc like '$chave_j05_setorloc%' ");
        }else{
           $sql = $clsetorlocvalor->sql_query("",$campos,"j05_sequencial","");
        }
        $repassa = array();
        if(isset($chave_j05_setorloc)){
          $repassa = array("chave_j05_sequencial"=>$chave_j05_sequencial,"chave_j05_setorloc"=>$chave_j05_setorloc);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clsetorlocvalor->sql_record($clsetorlocvalor->sql_query($pesquisa_chave));
          if($clsetorlocvalor->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j05_setorloc',false);</script>";
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
js_tabulacaoforms("form2","chave_j05_setorloc",true,1,"chave_j05_setorloc",true);
</script>
