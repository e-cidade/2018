<?php
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$this->objpdf->setleftmargin(4);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,$xlin-13,'ORDEM DE COMPRA N'.CHR(176));
	$this->objpdf->text(185,$xlin-13,db_formatar($this->numordem,'s','0',6,'e'));
	$this->objpdf->text(130,$xlin-10,'DATA :');
	$this->objpdf->text(185,$xlin-10,db_formatar($this->dataordem,'d'));
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->text(130,$xlin-7,'DEPARTAMENTO :');
	$this->objpdf->text(165,$xlin-7,$this->coddepto."-".$this->descrdepto);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
//	$this->objpdf->text(40,$xlin- 7,$this->municpref);
	$this->objpdf->text(40,$xlin- 7,"FONE: " . $this->telefpref);
	$this->objpdf->text(40,$xlin- 3,$this->emailpref);
	$this->objpdf->text(40,$xlin+1 ,$this->url . " - CNPJ:" . db_formatar($this->cgc,'cnpj'));

	$this->objpdf->rect($xcol,$xlin+2,$xcol+198,20,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+4.5,'Dados do Fornecedor');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+109,$xlin+8,'Numcgm');
	$this->objpdf->text($xcol+150,$xlin+8,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+  2,$xlin+8,'Nome');
	$this->objpdf->text($xcol+  2,$xlin+12,'Endereço');
	$this->objpdf->text($xcol+102,$xlin+12,'Complemento');
	$this->objpdf->text($xcol+  2,$xlin+16,'Município');
	$this->objpdf->text($xcol+115,$xlin+16,'CEP');
	$this->objpdf->text($xcol+  2,$xlin+20,'Contato');
	$this->objpdf->text($xcol+110,$xlin+20,'Telefone');
	$this->objpdf->text($xcol+155,$xlin+20,'FAX');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+157,$xlin+8,' :  '.$this->cnpj);
	$this->objpdf->text($xcol+122,$xlin+8,':  '.$this->numcgm);
	$this->objpdf->text($xcol+18,$xlin+ 8,':  '.$this->nome);
	$this->objpdf->text($xcol+18,$xlin+ 12,':  '.$this->ender);
	$this->objpdf->text($xcol+122,$xlin+12,':  '.$this->compl);
	$this->objpdf->text($xcol+18,$xlin+ 16,':  '.$this->munic.'-'.$this->uf);
	$this->objpdf->text($xcol+122,$xlin+16,':  '.$this->cep);
	$this->objpdf->text($xcol+18,$xlin+ 20,':  '.$this->contato);
	$this->objpdf->text($xcol+122,$xlin+20,':  '.$this->telef_cont);
	$this->objpdf->text($xcol+162,$xlin+20,':  '.$this->telef_fax);

	global $ordemdecompra1;
	global $ordemdecompra2;
	global $descrtexto;
	global $conteudotexto;

	$sqltexto = "select * from db_config where codigo = " . db_getsession("DB_instit");
	$resulttexto = db_query($sqltexto);
	db_fieldsmemory($resulttexto,0,true);

	$sqltexto = "select * from db_usuarios where id_usuario = " . db_getsession("DB_id_usuario");
	$resulttexto = db_query($sqltexto);
	db_fieldsmemory($resulttexto,0,true);
	
	$sqltexto = "select * from db_textos where id_instit = " . db_getsession("DB_instit") . " and ( descrtexto like 'ordemdecompra%')";
	$resulttexto = db_query($sqltexto);
	for( $xx = 0;$xx < pg_numrows($resulttexto);$xx ++ ){
	  db_fieldsmemory($resulttexto,$xx,true);
	  $text  = $descrtexto;
	  $$text = db_geratexto($conteudotexto);
	}

	$texto1 = @$ordemdecompra1;
	$texto2 = @$ordemdecompra2;
	
        $result_endent = db_query("select * from db_departender inner join ruas on j14_codigo = codlograd inner join bairro on j13_codi = codbairro where coddepto = " . $this->depto);
	if (pg_numrows($result_endent) > 0) {
  	  db_fieldsmemory($result_endent,0,true);
	  global $j14_nome;
	  global $numero;
	  global $compl;
	  global $j13_descr;
	  
          $this->objpdf->sety($xlin+24);
  	  $posicao_atual=$this->objpdf->gety();
	  $this->objpdf->Setfont('Arial','B',8);
	  $this->objpdf->multicell(202,4,"$texto1",1);
	  $this->objpdf->multicell(202,4,"ENDERECO DE ENTREGA: $j14_nome, $numero - $compl\nBAIRRO: $j13_descr",1);
	  $posicao_depois=$this->objpdf->gety();
	  $xlin+=$posicao_depois-$posicao_atual+2;

	}
	
          $this->objpdf->sety($xlin+24);
	  $posicao_atual=$this->objpdf->gety();
	  $this->objpdf->multicell(202,4,"PRAZO DE ENTREGA: " . $this->prazoent. " DIAS A CONTAR DA DATA DO RECEBIMENTO DESTA ORDEM DE COMPRA",1);
	  $this->objpdf->multicell(202,4,"CONDICOES DE PAGAMENTO: ". pg_result($this->recorddositens,0,$this->condpag),1);
	  $this->objpdf->multicell(202,4,"DESTINO: ". pg_result($this->recorddositens,0,$this->destino),1);
	  $posicao_depois=$this->objpdf->gety();
          $xlin+=$posicao_depois-$posicao_atual+2;

        if ($this->obs!=""){
          $this->objpdf->sety($xlin+24);
	  $posicao_atual=$this->objpdf->gety();
	  $this->objpdf->multicell(202,4,"OBSERVAÇÕES:  ".$this->obs,1);
	  $posicao_depois=$this->objpdf->gety();
          $xlin+=$posicao_depois-$posicao_atual+2;
	}
	
        $this->objpdf->sety($xlin+24);

	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->rect($xcol    ,$xlin+24,12,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 12,$xlin+24,15,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 27,$xlin+24,14,6,2,'DF','12');//$this->objpdf->rect($xcol+ 27,$xlin+24,11,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 41,$xlin+24,101,6,2,'DF','12');//$this->objpdf->rect($xcol+ 38,$xlin+24,104,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+24,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+24,30,6,2,'DF','12');

	$this->objpdf->rect($xcol    ,$xlin+30,12,205  -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+ 12,$xlin+30,15,205  -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+ 27,$xlin+30,14,205  -$xlin ,2,'DF','34'); //$this->objpdf->rect($xcol+ 27,$xlin+30,11,205  -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+ 41,$xlin+30,101,205 -$xlin ,2,'DF','34'); //$this->objpdf->rect($xcol+ 38,$xlin+30,104,205 -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+142,$xlin+30,30,205  -$xlin ,2,'DF','');
	$this->objpdf->rect($xcol+172,$xlin+30,30,205  -$xlin ,2,'DF','34');




