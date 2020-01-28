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
include("classes/db_aguahidromatric_classe.php");
$claguahidromatric = new cl_aguahidromatric;
$clrotulo = new rotulocampo;
$clrotulo->label('x04_matric  ');
$clrotulo->label('x04_nrohidro');
$clrotulo->label('x15_diametro');
$clrotulo->label('x03_sigla   ');
$clrotulo->label('x04_dtinst  ');
$clrotulo->label('x28_dttroca ');
$clrotulo->label('x28_obs     ');            
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$where = "";
$info = "";
if (($data != "--") && ($data1 != "--")) {
	$where = "where x04_dtinst  between '$data' and '$data1'  ";
	$data = db_formatar($data, "d");
	$data1 = db_formatar($data1, "d");
	$info = "Data de Instalação De $data até $data1.";
} else if ($data != "--") {
	$where = "where  x04_dtinst >= '$data'  ";
	$data = db_formatar($data, "d");
	$info = "Data de Instalação Apartir de $data.";
} else if ($data1 != "--") {
	$where = "where x04_dtinst <= '$data1'   ";
	$data1 = db_formatar($data1, "d");
	$info = "Data de Instalação Até $data1.";
}
$result = pg_exec("select x04_matric,
                          x04_nrohidro,
													x15_diametro,
													x03_sigla,
										      x04_dtinst,
													x28_dttroca,
													x28_obs
							     from aguahidromatric
									      inner join aguahidromarca    on x03_codmarca      = x04_codmarca
										    inner join aguahidrodiametro on x15_coddiametro   = x04_coddiametro
										    left join  aguahidrotroca    on x28_codhidrometro = x04_codhidrometro
									 $where");
if (pg_numrows($result) == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem registros  cadastrados.');
exit;
}
$head3 = "Relatório de Hidrômetros ";
$head5 = @$info;
$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$total = 0;
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;
$total = 0;
for($x = 0; $x < pg_numrows($result);$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage();
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$RLx04_matric  ,1,0,"C",1);
      $pdf->cell(20,$alt,$RLx04_nrohidro,1,0,"C",1); 
      $pdf->cell(20,$alt,$RLx15_diametro,1,0,"C",1); 
      $pdf->cell(30,$alt,$RLx03_sigla   ,1,0,"C",1); 
      $pdf->cell(25,$alt,$RLx04_dtinst  ,1,0,"C",1); 
      $pdf->cell(20,$alt,$RLx28_dttroca ,1,0,"C",1); 
      $pdf->cell(0,$alt,$RLx28_obs     ,1,1,"C",1); 
      $troca = 0;
			$p=0;
   }
   $pdf->setfont('arial','',7);
   $pdf->cell(20,$alt,$x04_matric  ,0,0,"C",$p);
   $pdf->cell(20,$alt,$x04_nrohidro,0,0,"C",$p);
   $pdf->cell(20,$alt,$x15_diametro,0,0,"L",$p);
   $pdf->cell(30,$alt,$x03_sigla   ,0,0,"L",$p);
   $pdf->cell(25,$alt,db_formatar($x04_dtinst,'d')  ,0,0,"C",$p);
   $pdf->cell(20,$alt,db_formatar($x28_dttroca,'d') ,0,0,"C",$p);
   $pdf->multicell(0,$alt,$x28_obs ,0,"L",$p);
	 if ($p==0)$p=1;
	 else $p=0;
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(0,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"L",0);
$pdf->Output();
?>