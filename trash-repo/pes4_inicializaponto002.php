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


function init_130($opcao){
  
   global $glm,$lotini,$lotfim,$matini,$matfim,$condicao,$max,$reg,$rubini,$rubfim, $pessoal, $Ipessoal,$tipofil;
   global $F019, $F018 , $F020, $F021 , $F023, $cadferia, $subpes, $d08_carnes, $cfpess, $dias_pagamento;
  
global $db_config,$sellotac,$campo_auxilio_regi;

db_selectmax("db_config","select lower(trim(munic)) as d08_carnes from db_config where codigo = ".db_getsession("DB_instit"));

$d08_carnes = $db_config[0]["d08_carnes"];

   $rubrica_licenca_saude = bb_space(4);

   // parametros de 14.salario;
   if( trim($d08_carnes) == "amparo"){

     // fonte se encontra no fpag501;
     // parametros_14salario(10,5,13,70);

   }


      if($opcao == 1){
         $arquivo = "pontofx";
         $sigla_pesquisa = "r90_";
      }else if($opcao == 2){
         $arquivo = "pontofa";
         $sigla_pesquisa = "r21_";
      }
      
      $condicaoaux  = "select ".$arquivo.".*, rh27_tipo,rh27_rubric,rh27_limdat,rh27_presta,rh27_calcp,rh27_propq from ".$arquivo." inner join rhrubricas  ";
      $condicaoaux .= "  on   ".$sigla_pesquisa."rubric = rh27_rubric " ;
      $condicaoaux .= "  and  ".$sigla_pesquisa."instit = rh27_instit ";
      $condicaoaux .= " where ".$sigla_pesquisa."anousu = ".db_sqlformat(db_substr($subpes,1,4));
      $condicaoaux .= "  and  ".$sigla_pesquisa."mesusu = ".db_sqlformat(db_val(db_substr($subpes,-2)));
      $condicaoaux .= "  and  ".$sigla_pesquisa."instit = ".DB_getsession("DB_instit");
      $condicaoaux .= "  and  ".$sigla_pesquisa."rubric >= ".db_sqlformat( $rubini );
      $condicaoaux .= "  and  ".$sigla_pesquisa."rubric <= ".db_sqlformat( $rubfim );

      
      if($glm == "g"){
         $condicao_selecao = " ";
      }else if($glm == "l"){
	       global $buscalotac;
	       if($tipofil == "i"){
		        db_selectmax( "buscalotac","select max(trim(TO_CHAR(R70_CODIGO,'9999'))),min(trim(TO_CHAR(R70_CODIGO,'9999'))) from rhlota where r70_instit = ".db_getsession("DB_instit")." and r70_estrut between '$lotini' and '$lotfim'");
		        $lotini = $buscalotac[0]["min"];
		        $lotfim = $buscalotac[0]["max"];
		        $condicaoaux .= " and ".$sigla_pesquisa."lotac >= ".db_sqlformat( $lotini ) ;
		        $condicaoaux .= " and ".$sigla_pesquisa."lotac <= ".db_sqlformat( $lotfim ) ;
	       }else{
		        db_selectmax( "buscalotac","select trim(TO_CHAR(R70_CODIGO,'9999')) as r13_codigo from rhlota where r70_instit = ".db_getsession("DB_instit") ." and r70_estrut in (".$campo_auxilio_lota.")");
		        $sellotac = "";
		        $vir="";
		        for($ibuscalotac = 0; $ibuscalotac<count($buscalotac); $ibuscalotac ++){
		          $sellotac .= $vir."'".$buscalotac[$ibuscalotac]["r13_codigo"]."'";
		          $vir = ",";
		        }
		        $condicaoaux .= " and ".$sigla_pesquisa."lotac in (".$sellotac.")" ;
	       }
      }else if($glm == "m"){
	       if($tipofil == "i"){
                  $condicao_selecao = " and ".$sigla_pesquisa."regist >= ".db_sqlformat( $matini ) ;
                  $condicao_selecao .= " and ".$sigla_pesquisa."regist <= ".db_sqlformat( $matfim ) ;
	       }else{
                  $condicao_selecao = " and ".$sigla_pesquisa."regist in (".$campo_auxilio_regi.")" ;
	       }

      }
      
      $max = 0;

      $condicaoaux .= $condicao_selecao;
      $condicaoaux .= " order by ".$sigla_pesquisa."regist ";
      global $arquivo_rubricas;
//echo "<BR> $condicaoaux";
      if( db_selectmax( "arquivo_rubricas",$condicaoaux) ){
         $max = count($arquivo_rubricas);
      }
     

      $sqlerro = false;
      $erro_msg = "Processamento concluído com sucesso.";

     
      if( $max <= 0){
				$sqlerro = true;
				$erro_msg = "Não existem funcionários nesta faixa de rubricas.";
      }else{

        $anomes = db_substr($subpes,1,4).db_substr($subpes,6,2);

        $dias_do_mes = ndias( db_substr($subpes,6,2)."/".db_substr($subpes,1,4,0,"0") );
        $dias_do_mes = ($dias_do_mes < 30? 30: $dias_do_mes);
        
        if($opcao == 1){
           // inicializa ponto de salarios;

           $condicaoaux = bb_condicaosubpes("r10_")." and r10_rubric >= ".db_sqlformat( $rubini );
           $condicaoaux .= " and r10_rubric <= ".db_sqlformat( $rubfim );
           if( $glm == "m"){
	           if($tipofil == "i"){
		           $condicaoaux .= " and r10_regist >= ".db_sqlformat( $matini ) ;
		           $condicaoaux .= " and r10_regist <= ".db_sqlformat( $matfim ) ;
	           }else{
		           $condicaoaux .= " and r10_regist in (".$campo_auxilio_regi.")" ;
	           }
           }else if( $glm == "l"){ 
	           global $buscalotac;
	           if($tipofil == "i"){
		            db_selectmax( "buscalotac","select max(trim(TO_CHAR(R70_CODIGO,'9999'))),min(trim(TO_CHAR(R70_CODIGO,'9999'))) from rhlota where r70_instit = ".db_getsession("DB_instit")." and r70_estrut between '$lotini' and '$lotfim'");
		            $lotini = $buscalotac[0]["min"];
		            $lotfim = $buscalotac[0]["max"];
		            $condicaoaux .= " and r10_lotac >= ".db_sqlformat( $lotini ) ;
		            $condicaoaux .= " and r10_lotac <= ".db_sqlformat( $lotfim ) ;
	           }else{
		            db_selectmax( "buscalotac","select trim(TO_CHAR(R70_CODIGO,'9999')) as r13_codigo from rhlota where r70_instit = ".db_getsession("DB_instit") ." and r70_estrut in (".$campo_auxilio_lota.")");
		            $sellotac = "";
		            $vir="";
		            for($ibuscalotac = 0; $ibuscalotac<count($buscalotac); $ibuscalotac ++){
		              $sellotac .= $vir."'".$buscalotac[$ibuscalotac]["r13_codigo"]."'";
		              $vir = ",";
		            }
		            $condicaoaux .= " and r10_lotac in (".$sellotac.")" ;
	           }
           }
//echo "<BR> $condicaoaux ";
  	       db_delete( "pontofs", $condicaoaux );
  	 
           $tot_func = count($arquivo_rubricas);
           $Iind = 0;
           while($Iind<$tot_func){

              db_atutermometro($Iind,$tot_func,'termometro',1);

              $retornar = true ;

              $matricu = $arquivo_rubricas[$Iind]["r90_regist"];

              $condicaoaux  = "select rh02_regist as r01_regist, trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,rh05_recis as r01_recis ,rh01_admiss as r01_admiss,rh02_tbprev as r01_tbprev,r33_codtab,r33_rubsau,r33_rubmat ";
              $condicaoaux .= " from rhpessoalmov left outer join inssirf ";
              $condicaoaux .= "  on rh02_tbprev+2 = r33_codtab ";
              $condicaoaux .= " and rh02_anousu = r33_anousu ";
              $condicaoaux .= " and rh02_mesusu = r33_mesusu ";
              $condicaoaux .= " and rh02_instit = r33_instit ";
              $condicaoaux .= "         inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
              $condicaoaux .= "         inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota ";
              $condicaoaux .= "         left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes "; 
              $condicaoaux .= bb_condicaosubpes("rh02_");
              $condicaoaux .= " and rh02_regist = ".db_sqlformat($arquivo_rubricas[$Iind]["r90_regist"] );
            //  $condicaoaux .= " and rh02_recis is null ";
              if( !db_selectmax( "pessoal", $condicaoaux )){
                 for(;$Iind<$tot_func;$Iind++){
  	               if($arquivo_rubricas[$Iind]["r90_regist"] != $matricu){
                     break;
  	               } 
		             }   
                 continue;
              }

              if( db_empty( $pessoal[0]["r33_rubsau"] )){
                 $rubrica_licenca_saude = bb_space(4);
              }else{
                 $rubrica_licenca_saude = $pessoal[0]["r33_rubsau"];
              }
              
              if( db_empty( $pessoal[0]["r33_rubmat"] )){
                 $rubrica_licenca_maternidade = bb_space(4);
              }else{
                 $rubrica_licenca_maternidade = $pessoal[0]["r33_rubmat"];
              }

              
              if( !db_boolean( $cfpess[0]["r11_confer"] ) && trim($d08_carnes) != "riogrande"){
                 ferias($arquivo_rubricas[$Iind]["r90_regist"]);
              }else{
                 $F019 = 0;
                 $F018 = 0;
                 $F020 = 0;
                 $F021 = 0;
                 $F023 = 0;
              }

              $proporcionalizar_salario = true;
              if( trim($d08_carnes) != "riogrande" && ( $F019>0 || $F020>0 || $F023>0 )){

                 if( db_boolean( $cadferia[0]["r30_paga13"] ) && strtolower($cfpess[0]["r11_fersal"]) == "s"){
                    $proporcionalizar_salario = false;
                 }
              }

	            $Ipessoal = 0;

              $situacao_130 = situacao_funcionario($arquivo_rubricas[$Iind]["r90_regist"]);

              for(;$Iind<$tot_func;$Iind++){
  	             if($arquivo_rubricas[$Iind]["r90_regist"] != $matricu){
  		             break;
  	              }	 

                 $quantidade      = $arquivo_rubricas[$Iind]["r90_quant"];
                 //echo "<BR> 0 passou aqui quantidade --> $quantidade";
                 $valor_descontar = $arquivo_rubricas[$Iind]["r90_valor"];
                 if( trim( $d08_carnes ) == "riogrande"){
                    if( $F019 >= 30 && $arquivo_rubricas[$Iind]["rh27_tipo"] == "1"){  // reis
                       continue;
                    }
                 }else{
                    if( strtolower($cfpess[0]["r11_fersal"]) == "f" && $F019 >= 30 && $arquivo_rubricas[$Iind]["rh27_tipo"] == "1"){  // reis
                 //echo "<BR> 0.1 passou aqui quantidade --> $quantidade";

                       // rubrica com calculo especial - plano de saude amparo;
                       // esta rubrica deve permanecer integral;
                       if( trim( $d08_carnes ) != "amparo" || ( trim( $d08_carnes ) == "amparo" && $arquivo_rubricas[$Iind]["rh27_rubric"] != '0514' ) ){
                          continue;
                       }

                    }
                 }

                 if( $arquivo_rubricas[$Iind]["rh27_rubric"] == '0604' && trim($d08_carnes) == "riogrande"){

                       $condicaoaux  = " and r90_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                       $condicaoaux .= " and r90_rubric = '0604'";
  		                 global $ponto;
                       if( db_selectmax("ponto", "select * from pontofx".bb_condicaosubpesanterior( "r90_").$condicaoaux )){
                          $valor_descontar = $ponto[0]["r90_valor"];

                          $condicaoaux  = " and r14_regist = ".db_sqlformat( $matricu );
                          $condicaoaux .= " and r14_rubric = '0604'";
  		                   	global $gerfsal;
                          if( db_selectmax("gerfsal", "select * from gerfsal ".bb_condicaosubpesanterior( "r14_").$condicaoaux )){
                             $valor_descontar -= $gerfsal[0]["r14_valor"];
                          }

                          $condicaoaux  = " and r90_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                          $condicaoaux += " and r90_rubric = '0604'";
                          if( db_val(db_str($valor_descontar,15,2)) > 0){
                             $matriz1 = array();
                             $matriz2 = array();
                             $matriz1[1] = "r90_valor";
                             $matriz2[1] = $valor_descontar;

                             db_update("pontofx",$matriz1,$matriz2,bb_condicaosubpes("r90_").$condicaoaux );
                          }else{
                             db_delete("pontofx",bb_condicaosubpes("r90_").$condicaoaux );

                             continue;
                          }
                       }
                 }else if( db_at($arquivo_rubricas[$Iind]["rh27_rubric"],"-0131-0256-0291-0334-") > 0 && trim($d08_carnes) =="viamao"){
  		 
                       $condicaoaux  = " and r90_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                       $condicaoaux .= " and r90_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );
  		                 global $ponto;
                       if( db_selectmax("ponto", "select * from pontofx".bb_condicaosubpesanterior( "r90_").$condicaoaux )){
                          $valor_descontar = $ponto[0]["r90_valor"];

                          $condicaoaux  = " and r14_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                          $condicaoaux .= " and r14_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );
  			                  global $gerfsal;
                          if( db_selectmax("gerfsal", "select * from gerfsal ".bb_condicaosubpesanterior( "r14_").$condicaoaux )){
                             $valor_descontar -= $gerfsal[0]["r14_valor"];
                          }
   
                          $condicaoaux  = " and r90_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                          $condicaoaux .= " and r90_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );
                          if( db_val(db_str($valor_descontar,15,2)) > 0){
                             $matriz1 = array();
                             $matriz2 = array();
                             $matriz1[1] = "r90_valor";
                             $matriz2[1] = $valor_descontar;

                             db_update("pontofx",$matriz1,$matriz2,bb_condicaosubpes("r90_").$condicaoaux );
                          }else{
                             db_delete("pontofx",bb_condicaosubpes("r90_").$condicaoaux );
                             continue;
                          }
                       }
                 }else if( 
		                      (
		                          db_substr($arquivo_rubricas[$Iind]["rh27_rubric"],1,1) != "R"
			                     && db_val($arquivo_rubricas[$Iind]["rh27_rubric"]) > 0  
                           && db_val($arquivo_rubricas[$Iind]["rh27_rubric"]) < 2000 
                           && $arquivo_rubricas[$Iind]["rh27_tipo"] == "2" // variavel 
                           && $arquivo_rubricas[$Iind]["rh27_limdat"] == 't' // usa data limite
			                     && !db_empty($arquivo_rubricas[$Iind]["r90_datlim"])
                           && ((db_substr( $arquivo_rubricas[$Iind]["r90_datlim"],1,4).db_substr( $arquivo_rubricas[$Iind]["r90_datlim"],-2)) 
			                       < (db_substr($subpes,1,4).db_substr($subpes,-2)))
			                    )
			                    || 
			                    ( 
			                      $arquivo_rubricas[$Iind]["rh27_tipo"] == 2 && $arquivo_rubricas[$Iind]["rh27_limdat"] == 'f'
			                    )
			                 ){
                   //echo "<BR> 1 passou aqui ";
                       $condicaoaux  = " and r90_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                       $condicaoaux .= " and r90_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );
                       db_delete( "pontofx", bb_condicaosubpes("r90_").$condicaoaux);
                 }else if( $arquivo_rubricas[$Iind]["rh27_limdat"] == 't' && db_boolean($arquivo_rubricas[$Iind]["rh27_presta"])){
                   //echo "<BR> 2 passou aqui ";
                       $quantidade = 0;
                       $verano = db_val(db_substr($subpes,1,4));
                       $vermes = db_val(db_substr($subpes,-2));
                       while($verano < db_val(db_substr( $arquivo_rubricas[$Iind]["r90_datlim"],1,4))
                            || ( $verano == db_val(db_substr( $arquivo_rubricas[$Iind]["r90_datlim"],1,4))
                            && $vermes <= db_val(db_substr( $arquivo_rubricas[$Iind]["r90_datlim"],-2)))){
                          $quantidade += 1;
                   //echo "<BR> 3 passou aqui verano --> $verano vermes --> $vermes quantidade --> $quantidade";
                          $vermes += 1;
                          if( $vermes > 12){
                   //echo "<BR> 4 passou aqui ";
                              $vermes = 1;
                              $verano += 1;
                          }
                       }
                       $condicaoaux  = " and r90_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                       $condicaoaux .= " and r90_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );
                       $matriz1 = array();
                       $matriz2 = array();
                       $matriz1[1] = "r90_quant";
                       $matriz2[1] = $quantidade;
                   //echo "<BR> 5 passou aqui ";
                       db_update("pontofx",$matriz1,$matriz2,bb_condicaosubpes("r90_").$condicaoaux );
                 }
                 // 2 - Afastado sem Remuneracao
                 // 4 - Afastado Servico Militar
                 // 3 - Afastado Acidente de Trabalho + 15 Dias
                 // 6 - Afastado Doenca + 15 Dias   
                 // 5 - licenca maternidade 
                 // 7 - Licenca sem Vencimento, cessao sem onus
                 $condicaoaux  = " and r90_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                 $condicaoaux .= " and r90_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_rubric"] );
                 global $ponto;
                 if( db_selectmax("ponto", "select * from pontofx".bb_condicaosubpes( "r90_").$condicaoaux)  && 
                   ( db_at(db_str($situacao_130,1),"1-3-4") > 0  || 
		               ( db_at(db_str($situacao_130,1),"2-6-7") > 0 && !db_empty($dias_pagamento) ) || 
		               ( db_str($situacao_130,1) == "6" && !db_empty($rubrica_licenca_saude) ) || 
		               ( db_str($situacao_130,1) == "5" && !db_empty($rubrica_licenca_maternidade) ) ) )
		               {

                   //echo "<BR> 6 passou aqui quantidade --> $quantidade situacao_130 --> $situacao_130";
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

                     $matriz2[1] = $arquivo_rubricas[$Iind]["r90_regist"];
                     $matriz2[2] = $arquivo_rubricas[$Iind]["r90_rubric"];
                     $matriz2[3] = $arquivo_rubricas[$Iind]["r90_lotac"];
                     $matriz2[4] = $quantidade;
                     $matriz2[5] = $valor_descontar;
                     $matriz2[6] = $arquivo_rubricas[$Iind]["r90_datlim"];
                     $matriz2[7] = db_val( db_substr( $subpes,1,4 ) );
                     $matriz2[8] = db_val( db_substr( $subpes,6,2 ) );
		                 $matriz2[9] = db_getsession("DB_instit");
//echo "<BR> 1 passou aqui !!!";		     
                     db_insert( "pontofs", $matriz1, $matriz2 );

                     if( $quantidade != $arquivo_rubricas[$Iind]["r90_quant"]){
                   //echo "<BR> 7 passou aqui ";
                        $matriz1 = array();
                        $matriz2 = array();
                        $matriz1[1] = "r90_quant";
                        $matriz2[1] = $quantidade;
                        $condicaoaux  = " and r90_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                        $condicaoaux .= " and r90_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );

                        db_update( "pontofx", $matriz1, $matriz2, bb_condicaosubpes("r90_").$condicaoaux );
                     }
                 }
                 // nao deve proporcionalizar no sal.materinidade pois isto;
                 // e feito na geracao do calculo;
                 if( !db_empty($dias_pagamento)){
                    if( (   db_str($situacao_130,1) == "5" && db_empty($rubrica_licenca_maternidade) )
                        || ( db_str($situacao_130,1) == "6" && db_empty( $rubrica_licenca_saude     )  )  
                        || db_str($situacao_130,1) == "2"
                        || db_str($situacao_130,1) == "3"
                        || db_str($situacao_130,1) == "7"
                        || (    db_year($pessoal[0]["r01_admiss"]) == db_val(db_substr($subpes,1,4)) 
                             && db_month($pessoal[0]["r01_admiss"]) == db_val(db_substr($subpes,-2)) 
                             && $dias_pagamento < 30 )
                      ){

                        if( !db_empty( $valor_descontar )){
                           $matriz1 = array();
                           $matriz2 = array();
                           $matriz1[1] = "r10_valor";
                           $condicaoaux  = " and r10_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                           $condicaoaux .= " and r10_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );

                           if( db_boolean( $arquivo_rubricas[$Iind]["rh27_calcp"] )){
                               $matriz2[1] = ( $valor_descontar / 30 ) * $dias_pagamento;
                           }else{
                               $matriz2[1] = $valor_descontar;
                           }
                           db_update( "pontofs", $matriz1, $matriz2, bb_condicaosubpes("r10_").$condicaoaux );
                        }
                        if( !db_empty( $quantidade )){
                           $matriz1 = array();
                           $matriz2 = array();
                           $matriz1[1] = "r10_quant";

                           $condicaoaux  = " and r10_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                           $condicaoaux .= " and r10_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );
                           if( db_boolean($arquivo_rubricas[$Iind]["rh27_calcp"])){
                              if( db_boolean($arquivo_rubricas[$Iind]["rh27_propq"] )){
                                  $matriz2[1] = ( $quantidade / 30 ) * $dias_pagamento;
                              }else{
                                  $matriz2[1] = $quantidade ;
                              }
                           }else{
                              $matriz2[1] = $arquivo_rubricas[$Iind]["r90_quant"] ;
                              $matriz2[1] = $quantidade ;
                           }
                           db_update( "pontofs", $matriz1,$matriz2,bb_condicaosubpes("r10_").$condicaoaux );
                        }
                     }
                 }

                 //**** proporcionalidade retorno de ferias.;
                 if( $F019 > 0 && $proporcionalizar_salario ){

                     if( !db_empty( $valor_descontar )){
                        $matriz1 = array();
                        $matriz2 = array();
                        $matriz1[1] = "r10_valor";
                        $condicaoaux  = " and r10_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"] );
                        $condicaoaux .= " and r10_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );
                        if( db_boolean( $arquivo_rubricas[$Iind]["rh27_calcp"])){
                           $valor_descontar = bb_round( ( $valor_descontar / 30 ) * (30-$F019),2 );
                        }
                        $matriz2[1] = $valor_descontar;
                        db_update( "pontofs", $matriz1,$matriz2,bb_condicaosubpes("r10_").$condicaoaux );
                     }

                     if( !db_empty( $quantidade )){
                        $matriz1 = array();
                        $matriz2 = array();
                        $matriz1[1] = "r10_quant";
                        $condicaoaux  = " and r10_regist = ".db_sqlformat( $arquivo_rubricas[$Iind]["r90_regist"]);
                        $condicaoaux .= " and r10_rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"]);
                        if( db_boolean( $arquivo_rubricas[$Iind]["rh27_calcp"])){
                           if( db_boolean( $arquivo_rubricas[$Iind]["rh27_propq"]) ){
                              $quantidade = bb_round( ( $quantidade / 30 ) * (30-$F019),2);
                           }
                        }
                        $matriz2[1] = $quantidade;
                        db_update( "pontofs", $matriz1,$matriz2,bb_condicaosubpes("r10_").$condicaoaux );
                     }
                     if( $valor_descontar == 0 && $quantidade == 0){
                         db_delete( "pontofs", bb_condicaosubpes("r10_").$condicaoaux );
                     }
                 }
              }
              if( trim( $d08_carnes ) == "amparo" && $rubrica_14salario >= $rubini && $rubrica_14salario <= $rubfim){


                 $condicaoaux = "select distinct( r89_regist ) ";
                 $condicaoaux .="      ,( select sum(r89_valor) ";
                 $condicaoaux .="           from gerfs14 ";
                 $condicaoaux .="          where r89_anousu=".db_sqlformat(db_substr($subpes,1,4)) ;
                 $condicaoaux .="            and r89_mesusu=".db_sqlformat(db_substr($subpes,6,2)) ;
                 $condicaoaux .="            and r89_regist=".db_sqlformat($pessoal[0]["r01_regist"]) ;
                 $condicaoaux .="            and r89_pd=1 ) as prov ";
                 $condicaoaux .="      ,( select sum(r89_valor) ";
                 $condicaoaux .="           from gerfs14 ";
                 $condicaoaux .="          where r89_anousu=".db_sqlformat(db_substr($subpes,1,4)) ;
                 $condicaoaux .="            and r89_mesusu=".db_sqlformat(db_substr($subpes,6,2)) ;
                 $condicaoaux .="            and r89_regist=".db_sqlformat($pessoal[0]["r01_regist"]);
                 $condicaoaux .="            and r89_pd=2 ) as desc ";
                 $condicaoaux .="  from gerfs14 ";
                 $condicaoaux .=" where r89_anousu=".db_sqlformat(db_substr($subpes,1,4)) ;
                 $condicaoaux .="   and r89_mesusu=".db_sqlformat(db_substr($subpes,6,2)) ;
                 $condicaoaux .="   and r89_regist=".db_sqlformat($pessoal[0]["r01_regist"]) ;

                 global $gerfs14;
                 if( db_selectmax( "gerfs14", $condicaoaux ) && ($gerfs14[0]["prov"] - $gerfs14[0]["desc"]) > 0){
  		 
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

                     $matriz2[1] = $pessoal[0]["r01_regist"];
                     $matriz2[2] = $rubrica_14salario;
                     $matriz2[3] = $pessoal[0]["r01_lotac"];
                     $matriz2[4] = 0;
                     $matriz2[5] = ($gerfs14[0]["prov"] - $gerfs14[0]["desc"]);
                     $matriz2[6] = bb_space(7);
                     $matriz2[7] = db_val( db_substr( $subpes,1,4 ) );
                     $matriz2[8] = db_val( db_substr( $subpes,6,2 ) );
		                 $matriz2[9] = db_getsession("DB_instit");

//echo "<BR> 2 r90_regist --> ".$arquivo_rubricas[$Iind]["r90_regist"]; 
                     db_insert( "pontofs", $matriz1, $matriz2 );
                  }

              }

          }

        }else if( $opcao == 2){ 

           // nao inicializar para quem esta de ferias....;

           // inicializa ponto de adto de salarios;
  	 
           for($Iind=0;$Iind<count($arquivo_rubricas);$Iind++){
              db_atutermometro($Iind,count($arquivo_rubricas),'termometro',1);
              $matricu = $arquivo_rubricas[$Iind]["r21_regist"];
              //$matricu = eval($matricu);
	            global $pessoal;
              $condicaoaux  = " select * from rhpessoalmov ";
              $condicaoaux .= "         inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist ";
              $condicaoaux .= "         inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota ";
              $condicaoaux .= "         left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes "; 
              $condicaoaux .= " ".bb_condicaosubpes("rh02_")." and rh02_regist = ".db_sqlformat( $matricu );
              if(!db_selectmax( "pessoal",$condicaoaux )){
                 break;
              }
              if( !db_empty($pessoal[0]["rh05_recis"])){
                 continue;
              }



              if($arquivo_rubricas[$Iind]["rh27_tipo"] == 2){
                 $condicaoaux  = " and ".$sigla_pesquisa."regist = ".db_sqlformat( $matricu );
                 $condicaoaux .= " and ".$sigla_pesquisa."rubric = ".db_sqlformat( $arquivo_rubricas[$Iind]["rh27_rubric"] );

                 db_delete( "pontofa", bb_condicaosubpes( "r21_").$condicaoaux );
              }
           }
        }
      }
      return $erro_msg;
 }

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_libpessoal.php");


db_inicio_transacao();

db_postmemory($HTTP_POST_VARS);

?>


<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<table width="100%" height="18"  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="left" valign="center" bgcolor="#CCCCCC"> 
<center>
<?
db_criatermometro('termometro','Concluido...','blue',1,'Inicializando Ponto ...');
?>
</center>
     <?
	db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
     ?> 
    </td>
  </tr>
</table>
</body>
</html>
<?

global $cfpess,$subpes,$d08_carnes,$db_config;
global $glm,$lotini,$lotfim,$matini,$matfim,$rubini,$rubfim;

$subpes = db_anofolha().'/'.db_mesfolha();

db_selectmax("cfpess"," select * from cfpess ".bb_condicaosubpes("r11_")); 

global $db_config;
db_selectmax("db_config","select lower(trim(munic)) as d08_carnes , cgc from db_config where codigo = ".db_getsession("DB_instit"));

if(trim($db_config[0]["cgc"]) == "90940172000138"){
     $d08_carnes = "daeb";
}else{
     $d08_carnes = $db_config[0]["d08_carnes"];
}

$erro_msg = init_130($opcao);
db_fim_transacao();
//exit;

db_msgbox($erro_msg);
db_redireciona("pes4_inicializaponto001.php");
   
?>