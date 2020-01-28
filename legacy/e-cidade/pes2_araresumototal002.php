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
$tipo = 't';
$lotaini = 0;
$lotafin = 999999;
if($tipoarq == 's'){
  $arquivo = 'gerfsal';
  $folha   = 'r14';
}elseif($tipoarq == 'd'){
  $arquivo = 'gerfs13';
  $folha   = 'r35';
}elseif($tipoarq == 'c'){
  $arquivo = 'gerfcom';
  $folha   = 'r48';
}


$head2 = 'RESUMO DA FOLHA DE PAGAMENTO';
$head4 = "PERIODO : ".$mes." / ".$ano;

$where_banco = '';

$head6 = "TODOS OS BANCOS E COM E SEM CONTA ";

if($xbanco == 'b' && $xconta == 'cc'){
  $head6 = "BANCO DO BRASIL - COM CONTA ";
  $where_banco = " and trim(rh44_codban) = '001' and rh02_fpagto = 3 ";
}elseif($xbanco == 'c' && $xconta == 'cc'){
  $head6 = "CAIXA ECONOMICA FEDERAL - COM CONTA ";
  $where_banco = " and trim(rh44_codban) = '104' and rh02_fpagto = 3 ";
}elseif($xconta == 'sc'){
  $head6 = "SEM CONTA";
  $where_banco = " and rh02_fpagto <> 3 ";
}


$where_local = '';
if(trim($local) != ''){
   $where_local = " and rh56_localtrab in ($local)";
}
$where_dentista = " ";

$select_campos  = "
                   rh55_estrut as r70_estrut,
                   rh55_descr  as r70_descr
                  ";
$select_campos1 = "rh56_localtrab as lota";

$inner_local    = "
     	           inner join rhlocaltrab  on rh55_codigo = lota
     	                                  and rh55_instit = rh27_instit 
                  ";

$xand = "rh56_localtrab";


