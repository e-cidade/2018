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

function relatorio_vantagens($regist,$datacert){
  global $vinculos,$data_cert,$regist,$cgm,$head1,$head3,$head5;
  
   $head3 = "Relatório de Vantagens ";
   $head5 = "Funcionário : ".$cgm[0]["z01_nome"];

   $pdf = new PDF();
   $pdf->Open();
   $pdf->AliasNbPages();
   $total = 0;
   $pdf->setfillcolor(235);
   $pdf->setfont('arial','b',8);
   $alt = 4;
   $troca = true; 
   if( $pdf->gety() > $pdf->h - 30 || $troca  ){
       $pdf->addpage();
       $pdf->setfont('arial','b',8);
       $pdf->cell(25,$alt,"Vantagens",0,0,"C",1);
       $pdf->cell(10,$alt,"Nr",0,0,"C",1);
       $pdf->cell(10,$alt,"Percen",0,0,"C",1);
       $pdf->cell(20,$alt,"A/C de",0,0,"C",1);
       $pdf->cell(25,$alt,"Lei",0,0,"C",1);
       $pdf->cell(20,$alt,"Dt Inicial",0,0,"C",1);
       $pdf->cell(20,$alt,"Dt Final",0,1,"C",1);
       $pdf->ln($alt*2);
       $troca = false;
   }

   $desc_vant = "a";
   if( vantagem($desc_vant,$data_cert,$regist,"arq3")){
      $pdf->Output();
      return; 
   }
   global $work;
   db_selectmax("work","select * from arq3");
   for($Iwork=0;$Iwork< count($work);$Iwork++){
      $pdf->cell(25,$alt,($desc_vant = "a"?"Av - Avanço":"Gr - Gratificação"),0,0,"C",1);
      $pdf->cell(10,$alt, $work[$Iwork]["wv_anos"],0,0,"C",1);
      if( !db_empty($work[$Iwork]["wv_inf"])){
         $pdf->cell(10,$alt, $work[$Iwork]["wv_inf"],0,0,"C",1);
      }else{
         $pdf->cell(10,$alt, $work[$Iwork]["wv_perc"],0,0,"C",1);
      }
      $pdf->cell(20,$alt, $work[$Iwork]["wv_dtvant"],0,0,"C",1);
      $pdf->cell(25,$alt, $work[$Iwork]["wv_lei"],0,0,"C",1);
      global $leis;
      if( db_selectmax("leis","select * from pesleis where h08_numero = ".db_sqlformat($work[$Iwork]["wv_lei"]))){
         $pdf->cell(20,$alt, $leis[0]["h08_dtini"],0,0,"C",1);
         $pdf->cell(20,$alt, $leis[0]["h08_dtfim"],0,0,"C",1);
      }
      $pdf->ln($alt);
   }
   if( count($vinculos) > 0){
      $lei_avanc = $vinculos[0]["h11_cert01"];
      $lei_avanc2 = $vinculos[0]["h11_cert02"];
   }else{
      $lei_avanc = bb_space(200);
      $lei_avanc2 = bb_space(200);
   }

   $desc_vant = "g";
   if( vantagem($desc_vant,$data_cert,$regist,"arq4")){
      $pdf->Output();
      return; 
   }
   
   global $work;
   db_selectmax("work","select * from arq4");

   for($Iwork=0;$Iwork< count($work);$Iwork++){
      $pdf->cell(25,$alt,($desc_vant = "a"?"Av - Avanço":"Gr - Gratificação"),0,0,"C",1);
      $pdf->cell(10,$alt,$work[$Iwork]["wv_anos"],0,0,"C",1);
      if( !db_empty($work[$Iwork]["wv_inf"])){
         $pdf->cell(10,$alt, $work[$Iwork]["wv_inf"],0,0,"C",1);
      }else{
         $pdf->cell(10,$alt, $work[$Iwork]["wv_perc"],0,0,"C",1);
      }
      $pdf->cell(20,$alt, $work[$Iwork]["wv_dtvant"],0,0,"C",1);
      $pdf->cell(25,$alt, $work[$Iwork]["wv_lei"],0,0,"C",1);
      global $leis;
      if( db_selectmax("leis","select * from pesleis where h08_numero = ".db_sqlformat($work[$Iwork]["wv_lei"]))){
         $pdf->cell(20,$alt, $leis[0]["h08_dtini"],0,0,"C",1);
         $pdf->cell(20,$alt, $leis[0]["h08_dtfim"],0,0,"C",1);
      }
      $pdf->ln($alt);
   }
   if( count($vinculos) > 0){
      $lei_grat = $vinculos[0]["h11_cert01"];
      $lei_grat2 = $vinculos[0]["h11_cert02"];
   }else{
      $lei_grat = bb_space(200);
      $lei_grat2 = bb_space(200);
   }
   $pdf->ln($alt*2);
   if( !db_empty($lei_avanc)){
       $pdf->cell(15,$alt,db_substr($lei_avanc,1,130),0,1,"C",0);
       $pdf->cell(15,$alt,db_substr($lei_avanc,131,70)." ".db_substr($lei_avanc2,1,60),0,1,"C",0);
      if( !db_empty($lei_avanc2)){
         $pdf->cell(15,$alt,db_substr($lei_avanc2,60,130),0,1,"C",0);
         $pdf->cell(15,$alt,db_substr($lei_avanc2,190,12),0,1,"C",0);
      }
   }
   $pdf->ln($alt);
   if( !db_empty($lei_grat)){
       $pdf->cell(15,$alt,db_substr($lei_grat,1,130),0,1,"C",0);
       $pdf->cell(15,$alt,db_substr($lei_grat,131,70)." ".db_substr($lei_grat2,1,60)." ",0,1,"C",0);
      if( !db_empty($lei_grat2)){
         $pdf->cell(15,$alt,db_substr($lei_grat2,60,130),0,1,"C",0);
         $pdf->cell(15,$alt,db_substr($lei_grat2,190,10),0,1,"C",0);
      }
   }
   $pdf->Output();
}


