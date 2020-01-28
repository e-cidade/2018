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

//MODULO: educação
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

db_postmemory( $_POST );
parse_str( $HTTP_SERVER_VARS["QUERY_STRING"] );

$oDaoSerie = new cl_serie;
$oDaoSerie->rotulo->label( "ed11_i_codigo" );
$oDaoSerie->rotulo->label( "ed11_c_descr" );

$aWhere = array();
if ( !empty($base) ) {
  $aWhere[] = " ed34_i_base = {$base} ";
}

?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <script type="text/javascript" src="scripts/strings.js"></script>
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
  <form name="form2" method="post" action="" class="container">
    <table class="form-container">
      <tr>
        <td>
          <fieldset>
            <legend>Filtros:</legend>
            <table class="subtable">
              <tr>
                <td nowrap title="<?=$Ted11_i_codigo?>">
                  <?=$Led11_i_codigo?>
                </td>
                <td nowrap>
                  <?php
                    db_input( "ed11_i_codigo", 10, $Ied11_i_codigo, true, "text", 4, "", "chave_ed11_i_codigo");
                  ?>
                </td>
              </tr>
              <tr>
                <td nowrap title="<?=$Ted11_c_descr?>">
                  <?=$Led11_c_descr?>
                </td>
                <td nowrap>
                  <?php
                    db_input( "ed11_c_descr", 30, $Ied11_c_descr, true, "text", 4, "", "chave_ed11_c_descr");
                  ?>
                </td>
              </tr>
              <tr>
                <td colspan="2" align="center">
                  <input name="curso" type="hidden" value="<?=isset( $curso ) ? $curso : "";?>">
                  <?php
                    if ( isset( $inicial ) ) {
                  ?>
                      <input name="inicial" type="hidden" value="<?=$inicial?>">
                  <?}?>
                </td>
              </tr>
            </table>
          </fieldset>
          <div style="text-align: center;">
            <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
            <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
            <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_serie.hide();">
          </div>
        </td>
      </tr>
    </table>
  </form>
  <?php

    $sCampos  = " distinct serie.ed11_i_codigo, ";
    $sCampos .= " trim(serie.ed11_c_descr)::varchar AS ed11_c_descr, ";
    $sCampos .= " trim(serie.ed11_c_abrev)::varchar AS ed11_c_abrev, ";
    $sCampos .= " serie.ed11_i_sequencia, ";
    $sCampos .= " serie.ed11_i_codcenso ";

    if( isset( $chave_ed11_i_codigo ) && ( trim( $chave_ed11_i_codigo ) != "" ) ) {
      $aWhere[] = " ed11_i_codigo = {$chave_ed11_i_codigo} ";
    } else if( isset( $chave_ed11_c_descr ) && ( trim( $chave_ed11_c_descr ) != "" ) ) {
      $aWhere[] = " ed11_c_descr like '{$chave_ed11_c_descr}%' ";
    }

    $aRepassa = array();
    if ( isset($chave_ed11_i_codigo) ) {
     $aRepassa = array("chave_ed11_i_codigo" => $chave_ed11_i_codigo, "chave_ed11_c_descr" => $chave_ed11_c_descr);
    }

    $sSql = $oDaoSerie->sql_query_turma(null, $sCampos, " ed11_i_sequencia ", implode(" and ", $aWhere));

    echo "<div class='container'>";
    db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa );
    echo "</div>";
  ?>
  </body>
</html>
<script>
$('chave_ed11_i_codigo').className = 'field-size2';
$('chave_ed11_c_descr').className  = 'field-size9';
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>