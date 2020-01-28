<?php

global $contapagina;
$contapagina=1;
	
////modelo de comprovante de ajuda de custo
 	
$this->objpdf->AliasNbPages();
$this->objpdf->settopmargin(1);
$this->objpdf->SetAutoPageBreak('on',0);
$this->objpdf->line(2,148.5,208,148.5);
	
$xlin        = 21;
$xcol        = 4;
$comeco      = 0;
$passada     = 0;
$observacao  = false;
$cont_obs    = 0;
$quant_itens = 0;
$obs         = null;
$iVias       = 0;
$iPag_a      = false;
$iPag_b      = false;
$comeco      = 0;
$passou      = 0;

$total       = 0;
$contaobs    = 0;

//verifica se algum item tem observação
for ($j = 0 ;$j < $this->linhasdositens; $j++) {
  
  $obs = trim(pg_result($this->recorddositens, $j, $this->robsdositens));
  if ($obs != '') {
    $cont_obs++;
  }
  
}
if ((int)$cont_obs > 0){

  // MAXIMO QUE PODER SER IMPRESSO 
  $quant_itens = 5;
  $qReg        = $this->linhasdositens;
  $passou      = 0;
  for ($i = 0; $i < $qReg; $i++) {
       
    $contaobs++;
    if ((($contaobs == $quant_itens) || ($passou == 0 && $qReg > $quant_itens)) && ($qReg > $quant_itens)) {
          
      $iVias++;
      $total   += $contaobs;
      $contaobs = 0;
      $iPag_a   = true;
      $passou   = 1;
       
    }
    if (isset($total) && $qReg < $quant_itens) {
           
      $iVias++;   
      $contaobs = 0;
      $iPag_b   = true;        
      break;
       
    }
   
  }
  
  if ($iPag_a == true && $iPag_b == true) {
    $iVias = $iVias;
  } elseif ($iPag_a == true && $iPag_b == false) {
    $iVias = $iVias;
  } elseif ($iPag_a == false && $iPag_b == true) {
    $iVias = 1;
  }

} else {
  
  //14 itens por folha se não conter observação em nenhum item
  $quant_itens = 8;
  $qReg        = $this->linhasdositens;
  for ($i = 0; $i < $qReg; $i++){
       
    $contaobs++;
    if ((($contaobs == $quant_itens) || ($passou == 0 && $qReg >$quant_itens)) && ($qReg >$quant_itens)){   
          
      $iVias++;
      $total   += $contaobs;
      $contaobs = 0;
      $iPag_a   = true;
      $passou   = 1;
       
    }
    if (isset($total) && $qReg < $quant_itens){
           
      $iVias++;
      $contaobs = 0;
      $iPag_b   = true;
      break;
       
    }
    
  }  

  if ($iPag_a == true && $iPag_b == true) {
    $iVias = $iVias;
  } elseif ($iPag_a == true && $iPag_b == false) {
    $iVias = $iVias;
  } elseif ($iPag_a == false && $iPag_b == true) {
    $iVias = 1;
  }

}

$iVias = $iVias * 2;


function printHead($oPdf, $iLin, $iCol, $oImp) {
  
  $oPdf->setfillcolor(245);
  $oPdf->roundedrect($iCol - 2, $iLin - 18, 206, 145, 2, 'DF',' 1234');
  $oPdf->setfillcolor(255, 255, 255);
  $oPdf->Setfont('Arial', 'B', 8);
  $oPdf->text(110, $iLin - 13, 'RECIBO DE AJUDA DE CUSTO N'.chr(176).' '.$oImp->Rnumero);
  $oPdf->text(110, $iLin - 9, 'PEDIDO TFD N'.chr(176).' '.$oImp->iRtf01_i_codigo);
  
	$oPdf->Image('imagens/files/logo_boleto.png', 10, $iLin - 16, 12);
	$oPdf->Setfont('Arial','B',9);
	$oPdf->text(30, $iLin - 14, $oImp->prefeitura);
	$oPdf->Setfont('Arial','',7);
	$oPdf->text(30, $iLin - 11, $oImp->enderpref);
	$oPdf->text(30, $iLin - 8, $oImp->municpref);
	$oPdf->text(30, $iLin - 4, $oImp->telefpref);
	$oPdf->text(30, $iLin - 1, $oImp->emailpref);
  
}

