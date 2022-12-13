<?php
 
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->setfillcolor(245);
	$this->objpdf->roundedrect(05,05,200,288,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->roundedrect(10,07,190,250,2,'DF','1234');
	$this->objpdf->Image('imagens/files/rpps.jpg',12,12,25);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,12,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,16,$this->enderpref);
	$this->objpdf->text(40,20,$this->municpref);
	$this->objpdf->text(40,24,$this->telefpref);
	$this->objpdf->text(40,28,$this->emailpref);

	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,16,'EXTRATO ANUAL DO FUNDO DE PENSÃO');
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(145,20,'Para simples verificação');
	$this->objpdf->text(143,24,'Lei Complementar 017/2005');
//	$this->objpdf->text(40,28,$this->emailpref);
        $linha = 47;
//	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect(15,45,80,10,'DF');
	$this->objpdf->rect(95,45,33,10,'DF');
	$this->objpdf->rect(128,45,33,10,'DF');
	$this->objpdf->rect(161,45,34,10,'DF');

	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(17,$linha,'Ente Federativo');
	$this->objpdf->text(97,$linha,'Código');
	$this->objpdf->text(130,$linha,'Operação');
	$this->objpdf->text(163,$linha,'Emissão');
	
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(17,$linha+5,$this->prefeitura);
//	$this->objpdf->text(97,$linha+5,'Código');
//	$this->objpdf->text(130,$linha+5,'Operação');
	$this->objpdf->text(163,$linha+5,date('d/m/Y',db_getsession("DB_datausu")));
	
	$this->objpdf->rect(15,$linha+8,80,10,'DF');
	$this->objpdf->rect(95,$linha+8,50,10,'DF');
	$this->objpdf->rect(145,$linha+8,50,10,'DF');
	
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(17,$linha+10,'Fundo');
	$this->objpdf->text(97,$linha+10,'CNPJ do Fundo');
	$this->objpdf->text(147,$linha+10,'Início das Atividades do Fundo');
	
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(17,$linha+15,'ALEGRETE PREV');
	$this->objpdf->text(97,$linha+15,'87896874/0001-57');
	$this->objpdf->text(147,$linha+15,'15/07/2003');
	
	$this->objpdf->Setfont('Arial','BI',8);
	$this->objpdf->text(17,$linha+30,'Administadora');
	
	$this->objpdf->rect(15,$linha+33,72.5,10,'DF');
	$this->objpdf->rect(87.5,$linha+33,72.5,10,'DF');
	$this->objpdf->rect(160,$linha+33,35,10,'DF');

	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(17,$linha+35,'Nome');
	$this->objpdf->text(89,$linha+35,'Endereço');
	$this->objpdf->text(162,$linha+35,'CNPJ da Administradora');
	
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(17,$linha+40,'Banco do Brasil');
	$this->objpdf->text(89,$linha+40,'Rua General Vitorino, 272');
