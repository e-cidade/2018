<?php

  $this->objpdf->SetTextColor(0,0,0);
  $this->objpdf->SetFont('Arial','B',12);
  $coluna = 44;
  $linha = 20;
  $this->objpdf->SetLineWidth(1);
  $this->objpdf->RoundedRect(37,0.2,137,195,2,'1234');
  $this->objpdf->SetLineWidth(0.5);
  $this->objpdf->roundedrect(39,2,133,191,2,'1234');
  $this->objpdf->SetLineWidth(0.2);
  $this->objpdf->Image('imagens/files/Brasao.png',43,5,20);
  
  $cldb_config = new cl_db_config;
  global $logo;
  $resinst = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"), 'logo'));
  db_fieldsmemory($resinst,0);
  $logofundo = substr($logo,0,strpos($logo,"."));
  /*   F U N D O   D O   D O C U M E N T O  */
  if (file_exists('imagens/files/' . $logofundo.'_fundoalvara_sanitario.jpg')){
    $this->objpdf->Image('imagens/files/'.$logofundo.'_fundoalvara_sanitario.jpg',60,30,100);
  } else {
    $this->objpdf->Image('imagens/files/Brasao.jpg',60,30,100);
  }
  
  
  $this->objpdf->setleftmargin(50);
  $this->objpdf->setrightmargin(50);

  $this->objpdf->setxy(65,8);
  $this->objpdf->setfont('Arial','B',13);
  $this->objpdf->Multicell(0,8,$this->prefeitura,"C"); // prefeitura

  $this->objpdf->setxy(75,15);
  $this->objpdf->setfont('Arial','B',13);
  $this->objpdf->Multicell(0,8,$this->tipoalvara,"C"); // tipo de alvara

  $this->objpdf->Ln(6);
  $this->objpdf->sety(28);
  $this->objpdf->SetFont('Arial','',11);
  $this->objpdf->multicell(0,5,db_geratexto($this->texto),0,"J",0,20);

  $this->objpdf->SetFont('Arial','B',9);
  $this->objpdf->Text($coluna,$linha+35,'N° ALVARÁ:'); // inscricao
  if ($this->processo > 0) {
   $this->objpdf->Text($coluna + 70,$linha+35,'PROCESSO:'); // inscricao
  }

  $this->objpdf->SetFont('Arial','',9);
  $this->objpdf->Text($coluna + 40,$linha+35,$this->nrinscr); // inscricao
  if ($this->processo > 0) {
    $this->objpdf->Text($coluna + 90,$linha+35,$this->processo); // processo
  }
  
  $this->objpdf->SetFont('Arial','B',9);
  $this->objpdf->Text($coluna,$linha+39,"NOME/RAZÃO SOCIAL: "); // nome
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
    $this->objpdf->Text($coluna + 90,$linha+51,($this->compl == ""?"":$this->compl));
  }

  $this->objpdf->SetFont('Arial','B',9);
  $this->objpdf->Text($coluna,$linha+55,"BAIRRO: "); // endereco
  $this->objpdf->SetFont('Arial','',9);
  $this->objpdf->Text($coluna + 40,$linha+55,$this->bairropri);

  if (isset($this->area)&&$this->area!="") {
  	
    $this->objpdf->SetFont('Arial','B',9);
    $this->objpdf->Text($coluna+90,$linha+55,"ÁREA: "); // endereco
    $this->objpdf->SetFont('Arial','',9);
    $this->objpdf->Text($coluna +102,$linha+55,$this->area);
    
  }

  $this->objpdf->SetFont('Arial','B',9);
  $this->objpdf->Text($coluna,$linha+59,"DATA INICIAL: ");
  if ($this->datafim != "") {
    $this->objpdf->SetFont('Arial','B',11); 
    $this->objpdf->Text($coluna + 60,$linha+59,"VALIDADE ATÉ: ");
  }

  global $cgc;
  $resultcnpj  = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"), "cgc"));
  db_fieldsmemory($resultcnpj, 0);

  if ($cgc == "87896874000157") {
   $this->objpdf->SetFont('Arial','B',11);
   $anoseguinte = date("Y")+1;
   $this->objpdf->Text($coluna + 70,$linha+59,"VALIDO ATÉ 31/03/$anoseguinte");
  }

  $this->objpdf->SetFont('Arial','',9);
  $this->objpdf->Text($coluna + 40,$linha+59,db_formatar($this->datainc,'d'));
  if ($this->datafim != "") {
   $this->objpdf->SetFont('Arial','B',11); 
   $this->objpdf->Text($coluna + 92,$linha+59,db_formatar($this->datafim,'d'));
  }
    
  $this->objpdf->setx(44);
  if ($this->q02_memo!='') {
    $this->objpdf->SetFont('Arial','B',9);
    $this->objpdf->Text($coluna,$linha+64,"OBSERVAÇÃO: "); // observação
    $this->objpdf->SetFont('Arial','',9);
    $this->objpdf->sety($linha+64);
    $this->objpdf->Multicell(0,3,$this->q02_memo); // texto
    $this->objpdf->SetFont('Arial','B',10);
    $this->objpdf->roundedrect(42,$linha+30,127,42,2,'1234');
    $linha = 94;
  } else {
    $this->objpdf->roundedrect(42,$linha+30,127,31,2,'1234');
    $linha = 83;
  }
  
  $this->objpdf->sety($linha);
