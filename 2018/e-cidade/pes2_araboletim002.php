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

include("fpdf151/pdf.php");
include("libs/db_sql.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head2 = "BOLETIM DE INFORMAÇÕES";
$head4 = "PERÍODO : ".$mes.'/'.$ano;

$where = " where rh02_anousu = $ano and rh02_mesusu = $mes and rh05_seqpes is null ";

if(isset($matriculas)){
  $where .= " and rh01_regist in ($matriculas)";
}elseif(isset($mat_ini)){
  $where .= " and rh01_regist between $mat_ini and $mat_fin";
}

if(isset($local) && trim($local) != '' ){
  $where .= " and rh56_localtrab = $local ";
}

$sql = "
 select *,
        case when rh16_ctps_n is null then 0 else rh16_ctps_n end as ctps_n,
        case when rh16_ctps_s is null then 0 else rh16_ctps_s end as ctps_s,
        case r45_situac
           when 2    then 'AFASTADO SEM REMUNERAÇÃO'
           when 3    then 'AFASTADO POR ACIDENTE'
           when 4    then 'AFASTADO EXÉRCITO'
           when 5    then 'LICENÇA GESTANTE'
           when 6    then 'AFASTADO POR DOENÇA +15 DIAS'
           when 7    then 'AFASTADO SEM VENCIMENTOS'
           when 8    then 'AFASTADO POR DOENÇA +30 DIAS'
           else           'NORMAL'
        end as situacao,
        substr(db_fxxx(rh01_regist,rh02_anousu,rh02_mesusu,rh02_instit),221,20) as padrao,
        substr(db_fxxx(rh01_regist,rh02_anousu,rh02_mesusu,rh02_instit),111,11) as f010,
        case when rh02_fpagto = 3 then 'CONTA' else 'CHEQUE' end as tip_pagto
        
 from rhpessoal 
      left  join rhpesdoc       on rh16_regist = rh01_regist
      inner join cgm            on rh01_numcgm = z01_numcgm 
      inner join rhpessoalmov   on rh01_regist = rh02_regist 
                               and rh02_anousu = $ano 
                               and rh02_mesusu = $mes
      left  join rhpesrescisao  on rh05_seqpes = rh02_seqpes 
      left  join rhpeslocaltrab on rh02_seqpes = rh56_seqpes 
                               and rh56_princ  = true  
      left join rhlocaltrab     on rh56_localtrab = rh55_codigo
                               and rh55_instit = rh02_instit
      inner join rhfuncao       on rh37_funcao = rh01_funcao
      inner join rhlota         on r70_codigo  = rh02_lota 
      left  join rhlotaexe      on rh26_codigo = r70_codigo
                               and rh26_anousu = rh02_anousu
      left  join orcorgao       on o40_anousu  = rh26_anousu
                               and o40_orgao   = rh26_orgao
      inner join rhregime       on rh02_codreg = rh30_codreg 
      left  join rhpesbanco     on rh44_seqpes = rh02_seqpes
      left  join afasta         on r45_regist  = rh01_regist 
                               and r45_anousu  = rh02_anousu 
                               and r45_mesusu  = rh02_mesusu 
                               and (r45_dtreto  > r45_anousu||'-'||r45_mesusu||'-30' or r45_dtreto is null)
 $where
       ";
//echo $sql ; exit;

$result = db_query($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem funcionários cadastrados no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 6;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   $head6 = 'MATRÍCULA : '.$rh01_regist;
   $head8 = 'NOME : '.$z01_nome;
   $pdf->addpage();

   $pdf->setfont('arial','b',10);
   $pdf->ln(5);
   $pdf->cell(0,$alt,'DADOS PESSOAIS',0,1,"C",1);
   $pdf->ln(5);

   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'LOTAÇÃO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(0,$alt,':  '.$r70_estrut.' - '.$r70_descr,0,1,"L",0);

   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'SECRETARIA',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(0,$alt,':  '.$o40_orgao.' - '.$o40_descr.($rh55_codigo == 3 ? ' - FOLHA : '.$rh01_clas1:''),0,1,"L",0);

   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'L. TRABALHO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(75,$alt,':  '.$rh55_codigo.' - '.$rh55_descr,0,0,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'SITUAÇÃO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(75,$alt,':  '.$situacao,0,1,"L",0);

   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'NASCIMENTO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(25,$alt,':  '.db_formatar($rh01_nasc,'d'),0,0,"L",0);

   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'ADMISSÃO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(25,$alt,':  '.db_formatar($rh01_admiss,'d'),0,0,"L",0);
   
   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'SALÁRIO BASE',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(25,$alt,':  '.db_formatar($f010,'f'),0,1,"L",0);

   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'CARGO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(75,$alt,':  '.$rh37_funcao.' - '.$rh37_descr,0,0,"L",0);

   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'PADRÃO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(75,$alt,':  '.$padrao,0,1,"L",0);


   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'TIPO PAGTO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(25,$alt,':  '.$tip_pagto,0,0,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'BANCO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(25,$alt,':  '.$rh44_codban,0,0,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'AGÊNCIA',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(25,$alt,':  '.$rh44_agencia.($rh44_codban == '001'?'-'.$rh44_dvagencia:''),0,0,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(15,$alt,'CONTA',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(25,$alt,':  '.$rh44_conta.'-'.$rh44_dvconta,0,1,"L",0);

   $pdf->setfont('arial','b',10);
   $pdf->ln(5);
   $pdf->cell(0,$alt,'DOCUMENTOS/ENDEREÇO',0,1,"C",1);
   $pdf->ln(5);

   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'CPF',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(75,$alt,':  '.db_formatar($z01_cgccpf,'cpf'),0,0,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'IDENTIDADE',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(25,$alt,':  '.$z01_ident,0,1,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'PIS/PASEP',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(75,$alt,':  '.$rh16_pis,0,0,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'CTPS',0,0,"L",0);
   $pdf->setfont('arial','',8);
   //$pdf->cell(35,$alt,':  '.db_formatar($cpts_n,'s','0',7,'e').'/'.db_formatar($cpts_s,'s','0',5,'e'),0,1,"L",0);
   $pdf->cell(35,$alt,':  ',0,1,"L",0);

   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'ENDEREÇO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(75,$alt,':  '.$z01_ender.($z01_numero > 0?', '.$z01_numero:'').(trim($z01_compl) != ''?', '.$z01_compl:''),0,1,"L",0);
   
   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'BAIRRO',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(75,$alt,':  '.$z01_bairro,0,0,"L",0);
   $pdf->setfont('arial','b',8);
   $pdf->cell(25,$alt,'CEP',0,0,"L",0);
   $pdf->setfont('arial','',8);
   $pdf->cell(30,$alt,':  '.db_formatar($z01_cep,'cep'),0,1,"L",0);



   $pdf->ln(5);
   $sql_calculo = "select gerfsal.*, rh27_descr 
                   from gerfsal
                        inner join rhrubricas on rh27_rubric = r14_rubric and rh27_instit = r14_instit 
                   where r14_anousu = $ano and 
                         r14_mesusu = $mes and 
                         r14_regist = $rh01_regist
                   order by r14_rubric";
   $res_calculo = db_query($sql_calculo);
   $cabecalho = 1;
   $base_prev = 0;
   $base_irrf = 0;
   $margem    = 0;
   $pre       = 0;
   $proventos = 0;
   $descontos = 0;
   $liquido   = 0;
   $margem_consignada = 0;
   $margem_deduz = 0;
   $alt       = 5;
   $num_calculo = pg_numrows($res_calculo);
//echo ' <br><br>  num calculo '.$num_calculo;exit;
   if($num_calculo > 0){
     for($xy = 0; $xy < $num_calculo;$xy++){
        db_fieldsmemory($res_calculo,$xy);
        if($cabecalho == 1){
          $pdf->setfont('arial','b',10);
          $pdf->ln(5);
          $pdf->cell(0,$alt,'DADOS FINANCEIROS',0,1,"C",1);
          $pdf->ln(5);
          $pdf->setfont('arial','b',8);
          $pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
          $pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
          $pdf->cell(15,$alt,'QUANT',1,0,"C",1);
          $pdf->cell(20,$alt,'PROVENTO',1,0,"C",1);
          $pdf->cell(20,$alt,'DESCONTO',1,1,"C",1);
          $cabecalho = 0;
        }
        if($r14_pd != 3 ){
          $pdf->setfont('arial','',8);
          $pdf->cell(15,$alt,$r14_rubric,1,0,"C",$pre);
          $pdf->cell(80,$alt,$rh27_descr,1,0,"L",$pre);
          $pdf->cell(15,$alt,$r14_quant,1,0,"R",$pre);
          if($r14_pd == 1){
            $proventos += $r14_valor;
            $pdf->cell(20,$alt,db_formatar($r14_valor, 'f'),1,0,"R",$pre);
            $pdf->cell(20,$alt,db_formatar(0, 'f'),1,1,"R",$pre);
          }else{
            $descontos += $r14_valor;
            $pdf->cell(20,$alt,db_formatar(0, 'f'),1,0,"R",$pre);
            $pdf->cell(20,$alt,db_formatar($r14_valor, 'f'),1,1,"R",$pre);
          }
//          echo "<br> Rubrica --> $r14_rubric";
          if($r14_rubric == '0005' || $r14_rubric == '0006' || $r14_rubric == '0007' || $r14_rubric == '0008' ||
             $r14_rubric == '0011' || $r14_rubric == '0014' || $r14_rubric == '0017' || $r14_rubric == '0018' ||
             $r14_rubric == '0020' || $r14_rubric == '0021' || $r14_rubric == '0023' || $r14_rubric == '0055' ||
             $r14_rubric == '0060' || $r14_rubric == '0061' || $r14_rubric == '0062' || $r14_rubric == '0063' ||
             $r14_rubric == '0064' || $r14_rubric == '0065' || $r14_rubric == '0098' || $r14_rubric == '0099' ||
             $r14_rubric == '0101' || $r14_rubric == '0104' || $r14_rubric == '0105' || $r14_rubric == '0107' ||
             $r14_rubric == '0108' || $r14_rubric == '0112' || $r14_rubric == '0116' || $r14_rubric == '0117' ||
             $r14_rubric == '0118' || $r14_rubric == '0121' || $r14_rubric == '0122' || $r14_rubric == '0126' ||
             $r14_rubric == '0129' || $r14_rubric == '0131' || $r14_rubric == '0132' || $r14_rubric == '0133' ||
             $r14_rubric == '0134' || $r14_rubric == '0135' || $r14_rubric == '0136' || $r14_rubric == '0137' ||
             $r14_rubric == '0138' || $r14_rubric == '0150' || $r14_rubric == '0151' || $r14_rubric == '0160' ||
             $r14_rubric == '0170' || $r14_rubric == '0190' ){
//          echo "<br> ENTROU NA MARGEM +++  --> $r14_rubric";
              $margem_consignada += $r14_valor;
          }elseif($r14_rubric == 'R901' || $r14_rubric == 'R904' || $r14_rubric == 'R913' || $r14_rubric == '0333'){
              $margem_consignada -= $r14_valor;
//          echo "<br> ENTROU NA MARGEM ---  --> $r14_rubric";
          }elseif($r14_rubric == '0330' ||
                  $r14_rubric == '0334' ||
                  $r14_rubric == '0335' ||
                  $r14_rubric == '0336' ||
                  $r14_rubric == '0337' ||
                  $r14_rubric == '0338' ||
                  $r14_rubric == '0340' ||
                  $r14_rubric == '0341' ||
                  $r14_rubric == '0342' ||
                  $r14_rubric == '0343' ||
                  $r14_rubric == '0344' ||
                  $r14_rubric == '0345'){
             $margem_deduz += $r14_valor;
//          echo "<br> ENTROU NA MARGEM DEDUZ --> $r14_rubric";
          }
        }elseif($r14_rubric == 'R981' || $r14_rubric == 'R982' || $r14_rubric == 'R983'){
           $base_irrf += $r14_valor;
        }elseif($r14_rubric == 'R985' || $r14_rubric == 'R986' || $r14_rubric == 'R987'){
           $base_prev += $r14_valor;
        }
         
     }
     $pdf->setfont('arial','B',8);
     $pdf->cell(15,$alt,'',1,0,"C",1);
     $pdf->cell(80,$alt,'TOTAL',1,0,"L",1);
     $pdf->cell(15,$alt,'',1,0,"R",1);
     $pdf->cell(20,$alt,db_formatar($proventos, 'f'),1,0,"R",1);
     $pdf->cell(20,$alt,db_formatar($descontos, 'f'),1,1,"R",1);
     $pdf->cell(15,$alt,'',1,0,"C",1);
     $pdf->cell(80,$alt,'LÍQUIDO',1,0,"L",1);
     $pdf->cell(15,$alt,'',1,0,"R",1);
     $pdf->cell(20,$alt,'',1,0,"R",1);
     $pdf->cell(20,$alt,db_formatar($proventos - $descontos, 'f'),1,1,"R",1);
  
  
     $pdf->setfont('arial','B',8);
     $pdf->ln(5);
     $pdf->cell(50,$alt,"BASE DE PREVIDÊNCIA  : ",0,0,"L",$pre);
     $pdf->cell(20,$alt,db_formatar($base_prev, 'f'),0,1,"R",$pre);
     $pdf->cell(50,$alt,"BASE DE IRRF : ",0,0,"L",$pre);
     $pdf->cell(20,$alt,db_formatar($base_irrf, 'f'),0,1,"R",$pre);
     $pdf->cell(50,$alt,"MARGEM CONSIGNÁVEL : ",0,0,"L",$pre);
//     echo "<br><br>MARGEM -->  $margem_consignada    DEDUZ --> $margem_deduz ";exit;
     $pdf->cell(20,$alt,db_formatar((  (( $margem_consignada*30/100 ) - $margem_deduz ) < 0?0:(($margem_consignada*30/100 ) - $margem_deduz) )   ,'f') ,0,1,"R",$pre);
   }else{
     $pdf->setfont('arial','b',8);
     $pdf->cell(70,$alt,"SEM CÁLCULO NO PERÍODO DE :  ".$mes.' / '.$ano,0,1,"R",$pre);
   }
}

$pdf->Output();
   
?>