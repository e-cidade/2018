<?php
	$this->objpdf->settopmargin(1);
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',12);
	$this->objpdf->SetLineWidth(1);
	$this->objpdf->RoundedRect(2,3,204,292,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
	$this->objpdf->roundedrect(4,5,200,288,2,'1234');
	$this->objpdf->SetLineWidth(0.2);
	$this->objpdf->Image('imagens/files/Brasao.jpg',60,80,100);
	$this->objpdf->Image('imagens/files/Brasao.png',90,5,20);

	$this->objpdf->sety(34);
	$this->objpdf->setfont('Arial','B',18);
	$this->objpdf->Multicell(0,8,$this->prefeitura,0,"C",0); // prefeitura

	$this->objpdf->sety(42);
	$this->objpdf->setfont('Arial','B',18);
	$this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara

	$this->objpdf->setxy(10,59);
	$this->objpdf->SetFont('Arial','',16);
	$this->objpdf->multicell(0,7,db_geratexto($this->texto),0,"J",0,40);

	$coluna = 15;
	$linha = 60;
	$fonte = 14;

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+35,'INSCRIÇÃO:'); // inscricao
	$this->objpdf->Text($coluna+120,$linha+35,'CÓDIGO PRÓPRIO:'); // inscricao

        if ($this->processo > 0) {
		$this->objpdf->Text($coluna + 70,$linha+35,'PROCESSO:'); // inscricao
        }

	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+35,$this->nrinscr); // inscricao
        
	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna+170,$linha+35,$this->numbloco); // numero do bloco
	
        if ($this->processo > 0) {
  		$this->objpdf->Text($coluna + 110,$linha+35,$this->processo); // processo
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
	  	$this->objpdf->Text($coluna + 110 ,$linha+63,"COMPLEMENTO: "); // endereco
	  	$this->objpdf->SetFont('Arial','',$fonte);
	  	$this->objpdf->Text($coluna + 160,$linha+63,($this->compl == ""?"":$this->compl));
    	}

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+70,"BAIRRO: "); // endereco
	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+70,$this->bairropri);

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+77,"DATA DE INCLUSAO: ");
    	if ($this->datafim != "") {
  		$this->objpdf->Text($coluna + 60,$linha+77,"VALIDADE ATÉ: ");
    	}
	$this->objpdf->SetFont('Arial','',$fonte);
	$this->objpdf->Text($coluna + 60,$linha+77,db_formatar($this->datainc,'d'));
    	if ($this->datafim != "") {
		$this->objpdf->Text($coluna + 105,$linha+77,db_formatar($this->datafim,'d'));
    	}
    	$this->objpdf->setx(44);
	if($this->q02_memo!=''){
		$this->objpdf->SetFont('Arial','B',$fonte);
		$this->objpdf->Text($coluna,$linha+99,"OBSERVAÇÃO: "); // observação
		$this->objpdf->SetFont('Arial','',$fonte);
	  	$this->objpdf->sety($linha+99);
	  	$this->objpdf->Multicell(0,3,$this->q02_memo); // texto
	  	$this->objpdf->SetFont('Arial','B',10);
 	  	$this->objpdf->roundedrect($coluna-2,$linha+30,187,62,2,'1234');
	  	$linha = 186;
	}else{
  	  	$this->objpdf->roundedrect($coluna-2,$linha+30,187,51,2,'1234');
	  	$linha = 175;
	}

