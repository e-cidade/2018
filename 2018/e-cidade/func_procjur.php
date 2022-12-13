<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBSeller Servicos de Informatica
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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_procjur_classe.php"));
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clprocjur = new cl_procjur;
$clprocjur->rotulo->label("v62_sequencial");
$clprocjur->rotulo->label("v62_procjurtipo");
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
          <td><label><?=$Lv62_sequencial?></label></td>
          <td><? db_input("v62_sequencial",10,$Iv62_sequencial,true,"text",4,"","chave_v62_sequencial"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lv62_procjurtipo?></label></td>
          <td><? db_input("v62_procjurtipo",10,$Iv62_procjurtipo,true,"text",4,"","chave_v62_procjurtipo");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_procjur.hide();">
  </form>
      <?
      $sWhereInstit = "v62_instit = ". db_getsession("DB_instit");

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_procjur.php")==true){
             include(modification("funcoes/db_func_procjur.php"));
           }else{
             $campos = "procjur.*";
           }
        }

        if(isset($chave_v62_sequencial) && (trim($chave_v62_sequencial)!="") ){

           $sWhereInstit .= " AND v62_sequencial = $chave_v62_sequencial";
	         $sql = $clprocjur->sql_query($chave_v62_sequencial,$campos,"v62_sequencial", $sWhereInstit);
        }else if(isset($chave_v62_procjurtipo) && (trim($chave_v62_procjurtipo)!="") ){
	         $sql = $clprocjur->sql_query("",$campos,"v62_procjurtipo"," v62_procjurtipo like '$chave_v62_procjurtipo%' AND $sWhereInstit");
        }else{
           $sql = $clprocjur->sql_query("",$campos,"v62_sequencial","$sWhereInstit");
        }
        $repassa = array();
        if(isset($chave_v62_procjurtipo)){
          $repassa = array("chave_v62_sequencial"=>$chave_v62_sequencial,"chave_v62_procjurtipo"=>$chave_v62_procjurtipo);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          if (isset($validaativ)) {
            $result = $clprocjur->sql_record($clprocjur->sql_query(null,"*",null," v62_sequencial = {$pesquisa_chave} and v62_situacao = 1 and $sWhereInstit"));
          } else {
            $result = $clprocjur->sql_record($clprocjur->sql_query(null, "*", null, " v62_sequencial = {$pesquisa_chave} and $sWhereInstit"));
          }
          if($clprocjur->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v62_descricao',false);</script>";
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
js_tabulacaoforms("form2","chave_v62_procjurtipo",true,1,"chave_v62_procjurtipo",true);
</script>
