<?
//$this->objpdf->SetRightMargin(152);
//$this->objpdf->SetTopMargin(95);
//$this->objpdf->Image('imagens/files/alvarapreimp.jpg','','',152,95);
$this->objpdf->SetFont('Arial','',8);

$coluna = 150;
$linha = 32;

$this->objpdf->setxy($coluna,$linha);

$this->objpdf->Cell(55,5,"",0,0,"D",0);
$this->objpdf->Cell(28,5,"$this->cnpjcpf",0,0,"C",0);
$this->objpdf->Cell(20,5,"",0,0,"D",0);
$this->objpdf->Cell(90,5,"$this->ativ.$this->nrinscr",0,0,"D",0);
$linha = $linha+9;

$this->objpdf->setxy($coluna,$linha);
$this->objpdf->Cell(15,5,"",0,0,"D",0); ////
$this->objpdf->Cell(100,5,"$this->nome",0,1,"D",0);
$linha = $linha+9;

$this->objpdf->setxy($coluna,$linha);
$this->objpdf->Cell(15,5,"",0,0,"D",0); ////
//$this->objpdf->Cell(100,5,substr($this->ender,0, strpos($this->ender,",")).", ".$this->numero."/".$this->compl,0,1,"D",0);a

$this->objpdf->Cell(100,5,$this->ender.", ".$this->numero.(isset($this->compl)&&$this->compl!=""?"/".$this->compl:""),0,1,"D",0);

//$this->objpdf->Cell(100,5,$this->ender,0,1,"D",0);
$pdf1->numero      = $this->numero;
$linha = $linha+8;

$this->objpdf->setxy($coluna,$linha);
$this->objpdf->Cell(15,5,"",0,0,"D",0); ////

$this->objpdf->Cell(60,5,substr("$this->descrativ",0,40),0,0,"D",0);
//insere data de inicio da inscricao
$this->objpdf->Cell(25,5,"",0,0,"D",0);
$this->objpdf->Cell(5,5,db_formatar("$this->dtinic",'d'),0,1,"E",0);

?>
