<?php
if (strtoupper(trim($this->municpref)) == 'GUAIBA') {

       
	//$assinatura2 = 'CONTADORA';
	//$assinatura3 = 'JORGE ANTONIO POKORSKI';
	//$assinaturaprefeito = 'MANOEL STRINGHINI';
	
    // grupo do CONTADORIA - ASSINATURA 01
    $ass_cont_nome_01 = $this->assinatura(1005,"",'0');
    $ass_cont_cargo_01 = $this->assinatura(1005,"",'1');
    $ass_cont_info_01    = $this->assinatura(1005,"",'2');
    //  grupo do CONTADORIA - ASSINATURA 02
    $ass_cont_nome_02 = $this->assinatura(1002,"",'0');
    $ass_cont_cargo_02 = $this->assinatura(1002,"",'1');
    // grupo do PAGUE-SE
    $ass_pague_nome = $this->assinatura(1000,"",'0');
    $ass_pague_cargo = $this->assinatura(1000,"",'1');	 

	for ($xxx = 0; $xxx < $this->nvias; $xxx ++) {
		$this->objpdf->AliasNbPages();
		$this->objpdf->AddPage();
		$this->objpdf->settopmargin(1);
		$pagina = 1;
		$xlin = 20;
		$xcol = 4;
		//Inserindo usuario e data no rodape
		$this->objpdf->Setfont('Arial', 'I', 6);
		$this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y H:m")."");

		$this->objpdf->setfillcolor(245);
		$this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
		$this->objpdf->setfillcolor(255, 255, 255);
		$this->objpdf->Setfont('Arial', 'B', 10);
		$this->objpdf->text(128, $xlin -13, 'NOTA DE EMPENHO N'.CHR(176).': ');
		$this->objpdf->text(175, $xlin -13, db_formatar($this->codemp, 's', '0', 6, 'e'));
		$this->objpdf->text(134, $xlin -8, 'DATA DE EMISSÃO : ');
		$this->objpdf->text(175, $xlin -8, $this->emissao);
		$this->objpdf->Image('imagens/files/logo_boleto.png', 15, $xlin -17, 12); //.$this->logo
		$this->objpdf->Setfont('Arial', 'B', 9);
		$this->objpdf->text(40, $xlin -15, $this->prefeitura);
		$this->objpdf->Setfont('Arial', '', 7);
		$this->objpdf->text(40, $xlin -11, $this->enderpref);
		$this->objpdf->text(40, $xlin -8, $this->municpref);
		$this->objpdf->text(40, $xlin -5, $this->telefpref);
		$this->objpdf->text(40, $xlin -2, $this->emailpref);
		$this->objpdf->text(40, $xlin, db_formatar($this->cgcpref, 'cnpj'));

		/// retangulo dos dados da dotação
		$this->objpdf->rect($xcol, $xlin +2, $xcol +100, 50, 2, 'DF', '1234');
		$this->objpdf->Setfont('Arial', 'B', 8);
		$this->objpdf->text($xcol +2, $xlin +7, 'Órgao');
		$this->objpdf->text($xcol +2, $xlin +10.5, 'Unidade');
		$this->objpdf->text($xcol +2, $xlin +14, 'Função');
		$this->objpdf->text($xcol +2, $xlin +17.5, 'Subfunção');
		$this->objpdf->text($xcol +2, $xlin +21, 'Programa');

		$this->objpdf->text($xcol +2, $xlin +24.5, 'Proj/Ativ');
		$this->objpdf->text($xcol +2, $xlin +28, 'Rubrica');
		$this->objpdf->text($xcol +2, $xlin +35, 'Recurso');

		if ($this->banco != "") {
			$this->objpdf->text($xcol +2, $xlin +38.5, 'Banco');
			$this->objpdf->text($xcol +30, $xlin +38.5, 'Agencia:');
			$this->objpdf->text($xcol +60, $xlin +38.5, 'Conta:');
		}

		$this->objpdf->text($xcol +2, $xlin +42.5, 'Reduzido');
		$this->objpdf->text($xcol +2, $xlin +48, 'Licitação');

		$this->objpdf->Setfont('Arial', '', 8);
		$this->objpdf->text($xcol +17, $xlin +7, ':  '.db_formatar($this->orgao, 'orgao').' - '.$this->descr_orgao);
		$this->objpdf->text($xcol +17, $xlin +10.5, ':  '.db_formatar($this->unidade, 'unidade').' - '.$this->descr_unidade);
		$this->objpdf->text($xcol +17, $xlin +14, ':  '.db_formatar($this->funcao, 'funcao').' - '.$this->descr_funcao);
		$this->objpdf->text($xcol +17, $xlin +17.5, ':  '.db_formatar($this->subfuncao, 'subfuncao').' - '.$this->descr_subfuncao);
		$this->objpdf->text($xcol +17, $xlin +21, ':  '.db_formatar($this->programa, 'programa').' - '.$this->descr_programa);

		$this->objpdf->text($xcol +17, $xlin +24.5, ':  '.db_formatar($this->projativ, 'projativ').' - '.$this->descr_projativ);

		$this->objpdf->text($xcol +17, $xlin +28, ':  '.db_formatar($this->sintetico, 'elemento'));
		$this->objpdf->setxy($xcol +18, $xlin +29);
		$this->objpdf->multicell(90, 3, $this->descr_sintetico, 0, "L");

		$this->objpdf->text($xcol +17, $xlin +35, ':  '.$this->recurso.' - '.$this->descr_recurso);

		if ($this->banco != "") {
			$this->objpdf->text($xcol +17, $xlin +38.5, ':  '.$this->banco);
			$this->objpdf->text($xcol +47, $xlin +38.5, $this->agencia);
			$this->objpdf->text($xcol +77, $xlin +38.5, $this->conta);
		}

		$this->objpdf->text($xcol +17, $xlin +42.5, ':  '.$this->coddot);

		$this->objpdf->text($xcol +17, $xlin +48, ':  '.$this->descr_licitacao);

		//// retangulo dos dados do credor
		$this->objpdf->rect($xcol +106, $xlin +2, 96, 18, 2, 'DF', '1234');
		$this->objpdf->Setfont('Arial', '', 6);
		$this->objpdf->text($xcol +108, $xlin +4, 'Dados do Credor:');
		$this->objpdf->Setfont('Arial', 'B', 8);
		$this->objpdf->text($xcol +107, $xlin +7, 'Numcgm');
		$this->objpdf->text($xcol +140, $xlin +7, (strlen($this->cnpj) == 11 ? 'CPF' : 'CNPJ'));
		$this->objpdf->text($xcol +107, $xlin +11, 'Nome');
		$this->objpdf->text($xcol +107, $xlin +15, 'Endereço');
		$this->objpdf->text($xcol +107, $xlin +19, 'Município');
		$this->objpdf->Setfont('Arial', '', 8);
		$this->objpdf->text($xcol +124, $xlin +7, ': '.$this->numcgm);
		$this->objpdf->text($xcol +149, $xlin +7, ':  '. (strlen($this->cnpj) == 11 ? db_formatar($this->cnpj, 'cpf') : db_formatar($this->cnpj, 'cnpj')).'   Fone: '.$this->telefone);
		$this->objpdf->text($xcol +124, $xlin +11, ': '.$this->nome);
		$this->objpdf->text($xcol +124, $xlin +15, ': '.$this->ender.'  '.$this->compl);
		$this->objpdf->text($xcol +124, $xlin +19, ': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);

		///// retangulo dos valores
		$this->objpdf->rect($xcol +106, $xlin +21.5, 96, 9, 2, 'DF', '1234');
		$this->objpdf->rect($xcol +106, $xlin +32.0, 47, 9, 2, 'DF', '1234');
		$this->objpdf->rect($xcol +155, $xlin +32.0, 47, 9, 2, 'DF', '1234');
		$this->objpdf->rect($xcol +106, $xlin +42.5, 47, 9, 2, 'DF', '1234');
		$this->objpdf->rect($xcol +155, $xlin +42.5, 47, 9, 2, 'DF', '1234');
		$this->objpdf->Setfont('Arial', '', 6);
		$this->objpdf->text($xcol +108, $xlin +34.0, 'Valor Orçado');
		$this->objpdf->text($xcol +157, $xlin +34.0, 'Saldo Anterior');
		$this->objpdf->text($xcol +108, $xlin +44.5, 'Valor Empenhado');
		$this->objpdf->text($xcol +157, $xlin +44.5, 'Saldo Atual');
		$this->objpdf->Setfont('Arial', '', 8);
		$this->objpdf->text($xcol +108, $xlin +27, 'AUTORIZAÇÃO N'.chr(176).' '.db_formatar($this->numaut, 's', '0', 5, 'e'));
		$this->objpdf->text($xcol +150, $xlin +27, 'SEQ. DO EMPENHO N'.chr(176).' '.db_formatar($this->numemp, 's', '0', 6, 'e'));
		//	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
		$this->objpdf->text($xcol +130, $xlin +38.0, db_formatar($this->orcado, 'f'));
		$this->objpdf->text($xcol +180, $xlin +38.0, db_formatar($this->saldo_ant, 'f'));
		$this->objpdf->text($xcol +130, $xlin +47.5, db_formatar($this->empenhado, 'f'));
		$this->objpdf->text($xcol +180, $xlin +47.5, db_formatar($this->saldo_ant - $this->empenhado, 'f'));

		/// retangulo do corpo do empenho 
		$this->objpdf->rect($xcol, $xlin +60, 15, 100, 2, 'DF', '');
		$this->objpdf->rect($xcol +15, $xlin +60, 137, 100, 2, 'DF', '');
		$this->objpdf->rect($xcol +152, $xlin +60, 25, 123, 2, 'DF', '');
		$this->objpdf->rect($xcol +177, $xlin +60, 25, 123, 2, 'DF', '');
		$this->objpdf->rect($xcol, $xlin +160, 152, 23, 2, 'DF', ''); // resumo

		//// retangulos do titulo do corpo do empenho
		$this->objpdf->Setfont('Arial', 'B', 7);
		$this->objpdf->rect($xcol, $xlin +54, 15, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +15, $xlin +54, 137, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +152, $xlin +54, 25, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +177, $xlin +54, 25, 6, 2, 'DF', '12');

		//// título do corpo do empenho
		$this->objpdf->text($xcol +2, $xlin +58, 'QUANT');
		$this->objpdf->text($xcol +70, $xlin +58, 'MATERIAL OU SERVIÇO');
		$this->objpdf->text($xcol +154, $xlin +58, 'VALOR UNITÁRIO');
		$this->objpdf->text($xcol +181, $xlin +58, 'VALOR TOTAL');
		$maiscol = 0;

		/// monta os dados para itens do empenho
		$this->objpdf->SetWidths(array (15, 137, 25, 25));
		$this->objpdf->SetAligns(array ('C', 'L', 'R', 'R'));

		$this->objpdf->setleftmargin(4);
		$this->objpdf->sety($xlin +62);
		$this->objpdf->Setfont('Arial', '', 7);
		$ele = 0;
		$xtotal = 0;
		for ($ii = 0; $ii < $this->linhasdositens; $ii ++) {
			db_fieldsmemory($this->recorddositens, $ii);
			$this->objpdf->Setfont('Arial', 'B', 7);
			if ($ele != pg_result($this->recorddositens, $ii, $this->analitico)) {
				$this->objpdf->cell(15, 4, '', 0, 0, "C", 0);
				$this->objpdf->cell(137, 4, db_formatar(pg_result($this->recorddositens, $ii, $this->analitico), 'elemento').' - '.pg_result($this->recorddositens, $ii, $this->descr_analitico), 0, 1, "L", 0);
				$ele = pg_result($this->recorddositens, $ii, $this->analitico);
			}
			$this->objpdf->Setfont('Arial', '', 7);
			$this->objpdf->Row(array (pg_result($this->recorddositens, $ii, $this->quantitem), pg_result($this->recorddositens, $ii, $this->descricaoitem), db_formatar(pg_result($this->recorddositens, $ii, $this->valor), 'v', " ", $this->casadec), db_formatar(pg_result($this->recorddositens, $ii, $this->valoritem), 'f')), 3, false, 4);
			$xtotal += pg_result($this->recorddositens, $ii, $this->valoritem);
			/////// troca de pagina
			if (($this->objpdf->gety() > $this->objpdf->h - 125 && $pagina == 1) || ($this->objpdf->gety() > $this->objpdf->h - 22 && $pagina != 1)) {

				$proxima_pagina = $pagina +1;
				$this->objpdf->Row(array ('', "Continua na página $proxima_pagina", '', ''), 3, false, 4);
				if ($pagina == 1) {
					$this->objpdf->rect($xcol, $xlin +183, 152, 6, 2, 'DF', '34');
					$this->objpdf->rect($xcol +152, $xlin +183, 25, 6, 2, 'DF', '34');
					$this->objpdf->rect($xcol +177, $xlin +183, 25, 6, 2, 'DF', '34');

					$this->objpdf->rect($xcol, $xlin +197, 60, 47, 2, 'DF', '34');
					$this->objpdf->rect($xcol +60, $xlin +197, 60, 47, 2, 'DF', '34');
					$this->objpdf->rect($xcol +120, $xlin +197, 82, 47, 2, 'DF', '34');
					$this->objpdf->rect($xcol +120, $xlin +216, 32, 28, 2, 'DF', '4');

					//	   $this->objpdf->setfillcolor(0,0,0);
					$this->objpdf->SetFont('Arial', '', 7);
					$this->objpdf->text($xcol +2, $xlin +187, 'DESTINO : ', 0, 1, 'L', 0);
					$this->objpdf->text($xcol +30, $xlin +187, $this->destino, 0, 1, 'L', 0);

					$this->objpdf->setxy($xcol +1, $xlin +165);
					$this->objpdf->text($xcol +2, $xlin +164, 'RESUMO : ', 0, 1, 'L', 0);
					$this->objpdf->multicell(147, 3.5, $this->resumo);

					$this->objpdf->text($xcol +159, $xlin +187, 'T O T A L', 0, 1, 'L', 0);
					$this->objpdf->setxy($xcol +185, $xlin +182);
					$this->objpdf->cell(30, 10, db_formatar($this->empenhado, 'f'), 0, 0, 'f');

					$this->objpdf->rect($xcol, $xlin +191, 60, 6, 2, 'DF', '12');
					$this->objpdf->rect($xcol +60, $xlin +191, 60, 6, 2, 'DF', '12');
					$this->objpdf->rect($xcol +120, $xlin +191, 82, 6, 2, 'DF', '12');
					$this->objpdf->text($xcol +15, $xlin +195, 'CONTADORIA GERAL');
					$this->objpdf->text($xcol +82, $xlin +195, 'PAGUE-SE');
					$this->objpdf->text($xcol +150, $xlin +195, 'TESOURARIA');

					if ($this->assinatura1 != "") {
						//               $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
					}

					if ($assinatura2 != "") {
						$this->objpdf->line($xcol +5, $xlin +215, $xcol +54, $xlin +225);
					}

					if ($assinatura3 != "") {
						$this->objpdf->line($xcol +5, $xlin +235, $xcol +54, $xlin +238);
					}

					$this->objpdf->line($xcol +65, $xlin +225, $xcol +114, $xlin +225);
					$this->objpdf->SetFont('Arial', '', 6);
					//         $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
					$this->objpdf->text($xcol +27 - (strlen($this->assinatura1) / 2), $xlin +218, $this->assinatura1);
					
					// $this->objpdf->text($xcol +27 - (strlen($assinatura2) / 2), $xlin +238, $ass_cont_nome_01);
					// $this->objpdf->text($xcol +27 - (strlen($assinatura3) / 2), $xlin +241, $assinatura3);
                    $this->objpdf->text($xcol +27 - (strlen($ass_cont_nome_01) / 2), $xlin +238, $ass_cont_nome_01);
                    $this->objpdf->text($xcol +27 - (strlen($ass_cont_nome_02) / 2), $xlin +241, $ass_cont_nome_02);

					//           $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
					
					// aqui , alterado assinatura
					//$this->objpdf->text($xcol +88 - (strlen($assinaturaprefeito) / 2), $xlin +228, $assinaturaprefeito);
					$this->objpdf->text($xcol +88 - (strlen($ass_pague_nome) / 2), $xlin +228, $ass_pague_nome);					

					$this->objpdf->text($xcol +122, $xlin +207, 'CHEQUE N'.chr(176));
					$this->objpdf->text($xcol +170, $xlin +207, 'DATA');
					$this->objpdf->text($xcol +122, $xlin +215, 'BANCO N'.chr(176));
					$this->objpdf->text($xcol +127, $xlin +218, 'DOCUMENTO N'.chr(176));
					$this->objpdf->line($xcol +155, $xlin +240, $xcol +200, $xlin +240);
					$this->objpdf->text($xcol +170, $xlin +242, 'TESOUREIRO');

					$this->objpdf->rect($xcol, $xlin +246, 202, 26, 2, 'DF', '1234');

					$this->objpdf->SetFont('Arial', '', 7);
					$this->objpdf->text($xcol +90, $xlin +249, 'R E C I B O');
					$this->objpdf->text($xcol +45, $xlin +253, 'RECEBI(EMOS) DO MUNICÍPIO DE '.$this->municpref.', A IMPORTÂNCIA ABAIXO ESPECIFICADA, REFERENTE À:');
					$this->objpdf->text($xcol +2, $xlin +257, '(     ) PARTE DO VALOR EMPENHADO');
					$this->objpdf->text($xcol +102, $xlin +257, '(     ) SALDO/TOTAL EMPENHADO');
					$this->objpdf->text($xcol +2, $xlin +261, 'R$');
					$this->objpdf->text($xcol +102, $xlin +261, 'R$');
					$this->objpdf->text($xcol +2, $xlin +265, 'EM ________/________/________', 0, 0, 'C', 0);
					$this->objpdf->text($xcol +42, $xlin +265, '_________________________________________', 0, 0, 'C', 0);
					$this->objpdf->text($xcol +102, $xlin +265, 'EM ________/________/________', 0, 0, 'C', 0);
					$this->objpdf->text($xcol +142, $xlin +265, '_________________________________________', 0, 1, 'C', 0);
					$this->objpdf->SetFont('Arial', '', 6);
					$this->objpdf->text($xcol +62, $xlin +269, 'CREDOR', 0, 0, 'C', 0);
					$this->objpdf->text($xcol +162, $xlin +269, 'CREDOR', 0, 1, 'C', 0);

					$this->objpdf->SetFont('Arial', '', 4);
					$this->objpdf->Text(2, 296, $this->texto); // texto no canhoto do carne
					$this->objpdf->setfont('Arial', '', 11);
					$xlin = 169;
				}

				$this->objpdf->addpage();
				$pagina += 1;

				$this->objpdf->settopmargin(1);
				$xlin = 20;
				$xcol = 4;
				//Inserindo usuario e data no rodape
				$this->objpdf->Setfont('Arial', 'I', 6);
				$this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y",db_getsession("DB_datausu"))."");

				$this->objpdf->setfillcolor(245);
				$this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
				$this->objpdf->setfillcolor(255, 255, 255);
				$this->objpdf->Setfont('Arial', 'B', 11);

				$this->objpdf->text(128, $xlin -13, 'NOTA DE EMPENHO N'.CHR(176).': ');
				$this->objpdf->text(175, $xlin -13, db_formatar($this->codemp, 's', '0', 6, 'e'));
				$this->objpdf->text(134, $xlin -8, 'DATA DE EMISSÃO : ');
				$this->objpdf->text(175, $xlin -8, $this->emissao);
				$this->objpdf->Image('imagens/files/logo_boleto.png', 15, $xlin -17, 12); //.$this->logo
				$this->objpdf->Setfont('Arial', 'B', 9);
				$this->objpdf->text(40, $xlin -15, $this->prefeitura);
				$this->objpdf->Setfont('Arial', '', 9);
				$this->objpdf->text(40, $xlin -11, $this->enderpref);
				$this->objpdf->text(40, $xlin -8, $this->municpref);
				$this->objpdf->text(40, $xlin -5, $this->telefpref);
				$this->objpdf->text(40, $xlin -2, $this->emailpref);
				$xlin = -30;
				$this->objpdf->Setfont('Arial', 'B', 8);

				//  	    $this->objpdf->Roundedrect($xcol,$xlin+54,15,6,2,'DF','12');
				$this->objpdf->rect($xcol, $xlin +54, 15, 6, 2, 'DF', '12');
				$this->objpdf->rect($xcol +15, $xlin +54, 127, 6, 2, 'DF', '12');
				$this->objpdf->rect($xcol +142, $xlin +54, 35, 6, 2, 'DF', '12');
				$this->objpdf->rect($xcol +177, $xlin +54, 25, 6, 2, 'DF', '12');

				//  	    $this->objpdf->Roundedrect($xcol,$xlin+60,15,262,2,'DF','34');
				$this->objpdf->rect($xcol, $xlin +60, 15, 262, 2, 'DF', '34');
				$this->objpdf->rect($xcol +15, $xlin +60, 127, 262, 2, 'DF', '34');
				$this->objpdf->rect($xcol +142, $xlin +60, 35, 262, 2, 'DF', '34');
				$this->objpdf->rect($xcol +177, $xlin +60, 25, 262, 2, 'DF', '34');

				$this->objpdf->sety($xlin +66);
				$alt = 4;

				//	    $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
				$this->objpdf->text($xcol +0.5, $xlin +58, 'QUANT');
				$this->objpdf->text($xcol +65, $xlin +58, 'MATERIAL OU SERVIÇO');
				$this->objpdf->text($xcol +145, $xlin +58, 'VALOR UNITÁRIO');
				$this->objpdf->text($xcol +179, $xlin +58, 'VALOR TOTAL');
				$this->objpdf->text($xcol +38, $xlin +63, 'Continuação da Página '. ($this->objpdf->PageNo() - 1));

				$maiscol = 0;

			}

		}

		if ($pagina == 1) {
			$this->objpdf->rect($xcol, $xlin +183, 152, 6, 2, 'DF', '34');
			$this->objpdf->rect($xcol +152, $xlin +183, 25, 6, 2, 'DF', '34');
			$this->objpdf->rect($xcol +177, $xlin +183, 25, 6, 2, 'DF', '34');

			//	   $this->objpdf->setfillcolor(0,0,0);
			$this->objpdf->SetFont('Arial', '', 7);
			$this->objpdf->text($xcol +2, $xlin +187, 'DESTINO : ', 0, 1, 'L', 0);
			$this->objpdf->text($xcol +30, $xlin +187, $this->destino, 0, 1, 'L', 0);

			$this->objpdf->setxy($xcol +1, $xlin +165);
			$this->objpdf->text($xcol +2, $xlin +164, 'RESUMO : ', 0, 1, 'L', 0);
			$this->objpdf->multicell(147, 3.5, $this->resumo);

			$this->objpdf->text($xcol +159, $xlin +187, 'T O T A L', 0, 1, 'L', 0);
			$this->objpdf->setxy($xcol +185, $xlin +182);
			$this->objpdf->cell(30, 10, db_formatar($this->empenhado, 'f'), 0, 0, 'f');
			//	   $this->rodape($mod_rodape); 

			$this->objpdf->rect($xcol, $xlin +197, 60, 47, 2, 'DF', '34');
			$this->objpdf->rect($xcol +60, $xlin +197, 60, 47, 2, 'DF', '34');
			$this->objpdf->rect($xcol +120, $xlin +197, 82, 47, 2, 'DF', '34');
			$this->objpdf->rect($xcol +120, $xlin +216, 32, 28, 2, 'DF', '4');

			$this->objpdf->rect($xcol, $xlin +191, 60, 6, 2, 'DF', '12');
			$this->objpdf->rect($xcol +60, $xlin +191, 60, 6, 2, 'DF', '12');
			$this->objpdf->rect($xcol +120, $xlin +191, 82, 6, 2, 'DF', '12');
			$this->objpdf->text($xcol +15, $xlin +195, 'CONTADORIA GERAL');
			$this->objpdf->text($xcol +82, $xlin +195, 'PAGUE-SE');
			$this->objpdf->text($xcol +150, $xlin +195, 'TESOURARIA');
			
			if ($ass_cont_nome_01 != "") {
				$this->objpdf->line($xcol +5, $xlin +215, $xcol +54, $xlin +215);
			}
			if ($ass_cont_nome_02 != "") {
				$this->objpdf->line($xcol +5, $xlin +235, $xcol +54, $xlin +235); 
			}

			$this->objpdf->line($xcol +65, $xlin +225, $xcol +114, $xlin +225);
 			$this->objpdf->SetFont('Arial', '', 6);

            $this->objpdf->text($xcol +27 - (strlen($ass_cont_nome_01) / 2), $xlin +218, $ass_cont_nome_01);
			$this->objpdf->text($xcol +27 - (strlen($ass_cont_cargo_01) / 2), $xlin +221, $ass_cont_cargo_01);
			$this->objpdf->text($xcol +27 - (strlen($ass_cont_info_01) / 2), $xlin +224, $ass_cont_info_01);
			
			$this->objpdf->text($xcol +27 - (strlen($ass_cont_nome_02) / 2), $xlin +238, $ass_cont_nome_02);			
			$this->objpdf->text($xcol +27 - (strlen($ass_cont_cargo_02) / 2), $xlin +241, $ass_cont_cargo_02);


			$this->objpdf->text($xcol +88 - (strlen($ass_pague_nome) / 2), $xlin +228, $ass_pague_nome);
			$this->objpdf->text($xcol +88 - (strlen($ass_pague_cargo) / 2), $xlin +231, $ass_pague_cargo);
						
						
			$this->objpdf->text($xcol +122, $xlin +207, 'CHEQUE N'.chr(176));
			$this->objpdf->text($xcol +170, $xlin +207, 'DATA');
			$this->objpdf->text($xcol +122, $xlin +215, 'BANCO N'.chr(176));
			$this->objpdf->text($xcol +127, $xlin +218, 'DOCUMENTO N'.chr(176));
			$this->objpdf->line($xcol +155, $xlin +240, $xcol +200, $xlin +240);
			$this->objpdf->text($xcol +170, $xlin +242, 'TESOUREIRO');

			$this->objpdf->rect($xcol, $xlin +246, 202, 26, 2, 'DF', '1234');

			//////	   $this->objpdf->text($xcol+2,$xlin+250,'RESUMO: ',0,1,'L',0);

			//////	   $this->objpdf->setxy($xcol+1,$xlin+252);
			////// 	   $this->objpdf->multicell(147,3.5,$this->resumo);

			/*
				     
				     $this->objpdf->SetFont('Arial','',7);
			
				     $this->objpdf->text($xcol+90,$xlin+249,'R E C I B O');
				     $this->objpdf->text($xcol+45,$xlin+253,'RECEBI(EMOS) DO MUNICÍPIO DE '.$this->municpref.', A IMPORTÂNCIA ABAIXO ESPECIFICADA, REFERENTE À:');
				     $this->objpdf->text($xcol+2,$xlin+257,'(     ) PARTE DO VALOR EMPENHADO');
				     $this->objpdf->text($xcol+102,$xlin+257,'(     ) SALDO/TOTAL EMPENHADO');
				     $this->objpdf->text($xcol+2,$xlin+261,'R$');
				     $this->objpdf->text($xcol+102,$xlin+261,'R$');
				     $this->objpdf->text($xcol+2,$xlin+265,'EM ________/________/________',0,0,'C',0);
				     $this->objpdf->text($xcol+42,$xlin+265,'_________________________________________',0,0,'C',0);
				     $this->objpdf->text($xcol+102,$xlin+265,'EM ________/________/________',0,0,'C',0);
				     $this->objpdf->text($xcol+142,$xlin+265,'_________________________________________',0,1,'C',0);
				     $this->objpdf->SetFont('Arial','',6);
				     $this->objpdf->text($xcol+62,$xlin+269,'CREDOR',0,0,'C',0);
				     $this->objpdf->text($xcol+162,$xlin+269,'CREDOR',0,1,'C',0);
				     
				     $this->objpdf->SetFont('Arial','',4);
				     $this->objpdf->Text(2,296,$this->texto); // texto no canhoto do carne
				     $this->objpdf->SetFont('Arial','',6);
				     $this->objpdf->Text(200,296,($xxx+1).' via'); // texto no canhoto do carne
				     $this->objpdf->setfont('Arial','',11);
			*/
			$xlin = 169;
		}
	}

} else {
	for ($xxx = 0; $xxx < $this->nvias; $xxx ++) {
		$this->objpdf->AliasNbPages();
		$this->objpdf->AddPage();
		$this->objpdf->settopmargin(1);
		$pagina = 1;
		$xlin = 20;
		$xcol = 4;
		//Inserindo usuario e data no rodape
		$this->objpdf->Setfont('Arial', 'I', 6);
		$this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")."  Data: ".date("d/m/Y",db_getsession("DB_datausu"))."");

		$this->objpdf->setfillcolor(245);
		$this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
		$this->objpdf->setfillcolor(255, 255, 255);
		$this->objpdf->Setfont('Arial', 'B', 10);
		$this->objpdf->text(128, $xlin -13, 'NOTA DE EMPENHO N'.CHR(176).': ');
		$this->objpdf->text(175, $xlin -13, db_formatar($this->codemp, 's', '0', 6, 'e'));
		$this->objpdf->text(134, $xlin -8, 'DATA DE EMISSÃO : ');
		$this->objpdf->text(175, $xlin -8, $this->emissao);

		$this->objpdf->text(159, $xlin -3, 'TIPO : ');
		$this->objpdf->text(175, $xlin -3, $this->emptipo);

		$this->objpdf->Image('imagens/files/logo_boleto.png', 15, $xlin -17, 12); //.$this->logo
		$this->objpdf->Setfont('Arial', 'B', 9);
		$this->objpdf->text(40, $xlin -15, $this->prefeitura);
		$this->objpdf->Setfont('Arial', '', 7);
		$this->objpdf->text(40, $xlin -11, $this->enderpref);
		$this->objpdf->text(40, $xlin -8, $this->municpref);
		$this->objpdf->text(40, $xlin -5, $this->telefpref);
		$this->objpdf->text(40, $xlin -2, $this->emailpref);
		$this->objpdf->text(40, $xlin +1, db_formatar($this->cgcpref, 'cnpj'));

		/// retangulo dos dados da dotação
		$this->objpdf->rect($xcol, $xlin +2, $xcol +100, 50, 2, 'DF', '1234');
		$this->objpdf->Setfont('Arial', 'B', 8);
		$this->objpdf->text($xcol +2, $xlin +7, 'Órgao');
		$this->objpdf->text($xcol +2, $xlin +10.5, 'Unidade');
		$this->objpdf->text($xcol +2, $xlin +14, 'Função');
		$this->objpdf->text($xcol +2, $xlin +17.5, 'Subfunção');
		$this->objpdf->text($xcol +2, $xlin +21, 'Programa');
		$this->objpdf->text($xcol +2, $xlin +24.5, 'Proj/Ativ');
		$this->objpdf->text($xcol +2, $xlin +28, 'Rubrica');
		$this->objpdf->text($xcol +2, $xlin +35, 'Recurso');

		if ($this->banco != "") {
			$this->objpdf->text($xcol +2, $xlin +38.5, 'Banco');
			$this->objpdf->text($xcol +30, $xlin +38.5, 'Agencia:');
			$this->objpdf->text($xcol +60, $xlin +38.5, 'Conta:');
		}

		$this->objpdf->text($xcol +2, $xlin +42.5, 'Reduzido');
		$this->objpdf->text($xcol +2, $xlin +48, 'Licitação');

		$this->objpdf->Setfont('Arial', '', 8);
		$this->objpdf->text($xcol +17, $xlin +7, ':  '.db_formatar($this->orgao, 'orgao').' - '.$this->descr_orgao);
		$this->objpdf->text($xcol +17, $xlin +10.5, ':  '.db_formatar($this->unidade, 'unidade').' - '.$this->descr_unidade);
		$this->objpdf->text($xcol +17, $xlin +14, ':  '.db_formatar($this->funcao, 'funcao').' - '.$this->descr_funcao);
		$this->objpdf->text($xcol +17, $xlin +17.5, ':  '.db_formatar($this->subfuncao, 'subfuncao').' - '.$this->descr_subfuncao);
		$this->objpdf->text($xcol +17, $xlin +21, ':  '.db_formatar($this->programa, 'programa').' - '.$this->descr_programa);

		$this->objpdf->text($xcol +17, $xlin +24.5, ':  '.db_formatar($this->projativ, 'projativ').' - '.$this->descr_projativ);

		$this->objpdf->text($xcol +17, $xlin +28, ':  '.db_formatar($this->sintetico, 'elemento_int'));
		$this->objpdf->setxy($xcol +18, $xlin +29);
		$this->objpdf->multicell(90, 3, $this->descr_sintetico, 0, "L");

		$this->objpdf->text($xcol +17, $xlin +35, ':  '.$this->recurso.' - '.$this->descr_recurso);

		if ($this->banco != "") {
			$this->objpdf->text($xcol +17, $xlin +38.5, ':  '.$this->banco);
			$this->objpdf->text($xcol +47, $xlin +38.5, $this->agencia);
			$this->objpdf->text($xcol +77, $xlin +38.5, $this->conta);
		}

		$this->objpdf->text($xcol +17, $xlin +42.5, ':  '.$this->coddot);

		$this->objpdf->text($xcol +17, $xlin +48, ':  '. ($this->num_licitacao != null ? $this->num_licitacao.' - ' : '').$this->descr_licitacao);

		//// retangulo dos dados do credor
		$this->objpdf->rect($xcol +106, $xlin +2, 96, 18, 2, 'DF', '1234');
		$this->objpdf->Setfont('Arial', '', 6);
		$this->objpdf->text($xcol +108, $xlin +4, 'Dados do Credor:');
		$this->objpdf->Setfont('Arial', 'B', 8);
		$this->objpdf->text($xcol +107, $xlin +7, 'Numcgm');
		$this->objpdf->text($xcol +135, $xlin +7, (strlen($this->cnpj) == 11 ? 'CPF' : 'CNPJ'));
		$this->objpdf->text($xcol +107, $xlin +11, 'Nome');
		$this->objpdf->text($xcol +107, $xlin +15, 'Endereço');
		$this->objpdf->text($xcol +107, $xlin +19, 'Município');
		$this->objpdf->Setfont('Arial', '', 8);
		$this->objpdf->text($xcol +124, $xlin +7, ': '.$this->numcgm);
		$this->objpdf->text($xcol +143, $xlin +7, ': '. (strlen($this->cnpj) == 11 ? db_formatar($this->cnpj, 'cpf') : db_formatar($this->cnpj, 'cnpj')).' - Fone: '.$this->telefone);
		$this->objpdf->text($xcol +124, $xlin +11, ': '.$this->nome);
		$this->objpdf->text($xcol +124, $xlin +15, ': '.$this->ender.'  '.$this->compl);
		$this->objpdf->text($xcol +124, $xlin +19, ': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);

		///// retangulo dos valores
		$this->objpdf->rect($xcol +106, $xlin +21.5, 96, 9, 2, 'DF', '1234');
		$this->objpdf->rect($xcol +106, $xlin +32.0, 47, 9, 2, 'DF', '1234');
		$this->objpdf->rect($xcol +155, $xlin +32.0, 47, 9, 2, 'DF', '1234');
		$this->objpdf->rect($xcol +106, $xlin +42.5, 47, 9, 2, 'DF', '1234');
		$this->objpdf->rect($xcol +155, $xlin +42.5, 47, 9, 2, 'DF', '1234');
		$this->objpdf->Setfont('Arial', '', 6);
		$this->objpdf->text($xcol +108, $xlin +34.0, 'Valor Orçado');
		$this->objpdf->text($xcol +157, $xlin +34.0, 'Saldo Anterior');
		$this->objpdf->text($xcol +108, $xlin +44.5, 'Valor Empenhado');
		$this->objpdf->text($xcol +157, $xlin +44.5, 'Saldo Atual');
		$this->objpdf->Setfont('Arial', '', 8);
		$this->objpdf->text($xcol +108, $xlin +27, 'AUTORIZAÇÃO N'.chr(176).' '.db_formatar($this->numaut, 's', '0', 5, 'e'));
		$this->objpdf->text($xcol +150, $xlin +27, 'SEQ. DO EMPENHO N'.chr(176).' '.db_formatar($this->numemp, 's', '0', 6, 'e'));
		//	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
		$this->objpdf->text($xcol +130, $xlin +38.0, db_formatar($this->orcado, 'f'));
		$this->objpdf->text($xcol +180, $xlin +38.0, db_formatar($this->saldo_ant, 'f'));
		$this->objpdf->text($xcol +130, $xlin +47.5, db_formatar($this->empenhado, 'f'));
		$this->objpdf->text($xcol +180, $xlin +47.5, db_formatar($this->saldo_ant - $this->empenhado, 'f'));

		/// retangulo do corpo do empenho 
		$this->objpdf->rect($xcol, $xlin +60, 15, 100, 2, 'DF', '');
		$this->objpdf->rect($xcol +15, $xlin +60, 137, 100, 2, 'DF', '');
		$this->objpdf->rect($xcol +152, $xlin +60, 25, 123, 2, 'DF', '');
		$this->objpdf->rect($xcol +177, $xlin +60, 25, 123, 2, 'DF', '');
		$this->objpdf->rect($xcol, $xlin +160, 152, 23, 2, 'DF', '');

		//// retangulos do titulo do corpo do empenho
		$this->objpdf->Setfont('Arial', 'B', 7);
		$this->objpdf->rect($xcol, $xlin +54, 15, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +15, $xlin +54, 137, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +152, $xlin +54, 25, 6, 2, 'DF', '12');
		$this->objpdf->rect($xcol +177, $xlin +54, 25, 6, 2, 'DF', '12');

		//// título do corpo do empenho
		$this->objpdf->text($xcol +2, $xlin +58, 'QUANT');
		$this->objpdf->text($xcol +70, $xlin +58, 'MATERIAL OU SERVIÇO');
		$this->objpdf->text($xcol +154, $xlin +58, 'VALOR UNITÁRIO');
		$this->objpdf->text($xcol +181, $xlin +58, 'VALOR TOTAL');
		$maiscol = 0;

		/// monta os dados para itens do empenho
		$this->objpdf->SetWidths(array (15, 137, 25, 25));
		$this->objpdf->SetAligns(array ('C', 'L', 'R', 'R'));

		$this->objpdf->setleftmargin(4);
		$this->objpdf->sety($xlin +62);
		$this->objpdf->Setfont('Arial', '', 7);
		$ele = 0;
		$xtotal = 0;
		for ($ii = 0; $ii < $this->linhasdositens; $ii ++) {
			db_fieldsmemory($this->recorddositens, $ii);
			$this->objpdf->Setfont('Arial', 'B', 7);
			if ($ele != pg_result($this->recorddositens, $ii, $this->analitico)) {
				$this->objpdf->cell(15, 4, '', 0, 0, "C", 0);
				$this->objpdf->cell(137, 4, db_formatar(pg_result($this->recorddositens, $ii, $this->analitico), 'elemento_int').' - '.pg_result($this->recorddositens, $ii, $this->descr_analitico), 0, 1, "L", 0);
				$ele = pg_result($this->recorddositens, $ii, $this->analitico);
			}
			$this->objpdf->Setfont('Arial', '', 7);
			$this->objpdf->Row(array (pg_result($this->recorddositens, $ii, $this->quantitem), pg_result($this->recorddositens, $ii, $this->descricaoitem), db_formatar(pg_result($this->recorddositens, $ii, $this->valor), 'v', " ", $this->casadec), db_formatar(pg_result($this->recorddositens, $ii, $this->valoritem), 'f')), 3, false, 4);
			$xtotal += pg_result($this->recorddositens, $ii, $this->valoritem);
			/////// troca de pagina
			if (($this->objpdf->gety() > $this->objpdf->h - 125 && $pagina == 1) || ($this->objpdf->gety() > $this->objpdf->h - 22 && $pagina != 1)) {

				$proxima_pagina = $pagina +1;
				$this->objpdf->Row(array ('', "Continua na página $proxima_pagina", '', ''), 3, false, 4);
				if ($pagina == 1) {
					$this->objpdf->rect($xcol, $xlin +183, 152, 6, 2, 'DF', '34');
					$this->objpdf->rect($xcol +152, $xlin +183, 25, 6, 2, 'DF', '34');
					$this->objpdf->rect($xcol +177, $xlin +183, 25, 6, 2, 'DF', '34');

					$this->objpdf->rect($xcol, $xlin +197, 60, 47, 2, 'DF', '34');
					$this->objpdf->rect($xcol +60, $xlin +197, 60, 47, 2, 'DF', '34');
					$this->objpdf->rect($xcol +120, $xlin +197, 82, 47, 2, 'DF', '34');
					$this->objpdf->rect($xcol +120, $xlin +216, 32, 28, 2, 'DF', '4');

					//	   $this->objpdf->setfillcolor(0,0,0);
					$this->objpdf->SetFont('Arial', '', 7);
					$this->objpdf->text($xcol +2, $xlin +187, 'DESTINO : ', 0, 1, 'L', 0);
					$this->objpdf->text($xcol +30, $xlin +187, $this->destino, 0, 1, 'L', 0);

					$this->objpdf->setxy($xcol +1, $xlin +165);
					$this->objpdf->text($xcol +2, $xlin +164, 'RESUMO : ', 0, 1, 'L', 0);
					$this->objpdf->multicell(147, 3.5, $this->resumo);

					$this->objpdf->text($xcol +159, $xlin +187, 'T O T A L', 0, 1, 'L', 0);
					$this->objpdf->setxy($xcol +185, $xlin +182);
					$this->objpdf->cell(30, 10, db_formatar($this->empenhado, 'f'), 0, 0, 'f');

					$this->objpdf->rect($xcol, $xlin +191, 60, 6, 2, 'DF', '12');
					$this->objpdf->rect($xcol +60, $xlin +191, 60, 6, 2, 'DF', '12');
					$this->objpdf->rect($xcol +120, $xlin +191, 82, 6, 2, 'DF', '12');
					$this->objpdf->text($xcol +15, $xlin +195, 'CONTADORIA GERAL');
					$this->objpdf->text($xcol +82, $xlin +195, 'PAGUE-SE');
					$this->objpdf->text($xcol +150, $xlin +195, 'TESOURARIA');

					if ($this->assinatura1 != "") {
						$this->objpdf->line($xcol +5, $xlin +211, $xcol +54, $xlin +211);
					}

					if ($this->assinatura2 != "") {
						$this->objpdf->line($xcol +5, $xlin +225, $xcol +54, $xlin +225);
					}

					if ($this->assinatura3 != "") {
						$this->objpdf->line($xcol +5, $xlin +238, $xcol +54, $xlin +238);
					}

					$this->objpdf->line($xcol +65, $xlin +225, $xcol +114, $xlin +225);
					$this->objpdf->SetFont('Arial', '', 6);
					//         $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
					$this->objpdf->text($xcol +27 - (strlen($this->assinatura1) / 2), $xlin +213, $this->assinatura1);
					$this->objpdf->text($xcol +27 - (strlen($this->assinatura2) / 2), $xlin +227, $this->assinatura2);
					$this->objpdf->text($xcol +27 - (strlen($this->assinatura3) / 2), $xlin +240, $this->assinatura3);

					$this->objpdf->text($xcol +66, $xlin +212, 'DATA  ____________/____________/____________');
					$this->objpdf->text($xcol +88 - (strlen($this->assinaturaprefeito) / 2), $xlin +227, $this->assinaturaprefeito);

					$this->objpdf->text($xcol +66, $xlin +212, 'DATA  ____________/____________/____________');
					//  $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');

					$this->objpdf->text($xcol +122, $xlin +207, 'CHEQUE N'.chr(176));
					$this->objpdf->text($xcol +170, $xlin +207, 'DATA');
					$this->objpdf->text($xcol +122, $xlin +215, 'BANCO N'.chr(176));
					$this->objpdf->text($xcol +127, $xlin +218, 'DOCUMENTO N'.chr(176));
					$this->objpdf->line($xcol +155, $xlin +240, $xcol +200, $xlin +240);
					$this->objpdf->text($xcol +170, $xlin +242, 'TESOUREIRO');

					$this->objpdf->rect($xcol, $xlin +246, 202, 26, 2, 'DF', '1234');

					$this->objpdf->SetFont('Arial', '', 7);
					$this->objpdf->text($xcol +90, $xlin +249, 'R E C I B O');
					$this->objpdf->text($xcol +45, $xlin +253, 'RECEBI(EMOS) DO MUNICÍPIO DE '.$this->municpref.', A IMPORTÂNCIA ABAIXO ESPECIFICADA, REFERENTE À:');
					$this->objpdf->text($xcol +2, $xlin +257, '(     ) PARTE DO VALOR EMPENHADO');
					$this->objpdf->text($xcol +102, $xlin +257, '(     ) SALDO/TOTAL EMPENHADO');
					$this->objpdf->text($xcol +2, $xlin +261, 'R$');
					$this->objpdf->text($xcol +102, $xlin +261, 'R$');
					$this->objpdf->text($xcol +2, $xlin +265, 'EM ________/________/________', 0, 0, 'C', 0);
					$this->objpdf->text($xcol +42, $xlin +265, '_________________________________________', 0, 0, 'C', 0);
					$this->objpdf->text($xcol +102, $xlin +265, 'EM ________/________/________', 0, 0, 'C', 0);
					$this->objpdf->text($xcol +142, $xlin +265, '_________________________________________', 0, 1, 'C', 0);
					$this->objpdf->SetFont('Arial', '', 6);
					$this->objpdf->text($xcol +62, $xlin +269, 'CREDOR', 0, 0, 'C', 0);
					$this->objpdf->text($xcol +162, $xlin +269, 'CREDOR', 0, 1, 'C', 0);

					$this->objpdf->SetFont('Arial', '', 4);
					$this->objpdf->Text(2, 296, $this->texto); // texto no canhoto do carne
					$this->objpdf->setfont('Arial', '', 11);
					$xlin = 169;
				}

				$this->objpdf->addpage();
				$pagina += 1;

				$this->objpdf->settopmargin(1);
				$xlin = 20;
				$xcol = 4;
				//Inserindo usuario e data no rodape
				$this->objpdf->Setfont('Arial', 'I', 6);
				$this->objpdf->text($xcol +3, $xlin +276, "Emissor: ".db_getsession("DB_login")." Data: ".date("d/m/Y H:m")."");

				$this->objpdf->setfillcolor(245);
				$this->objpdf->rect($xcol -2, $xlin -18, 206, 292, 2, 'DF', '1234');
				$this->objpdf->setfillcolor(255, 255, 255);
				$this->objpdf->Setfont('Arial', 'B', 11);

				$this->objpdf->text(128, $xlin -13, 'NOTA DE EMPENHO N'.CHR(176).': ');
				$this->objpdf->text(175, $xlin -13, db_formatar($this->codemp, 's', '0', 6, 'e'));
				$this->objpdf->text(134, $xlin -8, 'DATA DE EMISSÃO : ');
				$this->objpdf->text(175, $xlin -8, $this->emissao);
				$this->objpdf->Image('imagens/files/logo_boleto.png', 15, $xlin -17, 12); //.$this->logo
				$this->objpdf->Setfont('Arial', 'B', 9);
				$this->objpdf->text(40, $xlin -15, $this->prefeitura);
				$this->objpdf->Setfont('Arial', '', 9);
				$this->objpdf->text(40, $xlin -11, $this->enderpref);
				$this->objpdf->text(40, $xlin -8, $this->municpref);
				$this->objpdf->text(40, $xlin -5, $this->telefpref);
				$this->objpdf->text(40, $xlin -2, $this->emailpref);
				$xlin = -30;
				$this->objpdf->Setfont('Arial', 'B', 8);

				//  	    $this->objpdf->Roundedrect($xcol,$xlin+54,15,6,2,'DF','12');
				$this->objpdf->rect($xcol, $xlin +54, 15, 6, 2, 'DF', '12');
				$this->objpdf->rect($xcol +15, $xlin +54, 127, 6, 2, 'DF', '12');
				$this->objpdf->rect($xcol +142, $xlin +54, 35, 6, 2, 'DF', '12');
				$this->objpdf->rect($xcol +177, $xlin +54, 25, 6, 2, 'DF', '12');

				//  	    $this->objpdf->Roundedrect($xcol,$xlin+60,15,262,2,'DF','34');
				$this->objpdf->rect($xcol, $xlin +60, 15, 262, 2, 'DF', '34');
				$this->objpdf->rect($xcol +15, $xlin +60, 127, 262, 2, 'DF', '34');
				$this->objpdf->rect($xcol +142, $xlin +60, 35, 262, 2, 'DF', '34');
				$this->objpdf->rect($xcol +177, $xlin +60, 25, 262, 2, 'DF', '34');

				$this->objpdf->sety($xlin +66);
				$alt = 4;

				//	    $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
				$this->objpdf->text($xcol +0.5, $xlin +58, 'QUANT');
				$this->objpdf->text($xcol +65, $xlin +58, 'MATERIAL OU SERVIÇO');
				$this->objpdf->text($xcol +145, $xlin +58, 'VALOR UNITÁRIO');
				$this->objpdf->text($xcol +179, $xlin +58, 'VALOR TOTAL');
				$this->objpdf->text($xcol +38, $xlin +63, 'Continuação da Página '. ($this->objpdf->PageNo() - 1));

				$maiscol = 0;

			}

		}

		if ($pagina == 1) {
			$this->objpdf->rect($xcol, $xlin +183, 152, 6, 2, 'DF', '34');
			$this->objpdf->rect($xcol +152, $xlin +183, 25, 6, 2, 'DF', '34');
			$this->objpdf->rect($xcol +177, $xlin +183, 25, 6, 2, 'DF', '34');

			//	   $this->objpdf->setfillcolor(0,0,0);
			$this->objpdf->SetFont('Arial', '', 7);
			$this->objpdf->text($xcol +2, $xlin +187, 'DESTINO : ', 0, 1, 'L', 0);
			$this->objpdf->text($xcol +30, $xlin +187, $this->destino, 0, 1, 'L', 0);

			$this->objpdf->setxy($xcol +1, $xlin +165);
			$this->objpdf->text($xcol +2, $xlin +164, 'RESUMO : ', 0, 1, 'L', 0);
			$this->objpdf->multicell(147, 3.5, $this->resumo);

			$this->objpdf->text($xcol +159, $xlin +187, 'T O T A L', 0, 1, 'L', 0);
			$this->objpdf->setxy($xcol +185, $xlin +182);
			$this->objpdf->cell(30, 10, db_formatar($this->empenhado, 'f'), 0, 0, 'f');
			//	   $this->rodape($mod_rodape); 

			$this->objpdf->rect($xcol, $xlin +197, 60, 47, 2, 'DF', '34');
			$this->objpdf->rect($xcol +60, $xlin +197, 60, 47, 2, 'DF', '34');
			$this->objpdf->rect($xcol +120, $xlin +197, 82, 47, 2, 'DF', '34');
			$this->objpdf->rect($xcol +120, $xlin +216, 32, 28, 2, 'DF', '4');

			$this->objpdf->rect($xcol, $xlin +191, 60, 6, 2, 'DF', '12');
			$this->objpdf->rect($xcol +60, $xlin +191, 60, 6, 2, 'DF', '12');
			$this->objpdf->rect($xcol +120, $xlin +191, 82, 6, 2, 'DF', '12');
			$this->objpdf->text($xcol +15, $xlin +195, 'CONTADORIA GERAL');
			$this->objpdf->text($xcol +82, $xlin +195, 'PAGUE-SE');
			$this->objpdf->text($xcol +150, $xlin +195, 'TESOURARIA');

			if (strtoupper(trim($this->municpref)) == 'BAGE') { // eu carlos, assumo ! 
				$this->assinatura1 = 'EMISSOR';
				$this->assinatura2 = 'CONTABILIDADE - CONFERIDO';
				$this->assinatura3 = 'SECRETÃRIO(A) RESPONSAVEL';
				$this->assinatura4 = 'SECRETÃRIO DA FAZENDA';
				$this->assinaturaprefeito = 'SECRETARIA DA FAZENDA';
			}

			if ($this->assinatura1 != "") {
				$this->objpdf->line($xcol +5, $xlin +211, $xcol +54, $xlin +211);
			}

			if ($this->assinatura2 != "") {
				$this->objpdf->line($xcol +5, $xlin +225, $xcol +54, $xlin +225);
			}

			if ($this->assinatura3 != "") {
				$this->objpdf->line($xcol +5, $xlin +238, $xcol +54, $xlin +238);
			}

			$this->objpdf->line($xcol +65, $xlin +225, $xcol +114, $xlin +225);
			$this->objpdf->SetFont('Arial', '', 6);
			//         $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
			$this->objpdf->text($xcol +27 - (strlen($this->assinatura1) / 2), $xlin +213, $this->assinatura1);
			$this->objpdf->text($xcol +27 - (strlen($this->assinatura2) / 2), $xlin +227, $this->assinatura2);
			$this->objpdf->text($xcol +27 - (strlen($this->assinatura3) / 2), $xlin +240, $this->assinatura3);

			$this->objpdf->text($xcol +66, $xlin +212, 'DATA  ____________/____________/____________');
			$this->objpdf->text($xcol +88 - (strlen($this->assinaturaprefeito) / 2), $xlin +227, $this->assinaturaprefeito);

			$this->objpdf->text($xcol +122, $xlin +207, 'CHEQUE N'.chr(176));
			$this->objpdf->text($xcol +170, $xlin +207, 'DATA');
			$this->objpdf->text($xcol +122, $xlin +215, 'BANCO N'.chr(176));
			$this->objpdf->text($xcol +127, $xlin +218, 'DOCUMENTO N'.chr(176));
			$this->objpdf->line($xcol +155, $xlin +240, $xcol +200, $xlin +240);
			$this->objpdf->text($xcol +170, $xlin +242, 'TESOUREIRO');

			$this->objpdf->rect($xcol, $xlin +246, 202, 26, 2, 'DF', '1234');

			$this->objpdf->SetFont('Arial', '', 7);
			$this->objpdf->text($xcol +90, $xlin +249, 'R E C I B O');
			$this->objpdf->text($xcol +45, $xlin +253, 'RECEBI(EMOS) DO MUNICÍPIO DE '.$this->municpref.', A IMPORTÂNCIA ABAIXO ESPECIFICADA, REFERENTE À:');
			$this->objpdf->text($xcol +2, $xlin +257, '(     ) PARTE DO VALOR EMPENHADO');
			$this->objpdf->text($xcol +102, $xlin +257, '(     ) SALDO/TOTAL EMPENHADO');
			$this->objpdf->text($xcol +2, $xlin +261, 'R$');
			$this->objpdf->text($xcol +102, $xlin +261, 'R$');
			$this->objpdf->text($xcol +2, $xlin +265, 'EM ________/________/________', 0, 0, 'C', 0);
			$this->objpdf->text($xcol +42, $xlin +265, '_________________________________________', 0, 0, 'C', 0);
			$this->objpdf->text($xcol +102, $xlin +265, 'EM ________/________/________', 0, 0, 'C', 0);
			$this->objpdf->text($xcol +142, $xlin +265, '_________________________________________', 0, 1, 'C', 0);
			$this->objpdf->SetFont('Arial', '', 6);
			$this->objpdf->text($xcol +62, $xlin +269, 'CREDOR', 0, 0, 'C', 0);
			$this->objpdf->text($xcol +162, $xlin +269, 'CREDOR', 0, 1, 'C', 0);

			$this->objpdf->SetFont('Arial', '', 4);
			// para localizar a data do rodape, procure a palavra 'Emissor'
			$this->objpdf->Text(2, 296, $this->texto); // texto no canhoto do carne
			$this->objpdf->SetFont('Arial', '', 6);
			$this->objpdf->Text(200, 296, ($xxx +1).' via'); // texto no canhoto do carne
			$this->objpdf->setfont('Arial', '', 11);
			$xlin = 169;
		}
	}
}
?>
