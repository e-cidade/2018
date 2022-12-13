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


$head3 = "RELATÓRIO DE MATRÍCULAS";
///$head5 = "PERÍODO : ".$mes." / ".$ano;

$txt_where = "";
$and = "";
if ($bairros!=""){
  if (isset($verbairro) and $verbairro=="com"){
      $txt_where.=" $and j34_bairro in ($bairros) ";
      $and = " and ";
  } else {
      $txt_where.=" $and j34_bairro not in  ($bairros) ";
      $and = " and ";
  }	 
}  

if ($ruas!=""){
  if (isset($verrua) and $verrua=="com"){
      $txt_where.=" $and j14_codigo in ($ruas) ";
      $and = " and ";
  } else {
      $txt_where.=" $and j14_codigo not in  ($ruas) ";
      $and = " and ";
  }	 
}
if ($txt_where!=""){
	$txt_where = "where $txt_where";
}
$sql = "
	select count(*) as total,
		   sum(case when j01_baixa is not null then 1 else 0 end) as baixadas, 
		   sum(case when j01_baixa is null then 1 else 0 end) as nao_baixadas, 
		   sum(case when j01_tipoimp = 'Predial' and j01_baixa is not null then 1 else 0 end) as predial_bai, 
		   sum(case when j01_tipoimp = 'Territorial' and j01_baixa is not null then 1 else 0 end) as territorial_bai, 

		   sum(case when j01_tipoimp = 'Predial' then 1 else 0 end) as predial, 
		   sum(case when j01_tipoimp = 'Territorial' then 1 else 0 end) as territorial 

	from (select distinct j01_matric, j01_baixa, j01_tipoimp from proprietario $txt_where) as proprietario;
";

//echo $sql ; exit;

$result = pg_exec($sql);
$xxnum = pg_numrows($result);
if ($xxnum == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Matrículas cadastradas.');

}

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','',8);
$troca = 1;
$alt = 6;
for($x = 0; $x < pg_numrows($result);$x++){  
   db_fieldsmemory($result,$x); 
   if ($total==0){
	   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem Matrículas cadastradas.');
   }
   
   $pdf->addpage();
   $pdf->setfont('arial','b',9);
   $pdf->cell(0,10,'',0,1,"L",0);
      
   
   
   $pdf->setfillcolor(235);
   $pdf->cell(30,10,'',0,0,"L",0);
   $pdf->cell(70,$alt,'TOTAL DE MATRÍCULAS',0,0,"L",1);
   $pdf->cell(2,$alt,':',0,0,"L",1);
   $pdf->cell(15,$alt,$total,0,1,"R",1);
   
   $pdf->setfillcolor(230);
   $pdf->cell(30,10,'',0,0,"L",0);
   $pdf->cell(70,$alt,'TOTAL DE BAIXADAS',0,0,"L",1);
   $pdf->cell(2,$alt,':',0,0,"L",1);
   $pdf->cell(15,$alt,$baixadas,0,1,"R",1);
   
   $pdf->setfillcolor(235);
   $pdf->cell(30,10,'',0,0,"L",0);
   $pdf->cell(70,$alt,'TOTAL DE NÃO BAIXADAS',0,0,"L",1);
   $pdf->cell(2,$alt,':',0,0,"L",1);
   $pdf->cell(15,$alt,$nao_baixadas,0,1,"R",1);
   
   $pdf->setfillcolor(230);
   $pdf->cell(30,10,'',0,0,"L",0);
   $pdf->cell(70,$alt,'TOTAL DE PRED. BAIXADAS',0,0,"L",1);
   $pdf->cell(2,$alt,':',0,0,"L",1);
   $pdf->cell(15,$alt,$predial_bai,0,1,"R",1);
   
   $pdf->setfillcolor(235);
   $pdf->cell(30,10,'',0,0,"L",0);
   $pdf->cell(70,$alt,'TOTAL DE TERR. BAIXADAS',0,0,"L",1);
   $pdf->cell(2,$alt,':',0,0,"L",1);
   $pdf->cell(15,$alt,$territorial_bai,0,1,"R",1);
   
   $pdf->setfillcolor(230);
   $pdf->cell(30,10,'',0,0,"L",0);
   $pdf->cell(70,$alt,'TOTAL DE PREDIAIS',0,0,"L",1);
   $pdf->cell(2,$alt,':',0,0,"L",1);
   $pdf->cell(15,$alt,$predial,0,1,"R",1);
   
   $pdf->setfillcolor(235);
   $pdf->cell(30,10,'',0,0,"L",0);
   $pdf->cell(70,$alt,'TOTAL DE TERRITORIAIS',0,0,"L",1);
   $pdf->cell(2,$alt,':',0,0,"L",1);
   $pdf->cell(15,$alt,$territorial,0,1,"R",1);
}

$pdf->Output();
   
?>