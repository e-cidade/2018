<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBselller Servicos de Informatica             
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

function grava_cadferia () {
 
  global $pessoal, $Ipessoal,$mtipo, $r30_perai, $ponto, $r30_tipoapuracaomedia, $debug;
  global $r30_perai,$ponto,$r30_peraf, $r30_faltas, $r30_peri,$r30_perf, $subpes_pagamento, $mtipo, $nsaldo_anterior, $ndiassaldo;
  global $nabono, $r30_ndias, $paga_13, $mpsal ,$subpes,$cadferia, $r30_peri,$r30_perf, $mtipo, $nsaldo, $mpsal;
  global $r30_periodolivreinicial_dia, $r30_periodolivreinicial_mes, $r30_periodolivreinicial_ano, $r30_periodolivreinicial;
  global $r30_periodolivrefinal_dia, $r30_periodolivrefinal_mes, $r30_periodolivrefinal_ano, $r30_periodolivrefinal,$r30_obs;
  
  $condicaoaux  =  " and r30_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] ) ;
  $condicaoaux .=  " and r30_perai = ".db_sqlformat($r30_perai) ;

  if( ($mtipo >= "01" && $mtipo <= "08") || ($mtipo >= "12" && $mtipo <= "15") || $mtipo == "999") {

       $matriz1 = array();
       $matriz2 = array();
       $matriz1[1]  = "r30_regist";
       $matriz1[2]  = "r30_numcgm";
       $matriz1[3]  = "r30_perai";
       $matriz1[4]  = "r30_ponto" ;
       $matriz1[5]  = "r30_peraf";
       $matriz1[6]  = "r30_faltas";
       $matriz1[7]  = "r30_per1i";
       $matriz1[8]  = "r30_per1f";
       $matriz1[9]  = "r30_proc1";
       $matriz1[10] = "r30_tip1";
       $matriz1[11] = "r30_dias1";
       $matriz1[12] = "r30_abono";
       $matriz1[13] = "r30_ndias";
       $matriz1[14] = "r30_paga13";
       $matriz1[15] = "r30_psal1";
       $matriz1[16] = "r30_anousu";
       $matriz1[17] = "r30_mesusu";
       $matriz1[18] = "r30_obs";

       if (isset($r30_tipoapuracaomedia) && $r30_tipoapuracaomedia == 2) {

         $matriz1[19] = "r30_tipoapuracaomedia";
         $matriz1[20] = "r30_periodolivreinicial";
         $matriz1[21] = "r30_periodolivrefinal";
       }
     
       $matriz2[1] = $pessoal[0]["r01_regist"];
       $matriz2[2] = $pessoal[0]["r01_numcgm"];
       $matriz2[3] = db_nulldata($_POST["r30_perai"]);
       $matriz2[4] = $ponto;
       $matriz2[5] = db_nulldata($_POST["r30_peraf"]);
       $matriz2[6] = $r30_faltas+0;
       $matriz2[7] = db_nulldata($r30_peri);
       $matriz2[8] = db_nulldata($r30_perf);
       $matriz2[9] = $subpes_pagamento;

       if ($mtipo == 999) {
       	 $matriz2[10] = "00";
       } else {
         $matriz2[10] = $mtipo;
       }

       $matriz2[11] = $nsaldo_anterior;
       if ($nabono == "") {
         $nabono  = 0;
       }

       $matriz2[12] = $nabono;
       $matriz2[13] = $r30_ndias;
       $matriz2[14] = strtoupper($paga_13);
       $matriz2[15] = $mpsal;
       $matriz2[16] = db_val( db_substr($subpes,1,4));
       $matriz2[17] = db_val( db_substr($subpes,6,2));
       $matriz2[18] = $r30_obs;

       if (isset($r30_tipoapuracaomedia) && $r30_tipoapuracaomedia == 2) {

         $matriz2[19] = $r30_tipoapuracaomedia;
         $matriz2[20] = implode("-", array_reverse(explode("/",$r30_periodolivreinicial)));
         $matriz2[21] = implode("-", array_reverse(explode("/",$r30_periodolivrefinal)));
         $r30_perai =     $r30_periodolivreinicial;
         $r30_peraf =     $r30_periodolivrefinal;
       }

     if ($debug == true) {
       echo "grava_cadferia: Inserindo em cadferia: <br>";
       echo "grava_cadferia: Valores: <br>";
       echo "grava_cadferia:<pre>";
       print_r($matriz2);
       echo "</pre>";
       echo "<br>";
     }  

     db_insert( "cadferia", $matriz1, $matriz2 );
  } else if( $mtipo == "09") {
     
    db_debug1("vai gravar r30_per2i --> $r30_peri");
    db_debug1("vai gravar r30_per2i --> $r30_perf");
     $matriz1 = array();
     $matriz2 = array();
     $matriz1[1] = "r30_per2i";
     $matriz1[2] = "r30_per2f";
     $matriz1[3] = "r30_proc2";
     $matriz1[4] = "r30_tip2";
     $matriz1[5] = "r30_dias2";
     $matriz1[6] = "r30_psal2";
     $matriz1[7] = "r30_ponto" ;
     $matriz2[1] = db_nulldata($r30_peri);
     $matriz2[2] = db_nulldata($r30_perf);
     $matriz2[3] = $subpes_pagamento;
     $matriz2[4] = $mtipo;
     $matriz2[5] = $ndiassaldo;
     $matriz2[6] = $mpsal;
     $matriz2[7] = $ponto;
     db_update( "cadferia", $matriz1, $matriz2, bb_condicaosubpes("r30_").$condicaoaux );

  } else if( $mtipo == "10") { 
     $matriz1 = array();
     $matriz2 = array();
     $matriz1[1] = "r30_abono";
     $matriz1[2] = "r30_proc2";
     $matriz1[3] = "r30_tip2";
     $matriz1[4] = "r30_psal2";
     $matriz2[1] = $nabono;
     $matriz2[2] = $subpes_pagamento;
     $matriz2[3] = $mtipo;
     $matriz2[4] = $mpsal;
     db_update( "cadferia", $matriz1, $matriz2, bb_condicaosubpes("r30_").$condicaoaux );

  }
}

