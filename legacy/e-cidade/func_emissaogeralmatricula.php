<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_emissaogeralmatricula_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clemissaogeralmatricula = new cl_emissaogeralmatricula;
$clemissaogeralmatricula->rotulo->label("tr03_sequencial");
$clemissaogeralmatricula->rotulo->label("tr03_matric");
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
          <td><label><?=$Ltr03_sequencial?></label></td>
          <td><? db_input("tr03_sequencial",10,$Itr03_sequencial,true,"text",4,"","chave_tr03_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ltr03_matric?></label></td>
          <td><? db_input("tr03_matric",10,$Itr03_matric,true,"text",4,"","chave_tr03_matric");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_emissaogeralmatricula.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_emissaogeralmatricula.php")==true){
             include(modification("funcoes/db_func_emissaogeralmatricula.php"));
           }else{
           $campos = "emissaogeralmatricula.*";
           }
        }
        if(isset($chave_tr03_sequencial) && (trim($chave_tr03_sequencial)!="") ){
	         $sql = $clemissaogeralmatricula->sql_query($chave_tr03_sequencial,$campos,"tr03_sequencial");
        }else if(isset($chave_tr03_matric) && (trim($chave_tr03_matric)!="") ){
	         $sql = $clemissaogeralmatricula->sql_query("",$campos,"tr03_matric"," tr03_matric like '$chave_tr03_matric%' ");
        }else{
           $sql = $clemissaogeralmatricula->sql_query("",$campos,"tr03_sequencial","");
        }
        $repassa = array();
        if(isset($chave_tr03_matric)){
          $repassa = array("chave_tr03_sequencial"=>$chave_tr03_sequencial,"chave_tr03_matric"=>$chave_tr03_matric);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clemissaogeralmatricula->sql_record($clemissaogeralmatricula->sql_query($pesquisa_chave));
          if($clemissaogeralmatricula->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tr03_matric',false);</script>";
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
js_tabulacaoforms("form2","chave_tr03_matric",true,1,"chave_tr03_matric",true);
</script>
