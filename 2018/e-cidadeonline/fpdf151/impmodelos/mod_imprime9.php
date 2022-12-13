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
	$this->objpdf->Image('imagens/files/Brasao.jpg',60,30,100);
	
//	$this->objpdf->roundedrect(42,$linha+30,127,35,2,'1234');
//	$this->objpdf->roundedrect(42,$linha+72,127,15,2,'1234'); // obs da atividade principal
	
//  	$this->objpdf->roundedrect(42,$linha+88,127,5,2,'1234'); // descricao da atividade secundaria
//	$this->objpdf->roundedrect(42,$linha+94,127,15,2,'1234'); // obs da atividade secundaria

//	$this->objpdf->setdrawcolor(235);

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
	$this->objpdf->Text($coluna,$linha+35,'INSCRIÇÃO:'); // inscricao

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

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+59,"DATA DE INCLUSAO: ");
        if ($this->datafim != "") {
  	  $this->objpdf->Text($coluna + 60,$linha+59,"VALIDADE ATÉ: ");
        }
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+59,db_formatar($this->datainc,'d'));
        if ($this->datafim != "") {
	  $this->objpdf->Text($coluna + 85,$linha+59,db_formatar($this->datafim,'d'));
        }

        $this->objpdf->setx(44);

	if($this->q02_memo!=''){
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
         
	  $this->objpdf->roundedrect(42,$linha-1,127,5,2,'1234');
	  $this->objpdf->SetFont('Arial','B',8);
  	  $this->objpdf->Ln(0.5);
	  $this->objpdf->setx(45);
	  $this->objpdf->Multicell(0,3,"ATIVIDADE PRINCIPAL: " . $this->descrativ) ; // descrição da atividade principal
  	  $linha += 6;
          $yyy = $this->objpdf->gety();
	  $obs='';
	  if(isset($this->q03_atmemo[$this->ativ])){
	    if ($this->q03_atmemo[$this->ativ] != '') {;
	      $obs = $this->q03_atmemo[$this->ativ];
	      $this->objpdf->Ln(3);
	      $this->objpdf->SetFont('Arial','',7);
	      $this->objpdf->Multicell(0,3,$this->q03_atmemo[$this->ativ]); // texto
//		 $linha += 16;
              $yyyatual = $this->objpdf->gety();
	      $this->objpdf->roundedrect(42,$linha-1,127,$yyyatual-$yyy,2,'1234'); // obs da atividade principal
	    }
	  }

        $yyy = $this->objpdf->gety();
        $linha = $this->objpdf->gety() + 2;
        $this->objpdf->sety($linha);
//        $this->objpdf->sety($linha);
	  
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
//           $this->objpdf->roundedrect(42,$yyy-1,127,5,2,'1234'); // descricao da atividade secundaria
           $this->objpdf->Multicell(0,3,"ATIVIDADE" . ($num_outras > 1?"S":"") . " SECUNDÁRIA" . ($num_outras > 1?"S":"") . ":");
           $linha += 6;
  	   $this->objpdf->Ln(2);

           $this->objpdf->roundedrect(42,$y,127,5,2,'1234'); // descricao da atividade secundaria

	   for($i=0; $i<$num_outras; $i++){
             $yyy = $this->objpdf->gety();
	     $chave=key($this->outrasativs);
	     $obs='';
	     if(isset($this->q03_atmemo[$chave])){
	       $obs = $this->q03_atmemo[$chave];
	     }

	     $this->objpdf->SetFont('Arial','',8);

//             $this->objpdf->roundedrect(42,$yyy-1,127,5,2,'1234'); // descricao da atividade secundaria
  	     $this->objpdf->Ln(0.5);
	     $this->objpdf->setx(45);
// 	     $this->objpdf->Multicell(0,3,"ATIVIDADE SECUNDÁRIA: " . $this->outrasativs[$chave]); // texto
 	     $this->objpdf->Multicell(0,3,$this->outrasativs[$chave]); // texto
	     $linha += 5;

	     if($obs!=""){
               $yyyant = $this->objpdf->gety() + 2;
//               $this->objpdf->Ln(1);
  	       $this->objpdf->SetFont('Arial','B',7);
 	       $this->objpdf->Multicell(0,3,$obs); // texto
               $yyyatual = $this->objpdf->gety();
//               $this->objpdf->roundedrect(42,$linha-1,127,$yyyatual-$yyyant+1,2,'1234'); // obs da atividade secundaria
	       $linha += $yyyatual-$yyyant;
	     }

	     $x=$x+4;
	     next($this->outrasativs);
//             $this->objpdf->ln(2.5);
	     $this->objpdf->sety($linha);

             $yyyatual = $this->objpdf->gety();

//	     if  ($i >= 5) break;
	     if  ($yyyatual >= 130) break;
	     
	   }

           $this->objpdf->roundedrect(42,$y,127,$linha-$y,2,'1234'); // descricao da atividade secundaria
	   
 	}


        $x=64;

//	$this->objpdf->sety(135);
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Multicell(0,4,$this->obs); // observação

//        if($this->q02_obs!=''){
//	  $this->objpdf->Text($coluna,$linha+$x,"OBSERVAÇÃO: "); // descrição da atividade principal
//	  $this->objpdf->Text($coluna + 45,$linha+$x,$this->q02_obs); // descrição da atividade principal
//	  $x=$x+4;
//	}

        $this->objpdf->ln(10);
        $this->objpdf->SetFont('Arial','B',9);
        $this->objpdf->cell($coluna,5,'',0,0,"L",0);
        $this->objpdf->cell(60,5,"DATA DE EMISSÃO DESTE DOCUMENTO.",0,1,"L",0);
        $this->objpdf->cell($coluna,5,'',0,0,"L",0);
        $this->objpdf->cell(60,5,$this->municpref . ", ".date('d')." DE ".strtoupper(db_mes( date('m')))." DE ".date('Y') . ".",0,1,"L",0); // data
       
        global $db02_texto;

	$sqlparag = "select db02_texto 
		     from db_documento 
		     inner join db_docparag on db03_docum = db04_docum 
		     inner join db_paragrafo on db04_idparag = db02_idparag 
		     where db03_docum = 26 and db02_descr ilike '%Assinatura Alvara%' and db03_instit = " . db_getsession("DB_instit");
	$resparag = db_query($sqlparag);

	if ( pg_numrows($resparag) == 0 ) {
	  db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento 26 com os paragrafos do alvara!');
	  exit; 
	}

	db_fieldsmemory($resparag,0);

	$this->objpdf->setfont('arial','',6);
        $this->objpdf->SetXY($coluna-18,170);

        $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$db02_texto,0,"C",0);
        $this->objpdf->SetXY($coluna+50,170);
        $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);
					
//        $this->objpdf->SetXY($coluna-35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".'SECRETÁRIO DA IND. COM. E TURISMO',0,"C",0);
//        $this->objpdf->SetXY($coluna+35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);


	$this->objpdf->sety(185);
        $this->objpdf->setfont('arial','B',12);
        $this->objpdf->multicell(0,6,'FIXAR EM LUGAR VISÍVEL',1,"C");
	$this->objpdf->SetFont('Arial','B',10);


?>
