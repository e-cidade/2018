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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_rhlocaltrab_classe.php");
$clrhlocaltrab = new cl_rhlocaltrab;

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

if($tipoarq == 's'){
  $arquivo = 'gerfsal';
  $sigla   = 'r14';
  $head8 = 'TIPO - SALARIO';
}elseif($tipoarq == 'd'){
  $arquivo = 'gerfs13';
  $sigla   = 'r35';
  $head8 = 'TIPO - 13o.  SALARIO';
}elseif($tipoarq == 'c'){
  $arquivo = 'gerfcom';
  $sigla   = 'r48';
  $head8 = 'TIPO - COMPLEMENTAR';
}


$result_local = $clrhlocaltrab->sql_record($clrhlocaltrab->sql_query_file($local,db_getsession('DB_instit'), "rh55_codigo, rh55_codigo||'-'||rh55_descr as rh55_descr", "rh55_descr" ));
db_fieldsmemory($result_local,0);

$head2 = $rh55_descr;
$head4 = "PERIODO : ".$mes." / ".$ano;


$where_banco = '';
if($xbanco == 'b'){
  if( $xconta == 'cc'){
    $head6 = "BANCO DO BRASIL - COM CONTA ";
    $where_banco = " and trim(rh44_codban) = '001' and rh02_fpagto = 3 ";
  }elseif( $xconta == 'sc'){
    $head6 = "BANCO DO BRASIL - SEM CONTA ";
    $where_banco = " and trim(rh44_codban) = '001' and rh02_fpagto <> 3 ";
  }else{
    $head6 = "BANCO DO BRASIL - TODOS ";
    $where_banco = " and trim(rh44_codban) = '001' ";
  }
}elseif($xbanco == 'c' ){
  if( $xconta == 'cc'){
    $head6 = "CAIXA ECONOMICA FEDERAL - COM CONTA ";
    $where_banco = " and trim(rh44_codban) = '104' and rh02_fpagto = 3 ";
  }elseif( $xconta == 'sc'){
    $head6 = "CAIXA ECONOMICA FEDERAL - SEM CONTA ";
    $where_banco = " and trim(rh44_codban) = '104' and rh02_fpagto <> 3 ";
  }else{
    $head6 = "CAIXA ECONOMICA FEDERAL - TODOS ";
    $where_banco = " and trim(rh44_codban) = '104' ";
  }
}elseif($xbanco == 't'){
  if( $xconta == 'cc'){
    $head6 = "TODOS OS BANCOS - COM CONTA ";
    $where_banco = " and rh02_fpagto = 3 ";
  }elseif( $xconta == 'sc'){
    $head6 = "TODOS OS BANCOS - SEM CONTA ";
    $where_banco = " and rh02_fpagto <> 3 ";
  }else{
    $head6 = "TODOS OS BANCOS ";
  }
}


$where_dentista = '';

$where_local = " and rh56_localtrab = $local";


$select_campos = "
                  r70_estrut,
                  r70_descr,
                 ";
$group_ordem = "
                group by r70_estrut,
                         r70_descr
                order by r70_estrut
";


