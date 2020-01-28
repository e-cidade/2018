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

function buscaValorAdiantamento13Canela($iRegist, $iAnousu, $iMesusu){

  $nValor = 0.00;
  global $DB_instit;

  $sSqlAdiantamentoInss  = " select sum(r35_valor) as valor_adiantamento_inss ";
  $sSqlAdiantamentoInss .= "   from gerfs13 ";

  $sSqlAdiantamentoInss .= "  where r35_rubric = '4535' ";
  $sSqlAdiantamentoInss .= "    and exists ( select 1 "; 
  $sSqlAdiantamentoInss .= "                   from rhpessoalmov  ";
  $sSqlAdiantamentoInss .= "                        inner join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg ";
  $sSqlAdiantamentoInss .= "                  where rhpessoalmov.rh02_regist = r35_regist ";
  $sSqlAdiantamentoInss .= "                    and rhpessoalmov.rh02_anousu = r35_anousu ";
  $sSqlAdiantamentoInss .= "                    and rhpessoalmov.rh02_mesusu = r35_mesusu ";
  $sSqlAdiantamentoInss .= "                    and rhpessoalmov.rh02_instit = r35_instit ";
  $sSqlAdiantamentoInss .= "                    and rh30_vinculo in ('I', 'P') ) ";
  $sSqlAdiantamentoInss .= "    and r35_regist = {$iRegist} ";
  $sSqlAdiantamentoInss .= "    and r35_anousu = {$iAnousu} ";
  $sSqlAdiantamentoInss .= "    and r35_mesusu < {$iMesusu} ";
  $sSqlAdiantamentoInss .= "    and r35_instit = {$DB_instit} ";

  $rsAdiantamentoInss   = db_query($sSqlAdiantamentoInss);
  if (!$rsAdiantamentoInss){
    return false;  
  }

  if (pg_num_rows($rsAdiantamentoInss) == 0){
    return $nValor;      
  }

  $oValorAdiantamentoInss = db_utils::fieldsMemory($rsAdiantamentoInss, 0);
  $nValor = $oValorAdiantamentoInss->valor_adiantamento_inss;

  return $nValor;

}

function calculos_especificos_carazinho($opcao_geral,$r110_regist, $r110_lotac){

  global $transacao,$pessoal, $Ipessoal, $calcula_valor_514,$tot_desc, $tot_prov, $subpes;
  global $anousu, $mesusu, $DB_instit;

  // --> Calculo da contribuicao Partidaria

  //     Rubrica --> 0514 CONTR. PARTIDARIA 10% - PPB

  if( $calcula_valor_514 ){
     if( $opcao_geral == 1 ){
  $condicaoaux = " and r14_rubric in ('R901','R902','R903','R904','R905','R906','R907','R908','R909','R910','R911','R912','R913','R914','R915','R918','R919','R920','R921','0026')";
  $condicaoaux = " and r14_regist = ".db_sqlformat( $r110_regist );
  if ( db_selectmax( "transacao", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )) {
     $valor_514 = $tot_prov;
     for($Itrans=0;$Itrans< count($transacao);$Itrans++){
         $valor_514 -= $transacao[$Itrans]["r14_valor"];
     }
     if( $valor_514 > 0){
        // valor_514 = valor_514 * 0.05;
        // alterado para nov/2000 de 5% para 10%;
        $valor_514 = $valor_514 * 0.1;
        $tot_desc += $valor_514;
        if ($db_debug == true) { echo "[calculos_especificos_carazinho] total de desconto: $tot_desc<br>"; }
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
   
        $matriz2[1] = $r110_regist;
        $matriz2[2] = "0514";
        $matriz2[3] = $r110_lotac;
        $matriz2[4] = round( $valor_514,2);
        $matriz2[5] = 10;
        $matriz2[6] = 2;
        $matriz2[7] = 1;
        $matriz2[8] = $anousu;
        $matriz2[9] = $mesusu;
        $matriz2[10] = $DB_instit;
        db_insert( "gerfsal", $matriz1, $matriz2 );
     }
  }
     }else{
  $condicaoaux = " and r48_rubric in ('R901','R902','R903','R904','R905','R906','R907','R908','R909','R910','R911','R912','R913','R914','R915','R918','R919','R920','R921','0026')";
  $condicaoaux = " and r48_regist = ".db_sqlformat( $r110_regist );
  if( db_selectmax( "transacao", "select * from gerfcom ".bb_condicaosubpes( "r48_" ).$condicaoaux )){
     $valor_514 = $tot_prov;
     for($Itrans=0;$Itrans< count($transacao);$Itrans++){
         $valor_514 -= $transacao[$Itrans]["r48_valor"];
     }
     if( $valor_514 > 0){
        //  alterado de 5% para 10% em nov/2000;
        $valor_514 = $valor_514 * 0.10;
        $tot_desc += $valor_514;
        $matriz1 = array();
        $matriz2 = array();

        $matriz1[1]  = "r48_regist";
        $matriz1[2]  = "r48_rubric";
        $matriz1[3]  = "r48_lotac";
        $matriz1[4]  = "r48_valor";
        $matriz1[5]  = "r48_quant";
        $matriz1[6]  = "r48_pd";
        $matriz1[7]  = "r48_semest";
        $matriz1[8]  = "r48_anousu";
        $matriz1[9]  = "r48_mesusu";
        $matriz1[10] = "r48_instit";

        $matriz2[1] = $r110_regist;
        $matriz2[2] = "0514";
        $matriz2[3] = $r110_lotac;
        $matriz2[4] = round( $valor_514,2);
        $matriz2[5] = 10;
        $matriz2[6] = 2;
        $matriz2[7] = 0;
        $matriz2[8] = $anousu;
        $matriz2[9] = $mesusu;
        $matriz2[10] = $DB_instit;

        db_insert( "gerfcom", $matriz1, $matriz2 );
     }
  }
     }
  }
  
  if ($db_debug == true) { echo "[calculos_especificos_carazinho] 1 - calcula R928 - "; }
  calcula_r928($r110_regist,$r110_lotac,$opcao_geral);
  
  if( $opcao_geral = 1 ){
     
  }

}

function calculos_especificos_itaqui($opcao_geral,$r110_regist, $r110_lotac){
  global $subpes;
  global $anousu, $mesusu, $DB_instit,$tot_prov;

  if($opcao_geral == 1 || $opcao_geral == 8){  

     // Repassa o valor das rubricas de horas extras de 50 e 100% das ferias para a rubrica 0085 
     // - media de horas extras e lança no salário ou complementar.

     $condicaoaux  = " and r09_base = ".db_sqlformat( "B021");
     $sel_B021 = "";
     global $basesr;
//echo "<BR> select r09_rubric from basesr ".bb_condicaosubpes("r09_").$condicaoaux;
     if( db_selectmax( "basesr", "select r09_rubric from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
       $sel_B021 = "'";
       for($Ibasesr=0;$Ibasesr<count($basesr);$Ibasesr++){
          if($Ibasesr > 0){
             $sel_B021 .= ",'"; 
          } 
          $sel_B021 .= $basesr[$Ibasesr]["r09_rubric"]."'"; 
       }

       ////// verifaca em que ponto sera calculado as ferias 

       $condicaoaux = " and r30_regist = ".db_sqlformat( $r110_regist ).
                      " and r30_proc1 = '$subpes' ";
       global $cadferia_itaqui;

       if(db_selectmax( "cadferia_itaqui", "select r30_ponto
                         from cadferia ".bb_condicaosubpes( "r30_" ).$condicaoaux )){
          $ponto_pag_itaqui = $cadferia_itaqui[0]["r30_ponto"];
       }

       $condicaoaux = " and r31_regist = ".db_sqlformat( $r110_regist ).
                      " and r31_rubric in (".$sel_B021.") group by r31_regist ";
       global $gerffer;
       if(db_selectmax( "gerffer", "select r31_regist,
                                           sum(r31_quant) as r31_quant, 
                                           sum(r31_valor) as r31_valor 
                                    from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux )){
       
          if( $gerffer[0]["r31_quant"] != 0 || $gerffer[0]["r31_valor"] !=0) {
            if($opcao_geral == 1 && $ponto_pag_itaqui == 'S'){
 //echo "<BR> r31_quant --> ".$gerffer[0]["r31_quant"]."   r31_valor --> ".$gerffer[0]["r31_valor"];
           /////  insere no pontofs 

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
            
            
            $matriz2[1] = $r110_regist;
            $matriz2[2] = "0085";  // MEDIA HS EXTRAS
            $matriz2[3] = $r110_lotac;
            $matriz2[4] = $gerffer[0]["r31_quant"];
            $matriz2[5] = $gerffer[0]["r31_valor"];
            $matriz2[6] = str_pad(" ", 7 );
            $matriz2[7] = $anousu;
            $matriz2[8] = $mesusu;
            $matriz2[9] = $DB_instit;
            $tot_prov += $gerffer[0]["r31_valor"];
            
            $condicaoaux  = " and r10_regist = ".db_sqlformat($r110_regist);
            $condicaoaux .= " and r10_rubric = '0085'";
            if( !db_selectmax( "pontofs", "select r10_regist from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )){
                     db_insert( "pontofs", $matriz1, $matriz2 );
            }else{
         db_update( "pontofs", $matriz1, $matriz2, bb_condicaosubpes("r10_").$condicaoaux );
                  }

           /////  insere no gerfsal 

            $matriz3 = array();
            $matriz4 = array();
            
            $matriz3[1] = "r14_regist";
            $matriz3[2] = "r14_rubric";
            $matriz3[3] = "r14_lotac";
            $matriz3[4] = "r14_quant";
            $matriz3[5] = "r14_valor";
            $matriz3[6] = "r14_pd";
            $matriz3[7] = "r14_anousu";
            $matriz3[8] = "r14_mesusu";
            $matriz3[9] = "r14_instit";
            $matriz3[10] = "r14_semest";
            
            
            $matriz4[1] = $r110_regist;
            $matriz4[2] = "0085";  // MEDIA HS EXTRAS
            $matriz4[3] = $r110_lotac;
            $matriz4[4] = $gerffer[0]["r31_quant"];
            $matriz4[5] = $gerffer[0]["r31_valor"];
            $matriz4[6] = 1;
            $matriz4[7] = $anousu;
            $matriz4[8] = $mesusu;
            $matriz4[9] = $DB_instit;
            $matriz4[10] = 0;
            
            $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist);
                  $condicaoaux .= " and r14_rubric = '0085'";
            if( !db_selectmax( "gerfsal", "select r14_regist from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
                     db_insert( "gerfsal", $matriz3, $matriz4 );
            }else{
         db_update( "gerfsal", $matriz3, $matriz4, bb_condicaosubpes("r14_").$condicaoaux );
                  }
            }elseif($opcao_geral == 8 && $ponto_pag_itaqui == 'C'){
           /////  insere no pontofs 
//echo "<br><br> opcao_geral --> $opcao_geral";
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
            
            
            $matriz2[1] = $r110_regist;
            $matriz2[2] = "0085";  // MEDIA HS EXTRAS
            $matriz2[3] = $r110_lotac;
            $matriz2[4] = $gerffer[0]["r31_quant"];
            $matriz2[5] = $gerffer[0]["r31_valor"];
            $matriz2[6] = $anousu;
            $matriz2[7] = $mesusu;
            $matriz2[8] = $DB_instit;
            $tot_prov += $gerffer[0]["r31_valor"];
            
            $condicaoaux  = " and r47_regist = ".db_sqlformat($r110_regist);
            $condicaoaux .= " and r47_rubric = '0085'";
            if( !db_selectmax( "pontocom", "select r47_regist from pontocom ".bb_condicaosubpes( "r47_" ).$condicaoaux )){
                     db_insert( "pontocom", $matriz1, $matriz2 );
            }else{
         db_update( "pontocom", $matriz1, $matriz2, bb_condicaosubpes("r47_").$condicaoaux );
                  }

           /////  insere no gerfcom

            $matriz3 = array();
            $matriz4 = array();
            
            $matriz3[1] = "r48_regist";
            $matriz3[2] = "r48_rubric";
            $matriz3[3] = "r48_lotac";
            $matriz3[4] = "r48_quant";
            $matriz3[5] = "r48_valor";
            $matriz3[6] = "r48_pd";
            $matriz3[7] = "r48_anousu";
            $matriz3[8] = "r48_mesusu";
            $matriz3[9] = "r48_instit";
            $matriz3[10] = "r48_semest";
            
            
            $matriz4[1] = $r110_regist;
            $matriz4[2] = "0085";  // MEDIA HS EXTRAS
            $matriz4[3] = $r110_lotac;
            $matriz4[4] = $gerffer[0]["r31_quant"];
            $matriz4[5] = $gerffer[0]["r31_valor"];
            $matriz4[6] = 1;
            $matriz4[7] = $anousu;
            $matriz4[8] = $mesusu;
            $matriz4[9] = $DB_instit;
            $matriz4[10] = 0;
            
            $condicaoaux  = " and r48_regist = ".db_sqlformat($r110_regist);
                  $condicaoaux .= " and r48_rubric = '0085'";
            if( !db_selectmax( "gerfcom", "select r48_regist from gerfcom ".bb_condicaosubpes( "r48_" ).$condicaoaux )){
                     db_insert( "gerfcom", $matriz3, $matriz4 );
            }else{
         db_update( "gerfcom", $matriz3, $matriz4, bb_condicaosubpes("r48_").$condicaoaux );
                  }
           } ///////
         }  
       }
     } 
  }
  if ($db_debug == true) { 
    echo "[calculos_especificos_itaqui] 2 - calcula R928 - "; 
  }
  calcula_r928($r110_regist,$r110_lotac,$opcao_geral);
}


function calculos_especificos_itaqui_ajuste($registro_, $lotac_){

  global $subpes;
  global $anousu, $mesusu, $DB_instit;

  // 0053 - ASEMI    
  // 0055 - SINDICATO
  $rubricas_ = "053-055-067- ";
  $reg_053 = 0;
  $reg_055 = 0;
  $reg_067 = 0;

  $calcula_valor_053 = false;
  $calcula_valor_055 = false;
  $calcula_valor_067 = false;

  $tot_valor_liquido = 0;
  $limite_desconto = 0;
  $limite_desconto_total = 0;
  $valor_proventos_base = 0;
  $valor_descontos_base = 0;
  $valor_descontos_obrigatorios = 0;
  $total_descontos_avaliar = 0;

  $condicaoaux  = " and r14_regist = ".db_sqlformat( $registro_ );
  $condicaoaux .= " and r14_rubric <= 'R926' ";
  global $gerfsal;
  if( db_selectmax( "gerfsal", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
     for($Igerfsal=0;$Igerfsal< count($gerfsal);$Igerfsal++){
     if( $gerfsal[$Igerfsal]["r14_pd"] == 1){
        $valor_proventos_base += $gerfsal[$Igerfsal]["r14_valor"];
        $calculado_total = true;
     }else{


        //valor_descontos_base += gerfsal[0][r14_valor;


        // descontar previdencia, ir, pensao alimenticia, desconto;
        // adiantamento salario, cesta basica, 0059;
        // 0054   APEI                    DESCONTO...                   Não    
        // 0059   DESC.ADIANT.SALARIO 
        // 0060   PENSAO ALIMENTICIA      DESCONTO...                   Não    
        // 0063   APEMI                   DESCONTO...   D009*1/100      Não    
        // 0064   CESTA BASICA 1% BAS.    DESCONTO...                   Não   LANCAR EM VALOR. 
        // 0066   MENSALIDADE CAMI        DESCONTO...   B013*0.01       Não   LANCAR 1,00 NA QUANTIDADE.
        // 0116   PARTIDO PMDB 5%BRUTO    DESCONTO...   B014*0.05       Não   LANCAR 1,00 NA QUANTIDADE.
        // 0143   MENSALIDADE ASEMI       DESCONTO...   D008*4.5/100    Não   INFORMAR 1,00 NO PONTO.
        // 0144   MENSALIDADE SINDICATO   DESCONTO...   D008*3/100      Não   INFORMAR 1,00 NO PONTO.

        $rubricas_obrigatorios = "0054-0059-0060-0063-0064-0066-0116-0143-0144-";

        // 0091   EMPRESTIMO BANRISUL     DESCONTO...                  Sim   LANCAR EM VALOR (COLOCAR DATA LIMITE) 
        // 0118   EMPRESTIMO CEF          DESCONTO...                  Sim   LANCAR EM VALOR (COLOCAR DATA LIMITE)
        // 0125   CONVENIO CEF            DESCONTO...                  Não   LANCAR EM VALOR (COLOCAR DATA LIMITE)
        // 0051   SINDICATO PL. SAUDE     DESCONTO...      B016*0.10   Não   LANCAR 1,00 NA QUANTIDADE.
        // 0065   S.P.S. - ESPOSO         DESCONTO...                  Não   LANCAR EM VALOR. 
        // 0092   S.P.S. - CONSULTAS      DESCONTO...                  Não    
        // 0122   S.P.S. - DEPENDENTE     DESCONTO...                  Não   LANCAR EM VALOR. 
        // 0168   EMPRESTIMO SANTANDER    DESCONTO...                  Sim   LANCAR EM VALOR(COLOCAR DATA LIMITE)
        // 0124   EMPRESTIMO B.B.         DESCONTO...                  Sim   LANCAR EM VALOR (COLOCAR DATA LIMITE)
        // 0178   EMPRESTIMO BRADESCO     DESCONTO...                  Sim    

        $rubricas_descontos = "0091-0118-0125-0051-0065-0092-0122-0168-0124-0178-" ;
        
        if( db_at($gerfsal[$Igerfsal]["r14_rubric"],$rubricas_obrigatorios)> 0 || ($gerfsal[$Igerfsal]["r14_rubric"] >= 'R901' 
      && $gerfsal[$Igerfsal]["r14_rubric"] <= 'R915')) {
     $valor_descontos_obrigatorios += $gerfsal[$Igerfsal]["r14_valor"];
        }else if( db_at($gerfsal[$Igerfsal]["r14_rubric"],$rubricas_descontos) > 0 ){ 
     $valor_descontos_base += $gerfsal[$Igerfsal]["r14_valor"];
        }
        
     }
     }
  }

  if( $valor_proventos_base == 0){
     return;
  }


  // limite_desconto = round( valor_proventos_base * 0.20,2);

  //limite_desconto_total = round( valor_proventos_base * 0.25,2);

  $avaliar = $valor_proventos_base - $valor_descontos_obrigatorios - $valor_descontos_base;
  $limite_desconto = round( $avaliar  * 0.20,2);

//  $limite_desconto_total = round( ($valor_proventos_base - $valor_descontos_obrigatorios) * 0.25,2);
  $limite_desconto_total = round( ($valor_proventos_base - $valor_descontos_obrigatorios));


  $condicaoaux  = " and r10_regist = ".db_sqlformat( $registro_ ) ;
  $condicaoaux .= " and r10_rubric in ( '0053','0055','0067' ) ";
  global $pontofs_;
  if( db_selectmax( "pontofs_", "select sum(r10_valor) as total_descontos_avaliar from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )){
     $total_descontos_avaliar = $pontofs_[0]["total_descontos_avaliar"];
      
     //salario_liquido = valor_proventos_base - valor_descontos_base - total_descontos_avaliar;
     $salario_liquido = $valor_proventos_base - $valor_descontos_obrigatorios - $valor_descontos_base - $total_descontos_avaliar;
     if( $salario_liquido < $limite_desconto_total ){
        $rateio = $limite_desconto_total - $salario_liquido ;
     }else{
        $rateio = 0;
     }
     $saldo_rateio = $rateio;

     $condicaoaux .= " order by r10_valor desc ";
     db_selectmax( "pontofs_", "select r10_rubric, r10_valor from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux );
     for($Ipontofs_=0;$Ipontofs_ < count($pontofs_);$Ipontofs_++){

        $rubrica_varia = substr("#". $pontofs_[$Ipontofs_]["r10_rubric"],2,3 );

        $valor_liquido = "valor_liquido".$rubrica_varia;
        $$valor_liquido = $pontofs_[$Ipontofs_]["r10_valor"];
        $calcula_valor = "calcula_valor_".$rubrica_varia;
        $$calcula_valor = true;
  
        if( !db_empty($pontofs_[$Ipontofs_]["r10_valor"])){
          if( !db_empty($valor_proventos_base)){
         
            if( $saldo_rateio > 0){
              if( $pontofs_[$Ipontofs_]["r10_valor"] > $limite_desconto){
                // os proventos nao serao suficientes para o desconto integral;
                // diferenca entre o lancado e o limite de 20%;
                $valor = $pontofs_[$Ipontofs_]["r10_valor"] - $limite_desconto;
                if( $valor > $saldo_rateio){
                  $$valor_liquido -= $saldo_rateio;
                  $saldo_rateio = 0;
                }else{
                  $$valor_liquido = $limite_desconto;
                  $saldo_rateio -= $valor                       ;
                }
              }else{
                // lanca o restante do valor a descontar (saldo final);
                $$valor_liquido = $pontofs_[$Ipontofs_]["r10_valor"];
              }
            }else{
              $$valor_liquido = $pontofs_[$Ipontofs_]["r10_valor"];
            }
            $tot_valor_liquido += $$valor_liquido;

          }
        }
     }
  }


  //salario_liquido = valor_proventos_base - valor_descontos_base - tot_valor_liquido;
  $salario_liquido = $valor_proventos_base - $valor_descontos_obrigatorios - $tot_valor_liquido;
  if( $salario_liquido < $limite_desconto_total ){
      $rateio = $limite_desconto_total - $salario_liquido ;
  }else{
      $rateio = 0;
  }

  $matriz1 = array();
  $matriz2 = array();

  $matriz1[1] = "r14_regist";
  $matriz1[2] = "r14_rubric";
  $matriz1[3] = "r14_lotac";
  $matriz1[4] = "r14_valor";
  $matriz1[5] = "r14_quant";
  $matriz1[6] = "r14_pd";
  $matriz1[7] = "r14_semest";
  $matriz1[8] = "r14_anousu";
  $matriz1[9] = "r14_mesusu";
  $matriz1[10] = "r14_instit";


  for($i= 1 ;$i<= 12;$i+=4){
     $rubrica_varia = substr("#". $rubricas_,$i,3 );

     $calcula_valor = "calcula_valor_".$rubrica_varia;
     $valor_liquido = "valor_liquido".$rubrica_varia;
     
     if( $$calcula_valor ){
       if(  $$valor_liquido > 0){
          $proporcao = 0;
          if( $rateio > 0){
            $proporcao = ( $rateio * ( ( $$valor_liquido*100 )/ $tot_valor_liquido) )/100;
          }
          $valor_a_gravar = ( $$valor_liquido - $proporcao );
          if( $valor_a_gravar < 0 ){
            $valor_a_gravar = 0;
          }
          $matriz2[1] = $registro_;
          $matriz2[2] = "0".$rubrica_varia;
          $matriz2[3] = $lotac_;
          $matriz2[4] = round( $valor_a_gravar, 2 );
          $matriz2[5] = 0;
          $matriz2[6] = 2;
          $matriz2[7] = 0;
          $matriz2[8] = $anousu;
          $matriz2[9] = $mesusu;
          $matriz2[10] = $DB_instit;
          $condicaoaux  = " and r14_regist = ".db_sqlformat( $registro_ );
          $condicaoaux .= " and r14_rubric = ".db_sqlformat( "0".$rubrica_varia);
          if( !db_selectmax( "gerfsal", "select r14_regist from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
             
             if( $valor_a_gravar  > 0){
               db_insert( "gerfsal", $matriz1, $matriz2 );
             }
          }else{
            if( $valor_a_gravar  > 0){
               db_update( "gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_").$condicaoaux );
            }else{
               db_delete( "gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
            }
         }
       }
     }
  }

  global $tot_desc, $tot_prov;

  $tot_desc = 0;
  $tot_prov = 0;


  $condicaoaux  = " and r14_regist = ".db_sqlformat( $registro_ );
  $condicaoaux .= " and r14_rubric <= 'R926' ";
  global $gerfsal;
  if( db_selectmax( "gerfsal", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
    for($Igerfsal=0;$Igerfsal< count($gerfsal);$Igerfsal++){
      if( $gerfsal[$Igerfsal]["r14_pd"] == 1){
        $tot_prov +=             $gerfsal[$Igerfsal]["r14_valor"];
      }else{
        $tot_desc +=             $gerfsal[$Igerfsal]["r14_valor"];
        if ($db_debug == true) {
            echo "[calculos_especificos_itaqui_ajuste] 3 - tot_desc: $tot_desc<br>"; 
          }
      }
    }
   if ($db_debug == true) { 
     echo "[calculos_especificos_itaqui_ajuste] 3 - calcula R928 <br>";
   } 

  }

  calcula_r928($registro_,$lotac_,1);


}

function funcionario_inicio_ferias(){

global $pessoal, $Ipessoal, $cadferia_;
  global $anousu, $mesusu, $DB_instit;

  // funcao utilizada tambem no fpag162 (ferias)
  
  $retorno = false;

  if(!db_empty( $pessoal[$Ipessoal]["r01_recis"] ) ){
     $retorno = true;
  }else{
     $condicaoaux  =  " and r30_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
     $condicaoaux .= " order by r30_regist,r30_perai desc limit 1 ";

  // verificar se e necessario db_select em cadferia ou so select

     if(db_selectmax( "cadferia_", "select r30_per1i,r30_per2i,r30_regist,r30_perai from cadferia ".bb_condicaosubpes( "r30_" ).$condicaoaux)){
        if( db_empty($cadferia_[0]["r30_proc2"]) ){
             $r30_peri = "r30_per1i";
          }else{
             $r30_peri = "r30_per2i";
          }
  
        if( db_month( $cadferia_[0][$r30_peri] ) == $mesusu && db_year( $cadferia_[0][$r30_peri] ) == $anousu ){
            $retorno = true;
        }
     }
  }
  return $retorno;
}                 


// So desconta se o liquido for maior que 30% do valor do Provento

function calculos_especificos_bage($opcao_geral){

  Global $r110_regist, $transacao, $transacao1, $tot_prov, $tot_desc, $pessoal, $Ipessoal,$matriz1,$matriz2;
  global $anousu, $mesusu, $DB_instit, $db_debug;

  $r110_regist = $pessoal[$Ipessoal]["r01_regist"];

  if( !db_empty($tot_prov) || !db_empty($tot_desc)){

   // so pode consignar 70% porcento do salario 

    // '0687', '0609', '0610', '0634', '0608', '0669', '0448', '0502', '0680', '0697', '0504',
    // '0505', '0656', '0663', '0533', '0642', '0614', '0613', '0615', '0433', '0692', '0694'

   $condicaoaux  = " and r14_regist = " . db_sqlformat( $r110_regist );
    $condicaoaux .= " and r14_rubric in ('0687', '0609', '0610', '0634', '0608', '0669', '0448', '0502', '0680', '0697', '0504',
                                         '0505', '0656', '0663', '0533', '0642', '0614', '0613', '0615', '0433', '0692', '0694')";
    if( db_selectmax( "transacao", "select sum(case when r14_rubric = '0687' then r14_valor else 0 end) as rub_0687,
                                           sum(case when r14_rubric = '0609' then r14_valor else 0 end) as rub_0609,
                                           sum(case when r14_rubric = '0610' then r14_valor else 0 end) as rub_0610,
                                           sum(case when r14_rubric = '0634' then r14_valor else 0 end) as rub_0634,
                                           sum(case when r14_rubric = '0608' then r14_valor else 0 end) as rub_0608,
                                           sum(case when r14_rubric = '0669' then r14_valor else 0 end) as rub_0669,
                                           sum(case when r14_rubric = '0448' then r14_valor else 0 end) as rub_0448,
                                          sum(case when r14_rubric = '0502' then r14_valor else 0 end) as rub_0502,
                                           sum(case when r14_rubric = '0680' then r14_valor else 0 end) as rub_0680,
                                           sum(case when r14_rubric = '0697' then r14_valor else 0 end) as rub_0697,
                                           sum(case when r14_rubric = '0504' then r14_valor else 0 end) as rub_0504,
                                           sum(case when r14_rubric = '0505' then r14_valor else 0 end) as rub_0505,
                                          sum(case when r14_rubric = '0656' then r14_valor else 0 end) as rub_0656,
                                           sum(case when r14_rubric = '0663' then r14_valor else 0 end) as rub_0663,
                                           sum(case when r14_rubric = '0533' then r14_valor else 0 end) as rub_0533,
                                           sum(case when r14_rubric = '0642' then r14_valor else 0 end) as rub_0642,
                                          sum(case when r14_rubric = '0614' then r14_valor else 0 end) as rub_0614,
                                           sum(case when r14_rubric = '0613' then r14_valor else 0 end) as rub_0613,
                                          sum(case when r14_rubric = '0615' then r14_valor else 0 end) as rub_0615,
                                           sum(case when r14_rubric = '0433' then r14_valor else 0 end) as rub_0433,
                                           sum(case when r14_rubric = '0692' then r14_valor else 0 end) as rub_0692,
                                           sum(case when r14_rubric = '0694' then r14_valor else 0 end) as rub_0694,
                                          sum(case when r14_rubric in ('0277','0278','0373') then r14_valor else 0 end) as abate
                                   from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
      
      $prov_tot = $tot_prov - $transacao[0]["abate"]; 

      $liquido_folha = round($prov_tot - $tot_desc,2);
      $minimo = round($prov_tot * 0.3,2);
      if( $liquido_folha <= $minimo){

        // 0505 DESCONTO SIMBA - DIVERSOS
        // 0636 CARTAO SERVIDOR - BANRISUL
        // 0647 SERVICE CARD
        // 0642 GBOEX
        // 0533 GBOEX PECULIO
        // 0502 DESC CAIXA ECONOMICA FEDERAL
        // 0656 DESCONTO BANCO DO BRASIL
        // 0607 AFUNCAB - DIVERSOS
        // 0614 DESCONTO UNIODONTO - MENSALIDA
        // 0615 DESCONTO UNIODONTO - DIVERSOS
        // 0648 SERVICE CARD MENSALIDADE

         $condicaoaux  = " and r14_regist = " . db_sqlformat( $r110_regist );
         $condicaoaux .= " and r14_rubric = 'R928'";
         if( db_selectmax( "transacao1", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
            db_delete("gerfsal", bb_condicaosubpes("r14_").$condicaoaux) ;
         }
   
         $rub_cod = array();
         $rub_vlr = array();
   
          $rub_cod[22] = "0687";
          $rub_cod[21] = "0609";
          $rub_cod[20] = "0610";
          $rub_cod[19] = "0634";
          $rub_cod[18] = "0608"; 
          $rub_cod[17] = "0669";
          $rub_cod[16] = "0448";
          $rub_cod[15] = "0502";
          $rub_cod[14] = "0680";
          $rub_cod[13] = "0697";
          $rub_cod[12] = "0504";
          $rub_cod[11] = "0505";
          $rub_cod[10] = "0656";
          $rub_cod[9]  = "0663";
          $rub_cod[8]  = "0533";
          $rub_cod[7]  = "0642";
          $rub_cod[6]  = "0614";
          $rub_cod[5]  = "0613";
          $rub_cod[4]  = "0615";
          $rub_cod[3]  = "0433";
          $rub_cod[2]  = "0692";
          $rub_cod[1]  = "0694";

          $rub_vlr[22] = $transacao[0]["rub_0687"];
          $rub_vlr[21] = $transacao[0]["rub_0609"];
          $rub_vlr[20] = $transacao[0]["rub_0610"];
          $rub_vlr[19] = $transacao[0]["rub_0634"];
          $rub_vlr[18] = $transacao[0]["rub_0608"]; 
          $rub_vlr[17] = $transacao[0]["rub_0669"];
          $rub_vlr[16] = $transacao[0]["rub_0448"];
          $rub_vlr[15] = $transacao[0]["rub_0502"];
          $rub_vlr[14] = $transacao[0]["rub_0680"];
          $rub_vlr[13] = $transacao[0]["rub_0697"];
          $rub_vlr[12] = $transacao[0]["rub_0504"];
          $rub_vlr[11] = $transacao[0]["rub_0505"];
          $rub_vlr[10] = $transacao[0]["rub_0656"];
          $rub_vlr[9]  = $transacao[0]["rub_0663"];
          $rub_vlr[8]  = $transacao[0]["rub_0533"];
          $rub_vlr[7]  = $transacao[0]["rub_0642"];
          $rub_vlr[6]  = $transacao[0]["rub_0614"];
          $rub_vlr[5]  = $transacao[0]["rub_0613"];
          $rub_vlr[4]  = $transacao[0]["rub_0615"];
          $rub_vlr[3]  = $transacao[0]["rub_0433"];
          $rub_vlr[2]  = $transacao[0]["rub_0692"];
          $rub_vlr[1]  = $transacao[0]["rub_0694"];
   
   
         $desc_tot = $tot_desc;
   
          for($nro_rub = 1;$nro_rub<23;$nro_rub++){
            if( $rub_vlr[$nro_rub] == 0){
               continue;
            }
            $condicaoaux  = " and r14_regist = ".db_sqlformat( $r110_regist );
            $condicaoaux .= " and r14_rubric = ".db_sqlformat( $rub_cod[$nro_rub] );
            $desc_tot    -=  $rub_vlr[$nro_rub];
            $saldo        = $prov_tot - $desc_tot;
            if( $saldo > $minimo){
             $tot_desc -= ($rub_vlr[$nro_rub] - ($saldo - $minimo));
              if ($db_debug == true) { echo "[calculos_especificos] 4 - tot_desc: $tot_desc<br>"; }
               $matriz1 = array();
               $matriz2 = array();
               $matriz1[1] = "r14_valor";
               $matriz2[1] = ($saldo - $minimo);
               db_update( "gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_").$condicaoaux );
               break ;
            }else if( $saldo <= $minimo   ){ 
              db_delete("gerfsal",bb_condicaosubpes("r14_").$condicaoaux );
              $tot_desc -= $rub_vlr[$nro_rub];
              if ($db_debug == true) { echo "[calculos_especificos] 5 - tot_desc: $tot_desc<br>"; }
            }
         }  
     }
 }
 
  } 
 if ($db_debug == true) { 
    echo "[calculos_especificos] 4 - calcula R928 <br>";
 }
 calcula_r928($pessoal[$Ipessoal]["r01_regist"],$pessoal[$Ipessoal]["r01_lotac"],$opcao_geral);
}


function meses_entredatas($datainicial, $datafinal, $contamesinicio=null, $contamesfinal=null, $quinzedias=null){
/*
  alteracoes para considera dias proporcionais no mes...;
  ver com paulo mais detalhes;
  numero meses que esta avaliando;
  proporcionalizar pagamento;
  difernca entre avaliacao feita no mesdb_atual em relacao a avaliacoa do;
  mes anterior;
*/

if( !$contamesinicio){
   //echo "<BR> datainicial --> ".substr("#".db_dtoc($datainicial),4,7);
   $datainicial = date("Y-m-d",db_mktime($datainicial) + (ndias( substr("#". db_dtoc($datainicial),4,7) ) * 86400));
}
if( !$contamesfinal){
   //echo "<BR> datainicial --> ".substr("#".db_dtoc($datainicial),4,7);
   $datafinal = date("Y-m-d",db_mktime($datafinal) - ( (ndias( substr("#". db_dtoc($datafinal),4,7) ) + 1) * 86400 ) );
}
if( $quinzedias){
   if( db_day( $datainicial ) > 15){
      $anoi = db_year($datainicial);
      $mesi = db_month($datainicial);
      $mesi += 1;
      if( $mesi > 12){
         $mesi = 12;
         $anoi += 1;
      }
      $datainicial = db_ctod( "01/".db_str($mesi,2,0,"0")."/".db_str($anoi,4,0) );
   }
   if( db_day( $datafinal ) < 15){
      $anoi = db_year($datafinal);
      $mesi = db_month($datafinal);
      $mesi -= 1;
      if( $mesi <= 0){
         $mesi = 12;
         $anoi -= 1;
      }
      //echo "<BR> 1 - dia --> ".str($mesi,2,0,"0")."/".str($anoi,4,0);
      $dia = db_str( ndias( db_str($mesi,2,0,"0")."/".db_str($anoi,4,0) ) , 2,0,"0" );
      $datafinal = db_ctod( $dia."/".db_str($mesi,2,0,"0")."/".db_str($anoi,4,0) );
   }else{
      $anoi = db_year($datafinal);
      $mesi = db_month($datafinal);
      //echo "<BR> 2 - dia --> ".str($mesi,2,0,"0")."/".str($anoi,4,0);
      $dia = db_str( ndias( db_str($mesi,2,0,"0")."/".db_str($anoi,4,0) ) , 2,0,"0" );
      $datafinal = db_ctod( $dia."/".db_str($mesi,2,0,"0")."/".db_str($anoi,4,0) );
   }
}
$contou = false;
$mesescontados = 0;
if(db_mktime($datainicial) < db_mktime($datafinal) ){
   $datai = $datainicial;
   $mesescontados += 1;
   while (db_mktime($datai) < db_mktime($datafinal)){
      //echo "<BR> 3 - datai --> $datai e  datafinal --> $datafinal";
     
      $dataant = $datai;
      $anoi = db_year($datai);
      //echo "<BR> 3.1 - anoi --> $anoi";
      $mesi = db_month($datai);
      $mesi += 1;
      if( $mesi > 12){
         $mesi = 1;
         $anoi += 1;
      }

      //echo "<BR> 3 - dia --> ".db_str($mesi,2,0,"0")."/".db_str($anoi,4,0,"0");
      $diasdomes = ndias( db_str($mesi,2,0,"0")."/".db_str($anoi,4,0,"0") );
      $dia = db_str((db_day($datainicial) > $diasdomes ? $diasdomes: db_day($datainicial) ), 2,0,"0");
      $datai = db_ctod( $dia."/".db_str($mesi,2,0,"0")."/".db_str($anoi,4,0,"0") ) ;
      //echo "<BR> 3 - datai--> $datai";

      if( db_mktime($datai) < db_mktime($datafinal)){
         $mesescontados += 1;
         $contou = true;
      }else if( ( db_month( $datai ) == db_month( $datafinal ) ) && ( db_year( $datai ) == db_year( $datafinal ) ) && $contamesfinal ) {
        
         $mesescontados += 1;
         $contou = true;
      }else{
         $contou = false;
      }
   }
   //echo "<BR> 3 - saiu do loop --> $datai e  datafinal --> $datafinal";
   if( db_mktime($dataant) < db_mktime($datafinal) && !$contou && !$quinzedias){
      $mesescontados += 1;
   }
}else if( db_mktime($datainicial) == db_mktime($datafinal)){ 
   $mesescontados = 1;
}

return $mesescontados;

}

function le_adi_fer($rubrica, $area1, $sigla2,$nro_registro){

  global $carregarubricas_geral;
  global $anousu, $mesusu, $DB_instit;
  
  $formula = $rubrica;
  $rubrica_contem = $carregarubricas_geral[$rubrica];
// echo "<BR> rubrica --> $rubrica   rubrica_contem --> $rubrica_contem"; 
  if( $rubrica_contem == "+" || $rubrica_contem == "-"){
     $formula = "valor_zerado";
  }else{
     $formula = substr("#".$rubrica_contem,2,strlen($rubrica_contem)-1);
     if(db_at("B",$formula) != 0){
  if( $area1 == "gerffer"){
//echo "<BR> 1 area1 --> $area1";  
    $formula = le_var_bxxx($formula,"pontofe", $area1,"r29", $sigla2, 0,$rubrica);
  }else{
//echo "<BR> 2 area1 --> $area1";  
    $formula = le_var_bxxx($formula,"pontofx", $area1,"r90", $sigla2, $nro_registro,$rubrica);
  }
     }
  }
//echo "<BR> formula --> $formula";  
  return $formula;
}

// Rubricas que sao calculadas pegando por base o liquido (prov - desc) e aplicado um percentual que é indicado no
// campo quantidade da rubrica


function calculos_desconto_liquido_generico_ajuste($registro_, $lotac_){

  global $cfpess,$rubricas_in,$pontofs_,$gerfsal_,$subpes; 
  global $anousu, $mesusu, $DB_instit;
  
  $rubricas_ = trim( $cfpess[0]["r11_desliq"] );

  $condicaoaux  = " and r10_regist  = ".db_sqlformat( $registro_ );
  $condicaoaux .= " and r10_rubric in ".$rubricas_in;
  $condicaoaux .= " and r10_valor = 0 " ;
  if( db_selectmax( "pontofs_", "select r10_rubric from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )){
     for($Ipontofs_=0;$Ipontofs_< count($pontofs_);$Ipontofs_++){
  $rubrica_ = $pontofs_[$Ipontofs_]["r10_rubric"] ;
  $calcula_valor = "calcula_valor_".$rubrica_;
  global $$calcula_valor;
  $$calcula_valor = true;
     }
  }else{
     return;
  }
  $salfamilia = 0;
  $tot_prov = 0;
  $tot_desc = 0;
  $valor_obrigacoes = 0;
  $valor_proventos_base = 0;
  $valor_descontos_base = 0;

  $condicaoaux  = " and r14_regist = ".db_sqlformat( $registro_ );
  $condicaoaux .= " and ( r14_rubric <= 'R920' or r14_rubric = 'R927' )";
  if( db_selectmax( "gerfsal_", "select r14_valor,r14_pd,r14_rubric from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
     for($Igerfsal=0;$Igerfsal<count($gerfsal_);$Igerfsal++){
   if( $gerfsal_[$Igerfsal]["r14_pd"] == 1 && substr("#".$gerfsal_[$Igerfsal]["r14_rubric"],1,1) != "R" ){
      $valor_proventos_base += $gerfsal_[$Igerfsal]["r14_valor"];
   }else if( $gerfsal_[$Igerfsal]["r14_rubric"] >= 'R901' && $gerfsal_[$Igerfsal]["r14_rubric"] <= 'R916'){ 
      $valor_obrigacoes += $gerfsal_[$Igerfsal]["r14_valor"];
   }else if( $gerfsal_[$Igerfsal]["r14_rubric"] >= 'R918' && $gerfsal_[$Igerfsal]["r14_rubric"] <= 'R920'){ 
      $salfamilia += $gerfsal_[$Igerfsal]["r14_valor"];
   }else if( $gerfsal_[$Igerfsal]["r14_pd"] == 2 ){ 
      $valor_descontos_base += $gerfsal_[$Igerfsal]["r14_valor"];
   }
     }
  }
  $tot_prov = $valor_proventos_base;
  $tot_desc = $valor_obrigacoes + $valor_descontos_base ;
  if ($db_debug == true) { 
    echo "[calculos_desconto_liquido_generico_ajuste 1 - tot_desc: $tot_desc<br>";
  }

  $valor_liquido = $valor_proventos_base - $valor_obrigacoes;
  if( $valor_liquido  <= 0){
     return;
  }

  $matriz1 = array();
  $matriz2 = array();

  $matriz1[1] = "r14_regist";
  $matriz1[2] = "r14_rubric";
  $matriz1[3] = "r14_lotac";
  $matriz1[4] = "r14_valor";
  $matriz1[5] = "r14_quant";
  $matriz1[6] = "r14_pd";
  $matriz1[7] = "r14_semest";
  $matriz1[8] = "r14_anousu";
  $matriz1[9] = "r14_mesusu";
  $matriz1[10] = "r14_instit";

  $retornar = true ;

  for($I=0;$I < strlen($rubricas_);$I+=4){
     $rubrica_varia = substr("#". $rubricas_,$I,4 );
     $verifica_calcula_valor = "calcula_valor_".$rubrica_varia;
     global $$verifica_calcula_valor;
     if( ( $$verifica_calcula_valor )){
  $condicaoaux  = " and rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat( $rubrica_varia );
  global $rubr_;
  db_selectmax( "rubr_", "select rh27_pd from rhrubricas ".$condicaoaux );

  $condicaoaux  = " and r10_regist  = ".db_sqlformat( $registro_ );
  $condicaoaux .= " and r10_rubric = '".$rubrica_varia."'" ;
  $condicaoaux .= " and r10_valor = 0 " ;
  if( db_selectmax( "pontofs_", "select r10_quant from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )){

      $valor_a_gravar = round( $valor_liquido * ($pontofs_[0]["r10_quant"]/100),2);

      $matriz2[1] = $registro_;
      $matriz2[2] = $rubrica_varia;
      $matriz2[3] = $lotac_;
      $matriz2[4] = $valor_a_gravar;
      $matriz2[5] = $pontofs_[0]["r10_quant"];
      $matriz2[6] = $rubr_[0]["rh27_pd"];
      $matriz2[7] = 1;
      $matriz2[8] = $anousu;
      $matriz2[9] = $mesusu;
      $matriz2[10] = $DB_instit;
      $retornar = true;
      $condicaoaux  = " and r14_regist = ".db_sqlformat( $registro_ );
      $condicaoaux .= " and r14_rubric = ".db_sqlformat( $rubrica_varia);
      global $gerfsal_;
      if( !db_selectmax( "gerfsal_", "select r14_regist from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
         if( $valor_a_gravar  > 0){
       db_insert( "gerfsal",$matriz1, $matriz2 );
         }
      }else{
         if( $valor_a_gravar  > 0){
       db_update( "gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_").$condicaoaux );
         }
      }
      if( $rubr_[0]["rh27_pd"] == 2 ){
    $tot_desc += $valor_a_gravar;
    if ($db_debug == true) {
          echo "[calculos_desconto_liquido_generico_ajuste] 2 - tot_desc: $tot_desc<br>";
        }
      }else{
    $tot_prov += $valor_a_gravar;
      }
      if( !$retornar){
         break;
      }
  }
     }
  }

  if( $retornar){
     if( !db_empty($tot_prov) || !db_empty($tot_desc)){
//        $tot_prov = db_val(substr("#".db_str($tot_prov,22,5),1,19));
//        $tot_desc = db_val(substr("#".db_str($tot_desc,22,5),1,19));
  if( $tot_prov > $tot_desc){
     $r01_rubric = "R926";
     $tot_liq = $tot_prov + $salfamilia  - $tot_desc;
     $arredn = arredonda_100($tot_liq, $cfpess[0]["r11_arredn"]);
     $tot_liq += $arredn;

     $condicaoaux  = " and r14_regist = ".db_sqlformat( $registro_ );
     $condicaoaux .= " and r14_rubric in ('R928') ";
     db_delete( "gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
  }else{
     $arredn = ( $tot_desc ) - $tot_prov;
     $r01_rubric = "R928";

     $condicaoaux  = " and r14_regist = ".db_sqlformat( $registro_ );
     $condicaoaux .= " and r14_rubric in ('R926') ";
     db_delete( "gerfsal", bb_condicaosubpes("r14_").$condicaoaux );

  }
  $condicaoaux  = " and r14_regist = ".db_sqlformat( $registro_ );
  $condicaoaux .= " and r14_rubric = ".db_sqlformat( $r01_rubric );
  if( $arredn > 0){
     if( !db_selectmax( "gerfsal_", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
        $acao = "insere";
     }else{
        $acao = "altera";
     }
     $matriz2[1] = $registro_;
     $matriz2[2] = $r01_rubric;
     $matriz2[3] = $lotac_;
     $matriz2[4] = $arredn;
     $matriz2[5] = 0;
     $matriz2[6] = 1;
     $matriz2[7] = 1;
     $matriz2[8] = $anousu;
     $matriz2[9] = $mesusu;
     $matriz2[10] = $DB_instit;

     if( $acao == "altera"){
        db_update( "gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_").$condicaoaux );
     } else {
       
       if ($db_debug == true) {
          echo "[calculos_desconto_liquido_generico_ajuste] 12 - Insert: Gerfsal<br>";
          echo "Dados: <br>";
          echo "r14_regist: ".$matriz2[1]."<br>";
          echo "r14_rubric:".$matriz2[2]."<br>";
          echo "r14_lotac:".$matriz2[3]."<br>";
          echo "r14_valor:".$matriz2[4]."<br>";
          echo "r14_quant:".$matriz2[5]."<br>";
          echo "r14_pd:".$matriz2[6]."<br>";
          echo "r14_semest:".$matriz2[7]."<br>";
          echo "r14_anousu:".$matriz2[8]."<br>";
          echo "r14_mesusu:".$matriz2[9]."<br>";
          echo "r14_instit:".$matriz2[10]."<br>";
          echo "<br>";
       }
       db_insert( "gerfsal", $matriz1, $matriz2 );
     }
  }else{
//    echo "<BR> passou aqui 1";
     db_delete( "gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
  }
     }
  }
}

/// le_rubricas_condicao ///

// Carrega a matriz "carregarubricas_geral", com a formula na qual a condicao para carrega-la seja a verdadeira 
// ,è possivel programar 3 tipos de formulas e associar a cada uma, uma condicao para dispara-la.
// Prevalece a formula com a ultima condicao verdadeira ( todas as tres condicoes sao avaliadas)

function le_rubricas_condicao($qual_parametro=null){
   global $db_debug;
      
   if ( func_num_args() == 1 ) {
     
     switch ($qual_parametro) {
       
       case 'pontofx' :
          
         $qual_ponto_decisao = true;
         break;
         
       case 'gerfprovfer':
         
         $qual_ponto_decisao = true;
         break;
       default:

         $qual_ponto_decisao = false;
         break;  
     }
   }
  
  global $rubricas1,$Ipessoal,$pessoal,$carregarubricas,$carregarubricas_geral,$chamada_geral_arquivo,$db_debug; 
  global $anousu, $mesusu, $DB_instit;
 
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;

  global $quais_diversos;
  eval($quais_diversos);

  $condicaoaux  = " and ( rh27_form2 <> ' ' or  rh27_form3 <> ' ')";
  db_selectmax("carregarubricas","select * from rhrubricas where rh27_instit = $DB_instit ".$condicaoaux." order by rh27_rubric" );
  
  for($Icarregar=0;$Icarregar<count($carregarubricas);$Icarregar++){
     
      $r10_pd = ( $carregarubricas[$Icarregar]["rh27_pd"] != 2 );
      $formula = $carregarubricas[$Icarregar]["rh27_form"];
      $cond = trim($carregarubricas[$Icarregar]["rh27_cond2"]);
      $cond = str_replace('$ipessoal','$Ipessoal',$cond);
      if( !db_empty($cond) ){
        $cond = '$condicao = '.$cond.";";
        //echo "<BR> condicao 2 -> : $cond rubrica --> ".$carregarubricas[$Icarregar]["rh27_rubric"];;
  
        ob_start();
        eval(stripslashes($cond));
        db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$cond,$carregarubricas[$Icarregar]["rh27_rubric"]); 
        //echo "<BR> condicao 2.1 -> ".($condicao?"01":"02");
        if( $condicao ){
        $formula =  $carregarubricas[$Icarregar]["rh27_form2"];
        }
      }
      $cond = trim($carregarubricas[$Icarregar]["rh27_cond3"]);
      $cond = str_replace('$ipessoal','$Ipessoal',$cond);
      if( !db_empty($cond) ){
        $cond = '$condicao = '.$cond.';';
        //echo "<BR> condicao 3 -> : $cond";
        //echo "<BR> condicao 3.1 -> ".($condicao?"01":"02");

        ob_start();
        eval(stripslashes($cond));
        db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$cond,$carregarubricas[$Icarregar]["rh27_rubric"]); 
        if( $condicao ){
          $formula =  $carregarubricas[$Icarregar]["rh27_form3"];
        }
      }
      $r10_form = '('.trim($formula).')';
      if( !$r10_pd){
         $r10_form = "-".$r10_form;
      }else{
         $r10_form = "+".$r10_form;
      }
      $r10_form = str_replace('D','$D',$r10_form);
      $r10_form = str_replace('F','$F',$r10_form);
      
      $carregarubricas_geral[$carregarubricas[$Icarregar]["rh27_rubric"]] = $r10_form;
   }   
}

/// le_salariofamilia ///


// Retorna o valor do salario familia conforme a quantidade de dependentes e o regime


function le_salfamilia($salario,$r01_regime,$r01_tbprev){

  global $d08_carnes;
  global $anousu, $mesusu, $DB_instit;
  global $F001,$F002,$F003,$F004,$F005,$F006,$F007,$F008,$F009,$F010,$F011,$F012,$F013,$F014,$F015,$F022,$F024,$F025;
  global $D919,$D914,$D906,$D917,$D918,$D903,$D904;
  global $r14_quant ,$r20_quant, $r22_quant;

  global $quais_diversos;
  eval($quais_diversos);
    
  //;
  //se regime 1 e tabela nao inss - r918 ;
  //se regime 1 e tabela inss     - r919 e r918 (complemento ou integral se;
  //                                passar do limite );
  //se regime 3 e tabela nao inss - r920;
  //se regime 3 e tabela inss     - r919 e r920 (complemento ou integral se;
  //                                passar do limite da base );
  //se regime 2 /tabela inss      - r919;
  //;
  //r919 - sempre parte do inss;

  $calculo = 0;
  $r14_quant=$F006;
  $r20_quant=$F006;
  $r22_quant=$F006;
  
  if($d08_carnes == "riogrande"){
     if( $r01_regime == 1 ){
        //  estatutario e estatutario em extinsao;
        //  D903 - salario familia para estes regime;
        //
        // D907 % SAL FAMILIA P/ DEPENDENTE
      $calculo = ($D903*($D907/100)*$F006) ;
     }else if($r01_regime == 3){
        //  clt e clt em extinsao;
        //  D904 - salario familia maior valor;
  if ($r01_regime == 3){
     $F006 = $F006_clt;
  }
  // D904 MAIOR VALOR DO SALARIO BASE
  if($salario <= $D904){
     //  D906 - valor maximo do salario familia ;
     //
     $calculo = ($D906*$F006);
  }else{
     $calculo = 0;
  }
     }

  }else if($d08_carnes == "viamao"){

     if( $r01_tbprev == 2){
        // D914 VALOR MAXIMO SALARIO FAM.ESTAT
  if($salario <= $D914){
     $calculo = $F006 * $D920;
  }else{ 
     $calculo = $F006 * $D921;
  }
     }else{
  //  clt e clt em extinsao;
  //  d904 - salario familia maior valor;
  //
  if($salario <= $D904){
     //  D906 - valor maximo do salario familia;
     //
     $calculo = ($D906*$F006);
  }else{
     $calculo = 0;
  }
     }

  }else{

     //echo "<BR> le_salariofamilia D903 1 --> $D903";     
     //echo "<BR> le_salariofamilia D907 1 --> $D907";     
     //echo "<BR> le_salariofamilia D914 1 --> $D914";     
     //echo "<BR> le_salariofamilia D916 1 --> $D916";     
      //echo "<BR> le_salariofamilia D917 1 --> $D917";    
     if( $r01_regime == 1 || $r01_regime == 3 ){
        //  estatutario e estatutario em extinsao;
        //  D903 - salario familia para estes regime;
        //  D914 VALOR MAXIMO SALARIO FAM.ESTAT
        if( $salario <= $D914){
          // D903 BASE SALARIO FAMILIA (1 E 3)
          // D907 % SAL FAMILIA P/ DEPENDENTE
          $calculo = ( round( $D903 * ($D907/100), 2 ) * $F006 ) ;
          //echo "<BR> le_salariofamilia calculo 1 --> $calculo";    
          if( $salario <= $D916){
            // D907 % SAL FAMILIA P/ DEPENDENTE
            $calculo = ( round( $D917 * ($D907/100), 2 ) * $F006 ) ;
            //echo "<BR> le_salariofamilia calculo 2 --> $calculo";    
          }
        }  
     }else{
        //  clt e clt em extinsao;
        //  D904 - salario familia maior valor;
        if( $salario <= $D904){
           //  D906 - valor maximo do salario familia ;
           $calculo = round($D906*$F006,2);
           if( $salario <= $D918){
             $calculo = round($D919*$F006,2);
           }
        }else{
           $calculo = 0;
        }   
     }
  }
  return $calculo;
}

/// fim da funcao le_salariofamilia ///

/// gerfadi ///

function gerfadi($opcao_geral=null,$opcao_tipo=1){
  // globais de outras funcoes
  global $campos_pessoal, $r110_regisi, $r110_regisf, $subpes,$chamada_geral,$chamada_geral_arquivo,$pessoal,$Ipessoal,$transacao,$cfpess;
  global $anousu, $mesusu, $DB_instit;

  global $situacao_funcionario,$naoencontroupontosalario,$d08_carnes,$carregarubricas,$diversos,$Iinssirf,$inssirf,$subpes,$cadferia;

  global $dias_pagamento,$mes,$ano,$r110_lotac,$carregarubricas_geral;
  global $rubricas,$prev_desc;
  
  // GLOBAIS QUE PRECISAM MIGRAR PARA OUTRAS FUNCOES
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  

  global $quais_diversos;
  eval($quais_diversos);

  global $dtot_vpass,$dperc_pass,$dquant_pass,$quant_pass,$contador_registros;
  
  global $r110_regist;
 
  global $opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,$r110_lotacf,$faixa_regis,$faixa_lotac;
  
  global $dias_pagamento, $data_afastamento, $dias_pagamento_sf, $dtfim;
 
  // esta variavel abaixo nao precisa mais
  $siglag = "r22_";
  $siglap = "r21_";
  

if( $opcao_tipo == 2){

//   $condicaoaux  = " and  r01_recis is null ";
//   $condicaoaux .= " order by r01_regist ";
//   db_selectmax( "pessoal", "select ".$campos_pessoal." from pessoal ".bb_condicaosubpes( "r01_" ).$condicaoaux );

      $condicaoaux  = " and rh05_recis is null ";
      $condicaoaux .= " order by rh02_regist ";
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
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );
}else{

   if( $opcao_filtro != "0" ){
       
       $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"rh02_",$r110_regisi,$r110_regisf,$r110_lotaci,
                                     $r110_lotacf,$faixa_regis,$faixa_lotac);
  
//      db_selectmax( "pessoal", "select ".$campos_pessoal." from pessoal ".bb_condicaosubpes( "r01_" ).$condicaoaux );

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
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                   left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                   left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                   left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                   left join rhpesprop on rh19_regist = rh02_regist
                   ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,
                                    $r110_lotacf,$faixa_regis,$faixa_lotac);
      
      db_delete( $chamada_geral_arquivo, bb_condicaosubpes( $siglag ).$condicaoaux );

   }

   $retornar = true;
}


$contador_registros = 1;

$matriz1 = array();
$matriz2 = array();

$matriz1[1] = "r22_regist";
$matriz1[2] = "r22_rubric";
$matriz1[3] = "r22_lotac";
$matriz1[4] = "r22_valor";
$matriz1[5] = "r22_quant";
$matriz1[6] = "r22_pd";
$matriz1[7] = "r22_anousu";
$matriz1[8] = "r22_mesusu";
$matriz1[9] = "r22_instit";

for($Ipessoal=0;$Ipessoal<count($pessoal);$Ipessoal++){

   db_atutermometro($Ipessoal,count($pessoal),'calculo_folha',1);

   $condicaoaux  = " and r21_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"]);
   $condicaoaux .= " order by r21_regist,r21_rubric ";
   global $pontofa;
   if( !db_selectmax( "pontofa", "select * from pontofa ".bb_condicaosubpes( "r21_" ).$condicaoaux )){
      continue;
   }
   $situacao_funcionario = situacao_funcionario($pessoal[$Ipessoal]["r01_regist"]);
   // para parobe, se o funcionario estiver comecando as ferias no mes;
   // o funcionario nao deve receber adiantamento de salario.;
   if( db_at(db_str($situacao_funcionario,1),"2-7") > 0 || !db_empty($pessoal[$Ipessoal]["r01_recis"]) || ( trim($d08_carnes)=="parobe" 
       && funcionario_inicio_ferias() ) ) {
      continue;
   }
     
   carrega_fxxx($pontofa[0]["r21_regist"],true,"gerfadi");

   le_rubricas_condicao();
   
   $r110_regist = $pontofa[0]["r21_regist"];
   $r110_lotac  = $pontofa[0]["r21_lotac"];
   $base_inss   = 0;
   $inss_desc   = 0;
   $base_irf    = 0;
   $valor904    = 0;
   $r22_pd      = 0;
   $tot_prov    = 0;
   $tot_desc    = 0;
   if ($db_debug == true) {
     echo "[gerfadi] 8 - total de desconto: $tot_desc<br>";
   }
   for($Ipontofa=0;$Ipontofa<count($pontofa);$Ipontofa++){
      $r22_quant   = $pontofa[$Ipontofa]["r21_quant"];
      $r22_form    = $pontofa[$Ipontofa]["r21_rubric"];
      $r22_valor   = $pontofa[$Ipontofa]["r21_valor"];
      
//echo "<BR> r22_rubric 1 --> $r22_form";      
//echo "<BR> r22_quant  1 --> $r22_quant";      
//echo "<BR> r22_valor  1 --> $r22_valor";      
      global $rub_;
      $condicaoaux = " where rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat($pontofa[$Ipontofa]["r21_rubric"]);
      db_selectmax( "rub_", "select * from rhrubricas ".$condicaoaux );
      
      $r22_pd = $rub_[0]["rh27_pd"];
      
      global $pontofx, $proc_ler_var_bxxx; 
      $condicaoaux = " and r90_regist = ".db_sqlformat( $r110_regist );
      $proc_ler_var_bxxx = db_selectmax( "pontofx", "select * from pontofx ".bb_condicaosubpes( "r90_" ).$condicaoaux );
      
      if($proc_ler_var_bxxx) {
         $r07_form = le_adi_fer(trim($pontofa[$Ipontofa]["r21_rubric"]),"gerfadi","r22",0);
      }else{
   $r07_form = "base_zerada";
      }  
      
      // primeiro if alterado para que seja possivel incluir um adiantamento ;
      // por valor;
      if( $r07_form == "valor_zerado" || db_empty($r07_form) || (!db_empty($r07_form) && !db_empty($pontofa[$Ipontofa]["r21_valor"] ) ) ) {
         $r14_valor = $pontofa[$Ipontofa]["r21_valor"];
         if ($db_debug == true) {
           echo "[gerfadi] 1 - r14_valor = $r14_valor  <br>";
         } 
      }else if( $r07_form == "base_zerada"){ 
         $r14_valor = 0;
         if ($db_debug == true) {
           echo "[gerfadi] 2 - r14_valor = $r14_valor  <br>";
         }
      }else{
         $r01_form = '$r07_form  = '.$r07_form.";";
         ob_start();
         eval($r01_form);
         db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontofa[$Ipontofa]["r21_rubric"]); 
         $r22_valor = $pontofa[$Ipontofa]["r21_quant"] * $r07_form;
         $r22_quant = $pontofa[$Ipontofa]["r21_quant"];
      }

      // proporcionaliza valores dos inativos conforme cadastro e rubricas;
      // r01_propi --> Perc.Inativo
      if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) != 'a' && $pessoal[$Ipessoal]["r01_propi"] > 0 && $pessoal[$Ipessoal]["r01_propi"] < 100
    && ('t' == $rub_[0]["rh27_propi"] ) ) {
         $r22_valor = round( $r22_valor * ( $pessoal[$Ipessoal]["r01_propi"] / 100 ),2 ) ;
      }

//echo "<BR> r22_rubric 2 --> ".$pontofa[$Ipontofa]["r21_rubric"];      
//echo "<BR> r22_quant  2 --> $r22_quant";      
//echo "<BR> r22_valor  2 --> $r22_valor";      
//echo "<BR> r22_valor  3 --> ".round($r22_valor,2);      
      if( $r22_valor > 0){
         $matriz1 = array();
   $matriz2 = array();
         $matriz1[1] = "r22_regist";
         $matriz1[2] = "r22_rubric";
         $matriz1[3] = "r22_lotac";
         $matriz1[4] = "r22_valor";
         $matriz1[5] = "r22_quant";
         $matriz1[6] = "r22_pd";
         $matriz1[7] = "r22_anousu";
         $matriz1[8] = "r22_mesusu";
         $matriz1[9] = "r22_instit";
   
         $matriz2[1] = $r110_regist;
         $matriz2[2] = $pontofa[$Ipontofa]["r21_rubric"];
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = round($r22_valor,2);
         $matriz2[5] = $r22_quant;
         $matriz2[6] = $r22_pd;
         $matriz2[7] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[8] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[9] = $DB_instit;

         db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );

      }
   }

 }

}

// fim da funcao gerfadi //


function gerffer($opcao_geral=null,$opcao_tipo=1){
  // globais de outras funcoes
 
  global $quais_diversos,$tot_prov, $tot_desc,$carregarubricas_geral;
  global $anousu, $mesusu, $DB_instit;
  
  global $campos_pessoal, $r110_regisi, $subpes,$chamada_geral,$chamada_geral_arquivo,$pessoal,$Ipessoal,$transacao,$cfpess;

  global $situacao_funcionario,$naoencontroupontosalario,$d08_carnes,$carregarubricas,$diversos,$Iinssirf,$inssirf,$subpes,$cadferia;

  global $dias_pagamento,$mes,$ano,$dias_do_mes,$vlr_sal_saude_ou_acidente,$valor_salario_maternidade ;
  global $rubricas,$prev_desc,$func_em_ferias,$ajusta,$sigla_ajuste,$inssirf_base_ferias;
  
  // GLOBAIS QUE PRECISAM MIGRAR PARA OUTRAS FUNCOES
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  
  global $quais_diversos;
  eval($quais_diversos);
 

  global $dtot_vpass,$dperc_pass,$dquant_pass,$quant_pass,$contador_registros;
  
  // esta variavel abaixo nao precisa mais
  global $recno_110,$r110_regist, $r110_lotac;
  
  $recno_110 = 0;

  global $opcao_filtro,$opcao_gml,$r110_regisf,$r110_lotaci, $r110_lotacf,$faixa_regis,$faixa_lotac;
  
  global $r110_regisf,$numcgm;
  //$r110_regisf = $r110_regisi;
  
$anomes = substr("#".$subpes,1,4).substr("#".$subpes,6,2);
$siglap = "r29_";
$siglag = "r31_";
if( $opcao_tipo == 2){

//   $condicaoaux ="select distinct(r01_regist),".$campos_pessoal.",r29_regist from pessoal,pontofe ".bb_condicaosubpes( "r01_" );
//   $condicaoaux .= " and r01_regist = r29_regist ";
//   $condicaoaux .= " and r29_anousu = ".db_sqlformat(substr("#".$subpes,1,4));
//   $condicaoaux .= " and r29_mesusu = ".db_sqlformat(substr("#".$subpes,6,2));
//   $condicaoaux .= " and r01_recis is null ";
//   $condicaoaux .= " order by r01_regist ";
   
//   db_selectmax( "pessoal", $condicaoaux );

      $condicaoaux  = " and rh05_recis is null ";
      $condicaoaux .= " order by rh02_regist ";
      db_selectmax("pessoal", "select distinct(rh02_regist),".$campos_pessoal.",r29_regist from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                               and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                       left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                       left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
                                         and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                       left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes = rhpessoalmov.rh02_seqpes 
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       left outer join pontofe on r29_regist = rhpessoalmov.rh02_regist
                                                  and r29_anousu= rhpessoalmov.rh02_anousu
                                                  and r29_mesusu= rhpessoalmov.rh02_mesusu
                                                  and r29_instit= rhpessoalmov.rh02_instit
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );
}else{
   if( $opcao_filtro != "0" ){
       
       $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"rh02_",$r110_regisi,$r110_regisf,$r110_lotaci,
                                     $r110_lotacf,$faixa_regis,$faixa_lotac);
  
//      db_selectmax( "pessoal", "select ".$campos_pessoal." from pessoal ".bb_condicaosubpes( "r01_" ).$condicaoaux );

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
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,
                                    $r110_lotacf,$faixa_regis,$faixa_lotac);
      
//      db_delete( $chamada_geral_arquivo, bb_condicaosubpes( $siglag ).$condicaoaux );
      global $$chamada_geral_arquivo;
      if( db_selectmax( $chamada_geral_arquivo, "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes( $siglag ).$condicaoaux )){
         $arquivo = $$chamada_geral_arquivo;
         for($Iarquivo=0;$Iarquivo< count($arquivo);$Iarquivo++){
            deleta_para_ajustes( $arquivo[$Iarquivo]["r31_rubric"], $arquivo[$Iarquivo]["r31_regist"], "F");

            db_delete( $chamada_geral_arquivo, bb_condicaosubpes( $siglag ).$condicaoaux );
         }
      }

   }
}

$contador_registros = 1;
  
for($Ipessoal=0;$Ipessoal<count($pessoal);$Ipessoal++){
  
   db_atutermometro($Ipessoal,count($pessoal),'calculo_folha',1);
//     echo "<BR> ".date("H:i:s")."Calculando registro $Ipessoal de ".count($pessoal).": ".$pessoal[$Ipessoal]["r01_regist"];
//     flush();
   
   $mpsal = false;    // pagamento de salarios no ponto de ferias;

   if( $chamada_geral == "p"  ){
      $condicaoaux = " and r52_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
      if( !db_selectmax( "pensao", "select r52_regist from pensao ".bb_condicaosubpes( "r52_" ).$condicaoaux )){
         continue;
      }else{
         $condicaoaux = " and ".$siglag."regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
         db_delete( $chamada_geral_arquivo, bb_condicaosubpes( $siglag ).$condicaoaux );
      }
   }
   
   $situacao_funcionario = situacao_funcionario( $pessoal[$Ipessoal]["r01_regist"] );

   $condicaoaux  = " and r29_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
   $condicaoaux .= " order by r29_regist,r29_rubric ";
   global $pontofe, $proc_ler_var_bxxx;
   $proc_ler_var_bxxx = db_selectmax( "pontofe", "select * from pontofe ".bb_condicaosubpes("r29_").$condicaoaux );
   
   
   if( !$proc_ler_var_bxxx){
      continue;
   }
   if( db_at(db_str($situacao_funcionario,1),"2-7") > 0){
      // Afastado sem Remuneracao
      // Licensa sem Vencimento, cessao sem onus
      continue;
   }
   carrega_fxxx(db_str($pessoal[$Ipessoal]["r01_regist"],6),true,"gerffer");
   
   le_rubricas_condicao();

   $condicaoaux = " and r33_codtab = ".db_sqlformat( $pessoal[$Ipessoal]["r01_tbprev"]+2 );
   global $inssirf_;
   $achou_tabela = db_selectmax( "inssirf_", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux );
   if( $situacao_funcionario == 6 ){ // Afastado Doenca + 15 Dias
      if( $achou_tabela){
         $rubrica_licenca_saude = $inssirf_[0]["r33_rubsau"];
      }else{
         $rubrica_licenca_saude = str_pad(" ",4);
      }
   }
   if( $situacao_funcionario == 3 ){ // Afastado Acidente de Trabalho + 15 Dias
      if( $achou_tabela){
         $rubrica_acidente = $inssirf_[0]["r33_rubaci"];
      }else{
         $rubrica_acidente = str_pad(" ",4);
      }
   }

   $inssirf_base_ferias = "B002";
   if( !db_empty( $inssirf_[0]["r33_basfer"] )){
      $inssirf_base_ferias = $inssirf_[0]["r33_basfer"];
   }

   // --- guarda valores de ferias p/ futuro reclaculo de ferias
   $vfgt = 0;
   $fgtp1 = str_pad(" ",07);
   $fgtp2 = str_pad(" ",07);
   $fgtv0 = 0;
   $fgtv1 = 0;
   $fgtv2 = 0;
   
   $condicaoaux  = " and r30_regist = ".db_sqlformat( $pontofe[0]["r29_regist"] ) ;
   $condicaoaux .= " order by r30_perai desc limit 1";
   if( db_selectmax( "cadferia", "select * from cadferia ".bb_condicaosubpes( "r30_" ).$condicaoaux )){
      if( $cadferia[0]["r30_proc1"] == $subpes){
         $mpsal = ('t' ==  $cadferia[0]["r30_psal1"]);
         $datai = $cadferia[0]["r30_per1i"];
         $dataf = $cadferia[0]["r30_per1f"];
      }else if( $cadferia[0]["r30_proc2"] == $subpes ){ 
         $mpsal = ('t' ==  $cadferia[0]["r30_psal2"] );
         $datai = $cadferia[0]["r30_per2i"];
         $dataf = $cadferia[0]["r30_per2f"];
      }
   }
   if( db_empty($cadferia[0]["r30_proc2"]) ){
       $r30_proc = "r30_proc1";
       $r30_peri = "r30_per1i";
       $r30_perf = "r30_per1f";
   }else{
       $r30_proc = "r30_proc2";
       $r30_peri = "r30_per2i";
       $r30_peri = "r30_per2f";
   }

   if($cadferia[0][$r30_proc] > $subpes) {
      continue;
   }   
   // ver se pagamento e de diferenca ou nao, se ja pago e qual o mes
   $gera_somente_diferenca = false;
   
   if( db_year($cadferia[0]["r30_per1i"]) == $anousu && 
      db_month($cadferia[0]["r30_per1i"]) == $mesusu && $cadferia[0]["r30_proc1"] != $subpes ) {
      $gera_somente_diferenca = true;
   }
   if( !$mpsal){

      $condicaoaux  = " select r14_regist,r14_rubric,rh27_rubric,rh27_pd ";
      $condicaoaux .= "   from gerfsal inner join rhrubricas on r14_rubric = rh27_rubric ";
      $condicaoaux .= "        and r14_instit = rh27_instit ".bb_condicaosubpes( "r14_" );
      $condicaoaux .= "        and r14_regist = ".db_sqlformat( $pontofe[0]["r29_regist"] );
      $condicaoaux .= "        and rh27_pd = 1";
      global $gerfsal_;
      if( db_selectmax( "gerfsal_", $condicaoaux ) ){
         for($Igerfsal=0;$Igerfsal<count($gerfsal_);$Igerfsal++){
      if($gerfsal_[$Igerfsal]["r14_regist"] <> $pontofe[0]["r29_regist"]){
        break;
      } 
            $rubrica = $gerfsal_[$Igerfsal]["r14_rubric"];
            $condicaoaux  = " and r14_regist = ".db_sqlformat( $pontofe[0]["r29_regist"] );
            $condicaoaux .= " and r14_rubric = ".db_sqlformat( $rubrica );

            db_delete( "gerfsal", bb_condicaosubpes( "r14_" ).$condicaoaux );
         }
      }
   }

   $base_prev  = 0;
   $prev_desc  = 0;
   $base_irf   = 0;
   $r14_pd     = 0;
   $tot_prov   = 0;
   $tot_desc   = 0;
   if ($db_debug == true) { 
     echo "[gerffer] 9 - total de desconto: $tot_desc<br>";
   }
   $r14_valor  = 0;
   $r14_quant  = 0;
   $salfamilia = 0;
   $tot_ferias = 0;
   
   if( !db_empty($pessoal[$Ipessoal]["r01_fgts"]) ) {
      $condicaoaux  = " where r40_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " and r40_proc = ".db_sqlformat( $subpes );
      db_delete( "fgtsfer" , $condicaoaux );
   }
   
   $recno_111 = 0;
   $recno_110 = 0;
   $r110_regist = $pontofe[0]["r29_regist"];
   $r110_lotac  = $pontofe[0]["r29_lotac"];
   for($Ipontofe=0;$Ipontofe<count($pontofe);$Ipontofe++){
      $r14_quant   = $pontofe[$Ipontofe]["r29_quant"];
      $r14_valor   = $pontofe[$Ipontofe]["r29_valor"];
      if ($db_debug == true) {
        echo "[gerffer] 3 - r14_valor = $r14_valor  <br>";
      }
      $rtpgto      = $pontofe[$Ipontofe]["r29_tpp"];

//echo "<BR> pontofe : rubric --> ".$pontofe[$Ipontofe]["r29_rubric"]." r29_valor --> $r14_valor r29_quant --> $r14_quant rtpgto --> $rtpgto"; 
      
      $condicaoaux = " where rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat( $pontofe[$Ipontofe]["r29_rubric"]);
      
      global $rub_;
      db_selectmax( "rub_", "select * from rhrubricas ".$condicaoaux );

      $r14_pd    = $rub_[0]["rh27_pd"];

      $condicaoaux = " and r10_regist = ".db_sqlformat( $r110_regist );
      $condicaoaux .= " order by r10_regist,r10_rubric ";
      global $pontofs;
      db_selectmax( "pontofs", "select * from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux );

      if( db_empty( $r14_valor )){
//echo "<BR> pontofe : rubric --> ".$pontofe[$Ipontofe]["r29_rubric"]." r29_valor --> $r14_valor r29_quant --> $r14_quant"; 
         $r07_form = le_adi_fer(trim($pontofe[$Ipontofe]["r29_rubric"]),"gerffer","r31",0);
   
      }else{
         $r07_form = "valor_zerado";
      }
      

      if( $r07_form == "valor_zerado"){
         $r14_valor = $pontofe[$Ipontofe]["r29_valor"];
         if ($db_debug == true) { echo "[gerffer] 4 - r14_valor = $r14_valor  <br>"; }
      }else if( $r07_form == "()"){ 
         $r14_valor = 0;
         if ($db_debug == true) { echo "[gerffer] 5 - r14_valor = $r14_valor  <br>"; }
      }else{
  
         $cod_erro  = 0;
         $elem_erro =  " ";
         //$r07_form  = operacao(r07_form);
         $r01_form  = '$r07_form = '.$r07_form.";";
      //echo "<BR> 1 r29_rubric --> ".$pontofe[$Ipontofe]["r29_rubric"]." r01_form --> $r01_form";
         ob_start();
         eval($r01_form);
         db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontofe[$Ipontofe]["r29_rubric"]); 
   
      //echo "<BR> 2 r07_form --> $r07_form F008 --> $F008 --> F007 --> $F007";
         $r14_valor = round($pontofe[$Ipontofe]["r29_quant"] * $r07_form,2);
         if ($db_debug == true) { echo "[gerffer] 6 - r14_valor = $r14_valor  <br>"; }
         $r14_quant = $pontofe[$Ipontofe]["r29_quant"];
   
      }

      if (!db_empty($r14_valor)) {
        
         if( $r14_pd == 2 ){
            $tot_desc += round($r14_valor,2);
         }else{
            $tot_prov += round($r14_valor,2);
            $val_rubr  = $pontofe[$Ipontofe]["r29_rubric"];
            if( ($val_rubr > "1999" && $val_rubr < "4000") || $val_rubr == "R930" || $val_rubr == "R932"){
               $tot_ferias += round($r14_valor,2);
            }
       }
   
         $matriz1 = array();
         $matriz2 = array();
   
         $matriz1[1] = "r31_regist";
         $matriz1[2] = "r31_rubric";
         $matriz1[3] = "r31_lotac";
         $matriz1[4] = "r31_valor";
         $matriz1[5] = "r31_quant";
         $matriz1[6] = "r31_pd";
         $matriz1[7] = "r31_semest";
         $matriz1[8] = "r31_tpp";
         $matriz1[9] = "r31_anousu";
         $matriz1[10] = "r31_mesusu";
         $matriz1[11] = "r31_instit";

         $matriz2[1] = $r110_regist;
         $matriz2[2] = $pontofe[$Ipontofe]["r29_rubric"];
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = round($r14_valor,2);
         $matriz2[5] = $r14_quant;
         $matriz2[6] = $r14_pd;
         $matriz2[7] = 0;
         $matriz2[8] = $rtpgto;
         $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[11] = $DB_instit;

         db_insert( $chamada_geral_arquivo,$matriz1,$matriz2 );

      }
   }
   $r14_valor = 0;
   if ($db_debug == true) { echo "[gerffer] 7 - r14_valor = $r14_valor  <br>"; }
   
   carrega_r9xx("pontofe","r29","r31",$recno_111,$opcao_tipo);


   $matriz1 = array();
   $matriz2 = array();
   $matriz1[1] = "r31_regist";
   $matriz1[2] = "r31_rubric";
   $matriz1[3] = "r31_lotac";
   $matriz1[4] = "r31_valor";
   $matriz1[5] = "r31_quant";
   $matriz1[6] = "r31_pd";
   $matriz1[7] = "r31_semest";
   $matriz1[8] = "r31_tpp";
   $matriz1[9] = "r31_anousu";
   $matriz1[10] = "r31_mesusu";
   $matriz1[11] = "r31_instit";

   if( !db_empty($tot_prov) || !db_empty($tot_desc)){
      if( $tot_prov > $tot_desc){
         $r01_rubric = "R926";
         $tot_liq = $tot_prov - $tot_desc;
         $arredn = arredonda_100($tot_liq, $cfpess[0]["r11_arredn"]);
         $tot_liq += $arredn;
      }else{
         $arredn = $tot_desc - $tot_prov;
         $r01_rubric = "R928";
//echo "<BR> rubrica 27.2 -->R928  valor --> $arredn"; // reis
      }
      if( !db_empty($arredn)){

         $matriz2[1] = $r110_regist;
         $matriz2[2] = $r01_rubric;
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = $arredn;
         $matriz2[5] = 0;
         $matriz2[6] = 1;
         $matriz2[7] = 1;
         $matriz2[8] = " " ;
         $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[11] = $DB_instit;


         db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );

      }
   }

   if( strtolower(trim($d08_carnes)) == "riogrande"){
      $bbru = 0;
      $birf = 0;
      $bfgt = 0;
      $bpre = 0;
      $valor_bruto = 0;
      $condicaoaux  = " and r31_regist = ".db_sqlformat( $r110_regist );
      global $transacao;
      db_selectmax( "transacao", "select * from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux ) ;
      for($Itran=0;$Itran < count($transacao);$Itran++){
         $mrub = $transacao[$Itran]["r31_rubric"];
         if( $mrub >= "2000" && $mrub < "4000" ){
            if( $transacao[$Itran]["r31_pd"] == 1){
               $valor_bruto += $transacao[$Itran]["r31_valor"];
            }
         }
         if( ($mrub >= "2000" && $mrub < "4000") || ($mrub >= "R930" && $mrub <= "R932")){
            if( $transacao[$Itran]["r31_pd"] == 1){
               $bbru += $transacao[$Itran]["r31_valor"];
            }
         }else{
           if( db_at($mrub,"R903-R906-R909-R909-R915") > 0){
              if( $transacao[$Itran]["r31_pd"] == 1){
                 $bbru += $transacao[$Itran]["r31_valor"];
              }
           }else if( $mrub == "R987"){ 
              if( $transacao[$Itran]["r31_pd"] == 1){
                 $bpre -= $transacao[$Itran]["r31_valor"];
              }else{
                 $bpre += $transacao[$Itran]["r31_valor"];
              }
           }else if( $mrub == "R983"){ 
              if( $transacao[$Itran]["r31_pd"] == 1){
                 $birf -= $transacao[$Itran]["r31_valor"];
              }else{
                 $birf += $transacao[$Itran]["r31_valor"];
              }
           }
         }
      }

      if( !db_empty($pessoal[$Ipessoal]["r01_fgts"])){
         if($db_debug == true){ echo "[gerffer] 1 - chamando a função calc_rubrica() <br>"; }
         $r07_valor = calc_rubrica("R996","pontofe","r29","r31",$recno_111,true);
         $r14_valor = $r07_valor;
         if ($db_debug == true) { echo "[gerffer] 8 - r14_valor = $r14_valor  <br>"; }
         $r14_quant = 0;
   
         if( $r14_valor > 0){
     
            verfgts($anomes,$datai,$dataf,&$fgtp1,&$fgtp2,&$fgtv0,&$fgtv1,&$fgtv2);
      
            if( $fgtv0 > 0 ){
               $matriz2[1] = $r110_regist;
               $matriz2[2] = "R991";
               $matriz2[3] = $r110_lotac;
               $matriz2[5] = 0;
               $matriz2[6] = 2;
               $matriz2[7] = 1;
               $matriz2[8] = " " ;
               $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
               $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
               $matriz2[11] = $DB_instit;

               $condicaoaux  = " and r31_regist = ".db_sqlformat( $r110_regist );
               $condicaoaux .= " and r31_rubric = 'R991'";
         
               if( db_selectmax( "transacao", "select * from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux ) ){

                  $matriz2[4] = round( $transacao[$Itran]["r31_valor"]+(($r14_valor/($fgtv0+$fgtv1+$fgtv2))*$fgtv0),2);

                  db_update( $chamada_geral_arquivo,$matriz1, $matriz2, bb_condicaosubpes( $siglag ).$condicaoaux );
               }else{


                  $matriz2[4] = round((($r14_valor/($fgtv0+$fgtv1+$fgtv2))*$fgtv0),2);

                  db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );
               }
            }
      $resultado =( ($fgtv0 + $fgtv1 + $fgtv2) * $fgtv1 ) ;
            if( $fgtv1 > 0 ){
               if( db_empty( $resultado) ) {
                  $fgtv1 = 1;
               }
               $matrizaux1 = array();
         $matrizaux2 = array();

               $matrizaux1[1] = "r40_regist";
               $matrizaux1[2] = "r40_proc";
               $matrizaux1[3] = "r40_recol";
               $matrizaux1[4] = "r40_valor";

               $matrizaux2[1] = $r110_regist;
               $matrizaux2[2] = $subpes;
               $matrizaux2[3] = $fgtp1;
               $matrizaux2[4] = round( ($r14_valor/($fgtv0+$fgtv1+$fgtv2)) * $fgtv1, 2 );

               db_insert( "fgtsfer", $matrizaux1, $matrizaux2 );

            }
            if( $fgtv2 > 0 ){
               if( db_empty($resultado) ){
                  $fgtv1 = 1;
               }

               $matrizaux1 = array();
         $matrizaux2 = array();

               $matrizaux1[1] = "r40_regist";
               $matrizaux1[2] = "r40_proc";
               $matrizaux1[3] = "r40_recol";
               $matrizaux1[4] = "r40_valor";

               $matrizaux2[1] = $r110_regist;
               $matrizaux2[2] = $subpes;
               $matrizaux2[3] = $fgtp2;
               $matrizaux2[4] = bb_mens( ($r14_valor/($fgtv0+$fgtv1+$fgtv2)) * $fgtv2, 2 );

               db_insert( "fgtsfer", $matrizaux1, $matrizaux2 );
            }
         }
      }

      if( !db_empty($pessoal[$Ipessoal]["r01_fgts"])){
         $r14_valor = 0;
         if ($db_debug == true) { echo "[gerffer] 9 - r14_valor = $r14_valor  <br>"; }
         $condicaoaux  = " where r40_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
         $condicaoaux .= "  and  r40_recol  = ".db_sqlformat( $subpes );
   
   global $fgtsfer;
         db_selectmax( "fgtsfer", "select * from fgtsfer ".$condicaoaux );
         for($Ifgtsfer=0 ; $Ifgtsfer < count($fgtsfer) ; $Ifgtsfer++){
     if( $fgtsfer[$Ifgtsfer]["r40_regist"] <> $pessoal[$Ipessoal]["r01_regist"]){
       break;
     }
           $fgtsfer[$Ifgtsfer]["r40_recol"] = $subpes;
           $r14_valor += $fgtsfer[$Ifgtsfer]["r40_valor"];
           if ($db_debug == true) { echo "[gerffer] 10 - r14_valor = $r14_valor  <br>"; }
         }
   
         if( $r14_valor > 0){
            $matriz2[1] = $r110_regist;
            $matriz2[2] = "R996";
            $matriz2[3] = $r110_lotac;
            $matriz2[4] = round( $r14_valor, 2);
            $matriz2[5] = 0;
            $matriz2[6] = 2;
            $matriz2[7] = 1;
            $matriz2[8] = " " ;
            $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
            $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
            $matriz2[11] = $DB_instit;

            $condicaoaux  = " and r31_regist = ".db_sqlformat( $r110_regist );
            $condicaoaux .= " and r31_rubric = ".db_sqlformat( "R996" );
            if( db_selectmax( "transacao", "select * from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux )){
               db_update( $chamada_geral_arquivo,$matriz1, $matriz2, bb_condicaosubpes( $siglag ).$condicaoaux );
            }else{
               db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );
            }
         }
      }
      
      // salva valores no cadastro de ferias
      
      if( $gera_somente_diferenca == false){
         $condicaoaux =  " and r30_regist = ".db_sqlformat( $r110_regist ) ;
         $condicaoaux .= " order by r30_perai desc limit 1 ";
   global $cadferia;
         if( db_selectmax( "cadferia", "select * from cadferia ".bb_condicaosubpes( "r30_" ).$condicaoaux )){

            $perai_cadferia = $cadferia[0]["r30_perai"];
            $condicaoaux  = " and r30_regist = ".db_sqlformat( $r110_regist ) ;
            $condicaoaux .= " and r30_perai = ".db_sqlformat( $perai_cadferia );

            $matrizaux1 = array();
      $matrizaux2 = array();

            if( db_empty($cadferia[0]["r30_proc1d"])){
               $matrizaux1[1] = "r30_vliq1";
               $matrizaux1[2] = "r30_virf1";
               $matrizaux1[3] = "r30_vpre1";

               $matrizaux2[1] = $bbru;
               $matrizaux2[2] = $birf;
               $matrizaux2[3] = $bpre;


               db_update( "cadferia", $matrizaux1, $matrizaux2, bb_condicaosubpes( "r30_").$condicaoaux );

            }else if( db_empty($cadferia[0]["r30_proc2"])){ 
               $matrizaux1[1] = "r30_vliq1d";
               $matrizaux1[2] = "r30_virf1d";
               $matrizaux1[3] = "r30_vpre1d";

               $matrizaux2[1] = $bbru;
               $matrizaux2[2] = $birf;
               $matrizaux2[3] = $bpre;

               db_update( "cadferia", $matrizaux1, $matrizaux2, bb_condicaosubpes( "r30_").$condicaoaux );
            }else if( db_empty($cadferia[0]["r30_proc2d"])){ 
               $matrizaux1[1] = "r30_vliq2";
               $matrizaux1[2] = "r30_virf2";
               $matrizaux1[3] = "r30_vpre2";

               $matrizaux2[1] = $bbru;
               $matrizaux2[2] = $birf;
               $matrizaux2[3] = $bpre;

               db_update( "cadferia", $matrizaux1, $matrizaux2, bb_condicaosubpes( "r30_").$condicaoaux );
            }else{
               $matrizaux1[1] = "r30_vliq2d";
               $matrizaux1[2] = "r30_virf2d";
               $matrizaux1[3] = "r30_vpre2d";
               $matrizaux2[1] = $bbru;
               $matrizaux2[2] = $birf;
               $matrizaux2[3] = $bpre;

               db_update( "cadferia", $matrizaux1, $matrizaux2, bb_condicaosubpes( "r30_").$condicaoaux );
            }

         }
      }else{
         // somente rio grande e se for diferenca;
         // ver no mes do pagamento das ferias qual ;
   
         $condicaoaux  = " and r30_regist = ".db_sqlformat( $r110_regist ) ;
         $condicaoaux .= " order by r30_perai desc limit 1 ";
         db_selectmax("cadferia", "select * from cadferia ".bb_condicaosubpes( "r30_" ).$condicaoaux );
        
         if( db_empty($cadferia[0]["r30_proc2"]) ){
            $r30_proc = "r30_proc1";
            $r30_peri = "r30_per1i";
            $r30_perf = "r30_per1f";
         }else{
            $r30_proc = "r30_proc2";
            $r30_peri = "r30_per2i";
            $r30_peri = "r30_per2f";
         }
   
         if( $cadferia[0]["r30_ndias"] < 30 && $pessoal[$Ipessoal]["r01_regime"] == 2){
            $um_terco = 3;
         }else{
            $um_terco = 1;
         }
         $tem_ferias = false;

         $condicaoaux  = " and r31_regist = ".db_sqlformat( $r110_regist );
         $condicaoaux .= " and r31_rubric != '0148' ";
         $matriz1aux = array();
   $matriz1aux = array();
   
         $matriz1aux[1] = "r31_semest";
         $matriz2aux[1] = 0;

         db_update( $chamada_geral_arquivo, $matriz1aux,$matriz2aux, bb_condicaosubpes( "r31_").$condicaoaux  );


         db_delete( $chamada_geral_arquivo, bb_condicaosubpes( "r31_").$condicaoaux  );

         $condicaoaux  = " and r31_regist = ".db_sqlformat( $r110_regist );
         $condicaoaux .= " and r31_rubric = '0148' ";
   global $transacao;
         if( db_selectmax( "transacao", "select * from gerffer ".bb_condicaosubpes( "r31_" ).$condicaoaux )){
            $tem_ferias = true ;

            $matriz2[1] = $r110_regist;
            $matriz2[2] = "0152";
            $matriz2[3] = $r110_lotac;
            $matriz2[4] = round( ( ( $valor_bruto / 30 ) * $cadferia[0]["r30_ndias"] ) / $um_terco,2 );
            $matriz2[5] = 0;
            $matriz2[6] = 1;
            $matriz2[7] = 1;
            $matriz2[8] = " " ;
            $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
            $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
            $matriz2[11] = $DB_instit;

            db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );

         }
      }

   }
   // gravado para o ajuste de previdencia e ir
   // F019 - Numero de dias a pagar no mes
   global $pes_prev;
   $pes_prev = array();
   grava_ajuste_previdencia();
  // grava_ajuste_irrf();
  if(count($pes_prev) == 0){
     grava_ajuste_irrf($pessoal[$Ipessoal]["r01_numcgm"],$r110_regist,strtolower($pessoal[$Ipessoal]["r01_tpvinc"]));
  }   

 }

}

/// fim da funcao gerffer ///


function gerfprovfer($opcao_geral=null,$opcao_tipo=1){
  //echo "<BR> n gerfprovfer($opcao_geral=null,$opcao_tipo=1){";
  // globais de outras funcoes
 
  global $quais_diversos,$tot_prov, $tot_desc,$carregarubricas_geral;
  global $anousu, $mesusu, $DB_instit, $db_debug;
  
  global $campos_pessoal, $r110_regisi, $subpes,$chamada_geral,$chamada_geral_arquivo,$pessoal,$Ipessoal,$transacao,$cfpess;

  global $situacao_funcionario,$naoencontroupontosalario,$d08_carnes,$carregarubricas,$diversos,$Iinssirf,$inssirf,$subpes,$cadferia;

  global $dias_pagamento,$mes,$ano,$dias_do_mes,$vlr_sal_saude_ou_acidente,$valor_salario_maternidade ;
  global $rubricas,$prev_desc,$func_em_ferias,$ajusta,$sigla_ajuste,$inssirf_base_ferias;
  
  // GLOBAIS QUE PRECISAM MIGRAR PARA OUTRAS FUNCOES
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  

  global $quais_diversos;
  eval($quais_diversos);
 

  global $dtot_vpass,$dperc_pass,$dquant_pass,$quant_pass,$contador_registros;
  
  // esta variavel abaixo nao precisa mais
  global $recno_110,$r110_regist, $r110_lotac;
  
  $recno_110 = 0;

  global $r110_regisf,$numcgm;

  global $opcao_filtro,$opcao_gml,$r110_regisf,$r110_lotaci, $r110_lotacf,$faixa_regis,$faixa_lotac;
  
  //$r110_regisf = $r110_regisi;
$siglap = "r91_";
$siglag = "r93_";

$condicao = $r110_lotaci . db_str($r110_regisi,6);
$situacao_funcionario = 1;  // Normal
  
      $condicaoaux = " and rh02_regist in(select distinct r91_regist from pontoprovfe ".bb_condicaosubpes( "r91_").")"; 
      db_selectmax("pessoal", "select distinct(rh02_regist),".$campos_pessoal." from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                               and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                       left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                       left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
                                         and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                       left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes = rhpessoalmov.rh02_seqpes 
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

$contador_registros = 1;

$matriz1 = array();
$matriz1 = array();

$matriz1[1] = "r93_regist";
$matriz1[2] = "r93_rubric";
$matriz1[3] = "r93_lotac";
$matriz1[4] = "r93_valor";
$matriz1[5] = "r93_quant";
$matriz1[6] = "r93_pd";
$matriz1[7] = "r93_semest";
$matriz1[8] = "r93_tpp";
$matriz1[9] = "r93_anousu";
$matriz1[10] = "r93_mesusu";
$matriz1[11] = "r93_instit";


for($Ipessoal=0;$Ipessoal< count($pessoal);$Ipessoal++){
   
   db_atutermometro($Ipessoal,count($pessoal),'calculo_folha',1);
   //echo "<BR> ".date("H:i:s")."Calculando registro $Ipessoal de ".count($pessoal).": ".$pessoal[$Ipessoal]["r01_regist"]. " numcgm --> ".$pessoal[$Ipessoal]["r01_regist"];
   //flush();

   $condicaoaux = " and r33_codtab = ".db_sqlformat( $pessoal[$Ipessoal]["r01_tbprev"]+2 );
   global $inssirf_;
   db_selectmax( "inssirf_", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux );
   
   $inssirf_base_ferias = "B002";
   
   if( !db_empty( $inssirf_[0]["r33_basfer"])){
      $inssirf_base_ferias = $inssirf_[0]["r33_basfer"];
   }
   
   $condicaoaux = " and r91_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"]);
   $condicaoaux .= " order by r91_regist,r91_rubric ";
   global $pontoprovfe;
   if( !db_selectmax( "pontoprovfe", "select * from pontoprovfe ".bb_condicaosubpes( "r91_" ).$condicaoaux )){
    if ($db_debug == true) {
      echo "[gerfprovfer] não encontrou registros em pontoprovfe quando ".bb_condicaosubpes( "r91_" ).$condicaoaux."...<br> continuando... <br>";
    }
     continue;
   }
   
   if ($db_debug) {
    echo "[gerfprovfer] dados encontrados na tabela pontoprovfe ...<br>";
    echo "<pre>";
    print_r($pontoprovfe);
    echo "</pre>";
    echo "[gerfprovfer] <br>";
   } 
   
   $base_prev  = 0;
   $prev_desc  = 0;
   $base_irf   = 0;
   $r93_pd     = 0;
   $tot_prov   = 0;
   $tot_desc   = 0;
   if ($db_debug == true) { echo "[gerfprovfer] 11 - total de desconto: $tot_desc<br>"; }
   $r93_valor  = 0; 
   $r93_quant  = 0;
   $salfamilia = 0; 
   
   if ($db_debug == true) {
    echo "[gerfprovfer] <br>";
    echo "[gerfprovfer] chamando a função carrega_fxxx(".db_str($pessoal[$Ipessoal]["r01_regist"],6).",true)<br>";
    echo "[gerfprovfer] <br>";
   }
   carrega_fxxx(db_str($pessoal[$Ipessoal]["r01_regist"],6),true);
   
   if ($db_debug == true) {
    echo "[gerfprovfer] <br>";
    echo "[gerfprovfer] fim do processamento da função carrega_fxxx(".db_str($pessoal[$Ipessoal]["r01_regist"],6).",true)<br>";
    echo "[gerfprovfer] <br>";
   }
   
   /**
    * Passa por parâmetro o nome da tabela 'gerfprovfer' para que seja salvo na tabela gerfprovfer
    * os dados referentes ao provento de férias.
    */
   if ($db_debug == true) {
    echo "[gerfprovfer] <br>";
    echo "[gerfprovfer] chamando a função le_rubricas_condicao('gerfprovfer')<br>";
    echo "[gerfprovfer] <br>";
   }   
   le_rubricas_condicao('gerfprovfer');
   
   if ($db_debug == true) {
    echo "[gerfprovfer] <br>";
    echo "[gerfprovfer] fim do processamento da função le_rubricas_condicao('gerfprovfer')<br>";
    echo "[gerfprovfer] <br>";
    echo "[gerfprovfer] Iniciando variáveis com o numero de dias a pagar<br>";
    echo "[gerfprovfer] F019 = 30 -- Numero de dias a pagar no mes<br>";
    echo "[gerfprovfer] F020 = 0  -- Numero de dias abono p/ pagar no mes<br>";
    echo "[gerfprovfer] F023 = 0  -- Numero de dias p/ calc do FGTS no mes<br>";
    echo "[gerfprovfer] F021 = 0  -- <br>";
    
   }   
   $F019=30; // F019 - Numero de dias a pagar no mes
   $F020=0;  // F020 - Numero de dias abono p/ pagar no mes
   $F023=0;  // F021 - Numero de dias p/ calc do FGTS no mes
   $F021=0;
   
   if( $pessoal[$Ipessoal]["r01_mremun"] > 0){
      $F007 = $pessoal[$Ipessoal]["r01_mremun"];
      $F010 = $pessoal[$Ipessoal]["r01_mremun"];
      $F001 = $pessoal[$Ipessoal]["r01_mremun"] / $F008;
   }
   
   $r110_regist = $pontoprovfe[0]["r91_regist"];
   $r110_lotac  = $pontoprovfe[0]["r91_lotac"];
   
   $mpsal = false;    // pagamento de salarios no ponto de ferias;

   $condicaoaux  = " and r30_regist = ".db_sqlformat( $r110_regist ) ;
   $condicaoaux .= " order by r30_perai desc limit 1";
   if( db_selectmax( "cadferia", "select * from cadferia ".bb_condicaosubpes( "r30_" ).$condicaoaux )){
       $cadferia[0]["r30_paga13"] = "f";
       $cadferia[0]["r30_proc1"] = $subpes; 
       $cadferia[0]["r30_per1i"] = $anousu."-".$mesusu."-01";
       $cadferia[0]["r30_per1f"] = $anousu."-".$mesusu."-".ndias($anousu,$mesusu);
       $datai = $cadferia[0]["r30_per1i"];
       $dataf = $cadferia[0]["r30_per1f"];
   }

   $r30_proc = "r30_proc1";
   $r30_peri = "r30_per1i";
   $r30_perf = "r30_per1f";

   for($Ipontoprovfe=0;$Ipontoprovfe< count($pontoprovfe) ;$Ipontoprovfe++){
     
      $r93_quant   = $pontoprovfe[$Ipontoprovfe]["r91_quant"];
      $r93_form    = $pontoprovfe[$Ipontoprovfe]["r91_rubric"];
      $r93_valor   = $pontoprovfe[$Ipontoprovfe]["r91_valor"];
      
      
      $condicaoaux = " where rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat( $pontoprovfe[$Ipontoprovfe]["r91_rubric"] );
      global $rub_;
      db_selectmax( "rub_", "select * from rhrubricas ".$condicaoaux );
      $r93_pd = $rub_[0]["rh27_pd"];

      if($db_debug == true){ echo "[gerfprovfer] 2 - chamando a função calc_rubrica()<br>"; }
      $r07_form = calc_rubrica($pontoprovfe[$Ipontoprovfe]["r91_rubric"],"pontoprovfe","r91","r93",$recno_110,false);
      
      if( db_empty($r07_form) || (!db_empty($r07_form) && !db_empty($pontoprovfe[$Ipontoprovfe]["r91_valor"]))){
         $r93_valor = $pontoprovfe[$Ipontoprovfe]["r91_valor"];
      }else{
         $cod_erro  = 0;
         $r01_form = '$r07_form  = '.$r07_form.";";
         ob_start();
         eval($r01_form);
         db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontoprovfe[$Ipontoprovfe]["r91_rubric"]); 
         $r93_valor = round( $pontoprovfe[$Ipontoprovfe]["r91_quant"] * $r07_form, 2 );
         $r93_quant = $pontoprovfe[$Ipontoprovfe]["r91_quant"];
      }


      // proporcionaliza valores dos inativos conforme cadastro e rubricas
      // r01_propi --> Perc.Inativo       
      if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) != 'a' && $pessoal[$Ipessoal]["r01_propi"] > 0 
                                    && $pessoal[$Ipessoal]["r01_propi"] < 100 
                                    && ('t' == $rub_[0]["rh27_ propi"]) ){
         $r93_valor = round( $r93_valor * ( $pessoal[$Ipessoal]["r01_propi"] / 100 ),2 ) ;
      }
      if ($db_debug) {
        
        if ($pontoprovfe[$Ipontoprovfe]["r91_rubric"] == 'R931') {
          echo "[gerfprovfer] Rubrica R931' - Valor = {$r93_valor} Formula == {$r01_form}<br>";
        }
      }
      if( $r93_valor > 0){
         $quant_formq = " ";
         if( !db_empty( $rub_[0]["rh27_formq"] )){

             if($db_debug == true){ echo "[gerfprovfer] 3 - chamando a função calc_rubrica() <br>"; }
             $quant_formq = calc_rubrica("formq","pontoprovfe","r91","r93",$recno_110,false,$rub_[0]["rh27_formq"],$r93_valor);
             $cod_erro_  = 0;
             $elem_erro_ =  " ";
             $r01_form = '$quant_formq  = '.$quant_formq.";";
             ob_start();
             eval($r01_form);
             db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$quant_formq,$rub_[0]["rh27_rubric"]); 
             $r93_quant = $quant_formq;

         }
 
         $matriz1 = array();
         $matriz1 = array();
   
         $matriz1[1] = "r93_regist";
         $matriz1[2] = "r93_rubric";
         $matriz1[3] = "r93_lotac";
         $matriz1[4] = "r93_valor";
         $matriz1[5] = "r93_quant";
         $matriz1[6] = "r93_pd";
         $matriz1[7] = "r93_semest";
         $matriz1[8] = "r93_tpp";
         $matriz1[9] = "r93_anousu";
         $matriz1[10] = "r93_mesusu";
         $matriz1[11] = "r93_instit";
      
         $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
         $matriz2[2] = $pontoprovfe[$Ipontoprovfe]["r91_rubric"];
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = round($r93_valor,2);
         $matriz2[5] = $r93_quant;
         $matriz2[6] = $r93_pd;
         $matriz2[7] = 0;
         $matriz2[8] = $pontoprovfe[$Ipontoprovfe]["r91_tpp"];
         $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[11] = $DB_instit;

         db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );

      }
   }
   
   $r14_valor = 0;
   if ($db_debug == true) { echo "[gerfprovfer] 11 - r14_valor = $r14_valor  <br>"; }
   carrega_r9xx("pontoprovfe","r91","r93",$recno_110,$opcao_tipo);
//echo "<BR> 22220 passou aqui !!!";
   $matriz1 = array();
   $matriz2 = array();
   
   $matriz1[1] = "r93_regist";
   $matriz1[2] = "r93_rubric";
   $matriz1[3] = "r93_lotac";
   $matriz1[4] = "r93_valor";
   $matriz1[5] = "r93_quant";
   $matriz1[6] = "r93_pd";
   $matriz1[7] = "r93_semest";
   $matriz1[8] = "r93_tpp";
   $matriz1[9] = "r93_anousu";
   $matriz1[10] = "r93_mesusu";
   $matriz1[11] = "r93_instit";


   // desconto de insuficiencia de saldo mes anterior ( r929 )
   
   if( $pessoal[$Ipessoal]["r01_arredn"] > 0){
      $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
      $matriz2[2] = $pessoal[$Ipessoal]["r01_rubric"];
      $matriz2[3] = $r110_lotac;
      $matriz2[4] = $pessoal[$Ipessoal]["r01_arredn"];
      $matriz2[5] = 0;
      $matriz2[6] = 2;
      $matriz2[7] = 0;
      $matriz2[8] = " ";
      $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
      $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
      $matriz2[11] = $DB_instit;

//echo "<BR> 22223 passou aqui !!!";
      db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );
//echo "<BR> 22224 passou aqui !!!";
   }
 }
//echo "<BR> 22231 passou aqui !!!";

}

function gerfres($opcao_geral=null,$opcao_tipo=1){
  // globais de outras funcoes
 
  global $quais_diversos,$tot_prov, $tot_desc,$carregarubricas_geral;
  global $anousu, $mesusu, $DB_instit;
  
  global $campos_pessoal, $r110_regisi, $subpes,$chamada_geral,$chamada_geral_arquivo,$pessoal,$Ipessoal,$transacao,$cfpess;

  global $situacao_funcionario,$naoencontroupontosalario,$d08_carnes,$carregarubricas,$diversos,$Iinssirf,$inssirf,$subpes,$cadferia;

  global $dias_pagamento,$mes,$ano,$dias_do_mes,$vlr_sal_saude_ou_acidente,$valor_salario_maternidade ;
  global $rubricas,$prev_desc,$func_em_ferias,$ajusta,$sigla_ajuste,$inssirf_base_ferias;
  
  // GLOBAIS QUE PRECISAM MIGRAR PARA OUTRAS FUNCOES
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  

  global $quais_diversos;
  eval($quais_diversos);
 

  global $dtot_vpass,$dperc_pass,$dquant_pass,$quant_pass,$contador_registros;
  
  // esta variavel abaixo nao precisa mais
  global $recno_110,$r110_regist, $r110_lotac;
  
  $recno_110 = 0;

  global $r110_regisf,$numcgm;

  global $opcao_filtro,$opcao_gml,$r110_regisf,$r110_lotaci, $r110_lotacf,$faixa_regis,$faixa_lotac;
  
  //$r110_regisf = $r110_regisi;
$siglap = "r19_";
$siglag = "r20_";

$condicao = $r110_lotaci . db_str($r110_regisi,6);
$situacao_funcionario = 1;  // Normal
if( $opcao_tipo == 2){
//   $condicaoaux = " and r01_regist in(select distinct r19_regist from pontofr ".bb_condicaosubpes( "r19_").")"; 
//   db_selectmax( "pessoal", "select ".$campos_pessoal." from pessoal ".bb_condicaosubpes( "r01_" ) .$condicaoaux );
  
      $condicaoaux = " and rh02_regist in(select distinct r19_regist from pontofr ".bb_condicaosubpes( "r19_").")"; 
      db_selectmax("pessoal", "select distinct(rh02_regist),".$campos_pessoal." from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                               and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                       left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                       left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
                                         and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                       left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes = rhpessoalmov.rh02_seqpes 
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );
}else{

   if( $opcao_filtro <> "0" ){
       
      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"rh02_",$r110_regisi,$r110_regisf,$r110_lotaci,
                                    $r110_lotacf,$faixa_regis,$faixa_lotac);
      global $pessoal;
//      db_selectmax( "pessoal", "select ".$campos_pessoal." from pessoal ".bb_condicaosubpes( "r01_" ).$condicaoaux );

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
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,
                                    $r110_lotacf,$faixa_regis,$faixa_lotac);
      global $$chamada_geral_arquivo;
      
      if( db_selectmax( $chamada_geral_arquivo, "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes( $siglag ).$condicaoaux )){
         $arquivo = $$chamada_geral_arquivo;
   
         for($Iarquivo=0;$Iarquivo< count($arquivo);$Iarquivo++){

               deleta_para_ajustes($arquivo[$Iarquivo]["r20_rubric"] ,$arquivo[$Iarquivo]["r20_regist"], "R");

               $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,
                                             $r110_lotacf,$faixa_regis,$faixa_lotac);
               db_delete( $chamada_geral_arquivo, bb_condicaosubpes( $siglag ).$condicaoaux );

         }
         $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"r14_",$r110_regisi,$r110_regisf,$r110_lotaci,
                                       $r110_lotacf,$faixa_regis,$faixa_lotac);
         global $gerfsal_;
         if( db_selectmax( "gerfsal_", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
            for($Igerfsal=0;$Igerfsal< count($gerfsal_);$Igerfsal++){
               deleta_para_ajustes( $gerfsal_[$Igerfsal]["r14_rubric"], $gerfsal_[$Igerfsal]["r14_regist"], "S");
               db_delete( "gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
            }
         }
      }
   }
   // no salario o arquivo contado e o pessoal
}


$contador_registros = 1;

$matriz1 = array();
$matriz1 = array();

$matriz1[1] = "r20_regist";
$matriz1[2] = "r20_rubric";
$matriz1[3] = "r20_lotac";
$matriz1[4] = "r20_valor";
$matriz1[5] = "r20_quant";
$matriz1[6] = "r20_pd";
$matriz1[7] = "r20_semest";
$matriz1[8] = "r20_tpp";
$matriz1[9] = "r20_anousu";
$matriz1[10] = "r20_mesusu";
$matriz1[11] = "r20_instit";


for($Ipessoal=0;$Ipessoal< count($pessoal);$Ipessoal++){
   
   db_atutermometro($Ipessoal,count($pessoal),'calculo_folha',1);
   //echo "<BR> ".date("H:i:s")."Calculando registro $Ipessoal de ".count($pessoal).": ".$pessoal[$Ipessoal]["r01_regist"]. " numcgm --> ".$pessoal[$Ipessoal]["r01_regist"];
   //flush();

   if(db_empty($pessoal[$Ipessoal]["r01_causa"])){
     continue;
   }  
   $condicaoaux = " and r33_codtab = ".db_sqlformat( $pessoal[$Ipessoal]["r01_tbprev"]+2 );
   global $inssirf_;
   db_selectmax( "inssirf_", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux );
   
   $inssirf_base_ferias = "B002";
   
   if( !db_empty( $inssirf_[0]["r33_basfer"])){
      $inssirf_base_ferias = $inssirf_[0]["r33_basfer"];
   }
   
   $situacao_funcionario = situacao_funcionario( $pessoal[$Ipessoal]["r01_regist"] );
   
   $admissao_mais_um_ano = db_ctod( substr("#". db_dtoc($pessoal[$Ipessoal]["r01_admiss"]),1,6).db_str(db_year($pessoal[$Ipessoal]["r01_admiss"])+1,4) );
   
   $menos_um_ano = ( db_mktime($pessoal[$Ipessoal]["r01_recis"]) < db_mktime($admissao_mais_um_ano)? "s": "n" );

//echo "<BR> rescisao             dtoc() 0 --> ".substr("#". db_dtoc($pessoal[$Ipessoal]["r01_recis"]),1,6).db_str(db_year($pessoal[$Ipessoal]["r01_recis"]),4);
//echo "<BR> admissao_mais_um_ano dtoc() 1 --> ".substr("#". db_dtoc($pessoal[$Ipessoal]["r01_admiss"]),1,6).db_str(db_year($pessoal[$Ipessoal]["r01_admiss"]),4);
//echo "<BR> admissao_mais_um_ano dtoc() 2 --> ".substr("#". db_dtoc($pessoal[$Ipessoal]["r01_admiss"]),1,6).db_str(db_year($pessoal[$Ipessoal]["r01_admiss"])+1,4);
//echo "<BR> admissao_mais_um_ano ctod() --> $admissao_mais_um_ano";
//echo "<BR> menos_um_ano --> $menos_um_ano";

   $condicaoaux  = " and r59_regime = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regime"] );
   $condicaoaux .= " and r59_causa = ".db_sqlformat( $pessoal[$Ipessoal]["r01_causa"] );
   $condicaoaux .= " and r59_caub = ".db_sqlformat( $pessoal[$Ipessoal]["r01_caub"] );
   $condicaoaux .= " and lower(r59_menos1) = ".db_sqlformat( $menos_um_ano );
   global $rescisao;

//echo "<BR> condicaoaux --> $condicaoaux";

   db_selectmax( "rescisao", "select * from rescisao ".bb_condicaosubpes( "r59_" ).$condicaoaux );


   if( strtolower($chamada_geral) == "p"  ){
      $condicaoaux = " and r52_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
      if( !db_selectmax( "pensao", "select r52_regist from pensao ".bb_condicaosubpes( "r52_" ).$condicaoaux )){
         continue;  
      }else{
         $condicaoaux = " and r20_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
         db_delete( "gerfres", bb_condicaosubpes( "r20_" ).$condicaoaux );
      }
   }
   $condicaoaux = " and r19_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"]);
   $condicaoaux .= " order by r19_regist,r19_rubric ";
   global $pontofr;
   if( !db_selectmax( "pontofr", "select * from pontofr ".bb_condicaosubpes( "r19_" ).$condicaoaux )){
     continue;
   }
   
   $base_prev  = 0;
   $prev_desc  = 0;
   $base_irf   = 0;
   $r14_pd     = 0;
   $tot_prov   = 0;
   $tot_desc   = 0;
   if ($db_debug == true) { echo "[gerfres] 12 - total de desconto: $tot_desc<br>"; }
   $r14_valor  = 0; 
   $r14_quant  = 0;
   $salfamilia = 0; 
   
     
   carrega_fxxx(db_str($pessoal[$Ipessoal]["r01_regist"],6),true);
   
   le_rubricas_condicao();
   
   $F019=30; // F019 - Numero de dias a pagar no mes
   $F020=0;  // F020 - Numero de dias abono p/ pagar no mes
   $F023=0;  // F021 - Numero de dias p/ calc do FGTS no mes
   $F021=0;
   
   if( $pessoal[$Ipessoal]["r01_mremun"] > 0){
      $F007 = $pessoal[$Ipessoal]["r01_mremun"];
      $F010 = $pessoal[$Ipessoal]["r01_mremun"];
      $F001 = $pessoal[$Ipessoal]["r01_mremun"] / $F008;
   }
   
   $r110_regist = $pontofr[0]["r19_regist"];
   $r110_lotac  = $pontofr[0]["r19_lotac"];
   
   for($Ipontofr=0;$Ipontofr< count($pontofr) ;$Ipontofr++){
     
      $r20_quant   = $pontofr[$Ipontofr]["r19_quant"];
      $r20_form    = $pontofr[$Ipontofr]["r19_rubric"];
      $r20_valor   = $pontofr[$Ipontofr]["r19_valor"];
      
      
      $condicaoaux = " where rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat( $pontofr[$Ipontofr]["r19_rubric"] );
      global $rub_;
      db_selectmax( "rub_", "select * from rhrubricas ".$condicaoaux );
      $r20_pd = $rub_[0]["rh27_pd"];
      
      if($db_debug == true){ echo "[gerfres] 4 - chamando a função calc_rubrica() <br>"; }
      $r07_form = calc_rubrica($pontofr[$Ipontofr]["r19_rubric"],"pontofr","r19","r20",$recno_110,false);
      
      if( db_empty($r07_form) || (!db_empty($r07_form) && !db_empty($pontofr[$Ipontofr]["r19_valor"]))){
         $r20_valor = $pontofr[$Ipontofr]["r19_valor"];
      }else{
         $cod_erro  = 0;
         $r01_form = '$r07_form  = '.$r07_form.";";
         ob_start();
         eval($r01_form);
         db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontofr[$Ipontofr]["r19_rubric"]); 
         $r20_valor = round( $pontofr[$Ipontofr]["r19_quant"] * $r07_form, 2 );
         $r20_quant = $pontofr[$Ipontofr]["r19_quant"];
      }


      // proporcionaliza valores dos inativos conforme cadastro e rubricas
      // r01_propi --> Perc.Inativo       
      if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) != 'a' && $pessoal[$Ipessoal]["r01_propi"] > 0 
                                    && $pessoal[$Ipessoal]["r01_propi"] < 100 
                                    && ('t' == $rub_[0]["rh27_ propi"]) ){
         $r20_valor = round( $r20_valor * ( $pessoal[$Ipessoal]["r01_propi"] / 100 ),2 ) ;
      }

      if( $r20_valor > 0){
         $quant_formq = " ";
         if( !db_empty( $rub_[0]["rh27_formq"] )){

             if($db_debug == true){ echo "[gerfres] 5 - chamando a função calc_rubrica() <br>"; }
             $quant_formq = calc_rubrica("formq","pontofr","r19","r20",$recno_110,false,$rub_[0]["rh27_formq"],$r20_valor);
             $cod_erro_  = 0;
             $elem_erro_ =  " ";
             $r01_form = '$quant_formq  = '.$quant_formq.";";
             ob_start();
             eval($r01_form);
             db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$quant_formq,$rub_[0]["rh27_rubric"]); 
             $r20_quant = $quant_formq;

         }
 
         $matriz1 = array();
   $matriz1 = array();
   
         $matriz1[1] = "r20_regist";
         $matriz1[2] = "r20_rubric";
         $matriz1[3] = "r20_lotac";
         $matriz1[4] = "r20_valor";
         $matriz1[5] = "r20_quant";
         $matriz1[6] = "r20_pd";
         $matriz1[7] = "r20_semest";
         $matriz1[8] = "r20_tpp";
         $matriz1[9] = "r20_anousu";
         $matriz1[10] = "r20_mesusu";
         $matriz1[11] = "r20_instit";
      
         $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
         $matriz2[2] = $pontofr[$Ipontofr]["r19_rubric"];
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = round($r20_valor,2);
         $matriz2[5] = $r20_quant;
         $matriz2[6] = $r20_pd;
         $matriz2[7] = 0;
         $matriz2[8] = $pontofr[$Ipontofr]["r19_tpp"];
         $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[11] = $DB_instit;

         db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );

      }
   }
   
   $r14_valor = 0;
   if ($db_debug == true) { echo "[gerfres] 12 - r14_valor = $r14_valor  <br>"; }
   carrega_r9xx("pontofr","r19","r20",$recno_110,$opcao_tipo);
//echo "<BR> 22220 passou aqui !!!";
   $matriz1 = array();
   $matriz2 = array();
   
   $matriz1[1] = "r20_regist";
   $matriz1[2] = "r20_rubric";
   $matriz1[3] = "r20_lotac";
   $matriz1[4] = "r20_valor";
   $matriz1[5] = "r20_quant";
   $matriz1[6] = "r20_pd";
   $matriz1[7] = "r20_semest";
   $matriz1[8] = "r20_tpp";
   $matriz1[9] = "r20_anousu";
   $matriz1[10] = "r20_mesusu";
   $matriz1[11] = "r20_instit";


   // desconto de insuficiencia de saldo mes anterior ( r929 )
   
   if( $pessoal[$Ipessoal]["r01_arredn"] > 0){
      $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
      $matriz2[2] = $pessoal[$Ipessoal]["r01_rubric"];
      $matriz2[3] = $r110_lotac;
      $matriz2[4] = $pessoal[$Ipessoal]["r01_arredn"];
      $matriz2[5] = 0;
      $matriz2[6] = 2;
      $matriz2[7] = 0;
      $matriz2[8] = " ";
      $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
      $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
      $matriz2[11] = $DB_instit;

//echo "<BR> 22223 passou aqui !!!";
      db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );
//echo "<BR> 22224 passou aqui !!!";
   }
   // desconto do adiantamento de salario 
   
   $condicaoaux  = " and r22_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
   $condicaoaux .= " and r22_pd    != 3 ";
   
   global $gerfadi;
   if( db_selectmax( "gerfadi", "select * from gerfadi ".bb_condicaosubpes( "r22_" ).$condicaoaux )){
      for($Igerfadi=0;$Igerfadi< count($gerfadi);$Igerfadi++){
         $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
         $matriz2[2] = $gerfadi[$Igerfadi]["r22_rubric"];
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = $gerfadi[$Igerfadi]["r22_valor"];
         $matriz2[5] = $gerfadi[$Igerfadi]["r22_quant"];
         $matriz2[6] = 2;
         $matriz2[7] = 0;
         $matriz2[8] = " ";
         $matriz2[9] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[10] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[11] = $DB_instit;

//echo "<BR> 22225 passou aqui !!!";
         db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );
//echo "<BR> 22226 passou aqui !!!";

      }
   }
//echo "<BR> 22230 passou aqui !!!";
 }
//echo "<BR> 22231 passou aqui !!!";

}

/// fim da funcao gerfres ///


function gerffx($opcao_geral=null,$opcao_tipo=1){
  
  // globais de outras funcoes
 
  global $quais_diversos,$tot_prov, $tot_desc,$carregarubricas_geral, $db_debug;
  global $anousu, $mesusu, $DB_instit;
  
  global $campos_pessoal, $r110_regisi, $subpes,$chamada_geral,$chamada_geral_arquivo,$pessoal,$Ipessoal,$transacao,$cfpess;

  global $situacao_funcionario,$d08_carnes,$carregarubricas,$diversos,$Iinssirf,$inssirf,$subpes,$cadferia;

  global $dias_pagamento,$mes,$ano,$dias_do_mes,$vlr_sal_saude_ou_acidente,$valor_salario_maternidade ;
  global $rubricas,$prev_desc,$func_em_ferias,$ajusta,$sigla_ajuste,$inssirf_base_ferias;
  
  // GLOBAIS QUE PRECISAM MIGRAR PARA OUTRAS FUNCOES
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  

  global $quais_diversos;
  eval($quais_diversos);
 

  global $contador_registros;
  
  // esta variavel abaixo nao precisa mais
  global $recno_110,$r110_regist, $r110_lotac;
  
  $recno_110 = 0;

  global $r110_regisf;
  
  global $opcao_filtro,$opcao_gml,$r110_regisf,$r110_lotaci, $r110_lotacf,$faixa_regis,$faixa_lotac;
  
$siglag = "r53_";
$siglap = "r90_";

$calcula_valor_604 = false;
$calcula_valor_603 = false;

$condicao = $r110_lotaci . db_str($r110_regisi,6);
if( $opcao_tipo == 2 ){
//   $condicaoaux ="select distinct(r01_regist),".$campos_pessoal.",r90_regist from pessoal,pontofx ".bb_condicaosubpes( "r01_" );
//   $condicaoaux .= " and r01_regist = r90_regist ";
//   $condicaoaux .= " and r90_anousu = ".db_sqlformat(substr("#".$subpes,1,4));
//   $condicaoaux .= " and r90_mesusu = ".db_sqlformat(substr("#".$subpes,6,2));
//   $condicaoaux .= " and ( r01_recis is null or r01_recis >= ".db_sqlformat( db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
//   $condicaoaux .= " order by r01_regist ";
//   db_selectmax( "pessoal", $condicaoaux );


      $condicaoaux  = " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
      $condicaoaux .= " order by rh02_regist ";
      db_selectmax("pessoal", "select distinct(rh02_regist),".$campos_pessoal.",r90_regist from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                               and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                       left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                       left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
                                         and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                       left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes = rhpessoalmov.rh02_seqpes 
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       left outer join pontofx on r90_regist = rh02_regist
                                                  and r90_anousu= rhpessoalmov.rh02_anousu
                                                  and r90_mesusu= rhpessoalmov.rh02_mesusu
                                                  and r90_instit= rhpessoalmov.rh02_instit
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );
}else{

   if( $opcao_filtro <> "0" ){
       
      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"rh02_",$r110_regisi,$r110_regisf,$r110_lotaci,
                                    $r110_lotacf,$faixa_regis,$faixa_lotac);
//      db_selectmax( "pessoal", "select ".$campos_pessoal." from pessoal ".bb_condicaosubpes( "r01_" ).$condicaoaux );

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
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,
                                    $r110_lotacf,$faixa_regis,$faixa_lotac);
      
      db_delete( $chamada_geral_arquivo, bb_condicaosubpes( $siglag ).$condicaoaux );
   }

}

$contador_registros = 1;

$matriz1 = array();
$matriz2 = array();
$matriz1[1] = "r53_regist";
$matriz1[2] = "r53_rubric";
$matriz1[3] = "r53_lotac";
$matriz1[4] = "r53_valor";
$matriz1[5] = "r53_quant";
$matriz1[6] = "r53_pd";
$matriz1[7] = "r53_semest";
$matriz1[8] = "r53_anousu";
$matriz1[9] = "r53_mesusu";
$matriz1[10] = "r53_instit";

for($Ipessoal=0;$Ipessoal<count($pessoal);$Ipessoal++){  

   db_atutermometro($Ipessoal,count($pessoal),'calculo_folha',1);
//   echo "<BR> ".date("H:i:s")."Calculando registro $Ipessoal de ".count($pessoal).": ".$pessoal[$Ipessoal]["r01_regist"];
//   flush();
   
  //echo "<BR> 1.1 passou aqui !!!";
   $condicaoaux = " and r90_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
   $condicaoaux .= " order by r90_regist,r90_rubric ";
   global $pontofx;
   if( !db_selectmax( "pontofx", "select * from pontofx ".bb_condicaosubpes( "r90_" ).$condicaoaux )){
//  echo "<BR> 1.1 passou aqui !!!";
//   flush();
      continue;
   }
//  echo "<BR> 1.2 passou aqui !!!";

   $datafim = db_ctod(db_str(ndias(db_substr($subpes,-2)."/".db_substr($subpes,1,4)),2,0,"0")."/".db_substr($subpes,-2)."/".db_substr($subpes,1,4));
   $situacao_funcionario = situacao_funcionario($pessoal[$Ipessoal]["r01_regist"],$datafim);
  

   $condicaoaux = " and r33_codtab = ".db_sqlformat( $pessoal[$Ipessoal]["r01_tbprev"]+2 );
   global $inssirf_;
   db_selectmax( "inssirf_", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux );
   $inssirf_base_ferias = "B002";
   if( !db_empty( $inssirf_[0]["r33_basfer"] )){
      $inssirf_base_ferias = $inssirf_[0]["r33_basfer"];
   }

     
//  echo "<BR> 3 passou aqui !!!";
//   flush();
    
   carrega_fxxx(db_str($pontofx[0]["r90_regist"],6),true);

//echo "<BR> 4 passou aqui !!!";
//   flush();

   le_rubricas_condicao('gerffx');
//echo "<BR> 5 passou aqui !!!";
//   flush();
   
   $recno_110   = 0;
   $r110_regist = $pontofx[0]["r90_regist"];
   $r110_lotac  = $pontofx[0]["r90_lotac"];
    
   $base_inss = 0;
   $inss_desc = 0;
   $base_irf  = 0; 
   $valor904  = 0;
   $r53_pd    = 0;
   $tot_prov  = 0; 
   $tot_desc  = 0;
   if ($db_debug == true) { echo "[gerffx] 13 - total de desconto: $tot_desc<br>"; }
   
   for($Ipontofx=0;$Ipontofx<count($pontofx);$Ipontofx++){

//  echo "<BR> r90_rubric --> ".$pontofx[$Ipontofx]["r90_rubric"]. " valor --> ".$pontofx[$Ipontofx]["r90_valor"]. " quant --> ".$pontofx[$Ipontofx]["r90_quant"];
//   flush();
      // para rio grande, nao calcular estas rubricas pois sao avaliadas em  fontes
      
      if( trim( $d08_carnes ) == "riogrande" && db_at($pontofx[$Ipontofx]["r90_rubric"], "0603-0604-")> 0 ){
         continue;
      }
      $recno_112   = $Ipontofx;

      $r53_quant   = $pontofx[$Ipontofx]["r90_quant"];
      $r53_form    = $pontofx[$Ipontofx]["r90_rubric"];
      $r53_valor   = $pontofx[$Ipontofx]["r90_valor"];
      
      
      $condicaoaux = " where rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat( $pontofx[$Ipontofx]["r90_rubric"] );
      global $rub_;
      db_selectmax( "rub_", "select * from rhrubricas ".$condicaoaux );

      $r53_pd = $rub_[0]["rh27_pd"];
      
      if($db_debug == true){ echo "[gerffx] 6 - chamando a função calc_rubrica() <br>"; }
      $r07_form = calc_rubrica($pontofx[$Ipontofx]["r90_rubric"],"pontofx","r90","r53",$recno_110,false);

      if( db_empty($r07_form) ||  (!db_empty($r07_form) && !db_empty($pontofx[$Ipontofx]["r90_valor"]))){
         $r53_valor = $pontofx[$Ipontofx]["r90_valor"];
      }else{
         $cod_erro  = 0;
         $elem_erro =  " ";
         $r01_form = '$r07_form  = '.$r07_form.";";
         ob_start();
         eval($r01_form);
         db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontofx[$Ipontofx]["r90_rubric"]); 
         $r53_valor = $pontofx[$Ipontofx]["r90_quant"] * $r07_form;
         $r53_quant = $pontofx[$Ipontofx]["r90_quant"];
      }


      // proporcionaliza valores dos inativos conforme cadastro e rubricas
      // r01_propi --> Perc.Inativo 
      if( $pessoal[$Ipessoal]["r01_tpvinc"] != 'a' && $pessoal[$Ipessoal]["r01_propi"] > 0 && $pessoal[$Ipessoal]["r01_propi"] < 100 && ('t' == $rub_[0]["rh27_propi"])){
         $r53_valor = round( $r53_valor * ( $pessoal[$Ipessoal]["r01_propi"] / 100 ),2 ) ;
      }

      if( $r53_valor > 0){
         $matriz1 = array();
   $matriz2 = array();
   
         $matriz1[1] = "r53_regist";
         $matriz1[2] = "r53_rubric";
         $matriz1[3] = "r53_lotac";
         $matriz1[4] = "r53_valor";
         $matriz1[5] = "r53_quant";
         $matriz1[6] = "r53_pd";
         $matriz1[7] = "r53_semest";
         $matriz1[8] = "r53_anousu";
         $matriz1[9] = "r53_mesusu";
         $matriz1[10] = "r53_instit";
         
         $matriz2[1] = $r110_regist;
         $matriz2[2] = $pontofx[$Ipontofx]["r90_rubric"];
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = round($r53_valor,2);
         $matriz2[5] = $r53_quant;
         $matriz2[6] = $r53_pd;
         $matriz2[7] = 0;
         $matriz2[8] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[9] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[10] = $DB_instit;

         db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );

      }

   }

   $condicaoaux = " and r10_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
   $condicaoaux .= " order by r10_regist,r10_rubric ";
   global $pontofs;
   if( db_selectmax( "pontofs", "select * from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )){
      $recno_110fs   = 0;
      $r110_registfs = $pontofs[0]["r10_regist"];
      $r110_lotacfs  = $pontofs[0]["r10_lotac"];
      
      for($Ipontofs=0;$Ipontofs<count($pontofs);$Ipontofs++){
  //echo "<BR> r10_rubric --> ".$pontofs[$Ipontofs]["r10_rubric"]. " valor --> ".$pontofs[$Ipontofs]["r10_valor"]. " quant --> ".$pontofs[$Ipontofs]["r10_quant"];
         if( trim( $d08_carnes ) == "riogrande" && db_at($pontofs[$Ipontofs]["r10_rubric"],"0603-0604-") > 0){
            continue;
         }

         // rubricas de ferias nao devem ser lidas.
   
         if(db_at($pontofs[$Ipontofs]["r10_rubric"], 
     $cfpess[0]["r11_ferias"]."-".   // Rubrica onde é pago as férias    
     $cfpess[0]["r11_fer13"]."-".   // Rubrica onde é pago um 1/3 de férias
     $cfpess[0]["r11_fer13a"]."-".   // Rubrica onde é pago um 1/3 s/ abono de férias
     $cfpess[0]["r11_ferabo"]."-".   // Rubrica onde é pago o abono de férias
     $cfpess[0]["r11_feradi"]."-".   // Rubrica onde é pago o adiantamento de férias
     $cfpess[0]["r11_ferant"]."-".   // Rubrica onde é descontado as férias pagas no mês anterior
     $cfpess[0]["r11_feabot"]."-".   // Rubrica em que será lançado o abono do mês anterior
     $cfpess[0]["r11_fadiab"]) > 0){ // Rubrica onde será lançado o adiantamento s/abono de férias
            continue;
         }

         $recno_112fs   = $Ipontofs;
         $r53_quant   = $pontofs[$Ipontofs]["r10_quant"];
         $r53_form    = $pontofs[$Ipontofs]["r10_rubric"];
         $r53_valor   = $pontofs[$Ipontofs]["r10_valor"];

         $condicaoaux = " where rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat( $pontofs[$Ipontofs]["r10_rubric"] );
   global $rub_;
         db_selectmax( "rub_", "select * from rhrubricas ".$condicaoaux );

         // somente calcular as rubricas variaveis do ponto de salario
   
         $condicaoaux  = " and r53_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
         $condicaoaux .= " and r53_rubric = ".db_sqlformat( $rub_[0]["rh27_rubric"] );
         $condicaoaux .= " and r53_pd = ".$rub_[0]["rh27_pd"];

         if( db_val( $rub_[0]["rh27_rubric"] ) < 2000 && substr("#".$rub_[0]["rh27_rubric"],1,1) != "R" ){
      global $transacao;
            if( $rub_[0]["rh27_tipo"] == "2" || ( $rub_[0]["rh27_tipo"] == "1" && !db_selectmax( "transacao", "select * from gerffx ".bb_condicaosubpes("r53_").$condicaoaux ) ) ){

               $r53_pd = $rub_[0]["rh27_pd"];
               
               if($db_debug == true){ echo "[gerffx] 7 - chamando a função calc_rubrica() <br>"; }
               $r07_form = calc_rubrica($pontofs[$Ipontofs]["r10_rubric"],"pontofs","r10","r53",$recno_110fs,false);

               if( db_empty($r07_form) ||  (!db_empty($r07_form) && !db_empty($pontofs[$Ipontofs]["r10_valor"]))){
                  $r53_valor = $pontofs[$Ipontofs]["r10_valor"];
               }else{
                  $cod_erro  = 0;
                  $elem_erro =  " ";
                  $r01_form = '$r07_form  = '.$r07_form.";";
                  ob_start();
                  eval($r01_form);
                  db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontofs[$Ipontofs]["r10_rubric"]); 
                  $r53_valor = $pontofs[$Ipontofs]["r10_quant"] * $r07_form;
                  $r53_quant = $pontofs[$Ipontofs]["r10_quant"];
               }
              

               // proporcionaliza valores dos inativos conforme cadastro e rubricas
         // r01_propi --> Perc.Inativo 
               if( $pessoal[$Ipessoal]["r01_tpvinc"] != 'a' && $pessoal[$Ipessoal]["r01_propi"] > 0 && $pessoal[$Ipessoal]["r01_propi"] < 100 
                   && ('t' == $rub_[0]["rh27_propi"])){
                   $r53_valor = round( $r53_valor * ( $pessoal[$Ipessoal]["r01_propi"] / 100 ),2 ) ;
               }


               if( $r53_valor > 0){

                  $matriz1 = array();
      $matriz1 = array();
      
                  $matriz1[1] = "r53_regist";
                  $matriz1[2] = "r53_rubric";
                  $matriz1[3] = "r53_lotac";
                  $matriz1[4] = "r53_valor";
                  $matriz1[5] = "r53_quant";
                  $matriz1[6] = "r53_pd";
                  $matriz1[7] = "r53_semest";
                  $matriz1[8] = "r53_anousu";
                  $matriz1[9] = "r53_mesusu";
                  $matriz1[10] = "r53_instit";
      
                  $matriz2[1] = $r110_registfs;
                  $matriz2[2] = $pontofs[$Ipontofs]["r10_rubric"];
                  $matriz2[3] = $r110_lotacfs;
                  $matriz2[4] = round($r53_valor,2);
                  $matriz2[5] = $r53_quant;
                  $matriz2[6] = $r53_pd;
                  $matriz2[7] = 0;
                  $matriz2[8] = db_val( substr("#".$subpes,1,4 ));
                  $matriz2[9] = db_val( substr("#".$subpes,6,2 ));
                  $matriz2[10] = $DB_instit;

                  $condicaoaux  = " and r53_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
                  $condicaoaux .= " and r53_rubric = ".db_sqlformat( $pontofs[$Ipontofs]["r10_rubric"] );
                  $condicaoaux .= " and r53_pd = ".$r53_pd;
                  if( db_selectmax( "transacao", "select * from gerffx ".bb_condicaosubpes("r53_").$condicaoaux )){
                       $acao = "altera";
                  }else{
                       $acao = "insere";
                  }

                  if( $acao == "insere"){
                     db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );
                  }else{
                     db_update( $chamada_geral_arquivo,$matriz1,$matriz2, bb_condicaosubpes("r53_").$condicaoaux  );
                  }
               }
            }
         }
      }
   }

 }
 
}

/// fim da funcao gerffx ///

function gerfprovs13($opcao_geral=null,$opcao_tipo=1){
  // globais de outras funcoes
 
  global $quais_diversos,$tot_prov, $tot_desc,$carregarubricas_geral;
  global $anousu, $mesusu, $DB_instit;
  
  global $campos_pessoal, $r110_regisi, $subpes,$chamada_geral,$chamada_geral_arquivo,$pessoal,$Ipessoal,$transacao,$cfpess;

  global $situacao_funcionario,$naoencontroupontosalario,$d08_carnes,$carregarubricas,$diversos,$Iinssirf,$inssirf,$subpes,$cadferia;

  global $dias_pagamento,$mes,$ano,$dias_do_mes,$vlr_sal_saude_ou_acidente,$valor_salario_maternidade ;
  global $rubricas,$prev_desc,$func_em_ferias,$ajusta,$sigla_ajuste,$inssirf_base_ferias;
  
  // GLOBAIS QUE PRECISAM MIGRAR PARA OUTRAS FUNCOES
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  

  global $quais_diversos;
  eval($quais_diversos);
 

  global $opcao_filtro,$opcao_gml,$r110_regisf,$r110_lotaci, $r110_lotacf,$faixa_regis,$faixa_lotac;
  
  global $dtot_vpass,$dperc_pass,$dquant_pass,$quant_pass,$contador_registros;
  
  // esta variavel abaixo nao precisa mais
  global $recno_110,$r110_regist, $r110_lotac;
  
  $recno_110 = 0;

  $contador_registros = 1;
  
  global $r110_regisf,$numcgm;
  //$r110_regisf = $r110_regisi;
  
$siglap = "r92_";
$siglag = "r94_";

if( $opcao_tipo == 2){
//   $condicaoaux ="select distinct(r01_regist),".$campos_pessoal.",r34_regist from pessoal,pontof13 ".bb_condicaosubpes("r01_");
//   $condicaoaux .= " and r01_regist = r34_regist ";
//   $condicaoaux .= " and r34_anousu = ".db_sqlformat(substr("#".$subpes,1,4));
//   $condicaoaux .= " and r34_mesusu = ".db_sqlformat(substr("#".$subpes,6,2));
//   $condicaoaux .= " and ( r01_recis is null or r01_recis >= ".db_sqlformat( db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
//   $condicaoaux .= " order by r01_regist ";
//   db_selectmax( "pessoal", $condicaoaux );

    $condicaoaux  = " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
    $condicaoaux .= " order by rh02_regist ";
    db_selectmax("pessoal", "select distinct(rh02_regist),".$campos_pessoal.",r34_regist from rhpessoalmov 
                     inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                     inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                             and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                     inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                     left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                     left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                     left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
                                       and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                     left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes = rhpessoalmov.rh02_seqpes 
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                     left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                     left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                     left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                     left join rhpesprop on rh19_regist = rh02_regist
                     left outer join pontof13 on r34_regist = rhpessoalmov.rh02_regist
                                                 and r34_anousu= rhpessoalmov.rh02_anousu
                                                 and r34_mesusu= rhpessoalmov.rh02_mesusu
                                                 and r34_instit= rhpessoalmov.rh02_instit
                     ".bb_condicaosubpes("rh02_" ).$condicaoaux );

}

$matriz1 = array();
$matriz2 = array();

$matriz1[1] = "r94_regist";
$matriz1[2] = "r94_rubric";
$matriz1[3] = "r94_lotac";
$matriz1[4] = "r94_valor";
$matriz1[5] = "r94_quant";
$matriz1[6] = "r94_pd";
$matriz1[7] = "r94_semest";
$matriz1[8] = "r94_anousu";
$matriz1[9] = "r94_mesusu";
$matriz1[10] = "r94_instit";


for($Ipessoal=0;$Ipessoal< count($pessoal);$Ipessoal++){
   
   db_atutermometro($Ipessoal,count($pessoal),'calculo_folha',1);

   $condicaoaux = " and r92_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
   $condicaoaux .= " order by r92_regist,r92_rubric ";
   global $pontoprovf13;
   if( !db_selectmax( "pontoprovf13", "select * from pontoprovf13 ".bb_condicaosubpes( "r92_" ).$condicaoaux )){
//echo "<BR> ".date("H:i:s")." 1 Calculando registro $Ipessoal de ".count($pessoal).": ".$pessoal[$Ipessoal]["r01_regist"];
//flush();
      continue;
   }

   $base_prev  = 0;
   $prev_desc  = 0;
   $base_irf   = 0;
   $r94_pd     = 0; 
   $tot_prov   = 0;
   $tot_desc   = 0;
   if ($db_debug == true) { echo "[gerfprovs13] 14 - total de desconto: $tot_desc<br>"; }
   $r94_valor  = 0;
   $r94_quant  = 0;
   $salfamilia = 0;
   $tot_ferias = 0; 
   
   carrega_fxxx(db_str($pessoal[$Ipessoal]["r01_regist"],6),true);
   
   le_rubricas_condicao();
   
   $situacao_funcionario = situacao_funcionario($pessoal[$Ipessoal]["r01_regist"]);

//echo "<BR> situacao_funcionario 1 --> $situacao_funcionario";

   $condicaoaux = " and r33_codtab = ".db_sqlformat( $pessoal[$Ipessoal]["r01_tbprev"]+2 );
   global $inssirf_;
   db_selectmax( "inssirf_", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux );
   $inssirf_base_ferias = "B002";
   if( !db_empty( $inssirf_[0]["r33_basfer"] )){
      $inssirf_base_ferias = $inssirf_[0]["r33_basfer"];
   }
   $r110_regist = $pontoprovf13[0]["r92_regist"];
   $r110_lotac  = $pontoprovf13[0]["r92_lotac"];
   
   for($Ipontoprovf13=0;$Ipontoprovf13< count($pontoprovf13);$Ipontoprovf13++){
      $calcula_valor_275 = false;

      
      $r94_quant   = $pontoprovf13[$Ipontoprovf13]["r92_quant"];
      $r94_valor   = $pontoprovf13[$Ipontoprovf13]["r92_valor"];
      
      
      $condicaoaux = " where rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat( $pontoprovf13[$Ipontoprovf13]["r92_rubric"] );
      global $rub_;
      db_selectmax( "rub_", "select * from rhrubricas ".$condicaoaux );

      $r94_pd  = $rub_[0]["rh27_pd"];
      
      if($db_debug == true){ echo "[gerfprovs13] 8 - chamando a função calc_rubrica() <br>"; }
      $r07_form = calc_rubrica($pontoprovf13[$Ipontoprovf13]["r92_rubric"],"pontoprovf13","r92","r94",$recno_110,false);
      
      if( db_empty($r07_form) || (!db_empty($r07_form) && !db_empty($pontoprovf13[$Ipontoprovf13]["r92_valor"]))){
         $r94_valor = $pontoprovf13[$Ipontoprovf13]["r92_valor"];
      }else{
         $cod_erro  = 0;
         $elem_erro =  " ";
         $r07_form = '$r07_form  = '.$r07_form.";";
         ob_start();
         eval($r07_form);
         db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontoprovf13[$Ipontoprovf13]["r92_rubric"]); 
         $r94_valor = $pontoprovf13[$Ipontoprovf13]["r92_quant"] * $r07_form;
         $r94_quant = $pontoprovf13[$Ipontoprovf13]["r92_quant"];
      }


      // proporcionaliza valores dos inativos conforme cadastro e rubricas
      // r01_propi --> Perc.Inativo 
      if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) != 'a' && $pessoal[$Ipessoal]["r01_propi"] > 0 
                                    && $pessoal[$Ipessoal]["r01_propi"] < 100 
                                    && ('t' == $rub_[0]["rh27_propi"])) {
         $r94_valor = round( $r94_valor * ( $pessoal[$Ipessoal]["r01_propi"] / 100 ),2 ) ;
      }

      if( $r94_valor > 0){

         $quant_formq = " ";
         if( !db_empty( $rub_[0]["rh27_formq"] )){
          
            if($db_debug == true){ echo "[gerfprovs13] 9 - chamando a função calc_rubrica() <br>"; }
            $quant_formq = calc_rubrica("formq","pontoprovf13","r92","r94",$recno_110,false,$rub_[0]["rh27_formq"],$r94_valor);
            $cod_erro_  = 0;
            $elem_erro_ =  " ";
            $quant_form = '$quant_formq = '.$quant_formq.";";
            ob_start();
            eval($quant_form);
            db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$quant_form,$rub_[0]["rh27_rubric"]); 
            $r94_quant = $quant_formq;
         }

         if( $r94_pd == 2 ){
            $tot_desc += round($r94_valor,2);
            if ($db_debug == true) {
              echo "[gerfprovs13] 5 - tot_desc: $tot_desc<br>";
            }            
         }else{
            $tot_prov += round($r94_valor,2);
            $val_rubr = db_val(substr("#".$pontoprovf13[$Ipontoprovf13]["r92_rubric"],2,3));
         }
         
         $matriz1 = array();
         $matriz1 = array();
   
         $matriz1[1] = "r94_regist";
         $matriz1[2] = "r94_rubric";
         $matriz1[3] = "r94_lotac";
         $matriz1[4] = "r94_valor";
         $matriz1[5] = "r94_quant";
         $matriz1[6] = "r94_pd";
         $matriz1[7] = "r94_semest";
         $matriz1[8] = "r94_anousu";
         $matriz1[9] = "r94_mesusu";
         $matriz1[10] = "r94_instit";
         
         $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
         $matriz2[2] = $pontoprovf13[$Ipontoprovf13]["r92_rubric"];
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = round($r94_valor,2);
         $matriz2[5] = $r94_quant;
         $matriz2[6] = $r94_pd;
         $matriz2[7] = 0;
         $matriz2[8] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[9] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[10] = $DB_instit;
         db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );

      }

   }
   $r14_valor = 0;
   if ($db_debug == true) { echo "[gerfprovs13] 13 - r14_valor = $r14_valor  <br>"; }
   carrega_r9xx("pontoprovf13","r92","r94",$recno_110,$opcao_tipo);
   

   }

   
   // gravado para o ajuste de previdencia e ir
   

}

function gerfs13($opcao_geral=null,$opcao_tipo=1) {  
  // globais de outras funcoes
 
  global $quais_diversos,$tot_prov, $tot_desc,$carregarubricas_geral;
  global $anousu, $mesusu, $DB_instit,$db_debug;
  global $campos_pessoal, $r110_regisi, $subpes,$chamada_geral,$chamada_geral_arquivo,$pessoal,$Ipessoal,$transacao,$cfpess;
  global $situacao_funcionario,$naoencontroupontosalario,$d08_carnes,$carregarubricas,$diversos,$Iinssirf,$inssirf,$subpes,$cadferia;
  global $dias_pagamento,$mes,$ano,$dias_do_mes,$vlr_sal_saude_ou_acidente,$valor_salario_maternidade ;
  global $rubricas,$prev_desc,$func_em_ferias,$ajusta,$sigla_ajuste,$inssirf_base_ferias;
  
  // GLOBAIS QUE PRECISAM MIGRAR PARA OUTRAS FUNCOES
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  

  global $quais_diversos;
  eval($quais_diversos);
 
  global $opcao_filtro,$opcao_gml,$r110_regisf,$r110_lotaci, $r110_lotacf,$faixa_regis,$faixa_lotac;
  global $dtot_vpass,$dperc_pass,$dquant_pass,$quant_pass,$contador_registros;
  
  // esta variavel abaixo nao precisa mais
  global $recno_110,$r110_regist, $r110_lotac;
  
  $recno_110 = 0;

  $contador_registros = 1;
  
  global $r110_regisf,$numcgm;
  //$r110_regisf = $r110_regisi;
  
  if ($db_debug == true) {
    echo "[gerfs13] INICIO DO PROCESSAMENTO DA GERFS13($opcao_geral,$opcao_tipo)...<br>";
  }
    
$siglap = "r34_";
$siglag = "r35_";

if( $opcao_tipo == 2){
//   $condicaoaux ="select distinct(r01_regist),".$campos_pessoal.",r34_regist from pessoal,pontof13 ".bb_condicaosubpes("r01_");
//   $condicaoaux .= " and r01_regist = r34_regist ";
//   $condicaoaux .= " and r34_anousu = ".db_sqlformat(substr("#".$subpes,1,4));
//   $condicaoaux .= " and r34_mesusu = ".db_sqlformat(substr("#".$subpes,6,2));
//   $condicaoaux .= " and ( r01_recis is null or r01_recis >= ".db_sqlformat( db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
//   $condicaoaux .= " order by r01_regist ";
//   db_selectmax( "pessoal", $condicaoaux );

    $condicaoaux  = " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
    $condicaoaux .= " order by rh02_regist ";
    db_selectmax("pessoal", "select distinct(rh02_regist),".$campos_pessoal.",r34_regist from rhpessoalmov 
                     inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                     inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                             and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                     inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                     left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                     left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                     left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
                                       and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                     left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes = rhpessoalmov.rh02_seqpes 
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                     left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                     left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                     left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                     left join rhpesprop on rh19_regist = rh02_regist
                     left outer join pontof13 on r34_regist = rhpessoalmov.rh02_regist
                                                 and r34_anousu= rhpessoalmov.rh02_anousu
                                                 and r34_mesusu= rhpessoalmov.rh02_mesusu
                                                 and r34_instit= rhpessoalmov.rh02_instit
                     ".bb_condicaosubpes("rh02_" ).$condicaoaux );

} else {

   if( $opcao_filtro != "0" ){
       
      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"rh02_",$r110_regisi,$r110_regisf,$r110_lotaci,
                                    $r110_lotacf,$faixa_regis,$faixa_lotac);
//      $condicaoaux .= " order by  r01_regist ";

//      db_selectmax( "pessoal", "select ".$campos_pessoal." from pessoal ".bb_condicaosubpes( "r01_" ).$condicaoaux );

      $condicaoaux .= " order by  rh02_regist ";
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
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,
                                    $r110_lotacf,$faixa_regis,$faixa_lotac);
      global $$chamada_geral_arquivo;
      if( db_selectmax( $chamada_geral_arquivo, "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes( $siglag ).$condicaoaux )){
         $arquivo = $$chamada_geral_arquivo;
         for($Iarquivo=0;$Iarquivo< count($arquivo);$Iarquivo++){
            deleta_para_ajustes( $arquivo[$Iarquivo]["r35_rubric"], $arquivo[$Iarquivo]["r35_regist"], "3");

            db_delete( $chamada_geral_arquivo, bb_condicaosubpes( $siglag ).$condicaoaux );
         }
      }
   }

   //  no salario o arquivo contado e o pessoal
}


$matriz1 = array();
$matriz2 = array();

$matriz1[1] = "r35_regist";
$matriz1[2] = "r35_rubric";
$matriz1[3] = "r35_lotac";
$matriz1[4] = "r35_valor";
$matriz1[5] = "r35_quant";
$matriz1[6] = "r35_pd";
$matriz1[7] = "r35_semest";
$matriz1[8] = "r35_anousu";
$matriz1[9] = "r35_mesusu";
$matriz1[10] = "r35_instit";


for($Ipessoal=0;$Ipessoal< count($pessoal);$Ipessoal++){
   
   db_atutermometro($Ipessoal,count($pessoal),'calculo_folha',1);

   if( $chamada_geral == "p"  ){
      $condicaoaux = " and r52_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
      if( !db_selectmax( "pensao", "select r52_regist from pensao ".bb_condicaosubpes( "r52_" ).$condicaoaux )){
         continue;  
      }else{

         $condicaoaux = " and r35_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
         db_delete( "gerfs13", bb_condicaosubpes( "r35_" ).$condicaoaux );

      }
   }

   $condicaoaux = " and r34_regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
   $condicaoaux .= " order by r34_regist,r34_rubric ";
   global $pontof13;
   if( !db_selectmax( "pontof13", "select * from pontof13 ".bb_condicaosubpes( "r34_" ).$condicaoaux )){
//echo "<BR> ".date("H:i:s")." 1 Calculando registro $Ipessoal de ".count($pessoal).": ".$pessoal[$Ipessoal]["r01_regist"];
//flush();
      continue;
   }

   $base_prev  = 0;
   $prev_desc  = 0;
   $base_irf   = 0;
   $r14_pd     = 0; 
   $tot_prov   = 0;
   $tot_desc   = 0;
   if ($db_debug == true) { echo "[gerfs13] 18 - total de desconto: $tot_desc<br>"; }
   $r14_valor  = 0;
   $r14_quant  = 0;
   $salfamilia = 0;
   $tot_ferias = 0; 
   
   if ($db_debug == true) {
    echo "[gerfs13] chamando a função carrega_fxxx<br>";
   }
   carrega_fxxx(db_str($pessoal[$Ipessoal]["r01_regist"],6),true);
   
   if ($db_debug == true) {
    echo "[gerfs13] chamando a função le_rubricas_condicao<br>";
   }
   le_rubricas_condicao();
   
   $situacao_funcionario = situacao_funcionario($pessoal[$Ipessoal]["r01_regist"]);

//echo "<BR> situacao_funcionario 1 --> $situacao_funcionario";

   $condicaoaux = " and r33_codtab = ".db_sqlformat( $pessoal[$Ipessoal]["r01_tbprev"]+2 );
   global $inssirf_;
   db_selectmax( "inssirf_", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux );
   $inssirf_base_ferias = "B002";
   if( !db_empty( $inssirf_[0]["r33_basfer"] )){
      $inssirf_base_ferias = $inssirf_[0]["r33_basfer"];
   }
   $r110_regist = $pontof13[0]["r34_regist"];
   $r110_lotac  = $pontof13[0]["r34_lotac"];
   
   for($Ipontof13=0;$Ipontof13< count($pontof13);$Ipontof13++){
      $calcula_valor_275 = false;
      if( trim($d08_carnes) == "viamao"){
         if( $pontof13[$Ipontof13]["r34_rubric"] == "4275" && $pontof13[$Ipontof13]["r34_valor"] == 0){
             $calcula_valor_275 = true;
             continue;
         }
      }

      
      $r14_quant   = $pontof13[$Ipontof13]["r34_quant"];
      $r14_valor   = $pontof13[$Ipontof13]["r34_valor"];
      if ($db_debug == true) { echo "[gerfs13] 14 - r14_valor = $r14_valor  <br>"; }
      
      $condicaoaux = " where rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat( $pontof13[$Ipontof13]["r34_rubric"] );
      global $rub_;
      db_selectmax( "rub_", "select * from rhrubricas ".$condicaoaux );

      $r14_pd  = $rub_[0]["rh27_pd"];
      
      if($db_debug == true){ echo "[gerfs13] 10 - chamando a função calc_rubrica() <br>"; }
      $r07_form = calc_rubrica($pontof13[$Ipontof13]["r34_rubric"],"pontof13","r34","r35",$recno_110,false);
      
      if( db_empty($r07_form) || (!db_empty($r07_form) && !db_empty($pontof13[$Ipontof13]["r34_valor"]))){
         $r14_valor = $pontof13[$Ipontof13]["r34_valor"];
         if ($db_debug == true) { echo "[gerfs13] 15 - r14_valor = $r14_valor  <br>"; }
      }else{
         $cod_erro  = 0;
         $elem_erro =  " ";
         $r07_form = '$r07_form  = '.$r07_form.";";
         ob_start();
         eval($r07_form);
         db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontof13[$Ipontof13]["r34_rubric"]); 
         $r14_valor = $pontof13[$Ipontof13]["r34_quant"] * $r07_form;
         if ($db_debug == true) { echo "[gerfs13] 16 - r14_valor = $r14_valor  <br>"; }
         $r14_quant = $pontof13[$Ipontof13]["r34_quant"];
      }


      // proporcionaliza valores dos inativos conforme cadastro e rubricas
      // r01_propi --> Perc.Inativo 
      if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) != 'a' && $pessoal[$Ipessoal]["r01_propi"] > 0 
                                    && $pessoal[$Ipessoal]["r01_propi"] < 100 
                                    && ('t' == $rub_[0]["rh27_propi"])) {
         $r14_valor = round( $r14_valor * ( $pessoal[$Ipessoal]["r01_propi"] / 100 ),2 ) ;
         if ($db_debug == true) { echo "[gerfs13] 17 - r14_valor = $r14_valor  <br>"; }
      }

      if( $r14_valor > 0){

         $quant_formq = " ";
         if( !db_empty( $rub_[0]["rh27_formq"] )){
          
            if($db_debug == true){ echo "[gerfs13] 11 - chamando a função calc_rubrica() <br>"; }
            $quant_formq = calc_rubrica("formq","pontof13","r34","r35",$recno_110,false,$rub_[0]["rh27_formq"],$r14_valor);
            $cod_erro_  = 0;
            $elem_erro_ =  " ";
            $quant_form = '$quant_formq = '.$quant_formq.";";
            ob_start();
            eval($quant_form);
            db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$quant_form,$rub_[0]["rh27_rubric"]); 
            $r14_quant = $quant_formq;
         }

        if( $r14_pd == 2 ){
            $tot_desc += round($r14_valor,2);
            if ($db_debug == true) {
              echo "[gerfs13] 6 - tot_desc: $tot_desc<br>";
            }
         } else {
            $tot_prov += round($r14_valor,2);
            $val_rubr = db_val(substr("#".$pontof13[$Ipontof13]["r34_rubric"],2,3));
         }
         
         $matriz1 = array();
         $matriz1 = array();
   
         $matriz1[1] = "r35_regist";
         $matriz1[2] = "r35_rubric";
         $matriz1[3] = "r35_lotac";
         $matriz1[4] = "r35_valor";
         $matriz1[5] = "r35_quant";
         $matriz1[6] = "r35_pd";
         $matriz1[7] = "r35_semest";
         $matriz1[8] = "r35_anousu";
         $matriz1[9] = "r35_mesusu";
         $matriz1[10] = "r35_instit";
         
         $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
         $matriz2[2] = $pontof13[$Ipontof13]["r34_rubric"];
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = round($r14_valor,2);
         $matriz2[5] = $r14_quant;
         $matriz2[6] = $r14_pd;
         $matriz2[7] = 0;
         $matriz2[8] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[9] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[10] = $DB_instit;

         //
         // Ajuste de adiantamento inss / previdencia
         //
         if (strtolower($d08_carnes) == "canela" and $matriz2[2] == 'R934' ) {
           $matriz2[4] -= buscaValorAdiantamento13Canela($matriz2[1], $matriz2[8], $matriz2[9]);         
         }

         if ($db_debug == true) {
           echo "[gerfs13] 1 - Insert: $chamada_geral_arquivo<br>";
           echo "[gerfs13] Dados: <br>";
           echo "[gerfs13] r35_regist: ".$matriz2[1]."<br>";
           echo "[gerfs13] r35_rubric:" .$matriz2[2]."<br>";
           echo "[gerfs13] r35_lotac:"  .$matriz2[3]."<br>";
           echo "[gerfs13] r35_valor:"  .$matriz2[4]."<br>";
           echo "[gerfs13] r35_quant:"  .$matriz2[5]."<br>";
           echo "[gerfs13] r35_pd:"     .$matriz2[6]."<br>";
           echo "[gerfs13] r35_semest:" .$matriz2[7]."<br>";
           echo "[gerfs13] r35_anousu:" .$matriz2[8]."<br>";
           echo "[gerfs13] r35_mesusu:" .$matriz2[9]."<br>";
           echo "[gerfs13] r35_instit:" .$matriz2[10]."<br>";
           echo "<br>";
         }
         db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );
      }
   }
   $r14_valor = 0;
   if ($db_debug == true) { 
    echo "[gerfs13] 18 - r14_valor = $r14_valor  <br>";
    echo "[gerfs13] cahmando a função carrega_r9xx() "; 
   }
   carrega_r9xx("pontof13","r34","r35",$recno_110,$opcao_tipo);

   if( !db_empty($tot_prov) || !db_empty($tot_desc)){
      if( $tot_prov > $tot_desc){
         $r01_rubric = "R926";
         $tot_liq = $tot_prov - $tot_desc;
         $arredn = arredonda_100($tot_liq, $cfpess[0]["r11_arredn"]);
         $tot_liq += $arredn;
      }else{
         $arredn = $tot_desc-($tot_prov-$salfamilia);
         $r01_rubric = "R928";
//echo "<BR> rubrica 27.3 -->R928  valor --> $arredn"; // reis
      }
      if( $arredn > 0){
         $matriz2[1] = $pessoal[$Ipessoal]["r01_regist"];
         $matriz2[2] = $r01_rubric;
         $matriz2[3] = $r110_lotac;
         $matriz2[4] = $arredn;
         $matriz2[5] = 0;
         $matriz2[6] = 1;
         $matriz2[7] = 1;
         $matriz2[8] = db_val( substr("#".$subpes,1,4 ));
         $matriz2[9] = db_val( substr("#".$subpes,6,2 ));
         $matriz2[10] = $DB_instit;

         if ($db_debug == true) {
           echo "[gerfs13] 2 - Insert: $chamada_geral_arquivo<br>";
           echo "Dados: <br>";
           echo $matriz2[1] . " : " . $matriz2[1]."<br>";
           echo $matriz2[2] . " : " . $matriz2[2]."<br>";
           echo $matriz2[3] . " : " . $matriz2[3]."<br>";
           echo $matriz2[4] . " : " . $matriz2[4]."<br>";
           echo $matriz2[5] . " : " . $matriz2[5]."<br>";
           echo $matriz2[6] . " : " . $matriz2[6]."<br>";
           echo $matriz2[7] . " : " . $matriz2[7]."<br>";
           echo $matriz2[8] . " : " . $matriz2[8]."<br>";
           echo $matriz2[9] . " : " . $matriz2[9]."<br>";
           echo $matriz2[10]. " : " . $matriz2[10]."<br>";
           echo "<br>";
         }
         db_insert( $chamada_geral_arquivo,$matriz1, $matriz2 );

      }
   }
   
   // gravado para o ajuste de previdencia e ir
   
   if( $ajusta ){
      global $pes_prev;
      $pes_prev = array();
      grava_ajuste_previdencia();
     // grava_ajuste_irrf();
      if(count($pes_prev) == 0){
         grava_ajuste_irrf($pessoal[$Ipessoal]["r01_numcgm"],$r110_regist,strtolower($pessoal[$Ipessoal]["r01_tpvinc"]));
      }


   }

 }

 if ($db_debug == true ) {
   echo "[gerfs13] FIM DO PROCESSAMENTO DA FUNÇÃO gerfs13() <br><br>";  
 }

}

/// fim da funcao gerfs13 ///

 
/// gerfsal ///

function gerfsal($opcao_geral=null,$opcao_tipo=1)
{
  // globais de outras funcoes
  global $rubrica_maternidade, $rubrica_licenca_saude, $rubrica_acidente;
  global $anousu, $mesusu, $DB_instit, $db_debug;
  
  global $quais_diversos,$tot_prov, $tot_desc,$calcula_xvalor,$carregarubricas_geral;
  
  global $campos_pessoal, $r110_regisi, $subpes,$chamada_geral,$chamada_geral_arquivo,$pessoal,$Ipessoal,$transacao,$cfpess;
  
  global $situacao_funcionario,$naoencontroupontosalario,$d08_carnes,$carregarubricas,$diversos,$Iinssirf,$inssirf,$subpes,$cadferia;
  
  global $dias_pagamento,$mes,$ano,$dias_do_mes,$vlr_sal_saude_ou_acidente,$valor_salario_maternidade ;
  global $rubricas,$prev_desc,$func_em_ferias,$ajusta,$sigla_ajuste,$inssirf_base_ferias;
  
  // GLOBAIS QUE PRECISAM MIGRAR PARA OUTRAS FUNCOES
  global $F001, $F002, $F004, $F005, $F006,
  $F007, $F008, $F009, $F010, $F011,
  $F012, $F013, $F014, $F015, $F016,
  $F017, $F018, $F019, $F020, $F021,
  $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  
  
  global $quais_diversos;
  eval($quais_diversos);
  
  global $opcao_filtro,$opcao_gml,$r110_regisf,$r110_lotaci, $r110_lotacf,$faixa_regis,$faixa_lotac;
  
  global $dtot_vpass,$dperc_pass,$dquant_pass,$quant_pass,$contador_registros;
  
  // esta variavel abaixo nao precisa mais
  global $recno_110,$r110_regist, $r110_lotac;
  
  $recno_110 = 0;
  
  global $r110_regisf,$numcgm;
  
  global $calcula_valor_1602,
  $calcula_valor_514,
  $calcula_valor_275,
  $calcula_valor_131,
  $calcula_valor_334,
  $calcula_valor_256,
  $calcula_valor_291,
  $calcula_valor_604,
  $calcula_valor_603,
  $calcula_valor_840,
  $calcula_valor_841,
  $calcula_valor_759,
  $calcula_valor_758,
  $calcula_valor_776;
  
  $salario_esposa = 0;
  $calcula_valor_053 = false;
  // itaqui;
  $calcula_valor_055 = false;
  $calcula_valor_067 = false;
  //$r110_regisf = $r110_regisi;
  
  if ($db_debug == true) {
    echo "[gerfsal] INICIANDO PROCESSAMENTO DA FUNÇÃO gerfsal()... <br>";
  }
  if ($opcao_geral == 1 ) {
    $siglag = "r14_";
    $siglap = "r10_";
  } else {
    $siglag = "r48_";
    $siglap = "r47_";
  }
  //echo "<BR>  1 - entrou no gerfsal() ";
  
  $stringferias  = "('".$cfpess[0]["r11_ferias"]."','".$cfpess[0]["r11_fer13"]."','";
  $stringferias .= $cfpess[0]["r11_fer13a"]."','".$cfpess[0]["r11_ferabo"]."','";
  $stringferias .= $cfpess[0]["r11_feradi"]."','".$cfpess[0]["r11_ferant"]."','";
  $stringferias .= $cfpess[0]["r11_feabot"]."','".$cfpess[0]["r11_fadiab"]."')";
  
  $rubricas_calc_integral = "";
  
  $verifica_vazio = trim($cfpess[0]["r11_rubpgintegral"]);
  //  echo "<BR><BR>verifica_vazio = ================  $verifica_vazio";
  if (!db_empty($verifica_vazio )) {
    //  echo "<BR><BR>entrou no vazio";
    
    $rubricas_calc_integral = "";
    //echo"<BR> tamanho formula especial ".$cfpess[0]["r11_desliq"];
    //echo"<BR><BR>-------------".strlen(trim($cfpess[0]["r11_desliq"]))." -------------<BR><BR>";
    for ($icalc=0; $icalc < strlen(trim($cfpess[0]["r11_rubpgintegral"])); $icalc+=4) {
      $rubricas_calc_integral .= ",'".substr("#". trim($cfpess[0]["r11_rubpgintegral"]), $icalc+1, 4 )."'";
    }
  }
  
  if ($opcao_tipo == 2 ) {
    if ($opcao_geral == 1) {

      $condicaoaux  = " and rh05_recis is null ";
      $condicaoaux .= " order by rh02_regist ";
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
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

    } else {
      
      $condicaoaux  = " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
      $condicaoaux .= " order by rh02_regist ";
      db_selectmax("pessoal", "select distinct(rh02_regist),".$campos_pessoal.",r47_regist,r29_regist from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                               and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                       left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                       left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
                                         and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                       left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes = rhpessoalmov.rh02_seqpes
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       left outer join pontocom on r47_regist = rhpessoalmov.rh02_regist 
                                                   and r47_anousu= rhpessoalmov.rh02_anousu
                                                   and r47_mesusu= rhpessoalmov.rh02_mesusu
                                                   and r47_instit= rhpessoalmov.rh02_instit
                       left outer join pontofe  on r29_regist = rhpessoalmov.rh02_regist
                                                   and r29_anousu= rhpessoalmov.rh02_anousu
                                                   and r29_mesusu= rhpessoalmov.rh02_mesusu
                                                   and r29_instit= rhpessoalmov.rh02_instit
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

    }
    
    //echo "<BR>  1 - passou aqui ";
  } else {
    //echo "<BR>  1 - passou aqui ";
    if ($opcao_filtro != "0" ) {
      
      //echo "<BR>  22- passou aqui ";
      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"rh02_",$r110_regisi,$r110_regisf,$r110_lotaci,
      $r110_lotacf,$faixa_regis,$faixa_lotac);
      
      //echo "<BR>  condicao ".$condicaoaux;
//      if ($opcao_geral == 1) {
//        $condicaoaux .= " and r01_recis is null ";
//      } else {
//        $condicaoaux .= " and ( r01_recis is null or r01_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
//      }
//      db_selectmax("pessoal", "select ".$campos_pessoal." from pessoal ".bb_condicaosubpes("r01_" ).$condicaoaux );
      
      if ($opcao_geral == 1) {
        $condicaoaux .= " and rh05_recis is null ";
      } else {
        $condicaoaux .= " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
      }
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
                                                   and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                       left join rhpesfgts on rhpesfgts.rh15_regist = rhpessoalmov.rh02_regist
                       left join tpcontra on tpcontra.h13_codigo = rh02_tpcont  
                       left join rhinssoutros on rh51_seqpes = rh02_seqpes 
                       left join rhpesprop on rh19_regist = rh02_regist
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,
      $r110_lotacf,$faixa_regis,$faixa_lotac);
      
      
      global $$chamada_geral_arquivo;
      
      if (db_selectmax($chamada_geral_arquivo, "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux )) {
        $arq_ = $$chamada_geral_arquivo;
        
        if ($opcao_geral == 1 ) {
          //echo "<BR> Apagar registro do gerfsal";
          global $Igerfsal;
          for ($Iarq=0; $Iarq<count($arq_); $Iarq++) {
            deleta_para_ajustes($arq_[$Iarq]["r14_rubric"], $arq_[$Iarq]["r14_regist"] , "S");
          }
          
          $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,
          $r110_lotacf,$faixa_regis,$faixa_lotac);
          
          db_delete($chamada_geral_arquivo, bb_condicaosubpes($siglag ).$condicaoaux );
          //echo "<BR> Registros removidos";
          
        } else {
          for ($Iarq=0; $Iarq<count($arq_); $Iarq++) {
            deleta_para_ajustes($arq_[$Iarq]["r48_rubric"], $arq_[$Iarq]["r48_regist"], "C");
            
          }
          $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"r14_",$r110_regisi,$r110_regisf,$r110_lotaci,
          $r110_lotacf,$faixa_regis,$faixa_lotac);
          global $gerfsal_;
          if (db_selectmax("gerfsal_", "select * from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
            for ($Igerfsal=0; $Igerfsal<count($gerfsal_); $Igerfsal++) {
              deleta_para_ajustes($gerfsal_[$Igerfsal]["r14_rubric"], $gerfsal_[$Igerfsal]["r14_regist"], "S");
            }
            db_delete("gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
            
          }
          $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,$siglag,$r110_regisi,$r110_regisf,$r110_lotaci,
          $r110_lotacf,$faixa_regis,$faixa_lotac);
          
          db_delete($chamada_geral_arquivo, bb_condicaosubpes($siglag ).$condicaoaux );
        }
      }
      //echo "<BR><BR><BR> $opcao_geral ---- $opcao_tipo <BR><BR><BR>$opcao_filtro<BR><BR>";
      
      
      if ($opcao_geral == 1) {
        
        
        //echo "<BR> 1 - stringferias --> $stringferias";
        
        $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"r10_",$r110_regisi,$r110_regisf,$r110_lotaci,
        $r110_lotacf,$faixa_regis,$faixa_lotac);
        
        $condicaoaux .= " and ( r10_rubric in  " . $stringferias;
        
        if (strtolower($d08_carnes) == "saocarlos") {
          $condicaoaux .= " or r10_rubric in ('0270') ";
        }
        $condicaoaux .= "  or r10_rubric between '2000' and '3999' )";
        // rubricas de Ferias
        
        db_delete("pontofs", bb_condicaosubpes("r10_").$condicaoaux );
        //echo "<BR> 2 - remove rubricas de ferias";
        
        
      } else {
        
        
        $condicaoaux = db_condicaoaux($opcao_filtro,$opcao_gml,"r47_",$r110_regisi,$r110_regisf,$r110_lotaci,
        $r110_lotacf,$faixa_regis,$faixa_lotac);
        
        $condicaoaux .= " and ( r47_rubric in ".$stringferias;
        if (strtolower($d08_carnes) == "saocarlos") {
          $condicaoaux .= " or r47_rubric in ('0270') ";
        }
        $condicaoaux .= " or r47_rubric between '2000' and '3999' )";
        // rubricas de Ferias
        
        db_delete("pontocom", bb_condicaosubpes("r47_").$condicaoaux );
        //echo "<BR> 1 - remove rubricas de ferias";
      }
      
    }
    //echo "<BR> 1.23 quantidade --> ".count($pessoal);
    
  }
  $contador_registros = 1;
  
  for ($Ipessoal=0; $Ipessoal<count($pessoal); $Ipessoal++) {
    

    $r110_regist = $pessoal[$Ipessoal]["r01_regist"];
    $r110_lotac  = $pessoal[$Ipessoal]["r01_lotac"];

    db_atutermometro($Ipessoal,count($pessoal),'calculo_folha',1);
    
    $tot_prov = 0;
    $tot_desc = 0;
    
    if ($chamada_geral == "p"  ) {

      $condicaoaux = " and r52_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      if (!db_selectmax("pensao", "select r52_regist from pensao ".bb_condicaosubpes("r52_" ).$condicaoaux )) {
        continue;
      } else {

        if ($opcao_geral == 1) {
          
          $condicaoaux = " and r14_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          $retornar    = db_delete("gerfsal", bb_condicaosubpes("r14_" ).$condicaoaux );
          
          $condicaoaux = " and r10_rubric in  " . $stringferias;
          if (strtolower($d08_carnes ) == "saocarlos") {
            $condicaoaux .= " or r10_rubric in ('0270') ";
          }
          $condicaoaux .= "  or r10_rubric = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          
          db_delete("pontofs", bb_condicaosubpes("r10_").$condicaoaux );
          
        } else {
          
          $condicaoaux = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          $retornar    = db_delete("gerfcom", bb_condicaosubpes("r48_" ).$condicaoaux );
          
          $condicaoaux = " and r47_rubric in  " . $stringferias;
          if (strtolower($d08_carnes ) == "saocarlos") {
            $condicaoaux .= " or r47_rubric in ('0270') ";
          }
          $condicaoaux .= "  or r47_rubric = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          
          db_delete("pontocom", bb_condicaosubpes("r47_").$condicaoaux );
        }
        
      }
    }
    
    $condicaoaux = " and r33_codtab = ".db_sqlformat($pessoal[$Ipessoal]["r01_tbprev"]+2 );
    global $inssirf_;
    db_selectmax("inssirf_", "select * from inssirf ".bb_condicaosubpes("r33_" ).$condicaoaux );
    $rubrica_maternidade   = trim($inssirf_[0]["r33_rubmat"]);
    $rubrica_licenca_saude = trim($inssirf_[0]["r33_rubsau"]);
    $rubrica_acidente      = trim($inssirf_[0]["r33_rubaci"]);
    $inssirf_base_ferias   = "B002";
    if (!db_empty($inssirf_[0]["r33_basfer"] )) {
      $inssirf_base_ferias = $inssirf_[0]["r33_basfer"];
    }
    //echo "<BR> inssirf_base_ferias --> $inssirf_base_ferias r01_tbprev --> ".$pessoal[$Ipessoal]["r01_tbprev"];
    
    $valor_salario_fam = 0;
    $valor_salario_maternidade = 0;
    //echo "<BR>  Verifica férias AKI";
    
    ferias($pessoal[$Ipessoal]["r01_regist"]," ");
    
    //echo "<BR>  saiu Verifica férias AKI";
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

    if ($opcao_geral == 1) {
      
      $naoencontroupontosalario = false;
      $condicaoaux  = " and r10_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " order by r10_regist,r10_rubric ";
      //echo "<BR> Pesquisa ponto salario";
      global $pontofs;
      if (!db_selectmax("pontofs", "select * from pontofs ".bb_condicaosubpes("r10_" ).$condicaoaux )) {
        
        $naoencontroupontosalario = true;
        if ($F019 < 30) {
          // F019 - Numero de dias a pagar no mes
          
          //echo "<BR><BR>entra Verifica férias 1";
          verifica_ferias_100();
          //echo "<BR><BR>sai Verifica férias 1";
          
          $condicaoaux = " and r10_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          $condicaoaux .= " order by r10_regist,r10_rubric ";
    
          if (!db_selectmax("pontofs", "select * from pontofs ".bb_condicaosubpes("r10_" ).$condicaoaux )) {

             if ($opcao_geral == 1) {

                if (!db_empty($pessoal[$Ipessoal]["r01_arredn"])) {
                  
                  $tot_desc += $pessoal[$Ipessoal]["r01_arredn"];
                  if ($db_debug == true) {
                    echo "[gerfsal] 10 - tot_desc: $tot_desc<br>";
                  }
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
                    if ($db_debug == true) {
                      echo "[gerfsal] 1 - Insert: Gerfsal<br>";
                      echo "Dados: <br>";
                      echo "r14_regist: ".$matriz2[1]."<br>";
                      echo "r14_rubric:".$matriz2[2]."<br>";
                      echo "r14_lotac:".$matriz2[3]."<br>";
                      echo "r14_valor:".$matriz2[4]."<br>";
                      echo "r14_quant:".$matriz2[5]."<br>";
                      echo "r14_pd:".$matriz2[6]."<br>";
                      echo "r14_semest:".$matriz2[7]."<br>";
                      echo "r14_anousu:".$matriz2[8]."<br>";
                      echo "r14_mesusu:".$matriz2[9]."<br>";
                      echo "r14_instit:".$matriz2[10]."<br>";
                      echo "<br>";
                    }
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
                    if ($db_debug == true) {
                      echo "[gerfsal] 2 - Insert: Gerfsal<br>";
                      echo "Dados: <br>";
                      echo "r14_regist: ".$matriz2[1]."<br>";
                      echo "r14_rubric:".$matriz2[2]."<br>";
                      echo "r14_lotac:".$matriz2[3]."<br>";
                      echo "r14_valor:".$matriz2[4]."<br>";
                      echo "r14_quant:".$matriz2[5]."<br>";
                      echo "r14_pd:".$matriz2[6]."<br>";
                      echo "r14_semest:".$matriz2[7]."<br>";
                      echo "r14_anousu:".$matriz2[8]."<br>";
                      echo "r14_mesusu:".$matriz2[9]."<br>";
                      echo "r14_instit:".$matriz2[10]."<br>";
                      echo "<br>";
                    }
                    
                    db_insert("gerfsal", $matriz1, $matriz2 );
                  }
                }else{
                  continue;
                }
             }else{
                continue;
             }
          }
        } else if ($F019 == 30 ) {
          // F019 - Numero de dias a pagar no mes
          //echo "<BR><BR>entra Verifica férias 2";
          
          verifica_ferias_100();
          //echo "<BR><BR>sai Verifica férias 2";
        } else {
          
          $tot_prov =0;
          $tot_desc =0;
          if ($db_debug == true) { 
            echo "[gerfsal] 23 - tot_desc: $tot_desc<br>"; 
          }
        }
      } else {

        if ($F019 > 0 || $F020 > 0 || $F023 > 0) {
          // F019 - Numero de dias a pagar no mes
          // F020 - Numero de dias abono p/ pagar no mes
          
          //echo "<BR><BR>entra Verifica férias 3";
          verifica_ferias_100();
          //echo "<BR><BR>sai   Verifica férias 3";
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
          
          //echo "<BR><BR>entra Verifica férias 4";
          verifica_ferias_100();
          //echo "<BR><BR>sai Verifica férias 4";
          
          $condicaoaux = " and r47_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          $condicaoaux .= " order by r47_regist,r47_rubric ";
          if (!db_selectmax("pontocom", "select * from pontocom ".bb_condicaosubpes("r47_" ).$condicaoaux )) {
            continue;
          }
        } else if ($F019 == 30) {
          // F019 - Numero de dias a pagar no mes
          //echo "<BR><BR>entra Verifica férias 5";
          verifica_ferias_100();
          //echo "<BR><BR>sai Verifica férias 5";
        } else {
          $tot_prov = 0;
          $tot_desc = 0;
        }
      } else {
        if ($F019 > 0 || $F020 > 0 || $F023 > 0) {
          // F019 - Numero de dias a pagar no mes
          // F020 - Numero de dias abono p/ pagar no mes
          verifica_ferias_100();
        }
      }
      
    }

    $situacao_afastado = false;
    if ($opcao_geral == 1) {
      $situacao_funcionario = situacao_funcionario($pessoal[$Ipessoal]["r01_regist"]);
    } else {
      $situacao_funcionario = 1;
      // Normal
    }

    //echo "<BR> Situacao funcionario -> $situacao_funcionario";
    
    
    if ($opcao_geral == 1 ) {
      //echo "<BR> 010-Carrega FXXX salario";
      carrega_fxxx($pessoal[$Ipessoal]["r01_regist"],true,"gerfsal");
    } else {
      //echo "<BR> 010-Carrega FXXX complementar";
      carrega_fxxx($r110_regist,true,"gerfcom");
    }
    

    le_rubricas_condicao();
    
    $calcula_valor_1602= false;
    // sapiranga;
    $calcula_valor_514 = false;
    // carazinho e amparo (plano saude);
    $calcula_valor_275 = false;
    // viamao;
    $calcula_valor_131 = false;
    $calcula_valor_334 = false;
    $calcula_valor_256 = false;
    $calcula_valor_291 = false;
    $calcula_valor_604 = false;
    // rio grande;
    $calcula_valor_603 = false;
    $calcula_valor_840 = false;
    $calcula_valor_841 = false;
    $calcula_valor_759 = false;
    $calcula_valor_758 = false;
    $calcula_valor_776 = false;
    $salario_esposa = 0;
    $calcula_valor_053 = false;
    // itaqui;
    $calcula_valor_055 = false;
    $calcula_valor_067 = false;
    
    $rubricas_calculos_especiais = "";
    
    $verifica_vazio = trim($cfpess[0]["r11_desliq"]);
    //  echo "<BR><BR>verifica_vazio = ================  $verifica_vazio";
    if (!db_empty($verifica_vazio )) {

      //  echo "<BR><BR>entrou no vazio";
      
      $rubricas_calculos_especiais = "(";
      //echo"<BR> tamanho formula especial ".$cfpess[0]["r11_desliq"];
      //echo"<BR><BR>-------------".strlen(trim($cfpess[0]["r11_desliq"]))." -------------<BR><BR>";
      for ($icalc=0; $icalc < strlen(trim($cfpess[0]["r11_desliq"])); $icalc+=4) {
        
        $rubrica_desconto = substr("#". trim($cfpess[0]["r11_desliq"]), $icalc+1, 4 ) ;
        //private calcula_valor_&rubrica_desconto            ;
        //public calcula_xvalor_&rubrica_desconto;
        $calcula_yvalor = "calcula_xvalor_".$rubrica_desconto;
        global $$calcula_yvalor;
        $$calcula_yvalor = false;
        
        $rubricas_calculos_especiais .= "'".$rubrica_desconto."',";
      }
      $rubricas_calculos_especiais = substr("#".$rubricas_calculos_especiais,1,strlen($rubricas_calculos_especiais)-1 ).")";
      
    }
    $gerou_rubrica_calculo = false;
    
    if ($opcao_geral == 1) {
      $naoencontroupontosalario = false;
      $condicaoaux  = " and r10_regist  = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " order by r10_regist,r10_rubric ";
      //echo "<BR> Pesquisa ponto salario";
      global $pontofs;
      db_selectmax("pontofs", "select * 
                                 from pontofs 
                               inner join rhrubricas on r10_instit = rh27_instit 
                                                    and r10_rubric = rh27_rubric ".bb_condicaosubpes("r10_" ).$condicaoaux );

    } else {
      $condicaoaux = " and r47_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " order by r47_regist,r47_rubric ";
      global $pontocom;
      db_selectmax("pontocom", "select * from pontocom inner join rhrubricas on r47_instit = rh27_instit and r47_rubric = rh27_rubric ".bb_condicaosubpes("r47_" ).$condicaoaux );
    }
    
    if ($opcao_geral == 1) {
      $quant_pontofs = count($pontofs);
    } else {
      $quant_pontofs = count($pontocom);
    }
    if ($quant_pontofs ==0) {
       if ($opcao_geral == 1) {
          //echo "<BR><BR> 2 Continue";
          if (!db_empty($pessoal[$Ipessoal]["r01_arredn"])) {
            //echo "<BR><BR> 3 Continue";
            $tot_desc += $pessoal[$Ipessoal]["r01_arredn"];
            if ($db_debug == true) {            
              echo "[gerfsal] 11 - tot_desc: $tot_desc<br>";
            }
            $tot_prov += $pessoal[$Ipessoal]["r01_arredn"];
            $gerou_rubrica_calculo = true;
            
            $matriz1 = array();
            $matriz2 = array();
            
            $matriz1[1] = "r14_regist";
            $matriz1[2] = "r14_rubric";
            $matriz1[3] = "r14_lotac";
            $matriz1[4] = "r14_valor";
            $matriz1[5] = "r14_quant";
            $matriz1[6] = "r14_pd";
            $matriz1[7] = "r14_semest";
            $matriz1[8] = "r14_anousu";
            $matriz1[9] = "r14_mesusu";
            $matriz1[10] = "r14_instit";
            
            $matriz2[1] = $r110_regist;
            $matriz2[2] = $pessoal[$Ipessoal]["r01_rubric"];
            $matriz2[3] = $r110_lotac;
            $matriz2[4] = $pessoal[$Ipessoal]["r01_arredn"];
            $matriz2[5] = 0;
            $matriz2[6] = 2;
            $matriz2[7] = 0;
            $matriz2[8] = $anousu;
            $matriz2[9] = $mesusu;
            $matriz2[10] = $DB_instit;
            
            $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist );
            $condicaoaux .= " and r14_pd = 2 ";
            $condicaoaux .= " and r14_rubric = ".db_sqlformat($pessoal[$Ipessoal]["r01_rubric"] );
            
            if (db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
              db_update("gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_" ).$condicaoaux );
            } else {
              if ($db_debug == true) {
                 echo "[gerfsal] 3 - Insert: Gerfsal<br>";
                 echo "Dados: <br>";
                 echo "r14_regist: ".$matriz2[1]."<br>";
                 echo "r14_rubric:".$matriz2[2]."<br>";
                 echo "r14_lotac:".$matriz2[3]."<br>";
                 echo "r14_valor:".$matriz2[4]."<br>";
                 echo "r14_quant:".$matriz2[5]."<br>";
                 echo "r14_pd:".$matriz2[6]."<br>";
                 echo "r14_semest:".$matriz2[7]."<br>";
                 echo "r14_anousu:".$matriz2[8]."<br>";
                 echo "r14_mesusu:".$matriz2[9]."<br>";
                 echo "r14_instit:".$matriz2[10]."<br>";
                 echo "<br>";
              }
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
              if ($db_debug == true) {
                echo "[gerfsal] 4 - Insert: Gerfsal<br>";
                echo "Dados: <br>";
                echo "r14_regist: ".$matriz2[1]."<br>";
                echo "r14_rubric:".$matriz2[2]."<br>";
                echo "r14_lotac:".$matriz2[3]."<br>";
                echo "r14_valor:".$matriz2[4]."<br>";
                echo "r14_quant:".$matriz2[5]."<br>";
                echo "r14_pd:".$matriz2[6]."<br>";
                echo "r14_semest:".$matriz2[7]."<br>";
                echo "r14_anousu:".$matriz2[8]."<br>";
                echo "r14_mesusu:".$matriz2[9]."<br>";
                echo "r14_instit:".$matriz2[10]."<br>";
                echo "<br>";
              }             
              db_insert("gerfsal", $matriz1, $matriz2 );
            }
          }else{
            continue;
          }
       }else{
          continue;
       }
    }
    //echo "<BR> Calcula ponto";
    //echo "<BR> Valor do Registro ->".$pessoal[$Ipessoal]["r01_regist"];
    
    global $Iponto;
    for ($Iponto=0; $Iponto<$quant_pontofs; $Iponto++) {

// Inicio --> Rubricas que seram calculadas por ultimo em calculos especificos 

      if ($d08_carnes == "sapiranga") {
        if ($opcao_geral == 1) {
          if ($pontofs[$Iponto]["r10_rubric"] == "1602") {
            $calcula_valor_1602 = true;
            // echo "<BR><BR>Continue 2";
            continue;
          }
        }
      }
      if ($d08_carnes == "riogrande") {
        if ($opcao_geral == 1) {
          if ($pontofs[$Iponto]["r10_rubric"] == "0604") {
            $calcula_valor_604 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0841") {
            $calcula_valor_841 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0603") {
            $calcula_valor_603 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0840") {
            $calcula_valor_840 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0759") {
            $calcula_valor_759 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0758") {
            $calcula_valor_758 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0776") {
            $calcula_valor_776 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0045") {
            $salario_esposa = $pontofs[$Iponto]["r10_valor"];
          }
        } else {
          if ($pontocom[$Iponto]["r47_rubric"] == "0604") {
            $calcula_valor_604 = true;
            continue;
          }
          if ($pontocom[$Iponto]["r47_rubric"] == "0603") {
            $calcula_valor_603 = true;
            continue;
          }
          if ($pontocom[$Iponto]["r47_rubric"] == "0840") {
            $calcula_valor_840 = true;
            continue;
          }
          if ($pontocom[$Iponto]["r47_rubric"] == "0759") {
            $calcula_valor_759 = true;
          }
          if ($pontocom[$Iponto]["r47_rubric"] == "0758") {
            $calcula_valor_758 = true;
            continue;
          }
          if ($pontocom[$Iponto]["r47_rubric"] == "0776") {
            $calcula_valor_776 = true;
            continue;
          }
          if ($pontocom[$Iponto]["r47_rubric"] == "0045") {
            $salario_esposa = $pontocom[$Iponto]["r47_valor"];
          }
        }
      } else if (strtolower($d08_carnes) == "carazinho") {
        if ($opcao_geral == 1) {
          if ($pontofs[$Iponto]["r10_rubric"] == "0514" && !db_empty($pessoal[$Ipessoal]["r01_cc"])) {
            $calcula_valor_514 = true;
            continue;
          }
        } else {
          if ($pontocom[$Iponto]["r47_rubric"] == "0514" && !db_empty($pessoal[$Ipessoal]["r01_cc"])) {
            $calcula_valor_514 = true;
            continue;
          }
        }
      } else if (strtolower($d08_carnes) == "viamao") {
        if ($opcao_geral == 1) {
          if ($pontofs[$Iponto]["r10_rubric"] == "0275" && $pontofs[$Iponto]["r10_valor"] == 0) {
            $calcula_valor_275 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0131") {
            $calcula_valor_131 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0256") {
            $calcula_valor_256 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0291") {
            $calcula_valor_291 = true;
            continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0334") {
            $calcula_valor_334 = true;
            continue;
          }
        } else {
          if ($pontocom[$Iponto]["r47_rubric"] == "0275" && $pontocom[$Iponto]["r47_valor"] == 0) {
            $calcula_valor_275 = true;
            continue;
          }
        }
      } else if (strtolower($d08_carnes) == "itaqui"  ) {
        
        if ($opcao_geral == 1) {
          if ($pontofs[$Iponto]["r10_rubric"] == "0053") {
            $calcula_valor_053 = true;
            //continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0055") {
            $calcula_valor_055 = true;
            //continue;
          }
          if ($pontofs[$Iponto]["r10_rubric"] == "0067") {
            $calcula_valor_067 = true;
            //continue;
          }
        }
        
      } else if (strtolower($d08_carnes) == "amparo") {
        
        if ($opcao_geral == 1) {
          if ($pontofs[$Iponto]["r10_rubric"] == "0514" && $pontofs[$Iponto]["r10_quant"] > 0 && $pontofs[$Iponto]["r10_valor"] == 0 ) {
            $calcula_valor_514 = true;
            continue;
          }
        } else {
          if ($pontocom[$Iponto]["r47_rubric"] == "0514" && $pontocom[$Iponto]["r47_quant"] > 0 && $pontocom[$Iponto]["r47_valor"] == 0 ) {
            $calcula_valor_514 = true;
            continue;
          }
        }
      }
      if ($opcao_geral == 1 && !db_empty($cfpess[0]["r11_desliq"] )) {

        if (db_at($pontofs[$Iponto]["r10_rubric"],$rubricas_calculos_especiais) > 0 && $pontofs[$Iponto]["r10_valor"] <= 0 ) {
          $rub = $pontofs[$Iponto]["r10_rubric"] ;
          $calcula_xvalor = "calcula_xvalor_".$rub;
          global $$calcula_xvalor;
          $$calcula_xvalor = true;
          continue;
        }
      }
      
      
// Fim --> Rubricas que seram calculadas por ultimo em calculos especificos 

      if ($opcao_geral == 1) {
        
        $condicaoaux  = " and r90_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"]  );
        $condicaoaux .= " and r90_rubric = ".db_sqlformat($pontofs[$Iponto]["r10_rubric"] );
        $dias_maternidade = 30-$dias_pagamento;
        
        // F019 - Numero de dias a pagar no mes
        
        // O calculo do Ponto de salario quando em licença gestante, a quantidade vai ser baseada no que foi lan-
        // cado no ponto fixo
        global $pontofx;
        if ($F019 > 0 && $situacao_funcionario == 5 // Afastado Licenca Gestante
        && db_selectmax("pontofx", "select * from pontofx ".bb_condicaosubpes("r90_" ).$condicaoaux ) ) {
          
          $r14_quant   = $pontofx[0]["r90_quant"];
          if ($dias_do_mes == 31) {
            
            if (('t' == $pontofs[$Iponto]["rh27_propq"] )) {
              $r14_quant   = round(($pontofx[0]["r90_quant"]/30*31), 2);
            }
            
          }
          
          $r14_pd = $pontofs[$Iponto]["rh27_pd"];
          
          if($db_debug == true){ echo "[gerfsal] 12 - chamando a função calc_rubrica() <br>"; }
          $r07_form = calc_rubrica($pontofs[$Iponto]["r10_rubric"],"pontofs","r10","r14",$Iponto,false);
          
          
          if (db_empty($r07_form) || (!db_empty($r07_form) && !db_empty($pontofs[$Iponto]["r10_valor"]))) {
            
            if ($dias_do_mes == 31 && ('t' == $pontofs[$Iponto]["rh27_calcp"] )) {
              $r14_valor = round($pontofx[0]["r90_valor"]+($pontofx[0]["r90_valor"]/30),2);
              if ($db_debug == true) {
                echo "[gerfsal] 19 - r14_valor = $r14_valor  <br>";
              }
            } else {
              $r14_valor = $pontofx[0]["r90_valor"];
              if ($db_debug == true) {
                echo "[gerfsal] 20 - r14_valor = $r14_valor  <br>";
              }  
            }
          } else {
            $cod_erro  = 0;
            $elem_erro =  " ";
            
            $r01_form = '$r07_form  = '.$r07_form.";";
            ob_start();
            eval($r01_form);
            db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontofs[$Iponto]["r10_rubric"]); 
            
            if ($dias_do_mes == 31) {
              if (('t' == $pontofs[$Iponto]["rh27_propq"])) {
                $r14_quant    = round($pontofx[0]["r90_quant"]+($pontofx[0]["r90_quant"]/30),2);
              }
              $r14_valor    = $r07_form * $r14_quant ;
              if ($db_debug == true) { echo "[gerfsal] 21 - r14_valor = $r14_valor  <br>"; }
            } else {
              $r14_valor = $pontofx[0]["r90_quant"] * $r07_form;
              if ($db_debug == true) { echo "[gerfsal] 22 - r14_valor = $r14_valor  <br>"; }
            }
          }
        } else {

          $r14_quant   = $pontofs[$Iponto]["r10_quant"];
          $r14_valor   = $pontofs[$Iponto]["r10_valor"];
          if ($db_debug == true) { echo "[gerfsal] 23 - r14_valor = $r14_valor  <br>"; }
          //        $condicaoaux = " and rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat($pontofs[$Iponto]["r10_rubric"] );
          //        db_selectmax( "rubricas", "select rh27_propi,rh27_rubric,rh27_pd,rh27_calcp,rh27_propq,rh27_formq from rhrubricas ".$condicaoaux );
          $r14_pd = $pontofs[$Iponto]["rh27_pd"];
          
          if($db_debug == true){ echo "[gerfsal] 13 - chamando a função calc_rubrica() <br>"; }

          $r07_form = calc_rubrica($pontofs[$Iponto]["r10_rubric"],"pontofs","r10","r14",$Iponto,false);
          
          //{AQUI} {AQUI}
          //=======================================================================
          //=if($r110_regist == 19 && $pontofs[$Iponto]["r10_rubric"] == "0555"){  =  
          //=                                                                      =        
          //=  print_r($r07_form);                                                 =                                                                    
          //=  echo '</br></br></br>Iponto: ' . $Iponto;                           =                                                                                                                                                                                                                     
          //=                                                                      =                                                                                                                                                                                                                                  
          //=                                                                      =                                                                                             
          //=  die();                                                              =                                                                                                                                                      
          //=}                                                                     =                                                                                                                                                          
          //===================================================================== ==
          
          //echo "<BR> tipo -> $r14_pd formula -> $r07_form";
          
          //if ($pontofs[$Iponto]["r10_rubric"] == "0555") {
          //  // reis
          //  echo "<BR> F010 --> $F010";
          //  echo "<BR> F013 --> $F013";
          //  echo "<BR> r10_rubric --> ".$pontofs[$Iponto]["r10_rubric"];
          //  die();
          //}
          // reis
          
          if (db_empty($r07_form) ||  (!db_empty($r07_form) && !db_empty($pontofs[$Iponto]["r10_valor"]))) {
            //{aqui}
            // if($r110_regist == 19 && $pontofs[$Iponto]["r10_rubric"] == "0555")
            //  die('aqui 1');

            $r14_valor = $pontofs[$Iponto]["r10_valor"];
            if ($db_debug == true) { echo "[gerfsal] 24 - r14_valor = $r14_valor  <br>"; }
          } else {

            
              //{aqui}
              //if($r110_regist == 19 && $pontofs[$Iponto]["r10_rubric"] == "0555")
              //die('aqui 2');

            $cod_erro  = 0;
            $elem_erro =  " ";
            //$r07_form  = operacao($r07_form);
            $r01_form = '$r07_form  = '.$r07_form.";";
            ob_start();
            eval($r01_form);
            db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontofs[$Iponto]["r10_rubric"]); 
            
            $r14_valor = $pontofs[$Iponto]["r10_quant"] * $r07_form;
            if ($db_debug == true) { echo "[gerfsal] 25 - r14_valor = $r14_valor  <br>"; }
            $r14_quant = $pontofs[$Iponto]["r10_quant"];
            
          }
          // r01_propi --> Perc.Inativo
          if (strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) != 'a' && $pessoal[$Ipessoal]["r01_propi"] > 0 // r01_propi --> Perc.Inativo
          && $pessoal[$Ipessoal]["r01_propi"] < 100
          && ('t' == $pontofs[$Iponto]["rh27_propi"]) ) {
            $r14_valor = round($r14_valor * ( $pessoal[$Ipessoal]["r01_propi"] / 100 ),2 ) ;
            if ($db_debug == true) { echo "[gerfsal] 26 - r14_valor = $r14_valor  <br>"; }
          }
        }
        // 4 - Afastado Servico Militar
        // 3 - Afastado Acidente de Trabalho + 15 Dias
        if ($r14_valor > 0 && ( $situacao_funcionario <> 4 || ($situacao_funcionario == 3 and !db_empty($rubrica_acidente))
        || !db_empty($dias_pagamento) ) ) {

// Inicio --> Calcula conforme a formula da quantidade da rubrica, se tiver formula na quantidade

          $quant_formq = " ";
          $verifica = trim($pontofs[$Iponto]["rh27_formq"]);
          //                echo "<BR> formula pela quantidade -> ".$pontofs[$Iponto]["rh27_formq"];
          if (!db_empty($verifica )) {
            
            //              echo "<BR> formula pela quantidade 1 -> ".$pontofs[$Iponto]["rh27_formq"];
            
            if($db_debug == true){ echo "[gerfsal] 14 - chamando a função calc_rubrica() <br>"; }
            $quant_formq = calc_rubrica("formq","pontofs","r10","r14",$Iponto,false,$pontofs[$Iponto]["rh27_formq"],$r14_valor);
            //     echo "<BR> formula 1 --> $quant_formq";
            $cod_erro_  = 0;
            $elem_erro_ =  " ";
            //$quant_formq = operacao( $quant_formq );
            if (trim($quant_formq) != '') {
              $quant_formq = '$quant_formqc = '.$quant_formq.";";
              ob_start();
              eval($quant_formq);
              db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$quant_formq,$pontofs[$Iponto]["r10_rubric"]); 
              $r14_quant = $quant_formqc;
            } else {
              $r14_quant = 0;
            }
            
          }

// Fim --> Calcula conforme a formula da quantidade da rubrica

          $gerou_rubrica_calculo = true;
          
          $matriz1 = array();
          $matriz2 = array();
          
          $matriz1[1] = "r14_regist";
          $matriz1[2] = "r14_rubric";
          $matriz1[3] = "r14_lotac";
          $matriz1[4] = "r14_valor";
          $matriz1[5] = "r14_quant";
          $matriz1[6] = "r14_pd";
          $matriz1[7] = "r14_semest";
          $matriz1[8] = "r14_anousu";
          $matriz1[9] = "r14_mesusu";
          $matriz1[10] = "r14_instit";
          
          $matriz2[1] = $r110_regist;
          $matriz2[2] = $pontofs[$Iponto]["r10_rubric"];
          $matriz2[3] = $r110_lotac;
          $matriz2[4] = round($r14_valor,2);
          $matriz2[5] = round($r14_quant, 2 );
          $matriz2[6] = $r14_pd;
          $matriz2[7] = 0;
          $matriz2[8] = $anousu;
          $matriz2[9] = $mesusu;
          $matriz2[10] = $DB_instit;
          
          //echo "<BR> rubrica 23 -->".$pontofs[$Iponto]["r10_rubric"]."  valor --> $r14_valor quant --> $r14_quant" ;
          // reis
          
          if ($db_debug == true) {
            echo "[gerfsal] 5 - Insert: Gerfsal<br>";
            echo "Dados: <br>";
            echo "r14_regist: ".$matriz2[1]."<br>";
            echo "r14_rubric:".$matriz2[2]."<br>";
            echo "r14_lotac:".$matriz2[3]."<br>";
            echo "r14_valor:".$matriz2[4]."<br>";
            echo "r14_quant:".$matriz2[5]."<br>";
            echo "r14_pd:".$matriz2[6]."<br>";
            echo "r14_semest:".$matriz2[7]."<br>";
            echo "r14_anousu:".$matriz2[8]."<br>";
            echo "r14_mesusu:".$matriz2[9]."<br>";
            echo "r14_instit:".$matriz2[10]."<br>";
            echo "<br>";
          }
          db_insert("gerfsal", $matriz1, $matriz2 );
          
          $r14_quant = 0;
          //if (strtolower($d08_carnes)=="sapiranga") {
          if ( ($pontofs[$Iponto]["r10_rubric"] == "R916") || $pontofs[$Iponto]["r10_rubric"] < "R800" && $r14_pd <> 3 && db_at($pontofs[$Iponto]["r10_rubric"],$rubricas_calc_integral) == 0 ) {
            //echo "<BR>  db_at(".$pontofs[$Iponto]["r10_rubric"].",$rubricas_calc_integral) > 0 ) --> ".db_at($pontofs[$Iponto]["r10_rubric"],$rubricas_calc_integral);
            if ($r14_pd == 2 ) {
              $tot_desc += round($r14_valor,2);
               if ($db_debug == true) {
                echo "[gerfsal] 12 - tot_desc: $tot_desc<br>";
              }
            } else {
              $tot_prov += round($r14_valor,2);
            }

          }
          //} else {
          //  if ($r14_pd == 1 && $pontofs[$Iponto]["r10_rubric"] < "R800") {
          //    $tot_prov += round($r14_valor,2);
          //    //       echo "<BR> 1 passou aqui !!! tot_prov --> $tot_prov";
          //  } else if ($pontofs[$Iponto]["r10_rubric"] < "R800" ) {
          //    $tot_desc += round($r14_valor,2);
          //    //       echo "<BR> 2 passou aqui !!! tot_desc --> $tot_desc";
          //  }
          //}
        }
      } else {
        
        
        $r14_quant   = $pontocom[$Iponto]["r47_quant"];
        $r14_valor   = $pontocom[$Iponto]["r47_valor"];
        if ($db_debug == true) { echo "[gerfsal] 27 - r14_valor = $r14_valor  <br>"; }
        //     $condicaoaux = " and rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat($pontocom[$Iponto]["r47_rubric"] );
        //     db_selectmax( "rubricas", "select * from rhrubricas ".$condicaoaux );
        $r14_pd = $pontocom[$Iponto]["rh27_pd"];
        
        if($db_debug){ echo "[gerfsal] 15 - chamando a função calc_rubrica() <br>"; }
        $r07_form = calc_rubrica($pontocom[$Iponto]["r47_rubric"],"pontocom","r47","r48",$Iponto,false);
        if (db_empty($r07_form) ||  (!db_empty($r07_form) && !db_empty($pontocom[$Iponto]["r47_valor"]))) {
          $r14_valor = $pontocom[$Iponto]["r47_valor"];
          if ($db_debug == true) { echo "[gerfsal] 28 - r14_valor = $r14_valor  <br>"; }
        } else {
          $cod_erro  = 0;
          $elem_erro =  " ";
          //$r07_form  = operacao($r07_form);
          
          $r07_form  = '$r07_form = '.$r07_form.";";
          ob_start();
          eval($r07_form);
          db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$r07_form,$pontofcom[$Iponto]["r47_rubric"]); 
          
          $r14_valor = round($pontocom[$Iponto]["r47_quant"] * $r07_form,2);
          if ($db_debug == true) { echo "[gerfsal] 29 - r14_valor = $r14_valor  <br>"; }
          $r14_quant = $pontocom[$Iponto]["r47_quant"];
        }
        // r01_propi --> Perc.Inativo
        if (strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) != 'a' && $pessoal[$Ipessoal]["r01_propi"] > 0 // r01_propi --> Perc.Inativo
        && $pessoal[$Ipessoal]["r01_propi"] < 100
        && ('t' == $pontocom[$Iponto]["rh27_propi"]) ) {
          $r14_valor = round($r14_valor * ( $pessoal[$Ipessoal]["r01_propi"] / 100 ),2 ) ;
          if ($db_debug == true) { echo "[gerfsal] 30 - r14_valor = $r14_valor  <br>"; }
        }
        
        
        if ($r14_valor > 0) {
          
          $quant_formq = " ";
          if (!db_empty($pontocom[$Iponto]["rh27_formq"] )) {
            
            if($db_debug == true){ echo "[gerfsal] 16 - chamando a função calc_rubrica() <br>"; }
            $quant_formq = calc_rubrica("formq","pontocom","r47","r48",$Iponto,false,$pontocom[$Iponto]["rh27_formq"],$r14_valor);
            //echo "<BR> formula 1 --> $quant_formq";
            $cod_erro_  = 0;
            $elem_erro_ =  " ";
            //$quant_formq = operacao( $quant_formq );
            $quant_formq = '$quant_formq = '.$quant_formq.";";
            ob_start();
            eval($quant_formq);
            db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$quant_formq,$pontofcom[$Iponto]["r47_rubric"]); 
            
            $r14_quant = $quant_formq;
            
          }
          
          $matriz1 = array();
          $matriz2 = array();
          
          $matriz1[1] = "r48_regist";
          $matriz1[2] = "r48_rubric";
          $matriz1[3] = "r48_lotac";
          $matriz1[4] = "r48_valor";
          $matriz1[5] = "r48_quant";
          $matriz1[6] = "r48_pd";
          $matriz1[7] = "r48_semest";
          $matriz1[8] = "r48_anousu";
          $matriz1[9] = "r48_mesusu";
          $matriz1[10] = "r48_instit";
          
          $matriz2[1] = $r110_regist;
          $matriz2[2] = $pontocom[$Iponto]["r47_rubric"];
          $matriz2[3] = $r110_lotac;
          $matriz2[4] = round($r14_valor,2);
          $matriz2[5] = round($r14_quant,2);
          $matriz2[6] = $r14_pd;
          $matriz2[7] = 0;
          $matriz2[8] = $anousu;
          $matriz2[9] = $mesusu;
          $matriz2[10] = $DB_instit;
          
          db_insert("gerfcom", $matriz1, $matriz2 );
          $r14_quant = 0;
          if ($r14_pd == 1 && $pontocom[$Iponto]["r47_rubric"] < "R800") {
            $tot_prov += round($r14_valor,2);
          } else if ($pontocom[$Iponto]["r47_rubric"] < "R800" ) {
            $tot_desc += round($r14_valor,2);
            if ($db_debug == true) {
              echo "[gerfsal] 13 - tot_desc: $tot_desc<br>";
            }
          }
        }
      }
    }

    // fim do loop do Ponto
    
    // Agrega as rubricas que entram no calculo do salario maternidade
    // Para somar na rubrica r33_rubmat as rubricas tem que estar com o flag calcula Proporcionalidade de afastamento 
    // setado para sim ela soma a r33_rubmat e exclui a rubricas setadas do ponto gerado para nao aparecerem no ponto 
    // individualmente
    //
    
    $valor_salario_maternidade = 0;
    if ($situacao_funcionario == 5) {
      // Afastado Licenca Gestante
      $condicaoaux = " and substr(r14_rubric,1,1) <> 'R' and rh27_calcp = 't' and r14_regist = ".db_sqlformat($r110_regist );
      $condicaoaux1 = " and substr(r53_rubric,1,1) <> 'R' and rh27_calcp = 't' and r53_regist = ".db_sqlformat($r110_regist );
      if (db_selectmax("transacao", "select gerffx.*,rh27_rubric,rh27_pd,rh27_calcp,rh27_propq from gerffx inner join rhrubricas on r53_instit = rh27_instit and r53_rubric = rh27_rubric ".bb_condicaosubpes("r53_").$condicaoaux1 )) {
        for ($Itransacao=0; $Itransacao< count($transacao); $Itransacao++) {
          //       echo "<BR> r14_rubric --> ".$transacao[$Itransacao]["r14_rubric"];
          $salario = $transacao[$Itransacao]["r53_valor"];
          $qtd_salario = $transacao[$Itransacao]["r53_quant"];
          $dividir_por = 30;
          if ($db_debug == true) { echo "[gerfsal] Salario:".$salario." - ".$dias_pagamento."<br>"; }
          $valor_salario_fam =  round(( $salario / 30 ) * ( 30 - $dias_pagamento ),2);
          
          if ( $transacao[$Itransacao]["rh27_pd"] == 2 ) {
            $valor_salario_maternidade -= $valor_salario_fam;
          } else {
            $valor_salario_maternidade += $valor_salario_fam;
          }
                    
          if ($db_debug == true) { echo "[gerfsal] >>".$inssirf_[0]["r33_rubmat"]." - ".$rubricas[0]["rh27_pd"]." - $valor_salario_fam<br>"; }
          
          if (db_empty($inssirf_[0]["r33_rubmat"])) {
            if ( $rubricas[0]["rh27_pd"] == 2 ) {
              $tot_desc -= $valor_salario_fam;
              if ($db_debug == true) {  echo "[gerfsal] 14 - tot_desc: $tot_desc<br>"; }
            } else {
              $tot_prov -= $valor_salario_fam;
            }
          }
          
          $valor_ferias_ = 0;
          // F019 - Numero de dias a pagar no mes
          if ($F019 > 0 && 'f' == $cadferia[0]["r30_paga13"] ) {
            $avaliar_com = $F019 ;
            $valor_ferias_ = round($transacao[$Itransacao]["r53_valor"] / $dividir_por * $avaliar_com ,2);
          }
          $rubrica_salario = $transacao[$Itransacao]["r53_rubric"];
          $condicaoaux = " and r14_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          $condicaoaux .= " and r14_rubric = ".db_sqlformat($rubrica_salario );
          if ($dias_pagamento > 0) {
            
            if (round(( $transacao[$Itransacao]["r53_valor"] - ($valor_salario_fam)
            -(strtolower($d08_carnes)=="riogrande"?0:$valor_ferias_ )),2) <= 0 ) {
               if ($transacao[$Itransacao]["rh27_pd"] == 2) {
                $tot_desc -= ($valor_ferias_);
                if ($db_debug == true) { echo "[gerfsal] 15 - tot_desc: $tot_desc<br>"; }
              } else {
                $tot_prov -= ($valor_ferias_);
              }
              db_delete("gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
            } else {
              $valor = ( $transacao[$Itransacao]["r53_valor"] - ($valor_salario_fam)
              - (strtolower($d08_carnes)=="riogrande"? 0:$valor_ferias_ ));
              
              $matriz1 = array();
              $matriz2 = array();
              $matriz1[1] = "r14_valor";
              $matriz2[1] = round($valor, 2 );
              
              //echo "<BR> rubrica 24 -->".$pontofs[$Iponto]["r10_rubric"]."  valor --> $valor";
              // reis
              db_update("gerfsal",$matriz1,$matriz2,bb_condicaosubpes("r14_").$condicaoaux);
              
              if (!db_empty($transacao[$Itransacao]["r53_quant"]) && ('t' == $transacao[$Itransacao]["rh27_propq"])) {
                // F019 - Numero de dias a pagar no mes
                if ($F019 > 0 &&  'f' == $cadferia[0]["r30_paga13"] ) {
                  $quantidade = ( $qtd_salario/30) * ( $dividir_por - $dias_maternidade - (strtolower($d08_carnes)=="riogrande"? 0: $F019 ) );
                } else {
                  $quantidade = ( $qtd_salario/30) * ( 30 - $dias_maternidade );
                }
                $matriz1[1] = "r14_quant";
                $matriz2[1] = round($quantidade,2);
                db_update("gerfsal",$matriz1,$matriz2,bb_condicaosubpes("r14_").$condicaoaux);
              }
            }
          }
        }
      }
      if (!db_empty($valor_salario_maternidade)
      && !db_empty($pessoal[$Ipessoal]["r01_tbprev"])
      && !db_empty($inssirf_[0]["r33_rubmat"])) {
        //echo "<BR> r110_regist --> $r110_regist valor_salario_maternidade --> $valor_salario_maternidade r33_rubmat --> ".$inssirf_[0]["r33_rubmat"]. " dias_pagamento --> $dias_pagamento";
        ;
        // reis
        $matriz1 = array();
        $matriz2 = array();
        
        $matriz1[1] = "r14_regist";
        $matriz1[2] = "r14_rubric";
        $matriz1[3] = "r14_lotac";
        $matriz1[4] = "r14_valor";
        $matriz1[5] = "r14_quant";
        $matriz1[6] = "r14_pd";
        $matriz1[7] = "r14_semest";
        $matriz1[8] = "r14_anousu";
        $matriz1[9] = "r14_mesusu";
        $matriz1[10] = "r14_instit";
        
        $matriz2[1] = $r110_regist;
        $matriz2[2] = $inssirf_[0]["r33_rubmat"];
        $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
        $matriz2[4] = round($valor_salario_maternidade,2);
        $matriz2[5] = (30 - $dias_pagamento);
        $matriz2[6] = 1;
        $matriz2[7] = 0;
        $matriz2[8] = $anousu;
        $matriz2[9] = $mesusu;
        $matriz2[10] = $DB_instit;
        
          if ($db_debug == true) {
            echo "[gerfsal] 6 - Insert: Gerfsal<br>";
            echo "Dados: <br>";
            echo "r14_regist: ".$matriz2[1]."<br>";
            echo "r14_rubric:".$matriz2[2]."<br>";
            echo "r14_lotac:".$matriz2[3]."<br>";
            echo "r14_valor:".$matriz2[4]."<br>";
            echo "r14_quant:".$matriz2[5]."<br>";
            echo "r14_pd:".$matriz2[6]."<br>";
            echo "r14_semest:".$matriz2[7]."<br>";
            echo "r14_anousu:".$matriz2[8]."<br>";
            echo "r14_mesusu:".$matriz2[9]."<br>";
            echo "r14_instit:".$matriz2[10]."<br>";
            echo "<br>";
          }
        db_insert("gerfsal", $matriz1, $matriz2 );
      }
    }

// Inicio --> Proporcionaliza o Ponto conforme os dias trabalhados (licenca saude ou acidente), ha so entra no ponto 
// as rubricas marcadas para afastamento proporcional

    $vlr_sal_saude_ou_acidente = 0;
    if (($situacao_funcionario == 6  && !db_empty($rubrica_licenca_saude ) ) // Afastado Doenca + 15 Dias
    ||( $situacao_funcionario ==  3  && !db_empty($rubrica_acidente )) ) {
      $xvalor_salario = 0;
      $xvalor_ferias = 0;
      $condicaoaux  = " and rh27_calcp = 't' and r14_regist = ".db_sqlformat($r110_regist );
      $condicaoaux1  = " and rh27_calcp = 't' and r53_regist = ".db_sqlformat($r110_regist );
      if ($pessoal[$Ipessoal]["r01_tbprev"] != $cfpess[0]["r11_tbprev"] ) {
        $condicaoaux  = " and rh27_calcp = 't' and rh27_pd = 1 and r14_regist = ".db_sqlformat($r110_regist );
        $condicaoaux1 = " and rh27_calcp = 't' and rh27_pd = 1 and r53_regist = ".db_sqlformat($r110_regist );
      }
      global $transacao;
      if (db_selectmax("transacao", "select gerffx.*,rh27_rubric,rh27_pd,rh27_calcp,rh27_propq from gerffx inner join rhrubricas on r53_instit = rh27_instit and r53_rubric = rh27_rubric ".bb_condicaosubpes("r53_" ).$condicaoaux1)) {
        for ($Itransacao=0; $Itransacao< count($transacao); $Itransacao++) {
          if (substr("#".$transacao[$Itransacao]["r53_rubric"],1,1) != "R" ) {
            $xvalor_salario =  db_val(db_str(( $transacao[$Itransacao]["r53_valor"] / 30 ) *  ( 30 - $dias_pagamento ),15,2));
            if ($transacao[$Itransacao]["rh27_pd"] == 2 ) {
              $vlr_sal_saude_ou_acidente -= $xvalor_salario;
            } else {
              $vlr_sal_saude_ou_acidente += $xvalor_salario;
            }
            // F019 - Numero de dias a pagar no mes
            if ($F019> 0 && 'f' == $cadferia[0]["r30_paga13"] ) {
              $xvalor_ferias = $transacao[$Itransacao]["r53_valor"] /30 * $F019;
            } else {
              $xvalor_ferias = 0;
            }
            $rubrica_salario = $transacao[$Itransacao]["r53_rubric"];
            $condicaoaux = " and r14_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and r14_rubric = ".db_sqlformat($rubrica_salario );
            if ($dias_pagamento == 0) {
              db_delete("gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
            } else {
              if ( $transacao[$Itransacao]["rh27_pd"] == 2  && $pessoal[$Ipessoal]["r01_tbprev"] != $cfpess[0]["r11_tbprev"] ) {
                continue;
              }
              if((30 -(30-$dias_pagamento) - $F019) > 0){ 
                $valor =  $transacao[$Itransacao]["r53_valor"] - $xvalor_salario - $xvalor_ferias;
                $matriz1 = array();
                $matriz2 = array();
                $matriz1[1] = "r14_valor";
                $matriz2[1] = round($valor,2 );
                if (!db_empty($transacao[$Itransacao]["r53_quant"]) && ('t' == $transacao[$Itransacao]["rh27_propq"]) ) {
                  // F019 - Numero de dias a pagar no mes
                  $quantidade = ( $transacao[$Itransacao]["r53_quant"]/30) * (  30 - (30-$dias_pagamento) - $F019 );
                  $matriz1[2] = "r14_quant";
                  $matriz2[2] = round($quantidade,2);
                }
                db_update("gerfsal",$matriz1,$matriz2,bb_condicaosubpes("r14_").$condicaoaux);
              }else{
              db_delete("gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
              } 
            }
          }
        }
      }
      if (!db_empty($vlr_sal_saude_ou_acidente)) {
        $matriz1 = array();
        $matriz2 = array();
        
        $matriz1[1] = "r14_regist";
        $matriz1[2] = "r14_rubric";
        $matriz1[3] = "r14_lotac";
        $matriz1[4] = "r14_valor";
        $matriz1[5] = "r14_quant";
        $matriz1[6] = "r14_pd";
        $matriz1[7] = "r14_semest";
        $matriz1[8] = "r14_anousu";
        $matriz1[9] = "r14_mesusu";
        $matriz1[10] = "r14_instit";
        
        $matriz2[1] = $r110_regist;
        $matriz2[2] = ($situacao_funcionario == 6?$rubrica_licenca_saude:$rubrica_acidente);
        $matriz2[3] = $pessoal[$Ipessoal]["r01_lotac"];
        $matriz2[4] = round($vlr_sal_saude_ou_acidente,2);
        $matriz2[5] = 30 - $dias_pagamento;
        $matriz2[6] = 1;
        $matriz2[7] = 0;
        $matriz2[8] = $anousu;
        $matriz2[9] = $mesusu;
        $matriz2[10] = $DB_instit;
        
        //echo "<BR> rubrica 26 -->".($situacao_funcionario == 6?$rubrica_licenca_saude:$rubrica_acidente)."  valor --> $vlr_sal_saude_ou_acidente";
        // reis
        if ($db_debug == true) {
          echo "[gerfsal] 7 - Insert: Gerfsal<br>";
          echo "Dados: <br>";
          echo "r14_regist: ".$matriz2[1]."<br>";
          echo "r14_rubric:".$matriz2[2]."<br>";
          echo "r14_lotac:".$matriz2[3]."<br>";
          echo "r14_valor:".$matriz2[4]."<br>";
          echo "r14_quant:".$matriz2[5]."<br>";
          echo "r14_pd:".$matriz2[6]."<br>";
          echo "r14_semest:".$matriz2[7]."<br>";
          echo "r14_anousu:".$matriz2[8]."<br>";
          echo "r14_mesusu:".$matriz2[9]."<br>";
          echo "r14_instit:".$matriz2[10]."<br>";
          echo "<br>";
        }
        db_insert("gerfsal", $matriz1, $matriz2 );
        
      }
    }

// Fim --> Proporcionaliza o Ponto conforme os dias trabalhados (licenca saude ou acidente ) 

    if (strtolower($d08_carnes) == "viamao" && $opcao_geral == 1 ) {
      // D912 VALOR DO SALARIO MINIMO
      if ($tot_prov < $D912) {
        viamao_equiparacao_salarial();
      }
    }
    
    
    $r14_valor = 0;
    if ($db_debug == true) { echo "[gerfsal] 31 - r14_valor = $r14_valor  <br>"; }
    if ($opcao_geral == 1 ) {
      carrega_r9xx("pontofs","r10","r14",$recno_110,$opcao_tipo);
    } else {
      carrega_r9xx("pontocom","r47","r48",$recno_110,$opcao_tipo);
    }


    // 4 - Afastado Servico Militar
    // 3 - Afastado Acidente de Trabalho + 15 Dias
    
    if (( $situacao_funcionario != 4 || ($situacao_funcionario == 3 and !db_empty($rubrica_acidente))
    || !db_empty($dias_pagamento) ) ) {
      if ($opcao_geral == 1 ) {

        if ($db_debug == true) { echo "[gerfsal] Inicio --> Grava no Gerfsal o arredondamento do mes anterior<br>"; }   

        if (!db_empty($pessoal[$Ipessoal]["r01_arredn"])) {
          $tot_desc += $pessoal[$Ipessoal]["r01_arredn"];
          if ($db_debug == true) { echo "[gerfsal] 16 - tot_desc: $tot_desc<br>"; }
          $gerou_rubrica_calculo = true;
          
          $matriz1 = array();
          $matriz2 = array();
          
          $matriz1[1] = "r14_regist";
          $matriz1[2] = "r14_rubric";
          $matriz1[3] = "r14_lotac";
          $matriz1[4] = "r14_valor";
          $matriz1[5] = "r14_quant";
          $matriz1[6] = "r14_pd";
          $matriz1[7] = "r14_semest";
          $matriz1[8] = "r14_anousu";
          $matriz1[9] = "r14_mesusu";
          $matriz1[10] = "r14_instit";
          
          $matriz2[1] = $r110_regist;
          $matriz2[2] = $pessoal[$Ipessoal]["r01_rubric"];
          $matriz2[3] = $r110_lotac;
          $matriz2[4] = $pessoal[$Ipessoal]["r01_arredn"];
          $matriz2[5] = 0;
          $matriz2[6] = 2;
          $matriz2[7] = 0;
          $matriz2[8] = $anousu;
          $matriz2[9] = $mesusu;
          $matriz2[10] = $DB_instit;
          
          $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist );
          $condicaoaux .= " and r14_pd = 2 ";
          $condicaoaux .= " and r14_rubric = ".db_sqlformat($pessoal[$Ipessoal]["r01_rubric"] );
          
          if (db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
            db_update("gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_" ).$condicaoaux );
          } else {
            if ($db_debug == true) {
              echo "[gerfsal] 8 - Insert: Gerfsal<br>";
              echo "Dados: <br>";
              echo "r14_regist: ".$matriz2[1]."<br>";
              echo "r14_rubric:".$matriz2[2]."<br>";
              echo "r14_lotac:".$matriz2[3]."<br>";
              echo "r14_valor:".$matriz2[4]."<br>";
              echo "r14_quant:".$matriz2[5]."<br>";
              echo "r14_pd:".$matriz2[6]."<br>";
              echo "r14_semest:".$matriz2[7]."<br>";
              echo "r14_anousu:".$matriz2[8]."<br>";
              echo "r14_mesusu:".$matriz2[9]."<br>";
              echo "r14_instit:".$matriz2[10]."<br>";
              echo "<br>";
            }           
            db_insert("gerfsal", $matriz1, $matriz2 );
          }
        }


        if ($db_debug == true) { 
          echo "[gerfsal] Fim --> Grava no Gerfsal ou no Gerfcom o arredondamento do mes anterior<br>";   
          echo "[gerfsal] Inicio --> Grava no Gerfsal o adiantamento do salario como desconto<br>";
        }

        $condicaoaux  = " and r22_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
        $condicaoaux .= " and r22_pd    != 3 ";
        global $gerfadi;
        if (db_selectmax("gerfadi", "select * from gerfadi ".bb_condicaosubpes("r22_" ).$condicaoaux )) {
          for ($Igerfadi=0; $Igerfadi < count($gerfadi); $Igerfadi++) {
            $tot_desc += $gerfadi[$Igerfadi]["r22_valor"];
            if ($db_debug == true) { echo "[gerfsal] 17 - tot_desc: $tot_desc<br>"; }
            $gerou_rubrica_calculo = true;
            $matriz1 = array();
            $matriz2 = array();
            
            $matriz1[1] = "r14_regist";
            $matriz1[2] = "r14_rubric";
            $matriz1[3] = "r14_lotac";
            $matriz1[4] = "r14_valor";
            $matriz1[5] = "r14_quant";
            $matriz1[6] = "r14_pd";
            $matriz1[7] = "r14_semest";
            $matriz1[8] = "r14_anousu";
            $matriz1[9] = "r14_mesusu";
            $matriz1[10] = "r14_instit";
            
            $matriz2[1] = $r110_regist;
            $matriz2[2] = $gerfadi[$Igerfadi]["r22_rubric"];
            $matriz2[3] = $r110_lotac;
            $matriz2[4] = $gerfadi[$Igerfadi]["r22_valor"];
            $matriz2[5] = $gerfadi[$Igerfadi]["r22_quant"];
            $matriz2[6] = 2;
            $matriz2[7] = 0;
            $matriz2[8] = $anousu;
            $matriz2[9] = $mesusu;
            $matriz2[10] = $DB_instit;
            
            //echo "<BR> rubrica 27 -->".$pontofs[$Iponto]["r10_rubric"]."  valor --> ".$gerfadi[$Igerfadi]["r22_valor"];
            // reis
            if ($db_debug == true) {
              echo "[gerfsal] 9 - Insert: Gerfsal<br>";
              echo "Dados: <br>";
              echo "r14_regist: ".$matriz2[1]."<br>";
              echo "r14_rubric:".$matriz2[2]."<br>";
              echo "r14_lotac:".$matriz2[3]."<br>";
              echo "r14_valor:".$matriz2[4]."<br>";
              echo "r14_quant:".$matriz2[5]."<br>";
              echo "r14_pd:".$matriz2[6]."<br>";
              echo "r14_semest:".$matriz2[7]."<br>";
              echo "r14_anousu:".$matriz2[8]."<br>";
              echo "r14_mesusu:".$matriz2[9]."<br>";
              echo "r14_instit:".$matriz2[10]."<br>";
              echo "<br>";
            }            
            db_insert("gerfsal", $matriz1, $matriz2 );
            
          }
        }

       if ($db_debug == true) { echo "[gerfsal] Fim --> Grava no Gerfsal o adiantamento do salario como desconto<br>"; }

      }
      
      if ($db_debug == true) {
        echo "[gerfsal] Total de desconto: $r14_valor <br>";
        echo "[gerfsal] Chamou a função de calculos_especificos<br><br>"; 
      }
      calculos_especificos($pessoal[$Ipessoal]["r01_regist"],$pessoal[$Ipessoal]["r01_lotac"]);
      
      // echo "<BR><BR> saiu do calculos_especificos";
      if (strtolower($d08_carnes) == "riogrande") {
        calculos_especificos_riogrande();
      } else if (($d08_carnes) == "sapiranga" ) {
        calculos_especificos_sapiranga($opcao_geral);
      } else if (($d08_carnes) == "bage" ) {
        calculos_especificos_bage($opcao_geral);
        //echo "<BR><BR> sai  calculos_especificos bage";
      } else if (strtolower($d08_carnes) == "carazinho") {
        calculos_especificos_carazinho($opcao_geral,$pessoal[$Ipessoal]["r01_regist"],$pessoal[$Ipessoal]["r01_lotac"]);
      } else if (strtolower($d08_carnes) == "amparo") {
        calculos_especificos_amparo();
      } else if (strtolower($d08_carnes) == "viamao") {
        calculos_especificos_viamao();
        viamao();
      } else if (strtolower($d08_carnes) == "itaqui") {
        calculos_especificos_itaqui($opcao_geral,$pessoal[$Ipessoal]["r01_regist"],$pessoal[$Ipessoal]["r01_lotac"]);
      } else {
        if ($db_debug == true) { echo "[gerfsal] 5 - calcula R928 - Total de desconto: $tot_desc Loop $Ipessoal<br>"; }
        calcula_r928($pessoal[$Ipessoal]["r01_regist"],$pessoal[$Ipessoal]["r01_lotac"],$opcao_geral);
      }
    }


// Inicio - Caso a licenca maternidade for o mes inteiro o ponto calculado so tera a rubrica maternidade lancada
//          mais as rubricas nao proporcionalizar no afastamento , entao as outras rubricas marcadas
//          para proporcionaliza no afastamento seram apagadas.

    if ($situacao_funcionario == 5 && $dias_pagamento == 0) {
      
      $condicaoaux  = " and rh27_calcp = 't' and r14_regist = ".db_sqlformat($r110_regist );
      $condicaoaux .= " and r14_rubric < 'R900' ";
      $condicaoaux .= " and r14_rubric != ".db_sqlformat($inssirf_[0]["r33_rubmat"] );
      global $transacao;
      if (db_selectmax("transacao", "select r14_regist,
                                            r14_rubric, 
                                            rh27_calcp 
                                       from gerfsal inner join rhrubricas on r14_instit = rh27_instit 
                                                                         and r14_rubric = rh27_rubric ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
        for ($Itrans=0; $Itrans<count($transacao); $Itrans++) {
          $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist );
          $condicaoaux .= " and r14_rubric = ".db_sqlformat($transacao[$Itrans]["r14_rubric"] );
          db_delete("gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
        }
      }
    } else if ($situacao_funcionario == 3 && db_empty($rubrica_acidente) && $dias_pagamento == 0 ) {
      
      $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist );
      $condicaoaux .= " and r14_rubric not in ('R991', 'R929')";
      db_delete("gerfsal", bb_condicaosubpes("r14_").$condicaoaux );
    
    
      $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist )." and r14_rubric != 'R928'";
      if (db_selectmax("transacao", "select sum(case when r14_pd = 1 then r14_valor else 0 end ) as tot_prov,
                                            sum(case when r14_pd = 2 then r14_valor else 0 end ) as tot_desc
                                       from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
          $tot_prov = $transacao[0]["tot_prov"];
          $tot_desc = $transacao[0]["tot_desc"];

          calcula_r928($pessoal[$Ipessoal]["r01_regist"],$pessoal[$Ipessoal]["r01_lotac"],$opcao_geral);

      }
      
    }
         
// Fim - do caso licenca maternidade for o mes inteiro.

    if ($ajusta ) {
      // ver estas linhas abaixo
      global $pes_prev;
      $pes_prev = array();
      grava_ajuste_previdencia();
     // grava_ajuste_irrf();
      if(count($pes_prev) == 0){
         grava_ajuste_irrf($pessoal[$Ipessoal]["r01_numcgm"],$r110_regist,strtolower($pessoal[$Ipessoal]["r01_tpvinc"]));
      }
    }
}

  if ($db_debug == "true"){
    echo "[gerfsal] FIM DO PROCESSAMENTO DA FUNÇÃO gerfsal()...";
  }
}

/// fim da funcao gerfsal ///
/// ajusta_previdencia ///
function deleta_ajustes_calculogeral($tipo){

//  $condicaoaux = " and upper(r61_folha) = ".db_sqlformat(strtoupper($tipo));
  $retornar = pg_query("delete 
                        from ajusteir
                       using rhpessoalmov 
                       where rh02_anousu = r61_anousu 
                         and rh02_mesusu = r61_mesusu 
                         and rh02_regist = r61_regist 
                         and r61_anousu = ".db_anofolha()." 
                         and r61_mesusu = ".db_mesfolha()."
                         and upper(r61_folha) = ".db_sqlformat(strtoupper($tipo))." 
                         and rh02_instit = ".db_getsession('DB_instit')); 

  $retornar = pg_query("delete 
                        from previden
                       using rhpessoalmov 
                       where rh02_anousu = r60_anousu 
                         and rh02_mesusu = r60_mesusu 
                         and rh02_regist = r60_regist 
                         and r60_anousu = ".db_anofolha()." 
                         and r60_mesusu = ".db_mesfolha()."
                         and upper(r60_folha) = ".db_sqlformat(strtoupper($tipo))." 
                         and rh02_instit = ".db_getsession('DB_instit')); 

}

function ajusta_previdencia($arquivo, $rubrica_base, $sequencia, $sigla_ajuste)
{
  
  
  global $previden,$inssirf,$Iinssirf,$previden_,$campos_pessoal,$opcao_geral,$d08_carnes,$subpes,$perc_inss;
  global $$arquivo,$cfpess, $pessoal, $db_debug;
  global $anousu, $mesusu, $DB_instit, $F023,$F019;
  
  $matriz1 = array();
  $matriz2 = array();
  $matriz1[1] = "r60_ajuste";
  $matriz2[1] = 'f';
  
  db_update("previden", $matriz1, $matriz2, bb_condicaosubpes("r60_"));
  
  //echo "<BR><BR> entrou funcao ajusta_previdencia() : sequencia --> $sequencia"; // reis
  if (db_selectmax("previden_", "select * from previden ".bb_condicaosubpes("r60_") ) ) {
    
    // Todos que foram alterados vao sofrer ajuste
    $condicaoaux  = " and r60_altera = 't' and r60_rubric = ".db_sqlformat($rubrica_base );
    if($arquivo == 'gerfsal' ){
       $condicaoaux  .= " and r60_folha = 'S'";
    }elseif($arquivo == 'gerfcom' ){
       $condicaoaux  .= " and r60_folha = 'C'";
    }   
    db_selectmax("previden_", "select * from previden ".bb_condicaosubpes("r60_").$condicaoaux );
    for ($Ipreviden=0; $Ipreviden<count($previden_); $Ipreviden++) {
      //echo "<BR> rubrica 35.1.1 numcgm -->".$previden_[$Ipreviden]["r60_numcgm"]."  rubrica baser --> $rubrica_base  r60_altera --> ".$previden_[$Ipreviden]["r60_altera"];  // reis
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
    //db_criatabela(pg_query("select * from previden ".bb_condicaosubpes("r60_" ).$condicaoaux ));
    db_selectmax("previdencia_", "select * from previden ".bb_condicaosubpes("r60_" ).$condicaoaux );
    for ($Ipreviden=0; $Ipreviden<count($previdencia_); $Ipreviden++) {
      $numcgm = $previdencia_[$Ipreviden]["r60_numcgm"];
      $tbprev = $previdencia_[$Ipreviden]["r60_tbprev"];
      $registro = $previdencia_[$Ipreviden]["r60_regist"];
      //echo "<BR> rubrica 35.2104 -->$numcgm  r60_regist --> $registro"; // reis

      $soma_base_teto = 0;
      if($rubrica_base == 'R985' && $arquivo == 'gerfsal' ){
        $condicaoaux  = " and r60_rubric = 'R987'";
        $condicaoaux .= " and r60_numcgm = ".db_sqlformat($numcgm );
        global $transacao1;
        if( db_selectmax("transacao1", "select sum(r60_basef) as soma_basef from previden ".bb_condicaosubpes("r60_" ).$condicaoaux )){
            $soma_base_teto = $transacao1[0]["soma_basef"];
        }
      }
      //echo "<BR> soma_base_teto --> $soma_base_teto";    

      $soma_base = 0;
      $soma_base_F = 0;
      $soma_base_D = 0;
      
      global $pessoal_2,$pessoal_3;
      $condicaoaux = " and rh01_numcgm = ".db_sqlformat($previdencia_[$Ipreviden]["r60_numcgm"]);
      //$condicaoaux.= " and rh02_tbprev = {$cfpess[0]["r11_tbprev"]}";
      
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
      
      $soma_base_D = $soma_base - $soma_base_F;

      $mat_r60_numcgm = array();
      $mat_r60_tbprev = array();
      $mat_r60_rubric = array();
      $mat_r60_regist = array();
      $mat_r60_folha  = array(); 
      $mat_r60_novods = array();
      $mat_r60_novodf = array();
      $mat_r60_dif    = array();
      $mat_r60_novop  = array();
      $nro = 0;

      $soma_base1 = 0;
      $desc_prev_ext = 0;
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

      $atingiu_o_teto_tbprev = false;
      if($arquivo == 'gerffer' ){
        //echo "<BR> soma_base_F --> $soma_base_F";
        $perc_inss = 0;
        if($soma_base_F != 0 ){
           $valor_desc_total_F = teto_tabprev($soma_base_F, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
           if($valor_desc_total_F <= 0 ){
              $valor_desc_total_F = calc_tabprev($soma_base_F, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
           }
           $valor_a_ratear_F = $valor_desc_total_F - $desc_prev_ext ;
           $perc_inss_F = $perc_inss;
        }

        //echo "<BR> soma_base_D --> $soma_base_D";
        $perc_inss = 0;
        if($soma_base_D != 0 ){
           $valor_desc_total_D = teto_tabprev($soma_base_D, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
           if($valor_desc_total_D <= 0 ){
              $valor_desc_total_D = calc_tabprev($soma_base_D, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
           }
           $valor_a_ratear_D = $valor_desc_total_D ;
           $perc_inss_D = $perc_inss;
        }
      }else{
        //echo "<BR>   if($rubrica_base = 'R985' && $arquivo == 'gerfsal' && isset($soma_base_teto) && $soma_base_teto > 0){";
        if($rubrica_base == 'R985' && $arquivo == 'gerfsal' && isset($soma_base_teto) && $soma_base_teto > 0){
           $valor_desconto_total = teto_tabprev($soma_base_teto, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
           //echo "<BR> $valor_desconto_total = teto_tabprev($soma_base_teto,". db_str($tbprev+2,1).",".$pessoal_2[0]["r01_tpcont"].")";
           if($valor_desconto_total > 0 ){
             $atingiu_o_teto_tbprev = true;
           }
        }
        $valor_desconto_total = calc_tabprev($soma_base1, db_str($tbprev+2,1),$pessoal_2[0]["r01_tpcont"]);
        //echo "<BR> valor_desconto_total --> $valor_desconto_total --> soma_base1 --> $soma_base1";
        $valor_a_ratear = $valor_desconto_total - $desc_prev_ext ;
      }

      if($atingiu_o_teto_ext == true){
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
        
        $matriz1 = array();
        $matriz2 = array();
        $matriz1[1] = $sigla_ajuste."valor";
        $matriz1[2] = $sigla_ajuste."quant";
        $soma_desc_F = 0;
        $soma_desc_D = 0;
        for ($Ipreviden_=0; $Ipreviden_<count($previden_); $Ipreviden_++) {
          
          
          $mat_r60_regist[$nro] = $previden_[$Ipreviden_]["r60_regist"];
          $mat_r60_tpvinc[$nro] = $previden_[$Ipreviden_]["r01_tpvinc"];
          $mat_r60_numcgm[$nro] = $numcgm;

          if($arquivo == 'gerffer' ){

            $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($previden_[$Ipreviden_]["r60_regist"]);
            $condicaoaux .= " and ".$sigla_ajuste."rubric = ".db_sqlformat($rubrica_desconto );

            if($soma_base_F != 0 ){
              $novo_desc_F   = round($previden_[$Ipreviden_]["r60_basef"] / $soma_base_F  * $valor_a_ratear_F,2 ) ;
             //echo "<BR>  mat_r60_regist --> $mat_r60_regist novo_desc_F --> $novo_desc_F   = round(".$previden_[$Ipreviden_]["r60_basef"]." / $soma_base_F  * $valor_a_ratear_F,2 ) ;";
              $matriz2[1] = $novo_desc_F;
              $matriz2[2] = $perc_inss_F;
              //echo "<BR> perc_inss_F --> $perc_inss_F";

              $soma_desc_F += $novo_desc_F;
            //echo "<BR> if($soma_desc_F > $valor_a_ratear_F){";
              if(round($soma_desc_F,2) > round($valor_a_ratear_F,2)){
                $matriz2[1] = abs($novo_desc_F - (round($soma_desc_F - ($soma_desc_F-$valor_a_ratear_F),2)));
                //echo "<BR> matriz2 --> ".$matriz2[1]." = round($soma_desc_F - ($soma_desc_F-$valor_a_ratear_F),2);";
              }

              if($atingiu_o_teto_tbprev == true){
                 $matriz2[2] = 0;
              }  
              $retornar = db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux." and r31_tpp = 'F'" );
            }
            if($soma_base_D != 0 ){
               $novo_desc_D   = round(($previden_[$Ipreviden_]["r60_base"] - $previden_[$Ipreviden_]["r60_basef"]) / $soma_base_D  * $valor_a_ratear_D,2 ) ;
             //echo "<BR> mat_r60_regist --> $mat_r60_regist novo_desc_D -->  $novo_desc_D   = round(".$previden_[$Ipreviden_]["r60_basef"]." / $soma_base_D  * $valor_a_ratear_D,2 ) ;";
               $matriz2[1] = $novo_desc_D;
               $matriz2[2] = $perc_inss_D;
              //echo "<BR> perc_inss_D --> $perc_inss_D";
               $retornar = db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux." and r31_tpp = 'D'" );

               $soma_desc_D += $novo_desc_D;
               if(round($soma_desc_D,2) > round($valor_a_ratear_D,2)){
                 $matriz2[1] = round($soma_desc_D - ($soma_desc_D-$valor_a_ratear_D),2);
                 $matriz2[1] = abs($novo_desc_D - (round($soma_desc_D - ($soma_desc_D-$valor_a_ratear_D),2)));
               }

            }   

          }else{
             if ($valor_a_ratear > 0) {
                $novo_desconto = round($previden_[$Ipreviden_]["r60_base"] / $soma_base  * $valor_a_ratear,2 ) ;
             } else {
               $novo_desconto = 0;
             }

             $registrop = $previden_[$Ipreviden_]["r60_regist"];
             $qual_folha = strtoupper($previden_[$Ipreviden_]["r60_folha"]);
             $valor_desconto_fer = 0;
             $valor_desconto_com = 0;
             //echo "<BR> matricula --> $registrop novo_desconto --> $novo_desconto = round(".$previden_[$Ipreviden_]["r60_base"]." / $soma_base  * $valor_a_ratear,2 )" ;
             //echo "<BR> 2------->  if ($opcao_geral != 3) {";
             if ($opcao_geral != 3) {
                $sqlfer = " select sum(".$sigla_ajuste."valor) as valor_desconto_fer
                       from ".$arquivo."
                            inner join rhpessoal on rh01_regist = ".$sigla_ajuste."regist
                       ".bb_condicaosubpes($sigla_ajuste)."
                       and ".$sigla_ajuste."rubric in ('R903','R906','R909','R912') and rh01_numcgm = $numcgm and rh01_regist = $registrop";
                $resfer = pg_exec($sqlfer);
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
                   $resfer = pg_exec($sqlfer);
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
                        and r48_rubric in ('R901','R902','R904','R905','R907','R908','R910','R911') and rh01_numcgm = $numcgm and rh01_regist = $registrop";
                   $rescom = pg_exec($sqlcom);
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
             $mat_r60_novods[$nro] = round($novo_desconto - $valor_desconto_com,2);
             $mat_r60_novodf[$nro] = round($valor_desconto_fer,2); 
             $mat_r60_dif[$nro]    = round(($novo_desconto - $valor_desconto_com - $valor_desconto_fer),2); 
             //echo "<BR> mat_r60_dif --> ".$mat_r60_dif[$nro]."    = round(($novo_desconto - $valor_desconto_com - $valor_desconto_fer),2); ";
             $mat_r60_novop[$nro]  = $perc_inss; 
             //echo "<BR> $numcgm  $tbprev  $rubrica_base  $registrop $qual_folha ".round($novo_desconto,2)." ".round($valor_desconto_fer,2)." $perc_inss ".$mat_r60_dif[$nro];
          
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
            $matriz2[ 1 ] = $mat_r60_novods[$nro];
            $matriz2[ 2 ] = $mat_r60_novop[$nro];
            $condicaoaux  = " and r60_numcgm = ".db_sqlformat($mat_r60_numcgm[$nro] );
            $condicaoaux .= " and r60_tbprev = ".db_sqlformat($mat_r60_tbprev[$nro] );
            $condicaoaux .= " and r60_rubric = ".db_sqlformat($mat_r60_rubric[$nro] );
            $condicaoaux .= " and r60_regist = ".db_sqlformat($mat_r60_regist[$nro] );
            $condicaoaux .= " and upper(r60_folha)  = ".db_sqlformat(strtoupper($mat_r60_folha[$nro]) );
            //echo "<BR> condicaoaux  --> $condicaoaux"; // reis
            db_update("previden", $matriz1, $matriz2, bb_condicaosubpes("r60_").$condicaoaux );
          
            $rubrica_desconto = "R9".db_str((( 3 * $mat_r60_tbprev[$nro] ) -2 ) -1 + $sequencia ,2,0,"0");
            
            $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($mat_r60_regist[$nro] );
            $condicaoaux .= " and ".$sigla_ajuste."rubric = ".db_sqlformat($rubrica_desconto );
            
            $ajusta_dif = round($mat_r60_novods[$nro]-$mat_r60_novodf[$nro],2);
            //echo "<BR> ajusta_dif --> ".$ajusta_dif." = round($mat_r60_novods[$nro]-$mat_r60_novodf[$nro],2);";

            if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {
              $arq_ = $$arquivo;
              //if(($ajusta_dif <= 0 && $atingiu_o_teto_tbprev == false ) || $atingiu_o_teto_tbprev == true){
              if(($ajusta_dif <= 0 && $atingiu_o_teto_tbprev == false ) || $atingiu_o_teto_tbprev == true){
                //echo "<BR> passou aqui 3 condicaoaux --> ".bb_condicaosubpes($sigla_ajuste).$condicaoaux;
                db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
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
                
                
                //$perc_inss = $inssirf[$Iinssirf]["r33_perc"];
                $matriz2[1] = $mat_r60_regist[$nro];
                $matriz2[2] = $rubrica_desconto;
                $matriz2[3] = $pessoal_2[0]["r01_lotac"];
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
              } else {
                //echo "<BR> db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );";
                db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
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
                //$perc_inss = $inssirf[$Iinssirf]["r33_perc"];
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
              }
            }
          }elseif($opcao_geral == 3) {
            $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($mat_r60_regist[$nro] );
            $condicaoaux .= " and ".$sigla_ajuste."rubric = 'R993'";
            //echo "<BR> 54 select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux ;
            if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {
              db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
            }
          }
          //echo "<BR> 1 grava_ajuste_irrf($numcgm,".$mat_r60_regist[$nro].",".$mat_r60_tpvinc[$nro].");";
          grava_ajuste_irrf($numcgm,$mat_r60_regist[$nro],$mat_r60_tpvinc[$nro]);
          //echo "<BR> 2 grava_ajuste_irrf($numcgm,".$mat_r60_regist[$nro].",".$mat_r60_tpvinc[$nro].");";
          // Inicio do Calculo da Insuficiencia de Saldo

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
              if ($d08_carnes == "riogrande" && $arq_[$Iarquivo][$sigla_ajuste."rubric"] == "0045" ) {
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
          if ($d08_carnes == "viamao" && $d08_carnes == "carazinho" ) {
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
                db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
              } else {
                db_insert($arquivo, $matriz1, $matriz2 );
              }
            }
// Fim do Calculo da Insuficiencia de Saldo
          }
        } 
      }
    }
  }
}

function deleta_para_ajustes($vrubrica, $registro_, $tipo){
   global $pessoal,$Ipessoal,$campos_pessoal;

   if( db_at($vrubrica ,"R985-R986-R987-") > 0){
      // R985 BASE PREVIDENCIA (SALARIO) 
      // R986 BASE PREVIDENCIA (13O SAL) 
      // R987 BASE PREVIDENCIA (FERIAS) 
      global $pessoal_del;
      
      $condicaoaux = " and rh02_regist = ".db_sqlformat( $registro_ );
      db_selectmax("pessoal_del", "select rh01_numcgm as r01_numcgm, rh02_tbprev as r01_tbprev, rh02_regist as r01_regist from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                               and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      $condicaoaux  = " and r60_numcgm = ".db_sqlformat( $pessoal_del[0]["r01_numcgm"] );
      $condicaoaux .= " and r60_tbprev = ".db_sqlformat( $pessoal_del[0]["r01_tbprev"] );
      $condicaoaux .= " and r60_rubric = ".db_sqlformat( $vrubrica );
      $condicaoaux .= " and r60_regist = ".db_sqlformat( $pessoal_del[0]["r01_regist"] );
      $condicaoaux .= " and upper(r60_folha) = ".db_sqlformat( strtoupper($tipo) );
      db_delete( "previden", bb_condicaosubpes("r60_").$condicaoaux );
   }
   if(db_at($vrubrica ,"R981-R982-R983-") > 0){
      // R981 BASE IRF SALARIO (BRUTA) 
      // R982 BASE IRF 13O SAL (BRUTA) 
      // R983 BASE IRF FERIAS (BRUTA) 
      global $pessoal_del;

      $condicaoaux = " and rh02_regist = ".db_sqlformat( $registro_ );
      db_selectmax("pessoal_del", "select rh01_numcgm as r01_numcgm, rh02_regist as r01_regist from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );

      $condicaoaux  = " and r61_numcgm = ".db_sqlformat( $pessoal_del[0]["r01_numcgm"] );
      $condicaoaux .= " and r61_rubric = ".db_sqlformat( $vrubrica );
      $condicaoaux .= " and r61_regist = ".db_sqlformat( $pessoal_del[0]["r01_regist"] );
      $condicaoaux .= " and upper(r61_folha) = ".db_sqlformat( strtoupper($tipo) );
      db_delete( "ajusteir", bb_condicaosubpes("r61_").$condicaoaux );
   }

}

/// fim da funcao ajusta_previdencia ///
/// ajusta_irrf ///

function ajusta_irrf($arquivo, $rubrica_base, $sequencia, $sigla_ajuste) {
  
  global $quais_diversos, $ajusteir,$campos_pessoal,$ajusteir_,$inssirf,$Iinssirf,$subpes,$d08_carnes,$cfpess,
  $inssirf_r33_perc;
  global $anousu, $mesusu, $DB_instit, $db_debug;
  global $quais_diversos;
  eval($quais_diversos);
  
  $matriz1 = array();
  $matriz2 = array();
  $matriz1[1] = "r61_ajuste";
  $matriz2[1] = 'f';
  
  db_update("ajusteir",$matriz1, $matriz2, bb_condicaosubpes("r61_" ));
  
  $condicaoaux  = " and r61_altera = 't' and r61_rubric = ".db_sqlformat($rubrica_base);
  $condicaoaux .= " and upper(r61_folha) in ('".($sequencia==1?"S":($sequencia==2?"3":"F"))."') ";
  
  db_selectmax("ajusteir_", "select * from ajusteir ".bb_condicaosubpes("r61_" ).$condicaoaux);
  for ($Iajusteir=0; $Iajusteir<count($ajusteir_); $Iajusteir++) {
    $matriz2[1] = 't' ;
    $condicaoaux  = " and r61_numcgm = ".db_sqlformat($ajusteir_[$Iajusteir]["r61_numcgm"] );
    $condicaoaux .= " and r61_rubric = ".db_sqlformat($rubrica_base );
    db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_").$condicaoaux );
    //   $Iajusteir = $posicao;
  }
  
  
  $condicaoaux  = " and r61_ajuste = 't' and r61_rubric = ".db_sqlformat($rubrica_base);
  $condicaoaux .= " and upper(r61_folha) in ('".($sequencia==1?"S":($sequencia==2?"3":"F"))."') order by r61_numcgm ";
  
  db_selectmax("ajusteir", "select * from ajusteir ".bb_condicaosubpes("r61_").$condicaoaux);
  
  //echo "<BR> rubrica_base    --> ".$rubrica_base;
  //echo "<BR> count(ajusteir) --> ".count($ajusteir);
  if (count($ajusteir) > 0) {
    //   $condicaoaux  = " and r61_ajuste = 't' and r61_rubric = ".db_sqlformat($rubrica_base);
    //   db_selectmax("ajusteir", "select * from ajusteir ".bb_condicaosubpes("r61_"));
    
    // o que define a condicao e a gravacao do arquivo na ion. gerfsal ...;
    $Iajuste = 0;
    $tot_ajusteir = count($ajusteir);
    while ($Iajuste < $tot_ajusteir) {
      //echo "<BR> chegou aqui 31 :  arquivo --> $arquivo ---> rubrica base --> $rubrica_base  sigla ajuste --> $sigla_ajuste"; // reis
      $numcgm = $ajusteir[$Iajuste]["r61_numcgm"];
      
      $registro = $ajusteir[$Iajuste]["r61_regist"];
      $soma_base = 0;
      $soma_desc_fixo = 0;
      $soma_base_fixo = 0;
      $soma_deducoes = 0;
      $soma_depend = 0;
      $soma_didade = 0;
      // soma dos brutos e deducoes;
      while ($Iajuste < $tot_ajusteir && ($ajusteir[$Iajuste]["r61_numcgm"] == $numcgm )) {
        
        //echo "<BR> chegou aqui 32 :  numcgm --> $numcgm"; // reis
        //echo "<BR> r61_ajuste --> ".$ajusteir[$Iajuste]["r61_ajuste"];
        //echo "<BR> r61_rubric --> ".$ajusteir[$Iajuste]["r61_rubric"];
        //echo "<BR> r61_depend --> ".$ajusteir[$Iajuste]["r61_depend"];
        //echo "<BR> r61_didade --> ".$ajusteir[$Iajuste]["r61_didade"];
        //echo "<BR> r61_deduc  --> ".$ajusteir[$Iajuste]["r61_deduc"];
        //echo "<BR> r61_base   --> ".$ajusteir[$Iajuste]["r61_base"];
        //echo "<BR> r61_novod  --> ".$ajusteir[$Iajuste]["r61_novod"];
        
        
        $soma_base += $ajusteir[$Iajuste]["r61_base"];
        if ($ajusteir[$Iajuste]["r61_depend"] > $soma_depend) {
          $soma_depend = $ajusteir[$Iajuste]["r61_depend"];
        }
        $soma_didade += $ajusteir[$Iajuste]["r61_didade"];
        // D902 VLR DESC IRF P/65 ANOS
        if ($soma_didade > $D902) {
          $soma_didade = $D902;
        }
        $soma_deducoes += ( $ajusteir[$Iajuste]["r61_deduc"] );
        if ('f' ==  $ajusteir[$Iajuste]["r61_altera"] ) {
          $soma_base_fixo += $ajusteir[$Iajuste]["r61_base"];
          $soma_desc_fixo += $ajusteir[$Iajuste]["r61_novod"];
        }
        //echo "<BR> chegou aqui 32.1 :  numcgm --> $numcgm"; // reis
        //echo "<BR> base 1 --> $soma_base";
        //echo "<BR> depend  1 --> $soma_depend";
        //echo "<BR> didade  1 --> $soma_didade";
        //echo "<BR> deducoes  1 --> $soma_deducoes";
        //echo "<BR> base fixo  1 --> $soma_base_fixo";
        //echo "<BR> desc fixo  1 --> $soma_desc_fixo";
        $Iajuste++;
      }
      if($sequencia==3){
         global $previden;
         $sqlfer = " select sum(r31_valor) as  soma_desc_previdencia
                from gerffer
                     inner join rhpessoal on rh01_regist = r31_regist
                ".bb_condicaosubpes($sigla_ajuste)."
                and r31_rubric in ('R903','R906','R909','R912') and r31_tpp = 'F' and rh01_numcgm = $numcgm ";
         if (db_selectmax("previden", $sqlfer )) {
             $soma_desc_previdencia = $previden[0]["soma_desc_previdencia"];
         }
      }else{
         // soma os descontos de previdencia deste numcgm;
         $soma_desc_previdencia = 0;
         $condicaoaux  = " and r60_rubric = 'R98". db_str((db_val(db_substr($rubrica_base, 4, 1) )+4),1,0)."' and r60_numcgm = ".db_sqlformat($numcgm );
         global $previden;
         if (db_selectmax("previden", "select * from previden ".bb_condicaosubpes("r60_" ).$condicaoaux )) {
           for ($Ipreviden=0; $Ipreviden<count($previden); $Ipreviden++) {
             $soma_desc_previdencia += $previden[$Ipreviden]["r60_novod"];
           }
         }
      }
      //echo "<BR> soma base             1.2 --> ".$soma_base;
      //echo "<BR> soma desc previdencia 1.2 --> ".$soma_desc_previdencia;
      //echo "<BR> soma depend           1.2 --> ".$soma_depend;
      //echo "<BR> soma deducoes         1.2 --> ".$soma_deducoes;
      //echo "<BR> soma didade           1.2 --> ".$soma_didade;
      
      $base_liquida = round(( $soma_base - $soma_desc_previdencia - $soma_depend - $soma_deducoes - $soma_didade ),2 );
      //echo "<BR> base liquida          1 --> ".$base_liquida;
      $valor_desconto_total = 0;
      if ($base_liquida > 0) {
        //echo "<BR> le_irf() base_liquida         --> $base_liquida";
        $valor_desconto_total = round(le_irf($base_liquida,"1"),2) ;
        //echo "<BR> le_irf() valor desconto total --> $valor_desconto_total";
      }
      // D911 VALOR MINIMO P/ DESC DE IRF
      if ($valor_desconto_total < $D911) {
        $valor_a_ratear = 0;
        //echo "<BR> valor a ratear 1 --> ".$valor_a_ratear;
      } else {
        $valor_a_ratear = $valor_desconto_total - $soma_desc_fixo;
        //echo "<BR> valor a ratear 2  --> ".$valor_a_ratear;
      }
      
      //echo "<BR> chegou aqui 4 :  arquivo --> $arquivo ---> rubrica base --> $rubrica_base  sigla ajuste --> $sigla_ajuste"; // reis
      $condicaoaux  = " and r61_numcgm = ".db_sqlformat($numcgm );
      $condicaoaux .= " and r61_rubric = ".db_sqlformat($rubrica_base );
      $condicaoaux .= " and upper(r61_folha) in ('".($sequencia==1?"S":($sequencia==2?"3":"F"))."') ";
      $condicaoaux .= " and r61_altera = 't' ";
      global $ajusteir_;
      if (db_selectmax("ajusteir_", "select * from ajusteir ".bb_condicaosubpes("r61_" ).$condicaoaux )) {
        for ($Iajusteir_=0; $Iajusteir_< count($ajusteir_) ; $Iajusteir_++) {
          
          //echo "<BR> chegou aqui 6.1:  numcgm  --> ".$ajusteir_[$Iajusteir_]["r61_numcgm"];
          // reis
          //echo "<BR> chegou aqui 6.1:  regist  --> ".$ajusteir_[$Iajusteir_]["r61_regist"];
          // reis
//          $condicaoaux = " and r01_regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
          global $pessoal_1;
//          db_selectmax("pessoal_","select r01_tpcont,r01_numcgm,r01_lotac from pessoal ".bb_condicaosubpes("r01_").$condicaoaux );

      $condicaoaux = " and rh02_regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
      db_selectmax("pessoal_1", "select rh01_numcgm  as r01_numcgm, rh02_tpcont as r01_tpcont,  trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );
          
          if ($valor_a_ratear > 0) {
            //echo "<BR> ".$ajusteir_[$Iajusteir_]["r61_base"]." / (".$soma_base." - ".$soma_base_fixo.") * ".$valor_a_ratear ;

            $novo_desconto = ($ajusteir_[$Iajusteir_]["r61_base"] / ($soma_base - $soma_base_fixo) * $valor_a_ratear) ;

            //echo "<BR> chegou aqui 6 :  r61_base --> ".$ajusteir_[$Iajusteir_]["r61_base"];
            // reis
            //echo "<BR> chegou aqui 6 :  novo_desconto --> $novo_desconto"; // reis
          } else {
            //echo "<BR> chegou aqui 7 :  novo_desconto --> $novo_desconto"; // reis
            $novo_desconto = 0;
          }
          // percentual - se o inssirf estiver deslocado para as tabelas de;
          // previdencia dara erro;
          
          $perc_inss = $inssirf_r33_perc;
          
          if ($pessoal_1[0]["r01_tpcont"] == "13") {
            $perc_inss = 11;
          }
          
          $novop = ($inssirf[0]["r33_codtab"] != "1"? 0: $perc_inss );
          
          $matriz1 = array();
          $matriz2 = array();
          
          $matriz1[1] = "r61_novod";
          $matriz1[2] = "r61_novop";
          $matriz1[3] = "r61_dprev";
          
          $matriz2[1]  = round($novo_desconto,2);
          $matriz2[2]  = round($novop,2);
          $matriz2[3]  = round($soma_desc_previdencia,2);
          
          $registroa   = $ajusteir_[$Iajusteir_]["r61_regist"];
          $qual_folha  = strtoupper($ajusteir_[$Iajusteir_]["r61_folha"]);
          
          $condicaoaux  = " and r61_numcgm = ".db_sqlformat($pessoal_1[0]["r01_numcgm"] );
          $condicaoaux .= " and r61_rubric = ".db_sqlformat($rubrica_base );
          $condicaoaux .= " and r61_regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
          $condicaoaux .= " and upper(r61_folha) = ".db_sqlformat($qual_folha );
          
          db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_").$condicaoaux );
          
          $rubrica_desconto = "R91". db_str($sequencia + 2 ,1);
          
          //echo "<BR> chegou aqui 6 :  novo valor --> $novo_desconto ---> rubrica --> $rubrica_desconto "; // reis
          $condicaoaux  = " and ".$sigla_ajuste."regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"] );
          $condicaoaux .= " and ".$sigla_ajuste."rubric = ".db_sqlformat($rubrica_desconto );
          if (db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux )) {
            if ($novo_desconto == 0) {
              //    echo "<BR> passou aqui 6 ".$ajusteir_[$Iajusteir_]["r61_regist"];
              
              db_delete($arquivo, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
            } else {
              
              $matriz1 = array();
              $matriz2 = array();
              $matriz1[1] = $sigla_ajuste."valor";
              $matriz1[2] = $sigla_ajuste."quant";
              $matriz2[1] = round($novo_desconto,2);
              $matriz2[2] = $inssirf_r33_perc;
              
              db_update($arquivo, $matriz1, $matriz2, bb_condicaosubpes($sigla_ajuste).$condicaoaux );
            }
            
          } else {
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
          
          $tot_desc = 0;
          $tot_prov = 0;
          $salfamilia = 0;
          $tot_liq = 0;
          $salario_esposa = 0;
          
          global $$arquivo;
          $condicaoaux = " and ".$sigla_ajuste."regist = ".db_sqlformat($ajusteir_[$Iajusteir_]["r61_regist"]);
          db_selectmax($arquivo, "select * from ".$arquivo." ".bb_condicaosubpes($sigla_ajuste ).$condicaoaux );
          $arq_ = $$arquivo;
          for ($Iarquivo=0; $Iarquivo <count($arq_); $Iarquivo++) {
            
            //echo "<BR> rubrica 71 -->".$arq_[$Iarquivo][$sigla_ajuste."rubric"]."  valor --> ".$arq_[$Iarquivo][$sigla_ajuste."valor"]." tipo --> ".$arq_[$Iarquivo][$sigla_ajuste."pd"];
            // reis
            if (substr("#".$arq_[$Iarquivo][$sigla_ajuste."rubric"],1,1) != "R" ) {
              // caso especial para salario_esposa;
              if (trim($d08_carnes)== "riogrande" && $arq_[$Iarquivo][$sigla_ajuste."rubric"] == "0045" ) {
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
          if (trim($d08_carnes ) == "viamao" ||  trim($d08_carnes ) == "carazinho" ) {
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
    }
  }
  
  //echo "<BR> fim da rotina ajusta irrf"; //reis
}


/// fim da funcao ajusta_irrf ///
/// carrega_fxxx ///
function carrega_fxxx($codigo, $carrega, $cfuncao=null ){
  global $F001, $F002, $F004, $F005, $F006,$F006_clt, 
   $F007, $F008, $F009, $F010, $F011,
   $F012, $F013, $F014, $F015, $F016,
   $F017, $F018, $F019, $F020, $F021,
   $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028,
   $subpes, $cfpess,$pessoal,$Ipessoal,$d08_carnes,$padroes;

  global $quais_diversos;
  eval($quais_diversos);

  global $diversos;
  global $anousu, $mesusu, $DB_instit;
 
 
  global $pad,$tri,$progress;
   
  $ultdat = db_str(ndias(per_fpagto(1)),2,0,'0')."/".per_fpagto(1);
  $F003  = "";
  

  $campos_pessoal_  = "RH02_ANOUSU as r01_anousu, 
                       RH02_MESUSU as r01_mesusu,
                       RH01_REGIST as r01_regist,
                       RH01_NUMCGM as r01_numcgm, 
                       trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,";
  $campos_pessoal_ .= "RH01_ADMISS as r01_admiss, 
                       RH05_RECIS as r01_recis, 
                       RH02_tbprev as r01_tbprev,
                       RH30_REGIME as r01_regime, 
                       RH30_VINCULO as r01_tpvinc,";
  $campos_pessoal_ .= "RH02_salari as r01_salari, 
                       RH03_PADRAO as r01_padrao, 
                       RH02_HRSSEM as r01_hrssem, 
                       RH02_HRSMEN as r01_hrsmen, 
                       RH01_NASC as r01_nasc, 
                       RH02_TPCONT as r01_tpcont,";
  $campos_pessoal_ .= "RH01_PROGRES as r01_anter,  
                       RH01_TRIENIO as r01_trien, 
                       (CASE WHEN RH01_PROGRES IS NOT NULL THEN 'S' ELSE 'N' END) AS r01_progr, 
                       RH01_CLAS1 AS r01_clas1,
                       rh02_codreg ,
                       RH01_CLAS2 AS r01_clas2, ";

  $campos_pessoal_ .= "fc_dias_vale(rh02_regist,".substr("#".$subpes,1,4).", ".substr("#".$subpes,6,2).",rh02_instit) as dias_vale , ";
  $campos_pessoal_ .= "fc_dias_trabalhados(rh02_regist,".substr("#".$subpes,1,4).", ".substr("#".$subpes,6,2).",false,rh02_instit) as dias_trab ";

  $F027 = 0;
  $F028 = 0;
//  $condicaoaux = " and r01_regist = ".db_sqlformat( $codigo );
  //echo "<BR> funcao carrega_fxxx()  codigo --> $codigo";
  global $pessoal_;
//  db_selectmax( "pessoal_", "select ".$campos_pessoal_." from pessoal ".bb_condicaosubpes( "r01_" ).$condicaoaux);

  $condicaoaux = " and rh02_regist = ".db_sqlformat( $codigo );
  db_selectmax("pessoal_", "select ".$campos_pessoal_." from rhpessoalmov 
                   inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                   inner join rhlota       on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                           and rhlota.r70_instit          = rhpessoalmov.rh02_instit  
                   inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                   left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                   left join rhpespadrao on rhpespadrao.rh03_seqpes = rhpessoalmov.rh02_seqpes
                   left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg 
                                     and rhregime.rh30_instit = rhpessoalmov.rh02_instit 
                   ".bb_condicaosubpes("rh02_" ).$condicaoaux );

  if( count($pessoal_) > 0){


     $F027 = $pessoal_[0]["dias_vale"] ;
     $F028 = $pessoal_[0]["dias_trab"] ;
     if( $pessoal_[0]["r01_hrssem"] > 0){
  $F002 = $pessoal_[0]["r01_hrssem"];
     }else{
  $condicaoaux  = " and r02_regime = ".db_sqlformat($pessoal_[0]["r01_regime"] );
  $condicaoaux .= " and trim(upper(r02_codigo)) = ".db_sqlformat(trim(strtoupper($pessoal_[0]["r01_padrao"])) );
  if( db_selectmax( "padroes", "select * from padroes ".bb_condicaosubpes( "r02_" ).$condicaoaux )){
     $F002 = $padroes[0]["r02_hrssem"];
  }
     }
     
     if( $pessoal_[0]["r01_hrsmen"] > 0 ){
         $F008 = $pessoal_[0]["r01_hrsmen"];
     }else{
         $condicaoaux  = " and r02_regime = ".db_sqlformat($pessoal_[0]["r01_regime"] );
         $condicaoaux .= " and trim(upper(r02_codigo)) = ".db_sqlformat(trim(strtoupper($pessoal_[0]["r01_padrao"])) );
   if( db_selectmax("padroes","select * from padroes ".bb_condicaosubpes( "r02_" ).$condicaoaux )){
      $F008 = $padroes[0]["r02_hrsmen"];
   }
     }
     if($subpes < "1997/06"){     
       $F008 = $F008 * 5;
     }
     if( $F008 < 1){
  $F008 = 220;
  $F002 = $F008/5;
     }
     $F026 = $pessoal_[0]["r01_padrao"];
     if( ( $cfuncao == "gerfres" && strtolower($pessoal_[0]["r01_tpvinc"]) == "a" ) 
  || ( !db_empty( $pessoal_[0]["r01_recis"] ) && strtolower($pessoal_[0]["r01_tpvinc"]) == "a" ) ){
  $data_base = $pessoal_[0]["r01_recis"];
     }else{
  $data_base = (strtolower($pessoal_[0]["r01_tpvinc"])=="a"?$cfpess[0]["r11_dataf"]:$pessoal_[0]["r01_admiss"]);
     }
     $data_progr = (db_empty($pessoal_[0]["r01_anter"])?$pessoal_[0]["r01_admiss"]:$pessoal_[0]["r01_anter"]);
     $data_trien = (db_empty($pessoal_[0]["r01_trien"])?$pessoal_[0]["r01_admiss"]:$pessoal_[0]["r01_trien"]);
     if( $d08_carnes == "viamao" 
         || trim($d08_carnes) == "charque" 
         || trim($d08_carnes) == "terra" ){
  $diadomes = ndias( substr("#". db_dtoc( $data_base ),4,7 ) ) ;
  $diadomes_q = $diadomes;
  $diadomes_t = $diadomes;
  if( db_day( $data_progr ) < $diadomes){
     $diadomes_q = db_day( $data_progr );
  }
  if( db_day( $data_progr ) < $diadomes){
     $diadomes_t = db_day( $data_progr );
  }
  $data_ref_q = ( strtolower($pessoal_[0]["r01_tpvinc"]) == "a" ?
         db_ctod( db_str($diadomes_q,2,0,"0")."/" 
           . db_str( db_month($data_base),2,0,"0")."/" 
           . db_str( db_year($data_base),4,0) )
        : $data_base );
  $data_ref_t = ( strtolower($pessoal_[0]["r01_tpvinc"]) == "a" 
        ? db_ctod( db_str($diadomes_t,2,0,"0")."/" 
           . db_str( db_month($data_base),2,0,"0")."/" 
           . db_str( db_year($data_base),4,0) )
        : $data_base );

  $meses_q = ( meses_entredatas($data_progr,$data_ref_q,false,true,false)  );
  
  $F012 = $meses_q/12;
  $F012 = (int)$F012;
  
  $F024 = $meses_q;
     }else{
      if( $d08_carnes == "sapiranga"){
        if( $pessoal_[0]["r01_regime"] == 2 && strtolower(substr("#".$pessoal_[0]["r01_padrao"],1,2)) != 'pf'){
          if( db_mktime($pessoal_[0]["r01_admiss"]) > db_mktime(db_ctod('30/09/1984'))){
       if( db_selectmax("tri","select fc_idade(to_date('".db_dtoc($pessoal_[0]["r01_admiss"])."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
         $F013 = $tri[0]["fc_idade"]/3;
         $F013 = (int)$F013;
       }
          }else{
       if( db_selectmax( "tri", "select fc_idade(to_date('30-09-1984','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
         $F013 = $tri[0]["fc_idade"]/3;
               $F013 = (int)$F013; 
       }
          }
    if( db_selectmax( "pad", "select fc_idade(to_date('".db_dtoc($pessoal_[0]["r01_admiss"])."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
       $F012 = $pad[0]["fc_idade"];
    }
        }else if( db_at(trim($pessoal_[0]["r01_clas1"]) , "1-9-2") > 0 && strtolower(substr("#".$pessoal_[0]["r01_padrao"],1,2)) == 'pf'){ 
    if( trim($pessoal_[0]["r01_clas1"]) == '2'){
      if( db_empty($pessoal_[0]["r01_clas2"]) ){
              if( db_mktime($pessoal_[0]["r01_admiss"]) < db_mktime(db_ctod("31/10/1997"))){
          if( db_selectmax( "tri", "select fc_idade(to_date('".db_dtoc($pessoal_[0]["r01_admiss"])."','dd-mm-YYYY'),to_date('31-10-1997','dd-mm-YYYY'))") ){
            $F013 = $tri[0]["fc_idade"]/3;
                  $F013 = (int)$F013; 
          }
              }else{
          if( db_selectmax( "tri", "select fc_idade(to_date('".db_dtoc($pessoal_[0]["r01_admiss"])."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
            $F013 = $tri[0]["fc_idade"]/3;
                  $F013 = (int)$F013; 
                }
              }
              if( ( db_mktime($pessoal_[0]["r01_admiss"]) < db_mktime(db_ctod('31/10/1997')) && $F013 == 0 ) || db_mktime($pessoal_[0]["r01_admiss"]) > db_mktime(db_ctod('31/10/1997'))){
                $xdata = $pessoal_[0]["r01_admiss"] ;
              }else{
          $xxano = db_year($pessoal_[0]["r01_admiss"])+($F013*3);
          if( db_month($pessoal_[0]["r01_admiss"]) == 2 && db_day($pessoal_[0]["r01_admiss"]) == 29){
            $xdia = 28;
          }else{
            $xdia = db_day($pessoal_[0]["r01_admiss"]);
          }
          $xdata = db_ctod(db_str($xdia,2,0,'0').'/'.db_str(db_month($pessoal_[0]["r01_admiss"]),2,0,'0').'/'.db_str($xxano,4,0,'0'));
              }
              $data_base_ant = $data_base;
              if( $pessoal_[0]["r01_regist"] == 4096 || $pessoal_[0]["r01_regist"] == 1260 ){
           if( $pessoal_[0]["r01_regist"] == 4096){
             // reis aqui tu tens que somar timestamp
             $data_base = date("Y-m-d",db_mktime($data_base) + (3890 * 86400));
           }else{
             // reis aqui tu tens que somar timestamp
             $data_base = date("Y-m-d",db_mktime($data_base) + (730 * 86400));
           }
              }
              $xdata_ant = $xdata;
              if( $pessoal_[0]["r01_regist"] == 1011 || $pessoal_[0]["r01_regist"] == 398 || $pessoal_[0]["r01_regist"] == 1144 || $pessoal_[0]["r01_regist"] == 1255){
           $xdata = $pessoal_[0]["r01_admiss"];
              }
        if( db_selectmax( "pad", "select fc_idade(to_date('".db_dtoc($xdata)."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
          $F012 = $pad[0]["fc_idade"];
        }
        $data_base = $data_base_ant;
        $xdata = $xdata_ant;
      }else{
        if( db_selectmax( "tri", "select fc_idade(to_date('".db_dtoc($pessoal_[0]["r01_admiss"])."','dd-mm-YYYY'),to_date('".db_dtoc($pessoal_[0]["r01_clas2"])."','dd-mm-YYYY'))") ){
          $F013 = $tri[0]["fc_idade"]/3;
    $F013 = (int)$F013;
        }
              if( ( db_mktime($pessoal_[0]["r01_admiss"]) < db_mktime($pessoal_[0]["r01_clas2"]) && $F013 == 0 ) || db_mktime($pessoal_[0]["r01_admiss"]) > db_mktime($pessoal_[0]["r01_clas2"]) ){
                $xdata = $pessoal_[0]["r01_admiss"] ;
              }else{
          $xxano = db_year($pessoal_[0]["r01_admiss"])+($F013*3);
          if( db_month($pessoal_[0]["r01_admiss"]) == 2 && db_day($pessoal_[0]["r01_admiss"]) == 29){
             $xdia = 28;
          }else{
            $xdia = db_day($pessoal_[0]["r01_admiss"]);
          }
                $xdata = db_ctod(db_str($xdia,2,0,'0').'/'.db_str(db_month($pessoal_[0]["r01_admiss"]),2,0,'0').'/'.db_str($xxano,4,0,'0'));
              }
              $xclas2_ant = $xdata;
        if( db_selectmax( "pad", "select fc_idade(to_date('".db_dtoc($xclas2_ant)."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
          $F012 = $pad[0]["fc_idade"];
        }
            }
    }else if( trim($pessoal_[0]["r01_clas1"]) == '9'){ 
      if( db_selectmax("tri", "select fc_idade(to_date('".db_dtoc($pessoal_[0]["r01_admiss"])."','dd-mm-YYYY'),to_date('31-10-1997','dd-mm-YYYY'))") ){
              $F013 = $tri[0]["fc_idade"]/3;
        $F013 = (int)$F013;
      }
            if( db_mktime($pessoal_[0]["r01_admiss"]) < db_mktime(db_ctod('31/10/1997')) && $F013 == 0){
              $xdata = $pessoal_[0]["r01_admiss"] ;
            }else{
              $xxano = db_year($pessoal_[0]["r01_admiss"])+($F013*3);
              if( db_month($pessoal_[0]["r01_admiss"]) == 2 && db_day($pessoal_[0]["r01_admiss"]) == 29){
          $xdia = 28;
       }else{
          $xdia = db_day($pessoal_[0]["r01_admiss"]);
       }
      $xdata = db_ctod(db_str($xdia,2,0,'0').'/'.db_str(db_month($pessoal_[0]["r01_admiss"]),2,0,'0').'/'.db_str($xxano,4,0,'0'));
          }
    if( db_selectmax("pad", "select fc_idade(to_date('".db_dtoc($xdata)."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
      $F012 = $pad[0]["fc_idade"];
    }
        }else{
    if( db_selectmax("tri", "select fc_idade(to_date('".db_dtoc($data_trien)."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
      $F013 = $tri[0]["fc_idade"]/3;
      $F013 = (int)$F013;
    }
    if( db_selectmax("pad", "select fc_idade(to_date('".db_dtoc($pessoal_[0]["r01_admiss"])."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
      $F012 = $pad[0]["fc_idade"];
    }
  }
      }else{
   if( db_selectmax("pad", "select fc_idade(to_date('".db_dtoc($data_trien)."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
           $F013 = $pad[0]["fc_idade"]/3;
     $F013 = (int)$F013;
        }
        $data_base_ant = $data_base;
        if( $pessoal_[0]["r01_regist"] == 2870){
          $data_base     = date("Y-m-d",db_mktime($data_base) + (1155 * 86400));
        }
        if( db_selectmax("pad", "select fc_idade(to_date('".db_dtoc($pessoal_[0]["r01_admiss"])."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
     $F012 = $pad[0]["fc_idade"];
  }
  $data_base = $data_base_ant;
      }
      
     //$F024 = round( ((( db_datedif($data_base,$data_progr) / 365)*12)-1),2) - 1;
     //$F024 = round( ((( db_datedif($data_base,$data_progr) / 365)*12)-1),2) ;
     $F024 = conta_meses($data_progr,$data_base) -1;
    }else{
      if( db_selectmax("pad", "select fc_idade(to_date('".db_dtoc($data_progr)."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
        $F012 = $pad[0]["fc_idade"];
      }
      // ver como retornar dias entre datas
      
      //$F024 = round((( db_datedif($data_base,$data_progr ) /365*12)-1),2);
      $F024 = conta_meses($data_progr,$data_base) ;
      global $tri;
      if( db_selectmax("tri", "select fc_idade(to_date('".db_dtoc($data_trien)."','dd-mm-YYYY'),to_date('".db_dtoc($data_base)."','dd-mm-YYYY'))") ){
        $F013 = $tri[0]["fc_idade"]/3;
        $F013 = (int)$F013;

      }
    }
  }
     salario_base($pessoal,$Ipessoal, $cfuncao);
     
  if( $pessoal_[0]["r01_hrsmen"] > 0  || $padroes[0]["r02_hrsmen"] > 0){
     if( $F008 != 0){
        $F001 = $F007/$F008    ;
        $F011 = $F010/$F008;
     }else{
        $F001 = 0;
        $F011 = 0;
     }
  }
     $F003 = $pessoal_[0]["r01_admiss"];
     $xxano = substr("#".$subpes,1,4);
     $xxmes = substr("#".$subpes,6,2);
     $F025 = db_day(date("Y-m-d",db_mktime(db_ctod('01/'.db_str(db_val($xxmes)+1,2,0,'0').'/'.$xxano)) - (1*86400)));
     if( db_month($data_base) == db_month($data_trien) && db_year($data_base) == db_year($data_trien)){
  $dias_trienio = db_day($data_trien);
     }
     // D909 MAXIMO DE TRIENIOS (ATIVO)
     if( $F013 > $D909 && $D909 != 0){
  $F013 = $D909 ;
     }
     
     if( $d08_carnes == "sapiranga"){
       if( db_mktime($pessoal_[0]["r01_admiss"]) < db_mktime(db_ctod("03/04/1990"))){
         $F022 = db_datedif($data_base,db_ctod("03/04/1990"));
       }else{
         $F022 = db_datedif($data_base,$data_progr);
       }
     }elseif($d08_carnes == "arapiraca"){
       $F022 = db_datedif($data_base,$pessoal_[0]["r01_admiss"] );
     }else{
       $F022 = db_datedif($data_base,$data_progr );
     }
     
     $F022 = bcdiv($F022,1825,0);
     
     if( strtolower($pessoal_[0]["r01_progr"]) == "s"){
  $condicaoaux  = " and r24_regime = ".db_sqlformat( $pessoal_[0]["r01_regime"] );
  $condicaoaux .= " and r24_padrao = ".db_sqlformat( $pessoal_[0]["r01_padrao"] );
  $condicaoaux .= " and r24_meses <= $F024 order by r24_meses";
  global $progress;
  if( db_selectmax( "progress", "select * from progress ".bb_condicaosubpes( "r24_" ).$condicaoaux )){
     $F014 = 0;
     $F015 = 0;
     for($Iprogress=0;$Iprogress < count($progress) ;$Iprogress++){
        $F014 += 1 ;
              $F026 = trim($progress[$Iprogress]["r24_descr"]);
        $F015 = $progress[$Iprogress]["r24_perc"];
     }
  }
     }
     $F004 = ver_idade($ultdat,db_dtoc($pessoal[$Ipessoal]["r01_nasc"]));
     
     if( db_mktime($F003) < db_mktime(db_ctod("01/01/".substr("#".$ultdat,7,4))) ){
  $F009 = 12;
     }else{
  if( db_day($F003) > 15){
     $F009 = (13 - db_month($F003)) - 1;
  }else{
     $F009 = (13 - db_month($F003));
  }
     }
  }
  if( $carrega){
     dependentes($pessoal_[0]["r01_regist"]);
     vale_transp($pessoal_[0]["r01_regist"],$pessoal_[0]["r01_admiss"]);
     ferias($pessoal_[0]["r01_regist"],$cfuncao);
  }


//echo "<BR> $F001, $F002, $F004, $F005, $F006,$F006_clt";
//echo "<BR> $F007, $F008, $F009, $F010, $F011";
//echo "<BR> $F012, $F013, $F014, $F015, $F016";
//echo "<BR> $F017, $F018, $F019, $F020, $F021";
//echo "<BR> $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028";

 
  return true;

}


/// fim da funcao carrega_fxxx ///
/// carrega_r9xx ///
function carrega_r9xx($area, $sigla, $sigla2, $nro_do_registro,$opcao_tipo) {
  
  global $chamada_geral_arquivo,$cadferia,$pessoal,$Ipessoal,$d08_carnes,$dias_pagamento,$func_em_ferias,$opcao_geral, $db_debug;
  
  global $dtot_vpass,$dperc_pass,$dquant_pass,$quant_pass,$r14_valor,$r14_quant,$r20_rubr,$subpes,$situacao_funcionario;
  global $tot_vpass,$perc_pass,$cfpess,$afasta,$inssirf,$valor_salario_maternidade,$basesr, $Iinssirf,$siglag;
  global $anousu, $mesusu, $DB_instit,$n,$vlr_sal_saude_ou_acidente,$valor_salario_maternidade;
  
  
  
  global $dias_pagamento_sf,$mpsal , $valor_ant_vt, $quant_ant_vt ;
  global $F001, $F002, $F004, $F005, $F006,
  $F007, $F008, $F009, $F010, $F011,
  $F012, $F013, $F014, $F015, $F016,
  $F017, $F018, $F019, $F020, $F021,
  $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  
  global $quais_diversos;
  eval($quais_diversos);
  
  if($db_debug == true){
    echo "[carrega_r9xx] INICIANDO PROCESSAMENTO DA FUNÇÃO carrega_r9xx() <br>";
  }
  
  $r110_regist = $pessoal[$Ipessoal]["r01_regist"];
  
  //echo "<BR> Carregando r9xx - funcao $nro_do_registro";
  
  $valor_maximo = 0;
  $gerar_salario_familia = false;
  
// Inicio do Calculo R988 --> Deducoes p/IRRF(Salario)
  
  if($db_debug == true){ echo "[carrega_r9xx] 17 - chamando a função calc_rubrica() <br>"; }
  $r07_valor = calc_rubrica("R988",$area,$sigla,$sigla2,$nro_do_registro,true);

  // R988 DEDUCOES P/IRRF(SALARIO) --> baseado na base B988
  
  //echo "<br> 1 R988 : valor --> $r07_valor";
  
  if (db_empty($r07_valor)) {
    $r07_valor = 0;
  }
  $r14_valor = $r07_valor;
  if ($db_debug == true) { echo "[carrega_r9xx] 32 - r14_valor = $r14_valor  <br>"; }
  if (!db_empty($r14_valor)  ) {
    $r20_rubr = "R988";
    $r14_quant = 0;
    
    //echo "<br> 2 R988 : valor --> $r14_valor";
    if ($db_debug == true) {
      echo "[carrega_r9xx] 1 - Chamando a função grava_gerf() <br>";
    }
    grava_gerf($area);
  }
  
// Fim do Calculo R988 --> Deducoes p/IRRF(Salario)
  
// Inicio --> R979 DEDUCOES P/IRRF (FERIAS)

  if (db_empty($cadferia[0]["r30_proc2"]) ) {
    $r30_proc = "r30_proc1";
    $r30_peri = "r30_per1i";
    $r30_perf = "r30_per1f";
  } else {
    $r30_proc = "r30_proc2";
    $r30_peri = "r30_per2i";
    $r30_peri = "r30_per2f";
  }

  if ($chamada_geral_arquivo == "gerffer"
  && ($cadferia[0][$r30_proc] == $subpes )
  && ( ( strtolower($cfpess[0]["r11_fersal"]) == "f" && ('t' == $cadferia[0]["r30_paga13"]) )
  || 'f' == $cadferia[0]["r30_paga13"]
  )
  ) {
    
    // Inicio do Calculo R979 --> Deducoes p/IRRF(Ferias)
    
    // ir calculado ferias
    
    // Inicio calculo R979
    
    if($db_debug == true){ echo "[carrega_r9xx] 18 - chamando a função calc_rubrica() <br>"; }
    $r07_valor = calc_rubrica("R979",$area,$sigla,$sigla2,$nro_do_registro,true);
    // R979 DEDUCOES P/IRRF (FERIAS)
    if (db_empty($r07_valor)) {
      // se baseia na Base B979
      $r07_valor = 0 ;
    }
    $r14_valor = $r07_valor;
    if ($db_debug == true) { echo "[carrega_r9xx] 33 - r14_valor = $r14_valor  <br>"; }
    if (!db_empty($r14_valor)  ) {
      $r20_rubr = "R979";
      $r14_quant = 0;
      if ($db_debug == true) {
        echo "[carrega_r9xx] 2 - Chamando a função grava_gerf() <br>";
      }      
      grava_gerf($area);
    }
    
    // Fim calculo R979
    
    
  }
  
// Fim --> R979 DEDUCOES P/IRRF (FERIAS) e do Calculo R977 e R978
  
// Inicio Calculo R989 - DEDUCOES P/IRRF(13.SALARIO)
  
  if($db_debug == true){ echo "[carrega_r9xx] 19 - chamando a função calc_rubrica() <br>"; }
  $r07_valor = calc_rubrica("R989",$area,$sigla,$sigla2,$nro_do_registro,true);

  // R989 DEDUCOES P/IRRF(13.SALARIO)

  if (db_empty($r07_valor)) {
    $r07_valor = 0;
  }
  $r14_valor = $r07_valor;
  if ($db_debug == true) { echo "[carrega_r9xx] 34 - r14_valor = $r14_valor  <br>"; }
  if (!db_empty($r14_valor)  ) {
    //echo "<BR> passou aqui R989";
    $r20_rubr = "R989";
    $r14_quant = 0;
    if ($db_debug == true) {
      echo "[carrega_r9xx] 3 - Chamando a função grava_gerf() <br>";
    }
    grava_gerf($area);
  }
  
// Fim do Calculo R989 - DEDUCOES P/IRRF(13.SALARIO)

// Inicio do Calculo das rubricas do desconto do IRRF e(ou) do desconto da Previdencia


  if ($pessoal[$Ipessoal]["r01_tbprev"] == 0 || ( $d08_carnes == "riogrande"
  && strtolower($area) == "pontof13"
  && $pessoal[$Ipessoal]["r01_tbprev"] == 2  ) ) {
    
    // Entra aqui caso de ser Inativo e Pensionista
    // R913 - % IRRF S/SALARIO
    // R914 - % IRRF S/FERIAS
    // R915 - % IRRF S/FERIAS

    $n = 1;
    calc_irf("R913",$area,$sigla,$sigla2,$nro_do_registro,true);
    //echo "carregar9xx - calculou irf r913";
    $n = 2 ;
    if (!( $area == "pontof13" && $cfpess[0]["r11_mes13"] != $mesusu )) {
      calc_irf("R914",$area,$sigla,$sigla2,$nro_do_registro,true);
    }
    $n = 3;
    calc_irf("R915",$area,$sigla,$sigla2,$nro_do_registro,true);
    
      // Ferias 

      if ($n == 3 && (  ( strtolower($cfpess[0]["r11_fersal"]) == "f" && ('t' == $cadferia[0]["r30_paga13"]) ) || 'f' == $cadferia[0]["r30_paga13"])) {
        // ir calculado ferias
        if (($F019+$F023) > 0) {
          $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          $condicaoaux .= " and r31_rubric = ".db_sqlformat("R983" );
          
          global $gerffer;
          if (db_selectmax("gerffer", "select * from gerffer ".bb_condicaosubpes("R31_" ).$condicaoaux )
          && (  ( $opcao_geral == 1
          && strtolower($cadferia[0]["r30_ponto"]) == "s"
          )
          || ( $opcao_geral == 8
          && strtolower($cadferia[0]["r30_ponto"]) =="c"
          && 'f' == $cadferia[0]["r30_paga13"]
          )
          || ( $opcao_geral == 1
          && strtolower($cadferia[0]["r30_ponto"]) == "c"
          && ( $cadferia[0][$r30_proc] < $subpes
          || ( $cadferia[0][$r30_proc] == $subpes
          && 't' == $cadferia[0]["r30_paga13"]
          && db_month($cadferia[0][$r30_peri])==$mesusu  )
          )
          )
          )
          ) {
            //echo "<BR> deleta do gerfsal ou gerfcom as rubricas";
            //echo "<BR> R915 --> % IRRF S/FERIAS  ";
            //echo "<BR> R983 --> BASE IRF FERIAS  (BRUTA)";
            //echo "<BR> R979 --> DEDUCOES P/IRRF (FERIAS)";
            //echo "<BR> pois sera transferidos do calculo das ferias (gerffer) para o gerfsal ou gerfcom estas rubricas";

            if ($area == "pontofs") {
              $condicaoaux  = " and r14_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
              $condicaoaux .= " and r14_rubric in ( 'R915', 'R983', 'R979' )";
              db_delete("gerfsal", bb_condicaosubpes("R14_" ).$condicaoaux );
              
              
            } else if ($area == "pontocom") {
              $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
              $condicaoaux .= " and r48_rubric in ( 'R915', 'R983', 'R979' )";
              db_delete("gerfcom", bb_condicaosubpes("R48_" ).$condicaoaux );
              
            }
            global $gerffer_;
            $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and r31_rubric = ".db_sqlformat("R915" );
            if (db_selectmax("gerffer_", "select * from gerffer ".bb_condicaosubpes("R31_" ).$condicaoaux )) {
              $r14_valor = $gerffer_[0]["r31_valor"];
              if ($db_debug == true) { echo "[carrega_r9xx] 35 - r14_valor = $r14_valor  <br>"; }
              $r14_quant = $gerffer_[0]["r31_quant"];
              $r20_rubr = "R915";
              if ($db_debug == true) {
                echo "[carrega_r9xx] 4 - Chamando a função grava_gerf() <br>";
              }
              grava_gerf($area);
            }
            $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"]);
            $condicaoaux .= " and r31_rubric = ".db_sqlformat("R983" );
            if (db_selectmax("gerffer_", "select * from gerffer ".bb_condicaosubpes("R31_" ).$condicaoaux )) {
              $r14_valor = $gerffer_[0]["r31_valor"];
              if ($db_debug == true) { echo "[carrega_r9xx] 36 - r14_valor = $r14_valor  <br>"; }
              $r14_quant = $gerffer_[0]["r31_quant"];
              $r20_rubr = "R983";
              if ($db_debug == true) {
                echo "[carrega_r9xx] 5 - Chamando a função grava_gerf() <br>";
              }
              grava_gerf($area);
            }
            $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and r31_rubric = ".db_sqlformat("R979" );
            if (db_selectmax("gerffer_", "select * from gerffer ".bb_condicaosubpes("r31_" ).$condicaoaux )) {
              $r14_valor = $gerffer_[0]["r31_valor"];
              if ($db_debug == true) { echo "[carrega_r9xx] 37 - r14_valor = $r14_valor  <br>"; }
              $r14_quant = $gerffer_[0]["r31_quant"];
              $r20_rubr = "R979";
              if ($db_debug == true) {
                echo "[carrega_r9xx] 6 - Chamando a função grava_gerf() <br>";
              }
              grava_gerf($area);
            }
            
            $condicaoaux  = " and r14_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and r14_rubric = ".db_sqlformat("R984" );
            $condicaoaux48  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux48 .= " and r48_rubric = ".db_sqlformat("R984" );
            if (( $area == "pontocom" && !db_selectmax("transacao", "select * from gerfcom ".bb_condicaosubpes("R48_").$condicaoaux48 ) )
            || ($area == "pontofs" && !db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("R14_" ).$condicaoaux ))) {
              
              $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
              $condicaoaux .= " and r31_rubric = ".db_sqlformat("R984" );
              global $gerffer_;
              if (db_selectmax("gerffer_", "select * from gerffer ".bb_condicaosubpes("R31_" ).$condicaoaux )) {
                $r14_valor = $gerffer_[0]["r31_valor"];
                if ($db_debug == true) { echo "[carrega_r9xx] 38 - r14_valor = $r14_valor  <br>"; }
                $r14_quant = $gerffer_[0]["r31_quant"];
                $r20_rubr = "R984";
                // R984 --> VLR REF DEPENDENTES P/ IRF
                if ($db_debug == true) { echo "[carrega_r9xx] 7 - Chamando a função grava_gerf() <br>"; }
                grava_gerf($area);
              }
            }
          }
// Fim do calculo das Rubricas R915, R983 e R979 e R984          
        }
      }
  } else {
    //echo "<BR> r01_tbprev --> ".$pessoal[$Ipessoal]["r01_tbprev"] . " registro --> ".$pessoal[$Ipessoal]["r01_regist"];
    //echo "<br> carregar9xx - R9".db_str((3*$pessoal[$Ipessoal]["r01_tbprev"])-2,2,0,"0");
    // R901 % Inss S/SALARIO DESCONTO -
    // R902 % Inss S/13§ SALARIO DESCONTO -
    // R903 % Inss S/FERIAS DESCONTO -
    // R904 % Faps S/ SALÁRIO DESCONTO -
    // R905 % Faps S/ 13o SALÁRIO DESCONTO -
    // R906 % Faps S/ FÉRIAS DESCONTO -
    // R907 % Inss Consel S/SALARIO DESCONTO -
    // R908 % Inss Consel S/13§ SALARIO DESCONTO -
    // R909 % Inss Consel S/FERIAS DESCONTO
    //echo "<BR> chamando o le_tbprev --> R9".db_str((3*$pessoal[$Ipessoal]["r01_tbprev"])-2,2,0,"0");
    //if ($situacao_funcionario == 5 && $opcao_geral == 1 ) {
    //    le_tbprev("R9".db_str((3*$pessoal[$Ipessoal]["r01_tbprev"])-2,2,0,"0"),$area,$sigla,"r53",$nro_do_registro,true);
    //}else{
        le_tbprev("R9".db_str((3*$pessoal[$Ipessoal]["r01_tbprev"])-2,2,0,"0"),$area,$sigla,$sigla2,$nro_do_registro,true);
    //}
    
  }

// --> Fim do Calculo das rubricas do desconto do IRRF e(ou) do desconto da Previdencia

// --> Inicio do calculo do  R916 - VALE TRANSPORTE e do R922 VALE TRANSP. MES ANTERIOR

  if (( ( $d08_carnes != "viamao" && $dias_pagamento > 0 ) || ( ( $d08_carnes == "viamao" )
  && $dias_pagamento >= 15 ) )) {
    
    if (( $func_em_ferias == false
    && $opcao_geral != 8
    && $opcao_geral != 5 )) {
      
      if($db_debug == true){ echo "[carrega_r9xx] 20 - chamando a função calc_rubrica() <br>"; }
      $r07_valor = calc_rubrica("R916",$area,$sigla,$sigla2,$nro_do_registro,true);

      // R916 VALE TRANSPORTE
      $r14_quant = $quant_pass;

      // vem da funcao vale_transp()

      $r14_valor = ($r07_valor * ($perc_pass / 100));
      if ($db_debug == true) { echo "[carrega_r9xx] 39 - r14_valor = $r14_valor  <br>"; }

      // perc_pass --> vem da funcao vale_transp()
      ///  sandro - vale transporte
      if (db_selectmax("rubrica_vale", "select * from rhrubricas where rh27_rubric = 'R802' and rh27_instit = $DB_instit" )) {
        $valor_ant_vt = $r14_valor ;
        $quant_ant_vt = $r14_quant;
        $r20_rubr = "R802";

        grava_gerf($area);

        $r14_valor = $valor_ant_vt;
        $r14_quant = $quant_ant_vt;
      }

      if (db_selectmax("rubrica_vale", "select * from rhrubricas where rh27_rubric = 'R801' and rh27_instit = $DB_instit" )) {
        $valor_ant_vt = $r14_valor ;
        $quant_ant_vt = $r14_quant;
        $r20_rubr = "R801";
        $r14_valor = $tot_vpass;

        grava_gerf($area);

        $r14_valor = $valor_ant_vt;
        $r14_quant = $quant_ant_vt;
      }


      if (($r14_valor > $tot_vpass)) {
        $r14_valor = $tot_vpass;
        if ($db_debug == true) { echo "[carrega_r9xx] 40 - r14_valor = $r14_valor  <br>"; }
        // vem da funcao vale_transp()
      }
      $r20_rubr = "R916";
      if ($d08_carnes != "riogrande" ||  $area == "pontofs") {
        if ($db_debug == true) { echo "[carrega_r9xx] 8 - Chamando a função grava_gerf() <br>"; }
        grava_gerf($area);
      }
      if ($area == "pontofs") {
        $r14_quant = $dquant_pass;
        // vem da funcao vale_transp()
        $r14_valor = ($r07_valor * ($dperc_pass / 100));
        if ($db_debug == true) { echo "[carrega_r9xx] 41 - r14_valor = $r14_valor  <br>"; }
        // $dperc_pass --> vem da funcao vale_transp()
        if (($r14_valor > $dtot_vpass)) {
          // vem da funcao vale_transp()
          $r14_valor = $dtot_vpass;
          if ($db_debug == true) {  echo "[carrega_r9xx] 42 - r14_valor = $r14_valor  <br>"; }
        }
        $r20_rubr = "R922";
        // R922 VALE TRANSP. MES ANTERIOR
        if ($db_debug == true) { echo "[carrega_r9xx] 9 - Chamando a função grava_gerf() <br>"; }
        grava_gerf($area);
      }
    }
  }
  
// --> Fim do calculo do  R916 - VALE TRANSPORTE e do R922 VALE TRANSP. MES ANTERIOR


// Calcula Todas as Rubricas R9xx referentes ao Salario Familia
  
  if ($area != "pontof13" && $area != "pontocom") {
    
    // Calcula a base p/salario familia baseados nos valores do salario e da complementar se marcada na base B009 e caso 
    // estiver em licenca maternidade acrescenta o valor salario_maternidade se marcadas na base (B009)

    $valor_base_sal_familia = 0;

    // R917 BASE P/SALARIO FAMILIA , Pesquisando no Salário.

    if($db_debug == true){ echo "[carrega_r9xx] 21 - chamando a função calc_rubrica() <br>"; }  
    $valor_base_sal_familia = calc_rubrica("R917",$area,$sigla,$sigla2,$nro_do_registro,true);
    //echo "<BR> 1 -- valor_base_sal_familia --> $valor_base_sal_familia";
    // R917 BASE P/SALARIO FAMILIA , Pesquisando na Complementar.
    if($vlr_sal_saude_ou_acidente > 0 && $dias_pagamento > 0){
       $valor_base_sal_familia += $vlr_sal_saude_ou_acidente;
       //echo "<BR> 1 -- valor_base_sal_familia --> $valor_base_sal_familia vlr_sal_saude_ou_acidente --> $vlr_sal_saude_ou_acidente";
    }
    if ($valor_salario_maternidade > 0 && $dias_pagamento > 0){
      $valor_base_sal_familia += $valor_salario_maternidade;
       //echo "<BR> 2 -- valor_base_sal_familia --> $valor_base_sal_familia valor_salario_maternidade --> $valor_salario_maternidade";
    }    
    if ($area == "pontofs") {
      $condicaoaux = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      global $basesr;
      global $gerfcom_;
      if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        
        for ($Igerfcom=0; $Igerfcom<count($gerfcom_); $Igerfcom++) {
          $condicaoaux  = " where rh54_base = 'B009'";
          // B009 BASE SALARIO FAMILIA
          $condicaoaux .= " and rh54_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
            $condicaoaux .= " and rh54_rubric = ".db_sqlformat($gerfcom_[$Igerfcom]["r48_rubric"] );
            if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
              $valor_base_sal_familia += $gerfcom_[$Igerfcom]["r48_valor"];
            }
          } else {
            $condicaoaux  = " and r09_base = 'B009'";
            // B009 BASE SALARIO FAMILIA
            $condicaoaux .= " and r09_rubric = ".db_sqlformat($gerfcom_[$Igerfcom]["r48_rubric"] );
            if (db_selectmax("basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )) {
              $valor_base_sal_familia += $gerfcom_[$Igerfcom]["r48_valor"];
            }
          }
        }
      }
      //echo "<BR> 2 -- valor_base_sal_familia --> $valor_base_sal_familia";
    }

    // Para acrescentar o valor da licenca maternidade é importante que a rubrica maternidade
    // esteja marcado na base salario familia (B009)
    // O codigo da rubrica maternidade é indicado no cadastro de manutençao da tabela de Previdencia


    $gerar_salario_familia = false;
    //echo "<BR> situacao_funcionario --> $situacao_funcionario dias_pagamento --> $dias_pagamento";
    if ($situacao_funcionario == 5) {
      // Licenca Maternidade
        $condicaoaux = " and r33_codtab = ".db_sqlformat(db_str($pessoal[$Ipessoal]["r01_tbprev"]+2,1,0) );
        global $inssirf_;
        //echo "<BR> r33_rubmat --> ".$inssirf_[0]["r33_rubmat"];
        if (( db_selectmax("inssirf_", "select * from inssirf ".bb_condicaosubpes("r33_" ).$condicaoaux )
        && !db_empty($inssirf_[0]["r33_rubmat"] ) )
        || $dias_pagamento > 0 ) {
          $gerar_salario_familia = true;
        }
      } else {
        $gerar_salario_familia = true;
      }
      //echo "<BR> gerar_salario_familia --> ".($gerar_salario_familia?"1":"2");
      
      if (( $valor_base_sal_familia > 0 && $gerar_salario_familia )) {
        if ($pessoal[$Ipessoal]["r01_regime"] == 2) {
          // D904 MAIOR VALOR DO SALARIO BASE
          $valor_maximo = $D904;
          //echo "<BR> 12 $valor_maximo = $D904;";

        } else {
          if ($d08_carnes != "viamao") {
            // D914 VALOR MAXIMO SALARIO FAM.ESTAT
            $valor_maximo = $D914;
            //echo "<BR> 13 $valor_maximo = $D914;";
          } else {
            $valor_maximo = 999999999;
          }
        }

        //echo "<BR> if ($valor_base_sal_familia <= $valor_maximo) {";
        if ($valor_base_sal_familia <= $valor_maximo) {
          $r14_valor = le_salfamilia($valor_base_sal_familia,$pessoal[$Ipessoal]["r01_regime"],$pessoal[$Ipessoal]["r01_tbprev"]);
          if ($db_debug == true) { echo "[carrega_r9xx] 43 - r14_valor = $r14_valor  <br>"; }
          //echo "<BR> salario familia 1.1 --> $r14_valor";
        } else {
          $r14_valor = 0;
          if ($db_debug == true) { echo "[carrega_r9xx] 44 - r14_valor = $r14_valor  <br>"; }
        }

        // Define o codigo da rubrica que tera o salario familia
        
        // TIPOS DE REGIME : 1 - ESTATUTARIO / 2 - CLT / 3 - EXTRA QUADRO
        
        // R918 SALARIO FAMILIA ESTATUTARIO PROVENTO -
        // R919 SALARIO FAMILIA CLT PROVENTO -
        // R920 SALARIO FAMILIA ESTAT.EXTINCAO PROVENTO -
        $r20_rubr  = "R9".db_str($pessoal[$Ipessoal]["r01_regime"]+17,2,0,"0");
        //echo "<BR> r20_rubr 1.1 --> $r20_rubr";
        if ($area == "pontofe") {
          if ($mpsal) {
            $r14_valor = 0;
            if ($db_debug == true) { echo "[carrega_r9xx] 45 - r14_valor = $r14_valor  <br>"; }
          }
        }

        // TIPOS DE REGIME : 1 - ESTATUTARIO OU  3 - EXTRA QUADRO

        if (($pessoal[$Ipessoal]["r01_regime"] == 1
        || $pessoal[$Ipessoal]["r01_regime"] == 3 )
        && $pessoal[$Ipessoal]["r01_tbprev"] == $cfpess[0]["r11_tbprev"]
        && $r14_valor > 0) {
          
          // Entra aqui quando o funcionario é Estatutario ou Extra Quadro e esta sendo regido pela Previdencia INSS
          // Poucos são os casos em que esta condição é preenchida, o normal é o regime 1 terem Previdencia diferente
          // da INSS

          // Gera a R918 ou R920

          // D904 MAIOR VALOR DO SALARIO BASE
          if ($valor_base_sal_familia < $D904) {
            if ($valor_base_sal_familia <= $D916 ) {
              $r14_valor = $r14_valor - (  $F006_clt * $D919 );
              if ($db_debug == true) { echo "[carrega_r9xx] 46 - r14_valor = $r14_valor  -  dias_pagamento_sf = $dias_pagamento_sf <br>";}
            } else {
              // D906 VALOR MAXIMO P/ SAL FAMILIA
              $r14_valor = $r14_valor - (  $F006_clt * $D906 );
              if ($db_debug == true) { echo "[carrega_r9xx] 47 - r14_valor = $r14_valor -  dias_pagamento_sf = $dias_pagamento_sf <br>";}
            }
            $r14_quant = $r14_quant - $F006_clt;
            if (!db_empty($dias_pagamento_sf) ) {
              $r14_valor = ( $r14_valor / 30 ) * $dias_pagamento_sf;
              if ($db_debug == true) { echo "[carrega_r9xx] 48 - r14_valor = $r14_valor  -  dias_pagamento_sf = $dias_pagamento_sf <br>";}
            }else{
              $r14_valor = 0;
              if ($db_debug == true) { echo "[carrega_r9xx] 51 - r14_valor = $r14_valor  <br>";}
              $r14_quant = 0;
            }
            if ($r14_valor < 0) {
              $r14_valor = 0;
              if ($db_debug == true) { echo "[carrega_r9xx] 49 - r14_valor = $r14_valor  <br>";}
              $r14_quant = 0;
            }
          }
        } else {
          
          // 2 - CLT
          if (!db_empty($dias_pagamento_sf) ) {
            $r14_valor = ( $r14_valor / 30 ) * $dias_pagamento_sf;
            if ($db_debug == true) { echo "[carrega_r9xx] 50 - r14_valor = $r14_valor  <br>";}
          }else{
            $r14_valor = 0;
            if ($db_debug == true) { echo "[carrega_r9xx] 51 - r14_valor = $r14_valor  <br>";}
            $r14_quant = 0;
          }
        }
        // F019 - Numero de dias a pagar no mes
        if ($db_debug == true) { echo "[carrega_r9xx] 10 - Chamando a função grava_gerf() <br>"; }
        grava_gerf($area);

        if (($pessoal[$Ipessoal]["r01_regime"] == 1
        || $pessoal[$Ipessoal]["r01_regime"] == 3 )
        && $pessoal[$Ipessoal]["r01_tbprev"] == $cfpess[0]["r11_tbprev"]
        && $valor_base_sal_familia > 0) {
          // D904 MAIOR VALOR DO SALARIO BASE
          $r14_valor = 0;
          if ($db_debug == true) { echo "[carrega_r9xx] 52 - r14_valor = $r14_valor  <br>"; }
          $r14_quant = 0;
          $r20_rubr  = "R919";
          // R919 SALARIO FAMILIA INSS
          if ($valor_base_sal_familia <= $D904) {
            if ($valor_base_sal_familia <= $D916) {
              $r14_valor = $F006_clt * $D919;
              if ($db_debug == true) { echo "[carrega_r9xx] 53 - r14_valor = $r14_valor  <br>"; }
            } else {
              // D906 VALOR MAXIMO P/ SAL FAMILIA
              $r14_valor = $F006_clt * $D906;
              if ($db_debug == true) { echo "[carrega_r9xx] 54 - r14_valor = $r14_valor  <br>"; }
            }

            if (!db_empty($dias_pagamento_sf)) {
              // Licenca Maternidade
              $r14_valor = ( $r14_valor / 30 ) * $dias_pagamento_sf;
              if ($db_debug == true) { echo "[carrega_r9xx] 55 - r14_valor = $r14_valor  <br>"; }
            }else{
              $r14_valor = 0;
              if ($db_debug == true) { echo "[carrega_r9xx] 51.1 - r14_valor = $r14_valor  <br>";}
              $r14_quant = 0;
            }

            $r14_quant = $F006_clt;
            $r20_rubr  = "R919";
            // R919 SALARIO FAMILIA INSS
          }
          if ($area == "pontofe") {
            if ($mpsal) {
              $r14_valor = 0;
              if ($db_debug == true) { echo "[carrega_r9xx] 56 - r14_valor = $r14_valor  <br>"; }
            }
          }
          
          if ($db_debug == true) { echo "[carrega_r9xx] 11 - Chamando a função grava_gerf() <br>"; }
          grava_gerf($area);
        }
      }
    }
    
// Fim do Calculo de todas as Rubricas R9xx referentes ao Salario Familia
    
// Inicio do Calculo da Base do FGTS --> R991
    
    if($db_debug == true){ echo "[carrega_r9xx] 22 - chamando a função calc_rubrica() <br>"; }
    $r07_valor = calc_rubrica("R991",$area,$sigla,$sigla2,$nro_do_registro,true);
    // R991 BASE F.G.T.S.
    if (( ($pessoal[$Ipessoal]["r01_regime"] == 2) || ($pessoal[$Ipessoal]["r01_regime"] == 4)  )
    && strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) == "a" ) {
      $r14_valor = round($r07_valor,2);
      if ($db_debug == true) { echo "[carrega_r9xx] 57 - r14_valor = $r14_valor  <br>"; }
      $r20_rubr = "R991";
      if ($dias_pagamento > 0  && $situacao_funcionario == 5) {
        $r14_valor += $valor_salario_maternidade;
        if ($db_debug == true) { echo "[carrega_r9xx] 58 - r14_valor = $r14_valor  <br>"; }
      }
     
      if ($db_debug == true) { echo "[carrega_r9xx] 12 - Chamando a função grava_gerf() <br>"; }
      grava_gerf($area);
    }
    
// Fim do Calculo da Base do FGTS --> R991


// Inicio do Calculo do valor Referente aos dependentes para IRF --> R984
    
    // R981 --> BASE IRF SALARIO (BRUTA)
    // R982 --> BASE IRF 13O SAL (BRUTA)
    // R983 --> BASE IRF FERIAS  (BRUTA)

    $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat($r110_regist );
    $condicaoaux .= " and ".$siglag."rubric in ( 'R981', 'R982', 'R983' ) ";
    if (db_selectmax("transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux )) {
      
      if($db_debug == true){ echo "[carrega_r9xx] 23 - chamando a função calc_rubrica() <br>"; }
      $r07_valor = calc_rubrica("R984",$area,$sigla,$sigla2,$nro_do_registro,true);
      $r14_valor = $r07_valor;
      if ($db_debug == true) { echo "[carrega_r9xx] 59 - r14_valor = $r14_valor  <br>"; }
      $r20_rubr = "R984";
      // R984 VLR REF DEPENDENTES P/ IRF
      $r14_quant = $F005;
      
      if ($db_debug == true) { echo "[carrega_r9xx] 13 - Chamando a função grava_gerf() <br>"; }
      grava_gerf($area);
    }
// Fim do Calculo do valor Referente aos dependentes para IRF -> R984
    if ($db_debug == true) {
      echo "[carrega_r9xx]  FIM DO PROCESSAMENTO DA FUNÇÃO carrega_r9xx()... <br><br>"; 
    }
  }
  


/// fim da funcao carrega_r9xx ///
/// calc_rubrica ///

function calc_rubrica($rubrica, $area0, $sigla, $sigla2, $nro_do_registro, $operacao,$formq=null,$valor_=0,$recursivo=0) {

  global $carregarubricas_geral,$rubricas,$r110_lotac,$$area0,$r110_regist;
  global $anousu, $mesusu, $DB_instit, $db_debug ;
 
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  
  global $quais_diversos,$m_rubr, $qtd_chamadas;
  eval($quais_diversos);

  if ($db_debug == true) {
    echo "[calc_rubrica] INICIANDO PROCESSAMENTO DA FUNCAO calc_rubrica";
  }
  //echo "<BR> regist --> $r110_regist  Calculando uma rubrica $rubrica";
  if ( $rubrica == "formq") {

     $rubrica_contem = "+".$formq;
      //echo "<BR> Calculando uma rubrica 12 -> $rubrica_contem";
     
     $posicao = db_at( $rubrica ,$rubrica_contem );

     if ( $posicao != 0 && $valor_ != 0) {

        $rubrica_contem = substr("#".$rubrica_contem,1, $posicao-1 ) .
        db_strtran( db_str( $valor_,10,2), ",",".").
        substr("#".$rubrica_contem , $posicao + 4 );
     }
     // tempo
     $rubrica_contem = str_replace('D','$D',$rubrica_contem);
     $rubrica_contem = str_replace('F','$F',$rubrica_contem);
    //echo "<BR> rubrica contem - $rubrica_contem";

  }else{
    $rubrica_contem = $carregarubricas_geral[$rubrica];
    
  }

  if( ($rubrica_contem == "+") || ($rubrica_contem == "-")){
     $formula = 0;
  }else{
     if( $area0 == "pontofs"){
        if( $sigla2 == "r53" ){
           $area1 = "gerffx";
        }else{
           $area1 = "gerfsal";
        }
     }else if( $sigla2 == "r48"){ 
       $area1 = "gerfcom";
     }else if( $sigla2 == "r22"){ 
       $area1 = "gerfadi";
     }else if( $sigla2 == "r20"){ 
       $area1 = "gerfres";
     }else if( $sigla2 == "r31"){ 
       $area1 = "gerffer";
     }else if( $sigla2 == "r35"){ 
       $area1 = "gerfs13";
     }else if( $sigla2 == "r53"){ 
       $area1 = "gerffx";
     }else if( $sigla2 == "r93"){ 
       $area1 = "gerfprovfer";
     }else if( $sigla2 == "r94"){ 
       $area1 = "gerfprovs13";
     }
     $formula = substr("#".$rubrica_contem,2,strlen($rubrica_contem)-1);
     if( $area0 == "pontofr" || $area0 == "pontoprovfe"|| $area0 == "pontoprovf13"){
      
       if($db_debug == true) { echo "[calc_rubrica] Função: calc_rubrica -> rle_var_bxxx <br>"; }
       $formula = rle_var_bxxx($formula,$area0, $area1, $sigla, $sigla2, $nro_do_registro,$rubrica);
       
     }else{
       
       if ($db_debug == true) { echo "[calc_rubrica]  matric 1 --> $r110_regist  formula --> $formula <br>"; }
       if ($recursivo==0) {
        
          $qtd_chamadas=0;
          $m_rubr = array(); 
          $m_rubr[1] = $rubrica;
          
          if ($db_debug == true) { 
             echo "[calc_rubrica]  Matricula --> $r110_regist Chamando  Rubrica --> ".$m_rubr[1]." --> Formula $formula <br>";
          }
             
       }
       
       $formula = le_var_bxxx($formula,$area0, $area1, $sigla, $sigla2, $nro_do_registro,$rubrica);
       if ($db_debug == true) { 
         echo "[calc_rubrica] Função: calc_rubrica -> le_var_bxxx <br>"; 
         echo "[calc_rubrica] matric 2 --> $r110_regist  formula --> $formula <br>";
       }  
   
     }
     
     if( !db_empty($formula) && $formula != "()"){
      
     if ($db_debug == true) { 
      echo "[calc_rubrica] $formula = operacao($formula) <br>";
        echo "[calc_rubrica] Rubrica --------> $rubrica <br>";
        echo "[calc_rubrica] Formula da funcao calc_rubrica $formula <br>";
     }
   
     ob_start();
     eval('$formula = '.$formula.';');
     db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$formula,$rubrica);
     }
  }
  
  if ($db_debug == true) {
    echo "[calc_rubrica] FIM DO PROCESSAMENTO DA FUNÇÃO calc_rubrica <br><br>";
  }
  
  return $formula;
  
}


/// fim da funcao calc_rubrica ///
/// le_var_bxxx ///




function le_var_bxxx($formula=null, $area0=null, $area1=null, $sigla=null, $sigla2=null, $nro_do_registro=0,$rubrica=null) {
  
  //echo "<BR> ------------------>  Carregando base bxxx $formula rubrica --> $rubrica" ;
  
  global $carregarubricas_geral, $db_debug;
  global $F001, $F002, $F004, $F005, $F006,
  $F007, $F008, $F009, $F010, $F011,
  $F012, $F013, $F014, $F015, $F016,
  $F017, $F018, $F019, $F020, $F021,
  $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  
  global $quais_diversos;
  eval($quais_diversos);
  
  
  global $r110_regist,$chamada_geral_arquivo,$cfpess,$subpes;
  global $cadferia,$pontofe,$pontofr;
  global $anousu, $mesusu, $DB_instit;
  
  global $$area0,$inssirf_base_ferias;
  global $opcao_tipo,$opcao_geral;
  
  if ($db_debug == true) { 
    echo "[le_var_bxxx] INICIANDO PROCESSAMENTO DA FUNÇÃO le_var_bxxx...<br>";
  }
  
  global $array_rubricas,$situacao_funcionario,$vlr_base_prev_ferias_D,$vlr_base_prev_ferias_F,$m_rubr, $qtd_chamadas;
  // $pos_base = db_at("B",$formula);
  $pos_base = strpos("#".$formula,"B")+0;
  if ($pos_base > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ) {
    $base_mae = substr("#".$formula,$pos_base,4);
    if ($db_debug == true) { echo "[le_var_bxxx] 1 - Rubrica --> $rubrica base_mae ---> $base_mae formula --> $formula <br>"; }
    
    while ($pos_base  > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ) {
      
      $base = substr("#".$formula,$pos_base,4);
      if($db_debug == true) { echo "[le_var_bxxx] 2 - Calculando bxxx -> $base  e  formula -> $formula <br>"; }
      
      $condicaoaux  = " and r08_codigo = ".db_sqlformat($base );
      
      global $bases;
      db_selectmax("bases", "select * from bases ".bb_condicaosubpes("r08_").$condicaoaux );
      $abre_base = "(0";
      $valor = 0;
      if (('t' == $bases[0]["r08_calqua"]) && 'f' == $bases[0]["r08_mesant"] ) {
        
        // Calcula Base pela quantidade baseado no ponto de salario ou no ponto de ferias
        
        if ($area0 == "pontofs") {
          $sigla = "r10"   ;
        } else if ($area0 == "pontofe") {
          $sigla = "r29"   ;
        }
        $condicaoaux  = " and ".$sigla."_regist = ".db_sqlformat($r110_regist );
        $condicaoaux .= " order by ".$sigla."_regist,".$sigla."_rubric ";
        Global $transacao1;
        
        if (db_selectmax("transacao1","select * from ".$area0." ".bb_condicaosubpes($sigla."_" ).$condicaoaux ) ) {
          
          for ($i=0; $i<count($transacao1); $i++) {
            eval('$campo_rubrica = $transacao1'."[$i]['".$sigla."_rubric'];");
            eval('$campo_rubrica = $transacao1'."[$i]['".$sigla."_rubric'];");
            eval('$campo_quant   = $transacao1'."[$i]['".$sigla."_quant'];");
            
            $rubrica_contem = $carregarubricas_geral[$campo_rubrica];
            
            $campo_pd       = (substr("#".$rubrica_contem,1,1)=="+"?"1":"2");
            $formula1       = substr("#".$rubrica_contem,2,strlen($rubrica_contem)-1);
            
            global $basesr;
            $achou = false;
            $condicaoaux  = " where rh54_base = ".db_sqlformat($base );
            $condicaoaux .= " and rh54_regist = ".db_sqlformat($r110_regist );
            
            if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
              
              $condicaoaux .= " and rh54_rubric = ".db_sqlformat($campo_rubrica );
              if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
                
                $achou = true;
                if($db_debug == true) { echo " [le_var_bxxx] 3 - achou na base rhbasesreg 5 --> $condicaoaux <br>"; }
                
              }
              
            } else {
              
              $condicaoaux  = " and r09_base = ".db_sqlformat($base );
              $condicaoaux .= " and r09_rubric = ".db_sqlformat($campo_rubrica );
              if (db_selectmax("basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )) {
                
                $achou = true;
                if($db_debug == true) { echo "[le_var_bxxx] 4 - achou na base basesr 5 --> $condicaoaux <br>"; }
                
              }
              
            }
            
            if ($achou && $campo_rubrica != $rubrica) {
              if (db_at($base,"B804-B805-B806") > 0 ) {
                
                $valor += ( $campo_quant / 100 );
              } else {
                $valor += $campo_quant;
              }
            }
            
          }
          
        }
        
      } else if (('t' == $bases[0]["r08_mesant"])) {
        
        if($db_debug == true) {
          echo " [le_var_bxxx] 5 <br>"; 
          echo " [le_var_bxxx] +------------------------------------------------------------------------------------------ <br>";
          echo " [le_var_bxxx]  Calcula a Base com informacoes do mes anterior do ponto de salario calculado<br>";
          echo " [le_var_bxxx]  O calculo da base pode ser por Valor ou por Quantidade<br>";
          echo " [le_var_bxxx] +------------------------------------------------------------------------------------------ <br>";
        }
        
        global $basesr;
        $achou = false;
        $condicaoaux  = " where rh54_base = ".db_sqlformat($base );
        $condicaoaux .= " and rh54_regist = ".db_sqlformat($r110_regist );
        if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
          $achou = true;
          $rubric = "rh54_rubric";
          //echo "<BR> achou na base rhbasesreg 2 --> $condicaoaux";
        } else {
          $condicaoaux  = " and r09_base = ".db_sqlformat($base );
          if (db_selectmax("basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )) {
            $achou = true;
            $rubric = "r09_rubric";
            //echo "<BR> achou na base basesr 2 --> $condicaoaux";
          }
        }
        if ($achou) {
          $mrubr = array();
          global $gerfant;
          $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist );
          //echo "<BR> select * from gerfsal ".bb_condicaosubpesanterior( "r14_" ).$condicaoaux ;
          db_selectmax("gerfant", "select * from gerfsal ".bb_condicaosubpesanterior("r14_" ).$condicaoaux );
          for ($pos=0; $pos<count($gerfant); $pos++) {
            $mrubr[$pos+1] = $gerfant[$pos]["r14_rubric"];
          }
          for ($i=0; $i<count($basesr); $i++) {
            if (!db_empty($basesr[$i][$rubric]) && $basesr[$i][$rubric] != $rubrica ) {
              $pos = db_ascan($mrubr,$basesr[$i]["r09_rubric"]);
              if ($pos != 0 ) {
                
                if ($gerfant[$pos-1]["r14_pd"] == 1) {
                  if ('f' == $bases[0]["r08_calqua"]) {
                    $valor += $gerfant[$pos-1]["r14_valor"];
                    //echo "<BR> r14_rubric --> ".$gerfant[$pos-1]["r14_rubric"] ." r14_valor --> ".$gerfant[$pos-1]["r14_valor"]." r14_quant --> ".$gerfant[$pos-1]["r14_quant"];
                  } else {
                    $valor += $gerfant[$pos-1]["r14_quant"];
                    //echo "<BR> r14_rubric --> ".$gerfant[$pos-1]["r14_rubric"] ." r14_valor --> ".$gerfant[$pos-1]["r14_valor"]." r14_quant --> ".$gerfant[$pos-1]["r14_quant"];
                  }
                } else {
                  if (substr("#".$basesr[$i][$rubric],1,1) == "R" && substr("#".$basesr[$i][$rubric],2,3)+0 > 922) {
                    if ('f' == $bases[0]["r08_calqua"]) {
                      $valor += $gerfant[$pos-1]["r14_valor"];
                      //echo "<BR> r14_rubric --> ".$gerfant[$pos-1]["r14_rubric"] ." r14_valor --> ".$gerfant[$pos-1]["r14_valor"]." r14_quant --> ".$gerfant[$pos-1]["r14_quant"];
                    } else {
                      $valor += $gerfant[$pos-1]["r14_quant"];
                      //echo "<BR> r14_rubric --> ".$gerfant[$pos-1]["r14_rubric"] ." r14_valor --> ".$gerfant[$pos-1]["r14_valor"]." r14_quant --> ".$gerfant[$pos-1]["r14_quant"];
                    }
                  } else {
                    if ('f' == $bases[0]["r08_calqua"]) {
                      $valor -= $gerfant[$pos-1]["r14_valor"];
                      //echo "<BR> r14_rubric --> ".$gerfant[$pos-1]["r14_rubric"] ." r14_valor --> ".$gerfant[$pos-1]["r14_valor"]." r14_quant --> ".$gerfant[$pos-1]["r14_quant"];
                    } else {
                      $valor -= $gerfant[$pos-1]["r14_quant"];
                      //echo "<BR> r14_rubric --> ".$gerfant[$pos-1]["r14_rubric"] ." r14_valor --> ".$gerfant[$pos-1]["r14_valor"]." r14_quant --> ".$gerfant[$pos-1]["r14_quant"];
                    }
                  }
                }
              }
            }
          }
        }
      } else if (('t' == $bases[0]["r08_pfixo"]) && $area0 != "pontofx" ) {
        
        if ($db_debug == true) {
           echo " [le_var_bxxx] 6 <br>"; 
           echo " [le_var_bxxx] +------------------------------------------------------------------------------------------ <br>";
           echo " [le_var_bxxx]  Calcula a base com informacoes do Ponto Fixo calculado<br>";
           echo " [le_var_bxxx]  O calculo da base pode ser por Valor ou por Quantidade<br>";
           echo " [le_var_bxxx] +------------------------------------------------------------------------------------------ <br>";
        }
        
        global $basesr;
        $achou = false;
        $condicaoaux  = " where rh54_base = ".db_sqlformat($base );
        $condicaoaux .= " and rh54_regist = ".db_sqlformat($r110_regist );
        if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
          $achou = true;
          $rubric = "rh54_rubric";
          //echo "<BR> achou na base rhbasesreg 3 --> $condicaoaux";
        } else {
          $condicaoaux  = "select * from basesr ".bb_condicaosubpes("r09_")." and r09_base = ".db_sqlformat($base );
          if (db_selectmax("basesr",$condicaoaux )) {
            $achou = true;
            //echo "<BR> achou na base basesr 3.1 --> $condicaoaux";
            $rubric = "r09_rubric";
          }
        }
        if ($achou ) {
          $mrubr = array();
          $condicaoaux  = " and r53_regist = ".db_sqlformat($r110_regist );
          global $transacao;
          db_selectmax("transacao", "select * from gerffx ".bb_condicaosubpes("r53_" ).$condicaoaux);
          
          for ($pos=0; $pos<count($transacao); $pos++) {
            
            $mrubr[$pos+1] = $transacao[$pos]["r53_rubric"];
            //echo "<BR> matricula --> $r110_regist gerffx rubrica --> ".$mrubr[$pos+1]."   valor --> ".$transacao[$pos]["r53_valor"];
          }
          for ($i=0; $i<count($basesr); $i++) {
            //echo "<BR> basesr 1 rubrica --> ".$basesr[$i][$rubric]." $area1 --> $rubric" ;
            if (!db_empty($basesr[$i][$rubric]) && $basesr[$i][$rubric] != $rubrica ) {
              $pos = db_ascan($mrubr,$basesr[$i][$rubric]);
              //echo "<BR> basesr 1 rubrica --> ".$basesr[$i][$rubric]." posicao --> $pos" ;
              if ($pos != 0 ) {
                //echo "<BR> basesr 2 rubrica --> ".$basesr[$i][$rubric];
                if (strtolower($area1) == "gerffx") {
                  global $transacao;
                  if ($transacao[$pos-1]["r53_pd"] == 1) {
                    if ('f' == $bases[0]["r08_calqua"]) {
                      $valor += $transacao[$pos-1]["r53_valor"];
                    } else {
                      $valor += $transacao[$pos-1]["r53_quant"];
                    }
                  } else {
                    if (substr("#".$basesr[$i][$rubric],1,1) == "R" && db_val(substr("#".$basesr[$i][$rubric],2,3))>922) {
                      if ('f' == $bases[0]["r08_calqua"]) {
                        $valor += $transacao[$pos-1]["r53_valor"];
                      } else {
                        $valor += $transacao[$pos-1]["r53_quant"];
                      }
                    } else {
                      if ('f' == $bases[0]["r08_calqua"]) {
                        $valor -= $transacao[$pos-1]["r53_valor"];
                      } else {
                        $valor -= $transacao[$pos-1]["r53_quant"];
                      }
                    }
                  }
                } else {
                  if ($transacao[$pos-1]["r53_pd"] == 1) {
                    if ('f' == $bases[0]["r08_calqua"]) {
                      $valor += $transacao[$pos-1]["r53_valor"];
                    } else {
                      $valor += $transacao[$pos-1]["r53_quant"];
                    }
                  } else {
                    if (substr("#".$basesr[$i][$rubric],1,1) == "R" && db_val(substr("#".$basesr[$i][$rubric],2,3)) > 922) {
                      if ('f' == $bases[0]["r08_calqua"]) {
                        $valor += $transacao[$pos-1]["r53_valor"];
                      } else {
                        $valor += $transacao[$pos-1]["r53_quant"];
                      }
                    } else {
                      if ('f' == $bases[0]["r08_calqua"]) {
                        $valor -= $transacao[$pos-1]["r53_valor"];
                      } else {
                        $valor -= $transacao[$pos-1]["r53_quant"];
                      }
                    }
                  }
                }
              }
            }
          }
        }
      } else {
        //echo "<BR> le_var_bxxx() : passou aqui base --> $base";
        global $proc_ler_var_bxxx;
        //echo "<BR> le_var_bxxx() :  proc_ler_var_bxxx --> $proc_ler_var_bxxx";
        if ($area1 != "gerffer" && $area1 != "gerfadi") {
          $proc_ler_var_bxxx = true;
        }
        if ($proc_ler_var_bxxx ) {
          $arq_ = $$area0;
     //echo "<BR> area0 --> $area0  area1--> $area1";     
          // Percorre o Ponto (pontofs, pontofx,pontocom, etc..)
          
          for ($ix=0; $ix<count($arq_); $ix++) {
            eval('$campo_rubrica = $arq_'."[$ix]['".$sigla."_rubric'];");
            eval('$campo_quant   = $arq_'."[$ix]['".$sigla."_quant'];");
            eval('$campo_valor   = $arq_'."[$ix]['".$sigla."_valor'];");
            
            //echo "<BR> le_var_bxxx() --> campo_rubrica --> $campo_rubrica  campo_quant   --> $campo_quant  campo_valor   --> $campo_valor";
            
            $rubrica_contem = $carregarubricas_geral[$campo_rubrica];
            
            $campo_pd       = (substr("#".$rubrica_contem,1,1)=="+"?"1":"2");
            
            $formula1       = substr("#".$rubrica_contem,2,strlen($rubrica_contem)-1);
            
            global $basesr;
            $achou = false;
            $condicaoaux  = " where rh54_base = ".db_sqlformat($base );
            $condicaoaux .= " and rh54_regist = ".db_sqlformat($r110_regist );
            if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
              $condicaoaux .= " and rh54_rubric = ".db_sqlformat($campo_rubrica );
              if (db_selectmax("basesr", "select * from rhbasesreg ".$condicaoaux )) {
                $achou = true;
                //echo "<BR> achou na base rhbasesreg 6 --> $condicaoaux";
              }
            } else {
              $condicaoaux  = " and r09_base = ".db_sqlformat($base );
              $condicaoaux .= " and r09_rubric = ".db_sqlformat($campo_rubrica );
              if (db_selectmax("basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )) {
                $achou = true;
                //echo "<BR> achou na base basesr 6 --> $condicaoaux";
              }
            }
            //echo "<BR> campo_rubrica -->  $campo_rubrica != rubrica --> $rubrica";

            if ($achou && $campo_rubrica != $rubrica) {

              // Contabiliza somente as rubricas encontrada na base

              if ($area0 == "pontofe" ) {
                $tpgto = strtolower($arq_[$ix]["r29_tpp"]);
                //echo "<BR>  1 achou na base --> $base matr --> $r110_regist  rubrica --> $campo_rubrica quant --> $campo_quant  valor --> $campo_valor  indice --> $ix formula --> $formula1 tpgto --> $tpgto";
              } else if ($area0 == "pontofr") {
                $tpgto = strtolower($arq_[$ix]["r19_tpp"]);
              }
              if (db_empty($cadferia[0]["r30_proc2"]) ) {
                $r30_proc = "r30_proc1";
                $r30_peri = "r30_per1i";
                $r30_perf = "r30_per1f";
              } else {
                $r30_proc = "r30_proc2";
                $r30_peri = "r30_per2i";
                $r30_peri = "r30_per2f";
              }
              $mes_gozo = substr("#". $cadferia[0][$r30_peri],1,4 )."/".substr("#". $cadferia[0][$r30_peri],6,2);
              
              //echo "<BR> mes_gozo --> $mes_gozo";
              // B004 - BASE IRRF (SALARIO)
              if ($base == "B004" && ($area0 == "pontofs" || $area0 == "pontocom")) {
                if (( strtolower($cfpess[0]["r11_fersal"]) == "f" && ('t' == $cadferia[0]["r30_paga13"]) && $cadferia[0][$r30_proc] ==  $mes_gozo )) {
                  // Não levar em contas as Rubricas Especiais , mesmo marcadas na base B004
                  if ($campo_rubrica == $cfpess[0]["r11_ferias"] ||      // Rubrica onde é pago as férias
                  $campo_rubrica == $cfpess[0]["r11_fer13"] ||      // Rubrica onde é pago um 1/3 de férias Constitucional
                  $campo_rubrica == $cfpess[0]["r11_fer13a"] ||      // Rubrica onde é pago um 1/3 s/ abono de férias Pecuniario
                  $campo_rubrica == $cfpess[0]["r11_ferabo"] ||      // Rubrica onde é pago o abono de férias
                  $campo_rubrica == $cfpess[0]["r11_feradi"] ||      // Rubrica onde é pago o adiantamento de férias
                  $campo_rubrica == $cfpess[0]["r11_fadiab"] ||      // Rubrica onde é descontado as férias pagas no mês anterior
                  $campo_rubrica == $cfpess[0]["r11_ferant"] ||      // Rubrica em que será lançado o abono do mês anterior
                  $campo_rubrica == $cfpess[0]["r11_feabot"] ) {
                    continue;
                  }
                }
              }
              
              if ($area0 == "pontofe") {
                
                // Filtro para o calculo da Base para Imposto de Renda e da Base da Previdencia

                if ('f' ==  $cadferia[0]["r30_paga13"] ) {
                  if ($base == $inssirf_base_ferias) {
                    if ( $tpgto =="a" ) {
                      // As rubricas de Abono não são contabilizados ou não incide Previdência no Abono
                      continue;
                    }
                  } else if ($base == "B005" ) {
                    // B005 - BASE IRRF (FERIAS)
                    if ( $tpgto == "a" ) { // incluido este if para nao calcular abono no IRRF
                      continue;
                    }
                    if ($cadferia[0][$r30_proc] < $subpes && ( $tpgto == "d" )) {
                      // No recalculo (diferença de Férias) as rubricas de Adiantamento não são contabilizados 
                      // não incide na Base IRRF
                      //echo "<BR> 3 passou aqui !!";
                      continue;
                    }
                  }
                } else if (strtolower($cfpess[0]["r11_fersal"]) == "f" ) {

                  // Se Somente 1/3 sim e Pagar como Férias

                  if ($base == $inssirf_base_ferias) {
                      
                    if ( $tpgto == "a" ) {
                      // Não entra na Base da Previdência as Rubricas de Adiantamento de Férias ou Abono
                      //echo "<BR> 4 passou aqui !!";
                      continue;
                    }
                  } else if ($base == "B005" ) {
                    // B005 - BASE IRRF (FERIAS)
                    if ( $tpgto == "a" ) {
                      continue;
                    }
                    if ( $tpgto == "d" && $arq_[$ix]["r29_rubric"] != "R940" && $cadferia[0][$r30_proc] == $subpes) {
                      //echo "<BR> 3 passou aqui !!";
                      continue;
                    }
                    if ( $tpgto == "d" && $cadferia[0][$r30_proc] != $subpes ) {
                      //echo "<BR> 3 passou aqui !!";
                      continue;
                    }
                  } 
                } else if (strtolower($cfpess[0]["r11_fersal"]) == "s") {

                  // Se Somente 1/3 sim e Pagar como Salário
                  // B005 - BASE IRRF (FERIAS)
                  if ($base == "B005" || $base == $inssirf_base_ferias) {
                    if ( $tpgto == "a" ) {
                       continue;
                    }
                    if (( $tpgto != "d" )) {
                      //echo "<BR> passou aqui !!";
                      continue;
                    } else if ($tpgto == "d" && $arq_[$ix]["r29_rubric"] != "R940" ) {
                      // 1/3 ADIANTAMENTO FERIAS
                      // apenas para ferias de so 1/3 -;
                      // para os calculos de descontos sobre adiantamentos;
                      // deve ser apenas sobre o 1/3;
                      //echo "<BR> passou aqui !!";
                      continue;
                    }
                  }
                }
              }
              
              if ($db_debug == true) {
                 echo " [le_var_bxxx] 7 parte que busca os valores ja calculados das rubricas, busca nos gerf... <br>";
                 echo " [le_var_bxxx] 7 opcao_tipo--> $opcao_tipo <br>";
              }
              
              if ($opcao_tipo == 1 || $opcao_geral == 1 || $opcao_geral == 8 || $opcao_geral == 3 || $opcao_geral == 5) {
                
                if($db_debug == true) { echo "[le_var_bxxx] 8 Calculo Geral  ou Salario  ou Complementar ou ferias <br> "; }
                
                $condicaoaux  = " and ".$sigla2."_regist = ".db_sqlformat($r110_regist );
                $condicaoaux .= " and ".$sigla2."_pd = ".db_sqlformat($campo_pd );
                $condicaoaux .= " and ".$sigla2."_rubric = ".db_sqlformat($campo_rubrica );
                if ($area0 == "pontofr" ) {
                  $condicaoaux .= " and upper(".$sigla2."_tpp) = ".db_sqlformat(strtoupper($tpgto)  );
                } else if ($area0 == "pontofe"  ) {
                  
                  if( $rubrica != "R979"){
                      $condicaoaux .= " and upper(".$sigla2."_tpp) = ".db_sqlformat(strtoupper($tpgto)  );
                  }
                }
               if ($db_debug == true) { 
                  echo " [le_var_bxxx] 9 - area --> $area1  condicaoaux --> select * from ".$area1." ".bb_condicaosubpes($sigla2."_" ).$condicaoaux ."<br>"; 
                  echo " [le_var_bxxx] Abate tambem a pensao alimenticia<br>";
                  echo " [le_var_bxxx] R988 DEDUCOES P/IRRF(SALAR./FERIAS) { abate tambem a pensao alimenticia }<br> ";
                  echo " [le_var_bxxx] R989 DEDUCOES P/IRRF(13.SALARIO) { abate tambem a pensao alimenticia }<br> ";
                  echo " [le_var_bxxx] R979 DEDUCOES P/IRRF (FERIAS) { abate tambem a pensao alimenticia }<br> ";
                }
                global $transacao;
                if (db_selectmax("transacao","select * from ".$area1." ".bb_condicaosubpes($sigla2."_" ).$condicaoaux )) {
                
                  if ($db_debug == true) {
                    echo "[le_var_bxxx] 10 - area --> $area1  condicaoaux --> select * from ".$area1." ".bb_condicaosubpes($sigla2."_" ).$condicaoaux."<br>"; 
                  }
                  
                  if ($m_rubr[1] == "R988" || $m_rubr[1] == "R989" || $m_rubr[1] == "R979") {
                    
                    if ($campo_pd == "1") {
                      $valor -= $transacao[0][$sigla2."_valor"];
                    } else {
                      $valor += $transacao[0][$sigla2."_valor"];
                    }
                    if($db_debug == true){  echo "[le_var_bxxx] 11 - R988 : valor --> $valor <br>"; }
                    
                  } else {
                    
                    if ($db_debug == true) { echo "[le_var_bxxx] 12 - Area: $area0 tpgto: ".@$tpgto." <br>"; }
                    if ($area0 == "pontofe" && $m_rubr[1] == "R987") {
                      if($tpgto == "d"){
                        
                         if ($campo_pd == "1") {
                           $vlr_base_prev_ferias_D += round($transacao[0][$sigla2."_valor"],2);
                           if($db_debug == true) { echo "[le_var_bxxx] 13 - $campo_rubrica tipo --> $tpgto + acumula --> $valor valor --> ".round($transacao[0][$sigla2."_valor"],2)."<br>"; }
                         } else {
                           $vlr_base_prev_ferias_D -= round($transacao[0][$sigla2."_valor"],2);
                           if($db_debug == true) { echo "[le_var_bxxx] 14 - $campo_rubrica tipo --> $tpgto - subtraiu --> $valor valor --> ".round($transacao[0][$sigla2."_valor"],2)."<br>"; }
                         }
                        
                      }elseif($tpgto == "f"){
                        
                         if ($campo_pd == "1") {
                           $vlr_base_prev_ferias_F += round($transacao[0][$sigla2."_valor"],2);
                           if($db_debug == true) { echo "[le_var_bxxx] 15 - $campo_rubrica tipo --> $tpgto + acumula --> $valor valor --> ".round($transacao[0][$sigla2."_valor"],2)."<br>"; }
                         } else {
                           $vlr_base_prev_ferias_F -= round($transacao[0][$sigla2."_valor"],2);
                           if($db_debug == true) { echo "[le_var_bxxx] 16 - $campo_rubrica tipo --> $tpgto - subtraiu --> $valor valor --> ".round($transacao[0][$sigla2."_valor"],2)."<br>"; }
                         }

                      }elseif($tpgto =="a"){
                        continue;
                      }
                      // 
                    }
                    
                    if ($campo_pd == "1") {
                      $valor += round($transacao[0][$sigla2."_valor"],2);
                      if($db_debug == true) { echo "[le_var_bxxx] 17 - $campo_rubrica + acumula --> $valor valor --> ".round($transacao[0][$sigla2."_valor"],2)."<br>"; } 
                    } else {
                      if($db_debug == true) { echo "[le_var_bxxx] 18 - $campo_rubrica - subtraiu --> $valor valor --> ".round($transacao[0][$sigla2."_valor"],2)."<br>"; }
                      $valor -= round($transacao[0][$sigla2."_valor"],2);
                      if($db_debug == true) { echo "[le_var_bxxx] 19 - $campo_rubrica - subtraiu --> $valor valor --> ".round($transacao[0][$sigla2."_valor"],2)."<br>"; }
                    }
                  }
                  
                  continue;
                  
                }
                
              }
              
              if($db_debug == true) { echo "[le_var_bxxx] 20 - Parte que calculo as rubricas do Ponto se não encontrar calculado nos gerf... <br>"; }
              if (!db_empty($formula1) && $campo_valor == 0) {

                 if($db_debug == true){ echo "[le_var_bxxx] 21 - chamando a função calc_rubrica() <br>"; }
                 $valor_ = calc_rubrica($campo_rubrica,$area0, $sigla, $sigla2, $nro_do_registro, false,null,0,1);
                 //echo "<BR> bases valores --> $valor_";
                 if($valor_ < 0){
                   continue;
                 }else if($valor_ > 0 ){
                  if ($situacao_funcionario != 5 || ($situacao_funcionario == 5 && $F019 == 0 )) {
                    $valor_ = round($campo_quant * $valor_ ,2);
                    if ($campo_pd == "1") {
                      $valor += $valor_;
                    } else {
                      $valor -= $valor_;
                    }
                  }  
                    continue;
                 }
                //echo "<BR> rubrica_contem --> $rubrica_contem";
                if ((strpos("#".$rubrica_contem,"B")+0) == 0) {
                  if ($situacao_funcionario != 5 || ($situacao_funcionario == 5 && $F019 == 0 )) {
                
                    // Achou na Base mas não esta calculado , calcula aqui. 

                    // Afastado Licenca Gestante
                    ////echo "<BR> 1 passou aqui !!";
                    //echo "F0->".$campo_rubrica."\n";
                    $formula2 ='$formula1 = '.$formula1.";";
                    ob_start();
                    eval($formula2);
                    db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$formula1,$rubrica); 
                    
                    //echo "F2->".$formula1."\n";
                    $resultado = round($campo_quant * $formula1 ,2);
                    //echo "F3->".$resultado."\n";
                    
                    if ($campo_pd == "1") {
                      $valor += $resultado;
                    } else {
                      $valor -= $resultado;
                    }
                  }
                } else {
                  $rubrica = $campo_rubrica;
                  // Caso achou Base na formula da Rubrica, explode esta formula
                  //echo "<BR> 2 passou aqui !!";
                  if (db_empty($campo_quant)) {
                    if ($campo_pd == "1") {
                      $abre_base .= $rubrica_contem;
                    } else {
                      $abre_base .= "-".$rubrica_contem;
                    }
                  } else {
                    if ($campo_pd == "1") {
                      $abre_base .= '+('.$formula1.'*'.$campo_quant.')' ;
                    } else {
                      $abre_base .= '-('.$formula1.'*'.$campo_quant.')' ;
                    }
                    //echo "<BR> abre_base --> $abre_base";
                  }
                }
              } else {
              
                if($db_debug == true) { echo "[le_var_bxxx] 22 - Parte que pega os valores das rubricas do Ponto se não encontrar calculado nos gerf... <br> "; }

                if ($area0 != "pontofe") {
                  
                  if ($situacao_funcionario != 5 || ($situacao_funcionario == 5 && $F019 == 0 )) {
                    if ($campo_pd == "1") {
                      $valor += $campo_valor;
                    } else {
                      $valor -= $campo_valor;
                    }
                  }
                  
                  if($db_debug == true) { 
                     echo " [le_var_bxxx] 23 - rea: $area0 - Situacao_funcionario diferente de $situacao_funcionario ou situacao_funcionario igual a 5 e F019 == 0 <br>";
                     echo " [le_var_bxxx] Valor: $valor <br>"; 
                  }
                  
                } else {
                  
                  if (strtolower($tpgto) == "a" && $base == "B077" && $F020 <= 20) {
                    // ABONO DE FERIAS
                    // B077 BASE FGTS (FERIAS)
                    if (db_empty($F019)) {
                      $F019 = 1;
                    }
                    if ($campo_pd == "1") {
                      $valor += ($campo_valor/$F019)*$F021;
                      $vfgt  += ($campo_valor/$F019)*$F021;
                    } else {
                      $valor -= ($campo_valor/$F019)*$F021;
                      $vfgt  -= ($campo_valor/$F019)*$F021;
                    }
                  } else {
                    if ($campo_pd == "1") {
                      $valor += $campo_valor;
                    } else {
                      $valor -= $campo_valor;
                    }
                  }
                  
                }
              }
            }
          }
          
        }
      }
      if ($valor < 0) {
        $valor = 0;
      }
      $abre_base     .= "+".$valor.")";
      $formula = db_strtran($formula,$base,$abre_base) ;
      
      //echo "<BR>laco formula --> $formula";  // reis
      $pos_base = (strpos("#".$formula,"B")+0);
      //echo "<BR>Sai do pos_base $pos_base  base ".db_val(substr("#".$formula,$pos_base+1,3));
    }
    
    if($db_debug == true) { echo "[le_var_bxxx] 24 - Sai do bxxx $formula  F008 --> $F008 F010 --> $F010 <br>"; }
  }
  
  if($db_debug == true) {
    echo "[le_var_bxxx] FIM DO PROCESSAMENTO DA FUNÇÃO le_var_bxxx <br>";
  }
  return $formula;
}


/// fim da funcao le_var_bxxx ///



/// fim da funcao operacao ///
/// le_tbprev ///
function le_tbprev($r20_rubr=null, $area=null, $sigla=null, $sigla2=null, $nro_do_registro=null, $operacao=null) {
  
  //echo "<BR> Le_tbprev -> $r20_rubr";
  flush();
  
  global $rubrica_maternidade, $rubrica_licenca_saude, $rubrica_acidente, $db_debug;
  global $anousu, $mesusu, $DB_instit,$vlr_desc_prev_ferias_F;
  
  global $r01_tbprevi,$r01_tpcont,$afasta,$pessoal,$Ipessoal,$cfpess,$r110_lotac,$r110_regist;
  
  global $subpes,$opcao_geral,$inssirf_base_ferias,$base_prev,$prev_desc,$d08_carnes,$cadferia;
  
  global $F001, $F002, $F004, $F005, $F006,
  $F007, $F008, $F009, $F010, $F011,
  $F012, $F013, $F014, $F015, $F016,
  $F017, $F018, $F019, $F020, $F021,
  $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  
  
  global $naoencontroupontosalario,$dias_pagamento,$valor_salario_maternidade,$vlr_sal_saude_ou_acidente;
  
  global $quais_diversos;
  eval($quais_diversos);
  
  
  global $n,$desc_prev;
  
  global $r14_valor ,  $r14_quant ,   $r20_rubr, $base_irfb, $situacao_funcionario,$vlr_base_prev_ferias_D,$vlr_base_prev_ferias_F;

  $vlr_base_prev_ferias_D = 0; 
  $vlr_base_prev_ferias_F = 0; 

  if($db_debug == true){
    echo "[le_tbprev] INICIO DO PROCESSAMENTO DA FUNÇÃO le_tbprev... <br>";
  }
  if (db_empty($cadferia[0]["r30_proc2"]) ) {
    $r30_proc = "r30_proc1";
    $r30_peri = "r30_per1i";
    $r30_perf = "r30_per1f";
  } else {
    $r30_proc = "r30_proc2";
    $r30_peri = "r30_per2i";
    $r30_peri = "r30_per2f";
  }
  
  if ($opcao_geral != 1 && $opcao_geral != 8) {
    $situacao_funcionario = situacao_funcionario($pessoal[$Ipessoal]["r01_regist"] );
  }
  
  $lreaj = false;
  $r11_tbprev = trim($cfpess[0]["r11_tbprev"]);
  
  //echo "<BR> verifica_vazio --> ".($verifica_vazio?'1':'0');
  //echo "<BR> r11_tbprev --> ".$cfpess[0]["r11_tbprev"];
  
  //echo "<BR> 1.11 Ipessoal  ---> $Ipessoal";
  //echo "<BR> 1.11 r01_tbprev ---> ".$pessoal[$Ipessoal]["r01_tbprev"];
  flush();
  
  // R901 % Inss S/SALARIO DESCONTO -
  // R902 % Inss S/13§ SALARIO DESCONTO -
  // R903 % Inss S/FERIAS DESCONTO -
  // R904 % Faps S/ SALÁRIO DESCONTO -
  // R905 % Faps S/ 13o SALÁRIO DESCONTO -
  // R906 % Faps S/ FÉRIAS DESCONTO -
  // R907 % Inss Consel S/SALARIO DESCONTO -
  // R908 % Inss Consel S/13§ SALARIO DESCONTO -
  // R909 % Inss Consel S/FERIAS DESCONTO
  if (!db_empty($r11_tbprev ) && $cfpess[0]["r11_tbprev"] == $pessoal[$Ipessoal]["r01_tbprev"]+2 ) {
    
    $basinst = array();
    $basetemp = 0;
    $vlrtemp  = 0;
    $ind      = 0;
    $r20_temp = $r20_rubr;

    if (($area=="pontofe" || $area=="pontoprovfe" ) && $n == 1) {
       $n=2;
    }

    for ($i=1; $i < 4 ; $i++) {
      
      
      // 1  S/SALARIO DESCONTO -
      // 2  S/13§ SALARIO DESCONTO -
      // 3  S/FERIAS DESCONTO -

      if($db_debug == true){ echo "[le_tbprev] 25 - chamando a função calc_rubrica() <br>"; }
      $r07_valor = calc_rubrica($r20_rubr, $area, $sigla, $sigla2,$nro_do_registro, $operacao);
      $ind ++;
      $basinst[$ind] = 0;
      if ($n != 2) {
        if (($area != "pontof13" && $area != "pontoprovf13") || $cfpess[0]["r11_mes13"] == substr("#".$subpes,6,2)+0 ) {
          
          $basinst[$ind] = round($r07_valor,2);
          $basetemp += round($r07_valor,2);
          $r07_valor = calc_tabprev($r07_valor,db_str($pessoal[$Ipessoal]["r01_tbprev"]+2,1),$pessoal[$Ipessoal]["r01_tpcont"]);
          $vlrtemp  += round($r07_valor,2)   ;
        }
      }
      $r20_rubr  = "R9".db_str(substr("#".$r20_rubr,3,2)+1,2,0,"0");
      //echo "<BR> rubrica 1 --> $r20_rubr  valor ---> $r07_valor"; // reis
      if ($area == "pontofs" && $n==2) {
        $n=3;
      }
    }

    $vlrinst = calc_tabprev($basetemp,db_str($pessoal[$Ipessoal]["r01_tbprev"]+2,1),$pessoal[$Ipessoal]["r01_tpcont"]);
    if ($vlrtemp != $vlrinst) {
      $lreaj = true;
    }
    $r20_rubr = $r20_temp;
  }

  if($db_debug == true){ 
     echo " [le_tbprev] Inicio do Calculo das Bases da Previdencia <br> ";
     echo " [le_tbprev] R985 - BASE PREVIDENCIA            --> R904 <br> ";
     echo " [le_tbprev] R986 - BASE PREVIDENCIA (13 SAL)   --> R905 <br> ";
     echo " [le_tbprev] R987 - BASE PREVIDENCIA S/FERIAS   --> R906 <br> ";
  }  

  for ($n=1; $n < 4; $n++) {
    
    // R985 - BASE PREVIDENCIA            --> R904
    // R986 - BASE PREVIDENCIA (13 SAL)   --> R905
    // R987 - BASE PREVIDENCIA S/FERIAS   --> R906
    
    $r20_rubr  = ($n == 1? "R985":($n == 2? "R986":"R987"));
    if (($area=="pontofe" || $area=="pontoprovfe") && $n ==1) {
      // Atencao !!!
      // Quando for o Calculo das Ferias o n comeca em 2
      $n=2;
      $r20_rubr = "R986";
    }
    
    if($db_debug == true){ echo "[le_tbprev] 26 - chamando a função calc_rubrica() <br>"; }
    $r07_valor  = calc_rubrica($r20_rubr, $area, $sigla, $sigla2,$nro_do_registro, $operacao);
    
    if($db_debug == true) {
       echo "[le_tbprev] Calculo da BASE PREVIDENCIA: $r20_rubr n: $n <br>";
       echo "[le_tbprev] Valor da Base de Previdencia Inicio: $r07_valor <br>
             [le_tbprev] Rubrica: $r20_rubr <br>
             [le_tbprev] Area: $area <br>
             [le_tbprev] Sigla: $sigla <br>
             [le_tbprev] Sigla2: $sigla2  <br>
             [le_tbprev] nro_do_registro: $nro_do_registro <br> 
             [le_tbprev] Operacao: $operacao <br>";
    }
     
    
    if (($area == "pontofe"|| $area == "pontoprevfe")  && $n == 3) {
      // R987 - BASE PREVIDENCIA S/FERIAS
      if ($r07_valor < 0) {
        $r07_valor = 0;
      }
    }
    $r07_valor = teto_prev_inativo($r07_valor,db_str($pessoal[$Ipessoal]["r01_tbprev"]+2,1) );
    
    
    $base_prev += $r07_valor;

    if($db_debug == true) { echo "[le_tbprev] Base da Previdencia apos Teto do Inativo: $r07_valor <br>";}
    
    if ($n==1) {                                         // <-- R985 - BASE PREVIDENCIA
      if (( ($situacao_funcionario == 6 ) && !db_empty($rubrica_licenca_saude ) )) {
        
        if ($dias_pagamento > 0 ) {
          
          $r07_valor += $vlr_sal_saude_ou_acidente;
          if($db_debug == true) { echo "[le_tbprev] Acrescenta o Valor da Licenca Saude a Base da Previdencia --> $r07_valor += $vlr_sal_saude_ou_acidente <br>"; }
          
        }
      } else if (( ($situacao_funcionario == 3 ) && !db_empty($rubrica_acidente ) )) {
        
        if ($dias_pagamento > 0 ) {
          
          $r07_valor += $vlr_sal_saude_ou_acidente;
          if($db_debug == true) { echo "[le_tbprev] Acrescenta o Valor da Licenca a Acidente a Base da Previdencia --> $r07_valor += $vlr_sal_saude_ou_acidente <br>"; }
          
        }
      } else if (( ($situacao_funcionario == 5 ) && !db_empty($rubrica_maternidade ) )) {
        
        if ($dias_pagamento > 0 ) {
          
          $r07_valor += $valor_salario_maternidade;
          if($db_debug == true) { echo "[le_tbprev] Acrescenta o Valor da Licenca Maternidade a Base da Previdencia --> $r07_valor += $valor_salario_maternidade <br>"; }
          
        }
      }
      
    }

    $r20_trubr = $r20_rubr;
    $r14_valor = $r07_valor;
    if ($db_debug == true) { echo "[le_tbprev]  60 - r14_valor = $r14_valor  <br>"; }
    
    if (round($r14_valor,2 ) > 0.00 && !( ($area == "pontof13" || $area == "pontoprovf13" ) && $cfpess[0]["r11_mes13"] != substr("#".$subpes,6,2))) {
      $r14_quant = 0;
      
      if ($db_debug == true) {
        echo "[le_tbprev] Valor da Base da Previdencia $r20_rubr gravada: $r14_valor <br>";    
        echo "[le_tbprev] 14 - Chamando a função grava_gerf() <br>"; 
      }
      grava_gerf($area);
    }

    $base_folha_complementar = 0;
    
    global $gerfcom_;
    if ($opcao_geral == 1 && !$naoencontroupontosalario) {
      // Salario ou Rescisao
      if ($n == 1) {
        // R985 - BASE PREVIDENCIA
        $condicaoaux  = " and r48_regist = ".db_sqlformat($r110_regist );
        $condicaoaux .= " and r48_rubric = ".db_sqlformat("R985" );
        if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("R48_" ).$condicaoaux )) {
          $base_folha_complementar = $gerfcom_[0]["r48_valor"];
          $base_crubr = "R953";
          // Base Prev. Salario Complementar
        }
      }
      if ($n == 2) {
        // R986 - BASE PREVIDENCIA (13 SAL)
        $condicaoaux  = " and r48_regist = ".db_sqlformat($r110_regist );
        $condicaoaux .= " and r48_rubric = ".db_sqlformat("R986" );
        if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("R48_" ).$condicaoaux )) {
          $base_folha_complementar = $gerfcom_[0]["r48_valor"];
          $base_crubr = "R954";
          // Base Prev. 13 Salario Complementar
        }
      }
      if ($n == 3) {
        // R987 - BASE PREVIDENCIA S/FERIAS
        $condicaoaux  = " and r48_regist = ".db_sqlformat($r110_regist );
        $condicaoaux .= " and r48_rubric = ".db_sqlformat("R987" );
        if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
          $base_folha_complementar = $gerfcom_[0]["r48_valor"];
          $base_crubr = "R955";
          // Base Prev. Ferias
        }
      }
      if (!db_empty($base_folha_complementar)) {
         $r20_rubr = $base_crubr;
         $r14_valor = $base_folha_complementar;
         if ($db_debug == true) { echo "[le_tbprev] 61 - r14_valor = $r14_valor  <br>"; }
         
         if ($db_debug == true) { 
          echo "[le_tbprev] 15 - Chamando a função grava_gerf() <br>"; 
            echo "[le_tbprev] 2 r14_valor --> $r14_valor <br>";
            echo "[le_tbprev] Transporta para o Salario as Rubricas que Representa a Base da Previdencia da Complementar e/ou 13 SAL e/ou S/FERIAS <br>";
         }
         grava_gerf($area);
      }
    }

    if($db_debug == true) { echo "[le_tbprev]  Valor da  base de Previdencia da_folha complementar --> $base_folha_complementar <br>"; }
    if ($r07_valor + $base_folha_complementar > 0) {
      if ($n == 2 ) {
        
        if ($db_debug == true) { echo "[le_tbprev]  Valor da Base antes de ir na Tabela de Previdencia --> ".($r07_valor + $pessoal[$Ipessoal]["r01_b13fo"] + $base_folha_complementar)."<br>"; }
        $r07_valor  = calc_tabprev($r07_valor + $pessoal[$Ipessoal]["r01_b13fo"] + $base_folha_complementar,db_str($pessoal[$Ipessoal]["r01_tbprev"]+2,1),$pessoal[$Ipessoal]["r01_tpcont"]);
        if ($db_debug == true) { echo "[le_tbprev]  Valor do Desconto Previdenciario --> $r07_valor <br>"; }
        
      } else {
        
        if ($db_debug == true) { echo "[le_tbprev] Valor da Base antes de ir na Tabela de Previdencia --> ".($r07_valor + $pessoal[$Ipessoal]["r01_basefo"] + $base_folha_complementar)."<br>"; }
        $r07_valor  = calc_tabprev($r07_valor + $pessoal[$Ipessoal]["r01_basefo"] + $base_folha_complementar,db_str($pessoal[$Ipessoal]["r01_tbprev"]+2,1),$pessoal[$Ipessoal]["r01_tpcont"]);
        if ($db_debug == true) { echo "[le_tbprev] Valor do Desconto Previdenciario --> $r07_valor <br>"; }

        if($area == "pontofe"){
        //echo "<BR> Valor da Base Previdenciario D--> $vlr_base_prev_ferias_D";
        //echo "<BR> Valor da Base Previdenciario F--> $vlr_base_prev_ferias_F";
           global $perc_inss;
           $vlr_desc_prev_ferias_D  = calc_tabprev($vlr_base_prev_ferias_D ,db_str($pessoal[$Ipessoal]["r01_tbprev"]+2,1),$pessoal[$Ipessoal]["r01_tpcont"]);
           $perc_inss_D = $perc_inss;
           $vlr_desc_prev_ferias_F  = calc_tabprev($vlr_base_prev_ferias_F + $pessoal[$Ipessoal]["r01_basefo"],db_str($pessoal[$Ipessoal]["r01_tbprev"]+2,1),$pessoal[$Ipessoal]["r01_tpcont"]);
           $base_prev = $vlr_base_prev_ferias_F + $pessoal[$Ipessoal]["r01_basefo"] ;
        //echo "<BR> Valor do Desconto Previdenciario D--> $vlr_desc_prev_ferias_D";
        //echo "<BR> Valor do Desconto Previdenciario F--> $vlr_desc_prev_ferias_F";
        }  
      }
    }
    if (($area != "pontof13" && $area != "pontoprovf13") || $cfpess[0]["r11_mes13"] == $mesusu) {
      $r14_valor  = $r07_valor;
      if ($db_debug == true) { echo "[le_tbprev] 62 - r14_valor = $r14_valor  <br>"; }
      //echo "<BR> 3 r14_valor --> $r14_valor";
    } else {
      $r14_valor = 0;
      if ($db_debug == true) { echo "[le_tbprev] 63 - r14_valor = $r14_valor  <br>"; }
      //echo "<BR> 4 r14_valor --> $r14_valor";
      $r07_valor = 0;
    }
    if ($lreaj && $n !=  2 && $r07_valor > 0) {
      if (db_empty($basetemp)) {
        $basetemp = 1;
      }
      $r14_valor = $vlrinst * round(($basinst[$n]/$basetemp),2);
      if ($db_debug == true) { echo "[le_tbprev] 64 - r14_valor = $r14_valor  <br>"; }
      //echo "<BR> 5 r14_valor --> $r14_valor";
    }

// --> Inicio - Desconta o valor ja descontado de previdencia na complementar    

    global $gerfcom_;
    if ($opcao_geral == 1 ) {
      if ($n == 1) {
        $condicaoaux  = " and r48_regist = ".db_sqlformat($r110_regist );
        $condicaoaux .= " and r48_rubric in ( 'R901','R904','R907','R910','R903','R906','R909','R912' )";
        if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("R48_" ).$condicaoaux )) {
          $r14_valor -= $gerfcom_[0]["r48_valor"];
          if ($db_debug == true) { echo "[le_tbprev] 65 - r14_valor = $r14_valor  <br>"; }
          //echo "<BR> 6 r14_valor --> $r14_valor";
        }
      } else if ($n == 2 ) {
        $condicaoaux  = " and r48_regist = ".db_sqlformat($r110_regist );
        $condicaoaux .= " and r48_rubric in ( 'R902','R905','R908','R911')";
        if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("R48_" ).$condicaoaux )) {
          $r14_valor -= $gerfcom_[0]["r48_valor"];
          if ($db_debug == true) { echo "[le_tbprev] 66 - r14_valor = $r14_valor  <br>"; }
          //echo "<BR> 7 r14_valor --> $r14_valor";
        }
      } else if ($n == 3) {
        $condicaoaux  = " and r48_regist = ".db_sqlformat($r110_regist );
        $condicaoaux .= " and r48_rubric in ( 'R903','R906','R909','R912' )";
        if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("R48_" ).$condicaoaux )) {
          $r14_valor -= $gerfcom_[0]["r48_valor"];
          if ($db_debug == true) { echo "[le_tbprev] 67 - r14_valor = $r14_valor  <br>"; }
          //echo "<BR> 8 r14_valor --> $r14_valor";
        }
      }
    }

    
// --> Fim - Desconta o valor ja descontado de previdencia na complementar   


// --> Inicio - diminui do desconto da Previdencia o valor que ja foi recolhido em outra empresa

    //echo "<BR> Valor do Desc da Previdencia menos o valor ja descontado de previdencia na complementar --> $r14_valor";
    if ($n != 2 ) {
      $r14_valor -= $pessoal[$Ipessoal]["r01_descfo"];
      if ($db_debug == true) { echo "[le_tbprev] 68 - r14_valor = $r14_valor  <br>"; }
      $vlr_base_prev_ferias_F -= $pessoal[$Ipessoal]["r01_descfo"];
      //echo "<BR> 10 r14_valor --> $r14_valor r01_descfo -->".$pessoal[$Ipessoal]["r01_descfo"];
    } else {
      $r14_valor -= $pessoal[$Ipessoal]["r01_d13fo"];
      if ($db_debug == true) { echo "[le_tbprev] 69 - r14_valor = $r14_valor  <br>"; }
      //echo "<BR> 11 r14_valor --> $r14_valor";
    }
    if ($r14_valor < 0) {
      $r14_valor = 0;
      if ($db_debug == true) { echo "[le_tbprev] 70 - r14_valor = $r14_valor  <br>"; }
    }
    

    $prev_desc += $r14_valor;
    
// --> Fim - diminui do desconto da Previdencia o valor que ja foi recolhido em outra empresa

    //echo "<BR> Valor do Desc da Previdencia menos o valor ja descontado de previdencia que ja foi recolhido em outra empresa --> $r14_valor";

    //echo "<BR> 12 r01_tbprev --> ".$pessoal[$Ipessoal]["r01_tbprev"];
    // R902 % Inss S/13§ SALARIO DESCONTO -
    // R903 % Inss S/FERIAS DESCONTO -

    $desc_prev = $r14_valor;    

    $r20_rubr = "R9".db_str(( (3*$pessoal[$Ipessoal]["r01_tbprev"])-2)-1+$n ,2,0,"0");
    $r31_valor=0;
    //echo "<BR> Desconto da Previdencia na Rubrica $r20_rubr  n --> $n";
    // esta condicao busca os valores de desconto da previdencia de ferias e coloca no ponto de calculo
    if( $area == "pontofs" || $area == "pontocom" || $area == "pontofe" ){

     global $pontofe,$cadferia;
      if ( $n == 1 ){


         if((   't' == $cadferia[0]["r30_paga13"]
              &&
                $opcao_geral == 1
            )
            || 
            (   'f' == $cadferia[0]["r30_paga13"]
              &&
               (
                 (
                  $opcao_geral == 8
                 && 
                  strtolower($cadferia[0]["r30_ponto"]) =="c"
                 && 
                  $cadferia[0][$r30_proc] == $subpes 
                 )
               ||
                 (
                  $opcao_geral == 1
                 && 
                  strtolower($cadferia[0]["r30_ponto"]) =="s"
                 )
               ||
                 (
                  $opcao_geral == 1
                 && 
                  strtolower($cadferia[0]["r30_ponto"]) =="c"
                 && 
                  $cadferia[0][$r30_proc] < $subpes 
                 )
               )
           )
          ){
             global $r31_anousu,$r31_mesusu,$r31_regist,$r31_rubric,$r31_valor,$r31_quant,$r31_lotac;

             $condicaoaux = " and r31_rubric in ( 'R903','R906','R909','R912' ) and r31_tpp = 'F' and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
             $sqlfer = " select r31_anousu,r31_mesusu,r31_regist,r31_rubric,r31_valor,r31_quant,r31_lotac
                         from gerffer
                         ".bb_condicaosubpes("R31_" ).$condicaoaux;
             $resfer = pg_exec($sqlfer);
               //echo "<BR> 1 r30_paga13 --> ".($cadferia[0]["r30_paga13"]=='t'?"1":"2"). " opcao_geral --> ".$opcao_geral;
             if( pg_numrows($resfer) > 0 ){
               db_fieldsmemory($resfer,0);
               //echo "<BR> 2 r30_paga13 --> ".($cadferia[0]["r30_paga13"]=='t'?"1":"2"). " opcao_geral --> ".$opcao_geral;
               if( 't' == $cadferia[0]["r30_paga13"]
                   &&
                   $opcao_geral == 1
               ){
                   if ($db_debug == true) {
                     echo "[le_tbprev] 13 - Insert: Gerfsal<br>";
                   }
                   $sqlfer = "insert into gerfsal values ($r31_anousu,$r31_mesusu,$r31_regist,'$r31_rubric',$r31_valor,2,$r31_quant,'$r31_lotac',0,$DB_instit)";
               }else{
                   if($area == "pontocom") {
                      $sqlfer = "insert into gerfcom values ($r31_anousu,$r31_mesusu,$r31_regist,'$r31_rubric',$r31_valor,2,$r31_quant,'$r31_lotac',0,$DB_instit)";
                   }else{
                      if ($db_debug == true) {
                         echo "[le_tbprev] 14 - Insert: Gerfsal<br>";
                      }
                      $sqlfer = "insert into gerfsal values ($r31_anousu,$r31_mesusu,$r31_regist,'$r31_rubric',$r31_valor,2,$r31_quant,'$r31_lotac',0,$DB_instit)";
                   }
               } 
                global $tot_desc;
                $tot_desc += $r31_valor;
                if ($db_debug == true) { echo "[le_tbprev] 28 - tot_desc: $tot_desc<br>"; }
                $resfer = pg_exec($sqlfer);
                if($resfer==false){
                  //echo "erro";exit;
                }
            } 
         }
         //echo "<BR> 1 gravando na Rubrica $r20_rubr --> $r14_valor -= $r31_valor"; 
         $r14_valor -= $r31_valor;
         if ($db_debug == true) { echo "[le_tbprev] 72 - r14_valor = $r14_valor  <br>"; }
         $desc_prev -= $r31_valor;
         //echo "<BR> 2 gravando na Rubrica $r20_rubr --> $r14_valor -= $r31_valor";

         if ($db_debug == true) { echo "[le_tbprev] 16 - Chamando a função grava_gerf() <br>"; }
         grava_gerf($area);

      }else if ( $n==3  ){
        //echo "<BR><BR> F019 --> $F019    Numero de dias a pagar de ferias"; 
        //echo "<BR> F020 --> $F020    Numero de dias de abono p/ pagar de ferias";
        //echo "<BR> F021 --> $F021    Numero de dias p/ calc do FGTS no mes";
        //echo "<BR> F023 --> $F023    Numero de dias de Adiantamento de ferias";
        if ( $F023 != 0 ){

          $r14_valor_diferenca = $r14_valor;
          $r14_quant_diferenca = $r14_quant;
          $r14_quant = $perc_inss_D;
          if ( $F019 != 0 ){
            //echo "<BR><BR> gravando na Rubrica $r20_rubr --> $r14_valor_r906 = ( ( $r14_valor_diferenca / 30 ) * ( $F023 ) ) tipo --> D"; 
            $r14_valor = $vlr_desc_prev_ferias_D;
            if ($db_debug == true) { echo "[le_tbprev] 73 - r14_valor = $r14_valor  <br>"; }      
            //echo "<BR><BR> gravando na Rubrica $r20_rubr --> $r14_valor tipo --> D";
          }

          if ($db_debug == true) { echo "[le_tbprev] 17 - Chamando a função grava_gerf() <br>"; }
          grava_gerf($area,"D");
        }

        if ( $F019 != 0 ){
           
           if ( $F023 != 0 ){
             $r14_quant  = $r14_quant_diferenca ;
             //echo "<BR> gravando na Rubrica $r20_rubr --> $r14_valor = $r14_valor_diferenca - $r14_valor_r906  tipo --> F"; 
              $r14_valor = $vlr_desc_prev_ferias_F;
              if ($db_debug == true) { echo "[le_tbprev] 74 - r14_valor = $r14_valor  <br>"; }       
              $prev_desc = $r14_valor;
            //echo "<BR> gravando na Rubrica $r20_rubr --> $r14_valor tipo --> F";
           }   
           
           if ($db_debug == true) { echo "[le_tbprev] 18 - Chamando a função grava_gerf() <br>"; }
           grava_gerf($area,"F");
         }  
      }else{

        //echo "<BR> gravando na Rubrica $r20_rubr --> $r14_valor";
        if ($db_debug == true) { echo "[le_tbprev] 19 - Chamando a função grava_gerf() <br>"; } 
        grava_gerf($area);

      }
    }else{

     if ($db_debug == true) { echo "[le_tbprev] 20 - Chamando a função grava_gerf() <br>"; }
     grava_gerf($area);

    }
    
    //////echo "<BR> 12 r20_rubr --> $r20_rubr  n --> $n r01_tbprev --> ".$pessoal[$Ipessoal]["r01_tbprev"];
    //grava_gerf($area);
    //////echo "<BR> 12.1 r14_valor --> $r14_valor";
    
    // Fim do Calculo das Rubricas : ( R901 ou R904 ou R907 ou R910)
    //                               ( R902 ou R905 ou R908 ou R911)
    //                               ( R903 ou R906 ou R909 ou R912)
    
    $r07_rubr  = $r20_rubr;

// --> Inicio do Calculo das Rubricas IRRF (R913, R914 e R915)

    $r20_rubr  = "R9".($n==1?"13":($n==2?"14":"15"));


// --> Inicio - nao calcular para adiantamento de 13.salario

    //echo "<BR> 15 r20_rubr --> $r20_rubr   valor --> $r14_valor"; // reis
    if (!( ($area == "pontof13" || $area == "pontoprovf13" ) && $cfpess[0]["r11_mes13"] != $mesusu )) {
      //echo "<BR> rubrica 2 --> $r20_rubr   valor --> $r14_valor"; // reis
      
      //echo "<BR><BR> Inicio do Calculo do IRRF para rubrica --> $r20_rubr"; // reis
      calc_irf($r20_rubr, $area, $sigla, $sigla2,$nro_do_registro, $operacao);
      //echo "<BR><BR> Fim do Calculo do IRRF <BR>"; // reis
    }

// --> Fim - nao calcular para adiantamento de 13.salario

// --> Fim do Calculo das Rubricas IRRF (R913, R914 e R915)

    $r20_rubr  = "R9".db_str(db_val(substr("#".$r07_rubr,3,2))+1,2,0,"0");
    ////echo "<BR> rubrica 3 --> $r20_rubr"; // reis
    if ($d08_carnes != "riogrande" ) {

      // se for salario nao calcular bases de ferias

      if (( $area == "pontofs" || $area == "pontocom" ) && $n==2) {
        $n+=1;
        $r20_rubr = "R9".db_str(db_val(substr("#".$r07_rubr,3,2))+2,2,0,"0");
        //////echo "<BR> rubrica 4 --> $r20_rubr"; // reis
      }

      // Ferias 

      if ($n == 3 && strtolower($cfpess[0]["r11_fersal"]) == "f" ) {
        // ir calculado ferias
        if (($F019+$F023) > 0) {
          $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
          $condicaoaux .= " and r31_rubric = ".db_sqlformat("R983" );
          
          global $gerffer;
          if(db_selectmax("gerffer", "select * from gerffer ".bb_condicaosubpes("R31_" ).$condicaoaux )
          && 
            (  
             (   't' == $cadferia[0]["r30_paga13"]
               &&
                 $opcao_geral == 1
             )
             || 
             (   'f' == $cadferia[0]["r30_paga13"]
               &&
                (
                  (
                   $opcao_geral == 8
                  && 
                   strtolower($cadferia[0]["r30_ponto"]) =="c"
                  && 
                   $cadferia[0][$r30_proc] == $subpes 
                  )
                ||
                  (
                   $opcao_geral == 1
                  && 
                   strtolower($cadferia[0]["r30_ponto"]) =="s"
                  )
                ||
                  (
                   $opcao_geral == 1
                  && 
                   strtolower($cadferia[0]["r30_ponto"]) =="c"
                  && 
                   $cadferia[0][$r30_proc] < $subpes 
                  )
                )
             )   
            )
          ) {
            //echo "<BR> deleta do gerfsal ou gerfcom as rubricas";
            //echo "<BR> R915 --> % IRRF S/FERIAS  ";
            //echo "<BR> R983 --> BASE IRF FERIAS  (BRUTA)";
            //echo "<BR> R979 --> DEDUCOES P/IRRF (FERIAS)";
            //echo "<BR> pois sera transferidos do calculo das ferias (gerffer) para o gerfsal ou gerfcom estas rubricas";

//          || ( $opcao_geral == 1
//          && strtolower($cadferia[0]["r30_ponto"]) == "c"
//          && ( $cadferia[0][$r30_proc] < $subpes
//          || ( $cadferia[0][$r30_proc] == $subpes
//          && 't' == $cadferia[0]["r30_paga13"]
//          && db_month($cadferia[0][$r30_peri])==$mesusu  )
//          )
//          )
            if ($area == "pontofs") {
              $condicaoaux  = " and r14_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
              $condicaoaux .= " and r14_rubric in ( 'R915', 'R983', 'R979' )";
              db_delete("gerfsal", bb_condicaosubpes("R14_" ).$condicaoaux );
              
              
            } else if ($area == "pontocom") {
              $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
              $condicaoaux .= " and r48_rubric in ( 'R915', 'R983', 'R979' )";
              db_delete("gerfcom", bb_condicaosubpes("R48_" ).$condicaoaux );
              
            }
            global $gerffer_;
            $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and r31_rubric = ".db_sqlformat("R915" );
            if (db_selectmax("gerffer_", "select * from gerffer ".bb_condicaosubpes("R31_" ).$condicaoaux )) {
              $r14_valor = $gerffer_[0]["r31_valor"];
              if ($db_debug == true) { echo "[le_tbprev] 75 - r14_valor = $r14_valor  <br>"; }
              $r14_quant = $gerffer_[0]["r31_quant"];
              $r20_rubr = "R915";
              
              if ($db_debug == true) { echo "[le_tbprev] 21 - Chamando a função grava_gerf() <br>"; }
              grava_gerf($area);
            }
            $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"]);
            $condicaoaux .= " and r31_rubric = ".db_sqlformat("R983" );
            if (db_selectmax("gerffer_", "select * from gerffer ".bb_condicaosubpes("R31_" ).$condicaoaux )) {
              $r14_valor = $gerffer_[0]["r31_valor"];
              if ($db_debug == true) { echo "[le_tbprev] 76 - r14_valor = $r14_valor  <br>"; }
              $r14_quant = $gerffer_[0]["r31_quant"];
              $r20_rubr = "R983";
              
              if ($db_debug == true) { echo "[le_tbprev] 22 - Chamando a função grava_gerf() <br>"; } 
              grava_gerf($area);
            }
            $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and r31_rubric = ".db_sqlformat("R979" );
            if (db_selectmax("gerffer_", "select * from gerffer ".bb_condicaosubpes("r31_" ).$condicaoaux )) {
              $r14_valor = $gerffer_[0]["r31_valor"];
              if ($db_debug == true) { echo "[le_tbprev] 77 - r14_valor = $r14_valor  <br>"; }
              $r14_quant = $gerffer_[0]["r31_quant"];
              $r20_rubr = "R979";
              
              if ($db_debug == true) { echo "[le_tbprev] 23 - Chamando a função grava_gerf() <br>"; }
              grava_gerf($area);
            }
            
            $condicaoaux  = " and r14_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and r14_rubric = ".db_sqlformat("R984" );
            $condicaoaux48  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux48 .= " and r48_rubric = ".db_sqlformat("R984" );
            if (( $area == "pontocom" && !db_selectmax("transacao", "select * from gerfcom ".bb_condicaosubpes("R48_").$condicaoaux48 ) )
            || ($area == "pontofs" && !db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("R14_" ).$condicaoaux ))) {
              
              $condicaoaux  = " and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
              $condicaoaux .= " and r31_rubric = ".db_sqlformat("R984" );
              global $gerffer_;
              if (db_selectmax("gerffer_", "select * from gerffer ".bb_condicaosubpes("R31_" ).$condicaoaux )) {
                $r14_valor = $gerffer_[0]["r31_valor"];
                if ($db_debug == true) { echo "[le_tbprev] 78 - r14_valor = $r14_valor  <br>"; }
                $r14_quant = $gerffer_[0]["r31_quant"];
                $r20_rubr = "R984";
                // R984 --> VLR REF DEPENDENTES P/ IRF
                
                if ($db_debug == true) { echo "[le_tbprev] 24 - Chamando a função grava_gerf() <br>"; }
                grava_gerf($area);
              }
            }
          }
// Fim do calculo das Rubricas R915, R983 e R979 e R984          
        }
      }
    }
  }
  // R992 BASE PREVIDENCIA
  // R990 BASE PREVID.PATRONAL (21%INSS)
  // R993 DESC PREVIDENCIA
  if($db_debug == true){
    echo "[le_tbprev] chamando a função grava_base_prev($area)... <br>";
  }  
  grava_base_prev($area);

  if($db_debug == true){
    echo "[le_tbprev] FIM DO PROCESSAMENTO DA FUNÇÃO le_tbprev... <br>";
  }  
}


/// fim da funcao le_tbprev ///
/// rle_var_bxxx ///
function rle_var_bxxx ($formula=null, $area0=null, $area1=null, $sigla=null, $sigla2=null, $nro_do_registro=null,$rubrica=null){


  global $carregarubricas_geral,$pessoal,$Ipessoal;
  global $anousu, $mesusu, $DB_instit;
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;

  global $quais_diversos;
  eval($quais_diversos);

  
 global $r110_regist;

 global $$area0,$inssirf_base_ferias;
 global $opcao_tipo;



  global $subpes,$rescisao,$bases, $transacao2, $gerfant, $transacao1,$basesr;
  
//echo "<BR> formula 1 --> $formula";
 $pos_base = strpos("#".$formula,"B")+0;
 if( $pos_base > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ){
   $base_mae = substr("#".$formula,$pos_base,4);
   while( $pos_base  > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ){

      
      $base = substr("#".$formula,$pos_base,4);
  
//echo "<BR> base 1 --> $base";

  $condicaoaux  = " and r08_codigo = ".db_sqlformat( $base );
  db_selectmax( "bases", "select * from bases ".bb_condicaosubpes("R08_").$condicaoaux );

  $abre_base = "(0";
  $n1 =0;
  $valor =0;
        $monta_formula = "(0";
  
//  if( ($base == $inssirf_base_ferias && !('t' == $rescisao[0]["r59_finss"])) 

        // R987 --> ( B002 ou B039 ) // BASE PREVIDENCIA S/FERIAS
  // B003 --> BASE PREVIDENCIA (13 SALARIO)
  // B005 --> BASE IRRF (FERIAS)
  // B006 --> BASE IRRF (13 SALARIO)
  
//echo "<BR><BR>  rescisao ---> ".print_r($rescisao);
//echo "<BR>f( ($rubrica == 'R987' && ('f' == ".($rescisao[0]["r59_finss"]?"1":"2")."))";
  if( ($rubrica == "R987" && ('f' == $rescisao[0]["r59_finss"]))    // Incide Ferias Previdencia
     ||  ($base == "B003" && ('f' == $rescisao[0]["r59_13inss"]))   // Incide 13 Previdencia
     ||  ($base == "B005" && ('f' == $rescisao[0]["r59_firrf"]))    // Incide Ferias IRRF
     ||  ($base == "B006" && ('f' == $rescisao[0]["r59_13irrf"]))){ // Incide 13 IRRF 
     $valor = 0;
//     echo "<BR> 1 passou aqui !!!!!!!!";
//echo "<BR> base 1.1 --> $base";
  }else{
//     echo "<BR> 2 passou aqui !!!!!!!!";
//echo "<BR> base 1.2 --> $base";

     if( ('t' == $bases[0]["r08_calqua"]) && 'f' == $bases[0]["r08_mesant"] ){
        // B801 --> BASE P/ABONO FERIAS COLETIVAS
              // B807 --> INSALUB/PENOSID/PERICULOSIDADE
        // B808 --> HORA EXTRA(COMPOSICAO DE BASE)
        // B809 --> HORA EXTRA (INSAL/PERIC/PENOS)
        
        $condicaoaux = " and ".$sigla."_regist = ".db_sqlformat( $r110_regist );
        $condicaoaux .= " order by ".$sigla."_regist, ".$sigla."_rubric ";
        global $transacao1;
        db_selectmax( "transacao1", "select * from ".$area0." ".bb_condicaosubpes( $sigla."_" ).$condicaoaux );

        for($i=0;$i<count($transacao1);$i++){
     
                 eval('$campo_rubrica = $transacao1'."[$i]['".$sigla."_rubric'];");
                 eval('$campo_quant   = $transacao1'."[$i]['".$sigla."_quant'];");
                 eval('$campo_valor   = $transacao1'."[$i]['".$sigla."_valor'];");
           $rubrica_contem = $carregarubricas_geral[$campo_rubrica];
     
                 $campo_pd       = (substr("#".$rubrica_contem,1,1)=="+"?"1":"2");
                 $formula2       = '$formula1 = '.substr("#".$rubrica_contem,2,strlen($rubrica_contem)-1).";";

           global $basesr;
           $achou = false;
                 $condicaoaux  = " where rh54_base = ".db_sqlformat( $base );
                 $condicaoaux .= " and rh54_regist = ".db_sqlformat( $r110_regist );
                 if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
                    $condicaoaux .= " and rh54_rubric = ".db_sqlformat( $campo_rubrica );
                    if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
          $achou = true;
        }  
           }else{
                   $condicaoaux  = " and r09_base = ".db_sqlformat( $base );
                   $condicaoaux .= " and r09_rubric = ".db_sqlformat( $campo_rubrica );
                   if( db_selectmax( "basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
         $achou = true;
       }
           }
           if($achou){
        if( db_at($base,"B804-B805-B806") > 0){
      $valor += ( $campo_quant / 100 );
        }else{
      $valor += $campo_quant;
        }
     }
        }
     }else if( ('t' == $bases[0]["r08_mesant"])){ 
        global $basesr;
        $achou = false;
              $condicaoaux  = " where rh54_base = ".db_sqlformat( $base );
              $condicaoaux .= " and rh54_regist = ".db_sqlformat( $r110_regist );
              if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
           $achou = true;
     $rubric = "rh54_rubric";
//     echo "<BR> base rhbasesreg 4 --> $condicaoaux";
        }else{
                 $condicaoaux  = " and r09_base = ".db_sqlformat( $base );
                 if( db_selectmax( "basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
       $achou = true;
       $rubric = "r09_rubric";
//     echo "<BR> base basesr 4 --> $condicaoaux";
     }
        }
        if($achou){

     for($i=0;$i<count($basesr);$i++){
        if( !db_empty($basesr[$i][$rubric])){
           $condicaoaux = " and r14_regist = ".db_sqlformat( $r110_regist );
           $condicaoaux .= " and r14_rubric = ".db_sqlformat( $basesr[$i][$rubric] );
           
           if( db_selectmax( "gerfant", "select * from gerfsal ".bb_condicaosubpesanterior( "r14_" ).$condicaoaux )){
        if( $gerfant[0]["r14_pd"] == 1){
           if( !('t' == $bases[0]["r08_calqua"])){
        $valor += $gerfant[0]["r14_valor"];
           }else{
        $valor += $gerfant[0]["r14_quant"];
           }
        }else{
           if( substr("#".$basesr[$i][$rubric],1,1) == "R" && db_val(substr("#".$basesr[$i][$rubric],2,3)) > 922){
        if( !('t' == $bases[0]["r08_calqua"])){
           $valor += $gerfant[0]["r14_valor"];
        }else{
           $valor += $gerfant[0]["r14_quant"];
        }
           }else{
        if( !('t' == $bases[0]["r08_calqua"])){
           $valor -= $gerfant[0]["r14_valor"];
        }else{
           $valor -= $gerfant[0]["r14_quant"];
        }
           }
        }
           }
        }
     }
        }
     }else if( ('t' == $bases[0]["r08_pfixo"]) && $area0 != "pontofx"){ 
        global $basesr;
        $achou = false;
              $condicaoaux  = " where rh54_base = ".db_sqlformat( $base );
              $condicaoaux .= " and rh54_regist = ".db_sqlformat( $r110_regist );
              if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
           $achou = true;
     $rubric = "rh54_rubric";
//     echo "<BR> base rhbasesreg 1--> $condicaoaux";
        }else{
                 $condicaoaux  = " and r09_base = ".db_sqlformat( $base );
                 if( db_selectmax( "basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
       $achou = true;
     $rubric = "r09_rubric";
//     echo "<BR> base basesr 1--> $condicaoaux";
     }
        }
        if($achou){
     for($i=0;$i<count($basesr);$i++){
        if( !db_empty($basesr[$i][$rubric])){
           $condicaoaux  = " and r53_regist = ".db_sqlformat( $r110_regist );
           $condicaoaux .= " and r53_rubric = ".db_sqlformat( $basesr[$i][$rubric] );
           global $transacao2;
           if( db_selectmax( "transacao2", "select * from gerffx ".bb_condicaosubpes( "r53_" ).$condicaoaux )){
        if( $transacao2[0]["r53_pd"] == 1){
           if( !('t' == $bases[0]["r08_calqua"])){
        $valor += $transacao2[0]["r53_valor"];
           }else{
        $valor += $transacao2[0]["r53_quant"];
           }
        }else{
           if( substr("#".$basesr[$i][$rubric],1,1) == "R" 
          && db_val(substr("#".$basesr[$i][$rubric],2,3)) > 922){
        if( !('t' == $bases[0]["r08_calqua"])){
           $valor += $transacao2[0]["r53_valor"];
        }else{
           $valor += $transacao2[0]["r53_quant"];
        }
           }else{
        if( !('t' == $bases[0]["r08_calqua"])){
           $valor -= $transacao2[0]["r53_valor"];
        }else{
           $valor -= $transacao2[0]["r53_quant"];
        }
           }
        }
           }
        }
     }
        }
     }else{
//echo "<BR> base 1.3 --> $base";
        for($i=0;$i<count($$area0);$i++){
     
                 eval('$campo_rubrica = $'.$area0."[$i]['".$sigla."_rubric'];");
//echo "<BR> campo_rubrica --> $campo_rubrica";
     if($campo_rubrica > "6000" && $campo_rubrica < "8000"  // rubricas de rescisao  
                    && ((('f' == $rescisao[0]["r59_rinss"]) && $base == "B001")   // Incide Rescisao na Base de Previdencia
            || (('f' == $rescisao[0]["r59_rirrf"]) && $base == "B004") // Incide Rescisao na Base de IRRF
            || (('f' == $rescisao[0]["r59_rfgts"]) && ($base == "B007" || $base == "B077")) )){ // Incide Rescisao na Base de FGTS
//echo "<BR> passou aqui 1";
        continue;
     }
     
     if( db_at($base, "B007-B077") > 0
         && ( ( ('f' == $rescisao[0]["r59_ffgts"]) 
           && ( ( $campo_rubrica >="2000" && $campo_rubrica <"4000" )
             || $campo_rubrica == "R931"  
             || $campo_rubrica == "R932" ) ) )
         || ($pessoal[$Ipessoal]["r01_regime"] == 2 && ('f' == $rescisao[0]["r59_13fgts"]) && ( $campo_rubrica >="4000" && $campo_rubrica < "6000" ) ) 
         || ($pessoal[$Ipessoal]["r01_regime"] == 2 && ('f' == $rescisao[0]["r59_rfgts"])  && ( $campo_rubrica >="6000" && $campo_rubrica < "8000" ))){
//echo "<BR> passou aqui 2 --> ".(('t' == $rescisao[0]["r59_13fgts"]) ?"1":"2");
//echo "<BR> passou aqui 2 --> ".(!('t' == $rescisao[0]["r59_13fgts"]) ?"1":"2");
//echo "<BR> passou aqui 2 --> ".(!('t' == $rescisao[0]["r59_13fgts"]) && ( $campo_rubrica >="4000" && $campo_rubrica < "6000" )?"1":"2"); 
         continue;
     }
     
//echo "<BR> passou aqui 3";
                 eval('$campo_quant   = $'.$area0."[$i]['".$sigla."_quant'];");
                 eval('$campo_valor   = $'.$area0."[$i]['".$sigla."_valor'];");
           $conteudo_rubrica = "R".$campo_rubrica;
           $rubrica_contem = $carregarubricas_geral[$campo_rubrica];
                 $campo_pd       = (substr("#".$rubrica_contem,1,1)=="+"?"1":"2");
                 $formula1 = substr("#".$rubrica_contem,2,strlen($rubrica_contem)-1);
  
     //if( $d08_carnes == "riogrande"){
     //   if( (( campo_rubrica = "0603" ;){
     //            $|| ( campo_rubrica = "0604" && base != "b014" )) ;;
     //       $&& calcula_valor_604 = true );
     //      $skip;
     //      loop
     //   }
     //}
           global $basesr;
           $achou = false;
                 $condicaoaux  = " where rh54_base = ".db_sqlformat( $base );
                 $condicaoaux .= " and rh54_regist = ".db_sqlformat( $r110_regist );
                 if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
                    $condicaoaux .= " and rh54_rubric = ".db_sqlformat( $campo_rubrica );
                    if( db_selectmax( "basesr", "select * from rhbasesreg ".$condicaoaux )){
          $achou = true;
        }  
           }else{
                   $condicaoaux  = " and r09_base = ".db_sqlformat( $base );
                   $condicaoaux .= " and r09_rubric = ".db_sqlformat( $campo_rubrica );
                   if( db_selectmax( "basesr", "select * from basesr ".bb_condicaosubpes("r09_").$condicaoaux )){
         $achou = true;
       }
           }
           if($achou){
              if( $area0 == "pontoprovfer" ){
                    $tpgto = $pontoprovfer[$i]["r91_tpp"];
              }else if($area0 == " pontofr"){
                    $tpgto = $pontofr[$i]["r19_tpp"];
              }      
//echo "<BR> passou aqui 4";
                    // r01_propi --> Perc.Inativo 
        if( $pessoal[$Ipessoal]["r01_propi"] > 0 && $pessoal[$Ipessoal]["r01_propi"] < 100){
           $condicaoaux  = " and ".$sigla2."_regist = ".db_sqlformat( $r110_regist );
           $condicaoaux .= " and ".$sigla2."_pd = ".db_sqlformat( $campo_pd );
           $condicaoaux .= " and ".$sigla2."_rubric = ".db_sqlformat( $campo_rubrica );
           if( db_at($area0,"pontoprovfer pontoprovs13 pontofr") > 0 ){
         $condicaoaux .= " and upper(".$sigla2."_tpp) = ".db_sqlformat( strtoupper($tpgto) );
           }
                 global $transacao;        
                     if( db_selectmax( "transacao", "select * from ".$area1." ".bb_condicaosubpes( $sigla2."_" ).$condicaoaux )){
                          if( $rubrica == "R988" || $rubrica == "R989" || $rubrica == "R979"){
                             if( $campo_pd == "1"){
                                $valor -= $transacao[0][$sigla2."_valor"];
                             }else{
                                $valor += $transacao[0][$sigla2."_valor"];
                             }
  //echo "<br> 11 : valor --> $valor";
                          }else{
                             if( $campo_pd == "1"){
                                $valor += round( $transacao[0][$sigla2."_valor"],2);
                             }else{
                                $valor -= round( $transacao[0][$sigla2."_valor"],2);
                             }
  //echo "<br> 12 : valor --> $valor";
                          }
                       continue;
                     }

        }
        if( !db_empty($formula1) && $campo_valor == 0){
                       if( (strpos("#".$rubrica_contem,"A")+0) == 0 && (strpos("#".$rubrica_contem,"B")+0) == 0 && (strpos("#".$rubrica_contem,"P")+0) == 0 ){

       //echo "F0->".$campo_rubrica."\n";
       //echo "F1->".$formula2."\n";
       $formula2 ='$formula1 = '.$formula1.";";
       ob_start();
       eval($formula2);
       db_alerta_erro_eval($pessoal[$Ipessoal]["r01_regist"],$formula1,$rubrica); 
       //echo "F2->".$formula1."\n";
       $resultado = round($campo_quant * $formula1 ,2);
       //echo "F3->".$resultado."\n";
 
        
        if( $campo_pd == "1"){
           $valor += $resultado;
        }else{
           $valor -= $resultado;
        }
           }else{
        $base_na_rubrica = substr("#".$rubrica_contem,(strpos("#".$rubrica_contem,"B")+0),4);
        if( ( $base_na_rubrica != $base) && ( $base_na_rubrica != $base_mae)){
           if( db_empty($campo_quant)){
        if( $campo_pd == "1"){
           $abre_base .= $rubrica_contem;
        }else{
           $abre_base .= "-".$rubrica_contem;
        }
           }else{
        if( $campo_pd == "1"){
           $abre_base .= '+('.$formula1.'*'.db_strtran(db_str($campo_quant,7,2),",",".").')' ;
        }else{
           $abre_base .= '-('.$formula1.'*'.db_strtran(db_str($campo_quant,7,2),",",".").')' ;
        }
           }
        }else{
           $n1 = 1;
        }
        if( db_empty($campo_quant)){
           if( $campo_pd == "1"){
        $monta_formula .= $rubrica_contem;
           }else{
        $monta_formula .= "-". $rubrica_contem;
           }
        }else{
           if( $campo_pd == "1"){
        $monta_formula .= '+('.$formula1.'*'.db_strtran((db_str($campo_quant,7,2)),",",".").')' ;
           }else{
        $monta_formula .= '-('.$formula1.'*'.db_strtran((db_str($campo_quant,7,2)),",",".").')' ;
           }
        }
           }
        }else{
           if( $campo_pd == "1"){
        $valor += $campo_valor;
           }else{
        $valor -= $campo_valor;
           }
        }
     }
        }
     }
  }
  
  $abre_base     .= "+".db_strtran((db_str($valor,20,2)),",",".").")";
  $monta_formula .= "+".db_strtran((db_str($valor,20,2)),",",".").")";
  $formula = db_strtran($formula,$base,$abre_base) ;
  if( $n1 == 1){
     $formula = db_strtran($formula,$base,$monta_formula);
  }
       $pos_base = strpos("#".$formula,"B")+0;
     }
  }
//echo "<BR> formula --> $formula";
  return $formula;

}


/// fim da funcao rle_var_bxxx ///
/// teto_prev_inativo ///

function teto_prev_inativo($r07_valor, $tbprev){
  global $pessoal,$Ipessoal,$inssirf_;

  if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) != "a" ){
     $condicaoaux = " and r33_codtab = ".db_sqlformat( $tbprev );
     if( db_selectmax( "inssirf_", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux )){
  if ( $r07_valor > $inssirf_[0]["r33_tinati"] ){
     $r07_valor = $r07_valor - $inssirf_[0]["r33_tinati"];
  }else{
    $r07_valor  = 0;
  }
     }   
  }
  
  return $r07_valor;
}

/// fim da funcao teto_prev_inativo ///
/// grava_base_inn_irf ///
// R992 BASE PREVIDENCIA
// R990 BASE PREVID.PATRONAL (21%INSS)
// R993 DESC PREVIDENCIA
function grava_base_prev($area_grava) {
  
  global $rubrica_maternidade, $rubrica_licenca_saude, $rubrica_acidente;
  global $anousu, $mesusu, $DB_instit, $db_debug;
  
  global $base_prev,$situacao_funcionario,$dias_pagamento;
  global $valor_salario_maternidade,$cfpess,$subpes,$prev_desc,$base_irfb,$situacao_funcionario,$r110_lotac,$r14_quant,$r14_valor,$r20_rubr;
  global $vlr_sal_saude_ou_acidente,$pessoal,$Ipessoal;
  global $teto_prev;
  
  $r14_valor = $base_prev;
  if ($db_debug == true) { echo "[grava_base_prev] 79 - r14_valor = $r14_valor  <br>"; }
 //echo "<BR> base_prev --> $base_prev"; 
  if (( ( $situacao_funcionario == 6 ) && !db_empty($rubrica_licenca_saude ) ) ) {
    if ($dias_pagamento > 0) {
      $r14_valor += $vlr_sal_saude_ou_acidente;
      if ($db_debug == true) { echo "[grava_base_prev] 80 - r14_valor = $r14_valor  <br>"; }
    }
  } else if (( ( $situacao_funcionario == 3) && !db_empty($rubrica_acidente ) ) ) {
    if ($dias_pagamento > 0) {
      $r14_valor += $vlr_sal_saude_ou_acidente;
      if ($db_debug == true) { echo "[grava_base_prev] 81 - r14_valor = $r14_valor  <br>"; }
    }
  } else if (( ( $situacao_funcionario == 5) && !db_empty($rubrica_maternidade ) ) ) {
    if ($dias_pagamento > 0) {
      $r14_valor += $valor_salario_maternidade;
      if ($db_debug == true) { echo "[grava_base_prev] 82 - r14_valor = $r14_valor  <br>"; }
    }
  }  
  $r20_rubr  = "R992";
  // R992 BASE PREVIDENCIA
  if (( ($area_grava != "pontof13" && $area_grava != "pontoprovf13") || !( $area_grava == "pontof13" && $cfpess[0]["r11_mes13"] != $mesusu)) ) {
 //echo "<BR> 1 base_prev --> $base_prev";
  
    if ($db_debug == true) { echo "[grava_base_prev] 25 - Chamando a função grava_gerf() <br>"; }
    grava_gerf($area_grava);
  }
  if ($situacao_funcionario == 5) {
    $r14_valor = $base_prev;
    if ($db_debug == true) { echo "[grava_base_prev] 83 - r14_valor = $r14_valor  <br>"; }
    if (!db_empty($rubrica_maternidade)) {
      if ($dias_pagamento > 0) {
//        echo $r14_valor." += ".$valor_salario_maternidade."<br>";
        $r14_valor += $valor_salario_maternidade;
        if ($db_debug == true) { echo "[grava_base_prev] 84 - r14_valor = $r14_valor  <br>"; }
      }
    }
    if (db_empty($rubrica_maternidade) || ( !db_empty($rubrica_maternidade) && $dias_pagamento == 0 ) ) {
      $r20_rubr  = "R990";
      // R990 BASE PREVID.PATRONAL (21%INSS)
      
      if ($db_debug == true) { echo "[grava_base_prev] 26 - Chamando a função grava_gerf() <br>"; }
      grava_gerf($area_grava);
    }
  }
  $r14_valor = $prev_desc;
  if ($db_debug == true) { echo "[grava_base_prev] 85 - r14_valor = $r14_valor  <br>"; }
  $condicaoaux = " and r33_codtab = ".db_sqlformat(db_str($pessoal[$Ipessoal]["r01_tbprev"]+2,1))." order by r33_inic desc limit 1";
  if (db_selectmax("teto_prev", "select r33_fim as ultima_faixa from inssirf ".bb_condicaosubpes("r33_" ).$condicaoaux )) {
    if ($r14_valor > $teto_prev[0]["ultima_faixa"]) {
      $r14_valor = $teto_prev[0]["ultima_faixa"];
      if ($db_debug == true) { echo "[grava_base_prev] 86 - r14_valor = $r14_valor  <br>"; }
    }
  }
  global $transacao1,$chamada_geral_arquivo,$siglag; 
  $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"]);
  $condicaoaux .= " and ".$siglag."rubric in ( 'R901','R902','R903','R904','R905','R906','R907','R908','R909','R910','R911','R912' )";
  if (db_selectmax("transacao1", "select sum(".$siglag."valor) as descontod from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag).$condicaoaux )) {
    $r14_valor = $transacao1[0]["descontod"];
    if ($db_debug == true) { echo "[grava_base_prev] 86 - r14_valor = $r14_valor  <br>"; }
    $r20_rubr  = "R993";
    // R993 DESC PREVIDENCIA
    //echo "<BR> 8 r14_valor --> $r14_valor";
    
    if ($db_debug == true) { echo "[grava_base_prev] 28 - Chamando a função grava_gerf() <br>"; }
    grava_gerf($area_grava);
  }
  $base_prev =0;
  $prev_desc =0;
  
  if ($db_debug == true) {
    echo "[grava_base_prev] FIM DO PROCESSAMENTO DA FUNÇÃO grava_base_prev <BR><BR>";
  }
}


// R981 BASE I.R.F.
// R950 BASE IRRF SALARIO-COMPLEMENTAR
// R982 BASE IRF 13O SAL (BRUTA)
// R951 BASE IRRF 13.SAL.-COMPLEMENTAR
// R983 BASE IRF FERIAS
// R952 BASE IRRF FERIAS -COMPLEMENTAR
function gravb_base_irf ($area_grava,$r20_rubrp){
  global $r20_rubr,$cfpess,$subpes,$naoencontroupontosalario,$base_folha_complementar,$r14_valor, $db_debug,
         $base_irfb,$situacao_funcionario,$r110_lotac,$r14_quant,$dias_pagamento,$rubrica_maternidade,
         $valor_salario_maternidade ,$vlr_sal_saude_ou_acidente ,$rubrica_acidente,$rubrica_licenca_saude; 
  global $anousu, $mesusu, $DB_instit,$pessoal, $Ipessoal;
   
  if ($db_debug == true) {
    echo "[gravb_base_irf] INICIANDO PROCESSAMENTO DA FUNÇÃO gravb_base_irf<BR>";
  }
  if($area_grava == "pontof13" && $cfpess[0]["r11_mes13"] > $mesusu){
     $base_irfb = 0;
  }
  if(     $r20_rubrp == "R913"){ // R913 I.R.R.F.
    //echo "<BR> passoou aqui "; // reis
     $r20_rubr  = "R981"; // R981 BASE I.R.F.
     $r20_rubr1 = "R950"; // R950 BASE IRRF SALARIO-COMPLEMENTAR
  }else if( $r20_rubrp == "R914"){ // R914 IRRF S/ 13o SALARIO
     $r20_rubr = "R982";  // R982 BASE IRF 13O SAL (BRUTA)
     $r20_rubr1 = "R951"; // R951 BASE IRRF 13.SAL.-COMPLEMENTAR
  }else if( $r20_rubrp == "R915"){ // R915 IRRF FERIAS
     $r20_rubr = "R983";  // R983 BASE IRF FERIAS
     $r20_rubr1 = "R952"; // R952 BASE IRRF FERIAS -COMPLEMENTAR
  }
  
  
  if( $r20_rubr  == "R981" || $r20_rubr  == "R982" || $r20_rubr  == "R983"){
    // R981 BASE I.R.F.
    // R982 BASE IRF 13O SAL (BRUTA)
    // R983 BASE IRF FERIAS
     $r14_valor = $base_irfb ;
     if ($db_debug == true) { echo "[gravb_base_irf] 87 - r14_valor = $r14_valor  <br>"; }
     if($r20_rubr == "R981"){
       if( $situacao_funcionario == 6 ){
          if( !db_empty( $rubrica_licenca_saude ) ){
             if( $dias_pagamento > 0){
               $r14_valor += $vlr_sal_saude_ou_acidente;
               if ($db_debug == true) { echo "[gravb_base_irf] 88 - r14_valor = $r14_valor  <br>"; }
             }
          }
       } else if( $situacao_funcionario == 3){
          if( !db_empty( $rubrica_acidente ) ){
             if( $dias_pagamento > 0){
               $r14_valor += $vlr_sal_saude_ou_acidente;
               if ($db_debug == true) { echo "[gravb_base_irf] 89 - r14_valor = $r14_valor  <br>"; }
             }
          }
       } else if( $situacao_funcionario == 5){
          if( !db_empty( $rubrica_maternidade ) ){
             if( $dias_pagamento > 0){
               $r14_valor += $valor_salario_maternidade;
               if ($db_debug == true) { echo "[gravb_base_irf] 90 - r14_valor = $r14_valor  <br>"; }
             }
          }
       }
//       echo "desconto IRRF $area_grava R975<br>";
     }
     
     if( $r14_valor <= 0.00){
        $r14_valor = 0;
        if ($db_debug == true) { echo "[gravb_base_irf] 91 - r14_valor = $r14_valor  <br>"; }
     }
     if( round($r14_valor,2) > 0.00){
      
       if ($db_debug == true) { echo "[gravb_base_irf] 29 - Chamando a função grava_gerf() <br>"; }
       $rValorMolestiaGrave = $r14_valor;
       grava_gerf($area_grava);
       if ($r20_rubr == 'R981' && $pessoal[$Ipessoal]["rh02_portadormolestia"] == 't' && $area_grava == 'pontofs') {
             
         $r20_rubr  = 'R975';
         $r14_valor = $rValorMolestiaGrave;
         if ($db_debug == true) { echo "[gravb_base_irf] 31 -Gravando Molestia Grave <br>";} 
         grava_gerf($area_grava);
       } else if ($r20_rubr == 'R981' && $pessoal[$Ipessoal]["rh02_portadormolestia"] == 't' && $area_grava == 'pontof13') {
             
         $r20_rubr  = 'R974';
         $r14_valor = $rValorMolestiaGrave;
         if ($db_debug == true) { echo "[gravb_base_irf] 31 -Gravando Molestia Grave <br>";} 
         grava_gerf($area_grava);
       }
     }
     $base_irfb = 0;
  }
  if( !db_empty($base_folha_complementar)){
     $r14_valor = $base_folha_complementar;
     if ($db_debug == true) { echo "[gravb_base_irf] 92 - r14_valor = $r14_valor  <br>"; }
     $r20_rubrc = $r20_rubr;
     $r20_rubr = $r20_rubr1;
     if( !$naoencontroupontosalario){
       if ($db_debug == true) { echo "[gravb_base_irf] 30 - Chamando a função grava_gerf() <br>"; }
       grava_gerf($area_grava);
     }
     $r20_rubr = $r20_rubrc;
  }
  
  if ($db_debug == true) {
    echo "[gravb_base_irf] FIM DO PROCESSAMENTO DA FUNÇÃO gravb_base_irf<BR><BR>";
  }
}

function grava_gerf($area_grava,$grava_tpp=" ") {
  
  global $r14_valor,$r20_rubr, $rubrica_acidente, $dias_pagamento, $r110_regist,
  $tot_desc,$salfamilia,$subpes,$r110_lotac,$r14_quant,$situacao_funcionario,$pessoal, $Ipessoal;
  global $anousu, $mesusu, $DB_instit,$subpes, $db_debug, $pontofs;
 
  global $pontofe,$transacao,$passada,$n,$chamada_geral,$pontoprovfe;

  if ($db_debug == true) {
    echo "[grava_gerf] INICIO DO PROCESSAMENTO DA FUNÇÃO grava_gerf<BR>";
  }  
    //echo "<BR> grava_gerf() rubrica --> $r20_rubr   valor --> $r14_valor aqui $area_grava $dias_pagamento ------ "; //reis
  if ($r14_valor > 0.01 ) {
    
    //echo "<BR> 0 passou aqui ! $r20_rubr";
    $nro = db_val(substr("#".$r20_rubr,3,2))  ;
    if ($nro <= 16) {
        if($area_grava == "pontofs" && $r20_rubr == ("R9".db_str(( (3*$pessoal[$Ipessoal]["r01_tbprev"])-2)-1+$n ,2,0,"0"))){
           $numcgm = $pessoal[$Ipessoal]["r01_numcgm"];
           
           global $pessoal__;
           $condicaoaux  = " and rh01_numcgm = ".db_sqlformat( $numcgm );
           $condicaoaux .= " and ( rh05_recis is null ";
           $condicaoaux .= "  or (extract(year from rh05_recis) >= ".db_sqlformat(substr("#".$subpes,1,4));
           $condicaoaux .= " and  extract(month from rh05_recis) >= ".db_sqlformat(substr("#".$subpes,6,2))."))";
           if(db_selectmax("pessoal__", "select rh02_regist as r01_regist from rhpessoalmov 
                            inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                            inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                            left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                            ".bb_condicaosubpes("rh02_" ).$condicaoaux )){
             if(count($pessoal__) == 1 ){
                if ($db_debug == true) { echo "[grava_gerf] 1 passou aqui ! $r14_valor   -->   $tot_desc <br>"; }
                $tot_desc += round($r14_valor,2);
                if ($db_debug == true) { echo "[grava_gerf] 29 - tot_desc: $tot_desc<br>"; }
                //echo "<BR> 2 passou aqui ! $tot_desc";
             }
              
           }else{
             $tot_desc += round($r14_valor,2);
             if ($db_debug == true) { echo "[grava_gerf] 30 - tot_desc: $tot_desc<br>"; }
          }
        }else{
           $tot_desc += round($r14_valor,2);
           if ($db_debug == true) { echo "[grava_gerf] 31 - tot_desc: $tot_desc<br>"; }
        }
    } else if ($nro >= 17 && $nro <= 22) {
      $salfamilia = round($r14_valor,2);
    }
    $condicaoaux = " where rh27_instit = $DB_instit and rh27_rubric = ".db_sqlformat($r20_rubr );
    
    global $rubr_;
    db_selectmax("rubr_", "select * from rhrubricas ".$condicaoaux );
    $r14_pd = $rubr_[0]["rh27_pd"];
    
    if ($area_grava == "pontofs") {
      if (( !db_empty($dias_pagamento) || $dias_pagamento > 0 )
      && $r20_rubr == "R991"
      && (db_str($situacao_funcionario,1) == "3" && db_empty($rubrica_acidente ) ) ) {
        // Afastado Acidente de Trabalho + 15 Dias
        
        $r14_valor = ( $r14_valor /$dias_pagamento ) * 30;
        if ($db_debug == true) { echo "[grava_gerf] 93 - r14_valor = $r14_valor  <br>"; }
      }
      
      if (( (!db_empty($dias_pagamento) || $dias_pagamento > 0 )
      || $r20_rubr == "R991" )
      || $situacao_funcionario == 5 // Afastado Licenca Gestante
      || $situacao_funcionario == 6 // Afastado Doenca + 15 Dias
      || $situacao_funcionario == 2 
      || $situacao_funcionario == 7 
      || ($situacao_funcionario == 3 && !db_empty($rubrica_acidente )) ) {
        //echo "<BR> Afastado Acidente de Trabalho + 15 Dias";
        $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist );
        $condicaoaux .= " and r14_rubric = ".db_sqlformat($r20_rubr );
        if (!db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
          $acao = "insere";
        } else {
          $acao = "altera";
        }
        if ($pessoal[$Ipessoal]["rh02_portadormolestia"] == 't') {

           if ($r20_rubr == 'R981') {
             $r14_valor = 0;
           }
        }
        $matriz1 = array();
        $matriz2 = array();
        $matriz1[1] = "r14_regist";
        $matriz1[2] = "r14_rubric";
        $matriz1[3] = "r14_lotac";
        $matriz1[4] = "r14_valor";
        $matriz1[5] = "r14_quant";
        $matriz1[6] = "r14_pd";
        $matriz1[7] = "r14_semest";
        $matriz1[8] = "r14_anousu";
        $matriz1[9] = "r14_mesusu";
        $matriz1[10] = "r14_instit";
        
        $matriz2[1] = $r110_regist;
        $matriz2[2] = $r20_rubr;
        $matriz2[3] = $r110_lotac;
        $matriz2[4] = round($r14_valor,2);
        $matriz2[5] = ($r14_quant==''?0:$r14_quant);
        $matriz2[6] = $r14_pd;
        $matriz2[7] = 0;
        $matriz2[8] = $anousu;
        $matriz2[9] = $mesusu;
        $matriz2[10] = $DB_instit;
        
        
        //echo "<BR> grava_gerf() 1 rubrica --> $r20_rubr   valor --> $r14_valor aqui $area_grava $dias_pagamento ------ "; //reis
        if ($acao=="insere") {
          if ($db_debug == true) {
            echo "[grava_gerf] 10 - Insert: Gerfsal<br>";
            echo "Dados: <br>";
            echo "r14_regist: ".$matriz2[1]."<br>";
            echo "r14_rubric:".$matriz2[2]."<br>";
            echo "r14_lotac:".$matriz2[3]."<br>";
            echo "r14_valor:".$matriz2[4]."<br>";
            echo "r14_quant:".$matriz2[5]."<br>";
            echo "r14_pd:".$matriz2[6]."<br>";
            echo "r14_semest:".$matriz2[7]."<br>";
            echo "r14_anousu:".$matriz2[8]."<br>";
            echo "r14_mesusu:".$matriz2[9]."<br>";
            echo "r14_instit:".$matriz2[10]."<br>";
            echo "<br>";
          }
          db_insert("gerfsal", $matriz1, $matriz2 );
        } else {
          db_update("gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_").$condicaoaux );
        }
      }
    } else if ($area_grava == "pontocom") {
      $condicaoaux  = " and r48_regist = ".db_sqlformat($r110_regist );
      $condicaoaux .= " and r48_rubric = ".db_sqlformat($r20_rubr );
      if (!db_selectmax("transacao", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        $acao = "insere";
      } else {
        $acao = "altera";
      }
      $matriz1 = array();
      $matriz2 = array();
      $matriz1[1] = "r48_regist";
      $matriz1[2] = "r48_rubric";
      $matriz1[3] = "r48_lotac";
      $matriz1[4] = "r48_valor";
      $matriz1[5] = "r48_quant";
      $matriz1[6] = "r48_pd";
      $matriz1[7] = "r48_semest";
      $matriz1[8] = "r48_anousu";
      $matriz1[9] = "r48_mesusu";
      $matriz1[10] = "r48_instit";
      
      $matriz2[1] = $r110_regist;
      $matriz2[2] = $r20_rubr;
      $matriz2[3] = $r110_lotac;
      $matriz2[4] = round($r14_valor,2);
      $matriz2[5] = ($r14_quant==''?0:$r14_quant);
      $matriz2[6] = $r14_pd;
      $matriz2[7] = 0;
      $matriz2[8] = $anousu;
      $matriz2[9] = $mesusu;
      $matriz2[10] = $DB_instit;
      
      
      if ($acao=="insere") {
        $retornar = db_insert("gerfcom", $matriz1, $matriz2 );
      } else {
        $retornar = db_update("gerfcom", $matriz1, $matriz2, bb_condicaosubpes("r48_").$condicaoaux );
      }
    } else if ($area_grava == "pontofr") {
      $matriz1 = array();
      $matriz2 = array();
      
      $matriz1[1] = "r20_regist";
      $matriz1[2] = "r20_rubric";
      $matriz1[3] = "r20_lotac";
      $matriz1[4] = "r20_valor";
      $matriz1[5] = "r20_quant";
      $matriz1[6] = "r20_pd";
      $matriz1[7] = "r20_tpp";
      $matriz1[8] = "r20_semest";
      $matriz1[9] = "r20_anousu";
      $matriz1[10] = "r20_mesusu";
      $matriz1[11] = "r20_instit";
      
      $matriz2[1] = $r110_regist;
      $matriz2[2] = $r20_rubr;
      $matriz2[3] = $r110_lotac;
      $matriz2[5] = ($r14_quant==''?0:$r14_quant);
      $matriz2[6] = $r14_pd;
      $matriz2[7] = " ";
      $matriz2[8] = 0;
      $matriz2[9] = $anousu;
      $matriz2[10] = $mesusu;
      $matriz2[11] = $DB_instit;
      
      $condicaoaux  = " and r20_regist = ".db_sqlformat($r110_regist );
      $condicaoaux .= " and r20_rubric = ".db_sqlformat($r20_rubr );
      if (!db_selectmax("transacao", "select * from gerfres ".bb_condicaosubpes("r20_" ).$condicaoaux )) {
        $matriz2[4] = round($r14_valor,2);
        // echo "<BR> 1 passou aqui !!!";
        db_insert("gerfres", $matriz1, $matriz2 );
        // echo "<BR> 1.1 passou aqui !!!";
      } else {
        // echo "<BR> 2 passou aqui !!!";
        $matriz2[4] = round($transacao[0]["r20_valor"]+$r14_valor,2);
        db_update("gerfres", $matriz1, $matriz2, bb_condicaosubpes("r20_" ).$condicaoaux );
        // echo "<BR> 2.2 passou aqui !!!";
      }
    } else if ($area_grava == "pontofe") {
    //  if ($r20_rubr < "R900" || $r20_rubr == "R931" || $r20_rubr == "R932" ) {
      if ($r20_rubr < "R900" || $r20_rubr == "R931" || $r20_rubr == "R932"  || $r20_rubr == "R906" || $r20_rubr == "R903"|| $r20_rubr == "R909"|| $r20_rubr == "R912") {
        
        // ver qual a variavel do laco do pontofe neste momento
        if($grava_tpp != " "){
          $tpgto = $grava_tpp;
        }else{  
          $tpgto = $pontofe[0]["r29_tpp"];
        }   
        
      } else {
        $tpgto = str_pad(" ",01);
      }
      //echo "<BR> 111111111111111111111111 tpgto --> $tpgto";
      $matriz1  = array();
      $matriz2  = array();
      
      $matriz1[1] = "r31_regist";
      $matriz1[2] = "r31_rubric";
      $matriz1[3] = "r31_lotac";
      $matriz1[4] = "r31_valor";
      $matriz1[5] = "r31_quant";
      $matriz1[6] = "r31_pd";
      $matriz1[7] = "r31_tpp";
      $matriz1[8] = "r31_semest";
      $matriz1[9] = "r31_anousu";
      $matriz1[10] = "r31_mesusu";
      $matriz1[11] = "r31_instit";
      
      $matriz2[1] = $r110_regist;
      $matriz2[2] = $r20_rubr;
      $matriz2[3] = $r110_lotac;
      $matriz2[5] = ($r14_quant==''?0:$r14_quant);
      $matriz2[6] = $r14_pd;
      $matriz2[7] = $tpgto;
      $matriz2[8] = 0;
      $matriz2[9] = $anousu;
      $matriz2[10] = $mesusu;
      $matriz2[11] = $DB_instit;
      
      $condicaoaux  = " and r31_regist = ".db_sqlformat($r110_regist );
      $condicaoaux .= " and r31_rubric = ".db_sqlformat($r20_rubr );

      if ($r20_rubr < "R900" || $r20_rubr == "R931" || $r20_rubr == "R932"  || $r20_rubr == "R906" || $r20_rubr == "R903"|| $r20_rubr == "R909"|| $r20_rubr == "R912") {
         $condicaoaux .= " and r31_tpp = '$tpgto'";
      }
      
      if (!db_selectmax("transacao", "select * from gerffer ".bb_condicaosubpes("r31_" ).$condicaoaux )) {
        $matriz2[4] = round($r14_valor,2);
        $retornar = db_insert("gerffer", $matriz1, $matriz2 );
      } else {
        $matriz2[4] = round($transacao[0]["r31_valor"]+$r14_valor,2);
        $retornar = db_update("gerffer", $matriz1, $matriz2, bb_condicaosubpes("r31_" ).$condicaoaux );
      }
    } else if ($area_grava == "pontoprovfe") {
    //  if ($r20_rubr < "R900" || $r20_rubr == "R931" || $r20_rubr == "R932" ) {
      if ($r20_rubr < "R900" || $r20_rubr == "R931" || $r20_rubr == "R932"  || $r20_rubr == "R906" || $r20_rubr == "R903"|| $r20_rubr == "R909"|| $r20_rubr == "R912") {
        
        // ver qual a variavel do laco do pontofe neste momento
        if($grava_tpp != " "){
          $tpgto = $grava_tpp;
        }else{  
          $tpgto = $pontoprovfe[0]["r91_tpp"];
        }   
        
      } else {
        $tpgto = str_pad(" ",01);
      }
      //echo "<BR> 111111111111111111111111 tpgto --> $tpgto";
      $matriz1  = array();
      $matriz2  = array();
      
      $matriz1[1] = "r93_regist";
      $matriz1[2] = "r93_rubric";
      $matriz1[3] = "r93_lotac";
      $matriz1[4] = "r93_valor";
      $matriz1[5] = "r93_quant";
      $matriz1[6] = "r93_pd";
      $matriz1[7] = "r93_tpp";
      $matriz1[8] = "r93_semest";
      $matriz1[9] = "r93_anousu";
      $matriz1[10] = "r93_mesusu";
      $matriz1[11] = "r93_instit";
      
      $matriz2[1] = $r110_regist;
      $matriz2[2] = $r20_rubr;
      $matriz2[3] = $r110_lotac;
      $matriz2[5] = ($r14_quant==''?0:$r14_quant);
      $matriz2[6] = $r14_pd;
      $matriz2[7] = $tpgto;
      $matriz2[8] = 0;
      $matriz2[9] = $anousu;
      $matriz2[10] = $mesusu;
      $matriz2[11] = $DB_instit;
      
      $condicaoaux  = " and r93_regist = ".db_sqlformat($r110_regist );
      $condicaoaux .= " and r93_rubric = ".db_sqlformat($r20_rubr );
 
      if ($r20_rubr < "R900" || $r20_rubr == "R931" || $r20_rubr == "R932"  || $r20_rubr == "R906" || $r20_rubr == "R903"|| $r20_rubr == "R909"|| $r20_rubr == "R912") {
         $condicaoaux .= " and r93_tpp = '$tpgto'";
      }
      
      if (!db_selectmax("transacao", "select * from gerfprovfer ".bb_condicaosubpes("r93_" ).$condicaoaux )) {
        $matriz2[4] = round($r14_valor,2);
        $retornar = db_insert("gerfprovfer", $matriz1, $matriz2 );
      } else {
        $matriz2[4] = round($transacao[0]["r93_valor"]+$r14_valor,2);
        $retornar = db_update("gerfprovfer", $matriz1, $matriz2, bb_condicaosubpes("r93_" ).$condicaoaux );
      }
    } else if ($area_grava == "pontof13") {
      $matriz1  = array();
      $matriz2  = array();
      
      if ($pessoal[$Ipessoal]["rh02_portadormolestia"] == 't') {

           if ($r20_rubr == 'R981') {
             $r14_valor = 0;
           }
      }
      $matriz1[1] = "r35_regist";
      $matriz1[2] = "r35_rubric";
      $matriz1[3] = "r35_lotac";
      $matriz1[4] = "r35_valor";
      $matriz1[5] = "r35_quant";
      $matriz1[6] = "r35_pd";
      $matriz1[7] = "r35_semest";
      $matriz1[8] = "r35_anousu";
      $matriz1[9] = "r35_mesusu";
      $matriz1[10] = "r35_instit";
      
      $matriz2[1] = $r110_regist;
      $matriz2[2] = $r20_rubr;
      $matriz2[3] = $r110_lotac;
      $matriz2[5] = ($r14_quant==''?0:$r14_quant);
      $matriz2[6] = $r14_pd;
      $matriz2[7] = 0;
      $matriz2[8] = $anousu;
      $matriz2[9] = $mesusu;
      $matriz2[10] = $DB_instit;
      
      $condicaoaux  = " and r35_regist = ".db_sqlformat($r110_regist );
      $condicaoaux .= " and r35_rubric = ".db_sqlformat($r20_rubr );
      
      if (!db_selectmax("transacao", "select * from gerfs13 ".bb_condicaosubpes("r35_" ).$condicaoaux )) {
        $matriz2[4] = round($r14_valor,2);
        $retornar = db_insert("gerfs13", $matriz1, $matriz2 );
      } else {
        $matriz2[4] = round($transacao[0]["r35_valor"]+$r14_valor,2);
        $retornar = db_update("gerfs13", $matriz1,$matriz2, bb_condicaosubpes("r35_" ).$condicaoaux );
      }
    } else if ($area_grava == "pontoprovf13") {
      $matriz1  = array();
      $matriz2  = array();
      
      $matriz1[1] = "r94_regist";
      $matriz1[2] = "r94_rubric";
      $matriz1[3] = "r94_lotac";
      $matriz1[4] = "r94_valor";
      $matriz1[5] = "r94_quant";
      $matriz1[6] = "r94_pd";
      $matriz1[7] = "r94_semest";
      $matriz1[8] = "r94_anousu";
      $matriz1[9] = "r94_mesusu";
      $matriz1[10] = "r94_instit";
      
      $matriz2[1] = $r110_regist;
      $matriz2[2] = $r20_rubr;
      $matriz2[3] = $r110_lotac;
      $matriz2[5] = ($r14_quant==''?0:$r14_quant);
      $matriz2[6] = $r14_pd;
      $matriz2[7] = 0;
      $matriz2[8] = $anousu;
      $matriz2[9] = $mesusu;
      $matriz2[10] = $DB_instit;
      
      $condicaoaux  = " and r94_regist = ".db_sqlformat($r110_regist );
      $condicaoaux .= " and r94_rubric = ".db_sqlformat($r20_rubr );
      
      if (!db_selectmax("transacao", "select * from gerfprovs13 ".bb_condicaosubpes("r94_" ).$condicaoaux )) {
        $matriz2[4] = round($r14_valor,2);
        $retornar = db_insert("gerfprovs13", $matriz1, $matriz2 );
      } else {
        $matriz2[4] = round($transacao[0]["r94_valor"]+$r14_valor,2);
        $retornar = db_update("gerfprovs13", $matriz1,$matriz2, bb_condicaosubpes("r94_" ).$condicaoaux );
      }
    }
    $r14_quant = 0;
    $r14_valor = 0;
    if ($db_debug == true) { echo "[grava_gerf] 94 - r14_valor = $r14_valor  <br>"; }
  }
  
  if ($db_debug == true) {
    echo "[grava_gerf] FIM DO PROCESSAMENTO DA FUNÇÃO grava_gerf<BR><BR>";
  }
}


/// fim da funcao grava_base_inn_irf ///
/// calculos_especificos ///
// CALCULOS SOBRE O LIQUIDO ( BRUTO - OBRIGATORIOS )
// Rubricas que sao um percentual do liquido

function calculos_especificos($r110_regist,$r110_lotac){

  global $cfpess,$subpes,$pontofs_,$calcula_xvalor,$tot_prov,$tot_desc;
  global $anousu, $mesusu, $DB_instit,$opcao_geral,$transacao,$d08_carnes,$db_debug;

  if( $d08_carnes == "bage"){
      $tot_obrig = 0;
      if( $opcao_geral == 1){
  $condicaoaux  = " and substr(r14_rubric,1,1) = 'R' and to_number(substr(r14_rubric,2,3),'999')::integer < 916  and r14_regist = ".db_sqlformat( $r110_regist );
  if( db_selectmax( "transacao", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
     for($xy=0;$xy<count($transacao);$xy++){
        $tot_obrig += round($transacao[$xy]["r14_valor"],2);
     }
  }
      }else if( $opcao_geral == 8){ 
  $condicaoaux  = " and substr(r48_rubric,1,1) = 'R' and to_number(substr(r48_rubric,2,3),'999')::integer < 916  and r48_regist = ".db_sqlformat( $r110_regist );
  if( db_selectmax( "transacao", "select * from gerfcom ".bb_condicaosubpes( "r48_" ).$condicaoaux )){
      for($xy=0;$xy<count($transacao);$xy++){
        $tot_obrig += round($transacao[$xy]["r48_valor"],2);
      }
  } 
      }
  }
  
  $varifica_tamanho = trim($cfpess[0]["r11_desliq"]);
  if(!db_empty($varifica_tamanho)){
  for($Ii=0;$Ii < strlen($varifica_tamanho) ;$Ii+=4){
    //echo "<BR><BR>IIIIII *--- $Ii";
     $rub = substr("#". trim($cfpess[0]["r11_desliq"]), $Ii+1, 4 ) ;
     $variavel = "calcula_xvalor_".$rub;
     global $$variavel;
     //echo "<BR><BR>$variavel --- ".($$variavel==true?"true":"false");
     $calcula_xvalor = ($$variavel == true);
     if( $calcula_xvalor){
  $condicaoaux  = " and r10_regist = ".db_sqlformat( $r110_regist );
  $condicaoaux .= " and r10_rubric = ".db_sqlformat( $rub );
  if( db_selectmax( "pontofs_", "select * from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )){
     $tem_quant = $pontofs_[0]["r10_quant"];
     if( !db_empty($rub)){
             $liquido_folha = $tot_prov - $tot_desc;
             if( $d08_carnes == "bage"){
                $liquido_folha = $tot_prov - $tot_obrig;
             }      
       $xvalor_espec = $liquido_folha / 100 * $tem_quant ;
       if( $xvalor_espec > 0){

        $matriz1 = array();
        $matriz2 = array();

        $matriz1[1] = "r14_regist";
        $matriz1[2] = "r14_rubric";
        $matriz1[3] = "r14_lotac";
        $matriz1[4] = "r14_valor";
        $matriz1[5] = "r14_quant";
        $matriz1[6] = "r14_pd";
        $matriz1[7] = "r14_semest";
        $matriz1[8] = "r14_anousu";
        $matriz1[9] = "r14_mesusu";
        $matriz1[10] = "r14_instit";

        $condicaoaux  = " and r14_regist = ".db_sqlformat( $r110_regist );
        $condicaoaux .= " and r14_rubric = ".db_sqlformat( $rub );
        
        if( !db_selectmax( "transacao", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
           if(  $xvalor_espec > 0){
        $matriz2[1] = $r110_regist;
        $matriz2[2] = $rub;
        $matriz2[3] = $r110_lotac;
        $matriz2[4] = round( $xvalor_espec,2 );
        $matriz2[5] = $tem_quant;
        $matriz2[6] = 2;
        $matriz2[7] = 0;
        $matriz2[8] = $anousu;
        $matriz2[9] = $mesusu;
        $matriz2[10] = $DB_instit;
        
        if ($db_debug == true) {
           echo "[calculos_especificos] 14 - Insert: Gerfsal<br>";
           echo "[calculos_especificos] Dados: <br>";
           echo "[calculos_especificos] r14_regist: ".$matriz2[1]."<br>";
           echo "[calculos_especificos] r14_rubric:".$matriz2[2]."<br>";
           echo "[calculos_especificos] r14_lotac:".$matriz2[3]."<br>";
           echo "[calculos_especificos] r14_valor:".$matriz2[4]."<br>";
           echo "[calculos_especificos] r14_quant:".$matriz2[5]."<br>";
           echo "[calculos_especificos] r14_pd:".$matriz2[6]."<br>";
           echo "[calculos_especificos] r14_semest:".$matriz2[7]."<br>";
           echo "[calculos_especificos] r14_anousu:".$matriz2[8]."<br>";
           echo "[calculos_especificos] r14_mesusu:".$matriz2[9]."<br>";
           echo "[calculos_especificos] r14_instit:".$matriz2[10]."<br>";
           echo "<br>";
        }
        db_insert( "gerfsal", $matriz1, $matriz2 );
           }
        }else{
           if( $valor_liquido > 0){
        $matriz2[1] = $r110_regist;
        $matriz2[2] = $rub;
        $matriz2[3] = $r110_lotac;
        $matriz2[4] = round( $xvalor_espec,2 );
        $matriz2[5] = $tem_quant;
        $matriz2[6] = 2;
        $matriz2[7] = 0;
        $matriz2[8] = $anousu;
        $matriz2[9] = $mesusu;
                          $matriz2[10] = $DB_instit;

  //echo "<BR> calculos especificos() 1 rubrica --> $rub   valor --> $xvalor_espec "; //reis
        $retornar = db_update( "gerfsal", $matriz1, $matriz2, bb_condicaosubpes( "r14_" ).$condicaoaux );
           }else{
        $retornar = db_delete( "gerfsal", bb_condicaosubpes( "r14_" ).$condicaoaux );
           }
        }
     }
        }
     }
  }
   }

   }
   
   if ($db_debug == true) {
     echo "[calculos_especificos] FIM DO PROCESSAMENTO DA FUNÇÃO <BR><BR>";
   }
}

/// fim da funcao calculos_especificos ///

/// calculos_especificos_sapiranga ///

// Calcula o Desconto Sindical rubrica -> 1602

function calculos_especificos_sapiranga($opcao_geral) {
  
  global $calcula_valor_1602, $pontofs_, $tot_prov, $tot_desc, $pessoal ,$Ipessoal,$subpes;
  global $anousu, $mesusu, $DB_instit, $db_debug;
  
 
  global $F001, $F002, $F004, $F005, $F006,
         $F007, $F008, $F009, $F010, $F011,
         $F012, $F013, $F014, $F015, $F016,
         $F017, $F018, $F019, $F020, $F021,
         $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
   
  $r110_regist = $pessoal[$Ipessoal]["r01_regist"];
  $r110_lotac  = $pessoal[$Ipessoal]["r01_lotac"];
  
  global $quais_diversos;
  eval($quais_diversos);

  if ($db_debug == true) {
    echo "[calculos_especificos_sapiranga] INICIO DO PROCESSAMENTO DA FUNÇÃO... <br><br> ";
  }
    
   if( $calcula_valor_1602 ){
      $condicaoaux  = " and r10_regist = ".db_sqlformat( $r110_regist );
      $condicaoaux .= " and r10_rubric = '1602'";
      if( db_selectmax( "pontofs_", "select * from pontofs ".bb_condicaosubpes( "r10_" ).$condicaoaux )){
         $tem_1602 = $pontofs_[0]["r10_valor"];
         if( !db_empty($tem_1602)){
            $valor_compara = 0;
            $valor_compara = $tot_prov ;
            if( !db_empty($valor_compara)){
               $valor_desconto_1602 = $tem_1602 ;
               $valor_liquido = $valor_compara * ($D030/100);
               if( $valor_liquido >= $valor_compara){
                  $valor_liquido = $valor_compara;
               }
               if( $valor_desconto_1602 <= $valor_liquido){
                  $valor_liquido = $valor_desconto_1602;
               }
         $liquido_folha = $tot_prov - $tot_desc;
         if ( $valor_liquido > $liquido_folha && $liquido_folha > 0 ){
            $valor_liquido = $liquido_folha;
         }else if( $liquido_folha <= 0){
            $valor_liquido = 0;
         }
               if( $valor_liquido > 0 ){
                  $tot_desc += $valor_liquido;
                  if ($db_debug == true) { echo "[calculos_especificos_sapiranga]32 - tot_desc: $tot_desc<br>"; }
                  $matriz1 = array();
                  $matriz2 = array();

                  $matriz1[1] = "r14_regist";
                  $matriz1[2] = "r14_rubric";
                  $matriz1[3] = "r14_lotac";
                  $matriz1[4] = "r14_valor";
                  $matriz1[5] = "r14_quant";
                  $matriz1[6] = "r14_pd";
                  $matriz1[7] = "r14_semest";
                  $matriz1[8] = "r14_anousu";
                  $matriz1[9] = "r14_mesusu";
                  $matriz1[10] = "r14_instit";

                  $condicaoaux  = " and r14_regist = ".db_sqlformat( $r110_regist );
                  $condicaoaux .= " and r14_rubric = ".db_sqlformat( "1602" );
                  if( !db_selectmax( "transacao", "select * from gerfsal ".bb_condicaosubpes( "r14_" ).$condicaoaux )){
                     if(  $valor_liquido > 0){
                        $matriz2[1] = $r110_regist;
                        $matriz2[2] = "1602";
                        $matriz2[3] = $r110_lotac;
                        $matriz2[4] = round( $valor_liquido,2 );
                        $matriz2[5] = 0;
                        $matriz2[6] = 2;
                        $matriz2[7] = 0;
                        $matriz2[8] = $anousu;
                        $matriz2[9] = $mesusu;
                        $matriz2[10] = $DB_instit;

                        if ($db_debug == true) {
                           echo "[calculos_especificos_sapiranga] 14 - Insert: Gerfsal<br>";
                           echo "[calculos_especificos_sapiranga] Dados: <br>";
                           echo "[calculos_especificos_sapiranga] r14_regist: ".$matriz2[1]."<br>";
                           echo "[calculos_especificos_sapiranga] r14_rubric:".$matriz2[2]."<br>";
                           echo "[calculos_especificos_sapiranga] r14_lotac:".$matriz2[3]."<br>";
                           echo "[calculos_especificos_sapiranga] r14_valor:".$matriz2[4]."<br>";
                           echo "[calculos_especificos_sapiranga] r14_quant:".$matriz2[5]."<br>";
                           echo "[calculos_especificos_sapiranga] r14_pd:".$matriz2[6]."<br>";
                           echo "[calculos_especificos_sapiranga] r14_semest:".$matriz2[7]."<br>";
                           echo "[calculos_especificos_sapiranga] r14_anousu:".$matriz2[8]."<br>";
                           echo "[calculos_especificos_sapiranga] r14_mesusu:".$matriz2[9]."<br>";
                           echo "[calculos_especificos_sapiranga] r14_instit:".$matriz2[10]."<br>";
                           echo "<br>";
                        }
                        $retornar = db_insert( "gerfsal", $matriz1, $matriz2 );

                     }
                  }else{
                     if( $valor_liquido > 0){
                        $matriz2[1] = $r110_regist;
                        $matriz2[2] = "1602";
                        $matriz2[3] = $r110_lotac;
                        $matriz2[4] = round( $valor_liquido,2 );
                        $matriz2[5] = 0;
                        $matriz2[6] = 2;
                        $matriz2[7] = 0;
                        $matriz2[8] = $anousu;
                        $matriz2[9] = $mesusu;
                        $matriz2[10] = $DB_instit;

                        $retornar = db_update( "gerfsal", $matriz1, $matriz2, bb_condicaosubpes( "r14_" ).$condicaoaux );
                     }else{
                        $retornar = db_delete( "gerfsal", bb_condicaosubpes( "r14_" ).$condicaoaux );
                     }
                  }
               }
            }
         }
      }
   }
   if ($db_debug == true) { echo "[calculos_especificos_sapiranga] 6 - calcula R928 "; }
   calcula_r928($pessoal[$Ipessoal]["r01_regist"],$pessoal[$Ipessoal]["r01_lotac"],$opcao_geral);
   
   if ($db_debug == true) {
    echo "[calculos_especificos_sapiranga] FIM DO PROCESSAMENTO DA FUNÇÃO... <br><br> ";
   }

}





/// fim da funcao calculos_especificos_sapiranga ///
/// calcula_r928 ///
function calcula_r928 ($r110_regist,$r110_lotac,$opcao_geral){

  global $tot_prov, $tot_desc, $subpes,$salfamilia,$cfpess,$db_debug;
  global $anousu, $mesusu, $DB_instit;
  if ($db_debug == true) {
    echo "[calcula_r928] INICIO DO PROCESSAMENTO DA FUNÇÃO calcula_r928... <br>";
    echo "[calcula_r928] Total de provento e descontos :".$tot_prov." - ".$tot_desc; 
  }
  if( !db_empty($tot_prov) || !db_empty($tot_desc)){
//     $tot_prov = db_val(substr("#".db_str($tot_prov,22,5),1,19));
//     $tot_desc = db_val(substr("#".db_str($tot_desc,22,5),1,19));

    if ($db_debug == true) { 
      echo "[calcula_r928] Função: calcula_r928<br>";
      echo "[calcula_r928] Provento : $tot_prov Desconto : $tot_desc <br>";
    }
     if ( $tot_prov > $tot_desc){
      $r01_rubric = "R926";
      $tot_liq = $tot_prov + $salfamilia - $tot_desc;
      if ($db_debug == true) { echo "[calcula_r928] Total Liquido: $tot_liq Critério:".$cfpess[0]["r11_arredn"]."<br>"; }
      $arredn = arredonda_100($tot_liq, $cfpess[0]["r11_arredn"]);
      $tot_liq += $arredn;
      if ($db_debug == true) { echo "[calcula_r928] Total Liquido: $tot_liq<br>"; }
     }else{
      $arredn = $tot_desc - $tot_prov;
      $r01_rubric = "R928";
     }
     if ($db_debug == true) { echo "[calcula_r928] diferença entre provento e desconto".$arredn."<br>"; }
     if( $arredn > 0){

  $matriz1 = array();
  $matriz2 = array();

  if( $opcao_geral == 1){

     $matriz1[1] = "r14_regist";
     $matriz1[2] = "r14_rubric";
     $matriz1[3] = "r14_lotac";
     $matriz1[4] = "r14_valor";
     $matriz1[5] = "r14_quant";
     $matriz1[6] = "r14_pd";
     $matriz1[7] = "r14_semest";
     $matriz1[8] = "r14_anousu";
     $matriz1[9] = "r14_mesusu";
     $matriz1[10] = "r14_instit";

     $matriz2[1] = $r110_regist;
     $matriz2[2] = $r01_rubric;
     $matriz2[3] = $r110_lotac;
     $matriz2[4] = $arredn;
     $matriz2[5] = 0;
     $matriz2[6] = 1;
     $matriz2[7] = 0;
     $matriz2[8] = $anousu;
     $matriz2[9] = $mesusu;
     $matriz2[10] = $DB_instit;
//echo "<BR> rubrica 71 matric --> $r110_regist r928 arredn --> $arredn proventos --> $tot_prov   descontos ---> $tot_desc "; //reis         
//echo "<BR> rubrica 71 -->R928  valor --> $arredn"; // reis
     
     $condicaoaux  = " and r14_regist = ".db_sqlformat($r110_regist );
     $condicaoaux .= " and r14_pd = 1 ";
     $condicaoaux .= " and r14_rubric = 'R928'";
                
                
     if (db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
        db_update("gerfsal", $matriz1, $matriz2, bb_condicaosubpes("r14_" ).$condicaoaux );
     } else {
        if ($db_debug == true) {
           echo "[calcula_r928] 11 - Insert: Gerfsal<br>";
           echo "[calcula_r928] Dados: <br>";
           echo "[calcula_r928] r14_regist: ".$matriz2[1]."<br>";
           echo "[calcula_r928] r14_rubric:".$matriz2[2]."<br>";
           echo "[calcula_r928] r14_lotac:".$matriz2[3]."<br>";
           echo "[calcula_r928] r14_valor:".$matriz2[4]."<br>";
           echo "[calcula_r928] r14_quant:".$matriz2[5]."<br>";
           echo "[calcula_r928] r14_pd:".$matriz2[6]."<br>";
           echo "[calcula_r928] r14_semest:".$matriz2[7]."<br>";
           echo "[calcula_r928] r14_anousu:".$matriz2[8]."<br>";
           echo "[calcula_r928] r14_mesusu:".$matriz2[9]."<br>";
           echo "[calcula_r928] r14_instit:".$matriz2[10];
           echo "<br>";
        }
        db_insert("gerfsal", $matriz1, $matriz2 );
     }
  }else{

     $matriz1[1] = "r48_regist";
     $matriz1[2] = "r48_rubric";
     $matriz1[3] = "r48_lotac";
     $matriz1[4] = "r48_valor";
     $matriz1[5] = "r48_quant";
     $matriz1[6] = "r48_pd";
     $matriz1[7] = "r48_semest";
     $matriz1[8] = "r48_anousu";
     $matriz1[9] = "r48_mesusu";
     $matriz1[10] = "r48_instit";

     $matriz2[1] = $r110_regist;
     $matriz2[2] = $r01_rubric;
     $matriz2[3] = $r110_lotac;
     $matriz2[4] = $arredn;
     $matriz2[5] = 0;
     $matriz2[6] = 1;
     $matriz2[7] = 0;
     $matriz2[8] = $anousu;
     $matriz2[9] = $mesusu;
       $matriz2[10] = $DB_instit;

//echo "<BR> rubrica 72 matric --> $r110_regist r928 arredn --> $arredn proventos --> $tot_prov   descontos ---> $tot_desc "; //reis         
//echo "<BR> rubrica 72 -->R928  valor --> $arredn"; // reis

       if ($db_debug == true) { echo "[calcula_r928] gerou em complementar<br>"; }
     $retornar = db_insert( "gerfcom", $matriz1, $matriz2 );
  }
     }
  }
  
  if ($db_debug == true) {
    echo "[calcula_r928] FIM DO PROCESSAMENTO DA FUNÇÃO calcula_r928... <br>";
  }   
}


/// dependentes ///

// Carrega as variaveis globais F005, F006 e F006_CLT com a quantidade de dependentes 
// para IRF ou Salario Familia  

function dependentes($registro){

  global $depend, $pessoal, $Ipessoal, $cfpess, $F005, $F006, $F006_clt, $D913, $D908;
  global $anousu, $mesusu, $DB_instit;

  //echo "<BR 1.2 - ndias --> ";
  $datpro = db_strtran(db_str(ndias(per_fpagto(1),2,0,"0"),2)."/".per_fpagto(1),"/","-");
  //echo "<BR 1.2 - saiu ndias --> ";
  
//echo "<BR> funcao dependentes() --> $datpro";


  $F005 =0;
  $F006 =0;
  $F006_clt = 0;
  $condicaoaux = " where rh31_regist = ".db_sqlformat( $registro );

  if(!db_selectmax("depend", "select rh31_depend as r03_depend, rh31_irf as r03_irf,rh31_dtnasc as r03_dtnasc from rhdepend ".$condicaoaux )){
    
     if ( $pessoal[$Ipessoal]["r01_depirf"] > '0' && $pessoal[$Ipessoal]["r01_depirf"] != null ){
       $F005 = $pessoal[$Ipessoal]["r01_depirf"];
     }else{
       $F005 =0;
     }
     if ( $pessoal[$Ipessoal]["r01_depsf"] > '0' && $pessoal[$Ipessoal]["r01_depsf"] != null ){
       $F006 = $pessoal[$Ipessoal]["r01_depsf"];
     }else{
       $F006 =0;
     }
     
  }else{

    for($Idepend=0; $Idepend < count($depend); $Idepend++){
      
      $idade = ver_idade($datpro,db_dtoc($depend[$Idepend]["r03_dtnasc"]));

      // Regimes :  
      //  1   Estatutário 
      //  2   CLT 
      //  3   Extra Quadro 


       // 'c' --> Calcula pela Idade
       
       if( strtolower($depend[$Idepend]["r03_depend"]) == 'c'){   
          
          // Tipo c = Calculado 
          // Sera dependente se a idade for ate x idade

          if( db_at(db_str($pessoal[$Ipessoal]["r01_regime"],1),"1-3") > 0 ){
             // D913 IDADE MAXIMA P/ SAL.FAM ESTATU
             if( $idade < $D913){
               $F006++;
             }    
             if( $pessoal[$Ipessoal]["r01_tbprev"] == $cfpess[0]["r11_tbprev"]){
                // D908 IDADE MAXIMA P/ SAL FAMILIA ESTATUTARIO OU EXTRA-QUADRO QUANDO A TABELA DE PREV DO FUNCIONARIO 
                // FOR INSS 

                if( $idade < $D908){
                  $F006_clt++;
                }    
             }  
          }else{
            // D908 IDADE MAXIMA P/ SAL FAMILIA EXTRA-QUADRO
            // o D908 tambem serve para o CLT
            if( $idade < $D908){
               $F006++;
            }  
          }
       }else if( strtolower($depend[$Idepend]["r03_depend"]) == 's') { // 's' --> Sempre dependente
         
         // Sera dependente independente da idade

         $F006++;
         $F006_clt++;
       }
       
       if($depend[$Idepend]["r03_irf"] != '0') {
         
          if(db_at($depend[$Idepend]["r03_irf"], "1-6-8") > 0) {
            
             $F005++;
          } else if( db_at($depend[$Idepend]["r03_irf"], "2-4-7") > 0) {
             
             if($idade <= 21) {
               $F005++;
             }
          } else if( db_at($depend[$Idepend]["r03_irf"], "5-3") > 0) {
             
             if($idade <= 24) {
               $F005++;
             }
          }
       }
    }
  }  
  if( db_empty($pessoal[$Ipessoal]["r01_tbprev"]) && db_at(db_str($pessoal[$Ipessoal]["r01_regime"],1),"2-3")>0){
     $F006 = 0;
     $F006_clt = 0;
  }
  return true;
}

/// fim da funcao dependentes ///
/// vale_transp ///
function vale_transp($registro, $admissao){

   global $subpes,$vtffunc,$vtfempr,$cfpess,$transacao1;
   global $dtot_vpass,$dperc_pass,$dquant_pass,$quant_pass,$perc_pass,$tot_vpass;
  global $anousu, $mesusu, $DB_instit;
  
   $npass = 0;
   $tot_vpass = 0;
   $perc_pass = 0;
   $quant_pass = 0;
   $dnpass = 0;
   $dtot_vpass = 0;
   $dperc_pass = 0;
   $dquant_pass = 0;
   $condicaoaux = " and r17_regist = ".db_sqlformat($registro) ;
   db_selectmax( "vtffunc", "select * from vtffunc ".bb_condicaosubpes("r17_").$condicaoaux );
   for($Ivt=0;$Ivt< count($vtffunc);$Ivt++){
      $condicaoaux = " and r16_codigo = ".db_sqlformat( $vtffunc[$Ivt]["r17_codigo"] );
      if( db_selectmax( "vtfempr", "select * from vtfempr ".bb_condicaosubpes("r16_").$condicaoaux)){
         if( strtolower($vtffunc[$Ivt]["r17_situac"]) == "a"){

            $quantvale = qvale($vtffunc[$Ivt]["r17_regist"],$vtffunc[$Ivt]["r17_tipo"],$vtffunc[$Ivt]["r17_codigo"],$vtffunc[$Ivt]["r17_difere"],$vtffunc[$Ivt]["r17_quant"],$admissao)    ;

            $quantvale = 0;
            if( ('t' == $cfpess[0]["r11_vtprop"]) && !('t' ==  $vtffunc[$Ivt]["r17_tipo"] )){

               $sqlaux  = "select quantvale_afas(";
               $sqlaux .= "'" .$vtffunc[$Ivt]["r17_codigo"]."',";
               $sqlaux .= db_str($vtffunc[$Ivt]["r17_regist"],6).",";
               $sqlaux .= substr("#".$subpes,1,4)          .",";
               $sqlaux .= substr("#".$subpes,6,2)          .",";
               $sqlaux .= db_str($vtffunc[$Ivt]["r17_quant"],3,0).",";
               $sqlaux .= "'" . $vtffunc[$Ivt]["r17_difere"]."',";
               $sqlaux .= "'" . $cfpess[0]["r11_vtfer"]."',";
               $sqlaux .= "ndias(".substr("#".$subpes,1,4)."," ;
               $sqlaux .=       "".substr("#".$subpes,6,2)."),$DB_instit) as total" ;

               if( db_selectmax( "transacao1",$sqlaux )){
                   $quantvale = $transacao1[0]["total"];
               }

            }else{

               $sqlaux  = "select quantvale(";
               $sqlaux .= "'" .$vtffunc[$Ivt]["r17_codigo"]."',";
               $sqlaux .=  $vtffunc[$Ivt]["r17_regist"].",";
               $sqlaux .=  substr("#".$subpes,1,4).",";
               $sqlaux .=  substr("#".$subpes,6,2).",";
               $sqlaux .=  db_str($vtffunc[$Ivt]["r17_quant"],3,0) .",";
               $sqlaux .= "'" . $vtffunc[$Ivt]["r17_difere"]."'";
               $sqlaux .= ",$DB_instit) as total" ;

               if( db_selectmax( "transacao1",$sqlaux )){
                   $quantvale = $transacao1[0]["total"];
               }

               if( ('t' == $cfpess[0]["r11_vtmpro"]) && ('t' ==  $vtffunc[$Ivt]["r17_tipo"] )){

                   $sqltotaldiasafastado  = "coalesce( conta_dias_afasta(";
                   $sqltotaldiasafastado .= $vtffunc[$Ivt]["r17_regist"] .",";
                   $sqltotaldiasafastado .= substr("#".$subpes,1,4).",";
                   $sqltotaldiasafastado .= substr("#".$subpes,6,2).",";
                   $sqltotaldiasafastado .= "ndias(".substr("#".$subpes,1,4)."," ;
                   $sqltotaldiasafastado .= substr("#".$subpes,6,2)."), $DB_instit), 0)" ;


                   $sqldias_gozo_ferias  = "coalesce( dias_gozo_ferias(";
                   $sqldias_gozo_ferias .= $vtffunc[$Ivt]["r17_regist"].",";
                   $sqldias_gozo_ferias .= substr("#".$subpes,1,4).",";
                   $sqldias_gozo_ferias .= substr("#".$subpes,6,2).",";
                   $sqldias_gozo_ferias .= "ndias(".substr("#".$subpes,1,4)."," ;
                   $sqldias_gozo_ferias .=       "".substr("#".$subpes,6,2)."), $DB_instit), 0)" ;


                   $sqlaux  = "select proporcao_vale_mensal(";
                   $sqlaux .= db_str($quantvale,10,0).",";
                   $sqlaux .= "( case when '".$cfpess[0]["r11_vtfer"]."' = '1'" ;
                   $sqlaux .= "       then ".$sqltotaldiasafastado."+".$sqldias_gozo_ferias ;
                   $sqlaux .= "       else ".$sqltotaldiasafastado ;
                   $sqlaux .= "       end ) ) as total" ;


                   if( db_selectmax( "transacao1",$sqlaux )){
                       $quantvale = $transacao1[0]["total"];
                   }



               }


            }

            if( !('t' ==  $vtffunc[$Ivt]["r17_difere"] )){
               $tot_vpass  += ($quantvale * $vtfempr[0]["r16_valor"]);
               $quant_pass += $quantvale   ;
               $perc_pass  += $vtfempr[0]["r16_perc"];
               $npass += 1;

            }else{
               $dtot_vpass  += ($quantvale * $vtfempr[0]["r16_valor"]);
               $dquant_pass += $quantvale  ;
               $dperc_pass  += $vtfempr[0]["r16_perc"];
               $dnpass += 1;

            }
         }
      }
   }
   if( !db_empty($npass)){
      $perc_pass = $perc_pass / $npass;
   }
   if( !db_empty($dnpass)){
      $dperc_pass = $dperc_pass / $dnpass;
   }
  
   return true;

}

/// fim da funcao vale_transp ///
/// grava_ajuste_previdencia ///
function grava_ajuste_previdencia (){
   global $pessoal, $Ipessoal,$subpes,$chamada_geral_arquivo,$opcao_geral,$numcgm;

   global $transacao;
  global $anousu, $mesusu, $DB_instit;


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
      
      // So faz ajuste para numcgm com mais de uma matricula

      if(count($pes_prev) > 1){
//echo "<BR><BR> 1 entrou na funcao grava_ajuste_previdencia() : matricula --> ".$pessoal[$Ipessoal]["r01_numcgm"]." <BR><BR>";

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
//echo "<BR><BR> 2 entrou na funcao grava_ajuste_previdencia() : matricula --> ".$pessoal[$Ipessoal]["r01_numcgm"]." <BR><BR>";
            // R985 BASE DE PREVIDENCIA
            // R986 BASE PREVIDENCIA (13O SAL)
            // R987 BASE PREVIDENCIA S/FERIAS

            $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and ".$siglag."rubric = 'R992'";
            db_selectmax( "transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes( $siglag ).$condicaoaux );
            $R992_base_F = $transacao[0][$siglag."valor"];


            $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat( $pessoal[$Ipessoal]["r01_regist"] );
            $condicaoaux .= " and ".$siglag."rubric in ('R985','R986','R987')";
            //db_criatabela(pg_query("select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes( $siglag ).$condicaoaux ));
            db_selectmax( "transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes( $siglag ).$condicaoaux );
            for($Itransacao=0;$Itransacao< count($transacao);$Itransacao++){
//echo "<BR><BR> 3 entrou na funcao grava_ajuste_previdencia() : matricula --> ".$pessoal[$Ipessoal]["r01_numcgm"]." <BR><BR>";
               $matrizr[$xy] = $transacao[$Itransacao][$siglag."rubric"];
               $matrizb[$xy] = $transacao[$Itransacao][$siglag."valor"];
               
//echo "<BR><BR> 3.1 rubrica $xy --> ".$matrizr[$xy]."  valor --> ".$matrizb[$xy];
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
//echo "<BR><BR> 4 entrou na funcao grava_ajuste_previdencia() : matricula --> ".$pessoal[$Ipessoal]["r01_numcgm"]." <BR><BR>";
                     db_update( "previden", $matriz1, $matriz2, bb_condicaosubpes( "r60_" ).$condicaoaux );
                  }else{
//echo "<BR><BR> 5 entrou na funcao grava_ajuste_previdencia() : matricula --> ".$pessoal[$Ipessoal]["r01_numcgm"]." <BR><BR>";
                     db_insert( "previden", $matriz1, $matriz2 );
                  }
               }
          }
      }
   }

}


/// fim da funcao grava_ajuste_previdencia ///
/// grava_ajuste_irf ///
function grava_ajuste_irrf($numcgm,$registrop,$r01_tpvinc)
{
  
  global $subpes,$transacao,$opcao_geral,$chamada_geral_arquivo;
  global $F004,$ajusteir,$D902,$ajusteir_,$cfpess;
  global $anousu, $mesusu, $DB_instit;
  
  //echo "<BR> function grava_ajuste_irrf($numcgm,$registrop,$r01_tpvinc)";
  $prev_base = 0;
  $prev_desc = 0;
  $prev_perc = 0;
  if ($opcao_geral == 1 ) {
    $tipo_arquivo = "S";
    // Salario
    $sigla1 = "r14_";
  } else if ($opcao_geral == 8 ) {
    $tipo_arquivo = "C";
    // Complementar
    $sigla1 = "r48_";
  } else if ($opcao_geral == 5 ) {
    $tipo_arquivo = "3";
    // 13 salario
    $sigla1 = "r35_";
  } else if ($opcao_geral == 4 ) {
    $tipo_arquivo = "R";
    // Rescisao
    $sigla1 = "r20_";
  }else if( $opcao_geral == 3){
     $tipo_arquivo = "F";
     $sigla1 = "r31_";
  }
  $siglad = $sigla1;
  $siglag = $sigla1;
  
  
  //$numcgm = $pessoal[$Ipessoal]["r01_numcgm"];
  
  // Pega todos as matriculas do funcionario que nao tem contrato rescindido ou se foi rescindindo foi rescindido com data
  // igual ou superior ao ano e mes de exercicio da folha.
  
  global $pessoal_;

  $condicaoaux  = " and rh01_numcgm = ".db_sqlformat( $numcgm );
  $condicaoaux .= " and ( rh05_recis is null ";
  $condicaoaux .= "  or (extract(year from rh05_recis) >= ".db_sqlformat(substr("#".$subpes,1,4));
  $condicaoaux .= " and  extract(month from rh05_recis) >= ".db_sqlformat(substr("#".$subpes,6,2))."))";
  db_selectmax("pessoal_", "select rh02_regist as r01_regist from rhpessoalmov 
                   inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                   inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                   left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                   ".bb_condicaosubpes("rh02_" ).$condicaoaux );

  //echo "<BR> grava_ajuste_irrf count(pessoal_) --> ".count($pessoal_);
  if (count($pessoal_) > 1) {
    
    $matrizr = array();
    $matrizb = array();
    $matrizd = array();
    $matrizp = array();
    $matrizb[1] = 0;
    $matrizb[2] = 0;
    $matrizb[3] = 0;
    $matrizd[1] = 0;
    $matrizd[2] = 0;
    $matrizd[3] = 0;
    $matrizp[1] = 0;
    $matrizp[2] = 0;
    $matrizp[3] = 0;
    
    $valor_r984 = 0;
    // R984 VLR REF DEPENDENTES P/ IRF
    $valor_r988 = 0;
    // R988 DEDUCOES P/IRRF(SALARIO)
    $valor_r979 = 0;
    // R979 DEDUCOES P/IRRF (FERIAS)
    $valor_r989 = 0;
    // R989 DEDUCOES P/IRRF(13.SALARIO)
    $valor_r997 = 0;
    // R997 DEDUCAO INAT/PENS +65ANOS
    $valor_r999 = 0;
    // R999 DEDUCAO INAT/PENS+65ANOS 13SAL
    
    // R981 BASE I.R.F. BASE
    // R982 BASE IRF 13O SAL (BRUTA) BASE
    // R983 BASE IRF FERIAS BASE
    
    $matrizr[1] = "R981";
    $matrizr[2] = "R982";
    $matrizr[3] = "R983";
    $x = 1;
    
    
    $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat($registrop);
    $condicaoaux .= " and ".$siglag."rubric between 'R979' and 'R999' ";
    db_selectmax("transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux );
    
    for ($Itransacao=0; $Itransacao< count($transacao); $Itransacao++) {
      
      
      if (( db_val(substr("#". $transacao[$Itransacao][$siglag."rubric"],2,3 )) >= 981
      && db_val(substr("#". $transacao[$Itransacao][$siglag."rubric"],2,3 )) <= 983 ) ) {
        $x = db_val(substr("#". $transacao[$Itransacao][$siglag."rubric"],4,1 )) ;
        $matrizr[$x] = $transacao[$Itransacao][$siglag."rubric"];
        $matrizb[$x] = $transacao[$Itransacao][$siglag."valor"];
        //echo "<BR> rubrica 223.0 codigo --> $x rubrica --> ".$matrizr[$x]." valor --> ".$matrizb[$x];
        if($matrizr[$x]== "R983" 
           && $opcao_geral == 3 
           && strtolower($cfpess[0]["r11_fersal"]) == "f" 
           && 't' == $cfpess[0]["r11_recalc"] ){

           global $cadferia;
           $condicaoaux  = " and r30_regist = ".db_sqlformat($registrop ) ;
           $condicaoaux .= " order by r30_perai desc";
           if (db_selectmax("cadferia", "select * from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux )) {
              if('f' == $cadferia[0]["r30_paga13"] ) {

                 // --> Inicio do Calculo das Rubricas IRRF (R913, R914 e R915)
                 
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
           
        }
        $x += 1;
      } else if ($transacao[$Itransacao][$siglag."rubric"] == "R984") {
        // R984 VLR REF DEPENDENTES P/ IRF
        $valor_r984 = $transacao[$Itransacao][$siglag."valor"];
      } else if ($transacao[$Itransacao][$siglag."rubric"] == "R979") {
        // R979 DEDUCOES P/IRRF (FERIAS)
        $valor_r979 = $transacao[$Itransacao][$siglag."valor"];
      } else if ($transacao[$Itransacao][$siglag."rubric"] == "R988") {
        // R988 DEDUCOES P/IRRF(SALARIO) (exemplo : Pensao alimenticia)
        $valor_r988 = $transacao[$Itransacao][$siglag."valor"];
      } else if ($transacao[$Itransacao][$siglag."rubric"] == "R989") {
        // R989 DEDUCOES P/IRRF(13.SALARIO)
        $valor_r989 = $transacao[$Itransacao][$siglag."valor"];
      } else if ($transacao[$Itransacao][$siglag."rubric"] == "R997") {
        // R997 DEDUCAO INAT/PENS +65ANOS
        $valor_r997 = $transacao[$Itransacao][$siglag."valor"];
      } else if ($transacao[$Itransacao][$siglag."rubric"] == "R999") {
        // R999 DEDUCAO INAT/PENS+65ANOS 13SAL
        $valor_r999 = $transacao[$Itransacao][$siglag."valor"];
      }
    }
    
    //echo "<BR> rubrica 223.01 valor R984 --> $valor_r984";
    //echo "<BR> rubrica 223.01 valor R979 --> $valor_r979";
    //echo "<BR> rubrica 223.01 valor R988 --> $valor_r988";
    //echo "<BR> rubrica 223.01 valor R989 --> $valor_r989";
    //echo "<BR> rubrica 223.01 valor R997 --> $valor_r997";
    //echo "<BR> rubrica 223.01 valor R999 --> $valor_r999";
    
    if ($F004 >= 65 && strtolower($r01_tpvinc) != "a" && ( $valor_r997 + $valor_r999 ) == 0 ) {
      for ($x=1; $x<4; $x++) {
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
    //echo "<BR> rubrica 223.011 valor R997 --> $valor_r997";
    //echo "<BR> rubrica 223.011 valor R999 --> $valor_r999";
    
    // R913 I.R.R.F. DESCONTO              --->  R981
    // R914 IRRF S/ 13o SALARIO DESCONTO   --->  R982
    // R915 IRRF FERIAS DESCONTO           --->  R983
    
    $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat($registrop );
    $condicaoaux .= " and ".$siglag."rubric between 'R913' and 'R915'";
    db_selectmax("transacao", "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux );
    for ($Itransacao=0; $Itransacao<count($transacao); $Itransacao++) {
      $x = ( db_val(substr("#". $transacao[$Itransacao][$siglag."rubric"], 2, 3 ) ) - 913 ) + 1;
      if (!db_empty($matrizb[$x]  )) {
        $matrizd[$x] = $transacao[$Itransacao][$siglag."valor"];
        $matrizp[$x] = $transacao[$Itransacao][$siglag."quant"];
        //echo "<BR> rubrica 223.012 codigo --> $x rubrica --> ".$matrizd[$x]." valor --> ".$matrizp[$x];
        $x += 1;
      }
      if ($x > 3 ) {
        break;
      }
    }
    for ($nosx=1; $nosx<4; $nosx++) {
      if (!db_empty($matrizb[$nosx])) {
        
        $matriz1 = array();
        $matriz2 = array();
        
        $matriz1[1] = "r61_numcgm";
        $matriz1[2] = "r61_regist";
        $matriz1[3] = "r61_base";
        $matriz1[4] = "r61_descir";
        // Desconto de Imposto de Renda
        $matriz1[5] = "r61_percir";
        // Faixa na Tabela de Desconto de Imposto de Renda (Percentual de Desconto)
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
        $matriz2[7] = $tipo_arquivo;
        $matriz2[8] = 't';
        $matriz2[9] = round($valor_r984,2);
        $matriz2[10] = round(( $nosx == 2 ?  $valor_r999: $valor_r997 ),2);
        $matriz2[11] = round(( $nosx == 1 ? $valor_r988: ($nosx==2?$valor_r989:$valor_r979)),2);
        $matriz2[15] = 't';
        $matriz2[16] = $anousu ;
        $matriz2[17] = $mesusu ;
        
        //echo "<BR> PASSOU AQUI 20 depend --> ".$valor_r984;
        //echo "<BR>                base   --> ".$matrizb[$nosx];
        //echo "<BR>                rubr   --> ".$matrizr[$nosx];
        
        
        $condicaoaux  = " and r61_numcgm = ".db_sqlformat($numcgm);
        $condicaoaux .= " and r61_rubric = ".db_sqlformat($matrizr[$nosx]);
        $condicaoaux .= " and r61_regist = ".db_sqlformat($registrop );
        $condicaoaux .= " and upper(r61_folha)  = ".db_sqlformat($tipo_arquivo ) ;
        
        if (db_selectmax("ajusteir_", "select * from ajusteir ".bb_condicaosubpes("r61_" ).$condicaoaux )) {
          
          $matriz2[12] = $ajusteir_[0]["r61_dprev"];
          $matriz2[13] = $ajusteir_[0]["r61_novod"];
          $matriz2[14] = $ajusteir_[0]["r61_novop"];
          
          //echo "<BR> rubrica 223.013 --> ".$matriz2[12];
          //echo "<BR> rubrica 223.013 --> ".$matriz2[13];
          //echo "<BR> rubrica 223.013 --> ".$matriz2[14];
          
          $retornar = db_update("ajusteir", $matriz1, $matriz2, bb_condicaosubpes("r61_" ).$condicaoaux );
          
        } else {
          
          $matriz2[12] = 0;
          $matriz2[13] = 0;
          $matriz2[14] = 0;
          
          //echo "<BR> rubrica 223.014 --> ".$matriz2[12];
          //echo "<BR> rubrica 223.014 --> ".$matriz2[13];
          //echo "<BR> rubrica 223.014 --> ".$matriz2[14];
          
          $retornar = db_insert("ajusteir", $matriz1, $matriz2 );
        }
      } else {
        $condicaoaux  = " and r61_numcgm = ".db_sqlformat($numcgm );
        $condicaoaux .= " and r61_rubric = ".db_sqlformat($matrizr[$nosx] ) ;
        $condicaoaux .= " and r61_regist = ".db_sqlformat($registrop);
        $condicaoaux .= " and upper(r61_folha)  = ".db_sqlformat($tipo_arquivo ) ;
        if (db_selectmax("ajusteir_", "select * from ajusteir ".bb_condicaosubpes("r61_" ).$condicaoaux )) {
          //echo "<BR> rubrica 223.3 --> ".$transacao[$Itransacao][$siglag."rubric"];
          $retornar = db_delete("ajusteir", bb_condicaosubpes("r61_" ).$condicaoaux );
        }
      }
    }
  }
  
}


/// fim da funcao grava_ajuste_irf ///
/// calc_tabprev ///
function calc_tabprev ($base_inss=null,$codigo=null, $tpcont=null){
//echo "<br> Function calc_tabprev ($base_inss=null,$codigo=null, $tpcont=null){";

  global $perc_inss,$r14_quant,$r20_quant,$r22_quant,$inssirf,$Iinssirf;

  $calculo = 0;
  $condicaoaux = " and r33_codtab = ".db_sqlformat( $codigo )." order by r33_inic";
  global $inssirf;
  if( db_selectmax( "inssirf", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux )){
     for($Iinssirf=0;$Iinssirf<count($inssirf);$Iinssirf++){
       if( $base_inss >= $inssirf[$Iinssirf]["r33_inic"] && $base_inss <= $inssirf[$Iinssirf]["r33_fim"]){
    $r14_quant = $inssirf[$Iinssirf]["r33_perc"]; 
    $r20_quant = $inssirf[$Iinssirf]["r33_perc"];
    $r22_quant = $inssirf[$Iinssirf]["r33_perc"];
          $perc_inss = $inssirf[$Iinssirf]["r33_perc"];
          if ($tpcont == "13"){
         $perc_inss = 11;
      }
    $calculo = round(($base_inss/100)*$perc_inss,2);
    return $calculo;
       }
       
     }
//echo "<BR> 1 Iinssirf --> $Iinssirf";     
       $Iinssirf -= 1;
     if( $base_inss > $inssirf[$Iinssirf]["r33_fim"] && $inssirf[$Iinssirf]["r33_codtab"] == $codigo){
//echo "<BR> 4 Iinssirf --> $Iinssirf";     
        $r14_quant = $inssirf[$Iinssirf]["r33_perc"]; 
        $r20_quant = $inssirf[$Iinssirf]["r33_perc"];
        $r22_quant = $inssirf[$Iinssirf]["r33_perc"];
        $perc_inss = $inssirf[$Iinssirf]["r33_perc"];
  if( $tpcont == "13"){
     $perc_inss = 11;
  }
  $calculo = round(($inssirf[$Iinssirf]["r33_fim"]/100)*$perc_inss,2);
//echo "<BR> r33_fim --> ".$inssirf[$Iinssirf]["r33_fim"];
//echo "<BR> calculo --> $calculo";     
     }else{
  $r14_quant = 0;
  $r20_quant = 0;
  $r22_quant = 0;
  $calculo   = 0;
     }
  }
  return $calculo;

}


function teto_tabprev ($base_inss=null,$codigo=null, $tpcont=null){
//echo "<br> Function calc_tabprev ($base_inss=null,$codigo=null, $tpcont=null){";

  global $perc_inss,$inssirf,$Iinssirf;

  $calculo = 0;
  $condicaoaux = " and r33_codtab = ".db_sqlformat( $codigo )." order by r33_inic";
  global $inssirf;
  if( db_selectmax( "inssirf", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux )){
     for($Iinssirf=0;$Iinssirf<count($inssirf);$Iinssirf++){
       if( $base_inss >= $inssirf[$Iinssirf]["r33_inic"] && $base_inss <= $inssirf[$Iinssirf]["r33_fim"]){
         return 0;
       }
       
     }
     $Iinssirf -= 1;
     if( $base_inss > $inssirf[$Iinssirf]["r33_fim"] && $inssirf[$Iinssirf]["r33_codtab"] == $codigo){
        $perc_inss = $inssirf[$Iinssirf]["r33_perc"];
        if( $tpcont == "13"){
           $perc_inss = 11;
        }
        $calculo = round(($inssirf[$Iinssirf]["r33_fim"]/100)*$perc_inss,2);
     }else{
        $calculo = 0;
     }
  }
  return $calculo;

}

/// fim da funcao calc_tabprev ///
/// calc_irf ///
function calc_irf($r20_rubr_=null, $area=null, $sigla=null, $sigla2=null, $nro_do_registro=null, $operacao=null) {
  
  global $rubrica_maternidade, $rubrica_licenca_saude, $rubrica_acidente,$cadferia,$subpes,$vlr_desc_prev_ferias_F, $db_debug;
  
  global $Ipessoal,$opcao_geral, $d08_carnes,$desc_prev,$cfpess,$pessoal,$situacao_funcionario,$r14_valor;
  
  global $base_irf, $base_irfb,$dias_pagamento,$valor_salario_maternidade,$r14_quant,$vlr_sal_saude_ou_acidente;
  
  global $n,$transacao,$r20_rubr,$gerfcom_;
  global $anousu, $mesusu, $DB_instit,$dias_do_mes;
  
  global $F001, $F002, $F004, $F005, $F006,
  $F007, $F008, $F009, $F010, $F011,
  $F012, $F013, $F014, $F015, $F016,
  $F017, $F018, $F019, $F020, $F021,
  $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  
  if ($db_debug == true) {
    echo "[calc_irf] INICIO DO PROCESSAMENTO DA FUNCAO calc_irf... <br>";
  }
  if (db_empty($cadferia[0]["r30_proc2"]) ) {
    $r30_proc = "r30_proc1";
    $r30_peri = "r30_per1i";
    $r30_perf = "r30_per1f";
  } else {
    $r30_proc = "r30_proc2";
    $r30_peri = "r30_per2i";
    $r30_peri = "r30_per2f";
  }
  $ultdat = db_str(ndias(per_fpagto(1)),2,0,'0')."/".per_fpagto(1);
  
  
  global $quais_diversos;
  eval($quais_diversos);
  
  $base_irf  = 0;
  $base_irfb = 0;
  
  if ($db_debug == true) { 
     echo "[calc_irf] Inicio do Calculo das Rubricas IRRF (R913, R914 e R915) <br>";
     echo "[calc_irf] 27 - chamando a função calc_rubrica() <br>";
  }

  $r20_rubr  = $r20_rubr_;
  $r20_rubrp = $r20_rubr;
  $r07_valor = calc_rubrica($r20_rubr, $area, $sigla, $sigla2, $nro_do_registro, $operacao);
  
  if ($db_debug == true) {
     echo "[calc_irf] Fim do Calculo das Rubricas IRRF (R913, R914 e R915) <br><br>"; 
     echo "[calc_irf] Valor Inicial da Base IRRF $r20_rubr baseado no que esta marcado na Base --> $r07_valor <br>"; 
     echo "[calc_irf] --> Inicio - Abate o Desconto e a Previdencia do Adiantamento de Ferias do Mes Anterior - Tipo 'D' <br>";
  }   

  if ($r20_rubr == "R915" && $opcao_geral == 3 && $cadferia[0][$r30_proc] < $subpes ) {
    if (('t' == $cadferia[0]["r30_paga13"])
    && db_val(substr("#".$cadferia[0][$r30_proc],6,2)) == db_month($cadferia[0][$r30_peri] )
    && db_val(substr("#".$cadferia[0][$r30_proc],6,2)) < db_month($cadferia[0][$r30_perf] ) ) {
      
      $base_irf  -= $cadferia[0]["r30_descad"];
      $r07_valor -= $cadferia[0]["r30_descad"];
      if($db_debug == true) { echo "[calc_irf] 1 - Abate o Desconto e a Previdencia do Adiantamento de Ferias do Mes Anterior -> $r07_valor -= ".$cadferia[0]["r30_descad"]."<br>"; }
      
    } else {
      
      $base_irf  -= round($cadferia[0]["r30_descad"] / ($cadferia[0]["r30_ndias"]-$cadferia[0]["r30_abono"]+$F020) * ($F019+$F020) , 2 );
      $r07_valor -= round($cadferia[0]["r30_descad"] / ($cadferia[0]["r30_ndias"]-$cadferia[0]["r30_abono"]+$F020) * ($F019+$F020) , 2 );
      if($db_debug == true) { echo "[calc_irf] 2 - Abate o Desconto e a Previdencia do Adiantamento de Ferias do Mes Anterior -> $r07_valor -= round(".$cadferia[0]["r30_descad"]." / (".$cadferia[0]["r30_ndias"]."-".$cadferia[0]["r30_abono"]."+".$F020.") * (".$F019."+".$F020.") , 2 );<br>"; }
      
    }
  }
  if($r20_rubr == "R915" 
     && $opcao_geral == 3 
     && $pessoal[$Ipessoal]["r01_tbprev"] > 0
     && 'f' == $cadferia[0]["r30_paga13"]
     && strtolower($cfpess[0]["r11_fersal"]) == "f" 
     && 't' == $cfpess[0]["r11_recalc"] ){

      global $transacao; 

      $condicaoaux  = " and rh01_numcgm = ".db_sqlformat($pessoal[$Ipessoal]["r01_numcgm"] );
      $condicaoaux .= " and ( rh05_recis is null ";
      $condicaoaux .= "  or (extract(year from rh05_recis) >= ".db_sqlformat(substr("#".$subpes,1,4));
      $condicaoaux .= " and  extract(month from rh05_recis) >= ".db_sqlformat(substr("#".$subpes,6,2))."))";
      db_selectmax("transacao", "select rh02_regist as r01_regist from rhpessoalmov 
                       inner join rhpessoal    on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                       inner join cgm          on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                       left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes 
                       ".bb_condicaosubpes("rh02_" ).$condicaoaux );
      
      if(count($transacao) < 2){
        $condicaoaux  = " and r31_tpp = 'D' and r31_pd = 2 and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"]);
        if (db_selectmax("transacao", "select sum(r31_valor) as descontod from gerffer ".bb_condicaosubpes("r31_" ).$condicaoaux )) {
          $r07_valor -= $transacao[0]["descontod"]  ;
          if($db_debug == true) { echo "[calc_irf] Valor da Base IRF depois de R979 DEDUCOES P/IRRF (FERIAS) --> $r07_valor <br>"; }
        }
      }
  }  
  if($db_debug == true) { echo "[calc_irf] --> Fim - Abate o Desconto e a Previdencia do Adiantamento de Ferias do Mes Anterior - Tipo 'D'<br><br>"; }

  $base_irfb = $r07_valor;

  if($db_debug == true) { echo "[calc_irf] --> Inicio do leventamento do Desconto da Previdencia Complementar e a Base da Previdencia Complementar <br>"; }

  $base_desconto_prev_complementar = 0  ;
  $base_folha_complementar = 0;
  if ($opcao_geral == 1 || $opcao_geral==4) {
    if ($r20_rubr == "R913" ) {
      // R913 % IRRF S/SALARIO
      $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " and r48_rubric = ".db_sqlformat("R981" );
      // R981 BASE IRF SALARIO (BRUTA)
      if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        $base_folha_complementar = $gerfcom_[0]["r48_valor"];
        $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
        $condicaoaux .= " and r48_rubric in ( 'R901','R904','R907','R910' )";
        // R901 % Inss S/ SALÁRIO
        // R904 % Funpas S/SALARIO
        // R907 % F Inativos S/SALARIO
        // R910 % Previdencia 4 S/SALARIO
        if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
          $base_desconto_prev_complementar  = $gerfcom_[0]["r48_valor"];
          if($db_debug == true) { echo "[calc_irf] 1 Desconta da Previdencia Complementar a abater na Base IRRF   -> $base_desconto_prev_complementar <br>"; }
        }
      }
    }
    if ($r20_rubr == "R914" ) {
      // R914 % IRRF S/13. SALARIO
      $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " and r48_rubric = ".db_sqlformat("R982" );
      // R982 BASE IRF 13O SAL (BRUTA)
      if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        $base_folha_complementar = $gerfcom_[0]["r48_valor"];
        $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
        $condicaoaux .= " and r48_rubric in ( 'R902','R905','R908','R911')";
        // R902 % Inss S/ 13o SALÁRIO
        // R905 % Funpas S/13§ SALARIO
        // R908 % F Inativos S/13§ SALARIO
        // R911 % Previdencia 4 S/13§ SALARIO
        if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
          $base_desconto_prev_complementar  = $gerfcom_[0]["r48_valor"];
          if($db_debug == true) { echo "[calc_irf] 2 Desconta da Previdencia Complementar a abater na Base IRRF  -> $base_desconto_prev_complementar <br>"; }
        }
      }
    }
    if ($r20_rubr == "R915" ) {
      // R915 % IRRF S/FERIAS
      $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " and r48_rubric = ".db_sqlformat("R983" );
      // R983 BASE IRF FERIAS (BRUTA)
      if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        $base_folha_complementar = $gerfcom_[0]["r48_valor"];
        $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
        $condicaoaux .= " and r48_rubric in ( 'R903','R906','R909','R912' )";
        // R903 % Inss S/ FÉRIAS
        // R906 % Funpas S/FERIAS
        // R909 % F Inativos S/FERIAS
        // R912 % Previdencia 4 S/FERIAS
        if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
          $base_desconto_prev_complementar  = $gerfcom_[0]["r48_valor"];
          if($db_debug == true) { echo "[calc_irf] 3 Desconta da Previdencia Complementar a abater na Base IRRF   -> $base_desconto_prev_complementar <br>"; }
        }
      }
    }
    
  }
  if($db_debug == true) { echo "[calc_irf] --> Fim do leventamento do Desconto da Previdencia Complementar e a Base da Previdencia Complementar<br><br>"; }

  $r07_valor += $base_folha_complementar - $base_desconto_prev_complementar;
  
  if ($db_debug == true) { 
    echo "[calc_irf] Valor da Base depois de acrescentar a Base do IRRF Complementar e abater o Desconto da Prev Complementar --> $r07_valor += $base_folha_complementar - $base_desconto_prev_complementar <br>"; 
    echo "[calc_irf] --> Inicio - Abate o Desconto por Dependentes e o desconto da Previdencia <br>"; 
  } 

  if ($d08_carnes != "riogrande") {
    if ($r20_rubr=="R915" && ( $opcao_geral == 3 || $opcao_geral == 11 ) && (  ( strtolower($cfpess[0]["r11_fersal"]) == "f"
    && ('t' == $cadferia[0]["r30_paga13"]) )
    || 'f' == $cadferia[0]["r30_paga13"]) ) {
      // ir calculado ferias
      // D901 VLR DESC IRF P/DEPENDENTE
      
      $r07_valor -= ($D901*$F005);
      if($db_debug == true) { echo "[calc_irf] 1 - Abate o Desconto por Dependentes -> $r07_valor -= ($D901*$F005) <br>"; }
    } else {
      // D901 VLR DESC IRF P/DEPENDENTE
      if ($area == 'pontofe' ) {
         $r07_valor -= ($vlr_desc_prev_ferias_F + ($D901*$F005));
         if($db_debug == true) { echo "[calc_irf] 2 - Abate o Desconto por Dependentes e a Previdencia -> $r07_valor -= ($lr_desc_prev_ferias_F + ($D901*$F005)) <br>"; }
      }else{
         $r07_valor -= ($desc_prev + ($D901*$F005));
         if($db_debug == true) { echo "[calc_irf] 3 - Abate desconto";}
      }
    }
    
  } else {
    if ($pessoal[$Ipessoal]["r01_tbprev"] == 2 ) {
      // D901 VLR DESC IRF P/DEPENDENTE
      $r07_valor -= ($D901*$F005);
      if($db_debug == true) { echo "[calc_irf] 1 - calc_irf r07_valor -> $r07_valor <br>"; }
    } else {
      // D901 VLR DESC IRF P/DEPENDENTE
      if ($area == 'pontofe' ) {
         $r07_valor -= ($vlr_desc_prev_ferias_F + ($D901*$F005));
      }else{
         $r07_valor -= ($desc_prev + ($D901*$F005));
      }
      if($db_debug == true) { echo "[calc_irf] 2 - calc_irf r07_valor -> $r07_valor <br>"; }
    }
  }
  
  if ($db_debug == true) { 
  echo "[calc_irf] --> Fim - Abate o Desconto por Dependentes e o Desconto da Previdencia <br><br>"; 
    echo "[calc_irf] --> Inicio do VLR DESC IRF P/65 ANOS --> D902 <br>";
  }  

  if ( $r07_valor > 0 && db_at(strtolower($pessoal[$Ipessoal]["r01_tpvinc"]),"i-p") > 0 && ver_idade($ultdat,db_dtoc($pessoal[$Ipessoal]["r01_nasc"])) >= 65 ) {
    
    $r20_rubr_ant = $r20_rubr;
   
    if ($db_debug) { 
      echo "[calc_irf]1 - Abate VLR DESC IRF P/65 ANOS --> D902 -> $r07_valor <br>"; 
    }

    if ( $r07_valor < $D902 ){
      
      if ($base_irfb > $D902 ){
        $r07_valor = $D902;
        $r14_valor = $D902;
        if ($db_debug == true) { echo "[calc_irf] 95 - r14_valor = $r14_valor  <br>"; }
      } else {
        $r14_valor = $base_irfb;
        if ($db_debug == true) { echo "[calc_irf] 96 - r14_valor = $r14_valor  <br>"; }
      }
    } else {

      $r07_valor -= $D902;
      $r14_valor = $D902;
      if ($db_debug == true) { echo "[calc_irf] 97 - r14_valor = $r14_valor  <br>"; }
    }
    
    $r20_rubr_ant = $r20_rubr;
    
    if ($r20_rubr == "R913" || $r20_rubr == "R915" ) {
      $r20_rubr  = "R997" ;
      // R997 DEDUCAO INAT/PENS +65ANOS
    } else if ($r20_rubr == "R914") {
      $r20_rubr  = "R999" ;
      // R999 DEDUCAO INAT/PENS+65ANOS 13SAL
    }
    
    $r14_quant = 0;
    
    if ($db_debug) { 
      echo "[calc_irf] 31 - Chamando a função grava_gerf() <br>"; 
    }
    grava_gerf($area);
    
    $r20_rubr = $r20_rubr_ant;
  }
  
  $base_irf += $r07_valor;

  if ($db_debug == true) { 
    echo "[calc_irf] --> Fim do VLR DESC IRF P/65 ANOS <br><br>"; 
    echo "[calc_irf] Valor da Base do IRF --> $base_irf <br>";
    echo "[calc_irf] --> Inicio do Abatimento das deducoes ( Pensao Alimenticia ) Complementar <br> ";
  }
  
  $base_folha_complementar_deducoes = 0;
  
  
  if ($opcao_geral == 1 || $opcao_geral == 4 ) {
    
    
    if ($r20_rubr == "R913" ) {
      // R913 I.R.R.F.
      $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " and r48_rubric = ".db_sqlformat("R988" );
      // R988 DEDUCOES P/IRRF(SALARIO)
      if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        $base_folha_complementar_deducoes = $gerfcom_[0]["r48_valor"];
      }
    }
    
    if ($r20_rubr == "R914"  ) {
      // R914 IRRF S/ 13o SALARIO
      $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " and r48_rubric = ".db_sqlformat("R989" );
      // R989 DEDUCOES P/IRRF(13.SALARIO)
      if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        $base_folha_complementar_deducoes = $gerfcom_[0]["r48_valor"];
      }
    }
    
    if ($r20_rubr == "R915" ) {
      // R915 IRRF FERIAS
      $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      $condicaoaux .= " and r48_rubric = ".db_sqlformat("R988" );
      // R988 DEDUCOES P/IRRF(SALARIO)
      if (db_selectmax("gerfcom_", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        $base_folha_complementar_deducoes = $gerfcom_[0]["r48_valor"];
      }
    }
  }
 

  $base_irf -= $base_folha_complementar_deducoes;
  $r07_valor -= $base_folha_complementar_deducoes;

  if ($db_debug == true) {
    echo "[calc_irf] Abatimento das deducoes ( Pensao Alimenticia ) Complementar --> $base_irf -= $base_folha_complementar_deducoes<br>";
    echo "[calc_irf] --> Fim do Abatimento das deducoes ( Pensao Alimenticia ) Complementar <br><br>";
  }
  if ( $r20_rubr == "R913" || $r20_rubr == "R915" ) {
    if ($opcao_geral == 1) {
      // Abatimento da Pensao Alimenticia
      $condicaoaux  = " and r14_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      if (db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
        for ($xy=0; $xy<count($transacao); $xy++) {
          if ($transacao[$xy]["r14_rubric"] == "R988") {
            // R988 DEDUCOES P/IRRF(SALARIO)
            $base_irf -= $transacao[$xy]["r14_valor"]  ;
            $r07_valor -= $transacao[$xy]["r14_valor"]  ;
            //echo "<br> Valor da Base IRRF depois de R988 DEDUCOES P/IRRF(SALARIO) --> $r07_valor -= ".$transacao[$xy]["r14_valor"];
            break;
          }
        }
      }
      // Fim do Abatimento da Pensao Alimenticia
    } else if ($opcao_geral == 8) {
      // Abatimento da Pensao Alimenticia
      $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      if (db_selectmax("transacao", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        for ($xy=0; $xy<count($transacao); $xy++) {
          if ($transacao[$xy]["r48_rubric"] == "R988") {
            // R988 DEDUCOES P/IRRF(SALARIO)
            $base_irf -= $transacao[$xy]["r48_valor"]  ;
            $r07_valor -= $transacao[$xy]["r48_valor"]  ;
            //echo "<br> Valor da Base IRF depois de R988 DEDUCOES P/IRRF(SALARIO) --> $r07_valor -= ".$transacao[$xy]["r14_valor"];
            break;
          }
        }
      }
      // Fim do Abatimento da Pensao Alimenticia
    } else if ( ($opcao_geral == 3  || $opcao_geral == 11 )
      && (  (strtolower($cfpess[0]["r11_fersal"]) == "f"
      && ('t' == $cadferia[0]["r30_paga13"]) )
      || 'f' == $cadferia[0]["r30_paga13"]) ) {
      // ir calculado ferias
      $Ok1 =  ($F019 >= 30 && $cadferia[0][$r30_proc] == $subpes
      && db_month($cadferia[0][$r30_peri] ) == $mesusu
      && db_month($cadferia[0][$r30_perf] ) == $mesusu );
      
      $Ok2 = ( $F019 == $dias_do_mes && $cadferia[0][$r30_proc] == $subpes
      && db_month($cadferia[0][$r30_peri] ) == $mesusu
      && db_substr($subpes,6,2) == "02" );
      // R979 DEDUCOES P/IRRF (FERIAS)
      $base_irf  -= $vlr_desc_prev_ferias_F;
      $r07_valor -= $vlr_desc_prev_ferias_F;
      $condicaoaux  = " and r31_rubric = 'R979' and r31_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"]);
      if (db_selectmax("transacao", "select * from gerffer ".bb_condicaosubpes("r31_" ).$condicaoaux )) {
            $base_irf -= $transacao[0]["r31_valor"]  ;
            $r07_valor -= $transacao[0]["r31_valor"]  ;
            //echo "<br> Valor da Base IRF depois de R979 DEDUCOES P/IRRF (FERIAS) --> $r07_valor -= ".$transacao[$xy]["r31_valor"];
      }
    }
    if ($r20_rubr =="R913") {
      if (( $situacao_funcionario == 6 ) ) {
        // Afastado Doenca + 15 Dias
        if (($dias_pagamento == 0 && db_empty($rubrica_licenca_saude ))) {
          $base_irf -= $vlr_sal_saude_ou_acidente;
          $r07_valor -= $vlr_sal_saude_ou_acidente;
        }
        if ($dias_pagamento > 0 && !db_empty($rubrica_licenca_saude ) ) {
          $base_irf += $vlr_sal_saude_ou_acidente;
          $r07_valor += $vlr_sal_saude_ou_acidente;
        }
      } else if (( $situacao_funcionario == 3 ) ) {
        // Afastado Acidente de Trabalho + 15 Dias
        if (($dias_pagamento == 0 && db_empty($rubrica_acidente ))) {
          $base_irf -= $vlr_sal_saude_ou_acidente;
          $r07_valor -= $vlr_sal_saude_ou_acidente;
        }
        if ($dias_pagamento > 0 && !db_empty($rubrica_acidente )) {
          $base_irf += $vlr_sal_saude_ou_acidente;
          $r07_valor += $vlr_sal_saude_ou_acidente;
        }
      } else if (( $situacao_funcionario == 5 ) ) {
        // Afastado Aux. Maternidade
        if (($dias_pagamento == 0 && db_empty($rubrica_maternidade ))) {
          $base_irf  -= $valor_salario_maternidade;
          $r07_valor -= $valor_salario_maternidade;
        }
        if ($dias_pagamento > 0 && !db_empty($rubrica_maternidade )) {
          $base_irf  += $valor_salario_maternidade;
          $r07_valor += $valor_salario_maternidade;
        }
      }
      
    }
  } else if($r20_rubr == "R914") {
    if ($opcao_geral == 1) {
      // R989 DEDUCOES P/IRRF(13.SALARIO)
      $condicaoaux  = " and r14_rubric = 'R989' and r14_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      if (db_selectmax("transacao", "select * from gerfsal ".bb_condicaosubpes("r14_" ).$condicaoaux )) {
        $base_irf  -= $transacao[0]["r14_valor"]  ;
        $r07_valor -= $transacao[0]["r14_valor"]  ;
        //echo "<br> Valor da Base IRF depois de R989 DEDUCOES P/IRRF(13.SALARIO) --> $r07_valor -= ".$transacao[$Itra]["r14_valor"];
      }
    } else if ($opcao_geral == 8) {
      // R989 DEDUCOES P/IRRF(13.SALARIO)
      $condicaoaux  = " and r48_rubric = 'R989' and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      if (db_selectmax("transacao", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
        $base_irf  -= $transacao[0]["r48_valor"]  ;
        $r07_valor -= $transacao[0]["r48_valor"]  ;
        //echo "<br> Valor da Base IRF depois de R989 DEDUCOES P/IRRF(13.SALARIO) --> $r07_valor -= ".$transacao[$Itra]["r48_valor"];
      }
    } else if ($opcao_geral == 5 ) {
      // R989 DEDUCOES P/IRRF(13.SALARIO)
      // R980 DESCONTO ADIA.PENSAO AL.S/13S.
      $condicaoaux = " and r35_rubric = 'R989' and r35_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      if (db_selectmax("transacao", "select * from gerfs13 ".bb_condicaosubpes("r35_" ).$condicaoaux )) {
        $base_irf  -= $transacao[0]["r35_valor"]  ;
        $r07_valor -= $transacao[0]["r35_valor"]  ;
        //echo "<br> Valor da Base IRF depois de R989 DEDUCOES P/IRRF(13.SALARIO) --> $r07_valor -= ".$transacao[$Itra]["r35_valor"];
      }
      $condicaoaux = " and r35_rubric = 'R980' and r35_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
      if (db_selectmax("transacao", "select * from gerfs13 ".bb_condicaosubpes("r35_" ).$condicaoaux )) {
        $base_irf  -= $transacao[0]["r35_valor"]  ;
        $r07_valor -= $transacao[0]["r35_valor"]  ;
        //echo "<br> Valor da Base IRF depois de R989 DEDUCOES P/IRRF(13.SALARIO) --> $r07_valor -= ".$transacao[$Itra]["r35_valor"];
      }
    }
  }


  if ($r07_valor > 0.009) {

    //echo "<BR> Base do IRRF antes de ir na Tabela de IRRF -> $r07_valor ";
    
    $r14_valor = round(le_irf($r07_valor,"1"),2) ;
    if ($db_debug == true) { echo "[calc_irf] 98 - r14_valor = $r14_valor  <br>"; }
    
    //echo "<BR> Valor do IRRF ------------------------------> $r14_valor ";

    // D911 VALOR MINIMO P/ DESC DE IRF

    if (db_at($r20_rubr,"R913-R915-R914")>0 && $r14_valor <= $D911) {
      $r14_valor = 0;
      if ($db_debug == true) { echo "[calc_irf] 99 - r14_valor = $r14_valor  <br>"; }
    } else {
      if ($opcao_geral == 1) {
        $condicaoaux  = " and r48_regist = ".db_sqlformat($pessoal[$Ipessoal]["r01_regist"] );
        $condicaoaux .= " and r48_rubric = ".db_sqlformat($r20_rubr );
        if (db_selectmax("transacao", "select * from gerfcom ".bb_condicaosubpes("r48_" ).$condicaoaux )) {
          $r14_valor -= $transacao[0]["r48_valor"];
          if ($db_debug == true) { echo "[calc_irf] 100 - r14_valor = $r14_valor  <br>"; }
          if ($r14_valor < 0) {
            $r14_valor = 0;
            if ($db_debug == true) { echo "[calc_irf] 101 - r14_valor = $r14_valor  <br>"; }
          }
        }
      }
    }
    
    if ($db_debug == true) { echo "[calc_irf] 32 - Chamando a função grava_gerf() <br>"; }
    grava_gerf($area);

    if( ($area == "pontof13" || $area == "pontoprovf13" ) && $mesusu < $cfpess[0]["r11_mes13"]){
        // controle p/ nao calc prev e irf no adto do 13o salario (04/04/96)
        $base_irf = 0;
    }
    if( $base_irf > 0.009){
       $r14_valor = ($base_irf);
       if ($db_debug == true) { echo "[calc_irf] 102 - r14_valor = $r14_valor  <br>"; }
       if( $r14_valor < 0){
          $r14_valor = 0;
          if ($db_debug == true) { echo "[calc_irf] 103 - r14_valor = $r14_valor  <br>"; }
       }
    }
    $base_irf = 0;
  }

  if ($db_debug == true) {
    echo "[calc_irf] chamando a função gravb_base_irf($area, $r20_rubrp)... <br>";
  }
  gravb_base_irf($area,$r20_rubrp);
  
  if ($db_debug == true) {
    echo "[calc_irf] FIM DO PROCESSAMENTO DA FUNÇÃO calc_irf... <br><br>";
  }
}


/// fim da funcao calc_irf ///
/// le_irf ///
function le_irf ($base_irf=null,$codigo=null){

  global $r14_quant,$r20_quant,$r22_quant,$inssirf,$Iinssirf,$inssirf_r33_perc;
  
  $inssirf_r33_perc = 0;
  
  $condicaoaux = " and r33_codtab = ".db_sqlformat( $codigo );
  db_selectmax("inssirf","select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux );

  $calculo = 0;
  for($Iinssirf=0;$Iinssirf < count($inssirf);$Iinssirf++){
     if(!db_empty($inssirf[$Iinssirf]["r33_fim"])){
  if( $base_irf >= $inssirf[$Iinssirf]["r33_inic"] && $base_irf <= $inssirf[$Iinssirf]["r33_fim"]){
     $r14_quant = $inssirf[$Iinssirf]["r33_perc"];
     $r20_quant = $inssirf[$Iinssirf]["r33_perc"];
     $r22_quant = $inssirf[$Iinssirf]["r33_perc"];
     $inssirf_r33_perc = $inssirf[$Iinssirf]["r33_perc"];
     $calculo = ($base_irf/100)*($inssirf[$Iinssirf]["r33_perc"])-($inssirf[$Iinssirf]["r33_deduzi"]);
     //echo "<BR> Faixa atingida para calculo do  IRRF --> ( $base_irf >= ".$inssirf[$Iinssirf]["r33_inic"]." && $base_irf <= ".$inssirf[$Iinssirf]["r33_fim"].")";
     //echo "<BR> Calculo do IRRF --> $calculo = ($base_irf/100)*(".$inssirf[$Iinssirf]["r33_perc"].")-(".$inssirf[$Iinssirf]["r33_deduzi"].")";
     break;
  }
     }else{
  if( $base_irf >= $inssirf[$Iinssirf]["r33_inic"]){
     $r14_quant = $inssirf[$Iinssirf]["r33_perc"];
     $r20_quant = $inssirf[$Iinssirf]["r33_perc"];
     $r22_quant = $inssirf[$Iinssirf]["r33_perc"];
     $inssirf_r33_perc = $inssirf[$Iinssirf]["r33_perc"];
     $calculo = ($base_irf/100)*($inssirf[$Iinssirf]["r33_perc"])-($inssirf[$Iinssirf]["r33_deduzi"]);
     //echo "<BR> Faixa atingida para calculo do  IRRF --> ( $base_irf >= ".$inssirf[$Iinssirf]["r33_inic"];
     //echo "<BR> Calculo do IRRF --> $calculo = ($base_irf/100)*(".$inssirf[$Iinssirf]["r33_perc"].")-(".$inssirf[$Iinssirf]["r33_deduzi"].")";
     break;
  }
     }
  }
  return $calculo;
}


/// fim da funcao le_irf ///
/// verifica_ferias ///

function verifica_ferias_100() {
  
  global $pessoal , $Ipessoal, $cadferia, $pontofe_, $opcao_geral,$calcular_tipo_proc,
  $subpes  , $d08_carnes, $gerffer_, $cfpess ,$F019,$inssirf_base_ferias;
  
  global $anousu, $mesusu, $DB_instit;
  
  global $F016,
  $F017,
  $F018,
  $F019,
  $F020,
  $F021,
  $F023;
  
  global $rub_especial_ferias,           // $cfpess[0]["r11_ferias"]
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
    
    // Calculo especifico para riogrande
    
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
      
      //echo "<BR>  verifica_ferias_100 F016 --> $F016";
      //echo "<BR>  verifica_ferias_100 F017 --> $F017";
      //echo "<BR>  verifica_ferias_100 F018 --> $F018";
      //echo "<BR>  verifica_ferias_100 F019 --> $F019";
      //echo "<BR>  verifica_ferias_100 F020 --> $F020";
      //echo "<BR>  verifica_ferias_100 F021 --> $F021";
      //echo "<BR>  verifica_ferias_100 F023 --> $F023";
      
      //echo "<BR> opcao_geral --> $opcao_geral";
      //echo "<BR> r30_proc --> ".$cadferia[0][$r30_proc];
      //echo "<BR> r30_paga13 --> ".$cadferia[0]["r30_paga13"];
      //echo "<BR> r11_fersal --> ".strtolower($cfpess[0]["r11_fersal"]);
      //echo "<BR> r30_ponto  --> ".$cadferia[0]["r30_ponto"];
      // opcao_geral --> 1 - Salario
      //                 8 - Complementar
      if (( $opcao_geral == 1 && strtolower($cadferia[0]["r30_ponto"]) == "s" )
         ||
         ( $opcao_geral == 1 && $cadferia[0][$r30_proc]  < $subpes )
         ||
         ( $opcao_geral == 1 && $cadferia[0][$r30_proc] == $subpes && strtolower($cadferia[0]["r30_ponto"]) == "c"
            && ('t' == $cadferia[0]["r30_paga13"]) && strtolower($cfpess[0]["r11_fersal"]) == "f" )
         ||
         ( $opcao_geral == 8 && strtolower($cadferia[0]["r30_ponto"]) == "c" && $cadferia[0][$r30_proc] == $subpes ) ) {
        
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
        if (($opcao_geral == 8 && strtolower($cadferia[0]["r30_ponto"]) == "c")
        || ($opcao_geral == 1 && strtolower($cadferia[0]["r30_ponto"]) == "s" )  ) {
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
              if ($opcao_geral == 1) {
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
                
                $retornar = db_insert("pontofs",$matriz1, $matriz2 );
              }
            } else if ($gerffer_[$Igerffer]["r31_rubric"] == $cfpess[0]["r11_feabot"]) {
              // Abono mes anterior
              if ($opcao_geral == 1) {
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
                
                $retornar = db_insert("pontofs",$matriz1, $matriz2 );
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
                  || ( $opcao_geral == 1  && 'f' == $cadferia[0]["r30_paga13"]
                  && $cadferia[0][$r30_proc] < $subpes )
                  || ( $opcao_geral == 1
                  && ( ( db_month($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],6,2))
                  && db_year($cadferia[0][$r30_peri]) == db_val(substr("#".$cadferia[0][$r30_proc],1,4)))
                  || ( db_month($cadferia[0][$r30_peri]) < db_val(substr("#".$cadferia[0][$r30_proc],6,2))
                  && db_year($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],1,4))) ))
                  || ($opcao_geral == 1
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
                 || ( $opcao_geral == 1
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
                   //echo "<BR> valor_ferias_13 --> $rub_especial_ferias_13";
                 } else {
                   $rub_especial_ferias_adia += $gerffer_[$Igerffer]["r31_valor"];
                   //echo "<BR> 2 r10_rubric = ".$gerffer_[$Igerffer]["r31_rubric"]." rub_especial_ferias_adia --> $rub_especial_ferias_adia";
                   //echo "<BR> 3 valor  = ".$gerffer_[$Igerffer]["r31_valor"];
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
    
                
                if ($opcao_geral == 1  ) {
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
                           $retornar = db_insert("pontofs",$matriz1, $matriz2 );
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
                         $retornar = db_update("pontocom",$matriz1, $matriz2 , bb_condicaosubpes("r47_" ).$condicaoaux );
                       }else{
                         $matriz2[4] = ( $gerffer_[$Igerffer]["r31_quant"] );
                         $matriz2[5] = ( $gerffer_[$Igerffer]["r31_valor"] );
                         $retornar = db_insert("pontocom",$matriz1, $matriz2 );
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
                  ( $opcao_geral == 1 && ( $ferias_paga_1_3 || ( 'f' == $cadferia[0]["r30_paga13"] && $cadferia[0][$r30_proc] < $subpes ) ) )
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
                  || ( $opcao_geral == 1
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
              $nr_dias_mes = pg_result(pg_query("select ndias(".db_year($cadferia[0][$r30_peri] ).",".db_month($cadferia[0][$r30_peri] ).") as ndias "),0,"ndias");
              
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
                  
                  if ($opcao_geral == 1) {
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
                      db_insert("pontofs",$matriz1, $matriz2 );
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
                      db_update("pontocom",$matriz1, $matriz2 , bb_condicaosubpes("r47_" ).$condicaoaux );
                    } else {
                      db_insert("pontocom",$matriz1, $matriz2 );
                    }
                  }
                }
              } else {

                // Repassa a rubrica de Pensao Alimenticia de Férias para o salário ou a complementar

                //echo "<BR> passo aqui 2 -->".$gerffer_[$Igerffer]["r31_rubric"];
                if ($gerffer_[$Igerffer]["r31_rubric"] == $rubrica_ferias_pensao && $ir_calculado_ferias ) {
                  // r11_fersal --> Paga como <F>erias ou <S>alario
                  
                  if ($opcao_geral == 1) {
                    
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
                        db_insert("pontofs",$matriz1, $matriz2 );
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
                        db_update("pontocom",$matriz1, $matriz2 , bb_condicaosubpes("r47_" ).$condicaoaux );
                      } else {
                        $matriz2[4] = ( $gerffer_[$Igerffer]["r31_quant"] );
                        $matriz2[5] = ( $gerffer_[$Igerffer]["r31_valor"] );
                        db_insert("pontocom",$matriz1, $matriz2 );
                      }
                    }
                  }
                  
                } else if ($cadferia[0][$r30_proc] == $subpes
                && substr("#".$gerffer_[$Igerffer]["r31_rubric"],1,1) != "R"
                && db_val($gerffer_[$Igerffer]["r31_rubric"]) < 2000 ) {
                  
                  // Repassa as rubricas as Rubricas de desconto de salario que estão no Ponto calculado das Férias para o Ponto de Salario ou complementar
                  
                  if ($opcao_geral == 1) {
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
                    
                    $retornar = db_insert("pontofs",$matriz1, $matriz2 );
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
                    
                    $retornar = db_insert("pontocom",$matriz1, $matriz2 );
                  }
                }
              }
            }
          }
          //echo "<BR> entrou grava_rubricas_ferias ";
          grava_rubricas_ferias_especiais($r30_proc,$r30_peri,$r30_perf);
          //echo "<BR> sai grava_rubricas_ferias ";
        }
        //echo "<BR> entrou aqui verifica_ferias_100 --> ";
      }
    }
  }
}

function grava_rubricas_ferias_especiais($r30_proc,$r30_peri,$r30_perf)
{
  
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
      
      db_insert("pontofs",$matriz1, $matriz2 );
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
        
        db_insert("pontofs",$matriz1, $matriz2 );
        
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
      
      db_insert("pontofs",$matriz1, $matriz2);
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
          
          db_insert("pontofs",$matriz1, $matriz2 );
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
          
          db_insert("pontofs",$matriz1, $matriz2 );
        }
        if ($rub_especial_ferias_abono > 0 ) {
          $matriz2[2] = $cfpess[0]["r11_ferabo"];
          // Abono de ferias:
          $matriz2[5] = $rub_especial_ferias_abono;
          
          db_insert("pontofs",$matriz1, $matriz2 );
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
      db_insert("pontocom",$matriz1, $matriz2 );
    }
    if ($cadferia[0][$r30_proc] == $subpes &&  $calcular_tipo_proc ) {
      if ($rub_especial_ferias_adia > 0  ) {
        $matriz2[2] = $cfpess[0]["r11_feradi"];
        $matriz2[4] = $F023;
        $matriz2[5] = $rub_especial_ferias_adia;
        //echo "<BR> 5 gravar_valor_adiantamento_ferias_a_descontar --> $gravar_valor_adiantamento_ferias_a_descontar --> ";
        db_insert("pontocom",$matriz1, $matriz2 );
      }
    }
    
    if ($rub_especial_ferias_13 > 0) {
      $matriz2[2] = $cfpess[0]["r11_fer13"];
      $matriz2[4] = 0;
      $matriz2[5] = $rub_especial_ferias_13;
      
      db_insert("pontocom",$matriz1, $matriz2 );
    }
    
    if (('t' ==  $cfpess[0]["r11_pagaab"] )) {
      // Paga abono ferias
      $matriz2[4] = 0;
      if (db_substr(db_dtos($cadferia[0][$r30_peri]),1,6) > db_strtran($subpes,"/","")) {
        if ($rub_especial_ferias_13a+$rub_especial_ferias_abono > 0 &&  $calcular_tipo_proc) {
          $matriz2[2] = $cfpess[0]["r11_fadiab"];
          // Rubrica onde será lançado o adiantamento s/abono de férias
          $matriz2[5] = $rub_especial_ferias_13a+$rub_especial_ferias_abono;
          
          db_insert("pontocom",$matriz1, $matriz2 );
        }
      } else {
        if ($rub_especial_ferias_13a > 0) {
          $matriz2[2] = $cfpess[0]["r11_fer13a"];
          $matriz2[5] = $rub_especial_ferias_13a;
          
          db_insert("pontocom",$matriz1, $matriz2 );
        }
        if ($rub_especial_ferias_abono > 0 ) {
          $matriz2[2] = $cfpess[0]["r11_ferabo"];
          $matriz2[5] = $rub_especial_ferias_abono;
          
          db_insert("pontocom",$matriz1, $matriz2 );
          
        }
        
      }
    }
  }
  
}

/// fim da funcao verifica_ferias ///
/// qvale ///
function qvale ($registro,$tipo,$codigo,$difere,$quant,$admissao ){

  global $cfpess,$transacao1,$subpes;

  global $DB_instit;

  $quantvale = 0;
  if( ('t' == $cfpess[0]["r11_vtprop"]) && !('t' ==  $tipo )){

     $sqlaux  = "select quantvale_afas(";
     $sqlaux .= "'" .$codigo                        ."'"        .",";
     $sqlaux .= db_str($registro,6)     .",";
     $sqlaux .= substr("#".$subpes,1,4)  .",";
     $sqlaux .= substr("#".$subpes,6,2)  .",";
     $sqlaux .= db_str($quant,3,0)      .",";
     $sqlaux .= "'" . $difere                       ."'"        .",";
     $sqlaux .= "'" . $cfpess[0]["r11_vtfer"]  ."'"        .",";
     $sqlaux .= "ndias(".substr("#".$subpes,1,4)."," ;
     $sqlaux .=       substr("#".$subpes,6,2)."),$DB_instit) as total" ;


     if( db_selectmax( "transacao1",$sqlaux )){
   $quantvale = $transacao1[0]["total"];
     }

  }else{

     $sqlaux  = "select quantvale(";
     $sqlaux .= "'" .$codigo                        ."'"        .",";
     $sqlaux .= db_str($registro,6)     .",";
     $sqlaux .= substr("#".$subpes,1,4)  .",";
     $sqlaux .= substr("#".$subpes,6,2)  .",";
     $sqlaux .= db_str($quant,3,0)      .",";
     $sqlaux .= "'" . $difere                       ."'" ;
     $sqlaux .= ",$DB_instit) as total" ;
     if( db_selectmax( "transacao1",$sqlaux )){
  $quantvale = $transacao1[0]["total"];
     }
     if( ('t' == $cfpess[0]["r11_vtmpro"]) && ('t' ==  $tipo )){
  $diasadmissao = 0 ;
  if( substr("#".db_dtos( $admissao ),1,6) == db_strtran($subpes,"/","")){
     $diasadmissao = db_day( $admissao ) - 1 ;
  }

  $sqltotaldiasafastado  = "coalesce( conta_dias_afasta(";
  $sqltotaldiasafastado .=  db_str($registro,6)       .",";
  $sqltotaldiasafastado .=  substr("#".$subpes,1,4)    .",";
  $sqltotaldiasafastado .=  substr("#".$subpes,6,2)    .",";
  $sqltotaldiasafastado .= "ndias(".substr("#".$subpes,1,4)."," ;
  $sqltotaldiasafastado .=       substr("#".$subpes,6,2)."), $DB_instit), 0)" ;


  $sqldias_gozo_ferias  = "coalesce( dias_gozo_ferias(";
  $sqldias_gozo_ferias .= db_str($registro,6)      .",";
  $sqldias_gozo_ferias .= substr("#".$subpes,1,4)   .",";
  $sqldias_gozo_ferias .= substr("#".$subpes,6,2)   .",";
  $sqldias_gozo_ferias .= "ndias(".substr("#".$subpes,1,4)."," ;
  $sqldias_gozo_ferias .=       substr("#".$subpes,6,2)."), $DB_instit), 0)" ;


  $sqlaux  = "select proporcao_vale_mensal(";
  $sqlaux .= db_str($quantvale,10,0) .",";
  $sqlaux .= "( case when '".$cfpess[0]["r11_vtfer"]."' = '1'" ;
  $sqlaux .= "       then ".$sqltotaldiasafastado."+".$sqldias_gozo_ferias."+".db_str($diasadmissao,2,0) ;
  $sqlaux .= "       else ".$sqltotaldiasafastado."+".db_str($diasadmissao,2,0) ;
  $sqlaux .= "       end ) ) as total" ;


  if( db_selectmax( "transacao1",$sqlaux )){
     $quantvale = $transacao1[0]["total"];
  }

     }

  }

  return $quantvale;

}



/// fim da funcao qvale ///
/// arredonda ///
function arredonda_100 ($valor,$criterio){
  global $db_debug;
  if ($db_debug == true) { echo "[arredonda_100] função arredonda_100<br>"; }
   $arredn = 0;
   if( !db_empty($criterio)){
      $elevado = 10^$criterio;
      $max_arredn = $elevado*0.01;
      if ($db_debug == true) { echo '[arredonda_100] round('.$valor.'-(quebra_100('.$valor.'/'.$elevado.')*'.$elevado.'),2)<br>'; }
      $parte = round($valor-(quebra_100($valor/$elevado)*$elevado),2);
      if( !db_empty($parte)){
         $arredn = $max_arredn - $parte;
      }
   }
 return $arredn;
}

function arredonda($valor, $criterio){
  for($i=1;$i <= ($criterio - 1);$i++){
     $valor /= 10;
  }
  $valor = db_val(db_str($valor,18,2));
  for($i=1;$i<= ($criterio - 1);$i++){
     $valor *= 10;
  }
  return $valor;
}

function quebra_100($valor){
   $valor = db_str($valor,16,3);
   $valor = substr("#".$valor,-15);
   $valor = db_val($valor);
return $valor;
}
   

/// fim da funcao arredonda ///
/// calc_pensao ///
function calc_pensao($icalc, $opcao_geral, $opcao_tipo, $chamada_geral_arquivo=null) {

  global $F001, 
  $F002, $F004, $F005, $F006,
  $F007, $F008, $F009, $F010, $F011,
  $F012, $F013, $F014, $F015, $F016,
  $F017, $F018, $F019, $F020, $F021,
  $F022, $F023, $F006_clt, $F024, $F003, $F025, $F026, $F027, $F028;
  
  global $quais_diversos, $db_debug;
  eval($quais_diversos);
  global $anousu, $mesusu, $DB_instit;
  global $siglap, $d08_carnes, $cfpess, $subpes,$r110_regisi,$pensao;
  global $$chamada_geral_arquivo,$minha_calcula_pensao,$campos_pessoal;
  
  global $opcao_filtro,$opcao_gml,$r110_regisf,$r110_lotaci, $r110_lotacf,$faixa_regis,$faixa_lotac;
  
  if ($db_debug == true) {
    echo "[calc_pensao] INICIANDO CALCULO DA PENSÃO! <br>";
    echo "[calc_pensao] opcao_geral ..: {$opcao_geral} <br>";
    echo "[calc_pensao] opcao_tipo ...: {$opcao_tipo} <br>";
  }
  if ($opcao_geral == 1) {
    $sigla = "r10_";
    $sigla1 = "r14_";
    $qual_ponto = "pontofs";
  } else if ($opcao_geral == 8) {
    $sigla = "r47_";
    $sigla1 = "r48_";
    $qual_ponto = "pontocom";
  } else if ($opcao_geral == 3) {
    $sigla = "r29_";
    $sigla1 = "r31_";
    $qual_ponto = "pontofe";
  } else if ($opcao_geral == 4) {
    $sigla = "r19_";
    $sigla1 = "r20_";
    $qual_ponto = "pontofr";
  } else if ($opcao_geral == 5) {
    $sigla = "r34_";
    $sigla1 = "r35_";
    $qual_ponto = "pontof13";
  }
  $siglag = $sigla1;
  
  if ($opcao_tipo == 2) {
    
    if ($opcao_geral== 1 || $opcao_geral== 8) {
      $stringferias  = "('".$cfpess[0]["r11_ferias"]."','".$cfpess[0]["r11_fer13"]."','";
      $stringferias .= $cfpess[0]["r11_fer13a"]."','".$cfpess[0]["r11_ferabo"]."','";
      $stringferias .= $cfpess[0]["r11_feradi"]."','".$cfpess[0]["r11_ferant"]."','";
      $stringferias .= $cfpess[0]["r11_feabot"]."','".$cfpess[0]["r11_fadiab"]."')";
      if ($opcao_geral == 1 ) {
        $condicaoaux = " and ( r10_rubric in  " . $stringferias;
        if ($d08_carnes == "saocarlos") {
          $condicaoaux .= " or r10_rubric in ('0270') ";
        }
        //echo "<BR> 2 - stringferias --> $stringferias";
        $condicaoaux .= "  or r10_rubric between '2000' and '3999' )";
        $retornar = db_delete("pontofs", bb_condicaosubpes("r10_").$condicaoaux );
      } else if ($opcao_geral == 8) {
        $condicaoaux = " and ( r47_rubric in ".$stringferias;
        if ($d08_carnes == "saocarlos") {
          $condicaoaux .= " or r47_rubric in ('0270') ";
        }
        $condicaoaux .= "  or r47_rubric between '2000' and '3999' )";
        $retornar = db_delete("pontocom", bb_condicaosubpes("r47_").$condicaoaux );
      }
    }
    if ($opcao_geral == 1) {
      
      $condicaoaux  = " and  rh05_recis is null ";
      $condicaoaux .= " order by r52_regist ";
      db_selectmax("pensao", "select pensao.*, 
                                     rh01_regist as r01_regist,
                                     trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac 
                              from pensao
                                   inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu 
                                                          and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu 
                                                          and pensao.r52_regist         = rhpessoalmov.rh02_regist
                                                          and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
                                   inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                                   inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                          and rhlota.r70_instit         = rhpessoalmov.rh02_instit  
                                   inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
                                   left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                                   ".bb_condicaosubpes("r52_" ).$condicaoaux );
    } else if ($opcao_geral == 2 ) {

      $condicaoaux  = " and  rh05_recis is null ";
      $condicaoaux .= " order by r52_regist ";
      db_selectmax("pensao", "select pensao.*, 
                                     rh01_regist as r01_regist,
                                     trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac 
                              from pensao
                                   inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu 
                                                          and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu 
                                                          and pensao.r52_regist         = rhpessoalmov.rh02_regist
                                                          and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
                                   inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                                   inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                          and rhlota.r70_instit         = rhpessoalmov.rh02_instit  
                                   inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
                                   left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                                   left join rhregime      on rhregime.rh30_codreg      = rhpessoalmov.rh02_codreg
                                                          and rhregime.rh30_instit      = rhpessoalmov.rh02_instit 
                                   left join rhpesrubcalc on rhpesrubcalc.rh65_seqpes   = rhpessoalmov.rh02_seqpes
                                   left join rhinssoutros on rh51_seqpes                = rh02_seqpes
                                   left join rhpesprop    on rh19_regist                = rh02_regist
                                   ".bb_condicaosubpes("r52_" ).$condicaoaux );
    } else if ($opcao_geral == 3 ) {

      $condicaoaux  = " and  rh05_recis is null ";
      $condicaoaux .= " order by r52_regist ";
      db_selectmax("pensao", "select distinct(r52_regist+r52_numcgm), 
                                     pensao.*, 
                                     rh01_regist as r01_regist,
                                     trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac 
                              from pensao
                                  inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu 
                                                         and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu 
                                                         and pensao.r52_regist         = rhpessoalmov.rh02_regist
                                                         and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
                                  left  join pontofe      on pontofe.r29_anousu        = rhpessoalmov.rh02_anousu 
                                                         and pontofe.r29_mesusu        = rhpessoalmov.rh02_mesusu 
                                                         and pontofe.r29_regist        = rhpessoalmov.rh02_regist
                                  inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                                  inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                         and rhlota.r70_instit         = rhpessoalmov.rh02_instit  
                                  inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
                                  left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                                  ".bb_condicaosubpes("r52_" ).$condicaoaux );
    } else if ($opcao_geral == 4 ) {

      $condicaoaux  = " and  rh05_recis is not null ";
      $condicaoaux .= " and ( (  extract(year  from rh05_recis)=".db_sqlformat(substr("#".$subpes,1,4) );
      $condicaoaux .= "      and extract(month from rh05_recis)>=".db_sqlformat(substr("#".$subpes,6,2) );
      $condicaoaux .= " ) or extract(year from rh05_recis)>".db_sqlformat(substr("#".$subpes,1,4) ).")";
      $condicaoaux .= " order by r52_regist ";
      db_selectmax("pensao", "select pensao.*, 
                                     rh01_regist as r01_regist,
                                     trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac 
                              from pensao
                                   inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu 
                                                          and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu 
                                                          and pensao.r52_regist         = rhpessoalmov.rh02_regist
                                                          and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
                                   inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                                   inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                          and rhlota.r70_instit         = rhpessoalmov.rh02_instit  
                                   inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
                                   left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                                   ".bb_condicaosubpes("r52_" ).$condicaoaux );
    } else if ($opcao_geral == 5 ) {

      $condicaoaux  = " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
      $condicaoaux .= " order by r52_regist ";
      db_selectmax("pensao", "select distinct(r52_regist+r52_numcgm), 
                                     pensao.*, 
                                     rh01_regist as r01_regist,
                                     trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,
                                     r34_regist 
                              from pensao
                                   inner join rhpessoalmov on pensao.r52_anousu         = rhpessoalmov.rh02_anousu 
                                                          and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu 
                                                          and pensao.r52_regist         = rhpessoalmov.rh02_regist
                                                          and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
                                   left  join pontof13     on pontof13.r34_anousu       = rhpessoalmov.rh02_anousu 
                                                          and pontof13.r34_mesusu       = rhpessoalmov.rh02_mesusu 
                                                          and pontof13.r34_regist       = rhpessoalmov.rh02_regist
                                   inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                                   inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                          and rhlota.r70_instit         = rhpessoalmov.rh02_instit  
                                   inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
                                   left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                                   ".bb_condicaosubpes("r52_" ).$condicaoaux );
    } else if ($opcao_geral == 8 ) {
     
      $condicaoaux  = " and ( r47_regist is not null or r29_regist is not null ) ";
      $condicaoaux .= " and ( rh05_recis is null or rh05_recis >= ".db_sqlformat(db_ctod("01/".substr("#".$subpes,6,2)."/".substr("#".$subpes,1,4))).")";
      $condicaoaux .= " order by r52_regist ";
      db_selectmax("pensao", "select distinct(r52_regist+r52_numcgm), 
                                     pensao.*, 
                                     rh01_regist as r01_regist,
                                     r47_regist,
                                     trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,
                                     r29_regist 
                              from pensao
                                   inner join rhpessoalmov on rhpessoalmov.rh02_anousu  = pensao.r52_anousu  
                                                          and rhpessoalmov.rh02_mesusu  = pensao.r52_mesusu  
                                                          and rhpessoalmov.rh02_regist  = pensao.r52_regist 
                                                          and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
                                   left  join pontocom     on pontocom.r47_anousu       = rhpessoalmov.rh02_anousu 
                                                          and pontocom.r47_mesusu       = rhpessoalmov.rh02_mesusu 
                                                          and pontocom.r47_regist       = rhpessoalmov.rh02_regist 
                                                          and pontocom.r47_instit       = rhpessoalmov.rh02_instit
                                   left  join pontofe      on pontofe.r29_anousu        = rhpessoalmov.rh02_anousu 
                                                          and pontofe.r29_mesusu        = rhpessoalmov.rh02_mesusu 
                                                          and pontofe.r29_regist        = rhpessoalmov.rh02_regist 
                                                          and pontofe.r29_instit        = rhpessoalmov.rh02_instit
                                   inner join rhpessoal    on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                                   inner join rhlota       on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                          and rhlota.r70_instit         = rhpessoalmov.rh02_instit  
                                   inner join cgm          on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
                                   left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                              ".bb_condicaosubpes("r52_" ).$condicaoaux );
    }
    
  } else {

    $condicaoaux = "";
    if ($opcao_geral <= 3 ) {
      $condicaoaux .= " and  rh05_recis is null ";
    }
    $condicaoaux .= db_condicaoaux($opcao_filtro,$opcao_gml,"rh02_",$r110_regisi,$r110_regisf,$r110_lotaci,
    $r110_lotacf,$faixa_regis,$faixa_lotac,"rh01_");
    $condicaoaux .= " order by r52_regist ";
    db_selectmax("pensao", "select pensao.*, 
                                   rh01_regist as r01_regist,
                                   trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac 
                          from pensao
                                   inner join rhpessoalmov  on pensao.r52_anousu         = rhpessoalmov.rh02_anousu 
                                                           and pensao.r52_mesusu         = rhpessoalmov.rh02_mesusu 
                                                           and pensao.r52_regist         = rhpessoalmov.rh02_regist
                                                           and rhpessoalmov.rh02_instit  = ".db_getsession('DB_instit')."
                                   inner join rhpessoal     on rhpessoal.rh01_regist     = rhpessoalmov.rh02_regist
                                   inner join rhlota        on rhlota.r70_codigo         = rhpessoalmov.rh02_lota
                                                           and rhlota.r70_instit         = rhpessoalmov.rh02_instit  
                                   inner join cgm           on cgm.z01_numcgm            = rhpessoal.rh01_numcgm
                                    left join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
                                   ".bb_condicaosubpes("r52_" ).$condicaoaux );
  }

  $contador        = 1;
  $primeira_pensao = true;
  
  if (count($pensao)>0) {
    $minha_calcula_pensao=true;
  }
  
  if ($db_debug == true) {
    echo "[calc_pensao] Pensões encontradas: <br>";
    echo "<pre>";
    print_r($pensao);
    echo "</pre>";
  }

  for ($Ipensao=0; $Ipensao<count($pensao); $Ipensao++) {
    
    
    $oServidor = ServidorRepository::getInstanciaByCodigo(
                                                          $pensao[$Ipensao]["r52_regist"], 
                                                          $pensao[$Ipensao]["r52_anousu"],
                                                          $pensao[$Ipensao]["r52_mesusu"]);
    
    $oVariaveisCalculo = DBPessoal::getVariaveisCalculo($oServidor);

    $F001     = $oVariaveisCalculo->f001;
    $F002     = $oVariaveisCalculo->f002;
    $F003     = $oVariaveisCalculo->f003;
    $F004     = $oVariaveisCalculo->f004;
    $F005     = $oVariaveisCalculo->f005;
    $F006     = $oVariaveisCalculo->f006;
    $F006_clt = $oVariaveisCalculo->f006_clt;
    $F007     = $oVariaveisCalculo->f007;
    $F008     = $oVariaveisCalculo->f008;
    $F009     = $oVariaveisCalculo->f009;
    $F010     = $oVariaveisCalculo->f010;
    $F011     = $oVariaveisCalculo->f011;
    $F012     = $oVariaveisCalculo->f012;
    $F013     = $oVariaveisCalculo->f013;
    $F014     = $oVariaveisCalculo->f014;
    $F015     = $oVariaveisCalculo->f015;
    $F022     = $oVariaveisCalculo->f022;
    $F024     = $oVariaveisCalculo->f024;
    $F025     = $oVariaveisCalculo->f025;
    
    $pvalor_obriga       = 0;
    $pvalor_liquido      = 0;
    $pvalor_bruto        = 0;
    $pvalor_salfamilia   = 0;
    $pvalor_ad_13salario = 0;
    
    if ($db_debug == true) {
      echo "[calc_pensao] calculando pensao $Ipensao... <br>";
      echo "[calc_pensao] chamada_geral_arquivo: {$chamada_geral_arquivo} <br>";  
      echo "[calc_pensao] r11_mes13:".$cfpess[0]["r11_mes13"]." - mes:".$mesusu."<br>";
    }

    /**
     * Somente 13º - Início
     */
    if ($chamada_geral_arquivo == "gerfs13" && $cfpess[0]["r11_mes13"] == $mesusu) {

      if ($db_debug == true) {
        echo "[calc_pensao] Buscando valores das pensões anteriores a ".$pensao[$Ipensao]["r52_mesusu"]." para o cgm ".$pensao[$Ipensao]["r52_numcgm"]." no ano ".$pensao[$Ipensao]["r52_anousu"]."<br>";
      }
      $sSqlValorPensao = "select sum(coalesce(pensao.r52_val13,0)) as r52_val13 
                            from pensao 
                           where r52_regist = ".$pensao[$Ipensao]["r52_regist"]." 
                             and r52_numcgm = ".$pensao[$Ipensao]["r52_numcgm"]." 
                             and r52_anousu = ".$pensao[$Ipensao]["r52_anousu"]." 
                             and r52_mesusu < ".$pensao[$Ipensao]["r52_mesusu"]; 
      $rsValorPensao       = db_query($sSqlValorPensao);

      if (pg_num_rows($rsValorPensao) > 0) {
        $pvalor_ad_13salario = db_utils::fieldsMemory($rsValorPensao,0)->r52_val13;
      } 
        
      if ($db_debug == true) {
        echo "[calc_pensao] Adiantamentos de 13º (pvalor_ad_13salario): {$pvalor_ad_13salario} <br>";
      }
    }

    /**
     * Somente 13º - FIM
     */
    
    if ($db_debug) {
      echo "[calc_pensao] Tabela: $chamada_geral_arquivo...<br>";
    }    

    /**
     * TABELA GERF13o - Cálculo de 13º Salário
     */
    if ($chamada_geral_arquivo == "gerfs13") {
      
      /**
       * Valida se paga 13º conforme cadastro de pensão
       */
      if ('f' == $pensao[$Ipensao]["r52_pag13"]) {

        if ($db_debug) {
          echo "[calc_pensao] r52_pag13:".$pensao[$Ipensao]["r52_pag13"]." - continuando calculo pulando o registro... <br>";
        }       
        continue;
      }
      
      $lCalculaPensaoAdiantamento13 = false;

      if ($cfpess[0]["r11_mes13"] != $mesusu) {
        
        if ($db_debug) {
          echo "[calc_pensao] r11_mes13: ".$cfpess[0]["r11_mes13"]." != mes: $mesusu <br>";
        }
        if ($pensao[$Ipensao]["r52_adiantamento13"] == 't') {
          $lCalculaPensaoAdiantamento13 = true;
        } else {

          if ($db_debug) {
            echo "[calc_pensao] r52_adiantamento13: ".$pensao[$Ipensao]["r52_adiantamento13"]." - continuando calculo pulando o registro... <br>";
          }         
          continue;         
        }
      }
      
    /**
     * TABELA GERFCOM - Cálculo de Folha Complementar
     */
    } else if ($chamada_geral_arquivo == "gerfcom") {
      if ('f' == $pensao[$Ipensao]["r52_pagcom"]) {
        if ($db_debug) {
          echo "[calc_pensao] r52_pagcom: ".$pensao[$Ipensao]["r52_pagcom"]." - continuando calculo pulando o registro... <br>";
        }       
        continue;
      }
   /**
    * TABELA GERFFER - Cálculo de Folha Férias
    */
    } else if ($chamada_geral_arquivo == "gerffer") {
      if ('f' == $pensao[$Ipensao]["r52_pagfer"]) {
        if ($db_debug) {
          echo "[calc_pensao] r52_pagfer: ".$pensao[$Ipensao]["r52_pagfer"]." - continuando calculo pulando o registro... <br>";
        }       
        continue;
      }

   /**
    * TABELA GERFRES - Cálculo de Folha Rescisão
    */
    } else if ($chamada_geral_arquivo == "gerfres") {

      if ('f' == $pensao[$Ipensao]["r52_pagres"]) {
        if ($db_debug) {
          echo "[calc_pensao] r52_pagres: ".$pensao[$Ipensao]["r52_pagres"]." - continuando calculo pulando o registro... <br>";
        }       
        continue;
      }
    }

    /**
     * Termina aqui validaçao das NEGAÇÕES de pagamento de pensão
     */
    $registrop    = $pensao[$Ipensao]["r52_regist"];
    $numcgmp      = $pensao[$Ipensao]["r52_numcgm"];
    $condicaoaux  = " and r52_regist = ".db_sqlformat($registrop);
    $condicaoaux .= " and r52_numcgm = ".db_sqlformat($numcgmp);
    $matriz1      = array();
    $matriz2      = array();
    $retornar     = true;
    
    if ($chamada_geral_arquivo == "gerfs13") {
      
      $matriz1[1] = "r52_val13";
      $matriz2[1] = 0;
      if ($db_debug) {
          echo "[calc_pensao] Alterando o valor do campo r52_val13 da tabela pensao para 0 quando ".bb_condicaosubpes("r52_").$condicaoaux."<br>";
      } 
      db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
      
    } else if ($chamada_geral_arquivo == "gerfcom") {
      
      $matriz1[1] = "r52_valcom";
      $matriz2[1] = 0;
      if ($db_debug) {
        echo "[calc_pensao] Alterando o valor do campo r52_valcom da tabela pensao para 0 quando ".bb_condicaosubpes("r52_").$condicaoaux."<br>";
      }        
      db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
        
    } else if ($chamada_geral_arquivo == "gerfres") {

      $matriz1[1] = "r52_valres";
      $matriz2[1] = 0;
      if ($db_debug) {
        echo "[calc_pensao] Alterando o valor do campo r52_valres da tabela pensao para 0 quando ".bb_condicaosubpes("r52_").$condicaoaux."<br>";
      }        
      db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );

    } else {

      $matriz1[1] = "r52_valor";
      $matriz2[1] = 0;
      if ($db_debug) {
        echo "[calc_pensao] Alterando o valor do campo r52_valor da tabela pensao para 0 quando ".bb_condicaosubpes("r52_").$condicaoaux."<br>";
      }        
      db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
    }
    
    $condicaoaux  =  " and r30_regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] ) ;
    $condicaoaux .= " order by r30_perai desc";
    global $cadferia;
    db_selectmax("cadferia", "select r30_regist,r30_proc2,r30_per2i, r30_per2f,r30_proc1,r30_per1i,r30_per1f,r30_paga13,r30_descad from cadferia ".bb_condicaosubpes("r30_" ).$condicaoaux );
    if (db_empty($cadferia[0]["r30_proc2"]) ) {
      $r30_proc = "r30_proc1";
      $r30_peri = "r30_per1i";
      $r30_perf = "r30_per1f";
    } else {
      $r30_proc = "r30_proc2";
      $r30_peri = "r30_per2i";
      $r30_peri = "r30_per2f";
    }
    $condicaoaux = " and ".$siglag."regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] );
    $tem_calculo = db_selectmax($chamada_geral_arquivo, "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux );
    if ($tem_calculo) {
      
      if ($db_debug) {
        echo "[calc_pensao] encontrou calculo executando a query: select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux."<br>";
      } 
      $pvalor_bruto      = 0;
      $pvalor_liquido    = 0;
      $pvalor_obriga     = 0;
      $pvalor_salfamilia = 0;
      $qual_reg          = $sigla1."regist";
      $qual_rub          = $sigla1."rubric";
      $qual_tpp          = " ";
      if ($opcao_geral == 3) {
        $qual_tpp = $sigla1."tpp";
      }
      
      $chamada_geral_ = $$chamada_geral_arquivo;
      for ($Igeral=0; $Igeral<count($chamada_geral_); $Igeral++) {
        
        if( $chamada_geral_[$Igeral][$qual_rub] == "R993" ){
           continue;
        }
           
        if ($opcao_geral == 3
        && (( db_month($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],6,2)) &&
        db_year($cadferia[0][$r30_peri]) == db_val(substr("#".$cadferia[0][$r30_proc],1,4)))
        || ( db_month($cadferia[0][$r30_peri]) < db_val(substr("#".$cadferia[0][$r30_proc],6,2)) &&
        db_year($cadferia[0][$r30_peri]) > db_val(substr("#".$cadferia[0][$r30_proc],1,4)))
        )
        ) {
          if (strtolower($cfpess[0]["r11_fersal"]) == "f" && ('t' == $cadferia[0]["r30_paga13"]) ) {
            // Quando do Adiantamento de Férias , não Calcula a Pensão de Férias se for Pagar como Férias e somente 1/3 for sim 
            continue;
          }
          if ('f' == $cadferia[0]["r30_paga13"] && $cadferia[0][$r30_proc] < $subpes && strtolower($chamada_geral_[$Igeral][$qual_tpp]) == "d" ) {
            // Não Processar no Calculo da Pensão de Férias as Rubricas de Férias Adiantadas quando somente 1/3 for não  e  Data de Pagto não Venceu  
            continue;
          }
        }
        // a rubrica de pensao passa a ser calculada no gerffer
        // e depois repassada para o salario ou complentar
        if (( ( strtolower($cfpess[0]["r11_fersal"]) == "f" && ('t' ==  $cadferia[0]["r30_paga13"]) &&
        db_month($cadferia[0][$r30_peri]) == db_val(substr("#".$cadferia[0][$r30_proc],6,2))
        )
        || 'f' == $cadferia[0]["r30_paga13"]
        )
        &&
        ( $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_ferias"]  || // Rubrica onde é pago as férias
        $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_fer13"] || // Rubrica onde é pago um 1/3 de férias
        $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_fer13a"] || // Rubrica onde é pago um 1/3 s/ abono de férias
        $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_ferabo"] || // Rubrica onde é pago o abono de férias
        $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_feradi"] || // Rubrica onde é pago o adiantamento de férias
        $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_fadiab"]    // Rubrica onde é descontado as férias pagas no mês anterior
        )
        ) {
          // As Rubricas Especiais não entran no calculo da Pensão, quando do Calculo da Pensão no Salário ou na Complementar
          continue;
        }
        if ($opcao_geral != 3 && $opcao_geral != 4) {
          // No Calculo da Pensão de Salario ou Complementar as rubricas de Férias existente no Ponto não entram no Calculo da Pensão
          // Somente no Calculo da Pensão de Férias deve ler as rubricas 2000 e
          // os descontos referentess a ferias ( previdencia e ir )
          
          if (( substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) != "R" && ( db_val($chamada_geral_[$Igeral][$qual_rub] ) >= 2000
          && db_val($chamada_geral_[$Igeral][$qual_rub] ) < 4000 ))
          || $chamada_geral_[$Igeral][$qual_rub] == "R915" ) {
            continue;
          }
        }
        if ($opcao_geral == 3 ) {
          // Para pagamento somente 1/3 sim e restante em salario (Pagar como Salário)
          // para a geracao da Pensão de Férias so deve levar em conta para o Calculo da Pensão 1/3 Férias
          
          if (strtolower($cfpess[0]["r11_fersal"]) == "s" && ('t' == $cadferia[0]["r30_paga13"])) {
            if (( substr("#". $chamada_geral_[$Igeral][$qual_rub],1,1) != "R" && strtolower($chamada_geral_[$Igeral][$qual_tpp]) != "a" ) ) {
              continue;
            }
          }
        }
        if (substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) != "R"
        || (( substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) == "R"
        && ( (   db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) < 950
              && $chamada_geral_[$Igeral][$qual_rub] != "R928" 
              && db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) > 900
             )
        || db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) == 980 ))) ) {
//        $qual_val = str_replace(".","",db_formatar($chamada_geral_[$Igeral][$sigla1."valor"],'f'));
          $qual_val = $chamada_geral_[$Igeral][$sigla1."valor"];
          $qual_pd  = $chamada_geral_[$Igeral][$sigla1."pd"];
          if ($chamada_geral_arquivo == "gerfres") {
            if (db_at($chamada_geral_[$Igeral][$qual_rub], "R902-R905-R908-R911-R914-")>0) {
              if (('t' == $pensao[$Ipensao]["r52_pag13"])) {
                $pvalor_obriga += $qual_val;
                //echo "<BR> 1 pvalor_obriga ---> $pvalor_obriga rubrica --> ".$chamada_geral_[$Igeral][$qual_rub]." valor --> $qual_val" ;
              }
            } else if (substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) == "R"
            && db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) < 916 ) {
              $pvalor_obriga += $qual_val;
                //echo "<BR> 2 pvalor_obriga ---> $pvalor_obriga rubrica --> ".$chamada_geral_[$Igeral][$qual_rub]." valor --> $qual_val" ;
            }
          } else {
            // Obs : Não entra no Calculo das Obrigacoes os Descontos de Previdencia de Ferias 
            // quando tiver Calculando Pensão no Salario ou Complemetar
            if (( substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) == "R"
                  && db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) < 916
                  && ((db_at($chamada_geral_[$Igeral][$qual_rub], "R903-R906-R909-R912-") > 0 && $opcao_geral == 3)
                      ||
                      (db_at($chamada_geral_[$Igeral][$qual_rub], "R903-R906-R909-R912-") == 0 && $opcao_geral != 3)
                     ))
            ||  ( $chamada_geral_[$Igeral][$qual_rub] == "R915" && $opcao_geral == 3 ) ) {
              $pvalor_obriga += $qual_val;
              //echo "<BR> 3 pvalor_obriga ---> $pvalor_obriga rubrica --> ".$chamada_geral_[$Igeral][$qual_rub]." valor --> $qual_val" ;
            }
            if ($opcao_geral == 3 &&  db_at($chamada_geral_[$Igeral][$qual_rub] , "R903-R906-R909-R912") > 0 ) {
              // para ferias nao deve considerar os descontos
              // de previdencia normais
              
              continue;
            }
          }
          if ($chamada_geral_[$Igeral][$qual_rub] == "R980") {
            //$pvalor_ad_13salario += $qual_val;
            continue;
          }
          if (substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) == "R"
          && db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) >= 918
          && db_val(substr("#".$chamada_geral_[$Igeral][$qual_rub],2,3)) <= 921 ) {
            $pvalor_salfamilia += $qual_val;
          }
          if ($chamada_geral_arquivo == "gerfres") {
            if (substr("#".$chamada_geral_[$Igeral][$qual_rub],1,1) !="R"
            && $chamada_geral_[$Igeral][$qual_rub] > "4000"
            && $chamada_geral_[$Igeral][$qual_rub] < "6000"
            && 'f' == $pensao[$Ipensao]["r52_pag13"] ) {
              continue;
            }
          }
          if ($qual_pd == 1) {
            $pvalor_bruto += $qual_val;
            //echo "<BR> 2 pvalor_bruto ---> $pvalor_bruto";
          } else {
            if ((($opcao_geral==1 && ('t' == $cadferia[0]["r30_paga13"]) ) || $opcao_geral == 3 )
            && ($chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_ferant"] // Rubrica onde é descontado as férias pagas no mês anterior
            || $chamada_geral_[$Igeral][$qual_rub] == $cfpess[0]["r11_feabot"] ) ) {
              // Rubrica em que será lançado o abono do mês anterior
              $pvalor_bruto -= $qual_val;
              //echo "<BR> 2 palor_bruto ---> $pvalor_bruto";
            } else {
              //echo "<BR> 2 pvalor_liquido ---> $qual_val qual_rub --> ".$chamada_geral_[$Igeral][$qual_rub];
              $pvalor_liquido += $qual_val;
              //echo "<BR> 2 pvalor_liquido ---> $pvalor_liquido";
            }
          }
        }
      }
      
      if ($opcao_geral == 3 && $cadferia[0][$r30_proc] < $subpes) {
        ferias($pensao[$Ipensao]["r52_regist"]," " );
        
        // F019 - Numero de dias a pagar no mes
        // F020 - Numero de dias abono p/ pagar no mes
        
        // verificar a necessidade de proporcionalizar: por
        // exemplo  se for so 1/3 todo adiantado no mes
        // anterior , porem tem 25 dias de gozo neste mes e
        // outros 5 no proximo mes
        
        $pvalor_bruto -= ( ( $cadferia[0]["r30_descad"] / ($cadferia[0]["r30_ndias"]-$cadferia[0]["r30_abono"]+$F020) ) * ($F019+$F020) );
        //echo "<BR> 3 palor_bruto ---> $pvalor_bruto";
      }
      
      if ($pvalor_bruto < 0) {
        $pvalor_bruto = 0;
        //echo "<BR> 3 palor_bruto ---> $pvalor_bruto";
      }
      
      //echo "<BR> $pvalor_liquido = ( $pvalor_bruto-$pvalor_liquido > 0 ? $pvalor_bruto-$pvalor_liquido: 0 ) ;";
      $pvalor_liquido = ( $pvalor_bruto-$pvalor_liquido > 0 ? $pvalor_bruto-$pvalor_liquido: 0 ) ;
      //echo "<BR> 3 pvalor_liquido ---> $pvalor_liquido";
      
    }
    
    $formula_pensao = trim($pensao[$Ipensao]["r52_formul"]);
    if (!db_empty($formula_pensao)) {

      // if ($db_debug ) {
    

      // } 
      
      if ($tem_calculo) {
        
        $formpensao = $pensao[$Ipensao]["r52_formul"];
        
        global $rubricas_;
        db_selectmax("rubricas_", "select * from rhrubricas  where rh27_instit = $DB_instit " );
        for ($Irubricas=0; $Irubricas<count($rubricas_); $Irubricas++) {
          
          if (db_at($rubricas_[$Irubricas]["rh27_rubric"],$pensao[$Ipensao]["r52_formul"]) > 0) {
          //echo "<BR> ".$rubricas_[$Irubricas]["rh27_rubric"] ;
            $condicaoaux  = " and ".$siglag."regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] );
            $condicaoaux .= " and ".$siglag."rubric = ".db_sqlformat($rubricas_[$Irubricas]["rh27_rubric"] );
            if (db_selectmax($chamada_geral_arquivo, "select * from ".$chamada_geral_arquivo." ".bb_condicaosubpes($siglag ).$condicaoaux )) {
              $arq_ = $$chamada_geral_arquivo;
              $vararq = $arq_[0][$sigla1."valor"] ;
              $formpensao = db_strtran($formpensao,$rubricas_[$Irubricas]["rh27_rubric"],db_strtran(db_str($vararq,15,2),",","."));
              //echo "<BR> formula da pensao 3 ->  $formpensao";
            } else {
              $formpensao = db_strtran($formpensao,$rubricas_[$Irubricas]["rh27_rubric"],"0");
              //echo "<BR> formula da pensao 4 ->  $formpensao";
            }
          }
          
        }
        
        while (1==1) {
          
          $temtroca = false;
          if (db_at("7777",$formpensao) > 0) {
            $formpensao = db_strtran($formpensao,"7777",db_strtran(db_str($pvalor_liquido,15,2),",","."));
            //echo "<BR> formula da pensao 5 ->  $formpensao";
            $temtroca = true;
          }
          if (db_at("8888",$formpensao) > 0) {
            $formpensao = db_strtran($formpensao,"8888",db_strtran(db_str($pvalor_obriga,15,2),",","."));
            //echo "<BR> formula da pensao 6 ->  $formpensao";
            $temtroca = true;
          }
          if (db_at("9999",$formpensao) > 0) {
            $formpensao = db_strtran($formpensao,"9999",db_strtran(db_str($pvalor_bruto,15,2),",","."));
            //echo "<BR> formula da pensao 7 ->  $formpensao";
            $temtroca = true;
          }
          if ($temtroca == false) {
            break;
          }
          
        }
        
        $formpensao = str_replace('D','$D',$formpensao);
        $formpensao = str_replace('F','$F',$formpensao);
        
        //            ver a possibilidade de incluir na formula o calculo de bases
        
        //            $formpensao = le_var_bxxx($formpensao,$qual_ponto, $chamada_geral_arquivo, $sigla, $sigla1, 0,"");
        
        //echo "<BR> formula da pensao 8 ->  $formpensao rubrica --> ".$pensao[$Ipensao]["r52_regist"];
        global $valor_pensao;
        ob_start();

        eval('$valor_pensao = '.$formpensao.";");

        $sMsgErroCalcPensao  = "Formula cadastrada no cadastro da pensão!\\n";
        $sMsgErroCalcPensao .= " Ano/Mes: ".$pensao[$Ipensao]["r52_anousu"]."/".$pensao[$Ipensao]["r52_mesusu"];
        $sMsgErroCalcPensao .= " - Cgm: ".$pensao[$Ipensao]["r52_numcgm"];
        db_alerta_erro_eval($pensao[$Ipensao]["r52_regist"],$formpensao,"\\n $sMsgErroCalcPensao");
        //echo "<BR> formula com % pensao = $valor_pensao * (".$pensao[$Ipensao]["r52_perc"]."/100)";
        
        if ($db_debug) {
          echo "[calc_pensao] calculando valor da pensao... <br>";
          echo "[calc_pensao] valor_pensao = valor_pensao * (r52_perc / 100) => $valor_pensao * $valor_pensao (".$pensao[$Ipensao]["r52_perc"]."/100) = ".$valor_pensao * ($pensao[$Ipensao]["r52_perc"]/100)."<br>"; 
        }
        $valor_pensao = $valor_pensao * ($pensao[$Ipensao]["r52_perc"]/100);
        //echo "<BR> valor da Pensao Alimenticia --> $valor_pensao";
      } else {
        $valor_pensao = 0;
      }
      
    } else {
      
      if ($db_debug) {
        echo "[calc_pensao] não encontrou formula para a pensao... <br>";
      }
      
      if (($pvalor_bruto - $pvalor_salfamilia) > 0) {
        
        if ($db_debug) {
          echo "[calc_pensao] pvalor_bruto($pvalor_bruto) - pvalor_salfamilia($pvalor_salfamilia) > 0 <br>";
          echo "[calc_pensao] valor_pensao = ".$pensao[$Ipensao]["r52_vlrpen"]."<br>";
        }
        $valor_pensao = $pensao[$Ipensao]["r52_vlrpen"];
        
      } else {
        
        if ($db_debug) {
          echo "[calc_pensao] valor_pensao = 0<br>";
        }
        $valor_pensao = 0;
        
      }
      
      if ($opcao_geral == 3) {
        if ($cadferia[0][$r30_proc] < $subpes
        && db_month($cadferia[0][$r30_peri] ) == db_month($cadferia[0][$r30_perf] )
        && db_month($cadferia[0][$r30_peri] ) != db_val(substr("#". $cadferia[0][$r30_proc],6,2)) ) {
          if ($db_debug) {
            echo "[calc_pensao] zerando valor da pensao<br>";
            echo "[calc_pensao] valor_pensao antes {$valor_pensao}<br>";
            echo "[calc_pensao] valor_pensao depois 0<br>";
          } 
          $valor_pensao = 0;
        }
      }
    }
    //echo "<BR> pensao 8 ->  $chamada_geral_arquivo";
    
    if ($pvalor_ad_13salario > 0) {
      
      if ($db_debug) {
        echo "[calc_pensao] subtraindo pvalor_ad_13salario da variavel valor_pensao  <br>";
        echo "[calc_pensao] valor_pensao = {$valor_pensao} - {$pvalor_ad_13salario} = ".($valor_pensao-$pvalor_ad_13salario)."<br>";
      }       
      $valor_pensao -= $pvalor_ad_13salario;
    }
    
    if ($valor_pensao <= 0){
      if ($db_debug) {
      echo "[calc_pensao] valor da pensao menor que zero, zerando valor da pensao! <br>";
      }       
      $valor_pensao = 0;
    }
    
    if ($valor_pensao >= 0  ) {
      
      if ($db_debug) {
        echo "[calc_pensao] Valor da Pensao: {$valor_pensao} <br>";
      }

      if ($chamada_geral_arquivo == "gerfs13") {
        $rubrica_pensao = db_str(db_val($cfpess[0]["r11_palime"])+4000, 4,0); 
      } else if ($chamada_geral_arquivo == "gerffer") {
        $rubrica_pensao = db_str(db_val($cfpess[0]["r11_palime"])+2000, 4,0);
      } else {
        $rubrica_pensao = $cfpess[0]["r11_palime"];
      }
      $condicaoaux  = " and ".$siglap."regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] );
      $condicaoaux .= " and ".$siglap."rubric = ".db_sqlformat($rubrica_pensao );
      
      global $$qual_ponto;
      if (db_selectmax($qual_ponto, "select * from ".$qual_ponto." ".bb_condicaosubpes($siglap ).$condicaoaux )) {
        $acao = "altera";
      } else {
        $acao = "insere";
      }

      if ($chamada_geral_arquivo == "gerfs13") {
        
        if ($lCalculaPensaoAdiantamento13) {
          if (db_debug) {
            echo "[calc_pensao] calculando valor da pensao com adiantamento<br>";
            echo "[calc_pensao] valor da pensao = (valor da pensao * (r52_percadiantamento13)/100) = {$valor_pensao} * (".$pensao[$Ipensao]["r52_percadiantamento13"]."/100) = ".($valor_pensao * ($pensao[$Ipensao]["r52_percadiantamento13"]/100))."<br>";
          } 
          $valor_pensao = ($valor_pensao * ($pensao[$Ipensao]["r52_percadiantamento13"]/100));
        }
      }
      
      $ponto = $$qual_ponto;
      
      $qual_val = $sigla."valor";
      $qual_rep = $sigla;
      
      $valor_pensao = round($valor_pensao,2 );
      
      if ($valor_pensao > 0 ) {
         
        if ($db_debug) {
          echo "[calc_pensao] 13818 valor_pensao: $valor_pensao <br>";
          echo "[calc_pensao] opcao_geral: {$opcao_geral} <br>";
        }
        
         if ($opcao_geral == 1) {
           $matriz1 = array();
           $matriz2 = array();
           
           $matriz1[1] = "r10_regist";
           $matriz1[2] = "r10_rubric";
           $matriz1[3] = "r10_valor";
           $matriz1[4] = "r10_quant";
           $matriz1[5] = "r10_lotac";
           $matriz1[6] = "r10_anousu";
           $matriz1[7] = "r10_mesusu";
           $matriz1[8] = "r10_instit";
           
           $matriz2[1] = $pensao[$Ipensao]["r52_regist"];
           $matriz2[2] = $cfpess[0]["r11_palime"];
           
           if ($primeira_pensao) {
             $matriz2[3] = round($valor_pensao,2);
           } else {
             if ($acao == "altera") {
               $matriz2[3] = round($ponto[0]["r10_valor"] + $valor_pensao,2);
             } else {
               $matriz2[3] = round($valor_pensao,2);
             }
           }
           $matriz2[4] = 1;
           $matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
           $matriz2[6] = $anousu;
           $matriz2[7] = $mesusu;
           $matriz2[8] = $DB_instit;
           
         } else if ($opcao_geral == 3 ) {
           
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
           $matriz1[10]= "r29_mesusu";
           $matriz1[11]= "r29_instit";
           
           $matriz2[1] = $pensao[$Ipensao]["r52_regist"];
           $matriz2[2] = $rubrica_pensao;
           if ($primeira_pensao) {
             $matriz2[3] = round($valor_pensao,2);
           } else {
             if ($acao == "altera") {
               $matriz2[3] = round($ponto[0]["r29_valor"] + $valor_pensao,2);
             } else {
               $matriz2[3] = round($valor_pensao,2);
             }
           }
           $matriz2[4] = 1;
           $matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
           $matriz2[6] = 0;
           $matriz2[7] = 0;
           $matriz2[8] = " ";
           $matriz2[9] = $anousu;
           $matriz2[10] = $mesusu;
           $matriz2[11] = $DB_instit;
         } else if ($opcao_geral == 8) {
           
           $matriz1 = array();
           $matriz2 = array();
           
           $matriz1[1] = "r47_regist";
           $matriz1[2] = "r47_rubric";
           $matriz1[3] = "r47_valor";
           $matriz1[4] = "r47_quant";
           $matriz1[5] = "r47_lotac";
           $matriz1[6] = "r47_anousu";
           $matriz1[7] = "r47_mesusu";
           $matriz1[8]=  "r47_instit";
           
           $matriz2[1] = $pensao[$Ipensao]["r52_regist"];
           $matriz2[2] = $cfpess[0]["r11_palime"];
           if ($primeira_pensao) {
             $matriz2[3] = round($valor_pensao,2 );
           } else {
             if ($acao == "altera") {
               $matriz2[3] = round($ponto[0]["r47_valor"] + $valor_pensao, 2);
             } else {
               $matriz2[3] =  round($valor_pensao,2 );
             }
           }
           $matriz2[4] = 1;
           $matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
           $matriz2[6] = $anousu;
           $matriz2[7] = $mesusu;
           $matriz2[8] = $DB_instit;
           
         } else if ($opcao_geral == 4) {
           
           $matriz1 = array();
           $matriz2 = array();
           
           $matriz1[1] = "r19_regist";
           $matriz1[2] = "r19_rubric";
           $matriz1[3] = "r19_valor";
           $matriz1[4] = "r19_quant";
           $matriz1[5] = "r19_lotac";
           $matriz1[6] = "r19_tpp";
           $matriz1[7] = "r19_anousu";
           $matriz1[8] = "r19_mesusu";
           $matriz1[9]=  "r19_instit";
           
           $matriz2[1] = $pensao[$Ipensao]["r52_regist"];
           $matriz2[2] = $cfpess[0]["r11_palime"];
           if ($primeira_pensao) {
             $matriz2[3] = round($valor_pensao,2);
           } else {
             if ($acao == "altera") {
               $matriz2[3] = round($ponto[0]["r19_valor"] + $valor_pensao,2);
             } else {
               $matriz2[3] = round($valor_pensao,2);
             }
           }
           $matriz2[4] = 1;
           $matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
           $matriz2[6] = " ";
           $matriz2[7] = $anousu;
           $matriz2[8] = $mesusu;
           $matriz2[9] = $DB_instit;
           
         } else if ($opcao_geral == 5) {
           
           $matriz1 = array();
           $matriz2 = array();
           
           $matriz1[1] = "r34_regist";
           $matriz1[2] = "r34_rubric";
           $matriz1[3] = "r34_valor";
           $matriz1[4] = "r34_quant";
           $matriz1[5] = "r34_lotac";
           $matriz1[6] = "r34_media";
           $matriz1[7] = "r34_calc";
           $matriz1[8] = "r34_anousu";
           $matriz1[9] = "r34_mesusu";
           $matriz1[10]= "r34_instit";
           
           $matriz2[1] = $pensao[$Ipensao]["r52_regist"];
           $matriz2[2] = $rubrica_pensao;
           if ($primeira_pensao) {
             $matriz2[3] = $valor_pensao;
           } else {
             if ($acao == "altera") {
               $matriz2[3] = $ponto[0]["r34_valor"] + $valor_pensao;
             } else {
               $matriz2[3] = $valor_pensao;
             }
           }
           $matriz2[4] = 1;
           $matriz2[5] = $pensao[$Ipensao]["r01_lotac"];
           $matriz2[6] = 0;
           $matriz2[7] = 0;
           $matriz2[8] = $anousu;
           $matriz2[9] = $mesusu;
           $matriz2[10] = $DB_instit;
           
         }
      
         $condicaoaux  = " and ".$siglap."regist = ".db_sqlformat($pensao[$Ipensao]["r52_regist"] );
         $condicaoaux .= " and ".$siglap."rubric = ".db_sqlformat($rubrica_pensao );
         if ($acao == "altera") {
          
           if ($db_debug) {
             echo "[calc_pensao] Alterando {$qual_ponto}<br>";
             echo "Campos: <pre>";
             print_r($matriz1);
             echo "</pre>";
             echo "Valores: <pre>";
             print_r($matriz2);
             echo "</pre>";
             echo "[calc_pensao] <br>";
           }  
           db_update($qual_ponto, $matriz1, $matriz2, bb_condicaosubpes($siglap).$condicaoaux );
           
         } else {
          
           if ($db_debug) {
             echo "[calc_pensao] Inserindo em $qual_ponto<br>";
             echo "Campos: <pre>";
             print_r($matriz1);
             echo "</pre>";
             echo "Valores: <pre>";
             print_r($matriz2);
             echo "</pre>";
             echo "[calc_pensao] <br>";             
           }          
           db_insert($qual_ponto, $matriz1, $matriz2 );
           
         }
         
         $matriz1 = array();
         $matriz2 = array();
         $registrop = $pensao[$Ipensao]["r52_regist"];
         $numcgmp = $pensao[$Ipensao]["r52_numcgm"];
         $condicaoaux  = " and r52_regist = ".db_sqlformat($registrop);
         $condicaoaux .= " and r52_numcgm = ".db_sqlformat($numcgmp);
         
         if ($opcao_geral == 5) {
          
           $matriz1[1] = "r52_val13";
           $matriz2[1] = $valor_pensao;
           
           if ($db_debug) {
             echo "[calc_pensao] Alterando o valor do campo r52_val13 para {$valor_pensao} quando ".bb_condicaosubpes("r52_").$condicaoaux."<br>";
           }
           $retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
           
         } else if ($opcao_geral == 8) {
          
           $matriz1[1] = "r52_valcom";
           $matriz2[1] = $valor_pensao;
           
           if ($db_debug) {
            echo "[calc_pensao] Alterando o valor do campo r52_valcom para {$valor_pensao} quando ".bb_condicaosubpes("r52_").$condicaoaux."<br>";
           }           
           $retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
           
         } else if ($opcao_geral == 3) {
          
           $matriz1[1] = "r52_valfer";
           $matriz2[1] = $valor_pensao;
           
           if ($db_debug) {
            echo "[calc_pensao] Alterando o valor do campo r52_valfer para {$valor_pensao} quando ".bb_condicaosubpes("r52_").$condicaoaux."<br>";
           }           
           $retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
           
         } else if ($opcao_geral == 1) {
          
           $matriz1[1] = "r52_valor";
           $matriz2[1] = $valor_pensao;
           if ($db_debug) {
            echo "[calc_pensao] Alterando o valor do campo r52_valor para {$valor_pensao} quando ".bb_condicaosubpes("r52_").$condicaoaux."<br>";
           }           
           $retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
           
         } else if ($opcao_geral == 4) {
          
           $matriz1[1] = "r52_valres";
           $matriz2[1] = $valor_pensao;
           if ($db_debug) {
            echo "[calc_pensao] Alterando o valor do campo r52_valres para {$valor_pensao} quando ".bb_condicaosubpes("r52_").$condicaoaux."<br>";
           }           
           $retornar = db_update("pensao", $matriz1, $matriz2, bb_condicaosubpes("r52_").$condicaoaux );
           
         }
      }
      
    }
    $valor_pensao = 0;
    $primeira_pensao = false;
  }
  
  if ($db_debug == true) {
    echo "[calc_pensao] FIM DO CALCULO DA PENSÃO! <br>";
  }   
}
/// fim da funcao calc_pensao ///


?>