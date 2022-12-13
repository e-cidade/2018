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

$clrotulo = new rotulocampo;
$clrotulo->label('r14_rubric');
$clrotulo->label('z01_nome');
$clrotulo->label('r01_regist');
$clrotulo->label('r14_quant');
$clrotulo->label('r14_valor');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$sql1 = "select rh27_rubric,
                rh27_descr 
        from rhrubricas 
        where rh27_rubric = '$rubrica' and rh27_instit = ".db_getsession("DB_instit"); 
//echo $sql1;exit;
$result1 = pg_query($sql1);
db_fieldsmemory($result1,0);
if (pg_numrows($result1) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Rubrica não cadastrada no período de '.$mes.' / '.$ano);
}

$head2 = "FINANCEIRA POR RUBRICA";
$head3 = "RUBRICA : ".$rubrica." - ".$rh27_descr;
$head4 = "PERÍODO : ".$mes." / ".$ano;

if($ponto == 'f'){
  $arquivo = 'pontofx';
  $sigla   = 'r90_';
  $campo   = 'r90_datlim as datlim,';
  $head5   = 'PONTO : FIXO';
}elseif($ponto == 's'){
  $arquivo = 'pontofs';
  $sigla   = 'r10_';
  $campo   = 'r10_datlim as datlim,';
  $head5   = 'PONTO : SALÁRIO';
}elseif($ponto == 'c'){
  $arquivo = 'pontocom';
  $sigla   = 'r47_';
  $campo   = '';
  $head5   = 'PONTO : COMPLEMENTAR';
}elseif($ponto == 'a'){
  $arquivo = 'pontofa';
  $sigla   = 'r21_';
  $campo   = '';
  $head5   = 'PONTO : ADIANTAMENTO';
}elseif($ponto == 'r'){
  $arquivo = 'pontofr';
  $sigla   = 'r19_';
  $campo   = '';
  $head5   = 'PONTO : RESCISÃO';
}
if($recurso == 's'){
  $head6   = 'ALFABÉTICA POR RECURSO';
  $orderby = 'order by rh25_recurso, z01_nome ';
}else{  
   if($ordem == 'a'){
     $head6   = 'ORDEM : ALFABÉTICA '.strtoupper($tipoordem);
     $orderby = 'order by z01_nome '.$tipoordem;
   }elseif($ordem == 'n'){
     $head6   = 'ORDEM : NUMÉRICA '.strtoupper($tipoordem);
     $orderby = 'order by regist '.$tipoordem;
   }elseif($ordem == 'd'){
     $head6   = 'ORDEM : DIGITAÇÃO ';
     $orderby = 'order by '.$arquivo.'.oid ';
   }elseif($ordem == 'l'){
     $head6   = 'ORDEM : LOTAÇÃO '.strtoupper($tipoordem);
     $orderby = 'order by lotacao '.$tipoordem;
   }elseif($ordem == 'v'){
     $head6   = 'ORDEM : VALOR '.strtoupper($tipoordem);
     $orderby = 'order by valor '.$tipoordem;
   }elseif($ordem == 'q'){
     $head6   = 'ORDEM : QUANTIDADE '.strtoupper($tipoordem);
     $orderby = 'order by quant '.$tipoordem;
   }
}

$sql = "
        select ".$sigla."rubric as rubric,
               ".$sigla."regist as regist,
               $campo	       
       	       z01_nome,
	             round(".$sigla."quant,2) as quant,
               to_number(".$sigla."lotac,'99999') as lotacao,
	             rh25_recurso,
       	       o15_descr,
               r70_codigo,
	             round(".$sigla."valor,2) as valor
      	from ".$arquivo." inner join rhpessoalmov  on rh02_regist = ".$sigla."regist 
                          	                      and rh02_anousu = ".$sigla."anousu 
            			                                and rh02_mesusu = ".$sigla."mesusu 
                          											  and rh02_instit = ".$sigla."instit
													inner join rhpessoal on rh01_regist = ".$sigla."regist																					
	                        inner join cgm on z01_numcgm = rh01_numcgm 
                          inner join rhlota   on r70_codigo = rh02_lota
						                                 and r70_instit = ".$sigla."instit
                          left join (select distinct rh25_codigo, 
													                           rh25_recurso 
                        		         from rhlotavinc where rh25_anousu = $ano) as rhlotavinc on rh25_codigo = r70_codigo 
                    	    left join orctiporec on o15_codigo = rh25_recurso
       	where ".$sigla."rubric = '$rubrica'
      	  and ".$sigla."anousu = $ano 
       	  and ".$sigla."mesusu = $mes
       		and ".$sigla."instit = ".db_getsession("DB_instit")."
         	$orderby ";
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
$alt   = 4;
$xvalor = 0;
$xquant = 0;
$total = 0;
$func_c  = 0;
$tot_c   = 0;
$quebra = 0;
$totq_c  = 0;

