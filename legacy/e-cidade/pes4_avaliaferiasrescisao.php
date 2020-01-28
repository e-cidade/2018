<?
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


function zeraarray($ind){
  global $m_media,$m_valor,$m_quant,$qten,$vlrn;
  
  $m_media[$ind] = 0;
  $m_valor[$ind] = 0;
  $m_quant[$ind] = 0;
  $qten[$ind] = 0;
  $vlrn[$ind] = 0;
}
function calcula_afastamentos(){
 global $afasta,$pessoal,$subpes,$cfpess;
 global $dias_mes;
 $dias_total = 0;
 $retorno = 0;

$condicaoaux = " and r45_regist =". db_sqlformat( $pessoal[0]["r01_regist"] )." order by r45_regist, r45_dtafas desc"  ;
if( db_selectmax("afasta", "select * from afasta ".bb_condicaosubpes("r45_").$condicaoaux )){

   for($Iafasta=0;$Iafasta<count($afasta);$Iafasta++){
     //echo "<BR> r45_situac --> ".$afasta[$Iafasta]["r45_situac"]." r45_dtreto --> ".$afasta[$Iafasta]["r45_dtreto"];  
      if( db_str($afasta[$Iafasta]["r45_situac"],1) == "3" && $pessoal[0]["r01_tbprev"] != $cfpess[0]["r11_tbprev"] ) {
        //echo "<BR> 1 passou aqui !!";
         continue;
      }
      
      if( !db_empty($afasta[$Iafasta]["r45_dtreto"])){
         if( db_year($afasta[$Iafasta]["r45_dtreto"]) < db_val(db_substr($subpes,1,4)) ){
        //echo "<BR> 2 passou aqui !!";
            break;
         }
      }else{
         $mes = 12;
        //echo "<BR> 2 mes --> $mes";
      }
      if( db_year($afasta[$Iafasta]["r45_dtreto"]) > db_val(db_substr($subpes,1,4))){
         $mes = 12;
        //echo "<BR> 3 mes --> $mes";
      }else{
         if( !db_empty($afasta[$Iafasta]["r45_dtreto"])){
            $mes = db_month($afasta[$Iafasta]["r45_dtreto"]);
        //echo "<BR> 4 mes --> $mes";
         }
      }
      if( db_val(db_substr($subpes,-2)) < $mes ){
         $mes = db_val(db_substr($subpes,-2));
        //echo "<BR> 5 mes --> $mes";
         $mes++;
        //echo "<BR> 6 mes --> $mes";
         $ano = (db_empty(db_year($afasta[$Iafasta]["r45_dtreto"]))?db_val(db_substr($subpes,1,4)):db_year($afasta[$Iafasta]["r45_dtreto"]));
        //echo "<BR> 7 ano --> $ano";
         if( $mes > 12){
            $mes = 1;
        //echo "<BR> 8 mes --> $mes";
            $ano++;
        //echo "<BR> 9 ano --> $ano";
         }

         // ultimo dia do mes anterior 
         $dias_mes = db_day(date("Y-m-d",db_mktime(db_ctod("01/".db_str($mes,2,0,"0")."/".db_str($ano,4,0,"0"))) - 86400));
         $mes--;
        //echo "<BR>10 mes --> $mes";

         $diasmes = ndias( db_str($mes,2,0,"0")."/".db_str($ano,4,0,"0") );
        //echo "<BR>11 diasmes --> $diasmes";

      }else{
         $ano = ( (db_empty($afasta[$Iafasta]["r45_dtreto"]) || db_year($afasta[$Iafasta]["r45_dtreto"]) > db_val( db_substr($subpes,1,4)) ) ?db_val(db_substr($subpes,1,4))
                   :db_year($afasta[$Iafasta]["r45_dtreto"]));
         $datafinal = ( (db_empty($afasta[$Iafasta]["r45_dtreto"]) || db_year($afasta[$Iafasta]["r45_dtreto"]) > db_val( db_substr($subpes,1,4)) ) 
                   ?db_ctod( "31/12/".db_substr($subpes,1,4) ):$afasta[$Iafasta]["r45_dtreto"]);

         // retorna o nr de dias trabalhados no mes????
         $dias_mes = db_datedif($datafinal,db_ctod("01/".db_str($mes,2,0,"0")."/".db_str($ano,4,0,"0")));
//echo "<BR>  $dias_mes = db_datedif($datafinal,".db_ctod("01/".db_str($mes,2,0,"0")."/".db_str($ano,4,0,"0")).")";
         $diasmes = ndias(db_str(db_month($datafinal),2,0,"0")."/".db_str(db_year($datafinal),4,0,"0") );
        //echo "<BR>12 diasmes --> $diasmes";

      }
      
      $metade_trabalhado = bcdiv($diasmes,2,0);
      //echo "<BR> 13 metade_trabalhado --> $metade_trabalhado";

      $dias_total += $dias_mes;
      //echo "<BR> 14 dias_total --> $dias_total";
    //echo "<BR> 15 if( $diasmes - $dias_mes < $metade_trabalhado){";
      if( $diasmes - $dias_mes < $metade_trabalhado){
         $retorno += 1;
      //echo "<BR> 16 retorno --> $retorno";
      }
      
      if( db_month($afasta[$Iafasta]["r45_dtafas"]) == $mes && db_year($afasta[$Iafasta]["r45_dtafas"]) == $ano){
//echo "<BR> if( ".db_month($afasta[$Iafasta]["r45_dtafas"])." == $mes && ".db_year($afasta[$Iafasta]["r45_dtafas"])." == $ano){";
   //echo "<BR> 17 passou aqui !!";      
         if( $diasmes - $dias_mes < $metade_trabalhado ){
    //echo "<BR> 18  if( $diasmes - $dias_mes < $metade_trabalhado){";
    //echo "<BR> 18.1 $dias_mes = $dias_mes - ( ".db_day($afasta[$Iafasta]["r45_dtafas"])." - 1 )";
            $dias_mes = $dias_mes - ( db_day($afasta[$Iafasta]["r45_dtafas"]) - 1 );
    //echo "<BR> 19 dias_mes --> $dias_mes";
            
    //echo "<BR> 19.1 if( $diasmes - $dias_mes >= $metade_trabalhado){";
            if( $diasmes - $dias_mes >= $metade_trabalhado){
               $retorno--;
      //echo "<BR> 20  retorno --> $retorno";
            }
            $dias_total -= $dias_mes;
      //echo "<BR> 21 dias_total -> $dias_total";
         }
      }else{
         $mes--;
      //echo "<BR> 22 mes -> $mes";
         
         for($Imes=$mes; $Imes >= 1;$Imes--){
      //echo "<BR> 23 Imes -> $Imes";
            if( db_mktime($afasta[$Iafasta]["r45_dtafas"])  < db_mktime(db_ctod("01/".db_str($Imes,2,0,"0")."/".db_substr($subpes,1,4))) ){
               $retorno += 1;
      //echo "<BR> 24 retorno --> $retorno";
            }else{
               $dias_mes = db_datedif(db_ctod("01/".db_str($Imes,2,0,"0")."/".db_str($ano,4)),$afasta[$Iafasta]["r45_dtafas"]) - 1;
               $dias_total += $dias_mes;
      //echo "<BR> 25 dias_total -> $dias_total";
               
    //echo "<BR> 26  if( $diasmes - $dias_mes < $metade_trabalhado){";
               if( $diasmes - $dias_mes < $metade_trabalhado){
                  $retorno += 1;
      //echo "<BR> 27  retorno --> $retorno";
               }
               break;
            }
         }
      }
   }

   if( $retorno < 0){
       $retorno = 0;
   }

 }
 //echo "<BR> 50 retorno --> $retorno";
  return $retorno;
}
function gera_13_salario($datafim,$sigla="r19" )
{
  
  global $d08_carnes, $db21_codcli, $rubricas, $pontofx, $matriz1, $matriz2, $subpes,$pessoal,$cfpess;
  
  global $m_rubr, $m_tipo, $m_media , $m_valor , $m_quant, $qten , $vlrn, $matric,$subpes_original, $ns13;
  
  global $datainicio, $datafim, $max, $gerfsal, $gerffer, $gerfcom, $pontofx, $rescisao,$lotacaoatual;
  if($sigla=="r92"){  
     $matriz1[1] = "r92_regist";
     $matriz1[2] = "r92_rubric";
     $matriz1[3] = "r92_valor";
     $matriz1[4] = "r92_quant";
     $matriz1[5] = "r92_lotac";
     $matriz1[6] = "r92_calc";
     $matriz1[7] = "r92_anousu";
     $matriz1[8] = "r92_mesusu";
     $matriz1[9] = "r92_instit";
  }else{
     $matriz1[1] = "r19_regist";
     $matriz1[2] = "r19_rubric";
     $matriz1[3] = "r19_valor";
     $matriz1[4] = "r19_quant";
     $matriz1[5] = "r19_lotac";
     $matriz1[6] = "r19_tpp";
     $matriz1[7] = "r19_anousu";
     $matriz1[8] = "r19_mesusu";
     $matriz1[9] = "r19_instit";
  }
  $meses_afastado = calcula_afastamentos();
  //echo "<BR> meses_afastado --> $meses_afastado";
  $meses_admissao = 0;
  if (db_year($pessoal[0]["r01_admiss"] ) == db_val(db_substr($subpes,1,4))) {
    $meses_admissao = db_month($pessoal[0]["r01_admiss"]) - 1;
    $mesano = db_substr(db_dtoc($pessoal[0]["r01_admiss"]),4,7);
    if (( ndias($mesano ) - db_day($pessoal[0]["r01_admiss"])) < 15 ) {
      $meses_admissao += 1 ;
    }
  }
  $nm13 = db_month($datafim);
  //echo bcdiv(ndias(db_str(db_month($datafim),2,0,"0")."/".db_str(db_year($datafim),4,0,"0")),2,0);
  //echo "<BR><BR>".ndias(db_str(db_month($datafim),2,0,"0")."/".db_str(db_year($datafim),4,0,"0")).'  --- ndias(db_str(db_month('.$datafim.'),2,0,"0")."/".db_str(db_year('.$datafim.'),4,0,"0")) ';exit;

  if (db_day($datafim ) < bcdiv(ndias(db_str(db_month($datafim),2,0,"0")."/".db_str(db_year($datafim),4,0,"0")),2,0)) {
    $nm13-=1;
    if ($nm13 < 0) {
      $mn13 = 0;
    }
  }
  $ultimo_mes = $nm13;
  $nm13 -= $meses_admissao;
  //echo "<BR> ultimo_mes --> $ultimo_mes";
  //echo "<BR> nm13 --> $nm13";

  /**
   * Avos de 13 salario do exercicio da rescisao
   */   
  global $iAvos13Salario;
  $iAvos13Salario = $nm13;
  
  if ($nm13 != 0) {
    $imax   = 0;
    $sal13 = 0;
      
    $m_rubric = array();
    $m_quant  = array();
    $m_valor  = array();
    $m_cont   = array();
    $qten     = array();
    $vlrn     = array();
    
    
    $pensao_alim_13 = 0;
    $ns13 = 30 / 12 * $nm13 ;
    //echo "<BR> ns13 --> $ns13";
    $ano = db_substr($subpes,1,4 );
    //echo "<BR> ano --> $ano";
    $rubrica_13 = "R934";
    $inicio_he_carazi = $nm13 - 1 - 6;
    if ($inicio_he_carazi < 1) {
      $inicio_he_carazi = 1;
    }
    for ($cont=($meses_admissao+1); $cont <= $ultimo_mes; $cont++) {
      $mes  = db_str($cont,2,0,"0");
      $subpes = $ano."/".$mes;
      $tem_no_mes_tipo_9 = false;
      $maxmes   = 0;
      
      $mes_rubric = array();
      $mes_quant  = array();
      $mes_valor  = array();
      $qten_mes   = array();
      $vlrn_mes   = array();
      
      $indmes = 0;
      $tem_no_mes_tipo9 = false;
      $funcionario_em_ferias = "n";
      $dias_de_ferias = 0;
      $dias_de_abono = 0;
      $encontrei_arquivo = false;
      $condicaoaux = " and r14_regist = ".db_sqlformat($matric );
      if (db_selectmax("gerfsal", "select * from gerfsal ".bb_condicaosubpes("r14_").$condicaoaux )) {
        for ($Igerfsal=0; $Igerfsal<count($gerfsal); $Igerfsal++) {
          if (db_substr($gerfsal[$Igerfsal]["r14_rubric"],1,1) != "R" && (db_val($gerfsal[$Igerfsal]["r14_rubric"]) > 0 && db_val($gerfsal[$Igerfsal]["r14_rubric"]) < 2000 )) {
            if ( $db21_codcli == "18"
            && $pessoal[0]["r01_regime"] != 2
            && ( db_at($gerfsal[$Igerfsal]["r14_rubric"],"0004-0005") > 0 )
            && $cont < $inicio_he_carazi ) {
              continue;
            }
            if (!db_empty($cfpess[0]["r11_rubdec"])) {
              if ($gerfsal[$Igerfsal]["r14_rubric"] == $cfpess[0]["r11_rubdec"]) {
                $sal13 += $gerfsal[$Igerfsal]["r14_valor"];
              }
            }
            if (!db_empty($cfpess[0]["r11_palime"])) {
              if ($gerfsal[$Igerfsal]["r14_rubric"] == db_str(db_val($cfpess[0]["r11_palime"])+4000,4)) {
                $pensao_alim_13 += $gerfsal[$Igerfsal]["r14_valor"];
              }
            }
            
            if ($gerfsal[$Igerfsal]["r14_rubric"] == $cfpess[0]["r11_ferias"]) {
              $funcionario_em_ferias = "s";
              $dias_de_ferias = $gerfsal[$Igerfsal]["r14_quant"];
            } else if ($gerfsal[$Igerfsal]["r14_rubric"] == $cfpess[0]["r11_ferabo"] ) {
              $dias_de_abono = $gerfsal[$Igerfsal]["r14_quant"];
            }

            $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat($gerfsal[$Igerfsal]["r14_rubric"] );
            if (db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux )
            && $rubricas[0]["rh27_calc2"] != 0 ) {
              
              $indmes = db_ascan($mes_rubric,$gerfsal[$Igerfsal]["r14_rubric"]);
              if (db_empty($indmes)) {
                
                $maxmes += 1;
                $indmes  = $maxmes;
                $qten_mes[$maxmes] = 0;
                $vlrn_mes[$maxmes] = 0;
                $mes_rubric[$maxmes] = $gerfsal[$Igerfsal]["r14_rubric"];
                $mes_valor[$maxmes] = 0;
                $mes_quant[$maxmes] = 0;
              }
              if ($rubricas[0]["rh27_calc2"] ==  9) {
                $tem_no_mes_tipo9 = true;
                $mes_valor[$indmes] = $gerfsal[$Igerfsal]["r14_valor"];
                $mes_quant[$indmes] = $gerfsal[$Igerfsal]["r14_quant"];
              } else {
                $mes_valor[$indmes] += $gerfsal[$Igerfsal]["r14_valor"];
                $mes_quant[$indmes] += $gerfsal[$Igerfsal]["r14_quant"];
                if (db_at(db_str($rubricas[0]["rh27_calc2"],1),"1-2-7") > 0 ) {
                  $vlrn_mes[$indmes] = $gerfsal[$Igerfsal]["r14_valor"];
                  $qten_mes[$indmes] = $gerfsal[$Igerfsal]["r14_quant"];
                }
              }
              $encontrei_arquivo = true;
            }
          }
        }
      }
      // db_debug1(" matriz mes_rubric --->".print_r($mes_rubric));
      $condicaoaux = " and r48_regist = ".db_sqlformat($matric );
      if (db_selectmax("gerfcom", "select * from gerfcom ".bb_condicaosubpes("r48_").$condicaoaux )) {
        for ($Igerfcom=0; $Igerfcom<count($gerfcom); $Igerfcom++) {
          if (db_substr($gerfcom[$Igerfcom]["r48_rubric"],1,1) != "R" && (db_val($gerfcom[$Igerfcom]["r48_rubric"]) > 0 && db_val($gerfcom[$Igerfcom]["r48_rubric"]) < 2000 )) {
            if ( $db21_codcli == "18"  && $pessoal[0]["r01_regime"] != 2
            && ( db_at($gerfcom[$Igerfcom]["r48_rubric"],"0004-0005") > 0 )
            && $cont < $inicio_he_carazi ) {
              continue;
            }
            if (!db_empty($cfpess[0]["r11_rubdec"])) {
              if ($gerfcom[$Igerfcom]["r48_rubric"] == $cfpess[0]["r11_rubdec"]) {
                $sal13 += $gerfcom[$Igerfcom]["r48_valor"];
              }
            }
            
            if ($gerfcom[$Igerfcom]["r48_rubric"] == $cfpess[0]["r11_ferias"]) {
              $funcionario_em_ferias = "c";
              $dias_de_ferias = $gerfcom[$Igerfcom]["r48_quant"];
            } else if ($gerfcom[$Igerfcom]["r48_rubric"] == $cfpess[0]["r11_ferabo"] ) {
              $dias_de_abono = $gerfcom[$Igerfcom]["r48_quant"];
            }

            $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat($gerfcom[$Igerfcom]["r48_rubric"] );
            if (db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux )
            && $rubricas[0]["rh27_calc2"] != 0 ) {
              $indmes = db_ascan($mes_rubric,$gerfcom[$Igerfcom]["r48_rubric"]);
              if (db_empty($indmes)) {
                $maxmes += 1;
                $indmes  = $maxmes;
                $qten_mes[$maxmes]    = 0;
                $vlrn_mes[$maxmes]    = 0;
                
                $mes_rubric[$maxmes] = $gerfcom[$Igerfcom]["r48_rubric"];
                $mes_valor[$maxmes] = 0;
                $mes_quant[$maxmes] = 0;
              }
              if ($rubricas[0]["rh27_calc2"] == 9) {
                if (!$tem_no_mes_tipo9) {
                  $mes_valor[$indmes] = $gerfcom[$Igerfcom]["r48_valor"];
                  $mes_quant[$indmes] = $gerfcom[$Igerfcom]["r48_quant"];
                }
              } else {
                $mes_valor[$indmes] += $gerfcom[$Igerfcom]["r48_valor"];
                $mes_quant[$indmes] += $gerfcom[$Igerfcom]["r48_quant"];
                if (db_at(db_str($rubricas[0]["rh27_calc2"],1),"1-2-7")>0) {
                  $vlrn_mes[$indmes] = $gerfcom[$Igerfcom]["r48_valor"];
                  $qten_mes[$indmes] = $gerfcom[$Igerfcom]["r48_quant"];
                }
              }
            }
          }
        }
      }
      $altfer = db_strtran($cfpess[0]["r11_altfer"], "/", "" );
      $condicaoaux  = " and r31_regist = ".db_sqlformat($matric );
      global $gerffer;
      if (db_selectmax("gerffer", "select * from gerffer ".bb_condicaosubpes("r31_").$condicaoaux ) ) {
        if ($altfer > ($ano.$mes) || db_empty($altfer) ) {
          $funcionario_em_ferias = "a";
        }
      }
      if (strtolower($funcionario_em_ferias) != "n") {
        $condicaoaux  = " and r90_regist = ".db_sqlformat($matric );
        global $pontofx;
        if (db_selectmax("pontofx", "select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux )) {
          for ($Ipontofx=0; $Ipontofx<count($pontofx); $Ipontofx++) {
            if (db_substr($pontofx[$Ipontofx]["r90_rubric"],1,1) != "R" && (db_val($pontofx[$Ipontofx]["r90_rubric"]) > 0
            && db_val($pontofx[$Ipontofx]["r90_rubric"]) < 2000)) {
              $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat($pontofx[$Ipontofx]["r90_rubric"] );
              if (db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux )
              && $rubricas[0]["rh27_calc2"] != 0 ) {
                $indmes = db_ascan($mes_rubric,$pontofx[$Ipontofx]["r90_rubric"]);
                if (db_empty($indmes)) {
                  $maxmes += 1;
                  $indmes  = $maxmes;
                  $qten_mes[$maxmes] = 0;
                  $vlrn_mes[$maxmes] = 0;
                  
                  $mes_rubric[$maxmes] = $pontofx[$Ipontofx]["r90_rubric"];
                  $mes_valor[$maxmes] = 0;
                  $mes_quant[$maxmes] = 0;
                }
                if ($rubricas[0]["rh27_calc2"] == 9) {
                  if (!$tem_no_mes_tipo9) {
                    $mes_valor[$indmes] = $pontofx[$Ipontofx]["r90_valor"];
                    $mes_quant[$indmes] = $pontofx[$Ipontofx]["r90_quant"];
                  }
                } else {
                  if (strtolower($funcionario_em_ferias) == "a") {
                    $mes_valor[$indmes] += ($pontofx[$Ipontofx]["r90_valor"]);
                    $mes_quant[$indmes] += ($pontofx[$Ipontofx]["r90_quant"]);
                  } else {
                    $mes_valor[$indmes] += ($pontofx[$Ipontofx]["r90_valor"]/30*$dias_de_ferias);
                    $mes_quant[$indmes] += ($pontofx[$Ipontofx]["r90_quant"]/30*$dias_de_ferias );
                  }
                  if (db_at(db_str($rubricas[0]["rh27_calc2"],1),"1-2-7")>0 ) {
                    $vlrn_mes[$indmes] = $pontofx[$Ipontofx]["r90_valor"];
                    $qten_mes[$indmes] = $pontofx[$Ipontofx]["r90_quant"];
                  }
                }
                $encontrei_arquivo = true;
              }
            }
          }
        }
      }
      
      
      if (!$encontrei_arquivo && $subpes == $subpes_original) {
        $condicaoaux = " and r19_regist = ".db_sqlformat($matric );
        global $pontofr;
        if (db_selectmax("pontofr", "select * from pontofr ".bb_condicaosubpes("r19_").$condicaoaux )) {
          for ($Ipontofr=0; $Ipontofr<count($pontofr); $Ipontofr++) {
            if (db_substr($pontofr[$Ipontofr]["r19_rubric"],1,1) != "R" && (( db_val($pontofr[$Ipontofr]["r19_rubric"]) > 0  && db_val($pontofr[$Ipontofr]["r19_rubric"]) < 2000 )
            ||  ( db_val($pontofr[$Ipontofr]["r19_rubric"]) > 6000 && db_val($pontofr[$Ipontofr]["r19_rubric"]) < 8000 ))) {
              if (db_val($pontofr[$Ipontofr]["r19_rubric"]) > 6000 ) {
                $rubrica_ponto = db_str((db_val($pontofr[$Ipontofr]["r19_rubric"]) - 6000), 4, 0, "0" );
              } else {
                $rubrica_ponto = $pontofr[$Ipontofr]["r19_rubric"];
              }
              $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat($rubrica_ponto );
              if (db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux )
              && $rubricas[0]["rh27_calc2"] != 0 ) {
                $indmes = db_ascan($mes_rubric,$rubrica_ponto);
                if (db_empty($indmes)) {
                  $maxmes += 1;
                  $indmes  = $maxmes;
                  $qten_mes[$maxmes] = 0;
                  $vlrn_mes[$maxmes] = 0;
                  
                  $mes_rubric[$maxmes] = $rubrica_ponto;
                  $mes_valor[$maxmes] = 0;
                  $mes_quant[$maxmes] = 0;
                }
                if ($rubricas[0]["rh27_calc2"] == 9) {
                  if (!$tem_no_mes_tipo9) {
                    $mes_valor[$indmes] = $pontofr[$Ipontofr]["r19_valor"];
                    $mes_quant[$indmes] = $pontofr[$Ipontofr]["r19_quant"];
                  }
                } else {
                  $mes_valor[$indmes] += $pontofr[$Ipontofr]["r19_valor"];
                  $mes_quant[$indmes] += $pontofr[$Ipontofr]["r19_quant"];
                  if (db_at(db_str($rubricas[0]["rh27_calc2"],1),"1-2-7")>0) {
                    $vlrn_mes[$indmes] = $pontofr[$Ipontofr]["r19_valor"];
                    $qten_mes[$indmes] = $pontofr[$Ipontofr]["r19_quant"];
                  }
                }
              }
            }
          }
        }
      }

      for ($i=1; $i <= $maxmes; $i++) {
        $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat($mes_rubric[$i] );
        db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux ) ;
        $iind = db_ascan($m_rubric, $mes_rubric[$i] );
        if (db_empty($iind)) {
          $imax += 1;
          $iind  = $imax;
          $m_rubric[$imax] = $mes_rubric[$i];
          $m_valor[$imax] = 0;
          $m_quant[$imax] = 0;
          $m_cont[$imax]  = 0;
          $qten[$imax]    = 0;
          $vlrn[$imax]    = 0;
        }
        if ($rubricas[0]["rh27_calc2"] == 9) {
          $m_cont[$iind]  += 1;
          $m_valor[$iind] = $mes_valor[$i];
          $m_quant[$iind] = $mes_quant[$i];
        } else if (( db_at(db_str($rubricas[0]["rh27_calc2"],1),"3-4-7")==0
        || ($mes_quant[$i] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))) ) {
          
          if (($mes_quant[$i] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) /2))) {
            $m_cont[$iind]  += 1;
          }
          if (db_at(db_str($rubricas[0]["rh27_calc2"],1),"3-4-7")>0) {
            $quant_restomenos = ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
            if (!db_empty($quant_restomenos)) {
              $quant_resto = $mes_quant[$i] - ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
              while ($quant_resto > 0) {
                if ($quant_resto >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) /2)) {
                  $m_cont[$iind] += 1;
                  $quant_resto = $quant_resto - ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
                } else {
                  break;
                }
              }
            } else {
              $m_cont[$iind] += 1;
            }
          }
          $m_valor[$iind] += $mes_valor[$i];
          $m_quant[$iind] += $mes_quant[$i];
          if (db_at(db_str($rubricas[0]["rh27_calc2"],1),"1-2-7")>0) {
            $vlrn[$iind] = $vlrn_mes[$i];
            $qten[$iind] = $qten_mes[$i];
          }
        }
      }
      $condicaoaux = " and r35_regist = ".db_sqlformat($matric );
      global $gerfs13;
      if (db_selectmax("gerfs13", "select * from gerfs13 ".bb_condicaosubpes("r35_").$condicaoaux )
      && ( $mes != db_substr($subpes,6,2) || ( $mes == db_substr($subpes,6,2) ) ) ) {
        for ($Igerfs13=0; $Igerfs13<count($gerfs13); $Igerfs13++) {
          if (db_substr($gerfs13[$Igerfs13]["r35_rubric"],1,1) != "R" && (db_val($gerfs13[$Igerfs13]["r35_rubric"]) > 0 && db_val($gerfs13[$Igerfs13]["r35_rubric"]) > 4000
          && db_val($gerfs13[$Igerfs13]["r35_rubric"]) < 6000 )) {
            if ($gerfs13[$Igerfs13]["r35_pd"] == 1) {
              $sal13 += $gerfs13[$Igerfs13]["r35_valor"];
            } else if ($gerfs13[$Igerfs13]["r35_pd"] == 2) {
              $sal13 -= $gerfs13[$Igerfs13]["r35_valor"];
            }
            if (!db_empty($cfpess[0]["r11_palime"])) {
              if ($gerfs13[$Igerfs13]["r35_rubric"] == db_str(db_val($cfpess[0]["r11_palime"])+4000,4)) {
                $pensao_alim_13 += $gerfs13[$Igerfs13]["r35_valor"];
              }
            }
          }
          if (!db_empty($cfpess[0]["r11_rubdec"])) {
            if ($gerfs13[$Igerfs13]["r35_rubric"] == $cfpess[0]["r11_rubdec"]) {
              $sal13 += $gerfs13[$Igerfs13]["r35_valor"];
            }
          }
        }
      }
    }
    $subpes = $subpes_original;
    $condicaoaux = " and r90_regist = ".db_sqlformat($pessoal[0]["r01_regist"] );
    global $pontofx;
    if (db_selectmax("pontofx", "select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux )) {
      for ($Ipontofx=0; $Ipontofx<count($pontofx); $Ipontofx++) {
        $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat($pontofx[$Ipontofx]["r90_rubric"] );
        if (db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux )
        && $rubricas[0]["rh27_calc2"] != 0  ) {
          $iind = db_ascan($m_rubric,$pontofx[$Ipontofx]["r90_rubric"]);

          if (db_empty($iind)) {
            $imax += 1;
            $iind = $imax;
            $m_rubric[$iind] = $pontofx[$Ipontofx]["r90_rubric"];
            $m_cont[$iind]   = 0;
            $m_valor[$iind]  = 0;
            $m_quant[$iind]  = 0;
            $qten[$iind]     = 0;
            $vlrn[$iind]     = 0;
            $m_cont[$iind]  += 1;
            $m_valor[$iind] += $pontofx[$Ipontofx]["r90_valor"];
            $m_quant[$iind] += $pontofx[$Ipontofx]["r90_quant"];
            if (db_at(db_str($rubricas[0]["rh27_calc2"],1),"1-2-7")>0) {
              $qten[$iind] = $pontofx[$Ipontofx]["r90_quant"];
              $vlrn[$iind] = $pontofx[$Ipontofx]["r90_valor"];
            }
          } else {
            if (db_at(db_str($rubricas[0]["rh27_calc2"],1),"1-2-7")>0) {
              $qten[$iind] = $pontofx[$Ipontofx]["r90_quant"];
              $vlrn[$iind] = $pontofx[$Ipontofx]["r90_valor"];
            }
          }
        }
      }
    }
    if (!db_empty($sal13)) {
      $matriz2[1] = $matric;
      $matriz2[2] = $rubrica_13;
      $matriz2[3] = round($sal13,2);
      $matriz2[4] = 0;
      $matriz2[5] = $lotacaoatual;
      $matriz2[6] = "3";
      $matriz2[7] = db_val(db_substr($subpes,1,4 ) );
      $matriz2[8] = db_val(db_substr($subpes, -2 ) );
      $matriz2[9] = db_getsession("DB_instit");
      if($sigla=="r92" ){
         db_insert("pontoprovf13", $matriz1, $matriz2 );
      }else{
         db_insert("pontofr", $matriz1, $matriz2 );
      }
    }
    if (!db_empty($pensao_alim_13)) {
      $matriz2[1] = $matric;
      $matriz2[2] = "R980";
      $matriz2[3] = round($pensao_alim_13,2);
      $matriz2[4] = 0;
      $matriz2[5] = $lotacaoatual;
      $matriz2[6] = "3";
      $matriz2[7] = db_val(db_substr($subpes,1,4 ) );
      $matriz2[8] = db_val(db_substr($subpes, -2 ) );
      $matriz2[9] = db_getsession("DB_instit");
      if($sigla=="r92" ){
         db_insert("pontoprovf13", $matriz1, $matriz2 );
      }else{
         db_insert("pontofr", $matriz1, $matriz2 );
      }
    }
    $calc_meses_afas = $meses_afastado ;
    //echo "<BR> calc_meses_afas --> $calc_meses_afas";
    $nm13 = 12;
    $mes_rescisao = db_month($datafim);
    for ($iind=1; $iind<=$imax; $iind++) {
      $calcula_fracao_nm13 = false;
      $quant = 0;
      $valor = 0;
      $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat($m_rubric[$iind] );
      if (db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux )) {
        $vtipo = db_str($rubricas[0]["rh27_calc2"],1);
        $condicaoaux  = " and r90_regist = ".db_sqlformat($matric );
        $condicaoaux .= " and r90_rubric = ".db_sqlformat($m_rubric[$iind] );
        if (db_selectmax("pontofx", "select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux )) {
          if (db_at($vtipo,"1-2-7")>0) {
            if (!db_empty($rubricas[0]["rh27_form"] ) && $pontofx[0]["r90_valor"] > 0) {
              if (db_boolean($rubricas[0]["rh27_calcp"])) {
                $valor  = ($vlrn[$iind]/30) * $ns13;
              } else {
                $valor  = $vlrn[$iind];
              }
            } else {
              if (!db_empty($rubricas[0]["rh27_form"])) {
                if (db_boolean($rubricas[0]["rh27_propq"])) {
                  $quant  = ($qten[$iind]/30) * $ns13;
                  //echo "<br>  tipo 1 --> ".$quant."  = (".$qten[$iind]."/30) * ".$ns13."     rubrica --> ".db_sqlformat($m_rubric[$iind] );
                } else {
                  $quant  = $qten[$iind];
                }
              } else {
                if (db_boolean($rubricas[0]["rh27_calcp"])) {
                  $valor  = ($vlrn[$iind]/30) * $ns13;
                } else {
                  $valor  = $vlrn[$iind];
                }
              }
            }
          } else if (db_at($vtipo,"3-4")>0 && $m_quant[$iind] != 0 ) {
            if (!db_empty($rubricas[0]["rh27_form"])) {
              $quant = ($rubricas[0]["rh27_quant"] / $nm13) * $m_cont[$iind];
            } else {
              $valor = ($rubricas[0]["rh27_quant"] / $nm13) * $m_cont[$iind];
            }
          } else if (db_at($vtipo,"5-6")>0) {
            if (!db_empty($rubricas[0]["rh27_form"])) {
              $quant += $m_quant[$iind] / $nm13;
            } else {
              $valor += $m_valor[$iind] / $nm13;
            }
            $calcula_fracao_nm13 = true;
          } else if ($vtipo == "8") {
            $valor += $m_valor[$iind] / $nm13;
            $calcula_fracao_nm13 = true;
          } else if ($vtipo == "9" && $m_cont[$iind] != 0 ) {
            if (!db_empty($rubricas[0]["rh27_form"])) {
              $quant = (($pontofx[0]["r90_quant"]) / $nm13) * $m_cont[$iind];
            } else {
              $valor = (($pontofx[0]["r90_valor"]) / $nm13) * $m_cont[$iind] ;
            }
          }
        } else {
          if ($vtipo == "2") {
            if (! db_empty($rubricas[0]["rh27_form"])) {
              if (db_boolean($rubricas[0]["rh27_propq"])) {
                $quant  = ($qten[$iind]/30) * $ns13;
              } else {
                $quant  = $qten[$iind];
              }
            } else {
              if (db_boolean($rubricas[0]["rh27_calcp"])) {
                $valor  = ($vlrn[$iind]/30) * $ns13;
              } else {
                $valor  = $vlrn[$iind];
              }
            }
          } else if (db_at($vtipo,"3-4")>0 && $m_quant[$iind] != 0 ) {
            if (!db_empty($rubricas[0]["rh27_form"])) {
              $quant = ($rubricas[0]["rh27_quant"] / $nm13) * $m_cont[$iind];
            } else {
              $valor = ($rubricas[0]["rh27_quant"] / $nm13) * $m_cont[$iind];
            }
            $calcula_fracao_nm13 = true;
          } else if ($vtipo == "6") {
            $dividir_por = ($nm13);
            if ( $db21_codcli == "18"
            && $pessoal[0]["r01_regime"] != 2
            && ( db_at($rubricas[0]["rh27_rubric"],"0004-0005") > 0  ) ) {
              if ($dividir_por > 6) {
                $dividir_por = 6;
              }
            }
            if (!db_empty($rubricas[0]["rh27_form"])) {
              $quant += $m_quant[$iind] / $dividir_por;
            } else {
              $valor += $m_valor[$iind] / $dividir_por;
            }
            $calcula_fracao_nm13 = true;
          } else if ($vtipo == "8") {
            $valor += $m_valor[$iind] / ( $nm13 );
            $calcula_fracao_nm13 = true;
          } else if ($vtipo == "9" && $m_cont[$iind] != 0) {
            if (!db_empty($rubricas[0]["rh27_form"])) {
              $quant = (($m_quant[$iind])/ $nm13) * $m_cont[$iind];
            } else {
              $valor = (($m_valor[$iind]) / $nm13) * $m_cont[$iind] ;
            }
          }
        }
      }
      // db_debug1(" matriz1 --> ".print_r($matriz1));
      // db_debug1(" valor quant --> $quant");
      // db_debug1(" valor valor --> $valor");
      
      if ($quant != 0 || $valor != 0) {
        $matriz2[1] = $matric;
        $matriz2[2] = db_str(db_val($m_rubric[$iind])+4000,4,0,"0");
        if ($valor  != 0 ) {
          if ($calcula_fracao_nm13) {
            $matriz2[3] = round($valor,2);
          } else {
            $matriz2[3] = round(( ( $valor * ( $nm13 - $calc_meses_afas ) ) / $nm13 ),2);
          }
          $matriz2[4] = 0;
        } else {
          if ($calcula_fracao_nm13) {
            $matriz2[4] = round($quant,2);
          } else {
            $matriz2[4] = round(( ( $quant * ( $mes_rescisao - $calc_meses_afas ) ) / $mes_rescisao ),2);
//echo "<BR> ".$matriz2[4]." = round(( ( $quant * ( $mes_rescisao - $calc_meses_afas ) ) / $mes_rescisao ),2);";
          }
          $matriz2[3] = 0;
        }
        $matriz2[5] = $lotacaoatual;
        $matriz2[6] = "3";
        $matriz2[7] = db_val(db_substr($subpes,1,4 ) );
        $matriz2[8] = db_val(db_substr($subpes, -2 ) );
        $matriz2[9] = db_getsession("DB_instit");
        if($sigla=="r92" ){
           db_insert("pontoprovf13", $matriz1, $matriz2 );
        }else{
           db_insert("pontofr", $matriz1, $matriz2 );
        }
      }
    }
  }
  
}


