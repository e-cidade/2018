<?php

/*
$this->iptcabec_obs    = "* Válido até o vencimento, após retirar novo carnê";
$this->descr1          = "Descrição";
$this->descr2          = "Única";  
$this->emissao         = "Data de Emissão";
$this->descr_anousu    = "Exercício";
$this->descr3_1        = "Nome";
$this->descr3_2        = "CNPJ/CPF";
$this->descr4_1        = "Local Para Pagamento";
$this->descr4_2        = "Vencimento";
$this->descr5          = "Pagável nas agências do Banrisul e casas conveniadas";
$this->cedente         = "Cedente";
$this->valor_documento = "Valor Doc.";
$this->obs             = "Observações";
$this->descr6          = "Matrícula : ";
$this->descr7          = "BQL : ";   
$this->descr8          = "Valor sem desconto : ";
$this->descr9          = "Valor Venal : ";
$this->descr10         = "Desconto : ";
$this->descr11_1       = "Alíquota : ";
$this->descr11_2       = "Logradouro : ";
$this->descr12_1       = "Proprietário : ";
$this->descr12_2       = "Endereço do CGM"; 
$this->descr13         = "Endereço do Imóvel";
$this->descr14         = "Nome : ";
$this->descr15         = "Matr. : ";
$this->descr16_1       = "BQL : ";
$this->descr16_2       = "Rua : ";
$this->descr16_3       = "Rua : ";
$this->descr17         = "Bairro : ";
$this->descr18         = "Bairro : ";
$this->descr19         = "Cidade : ";
$this->descr20         = "CEP : ";
$this->descr21         = "Cidade : ";
*/

$this->objpdf->AddPage();
$linha = 4;
$coluna = 10;
$sb = 0;

