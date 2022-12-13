<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_liclicitaeventodocumento_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clliclicitaeventodocumento = new cl_liclicitaeventodocumento;
$clliclicitaeventodocumento->rotulo->label("l47_sequencial");
$clliclicitaeventodocumento->rotulo->label("l47_nomearquivo");
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
          <td><label><?=$Ll47_sequencial?></label></td>
          <td><? db_input("l47_sequencial",10,$Il47_sequencial,true,"text",4,"","chave_l47_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ll47_nomearquivo?></label></td>
          <td><? db_input("l47_nomearquivo",10,$Il47_nomearquivo,true,"text",4,"","chave_l47_nomearquivo");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_liclicitaeventodocumento.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_liclicitaeventodocumento.php")==true){
             include(modification("funcoes/db_func_liclicitaeventodocumento.php"));
           }else{
           $campos = "liclicitaeventodocumento.*";
           }
        }
        if(isset($chave_l47_sequencial) && (trim($chave_l47_sequencial)!="") ){
	         $sql = $clliclicitaeventodocumento->sql_query($chave_l47_sequencial,$campos,"l47_sequencial");
        }else if(isset($chave_l47_nomearquivo) && (trim($chave_l47_nomearquivo)!="") ){
	         $sql = $clliclicitaeventodocumento->sql_query("",$campos,"l47_nomearquivo"," l47_nomearquivo like '$chave_l47_nomearquivo%' ");
        }else{
           $sql = $clliclicitaeventodocumento->sql_query("",$campos,"l47_sequencial","");
        }
        $repassa = array();
        if(isset($chave_l47_nomearquivo)){
          $repassa = array("chave_l47_sequencial"=>$chave_l47_sequencial,"chave_l47_nomearquivo"=>$chave_l47_nomearquivo);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clliclicitaeventodocumento->sql_record($clliclicitaeventodocumento->sql_query($pesquisa_chave));
          if($clliclicitaeventodocumento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$l47_nomearquivo',false);</script>";
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
js_tabulacaoforms("form2","chave_l47_nomearquivo",true,1,"chave_l47_nomearquivo",true);
</script>
