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
require_once("classes/db_issarquivoretencaoregistroissvar_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clissarquivoretencaoregistroissvar = new cl_issarquivoretencaoregistroissvar;
$clissarquivoretencaoregistroissvar->rotulo->label("q146_sequencial");
$clissarquivoretencaoregistroissvar->rotulo->label("q146_sequencial");
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
          <td><label><?=$Lq146_sequencial?></label></td>
          <td><? db_input("q146_sequencial",10,$Iq146_sequencial,true,"text",4,"","chave_q146_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lq146_sequencial?></label></td>
          <td><? db_input("q146_sequencial",10,$Iq146_sequencial,true,"text",4,"","chave_q146_sequencial");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_issarquivoretencaoregistroissvar.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_issarquivoretencaoregistroissvar.php")==true){
             include("funcoes/db_func_issarquivoretencaoregistroissvar.php");
           }else{
           $campos = "issarquivoretencaoregistroissvar.*";
           }
        }
        if(isset($chave_q146_sequencial) && (trim($chave_q146_sequencial)!="") ){
	         $sql = $clissarquivoretencaoregistroissvar->sql_query($chave_q146_sequencial,$campos,"q146_sequencial");
        }else if(isset($chave_q146_sequencial) && (trim($chave_q146_sequencial)!="") ){
	         $sql = $clissarquivoretencaoregistroissvar->sql_query("",$campos,"q146_sequencial"," q146_sequencial like '$chave_q146_sequencial%' ");
        }else{
           $sql = $clissarquivoretencaoregistroissvar->sql_query("",$campos,"q146_sequencial","");
        }
        $repassa = array();
        if(isset($chave_q146_sequencial)){
          $repassa = array("chave_q146_sequencial"=>$chave_q146_sequencial,"chave_q146_sequencial"=>$chave_q146_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clissarquivoretencaoregistroissvar->sql_record($clissarquivoretencaoregistroissvar->sql_query($pesquisa_chave));
          if($clissarquivoretencaoregistroissvar->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$q146_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_q146_sequencial",true,1,"chave_q146_sequencial",true);
</script>
