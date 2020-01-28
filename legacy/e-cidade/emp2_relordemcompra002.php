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
include("classes/db_matordem_classe.php");
include("classes/db_matordemitem_classe.php");

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$clmatordem = new cl_matordem;
$clmatordemitem = new cl_matordemitem;

$clmatordem->rotulo->label();
$clmatordemitem->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('descrdepto');
$clrotulo->label('z01_nome');
$clrotulo->label('e60_codemp');
$clrotulo->label('pc01_codmater');
$clrotulo->label('pc01_descrmater');
$clrotulo->label('e62_descr');







$erro=0;
$info1="";
if ($ordem=='a'){
  $ordem='m51_codordem'; 
  $info1='Ordem por Cod.Ordem';
}else if ($ordem=='b'){
  $ordem='m51_depto'; 
  $info1='Ordem por Departamento';
}else if ($ordem=='c'){
  $ordem='m51_numcgm';
  $info1='Ordem por Fornecedor';
}else if ($ordem=='d'){
  $ordem='m51_data';
  $info1='Ordem por Data';
}

$where1='and 1=1 ';
$info="Periodo Total";
if ($opcao=='a'){
  $where='1=1';  
  $info2='Opção de impressão: Todas';
}else if ($opcao=='b'){
  $where='m53_data is null';
  $info2='Opção de impressão: Não Anuladas';
}else if ($opcao=='c'){
  $where='m53_data is not null';
  $info2='Opção de impressão: Anuladas';
}
$txt_where="and 1=1";
if ($listadepart!=""){
  if (isset($verdepart) and $verdepart=="com"){
    $txt_where= $txt_where." and m51_depto in  ($listadepart)";
  } else {
    $txt_where= $txt_where." and m51_depto not in  ($listadepart)";
  }	 
}  
if ($listacgm!=""){
  if (isset($vercgm) and $vercgm=="com"){
    $txt_where= $txt_where." and m51_numcgm in  ($listacgm)";
  } else {
    $txt_where= $txt_where." and m51_numcgm not in  ($listacgm)";
  }	 
}  
if ($listaitens!=""){
  if (isset($veritens) and $veritens=="com"){
    $where1=" and pc01_codmater in  ($listaitens)";
  } else {
    $where1=" and pc01_codmater not in  ($listaitens)";
  }	 
}  

if (($data!="--")&&($data1!="--")) {
  $txt_where = $txt_where." and m51_data  between '$data' and '$data1'  ";
  $data=db_formatar($data,"d");
  $data1=db_formatar($data1,"d");
  $info="De $data até $data1.";
} else if ($data!="--"){
  $txt_where = $txt_where." and m51_data >= '$data'  ";
  $data=db_formatar($data,"d");
  $info="Apartir de $data.";
} else if ($data1!="--"){
  $txt_where = $txt_where."and m51_data <= '$data1'   ";  
  $data1=db_formatar($data1,"d");
  $info="Até $data1.";
}

$inicial="";

$final="";

if (isset($m51_codordemINI) && $m51_codordemINI!="" ){
  $inicial=$m51_codordemINI;
}


if (isset($m51_codordemFIN) && $m51_codordemFIN!="" ){
  $final=$m51_codordemFIN;
}


if (($inicial!="")&&($final!="")) {
  $txt_where = $txt_where." and m51_codordem  between '$inicial' and '$final'  ";
} else if ($inicial!=""){
  $txt_where = $txt_where." and m51_codordem >= '$inicial'  ";
} else if ($final!=""){
  $txt_where = $txt_where."and m51_codordem <= '$final'   ";  
}



$head3 = "Ordens de Compra";
$head4 = "$info";
$head5 = "$info1";
$head6 = "$info2";



$result=$clmatordem->sql_record($clmatordem->sql_query_anu(null,'distinct m51_codordem,m51_depto,m51_data,m51_numcgm,m51_valortotal,z01_nome,descrdepto,m53_data',$ordem,"$where $txt_where "));


