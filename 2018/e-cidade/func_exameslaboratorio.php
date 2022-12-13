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

require_once (modification("libs/db_stdlib.php"));
require_once (modification("libs/db_conecta.php"));
require_once (modification("libs/db_sessoes.php"));
require_once (modification("libs/db_usuariosonline.php"));
require_once (modification("dbforms/db_funcoes.php"));
require_once (modification("classes/db_lab_exame_classe.php"));

db_postmemory($_POST);
parse_str($_SERVER["QUERY_STRING"]);

$oRotulo = new rotulocampo();
$oRotulo->label("la09_i_codigo");
$oRotulo->label("la09_i_exame");
$oRotulo->label("la08_c_descr");

$oDaoExame = new \cl_lab_exame();

$sCampos = " la09_i_exame, la08_c_descr, la09_i_codigo as db_la09_i_codigo";
$aWhere  = array();
if ( !empty( $_GET['iLaboratorio'] ) ) {
  $aWhere[] = " la24_i_laboratorio = " .$_GET['iLaboratorio'];
}
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
          <td><label><?=$Lla09_i_exame?></label></td>
          <td><? db_input("la09_i_exame",10,$Ila09_i_exame,true,"text",4,"","chave_la09_i_exame"); ?></td>
        </tr>
        <tr>
          <td><label><?=$Lla08_c_descr?></label></td>
          <td><? db_input("la08_c_descr",30,$Ila08_c_descr,true,"text",4,"","chave_la08_c_descr");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_lab_exame.hide();">
  </form>
  <?php


  if (!isset($pesquisa_chave)) {

    if ( !empty($chave_la09_i_exame) ) {
      $aWhere[] = " la09_i_exame = {$chave_la09_i_exame} ";
    }
    if ( !empty( $chave_la08_c_descr ) ) {
      $aWhere[] = " la08_c_descr like '{$chave_la08_c_descr}%' ";
    }
    $sWhere = implode(" and ", $aWhere);
    $sSql   = $oDaoExame->sql_query_exame_laboratorio(null, $sCampos, "la08_c_descr", $sWhere);

    $repassa = array();
    if (isset($chave_la09_i_exame)) {
      $repassa = array("chave_la09_i_exame" => $chave_la09_i_exame, "chave_la08_c_descr" => $chave_la08_c_descr);
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
      db_lovrot($sSql,15,"()","",$funcao_js,"","NoMe",$repassa);
    echo '  </fieldset>';
    echo '</div>';
  } else {

    if ($pesquisa_chave != null && $pesquisa_chave != "") {

      $aWhere[] = " la09_i_exame = {$pesquisa_chave} ";
      $sWhere   = implode(" and ", $aWhere);
      $sSql     = $oDaoExame->sql_query_exame_laboratorio(null, $sCampos, null, $sWhere);
      $result   = $oDaoExame->sql_record($sSql);
      if ($oDaoExame->numrows != 0) {

        db_fieldsmemory($result,0);
        echo "<script>".$funcao_js."('$la08_c_descr',false, '$db_la09_i_codigo');</script>";
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
  js_tabulacaoforms("form2","chave_la09_i_exame",true,1,"chave_la08_c_descr",true);
</script>
