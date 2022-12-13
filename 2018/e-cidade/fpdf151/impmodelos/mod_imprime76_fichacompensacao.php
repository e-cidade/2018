<?
                            	  //  F I C H A   D E   C O M P E N S A Ç Ã O 

/*  Line(float x1, float y1, float x2, float y2)

Parâmetros:
x1
   Abscissa do primeiro ponto. 
y1
   Ordenada do primeiro ponto. 
x2
   Abscissa do segundo ponto. 
y2
   Ordenada do segundo ponto.  */
  
  $y  = 189;
  $x  = 5;

  $xx = 15;
  $yy = 0;
 
  $this->objpdf->SetDash(1,1);
  $this->objpdf->Line($x-5,     $y-0.4, $xx+$x+190, $y-0.4); //horiz 
  $this->objpdf->SetDash();

  $this->objpdf->Line($x+42,$y+1,$x+42,$y+9);  //vert
  $this->objpdf->Line($x+57,$y+1,$x+57,$y+9);  //vert

  $this->objpdf->SetLineWidth(0.4);

  $this->objpdf->Line($x,        $y+9, $xx+$x+182, $y+9);   // horiz linha inicial superior  1
  $this->objpdf->Line($x,        $y+9, $x,         $y+87);  // vert  linha inicial lateral esquerda 1
  $this->objpdf->Line($xx+$x+182,$y+9, $xx+$x+182, $y+87);  // vart  linha final lateral esquerda  2

  $this->objpdf->SetLineWidth(0.2);

  $this->objpdf->Line($x,     $y+17, $xx+$x+182, $y+17); //horiz  2
  $this->objpdf->Line($x,     $y+20, $xx+$x+121, $y+20); //horiz  2-A
  $this->objpdf->Line($x,     $y+24, $xx+$x+182, $y+24); //horiz  3
  $this->objpdf->Line($x,     $y+31, $xx+$x+182, $y+31); //horiz  4 
  $this->objpdf->Line($x,     $y+38, $xx+$x+182, $y+38); //horiz  5
  $this->objpdf->Line($x+136, $y+45, $xx+$x+182, $y+45); //horiz  6
  $this->objpdf->Line($x+136, $y+52, $xx+$x+182, $y+52); //horiz  7
  $this->objpdf->Line($x+136, $y+59, $xx+$x+182, $y+59); //horiz  8
  $this->objpdf->Line($x+136, $y+66, $xx+$x+182, $y+66); //horiz  9
  $this->objpdf->Line($x,     $y+73, $xx+$x+182, $y+73); //horiz 10

  $this->objpdf->Line($x+106, $y+17,  $x+106, $y+20); //vert nova

  $this->objpdf->Line($x+136, $y+9,  $x+136, $y+87); //vert 2 
  $this->objpdf->Line($x+156, $y+9,  $x+156, $y+17); //vert linha vencimento

  $this->objpdf->Line($x+27,  $y+24, $x+27,  $y+31); //vert
  $this->objpdf->Line($x+73,  $y+24, $x+73,  $y+31); //vert
  $this->objpdf->Line($x+99,  $y+24, $x+99, $y+31); //vert
  $this->objpdf->Line($x+112, $y+24, $x+112, $y+31); //vert

  $this->objpdf->Line($x+32,  $y+31, $x+32,  $y+38); //vert
  $this->objpdf->Line($x+53,  $y+31, $x+53,  $y+38); //vert
  $this->objpdf->Line($x+78,  $y+31, $x+78,  $y+38); //vert
  $this->objpdf->Line($x+108, $y+31, $x+108, $y+38); //vert

  $this->objpdf->SetLineWidth(0.4);
  $this->objpdf->Line($x,     $y+87, $xx+$x+182, $y+87); //horiz ultima linha
  
  //codigo de barras
  $this->objpdf->SetFillColor(0,0,0);
  
	if ($this->codigo_barras != null) {
    $this->objpdf->int25(10,$y+92.5,$this->codigo_barras,15,0.3);
	}

   
    // quadrado inferior //
  $this->objpdf->Image($this->imagemlogo,$x,$y+1,32,7);