function printRectangleRetiradoPor ($oPdf, $iLin, $iCol, $oImp) {
  
  $iLin += 32;
  $oPdf->Roundedrect($iCol, $iLin + 1, $iCol + 98 + 100, 16, 2, 'DF', '1234');
	$oPdf->Setfont('Arial' ,'b', 8);
	$oPdf->text($iCol + 2, $iLin + 6, 'Retirado por:');
	$oPdf->Setfont('Arial', '', 8);
	$oPdf->text($iCol + 21, $iLin + 6, $oImp->Rretirou.' - '.$oImp->Rcgsretirou);
	$oPdf->Setfont('Arial','b',8);
	$oPdf->text($iCol + 2, $iLin + 10, 'CPF:');
	$oPdf->Setfont('Arial','',8);
	$oPdf->text($iCol + 21, $iLin + 10, $oImp->Rcpf);
	$oPdf->Setfont('Arial','b',8);
	$oPdf->text($iCol + 110, $iLin + 6, 'Hora: ');
	$oPdf->Setfont('Arial','',8);
	$oPdf->text($iCol + 120, $iLin + 6, '        '.$oImp->Rhoratfd);
	$oPdf->Setfont('Arial', 'b', 8);
	$oPdf->text($iCol + 110, $iLin + 10, 'Data: ');
	$oPdf->Setfont('Arial', '', 8);
	$oPdf->text($iCol + 120, $iLin + 10, '        '.db_formatar($oImp->Rdatatfd,"d"));
	  
	$oPdf->Setfont('Arial', 'b', 8);
  $oPdf->text($iCol + 2, $iLin + 14, 'Identidade:');
  $oPdf->Setfont('Arial', '', 8);
  $oPdf->text($iCol + 21, $iLin + 14, $oImp->Ridentidade);
  $oPdf->Setfont('Arial', 'b', 8);
  $oPdf->text($iCol + 110, $iLin + 14, 'Atendente:');
  $oPdf->Setfont('Arial', '', 8);
  $oPdf->text($iCol + 125, $iLin + 14, '  '.$oImp->Ratendente);

}


