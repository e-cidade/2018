<?
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_unidadegestora_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clunidadegestora = new cl_unidadegestora;
$clunidadegestora->rotulo->label("k171_sequencial");
$clunidadegestora->rotulo->label("k171_nome");
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
          <td><label><?=$Lk171_sequencial?></label></td>
          <td><? db_input("k171_sequencial",10,$Ik171_sequencial,true,"text",4,"","chave_k171_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lk171_nome?></label></td>
          <td><? db_input("k171_nome",10,$Ik171_nome,true,"text",4,"","chave_k171_nome");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_unidadegestora.hide();">
  </form>
      <?php
      
      $aWhere = array('instit = '.db_getsession("DB_instit"));
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_unidadegestora.php")==true){
             include(modification("funcoes/db_func_unidadegestora.php"));
           }else{
           $campos = "unidadegestora.*";
           }
        }
        if (isset($chave_k171_sequencial) && (trim($chave_k171_sequencial)!="") ){
	         $aWhere[] = "k171_sequencial = {$chave_k171_sequencial} ";
        }
        if (isset($chave_k171_nome) && (trim($chave_k171_nome)!="")){          
          $aWhere[] = "k171_nome ilike '%{$chave_k171_sequencial}%'";
         }
        
        $sql = $clunidadegestora->sql_query("",$campos, "k171_nome", implode(" and ", $aWhere));        
        $repassa = array();
        if(isset($chave_k171_nome)){
          $repassa = array("chave_k171_sequencial"=>$chave_k171_sequencial,"chave_k171_nome"=>$chave_k171_nome);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clunidadegestora->sql_record($clunidadegestora->sql_query($pesquisa_chave));
          if($clunidadegestora->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k171_nome',false);</script>";
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
js_tabulacaoforms("form2","chave_k171_nome",true,1,"chave_k171_nome",true);
</script>
