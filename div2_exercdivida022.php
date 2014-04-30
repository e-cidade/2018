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

include("libs/db_sql.php");
include("fpdf151/pdf.php");

$clrotulo = new rotulocampo;
$clrotulo->label('');

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$head2 = 'RELATÓRIO DA DÍVIDA POR EXERCÍCIO';
$head5 = '';
$head6 = '';

$where="";

if(isset($exerc)){
  $where = ' v01_exerc in ('.str_replace("-",",",$exerc).') ';
}

$datausu=date('Y-m-d',db_getsession("DB_datausu"));
$anousu=date('Y',db_getsession("DB_datausu"));
/* 
$sql="select v01_exerc, fc_calcula(k00_numpre,k00_numpar,k00_receit,'$datausu','".db_vencimento($datausu)."',$anousu) 		  
	  		from divida
		    inner join arrecad on arrecad.k00_numpre=v01_numpre and arrecad.k00_numpar=v01_numpar
	  		where $where";
	  		die($sql);
	  		*/
$sql="select    v01_exerc,
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
	  		select v01_exerc, fc_calcula(k00_numpre,k00_numpar,k00_receit,'$datausu','".db_vencimento($datausu)."',$anousu) 		  
	  		from divida
		    inner join arrecad on arrecad.k00_numpre=v01_numpre and arrecad.k00_numpar=v01_numpar
	  		where $where)
	  as x group by v01_exerc";

die($sql);
$result=pg_query($sql);

if (pg_numrows($result)==0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}	  

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->SetFillColor(220);
$pdf->SetFont('Arial','B',7);
$pag = 1;
$p=0;
$totalreg = 0;
$totalhis = 0;
$totalcor = 0;
$totaljur = 0;
$totalmul = 0;
$totaldesc = 0;
$totalval = 0;

for ($x = 0 ; $x < pg_numrows($result);$x++){
  db_fieldsmemory($result,$x);


  if (($pdf->gety() > $pdf->h - 30) || $pag == 1){
     $pdf->addpage();
     $pdf->cell(20,5,'Ano',1,0,"L",1);
     $pdf->cell(20,5,'Historico',1,0,"L",1);
     $pdf->cell(20,5,'Corrigido',1,0,"L",1);
     $pdf->cell(20,5,'Juros',1,0,"L",1);
     $pdf->cell(20,5,'Multa',1,0,"L",1);
     $pdf->cell(20,5,'Desconto',1,0,"L",1);
     $pdf->cell(20,5,'Total',1,1,"L",1);
     $pag = 0;
     $p=0;
  
  }
  $pdf->SetFont('Arial','',7);
  
  $pdf->cell(20,5,$v01_exerc,0,0,"R",$p);
  $pdf->cell(20,5,db_formatar($vlrhis,'f'),0,0,"R",$p);
  $pdf->cell(20,5,db_formatar($vlrcor,'f'),0,0,"R",$p);
  $pdf->cell(20,5,db_formatar($vlrjuros,'f'),0,0,"R",$p);
  $pdf->cell(20,5,db_formatar($vlrmulta,'f'),0,0,"R",$p);
  $pdf->cell(20,5,db_formatar($vlrdesconto,'f'),0,0,"R",$p);
  $pdf->cell(20,5,db_formatar($total,'f'),0,1,"R",0);
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
$pdf->Cell(140,7,'Total de Registros : '.$totalreg,'T',0,"R",0);
$pdf->cell(20,7,"",'T',0,"R",0);
$pdf->cell(20,7,db_formatar($totalhis,'f'),'T',0,"R",0);
$pdf->cell(20,7,db_formatar($totalcor,'f'),'T',0,"R",0);
$pdf->cell(20,7,db_formatar($totaljur,'f'),'T',0,"R",0);
$pdf->cell(20,7,db_formatar($totalmul,'f'),'T',0,"R",0);
$pdf->cell(20,7,db_formatar($totaldesc,'f'),'T',0,"R",0);
$pdf->cell(20,7,db_formatar($totalval,'f'),'T',1,"R",0);
			
$pdf->Output();