function vantagem($tipo_vantagem,$datadia,$registro,$arq_work){

global $work,$assenta,$Iassenta,$pessoal;

$data_vantagem = array();
$dias_prot     = array();

// cria arquivo work ;

$nome     = array();
$tipo     = array();
$tamanho  = array();
$decimais = array();

$nome[1] = "wv_regist";
$nome[2] = "wv_dtbase";
$nome[3] = "wv_dtvant";
$nome[4] = "wv_lei";
$nome[5] = "wv_anos";
$nome[6] = "wv_perc";
$nome[7] = "wv_tipo";
$nome[8] = "wv_protel";
$nome[9] = "wv_nome";
$nome[10] = "wv_lotac";
$nome[11] = "wv_inf";

$tipo[1] = "n";
$tipo[2] = "d";
$tipo[3] = "d";
$tipo[4] = "c";
$tipo[5] = "n";
$tipo[6] = "n";
$tipo[7] = "c";
$tipo[8] = "n";
$tipo[9] = "c";
$tipo[10] = "c";
$tipo[11] = "c";

$tamanho[1] = 6;
$tamanho[2] = 8;
$tamanho[3] = 8;
$tamanho[4] = 6;
$tamanho[5] = 2;
$tamanho[6] = 6;
$tamanho[7] = 1;
$tamanho[8] = 10;
$tamanho[9] = 40;
$tamanho[10] = 4 ;
$tamanho[11] = 3;

$decimais = array_fill(1,11,0);
$decimais[6] = 2;

db_criatemp($arq_work,$nome,$tipo,$tamanho,$decimais);

global $work;
db_selectmax( "work", "select * from ".$arq_work );

// carrega tabela de protelacoes para memoria somente dos avancoes ou gratifica-;
// coes. a tabela tab_soma funciona em conjunto com a tab_vantagem, pois em al-;
// guns casos, quando o conteudo do campo tipoasse->h12_efetiv estiver com ;
// conteudo "+", as protelacoes funcionam ao contrario: a data da vantagem nao;
// prorroga e sim retorna.;
// (tab_soma = 1 soma ) (tab_soma = 2 diminui)  (tab_soma = 3 dobra ) ;

$tab_protelac = array();
$tab_soma     = array();
$i = 0;
$condicaoaux = " where h19_tipo = ".db_sqlformat( $tipo_vantagem );
global $protelac,$tipoasse;
db_selectmax("protelac","select * from protelac ".$condicaoaux." order by h19_assent, h19_tipo" );

for($Iprotelac=0;$Iprotelac< count($protelac);$Iprotelac++){
      $i++;
      $tab_protelac[$i] = $protelac[$Iprotelac]["h19_assent"];
      $tab_soma[$i] = 1;
      if(db_selectmax("tipoasse","select * from tipoasse where h12_assent = " .db_sqlformat($protelac[$Iprotelac]["h19_assent"]))){
         return $work;
      }else{
         if( $tipoasse[0]["h12_efetiv"] == "+"){
            $tab_soma[$i] = 2;
         }else{
            $tab_soma[$i] = 3;
         }
      }
}

// testa se o processamento eh geral ou so de um funcionario *******************;


if( db_empty($registro)){
 // somente na opcao de relatorios de avanco/gratificao este item e zerado;

   $condicaoaux  = " and r01_tpvinc != 'p'";
   $condicaoaux .= " and ( r01_regime = ".db_sqlformat( $pessoal[0]["r01_regime"]) ;
   $condicaoaux .= "      or '".db_str($pessoal[0]["r01_regime"],1,0)."' = '0' )";
   $condicaoaux .= " and ( r01_recis is null ";
   $condicaoaux .= "      or r01_recis is not null and r01_recis >= ".db_sqlformat( $datadia ) .")";
   $condicaoaux .= " and r01_regist <= 10 ";


   $campos_pess = "r01_regist,r01_numcgm,r01_recis,r01_tpvinc,r01_funcao,r01_regime,r01_admiss,r01_lotac ";
   global $pessoal;
   db_selectmax("pessoal","select ".$campos_pess." from pessoal ". bb_condicaosubpes("r01_").$condicaoaux );
}
for($Ipessoal=0;$Ipessoal<count($pessoal);$Ipessoal++){
   if( $registro != $pessoal[$Ipessoal]["r01_regist"]){
       break;
   }
   // se funcionario demitido antes da data de fim ignora **********************;
   
   if( !db_empty($pessoal[$Ipessoal]["r01_recis"]) && db_mktime($pessoal[$Ipessoal]["r01_recis"]) < db_mktime($datadia)){
      continue;
   }
   
   // ignora funcionarios pensionistas *****************************************;
   
   if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) == "p"){
      continue;
   }
   
   // pega data de admissao do funcionario ou a data de contagem de trienio ****;
   
   $data_admissao = "";


   $condicaoaux  = "select h16_assent,h16_regist,h16_dtconc ";
   $condicaoaux .= "  from assenta ";
   $condicaoaux .= " inner join assentamentofuncional on rh193_assentamento_funcional = h16_codigo";
 
   $condicaoaux .= "  left outer join tipoasse ";
   $condicaoaux .= "    on h12_codigo = h16_assent";
   $condicaoaux .= "   and h12_efetiv = 'I' ";

   $condicaoaux .= "where h16_regist = ".db_sqlformat( db_str($pessoal[$Ipessoal]["r01_regist"],6) );
   $condicaoaux .= "  and h12_assent is not null ";
   $condicaoaux .= "order by h16_dtconc, h16_assent ";
   global $assenta;
   if(db_selectmax( "assenta", $condicaoaux )){
      $data_admissao = $assenta[0]["h16_dtconc"];
   }

   
   if( db_empty($data_admissao)){
      $data_admissao = $pessoal[$Ipessoal]["r01_admiss"];
   }
   
   // verifica se funcionario esta enquadrado em alguma lei, de acordo com *****;
   // seu regime e funcao.;
   global $vinculos;
   if( db_selectmax("vinculos","select * from vinculos where h11_tipo = " . db_sqlformat($tipo_vantagem) . " and h11_regime = " . db_sqlformat(db_str($pessoal[$Ipessoal]["r01_regime"],1))." and h11_funcao = " . db_sqlformat(db_str($pessoal[$Ipessoal]["r01_funcao"],5))) 
     || db_selectmax("vinculos","select * from vinculos where h11_tipo = " . db_sqlformat($tipo_vantagem) . " and h11_regime = " . db_sqlformat(db_str($pessoal[$Ipessoal]["r01_regime"],1))." and h11_funcao = '99999'")){
      
      if( busca_lei($vinculos[0]["h11_lei1"],$tipo_vantagem)){
         $anos_ant  = 0;
         $anos_perc = 0;
         
         // se funcionario inativo a data de fim sera a de admissao, a de inicio;
         // sera pega a do trienio, que normalmente esta preenchida no cadastro.;
         
         if( strtolower($pessoal[$Ipessoal]["r01_tpvinc"]) == "a" ){
            $data_fim = $datadia;
         }else{
            if( $datadia < $pessoal[$Ipessoal]["r01_admiss"]){
               $data_fim = $datadia;
            }else{
               $data_fim = $pessoal[$Ipessoal]["r01_admiss"];
            }
         }
         
         $data_fim  = date("Y-m-d",db_mktime($data_fim)+(5*365*86400));

         // calcula numero de anos. a matriz data_vantagem fica com todas as ***;
         // em que o funcionario completa um ano.;
         
         $data_vantagem = array_fill(1,99," ");
         $dias_prot     = array_fill(1,99,0);
         $anos = 0;
         $data_calculo = $data_admissao;
         if( db_day($data_calculo) == 29 && db_month($data_calculo) == 2){
            $data_calculo = date("Y-m-d",db_mktime($data_calculo) - (1*86400));
         }
         $dia_vantagem = db_str(db_day($data_calculo),2,0,"0");
         while(db_mktime($data_calculo) <= db_mktime($data_fim)){
            $anos += 1;
            if( db_val(db_str(bcdiv(db_year($data_calculo),4,0),13,2)) == db_val(db_str(db_year($data_calculo)/4,13,2)) ){
               $bisexto = 365; // 366;
            }else{
               $bisexto = 365;
            }
            $data_calculo  = date("Y-m-d",db_mktime($data_calculo) + ($bisexto*86400));
            $data_vantagem[$anos] = $data_calculo ;
         }
         $anos -= 1;
         
         // continua processamento somente se o numero de anos do funcionario **;
         // for maior que zero.;
         
         $data_ultima_nomeacao = "";
         if(!db_empty($anos)){
            
            // arruma datas da tabela data_vantagem de acordo com as protelacoes.;
            global $assenta;
            if( db_selectmax("assenta","select * from assenta inner join assentamentofuncional on rh193_assentamento_funcional = h16_codigo where h16_regist = " . db_sqlformat(db_str($pessoal[0]["r01_regist"],6))." order by h16_dtconc, h16_assent ")){
               $assentamento_final = false;
               $data_inicio_protelar = "";
	       $data_final_protelar  = "";
	       $Iassenta = 0;
               while($Iassenta < count($assenta) ){
                  
                  // verifica se o tipo de assentamento conta para protelacao **;
                  // e se a data inicial do assentamento eh posterior a data de ;
                  // inicio data_admissao;
  
                  if( db_selectmax("tipoasse","select h12_assent,h12_efetiv from tipoasse where h12_codigo = " . db_sqlformat($assenta[$Iassenta]["h16_assent"]))){
                     if( !$assentamento_final){
                        if( strtolower($tipoasse[0]["h12_efetiv"]) == "f" ){
                           $data_inicio_protelar = $assenta[$Iassenta]["h16_dtconc"];
                           $assentamento_final = true;
                        }
                     }else{
                        if( strtolower($tipoasse[0]["h12_efetiv"] == "i") && !db_empty($data_inicio_protelar)){
                           $data_final_protelar =  $assenta[$Iassenta]["h16_dtconc"] ;
                           $data_ultima_nomeacao = $assenta[$Iassenta]["h16_dtconc"];
                           
                           $dias_protelar = db_datedif($data_final_protelar,$data_inicio_protelar) - 1;
                           for($i=1;$i <=$anos;$i++){
                              if( (db_mktime($data_inicio_protelar)+(1*86400)) <= db_mktime($data_vantagem[$i])){
                                 break;
                              }
                           }
                           
                           // soma os dias a protelar nas proximas ocorrencias da ***;
                           // tabela.;
                           
                           for($j=$i;$j<=$anos;$j++){
                              $data_vantagem[$j] = date("Y-m-d",db_mktime($data_vantagem[$j]) + ($dias_protelar*86400));
                              $dias_prot[$j]    += $dias_protelar;
                           }
                           $assentamento_final = false;
			   $data_inicio_protelar = "";
			   $data_final_protelar  = "";
                        }
                     }
                  }
                  $posicao = db_ascan($tab_protelac,$assenta[$Iassenta]["h16_assent"]);
                  if( !db_empty($posicao) ){
                     
                     // busca em data_vantagem o periodo em que a protelacao ***;
                     // enquadra.;
                     
                     if( db_empty($assenta[$Iassenta]["h16_dtterm"])){
			$termo = date('Y-m-d',db_getsession('DB_datausu'));
                     }else{
                        $termo = $assenta[$Iassenta]["h16_dtterm"];
                     }
                     $dias_protelar = db_datedif($termo,$assenta[$Iassenta]["h16_dtconc"]) + 1;

                     if( $tab_soma[$posicao] == 2){
                        $dias_protelar = $dias_protelar * -1;
                     }else{
                        $dias_protelar = ( $dias_protelar * 2 ) * -1;
                     }

                     $condicaoaux  = " where h19_assent = " . db_sqlformat($assenta[$Iassenta]["h16_assent"]) ;
                     $condicaoaux .= "   and h19_tipo = " . db_sqlformat($tipo_vantagem);
                     $condicaoaux .= "   and h19_dia01 > 0 ";
		     global $protelac;
                     if( db_selectmax("protelac","select * from protelac ".$condicaoaux )){
                           for($iprot=1;$iprot<=10;$iprot++){
                              if( $dias_protelar < $protelac[0]["h19_dia".db_str($iprot,2,0,"0")]){
                                 $oper_pro = $protelac[0]["h19_op".db_str($iprot,2,0,"0")];
                                 $perc_pro = $protelac[0]["h19_per".db_str($iprot,2,0,"0")];
                                 if( $oper_pro == "="){
                                    $dias_protelar = $perc_pro;
                                 }else{
                                    $operador = "$dias_protelar .$oper_pro . db_val('".db_str($perc_pro,5,2)."');";
                                    $dias_protelar = eval($operador);
                                 }
                                 break;
                              }
                           }
                     }
                     for($i=1;$i<= $anos;$i++){
                        if( db_mktime($assenta[$Iassenta]["h16_dtconc"]) <= db_mktime($data_vantagem[$i])){
                           if( db_at(db_str($tab_soma[$posicao],1),"2-3") > 0 ){
                              $diferenca_diminuir = db_datedif($data_vantagem[$i],$assenta[$Iassenta]["h16_dtconc"]);
                           }else{
                              $diferenca_diminuir = 0;
                           }
                           break;
                        }
                     }
                     
                     // soma os dias a protelar nas proximas ocorrencias da ***;
                     // tabela.;
                     
                     for($j=$i;$j < count($anos);$j++){
                        if( db_empty($diferenca_diminuir)){
                           $data_vantagem[$j] = date("Y-m-d",(db_mktime($data_vantagem[$j]) + ($dias_protelar*86400)));
                        }else{
                           $data_vantagem[$j] = date("Y-m-d",(db_mktime($data_vantagem[$j]) - ($diferenca_diminuir*86400)));
                           $diferenca_diminuir = 0;
                        }
                        $dias_prot[$j] += $dias_protelar;
                     }
                     
                  }
	       $Iassenta++;
               }
            }
            global $work; 
            db_selectmax("work","select * from " .$arq_work);

            enquadra_na_lei();
            if( busca_lei($vinculos[0]["h11_lei2"],$tipo_vantagem)){
               enquadra_na_lei();
               if( busca_lei($vinculos[0]["h11_lei3"],$tipo_vantagem)){
                  enquadra_na_lei();
                  if( busca_lei($vinculos[0]["h11_lei4"],$tipo_vantagem)){
                     enquadra_na_lei();
                     if( busca_lei($vinculos[0]["h11_lei5"],$tipo_vantagem)){
                        enquadra_na_lei();
                     }
                  }
               }
            }
         }
      }
   }
}

