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
include(modification("libs/db_stdlibwebseller.php"));
require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("classes/db_base_classe.php"));
include(modification("classes/db_escolabase_classe.php"));

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$iModulo      = db_getsession('DB_modulo');
$clbase       = new cl_base;
$clescolabase = new cl_escolabase;
$clbase->rotulo->label("ed31_i_codigo");
$clbase->rotulo->label("ed31_c_descr");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

  <form name="form2" method="post" action="" class="container">
    <fieldset>
      <legend>Dados para Pesquisa</legend>
      <table width="35%" border="0" align="center" cellspacing="3" class="form-container">
        <tr>
          <td><label for="chave_ed31_i_codigo"><?=$Led31_i_codigo?></label></td>
          <td><?php db_input("ed31_i_codigo",10,$Ied31_i_codigo,true,"text",4,"","chave_ed31_i_codigo");?></td>
        </tr>
        <tr>
          <td><label for="chave_ed31_c_descr"><?=$Led31_c_descr?></label></td>
          <td><?php db_input("ed31_c_descr",40,$Ied31_c_descr,true,"text",4,"","chave_ed31_c_descr");?></td>
        </tr>
      </table>
    </fieldset>
    <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
    <input name="limpar" type="reset" id="limpar" value="Limpar" >
    <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_base.hide();">
  </form>

  <?php
  $escola       = db_getsession("DB_coddepto");
  $sWhereEscola = "ed77_i_escola = {$escola}";
  $aWhereBase   = array();

  if ( !isset($lBuscarBasesSecretaria) || $lBuscarBasesSecretaria == 'false') {
    $aWhereBase[] = $sWhereEscola;
  }

  if ( isset($curso) ) {
    $aWhereBase[] = "ed31_i_curso = {$curso}";
  }

  if ( !empty($somenteAtivas) && $somenteAtivas == 'S' ) {
    $aWhereBase[] = " ed31_c_ativo = 'S' ";
  }

  $sQuery = 'sql_query_base';

  /* Verifica se o módulo é a Secretaria e altera a query para buscar somente bases sem vínculo com escolabase */
  if ( isset($lBuscarBasesSecretaria) && $lBuscarBasesSecretaria == 'true') {

    $sQuery       = 'sql_query_base2';
    $aWhereBase[] = 'ed77_i_codigo is null';
    // Se for na escola, deve trazer somente bases com os cursos da escola
    if ( $iModulo == 1100747 ) {
      $aWhereBase[] = "exists (select 1 from cursoescola where ed71_i_escola = {$escola} and ed71_i_curso = ed29_i_codigo) ";
    }
  }


  if( !isset($pesquisa_chave) ) {

    if( isset($campos) == false ) {

      $campos = "base.*";
      if( file_exists("funcoes/db_func_base.php") == true ) {
        include(modification("funcoes/db_func_base.php"));
      }
    }

    $sOrdemBase = "ed29_c_descr, ed31_c_descr";

    if( !empty($chave_ed31_i_codigo) ) {
      $aWhereBase[] = " ed31_i_codigo = {$chave_ed31_i_codigo}";
    }

    if( !empty($chave_ed31_c_descr) ) {
      $aWhereBase[] = " ed31_c_descr ilike '{$chave_ed31_c_descr}%'";
    }

    $sql = $clbase->$sQuery( null, $campos, $sOrdemBase, implode(" AND ", $aWhereBase) );


    echo '<div class="container">';
    echo '  <fieldset>';
    echo '    <legend>Resultado da Pesquisa</legend>';
      db_lovrot( $sql, 15, "()", "", $funcao_js );
    echo '  </fieldset>';
    echo '</div>';

  } else {

    if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

      $aWhereBase[] = 'ed31_i_codigo = $pesquisa_chave';
      $sSqlBase     = $clbase->$sQuery( null, "*", null, implode(" AND ", $aWhereBase) );

      $rsBase = db_query( $sSqlBase );

      if( !$rsBase && pg_num_rows($rsBase) > 0 ) {

        db_fieldsmemory($rsBase, 0);
        echo "<script>".$funcao_js."('$ed31_c_descr','$ed218_c_nome',false);</script>";
      } else {
        echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
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
