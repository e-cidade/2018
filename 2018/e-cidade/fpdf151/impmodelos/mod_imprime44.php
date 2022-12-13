<?php
// quando não for guaiba  

    $data= date("Y-m-d",db_getsession("DB_datausu")); 	
    $data=split('-',$data);
    $dia=$data[2];
    $mes=$data[1];
    $ano=$data[0];
    $mes=db_mes($mes);
    $data=" $dia de $mes de $ano ";

		$this->objpdf->AliasNbPages();
		$this->objpdf->AddPage();
		$this->objpdf->settopmargin(1);
		$pagina = 1;
		$xlin = 20;
		$xcol = 4;

  for($i = 0;$i < 2;$i++){
		//Inserindo usuario e data no rodape
		$this->objpdf->Setfont('Arial', 'I', 6);
		$this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")."  Data: ".date("d/m/Y", db_getsession("DB_datausu"))."");

		$this->objpdf->setfillcolor(245);
		$this->objpdf->roundedrect($xcol -2, $xlin -18, 206, 140, 2, 'DF', '1234');
		$this->objpdf->setfillcolor(255, 255, 255);
		$this->objpdf->Setfont('Arial', 'B', 10);

		$this->objpdf->Image('imagens/files/'.$this->logo, 15, $xlin -17, 15); 
		$this->objpdf->Setfont('Arial', 'B', 9);
		$this->objpdf->text(40, $xlin -11, $this->prefeitura);
		$this->objpdf->text(150, $xlin -11, 'ANULAR DE RECEITA');
		$this->objpdf->Setfont('Arial', '', 7);
		$this->objpdf->text(40, $xlin -8, 'SECRETARIA DA FAZENDA - SETOR DE CONTABILIDADE');

		/// retangulo dos dados do empenho
    $this->objpdf->roundedrect($xcol, $xlin +2, $xcol +198, 20, 2, 'DF', '1234');
		$this->objpdf->Setfont('Arial', 'B', 8);
		$this->objpdf->text($xcol +2, $xlin +7, 'Numcgm'   );
		$this->objpdf->text($xcol +45, $xlin +7, (strlen($this->cnpj) == 11 ? 'CPF' : 'CNPJ'));
		$this->objpdf->text($xcol +2, $xlin +11, 'Credor');
		$this->objpdf->text($xcol +2, $xlin +15, 'Valor');
		
		$this->objpdf->text($xcol +20, $xlin +7, ': '.$this->numcgm);
		$this->objpdf->text($xcol +55, $xlin +7, ': '. (strlen($this->cnpj) == 11 ? db_formatar($this->cnpj, 'cpf') : db_formatar($this->cnpj, 'cnpj')));
		$this->objpdf->text($xcol +20, $xlin +11, ': '.$this->nome);
		$this->objpdf->text($xcol +20, $xlin +15, ': '.db_formatar($this->valor, 'f'));


    ///// retangulo do historico
    $this->objpdf->roundedrect($xcol, $xlin +25, $xcol +198, 40, 2, 'DF', '1234');
		$this->objpdf->Setfont('Arial', '', 6);
		$this->objpdf->text($xcol +2, $xlin +27, 'Historico');
    $this->objpdf->setx($xcol+2);
    $this->objpdf->sety($xlin+30);
    $this->objpdf->multicell(190,4,$this->historico,0,"J",0,0);
    
		$this->objpdf->Setfont('Arial', '', 8);
		$this->objpdf->text($xcol +2, $xlin +70, ucfirst($this->municpref).', '.$data);

    $this->objpdf->setleftmargin(10);
    $this->objpdf->sety($xlin+85);
 
		$this->objpdf->multicell(40, 4, $this->assinatura1,0,'C' );

    $this->objpdf->setleftmargin(60);
    //$this->objpdf->setx($xcol+120);
    $this->objpdf->sety($xlin+85);
 
		$this->objpdf->multicell(40, 4, $this->assinatura2,0,'C' );
		
    $this->objpdf->setleftmargin(110);
    $this->objpdf->sety($xlin+85);
 
		$this->objpdf->multicell(40, 4, $this->assinatura3,0,'C' );
		
    $this->objpdf->setleftmargin(160);
    $this->objpdf->sety($xlin+85);
 
		$this->objpdf->multicell(40, 4, $this->assinatura4,0,'C' );
		
    $this->objpdf->setleftmargin(10);


		
		$this->objpdf->text($xcol +2, $xlin +105, "Recebi da ".$this->prefeitura." a quantia supra em  ......./......./....... .");
		$this->objpdf->text($xcol+120, $xlin+115, $this->nome);
    $xlin = 170;
  }

?>
