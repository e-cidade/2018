<?
//require('fpdf151/alphapdf.php');
//$obj_alpha   = new alphapdf;

   $cldb_config = new cl_db_config;

	$this->objpdf->setTopMargin(50);
//  $this->objpdf->settopmargin(1);
//	$this->objpdf->settopmargin(1);
//	$this->objpdf->settopmargin(1);
	
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',12);
	$this->objpdf->SetLineWidth(1);
//	$this->objpdf->RoundedRect(2,3,204,292,2,'1234');
	$this->objpdf->RoundedRect(4,3,203,290,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
//	$this->objpdf->roundedrect(4,5,200,288,2,'1234');
	$this->objpdf->roundedrect(6,5,199,286,2,'1234');
    $this->objpdf->SetLineWidth(0.2);

  global $logo;
  $resinst = $cldb_config->sql_record($cldb_config->sql_query_file(db_getsession("DB_instit"), 'logo'));
	db_fieldsmemory($resinst,0);
  $logofundo = substr($logo,0,strpos($logo,"."));
	/*   F U N D O   D O   D O C U M E N T O  */
/*
if (file_exists('imagens/files/' . $logofundo.'_fundoalvara.jpg')){
  	  $this->objpdf->Image('imagens/files/'.$logofundo.'_fundoalvara.jpg',60,95,100);
	}else{
        $this->objpdf->Image('imagens/files/Brasao.jpg',60,80,100);
	}
		
*/

// $obj_alpha->SetAlpha(0.5);
//adiciona imagem no topo da folha
//	$this->objpdf->Image('imagens/files/' . $logo,90,7,20);

	$this->objpdf->sety(34);
	$this->objpdf->setfont('Arial','B',18);
//	$this->objpdf->Multicell(0,8,db_getNomeSecretaria(),0,"C",0); // libs/db_libtributario.php 
//	$this->objpdf->Multicell(0,8,'CADASTRO FISCAL DO ISSQN',0,"C",0);  

	$this->objpdf->sety(47);
	$this->objpdf->setfont('Arial','B',14);
	$this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara- criar doc 1034.

	$this->objpdf->setxy(12,59);
	$this->objpdf->SetFont('Arial','',10);
  $this->objpdf->Multicell(0,6,db_geratexto($this->texto));
//$this->objpdf->multicell(0,6,db_geratexto($this->texto),0,"J",0,10);//

	$coluna = 15;
	$fonte  = 8;
  $linha =  $this->objpdf->gety();
	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+9,'INSCRIÇÃO:'); // inscricao
/*
    if ($this->processo > 0) {
      $this->objpdf->Text($coluna + 70,$linha+9,'PROCESSO:'); // inscricao
    }
*/
	$this->objpdf->SetFont('Arial','B',$fonte);
  
  $DigAtivM1=substr($this->ativ,0,-3);
  $DigAtivM2=substr($this->ativ,-3);
  $DigAtiv=$DigAtivM1.".".$DigAtivM2;

	$this->objpdf->Text($coluna + 34,$linha + 9,$DigAtiv.".".$this->nrinscr); // inscricao