for($i=0;$i<2;$i++){
    $linha  = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-3; 
    $this->objpdf->setxy($coluna,$linha);
    $this->objpdf->RoundedRect($coluna-3,$linha,203,117,2,'1234'); // rect geral		
//	$this->objpdf->Image('imagens/files/logo_boleto.png',$coluna+2,$linha+2,20);
	if(strlen($this->logo) == 0) {
		$this->logo = "logo_boleto.png";
	}
	$this->objpdf->Image("imagens/files/".$this->logo,$coluna+2,$linha+2,20);
	$this->objpdf->ln(2);
	$this->objpdf->SetFont('Times', 'B', 15);
	$this->objpdf->cell(35,5,"",$sb,0,"C",0);
	$this->objpdf->cell(125,5, $this->prefeitura,$sb,0,"L",0);		// Prefeitura
	$this->objpdf->cell(35,5,$this->tipodebito,$sb,1,"C",0);		// Tipo de Debito. Ex.: IPTU, 
	                                                                //                      PARCELAMENTO DE DIVIDA
	$linha += 5;
    
    $this->objpdf->SetFont('Times', 'B', 14);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
    $this->objpdf->cell(70,5," ",$sb,0,"C",0);
  	$this->objpdf->SetFont('Times', '', 8);
// 	$this->objpdf->cell(95,5,"* Válido até o vencimento, após retirar novo carnê",$sb,1,"C",0);
	$this->objpdf->cell(95,5,$this->iptcabec_obs,$sb,1,"C",0);
    
    $linha = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-2;
    
    $this->objpdf->RoundedRect($coluna+32,$linha,163,22,2,'1234'); // ok
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
//    $this->objpdf->cell(40,5, "Descrição",$sb,0,"C",0);
//    $this->objpdf->cell(40,5, "Vencimento",$sb,0,"C",0);
//    $this->objpdf->cell(40,5, "Data de Emissão",$sb,0,"C",0);    
//    $this->objpdf->cell(40,5, "Exercício",$sb,1,"C",0);
    $this->objpdf->cell(40,5, $this->descricao,$sb,0,"C",0);
	$this->objpdf->cell(40,5, $this->titulo6,$sb,0,"C",0);
    $this->objpdf->cell(40,5, $this->descr_dtemis,$sb,0,"C",0);    
    $this->objpdf->cell(40,5, $this->descr_anousu,$sb,1,"C",0);
    
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
//    $this->objpdf->cell(40,5,"Única",$sb,0,"C",0);
	$this->objpdf->cell(40,5, $this->descr_unica,$sb,0,"C",0);
    $this->objpdf->cell(40,5, $this->descr6,$sb,0,"C",0);
    $this->objpdf->cell(40,5, $this->iptdataemis,$sb,0,"C",0);
    $this->objpdf->cell(40,5, $this->iptexercicio,$sb,1,"C",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
//    $this->objpdf->cell(120,5,"Nome",$sb,0,"L",0);
//    $this->objpdf->cell(40,5,"CNPJ/CPF",$sb,1,"C",0);
    $this->objpdf->cell(120,5,$this->titulo3,$sb,0,"L",0);
    $this->objpdf->cell(40,5,$this->descr_cgccpf,$sb,1,"C",0);
    
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
    $this->objpdf->cell(120,5,$this->descr3_1,$sb,0,"L",0);
    $this->objpdf->cell(40,5,$this->iptz01_cgccpf,$sb,1,"C",0);
    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
    $linha += 30;

    $this->objpdf->setxy($coluna,$linha);

    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(10,5,"",$sb,0,"L",0);
//    $this->objpdf->cell(140,5,"Local Para Pagamento",$sb,0,"L",0);
//    $this->objpdf->cell(50,5,"Vencimento",$sb,1,"C",0);
    $this->objpdf->cell(140,5,$this->titulo12,$sb,0,"L",0);
    $this->objpdf->cell(50,5,$this->titulo6,$sb,1,"C",0);
    $this->objpdf->SetFont('Times', '', 8);
    $this->objpdf->cell(10,5,"",$sb,0,"L",0);
//    $this->objpdf->cell(140,5,"Pagável nas agências do Banrisul e casas conveniadas",$sb,0,"L",0);
	if($i==0) {
	    $parte1 = substr($this->descr12_1,0,115);
	    $parte2 = substr($this->descr12_1,115,115);
	    $parte3 = substr($this->descr12_1,230,115);

		$this->objpdf->cell(140,5," ",$sb,0,"L",0);
		$y = $linha;
	    $this->objpdf->Text(30,$y+6,$parte1);
		$this->objpdf->Text(30,$y+9,$parte2);
		$this->objpdf->Text(30,$y+11,$parte3);
		$this->objpdf->Text(30,$y+12,$this->descr12_2);
	}
	else {
	    $parte1 = $this->descr16_1;
	    $parte2 = $this->descr16_2;
	    $parte3 = $this->descr16_3;

		$this->objpdf->cell(140,5," ",$sb,0,"L",0);
		$y = $linha;
	    $this->objpdf->Text(30,$y+6,$parte1);
		$this->objpdf->Text(30,$y+9,$parte2);
		$this->objpdf->Text(30,$y+11,$parte3);
		$this->objpdf->Text(30,$y+12,$this->descr12_2);
	}

    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,$this->descr6,$sb,1,"C",0);
    $linha += 34;

    $linha = $this->objpdf->GetY()-5; 
    $coluna = $this->objpdf->GetX()-2;

    $this->objpdf->RoundedRect($coluna,$linha-10,195,25,2,'1234');

    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(10,5,"",$sb,0,"L",0);
//    $this->objpdf->cell(140,5,"Cedente",$sb,0,"L",0);
//    $this->objpdf->cell(50,5,"Valor Doc.",$sb,1,"C",0);
    $this->objpdf->cell(140,5,"Cedente",$sb,0,"L",0);
    $this->objpdf->cell(50,5,$this->titulo7,$sb,1,"C",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(10,5,"",$sb,0,"L",0);
    $this->objpdf->cell(140,5,$this->prefeitura,$sb,0,"L",0);
    $this->objpdf->cell(50,5,$this->descr7,$sb,1,"C",0);
    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
  $linha += 15;
    
    $linha = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-2;
        
    $this->objpdf->RoundedRect($coluna,$linha,195,35,2,'1234'); // rect das observações
    $this->objpdf->SetFont('Times', 'B', 14);
    $this->objpdf->cell(200,5,"Observações",$sb,1,"C",0);
    $this->objpdf->SetFont('Times', 'B', 12);
//    $this->objpdf->cell(50,5,"Matrícula : ",$sb,0,"R",0);
    $this->objpdf->cell(50,5,$this->iptjtit_matric,$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,$this->iptj01_matric,$sb,0,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
//    $this->objpdf->cell(50,5,"BQL : ",$sb,0,"R",0);
    $this->objpdf->cell(50,5,$this->descr_bql,$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(150,5,$this->iptbql,$sb,1,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
//    $this->objpdf->cell(50,5,"Valor sem desconto : ",$sb,0,"R",0);
    $this->objpdf->cell(50,5,$this->descr_vrlcor,$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,$this->iptuvlrcor,$sb,0,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
//    $this->objpdf->cell(50,5,"Valor Venal : ",$sb,0,"R",0);
    $this->objpdf->cell(50,5,$this->descr_vlrter,$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,$this->iptj23_vlrter,$sb,1,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
//    $this->objpdf->cell(50,5,"Desconto : ",$sb,0,"R",0);
    $this->objpdf->cell(50,5,$this->iptdesc_desconto,$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,$this->iptuvlrdesconto,$sb,0,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
//    $this->objpdf->cell(50,5,"Alíquota : ",$sb,0,"R",0);
    $this->objpdf->cell(50,5,$this->descr_aliq,$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,$this->iptj23_aliq,$sb,1,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
//    $this->objpdf->cell(50,5,"Logradouro : ",$sb,0,"R",0);
    $this->objpdf->cell(50,5,$this->titulo10,$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(150,5,$this->descr15,$sb,1,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
//    $this->objpdf->cell(50,5,"Proprietário : ",$sb,0,"R",0);
    $this->objpdf->cell(50,5,$this->iptproprietario,$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(150,5,$this->descr11_1,$sb,1,"L",0);

    $this->objpdf->SetFont('Times', 'B', 16);
    $this->objpdf->SetFont('Times', '', 8);
    /**/
    if(isset($this->iptdebant)&&$this->iptdebant!=""){
    	$this->objpdf->SetFont('Times', '', 8);
    	$this->objpdf->cell(200,5,$this->iptdebant,$sb,1,"C",0);
    }else{
    	$this->objpdf->cell(200,5,"",$sb,1,"R",0);
    }
    /**/
    $this->objpdf->SetFont('Times','', 10);
    $this->objpdf->cell(200,5,$this->linha_digitavel,$sb,1,"C",0);

    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
        
    $linha  = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-2;
    
    $this->objpdf->int25($coluna+45,$linha-17,$this->codigo_barras,9,0.341);
   
}
//    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
//    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
//    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
//    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
    
    $sb = 0;
    $linha = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX();
    $this->objpdf->RoundedRect($coluna-3,$linha-5,198,25,2,'1234');

    //linha 1
    $this->objpdf->cell(100,4,"Endereço do CGM",$sb,0,"C",0);
    $this->objpdf->cell(100,4,"Endereço do Imóvel","L",1,"C",0);
//    $this->objpdf->cell(100,4,"",$sb,0,"C",0);
//    $this->objpdf->cell(100,4,"","L",1,"C",0);

    //linha 2  
//    $this->objpdf->cell(100,3,"Nome : ".$this->iptz01_nome,$sb,0,"L",0);
//    $this->objpdf->cell(50,3,"Matr. : ".$this->iptj01_matric,"L",0,"L",0);
//    $this->objpdf->cell(50,3,"BQL : ".$this->iptbql,$sb,1,"L",0);
    $this->objpdf->cell(100,3,$this->titulo3.$this->descr3_1,$sb,0,"L",0);
    $this->objpdf->cell(50,3,"Matr. : ".$this->iptj01_matric_rodape,"L",0,"L",0);
    $this->objpdf->cell(50,3,"SQL : ".$this->iptbql,$sb,1,"L",0);

    //linha 3
//    $this->objpdf->cell(100,3,"Rua : ".$this->iptz01_ender,$sb,0,"L",0);
//    $this->objpdf->cell(100,3,"Rua : ".$this->iptnomepri.", ".$this->iptcodpri,"L",1,"L",0);
    $this->objpdf->cell(100,3,$this->titulo10.$this->descr10,$sb,0,"L",0);
    $this->objpdf->cell(100,3,$this->titulo11.$this->descr11_2,"L",1,"L",0);

    //linha 4
    $this->objpdf->cell(100,3,"Bairro : ".$this->iptz01_bairro,$sb,0,"L",0);
    $this->objpdf->cell(100,3,"Bairro : ".$this->iptbairroimo,"L",1,"L",0);

    //linha 5
    $this->objpdf->cell(50,3,"Cidade : ".$this->iptz01_munic,$sb,0,"L",0);
    $this->objpdf->cell(50,3,"CEP : ".$this->iptz01_cep,$sb,0,"L",0);
    $this->objpdf->cell(50,3,"Cidade : ".$this->iptz01_cidade,"L",0,"L",0);
//    $this->objpdf->cell(50,3,"",$sb,0,"L",0);
//    $this->objpdf->cell(50,3,"",$sb,0,"L",0);
//    $this->objpdf->cell(50,3,"","L",0,"L",0);
?>
