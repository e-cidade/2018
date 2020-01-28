<?
/***************   L I N H A   E   C O L U N A   I N I C I A L   ********************************/
$col = 20.5;
$lin = 23;

/************************************************************************************************/	
$this->objpdf->AddPage();
for($i=0;$i<2;$i++){
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFillColor(250,250,250);
	$this->objpdf->SetFont('Arial','',5);
	
	// Identificacao (Nome, Endereco, Municipio, Bairro, Cep e CNPJ/CPF
	$this->objpdf->Text($col,$lin,$this->descr3_1);   // contribuinte
	$this->objpdf->Text($col,$lin+3,$this->descr3_2); // endereco
	$this->objpdf->Text($col,$lin+6,$this->munic);    // municipio
	$this->objpdf->Text($col,$lin+9,$this->bairropri);// bairro

	$this->objpdf->Text($col+42,$lin+6,$this->cep);    // cep 
	$this->objpdf->Text($col+42,$lin+9,$this->cgccpf); // cnpj/cpf

  // Origem do Debito
	$this->objpdf->Text($col+74,$lin,$this->titulo8); // titulo matricula ou inscricao  
	$this->objpdf->Text($col+86,$lin,$this->descr8);  // descr matricula ou inscricao  
	$this->objpdf->Text($col+74,$lin+2,$this->tipolograd); // titulo do logradouro
	$this->objpdf->Text($col+86,$lin+2,$this->nomepri);  // nome do logradouro
	$this->objpdf->Text($col+74,$lin+4,$this->tipocompl); // titulo do numero
	$this->objpdf->Text($col+86,$lin+4,$this->nrpri . " " . $this->complpri);  // numero e complemento
	$this->objpdf->Text($col+74,$lin+6,$this->tipobairro); // titulo do bairro
	$this->objpdf->Text($col+86,$lin+6,$this->bairropri);  // nome do bairro

  //
	//  RECEITAS
	//
	$reccol  = ($col - 12);
	$reccol2 = ($col - 12);
	$bklin   = $lin+15; 
	$bklin2  = $lin+15;
	$intnumrows = count($this->arraycodreceitas);
  // 
  for($x=0;$x<$intnumrows;$x++){
	  if($x==16){
		  db_msgbox("O numero de receitas ultrapassou o espaço limite do carne. \n Contate o suporte!");
      break;		  
    }
		$this->objpdf->Text($reccol,$bklin,db_formatar($this->arraycodreceitas[$x], 's', "0", 5, "e"));    
		$this->objpdf->Text($reccol+5,$bklin," (".$this->arrayreduzreceitas[$x].")");    
		$this->objpdf->Text($reccol+10,$bklin,$this->arraydescrreceitas[$x]);    
		$this->objpdf->Text($reccol+52,$bklin, db_formatar($this->arrayvalreceitas[$x], 'f') );    
    if($x==8){
      $bklin  = $bklin2-2;
      $reccol += 68;
    }		
    $bklin += 2;
  }
	
	// Historico
  $this->objpdf->SetFont('Arial','',6);
  $this->objpdf->Text($col-10,$lin+36,$this->descr4_1." ".$this->descr4_2); // historico - linha 1
  if($i==1){	
    $this->objpdf->Text($col-10,$lin+40,$this->descr16_1." ".$this->descr16_2." ".$this->descr16_3); // 
  }else{
    $this->objpdf->Text($col-10,$lin+48,$this->descr12_1." ".$this->descr12_2); // 
  }

  //
  // Primeira Via
	//
	if($i==0){
		// Linha Digitavel
	  $this->objpdf->SetFont('Arial','',7);
	  $this->objpdf->Text ($col-7,$lin+66,$this->linha_digitavel);

		// SEM Codigo de Barras
	  //$this->objpdf->SetFillColor(0,0,0); 
	  //$this->objpdf->int25($col-10,$lin+68,$this->codigo_barras,9,0.2241);
		
		// Vencimento, Valor A Pagar e Codigo de Arrecadacao
	  $this->objpdf->SetFont('Arial','',6);	
	  $this->objpdf->Text($col+070.5,$lin+62,$this->descr6);  // Data de Vencimento
	  $this->objpdf->Text($col+084.5,$lin+62,str_replace(" ", "*", $this->descr7));  // qtd de URM ou valor
	  $this->objpdf->Text($col+103.5,$lin+62,$this->descr9); // cod. de arrecadação
		
	  $this->objpdf->SetFillColor(0,0,0); 
  //		
	// Segunda Via
	//
	}else{
		// Vencimento, Valor A Pagar e Codigo de Arrecadacao
	  $this->objpdf->SetFont('Arial','',6);	
	  $this->objpdf->Text($col-11,$lin+49,$this->descr6);  // Data de Vencimento
	  $this->objpdf->Text($col+03,$lin+49,str_replace(" ", "*", $this->descr7));  // qtd de URM ou valor
	  $this->objpdf->Text($col+22,$lin+49,$this->descr9); // cod. de arrecadação
		
		// Linha Digitavel
	  $this->objpdf->SetFont('Arial','',10);
	  $this->objpdf->Text ($col-7,$lin+58,$this->linha_digitavel);

		// Codigo de Barras
	  $this->objpdf->SetFillColor(0,0,0); 
	  $this->objpdf->int25($col-10,$lin+60,$this->codigo_barras,17,0.38);
		

	}

  // Imprime Usuario, Data e Base de Dados
	$texto = db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();
  $this->objpdf->SetFont('Arial','',4);
	$this->objpdf->TextWithDirection($col-15,$lin+35,$texto,'U');
	
	
  $lin += 99;   // LINHA Q COMEÇA A IMPERSSAO DA SEGUNDA VIA  

}

?>
