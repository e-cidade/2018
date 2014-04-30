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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$head3 = "RELATORIO - GASTOS DE 30%";
$head5 = "MES : ".$mes." / ".$ano;

$sql = "
select rh01_regist, 
       z01_nome,
       round( sum(case when r14_pd = 1 then r14_valor else 0 end),2) as proventos,
       round((sum(case when r14_pd = 1 then r14_valor else 0 end)*0.30),2) as perc_30,
       round( sum(case when r14_pd = 2 and r14_rubric <> '0758' then r14_valor else 0 end),2) as descontos,
       round( sum(case when r14_rubric = '0758' then r14_valor else 0 end),2) as telefone
from gerfsal 
     inner join rhpessoal    on rh01_regist = r14_regist
     inner join cgm          on rh01_numcgm = z01_numcgm 
     inner join rhpessoalmov on rh02_anousu = r14_anousu
                            and rh02_mesusu = r14_mesusu
                            and rh02_regist = r14_regist
                            and rh02_instit = r14_instit
     inner join rhregime     on rh30_codreg = rh02_codreg 
                            and rh30_instit = rh02_instit 
where r14_anousu = $ano
  and r14_mesusu = $mes
  and r14_instit = ".db_getsession('DB_instit')."
  and r14_rubric < 'R900' 
  and rh30_codreg not in (9, 17, 38, 42, 43) 
group by rh01_regist, z01_nome
order by z01_nome
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Códigos cadastrados no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,'MATRICULA',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME',1,0,"C",1);
      $pdf->cell(25,$alt,'PROVENTOS',1,0,"C",1);
      $pdf->cell(20,$alt,'30%',1,0,"C",1);
      $pdf->cell(25,$alt,'DESC. - TELEF',1,0,"C",1);
      $pdf->cell(25,$alt,'TELEFONE',1,1,"C",1);

      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$rh01_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(25,$alt,db_formatar($proventos,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($perc_30,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($descontos,'f'),0,0,"R",$pre);
   if($telefone+0 > $perc_30+0){
     $pdf->setfont('arial','b',8);
   }
   $pdf->cell(25,$alt,db_formatar($telefone,'f'),0,1,"R",$pre);
   $pdf->setfont('arial','',7);
   $total += 1;
//   $pdf->SetXY($pdf->lMargin,$pdf->gety() + $alt);
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS :  '.$total,"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>