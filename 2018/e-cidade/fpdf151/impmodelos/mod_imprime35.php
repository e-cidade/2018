<?
//require('fpdf151/alphapdf.php');
//$obj_alpha   = new alphapdf;
//ie("aki");
$cldb_config = new cl_db_config;

	$this->objpdf->settopmargin(1);
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',10);
	$this->objpdf->SetLineWidth(1);
	$this->objpdf->RoundedRect(2,3,204,292,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
	$this->objpdf->roundedrect(4,5,200,288,2,'1234');
  $this->objpdf->SetLineWidth(0.2);

  global $logo;
  $resinst = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"), 'logo'));
	db_fieldsmemory($resinst,0);
  $logofundo = substr($logo,0,strpos($logo,"."));

  /*   F U N D O   D O   D O C U M E N T O  */
  		
	if (file_exists('imagens/files/' . $logofundo.'_fundoalvara.jpg')){
  	  $this->objpdf->Image('imagens/files/'.$logofundo.'_fundoalvara.jpg',60,95,100);
	}else{
          $this->objpdf->Image('imagens/files/Brasao.jpg',60,80,100);
	}
	

//        $obj_alpha->SetAlpha(0.5);
	$this->objpdf->Image('imagens/files/' . $logo,90,7,20);

	$this->objpdf->sety(34);
	$this->objpdf->setfont('Arial','B',16);
	$this->objpdf->Multicell(0,8,$this->prefeitura,0,"C",0); // prefeitura

	$this->objpdf->sety(42);
	$this->objpdf->setfont('Arial','B',16);
	$this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara

	$this->objpdf->setxy(10,59);
	$this->objpdf->SetFont('Arial','',14);
	$this->objpdf->multicell(0,7,db_geratexto($this->texto),0,"J",0,40);

	$coluna = 15;
	$linha = 58;
	$fonte = 12;

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+35,'INSCRIÇÃO:'); // inscricao

    if ($this->processo > 0) {
      $this->objpdf->Text($coluna + 97,$linha+35,'PROCESSO:'); // inscricao
    }

	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+35,$this->nrinscr); // inscricao
        
    if ($this->processo > 0) {
  	  $this->objpdf->Text($coluna + 135,$linha+35,$this->processo); // processo
    }

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+42,"NOME/RAZAO SOCIAL: "); // nome
	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+42,$this->nome); // nome

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+49,"CNPJ/CPF: ");
	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+49,$this->cnpjcpf);


	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+56,"ENDEREÇO: "); // endereco
	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+56,$this->ender); // endereco

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+63,"NÚMERO: "); // endereco
	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+63,($this->numero == ""?"":$this->numero));

    if ($this->compl != "") {
   	  $this->objpdf->SetFont('Arial','B',$fonte);
	  $this->objpdf->Text($coluna + 97 ,$linha+63,"COMPLEMENTO: "); // endereco
	  $this->objpdf->SetFont('Arial','',$fonte);
	  $this->objpdf->Text($coluna + 135,$linha+63,($this->compl == ""?"":$this->compl));
    }

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+70,"BAIRRO: "); // endereco
	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+70,$this->bairropri);

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+77,"DATA INICIAL: ");
    if ($this->datafim != "") {
  	  $this->objpdf->Text($coluna + 60,$linha+77,"VALIDADE ATÉ: ");
    }
	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+77,db_formatar($this->datainc,'d'));
    if ($this->datafim != "") {
	  $this->objpdf->Text($coluna + 105,$linha+77,db_formatar($this->datafim,'d'));
    }
    $this->objpdf->setx(44);
    $this->objpdf->roundedrect($coluna-2,$linha+30,187,51,2,'1234');
	$linha = 142;

//========================= ATIVIDADE PRINCIPAL ========================================================================================
      $this->objpdf->sety($linha);
      $pos = $linha;
      $alt = 10; 