/*        
    if ($this->processo > 0) {
  	  $this->objpdf->Text($coluna + 110,$linha+9,$this->processo); // processo
    }
 */

  $this->objpdf->SetFont('Arial','B',$fonte);
  $this->objpdf->Text($coluna + 115,$linha + 9,"N°. DO C.G.M.: "); // nome
  $this->objpdf->SetFont('Arial','B',$fonte);
  $this->objpdf->Text($coluna + 147,$linha + 9,$this->cgm); // nome




	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+15,"NOME/RAZÃO SOCIAL: "); // nome
	$this->objpdf->SetFont('Arial','B',$fonte);
  if ($this->nomecompl!=""){
      $this->objpdf->Text($coluna + 34,$linha + 15,substr($this->nomecompl,0,75)); // nome completo
  }else{
	    $this->objpdf->Text($coluna + 34,$linha + 15,$this->nome); // nome
  }
	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+21,"NOME FANTASIA: "); // nome
	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna + 34,$linha+21,$this->fantasia); // nome


	$this->objpdf->SetFont('Arial','B',$fonte);
  $tamanhho=strlen($this->cnpjcpf);
  if ($tamanhho==11){
      $this->objpdf->SetFont('Arial','B',$fonte);
      $this->objpdf->Text($coluna,$linha+27,"CPF: ");
    	$this->objpdf->Text($coluna + 34,$linha+27,db_formatar($this->cnpjcpf,"cpf"));
  }else{
      $this->objpdf->SetFont('Arial','B',$fonte);
      $this->objpdf->Text($coluna,$linha+27,"CNPJ: ");
      $this->objpdf->Text($coluna + 34,$linha+27,db_formatar($this->cnpjcpf,"cnpj"));
  }

  $imprime="";
  if ($this->icms!=""){
      $imprime="ICMS:";
      $icms_rg=$this->icms;
  }else if ($this->rg!=""){
            $imprime="RG:";
            $icms_rg=$this->rg;
        }else{
            $icms_rg="";
       }

  $this->objpdf->SetFont('Arial','B',$fonte);
  $this->objpdf->Text($coluna+ 115,$linha+27,"$imprime");
  $this->objpdf->SetFont('Arial','B',$fonte);
  $this->objpdf->Text($coluna + 147,$linha+27,"$icms_rg");


	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+33,"ENDEREÇO: "); // endereco
	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna + 34,$linha+33,$this->ender); // endereco

  $this->objpdf->SetFont('Arial','B',$fonte);
  $this->objpdf->Text($coluna+115,$linha+33,"NÚMERO: "); // endereco
  $this->objpdf->SetFont('Arial','B',$fonte);
  $this->objpdf->Text($coluna + 147,$linha+33,($this->numero == ""?"":$this->numero));


    if ($this->compl != "") {
   	  $this->objpdf->SetFont('Arial','B',$fonte);
	  $this->objpdf->Text($coluna  ,$linha+39,"COMPLEMENTO: "); // endereco
	  $this->objpdf->SetFont('Arial','B',$fonte);
	  $this->objpdf->Text($coluna + 34 ,$linha+39,($this->compl == ""?"":$this->compl));
    }

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna+115,$linha+39,"BAIRRO: "); // endereco
	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna + 147,$linha+39,substr($this->bairropri,0,18));

	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna,$linha+45,"DATA INICIAL: ");
/*  if ($this->datafim != "") {
  	  $this->objpdf->Text($coluna + 50,$linha+57,"VALIDADE ATÉ: ");
  }
*/
	$this->objpdf->SetFont('Arial','B',$fonte);
	$this->objpdf->Text($coluna + 34,$linha+45,db_formatar($this->datainc,'d'));
/*  if ($this->datafim != "") {
	  $this->objpdf->Text($coluna + 105,$linha+57,db_formatar($this->datafim,'d'));
    }
*/

   $this->objpdf->SetFont('Arial','B',$fonte);
   $this->objpdf->Text($coluna+115,$linha+45,"DATA DO CADASTRO: ");
   $this->objpdf->SetFont('Arial','B',$fonte);
   $this->objpdf->Text($coluna + 147,$linha + 45,db_formatar($this->datacad,'d'));

    $this->objpdf->setx(44);
    $this->objpdf->roundedrect($coluna-2,$linha+3,187,46,2,'1234');
	  $this->objpdf->Ln(1);
    $linha = 137;
	  $linha1 =  $this->objpdf->gety();
