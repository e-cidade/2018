<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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


$sql_prev = "select r33_ppatro,r33_nome from inssirf 
                 where r33_anousu = $ano 
								   and r33_mesusu = $mes 
									 and r33_instit = ".db_getsession("DB_instit")."
									 and r33_codtab = $prev+2 limit 1;";
$res_prev = pg_query($sql_prev);
db_fieldsmemory($res_prev,0);

$head3 = "RELATÓRIO ".strtoupper($r33_nome);
$head4 = "PERÍODO  : ".$mes." / ".$ano;

if($ordem == 'A'){
  $xordem = ' order by z01_nome';
}else{
  $xordem = ' order by r14_regist';
}

if($sembase == 'S'){
  $xsembase = '';
}else{
  $xsembase = ' and base > 0 ';
}
//echo '<br> vinculo --> '.$vinculo;
if($vinculo == 'A'){
  $where_vinc = " and rh30_vinculo = 'A'";
  $head7 = 'VINCULO : ATIVOS';
}elseif($vinculo == 'I'){
  $where_vinc = " and rh30_vinculo = 'I'";
  $head7 = 'VINCULO : INATIVOS';
}elseif($vinculo == 'P'){
  $where_vinc = " and rh30_vinculo = 'P'";
  $head7 = 'VINCULO : PENSIONISTAS';
}elseif($vinculo == 'IP'){
  $where_vinc = " and rh30_vinculo <> 'A'";
  $head7 = 'VINCULO : INATIVOS/PENSIONISTAS';
}else{
  $where_vinc = '';
  $head7 = 'VINCULO : TODOS';
}


$rubric = 'R993';
$arquivo = '';
if($folha == 'r14'){
  $head5 = "PATRONAL : ".$r33_ppatro."% - SALÁRIO";
  $arquivo = 'gerfsal';
}elseif($folha == 'r35'){
  $head5 = "PATRONAL : ".$r33_ppatro."% - 13o. SALÁRIO";
  $arquivo = 'gerfs13';
}elseif($folha == 'r48'){
  $head5 = "PATRONAL : ".$r33_ppatro."% - COMPLEMENTAR";
  $arquivo = 'gerfcom';
}elseif($folha == 'r20'){
  $head5 = "PATRONAL : ".$r33_ppatro."% - RESCISÃO";
  $arquivo = 'gerfres';
}

$instit = db_getsession('DB_instit');

   $sql = "
 
   select z01_nome,
          r14_regist,
          round(proventos,2) as proventos, 
          round(base,2) as base, 
          round(segurado,2) as segurado ,
          round(base/100*$r33_ppatro,2) as patronal, 
          round((base/100*$r33_ppatro) + segurado,2) as total
   from 
   (
   select ".$folha."_regist as r14_regist,
           sum( case when ".$folha."_pd = 1                            then ".$folha."_valor else 0 end ) as proventos,
           sum( case when ".$folha."_rubric = 'R992'                   then ".$folha."_valor else 0 end ) as base,
           sum( case when ".$folha."_rubric = '$rubric'                then ".$folha."_valor else 0 end ) as segurado
   from    ".$arquivo."
   where   ".$folha."_anousu = $ano
   and     ".$folha."_mesusu = $mes
   and     ".$folha."_instit = $instit
   group by ".$folha."_regist) as x
   	inner join rhpessoal on r14_regist = rh01_regist
   	inner join rhpessoalmov on rh02_anousu = $ano
   	                       and rh02_mesusu = $mes
   			                   and rh02_regist = rh01_regist
                           and rh02_instit = $instit
   			                   and rh02_tbprev = $prev
   	inner join rhregime     on rh02_codreg = rh30_codreg
                           and rh30_instit = $instit
   	inner join cgm on z01_numcgm = rh01_numcgm
   where 1=1 $xsembase
   $where_vinc
   $xordem 
 
         ";
//  echo $sql ; exit;

  $result = pg_exec($sql);
  $xxnum = pg_numrows($result);
  if ($xxnum == 0){
     db_redireciona('db_erros.php?fechar=true&db_erro=No existem clculos para o perodo de '.$mes.' / '.$ano);
  
  }
  
  $pdf = new PDF(); 
  $pdf->Open(); 
  $pdf->AliasNbPages(); 
  $total = 0;
  $pdf->setfillcolor(235);
  $pdf->setfont('arial','b',8);
  $troca      = 1;
  $alt        = 4;
  $total_fun  = 0;
  $total_prov = 0;
  $total_seg  = 0;
  $total_base = 0;
  $total_patro= 0;
  $total_total= 0;
  
  for($x = 0; $x < pg_numrows($result);$x++){
     db_fieldsmemory($result,$x);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
        $pdf->addpage();
        $pdf->setfont('arial','b',8);
        $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
        $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
        $pdf->cell(20,$alt,'BRUTO',1,0,"C",1);
        $pdf->cell(20,$alt,'BASE',1,0,"C",1);
        $pdf->cell(20,$alt,'SEGURADO',1,0,"C",1);
        $pdf->cell(20,$alt,'PATRON',1,0,"C",1);
        $pdf->cell(20,$alt,'TOTAL',1,1,"C",1);
        $troca = 0;
        $pre = 1;
     }
     if($pre == 1){
       $pre = 0;
     }else{
       $pre = 1;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(15,$alt,$r14_regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
     $pdf->cell(20,$alt,db_formatar($proventos,'f'),0,0,"R",$pre);
     $pdf->cell(20,$alt,db_formatar($base,'f'),0,0,"R",$pre);
     $pdf->cell(20,$alt,db_formatar($segurado,'f'),0,0,"R",$pre);
     $pdf->cell(20,$alt,db_formatar($patronal,'f'),0,0,"R",$pre);
     $pdf->cell(20,$alt,db_formatar($total,'f'),0,1,"R",$pre);
     $total_fun   += 1;
     $total_prov  += $proventos;
     $total_base  += $base ;
     $total_seg   += $segurado ;
     $total_patro += $patronal;
     $total_total += $total;
  }
  $pdf->setfont('arial','b',8);
  $pdf->cell(75,$alt,'TOTAL :  '.$total_fun.' FUNCIONÁRIOS ',"T",0,"C",0);
  $pdf->cell(20,$alt,db_formatar($total_prov,'f'),"T",0,"C",0);
  $pdf->cell(20,$alt,db_formatar($total_base,'f'),"T",0,"C",0);
  $pdf->cell(20,$alt,db_formatar($total_seg,'f'),"T",0,"C",0);
  $pdf->cell(20,$alt,db_formatar($total_patro,'f'),"T",0,"C",0);
  $pdf->cell(20,$alt,db_formatar($total_total,'f'),"T",1,"C",0);
  $pdf->Output();
     
  ?>