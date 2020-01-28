<?php
//function imprime(){
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
        $this->objpdf->line(2,148.5,208,148.5);
	$xlin = 20;
	$xcol = 4;
	$alt = 7;
       	for ($i = 0;$i < 2;$i++){
		$this->objpdf->setfillcolor(245);
		$this->objpdf->rect($xcol-2,$xlin-18,206,144.5);
		$this->objpdf->setfillcolor(255,255,255);
		
		$this->objpdf->Setfont('Arial','B',12);
		$this->objpdf->text(60,$xlin-12,$this->previdencia);
		
		$this->objpdf->Image('imagens/files/'.$this->logo,5,$xlin+5,32);
		$this->objpdf->Setfont('Arial','B',10);
		$this->objpdf->text($xcol+15,$xlin-5,$this->prefeitura);
	
		$this->objpdf->Setfont('Arial','',9);
		
		$this->objpdf->rect($xcol+35,$xlin,77,45);
		$this->objpdf->Setfont('Arial','',5);
		$this->objpdf->text($xcol+36,$xlin+2,'1 - Carimbo Padronizado do CGC');
		
		$this->objpdf->rect($xcol+2,$xlin+50,110,$alt);
		$this->objpdf->Setfont('Arial','',5);
		$this->objpdf->text($xcol+4,$xlin+52,'2 - Nome / Razão Social');
		$this->objpdf->Setfont('Arial','b',7);
		$this->objpdf->text($xcol+4,$xlin+56,$this->prefeitura);

		$this->objpdf->rect($xcol+2,$xlin+57,110,$alt);
		$this->objpdf->Setfont('Arial','',5);
		$this->objpdf->text($xcol+4,$xlin+59,'3 - Endereço');
		$this->objpdf->Setfont('Arial','b',7);
		$this->objpdf->text($xcol+4,$xlin+63,$this->enderpref);

		$this->objpdf->rect($xcol+2,$xlin+64,30,$alt);
		$this->objpdf->Setfont('Arial','',5);
		$this->objpdf->text($xcol+4,$xlin+66,'4 - CEP');
		$this->objpdf->Setfont('Arial','b',7);
		$this->objpdf->text($xcol+4,$xlin+70,$this->cep);
		
		$this->objpdf->rect($xcol+32,$xlin+64,70,$alt);
		$this->objpdf->Setfont('Arial','',5);
		$this->objpdf->text($xcol+34,$xlin+66,'5 - Município');
		$this->objpdf->Setfont('Arial','b',7);
		$this->objpdf->text($xcol+34,$xlin+70,$this->municpref);
		
		$this->objpdf->rect($xcol+102,$xlin+64,10,$alt);
		$this->objpdf->Setfont('Arial','',5);
		$this->objpdf->text($xcol+104,$xlin+66,'6 - UF');
		$this->objpdf->Setfont('Arial','b',7);
		$this->objpdf->text($xcol+104,$xlin+70,$this->ufpref);
		
		$this->objpdf->rect($xcol+2,$xlin+77,110,45);
		$this->objpdf->Setfont('Arial','',5);
		$this->objpdf->text($xcol+4,$xlin+79,'7 - Outras Informações');
		
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+9,$xlin+89.8,'N° de Funcionários');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+58,$xlin+89.5,db_formatar($this->func,'f',' ',2,'e',0));
		$this->objpdf->rect($xcol+7,$xlin+85,45,$alt);
		$this->objpdf->rect($xcol+52,$xlin+85,55,$alt);

		$this->objpdf->rect($xcol+7,$xlin+95,100,$alt);
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+39,$xlin+99.5,'Salário Contribuição');

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+9,$xlin+106.5,'Funcionários');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+58,$xlin+106.5,'R$'.db_formatar($this->base,'f'));
		$this->objpdf->rect($xcol+7,$xlin+102,45,$alt);
		$this->objpdf->rect($xcol+52,$xlin+102,55,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+9,$xlin+113.5,'Autônomos');
		$this->objpdf->rect($xcol+7,$xlin+109,45,$alt);
		$this->objpdf->rect($xcol+52,$xlin+109,55,$alt);


	
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin-8.5,'8 - CGC');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin-8.5,db_formatar($this->cgcpref,'cnpj'));
		$this->objpdf->rect($xcol+115,$xlin-13,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin-13,50,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin-1.5,'9 - MÊS/ANO');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin-1.5,db_formatar($this->mes,'s','0',2,'e',0).'/'.$this->ano);
		$this->objpdf->rect($xcol+115,$xlin-6,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin-6,50,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin+5.5,'10 - CÓD. PAGTO');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin+5.5,$this->cod_pagto);
		$this->objpdf->rect($xcol+115,$xlin+1,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin+1,50,$alt);


		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin+19.5,'11 - SEGURADOS');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin+19.5,'R$'.db_formatar($this->desconto,'f'));
		$this->objpdf->rect($xcol+115,$xlin+15,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin+15,50,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin+26.5,'12 - EMPRESA');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin+26.5,'R$'.db_formatar($this->patronal,'f'));
		$this->objpdf->rect($xcol+115,$xlin+22,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin+22,50,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin+33.5,'13 - TERCEIROS');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin+33.5,'R$'.db_formatar($this->terceiros,'f'));
		$this->objpdf->rect($xcol+115,$xlin+29,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin+29,50,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin+40.5,'14 - DEDUÇÕES');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin+40.5,'R$'.db_formatar($this->deducao,'f'));
		$this->objpdf->rect($xcol+115,$xlin+36,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin+36,50,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$liquido = $this->desconto + $this->patronal - $this->deducao;
		$this->objpdf->text($xcol+116,$xlin+47.5,'15 - TOTAL LÍQUIDO');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin+47.5,'R$'.db_formatar($liquido,'f'));
		$this->objpdf->rect($xcol+115,$xlin+43,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin+43,50,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin+54.5,'16 - ATUAL. MONETÁRIA');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin+54.5,'R$'.db_formatar($this->atu_monetaria,'f'));
		$this->objpdf->rect($xcol+115,$xlin+50,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin+50,50,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin+61.5,'17 - JUROS/MULTA');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin+61.5,'R$'.db_formatar($this->juros,'f'));
		$this->objpdf->rect($xcol+115,$xlin+57,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin+57,50,$alt);

		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+116,$xlin+68.5,'18 - TOTAL');
		$this->objpdf->Setfont('Arial','B',10);
                $this->objpdf->text($xcol+155,$xlin+68.5,'R$'.db_formatar($liquido,'f'));
		$this->objpdf->rect($xcol+115,$xlin+64,35,$alt);
		$this->objpdf->rect($xcol+150,$xlin+64,50,$alt);
		
		$this->objpdf->rect($xcol+115,$xlin+77,85,45);

		
		$this->objpdf->Setfont('Arial','B',11);
//		$this->objpdf->text(150,$xlin-13,'RECIBO VÁLIDO ATÉ: ');

	        $xlin = 169;

         }
//}
?>
