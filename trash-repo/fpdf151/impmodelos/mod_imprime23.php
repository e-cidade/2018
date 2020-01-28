<?php

  $cldb_config = new cl_db_config;
  
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',12);
	$coluna = 44;
	$linha = 23;
	$this->objpdf->SetLineWidth(1);
	$this->objpdf->RoundedRect(37,0.2,137,195,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
	$this->objpdf->roundedrect(39,2,133,191,2,'1234');
	$this->objpdf->SetLineWidth(0.2);

  global $logo;
  $resinst = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"), 'logo'));
	db_fieldsmemory($resinst,0);
  $logofundo = substr($logo,0,strpos($logo,"."));

	/*   F U N D O   D O   D O C U M E N T O  */
  		
	if (file_exists('imagens/files/' . $logofundo.'_fundoalvara.jpg')){
		
  	  $this->objpdf->Image('imagens/files/'.$logofundo.'_fundoalvara.jpg',60,30,100,150);
	}else{
		
          $this->objpdf->Image('imagens/files/Brasao.jpg',60,30,100,150);
	}
  		
//	$this->objpdf->setxy(52,8);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->Multicell(0,8,$this->prefeitura,0,"C",0); // prefeitura

	$this->objpdf->setxy(58,15);
	$this->objpdf->setfont('Arial','B',12);
	$this->objpdf->setleftmargin(50);
	$this->objpdf->setrightmargin(50);
	$this->objpdf->Multicell(0,6,$this->tipoalvara,0,"C",0); // tipo de alvara

  $this->objpdf->Ln(6);
	$this->objpdf->sety(28);
	$this->objpdf->SetFont('Arial','',11);
	$this->objpdf->multicell(0,5,db_geratexto($this->texto),0,"J",0,20);

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+35,'INSCRIÇÃO:'); // inscricao

  if ($this->processo > 0) {
	  $this->objpdf->Text($coluna + 60,$linha+35,'PROCESSO:'); // inscricao
  }

	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+35,$this->nrinscr); // inscricao
        
  if ($this->processo > 0) {
    $this->objpdf->Text($coluna + 80,$linha+35,$this->processo); // processo
  }
	
	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+39,"NOME/RAZAO SOCIAL: "); // nome
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+39,$this->nome); // nome

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+43,"CNPJ/CPF: ");
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+43,$this->cnpjcpf);


	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+47,"ENDEREÇO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+47,$this->ender); // endereco

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+51,"NÚMERO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+51,($this->numero == ""?"":$this->numero));

  if ($this->compl != "") {
    $this->objpdf->SetFont('Arial','B',9);
	  $this->objpdf->Text($coluna + 60 ,$linha+51,"COMPLEMENTO: "); // endereco
	  $this->objpdf->SetFont('Arial','',9);
	  $this->objpdf->Text($coluna + 88,$linha+51,(substr($this->compl == ""?"":$this->compl,0)));
  }

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+55,"BAIRRO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+55,$this->bairropri);

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+59,"DATA INICIAL: ");
  if ($this->datafim != "") {
 	  $this->objpdf->Text($coluna + 60,$linha+59,"VALIDADE ATÉ: ");
  }
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+59,db_formatar($this->datainc,'d'));
  if ($this->datafim != "") {
	  $this->objpdf->Text($coluna + 85,$linha+59,db_formatar($this->datafim,'d'));
  }
  $this->objpdf->setx(45);
  $this->objpdf->roundedrect(42,$linha+30,127,31,2,'1234');
	$linha = 84;
