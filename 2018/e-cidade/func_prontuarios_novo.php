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

$oPost = db_utils::postMemory($_POST);
$oGet  = db_utils::postMemory($_GET);
$oData = new DBDate( date('d/m/Y') );

if ( !isset($oPost->pesquisar) && empty($oPost->sd24_d_cadastro_inicio) ) {

  $sd24_d_cadastro_inicio     = $oData->convertTo(DBDate::DATA_PTBR);
  $sd24_d_cadastro_inicio_dia = $oData->getDia();
  $sd24_d_cadastro_inicio_mes = $oData->getMes();
  $sd24_d_cadastro_inicio_ano = $oData->getAno();
}

if ( !isset($oPost->pesquisar) && empty($oPost->sd24_d_cadastro_fim) ) {

  $sd24_d_cadastro_fim        = $oData->convertTo(DBDate::DATA_PTBR);
  $sd24_d_cadastro_fim_dia    = $oData->getDia();
  $sd24_d_cadastro_fim_mes    = $oData->getMes();
  $sd24_d_cadastro_fim_ano    = $oData->getAno();
}

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$oDaoProntuarios = new cl_prontuarios;
$oDaoProntuarios->rotulo->label();

$oRotulo = new rotulocampo;
$oRotulo->label("z01_v_nome");


/**
 * Filtros informados por parâmetro
 */
$iUnidade = DB_getsession("DB_coddepto");
if ( !empty($oPost->iUnidade) ) {
  $iUnidade = $oPost->iUnidade;
}

$aWhere     = array();
$aWhere[]   = " sd24_c_digitada = 'N'"; // somente FAA abertas
$aWhere[]   = " sd24_i_unidade = {$iUnidade} ";

if( !isset($oGet->lFiltraMovimentados) ) {
  $aWhere[] = " sd91_local in (1, 4) ";
}

/**
 * Listar campos:
 */
$sCampos  = " prontuarios.sd24_i_codigo, ";
$sCampos .= " prontuarios.sd24_d_cadastro, ";
$sCampos .= " prontuarios.sd24_c_cadastro, ";
$sCampos .= " prontuarios.sd24_i_numcgs, ";
$sCampos .= " cgs_und.z01_v_nome, ";
$sCampos .= " cgs_und.z01_d_nasc, ";
$sCampos .= " cast('<div style=\'background-color: ' || classificacaorisco.sd78_cor || ';width: 150px;height: 16px;display:inline-block;text-align:center;\'>' || classificacaorisco.sd78_descricao || '</div>' as varchar) as dl_Prioridade ";

?>

<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  </head>
  <body class='body-default'>
  <div class="container">

    <fieldset>
      <legend>Filtros</legend>
      <form name="form2" method="post">
        <table class="form-container">
          <tr>
            <td class='bold'>FAA:</td>
            <td colspan="3">
              <?php
                db_input("sd24_i_codigo",11,$Isd24_i_codigo,true,"text",4,"","chave_sd24_i_codigo");
              ?>
            </td>
          </tr>
          <tr>
            <td class='bold'>Paciente:</td>
            <td colspan="3">
              <?php
                db_input("z01_v_nome", 50, $Iz01_v_nome,true,"text",4,"","chave_z01_v_nome");
              ?>
            </td>
          </tr>
          <tr>
            <td class='bold'>Data atendimento de:</td>
            <td >
              <?php
                db_inputdata( 'sd24_d_cadastro_inicio', $sd24_d_cadastro_inicio_dia, $sd24_d_cadastro_inicio_mes, $sd24_d_cadastro_inicio_ano, true, 'text', 1);
              ?>
            </td>
            <td class='bold text-center'>até:</td>
            <td class="text-right">
              <?php
                db_inputdata( 'sd24_d_cadastro_fim', $sd24_d_cadastro_fim_dia, $sd24_d_cadastro_fim_mes, $sd24_d_cadastro_fim_ano, true, 'text', 1);
              ?>
            </td>
          </tr>
        </table>

        <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
        <input name="limpar"    type="button" id="limpar" value="Limpar" onClick="js_limpar();">
        <input name="Fechar"    type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_prontuarios.hide();">

      </form>
    </fieldset>
  </div>

  <div class="subcontainer">

    <?php

      /**
       * Filtros realizados no formulário
       */
      if ( !empty($chave_sd24_i_codigo) ) {
        $aWhere[] = "sd24_i_codigo = {$chave_sd24_i_codigo}";
      }

      if ( !empty($chave_z01_v_nome) )  {
        $aWhere[] = "z01_v_nome ilike '$chave_z01_v_nome%' ";
      }

      /**
       * sempre que for para pesquisar qualquer pronturario
       */
      if ( !isset($pesquisa_chave) && !empty($sd24_d_cadastro_inicio) ) {

        $oDataInicio = new DBDate($sd24_d_cadastro_inicio);
        $aWhere[]    = " sd24_d_cadastro >= '" .  $oDataInicio->getDate() . "'";
      }

      if ( !isset($pesquisa_chave) && !empty($sd24_d_cadastro_fim) ) {

        $oDataFim = new DBDate($sd24_d_cadastro_fim);
        $aWhere[] = " sd24_d_cadastro <= '" . $oDataFim->getDate() . "'";
      }

      $sWhere = implode(" and ", $aWhere);
      $sOrdem = " COALESCE(sd78_peso,0) desc, sd24_d_cadastro, sd24_c_cadastro::time  ";


      if ( !isset($pesquisa_chave) ) {

        $aRepassa                               = array();
        $aRepassa["chave_z01_v_nome"]           = !empty($chave_z01_v_nome) ? $chave_z01_v_nome : '';
        $aRepassa["sd24_d_cadastro_inicio"]     = $sd24_d_cadastro_inicio;
        $aRepassa["sd24_d_cadastro_inicio_dia"] = $sd24_d_cadastro_inicio_dia;
        $aRepassa["sd24_d_cadastro_inicio_mes"] = $sd24_d_cadastro_inicio_mes;
        $aRepassa["sd24_d_cadastro_inicio_ano"] = $sd24_d_cadastro_inicio_ano;
        $aRepassa["sd24_d_cadastro_fim"]        = $sd24_d_cadastro_fim;
        $aRepassa["sd24_d_cadastro_fim_dia"]    = $sd24_d_cadastro_fim_dia;
        $aRepassa["sd24_d_cadastro_fim_mes"]    = $sd24_d_cadastro_fim_mes;
        $aRepassa["sd24_d_cadastro_fim_ano"]    = $sd24_d_cadastro_fim_ano;

        $sSql = $oDaoProntuarios->sql_query_atendimentos( null, $sCampos, $sOrdem, $sWhere );

        db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa, false);
      } else if ( !empty($pesquisa_chave) ) {

        $sSqlAtendimento = $oDaoProntuarios->sql_query_atendimentos( $pesquisa_chave, $sCampos );
        $rsAtendimento   = $oDaoProntuarios->sql_record( $sSqlAtendimento );

        $sRertorno = "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        if( $oDaoProntuarios->numrows != 0 ) {

          db_fieldsmemory( $rsAtendimento, 0 );
          $sRertorno = "<script>" . $funcao_js . "('$z01_v_nome',false, $sd24_i_numcgs);</script>";
        }
        echo $sRertorno;
      } else {
        echo "<script>".$funcao_js."('',false);</script>";
      }
    ?>
  </div>
  </body>
</html>
<script type="text/javascript">

  function js_limpar() {

    $("chave_z01_v_nome").value    = "";
    $("chave_sd24_i_codigo").value = "";
  }

</script>
