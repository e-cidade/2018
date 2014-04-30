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
$clrotulo = new rotulocampo;
$clrotulo->label('');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$head3 = "POSIÇÃO DA DÍVIDA";
$head5 = "$info : $val";
if ($info=='CGM'){
	$inner="inner join arrenumcgm on arrecad.k00_numpre = arrenumcgm.k00_numpre";
	$where="arrenumcgm.k00_numcgm=$val";
	$tab="arrenumcgm";
}elseif($info=="MATRÍCULA"){
	$inner="inner join arrematric on arrecad.k00_numpre = arrematric.k00_numpre";
	$where="arrematric.k00_matric=$val";
	$tab="arrematric";
}elseif($info=="INSCRIÇÃO"){
	$inner="inner join arreinscr on arrecad.k00_numpre = arreinscr_k00_numpre";
	$where="arreinscr.k00_inscr=$val";
	$tab="arreinscr";
}
$datausu=date('Y-m-d',db_getsession("DB_datausu"));
$anousu=date('Y',db_getsession("DB_datausu"));
$sql_norm="select    v01_exerc,
		        sum(substr(fc_calcula,2,13)::float8) as vlrhis,
                sum(substr(fc_calcula,15,13)::float8) as vlrcor,
                sum(substr(fc_calcula,28,13)::float8) as vlrjuros,
                sum(substr(fc_calcula,41,13)::float8) as vlrmulta,
                sum(substr(fc_calcula,54,13)::float8) as vlrdesconto,
                sum((substr(fc_calcula,15,13)::float8+
                substr(fc_calcula,28,13)::float8+
                substr(fc_calcula,41,13)::float8-
                substr(fc_calcula,54,13)::float8)) as total
      from (  select v01_exerc,fc_calcula(k00_numpre,0,0,current_date,current_date,$anousu)
	  		from (select distinct v01_exerc,arrecad.k00_numpre 		  
	  		from $tab
	  		inner join arrecad on arrecad.k00_numpre=$tab.k00_numpre
		    inner join divida on arrecad.k00_numpre=v01_numpre and arrecad.k00_numpar=v01_numpar and v01_instit = ".db_getsession('DB_instit')." 
		    where $where )as y
	       ) as x group by v01_exerc";
$sql_parc="select v07_parcel,
		        sum(substr(fc_calcula,2,13)::float8) as vlrhis,
                sum(substr(fc_calcula,15,13)::float8) as vlrcor,
                sum(substr(fc_calcula,28,13)::float8) as vlrjuros,
                sum(substr(fc_calcula,41,13)::float8) as vlrmulta,
                sum(substr(fc_calcula,54,13)::float8) as vlrdesconto,
                sum((substr(fc_calcula,15,13)::float8+
                substr(fc_calcula,28,13)::float8+
                substr(fc_calcula,41,13)::float8-
                substr(fc_calcula,54,13)::float8)) as total
      from (  
	  		select v07_parcel,fc_calcula(v07_numpre,0,0,current_date,current_date,$anousu) from  		  
	  		(select distinct v07_parcel,v07_numpre from $tab
        inner join termo on v07_numpre=$tab.k00_numpre	  		
				                and v07_instit = ".db_getsession('DB_instit')." 
		    inner join termodiv on v07_parcel=parcel
		    inner join divida on coddiv=v01_coddiv
				                 and v01_instit = ".db_getsession('DB_instit')." 
		    inner join arrecad on arrecad.k00_numpre=v07_numpre
		    where $where)as y			    						
	  		)as x group by v07_parcel";

//die($sql_norm);
//die($sql_parc);	  		

