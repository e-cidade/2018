<?

// MODELO 1  - CARNES DE PARCELAMENTO
// MODELO 2  - RECIBO DE PAGAMENTO ( 2 VIAS )
// MODELO 3  - ALVARÁ 
// MODELO 4  - BOLETO
// MODELO 5  - AUTORIZAÇÃO DE EMPENHO
// MODELO 6  - NOTA DE EMPENHO
// MODELO 22 - RECIBO DE PAGAMENTO ( 1 VIAS )

class db_impcarne {

/////   VARIÁVEIS PARA EMISSAO DE CARNES DE PARCELAMENTO - MODELO 1

  var $modelo    = 1;

  var $qtdcarne  = null;
  var $tipodebito= 'TIPO DE DÉBITO';
  var $tipoinscr = null;
  var $prefeitura= 'PREFEITURA DBSELLER';
  var $secretaria= 'SECRETARIA DE FAZENDA';
  var $debito    = null;
  var $logo      = null;
  var $parcela   = null;
  var $titulo1   = '';
  var $descr1    = null;
  var $titulo2   = 'Cód de Arrecadação';
  var $descr2    = null;
  var $titulo3   = 'Contribuinte/Endereço';
  var $descr3_1  = null;
  var $descr3_2  = null;
  var $titulo4   = 'Instruções';
  var $descr4_1  = null;
  var $descr4_2  = null;
  var $titulo5   = 'Parcela';
  var $descr5    = null;
  var $titulo6   = 'Vencimento';
  var $descr6    = null;
  var $titulo7   = 'Valor';
  var $descr7    = null;
  var $titulo8   = '';
  var $descr8    = null;
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
  var $titulo13  = '';
  var $descr13   = null;
  var $titulo14  = 'Vencimento';
  var $descr14   = null;
  var $texto     = null;
  var $titulo15  = 'Valor';
  var $descr15   = null;
  var $descr16_1 = null;
  var $descr16_2 = null;
  var $descr16_3 = null;
  var $linha_digitavel = null;
  var $codigo_barras = null;
  var $objpdf = null;

//////  VARIÁVEIS PARA EMISSAO DE RECIBO DE PAGAMENTO - MODELO 2

  var $enderpref = null;
  var $municpref = null;
  var $telefpref = null;
  var $emailpref = null;
  var $nome      = null;
  var $ender     = null;
  var $compl     = null;
  var $munic     = null;
  var $uf        = null;
  var $cep       = null;
  var $tipoinscr = 'Matr/Inscr';
  var $nrinscr   = null;
  var $ip        = null;
  var $nomepri   = '';
  var $nrpri     = '';
  var $complpri  = '';
  var $bairropri = null;
  var $datacalc  = null;
  var $taxabanc  = 0;
  var $rowspagto = 0;
  var $receita   = null;
  var $dreceita  = null;
  var $ddreceita = null;
  var $valor     = null;
  var $historico = null;
  var $histparcel= null;
  var $recorddadospagto = 0;
  var $linhasdadospagto = 0;
  var $dtvenc    = null;
  var $numpre    = null;
  var $valtotal  = null;
 
//////  VARIÁVEIS PARA EMISSAO DE ALVARÁ

  var $tipoalvara  = null;
  var $obs         = null;
  var $ativ        = null;
  var $descrativ   = null;

//////  FICHA DE COMPENSACAO
  
  var $numbanco		= '';
  var $localpagamento   = '';
  var $cedente		= ''; 
  var $agencia_cedente	= '';
  var $data_documento	= '';
  var $numero_documento = '';
  var $especie_doc	= '';
  var $aceite		= '';
  var $data_processamento = '';
  var $nosso_numero	= '';
  var $codigo_cedente	= '';
  var $carteira		= '';
  var $especie		= '';
  var $quantidade	= '';
  var $valor_documento	= '';
  var $instrucoes1	= '';
  var $instrucoes2	= '';
  var $instrucoes3	= '';
  var $instrucoes4	= '';
  var $instrucoes5	= '';
  var $desconto_abatimento = '';	
  var $outras_deducoes	= '';
  var $mora_multa	= '';
  var $outros_acrecimos	= '';
  var $valor_cobrado	= '';
  var $sacado1		= '';
  var $sacado2		= '';
  var $sacado3		= '';

//// variaveis para a autorização de empenho

  var $numaut 		= null;  	// numero da autorização de empenho
  var $numsol 		= null;  	// numero da solicitação de compra
  var $numcgm 		= null;		// cgm do fornecedor
//  var $nome   		= null;		// nome do fornecedor
//  var $ender  		= null;		// endereço do fornecedor
//  var $munic  		= null;		// municipio do fornecedor
  var $dotacao 		= null;		// dotacao orcamentaria (orgao,unidade,funcao,subfuncao,programa,projativ,elemento,recurso)
  var $descrdotacao 	= null;		// descricao da dotacao
  var $coddot		= null;		// codigo reduzido da despesa
  var $destino		= null;		// destino do material ou serviço
  var $licitacao  	= null;		// tipo de licitação
  var $num_licitacao  	= null;		// numero da licitação
  var $descr_licitacao 	= null;		// descrição do tipo de licitação
  var $descr_tipocompra	= null;		// descrição do tipo de compra
  var $prazo_ent  	= null;		// prazo de entrega
  var $obs		= null;		// observações
  var $cond_pag		= null;		// condições de pagamento
  var $out_cond		= null;		// outras condições de pagamento
  var $contato		= null;		// contato 
  var $telef_cont 	= null;		// telefone do contato
  var $recorddositens 	= null;		// record set dos itens
  var $linhasdositens 	= null;		// numero de itens da autorizacao
  var $item	    	= null;		// codigo do item
  var $quantitem    	= null;		// quantidade do item
  var $valoritem    	= null;		// valor unitário do item
  var $observacaoitem   = null;
  var $descricaoitem    = null;
	
/// variaveis para a nota de empenho

  var $numemp 		= null;  	// numero do empenho
  var $orgao		= null;		// órgão da dotação
  var $descr_orgao	= null;		// órgão da dotação
  var $unidade		= null;		// unidade da dotação
  var $descr_unidade	= null;		// unidade da dotação
  var $funcao		= null;		// função da dotação
  var $descr_funcao	= null;		// função da dotação
  var $subfuncao	= null;		// subfunção da dotação
  var $descr_subfuncao	= null;		// subfunção da dotação
  var $programa		= null;		// programa da dotação
  var $descr_programa	= null;		// programa da dotação
  var $projativ		= null;		// projativ da dotação
  var $descr_projativ	= null;		// projativ da dotação
  var $analitico	= null;		// elemento da dotação analitico
  var $descr_analitico  = null;		// descricao do elemento da dotação analitico
  var $sintetico	= null;		// elemento da dotação sintetico
  var $descr_sintetico  = null;		// descricao do elemento da dotação sintetico
  var $recurso		= null;		// recurso da dotação
  var $descr_recurso	= null;		// recurso da dotação
  var $orcado		= null;		// valor orcado
  var $saldo_ant	= null;		// saldo anterior
  var $empenhado	= null;		// valor do empenho
  var $emissao		= null;		// data da geracao do empenho

  
  function db_impcarne($objpdf){
    $this->objpdf = $objpdf;
 
 
  }
 
