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
include("libs/db_liborcamento.php");
include("libs/db_sql.php");

//db_postmemory($HTTP_SERVER_VARS,2);exit;
db_postmemory($HTTP_SERVER_VARS);

$head1 = "BALANCETE DA DESPESA";
$head3 = "EXERCÍCIO: ".db_getsession("DB_anousu");
$head5 = "PERÍODO: ".db_formatar($dataini,'d')." A ".db_formatar($datafin,'d');
$xinstit = split("-",$db_selinstit);
$resultinst = pg_exec("select codigo,nomeinst,nomeinstabrev from db_config where codigo in (".str_replace('-',', ',$db_selinstit).") ");
$descr_inst = '';
$xvirg = '';
$flag_abrev = false;
for($xins = 0; $xins < pg_numrows($resultinst); $xins++){
  db_fieldsmemory($resultinst,$xins);
  if (strlen(trim($nomeinstabrev)) > 0){
       $descr_inst .= $xvirg.$nomeinstabrev; 
       $flag_abrev  = true;
  } else {
       $descr_inst .= $xvirg.$nomeinst; 
  }

  $xvirg = ', ';
}

if ($flag_abrev == false){
     if (strlen($descr_inst) > 42){
          $descr_inst = substr($descr_inst,0,100);
     }
}

$head6 = "INSTITUIÇÕES : ".$descr_inst;


$anousu = db_getsession("DB_anousu");
//$dataini = '2005-01-01';
//$datafin = '2005-02-28';
$sele_work = ' w.o58_instit in ('.str_replace('-',', ',$db_selinstit).') ';
$result    = db_dotacaosaldo(4,2,2,true,$sele_work,$anousu,$dataini,$datafin,8,0,true);
$sql = "select o58_funcao,
               o52_descr,
	       o58_subfuncao,
	       o53_descr,
	       sum(dot_ini) as dot_ini,
	       sum(saldo_anterior) as saldo_anterior,
	       sum(empenhado) as empenhado,
	       sum(anulado) as anulado,
	       sum(liquidado) as liquidado,
	       sum(pago) as pago,
	       sum(suplementado) as suplementado,
	       sum(reduzido) as reduzido,
	       sum(atual) as atual,
	       sum(reservado) as reservado,
	       sum(atual_menos_reservado) as atual_menos_reservado,
	       sum(atual_a_pagar) as atual_a_pagar,
	       sum(atual_a_pagar_liquidado) as atual_a_pagar_liquidado,
	       sum(empenhado_acumulado) as empenhado_acumulado,
	       sum(anulado_acumulado) as anulado_acumulado,
	       sum(liquidado_acumulado) as liquidado_acumulado,
	       sum(pago_acumulado) as pago_acumulado,
	       sum(suplementado_acumulado) as suplementado_acumulado,
	       sum(reduzido_acumulado) as reduzido_acumulado
        from ($result) as rr 
	group by o58_funcao,o52_descr,o58_subfuncao,o53_descr
	order by o58_funcao,o58_subfuncao";
//db_criatabela($result);exit;
// funcao para gerar work
//echo $sql; exit;
$res = pg_exec($sql);


pg_exec("commit");

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',7);
$pagina        = 1;
$alt = 4;

$funcao = '';

$totfuncadot_ini                = 0; 
$totfuncasuplementado_acumulado = 0;
$totfuncareduzido_acumulado     = 0;
$totfuncaatual                  = 0;
$totfuncaempenhado              = 0;
$totfuncaanulado                = 0;
$totfuncaliquidado              = 0;
$totfuncapago                   = 0;
$totfuncaatual_a_pagar          = 0;
$totfuncaempenhado_acumulado    = 0;
$totfuncaanulado_acumulado      = 0;
$totfuncaliquidado_acumulado    = 0;
$totfuncapago_acumulado         = 0;
$totfuncaatual_a_pagar_liquidado= 0;      

$totgeraldot_ini                = 0; 
$totgeralsuplementado_acumulado = 0;
$totgeralreduzido_acumulado     = 0;
$totgeralatual                  = 0;
$totgeralempenhado              = 0;
$totgeralanulado                = 0;
$totgeralliquidado              = 0;
$totgeralpago                   = 0;
$totgeralatual_a_pagar          = 0;
$totgeralempenhado_acumulado    = 0;
$totgeralanulado_acumulado      = 0;
$totgeralliquidado_acumulado    = 0;
$totgeralpago_acumulado         = 0;
$totgeralatual_a_pagar_liquidado= 0;      