$result_norm=pg_query($sql_norm);
$result_parc=pg_query($sql_parc);
if (pg_numrows($result_norm)==0&&pg_numrows($result_parc)==0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem dividas.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$pdf->addpage();
$pag = 1;
$p=0;
$totalreg = 0;
$totalhis = 0;
$totalcor = 0;
$totaljur = 0;
$totalmul = 0;
$totaldesc = 0;
$totalval = 0;
for($x = 0; $x < pg_numrows($result_norm);$x++){
   db_fieldsmemory($result_norm,$x);
   if (($pdf->gety() > $pdf->h - 30) || $pag == 1){
   	 $pdf->setfont('arial','b',10);
     $pdf->Cell(175,7,'DÍVIDA NORMAL',0,1,"C",0);
     $pdf->setfont('arial','b',8);
     $pdf->cell(25,5,'Ano',1,0,"C",1);
     $pdf->cell(25,5,'Historico',1,0,"L",1);
     $pdf->cell(25,5,'Corrigido',1,0,"L",1);
     $pdf->cell(25,5,'Juros',1,0,"L",1);
     $pdf->cell(25,5,'Multa',1,0,"L",1);
     $pdf->cell(25,5,'Desconto',1,0,"L",1);
     $pdf->cell(25,5,'Total',1,1,"L",1);
     $pag = 0;
     $p=0;
  }
  $pdf->SetFont('Arial','',7);
  $pdf->cell(25,5,$v01_exerc,0,0,"C",$p);
  $pdf->cell(25,5,db_formatar($vlrhis,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($vlrcor,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($vlrjuros,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($vlrmulta,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($vlrdesconto,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($total,'f'),0,1,"R",$p);
  if ($p==0){
  	$p=1;
  }else{
  	$p=0;
  }
  $totalreg += 1;
  $totalhis += $vlrhis;
  $totalcor += $vlrcor;
  $totaljur += $vlrjuros;
  $totalmul += $vlrmulta;
  $totalval += $total;
}			
$pdf->SetFont('Arial','B',7);
$pdf->Cell(175,7,'Total de Registros : '.$totalreg,'T',1,"R",0);
$pdf->cell(25,7,"Totais",'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totalhis,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totalcor,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totaljur,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totalmul,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totaldesc,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totalval,'f'),'T',1,"R",0);
//-------------------------------------------------------------------------
$pag = 1;
$p=0;
$totalreg = 0;
$totalhis = 0;
$totalcor = 0;
$totaljur = 0;
$totalmul = 0;
$totaldesc = 0;
$totalval = 0;
for($x = 0; $x < pg_numrows($result_parc);$x++){
   db_fieldsmemory($result_parc,$x);
   if (($pdf->gety() > $pdf->h - 30) || $pag == 1){
   	 $pdf->setfont('arial','b',10);
     $pdf->Cell(175,7,'DÍVIDA PARCELADA',0,1,"C",0);
     $pdf->setfont('arial','b',8);
     $pdf->cell(25,5,'Cod. Parc.',1,0,"C",1);
     $pdf->cell(25,5,'Historico',1,0,"L",1);
     $pdf->cell(25,5,'Corrigido',1,0,"L",1);
     $pdf->cell(25,5,'Juros',1,0,"L",1);
     $pdf->cell(25,5,'Multa',1,0,"L",1);
     $pdf->cell(25,5,'Desconto',1,0,"L",1);
     $pdf->cell(25,5,'Total',1,1,"L",1);
     $pag = 0;
     $p=0;
  }
  $pdf->SetFont('Arial','',7);
  $pdf->cell(25,5,$v07_parcel,0,0,"C",$p);
  $pdf->cell(25,5,db_formatar($vlrhis,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($vlrcor,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($vlrjuros,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($vlrmulta,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($vlrdesconto,'f'),0,0,"R",$p);
  $pdf->cell(25,5,db_formatar($total,'f'),0,1,"R",$p);
  if ($p==0){
  	$p=1;
  }else{
  	$p=0;
  }
  $totalreg += 1;
  $totalhis += $vlrhis;
  $totalcor += $vlrcor;
  $totaljur += $vlrjuros;
  $totalmul += $vlrmulta;
  $totalval += $total;
}			
$pdf->SetFont('Arial','B',7);
$pdf->Cell(175,7,'Total de Registros : '.$totalreg,'T',1,"R",0);
$pdf->cell(25,7,"Totais:",'T',0,"C",0);
$pdf->cell(25,7,db_formatar($totalhis,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totalcor,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totaljur,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totalmul,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totaldesc,'f'),'T',0,"R",0);
$pdf->cell(25,7,db_formatar($totalval,'f'),'T',1,"R",0);
$pdf->Output();
?>