<?php

$sFonte = "arial";
$iFonte = 7;
$iAlt   = 4;
//cabecalhoRelatorio($this->objpdf, $sFonte, $iAlt);
$i = 1;
$nTotalEmpenho   = 0;
$nTotalDescontos = 0;
$iRecurso        = '';
$nTotalRecDesc   = 0;
$nTotalRec       = 0;
$aRubricasDesc   = array();

foreach ($this->aLinhasRelatorio as $iInd => $oLinhaRelatorio) {

	if ( $iRecurso != $oLinhaRelatorio->rh72_recurso ) {

		if ( $iInd != 0 ) {

			$this->objpdf->ln();
			$this->objpdf->SetFont($sFonte,"B",$iFonte);
			$this->objpdf->cell(100, $iAlt,"Total de Retenções do Recurso",1,1,"C",1);
			$this->objpdf->SetFont($sFonte,"",$iFonte);
			foreach ( $aRubricasDesc as $sDescr => $nValor ) {
				if ($this->objpdf->GetY() > $this->objpdf->h - 25){
					$this->objpdf->AddPage();
				}
				$this->objpdf->cell(70, $iAlt,$sDescr                 ,1,0,"L",0);
				$this->objpdf->cell(30, $iAlt,db_formatar($nValor,'f'),1,1,"R",0);
				$nTotalRecDesc += $nValor;

			}
			$this->objpdf->SetFont($sFonte,"B",$iFonte);
			$this->objpdf->cell(70, $iAlt,"Total Descontos"                         ,1,0,"L",1);
			$this->objpdf->cell(30, $iAlt,db_formatar($nTotalRecDesc,'f')           ,1,1,"R",1);
			$this->objpdf->cell(70, $iAlt,"Total Empenhado"                           ,1,0,"L",1);
			$this->objpdf->cell(30, $iAlt,db_formatar($nTotalRec,'f')               ,1,1,"R",1);
			$this->objpdf->cell(70, $iAlt,"Total Líquido"                           ,1,0,"L",1);
			$this->objpdf->cell(30, $iAlt,db_formatar($nTotalRec-$nTotalRecDesc,'f'),1,1,"R",1);

			$nTotalRecDesc = 0;
			$nTotalRec     = 0;

			unset($aRubricasDesc);
			$aRubricasDesc   = array();

		}

		if ( $iInd != 0 ) {
			$this->objpdf->AddPage();
		}
		$this->objpdf->SetFont($sFonte,"B",$iFonte+3);
		$this->objpdf->ln($iAlt*2);
		$this->objpdf->cell(50, $iAlt,"{$oLinhaRelatorio->rh72_recurso} - {$oLinhaRelatorio->o15_descr}",0,1,"L");
		$this->objpdf->ln();
		cabecalhoRelatorio($this->objpdf, $sFonte, $iAlt, $this->lRetencao);
		$iRecurso = $oLinhaRelatorio->rh72_recurso;

	}

	if ($this->objpdf->GetY() > $this->objpdf->h - 25){
		$this->objpdf->AddPage();
		cabecalhoRelatorio($this->objpdf, $sFonte, $iAlt, $this->lRetencao);
	}


	$iNumeroEmpenho = "";

	if ($oLinhaRelatorio->e60_codemp != "") {
		$iNumeroEmpenho = "{$oLinhaRelatorio->e60_codemp}/{$oLinhaRelatorio->e60_anousu}";
	}

	$sLocalizacao  = str_pad($oLinhaRelatorio->rh72_orgao       ,2,"0",STR_PAD_LEFT);
	$sLocalizacao .= ".".str_pad($oLinhaRelatorio->rh72_unidade ,2,"0",STR_PAD_LEFT);
	$sLocalizacao .= ".".str_pad($oLinhaRelatorio->rh72_projativ,4,"0",STR_PAD_LEFT);
	$sLocalizacao .= ".".str_pad($oLinhaRelatorio->rh72_recurso ,4,"0",STR_PAD_LEFT);

	$this->objpdf->SetFont($sFonte,"",$iFonte);
	$this->objpdf->cell(8, $iAlt, $i, "TBR", 0, "R");
	$this->objpdf->cell(17, $iAlt, $iNumeroEmpenho, 1, 0, "C");
	$this->objpdf->cell(15, $iAlt, $oLinhaRelatorio->rh72_sequencial, 1, 0, "R");
	$this->objpdf->cell(80, $iAlt, $oLinhaRelatorio->pc01_descrmater, 1, 0, "L");
	$this->objpdf->cell(15, $iAlt, $oLinhaRelatorio->rh72_coddot, 1, 0, "R");
	$this->objpdf->cell(10, $iAlt, $oLinhaRelatorio->rh72_concarpeculiar, 1, 0, "R");
	$this->objpdf->cell(22, $iAlt, $sLocalizacao, 1, 0, "R");
	$this->objpdf->cell(20, $iAlt, $oLinhaRelatorio->o56_elemento , 1, 0, "L");
	$this->objpdf->cell(70, $iAlt, $oLinhaRelatorio->o56_descr , 1, 0, "L");
	$this->objpdf->cell(23, $iAlt, trim(db_formatar($oLinhaRelatorio->rh73_valor,"f")), "TBL", 1, "R");
	$nTotalEmpenho += $oLinhaRelatorio->rh73_valor;
	$nTotalRec     += $oLinhaRelatorio->rh73_valor;

	if ($this->lRetencao == "t") {

	  foreach($oLinhaRelatorio->aDescontos as $oRetencao) {

	  	if ($this->objpdf->GetY() > $this->objpdf->h - 25) {

	  		$this->objpdf->AddPage();
	  		cabecalhoRelatorio($this->objpdf, $sFonte, $iAlt, $this->lRetencao);
	  		$this->objpdf->SetFont($sFonte,"",$iFonte);
	  	}

	  	$this->objpdf->cell(8, $iAlt, "", 0, 0, "R");
	  	$this->objpdf->cell(17, $iAlt, "", 0, 0, "C");
	  	$this->objpdf->cell(15, $iAlt, $oRetencao->rh73_rubric, 1, 0, "C");
	  	$this->objpdf->cell(80, $iAlt, $oRetencao->rh27_descr, 1, 0, "L");
	  	$this->objpdf->cell(15, $iAlt, trim(db_formatar($oRetencao->valorretencao,"f")), 1, 1, "R");

	  	$nTotalDescontos += $oRetencao->valorretencao;

	  	$sDescrRubric = $oRetencao->rh73_rubric." - ".$oRetencao->rh27_descr;

	  	if ( isset($aRubricasDesc[$sDescrRubric]) ) {
	  		$aRubricasDesc[$sDescrRubric] += $oRetencao->valorretencao;
	  	} else {
	  		$aRubricasDesc[$sDescrRubric]  = $oRetencao->valorretencao;
	  	}

	  }

	}

	$i++;

}