  function imprime() {

/////////////  MODELO 1  -  CARNES DE PARCELAMENTO 

     if($this->modelo == 1){
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
//        $this->objpdf->multicell(0,3,$this->descr4_2); // Instruções 1 - linha 2
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
//        $this->objpdf->Line(5,$y+58,205,$y+58); // linha tracejada horizontal
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
/*
        $y = $this->objpdf->gety();
        $this->objpdf->setleftmargin(10);
        $this->objpdf->setrightmargin(120);
        $this->objpdf->sety($y+28);
        $this->objpdf->multicell(0,3,$this->descr4_1); // Instruções 1 - linha 1
        $this->objpdf->ln(39.5);
*/ 
    }else if ( $this->modelo == 22 ) {       
	
/////////// MODELO 22  -  RECIBO DE PAGAMENTO (UM POR FOLHA)
	
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->Setfont('Arial','B',12);
	$this->objpdf->setfillcolor(245);
	$this->objpdf->roundedrect(05,05,200,288,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
	$this->objpdf->Image('imagens/files/'.$this->logo,45,9,20);
	$this->objpdf->text(70,15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',12);
	$this->objpdf->text(70,20,$this->enderpref);
	$this->objpdf->text(70,25,$this->municpref);
	$this->objpdf->text(70,30,$this->telefpref);
	$this->objpdf->text(70,35,$this->emailpref);

	$this->objpdf->setfillcolor(245);
	$this->objpdf->Roundedrect(15,45,110,35,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(16,47,'Identificação:');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(16,51,'Nome :');
	$this->objpdf->text(32,51,$this->nome);
	$this->objpdf->text(16,56,'Endereço :');
	$this->objpdf->text(32,56,$this->ender);
	$this->objpdf->text(16,60,'Município :');
	$this->objpdf->text(32,60,$this->munic);
	$this->objpdf->text(16,64,'CEP :');
	$this->objpdf->text(32,64,$this->cep);
	$this->objpdf->text(16,68,'Data :');
	$this->objpdf->text(32,68,date('d/m/Y'));
	$this->objpdf->text(50,68,'Hora: '.date("H:i:s"));
	$this->objpdf->text(16,72,$this->tipoinscr);
	$this->objpdf->text(32,72,$this->nrinscr);
	$this->objpdf->text(16,76,'IP :');
	$this->objpdf->text(32,76,$this->ip);
	$this->objpdf->Setfont('Arial','',6);
	
	$this->objpdf->Roundedrect(130,45,65,35,2,'DF','1234');
	$this->objpdf->text(132,47,$this->tipoinscr);
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(132,50,$this->nrinscr);
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(132,55,'Logradouro :');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(132,58,$this->nomepri);
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(132,63,'Número/Complemento :');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(132,66,$this->nrpri."      ".$this->complpri);
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text(132,71,'Bairro :');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text(132,74,$this->bairropri);
	
	$this->objpdf->Setfont('Arial','B',11);
	$this->objpdf->text(70,87,'RECIBO VÁLIDO ATÉ: '.$this->datacalc);
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->Roundedrect(15,90,180,65,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',8);
	
	$this->objpdf->SetXY(17,96);
	if($this->taxabanc!=0){
	  $this->objpdf->Cell(20,4,'Taxa Bancária',0,0,"L",0);
	  $this->objpdf->Cell(20,4,db_formatar($this->taxabanc,'f'),0,1,"R",0);
	}
	
	for($i = 0;$i < $this->linhasdadospagto ;$i++) {
	   $this->objpdf->setx(17);
	   $this->objpdf->cell(5,4,trim(pg_result($this->recorddadospagto,$i,$this->receita)),0,0,"C",0);
           if ( trim(pg_result($this->recorddadospagto,$i,$this->ddreceita) ) == ''){
     		$this->objpdf->cell(70,4,trim(pg_result($this->recorddadospagto,$i,$this->dreceita)),0,0,"L",0);
           }else{ 
		$this->objpdf->cell(70,4,trim(pg_result($this->recorddadospagto,$i,$this->ddreceita)),0,0,"L",0);
           }
	   $this->objpdf->cell(15,4,db_formatar(pg_result($this->recorddadospagto,$i,$this->valor),'f'),0,1,"R",0);
	}
	$this->objpdf->SetXY(15,158);
	$this->objpdf->multicell(0,4,'HISTÓRICO :   '.$this->historico);
	$this->objpdf->setx(15);
	$this->objpdf->multicell(0,4,$this->histparcel);
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Roundedrect(10,195,190,46,2,'DF','1234');
	
	$this->objpdf->setfont('Arial','',6);
	$this->objpdf->setfillcolor(245);
	$this->objpdf->Roundedrect(40,200,48,10,2,'DF','1234');
	$this->objpdf->Roundedrect(93,200,48,10,2,'DF','1234');
	$this->objpdf->Roundedrect(146,200,48,10,2,'DF','1234');
	$this->objpdf->text(42,202,'Vencimento');
	$this->objpdf->text(95,202,'Código de Arrecadação');
	$this->objpdf->text(148,202,'Valor a Pagar');
	$this->objpdf->setfont('Arial','',10);
	$this->objpdf->text(48,207,$this->dtvenc);
	$this->objpdf->text(101,207,$this->numpre);
	$this->objpdf->text(153,207,$this->valtotal);
	
	$this->objpdf->SetDash(0.8,0.8);
	$this->objpdf->line(5,242.5,205,242.5);
	$this->objpdf->SetDash();
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Roundedrect(10,244,190,46,2,'DF','1234');
	$this->objpdf->setfont('Arial','',12);
	$this->objpdf->setfillcolor(0,0,0);
	$this->objpdf->Image('imagens/files/'.$this->logo,12,200,25);
	$this->objpdf->text(60,218,$this->linhadigitavel);
	$this->objpdf->int25(60,220,$this->codigobarras,15,0.341);
	$this->objpdf->setfillcolor(245);
	$this->objpdf->Roundedrect(40,250,48,10,2,'DF','1234');
	$this->objpdf->Roundedrect(93,250,48,10,2,'DF','1234');
	$this->objpdf->Roundedrect(146,250,48,10,2,'DF','1234');
	$this->objpdf->setfont('Arial','',6);
	$this->objpdf->text(42,252,'Vencimento');
	$this->objpdf->text(95,252,'Código de Arrecadação');
	$this->objpdf->text(148,252,'Valor a Pagar');
	$this->objpdf->setfont('Arial','',10);
	$this->objpdf->text(48,257,$this->dtvenc);
	$this->objpdf->text(101,257,$this->numpre);
	$this->objpdf->text(153,257,$this->valtotal);
	$this->objpdf->Image('imagens/files/'.$this->logo,12,250,25);
        $this->objpdf->SetFont('Arial','',5);
        $this->objpdf->text(10,$this->objpdf->h-2,'Base: '.@$GLOBALS["DB_NBASE"]);
	$this->objpdf->setfillcolor(0,0,0);
	$this->objpdf->setfont('Arial','',12);
	$this->objpdf->text(60,268,$this->linhadigitavel);
	$this->objpdf->int25(60,270,$this->codigobarras,15,0.341);
    }else if ( $this->modelo == 3 ) {

/////////// MODELO 3  -  ALVARÁ
    
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',12);
	$coluna = 60;
	$linha = 45;
	$this->objpdf->SetLineWidth(1);
	$this->objpdf->RoundedRect(37,0.2,137,195,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
	$this->objpdf->roundedrect(39,2,133,191,2,'1234');
	$this->objpdf->SetLineWidth(0.2);
	$this->objpdf->Image('imagens/files/Brasao.png',43,5,20);
	$this->objpdf->Image('imagens/files/Brasao.jpg',60,30,100);
	$this->objpdf->roundedrect(50,$linha+35,110,40,2,'1234');
	$this->objpdf->setdrawcolor(235);

	$this->objpdf->setxy(65,5);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->Multicell(0,8,$this->prefeitura,"C"); // prefeitura

	$this->objpdf->setxy(65,10);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->setleftmargin(50);
	$this->objpdf->setrightmargin(50);
	$this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara

	$this->objpdf->settextcolor(150);
	$this->objpdf->setxy(85,30);
	$this->objpdf->setfont('Arial','B',60);
	$this->objpdf->Multicell(0,8,date('Y'),"C"); // exercicio
	$this->objpdf->settextcolor(0,0,0);

	$this->objpdf->setxy(84,29);
	$this->objpdf->setfont('Arial','B',60);
	$this->objpdf->Multicell(0,8,date('Y'),"C"); // exercicio

	$this->objpdf->sety(50);
	$this->objpdf->SetFont('Arial','',11);
	$this->objpdf->Multicell(0,6,$this->texto); // texto
	$this->objpdf->SetFont('Arial','B',12);
	$this->objpdf->Text($coluna+20,$linha+103,"Sapiranga, ".date('d')." de ".db_mes( date('m') )." de ".date('Y')); // data

	$this->objpdf->sety(125);
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Multicell(0,6,$this->obs); // observação
	$this->objpdf->setfont('arial','',6);
        $this->objpdf->SetXY($coluna-35,160);
        $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".'SECRETÁRIO DA IND. COM. E TURISMO',0,"C",0);
        $this->objpdf->SetXY($coluna+35,160);
        $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);
	$this->objpdf->sety(180);
        $this->objpdf->setfont('arial','B',12);
        $this->objpdf->multicell(0,8,'FIXAR EM LUGAR VISÍVEL',1,"C");
	$this->objpdf->SetFont('Arial','B',10);

	$this->objpdf->Text($coluna,$linha+48,'CCM : '.$this->ativ.' / '.$this->nrinscr); // atividade / inscricao
	$this->objpdf->Text($coluna,$linha+52,$this->nome); // nome
	$this->objpdf->Text($coluna,$linha+56,$this->ender); // endereco
	$this->objpdf->Text($coluna,$linha+60,$this->compl); // complemento
	$this->objpdf->Text($coluna,$linha+64,$this->descrativ); // descrição da atividade
    }else if ( $this->modelo == 4 ) {
       
///////////// MODELO 4  -  BOLETO BANCÁRIO
        
        $linha = 186;
	$pdf->Line(47,$linha,47,$linha+9);
	$pdf->Line(63,$linha,63,$linha+9);
	$pdf->SetLineWidth(0.6);
        $pdf->Line(10,$linha+9,195,$linha+9);
	$pdf->SetLineWidth(0.2);

	$pdf->Line(10,$linha+17,195,$linha+17);
	$pdf->Line(10,$linha+25,195,$linha+25);
	$pdf->Line(10,$linha+33,195,$linha+33);
	$pdf->Line(10,$linha+41,195,$linha+41);
	$pdf->Line(149,$linha+49,195,$linha+49);
	$pdf->Line(149,$linha+57,195,$linha+57);
	$pdf->Line(149,$linha+65,195,$linha+65);
	$pdf->Line(149,$linha+73,195,$linha+73);
	$pdf->Line(10,$linha+81,195,$linha+81);

	$pdf->Line(149,$linha+9,149,$linha+81);
	$pdf->Line(169,$linha+9,169,$linha+17);
	$pdf->Line(40,$linha+25,40,$linha+33);
	$pdf->Line(86,$linha+25,86,$linha+33);
	$pdf->Line(112,$linha+25,112,$linha+33);
	$pdf->Line(125,$linha+25,125,$linha+33);

	$pdf->Line(45,$linha+33,45,$linha+41);
	$pdf->Line(65,$linha+33,65,$linha+41);
	$pdf->Line(91,$linha+33,91,$linha+41);
	$pdf->Line(121,$linha+33,121,$linha+41);

	$pdf->Line(10,$linha+93,195,$linha+93);
	
        //codigo de barras
        $this->objpdf->SetFillColor(0,0,0);
        
	$this->objpdf->int25(10,$linha+94,$this->codigo_barras,20,0.341);
        
	
        // quadrado inferior //
	$this->objpdf->Image('imagens/files/Brasao.png',10,187,35,7);
	$this->objpdf->SetFont('Arial','b',14);
	$this->objpdf->Text(49,$linha+7,$this->numbanco);			// numero do banco
	$this->objpdf->SetFont('Arial','b',11);
	$this->objpdf->Text(70,$linha+7,$this->linha_digitavel);
	$this->objpdf->SetFont('Arial','b',5);
	$this->objpdf->Text(13,$linha+11,"Local de Pagamento");
	$this->objpdf->Text(151,$linha+11,"Parcela");
	$this->objpdf->Text(171,$linha+11,"Vencimento");
	$this->objpdf->Text(13,$linha+19,"Cedente");
        $this->objpdf->Text(151,$linha+19,"Agência/Código Cedente");
	$this->objpdf->Text(13,$linha+27,"Data do Documento");
	$this->objpdf->Text(42,$linha+27,"Número do Documento");
	$this->objpdf->Text(88,$linha+27,"Espécie Doc.");
	$this->objpdf->Text(114,$linha+27,"Aceite");
        $this->objpdf->Text(127,$linha+27,"Data do Processamento");
        $this->objpdf->Text(151,$linha+27,"Nosso Número");
        $this->objpdf->Text(13,$linha+35,"Código do Cedente");
        $this->objpdf->Text(47,$linha+35,"Carteira");
	$this->objpdf->Text(67,$linha+35,"Espécie");
        $this->objpdf->Text(93,$linha+35,"Quantidade");
        $this->objpdf->Text(123,$linha+35,"Valor");
	$this->objpdf->Text(151,$linha+35,"( = ) Valor do Documento");
        $this->objpdf->Text(13,$linha+43,"Instruções");
	$this->objpdf->Text(151,$linha+43,"( - ) Desconto / Abatimento");
	$this->objpdf->Text(151,$linha+51,"( - ) Outras Deduções");
	$this->objpdf->Text(151,$linha+59,"( + ) Mora / Multa");
	$this->objpdf->Text(151,$linha+67,"( + ) Outros Acrécimos");
	$this->objpdf->Text(151,$linha+75,"( = ) Valor Cobrado");
	$this->objpdf->Text(13,$linha+83,"Sacado");
	$this->objpdf->Text(13,$linha+91,"Sacador/Avalista");
	$this->objpdf->Text(160,$linha+99,"Autenticação Mecânica");
	
	$this->objpdf->SetFont('Arial','b',8);
        $this->objpdf->Text(13,$linha+15,$this->localpagamento);  	// local de pagamento
	$this->objpdf->SetFont('Arial','',10);
	$this->objpdf->Text(151,$linha+15,$this->parcela);  		// parcela
	$this->objpdf->Text(171,$linha+15,$this->dtvenc);  		// vencimento
	$this->objpdf->Text(13,$linha+23,$this->cedente);  		// cedente
        $this->objpdf->Text(151,$linha+23,$this->agencia_cedente);  	// agencia do cedente
        $this->objpdf->Text(13,$linha+31,$this->data_documento);  	// data do documento
        $this->objpdf->Text(42,$linha+31,$this->numero_documento);	// numero do documento
        $this->objpdf->Text(88,$linha+31,$this->especie_doc);  		// especie do documento
	$this->objpdf->Text(114,$linha+31,$this->aceite);  		// aceite
        $this->objpdf->Text(127,$linha+31,$this->data_processamento);	// data do processamento
        $this->objpdf->Text(151,$linha+31,$this->nosso_numero);  	// nosso numero
	$this->objpdf->Text(13,$linha+39,$this->codigo_cedente); 	// codigo do cedente
        $this->objpdf->Text(47,$linha+39,$this->carteira);  		// carteira
	$this->objpdf->Text(67,$linha+39,$this->especie);  		// especie
	$this->objpdf->Text(93,$linha+39,$this->quantidade); 		// quantidade
	$this->objpdf->Text(123,$linha+39,$this->valor);  		// valor
	$this->objpdf->Text(151,$linha+39,$this->valor_documento);  	// valor do documento
	
	$this->objpdf->Text(20,$linha+54,$this->instrucoes1);  		// instrucoes 1
	$this->objpdf->Text(20,$linha+58,$this->instrucoes2);  		// instrucoes 2
	$this->objpdf->Text(20,$linha+62,$this->instrucoes3);  		// instrucoes 3
	$this->objpdf->Text(15,$linha+70,$this->instrucoes4);  		// instrucoes 4
	$this->objpdf->Text(20,$linha+74,$this->instrucoes5);  		// instrucoes 5

	$this->objpdf->Text(151,$linha+47,$this->desconto_abatimento);	// desconto/abatimento
	$this->objpdf->Text(151,$linha+55,$this->outras_deducoes); 	// outras deducoes
	$this->objpdf->Text(151,$linha+63,$this->mora_multa);  		// multa
	$this->objpdf->Text(151,$linha+71,$this->outros_acrecimos);	// outros acrescimos
        $this->objpdf->Text(151,$linha+79,$this->valor_cobrado);  	// valor cobrado
        
        $this->objpdf->Text(29,$linha+85,$this->sacado1);  		// sacado 1
        $this->objpdf->Text(29,$linha+88,$this->sacado2);		// sacado 2
	$this->objpdf->Text(29,$linha+91,$this->sacado3);		// sacado 3
	
    }else if ( $this->modelo == 2 ) {     
      
////////// MODELO 2  -  RECIBO DE PAGAMENTO (2 POR FOLHA)
	
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
        $this->objpdf->line(2,148.5,208,148.5);
	$xlin = 20;
	$xcol = 4;
       	for ($i = 0;$i < 2;$i++){
		$this->objpdf->setfillcolor(245);
		$this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
		$this->objpdf->setfillcolor(255,255,255);
//		$this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
		$this->objpdf->Setfont('Arial','B',11);
		$this->objpdf->text(150,$xlin-13,'RECIBO VÁLIDO ATÉ: ');
		$this->objpdf->text(159,$xlin-8,$this->datacalc);
		$this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
		$this->objpdf->Setfont('Arial','B',9);
		$this->objpdf->text(40,$xlin-15,$this->prefeitura);
		$this->objpdf->Setfont('Arial','',9);
		$this->objpdf->text(40,$xlin-11,$this->enderpref);
		$this->objpdf->text(40,$xlin-8,$this->municpref);
		$this->objpdf->text(40,$xlin-5,$this->telefpref);
		$this->objpdf->text(40,$xlin-2,$this->emailpref);
//		$this->objpdf->setfillcolor(245);
	
		$this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+119,20,2,'DF','1234');
		$this->objpdf->Setfont('Arial','',6);
		$this->objpdf->text($xcol+2,$xlin+4,'Identificação:');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+2,$xlin+7,$this->tipoinscr);
		$this->objpdf->text($xcol+17,$xlin+7,$this->nrinscr);
		$this->objpdf->text($xcol+30,$xlin+7,'Nome :');
		$this->objpdf->text($xcol+40,$xlin+7,$this->nome);
		$this->objpdf->text($xcol+2,$xlin+11,'Endereço :');
		$this->objpdf->text($xcol+17,$xlin+11,$this->ender);
		$this->objpdf->text($xcol+2,$xlin+15,'Município :');
		$this->objpdf->text($xcol+17,$xlin+15,$this->munic);
		$this->objpdf->text($xcol+75,$xlin+15,'CEP :');
		$this->objpdf->text($xcol+82,$xlin+15,$this->cep);
		$this->objpdf->text($xcol+2,$xlin+19,'Data :');
		$this->objpdf->text($xcol+17,$xlin+19,date('d/m/Y'));
		$this->objpdf->text($xcol+40,$xlin+19,'Hora: '.date("H:i:s"));
		$this->objpdf->text($xcol+75,$xlin+19,'IP :');
		$this->objpdf->text($xcol+82,$xlin+19,$this->ip);
		$this->objpdf->Setfont('Arial','',6);
	
		$this->objpdf->Roundedrect($xcol+126,$xlin+2,76,20,2,'DF','1234');
		$this->objpdf->text($xcol+128,$xlin+7,$this->tipoinscr);
		$this->objpdf->text($xcol+145,$xlin+7,$this->nrinscr);
		$this->objpdf->text($xcol+128,$xlin+11,'Logradouro :');
		$this->objpdf->text($xcol+145,$xlin+11,$this->nomepri);
		$this->objpdf->text($xcol+128,$xlin+15,'N'.chr(176).'/Compl :');
		$this->objpdf->text($xcol+145,$xlin+15,$this->nrpri."      ".$this->complpri);
		$this->objpdf->text($xcol+128,$xlin+19,'Bairro :');
		$this->objpdf->text($xcol+145,$xlin+19,$this->bairropri);

//		$this->objpdf->setfillcolor(245);
		$this->objpdf->Roundedrect($xcol,$xlin+24,202,45,2,'DF','1234');
	   	$this->objpdf->sety($xlin+28);
                $maiscol = 0;
                $yy = $this->objpdf->gety();
		for($ii = 0;$ii < $this->linhasdadospagto ;$ii++) {
                   if ($ii == 10 ){
                      $maiscol = 100;
                      $this->objpdf->sety($yy);
                   }
	   	   $this->objpdf->setx($xcol+3+$maiscol);
	   	   $this->objpdf->cell(5,3,trim(pg_result($this->recorddadospagto,$ii,$this->receita)),0,0,"R",0);
           	   if ( trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita) ) == ''){
     		      $this->objpdf->cell(70,3,trim(pg_result($this->recorddadospagto,$ii,$this->dreceita)),0,0,"L",0);
           	   }else{ 
	  	      $this->objpdf->cell(70,3,trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita)),0,0,"L",0);
        	   }
 		   $this->objpdf->cell(15,3,db_formatar(pg_result($this->recorddadospagto,$ii,$this->valor),'f'),0,1,"R",0);
		}
		$this->objpdf->Roundedrect($xcol,$xlin+71,202,30,2,'DF','1234');
		$this->objpdf->SetY($xlin+72);
		$this->objpdf->SetX($xcol+3);
		$this->objpdf->multicell(0,4,'HISTÓRICO :   '.$this->historico);
		$this->objpdf->SetX($xcol+3);
		$this->objpdf->multicell(0,4,$this->histparcel);
		$this->objpdf->Setfont('Arial','',6);
		$this->objpdf->setx(15);

		$this->objpdf->Roundedrect(128,$xlin+103,38,10,2,'DF','1234');
		$this->objpdf->Roundedrect(168,$xlin+103,38,10,2,'DF','1234');
		$this->objpdf->Roundedrect(146,$xlin+115,40,10,2,'DF','1234');
		$this->objpdf->text(130,$xlin+105,'Vencimento');
		$this->objpdf->text(170,$xlin+105,'Código de Arrecadação');
		$this->objpdf->text(148,$xlin+118,'Valor a Pagar');
		$this->objpdf->setfont('Arial','',10);
		$this->objpdf->text(135,$xlin+110,$this->dtvenc);
		$this->objpdf->text(170,$xlin+110,$this->numpre);
		$this->objpdf->text(155,$xlin+123,$this->valtotal);

		$this->objpdf->setfillcolor(0,0,0);
		$this->objpdf->SetFont('Arial','',4);
	        $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
		$this->objpdf->setfont('Arial','',11);
		$this->objpdf->text(10,$xlin+108,$this->linhadigitavel);
		$this->objpdf->int25(10,$xlin+110,$this->codigobarras,15,0.341);
	        $xlin = 169;
      }
    }else if ( $this->modelo == 5 ) {     
      
////////// MODELO 5  -  AUTORIZACAO DE EMPENHO
	
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->roundedrect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	$this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	$this->objpdf->text(133.5,$xlin-8,'SOLICITAÇÃO DE COMPRA N'.CHR(176));
	$this->objpdf->text(185,$xlin-8,db_formatar($this->numsol,'s','0',6,'e'));
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);

	$this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+100,28,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+4,'Dados da Ordem de Compra');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+2,$xlin+ 8,'Licitação');
	$this->objpdf->text($xcol+2,$xlin+12,'Tipo de Compra');
	$this->objpdf->text($xcol+2,$xlin+16,'Prazo de Entrega');
	$this->objpdf->text($xcol+2,$xlin+20,'Observações');
	$this->objpdf->text($xcol+2,$xlin+24,'Cond.de Pagto');
	$this->objpdf->text($xcol+2,$xlin+28,'Outras Condições');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+27,$xlin+ 8,':  '.$this->num_licitacao.'  -  '.$this->descr_licitacao);
	$this->objpdf->text($xcol+27,$xlin+12,':  '.$this->descr_tipocompra);
	$this->objpdf->text($xcol+27,$xlin+16,':  '.$this->prazo_ent);
	$this->objpdf->text($xcol+27,$xlin+20,':  '.$this->obs);
	$this->objpdf->text($xcol+27,$xlin+24,':  '.$this->cond_pag);
	$this->objpdf->text($xcol+27,$xlin+28,':  '.$this->out_cond);

	$this->objpdf->Roundedrect($xcol+106,$xlin+2,96,28,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+110,$xlin+4,'Dados da Dotação');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+108,$xlin+ 8,'Dotação');
	$this->objpdf->text($xcol+108,$xlin+12,'Reduzido');
	$this->objpdf->text($xcol+108,$xlin+16,'Descrição');
	$this->objpdf->text($xcol+108,$xlin+20,'Órgão');
	$this->objpdf->text($xcol+108,$xlin+24,'Unidade');
	$this->objpdf->text($xcol+108,$xlin+28,'Destino');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+122,$xlin+ 8,':  '.$this->dotacao);
	$this->objpdf->text($xcol+122,$xlin+12,':  '.$this->coddot.'-'.db_CalculaDV($this->coddot));
	$this->objpdf->text($xcol+122,$xlin+16,':  '.$this->descrdotacao);
	$this->objpdf->text($xcol+122,$xlin+20,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	$this->objpdf->text($xcol+122,$xlin+24,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	$this->objpdf->text($xcol+122,$xlin+28,':  '.$this->destino);

	$this->objpdf->Roundedrect($xcol,$xlin+32,$xcol+198,20,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+34,'Dados do Credor');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
	$this->objpdf->text($xcol+  2,$xlin+38,'Nome');
	$this->objpdf->text($xcol+  2,$xlin+42,'Endereço');
	$this->objpdf->text($xcol+102,$xlin+42,'Complemento');
	$this->objpdf->text($xcol+  2,$xlin+46,'Município');
	$this->objpdf->text($xcol+115,$xlin+46,'CEP');
	$this->objpdf->text($xcol+  2,$xlin+50,'Contato');
	$this->objpdf->text($xcol+110,$xlin+50,'Telefone');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
	$this->objpdf->text($xcol+18,$xlin+ 38,':  '.$this->nome);
	$this->objpdf->text($xcol+18,$xlin+ 42,':  '.$this->ender);
	$this->objpdf->text($xcol+122,$xlin+42,':  '.$this->compl);
	$this->objpdf->text($xcol+18,$xlin+ 46,':  '.$this->munic.'-'.$this->uf);
	$this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
	$this->objpdf->text($xcol+18,$xlin+ 50,':  '.$this->contato);
	$this->objpdf->text($xcol+122,$xlin+50,':  '.$this->telef_cont);
	
	$this->objpdf->Setfont('Arial','B',8);
//	$this->objpdf->Roundedrect($xcol,$xlin+54,202,80,2,'DF','1234');
	$this->objpdf->Roundedrect($xcol    ,$xlin+54,15,6,2,'DF','12');
	$this->objpdf->Roundedrect($xcol+ 15,$xlin+54,20,6,2,'DF','12');
	$this->objpdf->roundedrect($xcol+ 35,$xlin+54,107,6,2,'DF','12');
	$this->objpdf->roundedrect($xcol+142,$xlin+54,30,6,2,'DF','12');
	$this->objpdf->roundedrect($xcol+172,$xlin+54,30,6,2,'DF','12');


	$this->objpdf->Roundedrect($xcol,    $xlin+60,15,155,2,'DF','34');
	$this->objpdf->Roundedrect($xcol+ 15,$xlin+60,20,155,2,'DF','34');
	$this->objpdf->roundedrect($xcol+ 35,$xlin+60,107,155,2,'DF','34');
	$this->objpdf->roundedrect($xcol+142,$xlin+60,30,155,2,'DF','34');
	$this->objpdf->roundedrect($xcol+172,$xlin+60,30,155,2,'DF','34');
	
   	$this->objpdf->sety($xlin+28);
	$alt = 4;
	
	$this->objpdf->text($xcol+   4,$xlin+58,'ITEM');
	$this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
	$this->objpdf->text($xcol+  70,$xlin+58,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+58,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 176,$xlin+58,'VALOR TOTAL');
        $maiscol = 0;
	
        $this->objpdf->SetWidths(array(10,22,105,30,30));
	$this->objpdf->SetAligns(array('C','C','L','R','R'));
	
	$this->objpdf->setleftmargin(8);
	$this->objpdf->sety($xlin+62);

	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	  db_fieldsmemory($this->recorddositens,$ii);
	  $this->objpdf->Setfont('Arial','',8);
          $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),pg_result($this->recorddositens,$ii,$this->quantitem),pg_result($this->recorddositens,$ii,$this->descricaoitem)."\n".pg_result($this->recorddositens,$ii,$this->observacaoitem),db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/pg_result($this->recorddositens,$ii,$this->quantitem),'f'),db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	  $this->objpdf->Setfont('Arial','B',8);
 /////// troca de pagina
	  if( ( $this->objpdf->gety() > $this->objpdf->h - 70 && $pagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h - 22 && $pagina != 1 )){
            if ($this->objpdf->PageNo() == 1){
	       $this->objpdf->text(110,$xlin+214,'Continua na Página '.($this->objpdf->PageNo()+1));
               $this->objpdf->Roundedrect($xcol,$xlin+217,202,55,2,'DF','1234');
	       
	       $y = 260;
               $this->objpdf->SetXY(2,$y);
               $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'DIRETOR DE COMPRAS',0,"C",0);
	       
               $this->objpdf->SetXY(72,$y);
               $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'SECRETARIA DE FINANÇAS',0,"C",0);
	       
               $this->objpdf->SetXY(142,$y);
               $this->objpdf->MultiCell(70,4,'VISTO'."\n\n\n".'',0,"C",0);
	       
               $this->objpdf->setfillcolor(0,0,0);
	       $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
               $this->objpdf->SetFont('Arial','',4);
               $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
               $this->objpdf->setfont('Arial','',11);
            }else{
	       $this->objpdf->text(110,$xlin+320,'Continua na Página '.($this->objpdf->PageNo()+1));
	    }
            $this->objpdf->addpage();
            $pagina += 1;	   
	    
  	    $this->objpdf->settopmargin(1);
	    $xlin = 20;
	    $xcol = 4;
	
	    $this->objpdf->setfillcolor(245);
	    $this->objpdf->roundedrect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	    $this->objpdf->setfillcolor(255,255,255);
	    $this->objpdf->Setfont('Arial','B',9);
	    $this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	    $this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	    $this->objpdf->text(133.5,$xlin-8,'SOLICITAÇÃO DE COMPRA N'.CHR(176));
	    $this->objpdf->text(185,$xlin-8,db_formatar($this->numsol,'s','0',6,'e'));
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

  	    $this->objpdf->Roundedrect($xcol,$xlin+54,15,6,2,'DF','12');
	    $this->objpdf->Roundedrect($xcol+15,$xlin+54,20,6,2,'DF','12');
	    $this->objpdf->roundedrect($xcol+35,$xlin+54,107,6,2,'DF','12');
	    $this->objpdf->roundedrect($xcol+142,$xlin+54,30,6,2,'DF','12');
	    $this->objpdf->roundedrect($xcol+172,$xlin+54,30,6,2,'DF','12');

  	    $this->objpdf->Roundedrect($xcol,$xlin+60,15,262,2,'DF','34');
	    $this->objpdf->Roundedrect($xcol+15,$xlin+60,20,262,2,'DF','34');
	    $this->objpdf->roundedrect($xcol+35,$xlin+60,107,262,2,'DF','34');
	    $this->objpdf->roundedrect($xcol+142,$xlin+60,30,262,2,'DF','34');
	    $this->objpdf->roundedrect($xcol+172,$xlin+60,30,262,2,'DF','34');
	    
   	    $this->objpdf->sety($xlin+66);
	    $alt = 4;
	
	    $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
	    $this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
	    $this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVIÇO');
	    $this->objpdf->text($xcol+145,$xlin+58,'VALOR UNITÁRIO');
	    $this->objpdf->text($xcol+176,$xlin+58,'VALOR TOTAL');
	    $this->objpdf->text($xcol+38,$xlin+63,'Continuação da Página '.($this->objpdf->PageNo()-1));
	    
            $maiscol = 0;
	    
	  }
	    
	}
