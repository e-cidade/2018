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

/**
 * Definição do Ajuste de Previdencia do Servidor no Calculo Financeiro
 *
 * @abstract
 * @author Rafael Serpa Nery  <rafael.nery@dbseller.com.br>
 */
abstract class AjusteIRRF {

  const AJUSTE_SALARIO         = "S";
  const AJUSTE_FERIAS          = "F";
  const AJUSTE_RESCISAO        = "R";
  const AJUSTE_13_SALARIO      = "3";
  const AJUSTE_COMPLEMENTAR    = "C";

  /**
   * Faz levantamento das matriculas que possuem duplovinculo atraves do cgm
   *
   * @param integer $numcgm
   * @param unknow $registrop
   * @param unknow $r01_tpvinc
   * @static
   * @access public
   * @return void
   */
  static public function gravarModificacoes($numcgm, $registrop, $r01_tpvinc) {

    LogCalculoFolha::write("CGM............: $numcgm");
    LogCalculoFolha::write("REGISTROP......: $registrop");
    LogCalculoFolha::write("R01_TPVINC.....: $r01_tpvinc");

    /**
     * Aliases para as variáveis para melhor entendimento
     */
    $iMatriculaServidor   = $registrop;
    $iCgmServidor         = $numcgm;
    $iTipoVinculoServidor = $r01_tpvinc;

    global $subpes;
    global $transacao;
    global $opcao_geral;
    global $chamada_geral_arquivo;
    global $F004;
    global $ajusteir;
    global $D902;
    global $ajusteir_;
    global $cfpess;
    global $anousu;
    global $mesusu;
    global $DB_instit;

    //$prev_base = 0;//@TODO Não utilizadas nesta função, antes de excluir verificar real dependencia
    //$prev_desc = 0;//@TODO Não utilizadas nesta função, antes de excluir verificar real dependencia
    //$prev_perc = 0;//@TODO Não utilizadas nesta função, antes de excluir verificar real dependencia


    if ( $opcao_geral == PONTO_SALARIO ) {

      $sTipoCalculo = AjusteIRRF::AJUSTE_SALARIO;
      $sSiglaTabela = CalculoFolhaSalario::SIGLA_TABELA;

    } else if ( $opcao_geral == PONTO_COMPLEMENTAR ) {

      $sTipoCalculo = AjusteIRRF::AJUSTE_COMPLEMENTAR;
      $sSiglaTabela = CalculoFolhaComplementar::SIGLA_TABELA;

    } else if ( $opcao_geral == PONTO_13_SALARIO ) {

      $sTipoCalculo = AjusteIRRF::AJUSTE_13_SALARIO;
      $sSiglaTabela = CalculoFolha13o::SIGLA_TABELA;

    } else if ( $opcao_geral == PONTO_RESCISAO ) {

      $sTipoCalculo = AjusteIRRF::AJUSTE_RESCISAO;
      $sSiglaTabela = CalculoFolhaRescisao::SIGLA_TABELA;

    } else if ( $opcao_geral == PONTO_FERIAS ) {

      $sTipoCalculo = AjusteIRRF::AJUSTE_FERIAS;
      $sSiglaTabela = CalculoFolhaFerias::SIGLA_TABELA;

    }
    $sSiglaTabelaSQL = strtolower($sSiglaTabela)."_";

    LogCalculoFolha::write("Sigla Tabela...: $sSiglaTabela");
    LogCalculoFolha::write("Tipo Calculo...: $sTipoCalculo");

    /**
     * Pega todos as matriculas do funcionario que nao tem contrato rescindido ou se foi rescindindo foi rescindido com data
     * igual ou superior ao ano e mes de exercicio da folha.
     */
    global $pessoal_;

    $aCompetencia   = explode("/", $subpes);
    $iMesFolha      = $aCompetencia[1];
    $iAnoFolha      = $aCompetencia[0];
    /**
     * Utilizado dia 01 da competencia, para fazer comparações de competencias
     * Ex.: Folha        : 2013/11
     *      Comparar com : 2012/10
     *
     *      No SQL Ficará: case '2013-11-01'::date > '2012-10-10'::date
     */
    $sDataBase      = "$iAnoFolha-$iMesFolha-01";
    $sDataRegistro  = "(extract(year  from rh05_recis) || '-' || extract(month from rh05_recis) || '-01')::date";
    $sSqlServidores = "select rh02_regist as r01_regist                                                      \n";
    $sSqlServidores.= "  from rhpessoalmov                                                                   \n";
    $sSqlServidores.= "       inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist\n";
    $sSqlServidores.= "       inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm   \n";
    $sSqlServidores.= "       left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes\n";
    $sSqlServidores.= bb_condicaosubpes("rh02_");
    $sSqlServidores.= " and rh01_numcgm = {$numcgm}                                                          \n";
    $sSqlServidores.= " and ( rh05_recis is null                                                             \n";
    $sSqlServidores.= "       or                                                                             \n";
    $sSqlServidores.= "       {$sDataRegistro} >= '{$sDataBase}'::date)";

    db_selectmax("pessoal_", $sSqlServidores);
    LogCalculoFolha::write("Quantos vinculos o servidor possue? ".count($pessoal_));

    /**
     * Aqui valida se o funcionário tem duplo vinculo
     */
    if (count($pessoal_) > 1) {

      $matrizr      = array(); // Rubricas Utilizadas para cálculo

      $matrizb      = array(); // Valores das Rubricas
      $matrizd      = array(); // Valores de desconto do IRRF
      $matrizp      = array(); // Quantidade do Pecentual de desconto do IRRF

      $matrizr[1]   = "R981"; // R981 BASE I.R.F. BASE
      $matrizr[2]   = "R982"; // R982 BASE IRF 13O SAL (BRUTA) BASE
      $matrizr[3]   = "R983"; // R983 BASE IRF FERIAS BASE

      $matrizb[1]   = 0;      // Valor da R981 BASE I.R.F. BASE
      $matrizb[2]   = 0;      // Valor da R982 BASE IRF 13O SAL (BRUTA) BASE
      $matrizb[3]   = 0;      // Valor da R983 BASE IRF FERIAS BASE

      $matrizd[1]   = 0;      // Valor R913 I.R.R.F. DESCONTO                   Está para -> R981
      $matrizd[2]   = 0;      // Valor R914 IRRF S/ 13o SALARIO DESCONTO        Está para -> R982
      $matrizd[3]   = 0;      // Valor R915 IRRF FERIAS DESCONTO                Está para -> R983

      $matrizp[1]   = 0;      // Quantidade R913 I.R.R.F. DESCONTO              Está para -> R981
      $matrizp[2]   = 0;      // Quantidade R914 IRRF S/ 13o SALARIO DESCONTO   Está para -> R982
      $matrizp[3]   = 0;      // Quantidade R915 IRRF FERIAS DESCONTO           Está para -> R983

      $valor_r979   = 0;      // R979 DEDUCOES P/IRRF (FERIAS)
      $valor_r984   = 0;      // R984 VLR REF DEPENDENTES P/ IRF
      $valor_r988   = 0;      // R988 DEDUCOES P/IRRF(SALARIO)
      $valor_r989   = 0;      // R989 DEDUCOES P/IRRF(13.SALARIO)
      $valor_r997   = 0;      // R997 DEDUCOES INAT/PENS +65ANOS
      $valor_r999   = 0;      // R999 DEDUCOES INAT/PENS+65ANOS 13SAL
      $nDescontoPrevidencia = 0;
      $x            = 1;      // Variavel Utilizada para navegar entre as Rurbicas R981, R982, R983

      $condicaoaux  = " and ".$sSiglaTabelaSQL."regist = ".db_sqlformat($registrop);
      $condicaoaux .= " and ".$sSiglaTabelaSQL."rubric between 'R979' and 'R999' ";
      db_selectmax("transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($sSiglaTabelaSQL ).$condicaoaux );

      LogCalculoFolha::write();
      LogCalculoFolha::write("Percorrendo os Eventos financeiros, chamados de 'Transações', com Rubricas entre R979 e R999");

      for ($Itransacao=0; $Itransacao< count($transacao); $Itransacao++) {

        LogCalculoFolha::write();

        /**
         * Transação equivale ao Evento financeiro da folha de pagamento
         */
        $oEventoFinanceiro = (object)$transacao[$Itransacao];
        $sRubricaTransacao = $oEventoFinanceiro->{$sSiglaTabelaSQL."rubric"};
        $sValorTransacao   = $oEventoFinanceiro->{$sSiglaTabelaSQL."valor"};
        LogCalculoFolha::write(" Rubrica Transacao...: $sRubricaTransacao");
        LogCalculoFolha::write(" Valor Transacao.....: $sValorTransacao");


        if ( in_array($sRubricaTransacao, $matrizr) ) {

          LogCalculoFolha::write(" A Rubrica é uma base");
          $x                 = db_val(substr("#". $sRubricaTransacao,4,1 ));
          $matrizb[$x]       = $sValorTransacao;//Define o Valor do Evento Financeiro

          $sRubricaDesconto  = $matrizr[$x];

          if ( $opcao_geral != PONTO_RESCISAO ) {

            $aFolhas = array(PONTO_SALARIO, PONTO_COMPLEMENTAR);

          if ( $sRubricaDesconto == "R981" && !in_array($opcao_geral, $aFolhas) ) {
              LogCalculoFolha::write("Nao calcula R981 fora do Ponto de Salário ou Complementar");
              continue; // R981 BASE I.R.F. BASE
            } elseif ( $sRubricaDesconto == "R982" && $opcao_geral != PONTO_13_SALARIO ) {
              LogCalculoFolha::write("Nao calcula R982 fora do Ponto de Salário");
              continue; // R982 BASE IRF 13O SAL (BRUTA) BASE
            } elseif ( $sRubricaDesconto == "R983" && $opcao_geral != PONTO_FERIAS ) {
              LogCalculoFolha::write("Nao calcula R981 fora do Ponto de Salário");
              continue; // R983 BASE IRF FERIAS BASE
            }
          }
          /**
           * Valida se o pagamento sera efetuado como férias(F) ou como salário(S)
           */
          $lPagamentoComoFerias             = strtolower($cfpess[0]["r11_fersal"]) == "f";
          $lRecalculaTercoFeriasPeriodoGozo = $cfpess[0]["r11_recalc"] == 't';
          $lRubricaIRFFerias                = $matrizr[$x] == "R983";
          $lFolhaFerias                     = $opcao_geral == PONTO_FERIAS;

          if( $lRubricaIRFFerias && $lFolhaFerias && $lPagamentoComoFerias && $lRecalculaTercoFeriasPeriodoGozo ) {

            global $cadferia;

            $sSqlUltimoPeriodoAquisitivo    = "select *                         ";
            $sSqlUltimoPeriodoAquisitivo   .= "  from cadferia                  ";
            $sSqlUltimoPeriodoAquisitivo   .= bb_condicaosubpes("r30_");
            $sSqlUltimoPeriodoAquisitivo   .=   " and r30_regist = {$registrop} ";
            $sSqlUltimoPeriodoAquisitivo   .= " order by r30_perai desc         ";
            $lExisteUltimoPeriodoAquisitivo = db_selectmax("cadferia", $sSqlUltimoPeriodoAquisitivo);

            if ( $lExisteUltimoPeriodoAquisitivo ) {

              LogCalculoFolha::write(" Existe ultimo periodo aquisitivo...");

              $lPagaTercoFerias = 't' == $cadferia[0]["r30_paga13"];
              /**
               * Verifica se não paga 1/3 de férias
               */
              if ( !$lPagaTercoFerias ) {

                LogCalculoFolha::write(" ...E não é pago como 1/3 de férias");
                LogCalculoFolha::write(" Calculando Rubricas de IRRF(R91, R914, R915)");
                // --> Inicio do Calculo das Rubricas IRRF (R913, R914 e R915)
                // @TODO - Continuar DEBUG com o cálculo de Férias
                global $transacao1;

                $condicaoaux  = " and r31_tpp = 'D' and r31_pd = 2 and r31_regist = ".db_sqlformat($registrop);

                if(db_selectmax("transacao1", "select sum(r31_valor) as descontod from gerffer ".bb_condicaosubpes("r31_" ).$condicaoaux )){

                  $matrizb[$x] -= $transacao1[0]["descontod"];
                  //echo "<br> Valor da Base IRF depois de R979 DEDUCOES P/IRRF (FERIAS) --> $r07_valor" ;
                  $condicaoaux  = " and r31_regist = ".db_sqlformat($registrop );
                  $condicaoaux .= " and r31_rubric = 'R983'";

                  $matriz1 = array();
                  $matriz2 = array();
                  $matriz1[1] = "r31_valor";
                  $matriz2[1] = $matrizb[$x];

                  //echo "<BR> rubrica 223.013 --> ".$matriz2[12];
                  //echo "<BR> rubrica 223.013 --> ".$matriz2[13];
                  //echo "<BR> rubrica 223.013 --> ".$matriz2[14];

                  $retornar = db_update("gerffer", $matriz1, $matriz2, bb_condicaosubpes("r31_" ).$condicaoaux );
                }
              }
            }

          } else {
            LogCalculoFolha::write("  Caso nao seja relacionado a cálculo de férias não faz nada");
          }
          $x += 1;
        } else if ( $sRubricaTransacao == "R984" ) {// R984 VLR REF DEPENDENTES P/ IRF
          $valor_r984 = $sValorTransacao;
        } else if ( $sRubricaTransacao == "R979" ) {// R979 DEDUCOES P/IRRF (FERIAS)
          $valor_r979 = $sValorTransacao;
        } else if ( $sRubricaTransacao == "R988" ) {// R988 DEDUCOES P/IRRF(SALARIO) (exemplo : Pensao alimenticia)
          $valor_r988 = $sValorTransacao;
        } else if ( $sRubricaTransacao == "R989" ) {// R989 DEDUCOES P/IRRF(13.SALARIO)
          $valor_r989 = $sValorTransacao;
        } else if ( $sRubricaTransacao == "R997" ) {// R997 DEDUCAO INAT/PENS +65ANOS
          $valor_r997 = $sValorTransacao;
        } else if ( $sRubricaTransacao == "R999" ) {// R999 DEDUCAO INAT/PENS+65ANOS 13SAL
          $valor_r999 = $sValorTransacao;
        } else if ( $sRubricaTransacao == "R993" ) {// R993 DESCONTO DE PREVIDENCIA
          $nDescontoPrevidencia = $sValorTransacao;
        }
      }
      LogCalculoFolha::write("Fim do FOR");
      LogCalculoFolha::write();
      LogCalculoFolha::write("R984 VLR REF DEPENDENTES P/ IRRF .............................: $valor_r984");
      LogCalculoFolha::write("R979 DEDUCOES P/IRRF (FERIAS) ................................: $valor_r979");
      LogCalculoFolha::write("R988 DEDUCOES P/IRRF(SALARIO) (Ex.: Pensao alimenticia) ......: $valor_r988");
      LogCalculoFolha::write("R989 DEDUCOES P/IRRF(13.SALARIO)..............................: $valor_r989");
      LogCalculoFolha::write("R997 DEDUCAO INAT/PENS +65ANOS ...............................: $valor_r997");
      LogCalculoFolha::write("R999 DEDUCAO INAT/PENS+65ANOS 13SAL ..........................: $valor_r999");
      LogCalculoFolha::write("R993 DESCONTO DE PREVIDENCIA..................................: $nDescontoPrevidencia");

      LogCalculoFolha::write();
      LogCalculoFolha::write("Idade do Servidor: " . $F004);

      $iIdadeServidor = $F004;

      if ($iIdadeServidor >= 65 && strtolower($r01_tpvinc) != "a" && ( $valor_r997 + $valor_r999 ) == 0 ) {

        LogCalculoFolha::write("Executando modificações para que quando:");
        LogCalculoFolha::write(" - Idade do Servidor maior que 65 anos;");
        LogCalculoFolha::write(" - Tipo de vinculo diferente de Ativo;");
        LogCalculoFolha::write(" - Soma das rubricas de Deduções para Inativos/Pensionistas > 65 anos");
        for ( $x=1; $x<4; $x++ ) {

          if (!db_empty($matrizb[$x] )) {
            if ($x != 2) {
              // D902 VLR DESC IRF P/65 ANOS
              if ($matrizb[$x] < $D902) {
                $valor_r997 = $matrizb[$x];
              } else {
                $valor_r997 = $D902;
              }
            } else {
              // D902 VLR DESC IRF P/65 ANOS
              if ($matrizb[$x] < $D902) {
                $valor_r999 = $matrizb[$x];
              } else {
                $valor_r999 = $D902;
              }
            }
          }
        }
      }
      // R913 I.R.R.F. DESCONTO              --->  R981
      // R914 IRRF S/ 13o SALARIO DESCONTO   --->  R982
      // R915 IRRF FERIAS DESCONTO           --->  R983
      $condicaoaux  = " and ".$sSiglaTabelaSQL."regist = ".db_sqlformat($registrop );
      $condicaoaux .= " and ".$sSiglaTabelaSQL."rubric between 'R913' and 'R915'";
      db_selectmax("transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($sSiglaTabelaSQL ).$condicaoaux );


      LogCalculoFolha::write();
      LogCalculoFolha::write("Percorrendo os Eventos financeiros, chamados de 'Transações', com Rubricas entre R913 e R915");

      for ($Itransacao=0; $Itransacao<count($transacao); $Itransacao++) {

        $sRubricaTransacao    = $transacao[$Itransacao][$sSiglaTabelaSQL."rubric"];
        $sValorTransacao      = $transacao[$Itransacao][$sSiglaTabelaSQL."valor"];
        $sQuantidadeTransacao = $transacao[$Itransacao][$sSiglaTabelaSQL."quant"];

        if ( $opcao_geral != PONTO_RESCISAO ) {

          $aFolhas = array(PONTO_SALARIO, PONTO_COMPLEMENTAR);
          if ( $sRubricaTransacao == "R981" && !in_array($opcao_geral, $aFolhas) ) {
            LogCalculoFolha::write("Nao calcula R981 fora do Ponto de Salário");
            continue; // R981 BASE I.R.F. BASE
          } elseif ( $sRubricaTransacao == "R982" && $opcao_geral != PONTO_13_SALARIO ) {
            LogCalculoFolha::write("Nao calcula R982 fora do Ponto de Salário");
            continue; // R982 BASE IRF 13O SAL (BRUTA) BASE
          } elseif ( $sRubricaTransacao == "R983" && $opcao_geral != PONTO_FERIAS ) {
            LogCalculoFolha::write("Nao calcula R981 fora do Ponto de Salário");
            continue; // R983 BASE IRF FERIAS BASE
          }
        }
        LogCalculoFolha::write();
        LogCalculoFolha::write("  Rubrica...:" . $sRubricaTransacao);
        LogCalculoFolha::write("  Valor.....:" . $sValorTransacao);
        LogCalculoFolha::write("  Quantidade:" . $sQuantidadeTransacao);

        $x = ( db_val(substr("#". $sRubricaTransacao, 2, 3 ) ) - 913 ) + 1;

        if (!db_empty($matrizb[$x]  )) {

          $matrizd[$x] = $sValorTransacao;
          $matrizp[$x] = $sQuantidadeTransacao;
          //echo "<BR> rubrica 223.012 codigo --> $x rubrica --> ".$matrizd[$x]." valor --> ".$matrizp[$x];
          //LogCalculoFolha::write("<BR> rubrica 223.012 codigo --> $x rubrica --> ".$matrizd[$x]." valor --> ".$matrizp[$x]);
          $x += 1;
        }

        if ($x > 3 ) {
          break;
        }
      }


      LogCalculoFolha::write();
      LogCalculoFolha::write("Percorrendo rubricas R981, R982, R983 - Para ser salva na tabela 'ajusteir'");

      for ($nosx=1; $nosx<4; $nosx++) {

        $sRubricaTransacao = $matrizr[$nosx];

        if ( $opcao_geral != PONTO_RESCISAO ) {
          $aFolhas = array(PONTO_SALARIO, PONTO_COMPLEMENTAR);
          if ( $sRubricaTransacao == "R981" && !in_array($opcao_geral, $aFolhas) ) {
            LogCalculoFolha::write("Nao calcula R981 fora do Ponto de Salário");
            continue; // R981 BASE I.R.F. BASE
          } elseif ( $sRubricaTransacao == "R982" && $opcao_geral != PONTO_13_SALARIO ) {
            LogCalculoFolha::write("Nao calcula R982 fora do Ponto de Salário");
            continue; // R982 BASE IRF 13O SAL (BRUTA) BASE
          } elseif ( $sRubricaTransacao == "R983" && $opcao_geral != PONTO_FERIAS ) {
            LogCalculoFolha::write("Nao calcula R981 fora do Ponto de Salário");
            continue; // R983 BASE IRF FERIAS BASE
          }
        }

        if (!db_empty($matrizb[$nosx])) {
          $matriz1 = array();
          $matriz2 = array();

          $matriz1[1] = "r61_numcgm";
          $matriz1[2] = "r61_regist";
          $matriz1[3] = "r61_base";
          $matriz1[4] = "r61_descir";// Desconto de Imposto de Renda
          $matriz1[5] = "r61_percir";// Faixa na Tabela de Desconto de Imposto de Renda (Percentual de Desconto)
          $matriz1[6] = "r61_rubric";
          $matriz1[7] = "r61_folha";
          $matriz1[8] = "r61_altera";
          $matriz1[9] = "r61_depend";
          $matriz1[10] = "r61_didade";
          $matriz1[11] = "r61_deduc";
          $matriz1[12] = "r61_dprev";
          $matriz1[13] = "r61_novod";
          $matriz1[14] = "r61_novop";
          $matriz1[15] = "r61_ajuste";
          $matriz1[16] = "r61_anousu";
          $matriz1[17] = "r61_mesusu";

          $matriz2[1] = $numcgm;
          $matriz2[2] = $registrop;
          $matriz2[3] = round($matrizb[$nosx],2);
          $matriz2[4] = round($matrizd[$nosx],2);
          $matriz2[5] = $matrizp[$nosx];
          $matriz2[6] = $matrizr[$nosx];
          $matriz2[7] = $sTipoCalculo;
          $matriz2[8] = 't';
          $matriz2[9] = round($valor_r984,2);
          $matriz2[10] = round(( $nosx == 2 ?  $valor_r999: $valor_r997 ),2);
          $matriz2[11] = round(( $nosx == 1 ? $valor_r988: ($nosx==2?$valor_r989:$valor_r979)),2);
          $matriz2[12] = $nDescontoPrevidencia;
          $matriz2[13] = (round($matrizd[$nosx],2)) ? round($matrizd[$nosx],2) : 0;
          $matriz2[14] = $matrizp[$nosx];
          $matriz2[15] = 't';
          $matriz2[16] = $anousu ;
          $matriz2[17] = $mesusu ;

          $condicaoaux  = " and r61_numcgm = ".db_sqlformat($numcgm);
          $condicaoaux .= " and r61_rubric = ".db_sqlformat($matrizr[$nosx]);
          $condicaoaux .= " and r61_regist = ".db_sqlformat($registrop );
          $condicaoaux .= " and upper(r61_folha)  = ".db_sqlformat($sTipoCalculo ) ;

          /**
           * @todo Método separado {{{
           */

          if ( DBPessoal::verificarUtilizacaoEstruturaSuplementar() && ( $sTipoCalculo == "S" || $sTipoCalculo == "C" ) ) {

            $aServidoresQueNaoParticipamFolha = CalculoFolha::$aMatriculasExcluirHistoricoPonto;

            if ( !empty($aServidoresQueNaoParticipamFolha) && in_array($registrop, $aServidoresQueNaoParticipamFolha) ) {

              $oHistoricoCalculoServidorNaoParticipa    = new cl_rhhistoricocalculo();
              $sSqlHistoricoCalculoServidorNaoParticipa = $oHistoricoCalculoServidorNaoParticipa->sql_query_eventosfinanceiros_fechados(
                DBPessoal::getCompetenciaFolha(),
                $chamada_geral_arquivo, //Qual cálculo está sendo executado
                ServidorRepository::getInstanciaByCodigo(
                  $registrop,
                  DBPessoal::getAnoFolha(),
                  DBPessoal::getMesFolha()
                ),
                RubricaRepository::getInstanciaByCodigo('R913')
              );

              $rsHistoricoCalculoServidorNaoParticipa      = db_query($sSqlHistoricoCalculoServidorNaoParticipa);

              if(!$rsHistoricoCalculoServidorNaoParticipa) {
                throw new DBException("Ocorreu um erro ao buscar o histórico do cálculo");
              }

              if(pg_num_rows($rsHistoricoCalculoServidorNaoParticipa) == 0) {
                $oCamposHistoricoCalculoServidorNaoParticipa->rh143_valor = 0;
              }

              $oCamposHistoricoCalculoServidorNaoParticipa = db_utils::fieldsMemory($rsHistoricoCalculoServidorNaoParticipa, 0);
              $matriz2[8]  = 'f';
              $matriz2[15] = 'f';
              $matriz2[13] = ($oCamposHistoricoCalculoServidorNaoParticipa->rh143_valor) ? $oCamposHistoricoCalculoServidorNaoParticipa->rh143_valor : 0;
            }
          }
          /**
           * }}}
           */

          if (db_selectmax("ajusteir_", "select * from ajusteir ".bb_condicaosubpes("r61_" ).$condicaoaux )) {

            LogCalculoFolha::write("ALTERANDO Registro na Tabela: 'AJUSTEIR'");
            $retornar    = db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_" ).$condicaoaux );
          } else {

            LogCalculoFolha::write("INSERINDO Registro na Tabela: 'AJUSTEIR'");
            $retornar    = db_insert("ajusteir", $matriz1, $matriz2 );
          }

          /**
           * Zera Valores de Salario para que nao influencie no valor da complementar
           * Tanto em questão de valores quanto percentual
           */
          if ( $sTipoCalculo == AjusteIRRF::AJUSTE_COMPLEMENTAR ) {

            $aCampos[1]  = "r61_novod";
            $aCampos[2]  = "r61_novop";
            $matriz1[3]  = "r61_ajuste";
            $matriz1[4]  = "r61_altera";
            $matriz1[5]  = "r61_base";
            $matriz1[6]  = "r61_percir";

            $aValores[1] = "0";
            $aValores[2] = "0";
            $aValores[3] = "f";
            $aValores[4] = "f";
            $aValores[5] = "0";
            $aValores[6] = "0";

            $sWhere   = bb_condicaosubpes("r61_" );
            $sWhere  .= " and r61_numcgm = ".db_sqlformat($numcgm);
            $sWhere  .= " and r61_rubric = ".db_sqlformat($matrizr[$nosx]);
            $sWhere  .= " and r61_regist = ".db_sqlformat($registrop );
            $sWhere  .= " and r61_folha  = 'S'";
            db_delete("ajusteir", $sWhere);
          }

          LogCalculoFolha::write("Lançando ajuste da Rubrica:{$matrizr[$nosx]}");
          LogCalculoFolha::write(print_r(array_combine($matriz1, $matriz2),true));

        } else {
          LogCalculoFolha::write("Removendo do Ajuste do IRRF a Rubrica: {$matrizr[$nosx]}");
          $condicaoaux  = " and r61_numcgm = ".db_sqlformat($numcgm );
          $condicaoaux .= " and r61_rubric = ".db_sqlformat($matrizr[$nosx] ) ;
          $condicaoaux .= " and r61_regist = ".db_sqlformat($registrop);
          $condicaoaux .= " and upper(r61_folha)  = ".db_sqlformat($sTipoCalculo ) ;
          if (db_selectmax("ajusteir_", "select * from ajusteir ".bb_condicaosubpes("r61_" ).$condicaoaux )) {
            //echo "<BR> rubrica 223.3 --> ".$sRubricaTransacao;
            $retornar = db_delete("ajusteir", bb_condicaosubpes("r61_" ).$condicaoaux );
          }
        }
      }
    }
    LogCalculoFolha::write("Fim da GRAVAÇÃO DAS MODIFICAÇÕES");
  }
  /**
   * ajustar
   *
   * @param string $arquivo      - Tabela a ser ajustada
   * @param String $rubrica_base - Rubrica a ser calculada.
   * @param unknow $sequencia
   * @param unknow $sigla_ajuste
   * @static
   * @access public
   * @return void
   */
  static public function ajustar($arquivo, $rubrica_base, $sequencia, $sigla_ajuste) {

    LogCalculoFolha::write("Variáveis:");
    LogCalculoFolha::write(" - Tabela......: {$arquivo}");
    LogCalculoFolha::write(" - Rubrica Base: {$rubrica_base}");
    LogCalculoFolha::write(" - Sequencia...: {$sequencia}");
    LogCalculoFolha::write(" - Sigla Ajuste: {$sigla_ajuste}");
    global $quais_diversos;
    global $ajusteir;
    global $campos_pessoal;
    global $ajusteir_;
    global $inssirf;
    global $Iinssirf;
    global $subpes;
    global $db21_codcli;
    global $cfpess;
    global $inssirf_r33_perc;
    global $anousu;
    global $mesusu;
    global $DB_instit;
    global $db_debug;
    global $quais_diversos;
    global $opcao_geral;
    global $faixa_regis;
    global $opcao_tipo;

    eval($quais_diversos);

    $aRubricasPrevidencia = array(
      "R981"  => "R985", //SALARIO
      "R982"  => "R986", // 13º
      "R983"  => "R987"  // FERIAS
    );
    $aRubricasDesconto    = array(
      "R981"  => "R913", //SALARIO
      "R982"  => "R914", // 13º
      "R983"  => "R915"  // FERIAS
    );
    /**
     * Validando Qual tipo de folha resgata
     */
    switch ( $opcao_geral ) {
    case PONTO_COMPLEMENTAR:
      $sAjusteCalculo = "C";
      break;
    case PONTO_RESCISAO:
      $sAjusteCalculo = AjusteIRRF::AJUSTE_RESCISAO;
      break;
    case PONTO_SALARIO:
    case PONTO_13_SALARIO:
    case PONTO_FERIAS:
    default:
      $sAjusteCalculo = $sequencia == 1 ? "S" : ( $sequencia == 2 ? "3" : "F" );
      break;
    }
    LogCalculoFolha::write("Tipo de Ajuste: $sAjusteCalculo");
    $matriz1           = array();
    $matriz2           = array();
    $matriz1[1]        = "r61_ajuste";
    $matriz2[1]        = 'f';
    $sWhereAjusteIRRF  = bb_condicaosubpes("r61");

    LogCalculoFolha::write("Define todos os dados da tabela ajusteir para r61_ajuste = 'f'");

    $sWhereMatriculasCalculadas = '';
    if( $opcao_tipo == TIPO_CALCULO_PARCIAL ) {
      $sWhereMatriculasCalculadas = " and r61_regist in ({$faixa_regis})";
      db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_") . $sWhereMatriculasCalculadas  );
    }else{
      db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_") );
    }
    //db_update("ajusteir",$matriz1, $matriz2, bb_condicaosubpes("r61_" ));

    $condicaoaux  = " and r61_altera = 't'                                                        ";
    $condicaoaux .= " and r61_rubric = '{$rubrica_base}'                                          ";
    $condicaoaux .= " and upper(r61_folha) in ('$sAjusteCalculo')                                 ";
    $condicaoaux .= $sWhereMatriculasCalculadas;
    /**
     * Acima a sequencia
     *    1 - Salário
     *    2 - 13o Salario
     *    x - Ferias
     */
    LogCalculoFolha::write("Seleciona todos os registros da tabela ajusteir pela sequencia, alteracao = true e rubrica = Rubrica Base");
    LogCalculoFolha::write();
    LogCalculoFolha::write("select * from ajusteir ".bb_condicaosubpes("r61_" ).$condicaoaux);
    LogCalculoFolha::write();

    $condicaoAjusteIRRF = " inner join rhpessoal on r61_regist = rh01_regist and rh01_instit = " . $DB_instit ;
    db_selectmax("ajusteir_","select * from ajusteir " .$condicaoAjusteIRRF . " "  . bb_condicaosubpes("r61_") . $condicaoaux);

    for ($Iajusteir=0; $Iajusteir<count($ajusteir_); $Iajusteir++) {

      /**
       * Após realiza update com os registros com base no CGM encontrado anteriormente
       * e a rubrica base definindo o campo r61_ajuste como true
       */
      $matriz2[1]   = 't' ;
      $condicaoaux  = " and r61_numcgm = ".db_sqlformat($ajusteir_[$Iajusteir]["r61_numcgm"] );

      if (isset($faixa_regis) && !empty($faixa_regis)) {
        $condicaoaux .= " and r61_regist in ($faixa_regis)";
      }

      $condicaoaux .= " and r61_rubric = ".db_sqlformat($rubrica_base );
      db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_").$condicaoaux );
    }
    /**
     * Modificado pois deve considerar a rubrica independente de folha
     */
    $condicaoaux   = " and r61_ajuste = 't' and r61_rubric = ".db_sqlformat($rubrica_base);
    $condicaoaux  .= $sWhereMatriculasCalculadas;
    $condicaoaux  .= "order by r61_numcgm ";
    //antes    $condicaoaux  .= " and upper(r61_folha) in ('".($sequencia==1?"S":($sequencia==2?"3":"F"))."') order by r61_numcgm ";

    $sSqlRegistros = "select * from ajusteir " . $condicaoAjusteIRRF . " "  . bb_condicaosubpes("r61_") . $condicaoaux;

    db_selectmax("ajusteir", $sSqlRegistros);

    if (count($ajusteir) > 0) {

      /**
       * o que define a condicao e a gravacao do arquivo na ion. gerfsal ...;
       */
      $Iajuste = 0;
      $tot_ajusteir = count($ajusteir);

      LogCalculoFolha::write("Percorrendo Ajustes - Total:" . $tot_ajusteir);
      while ($Iajuste < $tot_ajusteir) {

        LogCalculoFolha::write("WHILE_1_INICIO:");
        $numcgm          = $ajusteir[$Iajuste]["r61_numcgm"];
        $registro        = $ajusteir[$Iajuste]["r61_regist"];
        $soma_base       = 0;
        $soma_desc_fixo  = 0;
        $soma_base_fixo  = 0;
        $soma_deducoes   = 0;
        $soma_depend     = 0;
        $soma_didade     = 0;
        $nDescontoPrevidencia = 0;
        LogCalculoFolha::write("  CGM.......:" . $numcgm);
        // soma dos brutos e deducoes;

        while ($Iajuste < $tot_ajusteir && ($ajusteir[$Iajuste]["r61_numcgm"] == $numcgm )) {

          LogCalculoFolha::write('');
          LogCalculoFolha::write("----------------- Matrícula .:" . $ajusteir[$Iajuste]["r61_regist"]." -----------------");
          LogCalculoFolha::write("--- Tipo Folha.:" . $ajusteir[$Iajuste]["r61_folha"]);

          $rubrica_previdencia = $aRubricasPrevidencia[$rubrica_base];

          /**
           * Busca o novo valor que foi gerado no ajuste de previdência.
           */
          $oDaoPrevidencia       = new cl_previden();

          $sWhere                = "    r60_regist = {$ajusteir[$Iajuste]["r61_regist"]}  ";
          $sWhere               .= "and r60_anousu = {$ajusteir[$Iajuste]["r61_anousu"]}  ";
          $sWhere               .= "and r60_mesusu = {$ajusteir[$Iajuste]["r61_mesusu"]}  ";
          $sWhere               .= "and r60_folha  = '{$ajusteir[$Iajuste]["r61_folha"]}' ";
          $sWhere               .= "and r60_rubric = '{$rubrica_previdencia}' ";

          if($opcao_geral == PONTO_FERIAS) {

            $sWhere                = "    r60_regist = {$ajusteir[$Iajuste]["r61_regist"]}  ";
            $sWhere               .= "and r60_anousu = {$ajusteir[$Iajuste]["r61_anousu"]}  ";
            $sWhere               .= "and r60_mesusu = {$ajusteir[$Iajuste]["r61_mesusu"]}  ";
            $sWhere               .= "and r60_folha  in ('S','C')                           ";
            $sWhere               .= "and r60_rubric in ('R985')                            ";
          }

          $sSqlValorPrevidencia  = $oDaoPrevidencia->sql_query_file(null, null, null, null, null, "r60_novod", null, $sWhere);
          $rsValorPrevidencia    = db_query($sSqlValorPrevidencia);

          if(!$rsValorPrevidencia) {
            throw new DBException("Ocorreu um erro ao buscar o valor de previdência");
          }
          $fValorPrevidencia     = 0;
          if(pg_num_rows($rsValorPrevidencia) > 0) {
            $fValorPrevidencia   = db_utils::fieldsMemory($rsValorPrevidencia, 0)->r60_novod;
          }
          $nDescontoPrevidencia += $fValorPrevidencia;
          LogCalculoFolha::write("  - Valor Parcial da Previdência....+= {$fValorPrevidencia}");

          /**
           * Base
           */
          $soma_base += $ajusteir[$Iajuste]["r61_base"];
          LogCalculoFolha::write("  - Valor Parcial da Base...........+= {$ajusteir[$Iajuste]["r61_base"]}");

          /**
           * Dedução Dependentes
           */
          if ($ajusteir[$Iajuste]["r61_depend"] > $soma_depend) {
            $soma_depend = $ajusteir[$Iajuste]["r61_depend"];
            LogCalculoFolha::write("  - Valor Parcial dependentes.......+= {$ajusteir[$Iajuste]["r61_depend"]}");
          }

          /**
           * Idade
           */
          $soma_didade += $ajusteir[$Iajuste]["r61_didade"];
          LogCalculoFolha::write("  - Valor Parcial idade.............+= {$ajusteir[$Iajuste]["r61_didade"]}");

          // D902 VLR DESC IRF P/65 ANOS
          if ($soma_didade > $D902) {

            $soma_didade = $D902;
            LogCalculoFolha::write("  - Valor Parcial idade.............= {$D902}");
          }

          /**
           * Deduções
           */
          $soma_deducoes += ( $ajusteir[$Iajuste]["r61_deduc"] );
          LogCalculoFolha::write("  - Valor Parcial das Deduções......+= {$ajusteir[$Iajuste]["r61_deduc"]}");
          if ('f' ==  $ajusteir[$Iajuste]["r61_altera"] ) {

            LogCalculoFolha::write("  - Valor Parcial desconto Fixo.....+= {$ajusteir[$Iajuste]["r61_novod"]}");
            LogCalculoFolha::write("  - Valor Parcial da base fixa......+= {$ajusteir[$Iajuste]["r61_base"]}");
            $soma_base_fixo += $ajusteir[$Iajuste]["r61_base"];
            $soma_desc_fixo += $ajusteir[$Iajuste]["r61_novod"];
          }

          /**
           * Resetando varíaveis que serão utilizadas na proporcionalização de > 65 anos e isentos ou inativos
           */
          $sCalculoTipo                     = false;
          $lVinculoServidorAtualInativo     = null;
          $lVinculoServidorAtualPensionista = null;

          /**
           * Verifica se está percorrendo a linha do ajuste de salário, 13o ou Rescisão e não está no cálculo de complementar
           */
          if(in_array($ajusteir[$Iajuste]["r61_folha"], array('S','3','R', 'C'))) {
          // if(in_array($ajusteir[$Iajuste]["r61_folha"], array('S','3','R')) && $sigla_ajuste != "r48_") {

            /**
             * Montamos objeto servidor para verificar se possui vínculo para ratear parcela de isenção
             */
            LogCalculoFolha::write('');
            LogCalculoFolha::write('  - Montando objeto servidor para verificar se precisa proporcionalizar a parcela de isenção.');
            LogCalculoFolha::write('  -- Servidor matrícula: '.$ajusteir[$Iajuste]["r61_regist"]);

            $aMatriculasServidorInativo = array($ajusteir[$Iajuste]["r61_regist"]);
            $oServidorAtual             = ServidorRepository::getInstanciaByCodigo($ajusteir[$Iajuste]["r61_regist"]);
            $lVinculoServidorAtualInativo     = $oServidorAtual->getVinculo()->getTipo() == VinculoServidor::VINCULO_INATIVO;
            $lVinculoServidorAtualPensionista = $oServidorAtual->getVinculo()->getTipo() == VinculoServidor::VINCULO_PENSIONISTA;

            if($oServidorAtual->getIdade() >= 65) {

              LogCalculoFolha::write('  -- Maior de 65 anos');

              if($lVinculoServidorAtualInativo || $lVinculoServidorAtualPensionista) {

                if($oServidorAtual->hasVinculadoInativoPensionistaMaior65Anos()) {

                  $oServidorVinculado = $oServidorAtual->getServidorVinculado();

                  $aMatriculasAtualizarServidorInativo[$oServidorAtual->getMatricula()]     = $oServidorAtual->getMatricula();
                  $aMatriculasAtualizarServidorInativo[$oServidorVinculado->getMatricula()] = $oServidorVinculado->getMatricula();

                  /**
                   * Verifica a sigla que está fazendo o ajuste, se folha de salário ou 13º salário
                   */
                  switch ($sigla_ajuste) {

                    case 'r14_':
                      $sCalculoTipo     = CalculoFolha::CALCULO_SALARIO;
                      $sSiglaFolha      = 'S';
                      $sRubricaIsencao  = 'R997';
                      break;

                    case 'r48_':
                      $sCalculoTipo     = CalculoFolha::CALCULO_COMPLEMENTAR;
                      $sSiglaFolha      = 'C';
                      $sRubricaIsencao  = 'R997';
                      break;

                    case 'r35_':
                      $sCalculoTipo     = CalculoFolha::CALCULO_13o;
                      $sSiglaFolha      = '13';
                      $sRubricaIsencao  = 'R999';
                      break;

                    case 'r20_':
                      $sCalculoTipo    = CalculoFolha::CALCULO_RESCISAO;
                      $sSiglaFolha     = 'R';
                      $sRubricaIsencao = 'R997';

                      if($ajusteir[$Iajuste]["r61_rubric"] == 'R982') {
                        $sSiglaFolha      = '13';
                        $sRubricaIsencao = 'R999';
                      }
                      break;
                  }
                  LogCalculoFolha::write('  -- Rubrica com a parcela de isenção: '.$sRubricaIsencao);
                  LogCalculoFolha::write();

                  $oCalculoServidorInativoPensionista   = null;

                  if($sSiglaFolha == $ajusteir[$Iajuste]["r61_folha"]) {
                    $oCalculoServidorInativoPensionista = $oServidorAtual->getCalculoFinanceiro($sCalculoTipo);
                  }

                  if(!empty($oCalculoServidorInativoPensionista)) {

                    global $D902;
                    $aEventosFinanceirosServidorAtual = $oCalculoServidorInativoPensionista->getEventosFinanceiros(null, $sRubricaIsencao);

                    if(count($aEventosFinanceirosServidorAtual) > 0) {

                      LogCalculoFolha::write('');
                      LogCalculoFolha::write('  -- Rubrica da parcela de isenção..................: '.$sRubricaIsencao);
                      LogCalculoFolha::write('  -- Valor do teto da parcela de isenção..... ......: '.$D902);

                      $oEventoFinanceiroServidorAtual = $aEventosFinanceirosServidorAtual[0];
                      $nValorIsencao                  = $oCalculoServidorInativoPensionista->ajustarParcelaIsentaAposentadoPensionista(
                                                          $sRubricaIsencao,
                                                          $D902,
                                                          $oEventoFinanceiroServidorAtual->getValor()
                                                        );

                      LogCalculoFolha::write('  -- Valor da parcela de isenção antes do cálculo...: '.$oEventoFinanceiroServidorAtual->getValor());
                      LogCalculoFolha::write('  -- Valor da parcela de isenção após o cálculo.....: '.$nValorIsencao);

                      /**
                       * Verifica se deve atualizar o valor das matrículas, a verificação existe
                       * pois no cálculo com suplementar não deve alterar rubrica de folha fechada
                       */
                      if(in_array($ajusteir[$Iajuste]["r61_regist"], $aMatriculasAtualizarServidorInativo)) {

                        /**
                         * Atualiza na tabela o valor de isenção
                         */
                        $sWhereParcelaIsencao = bb_condicaosubpes($sigla_ajuste);
                        $sWhereParcelaIsencao.= ' and '.$sigla_ajuste.'rubric = \''.$sRubricaIsencao.'\'';
                        $sWhereParcelaIsencao.= ' and '.$sigla_ajuste.'regist = ' . $ajusteir[$Iajuste]["r61_regist"];

                        db_update(
                          $sCalculoTipo,
                          array(1=>$sigla_ajuste.'valor'),
                          array(1=>$nValorIsencao),
                          $sWhereParcelaIsencao
                        );

                        LogCalculoFolha::write('');
                        LogCalculoFolha::write('  -- Matrícula com parcela atualizada....: '.$ajusteir[$Iajuste]["r61_regist"]);
                      }
                    }
                  }
                }
              }
            }
          }

          $Iajuste++;
        }

        LogCalculoFolha::write();
        LogCalculoFolha::write(" Ao fim do While Chegou-se ao resultado:");
        LogCalculoFolha::write("  - Soma da Base......................: $soma_base     ");
        LogCalculoFolha::write("  - Soma desconto Fixo................: $soma_desc_fixo");
        LogCalculoFolha::write("  - Soma da base fixa.................: $soma_base_fixo");
        LogCalculoFolha::write("  - Soma das Deduções.................: $soma_deducoes ");
        LogCalculoFolha::write("  - Soma valor dependentes............: $soma_depend   ");
        LogCalculoFolha::write("  - Soma valor idade..................: $soma_didade   ");
        LogCalculoFolha::write("  - Soma desconto de previdencia......: $nDescontoPrevidencia ");

        $soma_desc_previdencia = $nDescontoPrevidencia; //@TODO VAlidar essa regra qui oh

        if ( $sequencia  == 3 ) { //Férias

          global $previden;

          $sSqlDadosFerias = " select coalesce(sum(r31_valor),0) as  soma_desc_previdencia \n";
          $sSqlDadosFerias.= "   from gerffer                                              \n";
          $sSqlDadosFerias.= "        inner join rhpessoal on rh01_regist = r31_regist     \n";
          $sSqlDadosFerias.= bb_condicaosubpes($sigla_ajuste);
          $sSqlDadosFerias.= "   and r31_rubric in ('R903','R906','R909','R912')           \n";
          $sSqlDadosFerias.= "   and r31_tpp = 'F'                                         \n";
          $sSqlDadosFerias.= "   and rh01_numcgm = $numcgm                                 \n";
          db_selectmax("previden", $sSqlDadosFerias );
          $soma_desc_previdencia = $previden[0]["soma_desc_previdencia"];

        }

        LogCalculoFolha::write("  - Soma valor Previdencia.: $soma_desc_previdencia");
        LogCalculoFolha::write();
        LogCalculoFolha::write("  - Fórmula para Base Líquida:");
        LogCalculoFolha::write("  - BaseLiquida = SomaBase   - TotalDeducoes");
        LogCalculoFolha::write("  - BaseLiquida = SomaBase   - (SomaDescontoPrevidencia + SomaDescontoDependentes + SomaDeducoes + SomaDescontoIdade )");
        LogCalculoFolha::write("  - BaseLiquida = $soma_base - ($soma_desc_previdencia + $soma_depend + $soma_deducoes + $soma_didade )");

        $base_liquida = round(( $soma_base - $soma_desc_previdencia - $soma_depend - $soma_deducoes - $soma_didade ),2 );
        LogCalculoFolha::write("  - BaseLiquida = $base_liquida");

        $valor_desconto_total = 0;

        if ($base_liquida > 0) {

          LogCalculoFolha::write("Base Liquida Maior que ZERO, chamado função: 'le_irf(BaseLiquida,1)' ");
          LogCalculoFolha::write("Chamando Função de Cálculo de IRRF");
          $valor_desconto_total = round(le_irf($base_liquida,"1"),2) ;
        }
        LogCalculoFolha::write("Valor do Desconto Total: $valor_desconto_total");

        // D911 VALOR MINIMO P/ DESC DE IRF
        if ($valor_desconto_total < $D911) {

          LogCalculoFolha::write("Valor a Ratear Zerado pois valor do Desconto Total é menor que o Minimo para desconto do IRRF(D911): $D911");
          $valor_a_ratear = 0;
        } else {

          $valor_a_ratear = $valor_desconto_total - $soma_desc_fixo;
          LogCalculoFolha::write();
          LogCalculoFolha::write("  - ValorRatear = ValorDescontoTotal - SomaDescontoFixo");
          LogCalculoFolha::write("  - ValorRatear = $valor_desconto_total - $soma_desc_fixo");
          LogCalculoFolha::write("  - ValorRatear = $valor_a_ratear");
        }

        $condicaoaux  = " and r61_numcgm = ".db_sqlformat($numcgm );
        $condicaoaux .= " and r61_rubric = ".db_sqlformat($rubrica_base );
        //        $condicaoaux .= " and upper(r61_folha) in ('$sAjusteCalculo') ";
        $condicaoaux .= " and r61_altera = 't' ";
        $iInstituicao = db_getsession("DB_instit");
        $sSqlAjusteIRRF = "select * from ajusteir inner join rhpessoal on r61_regist = rh01_regist and rh01_instit = {$iInstituicao}".bb_condicaosubpes("r61_" ).$condicaoaux;

        global $ajusteir_;

        LogCalculoFolha::write("Percorre os as Matriculas do CGM quais foram gerados ajuste" );
        LogCalculoFolha::write($sSqlAjusteIRRF);

        if ( db_selectmax("ajusteir_", $sSqlAjusteIRRF) ) {

          for ($Iajusteir_=0; $Iajusteir_< count($ajusteir_) ; $Iajusteir_++) {


            //echo "<BR> chegou aqui 6.1:  numcgm  --> ".$ajusteir_[$Iajusteir_]["r61_numcgm"];
            // reis
            //echo "<BR> chegou aqui 6.1:  regist  --> ".$ajusteir_[$Iajusteir_]["r61_regist"];
            // reis
            //          $condicaoaux = " and r01_regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
            global $pessoal_1;

            $condicaoaux = " and rh02_regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
            db_selectmax("pessoal_1",
              "select rh01_numcgm as r01_numcgm,                                                ".
              "       rh02_tpcont as r01_tpcont,                                                ".
              "       trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac                              ".
              "  from rhpessoalmov                                                              ".
              "        inner join rhpessoal on rhpessoal.rh01_regist = rhpessoalmov.rh02_regist ".
              "        inner join cgm       on cgm.z01_numcgm        = rhpessoal.rh01_numcgm    ".
              bb_condicaosubpes("rh02_" ).
              $condicaoaux
            );

            if ($valor_a_ratear > 0) {
              /**
               * Aqui faz a DIVISAO
               */
              LogCalculoFolha::write("Aqui faz a Divisao dos Valores entre as matriculas do CGM");
              $novo_desconto = ($ajusteir_[$Iajusteir_]["r61_base"] / ($soma_base - $soma_base_fixo) * $valor_a_ratear) ;
              LogCalculoFolha::write(" - NovoDesconto = BaseTabelaAjusteIR / (SomaBase - SomaBaseFixa) * ValorRatear");
              LogCalculoFolha::write(" - NovoDesconto = {$ajusteir_[$Iajusteir_]["r61_base"]} / ({$soma_base} - {$soma_base_fixo}) * {$valor_a_ratear}");
            } else {
              $novo_desconto = 0;
            }
            LogCalculoFolha::write(" - NovoDesconto = $novo_desconto");
            // percentual - se o inssirf estiver deslocado para as tabelas de;
            // previdencia dara erro;

            $perc_inss = $inssirf_r33_perc;

            if ($pessoal_1[0]["r01_tpcont"] == "13") {
              $perc_inss = 11;
            }

            $novop       = ($inssirf[0]["r33_codtab"] != "1"? 0: $perc_inss );

            $matriz1     = array();
            $matriz2     = array();

            $matriz1[1]  = "r61_novod";
            $matriz1[2]  = "r61_novop";

            $matriz2[1]  = round($novo_desconto,2);
            $matriz2[2]  = round($novop,2);

            $registroa   = $ajusteir_[$Iajusteir_]["r61_regist"];
            $qual_folha  = strtoupper($ajusteir_[$Iajusteir_]["r61_folha"]);

            $condicaoaux  = " and r61_numcgm = ".db_sqlformat($pessoal_1[0]["r01_numcgm"] );
            $condicaoaux .= " and r61_rubric = ".db_sqlformat($rubrica_base );
            $condicaoaux .= " and r61_regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
            $condicaoaux .= " and upper(r61_folha) = ".db_sqlformat($qual_folha );

            db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_").$condicaoaux );

            $rubrica_desconto = $aRubricasDesconto[$rubrica_base];
            //echo "<BR> chegou aqui 6 :  novo valor --> $novo_desconto ---> rubrica --> $rubrica_desconto "; // reis
            $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
            $condicaoaux .= " and ".$sigla_ajuste."rubric = ".db_sqlformat($rubrica_desconto );

            if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {
              /**
               * Remove rubrica do ajusta caso esteja zerado;
               */
              if ($novo_desconto == 0) {
                db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
              } else {

                $matriz1 = array();
                $matriz2 = array();
                $matriz1[1] = $sigla_ajuste."valor";
                $matriz1[2] = $sigla_ajuste."quant";
                $matriz2[1] = round($novo_desconto,2);
                $matriz2[2] = $inssirf_r33_perc;

                LogCalculoFolha::write("Alterando valor do cálculo $arquivo, com os seguintes valores ");
                LogCalculoFolha::write(print_r(array_combine($matriz1, $matriz2),1));//"Alterando valor do cálculo $arquivo, com os seguintes valores:");
                LogCalculoFolha::write("  Através da Condição: ".bb_condicaosubpes($sigla_ajuste)."$condicaoaux");
                db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
              }

            } else {

              $matriz1    = array();
              $matriz2    = array();

              $matriz1[1] = $sigla_ajuste . "regist";
              $matriz1[2] = $sigla_ajuste . "rubric";
              $matriz1[3] = $sigla_ajuste . "lotac";
              $matriz1[4] = $sigla_ajuste . "pd";
              $matriz1[5] = $sigla_ajuste . "valor";
              $matriz1[6] = $sigla_ajuste . "quant";
              $matriz1[7] = $sigla_ajuste . "anousu";
              $matriz1[8] = $sigla_ajuste . "mesusu";
              $matriz1[9] = $sigla_ajuste . "instit";

              $matriz2[1] = $ajusteir_[$Iajusteir_]["r61_regist"];
              $matriz2[2] = $rubrica_desconto;
              $matriz2[3] = $pessoal_1[0]["r01_lotac"];
              $matriz2[4] = 2;
              $matriz2[5] = round($novo_desconto,2);
              $matriz2[6] = $inssirf_r33_perc;
              $matriz2[7] = $anousu;
              $matriz2[8] = $mesusu;
              $matriz2[9] = $DB_instit;

              $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
              $condicaoaux .= " and ".$sigla_ajuste."rubric = ".db_sqlformat($rubrica_desconto );
              if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {
                if ($novo_desconto > 0) {
                  db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
                } else {
                  //  echo "<BR> passou aqui 7 ".$ajusteir_[$Iajusteir_]["r61_regist"];
                  db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
                }
              } else {
                if ($novo_desconto > 0) {
                  if ($arquivo == "gerfres" || $arquivo == "gerffer") {
                    $matriz1[10] = $sigla_ajuste."tpp";
                    $matriz2[10] = " ";
                  }
                  db_insert($arquivo, $matriz1, $matriz2 );
                }
              }
            }

            $tot_desc       = 0;
            $tot_prov       = 0;
            $salfamilia     = 0;
            $tot_liq        = 0;
            $salario_esposa = 0;
            global $$arquivo;
            $condicaoaux    = " and ".$sigla_ajuste."regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"]);

            db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux );
            $arq_ = $$arquivo;

            for ($Iarquivo=0; $Iarquivo <count($arq_); $Iarquivo++) {

              //echo "<BR> rubrica 71 -->".$arq_[$Iarquivo][$sigla_ajuste."rubric"]."  valor --> ".$arq_[$Iarquivo][$sigla_ajuste."valor"]." tipo --> ".$arq_[$Iarquivo][$sigla_ajuste."pd"];
              // reis
              if (substr("#".$arq_[$Iarquivo][$sigla_ajuste."rubric"],1,1) != "R" ) {
                // caso especial para salario_esposa;
                if (trim($db21_codcli)== "999999999" && $arq_[$Iarquivo][$sigla_ajuste."rubric"] == "0045" ) {
                  $salario_esposa += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                } else {
                  if ($arq_[$Iarquivo][$sigla_ajuste."pd"] == 1) {
                    $tot_prov += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                  } else if ($arq_[$Iarquivo][$sigla_ajuste."pd"] == 2) {
                    $tot_desc += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                    if ($db_debug == true) { echo "[ajusta_irrf] 24 - tot_desc: $tot_desc<br>"; }
                  }
                }
              } else {

                if ($arq_[$Iarquivo][$sigla_ajuste."rubric"] == "R927" || $arq_[$Iarquivo][$sigla_ajuste."rubric"] == "R929") {

                  $tot_desc += round($arq_[$Iarquivo][$sigla_ajuste."valor"],2);
                  if ($db_debug == true) { echo "[ajusta_irrf] 25 - tot_desc: $tot_desc<br>"; }
                } elseif($arq_[$Iarquivo][$sigla_ajuste."rubric"] != "R928") {

                  $nro = db_val(substr("#".$arq_[$Iarquivo][$sigla_ajuste."rubric"],3,2))  ;
                  // rubricas de 901 a 915 sao referentes a previdencia...;
                  if ($nro >= 17 && $nro <= 22) {
                    $salfamilia = round($arq_[$Iarquivo][$sigla_ajuste."valor"],2);
                  }elseif ($nro <= 50) {

                    if ($arq_[$Iarquivo][$sigla_ajuste."pd"] == 1) {
                      $tot_prov += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                    } else if ($arq_[$Iarquivo][$sigla_ajuste."pd"] == 2) {
                      $tot_desc += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                      if ($db_debug == true) { echo "[ajusta_irrf] 26 - tot_desc: $tot_desc<br>"; }
                    }
                  }
                }
              }
            }
            if (trim($db21_codcli ) == "999999999" ||  trim($db21_codcli ) == "18" ) {
              $salario_familia = 0;
            }
            if (!db_empty($tot_prov) || !db_empty($tot_desc)) {
              //                  $tot_prov = db_val(substr("#".db_str($tot_prov,22,5),1,19));
              //                  $tot_desc = db_val(substr("#".db_str($tot_desc,22,5),1,19));
              if ($tot_prov > $tot_desc) {
                //echo "<BR> rubrica 72 proventos --> $tot_prov   descontos ---> $tot_desc salario esposa --> $salario_esposa"; //reis
                $r01_rubric = "R926";
                $tot_liq = $tot_prov + $salario_esposa - $tot_desc;
                $arredn = arredonda_100($tot_liq, $cfpess[0]["r11_arredn"]);
                //echo "<BR> rubrica 73 r926  arredn --> $arredn"; //reis
                $tot_liq += $arredn;
              } else {
                $arredn = $tot_desc + $salario_esposa - $tot_prov;
                $r01_rubric = "R928";
                //echo "<BR> rubrica 72 r928 arredn --> $arredn proventos --> $tot_prov   descontos ---> $tot_desc salario esposa --> $salario_esposa"; //reis
              }

              LogCalculoFolha::write("Valor que será adicionado a tabela de Cálculo: {$arredn}");
              if ($arredn > 0 && $arquivo != "gerfres") {
                $matriz1 = array();
                $matriz2 = array();
                $matriz1[1] = $sigla_ajuste."regist";
                $matriz1[2] = $sigla_ajuste."rubric";
                $matriz1[3] = $sigla_ajuste."lotac";
                $matriz1[4] = $sigla_ajuste."pd";
                $matriz1[5] = $sigla_ajuste."valor";
                $matriz1[6] = $sigla_ajuste."quant";
                $matriz1[7] = $sigla_ajuste."anousu";
                $matriz1[8] = $sigla_ajuste."mesusu";
                $matriz1[9] = $sigla_ajuste."instit";

                $matriz2[1] = $ajusteir_[$Iajusteir_]["r61_regist"];
                $matriz2[2] = $r01_rubric;
                $matriz2[3] = $pessoal_1[0]["r01_lotac"];
                $matriz2[4] = 1;
                $matriz2[5] = $arredn;
                $matriz2[6] = 0;
                $matriz2[7] = $anousu;
                $matriz2[8] = $mesusu;
                $matriz2[9] = $DB_instit;

                $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
                $condicaoaux .= " and ".$sigla_ajuste."rubric = ".db_sqlformat($r01_rubric );


                if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {
                  db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
                } else {
                  //echo "<BR> chegou aqui 11"; //reis
                  db_insert($arquivo, $matriz1, $matriz2 );
                }
              }
            }

          }
        }
        LogCalculoFolha::write("WHILE_1_FIM: ");
      }
    }
    LogCalculoFolha::write("-----------------Finalizando método AjusteIRRF::ajustar-----------------");
  }

  /**
   * Retorno separadamente os valores de Base de IRRF e o valor de dedução, separadamente.
   * As rubricas que devem ser consideradas para o somatorio de base e de dedução, devem ser informadas
   * por parâmetros.
   *
   * @param  CalculoFolha $oCalCuloServidor  Objeto com a folha de pagamento que se deseja obter os valores.
   * @param  String    $sRubricaBaseIrrf  Rubrica a ser considerada para a base de IRRF.
   * @param  String    $sRubricaDeducao   Rubrica a ser considerada para a base de dedução.
   *
   * @return Object objeto com o valor de BaseIRRF e ValorDeducao
   */
  static public function getEventosBaseCalculoImposto(CalculoFolha $oCalCuloServidor, $sRubricaBaseIrrf, $sRubricaDeducao) {

    $oValoresRetorno = new stdClass();
    $oValoresRetorno->nValorBaseIrrf = 0.0;
    $oValoresRetorno->nValorDeducao  = 0.0;

    $aEventosFinanceirosServidor = $oCalCuloServidor->getEventosFinanceiros(null, array($sRubricaBaseIrrf, $sRubricaDeducao));

    if (!empty($aEventosFinanceirosServidor)) {

      foreach ($aEventosFinanceirosServidor as $oEventoFinanceiro) {

        switch ($oEventoFinanceiro->getRubrica()->getCodigo()) {

          case $sRubricaBaseIrrf:
            $oValoresRetorno->nValorBaseIrrf += $oEventoFinanceiro->getValor();
            break;

          case $sRubricaDeducao:
            $oValoresRetorno->nValorDeducao += $oEventoFinanceiro->getValor();
            break;
        }
      }
    }

    return $oValoresRetorno;
  }
}
