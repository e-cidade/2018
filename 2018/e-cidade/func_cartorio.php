<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
require_once("classes/db_cartorio_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcartorio = new cl_cartorio;
$clcartorio->rotulo->label("v82_sequencial");
$clcartorio->rotulo->label("v82_sequencial");
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
          <td><label for='v82_sequencial'><?=$Lv82_sequencial?></label></td>
          <td><? db_input("v82_sequencial",10,$Iv82_sequencial,true,"text",4,"","chave_v82_sequencial"); ?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cartorio.hide();">
  </form>
      <?

      $sWhere = "v82_extrajudicial = false";

      if (isset($v82_extrajudicial)) {
        $sWhere = "v82_extrajudicial = '$v82_extrajudicial'";
      }

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_cartorio.php") == true){
             include("funcoes/db_func_cartorio.php");
           }else{
           $campos = "cartorio.*";
           }
        }
        if(isset($chave_v82_sequencial) && (trim($chave_v82_sequencial)!="") ){
	         $sql = $clcartorio->sql_query("",$campos,"v82_sequencial", "v82_sequencial = $chave_v82_sequencial and $sWhere");
        }else if(isset($chave_v82_sequencial) && (trim($chave_v82_sequencial)!="") ){
	         $sql = $clcartorio->sql_query("",$campos,"v82_sequencial"," v82_sequencial like '$chave_v82_sequencial%' and $sWhere");
        }else{
           $sql = $clcartorio->sql_query("",$campos,"v82_sequencial",$sWhere);
        }
        $repassa = array();
        if(isset($chave_v82_sequencial)){
          $repassa = array("chave_v82_sequencial"=>$chave_v82_sequencial,"chave_v82_sequencial"=>$chave_v82_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $result = $clcartorio->sql_record($clcartorio->sql_query("", "*", "", "$sWhere and v82_sequencial = $pesquisa_chave"));

          if ($clcartorio->numrows!=0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v82_sequencial','$v82_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_v82_sequencial",true,1,"chave_v82_sequencial",true);
</script>
