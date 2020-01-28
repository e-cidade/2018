<?

$teste = 1;
$teste = 2;

for($xxx = 0;$xxx < $this->nvias;$xxx++){	
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	$ano = $this->ano;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text(128,$xlin-13,'NOTA DE LIQUIDAÇÃO N'.CHR(176).': ');
	$this->objpdf->text(177,$xlin-13,db_formatar($this->ordpag,'s','0',6,'e'));
	$this->objpdf->text(134,$xlin-8,'DATA DE EMISSÃO : ');
	$this->objpdf->text(175,$xlin-8,$this->emissao);
	$this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12); 
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin-8,$this->municpref);
	$this->objpdf->text(40,$xlin-5,$this->telefpref);
	$this->objpdf->text(40,$xlin-2,$this->emailpref);

        /// retangulo dos dados da dotação
	$this->objpdf->rect($xcol,$xlin+2,$xcol+100,39,2,'DF','1234');
	$this->objpdf->Setfont('Arial','B',8);
	  
	$this->objpdf->text($xcol+2,$xlin+7,'Órgao');
	$this->objpdf->text($xcol+2,$xlin+11,'Unidade');
	$this->objpdf->text($xcol+2,$xlin+15,'Função');
	
	$this->objpdf->text($xcol+2,$xlin+19,'Proj/Ativ');
	$this->objpdf->text($xcol+2,$xlin+23,'Dotação');
	$this->objpdf->text($xcol+2,$xlin+27,'Elemento');
	$this->objpdf->text($xcol+2,$xlin+34,'Recurso');
	
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.substr($this->descr_orgao,0,46));
	$this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	$this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);
	
	$this->objpdf->text($xcol+17,$xlin+19,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	$this->objpdf->text($xcol+17,$xlin+23,':  '.$this->dotacao);
	$this->objpdf->text($xcol+17,$xlin+27,':  '.db_formatar($this->elemento,'elemento'));
	$this->objpdf->text($xcol+17,$xlin+30,'   '.$this->descr_elemento);
	$this->objpdf->text($xcol+17,$xlin+34,':  '.$this->recurso.' - '.$this->descr_recurso);

        if($ano < db_getsession("DB_anousu")){
          $this->objpdf->text($xcol+2,$xlin+38,'RESTOS A PAGAR ');
	}
	
      
        //// retangulo dos dados do credor
	$this->objpdf->rect($xcol+106,$xlin+2,96,27,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+107,$xlin+9,'Numcgm');
	$this->objpdf->text($xcol+150,$xlin+9,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+107,$xlin+13,'Nome');
	$this->objpdf->text($xcol+107,$xlin+17,'Endereço');
	$this->objpdf->text($xcol+107,$xlin+21,'Município');
	$this->objpdf->text($xcol+107,$xlin+25,'Banco/Ag./Conta');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+124,$xlin+9,': '.$this->numcgm);
	$this->objpdf->text($xcol+157,$xlin+9,' :  '.$this->cnpj);
	$this->objpdf->text($xcol+124,$xlin+13,': '.$this->nome);
	$this->objpdf->text($xcol+124,$xlin+17,': '.$this->ender.'  '.$this->compl);
	$this->objpdf->text($xcol+124,$xlin+21,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);
	if($this->banco != null){
	  $agenciadv = "";
	  $contadv = "";
	  if(trim($this->agenciadv)!=""){
	    $agenciadv = "-".$this->agenciadv;
	  }
	  if(trim($this->contadv)!=""){
	    $contadv = "-".$this->contadv;
	  }
	  $this->objpdf->text($xcol+131,$xlin+25,': '.$this->banco.' / '.$this->agencia.$agenciadv.' / '.$this->conta.$contadv);
	}
	
	///// retangulo do empenho
	$this->objpdf->rect($xcol+106,$xlin+32,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+32,47,9,2,'DF','1234');
	
        ///// retangulo dos itens	
        $this->objpdf->rect($xcol+102,$xlin+ 98, 25, 7,2,'DF','');
        $this->objpdf->rect($xcol+127,$xlin+ 98, 25, 7,2,'DF','');
        $this->objpdf->rect($xcol+152,$xlin+ 98, 25, 7,2,'DF','');
        $this->objpdf->rect($xcol+177,$xlin+ 98, 25, 7,2,'DF','');
        $this->objpdf->rect($xcol+000,$xlin+ 98,102,24,2,'DF','34');
        $this->objpdf->rect($xcol+000,$xlin+ 48,102,50,2,'DF','12');
        $this->objpdf->rect($xcol+102,$xlin+ 48, 25,50,2,'DF','12');
        $this->objpdf->rect($xcol+127,$xlin+ 48, 25,50,2,'DF','12');
        $this->objpdf->rect($xcol+152,$xlin+ 48, 25,50,2,'DF','12');
        $this->objpdf->rect($xcol+177,$xlin+ 48, 25,50,2,'DF','12');
        $this->objpdf->rect($xcol+102,$xlin+105, 75,17,2,'DF','34');
        $this->objpdf->rect($xcol+177,$xlin+105, 25,17,2,'DF','34');

        ///// retangulo das retenções
	$this->objpdf->rect($xcol+177,$xlin+179, 25, 8,2,'DF','34');
	$this->objpdf->rect($xcol+177,$xlin+171, 25, 8,2,'DF','');
	$this->objpdf->rect($xcol+000,$xlin+133, 75,46,2,'DF','12');
	$this->objpdf->rect($xcol+000,$xlin+179, 75, 8,2,'DF','34');
	$this->objpdf->rect($xcol+75 ,$xlin+133, 25,46,2,'DF','12');
	$this->objpdf->rect($xcol+75 ,$xlin+179, 25, 8,2,'DF','34');
	$this->objpdf->rect($xcol+102,$xlin+133, 75,38,2,'DF','12');
	$this->objpdf->rect($xcol+102,$xlin+171, 75, 8,2,'DF','');
	$this->objpdf->rect($xcol+102,$xlin+179, 75, 8,2,'DF','34');
	$this->objpdf->rect($xcol+177,$xlin+133, 25,38,2,'DF','12');

	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+34,'Empenho N'.chr(176));
	$this->objpdf->text($xcol+157,$xlin+34,'Valor do Empenho');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+130,$xlin+38,db_formatar($this->numemp,'s','0',6,'e'));
	$this->objpdf->text($xcol+180,$xlin+38,db_formatar($this->empenhado,'f'));
	
	//// retangulos do titulo do corpo do empenho
	
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text($xcol+2,$xlin+46,'Dados da Nota de Liquidação.');
	$this->objpdf->Setfont('Arial','B',6);
	
	//// título do corpo do empenho
        $maiscol = 0;
	
	/// monta os dados dos elementos da ordem de compra
        $this->objpdf->SetWidths(array(20,80,25,25,25,25));
	$this->objpdf->SetAligns(array('L','L','R','R','R','R'));
	$this->objpdf->setleftmargin(4);
	$this->objpdf->sety($xlin+48);
	$this->objpdf->cell(20,4,'ELEMENTO',0,0,"L");
	$this->objpdf->cell(80,4,'DESCRIÇÃO',0,0,"L");
	$this->objpdf->cell(25,4,'VALOR',0,0,"R");
	$this->objpdf->cell(25,4,'ANULADO',0,0,"R");
	$this->objpdf->cell(25,4,'PAGO',0,0,"R");
	$this->objpdf->cell(25,4,'SALDO',0,1,"R");
	$this->objpdf->Setfont('Arial','',7);

        $total_pag = 0;
        $total_emp = 0;
        $total_anu = 0;
        $total_sal = 0;
	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	  db_fieldsmemory($this->recorddositens,$ii);
	  
	  $this->objpdf->Setfont('Arial','',7);
          $this->objpdf->Row(array(
	                           (pg_result($this->recorddositens,$ii,$this->elementoitem)),
				   (pg_result($this->recorddositens,$ii,$this->descr_elementoitem)),
	                           db_formatar(pg_result($this->recorddositens,$ii,$this->vlremp),'f'),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->vlranu),'f'), 
				   db_formatar(pg_result($this->recorddositens,$ii,$this->vlrpag),'f'),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->vlrsaldo),'f')),3,false,3);
          $total_emp  += pg_result($this->recorddositens,$ii,$this->vlremp);
          $total_anu  += pg_result($this->recorddositens,$ii,$this->vlranu);
	  $total_pag  += pg_result($this->recorddositens,$ii,$this->vlrpag);
          $total_sal  += pg_result($this->recorddositens,$ii,$this->vlrsaldo);
	}


	/// monta os dados das retenções da ordem de compra
        $this->objpdf->SetWidths(array(10,62,25));
	$this->objpdf->SetAligns(array('C','L','R'));
	$this->objpdf->setleftmargin(4);
	$this->objpdf->setxy($xcol+102,$xlin+134);
	$this->objpdf->Setfont('Arial','B',10);

        $this->objpdf->text($xcol+104,$xlin+129,'Dados das Retenções');
        $this->objpdf->text($xcol+2,$xlin+129,'Repasses');
	$this->objpdf->Setfont('Arial','b',7);
		

        $this->objpdf->cell(10,4,'REC.',0,0,"L");
        $this->objpdf->cell(62,4,'DESCRIÇÃO',0,0,"L");
	$this->objpdf->cell(25,4,'VALOR',0,1,"R");
	
	$this->objpdf->Setfont('Arial','',7);

        $total_ret = 0;
	for($ii = 0;$ii < $this->linhasretencoes ;$ii++) {
	   $this->objpdf->setx($xcol+102);
	   db_fieldsmemory($this->recordretencoes,$ii);
	   $this->objpdf->Setfont('Arial','',7);
           $this->objpdf->Row(array(
	                           pg_result($this->recordretencoes,$ii,$this->receita),
				   pg_result($this->recordretencoes,$ii,$this->dreceita),
	 			   db_formatar(pg_result($this->recordretencoes,$ii,$this->vlrrec),'f')),3,false,3);
	   $total_ret += pg_result($this->recordretencoes,$ii,$this->vlrrec);
	}





	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->setxy($xcol+100,$xlin+100);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->cell(25,4,db_formatar($total_emp,'f'),0,0,"R");
	$this->objpdf->cell(25,4,db_formatar($total_anu,'f'),0,0,"R");
	$this->objpdf->cell(25,4,db_formatar($total_pag,'f'),0,0,"R");
	$this->objpdf->cell(25,4,db_formatar($total_sal,'f'),0,1,"R");
	

	$this->objpdf->setxy($xcol+127,$xlin+106);
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->cell(50,4,'TOTAL DA NOTA',0,0,"R");
	$this->objpdf->cell(23,4,db_formatar($total_emp-$total_anu,'f'),0,1,"R");
	$this->objpdf->setx($xcol+127);
	
	$this->objpdf->cell(50,4,'',0,0,"R");
	$this->objpdf->setx($xcol+127);

	$this->objpdf->cell(50,4,'',0,0,"R");
	$this->objpdf->setx($xcol+127);
	$this->objpdf->Setfont('Arial','b',8);
	$this->objpdf->text($xcol+2,$xlin+102,'OBSERVAÇÕES :');
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->setxy($xcol,$xlin+103);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->multicell(102,4,$this->obs);
        
	if ($teste != 1 ){
	  $xlin -= 10;
	}

	/// total das retenções
	$this->objpdf->setxy($xcol+127,$xlin+172);
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->cell(50,5,'TOTAL ',0,0,"R");
	$this->objpdf->cell(23,5,db_formatar($total_ret,'f'),0,1,"R");
	
	/// total dos repasses
	$this->objpdf->setxy($xcol,$xlin+181);
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->cell(75,5,'TOTAL ',0,0,"R");
	$this->objpdf->cell(23,5,db_formatar(0,'f'),0,1,"R");
        
	/// liquido da ordem de compra
	$this->objpdf->setxy($xcol+127,$xlin+181);
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->cell(50,5,'LÍQUIDO DA NOTA. ',0,0,"R");
	$this->objpdf->cell(23,5,db_formatar($total_sal - $total_ret,'f'),0,1,"R");


        // inclui rodape do cliente
	$file_default = 'pdfcliente/mod_rodape'.$this->impmodelo.'_default.php';
	$file_cliente = 'pdfcliente/mod_rodape'.$this->impmodelo.'_'.db_getsession("DB_instit").'.php';
        if (file_exists($file_cliente)) {
            include($file_cliente);// inclui arquivo padrão
        } else {
            include($file_default);// inclui arquivo cliente
        }
      
} // END FOR      

?>
