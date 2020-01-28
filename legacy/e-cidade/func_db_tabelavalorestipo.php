<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_db_tabelavalorestipo_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_tabelavalorestipo = new cl_db_tabelavalorestipo;
$cldb_tabelavalorestipo->rotulo->label("db151_sequencial");
$cldb_tabelavalorestipo->rotulo->label("db151_descricao");
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
          <td><label><?=$Ldb151_sequencial?></label></td>
          <td><? db_input("db151_sequencial",10,$Idb151_sequencial,true,"text",4,"","chave_db151_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ldb151_descricao?></label></td>
          <td><? db_input("db151_descricao",10,$Idb151_descricao,true,"text",4,"","chave_db151_descricao");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_tabelavalorestipo.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_tabelavalorestipo.php")==true){
             include(modification("funcoes/db_func_db_tabelavalorestipo.php"));
           }else{
           $campos = "db_tabelavalorestipo.*";
           }
        }
        if(isset($chave_db151_sequencial) && (trim($chave_db151_sequencial)!="") ){
	         $sql = $cldb_tabelavalorestipo->sql_query($chave_db151_sequencial,$campos,"db151_sequencial");
        }else if(isset($chave_db151_descricao) && (trim($chave_db151_descricao)!="") ){
	         $sql = $cldb_tabelavalorestipo->sql_query("",$campos,"db151_descricao"," db151_descricao like '$chave_db151_descricao%' ");
        }else{
           $sql = $cldb_tabelavalorestipo->sql_query("",$campos,"db151_sequencial","");
        }
        $repassa = array();
        if(isset($chave_db151_descricao)){
          $repassa = array("chave_db151_sequencial"=>$chave_db151_sequencial,"chave_db151_descricao"=>$chave_db151_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_tabelavalorestipo->sql_record($cldb_tabelavalorestipo->sql_query($pesquisa_chave));
          if($cldb_tabelavalorestipo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$db151_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_db151_descricao",true,1,"chave_db151_descricao",true);
</script>
