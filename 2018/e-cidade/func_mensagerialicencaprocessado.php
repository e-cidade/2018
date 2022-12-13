<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_mensagerialicencaprocessado_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmensagerialicencaprocessado = new cl_mensagerialicencaprocessado;
$clmensagerialicencaprocessado->rotulo->label("am15_sequencial");
$clmensagerialicencaprocessado->rotulo->label("am15_licencaempreendimento");
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
          <td><label><?=$Lam15_sequencial?></label></td>
          <td><? db_input("am15_sequencial",10,$Iam15_sequencial,true,"text",4,"","chave_am15_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lam15_licencaempreendimento?></label></td>
          <td><? db_input("am15_licencaempreendimento",10,$Iam15_licencaempreendimento,true,"text",4,"","chave_am15_licencaempreendimento");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_mensagerialicencaprocessado.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_mensagerialicencaprocessado.php")==true){
             include("funcoes/db_func_mensagerialicencaprocessado.php");
           }else{
           $campos = "mensagerialicencaprocessado.*";
           }
        }
        if(isset($chave_am15_sequencial) && (trim($chave_am15_sequencial)!="") ){
	         $sql = $clmensagerialicencaprocessado->sql_query($chave_am15_sequencial,$campos,"am15_sequencial");
        }else if(isset($chave_am15_licencaempreendimento) && (trim($chave_am15_licencaempreendimento)!="") ){
	         $sql = $clmensagerialicencaprocessado->sql_query("",$campos,"am15_licencaempreendimento"," am15_licencaempreendimento like '$chave_am15_licencaempreendimento%' ");
        }else{
           $sql = $clmensagerialicencaprocessado->sql_query("",$campos,"am15_sequencial","");
        }
        $repassa = array();
        if(isset($chave_am15_licencaempreendimento)){
          $repassa = array("chave_am15_sequencial"=>$chave_am15_sequencial,"chave_am15_licencaempreendimento"=>$chave_am15_licencaempreendimento);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmensagerialicencaprocessado->sql_record($clmensagerialicencaprocessado->sql_query($pesquisa_chave));
          if($clmensagerialicencaprocessado->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$am15_licencaempreendimento',false);</script>";
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
js_tabulacaoforms("form2","chave_am15_licencaempreendimento",true,1,"chave_am15_licencaempreendimento",true);
</script>
