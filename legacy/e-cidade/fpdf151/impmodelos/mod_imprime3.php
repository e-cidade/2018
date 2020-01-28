<?php
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',12);
	$coluna = 44;
	$linha = 35;
	$this->objpdf->SetLineWidth(1);
	$this->objpdf->RoundedRect(37,0.2,137,195,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
	$this->objpdf->roundedrect(39,2,133,191,2,'1234');
	$this->objpdf->SetLineWidth(0.2);
	$this->objpdf->Image('imagens/files/Brasao.png',43,5,20);
	$this->objpdf->Image('imagens/files/Brasao.jpg',60,30,100);
	
	$this->objpdf->setxy(65,5);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->Multicell(0,8,$this->prefeitura,"C"); // prefeitura

	$this->objpdf->setxy(65,10);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->setleftmargin(50);
	$this->objpdf->setrightmargin(50);
	$this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara

	$this->objpdf->settextcolor(150);
	$this->objpdf->setxy(85,25);
	$this->objpdf->setfont('Arial','B',60);
	$this->objpdf->Multicell(0,8,date('Y'),"C"); // exercicio
	$this->objpdf->settextcolor(0,0,0);

	$this->objpdf->setxy(84,24);
	$this->objpdf->setfont('Arial','B',60);
	$this->objpdf->Multicell(0,8,date('Y'),"C"); // exercicio

    $this->objpdf->Ln(6);
	$this->objpdf->SetFont('Arial','',11);
	$this->objpdf->Multicell(0,6,$this->texto); // texto

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+35,'CCM:'); // atividade / inscricao
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+35,$this->ativ.' / '.$this->nrinscr); // atividade / inscricao
	
	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+39,"NOME/RAZAO SOCIAL: "); // nome
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+39,$this->nome); // nome

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+43,"ENDEREÇO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+43,$this->ender); // endereco

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+47,"NÚMERO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+47,($this->numero == ""?"":$this->numero));

        if ($this->compl != "") {
   	  $this->objpdf->SetFont('Arial','B',9);
	  $this->objpdf->Text($coluna + 60 ,$linha+47,"COMPLEMENTO: "); // endereco
	  $this->objpdf->SetFont('Arial','',9);
	  $this->objpdf->Text($coluna + 90,$linha+47,($this->compl == ""?"":$this->compl));
        }

        $this->objpdf->setx(40);
	if($this->q02_memo!=''){
	  $this->objpdf->SetFont('Arial','B',9);
	  $this->objpdf->Text($coluna,$linha+51,"OBSERVAÇÃO: "); // observação
	  $this->objpdf->SetFont('Arial','',9);
	  $this->objpdf->sety($linha+52);
	  $this->objpdf->Multicell(0,3,$this->q02_memo); // texto
	  $this->objpdf->SetFont('Arial','B',10);
  	  $this->objpdf->roundedrect(42,$linha+30,127,35,2,'1234');
	  $linha = 102;
	} else {
  	  $this->objpdf->roundedrect(42,$linha+30,127,20,2,'1234');
	  $linha = 87;
	}

        $this->objpdf->sety($linha);
         
	  $this->objpdf->roundedrect(42,$linha-1,127,5,2,'1234');
	  $this->objpdf->SetFont('Arial','B',8);
  	  $this->objpdf->Ln(0.5);
	  $this->objpdf->setx(45);
	  $this->objpdf->Multicell(0,3,"ATIVIDADE PRINCIPAL: " . $this->descrativ) ; // descrição da atividade principal
  	  $linha += 6;
	     $obs='';
	     if(isset($this->q03_atmemo[$this->ativ])){
	       if ($this->q03_atmemo[$this->ativ] != '') {;
  	         $this->objpdf->roundedrect(42,$linha-1,127,15,2,'1234'); // obs da atividade principal
		 $obs = $this->q03_atmemo[$this->ativ];
		 $this->objpdf->Ln(3);
		 $this->objpdf->SetFont('Arial','',7);
		 $this->objpdf->Multicell(0,3,$this->q03_atmemo[$this->ativ]); // texto
		 $linha += 16;
               }
	     }

        $this->objpdf->sety($linha);
	  
        $num_outras=count($this->outrasativs);
	$x=105;
        if ($num_outras >0 ) {

           $x=$x+4;
	   reset($this->outrasativs); 
	   for($i=0; $i<$num_outras; $i++){
             $yyy = $this->objpdf->gety();
	     $chave=key($this->outrasativs);
	     $obs='';
	     if(isset($this->q03_atmemo[$chave])){
	       $obs = $this->q03_atmemo[$chave];
	     }

	     $this->objpdf->SetFont('Arial','B',8);
  	     $this->objpdf->Ln(0.5);
	     $this->objpdf->setx(45);
 	     $this->objpdf->Multicell(0,3,"ATIVIDADE SECUNDÁRIA: " . $this->outrasativs[$chave]); // texto
	     $linha += 6;

	     if($obs!=""){
	       $this->objpdf->roundedrect(42,$linha-1,127,15,2,'1234'); // obs da atividade secundaria
               $this->objpdf->Ln(3);
  	       $this->objpdf->SetFont('Arial','',7);
 	       $this->objpdf->Multicell(0,3,$obs); // texto
	       $linha += 16;
	     }

	     $x=$x+4;
	     next($this->outrasativs);
	     $this->objpdf->sety($linha);
	   }  
 	}
    $x=64;
	$this->objpdf->SetFont('Arial','B',12);
	$this->objpdf->Text($coluna+55,$linha + 5,"Sapiranga, ".date('d')." de ".db_mes( date('m') )." de ".date('Y') . "."); // data

	$this->objpdf->sety(125);
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Multicell(0,6,$this->obs); // observação
	$this->objpdf->setfont('arial','',6);
    $this->objpdf->SetXY($coluna-18,165);
    $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".'SECRETÁRIO DA IND. COM. E TURISMO',0,"C",0);
    $this->objpdf->SetXY($coluna+50,165);
    $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);

	$this->objpdf->sety(180);
    $this->objpdf->setfont('arial','B',12);
    $this->objpdf->multicell(0,8,'FIXAR EM LUGAR VISÍVEL',1,"C");
	$this->objpdf->SetFont('Arial','B',10);

?>