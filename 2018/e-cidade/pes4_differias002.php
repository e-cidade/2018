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

require(modification("libs/db_stdlib.php"));
require(modification("libs/db_conecta.php"));
include(modification("libs/db_sessoes.php"));
include(modification("libs/db_usuariosonline.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("dbforms/db_funcoes.php"));
include(modification("pes4_avalferia001.php"));


function diferencaferias($subpes)
{
  
  global $m_rubr, $rubricas , $d08_carnes, $db21_codcli,$vtipo, $qmesesavalia,$pontofx,$nsaldo,$dias_adi,$nabono,
  $m_quant, $nsaldo_anterior ,$m_media,$m_quant, $saldo,$m_valor,$qten, $vlrn,$matric,
  $pessoal,$Ipessoal, $subpes, $max,$qmeses,$mpsal,$nsalar,$max;
  
  global $cadferia,$max_total,$matric,$cfpess,$r110_regisf,$r110_regisi,$subpes,$subpes_ant,$indano,$indmes;
  
  global $opcao_gml,$condicao,$reg,$opcao_filtro,$campo_auxilio_rubr,$faixa_regis,$subpes,$subpes_ofi;
  
  // pergunta se calcula
  
  if ($opcao_gml == "g") {
    $condicao_selecao = " ";
  } else if ($opcao_gml == "m") {
    if ($opcao_filtro  == "i") {
      $condicao_selecao = " and r30_regist >= ".db_sqlformat($r110_regisi ) ;
      $condicao_selecao .= " and r30_regist <= ".db_sqlformat($r110_regisf) ;
    } else {
      $condicao_selecao = " and r30_regist in (".$faixa_regis.")" ;
    }
    
  }
  
  $subpes_ofi = $subpes ;
//  echo "<BR> condicao_selecao -> $condicao_selecao";
  for ($Icontador=1; $Icontador<=2; $Icontador++) {
//    echo "<BR> passou aqui !! Icontador --> $Icontador";
    if ($Icontador == 1 ) {
//    echo "<BR>  1 passou aqui !! subpes --> $subpes";
      $chave_seek2 = db_strtran($subpes,"/","");
      $mes_seek = db_val(db_substr($subpes,-2))-1;
      if ($mes_seek < 1) {
        $mes_seek = "12";
        $ano_seek = db_str((db_val(db_substr($subpes,1,4))-1),4,0);
      } else {
        $mes_seek = db_str($mes_seek,2,0,"0");
        $ano_seek = db_substr($subpes,1,4);
      }
      $inicio_mes_seek = db_ctod("01/".$mes_seek."/".$ano_seek );
      $fim_mes_seek = db_ctod(db_str(ndias($mes_seek."/".$ano_seek),2,0,"0")."/".$mes_seek."/".$ano_seek );
      $condicaoaux  = " and (( r30_per1i >= ".db_sqlformat($inicio_mes_seek );
      $condicaoaux .= "   and r30_per1f >= ".db_sqlformat($fim_mes_seek );
      $condicaoaux .= " ) or ( r30_per2i >= ".db_sqlformat($inicio_mes_seek );
      $condicaoaux .= " and r30_per2f >= ".db_sqlformat($fim_mes_seek )."))";
      db_selectmax("cadferia", "select * from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux .$condicao_selecao );
//      echo "<BR> select * from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux .$condicao_selecao;
//      db_criatabela(db_query("select * from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux .$condicao_selecao));
      $max_total = count($cadferia);
    } else {
      $condicaoaux  = " and ( r30_proc1 = ".db_sqlformat($subpes ). " or r30_proc2 = ".db_sqlformat($subpes ).")";
      db_selectmax("cadferia", "select * from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux .$condicao_selecao);
//      echo "<BR> select * from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux .$condicao_selecao;
//      db_criatabela(db_query("select * from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux .$condicao_selecao));
      $max_total = count($cadferia);
      $chave_seek2 = "999999";
    }
    $matriz1 = array();
    $matriz2 = array();
    $matriz1[1] = "r29_regist";
    $matriz1[2] = "r29_rubric";
    $matriz1[3] = "r29_valor";
    $matriz1[4] = "r29_quant";
    $matriz1[5] = "r29_lotac";
    $matriz1[6] = "r29_media";
    $matriz1[7] = "r29_calc";
    $matriz1[8] = "r29_tpp";
    $matriz1[9] = "r29_anousu";
    $matriz1[10] = "r29_mesusu";
    $matriz1[11] = "r29_instit";
    $contador = 1;
    for ($Icadferia=0; $Icadferia<count($cadferia); $Icadferia++) {
      if (db_empty($cadferia[$Icadferia]["r30_proc2"]) ) {
        $r30_dias = "r30_dias1";
        $r30_proc = "r30_proc1";
        $r30_peri = "r30_per1i";
        $r30_perf = "r30_per1f";
        $r30_vfgt = "r30_vfgt1";
        $r30_vliq = "r30_vliq1";
      } else {
        $r30_dias = "r30_dias2";
        $r30_proc = "r30_proc2";
        $r30_peri = "r30_per2i";
        $r30_perf = "r30_per2f";
        $r30_vfgt = "r30_vfgt1";
        $r30_vliq = "r30_vliq2";
      }
      db_atutermometro($Icadferia,count($cadferia),'calculo_folha',1);
      //echo "<BR> r30_regist --> ".$cadferia[$Icadferia]["r30_regist"]. " r30_proc1 --> ".$cadferia[$Icadferia][$r30_proc];
      $matric = $cadferia[$Icadferia]["r30_regist"];
      if( !db_boolean($cfpess[0]["r11_recalc"]) 
      && $cadferia[$Icadferia]["r30_proc1"] != $subpes
      && $cadferia[$Icadferia]["r30_proc2"] != $subpes ) {
        //echo "<BR> r30_paga13 --> ".$cadferia[$Icadferia]["r30_paga13"] . " r30_proc1 --> " .$cadferia[$Icadferia]["r30_proc1"] . " r30_proc2 --> ".$cadferia[$Icadferia]["r30_proc1"];
        continue;
      }
      if (db_month($cadferia[$Icadferia]["r30_per1f"]) == db_val(db_substr($subpes,-2))
      && db_year($cadferia[$Icadferia]["r30_per1f"]) == db_val(db_substr($subpes,1,4))
      && (strtolower($cfpess[0]["r11_fersal"]) == 's' && !db_boolean($cfpess[0]["r11_recalc"]) ) ) {
        //echo "<BR> passou aqui 2 !!!";
        continue;
      }
//      $condicaoaux = " and r01_regist = ".db_sqlformat($cadferia[$Icadferia]["r30_regist"] );
      global $pessoal;
//      db_selectmax("pessoal", "select * from pessoal ".bb_condicaosubpes("r01_" ).$condicaoaux );
      
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

      $condicaoaux = " and rh01_regist = ".db_sqlformat($cadferia[$Icadferia]["r30_regist"] );
//      echo "<BR> condicaoaux --> $condicaoaux";
      db_selectmax("pessoal", "select ".$campos_pessoal." from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                               and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
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

      $Ipessoal = 0;
      if(count($pessoal) == 0 || $cadferia[$Icadferia][$r30_proc] < $cfpess[0]["r11_altfer"]) {
//        echo "<BR> passou aqui 3 !!!";
        continue;
      }
      if (( db_substr(db_dtos($cadferia[$Icadferia][$r30_peri]),1,6) > $chave_seek2 ||
      db_substr(db_dtos($cadferia[$Icadferia][$r30_perf]),1,6) < $chave_seek2  ) ) {
//        echo "<BR> passou aqui 4 !!!";
        continue;
      }
      $condicaoaux = " and r29_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      
//    echo "<BR> condicaoaux --> $condicaoaux";
      
      db_delete("pontofe", bb_condicaosubpes("r29_" ).$condicaoaux );
      $m_rubr = array();
      $m_quant= array();
      $m_valor= array();
      $m_media= array();
      $m_tipo = array();
      $qten   = array();
      $vlrn   = array();
      $nsaldo = $cadferia[$Icadferia][$r30_dias];
      //$nsaldo = $r30_ndias - ($r30_dias1 + $r30_dias2 + $r30_abono);
      $nabono = $cadferia[$Icadferia]["r30_abono"];
      $nsaldo_anterior = $nsaldo;
      $dias_adi = 0;
      //echo "<BR> 1.1 nsaldo --> $nsaldo nabono --> $nabono";
      //echo "<BR> r30_peri --> ".$cadferia[$Icadferia][$r30_peri];
      //echo "<BR> r30_perf --> ".$cadferia[$Icadferia][$r30_perf];
      //echo "<BR> subpes    --> $subpes";
      //echo "<BR> r30_dias1 --> ".$cadferia[$Icadferia]["r30_dias1"];
      //echo "<BR> r30_dias2 --> ".$cadferia[$Icadferia]["r30_dias2"];
      
      if (db_month($cadferia[$Icadferia][$r30_peri]) == db_val(db_substr($subpes,-2))
      && db_year($cadferia[$Icadferia][$r30_peri]) == db_val(db_substr($subpes,1,4)) ) {
        //echo "<BR> 0.0 passou aqui";
        if (( db_month($cadferia[$Icadferia][$r30_perf]) > db_val(db_substr($subpes,-2))
        && db_year($cadferia[$Icadferia][$r30_perf]) >= db_val(db_substr($subpes,1,4))
        )
        ||
        ( ( db_month($cadferia[$Icadferia][$r30_perf]) > db_month($cadferia[$Icadferia][$r30_peri])
        && db_year($cadferia[$Icadferia][$r30_peri]) == db_year($cadferia[$Icadferia][$r30_perf])
        )
        || db_year($cadferia[$Icadferia][$r30_perf]) > db_year($cadferia[$Icadferia][$r30_peri] )
        )
        ) {
          if (db_year($cadferia[$Icadferia][$r30_peri]%4) != 0
          && db_day($cadferia[$Icadferia][$r30_peri]) == 31
          && db_month($cadferia[$Icadferia][$r30_peri]) == 1
          && db_month($cadferia[$Icadferia][$r30_perf]) == 3 ) {
            $nsaldo = 1;
            $dias_adi = 29;
            //echo "<BR> 1.2 nsaldo --> $nsaldo nabono --> $nabono dias_adi --> $dias_adi";
          } else if (db_month($cadferia[$Icadferia][$r30_peri]) == 2  && db_month($cadferia[$Icadferia][$r30_perf]) == 3) {
            $nsaldo = (ndias(db_substr($subpes,6,2)."/".db_substr($subpes,1,4) ) ) 
                      - db_day($cadferia[$Icadferia][$r30_peri] ) +1 ;
            $dias_adi = 30 - $nsaldo - $nabono;
            //echo "<BR> 1.3 nsaldo --> $nsaldo nabono --> $nabono dias_adi --> $dias_adi";
          } else {
            if (db_month($cadferia[$Icadferia][$r30_peri]) == db_val(db_substr($subpes,6,2))) {
              $nsaldo = (ndias(db_substr($subpes,6,2)."/".db_substr($subpes,1,4) ) ) 
              - db_day($cadferia[$Icadferia][$r30_peri] ) +1 ;
              $dias_adi = db_day($cadferia[$Icadferia][$r30_perf] );
              //echo "<BR> 1.4 nsaldo --> $nsaldo nabono --> $nabono dias_adi --> $dias_adi";
            }
          }
        }
      } else if (db_month($cadferia[$Icadferia][$r30_perf]) == db_val(db_substr($subpes,-2))
      && db_year($cadferia[$Icadferia][$r30_perf]) == db_val(db_substr($subpes,1,4)) ) {
        
        // quando data do periodo aquisitivo final for igual ao que mes da folha em exercicio
        
        $nsaldo = db_day($cadferia[$Icadferia][$r30_perf] );
        $nabono = 0;
        //echo "<BR> 1.5 nsaldo --> $nsaldo nabono --> $nabono dias_adi --> $dias_adi";
      } else if (( ( db_year($cadferia[$Icadferia][$r30_peri]) < db_val(db_substr($subpes,1,4))
      || ( db_month($cadferia[$Icadferia][$r30_peri]) < db_val(db_substr($subpes,-2))
      && db_year($cadferia[$Icadferia][$r30_peri]) == db_val(db_substr($subpes,1,4)) )) )
      &&
      ( ( db_year($cadferia[$Icadferia][$r30_perf]) > db_val(db_substr($subpes,1,4))
      || ( db_month($cadferia[$Icadferia][$r30_perf]) > db_val(db_substr($subpes,-2))
      && db_year($cadferia[$Icadferia][$r30_perf]) == db_val(db_substr($subpes,1,4)) )) )
      && $cadferia[$Icadferia][$r30_proc] < $subpes ) {
        
        // quando data do periodo aquisitivo inicial for menor que mes da folha em exercicio
        
        $nsaldo = ndias(db_substr($subpes,6,2)."/".db_substr($subpes,1,4));
        $dias_adi = 0;
        $nabono = 0;
        //echo "<BR> 1.6 nsaldo --> $nsaldo nabono --> $nabono dias_adi --> $dias_adi";
      } else {
        $dias_adi = $nsaldo;
        $nsaldo = 0;
        //echo "<BR> 1.7 nsaldo --> $nsaldo nabono --> $nabono dias_adi --> $dias_adi";
      }

      $mpsal = false;
      $nsalar = 0;
      $mdabo = false;
      $max = 0;
      //echo "<BR> r30_perai --> ".$cadferia[$Icadferia]["r30_perai"];
      $indano = db_year($cadferia[$Icadferia]["r30_perai"]);
      $indmes = db_month($cadferia[$Icadferia]["r30_perai"]);
      
      $mes_ano = db_str(db_month($cadferia[$Icadferia]["r30_perai"] ) ,2,0,"0") . "/" . db_str(db_year($cadferia[$Icadferia]["r30_perai"] ),4);
      if ((( ndias($mes_ano ) - db_day($cadferia[$Icadferia]["r30_perai"]) ) < 15 ) ||
      ($indmes == 2 && db_day($cadferia[$Icadferia]["r30_perai"]) == 15 ) ) {
        if ($indmes == 2 && db_day($cadferia[$Icadferia]["r30_perai"]) == 15 ) {
          $indmes--;
        }
        $indmes++;
        if ($indmes > 12) {
          $indmes = 01;
          $indano++;
        }
      }
      if ( $db21_codcli == "12") {
        $indmes = db_val(db_substr($subpes, 6, 2) );
        $indano = db_val(db_substr($subpes, 1, 4) );
        for ($i=1; $i<=12; $i++) {
          $indmes--;
          if ($indmes < 1) {
            $indano --;
            $indmes = 12;
          }
        }
      }
      $subpes_ant = $subpes;
//      if( db_month($cadferia[$Icadferia][$r30_peri]) == 2 && $cadferia[$Icadferia]["r30_tip1"] == "01" && !db_boolean($cfpess[0]["r11_recalc"]) ){
//        $nsalar = 30;
//      }
//echo "<BR> 1.1 passou aqui! subpes_ant --> $subpes_ant subpes --> $subpes";
      levanta_ponto(); 
//echo "<BR> 2 passou aqui!";
      $subant = $subpes ;
      $subpes = $subpes_ant;
      
//echo "<BR> 3 passou aqui!";
      acrescentapontofx();

      $tot_retornar = 0;
      if( $db21_codcli == "18" && $pessoal[0]["r01_regime"] != 2 ){
         horasextrascarazinho();
      }
      if( $db21_codcli == "12" ){
         horasextrasguaiba();
         acrescentapontofx();
      }
     if( db_substr($subpes_ant,6,2) == "02" &&  db_month($cadferia[$Icadferia][$r30_peri]) == 2 && $cadferia[$Icadferia]["r30_tip1"] == "01"
       && !db_boolean($cfpess[0]["r11_recalc"]) ){
      $nsaldo = 30;
      $dias_adi = 0;
     }

      avalia_ponto(12);

      gera_ponto_salario("s");
      $matriz1 = array();
      $matriz2 = array();
      $matriz1[1] = "r29_regist";
      $matriz1[2] = "r29_rubric";
      $matriz1[3] = "r29_valor";
      $matriz1[4] = "r29_quant";
      $matriz1[5] = "r29_lotac";
      $matriz1[6] = "r29_media";
      $matriz1[7] = "r29_calc";
      $matriz1[8] = "r29_tpp";
      $matriz1[9] = "r29_anousu";
      $matriz1[10] = "r29_mesusu";
      $matriz1[11] = "r29_instit";
      if (!db_empty($cadferia[$Icadferia][$r30_vfgt]) && $cadferia[$Icadferia][$r30_proc] < $subpes) {
        if (db_substr(db_dtos($cadferia[$Icadferia][$r30_peri]),1,6) > db_strtran($cadferia[$Icadferia][$r30_proc],"/","")
        ||
        ( db_substr(db_dtos($cadferia[$Icadferia][$r30_peri]),1,6) == db_strtran($cadferia[$Icadferia][$r30_proc],"/","")
        && $cadferia[$Icadferia][$r30_proc] < $subpes
        && db_substr(db_dtos($cadferia[$Icadferia][$r30_perf]),1,6) > db_strtran($subpes,"/","")) ) {
          $valor_desc = $cadferia[$Icadferia][$r30_vfgt]/$nsaldo_anterior * $nsaldo ;
        } else {
          $valor_desc = $cadferia[$Icadferia][$r30_vfgt];
        }
        //echo "<BR> 11.11 passou aqui !!";
        $matriz2[1] = $matric;
        $matriz2[2] = $cfpess[0]["r11_ferant"];
        $matriz2[3] = bb_round($valor_desc,2);
        $matriz2[4] = 0;
        $matriz2[5] = $pessoal[$Ipessoal]["r01_lotac"];
        $matriz2[6] = 0;
        $matriz2[7] = 0;
        $matriz2[8] = "F";
        $matriz2[9] = db_val(db_substr($subpes,1,4 ) );
        $matriz2[10] = db_val(db_substr($subpes, -2 ) );
        $matriz2[11] = db_getsession("DB_instit");
        db_insert("pontofe", $matriz1, $matriz2 );
      }
      if (!db_empty($cadferia[$Icadferia][$r30_vliq]) && $cadferia[$Icadferia][$r30_proc] < $subpes) {
        if (db_substr(db_dtos($cadferia[$Icadferia][$r30_peri]),1,6) == db_strtran($subpes,"/","")  ) {
          //echo "<BR> 11.12 passou aqui !!";
          $valor_desc = $cadferia[$Icadferia][$r30_vliq] ;
          $matriz2[1] = $matric;
          $matriz2[2] = $cfpess[0]["r11_feabot"];
          $matriz2[3] = bb_round($valor_desc, 2 );
          $matriz2[4] = 0;
          $matriz2[5] = $pessoal[$Ipessoal]["r01_lotac"];
          $matriz2[6] = 0;
          $matriz2[7] = 0;
          $matriz2[8] = "F";
          $matriz2[9] = db_val(db_substr($subpes,1,4 ) );
          $matriz2[10] = db_val(db_substr($subpes, -2 ) );
          $matriz2[11] = db_getsession("DB_instit");
          db_insert("pontofe", $matriz1, $matriz2 );
        }
      }
      
      $subpes = $subpes_ant;
    }
  }
  
}


function exclusao_167(){
 
   global $pessoal,$Ipessoal,$cadferia,$subpes,$max_total,$matric;
   
   $mes = db_val(db_substr($subpes,6,2));
   $ano = db_val(db_substr($subpes,1,4));
   $mes -= 1;
   if( $mes < 1){
      $mes = 12;
      $ano -= 1 ;
   }
   $mesanterior = db_str( $ano,4,0 ) . "/". db_str( $mes,2,0,"0");
   $condicaoaux =  " and r30_proc1 = ".db_sqlformat( $mesanterior );
   db_selectmax( "cadferia", "select * from cadferia ".bb_condicaosubpes( "r30_" ).$condicaoaux );
   $max_total = count($cadferia);
   $contador = 1;
   for($Icadferia=0;$Icadferia<count($cadferia);$Icadferia++){
      if( db_str(db_year($cadferia[$Icadferia]["r30_per1f"]),4,0) <= db_substr($subpes,1,4)
           && db_str(db_month($cadferia[$Icadferia]["r30_per1f"]),2,0,"0")  < db_substr($subpes,6,2) ){
         continue;
      }
      $matric = $cadferia[$Icadferia]["r30_regist"];
      $condicaoaux = " and r29_regist = ".db_sqlformat( $matric );
      db_delete( "pontofe", bb_condicaosubpes( "r29_" ).$condicaoaux );
   }

}


global $cfpess,$subpes,$d08_carnes,$anousu, $mesusu,$DB_instit, $db21_codcli;

$subpes = db_anofolha().'/'.db_mesfolha();
$anousu = db_anofolha();
$mesusu = db_mesfolha();
$DB_instit = DB_getsession("DB_instit");

db_selectmax("cfpess"," select * from cfpess ".bb_condicaosubpes("r11_")); 

db_inicio_transacao();

global $opcao_gml,$opcao_geral;

global $condicao,$reg,$campo_auxilio_rubr;

db_postmemory($HTTP_POST_VARS);

if(!isset($opcao_filtro)){
  $opcao_filtro = "0";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br><br><br>
<center>
<?
db_criatermometro('calculo_folha','Concluido...','blue',1,'Efetuando Calculo ...');
//db_criatermometro('calc_folha','Concluido...','blue',1,'Processando Pensoes');
?>
</center>

<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
<?

global $db_config;
db_selectmax("db_config","select lower(trim(munic)) as d08_carnes , cgc, db21_codcli from db_config where codigo = ".db_getsession("DB_instit"));

if(trim($db_config[0]["cgc"]) == "90940172000138"){
     $d08_carnes = "daeb";
}else{
     $d08_carnes = $db_config[0]["d08_carnes"];
}

$db21_codcli = $db_config[0]["db21_codcli"];

$db_erro = false;

diferencaferias($subpes);

//echo "<BR> antes do fim db_fim_transacao()";
flush();
db_fim_transacao();
//exit;
//echo "<BR> andre montano reis";
flush();
db_redireciona("pes4_differias001.php");

?>