<?php
/**
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

/**
 * imp_gradeefetividade
 *
 * @param mixed $cparam
 * @param mixed $matric
 * @param mixed $datacert
 * @param mixed $matriz_tot_assent
 * @param mixed $cert
 * @param mixed $final_pag
 * @param mixed $tipo_certd
 * @param mixed $certinic
 * @param mixed $num_proc
 * @param mixed $pdf
 * @access public
 * @return void
 */
function imp_gradeefetividade($cparam,$matric,$datacert,$matriz_tot_assent=null,$cert=true,$final_pag=true,$tipo_certd=true,$certinic=null,$num_proc=null,$pdf=null){
  global $pessoal, $cgm, $funcao, $lotacao, $padroes,$arquivo,$tot_assent_mat, $campos_pessoal;

  //   echo "<BR> passou aqui !!! $cparam"; 
  $erro = false;  
  // Quando chamado pela opção : Relatórios / Grade de Efevidade
  // Quando chamado pela opção : Relatórios / Emissão da Certidao do Tempo de Serviço

  // Ano_fim representa até que ano fazer o levantamento do tempo de serviço 
  $ano_fim = db_val(db_substr(db_dtoc($datacert),-4));
  $sql     = "select cgm.*, 
                     RH02_ANOUSU   as r01_anousu, 
                     RH02_MESUSU   as r01_mesusu, 
                     RH01_REGIST   as r01_regist,
                     RH01_NUMCGM   as r01_numcgm, 
                     trim(TO_CHAR(RH02_LOTA,'9999')) as r01_lotac,
                     RH01_ADMISS   as r01_admiss, 
                     RH05_RECIS    as r01_recis, 
                     RH02_tbprev   as r01_tbprev,
                     RH30_REGIME   as r01_regime, 
                     RH30_VINCULO  as r01_tpvinc,
                     RH02_salari   as r01_salari,
                     RH03_PADRAO   as r01_padrao,
                     RH02_HRSSEM   as r01_hrssem,
                     RH02_HRSMEN   as r01_hrsmen, 
                     RH01_NASC     as r01_nasc,
                     rh65_rubric   as r01_rubric, 
                     rh65_valor    as r01_arredn,
                     RH02_EQUIP    as r01_equip,
                     RH01_PROGRES  as r01_anter,  
                     RH01_TRIENIO  as r01_trien, 
                     (case when RH01_PROGRES IS NOT NULL then 'S' else 'N' end) as r01_progr, 
                     RH15_DATA     as r01_fgts,
                     RH05_CAUSA    as r01_causa,  
                     RH05_CAUB     as r01_caub,  
                     RH05_MREMUN   as r01_mremun,
                     RH01_FUNCAO   as r01_funcao,
                     RH01_CLAS1    as r01_clas1,
                     RH01_CLAS2    as r01_clas2,
                     RH02_TPCONT   as r01_tpcont,
                     RH02_OCORRE   as r01_ocorre, 
                     rh51_b13fo    as r01_b13fo, 
                     rh51_basefo   as r01_basefo,
                     rh51_descfo   as r01_descfo, 
                     rh51_d13fo    as r01_d13fo,
                     RH02_TIPSAL   as r01_tipsal,
                     RH19_PROPI    as r01_propi ,
                     rh01_depirf   as r01_depirf, 
                     rh01_vale     as r01_vale, 
                     rh01_depsf    as r01_depsf,
                     rh37_funcao   as r37_funcao,
                     rh37_descr    as r37_descr,
                     r70_estrut    as r13_codigo,
                     r70_descr     as r13_descr,
                     rh01_nacion   as r01_nacion,
                     r02_descr
                from rhpessoalmov
                     inner join rhpessoal      on rhpessoal.rh01_regist       = rhpessoalmov.rh02_regist
                     inner join rhlota         on rhlota.r70_codigo           = rhpessoalmov.rh02_lota
                                              and rhlota.r70_instit           = rhpessoalmov.rh02_instit  
                     inner join cgm            on cgm.z01_numcgm              = rhpessoal.rh01_numcgm
                     left join rhpesrescisao   on rhpesrescisao.rh05_seqpes   = rhpessoalmov.rh02_seqpes 
                     left join rhpespadrao     on rhpespadrao.rh03_seqpes     = rhpessoalmov.rh02_seqpes
                     left join rhregime        on rhregime.rh30_codreg        = rhpessoalmov.rh02_codreg 
                                              and rhregime.rh30_instit        = rhpessoalmov.rh02_instit 
                     left join rhpesrubcalc    on rhpesrubcalc.rh65_seqpes    = rhpessoalmov.rh02_seqpes 
                                              and (rh65_rubric = 'R927' or rh65_rubric = 'R929')
                     left join rhpesfgts       on rhpesfgts.rh15_regist       = rhpessoalmov.rh02_regist
                     left join tpcontra        on tpcontra.h13_codigo         = rh02_tpcont  
                     left join rhinssoutros    on rh51_seqpes                 = rh02_seqpes 
                     left join rhpesprop       on rh19_regist                 = rh02_regist
                     left join rhfuncao        on rh37_funcao                 = rh01_funcao
                                              and rh37_instit                 = rh02_instit
                     left join padroes         on rh03_anousu                 = r02_anousu
                                              and rh03_mesusu                 = r02_mesusu
                                              and rh03_regime                 = r02_regime
                                              and rh03_padrao                 = r02_codigo
                                              and r02_instit                  = rh02_instit
                     ".bb_condicaosubpes("rh02_" )." and rh01_regist = $matric";

   db_selectmax("pessoal", $sql); 
   $nfuncao    = $pessoal[0]["r37_descr"];
   $lotac      = $pessoal[0]["r13_descr"];
   $npad       = $pessoal[0]["r02_descr"];
   $arquivo    = "arq1";
   $nom        = array();
   $tip        = array();
   $tam        = array();
   $dec        = array();

   $nom[1] = "w_regist";
   $nom[2] = "w_ano";
   $nom[3] = "w_mes";
   $nom[4] = "w_assent";
   $nom[5] = "w_dias";
   $tip = array_fill(1,5,"c");
   $tip[1] = "n";
   $tip[5] = "n";
   $tam[1] = 6;
   $tam[2] = 4;
   $tam[3] = 2 ;
   $tam[4] = 5;
   $tam[5] = 3;
   $dec = array_fill(1,5,0);

   db_criatemp($arquivo,$nom,$tip,$tam,$dec);

   $retorno = db_query("create index work3_in on ".$arquivo." (w_regist,w_ano,w_mes,w_assent)") ;
   $csql    = "select *,tipoasse.h12_assent,tipoasse.h12_efetiv,tipoasse.h12_reltot from assenta
     inner join tipoasse on h12_codigo = h16_assent 
     inner join assentamentofuncional on rh193_assentamento_funcional = h16_codigo
     where  h16_regist = " .db_sqlformat(db_str($pessoal[0]["r01_regist"],6));
   $tot_assent_mat = count($matriz_tot_assent);
   if( $cparam == 3 && $tot_assent_mat > 0 && $matriz_tot_assent[0] <> '' ){

     // Quando chamado pela opção : Relatórios / Grade de Efevidade
     $csql .= " and h16_assent in (";
     for($k=0;$k<$tot_assent_mat;$k++){
       if($k>0){
         $csql .= ",";
       }
       $csql .= "'".$matriz_tot_assent[$k]."'";
     }
     $csql .= ")";
   }else{
     // Quando chamado pela opção : Relatórios / Emissão da Certidao do Tempo de Serviço
     $csql .= " and h12_graefe = 't' ";
   }

   global $assenta,$Iassenta;
   //echo $csql;
  //db_criatabela(db_query($csql));
  //exit;
   if( db_selectmax("assenta",$csql." order by h16_dtconc, h16_assent ")){

     $Iassenta = 0;
     if ($cparam == 2 ){
       // Quando chamado pela opção : Relatórios / Emissão da Certidao do Tempo de Serviço

       for($Iassenta=0;$Iassenta<count($assenta);$Iassenta++) {

         if ($assenta[$Iassenta]["h16_regist"] == $pessoal[0]["r01_regist"]) {
           break;
         }   
         if( strtolower($assenta[$Iassenta]["h12_efetiv"]) == "n" && $assenta[$Iassenta]["h12_reltot"] <= 1){
           continue;
         }else{
           break;
         }
       }
       // localiza o inicio para avaliacao do tempo certidao que deve constar;
       // na grade de efetividade;
       $certinic = $assenta[$Iassenta]["h16_dtconc"];
     }

     $inicio_certidao = $assenta[$Iassenta]["h16_dtconc"];

     // final_certidao indica a data limite para levantamento do tempo de serviço 
     $final_certidao = $datacert;

     $inicio_contagem = "";
     $final_contagem  = "";
     $inicio = false;

     $erro = false;

     $listou_assentamentos = false;

     // Faz o levantamento do Tempo de Serviço por Assentamento e grava no arquivo temporário "work3"

/*
      -- Aqui mostra como o programa vai varrer o arquivo assenta para montar a grade de efetividade

      select *,
             tipoasse.h12_assent,
             tipoasse.h12_efetiv,
             tipoasse.h12_reltot 
      from assenta 
      inner join tipoasse on h12_codigo = h16_assent 
                       where h16_regist = ' 612' 
                         and h16_assent in ('14','19','45')  order by h16_dtconc, h16_assent

      h16_codigo  h16_regist  h16_assent  h16_dtconc  h16_histor                                      h16_nrport  h16_atofic h16_quant  h16_perc  h16_dtterm  h16_hist2   h16_login   h16_dtlanc  h16_conver  h12_codigo  h12_assent  h12_descr               h12_dias  h12_relvan  h12_relass  h12_reltot  h12_relgra  h12_tipo  h12_graefe  h12_efetiv  h12_tipefe  h12_regenc  h12_assent  h12_efetiv  h12_reltot
      11642       612         19          1991-05-23  NOMEADO P/ CARGO EFETIVO DE MOTORISTA DE ONIBUS 308/91      PORTARIA   0          0                                 1                       f           19          INE         INICIO DE EFETIVIDADE   0         f           f           1           f           S         t           I           P           f           INE         I           1
      16584       612         45          1998-03-30  PERIODO AQUISITIVO DE 23/05/91-22/03/96.        031/98                 64         0         1998-06-01              1           2004-02-26  f           45          LPG         LICENCA PREMIO EM GOZO  0         f           f           0           f           A         t           N           P           f           LPG         N           0
      22631       612         14          2001-06-26  ATESTADO                                                               2          0         2001-06-27              1           2001-07-05  f           14          LS          LICENCA SAUDE           0         f           f           1           f           A         t           N           P           f           LS          N           1
      20657       612         14          2002-05-21  ATESTADO                                                               15         0         2002-06-04              1           2002-06-25  f           14          LS          LICENCA SAUDE           0         f           f           1           f           A         t           N           P           f           LS          N           1
      16444       612         45          2003-04-10  PERIODO AQUISITIVO: 23.05.95 A 22.05.01   122/03  PORTARIA             90         0         2003-07-08              1           2003-08-27  f           45          LPG         LICENCA PREMIO EM GOZO  0         f           f           0           f           A         t           N           P           f           LPG         N           0
      17183       612         14          2003-12-20  ATESTADO  401099  PROCESSO                                             1          0         2003-12-20              1           2003-12-23  f           14          LS          LICENCA SAUDE           0         f           f           1           f           A         t           N           P           f           LS          N           1
      18778       612         14          2005-12-12  ATESTADO  517062  PROCESSO                                             10         0         2005-12-21              1           2005-12-30  f           14          LS          LICENCA SAUDE           0         f           f           1           f           A         t           N           P           f           LS          N           1
      18932       612         14          2006-07-07  ATESTADO  556505  PROCESSO                                             1          0         2006-07-07              1           2006-07-25  f           14          LS          LICENCA SAUDE           0         f           f           1           f           A         t           N           P           f           LS          N           1
      16435       612         45          2007-06-16  PORTARIA  493/07  PORTARIA                                             93         0         2007-09-16              1           2007-07-10  f           45          LPG         LICENCA PREMIO EM GOZO  0         f           f           0           f           A         t           N           P           f           LPG         N           0
      28822       612         14          2008-04-14  ATESTADO.   69171   PROCESSO                                           4          0         2008-04-17              23374       2008-05-14  f           14          LS          LICENCA SAUDE           0         f           f           1           f           A         t           N           P           f           LS          N           1

      Este while() percorre cada registro desta tabela, pega por exemplo o registro 11642 e chama a funcao cria_assenta() e vai 
      para o próximo registro ,no caso 16584, e chama novamente a funcao cria_assenta() e vai assim até o registro 28822.
 */
     $primeiro = true;
     $Iassenta =0 ;
     $lTemInicioDeEfetividade = false;
     while ($Iassenta < count($assenta)){
       if( $cparam != 3){
         // Quando chamado pela opção : Relatórios / Emissão da Certidao do Tempo de Serviço
         if( strtolower($assenta[$Iassenta]["h12_efetiv"]) == "s" ){
           // h12_efetiv = s -> Não soma Tempo
           $Iassenta++;
           continue;
         }
       }
       $registra_assenta = "E";

       if (!$inicio) {

         if( strtolower($assenta[$Iassenta]["h12_efetiv"]) != "i" && strtolower($assenta[$Iassenta]["h12_efetiv"]) != "n" && strtolower($assenta[$Iassenta]["h12_efetiv"]) != "d"){

           $registro_local = $Iassenta;
           $mes_local  = db_month($assenta[$Iassenta]["h16_dtconc"]);
           $ano_local  = db_year($assenta[$Iassenta]["h16_dtconc"]);
           $tem_inicio = false;
           while($Iassenta < count($assenta) && $mes_local == db_month($assenta[$Iassenta]["h16_dtconc"]) 
             && $ano_local == db_year($assenta[$Iassenta]["h16_dtconc"])){
             if( strtolower($assenta[$Iassenta]["h12_efetiv"]) == "i"){
               $tem_inicio = true;
             }
             $Iassenta++;
           }
           $Iassenta = $registro_local;
         }

         if( strtolower($assenta[$Iassenta]["h12_efetiv"]) == "i") {

           $lTemInicioDeEfetividade = true;
           $inicio                  = true;
           $registra_assenta        = "I";
         }
       } else {

         if ($tot_assent_mat == 0 ) {

           if (strtolower($assenta[$Iassenta]["h12_efetiv"]) == "i" ) {

             $erro_msg = "Assentamento de inicio ja cadastrado : ".$assenta[$Iassenta]["h16_assent"]." verifique.";
             $erro     = true;
             break;
           }
         }
       }


       if( strtolower($assenta[$Iassenta]["h12_efetiv"]) == "f" ){
         $registra_assenta = "F";
         $inicio = false;
       }

       if( strtolower($assenta[$Iassenta]["h12_efetiv"]) == "-"){
         $registra_assenta = "-";
       }

       if( strtolower($assenta[$Iassenta]["h12_efetiv"]) == "n"){
         $registra_assenta = "N";
       }

       cria_assenta($registra_assenta, (strtolower($assenta[$Iassenta]["h12_tipefe"])=="i"?1:0),$certinic,$datacert,$tipo_certd);
       $listou_assentamentos = true;

       $Iassenta++; 
       continue;

     }
     if (!$lTemInicioDeEfetividade) {

       $erro_msg = "Sem assentamento de inicio.";
       $erro     = true;
     }
     // ------------ Começa a Impressão da Grade de Efetividade ou a Certidão de Tempo de Serviço

     global $work3;
     if( $erro == false){

       if(db_selectmax("work3","select * from " .$arquivo. " order by w_regist,w_ano,w_mes,w_assent")){
         if( $cparam == 0){
           $ano_inic_cert = db_substr(db_dtoc($certinic),7,4);
           $mes_inic_cert = db_substr(db_dtoc($certinic),4,2);
           if( db_empty($pessoal[0]["r01_recis"])){
             $ano_fim_cert = db_substr(db_dtoc($datacert),7,4);
             $mes_fim_cert = db_substr(db_dtoc($datacert),4,2);
           }else{
             // Quando chamado pela opção : Relatórios / Grade de Efevidade
             // Quando chamado pela opção : Relatórios / Emissão da Certidao do Tempo de Serviço
             if( $datacert < $pessoal[0]["r01_recis"]){
               $ano_fim_cert = db_substr(db_dtoc($datacert),7,4);
               $mes_fim_cert = db_substr(db_dtoc($datacert),4,2);
             }else{
               $ano_fim_cert = db_substr(db_dtoc($pessoal[0]["r01_recis"]),7,4) ;
               $mes_fim_cert = db_substr(db_dtoc($pessoal[0]["r01_recis"]),4,2);
             }
           }

           db_delete($arquivo," where w_ano < " .db_sqlformat($ano_inic_cert)." or w_ano > " .db_sqlformat($ano_fim_cert)
             . " or (w_ano = " .db_sqlformat($ano_fim_cert)." and w_mes > " .db_sqlformat($mes_fim_cert).")" 
             . " or (w_ano = " .db_sqlformat($ano_inic_cert)." and w_mes < " .db_sqlformat($mes_inic_cert).")");

         }else if($cparam == 2){
           // Quando chamado pela opção : Relatórios / Emissão da Certidao do Tempo de Serviço
           if( db_empty($pessoal[0]["r01_recis"])){
             $ano_fim_cert = db_substr(db_dtoc($datacert),7,4);
             $mes_fim_cert = db_substr(db_dtoc($datacert),4,2);
           }else{
             $ano_fim_cert = db_substr(db_dtoc($pessoal[0]["r01_recis"]),7,4) ;
             $mes_fim_cert = db_substr(db_dtoc($pessoal[0]["r01_recis"]),4,2);
           }
           $cond  = " where w_ano > " .db_sqlformat($ano_fim_cert); 
           $cond .= "  or (w_ano = " .db_sqlformat($ano_fim_cert) ;
           $cond .= " and w_mes > " .db_sqlformat($mes_fim_cert).")";
           db_delete($arquivo,$cond);

         }else if($cparam == 3){
           // Quando chamado pela opção : Relatórios / Grade de Efevidade
           $ano_inic_cert = db_substr(db_dtoc($certinic),7,4);
           $mes_inic_cert = db_substr(db_dtoc($certinic),4,2);
           if( db_empty($pessoal[0]["r01_recis"])){
             $ano_fim_cert = db_substr(db_dtoc($datacert),7,4);
             $mes_fim_cert = db_substr(db_dtoc($datacert),4,2);
           }else{
             $ano_fim_cert = db_substr(db_dtoc($pessoal[0]["r01_recis"]),7,4) ;
             $mes_fim_cert = db_substr(db_dtoc($pessoal[0]["r01_recis"]),4,2);
           }
           db_delete($arquivo," where w_ano < " .db_sqlformat($ano_inic_cert)." or w_ano > " .db_sqlformat($ano_fim_cert)." or (w_ano = " .db_sqlformat($ano_fim_cert)." and w_mes > " .db_sqlformat($mes_fim_cert).") or (w_ano = " .db_sqlformat($ano_inic_cert)." and w_mes < " .db_sqlformat($mes_inic_cert).")");
           $Iassenta = 0;
         }

         $cond = "select w_regist,w_ano,w_mes,w_assent,w_dias,h12_assent,h12_graefe from ".$arquivo ;
         $cond .= " left join tipoasse ";
         $cond .= "   on h12_assent = w_assent::varchar(5) ";
         $cond .= "  and h12_graefe = 't' ";
         $cond .= " where (w_assent is not null or (trim(w_assent) = 'E' and w_assent is null ))" ;

         $cond .= " order by w_regist,w_ano,w_mes,w_assent";
         global $work3,$head1,$head3,$head4,$head5,$head6,$head7,$head8,$head9, $tipoasse;
         ////db_criatabela(db_query($cond ));
         if( db_selectmax("work3", $cond )){
           //	echo "<BR> work3 --> ".print_r($work3);

           $regist_base = $work3[0]["w_regist"];
           $ano_cont = db_val($work3[0]["w_ano"]);

           ////////// Comeca a impressao

           //$head5 = "PERÍODO : ".db_formatar($dataini,'d')." A ".db_formatar($datafin,'d');

           $head1 = "GRADE EFETIVIDADE"; 
           $head3 = "Matrícula: {$pessoal[0]['r01_regist']}-".substr($pessoal[0]['z01_nome'],0,29);
           $head4 = "Lotação: " . substr($lotac,0,33);
           $head5 = "Admissão: " . db_dtoc($pessoal[0]["r01_admiss"]);
           $head6 = "Rescisão/Exoneração: " . db_dtoc($pessoal[0]["r01_recis"]);
           $head7 = "Função: " . substr($nfuncao,0,33);
           $head8  = "Padrão: " . substr($npad,0,33);
           if( $cparam == 0){
             if( !db_empty($num_proc)){
               $head7 = "Processo: ".$num_proc;
               $head8 = "Carga Horária :".db_str($pessoal[0]["r01_hrsmen"],6);
             }
           }else if($cparam == 3){
             // Quando chamado pela opção : Relatórios / Grade de Efevidade
             $pdf = new PDF();
             $pdf->Open();
             $pdf->AliasNbPages();
           }
           $total = 0;
           $pdf->setfillcolor(235);
           $pdf->setfont('arial','b',8);
           $alt = 5;


           $max = 0;
           $total_munic = 0;
           $Iwork3=0;
           $troca = 0;

           //echo "<BR> 1 while($Iwork3 < ".count($work3)."){";
           while($Iwork3 < count($work3)){

             $ano_base = $work3[$Iwork3]["w_ano"];
             //       echo "<BR> ano_base --> $ano_base" ;
             if($pdf->gety() > $pdf->h - 30 || $troca == 0){
               $troca = 1;
               $pdf->addpage();
               $pdf->setfont('arial','b',8);
               $pdf->cell(13,$alt,'Ano',1,0,"C",1);
               $pdf->cell(13,$alt,'Jan',1,0,"C",1);
               $pdf->cell(13,$alt,'Fev',1,0,"C",1);
               $pdf->cell(13,$alt,'Mar',1,0,"C",1);
               $pdf->cell(13,$alt,'Abr',1,0,"C",1);
               $pdf->cell(13,$alt,'Mai',1,0,"C",1);
               $pdf->cell(13,$alt,'Jun',1,0,"C",1);
               $pdf->cell(13,$alt,'Jul',1,0,"C",1);
               $pdf->cell(13,$alt,'Ago',1,0,"C",1);
               $pdf->cell(13,$alt,'Set',1,0,"C",1);
               $pdf->cell(13,$alt,'Out',1,0,"C",1);
               $pdf->cell(13,$alt,'Nov',1,0,"C",1);
               $pdf->cell(13,$alt,'Dez',1,0,"C",1);
               $pdf->cell(21,$alt,'Total',1,1,"C",1);
               $final_pag = false;

             }


             $ano_temp = $work3[$Iwork3]["w_ano"];

             $matriz = array();
             $matdias = array();

             for($i=1;$i<= 12;$i++){
               for($j=1;$j<= 10;$j++){
                 $matriz[$i][$j]  = "       ";
                 $matdias[$i][$j] = "  ";
               }
             }

             $maior = 0;


             if( $cparam != 3){
               
               // Quando chamado pela opção : Relatórios / Emissão da Certidao do Tempo de Serviço
               $cond = "select w_regist,w_ano,w_mes,w_assent,w_dias from " .$arquivo ;
               $cond .= " left join tipoasse ";
               $cond .= "   on h12_assent = w_assent::varchar(5) ";
               $cond .= "  and h12_graefe = 't' ";
               $cond .= " where (w_assent is not null ";
               $cond .= "        or (trim(w_assent) = 'E' and w_assent is null ))" ;
               $cond .= "   and w_ano = " .db_sqlformat($ano_base);
               $cond .= " order by w_regist,w_ano,w_mes,w_assent";
               db_selectmax("work3", $cond);

             }else{
               db_selectmax("work3","select * from " . $arquivo . " where w_regist = " .db_sqlformat($pessoal[0]["r01_regist"])." and w_ano = " .db_sqlformat($ano_temp). " order by w_regist,w_ano,w_mes,w_assent");
             }
             $Iwork3 = 0; 
             $totano = 0;
             while($Iwork3 < count($work3) && $ano_temp == $work3[$Iwork3]["w_ano"]){

               $totmes = 0;
               $mes_temp = $work3[$Iwork3]["w_mes"];
               $indice = 0;

               while($Iwork3 < count($work3) && $mes_temp == $work3[$Iwork3]["w_mes"] && $ano_temp == $work3[$Iwork3]["w_ano"]){

                 $indice++;
                 $matriz[db_val($mes_temp)][$indice] = $work3[$Iwork3]["w_assent"];
                 $matdias[db_val($mes_temp)][$indice] = db_str($work3[$Iwork3]["w_dias"],2,0,"0");

                 if( strtolower(trim($work3[$Iwork3]["w_assent"])) == "e"){
                   $totmes += $work3[$Iwork3]["w_dias"];
                 }

                 if( $maior < $indice){
                   $maior = $indice;
                 }

                 $Iwork3++;

               }

               $totano += $totmes;

             }
             if($Iwork3>0){
               $Iwork3 = $Iwork3 - 1;
               $ano_temp = $work3[$Iwork3]["w_ano"];
             }
             $lado = 1;
             if($maior > 1){
               $lado = "LT";
             }
             $pdf->cell(13,$alt,$ano_base,$lado,0,"C",0); // Ano Base
             for($k=1;$k <= $maior;$k++){

               if( $maior > 1 ){

                 if($k < $maior ){
                   $lado = "LR";
                 }else if($k == $maior ){
                   $lado = "LBR";
                 }

                 if($k > 1 ){
                   $pdf->cell(13,$alt,'',$lado,0,"C",0);
                 }
               }

               for($t=1;$t<=12;$t++){

                 if( db_empty($matriz[$t][$k]) || db_val($matdias[$t][$k]) == 0){
                   $pdf->cell(13,$alt,'',$lado,0,"C",0);
                 }else{
                   $pdf->cell(13,$alt,$matdias[$t][$k].$matriz[$t][$k],$lado,0,"C",0);
                 }

               }

               if( $maior > 1 && $k != $maior){
                 $pdf->cell(21,$alt,'',"LR",1,"C",0);
               }


             }
             if($maior > 1){
               $lado = "BR";
             }else{
               $lado = 1;
             }
             $pdf->cell(21, $alt, db_str($totano,3,0,"0"),$lado,1,"C",0);
             $total_munic += $totano;

             $ano_cont += 1;
             $ano_base = db_str(db_val($ano_base)+1,4);

             while(true){

               if( $cparam != 3){

                 // Quando chamado pela opção : Relatórios / Emissão da Certidao do Tempo de Serviço
                 $cond = "select w_regist,w_ano,w_mes,w_assent,w_dias from " . $arquivo ;
                 $cond .= " left join tipoasse ";
                 $cond .= "   on h12_assent = w_assent::varchar(5) ";
                 $cond .= "  and h12_graefe = 't' ";
                 $cond .= " where (w_assent is not null ";
                 $cond .= "        or (trim(w_assent) = 'E' and w_assent is null ))" ;
                 $cond .= "   and w_regist = " .db_sqlformat($regist_base) ;
                 $cond .= "   and w_ano = " .db_sqlformat($ano_base);
                 $cond .= " order by w_regist,w_ano,w_mes,w_assent";
               }else{
                 $cond = "select w_regist,w_ano,w_mes,w_assent,w_dias from " . $arquivo ;
                 $cond .= " where w_regist = " .db_sqlformat($regist_base) ;
                 $cond .= "   and w_ano = " .db_sqlformat($ano_base);
                 $cond .= " order by w_regist,w_ano,w_mes,w_assent";
               }
               if( db_selectmax("work3",$cond)){
                 $Iwork3=0;
                 break;

               }else{
                 // Ano_fim representa até que ano fazer o levantamento do tempo de serviço 
                 if( $ano_cont > $ano_fim){
                   $Iwork3=0;
                   break;
                 }
                 $ano_cont += 1;
                 $ano_base = db_str(db_val($ano_base)+1,4);
               }

             }

           }
           if($total_munic > 0){ 
             $pdf->ln(10);
             $pdf->cell(130,$alt,"",0,0,"L",0);
             $pdf->cell(25,$alt,"Total do Período: ",0,0,"L",0,'');
             $pdf->cell(45,$alt, DBDate::getIdadeCompleta($total_munic),0,1,"L",0);
           }

           $cond = "select * from " . $arquivo ;
           $cond .= " inner join tipoasse ";
           $cond .= "   on h12_assent = w_assent::varchar(5) order by w_assent";
           db_selectmax("work3",$cond);

           $pdf->ln($alt);

           if ($pdf->gety() > $pdf->h - 30  ){
             $pdf->addpage();
             $pdf->setfont('arial','B',8);
           }

           $pdf->cell(0,$alt,"Resumo das Ocorrências",0,1,"L",0);
           $pdf->ln(2);
           $tempo_servico = array();
           $quant_tempo   = array();

           $max = 0;
           $Iwork3 =0;
           while($Iwork3<count($work3)){

             $total_dias = 0;
             $assent = $work3[$Iwork3]["w_assent"];

             $h12efetiv = $work3[$Iwork3]["h12_efetiv"];
             $h12reltot = $work3[$Iwork3]["h12_reltot"] ;
             $h12graefe = $work3[$Iwork3]["h12_graefe"];
             $h12descr  = $work3[$Iwork3]["h12_descr"];
             if( $h12reltot == 2){

               while($Iwork3<count($work3) && $work3[$Iwork3]["w_assent"] == $assent){
                 $total_dias += $work3[$Iwork3]["w_dias"];
                 $Iwork3++;
               }

               $total_dias = 0;

               $cond  = "select sum(h16_quant)::integer as total_dias from assenta inner join tipoasse ";
               $cond .= "on h16_assent = h12_codigo where h16_regist = " .db_sqlformat(db_str($matric,6))." and h12_reltot = 2";
               global $trans;
               db_selectmax("trans",$cond);

               $total_dias = $trans[0]["total_dias"];

             }else{
               while($Iwork3<count($work3) && $work3[$Iwork3]["w_assent"] == $assent){
                 $total_dias += $work3[$Iwork3]["w_dias"];
                 $Iwork3++;
               }
             }


             if( $h12efetiv == "D"){
               $total_dias += $total_dias;
             }

             // monta matriz para totalizar tempo de servico;

             if( trim($assent) != "E"){
               $tipo = " ";
               if( $h12reltot == 9){
                 $tipo = $assent;
               }else{
                 $tipo = db_str($h12reltot,1);
               }
             }else{
               $tipo = "1";
             }
             if( !db_empty($tipo)){
               $posicao = db_ascan($tempo_servico,$tipo);
               if(db_empty($posicao)){
                 $max += 1;
                 $posicao = $max;
                 $quant_tempo[$posicao] = 0;
               }
               $tempo_servico[$posicao] = $tipo;
               $quant_tempo[$posicao] = $quant_tempo[$posicao] + $total_dias;
             }
             $pdf->setfont('arial','',8);
             if( trim($assent) != "E" && db_boolean( $h12graefe )){
               $pdf->cell(100,$alt,$assent."( ".$h12descr.")",0,0,"L",0,'','.');
               $pdf->cell(20,$alt,$total_dias." dias ",0,1,"R",0);
             }else{
               $pdf->cell(100,$alt,$assent."( ".$h12descr.")",0,0,"L",0,'','.');
               $pdf->cell(20,$alt,$total_dias." dias ",0,1,"R",0);
             }
           }
           $pdf->ln($alt);
           $pdf->setfont('arial','b',8);
           if( $cparam == 0){
             $pdf->cell(80,$alt,"Totais do Tempo de Serviço - Período ",0,0,"L",0,'','.');
             $pdf->cell(40,$alt,db_formatar($certinic,'d')." a ".db_formatar($datacert,'d'),0,1,"R",0);
           }else{
             // Quando chamado pela opção : Relatórios / Grade de Efevidade
             // Quando chamado pela opção : Relatórios / Emissão da Certidao do Tempo de Serviço
             $pdf->cell(80,$alt,"Totais do Tempo de Serviço - Período ",0,0,"L",0,'','.');
             $pdf->cell(40,$alt, db_formatar($inicio_certidao,'d')." a ".(db_empty($pessoal[0]["r01_recis"])?db_formatar($final_certidao,'d'):db_formatar($pessoal[0]["r01_recis"],'d')),0,1,"R",0);
           }
           $pdf->ln($alt);
           $contador = 1;

           $total_tempo_servico = 0;

           $pdf->setfont('arial','',8);
           for($i=1;$i<=($max);$i++){
             if( $tempo_servico[$i] == "1"){
               if($total_munic > 0){
                 $pdf->cell(100,$alt,"Tempo de Serviço Municipal",0,0,"L",0,'','.');
                 $pdf->cell(20,$alt,$total_munic." dias" ,0,1,"R",0);
               }
               $total_tempo_servico +=  $total_munic ;
               continue;
             }else if($tempo_servico[$i] == "6"){
               $pdf->cell(100,$alt,"Tempo de Municipal Averbado ",0,0,"L",0,'','.');
             }else if($tempo_servico[$i] == "2"){
               $pdf->cell(100,$alt,"Tempo de Empresa Privada ",0,0,"L",0,'','.');
             }else if($tempo_servico[$i] == "3"){
               $pdf->cell(100,$alt,"Tempo Exército Nacional ",0,0,"L",0,'','.');
             }else if($tempo_servico[$i] == "4"){
               $pdf->cell(100,$alt,"Tempo Federal ",0,0,"L",0,'','.');
             }else if($tempo_servico[$i] == "5"){
               $pdf->cell(100,$alt,"Tempo Estadual ",0,0,"L",0,'','.');
             }else if(!db_empty($tempo_servico[$i])){
               db_selectmax("tipoasse","select * from tipoasse where trim(h12_assent) = ".db_sqlformat(trim($tempo_servico[$i]))) ;
               $pdf->cell(100,$alt,ucwords(strtolower(db_substr($tipoasse[0]["h12_descr"],1,38))),0,0,"L",0,'','.');
             }else{
               continue;
             }
             if( $tempo_servico[$i] == "2"){

               $total_dias = 0;
               $cond = "select sum(h16_quant)::integer as total_dias from assenta inner join tipoasse ";
               $cond .= "on h16_assent = h12_codigo where h16_regist = " .db_sqlformat(db_str($matric,6))." and h12_reltot = 2";
               global $trans;  
               db_selectmax("trans",$cond);

               $total_dias = $trans[0]["total_dias"];

               $quant_tempo[$i] = $total_dias;
             }
             $pdf->cell(20,$alt,$quant_tempo[$i]." dias",0,1,"R",0);;
             $total_tempo_servico += $quant_tempo[$i];
           }

           if($total_tempo_servico > 0){
             $pdf->setfont('arial','b',8);
             $pdf->cell(100,$alt,"Total Tempo de Serviço ",0,0,"L",0,'','.');
             $pdf->cell(20,$alt,$total_tempo_servico." dias",0,1,"R",0);
           }

           if($cparam == 3){
             // Quando chamado pela opção : Relatórios / Grade de Efevidade
             $pdf->Output("tmp/gradeevetividade_".date('ymdHis').".pdf", false);
           }
         }
       }else{
         $erro_msg = "Relatorio Vazio";
         $erro = true;
       }
     } 
   }
   if($erro){
     db_redireciona('db_erros.php?fechar=true&db_erro='.$erro_msg);
     exit;
   }
   return $pdf;
}



