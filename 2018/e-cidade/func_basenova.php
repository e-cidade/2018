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

db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clbase = new cl_base;
$clbase->rotulo->label("ed31_i_codigo");
$clbase->rotulo->label("ed31_c_descr");
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
          <td><label for='chave_ed31_i_codigo'><?=$Led31_i_codigo?></label></td>
          <td><?php db_input("ed31_i_codigo", 20, $Ied31_i_codigo, true, "text", 4, "", "chave_ed31_i_codigo"); ?></td>
        </tr>
        <tr>
          <td><label for='chave_ed31_c_descr'><?=$Led31_c_descr?></label></td>
          <td><?php db_input("ed31_c_descr", 20, $Ied31_c_descr, true, "text", 4, "", "chave_ed31_c_descr");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_base.hide();">
  </form>

<?php

  $aWhere   = array();
  $aWhere[] = " ed77_i_escola = " . db_getsession("DB_coddepto");

  if( !empty($curso)) {
    $aWhere[] = " ed31_i_curso = {$curso} ";
  }
  $sCampos = " ed31_i_codigo, ed31_c_descr, ed29_i_codigo as db_ed29_i_codigo, ed29_c_descr, ed31_c_medfreq";

  if ( !isset($pesquisa_chave) ) {

    if (isset($chave_ed31_i_codigo) && (trim($chave_ed31_i_codigo)!="") ) {
      $aWhere[] = " ed31_i_codigo = {$chave_ed31_i_codigo} ";
    } else if(isset($chave_ed31_c_descr) && (trim($chave_ed31_c_descr) != "") ) {
      $aWhere[] = " ed31_c_descr like '$chave_ed31_c_descr%' ";
    }

    $sWhere  = implode(" and ", $aWhere);
    $sql     = $clbase->sql_query_base("", $sCampos, "ed31_i_codigo", $sWhere);
    $repassa = array();
    if(isset($chave_ed31_i_codigo)){
      $repassa = array("chave_ed31_i_codigo"=>$chave_ed31_i_codigo,"chave_ed31_c_descr"=>$chave_ed31_c_descr);
    }
    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
      db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
    echo '  </fieldset>';
    echo '</div>';
  } else {


    if ( !empty($pesquisa_chave) ) {

      $aWhere[] = " ed31_i_codigo = {$pesquisa_chave} ";
      $sWhere   = implode(" and ", $aWhere);

      $result   = $clbase->sql_record($clbase->sql_query_base(null, $sCampos, null, $sWhere));
      if ($clbase->numrows != 0) {

        db_fieldsmemory($result,0);
        echo "<script>".$funcao_js."('{$ed31_c_descr}', '{$db_ed29_i_codigo}', '{$ed29_c_descr}', false);</script>";
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
  js_tabulacaoforms("form2","chave_ed31_i_codigo",true,1,"chave_ed31_i_codigo",true);
</script>
