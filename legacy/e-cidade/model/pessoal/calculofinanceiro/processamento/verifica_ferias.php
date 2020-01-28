<?php

function verificarFerias($lCalculoFeriasEfetuado) {

  global $pessoal , $Ipessoal, $cadferia, $pontofe_, $opcao_geral,$calcular_tipo_proc,
    $subpes, $db21_codcli, $gerffer_, $cfpess ,$F019,$inssirf_base_ferias;

  global $anousu, $mesusu, $DB_instit;

  global $F016,
    $F017,
    $F018,
    $F019,
    $F020,
    $F021,
    $F023;

  global $rub_especial_ferias,      // $cfpess[0]["r11_ferias"]
    $rub_especial_ferias_abono,     // $cfpess[0]["r11_fadiab"] ou(e) $cfpess[0]["r11_ferabo"] // Abono de Ferias + Adiantamento de Abono de Ferias
    $rub_especial_ferias_13,        // $cfpess[0]["r11_fer13"] // 1/3 de Ferias
    $rub_especial_ferias_13a,       // $cfpess[0]["r11_fadiab"] ou(e) $cfpess[0]["r11_fer13a"] --> Rubrica onde será lançado o adiantamento s/abono de férias
    $rub_especial_ferias_adia,      // $cfpess[0]["r11_feradi"]
    $rub_especial_ferias_descontos, // $cadferia[0]["r30_vfgt1"]
    $gravar_valor_adiantamento_ferias_a_descontar,  // $cadferia[0]["r30_vfgt1"]
    $valor_adiantamento_abono_a_descontar;
  // $cadferia[0]["r30_vliq1"]

  $ir_calculado_ferias = false;
  $gravar_valor_adiantamento_ferias_a_descontar=0;
  $valor_adiantamento_abono_a_descontar=0;
  $func_em_ferias = false;

  //echo "<BR> data de rescisao --> ".$pessoal[$Ipessoal]["r01_recis"];

  // r11_palime --> Rubrica de Pensao alimenticia
  $rubrica_ferias_pensao = db_str((db_val($cfpess[0]["r11_palime"] )+2000),4,0,"0" );

  if (db_empty($pessoal[$Ipessoal]["r01_recis"])) {

    // Nao esta rescindido o contrato

    // Calculo especifico para 999999999

    $condicaoaux  = " and r30_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] ) ;
    $condicaoaux .= " order by r30_perai desc";
    
    if (db_selectmax("cadferia", "select * from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux )) {

      // Tem que ter cadastrado as ferias

      if (db_empty($cadferia[0]["r30_proc2"]) ) {

        $r30_proc = "r30_proc1";
        $r30_peri = "r30_per1i";
        $r30_perf = "r30_per1f";
      } else {

        $r30_proc = "r30_proc2";
        $r30_peri = "r30_per2i";
        $r30_perf = "r30_per2f";
      }
      //echo "<BR> verifica_ferias_100 entrou aqui --> ".strtolower($cadferia[0]["r30_ponto"]);

      ferias($pessoal[$Ipessoal]["r01_regist"]," ");
      // F019 - Numero de dias a pagar no mes
      // F020 - Numero de dias abono p/ pagar no mes
      // F021 - Numero de dias p/ calc do FGTS no mes



      if (( $opcao_geral == PONTO_SALARIO && strtolower($cadferia[0]["r30_ponto"]) == "s" )
        ||
        ( $opcao_geral == PONTO_SALARIO && $cadferia[0][$r30_proc]  < $subpes )
        ||
        ( $opcao_geral == PONTO_SALARIO && $cadferia[0][$r30_proc] == $subpes && strtolower($cadferia[0]["r30_ponto"]) == "c"
        && ('t' == $cadferia[0]["r30_paga13"]) && strtolower($cfpess[0]["r11_fersal"]) == "f" )
        ||
        ( $opcao_geral == PONTO_COMPLEMENTAR && strtolower($cadferia[0]["r30_ponto"]) == "c" && $cadferia[0][$r30_proc] == $subpes ) ) {


        // 1 - Repassar para salario as rubricas de ferias que tem o 1/3 pago na
        //     complentar quando fersal = F

        // 2 - Quando paga abono e é pagamento so de 1/3 e na complementar deve
        //     lancar o abono inteiro na complementar

        $mes_gozo = db_str(db_val(substr("#". $cadferia[0][$r30_peri],0,4) ),4 )."/".db_str(db_month($cadferia[0][$r30_peri] ),2,0,'0');

        $pagar_so_1_3_na_complementar = false;
        $paga_como_ferias = false;
        if ($cadferia[0][$r30_proc] == $subpes && strtolower($cadferia[0]["r30_ponto"]) == "c"
          && ('t' == $cadferia[0]["r30_paga13"]) ) {
          $pagar_so_1_3_na_complementar = true ;
          if (strtolower($cfpess[0]["r11_fersal"]) == "f") {
            $paga_como_ferias  = true;
          }
        }

        $ferias_paga_1_3 = false;

        if (strtolower($cfpess[0]["r11_fersal"]) == "f") {
          if ($cadferia[0]["r30_paga13"] == 't') {
            $ferias_paga_1_3 = true;
          } else {
            $ferias_paga_1_3 = false;
          }
        }


        $ir_calculado_ferias = false;

        if ($ferias_paga_1_3  || 'f' == $cadferia[0]["r30_paga13"]) {
          $ir_calculado_ferias = true;
        } else if (strtolower($cfpess[0]["r11_fersal"]) == "s" && 't' == $cadferia[0]["r30_paga13"]) {
          $ir_calculado_ferias = false;
        }

        //echo "<BR> r11_fersal --> ".$cfpess[0]["r11_fersal"];
        //echo "<BR> r30_paga13 --> ".$cadferia[0]["r30_paga13"];
        //echo "<BR> ir_calculado_ferias --> ".($ir_calculado_ferias?'1':'0')."   <---";
        $calcular_tipo_proc = false;
        if (($opcao_geral == PONTO_COMPLEMENTAR && strtolower($cadferia[0]["r30_ponto"]) == "c")
          || ($opcao_geral == PONTO_SALARIO && strtolower($cadferia[0]["r30_ponto"]) == "s" )  ) {
          $calcular_tipo_proc = true;
        }

        $condicaoaux = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
        global $gerffer_;
        if (db_selectmax("gerffer_", "select * from gerffer ".bb_condicaosubpes("r31_" ).$condicaoaux )) {
          $gravar_valor_adiantamento_ferias_a_descontar = 0;
          $valor_adiantamento_abono_a_descontar = 0;
          $rub_especial_ferias = 0;
          $rub_especial_ferias_abono = 0;
          $rub_especial_ferias_13 = 0;
          $rub_especial_ferias_13a = 0;
          $rub_especial_ferias_adia = 0;
          $rub_especial_ferias_descontos = 0;
          for ($Igerffer=0; $Igerffer<count($gerffer_); $Igerffer++) {
            //echo "<BR> verifica_ferias rubrica --> ".$gerffer_[$Igerffer]["r31_rubric"]." valor   --> ".$gerffer_[$Igerffer]["r31_valor"]." quant   --> ".$gerffer_[$Igerffer]["r31_quant"]." r31_tpp --> ".strtolower($gerffer_[$Igerffer]["r31_tpp"]);
            //echo "<BR> 0 r10_rubric = ".$gerffer_[$Igerffer]["r31_rubric"]." gravar_valor_adiantamento_ferias_a_descontar --> $gravar_valor_adiantamento_ferias_a_descontar";
            //echo "<BR> 0 r10_rubric = ".$gerffer_[$Igerffer]["r31_rubric"]." rub_especial_ferias_adia --> $rub_especial_ferias_adia";
            if (substr("#".$gerffer_[$Igerffer]["r31_rubric"],1,1) == "R"
              && ( ( db_val(substr("#".$gerffer_[$Igerffer]["r31_rubric"],2,3)) > 900
              && db_val(substr("#".$gerffer_[$Igerffer]["r31_rubric"],2,3))<915
            )
            || db_val(substr("#".$gerffer_[$Igerffer]["r31_rubric"],2,3)) == 916
          )
            ) {
            //   R901 % Inss S/ SALÁRIO DESCONTO -
            //   R902 % Inss S/ 13o SALÁRIO DESCONTO -
            //   R903 % Inss S/ FÉRIAS DESCONTO -
            //   R904 % Funpas S/SALARIO DESCONTO -
            //   R905 % Funpas S/13§ SALARIO DESCONTO -
            //   R906 % Funpas S/FERIAS DESCONTO -
            //   R907 % F Inativos S/SALARIO DESCONTO
            //   R908 % F Inativos S/13§ SALARIO DESCONTO -
            //   R909 % F Inativos S/FERIAS DESCONTO -
            //   R910 % Previdencia 4 S/SALARIO DESCONTO -
            //   R911 % Previdencia 4 S/13§ SALARIO DESCONTO -
            //   R912 % Previdencia 4 S/FERIAS DESCONTO -
            //   R913 % IRRF S/SALARIO DESCONTO -
            //   R914 % IRRF S/13. SALARIO DESCONTO -
            //   R915 % IRRF S/FERIAS DESCONTO -
            //   R916 VALE TRANSPORTE DESCONTO
            if( 'f' == $cadferia[0]["r30_paga13"]){
              if (strtolower($cfpess[0]["r11_fersal"]) == "f" && 't' == $cfpess[0]["r11_recalc"] ){
                $rub_especial_ferias_descontos += $gerffer_[$Igerffer]["r31_valor"];
                if (strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "d" ) {
                  $rub_especial_ferias_adia -= $gerffer_[$Igerffer]["r31_valor"];
                  //echo "<BR> 2 r10_rubric = ".$gerffer_[$Igerffer]["r31_rubric"]." valor --> ".$gerffer_[$Igerffer]["r31_valor"];
                  //echo "<BR> 1 r10_rubric = ".$gerffer_[$Igerffer]["r31_rubric"]." rub_especial_ferias_adia --> $rub_especial_ferias_adia";
                }
              }

              //$rub_especial_ferias_descontos += $gerffer_[$Igerffer]["r31_valor"];
            }
            //echo "<BR> 4.2 rubric = ".$gerffer_[$Igerffer]["r31_rubric"]." valor_ferias_descontos --> $rub_especial_ferias_descontos";
            } else if ($gerffer_[$Igerffer]["r31_rubric"] == $cfpess[0]["r11_ferant"]) {
              // Ferias mes anterior
              if ($opcao_geral == PONTO_SALARIO) {
                $matriz1 = array();
                $matriz2 = array();

                $matriz1[1] = "r10_regist";
                $matriz1[2] = "r10_rubric";
                $matriz1[3] = "r10_lotac";
                $matriz1[4] = "r10_quant";
                $matriz1[5] = "r10_valor";
                $matriz1[6] = "r10_datlim";
                $matriz1[7] = "r10_anousu";
                $matriz1[8] = "r10_mesusu";
                $matriz1[9] = "r10_instit";
                ////echo "<BR> r11_ferant --> ".$cfpess[0]["r11_ferant"];
                $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
                $matriz2[2] = $cfpess[0]["r11_ferant"];
                $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
                $matriz2[4] = $F019;
                // F019 - Numero de dias a pagar no mes
                $matriz2[5] = $gerffer_[$Igerffer]["r31_valor"];
                $matriz2[6] = str_pad(" ",7);
                $matriz2[7] = $anousu ;
                $matriz2[8] = $mesusu;
                $matriz2[9] = $DB_instit;
                LogCalculoFolha::write("Inserindo dados na Tabela de Ponto de Salário");
                if(!$lCalculoFeriasEfetuado){
                  $retornar = db_insert("pontofs",$matriz1, $matriz2 );
                }
              }
            } else if ($gerffer_[$Igerffer]["r31_rubric"] == $cfpess[0]["r11_feabot"]) {
              // Abono mes anterior
              if ($opcao_geral == PONTO_SALARIO) {

                $matriz1 = array();
                $matriz2 = array();

                $matriz1[1] = "r10_regist";
                $matriz1[2] = "r10_rubric";
                $matriz1[3] = "r10_lotac";
                $matriz1[4] = "r10_quant";
                $matriz1[5] = "r10_valor";
                $matriz1[6] = "r10_datlim";
                $matriz1[7] = "r10_anousu";
                $matriz1[8] = "r10_mesusu";
                $matriz1[9] = "r10_instit";
                //echo "<BR> r11_feabot --> ".$cfpess[0]["r11_feabot"];
                $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
                $matriz2[2] = $cfpess[0]["r11_feabot"];
                $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
                $matriz2[4] = 0;
                $matriz2[5] = $gerffer_[$Igerffer]["r31_valor"];
                $matriz2[6] = str_pad(" ", 7 );
                $matriz2[7] = $anousu;
                $matriz2[8] = $mesusu;
                $matriz2[9] = $DB_instit;
                LogCalculoFolha::write("Inserindo dados na Tabela de Ponto de Salário");
                if(!$lCalculoFeriasEfetuado){
                  $retornar = db_insert("pontofs",$matriz1, $matriz2 );
                }
              }
            } else if($gerffer_[$Igerffer]["r31_rubric"] == "R931" ||
              $gerffer_[$Igerffer]["r31_rubric"] == "R932" ||
              $gerffer_[$Igerffer]["r31_rubric"] == "R940") {

              if ($gerffer_[$Igerffer]["r31_rubric"] == "R931") {
                // 1/3 DE FERIAS
                //echo "<BR> calcular_tipo_proc --> $calcular_tipo_proc";
                //echo "<BR> r30_paga13 --> ".$cadferia[0]["r30_paga13"];
                // Calculo para ferias na complementar ou salario
                if ($calcular_tipo_proc
                  || ( $opcao_geral == PONTO_SALARIO  && 'f' == $cadferia[0]["r30_paga13"]
                  && $cadferia[0][$r30_proc] < $subpes )
                  || ( $opcao_geral == PONTO_SALARIO
                  && ( ( db_month($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],6,2))
                  && db_year($cadferia[0][$r30_peri]) == db_val(substr("#".$cadferia[0][$r30_proc],1,4)))
                  || ( db_month($cadferia[0][$r30_peri]) < db_val(substr("#".$cadferia[0][$r30_proc],6,2))
                  && db_year($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],1,4))) ))
                  || ($opcao_geral == PONTO_SALARIO
                  && $ferias_paga_1_3
                  && db_val(substr("#".$cadferia[0][$r30_proc],6,2)) == db_month($cadferia[0][$r30_peri])
                  && $cadferia[0][$r30_proc] < $subpes ) ) {
                  $rub_especial_ferias_13 += $gerffer_[$Igerffer]["r31_valor"];

                  // grava o valor da variavel "valor_ferias_13" na rubrica --> $cfpess[0]["r11_fer13"]

                  //echo "<BR> valor_ferias_13 --> $rub_especial_ferias_13";
                }

              } else if ($gerffer_[$Igerffer]["r31_rubric"] == "R932") {
                // 1/3 DE ABONO PECUNIARIO
                if ($calcular_tipo_proc
                  || ( $opcao_geral == PONTO_SALARIO
                  && ( ( db_month($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],6,2))
                  && db_year($cadferia[0][$r30_peri]) == db_val(substr("#".$cadferia[0][$r30_proc],1,4)))
                  || ( db_month($cadferia[0][$r30_peri]) < db_val(substr("#".$cadferia[0][$r30_proc],6,2))
                  && db_year($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],1,4))) )) ) {
                  $rub_especial_ferias_13a += $gerffer_[$Igerffer]["r31_valor"];
                }

              } else if ($gerffer_[$Igerffer]["r31_rubric"] == "R940") {
                // 1/3 ADIANTAMENTO FERIAS
                if (('t' == $cadferia[0]["r30_paga13"]) && 'f' == $cfpess[0]["r11_recalc"]) {
                  // r11_recalc --> Recalcula 1/3 ferias mes gozo
                  if ($calcular_tipo_proc) {
                    $rub_especial_ferias_13 += $gerffer_[$Igerffer]["r31_valor"];
                  }
                } else {
                  $rub_especial_ferias_adia += $gerffer_[$Igerffer]["r31_valor"];
                }
              }
              // Proventos
            } else if ($gerffer_[$Igerffer]["r31_pd"] == 1
              &&
              substr("#".$gerffer_[$Igerffer]["r31_rubric"],1,1) != "R"
            ) {
            global $basesr;
            $achou = false;
            $condicaoaux  = " where rh54_base = ".db_sqlformat($inssirf_base_ferias );
            $condicaoaux .= " and rh54_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
              $condicaoaux .= " and rh54_rubric = ".db_sqlformat($gerffer_[$Igerffer]["r31_rubric"] );
              if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
                $achou = true;
                //echo "<BR> achou 0";
              }
            } else {
              $condicaoaux  = " and r09_base = ".db_sqlformat($inssirf_base_ferias );
              $condicaoaux .= " and r09_rubric = ".db_sqlformat($gerffer_[$Igerffer]["r31_rubric"] );
              //echo "<BR> condicaoaux 2 --> $condicaoaux";
              if (db_selectmax("basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )) {
                $achou = true;
                //echo "<BR> achou 1";
              }
            }

            //echo "<BR> nao achou na base inssirf_base_ferias lanca Rubrica ferias no ponto ou no salario";

            if (!$achou
              && (strtolower($cfpess[0]["r11_fersal"]) == "f" )
              && $cadferia[0][$r30_proc] <= $subpes  // Pagamento das ferias no mes do calculo da folha
            ) {
            //echo "<BR> passo aqui 2.0 -->".$gerffer_[$Igerffer]["r31_rubric"];

            //    Se nao faz parte da base de previdencia nao deve
            //  ser somado nas rubricas de ferias para o repasse
            //  para complementar /salario e sim trasferidas para
            //  o ponto a gerar


            if ($opcao_geral == PONTO_SALARIO  ) {
              //echo "<BR> Repassando para o salario ";
              // Repassa para o salario
              // vai gerar as 2000 mais um 1/3
              $matriz1 = array();
              $matriz2 = array();

              $matriz1[1] = "r10_regist";
              $matriz1[2] = "r10_rubric";
              $matriz1[3] = "r10_lotac";
              $matriz1[4] = "r10_quant";
              $matriz1[5] = "r10_valor";
              $matriz1[6] = "r10_datlim";
              $matriz1[7] = "r10_anousu";
              $matriz1[8] = "r10_mesusu";
              $matriz1[9] = "r10_instit";
              //echo "<BR> r31_rubric --> ".$gerffer_[$Igerffer]["r31_rubric"];
              $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
              $matriz2[2] = $gerffer_[$Igerffer]["r31_rubric"];
              $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
              $matriz2[6] = str_pad(" ", 7 );
              $matriz2[7] = $anousu ;
              $matriz2[8] = $mesusu;
              $matriz2[9] = $DB_instit;

              $condicaoaux  = " and r10_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
              $condicaoaux .= " and r10_rubric = ".db_sqlformat($gerffer_[$Igerffer]["r31_rubric"] );
              global $transacao;

              if(
                (strtolower($gerffer_[$Igerffer]["r31_tpp"]) != "d" && $cadferia[0]["r30_paga13"] == 't' )
                || $cadferia[0]["r30_paga13"] == 'f'
              ) {
              $OK = true;
              if(strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "a" ){
                $OK = false;
                if($calcular_tipo_proc && $cadferia[0][$r30_proc] == $subpes ){
                  $OK = true;
                  $valor_adiantamento_abono_a_descontar  += $gerffer_[$Igerffer]["r31_valor"];
                }else if ($cadferia[0][$r30_proc] < $subpes){
                  $OK = true;
                  $valor_adiantamento_abono_a_descontar  += $gerffer_[$Igerffer]["r31_valor"];
                }
              }
              if($OK){
                if (db_selectmax("transacao", "select * from pontofs ".bb_condicaosubpes("r10_" ).$condicaoaux )) {
                  $matriz2[4] = ($transacao[0]["r10_quant"] +  $gerffer_[$Igerffer]["r31_quant"] );
                  $matriz2[5] = ($transacao[0]["r10_valor"] +  $gerffer_[$Igerffer]["r31_valor"] );
                  $retornar = db_update("pontofs",$matriz1, $matriz2 , bb_condicaosubpes("r10_" ).$condicaoaux );
                } else {
                  $matriz2[4] = ( $gerffer_[$Igerffer]["r31_quant"] );
                  $matriz2[5] = ( $gerffer_[$Igerffer]["r31_valor"] );
                  LogCalculoFolha::write("Inserindo dados na Tabela de Ponto de Salário");
                  if(!$lCalculoFeriasEfetuado){
                    $retornar = db_insert("pontofs",$matriz1, $matriz2 );
                  }
                }
              }
              }
              if(strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "d" && $cadferia[0]["r30_paga13"] == 'f'){
                $gravar_valor_adiantamento_ferias_a_descontar += $gerffer_[$Igerffer]["r31_valor"];
              }
            } else {
              // Repassa para a complementar
              // vai gerar as 2000 mais 1/3
              $matriz1 = array();
              $matriz2 = array();

              $matriz1[1] = "r47_regist";
              $matriz1[2] = "r47_rubric";
              $matriz1[3] = "r47_lotac";
              $matriz1[4] = "r47_quant";
              $matriz1[5] = "r47_valor";
              $matriz1[6] = "r47_anousu";
              $matriz1[7] = "r47_mesusu";
              $matriz1[8] = "r47_instit";

              $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
              $matriz2[2] = $gerffer_[$Igerffer]["r31_rubric"];
              $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
              $matriz2[6] = $anousu ;
              $matriz2[7] = $mesusu;
              $matriz2[8] = $DB_instit;

              $condicaoaux  = " and r47_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
              $condicaoaux .= " and r47_rubric = ".db_sqlformat($gerffer_[$Igerffer]["r31_rubric"] );
              //echo "<BR> condicaoaux --> $condicaoaux";
              global $transacao;
              if(
                (strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "a" && $cadferia[0]["r30_paga13"] == 't' )
                || $cadferia[0]["r30_paga13"] == 'f'
              ) {
              $OK = true;
              if(strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "a" ){
                $OK = false;
                if($calcular_tipo_proc && $cadferia[0][$r30_proc] == $subpes ){
                  $OK = true;
                  $valor_adiantamento_abono_a_descontar  += $gerffer_[$Igerffer]["r31_valor"];
                }
              }
              if($OK){
                if (db_selectmax("transacao", "select * from pontocom ".bb_condicaosubpes("r47_" ).$condicaoaux )) {
                  $matriz2[4] = ($transacao[0]["r47_quant"] + $gerffer_[$Igerffer]["r31_quant"] );
                  $matriz2[5] = ($transacao[0]["r47_valor"] + $gerffer_[$Igerffer]["r31_valor"] );
                  if(!$lCalculoFeriasEfetuado){
                    $retornar = db_update("pontocom",$matriz1, $matriz2 , bb_condicaosubpes("r47_" ).$condicaoaux );
                  }
                }else{
                  $matriz2[4] = ( $gerffer_[$Igerffer]["r31_quant"] );
                  $matriz2[5] = ( $gerffer_[$Igerffer]["r31_valor"] );
                  LogCalculoFolha::write("Inserindo dados na Tabela de Ponto Complementar");
                  if(!$lCalculoFeriasEfetuado){
                    $retornar = db_insert("pontocom",$matriz1, $matriz2 );
                  }
                }
              }
              }
              if(strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "d" && $cadferia[0]["r30_paga13"] == 'f'){
                $gravar_valor_adiantamento_ferias_a_descontar += $gerffer_[$Igerffer]["r31_valor"];
              }
            }
            } else {
              // Achou na base inssirf_base_ferias lanca Rubrica ferias no ponto ou no salario
              //echo "<BR> r31_tpp --> ".strtolower($gerffer_[$Igerffer]["r31_tpp"]);
              if (strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "f") {
                if (( $calcular_tipo_proc && 'f' == $cadferia[0]["r30_paga13"] )
                  ||
                  ( $opcao_geral == PONTO_SALARIO && ( $ferias_paga_1_3 || ( 'f' == $cadferia[0]["r30_paga13"] && $cadferia[0][$r30_proc] < $subpes ) ) )
                ) {

                $rub_especial_ferias += $gerffer_[$Igerffer]["r31_valor"];
                //echo "<BR> 2 valor_ferias --> $rub_especial_ferias";
                }
              } else if (strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "d" ) {
                // adiantamento de ferias


                if (strtolower($cfpess[0]["r11_fersal"]) == "f" && 'f' == $cadferia[0]["r30_paga13"] ){
                  $rub_especial_ferias_adia += $gerffer_[$Igerffer]["r31_valor"];
                  $rub_especial_ferias_descontos += $gerffer_[$Igerffer]["r31_valor"];
                  //echo "<BR> 3 r10_rubric = ".$gerffer_[$Igerffer]["r31_rubric"]." rub_especial_ferias_adia --> $rub_especial_ferias_adia";
                }

              } else {
                if ($calcular_tipo_proc
                  || ( $opcao_geral == PONTO_SALARIO
                  && ( ( db_val(substr("#".$cadferia[0][$r30_proc],6,2)) < db_month($cadferia[0][$r30_peri])
                  && db_val(substr("#".$cadferia[0][$r30_proc],1,4)) == db_year($cadferia[0][$r30_peri] ) )
                  || db_val(substr("#".$cadferia[0][$r30_proc],1,4)) < db_year($cadferia[0][$r30_peri] ) )) ) {
                  $rub_especial_ferias_abono += $gerffer_[$Igerffer]["r31_valor"];
                }

              }
            }

            } else if ($gerffer_[$Igerffer]["r31_pd"] == 2 // Desconto
              && substr("#".$gerffer_[$Igerffer]["r31_rubric"],1,1) != "R"
            ) {
            //echo "<BR> passou aqui !!!!  rubrica --> ".$gerffer_[$Igerffer]["r31_rubric"]."  tpp --->".$gerffer_[$Igerffer]["r31_tpp"];
            // sandro
            $nr_dias_mes = pg_result(db_query("select ndias(".db_year($cadferia[0][$r30_peri] ).",".db_month($cadferia[0][$r30_peri] ).") as ndias "),0,"ndias");

            // adiantamento de ferias
            if(  strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "d"
              && strtolower($cfpess[0]["r11_fersal"]) == "f"
              && 'f' == $cadferia[0]["r30_paga13"]
              && 't' ==  $cfpess[0]["r11_recalc"]
            ) {

            // As rubricas de desconto tipo Adiantamento de Férias são subtraidos do Adiantamento de Férias, quando
            // Pagar como Férias e recalcula Sim e Pagar 1/3 Não

            $rub_especial_ferias_descontos += $gerffer_[$Igerffer]["r31_valor"];
            $rub_especial_ferias_adia -= $gerffer_[$Igerffer]["r31_valor"];
            //echo "<BR> 4 r10_rubric = ".$gerffer_[$Igerffer]["r31_rubric"]." rub_especial_ferias_adia --> $rub_especial_ferias_adia";
            } else if ( strtolower($gerffer_[$Igerffer]["r31_tpp"]) == "f"
              && ( $F019 == 30 && db_month($cadferia[0][$r30_peri]) == db_month($cadferia[0][$r30_perf]) || $F019 >= $nr_dias_mes )
              && db_str(db_year($cadferia[0][$r30_peri]),4,0)."/". db_str(db_month($cadferia[0][$r30_peri]),2,0,"0") == $subpes
              && $gerffer_[$Igerffer]["r31_rubric"] != db_str((db_val($cfpess[0]["r11_palime"])+2000),4,0 )
              && ( db_val($gerffer_[$Igerffer]["r31_rubric"]) > 2000 && db_val($gerffer_[$Igerffer]["r31_rubric"]) < 4000 )
            ) {

            // Quando as Férias é Integral repassa as rubricas do ponto Fixo para o salario ou complementar baseada nas rubricas
            // do ponto calculado das Férias tipo "F"

            $condicaoaux  = " and r90_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and r90_rubric = ".db_sqlformat(db_str(( db_val($gerffer_[$Igerffer]["r31_rubric"])-2000),4,0, "0") );
            Global $pontofx_;
            if (db_selectmax("pontofx_", "select * from pontofx ".bb_condicaosubpes("r90_" ).$condicaoaux )) {

              $matriz1 = array();
              $matriz2 = array();

              if ($opcao_geral == PONTO_SALARIO) {
                $matriz1[1] = "r10_regist";
                $matriz1[2] = "r10_rubric";
                $matriz1[3] = "r10_lotac";
                $matriz1[4] = "r10_quant";
                $matriz1[5] = "r10_valor";
                $matriz1[6] = "r10_datlim";
                $matriz1[7] = "r10_anousu";
                $matriz1[8] = "r10_mesusu";
                $matriz1[9] = "r10_instit";
                //echo "<BR> r90_rubric --> ".$pontofx_[0]["r90_rubric"];
                $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
                $matriz2[2] = $pontofx_[0]["r90_rubric"];
                $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
                $matriz2[4] = $pontofx_[0]["r90_quant"];
                $matriz2[5] = $pontofx_[0]["r90_valor"];
                $matriz2[6] = str_pad(" ", 7 );
                $matriz2[7] = $anousu ;
                $matriz2[8] = $mesusu;
                $matriz2[9] = $DB_instit;

                $condicaoaux  = " and r10_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
                $condicaoaux .= " and r10_rubric = ".db_sqlformat(db_str((db_val($gerffer_[$Igerffer]["r31_rubric"])-2000),4,0, "0") );
                if (db_selectmax("transacao", "select * from pontofs ".bb_condicaosubpes("r10_" ).$condicaoaux )) {
                  db_update("pontofs",$matriz1, $matriz2 , bb_condicaosubpes("r10_" ).$condicaoaux );
                } else {
                  LogCalculoFolha::write("Inserindo dados na Tabela de Ponto Salário");
                  if(!$lCalculoFeriasEfetuado){
                    db_insert("pontofs",$matriz1, $matriz2 );
                  }
                }
              } elseif( 'f' == $cadferia[0]["r30_paga13"] ) {
                $matriz1[1] = "r47_regist";
                $matriz1[2] = "r47_rubric";
                $matriz1[3] = "r47_lotac";
                $matriz1[4] = "r47_quant";
                $matriz1[5] = "r47_valor";
                $matriz1[6] = "r47_anousu";
                $matriz1[7] = "r47_mesusu";
                $matriz1[8] = "r47_instit";

                $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
                $matriz2[2] = $pontofx_[0]["r90_rubric"];
                $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
                $matriz2[4] = $pontofx_[0]["r90_quant"];
                $matriz2[5] = $pontofx_[0]["r90_valor"];
                $matriz2[6] = $anousu ;
                $matriz2[7] = $mesusu;
                $matriz2[8] = $DB_instit;

                $condicaoaux  = " and r47_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
                $condicaoaux .= " and r47_rubric = ".db_sqlformat(db_str((db_val($gerffer_[$Igerffer]["r31_rubric"])-2000),4,0, "0") );
                if (db_selectmax("transacao", "select * from pontocom ".bb_condicaosubpes("r47_" ).$condicaoaux )) {
                  if(!$lCalculoFeriasEfetuado){
                    db_update("pontocom",$matriz1, $matriz2 , bb_condicaosubpes("r47_" ).$condicaoaux );
                  }
                } else {
                  LogCalculoFolha::write("Inserindo dados na Tabela de Complementar");
                  if(!$lCalculoFeriasEfetuado){
                    db_insert("pontocom",$matriz1, $matriz2 );
                  }
                }
              }
            }
            } else {

              // Repassa a rubrica de Pensao Alimenticia de Férias para o salário ou a complementar

              //echo "<BR> passo aqui 2 -->".$gerffer_[$Igerffer]["r31_rubric"];
              if ($gerffer_[$Igerffer]["r31_rubric"] == $rubrica_ferias_pensao && $ir_calculado_ferias ) {
                // r11_fersal --> Paga como <F>erias ou <S>alario

                if ($opcao_geral == PONTO_SALARIO) {

                  if ($paga_como_ferias || !$pagar_so_1_3_na_complementar ) {

                    $matriz1 = array();
                    $matriz2 = array();

                    $matriz1[1] = "r10_regist";
                    $matriz1[2] = "r10_rubric";
                    $matriz1[3] = "r10_lotac";
                    $matriz1[4] = "r10_quant";
                    $matriz1[5] = "r10_valor";
                    $matriz1[6] = "r10_datlim";
                    $matriz1[7] = "r10_anousu";
                    $matriz1[8] = "r10_mesusu";
                    $matriz1[9] = "r10_instit";
                    ////echo "<BR> r31_rubric --> ".$gerffer_[$Igerffer]["r31_rubric"];
                    $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
                    $matriz2[2] = $gerffer_[$Igerffer]["r31_rubric"];
                    $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
                    $matriz2[6] = str_pad(" ", 7 );
                    $matriz2[7] = $anousu;
                    $matriz2[8] = $mesusu;
                    $matriz2[9] = $DB_instit;


                    $condicaoaux  = " and r10_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
                    $condicaoaux .= " and r10_rubric = ".db_sqlformat($gerffer_[$Igerffer]["r31_rubric"] );
                    global $transacao_;
                    if (db_selectmax("transacao_", "select * from pontofs ".bb_condicaosubpes("r10_" ).$condicaoaux )) {
                      $matriz2[4] = $transacao_[0]["r10_quant"] + $gerffer_[$Igerffer]["r31_quant"];
                      $matriz2[5] = $transacao_[0]["r10_valor"] + $gerffer_[$Igerffer]["r31_valor"];
                      db_update("pontofs",$matriz1, $matriz2 , bb_condicaosubpes("r10_" ).$condicaoaux );
                    } else {
                      $matriz2[4] = $gerffer_[$Igerffer]["r31_quant"];
                      $matriz2[5] = $gerffer_[$Igerffer]["r31_valor"];
                      LogCalculoFolha::write("Inserindo dados na Tabela de Ponto Salário");
                      if(!$lCalculoFeriasEfetuado){
                        db_insert("pontofs",$matriz1, $matriz2 );
                      }
                    }
                  }
                } else {
                  if (($pagar_so_1_3_na_complementar && !$paga_como_ferias) || !$pagar_so_1_3_na_complementar ) {

                    $matriz1 = array();
                    $matriz2 = array();

                    $matriz1[1] = "r47_regist";
                    $matriz1[2] = "r47_rubric";
                    $matriz1[3] = "r47_lotac";
                    $matriz1[4] = "r47_quant";
                    $matriz1[5] = "r47_valor";
                    $matriz1[6] = "r47_anousu";
                    $matriz1[7] = "r47_mesusu";
                    $matriz1[8] = "r47_instit";

                    $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
                    $matriz2[2] = $gerffer_[$Igerffer]["r31_rubric"];
                    $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
                    $matriz2[6] = $anousu ;
                    $matriz2[7] = $mesusu;
                    $matriz2[8] = $DB_instit;

                    $condicaoaux  = " and r47_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
                    $condicaoaux .= " and r47_rubric = ".db_sqlformat($gerffer_[$Igerffer]["r31_rubric"] );
                    global $transacao_;
                    if (db_selectmax("transacao_", "select * from pontocom ".bb_condicaosubpes("r47_" ).$condicaoaux )) {
                      $matriz2[4] = ($transacao_[0]["r47_quant"] + $gerffer_[$Igerffer]["r31_quant"] );
                      $matriz2[5] = ($transacao_[0]["r47_valor"] + $gerffer_[$Igerffer]["r31_valor"] );
                      if(!$lCalculoFeriasEfetuado) {
                        db_update("pontocom",$matriz1, $matriz2 , bb_condicaosubpes("r47_" ).$condicaoaux );
                      }
                    } else {
                      $matriz2[4] = ( $gerffer_[$Igerffer]["r31_quant"] );
                      $matriz2[5] = ( $gerffer_[$Igerffer]["r31_valor"] );
                      LogCalculoFolha::write("Inserindo dados na Tabela de Ponto Complementar");
                      if(!$lCalculoFeriasEfetuado){
                        db_insert("pontocom",$matriz1, $matriz2 );
                      }
                    }
                  }
                }

              } else if ($cadferia[0][$r30_proc] == $subpes
                && substr("#".$gerffer_[$Igerffer]["r31_rubric"],1,1) != "R"
                && db_val($gerffer_[$Igerffer]["r31_rubric"]) < 2000 ) {

                // Repassa as rubricas as Rubricas de desconto de salario que estão no Ponto calculado das Férias para o Ponto de Salario ou complementar

                if ($opcao_geral == PONTO_SALARIO) {
                  $matriz1 = array();
                  $matriz2 = array();

                  $matriz1[1] = "r10_regist";
                  $matriz1[2] = "r10_rubric";
                  $matriz1[3] = "r10_lotac";
                  $matriz1[4] = "r10_quant";
                  $matriz1[5] = "r10_valor";
                  $matriz1[6] = "r10_datlim";
                  $matriz1[7] = "r10_anousu";
                  $matriz1[8] = "r10_mesusu";
                  $matriz1[9] = "r10_instit";
                  //echo "<BR> r31_rubric --> ".$gerffer_[$Igerffer]["r31_rubric"];
                  $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
                  $matriz2[2] = $gerffer_[$Igerffer]["r31_rubric"];
                  $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
                  $matriz2[4] = 0;
                  $matriz2[5] = $gerffer_[$Igerffer]["r31_valor"];
                  $matriz2[6] = str_pad(" ", 7 );
                  $matriz2[7] = $anousu;
                  $matriz2[8] = $mesusu;
                  $matriz2[9] = $DB_instit;

                  LogCalculoFolha::write("Inserindo dados na Tabela de Ponto de Salário");
                  if(!$lCalculoFeriasEfetuado){
                    $retornar = db_insert("pontofs",$matriz1, $matriz2 );
                  }
                } else {
                  //echo "<BR> passo aqui 3 -->".$gerffer_[$Igerffer]["r31_rubric"];
                  $matriz1 = array();
                  $matriz2 = array();

                  $matriz1[1] = "r47_regist";
                  $matriz1[2] = "r47_rubric";
                  $matriz1[3] = "r47_lotac";
                  $matriz1[4] = "r47_quant";
                  $matriz1[5] = "r47_valor";
                  $matriz1[6] = "r47_anousu";
                  $matriz1[7] = "r47_mesusu";
                  $matriz1[8] = "r47_instit";

                  $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
                  $matriz2[2] = $gerffer_[$Igerffer]["r31_rubric"];
                  $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
                  $matriz2[4] = 0;
                  $matriz2[5] = $gerffer_[$Igerffer]["r31_valor"];
                  $matriz2[6] = $anousu ;
                  $matriz2[7] = $mesusu;
                  $matriz2[8] = $DB_instit;

                  LogCalculoFolha::write("Inserindo dados na Tabela de Ponto complementar");
                  if(!$lCalculoFeriasEfetuado){
                    $retornar = db_insert("pontocom",$matriz1, $matriz2 );
                  }
                }
              }
            }
            }
          }
          LogCalculoFolha::write("Chamando grava_rubricas_ferias_especiais. Parametros: $r30_proc|$r30_peri|$r30_perf");
          grava_rubricas_ferias_especiais($r30_proc,$r30_peri,$r30_perf, $lCalculoFeriasEfetuado);
          //echo "<BR> sai grava_rubricas_ferias ";
        }
        //echo "<BR> entrou aqui verifica_ferias_100 --> ";
      }
    }
  }
}