//========================= ATIVIDADE PRINCIPAL ========================================================================================
  $this->objpdf->sety($linha);
  $pos = $linha;
  $alt = 4; 
	$this->objpdf->SetFont('Arial','B',8);
  $this->objpdf->Ln(2);
	$this->objpdf->setx(45);
	  
	$this->objpdf->Cell(83,3,"ATIVIDADE PRINCIPAL: ",0,0,"L",0) ; // descri??o da atividade principal
	if ($this->impdatas == 't'){
		$this->objpdf->Cell(20,3,"INICIO",0,0,"C",0);
		if ($this->permanente == 'f'){
		  $this->objpdf->Cell(20,3,"FINAL",0,1,"C",0);
		} else {
		  $this->objpdf->Cell(20,3,"",0,1,"C",0);
		}
  } else {
	  $this->objpdf->Cell(20,3,"",0,0,"C",0);
	  $this->objpdf->Cell(20,3,"",0,1,"C",0);
	}
	$this->objpdf->SetFont('Arial','',7);
	
	if ($this->impcodativ == 't'){
	  $this->objpdf->setx(45);
		$this->objpdf->Cell(10,3,$this->ativ,0,0,"C",0);
	} else {
	  $this->objpdf->setx(45);
	  $this->objpdf->Cell(10,3,"",0,0,"C",0);
	}
	$iPosInicioDescrAtividadePrimaria  = $this->objpdf->getY();
  $iLinhasDescricaoAtividadePrimaria = (round((strlen($this->descrativ)/73),2)*4);

	$this->objpdf->MultiCell(73,3,$this->descrativ,0,"L");

	$this->objpdf->sety($iPosInicioDescrAtividadePrimaria);
  $this->objpdf->setx(128);

	if ($this->impdatas == 't'){
		$this->objpdf->Cell(20,3,db_formatar($this->dtiniativ,'d'),0,0,"C",0);
		if ($this->permanente == 'f'){
		  $this->objpdf->Cell(20,3,db_formatar($this->dtfimativ,'d'),0,1,"C",0);
		} else {
			$this->objpdf->Cell(20,3,"",0,1,"C",0);
		}
	} else {
		$this->objpdf->Cell(20,3,"",0,0,"C",0);
	  $this->objpdf->Cell(20,3,"",0,1,"C",0);
	}
	
	if ($this->impobsativ == 't'){
		 $alt+= 3;
	   if (isset($this->obsativ) && $this->obsativ != "") {
	     $this->objpdf->setx(45);
	     $obs = $this->obsativ;
	     $this->objpdf->Cell(10,3,"",0,0,"C",0);
	     $this->objpdf->Cell(113,3,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,1,"L",0);
	   } else {
		   $this->objpdf->setx(45);
		   $this->objpdf->Cell(10,3,"",0,0,"C",0);
		   $this->objpdf->Cell(113,3,"OBS: Sem observações ...",0,1,"L",0);
		 }
	}
  
	$linha += 16;
  $yyy = $this->objpdf->gety();
	$obs='';
	$this->objpdf->sety($iPosInicioDescrAtividadePrimaria+$iLinhasDescricaoAtividadePrimaria);
	$this->objpdf->roundedrect($coluna-2,$pos+1,127,$alt+2+$iLinhasDescricaoAtividadePrimaria,2,'1234');