//    $linhaAtividadeSecudanria = $this->objpdf->gety();
//========================= ATIVIDADE PRINCIPAL ========================================================================================
      $this->objpdf->sety($linha-3);
      $pos = $linha;
      $alt = 12; 
	  $this->objpdf->SetFont('Arial','B',$fonte);
  	  $this->objpdf->Ln(1);
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
	  $this->objpdf->SetFont('Arial','B',$fonte);
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

     $this->objpdf->setx(15);
     $this->objpdf->Cell(15,4,"",0,0,"C",0);
     $this->objpdf->SetFont('Arial','B',$fonte);
     if ($this->q07horaini!="" && $this->q07horafim!=""){
     $this->objpdf->Cell(164,4,"HORÁRIO PERMITIDO DAS "   .$this->q07horaini.   " às "   .$this->q07horafim   ,0,1,"R",0);
     }
  	  $linha += 16;
      $yyy = $this->objpdf->gety();
	  $obs='';
	  $this->objpdf->roundedrect($coluna-2,$pos+1,187,$alt,2,'1234');	  
    $this->objpdf->setxy(20,135);  
    $this->objpdf->setfillcolor(255);
     $this->objpdf->SetFont('Arial','B',$fonte);
	  $this->objpdf->cell(35,5,"ATIVIDADE PRINCIPAL",0,0,"C",1) ; // descrição da atividade principal
    $this->objpdf->sety(140+($alt*2));
	  
//========================= ATIVIDADE SECUNDARIAS ========================================================================================	  
		    $fonteSec=8; 
        $this->objpdf->setx(15);
  	    $yyy = $this->objpdf->gety();
        $linha = $this->objpdf->gety();
        $this->objpdf->sety($linha-15);
        $num_outras=count($this->outrasativs);
		$x=105;
		$y=$linha-10;//linha do retângulo
