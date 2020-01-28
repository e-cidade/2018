<?
require(modification("libs/db_stdlib.php"));
//dbhack
require(modification("libs/db_cone" . "cta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_iptucalcconfrec_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cliptucalcconfrec = new cl_iptucalcconfrec;
$cliptucalcconfrec->rotulo->label("j23_sequencial");
$cliptucalcconfrec->rotulo->label("j23_matric");
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
          <td><label><?=$Lj23_sequencial?></label></td>
          <td><? db_input("j23_sequencial",10,$Ij23_sequencial,true,"text",4,"","chave_j23_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lj23_matric?></label></td>
          <td><? db_input("j23_matric",10,$Ij23_matric,true,"text",4,"","chave_j23_matric");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_iptucalcconfrec.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_iptucalcconfrec.php")==true){
             include(modification("funcoes/db_func_iptucalcconfrec.php"));
           }else{
           $campos = "iptucalcconfrec.*";
           }
        }
        if(isset($chave_j23_sequencial) && (trim($chave_j23_sequencial)!="") ){
	         $sql = $cliptucalcconfrec->sql_query($chave_j23_sequencial,$campos,"j23_sequencial");
        }else if(isset($chave_j23_matric) && (trim($chave_j23_matric)!="") ){
	         $sql = $cliptucalcconfrec->sql_query("",$campos,"j23_matric"," j23_matric like '$chave_j23_matric%' ");
        }else{
           $sql = $cliptucalcconfrec->sql_query("",$campos,"j23_sequencial","");
        }
        $repassa = array();
        if(isset($chave_j23_matric)){
          $repassa = array("chave_j23_sequencial"=>$chave_j23_sequencial,"chave_j23_matric"=>$chave_j23_matric);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cliptucalcconfrec->sql_record($cliptucalcconfrec->sql_query($pesquisa_chave));
          if($cliptucalcconfrec->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j23_matric',false);</script>";
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
js_tabulacaoforms("form2","chave_j23_matric",true,1,"chave_j23_matric",true);
</script>
