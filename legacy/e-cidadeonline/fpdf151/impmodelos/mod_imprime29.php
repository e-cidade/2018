<?
//===============================================================================//
/////////////////   C E R T I D Ã O   D E   I S E N Ç Ã O   ///////////////////////
//===============================================================================//

	$this->objpdf->settopmargin(1);
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',12);
	$this->objpdf->SetLineWidth(1);
	$this->objpdf->RoundedRect(2,3,204,292,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
	$this->objpdf->roundedrect(4,5,200,288,2,'1234');
	$this->objpdf->SetLineWidth(0.2);
	$this->objpdf->Image('imagens/files/Brasao.png',10,15,20);
	
	$this->objpdf->setfont('Arial','B',18);
	$linha = $this->objpdf->gety+25;
    $coluna = $this->objpdf->getx+5;
    $borda = "0";
    
    $this->objpdf->sety($linha);
    $this->objpdf->setx($coluna);
    $this->objpdf->Multicell(0,8,$this->isenprefeitura,$borda,"C",0); // prefeitura
    
    $linha += 30; 
    $this->objpdf->sety($linha);
    $this->objpdf->setx($coluna+5);
     
    $this->objpdf->setfont('Arial','B',30);
	$this->objpdf->Multicell(190,8,$this->isenmsg1,$borda,"C",0);
	$this->objpdf->cell(190,8,"",$borda,1,"C",0); 
	$this->objpdf->Multicell(190,8,$this->isenmsg2,$borda,"C",0);
	$this->objpdf->cell(190,8,"",$borda,1,"C",0); 
	$this->objpdf->Multicell(190,8,$this->isenmsg3,$borda,"C",0); 

    $linha  += 50;

    $this->objpdf->sety($linha+5);
    $this->objpdf->setx($coluna+5);
    
    $this->objpdf->roundedrect(8,$linha,190,100,2,'1234');
    
    $this->objpdf->setfont('Arial','',12);    
    $this->objpdf->Multicell(185,8,"           		".$this->isenmsg4,$borda,"J",0);
    
    $this->objpdf->setfont('Arial','B',12);
    $this->objpdf->cell(45,8,"Matrícula : ",$borda,0,"R",0);
    $this->objpdf->setfont('Arial','',12);
    $this->objpdf->cell(50,8,$this->isenmatric,$borda,0,"L",0);
    
    $this->objpdf->setfont('Arial','B',12); 
	$this->objpdf->cell(41,8,"Cpf : ",$borda,0,"R",0);
	$this->objpdf->setfont('Arial','',12); 
	$this->objpdf->cell(50,8,$this->isencgc,$borda,1,"C",0); 
    
    $this->objpdf->setfont('Arial','B',12);
    $this->objpdf->cell(45,8,"Nome : ",$borda,0,"R",0);
    $this->objpdf->setfont('Arial','',12); 
	$this->objpdf->cell(140,8,$this->isennome,$borda,1,"L",0); 
	
	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->cell(45,8,"Endereço : ",$borda,0,"R",0);
	$this->objpdf->setfont('Arial','',12); 
    $this->objpdf->cell(140,8,$this->isenender,$borda,1,"L",0); 
	
	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->cell(45,8,"Bairro : ",$borda,0,"R",0);
	$this->objpdf->setfont('Arial','',12); 
	$this->objpdf->cell(140,8,$this->isenbairro,$borda,1,"L",0); 
	
	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->cell(45,8,"Processo : ",$borda,0,"R",0);
	$this->objpdf->setfont('Arial','',12); 
	$this->objpdf->cell(140,8,$this->isenproc,$borda,1,"L",0);     
	
	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->cell(185,8,"Periodo de isenção : ",$borda,1,"C",0);
	$this->objpdf->setfont('Arial','',12); 
	$this->objpdf->cell(185,8,"De ".db_formatar($this->isendtini,'d')." à ".db_formatar($this->isendtfim,'d'),$borda,1,"C",0);     
   
    $this->objpdf->cell(185,8,"",$borda,1,"C",0);
    $this->objpdf->cell(185,8,"",$borda,1,"C",0);
    $this->objpdf->cell(185,8,"",$borda,1,"C",0);
    $this->objpdf->cell(185,8,"",$borda,1,"C",0);
    $this->objpdf->cell(185,8,"",$borda,1,"C",0);
    $this->objpdf->cell(70,8,"",$borda,0,"C",0);
    if (isset($this->isenassinatura) && $this->isenassinatura != ""){
      
    	$this->objpdf->multicell(110,8,db_geratexto($this->isenassinatura),0,1,"C",0);   
    }else{
    	$this->objpdf->cell(90,10,"",0,1,"C",0);
    }
    $this->objpdf->Ln(2);
	$this->objpdf->setxy(14,$y+$linha-$y+5);
//	$this->objpdf->SetAutoPageBreak('on',0);
?>
