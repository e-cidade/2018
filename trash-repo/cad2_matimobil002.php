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
include("classes/db_cadimobil_classe.php");
include("classes/db_imobil_classe.php");
$clcadimobil = new cl_cadimobil;
$climobil = new cl_imobil;
$clcadimobil->rotulo->label();
$climobil->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('j01_matric');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$where = "";
if ($lista != "") {
	if (isset ($ver) and $ver == "com") {
		$where = "  j63_numcgm in  ($lista)";
	} else {
		$where = "  j63_numcgm not in  ($lista)";
	}
}

$head3 = "RELATÓRIO DE MATRÍCULA POR IMOBILIÁRIA";
if ($tipo=='a'){
	$head4 = "TIPO: Analítico";
}else if ($tipo=='s'){
	$head4 = "TIPO: Sintético";
}
$order_by = "";
if (isset($ordem)&&$ordem=='m'){
	$head5 = "ORDEM: Matrícula";
	$order_by = " j44_matric ";
}else if (isset($ordem)&&$ordem=='c'){
	$head5 = "ORDEM: Contribuinte";
	$order_by = " z01_nome ";
}
$result = $clcadimobil->sql_record($clcadimobil->sql_query(null,"cadimobil.j63_numcgm,cgm.z01_nome as nome_imb,z01_ender as ender_imb,z01_compl as compl_imb,z01_numero as numero_imb,z01_munic as munic_imb,z01_bairro as bairro_imb",null,$where));
if ($clcadimobil->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
   exit;
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
$numrows = $clcadimobil->numrows;
for($x = 0; $x < $numrows; $x++){	
   db_fieldsmemory($result,$x);
   if ($tipo=='s'){
	   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	      $pdf->addpage("");
	   }
	   $troca=1;
	   $pdf->setfont('arial','b',10);
	   $pdf->cell(160,$alt,$RLj63_numcgm.": ".$j63_numcgm." - ".$nome_imb,"B",1,"L",0);             
	   $result_imobil = $climobil->sql_record($climobil->sql_query_nome(null,"*",$order_by,"j44_numcgm=$j63_numcgm"));
	   $numrows_imobil = $climobil->numrows;
	   if ($numrows_imobil==0){
	   		$pdf->cell(160,$alt,"Não existem matrículas vinculadas.",0,1,"C",0);
	   		$pdf->setfont('arial','b',8);   
	   		$pdf->cell(160,$alt,'TOTAL DE MATRÍCULAS  :  '.$total,"T",0,"R",0);
	   		$pdf->Ln();
	   		$pdf->Ln();
	   		$troca = 0;
	   		continue;   	
	   }else{
		   $pdf->setfont('arial','b',8);
		   $pdf->cell(60,$alt,$RLj01_matric,1,0,"C",1);	      
		   $pdf->cell(100,$alt,$RLz01_nome,1,1,"C",1);
	   }
	   $troca = 0;
	   $p=0;
	   $total = 0;
	   for($w=0;$w<$numrows_imobil;$w++){  	
	   		db_fieldsmemory($result_imobil,$w);
	   		if ($pdf->gety() > $pdf->h - 30 ){
	      		$pdf->addpage("");      		
	      		$pdf->setfont('arial','b',10);
	      		$pdf->cell(160,$alt,$RLj63_numcgm.": ".$j63_numcgm." - ".$nome_imb,"B",1,"L",0);
	      		$pdf->setfont('arial','b',8);
		   		$pdf->cell(60,$alt,$RLj01_matric,1,0,"C",1);	      
		   		$pdf->cell(100,$alt,$RLz01_nome,1,1,"C",1);     		
			   	$p=0;		    
	   		}
	   		$pdf->setfont('arial','',7);
	   		$pdf->cell(60,$alt,$j44_matric,0,0,"C",$p);
	   		$pdf->cell(100,$alt,$z01_nome,0,1,"L",$p);  		     		
	   		
	   		if ($p==0){
	   			$p=1;
	   		}else{
	   			$p=0;
	   		}
	   		$total++;
	   }
	   $pdf->setfont('arial','b',8);   
	   $pdf->cell(160,$alt,'TOTAL DE MATRÍCULAS  :  '.$total,"T",0,"R",0);
	   $pdf->Ln();
	   $pdf->Ln();
   }else if ($tipo=='a'){
   		if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
	      $pdf->addpage("L");
	   }
	   $troca=1;
	   $pdf->setfont('arial','b',10);
	   $pdf->cell(100,$alt,$RLj63_numcgm.": ".$j63_numcgm." - ".$nome_imb,"",1,"L",0);
	   $pdf->cell(100,$alt,"Endereço: ".$ender_imb.", ".$numero_imb." - ".$compl_imb." -".$bairro_imb,"",1,"L",0);
	   $pdf->cell(75,$alt,"Municipio: ".$munic_imb,"",1,"L",0);
	   $result_imobil = $climobil->sql_record($climobil->sql_query_nome(null,"distinct *",$order_by,"j44_numcgm=$j63_numcgm"));
	   $numrows_imobil = $climobil->numrows;
	   if ($numrows_imobil==0){
	   		$pdf->cell(0,$alt,"Não existem matrículas vinculadas.",0,1,"C",0);
	   		$pdf->setfont('arial','b',8);   
	   		$pdf->cell(0,$alt,'TOTAL DE MATRÍCULAS  :  '.$total,"T",1,"R",0);
	   		$pdf->Ln();
	   		$pdf->Ln();
	   		$troca = 0;
	   		continue;   	
	   }else{
		   $pdf->setfont('arial','b',8);
		   $pdf->cell(30,$alt,$RLj01_matric,1,0,"C",1);	      
		   $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
		   $pdf->cell(70,$alt,"Rua/Avenida",1,0,"C",1);
		   $pdf->cell(15,$alt,"Número",1,0,"C",1);
		   $pdf->cell(40,$alt,"Complemento",1,0,"C",1);
		   $pdf->cell(50,$alt,"Bairro",1,1,"C",1);
	   }
	   $troca = 0;
	   $p=0;
	   $total = 0;
	   for($w=0;$w<$numrows_imobil;$w++){  	
	   		db_fieldsmemory($result_imobil,$w);
	   		if ($pdf->gety() > $pdf->h - 30 ){
	      		$pdf->addpage("L");      		
	      		$pdf->setfont('arial','b',10);
            $pdf->cell(100,$alt,$RLj63_numcgm.": ".$j63_numcgm." - ".$nome_imb,"",1,"L",0);
            $pdf->cell(100,$alt,"Endereço: ".$ender_imb.", ".$numero_imb." - ".$compl_imb." -".$bairro_imb,"",1,"L",0);
            $pdf->cell(75,$alt,"Municipio: ".$munic_imb,"",1,"L",0);
	      		//$pdf->cell(160,$alt,$RLj63_numcgm.": ".$j63_numcgm." - ".$nome_imb,"B",1,"L",0);
	      		$pdf->setfont('arial','b',8);
		   		$pdf->cell(30,$alt,$RLj01_matric,1,0,"C",1);	      
		   $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
		   $pdf->cell(70,$alt,"Rua/Avenida",1,0,"C",1);
		   $pdf->cell(15,$alt,"Número",1,0,"C",1);
		   $pdf->cell(40,$alt,"Complemento",1,0,"C",1);
		   $pdf->cell(50,$alt,"Bairro",1,1,"C",1);   		
			   	$p=0;		    
	   		}
	   		$pdf->setfont('arial','',7);
	   		$pdf->cell(30,$alt,$j44_matric,0,0,"C",$p);
	   		$pdf->cell(70,$alt,$z01_nome,0,0,"L",$p);
	   		$pdf->cell(70,$alt,$j14_nome,0,0,"L",$p);
		   $pdf->cell(15,$alt,$j39_numero,0,0,"C",$p);
		   $pdf->cell(40,$alt,$j39_compl,0,0,"L",$p);
		   $pdf->cell(50,$alt,$j13_descr,0,1,"L",$p);  		     		
	   		
	   		if ($p==0){
	   			$p=1;
	   		}else{
	   			$p=0;
	   		}
	   		$total++;
	   }
	   $pdf->setfont('arial','b',8);   
	   $pdf->cell(0,$alt,'TOTAL DE MATRÍCULAS  :  '.$total,"T",1,"R",0);
	   $pdf->Ln();
	   $pdf->Ln();
   }
}
$pdf->setfont('arial','b',8);
//$pdf->cell(130,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$pdf->Output();
?>