<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_emissaogeralinscricao_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clemissaogeralinscricao = new cl_emissaogeralinscricao;
$clemissaogeralinscricao->rotulo->label("tr04_sequencial");
$clemissaogeralinscricao->rotulo->label("tr04_inscr");
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
          <td><label><?=$Ltr04_sequencial?></label></td>
          <td><? db_input("tr04_sequencial",10,$Itr04_sequencial,true,"text",4,"","chave_tr04_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ltr04_inscr?></label></td>
          <td><? db_input("tr04_inscr",10,$Itr04_inscr,true,"text",4,"","chave_tr04_inscr");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_emissaogeralinscricao.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_emissaogeralinscricao.php")==true){
             include(modification("funcoes/db_func_emissaogeralinscricao.php"));
           }else{
           $campos = "emissaogeralinscricao.*";
           }
        }
        if(isset($chave_tr04_sequencial) && (trim($chave_tr04_sequencial)!="") ){
	         $sql = $clemissaogeralinscricao->sql_query($chave_tr04_sequencial,$campos,"tr04_sequencial");
        }else if(isset($chave_tr04_inscr) && (trim($chave_tr04_inscr)!="") ){
	         $sql = $clemissaogeralinscricao->sql_query("",$campos,"tr04_inscr"," tr04_inscr like '$chave_tr04_inscr%' ");
        }else{
           $sql = $clemissaogeralinscricao->sql_query("",$campos,"tr04_sequencial","");
        }
        $repassa = array();
        if(isset($chave_tr04_inscr)){
          $repassa = array("chave_tr04_sequencial"=>$chave_tr04_sequencial,"chave_tr04_inscr"=>$chave_tr04_inscr);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clemissaogeralinscricao->sql_record($clemissaogeralinscricao->sql_query($pesquisa_chave));
          if($clemissaogeralinscricao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tr04_inscr',false);</script>";
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
js_tabulacaoforms("form2","chave_tr04_inscr",true,1,"chave_tr04_inscr",true);
</script>
