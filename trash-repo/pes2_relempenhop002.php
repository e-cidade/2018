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

$tipoemp = $sinana;
if ($sinana == '1'){
   $head3 = "EMPENHOS DE I.P.E.";
}elseif ($sinana == '2'){
   $head3 = "EMPENHOS DE I.N.S.S.";
}elseif ($sinana == '3'){
   $head3 = "EMPENHOS DE F.A.P.S.";
}elseif ($sinana == '4'){
   $head3 = "EMPENHOS DE INSS-4";
}

if ($folha == 'R14'){
   $xarquivo = 'Salário';
}elseif ($folha == 'R20'){  
   $xarquivo = 'Rescisão';
}elseif ($folha == 'R35'){  
   $xarquivo = '13o Salário';
}elseif ($folha == 'R22'){  
   $xarquivo = 'Adiantamento';
}elseif ($folha == 'R48'){  
   $xarquivo = 'Complementar';
}

$head5 = "Período : ".$mes." / ".$ano;
$head7 = "Arquivo : ".$xarquivo;
if ($tipo == "L")
   $xxtipo = "LOTAÇÃO";
else
   $xxtipo = "GERAL";
if ($sinana == "A")
   $xsinana = "ANALÍTICO";
else
   $xsinana = "SINTÉTICO";
   
$head8 = $xxtipo." - ".$xsinana;

