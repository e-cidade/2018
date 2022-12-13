<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_emissaogeralparcelaunica_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clemissaogeralparcelaunica = new cl_emissaogeralparcelaunica;
$clemissaogeralparcelaunica->rotulo->label("tr05_sequencial");
$clemissaogeralparcelaunica->rotulo->label("tr05_sequencial");
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
          <td><label><?=$Ltr05_sequencial?></label></td>
          <td><? db_input("tr05_sequencial",10,$Itr05_sequencial,true,"text",4,"","chave_tr05_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Ltr05_sequencial?></label></td>
          <td><? db_input("tr05_sequencial",10,$Itr05_sequencial,true,"text",4,"","chave_tr05_sequencial");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_emissaogeralparcelaunica.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_emissaogeralparcelaunica.php")==true){
             include(modification("funcoes/db_func_emissaogeralparcelaunica.php"));
           }else{
           $campos = "emissaogeralparcelaunica.*";
           }
        }
        if(isset($chave_tr05_sequencial) && (trim($chave_tr05_sequencial)!="") ){
	         $sql = $clemissaogeralparcelaunica->sql_query($chave_tr05_sequencial,$campos,"tr05_sequencial");
        }else if(isset($chave_tr05_sequencial) && (trim($chave_tr05_sequencial)!="") ){
	         $sql = $clemissaogeralparcelaunica->sql_query("",$campos,"tr05_sequencial"," tr05_sequencial like '$chave_tr05_sequencial%' ");
        }else{
           $sql = $clemissaogeralparcelaunica->sql_query("",$campos,"tr05_sequencial","");
        }
        $repassa = array();
        if(isset($chave_tr05_sequencial)){
          $repassa = array("chave_tr05_sequencial"=>$chave_tr05_sequencial,"chave_tr05_sequencial"=>$chave_tr05_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clemissaogeralparcelaunica->sql_record($clemissaogeralparcelaunica->sql_query($pesquisa_chave));
          if($clemissaogeralparcelaunica->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$tr05_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_tr05_sequencial",true,1,"chave_tr05_sequencial",true);
</script>
