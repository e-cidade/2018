<?php
global $resparag, $resparagpadrao, $db61_texto, $db02_texto;

if (!function_exists('addCaracter')){

	function addCaracter($sString, $sQuebra = "\n", $iLimite = 86){
		$aPalavras = explode(' ', $sString);
				
		$iTamanho = 0;
		$index    = 0;
		$aRetorno = array('');
		
		foreach($aPalavras as $sPalavra) {
		  $iTamanhoPalavra = strlen($sPalavra) + 1;
		
		  if( ( $iTamanho + $iTamanhoPalavra ) <= $iLimite ){
		
		    $aRetorno[$index] .= $sPalavra . ' ';
		    $iTamanho 			  += $iTamanhoPalavra;
		  } else {
		
		    $index += 1;
		    $iTamanho 		    = $iTamanhoPalavra;
		    $aRetorno[$index] = $sPalavra . ' ';
		  }
		}
		
		return implode ("$sQuebra",$aRetorno);
	}
	
}

$seq_item = 1;
$pagina = 1;
if (strtoupper(trim($this->municpref)) == 'GUAIBA') {

  $this->objpdf->AliasNbPages();
  $this->objpdf->AddPage();
  $this->objpdf->settopmargin(1);
  $xlin = 20;
  $xcol = 4;
  //Inserindo usuario e data no rodape
  $this->objpdf->Setfont('Arial', 'I', 6);
  $this->objpdf->text($xcol + 3, $xlin + 276, "Emissor: " . db_getsession("DB_login") . " Data: " . date("d/m/Y", db_getsession("DB_datausu")) . "");

  $this->objpdf->setfillcolor(245);
  $this->objpdf->rect($xcol - 2, $xlin - 18, 206, 292, 2, 'DF', '1234');
  $this->objpdf->setfillcolor(255, 255, 255);
  $this->objpdf->Setfont('Arial', 'B', 9);
  $this->objpdf->text(130, $xlin - 13, 'AUTORIZAÇÃO DE EMPENHO N' . CHR(176));
  $this->objpdf->text(185, $xlin - 13, db_formatar($this->numaut, 's', '0', 6, 'e'));

  if ($this->informa_adic == "PC") {
    $this->objpdf->text(137.5, $xlin - 8, 'PROCESSO DE COMPRA N' . CHR(176));
    $this->objpdf->text(185, $xlin - 8, db_formatar(pg_result($this->recorddositens, 0, $this->Snumeroproc), 's', '0', 6, 'e'));
  }

  if ($this->Scodemp != "") {
    $this->objpdf->text(137, $xlin - 3, 'EMPENHO N' . CHR(176));
    $this->objpdf->text(180, $xlin - 3, $this->Scodemp);
  }
  
  if (isset($this->iPlanoPacto) && $this->iPlanoPacto != "") {
     
    
    $this->objpdf->text(130,$xlin-2,'PLANO');
    $this->objpdf->text(180,$xlin-2,': '.substr($this->iPlanoPacto."-".$this->SdescrPacto,0,40));
  }
  $this->objpdf->Image('imagens/files/' . $this->logo, 15, $xlin - 17, 12);
  $this->objpdf->Setfont('Arial', 'B', 9);
  $this->objpdf->text(40, $xlin - 15, $this->prefeitura);
  $this->objpdf->Setfont('Arial', '', 7);
  $this->objpdf->text(40, $xlin - 12, $this->enderpref);
  $this->objpdf->text(40, $xlin - 9, $this->municpref);
  $this->objpdf->text(40, $xlin - 6, $this->telefpref);
  $this->objpdf->text(40, $xlin - 3, $this->emailpref);
  $this->objpdf->text(40, $xlin, db_formatar($this->cgcpref, 'cnpj'));

  $this->objpdf->rect($xcol, $xlin + 2, $xcol + 100, 33.2, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 6);
  $this->objpdf->text($xcol + 2, $xlin + 4, 'Dados da Compra');
  $this->objpdf->Setfont('Arial', 'B', 8);
  
  $this->objpdf->text($xcol + 2, $xlin + 7,    'Licitação');
  $this->objpdf->text($xcol + 2, $xlin + 10.5, 'Modalidade da Licitação');
  $this->objpdf->text($xcol + 2, $xlin + 14.5, 'Tipo de Compra');
  $this->objpdf->text($xcol + 2, $xlin + 18.5, 'Prazo de Entrega');
  $this->objpdf->text($xcol + 2, $xlin + 22.5, 'Observações');
  $this->objpdf->text($xcol + 2, $xlin + 26.5, 'Cond.de Pagto');
  $this->objpdf->text($xcol + 2, $xlin + 30.5, 'Outras Condições');
  $this->objpdf->text($xcol + 2, $xlin + 34.5, 'Proc. Administrativo');
  
  $this->objpdf->Setfont('Arial', '', 8);
  
  $this->objpdf->text($xcol + 35, $xlin + 7,    ':  ' . $this->edital_licitacao . '/' . $this->ano_licitacao);
  $this->objpdf->text($xcol + 35, $xlin + 10.5, ':  ' . $this->num_licitacao . '  -  ' . substr($this->descr_licitacao,0,30));
  $this->objpdf->text($xcol + 35, $xlin + 14.5, ':  ' . $this->descr_tipocompra);
  $this->objpdf->text($xcol + 35, $xlin + 18.5, ':  ' . stripslashes($this->prazo_ent));
  $this->objpdf->text($xcol + 35, $xlin + 22.5, ':  ' . stripslashes($this->obs));
  $this->objpdf->text($xcol + 35, $xlin + 26.5, ':  ' . stripslashes($this->cond_pag));
  $this->objpdf->text($xcol + 35, $xlin + 30.5, ':  ' . stripslashes($this->out_cond));
  $this->objpdf->text($xcol + 35, $xlin + 34.5, ':  ' . stripslashes($this->processoadministrativo));
  
  $this->objpdf->rect($xcol + 106, $xlin + 2, 96, 33.2, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 6);
  $this->objpdf->text($xcol + 110, $xlin + 4, 'Dados da Dotação');
  $this->objpdf->Setfont('Arial', 'B', 8);

  $this->objpdf->text($xcol + 108, $xlin + 8,    'Dotação');
  $this->objpdf->text($xcol + 108, $xlin + 11.5, 'Órgão');
  $this->objpdf->text($xcol + 108, $xlin + 14,   'Unidade');
  $this->objpdf->text($xcol + 108, $xlin + 17,   'Proj/Ativ');
  $this->objpdf->text($xcol + 108, $xlin + 20,   'Subfunção');
  $this->objpdf->text($xcol + 108, $xlin + 23,   'Prog.');
  $this->objpdf->text($xcol + 108, $xlin + 26,   'Elemento');
  $this->objpdf->text($xcol + 108, $xlin + 29,   'Recurso');
  $this->objpdf->text($xcol + 178, $xlin + 29,   'Reduz');
  $this->objpdf->text($xcol + 108, $xlin + 32,   'Destino');
  $this->objpdf->text($xcol + 108, $xlin + 34.5, "Característica Peculiar");

  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 122, $xlin + 8,    ' :  ' . $this->dotacao);
  $this->objpdf->text($xcol + 122, $xlin + 11.5, ' :  ' . db_formatar($this->orgao, 'orgao') . ' - ' . $this->descr_orgao);
  $this->objpdf->text($xcol + 122, $xlin + 14,   ' :  ' . substr(db_formatar($this->orgao, 'orgao') . db_formatar($this->unidade, 'unidade') . ' - ' . $this->descr_unidade,0,46));
  $this->objpdf->text($xcol + 122, $xlin + 17,   ' :  ' . substr(db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ,0,45));
  $this->objpdf->text($xcol + 122, $xlin + 20,   ' :  ' . $this->subfuncao . " - " . $this->descr_subfuncao);
  $this->objpdf->text($xcol + 122, $xlin + 23,   ' :  ' . $this->programa . " - " . $this->descr_programa);
  $this->objpdf->text($xcol + 122, $xlin + 26,   ' :  ' . $this->descrdotacao);
  $this->objpdf->text($xcol + 122, $xlin + 29,   ' :  ' . $this->recurso . ' - ' . $this->descr_recurso);
  $this->objpdf->text($xcol + 188, $xlin + 29,   ' :  ' . $this->coddot . '-' . db_CalculaDV($this->coddot));
  $this->objpdf->text($xcol + 122, $xlin + 32,   ' :  ' . stripslashes($this->destino));
  $this->objpdf->text($xcol + 138, $xlin + 34.5, ' :  ' . $this->cod_concarpeculiar . " - " . $this->descr_concarpeculiar);

  $this->objpdf->rect($xcol, $xlin + 36, $xcol + 198, 20, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 6);
  $this->objpdf->text($xcol + 2, $xlin + 38, 'Dados do Credor');
  $this->objpdf->Setfont('Arial', 'B', 8);
  $this->objpdf->text($xcol + 110, $xlin + 42, 'Numcgm');
  $this->objpdf->text($xcol + 150, $xlin + 42, (strlen($this->cnpj) == 11 ? 'CPF' : 'CNPJ'));
  $this->objpdf->text($xcol + 2, $xlin + 42, 'Nome');
  $this->objpdf->text($xcol + 2, $xlin + 45, 'Endereço');
  $this->objpdf->text($xcol + 110, $xlin + 45, 'Numero');
  $this->objpdf->text($xcol + 150, $xlin + 45, 'Complemento');
  $this->objpdf->text($xcol + 2, $xlin + 48, 'Município');
  $this->objpdf->text($xcol + 110, $xlin + 48, 'Bairro');
  $this->objpdf->text($xcol + 150, $xlin + 48, 'CEP');
  $this->objpdf->text($xcol + 2, $xlin + 51, 'Contato');
  $this->objpdf->text($xcol + 110, $xlin + 51, 'Telefone');
  $this->objpdf->text($xcol + 150, $xlin + 51, 'FAX');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 159, $xlin + 42, ':  ' . (strlen($this->cnpj) == 11 ? db_formatar($this->cnpj, 'cpf') : db_formatar($this->cnpj, 'cnpj')));
  $this->objpdf->text($xcol + 122, $xlin + 42, ':  ' . $this->numcgm);
  $this->objpdf->text($xcol + 18, $xlin + 42, ':  ' . $this->nome);
  $this->objpdf->text($xcol + 18, $xlin + 45, ':  ' . $this->ender);
  $this->objpdf->text($xcol + 122, $xlin + 45, ':  ' . $this->numero);
  $this->objpdf->text($xcol + 170, $xlin + 45, ':  ' . $this->compl);
  $this->objpdf->text($xcol + 18, $xlin + 48, ":  {$this->munic} - {$this->ufFornecedor}");
  $this->objpdf->text($xcol + 122, $xlin + 48, ':  ' . $this->bairro);
  $this->objpdf->text($xcol + 170, $xlin + 48, ':  ' . $this->cep);
  $this->objpdf->text($xcol + 18, $xlin + 51, ':  ' . $this->contato);
  $this->objpdf->text($xcol + 122, $xlin + 51, ':  ' . $this->telefone);
  $this->objpdf->text($xcol + 170, $xlin + 51, ':  ' . $this->fax);

  $this->objpdf->Setfont('Arial', 'B', 8);

  $this->objpdf->rect($xcol, $xlin + 58, 8, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 8, $xlin + 58, 12, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 20, $xlin + 58, 15, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 35, $xlin + 58, 107, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 142, $xlin + 58, 30, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 172, $xlin + 58, 30, 6, 2, 'DF', '12');

  $this->objpdf->rect($xcol, $xlin + 64, 8, 118, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 8, $xlin + 64, 12, 118, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 20, $xlin + 64, 15, 118, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 35, $xlin + 64, 107, 118, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 142, $xlin + 64, 30, 151, 2, 'DF', '');
  $this->objpdf->rect($xcol + 172, $xlin + 64, 30, 151, 2, 'DF', '34');

  $this->objpdf->rect($xcol, $xlin + 205, 142, 10, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 142, $xlin + 205, 30, 10, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 172, $xlin + 205, 30, 10, 2, 'DF', '34');
  $this->objpdf->text($xcol + 120, $xlin + 211, 'T O T A L');

  $this->objpdf->SetXY(172, $xlin + 205);
  $this->objpdf->cell(30, 10, db_formatar($this->valtotal, 'f'), 0, 0, "R");

  $this->objpdf->rect($xcol, $xlin + 182, 142, 23, 2, 'DF', '');

  $this->objpdf->sety($xlin + 28);
  $alt = 4;

  $this->objpdf->text($xcol + 1, $xlin + 62, 'SEQ.');
  $this->objpdf->text($xcol + 10, $xlin + 62, 'ITEM');
  $this->objpdf->text($xcol + 22, $xlin + 62, 'QUANT.');
  $this->objpdf->text($xcol + 70, $xlin + 62, 'MATERIAL OU SERVIÇO');
  $this->objpdf->text($xcol + 145, $xlin + 62, 'VALOR UNITÁRIO');
  $this->objpdf->text($xcol + 176, $xlin + 62, 'VALOR TOTAL');
  $maiscol = 0;

  $this->objpdf->SetWidths(array (

        9, 
        10, 
        17, 
        105, 
        30, 
        30 
        ));
  $this->objpdf->SetAligns(array (

        'C', 
        'C', 
        'C', 
        'L', 
        'R', 
        'R' 
        ));

  $this->objpdf->setleftmargin(4);
  $this->objpdf->sety($xlin + 65);
  $ele = 0;
  $xtotal = 0;

  $retorna_obs = 0;
  for($ii = 0; $ii < $this->linhasdositens; $ii ++) {
    db_fieldsmemory($this->recorddositens, $ii);
    if ($retorna_obs == 0) {
      if ($this->usa_sub == 'f') {
        $this->objpdf->Setfont('Arial', 'B', 7);
        if ($ele != pg_result($this->recorddositens, $ii, $this->analitico)) {
          $this->objpdf->cell(36, 4, '', 0, 0, "C", 0);
          $this->objpdf->cell(137, 4, db_formatar(pg_result($this->recorddositens, $ii, $this->analitico), 'elemento') . ' - ' . pg_result($this->recorddositens, $ii, $this->descr_analitico), 0, 1, "L", 0);
          $ele = pg_result($this->recorddositens, $ii, $this->analitico);
        }
      }

      $descricaoitem = pg_result($this->recorddositens, $ii, $this->descricaoitem);

      if ($this->informa_adic == "PC") {
        if (pg_result($this->recorddositens, $ii, $this->Snumero) != "") {
          $descricaoitem .= "\n" . 'SOLICITAÇÃO: ' . pg_result($this->recorddositens, $ii, $this->Snumero);
        }
      }
      if (pg_result($this->recorddositens, $ii, 'e56_orctiporec') != "") {
        $descricaoitem .= "\n" . 'CP: ' . pg_result($this->recorddositens, $ii, 'e56_orctiporec');
      }

      $this->objpdf->Row(array (

            db_formatar($seq_item, "s", "0", (strlen($seq_item) + 1), "e", 0), 
            pg_result($this->recorddositens, $ii, $this->item), 
            pg_result($this->recorddositens, $ii, $this->quantitem), 
            $descricaoitem, 
            db_formatar(pg_result($this->recorddositens, $ii, $this->valor), 'v', " ", $this->casadec), 
            db_formatar(pg_result($this->recorddositens, $ii, $this->valoritem), 'f') 
            ), 3, false, 4, 0, true);
      $seq_item ++;
      $xtotal += pg_result($this->recorddositens, $ii, $this->valoritem);

      if ($this->observacaoitem == "pc23_obs") {
        $obsitem = pg_result($this->recorddositens, $ii, $this->resumo_item);
        $obsitem .= "\n\n" . pg_result($this->recorddositens, $ii, $this->observacaoitem);
      } else {
        $obsitem = pg_result($this->recorddositens, $ii, $this->observacaoitem);
      }
			
    } else {
      $retorna_obs = 0;
    }

    $seta_altura_pagina_row = $this->objpdf->h - 110;
    if ($pagina != 1) {
      $seta_altura_pagina_row = $this->objpdf->h - 40;
    }

    $obsitem = str_replace("\\n", "\n", $obsitem);
    
    $this->objpdf->Setfont('Arial', '', 7);
    
    if(count(split("\n", $obsitem)) <= 3 &&  strlen($obsitem) > 2000){ //Texto em bloco
    	$obsitem = addCaracter($obsitem, "\n", 86);
    }

    $obsitem = $this->objpdf->Row_multicell(array('','','', $obsitem), 3, false, 5, 0, true, true, 3, $seta_altura_pagina_row, null, null);

    if (trim($obsitem) != "") {
      $retorna_obs = 1;
      $ii --;
    }

    $this->objpdf->Setfont('Arial', 'B', 8);

    /////// troca de pagina


    if (($this->objpdf->gety() > $this->objpdf->h - 100 && $pagina == 1) || ($this->objpdf->gety() > $this->objpdf->h - 30 && $pagina != 1)) {
    	
				  if ($pagina == 1) {
				    $this->objpdf->setxy($xcol + 1, $xlin + 187);
				    $this->objpdf->text($xcol + 2, $xlin + 186, 'RESUMO : ', 0, 1, 'L', 0);
				    $this->objpdf->Setfont('Arial', '', 7);
				    $this->objpdf->multicell(140, 3, $this->resumo);
				    $this->objpdf->Setfont('Arial', 'B', 8);
				  }
				
				  if ($pagina == 1) {
				    // Assinatura documento    
				
				
				    $sqlparag = "select db02_texto ";
				    $sqlparag .= "  from db_documento ";
				    $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
				    $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
				    $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
				    $sqlparag .= " where db03_tipodoc = 1503 and db03_instit = " . db_getsession("DB_instit") . " order by db04_ordem ";
				
				    $resparag = @db_query($sqlparag);
				
				    if (@pg_numrows($resparag) > 0) {
				      db_fieldsmemory($resparag, 0);
				
				      eval($db02_texto);
				    } else {
				      $sqlparagpadrao = "select db61_texto ";
				      $sqlparagpadrao .= "  from db_documentopadrao ";
				      $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
				      $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
				      $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
				      $sqlparagpadrao .= " where db60_tipodoc = 1503 order by db62_ordem";
				
				      $resparagpadrao = @db_query($sqlparagpadrao);
				      if (@pg_numrows($resparagpadrao) > 0) {
				        db_fieldsmemory($resparagpadrao, 0);
				
				        eval($db61_texto);
				      }
				    }
				  }    	

      if ($this->objpdf->PageNo() == 1) {

        $this->objpdf->text(110, $xlin + 214, 'Continua na Página ' . ($this->objpdf->PageNo() + 1));

        // Assinatura documento    


        $sqlparag = "select db02_texto ";
        $sqlparag .= "  from db_documento ";
        $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
        $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
        $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
        $sqlparag .= " where db03_tipodoc = 1503 and db03_instit = " . db_getsession("DB_instit") . " order by db04_ordem ";

        $resparag = @db_query($sqlparag);

        if (@pg_numrows($resparag) > 0) {
          db_fieldsmemory($resparag, 0);

          eval($db02_texto);
        } else {
          $sqlparagpadrao = "select db61_texto ";
          $sqlparagpadrao .= "  from db_documentopadrao ";
          $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
          $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
          $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
          $sqlparagpadrao .= " where db60_tipodoc = 1503 order by db62_ordem";

          $resparagpadrao = @db_query($sqlparagpadrao);
          if (@pg_numrows($resparagpadrao) > 0) {
            db_fieldsmemory($resparagpadrao, 0);

            eval($db61_texto);
          }
        }

        if ($pagina == 1) {
          $this->objpdf->setxy($xcol + 1, $xlin + 187);
          $this->objpdf->text($xcol + 2, $xlin + 186, 'RESUMO : ', 0, 1, 'L', 0);
          $this->objpdf->Setfont('Arial', '', 7);
          $this->objpdf->multicell(140, 3, $this->resumo);
          $this->objpdf->Setfont('Arial', 'B', 8);
        }

      } else {
        $this->objpdf->text(110, $xlin + 320, 'Continua na Página ' . ($this->objpdf->PageNo() + 1));
      }
      $this->objpdf->addpage();
      $pagina += 1;

      $this->objpdf->settopmargin(1);
      $xlin = 20;
      $xcol = 4;
      //Inserindo usuario e data no rodape
      $this->objpdf->Setfont('Arial', 'I', 6);
      $this->objpdf->text($xcol + 3, $xlin + 276, "Emissor: " . db_getsession("DB_login") . " Data: " . date("d/m/Y", db_getsession("DB_datausu")) . "");

      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol - 2, $xlin - 18, 206, 292, 2, 'DF', '1234');
      $this->objpdf->setfillcolor(255, 255, 255);
      $this->objpdf->Setfont('Arial', 'B', 9);
      $this->objpdf->text(130, $xlin - 13, 'AUTORIZAÇÃO DE EMPENHO N' . CHR(176));
      $this->objpdf->text(185, $xlin - 13, db_formatar($this->numaut, 's', '0', 6, 'e'));

      if ($this->informa_adic == "PC") {
        $this->objpdf->text(137.5, $xlin - 8, 'PROCESSO DE COMPRA N' . CHR(176));
        $this->objpdf->text(185, $xlin - 8, db_formatar(pg_result($this->recorddositens, 0, $this->Snumeroproc), 's', '0', 6, 'e'));
      }

      $this->objpdf->Image('imagens/files/' . $this->logo, 15, $xlin - 17, 12);
      $this->objpdf->Setfont('Arial', 'B', 9);
      $this->objpdf->text(40, $xlin - 15, $this->prefeitura);
      $this->objpdf->Setfont('Arial', '', 9);
      $this->objpdf->text(40, $xlin - 11, $this->enderpref);
      $this->objpdf->text(40, $xlin - 8, $this->municpref);
      $this->objpdf->text(40, $xlin - 5, $this->telefpref);
      $this->objpdf->text(40, $xlin - 2, $this->emailpref);

      $xlin = - 30;
      $this->objpdf->Setfont('Arial', 'B', 8);

      $this->objpdf->rect($xcol, $xlin + 58, 8, 6, 2, 'DF', '12');
      $this->objpdf->rect($xcol + 8, $xlin + 58, 12, 6, 2, 'DF', '12');
      $this->objpdf->rect($xcol + 20, $xlin + 58, 15, 6, 2, 'DF', '12');
      $this->objpdf->rect($xcol + 35, $xlin + 58, 107, 6, 2, 'DF', '12');
      $this->objpdf->rect($xcol + 142, $xlin + 58, 30, 6, 2, 'DF', '12');
      $this->objpdf->rect($xcol + 172, $xlin + 58, 30, 6, 2, 'DF', '12');

      $this->objpdf->rect($xcol, $xlin + 64, 8, 262, 2, 'DF', '34');
      $this->objpdf->rect($xcol + 8, $xlin + 64, 12, 262, 2, 'DF', '34');
      $this->objpdf->rect($xcol + 15, $xlin + 64, 15, 262, 2, 'DF', '34');
      $this->objpdf->rect($xcol + 35, $xlin + 64, 107, 262, 2, 'DF', '34');
      $this->objpdf->rect($xcol + 142, $xlin + 64, 30, 262, 2, 'DF', '34');
      $this->objpdf->rect($xcol + 172, $xlin + 64, 30, 262, 2, 'DF', '34');

      $this->objpdf->sety($xlin + 68);
      $alt = 4;

      $this->objpdf->text($xcol + 1, $xlin + 62, 'SEQ.');
      $this->objpdf->text($xcol + 10, $xlin + 62, 'ITEM');
      $this->objpdf->text($xcol + 22, $xlin + 62, 'QUANT.');
      $this->objpdf->text($xcol + 70, $xlin + 62, 'MATERIAL OU SERVIÇO');
      $this->objpdf->text($xcol + 145, $xlin + 62, 'VALOR UNITÁRIO');
      $this->objpdf->text($xcol + 176, $xlin + 62, 'VALOR TOTAL');
      $this->objpdf->text($xcol + 38, $xlin + 67, 'Continuação da Página ' . ($this->objpdf->PageNo() - 1));

      $maiscol = 0;

    }

  }

  if ($pagina == 1) {
    $this->objpdf->setxy($xcol + 1, $xlin + 187);
    $this->objpdf->text($xcol + 2, $xlin + 186, 'RESUMO : ', 0, 1, 'L', 0);
    $this->objpdf->Setfont('Arial', '', 7);
    $this->objpdf->multicell(140, 3, $this->resumo);
    $this->objpdf->Setfont('Arial', 'B', 8);
  }

  if ($pagina == 1) {
    // Assinatura documento    


    $sqlparag = "select db02_texto ";
    $sqlparag .= "  from db_documento ";
    $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
    $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
    $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
    $sqlparag .= " where db03_tipodoc = 1503 and db03_instit = " . db_getsession("DB_instit") . " order by db04_ordem ";

    $resparag = @db_query($sqlparag);

    if (@pg_numrows($resparag) > 0) {
      db_fieldsmemory($resparag, 0);

      eval($db02_texto);
    } else {
      $sqlparagpadrao = "select db61_texto ";
      $sqlparagpadrao .= "  from db_documentopadrao ";
      $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
      $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
      $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
      $sqlparagpadrao .= " where db60_tipodoc = 1503 order by db62_ordem";

      $resparagpadrao = @db_query($sqlparagpadrao);
      if (@pg_numrows($resparagpadrao) > 0) {
        db_fieldsmemory($resparagpadrao, 0);

        eval($db61_texto);
      }
    }
  }

} else {

  // quando nao e guaiba


  $this->objpdf->AliasNbPages();
  $this->objpdf->AddPage();
  $this->objpdf->settopmargin(1);
  $xlin = 20;
  $xcol = 4;

  //Inserindo usuario e data no rodape


  $this->objpdf->Setfont('Arial', 'I', 6);
  $this->objpdf->text($xcol + 3, $xlin + 276, "Emissor : " . db_getsession("DB_login") . " Data: " . date("d/m/Y", db_getsession("DB_datausu")) . "");

  $this->objpdf->setfillcolor(245);
  $this->objpdf->rect($xcol - 2, $xlin - 18, 206, 292, 2, 'DF', '1234');
  $this->objpdf->setfillcolor(255, 255, 255);
  $this->objpdf->Setfont('Arial', 'B', 9);
  $this->objpdf->text(130, $xlin - 13, 'AUTORIZAÇÃO DE EMPENHO N' . CHR(176));
  $this->objpdf->text(185, $xlin - 13, db_formatar($this->numaut, 's', '0', 6, 'e'));

  if ($this->informa_adic == "PC") {
    $this->objpdf->text(137.5, $xlin - 8, 'PROCESSO DE COMPRA N' . CHR(176));
    $this->objpdf->text(185, $xlin - 8, db_formatar(pg_result($this->recorddositens, 0, $this->Snumeroproc), 's', '0', 6, 'e'));
  }

  if ($this->Scodemp != "") {
    $this->objpdf->text(137, $xlin - 3, 'EMPENHO N' . CHR(176));
    $this->objpdf->text(180, $xlin - 3, $this->Scodemp);
  }
  if (isset($this->iPlanoPacto) && $this->iPlanoPacto != "") {
     
    $this->objpdf->Setfont('Arial', 'B', 8);  
    $this->objpdf->text(130,$xlin,'PLANO N'. CHR(176));
    $this->objpdf->text(145,$xlin,' '.substr($this->iPlanoPacto."-".$this->SdescrPacto,0,40));
  }
  $this->objpdf->Setfont('Arial', 'I', 5);
  $this->objpdf->Image('imagens/files/' . $this->logo, 15, $xlin - 17, 12);
  $this->objpdf->Setfont('Arial', 'B', 9);
  $this->objpdf->text(40, $xlin - 15, $this->prefeitura);
  $this->objpdf->Setfont('Arial', '', 7);
  $this->objpdf->text(40, $xlin - 12, $this->enderpref);
  $this->objpdf->text(40, $xlin - 9, $this->municpref);
  $this->objpdf->text(40, $xlin - 6, $this->telefpref);
  $this->objpdf->text(40, $xlin - 3, $this->emailpref);
  $this->objpdf->text(40, $xlin, db_formatar($this->cgcpref, 'cnpj'));

  $this->objpdf->rect($xcol, $xlin + 2, $xcol + 100, 33.2, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 6);
  $this->objpdf->text($xcol + 2, $xlin + 4, 'Dados da Compra');
  $this->objpdf->Setfont('Arial', 'B', 8);
  $this->objpdf->text($xcol + 2, $xlin + 7,    'Licitação');
  $this->objpdf->text($xcol + 2, $xlin + 10.5, 'Modalidade da Licitação');
  $this->objpdf->text($xcol + 2, $xlin + 14.5, 'Tipo de Compra');
  $this->objpdf->text($xcol + 2, $xlin + 18.5, 'Prazo de Entrega');
  $this->objpdf->text($xcol + 2, $xlin + 22.5, 'Observações');
  $this->objpdf->text($xcol + 2, $xlin + 26.5, 'Cond.de Pagto');
  $this->objpdf->text($xcol + 2, $xlin + 30.5, 'Outras Condições');
  $this->objpdf->text($xcol + 2, $xlin + 34.5, 'Proc. Administrativo');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 35, $xlin + 7,    ':  ' . $this->edital_licitacao . '/' . $this->ano_licitacao);
  $this->objpdf->text($xcol + 35, $xlin + 10.5, ':  ' . $this->num_licitacao . '  -  ' . $this->descr_licitacao);  
  $this->objpdf->text($xcol + 35, $xlin + 14.5, ':  ' . $this->descr_tipocompra);
  $this->objpdf->text($xcol + 35, $xlin + 18.5, ':  ' . stripslashes($this->prazo_ent));
  $this->objpdf->text($xcol + 35, $xlin + 22.5, ':  ' . stripslashes($this->obs));
  $this->objpdf->text($xcol + 35, $xlin + 26.5, ':  ' . stripslashes($this->cond_pag));
  $this->objpdf->text($xcol + 35, $xlin + 30.5, ':  ' . stripslashes($this->out_cond));
  $this->objpdf->text($xcol + 35, $xlin + 34.5, ':  ' . stripslashes($this->processoadministrativo));
  
  $this->objpdf->rect($xcol + 106, $xlin + 2, 96, 33.2, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 6);

  $this->objpdf->text($xcol + 110, $xlin + 4, 'Dados da Dotação');
  $this->objpdf->Setfont('Arial', 'B', 8);

  $this->objpdf->text($xcol + 108, $xlin + 8, 'Dotação');
  $this->objpdf->text($xcol + 108, $xlin + 11.5, 'Órgão');
  $this->objpdf->text($xcol + 108, $xlin + 14, 'Unidade');
  $this->objpdf->text($xcol + 108, $xlin + 17, 'Proj/Ativ');
  $this->objpdf->text($xcol + 108, $xlin + 20, 'Subfunção');
  $this->objpdf->text($xcol + 108, $xlin + 23, 'Prog.');
  $this->objpdf->text($xcol + 108, $xlin + 26, 'Elemento');
  $this->objpdf->text($xcol + 108, $xlin + 29, 'Recurso');
  $this->objpdf->text($xcol + 178, $xlin + 29, 'Reduz');
  $this->objpdf->text($xcol + 108, $xlin + 32, 'Destino');
  $this->objpdf->text($xcol + 108, $xlin + 34.5, "Característica Peculiar");
