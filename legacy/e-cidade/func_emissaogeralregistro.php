<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_emissaogeralregistro_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clemissaogeralregistro = new cl_emissaogeralregistro;
$clemissaogeralregistro->rotulo->label("tr02_sequencial");
$clemissaogeralregistro->rotulo->label("tr02_emissaogeral");
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
          <td><label><?=$Ltr02_sequencial?></label></td>
          <td><? db_input("tr02_sequencial",10,$Itr02_sequencial,true,"text",4,"","chave_tr02_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ltr02_emissaogeral?></label></td>
          <td><? db_input("tr02_emissaogeral",10,$Itr02_emissaogeral,true,"text",4,"","chave_tr02_emissaogeral");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_emissaogeralregistro.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_emissaogeralregistro.php")==true){
             include(modification("funcoes/db_func_emissaogeralregistro.php"));
           }else{
           $campos = "emissaogeralregistro.*";
           }
        }
        if(isset($chave_tr02_sequencial) && (trim($chave_tr02_sequencial)!="") ){
	         $sql = $clemissaogeralregistro->sql_query($chave_tr02_sequencial,$campos,"tr02_sequencial");
        }else if(isset($chave_tr02_emissaogeral) && (trim($chave_tr02_emissaogeral)!="") ){
	         $sql = $clemissaogeralregistro->sql_query("",$campos,"tr02_emissaogeral"," tr02_emissaogeral like '$chave_tr02_emissaogeral%' ");
        }else{
           $sql = $clemissaogeralregistro->sql_query("",$campos,"tr02_sequencial","");
        }
        $repassa = array();
        if(isset($chave_tr02_emissaogeral)){
          $repassa = array("chave_tr02_sequencial"=>$chave_tr02_sequencial,"chave_tr02_emissaogeral"=>$chave_tr02_emissaogeral);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clemissaogeralregistro->sql_record($clemissaogeralregistro->sql_query($pesquisa_chave));
          if($clemissaogeralregistro->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tr02_emissaogeral',false);</script>";
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
js_tabulacaoforms("form2","chave_tr02_emissaogeral",true,1,"chave_tr02_emissaogeral",true);
</script>
