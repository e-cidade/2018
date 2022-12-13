<?php
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',12);
	$coluna = 44;
	$linha = 23;
	$this->objpdf->SetLineWidth(1);
	$this->objpdf->RoundedRect(37,0.2,137,195,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
	$this->objpdf->roundedrect(39,2,133,191,2,'1234');
	$this->objpdf->SetLineWidth(0.2);
	$this->objpdf->Image('imagens/files/Brasao.png',43,5,20);
	$this->objpdf->Image('imagens/files/Brasao.jpg',60,30,90);

	$this->objpdf->setxy(65,8);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->Multicell(0,8,$this->prefeitura,"C"); // prefeitura

	$this->objpdf->setxy(65,15);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->setleftmargin(50);
	$this->objpdf->setrightmargin(50);
	$this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara

    $this->objpdf->Ln(6);
	$this->objpdf->sety(28);
	$this->objpdf->SetFont('Arial','',11);
	$this->objpdf->multicell(0,5,db_geratexto($this->texto),0,"J",0,20);

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+35,'INSCRI��O:'); // inscricao

    if ($this->processo > 0) {
	  $this->objpdf->Text($coluna + 70,$linha+35,'PROCESSO:'); // inscricao
    }

	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+35,$this->nrinscr); // inscricao
        
    if ($this->processo > 0) {
  	  $this->objpdf->Text($coluna + 90,$linha+35,$this->processo); // processo
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
	$this->objpdf->Text($coluna,$linha+47,"ENDERE�O: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+47,$this->ender); // endereco

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+51,"N�MERO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+51,($this->numero == ""?"":$this->numero));

    if ($this->compl != "") {
   	  $this->objpdf->SetFont('Arial','B',9);
	  $this->objpdf->Text($coluna + 60 ,$linha+51,"COMPLEMENTO: "); // endereco
	  $this->objpdf->SetFont('Arial','',9);
	  $this->objpdf->Text($coluna + 90,$linha+51,($this->compl == ""?"":$this->compl));
    }

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+55,"BAIRRO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+55,$this->bairropri);

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+59,"DATA DE INCLUS�O: ");
    if ($this->datafim != "") {
 	  $this->objpdf->Text($coluna + 60,$linha+59,"VALIDADE AT�: ");
    }
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+59,db_formatar($this->datainc,'d'));
    if ($this->datafim != "") {
	  $this->objpdf->Text($coluna + 85,$linha+59,db_formatar($this->datafim,'d'));
    }
     $this->objpdf->setx(45);
     $this->objpdf->roundedrect(42,$linha+30,127,31,2,'1234');
	 $linha = 86;
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
		  	if($this->permanente == 'f'){
		    	$this->objpdf->Cell(20,3,"FINAL",0,1,"C",0);
		    }else{
		    	$this->objpdf->Cell(20,3,"",0,1,"C",0);
		    }
	  }else{
	       	$this->objpdf->Cell(20,3,"",0,0,"C",0);
	       	$this->objpdf->Cell(20,3,"",0,1,"C",0);
	  }
	  $this->objpdf->SetFont('Arial','',7);
	  if ($this->impcodativ == 't'){
	  	 $this->objpdf->setx(45);
		 $this->objpdf->Cell(10,3,$this->ativ,0,0,"C",0);
	  }else{
	  	 $this->objpdf->setx(45);
	  	 $this->objpdf->Cell(10,3,"",0,0,"C",0);
	  }
	  $this->objpdf->Cell(73,3,$this->descrativ,0,0,"L",0);
	  if ($this->impdatas == 't'){
		 $this->objpdf->Cell(20,3,db_formatar($this->dtiniativ,'d'),0,0,"C",0);
		 if ($this->permanente == 'f'){
		    $this->objpdf->Cell(20,3,db_formatar($this->dtfimativ,'d'),0,1,"C",0);
			 }else{
			  	$this->objpdf->Cell(20,3,"",0,1,"C",0);
			 }
		 }else{
		     	$this->objpdf->Cell(20,3,"",0,0,"C",0);
	       		$this->objpdf->Cell(20,3,"",0,1,"C",0);
		 }
	   if ($this->impobsativ == 't'){
		 $alt+= 3;
	     if(isset($this->obsativ) && $this->obsativ != ""){
	       	$this->objpdf->setx(45);
	        $obs = $this->obsativ;
	        $this->objpdf->Cell(10,3,"",0,0,"C",0);
	        $this->objpdf->Cell(113,3,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,1,"L",0);
	        }else{
		    	$this->objpdf->setx(45);
		     	$this->objpdf->Cell(10,3,"",0,0,"C",0);
		        $this->objpdf->Cell(113,3,"OBS: Sem observa��es ...",0,1,"L",0);
		    }
		 }
  	  $linha += 16;
      $yyy = $this->objpdf->gety();
	  $obs='';
	  $this->objpdf->roundedrect($coluna-2,$pos+1,127,$alt+4,2,'1234');