function ferias_para_rescisao ($datainicio, $datafim, $tpp, $sigla="r19") {

  global $d08_carnes, $db21_codcli, $rubricas, $pontofx, $matriz1, $matriz2, $subpes;
  global $m_rubr, $m_tipo, $m_media , $m_valor , $m_quant, $qten , $vlrn, $r01_taviso,$subpes_original;
  global $datainicio, $datafim, $max, $gerfsal, $gerffer, $gerfcom, $pontofx, $rescisao, $pessoal,$qmeses, $db_debug;
  
  $qmeses = bcdiv(db_datedif($datafim ,$datainicio),30,0);
  $anofim = db_year($datainicio);
  $mesfim = db_month($datainicio)+$qmeses;
  if( $mesfim > 12 ){
     $mesfim = ($mesfim - 12);
     $anofim++;
  }
  
  $dia_fim = ndias(db_str( $mesfim ,2,0,"0") . "/" . db_str( $anofim,4,0,"0"));
  if (db_day($datainicio ) < $dia_fim) {
    $dia_fim = db_day($datainicio );
  }
  
  $datafim_ = db_ctod(db_str($dia_fim,2,0,"0") . "/" .db_str($mesfim,2,0,"0"). "/" . db_str($anofim,4,0,"0"));
  if( db_substr(db_dtoc($datafim_),1,2) > "28" && db_substr(db_dtoc($datafim_),4,2) == "02") {
     $datafim_ = db_ctod(db_str("28/02/".db_str($anofim,4,0,"0")));
  }

  if (db_datedif($datafim ,$datafim_) >= 15) {
    $qmeses++;
  }
  
  $m_rubr = array();
  $m_quant= array();
  $m_valor= array();
  $m_media= array();
  $m_tipo = array();
  $qten   = array();
  $vlrn   = array();
  
//echo "<BR> qmeses --> $qmeses";	
  $max = 0;
  $indano = db_year($datainicio);
  $indmes = db_month($datainicio);
  $mes_ano = db_str( db_month($datainicio ) ,2,0,"0") . "/" . db_str( db_year($datainicio ),4,0,"0");
  
  if( ( ( ndias( $mes_ano ) - db_day($datainicio) ) < 15 ) || ( $indmes == 2 && db_day( $datainicio ) == 15 ) ){
    $indmes++;
    if( $indmes > 12){
       $indmes = 01;
       $indano++;
    }
  }
  if( $indmes == 3 && db_day($datainicio) == 15){
     $indmes--;
     if( $indmes < 01){
        $indmes = 12;
        $indano--;
     }
  }
  
  $meses_avaliados = 0;
//echo "<BR> indano --> $indano";	
//echo "<BR> indmes --> $indmes";	
  while (1==1) {

    $subpes = db_str($indano,4,0,"0")."/".db_str($indmes,2,0,"0");
    //echo "<BR> subpes --> $subpes";	
    //echo "<BR> subpes_original  --> $subpes_original";	
    $meses_avaliados++;
    $tem_no_mes_tipo9 = false;
    $iencontrei = false;
    $condicaoaux = " and r14_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
    if ( db_selectmax("gerfsal", "select * from gerfsal ".bb_condicaosubpes("r14_").$condicaoaux )) {

      for ($Igerfsal=0;$Igerfsal<count($gerfsal);$Igerfsal++) {
        //echo "<BR> rubrica gerfsal --> ".$gerfsal[$Igerfsal]["r14_rubric"];
        //echo "<BR> valor max $max";
        $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat($gerfsal[$Igerfsal]["r14_rubric"]);
        if (db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) && $rubricas[0]["rh27_calc1"] != 0 ) {
          //echo "<BR> achou rubrica rubricas --> ".$rubricas[0]["rh27_rubric"];
          if( $db21_codcli == "18" && $pessoal[0]["r01_regime"] != 2 && ( db_at($gerfsal[$Igerfsal]["r14_rubric"],"0004-0005") > 0 ) ) {
            continue;
          }

          $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
          
          $ind = db_ascan($m_rubr,$gerfsal[$Igerfsal]["r14_rubric"]);
          //echo "<BR> indice  -->  $ind";

          if ( db_empty($ind)) {

            $max += 1;
            $ind = $max;
            $m_rubr[$ind] = $gerfsal[$Igerfsal]["r14_rubric"];
            $m_tipo[$ind] = $tiporubrica;
            zeraarray($ind);

          }

          if ( $tiporubrica == "9") {

            $tem_no_mes_tipo9 = true;
            $m_media[$ind] += 1;
            $m_valor[$ind] = $gerfsal[$Igerfsal]["r14_valor"];
            $m_quant[$ind] = $gerfsal[$Igerfsal]["r14_quant"];

          } else {

              if ( ($gerfsal[$Igerfsal]["r14_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
                $m_media[$ind] += 1;
                //echo "<BR> m_media 1.3.1 --> ";
              }

              if ( db_at($tiporubrica,"3-4-7")>0) {
                $quant_restomenos = ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;

                if ( !db_empty($quant_restomenos)) {
                  $quant_resto = $gerfsal[$Igerfsal]["r14_quant"] - $quant_restomenos;

                  while ($quant_resto > 0) {

                    if ( $quant_resto >= ( $quant_restomenos /2 )) {
                      $m_media[$ind] += 1;
                      $quant_resto = $quant_resto - $quant_restomenos;
                    } else {
                      break;
                    }
                  }

                } else { 
                  $m_media[$ind] += 1;
                }

              }

              $m_valor[$ind] += $gerfsal[$Igerfsal]["r14_valor"];
              $m_quant[$ind] += $gerfsal[$Igerfsal]["r14_quant"];
              if (db_at($tiporubrica,"1-2-7")>0) {
                //echo "<BR> rubrica 1.3.1 --> ".$rubricas[0]["rh27_rubric"];
                //echo "<BR> qten 1.3.1    --> ".$gerfsal[$Igerfsal]["r14_quant"];
                $qten[$ind] = $gerfsal[$Igerfsal]["r14_quant"];
                $vlrn[$ind] = $gerfsal[$Igerfsal]["r14_valor"];
              }

              $iencontrei = true;

            }
        }
      }
    }

    $condicaoaux = " and r48_regist = ".db_sqlformat($pessoal[0]["r01_regist"] );
    if ( db_selectmax( "gerfcom", "select * from gerfcom ".bb_condicaosubpes("r48_").$condicaoaux )) {

      for ($Igerfcom=0;$Igerfcom<count($gerfcom);$Igerfcom++) {

        $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $gerfcom[$Igerfcom]["r48_rubric"] );
        if (db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) && $rubricas[0]["rh27_calc1"] != 0 ) {

          if ( $db21_codcli == "18"  && $pessoal[0]["r01_regime"] != 2 && ( db_at($gerfcom[$Igerfcom]["r48_rubric"],"0004-0005") > 0 ) ) {
            continue;
          }

          $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;

          $ind = db_ascan($m_rubr,$gerfcom[$Igerfcom]["r48_rubric"]);
          if ( db_empty($ind)) {
            $max += 1;
            $ind = $max;
            $m_rubr[$ind] = $gerfcom[$Igerfcom]["r48_rubric"];
            $m_tipo[$ind] = $tiporubrica;
            zeraarray($ind);
          }

          if ( $tiporubrica == "9") {

            if ( !$tem_no_mes_tipo9) {
              $m_valor[$ind] = $gerfcom[$Igerfcom]["r48_valor"];
              $m_quant[$ind] = $gerfcom[$Igerfcom]["r48_quant"];
              $tem_no_mes_tipo9 = true;
              $m_media[$ind] += 1;
            }

          } else {

              if ( ($gerfcom[$Igerfcom]["r48_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))) {
                $m_media[$ind] += 1;
              }

              if ( db_at($tiporubrica,"3-4-7")>0) {

                $quant_restomenos = ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;

                if ( !db_empty($quant_restomenos)) {
                  $quant_resto = $gerfcom[$Igerfcom]["r48_quant"] - $quant_restomenos;

                  while ( $quant_resto > 0) {
                    if ( $quant_resto >= ( $quant_restomenos /2 )) {
                      $m_media[$ind] += 1;
                      $quant_resto = $quant_resto - $quant_restomenos;
                    } else {
                      break;
                    }
                  }

                } else {

                  $m_media[$ind] += 1;
                }

              }

              $m_valor[$ind] += $gerfcom[$Igerfcom]["r48_valor"];
              $m_quant[$ind] += $gerfcom[$Igerfcom]["r48_quant"];
              if ( db_at($tiporubrica,"1-2-7")>0) {
                $qten[$ind] = $gerfcom[$Igerfcom]["r48_quant"];
                $vlrn[$ind] = $gerfcom[$Igerfcom]["r48_valor"];
              }

            }
        }
      }
    }

    if (!$iencontrei) {

      $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[0]["r01_regist"] );
      if ( db_selectmax("gerffer", "select * from gerffer ".bb_condicaosubpes("r31_").$condicaoaux )) {

        for ($Igerffer=0;$Igerffer<count($gerffer);$Igerffer++) { 

          $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $gerffer[$Igerffer]["r31_rubric"] );
          if (db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) && $rubricas[0]["rh27_calc1"] != 0 ){

            if( $db21_codcli == "18" && $pessoal[0]["r01_regime"] != 2 && ( db_at($gerffer[$Igerffer]["r31_rubric"],"0004-0005") > 0 ) ) {
              continue;
            }
            $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
            $ind = db_ascan($m_rubr,$gerffer[$Igerffer]["r31_rubric"]);
            if ( db_empty($ind)) {
              $max += 1;
              $ind = $max;
              $m_rubr[$ind] = $gerffer[$Igerffer]["r31_rubric"];
              $m_tipo[$ind] = db_str($rubricas[0]["rh27_calc1"],1);
              zeraarray($ind);
            }

            if ( $tiporubrica == "9") {

              if ( !$tem_no_mes_tipo9) {
                $m_valor[$ind] = $gerffer[$Igerffer]["r31_valor"];
                $m_quant[$ind] = $gerffer[$Igerffer]["r31_quant"];
                $tem_no_mes_tipo9 = true;
                $m_media[$ind] += 1;
              }

            } else {

                if ( ($gerffer[$Igerffer]["r31_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) /2))) {
                  $m_media[$ind] += 1;
                }

                if ( db_at(db_str($rubricas[0]["rh27_calc1"],1),"3-4-7")>0) {
                  $quant_restomenos = ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;

                  if ( !db_empty($quant_restomenos)) {
                    $quant_resto = $gerffer[$Igerffer]["r31_quant"] - ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;

                    while ($quant_resto > 0) {
                      if ( $quant_resto >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) /2)) {
                        $m_media[$ind] += 1;
                        $quant_resto = $quant_resto - ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
                      } else {
                        break;
                      }
                    }
                  } else {
                    $m_media[$ind] += 1;
                  }

                }

                $m_valor[$ind] += $gerffer[$Igerffer]["r31_valor"];
                $m_quant[$ind] += $gerffer[$Igerffer]["r31_quant"];

                if ( db_at(db_str($rubricas[0]["rh27_calc1"],1),"1-2-7")>0) {
                  $qten[$ind] = $gerffer[$Igerffer]["r31_quant"];
                  $vlrn[$ind] = $gerffer[$Igerffer]["r31_valor"];
                }
              }
          }
        }
      }
    }

    if ( !$iencontrei ) {

      $condicaoaux = " and r90_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
      db_selectmax( "pontofx", "select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux );
      for ($Ipontofx=0;$Ipontofx< count($pontofx) ;$Ipontofx++) {
        $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );

        if (db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) && $rubricas[0]["rh27_calc1"] != 0  ) {

          $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
      
          $ind = db_ascan($m_rubr,$pontofx[$Ipontofx]["r90_rubric"]);

          if ( db_empty($ind)) {
            $max += 1;
            $ind = $max;
            $m_rubr[$ind] = $pontofx[$Ipontofx]["r90_rubric"];
            $m_tipo[$ind] = $tiporubrica;
            zeraarray($ind);
          }

          if ( $tiporubrica == "9") {
            $m_media[$ind] += 1;
            $m_valor[$ind] = $pontofx[$Ipontofx]["r90_valor"]   ;
            $m_quant[$ind] = $pontofx[$Ipontofx]["r90_quant"] ;
          } else {
            $m_media[$ind] += 1;
            $m_valor[$ind] += $pontofx[$Ipontofx]["r90_valor"]   ;
            $m_quant[$ind] += $pontofx[$Ipontofx]["r90_quant"]     ;
          }

          if ( db_at($tiporubrica,"1-2-7")>0) {
            $qten[$ind] = $pontofx[$Ipontofx]["r90_quant"];
            $vlrn[$ind] = $pontofx[$Ipontofx]["r90_valor"];
          }

        }

      }

    }

    if( $r01_taviso == 2 && $subpes == $subpes_original) {

      $condicaoaux = " and r90_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
      db_selectmax( "pontofx", "select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux );
      for ($Ipontofx=0;$Ipontofx<count($pontofx);$Ipontofx++) {

        $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
        if (db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) && $rubricas[0]["rh27_calc1"] != 0 ) {

          $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
          
          $ind = db_ascan($m_rubr,$pontofx[$Ipontofx]["r90_rubric"]);

          if ( db_empty($ind)) {
            $max += 1;
            $ind = $max;
            $m_rubr[$ind] = $pontofx[$Ipontofx]["r90_rubric"];
            $m_tipo[$ind] = $tiporubrica;
            zeraarray($ind);
          }

          if ( $tiporubrica == "9") {
            $m_media[$ind] += 1;
            $m_valor[$ind] = $pontofx[$Ipontofx]["r90_valor"]   ;
            $m_quant[$ind] = $pontofx[$Ipontofx]["r90_quant"]   ;
          } else {
            $m_media[$ind] += 1;
            $m_valor[$ind] += $pontofx[$Ipontofx]["r90_valor"]   ;
            $m_quant[$ind] += $pontofx[$Ipontofx]["r90_quant"]  ;
          }

          if ( db_at($tiporubrica,"1-2-7") > 0 ) {
            $qten[$ind] = $pontofx[$Ipontofx]["r90_quant"];
            $vlrn[$ind] = $pontofx[$Ipontofx]["r90_valor"];
          }

        }

      }

    }

    $testa_mes = db_month($datafim);
    $testa_ano = db_year($datafim);
    $dias_peraf = db_str($testa_mes,2,0,"0") . "/" .db_str($testa_ano,4,0,"0");

