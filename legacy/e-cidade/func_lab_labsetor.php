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

$cllab_labsetor = new cl_lab_labsetor;
$cllab_setor    = new cl_lab_setor;

$cllab_labsetor->rotulo->label("la24_i_codigo");
$cllab_setor->rotulo->label("la23_c_descr");
$cllab_labsetor->rotulo->label("la24_i_setor");
?>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body bgcolor=#CCCCCC>
  <div class="container">
    <form name="form2" method="post" action="" class="form-container">
      <fieldset>
        <legend>Filtros:</legend>
        <table>
          <tr>
            <td>
              <?=$Lla24_i_codigo?>
            </td>
            <td>
              <?php
              db_input( "la24_i_codigo", 10, $Ila24_i_codigo, true, "text", 4, "", "chave_la24_i_codigo" );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Setor:</label>
            </td>
            <td>
              <?php
              db_input( "la24_i_setor", 50, $Ila24_i_setor, true, "text", 4, "", "chave_la24_i_setor" );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <?=$Lla23_c_descr?>
            </td>
            <td>
              <?php
              db_input( "la23_c_descr", 50, $Ila23_c_descr, true, "text", 4, "", "chave_la23_c_descr" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="reset"  id="limpar"     value="Limpar" >
      <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_lab_labsetor.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td>
          <?php
          $sQuery = "sql_query";
          if( !isset( $pesquisa_chave ) ) {

            if( isset( $campos ) == false ) {
              $campos = " la24_i_codigo, la24_i_setor, la23_c_descr, la24_i_laboratorio, la02_c_descr";
            }

            $sWhere = "";
            $sep    = "";

            if( isset( $la24_i_laboratorio ) ) {

              $sWhere .= " la24_i_laboratorio = {$la24_i_laboratorio} ";
              $sep     = " and";
            }

            if ( isset( $la22_i_codigo ) ) {

              $sWhere .= " {$sep} la21_i_requisicao = {$la22_i_codigo} ";
              $sep     = " and";
              $sQuery  = "sql_query_requisicao";
              $campos  = " distinct {$campos}";
            }

            if( isset( $chave_la24_i_codigo ) && ( trim( $chave_la24_i_codigo ) != "" ) ) {

              $sWhere .= " {$sep} la24_i_codigo = {$chave_la24_i_codigo} ";
              $sep     = " and ";
            }

            if( isset( $chave_la23_c_descr ) && ( trim( $chave_la23_c_descr ) != "" ) ) {

              $sWhere .= " {$sep} la23_c_descr like '{$chave_la23_c_descr}%' ";
              $sep     = " and ";
            }

            if( isset( $chave_la24_i_setor ) && ( trim( $chave_la24_i_setor ) != "" ) ) {

              $sWhere .= " {$sep} la24_i_setor = {$chave_la24_i_setor} ";
              $sep     = " and ";
            }

            $repassa = array();
            if( isset( $chave_la24_i_codigo ) ) {
              $repassa = array( "chave_la24_i_codigo" => $chave_la24_i_codigo, "chave_la23_c_descr" => $chave_la23_c_descr );
            }

            $sql = $cllab_labsetor->$sQuery( "", $campos, "la24_i_codigo", $sWhere );

            db_lovrot( $sql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
          } else {

            if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

              $where = "";
              $sep   = "";

              if( isset( $la24_i_laboratorio ) ) {

                $where = " la24_i_laboratorio = {$la24_i_laboratorio} ";
                $sep   = " and";
              }

              if ( isset( $la22_i_codigo ) ) {

                $sWhere .= " la22_i_codigo = {$la22_i_codigo} ";
                $sep     = " and";
                $sQuery  = "sql_query_requisicao";
              }

              $sWhereLabSetor = "la23_i_codigo = {$pesquisa_chave} {$sep} {$where}";
              $sSqlLabSetor   = $cllab_labsetor->$sQuery( null, '*', null, $sWhereLabSetor );
              $result         = $cllab_labsetor->sql_record( $sSqlLabSetor );

              if( $cllab_labsetor->numrows != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('{$la23_c_descr}',false, {$la24_i_codigo});</script>";
              } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true, '');</script>";
              }
            } else {
              echo "<script>".$funcao_js."('',false, '');</script>";
            }
          }
          ?>
        </td>
      </tr>
    </table>
  </div>
</body>
</html>
<script>
$('chave_la24_i_codigo').className = 'field-size2';
$('chave_la24_i_setor').className  = 'field-size2';
$('chave_la23_c_descr').className  = 'field-size7';

js_tabulacaoforms( "form2", "chave_la24_i_codigo", true, 1, "chave_la24_i_codigo", true );
</script>