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
if ($folha == 'r14'){
     $xarquivo = 'DE SALÁRIO';
     $arquivo = 'gerfsal';
}elseif ($folha == 'r20'){
     $xarquivo = 'DE RESCISÃO';
     $arquivo = 'gerfres';
}elseif ($folha == 'r35'){
     $xarquivo = 'DE 13o SALÁRIO';
     $arquivo = 'gerfs13';
}elseif ($folha == 'r22'){
     $xarquivo = 'DE ADIANTAMENTO';
     $arquivo = 'gerfadi';
}elseif ($folha == 'r48'){
     $xarquivo = 'COMPLEMENTAR';
     $arquivo = 'gerfcom';
}

$wherepes = " and ".$folha."_instit = ".db_getsession("DB_instit")." ";
if(isset($semest) && $semest != 0){
  $wherepes = " and r48_semest = ".$semest;
  $head6 = $xarquivo ." ($semest)";
}

if($vinc == 'a'){
  $dvinc = ' ATIVOS';
  $xvinc = " and rh30_vinculo = 'A' ";
}elseif($vinc == 'i'){
  $dvinc = ' INATIVOS';
  $xvinc = " and rh30_vinculo = 'I' ";
}elseif($vinc == 'p'){
  $dvinc = ' PENSIONISTAS';
  $xvinc = " and rh30_vinculo = 'P' " ;
}elseif($vinc == 'ip'){
  $dvinc = ' INATIVOS/PENSIONISTAS ';
  $xvinc = " and rh30_vinculo in ('P','I') ";
}else{
  $dvinc = ' GERAL';
}

if($ordem == 'a'){
  if($tipo == "L"){
    $xxordem = ' r70_descr, rh27_rubric ';   
  }else{
    $xxordem = ' rh27_descr ';
  }
}else{
  if($tipo == "L"){
    $xxordem = ' r70_estrut, rh27_descr';   
  }else{
    $xxordem = $folha.'_rubric';
  }
}

//$head9    = 'FUNCIONÁRIOS COM INSS';
//$wherepes = " and r01_tbprev = 1 and r01_tpvinc = 'A' ";

$head1 = "RESUMO DA FOLHA DE PAGAMENTO ";
$head3 = "ARQUIVO : ".$xarquivo;
$head5 = "PERÍODO : ".$mes." / ".$ano;
$head9 = "VINCULO : ".$dvinc;


if ($tipo == "G")
   $head7 = "RESUMO GERAL - LOTACOES : ".$lotaini." A ".$lotafin;
				    
