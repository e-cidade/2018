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
 
$tipoemp = "M";

if ($classif == 'S'){
   $classificacao = ' and r42_reduz > 0 ';
}elseif ($classif == 'N'){
   $classificacao = ' and r42_reduz = 0 ';
}else{
   $classificacao = ' ';
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

$head3 = "EMPENHOS DA FOLHA DE PAGAMENTO";
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

if ($sinana == 'A'){
   if ($tipo == "L"){
    $sql = "
             select folhaemp.*,
    	            rh27_descr,
		    rh27_pd,
		    r13_descr 
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
	       $classificacao
	       and r42_lotac between '".db_formatar($lotaini,'s','0',4,'e')."' and '".db_formatar($lotafin,'s','0',4,'e')."'
	     order by r42_lotac,r42_elemen,r42_rubric
           ";
   }else{
    $sql = "
             select r42_elemen,
	            o18_descr,
	            r42_rubric,
    	            rh27_descr,
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
	       $classificacao
	       and r42_lotac between '".db_formatar($lotaini,'s','0',4,'e')."' and '".db_formatar($lotafin,'s','0',4,'e')."'
	     group by r42_elemen,o18_descr,r42_rubric,rh27_descr
	     order by r42_elemen,o18_descr,r42_rubric,rh27_descr
           ";
     
   }
}else{
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
		$classificacao
                and r42_lotac between '".db_formatar($lotaini,'s','0',4,'e')."' and '".db_formatar($lotafin,'s','0',4,'e')."'
              group by r42_lotac, 
                       r13_descr,
          	     r13_descro,
          	     r42_proati,
          	     r42_elemen,
          	     r42_reduz 
              order by 
	               r42_lotac,
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
	       $classificacao
	       and r42_lotac between '".db_formatar($lotaini,'s','0',4,'e')."' and '".db_formatar($lotafin,'s','0',4,'e')."'
	     group by r42_proati,r42_elemen,o18_descr,r42_reduz
	     order by r42_proati,r42_elemen,o18_descr,r42_reduz
           ";
     
   }
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
if($sinana == 'A'){

   if ($tipo == "L"){
     
      $pdf->addpage();
      $pdf->setfillcolor(235);
      $proventos = 0;
      $descontos = 0;
      $pdf->setfont('arial','b',8);
      db_fieldsmemory($result,0);
      
      $quebra = $r42_lotac+$r42_elemen;
      $pdf->cell(15,5,$r42_lotac." - ".strtoupper($r13_descr).'    Atividade : '.$r42_proati.'  -  '.$r42_elemen,0,1,"L",0);
      
      if ($r42_reduz != 0){
         $pdf->cell(100,5,"Dotacao : ".$r42_reduz."-".db_CalculaDV($r42_reduz),0,1,"L",0);
      }else{
         $pdf->cell(100,5,"erro",0,1,"L",0);
      }
      
      $pdf->cell(15,5,'RUBRICA',1,0,"C",1);
      $pdf->cell(60,5,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(15,5,'TIPO',1,0,"C",1);
      $pdf->cell(20,5,'EMPENHOS',1,0,"C",1);
      $pdf->cell(20,5,'DESCONTOS',1,1,"C",1);
      
      for($x = 0;$x < pg_numrows($result);$x++){
         db_fieldsmemory($result,$x);
         if ($quebra != $r42_lotac+$r42_elemen){
            $pdf->cell(15,5,'',"T",0,"C",0);
            $pdf->cell(75,5,'TOTAL',"T",0,"L",0);
            $pdf->cell(20,5,db_formatar($proventos,'f'),"T",0,"R",0);
            $pdf->cell(20,5,db_formatar($descontos,'f'),"T",1,"R",0);
            $pdf->cell(15,5,'',0,0,0,0);
            $pdf->cell(75,5,'LÍQUIDO',0,0,"L",0);
            $pdf->cell(20,5,'',0,0,"R",0);
            $pdf->cell(20,5,db_formatar($proventos - $descontos,'f'),0,1,"R",0);
            $proventos = 0;
            $descontos = 0;
            $quebra = $r42_lotac+$r42_elemen;
            $pdf->sety(290);
         }
         if ($pdf->gety() > $pdf->h -30){
            $pdf->addpage();
            $pdf->setfont('arial','b',8);
            $pdf->cell(15,5,$r42_lotac." - ".strtoupper($r13_descr).'    Atividade : '.$r42_proati.'  -  '.$r42_elemen,0,1,"L",0);
            if ($r42_reduz != 0){
               $pdf->cell(100,5,"Dotacao : ".$r42_reduz."-".db_CalculaDV($r42_reduz),0,1,"L",0);
            }else{
               $pdf->cell(100,5,"erro",0,1,"L",0);
            }
            $pdf->cell(15,5,'RUBRICA',1,0,"C",1);
            $pdf->cell(60,5,'DESCRIÇÃO',1,0,"C",1);
            $pdf->cell(15,5,'TIPO',1,0,"C",1);
            $pdf->cell(20,5,'EMPENHOS',1,0,"C",1);
            $pdf->cell(20,5,'DESCONTOS',1,1,"C",1);
         }
         $pdf->setfont('arial','',8);
         $pdf->cell(15,5,$r42_rubric,0,0,"C",0);
         $pdf->cell(60,5,$rh27_descr,0,0,"L",0);
         
         switch ($rh27_pd) {
         	case 1:
         	  $sTipo = "PROV.";
         	break;
          case 2:
            $sTipo = "DESC.";
          break;
          case 3:
            $sTipo = "BASE";
          break;                   	
         }
         
         $pdf->cell(15,5,$sTipo,0,0,"R",0);
         
         $pdf->cell(20,5,db_formatar($r42_proven,'f'),0,0,"R",0);
         $pdf->cell(20,5,db_formatar($r42_descon,'f'),0,1,"R",0);
         $proventos += $r42_proven;
         $descontos += $r42_descon;
         
      }
      $pdf->cell(15,5,'',"T",0,"C",0);
      $pdf->cell(75,5,'TOTAL',"T",0,"L",0);
      $pdf->cell(20,5,db_formatar($proventos,'f'),"T",0,"R",0);
      $pdf->cell(20,5,db_formatar($descontos,'f'),"T",1,"R",0);
      $pdf->cell(15,5,'',0,0,"C",0);
      $pdf->cell(75,5,'LÍQUIDO',0,0,"L",0);
      $pdf->cell(20,5,'',0,0,"R",0);
      $pdf->cell(20,5,db_formatar($proventos - $descontos,'f'),0,1,"R",0);
      $vencimentos = 0;
      $descontos   = 0;
      $total       = 0;
      $baseprev    = 0;
      $baseirf     = 0;
      $quebra      = $r42_lotac;
   }else{
      $pdf->addpage();
      $pdf->setfillcolor(235);
      $proventos = 0;
      $descontos = 0;
      $pdf->setfont('arial','b',8);
      db_fieldsmemory($result,0);
      
      $quebra = $r42_elemen;
      $pdf->cell(15,5,'Elemento : '.$r42_elemen.' - '.$o18_descr,0,1,"L",0);
      
      $pdf->cell(15,5,'RUBRICA',1,0,"C",1);
      $pdf->cell(60,5,'DESCRIÇÃO',1,0,"C",1);
      $pdf->cell(20,5,'EMPENHOS',1,0,"C",1);
      $pdf->cell(20,5,'DESCONTOS',1,0,"C",1);
      $pdf->cell(20,5,'TOTAL',1,1,"C",1);
      
      for($x = 0;$x < pg_numrows($result);$x++){
         db_fieldsmemory($result,$x);
         if ($quebra != $r42_elemen){
            $pdf->cell(75,5,'TOTAL',"T",0,"L",0);
            $pdf->cell(20,5,db_formatar($proventos,'f'),"T",0,"R",0);
            $pdf->cell(20,5,db_formatar($descontos,'f'),"T",0,"R",0);
            $pdf->cell(20,5,db_formatar($proventos - $descontos,'f'),"T",1,"R",0);
            $proventos = 0;
            $descontos = 0;
            $quebra = $r42_elemen;
            $pdf->sety(290);
         }
         if ($pdf->gety() > $pdf->h -30){
            $pdf->addpage();
            $pdf->setfont('arial','b',8);
            $pdf->cell(15,5,'Elemento : '.$r42_elemen.' - '.$o18_descr,0,1,"L",0);
            $pdf->cell(15,5,'RUBRICA',1,0,"C",1);
            $pdf->cell(60,5,'DESCRIÇÃO',1,0,"C",1);
            $pdf->cell(20,5,'EMPENHOS',1,0,"C",1);
            $pdf->cell(20,5,'DESCONTOS',1,0,"C",1);
            $pdf->cell(20,5,'TOTAL',1,1,"C",1);
         }
         $pdf->setfont('arial','',8);
         $pdf->cell(15,5,$r42_rubric,0,0,"C",0);
         $pdf->cell(60,5,$rh27_descr,0,0,"L",0);
         $pdf->cell(20,5,db_formatar($r42_proven,'f'),0,0,"R",0);
         $pdf->cell(20,5,db_formatar($r42_descon,'f'),0,0,"R",0);
         $pdf->cell(20,5,db_formatar($r42_proven - $r42_descon,'f'),0,1,"R",0);
         $proventos += $r42_proven;
         $descontos += $r42_descon;
         
      }
      $pdf->cell(75,5,'TOTAL',"T",0,"L",0);
      $pdf->cell(20,5,db_formatar($proventos,'f'),"T",0,"R",0);
      $pdf->cell(20,5,db_formatar($descontos,'f'),"T",0,"R",0);
      $pdf->cell(20,5,db_formatar($proventos - $descontos,'f'),"T",1,"R",0);
   
   }
}else{
   if ($tipo == "L"){
      $pdf->addpage("L");
      $pdf->setfillcolor(235);
      $altura = 4;
      $pdf->setfont('arial','b',8);
//      $pdf->cell(100,$altura,'ERROS DE CLASSIFICAÇÃO',0,1,"L",0);
      $pdf->cell(15,$altura,'LOTAÇÃO',1,0,"C",1);
      $pdf->cell(80,$altura,'UNIDADE',1,0,"C",1);
      $pdf->cell(80,$altura,'SECRETARIA',1,0,"C",1);
      $pdf->cell(30,$altura,'DESPESA',1,0,"C",1);
      $pdf->cell(10,$altura,'REDUZ.',1,0,"C",1);
      $pdf->cell(20,$altura,'EMPENHOS',1,0,"C",1);
      $pdf->cell(20,$altura,'DESCONTOS',1,0,"C",1);
      $pdf->cell(20,$altura,'TOTAL',1,1,"C",1);
      $totprov = 0;
      $totdesc = 0;
      
      $troca = 't';
      for($x = 0;$x < pg_numrows($result);$x++){
         db_fieldsmemory($result,$x);
//	 if ($troca == 't' && $r42_reduz != 0 ){
//	    $pdf->sety(300);
//	    $troca = 'f';
//	 }
         if ($pdf->gety() > $pdf->h - 30){
            $pdf->addpage("L");
            $pdf->setfont('arial','b',8);
            $pdf->cell(15,$altura,'LOTAÇÃO',1,0,"C",1);
            $pdf->cell(80,$altura,'UNIDADE',1,0,"C",1);
            $pdf->cell(80,$altura,'SECRETARIA',1,0,"C",1);
            $pdf->cell(30,$altura,'DESPESA',1,0,"C",1);
            $pdf->cell(10,$altura,'REDUZ.',1,0,"C",1);
            $pdf->cell(20,$altura,'EMPENHOS',1,0,"C",1);
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
      $pdf->cell(20,$altura,'EMPENHOS',1,0,"C",1);
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
            $pdf->cell(20,$altura,'EMPENHOS',1,0,"C",1);
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
}
$pdf->Output();
      
?>