//	$this->objpdf->rect($xcol,$xlin+182,142,23,2,'DF','');

	
   	$this->objpdf->sety($xlin+28);
	$alt = 4;
	
	$this->objpdf->text($xcol+   2,$xlin+28,'ITEM');
	$this->objpdf->text($xcol+12.5,$xlin+28,'EMPENHO');
	$this->objpdf->text($xcol+30.5,$xlin+28,'QUANT');  //$this->objpdf->text($xcol+27.5,$xlin+28,'QUANT');
	$this->objpdf->text($xcol+  67,$xlin+28,'MATERIAL OU SERVIÇO'); //$this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');
    $maiscol = 0;


    $this->objpdf->setfillcolor(0,0,0);
	$this->objpdf->text($xcol+10,290,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
        
	$this->objpdf->text($xcol+ 120,290,'___________________________________________');
	
	$this->objpdf->SetWidths(array(12,16,13,101,30,30));  //$this->objpdf->SetWidths(array(12,16,10,104,30,30));
	$this->objpdf->SetAligns(array('C','C','R','L','R','R'));
	
	$this->objpdf->setleftmargin(4);
	$this->objpdf->sety($xlin+32);

	$xtotal = 0;
    $item=1;
	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	     db_fieldsmemory($this->recorddositens,$ii);
	     $this->objpdf->Setfont('Arial','',8);
         $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->codmater),
	                           pg_result($this->recorddositens,$ii,$this->empempenho) . "/" . pg_result($this->recorddositens,$ii,$this->anousuemp),
	                           pg_result($this->recorddositens,$ii,$this->quantitem),
				   pg_result($this->recorddositens,$ii,$this->descricaoitem)."\n".pg_result($this->recorddositens,$ii,$this->observacaoitem),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->vlrunitem),'v'," ",$this->numdec),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	     $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
	     $item++;
	     $this->objpdf->Setfont('Arial','B',8);
 /////// troca de pagina
	  if( ( $this->objpdf->gety() > $this->objpdf->h - 85 && $pagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h - 40 && $pagina != 1 )){
            if ($this->objpdf->PageNo() == 1){
	       if ($this->obs!=""){
	         $this->objpdf->text(110,268-$xlin,'Continua na Página '.($pagina+1));
             //$this->objpdf->rect($xcol,$xlin+217,202,55,2,'DF','1234');
	       }else $this->objpdf->text(110,$xlin+243,'Continua na Página '.($pagina+1));
	       
            }else{
	       $this->objpdf->text(110,$xlin+320,'Continua na Página '.($pagina+1));
	    }
	    if($pagina == 1){
	      $xlin = 20;
	      $xcol = 4;
	      $this->objpdf->rect($xcol,    $xlin+205,142, 10,2,'DF','34');
	      $this->objpdf->rect($xcol+142,$xlin+205,30, 10,2,'DF','34');
	      $this->objpdf->rect($xcol+172,$xlin+205,30, 10,2,'DF','34');
	      $this->objpdf->text($xcol+100 ,$xlin+211,'T O T A L   D A   P Á G I N A');

	      $this->objpdf->SetXY(172,$xlin+205);
	      $this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");   

	      $this->objpdf->SetXY(4,$xlin+217);

	      $this->objpdf->multicell(202,4,$texto2,1);
	    }
            $this->objpdf->addpage();
            $pagina += 1;	   
	    
  	    $this->objpdf->settopmargin(1);
	    $xlin = 20;
	    $xcol = 4;
	
	    $this->objpdf->setfillcolor(245);
	    $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	    $this->objpdf->setfillcolor(255,255,255);
	    $this->objpdf->Setfont('Arial','B',9);
	    $this->objpdf->text(130,$xlin-13,'ORDEM DE COMPRA N'.CHR(176));
	    $this->objpdf->text(185,$xlin-13,db_formatar($this->numordem,'s','0',6,'e'));
	    $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	    $this->objpdf->Setfont('Arial','B',9);
	    $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	    $this->objpdf->Setfont('Arial','',9);
	    $this->objpdf->text(40,$xlin-11,$this->enderpref);
	    $this->objpdf->text(40,$xlin-8,$this->municpref);
	    $this->objpdf->text(40,$xlin-5,$this->telefpref);
	    $this->objpdf->text(40,$xlin-2,$this->emailpref);
	    
        $xlin = -30;
	    $this->objpdf->Setfont('Arial','B',8);

	$this->objpdf->rect($xcol    ,$xlin+54,12,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 12,$xlin+54,15,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 27,$xlin+54,14,6,2,'DF','12'); //$this->objpdf->rect($xcol+ 27,$xlin+54,11,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 41,$xlin+54,101,6,2,'DF','12'); //$this->objpdf->rect($xcol+ 38,$xlin+54,104,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	$this->objpdf->rect($xcol,    $xlin+60,12,252,2,'DF','34');
	$this->objpdf->rect($xcol+ 12,$xlin+60,15,252,2,'DF','34');
	$this->objpdf->rect($xcol+ 27,$xlin+60,14,252,2,'DF','34'); //$this->objpdf->rect($xcol+ 27,$xlin+60,11,252,2,'DF','34');
	$this->objpdf->rect($xcol+ 41,$xlin+60,101,252,2,'DF','34'); //$this->objpdf->rect($xcol+ 38,$xlin+60,104,252,2,'DF','34');
	$this->objpdf->rect($xcol+142,$xlin+60,30,252,2,'DF','');
	$this->objpdf->rect($xcol+172,$xlin+60,30,252,2,'DF','34');

	    $this->objpdf->sety($xlin+66);
	    $alt = 4;

	    $this->objpdf->text($xcol+   2,$xlin+59,'ITEM');
	    $this->objpdf->text($xcol+12.5,$xlin+59,'EMPENHO');
	    $this->objpdf->text($xcol+30.5,$xlin+59,'QUANT');
	    $this->objpdf->text($xcol+  70,$xlin+59,'MATERIAL OU SERVIÇO');
	    $this->objpdf->text($xcol+ 145,$xlin+59,'VALOR UNITÁRIO');
	    $this->objpdf->text($xcol+ 176,$xlin+59,'VALOR TOTAL');
	    $this->objpdf->text($xcol+  43,$xlin+63,'Continuação da Página '.($pagina-1));

	    $maiscol = 0;

	  }
	
	}
	if($pagina == 1){
	  $xlin = 20;
	  $xcol = 4;
	  $this->objpdf->rect($xcol,    $xlin+205,142, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+205,30, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+205,30, 10,2,'DF','34');
	  $this->objpdf->text($xcol+100 ,$xlin+211,'T O T A L   G E R A L');

	  $this->objpdf->SetXY(172,$xlin+205);
	  $this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");   

	  $this->objpdf->SetXY(4,$xlin+217);

	  $this->objpdf->multicell(202,4,$texto2,1);
	}else{
	  $this->objpdf->rect($xcol    ,$xlin+312,12,10,2,'DF','34');
	  $this->objpdf->rect($xcol+ 12,$xlin+312,15,10,2,'DF','34');
	  $this->objpdf->rect($xcol+ 27,$xlin+312,14,10,2,'DF','34');
	  $this->objpdf->rect($xcol+ 41,$xlin+312,101,10,2,'DF','34'); //$this->objpdf->rect($xcol+ 38,$xlin+312,104,10,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+312,30,10,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+312,30,10,2,'DF','34');

/*
	  $this->objpdf->rect($xcol,    $xlin+295,142, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+295,30, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+295,30, 10,2,'DF','34');
*/
	  $this->objpdf->text($xcol+100 ,$xlin+319,'T O T A L   G E R A L');
	  $this->objpdf->text($xcol+172 ,$xlin+319,db_formatar($xtotal,'f'));
	}
