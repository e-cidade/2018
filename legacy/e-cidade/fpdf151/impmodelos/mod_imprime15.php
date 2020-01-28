<?php
$classinatura = new cl_assinatura;

$ass_pref     = $classinatura->assinatura(1000,"","0");
$ass_prefFunc = $classinatura->assinatura(1000,"","1");
$ass_sec      = $classinatura->assinatura(1002,"","0");
$ass_secFunc  = $classinatura->assinatura(1002,"","1");
$ass_tes      = $classinatura->assinatura(1004,"","0");
$ass_tesFunc  = $classinatura->assinatura(1004,"","1");
$ass_cont     = $classinatura->assinatura(1005,"","0");
$ass_contFunc = $classinatura->assinatura(1005,"","1");



 for($xxx = 0;$xxx < $this->nvias;$xxx++){	
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text(117,$xlin-13,'ESTORNO DE PAGAMENTO N'.CHR(176).': ');
	$this->objpdf->text(175,$xlin-13,db_formatar($this->anulado,'s','0',6,'e'));
	$this->objpdf->text(134,$xlin-8,'DATA DE EMISSÃO : ');
	$this->objpdf->text(175,$xlin-8,$this->emissao);
	$this->objpdf->text(128,$xlin-3,'NOTA DE EMPENHO N'.CHR(176).': ');
	$this->objpdf->text(175,$xlin-3,db_formatar($this->codemp,'s','0',6,'e'));
	$this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin-8,$this->municpref);
	$this->objpdf->text(40,$xlin-5,$this->telefpref);
	$this->objpdf->text(40,$xlin-2,$this->emailpref);
	$this->objpdf->text(40,$xlin,db_formatar($this->cgcpref,'cnpj'));

        /// retangulo dos dados da dotação
	$this->objpdf->rect($xcol,$xlin+2,$xcol+100,50,2,'DF','1234');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+2,$xlin+7,'Órgao');
	$this->objpdf->text($xcol+2,$xlin+10.5,'Unidade');
	$this->objpdf->text($xcol+2,$xlin+14,'Função');
	
	$this->objpdf->text($xcol+2,$xlin+20.5,'Proj/Ativ');
	$this->objpdf->text($xcol+2,$xlin+27,'Rubrica');
	$this->objpdf->text($xcol+2,$xlin+35,'Recurso');

        if ($this->banco != "") {
            $this->objpdf->text($xcol+2,$xlin+38.5,'Banco');
            $this->objpdf->text($xcol+30,$xlin+38.5,'Agencia:');
            $this->objpdf->text($xcol+60,$xlin+38.5,'Conta:');
        }
	
	$this->objpdf->text($xcol+2,$xlin+42.5,'Reduzido');
	$this->objpdf->text($xcol+2,$xlin+48,'Licitação');
	
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	$this->objpdf->text($xcol+17,$xlin+10.5,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	$this->objpdf->text($xcol+17,$xlin+14,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);
	
	$this->objpdf->text($xcol+17,$xlin+20.5,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	
	$this->objpdf->text($xcol+17,$xlin+27,':  '.db_formatar($this->sintetico,'elemento'));
	$this->objpdf->setxy($xcol+18,$xlin+28);
	$this->objpdf->multicell(90,3,$this->descr_sintetico,0,"L");
	
	$this->objpdf->text($xcol+17,$xlin+35,':  '.$this->recurso.' - '.$this->descr_recurso);

        if ($this->banco != "") {
            $this->objpdf->text($xcol+17,$xlin+38.5,':  '.$this->banco);
            $this->objpdf->text($xcol+47,$xlin+38.5,      $this->agencia);
            $this->objpdf->text($xcol+77,$xlin+38.5,      $this->conta);
        }

	$this->objpdf->text($xcol+17,$xlin+42.5,':  '.$this->coddot);
	
	$this->objpdf->text($xcol+17,$xlin+48,':  '.$this->descr_licitacao);
	
	
        //// retangulo dos dados do credor
	$this->objpdf->rect($xcol+106,$xlin+2,96,18,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+107,$xlin+7,'Numcgm');
        $this->objpdf->text($xcol+140,$xlin+7,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+107,$xlin+11,'Nome');
	$this->objpdf->text($xcol+107,$xlin+15,'Endereço');
	$this->objpdf->text($xcol+107,$xlin+19,'Município');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+124,$xlin+7,': '.$this->numcgm);
	$this->objpdf->text($xcol+149,$xlin+7,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')).'  Fone: '.$this->telefone);
	$this->objpdf->text($xcol+124,$xlin+11,': '.$this->nome);
	$this->objpdf->text($xcol+124,$xlin+15,': '.$this->ender.'  '.$this->compl);
	$this->objpdf->text($xcol+124,$xlin+19,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);
	
	///// retangulo dos valores
	$this->objpdf->rect($xcol+106,$xlin+21.5,96,9,2,'DF','1234');
	$this->objpdf->rect($xcol+106,$xlin+32.0,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+32.0,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+106,$xlin+42.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+42.5,47,9,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+34.0,'Valor Empenhado');
	$this->objpdf->text($xcol+157,$xlin+34.0,'Data do Empenho');
	$this->objpdf->text($xcol+108,$xlin+44.5,'Valor Estorno');
	$this->objpdf->text($xcol+157,$xlin+44.5,'Data do Estorno');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+108,$xlin+27,'AUTORIZAÇÃO N'.chr(176).' '.db_formatar($this->numaut,'s','0',5,'e'));
	$this->objpdf->text($xcol+150,$xlin+27,'SEQ. DO EMPENHO N'.chr(176).' '.db_formatar($this->numemp,'s','0',6,'e'));
//	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
	$this->objpdf->text($xcol+130,$xlin+38.0,db_formatar($this->empenhado,'f'));
	$this->objpdf->text($xcol+180,$xlin+38.0,$this->emissao,'d');
	$this->objpdf->text($xcol+130,$xlin+47.5,db_formatar($this->vlr_anul,'f'));
	$this->objpdf->text($xcol+180,$xlin+47.5,db_formatar($this->data_est,'d'));
	

        /// retangulo do corpo do empenho 
	$this->objpdf->rect($xcol,$xlin+60,202,55,2,'DF','');
	$this->objpdf->rect($xcol,$xlin+117,202,55,2,'DF','');
	
  $maiscol = 0;
	
  $this->objpdf->rect($xcol,$xlin+178,202,6,2,'DF','34');
	  
	   $this->objpdf->SetFont('Arial','',7);
	   $this->objpdf->setxy($xcol+1,$xlin+68);
	   $this->objpdf->text($xcol+2,$xlin+64,'Histórico do Estorno : ',0,1,'L',0);
	   $this->objpdf->multicell(195,3.5,$this->descr_anu);
	   $this->objpdf->text($xcol+2,$xlin+120,'Histórico do Empenho : ',0,1,'L',0);
	   $this->objpdf->setxy($xcol+1,$xlin+124);
	   $this->objpdf->multicell(147,3.5,$this->resumo);
	   $this->objpdf->text($xcol+2,$xlin+182,'DESTINO : ',0,1,'L',0);
	   $this->objpdf->text($xcol+30,$xlin+182,$this->destino,0,1,'L',0);
	   
           $this->objpdf->rect($xcol,$xlin+197,67,47,2,'DF','34');
           $this->objpdf->rect($xcol+67,$xlin+197,67,47,2,'DF','34');
           $this->objpdf->rect($xcol+134,$xlin+197,68,47,2,'DF','34');
	   
           $this->objpdf->rect($xcol,$xlin+191,67,6,2,'DF','12');
           $this->objpdf->rect($xcol+67,$xlin+191,67,6,2,'DF','12');
           $this->objpdf->rect($xcol+134,$xlin+191,68,6,2,'DF','12');
       

           $this->objpdf->text($xcol+19,$xlin+195,'CONTADORIA GERAL');
//	       $this->objpdf->text($xcol+84,$xlin+195,'GABINETE DO PREFEITO');

          if ($this->db21_instit == '1'){
              $this->objpdf->text($xcol+84,$xlin+195,'GABINETE DO PREFEITO');
          }elseif ($this->db21_instit == '2'){
              $this->objpdf->text($xcol+82,$xlin+195,'PRESIDÊNCIA DO LEGISLATIVO');
          }elseif ($this->db21_instit == '5'){
              $this->objpdf->text($xcol+82,$xlin+195,'DIRETORIA');
          }elseif ($this->db21_instit == '6'){
              $this->objpdf->text($xcol+82,$xlin+195,'DIRETORIA');
          }else{
              $this->objpdf->text($xcol+84,$xlin+195,'GABINETE DO PREFEITO');
          }

	         $this->objpdf->text($xcol+158,$xlin+195,'TESOURARIA');

           if ($this->assinatura3 != "") {
	       $this->objpdf->line($xcol+8,$xlin+225,$xcol+58,$xlin+225);
	   }

	   
           if(strtoupper(trim($this->municpref)) == 'SAPIRANGA'){
	      $this->objpdf->line($xcol+10,$xlin+210,$xcol+55,$xlin+210);
	      $this->objpdf->SetFont('Arial','',6);
	      $this->objpdf->text($xcol+29-(strlen($this->assinatura2)/2),$xlin+212,$this->assinatura2);
	   }						       

	        $this->objpdf->line($xcol+73,$xlin+225,$xcol+125,$xlin+225);
          $this->objpdf->SetFont('Arial','',6);
            //$this->objpdf->text($xcol+29-(strlen($this->assinatura3)/2),$xlin+227,$this->assinatura3);
            //$this->objpdf->text($xcol+40-(strlen($this->assinatura3)/2),$xlin+227,$ass_cont);
          $this->objpdf->setXY($xcol+3, $xlin +227);
          $this->objpdf->cell(60,3,$ass_contFunc,0,0,"C",0);

          $this->objpdf->setXY($xcol+3, $xlin +230);
          $this->objpdf->cell(60,3,$ass_cont,0,0,"C",0);

           // $this->objpdf->text($xcol+99-(strlen($this->assinaturaprefeito)/2),$xlin+227,$this->assinaturaprefeito);
           // $this->objpdf->text($xcol+96-(strlen($this->assinaturaprefeito)/2),$xlin+227,$ass_pref);
         $this->objpdf->setXY($xcol+70, $xlin +227);
         $this->objpdf->cell(60,3,$ass_prefFunc,0,0,"C",0);

         $this->objpdf->setXY($xcol+70, $xlin +230);
         $this->objpdf->cell(60,3,$ass_pref,0,0,"C",0);

         $this->objpdf->line($xcol+141,$xlin+225,$xcol+195,$xlin+225);
	         // $this->objpdf->text($xcol+160,$xlin+227,'TESOUREIRO');
         $this->objpdf->setXY($xcol+138, $xlin +227);
         $this->objpdf->cell(60,3,$ass_tesFunc,0,0,"C",0);

         $this->objpdf->setXY($xcol+138, $xlin +230);
         $this->objpdf->cell(60,3,$ass_tes,0,0,"C",0);


//           $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
	   
	   $this->objpdf->SetFont('Arial','',4);
           $this->objpdf->Text(2,296,$this->texto); // texto no canhoto do carne
  	   $this->objpdf->SetFont('Arial','',6);
           $this->objpdf->Text(200,296,($xxx+1).' via'); // texto no canhoto do carne
	   $this->objpdf->setfont('Arial','',11);
           $xlin = 169;
            
      }

?>
