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

$oDaoFarMaterSaude = new cl_far_matersaude();
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
    <form id="formularioMedicamentosMaterial" name="formularioMedicamentosMaterial" method="post" action="">
      <fieldset>
        <legend>Filtros</legend>
        <table class="form-container">
          <tr>
            <td>
              <label for="codigoMedicamento">Código:</label>
            </td>
            <td>
              <input id="codigoMedicamento"
                     name="codigoMedicamento"
                     value=""
                     class="field-size2"
                     oninput="js_ValidaCampos( this, 1, 'Código', 't', 'f' );" />
            </td>
          </tr>
          <tr>
            <td>
              <label for="nomeMedicamento">Medicamento:</label>
            </td>
            <td>
              <input id="nomeMedicamento"
                     name="nomeMedicamento"
                     value=""
                     class="field-size7"
                     oninput="js_ValidaCampos( this, 3, 'Medicamento', 't', 't' );" />
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_medicamentosmaterial.hide();">
    </form>
  </div>
  <div class="container">
    <?php
    $aWhere = array();

    $sCampos  = "fa01_i_codigo, m60_descr";
    $sCampos .= ", case ";
    $sCampos .= "       when m08_codigo is not null ";
    $sCampos .= "            then m08_quantidade ";
    $sCampos .= "            else m60_quantent ";
    $sCampos .= "   end as m08_quantidade";
    $sCampos .= ", case  ";
    $sCampos .= "       when conteudomaterial.m61_codmatunid is not null ";
    $sCampos .= "            then conteudomaterial.m61_abrev  ";
    $sCampos .= "            else unidadematerial.m61_abrev  ";
    $sCampos .= "   end as dl_unidade";
    $sCampos .= ", case ";
    $sCampos .= "       when conteudomaterial.m61_codmatunid is not null ";
    $sCampos .= "            then conteudomaterial.m61_codmatunid  ";
    $sCampos .= "            else unidadematerial.m61_codmatunid  ";
    $sCampos .= "   end as m61_codmatunid";

    $sOrdenacao = "fa01_i_codigo";

    if( !isset( $pesquisa_chave ) ) {

      if( !empty( $codigoMedicamento ) ) {
        $aWhere[] = "fa01_i_codigo = {$codigoMedicamento}";
      }

      if( !empty( $nomeMedicamento ) ) {
        $aWhere[] = "m60_descr ilike '{$nomeMedicamento}%'";
      }

      $repassa = array();

      $sWhere  = implode( ', ', $aWhere );
      $sSql    = $oDaoFarMaterSaude->sql_query_medicamentos( null, $sCampos, $sOrdenacao, $sWhere );

      db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $repassa );
    } else {

      if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

        $aWhere[] = "fa01_i_codigo = {$pesquisa_chave}";
        $sWhere   = implode( ', ', $aWhere );

        $sSqlFarMaterSaude = $oDaoFarMaterSaude->sql_query_medicamentos( null, $sCampos, $sOrdenacao, $sWhere );
        $rsFarMaterSaude   = $oDaoFarMaterSaude->sql_record( $sSqlFarMaterSaude );

        if( $oDaoFarMaterSaude->numrows > 0 ) {

          db_fieldsmemory( $rsFarMaterSaude, 0 );

          echo "<script>".$funcao_js."( false, '{$fa01_i_codigo}', '{$m60_descr}', '{$m08_quantidade}', '{$dl_unidade}', '{$m61_codmatunid}' );</script>";
        } else {
          echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") não Encontrada');</script>";
        }
      } else {
        echo "<script>".$funcao_js."('',false);</script>";
      }
    }
    ?>
  </div>
</body>