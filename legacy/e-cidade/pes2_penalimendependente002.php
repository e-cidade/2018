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

  require_once ("fpdf151/pdf.php");
  require_once ("libs/db_sql.php");
  require_once ("libs/db_utils.php");
  require_once ("libs/db_app.utils.php");

  $oDaoSelecao = db_utils::getDao("selecao");
  $oDaoPensao  = db_utils::getDao("pensao");

  parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

  if (!empty($iMesInicial) && !empty($iAnoInicial)) {

    $head4 = "Período Inicial: " . $iMesInicial . " / " . $iAnoInicial ;
  } else {

    $head4 = "Período Inicial: Não informado";
  }

  if (!empty($iMesFinal) && !empty($iAnoFinal)) {

   $head5 = "Período Final: " . $iMesFinal . " / " . $iAnoFinal ;
  } else {

   $head5 = "Período Final: Não informado";
  }

  $head2 = "Relatório de Pensões por Dependente";
  $head6 = "Matricula(s): " . (!empty($aMatriculas)? (strlen($aMatriculas) > 20 ? substr($aMatriculas, 0, 20) : $aMatriculas) : 'Não Informado');
  $sWhere           = "";
  $sWhereSelecao    = "";
  $sWhereMatriculas = "";
  $sWhereData       = "";

  /**
  * Define a Seleção Selecionada
  */
  if ( trim ( $iSelecao ) != "" ) {

    $rsSelecao = $oDaoSelecao->sql_record ( $oDaoSelecao->sql_query_file ( $iSelecao, db_getsession("DB_instit") ) );

    if ( $oDaoSelecao->numrows > 0 ) {

      db_fieldsmemory($rsSelecao, 0);

      $sWhereSelecao = " and $r44_where \n";
      $head8         = "Seleção: " . $iSelecao . " - " . $r44_descr;
    }
  }

  if ( $sOrdem == 'nome' ) {
    $sOrdem = " nome_servidor ";
  } else if ($sOrdem == 'matricula') {
    $sOrdem = " matricula_servidor ";
  } else {
    $sOrdem = " ano_pensao, mes_pensao, valor, rh01_regist";
  }

  if ( trim ( $aMatriculas ) != "" ) {

    $sWhereMatriculas = "  and r52_regist in ({$aMatriculas}) \n";
  }

  if ( (trim ($iAnoInicial) != "") and (trim ($iMesInicial) != "") ) {

    $sWhereData .= " and ( r52_anousu >= {$iAnoInicial} and r52_mesusu >= {$iMesInicial} )   \n";
  }

  if ( (trim ($iAnoFinal) != "") and (trim ($iMesFinal) != "") ) {

    if ( !empty ( $sWhereData ) ) {

      $sWhereData .= " and ";
    }

    $sWhereData  .= " ( r52_anousu <= {$iAnoFinal} and r52_mesusu <= {$iMesFinal} ) \n";
  }

  $sCampos  = " distinct                                                          \n";
  $sCampos .= " rh01_regist            as matricula_servidor,                     \n";
  $sCampos .= " func.z01_nome          as nome_servidor,                          \n";
  $sCampos .= " r52_numcgm             as cgm_dependente,                         \n";
  $sCampos .= " r52_valor              as salario,                                \n";
  $sCampos .= " r52_valfer             as ferias,                                 \n";
  $sCampos .= " r52_valcom             as complementar,                           \n";
  $sCampos .= " r52_val13              as o13,                                    \n";
  $sCampos .= " r52_valres             as rescisao,                               \n";
  $sCampos .= " cgm.z01_nome           as nome_dependente,                        \n";
  $sCampos .= " (r52_valor + r52_valfer +                                         \n";
  $sCampos .= "  r52_valcom + r52_val13 + r52_valres) as valor,                   \n";
  $sCampos .= " r52_anousu             as ano_pensao,                             \n";
  $sCampos .= " r52_mesusu             as mes_pensao                              \n";

  $sWhere  .= " (r52_valor + r52_valfer + r52_valcom + r52_val13 + r52_valres) > 0 \n";
  $sWhere  .= $sWhereData;
  $sWhere  .= $sWhereSelecao;
  $sWhere  .= $sWhereMatriculas;
  
  $sSql        = $oDaoPensao->sql_query_gerarqbag(null, null, null, null, $sCampos, $sOrdem, $sWhere);
  $rsDAOPensao = $oDaoPensao->sql_record($sSql);

  if ( $oDaoPensao->numrows == 0 ) {

    db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem lancamentos no periodo de ' . $iMesInicial . '/' . $iAnoInicial .' à ' . $iMesFinal . '/' . $iAnoFinal);
  }

  $pdf = new PDF();
  $pdf->Open();
  $pdf->AliasNbPages();
  $alt     = 5;
  $total   = 0;
  $total_g = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial', 'b', 8);

  unset($aPensoes);
  unset($aDadosPensoes);

  $aPensoes = db_utils::getCollectionByRecord($rsDAOPensao);

  $iMatriculaServidor = null;

  foreach ($aPensoes as $oPensao) {

      $aDadosPensoes[$oPensao->matricula_servidor]['sNomeServidor'] = $oPensao->nome_servidor;
      $aDadosPensoes[$oPensao->matricula_servidor]['aDependente'][$oPensao->cgm_dependente]['aPeriodos'][$oPensao->mes_pensao . "/" . $oPensao->ano_pensao]['iSalario'] = $oPensao->salario;
      $aDadosPensoes[$oPensao->matricula_servidor]['aDependente'][$oPensao->cgm_dependente]['aPeriodos'][$oPensao->mes_pensao . "/" . $oPensao->ano_pensao]['iFerias'] = $oPensao->ferias;
      $aDadosPensoes[$oPensao->matricula_servidor]['aDependente'][$oPensao->cgm_dependente]['aPeriodos'][$oPensao->mes_pensao . "/" . $oPensao->ano_pensao]['iComplementar'] = $oPensao->complementar;
      $aDadosPensoes[$oPensao->matricula_servidor]['aDependente'][$oPensao->cgm_dependente]['aPeriodos'][$oPensao->mes_pensao . "/" . $oPensao->ano_pensao]['iDecimo'] = $oPensao->o13;
      $aDadosPensoes[$oPensao->matricula_servidor]['aDependente'][$oPensao->cgm_dependente]['aPeriodos'][$oPensao->mes_pensao . "/" . $oPensao->ano_pensao]['iRescisao'] = $oPensao->rescisao;
      $aDadosPensoes[$oPensao->matricula_servidor]['aDependente'][$oPensao->cgm_dependente]['sNomeDependente'] = $oPensao->nome_dependente;
      $aDadosPensoes[$oPensao->matricula_servidor]['aDependente'][$oPensao->cgm_dependente]['sNomeDependente'] = $oPensao->nome_dependente;
      $aDadosPensoes[$oPensao->matricula_servidor]['aDependente'][$oPensao->cgm_dependente]['aPeriodos'][$oPensao->mes_pensao . "/" . $oPensao->ano_pensao]['fValor'] = $oPensao->valor;
  }

  $lTrocaServidor   = true;
  $lTrocaDependente = true;
  $lTrocaPeriodo    = true;
  $fTotalGeral      = 0;