$this->objpdf->ln();
$this->objpdf->SetFont($sFonte,"B",$iFonte);
$this->objpdf->cell(100, $iAlt,"Total de Retenções do Recurso",1,1,"C",1);
$this->objpdf->SetFont($sFonte,"",$iFonte);

foreach ( $aRubricasDesc as $sDescr => $nValor ) {
	if ($this->objpdf->GetY() > $this->objpdf->h - 25){
		$this->objpdf->AddPage();
	}
	$this->objpdf->cell(70, $iAlt,$sDescr                 ,1,0,"L",0);
	$this->objpdf->cell(30, $iAlt,db_formatar($nValor,'f'),1,1,"R",0);
	$nTotalRecDesc += $nValor;
}

$this->objpdf->SetFont($sFonte,"B",$iFonte);
$this->objpdf->cell(70, $iAlt,"Total Descontos"                         ,1,0,"L",1);
$this->objpdf->cell(30, $iAlt,db_formatar($nTotalRecDesc,'f')           ,1,1,"R",0);
$this->objpdf->cell(70, $iAlt,"Total Empenhado"                         ,1,0,"L",1);
$this->objpdf->cell(30, $iAlt,db_formatar($nTotalRec,'f')               ,1,1,"R",0);
$this->objpdf->cell(70, $iAlt,"Total Líquido"                           ,1,0,"L",1);
$this->objpdf->cell(30, $iAlt,db_formatar($nTotalRec-$nTotalRecDesc,'f'),1,1,"R",0);
$this->objpdf->ln();