if($local == '1'){
  $where_local = " and rh56_localtrab in ($local)";
  if($inapen == 'I'){
    $where_local .= " and rh02_lota = 71";
    $head8 = "INATIVOS";
  }elseif($inapen == 'P'){
    $where_local .= " and rh02_lota = 72";
    $head8 = "PENSIONISTAS";
  }elseif($inapen == 'M'){
    $where_local .= " and rh02_lota = 74";
    $head8 = "SALARIO MATERNIDADE";
  }elseif($inapen == 'D'){
    $where_local .= " and rh02_lota = 73";
    $head8 = "AUXILIO DOENCA";
  }
}elseif($local == '3'){
   $where_local = " and rh56_localtrab in ($local)";
   $xand = "rh01_clas1 ";
   if($tip_fol != 'T'){
     $where_local .= " and trim(rh01_clas1) = '$tip_fol'";
   }
$select_campos = "
                  lota as r70_estrut,
                  case lota
                       when 'A' then 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES E PESSOAL EM ATIVIDADE PEDAGOGICAS DO ENSINO FUNDAMENTAL - (FOLHA-A)'
                       when 'B' then 'EFETIVOS - FOLHA DE PAGAMENTO DO PESSOAL DA AREA ADMINISTRATIVA DAS ESCOLAS DO ENSINO FUNDAMENTAL - (FOLHA-B)'
                       when 'C' then 'EFETIVOS - FOLHA DE PAGAMENTO DO PESSOAL DA AREA ADMINISTRATIVA DAS CRECHES - (FOLHA-C)'
                       when 'D' then 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES DO ENSINO INFANTIL - (FOLHA-D)'
                       when 'E' then 'EFETIVOS - FOLHA DE PAGAMENTO DO PESSOAL DA UNIDADE ADMINISTRATIVA - (FOLHA-E 10%)'
                       when 'F' then 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES EM EDUCACAO DE JOVENS E ADULTOS (EJA) - (FOLHA-F)'
                       when 'G' then 'EFETIVOS - FOLHA DE PAGAMENTO DOS PROFESSORES DAS CRECHES - (FOLHA-G)'
                  end as r70_descr
                 ";
$select_campos1= "
                  rh01_clas1 as lota
                 ";
$inner_local    = "";

}elseif($local == '9'){
  $where_local = " and rh56_localtrab in ($local)";
  if($comissionados == 'E'){
    $where_local .= " and substr(r70_estrut,1,2) = '08'";
    $head8 = "CARGOS EM COMISSAO DA EDUCACAO";
  }elseif($comissionados == 'S'){
    $where_local .= " and substr(r70_estrut,1,2) = '09'";
    $head8 = "CARGOS EM COMISSAO DA SAUDE";
  }elseif($comissionados == 'D'){
    $where_local .= " and substr(r70_estrut,1,2) not in ('09' , '08') ";
    $head8 = "CARGOS EM COMISSAO DE OUTRAS SECRETARIAS";
  }
}elseif($local == '5'){
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



$sql = "select 
               ".$select_campos.",
               x.lota,
               x.".$folha."_rubric as r14_rubric,
               case when rh23_rubric is null then ''
                    else '*' end as emp,
               rh27_descr,
               
               x.".$folha."_pd as r14_pd,
               x.valor,
               x.soma,
               x.quant 
        from (select ".$select_campos1.",
                     ".$folha."_rubric,
                     round(sum(".$folha."_valor),2) as valor,
                     ".$folha."_pd,count(".$folha."_rubric) as soma, 
                     round(sum(".$folha."_quant),2) as quant  
              from ".$arquivo."
                   inner join rhpessoal      on rh01_regist = ".$folha."_regist
                   inner join rhpessoalmov   on rh02_anousu = ".$folha."_anousu
                                            and rh02_mesusu = ".$folha."_mesusu
                                            and rh02_regist = ".$folha."_regist
     		                                    and rh02_instit = ".$folha."_instit
     	             inner join rhregime       on rh02_codreg = rh30_codreg
     	                                      and rh30_instit = rh02_instit 
     	             inner join rhlota         on r70_codigo  = rh02_lota  
     	                                      and r70_instit  = rh02_instit 
                   left  join rhpesbanco     on rh44_seqpes = rh02_seqpes  
                   left  join rhpeslocaltrab on rh56_seqpes = rh02_seqpes  
     		                                    and rh56_princ = 't'
                   left  join rhlocaltrab    on rh55_codigo = rh56_localtrab
     	                                    and rh55_instit = ".$folha."_instit 
              where ".$folha."_anousu = $ano 
                and ".$folha."_mesusu = $mes
     		        and ".$folha."_instit = ".db_getsession("DB_instit")."
                $where_dentista
                $where_local
                $where_banco
              group by ".$folha."_rubric,lota,".$folha."_pd
             )as x
     	       inner join rhrubricas   on rh27_rubric = x.".$folha."_rubric 
     	                              and rh27_instit = ".db_getsession("DB_instit")."
               $inner_local
               left join rhrubelemento on rh23_rubric = rh27_rubric
     	                              and rh23_instit = rh27_instit 
        order by lota , ".$folha."_rubric ";

//echo "Local --> $local  Tip_fol --> $tip_fol   ".$sql;exit;
$result = pg_exec($sql);
//db_criatabela($result);
$xxnum = pg_numrows($result);
//echo 'ENTROU 111';exit;
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem lançamentos no período de '.$mes.' / '.$ano.$erroajuda.".");

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

$pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
$pdf->cell(15,$alt,'N.FUNC.',1,0,"C",1);
$pdf->cell(15,$alt,'QUANT.',1,0,"C",1);
$pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
$pdf->cell(20,$alt,'PROVENTOS',1,0,"C",1);
$pdf->cell(20,$alt,'DESCONTOS',1,1,"C",1);


if ($tipo == "l" || $tipo == "o" || $tipo == "s" || $tipo == "t"){
   $quebra = $lota;
   if($tipo == "s"){
     if(empty($quebra)){
       $quebra = 0;
     }
   }  
   $pdf->multicell(0,5,$lota." - ".strtoupper($r70_descr));
   $pdf->ln(4);
}
for($x = 0;$x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if (($tipo == "l" || $tipo == "o" || $tipo == "s" || $tipo == "t") && $quebra != $lota){
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
                   inner join rhpessoal      on rh01_regist = ".$folha."_regist
                   inner join rhpessoalmov   on rh02_anousu = ".$folha."_anousu
                                            and rh02_mesusu = ".$folha."_mesusu
                                            and rh02_regist = ".$folha."_regist
     		                                    and rh02_instit = ".$folha."_instit
     	             inner join rhregime       on rh02_codreg = rh30_codreg
     	                                      and rh30_instit = rh02_instit 
     	             inner join rhlota         on r70_codigo  = rh02_lota  
     	                                      and r70_instit  = rh02_instit 
                   left  join rhpesbanco     on rh44_seqpes = rh02_seqpes  
                   left  join rhpeslocaltrab on rh56_seqpes = rh02_seqpes  
     		                                    and rh56_princ = 't'
                   inner join rhlocaltrab    on rh55_codigo = rh56_localtrab
     	                                    and rh55_instit = ".$folha."_instit 
              where ".$folha."_anousu = $ano 
                and ".$folha."_mesusu = $mes
     		        and ".$folha."_instit = ".db_getsession("DB_instit")."
                $where_dentista
                $where_local
                $where_banco";

      $sqllota	.= " and $xand = '$quebra' ";
 
// echo "<BR><BR> 1.0 $sqllota";exit;

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
			                   round(sum(basefgts),2) as basefgts ,
			                   round(sum(fgts),2) as fgts 
	                from (select ".$folha."_lotac,
     	               		       case when rh02_tbprev = 1 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev1,     		       
				                       case when rh02_tbprev = 2 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev2,
     		       		             case when rh02_tbprev = 3 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev3,
     		       		             case when rh02_tbprev = 4 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev4, 
     		       		             case when ".$folha."_rubric = 'R991' then ".$folha."_valor end as basefgts ,
     		       		             case when ".$folha."_rubric = 'R991' then round(".$folha."_valor*0.08,2) end as fgts 
                        from ".$arquivo."
                             inner join rhpessoal      on rh01_regist = ".$folha."_regist
                             inner join rhpessoalmov   on rh02_anousu = ".$folha."_anousu
                                                      and rh02_mesusu = ".$folha."_mesusu
                                                      and rh02_regist = ".$folha."_regist
     		                                              and rh02_instit = ".$folha."_instit
     	                       inner join rhregime       on rh02_codreg = rh30_codreg
     	                                                and rh30_instit = rh02_instit 
     	                       inner join rhlota         on r70_codigo  = rh02_lota  
     	                                                and r70_instit  = rh02_instit 
                             left  join rhpesbanco     on rh44_seqpes = rh02_seqpes  
                             left  join rhpeslocaltrab on rh56_seqpes = rh02_seqpes  
     		                                              and rh56_princ = 't'
                             inner join rhlocaltrab    on rh55_codigo = rh56_localtrab
     	                                              and rh55_instit = ".$folha."_instit 
                        where ".$folha."_anousu = $ano 
                          and ".$folha."_mesusu = $mes
     		                  and ".$folha."_instit = ".db_getsession("DB_instit")."
                          $where_dentista
                          $where_local
                          $where_banco";
   if($tipo == "s"){	    
      $sqlprev	.= " and $xand = $quebra ";
   }else{	    
      $sqlprev	.= " and $xand = '$quebra' ";
   }  
   $sqlprev .= " and ".$folha."_rubric in ('R992','R991')  ) as x ";
		          // ver esta caso depois
			  //and ".$folha."_rubric in ('R990','R992') 
//echo "<BR><BR> 2.0 $sqlprev";exit;
      $resultprev = pg_exec($sqlprev);
//      db_criatabela($resultprev);
      db_fieldsmemory($resultprev,0);
      $pdf->ln(3);
      $pdf->cell(45,$alt,'BASE PREV.1   :'.db_formatar($prev1,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'BASE PREV.2   :'.db_formatar($prev2,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'BASE PREV.3   :'.db_formatar($prev3,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'BASE PREV.4   :'.db_formatar($prev4,'f'),0,1,"L",0);
      $pdf->cell(45,$alt,'BASE F.G.T.S. :'.db_formatar($basefgts,'f'),0,0,"L",0);
      $pdf->cell(45,$alt,'F.G.T.S. EMPR :'.db_formatar($fgts,'f'),0,1,"L",0);

      
      $vencimentos = 0;
      $descontos = 0;
      $empenho = 0;
      $baseprev = 0;
      $baseirf  = 0;
      $quebra = $lota;
      if($tipo == "s"){
        if(empty($quebra)){
          $quebra = 0;
        }
      }  
      $pdf->sety(290);
   }
   if ($pdf->gety() > $pdf->h -30){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'RUBRICA',1,0,"C",1);
      $pdf->cell(15,$alt,'N.FUNC.',1,0,"C",1);
      $pdf->cell(15,$alt,'QUANT.',1,0,"C",1);
      $pdf->cell(60,$alt,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(20,$alt,'PROVENTOS',1,0,"C",1);
      $pdf->cell(20,$alt,'DESCONTOS',1,1,"C",1);
      if($tipo == "l" || $tipo == "o" || $tipo == "s" || $tipo == "t" ){
        $pdf->multicell(0,5,$lota." - ".strtoupper($r70_descr));
        $pdf->ln(4);
      }
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
if ($tipo == "l" || $tipo == "o" || $tipo == "s" || $tipo == "t"){
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
                   inner join rhpessoal      on rh01_regist = ".$folha."_regist
                   inner join rhpessoalmov   on rh02_anousu = ".$folha."_anousu
                                            and rh02_mesusu = ".$folha."_mesusu
                                            and rh02_regist = ".$folha."_regist
     		                                    and rh02_instit = ".$folha."_instit
     	             inner join rhregime       on rh02_codreg = rh30_codreg
     	                                      and rh30_instit = rh02_instit 
     	             inner join rhlota         on r70_codigo  = rh02_lota  
     	                                      and r70_instit  = rh02_instit 
                   left  join rhpesbanco     on rh44_seqpes = rh02_seqpes  
                   left  join rhpeslocaltrab on rh56_seqpes = rh02_seqpes  
     		                                    and rh56_princ = 't'
                   inner join rhlocaltrab    on rh55_codigo = rh56_localtrab
     	                                    and rh55_instit = ".$folha."_instit 
              where ".$folha."_anousu = $ano 
                and ".$folha."_mesusu = $mes
     		        and ".$folha."_instit = ".db_getsession("DB_instit")."
                $where_dentista
                $where_local
                $where_banco";

      $sqllota	.= " and $xand = '$quebra' ";
	 
// echo "<BR><BR> 3.0 $sqllota";
// echo "<BR><BR> 3.1 $tipo";
// exit;
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
     		              round(sum(basefgts),2) as basefgts ,
     		              round(sum(fgts),2) as fgts
		 
               from (
                    select ".$folha."_lotac,
     	                      case when rh02_tbprev = 1 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev1,
     		                    case when rh02_tbprev = 2 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev2,
     		                    case when rh02_tbprev = 3 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev3,
     		                    case when rh02_tbprev = 4 and ".$folha."_rubric <> 'R991' then ".$folha."_valor end as prev4, 
     		                    case when ".$folha."_rubric = 'R991' then ".$folha."_valor*0.08 end as basefgts, 
     		                    case when ".$folha."_rubric = 'R991' then round(".$folha."_valor*0.08,2) end as fgts 
              from ".$arquivo."
                   inner join rhpessoal      on rh01_regist = ".$folha."_regist
                   inner join rhpessoalmov   on rh02_anousu = ".$folha."_anousu
                                            and rh02_mesusu = ".$folha."_mesusu
                                            and rh02_regist = ".$folha."_regist
     		                                    and rh02_instit = ".$folha."_instit
     	             inner join rhregime       on rh02_codreg = rh30_codreg
     	                                      and rh30_instit = rh02_instit 
     	             inner join rhlota         on r70_codigo  = rh02_lota  
     	                                      and r70_instit  = rh02_instit 
                   left  join rhpesbanco     on rh44_seqpes = rh02_seqpes  
                   left  join rhpeslocaltrab on rh56_seqpes = rh02_seqpes  
     		                                    and rh56_princ = 't'
                   inner join rhlocaltrab    on rh55_codigo = rh56_localtrab
     	                                    and rh55_instit = ".$folha."_instit 
              where ".$folha."_anousu = $ano 
                and ".$folha."_mesusu = $mes
     		        and ".$folha."_instit = ".db_getsession("DB_instit")."
                $where_dentista
                $where_local
                $where_banco";

      $sqlprev	.= " and $xand = '$quebra' ";
      $sqlprev  .= " and ".$folha."_rubric in ('R992','R991') ) as x ";
// echo "<BR><BR>$sqlprev";
		          // ver esta caso depois
			  //and ".$folha."_rubric in ('R990','R992') 
   $resultprev = pg_exec($sqlprev);
   db_fieldsmemory($resultprev,0);
   $pdf->ln(3);
   $pdf->cell(45,$alt,'BASE PREV.1   :'.db_formatar($prev1,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV.2   :'.db_formatar($prev2,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV 3   :'.db_formatar($prev3,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'BASE PREV 4   :'.db_formatar($prev4,'f'),0,1,"L",0);
   $pdf->cell(45,$alt,'BASE F.G.T.S. :'.db_formatar($basefgts,'f'),0,0,"L",0);
   $pdf->cell(45,$alt,'F.G.T.S. EMPR :'.db_formatar($fgts,'f'),0,1,"L",0);
   $vencimentos = 0;
   $descontos = 0;
   $baseprev = 0;
   $baseirf  = 0;
   $quebra = $lota;
   if($tipo == "s"){
      if(empty($quebra)){
        $quebra = 0;
      }
   }  
   $pdf->sety(290);
}
$pdf->Output();
?>