if ($tipo == "L"){
    $sql =  "
              select r42_lotac, 
                     r13_descr,
          	   r13_descro,
          	   r42_proati,
          	   r42_elemen,
          	   r42_reduz,
          	   sum(r42_proven) as r42_proven,
          	   sum(r42_descon) as r42_descon
              from folhaemp 
                   left join elemento on r42_elemen = o18_codigo
                   inner join rhrubricas on r42_rubric = rh27_rubric 
          	 inner join lotacao on r13_codigo = r42_lotac 
          	        and r13_anousu = r42_anousu 
          		and r13_mesusu = r42_mesusu 
              where r42_anousu = $ano 
                and r42_mesusu = $mes
                and r42_tipo   = '$tipoemp'
                and r42_arqui  = '$folha'
                and r42_lotac between '".db_formatar($lotaini,'s','0',4,'e')."' and '".db_formatar($lotafin,'s','0',4,'e')."'
              group by r42_lotac, 
                       r13_descr,
          	     r13_descro,
          	     r42_proati,
          	     r42_elemen,
          	     r42_reduz 
              order by r42_lotac,
                       r42_elemen";
}else{
    $sql = "
             select r42_elemen,
	            o18_descr,
		    r42_proati,
		    r42_reduz,
		    sum(r42_proven) as r42_proven,
		    sum(r42_descon) as r42_descon 
             from folhaemp 
	          left join elemento on r42_elemen = o18_codigo
  	          inner join rhrubricas on r42_rubric = rh27_rubric 
	          inner join lotacao on r13_codigo = r42_lotac 
	                             and r13_anousu = r42_anousu 
				     and r13_mesusu = r42_mesusu 
	     where r42_anousu = $ano 
	       and r42_mesusu = $mes 
	       and r42_tipo   = '$tipoemp'
	       and r42_arqui  = '$folha'
	       and r42_lotac between '".db_formatar($lotaini,'s','0',4,'e')."' and '".db_formatar($lotafin,'s','0',4,'e')."'
	     group by r42_proati,r42_elemen,o18_descr,r42_reduz
	     order by r42_proati,r42_elemen,o18_descr,r42_reduz
           ";
     
}
//echo $sql ; exit;
$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem geração de empenhos no período de '.$mes.' / '.$ano);
}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
if ($tipo == "L"){
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $altura = 4;
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$altura,'LOTAÇÃO',1,0,"C",1);
      $pdf->cell(80,$altura,'UNIDADE',1,0,"C",1);
      $pdf->cell(80,$altura,'SECRETARIA',1,0,"C",1);
      $pdf->cell(30,$altura,'DESPESA',1,0,"C",1);
      $pdf->cell(10,$altura,'REDUZ.',1,0,"C",1);
      $pdf->cell(20,$altura,'PROVENTOS',1,0,"C",1);
      $pdf->cell(20,$altura,'DESCONTOS',1,0,"C",1);
      $pdf->cell(20,$altura,'TOTAL',1,1,"C",1);
      $totprov = 0;
      $totdesc = 0;
      
      for($x = 0;$x < pg_numrows($result);$x++){
         db_fieldsmemory($result,$x);
         if ($pdf->gety() > $pdf->h - 30){
            $pdf->addpage("L");
            $pdf->setfont('arial','b',8);
            $pdf->cell(15,$altura,'LOTAÇÃO',1,0,"C",1);
            $pdf->cell(80,$altura,'UNIDADE',1,0,"C",1);
            $pdf->cell(80,$altura,'SECRETARIA',1,0,"C",1);
            $pdf->cell(30,$altura,'DESPESA',1,0,"C",1);
            $pdf->cell(10,$altura,'REDUZ.',1,0,"C",1);
            $pdf->cell(20,$altura,'PROVENTOS',1,0,"C",1);
            $pdf->cell(20,$altura,'DESCONTOS',1,0,"C",1);
            $pdf->cell(20,$altura,'TOTAL',1,1,"C",1);
         }
         $pdf->setfont('arial','',7);
         $pdf->cell(15,$altura,$r42_lotac,0,0,"C",0);
         $pdf->cell(80,$altura,$r13_descr,0,0,"L",0);
         $pdf->cell(80,$altura,$r13_descro,0,0,"L",0);
         $pdf->cell(30,$altura,$r42_proati.' - '.$r42_elemen,0,0,"L",0);
         if ($r42_reduz != 0){
            $pdf->cell(10,$altura,$r42_reduz."-".db_CalculaDV($r42_reduz),0,0,"L",0);
         }else{
            $pdf->cell(10,$altura,"erro",0,0,"L",0);
         }
         $pdf->cell(20,$altura,db_formatar($r42_proven,'f'),0,0,"R",0);
         $pdf->cell(20,$altura,db_formatar($r42_descon,'f'),0,0,"R",0);
         $pdf->cell(20,$altura,db_formatar($r42_proven - $r42_descon,'f'),0,1,"R",0);
         $totprov += $r42_proven;
         $totdesc += $r42_descon;
      }
      $pdf->cell(15,$altura,'',"T",0,"C",0);
      $pdf->cell(80,$altura,'',"T",0,"L",0);
      $pdf->cell(80,$altura,'',"T",0,"L",0);
      $pdf->cell(30,$altura,'',"T",0,"L",0);
      $pdf->cell(10,$altura,'',"T",0,"L",0);
      $pdf->cell(20,$altura,db_formatar($totprov,'f'),"T",0,"R",0);
      $pdf->cell(20,$altura,db_formatar($totdesc,'f'),"T",0,"R",0);
      $pdf->cell(20,$altura,db_formatar($totprov - $totdesc,'f'),"T",1,"R",0);
}else{
      $pdf->addpage();
      $pdf->setfillcolor(235);
      $altura = 4;
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$altura,'ATIV.',1,0,"C",1);
      $pdf->cell(20,$altura,'ELEMENTO',1,0,"C",1);
      $pdf->cell(80,$altura,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(10,$altura,'REDUZ.',1,0,"C",1);
      $pdf->cell(20,$altura,'PROVENTOS',1,0,"C",1);
      $pdf->cell(20,$altura,'DESCONTOS',1,0,"C",1);
      $pdf->cell(20,$altura,'TOTAL',1,1,"C",1);
      $totprov = 0;
      $totdesc = 0;
      
      for($x = 0;$x < pg_numrows($result);$x++){
         db_fieldsmemory($result,$x);
         if ($pdf->gety() > $pdf->h - 30){
            $pdf->addpage();
            $pdf->setfont('arial','b',8);
            $pdf->cell(15,$altura,'ATIV.',1,0,"C",1);
            $pdf->cell(20,$altura,'ELEMENTO',1,0,"C",1);
            $pdf->cell(80,$altura,'DESCRIÇÃO',1,0,"C",1);
            $pdf->cell(10,$altura,'REDUZ.',1,0,"C",1);
            $pdf->cell(20,$altura,'PROVENTOS',1,0,"C",1);
            $pdf->cell(20,$altura,'DESCONTOS',1,0,"C",1);
            $pdf->cell(20,$altura,'TOTAL',1,1,"C",1);
         }
         $pdf->setfont('arial','',7);
         $pdf->cell(15,$altura,$r42_proati,0,0,"C",0);
         $pdf->cell(20,$altura,$r42_elemen,0,0,"L",0);
         $pdf->cell(80,$altura,$o18_descr,0,0,"L",0);
         if ($r42_reduz != 0){
            $pdf->cell(10,$altura,$r42_reduz."-".db_CalculaDV($r42_reduz),0,0,"L",0);
         }else{
            $pdf->cell(10,$altura,"erro",0,0,"L",0);
         }
         $pdf->cell(20,$altura,db_formatar($r42_proven,'f'),0,0,"R",0);
         $pdf->cell(20,$altura,db_formatar($r42_descon,'f'),0,0,"R",0);
         $pdf->cell(20,$altura,db_formatar($r42_proven - $r42_descon,'f'),0,1,"R",0);
         $totprov += $r42_proven;
         $totdesc += $r42_descon;
      }
      $pdf->cell(15,$altura,'',"T",0,"C",0);
      $pdf->cell(20,$altura,'',"T",0,"L",0);
      $pdf->cell(80,$altura,'',"T",0,"L",0);
      $pdf->cell(10,$altura,'',"T",0,"L",0);
      $pdf->cell(20,$altura,db_formatar($totprov,'f'),"T",0,"R",0);
      $pdf->cell(20,$altura,db_formatar($totdesc,'f'),"T",0,"R",0);
      $pdf->cell(20,$altura,db_formatar($totprov - $totdesc,'f'),"T",1,"R",0);
}
$pdf->Output();
?>