//echo "<BR> meses_avaliados  --> $meses_avaliados";	   
//echo "<BR> testa_mes  --> $testa_mes";	   
//echo "<BR> testa_ano  --> $testa_ano";	   
//echo "<BR> dias_peraf --> $dias_peraf";
//echo "<BR> ndias(dias_peraf) --> ".ndias($dias_peraf);

    if ( db_day($datafim) <= 14 || ( db_day($datafim) <= 15 && ndias( $dias_peraf ) == 31 ) ) {
      $testa_mes = db_month($datafim) - 1;
      if ( $testa_mes <= 0) {
        $testa_mes = 12;
        $testa_ano -= 1;
      }
    }
    //echo "<BR> testa_mes  1.2 --> $testa_mes";	   
    //echo "<BR> testa_ano  1.2 --> $testa_ano";	   
    //echo "<BR> indano     1.2 --> $indano";	   
    //echo "<BR> indmes     1.2 --> $indmes";	   
    if( $indano == $testa_ano && $indmes == $testa_mes){
      break;
    }

    if ( $indmes < 12) {
      $indmes += 1;
    } else {
      $indano += 1;
      $indmes = 1;
    }

    if ( ( $meses_avaliados >= 12 ) || ( db_val(db_substr($subpes_original,1,4)) < $indano 
      || ( db_val(db_substr($subpes_original,6,2)) < $indmes 
      && db_val(db_substr($subpes_original,1,4)) == $indano )) ) {
        break;
      }

  }
	
  /**
   * Qunatidade de ferias vencidas
   */   
  global $iFeriasVencidas;

  if ( $tpp == 'V' ) {
    $iFeriasVencidas = 1;
  }

  /**
   * Avos de ferias do exercicio da rescisao
   */   
  global $iAvosFeriasPeriodo;

  if ( $tpp == 'P' ) {
    $iAvosFeriasPeriodo = $meses_avaliados;
  }

	if( $db21_codcli == "18" && $pessoal[0]["r01_regime"] != 2){
	   horasextras_codcli18_164();
	}
	