for($i=0;$i<pg_numrows($res);$i++){

  db_fieldsmemory($res,$i);
  
  $codigo = db_formatar($o58_subfuncao,'subfuncao');
  $descr  = $o53_descr;
  
  if($pdf->gety() > $pdf->h-40 || $pagina == 1){
    $pagina = 0;

    $pdf->addpage();
    $pdf->setfont('arial','b',7);
    
    $pdf->ln(2);
    $pdf->cell(40,$alt,"",0,0,"C",0);
    $pdf->cell(30,$alt,"SALDO INICIAL",0,0,"R",0);
    $pdf->cell(30,$alt,"SUPLEMENTAÇÕES",0,0,"R",0);
    $pdf->cell(30,$alt,"REDUÇÕES",0,0,"R",0);
    $pdf->cell(30,$alt,"TOTAL CRÉDITOS",0,0,"R",0);
    $pdf->cell(30,$alt,"SALDO DISPONÍVEL",0,1,"R",0);
    $pdf->cell(40,$alt,"DOTAÇÃO",0,0,"L",0);
    $pdf->cell(30,$alt,"EMPENHADO NO MÊS",0,0,"R",0);
    $pdf->cell(30,$alt,"ANULADO NO MÊS",0,0,"R",0);
    $pdf->cell(30,$alt,"LIQUIDADO NO MÊS",0,0,"R",0);
    $pdf->cell(30,$alt,"PAGO NO MÊS",0,0,"R",0);
    $pdf->cell(30,$alt,"A LIQUIDAR",0,1,"R",0);
    $pdf->cell(40,$alt,"",0,0,"L",0);
    $pdf->cell(30,$alt,"EMPENHADO NO ANO",0,0,"R",0);
    $pdf->cell(30,$alt,"ANULADO NO ANO",0,0,"R",0);
    $pdf->cell(30,$alt,"LIQUIDADO NO ANO",0,0,"R",0);
    $pdf->cell(30,$alt,"PAGO NO ANO",0,0,"R",0);
    $pdf->cell(30,$alt,"A PAGAR LIQUIDO",0,1,"R",0);
    $pdf->cell(0,$alt,'',"T",1,"C",0);
  
    
  }
  if ($funcao != $o58_funcao){
    if($funcao != ''){
    $pdf->ln(3);
    $pdf->cell(10,$alt,'',0,0,"L",0);
    $pdf->cell(30,$alt,'TOTAL DA FUNÇÃO ',0,0,"L",0,'.');
    $pdf->cell(30,$alt,db_formatar($totfuncadot_ini,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncasuplementado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncareduzido_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncadot_ini + $totfuncasuplementado_acumulado - $totfuncareduzido_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncaatual,'f'),0,1,"R",0);
    
    $pdf->cell(40,$alt,"",0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($totfuncaempenhado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncaanulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncaliquidado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncapago,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncaatual_a_pagar,'f'),0,1,"R",0);
    
    $pdf->cell(40,$alt,"",0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($totfuncaempenhado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncaanulado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncaliquidado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncapago_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($totfuncaatual_a_pagar_liquidado,'f'),0,1,"R",0);
    $pdf->setfont('arial','',7);
    }
    $pdf->ln(3);
    $pdf->setfont('arial','B',8);
    $pdf->cell(10,$alt,'FUNÇÃO :  '.$o58_funcao.'  -  '.$o52_descr,0,1,"L",0);
    $totfuncadot_ini                = 0; 
    $totfuncasuplementado_acumulado = 0;
    $totfuncareduzido_acumulado     = 0;
    $totfuncaatual                  = 0;
    $totfuncaempenhado              = 0;
    $totfuncaanulado                = 0;
    $totfuncaliquidado              = 0;
    $totfuncapago                   = 0;
    $totfuncaatual_a_pagar          = 0;
    $totfuncaempenhado_acumulado    = 0;
    $totfuncaanulado_acumulado      = 0;
    $totfuncaliquidado_acumulado    = 0;
    $totfuncapago_acumulado         = 0;
    $totfuncaatual_a_pagar_liquidado= 0;      
    $funcao = $o58_funcao;
  }
    $pdf->ln(3);
    $pdf->setfont('arial','B',8);
    $pdf->cell(10,$alt,'SUBFUNÇÃO :  '.$codigo.'  -  '.$descr,0,1,"L",0);
    $pdf->setfont('arial','',7);
    $pdf->cell(10,$alt,'',0,0,"L",0);
    $pdf->cell(30,$alt,'',0,0,"L",0,'.');
    $pdf->cell(30,$alt,db_formatar($dot_ini,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($suplementado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($reduzido_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($dot_ini + $totgeralsuplementado_acumulado - $totgeralreduzido_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($atual,'f'),0,1,"R",0);

    $pdf->cell(40,$alt,"",0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($empenhado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($anulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($liquidado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($pago,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($atual_a_pagar,'f'),0,1,"R",0);

    $pdf->cell(40,$alt,"",0,0,"L",0);
    $pdf->cell(30,$alt,db_formatar($empenhado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($anulado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($liquidado_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($pago_acumulado,'f'),0,0,"R",0);
    $pdf->cell(30,$alt,db_formatar($atual_a_pagar_liquidado,'f'),0,1,"R",0);
    $pdf->ln(3);

    $totfuncadot_ini                += $dot_ini;
    $totfuncasuplementado_acumulado += $suplementado_acumulado;
    $totfuncareduzido_acumulado     += $reduzido_acumulado;
    $totfuncaatual                  += $atual;
    $totfuncaempenhado              += $empenhado;
    $totfuncaanulado                += $anulado;
    $totfuncaliquidado              += $liquidado;
    $totfuncapago                   += $pago;
    $totfuncaatual_a_pagar          += $atual_a_pagar;
    $totfuncaempenhado_acumulado    += $empenhado_acumulado;
    $totfuncaanulado_acumulado      += $anulado_acumulado;
    $totfuncaliquidado_acumulado    += $liquidado_acumulado;
    $totfuncapago_acumulado         += $pago_acumulado;      
    $totfuncaatual_a_pagar_liquidado+= $atual_a_pagar_liquidado;      

    $totgeraldot_ini                += $dot_ini;
    $totgeralsuplementado_acumulado += $suplementado_acumulado;
    $totgeralreduzido_acumulado     += $reduzido_acumulado;
    $totgeralatual                  += $atual;
    $totgeralempenhado              += $empenhado;
    $totgeralanulado                += $anulado;
    $totgeralliquidado              += $liquidado;
    $totgeralpago                   += $pago;
    $totgeralatual_a_pagar          += $atual_a_pagar;
    $totgeralempenhado_acumulado    += $empenhado_acumulado;
    $totgeralanulado_acumulado      += $anulado_acumulado;
    $totgeralliquidado_acumulado    += $liquidado_acumulado;
    $totgeralpago_acumulado         += $pago_acumulado;      
    $totgeralatual_a_pagar_liquidado+= $atual_a_pagar_liquidado;      
//  }
  
}
$pdf->setfont('arial','b',7);

$pdf->ln(3);
$pdf->cell(10,$alt,'',0,0,"L",0);
$pdf->cell(30,$alt,'TOTAL GERAL ',0,0,"L",0,'.');
$pdf->cell(30,$alt,db_formatar($totgeraldot_ini,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralsuplementado_acumulado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralreduzido_acumulado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeraldot_ini + $totgeralsuplementado_acumulado - $totgeralreduzido_acumulado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralatual,'f'),0,1,"R",0);

$pdf->cell(40,$alt,"",0,0,"L",0);
$pdf->cell(30,$alt,db_formatar($totgeralempenhado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralanulado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralliquidado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralpago,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralatual_a_pagar,'f'),0,1,"R",0);

$pdf->cell(40,$alt,"",0,0,"L",0);
$pdf->cell(30,$alt,db_formatar($totgeralempenhado_acumulado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralanulado_acumulado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralliquidado_acumulado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralpago_acumulado,'f'),0,0,"R",0);
$pdf->cell(30,$alt,db_formatar($totgeralatual_a_pagar_liquidado,'f'),0,1,"R",0);
$pdf->setfont('arial','',7);



//pg_free_result($result);
//include("fpdf151/geraarquivo.php");
$pdf->Output();
?>