if($local == 1 ){
$select_campos = "
                  r70_estrut,
                  r70_descr,
                  r70_estrut as quebra,
                  r70_descr  as descr_quebra,
                 ";
  $group_ordem = "
                  group by r70_estrut,
                           r70_descr
                  order by r70_estrut
                 ";
}elseif($local == 2){
$select_campos = "r70_estrut,
                  r70_descr,
                  o40_orgao as quebra,
                  o40_descr as descr_quebra,
                 ";
  $group_ordem = "
                  group by r70_estrut,
                           r70_descr,
                           o40_orgao,
                           o40_descr
                  order by o40_orgao, r70_estrut

                 ";
}elseif($local == 3){
$select_campos = "r70_estrut,
                  r70_descr,
                  rh01_clas1 as quebra,
                  case rh01_clas1
                       when 'A' then 'EFETIVOS - F. DE PAGTO DOS PROFESSORES E PESSOAL EM ATIVIDADE PEDAGOGICAS DO ENSINO FUNDAMENTAL - (FOLHA-A)'
                       when 'B' then 'EFETIVOS - F. DE PAGTO DO PESSOAL DA AREA ADMINISTRATIVA DAS ESCOLAS DO ENSINO FUNDAMENTAL - (FOLHA-B)'
                       when 'C' then 'EFETIVOS - F. DE PAGTO DO PESSOAL DA AREA ADMINISTRATIVA DAS CRECHES - (FOLHA-C)'
                       when 'D' then 'EFETIVOS - F. DE PAGTO DOS PROFESSORES DO ENSINO INFANTIL - (FOLHA-D)'
                       when 'E' then 'EFETIVOS - F. DE PAGTO DO PESSOAL DA UNIDADE ADMINISTRATIVA - (FOLHA-E 10%)'
                       when 'F' then 'EFETIVOS - F. DE PAGTO DOS PROFESSORES EM EDUCACAO DE JOVENS E ADULTOS (EJA) - (FOLHA-F)'
                       when 'G' then 'EFETIVOS - F. DE PAGTO DOS PROFESSORES DAS CRECHES - (FOLHA-G)'
                  end as descr_quebra,
                 ";
  $group_ordem = "
                  group by r70_estrut,
                           r70_descr,
                           rh01_clas1,
                           descr_quebra
                  order by rh01_clas1, r70_estrut
                 ";
}elseif($local == 9){
$select_campos = "r70_estrut,
                  r70_descr,
                  case when o40_orgao not in (8 , 9) 
                       then 1
                       else o40_orgao
                  end as quebra,
                  case o40_orgao 
                       when 8 then 'CARGOS EM COMISSAO DA EDUCACAO'
                       when 9 then 'CARGOS EM COMISSAO DA SAUDE'
                       else 'CARGOS EM COMISSAO DE OUTRAS SECRETARIAS'
                  end as descr_quebra,
                 ";
  $group_ordem = "
                  group by quebra,
                           descr_quebra,
                           r70_estrut,
                           r70_descr
                  order by quebra, r70_estrut
                 ";
}elseif($local == 5){
  if($dentista == 'D'){
    $where_dentista = " and rh01_funcao = 14 ";
    $head8 = "DENTISTAS";
  }elseif($dentista == 'O'){
    $where_dentista = " and rh01_funcao <> 14 ";
    $head8 = "OUTROS PROFISSIONAIS ";
  }else{
    $head8 = "TODOS ";
  }
}

$sql_basico = 
"
select  $select_campos
        count(distinct ".$sigla."_regist) as totfunc,
        round(sum(case when ".$sigla."_pd = 1 then ".$sigla."_valor else 0 end),2) as provento,
        round(sum(case when ".$sigla."_pd = 2 then ".$sigla."_valor else 0 end),2) as desconto,
        round(sum(case when ".$sigla."_pd = 1 then ".$sigla."_valor else ".$sigla."_valor *(-1) end),2) as liquido
 from ".$arquivo." 
      inner join rhrubricas     on rh27_rubric = ".$sigla."_rubric
      inner join rhpessoal      on ".$sigla."_regist  = rh01_regist 
      inner join cgm            on rh01_numcgm = z01_numcgm 
      inner join rhpessoalmov   on rh01_regist = rh02_regist 
                               and rh02_anousu = ".$sigla."_anousu 
                               and rh02_mesusu = ".$sigla."_mesusu 
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
      where ".$sigla."_anousu  = $ano and 
            ".$sigla."_mesusu  = $mes and 
            ".$sigla."_pd     != 3
            $where_banco
            $where_local
            $where_dentista
      $group_ordem 
";

$res_basico = pg_query($sql_basico);
//echo $sql_basico;
//db_criatabela($res_basico);exit;



