<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_liclicitaevento_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clliclicitaevento = new cl_liclicitaevento;
$clliclicitaevento->rotulo->label("l46_sequencial");
$clliclicitaevento->rotulo->label("l46_liclicita");
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
          <td><label><?=$Ll46_sequencial?></label></td>
          <td><? db_input("l46_sequencial",10,$Il46_sequencial,true,"text",4,"","chave_l46_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ll46_liclicita?></label></td>
          <td><? db_input("l46_liclicita",10,$Il46_liclicita,true,"text",4,"","chave_l46_liclicita");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_liclicitaevento.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_liclicitaevento.php")==true){
             include(modification("funcoes/db_func_liclicitaevento.php"));
           }else{
           $campos = "liclicitaevento.*";
           }
        }
        if(isset($chave_l46_sequencial) && (trim($chave_l46_sequencial)!="") ){
	         $sql = $clliclicitaevento->sql_query($chave_l46_sequencial,$campos,"l46_sequencial");
        }else if(isset($chave_l46_liclicita) && (trim($chave_l46_liclicita)!="") ){
	         $sql = $clliclicitaevento->sql_query("",$campos,"l46_liclicita"," l46_liclicita like '$chave_l46_liclicita%' ");
        }else{
           $sql = $clliclicitaevento->sql_query("",$campos,"l46_sequencial","");
        }
        $repassa = array();
        if(isset($chave_l46_liclicita)){
          $repassa = array("chave_l46_sequencial"=>$chave_l46_sequencial,"chave_l46_liclicita"=>$chave_l46_liclicita);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clliclicitaevento->sql_record($clliclicitaevento->sql_query($pesquisa_chave));
          if($clliclicitaevento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$l46_liclicita',false);</script>";
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
js_tabulacaoforms("form2","chave_l46_liclicita",true,1,"chave_l46_liclicita",true);
</script>
