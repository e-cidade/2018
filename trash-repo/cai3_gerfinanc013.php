<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
include("classes/db_cgm_classe.php");

$head1 = "";
$head2 = "";
$head3 = "SECRETARIA DA FAZENDA";
$head4 = "Relatório Total dos Débitos Analítico";
$head5 = "";
$head7 = "";
$head8 = "";
$head9 = "";

include("fpdf151/pdf.php");
require("libs/db_sql.php");
require("libs/db_utils.php");
db_postmemory($HTTP_SERVER_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));

if (isset($desconto)) {
  if ($desconto == 0) {
    $head6 = 'Sem Desconto';
  } else {
    $head6 = 'Com Desconto';
  }
} else {
  $head6 = 'Sem Desconto';
  $desconto = 0;
}

$sTiposDebitos = $tipos;
$tipos = split(",",$tipos);
$tipostodos = split(",",$tipostodos);

$head6 = "Débitos Calculados até: ".db_formatar($db_datausu,'d');
$where = "";
$and = " and ";
if (($dtini != "--") && ($dtfim != "--")) {
	$where = $where. $and. " k00_dtoper  between '$dtini' and '$dtfim'  ";
	$dtini = db_formatar($dtini, "d");
	$dtfim = db_formatar($dtfim, "d");
	$info = "De $dtini até $dtfim.";
	$and = " and ";
} else if ($dtini != "--") {
	$where = $where. $and. " k00_dtoper >= '$dtini'  ";
	$dtini = db_formatar($dtini, "d");
	$info = "Apartir de $dtini.";
	$and = " and ";
} else if ($dtfim != "--") {
	$where = $where. $and. " k00_dtoper <= '$dtfim'   ";
	$dtfim = db_formatar($dtfim, "d");
	$info = "Até $dtfim.";
	$and = " and ";
}

if (($exercini != "") && ($exercfim != "")) {
	$where = $where. $and. " fc_arrecexerc(y.k00_numpre,y.k00_numpar)  between '$exercini' and '$exercfim'  ";	
	$info1 = "Do exercício $exercini até $exercfim.";
	$and = " and ";
} else if ($exercini != "") {
	$where = $where. $and. " fc_arrecexerc(y.k00_numpre,y.k00_numpar) >= '$exercini'  ";	
	$info1 = "Apartir do exercício $exercini.";
	$and = " and ";
} else if ($exercfim != "") {
	$where = $where. $and. " fc_arrecexerc(y.k00_numpre,y.k00_numpar) <= '$exercfim'   ";	
	$info1 = "Até o exercício $exercfim.";
	$and = " and ";
}
if ($parReceit != ''){

  if ($parReceit != ''){

	  $where .= $where.$and." y.k00_receit in($parReceit)";

  }

}
$head5 = @$info;
$head6 = @$info1;

if (!empty($numcgm)) { 
  $clcgm = new cl_cgm;
  $result = $clcgm->sql_record($clcgm->sql_query_file($numcgm,"z01_nome"));
  db_fieldsmemory($result,0);  
  $result_teste = debitos_numcgm($numcgm,0,0,$DB_DATACALC,$DB_anousu,'','k00_origem,k00_tipo,k00_numpre,k00_numpar,k00_receit'); 
   
  $result = debitos_numcgm($numcgm,0,0,$DB_DATACALC,$DB_anousu,'','k00_origem,k00_tipo,k00_numpre,k00_numpar,k00_receit', $where);  
  $outros = '';
  $z01_numcgm = $numcgm;
} else if (!empty($matric)) {
	$result_teste = debitos_matricula($matric,0,0,$DB_DATACALC,$DB_anousu,'','k00_tipo,k00_numpre,k00_numpar,k00_receit');
	
  $result = debitos_matricula($matric,0,0,$DB_DATACALC,$DB_anousu,'','k00_tipo,k00_numpre,k00_numpar,k00_receit', $where);
  $outros = "Matrícula: ".$matric.
  $cliptubase = new cl_iptubase;
  $result_inf = $cliptubase->proprietario_record($cliptubase->proprietario_query($matric,"j34_setor#j34_quadra#j34_lote#tipopri#j39_numero#j39_compl#nomepri#z01_nome#z01_numcgm#z01_cgmpri"));
  if ($cliptubase->numrows!=0) {
    db_fieldsmemory($result_inf,0);
    $z01_numcgm = $z01_cgmpri;
    $outros = "Matrícula: ".$matric." - SQL: ".$j34_setor."/".$j34_quadra."/".$j34_lote." - Logradouro: ".$tipopri." ".$nomepri.", ".$j39_numero." ".$j39_compl;
  } else {
  }
} else if (!empty($inscr)) {
	$result_teste = debitos_inscricao($inscr,0,0,$DB_DATACALC,$DB_anousu,'','k00_tipo,k00_numpre,k00_numpar,k00_receit');
  $result = debitos_inscricao($inscr,0,0,$DB_DATACALC,$DB_anousu,'','k00_tipo,k00_numpre,k00_numpar,k00_receit', $where);
  $outros = "Inscrição: ".$inscr;
  $clissbase = new cl_issbase; 
  $result_inf = pg_exec("select * from empresa where q02_inscr = $inscr");
  if (pg_numrows($result_inf)!=0) {
    db_fieldsmemory($result_inf,0);
    $z01_numcgm = $q02_numcgm;
  } else {
  }
} else if (!empty($numpre)) {
	$result_teste = debitos_numpre($numpre,0,0,$DB_DATACALC,$DB_anousu,0,'','k00_tipo,k00_numpre,k00_numpar,k00_receit');  
  $outros = "Código Arrecadação: ".$numpre;
  $result = debitos_numpre($numpre,0,0,$DB_DATACALC,$DB_anousu,0,'','k00_tipo,k00_numpre,k00_numpar,k00_receit');
  $z01_numcgm = pg_result($result,0,"k00_numcgm");
  $clcgm = new cl_cgm;
  $result = $clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,"z01_nome"));
  db_fieldsmemory($result,0);
  //$result = debitos_numpre($numpre,0,0,$DB_DATACALC,$DB_anousu,0,'','k00_tipo,k00_numpre,k00_receit,k00_numpar',$desconto);
  $result = debitos_numpre($numpre,0,0,$DB_DATACALC,$DB_anousu,0,'','k00_tipo,k00_numpre,k00_numpar,k00_receit');
  $result = debitos_numpre($numpre,0,0,$DB_DATACALC,$DB_anousu,0,'','k00_tipo,k00_numpre,k00_numpar,k00_receit', $where);
}

