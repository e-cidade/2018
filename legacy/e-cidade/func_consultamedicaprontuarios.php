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
$oRotulo->label("sd04_i_cbo");
$oRotulo->label("sd03_i_codigo");
$oRotulo->label("sd27_i_codigo");
$oRotulo->label("rh70_sequencial");
$oRotulo->label("rh70_estrutural");
$oRotulo->label("rh70_descr");

$sd03_i_codigo = $oGet->iProfissional;

/**
 * Filtros informados por parâmetro
 */
$iUnidade = DB_getsession("DB_coddepto");
if ( !empty($oPost->iUnidade) ) {
  $iUnidade = $oPost->iUnidade;
}

$aEspecialidadesMedico = array();

if(!empty($oPost->rh70_sequencial)) {
  $aEspecialidadesMedico[] = $oPost->rh70_sequencial;
} else if(!empty($oGet->iEspecialidadeSequencial)) {
  $aEspecialidadesMedico[] = $oGet->iEspecialidadeSequencial;
} else if(!empty($oGet->iProfissional)) {
  $aEspecialidadesMedico = buscaEspecialidadeMedico($iUnidade, $oGet->iProfissional);
}

function buscaEspecialidadeMedico($iUnidade, $iProfissional) {

  $sWhereEspecMedico  = "     sd04_i_unidade = {$iUnidade}";
  $sWhereEspecMedico .= " and sd04_i_medico  = {$iProfissional}";
  $oDaoEspecMedico = new cl_especmedico();
  $sSqlEspecMedico = $oDaoEspecMedico->sql_query_especmedico(null, " sd27_i_rhcbo ", null, $sWhereEspecMedico);
  $rsEspecMedico   = db_query( $sSqlEspecMedico );
  if ( !$rsEspecMedico || pg_num_rows($rsEspecMedico) == 0 ) {
    return array();
  }
  $iLinhas = pg_num_rows($rsEspecMedico);

  $aEspecialidades = array();
  for ( $i = 0; $i < $iLinhas; $i++) {
    $aEspecialidades[] = db_utils::fieldsMemory($rsEspecMedico, $i)->sd27_i_rhcbo;
  }
  return $aEspecialidades;
}

$aWhere   = array();
$aWhere[] = " sd24_c_digitada = 'N'"; // somente FAA abertas
$aWhere[] = " sd24_i_unidade  = {$iUnidade} ";


/**
 * Listar campos:
 */
$sCampos  = " prontuarios.sd24_i_codigo, ";
$sCampos .= " prontuarios.sd24_d_cadastro, ";
$sCampos .= " prontuarios.sd24_c_cadastro, ";
$sCampos .= " prontuarios.sd24_i_numcgs, ";
$sCampos .= " cgs_und.z01_v_nome, ";
$sCampos .= " cgs_und.z01_d_nasc, ";
$sCampos .= " rhcbo.rh70_descr, ";
$sCampos .= " rhcbo.rh70_sequencial as db_rh70_sequencial, ";
$antes    = pg_escape_string('<div style="background-color: ');
$depois   = pg_escape_string(';width: 150px;height: 16px;display:inline-block;text-align:center;">');
$sCampos .= " cast('{$antes}' || sd78_cor || '{$depois}' || sd78_descricao || '</div>' as varchar) as dl_Prioridade ";

?>

