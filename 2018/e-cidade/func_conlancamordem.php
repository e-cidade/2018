<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_conlancamordem_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clconlancamordem = new cl_conlancamordem;
$clconlancamordem->rotulo->label("c03_sequencial");
$clconlancamordem->rotulo->label("c03_codlan");
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
          <td><label><?=$Lc03_sequencial?></label></td>
          <td><? db_input("c03_sequencial",10,$Ic03_sequencial,true,"text",4,"","chave_c03_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lc03_codlan?></label></td>
          <td><? db_input("c03_codlan",10,$Ic03_codlan,true,"text",4,"","chave_c03_codlan");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_conlancamordem.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_conlancamordem.php")==true){
             include("funcoes/db_func_conlancamordem.php");
           }else{
           $campos = "conlancamordem.*";
           }
        }
        if(isset($chave_c03_sequencial) && (trim($chave_c03_sequencial)!="") ){
	         $sql = $clconlancamordem->sql_query($chave_c03_sequencial,$campos,"c03_sequencial");
        }else if(isset($chave_c03_codlan) && (trim($chave_c03_codlan)!="") ){
	         $sql = $clconlancamordem->sql_query("",$campos,"c03_codlan"," c03_codlan like '$chave_c03_codlan%' ");
        }else{
           $sql = $clconlancamordem->sql_query("",$campos,"c03_sequencial","");
        }
        $repassa = array();
        if(isset($chave_c03_codlan)){
          $repassa = array("chave_c03_sequencial"=>$chave_c03_sequencial,"chave_c03_codlan"=>$chave_c03_codlan);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clconlancamordem->sql_record($clconlancamordem->sql_query($pesquisa_chave));
          if($clconlancamordem->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$c03_codlan',false);</script>";
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
js_tabulacaoforms("form2","chave_c03_codlan",true,1,"chave_c03_codlan",true);
</script>
