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
include("classes/db_iptucalclog_classe.php");
include("classes/db_iptucalclogmat_classe.php");
$cliptucalclog = new cl_iptucalclog;
$cliptucalclogmat = new cl_iptucalclogmat;
$cliptucalclogmat->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$where=" 1=1 ";


if ($tipo=="E"){
	$where .= " and j62_erro = 't' ";	
	$info2 = "Somente Erros";
}

if (($data != "--") && ($data1 != "--")) {
	$where = $where." and j27_data  between '$data' and '$data1'  ";
	$data = db_formatar($data, "d");
	$data1 = db_formatar($data1, "d");
	$info = "De $data até $data1.";
} else
	if ($data != "--") {
		$where = $where." and j27_data >= '$data'  ";
		$data = db_formatar($data, "d");
		$info = "Apartir de $data.";
	} else
		if ($data1 != "--") {
			$where = $where."and j27_data <= '$data1'   ";
			$data1 = db_formatar($data1, "d");
			$info = "Até $data1.";
		}
		
$head3 = "LOG DO CÁLCULO IPTU ";
$head5 = @$info;
$head6 = @$info2;

$result = $cliptucalclog->sql_record($cliptucalclog->sql_query_inf(null,"*","j27_codigo",$where));
if ($cliptucalclog->numrows==0){
	db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros cadastrados.');
}
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$j27_codigoant="";
$alt = 4;
$total = 0;
$p=0;
$pdf->addpage();
for($x = 0; $x < $cliptucalclog->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($j27_codigoant!=$j27_codigo){
   	if($x!=0){ 
   	  $pdf->cell(190,$alt,"Total de Matrículas:".$total,"T",1,"L",0);
   	}
   	  $troca=1;
   	  $total=0;
   	  $j27_codigoant=$j27_codigo;
   }
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->setfont('arial','b',8);
      
      if ($pdf->gety() > $pdf->h - 30){
      	$pdf->addpage();
      	$pdf->cell(40,$alt,$RLj28_matric,1,0,"C",1); 
        $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(80,$alt,"Log",1,1,"C",1);
      }else{ 
        $pdf->cell(30,$alt,"Código: $j27_codigo",0,0,"L",0);
        $pdf->cell(20,$alt,"Ano: $j27_anousu",0,0,"L",0);
        $pdf->cell(30,$alt,"Data: ".db_formatar($j27_data,"d"),0,0,"L",0); 
        $pdf->cell(30,$alt,"Hora: $j27_hora",0,0,"L",0);
        $pdf->cell(70,$alt,"Usuário: $j27_usuario - ".substr($nome,0,40),0,1,"L",0);
        $pdf->Ln();
        $pdf->cell(40,$alt,$RLj28_matric,1,0,"C",1); 
        $pdf->cell(70,$alt,$RLz01_nome,1,0,"C",1);
        $pdf->cell(80,$alt,"Log",1,1,"C",1);
      }
      $p=0; 
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(40,$alt,$j28_matric,0,0,"C",$p); 
   $pdf->cell(70,$alt,$z01_nome,0,0,"L",$p);
   $pdf->cell(80,$alt,substr($j62_descr,0,50),0,1,"L",$p);
   if ($p==0){
   	$p=1;   
   }else{
   	$p=0;
   }
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(190,$alt,"Total de Matrículas:".$total,"T",1,"L",0);
$pdf->Output();
?>