if ($this->objpdf->GetY() > $this->objpdf->h - 25){
  $this->objpdf->AddPage();
}

$this->objpdf->SetFont($sFonte,"b",$iFonte+2);
$this->objpdf->Cell(250, $iAlt, "Valor Bruto da Folha"                                   ,"TBR",0,"R",1);
$this->objpdf->cell(25,  $iAlt, trim(db_formatar($nTotalEmpenho,"f"))                    ,"TBL",1,"R",0);
$this->objpdf->Cell(250, $iAlt, "Total Descontos"                                        ,"TBR",0,"R",1);
$this->objpdf->cell(25,  $iAlt, trim(db_formatar($nTotalDescontos,"f"))                  ,"TBL",1,"R",0);
$this->objpdf->Cell(250, $iAlt, "Valor Líquido da Folha"                                 ,"TBR",0,"R",1);
$this->objpdf->cell(25,  $iAlt, trim(db_formatar($nTotalEmpenho-$nTotalDescontos,"f"))   ,"TBL",1,"R",0);
$this->objpdf->Output();

function cabecalhoRelatorio (&$oPdf, $sFonte, $iAlt, $lRetencoes) {

	$oPdf->SetFont($sFonte,"b",8);
	$oPdf->cell(8, $iAlt*2, "Seq", "TBR", 0, "C", 1);
	$oPdf->cell(17, $iAlt*2, "Empenho", 1, 0, "C", 1);
	$oPdf->cell(95, $iAlt, "Item", 1, 0, "C", 1);
	$oPdf->cell(15, $iAlt*2, "Dotação", 1, 0, "C", 1);
	$oPdf->cell(10, $iAlt*2, "CP", 1, 0, "C", 1);
	$oPdf->cell(112, $iAlt, "Desdobramento", 1, 0, "C", 1);
	$oPdf->cell(23, $iAlt*2, "Valor", "TBL", 1, "C", 1);
	$iAltura = $oPdf->GetY()-4;
	$oPdf->setxy(35, $iAltura);
	$oPdf->cell(15 , $iAlt, "Código", 1, 0, "C", 1);
	$oPdf->cell(80, $iAlt, "Descrição", 1, 0, "C", 1);
	$oPdf->setxy(155, $iAltura);
	$oPdf->cell(22, $iAlt, "Localização", 1, 0, "C", 1);
	$oPdf->cell(20, $iAlt, "Estrutural", 1, 0, "C", 1);
	$oPdf->cell(70, $iAlt, "Descrição", 1, 1, "C", 1);

	if ($lRetencoes == "t") {
	  $oPdf->cell(25, $iAlt, "", "T", 0, "C" ,1);
	  $oPdf->cell(95, $iAlt, "Descontos", 1, 0, "C", 1);
	  $oPdf->cell(15, $iAlt*2, "Valor", 1, 0, "C", 1);
	  $oPdf->cell(145, $iAlt*2, "", "TLB", 1, "C" ,1);
	  $oPdf->sety($oPdf->getY() -4);
	  $oPdf->cell(25, $iAlt, "", "B", 0, "C", 1);
	  $oPdf->cell(15, $iAlt, "Código", 1, 0, "C", 1);
	  $oPdf->cell(80, $iAlt, "Descrição", 1, 1, "C", 1);
	}
}