//echo "<BR> qten  --> ".print_r($qten);
	$subpes = $subpes_original;
	
	if ($db_debug == true) {
	  echo "[ferias_para_rescisao] Chamando função acrescentapontofx()... <br>";	
	}
	acrescentapontofx();
	if ($db_debug == true) {
	  echo "[ferias_para_rescisao] Fim do processamento dafunção acrescentapontofx()... <br><br>";
	}
	
	$meses_avaliados--;

//echo "<BR> m_rubr  --> ".print_r($m_rubr);	
//echo "<BR> m_quant --> ".print_r($m_quant);	
//echo "<BR> m_valor --> ".print_r($m_valor);	
//echo "<BR> m_media --> ".print_r($m_media);	
//echo "<BR> m_tipo  --> ".print_r($m_tipo);	
//echo "<BR> qten  --> ".print_r($qten);
    
	if ($db_debug == true) {
	  echo "[ferias_para_rescisao] 0 Chamando função avalia_ponto_ferias_rescisao($qmeses,$tpp,$sigla);<br>";	
	}
	avalia_ponto_ferias_rescisao($qmeses,$tpp,$sigla);
	if ($db_debug == true) {
		echo "[ferias_para_rescisao] Fim do processamento da função avalia_ponto_ferias_rescisao($qmeses,$tpp,$sigla);<br><br>";
	}
	
