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

require_once modification("libs/db_stdlib.php");
require_once modification("libs/db_conecta.php");
require_once modification("libs/db_sessoes.php");
require_once modification("libs/db_usuariosonline.php");
require_once modification("dbforms/db_funcoes.php");
require_once modification("classes/db_avaliacao_classe.php");
db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);
$clavaliacao = new cl_avaliacao;
$clavaliacao->rotulo->label("db101_sequencial");
$clavaliacao->rotulo->label("db101_descricao");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tdb101_sequencial?>">
              <label for="chave_db101_sequencial"><?=$Ldb101_sequencial?></label>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
		            db_input("db101_sequencial",10,$Idb101_sequencial,true,"text",4,"","chave_db101_sequencial");
	            ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tdb101_descricao?>">
              <label for="chave_db101_descricao"><?=$Ldb101_descricao?></label>
            </td>
            <td width="96%" align="left" nowrap>
              <?php
                db_input("db101_descricao", 40, $Idb101_descricao,true,"text",4,"","chave_db101_descricao");
              ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_avaliacao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?

      $aWhere = array();
      if (!isset($_GET["todos"])) {
        $aWhere[] = "db101_sequencial < 3000000";
      }

      if (isset($_GET["editaveis"]) && $_GET["editaveis"] == 'true') {
        $aWhere[] = "db101_permiteedicao is true";
      }

      if(isset($_GET['iTipoAvaliacao']) && ($_GET['iTipoAvaliacao'] == 5 || $_GET['iTipoAvaliacao'] == 6)) {
        $aWhere = array();
        $aWhere[] = "db101_avaliacaotipo = ".$_GET['iTipoAvaliacao'] . " and db101_ativo = 't'";
      }

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_avaliacao.php")==true){
             include(modification("funcoes/db_func_avaliacao.php"));
           }else{
           $campos = "avaliacao.*";
           }
        }

        if(isset($chave_db101_sequencial) && (trim($chave_db101_sequencial)!="") ){
          $aWhere[] = "db101_sequencial = {$chave_db101_sequencial}";
        }

        if (isset($chave_db101_descricao) && (trim($chave_db101_descricao)!="") ){
          $aWhere[] = "db101_descricao ilike '{$chave_db101_descricao}%'";
        }

        $sWhere = implode(" and ", $aWhere);
        $sql    = $clavaliacao->sql_query("",$campos,"db101_sequencial", $sWhere);

        $repassa = array();
        if(isset($chave_db101_sequencial)){
          $repassa = array("chave_db101_sequencial"=>$chave_db101_sequencial,"chave_db101_sequencial"=>$chave_db101_sequencial);
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          $aWhere[] = "db101_sequencial = {$pesquisa_chave}";
          $sWhere = implode(" and ", $aWhere);
          $result = $clavaliacao->sql_record($clavaliacao->sql_query(null,
                                                                     "*",
                                                                     null,
                                                                     " {$sWhere}"
                                                                     )
                                                                     );
          if(!$result){

            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          } else {
            if($clavaliacao->numrows!=0){
              db_fieldsmemory($result,0);
              echo "<script>".$funcao_js."('$db101_descricao',false);</script>";
            }else{
	            echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
            }
          }
        }else{
	       echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
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
js_tabulacaoforms("form2","chave_db101_sequencial",true,1,"chave_db101_sequencial",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
