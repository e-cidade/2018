<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_cotaprestadoraexamemensal_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcotaprestadoraexamemensal = new cl_cotaprestadoraexamemensal;
$clcotaprestadoraexamemensal->rotulo->label("age01_sequencial");
$clcotaprestadoraexamemensal->rotulo->label("age01_sequencial");
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
          <td><label><?=$Lage01_sequencial?></label></td>
          <td><? db_input("age01_sequencial",10,$Iage01_sequencial,true,"text",4,"","chave_age01_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lage01_sequencial?></label></td>
          <td><? db_input("age01_sequencial",10,$Iage01_sequencial,true,"text",4,"","chave_age01_sequencial");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cotaprestadoraexamemensal.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_cotaprestadoraexamemensal.php")==true){
             include(modification("funcoes/db_func_cotaprestadoraexamemensal.php"));
           }else{
           $campos = "cotaprestadoraexamemensal.*";
           }
        }
        if(isset($chave_age01_sequencial) && (trim($chave_age01_sequencial)!="") ){
	         $sql = $clcotaprestadoraexamemensal->sql_query($chave_age01_sequencial,$campos,"age01_sequencial");
        }else if(isset($chave_age01_sequencial) && (trim($chave_age01_sequencial)!="") ){
	         $sql = $clcotaprestadoraexamemensal->sql_query("",$campos,"age01_sequencial"," age01_sequencial like '$chave_age01_sequencial%' ");
        }else{
           $sql = $clcotaprestadoraexamemensal->sql_query("",$campos,"age01_sequencial","");
        }
        $repassa = array();
        if(isset($chave_age01_sequencial)){
          $repassa = array("chave_age01_sequencial"=>$chave_age01_sequencial,"chave_age01_sequencial"=>$chave_age01_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcotaprestadoraexamemensal->sql_record($clcotaprestadoraexamemensal->sql_query($pesquisa_chave));
          if($clcotaprestadoraexamemensal->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$age01_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_age01_sequencial",true,1,"chave_age01_sequencial",true);
</script>
