<?
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhfundamentacaolegal_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhfundamentacaolegal = new cl_rhfundamentacaolegal;
$clrhfundamentacaolegal->rotulo->label("rh137_sequencial");
$clrhfundamentacaolegal->rotulo->label("rh137_numero");
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
          <td><label><?=$Lrh137_sequencial?></label></td>
          <td><? db_input("rh137_sequencial",11,$Irh137_sequencial,true,"text",4,"","chave_rh137_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lrh137_numero?></label></td>
          <td><? db_input("rh137_numero",11,$Irh137_numero,true,"text",4,"","chave_rh137_numero");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhfundamentacaolegal.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhfundamentacaolegal.php")==true){
             include("funcoes/db_func_rhfundamentacaolegal.php");
           }else{
           $campos = "rhfundamentacaolegal.*";
           }
        }
        $sWhere = ' rhfundamentacaolegal.rh137_instituicao = ' .db_getsession("DB_instit");
        if(isset($chave_rh137_sequencial) && (trim($chave_rh137_sequencial)!="") ){
	         $sql = $clrhfundamentacaolegal->sql_query($chave_rh137_sequencial,$campos,"rh137_sequencial");
        }else if(isset($chave_rh137_numero) && (trim($chave_rh137_numero)!="") ){
	         $sql = $clrhfundamentacaolegal->sql_query("",$campos,"rh137_numero"," rh137_numero like '$chave_rh137_numero%' AND $sWhere");
        }else{
           $sql = $clrhfundamentacaolegal->sql_query("",$campos,"rh137_sequencial","$sWhere");
        }
        $repassa = array();
        if(isset($chave_rh137_numero)){
          $repassa = array("chave_rh137_sequencial"=>$chave_rh137_sequencial,"chave_rh137_numero"=>$chave_rh137_numero);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){


          $iInstituicao = db_getsession('DB_instit');
          $sWhere       = "rhfundamentacaolegal.rh137_sequencial  = {$pesquisa_chave}
                       AND rhfundamentacaolegal.rh137_instituicao = {$iInstituicao}";
          $sSql         = $clrhfundamentacaolegal->sql_query(null, '*', null, $sWhere);

          $result = $clrhfundamentacaolegal->sql_record($sSql);

          if($clrhfundamentacaolegal->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh137_numero', '$rh137_descricao', false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado', null, true);</script>";
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
js_tabulacaoforms("form2","chave_rh137_numero",true,1,"chave_rh137_numero",true);
</script>
