<?php
$iSetFontLabel = 6;
$iSetFontText  = 8;
$iSetAltLabel  = 4;
$iSetAlText    = 6;
$iSetTamCell   = 190;

$sCgcCpfTrans  = "";
$sCgcCpfContr  = "";
$sComplemento  = "";
$sFrenteViaSim = "";
$sFrenteViaNao = "";

$aDescr        = array();                        
$aTipo         = array();
$aArea         = array();
$aAreaTrans    = array();
$aAnoConstr    = array();

$this->objpdf->AddPage();
$this->objpdf->SetFillColor(235);

for ($i = 0; $i < 1; $i++){ 

  $this->objpdf->SetFont('Arial','B',$iSetFontText+2);  
  $this->objpdf->Cell($iSetTamCell-50,$iSetAlText,$this->nomeinst." - ".$this->z01_uf,0,0,"L",0);
  $this->objpdf->Cell($iSetTamCell-188,$iSetAlText,"",0,1,"C",0);  
  
  $iGetYLateral = $this->objpdf->GetY();
  $iGetXLateral = $this->objpdf->GetX(); 
  
  $this->objpdf->SetFont('Arial','B',$iSetFontText+2);  
  $this->objpdf->Cell($iSetTamCell-50,$iSetAlText,$this->nomecompprinc.$this->outroscompradores,0,0,"L",0); 
  $this->objpdf->Cell($iSetTamCell-188,$iSetAlText,"",0,1,"C",0); 
  
  $this->objpdf->SetFont('Arial','B',$iSetFontText);  
  $this->objpdf->Cell($iSetTamCell-50,$iSetAlText,"Conhecimento de Arrecadação ITIVBI",0,0,"L",0); 
  $this->objpdf->Cell($iSetTamCell-188,$iSetAlText,"",0,1,"C",0);  
  
  $this->objpdf->Cell($iSetTamCell,$iSetAlText-4,"",0,1,"C",0);
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(140,$iSetAltLabel,"Nome Contribuinte:","RTL",1,"L",0);
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(140,$iSetAlText,$this->nomecompprinc.$this->outroscompradores,"RBL",1,"L",0); 
  
  $this->objpdf->Cell($iSetTamCell,$iSetAlText-4,"",0,1,"C",0);
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(140,$iSetAltLabel,"Endereço:","RTL",1,"L",0);
  
  $sEndereco = substr($this->enderecocomprador.','.$this->numerocomprador.' / '.$this->complcomprador,0,50);
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(140,$iSetAlText,$sEndereco,"RBL",1,"L",0);  
  
  $this->objpdf->Cell($iSetTamCell,$iSetAlText-4,"",0,1,"C",0);
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(140,$iSetAltLabel,"Especificação da Receita:","RTL",1,"L",0);
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(140,$iSetAlText,"Imposto Sobre Transmissão de Bens Imóveis (ITBI)","RBL",1,"L",0);    
  
  $this->objpdf->Cell($iSetTamCell,$iSetAlText-4,"",0,1,"C",0); 
  
  $iGetYRepArr = $this->objpdf->GetY();
  $iGetXRepArr = $this->objpdf->GetX();   
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(140,$iSetAltLabel,"Observações:","RTL",1,"L",0);
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(140,42,"","RBL",1,"L",0);  
  
  $iGetY = $this->objpdf->GetY();
  $iGetX = $this->objpdf->GetX(); 
  
  $this->objpdf->SetY($iGetY-42);
  $this->objpdf->SetX($iGetX+2);  
  
  $sMsgObs  = $this->propri.$this->proprietarios.(strlen(trim($this->propri.$this->proprietarios)) > 0?"\n ":"");
  $sMsgObs .= (strlen(trim($this->it01_obs)) > 0?$this->it01_obs:"").". ".$this->sMsgSituacaoImovel;
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->MultiCell(138,4,substr($sMsgObs, 0, 800),0,"L",0);  

  $this->objpdf->ln();
  
  $this->objpdf->SetY($iGetYRepArr+46);
  $this->objpdf->SetX($iGetXRepArr);   
  
  $this->objpdf->Cell($iSetTamCell,$iSetAlText-4,"",0,1,"C",0);
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(40,$iSetAltLabel,"Uso da Repartição:","RTL",0,"L",0);
  $this->objpdf->Cell(2,$iSetAltLabel,"",0,0,"C",0);
  $this->objpdf->Cell(40,$iSetAltLabel,"Agente Arrecadador:","RTL",0,"L",0);
  $this->objpdf->Cell(2,$iSetAltLabel,"",0,1,"C",0);
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(40,$iSetAlText+14,"","RBL",0,"L",0); 
  $this->objpdf->Cell(2,$iSetAlText+14,"",0,0,"C",0);
  $this->objpdf->Cell(40,$iSetAlText+14,"","RBL",0,"L",0);
  $this->objpdf->Cell(2,$iSetAlText+12,"",0,1,"C",0);  
  
  $iGetYProcQuit = $this->objpdf->GetY();
  $iGetXProcQuit = $this->objpdf->GetX(); 
  
  $this->objpdf->SetY($iGetYProcQuit-22);
  $this->objpdf->SetX($iGetXProcQuit+84);   
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
  $this->objpdf->Cell(56,$iSetAltLabel,"Uso do Processo:","RTL",1,"L",0);  
  
  $this->objpdf->SetY($iGetYProcQuit-10);
  $this->objpdf->SetX($iGetXProcQuit+84);   
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
  $this->objpdf->Cell(106,$iSetAltLabel,"Linha Digitável:","RTL",1,"L",0); 
  
  $this->objpdf->SetY($iGetYProcQuit-18);
  $this->objpdf->SetX($iGetXProcQuit+84);  
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(56,$iSetAlText,"","RBL",0,"L",0);  
  
  $this->objpdf->SetY($iGetYProcQuit-6);
  $this->objpdf->SetX($iGetXProcQuit+84);  
  
  $iGetY = $this->objpdf->GetY();
  $iGetX = $this->objpdf->GetX(); 
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(106,$iSetAlText+2,$this->objpdf->Text($iGetX+1,$iGetY+4,$this->linha_digitavel),"RBL",1,"L",0);   
  
  $this->objpdf->SetY($iGetYLateral-4);
  $this->objpdf->SetX($iGetXLateral+142);  
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(48,$iSetAltLabel,"Guia: ","RTL",1,"L",0);  
  
  $this->objpdf->SetY($iGetYLateral);
  $this->objpdf->SetX($iGetXLateral+142); 
  
  $this->objpdf->SetFont('Arial','B',$iSetFontText+3);  
  $this->objpdf->Cell(48,$iSetAlText+6,$this->itbi,"RBL",1,"C",0);  

  $iGetY = $this->objpdf->GetY();
  $iGetX = $this->objpdf->GetX(); 
  
  $this->objpdf->SetY($iGetY+2);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(48,$iSetAltLabel,"Vencimento: ","RTL",1,"L",0); 
  
  $this->objpdf->SetY($iGetY+6);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(48,$iSetAlText,db_formatar($this->datavencimento,'d'),"RBL",1,"L",0);  
  
  $this->objpdf->SetY($iGetY+14);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(48,$iSetAltLabel,"Código: ","RTL",1,"L",0); 
  
  $this->objpdf->SetY($iGetY+18);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(48,$iSetAlText,"","RBL",1,"L",0);   
  
  $this->objpdf->SetY($iGetY+26);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(48,$iSetAltLabel,"Código: ","RTL",1,"L",0); 
  
  $this->objpdf->SetY($iGetY+30);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(48,$iSetAlText,"","RBL",1,"L",0);   

  $this->objpdf->SetY($iGetY+38);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(48,$iSetAltLabel,"Código: ","RTL",1,"L",0); 
  
  $this->objpdf->SetY($iGetY+42);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(48,$iSetAlText,"","RBL",1,"L",0);   
  
  $this->objpdf->SetY($iGetY+50);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(48,$iSetAltLabel,"Código: ","RTL",1,"L",0); 
  
  $this->objpdf->SetY($iGetY+54);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(48,$iSetAlText,"","RBL",1,"L",0);  

  $this->objpdf->SetY($iGetY+62);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(48,$iSetAltLabel,"Código: ","RTL",1,"L",0); 
  
  $this->objpdf->SetY($iGetY+66);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(48,$iSetAlText,"","RBL",1,"L",0);   
  
  $this->objpdf->SetY($iGetY+74);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(48,$iSetAltLabel,"Código: ","RTL",1,"L",0); 
  
  $this->objpdf->SetY($iGetY+78);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(48,$iSetAlText,"","RBL",1,"L",0);   
  
  
  $this->objpdf->SetY($iGetY+86);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);  
  $this->objpdf->Cell(48,$iSetAltLabel,"Total: ","RTL",1,"L",0); 
  
  if ($this->it14_valorpaga == 0) {
    $iValorTotal = 0;
  } else {
    $iValorTotal = db_formatar(($this->it14_valorpaga + $this->tx_banc),'f');
  }  
  
  $this->objpdf->SetY($iGetY+90);
  $this->objpdf->SetX($iGetX+142);  
  
  $this->objpdf->SetFont('Arial','',$iSetFontText);  
  $this->objpdf->Cell(48,$iSetAlText,"R$".$iValorTotal,"RBL",1,"L",0);   
  
  if (trim($this->tipoitbi) == "urbano") {
  	
	  // Modelo Guia Itbi Urbana
	  
	  $iGetY = $this->objpdf->GetY();
	  $iGetX = $this->objpdf->GetX(); 
	  
	  $this->objpdf->SetY($iGetY+14);
	  $this->objpdf->SetX($iGetX); 
	  
	  $this->objpdf->Cell($iSetTamCell,$iSetAlText,"",0,1,"C",0); 
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(18,$iSetAlText,"Transmitente:",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(172,$iSetAlText,$this->z01_nome.$this->outrostransmitentes,1,1,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(80,$iSetAlText,"",1,0,"L",0);  
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(18,$iSetAlText,"CPF ou CGC:",1,0,"L",0);  
	  
	   if (isset($this->z01_cgccpf) && !empty($this->z01_cgccpf)) {
	    
	     if ( trim(strlen($this->z01_cgccpf)) <= 11 ) {
	       $sCgcCpfTrans = db_formatar(trim($this->z01_cgccpf),'cpf');
	     } else {
	       $sCgcCpfTrans = db_formatar(trim($this->z01_cgccpf),'cnpj');
	     }   
	   } 
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(32,$iSetAlText,$sCgcCpfTrans,1,0,"L",0); 
	
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(10,$iSetAlText,"Zona:",1,0,"L",0); 
	  
	   if ($this->tipoitbi == "urbano") { 
	     $sZona = "Urbana";
	   } else {
	     $sZona = "Rural";
	   }   
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(50,$iSetAlText,$sZona,1,1,"L",0);  
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(18,$iSetAlText,"Logradouro:",1,0,"L",0);   
	  
    $sEndereco = @$this->j14_tipo . " " . @$this->j14_nome;
    if(strlen($sEndereco) > 50){
      $sPontos = "...";
    } else {
      $sPontos = "";
    }	  
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(172,$iSetAlText,substr($sEndereco, 0, 50).$sPontos,1,1,"L",0);
	
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(18,$iSetAlText,"Nome/Compl:",1,0,"L",0);   
	  
	  if (isset($this->j13_descr) && !empty($this->j13_descr)) {
	    $sComplemento = $this->j13_descr;
	  }
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(172,$iSetAlText,$sComplemento,1,1,"L",0);    
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(130,$iSetAlText,"Zona do Registro de Imóvel:",1,0,"R",0);   
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(60,$iSetAlText,$this->munic." - ".$this->z01_uf,1,1,"L",0);    
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(56,$iSetAlText,"TERRENO",1,0,"C",0);   
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(10,$iSetAlText,"Lote:","LTB",0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(26,$iSetAlText,$this->it22_loteri,"TBR",0,"L",0);   
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Quadra:","LTB",0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(26,$iSetAlText,$this->it22_quadrari,"TBR",0,"L",0);   
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(60,$iSetAlText,"",1,1,"L",0);   

	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(40,$iSetAlText,"SITUAÇÃO DA QUADRA",1,0,"C",1);
	  $this->objpdf->Cell(92,$iSetAlText,"DIMENSÕES",1,0,"C",1);
	  $this->objpdf->Cell(58,$iSetAlText,"ÁREA(m²)",1,1,"C",1);
	  
	  switch ( $this->it07_descr ) {
	  	
	  	case 'INTERNO':
        $sInterno   = "X";
        $sEncravado = "";
        $sEsquina   = "";
	  	break;

      case 'ENCRAVADO':
        $sInterno   = "";
        $sEncravado = "X";
        $sEsquina   = "";
      break;	  	

      case 'ESQUINA':
        $sInterno   = "";
        $sEncravado = "";
        $sEsquina   = "X";
      break;      
      
	  	default:
	  		$sInterno   = "";
	  		$sEncravado = "";
	  		$sEsquina   = "";
	  	break;
	  }
	  
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,$sEsquina,1,0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(15,$iSetAlText,"Esquina",1,0,"L",0);  
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,$sInterno,1,0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(15,$iSetAlText,"Interno",1,0,"L",0);   
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Frente:",1,0,"L",0); 
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(42,$iSetAlText,db_formatar($this->it05_frente,'f',' ' ,0,'e',3) . 'm',1,0,"L",0); 
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(18,$iSetAlText,"Lado Direito:",1,0,"L",0); 
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(20,$iSetAlText,db_formatar($this->it05_direito,'f',' ' ,0,'e',3).'m',1,0,"L",0);   
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(22,$iSetAlText,"Área Total:",1,0,"L",0); 
	  
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(36,$iSetAlText,"",1,0,"L",0); 	  
	  
    $iGetY = $this->objpdf->GetY();
    $iGetX = $this->objpdf->GetX(); 
    
    $this->objpdf->SetY($iGetY);
    $this->objpdf->SetX($iGetX-26); 
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $nAreaTotal = db_formatar($this->areaterreno+0,'f',' ',' ',' ',4);
	  $this->objpdf->Cell(18,$iSetAlText,$nAreaTotal.'m²',0,1,"L",0); 
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,$sEncravado,1,0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(15,$iSetAlText,"Encravado",1,0,"L",0);  
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(20,$iSetAlText,"",1,0,"C",0);  
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Fundos:",1,0,"L",0); 
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(42,$iSetAlText,db_formatar($this->it05_fundos,'f',' ' ,0,'e',3).'m',1,0,"L",0); 
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(18,$iSetAlText,"Lado Esquerdo:",1,0,"L",0); 
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(20,$iSetAlText,db_formatar($this->it05_esquerdo,'f',' ' ,0,'e',3).'m',1,0,"L",0);   
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(22,$iSetAlText,"Área Transmitida:",1,0,"L",0); 
	  
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(36,$iSetAlText,"",1,0,"L",0); 	  
	  
    $iGetY = $this->objpdf->GetY();
    $iGetX = $this->objpdf->GetX(); 
    
    $this->objpdf->SetY($iGetY);
    $this->objpdf->SetX($iGetX-26);	  
	  
	  $nAreaTransmitida = (count($this->areaterrenomat)==1?db_formatar($this->areatran,'f',' ',' ',' ',4).('m²'):(strlen($this->areaterrenomat[1])>2?$this->areatran:db_formatar($this->areatran,'f',' ',' ',' ',4).('m²')));
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(18,$iSetAlText,$nAreaTransmitida,0,1,"L",0);   

	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell($iSetTamCell,$iSetAlText,"CONSTRUÇÕES, BENFEITORIAS E MELHORAMENTOS",1,1,"C",1); 

    if ( $this->linhasresultcons > 0 ) {
      
      for ( $iInd = 0; $iInd < $this->linhasresultcons; $iInd++ ) {
      
        $aDescr[]     = (strlen($this->arrayit09_codigo[$iInd])>12? substr($this->arrayit09_codigo[$iInd],0,12)
                        ."...":$this->arrayit09_codigo[$iInd]);                        
        $aTipo[]      = substr($this->arrayit10_codigo[$iInd],0,20);
        $aArea[]      = db_formatar($this->arrayit08_area[$iInd],'f',' ',' ',' ',5);
        $aAreaTrans[] = db_formatar($this->arrayit08_areatrans[$iInd],'f',' ',' ',' ',5);
        $aAnoConstr[] = $this->arrayit08_ano[$iInd];
        
        if ( $iInd == 3 ) {
          break;
        }
      }
    }
    
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(30,$iSetAlText,"ESPÉCIE",1,0,"C",0);
	  
    $iGetYIni = $this->objpdf->GetY();
    $iGetXIni = $this->objpdf->GetX();
	  
	  $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
	  $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0); 
	  $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
	  $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);

    $iGetYFim = $this->objpdf->GetY();
    $iGetXFim = $this->objpdf->GetX();	  
	  
    $this->objpdf->SetY($iGetYIni);
    $this->objpdf->SetX($iGetXIni); 	  
	  
    foreach ( $aDescr as $iInd => $sDescr ) {
      $this->objpdf->Cell(22,$iSetAlText,$sDescr,0,0,"C",0);
    }    

    $this->objpdf->SetY($iGetYFim);
    $this->objpdf->SetX($iGetXFim);    
    
    $this->objpdf->Cell(7,$iSetAlText,"","LTR",0,"C",0);        
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Fina",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,0,"C",0);
	  $this->objpdf->Cell(7,$iSetAlText,"","LTR",0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Boa",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,0,"C",0);
	  $this->objpdf->Cell(7,$iSetAlText,"","LTR",0,"C",0);  
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Boa",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,1,"C",0); 

	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(30,$iSetAlText,"Área Total(m²):",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);

    $iGetYIni = $this->objpdf->GetY();
    $iGetXIni = $this->objpdf->GetX();
    
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0); 
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);

    $iGetYFim = $this->objpdf->GetY();
    $iGetXFim = $this->objpdf->GetX();    
    
    $this->objpdf->SetY($iGetYIni);
    $this->objpdf->SetX($iGetXIni);     
    
    foreach ( $aArea as $iInd => $nArea ) {
      $this->objpdf->Cell(22,$iSetAlText,$nArea,0,0,"C",0);
    }    

    $this->objpdf->SetY($iGetYFim);
    $this->objpdf->SetX($iGetXFim);    
    
    $this->objpdf->Cell(7,$iSetAlText,"","LR",0,"C",0);  	  
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Normal",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,0,"C",0);
	  $this->objpdf->Cell(7,$iSetAlText,"","LR",0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Normal",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,0,"C",0);
	  $this->objpdf->Cell(7,$iSetAlText,"","LR",0,"C",0);  
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Normal",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,1,"C",0); 

	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(30,$iSetAlText,"Área Transmitida(m²):",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);

    $iGetYIni = $this->objpdf->GetY();
    $iGetXIni = $this->objpdf->GetX();
    
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0); 
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);

    $iGetYFim = $this->objpdf->GetY();
    $iGetXFim = $this->objpdf->GetX();    
    
    $this->objpdf->SetY($iGetYIni);
    $this->objpdf->SetX($iGetXIni);     
    
    foreach ( $aAreaTrans as $iInd => $nAreaTrans ) {
      $this->objpdf->Cell(22,$iSetAlText,$nAreaTrans,0,0,"C",0);
    }    

    $this->objpdf->SetY($iGetYFim);
    $this->objpdf->SetX($iGetXFim);    
    
    $this->objpdf->Cell(7,$iSetAlText,"","LR",0,"C",0); 	  
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Simples",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,0,"C",0);
	  $this->objpdf->Cell(7,$iSetAlText,"","LR",0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Simples",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,0,"C",0);
	  $this->objpdf->Cell(7,$iSetAlText,"","LR",0,"C",0);  
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Simples",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,1,"C",0); 

	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(30,$iSetAlText,"Ano Construção:",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);

    $iGetYIni = $this->objpdf->GetY();
    $iGetXIni = $this->objpdf->GetX();
    
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0); 
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);

    $iGetY = $this->objpdf->GetY();
    $iGetX = $this->objpdf->GetX();
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->SetFillColor(0);
    $this->objpdf->TextWithRotation($iGetX+4,$iGetY+4,"Alvenária",90,2);   
    $this->objpdf->SetFillColor(235);     
    
    $iGetYFim = $this->objpdf->GetY();
    $iGetXFim = $this->objpdf->GetX();    
    
    $this->objpdf->SetY($iGetYIni);
    $this->objpdf->SetX($iGetXIni);     
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    foreach ( $aAnoConstr as $iInd => $iAnoConstr ) {
      $this->objpdf->Cell(22,$iSetAlText,$iAnoConstr,0,0,"C",0);
    }    

    $this->objpdf->SetY($iGetYFim);
    $this->objpdf->SetX($iGetXFim);    
    
    $this->objpdf->Cell(7,$iSetAlText,"","LR",0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Popular",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,0,"C",0);
	  
    $iGetY = $this->objpdf->GetY();
    $iGetX = $this->objpdf->GetX();
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->SetFillColor(0);
    $this->objpdf->TextWithRotation($iGetX+4,$iGetY+4,"Mista",90,2);   
    $this->objpdf->SetFillColor(235);  
	  
	  $this->objpdf->Cell(7,$iSetAlText,"","LR",0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Popular",1,0,"L",0);

	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,0,"C",0);

    $iGetY = $this->objpdf->GetY();
    $iGetX = $this->objpdf->GetX();
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->SetFillColor(0);
    $this->objpdf->TextWithRotation($iGetX+4,$iGetY+4,"Madeira",90,2);   
    $this->objpdf->SetFillColor(235); 	  
	  
	  $this->objpdf->Cell(7,$iSetAlText,"","LR",0,"C",0);  
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(12,$iSetAlText,"Popular",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(5,$iSetAlText,"",1,1,"C",0);  
  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(23,$iSetAlText,"TABELIONATO",1,0,"C",1);
	  $this->objpdf->Cell(137,$iSetAlText,"VALOR IMÓVEL",1,0,"C",1);
	  $this->objpdf->Cell(30,$iSetAlText,"SECRETÁRIA DA FAZENDA",1,1,"C",1); 
  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(23,$iSetAlText,"","LR",0,"C",0);
	  $this->objpdf->Cell(30,$iSetAlText,"",1,0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(28,$iSetAlText,"Atribuido p/ Contribuinte",1,0,"C",0);
	  $this->objpdf->Cell(24,$iSetAlText,"Atribuido p/ Fazenda",1,0,"C",0);
	  $this->objpdf->Cell(23,$iSetAlText,"Forma d/ Pagamento",1,0,"C",0);
	  $this->objpdf->Cell(23,$iSetAlText,"Avaliado",1,0,"C",0);
	  $this->objpdf->Cell(9,$iSetAlText,"Aliq",1,0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(30,$iSetAlText,"","LR",1,"C",0);
  
    $iGetYIni = $this->objpdf->GetY();
    $iGetXIni = $this->objpdf->GetX(); 	  

	  $nTotalAvaliado = 0;
	  $lExibeMsg      = false;
	   
	  if( count($this->aDadosFormasPgto) > 0 ){
	   	
	    foreach ( $this->aDadosFormasPgto as $iInd => $aDadosFormas ) {
	     	
	      if ( $iInd <= 3 ) {
	        
	        $nTotalAvaliado += $aDadosFormas['Valor'];

			    $this->objpdf->SetFont('Arial','',$iSetFontText);
			    $this->objpdf->Cell(28,$iSetAlText,"",0,0,"C",0);
			    
			    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
			    $this->objpdf->Cell(30,$iSetAlText,"",0,0,"L",0);
			    
			    $this->objpdf->SetFont('Arial','',$iSetFontText);
			    $this->objpdf->Cell(28,$iSetAlText,"",0,0,"L",0);
			    $this->objpdf->Cell(19,$iSetAlText,"",0,0,"L",0);
			    
			    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
			    $this->objpdf->Cell(23,$iSetAlText,$aDadosFormas['Descricao'],0,0,"L",0);
			    
			    $this->objpdf->SetFont('Arial','',$iSetFontText);
			    $this->objpdf->Cell(23,$iSetAlText,db_formatar($aDadosFormas['Valor'],'f'),0,0,"L",0);
			    $this->objpdf->Cell(9,$iSetAlText,$aDadosFormas['Aliquota']."%",0,0,"R",0);
			    $this->objpdf->Cell(30,$iSetAlText,"",0,1,"L",0);          	
	          
	      } else {
	        $lExibeMsg = true;
	      }
	    }
	  } 	  
	  
    $this->objpdf->SetY($iGetYIni);
    $this->objpdf->SetX($iGetXIni);       
   
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(23,$iSetAlText,"","LR",0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(30,$iSetAlText,"Terreno:",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(28,$iSetAlText,db_formatar($this->it01_valorterreno,'f'),1,0,"L",0);
	  $this->objpdf->Cell(24,$iSetAlText,db_formatar($this->it14_valoravalter,'f'),1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(23,$iSetAlText,"",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(23,$iSetAlText,"",1,0,"L",0);
	  $this->objpdf->Cell(9,$iSetAlText,"",1,0,"L",0);
	  $this->objpdf->Cell(30,$iSetAlText,"","LR",1,"L",0);  

	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(23,$iSetAlText,"","LR",0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(30,$iSetAlText,"Construções e Benfeitorias:",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(28,$iSetAlText,db_formatar($this->it01_valorconstr,'f'),1,0,"L",0);
	  $this->objpdf->Cell(24,$iSetAlText,db_formatar($this->it14_valoravalconstr,'f'),1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(23,$iSetAlText,"",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(23,$iSetAlText,"",1,0,"L",0);
	  $this->objpdf->Cell(9,$iSetAlText,"",1,0,"L",0);
	  $this->objpdf->Cell(30,$iSetAlText,"","LR",1,"L",0);  

	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(23,$iSetAlText,"","LR",0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(30,$iSetAlText,"Total:",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(28,$iSetAlText,db_formatar($this->it01_valortransacao,'f'),1,0,"L",0);
	  $this->objpdf->Cell(24,$iSetAlText,db_formatar($this->it14_valoraval,'f'),1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(23,$iSetAlText,"",1,0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(23,$iSetAlText,"",1,0,"L",0);
	  $this->objpdf->Cell(9,$iSetAlText,"",1,0,"L",0);
	  $this->objpdf->Cell(30,$iSetAlText,"","LR",1,"L",0);  

	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(23,$iSetAlText,"","LBR",0,"C",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(30,$iSetAlText,"","LBT",0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(28,$iSetAlText,"","BT",0,"L",0);
	  $this->objpdf->Cell(24,$iSetAlText,"","BTR",0,"L",0);
	  
	  $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	  $this->objpdf->Cell(23,$iSetAlText,"TOTAL:",1,0,"L",0);
	  
	  $iGetYIni = $this->objpdf->GetY();
    $iGetXIni = $this->objpdf->GetX();
	  
	  $this->objpdf->SetFont('Arial','',$iSetFontText);
	  $this->objpdf->Cell(15,$iSetAlText,"","TB",0,"L",0);
	  
    $iGetYFim = $this->objpdf->GetY();
    $iGetXFim = $this->objpdf->GetX();	  
	  
    $this->objpdf->SetY($iGetYIni);
    $this->objpdf->SetX($iGetXIni+2);	  
	  
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(15,$iSetAlText,db_formatar($nTotalAvaliado,'f'),0,0,"L",0);	  

    $this->objpdf->SetY($iGetYFim);
    $this->objpdf->SetX($iGetXFim);    
    
	  $this->objpdf->Cell(17,$iSetAlText,"","TB",0,"L",0);
	  $this->objpdf->Cell(30,$iSetAlText,"","LBR",1,"L",0);  
  
  } else {
  	
  	// Modelo Guia Itbi Rural
  	
    $iGetY = $this->objpdf->GetY();
    $iGetX = $this->objpdf->GetX(); 
    
    $this->objpdf->SetY($iGetY+14);
    $this->objpdf->SetX($iGetX); 
    
    $this->objpdf->Cell($iSetTamCell,$iSetAlText,"",0,1,"C",0); 
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(18,$iSetAlText,"Transmitente:",1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(172,$iSetAlText,$this->z01_nome.$this->outrostransmitentes,1,1,"L",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(80,$iSetAlText,"",1,0,"L",0);  
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(18,$iSetAlText,"CPF ou CGC:",1,0,"L",0);  
    
     if (isset($this->z01_cgccpf) && !empty($this->z01_cgccpf)) {
      
       if ( trim(strlen($this->z01_cgccpf)) <= 11 ) {
         $sCgcCpfTrans = db_formatar(trim($this->z01_cgccpf),'cpf');
       } else {
         $sCgcCpfTrans = db_formatar(trim($this->z01_cgccpf),'cnpj');
       }   
     } 
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(32,$iSetAlText,$sCgcCpfTrans,1,0,"L",0); 
  
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(10,$iSetAlText,"Zona:",1,0,"L",0); 
    
     if ($this->tipoitbi == "urbano") { 
       $sZona = "Urbana";
     } else {
       $sZona = "Rural";
     }   
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(50,$iSetAlText,$sZona,1,1,"L",0);  
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(18,$iSetAlText,"Logradouro:",1,0,"L",0);   
    
    $sEndereco = @$this->j14_tipo . " " . @$this->j14_nome;
    if(strlen($sEndereco) > 50){
      $sPontos = "...";
    } else {
      $sPontos = "";
    }   
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(172,$iSetAlText,substr($sEndereco, 0, 50).$sPontos,1,1,"L",0);
  
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(18,$iSetAlText,"Nome/Compl:",1,0,"L",0);   
    
    if (isset($this->j13_descr) && !empty($this->j13_descr)) {
      $sComplemento = $this->j13_descr;
    }
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(172,$iSetAlText,$sComplemento,1,1,"L",0);    
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(130,$iSetAlText,"Zona do Registro de Imóvel:",1,0,"R",0);   
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(60,$iSetAlText,$this->munic." - ".$this->z01_uf,1,1,"L",0);    
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(94,$iSetAlText,"TERRA NUA",1,0,"C",0);  

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(94,$iSetAlText,"",1,1,"C",0);    

    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(46,$iSetAlText,"DIMENSÕES(m)",1,0,"C",0);  

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(46,$iSetAlText,"ÁREA",1,0,"C",0);    
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(94,$iSetAlText,"AS TERRAS FAZEM FRENTE A VIA PÚBLICA?",1,1,"C",0);     

    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(18,$iSetAlText,"Frente:",1,0,"L",0);  

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,db_formatar($this->it18_frente,'f',' ' ,0,'e',3).'m',1,0,"L",0);    
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(20,$iSetAlText,"Área Total:",1,0,"L",0);  

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(26,$iSetAlText,db_formatar($this->areaterreno+0,'f',' ',' ',' ',6).'ha',1,0,"L",0);    
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);
    
    if ( trim($this->lFrenteVia) == 'Sim' ) {
      $sFrenteViaSim = "X";   	
    } else {
      $sFrenteViaNao = "X";    	
    }

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(5,$iSetAlText,$sFrenteViaSim,1,0,"C",0);

    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(14,$iSetAlText,"SIM",1,0,"L",0);

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(5,$iSetAlText,$sFrenteViaNao,1,0,"C",0);

    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(14,$iSetAlText,"NÃO",1,0,"C",0);

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(18,$iSetAlText,$this->it18_distcidade,1,0,"R",0);     
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(38,$iSetAlText,"DISTAM - KM DA VIA PÚBLICA",1,1,"L",0);    

    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(18,$iSetAlText,"Fundos:",1,0,"L",0);  

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,db_formatar($this->it18_fundos,'f',' ' ,0,'e',3).'m',1,0,"L",0);    
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(20,$iSetAlText,"Área Transmitida:",1,0,"L",0);  

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $nAreaTrans = (count($this->areaterrenomat)==1?db_formatar($this->areatran,'f',' ',' ',' ',6).'ha':(strlen($this->areaterrenomat[1])>2?$this->areatran:db_formatar($this->areatran,'f',' ',' ',' ',6).'ha'));
    $this->objpdf->Cell(26,$iSetAlText,$nAreaTrans,1,0,"L",0);    
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(94,$iSetAlText,"CONSTRUÇÕES, BENFEITORIAS E MELHORAMENTOS",1,1,"C",0);     

    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(46,$iSetAlText,"TERRA(ha)",1,0,"C",0);  
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(46,$iSetAlText,"DISTRIBUIÇÃO DAS TERRAS(ha)",1,0,"C",0);   
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
    
    if ( $this->linhasresultcons > 0 ) {
      
      for ( $iInd = 0; $iInd < $this->linhasresultcons; $iInd++ ) {
      
        $aDescr[]     = (strlen($this->arrayit09_codigo[$iInd])>12? substr($this->arrayit09_codigo[$iInd],0,12)
                        ."...":$this->arrayit09_codigo[$iInd]);                        
        $aTipo[]      = substr($this->arrayit10_codigo[$iInd],0,20);
        $aArea[]      = db_formatar($this->arrayit08_area[$iInd],'f',' ',' ',' ',5);
        $aAreaTrans[] = db_formatar($this->arrayit08_areatrans[$iInd],'f',' ',' ',' ',5);
        $aAnoConstr[] = $this->arrayit08_ano[$iInd];
        
        if ( $iInd >= 2 ) {
          break;
        }
      }
    }     
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(28,$iSetAlText,"ESPÉCIE",1,0,"C",0);  

    $iGetYEspecie = $this->objpdf->GetY();
    $iGetXEspecie = $this->objpdf->GetX();
        
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"C",0);
    $this->objpdf->Cell(22,$iSetAlText,"",1,1,"C",0);
    
    $iGetYTerra = $this->objpdf->GetY();
    $iGetXTerra = $this->objpdf->GetX();  

    $this->objpdf->SetY($iGetYEspecie);
    $this->objpdf->SetX($iGetXEspecie);  
     
    foreach ( $aDescr as $iInd => $sDescr ) {
      $this->objpdf->Cell(22,$iSetAlText,$sDescr,0,0,"C",0);
    }    
    
    
//    echo "<pre>";
//      print_r($aDescr);
//      print_r($aArea);
//      print_r($aAreaTrans);
//      print_r($aAnoConstr);
//    echo "</pre>";
    
    $this->objpdf->SetY($iGetYTerra);
    $this->objpdf->SetX($iGetXTerra);    
    
    $aEspecie[0] = "Área Total(m²):";
    $aEspecie[1] = "Área Transmitida(m²):";
    $aEspecie[2] = "Ano Construção:";
    $aEspecie[3] = "Tipo Construção:";
    
    for ( $iInd = 0; $iInd < 4; $iInd++ ) {
    	
	    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	    $this->objpdf->Cell(26,$iSetAlText,"",1,0,"L",0); 
	    
	    $this->objpdf->SetFont('Arial','B',$iSetFontText);
	    $this->objpdf->Cell(20,$iSetAlText,"",1,0,"L",0);      
	    $this->objpdf->SetFont('Arial','',$iSetFontText);
	    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
	    
	    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	    $this->objpdf->Cell(26,$iSetAlText,"",1,0,"L",0);  
	
	    $this->objpdf->SetFont('Arial','B',$iSetFontText);
	    $this->objpdf->Cell(20,$iSetAlText,"",1,0,"L",0);   
	    
	    $this->objpdf->SetFont('Arial','',$iSetFontText);
	    $this->objpdf->Cell(2,$iSetAlText,"","LR",0,"C",0);    
	    
	    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
	    $this->objpdf->Cell(28,$iSetAlText,$aEspecie[$iInd],1,0,"L",0);     
	    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"L",0);	
	    $this->objpdf->Cell(22,$iSetAlText,"",1,0,"L",0);
	    $this->objpdf->Cell(22,$iSetAlText,"",1,1,"L",0);    
    }
    
    $iGetY = $this->objpdf->GetY();
    $iGetX = $this->objpdf->GetX(); 
    
    if( count($this->aDadosRuralCaractDist) > 0 ){
      
      $this->objpdf->SetY($iGetYTerra);
      foreach ( $this->aDadosRuralCaractDist as $iInd => $aDadosDist ){
        
        $this->objpdf->SetX($iGetXTerra);      	
      	
        $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
        $this->objpdf->Cell(26,$iSetAlText,$aDadosDist['Descricao'],0,0,"L",0);  
    
        $this->objpdf->SetFont('Arial','',$iSetFontText);
        $this->objpdf->Cell(20,$iSetAlText,$aDadosDist['Valor']."%",0,0,"L",0); 

        $this->objpdf->SetFont('Arial','',$iSetFontText);
        $this->objpdf->Cell(2,$iSetAlText,"","LR",1,"C",0);
        if ($iInd == 0) {
        	
          $iGetYDistrTerra = $this->objpdf->GetY();
          $iGetXDistrTerra = $this->objpdf->GetX();         	
        }
        
        if ( $iInd >= 4) {
          break;
        }
      }
    }    
    
    if( count($this->aDadosRuralCaractUtil) > 0 ){
      
    	$this->objpdf->SetY($iGetYTerra);
      foreach ( $this->aDadosRuralCaractUtil as $iInd => $aDadosUtil ){  

      	$this->objpdf->SetX($iGetXTerra+48);
      	
        $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
        $this->objpdf->Cell(26,$iSetAlText,$aDadosUtil['Descricao'],0,0,"L",0);  
      
        $this->objpdf->SetFont('Arial','',$iSetFontText);
        $this->objpdf->Cell(20,$iSetAlText,$aDadosUtil['Valor']."%",0,0,"L",0);     
         
        $this->objpdf->SetFont('Arial','',$iSetFontText);
        $this->objpdf->Cell(2,$iSetAlText,"","LR",1,"C",0);        
        if ( $iInd == 4) {
          break;
        }
      }
    }  

    $this->objpdf->SetY($iGetYEspecie+6);
    $this->objpdf->SetX($iGetXEspecie);
    foreach ( $aArea as $iInd => $nArea ) {
    	$this->objpdf->SetFont('Arial','',$iSetFontText);
      $this->objpdf->Cell(22,$iSetAlText,$nArea,0,0,"L",0);
    } 
    
    $this->objpdf->SetY($iGetYEspecie+12);
    $this->objpdf->SetX($iGetXEspecie);
    foreach ( $aAreaTrans as $iInd => $nAreaTrans ) {
      $this->objpdf->SetFont('Arial','',$iSetFontText);
      $this->objpdf->Cell(22,$iSetAlText,$nAreaTrans,0,0,"L",0);
    }    
    
    $this->objpdf->SetY($iGetYEspecie+18);
    $this->objpdf->SetX($iGetXEspecie);
    foreach ( $aAnoConstr as $iInd => $iAnoConstr ) {
      $this->objpdf->SetFont('Arial','',$iSetFontText);
      $this->objpdf->Cell(22,$iSetAlText,$iAnoConstr,0,0,"L",0);
    }  

    $this->objpdf->SetY($iGetYEspecie+24);
    $this->objpdf->SetX($iGetXEspecie);
    foreach ( $aTipo as $iInd => $sTipo ) {
      $this->objpdf->SetFont('Arial','',$iSetFontText);
      $this->objpdf->Cell(22,$iSetAlText,$sTipo,0,0,"L",0);
    }    
    
    $this->objpdf->SetY($iGetY);
    $this->objpdf->SetX($iGetX); 
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(28,$iSetAlText,"TABELIONATO",1,0,"C",1);
    $this->objpdf->Cell(132,$iSetAlText,"VALOR IMÓVEL",1,0,"C",1);
    $this->objpdf->Cell(30,$iSetAlText,"SECRETÁRIA DA FAZENDA",1,1,"C",1); 
  
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,"","LR",0,"C",0);
    $this->objpdf->Cell(30,$iSetAlText,"",1,0,"C",0);
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(28,$iSetAlText,"Atribuido p/ Contribuinte",1,0,"C",0);
    $this->objpdf->Cell(24,$iSetAlText,"Atribuido p/ Fazenda",1,0,"C",0);
    $this->objpdf->Cell(23,$iSetAlText,"Forma d/ Pagamento",1,0,"C",0);
    $this->objpdf->Cell(15,$iSetAlText,"Avaliado",1,0,"C",0);
    $this->objpdf->Cell(12,$iSetAlText,"Aliquota",1,0,"C",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(30,$iSetAlText,"","LR",1,"C",0);
  
    $iGetYIni = $this->objpdf->GetY();
    $iGetXIni = $this->objpdf->GetX(); 

    $nTotalAvaliado = 0;
    $lExibeMsg      = false;
     
    if( count($this->aDadosFormasPgto) > 0 ){
      
      foreach ( $this->aDadosFormasPgto as $iInd => $aDadosFormas ) {
        
        if ( $iInd <= 3 ) {
          
          $nTotalAvaliado += $aDadosFormas['Valor'];

          $this->objpdf->SetFont('Arial','',$iSetFontText);
          $this->objpdf->Cell(28,$iSetAlText,"",0,0,"C",0);
          
          $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
          $this->objpdf->Cell(30,$iSetAlText,"",0,0,"L",0);
          
          $this->objpdf->SetFont('Arial','',$iSetFontText);
          $this->objpdf->Cell(28,$iSetAlText,"",0,0,"L",0);
          $this->objpdf->Cell(24,$iSetAlText,"",0,0,"L",0);
          
          $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
          $this->objpdf->Cell(18,$iSetAlText,$aDadosFormas['Descricao'],0,0,"L",0);
          
          $this->objpdf->SetFont('Arial','',$iSetFontText);
          $this->objpdf->Cell(20,$iSetAlText,db_formatar($aDadosFormas['Valor'],'f'),0,0,"L",0);
          $this->objpdf->Cell(12,$iSetAlText,$aDadosFormas['Aliquota']."%",0,0,"R",0);
          $this->objpdf->Cell(30,$iSetAlText,"",0,1,"L",0);           
            
        } else {
          $lExibeMsg = true;
        }
      }
    }     
    
    $this->objpdf->SetY($iGetYIni);
    $this->objpdf->SetX($iGetXIni);       
   
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,"","LR",0,"C",0);
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(30,$iSetAlText,"Terreno:",1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,db_formatar($this->it01_valorterreno,'f'),1,0,"L",0);
    $this->objpdf->Cell(24,$iSetAlText,db_formatar($this->it14_valoravalter,'f'),1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(23,$iSetAlText,"",1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(15,$iSetAlText,"",1,0,"L",0);
    $this->objpdf->Cell(12,$iSetAlText,"",1,0,"L",0);
    $this->objpdf->Cell(30,$iSetAlText,"","LR",1,"L",0);  

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,"","LR",0,"C",0);
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(30,$iSetAlText,"Construções e Benfeitorias:",1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,db_formatar($this->it01_valorconstr,'f'),1,0,"L",0);
    $this->objpdf->Cell(24,$iSetAlText,db_formatar($this->it14_valoravalconstr,'f'),1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(23,$iSetAlText,"",1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(15,$iSetAlText,"",1,0,"L",0);
    $this->objpdf->Cell(12,$iSetAlText,"",1,0,"L",0);
    $this->objpdf->Cell(30,$iSetAlText,"","LR",1,"L",0);  

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,"","LR",0,"C",0);
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(30,$iSetAlText,"Total:",1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,db_formatar($this->it01_valortransacao,'f'),1,0,"L",0);
    $this->objpdf->Cell(24,$iSetAlText,db_formatar($this->it14_valoraval,'f'),1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(23,$iSetAlText,"",1,0,"L",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(15,$iSetAlText,"",1,0,"L",0);
    $this->objpdf->Cell(12,$iSetAlText,"",1,0,"L",0);
    $this->objpdf->Cell(30,$iSetAlText,"","LR",1,"L",0);  

    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,"","LBR",0,"C",0);
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(30,$iSetAlText,"","LBT",0,"L",0);
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(28,$iSetAlText,"","BT",0,"L",0);
    $this->objpdf->Cell(24,$iSetAlText,"","BTR",0,"L",0);
    
    $this->objpdf->SetFont('Arial','B',$iSetFontLabel);
    $this->objpdf->Cell(23,$iSetAlText,"TOTAL:",1,0,"L",0);
    
    $iGetYIni = $this->objpdf->GetY();
    $iGetXIni = $this->objpdf->GetX();
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(15,$iSetAlText,"",1,0,"L",0);
    
    $iGetYFim = $this->objpdf->GetY();
    $iGetXFim = $this->objpdf->GetX();    
    
    $this->objpdf->SetY($iGetYIni);
    $this->objpdf->SetX($iGetXIni-5);   
    
    $this->objpdf->SetFont('Arial','',$iSetFontText);
    $this->objpdf->Cell(15,$iSetAlText,db_formatar($nTotalAvaliado,'f'),0,0,"L",0);   

    $this->objpdf->SetY($iGetYFim);
    $this->objpdf->SetX($iGetXFim);    
    
    $this->objpdf->Cell(12,$iSetAlText,"",1,0,"L",0);
    $this->objpdf->Cell(30,$iSetAlText,"","LBR",1,"L",0);  
      	
  }
  
  $this->objpdf->ln(2);
  $this->objpdf->SetFont('Arial','B',10);
  $this->objpdf->SetX(140);  
  
  if ($this->it14_valorpaga == 0) {
    $this->objpdf->Cell(60,$iSetAlText,'Valor a Pagar : I S E N T O',0,1,"C",0);
  } else {
    $this->objpdf->Cell(60,$iSetAlText,'Valor a Pagar : R$ '.db_formatar(($this->it14_valorpaga + $this->tx_banc),'f'),0,1,"L",0);
  }
   
  $pos = $this->objpdf->GetY();
  $this->objpdf->SetFillColor(0,0,0);
   
  if ($this->lLiberado) {
     
    if ($this->it14_valorpaga > 0) {
      
      $this->objpdf->Text(14,$pos-2,$this->linha_digitavel);
      $this->objpdf->int25(10,$pos,$this->codigo_barras,15,0.341);
    }
  }
   
  if (!$this->lLiberado) {
       
    $this->objpdf->SetFont('Arial','B',78);
    $this->objpdf->SetFillColor(178);
    $this->objpdf->TextWithRotation(12,$pos+1,"NÃO LIBERADA",20,0);   
    $this->objpdf->SetFillColor(235);
  }
  
}
?>