function printRectanglePaciente($oPdf, $iY, $iX, $oImp) {  

  $iY -= 11;
  $oPdf->Roundedrect(4, $iY + 11, 202, 32, 2, 'DF', '1234');  
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(6, $iY + 16, 'PACIENTE');
  $oPdf->Setfont('arial', 'B', 8);-
  $oPdf->text(13, $iY + 20, 'Paciente');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(26, $iY + 20, ': '.$oImp->sRNomePaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(101, $iY + 20, 'CGS ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(109, $iY + 20, ': '.$oImp->iRsCnsPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(137, $iY + 20, 'Data Nascimento');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(162, $iY + 20, ': '.db_formatar($oImp->dRNascPaciente, 'd'));
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(20, $iY + 24, 'RG ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(26, $iY + 24, ': '.$oImp->sRIdentPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(102, $iY + 24, 'CPF ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(109, $iY + 24, ': '.$oImp->sRCpfPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(144, $iY + 24, 'Cartão SUS ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(162, $iY + 24, ': '.$oImp->sRCartSusPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(6, $iY + 28, 'Nome da Mãe');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(26, $iY + 28, ': '.substr($oImp->sRMaePaciente, 0, 32));
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(101, $iY + 28, 'Sexo ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(109, $iY + 28, ': '.$oImp->sRSexoPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(11, $iY + 32, 'Endereço');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(26, $iY + 32, ': '.substr($oImp->sREnderecoPaciente, 0, 36));
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(97, $iY + 32, 'Número');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(109, $iY + 32, ': '.$oImp->sRNumeroPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(141, $iY + 32, 'Complemento ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(162, $iY + 32, ': '.substr($oImp->sRComplPaciente, 0, 23));
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(15, $iY + 36, 'Bairro ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(26, $iY + 36, ': '.substr($oImp->sRBairroPaciente, 0, 23));
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(95, $iY + 36, 'Município ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(109, $iY + 36, ': '.$oImp->sRMunicPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(155, $iY + 36, 'UF ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(162, $iY + 36, ': '.$oImp->sRUfPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(17, $iY + 40, 'CEP ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(26, $iY + 40, ': '.$oImp->sRCepPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(95, $iY + 40, 'Telefone ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(109, $iY + 40, ': '.$oImp->sRTelPaciente);
  $oPdf->Setfont('arial', 'B', 8);
  $oPdf->text(150, $iY + 40, 'Celular ');
  $oPdf->Setfont('arial', '', 8);
  $oPdf->text(162, $iY + 40, ': '.$oImp->sRCelPaciente);

}

function printItens($oPdf, $iLin, $iCol, $oImp, $comeco, $quant_itens, $passada) {
  
  $iLin += 8;
  $oPdf->Roundedrect($iCol, $iLin + 42, 202, 55, 2, 'DF', '1234');
  $oPdf->Setfont('Arial', 'b', 8);
  $oPdf->text($iCol + 2, $iLin + 46, 'CGS');
  $oPdf->text($iCol + 15, $iLin + 46, 'BENEFICIADO');
  $oPdf->text($iCol + 80, $iLin + 46, 'AJUDA');
  $oPdf->text($iCol + 180, $iLin + 46, 'VALOR');
  $oPdf->Setfont('Arial','',8);
  $oPdf->sety($iLin+47);
	  
	$maiscol      = 0;
	$cont         = 0;
	$yy 	        = $oPdf->gety();
  $oImp->rtotal = 0;
      
  for($ii = $comeco;$ii < $oImp->linhasdositens ;$ii++) {

    $cont++;
    $oImp->rtotal += pg_result($oImp->recorddositens,$ii,$oImp->rvalor);
    $oPdf->setx($iCol+3+$maiscol);
    
    $oPdf->cell(10 ,5, trim(pg_result($oImp->recorddositens,$ii,$oImp->rcodcgs))                       ,0,0,"L",0);
    $oPdf->cell(66 ,5, substr(trim(pg_result($oImp->recorddositens,$ii,$oImp->rbeneficiado)),0,50)     ,0,0,"L",0);
    $oPdf->cell(100,5, pg_result($oImp->recorddositens,$ii, 'tf12_descricao')                          ,0,0,"L",0);
    if (trim(pg_result($oImp->recorddositens,$ii,$oImp->robsdositens)) != '') {

      $oPdf->cell(15, 4, number_format(pg_result($oImp->recorddositens,$ii,$oImp->rvalor), 2, ',', '.'),0,1,"R",0);
      $obsitens=substr(trim(pg_result($oImp->recorddositens,$ii,$oImp->robsdositens)),0,110);
      $oPdf->Setfont('Arial', '', 6);
      $oPdf->multicell(200, 4,$obsitens);
      $oPdf->Setfont('Arial', '', 8);
	  
    } else {
      $oPdf->cell(15,5,number_format(pg_result($oImp->recorddositens,$ii,$oImp->rvalor), 2, ',', '.'),0,1,"R",0);
    }
    
    if ($quant_itens==6 && (trim(pg_result($oImp->recorddositens,$ii,$oImp->robsdositens)) == '')){

      $obsitens="";
      $oPdf->multicell(180,4,"");
     
    }
       
    if ((($ii+1) % $quant_itens ) == 0 && $ii > 0 && $passada == 0){
				 
      $maiscol = 0;
			$passada ++;
			$comeco  = $ii+1;
			break;
	     
    }

	  if(($ii+1) == $oImp->linhasdositens){
	     
	    $comeco  = 0;
	    $passada = 0;
	    break;  
	     
	  }

	  if ($cont == $quant_itens && $passada > 0){
				 
	    $maiscol = 0;
			$oPdf->sety($yy);
			$comeco  = $ii+1;
			$passada = 0;
			break;
	     
	  }
              
  }
  $iLin -= 7;
	$oPdf->Setfont('Arial','b',8);
	$oPdf->cell(173,5,"Total: ",0,0,"R",0);
  $oPdf->cell(15,5, number_format($oImp->rtotal, 2, ',', '.'),0,0,"R",0);
	$oPdf->Roundedrect($iCol,$iLin+105,$iCol+105,20,2,'DF','1234');
	$oPdf->Setfont('Arial','b',8);
	$oPdf->text($iCol+2,$iLin+110,'TOTAL: ');
	$oPdf->Setfont('Arial','b',8);
	$oPdf->text($iCol+134,$iLin+120,strtoupper($oImp->municpref).', '.substr($oImp->emissao,8,2).' DE '.strtoupper(db_mes(substr($oImp->emissao,5,2))).' DE '.substr($oImp->emissao,0,4).'.');
	$oPdf->line($iCol+130,$iLin+110,$iCol+195,$iLin+110);
	$oPdf->text($iCol+152,$iLin+114,'RECEBEDOR');
	$oPdf->Setfont('Arial','',8);
	$oPdf->sety($iLin+112);
	$oPdf->setx($iCol+1);
  $oPdf->multicell(107,5,"R$  ". number_format($oImp->rtotal, 2, ',', '.') ." - ". db_extenso($oImp->rtotal,true),0,"L");
  
}

for ($i = 0;$i < $iVias; $i++){
	  
  if (($i % 2 ) == 0) {
	   $this->objpdf->AddPage();
  }

	if ($this->linhasdositens <= $quant_itens) {
    $comeco = 0;
  }
  
	printHead($this->objpdf, $xlin, $xcol, $this);
  printRectanglePaciente($this->objpdf, $xlin, $xcol, $this);
  printRectangleRetiradoPor($this->objpdf, $xlin, $xcol, $this);  
  printItens($this->objpdf, $xlin, $xcol, $this, $comeco, $quant_itens, $passada);
	  
	if (($i % 2 ) == 0)
	  $xlin = 169;
	else
	  $xlin = 20;
}

?>