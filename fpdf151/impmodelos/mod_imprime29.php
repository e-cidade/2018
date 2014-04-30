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
	//  echo "$this->objpdf->isenlogo"; exit;

	//$this->objpdf->Image("imagens/files/$this->isenlogo",10,15,25);
 	$this->objpdf->setfont('Arial','B',18);
 	
	
   $linha =  $this->objpdf->gety()+10;
   $coluna = $this->objpdf->getx();
   $borda = "0";
   
	 
	//	 $linha += 25;	
	//	 $coluna += 5;	
	 
	//	 die($linha." -- ".$coluna);
    
   /*$this->objpdf->sety($linha);
   $this->objpdf->setx($coluna);
   $this->objpdf->Multicell(0,8,$this->isenprefeitura,$borda,"C",0); // prefeitura

   $this->objpdf->sety($linha+5);
   $this->objpdf->setx($coluna);
 	$this->objpdf->setfont('Arial','B',14);
   $this->objpdf->Multicell(0,8,$this->cabec_sec,$borda,"C",0); // secretaria
    */
   //$linha += 30;
   $linha += 0; 
   $this->objpdf->sety($linha);
   $this->objpdf->setx($coluna);
     
   $this->objpdf->setfont('Arial','B',30);
	$this->objpdf->Multicell(190,8,$this->isenmsg1,$borda,"C",0);
	$this->objpdf->cell(190,8,"",$borda,1,"C",0); 
	$this->objpdf->Multicell(190,8,$this->isenmsg2,$borda,"C",0);
	$this->objpdf->cell(190,8,"",$borda,1,"C",0); 
	//	die("fasdfasdfh asldfhasdfas === ".$this->isenmsg3);
   $this->objpdf->setfont('Arial','',12);    

   $linha  += 40;

   $this->objpdf->sety($linha+5);
   $this->objpdf->setx($coluna);
    
    
   $this->objpdf->Multicell(185,8,"           		".$this->isenmsg4,$borda,"J",0);
	$linha = $this->objpdf->gety();
	
	if(isset($this->isenmsg3) && $this->isenmsg3 != ""){
		$this->objpdf->sety($linha+5);
		$this->objpdf->Multicell(185,8," OBS : ".$this->isenmsg3,$borda,"C",0); 
		$linha = $this->objpdf->gety();
		$this->objpdf->sety($linha);
	}
    
   $this->objpdf->setfont('Arial','B',12);
   $this->objpdf->cell(45,8,"Matrícula : ",$borda,0,"R",0);
   $this->objpdf->setfont('Arial','',12);
   $this->objpdf->cell(50,8,$this->isenmatric,$borda,0,"L",0);
    
	$this->objpdf->setfont('Arial','B',12); 
	$this->objpdf->cell(90,8,"Setor : $this->isensetor Quadra : $this->isenquadra Lote : $this->isenlote ",$borda,1,"L",0);
    
    $this->objpdf->setfont('Arial','B',12);
    $this->objpdf->cell(45,8,"Nome : ",$borda,0,"R",0);
    $this->objpdf->setfont('Arial','',10); 
	$this->objpdf->cell(60,8,$this->isennome,$borda,1,"L",0); 

  $this->objpdf->setfont('Arial','B',12); 
	$this->objpdf->cell(45,8,"CPF/CNPJ : ",$borda,0,"R",0);
	$this->objpdf->setfont('Arial','',10); 
	$this->objpdf->cell(60,8,$this->isencgc,$borda,1,"L",0); 
	
	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->cell(45,8,"Endereço : ",$borda,0,"R",0);
	$this->objpdf->setfont('Arial','',10); 
    $this->objpdf->cell(140,8,$this->isenender,$borda,1,"L",0); 
	
	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->cell(45,8,"Bairro : ",$borda,0,"R",0);
	$this->objpdf->setfont('Arial','',10); 
	$this->objpdf->cell(140,8,$this->isenbairro,$borda,1,"L",0); 
	
	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->cell(45,8,"Processo : ",$borda,0,"R",0);
	$this->objpdf->setfont('Arial','',10); 
	$this->objpdf->cell(140,8,$this->isenproc,$borda,1,"L",0);

	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->cell(45,8,"Dados de localização: ",$borda,0,"R",0);
	$this->objpdf->setfont('Arial','',10); 
	$this->objpdf->cell(140,8,$this->j05_setorloc . "/" . $this->j05_quadraloc . "/" . $this->j05_loteloc,$borda,1,"L",0);

	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->cell(185,8,"Periodo de isenção : ",$borda,1,"C",0);
	$this->objpdf->setfont('Arial','',10); 
	$this->objpdf->cell(185,8,"De ".db_formatar($this->isendtini,'d')." a ".db_formatar($this->isendtfim,'d'),$borda,1,"C",0);     
    
    $yy = $this->objpdf->gety();

    $this->objpdf->roundedrect(8,$linha,190,$yy-$linha,2,'1234'); // rect dos dados 

    $alturatotal =  $this->objpdf->h;	
	
    $this->objpdf->sety($alturatotal-50);
    if (isset($this->isenassinatura) && $this->isenassinatura != ""){
	    $this->objpdf->setx(10);
		 if (isset($this->isenassinatura2) && $this->isenassinatura2 != ""){ 
	   		$this->objpdf->cell(90,5,db_geratexto($this->isenassinatura2),0,0,"C",0); 
		 }
	    $this->objpdf->setx(120);
    	$this->objpdf->multicell(90,5,db_geratexto($this->isenassinatura),0,1,"C",0);   
    }else{
    	$this->objpdf->cell(90,10,"",0,1,"C",0);
    }
    $this->objpdf->Ln(2);
	$this->objpdf->setxy(14,@$y+$linha-@$y+5);
//	$this->objpdf->SetAutoPageBreak('on',0);
?>
