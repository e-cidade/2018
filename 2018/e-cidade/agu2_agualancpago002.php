<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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


include(modification("fpdf151/pdf.php"));
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$head1 = "LANÇADO & ARRECADADO - ÁGUA";
$head2 = "EXERCÍCIO: $anousu";
$head3 = "MÊS FINAL: $mesfinal";

$pdf = new PDF(); // abre a classe
$pdf->Open(); // abre o relatorio
$pdf->AliasNbPages(); // gera alias para as paginas
$pdf->AddPage(); // adiciona uma pagina
$pdf->SetTextColor(0,0,0);
$pdf->SetFillColor(220);

$alt = 5;

$sqlconsumotipo = "SELECT * from aguaconsumotipo order by x25_codconsumotipo;";
$resultconsumotipo = db_query($sqlconsumotipo) or die($sqlconsumotipo);

$array_tipos = array();
$x23_valor_total=0;
$k00_valor_total=0;
$matric_calc_total=0;
$matric_pago_total=0;
$mediatotal=0;

$x23_valor_total_exer = 0;
$k00_valor_total_exer = 0;
$mediatotal_exer      = 0;

for ($mes=1; $mes <= $mesfinal; $mes++) {
  
  $sql = "
    select x23_codconsumotipo, 
           x25_descr, 
           round(sum(valor_calculado),2) as x23_valor, 
           round(sum(valor_pago+valor_pago_parcial),2) as k00_valor,
           count(distinct x22_matric) as quant_matric_calc,
           count(distinct x22_matric_pago)+count(distinct x22_matric_pago_parcial) as quant_matric_pago
     from (
                 select x23_codconsumotipo, 
                        k02_descr as x25_descr,
                        sum(x23_valor) as valor_calculado,
                        0 as valor_pago,
                        0 as valor_pago_parcial,
                        count(x22_matric) as x22_matric,
                        0 as x22_matric_pago,
                        0 as x22_matric_pago_parcial
                   from aguacalc
             inner join aguacalcval         on x23_codcalc             = x22_codcalc 
             inner join aguaconsumotipo     on x25_codconsumotipo      = x23_codconsumotipo
             inner join tabrec              on k02_codigo              = x25_receit             
                  where x22_exerc = $anousu
                    and x22_mes = $mes
               group by x23_codconsumotipo,
                        k02_descr

              union all

                 select x23_codconsumotipo, 
                        x25_descr,
                        0 as valor_calculado,                        
                        sum(k00_valor) as valor_pago,
                        0 as valor_pago_parcial,
                        0 as x22_matric,
                        count(distinct x22_matric) as x22_matric_pago,
                        0 as x22_matric_pago_parcial
                   from (      select x23_codconsumotipo,
                                      k02_descr as x25_descr,
                                      x22_matric,
                                      k00_numpre,
                                      k00_numpar,
                                      k00_receit,
                                      k00_valor
                                 from aguacalc
                           inner join aguacalcval         on x23_codcalc             = x22_codcalc 
                           inner join aguaconsumotipo     on x25_codconsumotipo      = x23_codconsumotipo
                           inner join tabrec              on k02_codigo              = x25_receit             
                           inner join arrepaga            on k00_numpre              = x22_numpre
                                                         and k00_numpar              = x22_mes
                                                         and k00_receit              = x25_receit
                                where x22_exerc = $anousu
                                  and x22_mes = $mes
                             group by x23_codconsumotipo,
                                      k02_descr,
                                      x22_matric,
                                      k00_numpre,
                                      k00_numpar,
                                      k00_receit,
                                      k00_valor ) as valores_pagos
               group by x23_codconsumotipo, 
                        x25_descr

              union all

                 select x23_codconsumotipo, 
                        x25_descr,
                        0 as valor_calculado,                        
                        0 as valor_pago,
                        sum(k00_valor) as valor_pago_parcial,
                        0 as x22_matric,
                        0 as x22_matric_pago,
                        count(distinct x22_matric) as x22_matric_pago_parcial
                   from (      select x23_codconsumotipo,
                                      k02_descr as x25_descr,
                                      x22_matric,
                                      arrepaga.k00_numpre,
                                      arrepaga.k00_numpar,
                                      arrepaga.k00_receit,
                                      arrepaga.k00_valor
                                 from aguacalc
                           inner join aguacalcval         on x23_codcalc             = x22_codcalc 
                           inner join aguaconsumotipo     on x25_codconsumotipo      = x23_codconsumotipo
                           inner join tabrec              on k02_codigo              = x25_receit
                           inner join arreckey            on arreckey.k00_numpre     = x22_numpre
                                                         and arreckey.k00_numpar     = x22_mes
                                                         and arreckey.k00_receit     = x25_receit
                           inner join abatimentoarreckey  on k128_arreckey           = arreckey.k00_sequencial
                           inner join abatimento          on k125_sequencial         = k128_abatimento
                                                         and k125_tipoabatimento     = 1                                            
                           inner join abatimentorecibo    on k127_abatimento         = k125_sequencial
                           inner join recibo              on recibo.k00_numpre       = k127_numprerecibo
                           inner join arrepaga            on arrepaga.k00_numpre     = recibo.k00_numpre
                                                         and arrepaga.k00_numpar     = recibo.k00_numpar
                                                         and arrepaga.k00_receit     = recibo.k00_receit
                            left join aguaconsumo         on x19_codconsumo          = x22_codconsumo
                                where x22_exerc = $anousu
                                  and x22_mes = $mes
                             group by x23_codconsumotipo,
                                      k02_descr,
                                      x22_matric,
                                      arrepaga.k00_numpre,
                                      arrepaga.k00_numpar,
                                      arrepaga.k00_receit,
                                      arrepaga.k00_valor ) as valores_pagos_parcial
               group by x23_codconsumotipo, 
                        x25_descr
          ) as w
 group by x23_codconsumotipo, 
          x25_descr";
  
  if($tipo=="s") $sql="select sum (x23_valor) as x23_valor,sum (k00_valor) as k00_valor from ($sql) as x";
  
  $result = db_query($sql) or die($sql);
  
  if($tipo=="a"){
    $pdf->Cell(49,$alt,"",0,0,"C",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    
    $pdf->Cell(40,$alt,"V A L O R   C A L C U L A D O",0,0,"C",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->Cell(40,$alt,"V A L O R   A R R E C A D A D O",0,0,"C",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    $pdf->Cell(40,$alt,"I N A D I M P L Ê N C I A (%)",0,0,"C",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    $pdf->ln();
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    $pdf->Cell(17,$alt,"MÊS",0,0,"C",1);
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    
    $pdf->Cell(30,$alt,"DESCRIÇÃO",0,0,"L",1);
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    
    
    $pdf->Cell(10,$alt,"QUANT",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    $pdf->Cell(18,$alt,"VALOR",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    $pdf->Cell(10,$alt,"%",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    
    $pdf->Cell(10,$alt,"QUANT",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    $pdf->Cell(18,$alt,"VALOR",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    $pdf->Cell(10,$alt,"%",0,0,"R",1);
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    
    $pdf->Cell(20,$alt,"NO MÊS",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    $pdf->Cell(19,$alt,"MÉDIA" ,0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);  
    
    $pdf->ln();
    
    $x23_valor_total=0;
    $k00_valor_total=0;
    $matric_calc_total=0;
    $matric_pago_total=0;
    
    for ($x=0; $x < pg_num_rows($result); $x++) {
      db_fieldsmemory($result, $x);
      $x23_valor_total+=$x23_valor;
      $k00_valor_total+=$k00_valor;
      $matric_calc_total+=$quant_matric_calc;
      $matric_pago_total+=$quant_matric_pago;
    }
    for ($x=0; $x < pg_num_rows($result); $x++) {
      $pdf->Cell(1,$alt,"",0,0,"C",1);
      db_fieldsmemory($result, $x);
      if ($x == 0) {
        $pdf->Cell(17,$alt,db_mes($mes,1),0,0,"C",0);
      } else {
        $pdf->Cell(17,$alt,"",0,0,"C",0);
      }
      
      $pdf->Cell(1,$alt,"",0,0,"C",1);
      
      $pdf->Cell(30,$alt,$x25_descr,0,0,"L",1);
      
      
      $pdf->Cell(1,$alt,"",0,0,"C",1);    
      
      $pdf->Cell(10,$alt,db_formatar($quant_matric_calc,"s"),0,0,"R",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);    
      $pdf->Cell(18,$alt,db_formatar($x23_valor,"f"),0,0,"R",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);    
      $pdf->Cell(10,$alt,db_formatar($x23_valor/$x23_valor_total*100,"f", " ", 4),0,0,"R",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);    
      
      $pdf->Cell(10,$alt,db_formatar($quant_matric_pago,"s"),0,0,"R",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);    
      $pdf->Cell(18,$alt,db_formatar($k00_valor,"f"),0,0,"R",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);    
      $pdf->Cell(10,$alt,db_formatar($k00_valor/$k00_valor_total*100,"f"," ",4),0,0,"R",0);
      
      $pdf->Cell(1,$alt,"",0,0,"C",1);    
      
      $pdf->Cell(20,$alt,db_formatar(100-($k00_valor == 0?0:$k00_valor/$x23_valor*100),"f", " ", 4),0,0,"R",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);    
      
      if (!isset($array_tipos[$x23_codconsumotipo])) {
        $array_tipos[$x23_codconsumotipo]=0;
      }
      $array_tipos[$x23_codconsumotipo] += 100-($k00_valor == 0?0:$k00_valor/$x23_valor*100);
      
      $pdf->Cell(19,$alt,db_formatar($array_tipos[$x23_codconsumotipo]/$mes,"f", " ", 4),0,0,"R",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);
      $pdf->ln();
      
    }//for
    $pdf->Cell(1,1,"",0,0,"C",1);
    $pdf->Cell(17,1,"",0,0,"C",0);
    $pdf->Cell(155,1,"",0,0,"C",1);
    $pdf->ln();
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    
    $pdf->Cell(17,$alt,"",0,0,"C",0);
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    
    $pdf->Cell(30,$alt,"TOTAL NO MÊS",0,0,"L",1);
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    
    $pdf->Cell(10,$alt,db_formatar($matric_calc_total,"s"),0,0,"R",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);    
    $pdf->Cell(18,$alt,db_formatar($x23_valor_total,"f"),0,0,"R",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);    
    $pdf->Cell(10,$alt,"100",0,0,"R",0);
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    
    $pdf->Cell(10,$alt,db_formatar($matric_pago_total,"s"),0,0,"R",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);    
    $pdf->Cell(18,$alt,db_formatar($k00_valor_total,"f"),0,0,"R",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);    
    $pdf->Cell(10,$alt,"100",0,0,"R",0);
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    
    $pdf->Cell(20,$alt,db_formatar(100-($k00_valor_total == 0?0:$k00_valor_total/$x23_valor_total*100),"f", " ", 4),0,0,"R",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);    
    
    $mediatotal += 100-($k00_valor_total == 0?0:$k00_valor_total/$x23_valor_total*100);
    
    $pdf->Cell(19,$alt,db_formatar($mediatotal/$mes,"f", " ", 4),0,0,"R",0);
    
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->ln();
    $pdf->Cell(1,1,"",0,0,"C",1);
    $pdf->Cell(17,1,"",0,0,"C",0);
    $pdf->Cell(155,1,"",0,0,"C",1);
    $pdf->ln();

    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->Cell(17,$alt,"",0,0,"C",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->Cell(30,$alt,"TOTAL NO EXERCÍCIO",0,0,"L",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->Cell(10,$alt,"",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $x23_valor_total_exer+=$x23_valor_total;
    $pdf->Cell(18,$alt,db_formatar($x23_valor_total_exer,"f"),0,0,"R",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->Cell(10,$alt,"",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->Cell(10,$alt,"",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $k00_valor_total_exer+=$k00_valor_total;
    $pdf->Cell(18,$alt,db_formatar($k00_valor_total_exer,"f"),0,0,"R",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->Cell(10,$alt,"",0,0,"R",1);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->Cell(20,$alt,db_formatar(100-($k00_valor_total_exer == 0?0:$k00_valor_total_exer/$x23_valor_total_exer*100),"f", " ", 4),0,0,"R",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $mediatotal_exer += 100-($k00_valor_total_exer == 0?0:$k00_valor_total_exer/$x23_valor_total_exer*100);
    $pdf->Cell(19,$alt,db_formatar($mediatotal_exer/$mes,"f", " ", 4),0,0,"R",0);
    $pdf->Cell(1,$alt,"",0,0,"C",1);
    $pdf->ln();

    $pdf->Cell(173,1,"",0,0,"C",1);
    $pdf->ln();
    
  if (($mes % 7)==0) {$pdf->ln(20);}
  }//if "a"
  else { 
    $pdf->Cell(18,$alt,"",0,0,"L",1);
    $pdf->Cell(2,$alt,"",0,0,"L",1);
    
    $pdf->Cell(70,$alt,"N O   M Ê S",0,0,"C",1);
    $pdf->Cell(3,$alt,"",0,0,"L",1);
    $pdf->Cell(70,$alt,"N O   E X E R C Í C I O",0,0,"C",1);
    //$pdf->Cell(40,$alt,"I N A D I M P L Ê N C I A",0,0,"C",1);
    $pdf->ln();
    
    $pdf->Cell(18,$alt,"MÊS",0,0,"C",1);
    $pdf->Cell(2,$alt,"",0,0,"L",1);
    
    $pdf->Cell(30,$alt,"LANÇADO",0,0,"C",1);
    $pdf->Cell(30,$alt,"ARRECADADO",0,0,"C",1);
    $pdf->Cell(10,$alt,"%",0,0,"C",1);
    
    $pdf->Cell(3,$alt,"",0,0,"L",1);
    
    $pdf->Cell(30,$alt,"LANÇADO",0,0,"C",1);
    $pdf->Cell(30,$alt,"ARRECADADO",0,0,"C",1);
    $pdf->Cell(10,$alt,"%",0,0,"C",1);
    
    //$pdf->Cell(20,$alt,"",0,0,"C",0);
    //$pdf->Cell(20,$alt,"MÉDIA" ,0,0,"R",1);
    
    $pdf->ln();
    
    //$x23_valor_total=0;
    //$k00_valor_total=0;
    
    //for ($x=0; $x < pg_num_rows($result); $x++) {
      //	db_fieldsmemory($result, $x);
      //	$x23_valor_total+=$x23_valor;
      //	$k00_valor_total+=$k00_valor;
      
    //}
    
    //$x23_valor_exerc = 0;
    //$k00_valor_exerc = 0;
    
    for ($x=0; $x < pg_num_rows($result); $x++) {
      db_fieldsmemory($result, $x);
      $pdf->Cell(1,$alt,"",0,0,"C",1);
      if ($x == 0) {
        $pdf->Cell(17,$alt,db_mes($mes,1),0,0,"R",0);
      } else {
        $pdf->Cell(17,$alt,"",0,0,"R",0);
      }
      
      $pdf->Cell(2,$alt,"",0,0,"L",1);
      
      //$pdf->Cell(10,$alt,"                    ",0,0,"R",0);
      $pdf->Cell(29,$alt,db_formatar($x23_valor,"f"),0,0,"C",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);
      $x23_valor_exerc+=$x23_valor;
      $pdf->Cell(29,$alt,db_formatar($k00_valor,"f"),0,0,"C",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);
      $pdf->Cell(9,$alt,db_formatar($k00_valor*100/$x23_valor,"f", " ", 4),0,0,"C",0); 
      $pdf->Cell(3,$alt,"",0,0,"C",1);
      //$pdf->Cell(10,$alt,"",0,0,"L",0);
      
      //$pdf->Cell(10,$alt,"                    ",0,0,"R",1);
      $pdf->Cell(29,$alt,db_formatar($x23_valor_exerc,"f", " ", 4),0,0,"C",0);  
      $pdf->Cell(1,$alt,"",0,0,"C",1);
      $k00_valor_exerc+=$k00_valor;
      $pdf->Cell(29,$alt,db_formatar($k00_valor_exerc,"f", " ", 4),0,0,"C",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);
      $pdf->Cell(10,$alt,db_formatar($k00_valor_exerc*100/$x23_valor_exerc,"f", " ", 4),0,0,"C",0);
      $pdf->Cell(1,$alt,"",0,0,"C",1);
      
      //if (!isset($array_tipos[$x23_codconsumotipo])) {
        //			$array_tipos[$x23_codconsumotipo]=0;
      //		}
      //	:	$array_tipos[$x23_codconsumotipo] += 100-($k00_valor == 0?0:$k00_valor/$k00_valor_total*100);
      
      //    $pdf->Cell(20,$alt,db_formatar($array_tipos[$x23_codconsumotipo]/$mes,"f", " ", 4),0,0,"R",1);
      
      $pdf->ln();
      $pdf->Cell(163,1,"",0,0,"C",1);

      
    }
    
    $pdf->ln();
    //$x23_valor_exerc=$x23_valor;
    //$k00_valor_exerc=$k00_valor;
  }//ELSE
  
}

$pdf->Output();

?>