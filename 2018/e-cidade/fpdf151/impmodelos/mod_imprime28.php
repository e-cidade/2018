<?php
//$y = $this->objpdf->GetY()-1;
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
  $this->objpdf->Image('imagens/files/'.$this->logo,$coluna+2,$linha+2,20);
  $this->objpdf->ln(2);
  $this->objpdf->SetFont('Times', 'B', 15);
  $this->objpdf->cell(35,5,"",$sb,0,"C",0);
  $this->objpdf->cell(125,5, $this->iptprefeitura,$sb,0,"L",0);
  //$this->objpdf->cell(125,5, $this->prefeitura,$sb,0,"L",0);
  $this->objpdf->cell(35,5,"IPTU ".$this->iptj23_anousu,$sb,1,"C",0);
  //$this->objpdf->cell(35,5,$this->iptj23_anousu,$sb,1,"C",0);
  //$this->objpdf->cell(35,5,$this->tipodebito,$sb,1,"C",0);
  $linha += 5;

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(35,5,"",$sb,0,"C",0);
  //    $this->objpdf->cell(70,5,"Administração Popular",$sb,0,"C",0);
  $this->objpdf->cell(70,5,@$this->iptsubtitulo,$sb,0,"C",0);
  $this->objpdf->SetFont('Times', '', 8);
  $this->objpdf->cell(95,5,"* Válido até o vencimento, após retirar novo carnê",$sb,1,"C",0);
  //  $this->objpdf->cell(200,5,"",$sb,1,"C",0);

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
  if(isset($this->descr10) && $this->descr10 != ""){
    $this->objpdf->cell(40,5,$this->descr10,$sb,0,"C",0);	
  }else{
    $this->objpdf->cell(40,5,"Única",$sb,0,"C",0);    	
  }    
  $this->objpdf->cell(40,5, $this->iptdtvencunic,$sb,0,"C",0);
  //$this->objpdf->cell(40,5, $this->descr6,$sb,0,"C",0);
  $this->objpdf->cell(40,5, $this->iptdataemis,$sb,0,"C",0);
  //$this->objpdf->cell(40,5, $this->data_processamento,$sb,0,"C",0);
  //     die("xxxxxxxxx".$this->iptdataemis);    
  //$this->objpdf->cell(40,5, $this->tipodebito,$sb,1,"C",0);
  $this->objpdf->cell(40,5, $this->iptj23_anousu,$sb,1,"C",0);

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(35,5,"",$sb,0,"C",0);
  $this->objpdf->cell(120,5,"Nome",$sb,0,"L",0);
  $this->objpdf->cell(40,5,"CGM",$sb,1,"C",0);

  $this->objpdf->SetFont('Times', '', 10);
  $this->objpdf->cell(35,5,"",$sb,0,"C",0);
  $this->objpdf->cell(120,5,$this->nome_especifico,$sb,0,"L",0);
  //$this->objpdf->cell(120,5,$this->descr3_1,$sb,0,"L",0);
  $this->objpdf->cell(40,5,$this->cgm_especifico,$sb,1,"C",0);
  //	  $this->objpdf->cell(40,5,$this->cgccpf,$sb,1,"C",0);
  //  $this->objpdf->cell(40,5,,$sb,1,"C",0);
  $this->objpdf->cell(200,5,"",$sb,1,"C",0);
  $linha += 25;

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(10,5,"",$sb,0,"L",0);
  // $this->objpdf->cell(140,5,"Local Para Pagamento",$sb,0,"L",0);
  $this->objpdf->cell(140,5,$this->titulo4,$sb,0,"L",0);
  $this->objpdf->cell(50,5,"Vencimento",$sb,1,"C",0);
  $this->objpdf->SetFont('Times', '', 9);
  $this->objpdf->cell(10,5,"",$sb,0,"L",0);

  
  if(isset($this->descr12_1) && $this->descr12_1 != ""){

    $linha_atual = $this->objpdf->GetY(); 
    $this->objpdf->multicell(140,3,$this->descr12_1); // Instruções 1 - linha 1
    $this->objpdf->setxy(160,$linha_atual);

  }else{ 

    $this->objpdf->cell(140,5, $this->sMensagemContribuinte, $sb,0,"L",0);
  }

  $this->objpdf->cell(50,5,$this->iptdtvencunic,$sb,1,"C",0);
  //$this->objpdf->cell(50,5,$this->dtparapag,$sb,1,"C",0);
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
  //$this->objpdf->cell(140,5,$this->prefeitura,$sb,0,"L",0);
  $this->objpdf->cell(140,5,$this->iptprefeitura,$sb,0,"L",0);
  //    $this->objpdf->cell(50,5,$this->descr7,$sb,1,"C",0);
  $this->objpdf->cell(50,5,"R$ ".trim($this->ipttotal),$sb,1,"C",0);
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
  //    $this->objpdf->cell(50,5,$this->descr1,$sb,0,"L",0);
  $this->objpdf->cell(50,5,$this->iptj01_matric,$sb,0,"L",0);

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(50,5,"BQL : ",$sb,0,"R",0);
  $this->objpdf->SetFont('Times', '', 10);
  $this->objpdf->cell(40,5,$this->iptbql,$sb,1,"R",0);

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(50,5,"Valor sem desconto : ",$sb,0,"R",0);
  $this->objpdf->SetFont('Times', '', 10);
  $this->objpdf->cell(50,5,"R$ ".trim($this->iptuvlrcor),$sb,0,"L",0);
  //    $this->objpdf->cell(50,5,$this->descr7,$sb,0,"L",0);
  //$this->objpdf->cell(50,5,$this->valtotal,$sb,0,"L",0);

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(50,5,"Valor Venal : ",$sb,0,"R",0);
  $this->objpdf->SetFont('Times', '', 10);
  $this->objpdf->cell(40,5,$this->iptj23_vlrter,$sb,1,"R",0);

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(50,5,"Desconto : ",$sb,0,"R",0);
  $this->objpdf->SetFont('Times', '', 10);
  $this->objpdf->cell(50,5,"R$ ".trim($this->iptuvlrdesconto),$sb,0,"L",0);

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(50,5,"Alíquota : ",$sb,0,"R",0);
  $this->objpdf->SetFont('Times', '', 10);
  $this->objpdf->cell(40,5,$this->iptj23_aliq,$sb,1,"R",0);

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(50,5,"Logradouro : ",$sb,0,"R",0);
  $this->objpdf->SetFont('Times', '', 10);
  //$this->objpdf->cell(150,5,$this->iptnomepri." ".$this->iptcodpri,$sb,1,"L",0);
  $this->objpdf->cell(150,5,@$this->iptendermatric,$sb,1,"L",0);
  //$this->objpdf->cell(150,5,$this->descr3_2,$sb,1,"L",0);

  $this->objpdf->SetFont('Times', 'B', 12);
  $this->objpdf->cell(50,5,"Contribuinte : ",$sb,0,"R",0);
  $this->objpdf->SetFont('Times', '', 10);
  $this->objpdf->cell(150,5,$this->iptproprietario,$sb,1,"L",0);
  //$this->objpdf->cell(150,5,$this->descr11_1,$sb,1,"L",0);

  $this->objpdf->SetFont('Times', 'B', 16);
  $this->objpdf->SetFont('Times', '', 8);
  /**/
  if(isset($this->iptdebant)&&$this->iptdebant!=""){
    $this->objpdf->SetFont('Times', '', 8);
    $this->objpdf->cell(200,5,$this->iptdebant,$sb,1,"C",0);
  }else{
    $this->objpdf->cell(200,5,"",$sb,1,"R",0);
  }

  //$this->objpdf->cell(200,5,$this->iptdebant,$sb,1,"C",0);

  /**/
  $this->objpdf->SetFont('Times','', 10);
  $this->objpdf->cell(200,5,$this->iptlinha_digitavel,$sb,1,"C",0);
  //$this->objpdf->cell(200,5,$this->linha_digitavel,$sb,1,"C",0);

  $this->objpdf->cell(200,5,"",$sb,1,"C",0);
  $this->objpdf->cell(200,5,"",$sb,1,"C",0);
  $this->objpdf->cell(200,5,"",$sb,1,"C",0);
  $this->objpdf->cell(200,2,"",$sb,1,"C",0);

  $linha  = $this->objpdf->GetY(); 
  $coluna = $this->objpdf->GetX()-2;
  $this->objpdf->int25($coluna+45,$linha-17,$this->iptcodigo_barras,9,0.341);
  //$this->objpdf->int25($coluna+45,$linha-17,$this->codigo_barras,9,0.341);

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
//$this->objpdf->cell(100,4,"Endereço do CGM",$sb,0,"C",0);
$this->objpdf->cell(100,4,"Endereço do Imóvel (Entrega) ",0,1,"C",0);

