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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oDaoCgsUnd = new cl_cgs_und_ext;
$oRotulo    = new rotulocampo();
$oRotulo->label( 'z01_i_cgsund' );
$oRotulo->label( 'z01_v_nome' );
$oRotulo->label( 'z01_v_ident' );
?>

<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script type="text/javascript" src="scripts/scripts.js"></script>
  <script type="text/javascript" src="scripts/prototype.js"></script>
  <script type="text/javascript" src="scripts/strings.js"></script>
</head>
<body>
  <div class="container">
    <form action="" method="post" name="form1">
      <fieldset>
        <legend>Filtros</legend>
        <table>
          <tr>
            <td>
              <label class="bold">CGS:</label>
            </td>
            <td>
              <?php
              db_input( 'z01_i_cgsund', 10, $Iz01_i_cgsund, true, 'text', 4, "", "chave_z01_i_cgsund" );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Data de Nascimento:</label>
            </td>
            <td>
              <?php
              db_inputdata( 'z01_d_nasc', @$z01_d_nasc_dia, @$z01_d_nasc_mes, @$z01_d_nasc_ano, true, 'text', 4,
                "onkeydown='return js_controla_tecla_enter(this, event)'", 'chave_z01_d_nasc' );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Nome:</label>
            </td>
            <td>
              <?php
              db_input( 'z01_v_nome', 30, $Iz01_v_nome, true, 'text', 4, "", 'chave_z01_v_nome' );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Identidade:</label>
            </td>
            <td>
              <?php
              db_input( 'z01_v_ident', 15, $Iz01_v_ident, true, 'text', 1, "", "chave_z01_v_ident" );
              ?>
            </td>
          </tr>
          <tr>
            <td>
              <label class="bold">Cartão SUS:</label>
            </td>
            <td>
              <?php
              db_input( 's115_c_cartaosus', 15, @$Is115_c_cartaosus, true, 'text', 4, "", 'chave_s115_c_cartaosus' );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
      <input name="limpar"    type="button" id="limpar"    value="Limpar" onClick="limparCampos();">
      <input name="Fechar"    type="button" id="fechar"    value="Fechar" onClick="parent.db_iframe_cgs_und.hide();">
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td>
          <?php
          $aWhere = array();
          $sSql   = '';

          $sWhereInativos  = " not exists (select 1 from cgs_und_ext ";
          $sWhereInativos .= "              where cgs_und_ext.z01_i_cgsund = cgs_und.z01_i_cgsund ";
          $sWhereInativos .= "                and (z01_b_inativo is true or z01_b_faleceu is true) ) ";

          if( !isset( $pesquisa_chave ) ) {

            $sCampos  = "z01_i_cgsund, z01_v_nome, s115_c_cartaosus, z01_d_nasc, z01_v_sexo, z01_v_ender, z01_i_numero";
            $sCampos .= ", z01_v_bairro, z01_v_ident, z01_v_mae";

            if( isset( $chave_z01_i_cgsund ) && trim( $chave_z01_i_cgsund ) != "" ) {
              $aWhere[] = "z01_i_cgsund = {$chave_z01_i_cgsund}";
            }

            if( isset( $chave_z01_v_nome ) && trim( $chave_z01_v_nome ) != "" ) {
              $aWhere[] = "to_ascii(z01_v_nome) like to_ascii('{$chave_z01_v_nome}%')";
            }

            if( isset( $chave_z01_v_ident ) && trim( $chave_z01_v_ident ) != "" ) {
              $aWhere[] = "z01_v_ident ilike '{$chave_z01_v_ident}%'";
            }

            if( isset( $chave_z01_d_nasc ) && trim( $chave_z01_d_nasc ) != "" ) {

              $oDataNascimento = new DBDate( $chave_z01_d_nasc );

              $aWhere[]        = "z01_d_nasc = '{$oDataNascimento->getDate()}'";
            }

            if( isset( $chave_s115_c_cartaosus ) && trim( $chave_s115_c_cartaosus ) != "" ) {
              $aWhere[] = "s115_c_cartaosus ilike '{$chave_s115_c_cartaosus}%'";
            }

            $repassa = array();

            if( isset( $chave_z01_i_cgsund ) ) {

              $repassa = array(
                                "chave_z01_i_cgsund"          => @$chave_z01_i_cgsund,
                                "chave_z01_v_nome"            => @$chave_z01_v_nome,
                                "chave_z01_v_ident"           => @$chave_z01_v_ident,
                                "chave_z01_d_nasc"            => @$chave_z01_d_nasc,
                                "chave_z01_c_cartaosus"       => @$chave_s115_c_cartaosus,
                                "chave_z01_i_familiamicroarea"=> @$chave_z01_i_familiamicroarea
                              );
            }

            if( count( $aWhere ) > 0 ) {

              $aWhere[] = $sWhereInativos;
              $sWhere = implode( ' and ', $aWhere );
              $sSql   = $oDaoCgsUnd->sql_query( null, $sCampos, "z01_i_cgsund", $sWhere );      

              db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
            }
          } else {

            if( isset( $pesquisa_chave ) && $pesquisa_chave != "" && $pesquisa_chave != null ) {

              $sWhere   = " cgs_und.z01_i_cgsund = {$pesquisa_chave} and {$sWhereInativos} ";
              $sSql     = $oDaoCgsUnd->sql_query( null, "*", null, $sWhere );
              $rsCgsUnd = db_query( $sSql );

              if( $rsCgsUnd && pg_num_rows( $rsCgsUnd ) > 0 ) {

                db_fieldsmemory( $rsCgsUnd, 0 );
                echo "<script>" . $funcao_js . "( '{$z01_v_nome}', false, '{$z01_v_sexo}', '{$s115_c_cartaosus}', '{$z01_d_nasc}' );</script>";
              } else {
                echo "<script>" . $funcao_js . "( 'Chave(" . $pesquisa_chave . ") não Encontrado', true );</script>";
              }
            } else {
              echo "<script>" . $funcao_js . "( '', false );</script>";
            }
          }
          ?>
        </td>
      </tr>
    </table>
  </div>
</body>
<script>

$('chave_z01_i_cgsund').className     = 'field-size2';
$('chave_z01_d_nasc').className       = 'field-size2';
$('chave_z01_v_nome').className       = 'field-size7';
$('chave_z01_v_ident').className      = 'field-size3';
$('chave_s115_c_cartaosus').className = 'field-size3';

function limparCampos() {

  $('chave_z01_i_cgsund').value     = '';
  $('chave_z01_d_nasc').value       = '';
  $('chave_z01_v_nome').value       = '';
  $('chave_z01_v_ident').value      = '';
  $('chave_s115_c_cartaosus').value = '';

  document.form1.submit();
}

document.body.onload = function() {
  $('chave_z01_v_nome').focus();
};
</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
