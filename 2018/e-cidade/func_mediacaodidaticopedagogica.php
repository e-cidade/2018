<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_mediacaodidaticopedagogica_classe.php");
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clmediacaodidaticopedagogica = new cl_mediacaodidaticopedagogica;
$clmediacaodidaticopedagogica->rotulo->label("ed130_codigo");
$clmediacaodidaticopedagogica->rotulo->label("ed130_descricao");
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
          <td><label><?=$Led130_codigo?></label></td>
          <td><? db_input("ed130_codigo",10,$Ied130_codigo,true,"text",4,"","chave_ed130_codigo"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Led130_descricao?></label></td>
          <td><? db_input("ed130_descricao",10,$Ied130_descricao,true,"text",4,"","chave_ed130_descricao");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_mediacaodidaticopedagogica.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_mediacaodidaticopedagogica.php")==true){
             include("funcoes/db_func_mediacaodidaticopedagogica.php");
           }else{
           $campos = "mediacaodidaticopedagogica.*";
           }
        }
        if(isset($chave_ed130_codigo) && (trim($chave_ed130_codigo)!="") ){
	         $sql = $clmediacaodidaticopedagogica->sql_query($chave_ed130_codigo,$campos,"ed130_codigo");
        }else if(isset($chave_ed130_descricao) && (trim($chave_ed130_descricao)!="") ){
	         $sql = $clmediacaodidaticopedagogica->sql_query("",$campos,"ed130_descricao"," ed130_descricao like '$chave_ed130_descricao%' ");
        }else{
           $sql = $clmediacaodidaticopedagogica->sql_query("",$campos,"ed130_codigo","");
        }
        $repassa = array();
        if(isset($chave_ed130_descricao)){
          $repassa = array("chave_ed130_codigo"=>$chave_ed130_codigo,"chave_ed130_descricao"=>$chave_ed130_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clmediacaodidaticopedagogica->sql_record($clmediacaodidaticopedagogica->sql_query($pesquisa_chave));
          if($clmediacaodidaticopedagogica->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ed130_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_ed130_descricao",true,1,"chave_ed130_descricao",true);
</script>
