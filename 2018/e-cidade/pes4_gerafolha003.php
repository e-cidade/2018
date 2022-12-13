<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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

/**
 * Constantes dos Tipos de Calculo.
 * Sobre   : Geralmente refernciada ao numero da passagem do cálculo, pois ele feito um "FOR" fixo no fonte
 *           pes4_gerafolha003.php
 *
 * Variável: $icalc
 */
define("CALCULO_NORMAL",  1);
define("CALCULO_PENSAO",  2);
define("CALCULO_AJUSTES", 3);

/**
 * Constantes com os Tipos de Folha de Pagamento
 * Sobre   : Geralmente Utilizada como flag para o tipo de cálculo executado, com isso sabemos por exemplo se
 *           o estamos em um cálculo de salário, férias ou ponto fixo
 * Variável: com $opcao_geral
 */
define("PONTO_SALARIO",             1);
define("PONTO_ADIANTAMENTO",        2);
define("PONTO_FERIAS",              3);
define("PONTO_RESCISAO",            4);
define("PONTO_13_SALARIO",          5);
define("PONTO_COMPLEMENTAR",        8);
define("PONTO_FIXO",               10);
define("PONTO_PROVISAO_FERIAS",    11);
define("PONTO_PROVISAO_13_SALARIO",12);

/**
 * Tipo de cálculo financeiro.
 *
 * Sobre   : Valida se foi utilizado cálculo geral, ou parcial(escolha de registros a serem calculados)
 * Variavel: $opcao_tipo
 */