//	echo $this->numaut."<br>";
//	echo $pagina;exit;
        if ($pagina == 1){
           $this->objpdf->Roundedrect($xcol,$xlin+217,202,55,2,'DF','1234');
	   $this->objpdf->setfillcolor(0,0,0);
	       
           $y = 260;
           $this->objpdf->SetXY(2,$y);
           $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'DIRETOR DE COMPRAS',0,"C",0);
	       
           $this->objpdf->SetXY(72,$y);
           $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'SECRETARIA DE FINANÇAS',0,"C",0);
	       
           $this->objpdf->SetXY(142,$y);
           $this->objpdf->MultiCell(70,4,'VISTO'."\n\n\n".'',0,"C",0);
	       
           $this->objpdf->setfillcolor(0,0,0);
	   $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
	       
//	   $this->objpdf->SetFont('Arial','',4);
//         $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
//	   $this->objpdf->setfont('Arial','',11);
//         $xlin = 169;
        }
    }else if ( $this->modelo == 6 ) {     
      
////////// MODELO 6  -  NOTA DE EMPENHO
	
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->roundedrect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text(128,$xlin-13,'NOTA DE EMPENHO N'.CHR(176).': ');
	$this->objpdf->text(175,$xlin-13,db_formatar($this->numemp,'s','0',6,'e'));
	$this->objpdf->text(134,$xlin-8,'DATA DE EMISSÃO : ');
	$this->objpdf->text(175,$xlin-8,$this->emissao);
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin-8,$this->municpref);
	$this->objpdf->text(40,$xlin-5,$this->telefpref);
	$this->objpdf->text(40,$xlin-2,$this->emailpref);

        /// retangulo dos dados da dotação
