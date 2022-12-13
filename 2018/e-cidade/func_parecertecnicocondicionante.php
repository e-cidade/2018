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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_parecertecnicocondicionante_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clparecertecnicocondicionante = new cl_parecertecnicocondicionante;
$clparecertecnicocondicionante->rotulo->label("am12_sequencial");
$clparecertecnicocondicionante->rotulo->label("am12_arquivo");
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
          <td><label><?=$Lam12_sequencial?></label></td>
          <td><? db_input("am12_sequencial",10,$Iam12_sequencial,true,"text",4,"","chave_am12_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lam12_arquivo?></label></td>
          <td><? db_input("am12_arquivo",10,$Iam12_arquivo,true,"text",4,"","chave_am12_arquivo");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_parecertecnicocondicionante.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_parecertecnicocondicionante.php")==true){
             include("funcoes/db_func_parecertecnicocondicionante.php");
           }else{
           $campos = "parecertecnicocondicionante.*";
           }
        }
        if(isset($chave_am12_sequencial) && (trim($chave_am12_sequencial)!="") ){
	         $sql = $clparecertecnicocondicionante->sql_query($chave_am12_sequencial,$campos,"am12_sequencial");
        }else if(isset($chave_am12_arquivo) && (trim($chave_am12_arquivo)!="") ){
	         $sql = $clparecertecnicocondicionante->sql_query("",$campos,"am12_arquivo"," am12_arquivo like '$chave_am12_arquivo%' ");
        }else{
           $sql = $clparecertecnicocondicionante->sql_query("",$campos,"am12_sequencial","");
        }
        $repassa = array();
        if(isset($chave_am12_arquivo)){
          $repassa = array("chave_am12_sequencial"=>$chave_am12_sequencial,"chave_am12_arquivo"=>$chave_am12_arquivo);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clparecertecnicocondicionante->sql_record($clparecertecnicocondicionante->sql_query($pesquisa_chave));
          if($clparecertecnicocondicionante->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$am12_arquivo',false);</script>";
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
js_tabulacaoforms("form2","chave_am12_arquivo",true,1,"chave_am12_arquivo",true);
</script>