//echo "<BR><BR>passou  1  ----- ". $rescisao[0]["r59_tercof"];
	if( isset($rescisao) && $rescisao[0]["r59_tercof"] > 0){
//echo "<BR><BR>passou  2";
	   if ($db_debug == true) {
	     echo "[ferias_para_rescisao] 1 Chamando função grava_terco();<br>";
	   }
	   grava_terco();
	   if ($db_debug == true) {
	   	echo "[ferias_para_rescisao] Fim do processamento da função grava_terco();<br><br>";
	   }	   
//echo "<BR><BR>passou  3";
	}
	
	/**
	 * Adicionado função 'grava_terco' passando por parâmetro o nome da tabela 'pontoprovfe'
	 * para que seja efetuado o ponto de provento de férias
	 */
	if ($db_debug == true) {
		echo "[ferias_para_rescisao] 2 Chamando função grava_terco('pontoprovfe');<br>";
	}	
	grava_terco('pontoprovfe');
	if ($db_debug == true) {
		echo "[ferias_para_rescisao] Fim do processamento da função grava_terco('pontoprovfe');<br><br>";
	}	
//echo "<BR><BR>passou  4";
	$subpes = $subpes_original;
}


function acrescentapontofx () {
  
  global $pessoal, $rubricas, $pontofx; 
  global $m_rubr,$qten,$vlrn,$m_media,$quants ,$quantd,$m_valor,$m_quant,$m_tipo;
  global $nsaldo, $max;
       
//echo "<BR> acrescentapontofx : r01_regist --> ".$pessoal[0]["r01_regist"];	
      $condicaoaux = " and r90_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
      db_selectmax("pontofx","select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux );
      
      for ($Ipontofx=0;$Ipontofx<count($pontofx);$Ipontofx++) {
//echo "<BR> rubrica do pontofx --> ".$pontofx[$Ipontofx]["r90_rubric"];	
//echo "<BR> valor max --> $max";	
         $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
         if ( db_selectmax("rubricas","select * from rhrubricas ".$condicaoaux )) {
	   
//echo "<BR> achou rubrica em rubricasx --> ".$pontofx[$Ipontofx]["r90_rubric"];	
           if ( $rubricas[0]["rh27_calc1"] == 0 ) {
	         continue; 
	       }

//echo "<BR> rh27_calc --> ".$rubricas[0]["rh27_calc1"];	
           $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
//echo "<BR> db_at(tiporubrica) --> ".db_at($tiporubrica,"1-2-7");   
	    
            // ** atencao **
	    // ver depois
           $ind = db_ascan($m_rubr,$pontofx[$Ipontofx]["r90_rubric"]);
	    
           if ( db_empty($ind)) {
           	
              if ( db_at( $tiporubrica,"1-3-7")>0 ) {
              	
                $max += 1;
                $ind = $max;
                $m_rubr[$ind] = $pontofx[$Ipontofx]["r90_rubric"];
                $m_tipo[$ind] = $tiporubrica;
                $m_media[$ind] = 0;
                $m_valor[$ind] = 0;
                $m_quant[$ind] = 0;
                $qten[$ind] = 0;
                $vlrn[$ind] = 0;
                $m_media[$ind] += 0;
                $m_valor[$ind] += $pontofx[$Ipontofx]["r90_valor"];
                $m_quant[$ind] += $pontofx[$Ipontofx]["r90_quant"];
	       
                $qten[$ind] = $pontofx[$Ipontofx]["r90_quant"];
                $vlrn[$ind] = $pontofx[$Ipontofx]["r90_valor"];
//echo "<BR> rubrica 1.3.21 --> ".$rubricas[0]["rh27_rubric"];
//echo "<BR> qten 1.3.21    --> ".$pontofx[$Ipontofx]["r90_quant"];
              }
              
	       } else {
	       	
              if( db_at($tiporubrica,"1-2-7") > 0 ){
                 $qten[$ind] = $pontofx[$Ipontofx]["r90_quant"];
                 $vlrn[$ind] = $pontofx[$Ipontofx]["r90_valor"];
//echo "<BR> rubrica 1.3.41 --> ".$rubricas[0]["rh27_rubric"];
//echo "<BR> qten 1.3.41    --> ".$pontofx[$Ipontofx]["r90_quant"];
              }
           }
         }
      }
}

function acrescentapontofx_ferias_rescisao (){
	

  global $d08_carnes, $db21_codcli, $rubricas, $pontofx, $matriz1, $matriz2,$pessoal;
  
  global $m_rubr, $m_tipo, $m_media , $m_valor , $m_quant, $qten , $vlrn, $max;
  
	$condicaoaux = " and r90_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
	db_selectmax("pontofx", "select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux );
	
	for($Ipontofx=0;$Ipontofx<count($pontofx);$Ipontofx++){
	   $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
	   if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) 
		     && $rubricas[0]["rh27_calc1"] != 0 ){

	      $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
	      
        $ind = db_ascan($m_rubr,$pontofx[$Ipontofx]["r90_rubric"]);
	      if( db_empty($ind)){
		 $max += 1;
		 $ind = $max;
		 $m_rubr[$ind] = $pontofx[$Ipontofx]["r90_rubric"];
		 $m_tipo[$ind] = $tiporubrica;
		 $m_media[$ind] = 0;
		 $m_valor[$ind] = 0;
		 $m_quant[$ind] = 0;
		 $qten[$ind] = 0;
		 $vlrn[$ind] = 0;
		 $m_media[$ind] += 1;
		 $m_valor[$ind] += $pontofx[$Ipontofx]["r90_valor"];
		 $m_quant[$ind] += $pontofx[$Ipontofx]["r90_quant"];
		 if( db_at($tiporubrica,"1-2-7") > 0 ){
		    $qten[$ind] = $pontofx[$Ipontofx]["r90_quant"];
		    $vlrn[$ind] = $pontofx[$Ipontofx]["r90_valor"];
		 }
	      }else{
		if(db_at($tiporubrica,"1-2-7") > 0 ){
		   $qten[$ind] = $pontofx[$Ipontofx]["r90_quant"];
		   $vlrn[$ind] = $pontofx[$Ipontofx]["r90_valor"];
		}
	      }
	  }
	}
}

