<?php
/**
 * Refactor das Funções de Ajuste de Previdência.
 *
 * @package Pessoal
 * @subpackage Cálculo Financeiro
 * @version $id$
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 */
class AjustePrevidencia {

  const RUBRICA_BASE_SALARIO      = "R985";
  const RUBRICA_BASE_FERIAS       = "R987";
  const RUBRICA_SOMA_BASE_SALARIO = "R992";

  public static $aValorTeto    = array();
  private static $aValoresBase = array();
  /**
   * Pré-Processa as informações,
   *
   * @static
   * @access public
   * @return void
   */
  static function gravarModificacoes(){

    global $pessoal, $Ipessoal,$subpes,$chamada_geral_arquivo,$opcao_geral,$numcgm;
    global $transacao;
    global $anousu, $mesusu, $DB_instit;


    LogCalculoFolha::write("Tabela de Previdencia:  " . $pessoal[$Ipessoal]["r01_tbprev"]);

    if( $pessoal[$Ipessoal]["r01_tbprev"] > 0){
      //echo "<BR><BR> entrou na funcao grava_ajuste_previdencia() : matricula --> ".$pessoal[$Ipessoal]["r01_regist"]." numcgm --> ".$pessoal[$Ipessoal]["r01_numcgm"]."  <BR><BR>";

      $prev_base = 0;
      $prev_desc = 0;
      $prev_perc = 0;

      if( $opcao_geral == 1 ){
        $tipo_arquivo = "S";
        $sigla1 = "r14_";
      }else if( $opcao_geral == 8 ){
        $tipo_arquivo = "C";
        $sigla1 = "r48_";
      }else if( $opcao_geral == 5 ){
        $tipo_arquivo = "3";
        $sigla1 = "r35_";
      }else if( $opcao_geral == 4){
        $tipo_arquivo = "R";
        $sigla1 = "r20_";
      }else if( $opcao_geral == 3){
        $tipo_arquivo = "F";
        $sigla1 = "r31_";
      }

      $siglag = $sigla1 ;
      $numcgm = $pessoal[$Ipessoal]["r01_numcgm"];

      global $pes_prev;

      $condicaoaux  = " and rh01_numcgm = ".db_sqlformat( $numcgm );
      $condicaoaux .= " and ( rh05_recis is null ";
      $condicaoaux .= "  or (extract(year from rh05_recis) >= ".db_sqlformat(substr("#".$subpes,1,4));
      $condicaoaux .= " and  extract(month from rh05_recis) >= ".db_sqlformat(substr("#".$subpes,6,2))."))";

      db_selectmax("pes_prev", "select rh02_regist as r01_regist from rhpessoalmov
        inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
        inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
        left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
        ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      //echo "<BR> qtda de registro pessoal_ ".count($pessoal_);

      LogCalculoFolha::write("Quantidade de Matriculas encontradas ".  count($pes_prev) );

      if(count($pes_prev) > 1){

        $matrizr  = array();
        $matrizb  = array();
        $matrizd  = array();
        $matrizp  = array();
        $matrizb[1] = 0;
        $matrizb[2] = 0;
        $matrizb[3] = 0;

        $matrizd[1] = 0;
        $matrizd[2] = 0;
        $matrizd[3] = 0;


        $matrizp[1] = 0;
        $matrizp[2] = 0;
        $matrizp[3] = 0;


        $matrizr[1] = "";
        $matrizr[2] = "";
        $matrizr[3] = "";



        $xy = 1;
        // R985 BASE DE PREVIDENCIA
        // R986 BASE PREVIDENCIA (13O SAL)
        // R987 BASE PREVIDENCIA S/FERIAS

        $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
        $condicaoaux .= " and ".$siglag."rubric = 'R992'";
        db_selectmax( "transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes( $siglag ).$condicaoaux );
        $R992_base_F = $transacao[0][$siglag."valor"];


        $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
        $condicaoaux .= " and ".$siglag."rubric in ('R985','R986','R987')";
        LogCalculoFolha::write("Percorrendo eventos financeiros com Rubricas = ('R985','R986','R987')");
        db_selectmax( "transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes( $siglag ).$condicaoaux );
        for($Itransacao=0;$Itransacao< count($transacao);$Itransacao++){
          //echo "<BR><BR> 3 entrou na funcao grava_ajuste_previdencia() : matricula --> ".$pessoal[$Ipessoal]["r01_numcgm"]." <BR><BR>";
          $matrizr[$xy] = $transacao[$Itransacao][$siglag."rubric"];
          $matrizb[$xy] = $transacao[$Itransacao][$siglag."valor"];

          LogCalculoFolha::write("Rubrica: ".$transacao[$Itransacao][$siglag."rubric"]." Valor:". $transacao[$Itransacao][$siglag."valor"]);
          if( $opcao_geral == 1 && $chamada_geral_arquivo == "gerfsal"){
            $condicaoaux  = " and r48_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and r48_rubric = 'R985'";
            if(db_selectmax( "transacao", "select * from gerfcom ".bb_condicaosubpes("r48_").$condicaoaux )){
              $matrizb[$xy] += $transacao[$Itransacao]["r48_valor"];
            }
          }
          $xy += 1;
        }
        $xy = 1;

        // R901 % Inss S/SALARIO
        // R902 % Inss S/13§ SALARIO
        // R903 % Inss S/FERIAS
        // R904 % Faps S/ SALÁRIO
        // R905 % Faps S/ 13o SALÁRIO
        // R906 % Faps S/ FÉRIAS
        // R907 % Inss Consel S/SALARIO
        // R908 % Inss Consel S/13§ SALARIO
        // R909 % Inss Consel S/FERIAS

        // r01_tbprev pode assumir os sequintes valores :

        //  1 => INSS        --> codigo no inssirf --> 3
        //  2 => Previdência --> codigo no inssirf --> 4
        //  3 => Previdência --> codigo no inssirf --> 5
        //  4 => Previdência --> codigo no inssirf --> 6

        //echo "<BR>  r01_tbprev -->".$pessoal[$Ipessoal]["r01_tbprev"];
        $rubrica_base = "R9".db_str(( (3*$pessoal[$Ipessoal]["r01_tbprev"])-2),2,0,"0");
        //echo "<BR><BR> 3.1 rubrica base --> $rubrica_base";

        // Vai pesquisar entre as seguintes faixas

        // Se R901 de R901 ate R903
        // Se R904 de R904 ate R906
        // Se R907 de R907 ate R909
        // Se R910 de R910 ate R912

        $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
        $condicaoaux .= " and ".$siglag."rubric between ".db_sqlformat( $rubrica_base ) ;
        $condicaoaux .= " and ".db_sqlformat( "R".db_str(db_val( substr("#". $rubrica_base, 2, 3 ) ) + 2,3) );
        //echo "<BR><BR> 3.1 condicaoaux  --> $condicaoaux";
        db_selectmax( "transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes( $siglag ).$condicaoaux );
        $xy = ( db_val( substr("#". $transacao[0][$siglag."rubric"], 2, 3 ) ) - db_val( substr("#". $rubrica_base, 2, 3 ) ) ) + 1;
        //echo "<BR><BR> 3.1 x3 --> $xy";
        for($Itransacao=0;$Itransacao<count($transacao);$Itransacao++){
          if( $matrizr[$xy] != "" ){
            $matrizd[$xy] = $transacao[$Itransacao][$siglag."valor"];
            $matrizp[$xy] = $transacao[$Itransacao][$siglag."quant"];
            //echo "<BR><BR> 3.1.1 rubrica $xy --> ".$matrizd[$xy]."  valor --> ".$matrizp[$xy];
            $xy += 1;
          }
        }
        //echo "<BR><BR> 3.2 quant rubricas --> ".count($matrizr);
        for($nosx=1;$nosx <4;$nosx++){
          //echo "<BR><BR> 3.2 rubrica $nosx --> ".$matrizr[$nosx]."  valor --> ".$matrizb[$nosx];
          if( $matrizr[$nosx] != ""){
            //echo "<BR><BR> 3.3 rubrica $nosx --> ".$matrizr[$nosx]."  r60_base --> ".$matrizb[$nosx]." r60_dprev $nosx --> ".$matrizd[$nosx]."  tipo_arquivo --> $tipo_arquivo";

            $sRubricaTransacao = $matrizr[$nosx];


            if ( $opcao_geral != PONTO_RESCISAO ) {

              // R985 BASE DE PREVIDENCIA
              // R986 BASE PREVIDENCIA (13O SAL)
              // R987 BASE PREVIDENCIA S/FERIAS
              if ( $sRubricaTransacao == "R985" && $opcao_geral != PONTO_SALARIO ) {
                LogCalculoFolha::write("Nao calcula R985 fora do Ponto de Salário");
                continue; // R981 BASE I.R.F. BASE
              } elseif ( $sRubricaTransacao == "R986" && $opcao_geral != PONTO_13_SALARIO ) {
                LogCalculoFolha::write("Nao calcula R986 fora do Ponto de Salário");
                continue; // R982 BASE IRF 13O SAL (BRUTA) BASE
              } elseif ( $sRubricaTransacao == "R987" && $opcao_geral != PONTO_FERIAS ) {
                LogCalculoFolha::write("Nao calcula R987 fora do Ponto de Salário");
                continue; // R983 BASE IRF FERIAS BASE
              }
            }

            $matriz1 = array();
            $matriz2 = array();

            $matriz1[1] = "r60_numcgm";
            $matriz1[2] = "r60_regist";
            $matriz1[3] = "r60_tbprev";
            $matriz1[4] = "r60_base";
            $matriz1[5] = "r60_dprev";
            $matriz1[6] = "r60_novod";
            $matriz1[7] = "r60_rubric";
            $matriz1[8] = "r60_folha";
            $matriz1[9] = "r60_altera";
            $matriz1[10] = "r60_anousu";
            $matriz1[11] = "r60_mesusu";
            $matriz1[12] = "r60_ajuste";
            $matriz1[13] = "r60_basef";

            $matriz2[1] = $pessoal[$Ipessoal]["r01_numcgm"];
            $matriz2[2] = $pessoal[$Ipessoal]["r01_regist"];
            $matriz2[3] = $pessoal[$Ipessoal]["r01_tbprev"];
            $matriz2[4] = round($matrizb[$nosx],2);
            $matriz2[5] = round($matrizd[$nosx],2);
            $matriz2[6] = 0;
            $matriz2[7] = $matrizr[$nosx];
            $matriz2[8] = $tipo_arquivo;
            $matriz2[9] = 't';
            $matriz2[10] = $anousu ;
            $matriz2[11] = $mesusu ;
            $matriz2[12] = 't';
            $matriz2[13] = round($R992_base_F,2);

            $condicaoaux  = " and r60_numcgm = ".db_sqlformat( $pessoal[$Ipessoal]["r01_numcgm"] );
            $condicaoaux .= " and r60_tbprev = ".db_sqlformat( $pessoal[$Ipessoal]["r01_tbprev"] );
            $condicaoaux .= " and r60_rubric = ".db_sqlformat( $matrizr[$nosx] ) ;
            $condicaoaux .= " and r60_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and upper(r60_folha)  = ".db_sqlformat( $tipo_arquivo ) ;


            global $transacao; 
           

            if( db_selectmax( "transacao", "select * from previden ".bb_condicaosubpes( "r60_" ).$condicaoaux )){
              LogCalculoFolha::write("Alterando os valores da tabela previdencia.".bb_condicaosubpes("r60_").$condicaoaux."\n\n".print_r(array_combine($matriz1, $matriz2),true));
              db_update( "previden", $matriz1, $matriz2, bb_condicaosubpes( "r60_" ).$condicaoaux );
            }else{
              LogCalculoFolha::write("Inserindo os valores da tabela previdencia.".print_r(array_combine($matriz1, $matriz2),true));
              db_insert( "previden", $matriz1, $matriz2 );
            }

          }
        }
      }
    }
  }


  /**
   * Executa o recalculo do valor do imposto usando a base de todas as matriculas do servidor, dados
   * estes, que foram criados a partir self::gravarModificacoes ou self::gravarDadosCalculados
   *
   * @param String $arquivo
   * @param String $rubrica_base
   * @param String $sequencia
   * @param String $sigla_ajuste
   * @static
   * @access public
   * @return void
   */
  public static function ajustar($arquivo, $rubrica_base, $sequencia, $sigla_ajuste) {

    global $tipo_arquivo;
    global $previden,$inssirf,$Iinssirf,$previden_,$campos_pessoal,$opcao_geral,$db21_codcli,$subpes,$perc_inss,$opcao_tipo;
    global $$arquivo,$cfpess, $pessoal, $db_debug;
    global $anousu,  $mesusu, $DB_instit, $F023,$F019;

    $aMatriculas = array();

    if( $opcao_geral == 1 ){
      $tipo_arquivo = "S";
    }else if( $opcao_geral == 8 ){
      $tipo_arquivo = "C";
    }else if( $opcao_geral == 5 ){
      $tipo_arquivo = "3";
    }else if( $opcao_geral == 4){

      if ( $opcao_tipo == TIPO_CALCULO_PARCIAL) {

        foreach ($pessoal as $aPessoal) {
          $aMatriculas[] = $aPessoal['r01_regist'];
        }
      }

      $tipo_arquivo = "R";
    }else if( $opcao_geral == 3){
      $tipo_arquivo = "F";
    }
    self::ajustarValorInativosQueNaoAlcancaramTeto($rubrica_base, $tipo_arquivo);

    if ( $arquivo == "gerfres" ) {

      switch ($rubrica_base) {

      case 'R985':
        $aRubricaDesconto[1] = 'R901';
        $aRubricaDesconto[2] = 'R904';
        $aRubricaDesconto[3] = 'R907';
        $aRubricaDesconto[4] = 'R910';
        break;

      case 'R986':
        $aRubricaDesconto[1] = 'R902';
        $aRubricaDesconto[2] = 'R905';
        $aRubricaDesconto[3] = 'R908';
        $aRubricaDesconto[4] = 'R911';
        break;

      case 'R987':
        $aRubricaDesconto[1] = 'R903';
        $aRubricaDesconto[2] = 'R906';
        $aRubricaDesconto[3] = 'R909';
        $aRubricaDesconto[4] = 'R912';
        break;
      }

    }

    $matriz1 = array();
    $matriz2 = array();
    $matriz1[1] = "r60_ajuste";
    $matriz2[1] = 'f';

    $condicaoAjustePreviden = " inner join rhpessoal on r60_regist = rh01_regist and rh01_instit = " . $DB_instit ;

    if ( count($aMatriculas) ) {
      $condicaoAjustePreviden .= " and rh01_regist in (" . implode(',', $aMatriculas) . ")";
    }

    if (db_selectmax("previden_", "select * from previden " .$condicaoAjustePreviden . " " . bb_condicaosubpes("r60_") ) ) {

      // Todos que foram alterados vao sofrer ajuste
      $condicaoaux  = " and r60_altera = 't' and r60_rubric = ".db_sqlformat($rubrica_base );

      if($arquivo == 'gerfsal' ){
        $condicaoaux  .= " and r60_folha = 'S'";
      }elseif($arquivo == 'gerfcom' ){
        $condicaoaux  .= " and r60_folha = 'C'";
      }

      db_selectmax("previden_", "select * from previden " .$condicaoAjustePreviden . " " . bb_condicaosubpes("r60_") . $condicaoaux );

      for ($Ipreviden=0; $Ipreviden<count($previden_); $Ipreviden++) {

        $numcgm = $previden_[$Ipreviden]["r60_numcgm"];
        $matriz2[1] = 't' ;
        $condicaoaux  = " and r60_numcgm = ".db_sqlformat($numcgm );
        $condicaoaux .= " and r60_rubric = ".db_sqlformat($rubrica_base );

        if ($arquivo == "gerfcom") {
          $condicaoaux .= " and upper(r60_folha) in ('C','E') ";
        }
        db_update("previden", $matriz1, $matriz2, bb_condicaosubpes("r60_").$condicaoaux );
      }
      $condicaoaux  = " and r60_ajuste = 't' and r60_rubric = ".db_sqlformat($rubrica_base );
      if($arquivo == 'gerfsal' ){
        $condicaoaux  .= " and r60_folha = 'S'";
      }elseif($arquivo == 'gerfcom' ){
        $condicaoaux  .= " and r60_folha = 'C'";
      }
      $condicaoaux  .= " order by r60_numcgm, r60_regist";
      global $previdencia_;
      db_selectmax("previdencia_", "select * from previden " .$condicaoAjustePreviden . " " . bb_condicaosubpes("r60_") . $condicaoaux );
      for ($Ipreviden=0; $Ipreviden<count($previdencia_); $Ipreviden++) {
        $numcgm         = $previdencia_[$Ipreviden]["r60_numcgm"];
        $tbprev         = $previdencia_[$Ipreviden]["r60_tbprev"];
        $registro       = $previdencia_[$Ipreviden]["r60_regist"];
        $soma_base_teto = 0;

        LogCalculoFolha::write("CGM ...........................: {$numcgm}        ");
        LogCalculoFolha::write("Tabela de Previdencia..........: {$tbprev}        ");
        LogCalculoFolha::write("Matrícula......................: {$registro}      ");
        LogCalculoFolha::write("Valor do Teto..................: {$soma_base_teto}");

        if($rubrica_base == 'R985' && $arquivo == 'gerfsal' ){

          $condicaoaux  = " and r60_rubric = 'R987'";
          $condicaoaux .= " and r60_numcgm = ".db_sqlformat($numcgm );
          global $transacao1;

          if( db_selectmax("transacao1", "select sum(r60_basef) as soma_basef from previden ".bb_condicaosubpes("r60_" ).$condicaoaux )){

            $soma_base_teto = isset($transacao1[0]["soma_basef"]) ? $transacao1[0]["soma_basef"] : 0;
            LogCalculoFolha::write("Soma da Base do Teto.....: $soma_base_teto");
          }
        }


        $soma_base   = 0;
        $soma_base_F = 0;
        $soma_base_D = 0;

        global $pessoal_2,$pessoal_3;
        $condicaoaux = " and rh01_numcgm = ".db_sqlformat($previdencia_[$Ipreviden]["r60_numcgm"]);


        db_selectmax("pessoal_3", "select sum(RH51_B13FO) as soma_b13fo,
          sum(RH51_DESCFO) as soma_descfo,
          sum(RH51_BASEFO) as soma_basefo,
          sum(RH51_D13FO) as  soma_d13fo
          from rhpessoalmov
          inner join rhpessoal   on rh01_regist = rhpessoalmov.rh02_regist
          left join rhinssoutros on rh51_seqpes = rhpessoalmov.rh02_seqpes ".bb_condicaosubpes("rh02_" ).$condicaoaux );


        $condicaoaux = " and rh02_regist = ".db_sqlformat($previdencia_[$Ipreviden]["r60_regist"] );
        db_selectmax("pessoal_2", "select rh02_tpcont as r01_tpcont,
          trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,
          RH30_VINCULO as r01_tpvinc
          from rhpessoalmov
          left join rhinssoutros on rh51_seqpes = rhpessoalmov.rh02_seqpes
          left join rhregime     on rh30_codreg = rhpessoalmov.rh02_codreg ".bb_condicaosubpes("rh02_" ).$condicaoaux );

        for ($Ipreviden2=$Ipreviden; $Ipreviden2 < count($previdencia_); $Ipreviden2++) {
          //echo "<BR> rubrica 35.2104 -->$numcgm  rubrica baser --> $rubrica_base"; // reis
          if ($previdencia_[$Ipreviden2]["r60_numcgm"] == $numcgm && $previdencia_[$Ipreviden2]["r60_tbprev"] == $tbprev ) {
            //echo "<BR> registro --> ".$previdencia_[$Ipreviden2]["r60_regist"] . " base --> ".$previdencia_[$Ipreviden2]["r60_base"];
            $soma_base   += $previdencia_[$Ipreviden2]["r60_base"];
            $soma_base_F += $previdencia_[$Ipreviden2]["r60_basef"];

            $Ipreviden = $Ipreviden2;
          } else {
            $Ipreviden = $Ipreviden2 - 1;
            break;
          }
        }

        $soma_base_D    = $soma_base - $soma_base_F;
        $mat_r60_numcgm = array();
        $mat_r60_tbprev = array();
        $mat_r60_rubric = array();
        $mat_r60_regist = array();
        $mat_r60_folha  = array();
        $mat_r60_novods = array();
        $mat_r60_novodf = array();
        $mat_r60_dif    = array();
        $mat_r60_novop  = array();
        $nro            = 0;

        $soma_base1     = 0;
        $desc_prev_ext  = 0;
        global $pessoaltipoprevidencia_;
        db_selectmax("pessoaltipoprevidencia_", "select rh02_tbprev
          from rhpessoalmov
          ".bb_condicaosubpes("rh02_" )." and rh02_regist = {$registro}");
        if ($pessoaltipoprevidencia_[0]["rh02_tbprev"] == $cfpess[0]["r11_tbprev"]) {

          if ($rubrica_base == "R986" ) {
            $soma_base1 = $pessoal_3[0]["soma_b13fo"];
            $desc_prev_ext = $pessoal_3[0]["soma_d13fo"];
          }else{
            $soma_base1 = $pessoal_3[0]["soma_basefo"];
            $desc_prev_ext = $pessoal_3[0]["soma_descfo"];
            $soma_base_D = ($soma_base+$pessoal_3[0]["soma_basefo"]) - $soma_base_F ;
          }
        }
        $atingiu_o_teto_ext = false;
        if($desc_prev_ext > 0 ){
          $valor_desconto_ext = teto_tabprev($soma_base1, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
          if($valor_desconto_ext > 0 ){
            $atingiu_o_teto_ext = true;
          }
        }
        if($soma_base1 == 0){
          $soma_base1 = $soma_base;
        }else{
          $soma_base1 = $soma_base+$soma_base1;
        }

        ferias($registro);

        global $perc_inss;

        LogCalculoFolha::write();
        LogCalculoFolha::write('Inicialmente nunca atinge o teto($atingiu_o_teto_tbprev = false) ');

        $atingiu_o_teto_tbprev = false;

        if ( $arquivo == 'gerffer' ) {

          //echo "<BR> soma_base_F --> $soma_base_F";
          $perc_inss = 0;

          if($soma_base_F != 0 ){

            $valor_desc_total_F = teto_tabprev($soma_base_F, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);

            if($valor_desc_total_F <= 0 ){
              LogCalculoFolha::write("Chamando função que calcula o valor da previdencia");
              $valor_desc_total_F = calc_tabprev($soma_base_F, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
            }

            $valor_a_ratear_F = $valor_desc_total_F - $desc_prev_ext ;
            $perc_inss_F      = $perc_inss;
          }

          $perc_inss = 0;

          if($soma_base_D != 0 ){
            $valor_desc_total_D = teto_tabprev($soma_base_D, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
            if($valor_desc_total_D <= 0 ){

              LogCalculoFolha::write("Chamando função que calcula o valor da previdencia");
              $valor_desc_total_D = calc_tabprev($soma_base_D, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
            }
            $valor_a_ratear_D = $valor_desc_total_D ;
            $perc_inss_D      = $perc_inss;
          }
        } else {
          //Else não é férias
          LogCalculoFolha::write("Rubrica Base.....................: $rubrica_base");
          LogCalculoFolha::write("Base Base do Teto................: $soma_base_teto");
          LogCalculoFolha::write("Tabela...........................: $arquivo");

          if ( $rubrica_base == 'R985' && $arquivo == 'gerfsal' && isset($soma_base_teto) && $soma_base_teto > 0 ) {

            $valor_desconto_total = teto_tabprev($soma_base_teto, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
            LogCalculoFolha::write("q Valor do Desconto Total .........: $valor_desconto_total");

            if($valor_desconto_total > 0 ){
              $atingiu_o_teto_tbprev = true;
            }
          }

          LogCalculoFolha::write("Chamando função que calcula o valor da previdencia");
          LogCalculoFolha::write("Valor da Base........:" . $soma_base1 );
          LogCalculoFolha::write("Tabela de Previdencia:" . db_str($tbprev+2, 1) );
          LogCalculoFolha::write("Tipo Contrato........:" . $pessoal_2[0]["r01_tpcont"] );
          $valor_desconto_total = calc_tabprev($soma_base1, db_str($tbprev+2, 1), $pessoal_2[0]["r01_tpcont"]);
         
          LogCalculoFolha::write("Valor Desconto Total.: $valor_desconto_total");
          $valor_a_ratear       = $valor_desconto_total - $desc_prev_ext;
          LogCalculoFolha::write("Valor Desconto Total.: $valor_desconto_total");
          LogCalculoFolha::write("Valor a Ratear.......: $valor_a_ratear");
        }

        if( $atingiu_o_teto_ext == true ){

          $atingiu_o_teto_tbprev = true;
        }

        $condicaoaux  = " and r60_numcgm = ".db_sqlformat($numcgm );
        $condicaoaux .= " and r60_tbprev = ".db_sqlformat($tbprev );
        $condicaoaux .= " and r60_rubric = ".db_sqlformat($rubrica_base );
        $condicaoaux .= " and r60_altera = 't' ";
        $sql = "select previden.*,
          rh30_vinculo as r01_tpvinc
          from previden
          inner join rhpessoalmov on rh02_regist = r60_regist
          and rh02_anousu = ".substr("#".$subpes,1,4)."
          and rh02_mesusu = ".substr("#".$subpes,6,2)."
          left join rhregime     on rh30_codreg = rh02_codreg ".bb_condicaosubpes("r60_" ).$condicaoaux;
        if (db_selectmax("previden_", $sql )) {

          $rubrica_desconto = "R9".db_str((( 3 * $tbprev ) -2 ) -1 + $sequencia ,2,0,"0");

          if ($arquivo == 'gerfres') {
            $rubrica_desconto = $aRubricaDesconto[$tbprev];
          }


          $matriz1     = array();
          $matriz2     = array();
          $matriz1[1]  = $sigla_ajuste."valor";
          $matriz1[2]  = $sigla_ajuste."quant";
          $soma_desc_F = 0;
          $soma_desc_D = 0;

          for ($Ipreviden_=0; $Ipreviden_<count($previden_); $Ipreviden_++) {

            $mat_r60_regist[$nro] = $previden_[$Ipreviden_]["r60_regist"];
            $mat_r60_tpvinc[$nro] = $previden_[$Ipreviden_]["r01_tpvinc"];
            $mat_r60_numcgm[$nro] = $numcgm;

            if($arquivo == 'gerffer' ){

              $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($previden_[$Ipreviden_]["r60_regist"]);
              $condicaoaux .= " and ".$sigla_ajuste."rubric = ".db_sqlformat($rubrica_desconto );

              if ( $soma_base_F != 0 ){

                $novo_desc_F   = round($previden_[$Ipreviden_]["r60_basef"] / $soma_base_F  * $valor_a_ratear_F,2 ) ;
                $matriz2[1]    = $novo_desc_F;
                $matriz2[2]    = $perc_inss_F;
                $soma_desc_F  += $novo_desc_F;

                if ( round($soma_desc_F,2) > round($valor_a_ratear_F,2) ) {
                  $matriz2[1] = abs($novo_desc_F - (round($soma_desc_F - ($soma_desc_F-$valor_a_ratear_F),2)));
                }

                if($atingiu_o_teto_tbprev == true){
                  $matriz2[2] = 0;
                }

                $retornar = db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux." and r31_tpp = 'F'" );
                LogCalculoFolha::write('Update: '.bb_condicaosubpes($sigla_ajuste).$condicaoaux."\n\n".print_r(array_combine($matriz1, $matriz2),true));
              }

              if($soma_base_D != 0 ){

                $novo_desc_D   = round(($previden_[$Ipreviden_]["r60_base"] - $previden_[$Ipreviden_]["r60_basef"]) / $soma_base_D  * $valor_a_ratear_D,2 ) ;
                $matriz2[1]    = $novo_desc_D;
                $matriz2[2]    = $perc_inss_D;
                $retornar      = db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux." and r31_tpp = 'D'" );
                LogCalculoFolha::write('Update: '.bb_condicaosubpes($sigla_ajuste).$condicaoaux."\n\n".print_r(array_combine($matriz1, $matriz2),true));;
                $soma_desc_D += $novo_desc_D;
                if(round($soma_desc_D,2) > round($valor_a_ratear_D,2)){
                  $matriz2[1] = round($soma_desc_D - ($soma_desc_D-$valor_a_ratear_D),2);
                  $matriz2[1] = abs($novo_desc_D - (round($soma_desc_D - ($soma_desc_D-$valor_a_ratear_D),2)));
                }

              }

            } else{

              $novo_desconto = 0;

              if ( $valor_a_ratear > 0) {
                LogCalculoFolha::write('Novo Desconto...: ("$previden_[$Ipreviden_]["r60_base"] / $soma_base  * $valor_a_ratear)');
                LogCalculoFolha::write("Novo Desconto...: ({$previden_[$Ipreviden_]["r60_base"]} / {$soma_base}  * {$valor_a_ratear})");
                $novo_desconto = round($previden_[$Ipreviden_]["r60_base"] / $soma_base  * $valor_a_ratear,2 ) ;
                LogCalculoFolha::write("Novo Desconto...: ({$novo_desconto})");
              }

              $registrop          = $previden_[$Ipreviden_]["r60_regist"];
              $qual_folha         = strtoupper($previden_[$Ipreviden_]["r60_folha"]);
              $valor_desconto_fer = 0;
              $valor_desconto_com = 0;
              if ($opcao_geral != 3) {
                $sqlfer = " select sum(".$sigla_ajuste."valor) as valor_desconto_fer
                  from ".$arquivo."
                  inner join rhpessoal on rh01_regist = ".$sigla_ajuste."regist
                  ".bb_condicaosubpes($sigla_ajuste)."
                  and ".$sigla_ajuste."rubric in ('R903','R906','R909','R912') and rh01_numcgm = $numcgm and rh01_regist = $registrop";
                $resfer = db_query($sqlfer);
                if($resfer ==false){
                  //echo "erro no ajuste da previdencia.";exit;
                }
                if(pg_numrows($resfer)>0){
                  $valor_desconto_fer = pg_result($resfer,0,0);
                  //echo "<BR>  salario matricula --> $registrop valor_desconto_fer --> $valor_desconto_fer";
                }
                if($arquivo == 'gerfsal'){
                  $sqlfer = " select sum(r48_valor) as valor_desconto_fer
                    from gerfcom
                    inner join rhpessoal on rh01_regist = r48_regist
                    ".bb_condicaosubpes('r48_')."
                    and r48_rubric in ('R903','R906','R909','R912') and rh01_numcgm = $numcgm and rh01_regist = $registrop";
                  $resfer = db_query($sqlfer);
                  if($resfer ==false){
                    //echo "erro no ajuste da previdencia.";exit;
                  }
                  if(pg_numrows($resfer)>0){
                    $valor_desconto_fer += pg_result($resfer,0,0);
                    //echo "<BR>  complementar  matricula --> $registrop valor_desconto_fer --> $valor_desconto_fer";
                  }
                  $sqlcom = " select sum(r48_valor) as valor_desconto_com
                    from gerfcom
                    inner join rhpessoal on rh01_regist = r48_regist
                    ".bb_condicaosubpes('r48_')."
                    and r48_rubric in ('R901','R902','R904','R905','R907','R908','R910','R911') and rh01_numcgm = $numcgm and rh01_regist = $registrop ";
                
                LogCalculoFolha::write("testeaugus".$registrop );


                  $rescom = db_query($sqlcom);
                  if($rescom ==false){
                    //echo "erro no ajuste da previdencia.";exit;
                  }
                  if(pg_numrows($rescom)>0){
                    $valor_desconto_com += pg_result($rescom,0,0);
                  }
                }
                //echo "<BR> valor_desconto_fer -----> ".$valor_desconto_fer;
                //echo "<BR> valor_desconto_com -----> ".$valor_desconto_com;
              }
              if ($pessoal_2[0]["r01_tpcont"] == "13") {
                $perc_inss = 11;
              }
              $mat_r60_numcgm[$nro] = $numcgm;
              $mat_r60_tbprev[$nro] = $tbprev;
              $mat_r60_rubric[$nro] = $rubrica_base ;
              $mat_r60_folha[$nro]  = $qual_folha;
            
              $mat_r60_novods[$nro] = round($novo_desconto, 2);
              $mat_r60_novodf[$nro] = abs(round($valor_desconto_fer - $valor_desconto_com,2));
               
              $mat_r60_dif[$nro]    = round(($novo_desconto -  $valor_desconto_fer),2);
              $mat_r60_novop[$nro]  = $perc_inss;

            }
            $nro++;
          }
 
          if($opcao_geral != 3) {

            asort($mat_r60_dif);
            $valor = 0;
            foreach ($mat_r60_dif as $key => $val) {
              if($val < 0){
                $mat_r60_novods[$key] = $valor_desconto_fer;
                $valor += $val;
                //echo "<BR> mat_r60_novods --> ".$mat_r60_novods[$key]." valor_desconto_fer --> $valor_desconto_fer valor --> $valor val --> $val";
              }else{
                //echo "<BR> 1 mat_r60_novods --> ".$mat_r60_novods[$key]." valor --> $valor ";
                $mat_r60_novods[$key] = $mat_r60_novods[$key] + $valor;
                //echo "<BR> 2 mat_r60_novods --> ".$mat_r60_novods[$key]." valor --> $valor ";
              }
            }
          }
       
          for($nro=0;$nro<count($mat_r60_numcgm);$nro++){
           
            if($opcao_geral != 3 ) {
              //echo "<BR> 1-$nro> ".$mat_r60_numcgm[$nro]." ".$mat_r60_regist[$nro]." ".$mat_r60_novods[$nro]." ".$mat_r60_novop[$nro];
              $matriz1 = array();
              $matriz2 = array();
              $matriz1[ 1 ] = "r60_novod";
              $matriz1[ 2 ] = "r60_novop";
              $matriz2[ 1 ] = ($mat_r60_novods[$nro]) ? $mat_r60_novods[$nro] : 0;
              $matriz2[ 2 ] = ($mat_r60_novop[$nro]) ? $mat_r60_novop[$nro] : 0;
              $condicaoaux  = " and r60_numcgm = ".db_sqlformat($mat_r60_numcgm[$nro] );
              $condicaoaux .= " and r60_tbprev = ".db_sqlformat($mat_r60_tbprev[$nro] );
              $condicaoaux .= " and r60_rubric = ".db_sqlformat($mat_r60_rubric[$nro] );
              $condicaoaux .= " and r60_regist = ".db_sqlformat($mat_r60_regist[$nro] );
              $condicaoaux .= " and upper(r60_folha)  = ".db_sqlformat(strtoupper($mat_r60_folha[$nro]) );
              //echo "<BR> condicaoaux  --> $condicaoaux"; // reis
              db_update("previden", $matriz1, $matriz2, bb_condicaosubpes("r60_").$condicaoaux );

              $rubrica_desconto = "R9".db_str((( 3 * $mat_r60_tbprev[$nro] ) -2 ) -1 + $sequencia ,2,0,"0");

              if ($arquivo == 'gerfres') {
                $rubrica_desconto = $aRubricaDesconto[$tbprev];
              }

              $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($mat_r60_regist[$nro] );
              $condicaoaux .= " and ".$sigla_ajuste."rubric = ".db_sqlformat($rubrica_desconto );

              $ajusta_dif   = round($mat_r60_novods[$nro] - $mat_r60_novodf[$nro],2);
              
              LogCalculoFolha::write("Procurando a incidência da Rubrica {$rubrica_desconto} na tabela $arquivo.");

              if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {

                LogCalculoFolha::write("Não eh que existe!!!");

                $arq_ = $$arquivo;

                LogCalculoFolha::write("Diferença no Ajuste....: {$ajusta_dif}");
                LogCalculoFolha::write("Atingiu o teto..........: " . ($atingiu_o_teto_tbprev ? "Sim" : "Não") );

                if( ($ajusta_dif <= 0 && $atingiu_o_teto_tbprev == false ) || $atingiu_o_teto_tbprev == true){

                  LogCalculoFolha::write("Ajusta_dif --> ".$ajusta_dif." = round({$mat_r60_novods[$nro]}-{$mat_r60_novodf[$nro]},2)");

                  db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
                  LogCalculoFolha::write('Delete: '.bb_condicaosubpes($sigla_ajuste).$condicaoaux."\n\n".print_r(array_combine($matriz1, $matriz2),true));
                  if($arquivo == 'gerfsal'){
                    global $transacao1;
                    $condicaoaux  = " and r14_regist = ".db_sqlformat($mat_r60_regist[$nro]);
                    $condicaoaux .= " and r14_rubric in ( 'R901','R902','R903','R904','R905','R906','R907','R908','R909','R910','R911','R912' )";
                    if (db_selectmax("transacao1", "select sum(r14_valor) as descontod from gerfsal ".bb_condicaosubpes("r14_").$condicaoaux )) {
                      $mat_r60_novods[$nro] = $transacao1[0]["descontod"];
                    
                    }
                  }
              
                } else {
                  
                  
                  $matriz1 = array();
                  $matriz2 = array();
                  $matriz1[1] = $sigla_ajuste."valor";
                  $matriz1[2] = $sigla_ajuste."quant";
                  $matriz2[1] = $ajusta_dif;
                  $matriz2[2] = $mat_r60_novop[$nro];

                  if($arquivo != "gerffer") {
                    //echo "<BR> x valor --> ".$matriz2[1]. "  quant --> ".$matriz2[2];
                    $retornar = db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
                    LogCalculoFolha::write("Modificando {$arquivo} quantidade para: ".$mat_r60_novop[$nro]);
                    LogCalculoFolha::write("Modificando {$arquivo} valores para: ".$ajusta_dif);
                    if($arquivo == 'gerfsal'){
                      global $transacao1;
                      $condicaoaux  = " and r14_regist = ".db_sqlformat($mat_r60_regist[$nro]);
                      $condicaoaux .= " and r14_rubric in ( 'R901','R902','R903','R904','R905','R906','R907','R908','R909','R910','R911','R912' )";
                      if (db_selectmax("transacao1", "select sum(r14_valor) as descontod from gerfsal ".bb_condicaosubpes("r14_").$condicaoaux )) {
                        $mat_r60_novods[$nro] = $transacao1[0]["descontod"];
                      }
                    }
                  }
                }
              } else {
                if ($ajusta_dif > 0) {
                  
                  //echo "<BR> rubrica 36 -->$rubrica_desconto  valor --> ".($mat_r60_novods[$nro]-$mat_r60_novodf[$nro]) ; // reis
                  $oServidorAtual = ServidorRepository::getInstanciaByCodigo($mat_r60_regist[$nro]);

                  $matriz1 = array();
                  $matriz2 = array();
                  $matriz1[1]  = $sigla_ajuste."regist";
                  $matriz1[2]  = $sigla_ajuste."rubric";
                  $matriz1[3]  = $sigla_ajuste."lotac";
                  $matriz1[4]  = $sigla_ajuste."pd";
                  $matriz1[5]  = $sigla_ajuste."valor";
                  $matriz1[6]  = $sigla_ajuste."quant";
                  $matriz1[7]  = $sigla_ajuste."anousu";
                  $matriz1[8]  = $sigla_ajuste."mesusu";
                  $matriz1[9]  = $sigla_ajuste."instit";


                  $matriz2[1] = $mat_r60_regist[$nro];
                  $matriz2[2] = $rubrica_desconto;
                  $matriz2[3] = $oServidorAtual->getCodigoLotacao();
                  $matriz2[4] = 2;
                  $matriz2[5] = $ajusta_dif;
                  $matriz2[6] = $mat_r60_novop[$nro];
                  $matriz2[7] = $anousu;
                  $matriz2[8] = $mesusu;
                  $matriz2[9] = $DB_instit;
                  if ($arquivo == "gerfres" || $arquivo == "gerffer") {
                    $matriz1[10] = $sigla_ajuste."tpp";
                    $matriz2[10] = "F";
                  }
                  $retornar = db_insert($arquivo, $matriz1, $matriz2 );

                }
              }

              // R993 DESC PREVIDENCIA
              global $transacao1;
              $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($mat_r60_regist[$nro]);
              $condicaoaux .= " and ".$sigla_ajuste."rubric in ( 'R901','R902','R903','R904','R905','R906','R907','R908','R909','R910','R911','R912' )";
              if (db_selectmax("transacao1", "select sum(".$sigla_ajuste."valor) as descontod from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste).$condicaoaux )) {
                $mat_r60_novods[$nro] = $transacao1[0]["descontod"];
              }

              $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($mat_r60_regist[$nro] );
              $condicaoaux .= " and ".$sigla_ajuste."rubric = 'R993'";
              //echo "<BR> select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux ;
              if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {
                $arq_ = $$arquivo;
                if ($mat_r60_novods[$nro] > 0) {
                  $matriz1 = array();
                  $matriz2 = array();
                  $matriz1[1] = $sigla_ajuste."valor";
                  $matriz2[1] = $mat_r60_novods[$nro];
                  db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
                  LogCalculoFolha::write('Update: '.bb_condicaosubpes($sigla_ajuste).$condicaoaux."\n\n".print_r(array_combine($matriz1, $matriz2),true));
                } else {
                  //echo "<BR> db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );";
                  db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
                  LogCalculoFolha::write('Delete: '.bb_condicaosubpes($sigla_ajuste).$condicaoaux."\n\n".print_r(array_combine($matriz1, $matriz2),true));
                }
              } else {
                //echo "<BR> rubrica 35.9 -->$numcgm  rubrica baser --> $rubrica_base"; // reis
                if ($mat_r60_novods[$nro] > 0) {
                  //echo "<BR> rubrica 35.10 -->$numcgm  rubrica baser --> $rubrica_base"; // reis
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
                  if ($pessoal_2[0]["r01_tpcont"] == "13") {
                    //echo "<BR> rubrica 35.11 -->$numcgm  rubrica baser --> $rubrica_base"; // reis
                    $perc_inss = 11;
                  }
                  $matriz2[1] = $mat_r60_regist[$nro];
                  $matriz2[2] = 'R993';
                  $matriz2[3] = $pessoal_2[0]["r01_lotac"];
                  $matriz2[4] = 2;
                  $matriz2[5] = $mat_r60_novods[$nro];
                  $matriz2[6] = $mat_r60_novop[$nro] ;
                  $matriz2[7] = $anousu;
                  $matriz2[8] = $mesusu;
                  $matriz2[9] = $DB_instit;
                  //echo "<BR> rubrica 35.12 -->$numcgm  rubrica baser --> $rubrica_base"; // reis
                  if ($arquivo == "gerfres" || $arquivo == "gerffer") {
                    $matriz1[10] = $sigla_ajuste."tpp";
                    $matriz2[10] = " ";
                  }
                  $retornar = db_insert($arquivo, $matriz1, $matriz2 );
                  LogCalculoFolha::write('Insertchê: '.print_r(array_combine($matriz1, $matriz2),true) );
                }
              }
            }elseif($opcao_geral == PONTO_FERIAS) {
              $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($mat_r60_regist[$nro] );
              $condicaoaux .= " and ".$sigla_ajuste."rubric = 'R993'";
              //echo "<BR> 54 select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux ;
              if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {
                db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
                LogCalculoFolha::write('Delete: '.bb_condicaosubpes($sigla_ajuste).$condicaoaux."\n\n".print_r(array_combine($matriz1, $matriz2),true));

              }
            }
            LogCalculoFolha::write();
            LogCalculoFolha::write("Chamando função AjusteIRRF::gravarModificacoes()");
            $tot_desc = 0;
            $tot_prov = 0;
            $salfamilia = 0;
            $tot_liq = 0;
            $salario_esposa = 0;
            $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($mat_r60_regist[$nro] );
            db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux );
            $arq_ = $$arquivo;
            for ($Iarquivo=0; $Iarquivo<count($arq_); $Iarquivo++) {
              if (substr("#". $arq_[$Iarquivo][$sigla_ajuste."rubric"],1,1) != "R") {
                if ($db21_codcli == "999999999" && $arq_[$Iarquivo][$sigla_ajuste."rubric"] == "0045" ) {
                  $salario_esposa += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                } else {
                  if ($arq_[$Iarquivo][$sigla_ajuste."pd"] == 1) {
                    $tot_prov += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                  } else if ($arq_[$Iarquivo][$sigla_ajuste."pd"] == 2) {
                    $tot_desc += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                    if ($db_debug == true) { echo "[ajusta_previdencia] 18 - tot_desc: $tot_desc<br>"; }
                  }
                }
              } else {
                if ($arq_[$Iarquivo][$sigla_ajuste."rubric"] == "R927" || $arq_[$Iarquivo][$sigla_ajuste."rubric"] == "R929") {
                  // R927 ARREDONDAMENTO ANTERIOR
                  // R929 DEBITO MES ANTERIOR
                  $tot_desc += round($arq_[$Iarquivo][$sigla_ajuste."valor"],2);
                  if ($db_debug == true) { echo "[ajusta_previdencia] 19 - tot_desc: $tot_desc<br>"; }
                } elseif($arq_[$Iarquivo][$sigla_ajuste."rubric"] != "R928") {
                  $nro1 = db_val(substr("#".$arq_[$Iarquivo][$sigla_ajuste."rubric"],3,2));
                  if ($nro1 >= 17 && $nro1 <= 22) {
                    // salario familia
                    $salfamilia = round($arq_[$Iarquivo][$sigla_ajuste."valor"],2);
                  }elseif ($nro1 <= 50) {
                    if ($arq_[$Iarquivo][$sigla_ajuste."pd"] == 1) {
                      $tot_prov += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                    } else if ($arq_[$Iarquivo][$sigla_ajuste."pd"] == 2) {
                      $tot_desc += $arq_[$Iarquivo][$sigla_ajuste."valor"];
                      if ($db_debug == true) {echo "[ajusta_previdencia] 20 - tot_desc: $tot_desc<br>"; }
                    }
                  }
                }
              }
            }
            if ( $db21_codcli == "18" ) {
              $salario_familia = 0;
            }
            if (!db_empty($tot_prov) || !db_empty($tot_desc)) {

              if ($tot_prov > $tot_desc) {
                $r01_rubric = "R926";

                $tot_liq = $tot_prov + $salario_esposa - $tot_desc;
                $arredn = arredonda_100($tot_liq, $cfpess[0]["r11_arredn"]);
                $tot_liq += $arredn;
                //echo "<BR> R926 --> $arredn";
              } else {
                $arredn = $tot_desc + $salario_esposa - $tot_prov;
                $r01_rubric = "R928";
                //echo "<BR> R928 --> $arredn";
              }

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

                $matriz2[1] = $mat_r60_regist[$nro] ;
                $matriz2[2] = $r01_rubric;
                $matriz2[3] = $pessoal_2[0]["r01_lotac"];
                $matriz2[4] = 1;
                $matriz2[5] = $arredn;
                $matriz2[6] = 0;
                $matriz2[7] = $anousu;
                $matriz2[8] = $mesusu;
                $matriz2[9] = $DB_instit;

                if ($arquivo == "gerfres" || $arquivo == "gerffer") {
                  $matriz1[10] = $sigla_ajuste."tpp";
                  $matriz2[10] = " ";
                }
                $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($mat_r60_regist[$nro] );
                $condicaoaux .= " and ".$sigla_ajuste."rubric = ".db_sqlformat($r01_rubric );
                if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {
                  LogCalculoFolha::write('Update: '.bb_condicaosubpes($sigla_ajuste).$condicaoaux."\n\n".print_r(array_combine($matriz1, $matriz2),true));;
                  db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
                } else {
                  db_insert($arquivo, $matriz1, $matriz2 );
                  LogCalculoFolha::write('Insertchê: '.print_r(array_combine($matriz1, $matriz2),true) );
                }
              }
              // Fim do Calculo da Insuficiencia de Saldo
            }
          }
        }
      }
    }
  }

  static function gravarDadosCalculados( Servidor $oServidorPrincipal, FolhaPagamento $oFolha ) {

    LogCalculoFolha::write("---------------------------Inicio funcao de gravacao dos Dados de Previdencia---------------------------");
    /**
     * Se o servidor não tem previdencia, não faz ajuste. SIMPLE =)
     */
    if ( $oServidorPrincipal->getTabelaPrevidencia() == 0 ) {
      return;
    }

    /**
     * Se o servidor não tem Duplo vinculo, também não será necessário fazer ajustes :]~
     */
    if ( !$oServidorPrincipal->hasServidorVinculado() ) {
      return;
    }

    /**
     * R901 - % Previdencia 1 S/SALARIO
     * R902 - % Previdencia 1 S/13§ SALARIO
     * R903 - % Previdencia 1 S/FERIAS
     *
     * R904 - % Previdencia 2 S/ SALÁRIO
     * R905 - % Previdencia 2 S/ 13o SALÁRIO
     * R906 - % Previdencia 2 S/ FÉRIAS
     *
     * R907 - % Previdencia 3 S/SALARIO
     * R908 - % Previdencia 3 S/13§ SALARIO
     * R909 - % Previdencia 3 S/FERIAS
     *
     * R910 - % Previdencia 4 S/SALARIO
     * R911 - % Previdencia 4 S/13§ SALARIO
     * R912 - % Previdencia 4 S/FERIAS
     */
    $aDeParaRubricasDesconto    = array(
      1 => "R901",
      2 => "R904",
      3 => "R907",
      4 => "R910"
    );

    /**
     * O Código no cadastro do servidor diferrZ
     * 1 => INSS        --> codigo no inssirf --> 3
     * 2 => Previdência --> codigo no inssirf --> 4
     * 3 => Previdência --> codigo no inssirf --> 5
     * 4 => Previdência --> codigo no inssirf --> 6
     */
    $aDeParaPrevidenciaServidor = array(
      1 => 3,
      2 => 4,
      3 => 5,
      4 => 6
    );
    $aServidores = array($oServidorPrincipal, $oServidorPrincipal->getServidorVinculado());

    foreach ( $aServidores as $oServidor ) {

      LogCalculoFolha::write("Gravando dados de previdencia no Servidor de Matricula: ". $oServidor->getMatricula() );
      /**
       * Rubrica Base de Previdencia
       *
       * R985 - SAlario
       * R986 - 13º
       * R987 - Férias
       */
      $sRubricaSomaBasePrevidencia = self::RUBRICA_SOMA_BASE_SALARIO;
      $sRubricaBasePrevidencia     = self::RUBRICA_BASE_SALARIO;
      $iTabelaPrevidencia          = $aDeParaPrevidenciaServidor[$oServidor->getTabelaPrevidencia()];
      $sRubricaDesconto            = $aDeParaRubricasDesconto[$oServidor->getTabelaPrevidencia()];

      $oCalculoSalario             = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_SALARIO);
      $oCalculoComplementar        = $oServidor->getCalculoFinanceiro(CalculoFolha::CALCULO_COMPLEMENTAR);
      $aEventosSalario             = $oCalculoSalario->getEventosFinanceiros(null, $sRubricaSomaBasePrevidencia);//R992

      /**
       * Se não tem R992 valor
       */
      $nValorSomaBases = 0;

      if ( !empty($aEventosSalario[0]) ) {
        $nValorSomaBases = $aEventosSalario[0]->getValor();
      }

      $aEventosSalario       = $oCalculoSalario->getEventosFinanceiros(null, $sRubricaBasePrevidencia);//R985 - Folha Salario
      $aEventosComplementar  = $oCalculoComplementar->getEventosFinanceiros(null, $sRubricaBasePrevidencia);//R985 - Folha Complementar

      $aEventosDescontos     = $oCalculoSalario->getEventosFinanceiros(null, $sRubricaDesconto);       //R9xx
      $aEventosBaseSalario   = array_merge($aEventosComplementar, $aEventosSalario);
      $nValorBase            = 0;//Soma da Rubrica R985
      $nValorDesconto        = 0;//Soma da Rubrica R9(01 a 12)
      /**
       * Percorrendo o valor do Evento com rubrica r985 nas folhas de salário .
       */
      for ( $iEvento = 0;  $iEvento < count($aEventosBaseSalario); $iEvento++ ) {

        $oEvento     = $aEventosBaseSalario[$iEvento];
        $nValorBase += $oEvento->getValor();
      }

      for ( $iEvento = 0;  $iEvento < count($aEventosDescontos); $iEvento++ ) {

        $oEvento         = $aEventosDescontos[$iEvento];
        $nValorDesconto += $oEvento->getValor();

      }

      $sWhereExclusao = "     r60_anousu = {$oServidor->getAnoCompetencia()}";
      $sWhereExclusao.= " and r60_mesusu = {$oServidor->getMesCompetencia()}";
      $sWhereExclusao.= " and r60_numcgm = {$oServidor->getCgm()->getCodigo()}";
      $sWhereExclusao.= " and r60_regist = {$oServidor->getMatricula()}";
      $sWhereExclusao.= " and r60_tbprev = {$oServidor->getTabelaPrevidencia()}";
      $sWhereExclusao.= " and r60_folha  = 'S'";
      $oDaoPreviden = new cl_previden();

      $oDaoPreviden->excluir(null,null,null,null,null, $sWhereExclusao);

      $oDaoPreviden = new cl_previden();

      $oDaoPreviden->r60_numcgm  = $oServidor->getCgm()->getCodigo();
      $oDaoPreviden->r60_regist  = $oServidor->getMatricula();
      $oDaoPreviden->r60_tbprev  = $oServidor->getTabelaPrevidencia();
      $oDaoPreviden->r60_base    = "$nValorBase";
      $oDaoPreviden->r60_dprev   = "$nValorDesconto";
      $oDaoPreviden->r60_novod   = "0";
      $oDaoPreviden->r60_rubric  = $sRubricaBasePrevidencia;
      $oDaoPreviden->r60_folha   = "S";
      $oDaoPreviden->r60_altera  = "t";
      $oDaoPreviden->r60_anousu  = $oServidor->getAnoCompetencia();
      $oDaoPreviden->r60_mesusu  = $oServidor->getMesCompetencia();
      $oDaoPreviden->r60_ajuste  = 't';
      $oDaoPreviden->r60_basef   = "$nValorSomaBases";
      $oDaoPreviden->r60_pdesc   = "0.00";
      $oDaoPreviden->r60_novop   = "0.00";

      LogCalculoFolha::write();
      LogCalculoFolha::write("Adicionando valores na tabela 'PREVIDEN': ");
      LogCalculoFolha::write("Matricula   : {$oDaoPreviden->r60_regist}");
      LogCalculoFolha::write("CGM         : {$oDaoPreviden->r60_numcgm}");
      LogCalculoFolha::write("BASE        : {$oDaoPreviden->r60_base}");
      LogCalculoFolha::write("BASE SOMA   : {$oDaoPreviden->r60_basef}");
      LogCalculoFolha::write("DESCONTO    : {$oDaoPreviden->r60_dprev}");

      $oDaoPreviden->incluir(
        $oDaoPreviden->r60_anousu,
        $oDaoPreviden->r60_mesusu,
        $oDaoPreviden->r60_numcgm,
        $oDaoPreviden->r60_tbprev,
        $oDaoPreviden->r60_rubric
      );

      LogCalculoFolha::write($oDaoPreviden->erro_msg);
    }
  }

  /**
   * Adiciona um evento financeiro em memória para que seja recalculado o valor quando todos os vinculos do servidor
   * forem inativos
   *
   * @param EventoFinanceiroFolha $oEvento
   */
  public static function adicionarValorPrevidenciaSemTeto( EventoFinanceiroFolha $oEvento ) {

    $iCgm       = $oEvento->getServidor()->getCgm()->getCodigo();
    $iMatricula = $oEvento->getServidor()->getMatricula();
    $sRubrica   = $oEvento->getRubrica()->getCodigo();
    self::$aValoresBase[$iCgm][$sRubrica][$iMatricula] = $oEvento;
    return;
  }

  public static function todasMatriculasInativas( CgmFisico $oCGM ) {

    $aServidores    = ServidorRepository::getServidoresByCgm($oCGM);
    $lTodosInativos = true;

    while( list($iIndice, $oServidor) = each($aServidores) ) {

      if ( $oServidor->getVinculo()->getTipo() == VinculoServidor::VINCULO_ATIVO ) {
        $lTodosInativos = false;
        break;
      }
    }
    return $lTodosInativos;
  }


  public static function ajustarValorInativosQueNaoAlcancaramTeto($sRubrica, $sTipoArquivo) {

    switch ( $sTipoArquivo){
    case "S":
      $sTabela = CalculoFolhaSalario::TABELA;
      $sSigla  = CalculoFolhaSalario::SIGLA_TABELA;
      break;
    case "C";
      $sTabela = CalculoFolhaComplementar::TABELA;
      $sSigla  = CalculoFolhaComplementar::SIGLA_TABELA;
      break;
    case "3";
      $sTabela = CalculoFolha13o::TABELA;
      $sSigla  = CalculoFolha13o::SIGLA_TABELA;
      break;
    case "R";
      $sTabela = CalculoFolhaRescisao::TABELA;
      $sSigla  = CalculoFolhaRescisao::SIGLA_TABELA;
      break;
    case "F";
      $sTabela = CalculoFolhaFerias::TABELA;
      $sSigla  = CalculoFolhaFerias::SIGLA_TABELA;
      break;
    }

    while ( list($iCgm, $aRubricas ) = each( self::$aValoresBase ) ) {

      LogCalculoFolha::write('Inicio do Laco CGM atual: '.$iCgm);

      if (!array_key_exists($sRubrica, $aRubricas) ) {
        continue;
      }
      /**
       * Se todas as matriculas não forem inativas não continua
       */
      if (!self::todasMatriculasInativas(CgmFactory::getInstanceByCgm($iCgm))) {
        LogCalculoFolha::write();
        LogCalculoFolha::write('--CGM contem matricula ativa. '. $iCgm);
        continue;
      }
      $nValorTeto  = self::$aValorTeto[$iCgm];
      $aEventos    = $aRubricas[$sRubrica];
      $nTotalBases = 0;

      LogCalculoFolha::write();
      LogCalculoFolha::write('--Valor do teto do CGM: '.$nValorTeto);

      $aValores = array();

      while ( list($iMatricula, $oEvento ) = each( $aEventos ) ) {

        $aValores[$iMatricula]  = $oEvento->getValor();
        $nTotalBases           += $oEvento->getValor();

        LogCalculoFolha::write();
        LogCalculoFolha::write('-- Matricula................: ' . $iMatricula);
        LogCalculoFolha::write('-- Evento financeiro........: ' . $oEvento->getRubrica()->getCodigo());
        LogCalculoFolha::write('-- Valor total das bases....: ' . $nTotalBases);
      }

      /**
       * Caso o valor das bases não atinja o teto
       * Vai para o proximo cgm
       */
      if ( $nTotalBases < $nValorTeto ) {
        LogCalculoFolha::write('Valor das bases somadas é menor que o teto para o cgm.'. $iCgm);
        continue;
      }

      $nValorBaseCorreto =  $nTotalBases - $nValorTeto;
      LogCalculoFolha::write();
      LogCalculoFolha::write('Valor das bases: '.$nTotalBases);
      LogCalculoFolha::write('Valor do teto: '.$nValorTeto);
      LogCalculoFolha::write('Valor da base correto: '.$nValorBaseCorreto);

      foreach ($aValores as $iMatricula => $nValor) {

        LogCalculoFolha::write();
        LogCalculoFolha::write('Matricula: '.$iMatricula);
        LogCalculoFolha::write('Valor: '.$nValor);

        $nPercentual          =  $nValor/ $nTotalBases;
        $nValorBaseIndividual = round($nValorBaseCorreto * $nPercentual,2);
        db_query("update previden
                     set r60_basef  = $nValorBaseIndividual,
                         r60_base   = $nValorBaseIndividual,
                         r60_dprev  = 0
                   where r60_regist = $iMatricula
                     and r60_numcgm = $iCgm
                     and r60_folha  = '$sTipoArquivo'
                     and r60_rubric = '$sRubrica';");

        db_update($sTabela,
                  array(1=>"{$sSigla}_valor"),
                  array(1=>"{$nValorBaseIndividual}"),
                  bb_condicaosubpes("{$sSigla}_")." and {$sSigla}_regist = {$iMatricula} and {$sSigla}_rubric in ('{$sRubrica}','R992')");

        }
      }
    return;
  }
}
