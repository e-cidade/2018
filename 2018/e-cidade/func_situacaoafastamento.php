<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_situacaoafastamento_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clsituacaoafastamento = new cl_situacaoafastamento;
$clsituacaoafastamento->rotulo->label("rh166_sequencial");
$clsituacaoafastamento->rotulo->label("rh166_descricao");
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
          <td><label><?=$Lrh166_sequencial?></label></td>
          <td><? db_input("rh166_sequencial",19,$Irh166_sequencial,true,"text",4,"","chave_rh166_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lrh166_descricao?></label></td>
          <td><? db_input("rh166_descricao",19,$Irh166_descricao,true,"text",4,"","chave_rh166_descricao");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" onClick="js_limpar_iframe_situacaoafastamento(this)">
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_situacaoafastamento.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_situacaoafastamento.php")==true){
             include("funcoes/db_func_situacaoafastamento.php");
           }else{
           $campos = "situacaoafastamento.*";
           }
        }
        if(isset($chave_rh166_sequencial) && (trim($chave_rh166_sequencial)!="") ){
	         $sql = $clsituacaoafastamento->sql_query($chave_rh166_sequencial,$campos,"rh166_sequencial");
        }else if(isset($chave_rh166_descricao) && (trim($chave_rh166_descricao)!="") ){
	         $sql = $clsituacaoafastamento->sql_query("",$campos,"rh166_sequencial"," rh166_descricao ilike '$chave_rh166_descricao%' ");
        }else{
           $sql = $clsituacaoafastamento->sql_query("",$campos,"rh166_sequencial","");
        }
        $repassa = array();
        if(isset($chave_rh166_sequencial)){
          $repassa = array("chave_rh166_sequencial"=>$chave_rh166_sequencial,"chave_rh166_descricao"=>$chave_rh166_descricao);
        }
        // dieSql($sql, false);
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clsituacaoafastamento->sql_record($clsituacaoafastamento->sql_query($pesquisa_chave));
          if($clsituacaoafastamento->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh166_descricao',false);</script>";
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
    function js_limpar_iframe_situacaoafastamento(buttonNode) {

      if(buttonNode.parentNode.tagName.toLowerCase() == 'form') {
        var form = buttonNode.parentNode;
        form.chave_rh166_sequencial.value = '';
        form.chave_rh166_descricao.value  = '';
        form.submit();
      }
    }
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_rh166_sequencial",true,1,"chave_rh166_sequencial",true);
</script>