//linha 2  
$this->objpdf->cell(100,3,"Nome : ".$this->iptz01_nome,0,1,"L",0);    // coluna 1
$this->objpdf->cell(50,3,"Matr. : ".$this->iptj01_matric,0,0,"L",0);  // coluna 2
//$this->objpdf->cell(50,3,"Matr. : ".$this->descr1,0,0,"L",0);  // coluna 2
$this->objpdf->cell(50,3,"BQL : ".$this->iptbql,$sb,1,0,0);           // coluna 2 
//$this->objpdf->cell(50,3,"BQL : ".$this->bql28,$sb,1,0,0);           // coluna 2 

//linha 3
$this->objpdf->cell(100,3,"Rua : ".$this->iptz01_ender,$sb,1,"L",0);                    // coluna 1
//$this->objpdf->cell(100,3,"Rua : ".$this->iptnomepri.", ".$this->iptcodpri,0,1,"L",0);// coluna 2
//$this->objpdf->cell(100,3,"Rua :  ".$this->descr3_2,0,1,"L",0);// coluna 2

//linha 4
$this->objpdf->cell(100,3,"Bairro : ".$this->iptz01_bairro,$sb,1,"L",0); // coluna 1
// die("ooooo oooo oooo".$this->iptbairroimo);
//$this->objpdf->cell(100,3,"Bairro : ".$this->iptbairroimo,0,1,"L",0);  // coluna 2
//$this->objpdf->cell(100,3,"Bairro : ".$this->descr3_3	,0,1,"L",0);  // coluna 2

//linha 5
//$this->objpdf->cell(50,3,"Cidade : ".$this->iptz01_munic,$sb,0,"L",0);    // coluna 1
//$this->objpdf->cell(50,3,"CEP : ".   $this->iptz01_cep,$sb,0,"L",0);         // coluna 1
$this->objpdf->cell(50,3,"Cidade : ".$this->iptz01_cidade,0,0,"L",0);   // coluna 2
//$this->objpdf->cell(50,3,"Cidade : ".$this->munic,0,0,"L",0);   // coluna 2
$this->objpdf->cell(50,3,"CEP : ".   $this->iptj43_cep,$sb,0,"L",0);
//$this->objpdf->cell(50,3,"CEP : ".   $this->cep,$sb,0,"L",0);
?>