if ($tipo == "L"){
   $sql = "select r70_estrut,
                  r70_descr,
                  x.lota,
	                x.".$folha."_rubric as r14_rubric,
                  case when rh23_rubric is null then '' else '*' end as emp,
            		  rh27_descr,
            		  x.".$folha."_pd as r14_pd,
             		  x.valor,
             		  x.soma,
             		  x.quant 
           from (select ".$folha."_lotac as lota,
                        ".$folha."_rubric,
                  			round(sum(".$folha."_valor),2) as valor,
                   			".$folha."_pd,count(".$folha."_rubric) as soma, 
                  			round(sum(".$folha."_quant),2) as quant  
                    from ".$arquivo." 
								    inner join rhpessoalmov on rh02_regist = ".$folha."_regist
			  	                        	       and rh02_anousu = ".$folha."_anousu
                         					         and rh02_mesusu = ".$folha."_mesusu
																					 and rh02_instit = ".$folha."_instit 
                    left join rhregime     on rh30_codreg  = rhpessoalmov.rh02_codreg
                                          and rh30_instit  = rhpessoalmov.rh02_instit   
                 where ".$folha."_anousu = $ano 
                   and ".$folha."_mesusu = $mes
                   and ".$folha."_pd    != 3' 
                   and to_number(".$folha."_lotac,'9999') >= $lotaini 
                   and to_number(".$folha."_lotac,'9999') <= $lotafin 
                   $wherepes
                   $xvinc
           		   group by ".$folha."_rubric,lota,".$folha."_pd) as x, 
           left join rhrubricas on rhtubricas.rh27_rubric = ".$folha."_rubric
                               and rhtubricas.rh27_instit = ".$folha."_instit
           left join rhrubelemento on rh23_rubric = rh27_rubric
                                  and rh23_instit = rh02_instit 
		       left join rhlota on r70_codigo = rh02_lota
                           and r70_instit = rh02_instit    
           order by $xxordem ";
}elseif ($tipo == "G"){
     $sql = "select x.".$folha."_rubric as r14_rubric,
                   case when rh23_rubric is null then '' else '*' end as emp,
                   rh27_descr,
            	     x.".$folha."_pd as r14_pd,
		               x.valor,
		               x.soma,
		               x.quant 
             from (select ".$folha."_rubric,
		                      round(sum(".$folha."_valor),2) as valor,
                          ".$folha."_pd,count(".$folha."_rubric) as soma, 
                          round(sum(".$folha."_quant),2) as quant  
                    from ".$arquivo." 
								    inner join rhpessoalmov on rh02_regist = ".$folha."_regist
			  	                        	       and rh02_anousu = ".$folha."_anousu
                         					         and rh02_mesusu = ".$folha."_mesusu
																					 and rh02_instit = ".$folha."_instit 
                    left join rhregime     on rh30_codreg  = rhpessoalmov.rh02_codreg
                                           on rh30_instit  = rhpessoalmov.rh02_instit
            	     where ".$folha."_anousu  = $ano 
                     and ".$folha."_mesusu  = $mes 
                     and ".$folha."_rubric != 3 
                     and to_number(".$folha."_lotac,9999) >= $lotaini 
                     and to_number(".$folha."_lotac,9999) <= $lotafin
                     $wherepes
                     $xvinc
         	  	     group by ".$folha."_rubric,".$folha."_pd) as x, rhrubricas 
             left join rhrubelemento on rh23_rubric = rh27_rubric
				     where x.".$folha."_rubric=rh27_rubric 
				     order by $xxordem ";
}


$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem lançamentos no período de '.$mes.' / '.$ano);

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->addpage();
$pdf->setfillcolor(235);
$baseprev = 0;
$baseirf  = 0;
$alt = 4;
$vencimentos = 0;
$descontos   = 0;
$empenho = 0;
$pdf->setfont('arial','b',8);
db_fieldsmemory($result,0);
//echo substr($r14_rubric,1,4) ;exit;
if ($tipo == "L"){
   $quebra = $lota;
   $pdf->cell(15,5,$r70_estrut." - ".$lota." - ".strtoupper($r13_descr),0,1,"L",0);
}

$pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
$pdf->cell(15,$alt,'N.FUNC.',1,0,"C",1);
$pdf->cell(15,$alt,'QUANT.',1,0,"C",1);
$pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
$pdf->cell(20,$alt,'PROVENTOS',1,0,"C",1);
$pdf->cell(20,$alt,'DESCONTOS',1,1,"C",1);