function limpa_ponto_ferias() {

  global $pessoal,$Ipessoal, $debug;
  $condicaoaux = " and r29_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
  db_delete( "pontofe", bb_condicaosubpes( "r29_" ).$condicaoaux );
}


function limpsal($ponto){
	
    global $subpes_pagamento,$subpes,$pessoal,$Ipessoal,$pontofs,$rubricas,$cfpess,$paga_13, $debug;
    global $nsaldo, $nabono, $dias_adi,$r30_peri,$r30_perf;

   $dias_no_mes = ndias(db_substr($subpes,6,2)."/".db_substr($subpes,1,4));

   $ok = false;
   if($dias_no_mes >= 30 && ($nsaldo == 30 && db_month($r30_peri) == db_month($r30_perf) ) ){
     $ok = true;
   }else if(db_substr($subpes,6,2) == "02" && ($nsaldo == 30 && db_day($r30_peri) == 1) ){
     $ok = true;
   }
   
   if($debug == true) {
     echo '$subpes_pagamento'.$subpes_pagamento.' == $subpes'.$subpes.'?<br>';
   }
   if ( $subpes_pagamento == $subpes) {
     if($debug == true){
       echo "SIM<br>";
     }
     
      $condicaoaux = " and r10_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
      db_selectmax( "pontofs", "select * from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux );
      for ($Ipontofs=0;$Ipontofs< count($pontofs);$Ipontofs++) {
         $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $pontofs[$Ipontofs]["r10_rubric"] );
         
         if ( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) ) {
           if ($rubricas[0]["rh27_tipo"] == 1 || ($rubricas[0]["rh27_tipo"] == 2 && $ok && $rubricas[0]["rh27_limdat"] == 't') ) {
             
              $condicaoaux  = " and r10_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
              $condicaoaux .= " and r10_rubric = ".db_sqlformat( $rubricas[0]["rh27_rubric"] );
              
              db_delete( "pontofs", bb_condicaosubpes( "r10_" ).$condicaoaux );
           }
           
         } else if( $ponto == "C" && strtolower($cfpess[0]["r11_fersal"]) == 'f' && $paga_13 == 'f' && $ok == true && $rubricas[0]["rh27_limdat"] == 't' && $rubricas[0]["rh27_pd"] == '2' ) {
		       $matriz1ps = array();
		       $matriz2ps = array();
		       $matriz1ps[1] = "r47_regist";
		       $matriz1ps[2] = "r47_rubric";
		       $matriz1ps[3] = "r47_valor";
		       $matriz1ps[4] = "r47_quant";
		       $matriz1ps[5] = "r47_lotac";
		       $matriz1ps[6] = "r47_anousu";
		       $matriz1ps[7] = "r47_mesusu";
           $matriz1ps[8] = "r47_instit";
           
		       $matriz2ps[1] = $pontofs[$Ipontofs]["r10_regist"];
		       $matriz2ps[2] = $pontofs[$Ipontofs]["r10_rubric"];
		       $matriz2ps[3] = $pontofs[$Ipontofs]["r10_valor"];
		       $matriz2ps[4] = $pontofs[$Ipontofs]["r10_quant"];
		       $matriz2ps[5] = $pontofs[$Ipontofs]["r10_lotac"];
		       $matriz2ps[6] = db_val( db_substr( $subpes,1,4 ) );
		       $matriz2ps[7] = db_val( db_substr( $subpes, -2 ) );
           $matriz2ps[8] = db_getsession("DB_instit");

		       $condicaoaux  = " and r47_regist = ".db_sqlformat( $pontofs[$Ipontofs]["r10_regist"] );
		       $condicaoaux .= " and r47_rubric = ".db_sqlformat( $pontofs[$Ipontofs]["r10_rubric"] );
		       if( !db_selectmax( "pontocom", "select * from pontocom ".bb_condicaosubpes( "r47_" ).$condicaoaux )){
		          db_insert( "pontocom", $matriz1ps, $matriz2ps );
		       }else{
		          db_update( "pontocom", $matriz1ps, $matriz2ps, bb_condicaosubpes( "r47_").$condicaoaux  );
		       }
           $condicaoaux  = " and r10_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
           $condicaoaux .= " and r10_rubric = ".db_sqlformat( $rubricas[0]["rh27_rubric"] );
           db_delete( "pontofs", bb_condicaosubpes( "r10_" ).$condicaoaux );
	       }
      }
      
   if ($debug == true) {   
     echo "limpando calculo de salarios:<br>";
   }
   $condicaoaux = " and r14_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
   db_delete( "gerfsal", bb_condicaosubpes( "r14_" ).$condicaoaux );

   /**
    * Se a variavel $DB_COMPLEMENTAR estiver ativa, exclui os dados da tabela 
    * rhhistoricocalculo e rhhistoricoponto, para obrigar o usuario a recalcular o salário.
    */
   if (DBPessoal::verificarUtilizacaoEstruturaSuplementar()) {
    
     $oCompetencia  = new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
     $aFolhaSalario = FolhaPagamento::getFolhaCompetenciaTipo($oCompetencia, FolhaPagamento::TIPO_FOLHA_SALARIO);
     $iFolhaSalario = $aFolhaSalario[0]->getSequencial();

     $oDaoRhHistoricoCalculo    = new cl_rhhistoricocalculo();
     $sWhereRhHistoricoCalculo  = "     rh143_folhapagamento = {$iFolhaSalario} ";
     $sWhereRhHistoricoCalculo .= " and rh143_regist         = {$pessoal[0]["r01_regist"]} ";
     $oDaoRhHistoricoCalculo->excluir(null, $sWhereRhHistoricoCalculo);
   }

   }
