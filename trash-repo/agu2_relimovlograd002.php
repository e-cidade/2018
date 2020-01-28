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
include("classes/db_aguabase_classe.php");
$claguabase = new cl_aguabase;
$claguabase->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('x11_complemento');
$clrotulo->label('x04_nrohidro');
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$where = " (fc_agua_hidrometroativo(aguahidromatric.x04_codhidrometro) is true or aguahidromatric.x04_codhidrometro is null) and (x11_tipo = 'P' or x11_matric is null)";
if ($lista != "") {
	if (isset ($ver) and $ver == "com") {
		$where .= " and x01_codrua in  ($lista)";
	} else {
		$where .= " and x01_codrua not in  ($lista)";
	}
}
$head3 = "RELATÓRIO DE IMOVEIS POR LOGRADOURO ";

$sqlaguabase = $claguabase->sql_query_aguahidromatricpropri(null,"x01_matric, proprietario, x01_codrua, j14_nome, x01_numero, x01_letra, x11_complemento, x04_nrohidro, case when x11_codconstr is not null then 'Predial    ' else 'Territorial' end as j31_descr","x01_codrua,x01_letra,x01_numero,x11_complemento",$where);
//die($sqlaguabase);
$result = $claguabase->sql_record($sqlaguabase);
if ($claguabase->numrows == 0){
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
$totalog = 0;
$codrua = "";
$p=0; 
for($x = 0; $x < $claguabase->numrows;$x++){
   db_fieldsmemory($result,$x);   
   if ($codrua != $x01_codrua){
   	  if ($codrua!=""){
   	  	$pdf->cell(280,$alt,'TOTAL DE MATRICULAS : '.$total,"T",1,"R",0);
   	  	$pdf->ln();
   	  	$pdf->ln();
   	  	$total=0;
   	  }
   	  if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      	$pdf->addpage("L");       
        $pdf->setrightmargin(0.5);
      	$troca = 0;
   	  }	
   	  $pdf->setfont('arial','b',8);
   	  /*
   	  $pdf->cell(0,$alt,"Logradouro: $x01_codrua - $j14_nome",0,1,"L",0);      
      $pdf->cell(15,$alt,"Matricula",1,0,"C",1);
      $pdf->cell(15,$alt,"Nº Imóvel",1,0,"C",1);
      $pdf->cell(20,$alt,"Letra",1,0,"C",1);
      $pdf->cell(25,$alt,"Complemento",1,0,"C",1);
      $pdf->cell(30,$alt,"Nº do Hidrômetro",1,0,"C",1);
      $pdf->cell(80,$alt,"Observação",1,1,"C",1);
      */
      $pdf->cell(0,$alt,$RLx01_codrua.": $x01_codrua - $j14_nome",0,1,"L",0);      
      $pdf->cell(15,$alt,$RLx01_matric,1,0,"C",1);
      $pdf->cell(75,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(15,$alt,$RLx01_numero,1,0,"C",1);
      $pdf->cell(20,$alt,$RLx01_letra,1,0,"C",1);
      $pdf->cell(25,$alt,$RLx11_complemento,1,0,"C",1);
      $pdf->cell(30,$alt,$RLx04_nrohidro,1,0,"C",1);
      $pdf->cell(20,$alt,"Tipo",1,0,"C",1);
      $pdf->cell(80,$alt,"Observação",1,1,"C",1);
      $p=0;
	  $codrua = $x01_codrua;      
      $totalog++;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
     $pdf->addpage("L");
     $pdf->setrightmargin(0.5);
     $pdf->setfont('arial','b',8);
     /*
     $pdf->cell(0,$alt,"Logradouro: $x01_codrua - $j14_nome",0,1,"L",0);
     $pdf->cell(15,$alt,"Matricula",1,0,"C",1);
     $pdf->cell(15,$alt,"Nº Imóvel",1,0,"C",1);
     $pdf->cell(20,$alt,"Letra",1,0,"C",1);
     $pdf->cell(25,$alt,"Complemento",1,0,"C",1);
     $pdf->cell(30,$alt,"Nº do Hidrômetro",1,0,"C",1);
     $pdf->cell(80,$alt,"Observação",1,1,"C",1);
     */
     $pdf->cell(0,$alt,$RLx01_codrua.": $x01_codrua - $j14_nome",0,1,"L",0);      
     $pdf->cell(15,$alt,$RLx01_matric,1,0,"C",1);
     $pdf->cell(75,$alt,$RLz01_nome,1,0,"C",1);
     $pdf->cell(15,$alt,$RLx01_numero,1,0,"C",1);
     $pdf->cell(20,$alt,$RLx01_letra,1,0,"C",1);
     $pdf->cell(25,$alt,$RLx11_complemento,1,0,"C",1);
     $pdf->cell(30,$alt,$RLx04_nrohidro,1,0,"C",1);
     $pdf->cell(20,$alt,"Tipo",1,0,"C",1);
     $pdf->cell(80,$alt,"Observação",1,1,"C",1);
     $p=0;       
   	 $troca = 0;
   }
   $pdf->setfont('arial','',7);   
   $pdf->cell(15,$alt,@$x01_matric,0,0,"C",$p);
   $pdf->cell(75,$alt,substr(@$proprietario,0,50),0,0,"L",$p);
   $pdf->cell(15,$alt,@$x01_numero,0,0,"C",$p);
   $pdf->cell(20,$alt,@$x01_letra,0,0,"C",$p);
   $pdf->cell(25,$alt,@$x11_complemento,0,0,"C",$p);
   $pdf->cell(30,$alt,@$x04_nrohidro,0,0,"C",$p);
   $pdf->cell(20,$alt,substr(@$j31_descr,0,35),0,0,"L",$p);          
   $pdf->cell(80,$alt,"",0,1,"L",$p);
   if ($p==0){
   	$p=1;
   }else{
   	$p=0;
   }
   $total++;
}
$pdf->cell(280,$alt,'TOTAL DE MATRICULAS : '.$total,"T",1,"R",0);
$pdf->ln();

$pdf->setfont('arial','b',8);
$pdf->cell(280,$alt,'TOTAL DE LOGRADOUROS  :  '.$totalog,"T",0,"L",0);
$pdf->Output();
?>