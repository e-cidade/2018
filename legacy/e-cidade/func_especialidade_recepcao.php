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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$iUnidade = db_getsession( "DB_coddepto" );

$oDaoEspecMedico = new cl_especmedico;
$oRotulo         = new rotulocampo;
$oRotulo->label( "rh70_estrutural" );
$oRotulo->label( "rh70_descr" );
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class="body-default">
  <div class="container">
    <form id="formularioEspecialidade" name="formularioEspecialidade" method="post" action="">
      <fieldset>
        <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td>
              <label>Estrutural:</label>
            </td>
            <td>
              <?php
   		        db_input( "rh70_estrutural", 10, $Irh70_estrutural, true, "text", 4, "", "chave_rh70_estrutural" );
		          ?>
            </td>
          </tr>
          <tr>
            <td>
              <label>Especialidade:</label>
            </td>
            <td>
              <?php
   		        db_input( "rh70_descr", 10, $Irh70_descr, true, "text", 4, "", "chave_rh70_descr" );
		          ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar" type="button" id="limpar" value="Limpar" onclick="limpaCampos();">
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_especmedico.hide();">
    </form>
  </div>
  <div class="container">
    <?php
    $sCampos    = "distinct rh70_estrutural, rh70_descr, rh70_sequencial";
    $sOrdenacao = "rh70_descr, rh70_estrutural";
    $aWhere     = array( "sd27_c_situacao = 'A'", "sd04_i_unidade = {$iUnidade}" );

    if( isset( $chave_sd04_i_medico )  && !empty( $chave_sd04_i_medico ) ) {

      $aWhere[] = "sd04_i_medico = {$chave_sd04_i_medico}";
      $sCampos  = "distinct rh70_estrutural, rh70_descr, sd27_i_codigo, rh70_sequencial";
    }

    if( !isset( $pesquisa_chave ) ) {

      if( isset( $chave_rh70_estrutural )  && !empty( $chave_rh70_estrutural ) ) {
        $aWhere[] = "rh70_estrutural ilike '{$chave_rh70_estrutural}%'";
      }

      if( isset( $chave_rh70_descr )  && !empty( $chave_rh70_descr ) ) {
        $aWhere[] = "rh70_descr ilike '{$chave_rh70_descr}%'";
      }

      $repassa = array();

      $sWhere = implode( ' AND ', $aWhere );
      $sSql   = $oDaoEspecMedico->sql_query( "", $sCampos, $sOrdenacao, $sWhere );
      db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
    } else {

      if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

        $sCampos  = "sd27_i_codigo, rh70_estrutural, rh70_descr, sd27_i_rhcbo";
        $aWhere[] = "rh70_estrutural = '{$pesquisa_chave}'";
        $sWhere   = implode( ' AND ', $aWhere );

        $sSql          = $oDaoEspecMedico->sql_query( null, $sCampos, $sOrdenacao, $sWhere );
        $rsEspecMedico = $oDaoEspecMedico->sql_record( $sSql );

        if( $oDaoEspecMedico->numrows > 0 ) {

          db_fieldsmemory( $rsEspecMedico, 0 );

          echo "<script>".$funcao_js."( false, '{$sd27_i_codigo}', '{$rh70_estrutural}', '{$rh70_descr}', '{$sd27_i_rhcbo}' );</script>";
        } else {
          echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrado');</script>";
        }
      } else {
        echo "<script>".$funcao_js."('',false);</script>";
      }
    }
    ?>
  </div>
</body>
<script>
$('chave_rh70_estrutural').addClassName( 'field-size2' );
$('chave_rh70_descr').addClassName( 'field-size7' );

function limpaCampos() {

  $('chave_rh70_estrutural').value = '';
  $('chave_rh70_descr').value      = '';
  $('formularioEspecialidade').submit();
}
</script>