/**
 * Grava Terco
 * Adicionado parâmetro $sParamTabela que recebe o nome da tabela em que os dados
 * deverão ser salvos.
 * 
 * Caso seja passado 'pontoprovfe' grava o provento de ferias (Rubrica 'R931')
 *
 * @param string $sParamTabela
 */
function grava_terco($sParamTabela = 'pontofr') {
  
  global $d08_carnes, $db21_codcli, $matriz2, $matriz1, $nsaldo, $pessoal, $matric, $lotacaoatual,$subpes;
  global $pontofr;
  
  //echo "<BR><BR>$nsaldo === passou";
  if( $nsaldo > 0 ){
    if ($sParamTabela == 'pontofr') {

      $matriz2[1] = $matric;
      $matriz2[2] = "R931";
      $matriz2[3] = 0;
      $matriz2[4] = 1;
      $matriz2[5] = $lotacaoatual;
      $matriz2[6] = " ";
      $matriz2[7] = db_val( db_substr( $subpes,1,4 ) );
      $matriz2[8] = db_val( db_substr( $subpes, -2 ) );
      $matriz2[9] = db_getsession("DB_instit");

      $condicaoaux  = " and r19_regist = ".db_sqlformat( $matric );
      $condicaoaux .= " and r19_rubric = 'R931'";
      
      if(!db_selectmax( "pontofr", "select * from pontofr ".bb_condicaosubpes("r19_").$condicaoaux )) {
        db_insert( "pontofr", $matriz1, $matriz2 );
      } else {
        db_update( "pontofr", $matriz1, $matriz2, bb_condicaosubpes("r19_").$condicaoaux );
      }
    } else {
    
      if ($sParamTabela == 'pontoprovfe') {
        /*
         * $aCamposPontoProvFe
         * 
         * São os Campos que serão salvos na tabela
         */
        $aCamposPontoProvFe[1]  = "r91_anousu";
        $aCamposPontoProvFe[2]  = "r91_mesusu";
        $aCamposPontoProvFe[3]  = "r91_regist";
        $aCamposPontoProvFe[4]  = "r91_rubric";
        $aCamposPontoProvFe[5]  = "r91_valor";
        $aCamposPontoProvFe[6]  = "r91_quant";
        $aCamposPontoProvFe[7]  = "r91_lotac";
        $aCamposPontoProvFe[8]  = "r91_media";
        $aCamposPontoProvFe[9]  = "r91_calc";
        $aCamposPontoProvFe[10] = "r91_tpp";
        $aCamposPontoProvFe[11] = "r91_instit";
        
        /*
         * $aDadosPontoProvFe
         * 
         * São os valores dos campos. Devem ser ordenados conforme tabela no banco de dados
         */
        $aDadosPontoProvFe[1]  = db_val( db_substr( $subpes,1,4 ) );
        $aDadosPontoProvFe[2]  = db_val( db_substr( $subpes, -2 ) );
        $aDadosPontoProvFe[3]  = $matric;
        $aDadosPontoProvFe[4]  = "R931";
        $aDadosPontoProvFe[5]  = 0;
        $aDadosPontoProvFe[6]  = 1;
        $aDadosPontoProvFe[7]  = $lotacaoatual;
        $aDadosPontoProvFe[8]  = 'null';
        $aDadosPontoProvFe[9]  = 'null';
        $aDadosPontoProvFe[10] = "P";
        $aDadosPontoProvFe[11] = db_getsession('DB_instit');
        
        // Condições Auxiliares para o select/insert/update na tabela 'pontoprovfe'
        $sCondicaoAuxiliar  = " and r91_regist = ".db_sqlformat( $matric );
        $sCondicaoAuxiliar .= " and r91_rubric = 'R931'";
             
        if (!db_selectmax("pontoprovfe", "select * from pontoprovfe ".bb_condicaosubpes("r91_").$sCondicaoAuxiliar)) {
          db_insert( "pontoprovfe", $aCamposPontoProvFe, $aDadosPontoProvFe);
        } else {
          db_update( "pontoprovfe", $aCamposPontoProvFe, $aDadosPontoProvFe, bb_condicaosubpes("r91_").$sCondicaoAuxiliar );
        }
      }
    }
  }

}

