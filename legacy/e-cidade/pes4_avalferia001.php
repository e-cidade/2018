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


function horasextrascarazinho(){
 global $subpes,$subpes_ofi, $rubricas,$pessoal,$Ipessoal,$d08_carnes, $debug;
 global $m_rubr,$qten,$vlrn,$m_media,$quants ,$quantd,$m_valor,$m_quant;
 global $max,$m_tipo;
    $ind = db_ascan($m_rubr,"0004");
    if(!db_empty($ind)){
       $m_media[$ind] = 0;
       $m_valor[$ind] = 0;
       $m_quant[$ind] = 0;
       $qten[$ind] = 0;
       $vlrn[$ind] = 0;
    }
    $ind = db_ascan($m_rubr,"0005");
    if(!db_empty($ind)){
       $m_media[$ind] = 0;
       $m_valor[$ind] = 0;
       $m_quant[$ind] = 0;
       $qten[$ind] = 0;
       $vlrn[$ind] = 0;
    }
    $Ipessoal = 0;
    $subpes_antescarazi = $subpes_ofi;
    $indmes = db_val( db_substr( $subpes_ofi, 6, 2) );
    $indano = db_val( db_substr( $subpes_ofi, 1, 4) );
    for($i=1;$i<=6;$i++){
       $indmes--;
       if( $indmes < 1){
	  $indano --;
	  $indmes = 12;
       }
    }
    $meses_avaliados = 0;
    while(1==1){
       $subpes = db_str($indano,4)."/".db_str($indmes,2,0,"0");
	  $meses_avaliados ++;
	  $iencontrei = false;
	  $condicaoaux = " and r14_rubric in('0004','0005') and r14_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
	  global $gerfsal;      
	  if( db_selectmax( "gerfsal", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
	      for($Igerfsal=0;$Igerfsal<count($gerfsal);$Igerfsal++){
		 $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $gerfsal[$Igerfsal]["r14_rubric"] );
		 global $rubricas;
		 if( db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux) ){
		     $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1);
		     $ind = db_ascan($m_rubr,$gerfsal[$Igerfsal]["r14_rubric"]);
		     if( db_empty($ind)){
			$max += 1;
//echo "<BR> 6 incluindo rubrica  --> ".$gerfsal[$Igerfsal]["r14_rubric"] ." max --> $max";      
			$ind = $max;
			$m_rubr[$ind] = $gerfsal[$Igerfsal]["r14_rubric"];
			$m_tipo[$ind] = $tiporubrica;

			$m_media[$ind] = 0;
			$m_valor[$ind] = 0;
			$m_quant[$ind] = 0;

			$qten[$ind] = 0;
			$vlrn[$ind] = 0;
		     }
		     if( !(db_at($tiporubrica,"3-4-7") > 0) 
			  ||  ($gerfsal[$Igerfsal]["r14_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[$Ipessoal]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
			 if( ($gerfsal[$Igerfsal]["r14_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[$Ipessoal]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
			    $m_media[$ind] += 1;
       }else{
          if(db_at($tiporubrica,"3-4-7") > 0){
			       $iencontrei = true;
          }
			 }
			 $m_valor[$ind] += $gerfsal[$Igerfsal]["r14_valor"];
			 $m_quant[$ind] += $gerfsal[$Igerfsal]["r14_quant"];
		     }
		 }
	      }
	  }
	  $condicaoaux = " and r48_rubric in('0004','0005') and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
	  global $gerfcom;
	  if( db_selectmax( "gerfcom", "select * from gerfcom ".bb_condicaosubpes( "r48_" ).$condicaoaux )){
	     for($Igerfcom=0;$Igerfcom< count($gerfcom);$Igerfcom++){
		$condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $gerfcom[$Igerfcom]["r48_rubric"] );
		if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) ){
		    $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1);
		    $ind = db_ascan($m_rubr,$gerfcom[$Igerfcom]["r48_rubric"]);
		    if( db_empty($ind)){
			$max += 1;
//echo "<BR> 7 incluindo rubrica  --> ".$gerfcom[$Igerfcom]["r48_rubric"] ." max --> $max";      
			$ind = $max;
			$m_rubr[$ind] = $gerfcom[$Igerfcom]["r48_rubric"];
			$m_tipo[$ind] = $tiporubrica;
			$m_media[$ind] = 0;
			$m_valor[$ind] = 0;
			$m_quant[$ind] = 0;
			$qten[$ind] = 0;
			$vlrn[$ind] = 0;
		     }
		     if( !(db_at($tiporubrica,"3-4-7") > 0) 
			 ||  ($gerfcom[$Igerfcom]["r48_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[$Ipessoal]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
			if( ($gerfcom[$Igerfcom]["r48_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[$Ipessoal]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
			    $m_media[$ind] += 1;
      }else{
          if(db_at($tiporubrica,"3-4-7") > 0){
			       $iencontrei = true;
          }
			}
			$m_valor[$ind] += $gerfcom[$Igerfcom]["r48_valor"];
			$m_quant[$ind] += $gerfcom[$Igerfcom]["r48_quant"];
		    }
		 }
	      }
	   }
	   if( !$iencontrei){
		$condicaoaux  = " and r31_rubric in('0004','0005') and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
		global $gerffer;
		if( db_selectmax( "gerffer", "select * from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux )){
		   for($Igerffer=0;$Igerffer<count($gerffer);$Igerffer++){
		      $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $gerffer[$Igerffer]["r31_rubric"] );
		      if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) ){
			 $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1);
			 $ind = db_ascan($m_rubr,$gerffer[$Igerffer]["r31_rubric"]);
			 if( db_empty($ind)){
			    $max += 1;
//echo "<BR> 9 incluindo rubrica  --> ".$gerffer[$Igerffer]["r31_rubric"] ." max --> $max";      
			    $ind = $max;
			    $m_rubr[$ind] = $gerffer[$Igerffer]["r31_rubric"];
			    $m_tipo[$ind] = $rubricas[0]["rh27_calc1"];
			    $m_media[$ind] = 0;
			    $m_valor[$ind] = 0;
			    $m_quant[$ind] = 0;
			    $qten[$ind] = 0;
			    $vlrn[$ind] = 0;
			 }
			 if( !(db_at(db_str($rubricas[0]["rh27_calc1"],1),"3-4-7") > 0 ) 
			    ||  ($gerffer[$Igerffer]["r31_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[$Ipessoal]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
			  if( ($gerffer[$Igerffer]["r31_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[$Ipessoal]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) /2))){
			       $m_media[$ind] += 1;
			    }
			    $m_valor[$ind] += $gerffer[$Igerffer]["r31_valor"];
			    $m_quant[$ind] += $gerffer[$Igerffer]["r31_quant"];
			    if( db_at(db_str($rubricas[0]["rh27_calc1"],1),"1-2-7") > 0 ){
			       $qten[$ind] = $gerffer[$Igerffer]["r31_quant"];
			       $vlrn[$ind] = $gerffer[$Igerffer]["r31_valor"];
			    }
			    $iencontrei = true;
			 }
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
       if( ( $meses_avaliados >= 6 ) || (db_str($indano,4)."/".db_str($indmes,2,0,"0") ) == $subpes_ofi ){
	  break;
       }
     }

    $subpes = $subpes_antescarazi;
}
function zeraarray($indx){
  global $m_media,$m_valor,$m_quant,$qten,$vlrn, $debug;
  
  $m_media[$indx] = 0;
  $m_valor[$indx] = 0;
  $m_quant[$indx] = 0;
  $qten[$indx] = 0;
  $vlrn[$indx] = 0;
}

function dias_gozo_no_mes_inicial($ini_gozo,$fim_gozo,$mtipo,$nsaldo){

   global $cfpess, $nsaldo,$subpes,$d08_carnes, $debug;
   
   $anomes = db_substr($subpes,1,4).db_substr($subpes,6,2);
   $mes_ano1i = db_str(db_month($ini_gozo),2,0,"0")."/".db_str(db_year($ini_gozo),4);
   $ndias = ndias($mes_ano1i);
   
   if( db_month($ini_gozo) == 2 && $mtipo == "01" && !db_boolean($cfpess[0]["r11_recalc"]) ){
     $ndias = 30;
   }
   
   if (db_substr(db_dtos($ini_gozo),1,6) == $anomes && db_substr(db_dtos($fim_gozo),1,6) == $anomes ) {
          $nsalar = 30 - $nsaldo;
   } else if ( db_substr(db_dtos($ini_gozo),1,6) == $anomes && db_substr(db_dtos($fim_gozo),1,6) > $anomes ) {
     if ($ndias == 31 ) {
       $nsalar = db_datedif($ini_gozo,db_substr($ini_gozo,1,8)."01") - 1 ;
     } else if( $ndias == 30  ) { 
       $nsalar = db_datedif($ini_gozo,db_substr($ini_gozo,1,8)."01") ;
     } else {
	     if ( ( strtolower($cfpess[0]["r11_fersal"]) == "s" && !db_boolean($cfpess[0]["r11_recalc"]) && db_year($ini_gozo) == 1) && $mtipo == "09" ){
		      $nsalar = 0;
	     } else {
          $nsalar = 30 - ( $ndias-db_day($ini_gozo)+1 );
	     }
     }
   } else {
     $nsalar = 30;
   }
   
  return $nsalar; 
}

function levanta_ponto(){

  global $pessoal, $Ipessoal, $rubricas, $pontofx_,$subpes,$indano,$indmes,$d08_carnes, $debug; 
  global $max,$subpes,$indano,$indmes,$subpes_ant,$r30_peraf,$r30_peraf;
  global $m_rubr, $m_tipo, $m_media, $m_valor, $m_quant,$qten, $vlrn, $m_media, $m_valor, $m_quant;
  global $qten , $vlrn,$subpes_ofi;
  
      $meses_avaliados = 0;
      while(1==1){
         $subpes = db_str($indano,4) . "/" . db_str($indmes,2,0,"0");
//echo "<BR> 2 subpes   --> $subpes";      
         $meses_avaliados++;
         $tem_no_mes_tipo9 = false;
         $iencontrei = false;

         // Inicio - Retorna se a Matricula tem dias de Férias para avaliação das rubricas tipo 4 e 6

         $pesquise_no_fixo_tipo_6 = false;
         $sqldias_gozo_ferias  = "select coalesce( dias_gozo_ferias(";
         $sqldias_gozo_ferias .= $pessoal[0]["r01_regist"].",";
         $sqldias_gozo_ferias .= substr("#".$subpes,1,4).",";
         $sqldias_gozo_ferias .= substr("#".$subpes,6,2).",";
         $sqldias_gozo_ferias .= "ndias(".substr("#".$subpes,1,4)."," ;
         $sqldias_gozo_ferias .=       "".substr("#".$subpes,6,2)."), ".db_getsession("DB_instit")."), '0') as ferias" ;
         global $transacao1;
         if( db_selectmax( "transacao1",$sqldias_gozo_ferias )){
           $pesquise_no_fixo_tipo_6 = ($transacao1[0]["ferias"]>0?true:false);
         }
         // Fim - Retorna se a Matricula tem dias de Férias para avaliação das rubricas tipo 4 e 6

         $condicaoaux = " and r14_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
         global $gerfsal;
         if( db_selectmax("gerfsal", "select * from gerfsal ".bb_condicaosubpes("r14_").$condicaoaux )){
           for($Igerfsal=0;$Igerfsal<count($gerfsal);$Igerfsal++){
             $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $gerfsal[$Igerfsal]["r14_rubric"] );
             if( db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux )
                            && $rubricas[0]["rh27_calc1"] != 0 
                             ){
                     if( trim($d08_carnes) == "carazinho" && $pessoal[0]["r01_regime"] != 2 
                       && (db_at($gerfsal[$Igerfsal]["r14_rubric"],"0004-0005") > 0 )){
                       continue; 
                     }

                     $tiporub = db_str($rubricas[0]["rh27_calc1"],1) ;

                     if( db_at($tiporub, "4-6" ) > 0){
                        if($pesquise_no_fixo_tipo_6 == true && $rubricas[0]["rh27_tipo"] == 1){
                         // echo "<BR> salario  nao esta passando rubrica --> ".$gerfsal[$Igerfsal]["r14_rubric"]." tipo --> $tiporub , Fixo "; 
                          continue;
                        }
                        //echo "<BR> salario rubrica --> ".$gerfsal[$Igerfsal]["r14_rubric"]." tipo --> $tiporub , Fixo ou variavel --> ".$rubricas[0]["rh27_tipo"];
                     }   
                    // ver este caso
            		    $ind = db_ascan($m_rubr,$gerfsal[$Igerfsal]["r14_rubric"]);
              	    if( db_empty($ind)){
		      
                        $max += 1;
                        $ind = $max;
                        $m_rubr[$ind] = $gerfsal[$Igerfsal]["r14_rubric"];
                        $m_tipo[$ind] = $tiporub;
                        zeraarray($ind);
                     }
                    //echo "<BR> 1.0 - subpes   --> $subpes  tiporub --> $tiporub m_rubr --> ".$m_rubr[$ind];
                     if( $tiporub == "9"){
                        $tem_no_mes_tipo9 = true;
                        $m_media[$ind] += 1;
                        $m_valor[$ind] = $gerfsal[$Igerfsal]["r14_valor"];
                        $m_quant[$ind] = $gerfsal[$Igerfsal]["r14_quant"];

                     }else{
                        if(db_at($tiporub,"3-4-7")>0){
                           if( ($gerfsal[$Igerfsal]["r14_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
                    		      $iencontrei = true;
                              $m_media[$ind] += 1;
                              //echo "<BR> 1 - subpes   --> $subpes  tiporub --> $tiporub m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                              $quant_restomenos = ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
                              if( !db_empty($quant_restomenos)){
                                 $quant_resto = $gerfsal[$Igerfsal]["r14_quant"] - $quant_restomenos;
                                 while ($quant_resto > 0){
                                    if( $quant_resto >= ( $quant_restomenos /2 )){
                                      $m_media[$ind] += 1;
                                      //echo "<BR> 1 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                                      $quant_resto = $quant_resto - $quant_restomenos;
                                    }else{
                                      break;
                                    }
                                 }
                              }else{
                                 $m_media[$ind] += 1;
                              }
                           }else{
                    		          $iencontrei = false;
                              //echo "<BR> 1.1 - subpes   --> $subpes  tiporub --> $tiporub m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                           }
                        }
                        $m_valor[$ind] += $gerfsal[$Igerfsal]["r14_valor"];
                        $m_quant[$ind] += $gerfsal[$Igerfsal]["r14_quant"];
                        //echo "<BR> salario rubrica --> ".$gerfsal[$Igerfsal]["r14_rubric"]." m_quant --> ".$m_quant[$ind]." += ".$gerfsal[$Igerfsal]["r14_quant"];
                        if(db_at($tiporub,"1-2-7")>0){
                           $qten[$ind] = $gerfsal[$Igerfsal]["r14_quant"];
                           $vlrn[$ind] = $gerfsal[$Igerfsal]["r14_valor"];
                        }
                     }
                  }
               }
            }
            $condicaoaux = " and r48_regist = ".db_sqlformat($pessoal[0]["r01_regist"] );
      	    global $gerfcom;
            if( db_selectmax( "gerfcom", "select * from gerfcom ".bb_condicaosubpes("r48_").$condicaoaux )){
               for($Igerfcom=0;$Igerfcom<count($gerfcom);$Igerfcom++){
                  $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $gerfcom[$Igerfcom]["r48_rubric"] );
                  if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) 
                       && $rubricas[0]["rh27_calc1"] != 0 
                        ){
                     if( trim($d08_carnes) == "carazinho" && $pessoal[0]["r01_regime"] != 2 
              		       && ( db_at($gerfcom[$Igerfcom]["r48_rubric"],"0004-0005") > 0 )){
                       continue; 
                    }
                     $tiporub = db_str($rubricas[0]["rh27_calc1"],1) ;
		     //ver depois
                     $ind = db_ascan($m_rubr,$gerfcom[$Igerfcom]["r48_rubric"]);

                     if( db_at($tiporub, "4-6" ) > 0){
                        if($pesquise_no_fixo_tipo_6 == true && $rubricas[0]["rh27_tipo"] == 1){
                          //echo "<BR> complementar nao esta passando rubrica --> ".$gerfcom[$Igerfcom]["r48_valor"]." tipo --> $tiporub , e Fixo "; 
                          continue;
                        }
                       //echo "<BR> complementar rubrica --> ".$gerfcom[$Igerfcom]["r48_valor"]." tipo --> $tiporub , Fixo ou variavel --> ".$rubricas[0]["rh27_tipo"]; 
                     }   
                     if( db_empty($ind)){
                        $max += 1;
                        $ind = $max;
                        $m_rubr[$ind] = $gerfcom[$Igerfcom]["r48_rubric"];
                        $m_tipo[$ind] = $tiporub;
                        zeraarray($ind);
                     }
                     if( $tiporub == "9"){
                        if( !$tem_no_mes_tipo9){
                            $m_valor[$ind] = $gerfcom[$Igerfcom]["r48_valor"];
                            $m_quant[$ind] = $gerfcom[$Igerfcom]["r48_quant"];
                            $tem_no_mes_tipo9 = true;
                            $m_media[$ind] += 1;
//echo "<BR> 2 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                        }
                     }else{
                        if( db_at($tiporub, "3-4-7" ) > 0){
                           if( ($gerfcom[$Igerfcom]["r48_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
                    		      $iencontrei = true;
                              $m_media[$ind] += 1;
                              //echo "<BR> 2 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                              $quant_restomenos = ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
                              if( !db_empty($quant_restomenos)){
                                 $quant_resto = $gerfcom[$Igerfcom]["r48_quant"] - $quant_restomenos;
                                 while ($quant_resto > 0){
                                    if( $quant_resto >= ( $quant_restomenos /2 )){
                                         $m_media[$ind] += 1;
                                         //echo "<BR> 2 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                                         $quant_resto = $quant_resto - $quant_restomenos;
                                    }else{
                                         break;
                                    }
                                 }
                              }else{
                                 $m_media[$ind] += 1;
                              }
                           }else{
                    		          $iencontrei = false;
                              //echo "<BR> 1.1 - subpes   --> $subpes  tiporub --> $tiporub m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                           }
                        }
                        $m_valor[$ind] += $gerfcom[$Igerfcom]["r48_valor"];
                        $m_quant[$ind] += $gerfcom[$Igerfcom]["r48_quant"];
                        //echo "<BR> m_quant --> ".$m_quant[$ind]." += ".$gerfcom[$Igerfcom]["r48_quant"];
                        if( db_at( $tiporub , "1-2-7")>0){
                           $qten[$ind] = $gerfcom[$Igerfcom]["r48_quant"];
                           $vlrn[$ind] = $gerfcom[$Igerfcom]["r48_valor"];
                        }
                     }
                  }
               }
            }
             if( !$iencontrei ){
               $condicaoaux = " and r90_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
      	       global $pontofx;
               db_selectmax( "pontofx", "select * from pontofx ".bb_condicaosubpes( "r90_" ).$condicaoaux );
               for($Ipontofx=0;$Ipontofx<count($pontofx);$Ipontofx++){
                  $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
                  if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) 
                            && $rubricas[0]["rh27_calc1"] != 0 
                             ){
                     $tiporub = db_str($rubricas[0]["rh27_calc1"],1) ;

		     // ver depois
                     $ind = db_ascan($m_rubr,$pontofx[$Ipontofx]["r90_rubric"]);
                     if( db_at($tiporub, "4-6" ) > 0){
                        if(($pesquise_no_fixo_tipo_6 == false && $rubricas[0]["rh27_tipo"] == 1) || $rubricas[0]["rh27_tipo"] == 2 ){
                          //echo "<BR> fixo  nao esta passando rubrica --> ".$pontofx[$Ipontofx]["r90_rubric"]." tipo --> $tiporub , e Fixo "; 
                          continue;
                        }
                        //echo "<BR> fixo rubrica --> ".$pontofx[$Ipontofx]["r90_rubric"]." tipo --> $tiporub , Fixo ou variavel  --> ".$rubricas[0]["rh27_tipo"]; 
                     }   
                     if( db_empty($ind)){
                        $max += 1;
                        $ind = $max;
                        $m_rubr[$ind] = $pontofx[$Ipontofx]["r90_rubric"];
                        $m_tipo[$ind] = $tiporub;
                        zeraarray($ind);
                     }
                     if( $tiporub == "9"){
                        $m_media[$ind] += 1;
                        $m_valor[$ind] = $pontofx[$Ipontofx]["r90_valor"]   ;
                        $m_quant[$ind] = $pontofx[$ipontofx]["r90_quant"]   ;
                     }else{
                        $m_media[$ind] += 1;
//echo "<BR> 4 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$pontofx[$Ipontofx]["r90_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                        $m_valor[$ind] += $pontofx[$Ipontofx]["r90_valor"]   ;
                        $m_quant[$ind] += $pontofx[$Ipontofx]["r90_quant"]   ;
                        //echo "<BR> m_quant --> ".$m_quant[$ind]." += ".$pontofx[$Ipontofx]["r90_valor"];
                     }
                     if( db_at($tiporub,"1-2-7")>0){
                        $qten[$ind] = $pontofx[$Ipontofx]["r90_quant"];
                        $vlrn[$ind] = $pontofx[$Ipontofx]["r90_valor"];
                     }
                  }
               }
           }    
         $testa_mes = db_month($r30_peraf);
         $testa_ano = db_year($r30_peraf) ;
         $dias_peraf = db_str($testa_mes,2,0,"0") . "/" .db_str($testa_ano,4,0,"0");
         if( db_day($r30_peraf) <= 14 || ( db_day($r30_peraf) <= 15 && ndias( $dias_peraf ) == 31 ) ){
            $testa_mes = db_month($r30_peraf)-1;
            if( $testa_mes <= 0){
               $testa_mes = 12;
               $testa_ano -= 1;
            }
         }
         if( $indano == $testa_ano && $indmes == $testa_mes){
            break;
         }
         if( $indmes < 12){
            $indmes += 1;
         }else{
            $indano += 1;
            $indmes = 1;
         }
         if(  ( $meses_avaliados >= 12 ) || ( db_str($indano,4)."/".db_str($indmes,2,0,"0") ) == $subpes_ofi 
              || ( db_val(db_substr($subpes_ofi,1,4)) < $indano 
                     || ( db_val(db_substr($subpes_ofi,6,2)) < $indmes 
                             && db_val(db_substr($subpes_ofi,1,4)) == $indano )) ){
            break;
         }
      }
// exit;     
}

function horasextrasguaiba(){
 global $subpes,$subpes_ofi, $rubricas,$pessoal,$Ipessoal,$d08_carnes, $debug;
 global $m_rubr,$qten,$vlrn,$m_media,$quants ,$quantd,$m_valor,$m_quant;
 global $max,$m_tipo;
  $m_rubr = array();
  $m_quant= array();
  $m_valor= array();
  $m_media= array();
  $m_tipo = array();
  $qten   = array();
  $vlrn   = array();
  $max    = 0 ;
    $Ipessoal = 0;
    $subpes_antesguaiba = $subpes_ofi;
    $indmes = db_val( db_substr( $subpes_ofi, 6, 2) );
    $indano = db_val( db_substr( $subpes_ofi, 1, 4) );
    for($i=1;$i<=12;$i++){
       $indmes--;
       if( $indmes < 1){
	  $indano --;
	  $indmes = 12;
       }
    }
    $meses_avaliados = 0;
    while(1==1){
       $subpes = db_str($indano,4)."/".db_str($indmes,2,0,"0");
//echo "<BR> subpes --> $subpes";
	  $meses_avaliados ++;
	  $iencontrei = false;
	  $condicaoaux = " and r14_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
	  global $gerfsal;      
	  if( db_selectmax( "gerfsal", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
	  	
	      for($Igerfsal=0;$Igerfsal<count($gerfsal);$Igerfsal++){
          $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $gerfsal[$Igerfsal]["r14_rubric"] );
          //echo "<BR r14_rubric --> ".$gerfsal[$Igerfsal]["r14_rubric"];
      	 global $rubricas;
         //echo "<BR> select * from rhrubricas ".bb_condicaosubpesproc("rh27_",$subpes_antesguaiba ).$condicaoaux;
     		 if( db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux)
		       ){
   		     $tiporub = db_str($rubricas[0]["rh27_calc1"],1);
           //echo "<BR> tiporubrica --> $tiporub";		     
  		     
   		     $ind = db_ascan($m_rubr,$gerfsal[$Igerfsal]["r14_rubric"]);
  		     
	  	     if( db_empty($ind)){
		           $max += 1;
	             $ind = $max;
               // //echo "<BR> guaiba max --> $max";
          	   $m_rubr[$ind] = $gerfsal[$Igerfsal]["r14_rubric"];
        		   $m_tipo[$ind] = $tiporub;

      	   	   $m_media[$ind] = 0;
      		   	 $m_valor[$ind] = 0;
       			   $m_quant[$ind] = 0;

			         $qten[$ind] = 0;
			         $vlrn[$ind] = 0;
		       }
// Incluir aqui          

            if( $tiporub == "9"){
               $tem_no_mes_tipo9 = true;
               $m_media[$ind] += 1;
               $m_valor[$ind] = $gerfsal[$Igerfsal]["r14_valor"];
               $m_quant[$ind] = $gerfsal[$Igerfsal]["r14_quant"];

            }else{
               if(db_at($tiporub,"3-4-7")>0){
                  if( ($gerfsal[$Igerfsal]["r14_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
             	       $iencontrei = true;
                     $m_media[$ind] += 1;
                     //echo "<BR> 1 - subpes   --> $subpes  tiporub --> $tiporub m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                     $quant_restomenos = ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
                     if( !db_empty($quant_restomenos)){
                        $quant_resto = $gerfsal[$Igerfsal]["r14_quant"] - $quant_restomenos;
                        while ($quant_resto > 0){
                           if( $quant_resto >= ( $quant_restomenos /2 )){
                             $m_media[$ind] += 1;
                             //echo "<BR> 1 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                             $quant_resto = $quant_resto - $quant_restomenos;
                           }else{
                             break;
                           }
                        }
                     }else{
                        $m_media[$ind] += 1;
                     }
                  }else{
             	          $iencontrei = false;
                     //echo "<BR> 1.1 - subpes   --> $subpes  tiporub --> $tiporub m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                  }
               }
               $m_valor[$ind] += $gerfsal[$Igerfsal]["r14_valor"];
               $m_quant[$ind] += $gerfsal[$Igerfsal]["r14_quant"];
               if(db_at($tiporub,"1-2-7")>0){
                  $qten[$ind] = $gerfsal[$Igerfsal]["r14_quant"];
                  $vlrn[$ind] = $gerfsal[$Igerfsal]["r14_valor"];
               }
            }
         }   
       } 
    }
       
	  $condicaoaux = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
	  global $gerfcom;
	  if( db_selectmax( "gerfcom", "select * from gerfcom ".bb_condicaosubpes( "r48_" ).$condicaoaux )){
	     for($Igerfcom=0;$Igerfcom< count($gerfcom);$Igerfcom++){
    		  $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $gerfcom[$Igerfcom]["r48_rubric"] );
		      if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) ){
		         $tiporub = db_str($rubricas[0]["rh27_calc1"],1);
		         $ind = db_ascan($m_rubr,$gerfcom[$Igerfcom]["r48_rubric"]);
		         if( db_empty($ind)){
		            $max += 1;
			          $ind = $max;
                //echo "<BR> guaiba 1 max --> $max";
  							$m_rubr[$ind] = $gerfcom[$Igerfcom]["r48_rubric"];
							  $m_tipo[$ind] = $tiporub;
								$m_media[$ind] = 0;
								$m_valor[$ind] = 0;
								$m_quant[$ind] = 0;
								$qten[$ind] = 0;
								$vlrn[$ind] = 0;
		         }
// Incluir aqui

            if( $tiporub == "9"){
               if( !$tem_no_mes_tipo9){
                   $m_valor[$ind] = $gerfcom[$Igerfcom]["r48_valor"];
                   $m_quant[$ind] = $gerfcom[$Igerfcom]["r48_quant"];
                   $tem_no_mes_tipo9 = true;
                   $m_media[$ind] += 1;
                   //echo "<BR> 2 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
               }
            }else{
               if( db_at($tiporub, "3-4-7" ) > 0){
                  if( ($gerfcom[$Igerfcom]["r48_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/2))){
             	       $iencontrei = true;
                     $m_media[$ind] += 1;
                     //echo "<BR> 2 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                     $quant_restomenos = ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
                     if( !db_empty($quant_restomenos)){
                        $quant_resto = $gerfcom[$Igerfcom]["r48_quant"] - $quant_restomenos;
                        while ($quant_resto > 0){
                           if( $quant_resto >= ( $quant_restomenos /2 )){
                                $m_media[$ind] += 1;
                                //echo "<BR> 2 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                                $quant_resto = $quant_resto - $quant_restomenos;
                           }else{
                                break;
                           }
                        }
                     }else{
                        $m_media[$ind] += 1;
                     }
                  }else{
             	          $iencontrei = false;
                     //echo "<BR> 1.1 - subpes   --> $subpes  tiporub --> $tiporub m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                  }
               }
               $m_valor[$ind] += $gerfcom[$Igerfcom]["r48_valor"];
               $m_quant[$ind] += $gerfcom[$Igerfcom]["r48_quant"];
               if( db_at( $tiporub , "1-2-7")>0){
                  $qten[$ind] = $gerfcom[$Igerfcom]["r48_quant"];
                  $vlrn[$ind] = $gerfcom[$Igerfcom]["r48_valor"];
               }
            }
	        }
       }
    }
       
// Incluir aqui
//    if( !$iencontrei ){
       $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[0]["r01_regist"] );
	     global $gerffer;
       if( db_selectmax( "gerffer", "select * from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux )){
       	   
          for($Igerffer=0;$Igerffer<count($gerffer);$Igerffer++){
          	
       	     $gerffer[$Igerffer]["r31_rubric"] = str_pad(($gerffer[$Igerffer]["r31_rubric"]-2000),4,'0',STR_PAD_LEFT);
       	     
             $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat($gerffer[$Igerffer]["r31_rubric"]);
             if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux )
                       && $rubricas[0]["rh27_calc1"] != 0 
                        ){
             if( trim($d08_carnes) == "carazinho" && $pessoal[0]["r01_regime"] != 2 
    	       && ( db_at($gerffer[$Igerffer]["r31_rubric"],"0004-0005") > 0 )){
               continue; 
            }
                $tiporub = db_str($rubricas[0]["rh27_calc1"],1) ;

          			// ver depois
                $ind = db_ascan($m_rubr,$gerffer[$Igerffer]["r31_rubric"]);
                
                if( db_empty($ind)){
                   $max += 1;
                   $ind = $max;
                   $m_rubr[$ind] = $gerffer[$Igerffer]["r31_rubric"];
                   $m_tipo[$ind] = $tiporub;
                   zeraarray($ind);
                }
                
                if( $tiporub == "9"){
                   if( $tem_no_mes_tipo9 == false){
                       $m_valor[$ind] = $gerffer[$Igerffer]["r31_valor"];
                       $m_quant[$ind] = $gerffer[$Igerffer]["r31_quant"];
                       $tem_no_mes_tipo9 = true;
                       $m_media[$ind] += 1;
                       //echo "<BR> 3 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                   }
                }else{
                   if( db_at($tiporub , "3-4-7" )>0 ){
                      if( ($gerffer[$Igerffer]["r31_quant"] >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) /2))){
                      	
             	           $iencontrei = true;
             	           $aRubricasEncontrei[$gerffer[$Igerffer]["r31_rubric"]] = true; 
             	           
                         $m_media[$ind] += 1;
                         //echo "<BR> 3 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                         $quant_restomenos = ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
                         if( !db_empty($quant_restomenos)){
                            $quant_resto = $gerffer[$Igerffer]["r31_quant"] - ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
                            while ($quant_resto > 0){
                               if( $quant_resto >= (($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) /2)){
                                    $m_media[$ind] += 1;
                                    //echo "<BR> 3 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                                    $quant_resto = $quant_resto - ($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) ;
                               }else{
                                    break;
                               }
                            }
                         }else{
                            $m_media[$ind] += 1;
                         }
                      }else{
                      	
            		         $iencontrei = false;
            		         
                         //echo "<BR> 1.1 - subpes   --> $subpes  tiporub --> $tiporub m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$gerfsal[$Igerfsal]["r14_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                      }
                   }   
                   $m_valor[$ind] += $gerffer[$Igerffer]["r31_valor"];
                   $m_quant[$ind] += $gerffer[$Igerffer]["r31_quant"];
                   if( db_at( $tiporub , "1-2-7")>0){
                      $qten[$ind] = $gerffer[$Igerffer]["r31_quant"];
                      $vlrn[$ind] = $gerffer[$Igerffer]["r31_valor"];
                   }
                }
             }
          }
       }
//    }

    //if( !$iencontrei ){
    
       
        $condicaoaux = " and r90_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
        global $pontofx;
        db_selectmax( "pontofx", "select * from pontofx ".bb_condicaosubpes( "r90_" ).$condicaoaux );
        
        for($Ipontofx=0;$Ipontofx<count($pontofx);$Ipontofx++){

        	if (array_key_exists(db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"]),$aRubricasEncontrei)){
        		continue;
        	}
        	
        	
           $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
           if( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) 
                     && $rubricas[0]["rh27_calc1"] != 0 
                      ){
              $tiporub = db_str($rubricas[0]["rh27_calc1"],1) ;
		          // ver depois
              $ind = db_ascan($m_rubr,$pontofx[$Ipontofx]["r90_rubric"]);
              if( db_empty($ind)){
                 $max += 1;
                 $ind = $max;
                 $m_rubr[$ind] = $pontofx[$Ipontofx]["r90_rubric"];
                 $m_tipo[$ind] = $tiporub;
                 zeraarray($ind);
              }
              if( $tiporub == "9"){
                 $m_media[$ind] += 1;
                 $m_valor[$ind] = $pontofx[$Ipontofx]["r90_valor"]   ;
                 $m_quant[$ind] = $pontofx[$ipontofx]["r90_quant"]   ;
              }else{
                 $m_media[$ind] += 1;
                 //echo "<BR> 4 - subpes   --> $subpes  m_rubr --> ".$m_rubr[$ind]." m_media -->".$m_media[$ind]. " ".$pontofx[$Ipontofx]["r90_quant"]." >= ".$rubricas[0]["rh27_quant"]/2;
                 $m_valor[$ind] += $pontofx[$Ipontofx]["r90_valor"]   ;
                 $m_quant[$ind] += $pontofx[$Ipontofx]["r90_quant"]   ;
              }
              if( db_at($tiporub,"1-2-7")>0){
                 $qten[$ind] = $pontofx[$Ipontofx]["r90_quant"];
                 $vlrn[$ind] = $pontofx[$Ipontofx]["r90_valor"];
              }
           }
        }
     //}
     if( $indmes < 12){
  	   $indmes += 1;
     }else{
	     $indano += 1;
	     $indmes = 1;
     }
     if( ( $meses_avaliados >= 12 ) || (db_str($indano,4)."/".db_str($indmes,2,0,"0") ) == $subpes_ofi ){
	     break;
     }
   }

    $subpes = $subpes_antesguaiba;
//    //echo "<BR> m_rubr --> ".print_r($m_rubr);
//    //echo "<BR> qten --> ".print_r($qten);
}

function acrescentapontofx (){
  
  global $pessoal, $Ipessoal, $rubricas, $pontofx_, $debug; 
  global $max;
  
  global $m_rubr, $m_tipo, $m_media, $m_valor, $m_quant,$qten, $vlrn, $m_media, $m_valor, $m_quant;
  global $qten , $vlrn;
  
      $condicaoaux = " and r90_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
      db_selectmax( "pontofx_", "select * from pontofx ".bb_condicaosubpes( "r90_" ).$condicaoaux );
      
      for($Ipontofx=0;$Ipontofx<count($pontofx_);$Ipontofx++){
         $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $pontofx_[$Ipontofx]["r90_rubric"] );
         if(db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux )
                   && $rubricas[0]["rh27_calc1"] != 0 ){
            $tiporubrica = db_str($rubricas[0]["rh27_calc1"],1) ;
	    
            // ** atencao **
	    // ver depois
            $ind = db_ascan($m_rubr,$pontofx_[$Ipontofx]["r90_rubric"]);
	    
            if( db_empty($ind)){
               if(db_at($tiporubrica,"1-3-7")>0 ){
               $max += 1;
               $ind = $max;
//echo "<BR> 1 incluindo rubrica  --> ".$pontofx_[$Ipontofx]["r90_rubric"] ." max --> $max";      
               $m_rubr[$ind] = $pontofx_[$Ipontofx]["r90_rubric"];
               $m_tipo[$ind] = $tiporubrica;
               $m_media[$ind] = 0;
               $m_valor[$ind] = 0;
               $m_quant[$ind] = 0;
               $qten[$ind] = 0;
               $vlrn[$ind] = 0;
               $m_media[$ind] += 0;
               $m_valor[$ind] += $pontofx_[$Ipontofx]["r90_valor"];
               $m_quant[$ind] += $pontofx_[$Ipontofx]["r90_quant"];
	       
                  $qten[$ind] = $pontofx_[$Ipontofx]["r90_quant"];
                  $vlrn[$ind] = $pontofx_[$Ipontofx]["r90_valor"];
               } 
            }else{
                if( db_at($tiporubrica,"1-2-7")>0 ){
                    $qten[$ind] = $pontofx_[$Ipontofx]["r90_quant"];
                    $vlrn[$ind] = $pontofx_[$Ipontofx]["r90_valor"];
                 }
            }
         }
      }
}

function avalia_ponto($qmeses)
{
  
  global $pessoal, $Ipessoal, $rubricas, $pontofx,$subpes,$m_quant, $debug;
  global $m_rubr,$qten,$vlrn,$m_media,$quants ,$quantd,$m_valor;
  global $nsaldo, $nabono, $dias_adi,$nsaldo_anterior,$max,$matric,$d08_carnes,$mdabo;
  //echo "<BR> avalia_ponto() qmeses --> $qmeses";
  //echo "<BR> 1.4- nsaldo   --> ".$nsaldo;
  //echo "<BR> 1.4- dias_adi --> ".$dias_adi;
  //echo "<BR> 1.4- nabono   --> ".$nabono;
  //echo "<BR> 1.4- max   --> ".$max;
  $nm13 = 1;
  for ($ind=1; $ind<=$max; $ind++) {
    
    //echo "<BR> avalia_ponto() ind --> $ind";
    //echo "<BR> avalia_ponto() rubrica --> ".$m_rubr[$ind];
    //echo "<BR> 1.4- m_valor  ".$m_valor[$ind];
    //echo "<BR> 1.4- m_quant  ".$m_quant[$ind];
    //echo "<BR> 1.4- qten     ".$qten[$ind];
    //echo "<BR> 1.4- vlrn     ".$vlrn[$ind];
    
    $quants = 0 ;
    $valors = 0 ;
    $quantd = 0 ;
    $valord = 0 ;
    $quanta = 0 ;
    $valora = 0 ;
    $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat($m_rubr[$ind]);
    if (db_selectmax("rubricas", "select * from rhrubricas ".$condicaoaux)) {
      $vtipo = db_str($rubricas[0]["rh27_calc1"],1);
      //echo "<BR> 1.4- vtipo $vtipo";
      if (trim($d08_carnes) == "carazinho" && $pessoal[0]["r01_regime"] != 2
      && (  db_at($m_rubr[$ind],"0004-0005") > 0 )) {
        $nm13 = 6;
      } else {
        $nm13 = $qmeses;
      }
      //	 $nm13 = $qmeses;
      //echo "<BR> 1.4- nm13 --> $nm13";
      $condicaoaux  = " and r90_regist = ".db_sqlformat($matric );
      $condicaoaux .= " and r90_rubric = ".db_sqlformat($m_rubr[$ind] );
      if (db_selectmax("pontofx", "select * from pontofx ".bb_condicaosubpes("r90_" ).$condicaoaux )) {
        if (db_at($vtipo , "1-2-7")>0) {
          if (!db_empty($rubricas[0]["rh27_form"])) {
            if (db_boolean($rubricas[0]["rh27_propq"] )) {
              $quants = ( ($qten[$ind] / 12 * $nm13) / 30) * $nsaldo ;
              $quantd = ( ($qten[$ind] / 12 * $nm13) / 30) * $dias_adi ;
              $quanta = ( $qten[$ind] / 12 * $nm13 / 30) * $nabono;
            } else {
              $quants = ( $qten[$ind] /30 * $nsaldo );
              $quanta = ( $qten[$ind] /30 * $nabono );
              $quantd = ( $qten[$ind] /30 * $dias_adi );
            }
            // Reis Alterado para atender a tarefa nro 4890 - 14/02/2007
            //      Obs : Se a rubrica for tipo 1 e tiver valor fazer a média dos valores da 
            //            mesma maneira que faz para a quantidade

            if(!db_empty($vlrn[$ind])){
              if (db_boolean($rubricas[0]["rh27_calcp"] )) {
                $valors = ( ($vlrn[$ind] / 12 * $nm13) /30) * $nsaldo;
                $valord = ( ($vlrn[$ind] / 12 * $nm13) /30) * $dias_adi;
                $valora = ( $vlrn[$ind] /12 * $nm13 /30) * $nabono;
              } else {
                $valors = ( $vlrn[$ind] /30 * $nsaldo );
                $valord = ( $vlrn[$ind] /30 * $dias_adi );
                $valora = ( $vlrn[$ind] /30 * $nabono );
              }
            }
          } else {
            if (db_boolean($rubricas[0]["rh27_calcp"] )) {
              $valors = ( ($vlrn[$ind] / 12 * $nm13) /30) * $nsaldo;
              $valord = ( ($vlrn[$ind] / 12 * $nm13) /30) * $dias_adi;
              $valora = ( $vlrn[$ind] /12 * $nm13 /30) * $nabono;
            } else {
              $valors = ( $vlrn[$ind] /30 * $nsaldo );
              $valord = ( $vlrn[$ind] /30 * $dias_adi );
              $valora = ( $vlrn[$ind] /30 * $nabono );
            }
          }
        } else if ((db_at($vtipo , "3-4")>0 ) && $m_quant[$ind] != 0 ) {
          if (!db_empty($rubricas[0]["rh27_form"])) {
            $quants = (($rubricas[0]["rh27_quant"]/$nsaldo_anterior*$nsaldo)/ 12) * $m_media[$ind];
            $quantd = (($rubricas[0]["rh27_quant"]/$nsaldo_anterior*$dias_adi) / 12) * $m_media[$ind];
            if ($nsaldo+$dias_adi != 30) {
              $quants = ($quants * $nsaldo_anterior) / 30;
              $quantd = ($quantd * $nsaldo_anterior) / 30;
            }
            $quanta = ($rubricas[0]["rh27_quant"] / 12) * $m_media[$ind] ;
            if ($nabono != 30) {
              $quanta = ($quanta * $nabono) / 30;
            }
          } else {
            $valors = (($pontofx[0]["r90_valor"]/$nsaldo_anterior*$nsaldo) / 12) * $m_media[$ind] ;
            $valord = (($pontofx[0]["r90_valor"]/$nsaldo_anterior*$dias_adi) / 12) * $m_media[$ind] ;
            if ($nsaldo+$dias_adi != 30) {
              $valors = ($valors * $nsaldo_anterior) / 30;
              $valord = ($valord * $nsaldo_anterior) / 30;
            }
            $valora = ($pontofx[0]["r90_valor"] / 12) * $m_media[$ind]  ;
            if ($nabono != 30) {
              $valora = ($valora * $nabono) / 30;
            }
          }
        } else if ($vtipo == "5" || $vtipo == "6") {
          if (!db_empty($rubricas[0]["rh27_form"])) {
            //echo "<BR> ind --> $ind $quants += ( $m_quant[$ind] / $nm13  / $nsaldo_anterior * $nsaldo )";
            $quants = ( $m_quant[$ind] / $nm13  / $nsaldo_anterior * $nsaldo );
            $quantd = ( $m_quant[$ind] / $nm13  / $nsaldo_anterior * $dias_adi );
            if ($nsaldo+$dias_adi != 30) {
              $quants = ($quants * $nsaldo_anterior) / 30;
              $quantd = ($quantd * $nsaldo_anterior) / 30;
            }
            $quanta = $m_quant[$ind] / $nm13 ;
            if ($nabono != 30) {
              $quanta = ($quanta * $nabono) / 30;
            }
          } else {
            $valors = ( $m_valor[$ind] / $nm13 / $nsaldo_anterior * $nsaldo ) ;
            $valord = ( $m_valor[$ind] / $nm13 / $nsaldo_anterior * $dias_adi );
            if ($nsaldo+$dias_adi != 30) {
              $valors = ($valors * $nsaldo_anterior) / 30 ;
              $valord = ($valord * $nsaldo_anterior) / 30 ;
            }
            $valora = $m_valor[$ind] / $nm13 ;
            if ($nabono != 30) {
              $valora = ($valora * $nabono) / 30 ;
            }
          }
        } else if ($vtipo == "8") {
          if (db_boolean($rubricas[0]["rh27_calcp"] )) {
            $valors = (( $m_valor[$ind] / $nm13 ) / 30 * $nsaldo);
            $valord = (( $m_valor[$ind] / $nm13 ) / 30 * $dias_adi);
            $valora = (( $m_valor[$ind] / $nm13 ) / 30 * $nabono);
          } else {
            $valors = ( $m_valor[$ind] / $nm13 / 30 * $nsaldo );
            $valord = ( $m_valor[$ind] / $nm13 / 30 * $dias_adi );
            $valora = ( $m_valor[$ind] / $nm13 / 30 * $nabono );
          }
        } else if ($vtipo == "9" && $m_media[$ind] != 0 ) {
          if (!db_empty($rubricas[0]["rh27_form"])) {
            $quants = (($pontofx[0]["r90_quant"]/$nsaldo_anterior*$nsaldo)/ 12) * $m_media[$ind];
            $quantd = (($pontofx[0]["r90_quant"]/$nsaldo_anterior*$dias_adi) / 12) * $m_media[$ind];
            if (($nsaldo+$dias_adi) != 30) {
              $quants = ($quants * $nsaldo_anterior) / 30;
              $quantd = ($quantd * $nsaldo_anterior) / 30;
            }
            $quanta = ($pontofx[0]["r90_quant"] / 12) * $m_media[$ind] ;
            if ($nabono != 30) {
              $quanta = ($quanta * $nabono) / 30;
            }
          } else {
            $valors = (($pontofx[0]["r90_valor"]/$nsaldo_anterior*$nsaldo) / 12) * $m_media[$ind] ;
            $valord = (($pontofx[0]["r90_valor"]/$nsaldo_anterior*$dias_adi) / 12) * $m_media[$ind] ;
            if (($nsaldo+$dias_adi) != 30) {
              $valors = ($valors * $nsaldo_anterior) / 30;
              $valord = ($valord * $nsaldo_anterior) / 30;
            }
            $valora = ($pontofx[0]["r90_valor"] / 12) * $m_media[$ind];
            if ($nabono != 30) {
              $valora = ($valora * $nabono) / 30;
            }
          }
        }
        
      } else {
        if ($vtipo == "2") {
          if (!db_empty($rubricas[0]["rh27_form"])) {
            if (db_boolean($rubricas[0]["rh27_propq"] )) {
              $quants  = ( $qten[$ind] /30 ) * $nsaldo;
              $quantd  = ( $qten[$ind] /30 ) * $dias_adi;
              $quanta  = ( $qten[$ind] /30 ) * $nabono;
            } else {
              $quants  = ( $qten[$ind] /30 ) * $nsaldo;
              $quantd  = ( $qten[$ind] /30 ) * $dias_adi;
              $quanta  = ( $qten[$ind] /30 ) * $nabono;
            }
          } else {
            if (db_boolean($rubricas[0]["rh27_calcp"])) {
              $valors  = ( $vlrn[$ind] /30 ) * $nsaldo;
              $valord  = ( $vlrn[$ind] /30 ) * $dias_adi;
              $valora  = ( $vlrn[$ind] /30 ) * $nabono;
            } else {
              $valors  = ( $vlrn[$ind] /30 ) * $nsaldo;
              $valord  = ( $vlrn[$ind] /30 ) * $dias_adi;
              $valora  = ( $vlrn[$ind] /30 ) * $nabono;
            }
          }
        } else if (db_at($vtipo , "4-7")>0 && $m_quant[$ind] != 0) {
          if (!db_empty($rubricas[0]["rh27_form"])) {
            $quants = ( (!db_empty($rubricas[0]["rh27_quant"])?($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) :($m_quant[$ind]/$m_media[$ind]) ) /12 * $m_media[$ind]  / $nsaldo_anterior * $nsaldo );
            $quantd = ( (!db_empty($rubricas[0]["rh27_quant"])?($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) :($m_quant[$ind]/$m_media[$ind]) ) /12 * $m_media[$ind]  / $nsaldo_anterior * $dias_adi );
            if (($nsaldo+$dias_adi) != 30 ) {
              $quants = ($quants * $nsaldo_anterior) / 30;
              $quantd = ($quantd * $nsaldo_anterior) / 30;
            }
            $quanta = (!db_empty($rubricas[0]["rh27_quant"])?($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"]) :($m_quant[$ind]/$m_media[$ind]) ) /12 *$m_media[$ind] ;
            if ($nabono != 30 ) {
              $quanta = ($quanta * $nabono) / 30;
            }
          } else {
            $valors = ( (!db_empty($rubricas[0]["rh27_quant"])?($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/$nm13:($m_valor[$ind]/$m_media[$ind])/12) * $m_media[$ind]  / $nsaldo_anterior * $nsaldo );
            $valord = ( (!db_empty($rubricas[0]["rh27_quant"])?($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/$nm13:($m_valor[$ind]/$m_media[$ind])/12) * $m_media[$ind]  / $nsaldo_anterior * $dias_adi );
            if (($nsaldo+$dias_adi) != 30) {
              $valors = ($valors * $nsaldo_anterior) / 30;
              $valord = ($valord * $nsaldo_anterior) / 30;
            }
            $valora = (!db_empty($rubricas[0]["rh27_quant"])?($rubricas[0]["rh27_quant"]>999?$pessoal[0]["r01_hrsmen"]:$rubricas[0]["rh27_quant"])/$nm13:($m_valor[$ind]/$m_media[$ind])/12) * $m_media[$ind] ;
            if ($nabono != 30) {
              $valora = ($valora * $nabono) / 30;
            }
          }
        } else if ($vtipo == "6") {
          if (!db_empty($rubricas[0]["rh27_form"])) {
            
            
            //echo "<BR> ind --> $ind $quants += ( $m_quant[$ind] / $nm13  / $nsaldo_anterior * $nsaldo )";
            $quants = ( $m_quant[$ind] / $nm13  / $nsaldo_anterior * $nsaldo );
            $quantd = ( $m_quant[$ind] / $nm13  / $nsaldo_anterior * $dias_adi );
            if ($nsaldo+$dias_adi != 30) {
              $quants = ($quants * $nsaldo_anterior) / 30;
              $quantd = ($quantd * $nsaldo_anterior) / 30;
            }
            $quanta = $m_quant[$ind] / $nm13 ;
            if ($nabono != 30) {
              $quanta = ($quanta * $nabono) / 30;
            }
          } else {
            $valors = ( $m_valor[$ind] / $nm13 / $nsaldo_anterior * $nsaldo ) ;
            $valord = ( $m_valor[$ind] / $nm13 / $nsaldo_anterior * $dias_adi );
            if ($nsaldo+$dias_adi != 30) {
              $valors = ($valors * $nsaldo_anterior) / 30 ;
              $valord = ($valord * $nsaldo_anterior) / 30 ;
            }
            $valora = $m_valor[$ind] / $nm13 ;
            if ($nabono != 30) {
              $valora = ($valora * $nabono) / 30 ;
            }
          }
        } else if ($vtipo == "8") {
          if (db_boolean($rubricas[0]["rh27_calcp"] )) {
            $valors = (( $m_valor[$ind] / $nm13 ) / 30 * $nsaldo);
            $valord = (( $m_valor[$ind] / $nm13 ) / 30 * $dias_adi);
            $valora = (( $m_valor[$ind] / $nm13 ) / 30 * $nabono);
          } else {
            $valors = ( $m_valor[$ind] / $nm13 / 30 * $nsaldo );
            $valord = ( $m_valor[$ind] / $nm13 / 30 * $dias_adi );
            $valora = ( $m_valor[$ind] / $nm13 / 30 * $nabono );
          }
        } else if ($vtipo == "9" && $m_media[$ind] != 0) {
          if (!db_empty($rubricas[0]["rh27_form"])) {
            $quants = (($m_quant[$ind]/$nsaldo_anterior*$nsaldo)/ 12) * $m_media[$ind];
            $quantd = (($m_quant[$ind]/$nsaldo_anterior*$dias_adi) / 12) * $m_media[$ind];
            if ($nsaldo+$dias_adi != 30) {
              $quants = ($quants * $nsaldo_anterior) / 30;
              $quantd = ($quantd * $nsaldo_anterior) / 30;
            }
            $quanta = ($m_quant[$ind] / 12) * $m_media[$ind] ;
            if ($nabono != 30) {
              $quanta = ($quanta * $nabono) / 30;
            }
          } else {
            $valors = (($m_valor[$ind]/$nsaldo_anterior*$nsaldo) / 12) * $m_media[$ind] ;
            $valord = (($m_valor[$ind]/$nsaldo_anterior*$dias_adi) / 12) * $m_media[$ind] ;
            if (($nsaldo+$dias_adi) != 30) {
              $valors = ($valors * $nsaldo_anterior) / 30 ;
              $valord = ($valord * $nsaldo_anterior) / 30 ;
            }
            $valora = ($m_valor[$ind] / 12) * $m_media[$ind] ;
            if ($nabono != 30) {
              $valora = ($valora * $nabono) / 30 ;
            }
          }
        }
      }
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
    if (bb_round($nsaldo,2) > 0 && ( bb_round($valors,2) > 0 || bb_round($quants,2) > 0 ) ) {
      
      $matriz2[1] = $matric;
      $matriz2[2] = db_str(db_val($m_rubr[$ind])+2000,4);
      $matriz2[3] = round($valors,2);
      $matriz2[4] = round($quants,2);
      $matriz2[5] = $pessoal[0]["r01_lotac"];
      $matriz2[6] = 0;
      $matriz2[7] = 0;
      $matriz2[8] = "F";
      $matriz2[9] = db_val(db_substr($subpes,1,4 ) );
      $matriz2[10] = db_val(db_substr($subpes, -2 ) );
      $matriz2[11] = db_getsession("DB_instit");
      
//      echo "<BR>rubrica -->".$matriz2[2];
//      echo "<BR>valors  -->".$matriz2[3];
//      echo "<BR>quant   -->".$matriz2[4];
//      echo "<BR>";
      
      db_insert("pontofe", $matriz1, $matriz2 );
    }
    if (bb_round($dias_adi,2) > 0 && (bb_round($valord,2) > 0 || bb_round($quantd,2) > 0) ) {
      
      $matriz2[1] = $matric;
      $matriz2[2] = db_str(db_val($m_rubr[$ind])+2000,4);
      $matriz2[3] = round($valord,2);
      $matriz2[4] = round($quantd,2);
      $matriz2[5] = $pessoal[0]["r01_lotac"];
      $matriz2[6] = 0;
      $matriz2[7] = 0;
      $matriz2[8] = "D";
      $matriz2[9] = db_val(db_substr($subpes,1,4 ) );
      $matriz2[10] = db_val(db_substr($subpes, -2 ) );
      $matriz2[11] = db_getsession("DB_instit");
      
      if(@$sigla == "r91"){
        db_insert("pontoprovfe", $matriz1, $matriz2 );
      }else{
        db_insert("pontofe", $matriz1, $matriz2 );
      }
    }
    if ($nabono > 0 && ($valora > 0 || $quanta > 0) &&
    ($rubricas[0]["rh27_pd"] == 1 || ($rubricas[0]["rh27_pd"] == 2 && $mdabo))) {
      
      $matriz2[1] = $matric;
      $matriz2[2] = db_str(db_val($m_rubr[$ind])+2000,4);
      $matriz2[3] = round($valora,2);
      $matriz2[4] = round($quanta,2);
      $matriz2[5] = $pessoal[0]["r01_lotac"];
      $matriz2[6] = 0;
      $matriz2[7] = 0;
      $matriz2[8] = "A";
      $matriz2[9] = db_val(db_substr($subpes,1,4 ) );
      $matriz2[10] = db_val(db_substr($subpes, -2 ) );
      $matriz2[11] = db_getsession("DB_instit");
      
      db_insert("pontofe", $matriz1, $matriz2 );
    }
  }
}


function gera_ponto_salario($diferenca_ferias=null){
 
  global $pessoal, $Ipessoal,$pontofx,$rubricas, $subpes,$subpes_pagamento,$cter,$pontofs,$r30_peri,$r30_perf, $debug;
  global $dias_adi, $nsaldo, $cter, $nsalar, $paga_13, $mpsal,$cadferia,$mtipo,$matric,$matriz1,$matriz2;
  global $r30_peri, $r30_perf,$cfpess,$nsalar, $r30_periodolivrefinal, $r30_periodolivreinicial;
	$valor_avaliado = 0;
	$qtd_avaliada = 0;

   //echo "<BR> nsaldo $nsaldo"; 
   //echo "<BR> mtipo $mtipo";
   if ($debug == true) {
     echo '$diferenca_ferias:'.$diferenca_ferias.'<br>';
     echo '$mtipo:'.$mtipo.'<br>';
   }
   if ($diferenca_ferias == "n") {
      
     if(db_at($mtipo,"01 02 03 04 05 06 07 09 12 13 14 15") > 0) {
       
        $nsalar = dias_gozo_no_mes_inicial($r30_peri,$r30_perf,$mtipo,$nsaldo);
        
     } else if($mtipo == "08" || $mtipo == "10"){
       
        $nsalar = 30;
        
     }   
     
   }
   
   if ($debug == true) {
     echo '$nsalar:'.$nsalar.'<br>'; 
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
  if( $nsaldo > 0 ){
     $matriz2[1] = $pessoal[0]["r01_regist"];
     $matriz2[2] = "R931";                  // R931 1/3 DE FERIAS
     $matriz2[3] = 0;                              
     $matriz2[4] = 1;
     $matriz2[5] = $pessoal[0]["r01_lotac"];
     $matriz2[6] = 12;
     $matriz2[7] = 1;
     $matriz2[8] = "F";
     $matriz2[9] = db_val( db_substr( $subpes,1,4 ) );
     $matriz2[10] = db_val( db_substr( $subpes, -2 ) );
     $matriz2[11] = db_getsession("DB_instit");

     db_insert("pontofe",$matriz1,$matriz2);
  }
  
  if( $dias_adi > 0 ){

     $matriz2[1] = $pessoal[0]["r01_regist"];
     $matriz2[2] = "R940";                   // R940 1/3 ADIANTAMENTO FERIAS
     $matriz2[3] = 0;
     $matriz2[4] = 1;
     $matriz2[5] = $pessoal[0]["r01_lotac"];
     $matriz2[6] = 12;
     $matriz2[7] = 1;
     $matriz2[8] = "D";
     $matriz2[9] = db_val( db_substr( $subpes,1,4 ) );
     $matriz2[10] = db_val( db_substr( $subpes, -2 ) );
     $matriz2[11] = db_getsession("DB_instit");
     db_insert("pontofe",$matriz1,$matriz2);
  }
  
    $matriz2[1] = $pessoal[0]["r01_regist"];
    $matriz2[2] = "R932";                      // R932 1/3 DE ABONO PECUNIARIO
    $matriz2[3] = 0;
    $matriz2[4] = 1;
    $matriz2[5] = $pessoal[0]["r01_lotac"];
    $matriz2[6] = 12;
    $matriz2[7] = 1;
    $matriz2[8] = "A";
    $matriz2[9] = db_val( db_substr( $subpes,1,4 ) );
    $matriz2[10] = db_val( db_substr( $subpes, -2 ) );
    $matriz2[11] = db_getsession("DB_instit");
    db_insert( "pontofe", $matriz1, $matriz2 );
  
   //echo "<BR> 4 - nsalar --> $nsalar";
  $nsalar_ant = $nsalar;
  if( $mpsal == true ){
   //echo "<BR> PAGA_13 --> $paga_13";
     if( $paga_13 == 't' && ($subpes_pagamento == $subpes) && strtolower($cfpess[0]["r11_fersal"]) == "s" ){
	     $nsalar = 30;
     }
  }
   //echo "<BR> PAGA_13 --> $paga_13";
   //echo "<BR> 5 - nsalar --> $nsalar";
   
  $condicaoaux = " and r90_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
  db_selectmax( "pontofx", "select * from pontofx ".bb_condicaosubpes( "r90_" ).$condicaoaux );
  for ($Ipontofx=0;$Ipontofx< count($pontofx);$Ipontofx++) {
    
     $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
     db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux );

     if ( $mpsal == false) {
	      if ( $nsalar >0) {
	         $valor_avaliado = 0;
	         $qtd_avaliada = 0;
	         if( $pontofx[$Ipontofx]["r90_valor"] > 0){
	            if( db_boolean($rubricas[0]["rh27_calcp"])){
	      	 $valor_avaliado = ($pontofx[$Ipontofx]["r90_valor"] / 30) * $nsalar;
	            }else{
	      	 $valor_avaliado = $pontofx[$Ipontofx]["r90_valor"];
	            }
	            if( db_boolean( $rubricas[0]["rh27_presta"] )){
	      	 $qtd_avaliada = $pontofx[$Ipontofx]["r90_quant"];
	            }
	         }
	         
	         if( $pontofx[$Ipontofx]["r90_quant"] > 0){
	            if( db_boolean($rubricas[0]["rh27_calcp"])){
	      	 if( db_boolean( $rubricas[0]["rh27_propq"])){
	      	    $qtd_avaliada = ($pontofx[$Ipontofx]["r90_quant"] / 30) * $nsalar;
	      	 }else{
	      	    $qtd_avaliada = $pontofx[$Ipontofx]["r90_quant"];
	      	 }
	            }else{
	      	 $qtd_avaliada = $pontofx[$Ipontofx]["r90_quant"];
	            }
	            if( db_boolean( $rubricas[0]["rh27_presta"] )){
	      	 $valor_avaliado = $pontofx[$Ipontofx]["r90_valor"];
	            }
	         }
	         
	         if ( $valor_avaliado != 0 || $qtd_avaliada != 0) {
        
	            $matriz2[1] = $pessoal[0]["r01_regist"];
	            $matriz2[2] = $pontofx[$Ipontofx]["r90_rubric"];
	            $matriz2[3] = round( $valor_avaliado,2);
	            $matriz2[4] = round( $qtd_avaliada,2);
	            $matriz2[5] = $pessoal[0]["r01_lotac"];
	            $matriz2[6] = 12;
	            $matriz2[7] = 1;
	            $matriz2[8] = "S";
	            $matriz2[9] = db_val( db_substr( $subpes,1,4 ) );
	            $matriz2[10] = db_val( db_substr( $subpes, -2 ) );
                    $matriz2[11] = db_getsession("DB_instit");
	            db_insert( "pontofe", $matriz1, $matriz2 );
	         }
	         
	      }
     } else {
       
       if ( $subpes_pagamento == $subpes) {
         
         if ($debug == true) {
           echo '<br>$nsalar('.$nsalar.') > 0??';
         }
	       if ( $nsalar >0) {
	       
  	       if ($debug == true) { 
  	         echo "SIM!! <br>";
  	       }
  	       
	           $valor_avaliado = 0;
	           $qtd_avaliada = 0;
	           if ( $pontofx[$Ipontofx]["r90_valor"] > 0) {
	             
		            if ( db_boolean($rubricas[0]["rh27_calcp"])){
		               $valor_avaliado = ($pontofx[$Ipontofx]["r90_valor"] / 30) * $nsalar;
		            } else {
		               $valor_avaliado = $pontofx[$Ipontofx]["r90_valor"];
		            }
		            
		            if ( db_boolean( $rubricas[0]["rh27_presta"] )) {
		              $qtd_avaliada = $pontofx[$Ipontofx]["r90_quant"];
		            }
		            
	           }
	           
	           if ( $pontofx[$Ipontofx]["r90_quant"] > 0) {
		            if(db_boolean($rubricas[0]["rh27_calcp"])){
		               if ( db_boolean( $rubricas[0]["rh27_propq"])){
		                 $qtd_avaliada = ($pontofx[$Ipontofx]["r90_quant"] / 30) * $nsalar;
		               } else {
		                 $qtd_avaliada = $pontofx[$Ipontofx]["r90_quant"];
		               }
		            } else {
		              $qtd_avaliada = $pontofx[$Ipontofx]["r90_quant"];
		            }
		            
		            if( db_boolean( $rubricas[0]["rh27_presta"] )) {
		                $valor_avaliado = $pontofx[$Ipontofx]["r90_valor"];
		            }
	           }
	       }

	       if ($debug == true) {
   	       echo "Valor_avaliado: $valor_avaliado<br>";
	         echo "Qtd_avaliada: $qtd_avaliada <br>";
	       }  
	       
	       if ($valor_avaliado != 0 || $qtd_avaliada != 0) {
		     
		        $acao = "altera";
		        $condicaoaux = " where rh27_instit = ".db_getsession("DB_instit")."  and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
		        if ( db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux ) &&  $rubricas[0]["rh27_tipo"] == 2 ) {
		          
		           
		           if($rubricas[0]["rh27_limdat"] == 'f') {

		             if ($debug == true) {
  		             echo "<br>1: rh27_limdat == 'f' <br>";  
		             }
		               
		              $condicaoaux  = " and r10_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
		              $condicaoaux .= " and r10_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
		              
		              if ( !db_selectmax( "pontofs", "select * from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )) {
			              $acao = "insere";
		              } else {
		                if ($debug == true) {
                      echo "continue; <br>";
                    }
			              continue;
		              }
		              
		           } else {
		             
		             if ($debug == true) { 
                   echo "<br>1: rh27_limdat != 'f' <br>"; 
                 }
		               
		              $condicaoaux  = " and r10_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
		              $condicaoaux .= " and r10_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
		              
		              if ( !db_selectmax( "pontofs", "select * from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )) {
			              $acao = "insere";
		              }
		           }
		           
		        } else {
		          
		           if ($debug == true) {
                 echo "<br>2:<br>"; 
               }
		           $condicaoaux  = " and r10_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
		           $condicaoaux .= " and r10_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
		           
		           if( !db_selectmax( "pontofs", "select * from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )) {
		              $acao = "insere";
		           }
		        }
		        
		        $matriz1ps = array();
		        $matriz2ps = array();
		        $matriz1ps[1] = "r10_regist";
		        $matriz1ps[2] = "r10_rubric";
		        $matriz1ps[3] = "r10_valor";
		        $matriz1ps[4] = "r10_quant";
		        $matriz1ps[5] = "r10_lotac";
		        $matriz1ps[6] = "r10_datlim";
		        $matriz1ps[7] = "r10_anousu";
		        $matriz1ps[8] = "r10_mesusu";
            $matriz1ps[9] = "r10_instit";
            
		        $matriz2ps[1] = $pessoal[0]["r01_regist"];
		        $matriz2ps[2] = $pontofx[$Ipontofx]["r90_rubric"];
		        $matriz2ps[3] = round( $valor_avaliado,2 );
		        $matriz2ps[4] = round( $qtd_avaliada, 2 );
		        $matriz2ps[5] = $pessoal[0]["r01_lotac"];
		        if( $rubricas[0]["rh27_limdat"] == 'f'){
		           $matriz2ps[6] = bb_space(7);
		        }else{
		           $matriz2ps[6] = $pontofx[$Ipontofx]["r90_datlim"];
		        }
		        $matriz2ps[7] = db_val( db_substr( $subpes,1,4 ) );
		        $matriz2ps[8] = db_val( db_substr( $subpes, -2 ) );
            $matriz2ps[9] = db_getsession("DB_instit");
            if ($debug == true) {
              echo "Acao: {$acao}<br>"; 
            }
		        if ( $acao == "insere") {
		          
		          if ($debug == true) {
                echo "<pre>";
		            echo "PONTOFS: <br>";
		            print_r($matriz1ps);
		            print_r($matriz2ps);
		            echo "</pre><br>";
              }   
		          
		           db_insert( "pontofs", $matriz1ps, $matriz2ps );
		           
		        } else {
		          
		           $condicaoaux  = " and r10_regist = ".db_sqlformat( $pessoal[0]["r01_regist"] );
		           $condicaoaux .= " and r10_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
		           db_update( "pontofs", $matriz1ps, $matriz2ps, bb_condicaosubpes( "r10_").$condicaoaux  );
		           
		        }
	       }
	       
	     }
	     
     }
     
  }
  
  $nsalar = $nsalar_ant;
  
}
?>