//========================================================================================================================
     	$this->objpdf->sety($linha-30);
     	$linharect = $linha-30;
     	$altrect   = 15;
	$this->objpdf->SetFont('Arial','B',12);
        $this->objpdf->Ln(2);
        $this->objpdf->setx(15);
	$this->objpdf->Cell(135,5,"ATIVIDADE PRINCIPAL : ",0,0,"L",0);
        //print_r($this->impdatas);exit;
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
  	$linha += 12;
  	$this->objpdf->SetFont('Arial','',11);
        //print_r($this->impcodativ);exit;
        if ($this->impcodativ == 't'){
		$this->objpdf->setx(15);
		$this->objpdf->Cell(15,5,$this->ativ,0,0,"C",0);
	}else{
		$this->objpdf->setx(15);
		$this->objpdf->Cell(15,5,"",0,0,"C",0);
	}
	$this->objpdf->Cell(120,5,$this->descrativ,0,0,"L",0);
	if ($this->impdatas == 't'){
        	$this->objpdf->Cell(24,5,db_formatar($this->dtiniativ,'d'),0,0,"C",0);
		if ($this->permanente == 'f'){
			$this->objpdf->Cell(24,5,db_formatar($this->dtfimativ,'d'),0,1,"C",0);
		}else{
		  	$this->objpdf->Cell(24,5,"",0,1,"C",0);
		}
	}else{
		$this->objpdf->Cell(24,5,"",0,0,"C",0);
	    	$this->objpdf->Cell(24,5,"",0,1,"C",0);
	}
	if ($this->impobsativ == 't'){
		$altrect += 5;
	     	if(isset($this->obsativ) && $this->obsativ != ""){
	       		$this->objpdf->setx(15);
	        	$obs = $this->obsativ;
	        	$this->objpdf->Cell(15,5,"",0,0,"C",0);
	        	$this->objpdf->Cell(177,5,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,1,"L",0);
	        }else{
		    	$this->objpdf->setx(15);
		     	$this->objpdf->Cell(15,5,"",0,0,"C",0);
		        $this->objpdf->Cell(177,5,"OBS: Sem observações ...",0,1,"L",0);
		}
	}
	$this->objpdf->roundedrect($coluna-2,$linharect-2,187,$altrect,2,'1234');
  	$linha += 16;
        $yyy = $this->objpdf->gety();
	$obs='';
	  
//============================================================================================================================

	$this->objpdf->setx(15);
    	$yyy = $this->objpdf->gety();
    	$linha = $this->objpdf->gety() + 5;
    	$this->objpdf->sety($linha);
    	$altlin = 5;
    	$tamfont = 11;
    	if (isset($this->outrasativs)!=""){
    		$num_outras=count($this->outrasativs);
       		if(($num_outras > 5) && ($this->impobsativ == 't')) {
         		$altlin = 3;
	 		$tamfont = 9;
       		}elseif($num_outras > 9){
	 		$altlin = 3;
	 		$tamfont = 9;
	 	}
       		$y=$linha+1;
       		$this->objpdf->SetFont('Arial','B',12);
		//print_r($this->outrasativs);exit;
		//print_r($num_outras);exit;
     		reset($this->outrasativs); 
           	$this->objpdf->setx(15);
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
	   	$this->objpdf->Cell(183,1,"",0,1,"L",0);
  	   	$this->objpdf->SetFont('Arial','',12);
		//print_r($this->outrasativs);exit;
   		for($i=0; $i<sizeof($this->outrasativs); $i++){
         		$yyy = $this->objpdf->gety();
         		$chave=key($this->outrasativs);
             		$indice="";
	     		$obs='';
	     		$indice = key($this->q03_atmemo);
             		$this->objpdf->SetFont('Arial','',$tamfont);
	     		$this->objpdf->setx(15);
	     		if ($this->impcodativ == 't'){
	        		$codativ = $this->outrasativs[$i]["codativ"];
	        		$this->objpdf->Cell(15,$altlin,"$codativ",0,0,"C",0);
	     		}else{
	        		$this->objpdf->Cell(15,$altlin,"",0,0,"L",0);
	     		}
	   		//echo "1 - ".$this->outrasativs[$i]["descr"];
	     		$descr = $this->outrasativs[$i]["descr"];
			//db_msgbox($descr);
 	     		$this->objpdf->Cell(120,$altlin,$descr,0,0,"L",0);
	     		if ($this->impdatas == 't'){ 
	    			$datain  = $this->outrasativs[$i]["datain"];
 	     			$this->objpdf->Cell(24,$altlin,db_formatar($datain,'d'),0,0,"C",0);
				if ($this->permanente == 'f'){
	        	       		$datafi  = $this->outrasativs[$i]["datafi"];
			        	$this->objpdf->Cell(24,$altlin,db_formatar($datafi,'d'),0,1,"L",0);
				}else{
			        	$this->objpdf->Cell(24,$altlin,"",0,1,"L",0);
				}
	     		}else{
	        	   	$this->objpdf->Cell(24,$altlin,"",0,0,"L",0);
	        	   	$this->objpdf->Cell(24,$altlin,"",0,1,"L",0);
	     		}
	     		if ($this->impobsativ == 't'){
			     	$linha += 8;
			     	if(isset($this->obsativ) && $this->obsativ != ""){
		       			$this->objpdf->setx(15);
		        		$obs = $this->obsativ;
		        		$this->objpdf->Cell(183,1,"",0,1,"L",0);
		        		$this->objpdf->setx(15);
		        		$this->objpdf->Cell(15,$altlin,"",0,0,"C",0);
		        		$this->objpdf->Cell(177,$altlin,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,1,"L",0);
		        	}else{
				    	$this->objpdf->setx(15);
				    	$this->objpdf->Cell(183,1,"",0,1,"L",0);
				    	$this->objpdf->setx(15);
				     	$this->objpdf->Cell(15,$altlin,"",0,0,"C",0);
				        $this->objpdf->Cell(177,$altlin,"OBS: Sem observações ...",0,1,"L",0);
				}
			}else{
			 	$linha++;
			}
 	     		if ($i == 0){
 	     			if(sizeof($this->outrasativs)==1){
 	     				$linha += 4;
 	     			}
            			$linha += 15;
 	     		}else{
 	     			$linha += 10;
 	     		}
	     		$ativ="";
	     		next($this->outrasativs);
             		$yyyatual = $this->objpdf->gety();
	     		if  ($yyyatual >= 200){
			}
		}

		$linha_n = $this->objpdf->gety();
       		$this->objpdf->Ln(1);
       		$this->objpdf->roundedrect($coluna-2,$y-2,187,$linha_n-$y+3,2,'1234'); // descricao da atividade secundaria
	}else{
        	$linha = $linha+13;		
        }		
