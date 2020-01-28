<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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


function pes4_geracalculo003($calcula_parcial=null,$calcula_pensao=null) {

 global $diversos, $chamada_geral_arquivo, $minha_calcula_pensao, $carregarubricas_geral, $subpes, $cfpess;
 global $carregarubricas, $chamada_geral, $quais_diversos, $db_debug, $db21_codcli, $r110_regisi, $r110_regisf;
 global $opcao_filtro, $faixa_lotac, $faixa_regis,$r110_lotaci,$r110_lotacf,$opcao_gml,$opcao_geral,$opcao_tipo;
 global $lotacao_faixa;
 
 global $quais_diversos;

 global $db_debug,$db21_codcli,$cfpess;

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
 	
   //echo "<BR> --------------------------------------------Primeira vez-------------------------------------";
   $chamada_geral = "n";
   $chamada_geral_arquivo = "";
   $calcula_pensao = "n";
   
 } else {
 	
    if ($calcula_pensao == "n") {
    	
	   if ($calcula_parcial != " ") {
         //echo "<BR> calculo_parcial -> $calcula_parcial";
         //echo "<BR>  calculo_pensao -> $calcula_pensao";
         //echo "<BR> --------------------------------------------Segunda vez 1-----------------------------------";
	     $r110_regisi = db_val(db_substr($calcula_parcial,1,6));
	     $r110_regisf = db_val(db_substr($calcula_parcial,-6));
	     $r110_lotaci = db_substr($calcula_parcial,7,4);
	     $r110_lotacf = db_substr($calcula_parcial,11,4);
	     $chamada_geral = "a";
	     //echo "<BR> 1 i $r110_regisi f $r110_regisf li $r110_lotaci lf $r110_lotacf";
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
	        //echo "<BR> 2 i $r110_regisi f $r110_regisf li $r110_lotaci lf $r110_lotacf";
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

  if ( $opcao_geral == 1) {
  	
	$sigla                 = "r10_";
	$sigla1                = "r14_";
	$qual_ponto            = "pontofs";
	$chamada_geral_arquivo = "gerfsal";
	
  } else if( $opcao_geral == 8) {
  	 
	$sigla                 = "r47_";
	$sigla1                = "r48_";
	$qual_ponto            = "pontocom";
	$chamada_geral_arquivo = "gerfcom";
	
  } else if( $opcao_geral == 2) {
  	 
	$sigla                 = "r21_";
	$sigla1                = "r22_";
	$qual_ponto            = "pontofa";
	$chamada_geral_arquivo = "gerfadi";
	
  } else if( $opcao_geral == 3) {
  	 
	$sigla                 = "r29_";
	$sigla1                = "r31_";
	$qual_ponto            = "pontofe";
	$chamada_geral_arquivo = "gerffer";
	
  } else if( $opcao_geral == 4) {
  	 
	$sigla                 = "r19_";
	$sigla1                = "r20_";
	$qual_ponto            = "pontofr";
	$chamada_geral_arquivo = "gerfres";
	
  } else if( $opcao_geral == 5) {
  	 
	$sigla                 = "r34_";
	$sigla1                = "r35_";
	$qual_ponto            = "pontof13";
	$chamada_geral_arquivo = "gerfs13";
	
  } else if( $opcao_geral == 10) {
  	 
	$sigla                 = "r90_";
	$sigla1                = "r53_";
	$qual_ponto            = "pontofx";
	$chamada_geral_arquivo = "gerffx";
	
  } else if( $opcao_geral == 11) {
  	 
	$sigla                 = "r91_";
	$sigla1                = "r93_";
	$qual_ponto            = "pontoprovfe";
	$chamada_geral_arquivo = "gerfprovfer";
	
  } else if( $opcao_geral == 12) {
  	 
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
  	
    //echo "<BR> foi chamado pelo programa";
	global $ajusta;
	$ajusta = false ;
	if(   $opcao_geral == 1 
	   || $opcao_geral == 8 
	   || $opcao_geral == 4 
	   || $opcao_geral == 3 
	   || $opcao_geral == 5  ){
	   $ajusta = true ;
	}
	
	if( $opcao_tipo == 2 || $opcao_tipo == 1 ) {

		 // Geral 

	   if ($opcao_tipo == 2 ) {
	   	
	   	  switch ($opcao_geral){
	   	  	case 1:
	   	  		
	   	  		if ($db_debug) {
	   	  		   echo "[pes4_geracalculo003] Excluindo dados da gerfsal, Condição: ".bb_condicaosubpes("r14_")." ...<br>";	
	   	  		}
	   	  		db_delete( "gerfsal", bb_condicaosubpes("r14_") );
	   	  		 
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

	   	  	case 2:

	   	  	   	if ($db_debug) {
	   	  	   	  echo "[pes4_geracalculo003] Excluindo dados da gerfadi, Condição: ".bb_condicaosubpes("r22_")." ...<br>";
	   	  	   	}
	   	  		db_delete( "gerfadi", bb_condicaosubpes("r22_") ) ;
	   	  	break;

	   	  	case 3:
	   	  		
	   	  		if ($db_debug) {
	   	  			echo "[pes4_geracalculo003] Excluindo dados da gerffer, Condição: ".bb_condicaosubpes("r31_")." ...<br>";
	   	  		}
	   	  		db_delete( "gerffer", bb_condicaosubpes("r31_")  ) ;

	   	  		if ($db_debug) {
	   	  			echo "[pes4_geracalculo003] Chamando função deleta_ajustes_calculogeral(f)...<br>";
	   	  		}	   	  		
	   	  		deleta_ajustes_calculogeral("f");
	   	  			   	  		
	   	  	break;
	   	  	
	   	  	case 4:
	   	  		
	   	  		if ($db_debug) {
	   	  			echo "[pes4_geracalculo003] Excluindo dados da gerfres, Condição: ".bb_condicaosubpes("r20_")." ...<br>";
	   	  		}
	   	  		db_delete( "gerfres", bb_condicaosubpes("r20_") );
	   	  		
	   	  		if ($db_debug) {
	   	  			echo "[pes4_geracalculo003] Chamando função deleta_ajustes_calculogeral(r)...<br>";
	   	  		}	   	  		
	   	  		deleta_ajustes_calculogeral("r");
	   	  		
	   	  	break;

	   	  	case 5:
	   	  		
	   	  		if ($db_debug) {
	   	  			echo "[pes4_geracalculo003] Excluindo dados da gerfs13, Condição: ".bb_condicaosubpes("r35_")." ...<br>";
	   	  		}
	   	  		db_delete( "gerfs13", bb_condicaosubpes("r35_") ) ;
	   	  		
	   	  		if ($db_debug) {
	   	  			echo "[pes4_geracalculo003] Chamando função deleta_ajustes_calculogeral(3)...<br>";
	   	  		}	   	  		
	   	  		deleta_ajustes_calculogeral("3");
	   	  		
	   	  	break;
	   	  	
	   	  	case 8:

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
	   	  	
	   	  	case 10:
	   	  		
	   	  		if ($db_debug) {
	   	  			echo "[pes4_geracalculo003] Excluindo dados da gerffx, Condição: ".bb_condicaosubpes("r53_")." ...<br>";
	   	  		}
	   	  		db_delete( "gerffx", bb_condicaosubpes("r53_") )  ;
	   	  		
            break;
            
	   	  	case 11:
	   	  		
	   	  		if ($db_debug) {
	   	  			echo "[pes4_geracalculo003] Excluindo dados da gerfprovfer, Condição: ".bb_condicaosubpes("r93_")." ...<br>";
	   	  		}	   	  		
	   	  		db_delete( "gerfprovfer", bb_condicaosubpes("r93_") )  ;
	   	  		
	   	  	break;	
	   	  	
	   	  	case 12:

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
	  if ($db_debug) {
	  	echo "[pes4_geracalculo003] Alterando os dados da tabela previden, Condição: ".bb_condicaosubpes("r60_")." ...<br>";
	  	echo "[pes4_geracalculo003] Campos:";
	  	print_r($matriz1);
	  	print_r($matriz2);
	  	echo "<br><br>";
	  }
	  db_update("previden", $matriz1, $matriz2, bb_condicaosubpes("r60_") );
	  
	  $matriz1 = array();
	  $matriz2 = array();
	  $matriz1[ 1 ] = "r61_altera";
	  $matriz2[ 1 ] = 'f';
	  if ($db_debug) {
	  	echo "[pes4_geracalculo003] Alterando os dados da tabela ajusteir, Condição: ".bb_condicaosubpes("r61_")." ...<br>";
	  	echo "[pes4_geracalculo003] Campos:";
	  	print_r($matriz1);
	  	print_r($matriz2);
	  	echo "<br><br>";
	  }
	  db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_") );
           
	  $minha_calcula_pensao = false;
	  
	  for ($icalc=1; $icalc < 3; $icalc++) {
	  	
	    if ($icalc == 1) {
	    	
		  if ($qual_ponto == "pontof13") {
		    $condicaoaux = " and ".$siglap."rubric = ".db_sqlformat( db_str( db_val( $cfpess[0]["r11_palime"] )+4000, 4,0) );
		  } else {
		    $condicaoaux  = " and ( ".$siglap."rubric = ".db_sqlformat( $cfpess[0]["r11_palime"] );
		    $condicaoaux .= "   or ".$siglap."rubric = ".db_sqlformat( db_str( db_val( $cfpess[0]["r11_palime"] )+2000, 4,0) ).")";
		  }
		  db_delete( $qual_ponto, bb_condicaosubpes( $siglap ) . $condicaoaux );
		  
	    }
	      
        if ( $icalc == 2  && $opcao_geral != 10 && $opcao_geral != 11 && $opcao_geral != 12) {
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
	    	
      	   if ( ( ($opcao_geral != 10 && $opcao_geral != 11 && $opcao_geral != 12 ) 
                || ( ($opcao_geral == 10 || $opcao_geral == 11 || $opcao_geral == 12 ) && $icalc == 1)) ) {
      	   	
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

		   if ($icalc ==1 || ($icalc == 2 && $minha_calcula_pensao)) {
		   
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
	      //echo "<BR> entrou ajuste ";
	        
          
          // R985 BASE DE PREVIDENCIA
          // R986 BASE PREVIDENCIA (13O SAL)
          // R987 BASE PREVIDENCIA S/FERIAS
          // R981 BASE IRF SALARIO
          // R982 BASE IRF 13O SAL (BRUTA) BASE -
          // R983 BASE IRF FERIAS BASE -
          
		  $y1 = ( ($opcao_geral == 1 || $opcao_geral == 8 )? 1: ( $opcao_geral == 5 ? 2: 3 ) );
		  
		  if ($icalc == 2 && $opcao_geral != 4) {
		  	
   		    $rubrica1 = ( ($opcao_geral == 1 || $opcao_geral == 8 ) ? "R985": ( $opcao_geral == 5 ? "R986": "R987" ) );
   		    $rubrica  = ( ($opcao_geral == 1 || $opcao_geral == 8 ) ? "R981": ( $opcao_geral == 5 ? "R982": "R983" ) );
          
	        //echo "<BR> entrando no ajusta_previdencia() --> $rubrica1";
		    ajusta_previdencia( $chamada_geral_arquivo, $rubrica1, $y1, $sigla1);
		    ajusta_irrf($chamada_geral_arquivo, $rubrica,$y1 ,$sigla1);
		    //echo "<BR> saiu do ajusta_previdencia()";
		    
          }
          
		  if ( ( $icalc == 2 ) && $opcao_geral == 1 ) {
		  	
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
		 
	    }
	      
	    if ( $opcao_geral == 2 ) {
		  break;
	    }

	  }
	  
	  return;
	  
	}
	
  }
     
  switch ($opcao_geral) {
     	 
    case 1:
    case 8:
    	
      if ($db_debug) {
      	 echo "Chamando a função gerfsal($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfsal($opcao_geral,$opcao_tipo);
      
    break;

    case 2:
    	
      if ($db_debug) {
      	echo "Chamando a função gerfadi($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfadi($opcao_geral,$opcao_tipo);
           		
    break;

    case 3:
    	
      if ($db_debug) {
      	echo "Chamando a função gerffer($opcao_geral,$opcao_tipo).... <br>";
      }
      gerffer($opcao_geral,$opcao_tipo);
           		
    break;
    
    case 4:
    	
      if ($db_debug) {
      	echo "Chamando a função gerfres($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfres($opcao_geral,$opcao_tipo);
           		
    break;

    case 5:
    	
      if ($db_debug) {
      	echo "Chamando a função gerfs13($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfs13($opcao_geral,$opcao_tipo);
           		
    break;	
    
    case 10:
    	
      if ($db_debug) {
      	echo "Chamando a função gerffx($opcao_geral,$opcao_tipo).... <br>";
      }
      gerffx($opcao_geral,$opcao_tipo);
           		
    break;

    case 11:
    	
      if ($db_debug) {
      	echo "Chamando a função gerfprovfer($opcao_geral,$opcao_tipo).... <br>";
      }
      gerfprovfer($opcao_geral,$opcao_tipo);
           		
    break;

    case 12:
    	
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
  			
}
?>