if ($clmatordem->numrows == 0){

   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros  cadastrados.');
}
      
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
for($x = 0; $x < $clmatordem->numrows;$x++){
   db_fieldsmemory($result,$x);
   $result_itens = $clmatordemitem->sql_record($clmatordemitem->sql_query(null,'*',null,"m52_codordem=$m51_codordem $where1"));
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,'Codigo',1,0,"C",1);
      $pdf->cell(20,$alt,'Data',1,0,"C",1);
      $pdf->cell(20,$alt,'Depart.',1,0,"C",1);
      $pdf->cell(70,$alt,$RLdescrdepto,1,0,"C",1);
      $pdf->cell(20,$alt,$RLm51_numcgm,1,0,"C",1);
      $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(25,$alt,'Data Anulação',1,0,"C",1);
      $pdf->cell(30,$alt,$RLm51_valortotal,1,1,"C",1); 
      $pdf->cell(20,$alt,'Cod. Lanc.',1,0,"C",1);
      $pdf->cell(20,$alt,$RLe60_codemp,1,0,"C",1);
      $pdf->cell(20,$alt,$RLm52_numemp,1,0,"C",1);
      $pdf->cell(20,$alt,$RLpc01_codmater,1,0,"C",1);
      $pdf->cell(60,$alt,$RLpc01_descrmater,1,0,"C",1);
      $pdf->cell(20,$alt,$RLm52_sequen,1,0,"C",1);
      $pdf->cell(55,$alt,$RLe62_descr,1,0,"C",1);
      $pdf->cell(20,$alt,'Valor Uni.',1,0,"C",1);
      $pdf->cell(20,$alt,$RLm52_quant,1,0,"C",1);
      $pdf->cell(20,$alt,'Valor',1,1,"C",1);
      $troca = 0;
   }
   if ($clmatordemitem->numrows==0){
     $erro++;
   }else{
   $pdf->setfont('arial','b',8);
   $pdf->cell(20,$alt,$m51_codordem,0,0,"C",0);
   $pdf->cell(20,$alt,db_formatar($m51_data,'d'),0,0,"C",0);
   $pdf->cell(20,$alt,$m51_depto,0,0,"C",0);
   $pdf->cell(70,$alt,$descrdepto,0,0,"L",0);
   $pdf->cell(20,$alt,$m51_numcgm,0,0,"C",0);
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",0);
   $pdf->cell(25,$alt,$m53_data,0,0,"C",0);
   $pdf->cell(30,$alt,db_formatar($m51_valortotal,'f'),0,1,"R",0); 
   $total++;
   for($i = 0; $i < $clmatordemitem->numrows;$i++){
     db_fieldsmemory($result_itens,$i);
     if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
       $pdf->addpage('L');
       $pdf->setfont('arial','b',8);
       $pdf->cell(20,$alt,'Codigo',1,0,"C",1);
       $pdf->cell(20,$alt,'Data',1,0,"C",1);
       $pdf->cell(20,$alt,'Depart.',1,0,"C",1);
       $pdf->cell(70,$alt,$RLdescrdepto,1,0,"C",1);
       $pdf->cell(20,$alt,$RLm51_numcgm,1,0,"C",1);
       $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
       $pdf->cell(25,$alt,'Data Anulação',1,0,"C",1);
       $pdf->cell(30,$alt,$RLm51_valortotal,1,1,"C",1); 
       $pdf->cell(20,$alt,'Cod. Lanc.',1,0,"C",1);
       $pdf->cell(20,$alt,$RLe60_codemp,1,0,"C",1);
       $pdf->cell(20,$alt,$RLm52_numemp,1,0,"C",1);
       $pdf->cell(20,$alt,$RLpc01_codmater,1,0,"C",1);
       $pdf->cell(60,$alt,$RLpc01_descrmater,1,0,"C",1);
       $pdf->cell(20,$alt,$RLm52_sequen,1,0,"C",1);
       $pdf->cell(55,$alt,$RLe62_descr,1,0,"C",1);
       $pdf->cell(20,$alt,'Valor Uni.',1,0,"C",1);
       $pdf->cell(20,$alt,$RLm52_quant,1,0,"C",1);
       $pdf->cell(20,$alt,'Valor',1,1,"C",1);
       
       
       $troca = 0;
     }
     $valoruni=$m52_valor/$m52_quant;
     $pdf->setfont('arial','',7);
     $pdf->cell(20,$alt,$m52_codlanc,0,0,"C",0);
     $pdf->cell(20,$alt,$e60_codemp,0,0,"C",0);
     $pdf->cell(20,$alt,$m52_numemp,0,0,"C",0);
     $pdf->cell(20,$alt,$pc01_codmater,0,0,"C",0);
     $pdf->cell(60,$alt,$pc01_descrmater,0,0,"L",0);
     $pdf->cell(20,$alt,$m52_sequen,0,0,"C",0);
     $pdf->cell(55,$alt,$e62_descr,0,0,"L",0);
     $pdf->cell(20,$alt,db_formatar($valoruni,'f'),0,0,"R",0);
     $pdf->cell(20,$alt,$m52_quant,0,0,"C",0);
     $pdf->cell(20,$alt,db_formatar($m52_valor,'f'),0,1,"R",0);
   }
   $num=$clmatordem->numrows - 1;
   
   if ($x==$num){
   }else{
     $pdf->cell(275,$alt,"","T",1,"C",0);
   }
   }
}
if ($erro==$clmatordem->numrows){
  db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros  cadastrados.');
}

