<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_importacaoplanoconta_classe.php"));
db_postmemory($_POST);
$oGet = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$climportacaoplanoconta = new cl_importacaoplanoconta;
$climportacaoplanoconta->rotulo->label("c96_sequencial");
$climportacaoplanoconta->rotulo->label("c96_data");
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
          <td><label><?=$Lc96_sequencial?></label></td>
          <td><? db_input("c96_sequencial",10,$Ic96_sequencial,true,"text",4,"","chave_c96_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lc96_data?></label></td>
          <td><? db_input("c96_data",10,$Ic96_data,true,"text",4,"","chave_c96_data");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_importacaoplanoconta.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){

        $aCampos = array(
          "c96_sequencial as dl_Código",
          "c96_data as dl_Data_de_Importação",
          "c94_nome as dl_Modelo",
          "c94_exercicio as dl_Exercício",
        );
        $campos = implode(',', $aCampos);
        if (!empty($oPost->chave_c96_sequencial)) {
	         $sql = $climportacaoplanoconta->sql_query($oPost->chave_c96_sequencial,$campos,"c96_sequencial");
        } else if (!empty($oPost->chave_c96_data)) {
	         $sql = $climportacaoplanoconta->sql_query("",$campos,"c96_data"," c96_data like '$oPost->chave_c96_data%' ");
        }else{
           $sql = $climportacaoplanoconta->sql_query("",$campos,"c96_sequencial","");
        }
        $repassa = array();
        if(isset($chave_c96_data)){
          $repassa = array("chave_c96_sequencial"=>$oPost->chave_c96_sequencial,"chave_c96_data"=>$oPost->chave_c96_data);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$oGet->funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';

      }else{

        if (!empty($oGet->pesquisa_chave)) {
          $result = $climportacaoplanoconta->sql_record($climportacaoplanoconta->sql_query($oGet->pesquisa_chave));
          if($climportacaoplanoconta->numrows!=0){
            $oStdDadosConsulta = db_utils::fieldsMemory($result, 0);
            echo "<script>".$oGet->funcao_js."('$oStdDadosConsulta->c94_nome', $oStdDadosConsulta->c94_exercicio,false);</script>";
          }else{
	         echo "<script>".$oGet->funcao_js."('Chave(".$oGet->pesquisa_chave.") não Encontrado',true);</script>";
          }
        }else{
	       echo "<script>".$oGet->funcao_js."('', false);</script>";
        }
      }
      ?>
</body>
</html>
<?
if(!isset($oGet->pesquisa_chave)){
  ?>
  <script>
  </script>
  <?
}
?>
<script>
js_tabulacaoforms("form2","chave_c96_data",true,1,"chave_c96_data",true);
</script>
