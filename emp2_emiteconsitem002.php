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
include("classes/db_empempaut_classe.php");
include("classes/db_empempenho_classe.php");
include("classes/db_empautitem_classe.php");
include("classes/db_empempitem_classe.php");
include("classes/db_empparametro_classe.php");
include("classes/db_pagordem_classe.php");
include("classes/db_pcmater_classe.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clempempaut  = new cl_empempaut;
$clempempenho = new cl_empempenho;
$clempautitem = new cl_empautitem;
$clempempitem = new cl_empempitem;
$clempparametro = new cl_empparametro;
$clpagordem = new cl_pagordem;
$clpcmater = new cl_pcmater;
$clcgm = new cl_cgm;
$clempempaut->rotulo->label();
$clempempenho->rotulo->label();
$clempautitem->rotulo->label();
$clempempitem->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('e60_numemp');
$clrotulo->label('e60_codemp');
$clrotulo->label('e60_anousu');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('e62_vlrun');
$clrotulo->label('e62_vltot');
$clrotulo->label('e62_quant');
$clrotulo->label('pc01_codmater');
$clrotulo->label('pc01_descrmater');

$campos = "distinct e60_numemp,e60_codemp,e60_anousu,z01_numcgm,z01_nome,e62_vlrun,e62_vltot,e62_quant,pc01_codmater,pc01_descrmater";
$dbwhereEMP = "";
$and = "";

$head1 = "Relatório de itens por empenho";

$nh = 2;

if(isset($e60_numemp) && trim($e60_numemp) != ""){
	$dbwhereEMP = " e60_numemp = ".$e60_numemp;
	$and = " and ";
	$HEAD = "head2";
	$$HEAD = "Número do empenho - ".$e60_numemp;
	$HEAD = "head3";
	$result_empenho = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp,"e60_codemp as codemp,e60_anousu as anousu"));
	if($clempempenho->numrows > 0){
		db_fieldsmemory($result_empenho, 0);
	  $$HEAD = "Empenho - ".$codemp." / ".$anousu;
	}
	$nh = 4;
}else{
	if(isset($e60_codemp) && $e60_codemp != "" ) {
	  $arr = split("/",$e60_codemp);
	  if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
	  	$dbwhereEMP .= " e60_codemp =  ".$arr[0]." and e60_anousu = ".$arr[1];
	  	$HEAD = "head".$nh;
	  	$$HEAD = "Empenho - ".$arr[0]." / ".$arr[1];
	  }else{
	  	$dbwhereEMP .= " e60_codemp =  ".$arr[0]." and e60_anousu = ".db_getsession("DB_anousu");
	  	$HEAD = "head".$nh;
	  	$$HEAD = "Empenho - ".$arr[0]." / ".db_getsession("DB_anousu");
	  }
	  $and = " and ";
	  $nh++;
	}
}
$data1=0;
$data2=0;
@$data1= "$dt1_ano-$dt1_mes-$dt1_dia";
@$data2= "$dt2_ano-$dt2_mes-$dt2_dia";

if(strlen($data1) < 3){
  unset($data1);
}  
if(strlen($data2) < 3){
  unset($data2);
}

if(isset($o58_coddot) && $o58_coddot != "") {
	$dbwhereEMP .= $and." o58_coddot=$o58_coddot ";
	$and = " and ";
	$HEAD = "head".$nh;
	$$HEAD = "Dotação - ".$o58_coddot;
	$nh++;
}
if(isset($pc01_codmater) && $pc01_codmater != "") {
	$dbwhereEMP .= $and." pc01_codmater = $pc01_codmater ";
	$and = " and ";
	$HEAD = "head".$nh;
	$$HEAD = "Material - ".$pc01_codmater;
	$result_material = $clpcmater->sql_record($clpcmater->sql_query_file($pc01_codmater,"pc01_descrmater as dmater"));
	if($clpcmater->numrows > 0){
		db_fieldsmemory($result_material, 0);
		$$HEAD.= " * ".$dmater;
	}
	$nh++;
}
if(isset($z01_numcgm) && $z01_numcgm != "") {
	$dbwhereEMP .= $and." e60_numcgm = $z01_numcgm ";
	$and = " and ";
	$HEAD = "head".$nh;
	$$HEAD = "CGM - ".$z01_numcgm;
	$result_cgm = $clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_cgm as ncgm"));
	if($clcgm->numrows > 0){
		db_fieldsmemory($result_cgm, 0);
		$$HEAD.= " * ".$ncgm;
	}
	$nh++;
}
if(isset($e53_codord) && $e53_codord !=""){
	$dbwhereEMP .= $and." e50_codord = $e53_codord ";
	$and = " and ";
	$HEAD = "head".$nh;
	$$HEAD = "Ordem - ".$e53_codord;
	$nh++;
}

