<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_liclicitatipoevento_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clliclicitatipoevento = new cl_liclicitatipoevento;
$clliclicitatipoevento->rotulo->label("l45_sequencial");
$clliclicitatipoevento->rotulo->label("l45_descricao");
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
          <td><label><?=$Ll45_sequencial?></label></td>
          <td><? db_input("l45_sequencial",10,$Il45_sequencial,true,"text",4,"","chave_l45_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ll45_descricao?></label></td>
          <td><? db_input("l45_descricao",10,$Il45_descricao,true,"text",4,"","chave_l45_descricao");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_liclicitatipoevento.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_liclicitatipoevento.php")==true){
             include(modification("funcoes/db_func_liclicitatipoevento.php"));
           }else{
           $campos = "liclicitatipoevento.*";
           }
        }
        if(isset($chave_l45_sequencial) && (trim($chave_l45_sequencial)!="") ){
	         $sql = $clliclicitatipoevento->sql_query($chave_l45_sequencial,$campos,"l45_sequencial");
        }else if(isset($chave_l45_descricao) && (trim($chave_l45_descricao)!="") ){
	         $sql = $clliclicitatipoevento->sql_query("",$campos,"l45_descricao"," l45_descricao like '$chave_l45_descricao%' ");
        }else{
           $sql = $clliclicitatipoevento->sql_query("",$campos,"l45_sequencial","");
        }
        $repassa = array();
        if(isset($chave_l45_descricao)){
          $repassa = array("chave_l45_sequencial"=>$chave_l45_sequencial,"chave_l45_descricao"=>$chave_l45_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clliclicitatipoevento->sql_record($clliclicitatipoevento->sql_query($pesquisa_chave));
          if($clliclicitatipoevento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$l45_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_l45_descricao",true,1,"chave_l45_descricao",true);
</script>
