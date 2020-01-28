<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
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

$clrotulo = new rotulocampo;
$clrotulo->label('r06_codigo');
$clrotulo->label('r06_descr');
$clrotulo->label('r06_elemen');
$clrotulo->label('r06_pd');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$ano = 2005;
$mes = 7;


$head3 = "FUNPAS INATIVOS";
$head5 = "PERIODO : 01/2004 A 02/2006";

$sql = "
select r14_anousu ,r14_mesusu,soma,
       round(sum(r985),2) as r985,
       round(sum(r904),2) as r904
from
  (
  select r14_anousu,r14_mesusu,count(*) as soma ,
         sum(case when r14_rubric = 'R985' then r14_valor else 0 end) as R985,
         sum(case when r14_rubric in ('R904','R907') then r14_valor else 0 end) as R904
  from gerfsal
       inner join pessoal on r01_regist = r14_regist and r01_anousu = 2006
                          and r01_mesusu = 3 
						  and r01_tpvinc = 'I'
						  and to_number(r01_lotac,'9999') < 2200
  where r14_anousu >= 2004  and r14_rubric in ('R985','R904','R907')
  group by r14_anousu,r14_mesusu

  union

  select r48_anousu,r48_mesusu,0,
         sum(case when r48_rubric = 'R985' then r48_valor else 0 end) as R985,
         sum(case when r48_rubric in ('R904','R907') then r48_valor else 0 end) as R904
  from gerfcom
       inner join pessoal on r01_regist = r48_regist
                          and r01_anousu = 2006
                          and r01_mesusu = 3
						  and r01_tpvinc = 'I'
						  and to_number(r01_lotac,'9999') < 2200
  where r48_anousu >= 2004
    and r48_rubric in ('R985','R904','R907')
  group by r48_anousu,r48_mesusu

  union

  select r35_anousu,13 as r35_mesusu,count(*),
         sum(case when r35_rubric = 'R986' then r35_valor else 0 end) as R985,
         sum(case when r35_rubric in ('R905','R908') then r35_valor else 0 end) as R904
  from gerfs13
       inner join pessoal on r01_regist = r35_regist
                          and r01_anousu = 2006
                          and r01_mesusu = 3
						  and r01_tpvinc = 'I'
						  and to_number(r01_lotac,'9999') < 2200
  where r35_anousu >= 2004
    and r35_rubric in ('R986','R905','R908')
  group by r35_anousu,r35_mesusu
  ) as x
  group by r14_anousu,r14_mesusu,soma

";
//echo $sql ; exit;

$result = pg_exec($sql);
db_criatabela($result);exit;
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
$t_r985 = 0;
$t_r904 = 0;
$pre    = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(10,$alt,'ANO',1,0,"C",1);
      $pdf->cell(10,$alt,'MES',1,0,"C",1);
      $pdf->cell(25,$alt,'BASE',1,0,"C",1);
      $pdf->cell(25,$alt,'DESCONTO',1,1,"C",1);
      $total = 0;
      $troca = 0;
	  $pre = 1;
   }
   if($pre == 1)
	 $pre = 0;
   else
	 $pre = 1;
   $pdf->setfont('arial','',7);
   $pdf->cell(10,$alt,$r14_anousu,0,0,"C",$pre);
   $pdf->cell(10,$alt,$r14_mesusu,0,0,"C",$pre);
   $pdf->cell(25,$alt,db_formatar($r985,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($r904,'f'),0,1,"R",$pre);
   $t_r985 += $r985;
   $t_r904 += $r904;
}
   if($pre == 1)
	 $pre = 0;
   else
	 $pre = 1;
$pdf->setfont('arial','b',8);
$pdf->cell(20,$alt,'TOTAL ',"T",0,"C",$pre);
$pdf->cell(25,$alt,db_formatar($t_r985,'f'),"T",0,"R",$pre);
$pdf->cell(25,$alt,db_formatar($t_r904,'f'),"T",1,"R",$pre);

$pdf->Output();
   
?>