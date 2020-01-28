<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_ciclosensino_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clciclosensino = new cl_ciclosensino;
$clciclosensino->rotulo->label("mo14_sequencial");
$clciclosensino->rotulo->label("mo14_ciclo");
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
          <td><label><?=$Lmo14_sequencial?></label></td>
          <td><? db_input("mo14_sequencial",10,$Imo14_sequencial,true,"text",4,"","chave_mo14_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lmo14_ciclo?></label></td>
          <td><? db_input("mo14_ciclo",10,$Imo14_ciclo,true,"text",4,"","chave_mo14_ciclo");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_ciclosensino.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_ciclosensino.php")==true){
             include("funcoes/db_func_ciclosensino.php");
           }else{
           $campos = "ciclosensino.*";
           }
        }
        if(isset($chave_mo14_sequencial) && (trim($chave_mo14_sequencial)!="") ){
	         $sql = $clciclosensino->sql_query($chave_mo14_sequencial,$campos,"mo14_sequencial");
        }else if(isset($chave_mo14_ciclo) && (trim($chave_mo14_ciclo)!="") ){
	         $sql = $clciclosensino->sql_query("",$campos,"mo14_ciclo"," mo14_ciclo like '$chave_mo14_ciclo%' ");
        }else{
           $sql = $clciclosensino->sql_query("",$campos,"mo14_sequencial","");
        }
        $repassa = array();
        if(isset($chave_mo14_ciclo)){
          $repassa = array("chave_mo14_sequencial"=>$chave_mo14_sequencial,"chave_mo14_ciclo"=>$chave_mo14_ciclo);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clciclosensino->sql_record($clciclosensino->sql_query($pesquisa_chave));
          if($clciclosensino->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$mo14_ciclo',false);</script>";
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
js_tabulacaoforms("form2","chave_mo14_ciclo",true,1,"chave_mo14_ciclo",true);
</script>