$pdf->setfont('arial','b',8);
$pdf->cell(275,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);

//---------------------------------------------------------------------------------------
//  TOTALIZAÇÕES

if ($depart=="true"){
  $result_totdep=$clmatordem->sql_record($clmatordem->sql_query_tot(null,'m51_depto,descrdepto,sum(m51_valortotal) as valortotal ',null,"$where $txt_where group by m51_depto,descrdepto "));
  $troca=1;
  $totaldepto=0;
  $valordepto=0;
  $p=0;
  for($i = 0; $i < $clmatordem->numrows;$i++){
    db_fieldsmemory($result_totdep,$i);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(210,$alt,'Totalização por Departamentos',1,1,"C",1);
      $pdf->cell(70,$alt,'Depart.',1,0,"C",1);
      $pdf->cell(70,$alt,$RLdescrdepto,1,0,"C",1);
      $pdf->cell(70,$alt,'Valor',1,1,"C",1);
      $troca = 0;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(70,$alt,$m51_depto,0,0,"C",$p);
     $pdf->cell(70,$alt,$descrdepto,0,0,"L",$p);
     $pdf->cell(70,$alt,db_formatar($valortotal,'f'),0,1,"R",$p);
     $totaldepto++;
     $valordepto+=$valortotal;
     
    
     if($p==0){
       $p=1;
     }else $p=0;
  }
  
  $pdf->setfont('arial','b',8);
  $pdf->cell(105,$alt,'TOTAL DE DEPARTAMENTOS  :  '.$totaldepto,"T",0,"L",0);
  $pdf->cell(105,$alt,'TOTAL   :  '.db_formatar($valordepto,'f'),"T",0,"R",1);
  
}
if ($forn=="true"){
  $result_totcgm=$clmatordem->sql_record($clmatordem->sql_query_tot(null,'m51_numcgm,z01_nome,sum(m51_valortotal) as valortotal ',null,"$where $txt_where group by m51_numcgm,z01_nome "));
  $troca=1;
  $totalcgm=0;
  $valorcgm=0;
  $p=0;
  for($i = 0; $i < $clmatordem->numrows;$i++){
    db_fieldsmemory($result_totcgm,$i);
    if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage('L');
      $pdf->setfont('arial','b',8);
      $pdf->cell(210,$alt,'Totalização por Fornecedor',1,1,"C",1);
      $pdf->cell(70,$alt,'Fornecedor',1,0,"C",1);
      $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(70,$alt,'Valor',1,1,"C",1);
      $troca = 0;
     }
     $pdf->setfont('arial','',7);
     $pdf->cell(70,$alt,$m51_numcgm,0,0,"C",$p);
     $pdf->cell(70,$alt,$z01_nome,0,0,"L",$p);
     $pdf->cell(70,$alt,db_formatar($valortotal,'f'),0,1,"R",$p);
     $totalcgm++;
     $valorcgm+=$valortotal;
     
    
     if($p==0){
       $p=1;
     }else $p=0;
  }
  
  $pdf->setfont('arial','b',8);
  $pdf->cell(105,$alt,'TOTAL DE FORNECEDORES :  '.$totalcgm,"T",0,"L",0);
  $pdf->cell(105,$alt,'TOTAL   :  '.db_formatar($valorcgm,'f'),"T",0,"R",1);
  
}
if ($itens=="true"){
  $troca=1;
  $totalitens=0;
  $valoritens=0;
  $p=0;
/*  $result_totitem=$clmatordem->sql_record($clmatordem->sql_query_anu(null,'distinct m51_codordem,m51_depto,m51_data,m51_numcgm,m51_valortotal,z01_nome,descrdepto,m53_data',$ordem,"$where $txt_where "));
  for($i = 0; $i < $clmatordem->numrows;$i++){
    db_fieldsmemory($result_totitem,$i);*/
    //$result_totitens = $clmatordemitem->sql_record($clmatordemitem->sql_query(null,'pc01_descrmater,pc01_codmater,sum(m52_valor) as valortotal',null,"m52_codordem=$m51_codordem $where1 $where $txt_where group by pc01_descrmater,pc01_codmater "));
    $sql=" select pc01_descrmater,
                  pc01_codmater,
	          sum(m52_valor) as valortotal 
	   from matordemitem 
	          inner join empempitem on empempitem.e62_numemp = matordemitem.m52_numemp and empempitem.e62_sequen = matordemitem.m52_sequen 
		  inner join matordem on matordem.m51_codordem = matordemitem.m52_codordem 
		  inner join orcelemento on orcelemento.o56_codele = empempitem.e62_codele 
		                        and orcelemento.o56_anousu = ".db_getsession("DB_anousu")."
		  inner join pcmater on pcmater.pc01_codmater = empempitem.e62_item 
		  inner join empempenho on empempenho.e60_numemp = empempitem.e62_numemp 
		  inner join cgm on cgm.z01_numcgm = matordem.m51_numcgm 
		  inner join db_depart on db_depart.coddepto = matordem.m51_depto 
		  left join matordemanu on m53_codordem=m51_codordem  
	   where $where $txt_where $where1 group by pc01_descrmater,pc01_codmater";
    $result_totitens=pg_exec($sql);	   
    for($y = 0; $y < pg_numrows($result_totitens);$y++){
      db_fieldsmemory($result_totitens,$y);
      if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	$pdf->addpage('L');
	$pdf->setfont('arial','b',8);
	$pdf->cell(210,$alt,'Totalização por Itens',1,1,"C",1);
	$pdf->cell(70,$alt,$RLpc01_codmater,1,0,"C",1);
	$pdf->cell(70,$alt,$RLpc01_descrmater,1,0,"C",1);
	$pdf->cell(70,$alt,'Valor',1,1,"C",1);
	$troca = 0;
      }
      $pdf->setfont('arial','',7);
      $pdf->cell(70,$alt,$pc01_codmater,0,0,"C",$p);
      $pdf->cell(70,$alt,$pc01_descrmater,0,0,"L",$p);
      $pdf->cell(70,$alt,db_formatar($valortotal,'f'),0,1,"R",$p);
      $totalitens++;
      $valoritens+=$valortotal;
       
      if($p==0){
        $p=1;
      }else $p=0;
    }
  
  
  $pdf->setfont('arial','b',8);
  $pdf->cell(105,$alt,'TOTAL DE ITENS :  '.$totalitens,"T",0,"L",0);
  $pdf->cell(105,$alt,'TOTAL   :  '.db_formatar($valoritens,'f'),"T",0,"R",1);
  
}
$pdf->Output();
   
?>