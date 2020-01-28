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

include("classes/db_issbase_classe.php");
include("classes/db_iptubase_classe.php");

$head1 = "SECRETARIA DA FAZENDA";
$head2 = "Relatório dos Débitos pendentes";
$head3 = "";
$head4 = "";
//$head5 = "Texto numero 5";
$head6 = "";
$head7 = "";
$head8 = "";


include("fpdf151/pdf.php");
require("libs/db_sql.php");

$DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
$head9 = "Débitos Calculados até: ".db_formatar($db_datausu,'d');

  if($tipo == 3) {
    if(!empty($numcgm)){
      $result = debitos_numcgm_var($numcgm,0,$tipo,$DB_DATACALC,$DB_anousu);
      $head4 = "Contribuinte : ".$numcgm;
	  $head5 = "";
    }else if(!empty($inscr)){	  
      $result = debitos_inscricao_var($inscr,0,$tipo,$DB_DATACALC,$DB_anousu);
      $head4  = "Inscrição: ".$inscr;
      $result_iden = 
	  $head5 = "";
	}else if(!empty($numpre)){
	  $result = debitos_numpre_var($numpre,0,$tipo,$DB_DATACALC,$DB_anousu);
      $head4 = "Código Arrecadação: ".$numpre;
	  $head5 = "";
    }
  } else {
    if(!empty($numcgm)) {
      $result = debitos_numcgm($numcgm,0,$tipo,$DB_DATACALC,$DB_anousu,'','k00_numpre,k00_receit,k00_numpar,k00_tipo');
      $head4 = "Contribuinte : ".$numcgm;
	  $head5 = "";
    } else if(!empty($matric)) {
      $result = debitos_matricula($matric,0,$tipo,$DB_DATACALC,$DB_anousu,'','k00_numpre,k00_receit,k00_numpar,k00_tipo');
      $head4 = "Matricula: ".$matric;
	  $cliptubase = new cl_iptubase;
	  $result_inf = $cliptubase->proprietario_record($cliptubase->proprietario_query($matric,"j34_setor#j34_quadra#j34_lote#nomepri#z01_nome"));
      if($cliptubase->numrows!=0){
	    db_fieldsmemory($result_inf,0);
	    $head5 = "Endereço:".$nomepri;
		$head6 = "Setor: $j34_setor Quadra: $j34_quadra Lote: $j34_lote";
		$head6 = "Nome: $z01_nome";
      }else{
	    $head5 = "";
	  }
    } else if(!empty($inscr)) {
      $result = debitos_inscricao($inscr,0,$tipo,$DB_DATACALC,$DB_anousu,'','k00_numpre,k00_receit,k00_numpar,k00_tipo');
      $head4 = "Inscrição: ".$inscr;
	  $clissbase = new cl_issbase;
	  $result_inf = $clissbase->empresa_record($clissbase->empresa_query($matric,"nomepri#ativid"));
      if($clissbase->numrows!=0){
	    db_fieldsmemory($result_inf,0);
	    $head5 = "Endereço:".$nomepri;
		$head6 = "Atividade: ";
      }else{
	    $head5 = "";
	  }
	} else if(!empty($numpre)) {
      $result = debitos_numpre($numpre,0,$tipo,$DB_DATACALC,$DB_anousu,0,'','k00_numpre,k00_receit,k00_numpar,k00_tipo');
      db_fieldsmemory($result,0);
      $head4 = "Código Arrecadação: ".$numpre;
      $sqlnumpre = "select a.k00_numpre,
	                       coalesce(k00_matric,0) as matric,
						   coalesce(k00_inscr,0) as inscr 
					from arrecad a 
                inner join arreinstit on arreinstit.k00_numpre = arrecad.k00_numpre 
                     								 and arreinstit.k00_instit = ".db_getsession('DB_instit') ."
					     left outer join arreinscr b on a.k00_numpre = b.k00_numpre 
						 left outer join arrematric c on a.k00_numpre = c.k00_numpre 
				    where a.k00_numpre = $numpre
					limit 1";
	}
  }
