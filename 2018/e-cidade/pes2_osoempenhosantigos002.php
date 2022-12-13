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


if ($folha == 'r14_'){
   $xarquivo = 'Salário';
   $arq = "gerfsal";
}elseif ($folha == 'r35_'){  
   $xarquivo = '13o Salário';
   $arq = "gerfs13";
}elseif ($folha == 'r48_'){  
   $xarquivo = 'Complementar';
   $arq = "gerfcom";
}

$head3 = "EMPENHOS DA FOLHA DE PAGAMENTO";
$head5 = "Período : ".$mes." / ".$ano;
$head7 = "Arquivo : ".$xarquivo;


$sql = "
select ".$folha."rubric as rubricas ,
       r06_descr, 
			 elemen,
			 r13_proati, 
			 proven ,
			 descon 
   from ( select ".$folha."rubric,
                rh27_descr as r06_descr,
              	coalesce(case rh30_vinculo when 'A' then rh27_elemen when 'I' then '339001010000' when 'P' then '339003010000' end,'000000000000') as elemen ,
                r13_proati, 
              	round(sum(case when ".$folha."pd = 1 then ".$folha."valor else 0 end),2) as proven,
              	round(sum(case when ".$folha."pd = 2 then ".$folha."valor else 0 end),2) as descon
           	from $arq inner join rhrubricas on rh27_rubric = ".$folha."rubric 
						                               and rh27_instit = ".$folha."instit  
                		  inner join rhpessoalmov on rh02_anousu = ".$folha."anousu 
											                       and rh02_mesusu = ".$folha."mesusu 
																			       and rh02_regist = ".$folha."regist
																						 and rh02_instit = ".$folha."instit
                      left join rhregime   on rh30_codreg    = rhpessoalmov.rh02_codreg
	                    inner join rhlotacao on r70_codigo = rh02_lota
											                    and r70_instit = rh02_instit
            where ".$folha."anousu = $ano 
						  and ".$folha."mesusu = $mes 
							and ".$folha."instit = ".db_getsession("DB_instit")."
							and (".$folha."pd != 3 and ".$folha."rubric <> 'R919') 
           	group by r13_proati,
						         rh27_elemen,
										 ".$folha."rubric,
										 rh27_descr,
										 rh30_vinculo 
          	order by ".$folha."rubric
				) as x 
   order by r13_proati,
	          elemen,
						".$folha."rubric,
						x.r06_descr;

";
//die($sql);
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if($xxnum == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não nenhum registro encontrado no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfont('arial','b',8);
$troca = 1;
$total = 0;
$alt = 4;
$ativ_elem = '';
$pdf->setfillcolor(235);
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'RUBR',1,0,"C",1);
      $pdf->cell(75,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(25,$alt,'PROVENTO',1,0,"C",1);
      $pdf->cell(25,$alt,'DESCONTO',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($ativ_elem != $r13_proati.$elemen){
     if($x != 0){
       $pdf->cell(90,$alt,'TOTAL','T',0,"L",0);
       $pdf->cell(25,$alt,db_formatar($total_elem_p,'f'),'T',0,"R",0);
       $pdf->cell(25,$alt,db_formatar($total_elem_d,'f'),'T',1,"R",0);
       $pdf->cell(90,$alt,'','T',0,"L",0);
       $pdf->cell(25,$alt,'LIQUIDO','T',0,"L",0);
       $pdf->cell(25,$alt,db_formatar($total_elem_p - $total_elem_d,'f'),'T',1,"R",0);
       $pdf->ln($alt);
     }
     //$pdf->addpage("L");
     $pdf->setfont('arial','b',8);
     $pdf->cell(0,$alt,$r13_proati.' - '.$elemen,0,1,"L",0);
     $ativ_elem = $r13_proati.$elemen;
     $total_elem_p = 0;
     $total_elem_d = 0;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }  
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$rubrica,0,0,"C",$pre);
   $pdf->cell(75,$alt,$r06_descr,0,0,"L",$pre);
   $pdf->cell(25,$alt,db_formatar($proven,'f'),0,0,"R",$pre);
   $pdf->cell(25,$alt,db_formatar($descon,'f'),0,1,"R",$pre);
   $total++;
   $total_elem_p += $proven;
   $total_elem_d += $descon;
}
$pdf->cell(90,$alt,'TOTAL','T',0,"L",0);
$pdf->cell(25,$alt,db_formatar($total_elem_p,'f'),'T',0,"R",0);
$pdf->cell(25,$alt,db_formatar($total_elem_d,'f'),'T',1,"R",0);
$pdf->cell(90,$alt,'','T',0,"L",0);
$pdf->cell(25,$alt,'LIQUIDO','T',0,"L",0);
$pdf->cell(25,$alt,db_formatar($total_elem_p - $total_elem_d,'f'),'T',1,"R",0);
$pdf->ln($alt);
//$pdf->setfont('arial','b',8);
//$pdf->cell(0,$alt,'TOTAL DE REGISTROS '.$total,"T",0,"R",0);
$pdf->Output();
?>