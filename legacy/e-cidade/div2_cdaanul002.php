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
include("classes/db_acertid_classe.php");

$clacertid =  new cl_acertid;
$clrotulo = new rotulocampo;
$clrotulo->label('');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//db_postmemory($HTTP_SERVER_VARS,2);exit;


$where = " v15_instit = ".db_getsession('DB_instit');
$and = "";
if (($data != "--") && ($data1 != "--")) {
	$where = $where." and v15_data  between '$data' and '$data1'  ";
	$data = db_formatar($data, "d");
	$data1 = db_formatar($data1, "d");
	$info = "De $data até $data1.";
	$and = " and " ;
} else if ($data != "--") {
	$where = $where." and v15_data >= '$data'  ";
	$data = db_formatar($data, "d");
	$info = "Apartir de $data.";
	$and = " and " ;
} else if ($data1 != "--") {
	$where = $where."and v15_data <= '$data1'   ";
	$data1 = db_formatar($data1, "d");
	$info = "Até $data1.";
	$and = " and " ;
}
if (isset($exercini)&&isset($exercfim)){
	if ($exercini!=""&&$exercfim!=""){
		$where .= "and v01_exerc between $exercini and $exercfim  "; 
		$and = " and " ;
		$info2 = "Do Exercício $exercini até $exercfim. ";
	}else if ($exercini!=""){
		$where .= " and v01_exerc >= $exercini   "; 
		$and = " and " ;
 	  $info2 = "Apartir do Exercício $exercini.";
	}else if ($exercfim!=""){
	  $where .= " and v01_exerc <= $exercfim  "; 
		$and = " and " ;
		$info2 = "Até o Exercício $exercfim.";
	}
}
if ($tipocda=="d"){
	$where .= " and acertdiv.v14_coddiv is not null  ";
	$and = " and " ;
	$info1 = "DIVIDA";
	$label_tip = "Dívida";
}else if ($tipocda=="p"){
	$where .= " and acertter.v14_parcel is not null  ";
	$and = " and " ;
	$info1 = "PARCELAMENTO";
	$label_tip = "Parc.";
}


$head3 = "CDA'S DE $info1 ANULADAS ";
$head4 = @$info;
$head5 = @$info2;
$result = $clacertid->sql_record($clacertid->sql_query_info(null,"distinct v15_certid,v15_data,v14_coddiv,v01_exerc,v14_parcel,v15_parcial,nome",null,$where));
$numrows = $clacertid->numrows;

if ($numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
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
$tipo = "";
for($x = 0; $x < $numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(30,$alt,"CDA",1,0,"C",1);
      $pdf->cell(30,$alt,"Dt. Anul.",1,0,"C",1); 
      $pdf->cell(30,$alt,"$label_tip",1,0,"C",1); 
      $pdf->cell(30,$alt,"Tipo",1,0,"C",1); 
      $pdf->cell(60,$alt,"Usuário",1,1,"C",1); 
      $troca = 0;
			$p=0;
   }
	 if ($v15_parcial=='t'){
		 $tipo = "Parcial";
	 }else if ($v15_parcial=='f'){
		 $tipo = "Total";
	 }
	 $exerc = "";
   $pdf->setfont('arial','',7);
   $pdf->cell(30,$alt,$v15_certid,0,0,"C",$p);
   $pdf->cell(30,$alt,db_formatar($v15_data,"d"),0,0,"C",$p);
	 if ($v14_coddiv!=""){
	    if ($v01_exerc!=""){
				$exerc = "/$v01_exerc";
		  }
			$pdf->cell(30,$alt,$v14_coddiv.$exerc,0,0,"C",$p);
	 }else if ($v14_parcel!=""){
			$pdf->cell(30,$alt,$v14_parcel,0,0,"C",$p);
	 }else{
			$pdf->cell(30,$alt,"",0,0,"C",$p);
	 }
   $pdf->cell(30,$alt,$tipo,0,0,"L",$p);
   $pdf->cell(60,$alt,$nome,0,1,"L",$p);
	 if ($p==1)$p=0;
		 else $p=1;
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(180,$alt,'TOTAL DE REGISTROS : '.$total,"T",0,"L",0);
$pdf->Output();
?>