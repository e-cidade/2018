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
include("classes/db_liclicita_classe.php");
include("classes/db_licbaixa_classe.php");
$clliclicita = new cl_liclicita;
$cllicbaixa = new cl_licbaixa;
$clliclicita->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('l03_descr');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);

$where = "";
$and = "";
if (($data != "--") && ($data1 != "--")) {
	$where .= $and." l20_dataaber  between '$data' and '$data1' ";
	$data = db_formatar($data, "d");
	$data1 = db_formatar($data1, "d");
	$info = "De $data até $data1.";
	$and = " and ";
}else if ($data != "--") {
	$where .= $and." l20_dataaber >= '$data'  ";
	$data = db_formatar($data, "d");
    $info = "Apartir de $data.";
    $and = " and ";
}else if ($data1 != "--") {
	$where .= $and." l20_dataaber <= '$data1'   ";
	$data1 = db_formatar($data1, "d");
	$info = "Até $data1.";
	$and = " and ";
}
if ($l20_codigo!=""){
	$where .= $and." l20_codigo=$l20_codigo ";
	$and = " and ";
}
if ($l20_numero!=""){
	$where .= $and." l20_numero=$l20_numero ";
	$and = " and ";
	$info1 = "Numero:".$l20_numero;
}
if ($l03_codigo!=""){
	$where .= $and." l20_codtipocom=$l03_codigo ";
	$and = " and ";
	if ($l03_descr!=""){
		$info2 = "Modalidade:".$l03_codigo."-".$l03_descr;
	}
}

$where .= $and." l20_instit = ".db_getsession("DB_instit");

$result=$clliclicita->sql_record($clliclicita->sql_query_baixa(null,"distinct liclicita.*,l03_descr",null,$where));
$numrows=$clliclicita->numrows;
if ($clliclicita->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
$head2 = "Edital Download";
$head3 = @$info;
$head4 = @$info1;
$head5 = @$info2;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
$p=0;
$pp=0;
for($x = 0; $x < $clliclicita->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(15,$alt,"Cod. Seq."/*$RLl20_codigo*/,1,0,"C",1);
      //$pdf->cell(15,$alt,$RLl20_codtipocom,1,0,"C",1);
      $pdf->cell(40,$alt,$RLl03_descr,1,0,"C",1);
      $pdf->cell(20,$alt,$RLl20_numero,1,0,"C",1);
      $pdf->cell(20,$alt,$RLl20_dataaber,1,0,"C",1);      
      $pdf->cell(0,$alt,$RLl20_objeto,1,1,"C",1);
      /*
      $pdf->cell(27,$alt,"",0,0,"C",0);
      $pdf->cell(60,$alt,"Nome",1,0,"C",1);
      $pdf->cell(20,$alt,"CNPJ/CPF",1,0,"C",1);
      $pdf->cell(40,$alt,"E-mail",1,0,"C",1);
      $pdf->cell(60,$alt,"Endereço",1,0,"C",1);
      $pdf->cell(30,$alt,"Cidade",1,0,"C",1);
      $pdf->cell(20,$alt,"Telefone",1,0,"C",1);
      $pdf->cell(20,$alt,"Data",1,1,"C",1);
      */
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(15,$alt,$l20_codigo,0,0,"C",$p);
   //$pdf->cell(15,$alt,$l20_codtipocom,0,0,"C",$p);
   $pdf->cell(40,$alt,$l03_descr,0,0,"L",$p);
   $pdf->cell(20,$alt,$l20_numero,0,0,"C",$p);
   $pdf->cell(20,$alt,db_formatar($l20_dataaber,"d"),0,0,"C",$p);   
   $pdf->multicell(0,$alt,$l20_objeto,0,"L",$p);   
   $result_baixa=$cllicbaixa->sql_record($cllicbaixa->sql_query(null,"*",null,"l28_liclicita=$l20_codigo"));
   $pp=0;
   $troca1 = 1;
   for($w=0;$w<$cllicbaixa->numrows;$w++){
   		db_fieldsmemory($result_baixa,$w);
   		if ($pdf->gety() > $pdf->h - 30 || $troca1 != 0 ){
   			if ($pdf->gety() > $pdf->h - 30){
   				$pdf->addpage("L");
			    $pdf->setfont('arial','b',8);
			    $pdf->cell(15,$alt,"Cod. Seq."/*$RLl20_codigo*/,1,0,"C",1);
			    //$pdf->cell(15,$alt,$RLl20_codtipocom,1,0,"C",1);
			    $pdf->cell(40,$alt,$RLl03_descr,1,0,"C",1);
			    $pdf->cell(20,$alt,$RLl20_numero,1,0,"C",1);
			    $pdf->cell(20,$alt,$RLl20_dataaber,1,0,"C",1);      
			    $pdf->cell(0,$alt,$RLl20_objeto,1,1,"C",1);
   			}
	   		$pdf->cell(27,$alt,"",0,0,"C",0);
	      	$pdf->cell(60,$alt,"Nome",1,0,"C",1);
	      	$pdf->cell(20,$alt,"CNPJ/CPF",1,0,"C",1);
	      	$pdf->cell(40,$alt,"E-mail",1,0,"C",1);
	      	$pdf->cell(60,$alt,"Endereço",1,0,"C",1);
	      	$pdf->cell(30,$alt,"Cidade",1,0,"C",1);
	      	$pdf->cell(20,$alt,"Telefone",1,0,"C",1);
	      	$pdf->cell(20,$alt,"Data",1,1,"C",1);  
	   		
	   		$troca1 = 0;
   		}
   		$pdf->cell(27,$alt,"",0,0,"L",0);
   		$pdf->cell(60,$alt,$l28_nome,0,0,"L",$pp);
   		$pdf->cell(20,$alt,$l28_cnpj,0,0,"C",$pp);
   		$pdf->cell(40,$alt,$l28_email,0,0,"L",$pp);
   		$pdf->cell(60,$alt,$l28_endereco,0,0,"L",$pp);
   		$pdf->cell(30,$alt,$l28_cidade,0,0,"L",$pp);
   		$pdf->cell(20,$alt,$l28_fone,0,0,"C",$pp);
   		$pdf->cell(20,$alt,db_formatar($l28_data,"d"),0,1,"C",$pp);
   	   		
	   	if ($pp==0){
	   		$pp=1;
	   	}else{
	   		$pp=0;
	    }
	    
   }
   $pdf->cell(27,$alt,"",0,0,"L",0);
   $pdf->cell(250,$alt,'',"T",1,"R",0); 
   /*if ($p==0){
	   		$p=1;
	}else{
		$p=0;
	}*/  
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"R",0);
$pdf->Output();
?>