function horasextras_codcli18_164 (){
  
  global $subpes, $subpes_original, $rubricas, $gerfsal , $d08_carnes, $db21_codcli, $pessoal;
  
  global $m_rubr, $m_tipo, $m_media , $m_valor , $m_quant, $qten , $vlrn,$max;

  global $gerfsal, $gerffer, $gerfcom;
  
  $subpes_antescarazi = $subpes;
  $indmes = db_val( db_substr( $subpes_original, 6, 2) );
  $indano = db_val( db_substr( $subpes_original, 1, 4) );
  for($i=1;$i<= 6;$i++){
     $indmes--;
     if($indmes < 1){
	$indano--;
	$indmes = 12;
     }
  }
  $meses_avaliados = 0;
  while (1==1){
     $subpes = db_str($indano,4)."/".db_str($indmes,2,0,"0");
	$meses_avaliados++;
	$iencontrei = false;
	$condicaoaux = " and r14_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
	if( db_selectmax( "gerfsal", "select * from gerfsal ".bb_condicaosubpes("r14_").$condicaoaux )){
	    for($Igerfsal=0;$Igerfsal<count($gerfsal);$Igerfsal++){
	       $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $gerfsal[$Igerfsal]["r14_rubric"] );
	       if(db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux )
			&& ( $rubricas[0]["rh27_rubric"] == "0004" 
			  || $rubricas[0]["rh27_rubric"] == "0005" ) ){
		   $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
		   $ind = db_ascan($m_rubr,$gerfsal[$Igerfsal]["r14_rubric"]);
		   if( db_empty($ind)){
		      $max += 1;
		      $ind = $max;
	              $m_rubr[$ind] = $gerfsal[$Igerfsal]["r14_rubric"];
		      $m_tipo[$ind] = $tiporubrica;

		      $m_media[$ind] = 0;
		      $m_valor[$ind] = 0;
		      $m_quant[$ind] = 0;

		      $qten[$ind] = 0;
		      $vlrn[$ind] = 0;
		   }

	       if( ($gerfsal[$Igerfsal]["r14_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
		       $m_media[$ind] += 1;
	       }
	       $m_valor[$ind] += $gerfsal[$Igerfsal]["r14_valor"];
	       $m_quant[$ind] += $gerfsal[$Igerfsal]["r14_quant"];
	       $iencontrei = true;
		   
	       }
	    }
	}
	$condicaoaux = " and r48_regist = ".db_sqlformat($pessoal[0]["r01_regist"] );
	if( db_selectmax( "gerfcom", "select * from gerfcom ".bb_condicaosubpes("r48_").$condicaoaux )){
	   for($Igerfcom=0;$Igerfcom< count($gerfcom);$Igerfcom++){
	      $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $gerfcom[$Igerfcom]["r48_rubric"] );
	      if(db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux )
			      && ( $rubricas[0]["rh27_rubric"] == "0004" 
				      || $rubricas[0]["rh27_rubric"] == "0005" ) ){
		  $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
		  $ind = db_ascan($m_rubr,$gerfcom[$Igerfcom]["r48_rubric"]);
		  if( db_empty($ind)){
		      $max += 1;
		      $ind = $max;
		      $m_rubr[$ind] = $gerfcom[$Igerfcom]["r48_rubric"];
		      $m_tipo[$ind] = $tiporubrica;
		      $m_media[$ind] = 0;
		      $m_valor[$ind] = 0;
		      $m_quant[$ind] = 0;
		      $qten[$ind] = 0;
		      $vlrn[$ind] = 0  ;
		   }
		   
        if( ($gerfcom[$Igerfcom]["r48_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
		     $m_media[$ind] += 1;
	      }
	      $m_valor[$ind] += $gerfcom[$Igerfcom]["r48_valor"];
	      $m_quant[$ind] += $gerfcom[$Igerfcom]["r48_quant"];
		  }

	    }
	 }
	 if( !$iencontrei){
	      $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[0]["r01_regist"] );
	      if( db_selectmax( "gerffer", "select * from gerffer ".bb_condicaosubpes("r31_").$condicaoaux )){
		 for($Igerffer=0;$Igerffer< count($gerffer) ;$Igerffer++){
		    $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $gerffer[$Igerffer]["r31_rubric"] );
	            if(db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux )
			      && ( $rubricas[0]["rh27_rubric"] == "0004" || $rubricas[0]["rh27_rubric"] == "0005" ) ){
		       $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
		       $ind = db_ascan($m_rubr,$gerffer[$Igerffer]["r31_rubric"]);
		       if( db_empty($ind)){
			  $max += 1;
			  $ind = $max;
			  $m_rubr[$ind] = $gerffer[$Igerffer]["r31_rubric"];
			  $m_tipo[$ind] = $rubricas[0]["rh27_calc1"];
			  $m_media[$ind] = 0;
			  $m_valor[$ind] = 0;
			  $m_quant[$ind] = 0;
			  $qten[$ind] = 0;
			  $vlrn[$ind] = 0;
		       }
		       

			  if( ($gerffer[$Igerffer]["r31_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) /2))){
			     $m_media[$ind] += 1;
			  }
			  $m_valor[$ind] += $gerffer[$Igerffer]["r31_valor"];
			  $m_quant[$ind] += $gerffer[$Igerffer]["r31_quant"];
			  if( db_at(db_str($rubricas[0]["rh27_calc1"],1),"1-2-7")>0){
			     $qten[$ind] = $gerffer[$Igerffer]["r31_quant"];
			     $vlrn[$ind] = $gerffer[$Igerffer]["r31_valor"];
			  }
			  $iencontrei = true;
		       
		    }
		 }
	      }
	 }
     if( $indmes < 12){
	 $indmes += 1;
     }else{
	 $indano += 1;
	 $indmes = 1;
     }

     if( ( $meses_avaliados >= 6 ) || ( db_str($indano,4)."/".db_str($indmes,2,0,"0") ) == $subpes_original  ){
	  break;
     }

  }

  $subpes = $subpes_antescarazi;

}