//=======================================================================================================================
	
    	$x=64;
  	$this->objpdf->Ln(2);
	//$this->objpdf->setx(14);
        $linha = $this->objpdf->gety()+6;
	$this->objpdf->sety($linha-8);
	$this->objpdf->SetFont('Arial','',13);
	$this->objpdf->Multicell(0,6,$this->obs); // observação
    	$this->objpdf->ln(2);
    	if(isset($this->lancobs) && $this->impobslanc == 't'){
        	$yyyatual = $this->objpdf->gety();
        	$obs = $this->lancobs;
	    	$this->objpdf->SetFont('Arial','',11);
	    	$this->objpdf->setx(15);
        	$this->objpdf->Cell(12,3,"",0,0,"L",0);
		if (strlen($obs)>75){
			$obs = substr("Obs: ".$obs,0,75)."...";		  
	    	}
        	$this->objpdf->Cell(150,3,"Obs: ".$obs,0,1,"L",0);
        	$this->objpdf->Cell(165,1,"",0,1,"L",0);
        	$yyyant = $this->objpdf->gety() + 2;
	    	$linha += $yyyatual-$yyyant-1;
	}
    	$this->objpdf->Ln(5);
  	$this->objpdf->SetFont('Arial','B',15);
    	$this->objpdf->cell(0,8,$this->municpref . ", ".date('d')." DE ".strtoupper(db_mes( date('m')))." DE ".date('Y') . ".",0,1,"R",0); // data
    
	$this->objpdf->Ln(25);
	$this->objpdf->setfont('arial','',10);
    	$this->objpdf->SetXY($coluna,264);
    	$this->objpdf->MultiCell(90,4,'.........................................................................................'."\n".$this->assalvara,0,"C",0);
	$this->objpdf->SetAutoPageBreak('on',0);
	$this->objpdf->sety(280);
    	$this->objpdf->setfont('arial','B',20);
    	$this->objpdf->multicell(0,10,'FIXAR EM LUGAR VISÍVEL',1,"C");
?>