define('TIPO_CALCULO_GERAL',   2);
define('TIPO_CALCULO_PARCIAL', 1);

 function pes4_geracalculo003($calcula_parcial=null,$calcula_pensao=null) {
   
   $r110_regisi = 1;
   $r110_regisf = 999999;
   flush();

   LogCalculoFolha::write();
   LogCalculoFolha::write("==============================Iniciando Cálculo Financeiro==============================");
   LogCalculoFolha::write("Calcula Parcial:".$calcula_parcial);
   LogCalculoFolha::write("Calcula Pensao.:".$calcula_pensao);
   /**
    * Globais utilizadas
    */
   global $diversos;
   global $chamada_geral_arquivo;
   global $minha_calcula_pensao;
   global $carregarubricas_geral;
   global $subpes;
   global $cfpess;
   global $carregarubricas;
   global $chamada_geral;
   global $quais_diversos;
   global $db_debug;
   global $db21_codcli;
   global $r110_regisi;
   global $r110_regisf;
   global $opcao_filtro;
   global $faixa_lotac;
   global $faixa_regis;
   global $r110_lotaci;
   global $r110_lotacf;
   global $opcao_gml;
   global $opcao_geral;
   global $opcao_tipo;
   global $lotacao_faixa;
   global $quais_diversos;
   global $db_debug;
   global $db21_codcli;
   global $cfpess;

   if ($db_debug == "true"){
     $db_debug = true;
     echo " <br> [pes4_geracalculo003] CALCULO ESTÁ SENDO EXECUTADO COM DEBUG. <BR><BR><BR>";
   } else {
     $db_debug = false;
   }

   $pcount = func_get_args();

   if ($opcao_gml == 'g') {
     $opcao_tipo = 2; // tipo geral
     $r110_regisi = 1;
     $r110_regisf = 999999;
     $r110_lotaci = "0000";
     $r110_lotacf = "0000";
   } else {
     $opcao_tipo = 1; // tipo parcial
   }

   if (isset($lotacao_faixa) && trim($lotacao_faixa) <> "") {

     global $rhlota;
     $faixa_lotacao = "";
     $faixa_lotac = str_replace("\\","",$lotacao_faixa);
     $condicaoaux = " where r70_instit = ".db_getsession("DB_instit")." and r70_estrut in (".$faixa_lotac.")" ;
     db_selectmax( "rhlota", "select r70_codigo from rhlota $condicaoaux ");
     $separa = " ";

     for ($Irhlota=0;$Irhlota < count($rhlota);$Irhlota++) {
       $faixa_lotacao .= $separa.$rhlota[$Irhlota]["r70_codigo"];
       $separa = ",";
     }

     $faixa_lotac = $faixa_lotacao;

   }

 if (count($pcount) == 0) {

   LogCalculoFolha::write("Executando quando parâmetros não foram passados.");

   //echo "<BR> --------------------------------------------Primeira vez-------------------------------------";
   $chamada_geral         = "n";
   $chamada_geral_arquivo = "";
   $calcula_pensao        = "n";
 } else {

    if ($calcula_pensao == "n") {

     if ($calcula_parcial != " ") {

       //echo "<BR> --------------------------------------------Segunda vez 1-----------------------------------";
       $r110_regisi = 1;
       $r110_regisf = 999999;
       $r110_lotaci = db_substr($calcula_parcial,7,4);
       $r110_lotacf = db_substr($calcula_parcial,11,4);
       $chamada_geral = "a";
     } else {

         //echo "<BR> ------------------------------------------Segunda vez 2-----------------------------------";
       $opcao_filtro = "i";
       if ($opcao_gml == "m") {
         $r110_regisi = 1;
         $r110_regisf = 999999;
         $r110_lotaci = "0000";
         $r110_lotacf = "0000";
       } else if ($opcao_gml == "l") {
         $r110_regisi = 0;
         $r110_regisf = 0;
         $r110_lotaci = "0000";
         $r110_lotacf = "9999";
       }
       $chamada_geral = "s";
     }
    } else {
      //echo "<BR> --------------------------------------------Segunda vez 3-----------------------------------";
      $chamada_geral = "p";
    }
 }

  global $valor_salario_familia, $xvalor_salario_familia,$campos_pessoal,$siglap,$siglag,$quant_formq;
  global $sigla_ajuste, $dias_do_mes, $naoencontroupontosalario, $rubrica_licenca_saude, $rubrica_acidente, $situacao_funcionario;
  global $dias_pagamento, $rubrica_maternidade, $valor_salario_familia, $xvalor_salario_familia, $inssirf_base_ferias;
  global $inssirf_base_ferias_total, $r06_form, $F006_clt, $dtot_vpass, $dperc_pass, $dquant_pass, $dias_pagamento_sf, $ultdat;

  $F006_clt                  = 0;
  $dtot_vpass                = 0;
  $dperc_pass                = 0;
  $dquant_pass               = 0;
  $dias_pagamento_sf         = 0;
  $ultdat                    = db_ctod(db_str(ndias(per_fpagto(1)),2,0,'0')."/".per_fpagto(1));

  $campos_pessoal            = "RH02_ANOUSU as r01_anousu,
                                RH02_MESUSU as r01_mesusu,
                                RH01_REGIST as r01_regist,
                                RH01_NUMCGM as r01_numcgm,
                                trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,
                                RH01_ADMISS as r01_admiss,
                                RH05_RECIS as r01_recis,
                                RH02_tbprev as r01_tbprev,
                                RH30_REGIME as r01_regime,
                                RH30_VINCULO as r01_tpvinc,
                                RH02_salari as r01_salari,
                                RH03_PADRAO as r01_padrao,
                                RH02_HRSSEM as r01_hrssem,
                                RH02_HRSMEN as r01_hrsmen,
                                RH01_NASC as r01_nasc,
                                rh65_rubric as r01_rubric,
                                rh65_valor as r01_arredn,
                                RH02_EQUIP  as r01_equip,
                                RH01_PROGRES as r01_anter,
                                RH01_TRIENIO as r01_trien,
                                (case when RH01_PROGRES IS NOT NULL then 'S' else 'N' end) as r01_progr,
                                RH15_DATA as r01_fgts,
                                RH05_CAUSA as r01_causa,
                                RH05_CAUB as r01_caub,
                                RH05_MREMUN as r01_mremun,
                                RH01_FUNCAO as r01_funcao,
                                RH01_CLAS1 as r01_clas1,
                                RH01_CLAS2 as r01_clas2,
                                RH02_TPCONT as r01_tpcont,
                                RH02_OCORRE as r01_ocorre,
                                rh51_b13fo as r01_b13fo,
                                rh51_basefo as r01_basefo,
                                rh51_descfo as r01_descfo,
                                rh51_d13fo as r01_d13fo,
                                RH02_TIPSAL as r01_tipsal,
                                RH19_PROPI as r01_propi ,
                                rh01_depirf as r01_depirf,
                                rh01_vale as r01_vale,
                                rh01_depsf as r01_depsf,
                                rh02_codreg,
                                rh02_portadormolestia";

  $dias_do_mes               = ndias( db_substr( $subpes,6,2)."/".db_substr( $subpes,1,4) );
  $naoencontroupontosalario  = false;
  $rubrica_licenca_saude     = bb_space(4);
  $rubrica_acidente          = bb_space(4);

  $situacao_funcionario      = 1;
  $dias_pagamento            = 30;
  $rubrica_maternidade       = "xxxx";
  $valor_salario_familia     = 0;
  $xvalor_salario_familia    = 0;
  $inssirf_base_ferias       = "B002";
  $inssirf_base_ferias_total = "B977";

  if ( $opcao_geral == PONTO_SALARIO) {

    $sigla                 = "r10_";
    $sigla1                = "r14_";
    $qual_ponto            = "pontofs";
    $chamada_geral_arquivo = "gerfsal";

  } else if( $opcao_geral == PONTO_COMPLEMENTAR) {

    $sigla                 = "r47_";
    $sigla1                = "r48_";
    $qual_ponto            = "pontocom";
    $chamada_geral_arquivo = "gerfcom";

  } else if( $opcao_geral == PONTO_ADIANTAMENTO) {

    $sigla                 = "r21_";
    $sigla1                = "r22_";
    $qual_ponto            = "pontofa";
    $chamada_geral_arquivo = "gerfadi";

  } else if( $opcao_geral == PONTO_FERIAS) {

    $sigla                 = "r29_";
    $sigla1                = "r31_";
    $qual_ponto            = "pontofe";
    $chamada_geral_arquivo = "gerffer";

  } else if( $opcao_geral == PONTO_RESCISAO) {

    $sigla                 = "r19_";
    $sigla1                = "r20_";
    $qual_ponto            = "pontofr";
    $chamada_geral_arquivo = "gerfres";

  } else if( $opcao_geral == PONTO_13_SALARIO) {

    $sigla                 = "r34_";
    $sigla1                = "r35_";
    $qual_ponto            = "pontof13";
    $chamada_geral_arquivo = "gerfs13";

  } else if( $opcao_geral == PONTO_FIXO) {

    $sigla                 = "r90_";
    $sigla1                = "r53_";
    $qual_ponto            = "pontofx";
    $chamada_geral_arquivo = "gerffx";

  } else if( $opcao_geral == PONTO_PROVISAO_FERIAS) {

    $sigla                 = "r91_";
    $sigla1                = "r93_";
    $qual_ponto            = "pontoprovfe";
    $chamada_geral_arquivo = "gerfprovfer";

  } else if( $opcao_geral == PONTO_PROVISAO_13_SALARIO) {

    $sigla                 = "r92_";
    $sigla1                = "r94_";
    $qual_ponto            = "pontoprovf13";
    $chamada_geral_arquivo = "gerfprovs13";

  }

  $siglap = $sigla;
  $siglag = $sigla1;

  global $mes,$ano;
  $mes = db_month( $cfpess[0]["r11_datai"]);
  $ano = db_year( $cfpess[0]["r11_datai"]);

  global $func_em_ferias;

  $func_em_ferias = false;

  if ( $chamada_geral == "n") {

    global $ajusta;
    $ajusta = false ;

    $aTipoCalculoAjuste = array(PONTO_SALARIO,
                                PONTO_COMPLEMENTAR,
                                PONTO_RESCISAO,
                                PONTO_FERIAS,
                                PONTO_13_SALARIO);
    if( in_array($opcao_geral, $aTipoCalculoAjuste) ) {
      $ajusta = true;
    }

    if( $opcao_tipo == TIPO_CALCULO_GERAL || $opcao_tipo == TIPO_CALCULO_PARCIAL ) {

      // Geral

      if ($opcao_tipo == TIPO_CALCULO_GERAL ) {

        switch ($opcao_geral){

        case PONTO_SALARIO:

          $sFolhaPagamento = CalculoFolha::CALCULO_SALARIO;
          $sPonto          = Ponto::SALARIO;

          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da gerfsal, Condição: ".bb_condicaosubpes("r14_")." ...<br>";
          }              

          db_delete( "gerfsal", bb_condicaosubpes("r14_", "pontofs"));
          
          $stringferias  = "('".$cfpess[0]["r11_ferias"]."','".$cfpess[0]["r11_fer13"]."','";
          $stringferias .= $cfpess[0]["r11_fer13a"]."','".$cfpess[0]["r11_ferabo"]."','";
          $stringferias .= $cfpess[0]["r11_feradi"]."','".$cfpess[0]["r11_ferant"]."','";
          $stringferias .= $cfpess[0]["r11_feabot"]."','".$cfpess[0]["r11_fadiab"]."')";
          $condicaoaux = " and ( r10_rubric in  " . $stringferias;

          if ( $db21_codcli == 54 ) {
            $condicaoaux .= " or r10_rubric in ('0270') ";
          }

          $condicaoaux .= "  or r10_rubric between '2000' and '3999' )";

          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da pontofs, Condição: ".bb_condicaosubpes("r14_").$condicaoaux." ...<br>";
          }
          db_delete( "pontofs", bb_condicaosubpes("r10_").$condicaoaux );

          if ($db_debug) {
            echo "[pes4_geracalculo003] Chamando função deleta_ajustes_calculogeral(s)...<br>";
          }
          deleta_ajustes_calculogeral("s");
          break;

        case PONTO_ADIANTAMENTO:

          $sFolhaPagamento = CalculoFolha::CALCULO_ADIANTAMENTO;
          $sPonto          = Ponto::ADIANTAMENTO;

          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da gerfadi, Condição: ".bb_condicaosubpes("r22_")." ...<br>";
          }

          db_delete( "gerfadi", bb_condicaosubpes("r22_") ) ;
          break;

        case PONTO_FERIAS:

          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da gerffer, Condição: ".bb_condicaosubpes("r31_")." ...<br>";
          }
          db_delete( "gerffer", bb_condicaosubpes("r31_")  ) ;

          if ($db_debug) {
            echo "[pes4_geracalculo003] Chamando função deleta_ajustes_calculogeral(f)...<br>";
          }
          deleta_ajustes_calculogeral("f");
          deleta_ajustes_calculogeral("c");
          deleta_ajustes_calculogeral("s");

          break;

        case PONTO_RESCISAO:

          $sFolhaPagamento = CalculoFolha::CALCULO_RESCISAO;
          $sPonto          = Ponto::ADIANTAMENTO;
          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da gerfres, Condição: ".bb_condicaosubpes("r20_")." ...<br>";
          }
          db_delete( "gerfres", bb_condicaosubpes("r20_") );

          if ($db_debug) {
            echo "[pes4_geracalculo003] Chamando função deleta_ajustes_calculogeral(r)...<br>";
          }
          deleta_ajustes_calculogeral("r");

          break;

        case PONTO_13_SALARIO:

          $sFolhaPagamento = CalculoFolha::CALCULO_13o;
          $sPonto          = Ponto::PONTO_13o;
          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da gerfs13, Condição: ".bb_condicaosubpes("r35_")." ...<br>";
          }
          db_delete( "gerfs13", bb_condicaosubpes("r35_") ) ;

          if ($db_debug) {
            echo "[pes4_geracalculo003] Chamando função deleta_ajustes_calculogeral(3)...<br>";
          }
          deleta_ajustes_calculogeral("3");

          break;
        case PONTO_COMPLEMENTAR:

          $sFolhaPagamento = CalculoFolha::CALCULO_COMPLEMENTAR;
          $sPonto          = Ponto::COMPLEMENTAR;
          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da gerfadi, Condição: ".bb_condicaosubpes("r22_")." ...<br>";
          }
          db_delete( "gerfcom", bb_condicaosubpes("r48_") )  ;
          $stringferias  = "('".$cfpess[0]["r11_ferias"]."','".$cfpess[0]["r11_fer13"]."','";
          $stringferias .= $cfpess[0]["r11_fer13a"]."','".$cfpess[0]["r11_ferabo"]."','";
          $stringferias .= $cfpess[0]["r11_feradi"]."','".$cfpess[0]["r11_ferant"]."','";
          $stringferias .= $cfpess[0]["r11_feabot"]."','".$cfpess[0]["r11_fadiab"]."')";
          $condicaoaux = " and ( r47_rubric in ".$stringferias;
          if( $db21_codcli == 54 ){
            $condicaoaux .= " or r47_rubric in ('0270') ";
          }
          $condicaoaux .= "  or r47_rubric between '2000' and '3999' )";

          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da pontocom, Condição: ".bb_condicaosubpes("r22_").$condicaoaux." ...<br>";
          }
          db_delete( "pontocom", bb_condicaosubpes("r47_").$condicaoaux );

          if ($db_debug) {
            echo "[pes4_geracalculo003] Chamando função deleta_ajustes_calculogeral(c)...<br>";
          }
          deleta_ajustes_calculogeral("c");

          break;

        case PONTO_FIXO:

          $sFolhaPagamento = CalculoFolha::CALCULO_PONTO_FIXO;
          $sPonto          = Ponto::FIXO;

          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da gerffx, Condição: ".bb_condicaosubpes("r53_")." ...<br>";
          }
          db_delete( "gerffx", bb_condicaosubpes("r53_") )  ;

          break;

        case PONTO_PROVISAO_FERIAS:

          $sFolhaPagamento = CalculoFolha::CALCULO_PROVISAO_FERIAS;
          $sPonto          = Ponto::PROVISAO_FERIAS;
          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da gerfprovfer, Condição: ".bb_condicaosubpes("r93_")." ...<br>";
          }
          db_delete( "gerfprovfer", bb_condicaosubpes("r93_") )  ;

          break;

        case PONTO_PROVISAO_13_SALARIO:

          $sFolhaPagamento = CalculoFolha::CALCULO_PROVISAO_13o;
          $sPonto          = Ponto::PROVISAO_13o;;
          if ($db_debug) {
            echo "[pes4_geracalculo003] Excluindo dados da gerfprovs13, Condição: ".bb_condicaosubpes("r94_")." ...<br>";
          }
          db_delete( "gerfprovs13", bb_condicaosubpes("r94_") )  ;

          break;

        }
        $calcula_pensao = "n"  ;
      }

      $matriz1 = array();
      $matriz2 = array();
      $matriz1[ 1 ] = "r60_altera";
      $matriz2[ 1 ] = 'f';
      LogCalculoFolha::write("Alterando os dados da tabela previden, Condição: ".bb_condicaosubpes("r60_"));
      if ($db_debug) {
        echo "[pes4_geracalculo003] Alterando os dados da tabela previden, Condição: ".bb_condicaosubpes("r60_")." ...<br>";
        echo "[pes4_geracalculo003] Campos:";
        print_r($matriz1);
        print_r($matriz2);
        echo "<br><br>";
      }

      if( $opcao_tipo == TIPO_CALCULO_PARCIAL ) {

         $sWhereMatriculasCalculadas = " and r60_regist in ({$faixa_regis})";
         db_update("previden", $matriz1, $matriz2, bb_condicaosubpes("r60_") . $sWhereMatriculasCalculadas  );

       }else{
         $sWhereMatriculasCalculadas = '';
         db_update("previden", $matriz1, $matriz2, bb_condicaosubpes("r60_") );
       }

      $matriz1 = array();
      $matriz2 = array();
      $matriz1[ 1 ] = "r61_altera";
      $matriz2[ 1 ] = 'f';
      LogCalculoFolha::write("Alterando os dados da tabela ajusteir, Condição: ".bb_condicaosubpes("r61_"));
      if ($db_debug) {
        echo "[pes4_geracalculo003] Alterando os dados da tabela ajusteir, Condição: ".bb_condicaosubpes("r61_")." ...<br>";
        echo "[pes4_geracalculo003] Campos:";
        print_r($matriz1);
        print_r($matriz2);
        echo "<br><br>";
      }

      if( $opcao_tipo == TIPO_CALCULO_PARCIAL ) {
        $sWhereMatriculasCalculadas = " and r61_regist in ({$faixa_regis})";
        db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_") . $sWhereMatriculasCalculadas  );
      }else{
        db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_") );
      }

      $minha_calcula_pensao = false;


      /**
       * Neste ponto o cálculo executado 3x, sendo:
       *   1º - Executa o cálculo de forma normal, sem maiores peculiaridades.
       *   2º - Executa o cálculo, executando tambem o cálculo de pensões
       *   3º - Executa o cálculo, executando também os ajustes para IRRF e Previdências
       */
      for ($icalc = 1; $icalc < 3; $icalc++) {

      if ($icalc == CALCULO_NORMAL) {

        if ($qual_ponto == "pontof13") {
          $condicaoaux = " and ".$siglap."rubric = ".db_sqlformat( db_str( db_val( $cfpess[0]["r11_palime"] )+4000, 4,0) );
        } else {
          $condicaoaux  = " and ( ".$siglap."rubric = ".db_sqlformat( $cfpess[0]["r11_palime"] );
          $condicaoaux .= "   or ".$siglap."rubric = ".db_sqlformat( db_str( db_val( $cfpess[0]["r11_palime"] )+2000, 4,0) ).")";
        }

        db_delete( $qual_ponto, bb_condicaosubpes( $siglap ) . $condicaoaux );
      }

      if ( $icalc == CALCULO_PENSAO && $opcao_geral != PONTO_ADIANTAMENTO && $opcao_geral != PONTO_FIXO && $opcao_geral != 11 && $opcao_geral != 12 ) {

        if ($db_debug) {
          echo "[pes4_geracalculo003] <br>";
          echo "[pes4_geracalculo003] Chamando a função calc_pensao($icalc,$opcao_geral,$opcao_tipo,$chamada_geral_arquivo)... <br>";
          echo "[pes4_geracalculo003] <br>";
        }

        calc_pensao($icalc, $opcao_geral, $opcao_tipo, $chamada_geral_arquivo);
        $calcula_pensao = "s";
      }

      //echo "<BR> pensao 9 ->  $chamada_geral_arquivo     volta --> $icalc";
      if ($opcao_tipo == 2) { // tipo Geral

        if ( ( ($opcao_geral != PONTO_FIXO && $opcao_geral != 11 && $opcao_geral != 12 )
          || ( ($opcao_geral == PONTO_FIXO || $opcao_geral == 11 || $opcao_geral == 12 ) && $icalc == CALCULO_NORMAL)) ) {

            $calcula_parcial = " ";
            //echo "<BR> entrou pes4_geracalculo003";

            if ($db_debug) {
              echo "<br>";
              echo "[pes4_geracalculo003]------------------------------------------------------------------------------------------------------<br>";
              echo "[pes4_geracalculo003] 1 - chamando novamente a função pes4_geracalculo003() com os parâmetros calcula_parcial = {$calcula_parcial} e calcula_pensao = {$calcula_pensao} <br>";
              echo "[pes4_geracalculo003]------------------------------------------------------------------------------------------------------<br>";
              echo "<br>";
            }
            pes4_geracalculo003($calcula_parcial,$calcula_pensao);

          }

      } else { // Tipo Parcial

        if ($icalc == CALCULO_NORMAL || ($icalc == CALCULO_PENSAO && $minha_calcula_pensao)) {
         
          $r110_regisi = 1;
          $r110_regisf = 999999;
          $calcula_parcial = db_str($r110_regisi+0,6,0,"0").$r110_lotaci.$r110_lotacf.db_str($r110_regisf+0,6,0,"0");
          if ($db_debug) {
            echo "<br>";
            echo "[pes4_geracalculo003]------------------------------------------------------------------------------------------------------<br>";
            echo "[pes4_geracalculo003] 2 - chamando novamente a função pes4_geracalculo003() com os parâmetros calcula_parcial = {$calcula_parcial} e calcula_pensao = n <br>";
            echo "[pes4_geracalculo003]------------------------------------------------------------------------------------------------------<br>";
            echo "<br>";
          }
          pes4_geracalculo003($calcula_parcial,"n");
        }
      }

      if ($ajusta) { 
        // R985 BASE DE PREVIDENCIA
        // R986 BASE PREVIDENCIA (13O SAL)
        // R987 BASE PREVIDENCIA S/FERIAS
        // R981 BASE IRF SALARIO
        // R982 BASE IRF 13O SAL (BRUTA) BASE -
        // R983 BASE IRF FERIAS BASE -
        $aFolhas  = array(PONTO_COMPLEMENTAR, PONTO_SALARIO, PONTO_RESCISAO);
        $y1 = ( in_array($opcao_geral, $aFolhas) ? 1: ( $opcao_geral == PONTO_13_SALARIO ? 2: 3 ) );

        if ($icalc == CALCULO_PENSAO && $opcao_geral != PONTO_RESCISAO) {

          $aFolhas  = array(PONTO_COMPLEMENTAR, PONTO_SALARIO, PONTO_RESCISAO);
          $rubrica1 = ( ($opcao_geral == PONTO_SALARIO || $opcao_geral ==PONTO_RESCISAO ) ? "R985": ( $opcao_geral == PONTO_13_SALARIO ? "R986": "R987" ) );
          $rubrica  =  in_array($opcao_geral, $aFolhas) ? "R981" : ($opcao_geral == PONTO_13_SALARIO ? "R982" : "R983");

          LogCalculoFolha::write();
          LogCalculoFolha::write("Chamando Função de Ajuste de Previdencia");
          global $pessoal;

          if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() && ($opcao_geral == PONTO_SALARIO || $opcao_geral == PONTO_COMPLEMENTAR) ) {

            /**
             * Percorrer os servidores
             */
            foreach ($pessoal as $aDadosServidor) {

               $oServidor = ServidorRepository::getInstanciaByCodigo($aDadosServidor["r01_regist"], DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
               AjustePrevidencia::gravarDadosCalculados($oServidor, CalculoFolha::$oFolhaAtual);
            }
          }
         
          /**
           * Para executar testes sem ajuste de Previdencia, o comando a baixo deve ser comentado
           */
          ajusta_previdencia( $chamada_geral_arquivo, $rubrica1, $y1, $sigla1); //Pos processamento dos dados da previdencia
          LogCalculoFolha::write();
          LogCalculoFolha::write("Chamando Função de Ajuste de IRRF - Para a Rubrica: $rubrica");
          /**
           * Para executar testes sem ajuste de Imposto de Renda, o comando a baixo deve ser comentado
           */
          AjusteIRRF::ajustar($chamada_geral_arquivo, $rubrica,$y1 ,$sigla1); //pós-processamento dos dados de IRRF
        }

        if ( $icalc == CALCULO_PENSAO ) {

          /**
           * Executa a Criação do Abono de Permanencia
           */
          global $pessoal;
          foreach ($pessoal as $aDadosServidor) {

            $oServidor = ServidorRepository::getInstanciaByCodigo($aDadosServidor["r01_regist"], DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());

            /**
             * Se o Servidor não possuir abono de permanência não executo o Calculo de Abono de Permanência.
             */
            if (!$oServidor->hasAbonoPermanencia()) {
              continue;
            }

            $oCalculo = $oServidor->getCalculoFinanceiro($chamada_geral_arquivo);

            $oCalculoPrevidencia = new CalculoDescontoPrevidencia($oCalculo);

            LogCalculoFolha::write('Lançando o abono permanência no cálculo de :'.$chamada_geral_arquivo);
            LogCalculoFolha::write('Lançando para a matrícula: '.$oServidor->getMatricula());
            $oCalculoPrevidencia->lancarAbonoPermanencia();
          }
        }


        if ( ( $icalc == CALCULO_PENSAO ) && $opcao_geral == PONTO_SALARIO ) {

          if($db21_codcli == 17){

            if( $opcao_tipo == 1 ) {
              global $pessoal_;

              $condicaoaux  = " and rh02_regist = ".db_sqlformat( $r110_regisi );
              $condicaoaux .= " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
              $condicaoaux .= " order by rh02_regist ";
              db_selectmax("pessoal_", "select rh02_regist as r01_regist,
                trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,
                rh05_recis as r01_recis
                from rhpessoalmov
                inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                and rhlota.r70_instit         = rhpessoalmov.rh02_instit
                left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes ".bb_condicaosubpes("rh02_" ).$condicaoaux );
            } else {

              global $pessoal_;

              $condicaoaux  = "  and r10_rubric in ('0053','0055','0067') ";
              $condicaoaux .= " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat( db_ctod("01/".db_substr($subpes,6,2)."/".db_substr($subpes,1,4))).")";
              $condicaoaux .= " order by rh02_regist ";
              db_selectmax("pessoal_", "select distinct(rh02_regist),
                r10_regist,
                rh02_regist as r01_regist,
                rh05_recis as r01_recis,
                trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac
                from rhpessoalmov
                inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                and rhlota.r70_instit           = rhpessoalmov.rh02_instit
                inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                left join rhpesrescisao on rhpesrescisao.rh05_seqpes   = rhpessoalmov.rh02_seqpes
                left join rhpespadrao   on rhpespadrao.rh03_seqpes     = rhpessoalmov.rh02_seqpes
                left outer join pontofs on r10_regist                  = rhpessoalmov.rh02_regist
                and r10_anousu                  = rhpessoalmov.rh02_anousu
                and r10_mesusu                  = rhpessoalmov.rh02_mesusu
                and r10_instit                  = rhpessoalmov.rh02_instit
                ".bb_condicaosubpes("rh02_" ).$condicaoaux );

            }

          }

          $tira_branco = trim($cfpess[0]["r11_desliq"]);
          if ( !db_empty( $tira_branco )) {

            global $rubricas_in;
            $rubricas_in = "(";
            for($ix=0;$ix < strlen( trim($cfpess[0]["r11_desliq"]) );$ix+=4){
              $rubrica_desconto = db_substr( trim($cfpess[0]["r11_desliq"]), $ix+1, 4 ) ;

              $calcula_valor = "calcula_valor_".$rubrica_desconto ;
              global $$calcula_valor;
              $$calcula_valor = false;

              $rubricas_in .= "'".$rubrica_desconto."',";
            }
            $rubricas_in = db_substr($rubricas_in,1,strlen($rubricas_in)-1 ).")";

            //echo "<BR> 3 rubricas_in --> $rubricas_in";exit;

            global $pessoal_;

            $condicaoaux  ="  and r10_rubric in ".$rubricas_in;
            $condicaoaux .="   and r10_regist is not null ";
            if( $opcao_tipo == 1 ){
              $condicaoaux  .= " and rh02_regist = ".db_sqlformat( $r110_regisi );
            }
            $condicaoaux .= " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat( db_ctod("01/".db_substr($subpes,6,2)."/".db_substr($subpes,1,4))).")";
            $condicaoaux .= " order by rh02_regist ";
            db_selectmax("pessoal_", "select distinct(rh02_regist),
              r10_regist,
              rh02_regist as r01_regist,
              rh05_recis as r01_recis,
              trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac
              from rhpessoalmov
              inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
              inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
              and rhlota.r70_instit         = rhpessoalmov.rh02_instit
              inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
              left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
              left outer join pontofs on r10_regist                = rhpessoalmov.rh02_regist
              and r10_anousu                = rhpessoalmov.rh02_anousu
              and r10_mesusu                = rhpessoalmov.rh02_mesusu
              ".bb_condicaosubpes("rh02_" ).$condicaoaux );


            for($Ipes=0;$Ipes<count($pessoal_);$Ipes++){

              if ($db_debug == true) {
                echo "[pes4_geracalculo003] entrando calculos_desconto_liquido_generico_ajuste()<br>";
              }
              calculos_desconto_liquido_generico_ajuste( $pessoal_[$Ipes]["r01_regist"], $pessoal_[$Ipes]["r01_lotac"] );
              //echo "<BR> saiu do calculos_desconto_liquido_generico_ajuste()";
            }

          }

        }

        if ( $opcao_geral == PONTO_RESCISAO && $icalc == CALCULO_PENSAO ) { //CALCULO_AJUSTES ) {

          LogCalculoFolha::write("\nChamando Função de Ajuste de Previdencia - Para a Rubrica: R985 - Salário");
          ajusta_previdencia( $chamada_geral_arquivo, 'R985', $y1, $sigla1);
          LogCalculoFolha::write("\nChamando Função de Ajuste de Previdencia - Para a Rubrica: R986 - Férias");
          ajusta_previdencia( $chamada_geral_arquivo, 'R986', $y1, $sigla1);
          LogCalculoFolha::write("\nChamando Função de Ajuste de Previdencia - Para a Rubrica: R987 - 13º Salário");
          ajusta_previdencia( $chamada_geral_arquivo, 'R987', $y1, $sigla1);

          LogCalculoFolha::write("\nChamando Função de Ajuste de IRRF        - Para a Rubrica: R981 - Salário");
          AjusteIRRF::ajustar($chamada_geral_arquivo, 'R981',$y1 ,$sigla1);
          LogCalculoFolha::write("\nChamando Função de Ajuste de IRRF        - Para a Rubrica: R982 - Férias");
          AjusteIRRF::ajustar($chamada_geral_arquivo, 'R982',$y1 ,$sigla1);
          LogCalculoFolha::write("\nChamando Função de Ajuste de IRRF        - Para a Rubrica: R983 - 13º Salário");
          AjusteIRRF::ajustar($chamada_geral_arquivo, 'R983',$y1 ,$sigla1);
        }
      }

      if ( $opcao_geral == PONTO_ADIANTAMENTO ) {
        break;
      }

    }

    return;
    }

  }

  switch ($opcao_geral) {

    case PONTO_SALARIO:
    case PONTO_COMPLEMENTAR:

      if ($db_debug) {
         echo "Chamando a função gerfsal($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfsal($opcao_geral,$opcao_tipo);

    break;

    case PONTO_ADIANTAMENTO:

      if ($db_debug) {
        echo "Chamando a função gerfadi($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfadi($opcao_geral,$opcao_tipo);

    break;

    case PONTO_FERIAS:

      if ($db_debug) {
        echo "Chamando a função gerffer($opcao_geral,$opcao_tipo).... <br>";
      }
      gerffer($opcao_geral,$opcao_tipo);

    break;

    case PONTO_RESCISAO:

      if ($db_debug) {
        echo "Chamando a função gerfres($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfres($opcao_geral,$opcao_tipo);

    break;

    case PONTO_13_SALARIO:

      if ($db_debug) {
        echo "Chamando a função gerfs13($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfs13($opcao_geral,$opcao_tipo);

    break;

    case PONTO_FIXO:

      if ($db_debug) {
        echo "Chamando a função gerffx($opcao_geral,$opcao_tipo).... <br>";
      }
      gerffx($opcao_geral,$opcao_tipo);

    break;

    case PONTO_PROVISAO_FERIAS:

      if ($db_debug) {
        echo "Chamando a função gerfprovfer($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfprovfer($opcao_geral,$opcao_tipo);

    break;

    case PONTO_PROVISAO_13_SALARIO:

      if ($db_debug) {
        echo "Chamando a função gerfprovs13($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfprovs13($opcao_geral,$opcao_tipo);

    break;
  }

  if ($db_debug) {
    echo "[pes4_geracalculo003] <br>";
    echo "[pes4_geracalculo003] --------------------------------------------------------------------------------- <br>";
    echo "[pes4_geracalculo003] FIM DO PROCESSAMENTO pes4_geracalculo003<br>";
    echo "[pes4_geracalculo003] --------------------------------------------------------------------------------- <br>";
    echo "<br>";
  }

  return true;
 }
