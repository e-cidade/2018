<?php
/*
 
/////   VARIÁVEIS PARA EMISSAO DE CARNES DE PARCELA UNICA

  var $quant_parcelas = 0;
  var $agencias       = null;
  var $prefeitura     = null;
  var $munic          = null;
  var $texto2         = null;
  
  var $estado         = 'TIPO DE DÉBITO';
  var $titulo9   = 'Cód. de Arrecadação';
  var $descr9    = null;
  var $titulo10  = 'Parcela';
  var $descr10   = null;
  var $titulo11  = 'Contribuinte/Endereço';
  var $descr11_1 = null;
  var $descr11_2 = null;
  var $titulo12  = 'Instruções';
  var $descr12_1 = null;
  var $descr12_2 = null;
  
*/
	
	$this->objpdf->Text(25, 37, "- Parcelado - em $this->quant_parcelas vezes - sem desconto");
	$this->objpdf->SetFont('Times', 'B', 10);
	$this->objpdf->Text(15, 45, "LOCAIS DE PAGAMENTO ATÉ O VENCIMENTO:");
	$this->objpdf->SetFont('Times', 'B', 7);
	$this->objpdf->Text(25, 50, @$this->agencias);
	$this->objpdf->Text(25, 55, "-  Via Internet e Home Banking");
	$this->objpdf->SetFont('Times', 'BI', 8);
	$this->objpdf->Text(15, 60, "* VALOR CONSTANTE NOS TICKETS DE PAGAMENTO PARA COTA ÚNICA JÁ CONCEDE O DESCONTO PREVISTO");
	$this->objpdf->SetFont('Times', 'B', 12);
	$pref = $this->prefeitura;
	$estado = "ESTADO DO RIO GRANDE DO SUL"; 
	$this->objpdf->Text(45, 80, $pref);
	$this->objpdf->SetFont('Times', 'B', 10);
	$this->objpdf->Text(45, 84, $estado);
	$this->objpdf->SetFont('Times', 'B', 11);
	$this->objpdf->Text(45, 88, "IMPOSTO PREDIAL E TERRITORIAL URBANO");
	$this->objpdf->SetFont('Times', 'BI', 22);
	$this->objpdf->Text(75, 99, "IPTU ".$this->anousu); //.db_getsession("DB_anousu")); 
	$this->objpdf->Rect(150, 70, 55, 40, 20, 'd');
	$this->objpdf->SetFont('Times', 'B', 8);
	$this->objpdf->Text(170, 80, "CONTRATO");
	$this->objpdf->Text(155, 90, "ECT/RS - PREF. $this->munic");



	$this->objpdf->Text(175, 100, @$this->texto2);

	$this->objpdf->SetFillColor(220);
	$this->objpdf->Rect(15, 130, 180, 40, 'DF');
	$this->objpdf->SetFont('Times', 'B', 12);
	$this->objpdf->Text(150, 140, "Inscrição : ".$this->matric);
	$this->objpdf->SetFont('Times', 'B', 8);
	$this->objpdf->Text(25, 140, "Nome:     ".$this->nome);
	$this->objpdf->Text(25, 145, "Endereço:  ".$endereco->ender. ($this->numero == "" ? "" : ", ".$this->numero."  ".$this->compl));
	$this->objpdf->Text(25, 150, "Município:".$this->munic);
	$this->objpdf->Text(70, 150, "Cep:".$this->cep);
	$this->objpdf->Text(25, 167, "Proprietário:".$this->proprietario);

	$this->objpdf->Rect(15, 180, 180, 30, '');
	$this->objpdf->SetXY(15, 180);
	$this->objpdf->MultiCell(180, 4, 'PARA USO DO CORREIO', 1, 'C', 0);
	$this->objpdf->SetXY(18, 187);
	$this->objpdf->SetFont('Times', '', 6);
	$this->objpdf->Cell(4, 3, '', 1, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Mudou-se', 0, 0, 'L', 0);
	$this->objpdf->Cell(4, 3, '', 1, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Não procurado', 0, 1, 'L', 0);
	$this->objpdf->SetXY(18, 191);
	$this->objpdf->Cell(4, 3, '', 1, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Endereço Insuficiente', 0, 0, 'L', 0);
	$this->objpdf->Cell(4, 3, '', 1, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Ausente', 0, 1, 'L', 0);
	$this->objpdf->SetXY(18, 195);
	$this->objpdf->Cell(4, 3, '', 1, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Não exite o n'.chr(176).' indicado', 0, 0, 'L', 0);
	$this->objpdf->Cell(4, 3, '', 1, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Falecido', 0, 1, 'L', 0);
	$this->objpdf->SetXY(18, 199);
	$this->objpdf->Cell(4, 3, '', 1, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Desconhecido', 0, 0, 'L', 0);
	$this->objpdf->Cell(4, 3, '', 1, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Informação escrita pelo porteiro/síndico', 0, 1, 'L', 0);
	$this->objpdf->SetXY(18, 203);
	$this->objpdf->Cell(4, 3, '', 1, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Recusado', 0, 1, 'L', 0);
	$this->objpdf->SetXY(113, 184);
	$this->objpdf->SetFont('Times', '', 5);
	$this->objpdf->Cell(40, 3, 'Data', 0, 0, 'C', 0);
	$this->objpdf->Cell(40, 3, 'Reintegrado ao serviço postal em:', 0, 0, 'C', 0);
	$this->objpdf->line(110, 184, 110, 210);
	$this->objpdf->line(155, 184, 155, 197);
	$this->objpdf->line(110, 197, 195, 197);
	$this->objpdf->SetXY(113, 197);
	$this->objpdf->Cell(80, 3, 'Assinatura do entregador n'.chr(176), 0, 0, 'C', 0);
	$this->objpdf->Image('imagens/files/logo_boleto.png', 10, 70, 30);


		if ($this->resultfin != false) {
			for ($unicont = 0; $unicont < pg_numrows($this->resultfin); $unicont ++) {
				db_fieldsmemory($this->resultfin, $unicont);
				$vlrhis = db_formatar($uvlrhis, 'f');
				$vlrdesconto = db_formatar($uvlrdesconto, 'f');
				$vlrtotal = db_formatar($utotal, 'f');
				$vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($utotal, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
				//        $vlrbar = "0".str_replace('.','',str_pad(number_format($utotal,2,"","."),11,"0",STR_PAD_LEFT));
				//	$numbanco = "4268" ;// deve ser tirado do db_config
				$resultnumbco = db_query("select numbanco from db_config where codigo = ".db_getsession("DB_instit"));
				$numbanco = pg_result($resultnumbco, 0); // deve ser tirado do db_config
				//        if($utotal > 999)
				//          $vlrbar = "0".$vlrbar;
				//        if($utotal > 9999)
				//          $vlrbar = "0".$vlrbar;
				//        if($utotal > 99999)
				//	  $vlrbar = "0".$vlrbar;

				$numpre = db_numpre($k00_numpre).'000'; //db_formatar(0,'s',3,'e');
				$dtvenc = str_replace("-", "", $dtvencunic);
				$resultcod = db_query("select fc_febraban('816'||'$vlrbar'||'".$numbanco."'||$dtvenc||'000000'||'$numpre')");
				db_fieldsmemory($resultcod, 0);
				$dtvencunic = db_formatar($dtvencunic, 'd');

				$codigo_barras = substr($fc_febraban, 0, strpos($fc_febraban, ','));
				$linha_digitavel = substr($fc_febraban, strpos($fc_febraban, ',') + 1);
				$this->objpdf->SetFont('Times', 'B', 8);
				//$this->objpdf->SetLineWidth(0.05);
				$this->objpdf->SetDash(0.8, 0.8);
				$this->objpdf->Line(05, $linha -9, 203, $linha -9);
				$this->objpdf->SetDash();
				$this->objpdf->Text(32, $linha -2, $pref);
				$this->objpdf->Text(35, $linha +2, $estado);
				$linha = $linha +05;
				$this->objpdf->SetFont('Times', '', 10);
				$this->objpdf->Text(15, $linha +3, $linha_digitavel);
				$this->objpdf->SetFillColor(0, 0, 0);
				$linha = $linha +5;
				$this->objpdf->int25(5, $linha, $codigo_barras, 15, 0.341);
				$this->objpdf->SetFont('Times', 'B', 18);
				$this->objpdf->Text(130, $linha +3, "Cota");
				$this->objpdf->Text(130, $linha +13, "Única");
				$this->objpdf->SetFont('Times', 'B', 5);
				$this->objpdf->Text(125, $linha +17, "NÃO RECEBER APÓS O VENCIMENTO");
				$this->objpdf->SetFont('Times', 'B', 8);
				$this->objpdf->Rect(163, $linha -15, 40, 11);
				$this->objpdf->Rect(163, $linha -4, 40, 11);
				$this->objpdf->Rect(163, $linha +7, 40, 11);
				$this->objpdf->Text(167, $linha -12, "Cód. de Arrecadação");
				$this->objpdf->SetFont('Times', 'B', 10);
				$this->objpdf->Text(167, $linha -6, db_numpre($k00_numpre).'.000');
				$this->objpdf->SetFont('Times', 'B', 8);
				$this->objpdf->Text(173, $linha -1, "Vencimento");
				$this->objpdf->SetFont('Times', 'B', 10);
				$this->objpdf->Text(175, $linha +5, $dtvencunic);
				$this->objpdf->SetFont('Times', 'B', 8);
				$this->objpdf->Text(171, $linha +10, "Valor em Reais:");
				$this->objpdf->SetFont('Times', 'B', 10);
				$this->objpdf->Text(175, $linha +17, db_formatar($utotal, 'f'));
				$linha = $linha +35;
				//$this->objpdf->output();
				//$this->objpdf->addpage();
			}
		}
		//$this->objpdf->addpage();
		pg_free_result($this->resultfin);
	}
	//  else {

	$linha = 125;
	$this->objpdf->AddPage();
	$this->objpdf->Image('imagens/files/logo_boleto.png', 10, 10, 30);
	$this->objpdf->SetFillColor(0, 0, 0);
	$this->objpdf->SetX(5);
	$xlin = 15;
	$this->objpdf->setfont('Times', 'B', 10);
	$this->objpdf->Text(50, $xlin, $pref);
	$this->objpdf->setfont('Times', '', 10);
	$this->objpdf->Text(50, $xlin +6, $estado);
	$this->objpdf->Text(50, $xlin +12, 'IMPOSTO PREDIAL E TERRITORIAL URBANO - '.$j23_anousu); //.db_getsession("DB_anousu"));
	$this->objpdf->setfont('Times', '', 5);
	$this->objpdf->Text(50, $xlin +20, 'CONTRIBUINTE');
	$this->objpdf->setfont('Times', 'B', 8);
	$this->objpdf->Text(70, $xlin +23, $z01_nome);
	$this->objpdf->Text(70, $xlin +26, $z01_ender. ($z01_numero == "" ? "" : ", ".$z01_numero."  ".$z01_compl));
	$this->objpdf->Text(70, $xlin +29, $z01_munic);
	$this->objpdf->setfont('Times', '', 5);
	$this->objpdf->Text(50, $xlin +40, 'ENDEREÇO DO IMÓVEL');
	$this->objpdf->setfont('Times', 'B', 8);
	$this->objpdf->Text(70, $xlin +45, $nomepri.",".$j39_numero." ".$j39_compl);

	$this->objpdf->setfont('Times', '', 4);
	$this->objpdf->Rect(5, 5, 200, 60, 'd');
	//  echo 'sandro'.$j40_refant;exit;		  
	$dadosma = split("\.", trim($j40_refant));

	$this->objpdf->Text(166, 9, 'INSCRIÇÃO');
	$this->objpdf->Text(144, 16, 'ZONA');
	$this->objpdf->Text(155, 16, 'SETOR');
	$this->objpdf->Text(167, 16, 'QUADRA');
	$this->objpdf->Text(182, 16, 'LOTE');
	$this->objpdf->Text(193, 16, 'SUBLOTE');
	$this->objpdf->setfont('Times', 'B', 11);
	$this->objpdf->Text(180, 12, $j01_matric);
	$this->objpdf->setfont('Times', 'B', 9);
	if (isset ($dadosma[0]))
		$this->objpdf->Text(144, 20, $dadosma[0]);
	if (isset ($dadosma[1]))
		$this->objpdf->Text(155, 20, $dadosma[1]);
	if (isset ($dadosma[2]))
		$this->objpdf->Text(167, 20, $dadosma[2]);
	if (isset ($dadosma[3]))
		$this->objpdf->Text(182, 20, $dadosma[3]);
	if (isset ($dadosma[4]))
		$this->objpdf->Text(193, 20, $dadosma[4]);

	$sql = "select sum(j22_valor) as vlredi
	          from iptucale
		  where j22_anousu = $j23_anousu and
		        j22_matric = $j01_matric";
	$sqlres = db_query($sql);
	if (pg_numrows($sqlres) > 0)
		db_fieldsmemory($sqlres, 0);
	else
		$vlredi = 0;

	$sql = "select j23_vlrter, j23_aliq
	          from iptucalc
		  where j23_anousu = $j23_anousu and
		        j23_matric = $j01_matric";
	$sqlres = db_query($sql);
	if (pg_numrows($sqlres) > 0)
		db_fieldsmemory($sqlres, 0);
	else {
		$j23_vlrter = 0;
		$j23_aliq = 0;
	}
	$j23_vlrter += $vlredi;

	pg_free_result($sqlres);

	$this->objpdf->setfont('Times', '', 4);
	$this->objpdf->Text(148, 23, 'BASE DE CÁLCULO');
	$this->objpdf->Text(180, 23, 'ALÍQUOTA');
	$this->objpdf->setfont('Times', 'B', 9);
	$this->objpdf->Text(149, 27, db_formatar($j23_vlrter, 'f'));
	$this->objpdf->Text(180, 27, $j23_aliq);
	$this->objpdf->setfont('Times', '', 8);

	$resultcalc = db_query("select *
	                         from iptucalv
				      left outer join iptucalh on j21_codhis = j17_codhis
				 where j21_anousu = $j23_anousu
				        and j21_matric = $j01_matric
				 order by j21_codhis");
	$dadostot = 0;
	$this->objpdf->SetY(35);
	for ($vlr = 0; $vlr < pg_numrows($resultcalc); $vlr ++) {
		db_fieldsmemory($resultcalc, $vlr);
		$linhas = 35 + ($vlr * 2);
		$this->objpdf->SetX(145);
		$this->objpdf->Cell(30, 3, $j17_descr, 0, 0, "L", 0);
		$this->objpdf->Cell(5, 3, 'R$', 0, 0, "C", 0);
		$this->objpdf->Cell(20, 3, db_formatar($j21_valor, 'f'), 0, 1, "R", 0);
		$dadostot += $j21_valor;
	}
	pg_free_result($resultcalc);
	$this->objpdf->Ln(3);
	$this->objpdf->SetX(145);
	$this->objpdf->Cell(30, 3, "Total:", 0, 0, "L", 0);
	$this->objpdf->Cell(5, 3, 'R$', 0, 0, "C", 0);
	$this->objpdf->Cell(20, 3, db_formatar($dadostot, 'f'), 0, 1, "R", 0);

	$this->objpdf->Rect(140, 7, 63, 56, 'd');
	$this->objpdf->line(140, 14, 203, 14);
	$this->objpdf->line(140, 21, 203, 21);
	$this->objpdf->line(140, 28, 203, 28);
	$this->objpdf->line(152, 14, 152, 21);
	$this->objpdf->line(165, 14, 165, 21);
	$this->objpdf->line(178, 14, 178, 21);
	$this->objpdf->line(191, 14, 191, 21);
	$this->objpdf->line(171, 21, 171, 28);
	$xlin = 65;
	$this->objpdf->SetFont('Times', 'B', 9);
	$this->objpdf->Text(15, $xlin +9, "O PAGAMENTO EM ATRASO SOMENTE PODERÁ SE EFETUADO NA PREFEITURA MUNICIPAL DE $munic");
	$this->objpdf->SetFont('Times', 'B', 7);
	$this->objpdf->Text(15, $xlin +19, "FORMAS DE PAGAMENTO:");

	$resultunica = db_query("select * from recibounica where k00_numpre = $codigos[0]");
	for ($contadorunica = 0; $contadorunica < pg_numrows($resultunica); $contadorunica ++) {
		db_fieldsmemory($resultunica, $contadorunica);
		$this->objpdf->Text(25, $xlin +24 + ($contadorunica * 4), "- Cota Única com pagamento até ".db_formatar($k00_dtvenc, "d")." - ".$k00_percdes."% de desconto *");
	}

	$this->objpdf->Text(25, $xlin +33, "-  Parcelado - em $quant_parcelas vezes - sem desconto");
	$this->objpdf->SetFont('Times', 'B', 7);
	$this->objpdf->Text(15, $xlin +39, "LOCAIS DE PAGAMENTO ATÉ O VENCIMENTO:");

	$this->objpdf->Text(25, $xlin +46, @$agencias);
	$this->objpdf->Text(25, $xlin +51, "-  Via Internet e Home Banking");
	$this->objpdf->SetFont('Times', 'B', 8);

	$sql = "select a.k00_numpre,k00_numpar,k00_numtot,k00_numdig,k00_dtvenc,sum(k00_valor)::float8 as k00_valor 
		                          from arrematric m
								       inner join arrecad a on m.k00_numpre = a.k00_numpre
								  where m.k00_numpre = ".$codigos[0]."
								  group by a.k00_numpre,k00_numpar,k00_numtot,k00_numdig,k00_dtvenc
								  order by a.k00_numpre,k00_numpar desc
								 ";
	$this->resultfin = db_query($sql);
	if ($this->resultfin != false) {
		for ($unicont = 0; $unicont < pg_numrows($this->resultfin); $unicont ++){
			db_fieldsmemory($this->resultfin, $unicont);
			if (array_search($codigos[0].'P'.$k00_numpar, $numpres) == 0) {
				continue;
			}
			$vlrbar = db_formatar(str_replace('.', '', str_pad(number_format($k00_valor, 2, "", "."), 11, "0", STR_PAD_LEFT)), 's', '0', 11, 'e');
			//      $vlrbar = "0".str_replace('.','',str_pad(number_format($k00_valor,2,"","."),11,"0",STR_PAD_LEFT));
			$numbanco = "4268"; // deve ser tirado do db_config
			$resultnumbco = db_query("select numbanco from db_config where codigo = ".db_getsession("DB_instit"));
			$numbanco = pg_result($resultnumbco, 0); // deve ser tirado do db_config
			$numpre = db_numpre($k00_numpre).'00'.$k00_numpar;
			$dtvenc = str_replace("-", "", $k00_dtvenc);
			$resultcod = db_query("select fc_febraban('816'||'$vlrbar'||'".$numbanco."'||$dtvenc||'000000'||'$numpre')");
			if (pg_numrows($resultcod) == 0) {
				$fc_febraban = "";
			} else {
				db_fieldsmemory($resultcod, 0);
			}
			$k00_dtvenc = db_formatar($k00_dtvenc, 'd');
			$k00_valor = db_formatar($k00_valor, 'f');
			$codigo_barras = substr($fc_febraban, 0, strpos($fc_febraban, ','));
			$linha_digitavel = substr($fc_febraban, strpos($fc_febraban, ',') + 1);
			$this->objpdf->SetFont('Times', 'B', 8);
			$this->objpdf->Text(32, $linha +4, $pref);
			$this->objpdf->Text(35, $linha +8, $estado);
			$linha = $linha +10;
			$this->objpdf->SetFont('Times', '', 10);
			$this->objpdf->Text(15, $linha +3, $linha_digitavel);
			$this->objpdf->SetFillColor(0, 0, 0);
			//$this->objpdf->SetLineWidth(0.05);
			$this->objpdf->SetDash(1, 1);
			$this->objpdf->Line(05, $linha -10, 203, $linha -10);
			$this->objpdf->SetDash();
			//$this->objpdf->line(05,$linha-10,203,$linha-10);
			$linha = $linha +5;
			$this->objpdf->int25(5, $linha, $codigo_barras, 15, 0.341);
			$this->objpdf->rect(163, $linha -12, 40, 9);
			$this->objpdf->line(183, $linha -12, 183, $linha -3);
			$this->objpdf->rect(163, $linha -3, 40, 9);
			$this->objpdf->rect(163, $linha +6, 40, 9);

			$this->objpdf->SetFont('Times', '', 18);
			$this->objpdf->Text(130, $linha +3, "IMPOSTO");
			$this->objpdf->SetFont('Times', '', 12);
			$this->objpdf->Text(130, $linha +13, $j01_tipoimp);
			$this->objpdf->SetFont('Times', 'B', 5);
			$this->objpdf->Text(125, $linha +17, "NÃO RECEBER APÓS O VENCIMENTO");

			$this->objpdf->SetFont('Times', '', 8);
			$this->objpdf->Text(165, $linha -9, "Cód. Arrec.");
			$this->objpdf->SetFont('Times', 'B', 9);
			$this->objpdf->Text(164, $linha -5, db_numpre($k00_numpre, $k00_numpar), 0);
			//$this->objpdf->Text(169,$linha-7,$q03_numpre);
			$this->objpdf->SetFont('Times', '', 8);
			$this->objpdf->Text(189, $linha -9, "Parcela: ");
			$this->objpdf->SetFont('Times', 'B', 10);
			$this->objpdf->Text(191, $linha -5, $k00_numpar);

			$this->objpdf->SetFont('Times', '', 8);
			$this->objpdf->Text(165, $linha, "Vencimento:");
			$this->objpdf->SetFont('Times', 'B', 10);
			$this->objpdf->Text(175, $linha +3, $k00_dtvenc);
			$this->objpdf->SetFont('Times', '', 8);
			$this->objpdf->Text(165, $linha +9, "Valor em Reais:");
			$this->objpdf->SetFont('Times', 'B', 10);
			$this->objpdf->Text(175, $linha +14, $k00_valor);
			//$teste .= $k00_numpar.'-'; 
			$linha = $linha +20;
		}
		pg_free_result($this->resultfin);
		//} 
	}



/*
   if ( ($this->qtdcarne % 4 ) == 0 ){
           $this->objpdf->AddPage();
   }
	$this->objpdf->SetLineWidth(0.05);
        $this->qtdcarne += 1;
        $top = $this->objpdf->GetY()-5;
        $this->objpdf->SetFont('Arial','B',8);
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFillColor(250,250,250);
	$this->objpdf->SetX(17);
	$this->objpdf->Text(17,$top,$this->prefeitura,0,0,"L",0);
	$this->objpdf->SetX(105);
	$this->objpdf->Text(105,$top,$this->prefeitura,0,1,"L",0);
	$this->objpdf->SetX(170);
	$this->objpdf->SetX(17);
	$this->objpdf->SetFont('Arial','',7);
	$this->objpdf->Text(17,$top+3,$this->secretaria,0,0,"L",0);
	$this->objpdf->SetX(105);
	$this->objpdf->Text(105,$top+3,$this->secretaria,0,1,"L",0);
	$this->objpdf->Ln(2);
	$this->objpdf->SetFont('Arial','B',8);
	$this->objpdf->SetX(10);
	$this->objpdf->Cell(90,4,$this->tipodebito,0,0,"C",0);
	$this->objpdf->SetX(105);
	$this->objpdf->Cell(90,4,$this->tipodebito,0,1,"C",0);
	$y = $this->objpdf->GetY()-1;
	$this->objpdf->Image('imagens/files/'.$this->logo,8,$y-14,8);
	$this->objpdf->Image('imagens/files/'.$this->logo,95,$y-14,8);
	$this->objpdf->SetFont('Times','',5);
	$this->objpdf->RoundedRect(10,$y+1,32,6,2,'DF','1234'); // matricula/ inscrição
	$this->objpdf->RoundedRect(43,$y+1,27,6,2,'DF','1234'); // cod. de arrecadação
	$this->objpdf->RoundedRect(71,$y+1,20,6,2,'DF','1234'); // parcela

	$this->objpdf->RoundedRect(10,$y+8,81,12,2,'DF','1234'); // nome / endereço
	
	$this->objpdf->RoundedRect(10,$y+21,81,14,2,'DF','1234'); // instruçoes

	$this->objpdf->RoundedRect(10,$y+36,39,7,2,'DF','1234'); // vencimento
	$this->objpdf->RoundedRect(50,$y+36,41,7,2,'DF','1234'); // valor

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+3,$this->titulo1); // matricula/ inscrição
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(13,$y+6,$this->descr1); // numero da matricula ou inscricao

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(45,$y+3,$this->titulo2); // cod. de arrecadação
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(47,$y+6,$this->descr2); // numpre
	
        $this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(73,$y+3,$this->titulo5); // Parcela
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(76,$y+6,$this->descr5); // Parcela inicial e total de parcelas

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+10,$this->titulo3); // contribuinte/endereço
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(13,$y+13,$this->descr3_1); // nome do contribuinte
	$this->objpdf->Text(13,$y+16,$this->descr3_2); // endereço

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+23,$this->titulo4); // Instruções
	$this->objpdf->SetFont('Arial','B',7);
        $xx = $this->objpdf->getx();
        $yy = $this->objpdf->gety();
        $this->objpdf->setleftmargin(10);
        $this->objpdf->setrightmargin(120);
        $this->objpdf->sety($y+23);
        $this->objpdf->multicell(0,3,$this->descr4_1); // Instruções 1 - linha 1
        $this->objpdf->multicell(0,3,$this->descr4_2); // Instruções 1 - linha 2
        $this->objpdf->setxy($xx,$yy);

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(13,$y+38,$this->titulo6); // Vencimento
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(20,$y+41,$this->descr6); // Data de Vencimento

	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(53,$y+38,$this->titulo7); // valor
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(56,$y+41,$this->descr7); // qtd de URM ou valor
	  
	
	$this->objpdf->RoundedRect(95,$y+1,33,6,2,'DF','1234'); // matricula / inscricao
	$this->objpdf->RoundedRect(129,$y+1,27,6,2,'DF','1234'); // cod. arrecadacao
	$this->objpdf->RoundedRect(157,$y+1,20,6,2,'DF','1234'); // parcela
	$this->objpdf->RoundedRect(178,$y+1,31,6,2,'DF','1234'); // livre
	
	$this->objpdf->RoundedRect(95,$y+8,82,13,2,'DF','1234'); // nome / endereco
	$this->objpdf->RoundedRect(95,$y+22,114,13,2,'DF','1234'); // instrucoes
	
	$this->objpdf->RoundedRect(178,$y+8,31,6,2,'DF','1234'); // vencimento
	$this->objpdf->RoundedRect(178,$y+15,31,6,2,'DF','1234'); // valor
	
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(97,$y+3,$this->titulo8); // matricula / inscricao
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(97,$y+6,$this->descr8); // numero da matricula ou inscricao
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(131,$y+3,$this->titulo9); // cod. de arrecadação
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(133,$y+6,$this->descr9); // numpre
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(159,$y+3,$this->titulo10); // parcela
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(162,$y+6,$this->descr10); // parcela e total das parcelas
	
        $this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+3,$this->titulo13); // livre
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(183,$y+6,$this->descr13); // livre
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(97,$y+10,$this->titulo11); // contribuinte / endereço
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(97,$y+13,$this->descr11_1); // nome do contribuinte
	$this->objpdf->Text(97,$y+16,$this->descr11_2); // endereço
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(97,$y+24,$this->titulo12); // instruções
	$this->objpdf->SetFont('Arial','B',7);
        $xx = $this->objpdf->getx();
        $yy = $this->objpdf->gety();
        $this->objpdf->setleftmargin(97);
        $this->objpdf->setrightmargin(2);
        $this->objpdf->sety($y+24);
        $this->objpdf->multicell(0,3,$this->descr12_1); // Instruções 2 - linha 1
        $this->objpdf->multicell(0,3,$this->descr12_2); // Instruções 2 - linha 2
        $this->objpdf->setxy($xx,$yy);
		
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+10,$this->titulo14); // vencimento
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+13,$this->descr14); // data de vencimento
	
	$this->objpdf->SetFont('Arial','',5);
	$this->objpdf->Text(180,$y+17,$this->titulo15); // valor
	$this->objpdf->SetFont('Arial','B',7);
	$this->objpdf->Text(180,$y+20,$this->descr15); // total de URM ou valor

	$this->objpdf->SetLineWidth(0.05);
	$this->objpdf->SetDash(1,1);
    $this->objpdf->Line(93,$y-30,93,$y+60); // linha tracejada vertical
 	$this->objpdf->SetDash(); 
	$this->objpdf->Ln(70);
	$this->objpdf->SetFillColor(0,0,0);
	$this->objpdf->SetFont('Arial','',10);

        $this->objpdf->SetFont('Arial','',4);
        $this->objpdf->TextWithDirection(2,$y+30,$this->texto,'U'); // texto no canhoto do carne

	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text(10,$y+46,$this->descr16_1); // 
	$this->objpdf->Text(10,$y+50,$this->descr16_2); // 
	$this->objpdf->Text(10,$y+54,$this->descr16_3); // 
	$this->objpdf->Text(105,$y+38,$this->linha_digitavel);
	$this->objpdf->int25(95,$y+39,$this->codigo_barras,15,0.341);
*/
?>
