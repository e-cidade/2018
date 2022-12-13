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
require_once("classes/db_zonassetorvalor_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clzonassetorvalor = new cl_zonassetorvalor;
$clzonassetorvalor->rotulo->label("j141_anousu");
$clzonassetorvalor->rotulo->label("j141_zonas");
$clzonassetorvalor->rotulo->label("j141_setor");
$clzonassetorvalor->rotulo->label("j141_sequencial");
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
          <td><label><?=$Lj141_zonas?></label></td>
          <td><? db_input("j141_zonas",10,$Ij141_zonas,true,"text",4,"","chave_j141_zonas"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lj141_setor?></label></td>
          <td><? db_input("j141_setor",4,$Ij141_setor,true,"text",4,"","chave_j141_setor"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lj141_sequencial?></label></td>
          <td><? db_input("j141_sequencial",4,$Ij141_sequencial,true,"text",4,"","chave_j141_sequencial");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_zonassetorvalor.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_zonassetorvalor.php")==true){
             include("funcoes/db_func_zonassetorvalor.php");
           }else{
           $campos = "zonassetorvalor.*";
           }
        }
        if(isset($chave_j141_zonas) && (trim($chave_j141_zonas)!="") ){
	         $sql = $clzonassetorvalor->sql_query(db_getsession('DB_anousu'),$chave_j141_zonas,$chave_j141_setor,$campos,"j141_zonas");
        }else if(isset($chave_j141_sequencial) && (trim($chave_j141_sequencial)!="") ){
	         $sql = $clzonassetorvalor->sql_query(db_getsession('DB_anousu'),"","",$campos,"j141_sequencial"," j141_sequencial like '$chave_j141_sequencial%' ");
        }else{
           $sql = $clzonassetorvalor->sql_query(db_getsession('DB_anousu'),"","",$campos,"j141_anousu#j141_zonas#j141_setor","");
        }
        $repassa = array();
        if(isset($chave_j141_sequencial)){
          $repassa = array("chave_j141_anousu"=>$chave_j141_anousu,"chave_j141_sequencial"=>$chave_j141_sequencial);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clzonassetorvalor->sql_record($clzonassetorvalor->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clzonassetorvalor->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j141_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_j141_sequencial",true,1,"chave_j141_sequencial",true);
</script>