$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFillColor(220);
//Dados
$dados = pg_exec("select z01_numcgm,z01_nome,z01_ender,z01_munic,z01_uf,z01_cgccpf,z01_ident 
                  from cgm 
				  where z01_numcgm = ".pg_result($result,0,"k00_numcgm"));
$X = 10;
$Y = 38;
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,21,'',"TB",0,'C');
$pdf->Text($X,$Y,"Numcgm:");
$pdf->Text($X,$Y + 4,"Nome:");
$pdf->Text($X,$Y + 8,"CgcCpf:");
$pdf->Text($X + 45,$Y + 8,"Identidade:");
$pdf->Text($X,$Y + 12,"Endereço:");
$pdf->Text($X,$Y + 16,"Município:");	
$pdf->Text($X + 55,$Y + 16,"UF:");
$pdf->SetFont('Arial','I',8);
$pdf->Text($X + 18,$Y,pg_result($dados,0,"z01_numcgm"));
$pdf->Text($X + 18,$Y + 4,pg_result($dados,0,"z01_nome"));
$pdf->Text($X + 18,$Y + 8,db_cgccpf(pg_result($dados,0,"z01_cgccpf")));
$pdf->Text($X + 18 + 45,$Y + 8,pg_result($dados,0,"z01_ident"));
$pdf->Text($X + 18,$Y + 12,pg_result($dados,0,"z01_ender"));
$pdf->Text($X + 18,$Y + 16,pg_result($dados,0,"z01_munic"));		
$pdf->Text($X + 18 + 45,$Y + 16,pg_result($dados,0,"z01_uf"));	

//DEBITOS
$pdf->SetXY(10,60);
$numrows = pg_numrows($result);

$TamMatric = 10;
$TamNumpar = 4;
$TamNumtot = 4;  
$TamK01_descr = 30;  
$TamK02_descr = 23;  
$TamReceit = 6;  
$TamVlrhis = 9; 
$TamVlrcor = 9;  
$TamVlrjuros = 9;  
$TamVlrmulta = 9;  
$TamVlrdesconto = 9;  
$TamTotal = 9;  

$pdf->SetFont('arial','B',7);
//  $pdf->Cell($TamMatric,5,"Mat","LRB",0,"C",0);
$pdf->Cell($TamNumpar,5,"P",1,0,"C",0);
$pdf->Cell($TamNumtot,5,"T",1,0,"C",0);
$pdf->Cell(17,5,"OPERAÇÃO",1,0,"C",0);
$pdf->Cell(17,5,"VENCIMENTO",1,0,"C",0);
$pdf->Cell($TamK01_descr,5,"DESCRIÇÃO",1,0,"C",0);
$pdf->Cell($TamReceit,5,"REC",1,0,"C",0);
$pdf->Cell($TamK02_descr,5,"DESCRIÇÃO",1,0,"C",0);
$pdf->Cell($TamVlrhis + 6,5,"VALOR",1,0,"C",0);
$pdf->Cell($TamVlrcor + 6,5,"CORRIGIDO",1,0,"C",0);
$pdf->Cell($TamVlrjuros + 6,5,"JUROS",1,0,"C",0);
$pdf->Cell($TamVlrmulta + 6,5,"MULTA",1,0,"C",0);
$pdf->Cell($TamVlrdesconto + 6,5,"DESCONTO",1,0,"C",0);
$pdf->Cell($TamTotal + 6,5,"TOTAL",1,1,"C",0);
$pdf->SetFont('arial','',7);

$tothis = 0;
$totcor = 0;
$totjuros = 0;
$totmulta = 0;
$totdesconto = 0;
$tottotal = 0;

$tothisp = 0;
$totcorp = 0;
$totjurosp = 0;
$totmultap = 0;
$totdescontop = 0;
$tottotalp = 0;

$xnumpre = pg_result($result,0,"k00_numpre");
$xnumtot = pg_result($result,0,"k00_tipo");

$valhis = 0;
$valcor = 0;
$valjuros = 0;
$valmulta = 0;
$valdesconto = 0;
$valtotal = 0;
$linha = 0;

