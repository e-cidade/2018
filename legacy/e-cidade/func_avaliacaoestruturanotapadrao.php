<?php
require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("classes/db_avaliacaoestruturanotapadrao_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$clavaliacaoestruturanotapadrao = new cl_avaliacaoestruturanotapadrao;
$clavaliacaoestruturanotapadrao->rotulo->label("ed139_sequencial");
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
          <td><label for='ed139_sequencial'><?=$Led139_sequencial?></label></td>
          <td><? db_input("ed139_sequencial",10,$Ied139_sequencial,true,"text",4,"","chave_ed139_sequencial"); ?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_avaliacaoestruturanotapadrao.hide();">
  </form>
  <?php

    $sCampos  = " ed139_sequencial, ed139_db_estrutura as db_ed139_db_estrutura, ";
    $sCampos .= " db77_descr as dl_estrutural, ed139_ativo, ed139_arredondamedia, ";
    $sCampos .= " ed139_regraarredondamento as db_ed139_regraarredondamento, ed316_descricao as dl_regra_de_arredondamento, ";
    $sCampos .= " replace(ed139_observacao, '\n', '[#]') as db_ed139_observacao, ed139_ano";

    if (!isset($pesquisa_chave)) {

      $sWhere = '';
      if(isset($chave_ed139_sequencial) && (trim($chave_ed139_sequencial)!="") ){
        $sWhere = "ed139_sequencial = {$chave_ed139_sequencial} ";
      }
      $sql = $clavaliacaoestruturanotapadrao->sql_query("", $sCampos, "ed139_ano", $sWhere );
      $repassa = array();
      if(isset($chave_ed139_sequencial)){
        $repassa = array("chave_ed139_sequencial"=>$chave_ed139_sequencial);
      }
      echo '<div class="container">';
      echo '  <fieldset>';
      echo '    <legend>Resultado da Pesquisa</legend>';
        db_lovrot($sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa, true);
      echo '  </fieldset>';
      echo '</div>';
    } else {

      if ($pesquisa_chave!=null && $pesquisa_chave!="") {

        $result = $clavaliacaoestruturanotapadrao->sql_record($clavaliacaoestruturanotapadrao->sql_query($pesquisa_chave));
        if($clavaliacaoestruturanotapadrao->numrows!=0){

          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$ed139_sequencial',false);</script>";
        } else {
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      } else {
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
js_tabulacaoforms("form2","chave_ed139_sequencial",true,1,"chave_ed139_sequencial",true);
</script>