//==================== ATIVIDADES SECUNDARIAS  ====================================================================================================================
        $yyy = $this->objpdf->gety();
        $linha = $this->objpdf->gety() + 2;
        $this->objpdf->sety($linha);
        $num_outras=count($this->outrasativs);
		$x=105;
		$y=$linha+1;
        if ($num_outras >0) {
           $x=$x+4;
		   reset($this->outrasativs); 
	  	   $this->objpdf->Ln(2);
		   $this->objpdf->setx(45);
	       $yyy = $this->objpdf->gety() + 7;
		   $this->objpdf->SetFont('Arial','B',8);
           $this->objpdf->Cell(83,5,"ATIVIDADE" . ($num_outras > 1?"S":"") . " SECUND�RIA" . ($num_outras > 1?"S":"") . ":",0,0,"L",0);
	       if ($this->impdatas == 't'){
	       		$this->objpdf->Cell(20,5,"INICIO",0,0,"C",0);
	       		if($this->permanente == 'f'){
	       			$this->objpdf->Cell(20,5,"FINAL",0,1,"C",0);
	       		}else{
	       			$this->objpdf->Cell(20,5,"",0,1,"C",0);
	       		}
	       }else{
	       		$this->objpdf->Cell(20,5,"",0,0,"C",0);
	       		$this->objpdf->Cell(20,5,"",0,1,"C",0);
	       }
//	       $linha += 6;
//	  	   $this->objpdf->Ln(1);
//========================================================================================================================================	  	   
	       //define em qual celula vai quebrar a linha
	       for($i=0; $i<$num_outras; $i++){
	         $yyy = $this->objpdf->gety();
		     $chave=key($this->outrasativs);
		     $this->objpdf->SetFont('Arial','',7);
		     $this->objpdf->setx(45);
		     if ($this->impcodativ == 't'){
			     $codativ=$this->outrasativs[$i]["codativ"];
			     $this->objpdf->Cell(10,3,$codativ,0,0,"C",0);
		     }else{
		     	$this->objpdf->Cell(10,3,"",0,0,"C",0);
		     }
		     $descr=$this->outrasativs[$i]["descr"];
		     $this->objpdf->Cell(73,3,$descr,0,0,"L",0);
		     if ($this->impdatas == 't'){
			     $datain=$this->outrasativs[$i]["datain"];
			     $this->objpdf->Cell(20,3,db_formatar($datain,'d'),0,0,"C",0);
			     if ($this->permanente == 'f'){
				     $datafi=$this->outrasativs[$i]["datafi"];
			         $this->objpdf->Cell(20,3,db_formatar($datafi,'d'),0,1,"C",0);
			     }else{
			     	$this->objpdf->Cell(20,3,"",0,1,"C",0);
			     }
		     }else{
		     	$this->objpdf->Cell(20,3,"",0,0,"C",0);
	       		$this->objpdf->Cell(20,3,"",0,1,"C",0);
		     }
		     if ($this->impobsativ == 't'){
		     	$linha += 3;
		         if(isset($this->q03_atmemo[$descr])){
		         	$this->objpdf->setx(45);
			        $obs = $this->q03_atmemo[$descr];
			        $this->objpdf->Cell(10,3,"",0,0,"C",0);
					$this->objpdf->Cell(114,3,"OBS: ".substr($obs,0,62).(strlen($obs) > 62 ? "...":""),0,1,"L",0);
			     }else{
			     	$this->objpdf->setx(45);
			     	$this->objpdf->Cell(10,3,"",0,0,"C",0);
			        $this->objpdf->Cell(114,3,"OBS: Sem observa��es ...",0,1,"L",0);
				}
		     }
		     $linha += 5;
		     $x=$x+2;
		     next($this->outrasativs);
	         $yyyatual = $this->objpdf->gety();
		     if  ($yyyatual >= 200){ 
		         break;
		     }   
		   }
		   $this->objpdf->roundedrect(42,$y,127,$linha-$y+5,2,'1234'); // descricao da atividade secundaria
        }else{
            $linha = $linha-2;  
        } 

//======================================================================================================================================== 	
 	
    $x=64;
    $this->objpdf->SetY($linha+3);
    $this->objpdf->ln(2);
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Multicell(0,4,$this->obs); // observa??o
//    $this->objpdf->ln(10);
    
    if(isset($this->impobslanc) && $this->impobslanc == 't'){
	  if (isset($this->lancobs) && $this->lancobs != '') {;
	    $this->objpdf->Ln(2);
	    $this->objpdf->setx(50);
	    $this->objpdf->SetFont('Arial','',8);
	    $this->objpdf->Multicell(0,5,"Obs : ".$this->lancobs);
	    $this->objpdf->Ln(2);
	  }
	}
	
    $this->objpdf->ln(5);
    $this->objpdf->SetFont('Arial','B',9);
    $this->objpdf->cell($coluna,5,'',0,0,"L",0);
    $this->objpdf->cell(60,5,$this->municpref . ", ".date('d')." DE ".strtoupper(db_mes( date('m')))." DE ".date('Y') . ".",0,1,"L",0); // data
       
 //   global $db02_texto;
 	$this->objpdf->setfont('arial','',6);
    $this->objpdf->SetXY($coluna,264);
    $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$this->assalvara,0,"C",0);
	$this->objpdf->sety(185);
    $this->objpdf->setfont('arial','B',12);
    $this->objpdf->multicell(0,6,'FIXAR EM LUGAR VIS�VEL',1,"C");
	$this->objpdf->SetFont('Arial','B',10);	
?>