//	$this->objpdf->text(162,$linha+40,'CNPJ da Administradora');

	$this->objpdf->Setfont('Arial','BI',8);
	$this->objpdf->text(17,$linha+55,'Contribuinte');
	
	$this->objpdf->rect(15,$linha+58,85,10,'DF');
	$this->objpdf->rect(100,$linha+58,30,10,'DF');
	$this->objpdf->rect(130,$linha+58,30,10,'DF');
	$this->objpdf->rect(160,$linha+58,18,10,'DF');
	$this->objpdf->rect(178,$linha+58,17,10,'DF');

	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(17,$linha+60,'Nome');
	$this->objpdf->text(102,$linha+60,'CPF/CNPJ');
	$this->objpdf->text(132,$linha+60,'Matrícula');
	$this->objpdf->text(162,$linha+60,'Ano');
	$this->objpdf->text(180,$linha+60,'Folha');

	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(17,$linha+65,$this->nome);
	$this->objpdf->text(102,$linha+65,db_formatar($this->cnpj,'cpf'));
	$this->objpdf->text(132,$linha+65,$this->matricula);
	$this->objpdf->text(162,$linha+65,$this->ano);
	$this->objpdf->text(180,$linha+65,'01');

	$this->objpdf->sety($linha+80);
	$this->objpdf->setleftmargin(15);
     
  $this->objpdf->Setfont('Arial','B',9);

	$this->objpdf->cell(30,6,'MÊS',1,0,"C",1);
	$this->objpdf->cell(26,6,'REMUNERAÇÃO',1,0,"C",1);
	$this->objpdf->cell(26,6,'B.CONTRIB',1,0,"C",1);
	$this->objpdf->cell(10,6,'%',1,0,"C",1);
	$this->objpdf->cell(26,6,'PATRONAL',1,0,"C",1);
	$this->objpdf->cell(10,6,'%',1,0,"C",1);
	$this->objpdf->cell(26,6,'SERVIDOR',1,0,"C",1);
	$this->objpdf->cell(26,6,'TOTAL',1,1,"C",1);
	
  $this->objpdf->Setfont('Arial','',9);

	$acum     = 0;
	$patronal = 0;
	$perc_patr = $this->patr;
	$perc_func = $this->func;

	$this->objpdf->cell(30,6,'JANEIRO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_01,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_01,'f'),1,0,"R",1);
	$patronal = $this->base_01/100*$this->patr;
	$acum += $this->desc_01 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_01,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	
	$this->objpdf->cell(30,6,'FEVEREIRO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_02,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_02,'f'),1,0,"R",1);
	$patronal = $this->base_02/100*$this->patr;
	$acum += $this->desc_02 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_02,'f'),1,0,"R",1);
        $this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	
	$this->objpdf->cell(30,6,'MARÇO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_03,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_03,'f'),1,0,"R",1);
	$patronal = $this->base_03/100*$this->patr;
	$acum += $this->desc_03 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_03,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	  
	
	$this->objpdf->cell(30,6,'ABRIL',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_04,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_04,'f'),1,0,"R",1);
	$patronal = $this->base_04/100*$this->patr;
	$acum += $this->desc_04 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_04,'f'),1,0,"R",1);
        $this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	
	$this->objpdf->cell(30,6,'MAIO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_05,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_05,'f'),1,0,"R",1);
	$patronal = $this->base_05/100*$this->patr;
	$acum += $this->desc_05 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_05,'f'),1,0,"R",1);
        $this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	
	$this->objpdf->cell(30,6,'JUNHO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_06,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_06,'f'),1,0,"R",1);
	$patronal = $this->base_06/100*$this->patr;
	$acum += $this->desc_06 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_06,'f'),1,0,"R",1);
        $this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	
	$this->objpdf->cell(30,6,'JULHO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_07,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_07,'f'),1,0,"R",1);
	$patronal = $this->base_07/100*$this->patr;
	$acum += $this->desc_07 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_07,'f'),1,0,"R",1);
        $this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	
	$this->objpdf->cell(30,6,'AGOSTO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_08,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_08,'f'),1,0,"R",1);
	$patronal = $this->base_08/100*$this->patr;
	$acum += $this->desc_08 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_08,'f'),1,0,"R",1);
        $this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	
	$this->objpdf->cell(30,6,'SETEMBRO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_09,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_09,'f'),1,0,"R",1);
	$patronal = $this->base_09/100*$this->patr;
	$acum += $this->desc_09 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_09,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	
	$this->objpdf->cell(30,6,'OUTUBRO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_10,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_10,'f'),1,0,"R",1);
	$patronal = $this->base_10/100*$this->patr;
	$acum += $this->desc_10 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_10,'f'),1,0,"R",1);
        $this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	
	$this->objpdf->cell(30,6,'NOVEMBRO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_11,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_11,'f'),1,0,"R",1);
	$patronal = $this->base_11/100*$this->patr;
	$acum += $this->desc_11 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_11,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	  
	$this->objpdf->cell(30,6,'DEZEMBRO',1,0,"L",1);
	$this->objpdf->cell(26,6,db_formatar($this->total_12,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->base_12,'f'),1,0,"R",1);
	$patronal = $this->base_12/100*$this->patr;
	$acum += $this->desc_12 + $patronal;
	if($patronal <= 0){
           $acum = 0;
	   $perc_patr = 0;
	   $perc_func = 0;
	}
	$this->objpdf->cell(10,6,db_formatar($perc_patr,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($patronal,'f'),1,0,"R",1);
	$this->objpdf->cell(10,6,db_formatar($perc_func,'f'),1,0,"R",1);
	$this->objpdf->cell(26,6,db_formatar($this->desc_12,'f'),1,0,"R",1);
 	$this->objpdf->cell(26,6,db_formatar($acum,'f'),1,1,"R",1);
	  

       // $this->objpdf->line(30,233,60,233);
	$this->objpdf->Image('imagens/files/ass_prefeito.jpg',35,225,25);
	$this->objpdf->Image('imagens/files/ass_presidente.jpg',140,225,25);
        $this->objpdf->text(35,235,'Prefeito Municipal');
        $this->objpdf->text(143,235,'Presidente');
	
	$this->objpdf->Setfont('Arial','',5);
        $this->objpdf->text(10,295,'Lotação : '.$this->lotacao);

//	$this->setxy(15,50);

    
?>