// echo "<pre>";
// print_r($aDadosPensoes);
// die();
  foreach ($aDadosPensoes as $iIndiceServidor => $oDadosServidor) {

    if ($sQuebra != 'dependente') {

      if ($pdf->gety() > $pdf->h - 30 || $lTrocaServidor == true){

        $pdf->addpage();
        $pdf->setfont('arial', 'b', 7);
        $lTrocaServidor = false;
      }

      $pdf->cell(20, $alt, 'Matrícula'    , 0, 0, "R", 1);
      $pdf->cell(80, $alt, 'Nome Servidor', 0, 0, "L", 1);
      $pdf->cell(92, $alt, ''             , 0, 1, "C", 1);
      $pdf->setfont('arial', '', 7);
      $pdf->cell(20, $alt, $iIndiceServidor                , 0, 0, "R", 0);
      $pdf->cell(80, $alt, $oDadosServidor['sNomeServidor'], 0, 1, "L", 0);
      $lTrocaDependente = true;
      $lTrocaPeriodo    = true;

      if ( $sQuebra == 'servidor' ) {

        $lTrocaServidor = true;
      }
    }

    $fTotalServidor = 0;

    foreach ($oDadosServidor['aDependente'] as $iIndiceDependente => $oDadosDependente) {

      if ($sQuebra == 'dependente') {

        $pdf->addpage();
        $pdf->setfont('arial', 'b', 7);

        $pdf->cell(20, $alt, 'Matrícula'    , 0, 0, "R", 1);
        $pdf->cell(80, $alt, 'Nome Servidor', 0, 0, "L", 1);
        $pdf->cell(92, $alt, ''             , 0, 1, "C", 1);

        $pdf->setfont('arial', '', 7);
        $pdf->cell(20, $alt, $iIndiceServidor                , 0, 0, "R", 0);
        $pdf->cell(80, $alt, $oDadosServidor['sNomeServidor'], 0, 1, "L", 0);
        $lTrocaPeriodo    = true;
        $lTrocaDependente = true;
      }

      if ($pdf->gety() > $pdf->h - 30 || $lTrocaDependente == true) {

         $pdf->setfont('arial', 'b', 7);
         $pdf->cell(70, $alt, 'PENSIONISTA', 0, 0, "R", 1);
         $pdf->cell(25, $alt, 'CGM'        , 0, 0, "R", 1);
         $pdf->cell(97, $alt, 'Nome'       , 0, 1, "L", 1);
         $lTrocaDependente = false;
      }

      $pdf->setfont('arial', '', 7);
      $pdf->cell(95, $alt, $iIndiceDependente                  , 0, 0, "R", 0);
      $pdf->cell(97, $alt, $oDadosDependente['sNomeDependente'], 0, 1, "L", 0);
      $fTotalPeriodo    = 0;
      $lTrocaDependente = true;

      foreach ($oDadosDependente['aPeriodos'] as $iIndicePeriodo => $oDadosPeriodo) {
/*        print_r($oDadosPeriodo);
        die();*/
        if ($pdf->gety() > $pdf->h - 30 || $lTrocaPeriodo == true) {

          $pdf->setfont('arial', 'b', 7);
          $pdf->cell(120, $alt, 'Período'       , 0, 0, "R", 1);
          $pdf->cell(42, $alt, 'Tipo de Folha', 0, 0, "R", 1);
          $pdf->cell(30, $alt, 'Valor'  , 0, 1, "C", 1);
        }

        if($oDadosPeriodo['iSalario'] != '0'){
          $pdf->setfont('arial', '', 7);
          $pdf->cell(120, $alt, $iIndicePeriodo                            , 0, 0, "R", 0);
          $pdf->cell(42, $alt, 'Salário'                                   , 0, 0, "R", 0);
          $pdf->cell(30, $alt, db_formatar($oDadosPeriodo['iSalario'], "f"), 0, 1, "R", 0);  
        }

        if($oDadosPeriodo['iFerias'] != '0'){
          $pdf->setfont('arial', '', 7);
          $pdf->cell(120, $alt, $iIndicePeriodo                           , 0, 0, "R", 0);
          $pdf->cell(42, $alt, 'Férias'                                   , 0, 0, "R", 0);
          $pdf->cell(30, $alt, db_formatar($oDadosPeriodo['iFerias'], "f"), 0, 1, "R", 0);  
        }

        if($oDadosPeriodo['iComplementar'] != '0'){
          $pdf->setfont('arial', '', 7);
          $pdf->cell(120, $alt, $iIndicePeriodo                                 , 0, 0, "R", 0);
          $pdf->cell(42, $alt, 'Complementar'                                   , 0, 0, "R", 0);
          $pdf->cell(30, $alt, db_formatar($oDadosPeriodo['iComplementar'], "f"), 0, 1, "R", 0);  
        }

        if($oDadosPeriodo['iDecimo'] != '0'){
          $pdf->setfont('arial', '', 7);
          $pdf->cell(120, $alt, $iIndicePeriodo                           , 0, 0, "R", 0);
          $pdf->cell(42, $alt, 'Décimo Terceiro'                          , 0, 0, "R", 0);
          $pdf->cell(30, $alt, db_formatar($oDadosPeriodo['iDecimo'], "f"), 0, 1, "R", 0);  
        }

        if($oDadosPeriodo['iRescisao'] != '0'){
          $pdf->setfont('arial', '', 7);
          $pdf->cell(120, $alt, $iIndicePeriodo                             , 0, 0, "R", 0);
          $pdf->cell(42, $alt, 'Rescisão'                                   , 0, 0, "R", 0);
          $pdf->cell(30, $alt, db_formatar($oDadosPeriodo['iRescisao'], "f"), 0, 1, "R", 0);  
        }

        $fTotalPeriodo = $fTotalPeriodo + $oDadosPeriodo['fValor'];
      }

      if (count($oDadosDependente['aPeriodos']) > 1) {

        $lTrocaPeriodo = true;
        $pdf->setfont('arial', 'B', 7);
        $pdf->cell(110,$alt,'TOTAL DO PERÍODO'            , "B", 0, "R", 1);
        $pdf->cell(82,$alt,db_formatar($fTotalPeriodo,"f"), "B", 1, "R", 1);
      }
      $fTotalServidor = $fTotalServidor + $fTotalPeriodo;
    }

    $pdf->setfont('arial', 'B', 7);
    $pdf->cell(31,$alt,'TOTAL DO SERVIDOR'              , "B", 0, "R", 1);
    $pdf->cell(161,$alt,db_formatar($fTotalServidor,"f"), "B", 1, "R", 1);
    $fTotalGeral = $fTotalGeral + $fTotalServidor;

    $pdf->ln(5);
  }

  $pdf->ln(3);
  $pdf->setfont('arial', 'B', 7);
  $pdf->cell(31,$alt,'TOTAL GERAL'                 , "B", 0, "R", 1);
  $pdf->cell(161,$alt,db_formatar($fTotalGeral,"f"), "B", 1, "R", 1);

  $pdf->Output();