$indice = " order by wv_dtvant ";
global $work_;
if( !db_selectmax("work_","select * from " .$arq_work .$indice )){
    return false;
}

$passou = false;

$max_conta = count();
$Iwork = 0;
for($Iwork=0;$Iwork< count($work_);$Iwork++){

   $campos_pesso  = " r01_regist,r01_numcgm,r01_admiss,r01_recis,r01_funcao,";
   $campos_pesso .= " r01_regime,r01_tpvinc,r01_lotac ";
   $condicaoaux   = " and r01_regist = " . db_sqlformat(db_str($work_[$Iwork]["wv_regist"],6));
   global $pessoal;
   if( !db_selectmax("pessoal","select ".$campos_pesso." from pessoal ".bb_condicaosubpes("r01_") .$condicaoaux )){
      return false;
   }

   if(strtolower($pessoal[0]["r01_tpvinc"]) == "a" ){
      $data_fim  = $datadia;
   }else{
      if( db_mktime($datadia) < db_mktime($pessoal_[0]["r01_admiss"])){
         $data_fim = $datadia;
      }else{
         $data_fim = $pessoal[0]["r01_admiss"];
      }
   }

   if( db_mktime($work_[$Iwork]["wv_dtvant"]) > db_mktime($data_fim)){
      $posic = $Iwork;
      db_delete($arq_work," where wv_regist = " . db_sqlformat($work_[$Iwork]["wv_regist"]). " and wv_dtvant = " . db_sqlformat( $work_[$Iwork]["wv_dtvant"]));

      if( count($work_) < $posic){
          break;
      }

      $Iwork = $posic;
  }

}

