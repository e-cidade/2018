<?php
/**
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
include(modification("classes/db_proced_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$clproced = new cl_proced;
$clproced->rotulo->label("v03_codigo");
$clproced->rotulo->label("v03_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tv03_codigo?>">
              <?=$Lv03_codigo?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("v03_codigo",4,$Iv03_codigo,true,"text",4,"","chave_v03_codigo");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$Tv03_descr?>">
              <?=$Lv03_descr?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("v03_descr",20,$Iv03_descr,true,"text",4,"","chave_v03_descr");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_proced.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?php
      $aWhere   = array();
      $aWhere[] = "v03_instit = " . db_getsession("DB_instit");
      $sQuery   = !isset($sTiposDebitos) ? "sql_query" : "sql_query_arretipo";

      if(!empty($sTiposDebitos)) {
        $aWhere[] = "v06_arretipo in({$sTiposDebitos})";
      }

      if(!isset($pesquisa_chave)) {

        if(isset($campos) == false) {

          if(file_exists("funcoes/db_func_proced.php") == true) {
            include(modification("funcoes/db_func_proced.php"));
          } else {
            $campos = "proced.*";
          }
        }

        if(isset($chave_v03_codigo) && (trim($chave_v03_codigo) != "") ) {
          $aWhere[] = "v03_codigo = {$chave_}";
        }

        if(isset($chave_v03_descr) && (trim($chave_v03_descr) != "") ) {
          $aWhere[] = "v03_descr like '{$chave_v03_descr}%'";
        }

        $sql = $clproced->{$sQuery}("", $campos, "v03_codigo", implode(' AND ', $aWhere));

        $repassa = array();
        if(isset($chave_v03_descr)){
          $repassa = array(
            "chave_v03_codigo" => $chave_v03_codigo,
            "chave_v03_descr"  => $chave_v03_descr
          );
        }

        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      } else {

        if($pesquisa_chave != null && $pesquisa_chave != "") {

          $aWhere[] = "v03_codigo = {$pesquisa_chave}";

          $sSql   = $clproced->{$sQuery}(null, "*", null, implode(' AND ', $aWhere));
          $result = $clproced->sql_record($sSql);

          if($clproced->numrows != 0) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v03_descr',false);</script>";
          } else {
	          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
          }
        } else {
	        echo "<script>".$funcao_js."('',false);</script>";
        }
      }
      ?>
     </td>
   </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form2","chave_v03_descr",true,1,"chave_v03_descr",true);
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