//   exit;
   return;
}

function limpaAjustesIrrfPreviden() {

  global $pessoal,$debug;

  if ($debug == true) {   
    echo "limpando Ajustes de IRRF:<br>";
  }

  $oServidorAtual      = ServidorRepository::getInstanciaByCodigo($pessoal[0]["r01_regist"],
                                                                  DBPessoal::getAnoFolha(), 
                                                                  DBPessoal::getMesFolha());
  $aMatriculasAjusteIrPreviden = array($oServidorAtual->getMatricula());

  if($oServidorAtual->hasServidorVinculado()) {

    $oServidorVinculado            = $oServidorAtual->getServidorVinculado();
    $aMatriculasAjusteIrPreviden[] = $oServidorVinculado->getMatricula();

  }

  $sMatriculasAjusteIrPreviden = join(",", $aMatriculasAjusteIrPreviden);

  $condicaoAuxiliarAjusteir    = " and r61_folha  in ('S', 'F', 'C')
                                   and r61_regist in (". $sMatriculasAjusteIrPreviden .")
                                   and r61_numcgm = ". $oServidorAtual->getCgm()->getCodigo();

  $condicaoAuxiliarPreviden    = " and r60_folha  in ('S', 'F', 'C')
                                   and r60_regist in (". $sMatriculasAjusteIrPreviden .")
                                   and r60_numcgm = ". $oServidorAtual->getCgm()->getCodigo();

  db_delete( "ajusteir", bb_condicaosubpes( "r61_" ).$condicaoAuxiliarAjusteir );
  db_delete( "previden", bb_condicaosubpes( "r60_" ).$condicaoAuxiliarPreviden );
}

