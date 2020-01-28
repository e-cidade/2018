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
require_once("libs/db_utils.php");
require_once("libs/db_stdlibwebseller.php");
require_once("dbforms/db_funcoes.php");

db_postmemory( $_POST );
parse_str( $_SERVER["QUERY_STRING"] );

$clrotulo = new rotulocampo;
$clrotulo->label("sd63_c_nome");
$clrotulo->label("sd63_c_procedimento");

$clsau_proccbo = new cl_sau_proccbo;
$clsau_proccbo->rotulo->label("sd96_i_codigo");
$clsau_proccbo->rotulo->label("sd96_i_procedimento");

if(!isset($intUnidade)){
  $intUnidade = db_getsession("DB_coddepto");
}

$lFiltraServico = true; // filtra por servico se a flag de filtragem por servico da sau_config estiver ativada
if( isset( $lNaoFiltrar ) && $lNaoFiltrar ) {
  $lFiltraServico = false;
}

$oSauConfig = loadConfig('sau_config_ext');
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
    <form name="form2" method="post" action="">
      <fieldset>
        <legend>Filtros:</legend>
        <table>
          <tr>
            <td nowrap title="<?=$Tsd63_c_procedimento?>">
              <label for="chave_sd63_c_procedimento"><?=$Lsd63_c_procedimento?></label>
            </td>
            <td nowrap>
              <?php
                db_input( 'sTodos',        5,              '', true, 'hidden', 4 );
                db_input( 'lAutomatico',   5,              '', true, 'hidden', 4 );
                db_input( "sd63_c_procedimento", 10, $Isd63_c_procedimento, true, "text", 4, "", "chave_sd63_c_procedimento" );
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap title="<?=$Tsd63_c_nome?>">
              <label for="chave_sd63_c_nome"><?=$Lsd63_c_nome?></label>
            </td>
            <td nowrap>
              <?php
              db_input( "sd63_c_nome", 60, $Isd63_c_nome, true, "text", 4, "", "chave_sd63_c_nome" );
              ?>
            </td>
          </tr>
        </table>
      </fieldset>
      <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" >
      <input name="limpar"    type="button" id="limpar"     value="Limpar"    onClick="js_limpar();">
      <input name="Fechar"    type="button" id="fechar"     value="Fechar"    onClick="js_fechar('<?=@$campoFoco ?>')">
      <?php
      if (isset($lBotaoMostrarTodos)) {
        ?>
        <input name="mostrartodos" type="button" id="mostrartodos" value="Todos" onClick="js_mostrarTodos()">
      <?php
      }
      ?>
    </form>
  </div>
  <div class="container">
    <table>
      <tr>
        <td>
          <?php
          $strWhere           = '';
          $sWhereProcedPadrao = '';

          if( isset( $lFiltrarPadroes ) ) {

            $lProcedimentosPadroesProfissional = false;
            $lProcedimentosPadroesUnidade      = false;
            $oDaoSauProcedMedAgendamento       = new cl_sau_procedmedagendamento();
            $sSql                              = $oDaoSauProcedMedAgendamento->sql_query(
                                                                                          null,
                                                                                          's156_i_codigo ',
                                                                                          '',
                                                                                          "s156_i_especmed = {$iEspecMed}"
                                                                                        );
            $rs = $oDaoSauProcedMedAgendamento->sql_record( $sSql );

            if( $oDaoSauProcedMedAgendamento->numrows > 0 ) {
              $lProcedimentosPadroesProfissional = true;
            } else {

              $oDaoSauProcedUnidadeAgendamento = new cl_sau_procedunidadeagendamento();
              $sSql                            = $oDaoSauProcedUnidadeAgendamento->sql_query(
                                                                                              null,
                                                                                              's157_i_codigo',
                                                                                              '',
                                                                                              "s157_i_unidade = {$intUnidade}"
                                                                                            );
              $rs = $oDaoSauProcedUnidadeAgendamento->sql_record( $sSql );

              if( $oDaoSauProcedUnidadeAgendamento->numrows > 0 ) {
                $lProcedimentosPadroesUnidade = true;
              }
            }

            if( $lProcedimentosPadroesProfissional ) {

              $sWhereProcedPadrao .= 'and sd63_c_procedimento in (select sd63_c_procedimento from sau_procedmedagendamento ';
              $sWhereProcedPadrao .= 'inner join sau_procedimento on sau_procedimento.sd63_i_codigo = ';
              $sWhereProcedPadrao .= 'sau_procedmedagendamento.s156_i_procedimento where sau_procedmedagendamento.s156_i_especmed = ';
              $sWhereProcedPadrao .= $iEspecMed.')';
            } else if( $lProcedimentosPadroesUnidade ) {

              $sWhereProcedPadrao .= 'and sd63_c_procedimento in (select sd63_c_procedimento from sau_procedunidadeagendamento ';
              $sWhereProcedPadrao .= 'inner join sau_procedimento on sau_procedimento.sd63_i_codigo = ';
              $sWhereProcedPadrao .= 'sau_procedunidadeagendamento.s157_i_procedimento where ';
              $sWhereProcedPadrao .= 'sau_procedunidadeagendamento.s157_i_unidade = '.$intUnidade.')';
            }
          }

          if( !isset( $chave_rh70_sequencial ) ) {
            $chave_rh70_sequencial = "";
          }

          if( !isset( $pesquisa_chave ) ) {

            if( isset( $campos ) == false ) {

              if( file_exists( "funcoes/db_func_sau_proccbo.php" ) == true ) {
                include("funcoes/db_func_sau_proccbo.php");
              } else {
                $campos = "sau_proccbo.*";
              }
            }

            if( $oSauConfig->s103_procsemcbo != 'N' ) {
              $lProcSemCBO = true;
            } else {
              $lProcSemCBO = false;
            }

            if( isset( $chave_sd63_c_procedimento ) && ( trim( $chave_sd63_c_procedimento ) != '' ) ) {

              $sql = $clsau_proccbo->sql_query_func(
                                                     '',
                                                     $campos,
                                                     "sd96_i_codigo asc, sd63_c_nome, sd96_i_anocomp desc, sd96_i_mescomp desc",
                                                     " sd63_c_procedimento ilike '{$chave_sd63_c_procedimento}%' {$strWhere} ",
                                                     $intUnidade,
                                                     $lFiltraServico,
                                                     $chave_rh70_sequencial,
                                                     $lProcSemCBO
                                                   );
              $sql = "select * from ( $sql ) as xx";
            } else if( isset( $chave_sd96_i_procedimento ) && ( trim( $chave_sd96_i_procedimento ) != "" ) ) {

              $sql = $clsau_proccbo->sql_query_func(
                                                     "",
                                                     $campos,
                                                     "sd96_i_anocomp desc , sd96_i_mescomp desc,sd96_i_codigo asc, sd63_c_nome",
                                                     " sd63_i_codigo = {$chave_sd96_i_procedimento} {$strWhere} ",
                                                     $intUnidade,
                                                     $lFiltraServico,
                                                     $chave_rh70_sequencial,
                                                     $lProcSemCBO
                                                   );

            } else if( isset( $chave_sd63_c_nome ) && ( trim( $chave_sd63_c_nome ) != "" ) ) {

              $sql = $clsau_proccbo->sql_query_func(
                                                     "",
                                                     $campos,
                                                     "sd96_i_anocomp desc, sd96_i_mescomp desc, sd96_i_codigo asc",
                                                     " sd63_c_nome ilike '{$chave_sd63_c_nome}%' {$strWhere} ",
                                                     $intUnidade,
                                                     $lFiltraServico,
                                                     $chave_rh70_sequencial,
                                                     $lProcSemCBO
                                                   );

            } else if( isset( $sTodos ) && ( trim( $sTodos ) != '' ) ) {

              $sql = $clsau_proccbo->sql_query_func(
                                                     "",
                                                     $campos,
                                                     "sd96_i_anocomp desc, sd96_i_mescomp desc, sd96_i_codigo asc, sd96_i_codigo",
                                                     substr($strWhere, 4),
                                                     $intUnidade,
                                                     $lFiltraServico,
                                                     $chave_rh70_sequencial,
                                                     $lProcSemCBO
                                                   );
            } else {

              $sql = $clsau_proccbo->sql_query_func(
                                                     "",
                                                     $campos,
                                                     "sd96_i_anocomp desc , sd96_i_mescomp desc, sd96_i_codigo asc ",
                                                     substr($sWhereProcedPadrao.$strWhere, 4),
                                                     $intUnidade,
                                                     $lFiltraServico,
                                                     $chave_rh70_sequencial,
                                                     $lProcSemCBO
                                                   );
            }

            $repassa = array();
            if( isset( $chave_sd96_i_procedimento ) ) {
              $repassa = array( "chave_sd96_i_procedimento" => $chave_sd96_i_procedimento );
            }

            if( isset( $nao_mostra ) ) {

              $sSep    = '';
              $aFuncao = explode( '|', $funcao_js );
              $rs      = $clsau_proccbo->sql_record( $sql );

              if( $clsau_proccbo->numrows == 0 ) {
                die('<script>'.$aFuncao[0]."('','Chave(".$chave_sd63_c_procedimento.") não Encontrado');</script>");
              } else {

                db_fieldsmemory( $rs, 0 );
                $sFuncao = $aFuncao[0].'(';

                for ($iCont = 1; $iCont < count($aFuncao); $iCont++) {

                  $sFuncao .= $sSep.'"'.eval('return @$'.$aFuncao[$iCont].';').'"';
                  $sSep     = ', ';
                }

                $sFuncao  = substr($sFuncao, 0, strlen($sFuncao));
                $sFuncao .= ');';
                die("<script>".$sFuncao.'</script>');
              }
            }

            $lAutomatico = isset($lAutomatico) || !isset($lControleOutrasRotinas) ? true : false;
            db_lovrot( $sql, 15, "()", "" ,$funcao_js, "", "NoMe", $repassa, $lAutomatico );
          } else {

            if( $pesquisa_chave != null && $pesquisa_chave != "" ) {

              $result = $clsau_proccbo->sql_record( $clsau_proccbo->sql_query_ext( $pesquisa_chave ) );

              if( $clsau_proccbo->numrows != 0 ) {

                db_fieldsmemory( $result, 0 );
                echo "<script>".$funcao_js."('$sd96_i_procedimento',false);</script>";
              } else {
                echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
              }
            } else {
              echo "<script>".$funcao_js."('',false);</script>";
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
/**
 * Botoão Fechar
 * campoFoco = foco de retorno quando fechar
 */
function js_fechar( campoFoco ) {

  if( campoFoco && campoFoco != undefined || campoFoco != '' ) {

    eval( "parent.document.getElementById('" + campoFoco + "').focus(); " );
    eval( "parent.document.getElementById('" + campoFoco + "').select(); " );
  }

  parent.db_iframe_sau_proccbo.hide();
}

function js_limpar() {

  document.form2.chave_sd63_c_procedimento.value = "";
  document.form2.chave_sd63_c_nome.value         = "";
}

function js_mostrarTodos() {

  document.getElementById('sTodos').value = 'mostrartodos'
  document.form2.submit();
}

$('chave_sd63_c_procedimento').className = 'field-size2';
$('chave_sd63_c_nome').className         = 'field-size7';

js_tabulacaoforms( "form2", "chave_sd96_i_procedimento", true, 1, "chave_sd96_i_procedimento", true );
</script>