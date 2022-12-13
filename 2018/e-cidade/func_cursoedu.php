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

//MODULO: educação
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("classes/db_cursoedu_classe.php"));
db_postmemory($_POST);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcurso = new cl_curso;
$clcurso->rotulo->label("ed29_i_codigo");
$clcurso->rotulo->label("ed29_c_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body >

<form name="form2" method="post" action="" class="container">
  <fieldset>
    <legend>Filtros</legend>
    <table class="form-container">
      <tr>
        <td title="<?=$Ted29_i_codigo?>">
          <label for="chave_ed29_i_codigo"><?=$Led29_i_codigo?></label>
        </td>
        <td >
          <?php db_input("ed29_i_codigo",10,$Ied29_i_codigo,true,"text",4,"","chave_ed29_i_codigo");?>
        </td>
      </tr>
      <tr>
        <td width="4%" align="right" nowrap title="<?=$Ted29_c_descr?>">
          <label for="chave_ed29_c_descr"><?=$Led29_c_descr?></label>
        </td>
        <td >
          <?php db_input("ed29_c_descr",30,$Ied29_c_descr,true,"text",4,"","chave_ed29_c_descr");?>
        </td>
      </tr>

    </table>
  </fieldset>
  <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
  <input name="limpar" type="reset" id="limpar" value="Limpar" >
  <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_curso.hide();">
</form>

<?php

  $aWhere = array();
  if (isset($soAtivo)) {
    $aWhere[] = ' ed29_ativo is true ';
  }
  if (!isset($pesquisa_chave)) {

    if ( !isset($campos)) {

      $campos = "cursoedu.*";
      if (file_exists("funcoes/db_func_cursoedu.php") == true) {
        include(modification("funcoes/db_func_cursoedu.php"));
      }
    }

    if ( isset($chave_ed29_i_codigo) && (trim($chave_ed29_i_codigo)!="") ) {
      $aWhere[] = " ed29_i_codigo = $chave_ed29_i_codigo";
    } else if(isset($chave_ed29_c_descr) && (trim($chave_ed29_c_descr)!="") ) {
      $aWhere[] = " ed29_c_descr like '{$chave_ed29_c_descr}%' ";
    }
    if ( !empty($aCursosVinculados) ) {
      $aWhere[] = "ed29_i_codigo not in ({$aCursosVinculados})";
    }
    $sWhere = implode(' and ', $aWhere);
    $sql    = $clcurso->sql_query( null, $campos, "ed29_c_descr", $sWhere);

    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
      db_lovrot($sql,15,"()","",$funcao_js);
    echo '  </fieldset>';
    echo '</div>';
  } else {

    if ( !empty($pesquisa_chave) ) {

      $aWhere   = array();
      $aWhere[] = " ed29_i_codigo = {$pesquisa_chave} ";
      if (isset($soAtivo)) {
        $aWhere[] = ' ed29_ativo is true ';
      }

      if ( !empty($aCursosVinculados) ) {
        $aWhere[] = " ed29_i_codigo not in ({$aCursosVinculados}) ";
      }
      $sWhere = implode(' and ', $aWhere);
      $result = $clcurso->sql_record($clcurso->sql_query( "", "*", "", $sWhere));
      if ($clcurso->numrows != 0) {

        db_fieldsmemory($result,0);
        echo "<script>".$funcao_js."('$ed29_c_descr',false, '$ed29_i_ensino');</script>";
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
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