//	$this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+100,50,2,'DF','1234');
	$this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+100,50,2,'DF','1234');
//	$this->objpdf->Roundedrect($xcol,$xlin+17,$xcol+100,8,2,'DF','1234');
//	$this->objpdf->Roundedrect($xcol,$xlin+25,$xcol+100,8,2,'DF','1234');
//	$this->objpdf->Roundedrect($xcol,$xlin+25,$xcol+100,8,2,'DF','1234');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+2,$xlin+7,'Órgao');
	$this->objpdf->text($xcol+2,$xlin+11,'Unidade');
	$this->objpdf->text($xcol+2,$xlin+15,'Função');
	
	$this->objpdf->text($xcol+2,$xlin+22,'Proj/Ativ');
	$this->objpdf->text($xcol+2,$xlin+30,'Rubrica');
	$this->objpdf->text($xcol+2,$xlin+42,'Recurso');
	$this->objpdf->text($xcol+2,$xlin+48,'Licitação');
	
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	$this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	$this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);
	
	$this->objpdf->text($xcol+17,$xlin+22,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	
//	$this->objpdf->text($xcol+17,$xlin+30,':  '.$this->analitico.' - '.$this->coddot);
//	$this->objpdf->text($xcol+17,$xlin+34,'   '.$this->descr_analitico);
	$this->objpdf->text($xcol+17,$xlin+30,':  '.db_formatar($this->sintetico,'elemento'));
	$this->objpdf->text($xcol+17,$xlin+34,'   '.$this->descr_sintetico);
	
	$this->objpdf->text($xcol+17,$xlin+41,':  '.$this->recurso.' - '.$this->descr_recurso);
	
	$this->objpdf->text($xcol+17,$xlin+48,':  Convite');
	
	
        //// retangulo dos dados do credor
	$this->objpdf->Roundedrect($xcol+106,$xlin+2,96,17,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+107,$xlin+9,'Numcgm');
	$this->objpdf->text($xcol+137,$xlin+9,'Nome :');
	$this->objpdf->text($xcol+107,$xlin+13,'Endereço');
	$this->objpdf->text($xcol+107,$xlin+17,'Município');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+124,$xlin+9,': '.$this->numcgm);
	$this->objpdf->text($xcol+147,$xlin+9,$this->nome);
	$this->objpdf->text($xcol+124,$xlin+13,': '.$this->ender.'  '.$this->compl);
	$this->objpdf->text($xcol+124,$xlin+17,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);
	
	///// retangulo dos valores
	$this->objpdf->Roundedrect($xcol+106,$xlin+21,96,9,2,'DF','1234');
	$this->objpdf->Roundedrect($xcol+106,$xlin+32,47,9,2,'DF','1234');
	$this->objpdf->roundedrect($xcol+155,$xlin+32,47,9,2,'DF','1234');
	$this->objpdf->roundedrect($xcol+106,$xlin+43,47,9,2,'DF','1234');
	$this->objpdf->roundedrect($xcol+155,$xlin+43,47,9,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+34,'Valor Orçado');
	$this->objpdf->text($xcol+157,$xlin+34,'Saldo Anterior');
	$this->objpdf->text($xcol+108,$xlin+45,'Valor Empenhado');
	$this->objpdf->text($xcol+157,$xlin+45,'Saldo Atual');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+108,$xlin+26.5,'AUTORIZAÇÃO DE EMPENHO N'.chr(176).' 00001');