// Procedimento para deducao da quantidade de meses no periodo aquisicao 


function cadastro_162(){

  global $pessoal, $Ipessoal,$cfpess,$afasta,$pontocom,$mdabo, $debug;
  global $protelac,$Iprotelac,$assenta;
  global $rubricas, $pontofx; 
  global $m_rubr,$qten,$vlrn,$m_media,$quants ,$quantd,$m_valor,$m_quant;
  global $nsaldo, $nabono, $dias_adi,$nsaldo_anterior;
  global $r30_perai,$ponto,$r30_peraf, $r30_faltas, $r30_peri,$r30_perf, $mtipo, $nsaldo_anterior;
  global $r30_ndias, $cadferia, $r30_peri,$r30_perf, $mpsal,$r30_regist;
  global $subpes,$subpes_pagamento;
  global $cter, $nsalar, $paga_13, $mpsal;
  global $subpes, $matriz1, $matri2,$subpes_ofi;
  global $ndias, $pontofs;
  global $cfpess,$max,$matric,$navos,$d08_carnes,$indano,$indmes,$subpes_ant;
  global $r30_periodolivreinicial_dia, $r30_periodolivreinicial_mes, $r30_periodolivreinicial_ano, $r30_periodolivreinicial;
  global $r30_periodolivrefinal_dia, $r30_periodolivrefinal_mes, $r30_periodolivrefinal_ano, $r30_periodolivrefinal,$r30_tipoapuracaomedia;
 
  $d08_carnes = strtolower(trim($d08_carnes)); 
  db_selectmax("cfpess","select * from cfpess where r11_anousu=".db_substr($subpes,1,4)." and r11_mesusu=".db_substr($subpes,-2)." and r11_instit = ".db_getsession("DB_instit"));
  $sDataPeriodoInicial = $r30_perai; 
  $sDataPeriodoFinal   = $r30_peraf; 
  $subpes_ofi = $subpes ;
  
  if (isset($r30_periodolivrefinal) && $r30_periodolivrefinal != "") {
    $r30_peraf = implode("-", array_reverse(explode("/", $r30_periodolivrefinal)));
  }
  if (isset($r30_periodolivreinicial) && $r30_periodolivreinicial != "") {
    $r30_perai = implode("-", array_reverse(explode("/", $r30_periodolivreinicial)));
  }
  $m_rubr = array();
  $m_quant= array();
  $m_valor= array();
  $m_media= array();
  $m_tipo = array();
  $qten   = array();
  $vlrn   = array();
  
  if( db_substr($subpes,6,2) == "12"){
    $subtem = db_str(db_val(db_substr($subpes,1,4))+1,4)."/01";
  }else{
    $subtem = db_substr($subpes,1,4)."/".db_str(db_val(db_substr($subpes,6,2))+1,2,0,"0");
  }
  $datlgo = db_str(ndias( db_substr($subtem,6,2)."/".db_substr($subtem,1,4) ),2,0,"0")."/".db_substr($subtem,6,2)."/".db_str( db_val( db_substr($subtem,1,4) ) ,4,0,"0");
  $datlgo = date("Y-m-d",db_mktime(db_ctod($datlgo)) + (60*86400));
  $datigo = db_ctod("01/".db_substr($subpes,6,2)."/".db_str(db_val(db_substr($subpes,1,4)),4,0,"0"));
 


      $matric = $r30_regist;
       
//      $condicaoaux = " and r01_regist = ".db_sqlformat( $matric );
//      db_selectmax( "pessoal", "select * from pessoal ".bb_condicaosubpes( "r01_" ).$condicaoaux ); 
      
  $campos_pessoal  = "RH02_ANOUSU as r01_anousu, 
                      RH02_ANOUSU as r01_mesusu, 
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
                      rh01_depsf as r01_depsf";

      $condicaoaux = " and rh01_regist = ".db_sqlformat( $matric );
      db_selectmax("pessoal", "select ".$campos_pessoal." from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
											                        and rhlota.r70_instit           = rhpessoalmov.rh02_instit 
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                       left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                       left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
											                   and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                       left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes = rhpessoalmov.rh02_seqpes 
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_seqpes
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      $mdabo = false;
      $mpsal = true;
      $cter = " ";
      
// Inicio do Bloco que Carrega os dados para calcular as media
      
    
      global $anopagto, $mespagto; 
//echo "<BR> 0 subpes   --> $subpes";      
//echo "<BR> anopagto --> $anopagto";      
//echo "<BR> mespagto --> $mespagto";      
      $subpes_pagto = $subpes;
      $subpes = db_str($anopagto,4)."/".db_str($mespagto,2,0,"0");
//echo "<BR> 1 subpes   --> $subpes";      
      $subpes_pagamento = db_str($anopagto,4)."/".db_str($mespagto,2,0,"0");
//echo "<BR> subpes_pagamento --> $subpes_pagamento";      
      $mes_ano = db_str($mespagto,2,0,"0") . "/" . db_str($anopagto,4);
      $subpes = $subpes_pagto;
//echo "<BR> 1 subpes   --> $subpes";

      if($debug == true){
        echo "Chamando a função limpa_ponto_ferias():<br>";
      }
      limpa_ponto_ferias();
      if($debug == true) {
        echo "Fim da chamada da função limpa_ponto_ferias()<br>";
      }
      
      $max = 0;
      
      $qten[0]= 1;
      $vlrn[0]= 1;
      
      $indano = db_year($r30_perai);
      $indmes = db_month($r30_perai);
      $mes_ano = db_str( db_month($r30_perai ) ,2,0,"0") . "/" . db_str( db_year($r30_perai ),4);
//echo "<BR> if( ( (".ndias( $mes_ano )." - ".db_day($r30_perai)." ) < 15 ) || ( $indmes == 2 && ".db_day( $r30_perai )." == 15 ) )";
      if( ( ( ndias( $mes_ano ) - db_day($r30_perai) ) < 15 ) || ( $indmes == 2 && db_day( $r30_perai ) == 15 ) ){
        $indmes++;
        if( $indmes > 12){
           $indmes = 01;
           $indano++;
        }
      }
      $subpes_ant = $subpes;
      
      if($debug == true){
        echo "Chamando a função levanta_ponto():<br>";
      }      
      levanta_ponto();
      if($debug == true){
        echo "Fim da chamanda da função levanta_ponto()<br>";
      }      

// Fim do Bloco que Carrega os dados para calcular as media
      
      if( trim( $d08_carnes ) == "carazinho" && $pessoal[0]["r01_regime"] != 2 ){
         horasextrascarazinho();
      }
//      if( trim( $d08_carnes ) == "guaiba" ){
//         horasextrasguaiba();
//      }
//     //echo "<BR> 1 nsaldo --> $nsaldo"; 
      $subant = $subpes;  
      $subpes = $subpes_ant;

      if($debug == true){
        echo "Chamando a função acrescentapontofx():<br>";
      }      
      acrescentapontofx();
      if($debug == true){
        echo "Fim da chamanda da função acrescentapontofx():<br>";
      }      

      $nsaldo_anterior = $nsaldo;
      $dias_adi = 0;
      if(db_year($r30_peri) > $anopagto || (db_month($r30_peri) > $mespagto  && db_year($r30_peri) == $anopagto )  ){
         if( strtolower($cfpess[0]["r11_fersal"]) == 'f' && !db_boolean($cfpess[0]["r11_recalc"]) ){
            $dias_adi = 0;
            $nsaldo = $nsaldo;
         }else {
            $dias_adi = $nsaldo;
            $nsaldo = 0;
         }
     //echo "<BR> 2 nsaldo --> $nsaldo"; 
      }else{
         if((db_month($r30_perf) > db_month($r30_peri) && db_year($r30_peri) == db_year( $r30_perf ) ) 
            ||  db_year($r30_perf) > db_year( $r30_peri ) ){
	    // ver mod abaixo
            if( ( db_year( $r30_peri )%4 ) != 0  && db_month($r30_peri) == 1 && db_day($r30_peri) == 31 && db_month($r30_perf) == 3 ){
     //echo "<BR> 3 nsaldo --> $nsaldo"; 
               $nsaldo = 1;
               $dias_adi = 29;
            }else{
               if( !( strtolower($cfpess[0]["r11_fersal"]) == 's' && !db_boolean($cfpess[0]["r11_recalc"]) )){
     //echo "<BR> 4 nsaldo --> $nsaldo"; 
                  $dias_adi = db_datedif($r30_perf,db_substr($r30_perf,1,8)."01")+1;
//echo "<BR> $dias_adi = db_datedif($r30_perf,".db_substr($r30_perf,1,8)."01) + 1";
                  $nsaldo -= $dias_adi;
               }
            }
         }
      }

//echo "<BR> nsaldo_anterior --> $nsaldo_anterior nsaldo --> $nsaldo dias_adi --> $dias_adi";
      if( db_substr($subpes_ant,6,2) == "02" && db_month($r30_peri) == 2 && $mtipo == "01" && !db_boolean($cfpess[0]["r11_recalc"]) ){
         $nsaldo = 30;
         $dias_adi = 0;
      }
      
      if ($debug == true) {
        echo "Chamando a função avalia_ponto(12):<br>";
      }
      avalia_ponto(12);
      if ($debug == true) {
        echo "FIM da chamada da função avalia_ponto(12)<br><br>";
      }

      if ($debug == true) {
        echo "Chamando a função limpaAjustesIrrfPreviden():<br>";
      }
      limpaAjustesIrrfPreviden();
      if ($debug == true) {
        echo "FIM da chamada da função limpaAjustesIrrfPreviden()<br><br>";
      }  
      
      if ($debug == true) {
        echo "Chamando a função limpsal({$ponto}):<br>";
      }
      limpsal($ponto);
      if ($debug == true) {
        echo "FIM da chamada da função limpsal({$ponto})<br><br>";
      }  
      
      if ($debug == true) {
        echo "<b>Chamando a Função gera_ponto_salario('n'): </b><br>";
      }
      gera_ponto_salario("n");
      $subpes = $subpes_ant;
      
      if ($debug == true) {
        echo "<br><b>Ponto de salário:</b>";
        db_criatabela(db_query("select * from pontofs where r10_regist = 10366 and r10_anousu = 2011 and r10_mesusu = 12"));
        echo "<br><b>FIM da chamada da função gera_ponto_salario('n')</b> <br><br>";
      }  
      
      $r30_perai = $sDataPeriodoInicial;
      $r30_peraf = $sDataPeriodoFinal;
      
      if ($debug == true) {
        echo "Chamando a Função: grava_cadferia()<br>";
      }

      grava_cadferia();
      $subpes = $subpes_pagto;
      if ($debug == true) {
        echo "FIM da chamada da função grava_cadferia()<br><br>";
      }

   }


global $cfpess,$subpes,$d08_carnes,$db_debug;

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("pes4_avalferia001.php"));
db_postmemory($HTTP_POST_VARS);
db_inicio_transacao();