//	$this->objpdf->multicell(202,4,"A) PARA INFORMAÇÕES SOBRE O PRESENTE ORDEM, FAVOR ENTRAR EM CONTATO COM MARA, PELO TELEFONE (055) 3961 1616, OU EM NOSSA SEDE: MAJOR JOÃO CEZIMBRA JACQUES, 200\n",1);
//	$this->objpdf->multicell(202,4,"B) AS NOTAS FISCAIS DEVEM SER ENCAMMINHADAS AO SETOR DE ALMOXARIFADO CENTRAL - CAM, EM 2 VIAS COM NUMERO DE EMPENHO E CONTA BANCARIA.",1);
//	$this->objpdf->multicell(202,4,"NAO SERAO ACEITAS NOTAS FISCAIS CONTENDO ITENS DE MAIS DE UMA ORDEM DE COMPRA",1);
//	$this->objpdf->multicell(202,4,"OS PRODUTOS DEVERAO SER ENTREGUES NO ALMOXARIFADO CENTRAL - CAM - CENTRO ADM MUNICIPAL NO PRAZO MAXIMO DE " . $this->prazoent. " DIAS A CONTAR DA DATA DO RECEBIMENTO DESTA ORDEM DE COMPRA",1);
	$posicao_depois=$this->objpdf->gety();
    $xlin+=$posicao_depois-$posicao_atual+2;

?>