//  $this->objpdf->Text($x+7,$y+7,$this->banco);

  

  $this->objpdf->SetFont('Arial','b',14);
  $this->objpdf->Text($x+43,  $y+7,$this->numbanco);      // numero do banco
  $this->objpdf->SetFont('Arial','b',13);
	if ($this->linha_digitavel != null) {
    $this->objpdf->Text($x+61,  $y+7,$this->linha_digitavel);
	}
  $this->objpdf->SetFont('Arial','b',5);
  $this->objpdf->Text($x+3,   $y+11,"Local de Pagamento");
  $this->objpdf->Text($x+138, $y+11,"Parcela");
  $this->objpdf->Text($x+158, $y+11,"Vencimento");

  $this->objpdf->Text($x+3,   $y+19,"Beneficiário:");
  $this->objpdf->Text($x+3,  $y+23,"Endereço:");                  // nome prefeitura
  $this->objpdf->Text($x+107, $y+19,"CNPJ:");                  // nome prefeitura
  $this->objpdf->Text($x+138, $y+19,"Agência/Código Cedente");

  $this->objpdf->Text($x+3,   $y+26,"Data do Documento");
  $this->objpdf->Text($x+29,  $y+26,"Número do Documento");
  $this->objpdf->Text($x+75,  $y+26,"Espécie Doc.");
  $this->objpdf->Text($x+101, $y+26,"Aceite");
  $this->objpdf->Text($x+114, $y+26,"Data do Processamento");
  $this->objpdf->Text($x+138, $y+26,"Nosso Número");

  $this->objpdf->Text($x+3,   $y+33,"Uso do banco");
  $this->objpdf->Text($x+34,  $y+33,"Carteira");
  $this->objpdf->Text($x+54,  $y+33,"Espécie");
  $this->objpdf->Text($x+80,  $y+33,"Quantidade");
  $this->objpdf->Text($x+110, $y+33,"Valor");
  $this->objpdf->Text($x+138, $y+33,"( = ) Valor do Documento");

  if ( isset($this->sTituloInstrucoes) && trim($this->sTituloInstrucoes) != '' ) {
    $this->objpdf->Text($x+3,   $y+40,$this->sTituloInstrucoes); 
  } else {
    $this->objpdf->Text($x+3,   $y+40,"Instruções");
  }
  
  $this->objpdf->Text($x+138, $y+40,"( - ) Desconto / Abatimento");

  $this->objpdf->Text($x+138, $y+47,"( - ) Outras Deduções");
  $this->objpdf->Text($x+138, $y+54,"( + ) Mora / Multa");
  $this->objpdf->Text($x+138, $y+61,"( + ) Outros Acréscimos");
  $this->objpdf->Text($x+138, $y+68,"( = ) Valor Cobrado");
  $this->objpdf->Text($x+3,   $y+75,"Sacado");
  $this->objpdf->Text($x+3,   $y+85,"Sacador/Avalista");

  $this->objpdf->SetFont('Arial','b',6);
  $this->objpdf->Text($x+120, $y+90,"AUTENTICAÇÃO MECÂNICA / FICHA DE COMPENSAÇÃO");

// $this->instrucoes1     = 'IPTU 2006';                                // instrucoes 1
// $this->instrucoes2     = 'CODIGO DE ARRECADAÇÃO : '.$this->descr9;   // instrucoes 2
// $this->instrucoes3     = 'EXERC : 2006';                             // ins
/* $this->agencia_cedente = "0694-7 / 73336-9";
   $this->carteira        = '18-027';*/

  $this->especie_doc     = "RC";
  $this->aceite          = "N";
  $this->localpagamento  = " EM QUALQUER BANCO OU CASA LOTÉRICA ATÉ O VALOR LIMITE "; 

  $this->objpdf->SetFont('Arial','b',8);
  $this->objpdf->Text($x+3,   $y+15,$this->localpagamento);              // local de pagamento
  $this->objpdf->SetFont('Arial','',10);
  $this->objpdf->Text($x+138, $y+15,$this->descr10); // $this->parcela); // parcela
//  $this->objpdf->Text($x+161, $y+15,$this->descr14); // $this->dtvenc);  // vencimento
  $this->objpdf->Text($x+158, $y+15,$this->dtparapag); // $this->dtvenc);  // vencimento
  
    $this->objpdf->SetFont('Arial','b',5);
    $this->objpdf->Text($x+15, $y+19,$this->prefeitura);                  // nome prefeitura
    $this->objpdf->Text($x+115, $y+19,db_formatar($this->cgcpref,'cnpj'));                    // cnpj
    $this->objpdf->Text($x+15, $y+23,$this->enderpref . ', '. $this->numeropref);                  // nome prefeitura
  
  $this->objpdf->SetFont('Arial','b',10);
  //$this->objpdf->Text($x+125,   $y+23,$this->tipo_convenio);             // tipo_convenio
  $this->objpdf->SetFont('Arial','',10);
  $this->objpdf->Text($x+138, $y+23,$this->agencia_cedente);   // agencia do cedente	

  $this->objpdf->Text($x+3,   $y+30,$this->data_processamento);                 // data do documento
  $this->objpdf->Text($x+29,  $y+30,$this->descr9);                             // numero do documento
  $this->objpdf->Text($x+75,  $y+30,$this->especie_doc);                        // especie do documento
  $this->objpdf->Text($x+101, $y+30,$this->aceite);                             // aceite
  $this->objpdf->Text($x+114, $y+30,date('d/m/Y'));           // data de opercao   data do processamento