//========================================================================================================================================================================
	    if ($num_outras >0) {
         $x=$x+4;
	       reset($this->outrasativs); 
	       $this->objpdf->Ln(1);
	       $this->objpdf->setx(15);
	       $yyy = $this->objpdf->gety() ;
	   
         $this->objpdf->SetFont('Arial','B',$fonteSec);
	       if ($this->impdatas == 't'){
	         $this->objpdf->Cell(24,5,"INICIO",0,0,"C",0);
	         $atv_perman = "t";   
	         for($i=0; $i<$num_outras; $i++){
	            $perman = $this->outrasativs[$i]["atv_perman"];
	            if($perman=="f"){
	              $atv_perman = "f";
	            }
	          }
	         
	       	 if($atv_perman == 'f'){
	       	    $this->objpdf->Cell(24,5,"FINAL",0,1,"C",0);
	       	 }else{
	       	    $this->objpdf->Cell(24,5,"",0,1,"C",0);
	       	 }
	       }else{
	         $this->objpdf->Cell(24,5,"",0,0,"C",0);
	       	 $this->objpdf->Cell(24,5,"",0,1,"C",0);
	       }
	       $this->objpdf->Ln(2);
	       $this->objpdf->setx(15);
	       $this->objpdf->SetFont('Arial','B',$fonteSec);
	       $impdatafim="";
	       $linha += 12;
//========================================================================================================================================================================
           //define em qual celula vai quebrar a linha
           $quebradatas = 0;
           $quebraobs   = 1;
           $quebradescr = 0;
           $incremento  = 10;
           if ($this->impdatas == 't'){
         	$quebradescr = 0;
         	$quebradatas = 1;
           }
           if ($this->impobsativ == 'f'){
         	$quebraobs   = 0;
         	$incremento  = 5;
           }
           $linhasec = 0;
           $imprime_linha = 'f';

	   for($i=0; $i<$num_outras; $i++){
	       $linhasec = $linhasec +1;
	       if($linhasec<16) { // para imprimir somente 14 atividades
		         if($linhasec!=15){ // se não for a 15º ativ... imprime
		           $imprime_linha = 't';
		         }else if($num_outras<15){  // se for a 15º ativ... e se o total de ativ.for menos q 15 ... imprime as 5... senão imprime 4 e "e outras"
		                   $imprime_linha = 't';
		               }else{
		                       $yyy = $this->objpdf->gety();
						               $chave=key($this->outrasativs);
						               $this->objpdf->SetFont('Arial','B',$fonteSec);
						               $this->objpdf->setx(15);
						               $this->objpdf->Cell(15,4,"",0,0,"C",0);
				                   $this->objpdf->Cell(40,4,"e outras ...",0,1,"L",0);
		                      $imprime_linha = 'f';
		               }
		         
		       
		       if($imprime_linha=='t'){
	           $yyy = $this->objpdf->gety();
				     $chave=key($this->outrasativs);
				     $this->objpdf->SetFont('Arial','B',$fonteSec);
				     $this->objpdf->setx(15);
				     
				     if ($this->impcodativ == 't'){
					     $codativ=$this->outrasativs[$i]["codativ"];
					   $this->objpdf->Cell(15,4,$codativ,0,0,"C",0);
				     }else{
				     	 $this->objpdf->Cell(15,4,"",0,0,"C",0);
				     }
				     $descr=$this->outrasativs[$i]["descr"];
				     $this->objpdf->Cell(120,4,$descr,0,$quebradescr,"L",0);//imprime secundaria
				     
				     if ($this->impdatas == 't'){
					     $datain=$this->outrasativs[$i]["datain"];
					     $this->objpdf->Cell(24,4,db_formatar($datain,'d'),0,0,"C",0);
					     $atv_perman = $this->outrasativs[$i]["atv_perman"];
					     if ($atv_perman == 'f'){
						     $datafi=$this->outrasativs[$i]["datafi"];
					         $this->objpdf->Cell(24,4,db_formatar($datafi,'d'),0,$quebradatas,"C",0);
					     }else{
					     	 $this->objpdf->Cell(24,4,"",0,$quebradatas,"C",0);
					     }
				     }else{
					   //  	$this->objpdf->Cell(24,5,"",0,0,"C",0);
			       		$this->objpdf->Cell(24,3,"",0,1,"C",0);
				     }
		     }
	       
//         echo "<pre>" . var_dump($this->q03_atmemo) . "</pre> - <br>descr: $descr<br>";
		     
		     if ($this->impobsativ == 't'){
		       $linhasec = $linhasec +1;
		       if($linhasec<15){
			         if(isset($this->q03_atmemo[$codativ])){
			         	$this->objpdf->setx(15);
				        $obs = $this->q03_atmemo[$codativ];
				        $this->objpdf->Cell(15,4,"",0,0,"C",0);
				        $this->objpdf->Cell(164,4,"OBS: ".substr($obs,0,65).(strlen($obs) > 65 ? "...":""),0,$quebraobs,"L",0);
				        
				     }else{ 
				        $this->objpdf->setx(15);
				     	  $this->objpdf->Cell(15,4,"",0,0,"C",0);
				        $this->objpdf->Cell(164,4,"OBS: Sem observação ...",0,$quebraobs,"L",0);
				     	  
				     }
		       }
		     }
	       }
		       $x=$x+2;
			     next($this->outrasativs);
		       $yyyatual = $this->objpdf->gety()-30;
			     if  ($yyyatual >= 200){ 
			         break;
			     }  
		   
		   }
		   
//=====================================================================================================================================================	   
    $this->objpdf->roundedrect($coluna-2,$y,187,49,2,'1234'); // descricao da atividade secundaria
    $this->objpdf->setxy(20,151);
    $this->objpdf->setfillcolor(255);
    $this->objpdf->SetFont('Arial','B',$fonte);
    $this->objpdf->Cell(42,5,"ATIVIDADE" . ($num_outras > 1?"S":"") . " SECUNDÁRIA" . ($num_outras > 1?"S":"") . "",0,0,"L",1);
   // $this->objpdf->sety(140+($alt*2));
 	}
  $x=64;
  $this->objpdf->Ln(0);
	$this->objpdf->setxy(12,205);

	$this->objpdf->SetFont('Arial','',10);
	$this->objpdf->Multicell(0,6,$this->obs); // observação

	if(isset($this->impobslanc) && $this->impobslanc == 't'){
	  if (isset($this->lancobs) && $this->lancobs != '') {;
	    $this->objpdf->Ln(2);
	    $this->objpdf->setx(15);
	    $this->objpdf->SetFont('Arial','',14);
	  //  $this->objpdf->Multicell(0,5,"Obs : ".$this->lancobs);
	    $this->objpdf->Ln(2);
	  }
	}
    
    $this->objpdf->ln(10);
    $this->objpdf->SetFont('Arial','B',8);
    $this->objpdf->cell(0,8,$this->municpref . ", ".date('d')." DE ".strtoupper(db_mes( date('m')))." DE ".date('Y') . ".",0,1,"R",0); // data
	$this->objpdf->ln(5);	