//==================== ATIVIDADES SECUNDARIAS  ====================================================================================================================
  $yyy   = $this->objpdf->gety();
  $linha = $this->objpdf->gety() + 1;
  $this->objpdf->sety($linha);
  $num_outras = count($this->outrasativs);
	
  $x          = 105;
	$y          = $linha+1;
	$iPos       = $y;
	
  if ($num_outras >0) {
    
    $x = $x + 4;
		reset($this->outrasativs); 
		$this->objpdf->setxy(45, $y);
	  $yyy = $this->objpdf->gety() + 7;
		$this->objpdf->SetFont('Arial','B',8);
    $this->objpdf->Cell(83,5,"ATIVIDADE" . ($num_outras > 1?"S":"") . " SECUNDÁRIA" . ($num_outras > 1?"S":"") . ":",0,0,"L",0);
    
	  if ($this->impdatas == 't') {
	    
	    $this->objpdf->Cell(20,5,"INÍCIO",0,0,"C",0);
	    if ($this->permanente == 'f') {
	      $this->objpdf->Cell(20,5,"FINAL",0,1,"C",0);
	    } else {
	      $this->objpdf->Cell(20,5,"",0,1,"C",0);
	    }
	  } else {
	    
	    $this->objpdf->Cell(20,5,"",0,0,"C",0);
	    $this->objpdf->Cell(20,5,"",0,1,"C",0);
	  }
//	       $linha += 6;
//	  	   $this->objpdf->Ln(1);
//========================================================================================================================================	  	   
	  //define em qual celula vai quebrar a linha
	  $count            = 0;
	  $iAlturaRetangulo = 0;
	  
	  for ($i = 0; $i < $num_outras; $i++) {
	  	
	    $yyy   = $this->objpdf->gety();
		  $chave = key($this->outrasativs);
		  $this->objpdf->SetFont('Arial','',7);
		  $this->objpdf->setx(45);
		  
		  if ($this->impcodativ == 't') {
		    
			  $codativ = $this->outrasativs[$i]["codativ"];
			  $this->objpdf->Cell(10,3,$codativ,0,0,"C",0);
		  } else {
		    $this->objpdf->Cell(10,3,"",0,0,"C",0);
		  }
		  
		  $descr           = $this->outrasativs[$i]["descr"];
		  $iAlturaAnterior = $this->objpdf->gety();
		  $this->objpdf->MultiCell(70, 3, mb_strtoupper($descr));
		  
		  $iAlturaPos        = $this->objpdf->gety();
		  $iAlturaRetangulo += ($iAlturaPos - $iAlturaAnterior) / 3;

		  $this->objpdf->SetY($iAlturaAnterior);
		  $this->objpdf->SetX(128);
		  
		  if ($this->impdatas == 't') {
		    
			  $datain = $this->outrasativs[$i]["datain"];
			  $this->objpdf->Cell(20,3,db_formatar($datain,'d'),0,0,"C",0);
			  if ($this->permanente == 'f') {
			    
			   $datafi = $this->outrasativs[$i]["datafi"];
			    $this->objpdf->Cell(20,3,db_formatar($datafi,'d'),0,1,"C",0);
			  } else {
			    $this->objpdf->Cell(20,3,"",0,1,"C",0);
			  }
		  } else {
		    
		    $this->objpdf->Cell(20,3,"",0,0,"C",0);
	      $this->objpdf->Cell(20,3,"",0,1,"C",0);
		  }
		  
		  $this->objpdf->SetY($iAlturaPos);
		  $this->objpdf->SetX(128);
		  
		  if ($this->impobsativ == 't') {
		    
		    $linha += 3;
		    if (isset($this->q03_atmemo[$descr])) {
		      
		      $this->objpdf->setx(45);
			    $obs = $this->q03_atmemo[$descr];
			    $this->objpdf->Cell(10,3,"",0,0,"C",0);
				 $this->objpdf->Cell(114,3,"OBS: ".substr($obs,0,62).(strlen($obs) > 62 ? "...":""),0,1,"L",0);
			  } else {
			    
			    $this->objpdf->setx(45);
			    $this->objpdf->Cell(10,3,"",0,0,"C",0);
			    $this->objpdf->Cell(114,3,"OBS: Sem observações ...",0,1,"L",0);
			 }
		  }
		  
		  $linha += 5;
		  $x      = $x+2;
		  next($this->outrasativs);
	    $yyyatual = $this->objpdf->gety();
		  if ($yyyatual >= 200){ 
		     break;
		  }
		  
      $count++;
		  if ($count > 13) {
		    
		  	 $count++;
		  	 $this->objpdf->Cell(40,4,"e outras ...",0,1,"L",0);
		  	 break;
		  }
		}
		
		/*
		 * Calculo para altura do quadro das atividades secundárias
		 * Multiplicamos a quantidade de atividades impressas por 3 que representa a altura da linha de cada registro
		 * Após somamos a altura referente a expressão "ATIVIDADES SECUNDARIAS" com o espaçamento da caixa (+1).
		 * 
		 * (Qtd de Atividades mostradas * 3 (altura da linha das atividades)) + 6 (Altura da linha da expressão "ATIVIDADES SECUNDARIAS" (5)+1(espaço do quadro))
		 */ 
		$altura = ($count * 3) + (($iAlturaRetangulo - $count) * 3) + 6;
		
		$this->objpdf->roundedrect(42, $y, 127, $altura, 2, '1234'); // descricao da atividade secundaria
  } else {
    
    $linha      = $linha-2;
    $iAlturaPos = $this->objpdf->gety();
  } 

//======================================================================================================================================== 	
 	
  $x = 64;
  $this->objpdf->ln(2);
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->SetY($iAlturaPos + 3);
	$this->objpdf->Multicell(0,4,$this->obs); // observa??o
//    $this->objpdf->ln(10);
    
  if (isset($this->impobslanc) && $this->impobslanc == 't') {
	  if (isset($this->lancobs) && $this->lancobs != '') { 
	    $this->objpdf->Ln(2);
	    $this->objpdf->setx(50);
	    $this->objpdf->SetFont('Arial','',8);
	    $this->objpdf->Multicell(0,5,"Obs : ".$this->lancobs);
	    $this->objpdf->Ln(2);
	  }
	}
	
	$this->objpdf->SetY(173);
  $this->objpdf->SetFont('Arial','B',9);
  $this->objpdf->cell($coluna,5,'',0,0,"L",0);
  $this->objpdf->cell(60,5,$this->municpref . ", ".date('d')." DE ".mb_strtoupper(db_mes( date('m')))." DE ".date('Y') . ".",0,1,"C"); // data
       
 //   global $db02_texto;
 	$this->objpdf->setfont('arial','',6);
  $this->objpdf->SetXY($coluna,165);
  $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$this->assalvara,0,"C",0);
	$this->objpdf->sety(185);
  $this->objpdf->setfont('arial','B',12);
  $this->objpdf->multicell(0,6,'FIXAR EM LUGAR VISIVEL',1,"C");
	$this->objpdf->SetFont('Arial','B',10);	
?>