//  $this->objpdf->Text($x+138, $y+30,str_pad($this->nosso_numero,17,"0",STR_PAD_LEFT));//   nosso numero
  $this->objpdf->Text($x+138, $y+30,$this->nosso_numero);//   nosso numero

  $this->objpdf->Text($x+3,   $y+37,"");        // codigo do cedente // 
  //$this->objpdf->Text($x+34,  $y+37,$this->carteira);          // carteira
  $this->objpdf->Text($x+34,  $y+37,"SR");          // carteira

  $this->objpdf->Text($x+54,  $y+37,$this->especie);           // especie

  $this->objpdf->Text($x+80,  $y+37,@$this->quantidade);        // quantidade
  $this->objpdf->Text($x+110, $y+37,@$this->valorhis);             // valor
  
//  $this->objpdf->setXY($x+114,$y+33);
  $this->objpdf->Text($x+131, $y+37, $this->valor_cobrado);//   nosso numero  
  //$this->objpdf->cell(60,4,$this->valor_cobrado,1,0,"L");//valor do Documento;
// 	$this->objpdf->Text($x+141, $y+37,$this->valtotal);          // valor do documento
  
  $this->objpdf->setXy($x+2,$y+41);
  $this->objpdf->SetFont('Arial','',9);

  $instrucao = "Tipo/Exercício: ".@$this->tipo_exerc.$this->descr12_1." \n".$this->pqllocal." \n".$this->sMensagemCaixa."\n";

  if(@$this->valororigem!=""){
  	$instrucao .= "Valor origem= ".trim($this->valororigem).' ';
  }
  if(@$this->valtotal!=""){
  	$instrucao .= "Valor corrigido = ".trim($this->valtotal).' ';;
  }
  if(@$this->desconto_abatimento!=""){
    $instrucao .= "Desconto/Abatimento = ".trim($this->desconto_abatimento).' ';;
	}
  if(@$this->mora_multa!=""){
    $instrucao .= "Mora/Multa = ".trim($this->mora_multa).' ';;
	}
	if(@$this->valor_cobrado!=""){
    $instrucao .= "Valor do documento = ".trim($this->valor_cobrado).' ';;
	}
	
	$this->objpdf->SetFont('Arial','',6);
  $this->objpdf->multicell(133,2,$instrucao); // Instrução
  $this->objpdf->SetFont('Arial','',10);
 
  $this->objpdf->setXY($x+136,$y+39);
  $this->objpdf->cell(30,6,"",0,0,"R");//desconto abatimento;  tirei @$this->desconto_abatimento
  $this->objpdf->setXY($x+136,$y+46);
  $this->objpdf->cell(30,6,'',0,0,"R");//outras deduções
  $this->objpdf->setXY($x+136,$y+53);
  $this->objpdf->cell(30,6,"",0,0,"R");//multa ...... @$this->mora_multa
  $this->objpdf->setXY($x+136,$y+60);
  $this->objpdf->cell(30,6,@$this->outros_acrecimos,0,0,"R");//outros acrescimos
  $this->objpdf->setXY($x+136,$y+67);
  $this->objpdf->cell(30,6,"",0,0,"R");//valor cobrado .... @$this->valor_cobrado
  $this->objpdf->SetFont('Arial','',8);
  $this->objpdf->Text($x+19,  $y+77,substr($this->descr11_1,0,42));         // sacado 1
  $this->objpdf->Text($x+93,  $y+77,"CPF/CNPJ: ".db_formatar(@$this->cgccpf,(strlen(@$this->cgccpf)<12?'cpf':'cnpj'))); // CPF/CNPJ
	$this->objpdf->Text($x+19,  $y+80,$this->descr11_2); // $this->ender);    // sacado 2
  if (!isset($this->ufcgm)) {
    $this->ufcgm = $this->uf_config; 
  }
  $this->objpdf->Text($x+19,  $y+83,$this->munic." / ".$this->ufcgm." / CEP-".$this->cep); // $this->munic);    // sacado 3
	 
?>