//Quebrar o nome da unidade para 48 caracteres (3 parametro) 

  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 122, $xlin + 8, ' :  ' . $this->dotacao);
  $this->objpdf->text($xcol + 122, $xlin + 11.5, ' :  ' . db_formatar($this->orgao, 'orgao') . ' - ' . $this->descr_orgao);
  $this->objpdf->text($xcol + 122, $xlin + 14,   ' :  ' . substr(db_formatar($this->orgao, 'orgao') . db_formatar($this->unidade, 'unidade') . ' - ' . $this->descr_unidade,0,46));
  $this->objpdf->text($xcol + 122, $xlin + 17, ' :  ' . substr(db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ,0,45));
  $this->objpdf->text($xcol + 122, $xlin + 20, ' :  ' . $this->subfuncao . " - " . $this->descr_subfuncao);
  $this->objpdf->text($xcol + 122, $xlin + 23, ' :  ' . $this->programa . " - " . $this->descr_programa);
  $this->objpdf->text($xcol + 122, $xlin + 26, ' :  ' . $this->descrdotacao);
  $this->objpdf->text($xcol + 122, $xlin + 29, ' :  ' . $this->recurso . ' - ' . $this->descr_recurso);
  $this->objpdf->text($xcol + 188, $xlin + 29, ' :  ' . $this->coddot . '-' . db_CalculaDV($this->coddot));
  $this->objpdf->text($xcol + 122, $xlin + 32, ' :  ' . stripslashes($this->destino));
  $this->objpdf->text($xcol + 138, $xlin + 34.5, ' :  ' . $this->cod_concarpeculiar . " - " . $this->descr_concarpeculiar);

  $this->objpdf->rect($xcol, $xlin + 36, $xcol + 198, 20, 2, 'DF', '1234');
  $this->objpdf->Setfont('Arial', '', 6);
  $this->objpdf->text($xcol + 2, $xlin + 38, 'Dados do Credor');
  $this->objpdf->Setfont('Arial', 'B', 8);
  $this->objpdf->text($xcol + 110, $xlin + 42, 'Numcgm');
  $this->objpdf->text($xcol + 150, $xlin + 42, (strlen($this->cnpj) == 11 ? 'CPF' : 'CNPJ'));
  $this->objpdf->text($xcol + 2, $xlin + 42, 'Nome');
  $this->objpdf->text($xcol + 2, $xlin + 45, 'Endereço');
  $this->objpdf->text($xcol + 110, $xlin + 45, 'Numero');
  $this->objpdf->text($xcol + 150, $xlin + 45, 'Complemento');
  $this->objpdf->text($xcol + 2, $xlin + 48, 'Município');
  $this->objpdf->text($xcol + 110, $xlin + 48, 'Bairro');
  $this->objpdf->text($xcol + 150, $xlin + 48, 'CEP');
  $this->objpdf->text($xcol + 2, $xlin + 51, 'Contato');
  $this->objpdf->text($xcol + 110, $xlin + 51, 'Telefone');
  $this->objpdf->text($xcol + 150, $xlin + 51, 'FAX');
  $this->objpdf->Setfont('Arial', '', 8);
  $this->objpdf->text($xcol + 159, $xlin + 42, ':  ' . (strlen($this->cnpj) == 11 ? db_formatar($this->cnpj, 'cpf') : db_formatar($this->cnpj, 'cnpj')));
  $this->objpdf->text($xcol + 122, $xlin + 42, ':  ' . $this->numcgm);
  $this->objpdf->text($xcol + 18, $xlin + 42, ':  ' . $this->nome);
  $this->objpdf->text($xcol + 18, $xlin + 45, ':  ' . $this->ender);
  $this->objpdf->text($xcol + 122, $xlin + 45, ':  ' . $this->numero);
  $this->objpdf->text($xcol + 170, $xlin + 45, ':  ' . $this->compl);
  $this->objpdf->text($xcol + 18, $xlin + 48, ':  ' . $this->munic . '-' . $this->ufFornecedor);
  $this->objpdf->text($xcol + 122, $xlin + 48, ':  ' . $this->bairro);
  $this->objpdf->text($xcol + 170, $xlin + 48, ':  ' . $this->cep);
  $this->objpdf->text($xcol + 18, $xlin + 51, ':  ' . $this->contato);
  $this->objpdf->text($xcol + 122, $xlin + 51, ':  ' . $this->telefone);
  $this->objpdf->text($xcol + 170, $xlin + 51, ':  ' . $this->fax);

  $this->objpdf->Setfont('Arial', 'B', 8);
  //	  $this->objpdf->Roundedrect($xcol,$xlin+54,202,80,2,'DF','1234')
  $this->objpdf->rect($xcol, $xlin + 58, 8, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 8, $xlin + 58, 12, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 20, $xlin + 58, 15, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 35, $xlin + 58, 107, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 142, $xlin + 58, 30, 6, 2, 'DF', '12');
  $this->objpdf->rect($xcol + 172, $xlin + 58, 30, 6, 2, 'DF', '12');

  $this->objpdf->rect($xcol, $xlin + 64, 8, 118, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 8, $xlin + 64, 12, 118, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 20, $xlin + 64, 15, 118, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 35, $xlin + 64, 107, 118, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 142, $xlin + 64, 30, 151, 2, 'DF', '');
  $this->objpdf->rect($xcol + 172, $xlin + 64, 30, 151, 2, 'DF', '34');

  $this->objpdf->rect($xcol, $xlin + 205, 142, 10, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 142, $xlin + 205, 30, 10, 2, 'DF', '34');
  $this->objpdf->rect($xcol + 172, $xlin + 205, 30, 10, 2, 'DF', '34');

  $this->objpdf->rect($xcol, $xlin + 182, 142, 23, 2, 'DF', '');

  $this->objpdf->sety($xlin + 28);
  $alt = 4;

  $this->objpdf->text($xcol + 1, $xlin + 62, 'SEQ.');
  $this->objpdf->text($xcol + 10, $xlin + 62, 'ITEM');
  $this->objpdf->text($xcol + 22, $xlin + 62, 'QUANT.');
  $this->objpdf->text($xcol + 70, $xlin + 62, 'MATERIAL OU SERVIÇO');
  $this->objpdf->text($xcol + 145, $xlin + 62, 'VALOR UNITÁRIO');
  $this->objpdf->text($xcol + 176, $xlin + 62, 'VALOR TOTAL');
  $maiscol = 0;

  $this->objpdf->SetWidths(array (8,12,15,105,30,30));

  $this->objpdf->SetAligns(array ('C','C','C','L','R','R'));

  $this->objpdf->setleftmargin(4);
  $this->objpdf->sety($xlin + 65);

  $ele = 0;
  $xtotal = 0;

  $retorna_obs = 0;
  
  for($ii = 0; $ii < $this->linhasdositens; $ii ++) {

    db_fieldsmemory($this->recorddositens, $ii);

    if ($retorna_obs == 0) {

      if ($this->usa_sub == 'f') {

        $this->objpdf->Setfont('Arial', 'B', 7);

        if ($ele != pg_result($this->recorddositens, $ii, $this->analitico)) {
          $this->objpdf->cell(36, 4, '', 0, 0, "C", 0);
          $this->objpdf->cell(137, 4, db_formatar(pg_result($this->recorddositens, $ii, $this->analitico), 'elemento') . ' - ' . pg_result($this->recorddositens, $ii, $this->descr_analitico), 0, 1, "L", 0);
          $ele = pg_result($this->recorddositens, $ii, $this->analitico);
        }

      }

      $descricaoitem = pg_result($this->recorddositens, $ii, $this->descricaoitem);

      if ($this->informa_adic == "PC") {
        if (pg_result($this->recorddositens, $ii, $this->Snumero) != "") {
          $descricaoitem .= "\n" . 'SOLICITAÇÃO: ' . pg_result($this->recorddositens, $ii, $this->Snumero);
        }
      }

      $this->objpdf->Setfont('Arial', '', 7);
      $this->objpdf->Row(array (

            db_formatar($seq_item, "s", "0", (strlen($seq_item) + 1), "e", 0), 
            pg_result($this->recorddositens, $ii, $this->item), 
            pg_result($this->recorddositens, $ii, $this->quantitem), 
            $descricaoitem, 
            db_formatar(pg_result($this->recorddositens, $ii, $this->valor), 'v', " ", $this->casadec), 
            db_formatar(pg_result($this->recorddositens, $ii, $this->valoritem), 'f') 
            ), 3, false, 4);

      $seq_item ++;

      $xtotal += pg_result($this->recorddositens, $ii, $this->valoritem);

      if ($this->observacaoitem == "pc23_obs") {
        $obsitem =   pg_result($this->recorddositens, $ii, $this->resumo_item);
        $obsitem .=  "\n".pg_result($this->recorddositens, $ii, $this->observacaoitem);
      } else {
        $obsitem = pg_result($this->recorddositens, $ii, $this->observacaoitem);
      }

    } else {
      $retorna_obs = 0;
    }

    $seta_altura_pagina_row = $this->objpdf->h - 110;

    if ($pagina != 1) {
      $seta_altura_pagina_row = $this->objpdf->h - 40;
    }
    
    $obsitem = str_replace("\\n", "\n", $obsitem);
    
    $this->objpdf->Setfont('Arial', '', 7);

    if(count(split("\n", $obsitem)) <= 3 && strlen($obsitem) > 2000){ //Texto em bloco
    	$obsitem = addCaracter($obsitem, "\n", 86);
    }
    
    $obsitem = $this->objpdf->Row_multicell(array('','','', $obsitem), 3, false, 5, 0, true, true, 3, $seta_altura_pagina_row, null, null);

    if (trim($obsitem) != "") {
      $retorna_obs = 1;
      $ii --;
    }

    $proximo = $ii;

    $this->objpdf->Setfont('Arial', 'B', 8);

    // troca de pagina


    if (($this->objpdf->gety() > $this->objpdf->h - 110 && $pagina == 1) || ($this->objpdf->gety() > $this->objpdf->h - 40 && $pagina != 1)) {
      if (($proximo + 1) < $this->linhasdositens) { // Alterado para controle de itens nao imprimir paginas sem itens(branco)
          if ($pagina == 1) {
            $this->objpdf->setxy($xcol + 1, $xlin + 187);
            $this->objpdf->text($xcol + 2, $xlin + 186, 'RESUMO: ', 0, 1, 'L', 0);
            $this->objpdf->Setfont('Arial', '', 7);
            $this->objpdf->multicell(140, 3, $this->resumo);
            $this->objpdf->Setfont('Arial', 'B', 8);
          } 

					  if ($pagina == 1) {
					
					    $this->objpdf->text($xcol + 120, $xlin + 211, 'T O T A L');
					    $this->objpdf->SetXY(172, $xlin + 205);
					    $this->objpdf->cell(30, 10, db_formatar($this->valtotal, 'f'), 0, 0, "R");
					
					    // Assinatura documento    
					
					
					    $sqlparag = "select db02_texto ";
					    $sqlparag .= "  from db_documento ";
					    $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
					    $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
					    $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
					    $sqlparag .= " where db03_tipodoc = 1503 and db03_instit = " . db_getsession("DB_instit") . " order by db04_ordem ";
					
					    $resparag = @db_query($sqlparag);
					
					    if (@pg_numrows($resparag) > 0) {
					      db_fieldsmemory($resparag, 0);
					
					      eval($db02_texto);
					
					    } else {
					      $sqlparagpadrao = "select db61_texto ";
					      $sqlparagpadrao .= "  from db_documentopadrao ";
					      $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
					      $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
					      $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
					      $sqlparagpadrao .= " where db60_tipodoc = 1503 order by db62_ordem";
					
					      $resparagpadrao = @db_query($sqlparagpadrao);
					      if (@pg_numrows($resparagpadrao) > 0) {
					        db_fieldsmemory($resparagpadrao, 0);
					
					        eval($db61_texto);
					      }
					    }
					  }          
          
        if ($this->objpdf->PageNo() == 1) {
          
          $this->objpdf->text(85, $xlin + 214, 'Continua na Página  ' . ($this->objpdf->PageNo() + 1));
          $this->objpdf->SetXY(172, $xlin + 205);
          //$this->objpdf->cell(30, 10, db_formatar($this->valtotal, 'f'), 0, 0, "R");


          // Assinatura documento    


          $sqlparag = "select db02_texto ";
          $sqlparag .= "  from db_documento ";
          $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
          $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
          $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
          $sqlparag .= " where db03_tipodoc = 1503 and db03_instit = " . db_getsession("DB_instit") . " order by db04_ordem ";

          $resparag = @db_query($sqlparag);

          if (@pg_numrows($resparag) > 0) {
            db_fieldsmemory($resparag, 0);

            eval($db02_texto);

          } else {

            $sqlparagpadrao = "select db61_texto ";
            $sqlparagpadrao .= "  from db_documentopadrao ";
            $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
            $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
            $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
            $sqlparagpadrao .= " where db60_tipodoc = 1503 order by db62_ordem";

            $resparagpadrao = @db_query($sqlparagpadrao);
            if (@pg_numrows($resparagpadrao) > 0) {
              db_fieldsmemory($resparagpadrao, 0);

              eval($db61_texto);
            }
          }

          if ($pagina == 1) {
            $this->objpdf->setxy($xcol + 1, $xlin + 187);
            $this->objpdf->text($xcol + 2, $xlin + 186, 'RESUMO: ', 0, 1, 'L', 0);
            $this->objpdf->Setfont('Arial', '', 7);
            $this->objpdf->multicell(140, 3, $this->resumo);
            $this->objpdf->Setfont('Arial', 'B', 8);
          }

        } else {
          $this->objpdf->text(85, $xlin + 320, 'Continua na Página ' . ($this->objpdf->PageNo() + 1));
        }

        $this->objpdf->addpage();
        $pagina += 1;

        $this->objpdf->settopmargin(1);
        $xlin = 20;
        $xcol = 4;
        //Inserindo usuario e data no rodape
        $this->objpdf->Setfont('Arial', 'I', 6);
        $this->objpdf->text($xcol + 3, $xlin + 276, "Emissor: " . db_getsession("DB_login") . " Data: " . date("d/m/Y", db_getsession("DB_datausu")) . "");

        $this->objpdf->setfillcolor(245);
        $this->objpdf->rect($xcol - 2, $xlin - 18, 206, 292, 2, 'DF', '1234');
        $this->objpdf->setfillcolor(255, 255, 255);
        $this->objpdf->Setfont('Arial', 'B', 9);
        $this->objpdf->text(130, $xlin - 13, 'AUTORIZAÇÃO DE EMPENHO N' . CHR(176));
        $this->objpdf->text(185, $xlin - 13, db_formatar($this->numaut, 's', '0', 6, 'e'));

        if ($this->informa_adic == "PC") {
          $this->objpdf->text(137.5, $xlin - 8, 'PROCESSO DE COMPRA N' . CHR(176));
          $this->objpdf->text(185, $xlin - 8, db_formatar(pg_result($this->recorddositens, 0, $this->Snumeroproc), 's', '0', 6, 'e'));
        }

        $this->objpdf->Image('imagens/files/' . $this->logo, 15, $xlin - 17, 12);
        $this->objpdf->Setfont('Arial', 'B', 9);
        $this->objpdf->text(40, $xlin - 15, $this->prefeitura);
        $this->objpdf->Setfont('Arial', '', 9);
        $this->objpdf->text(40, $xlin - 11, $this->enderpref);
        $this->objpdf->text(40, $xlin - 8, $this->municpref);
        $this->objpdf->text(40, $xlin - 5, $this->telefpref);
        $this->objpdf->text(40, $xlin - 2, $this->emailpref);

        $xlin = - 35;
        $this->objpdf->Setfont('Arial', 'B', 8);

        $this->objpdf->rect($xcol, $xlin + 59, 8, 6, 2, 'DF', '12');
        $this->objpdf->rect($xcol + 8, $xlin + 59, 12, 6, 2, 'DF', '12');
        $this->objpdf->rect($xcol + 20, $xlin + 59, 15, 6, 2, 'DF', '12');
        $this->objpdf->rect($xcol + 35, $xlin + 59, 107, 6, 2, 'DF', '12');
        $this->objpdf->rect($xcol + 142, $xlin + 59, 30, 6, 2, 'DF', '12');
        $this->objpdf->rect($xcol + 172, $xlin + 59, 30, 6, 2, 'DF', '12');

        $this->objpdf->rect($xcol, $xlin + 64, 8, 262, 2, 'DF', '34');
        $this->objpdf->rect($xcol + 8, $xlin + 64, 12, 262, 2, 'DF', '34');
        $this->objpdf->rect($xcol + 20, $xlin + 64, 15, 262, 2, 'DF', '34');
        $this->objpdf->rect($xcol + 35, $xlin + 64, 107, 262, 2, 'DF', '34');
        $this->objpdf->rect($xcol + 142, $xlin + 64, 30, 262, 2, 'DF', '34');
        $this->objpdf->rect($xcol + 172, $xlin + 64, 30, 262, 2, 'DF', '34');

        //$this->objpdf->rect($xcol, $xlin + 205, 142, 10, 2, 'DF', '34');
        //$this->objpdf->rect($xcol + 142, $xlin + 205, 30, 10, 2, 'DF', '34');
        //$this->objpdf->rect($xcol + 172, $xlin + 205, 30, 10, 2, 'DF', '34');


        $this->objpdf->sety($xlin + 70);

        $alt = 5;

        $this->objpdf->text($xcol + 1, $xlin + 62, 'SEQ.');
        $this->objpdf->text($xcol + 10, $xlin + 62, 'ITEM');
        $this->objpdf->text($xcol + 22, $xlin + 62, 'QUANT.');
        $this->objpdf->text($xcol + 70, $xlin + 62, 'MATERIAL OU SERVIÇO');
        $this->objpdf->text($xcol + 145, $xlin + 62, 'VALOR UNITÁRIO');
        $this->objpdf->text($xcol + 176, $xlin + 62, 'VALOR TOTAL');
        //$this->objpdf->text($xcol + 85, $xlin + 320, 'Continuação da Página ' . ($this->objpdf->PageNo() + 1));

        $alt = 4;
        $maiscol = 0;
      }
    }
  }
  if ($pagina == 1) {
    $this->objpdf->setxy($xcol + 1, $xlin + 187);
    $this->objpdf->text($xcol + 2, $xlin + 186, 'RESUMO : ', 0, 1, 'L', 0);
    $this->objpdf->Setfont('Arial', '', 7);
    
    $sResumo =  $this->resumo;

    $this->objpdf->multicell(140, 3, $sResumo); //stripslashes($this->resumo));
    
    $this->objpdf->Setfont('Arial', 'B', 8);
  }

  if ($pagina == 1) {

    $this->objpdf->text($xcol + 120, $xlin + 211, 'T O T A L');
    $this->objpdf->SetXY(172, $xlin + 205);
    $this->objpdf->cell(30, 10, db_formatar($this->valtotal, 'f'), 0, 0, "R");

    // Assinatura documento    


    $sqlparag = "select db02_texto ";
    $sqlparag .= "  from db_documento ";
    $sqlparag .= "       inner join db_docparag on db03_docum = db04_docum ";
    $sqlparag .= "       inner join db_tipodoc on db08_codigo  = db03_tipodoc ";
    $sqlparag .= "       inner join db_paragrafo on db04_idparag = db02_idparag ";
    $sqlparag .= " where db03_tipodoc = 1503 and db03_instit = " . db_getsession("DB_instit") . " order by db04_ordem ";

    $resparag = @db_query($sqlparag);

    if (@pg_numrows($resparag) > 0) {
      db_fieldsmemory($resparag, 0);

      eval($db02_texto);

    } else {
      $sqlparagpadrao = "select db61_texto ";
      $sqlparagpadrao .= "  from db_documentopadrao ";
      $sqlparagpadrao .= "       inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc ";
      $sqlparagpadrao .= "       inner join db_tipodoc         on db08_codigo   = db60_tipodoc ";
      $sqlparagpadrao .= "       inner join db_paragrafopadrao on db61_codparag = db62_codparag ";
      $sqlparagpadrao .= " where db60_tipodoc = 1503 order by db62_ordem";

      $resparagpadrao = @db_query($sqlparagpadrao);
      if (@pg_numrows($resparagpadrao) > 0) {
        db_fieldsmemory($resparagpadrao, 0);

        eval($db61_texto);
      }
    }
  } else {

    $this->objpdf->rect(4, 281, 202, 10, 2, 'DF', '34');
    $this->objpdf->text(131,287, 'T O T A L');
    $this->objpdf->text(187,287, db_formatar($this->valtotal, 'f'));

  }
}