function gravarRubricasEspeciaisFerias($r30_proc,$r30_peri,$r30_perf, $lCalculoFeriasEfetuado) {

  global $cadferia,$pessoal, $Ipessoal,$calcular_tipo_proc,$subpes,$gravar_valor_adiantamento_ferias_a_descontar;
  global $rub_especial_ferias_descontos,$perai_cadferia;
  global $rubrica_proporc,$rub_especial_ferias_adia,$F023,$F019,$rub_especial_ferias_13;
  global $rub_especial_ferias_13a,$rub_especial_ferias_abono,$cfpess, $opcao_geral,$rub_especial_ferias;
  global $anousu, $mesusu, $DB_instit,$valor_adiantamento_abono_a_descontar;

  global $F016, $F017, $F018, $F019,$F020, $F021, $F023;

  $perai_cadferia = $cadferia[0]["r30_perai"];
  $condicaoaux  = " and r30_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] ) ;
  $condicaoaux .= " and r30_perai = ".db_sqlformat($perai_cadferia );

  if (('t' ==  $cfpess[0]["r11_pagaab"] )) {
    // Paga abono ferias
    if (db_substr(db_dtos($cadferia[0][$r30_peri]),1,6) > db_strtran($subpes,"/","") && ('t' ==  $cfpess[0]["r11_recalc"] )) {
      if ($rub_especial_ferias_13a+$rub_especial_ferias_abono > 0) {
        $matriz1 = array();
        $matriz2 = array();
        $matriz1[1] = "r30_vliq1";
        $matriz2[1] = round($valor_adiantamento_abono_a_descontar+$rub_especial_ferias_13a+$rub_especial_ferias_abono,2);
        db_update("cadferia",$matriz1,$matriz2,bb_condicaosubpes("r30_" ).$condicaoaux );
      }
    }
  }
  if ($cadferia[0][$r30_proc] == $subpes &&  $calcular_tipo_proc ) {
    if ($rub_especial_ferias_adia > 0 ){
      $matriz4 = array();
      $matriz5 = array();
      $matriz4[1] = "r30_vfgt1";
      $matriz4[2] = "r30_descad";

      $matriz5[1] = round($gravar_valor_adiantamento_ferias_a_descontar+$rub_especial_ferias_adia,2);
      $matriz5[2] = round($rub_especial_ferias_descontos,2 );
      db_update("cadferia",$matriz4,$matriz5,bb_condicaosubpes("r30_" ).$condicaoaux );
    }
  }
  // r11_pagaab --> Indica se deixa pagar Abono de Ferias (Sim/Nao)

  // Rubrica de Ferias
  if ($opcao_geral == 1) {

    //echo "<BR> 2 grava_rubricas_ferias()  gravando r01_regist ---> ".$pessoal[$Ipessoal]["r01_regist"];
    //echo "<BR> 2 grava_rubricas_ferias()  gravando r01_lotac  ---> ".$pessoal[$Ipessoal]["r01_lotac"];
    //echo "<BR> 2 grava_rubricas_ferias()  gravando valor_ferias -> $rub_especial_ferias";

    if (( $rub_especial_ferias > 0 )) {
      $matriz1 = array();
      $matriz2 = array();

      $matriz1[1] = "r10_regist";
      $matriz1[2] = "r10_rubric";
      $matriz1[3] = "r10_lotac";
      $matriz1[4] = "r10_quant";
      $matriz1[5] = "r10_valor";
      $matriz1[6] = "r10_datlim";
      $matriz1[7] = "r10_anousu";
      $matriz1[8] = "r10_mesusu";
      $matriz1[9] = "r10_instit";

      //echo "<BR> r11_ferias --> ".$cfpess[0]["r11_ferias"];

      $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
      $matriz2[2] = $cfpess[0]["r11_ferias"];
      $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
      $matriz2[4] = $F019;
      // F019 - Numero de dias a pagar no mes
      $matriz2[5] = $rub_especial_ferias;
      $matriz2[6] = str_pad(" ", 7 );
      $matriz2[7] = $anousu;
      $matriz2[8] = $mesusu;
      $matriz2[9] = $DB_instit;
      // db_delete("pontofs",bb_condicaosubpes("r11_").$condicaoaux );
      LogCalculoFolha::write("Inserindo dados na Tabela de Ponto salário");

      $sWhere     = bb_condicaosubpes("r10_")." and r10_regist = {$matriz2[1]} and r10_rubric = '{$matriz2[2]}'";
      if(!$lCalculoFeriasEfetuado) {
        db_delete("pontofs", $sWhere);
        db_insert("pontofs",$matriz1, $matriz2 );
      }
    }
    if ($cadferia[0][$r30_proc] == $subpes &&  $calcular_tipo_proc) {
      if ( $rub_especial_ferias_adia > 0) {

        // Se o valor a receber como provento de adiantamento é menor que o valor dos descontos de adiantamento, não tem adiantamento

        $matriz1 = array();
        $matriz2 = array();

        $matriz1[1] = "r10_regist";
        $matriz1[2] = "r10_rubric";
        $matriz1[3] = "r10_lotac";
        $matriz1[4] = "r10_quant";
        $matriz1[5] = "r10_valor";
        $matriz1[6] = "r10_datlim";
        $matriz1[7] = "r10_anousu";
        $matriz1[8] = "r10_mesusu";
        $matriz1[9] = "r10_instit";
        //echo "<BR> r11_feradi -->".$cfpess[0]["r11_feradi"];
        $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
        $matriz2[2] = $cfpess[0]["r11_feradi"];
        // adiantamento de ferias
        $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
        $matriz2[4] = $F023;
        $matriz2[5] = $rub_especial_ferias_adia;
        $matriz2[6] = str_pad(" ", 7 );
        $matriz2[7] = $anousu ;
        $matriz2[8] = $mesusu;
        $matriz2[9] = $DB_instit;
        LogCalculoFolha::write("Inserindo dados na Tabela de Ponto salário");
        $sWhere     = bb_condicaosubpes("r10_")." and r10_regist = {$matriz2[1]} and r10_rubric = '{$matriz2[2]}'";

        if(!$lCalculoFeriasEfetuado) {
          db_delete("pontofs", $sWhere);
          db_insert("pontofs",$matriz1, $matriz2 );
        }
      }
    }

    //echo "<BR> 1 grava_rubricas_ferias()  gravando rubrica ---> ".$cfpess[0]["r11_fer13"]." valor ---> $rub_especial_ferias_13";

    if (!db_empty($rub_especial_ferias_13) && $rub_especial_ferias_13 > 0) {
      $matriz1 = array();
      $matriz2 = array();

      $matriz1[1] = "r10_regist";
      $matriz1[2] = "r10_rubric";
      $matriz1[3] = "r10_lotac";
      $matriz1[4] = "r10_quant";
      $matriz1[5] = "r10_valor";
      $matriz1[6] = "r10_datlim";
      $matriz1[7] = "r10_anousu";
      $matriz1[8] = "r10_mesusu";
      $matriz1[9] = "r10_instit";
      //echo "<BR> r11_fer13 --> ".$cfpess[0]["r11_fer13"];
      $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
      $matriz2[2] = $cfpess[0]["r11_fer13"];
      $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
      $matriz2[4] = 0;
      $matriz2[5] = $rub_especial_ferias_13;
      $matriz2[6] = str_pad(" ",7);
      $matriz2[7] = $anousu ;
      $matriz2[8] = $mesusu;
      $matriz2[9] = $DB_instit;
      LogCalculoFolha::write("Inserindo dados na Tabela de Ponto salário");
      $sWhere     = bb_condicaosubpes("r10_")." and r10_regist = {$matriz2[1]} and r10_rubric = '{$matriz2[2]}'";
      
      if(!$lCalculoFeriasEfetuado) {
        db_delete("pontofs", $sWhere);
        db_insert("pontofs",$matriz1, $matriz2 );
      }
      //echo "<BR> 2 grava_rubricas_ferias()  gravando rubrica    ---> ".$cfpess[0]["r11_fer13"]." valor ---> $rub_especial_ferias_13";
      //echo "<BR> 2 grava_rubricas_ferias()  gravando r01_regist ---> ".$pessoal[$Ipessoal]["r01_regist"];
      //echo "<BR> 2 grava_rubricas_ferias()  gravando r01_lotac  ---> ".$pessoal[$Ipessoal]["r01_lotac"];

    }

    if ('t' ==  $cfpess[0]["r11_pagaab"] ) {
      // Paga abono ferias
      if (db_substr(db_dtos($cadferia[0][$r30_peri]),1,6) > db_strtran($subpes,"/","") && ('t' ==  $cfpess[0]["r11_recalc"] )) {
        // r11_recalc --> Recalcula 1/3 ferias mes gozo
        if ($rub_especial_ferias_13a+$rub_especial_ferias_abono > 0 && $calcular_tipo_proc) {
          $matriz1 = array();
          $matriz2 = array();

          $matriz1[1] = "r10_regist";
          $matriz1[2] = "r10_rubric";
          $matriz1[3] = "r10_lotac";
          $matriz1[4] = "r10_quant";
          $matriz1[5] = "r10_valor";
          $matriz1[6] = "r10_datlim";
          $matriz1[7] = "r10_anousu";
          $matriz1[8] = "r10_mesusu";
          $matriz1[9] = "r10_instit";
          //echo "<BR> r11_fadiab --> ".$cfpess[0]["r11_fadiab"];
          $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
          $matriz2[2] = $cfpess[0]["r11_fadiab"];
          // Rubrica onde será lançado o adiantamento s/abono de férias
          $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
          $matriz2[4] = 0;
          $matriz2[5] = $rub_especial_ferias_13a+$rub_especial_ferias_abono;
          $matriz2[6] = str_pad(" ", 7 );
          $matriz2[7] = $anousu;
          $matriz2[8] = $mesusu;
          $matriz2[9] = $DB_instit;
          LogCalculoFolha::write("Inserindo dados na Tabela de Ponto salário");

          $sWhere     = bb_condicaosubpes("r10_")." and r10_regist = {$matriz2[1]} and r10_rubric = '{$matriz2[2]}'";
          if(!$lCalculoFeriasEfetuado) {
            db_delete("pontofs", $sWhere);
            db_insert("pontofs",$matriz1, $matriz2 );
          }
        }
      } else {
        $matriz1 = array();
        $matriz2 = array();
        $matriz1[1] = "r10_regist";
        $matriz1[2] = "r10_rubric";
        $matriz1[3] = "r10_lotac";
        $matriz1[4] = "r10_quant";
        $matriz1[5] = "r10_valor";
        $matriz1[6] = "r10_datlim";
        $matriz1[7] = "r10_anousu";
        $matriz1[8] = "r10_mesusu";
        $matriz1[9] = "r10_instit";
        $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
        $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
        $matriz2[4] = 0;
        $matriz2[6] = str_pad(" ", 7 );
        $matriz2[7] = $anousu ;
        $matriz2[8] = $mesusu;
        $matriz2[9] = $DB_instit;

        if ($rub_especial_ferias_13a > 0 ) {

          $matriz2[2] = $cfpess[0]["r11_fer13a"];
          // 1/3 s/ abono de férias:
          $matriz2[5] = $rub_especial_ferias_13a;
          LogCalculoFolha::write("Inserindo dados na Tabela de Ponto salário");

          $sWhere     = bb_condicaosubpes("r10_")." and r10_regist = {$matriz2[1]} and r10_rubric = '{$matriz2[2]}'";
          if(!$lCalculoFeriasEfetuado) {
            db_delete("pontofs", $sWhere);
            db_insert("pontofs",$matriz1, $matriz2 );
          }
        }

        if ($rub_especial_ferias_abono > 0 ) {

          $matriz2[2] = $cfpess[0]["r11_ferabo"];
          // Abono de ferias:
          $matriz2[5] = $rub_especial_ferias_abono;
          LogCalculoFolha::write("Inserindo dados na Tabela de Ponto salário");

          $sWhere     = bb_condicaosubpes("r10_")." and r10_regist = {$matriz2[1]} and r10_rubric = '{$matriz2[2]}'";
          if(!$lCalculoFeriasEfetuado){
            db_delete("pontofs", $sWhere);
            db_insert("pontofs",$matriz1, $matriz2 );
          }
        }
      }
    }
  } else {
    $matriz1 = array();
    $matriz2 = array();

    $matriz1[1] = "r47_regist";
    $matriz1[2] = "r47_rubric";
    $matriz1[3] = "r47_lotac";
    $matriz1[4] = "r47_quant";
    $matriz1[5] = "r47_valor";
    $matriz1[6] = "r47_anousu";
    $matriz1[7] = "r47_mesusu";
    $matriz1[8] = "r47_instit";

    $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
    $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
    $matriz2[6] = $anousu ;
    $matriz2[7] = $mesusu;
    $matriz2[8] = $DB_instit;
    //echo "<BR> valor_ferias --> $rub_especial_ferias";
    if ($rub_especial_ferias > 0 ) {
      $matriz2[2] = $cfpess[0]["r11_ferias"];
      $matriz2[4] = $F019;
      // F019 - Numero de dias a pagar no mes
      $matriz2[5] = $rub_especial_ferias;

      LogCalculoFolha::write("Inserindo dados na Tabela de Ponto complementar");
      $sWhere     = bb_condicaosubpes("r47_")." and r47_regist = {$matriz2[1]} and r47_rubric = '{$matriz2[2]}'";
      if(!$lCalculoFeriasEfetuado){
        db_delete("pontocom", $sWhere);
        db_insert("pontocom", $matriz1, $matriz2 );
      }
    }
    if ($cadferia[0][$r30_proc] == $subpes &&  $calcular_tipo_proc ) {
      if ($rub_especial_ferias_adia > 0  ) {
        $matriz2[2] = $cfpess[0]["r11_feradi"];
        $matriz2[4] = $F023;
        $matriz2[5] = $rub_especial_ferias_adia;
        //echo "<BR> 5 gravar_valor_adiantamento_ferias_a_descontar --> $gravar_valor_adiantamento_ferias_a_descontar --> ";
        //                    LogCalculoFolha::write("Inserindo dados na Tabela de Ponto complementar");
        LogCalculoFolha::write("Inserindo dados na Tabela de Ponto complementar");
        $sWhere     = bb_condicaosubpes("r47_")." and r47_regist = {$matriz2[1]} and r47_rubric = '{$matriz2[2]}'";
        if(!$lCalculoFeriasEfetuado) {
          db_delete("pontocom", $sWhere);
          db_insert("pontocom",$matriz1, $matriz2 );
        }
      }
    }

    if ($rub_especial_ferias_13 > 0) {
      $matriz2[2] = $cfpess[0]["r11_fer13"];
      $matriz2[4] = 0;
      $matriz2[5] = $rub_especial_ferias_13;
      LogCalculoFolha::write("Inserindo dados na Tabela de Ponto complementar");
      $sWhere     = bb_condicaosubpes("r47_")." and r47_regist = {$matriz2[1]} and r47_rubric = '{$matriz2[2]}'";
      if(!$lCalculoFeriasEfetuado) { 
        db_delete("pontocom", $sWhere);
        db_insert("pontocom",$matriz1, $matriz2 );
      }
    }

    if (('t' ==  $cfpess[0]["r11_pagaab"] )) {
      // Paga abono ferias
      $matriz2[4] = 0;
      if (db_substr(db_dtos($cadferia[0][$r30_peri]),1,6) > db_strtran($subpes,"/","")) {
        if ($rub_especial_ferias_13a+$rub_especial_ferias_abono > 0 &&  $calcular_tipo_proc) {
          $matriz2[2] = $cfpess[0]["r11_fadiab"];
          // Rubrica onde será lançado o adiantamento s/abono de férias
          $matriz2[5] = $rub_especial_ferias_13a+$rub_especial_ferias_abono;
          LogCalculoFolha::write("Inserindo dados na Tabela de Ponto complementar");
          $sWhere     = bb_condicaosubpes("r47_")." and r47_regist = {$matriz2[1]} and r47_rubric = '{$matriz2[2]}'";
          if(!$lCalculoFeriasEfetuado) {
            db_delete("pontocom", $sWhere);
            db_insert("pontocom",$matriz1, $matriz2 );
          }
        }
      } else {
        if ($rub_especial_ferias_13a > 0) {
          $matriz2[2] = $cfpess[0]["r11_fer13a"];
          $matriz2[5] = $rub_especial_ferias_13a;
          LogCalculoFolha::write("Inserindo dados na Tabela de Ponto complementar");
          $sWhere     = bb_condicaosubpes("r47_")." and r47_regist = {$matriz2[1]} and r47_rubric = '{$matriz2[2]}'";
          if(!$lCalculoFeriasEfetuado) {
            db_delete("pontocom", $sWhere);
            db_insert("pontocom",$matriz1, $matriz2 );
          }
        }
        if ($rub_especial_ferias_abono > 0 ) {
          $matriz2[2] = $cfpess[0]["r11_ferabo"];
          $matriz2[5] = $rub_especial_ferias_abono;
          LogCalculoFolha::write("Inserindo dados na Tabela de Ponto complementar");
          $sWhere     = bb_condicaosubpes("r47_")." and r47_regist = {$matriz2[1]} and r47_rubric = '{$matriz2[2]}'";
          if(!$lCalculoFeriasEfetuado) {
            db_delete("pontocom", $sWhere);
            db_insert("pontocom",$matriz1, $matriz2 );
          }

        }

      }
    }
  }

  /**
   * Se o parâmetro $DB_COMPLEMENTAR estiver ativo, salva o historico do ponto 
   * dos dados cadastrados nas tabelas pontocom/pontosal
   */
  global $oFolhaAtual;

  if ($oFolhaAtual && DBPessoal::verificarUtilizacaoEstruturaSuplementar() && ($oFolhaAtual->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_SALARIO || $oFolhaAtual->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR)) {

    $aServidoresHistorico = array();

    for ($iServidorHistorico = 0; $iServidorHistorico < count($pessoal); $iServidorHistorico++) {

      $oServidor = ServidorRepository::getInstanciaByCodigo( $pessoal[$iServidorHistorico]['r01_regist'], 
        $pessoal[$iServidorHistorico]['r01_anousu'], 
        $pessoal[$iServidorHistorico]['r01_mesusu']
      );
      $aServidoresHistorico[] = $oServidor;
    }

    $oFolhaAtual->salvarHistoricoPonto($aServidoresHistorico);
  }
}
