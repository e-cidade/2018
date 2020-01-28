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
require("fpdf151/pdf.php");
include("classes/db_iptuconstr_classe.php");
include("classes/db_iptuconstrdemo_classe.php");
db_postmemory($HTTP_SERVER_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);exit;

$where = " where ";
$total = 0;
$and = "";
$alt = 5;
$borda = 0;
$corfundo = "";

if ($datai!="--" && $dataf!="--"){
	$where .= " $and j39_dtdemo between '".$datai."' and '".$dataf."' ";
	$and = " and ";
	$head1 = "Periodo de ".db_formatar($datai,'d')." a ".db_formatar($dataf,'d');
}else if($datai !="--" && $dataf == "--"){
    $where .= " $and j39_dtdemo >= '".$datai."' ";
    $head1 = "Periodo superior a ".db_formatar($datai,'d');
    $and = " and ";
}else if($datai =="--" && $dataf != "--"){
    $where .= " $and j39_dtdemo <= '".$dataf."' ";
    $head1 = "Periodo inferior a ".db_formatar($dataf,'d');
    $and = " and ";
}else if($datai =="--" && $dataf == "--"){
    $head1 = "Periodo Total";
}
if($tiporel == 't'){
	$where .= " $and tipo = 'Total' ";
	$head3 = 'Tipo : Total';
}else if($tiporel == 'p'){
	$where .= " $and tipo = 'Parcial' ";
	$head3 = 'Tipo : Parcial';
}else{
	$head3 = 'Tipo : Total e Parcial';
}

$sql = "
			select * from
                     (select proprietario.j01_matric,
                             proprietario.z01_nome,
                             j39_idcons,
                             j39_dtdemo,
                             j39_area,
                             j60_area,
                             j39_numero,
                             j39_compl,
                             j60_codproc,
                             proprietario.j14_nome,
                             case when parcial is false
                                    then 'Total'
                                    else 'Parcial'
                             end as tipo
                     from ( select j39_matric,
                                  j39_idcons,
                                  j39_dtdemo,
                                  '' as j60_codproc,
                                  'f'::boolean as parcial,
                                  j39_area,
                                  0 as j60_area
                           from   iptuconstr
                              where j39_dtdemo is not null
                   union
                          select    j60_matric,
                                    j60_idcons,
                                    j60_datademo as j39_dtdemo,
                                    j60_codproc,
                                    't'::boolean as parcial,
                                    j39_area,
                                    j60_area
                             from iptuconstrdemo
                                  inner join iptuconstr on j39_matric = j60_matric ) as x
                                  inner join proprietario on proprietario.j01_matric = j39_matric) as y
                                  $where
			 ";

//die($sql);
$rsResult = pg_query($sql); 
$numrows  = pg_num_rows($rsResult);
if ($numrows == 0){
    db_redireciona('db_erros.php?fechar=true&db_erro=Nao existem demoli??es para o filtro selecionado.');
    exit;
}

$pdf = new pdf();
$pdf->SetFillColor(255);
$pdf->Open();
$pdf->AliasNbPages();

$pdf->addpage("L");
$pdf->SetFillColor(210);
$pdf->setfont('arial','b',9);
$pdf->cell(15,$alt+1,"Matr.",1,0,"C",1);
$pdf->cell(70,$alt+1,"Nome",1,0,"C",1);
$pdf->cell(20,$alt+1,"Cod. Proc.",1,0,"C",1);
$pdf->cell(20,$alt+1,"ID constr",1,0,"C",1);
$pdf->cell(20,$alt+1,"Area demol.",1,0,"C",1);
$pdf->cell(20,$alt+1,"Area Total.",1,0,"C",1);
if($tiporel == 'a'){
   $pdf->cell(20,$alt+1,"Tipo",1,0,"C",1);
}
$pdf->cell(20,$alt+1,"Dt demol.",1,0,"C",1);
$pdf->cell(75,$alt+1,"Endere?o",1,1,"C",1);
$pdf->cell(70,3,"",0,1,"C",0);$pdf->cell(70,3,"",0,1,"C",0);
for($i = 0; $i < $numrows;$i++){
	db_fieldsmemory($rsResult,$i);
   if ($pdf->gety() > $pdf->h - 30){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',9);
      $pdf->SetFillColor(210);
      $pdf->cell(15,$alt+1,"Matr.",1,0,"C",1);
      $pdf->cell(70,$alt+1,"Nome",1,0,"C",1);
      $pdf->cell(20,$alt+1,"Cod. Proc.",1,0,"C",1);
	  $pdf->cell(20,$alt+1,"ID constr",1,0,"C",1);
	  $pdf->cell(20,$alt+1,"Area demol.",1,0,"C",1);
	  $pdf->cell(20,$alt+1,"Area Total.",1,0,"C",1);
	  if($tiporel == 'a'){
	  	   $pdf->cell(20,$alt+1,"Tipo",1,0,"C",1);
	  }
	  $pdf->cell(20,$alt+1,"Dt demol.",1,0,"C",1);
	  $pdf->cell(75,$alt+1,"Endere?o",1,1,"C",1);
	  $pdf->cell(70,3,"",0,1,"C",0);
   }
   $pdf->setfont('arial','',8);
   
   if($i % 2 == 0){
       $corfundo = 236;
   }else{
       $corfundo = 245;
   }
   
   $pdf->SetFillColor($corfundo);
   $pdf->cell(15,$alt,$j01_matric,$borda,0,"C",1);
   $pdf->cell(70,$alt,(strlen($z01_nome) > 38?substr($z01_nome,0,38)."...":$z01_nome),$borda,0,"L",1);
   $pdf->cell(20,$alt,(isset($j60_codproc)&&$j60_codproc!=0?$j60_codproc:"Sem proc."),$borda,0,"C",1);
   $pdf->cell(20,$alt,$j39_idcons,$borda,0,"C",1);
   $pdf->cell(20,$alt,($tipo=='Total'?db_formatar($j39_area,'f'):db_formatar($j60_area,'f')),$borda,0,"C",1);
   $pdf->cell(20,$alt,db_formatar($j39_area,'f'),$borda,0,"C",1);
   if($tiporel == 'a'){
   		$pdf->cell(20,$alt,$tipo,$borda,0,"C",1);
   }
   $pdf->cell(20,$alt,db_formatar($j39_dtdemo,'d'),$borda,0,"C",1);
   $pdf->cell(75,$alt,(strlen($j14_nome) > 25?substr($j14_nome,0,25)."... ":$j14_nome).(isset($j39_numero)&&$j39_numero!=""?", ".$j39_numero:"").(isset($j39_compl)&&$j39_compl!=""?"/".$j39_compl:""),$borda,1,"L",1);
   $total ++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,"TOTAL DE REGISTROS  :  $total",'T',0,"L",0);
$pdf->output();
?>