$pdf = new PDF();
$pdf->Open();
$pdf->AliasNbPages();

//$pdf->AddPage();
$pdf->SetFillColor(235);

//Dados
$dados = pg_exec("select z01_numcgm,z01_nome,z01_ender,z01_munic,z01_uf,z01_cgccpf,z01_ident,z01_numero,z01_compl
from cgm where z01_numcgm = $z01_numcgm");

//DEBITOS
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

//Cabecalho dos debitos
$pdf->SetFont('arial','B',6);

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
$tottotalp= 0;

$xnumpre = pg_result($result,0,"k00_numpre");
$xnumtot = pg_result($result,0,"k00_tipo");

if(!empty($numcgm)) {
  $xorigem = pg_result($result,0,"k00_origem");
}
$nTotHisOrig      = 0;
$nTotCorOrig      = 0;
$nTotJurosOrig    = 0;
$nTotMultaOrig    = 0;
$nTotDescontoOrig = 0;
$nTotTotalOrig    = 0;

$valhis = 0;
$valcor = 0;
$valjuros = 0;
$valmulta = 0;
$valdesconto = 0;
$valtotal = 0;
$linha = 0;

$troca = 1;
$cabec = false;

for ($i = 0; $i < $numrows; $i++) {
  //   $pdf->setx(5);
  if ($pdf->GetY() > ( $pdf->h - 30  ) || $troca != 0) {
    $troca = 0;
    $linha = 0;
    $pdf->AddPage();
    $pdf->setxy(5,35);
    $X = 5;
    $Y = 38;
    $pdf->SetFont('Arial','B',8);
    if(!$cabec) {
      $pdf->Cell(0,21,'',"TB",0,'C');
      $pdf->Text($X,$Y,"Numcgm:");
      $pdf->Text($X+40,$Y,$outros);
      $pdf->Text($X,$Y + 4,"Nome:");
      $pdf->Text($X,$Y + 8,"CNPJ/CPF:");
      $pdf->Text($X + 45,$Y + 8,"Identidade:");
      $pdf->Text($X,$Y + 12,"Endereço:");
      $pdf->Text($X + 110,$Y + 12,"Número:");
      $pdf->Text($X + 155,$Y + 12,"Complemento:");
      $pdf->Text($X,$Y + 16,"Município:");
      $pdf->Text($X + 55,$Y + 16,"UF:");
      $pdf->SetFont('Arial','I',8);
      $pdf->Text($X + 18,     $Y     ,pg_result($dados,0,"z01_numcgm"));
      $pdf->Text($X + 18,     $Y + 4 ,pg_result($dados,0,"z01_nome"));
      $pdf->Text($X + 18,     $Y + 8 ,db_cgccpf(pg_result($dados,0,"z01_cgccpf")));
      $pdf->Text($X + 18 + 45,$Y + 8 ,pg_result($dados,0,"z01_ident"));
      $pdf->Text($X + 18,     $Y + 12,pg_result($dados,0,"z01_ender"));
      $pdf->Text($X + 130,    $Y + 12,pg_result($dados,0,"z01_numero"));
      $pdf->Text($X + 180,    $Y + 12,pg_result($dados,0,"z01_compl"));
      $pdf->Text($X + 18,     $Y + 16,pg_result($dados,0,"z01_munic"));
      $pdf->Text($X + 18 + 45,$Y + 16,pg_result($dados,0,"z01_uf"));
      $pdf->SetXY(5,60);
      $cabec = true;
    }
    $pdf->SetFont('arial','B',6);
    
    $pdf->Cell(2,5," ",0,0,"C",0);
    $pdf->Cell($TamNumpar,5,"P",1,0,"C",0);
    $pdf->Cell($TamNumtot,5,"T",1,0,"C",0);
    $pdf->Cell(13,5,"OPER.",1,0,"C",0);
    $pdf->Cell(13,5,"VENC.",1,0,"C",0);
    $pdf->Cell(13,5,"ORIGEM",1,0,"C",0);
    $pdf->Cell($TamK01_descr,5,"DESCRIÇÃO",1,0,"C",0);
    $pdf->Cell($TamReceit,5,"REC",1,0,"C",0);
    $pdf->Cell($TamK02_descr,5,"DESCRIÇÃO",1,0,"C",0);
    $pdf->Cell($TamVlrhis + 6,5,"VALOR",1,0,"C",0);
    $pdf->Cell($TamVlrcor + 6,5,"CORRIGIDO",1,0,"C",0);
    $pdf->Cell($TamVlrjuros + 6,5,"JUROS",1,0,"C",0);
    $pdf->Cell($TamVlrmulta + 6,5,"MULTA",1,0,"C",0);
    $pdf->Cell($TamVlrdesconto + 6,5,"DESCONTO",1,0,"C",0);
    $pdf->Cell($TamTotal + 6,5,"TOTAL",1,1,"C",0);
    $pdf->SetFont('arial','',6);

  }
  $numpre = pg_result($result,$i,"k00_numpre");  
  $sqlparcel = "select * from termo where v07_numpre = $xnumpre ";
  $resultparcel = pg_exec($sqlparcel);
  $linhasparcel = pg_num_rows($resultparcel);
  if ($linhasparcel>0) {
    $temparcel  = true;
    $v07_parcel = pg_result($resultparcel, 0, "v07_parcel");   
  } else {
    $temparcel  = false;
    $v07_parcel = "nao em parcel";
  }    
 
  $sql_tipo_debito = "";
  $sql_tipo_debito .= " select arretipo.k00_tipo, k03_tipo from caixa.arrecad ";
  $sql_tipo_debito .= " inner join caixa.arretipo on arrecad.k00_tipo = arretipo.k00_tipo ";
  $sql_tipo_debito .= " where k00_numpre = $xnumpre limit 1";
  $result_tipo_debito = pg_exec($sql_tipo_debito) or die($sql_tipo_debito);
  $tipo_debito = pg_result($result_tipo_debito,0,"k00_tipo");
  $k03_tipo    = pg_result($result_tipo_debito,0,"k03_tipo");

  $cProcessoForo = "";
  if ( $k03_tipo == 18 ) {
    $sql_processoforo  = "";
    $sql_processoforo .= " select distinct v55_codforo ";
    $sql_processoforo .= " from juridico.inicial ";
    $sql_processoforo .= " inner join juridico.inicialnumpre on inicial.v50_inicial = inicialnumpre.v59_inicial ";
    $sql_processoforo .= " inner join juridico.inicialcodforo on inicialcodforo.v55_inicial = inicial.v50_inicial ";
    $sql_processoforo .= " where inicialnumpre.v59_numpre = $xnumpre ";
    $result_processoforo = pg_exec($sql_processoforo) or die($sql_processoforo);
    if ( pg_numrows($result_processoforo) > 0 ) {
      $cProcessoForo = " - PROCESSO FORO: " . pg_result($result_processoforo,0,0);
    }
  }

  if ( $k03_tipo == 13 ) {
    $sqldivida  = "";
    $sqldivida .= " select distinct v01_exerc ";
    $sqldivida .= " from divida.termo ";
    $sqldivida .= " inner join divida.termoini on termoini.parcel = termo.v07_parcel ";
    $sqldivida .= " inner join juridico.inicialcert on inicialcert.v51_inicial = termoini.inicial ";
    $sqldivida .= " inner join divida.certdiv on certdiv.v14_certid = inicialcert.v51_certidao ";
    $sqldivida .= " inner join divida.divida on certdiv.v14_coddiv = divida.v01_coddiv ";
    $sqldivida .= " where termo.v07_numpre = $xnumpre ";
  } else {
    $sqldivida = "select distinct v01_exerc from divida where v01_numpre = $xnumpre";
  }
  $resultdivida = pg_exec($sqldivida) or die($sqldivida);

  $exercicios="";

  for ($exerc=0; $exerc < pg_numrows($resultdivida); $exerc++) {
    db_fieldsmemory($resultdivida,$exerc);
    if ( strlen($v01_exerc) == 4 ) {
      $v01_exerc = substr($v01_exerc,2,2);
    }
    $exercicios.=$v01_exerc.($exerc != pg_numrows($resultdivida) -1?",":"");
  }

  if (($xnumpre != pg_result($result,$i,"k00_numpre")) && ( in_array($xnumtot,$tipos) == true )) {
    $pdf->setx(5);
    $pdf->SetFont('arial','B',6);
    $pdf->Cell(2+$TamNumpar+$TamNumtot+13+13+13+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DO NUMPRE (1) ".$xnumpre .($temparcel==true?" - PARCELAMENTO: " . $v07_parcel . " (" . (strlen($exercicios) != ""?$exercicios:"").")":(strlen($exercicios) != ""?" - EXERCICIO: " . $exercicios:"")) . $cProcessoForo,"T",0,"L",1);
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

  if (( $xnumtot != pg_result($result,$i,"k00_tipo")) && ( in_array($xnumtot,$tipos) == true )) {
    $sql1 = " select k00_descr from arretipo where k00_tipo = $xnumtot and k00_instit = ".db_getsession('DB_instit') ;
    $pdf->setx(5);
    $pdf->SetFont('arial','B',6);
    $pdf->Cell(2+$TamNumpar+$TamNumtot+13+13+13+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DO TIPO : ".$xnumtot." - ".pg_result(pg_exec($sql1),0,"k00_descr"),"T",0,"L",1);
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
  
  if (!empty($numcgm)) {
    if (($xorigem != pg_result($result,$i,"k00_origem")) && ( in_array($xnumtot,$tipos) == true )) {
    	if($xorigem=='M-87'){
    	 echo "<br>Matricula - $xorigem - xnumtot = $xnumtot - tipos = $tipos<br>";
    	 echo " ".db_formatar($nTotHisOrig,'f');
	     echo " ".db_formatar($nTotCorOrig,'f') ;
	     echo " ".db_formatar($nTotJurosOrig,'f');
	     echo " ".db_formatar($nTotMultaOrig,'f');
	     echo " ".db_formatar($nTotDescontoOrig,'f');
	     echo " ".db_formatar($nTotTotalOrig,'f');
      
    	}
    	
      $pdf->setx(5);
      $pdf->SetFont('arial','B',6);
      $pdf->Cell(2+$TamNumpar+$TamNumtot+13+13+13+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DA ORIGEM ".$xorigem ,"T",0,"L",1);
      $pdf->Cell($TamVlrhis + 6,5,      db_formatar($nTotHisOrig,'f'),     1,0,"R",1);
      $pdf->Cell($TamVlrcor + 6,5,      db_formatar($nTotCorOrig,'f'),     1,0,"R",1);
      $pdf->Cell($TamVlrjuros + 6,5,    db_formatar($nTotJurosOrig,'f'),   1,0,"R",1);
      $pdf->Cell($TamVlrmulta + 6,5,    db_formatar($nTotMultaOrig,'f'),   1,0,"R",1);
      $pdf->Cell($TamVlrdesconto + 6,5, db_formatar($nTotDescontoOrig,'f'),1,0,"R",1);
      $pdf->Cell($TamTotal + 6,5,       db_formatar($nTotTotalOrig,'f'),   1,1,"R",1);
      $pdf->SetFont('arial','',6);
      $pdf->Ln(3);
      $nTotHisOrig      = 0;
      $nTotCorOrig      = 0;
      $nTotJurosOrig    = 0;
      $nTotMultaOrig    = 0;
      $nTotDescontoOrig = 0;
      $nTotTotalOrig    = 0;
    }
  }
  
  if (in_array(pg_result($result,$i,"k00_tipo"),$tipos) != null ) {
    if(empty($numcgm)) {
      $matinsc = '';
      $sql1 = "select distinct 
                      case 
                        when b.k00_matric is null then 
                          'I-'||c.k00_inscr
                        else 
                          'M-'||b.k00_matric 
                      end as matinsc
                 from arrecad a
								 inner join arreinstit on arreinstit.k00_numpre =  a.k00_numpre 
                     								  and arreinstit.k00_instit = ".db_getsession('DB_instit')." 
            left join arrematric b on a.k00_numpre = b.k00_numpre
            left join arreinscr  c on a.k00_numpre = c.k00_numpre
                where a.k00_numpre = ".pg_result($result,$i,"k00_numpre")." limit 1";
      $result1 = pg_exec($sql1);
      $matinsc = pg_result($result1,0,"matinsc");
    } else {
      $matinsc = pg_result($result,$i,"k00_origem");
    }
    $pdf->setx(5);
    $pdf->SetFont('arial','',6);
    
    $dtvenc = pg_result($result,$i,"k00_dtvenc");
    $dtvenc = mktime(0,0,0,substr($dtvenc,5,2),substr($dtvenc,8,2),substr($dtvenc,0,4));
    if ($dtvenc < $DB_DATACALC) {
      // se vencimento menos que data de hj
      $sinal = chr(253);
    }
    // define sinal
    else {
      // senao
      $sinal = "";
    }
    // defina sinal em branco
    
    $pdf->SetFont('zapfdingbats','',6);
    $pdf->Cell(2,4,$sinal,"",0,"C",0);
    $pdf->SetFont('arial','',6);
    $pdf->Cell($TamNumpar,4,pg_result($result,$i,"k00_numpar"),"LR",0,"C",0);
    $pdf->Cell($TamNumtot,4,pg_result($result,$i,"k00_numtot"),"R",0,"C",0);
    $dtoper = pg_result($result,$i,"k00_dtoper");
    $dtoper = mktime(0,0,0,substr($dtoper,5,2),substr($dtoper,8,2),substr($dtoper,0,4));
    $pdf->Cell(13,4,date("d-m-Y",$dtoper),"R",0,"C",0);
    
    $pdf->Cell(13,4,date("d-m-Y",$dtvenc),"R",0,"C",0);
    $pdf->cell(13,4,$matinsc,"R",0,"L",0);
    $pdf->Cell($TamK01_descr,4,substr(trim(pg_result($result,$i,"k01_descr")),0,20),"R",0,"L",0);
    $pdf->Cell($TamReceit,4,pg_result($result,$i,"k00_receit"),"R",0,"C",0);
    $pdf->Cell($TamK02_descr,4,substr(trim(pg_result($result,$i,"k02_descr")),0,15),"R",0,"L",0);
    $pdf->SetFont('arial','',6);
    $pdf->Cell($TamVlrhis + 6,4,db_formatar(pg_result($result,$i,"vlrhis"),'f'),"R",0,"R",0);
    $pdf->Cell($TamVlrcor + 6,4,db_formatar(pg_result($result,$i,"vlrcor"),'f'),"R",0,"R",0);
    $pdf->Cell($TamVlrjuros + 6,4,db_formatar(pg_result($result,$i,"vlrjuros"),'f'),"R",0,"R",0);
    $pdf->Cell($TamVlrmulta + 6,4,db_formatar(pg_result($result,$i,"vlrmulta"),'f'),"R",0,"R",0);
    $pdf->Cell($TamVlrdesconto + 6,4,db_formatar(pg_result($result,$i,"vlrdesconto"),'f'),"R",0,"R",0);
    $pdf->Cell($TamTotal + 6,4,db_formatar(pg_result($result,$i,"total"),'f'),"R",0,"R",0);
    $pdf->Cell(1,4,"",0,1,0,0);
    
    $tothisp      += pg_result($result,$i,"vlrhis");
    $totcorp      += pg_result($result,$i,"vlrcor");
    $totjurosp    += pg_result($result,$i,"vlrjuros");
    $totmultap    += pg_result($result,$i,"vlrmulta");
    $totdescontop += pg_result($result,$i,"vlrdesconto");
    $tottotalp    += pg_result($result,$i,"total");
    
    $tothis      += pg_result($result,$i,"vlrhis");
    $totcor      += pg_result($result,$i,"vlrcor");
    $totjuros    += pg_result($result,$i,"vlrjuros");
    $totmulta    += pg_result($result,$i,"vlrmulta");
    $totdesconto += pg_result($result,$i,"vlrdesconto");
    $tottotal    += pg_result($result,$i,"total");
    
    if (($xorigem != pg_result($result,$i,"k00_origem"))) {
    	
    	$nTotHisOrig      = 0;
      $nTotCorOrig      = 0;
      $nTotJurosOrig    = 0;
      $nTotMultaOrig    = 0;
      $nTotDescontoOrig = 0;
      $nTotTotalOrig    = 0;
   
    }
	    $nTotHisOrig      += pg_result($result,$i,"vlrhis");
	    $nTotCorOrig      += pg_result($result,$i,"vlrcor");
	    $nTotJurosOrig    += pg_result($result,$i,"vlrjuros");
	    $nTotMultaOrig    += pg_result($result,$i,"vlrmulta");
	    $nTotDescontoOrig += pg_result($result,$i,"vlrdesconto");
	    $nTotTotalOrig    += pg_result($result,$i,"total");
   
    
    $valhis      += pg_result($result,$i,"vlrhis");
    $valcor      += pg_result($result,$i,"vlrcor");
    $valjuros    += pg_result($result,$i,"vlrjuros");
    $valmulta    += pg_result($result,$i,"vlrmulta");
    $valdesconto += pg_result($result,$i,"vlrdesconto");
    $valtotal    += pg_result($result,$i,"total");
  }
  $xnumpre = pg_result($result,$i,"k00_numpre");
  $xnumtot = pg_result($result,$i,"k00_tipo");
  if (!empty($numcgm)) {
    $xorigem = pg_result($result,$i,"k00_origem");
  }
}

$sqlparcel = "select * from termo where v07_numpre = $xnumpre and v07_instit = ".db_getsession('DB_instit') ;
$resultparcel = pg_exec($sqlparcel);
$linhasparcel = pg_num_rows($resultparcel);
if ($linhasparcel>0) {
  $temparcel  = true;
  $v07_parcel = pg_result($resultparcel, 0, "v07_parcel");
} else {
  $temparcel  = false;
  $v07_parcel = "nao em parcel";
}

$sql_tipo_debito = "";
$sql_tipo_debito .= " select arretipo.k00_tipo, k03_tipo from caixa.arrecad ";
$sql_tipo_debito .= " inner join caixa.arretipo on arrecad.k00_tipo = arretipo.k00_tipo ";
$sql_tipo_debito .= " where k00_numpre = $xnumpre limit 1";
$result_tipo_debito = pg_exec($sql_tipo_debito) or die($sql_tipo_debito);
$tipo_debito = pg_result($result_tipo_debito,0,"k00_tipo");
$k03_tipo    = pg_result($result_tipo_debito,0,"k03_tipo");

$cProcessoForo = "";
if ( $k03_tipo == 18 ) {
  $sql_processoforo  = "";
  $sql_processoforo .= " select distinct v55_codforo ";
  $sql_processoforo .= " from juridico.inicial ";
  $sql_processoforo .= " inner join juridico.inicialnumpre on inicial.v50_inicial = inicialnumpre.v59_inicial ";
  $sql_processoforo .= " inner join juridico.inicialcodforo on inicialcodforo.v55_inicial = inicial.v50_inicial ";
  $sql_processoforo .= " where inicialnumpre.v59_numpre = $xnumpre ";
  $result_processoforo = pg_exec($sql_processoforo) or die($sql_processoforo);
  if ( pg_numrows($result_processoforo) > 0 ) {
    $cProcessoForo = " - PROCESSO FORO: " . pg_result($result_processoforo,0,0);
  }
}

if ( $k03_tipo == 13 ) {
  $sqldivida  = "";
  $sqldivida .= " select distinct v01_exerc ";
  $sqldivida .= " from divida.termo ";
  $sqldivida .= " inner join divida.termoini on termoini.parcel = termo.v07_parcel ";
  $sqldivida .= " inner join juridico.inicialcert on inicialcert.v51_inicial = termoini.inicial ";
  $sqldivida .= " inner join divida.certdiv on certdiv.v14_certid = inicialcert.v51_certidao ";
  $sqldivida .= " inner join divida.divida on certdiv.v14_coddiv = divida.v01_coddiv ";
  $sqldivida .= " where termo.v07_numpre = $xnumpre ";
} else {
  $sqldivida = "select distinct v01_exerc from divida where v01_numpre = $xnumpre";
}
$resultdivida = pg_exec($sqldivida) or die($sqldivida);

$exercicios="";
for ($exerc=0; $exerc < pg_numrows($resultdivida); $exerc++) {
  db_fieldsmemory($resultdivida,$exerc);
  if ( strlen($v01_exerc) == 4 ) {
    $v01_exerc = substr($v01_exerc,2,2);
  }
  $exercicios.=$v01_exerc.($exerc != pg_numrows($resultdivida) -1?",":"");
}
  
if (in_array($xnumtot,$tipos) == true ) {
  $pdf->setx(5);
  $pdf->SetFont('arial','B',6);
  $pdf->Cell(2+$TamNumpar+$TamNumtot+13+13+13+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DO NUMPRE (2) ".$xnumpre . ($temparcel==true?" - PARCELAMENTO: " . $v07_parcel . " (" . (strlen($exercicios) != ""?$exercicios:"").")":(strlen($exercicios) != ""?" - EXERCICIO: " . $exercicios:"")) . $cProcessoForo,"T",0,"L",1);
  $pdf->Cell($TamVlrhis + 6,5,db_formatar($tothisp,'f'),1,0,"R",1);
  $pdf->Cell($TamVlrcor + 6,5,db_formatar($totcorp,'f'),1,0,"R",1);
  $pdf->Cell($TamVlrjuros + 6,5,db_formatar($totjurosp,'f'),1,0,"R",1);
  $pdf->Cell($TamVlrmulta + 6,5,db_formatar($totmultap,'f'),1,0,"R",1);
  $pdf->Cell($TamVlrdesconto + 6,5,db_formatar($totdescontop,'f'),1,0,"R",1);
  $pdf->Cell($TamTotal + 6,5,db_formatar($tottotalp,'f'),1,1,"R",1);
  
  $pdf->setx(5);
  $pdf->SetFont('arial','B',6);
  $sql1 = " select k00_descr from arretipo where k00_tipo = $xnumtot and k00_instit = ".db_getsession('DB_instit') ;
  $pdf->Cell(2+$TamNumpar+$TamNumtot+13+13+13+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DO TIPO : ".$xnumtot." - ".pg_result(pg_exec($sql1),0,"k00_descr"),"T",0,"L",1);
  $pdf->Cell($TamVlrhis + 6,5,db_formatar($tothis,'f'),1,0,"R",1);
  $pdf->Cell($TamVlrcor + 6,5,db_formatar($totcor,'f'),1,0,"R",1);
  $pdf->Cell($TamVlrjuros + 6,5,db_formatar($totjuros,'f'),1,0,"R",1);
  $pdf->Cell($TamVlrmulta + 6,5,db_formatar($totmulta,'f'),1,0,"R",1);
  $pdf->Cell($TamVlrdesconto + 6,5,db_formatar($totdesconto,'f'),1,0,"R",1);
  $pdf->Cell($TamTotal + 6,5,db_formatar($tottotal,'f'),1,1,"R",1);
}

if (!empty($numcgm)) {
    $pdf->setx(5);
    $pdf->SetFont('arial','B',6);
    $pdf->Cell(2+$TamNumpar+$TamNumtot+13+13+13+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL DA ORIGEM ".$xorigem ,"T",0,"L",1);
    $pdf->Cell($TamVlrhis + 6,5,      db_formatar($nTotHisOrig,'f'),     1,0,"R",1);
    $pdf->Cell($TamVlrcor + 6,5,      db_formatar($nTotCorOrig,'f'),     1,0,"R",1);
    $pdf->Cell($TamVlrjuros + 6,5,    db_formatar($nTotJurosOrig,'f'),   1,0,"R",1);
    $pdf->Cell($TamVlrmulta + 6,5,    db_formatar($nTotMultaOrig,'f'),   1,0,"R",1);
    $pdf->Cell($TamVlrdesconto + 6,5, db_formatar($nTotDescontoOrig,'f'),1,0,"R",1);
    $pdf->Cell($TamTotal + 6,5,       db_formatar($nTotTotalOrig,'f'),   1,1,"R",1);
    $pdf->SetFont('arial','',6);
    $pdf->Ln(3);
    $nTotHisOrig      = 0;
    $nTotCorOrig      = 0;
    $nTotJurosOrig    = 0;
    $nTotMultaOrig    = 0;
    $nTotDescontoOrig = 0;
    $nTotTotalOrig    = 0;
}


$pdf->Ln(3);

///TOTAL
$pdf->setx(5);
$pdf->SetFont('arial','B',6);
$pdf->Cell(2+$TamNumpar+$TamNumtot+13+13+13+$TamK01_descr+$TamReceit+$TamK02_descr,5,"TOTAL GERAL : ","T",0,"L",1);
$pdf->Cell($TamVlrhis + 6,5,db_formatar($valhis,'f'),1,0,"R",1);
$pdf->Cell($TamVlrcor + 6,5,db_formatar($valcor,'f'),1,0,"R",1);
$pdf->Cell($TamVlrjuros + 6,5,db_formatar($valjuros,'f'),1,0,"R",1);
$pdf->Cell($TamVlrmulta + 6,5,db_formatar($valmulta,'f'),1,0,"R",1);
$pdf->Cell($TamVlrdesconto + 6,5,db_formatar($valdesconto,'f'),1,0,"R",1);
$pdf->Cell($TamTotal + 6,5,db_formatar($valtotal,'f'),1,1,"R",1);
$pdf->ln();
if ($tipostodos != $tipos) {
  $pdf->SetFont('arial','B',11);
  $pdf->setx(7);
  $pdf->Cell(195,5,"*** EXISTEM MAIS TIPOS DE DÉBITOS LANÇADOS QUE NÃO FORAM LISTADOS NESTE RELATÓRIO ***",0,1,"L",1);
}
if (pg_numrows($result_teste)>pg_numrows($result)) {
	$pdf->SetFont('arial', 'B', 11);
	$pdf->setx(7);
	$pdf->Cell(195, 5, "*** EXISTEM MAIS DÉBITOS LANÇADOS QUE NÃO FORAM LISTADOS NESTE RELATÓRIO ***", 0, 1, "L", 1);
}

$pdf->Ln();

if (isset ($matric)) {

  $sSqlInnerTabela  = " inner join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
  $sSqlInnerTabela .= " left  join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre ";
  $sSqlWhereTabela  = " arrematric.k00_matric = $matric ";

} else if (isset ($inscr)) {

  $sSqlInnerTabela  = " inner join arreinscr  on arreinscr.k00_numpre = arresusp.k00_numpre ";
  $sSqlInnerTabela .= " left  join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
  $sSqlWhereTabela  = " arreinscr.k00_inscr  = $inscr ";
    
} else if (isset ($numcgm)) {

  $sSqlInnerTabela  = " inner join arrenumcgm on arrenumcgm.k00_numpre = arresusp.k00_numpre ";
  $sSqlInnerTabela .= " left  join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
  $sSqlInnerTabela .= " left  join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre ";
  $sSqlWhereTabela  = " arrenumcgm.k00_numcgm = $numcgm ";
    
} else if (isset ($numpre)) {

  $sSqlInnerTabela  = " left join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre ";
  $sSqlInnerTabela .= " left join arrematric on arrematric.k00_numpre = arresusp.k00_numpre ";
  $sSqlWhereTabela  = " arresusp.k00_numpre   = $numpre ";
    
}   
 
 
$sSqlSuspensao  = " select arresusp.*,		  								 	    ";
$sSqlSuspensao .= " 		  arretipo.k00_descr,	  							 	    ";
$sSqlSuspensao .= " 		  tabrec.k02_descr,		  							 	    ";
$sSqlSuspensao .= " 		  case	  											 	    ";
$sSqlSuspensao .= " 		    when k00_matric is not null then 'M-'||k00_matric  	    ";
$sSqlSuspensao .= " 		    else 'I-'||k00_inscr  							 	    ";
$sSqlSuspensao .= " 		  end as matinscr   					 	 			    ";
$sSqlSuspensao .= " 	 from arresusp  		  								 	    ";
$sSqlSuspensao .= " 	 inner join suspensao on suspensao.ar18_sequencial = arresusp.k00_suspensao ";   
$sSqlSuspensao .= " 	 inner join arretipo on arretipo.k00_tipo = arresusp.k00_tipo   ";
$sSqlSuspensao .= " 	 inner join tabrec   on tabrec.k02_codigo = arresusp.k00_receit ";   
$sSqlSuspensao .= " 	 {$sSqlInnerTabela}		 								 	    ";
$sSqlSuspensao .= " 	 where {$sSqlWhereTabela} 							 	 	    ";
$sSqlSuspensao .= " 	   and suspensao.ar18_situacao = 1 							    ";
if (trim($parReceit) != ""){
  $sSqlSuspensao .= "   and arresusp.k00_receit in ({$parReceit})			 	  	";   	
}      
$sSqlSuspensao .= " 	   and arresusp.k00_tipo   in ({$sTiposDebitos})			 	";
$sSqlSuspensao .= " 	   and arretipo.k00_instit = ".db_getsession('DB_instit');   
$sSqlSuspensao .= " 	 order by arresusp.k00_tipo,									";
$sSqlSuspensao .= " 			  arresusp.k00_numpre,									";
$sSqlSuspensao .= " 			  arresusp.k00_numpar,									";
$sSqlSuspensao .= " 			  arresusp.k00_receit 									";

$rsSuspensao      = pg_query($sSqlSuspensao);
$iLinhasSuspensao = pg_num_rows($rsSuspensao);
$aSuspensao		 = array();


if ( $iLinhasSuspensao > 0 ) {
  
 $nTotNumprehis  = 0;
 $nTotNumprecor  = 0;
 $nTotNumprejur  = 0;
 $nTotNumpremul  = 0;
 $nTotNumpredes  = 0;
 $nTotNumpretot  = 0;
      
 $nTotTipohis  	 = 0;
 $nTotTipocor    = 0;
 $nTotTipojur    = 0;
 $nTotTipomul    = 0;
 $nTotTipodes    = 0;
 $nTotTipotot    = 0;
   
 $nTotSusphis  	 = 0;
 $nTotSuspcor    = 0;
 $nTotSuspjur    = 0;
 $nTotSuspmul    = 0;
 $nTotSuspdes    = 0;
 $nTotSusptot    = 0;	 
 
   $pdf->SetFont('Arial', 'BI', 12);
   $pdf->Cell(0,5,'Débitos Suspensos',0,1,"C",0);
   $pdf->Ln();
   $pdf->SetFont('arial','B',6);
   $pdf->setx(5);
   $pdf->Cell(2 ,5," "			,0,0,"C",0);
   $pdf->Cell(4,5,"P"				,1,0,"C",0);
   $pdf->Cell(4,5,"T"				,1,0,"C",0);
   $pdf->Cell(13,5,"OPER."		,1,0,"C",0);
   $pdf->Cell(13,5,"VENC."		,1,0,"C",0);
   $pdf->Cell(13,5,"ORIGEM"		,1,0,"C",0);
   $pdf->Cell(30,5,"DESCRIÇÃO"	,1,0,"C",0);
   $pdf->Cell(6 ,5,"REC"			,1,0,"C",0);
   $pdf->Cell(23,5,"DESCRIÇÃO"	,1,0,"C",0);
   $pdf->Cell(15,5,"VALOR"		,1,0,"C",0);
   $pdf->Cell(15,5,"CORRIGIDO"	,1,0,"C",0);
   $pdf->Cell(15,5,"JUROS"		,1,0,"C",0);
   $pdf->Cell(15,5,"MULTA"		,1,0,"C",0);
   $pdf->Cell(15,5,"DESCONTO"		,1,0,"C",0);
   $pdf->Cell(15 ,5,"TOTAL"		,1,1,"C",0);     
      
   $iNumpre = null;
   $iTipo   = null;
   
   for ($i=0; $i < $iLinhasSuspensao; $i++) {
    
     $oSuspensao = db_utils::fieldsMemory($rsSuspensao,$i);
   
     $pdf->setx(5);
     $pdf->SetFont('arial','',6);
     $nTotal = ( $oSuspensao->k00_vlrcor + $oSuspensao->k00_vlrjur + $oSuspensao->k00_vlrmul ) - $oSuspensao->k00_vlrdes;
     
     $pdf->Cell(2 ,4," "								 		,   0,0,"C",0);
     $pdf->Cell(4 ,4,$oSuspensao->k00_numpar 					,"LR",0,"C",0);
   $pdf->Cell(4 ,4,$oSuspensao->k00_numtot 					, "R",0,"C",0);
   $pdf->Cell(13,4,$oSuspensao->k00_dtoper 					, "R",0,"C",0);
   $pdf->Cell(13,4,$oSuspensao->k00_dtvenc 					, "R",0,"C",0);
   $pdf->cell(13,4,$oSuspensao->matinscr   					, "R",0,"L",0);
   $pdf->Cell(30,4,substr(trim($oSuspensao->k00_descr),0,20), "R",0,"L",0);
   $pdf->Cell(6 ,4,$oSuspensao->k00_receit					, "R",0,"C",0);
   $pdf->Cell(23,4,substr(trim($oSuspensao->k02_descr),0,15), "R",0,"L",0);
   $pdf->Cell(15,4,db_formatar($oSuspensao->k00_valor,'f')	, "R",0,"R",0);
   $pdf->Cell(15,4,db_formatar($oSuspensao->k00_vlrcor,'f')	, "R",0,"R",0);
   $pdf->Cell(15,4,db_formatar($oSuspensao->k00_vlrjur,'f')	, "R",0,"R",0);
   $pdf->Cell(15,4,db_formatar($oSuspensao->k00_vlrmul,'f')	, "R",0,"R",0);
   $pdf->Cell(15,4,db_formatar($oSuspensao->k00_vlrdes,'f') , "R",0,"R",0);
   $pdf->Cell(15,4,db_formatar($nTotal,'f')					, "R",0,"R",0);
   $pdf->Cell(1,4,"",0,1,0,0);
   
   $nTotNumprehis  += $oSuspensao->k00_valor;
   $nTotNumprecor  += $oSuspensao->k00_vlrcor;
   $nTotNumprejur  += $oSuspensao->k00_vlrjur;
   $nTotNumpremul  += $oSuspensao->k00_vlrmul;
   $nTotNumpredes  += $oSuspensao->k00_vlrdes;
   $nTotNumpretot  += $nTotal;
   
   $nTotTipohis    += $oSuspensao->k00_valor;
   $nTotTipocor    += $oSuspensao->k00_vlrcor;
   $nTotTipojur    += $oSuspensao->k00_vlrjur;
   $nTotTipomul    += $oSuspensao->k00_vlrmul;
   $nTotTipodes    += $oSuspensao->k00_vlrdes;
   $nTotTipotot    += $nTotal;
   
   $nTotSusphis    += $oSuspensao->k00_valor;
   $nTotSuspcor    += $oSuspensao->k00_vlrcor;
   $nTotSuspjur    += $oSuspensao->k00_vlrjur;
   $nTotSuspmul    += $oSuspensao->k00_vlrmul;
   $nTotSuspdes    += $oSuspensao->k00_vlrdes;
   $nTotSusptot    += $nTotal;	   
   
   if ( $oSuspensao->k00_numpre != $iNumpre ) {
     
     if ( $i == 0 ) { 
       $iNumpre = $oSuspensao->k00_numpre;
       $iTipo   = $oSuspensao->k00_tipo;
       continue;
     } 
     
     $pdf->setx(7);
     $pdf->SetFont('arial','B',6);
     $pdf->Cell(106,5,"TOTAL DO NUMPRE ".$oSuspensao->k00_numpre,"T",0,"L",1);
     $pdf->Cell(15 ,5,db_formatar($nTotNumprehis,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotNumprecor,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotNumprejur,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotNumpremul,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotNumpredes,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotNumpretot,'f'),1,1,"R",1);
     $pdf->SetFont('arial','',6);
     
   $nTotNumprehis  = 0;
     $nTotNumprecor  = 0;
     $nTotNumprejur  = 0;
     $nTotNumpremul  = 0;
     $nTotNumpredes  = 0;
     $nTotNumpretot  = 0;	     

     $iNumpre 		 = $oSuspensao->k00_numpre;
   }

   if ( $oSuspensao->k00_tipo != $iTipo ) {
     
     $pdf->setx(7);
     $pdf->SetFont('arial','B',6);
     $pdf->Cell(106,5,"TOTAL DO TIPO ".$oSuspensao->k00_tipo,"T",0,"L",1);
     $pdf->Cell(15 ,5,db_formatar($nTotTipohis,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotTipocor,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotTipojur,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotTipomul,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotTipodes,'f'),1,0,"R",1);
     $pdf->Cell(15 ,5,db_formatar($nTotTipotot,'f'),1,1,"R",1);
     $pdf->SetFont('arial','',6);
     
   $nTotTipohis  = 0;
     $nTotTipocor  = 0;
     $nTotTipojur  = 0;
     $nTotTipomul  = 0;
     $nTotTipodes  = 0;
     $nTotTipotot  = 0;	     

     $iTipo   	   = $oSuspensao->k00_tipo;
   }
   
   
   }
   $pdf->SetFont('arial','B',6);
   
   $pdf->setx(7);
   $pdf->Cell(106,5,"TOTAL DO NUMPRE ".$oSuspensao->k00_numpre,"T",0,"L",1);
   $pdf->Cell(15 ,5,db_formatar($nTotNumprehis,'f'),1,0,"R",1);
   $pdf->Cell(15 ,5,db_formatar($nTotNumprecor,'f'),1,0,"R",1);
   $pdf->Cell(15 ,5,db_formatar($nTotNumprejur,'f'),1,0,"R",1);
   $pdf->Cell(15 ,5,db_formatar($nTotNumpremul,'f'),1,0,"R",1);
   $pdf->Cell(15 ,5,db_formatar($nTotNumpredes,'f'),1,0,"R",1);
   $pdf->Cell(15 ,5,db_formatar($nTotNumpretot,'f'),1,1,"R",1);
   
   $pdf->setx(7);
 $pdf->Cell(106,5,"TOTAL DO TIPO ".$oSuspensao->k00_tipo,"T",0,"L",1);
 $pdf->Cell(15 ,5,db_formatar($nTotTipohis,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotTipocor,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotTipojur,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotTipomul,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotTipodes,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotTipotot,'f'),1,1,"R",1);

 $pdf->Ln(3);
 
   $pdf->setx(7);
 $pdf->Cell(106,5,"TOTAL GERAL :"			 ,"T",0,"L",1);
 $pdf->Cell(15 ,5,db_formatar($nTotSusphis,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotSuspcor,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotSuspjur,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotSuspmul,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotSuspdes,'f'),1,0,"R",1);
 $pdf->Cell(15 ,5,db_formatar($nTotSusptot,'f'),1,1,"R",1);
   
}




$pdf->Output();
//header('Content-Type: application/pdf');
?>