for($i = 0;$i < $numrows;$i++) {

   if($pdf->GetY() > ( $pdf->h - 30 )){
     $linha = 0;
	 $pdf->AddPage();
     $pdf->SetFont('arial','B',7);
     $pdf->Cell($TamNumpar,5,"P",1,0,"C",0);   
     $pdf->Cell($TamNumtot,5,"T",1,0,"C",0);
     $pdf->Cell(17,5,"Dt oper.",1,0,"C",0);
     $pdf->Cell(17,5,"Dt venc.",1,0,"C",0);
     $pdf->Cell($TamK01_descr,5,"DESCRIÇÃO",1,0,"C",0);
     $pdf->Cell($TamReceit,5,"REC",1,0,"C",0);
     $pdf->Cell($TamK02_descr,5,"DESCRIÇÃO",1,0,"C",0);
     $pdf->Cell($TamVlrhis + 6,5,"VALOR",1,0,"C",0);
     $pdf->Cell($TamVlrcor + 6,5,"VAL COR",1,0,"C",0);
     $pdf->Cell($TamVlrjuros + 6,5,"JUROS",1,0,"C",0);
     $pdf->Cell($TamVlrmulta + 6,5,"MULTA",1,0,"C",0);
     $pdf->Cell($TamVlrdesconto + 6,5,"DESCONTO",1,0,"C",0);
     $pdf->Cell($TamTotal + 6,5,"TOTAL",1,1,"C",0);
     $pdf->SetFont('arial','',6);
  }
  if ( $xnumpre != pg_result($result,$i,"k00_numpre")){ 
	$pdf->SetFont('arial','B',8);
    $pdf->Cell($TamNumpar+$TamNumtot+17+17+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DO NUMPRE ".$xnumpre,"T",0,"L",1);
    $pdf->Cell($TamVlrhis + 6,5,db_formatar($tothisp,'f'),1,0,"R",1);
    $pdf->Cell($TamVlrcor + 6,5,db_formatar($totcorp,'f'),1,0,"R",1);
    $pdf->Cell($TamVlrjuros + 6,5,db_formatar($totjurosp,'f'),1,0,"R",1);
    $pdf->Cell($TamVlrmulta + 6,5,db_formatar($totmultap,'f'),1,0,"R",1);
    $pdf->Cell($TamVlrdesconto + 6,5,db_formatar($totdescontop,'f'),1,0,"R",1);
    $pdf->Cell($TamTotal + 6,5,db_formatar($tottotalp,'f'),1,1,"R",1);
    $pdf->SetFont('arial','',6);
    $tothisp = 0;
    $totcorp = 0;
    $totjurosp = 0;
    $totmultap = 0;
    $totdescontop = 0;
    $tottotalp = 0;
  }

  if ( $xnumtot != pg_result($result,$i,"k00_tipo")){ 
    $sql1 = " select k00_descr from arretipo where k00_tipo = $xnumtot and k00_instit = ".db_getsession('DB_instit') ;
    $pdf->SetFont('arial','B',8);
    $pdf->Cell($TamNumpar+$TamNumtot+17+17+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DO TIPO : ".$xnumtot." - ".pg_result(pg_exec($sql1),0,"k00_descr"),"T",0,"L",1);
    $pdf->Cell($TamVlrhis + 6,5,db_formatar($tothis,'f'),1,0,"R",1);
    $pdf->Cell($TamVlrcor + 6,5,db_formatar($totcor,'f'),1,0,"R",1);
    $pdf->Cell($TamVlrjuros + 6,5,db_formatar($totjuros,'f'),1,0,"R",1);
    $pdf->Cell($TamVlrmulta + 6,5,db_formatar($totmulta,'f'),1,0,"R",1);
    $pdf->Cell($TamVlrdesconto + 6,5,db_formatar($totdesconto,'f'),1,0,"R",1);
    $pdf->Cell($TamTotal + 6,5,db_formatar($tottotal,'f'),1,1,"R",1);
    $pdf->SetFont('arial','',6);
    $pdf->Ln(3);
    $tothis = 0;
    $totcor = 0;
    $totjuros = 0;
    $totmulta = 0;
    $totdesconto = 0;
    $tottotal = 0;
  }
  $pdf->SetFont('arial','',6);
  $pdf->Cell($TamNumpar,4,pg_result($result,$i,"k00_numpar"),"LR",0,"C",0);
  $pdf->Cell($TamNumtot,4,pg_result($result,$i,"k00_numtot"),"R",0,"C",0);
  $dtoper = pg_result($result,$i,"k00_dtoper");
  $dtoper = mktime(0,0,0,substr($dtoper,5,2),substr($dtoper,8,2),substr($dtoper,0,4));
  $pdf->Cell(17,4,date("d-m-Y",$dtoper),"R",0,"C",0);
  $dtvenc = pg_result($result,$i,"k00_dtvenc");
  $dtvenc = mktime(0,0,0,substr($dtvenc,5,2),substr($dtvenc,8,2),substr($dtvenc,0,4));
  $pdf->Cell(17,4,date("d-m-Y",$dtvenc),"R",0,"C",0);
  $pdf->Cell($TamK01_descr,4,substr(trim(pg_result($result,$i,"k01_descr")),0,20),"R",0,"L",0);
  $pdf->Cell($TamReceit,4,pg_result($result,$i,"k00_receit"),"R",0,"C",0);
  $pdf->Cell($TamK02_descr,4,substr(trim(pg_result($result,$i,"k02_descr")),0,15),"R",0,"L",0);
  $pdf->SetFont('arial','',8);
  $pdf->Cell($TamVlrhis + 6,4,db_formatar(pg_result($result,$i,"vlrhis"),'f'),"R",0,"R",0);
  $pdf->Cell($TamVlrcor + 6,4,db_formatar(pg_result($result,$i,"vlrcor"),'f'),"R",0,"R",0);
  $pdf->Cell($TamVlrjuros + 6,4,db_formatar(pg_result($result,$i,"vlrjuros"),'f'),"R",0,"R",0);
  $pdf->Cell($TamVlrmulta + 6,4,db_formatar(pg_result($result,$i,"vlrmulta"),'f'),"R",0,"R",0);
  $pdf->Cell($TamVlrdesconto + 6,4,db_formatar(pg_result($result,$i,"vlrdesconto"),'f'),"R",0,"R",0);
  $pdf->Cell($TamTotal + 6,4,db_formatar(pg_result($result,$i,"total"),'f'),"R",0,"R",0);
  $pdf->Cell(1,4,"",0,1,0,0);
  $tothisp += pg_result($result,$i,"vlrhis");
  $totcorp += pg_result($result,$i,"vlrcor");
  $totjurosp += pg_result($result,$i,"vlrjuros");
  $totmultap += pg_result($result,$i,"vlrmulta");
  $totdescontop += pg_result($result,$i,"vlrdesconto");
  $tottotalp += pg_result($result,$i,"total");
  $xnumpre = pg_result($result,$i,"k00_numpre");

  $tothis += pg_result($result,$i,"vlrhis");
  $totcor += pg_result($result,$i,"vlrcor");
  $totjuros += pg_result($result,$i,"vlrjuros");
  $totmulta += pg_result($result,$i,"vlrmulta");
  $totdesconto += pg_result($result,$i,"vlrdesconto");
  $tottotal += pg_result($result,$i,"total");
  $xnumtot = pg_result($result,$i,"k00_tipo");

  $valhis += pg_result($result,$i,"vlrhis");
  $valcor += pg_result($result,$i,"vlrcor");
  $valjuros += pg_result($result,$i,"vlrjuros");
  $valmulta += pg_result($result,$i,"vlrmulta");
  $valdesconto += pg_result($result,$i,"vlrdesconto");
  $valtotal += pg_result($result,$i,"total");
}

$pdf->SetFont('arial','B',8);
$pdf->Cell($TamNumpar+$TamNumtot+17+17+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DO NUMPRE ".$xnumpre,"T",0,"L",1);
$pdf->Cell($TamVlrhis + 6,5,db_formatar($tothisp,'f'),1,0,"R",1);
$pdf->Cell($TamVlrcor + 6,5,db_formatar($totcorp,'f'),1,0,"R",1);
$pdf->Cell($TamVlrjuros + 6,5,db_formatar($totjurosp,'f'),1,0,"R",1);
$pdf->Cell($TamVlrmulta + 6,5,db_formatar($totmultap,'f'),1,0,"R",1);
$pdf->Cell($TamVlrdesconto + 6,5,db_formatar($totdescontop,'f'),1,0,"R",1);
$pdf->Cell($TamTotal + 6,5,db_formatar($tottotalp,'f'),1,1,"R",1);

$sql1 = " select k00_descr from arretipo where k00_tipo = $xnumtot and k00_instit = ".db_getsession('DB_instit') ;
$pdf->Cell($TamNumpar+$TamNumtot+17+17+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DO TIPO : ".$xnumtot." - ".pg_result(pg_exec($sql1),0,"k00_descr"),"T",0,"L",1);
$pdf->Cell($TamVlrhis + 6,5,db_formatar($tothis,'f'),1,0,"R",1);
$pdf->Cell($TamVlrcor + 6,5,db_formatar($totcor,'f'),1,0,"R",1);
$pdf->Cell($TamVlrjuros + 6,5,db_formatar($totjuros,'f'),1,0,"R",1);
$pdf->Cell($TamVlrmulta + 6,5,db_formatar($totmulta,'f'),1,0,"R",1);
$pdf->Cell($TamVlrdesconto + 6,5,db_formatar($totdesconto,'f'),1,0,"R",1);
$pdf->Cell($TamTotal + 6,5,db_formatar($tottotal,'f'),1,1,"R",1);
$pdf->Ln(3);


//TOTAL
//$pdf->SetFont('arial','B',6);
$pdf->Cell($TamNumpar+$TamNumtot+17+17+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL GERAL : ".$xnumtot." - ".pg_result(pg_exec($sql1),0,"k00_descr"),"T",0,"L",1);
$pdf->Cell($TamVlrhis + 6,5,db_formatar($valhis,'f'),1,0,"R",1);
$pdf->Cell($TamVlrcor + 6,5,db_formatar($valcor,'f'),1,0,"R",1);
$pdf->Cell($TamVlrjuros + 6,5,db_formatar($valjuros,'f'),1,0,"R",1);
$pdf->Cell($TamVlrmulta + 6,5,db_formatar($valmulta,'f'),1,0,"R",1);
$pdf->Cell($TamVlrdesconto + 6,5,db_formatar($valdesconto,'f'),1,0,"R",1);
$pdf->Cell($TamTotal + 6,5,db_formatar($valtotal,'f'),1,1,"R",1);
$pdf->Output();
//header('Content-Type: application/pdf');
?>