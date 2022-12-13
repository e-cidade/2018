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

parse_str( $_SERVER["QUERY_STRING"] );


$oPost = db_utils::postMemory($_POST);
$oData = new DBDate( date('d/m/Y') );

$oDaoProntuarios = new cl_prontuarios;
$oDaoProntuarios->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label("z01_i_cgsund");
$clrotulo->label("z01_v_nome");

if ( !isset($oPost->pesquisar) && empty($oPost->data_ini) ) {

  $data_ini     = $oData->convertTo(DBDate::DATA_PTBR);
  $data_ini_dia = $oData->getDia();
  $data_ini_mes = $oData->getMes();
  $data_ini_ano = $oData->getAno();
}

if(!isset($oPost->pesquisar) && empty( $oPost->data_fim ) ) {

  $data_fim     = $oData->convertTo(DBDate::DATA_PTBR);
  $data_fim_dia = $oData->getDia();
  $data_fim_mes = $oData->getMes();
  $data_fim_ano = $oData->getAno();
}

$aWhere   = array();
$aWhere[] = " sd24_i_unidade = " . DB_getsession( "DB_coddepto" );
$aWhere[] = " sd24_c_digitada = 'N'";

$sCampos  = "  prontuarios.sd24_i_codigo ";
$sCampos .= " ,prontuarios.sd24_d_cadastro ";
$sCampos .= " ,prontuarios.sd24_c_cadastro ";
$sCampos .= " ,prontuarios.sd24_i_numcgs ";
$sCampos .= " ,cgs_und.z01_v_nome ";
$sCampos .= " ,cgs_und.z01_d_nasc ";
$sCampos .= " ,cast('<div style=\'background-color: ' || classificacaorisco.sd78_cor || ';width: 150px;height: 16px;display:inline-block;text-align:center;\'>' || classificacaorisco.sd78_descricao || '</div>' as varchar) as dl_Prioridade ";

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
</head>
<body class="body-defaut">
  <div class="container">
    <form name="form2" method="post" action="" class="form-container">
      <fieldset>
        <legend>Filtros</legend>
        <table>
          <tr>
            <td title="<?=$Tsd24_i_codigo?>">
              <?=$Lsd24_i_codigo?>
            </td>
            <td nowrap>
              <?php
              db_input( "sd24_i_codigo", 11, $Isd24_i_codigo, true, "text", 4, "", "chave_sd24_i_codigo" );
              ?>
            </td>
          </tr>

          <tr>
            <td nowrap title="<?=$Tz01_v_nome?>">
              <label class="bold">Paciente:</label>
            </td>
            <td nowrap colspan="4">
              <?php
              db_input( "z01_v_nome", 35, $Iz01_v_nome, true, "text", 4, "", "chave_z01_v_nome" );
              ?>
            </td>
          </tr>

          <tr>
            <td>
              <label class="bold">Data de Atendimento de:</label>
            </td>
            <td>
              <?php
              db_inputdata( 'data_ini', $data_ini_dia, $data_ini_mes, $data_ini_ano, true, 'text', 4 );
              ?>
            </td>
            <td>
              <label class="bold">até</label>
            </td>
            <td>
              <?php
              db_inputdata( 'data_fim', $data_fim_dia, $data_fim_mes, $data_fim_ano, true, 'text', 4 );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
      <input name="limpar"    type="button" id="limpar"     value="Limpar"      onClick="js_limpar();">
      <input name="emite"     type="button" id="emite"      value="Emite Lista" onClick="js_emitelista()">
      <input name="Fechar"    type="button" id="fechar"     value="Fechar"      onClick="parent.db_iframe_triagem.hide();">
    </form>
</div>
<div class="container">
  <table>
    <tr>
      <td>
        <?php

        $lFiltrou = isset($oPost->pesquisar);

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
        if ( !isset($pesquisa_chave) && !empty($data_ini) ) {

          $oDataInicio = new DBDate($data_ini);
          $aWhere[]    = " sd24_d_cadastro >= '" .  $oDataInicio->getDate() . "'";
        }

        if ( !isset($pesquisa_chave) && !empty($data_fim) ) {

          $oDataFim = new DBDate($data_fim);
          $aWhere[] = " sd24_d_cadastro <= '" . $oDataFim->getDate() . "'";
        }

        $sOrdem  = " COALESCE(sd78_peso,0) desc, sd24_d_cadastro, sd24_c_cadastro::time ";

        if ( isset($lFiltrarMovimentados) ) {
          $aWhere[] = "sd91_local in (2, 4)";
        }

        $sWhere  = implode(" and ", $aWhere);

        $sSql  = "select {$sCampos}";
        $sSql .= "  from prontuarios ";
        $sSql .= "       inner join cgs_und                       on cgs_und.z01_i_cgsund          = prontuarios.sd24_i_numcgs ";
        $sSql .= "       left  join especmedico                   on especmedico.sd27_i_codigo     = prontuarios.sd24_i_profissional ";
        $sSql .= "       left  join unidademedicos                on unidademedicos.sd04_i_codigo  = especmedico.sd27_i_undmed ";
        $sSql .= "       left  join medicos                       on medicos.sd03_i_codigo         = unidademedicos.sd04_i_medico ";
        $sSql .= "       left  join cgm                           on cgm.z01_numcgm                = medicos.sd03_i_cgm ";
        $sSql .= "       left  join rhcbo                         on rhcbo.rh70_sequencial         =  especmedico.sd27_i_rhcbo ";
        $sSql .= "       left  join unidades                      on unidades.sd02_i_codigo        = prontuarios.sd24_i_unidade ";
        $sSql .= "       left  join db_depart                     on db_depart.coddepto            = unidades.sd02_i_codigo ";
        $sSql .= "       left  join prontuariosclassificacaorisco on sd101_prontuarios             = sd24_i_codigo";
        $sSql .= "       left  join classificacaorisco            on sd78_codigo                   = sd101_classificacaorisco";
        $sSql .= "       left  join setorambulatorial             on setorambulatorial.sd91_codigo = prontuarios.sd24_setorambulatorial";
        $sSql .= " where {$sWhere}  ";
        $sSql .= " order by {$sOrdem}";
        $aRepassa                 = array();
        $aRepassa["data_ini"]     = $data_ini;
        $aRepassa["data_ini_dia"] = $data_ini_dia;
        $aRepassa["data_ini_mes"] = $data_ini_mes;
        $aRepassa["data_ini_ano"] = $data_ini_ano;
        $aRepassa["data_fim"]     = $data_fim;
        $aRepassa["data_fim_dia"] = $data_fim_dia;
        $aRepassa["data_fim_mes"] = $data_fim_mes;
        $aRepassa["data_fim_ano"] = $data_fim_ano;

        db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa, false);
        ?>
       </td>
     </tr>
  </table>
</div>
</body>
</html>
<script>
js_tabulacaoforms( "form2", "chave_sd24_i_codigo", true, 1, "chave_sd24_i_codigo", true );

function js_emitelista() {

  jan = window.open(
                     'sau2_triagem001.php?unidade=<?=DB_getsession("DB_coddepto")?>',
                     '',
                     'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '
                   );
  jan.moveTo(0,0);
}

function js_limpar(){

  document.form2.chave_sd24_i_codigo.value = "";
  document.form2.chave_z01_v_nome.value    = "";
}

$('chave_z01_v_nome').focus();

</script>