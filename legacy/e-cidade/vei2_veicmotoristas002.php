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
include("classes/db_veicmotoristas_classe.php");
$clveicmotoristas = new cl_veicmotoristas;
$clveicmotoristas->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('z01_nome');
$clrotulo->label('ve30_descr');
$clrotulo->label('ve33_descr');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$where=" ve36_coddepto = " . db_getsession("DB_coddepto");
$and="";
if($ordem == "a") {
	$desc_ordem = "Alfebética";
	$order_by = "z01_nome";
}else {
	$desc_ordem = "Numérica";
	$order_by = "ve05_codigo";
}
if ($categcnh!=""){
	$and=" and ";
	$where .= $and."ve05_veiccadcategcnh = $categcnh ";
}
if ($motoristasit!=""){
	$and=" and ";
  $where .= $and." ve05_veiccadmotoristasit = $motoristasit ";
}
if (($dtvenc != "--") && ($dtvenc1 != "--")) {
	$where .= $and."  ve05_dtvenc  between '$dtvenc' and '$dtvenc1'  ";
	$dtvenc = db_formatar($dtvenc, "d");
	$dtvenc1 = db_formatar($dtvenc1, "d");
	$info = "Validade De $dtvenc até $dtvenc1.";
	$and=" and ";
} else if ($dtvenc != "--") {
	$where .= $and."  ve05_dtvenc >= '$dtvenc'  ";
	$dtvenc = db_formatar($dtvenc, "d");
	$info = "Validade Apartir de $dtvenc.";
	$and=" and ";
} else if ($dtvenc1 != "--") {
	$where .= $and." ve05_dtvenc <= '$dtvenc1'   ";
	$dtvenc1 = db_formatar($dtvenc1, "d");
	$info = "Validade Até $dtvenc1.";
	$and=" and ";
}
if (($dtprimcnh != "--") && ($dtprimcnh1 != "--")) {
	$where .= $and." ve05_dtprimcnh  between '$dtprimcnh' and '$dtprimcnh1'  ";
	$dtprimcnh = db_formatar($dtprimcnh, "d");
	$dtprimcnh1 = db_formatar($dtprimcnh1, "d");
	$info1 = "1º CNH De $dtprimcnh até $dtprimcnh1.";
	$and=" and ";
} else if ($dtprimcnh != "--") {
	$where .= $and." ve05_dtprimcnh >= '$dtprimcnh'  ";
	$dtprimcnh = db_formatar($dtprimcnh, "d");
	$info1 = "1º CNH Apartir de $dtprimcnh.";
	$and=" and ";
} else if ($dtprimcnh1 != "--") {
	$where .= $and." ve05_dtprimcnh <= '$dtprimcnh1'   ";
	$dtprimcnh1 = db_formatar($dtprimcnh1, "d");
	$info1 = "1º CNH Até $dtprimcnh1.";
	$and=" and ";
}
$head3 = "CADASTRO DE MOTORISTAS ";
$head4 = "ORDEM $desc_ordem";
$head5 = @$info;
$head6 = @$info1;

$result = $clveicmotoristas->sql_record($clveicmotoristas->sql_query_central(null,"*",$order_by,$where));

if ($clveicmotoristas->numrows == 0){
   db_redireciona('db_erros.php?fechar=true&db_erro=Não existem motoristas cadastrados.');
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
$p=0;
for($x = 0; $x < $clveicmotoristas->numrows;$x++){
   db_fieldsmemory($result,$x);
   if ($pdf->gety() > $pdf->h - 30 || $troca != 0 ){
      $pdf->addpage("L");
      $pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,/*$RLve05_codigo*/"Código",1,0,"C",1);
      $pdf->cell(20,$alt,/*$RLve05_numcgm*/"Cgm",1,0,"C",1);
      $pdf->cell(60,$alt,$RLz01_nome,1,0,"C",1);
      $pdf->cell(25,$alt,$RLve05_habilitacao,1,0,"C",1);
      //$pdf->cell(20,$alt,$RLve05_veiccadcategcnh,1,0,"C",1);
      $pdf->cell(40,$alt,/*$RLve30_descr*/"Categoria CNH",1,0,"C",1);
      $pdf->cell(25,$alt,/*$RLve05_dtprimcnh*/"1º Habilitação",1,0,"C",1);
      $pdf->cell(20,$alt,$RLve05_dtvenc,1,0,"C",1);      
      //$pdf->cell(20,$alt,$RLve05_veiccadmotoristasit,1,0,"C",1);
      $pdf->cell(40,$alt,/*$RLve33_descr*/"Situação do Morista",1,1,"C",1);
      $troca = 0;
   }
   $pdf->setfont('arial','',7);
   	  $pdf->cell(20,$alt,$ve05_codigo,0,0,"C",$p);
      $pdf->cell(20,$alt,$ve05_numcgm,0,0,"C",$p);
      $pdf->cell(60,$alt,$z01_nome,0,0,"L",$p);
      $pdf->cell(25,$alt,$ve05_habilitacao,0,0,"C",$p);
      //$pdf->cell(20,$alt,$ve05_veiccadcategcnh,0,0,"C",$p);
      $pdf->cell(40,$alt,$ve30_descr,0,0,"C",$p);
      $pdf->cell(25,$alt,db_formatar($ve05_dtprimcnh,'d'),0,0,"C",$p);
      $pdf->cell(20,$alt,db_formatar($ve05_dtvenc,'d'),0,0,"C",$p);      
      //$pdf->cell(20,$alt,$ve05_veiccadmotoristasit,0,0,"C",$p);
      $pdf->cell(40,$alt,$ve33_descr,0,1,"L",$p);
   if ($p==0){
   	$p=1;
   }else{
   	$p=0;
   }   
   $total++;
}
$pdf->setfont('arial','b',8);
$pdf->cell(250,$alt,'TOTAL DE REGISTROS  :  '.$total,"T",0,"R",0);
$pdf->Output();
?>