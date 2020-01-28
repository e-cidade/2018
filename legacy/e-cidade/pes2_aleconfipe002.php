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
db_postmemory($HTTP_GET_VARS);

$ano = $anofolha;
$mes = $mesfolha;

$head3 = "RELATÓRIO DE CONFERÊNCIA DO IPE";
$head5 = "PERÍODO  : ".$mes." / ".$ano;

//if(!isset($dif)){
if(!isset($dif)){
$wheredif = "";
//if(isset($dif)){
//  $wheredif = " where r14_valor <> (seg_est+seg_cc) ";
//}
$dbwherelota = "";
$dborderby   = "z01_nome";
if($tipores != "g"){
  if(isset($sellotac) && trim($sellotac) != ""){
    $dbwherelota = " and trim(r70_estrut) in ('" . str_replace(",","','",$sellotac) . "') ";
  }else if(isset($lotai) && trim($lotai) != "" && isset($lotaf) && trim($lotaf) != ""){
    $dbwherelota = " and r70_estrut between '" . $lotai . "' and ' " . $lotaf . "' ";
  }else if(isset($lotai) && trim($lotai) != ""){
    $dbwherelota = " and r70_estrut >= '" . $lotai . "' ";
  }else if(isset($lotaf) && trim($lotaf) != ""){
    $dbwherelota = " and r70_estrut <= '" . $lotaf . "' ";
  }
  $dborderby = "r70_estrut, z01_nome ";
}

$sql =
"
select * from 
(
select r36_regist,
       z01_nome,
       r36_contr1,
       r36_valorc as base, 
       r14_valor, 
       r14_rubric, 
       round(case when r14_rubric = '0595' then r36_valorc/100*6.8 else 0 end ,2) as seg_est,
       round(case when r14_rubric = '0595' then r36_valorc/100*6.4 else 0 end ,2) as patron,
       round(case when r14_rubric = '0695' then r36_valorc/100*13.2 else 0 end ,2) as seg_cc,
       r70_estrut,
       r70_descr,
       round(case when r14_rubric = '0595' 
              then r36_valorc/100*6.8 
              else 
     	      case when r14_rubric = '0695' 
		   then r36_valorc/100*13.2
	        else 0
	      end
            end
       ,2) as calculo 
from ipe
       inner join rhpessoal on rh01_regist = r36_regist
			 inner join cgm on rh01_numcgm = z01_numcgm
       inner join rhpessoalmov on rh02_regist = rh01_regist 
			                        and rh02_anousu = r36_anousu 
															and rh02_mesusu = r36_mesusu
															and rh02_instit = ".db_getsession('DB_instit')."
       inner join rhlota       on r70_codigo  = rh02_lota
                              and r70_instit  = rh02_instit
       inner join rhregime     on rh30_codreg = rh02_codreg
                              and rh30_instit = rh02_instit
       left join (
                  select r14_anousu,r14_mesusu,r14_regist,r14_rubric,sum(r14_valor) as r14_valor,r14_instit
		  from (
                      select r14_anousu,r14_mesusu,r14_regist,r14_rubric,r14_valor,r14_instit
                      from gerfsal 
		      where r14_anousu = $ano
		        and r14_mesusu = $mes
		        and r14_instit = ".db_getsession('DB_instit')."
                        and r14_rubric in ('0595', '0695') 
		      union all
                      select r48_anousu,r48_mesusu,r48_regist,r48_rubric,r48_valor,r48_instit
                      from gerfcom 
		      where r48_anousu = $ano
		        and r48_mesusu = $mes
		        and r48_instit = ".db_getsession('DB_instit')."
                        and r48_rubric in ('0595', '0695')
			)as aaa
			group by r14_anousu,r14_mesusu,r14_regist,r14_rubric,r14_instit) as x
		 
                         on r14_anousu = r36_anousu 
                        and r14_mesusu = r36_mesusu 
		        and r14_regist = r36_regist 
                        and r14_instit = rh02_instit
                        and r14_rubric in ('0595', '0695') 

where r36_anousu = $ano 
  and r36_mesusu = $mes
	and r36_instit = ".db_getsession('DB_instit')."
$dbwherelota
order by $dborderby
) as x $wheredif
";

//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem cálculos para o período de '.$mes.' / '.$ano);

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

$totallot_fun  = 0;
$totallot_prov = 0;
$totallot_seg  = 0;
$totallot_base = 0;
$totallot_patro= 0;
$totallot_total= 0;
$lotaant = "";

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if($tipores != "g" && $lotaant != $r70_estrut){
     $lotaant = $r70_estrut;
     $troca = 1;
     if($lotaant != ""){
       $pdf->cell(75,$alt,'TOTAL DA LOTAÇÃO:  '.$totallot_fun.' FUNCIONÁRIOS',"T",0,"C",0);
       $pdf->cell(20,$alt,db_formatar($totallot_prov,'f'),"T",0,"C",0);
       $pdf->cell(20,$alt,db_formatar($totallot_base,'f'),"T",0,"C",0);
       $pdf->cell(20,$alt,db_formatar($totallot_seg,'f'),"T",0,"C",0);
       $pdf->cell(20,$alt,db_formatar($totallot_patro,'f'),"T",0,"C",0);
       $pdf->cell(20,$alt,db_formatar($totallot_total,'f'),"T",1,"C",0);
     }
     $totallot_fun  = 0;
     $totallot_prov = 0;
     $totallot_seg  = 0;
     $totallot_base = 0;
     $totallot_patro= 0;
     $totallot_total= 0;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(20,$alt,'BASE',1,0,"C",1);
      $pdf->cell(20,$alt,'PATRONAL',1,0,"C",1);
      $pdf->cell(20,$alt,'ESTATUTARIO',1,0,"C",1);
      $pdf->cell(20,$alt,'CLT/CC',1,0,"C",1);
      $pdf->cell(20,$alt,'CÁLCULO',1,1,"C",1);
      $pdf->cell(175,$alt,$r70_estrut . " - " . $r70_descr,1,1,"L",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r36_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($base,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($patron,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($seg_est,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($seg_cc,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($calculo,'f'),0,1,"R",$pre);
   $total_fun   += 1;
   $total_prov  += $base;
   $total_base  += $patron ;
   $total_seg   += $seg_est ;
   $total_patro += $seg_cc;
   $total_total += $calculo;

   $totallot_fun   += 1;
   $totallot_prov  += $base;
   $totallot_base  += $patron ;
   $totallot_seg   += $seg_est ;
   $totallot_patro += $seg_cc;
   $totallot_total += $calculo;
}
if($tipores != "g" && $lotaant != $r70_estrut){
  $lotaant = $r70_estrut;
  $troca = 1;
  if($lotaant != ""){
    $pdf->cell(75,$alt,'TOTAL DA LOTAÇÃO:  '.$totallot_fun.' FUNCIONÁRIOS',"T",0,"C",0);
    $pdf->cell(20,$alt,db_formatar($totallot_prov,'f'),"T",0,"C",0);
    $pdf->cell(20,$alt,db_formatar($totallot_base,'f'),"T",0,"C",0);
    $pdf->cell(20,$alt,db_formatar($totallot_seg,'f'),"T",0,"C",0);
    $pdf->cell(20,$alt,db_formatar($totallot_patro,'f'),"T",0,"C",0);
    $pdf->cell(20,$alt,db_formatar($totallot_total,'f'),"T",1,"C",0);
  }
}

$pdf->setfont('arial','b',8);
$pdf->cell(75,$alt,'TOTAL :  '.$total_fun.' FUNCIONÁRIOS ',"T",0,"C",0);
$pdf->cell(20,$alt,db_formatar($total_prov,'f'),"T",0,"C",0);
$pdf->cell(20,$alt,db_formatar($total_base,'f'),"T",0,"C",0);
$pdf->cell(20,$alt,db_formatar($total_seg,'f'),"T",0,"C",0);
$pdf->cell(20,$alt,db_formatar($total_patro,'f'),"T",0,"C",0);
$pdf->cell(20,$alt,db_formatar($total_total,'f'),"T",1,"C",0);
}else{
$sql = "select r36_regist ,
               z01_nome,
               sum(calculo) as ipe,
	       sum(valor) as calculo
	from (select r36_regist,calculo , 0 as valor 
	      from ( select r36_regist, 
	                    z01_nome, 
			    r36_contr1, 
			    r36_valorc as base, 
			    r14_valor, 
			    r14_rubric, 
			    round(case when r14_rubric = '0595' then r36_valorc/100*6.8 else 0 end ,2) as seg_est, 
			    round(case when r14_rubric = '0595' then r36_valorc/100*6.4 else 0 end ,2) as patron, 
			    round(case when r14_rubric = '0695' then r36_valorc/100*13.2 else 0 end ,2) as seg_cc, 
			    r70_estrut, 
			    r70_descr, 
			    round(case when r14_rubric = '0595' 
			               then r36_valorc/100*6.8 
				       else 
					  case when r14_rubric = '0695' 
					    then r36_valorc/100*13.2 
					    else 0 
					  end 
				  end ,2) as calculo 
		     from ipe 
		          inner join rhpessoal on rh01_regist = r36_regist 
			  inner join cgm on rh01_numcgm = z01_numcgm 
			  inner join rhpessoalmov on rh02_regist = rh01_regist and rh02_anousu = r36_anousu and rh02_mesusu = r36_mesusu and rh02_instit = ".db_getsession('DB_instit')."  
			  inner join rhlota on r70_codigo = rh02_lota and r70_instit = rh02_instit 
			  inner join rhregime on rh30_codreg = rh02_codreg and rh30_instit = rh02_instit 
			  left join ( select r14_anousu,r14_mesusu,r14_regist,r14_rubric,sum(r14_valor) as r14_valor,r14_instit 
			              from ( select r14_anousu,r14_mesusu,r14_regist,r14_rubric,r14_valor,r14_instit 
				             from gerfsal 
					     where r14_anousu = $ano 
					       and r14_mesusu = $mes 
					       and r14_instit = ".db_getsession('DB_instit')." 
					       and r14_rubric in ('0595', '0695') 
					     
					     union all 
					     
					     select r48_anousu,r48_mesusu,r48_regist,r48_rubric,r48_valor,r48_instit 
					     from gerfcom 
					     where r48_anousu = $ano 
					       and r48_mesusu = $mes
					       and r48_instit = ".db_getsession('DB_instit')."
					       and r48_rubric in ('0595', '0695') )as aaa 
				      group by r14_anousu,r14_mesusu,r14_regist,r14_rubric,r14_instit
				    ) as x on r14_anousu = r36_anousu 
				          and r14_mesusu = r36_mesusu 
					  and r14_regist = r36_regist 
					  and r14_instit = rh02_instit 
					  and r14_rubric in ('0595', '0695') 
		     where r36_anousu = $ano 
		       and r36_mesusu = $mes
		       and r36_instit = ".db_getsession('DB_instit')."
		     order by z01_nome 
	     ) as x 
	     
	     union 
	     
	     select r14_regist, 0,r14_valor 
	     from gerfsal 
	     where r14_anousu = $ano 
	       and r14_mesusu = $mes
	       and r14_rubric in ('0595','0695') 
	       and r14_instit = ".db_getsession('DB_instit')."
	    ) as d 
            inner join rhpessoal on rh01_regist = r36_regist 
	    inner join cgm on rh01_numcgm = z01_numcgm 
        group by r36_regist,z01_nome 
	having sum(calculo) <> sum(valor);
";
//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem cálculos para o período de '.$mes.' / '.$ano);

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
$total_val  = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,'MATRIC',1,0,"C",1);
      $pdf->cell(60,$alt,'NOME DO FUNCIONÁRIO',1,0,"C",1);
      $pdf->cell(20,$alt,'CALCULO',1,0,"C",1);
      $pdf->cell(20,$alt,'IPE',1,0,"C",1);
      $pdf->cell(20,$alt,'DIFERENÇA',1,1,"C",1);
      $troca = 0;
      $pre = 1;
   }
   if($pre == 1){
     $pre = 0;
   }else{
     $pre = 1;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$r36_regist,0,0,"C",$pre);
   $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
   $pdf->cell(20,$alt,db_formatar($calculo,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar($ipe,'f'),0,0,"R",$pre);
   $pdf->cell(20,$alt,db_formatar(abs($calculo - $ipe),'f'),0,1,"R",$pre);
   $total_fun   += 1;
   $total_val   += abs($calculo - $ipe);
}

$pdf->setfont('arial','b',8);
$pdf->cell(75,$alt,'TOTAL :  '.$total_fun.' FUNCIONÁRIOS ',"T",0,"C",0);
$pdf->cell(20,$alt,'',"T",0,"C",0);
$pdf->cell(20,$alt,'',"T",0,"C",0);
$pdf->cell(20,$alt,db_formatar($total_val,'f'),"T",1,"R",0);

}
$pdf->Output();
   
?>