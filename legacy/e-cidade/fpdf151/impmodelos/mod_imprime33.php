<?
/***************   L I N H A   E   C O L U N A   I N I C I A L   ********************************/
$col = 39;
$lin = 19;

/************************************************************************************************/	
$this->objpdf->SetAutoPageBreak(true, 0.5);
$this->objpdf->AddPage();
for($i=0;$i<2;$i++){
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFillColor(250,250,250);
	$this->objpdf->SetFont('Arial','',5);
	
// Identificacao (Nome, Endereco, Municipio, Bairro, Cep e CNPJ/CPF
//	die($this->predescr3_2);
	$this->objpdf->Text($col,$lin,  $this->predescr3_1);  // contribuinte dados do cgm
	$this->objpdf->Text($col,$lin+3,$this->predescr3_2);  // endereco
	$this->objpdf->Text($col,$lin+6,$this->premunic);     // municipio
	$this->objpdf->Text($col,$lin+9,$this->prebairropri); // bairro

	$this->objpdf->Text($col+42,$lin+6,$this->precep);    // cep 
	$this->objpdf->Text($col+42,$lin+9,$this->precgccpf); // cnpj/cpf

	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text($col+74,$lin-9,$this->predescr6); //data do recibo 
	$this->objpdf->SetFont('Arial','',5);
	
  // Origem do Debito
	$this->objpdf->Text($col+76,$lin,  $this->pretitulo8);                          // titulo matricula ou inscricao  
	$this->objpdf->Text($col+88,$lin,  $this->predescr8);                           // descr matricula ou inscricao  
	$this->objpdf->Text($col+76,$lin+2,$this->pretipolograd);                       // titulo do logradouro
	$this->objpdf->Text($col+88,$lin+2,$this->prenomepri);                          // nome do logradouro
	$this->objpdf->Text($col+76,$lin+4,$this->pretipocompl);                        // titulo do numero
	$this->objpdf->Text($col+88,$lin+4,$this->prenrpri . " " . $this->precomplpri); // numero e complemento
	$this->objpdf->Text($col+76,$lin+6,$this->tipobairro);                          // titulo do bairro
	$this->objpdf->Text($col+88,$lin+6,$this->bairropri);                           // nome do bairro

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
		  db_redireciona('db_erros.php?fechar=true&db_erro=O numero de receitas ultrapassou o espaço limite do carne.  Contate o suporte!');
      break;		  
    }
    if(isset($this->arrayvalreceitas[$x]) && $this->arrayvalreceitas[$x] != ""){
//		echo $this->arrayvalreceitas[$x]."---- <br>";
			if(isset($this->arraycodreceitas[$x]) && $this->arraycodreceitas[$x] != "" && $this->arraycodreceitas[$x] > 0){
				$this->objpdf->Text($reccol,$bklin,db_formatar($this->arraycodreceitas[$x], 's', "0", 5, "e"));    
			}else{
				$this->objpdf->Text($reccol,$bklin,"");    
			}
			
			if(isset($this->arrayreduzreceitas[$x]) && $this->arrayreduzreceitas[$x] != "" && $this->arrayreduzreceitas[$x] > 0){
				$this->objpdf->Text($reccol+5,$bklin," (".$this->arrayreduzreceitas[$x].")");   
			}else{
				$this->objpdf->Text($reccol,$bklin,"");    
			}

			$this->objpdf->Text($reccol+10,$bklin,$this->arraydescrreceitas[$x]);    
			$this->objpdf->Text($reccol+52,$bklin, db_formatar($this->arrayvalreceitas[$x], 'f') );    
			if($x==8){
				$bklin  = $bklin2-2;
				$reccol += 68;
			}		
			$bklin += 2;
		}
  }
	// Historico
  $this->objpdf->SetFont('Arial','',6);
