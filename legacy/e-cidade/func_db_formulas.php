<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_formulas_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_formulas = new cl_db_formulas;
$cldb_formulas->rotulo->label("db148_sequencial");
$cldb_formulas->rotulo->label("db148_nome");
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
          <td><label><?=$Ldb148_sequencial?></label></td>
          <td><? db_input("db148_sequencial",10,$Idb148_sequencial,true,"text",4,"","chave_db148_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ldb148_nome?></label></td>
          <td><? db_input("db148_nome",10,$Idb148_nome,true,"text",4,"","chave_db148_nome");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_formulas.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_formulas.php")==true){
             include("funcoes/db_func_db_formulas.php");
           }else{
           $campos = "db_formulas.*";
           }
        }
        if(isset($chave_db148_sequencial) && (trim($chave_db148_sequencial)!="") ){
           $sWhere  = "     db148_sequencial = {$chave_db148_sequencial} ";
           $sWhere .= " and db148_ambiente   = 'f' ";
	         $sql = $cldb_formulas->sql_query($chave_db148_sequencial,$campos,"db148_sequencial", $sWhere);
        }else if(isset($chave_db148_nome) && (trim($chave_db148_nome)!="") ){
           $sWhere  = "     db148_nome like '$chave_db148_nome%' ";
           $sWhere .= " and db148_ambiente   = 'f' ";
	         $sql = $cldb_formulas->sql_query("",$campos,"db148_sequencial", $sWhere);
        }else{
           $sWhere  = " db148_ambiente = 'f' ";
           $sql = $cldb_formulas->sql_query("",$campos,"db148_sequencial", $sWhere);
        }
        $repassa = array();
        if(isset($chave_db148_nome)){
          $repassa = array("chave_db148_sequencial"=>$chave_db148_sequencial,"chave_db148_nome"=>$chave_db148_nome);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_formulas->sql_record($cldb_formulas->sql_query($pesquisa_chave));
          if($cldb_formulas->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$db148_sequencial',false, '$db148_nome');</script>";
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
js_tabulacaoforms("form2","chave_db148_sequencial",true,1,"chave_db148_nome",true);
</script>
