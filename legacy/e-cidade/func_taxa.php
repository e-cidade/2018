<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_taxa_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltaxa = new cl_taxa;
$cltaxa->rotulo->label("ar36_sequencial");
$cltaxa->rotulo->label("ar36_descricao");
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
          <td><label><?=$Lar36_sequencial?></label></td>
          <td><? db_input("ar36_sequencial",10,$Iar36_sequencial,true,"text",4,"","chave_ar36_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lar36_descricao?></label></td>
          <td><? db_input("ar36_descricao",10,$Iar36_descricao,true,"text",4,"","chave_ar36_descricao");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_taxa.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_taxa.php")==true){
             include(modification("funcoes/db_func_taxa.php"));
           }else{
           $campos = "taxa.*";
           }
        }
        if(isset($chave_ar36_sequencial) && (trim($chave_ar36_sequencial)!="") ){
	         $sql = $cltaxa->sql_query($chave_ar36_sequencial,$campos,"ar36_sequencial");
        }else if(isset($chave_ar36_descricao) && (trim($chave_ar36_descricao)!="") ){
	         $sql = $cltaxa->sql_query("",$campos,"ar36_descricao"," ar36_descricao like '$chave_ar36_descricao%' ");
        }else{
           $sql = $cltaxa->sql_query("",$campos,"ar36_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ar36_descricao)){
          $repassa = array("chave_ar36_sequencial"=>$chave_ar36_sequencial,"chave_ar36_descricao"=>$chave_ar36_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cltaxa->sql_record($cltaxa->sql_query($pesquisa_chave));
          if($cltaxa->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ar36_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_ar36_descricao",true,1,"chave_ar36_descricao",true);
</script>
