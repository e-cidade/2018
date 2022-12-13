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


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;
$ano = 2009;
$mes = 06;


$head3 = "CADASTRO DE CÓDIGOS";
$head5 = "PERÍODO : ".$mes." / ".$ano;

$sql = "

select rh27_rubric,
       rh27_descr,
       rh23_codele,
       o56_elemento,
       o56_descr
from 
(
select rh27_rubric,
       rh27_descr,
       rh23_codele
from rhrubricas 
           inner join rhrubelemento on rh27_rubric = rh23_rubric
                                   and rh23_instit = ".db_getsession('DB_instit')."
where rh27_instit = ".db_getsession('DB_instit')."
union


select distinct rh27_rubric,
                rh27_descr,
	        rh28_codelenov  
      from rhrubricas 
           inner join rhrubelemento on rh27_rubric=rh23_rubric 
                                   and rh23_instit = ".db_getsession('DB_instit')."
	   inner join rhlotavincele on rh23_codele = rh28_codeledef
where rh27_instit = ".db_getsession('DB_instit')."
) as x
inner join orcelemento on rh23_codele = o56_codele
where o56_anousu = $ano
order by rh27_rubric
       ";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Rubricas configuradas para empenho.');

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$rub = '';

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(100,$alt,'ELEMENTO',1,1,"C",1);
      $total = 0;
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   if($rub != $rh27_rubric){
     $rub = $rh27_rubric;
     $pdf->ln(3);
     $pdf->cell(15,$alt,$rh27_rubric,0,0,"C",0);
     $pdf->cell(80,$alt,$rh27_descr,0,0,"L",0);
   }else{
     $pdf->cell(15,$alt,'',0,"C",0);
     $pdf->cell(80,$alt,'',0,0,"L",0);
   }
   $pdf->cell(100,$alt,$o56_elemento.' - '.$o56_descr,0,1,"L",0);
}
//$pdf->setfont('arial','b',8);
//$pdf->cell(80,$alt,'TOTAL DO BANCO',"T",0,"C",0);
//$pdf->cell(20,$alt,'',"T",0,"C",0);
//$pdf->cell(30,$alt,db_formatar($total,'f'),"T",1,"R",0);

$pdf->Output();
   
?>