function avalia_ponto_ferias_rescisao($qmeses,$tpp,$sigla){
	
  global $d08_carnes, $db21_codcli, $rubricas, $pontofx, $matriz1, $matriz2,$matric,$lotacaoatual,$pessoal;
  global $m_rubr, $m_tipo, $m_media , $m_valor , $m_quant, $qten , $vlrn, $max,$subpes,$nsaldo, $db_debug;
  
  $nr_meses_ferias = 1;
  $nsaldo = (30/12)*$qmeses;
  
  if ($db_debug) {
  	echo "[avalia_ponto_ferias_rescisao] INICIO DO PROCESSAMENTO... <BR>";
    echo "[avalia_ponto_ferias_rescisao] tpp --> $tpp <br>";	
    echo "[avalia_ponto_ferias_rescisao] qmeses --> $qmeses <br>";	
    echo "[avalia_ponto_ferias_rescisao] nsaldo (30/12)*$qmeses --> $nsaldo <br>";	
    echo "[avalia_ponto_ferias_rescisao] max    --> $max <br>";	
  }
    
  for ($ind=1;$ind<=$max;$ind++) {
  	
	 $quants = 0;
	 $valors = 0;
	 $quantd = 0;
	 $valord = 0;
	 $quanta = 0;
	 $valora = 0;
	 $condicaoaux = " where rh27_instit = ". db_getsession("DB_instit") ." and rh27_rubric = ".db_sqlformat( $m_rubr[$ind]  );
	 if ( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux )) {
	 	
		  $vtipo = db_str($rubricas[0]["rh27_calc1"],1);
	    
	    $nr_meses_ferias = $qmeses;
	    
	    if ( $db21_codcli == "18"  && $pessoal[0]["r01_regime"] != 2 && ( db_at($m_rubr[$ind],"0004-0005") > 0)) {
		  $nr_meses_ferias = 6;
	    }
	    
	    $condicaoaux  = " and r90_regist = ".db_sqlformat( $matric );
	    $condicaoaux .= " and r90_rubric = ".db_sqlformat( $m_rubr[$ind] );
	    global $pontofx;
	    if (db_selectmax("pontofx", "select * from pontofx ".bb_condicaosubpes("r90_").$condicaoaux )) {
	    	
	      if ($db_debug) {
	      	echo "[avalia_ponto_ferias_rescisao] Encontrou dados na tabela pontofx quando ".bb_condicaosubpes("r90_").$condicaoaux."<br>";
	      	echo "[avalia_ponto_ferias_rescisao] vtipo = $vtipo <br>";
	      }	
	      
		  if (db_at($vtipo,"1-2-7")>0) {
		  	
		  	if ($db_debug) {
		  		echo "[avalia_ponto_ferias_rescisao] entrou na condição de vtipo ser 1,2 ou 7<br>";
		  	}
		  	if (!db_empty($rubricas[0]["rh27_form"] ) && $pontofx[0]["r90_valor"] > 0) {

		  	  if ($db_debug) {
		  	  	echo "[avalia_ponto_ferias_rescisao] encontrou formula e o valor do ponto é maior que zero...<br>";
		  	  }
			  if ( db_boolean($rubricas[0]["rh27_calcp"])) {
			  	
			  	if ($db_debug) {
			  		echo "[avalia_ponto_ferias_rescisao] calculando valor<br>";
			  		echo "[avalia_ponto_ferias_rescisao] valor($vlrn[$ind]) /30 * nsaldo($nsaldo) = ".( $vlrn[$ind] /30 * $nsaldo )."<br>";
			  	}			  	
			    $valors = ( $vlrn[$ind] /30 * $nsaldo );
			    
			  } else {
			  	if ($db_debug) {
			  		echo "[avalia_ponto_ferias_rescisao] valor = ".$vlrn[$ind]." <br>";
			  	}			  	
			    $valors = $vlrn[$ind];
			    
			  }
			  
		    } else {
		    	
		      if ($db_debug) {
		      	 echo "[avalia_ponto_ferias_rescisao] Não encontrou formula ou o valor do ponto não é maior que zero...<br>";
		      }
		      		    	
			  if (!db_empty($rubricas[0]["rh27_form"])) {
			  	
			  	if ($db_debug) {
			  		echo "[avalia_ponto_ferias_rescisao] Encontrou formula...<br>";
			  	}			  	
			  	
			  	if( db_boolean($rubricas[0]["rh27_propq"])) {
			  	  if ($db_debug) {
			  	  	echo "[avalia_ponto_ferias_rescisao] Se ".$rubricas[0]["rh27_propq"]." verdadeiro...<br>";
			  	  	echo "[avalia_ponto_ferias_rescisao] quants = ( ".$qten[$ind]." /30 * nsaldo($nsaldo)) => ".( $qten[$ind] /30 * $nsaldo )."<br>";
			  	  }			  		
			      $quants = ( $qten[$ind] /30 * $nsaldo );
			    } else {
			      if ($db_debug) {
			      	echo "[avalia_ponto_ferias_rescisao] quants = ".$qten[$ind]."<br>";
			      }
			      $quants = $qten[$ind];
			    }
			    
			  } else {
			  	
			  	if ($db_debug) {
			  		echo "[avalia_ponto_ferias_rescisao] Não encontrou formula...<br>";
			  	}			  	
			  	
			    if ( db_boolean( $rubricas[0]["rh27_calcp"])) {
			       if ($db_debug) {
			       	 echo "[avalia_ponto_ferias_rescisao] calculando valor<br>";
			       	 echo "[avalia_ponto_ferias_rescisao] valor($vlrn[$ind]) /30 * nsaldo($nsaldo) = ".( $vlrn[$ind] /30 * $nsaldo )."<br>";
			       }			    	
			       $valors = ( $vlrn[$ind] /30 * $nsaldo );
			    } else {
			       if ($db_debug) {
			       	 echo "[avalia_ponto_ferias_rescisao] valor = ".$vlrn[$ind]." <br>";
			       }
			       $valors = $vlrn[$ind];
			    }
			    
			  }
			  
		    }
		    
		  } else if ( db_at($vtipo,"3-4" )>0 && $m_quant[$ind] != 0 ) {
		  	 
		     if ( !db_empty($rubricas[0]["rh27_form"])) {
			   $quants = ($rubricas[0]["rh27_quant"]/12) * $m_media[$ind];
		     } else {
			   $valors = ($pontofx[0]["r90_valor"]/12) * $m_media[$ind] ;
		     }
		     
		  } else if( $vtipo == "5" || $vtipo == "6") {
		  	 
		     if ( !db_empty($rubricas[0]["rh27_form"])) {
			   $quants = $m_quant[$ind] / 12;
		     } else {
			   $valors = $m_valor[$ind] / 12;
		     }
		     
		  } else if( $vtipo == "8") { 
		  	
		     if ( db_boolean($rubricas[0]["rh27_calcp"])) {
			   $valors += (( $m_valor[$ind]/12 ) / 30 * $nsaldo);
		     } else {
			   $valors += (( $m_valor[$ind]/12 ) );
		     }
		     
		  } else if(  $vtipo == "9"  && $m_media[$ind] != 0 ) {
		  	 
		     if ( !db_empty($rubricas[0]["rh27_form"])) {
			   $quants = ( $pontofx[0]["r90_quant"] / 12 ) * $m_media[$ind];
		     } else {
			   $valors = ( $pontofx[0]["r90_valor"] / 12 ) * $m_media[$ind] ;
		     }
		  }
		  
	    } else {
	    	
	      if ($db_debug) {
	      	echo "[avalia_ponto_ferias_rescisao] Não encontrou dados na tabela pontofx quando ".bb_condicaosubpes("r90_").$condicaoaux."<br>";
	      }	    	
	    	
		  if ( $vtipo == "2") {
		  	
		    if ( ! db_empty($rubricas[0]["rh27_form"])) {
		    	
			  if ( db_boolean($rubricas[0]["rh27_propq"])) {
			     $quants  = ( $qten[$ind] /30 ) * $nsaldo;
			  } else {
			     $quants  = $qten[$ind];
			  }
			  
		    } else {
		    	
			  if ( db_boolean($rubricas[0]["rh27_calcp"])) {
			   $valors  = ( $vlrn[$ind] /30 ) * $nsaldo;
			  } else {
			   $valors  = $vlrn[$ind];
			  }
			  
		    }
		    
		  } else if( db_at($vtipo,"4-7")>0 && $m_quant[$ind] != 0) {
		  	 
		     if ( ! db_empty($rubricas[0]["rh27_form"])) {
		     	
			   if ( !db_empty($rubricas[0]["rh27_quant"])) {
			   	
			     if ( $rubricas[0]["rh27_quant"]>999) {
			       $quant_ = $pessoal[0]["r01_hrsmen"] / 12;
			     } else {
			       $quant_ = $rubricas[0]["rh27_quant"] / 12;
			     }
			     
			   } else {
			     $quant_ = ( $m_quant[$ind] / $m_media[$ind] ) / 12;
			   }
			   
			   $quants += $quant_ * $m_media[$ind];
			   
		     } else {
		     	
			    if ( !db_empty($rubricas[0]["rh27_quant"])) {
			    	
			      if ( $rubricas[0]["rh27_quant"] > 999) {
			        $valor_ = $pessoal[0]["r01_hrsmen"] / 12;
			      } else {
			        $valor_ = $rubricas[0]["rh27_quant"] / 12;
			      }
			      
			    } else {
			       $valor_ = ( $m_valor[$ind]/$m_media[$ind] ) / 12;
			    }
			    
			    $valors += $valor_ * $m_media[$ind] ;
			    
		     }
		     
		  } else if( $vtipo == "6") {
		  	 
		     if ( !db_empty($rubricas[0]["rh27_form"])) {
			   $quants = ( $m_quant[$ind] / 12 );
		     } else {
			   $valors = ( $m_valor[$ind] / 12 ) ;
		     }
		     
		  } else if( $vtipo == "8" ) { 
		  	
		     if ( db_boolean($rubricas[0]["rh27_calcp"] )) {
			   $valors += (( $m_valor[$ind]/12 ) / 30 * $nsaldo);
		     } else {
			   $valors +=  $m_valor[$ind] / 12;
		     }
		     
		  } else if( $vtipo == "9" && $m_media[$ind] != 0) {
		  	 
		     if ( !db_empty($rubricas[0]["rh27_form"])) {
			   $quants = ( $m_quant[$ind] / 12 ) * $m_media[$ind];
		     } else {
			   $valors = ( $m_valor[$ind] /12 ) * $m_media[$ind] ;
		     }
		     
		  }
	    }
	    
	 }
	    
	 if( bb_round($nsaldo,2) > 0 && ( bb_round($valors,2) > 0 || bb_round($quants,2) > 0 ) ) {

//echo "<BR> ind     1.1   --> $ind";	      
//echo "<BR> matricula     --> ".db_str(db_val($m_rubr[$ind])+2000,4);	      
//echo "<BR> valors  1.1   --> $valors";	      
//echo "<BR> quants  1.1   --> $quants";

		$matriz2[1] = $matric;
		$matriz2[2] = db_str(db_val($m_rubr[$ind])+2000,4);
		$matriz2[3] = round($valors,2);
		$matriz2[4] = round($quants,2);
		$matriz2[5] = $lotacaoatual;
		$matriz2[6] = strtoupper($tpp);
		$matriz2[7] = db_val( db_substr( $subpes,1,4 ) );
		$matriz2[8] = db_val( db_substr( $subpes, -2 ) );
	    $matriz2[9] = db_getsession("DB_instit");
		$acao = "insere";
		
        if ($sigla == "r91") {
        	
          $matriz1[1] = "r91_regist";
          $matriz1[2] = "r91_rubric";
          $matriz1[3] = "r91_valor";
          $matriz1[4] = "r91_quant";
          $matriz1[5] = "r91_lotac";
          $matriz1[6] = "r91_tpp";
          $matriz1[7] = "r91_anousu";
          $matriz1[8] = "r91_mesusu";
          $matriz1[9] = "r91_instit";
		  if (strtolower($tpp) == "v") {
			$condicaoaux  = " and r91_regist = ".db_sqlformat( $matric );
			$condicaoaux .= " and r91_rubric = ".db_sqlformat( $matriz2[2] );
			$condicaoaux .= " and upper(r91_tpp) = ".db_sqlformat( strtoupper($tpp) );
			global $transacao;
			if( db_selectmax("transacao", "select * from pontoprovfe ".bb_condicaosubpes("r91_").$condicaoaux)){
			   $acao = "altera";
			   $matriz2[3] += round($transacao[0]["r91_valor"],2);
			   $matriz2[4] += round($transacao[0]["r91_quant"],2);
			}
		  }
		  
		  if ( $acao == "insere") {
		  	if ($db_debug == true) {
		  	  echo "[avalia_ponto_ferias_rescisao] Inserindo dados na tabela pontoprovfe...<br>";
		  	  echo "[avalia_ponto_ferias_rescisao] Campos: ";
		  	  echo "<pre>";
		  	  print_r($matriz1);
		  	  echo "</pre>";
		  	  echo "[avalia_ponto_ferias_rescisao] Valores: ";
		  	  echo "<pre>";
		  	  print_r($matriz2);
		  	  echo "</pre>";	
		  	}
			db_insert( "pontoprovfe",$matriz1, $matriz2 );
		  } else {
		  	if ($db_debug == true) {
		  	  echo "[avalia_ponto_ferias_rescisao] Alterando dados da tabela pontoprovfe...<br>";
		  	  echo "[avalia_ponto_ferias_rescisao] Campos: ";
		  	  echo "<pre>";
		  	  print_r($matriz1);
		  	  echo "</pre>";
		  	  echo "[avalia_ponto_ferias_rescisao] Valores: ";
		  	  echo "<pre>";
		  	  print_r($matriz2);
		  	  echo "</pre>";		  	  	
		  	}
			db_update( "pontoprovfe", $matriz1, $matriz2, bb_condicaosubpes("r91_").$condicaoaux );
		  }
		  
        } else {
        	
          $matriz1[1] = "r19_regist";
          $matriz1[2] = "r19_rubric";
          $matriz1[3] = "r19_valor";
          $matriz1[4] = "r19_quant";
          $matriz1[5] = "r19_lotac";
          $matriz1[6] = "r19_tpp";
          $matriz1[7] = "r19_anousu";
          $matriz1[8] = "r19_mesusu";
          $matriz1[9] = "r19_instit";
		  if ( strtolower($tpp) == "v") {
			$condicaoaux  = " and r19_regist = ".db_sqlformat( $matric );
			$condicaoaux .= " and r19_rubric = ".db_sqlformat( $matriz2[2] );
			$condicaoaux .= " and upper(r19_tpp) = ".db_sqlformat( strtoupper($tpp) );
			global $transacao;
			if( db_selectmax("transacao", "select * from pontofr ".bb_condicaosubpes("r19_").$condicaoaux)){
			   $acao = "altera";
			   $matriz2[3] += round($transacao[0]["r19_valor"],2);
			   $matriz2[4] += round($transacao[0]["r19_quant"],2);
			}
		  }
		  
		  if ( $acao == "insere") {
			db_insert( "pontofr",$matriz1, $matriz2 );
		  } else {
            db_update( "pontofr", $matriz1, $matriz2, bb_condicaosubpes("r19_").$condicaoaux );
		  }
		  
        }
	 }
  }
  
  if ($db_debug) {
  	echo "[avalia_ponto_ferias_rescisao] FIM DO PROCESSAMENTO... <BR>";
  }	  
}
?>