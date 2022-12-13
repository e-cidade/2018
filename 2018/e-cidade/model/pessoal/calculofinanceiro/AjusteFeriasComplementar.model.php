<?php

/**
 * Realiza o lançamento dos registros financeiro no pontofs ou no pontocom no final 
 * do cálculo de férias quando é utilizado a estrutura da complementar.
 */
abstract class AjusteFeriasComplementar {
  
  /**
   * Realiza o lançamento dos registros financeiro no pontofs ou no pontocom dependendo
   * de qual ponto que foi selecionado no cadsatro de férias do servidor.
   */
  public static function lancarRegistrosPonto($iMatricula, $Ipessoal) {

    global $pessoal, $F019;

    $pessoal[$Ipessoal]["r01_regist"] = $iMatricula;

    ferias($pessoal[$Ipessoal]["r01_regist"]," ");

    $base_prev  = 0;
    $prev_desc  = 0;
    $base_irf   = 0;
    $r14_pd     = 0;
    $r14_valor  = 0;
    $r14_quant  = 0;
    $salfamilia = 0;
    $tot_prov   = 0;
    $tot_desc   = 0;

    $r110_regist = $pessoal[$Ipessoal]["r01_regist"];
    $r110_lotac  = $pessoal[$Ipessoal]["r01_lotac"];

    /**
     * Verificamos se as férias pagam no salario ou na complementar
     */
    $iTipoFolha = AjusteFeriasComplementar::verificaPagamento($iMatricula);

    $opcao_geral = $iTipoFolha;
  
    if ($opcao_geral == PONTO_SALARIO) {

      $naoencontroupontosalario  = false;
      $condicaoaux               = " and r10_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux              .= " order by r10_regist,r10_rubric ";
      $sSqlValidacaoPontoSalario = "select * from pontofs ".bb_condicaosubpes("r10_" ).$condicaoaux;
        
      global $pontofs;

      if ( !db_selectmax("pontofs", $sSqlValidacaoPontoSalario) ) {

        $naoencontroupontosalario = true;
        LogCalculoFolha::write("Numero de Dias a Pagar no mês..: {$F019}");

          if ($F019 < 30) {
            
            // F019 - Numero de dias a pagar no mes
            $opcao_geral = 8;
            verifica_ferias_100();

            $condicaoaux = " and r10_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " order by r10_regist,r10_rubric ";

            LogCalculoFolha::write("|Verfificando Ponto Vazio");
            if (!db_selectmax("pontofs", "select * from pontofs ".bb_condicaosubpes("r10_" ).$condicaoaux )) {

              if ( $opcao_geral == PONTO_SALARIO ) {
                LogCalculoFolha::write("|Arredondamento:" . $pessoal[$Ipessoal]["r01_arredn"]);
                if (!db_empty($pessoal[$Ipessoal]["r01_arredn"])) {

                  $tot_desc += $pessoal[$Ipessoal]["r01_arredn"];
                  $tot_prov += $pessoal[$Ipessoal]["r01_arredn"];
                  $gerou_rubrica_calculo = true;

                  $matriz1 = array();
                  $matriz2 = array();

                  $matriz1[1]  = "r14_regist";
                  $matriz1[2]  = "r14_rubric";
                  $matriz1[3]  = "r14_lotac";
                  $matriz1[4]  = "r14_valor";
                  $matriz1[5]  = "r14_quant";
                  $matriz1[6]  = "r14_pd";
                  $matriz1[7]  = "r14_semest";
                  $matriz1[8]  = "r14_anousu";
                  $matriz1[9]  = "r14_mesusu";
                  $matriz1[10] = "r14_instit";

                  $matriz2[1]  = $r110_regist;
                  $matriz2[2]  = $pessoal[$Ipessoal]["r01_rubric"];
                  $matriz2[3]  = $r110_lotac;
                  $matriz2[4]  = $pessoal[$Ipessoal]["r01_arredn"];
                  $matriz2[5]  = 0;
                  $matriz2[6]  = 2;
                  $matriz2[7]  = 0;
                  $matriz2[8]  = $anousu;
                  $matriz2[9]  = $mesusu;
                  $matriz2[10] = $DB_instit;

                  $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist );
                  $condicaoaux .= " and r14_pd = 2 ";
                  $condicaoaux .= " and r14_rubric = ".db_sqlformat($pessoal[$Ipessoal]["r01_rubric"] );

                  if (db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
                    db_update("gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_" ).$condicaoaux );
                  } else {
                    db_insert("gerfsal", $matriz1, $matriz2 );
                  }
                  $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist );
                  $condicaoaux .= " and r14_pd = 1 ";
                  $condicaoaux .= " and r14_rubric = 'R928'";

                  $matriz2[2] = "R928";
                  $matriz2[6] = 1;

                  if (db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {

                    db_update("gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_" ).$condicaoaux );
                  } else {
                    db_insert("gerfsal", $matriz1, $matriz2 );
                  }
                } else {

                  LogCalculoFolha::write("Saindo do Laço pois não trata-se de arredondadmento de salario ou insuficiencia na competencia anterior... continue");
                  continue;
                }
              } else {
                LogCalculoFolha::write("Saindo do pois não é cálculo de salario... continue");
                continue;
              }
            } else {
              LogCalculoFolha::write("Não entrou na lógica por não ter Ponto de Salário");
            }
          } else if ($F019 == 30 ) {

            $opcao_geral = 8;
            verifica_ferias_100();
          } else {
            $tot_prov =0;
            $tot_desc =0;
          }
        } else {

          if ($F019 > 0 || $F020 > 0 || $F023 > 0) {

            $opcao_geral = 8;
            verifica_ferias_100();
          }
        }
      } else {

        $condicaoaux = " and r47_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
        $condicaoaux .= " order by r47_regist,r47_rubric ";
        global $pontocom;
        if (!db_selectmax("pontocom", "select * from pontocom ".bb_condicaosubpes("r47_" ).$condicaoaux )) {
          
          if ($F019 < 30) {
            // F019 - Numero de dias a pagar no mes
            $tot_prov =0;
            $tot_desc =0;
            if ($db_debug == true) {
              echo "[gerfsal] 24 - tot_desc: $tot_desc<br>";
            }

            $opcao_geral = 8;
            verifica_ferias_100();

            $condicaoaux = " and r47_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " order by r47_regist,r47_rubric ";

            if (!db_selectmax("pontocom", "select * from pontocom ".bb_condicaosubpes("r47_" ).$condicaoaux )) {
              LogCalculoFolha::write("Saiu por dados no ponto complementar... continue");
            }
          } else if ($F019 == 30) {

            // F019 - Numero de dias a pagar no mes
            $opcao_geral = 8;
            verifica_ferias_100();
          } else {
            $tot_prov = 0;
            $tot_desc = 0;
          }
        } else {

          if ($F019 > 0 || $F020 > 0 || $F023 > 0) {

            $opcao_geral = 8;
            verifica_ferias_100();
          }
        }
    }

    $opcao_geral = 8;
  }


  /**
   * Função reponsavel por verificar se o pagamento das férias 
   * será efetuado no salário ou na Complementar.
   *
   * @param  integer  $iMatricula
   * @return integer  - 8 - Folha Complementar.
   *                  - 1 - Folha Salário.
   */
  private static function verificaPagamento($iMatricula) {

    $iAnoFolha = DBPessoal::getAnoFolha();
    $iMesFolha = DBPessoal::getMesFolha();
    
    $sWhere   = "    r30_regist = {$iMatricula} ";
    $sWhere .= "and r30_anousu = {$iAnoFolha}  ";
    $sWhere .= "and r30_mesusu = {$iMesFolha}  ";
    $sWhere .= "and r30_proc1 = '{$iAnoFolha}/{$iMesFolha}'";

    $oDaoCadFeria = new cl_cadferia();
    $sSqlCadFeria = $oDaoCadFeria->sql_query_file(null, "r30_ponto", null, $sWhere);
    $rsCadFeria   = db_query($sSqlCadFeria);

    if (!$rsCadFeria) {
      throw new DBException(pg_last_error());
    }

    if (pg_num_rows($rsCadFeria) > 0) {

      $sTipoPonto = db_utils::fieldsMemory($rsCadFeria, 0)->r30_ponto;

      if ($sTipoPonto == 'C') {
        return 8;
      }
    }
    
    return  1;
  }
}

?>