//  global $db02_texto;
	  $this->objpdf->setfont('arial','',9);
		
//    $this->objpdf->SetXY($coluna,264);
	
/******************************************** ASSINATURAS ************************************************************************************/	
//select * from where tipodoc = 1010 and db02_descr = 'ASSINATURAS_CODIGOPHP';
//se achou da eval no texto, senao faz como atualmente...
$sqlass = "
			select *
			from db_documento 
			inner join db_docparag on db03_docum = db04_docum
			inner join db_tipodoc on db08_codigo  = db03_tipodoc
			inner join db_paragrafo on db04_idparag = db02_idparag 
			where db03_tipodoc = 1010 and db03_instit = ".db_getsession("DB_instit")." 
			and db02_descr = 'ASSINATURAS_CODIGOPHP'
			";
			//die($sqlass);
$resultass = pg_query($sqlass);
$linhasass = pg_num_rows($resultass);
if ($linhasass>0){
	//db_fieldsmemory($resultass,0);
	$ass= pg_result($resultass,0,'db02_texto');
	eval($ass);
}else{
// QUANDO NÃO TIVER "ASSINATURAS_CODIGOPHP" CADASTRADAS NA DB_DOCUMENTOS pegar o modo antigo.
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
}	
/*******************************************************************************************************************************************************************/	
		
  	$this->objpdf->SetAutoPageBreak('on',0);
  	$this->objpdf->sety(275);
    $this->objpdf->setfont('arial','B',14);
    $this->objpdf->multicell(0,7,'FIXAR EM LUGAR VISÍVEL',1,"C");
    
    $this->objpdf->setfont('arial','B',8);
    $this->objpdf->multicell(0,5,'Obs: Vide verso',0,"R");

$this->objpdf->AddPage();
$this->objpdf->settopmargin(1);
$this->objpdf->SetTextColor(0,0,0);
$this->objpdf->SetFont('Arial','B',12);
$this->objpdf->SetLineWidth(1);
//$this->objpdf->RoundedRect(2,3,204,292,2,'1234');
$this->objpdf->RoundedRect(4,3,203,290,2,'1234');
$this->objpdf->SetLineWidth(0.5);
//$this->objpdf->roundedrect(4,5,200,288,2,'1234');
$this->objpdf->roundedrect(6,5,199,286,2,'1234');
$this->objpdf->SetLineWidth(0.2);
//roundedrect
$coluna = 10;
//$linha =  $this->objpdf->gety();
$linha = 0;
$this->objpdf->sety(10);
$this->objpdf->Ln(2);
$this->objpdf->setfont('arial','',10);
$this->objpdf->Multicell(190,5,substr($this->q02_memo,0,5900),0,"J");
if( $this->objpdf->gety()>242) {
  $this->objpdf->Cell(18,3,"e outras ...",0,0,"C",0);
}
$this->objpdf->sety(240);

$this->objpdf->setfont('arial','B',10);

$this->objpdf->Line(8,250,201,250);

$this->objpdf->Multicell(190,35,'VISITA DO FISCAL TRIBUTÁRIO EM:              /                /                 HORA:        ',0,"L");
$this->objpdf->Multicell(190,5,'-------------------------------------------------------------------------------------------',0,"C");
$this->objpdf->setfont('arial','B',10);
$this->objpdf->Multicell(190,5,'ASSINATURA E CARIMBO DO FISCAL',0,"C");

$this->objpdf->roundedrect($coluna-2,$linha+8,193,280,2,'1234');

$this->objpdf->setxy(15,7);
$this->objpdf->setfillcolor(255);
$this->objpdf->SetFont('Arial','B',$fonte);
$this->objpdf->cell(25,5,"OBSERVAÇÕES",0,0,"C",1) ; 


?>