//	  $this->objpdf->roundedrect($coluna-2,$linha-1,187,20,2,'1234');
	  $this->objpdf->SetFont('Arial','B',12);
  	  $this->objpdf->Ln(2);
	  $this->objpdf->setx(15);
	  $quebradatas = 0;
      $quebraobs   = 1;
      $quebradescr = 0;
      if ($this->impdatas == 't'){
      	$quebradescr = 0;
      	$quebradatas = 1;
      }
      if ($this->impobsativ == 'f'){
      	$quebraobs   = 0;
      	$incremento  = 6;
      }
	  $this->objpdf->Cell(135,5,"ATIVIDADE PRINCIPAL: ",0,0,"L",0) ; // descrição da atividade principal
	  if ($this->impdatas == 't'){
		  	$this->objpdf->Cell(24,5,"INICIO",0,0,"C",0);
		  	if($this->permanente == 'f'){
		    	$this->objpdf->Cell(24,5,"FINAL",0,1,"C",0);
		    }else{
		    	$this->objpdf->Cell(24,5,"",0,1,"C",0);
		    }
	  }else{
	       	$this->objpdf->Cell(24,5,"",0,0,"C",0);
	       	$this->objpdf->Cell(24,5,"",0,1,"C",0);
	  }
	  $this->objpdf->SetFont('Arial','',11);
	  if ($this->impcodativ == 't'){
	  	 $this->objpdf->setx(15);
		 $this->objpdf->Cell(15,5,$this->ativ,0,0,"C",0);
	  }else{
	  	 $this->objpdf->setx(15);
	  	 $this->objpdf->Cell(15,5,"",0,0,"C",0);
	  }
	  $this->objpdf->Cell(120,5,$this->descrativ,0,$quebradescr,"L",0);
	  if ($this->impdatas == 't'){
		 $this->objpdf->Cell(24,5,db_formatar($this->dtiniativ,'d'),0,0,"C",0);
		 if ($this->permanente == 'f'){
		    $this->objpdf->Cell(24,5,db_formatar($this->dtfimativ,'d'),0,$quebradatas,"C",0);
			 }else{
			  	$this->objpdf->Cell(24,5,"",0,$quebradatas,"C",0);
			 }
		 }else{
		     	$this->objpdf->Cell(24,5,"",0,0,"C",0);
	       		$this->objpdf->Cell(24,5,"",0,1,"C",0);
		 }
	   if ($this->impobsativ == 't'){
		 $alt+= 5;
	     if(isset($this->obsativ) && $this->obsativ != ""){
	       	$this->objpdf->setx(15);
	        $obs = $this->obsativ;
	        $this->objpdf->Cell(15,4,"",0,0,"C",0);
	        $this->objpdf->Cell(164,4,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,1,"L",0);
	        }else{
		    	$this->objpdf->setx(15);
		     	$this->objpdf->Cell(15,4,"",0,0,"C",0);
		        $this->objpdf->Cell(164,4,"OBS: Sem observação ...",0,1,"L",0);
		    }
		 }
  	  $linha += 16;
      $yyy = $this->objpdf->gety();
	  $obs='';
	  $this->objpdf->roundedrect($coluna-2,$pos+1,187,$alt+4,2,'1234');	  
	  
//========================= ATIVIDADE SECUNDARIAS ========================================================================================	  
		$this->objpdf->setx(15);
	    $yyy = $this->objpdf->gety();
        $linha = $this->objpdf->gety() + 5;
        $this->objpdf->sety($linha);
        $num_outras=count($this->outrasativs);
		$x=105;
		$y=$linha+1;