for($x = 0;$x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($tipo == "L" && $quebra != $lota){
      $pdf->cell(15,$alt,'',"T",0,"C",0);
      $pdf->cell(15,$alt,'',"T",0,"C",0);
      $pdf->cell(15,$alt,'',"T",0,"C",0);
      $pdf->cell(60,$alt,'',"T",0,"C",0);
      $pdf->cell(20,$alt,'',"T",0,"C",0);
      $pdf->cell(20,$alt,'',"T",1,"C",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'TOTAL',0,0,"L",0);
      $pdf->cell(20,$alt,db_formatar($vencimentos,'f'),0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($descontos,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'TOTAL LÍQUIDO ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($vencimentos - $descontos,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'N. FUNCIONÁRIOS ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $sqllota = "select count(distinct(".$folha."_regist)) 
                  from ".$arquivo."
        		      inner join rhpessoalmov on rh02_regist = ".$folha."_regist
 	 		   		                             and rh02_anousu = ".$folha."_anousu
                    		   		           and rh02_mesusu = ".$folha."_mesusu
																		     and rh02_instit = ".$folha."_instit 
                  left join rhregime      on rh30_codreg = rhpessoalmov.rh02_codreg
                                         and rh30_instit = rhpessoalmov.rh02_instit
               	  where to_number(".$folha."_lotac,9999) >= $lotaini and to_number(".$folha."_lotac,9999) <= $lotafin 
                        and ".$folha."_anousu = $ano 
                		    and ".$folha."_mesusu = $mes 
                		    and ".$folha."_lotac = '$quebra' 
                		    $wherepes 
                        $xvinc ";
      $resultlota = pg_exec($sqllota);
      db_fieldsmemory($resultlota,0);
      $pdf->cell(20,$alt,$count,0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'BASE PREVIDÊNCIA ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($baseprev,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'BASE I.R.R.F  ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($baseirf,'f'),0,1,"R",0);
      $pdf->cell(60,$alt,'',0,0,"C",0);
      $pdf->cell(45,$alt,'EMPENHOS  ',0,0,"L",0);
      $pdf->cell(20,$alt,'',0,0,"R",0);
      $pdf->cell(20,$alt,db_formatar($empenho,'f'),0,1,"R",0);
      $sqlprev = "select round(sum(prev1),2) as prev1,
                         round(sum(prev2),2) as prev2,
                  			 round(sum(prev3),2) as prev3,
                  			 round(sum(prev4),2) as prev4, 
                   			 round(sum(fgts),2) as fgts 
	                from (select ".$folha."_lotac,
     	                      		case when rh02_tbprev = 1 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev1,     		       case when r01_tbprev = 2 then ".$folha."_valor end as prev2,
                  		       		case when rh02_tbprev = 3 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev3,
     		                     		case when rh02_tbprev = 4 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev4, 
                   		       		case when ".$folha."_rubric = 'R991' then ".$folha."_valor end as fgts 
                        from ".$arquivo." 
         								    inner join rhpessoalmov on rh02_regist = ".$folha."_regist
			          	                        	       and rh02_anousu = ".$folha."_anousu
                                					         and rh02_mesusu = ".$folha."_mesusu
																									 and rh02_instit = ".$folha."_instit 
                            left join rhregime     on rh30_codreg  = rhpessoalmov.rh02_codreg
                                                  and rh30_instit  = rhpessoalmov.rh02_instit  
                    		where ".$folha."_anousu = $ano 
                         	and ".$folha."_mesusu = $mes
													and  ".$folha."_lotac = '$quebra'
                      		and ".$folha."_rubric in ('R992','R991')
                          $wherepes
                          $xvinc
            		       ) as x	";
		          // ver esta caso depois
			  //and ".$folha."_rubric in ('R990','R992') 
			  
      $resultprev = pg_exec($sqlprev);
//      db_criatabela($resultprev);
      db_fieldsmemory($resultprev,0);
      $pdf->ln(3);
      $pdf->cell(45,$alt,'BASE PREV.1   :'.db_formatar($prev1,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'BASE PREV.2   :'.db_formatar($prev2,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'BASE PREV.3   :'.db_formatar($prev3,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'BASE PREV.4   :'.db_formatar($prev4,'f'),0,1,"L",0);

      
      $vencimentos = 0;
      $descontos = 0;
      $empenho = 0;
      $baseprev = 0;
      $baseirf  = 0;
      $quebra = $lota;
      $pdf->sety(290);
   }
   if ($pdf->gety() > $pdf->h -30){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      if ($tipo == "L")
         $pdf->cell(15,5,$r70_estrut." - ".$lota." - ".strtoupper($r13_descr),0,1,"L",0);
      $pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
      $pdf->cell(15,$alt,'N.FUNC.',1,0,"C",1);
      $pdf->cell(15,$alt,'QUANT.',1,0,"C",1);
      $pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(20,$alt,'PROVENTOS',1,0,"C",1);
      $pdf->cell(20,$alt,'DESCONTOS',1,1,"C",1);
   }
   $pdf->setfont('arial','',8);
   if($r14_pd != 3 ){
     $pdf->cell(15,$alt,$emp.$r14_rubric,0,0,"R",0);
     $pdf->cell(15,$alt,$soma,0,0,"R",0);
     $pdf->cell(15,$alt,$quant,0,0,"R",0);
     $pdf->cell(60,$alt,$rh27_descr,0,0,"L",0);
     if ($r14_pd == 1){
        $pdf->cell(20,$alt,db_formatar($valor,'f'),0,0,"R",0);
        $pdf->cell(20,$alt,'',0,1,"R",0);
        $vencimentos += $valor;
     }else{
        $pdf->cell(20,$alt,'',0,0,"R",0);
        $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",0);
        $descontos += $valor;
     }
   }elseif($r14_rubric == 'R981'){
     $baseirf += $valor;
   }elseif($r14_rubric == 'R992'){
     $baseprev += $valor;
   }
   if($emp == '*' && $r14_pd != 3 ){
     if ($r14_pd == 1){
        $empenho += $valor;
     }else{
        $empenho -= $valor;
     }
   }



}
if ($tipo == "L"){
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(60,$alt,'',"T",0,"C",0);
   $pdf->cell(20,$alt,'',"T",0,"C",0);
   $pdf->cell(20,$alt,'',"T",1,"C",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'TOTAL',0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($vencimentos,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($descontos,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'TOTAL LÍQUIDO ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($vencimentos - $descontos,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'N. FUNCIONÁRIOS ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $sqllota = "select count(distinct(".$folha."_regist)) 
               from ".$arquivo." 
         			 inner join rhpessoalmov on rh02_regist = ".$folha."_regist
			                        	      and rh02_anousu = ".$folha."_anousu
                    					        and rh02_mesusu = ".$folha."_mesusu
																			and rh02_instit = ".$folha."_instit 
               left join rhregime     on rh30_codreg  = rhpessoalmov.rh02_codreg
                                     and rh30_instit  = rhpessoalmov.rh02_instit
               where to_number(".$folha."_lotac,9999) >= $lotaini 
                    	    and to_number(".$folha."_lotac,9999) <= $lotafin 
                    	    and ".$folha."_anousu = $ano 
                    	    and ".$folha."_mesusu = $mes 
                    	    and ".$folha."_lotac = '$quebra'
                    	    $wherepes  
                          $xvinc ";
   $resultlota = pg_exec($sqllota);
   db_fieldsmemory($resultlota,0);
   $pdf->cell(20,$alt,$count,0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'BASE PREVIDÊNCIA ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($baseprev,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'BASE I.R.R.F  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($baseirf,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'EMPENHOS  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($empenho,'f'),0,1,"R",0);
   $sqlprev = "select round(sum(prev1),2) as prev1,
                      round(sum(prev2),2) as prev2,
     		              round(sum(prev3),2) as prev3,
     		              round(sum(prev4),2) as prev4,
            		      round(sum(fgts),2) as fgts
		 
               from (select ".$folha."_lotac,
     	                      case when rh02_tbprev = 1 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev1,     		       case when r01_tbprev = 2 then ".$folha."_valor end as prev2,
                  		      case when rh02_tbprev = 3 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev3,
     		                    case when rh02_tbprev = 4 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev4, 
     		                    case when ".$folha."_rubric = 'R991' then ".$folha."_valor end as fgts 
                     from ".$arquivo." 
              			 inner join rhpessoalmov on rh02_regist = ".$folha."_regist
		      	                        	      and rh02_anousu = ".$folha."_anousu
                          					        and rh02_mesusu = ".$folha."_mesusu
																						and rh02_instit = ".$folha."_instit 
                     left join rhregime     on rh30_codreg  = rhpessoalmov.rh02_codreg
                                           and rh30_instit  = rhpessoalmov.rh02_instit 
                  	 where ".$folha."_anousu = $ano 
                  		 and ".$folha."_mesusu = $mes 
                       and ".$folha."_lotac  = '$quebra' 
											 and ".$folha."_rubric in ('R992','R991')
                       $wherepes
                       $xvinc
		                ) as x 	";
		          // ver esta caso depois
			  //and ".$folha."_rubric in ('R990','R992') 
   $resultprev = pg_exec($sqlprev);
   db_fieldsmemory($resultprev,0);
   $pdf->ln(3);
   $pdf->cell(45,$alt,'BASE PREV.1   :'.db_formatar($prev1,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV.2   :'.db_formatar($prev2,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV 3   :'.db_formatar($prev3,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV 4   :'.db_formatar($prev4,'f'),0,1,"L",0);
   $vencimentos = 0;
   $descontos = 0;
   $baseprev = 0;
   $baseirf  = 0;
   $quebra = $lota;
   $pdf->sety(290);
}else{
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(15,$alt,'',"T",0,"C",0);
   $pdf->cell(60,$alt,'',"T",0,"C",0);
   $pdf->cell(20,$alt,'',"T",0,"C",0);
   $pdf->cell(20,$alt,'',"T",1,"C",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'TOTAL',0,0,"L",0);
   $pdf->cell(20,$alt,db_formatar($vencimentos,'f'),0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($descontos,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'TOTAL LÍQUIDO ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($vencimentos - $descontos,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'N. FUNCIONÁRIOS ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $sqllota = "select count(distinct(".$folha."_regist)) 
                     from ".$arquivo." 
              			 inner join rhpessoalmov on rh02_regist = ".$folha."_regist
		      	                        	      and rh02_anousu = ".$folha."_anousu
                          					        and rh02_mesusu = ".$folha."_mesusu
																						and rh02_instit = ".$folha."_instit 
                     left join rhregime     on rh30_codreg  = rhpessoalmov.rh02_codreg
                                           and rh30_instit  = rhpessoalmov.rh02_instit   
       	       where to_number(".$folha."_lotac,9999) >= $lotaini and to_number(".$folha."_lotac,9999) <= $lotafin 
                     and ".$folha."_anousu = $ano
                		 and ".$folha."_mesusu = $mes
                		 $wherepes 
                     $xvinc ";
   $resultlota = pg_exec($sqllota);
   db_fieldsmemory($resultlota,0);
   $pdf->cell(20,$alt,$count,0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'BASE PREVIDÊNCIA ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($baseprev,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'BASE I.R.R.F  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($baseirf,'f'),0,1,"R",0);
   $pdf->cell(60,$alt,'',0,0,"C",0);
   $pdf->cell(45,$alt,'EMPENHOS  ',0,0,"L",0);
   $pdf->cell(20,$alt,'',0,0,"R",0);
   $pdf->cell(20,$alt,db_formatar($empenho,'f'),0,1,"R",0);
   $sqlprev = "select round(sum(prev1),2) as prev1,
                      round(sum(prev2),2) as prev2,
                      round(sum(prev3),2) as prev3,
     		              round(sum(prev4),2) as prev4, 
     		              round(sum(fgts),2) as fgts 
               from (select ".$folha."_lotac,
                           case when rh02_tbprev = 1 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev1,
                		       case when rh02_tbprev = 2 then ".$folha."_valor end as prev2,
     		                   case when rh02_tbprev = 3 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev3,
                 		       case when rh02_tbprev = 4 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev4, 
     		                   case when ".$folha."_rubric = 'R991' then ".$folha."_valor end as fgts 
                     from ".$arquivo." 
              			 inner join rhpessoalmov on rh02_regist = ".$folha."_regist
		      	                        	      and rh02_anousu = ".$folha."_anousu
                          					        and rh02_mesusu = ".$folha."_mesusu
																						and rh02_instit = ".$folha."_instit 
                     left join rhregime     on rh30_codreg    = rhpessoalmov.rh02_codreg
                                           and rh30_instit    = rhpessoalmov.rh02_instit 
                  	 where ".$folha."_anousu = $ano 
                   	   and ".$folha."_mesusu = $mes 
                       and to_number(".$folha."_lotac,9999) >= $lotaini and to_number(".$folha."_lotac,9999) <= $lotafin
     		               and ".$folha."_rubric in ('R992','R991')
                       $wherepes
                       $xvinc
              		  ) as x	"; 
	          // ver esta caso depois
     		  //and ".$folha."_rubric in ('R990','R992')
   $resultprev = pg_exec($sqlprev);
   db_fieldsmemory($resultprev,0);
   $pdf->ln(3);
   $pdf->cell(45,$alt,'BASE PREV.1   :'.db_formatar($prev1,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV.2   :'.db_formatar($prev2,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV.3   :'.db_formatar($prev3,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV.4   :'.db_formatar($prev4,'f'),0,1,"L",0);
   
   $pdf->cell(45,$alt,'BASE F.G.T.S. :'.db_formatar($fgts,'f'),0,0,"L",0);

}
$pdf->Output();
?>