$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfont('arial','b',8);
$pdf->setfillcolor(235);
$troca_pag = 1;
$troca     = 0;
$alt       = 4;
$total     = 0;
$total_quebra    = 0;
$tot_prov_quebra = 0;
$tot_desc_quebra = 0;
$tot_liq_quebra  = 0;
$tot_prov  = 0;
$tot_desc  = 0;
$tot_liq   = 0;
$pre = 0;
for($x = 0; $x < pg_numrows($res_basico);$x++){
   db_fieldsmemory($res_basico,$x);
   if($x == 0 && isset($quebra)){
     $troca = $quebra;
     $secr_ant = $quebra;
     $secre_descr_ant = $descr_quebra;
   }
   if(isset($quebra) ){  
     if($troca != $quebra ){
       $pdf->setfont('arial','b',7);
       $pdf->cell(120,$alt,"TOTAL  -->  ".$total_quebra,1,0,"C",$pre);
       $pdf->cell(20,$alt,db_formatar($tot_prov_quebra,'f'),1,0,"R",$pre);
       $pdf->cell(20,$alt,db_formatar($tot_desc_quebra,'f'),1,0,"R",$pre);
       $pdf->cell(20,$alt,db_formatar($tot_liq_quebra,'f'),1,1,"R",$pre);
       $pdf->ln(4);
    
       $secr_ant = $quebra;
       $secre_descr_ant = $descr_quebra;

       $total_quebra    = 0;
       $tot_prov_quebra = 0;
       $tot_desc_quebra = 0;
       $tot_liq_quebra  = 0;
       $troca           = $quebra;
       $troca_pag       = 1;
     }
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca_pag != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',7);
      $pdf->cell(120,$alt,'LOTAÇÃO',1,0,"C",1);
      $pdf->cell(20,$alt,'BRUTO',1,0,"C",1);
      $pdf->cell(20,$alt,'DESC.',1,0,"C",1);
      $pdf->cell(20,$alt,'LIQ.',1,1,"C",1);
      if(isset($quebra)){  
        $pdf->cell(60,$alt,$quebra." - ".$descr_quebra,0,1,"L",0);
      }
      $troca_pag = 0;
      $pre = 1;
      $pdf->ln(4);
   }
   $pre = 0;
   //if($pre == 1){
   //  $pre = 0;
   //}else{
   //  $pre = 1;
   //}
   $pdf->setfont('arial','',7);
   $pdf->cell(120,$alt,$r70_estrut.' - '.$r70_descr,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($provento,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($desconto,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($liquido,'f'),0,1,"R",$pre);
   //$pdf->ln(4);
   $total +=  $totfunc;
   $tot_prov += $provento;
   $tot_desc += $desconto;
   $tot_liq  += $liquido;
   $total_quebra    += $totfunc;
   $tot_prov_quebra += $provento;
   $tot_desc_quebra += $desconto;
   $tot_liq_quebra  += $liquido;
}
$pdf->setfont('arial','b',7);
if(isset($quebra)){  
  $pdf->setfont('arial','b',7);
  $pdf->cell(120,$alt,"TOTAL  -->  ".$total_quebra,1,0,"C",$pre);
  $pdf->cell(20,$alt,db_formatar($tot_prov_quebra,'f'),1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($tot_desc_quebra,'f'),1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($tot_liq_quebra,'f'),1,1,"R",$pre);
  $pdf->ln(4);
}
if(isset($quebra) ){  
  $pdf->addpage();
}
  $pdf->cell(120,$alt,'TOTAL GERAL --> '.$total,1,0,"C",$pre);
  $pdf->cell(20,$alt,db_formatar($tot_prov,'f'),1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($tot_desc,'f'),1,0,"R",$pre);
  $pdf->cell(20,$alt,db_formatar($tot_liq,'f'),1,1,"R",$pre);

$pdf->Output();
?>