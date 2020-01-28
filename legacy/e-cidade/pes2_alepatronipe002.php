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

$head3 = "RETALÓRIO DO IPE";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "
select r70_codigo,
       r70_descr,
       round(sum(r36_valorc),2) as base,
       round(sum(case when r14_rubric = '0595' then r36_valorc/100*6.8 else 0 end ),2) as seg_est,
       round(sum(case when r14_rubric = '0595' then r36_valorc/100*6.4 else 0 end ),2) as patron,
       round(sum(case when r14_rubric = '0695' then r36_valorc/100*13.2 else 0 end ),2) as seg_cc
from ipe
     inner join rhpessoal    on rh01_regist = r36_regist
     inner join rhpessoalmov on rh02_regist = rh01_regist
                            and rh02_anousu = r36_anousu
                            and rh02_mesusu = r36_mesusu
														and rh02_instit = r36_instit
     inner join rhlota       on r70_codigo  = rh02_lota
		                        and r70_instit  = rh02_instit
     inner join rhregime     on rh02_codreg = rh30_codreg
		                        and rh02_instit = rh30_instit
     left join gerfsal       on r14_anousu = r36_anousu 
                            and r14_mesusu = r36_mesusu 
		                        and r14_regist = r36_regist 
                            and r14_instit = rh02_instit
                            and r14_rubric in ('0595', '0695') 
where r36_anousu = $ano
  and r36_mesusu = $mes
	and r36_instit = ".db_getsession("DB_instit")."
group by
     r70_codigo,
     r70_descr
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Cálculo do IPE no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca    = 1;
$alt      = 4;
$t_base   = 0;
$t_patron = 0;
$t_seg_est= 0;
$t_seg_cc = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'LOTACAO',1,0,"C",1);
      $pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(25,$alt,'BASE',1,0,"C",1);
      $pdf->cell(25,$alt,'PATRONAL',1,0,"C",1);
      $pdf->cell(25,$alt,'ESTATUTARIO',1,0,"C",1);
      $pdf->cell(25,$alt,'CC/CLT',1,1,"C",1);
      $total = 0;
      $troca = 0;
      $pre   = 1; 
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,db_formatar($r70_codigo,'s',"0",4,"e",0),0,0,"C",$pre);
   $pdf->cell(60,$alt,$r70_descr,0,0,"L",$pre);
   $pdf->cell(25,$alt,db_formatar($base,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($patron,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($seg_est,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($seg_cc,'f'),0,1,"R",$pre);
   $t_base   += $base;
   $t_patron += $patron;
   $t_seg_est+= $seg_est;
   $t_seg_cc += $seg_cc;
}
$pdf->setfont('arial','b',8);
$pdf->cell(75,$alt,'TOTAL ',"T",0,"C",0);
$pdf->cell(25,$alt,db_formatar($t_base,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($t_patron,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($t_seg_est,'f'),"T",0,"R",0);
$pdf->cell(25,$alt,db_formatar($t_seg_cc,'f'),"T",1,"R",0);

$pdf->Output();
   
?>