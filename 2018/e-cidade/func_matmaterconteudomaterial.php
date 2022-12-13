<?php
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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_matmaterconteudomaterial_classe.php");

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$oDaoConteudoMaterial = new cl_matmaterconteudomaterial;
$oDaoConteudoMaterial->rotulo->label("m08_codigo");

$oRotulo = new rotulocampo();
$oRotulo->label("m60_descr");
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
          <td><label for="chave_m08_codigo"><?=$Lm08_codigo?></label></td>
          <td><? db_input("m08_codigo", 10, $Im08_codigo, true, "text", 4, "", "chave_m08_codigo"); ?></td>
        </tr>

        <tr>
          <td width="4%" align="right" nowrap title="<?=$Tm60_descr?>">
            <label for="chave_m60_descr" class="bold">Material:</label>
          </td>
          <td width="96%" align="left" nowrap>
            <?php
              db_input("m60_descr", 40, $Im60_descr, true, "text", 4, "", "chave_m60_descr");
            ?>
          </td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_matmaterconteudomaterial.hide();">
  </form>
      <?
      if ( !isset($pesquisa_chave) ) {

        $sCampos  = " m08_codigo    as DB_m08_codigo,           ";
        $sCampos .= " m08_matmater  as DB_m08_matmater,         ";
        $sCampos .= " m60_descr,                                ";
        $sCampos .= " m08_unidade       as DB_m08_unidade,      ";
        $sCampos .= " a.m61_descr       as DL_Unidade_Material, ";
        $sCampos .= " matunid.m61_descr as DL_Unidade_Conteúdo, ";
        $sCampos .= " m08_quantidade ";


        $aWhere = array();
        if (isset($chave_m08_codigo) && (trim($chave_m08_codigo)!="") ) {
          $aWhere[] = " m08_codigo = {$chave_m08_codigo} ";
        } else if (isset($chave_m60_descr) && (trim($chave_m60_descr)!="") ) {
          $aWhere[] = " m60_descr like '$chave_m60_descr%' ";
        }
        $sWhere  = implode(" and ", $aWhere );
        $sql     = $oDaoConteudoMaterial->sql_query("", $sCampos, "m08_codigo", $sWhere);

        $repassa = array();
        if ( isset($chave_m08_codigo) ) {
          $repassa = array("chave_m08_codigo"=>$chave_m08_codigo,"chave_m60_descr"=>$chave_m60_descr);
        }
        echo '<div class="container">';
        echo '  <fieldset>';
        echo '    <legend>Resultado da Pesquisa</legend>';
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        echo '  </fieldset>';
        echo '</div>';
      } else {

        if ($pesquisa_chave!=null && $pesquisa_chave!="") {

          $result = $oDaoConteudoMaterial->sql_record($oDaoConteudoMaterial->sql_query($pesquisa_chave));
          if ( $oDaoConteudoMaterial->numrows != 0 ) {

            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$m08_codigo',false);</script>";
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
<script>
  js_tabulacaoforms("form2","chave_m08_codigo",true,1,"chave_m08_codigo",true);
</script>