//========================================================================================================================================================================
	    if ($num_outras >0) {
           $x=$x+4;
	       reset($this->outrasativs); 
	       $this->objpdf->Ln(2);
	       $this->objpdf->setx(15);
	       $yyy = $this->objpdf->gety() + 7;
	       $this->objpdf->SetFont('Arial','B',11);
	       $this->objpdf->Cell(135,5,"ATIVIDADE" . ($num_outras > 1?"S":"") . " SECUNDÁRIA" . ($num_outras > 1?"S":"") . ":",0,0,"L",0);
	       if ($this->impdatas == 't'){
	         $this->objpdf->Cell(24,5,"INICIO",0,0,"C",0);
	       	 if($this->permanente == 'f'){
	       	    $this->objpdf->Cell(24,5,"FINAL",0,1,"C",0);
	       	 }else{
	       	    $this->objpdf->Cell(24,5,"",0,1,"C",0);
	       	 }
	       }else{
	         $this->objpdf->Cell(24,5,"",0,0,"C",0);
	       	 $this->objpdf->Cell(24,5,"",0,1,"C",0);
	       }
	       $this->objpdf->Ln(1);
	       $this->objpdf->setx(15);
	       $this->objpdf->SetFont('Arial','B',8);
	       $impdatafim="";
	       $linha += 12;
//========================================================================================================================================================================
           //define em qual celula vai quebrar a linha
           $quebradatas = 0;
           $quebraobs   = 1;
           $quebradescr = 0;
           $incremento  = 4;
           if ($this->impdatas == 't'){
         	$quebradescr = 0;
         	$quebradatas = 1;
           }
           if ($this->impobsativ == 'f'){
         	$quebraobs   = 0;
         	$incremento  = 4;
           }
       $alt = 0; 
	   for ($i=0; $i < $num_outras; $i++) {
          
             $yyy = $this->objpdf->gety();
		     $chave=key($this->outrasativs);
		     $this->objpdf->SetFont('Arial','',9);
		     $this->objpdf->setx(15);
		     if ($this->impcodativ == 't'){
			     $codativ=$this->outrasativs[$i]["codativ"];
			     $this->objpdf->Cell(15,4,$codativ,0,0,"C",0);
		     }else{
		     	 $this->objpdf->Cell(15,4,"",0,0,"C",0);
		     }
		     $descr=$this->outrasativs[$i]["descr"];
		     $this->objpdf->Cell(120,4,$descr,0,$quebradescr,"L",0);
		     if ($this->impdatas == 't'){
			     $datain=$this->outrasativs[$i]["datain"];
			     $this->objpdf->Cell(24,4,db_formatar($datain,'d'),0,0,"C",0);
			     if ($this->permanente == 'f'){
				     $datafi=$this->outrasativs[$i]["datafi"];
			         $this->objpdf->Cell(24,4,db_formatar($datafi,'d'),0,$quebradatas,"C",0);
			     }else{
			     	 $this->objpdf->Cell(24,4,"",0,$quebradatas,"C",0);
			     }
		     }else{
			     $this->objpdf->Cell(24,5,"",0,0,"C",0);
	       	     $this->objpdf->Cell(24,5,"",0,1,"C",0);
		     }
		     if ($this->impobsativ == 't'){
		         if(isset($this->q03_atmemo[$descr])){
		         	$this->objpdf->setx(15);
			        $obs = $this->q03_atmemo[$descr];
			        $this->objpdf->Cell(15,4,"",0,0,"C",0);
			        $this->objpdf->Cell(164,4,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,$quebraobs,"L",0);
			     }else{
			     	  $this->objpdf->setx(15);
			     	  $this->objpdf->Cell(15,4,"",0,0,"C",0);
			        $this->objpdf->Cell(164,4,"OBS: Sem observação ...",0,$quebraobs,"L",0);
			     }
		     }
		     $linha += $incremento;
			 $x=$x+2;
		     next($this->outrasativs);
	         $yyyatual = $this->objpdf->gety();
			 
		   if ( $i == 7 || $yyyatual >= 250) {
		   	 if ($i == 7) {
		   	   $alt = 5; 	
		   	 }
			 $chave=key($this->outrasativs);
			 $this->objpdf->SetFont('Arial','',11);
			 $this->objpdf->setx(15);
			 $this->objpdf->Cell(15,4,"",0,0,"C",0);
			 $this->objpdf->Cell(40,4,"e outras ...",0,1,"L",0);
			 break;
		   }

		   }

//=====================================================================================================================================================	   
    $this->objpdf->roundedrect($coluna-2,$y,187,$linha-($y-(4+$alt)),2,'1234'); // descricao da atividade secundaria
 	}
    $x=64;
  	$this->objpdf->Ln(2);
	$this->objpdf->setxy(14,$y+$linha-($y-(4+$alt)) );

	$this->objpdf->SetFont('Arial','',12);
	$this->objpdf->Multicell(0,6,$this->obs); // observação

	if(isset($this->impobslanc) && $this->impobslanc == 't'){
	  if (isset($this->lancobs) && $this->lancobs != '') {;
	    $this->objpdf->Ln(2);
	    $this->objpdf->setx(15);
	    $this->objpdf->SetFont('Arial','',10);
	    $this->objpdf->Multicell(0,5,"Obs : ".$this->lancobs);
	    $this->objpdf->Ln(2);
	  }
	}
    
    $this->objpdf->ln(5);
    $this->objpdf->SetFont('Arial','B',13);
    $this->objpdf->cell(0,8,$this->municpref . ", ".date('d')." DE ".strtoupper(db_mes( date('m')))." DE ".date('Y') . ".",0,1,"R",0); // data
		
//  global $db02_texto;
	  $this->objpdf->setfont('arial','',9);
		
  //  $this->objpdf->SetXY($coluna,264);
	
/***************************************************************************************************************************************************/	

//  for pegando as assinaturas do alvara
		$sqlparag = "select *
					from db_documento 
					inner join db_docparag on db03_docum = db04_docum
					inner join db_tipodoc on db08_codigo  = db03_tipodoc
					inner join db_paragrafo on db04_idparag = db02_idparag 
					where db03_tipodoc = 1010 and db03_instit = ".db_getsession("DB_instit")." 
					  and db02_descr ilike 'assinatura_%' 
					order by db04_ordem ";
		$resparag = pg_query($sqlparag);
		
//		db_criatabela($resparag);exit;
//		die($sqlparag);

		if (pg_numrows($resparag) == 0) {
			db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento do alvara!');
			exit;
		}
		$numrows = pg_numrows($resparag);
		
		$linha  = $this->objpdf->getY()+10;
		$colpri = $coluna;
		global $db02_texto;
		for ($i = 0; $i < $numrows; $i ++){
				db_fieldsmemory($resparag, $i);
				//echo("texto -- ".$db02_texto);
				$ass = $db02_texto;
				if($i % 2 == 0){
					 $this->objpdf->SetXY($coluna,$linha);
					 $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$ass,0,"C",0);
				}else{
					 $this->objpdf->SetXY($coluna+90,$linha);
					 $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$ass,0,"C",0);
				   $linha += 10;
				}
		}
		
/*******************************************************************************************************************************************************************/	
		
  	$this->objpdf->SetAutoPageBreak('on',0);
  	$this->objpdf->sety(280);
    $this->objpdf->setfont('arial','B',20);
    $this->objpdf->multicell(0,10,'FIXAR EM LUGAR VISÍVEL',1,"C");
?>