<html>
  <head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <link href="estilos.css" rel="stylesheet" type="text/css">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
  <script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
  </head>
  <body class='body-default'>
  <div class="container">

    <form name="form2" method="post">

      <fieldset>
        <legend>Filtros</legend>
          <table class="form-container">
            <tr>
              <td class='bold'><label for="chave_sd24_i_codigo">FAA:</label></td>
              <td colspan="3">
                <?php
                  db_input("sd24_i_codigo",11,$Isd24_i_codigo,true,"text",4,"","chave_sd24_i_codigo");
                ?>
              </td>
            </tr>
            <tr>
              <td class='bold'><label for="chave_z01_v_nome">Paciente:</label></td>
              <td colspan="3">
                <?php
                  db_input("z01_v_nome", 50, $Iz01_v_nome,true,"text",4,"","chave_z01_v_nome");
                ?>
              </td>
            </tr>
            <tr>
              <td class='bold'><label for="sd24_d_cadastro_inicio">Data de atendimento:</label></td>
              <td >
                <?php
                  db_inputdata( 'sd24_d_cadastro_inicio', $sd24_d_cadastro_inicio_dia, $sd24_d_cadastro_inicio_mes, $sd24_d_cadastro_inicio_ano, true, 'text', 1);
                ?>
              </td>
              <td class='bold text-center'><label for="sd24_d_cadastro_fim">até:</label></td>
              <td class="text-right">
                <?php
                  db_inputdata( 'sd24_d_cadastro_fim', $sd24_d_cadastro_fim_dia, $sd24_d_cadastro_fim_mes, $sd24_d_cadastro_fim_ano, true, 'text', 1);
                ?>
              </td>
            </tr>
            <tr >
              <td nowrap title="<?=$Tsd04_i_cbo?>">
                <label for="rh70_estrutural">
                  <?php
                    db_ancora($Lsd04_i_cbo, "js_pesquisaEspecialidade(true);", 1);
                  ?>
                </label>
              </td>
              <td colspan="3">
                <?php
                  // sd03_i_codigo = código do profissional
                  db_input('sd03_i_codigo', 10, $Isd03_i_codigo, true, 'hidden', 3);
                  db_input('sd27_i_codigo', 10, $Isd27_i_codigo, true, 'hidden', 3);
                  db_input('rh70_sequencial', 10, $Irh70_sequencial, true, 'hidden', 3);
                  db_input('rh70_estrutural', 10, $Irh70_estrutural, true, 'text', 1, " onchange = 'js_pesquisaEspecialidade(false);'");
                  db_input('rh70_descr', 36, $Irh70_descr, true, 'text', 3);
                ?>
              </td>
            </tr>

            <tr>
              <td><label for='filtro_agenda' class="bold"><label for="filtro_agenda">Agenda:</label></label> </td>
              <td>
                <?php
                  $aFiltro = array(1 => "Pacientes", 2 => "Urgência");
                  db_select("filtro_agenda", $aFiltro, true, 1, "onchange='js_validaFiltroAgenda();'");
                ?>
              </td>
            </tr>
          </table>


      </fieldset>

      <input name="pesquisar" type="submit" id="pesquisar" value="Pesquisar">
      <input name="limpar"    type="button" id="limpar"    value="Limpar" onClick="js_limpar();">
      <input name="Fechar"    type="button" id="fechar"    value="Fechar" onClick="parent.db_iframe_prontuarios.hide();">

    </form>
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

      if ( !empty($oPost->filtro_agenda) && $oPost->filtro_agenda == 2 ) {
        $aWhere[] = " (s104_i_codigo is null and s104_rhcbo is null)";
      }

      if(    !empty($sd03_i_codigo)
          && (    !isset($oPost->filtro_agenda)
               || (isset($oPost->filtro_agenda) && $oPost->filtro_agenda == 1))) {

        /**
         * Adicionado filtro por especialidade... para trazer os pacientes que não estão vinculados ao médico
         * logado mais que foram encaminhados para a especialidade que o médico atende
         */
        $aWhere[] = " (sd04_i_medico = {$sd03_i_codigo} or sd04_i_medico is null ) and s104_rhcbo is not null";
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

      if ( isset($lFiltrarMovimentados) ) {
        $aWhere[] = "sd91_local in (3, 4)";
      }

      $aRepassa = array();
      $aRepassa["filtro_agenda"]              = !empty($oPost->filtro_agenda) ? $oPost->filtro_agenda : 1;
      $aRepassa["sd24_d_cadastro_inicio"]     = $sd24_d_cadastro_inicio;
      $aRepassa["sd24_d_cadastro_inicio_dia"] = $sd24_d_cadastro_inicio_dia;
      $aRepassa["sd24_d_cadastro_inicio_mes"] = $sd24_d_cadastro_inicio_mes;
      $aRepassa["sd24_d_cadastro_inicio_ano"] = $sd24_d_cadastro_inicio_ano;
      $aRepassa["sd24_d_cadastro_fim"]        = $sd24_d_cadastro_fim;
      $aRepassa["sd24_d_cadastro_fim_dia"]    = $sd24_d_cadastro_fim_dia;
      $aRepassa["sd24_d_cadastro_fim_mes"]    = $sd24_d_cadastro_fim_mes;
      $aRepassa["sd24_d_cadastro_fim_ano"]    = $sd24_d_cadastro_fim_ano;
      $aRepassa["z01_v_nome"]                 = !empty($chave_z01_v_nome)        ? $oPost->chave_z01_v_nome : '';
      $aRepassa["sd03_i_codigo"]              = !empty($oPost->sd03_i_codigo)    ? $oPost->sd03_i_codigo   : '';
      $aRepassa["sd27_i_codigo"]              = !empty($oPost->sd27_i_codigo)    ? $oPost->sd27_i_codigo   : '';
      $aRepassa["rh70_sequencial"]            = !empty($oPost->rh70_sequencial)  ? $oPost->rh70_sequencial : '';
      $aRepassa["rh70_estrutural"]            = !empty($oPost->rh70_estrutural)  ? $oPost->rh70_estrutural : '';
      $aRepassa["rh70_descr"]                 = !empty($oPost->rh70_descr)       ? $oPost->rh70_descr      : '';

      $sWhere = implode(" and ", $aWhere);
      $sOrdem = " COALESCE(sd78_peso,0) desc, sd24_d_cadastro, sd24_c_cadastro::time  ";

      $sSql  = " select {$sCampos}";
      $sSql .= "    from prontuarios";
      $sSql .= "    inner join cgs                           on cgs.z01_i_numcgs                                = prontuarios.sd24_i_numcgs";
      $sSql .= "    inner join cgs_und                       on cgs_und.z01_i_cgsund                            = prontuarios.sd24_i_numcgs";
      $sSql .= "    left  join prontuariosclassificacaorisco on prontuariosclassificacaorisco.sd101_prontuarios = prontuarios.sd24_i_codigo";
      $sSql .= "    left  join classificacaorisco            on classificacaorisco.sd78_codigo                  = prontuariosclassificacaorisco.sd101_classificacaorisco";
      $sSql .= "    left  join prontprofatend                on prontprofatend.s104_i_prontuario                = prontuarios.sd24_i_codigo";


      $sSqlCondicao = '';
      if ( count($aEspecialidadesMedico) > 0 ) {

        $sEspecialidades = implode(",", $aEspecialidadesMedico);
        $sSqlCondicao .= " and s104_rhcbo in ({$sEspecialidades})";
      }
      if (!empty($oPost->filtro_agenda) && $oPost->filtro_agenda == 2) {
        $sSqlCondicao = '';
      }

      $sSql .= $sSqlCondicao;

      $sSql .= "    left  join especmedico                   on especmedico.sd27_i_codigo                       = prontprofatend.s104_i_profissional";
      $sSql .= "    left  join rhcbo                         on rhcbo.rh70_sequencial                           = prontprofatend.s104_rhcbo";
      $sSql .= "    left  join unidademedicos                on unidademedicos.sd04_i_codigo                    = especmedico.sd27_i_undmed";
      $sSql .= "    left  join setorambulatorial             on setorambulatorial.sd91_codigo                   = prontuarios.sd24_setorambulatorial";
      $sSql .= "    where {$sWhere}";
      $sSql .= "    order by {$sOrdem}";
      db_lovrot( $sSql, 15, "()", "", $funcao_js, "", "NoMe", $aRepassa, false);
    ?>
  </div>
  </body>
</html>

<script type="text/JavaScript">

var oGet = js_urlToObject();

function js_pesquisaEspecialidade( lMostra ) {

  var sUrl  = 'func_especmedico.php';
      sUrl += '?chave_sd04_i_medico=' + oGet.iProfissional;
      sUrl += "&chave_sd04_i_unidade=<?=$iUnidade?>";
      sUrl += '&funcao_js=parent.js_mostraRetornoEspecialidade|sd27_i_codigo|rh70_estrutural|rh70_descr|sd27_i_rhcbo';
  if ( lMostra ) {
    js_OpenJanelaIframe('', 'db_iframe_especmedico', sUrl, 'Pesquisa Especialidade Médica', true);
  } else if ( $F('rh70_estrutural') != '' ) {

    sUrl += '&chave_rh70_estrutural=' + $F('rh70_estrutural');
    js_OpenJanelaIframe('', 'db_iframe_especmedico', sUrl, 'Pesquisa Especialidade Médica', false);
  } else {

    $('sd27_i_codigo').value   = '';
    $('rh70_sequencial').value = '';
    $('rh70_estrutural').value = '';
    $('rh70_descr').value      = '';

  }
}

function js_mostraRetornoEspecialidade() {

  $('sd27_i_codigo').value   = arguments[0];
  $('rh70_estrutural').value = arguments[1];
  $('rh70_descr').value      = arguments[2];
  $('rh70_sequencial').value = arguments[3];
  db_iframe_especmedico.hide();
}

if( oGet.iEspecialidadeEstrutural != '' && "<?=!isset($oPost->pesquisar)?>" ) {

  $('rh70_estrutural').value = oGet.iEspecialidadeEstrutural;
  js_pesquisaEspecialidade(false);
}

function js_validaFiltroAgenda() {

  if ( $F('filtro_agenda') == 2 ) {

    $('rh70_estrutural').value    = '';
    $('sd27_i_codigo').value      = '';
    $('rh70_estrutural').value    = '';
    $('rh70_descr').value         = '';
    $('rh70_sequencial').value    = '';
  }
}

function js_limpar() {

  $("chave_z01_v_nome").value    = "";
  $("chave_sd24_i_codigo").value = "";
}

</script>
<script type="text/javascript">
(function() {
  var query = frameElement.getAttribute('name').replace('IF', ''), input = document.querySelector('input[value="Fechar"]');
  input.onclick = parent[query] ? parent[query].hide.bind(parent[query]) : input.onclick;
})();
</script>
