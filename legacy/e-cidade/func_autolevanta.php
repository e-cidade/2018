<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_autolevanta_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clautolevanta = new cl_autolevanta;
$clautolevanta->rotulo->label("y117_sequencial");
$clautolevanta->rotulo->label("y117_levanta");
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
          <td><label><?php echo $Ly117_sequencial;?></label></td>
          <td><?php db_input("y117_sequencial",10,$Iy117_sequencial,true,"text",4,"","chave_y117_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?php echo $Ly117_levanta;?></label></td>
          <td><?php db_input("y117_levanta",10,$Iy117_levanta,true,"text",4,"","chave_y117_levanta");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_autolevanta.hide();">
  </form>
      <?php
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_autolevanta.php")==true){
             include("funcoes/db_func_autolevanta.php");
           }else{
           $campos = "autolevanta.*";
           }
        }
        if(isset($chave_y117_sequencial) && (trim($chave_y117_sequencial)!="") ){
	         $sql = $clautolevanta->sql_query($chave_y117_sequencial,$campos,"y117_sequencial");
        }else if(isset($chave_y117_levanta) && (trim($chave_y117_levanta)!="") ){
	         $sql = $clautolevanta->sql_query("",$campos,"y117_levanta"," y117_levanta like '$chave_y117_levanta%' ");
        }else{
           $sql = $clautolevanta->sql_query("",$campos,"y117_sequencial","");
        }
        $repassa = array();
        if(isset($chave_y117_levanta)){
          $repassa = array("chave_y117_sequencial"=>$chave_y117_sequencial,"chave_y117_levanta"=>$chave_y117_levanta);
        }

        if (!isset($lovrot)) {

          echo '<div class="container">';
          echo '  <fieldset>';
          echo '    <legend>Resultado da Pesquisa</legend>';
            db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
          echo '  </fieldset>';
          echo '</div>';
        } else {
          $sql    = $clautolevanta->sql_query_file(null,"*",null, " y117_levanta = $y117_levanta ");
          $result = $clautolevanta->sql_record($sql);

          if ($clautolevanta->numrows!=0) {
            echo "<script>".$funcao_js."(true);</script>";
          } else {
            echo "<script>".$funcao_js."(false);</script>";
          }
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clautolevanta->sql_record($clautolevanta->sql_query($pesquisa_chave));
          if($clautolevanta->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$y117_levanta',false);</script>";
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
<?php
if(!isset($pesquisa_chave)){
  ?>
  <script>
  </script>
  <?php
}
?>
<script>
js_tabulacaoforms("form2","chave_y117_levanta",true,1,"chave_y117_levanta",true);
</script>