$matric = $r30_regist;

$subpes = db_anofolha().'/'.db_mesfolha();
 
global $db_config;
db_selectmax("db_config","select lower(trim(munic)) as d08_carnes , cgc from db_config where codigo = ".db_getsession("DB_instit"));

if(trim($db_config[0]["cgc"]) == "90940172000138"){
  $d08_carnes = "daeb";
}else{
  $d08_carnes = $db_config[0]["d08_carnes"];
}

if(isset($r30_per1i) && $r30_per1i != ''){
  $r30_peri = $r30_per1i;
}

if(isset($r30_per1f) && $r30_per1f != ''){
  $r30_perf = $r30_per1f;
}

if(isset($r30_per2i) && $r30_per2i != ''){
  $r30_peri = $r30_per2i;
}

if(isset($r30_per2f) && $r30_per2f != ''){
  $r30_perf = $r30_per2f;
}

cadastro_162();

if ($debug == true) {
  db_fim_transacao(true);
  
  echo "Fim do processamento do cadastro de férias! <br><br>";
  echo "<input type=\"button\" value=\"Retornar\" onclick=\"location='pes4_cadferia001.php'\">";
  exit;
  
} else {
  db_fim_transacao();
}

$qry = "";
if(isset($retorno) && trim($retorno) != "") {
  
  $qry  = "?perini_ano=$perini_ano&perini_mes=$perini_mes&perini_dia=$perini_dia";
  $qry .= "&perfim_ano=$perfim_ano&perfim_mes=$perfim_mes&perfim_dia=$perfim_dia";
  $qry .= "&r30_periodolivreinicial_dia=$r30_periodolivreinicial_dia&r30_periodolivreinicial_mes=$r30_periodolivreinicial_mes";
  $qry .= "&r30_periodolivreinicial_ano=$r30_periodolivreinicial_ano";
  $qry .= "&r30_periodolivrefinal_dia=$r30_periodolivrefinal_dia&r30_periodolivrefinal_mes=$r30_periodolivrefinal_mes";
  $qry .= "&r30_periodolivrefinal_ano=$r30_periodolivrefinal_ano&r30_tipoapuracaomedia=$r30_tipoapuracaomedia";
  $qry .= "&tipofer=$tipofer&pontofer=$pontofer&pagafer13=$pagafer13&ultmatric=$r30_regist";
  $qry .= "&r44_selec=$r44_selec&preanopagto=$preanopagto&premespagto=$premespagto";
  $qry .= "&retorno=true";
  db_redireciona("pes4_cadferia004.php".$qry);
}else{
  db_redireciona("pes4_cadferia001.php".$qry);
}
?>
