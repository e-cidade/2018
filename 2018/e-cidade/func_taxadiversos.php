<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_taxadiversos_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltaxadiversos = new cl_taxadiversos;
$cltaxadiversos->rotulo->label("y119_natureza");
$clgrupotaxadiversos = new cl_grupotaxadiversos;
$clgrupotaxadiversos->rotulo->label("y118_descricao");
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
          <td><label>Grupo:</label></td>
          <td><? db_input("y118_descricao",19,$Iy118_descricao,true,"text",4,"","chave_y118_descricao"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ly119_natureza?></label></td>
          <td><? db_input("y119_natureza",19,$Iy119_natureza,true,"text", 1,"","chave_y119_natureza");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="button" id="limpar" value="Limpar" onclick="location.href=location.href" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_taxadiversos.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_taxadiversos.php")==true){
             include(modification("funcoes/db_func_taxadiversos.php"));
           }else{
           $campos = "taxadiversos.*";
           }
        }
        if(isset($chave_y119_sequencial) && (trim($chave_y119_sequencial)!="") ){
	         $sql = $cltaxadiversos->sql_query($chave_y119_sequencial,$campos,"y119_sequencial");
        }else if(isset($chave_y118_descricao) && (trim($chave_y118_descricao)!="") ){
           $sql = $cltaxadiversos->sql_query("",$campos,"y119_sequencial"," y118_descricao ilike '$chave_y118_descricao%' ");
        }else if(isset($chave_y119_natureza) && (trim($chave_y119_natureza)!="") ){
	         $sql = $cltaxadiversos->sql_query("",$campos,"y119_sequencial"," y119_natureza ilike '$chave_y119_natureza%' ");
        }else if(isset($chave_y119_sequencial) && (trim($chave_y119_sequencial)!="") ){
           $sql = $cltaxadiversos->sql_query("",$campos,"y119_sequencial"," y119_sequencial like '$chave_y119_sequencial%' ");
        }else{
           $sql = $cltaxadiversos->sql_query("",$campos,"y119_sequencial","");
        }
        $repassa = array();
        if(isset($chave_y119_sequencial)){
          $repassa = array("chave_y119_sequencial"=>$chave_y119_sequencial,"chave_y119_sequencial"=>$chave_y119_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cltaxadiversos->sql_record($cltaxadiversos->sql_query($pesquisa_chave));
          if($cltaxadiversos->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$y119_sequencial',false, '$y119_natureza');</script>";
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
js_tabulacaoforms("form2","chave_y119_sequencial",true,1,"chave_y119_sequencial",true);
</script>