/*
$sql = $clempempenho->sql_query_impconsulta(null, $campos, "pc01_codmater,e60_numemp", $dbwhereEMP." ".$and." e60_instit = ".db_getsession("DB_instit"));
if((isset($dt1) && $dt1 != "") && (isset($dt2) && $dt2 != "")){
	$sql = $clempempenho->sql_query_impconsulta(null, $campos, "pc01_codmater,e60_numemp", $dbwhereEMP." ".$and." e60_emiss between '".$dt1."' and '".$dt2."' and e60_instit = ".db_getsession("DB_instit"));	
}
*/
//if(isset($pc01_codmater) && $pc01_codmater !=""){
  $sql = $clempempenho->sql_query_itemmaterial(null,$campos, "pc01_codmater,z01_numcgm,e60_numemp",$dbwhereEMP." ".$and." e60_instit = ".db_getsession("DB_instit"));
  if((isset($dt1) && $dt1 != "") && (isset($dt2) && $dt2 != "")){
    $sql = $clempempenho->sql_query_itemmaterial(null, $campos, "pc01_codmater,z01_numcgm,e60_numemp",$dbwhereEMP." ".$and." e60_emiss between '".$dt1."' and '".$dt2."' and e60_instit = ".db_getsession("DB_instit"));
    $HEAD = "head".$nh;
		$$HEAD = "Período - ".db_formatar($dt1,"d")." a ".db_formatar($dt2,"d");
		$nh++;
  }  
// }
if(isset($e53_codord) && $e53_codord !=""){
  $sql = $clpagordem->sql_query_impconsulta(null,$campos,"pc01_codmater,z01_numcgm,e60_numemp"," e50_codord = ".$e53_codord." and e60_instit = ".db_getsession("DB_instit"));
}

$result_itens  = $clempempitem->sql_record($sql);
$numrows_itens = $clempempitem->numrows;
if($numrows_itens == 0){
  db_redireciona('db_erros.php?fechar=true&db_erro=Nenhum item encontrado com os dados selecionados.');
}

$result_numdec = $clempparametro->sql_record($clempparametro->sql_query_file(db_getsession("DB_anousu"),"e30_numdec as numdec"));
db_fieldsmemory($result_numdec,0);

$pdf = new PDF(); 
$pdf->Open(); 
$pdf->AliasNbPages(); 
$pdf->setfillcolor(235);
$pdf->setfont('arial','b',8);
$troca = 1;
$alt = 4;

$totqtdCGM = 0;
$totvluCGM = 0;
$totvlrCGM = 0;
$totregCGM = 0;

$totqtd = 0;
$totvlu = 0;
$totvlr = 0;
$totreg = 0;
$totalg = 0;

$matant = "";
$cgmant = "";