//	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
	$this->objpdf->text($xcol+130,$xlin+38,db_formatar($this->orcado,'f'));
	$this->objpdf->text($xcol+180,$xlin+38,db_formatar($this->saldo_ant,'f'));
	$this->objpdf->text($xcol+130,$xlin+49,db_formatar($this->empenhado,'f'));
	$this->objpdf->text($xcol+180,$xlin+49,db_formatar($this->saldo_ant - $this->empenhado,'f'));
	
	//// retangulos do titulo do corpo do empenho
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->Roundedrect($xcol,$xlin+54,15,6,2,'DF','12');
	$this->objpdf->roundedrect($xcol+15,$xlin+54,137,6,2,'DF','12');
	$this->objpdf->roundedrect($xcol+152,$xlin+54,25,6,2,'DF','12');
	$this->objpdf->roundedrect($xcol+177,$xlin+54,25,6,2,'DF','12');

        /// retangulo do corpo do empenho 
//	$this->objpdf->Roundedrect($xcol,$xlin+60,15,155,2,'DF','34');
	$this->objpdf->Roundedrect($xcol,$xlin+60,15,123,2,'DF','');
	$this->objpdf->roundedrect($xcol+15,$xlin+60,137,123,2,'DF','');
	$this->objpdf->roundedrect($xcol+152,$xlin+60,25,123,2,'DF','');
	$this->objpdf->roundedrect($xcol+177,$xlin+60,25,123,2,'DF','');
	
	//// título do corpo do empenho
	$this->objpdf->text($xcol+2,$xlin+58,'QUANT');
	$this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+154,$xlin+58,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+181,$xlin+58,'VALOR TOTAL');
        $maiscol = 0;
	
	/// monta os dados para itens do empenho
        $this->objpdf->SetWidths(array(15,137,25,25));
	$this->objpdf->SetAligns(array('C','L','R','R'));
	
	$this->objpdf->setleftmargin(4);
	$this->objpdf->sety($xlin+62);
	$this->objpdf->Setfont('Arial','',7);

	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	  db_fieldsmemory($this->recorddositens,$ii);
	  
	  $this->objpdf->Setfont('Arial','B',7);
          $this->objpdf->cell(15,4,'',0,0,"C",0);
	  $this->objpdf->cell(137,4,db_formatar($this->analitico,'elemento').' - '.$this->descr_analitico,0,1,"L",0);
	  $this->objpdf->Setfont('Arial','',7);
          $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->quantitem),pg_result($this->recorddositens,$ii,$this->descricaoitem),db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/pg_result($this->recorddositens,$ii,$this->quantitem),'f'),db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
 /////// troca de pagina
	  if( ( $this->objpdf->gety() > $this->objpdf->h - 70 && $pagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h - 22 && $pagina != 1 )){
            if ($this->objpdf->PageNo() == 1){
	       $this->objpdf->text(110,$xlin+214,'Continua na Página '.($this->objpdf->PageNo()+1));
               $this->objpdf->Roundedrect($xcol,$xlin+217,202,55,2,'DF','1234');
	       
	       $y = 260;
               $this->objpdf->SetXY(2,$y);
               $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'DIRETOR DE COMPRAS',0,"C",0);
	       
               $this->objpdf->SetXY(72,$y);
               $this->objpdf->MultiCell(70,4,'AUTORIZO'."\n\n\n".'SECRETARIA DE FINANÇAS',0,"C",0);
	       
               $this->objpdf->SetXY(142,$y);
               $this->objpdf->MultiCell(70,4,'VISTO'."\n\n\n".'',0,"C",0);
	       
               $this->objpdf->setfillcolor(0,0,0);
	       $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
               $this->objpdf->SetFont('Arial','',4);
               $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
               $this->objpdf->setfont('Arial','',11);
            }else{
	       $this->objpdf->text(110,$xlin+320,'Continua na Página '.($this->objpdf->PageNo()+1));
	    }
            $this->objpdf->addpage();
            $pagina += 1;	   
	    
	    $this->objpdf->settopmargin(1);
	    $xlin = 20;
	    $xcol = 4;
	
	    $this->objpdf->setfillcolor(245);
	    $this->objpdf->roundedrect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	    $this->objpdf->setfillcolor(255,255,255);
	    $this->objpdf->Setfont('Arial','B',11);
	    $this->objpdf->text(150,$xlin-13,'NOTA DE EMPENHO N'.CHR(176).': ');
	    $this->objpdf->text(159,$xlin-8,db_formatar($this->numaut,'s','0',6,'e'));
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