/**
 * Registra os assentamentos para serem impressos no relatório
 *
 * @param mixed $tipo_contagem
 * @param mixed $somatorio
 * @param mixed $certinic
 * @param mixed $datacert
 * @param mixed $tipo_certd
 * @access public
 * @return void
 */
function cria_assenta($tipo_contagem, $somatorio,$certinic,$datacert,$tipo_certd){

  global $assenta, $Iassenta,$pessoal,$datacert,$arquivo,$cert,$tot_assent_mat;

  // Data da Concessão do Assentamento ou Afastamento
  $dias_inicio = db_day($assenta[$Iassenta]["h16_dtconc"]);
  $ano_inicio  = db_year($assenta[$Iassenta]["h16_dtconc"]);
  $mes_inicio  = db_month($assenta[$Iassenta]["h16_dtconc"]);

  // Data Final do Período indicado na chamada da Geração da Grade de Efetividade 
  if( db_empty($assenta[$Iassenta]["h16_dtterm"]) || db_mktime($assenta[$Iassenta]["h16_dtterm"]) > db_mktime($datacert)){

    // datacert indica a data limite para levantamento do tempo de serviço 
    $ano_final   = db_year($datacert);
    $mes_final   = db_month($datacert);
    $dias_final  = db_day($datacert);
  }else{

    // Caso o assentamento tiver data final , tem prioridade esta data em relação a data final do período da grade de efetividade indica no form

    $ano_final   = db_year($assenta[$Iassenta]["h16_dtterm"]);
    $mes_final   = db_month($assenta[$Iassenta]["h16_dtterm"]);
    $dias_final  = db_day($assenta[$Iassenta]["h16_dtterm"]) ;
  }


  // data do Inicio do Assentamento


  $codigo_assentamento = $assenta[$Iassenta]["h12_assent"];


  // Numero de dias para levar em conta no mês inicial do Assentamento ; 
  // Explo: se for de 30 dias o mes inicial mas a concessão do assentamento começou no dia 12 então o mês Efetivo será de 29 dias 
  // --> calculo : 30 - (12 - 1) = 29 -- > 29 dias restam no m¿s.

  $nro_dias_do_mes = dias_mes($ano_inicio,$mes_inicio);


  // I - Inicializa
  // N - Desconsidera     --> Só grava o Assentamento, mas não diminui e nem aumenta os dias efetivos 'E'
  // D - Tempo em Dobro   --> Só grava o assentamento, mas não diminui e nem aumenta os dias efetivos 'E'
  // + - Soma
  // E - Efetivos
  if($tipo_contagem == "F"){

    $mat1 = array();
    $mat2 = array();

    $mat1[1] = "w_regist";
    $mat1[2] = "w_ano";
    $mat1[3] = "w_mes";
    $mat1[4] = "w_assent";
    $mat1[5] = "w_dias";

    $mat2[1] = $pessoal[0]["r01_regist"];
    $mat2[2] = db_str($ano_inicio,4);
    $mat2[3] = db_str($mes_inicio,2,0,"0");
    $mat2[4] = $assenta[$Iassenta]["h12_assent"];
    $mat2[5] = 1;

    db_insert($arquivo,$mat1,$mat2);

    // O fim do Assentamento quantidade de dias Efetivos vai ser a data da Concessao. 
    $cond  = "select * from " . $arquivo . " left join tipoasse on h12_assent = w_assent::varchar(5) where w_regist = " .db_sqlformat($pessoal[0]["r01_regist"]);
    $cond .= "   and w_ano = " .db_sqlformat(db_str($ano_inicio,4)) ." and w_mes = " .db_sqlformat(db_str($mes_inicio,2,0,"0"))."  and h12_efetiv = '-'  order by w_regist,w_ano,w_mes ";
    global $work4;
    if( !db_selectmax("work4",$cond)){
      $mat2[1] = $pessoal[0]["r01_regist"];
      $mat2[2] = db_str($ano_inicio,4);
      $mat2[3] = db_str($mes_inicio,2,0,"0");
      $mat2[4] = "E";
      $mat2[5] = db_day($assenta[$Iassenta]["h16_dtconc"]) - $somatorio;

      db_update($arquivo,$mat1,$mat2," where w_regist = " .db_sqlformat($pessoal[0]["r01_regist"])." 
        and w_ano = " .db_sqlformat(db_str($ano_inicio,4))." 
        and w_mes = " .db_sqlformat(db_str($mes_inicio,2,0,"0"))." 
        and trim(w_assent) = 'E'");

    }

    return;
  }elseif($tipo_contagem == "I" ){
    $codigo_assentamento = "E";
  }

  if($mes_inicio == $mes_final && $ano_inicio == $ano_final && $dias_final < dias_mes($ano_inicio,$mes_inicio)){
    $qtde_dias_do_assentamento = ($dias_final-$dias_inicio)+1;
  }else{
    $qtde_dias_do_assentamento = dias_mes($ano_inicio,$mes_inicio) - ( $dias_inicio - 1);
  }

  // Este while()  Varre o intervalo que começa na data h16_dtconc ( Data de concessão do Assentamento ) e vai até 
  // a data de h16_dtterm ( data de termino do Assentamento )


  while(!(($ano_final == $ano_inicio && $mes_inicio > $mes_final) || $ano_inicio > $ano_final)){
    //echo "<BR> ($ano_inicio == $ano_final && $mes_inicio > $mes_final ) $dias_inicio e $dias_final";


    $saldo_nro_dias_efetivo = 0;

    $condicaoaux  = " where w_regist = " .db_sqlformat($pessoal[0]["r01_regist"]) ;
    $condicaoaux .= " and w_ano = " .db_sqlformat(db_str($ano_inicio,4)) ;
    $condicaoaux .= " and w_mes = " .db_sqlformat(db_str($mes_inicio,2,0,'0')) ;
    $condicaoaux .= " and trim(w_assent) = 'E'" ;
    global $work5;
    if(db_selectmax("work5","select * from " . $arquivo .$condicaoaux )){
      $saldo_nro_dias_efetivo = $work5[0]["w_dias"];
    }


    if( $tipo_contagem == "-"){

      // Inicio do ajuste do assentamento do tipo 'E' para exemplo : para refletir FNJ - os dias de Falta Não Justificada

      if( $saldo_nro_dias_efetivo > 0){

        $mat1 = array();
        $mat2 = array();

        $mat1[1] = "w_dias";
        $mat2[1] = 0;

        //echo "<BR>    if( $saldo_nro_dias_efetivo > $qtde_dias_do_assentamento){";
        if( $saldo_nro_dias_efetivo > $qtde_dias_do_assentamento){

          // Aqui estamos diminuindo o Assentamento tipo 'E', pois temos que ajustas-lo quando temos no mes assentamentos ,explo, tipo FNJ.

          $mat2[1] = $saldo_nro_dias_efetivo - $qtde_dias_do_assentamento;
        }
        $saldo_nro_dias_efetivo = $mat2[1]; 
        //echo "<BR>   numero_dias_efetivo = ".$mat2[1]; 

        db_update($arquivo,$mat1,$mat2, $condicaoaux );

      }
      // Fim do ajuste do assentamento do tipo 'E' para exemplo : para refletir FNJ - os dias de Falta Não Justificada
    }

    // Inicio do bloco que registra todos os Assentamentos que não descontam no assentamento tipo dia Efetivo 'E'

    $condicaoaux = " where w_regist = " .db_sqlformat($pessoal[0]["r01_regist"]) ;
    $condicaoaux.= " and w_ano = " .db_sqlformat(db_str($ano_inicio,4)) ;
    $condicaoaux.= " and w_mes = " .db_sqlformat(db_str($mes_inicio,2,0,"0")) ;
    $condicaoaux.= " and trim(w_assent) = " .db_sqlformat( $codigo_assentamento );
    global $work4;
    if( !db_selectmax("work4","select * from " . $arquivo . $condicaoaux )){

      // Caso não tenha sido gravado nem uma vez o assentamento na grade , entra aqui

      $mat1 = array();
      $mat2 = array();

      $mat1[01] = "w_regist";
      $mat1[02] = "w_ano";
      $mat1[03] = "w_mes";
      $mat1[04] = "w_assent";
      $mat1[05] = "w_dias";

      $mat2[01] = $pessoal[0]["r01_regist"];
      $mat2[02] = db_str($ano_inicio,4);
      $mat2[03] = db_str($mes_inicio,2,0,"0");
      $mat2[04] = $codigo_assentamento;
      $mat2[05] = $qtde_dias_do_assentamento;
      //echo "<BR> mat2[05] = $qtde_dias_do_assentamento;";

      db_insert($arquivo,$mat1,$mat2);
    }else{
      $mat1 = array();
      $mat2 = array();
      $mat1[1] = "w_dias";

      $qtde_dias_do_assentamento = $work4[0]["w_dias"]+$qtde_dias_do_assentamento;
      //echo "<BR>   $qtde_dias_do_assentamento = ".$work4[0]["w_dias"]."+".$qtde_dias_do_assentamento;
      //echo "<BR>   ($tipo_contagem == '-' && ($qtde_dias_do_assentamento+$saldo_nro_dias_efetivo) <= $nro_dias_do_mes )";
      if($tipo_contagem == "-" && ($qtde_dias_do_assentamento+$saldo_nro_dias_efetivo) <= $nro_dias_do_mes ){
        $mat2[1] = $qtde_dias_do_assentamento;
        //echo "<BR> 1 gravando  $qtde_dias_do_assentamento;";
        db_update($arquivo,$mat1,$mat2, $condicaoaux );
      }elseif($tipo_contagem != "-"){
        $mat2[1] = $qtde_dias_do_assentamento;
        //echo "<BR> 2 gravando  $qtde_dias_do_assentamento;";
        db_update($arquivo,$mat1,$mat2, $condicaoaux );
      }

    }

    // Fim do bloco que registra todos os Assentamentos que não descontam no assentamento tipo dia Efetivo 'E'

    // ------------------ Atenção ----------------------  

    // este código que faz avançar no intervalo entre a data inicial e final do periodo do levantamento da grade de efetividade
    // Vai avançando de mês em mês

    $ano_atual = $ano_inicio;
    $mes_atual = $mes_inicio;
    $mes_inicio += 1;
    if( $mes_inicio > 12){
      $mes_inicio = 1;
      $ano_inicio += 1;
    }


    // ------------------ Fim da atenção ----------------------  

    // Quantidades de dias no mes

    $nro_dias_do_mes = dias_mes($ano_inicio,$mes_inicio);

    if( db_val(db_substr(db_dtoc($assenta[$Iassenta]["h16_dtterm"]),4,2)) == $mes_inicio && db_year($assenta[$Iassenta]["h16_dtterm"]) == $ano_inicio){
      $nro_dias_do_mes = db_day($assenta[$Iassenta]["h16_dtterm"]);
    }

    $qtde_dias_do_assentamento = $nro_dias_do_mes;

  } // end while()

  if($tipo_contagem == "I"){
    $condicaoaux  = " where w_regist = " .db_sqlformat($pessoal[0]["r01_regist"]) ;
    $condicaoaux .= " and w_ano = " .db_sqlformat(db_str($ano_final,4)) ;
    $condicaoaux .= " and w_mes = " .db_sqlformat(db_str($mes_final,2,0,'0')) ;
    $condicaoaux .= " and trim(w_assent) = 'E'" ;
    global $work5;
    if(db_selectmax("work5","select * from " . $arquivo .$condicaoaux )){
      $mat1 = array();
      $mat2 = array();
      $mat1[1] = "w_dias";
      $mat2[1] = $dias_final;
      db_update($arquivo,$mat1,$mat2, $condicaoaux );

    }  
  }
}

/**
 * Retorna a quantidade de dias no mês
 *
 * @param mixed $mano
 * @param mixed $mmes
 * @access public
 * @return void
 */
function dias_mes($mano,$mmes){

  if( $mmes == 1 || $mmes == 3 || $mmes == 5 || $mmes == 7 || $mmes == 8 || $mmes == 10 || $mmes == 12){
    $dias = 31;
  }else if( $mmes == 2){
    if( ($mano/4) == bcdiv($mano,4,0)){
      $dias = 29;
    }else{
      $dias = 28;
    }
  }else{
    $dias = 30;
  }

  return $dias;

}