//========================================================================================================================
  $linharect = $linha;
  $altrect   = 10;
  $this->objpdf->SetFont('Arial','B',8);
  $this->objpdf->Ln(2);
  $this->objpdf->setx(45);
  $this->objpdf->Cell(83,3,"ATIVIDADE PRINCIPAL : ",0,0,"L",0);
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
  
  $linha += 12;
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
  $this->objpdf->MultiCell(75,3,$this->descrativ);
  
  $this->objpdf->sety($iPosInicioDescrAtividadePrimaria);
  $this->objpdf->setx(128);
  if ($this->impdatas == 't') {
  	
  	$iSomaAltura = 3;
  	$this->objpdf->Cell(20,3,db_formatar($this->dtiniativ,'d'),0,0,"C",0);
  	
    if ($this->permanente == 'f') {
      $this->objpdf->Cell(20,3,db_formatar($this->dtfimativ,'d'),0,1,"C",0);
    } else {
      $this->objpdf->Cell(20,3,"",0,1,"C",0);
    }
    
  } else {
  	
    $this->objpdf->Cell(20,3,"",0,0,"C",0);
    $this->objpdf->Cell(20,3,"",0,1,"C",0);
    
  }
  
  if ($this->impobsativ == 't') {
  	
  	$iSomaAltura += 3;
    $altrect += 3;
    
    if (isset($this->obsativ) && $this->obsativ != "") {
    	
      $this->objpdf->setx(45);
      $obs = $this->obsativ;
      $this->objpdf->Cell(10,3,"",0,0,"C",0);
      $this->objpdf->Cell(113,3,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,1,"L",0);
      
    } else {
    	
      $this->objpdf->setx(45);
      $this->objpdf->Cell(10,3,"",0,0,"C",0);
      $this->objpdf->Cell(113,3,"",0,1,"L",0);
      
    }
    
  }
  
  $this->objpdf->sety($iPosInicioDescrAtividadePrimaria+$iLinhasDescricaoAtividadePrimaria+$iSomaAltura);
  $this->objpdf->roundedrect($coluna-2,$linharect,127,$altrect+$iLinhasDescricaoAtividadePrimaria,2,'1234');
  $linha += 16;
  $yyy = $this->objpdf->gety();
  $obs='';
    