for($i=0; $i<$numrows_itens; $i++){
	db_fieldsmemory($result_itens, $i);

  if($cgmant != "" && $cgmant != $z01_numcgm){
  	$pdf->setfont('arial','',7);
    $pdf->cell(80,$alt,"TOTAL REGISTROS","LTB",0,"R",0);
    $pdf->cell(20,$alt,$totregCGM,"TBR",1,"R",0);

    $pdf->cell(40,$alt,"TOTAIS","TBL",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totqtdCGM,"f"),"TB",0,"R",0);
    $pdf->cell(20,$alt,"","TB",0,"R",0);
    $pdf->cell(20,$alt,db_formatar($totvlrCGM,"f"),"TBR",0,"R",0);

    $pdf->cell(40,$alt,"VALOR UNITÁRIO MÉDIO","LTB",0,"R",0);
    $pdf->cell(20,$alt,db_formatar(($totvlrCGM/$totqtdCGM),"f"),"RTB",1,"R",0);
    $pdf->ln(2);

    if($matant == $pc01_codmater){
    	$pdf->setfont('arial','b',8);
      $pdf->cell(20,$alt,$z01_numcgm,"TBL",0,"R",0);
      $pdf->cell(80,$alt,$z01_nome,"TBR",1,"L",0);
    }

		$totqtdCGM = 0;
		$totvluCGM = 0;
		$totvlrCGM = 0;
		$totregCGM = 0;
  }

  if($matant != "" && $matant != $pc01_codmater){
  	
	  if($cgmant == $z01_numcgm){
	  	$pdf->setfont('arial','',7);
	    $pdf->cell(80,$alt,"TOTAL REGISTROS","LTB",0,"R",0);
	    $pdf->cell(20,$alt,$totregCGM,"TBR",1,"R",0);
	
	    $pdf->cell(40,$alt,"TOTAIS","TBL",0,"R",0);
	    $pdf->cell(20,$alt,db_formatar($totqtdCGM,"f"),"TB",0,"R",0);
	    $pdf->cell(20,$alt,"","TB",0,"R",0);
	    $pdf->cell(20,$alt,db_formatar($totvlrCGM,"f"),"TBR",0,"R",0);
	
	    $pdf->cell(40,$alt,"VALOR UNITÁRIO MÉDIO","LTB",0,"R",0);
	    $pdf->cell(20,$alt,db_formatar(($totvlrCGM/$totqtdCGM),"f"),"RTB",1,"R",0);
	    $pdf->ln(2);
	
	    if($matant == $pc01_codmater){
	    	$pdf->setfont('arial','b',8);
	      $pdf->cell(20,$alt,$z01_numcgm,"TBL",0,"R",0);
	      $pdf->cell(80,$alt,$z01_nome,"TBR",1,"L",0);
	    }
	  }

  	$pdf->setfont('arial','b',8);

    $pdf->cell(100,$alt,"TOTALIZAÇÃO POR MATERIAL","1",1,"L",1);
    $pdf->cell(80,$alt,"TOTAL REGISTROS","LTB",0,"R",1);
    $pdf->cell(20,$alt,$totreg,"TBR",1,"R",1);

    $pdf->cell(40,$alt,"TOTAIS","TBL",0,"R",1);
    $pdf->cell(20,$alt,db_formatar($totqtd,"f"),"TB",0,"R",1);
    $pdf->cell(20,$alt,"","TB",0,"R",1);
    $pdf->cell(20,$alt,db_formatar($totvlr,"f"),"RTB",0,"R",1);

    $pdf->cell(40,$alt,"VALOR UNITÁRIO MÉDIO","LTB",0,"R",1);
    $pdf->cell(20,$alt,db_formatar(($totvlr/$totqtd),"f"),"RTB",1,"R",1);
    $pdf->ln(6);

    $pdf->cell(20,$alt,$pc01_codmater,"TBL",0,"R",1);
    $pdf->cell(80,$alt,$pc01_descrmater,"TBR",1,"L",1);

    $pdf->cell(20,$alt,$z01_numcgm,"TBL",0,"R",0);
    $pdf->cell(80,$alt,$z01_nome,"TBR",1,"L",0);
		$totqtd = 0;
		$totvlu = 0;
		$totvlr = 0;
		$totreg = 0;

		$totqtdCGM = 0;
		$totvluCGM = 0;
		$totvlrCGM = 0;
		$totregCGM = 0;
  }

  if(($pdf->gety() > $pdf->h - 30) || ($troca != 0)){
    $pdf->addpage();
  	$pdf->setfont('arial','b',8);
    $pdf->cell(20,$alt,$RLe60_numemp,1,0,"C",1);
    $pdf->cell(20,$alt,$RLe60_codemp,1,0,"C",1);
    $pdf->cell(20,$alt,$RLe62_quant,1,0,"C",1);
    $pdf->cell(20,$alt,$RLe62_vlrun,1,0,"C",1);
    $pdf->cell(20,$alt,$RLe62_vltot,1,1,"C",1);

    $pdf->cell(20,$alt,$RLpc01_codmater,"TBL",0,"R",1);
    $pdf->cell(80,$alt,$RLpc01_descrmater,"TBR",1,"L",1);
    $pdf->cell(20,$alt,$RLz01_numcgm,"TBL",0,"R",0);
    $pdf->cell(80,$alt,$RLz01_nome,"TBR",1,"L",0);

    $pdf->ln(2);

    $pdf->cell(20,$alt,$pc01_codmater,"TBL",0,"R",1);
    $pdf->cell(80,$alt,$pc01_descrmater,"TBR",1,"L",1);

    $pdf->cell(20,$alt,$z01_numcgm,"TBL",0,"R",0);
    $pdf->cell(80,$alt,$z01_nome,"TBR",1,"L",0);
    $troca = 0;
  }
  $pdf->setfont('arial','',7);
  $pdf->cell(20,$alt,$e60_numemp,1,0,"C",0);
  $pdf->cell(20,$alt,$e60_codemp,1,0,"C",0);
  $pdf->cell(20,$alt,db_formatar($e62_quant,"f"),1,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($e62_vlrun,"v"," ",$numdec),1,0,"R",0);
  $pdf->cell(20,$alt,db_formatar($e62_vltot,"f"),1,1,"R",0);
  //$pdf->cell(0,$alt," *** ".$z01_nome,1,1,"R",0);


  $totqtdCGM+= $e62_quant;
  $totvluCGM+= $e62_vlrun;
  $totvlrCGM+= $e62_vltot;
  $totregCGM++;

  $totqtd+= $e62_quant;
  $totvlu+= $e62_vlrun;
  $totvlr+= $e62_vltot;
  $totreg++;
  $totalg++;

  $matant = $pc01_codmater;
  $cgmant = $z01_numcgm;
}

