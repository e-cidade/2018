<?php
/**
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
require_once("classes/db_certidcartoriorecibopaga_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clcertidcartoriorecibopaga = new cl_certidcartoriorecibopaga;
$clcertidcartoriorecibopaga->rotulo->label("v33_sequencial");
$clcertidcartoriorecibopaga->rotulo->label("v33_numnov");
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
          <td><label><?=$Lv33_sequencial?></label></td>
          <td><? db_input("v33_sequencial",10,$Iv33_sequencial,true,"text",4,"","chave_v33_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lv33_numnov?></label></td>
          <td><? db_input("v33_numnov",10,$Iv33_numnov,true,"text",4,"","chave_v33_numnov");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_certidcartoriorecibopaga.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_certidcartoriorecibopaga.php")==true){
             include("funcoes/db_func_certidcartoriorecibopaga.php");
           }else{
           $campos = "certidcartoriorecibopaga.*";
           }
        }
        if(isset($chave_v33_sequencial) && (trim($chave_v33_sequencial)!="") ){
	         $sql = $clcertidcartoriorecibopaga->sql_query($chave_v33_sequencial,$campos,"v33_sequencial");
        }else if(isset($chave_v33_numnov) && (trim($chave_v33_numnov)!="") ){
	         $sql = $clcertidcartoriorecibopaga->sql_query("",$campos,"v33_numnov"," v33_numnov like '$chave_v33_numnov%' ");
        }else{
           $sql = $clcertidcartoriorecibopaga->sql_query("",$campos,"v33_sequencial","");
        }
        $repassa = array();
        if(isset($chave_v33_numnov)){
          $repassa = array("chave_v33_sequencial"=>$chave_v33_sequencial,"chave_v33_numnov"=>$chave_v33_numnov);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcertidcartoriorecibopaga->sql_record($clcertidcartoriorecibopaga->sql_query($pesquisa_chave));
          if($clcertidcartoriorecibopaga->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v33_numnov',false);</script>";
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
js_tabulacaoforms("form2","chave_v33_numnov",true,1,"chave_v33_numnov",true);
</script>