return true;
}

function enquadra_na_lei(){

global $leis,$tipo_vantagem,$data_ultima_nomeacao,$anos,$work,$Iwork,$data_fim,$pessoal,$arq_work,$anos_ant,$anos_perc;

if( strtolower($tipo_vantagem) == strtolower($leis[0]["h08_tipo"]) ){

   // enquadra numero de anos para buscar percentual da vantagem;
   
   if( !db_empty($data_ultima_nomeacao)){
      if( !db_empty($leis[0]["h08_dtfim"])){
         if( db_mktime($data_ultima_nomeacao) > db_mktime($leis[0]["h08_dtfim"])){
            return false;
         }
      }
   }
   
   $grava_codigo_lei = true;
   $percentual = 0;
   for($i=1;$i<=18;$i++){

      if( $i < 10){
         $campo = db_str($i,1);
      }else{
         $campo = db_str($i,2,0,"0");
      }
      if( db_empty($leis[0]["h08_perc".$campo]) || db_empty($leis[0]["h08_anos".$campo])){
         break;
      }
      $percentual  = $leis[0]["h08_perc".$campo];
      $inform      = $leis[0]["h08_car".$campo];
      $anos_perc   = $leis[0]["h08_anos".$campo];
      if( $anos >= $anos_perc && !db_empty($anos_perc) && $anos_perc > $anos_ant){
         $dt_vantagem   = $data_vantagem[$anos_perc];
         $dias_protelar = $dias_prot[$anos_perc];
         
         // grava registro calculado se funcionario tem percentual a receber e a;
         // lei ainda eh valida.;
         if( db_mktime($data_ultima_nomeacao) > db_mktime($dt_vantagem)){
            $data_consessao = $data_ultima_nomeacao;
         }else{
            $data_consessao = $dt_vantagem;
         }
         
         if( db_mktime($data_consessao) < db_mktime($leis[0]["h08_dtini"]) && $leis[0]["h08_numero"] == $work[$Iwork]["wv_lei"]){
            if( count($work) > 0){
               if( !db_empty($pessoal[0]["r01_regist"])){
                  db_delete($arq_work," where wv_regist = " . db_sqlformat($work[$Iwork]["wv_regist"]) . " and wv_dtvant = ".db_sqlformat( $work[$Iwork]["wv_dtvant"] ));
               }

               $grava_codigo_lei = true;
            }
            $data_consessao = $leis[0]["h08_dtini"];
         }
         
         if( ( db_mktime($dt_vantagem) <= db_mktime($leis[0]["h08_dtfim"] || db_empty($leis[0]["h08_dtfim"]) ) && db_mktime($data_fim) >= db_mktime($data_consessao)) ){
            $condicaoaux = " where z01_numcgm = ".db_sqlformat( $pessoal[0]["r01_numcgm"] );
	    global $cgm;
            db_selectmax( "cgm", "select z01_nome from cgm ".$condicaoaux );
            $mar1 = array();
	    $mar2 = array();
	    
            $mar1[01] = "wv_regist";
            $mar1[02] = "wv_lotac";
            $mar1[03] = "wv_dtbase";
            $mar1[04] = "wv_dtvant";
            $mar1[05] = "wv_anos";
            $mar1[06] = "wv_perc";
            $mar1[07] = "wv_tipo";
            $mar1[08] = "wv_protel";
            $mar1[09] = "wv_inf";
            $mar1[10] = "wv_lei";
            $mar1[11] = "wv_nome";
            
            $mar2[01] = $pessoal[0]["r01_regist"];
            $mar2[02] = $pessoal[0]["r01_lotac"] ;
            $mar2[03] = $data_admissao;
            $mar2[04] = $data_consessao;
            $mar2[05] = $anos_perc;
            $mar2[06] = $percentual;
            $mar2[07] = $tipo_vantagem;
            $mar2[08] = $dias_protelar;
            $mar2[09] = $inform;
            $mar2[10] = bb_space(6);
            $mar2[11] = $cgm[0]["z01_nome"];
            
            if( $grava_codigo_lei || db_empty($registro)){
               $mar2[10] = $leis[0]["h08_numero"];
               $grava_codigo_lei = false;
               if( db_mktime($data_consessao) < db_mktime($leis[0]["h08_dtini"])){
                  $mar2[4] = $leis[0]["h08_dtini"];
               }
            }
            db_insert($arq_work,$mar1,$mar2);
            $anos_ant = $anos_perc;
         }
         
      }

    db_selectmax($work, "select * from ".$arq_work );

    }

  }
}

function busca_lei($lei,$tipo_vantagem){
  
  global $leis;
  $retornar = false;

  if(!db_empty($lei)){
     if(!db_selectmax("leis","select * from pesleis where h08_numero = " . db_sqlformat($lei). " and h08_tipo = " . db_sqlformat($tipo_vantagem))){
        $retornar = false;
     }else{
        $retornar = true;
     }
  }else{
     $retornar = false;
  }
  return $retornar;
}

global $cfpess,$subpes,$d08_carnes,$matric ;

include(modification("fpdf151/pdf.php"));
include(modification("libs/db_libpessoal.php"));
include(modification("libs/db_sql.php"));

db_postmemory($HTTP_GET_VARS);

$subpes = db_anofolha().'/'.db_mesfolha();

relatorio_vantagens($regist,$datacert);

//exit;

db_redireciona("rh02_vantagens001.php");

?>