<?
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
include("classes/db_criterioatividadeimpacto_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcriterioatividadeimpacto = new cl_criterioatividadeimpacto;
$clcriterioatividadeimpacto->rotulo->label("am01_sequencial");
$clcriterioatividadeimpacto->rotulo->label("am01_descricao");
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
          <td><label><?=$Lam01_sequencial?></label></td>
          <td><? db_input("am01_sequencial",10,$Iam01_sequencial,true,"text",4,"","chave_am01_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lam01_descricao?></label></td>
          <td><? db_input("am01_descricao",10,$Iam01_descricao,true,"text",4,"","chave_am01_descricao");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_criterioatividadeimpacto.hide();">
  </form>
      <?
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_criterioatividadeimpacto.php")==true){
             include("funcoes/db_func_criterioatividadeimpacto.php");
           }else{
           $campos = "criterioatividadeimpacto.*";
           }
        }
        if(isset($chave_am01_sequencial) && (trim($chave_am01_sequencial)!="") ){
	         $sql = $clcriterioatividadeimpacto->sql_query($chave_am01_sequencial,$campos,"am01_sequencial");
        }else if(isset($chave_am01_descricao) && (trim($chave_am01_descricao)!="") ){
	         $sql = $clcriterioatividadeimpacto->sql_query("",$campos,"am01_descricao"," am01_descricao like '$chave_am01_descricao%' ");
        }else{
           $sql = $clcriterioatividadeimpacto->sql_query("",$campos,"am01_sequencial","");
        }
        $repassa = array();
        if(isset($chave_am01_descricao)){
          $repassa = array("chave_am01_sequencial"=>$chave_am01_sequencial,"chave_am01_descricao"=>$chave_am01_descricao);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcriterioatividadeimpacto->sql_record($clcriterioatividadeimpacto->sql_query($pesquisa_chave));
          if($clcriterioatividadeimpacto->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$am01_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_am01_descricao",true,1,"chave_am01_descricao",true);
</script>
