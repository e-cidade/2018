<?php

$this->objpdf->AddPage();
$linha = 4;
$coluna = 10;
$sb = 0;

for($i=0;$i<2;$i++){
//  $this->objpdf->RoundedRect(coluna,0.2,largura,altura,2,'1234');
    
    $linha  = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-3; 
    
    $this->objpdf->setxy($coluna,$linha);
     
    $this->objpdf->RoundedRect($coluna-3,$linha,203,115,2,'1234'); // rect geral		

	$this->objpdf->Image('imagens/files/logo_boleto.png',$coluna+2,$linha+2,20);
	$this->objpdf->ln(2);
	$this->objpdf->SetFont('Times', 'B', 15);
	$this->objpdf->cell(35,5,"",$sb,0,"C",0);
	$this->objpdf->cell(125,5, $this->iptprefeitura,$sb,0,"L",0);
	$this->objpdf->cell(35,5,"IPTU " . $this->iptj23_anousu,$sb,1,"C",0);
	$linha += 5;
    
    $this->objpdf->SetFont('Times', 'B', 14);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
//    $this->objpdf->cell(70,5,"Administração Popular",$sb,0,"C",0);
    	$this->objpdf->SetFont('Times', '', 8);
    	$this->objpdf->cell(95,5,"* Válido até o vencimento, após retirar novo carnê",$sb,1,"C",0);
//    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
    
    $linha = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-2;
    
    $this->objpdf->RoundedRect($coluna+32,$linha,163,22,2,'1234'); // ok
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
    $this->objpdf->cell(40,5, "Descrição",$sb,0,"C",0);
    $this->objpdf->cell(40,5, "Vencimento",$sb,0,"C",0);
    $this->objpdf->cell(40,5, "Data de Emissão",$sb,0,"C",0);    
    $this->objpdf->cell(40,5, "Exercício",$sb,1,"C",0);
    
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
    $this->objpdf->cell(40,5,"Única",$sb,0,"C",0);
    $this->objpdf->cell(40,5, $this->iptdtvencunic,$sb,0,"C",0);
    $this->objpdf->cell(40,5, $this->iptdataemis,$sb,0,"C",0);
    $this->objpdf->cell(40,5, $this->iptj23_anousu,$sb,1,"C",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
    $this->objpdf->cell(120,5,"Nome",$sb,0,"L",0);
    $this->objpdf->cell(40,5,"CGM",$sb,1,"C",0);
    
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(35,5,"",$sb,0,"C",0);
    $this->objpdf->cell(120,5,$this->iptz01_nome,$sb,0,"L",0);
    $this->objpdf->cell(40,5,$this->iptz01_numcgm,$sb,1,"C",0);
    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
    $linha += 25;

    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(10,5,"",$sb,0,"L",0);
    $this->objpdf->cell(140,5,"Instrucoes",$sb,0,"L",0);
    $this->objpdf->cell(50,5,"Vencimento",$sb,1,"C",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(10,5,"",$sb,0,"L",0);
    $this->objpdf->cell(140,5,$this->msgunica,$sb,0,"L",0);
    $this->objpdf->cell(50,5,$this->iptdtvencunic,$sb,1,"C",0);
    $linha += 20;

    $linha = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-2;
    
    $this->objpdf->RoundedRect($coluna,$linha-10,195,22,2,'1234');
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(10,5,"",$sb,0,"L",0);
    $this->objpdf->cell(140,5,"Cedente",$sb,0,"L",0);
    $this->objpdf->cell(50,5,"Valor Doc.",$sb,1,"C",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(10,5,"",$sb,0,"L",0);
    $this->objpdf->cell(140,5,$this->iptprefeitura,$sb,0,"L",0);
    $this->objpdf->cell(50,5,"R$ " . $this->ipttotal,$sb,1,"C",0);
    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
  $linha += 15;
    
    $linha = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-2;
        
    $this->objpdf->RoundedRect($coluna,$linha,195,35,2,'1234'); // rect das observações
    $this->objpdf->SetFont('Times', 'B', 14);
    $this->objpdf->cell(200,5,"Observações",$sb,1,"C",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(50,5,"Matrícula : ",$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,$this->iptj01_matric,$sb,0,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(50,5,"BQL : ",$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(150,5,$this->iptbql,$sb,1,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(50,5,"Valor sem desconto : ",$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,"R$ " . $this->iptuvlrcor,$sb,0,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(50,5,"Valor Venal : ",$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,$this->iptj23_vlrter,$sb,1,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(50,5,"Desconto : ",$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,"R$ " . $this->iptuvlrdesconto,$sb,0,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(50,5,"Alíquota : ",$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(50,5,$this->iptj23_aliq,$sb,1,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(50,5,"Logradouro : ",$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(150,5,$this->iptnomepri.", ".$this->iptcodpri . (trim($this->iptcompl)!=""?"/" . $this->iptcompl:""),$sb,1,"L",0);
    
    $this->objpdf->SetFont('Times', 'B', 12);
    $this->objpdf->cell(50,5,"Proprietário : ",$sb,0,"R",0);
    $this->objpdf->SetFont('Times', '', 10);
    $this->objpdf->cell(150,5,$this->iptz01_numcgm . " - " . $this->iptproprietario,$sb,1,"L",0);

    $this->objpdf->SetFont('Times', 'B', 16);
    $this->objpdf->SetFont('Times', '', 8);
    /**/
    if(isset($this->iptdebant)&&$this->iptdebant!=""){
    	$this->objpdf->SetFont('Times', '', 8);
    	$this->objpdf->cell(200,5,$this->iptdebant,$sb,1,"C",0);
    }else{
    	$this->objpdf->cell(200,5,"",$sb,1,"R",0);
    }
//    $this->objpdf->cell(200,5,$this->iptdebant,$sb,1,"C",0);
    /**/
    $this->objpdf->SetFont('Times','', 10);
    $this->objpdf->cell(200,5,$this->iptlinha_digitavel,$sb,1,"C",0);

    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
    $this->objpdf->cell(200,5,"",$sb,1,"C",0);
    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
        
    $linha  = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-2;
    
    $this->objpdf->int25($coluna+45,$linha-17,$this->iptcodigo_barras,7,0.341);
   
}
    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
    $this->objpdf->cell(200,2,"",$sb,1,"C",0);
    
    $sb = 0;
    $linha = $this->objpdf->GetY(); 
    $coluna = $this->objpdf->GetX()-2;
    $this->objpdf->RoundedRect($coluna-3,$linha-5,203,25,2,'1234');

    //linha 1
    $this->objpdf->cell(100,4,"Endereço do CGM",$sb,0,"C",0);
    $this->objpdf->cell(100,4,"Endereço do Imóvel","L",1,"C",0);

    //linha 2  
    $this->objpdf->cell(100,3,"Nome : ".$this->iptz01_nome,$sb,0,"L",0);
    $this->objpdf->cell(50,3,"Matr. : ".$this->iptj01_matric,"L",0,"L",0);
    $this->objpdf->cell(50,3,"BQL : ".$this->iptbql,$sb,1,"L",0);

    //linha 3
    $this->objpdf->cell(100,3,"Rua : ".$this->iptz01_ender,$sb,0,"L",0);
    $this->objpdf->cell(100,3,"Rua : ".$this->iptnomepri.", ".$this->iptcodpri . (trim($this->iptcompl)!=""?"/" . $this->iptcompl:""),"L",1,"L",0);

    //linha 4
    $this->objpdf->cell(100,3,"Bairro : ".$this->iptz01_bairro,$sb,0,"L",0);
    $this->objpdf->cell(100,3,"Bairro : ".$this->iptbairroimo,"L",1,"L",0);

    //linha 5
    $this->objpdf->cell(50,3,"Cidade : ".$this->iptz01_munic,$sb,0,"L",0);
    $this->objpdf->cell(50,3,"CEP : ".$this->iptz01_cep,$sb,0,"L",0);
    $this->objpdf->cell(50,3,"Cidade : ".$this->iptz01_cidade,"L",0,"L",0);
//    $this->objpdf->cell(50,3,"CEP : ".$this->iptj43_cep,$sb,0,"L",0);
?>