for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      if($xtotal == 'a'){
        $pdf->setfont('arial','b',8);
        $pdf->cell(12,$alt,$RLr01_regist,1,0,"C",1);
        $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(15,$alt,'LOTAÇÃO',1,0,"C",1);
        $pdf->cell(55,$alt,'RECURSO',1,0,"C",1);
        if($ponto == 'f' || $ponto == 's'){
          $pdf->cell(12,$alt,'DATLIM',1,0,"C",1);
        }
        $pdf->cell(12,$alt,'QUANT',1,0,"C",1);
        $pdf->cell(20,$alt,'VALOR',1,1,"C",1);
      }
      $troca = 0;
      $pre = 1;
   }
   if($xtotal == 'a'){
     if($pre == 1)
       $pre = 0;
     else
       $pre = 1;
     if ( $quebra != $rh25_recurso && $recurso == 's'){
        if($quebra != ''){
          $pdf->ln(1);
          $pdf->cell(156,$alt,'Total do Recurso  :  '.$func_c,"T",0,"L",0);
          $pdf->cell(12,$alt,db_formatar($totq_c,'f'),"T",0,"R",0);
          $pdf->cell(20,$alt,db_formatar($tot_c,'f'),"T",1,"R",0);
      	  $func_c = 0;
  	  $tot_c  = 0;
  	  $totq_c  = 0;
        }
        $pdf->setfont('arial','b',9);
        $pdf->ln(4);
        $pdf->cell(50,$alt,$o15_descr,0,1,"L",1);
        $quebra = $rh25_recurso;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(12,$alt,$regist,0,0,"C",$pre);
     $pdf->cell(60,$alt,$z01_nome,0,0,"L",$pre);
     $pdf->cell(15,$alt,$lotacao,0,0,"C",$pre);
     $pdf->cell(55,$alt,$o15_descr,0,0,"L",$pre);
     if($ponto == 'f' || $ponto == 's'){
       $pdf->cell(12,$alt,$datlim,0,0,"R",$pre);
     }  
   
     $pdf->cell(12,$alt,db_formatar($quant,'f'),0,0,"R",$pre);
     $pdf->cell(20,$alt,db_formatar($valor,'f'),0,1,"R",$pre);
   }
   $tot_c  += $valor;
   $totq_c += $quant;
   $xvalor += $valor;
   $xquant += $quant;
   $total  += 1;
   $func_c += 1;
}
if ( $recurso == 's' ){
   $pdf->ln(1);
   $pdf->cell(156,$alt,'Total do Recurso  :  '.$func_c,"T",0,"L",0);
   $pdf->cell(12,$alt,db_formatar($totq_c,'f'),"T",0,"R",0);
   $pdf->cell(20,$alt,db_formatar($tot_c,'f'),"T",1,"R",0);
}

$pdf->setfont('arial','b',8);
$pdf->cell(156,$alt,'TOTAL  :  '.$total.'  FUNCIONÁRIOS',"T",0,"L",0);
//if($ponto == 'f' || $ponto == 's'){
//  $pdf->cell(12,$alt,'',"T",0,"R",0);
//}
$pdf->cell(12,$alt,db_formatar($xquant,'f'),"T",0,"R",0);
$pdf->cell(20,$alt,db_formatar($xvalor,'f'),"T",1,"R",0);

$pdf->Output();
   
?>