$pdf->setfont('arial','',7);
$pdf->cell(80,$alt,"TOTAL REGISTROS","LTB",0,"R",0);
$pdf->cell(20,$alt,$totregCGM,"TBR",1,"R",0);

$pdf->cell(40,$alt,"TOTAIS","TBL",0,"R",0);
$pdf->cell(20,$alt,db_formatar($totqtdCGM,"f"),"TB",0,"R",0);
$pdf->cell(20,$alt,"","TB",0,"R",0);
$pdf->cell(20,$alt,db_formatar($totvlrCGM,"f"),"TBR",0,"R",0);

$pdf->cell(40,$alt,"VALOR UNITÁRIO MÉDIO","LTB",0,"R",0);
$pdf->cell(20,$alt,db_formatar(($totvlrCGM/$totqtdCGM),"f"),"RTB",1,"R",0);
$pdf->ln(2);


$pdf->setfont('arial','b',8);

$pdf->cell(100,$alt,"TOTALIZAÇÃO POR MATERIAL","1",1,"L",1);
$pdf->cell(80,$alt,"TOTAL REGISTROS","LTB",0,"R",1);
$pdf->cell(20,$alt,$totreg,"TBR",1,"R",1);

$pdf->cell(40,$alt,"TOTAIS","TBL",0,"R",1);
$pdf->cell(20,$alt,db_formatar($totqtd,"f"),"TB",0,"R",1);
$pdf->cell(20,$alt,"","TB",0,"R",1);
$pdf->cell(20,$alt,db_formatar($totvlr,"f"),"RTB",0,"R",1);

$pdf->cell(40,$alt,"VALOR UNITÁRIO MÉDIO","LTB",0,"R",1);
$pdf->cell(20,$alt,db_formatar(($totvlr/$totqtd),"f"),"RTB",1,"R",1);

$pdf->Output();
?>