//$this->objpdf->Text($col-10,$lin+36,$this->tipodebito." - ".$this->descr4_1." ".$this->descr4_2); // historico - linha 1
  $this->objpdf->setxy($col-10,$lin+36);
	$instrucoes = $this->pretipodebito." - ".$this->prehistoricoparcela." ".$this->predescr4_2;
	$this->objpdf->multicell($this->objpdf->w-70 ,2,(strlen($instrucoes)>500?substr($instrucoes,0,500)."...":$instrucoes));
  if($i==0){	
		$textox = $this->predescr16_1." ".$this->predescr16_2." ".$this->predescr16_3; 
		$textox = $this->premsgunica."  \" Locais de pagamento : Banco do Brasil, Banrisul, Caixa Econômica Federal, Lotéricas  e Agências Integradas\". \"Não aceitar após o vencimento - Solicitar segunda via junto Central de Atendimento da Secretaria da Fazenda \"  "; 
//  $this->objpdf->Text($col-10,$lin+48,(strlen($textox)>150?substr( $textox, 0, 150)."...":$textox) ); // 
    $this->objpdf->setxy($col-10,$lin+48);
	  $this->objpdf->multicell($this->objpdf->w-70 ,2,(strlen($textox)>500?substr($textox,0,500)."...":$textox));
  }else{
		// $textox = $this->descr12_1." ".$this->descr12_2;
		// $textox = " \" Locais de pagamento : Banco do Brasil, Banrrisul, Caixa Econômica Federal, Lotéricas  e Agências Integradas\". \"Não aceitar após o vencimento - Solicitar segunda via junto Central de Atendimento da Secretaria da Fazenda \"  "; 
    // $this->objpdf->Text($col-10,$lin+40,(strlen($textox)>150?substr( $textox, 0, 150)."...":$textox) ); // 
    // $this->objpdf->setxy($col-10,$lin+40);
	  // $this->objpdf->multicell($this->objpdf->w-50 ,2,(strlen($textox)>500?substr($textox,0,500)."...":$textox));
  }

  //
  // Primeira Via
	//
  $texto = db_getsession('DB_login').' - '.date("d-m-Y - H-i").'   '.db_base_ativa();
	if($i==0){
    $this->objpdf->SetFont('Arial','',4);
    $this->objpdf->Text ($col+10,$lin+61,$texto);
		// Linha Digitavel
	  $this->objpdf->SetFont('Arial','',7);
	  $this->objpdf->Text ($col-7,$lin+66,$this->linha_digitavel);

		// Vencimento, Valor A Pagar e Codigo de Arrecadacao
	  $this->objpdf->SetFont('Arial','',6);	
	  $this->objpdf->Text($col+072.5,$lin+65,$this->predescr6);  // Data de Vencimento
	  $this->objpdf->Text($col+084.5,$lin+65,$this->predescr7);  // qtd de URM ou valor
	  $this->objpdf->Text($col+107.5,$lin+65,$this->predescr9);  // cod. de arrecadação
		
	  $this->objpdf->SetFillColor(0,0,0); 
  //		
	// Segunda Via
	//
	}else{
		// Vencimento, Valor A Pagar e Codigo de Arrecadacao
	  $this->objpdf->SetFont('Arial','',6);	
	  $this->objpdf->Text($col-11,$lin+51,$this->predescr6);  // Data de Vencimento
	  $this->objpdf->Text($col+03,$lin+51,$this->predescr7);  // qtd de URM ou valor
	  $this->objpdf->Text($col+22,$lin+51,$this->predescr9);  // cod. de arrecadação
  
	  // Imprime Usuario, Data e Base de Dados
    $this->objpdf->SetFont('Arial','',4);
    $this->objpdf->Text ($col+10,$lin+63,$texto);
		
		// Linha Digitavel
	  $this->objpdf->SetFont('Arial','',10);
		if ($this->linha_digitavel != null) {
	    $this->objpdf->Text ($col-7,$lin+68,$this->linha_digitavel);
		}

		// Codigo de Barras
	  $this->objpdf->SetFillColor(0,0,0); 
		if ($this->codigo_barras != null) {
	    $this->objpdf->int25($col-10,$lin+70,$this->codigo_barras,17,0.38);
		}

	}

//	$this->objpdf->TextWithDirection($col-15,$lin+35,$texto,'U');
	
    $lin += 103;   // LINHA Q COMEÇA A IMPERSSAO DA SEGUNDA VIA  

}

?>