//============================================================================================================================

  $this->objpdf->setx(45);
  $yyy = $this->objpdf->gety();
  $linha = $this->objpdf->gety() + 5;
  $this->objpdf->sety($linha);
  $num_outras=count($this->outrasativs);
  $x=105;
  $y=$linha+1;
  $this->objpdf->SetFont('Arial','B',8);
  
   
  $iLinhasDescricaoAtividadeSecundaria = 0;
  if (isset($this->outrasativs)&&$this->outrasativs!=""){
     reset($this->outrasativs); 
     
     $this->objpdf->setx(45);
     $this->objpdf->Cell(82,3,"ATIVIDADE" . ($num_outras > 1?"S":"") . " SECUNDÁRIA" . ($num_outras > 1?"S":"") . ":",0,0,"L",0);
     if ($this->impdatas == 't'){
       $this->objpdf->Cell(20,3,"INÍCIO",0,0,"C",0);
        
       if($this->permanente == 'f'){
         $this->objpdf->Cell(20,3,"FINAL",0,1,"C",0);
       }else{
         $this->objpdf->Cell(20,3,"",0,1,"C",0);
       }
        
     } else {
       $this->objpdf->Cell(20,3,"",0,0,"C",0);
       $this->objpdf->Cell(20,3,"",0,1,"C",0);
     }
     
     $this->objpdf->Cell(183,1,"",0,1,"L",0);
     $this->objpdf->SetFont('Arial','',8);
       
     for ($i=0; $i<sizeof($this->outrasativs); $i++) {
     	
       $yyy = $this->objpdf->gety();
       $chave=key($this->outrasativs);
       $indice="";
       $obs='';
       $indice = key($this->q03_atmemo);
       $this->objpdf->SetFont('Arial','',7);
       $this->objpdf->setx(45);
       
       if ($this->impcodativ == 't') {
         $codativ = $this->outrasativs[$i]["codativ"];
         $this->objpdf->Cell(10,3,"$codativ",0,0,"C",0);
       } else {
         $this->objpdf->Cell(10,3,"",0,0,"L",0);
       }
       
       $descr   = $this->outrasativs[$i]["descr"];
       $iLinhasDescricaoAtividadeSecundaria += (round((strlen($descr)/73),2)*4);
       $this->objpdf->MultiCell(75,3,$descr);
       $this->objpdf->setxy(128,$yyy);
       
       if ($this->impdatas == 't') {
       	 
       	 $datain  = $this->outrasativs[$i]["datain"];
         $this->objpdf->Cell(20,3,db_formatar($datain,'d'),0,0,"C",0);
         if ($this->permanente == 'f') {
           $datafi  = $this->outrasativs[$i]["datafi"];
           $this->objpdf->Cell(20,3,db_formatar($datafi,'d'),0,1,"L",0);
         } else {
           $this->objpdf->Cell(20,3,"",0,1,"L",0);
         }
         
       } else {
       	 
         $this->objpdf->Cell(20,3,"",0,0,"L",0);
         $this->objpdf->Cell(20,3,"",0,1,"L",0);
         
       }
       
       if ($this->impobsativ == 't') {
       	
         $linha += 5;
         $this->objpdf->sety($this->objpdf->getY()+$iLinhasDescricaoAtividadeSecundaria);
         $iLinhasDescricaoAtividadeSecundaria += 3;
         
         if (isset($this->obsativ) && $this->obsativ != "") {
         	
           $obs = $this->obsativ;
           $this->objpdf->setx(45);
           $this->objpdf->Cell(10,3,"",0,0,"C",0);
           $this->objpdf->Cell(113,3,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,1,"L",0);
           
         } else {
         	 
         	 $this->objpdf->setx(45);
           $this->objpdf->Cell(10,3,"",0,0,"C",0);
           $this->objpdf->Cell(113,3,"",0,1,"L",0);
           
         }
         
       } else {
         $linha++;
       }
       
       if ($i == 0) {
       	
         if (sizeof($this->outrasativs)==1) {
           $linha += 3;
         }
         $linha += 11;
         
       } else {
       	
         $linha += 5;
         
       }
       
       $ativ="";
       $x=$x+2;
       next($this->outrasativs);
       //next($this->q03_atmemo);
       $yyyatual = $this->objpdf->gety();
       if ($yyyatual >= 200) {
         break;
       }
       
     }
     
     $this->objpdf->Ln(1);
     $this->objpdf->roundedrect(42,$y-3,127,$linha-$y-7+$iLinhasDescricaoAtividadeSecundaria,2,'1234'); // descricao da atividade secundaria
     
     
  } else {
    $linha += 11; 
  }
    
//=======================================================================================================================
  
  $x=64;
  $this->objpdf->Ln(2);
  $this->objpdf->setx(44);
  $this->objpdf->sety($y+$linha-$y-9+$iLinhasDescricaoAtividadeSecundaria);
  $this->objpdf->SetFont('Arial','',10);
  $this->objpdf->Multicell(0,6,$this->obs); // observação
  $this->objpdf->ln(5);
  if (isset($this->lancobs) && $this->impobslanc == 't') {
    $yyyatual = $this->objpdf->gety();
    
    $this->objpdf->SetFont('Arial','',9);
    $this->objpdf->setx(38);
    $this->objpdf->Cell(12,3,"",0,0,"L",0);
    
    $obs = "Obs.:".substr($this->lancobs,0,135);
    if(strlen($this->lancobs)>134) { 
    	$obs .= "..."; 
    }
    $this->objpdf->Multicell(110,4,$obs,0,"J",0); // observação
    $this->objpdf->Cell(165,1,"",0,1,"L",0);
    $yyyant = $this->objpdf->gety() + 2;
    $linha += $yyyatual-$yyyant-1;
  }
  
  $x=34;
  $this->objpdf->ln(2);
  $this->objpdf->SetFont('Arial','B',9);
  $this->objpdf->cell($coluna,5,'',0,0,"L",0);
  $this->objpdf->cell(60,5,$this->municpref . ", ".date('d')." DE ".strtoupper(db_mes( date('m')))." DE ".date('Y') . ".",0,1,"L",0); // data
  $this->objpdf->ln(1);
  $this->objpdf->setfont('arial','',6);
  $this->objpdf->SetXY($coluna,$this->objpdf->gety());
  $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$this->assalvara,0,"C",0);
  $this->objpdf->sety(185);
  $this->objpdf->setfont('arial','B',12);
  $this->objpdf->multicell(0,6,'FIXAR EM LUGAR VISÍVEL',1,"C");
  $this->objpdf->SetFont('Arial','B',10);
  
?>