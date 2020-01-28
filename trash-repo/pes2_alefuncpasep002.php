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

//$ano = 2006;
//$mes = 6;


$head2 = "PERÍODO : ".$mes." / ".$ano;
$head3 = "RELACAO DE FUNCIONÁRIO COM PASEP";
$head5 = "POR LOTAÇÃO E POR RECURSO";


$sql_orgao = "

select r70_estrut as o40_orgao,
       r70_descr  as o40_descr,
			 count(distinct r14_regist) 
from gerfsal 
     inner join rhpessoalmov on rh02_anousu = r14_anousu 
                            and rh02_mesusu = r14_mesusu
														and rh02_regist = r14_regist
							        			and rh02_instit = r14_instit
     left join rhregime      on rh30_codreg = rhpessoalmov.rh02_codreg 
		                        and rh30_instit = r14_instit
     inner join rhlota on r70_codigo = rh02_lota
		                  and r70_instit = r14_instit
where r14_anousu = $ano 
  and r14_mesusu = $mes 
	and r14_instit = ".db_getsession("DB_instit")."
	and rh30_vinculo = 'A' 
	and rh30_regime < 3
group by r70_estrut,
         r70_descr " ;

$sql_recurso = "
select o15_codigo, 
       o15_descr,
			 count(distinct r14_regist) 
from gerfsal 
     inner join rhpessoalmov on rh02_anousu = r14_anousu 
                            and rh02_mesusu = r14_mesusu
														and rh02_regist = r14_regist
							        			and rh02_instit = r14_instit
     left join rhregime      on rh30_codreg = rhpessoalmov.rh02_codreg 
		                        and rh30_instit = r14_instit
     inner join rhlota on r70_codigo = rh02_lota
		                  and r70_instit = r14_instit
		 inner join (select distinct rh25_codigo, rh25_recurso from rhlotavinc where rh25_anousu = $ano) as rhlotavinc on rh25_codigo = rh02_lota
     left join orctiporec on o15_codigo = rh25_recurso
where r14_anousu = $ano 
  and r14_mesusu = $mes 
	and r14_instit = ".db_getsession("DB_instit")."
	and rh30_vinculo = 'A' 
	and rh30_regime < 3

group by o15_codigo, o15_descr";

$result_orgao   = pg_exec($sql_orgao);
$result_recurso = pg_exec($sql_recurso);

$num_orgao      = pg_numrows($result_orgao);
$num_recurso    = pg_numrows($result_recurso);

//echo "<br> orgao $num_orgao   ".$sql_orgao;
//echo "<br> recurso $num_recurso  ".$sql_recurso;exit;

if($num_orgao == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfont('arial','b',8);

////// pasep por orgao

$troca = 1;
$total = 0;
$alt = 4;
$xsec = 0;
$pdf->setfillcolor(235);
for($x = 0; $x < $num_orgao;$x++){
   db_fieldsmemory($result_orgao,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'LOTAÇÃO',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCRICAO',1,0,"C",1);
      $pdf->cell(15,$alt,'QUANT.',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$o40_orgao,0,0,"C",$pre);
   $pdf->cell(80,$alt,$o40_descr,0,0,"L",$pre);
   $pdf->cell(15,$alt,$count,0,1,"R",$pre);
   $total += $count;
}
$pdf->setfont('arial','b',8);
$pdf->cell(95,$alt,'TOTAL DE REGISTROS ',"T",0,"R",0);
$pdf->cell(15,$alt,$total,"T",1,"R",0);

//// pasep por recurso

$troca = 1;
$total = 0;
$alt = 4;
$xsec = 0;
$pdf->setfillcolor(235);
for($x = 0; $x < $num_recurso;$x++){
   db_fieldsmemory($result_recurso,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'RECURSO',1,0,"C",1);
      $pdf->cell(80,$alt,'DESCRICAO',1,0,"C",1);
      $pdf->cell(15,$alt,'QUANT.',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$o15_codigo,0,0,"C",$pre);
   $pdf->cell(80,$alt,$o15_descr,0,0,"L",$pre);
   $pdf->cell(15,$alt,$count,0,1,"R",$pre);
   $total += $count;
}
$pdf->setfont('arial','b',8);
$pdf->cell(95,$alt,'TOTAL DE REGISTROS ',"T",0,"R",0);
$pdf->cell(15,$alt,$total,"T",1,"R",0);
$pdf->Output();
?>