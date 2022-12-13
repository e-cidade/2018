<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBseller Servicos de Informatica
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

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clissarquivoretencao = new cl_issarquivoretencao;
$clissarquivoretencao->rotulo->label("q90_sequencial");
$clissarquivoretencao->rotulo->label("q90_numeroremessa");
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
          <td><label for="chave_q90_sequencial"><?=$Lq90_sequencial?></label></td>
          <td><?php db_input("q90_sequencial",10,$Iq90_sequencial,true,"text",4,"","chave_q90_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label for="chave_q90_numeroremessa"><?=$Lq90_numeroremessa?></label></td>
          <td><?php db_input("q90_numeroremessa",10,$Iq90_numeroremessa,true,"text",4,"","chave_q90_numeroremessa");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_issarquivoretencao.hide();">
  </form>
      <?

      if (!isset($lProcessados)) {
        $lProcessados = false;
      }

      $sMetodoQuery = "sql_query";

      if ($lProcessados == 'false' || $lProcessados == false) {
        $sMetodoQuery = "sql_query_nao_processado";
      }

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_issarquivoretencao.php")==true){
             include("funcoes/db_func_issarquivoretencao.php");
           }else{
           $campos = "issarquivoretencao.*";
           }
        }

        if(isset($chave_q90_sequencial) && (trim($chave_q90_sequencial)!="") ){
	         $sql = $clissarquivoretencao->$sMetodoQuery($chave_q90_sequencial,$campos,"q90_sequencial");
        }else if(isset($chave_q90_numeroremessa) && (trim($chave_q90_numeroremessa)!="") ){
	         $sql = $clissarquivoretencao->$sMetodoQuery("",$campos,"q90_numeroremessa"," q90_numeroremessa like '$chave_q90_numeroremessa%' ");
        }else{
           $sql = $clissarquivoretencao->$sMetodoQuery("",$campos,"q90_sequencial","");
        }
        $repassa = array();
        if(isset($chave_q90_numeroremessa)){
          $repassa = array("chave_q90_sequencial"=>$chave_q90_sequencial,"chave_q90_numeroremessa"=>$chave_q90_numeroremessa);
        }

        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clissarquivoretencao->sql_record($clissarquivoretencao->$sMetodoQuery($pesquisa_chave));
          if($clissarquivoretencao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$q90_numeroremessa',false);</script>";
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
js_tabulacaoforms("form2","chave_q90_numeroremessa",true,1,"chave_q90_numeroremessa",true);
</script>