//  	    $this->objpdf->Roundedrect($xcol,$xlin+54,15,6,2,'DF','12');
	    $this->objpdf->Roundedrect($xcol,$xlin+54,20,6,2,'DF','12');
	    $this->objpdf->roundedrect($xcol+20,$xlin+54,122,6,2,'DF','12');
	    $this->objpdf->roundedrect($xcol+142,$xlin+54,30,6,2,'DF','12');
	    $this->objpdf->roundedrect($xcol+172,$xlin+54,30,6,2,'DF','12');

//  	    $this->objpdf->Roundedrect($xcol,$xlin+60,15,262,2,'DF','34');
	    $this->objpdf->Roundedrect($xcol,$xlin+60,20,262,2,'DF','34');
	    $this->objpdf->roundedrect($xcol+20,$xlin+60,122,262,2,'DF','34');
	    $this->objpdf->roundedrect($xcol+142,$xlin+60,30,262,2,'DF','34');
	    $this->objpdf->roundedrect($xcol+172,$xlin+60,30,262,2,'DF','34');
	    
   	    $this->objpdf->sety($xlin+66);
	    $alt = 4;
	
//	    $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
	    $this->objpdf->text($xcol+0.5,$xlin+58,'QUANTIDADE');
	    $this->objpdf->text($xcol+65,$xlin+58,'MATERIAL OU SERVIÇO');
	    $this->objpdf->text($xcol+145,$xlin+58,'VALOR UNITÁRIO');
	    $this->objpdf->text($xcol+176,$xlin+58,'VALOR TOTAL');
	    $this->objpdf->text($xcol+38,$xlin+63,'Continuação da Página '.($this->objpdf->PageNo()-1));
	    
            $maiscol = 0;
	    
	  }
	    
	}
        if ($pagina == 1){
           $this->objpdf->Roundedrect($xcol,$xlin+183,152,6,2,'DF','34');
           $this->objpdf->Roundedrect($xcol+152,$xlin+183,25,6,2,'DF','34');
           $this->objpdf->Roundedrect($xcol+177,$xlin+183,25,6,2,'DF','34');
	   
//	   $this->objpdf->setfillcolor(0,0,0);
	   $this->objpdf->SetFont('Arial','',7);
	   $this->objpdf->text($xcol+2,$xlin+187,'DESTINO : ',0,1,'L',0);
	   $this->objpdf->text($xcol+30,$xlin+187,$this->destino,0,1,'L',0);
	   
	   $this->objpdf->text($xcol+159,$xlin+187,'T O T A L',0,1,'L',0);

           $this->objpdf->Roundedrect($xcol,$xlin+191,60,6,2,'DF','12');
           $this->objpdf->Roundedrect($xcol+60,$xlin+191,60,6,2,'DF','12');
           $this->objpdf->Roundedrect($xcol+120,$xlin+191,82,6,2,'DF','12');
	   $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
	   $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
	   $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');
	   
	   
           $this->objpdf->Roundedrect($xcol,$xlin+197,60,47,2,'DF','34');
           $this->objpdf->Roundedrect($xcol+60,$xlin+197,60,47,2,'DF','34');
           $this->objpdf->Roundedrect($xcol+120,$xlin+197,82,47,2,'DF','34');
           $this->objpdf->Roundedrect($xcol+120,$xlin+216,32,28,2,'DF','');
	   
	   $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
	   $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
	   $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);
	   
	   $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);
	   
	   $this->objpdf->SetFont('Arial','',6);
	   $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
	   $this->objpdf->text($xcol+26,$xlin+213,'VISTO');
	   $this->objpdf->text($xcol+19,$xlin+227,'TÉCNICO CONTÁBIL');
	   $this->objpdf->text($xcol+13,$xlin+240,'SECRETÁRIO(A) DA FAZENDA');
	   
	   $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
	   $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');
	  
	   $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
	   $this->objpdf->text($xcol+170,$xlin+207,'DATA');
	   $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
	   $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
	   $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
	   $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');
	  
           $this->objpdf->Roundedrect($xcol,$xlin+246,202,26,2,'DF','1234');
	   
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
	   $this->objpdf->setfont('Arial','',11);
           $xlin = 169;
        }
    }else{
	echo "<script>alert('Modelo de carne($this->modelo) não definido')</script>";
	      exit;
    }
  }
}
?>
