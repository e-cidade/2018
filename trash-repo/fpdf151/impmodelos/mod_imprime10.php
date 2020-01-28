<?php
  global $resparag, $resparagpadrao, $db61_texto, $db02_texto;

  $dist = 4;
  
  $this->objpdf->SetAutoPageBreak(false);
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

  $tamdepto = strlen(trim($this->descrdepto));
  
  $linpc = $tamdepto>20?1:4;

  $this->objpdf->text(130, $xlin - 7, 'DEPTO. ORIGEM :');
  $this->objpdf->text(155, $xlin - 7, substr($this->sOrigem,0,35));
  
  $this->objpdf->text(130,$xlin-4,'DEPTO. DESTINO :');
  $this->objpdf->text(155,$xlin-4,substr($this->coddepto." - ".$this->descrdepto,0,35));
  
  $this->objpdf->text(130, ($xlin+2.5) - $linpc,'PROCESSO DE COMPRA N'.CHR(176));
  $this->objpdf->text(165, ($xlin+2.5) - $linpc,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));
	
  $this->objpdf->text(130, ($xlin+$linpc-3), 'TIPO DA COMPRA: ');
  $this->objpdf->text(165, ($xlin+$linpc-3) , db_formatar(pg_result($this->recorddositens, 0, 
                                                 $this->sTipoCompra), 's' , '0', 6, 'e'));
	

	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12); 
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 7,"FONE: " . $this->telefpref);
	$this->objpdf->text(40,$xlin- 3,$this->emailpref);
	$this->objpdf->text(40,$xlin+1 ,$this->url . " - CNPJ:" . db_formatar($this->cgc,'cnpj'));
	$this->objpdf->text(40,$xlin+5 ,$this->inscricaoestadualinstituicao);

	$xlin = $xlin + 5;
	$this->objpdf->rect($xcol,$xlin+2,$xcol+198,20,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+4.5,'Dados do Fornecedor');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+110,$xlin+5,'E-mail');
	$this->objpdf->text($xcol+110,$xlin+8.5,'Numcgm');
	$this->objpdf->text($xcol+150,$xlin+8.5,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+  2,$xlin+8.5,'Nome');
	$this->objpdf->text($xcol+  2,$xlin+12.5,'Endere�o');
	$this->objpdf->text($xcol+110,$xlin+12.5,'N�mero');
	$this->objpdf->text($xcol+150,$xlin+12.5,'Complemento');
	$this->objpdf->text($xcol+  2,$xlin+16,'Munic�pio');
	$this->objpdf->text($xcol+110,$xlin+16,'Bairro');
	$this->objpdf->text($xcol+150,$xlin+16,'CEP');
	$this->objpdf->text($xcol+  2,$xlin+20,'Contato');
	$this->objpdf->text($xcol+110,$xlin+20,'Telefone');
	$this->objpdf->text($xcol+150,$xlin+20,'FAX');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+122,$xlin+5,':  '.$this->email);
	$this->objpdf->text($xcol+158,$xlin+8.5,':  '.$this->cnpj);
	$this->objpdf->text($xcol+122,$xlin+8.5,':  '.$this->numcgm);
	$this->objpdf->text($xcol+18,$xlin+ 8.5,':  '.$this->nome);
	$this->objpdf->text($xcol+18,$xlin+ 12.5,':  '.$this->ender);
	$this->objpdf->text($xcol+122,$xlin+12.5,':  '.$this->numero);
	$this->objpdf->text($xcol+170,$xlin+12.5,':  '.$this->compl);
	$this->objpdf->text($xcol+18,$xlin+ 16,':  '.$this->munic.'-'.$this->ufFornecedor);
	$this->objpdf->text($xcol+122,$xlin+16,':  '.$this->bairro);
	$this->objpdf->text($xcol+165,$xlin+16,':  '.$this->cep);
	$this->objpdf->text($xcol+18,$xlin+ 20,':  '.$this->contato);
	$this->objpdf->text($xcol+122,$xlin+20,':  '.$this->telef_cont);
	$this->objpdf->text($xcol+158,$xlin+20,':  '.$this->telef_fax);

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
	
  $result_endent = db_query("select j14_nome as j14_nome_almox, numero as numero_almox, compl as compl_almox, 
        													 j13_descr as j13_descr_almox, fonedepto as fone_almox, ramaldepto as ramal_almox, 
        													 faxdepto as fax_almox
																	 from db_departender 
														inner join db_depart on db_depart.coddepto = db_departender.coddepto
														inner join ruas on j14_codigo = codlograd 
														inner join bairro on j13_codi = codbairro where db_departender.coddepto = " . $this->depto);
	if (pg_numrows($result_endent) > 0) {
  	
	  db_fieldsmemory($result_endent,0,true);
	  global $j14_nome_almox;
	  global $numero_almox;
	  global $compl_almox;
	  global $j13_descr_almox;
		global $fone_almox;
		global $ramal_almox;
		global $fax_almox;
	  
    $this->objpdf->sety($xlin+24);
  	$posicao_atual=$this->objpdf->gety();
	  $this->objpdf->Setfont('Arial','B',8);
	  $this->objpdf->multicell(202,4,"$texto1",1);
	  $this->objpdf->multicell(202,4,"ENDERECO DE ENTREGA: $j14_nome_almox, $numero_almox - $compl_almox\nBAIRRO: $j13_descr_almox\n" . ($fone_almox != ""?"FONE: $fone_almox - ":"") . ($ramal_almox != ""?"RAMAL: $ramal_almox - ":"") . ($fax_almox != ""?"FAX: $fax_almox":""),1);
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
    $this->objpdf->multicell(202,4,"OBSERVA��ES:  ".$this->obs,1);
    $posicao_depois=$this->objpdf->gety();
    $xlin+=$posicao_depois-$posicao_atual+2;
	}
	
  $this->objpdf->sety($xlin+24);

	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->rect($xcol    ,$xlin+24,12,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 12,$xlin+24,17,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 29,$xlin+24,13,6,2,'DF','12');//$this->objpdf->rect($xcol+ 27,$xlin+24,11,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 42,$xlin+24,101,6,2,'DF','12');//$this->objpdf->rect($xcol+ 38,$xlin+24,104,6,2,'DF','12');
	$this->objpdf->rect($xcol+143,$xlin+24,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+173,$xlin+24,30,6,2,'DF','12');

	$this->objpdf->rect($xcol    ,$xlin+30,12,205  -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+ 12,$xlin+30,17,205  -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+ 29,$xlin+30,13,205  -$xlin ,2,'DF','34'); //$this->objpdf->rect($xcol+ 27,$xlin+30,11,205  -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+ 42,$xlin+30,101,205 -$xlin ,2,'DF','34'); //$this->objpdf->rect($xcol+ 38,$xlin+30,104,205 -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+143,$xlin+30,30,205  -$xlin ,2,'DF','');
	$this->objpdf->rect($xcol+173,$xlin+30,30,205  -$xlin ,2,'DF','34');

  $this->objpdf->sety($xlin+28);
	$alt = 4;
	
	$this->objpdf->text($xcol+   2,$xlin+28,'ITEM');
	$this->objpdf->text($xcol+12.5,$xlin+28,'EMPENHO');
	$this->objpdf->text($xcol+30.5,$xlin+28,'QUANT');  //$this->objpdf->text($xcol+27.5,$xlin+28,'QUANT');
	$this->objpdf->text($xcol+  67,$xlin+28,'MATERIAL OU SERVI�O'); //$this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVI�O');
	$this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNIT�RIO');
	$this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');
  $maiscol = 0;

  $this->objpdf->setfillcolor(0,0,0);

  $sqlparag  = "select db02_texto ";
  $sqlparag .= "  from db_documento ";
  $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
  $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
  $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
  $sqlparag .= " where db03_tipodoc = 1502 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
        
  $resparag = @db_query($sqlparag);
        
  if (@pg_numrows($resparag) > 0) {
       
    db_fieldsmemory($resparag,0);
    eval($db02_texto);
  } else {

    $sqlparagpadrao  = "select db61_texto ";
    $sqlparagpadrao .= "  from db_documentopadrao ";
    $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
    $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
    $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
    $sqlparagpadrao .= " where db60_tipodoc = 1502 order by db62_ordem";
       
    $resparagpadrao = @db_query($sqlparagpadrao);
    if (@pg_numrows($resparagpadrao) > 0) {
         db_fieldsmemory($resparagpadrao,0);
         
         eval($db61_texto);
    }
  }
	
	$this->objpdf->SetWidths(array(12,16,13,101,30,30));  //$this->objpdf->SetWidths(array(12,16,10,104,30,30));
	$this->objpdf->SetAligns(array('C','C','R','L','R','R'));
	
	$this->objpdf->setleftmargin(4);
	$this->objpdf->sety($xlin+32);

	$xtotal    = 0;
  $item      = 1;
  $iVoltaImp = 0;
	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	  
	  db_fieldsmemory($this->recorddositens,$ii);
	  $this->objpdf->Setfont('Arial','',7);

	  $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem); 
    if (pg_result($this->recorddositens,$ii,$this->Snumero) != "") {
      $descricaoitem .= "\n\n".'SOLICITA��O: '.pg_result($this->recorddositens,$ii,$this->Snumero);
	  }

    $obsitem  = pg_result($this->recorddositens,$ii,$this->observacaoitem);
	  $obsitem .= "\n\n".pg_result($this->recorddositens,$ii,$this->obs_ordcom_orcamval);
    $sObsItem = $obsitem;

    //// troca de pagina
	  if( ($this->objpdf->gety() > $this->objpdf->h - 90 && $pagina == 1 ) 
	      || ( $this->objpdf->gety() > $this->objpdf->h - 50 && $pagina != 1 )) {
	        
	    $this->objpdf->Setfont('Arial','B',7);
      if ($this->objpdf->PageNo() == 1) {
        
	      if ($this->obs!="") {
	        
	        $this->objpdf->text(90,268-$xlin,'Continua na P�gina '.($pagina+1));
	      } else {
	        $this->objpdf->text(90,$xlin+243,'Continua na P�gina '.($pagina+1));
	      }
      } else {
	       $this->objpdf->text(110,$xlin+320,'Continua na P�gina '.($pagina+1));
	    }
	    if ($pagina == 1) {
	      
	      $xlin = 20;
	      $xcol = 4;
	      $this->objpdf->rect($xcol,    $xlin+205,143, 10,2,'DF','34');
	      $this->objpdf->rect($xcol+143,$xlin+205,30, 10,2,'DF','34');
	      $this->objpdf->rect($xcol+173,$xlin+205,30, 10,2,'DF','34');
	      $this->objpdf->text($xcol+100 ,$xlin+211,'T O T A L   D A   P � G I N A');

	      $this->objpdf->SetXY(173,$xlin+205);
	      $this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");   

	      $this->objpdf->SetXY(4,$xlin+217);

        if (isset($texto2) && trim($texto2) != ""){
	        $this->objpdf->multicell(202,4,$texto2,1);
        }		   
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
	    $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12); 
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
	    $this->objpdf->rect($xcol+ 12,$xlin+54,17,6,2,'DF','12');
	    $this->objpdf->rect($xcol+ 29,$xlin+54,13,6,2,'DF','12'); //$this->objpdf->rect($xcol+ 27,$xlin+54,11,6,2,'DF','12');
	    $this->objpdf->rect($xcol+ 42,$xlin+54,101,6,2,'DF','12'); //$this->objpdf->rect($xcol+ 38,$xlin+54,104,6,2,'DF','12');
	    $this->objpdf->rect($xcol+143,$xlin+54,30,6,2,'DF','12');
	    $this->objpdf->rect($xcol+173,$xlin+54,30,6,2,'DF','12');
      
	    $this->objpdf->rect($xcol,    $xlin+60,12,252,2,'DF','34');
	    $this->objpdf->rect($xcol+ 12,$xlin+60,17,252,2,'DF','34');
	    $this->objpdf->rect($xcol+ 29,$xlin+60,13,252,2,'DF','34'); //$this->objpdf->rect($xcol+ 27,$xlin+60,11,252,2,'DF','34');
	    $this->objpdf->rect($xcol+ 42,$xlin+60,101,252,2,'DF','34'); //$this->objpdf->rect($xcol+ 38,$xlin+60,104,252,2,'DF','34');
	    $this->objpdf->rect($xcol+143,$xlin+60,30,252,2,'DF','');
	    $this->objpdf->rect($xcol+173,$xlin+60,30,252,2,'DF','34');

	    $this->objpdf->sety($xlin+66);
	    $alt = 4;

	    $this->objpdf->text($xcol+   2,$xlin+59,'ITEM');
	    $this->objpdf->text($xcol+12.5,$xlin+59,'EMPENHO');
	    $this->objpdf->text($xcol+30.5,$xlin+59,'QUANT');
	    $this->objpdf->text($xcol+  70,$xlin+59,'MATERIAL OU SERVI�O');
	    $this->objpdf->text($xcol+ 145,$xlin+59,'VALOR UNIT�RIO');
	    $this->objpdf->text($xcol+ 176,$xlin+59,'VALOR TOTAL');
	    $this->objpdf->text($xcol+  43,$xlin+63,'Continua��o da P�gina '.($pagina-1));
       $this->objpdf->Setfont('Arial','',8);

	    $maiscol = 0;
	  }
    $controle = $item;
    $controle++;
    
    // Pega o ultimo item da pagina e testa se consegue imprimir tudo na mesma pagina
    if (($controle%7)==0) {
      
      if (strlen($obsitem) > 68) {
        $obsitem = substr($obsitem,0,68)." ...";
      }
    }
  
   if ($iVoltaImp == 0) {
     
     $this->objpdf->Row_multicell(array(pg_result($this->recorddositens,$ii,$this->codmater),
	                            pg_result($this->recorddositens,$ii,$this->empempenho)."/"
                             .pg_result($this->recorddositens,$ii,$this->anousuemp),
	                            pg_result($this->recorddositens,$ii,$this->quantitem),
				                      $descricaoitem."\n",
				                      db_formatar(pg_result($this->recorddositens,$ii,$this->vlrunitem),'v'," ",$this->numdec),
  			                      db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4,0,true);
     $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);                     
   }else if ($iVoltaImp == 1){
     $sObsItem = $sTextoaImprimir; //resto do texto 
   }
   
   if ((isset($sObsItem) && $obsitem != '' && $iVoltaImp == 0) || $iVoltaImp == 1){
     
     $this->objpdf->Setfont('Arial','',8);
     $iAlturaFinal = 80;
     if ($pagina != 1) {
       $iAlturaFinal = 30;
     }
     
     // Largura total do multicell
     $iWidthMulticell  = $this->objpdf->widths[4];
      
     // Consulta o total de linhas restantes
     $iLinhasRestantes = ((( $this->objpdf->h - 25 ) - $this->objpdf->GetY()) / $dist );
      
     // Consulta o total de linhas que ser� utilizado no multicelll
     $iLinhasMulticell = $this->objpdf->NbLines($iWidthMulticell,$sObsItem);
      
     // Verifica se o total de linhas utilizadas no multicell � maior que as linhas restantes
     if ( $iLinhasMulticell > $iLinhasRestantes ) {
     	 
     	// Total de carateres necess�rios para a impress�o at� o fim da p�gina
     	$iTotalCaract = ( $iWidthMulticell * $iLinhasRestantes );
     	$iLimitString = $iTotalCaract;
     	 
     	// Percorre o resumo do limite de caraceters at� um ponto que haja espa�o em branco para n�o quebre alguma palavra
     	for ($iInd = $iTotalCaract; $iInd < strlen($sObsItem); $iInd++) {
     		if ( $sObsItem{$iInd} == ' ') {
     			$iLimitString = $iInd;
     			break;
     		}
     	}
     	 
     	// Insere quebra no ponto informado
     	$sObsItem = substr($sObsItem,0,$iLimitString)."\n".substr($sObsItem,$iLimitString,strlen($sObsItem));
     }
     
     $sObsItem = $this->objpdf->Row_multicell(array('','','',stripslashes($sObsItem),'',''), 
                                              3, 
                                              false,
                                              5,
                                              0,
                                              true,
                                              true,
                                              3,
                                              ($this->objpdf->h - $iAlturaFinal)
                                              );                         
     if ($sObsItem != "") {
       
       $iVoltaImp       = 1;
       $sTextoaImprimir = $sObsItem;
       $ii--;
     } else {
     	$iVoltaImp = 0;
     }
    }   

	  $item++;
    
	}
	if($pagina == 1){
	  
	  $xlin = 20;
	  $xcol = 4;
	  $this->objpdf->rect($xcol,$xlin+205,143, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+143,$xlin+205,30, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+173,$xlin+205,30, 10,2,'DF','34');
	  $this->objpdf->text($xcol+100,$xlin+211,'T O T A L   G E R A L');

	  $this->objpdf->SetXY(173,$xlin+205);
	  $this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");   

	  $this->objpdf->SetXY(4,$xlin+217);

    if (isset($texto2) && trim($texto2) != ""){
 	    $this->objpdf->multicell(202,4,$texto2,1);
	  }
	}else{
	  
	  $this->objpdf->rect($xcol    ,$xlin+312,12,10,2,'DF','34');
	  $this->objpdf->rect($xcol+ 12,$xlin+312,17,10,2,'DF','34');
	  $this->objpdf->rect($xcol+ 29,$xlin+312,13,10,2,'DF','34');
	  $this->objpdf->rect($xcol+ 42,$xlin+312,101,10,2,'DF','34'); //$this->objpdf->rect($xcol+ 38,$xlin+312,104,10,2,'DF','34');
	  $this->objpdf->rect($xcol+143,$xlin+312,30,10,2,'DF','34');
	  $this->objpdf->rect($xcol+173,$xlin+312,30,10,2,'DF','34');

	  $this->objpdf->text($xcol+100 ,$xlin+319,'T O T A L   G E R A L');
	  $this->objpdf->text($xcol+173 ,$xlin+319,db_formatar($xtotal,'f'));
	}
	$posicao_depois=$this->objpdf->gety();
  $xlin+=$posicao_depois-$posicao_atual+2;
?>