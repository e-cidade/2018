<?

//include("cabec_rodape.php");
include("assinatura.php");

// MODELO 1  - CARNES DE PARCELAMENTO
// MODELO 2  - RECIBO DE PAGAMENTO ( 2 VIAS )
// MODELO 9  - ALVARÁ 
// MODELO 4  - BOLETO
// MODELO 5  - AUTORIZAÇÃO DE EMPENHO
// MODELO 6  - NOTA DE EMPENHO
// MODELO 7  - ORDEM DE PAGAMENTO
// MODELO 8  - FICHA DE TRANSFERENCIA DE BENS
// MODELO 10 - ORDEM DE COMPRA
// MODELO 11 - SOLICITAÇÃO DE COMPRA
// MODELO 12 - ANULAÇÃO DE EMPENHO
// MODELO 13 - SOLICITAÇÃO DE ORÇAMENTO
// MODELO 14 - AIDOF
// MODELO 15 - ESTORNO DE PAGAMENTO
// MODELO 22 - RECIBO DE PAGAMENTO ( 1 VIAS )

class db_impcarne extends cl_assinatura {
//class db_impcarne {

/////   VARIÁVEIS PARA EMISSAO DE CARNES DE PARCELAMENTO - MODELO 1

  var $mod_rodape= 1;
  var $modelo    = 1;

  var $qtdcarne  = null;
  var $tipodebito= 'TIPO DE DÉBITO';
  //var $tipoinscr = null;
  var $tipoinscr1= null;
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
  var $cgcpref   = null;
  var $tipocompl = null;
  var $tipolograd= null;
  var $tipobairro= null;
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
  var $nrinscr1  = null;
  var $ip        = null;
  var $nomepri   = '';
  var $nrpri     = '';
  var $complpri  = '';
  var $bairropri = null;
  var $datacalc  = null;
  var $taxabanc  = 0;
  var $rowspagto = 0;
  var $receita   = null;
  var $receitared= null;
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
  var $outrasativs = null;
  var $q02_memo    = null;
  var $numero      = null;
  var $q02_obs     = null;
  var $q03_atmemo  = null; //obs das atividades
  var $processo    = null;
  var $datainc     = null;
  var $datafim     = null;
  var $cnpjcpf     = null;

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

//// vairaveis para o orcamento
  var $orccodigo        = '';
  var $orcdtlim         = '';
  var $orchrlim         = '';
  var $faxforne         = '';



//// variaveis para a solicitação de compras
  var $secfaz           = null;  //Nome do secretário da fazenda
  var $nompre           = null;  //Nome do prefeiro
  
  // solicita
  var $Snumero          = null;  //número da solicitação
  var $Snumero_ant      = null;  //número da solicitação
  var $Sdata            = null;  //data da solicitação
  var $Svalor           = null;  //valor aproximado da solicitação
  var $Sorgao           = null;  //orgão
  var $Sunidade         = null;  //unidade
  var $sabrevunidade    = null;  //unidade abreviada
  var $Sresumo          = '';    //resumo da solicitação
  var $Stipcom          = '';    //tipo de compra da solicitação
  var $Sdepart          = '';    //departamento da solicitação
  var $Srespdepart      = '';    //responsável pelo departamento
  var $Susuarioger      = '';    //Usuário que gerou a solicitação
  
  var $Scoddepto        = '';    //responsável pelo departamento
  var $Sdescrdepto      = '';    //responsável pelo departamento
  var $Snumdepart       = '';    //responsável pelo departamento
  var $linhasdosdepart  = '';    //responsável pelo departamento
  var $resultdosdepart  = '';    //responsável pelo departamento
  
  // solicitem
  var $scodpcmater      = null;  //codigo do pcmater (quando for informado)
  var $scodunid         = null;  //codigo da unidade do item
  var $squantunid       = null;  //quantidade de cada unidade (caixa com 10 unidades)
  var $sprazo           = '';    //prazo de entrega do item
  var $spgto            = '';    //condições de pagamento do item
  var $sresum           = '';    //resumo do item
  var $sjust            = '';    //justificativa para a compra do item
  var $sunidade         = '';    //unidade (caixa,unitário, etc...)
  var $sservico         = '';    //se é serviço ou material
  var $svalortot        = '';    //valor total (quantidade * valor)
  var $susaquant        = '';    //se usa a quantidade ex. caixa (usa quant),unitário(não usa)
  var $selemento        = '';    //elemento do item da solicitação
  var $sdelemento       = '';    //descriçaõ do elemento do item da solicitação

  // pcdotac
  var $dcodigo          = null;  //código da dotação
  var $dcoddot          = null;  //código da dotação
  var $danousu          = null;  //ano da dotação
  var $dquant           = null;  //quantidade do item na dotação
  var $dvalor           = null;  //valor da dotação  
  var $delemento        = '';    //elemento da dotação
  var $dreserva         = '';    //se o valor da dotação foi reservado
  var $resultdasdotac   = null;  // recordset com dados dos fornecedores
  var $linhasdasdotac   = null;  // numero de linhas retornadas no recordsert

  //pcsugforn
  var $cgmforn          = null;       // cgm do fornecedor  
  var $nomeforn         = '';         // nome do fornecedor
  var $enderforn        = '';         // endereco do fornecedor
  var $municforn        = '';         // municipio do fornecedor
  var $foneforn         = '';         // telefone do fornecedor
  var $numforn          = '';         // numforn
  var $resultdosfornec  = null;       // recordset com dados dos fornecedores
  var $linhasdosfornec  = null;       // numero de linhas retornadas no recordsert

  //labels dos itens do processo do orçamento do processo de compras e orçamento de solicitação
  var $labtitulo        = '';         // se é orçamento de solicitação ou PC
  var $labdados         = '';         // se é orçamento de solicitação ou PC
  var $labsolproc       = '';         // código do orçamento ou solicitação
  var $labtipo          = '';         // se for solicitação, label do tipo

//// variaveis para a autorização de empenho E ORDEM DE COMPRA
  var $assinatura1       = 'VISTO';
  
  var $assinatura2       = 'TÉCNICO CONTÁBIL'; 
  var $assinatura3       = 'SECRETÁRIO(A) DA FAZENDA';
  var $assinatura4       = 'SECRETÁRIO DA FAZENDA';
  var $assinaturaprefeito= 'PREFEITO MUNICIPAL';
    
  var $usa_sub		= false;	// a prefeitura utiliza o orcamento no subelemento
  var $telefone		= null;		// telefone
  var $nvias		= 1;		// ano
  var $ano		= null;		// ano
  var $numaut 		= null;  	// numero do empenho
  var $numsol 		= null;  	// numero do empenho
  var $numemp 		= null;  	// numero do empenho
  var $codemp 		= null;  	// numero do empenho do ano
  var $emissao 		= null;  	// data da emissao
  var $orgao 		= null;  	// data da emissao
  var $descr_orgao	= null;  	// data da emissao
  var $unidade 		= null;  	// data da emissao
  var $descr_unidade	= null;  	// data da emissao
  var $funcao 		= null;  	// data da emissao
  var $descr_funcao	= null;  	// data da emissao
  var $projativ		= null;  	// data da emissao
  var $descr_projativ	= null;  	// data da emissao
  var $sintetico	= null;  	// data da emissao
  var $descr_sintetico	= null;  	// data da emissao
  var $recurso   	= null;  	// data da emissao
  var $descr_recurso    = null;  	// data da emissao
  var $orcado		= null;  	// data da emissao
  var $saldo_ant	= null;  	// data da emissao
  var $empenhado	= null;  	// data da emissao
  var $numcgm 		= null;		// cgm do fornecedor
  var $banco  		= null;		// banco
  var $agencia		= null;		// agencia
  var $conta  		= null;		// conta
  var $dotacao 		= null;		// dotacao orcamentaria (orgao,unidade,funcao,subfuncao,programa,projativ,elemento,recurso)
  var $descrdotacao 	= null;		// descricao da dotacao
  var $coddot		= null;		// codigo reduzido da despesa
  var $destino		= null;		// destino do material ou serviço
  var $resumo		= null;		// destino do material ou serviço
  var $licitacao  	= null;		// tipo de licitação
  var $num_licitacao  	= null;		// numero da licitação
  var $descr_licitacao 	= null;		// descrição do tipo de licitação
  var $descr_tipocompra	= null;		// descrição do tipo de compra
  var $prazo_ent  	= null;		// prazo de entrega
//  var $obs		= null;		// observações
  var $cond_pag		= null;		// condições de pagamento
  var $out_cond		= null;		// outras condições de pagamento
  var $contato		= null;		// contato 
  var $telef_cont 	= null;		// telefone do contato
  var $recorddositens 	= null;		// record set dos itens
  var $linhasdositens 	= null;		// numero de itens da autorizacao
  var $item	    	= null;		// codigo do item
  var $quantitem    	= null;		// quantidade do item
  var $valoritem    	= null;		// valor unitário do item
  var $empempenho       = null;         // cod empenho para emissão de ordem de compra
  var $dataordem        = null;         // data da geração da ordem de compra
  var $observacaoitem   = null;
  var $descricaoitem    = null;
  var $ordpag		= null;		// numero da ordem de pagamento
  var $elemento		= null;		// elemento da despesa
  var $descr_elemento	= null;		// descrição do elemento da despesa
  var $elementoitem	= null;		// elemento do item da ordem de pagamento
  var $descr_elementoitem= null;	// descrição do elemento do item da ordem de pagamento
  var $outrasordens     = null;		// saldo das outras ordens de pagamento do empenho
  var $vlrrec           = null;		// valor das receitas de retençoes
  var $cnpj             = null;         // cpf ou cnpj do credor
  var $anulado		= null;         // valor anulado
  var $vlr_anul         = null;         // valor anulado
  var $data_est         = null;         // data estorno
  var $descr_anu        = null;         // descrição da anulação
	
	
/// variaveis para a nota de empenho
  function db_impcarne($objpdf){
    $this->objpdf = $objpdf; 
  }
  
  function muda_pag($pagina,$xlin,$xcol,$fornec="false",&$contapagina){
    $x = false;
    if(($this->objpdf->gety() > $this->objpdf->h - 58 && $contapagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h-30 && $contapagina != 1)){
      if($contapagina == 1){
  	  $this->objpdf->rect($xcol,    $xlin+224.7,142,10.8,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+224.7,30, 10.8,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+224.7,30, 10.8,2,'DF','34');
	  $this->objpdf->text($xcol+120,$xlin+230.7,'T O T A L');
	  $this->objpdf->Setfont('Arial','',9);
	  if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
	    $this->objpdf->rect($xcol,$xlin+237,66,35,2,'DF','1234');
	    $this->objpdf->rect($xcol+68,$xlin+237,66,35,2,'DF','1234');
	    $this->objpdf->rect($xcol+136,$xlin+237,66,35,2,'DF','1234');

            $this->objpdf->setfillcolor(0,0,0);
	    $this->objpdf->text($xcol+5,$xlin+244,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	    $this->objpdf->text($xcol+20,$xlin+256,"AUTORIZO",0,4);
	    $this->objpdf->text($xcol+5,$xlin+268,substr($this->Sorgao,0,35));

            $this->objpdf->setfillcolor(0,0,0);
	    $this->objpdf->text($xcol+93,$xlin+256,"AUTORIZO",0,4);
	    if(strtoupper(trim($this->municpref)) != 'GUAIBA'){
	      $this->objpdf->text($xcol+83,$xlin+268,'DIV. DE ABASTECIMENTO',0,40);
	    }

            $this->objpdf->setfillcolor(0,0,0);
	    $this->objpdf->text($xcol+150,$xlin+256,"ORDENADOR DA DESPESA",0,4);
	  }else{
	    $this->objpdf->rect($xcol    ,$xlin+227,66,45,2,'DF','1234');
	    $this->objpdf->rect($xcol+ 68,$xlin+227,66,45,2,'DF','1234');
	    $this->objpdf->rect($xcol+136,$xlin+227,66,45,2,'DF','1234');

	    $this->objpdf->SetXY($xcol+08,$xlin+238);
	    $this->objpdf->multicell(66,4,"SOLICITANTE",0,"C");
	    $this->objpdf->SetXY($xcol+08,$xlin+245.5);
	    $this->objpdf->multicell(66,0.5,"_________________________________",0,"C");
	    $this->objpdf->SetXY($xcol+08,$xlin+247);
	    $this->objpdf->multicell(66,4,$this->secfaz,0,"C");
	    $this->objpdf->text($xcol+10,$xlin+270,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');


	    $this->objpdf->SetXY($xcol+68,$xlin+245.5);
	    $this->objpdf->multicell(66,0.5,"_________________________________",0,"C");
	    $this->objpdf->SetXY($xcol+68,$xlin+247);
	    $this->objpdf->multicell(66,4,"CONTABILIDADE",0,"C");
	    $this->objpdf->SetXY($xcol+68,$xlin+251);
	    $posicao = strpos($this->secfaz,"\n");
            $secretaria = $this->secfaz;
	    if($posicao!="" && $posicao!=0){
	      $secretaria = substr($this->secfaz,0,$posicao);
	    }
	    $this->objpdf->multicell(66,4,$secretaria,0,"C");
	    $this->objpdf->text($xcol+82,$xlin+260,"SECRETARIA DA FAZENDA",0,4);
	    $this->objpdf->text($xcol+92,$xlin+264,"CONFERIDO",0,4);
	    $this->objpdf->text($xcol+81.5,$xlin+270,"________/________/________",0,4);

	    $this->objpdf->SetXY($xcol+136,$xlin+240);
	    $this->objpdf->multicell(66,4,$this->nompre,0,"C");
	    $this->objpdf->SetXY($xcol+136,$xlin+252);
	    $this->objpdf->multicell(66,4,"AUTORIZO",0,"C");
	    $this->objpdf->text($xcol+150.5,$xlin+270,"________/________/________",0,4);
	    
	    /*
	    $this->objpdf->text($xcol+20,$xlin+241,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	    $this->objpdf->rect($xcol,$xlin+237,100,35,2,'DF','1234');
	    $this->objpdf->rect($xcol+102,$xlin+237,100,35,2,'DF','1234');
	    
	    $this->objpdf->text($xcol+40,$xlin+256,"AUTORIZO",0,4);
	    $this->objpdf->text($xcol+20,$xlin+264,substr($this->Srespdepart,0,35));
	    $this->objpdf->text($xcol+20,$xlin+268,substr($this->Sdepart,0,35));	    
	    $this->objpdf->text($xcol+145,$xlin+256,"VISTO",0,4);
	    */
	  }

	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(111.2,$xlin+234,'Continua na Página '.($contapagina+1));
	$this->objpdf->rect($xcol,$xlin+237,66,35,2,'DF','1234');
	$this->objpdf->rect($xcol+68,$xlin+237,66,35,2,'DF','1234');
	$this->objpdf->rect($xcol+136,$xlin+237,66,35,2,'DF','1234');
	$this->objpdf->setfillcolor(0,0,0);

	$this->objpdf->SetFont('Arial','',4);
	$this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
	$this->objpdf->setfont('Arial','',11);
      }else{
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(112.5,$xlin+271,'Continua na Página '.($contapagina+1));
      }
      $contapagina+=1;
      $this->objpdf->addpage();
      $pagina += 1;	   
      $muda_pag = true;
      
      $this->objpdf->settopmargin(1);
      $xlin = 20;
      $xcol = 4;
  
      // Imprime cabeçalho com dados sobre a prefeitura se mudar de página
      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(130,$xlin-13,'SOLICITAÇÃO DE COMPRA N'.CHR(176));
      $this->objpdf->text(185,$xlin-13,db_formatar($this->Snumero,'s','0',6,'e'));
      $this->objpdf->Setfont('Arial','B',7);
      $this->objpdf->text(130,$xlin-9,'ORGAO');
      $this->objpdf->text(142,$xlin-9,': '.substr($this->Sorgao,0,35));
      $this->objpdf->text(130,$xlin-5,'UNIDADE');
      $this->objpdf->text(142,$xlin-5,': '.substr($this->Sunidade,0,35));
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',9);
      $this->objpdf->text(40,$xlin-11,$this->enderpref);
      $this->objpdf->text(40,$xlin-8,$this->municpref);
      $this->objpdf->text(40,$xlin-5,$this->telefpref);
      $this->objpdf->text(40,$xlin-2,$this->emailpref);
      $this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));
//      $this->objpdf->text(40,$xlin+2,'Continuação da Página '.($contapagina-1));
      $this->objpdf->text(130,$xlin+2,'Página '.$contapagina);
      
      $xlin = 0;      
      if((isset($fornec) && $fornec=="false") || !isset($fornec)){
	$this->objpdf->Setfont('Arial','B',8);

        // Caixas dos label's
	$this->objpdf->rect($xcol    ,$xlin+24,10,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 10,$xlin+24,12,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 22,$xlin+24,22,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 44,$xlin+24,98,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+24,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+24,30,6,2,'DF','12');

        // Caixa dos itens
	$this->objpdf->rect($xcol,    $xlin+30,10,262,2,'DF','34');
        // Caixa da quantidade
	$this->objpdf->rect($xcol+ 10,$xlin+30,12,262,2,'DF','34');
	
	$this->objpdf->rect($xcol+ 22,$xlin+30,22,262,2,'DF','34');
        // Caixa dos materiais ou serviços
	$this->objpdf->rect($xcol+ 44,$xlin+30,98,262,2,'DF','34');
        // Caixa dos valores unitário3
	$this->objpdf->rect($xcol+142,$xlin+30,30,262,2,'DF','');
        // Caixa dos valores totais dos iten
	$this->objpdf->rect($xcol+172,$xlin+30,30,262,2,'DF','34');

	$this->objpdf->sety($xlin+66);
	$alt = 4;

        // Label das colunas
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+   2,$xlin+28,'ITEM');
	$this->objpdf->text($xcol+  11,$xlin+28,'QUANT');
	$this->objpdf->text($xcol+  30,$xlin+28,'REF');
	$this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');

      }else if(isset($fornec) && $fornec=="true"){
      }
      $maiscol = 0;
      $xlin = 20;
      // Seta altura nova para impressão dos dados
      $this->objpdf->sety($xlin+11);
      $this->objpdf->setleftmargin(3);
      $x = true;
    }
    return $x;
  }
 
  function imprime() {
     if($this->modelo == 1){
       // MODELO 1  - CARNES DE PARCELAMENTO
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
	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',12);
	$coluna = 44;
	$linha = 35;
	$this->objpdf->SetLineWidth(1);
	$this->objpdf->RoundedRect(37,0.2,137,195,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
	$this->objpdf->roundedrect(39,2,133,191,2,'1234');
	$this->objpdf->SetLineWidth(0.2);
	$this->objpdf->Image('imagens/files/Brasao.png',43,5,20);
	$this->objpdf->Image('imagens/files/Brasao.jpg',60,30,100);
	
//	$this->objpdf->roundedrect(42,$linha+30,127,35,2,'1234');
//	$this->objpdf->roundedrect(42,$linha+72,127,15,2,'1234'); // obs da atividade principal
	
//  	$this->objpdf->roundedrect(42,$linha+88,127,5,2,'1234'); // descricao da atividade secundaria
//	$this->objpdf->roundedrect(42,$linha+94,127,15,2,'1234'); // obs da atividade secundaria
	

//	$this->objpdf->setdrawcolor(235);

	$this->objpdf->setxy(65,5);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->Multicell(0,8,$this->prefeitura,"C"); // prefeitura

	$this->objpdf->setxy(65,10);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->setleftmargin(50);
	$this->objpdf->setrightmargin(50);
	$this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara

	$this->objpdf->settextcolor(150);
	$this->objpdf->setxy(85,25);
	$this->objpdf->setfont('Arial','B',60);
	$this->objpdf->Multicell(0,8,date('Y'),"C"); // exercicio
	$this->objpdf->settextcolor(0,0,0);

	$this->objpdf->setxy(84,24);
	$this->objpdf->setfont('Arial','B',60);
	$this->objpdf->Multicell(0,8,date('Y'),"C"); // exercicio

        $this->objpdf->Ln(6);
//	$this->objpdf->sety(38);
	$this->objpdf->SetFont('Arial','',11);
	$this->objpdf->Multicell(0,6,$this->texto); // texto

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+35,'CCM:'); // atividade / inscricao
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+35,$this->ativ.' / '.$this->nrinscr); // atividade / inscricao
	
	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+39,"NOME/RAZAO SOCIAL: "); // nome
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+39,$this->nome); // nome

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+43,"ENDEREÇO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+43,$this->ender); // endereco

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+47,"NÚMERO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+47,($this->numero == ""?"":$this->numero));

        if ($this->compl != "") {
   	  $this->objpdf->SetFont('Arial','B',9);
	  $this->objpdf->Text($coluna + 60 ,$linha+47,"COMPLEMENTO: "); // endereco
	  $this->objpdf->SetFont('Arial','',9);
	  $this->objpdf->Text($coluna + 90,$linha+47,($this->compl == ""?"":$this->compl));
        }

        $this->objpdf->setx(40);
	if($this->q02_memo!=''){
	  $this->objpdf->SetFont('Arial','B',9);
	  $this->objpdf->Text($coluna,$linha+51,"OBSERVAÇÃO: "); // observação
	  $this->objpdf->SetFont('Arial','',9);
	  $this->objpdf->sety($linha+52);
	  $this->objpdf->Multicell(0,3,$this->q02_memo); // texto
	  $this->objpdf->SetFont('Arial','B',10);
  	  $this->objpdf->roundedrect(42,$linha+30,127,35,2,'1234');
	  $linha = 102;
	} else {
  	  $this->objpdf->roundedrect(42,$linha+30,127,20,2,'1234');
	  $linha = 87;
	}

        $this->objpdf->sety($linha);
         
	  $this->objpdf->roundedrect(42,$linha-1,127,5,2,'1234');
	  $this->objpdf->SetFont('Arial','B',8);
  	  $this->objpdf->Ln(0.5);
	  $this->objpdf->setx(45);
	  $this->objpdf->Multicell(0,3,"ATIVIDADE PRINCIPAL: " . $this->descrativ) ; // descrição da atividade principal
  	  $linha += 6;
	     $obs='';
	     if(isset($this->q03_atmemo[$this->ativ])){
	       if ($this->q03_atmemo[$this->ativ] != '') {;
  	         $this->objpdf->roundedrect(42,$linha-1,127,15,2,'1234'); // obs da atividade principal
		 $obs = $this->q03_atmemo[$this->ativ];
		 $this->objpdf->Ln(3);
		 $this->objpdf->SetFont('Arial','',7);
		 $this->objpdf->Multicell(0,3,$this->q03_atmemo[$this->ativ]); // texto
		 $linha += 16;
               }
	     }

        $this->objpdf->sety($linha);
	  
        $num_outras=count($this->outrasativs);
	$x=105;
        if ($num_outras >0 ) {

           $x=$x+4;
	   reset($this->outrasativs); 
	   for($i=0; $i<$num_outras; $i++){
             $yyy = $this->objpdf->gety();
	     $chave=key($this->outrasativs);
	     $obs='';
	     if(isset($this->q03_atmemo[$chave])){
	       $obs = $this->q03_atmemo[$chave];
	     }

	     $this->objpdf->SetFont('Arial','B',8);

//             $this->objpdf->roundedrect(42,$yyy-1,127,5,2,'1234'); // descricao da atividade secundaria
  	     $this->objpdf->Ln(0.5);
	     $this->objpdf->setx(45);
 	     $this->objpdf->Multicell(0,3,"ATIVIDADE SECUNDÁRIA: " . $this->outrasativs[$chave]); // texto
	     $linha += 6;

	     if($obs!=""){
	       $this->objpdf->roundedrect(42,$linha-1,127,15,2,'1234'); // obs da atividade secundaria
               $this->objpdf->Ln(3);
  	       $this->objpdf->SetFont('Arial','',7);
 	       $this->objpdf->Multicell(0,3,$obs); // texto
	       $linha += 16;
	     }

	     $x=$x+4;
	     next($this->outrasativs);
//             $this->objpdf->ln(2.5);
	     $this->objpdf->sety($linha);
	   }  
 	}
        $x=64;
//        if($this->q02_obs!=''){
//	  $this->objpdf->Text($coluna,$linha+$x,"OBSERVAÇÃO: "); // descrição da atividade principal
//	  $this->objpdf->Text($coluna + 45,$linha+$x,$this->q02_obs); // descrição da atividade principal
//	  $x=$x+4;
//	}
        
//        $linha = $this->objpdf->gety();
	$this->objpdf->SetFont('Arial','B',12);
	$this->objpdf->Text($coluna+55,$linha + 5,"Sapiranga, ".date('d')." de ".db_mes( date('m') )." de ".date('Y') . "."); // data

	$this->objpdf->sety(125);
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Multicell(0,6,$this->obs); // observação
	$this->objpdf->setfont('arial','',6);
        $this->objpdf->SetXY($coluna-18,165);
        $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".'SECRETÁRIO DA IND. COM. E TURISMO',0,"C",0);
        $this->objpdf->SetXY($coluna+50,165);
        $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);
					
//        $this->objpdf->SetXY($coluna-35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".'SECRETÁRIO DA IND. COM. E TURISMO',0,"C",0);
//        $this->objpdf->SetXY($coluna+35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);


	$this->objpdf->sety(180);
        $this->objpdf->setfont('arial','B',12);
        $this->objpdf->multicell(0,8,'FIXAR EM LUGAR VISÍVEL',1,"C");
	$this->objpdf->SetFont('Arial','B',10);
	
	
    }else if ( $this->modelo == 4 ) {
       
        // BOLETO BANCÁRIO
        
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
      
        //// RECIBO
	
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

		
                $this->objpdf->text($xcol+17,$xlin+19, date("d-m-Y",db_getsession("DB_datausu")));



		$this->objpdf->text($xcol+40,$xlin+19,'Hora: '.date("H:i:s"));




		
		$this->objpdf->text($xcol+75,$xlin+19,'IP :');
		$this->objpdf->text($xcol+82,$xlin+19,$this->ip);
		$this->objpdf->Setfont('Arial','',6);
	
		$this->objpdf->Roundedrect($xcol+126,$xlin+2,76,20,2,'DF','1234');
		$this->objpdf->text($xcol+128,$xlin+7,$this->tipoinscr1);
		$this->objpdf->text($xcol+145,$xlin+7,$this->nrinscr1);
		$this->objpdf->text($xcol+128,$xlin+11,$this->tipolograd);
		$this->objpdf->text($xcol+145,$xlin+11,$this->nomepri);
		$this->objpdf->text($xcol+128,$xlin+15,$this->tipocompl);
		$this->objpdf->text($xcol+145,$xlin+15,$this->nrpri."      ".$this->complpri);
		$this->objpdf->text($xcol+128,$xlin+19,$this->tipobairro);
		$this->objpdf->text($xcol+145,$xlin+19,$this->bairropri);

//		$this->objpdf->setfillcolor(245);
		$this->objpdf->Roundedrect($xcol,$xlin+24,202,45,2,'DF','1234');
	   	$this->objpdf->sety($xlin+24);
                $maiscol = 0;
                $yy = $this->objpdf->gety();
		for($ii = 0;$ii < $this->linhasdadospagto ;$ii++) {
                   if ($ii == 14 ){
                      $maiscol = 100;
                      $this->objpdf->sety($yy);
                   }
		   if($ii==0 || $ii == 14){
		     
	   	     $this->objpdf->setx($xcol+3+$maiscol);
	   	     $this->objpdf->cell(5,3,"Rec",0,0,"L",0);
	   	     $this->objpdf->cell(7,3,"Reduz",0,0,"L",0);
     		     $this->objpdf->cell(63,3,"Descrição",0,0,"L",0);
 		     $this->objpdf->cell(15,3,"Valor",0,1,"R",0);

		   }
	   	   $this->objpdf->setx($xcol+3+$maiscol);
	   	   $this->objpdf->cell(5,3,trim(pg_result($this->recorddadospagto,$ii,$this->receita)),0,0,"R",0);
	   	   $this->objpdf->cell(7,3,"(".trim(pg_result($this->recorddadospagto,$ii,$this->receitared)).")",0,0,"R",0);
           	   if ( trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita) ) == ''){
     		      $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->dreceita)),0,0,"L",0);
           	   }else{ 
	  	      $this->objpdf->cell(63,3,trim(pg_result($this->recorddadospagto,$ii,$this->ddreceita)),0,0,"L",0);
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
//	        $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
		$this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto . ' - ' . ($i == 1?'2ª VIA':'1ª VIA'),'U'); // texto no canhoto do carne
		$this->objpdf->setfont('Arial','',11);
		$this->objpdf->text(10,$xlin+108,$this->linhadigitavel);
		$this->objpdf->int25(10,$xlin+110,$this->codigobarras,15,0.341);
	        $xlin = 169;

       }

    }else if ( $this->modelo == 5 ) {     
      
////////// MODELO 5  -  AUTORIZACAO DE EMPENHO
	
     if( strtoupper(trim($this->municpref)) == 'GUAIBA') {



	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	$this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	$this->objpdf->text(137.5,$xlin-8,'PROCESSO DE COMPRA N'.CHR(176));
	$this->objpdf->text(185,$xlin-8,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->text(40,$xlin-12,$this->enderpref);
	$this->objpdf->text(40,$xlin- 9,$this->municpref);
	$this->objpdf->text(40,$xlin- 6,$this->telefpref);
	$this->objpdf->text(40,$xlin- 3,$this->emailpref);
	$this->objpdf->text(40,$xlin,db_formatar($this->cgcpref,'cnpj'));

	$this->objpdf->rect($xcol,$xlin+2,$xcol+100,28,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+4,'Dados da Compra');
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

	$this->objpdf->rect($xcol+106,$xlin+2,96,28,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+110,$xlin+4,'Dados da Dotação');
	$this->objpdf->Setfont('Arial','B',8);

	
	$this->objpdf->text($xcol+108,$xlin+ 8,'Dotação');
	$this->objpdf->text($xcol+108,$xlin+11.5,'Órgão');
	$this->objpdf->text($xcol+108,$xlin+15,'Unidade');
	$this->objpdf->text($xcol+108,$xlin+18.5,'Proj/Ativ');
	$this->objpdf->text($xcol+108,$xlin+22,'Elemento');
	$this->objpdf->text($xcol+108,$xlin+25.5,'Recurso');
	$this->objpdf->text($xcol+178,$xlin+25.5,'Reduz');
	$this->objpdf->text($xcol+108,$xlin+29,'Destino');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+122,$xlin+ 8,':  '.$this->dotacao);
	$this->objpdf->text($xcol+122,$xlin+11.5,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	$this->objpdf->text($xcol+122,$xlin+15,':  '.db_formatar($this->orgao,'orgao').db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	$this->objpdf->text($xcol+122,$xlin+18.5,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	$this->objpdf->text($xcol+122,$xlin+22,':  '.$this->descrdotacao);
	$this->objpdf->text($xcol+122,$xlin+25.5,':  '.$this->recurso.' - '.$this->descr_recurso);
	$this->objpdf->text($xcol+188,$xlin+25.5,':  '.$this->coddot.'-'.db_CalculaDV($this->coddot));
	$this->objpdf->text($xcol+122,$xlin+29,':  '.$this->destino);

	$this->objpdf->rect($xcol,$xlin+32,$xcol+198,20,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+34,'Dados do Credor');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
	$this->objpdf->text($xcol+150,$xlin+38,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+  2,$xlin+38,'Nome');
	$this->objpdf->text($xcol+  2,$xlin+42,'Endereço');
	$this->objpdf->text($xcol+102,$xlin+42,'Complemento');
	$this->objpdf->text($xcol+  2,$xlin+46,'Município');
	$this->objpdf->text($xcol+115,$xlin+46,'CEP');
	$this->objpdf->text($xcol+  2,$xlin+50,'Contato');
	$this->objpdf->text($xcol+110,$xlin+50,'Telefone');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+159,$xlin+38,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')));
	$this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
	$this->objpdf->text($xcol+18,$xlin+ 38,':  '.$this->nome);
	$this->objpdf->text($xcol+18,$xlin+ 42,':  '.$this->ender);
	$this->objpdf->text($xcol+122,$xlin+42,':  '.$this->compl);
	$this->objpdf->text($xcol+18,$xlin+ 46,':  '.$this->munic.'-'.$this->uf);
	$this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
	$this->objpdf->text($xcol+18,$xlin+ 50,':  '.$this->contato);
	$this->objpdf->text($xcol+122,$xlin+50,':  '.$this->telefone);
	
	$this->objpdf->Setfont('Arial','B',8);
//	$this->objpdf->Roundedrect($xcol,$xlin+54,202,80,2,'DF','1234');
	$this->objpdf->rect($xcol    ,$xlin+54,15,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 15,$xlin+54,20,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 35,$xlin+54,107,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	$this->objpdf->rect($xcol,    $xlin+60,15,122,2,'DF','34');
	$this->objpdf->rect($xcol+ 15,$xlin+60,20,122,2,'DF','34');
	$this->objpdf->rect($xcol+ 35,$xlin+60,107,122,2,'DF','34');

	$this->objpdf->rect($xcol+142,$xlin+60,30,155,2,'DF','');
	$this->objpdf->rect($xcol+172,$xlin+60,30,155,2,'DF','34');
	
	$this->objpdf->rect($xcol,    $xlin+205,142, 10,2,'DF','34');
	$this->objpdf->rect($xcol+142,$xlin+205,30, 10,2,'DF','34');
	$this->objpdf->rect($xcol+172,$xlin+205,30, 10,2,'DF','34');
	$this->objpdf->text($xcol+120 ,$xlin+211,'T O T A L');

        $this->objpdf->SetXY(172,$xlin+205);
	$this->objpdf->cell(30 ,10,db_formatar($this->valtotal,'f'),0,0,"R");


	$this->objpdf->rect($xcol,$xlin+182,142,23,2,'DF','');

	
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
	$this->objpdf->sety($xlin+61);
        $ele = 0;
	$xtotal = 0;

	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	  db_fieldsmemory($this->recorddositens,$ii);
	  if($this->usa_sub == 'f'){
            $this->objpdf->Setfont('Arial','B',7);
            if($ele != pg_result($this->recorddositens,$ii,$this->analitico))
            {
               $this->objpdf->cell(32,4,'',0,0,"C",0);
               $this->objpdf->cell(137,4,db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),0,1,"L",0);
               $ele = pg_result($this->recorddositens,$ii,$this->analitico);
            }
	  }
          $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem);
	  if(pg_result($this->recorddositens,$ii,$this->Snumero)!=""){
            $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem)."\n".'SOLICITAÇÃO: '.pg_result($this->recorddositens,$ii,$this->Snumero);
	  }
          $this->objpdf->Setfont('Arial','',7);
          $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),
	                           pg_result($this->recorddositens,$ii,$this->quantitem),
				   $descricaoitem."\n".pg_result($this->recorddositens,$ii,$this->observacaoitem),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/pg_result($this->recorddositens,$ii,$this->quantitem),'f'),
				   db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	  $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
	  $this->objpdf->Setfont('Arial','B',8);
 /////// troca de pagina
	  if( ( $this->objpdf->gety() > $this->objpdf->h - 100 && $pagina == 1 ) || 
	      ( $this->objpdf->gety() > $this->objpdf->h - 30 && $pagina != 1 )){
            if ($this->objpdf->PageNo() == 1){
	       $this->objpdf->text(110,$xlin+214,'Continua na Página '.($this->objpdf->PageNo()+1));
               $this->objpdf->rect($xcol,$xlin+217,202,55,2,'DF','1234');
	       
	       $y = 260;

	       //// ASSINATURAS DA AUTORIZACAO
	       $cont =  "AUTORIZO"."\n\n\n"."__________________________________";
	       $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
	       $visto =  "VISTO";
	       
//	       $ass_cont   = $this->assinatura(1006,$cont);
//	       $ass_ord    = $this->assinatura(1002,$ord);
               if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
                  $ass_cont   = $this->assinatura(1006,$cont);
                  $ass_ord    = $this->assinatura(1002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }else{
                  $ass_cont   = $this->assinatura(51006,$cont);
                  $ass_ord    = $this->assinatura(51002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }
																			     
               $this->objpdf->SetXY(2,$y);

               $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);
	       
               $this->objpdf->SetXY(72,$y);
               $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);
	       
               $this->objpdf->SetXY(137,$y);
               $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
	       //////
	       
               $this->objpdf->setfillcolor(0,0,0);
	       $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
               $this->objpdf->SetFont('Arial','',4);
               $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
               $this->objpdf->setfont('Arial','',11);


	       if ($pagina == 1){
 		 $this->objpdf->setxy($xcol+1,$xlin+187);
 		 $this->objpdf->text($xcol+2,$xlin+186,'RESUMO : ',0,1,'L',0);
 		 $this->objpdf->Setfont('Arial','',7);
		 $this->objpdf->multicell(140,3.5,$this->resumo);
		 $this->objpdf->Setfont('Arial','B',8);
	       }
	       
            }else{
	       $this->objpdf->text(110,$xlin+320,'Continua na Página '.($this->objpdf->PageNo()+1));
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
	    $this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	    $this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	    $this->objpdf->text(137.5,$xlin-8,'PROCESSO DE COMPRA N'.CHR(176));
	    $this->objpdf->text(185,$xlin-8,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));
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

  	    $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	    $this->objpdf->rect($xcol+15,$xlin+54,20,6,2,'DF','12');
	    $this->objpdf->rect($xcol+35,$xlin+54,107,6,2,'DF','12');
	    $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	    $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	    $this->objpdf->rect($xcol,$xlin+60,15,262,2,'DF','34');
	    $this->objpdf->rect($xcol+15,$xlin+60,20,262,2,'DF','34');
	    $this->objpdf->rect($xcol+35,$xlin+60,107,262,2,'DF','34');
	    $this->objpdf->rect($xcol+142,$xlin+60,30,262,2,'DF','34');
	    $this->objpdf->rect($xcol+172,$xlin+60,30,262,2,'DF','34');

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

        if ($pagina == 1){
	  $this->objpdf->setxy($xcol+1,$xlin+187);
	  $this->objpdf->text($xcol+2,$xlin+186,'RESUMO : ',0,1,'L',0);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->multicell(140,3.5,$this->resumo);
	  $this->objpdf->Setfont('Arial','B',8);
	}

//	$this->objpdf->SetXY(172,$xlin+205);
//	$this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");   

	//	echo $this->numaut."<br>";
	//	echo $pagina;exit;
	if ($pagina == 1){
		$this->objpdf->rect($xcol,$xlin+217,100,55,2,'DF','1234');
		$this->objpdf->rect($xcol+102,$xlin+217,100,55,2,'DF','1234');
//		$this->objpdf->rect($xcol+136,$xlin+217,66,55,2,'DF','1234');
		$this->objpdf->setfillcolor(0,0,0);

		$y = 260;


	       $this->objpdf->Setfont('Arial','',6);
	       //// ASSINATURAS DA AUTORIZACAO
	       $cont =  "__________________________________";
	       $ord =   "ORDENADOR DA DESPESA";
	       $visto =  "";
	       
               if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
                  $ass_cont   = $this->assinatura(1006,$cont);
                  $ass_ord    = $this->assinatura(51002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }else{
                  $ass_cont   = $this->assinatura(51006,$cont);
                  $ass_ord    = $this->assinatura(51002,$ord);
                  $ass_visto  = $this->assinatura(5000,$visto);
               }
               $this->objpdf->SetXY(20,$y);
               
               $this->objpdf->MultiCell(70,2,"\n\n\n\n\n\n".$ass_cont,0,"C",0);
	       
               $this->objpdf->SetXY(122,$y);
               $this->objpdf->MultiCell(70,2,"\n\n\n\n\n\n".$ass_ord,0,"C",0);
	       
               $this->objpdf->SetXY(137,$y);
//               $this->objpdf->MultiCell(70,2,$ass_visto,0,"C",0);
	       //////
		
	       
	   $this->objpdf->Setfont('Arial','B',8);
           $this->objpdf->setfillcolor(0,0,0);
	   $this->objpdf->text($xcol+10,$xlin+223,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	       
//	   $this->objpdf->SetFont('Arial','',4);
//         $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
//	   $this->objpdf->setfont('Arial','',11);
//         $xlin = 169;
        }











     } else {

	  $this->objpdf->AliasNbPages();
	  $this->objpdf->AddPage();
	  $this->objpdf->settopmargin(1);
	  $pagina = 1;
	  $xlin = 20;
	  $xcol = 4;
	  
	  $this->objpdf->setfillcolor(245);
	  $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	  $this->objpdf->setfillcolor(255,255,255);
	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	  $this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	  $this->objpdf->text(137.5,$xlin-8,'PROCESSO DE COMPRA N'.CHR(176));
	  $this->objpdf->text(185,$xlin-8,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));
	  $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->text(40,$xlin-12,$this->enderpref);
	  $this->objpdf->text(40,$xlin- 9,$this->municpref);
	  $this->objpdf->text(40,$xlin- 6,$this->telefpref);
	  $this->objpdf->text(40,$xlin- 3,$this->emailpref);
	  $this->objpdf->text(40,$xlin,db_formatar($this->cgcpref,'cnpj'));

	  $this->objpdf->rect($xcol,$xlin+2,$xcol+100,28,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',6);
	  $this->objpdf->text($xcol+2,$xlin+4,'Dados da Compra');
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

	  $this->objpdf->rect($xcol+106,$xlin+2,96,28,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',6);
	  $this->objpdf->text($xcol+110,$xlin+4,'Dados da Dotação');
	  $this->objpdf->Setfont('Arial','B',8);

	  
	  $this->objpdf->text($xcol+108,$xlin+ 8,'Dotação');
	  $this->objpdf->text($xcol+108,$xlin+11.5,'Órgão');
	  $this->objpdf->text($xcol+108,$xlin+15,'Unidade');
	  $this->objpdf->text($xcol+108,$xlin+18.5,'Proj/Ativ');
	  $this->objpdf->text($xcol+108,$xlin+22,'Elemento');
	  $this->objpdf->text($xcol+108,$xlin+25.5,'Recurso');
	  $this->objpdf->text($xcol+178,$xlin+25.5,'Reduz');
	  $this->objpdf->text($xcol+108,$xlin+29,'Destino');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+122,$xlin+ 8,':  '.$this->dotacao);
	  $this->objpdf->text($xcol+122,$xlin+11.5,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	  $this->objpdf->text($xcol+122,$xlin+15,':  '.db_formatar($this->orgao,'orgao').db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	  $this->objpdf->text($xcol+122,$xlin+18.5,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	  $this->objpdf->text($xcol+122,$xlin+22,':  '.$this->descrdotacao);
	  $this->objpdf->text($xcol+122,$xlin+25.5,':  '.$this->recurso.' - '.$this->descr_recurso);
	  $this->objpdf->text($xcol+188,$xlin+25.5,':  '.$this->coddot.'-'.db_CalculaDV($this->coddot));
	  $this->objpdf->text($xcol+122,$xlin+29,':  '.$this->destino);

	  $this->objpdf->rect($xcol,$xlin+32,$xcol+198,20,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',6);
	  $this->objpdf->text($xcol+2,$xlin+34,'Dados do Credor');
	  $this->objpdf->Setfont('Arial','B',8);
	  $this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
	  $this->objpdf->text($xcol+150,$xlin+38,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	  $this->objpdf->text($xcol+  2,$xlin+38,'Nome');
	  $this->objpdf->text($xcol+  2,$xlin+42,'Endereço');
	  $this->objpdf->text($xcol+102,$xlin+42,'Complemento');
	  $this->objpdf->text($xcol+  2,$xlin+46,'Município');
	  $this->objpdf->text($xcol+115,$xlin+46,'CEP');
	  $this->objpdf->text($xcol+  2,$xlin+50,'Contato');
	  $this->objpdf->text($xcol+110,$xlin+50,'Telefone');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+159,$xlin+38,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')));
	  $this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
	  $this->objpdf->text($xcol+18,$xlin+ 38,':  '.$this->nome);
	  $this->objpdf->text($xcol+18,$xlin+ 42,':  '.$this->ender);
	  $this->objpdf->text($xcol+122,$xlin+42,':  '.$this->compl);
	  $this->objpdf->text($xcol+18,$xlin+ 46,':  '.$this->munic.'-'.$this->uf);
	  $this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
	  $this->objpdf->text($xcol+18,$xlin+ 50,':  '.$this->contato);
	  $this->objpdf->text($xcol+122,$xlin+50,':  '.$this->telefone);
	  
	  $this->objpdf->Setfont('Arial','B',8);
  //	$this->objpdf->Roundedrect($xcol,$xlin+54,202,80,2,'DF','1234');
	  $this->objpdf->rect($xcol    ,$xlin+54,15,6,2,'DF','12');
	  $this->objpdf->rect($xcol+ 15,$xlin+54,20,6,2,'DF','12');
	  $this->objpdf->rect($xcol+ 35,$xlin+54,107,6,2,'DF','12');
	  $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	  $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	  $this->objpdf->rect($xcol,    $xlin+60,15,122,2,'DF','34');
	  $this->objpdf->rect($xcol+ 15,$xlin+60,20,122,2,'DF','34');
	  $this->objpdf->rect($xcol+ 35,$xlin+60,107,122,2,'DF','34');

	  $this->objpdf->rect($xcol+142,$xlin+60,30,155,2,'DF','');
	  $this->objpdf->rect($xcol+172,$xlin+60,30,155,2,'DF','34');
	  
	  $this->objpdf->rect($xcol,    $xlin+205,142, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+205,30, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+205,30, 10,2,'DF','34');
	  $this->objpdf->text($xcol+120 ,$xlin+211,'T O T A L');


	  $this->objpdf->rect($xcol,$xlin+182,142,23,2,'DF','');

	  
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
	  $this->objpdf->sety($xlin+61);
	  $ele = 0;
	  $xtotal = 0;

	  for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	    db_fieldsmemory($this->recorddositens,$ii);
	    if($this->usa_sub == 'f'){
	      $this->objpdf->Setfont('Arial','B',7);
	      if($ele != pg_result($this->recorddositens,$ii,$this->analitico))
	      {
		 $this->objpdf->cell(32,4,'',0,0,"C",0);
		 $this->objpdf->cell(137,4,db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),0,1,"L",0);
		 $ele = pg_result($this->recorddositens,$ii,$this->analitico);
	      }
	    }
	    $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem);
	    if(pg_result($this->recorddositens,$ii,$this->Snumero) != "") {
              $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem)."\n".'SOLICITAÇÃO: '.pg_result($this->recorddositens,$ii,$this->Snumero);
	    }
	    $this->objpdf->Setfont('Arial','',7);
	    $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),
				     pg_result($this->recorddositens,$ii,$this->quantitem),
				     $descricaoitem."\n".pg_result($this->recorddositens,$ii,$this->observacaoitem),
				     db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/pg_result($this->recorddositens,$ii,$this->quantitem),'f'),
				     db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	    $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
	    $this->objpdf->Setfont('Arial','B',8);
   /////// troca de pagina
	    if( ( $this->objpdf->gety() > $this->objpdf->h - 100 && $pagina == 1 ) || 
	        ( $this->objpdf->gety() > $this->objpdf->h - 30  && $pagina != 1 )){
	      if ($this->objpdf->PageNo() == 1){
		 $this->objpdf->text(110,$xlin+214,'Continua na Página '.($this->objpdf->PageNo()+1));
		 $this->objpdf->rect($xcol,$xlin+217,202,55,2,'DF','1234');

                 $this->objpdf->SetXY(172,$xlin+205);
                 $this->objpdf->cell(30 ,10,db_formatar($this->valtotal,'f'),0,0,"R");
		 
		 $y = 260;

		 //// ASSINATURAS DA AUTORIZACAO
		 $cont =  "AUTORIZO"."\n\n\n"."__________________________________";
		 $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
		 $visto = "VISTO";
		 
  //	       $ass_cont   = $this->assinatura(1006,$cont);
  //	       $ass_ord    = $this->assinatura(1002,$ord);
  
		 if(strtoupper(trim($this->municpref)) == 'SAPIRANGA'){
		    $cont =  "AUTORIZO"."\n\n\n"."__________________________________";
		    $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
		    $visto = "VISTO";
		    
		    $ass_cont   = $this->assinatura(51006,$cont);
		    $ass_ord    = $this->assinatura(51002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $ass_usu    = $this->assinatura_usuario();
		    
		    /// primeiro quadro
		    $this->objpdf->SetXY(2,$y-20);
		    $this->objpdf->MultiCell(70,4,$ass_usu,0,"C",0);
		    
		    $this->objpdf->SetXY(2,$y+5);
		    $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);

		    
		    $this->objpdf->SetXY(72,$y);
		    $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
		    $this->objpdf->setfillcolor(0,0,0);
		    $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');

                 }elseif(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $this->objpdf->SetXY(2,$y);
  		    $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nDIRETOR DE COMPRAS",0,"C",0);
		    $this->objpdf->SetXY(72,$y);
  		    $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nSECRETÁRIO DE FINANÇAS",0,"C",0);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
		    $this->objpdf->setfillcolor(0,0,0);
		    $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		 }else{
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $this->objpdf->SetXY(2,$y);
		    $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);
		    $this->objpdf->SetXY(72,$y);
		    $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
	  	    $this->objpdf->setfillcolor(0,0,0);
		    $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		 } 
		/* 
		 if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		 }else{
		    $ass_cont   = $this->assinatura(51006,$cont);
		    $ass_ord    = $this->assinatura(51002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		 }
																			       
		 $this->objpdf->SetXY(2,$y);

		 if(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nDIRETOR DE COMPRAS",0,"C",0);
		 } else {
		   $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);
		 }
		 
		 
		 $this->objpdf->SetXY(72,$y);
		 if(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nSECRETÁRIO DE FINANÇAS",0,"C",0);
		 } else {
		   $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);
		 }
		 
		 $this->objpdf->SetXY(137,$y);
		 $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
		 /////
		 
		 $this->objpdf->setfillcolor(0,0,0);
		 $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
	         */
		 $this->objpdf->SetFont('Arial','',4);
		 $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
		 $this->objpdf->setfont('Arial','',11);


		 if ($pagina == 1){
		   $this->objpdf->setxy($xcol+1,$xlin+187);
		   $this->objpdf->text($xcol+2,$xlin+186,'RESUMO : ',0,1,'L',0);
		   $this->objpdf->Setfont('Arial','',7);
		   $this->objpdf->multicell(140,3.5,$this->resumo);
		   $this->objpdf->Setfont('Arial','B',8);
		 }
		 
	      }else{
		 $this->objpdf->text(110,$xlin+320,'Continua na Página '.($this->objpdf->PageNo()+1));
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
	      $this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO DE EMPENHO N'.CHR(176));
	      $this->objpdf->text(185,$xlin-13,db_formatar($this->numaut,'s','0',6,'e'));
	      $this->objpdf->text(137.5,$xlin-8,'PROCESSO DE COMPRA N'.CHR(176));
	      $this->objpdf->text(185,$xlin-8,db_formatar(pg_result($this->recorddositens,0,$this->Snumeroproc),'s','0',6,'e'));
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

	      $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	      $this->objpdf->rect($xcol+15,$xlin+54,20,6,2,'DF','12');
	      $this->objpdf->rect($xcol+35,$xlin+54,107,6,2,'DF','12');
	      $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	      $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	      $this->objpdf->rect($xcol,$xlin+60,15,262,2,'DF','34');
	      $this->objpdf->rect($xcol+15,$xlin+60,20,262,2,'DF','34');
	      $this->objpdf->rect($xcol+35,$xlin+60,107,262,2,'DF','34');
	      $this->objpdf->rect($xcol+142,$xlin+60,30,262,2,'DF','34');
	      $this->objpdf->rect($xcol+172,$xlin+60,30,262,2,'DF','34');

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

	  if ($pagina == 1){
	    $this->objpdf->setxy($xcol+1,$xlin+187);
	    $this->objpdf->text($xcol+2,$xlin+186,'RESUMO : ',0,1,'L',0);
	    $this->objpdf->Setfont('Arial','',7);
	    $this->objpdf->multicell(140,3.5,$this->resumo);
	    $this->objpdf->Setfont('Arial','B',8);
	  }

//	  $this->objpdf->SetXY(172,$xlin+205);
//	  $this->objpdf->cell(30 ,10,db_formatar($xtotal,'f'),0,0,"R");   

	  //	echo $this->numaut."<br>";
	  //	echo $pagina;exit;
	  if ($pagina == 1){
                  $this->objpdf->SetXY(172,$xlin+205);
	          $this->objpdf->cell(30 ,10,db_formatar($this->valtotal,'f'),0,0,"R");
		    
		  $this->objpdf->rect($xcol,$xlin+217,66,55,2,'DF','1234');
		  $this->objpdf->rect($xcol+68,$xlin+217,66,55,2,'DF','1234');
		  $this->objpdf->rect($xcol+136,$xlin+217,66,55,2,'DF','1234');
		  $this->objpdf->setfillcolor(0,0,0);

		  $y = 260;


		 $this->objpdf->Setfont('Arial','',6);
		 //// ASSINATURAS DA AUTORIZACAO
//		 $cont =  "__________________________________";
//		 $ord =   "__________________________________";
//		 $visto =  "VISTO";


		 //// ASSINATURAS DA AUTORIZACAO
		 $cont =  "AUTORIZO"."\n\n\n"."__________________________________";
		 $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
		 $visto = "VISTO";
		 
  //	       $ass_cont   = $this->assinatura(1006,$cont);
  //	       $ass_ord    = $this->assinatura(1002,$ord);
  
		 if(strtoupper(trim($this->municpref)) == 'SAPIRANGA'){
		 
		    $cont =  "__________________________________"."\n"."CONTABILIDADE";
		    $pref =  "__________________________________"."\n"."PREFEITO MUNICIPAL";
		    $ord =   "AUTORIZO"."\n\n\n"."__________________________________";
		    $visto = "VISTO";
		    $ch_compras = "__________________________________"."\n"."CHEFE COMPRAS";

		    
		    $ass_pref   = $this->assinatura(1000,$pref);
		    $ass_secfaz = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $ass_usu    = $this->assinatura_usuario();
		    /// primeiro quadro
		    $this->objpdf->SetXY(2,$y-15);
		    $this->objpdf->MultiCell(70,3,"__________________________________"."\n".$ass_usu,0,"C",0);
		    
		    $this->objpdf->SetXY(2,$y+8);
		    $this->objpdf->MultiCell(70,3,$ch_compras,0,"C",0);
		    
		    $this->objpdf->setfillcolor(0,0,0);
	            $this->objpdf->Setfont('Arial','B',8);
		    $this->objpdf->text($xcol+10,$xlin+270,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		   
		    /// segundo quadro
		    $this->objpdf->Setfont('Arial','',6);
		    
		    $this->objpdf->SetXY(72,$y-15);
		    $this->objpdf->MultiCell(65,3,$cont,0,"C",0);
		    
		    $this->objpdf->SetXY(72,$y);
		    $this->objpdf->MultiCell(65,3,"HÁ RECURSOS FINANCEIROS:",0,"C",0);
		   
		    $this->objpdf->SetXY(72,$y+8);
		    $this->objpdf->MultiCell(65,3,$ass_secfaz,0,"C",0);
		    
		    $this->objpdf->setfillcolor(0,0,0);
//	            $this->objpdf->Setfont('Arial','B',8);
		    $this->objpdf->text($xcol+95,$xlin+263,'CONFERIDO');
		    $this->objpdf->text($xcol+91,$xlin+270,'______/______/______');
		    
		    /// terceiro quadro
		    $this->objpdf->Setfont('Arial','',6);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,3,$ass_pref,0,"C",0);
		    
		    $this->objpdf->text($xcol+165,$xlin+263,'AUTORIZA');
		    $this->objpdf->text($xcol+161,$xlin+270,'______/______/______');

                 }elseif(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $this->objpdf->SetXY(2,$y);
  		    $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nDIRETOR DE COMPRAS",0,"C",0);
		    $this->objpdf->SetXY(72,$y);
  		    $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nSECRETÁRIO DE FINANÇAS",0,"C",0);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
		    $this->objpdf->setfillcolor(0,0,0);
		    $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		 }else{
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		    $this->objpdf->SetXY(2,$y);
		    $this->objpdf->MultiCell(70,4,$ass_cont,0,"C",0);
		    $this->objpdf->SetXY(72,$y);
		    $this->objpdf->MultiCell(70,4,$ass_ord,0,"C",0);
		    $this->objpdf->SetXY(137,$y);
		    $this->objpdf->MultiCell(70,4,$ass_visto,0,"C",0);
	  	    $this->objpdf->setfillcolor(0,0,0);
		    $this->objpdf->text($xcol+10,$xlin+223,$this->municpref.', '.date('d').' DE '.strtoupper(db_mes(date('m'))).' DE '.db_getsession("DB_anousu").'.');
		 } 



/*
		 if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
		    $ass_cont   = $this->assinatura(1006,$cont);
		    $ass_ord    = $this->assinatura(1002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		 }else{
		    $ass_cont   = $this->assinatura(51006,$cont);
		    $ass_ord    = $this->assinatura(51002,$ord);
		    $ass_visto  = $this->assinatura(5000,$visto);
		 }

		 $this->objpdf->SetXY(2,$y);
		 
		 if(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nDIRETOR DE COMPRAS",0,"C",0);
		 } else {
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\n".$ass_cont,0,"C",0);
		 }
		 
		 $this->objpdf->SetXY(72,$y);
		 if(strtoupper(trim($this->municpref)) == 'ALEGRETE'){
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\nSECRETÁRIO DE FINANÇAS",0,"C",0);
		 } else {
  		   $this->objpdf->MultiCell(70,2,"AUTORIZO"."\n\n\n\n\n\n".$ass_ord,0,"C",0);
		 }
		 
		 $this->objpdf->SetXY(137,$y);
		 $this->objpdf->MultiCell(70,2,$ass_visto,0,"C",0);
		 //////
		  
		 
	     $this->objpdf->Setfont('Arial','B',8);
	     $this->objpdf->setfillcolor(0,0,0);
	     $this->objpdf->text($xcol+10,$xlin+223,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
*/


  //	   $this->objpdf->SetFont('Arial','',4);
  //         $this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
  //	   $this->objpdf->setfont('Arial','',11);
  //         $xlin = 169;
        }
	
     }


     
    }else if ( $this->modelo == 6 ) {     
 
 ////////// MODELO 6  -  NOTA DE EMPENHO
     if( strtoupper(trim($this->municpref)) == 'GUAIBA' ) {

       $assinatura2        = 'CONTADORA';
       $assinatura3        = 'JORGE ANTONIO POKORSKI';
       $assinaturaprefeito = 'MANOEL STRINGHINI';

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
	  $this->objpdf->text(128,$xlin-13,'NOTA DE EMPENHO N'.CHR(176).': ');
	  $this->objpdf->text(175,$xlin-13,db_formatar($this->codemp,'s','0',6,'e'));
	  $this->objpdf->text(134,$xlin-8,'DATA DE EMISSÃO : ');
	  $this->objpdf->text(175,$xlin-8,$this->emissao);
	  $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
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
	  $this->objpdf->text($xcol+149,$xlin+7,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')).'   Fone: '.$this->telefone);
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
	  $this->objpdf->text($xcol+108,$xlin+34.0,'Valor Orçado');
	  $this->objpdf->text($xcol+157,$xlin+34.0,'Saldo Anterior');
	  $this->objpdf->text($xcol+108,$xlin+44.5,'Valor Empenhado');
	  $this->objpdf->text($xcol+157,$xlin+44.5,'Saldo Atual');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+108,$xlin+27,'AUTORIZAÇÃO N'.chr(176).' '.db_formatar($this->numaut,'s','0',5,'e'));
	  $this->objpdf->text($xcol+150,$xlin+27,'SEQ. DO EMPENHO N'.chr(176).' '.db_formatar($this->numemp,'s','0',6,'e'));
  //	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
	  $this->objpdf->text($xcol+130,$xlin+38.0,db_formatar($this->orcado,'f'));
	  $this->objpdf->text($xcol+180,$xlin+38.0,db_formatar($this->saldo_ant,'f'));
	  $this->objpdf->text($xcol+130,$xlin+47.5,db_formatar($this->empenhado,'f'));
	  $this->objpdf->text($xcol+180,$xlin+47.5,db_formatar($this->saldo_ant - $this->empenhado,'f'));
	  

	  /// retangulo do corpo do empenho 
	  $this->objpdf->rect($xcol,$xlin+60,15,100,2,'DF','');
	  $this->objpdf->rect($xcol+15,$xlin+60,137,100,2,'DF','');
	  $this->objpdf->rect($xcol+152,$xlin+60,25,123,2,'DF','');
	  $this->objpdf->rect($xcol+177,$xlin+60,25,123,2,'DF','');
	  $this->objpdf->rect($xcol,$xlin+160,152,23,2,'DF',''); // resumo
	  
	  //// retangulos do titulo do corpo do empenho
	  $this->objpdf->Setfont('Arial','B',7);
	  $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	  $this->objpdf->rect($xcol+15,$xlin+54,137,6,2,'DF','12');
	  $this->objpdf->rect($xcol+152,$xlin+54,25,6,2,'DF','12');
	  $this->objpdf->rect($xcol+177,$xlin+54,25,6,2,'DF','12');

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
	  $ele = 0;
	  $xtotal = 0;
	  for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	    db_fieldsmemory($this->recorddositens,$ii);
	    $this->objpdf->Setfont('Arial','B',7);
	    if($ele != pg_result($this->recorddositens,$ii,$this->analitico))
	    {
	      $this->objpdf->cell(15,4,'',0,0,"C",0);
	      $this->objpdf->cell(137,4,db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),0,1,"L",0);
	      $ele = pg_result($this->recorddositens,$ii,$this->analitico);
	    }
	    $this->objpdf->Setfont('Arial','',7);
	    $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->quantitem),pg_result($this->recorddositens,$ii,$this->descricaoitem),db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/pg_result($this->recorddositens,$ii,$this->quantitem),'f'),db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	    $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
   /////// troca de pagina
	    if( ( $this->objpdf->gety() > $this->objpdf->h - 125 && $pagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h - 22 && $pagina != 1 )){

	     $proxima_pagina = $pagina + 1;
	     $this->objpdf->Row(array('',"Continua na página $proxima_pagina",'',''),3,false,4);
	     if ($pagina == 1){
	     $this->objpdf->rect($xcol,$xlin+183,152,6,2,'DF','34');
	     $this->objpdf->rect($xcol+152,$xlin+183,25,6,2,'DF','34');
	     $this->objpdf->rect($xcol+177,$xlin+183,25,6,2,'DF','34');
	     
	     $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
	     $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
	     $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
	     $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','4');
	     
	     
  //	   $this->objpdf->setfillcolor(0,0,0);
	     $this->objpdf->SetFont('Arial','',7);
	     $this->objpdf->text($xcol+2,$xlin+187,'DESTINO : ',0,1,'L',0);
	     $this->objpdf->text($xcol+30,$xlin+187,$this->destino,0,1,'L',0);
	    
	     $this->objpdf->setxy($xcol+1,$xlin+165);
	     $this->objpdf->text($xcol+2,$xlin+164,'RESUMO : ',0,1,'L',0);
	     $this->objpdf->multicell(147,3.5,$this->resumo);
	     
	     $this->objpdf->text($xcol+159,$xlin+187,'T O T A L',0,1,'L',0);
	     $this->objpdf->setxy($xcol+185,$xlin+182);
	     $this->objpdf->cell(30,10,db_formatar($this->empenhado,'f'),0,0,'f');

	     $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
	     $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
	     $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
	     $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
	     $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
	     $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');


	     if ($this->assinatura1 != "") {
  //               $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
	     }

	     if ($assinatura2 != "") {
		 $this->objpdf->line($xcol+5,$xlin+215,$xcol+54,$xlin+225);
	     }

	     if ($assinatura3 != "") {
		 $this->objpdf->line($xcol+5,$xlin+235,$xcol+54,$xlin+238);
	     }

	     $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);
	     $this->objpdf->SetFont('Arial','',6);
  //         $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
	     $this->objpdf->text($xcol+27-(strlen($this->assinatura1)/2),$xlin+218,$this->assinatura1);
	     $this->objpdf->text($xcol+27-(strlen($assinatura2)/2),$xlin+238,$assinatura2);
	     $this->objpdf->text($xcol+27-(strlen($assinatura3)/2),$xlin+241,$assinatura3);
	   
  //           $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
	     $this->objpdf->text($xcol+88-(strlen($assinaturaprefeito)/2),$xlin+228,$assinaturaprefeito);
	     $this->objpdf->text($xcol+88-(strlen("PREFEITO MUNICIPAL")/2),$xlin+231,"PREFEITO MUNICIPAL");
																							      
  //	   $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
  //  $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');
	    
	     $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
	     $this->objpdf->text($xcol+170,$xlin+207,'DATA');
	     $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
	     $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
	     $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
	     $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');
	    
	     $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
	     
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

	       
	      $this->objpdf->addpage();
	      $pagina += 1;	   
	      
	      $this->objpdf->settopmargin(1);
	      $xlin = 20;
	      $xcol = 4;
	  
	      $this->objpdf->setfillcolor(245);
	      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	      $this->objpdf->setfillcolor(255,255,255);
	      $this->objpdf->Setfont('Arial','B',11);

	      $this->objpdf->text(128,$xlin-13,'NOTA DE EMPENHO N'.CHR(176).': ');
	      $this->objpdf->text(175,$xlin-13,db_formatar($this->codemp,'s','0',6,'e'));
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
	      $xlin = -30;
	      $this->objpdf->Setfont('Arial','B',8);

  //  	    $this->objpdf->Roundedrect($xcol,$xlin+54,15,6,2,'DF','12');
	      $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	      $this->objpdf->rect($xcol+15,$xlin+54,127,6,2,'DF','12');
	      $this->objpdf->rect($xcol+142,$xlin+54,35,6,2,'DF','12');
	      $this->objpdf->rect($xcol+177,$xlin+54,25,6,2,'DF','12');

  //  	    $this->objpdf->Roundedrect($xcol,$xlin+60,15,262,2,'DF','34');
	      $this->objpdf->rect($xcol,$xlin+60,15,262,2,'DF','34');
	      $this->objpdf->rect($xcol+15,$xlin+60,127,262,2,'DF','34');
	      $this->objpdf->rect($xcol+142,$xlin+60,35,262,2,'DF','34');
	      $this->objpdf->rect($xcol+177,$xlin+60,25,262,2,'DF','34');
	      
	      $this->objpdf->sety($xlin+66);
	      $alt = 4;
	  
  //	    $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
	      $this->objpdf->text($xcol+0.5,$xlin+58,'QUANT');
	      $this->objpdf->text($xcol+65,$xlin+58,'MATERIAL OU SERVIÇO');
	      $this->objpdf->text($xcol+145,$xlin+58,'VALOR UNITÁRIO');
	      $this->objpdf->text($xcol+179,$xlin+58,'VALOR TOTAL');
	      $this->objpdf->text($xcol+38,$xlin+63,'Continuação da Página '.($this->objpdf->PageNo()-1));
	      
	      $maiscol = 0;
	      
	    }
	      
	  }

	  if ($pagina == 1){
	     $this->objpdf->rect($xcol,$xlin+183,152,6,2,'DF','34');
	     $this->objpdf->rect($xcol+152,$xlin+183,25,6,2,'DF','34');
	     $this->objpdf->rect($xcol+177,$xlin+183,25,6,2,'DF','34');
	     
  //	   $this->objpdf->setfillcolor(0,0,0);
	     $this->objpdf->SetFont('Arial','',7);
	     $this->objpdf->text($xcol+2,$xlin+187,'DESTINO : ',0,1,'L',0);
	     $this->objpdf->text($xcol+30,$xlin+187,$this->destino,0,1,'L',0);
	    
	     $this->objpdf->setxy($xcol+1,$xlin+165);
	     $this->objpdf->text($xcol+2,$xlin+164,'RESUMO : ',0,1,'L',0);
	     $this->objpdf->multicell(147,3.5,$this->resumo);
	     
	     $this->objpdf->text($xcol+159,$xlin+187,'T O T A L',0,1,'L',0);
	     $this->objpdf->setxy($xcol+185,$xlin+182);
	     $this->objpdf->cell(30,10,db_formatar($this->empenhado,'f'),0,0,'f');
  //	   $this->rodape($mod_rodape); 

	     
	     $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
	     $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
	     $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
	     $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','4');
	     
	     $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
	     $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
	     $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
	     $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
	     $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
	     $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');


	     if ($this->assinatura1 != "") {
  //////////               $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
	     }

	     if ($assinatura2 != "") {
		 $this->objpdf->line($xcol+5,$xlin+215,$xcol+54,$xlin+215);
	     }

	     if ($assinatura3 != "") {
		 $this->objpdf->line($xcol+5,$xlin+235,$xcol+54,$xlin+235); //////////
	     }

	     $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);
	     $this->objpdf->SetFont('Arial','',6);
  //         $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
  //////////           $this->objpdf->text($xcol+27-(strlen($this->assinatura1)/2),$xlin+213,$this->assinatura1);
	     $this->objpdf->text($xcol+27-(strlen($assinatura2)/2),$xlin+218,$assinatura2);
	     $this->objpdf->text($xcol+27-(strlen($assinatura3)/2),$xlin+238,$assinatura3); //////////
	     $this->objpdf->text($xcol+27-(strlen($this->assinatura4)/2),$xlin+241,$this->assinatura4); //////////
	   
  ////           $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
	     $this->objpdf->text($xcol+88-(strlen($assinaturaprefeito)/2),$xlin+228,$assinaturaprefeito);
	     $this->objpdf->text($xcol+88-(strlen("PREFEITO MUNICIPAL")/2),$xlin+231,"PREFEITO MUNICIPAL");
																							      
	     $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
	     $this->objpdf->text($xcol+170,$xlin+207,'DATA');
	     $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
	     $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
	     $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
	     $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');

	     $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');

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
	  $this->objpdf->text(128,$xlin-13,'NOTA DE EMPENHO N'.CHR(176).': ');
	  $this->objpdf->text(175,$xlin-13,db_formatar($this->codemp,'s','0',6,'e'));
	  $this->objpdf->text(134,$xlin-8,'DATA DE EMISSÃO : ');
	  $this->objpdf->text(175,$xlin-8,$this->emissao);

          $this->objpdf->text(159,$xlin-3,'TIPO : ');
	  $this->objpdf->text(175,$xlin-3,$this->emptipo);
	
	  
	  $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->text(40,$xlin-11,$this->enderpref);
	  $this->objpdf->text(40,$xlin-8,$this->municpref);
	  $this->objpdf->text(40,$xlin-5,$this->telefpref);
	  $this->objpdf->text(40,$xlin-2,$this->emailpref);
	  $this->objpdf->text(40,$xlin+1,db_formatar($this->cgcpref,'cnpj'));

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
	  
	  $this->objpdf->text($xcol+17,$xlin+48,':  '.($this->num_licitacao != null?$this->num_licitacao.' - ':'').$this->descr_licitacao);
	  
	  //// retangulo dos dados do credor
	  $this->objpdf->rect($xcol+106,$xlin+2,96,18,2,'DF','1234');
	  $this->objpdf->Setfont('Arial','',6);
	  $this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
	  $this->objpdf->Setfont('Arial','B',8);
	  $this->objpdf->text($xcol+107,$xlin+7,'Numcgm');
	  $this->objpdf->text($xcol+135,$xlin+7,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	  $this->objpdf->text($xcol+107,$xlin+11,'Nome');
	  $this->objpdf->text($xcol+107,$xlin+15,'Endereço');
	  $this->objpdf->text($xcol+107,$xlin+19,'Município');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+124,$xlin+7,': '.$this->numcgm);
	  $this->objpdf->text($xcol+143,$xlin+7,': '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')).' - Fone: '.$this->telefone);
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
	  $this->objpdf->text($xcol+108,$xlin+34.0,'Valor Orçado');
	  $this->objpdf->text($xcol+157,$xlin+34.0,'Saldo Anterior');
	  $this->objpdf->text($xcol+108,$xlin+44.5,'Valor Empenhado');
	  $this->objpdf->text($xcol+157,$xlin+44.5,'Saldo Atual');
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+108,$xlin+27,'AUTORIZAÇÃO N'.chr(176).' '.db_formatar($this->numaut,'s','0',5,'e'));
	  $this->objpdf->text($xcol+150,$xlin+27,'SEQ. DO EMPENHO N'.chr(176).' '.db_formatar($this->numemp,'s','0',6,'e'));
  //	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
	  $this->objpdf->text($xcol+130,$xlin+38.0,db_formatar($this->orcado,'f'));
	  $this->objpdf->text($xcol+180,$xlin+38.0,db_formatar($this->saldo_ant,'f'));
	  $this->objpdf->text($xcol+130,$xlin+47.5,db_formatar($this->empenhado,'f'));
	  $this->objpdf->text($xcol+180,$xlin+47.5,db_formatar($this->saldo_ant - $this->empenhado,'f'));
	  

	  /// retangulo do corpo do empenho 
	  $this->objpdf->rect($xcol,$xlin+60,15,100,2,'DF','');
	  $this->objpdf->rect($xcol+15,$xlin+60,137,100,2,'DF','');
	  $this->objpdf->rect($xcol+152,$xlin+60,25,123,2,'DF','');
	  $this->objpdf->rect($xcol+177,$xlin+60,25,123,2,'DF','');
	  $this->objpdf->rect($xcol,$xlin+160,152,23,2,'DF','');
	  
	  //// retangulos do titulo do corpo do empenho
	  $this->objpdf->Setfont('Arial','B',7);
	  $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	  $this->objpdf->rect($xcol+15,$xlin+54,137,6,2,'DF','12');
	  $this->objpdf->rect($xcol+152,$xlin+54,25,6,2,'DF','12');
	  $this->objpdf->rect($xcol+177,$xlin+54,25,6,2,'DF','12');

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
	  $ele = 0;
	  $xtotal = 0;
	  for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	    db_fieldsmemory($this->recorddositens,$ii);
	    $this->objpdf->Setfont('Arial','B',7);
	    if($ele != pg_result($this->recorddositens,$ii,$this->analitico))
	    {
	      $this->objpdf->cell(15,4,'',0,0,"C",0);
	      $this->objpdf->cell(137,4,db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),0,1,"L",0);
	      $ele = pg_result($this->recorddositens,$ii,$this->analitico);
	    }
	    $this->objpdf->Setfont('Arial','',7);
	    $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->quantitem),pg_result($this->recorddositens,$ii,$this->descricaoitem),db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/pg_result($this->recorddositens,$ii,$this->quantitem),'f'),db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	    $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
   /////// troca de pagina
	    if( ( $this->objpdf->gety() > $this->objpdf->h - 125 && $pagina == 1 ) || ( $this->objpdf->gety() > $this->objpdf->h - 22 && $pagina != 1 )){

	     $proxima_pagina = $pagina + 1;
	     $this->objpdf->Row(array('',"Continua na página $proxima_pagina",'',''),3,false,4);
	     if ($pagina == 1){
	     $this->objpdf->rect($xcol,$xlin+183,152,6,2,'DF','34');
	     $this->objpdf->rect($xcol+152,$xlin+183,25,6,2,'DF','34');
	     $this->objpdf->rect($xcol+177,$xlin+183,25,6,2,'DF','34');
	     
	     $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
	     $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
	     $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
	     $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','4');
	     
	     
  //	   $this->objpdf->setfillcolor(0,0,0);
	     $this->objpdf->SetFont('Arial','',7);
	     $this->objpdf->text($xcol+2,$xlin+187,'DESTINO : ',0,1,'L',0);
	     $this->objpdf->text($xcol+30,$xlin+187,$this->destino,0,1,'L',0);
	    
	     $this->objpdf->setxy($xcol+1,$xlin+165);
	     $this->objpdf->text($xcol+2,$xlin+164,'RESUMO : ',0,1,'L',0);
	     $this->objpdf->multicell(147,3.5,$this->resumo);
	     
	     $this->objpdf->text($xcol+159,$xlin+187,'T O T A L',0,1,'L',0);
	     $this->objpdf->setxy($xcol+185,$xlin+182);
	     $this->objpdf->cell(30,10,db_formatar($this->empenhado,'f'),0,0,'f');

	     $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
	     $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
	     $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
	     $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
	     $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
	     $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');


	     if ($this->assinatura1 != "") {
		 $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
	     }

	     if ($this->assinatura2 != "") {
		 $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
	     }

	     if ($this->assinatura3 != "") {
		 $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);
	     }

	     $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);
	     $this->objpdf->SetFont('Arial','',6);
  //         $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
	     $this->objpdf->text($xcol+27-(strlen($this->assinatura1)/2),$xlin+213,$this->assinatura1);
	     $this->objpdf->text($xcol+27-(strlen($this->assinatura2)/2),$xlin+227,$this->assinatura2);
	     $this->objpdf->text($xcol+27-(strlen($this->assinatura3)/2),$xlin+240,$this->assinatura3);
	   
	     $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
	     $this->objpdf->text($xcol+88-(strlen($this->assinaturaprefeito)/2),$xlin+227,$this->assinaturaprefeito);
																							      
	     $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
  //  $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');
	    
	     $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
	     $this->objpdf->text($xcol+170,$xlin+207,'DATA');
	     $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
	     $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
	     $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
	     $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');
	    
	     $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
	     
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

	       
	      $this->objpdf->addpage();
	      $pagina += 1;	   
	      
	      $this->objpdf->settopmargin(1);
	      $xlin = 20;
	      $xcol = 4;
	  
	      $this->objpdf->setfillcolor(245);
	      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	      $this->objpdf->setfillcolor(255,255,255);
	      $this->objpdf->Setfont('Arial','B',11);

	      $this->objpdf->text(128,$xlin-13,'NOTA DE EMPENHO N'.CHR(176).': ');
	      $this->objpdf->text(175,$xlin-13,db_formatar($this->codemp,'s','0',6,'e'));
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
	      $xlin = -30;
	      $this->objpdf->Setfont('Arial','B',8);

  //  	    $this->objpdf->Roundedrect($xcol,$xlin+54,15,6,2,'DF','12');
	      $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	      $this->objpdf->rect($xcol+15,$xlin+54,127,6,2,'DF','12');
	      $this->objpdf->rect($xcol+142,$xlin+54,35,6,2,'DF','12');
	      $this->objpdf->rect($xcol+177,$xlin+54,25,6,2,'DF','12');

  //  	    $this->objpdf->Roundedrect($xcol,$xlin+60,15,262,2,'DF','34');
	      $this->objpdf->rect($xcol,$xlin+60,15,262,2,'DF','34');
	      $this->objpdf->rect($xcol+15,$xlin+60,127,262,2,'DF','34');
	      $this->objpdf->rect($xcol+142,$xlin+60,35,262,2,'DF','34');
	      $this->objpdf->rect($xcol+177,$xlin+60,25,262,2,'DF','34');
	      
	      $this->objpdf->sety($xlin+66);
	      $alt = 4;
	  
  //	    $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
	      $this->objpdf->text($xcol+0.5,$xlin+58,'QUANT');
	      $this->objpdf->text($xcol+65,$xlin+58,'MATERIAL OU SERVIÇO');
	      $this->objpdf->text($xcol+145,$xlin+58,'VALOR UNITÁRIO');
	      $this->objpdf->text($xcol+179,$xlin+58,'VALOR TOTAL');
	      $this->objpdf->text($xcol+38,$xlin+63,'Continuação da Página '.($this->objpdf->PageNo()-1));
	      
	      $maiscol = 0;
	      
	    }
	      
	  }

	  if ($pagina == 1){
	     $this->objpdf->rect($xcol,$xlin+183,152,6,2,'DF','34');
	     $this->objpdf->rect($xcol+152,$xlin+183,25,6,2,'DF','34');
	     $this->objpdf->rect($xcol+177,$xlin+183,25,6,2,'DF','34');
	     
  //	   $this->objpdf->setfillcolor(0,0,0);
	     $this->objpdf->SetFont('Arial','',7);
	     $this->objpdf->text($xcol+2,$xlin+187,'DESTINO : ',0,1,'L',0);
	     $this->objpdf->text($xcol+30,$xlin+187,$this->destino,0,1,'L',0);
	    
	     $this->objpdf->setxy($xcol+1,$xlin+165);
	     $this->objpdf->text($xcol+2,$xlin+164,'RESUMO : ',0,1,'L',0);
	     $this->objpdf->multicell(147,3.5,$this->resumo);
	     
	     $this->objpdf->text($xcol+159,$xlin+187,'T O T A L',0,1,'L',0);
	     $this->objpdf->setxy($xcol+185,$xlin+182);
	     $this->objpdf->cell(30,10,db_formatar($this->empenhado,'f'),0,0,'f');
  //	   $this->rodape($mod_rodape); 

	     
	     $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
	     $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
	     $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
	     $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','4');
	     
	     $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
	     $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
	     $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
	     $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
	     $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
	     $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');


	     if ($this->assinatura1 != "") {
		 $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
	     }

	     if ($this->assinatura2 != "") {
		 $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
	     }

	     if ($this->assinatura3 != "") {
		 $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);
	     }

	     $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);
	     $this->objpdf->SetFont('Arial','',6);
  //         $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
	     $this->objpdf->text($xcol+27-(strlen($this->assinatura1)/2),$xlin+213,$this->assinatura1);
	     $this->objpdf->text($xcol+27-(strlen($this->assinatura2)/2),$xlin+227,$this->assinatura2);
	     $this->objpdf->text($xcol+27-(strlen($this->assinatura3)/2),$xlin+240,$this->assinatura3);
	   
	     $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
	     $this->objpdf->text($xcol+88-(strlen($this->assinaturaprefeito)/2),$xlin+227,$this->assinaturaprefeito);
																							      
	     $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
	     $this->objpdf->text($xcol+170,$xlin+207,'DATA');
	     $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
	     $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
	     $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
	     $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');
	    
	     $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
	     
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
	     $xlin = 169;
	  }    
	}
     }
     
    }else if ( $this->modelo == 7 ) {     
      
////////// MODELO 7  -  ORDEM DE PAGAMENTO
	
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
	$this->objpdf->text(128,$xlin-13,'ORDEM DE PAGAMENTO N'.CHR(176).': ');
	$this->objpdf->text(177,$xlin-13,db_formatar($this->ordpag,'s','0',6,'e'));
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
	$this->objpdf->rect($xcol,$xlin+2,$xcol+100,39,2,'DF','1234');
	$this->objpdf->Setfont('Arial','B',8);
	//if($ano < 2005){
        //  $this->objpdf->text($xcol+2,$xlin+19,'RESTOS A PAGAR ');
	//}else{
	  $this->objpdf->text($xcol+2,$xlin+7,'Órgao');
	  $this->objpdf->text($xcol+2,$xlin+11,'Unidade');
	  $this->objpdf->text($xcol+2,$xlin+15,'Função');
	
	  $this->objpdf->text($xcol+2,$xlin+19,'Proj/Ativ');
	  $this->objpdf->text($xcol+2,$xlin+23,'Dotação');
	  $this->objpdf->text($xcol+2,$xlin+27,'Elemento');
	  $this->objpdf->text($xcol+2,$xlin+34,'Recurso');
	
	  $this->objpdf->Setfont('Arial','',8);
	  $this->objpdf->text($xcol+17,$xlin+7,':  '.db_formatar($this->orgao,'orgao').' - '.$this->descr_orgao);
	  $this->objpdf->text($xcol+17,$xlin+11,':  '.db_formatar($this->unidade,'unidade').' - '.$this->descr_unidade);
	  $this->objpdf->text($xcol+17,$xlin+15,':  '.db_formatar($this->funcao,'funcao').' - '.$this->descr_funcao);
	
	  $this->objpdf->text($xcol+17,$xlin+19,':  '.db_formatar($this->projativ,'projativ').' - '.$this->descr_projativ);
	  $this->objpdf->text($xcol+17,$xlin+23,':  '.$this->dotacao);
	  $this->objpdf->text($xcol+17,$xlin+27,':  '.db_formatar($this->elemento,'elemento'));
	  $this->objpdf->text($xcol+17,$xlin+30,'   '.$this->descr_elemento);
	  $this->objpdf->text($xcol+17,$xlin+34,':  '.$this->recurso.' - '.$this->descr_recurso);
	//}
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
 	  $this->objpdf->text($xcol+131,$xlin+25,': '.$this->banco.' / '.$this->agencia.' / '.$this->conta);
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
//        $this->objpdf->Roundedrect($xcol+177,$xlin+179, 25,5,2,'DF','34');
 

	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+34,'Empenho N'.chr(176));
	$this->objpdf->text($xcol+157,$xlin+34,'Valor do Empenho');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+130,$xlin+38,db_formatar($this->numemp,'s','0',6,'e'));
	$this->objpdf->text($xcol+180,$xlin+38,db_formatar($this->empenhado,'f'));
	
	//// retangulos do titulo do corpo do empenho
//	$this->objpdf->line($xcol,$xlin+42,$xcol+202,$xlin+42);

	
	$this->objpdf->Setfont('Arial','B',10);
	$this->objpdf->text($xcol+2,$xlin+46,'Dados da Ordem de Pagto.');
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
	$this->objpdf->text($xcol+104,$xlin+131,'Dados das Retenções');
	$this->objpdf->text($xcol+2,$xlin+131,'Repasses');
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
	

	$this->objpdf->setxy($xcol+127,$xlin+107);
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->cell(50,5,'TOTAL DA ORDEM',0,0,"R");
	$this->objpdf->cell(23,5,db_formatar($total_emp-$total_anu,'f'),0,1,"R");
	$this->objpdf->setx($xcol+127);
	$this->objpdf->cell(50,5,'OUTRAS ORDENS',0,0,"R");
	$this->objpdf->cell(23,4,db_formatar($this->outrasordens,'f'),0,1,"R");
	$this->objpdf->setx($xcol+127);
	$this->objpdf->cell(50,5,'VALOR RESTANTE',0,0,"R");
	$this->objpdf->cell(23,4,db_formatar($this->empenhado - $this->outrasordens - $total_emp - $total_anu ,'f'),0,1,"R");
	$this->objpdf->Setfont('Arial','b',8);
	$this->objpdf->text($xcol+2,$xlin+102,'OBSERVAÇÕES :');
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->setxy($xcol,$xlin+103);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->multicell(102,4,$this->obs);
        
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
	$this->objpdf->cell(50,5,'LÍQUIDO DA ORDEM DE PAGTO. ',0,0,"R");
	$this->objpdf->cell(23,5,db_formatar($total_sal - $total_ret,'f'),0,1,"R");

	
        $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
        $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
        $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
        $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','');
        

        $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
        $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
        $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
        $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
        $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
        $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');
        
        
	
	if($this->municpref == "GUAIBA"){
	    $this->objpdf->SetFont('Arial','',6);
            $this->objpdf->line($xcol+12,$xlin+221,$xcol+43,$xlin+221);
            $this->objpdf->line($xcol+74,$xlin+221,$xcol+100,$xlin+221);
	    
	    $this->objpdf->text($xcol+13,$xlin+224,'JORGE ANTONIO POKORSKI');
	    $this->objpdf->text($xcol+76,$xlin+224,'MANOEL STRINGHINI');
	    $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');
            $this->objpdf->text($xcol+13,$xlin+227,'SECRETÁRIO DA FAZENDA');

	}else{  
            $this->objpdf->line($xcol+5,$xlin+211,$xcol+54,$xlin+211);
            $this->objpdf->line($xcol+5,$xlin+238,$xcol+54,$xlin+238);
            $this->objpdf->line($xcol+65,$xlin+225,$xcol+114,$xlin+225);
	    
	    $this->objpdf->SetFont('Arial','',6);
	    $this->objpdf->text($xcol+12,$xlin+199,'EMPENHADO E CONFERIDO');
	    $this->objpdf->text($xcol+26,$xlin+213,'VISTO');
	    if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
	      $this->objpdf->line($xcol+5,$xlin+225,$xcol+54,$xlin+225);
	      $this->objpdf->text($xcol+19,$xlin+227,'TÉCNICO CONTÁBIL');
	      $this->objpdf->text($xcol+13,$xlin+240,'SECRETÁRIO(A) DA FAZENDA');
	    }
	    $this->objpdf->text($xcol+66,$xlin+212,'DATA  ____________/____________/____________');
	    $this->objpdf->text($xcol+76,$xlin+227,'PREFEITO MUNICIPAL');
        }



	
       
        $this->objpdf->text($xcol+122,$xlin+207,'CHEQUE N'.chr(176));
        $this->objpdf->text($xcol+170,$xlin+207,'DATA');
        $this->objpdf->text($xcol+122,$xlin+215,'BANCO N'.chr(176));
        $this->objpdf->text($xcol+127,$xlin+218,'DOCUMENTO N'.chr(176));
        $this->objpdf->line($xcol+155,$xlin+240,$xcol+200,$xlin+240);
        $this->objpdf->text($xcol+170,$xlin+242,'TESOUREIRO');
       
        $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
	   
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
        $xlin = 169;
      }   
    }else if ( $this->modelo == 8 ) {     
      
        //// Ficha de transferencia de bens


	$this->objpdf->AliasNbPages();
//	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
        $this->objpdf->line(2,148.5,208,148.5);
	$xlin = 20;
	$xcol = 4;
	$comeco = 0;
	$passada = 0;
	if ($this->linhasbens < 40)
	   $vias = 2;
	elseif ($this->linhasbens < 80)
	   $vias = 4;
	elseif ($this->linhasbens < 120)
	   $vias = 6;
	elseif ($this->linhasbens < 160)
	   $vias = 8;
	elseif ($this->linhasbens < 200)
	   $vias = 10;
       	for ($i = 0;$i < $vias;$i++){
	  if (($i % 2 ) == 0)
	     $this->objpdf->AddPage();
		$this->objpdf->setfillcolor(245);
		$this->objpdf->roundedrect($xcol-2,$xlin-18,206,144.5,2,'DF','1234');
		$this->objpdf->setfillcolor(255,255,255);
//		$this->objpdf->roundedrect(10,07,190,183,2,'DF','1234');
		$this->objpdf->Setfont('Arial','B',11);
		$this->objpdf->text(150,$xlin-13,'TRANSFERÊNCIA N'.chr(176).'  '.$this->codtransf);
		$this->objpdf->text(159,$xlin-8,$this->datacalc);
		$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
		$this->objpdf->Setfont('Arial','B',9);
		$this->objpdf->text(40,$xlin-15,$this->prefeitura);
		$this->objpdf->Setfont('Arial','',9);
		$this->objpdf->text(40,$xlin-11,$this->enderpref);
		$this->objpdf->text(40,$xlin-8,$this->municpref);
		$this->objpdf->text(40,$xlin-5,$this->telefpref);
		$this->objpdf->text(40,$xlin-2,$this->emailpref);
//		$this->objpdf->setfillcolor(245);
	
		$this->objpdf->Roundedrect($xcol,$xlin+2,$xcol+98,20,2,'DF','1234');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+2,$xlin+5,'Origem:');
		$this->objpdf->Setfont('Arial','b',8);
		$this->objpdf->text($xcol+2,$xlin+9,'Departamento ');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+22,$xlin+9,':  '.$this->origem);
		$this->objpdf->Setfont('Arial','b',8);
		$this->objpdf->text($xcol+2,$xlin+16,'Usuario');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+22,$xlin+16,':  '.$this->usuario);
		$this->objpdf->Setfont('Arial','',6);
	
		$this->objpdf->Roundedrect($xcol+104,$xlin+2,98,20,2,'DF','1234');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+106,$xlin+5,'Destino:');
		$this->objpdf->Setfont('Arial','b',8);
		$this->objpdf->text($xcol+106,$xlin+9,'Departamento');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+128,$xlin+9,':  '.$this->destino);

//		$this->objpdf->setfillcolor(245);
		$this->objpdf->Roundedrect($xcol,$xlin+24,202,70,2,'DF','1234');
		$this->objpdf->Setfont('Arial','',8);
		$this->objpdf->text($xcol+2,$xlin+27,'Itens a Transmitir :');
		$this->objpdf->Setfont('Arial','b',8);
		$this->objpdf->text($xcol+2,$xlin+30,'BEM');
		$this->objpdf->text($xcol+25,$xlin+30,'DESCRIÇÃO');
		$this->objpdf->text($xcol+75,$xlin+30,'CLASSIFICAÇÃO');
		$this->objpdf->text($xcol+102,$xlin+30,'BEM');
		$this->objpdf->text($xcol+125,$xlin+30,'DESCRIÇÃO');
		$this->objpdf->text($xcol+175,$xlin+30,'CLASSIFICAÇÃO');
		$this->objpdf->Setfont('Arial','',8);
	   	$this->objpdf->sety($xlin+31);
                $maiscol = 0;
                $yy = $this->objpdf->gety();
		for($ii = $comeco;$ii < $this->linhasbens ;$ii++) {
		   if (($ii % 40 ) == 0 && $ii > 0 && $passada == 0){
		      $maiscol = 0;
		      $passada ++;
		      $comeco = $ii;
                      break;
                   }elseif (($ii % 20 ) == 0 && $ii > 0 && ($ii % 40 ) != 0){
                      $maiscol = 100;
                      $this->objpdf->sety($yy);
                   }
		   
	   	   $this->objpdf->setx($xcol+3+$maiscol);
	   	   $this->objpdf->cell(5,3,trim(pg_result($this->recordbens,$ii,$this->bem)),0,0,"R",0);
	  	   $this->objpdf->cell(70,3,trim(pg_result($this->recordbens,$ii,$this->descr_bem)),0,0,"L",0);
 		   $this->objpdf->cell(15,3,pg_result($this->recordbens,$ii,$this->class_bem),0,1,"R",0);
		   if(($ii+1) == $this->linhasbens ){
		     $comeco = 0;
		     $passada = 0;
		     break;  
		   }
		}
		$this->objpdf->line($xcol+10,$xlin+116,$xcol+70,$xlin+116);
		$this->objpdf->text($xcol+30,$xlin+120,'TRANSMITENTE');
		$this->objpdf->line($xcol+135,$xlin+116,$xcol+195,$xlin+116);
		$this->objpdf->text($xcol+155,$xlin+120,'RECEBEDOR');
		
	  if (($i % 2 ) == 0)
	    $xlin = 169;
	  else
	    $xlin = 20;

      }

    }else if ( $this->modelo == 9 ) {

	$this->objpdf->SetTextColor(0,0,0);
	$this->objpdf->SetFont('Arial','B',12);
	$coluna = 44;
	$linha = 20;
	$this->objpdf->SetLineWidth(1);
	$this->objpdf->RoundedRect(37,0.2,137,195,2,'1234');
	$this->objpdf->SetLineWidth(0.5);
	$this->objpdf->roundedrect(39,2,133,191,2,'1234');
	$this->objpdf->SetLineWidth(0.2);
	$this->objpdf->Image('imagens/files/Brasao.png',43,5,20);
	$this->objpdf->Image('imagens/files/Brasao.jpg',60,30,100);
	
//	$this->objpdf->roundedrect(42,$linha+30,127,35,2,'1234');
//	$this->objpdf->roundedrect(42,$linha+72,127,15,2,'1234'); // obs da atividade principal
	
//  	$this->objpdf->roundedrect(42,$linha+88,127,5,2,'1234'); // descricao da atividade secundaria
//	$this->objpdf->roundedrect(42,$linha+94,127,15,2,'1234'); // obs da atividade secundaria

//	$this->objpdf->setdrawcolor(235);

	$this->objpdf->setxy(65,8);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->Multicell(0,8,$this->prefeitura,"C"); // prefeitura

	$this->objpdf->setxy(65,15);
	$this->objpdf->setfont('Arial','B',13);
	$this->objpdf->setleftmargin(50);
	$this->objpdf->setrightmargin(50);
	$this->objpdf->Multicell(0,8,$this->tipoalvara,0,"C",0); // tipo de alvara

        $this->objpdf->Ln(6);
	$this->objpdf->sety(28);
	$this->objpdf->SetFont('Arial','',11);
	$this->objpdf->multicell(0,5,db_geratexto($this->texto),0,"J",0,20);

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+35,'INSCRIÇÃO:'); // inscricao

        if ($this->processo > 0) {
	  $this->objpdf->Text($coluna + 70,$linha+35,'PROCESSO:'); // inscricao
        }

	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+35,$this->nrinscr); // inscricao
        
        if ($this->processo > 0) {
  	  $this->objpdf->Text($coluna + 90,$linha+35,$this->processo); // processo
        }
	
	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+39,"NOME/RAZAO SOCIAL: "); // nome
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+39,$this->nome); // nome

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+43,"CNPJ/CPF: ");
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+43,$this->cnpjcpf);


	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+47,"ENDEREÇO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+47,$this->ender); // endereco

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+51,"NÚMERO: "); // endereco
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+51,($this->numero == ""?"":$this->numero));

        if ($this->compl != "") {
   	  $this->objpdf->SetFont('Arial','B',9);
	  $this->objpdf->Text($coluna + 60 ,$linha+51,"COMPLEMENTO: "); // endereco
	  $this->objpdf->SetFont('Arial','',9);
	  $this->objpdf->Text($coluna + 90,$linha+51,($this->compl == ""?"":$this->compl));
        }

	$this->objpdf->SetFont('Arial','B',9);
	$this->objpdf->Text($coluna,$linha+55,"DATA DE INCLUSAO: ");
        if ($this->datafim != "") {
  	  $this->objpdf->Text($coluna + 60,$linha+55,"VALIDADE ATÉ: ");
        }
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Text($coluna + 40,$linha+55,db_formatar($this->datainc,'d'));
        if ($this->datafim != "") {
	  $this->objpdf->Text($coluna + 85,$linha+55,db_formatar($this->datafim,'d'));
        }

        $this->objpdf->setx(40);

	if($this->q02_memo!=''){
	  $this->objpdf->SetFont('Arial','B',9);
	  $this->objpdf->Text($coluna,$linha+59,"OBSERVAÇÃO: "); // observação
	  $this->objpdf->SetFont('Arial','',9);
	  $this->objpdf->sety($linha+60);
	  $this->objpdf->Multicell(0,3,$this->q02_memo); // texto
	  $this->objpdf->SetFont('Arial','B',10);
  	  $this->objpdf->roundedrect(42,$linha+30,127,38,2,'1234');
	  $linha = 90;
	} else {
  	  $this->objpdf->roundedrect(42,$linha+30,127,27,2,'1234');
	  $linha = 79;
	}

          $this->objpdf->sety($linha);
         
	  $this->objpdf->roundedrect(42,$linha-1,127,5,2,'1234');
	  $this->objpdf->SetFont('Arial','B',8);
  	  $this->objpdf->Ln(0.5);
	  $this->objpdf->setx(45);
	  $this->objpdf->Multicell(0,3,"ATIVIDADE PRINCIPAL: " . $this->descrativ) ; // descrição da atividade principal
  	  $linha += 6;
          $yyy = $this->objpdf->gety();
	  $obs='';
	  if(isset($this->q03_atmemo[$this->ativ])){
	    if ($this->q03_atmemo[$this->ativ] != '') {;
	      $obs = $this->q03_atmemo[$this->ativ];
	      $this->objpdf->Ln(3);
	      $this->objpdf->SetFont('Arial','',7);
	      $this->objpdf->Multicell(0,3,$this->q03_atmemo[$this->ativ]); // texto
//		 $linha += 16;
              $yyyatual = $this->objpdf->gety();
	      $this->objpdf->roundedrect(42,$linha-1,127,$yyyatual-$yyy,2,'1234'); // obs da atividade principal
	    }
	  }

        $yyy = $this->objpdf->gety();
        $linha = $this->objpdf->gety() + 2;
        $this->objpdf->sety($linha);
//        $this->objpdf->sety($linha);
	  
        $num_outras=count($this->outrasativs);
	$x=105;
	$y=$linha+1;
        if ($num_outras >0) {

           $x=$x+4;
	   reset($this->outrasativs); 

  	   $this->objpdf->Ln(2);
	   $this->objpdf->setx(45);
           $yyy = $this->objpdf->gety() + 7;
	   $this->objpdf->SetFont('Arial','B',8);
//           $this->objpdf->roundedrect(42,$yyy-1,127,5,2,'1234'); // descricao da atividade secundaria
           $this->objpdf->Multicell(0,3,"ATIVIDADE" . ($num_outras > 1?"S":"") . " SECUNDÁRIA" . ($num_outras > 1?"S":"") . ":");
           $linha += 6;
  	   $this->objpdf->Ln(2);

           $this->objpdf->roundedrect(42,$y,127,5,2,'1234'); // descricao da atividade secundaria

	   for($i=0; $i<$num_outras; $i++){
             $yyy = $this->objpdf->gety();
	     $chave=key($this->outrasativs);
	     $obs='';
	     if(isset($this->q03_atmemo[$chave])){
	       $obs = $this->q03_atmemo[$chave];
	     }

	     $this->objpdf->SetFont('Arial','',8);

//             $this->objpdf->roundedrect(42,$yyy-1,127,5,2,'1234'); // descricao da atividade secundaria
  	     $this->objpdf->Ln(0.5);
	     $this->objpdf->setx(45);
// 	     $this->objpdf->Multicell(0,3,"ATIVIDADE SECUNDÁRIA: " . $this->outrasativs[$chave]); // texto
 	     $this->objpdf->Multicell(0,3,$this->outrasativs[$chave]); // texto
	     $linha += 4;

	     if($obs!=""){
               $yyyant = $this->objpdf->gety();
//               $this->objpdf->Ln(1);
  	       $this->objpdf->SetFont('Arial','B',7);
 	       $this->objpdf->Multicell(0,3,$obs); // texto
               $yyyatual = $this->objpdf->gety();
//               $this->objpdf->roundedrect(42,$linha-1,127,$yyyatual-$yyyant+1,2,'1234'); // obs da atividade secundaria
	       $linha += $yyyatual-$yyyant;
	     }

	     $x=$x+4;
	     next($this->outrasativs);
//             $this->objpdf->ln(2.5);
	     $this->objpdf->sety($linha);

             $yyyatual = $this->objpdf->gety();

//	     if  ($i >= 5) break;
	     if  ($yyyatual >= 130) break;
	     
	   }

           $this->objpdf->roundedrect(42,$y,127,$linha-$y,2,'1234'); // descricao da atividade secundaria
	   
 	}


        $x=64;

//	$this->objpdf->sety(135);
	$this->objpdf->SetFont('Arial','',9);
	$this->objpdf->Multicell(0,4,$this->obs); // observação

//        if($this->q02_obs!=''){
//	  $this->objpdf->Text($coluna,$linha+$x,"OBSERVAÇÃO: "); // descrição da atividade principal
//	  $this->objpdf->Text($coluna + 45,$linha+$x,$this->q02_obs); // descrição da atividade principal
//	  $x=$x+4;
//	}

        $this->objpdf->ln(10);
        $this->objpdf->SetFont('Arial','B',9);
        $this->objpdf->cell($coluna,5,'',0,0,"L",0);
        $this->objpdf->cell(60,5,"DATA DE EMISSÃO DESTE DOCUMENTO.",0,1,"L",0);
        $this->objpdf->cell($coluna,5,'',0,0,"L",0);
        $this->objpdf->cell(60,5,$this->municpref . ", ".date('d')." DE ".strtoupper(db_mes( date('m')))." DE ".date('Y') . ".",0,1,"L",0); // data
       
        global $db02_texto;

	$sqlparag = "select db02_texto 
		     from db_documento 
		     inner join db_docparag on db03_docum = db04_docum 
		     inner join db_paragrafo on db04_idparag = db02_idparag 
		     where db03_docum = 26 and db02_descr ilike '%Assinatura Secretario%' and db03_instit = " . db_getsession("DB_instit");
	$resparag = db_query($sqlparag);

	if ( pg_numrows($resparag) == 0 ) {
	  db_redireciona('db_erros.php?fechar=true&db_erro=Configure o documento 26 com os paragrafos do alvara!');
	  exit; 
	}

	db_fieldsmemory($resparag,0);

	$this->objpdf->setfont('arial','',6);
        $this->objpdf->SetXY($coluna-18,170);

        $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".$db02_texto,0,"C",0);
        $this->objpdf->SetXY($coluna+50,170);
        $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);
					
//        $this->objpdf->SetXY($coluna-35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................'."\n".'SECRETÁRIO DA IND. COM. E TURISMO',0,"C",0);
//        $this->objpdf->SetXY($coluna+35,160);
//        $this->objpdf->MultiCell(90,4,'..........................................................................................',0,"C",0);


	$this->objpdf->sety(185);
        $this->objpdf->setfont('arial','B',12);
        $this->objpdf->multicell(0,6,'FIXAR EM LUGAR VISÍVEL',1,"C");
	$this->objpdf->SetFont('Arial','B',10);
    
    }else if ( $this->modelo == 10 ) {


////////// MODELO 10  -  ORDEM DE COMPRA
	
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
	$this->objpdf->rect($xcol+ 27,$xlin+24,11,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 38,$xlin+24,104,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+24,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+24,30,6,2,'DF','12');

	$this->objpdf->rect($xcol    ,$xlin+30,12,205  -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+ 12,$xlin+30,15,205  -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+ 27,$xlin+30,11,205  -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+ 38,$xlin+30,104,205 -$xlin ,2,'DF','34');
	$this->objpdf->rect($xcol+142,$xlin+30,30,205  -$xlin ,2,'DF','');
	$this->objpdf->rect($xcol+172,$xlin+30,30,205  -$xlin ,2,'DF','34');




//	$this->objpdf->rect($xcol,$xlin+182,142,23,2,'DF','');

	
   	$this->objpdf->sety($xlin+28);
	$alt = 4;
	
	$this->objpdf->text($xcol+   2,$xlin+28,'ITEM');
	$this->objpdf->text($xcol+12.5,$xlin+28,'EMPENHO');
	$this->objpdf->text($xcol+27.5,$xlin+28,'QUANT');
	$this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');
        $maiscol = 0;


        $this->objpdf->setfillcolor(0,0,0);
	$this->objpdf->text($xcol+10,290,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
        
	$this->objpdf->text($xcol+ 120,290,'___________________________________________');
	
	$this->objpdf->SetWidths(array(12,16,10,104,30,30));
	$this->objpdf->SetAligns(array('C','C','C','L','R','R'));
	
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
				   db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem)/pg_result($this->recorddositens,$ii,$this->quantitem),'f'),
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
	$this->objpdf->rect($xcol+ 27,$xlin+54,11,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 38,$xlin+54,104,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	$this->objpdf->rect($xcol,    $xlin+60,12,252,2,'DF','34');
	$this->objpdf->rect($xcol+ 12,$xlin+60,15,252,2,'DF','34');
	$this->objpdf->rect($xcol+ 27,$xlin+60,11,252,2,'DF','34');
	$this->objpdf->rect($xcol+ 38,$xlin+60,104,252,2,'DF','34');
	$this->objpdf->rect($xcol+142,$xlin+60,30,252,2,'DF','');
	$this->objpdf->rect($xcol+172,$xlin+60,30,252,2,'DF','34');

	    $this->objpdf->sety($xlin+66);
	    $alt = 4;

	    $this->objpdf->text($xcol+   2,$xlin+59,'ITEM');
	    $this->objpdf->text($xcol+12.5,$xlin+59,'EMPENHO');
	    $this->objpdf->text($xcol+27.5,$xlin+59,'QUANT');
	    $this->objpdf->text($xcol+  70,$xlin+59,'MATERIAL OU SERVIÇO');
	    $this->objpdf->text($xcol+ 145,$xlin+59,'VALOR UNITÁRIO');
	    $this->objpdf->text($xcol+ 176,$xlin+59,'VALOR TOTAL');
	    $this->objpdf->text($xcol+  40,$xlin+63,'Continuação da Página '.($pagina-1));

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
	  $this->objpdf->rect($xcol+ 27,$xlin+312,11,10,2,'DF','34');
	  $this->objpdf->rect($xcol+ 38,$xlin+312,104,10,2,'DF','34');
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

    }else if ( $this->modelo == 11 ) {
        global $contapagina;
	$contapagina=1;
        if(!in_array("cl_orcreservasol",get_declared_classes())){
          include("classes/db_orcreservasol_classe.php"); 
	}
	$clorcreservasol = new cl_orcreservasol;
////////// MODELO 11  -  SOLICITAÇÃO DE COMPRA
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;

	// Imprime caixa externa
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

        // Imprime o cabeçalho com dados sobre a prefeitura
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,$xlin-13,'SOLICITAÇÃO DE COMPRA N'.CHR(176));
	$this->objpdf->text(185,$xlin-13,db_formatar($this->Snumero,'s','0',6,'e'));
        $this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->text(130,$xlin-9,'ORGÃO');
	$this->objpdf->text(142,$xlin-9,': '.substr($this->Sorgao,0,35));
	$this->objpdf->text(130,$xlin-5,'UNIDADE');
	$this->objpdf->text(142,$xlin-5,': '.substr($this->Sunidade,0,35));
	$this->objpdf->text(130,$xlin-1,'USUÁRIO');
	$this->objpdf->text(142,$xlin-1,': '.substr($this->Susuarioger,0,35));
        $this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);
	$this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));

 	$this->objpdf->Setfont('Arial','B',8);
	// caixa para frases
	$this->objpdf->rect($xcol,$xlin+3,$xcol+198,9,2,'DF','1234');
	$this->objpdf->SetXY(4,$xlin+4);
	$this->objpdf->MultiCell(202,4,'QUANDO NECESSÁRIO FRETE, O MESMO CORRERÁ POR CONTA DO FORNECEDOR',0,"C",0);
	$this->objpdf->SetXY(4,$xlin+8);
	$this->objpdf->MultiCell(202,4,'TODO FRETE DEVERÁ SER PAGO PELA EMPRESA REMETENTE - O MATERIAL DEVERÁ SER DE PRIMEIRA QUALIDADE',0,"C",0);
	$this->objpdf->Setfont('Arial','',8);

        // Caixa com dados da solicitação
	$this->objpdf->rect($xcol,$xlin+13,$xcol+198,10,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+15,'Dados do Solicitação');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+  2,$xlin+18,'Departamento');
	$this->objpdf->text($xcol+109,$xlin+18,'Tipo');
	$this->objpdf->text($xcol+  2,$xlin+22,'Data');
	$this->objpdf->text($xcol+109,$xlin+22,'Val. Aprox.');

	// Imprime dados da solicitação
	$this->objpdf->text($xcol+ 23,$xlin+18,':  '.$this->Sdepart);
	if(isset($this->Sdata) && trim($this->Sdata)!=""){
	  $this->Sdata = db_formatar($this->Sdata,'d');
	}
	if(isset($this->Svalor) && trim($this->Svalor)!=""){
          $this->Svalor = db_formatar($this->Svalor,'f');
	}
	$this->objpdf->text($xcol+125,$xlin+18,':  '.$this->Stipcom);
	$this->objpdf->text($xcol+ 23,$xlin+22,':  '.$this->Sdata);
	$this->objpdf->text($xcol+125,$xlin+22,':  R$ '.$this->Svalor);

        $this->objpdf->text($xcol+  2,$xlin+27,'Resumo');
	$this->objpdf->setxy($xcol+22,$xlin+24);
	$this->objpdf->cell(3,4,':  ',0,0,"L",0);
	$this->objpdf->setxy($xcol+24.5,$xlin+24);
	$posini = $this->objpdf->gety();
	$this->objpdf->multicell(175,4,trim(AddSlashes($this->Sresumo)),0,"j");
	$setaut = $this->objpdf->gety();

	$oldsetaut = $setaut;
	
	$setaut += 8;
	$newsetaut = $setaut;
	if($setaut>64){
	  $newsetaut = $setaut - 8;
	  $tiramenos = $setaut-64;
	  $setaut = $setaut-$posini;
	}else if($setaut==64){
	  $newsetaut = $setaut-8;
	  $setaut -= 8;
	}else if($setaut==60){
	  $newsetaut = $setaut-4;
	  $setaut -= 4;
	}
//	db_msgbox($setaut.' -- '.$posini);

        $this->objpdf->rect($xcol,$xlin+24,$xcol+198,$newsetaut-$posini,2,'DF','1234');	

        $getdoy = 32;
	$contafornec = 0;
	if($this->linhasdosfornec>0){
	  $x = $this->muda_pag($pagina,$xlin,$xcol,"true",$contapagina);
	  for($i=0;$i<$this->linhasdosfornec;$i++){
	    $contafornec += 4;
	  }
	  $onze = 11;
	  if($oldsetaut+8>64){
	    $setaut += 36;
	  }

	  $this->objpdf->Setfont('Arial','B',8);
	  // Caixa de texto para labels 
	  $this->objpdf->rect($xcol    ,$setaut+0.8,202,6,2,'DF','12');
	  $this->objpdf->text($xcol+  4,$setaut+4.2,'FORNECEDORES SUGERIDOS ');

	  $this->objpdf->rect($xcol    ,$setaut+6.8,15,6,2,'DF','12');
	  $this->objpdf->rect($xcol+15 ,$setaut+6.8,64,6,2,'DF','12');
	  $this->objpdf->rect($xcol+79 ,$setaut+6.8,63,6,2,'DF','12');
	  $this->objpdf->rect($xcol+142,$setaut+6.8,40,6,2,'DF','12');
	  $this->objpdf->rect($xcol+182,$setaut+6.8,20,6,2,'DF','12');
	  
	  $this->objpdf->rect($xcol    ,$setaut+12.8,15,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+15 ,$setaut+12.8,64,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+79 ,$setaut+12.8,63,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$setaut+12.8,40,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+182,$setaut+12.8,20,$contafornec+1,2,'DF','34');
	  $this->objpdf->sety($xlin+66);

	  // Label das colunas
	  $this->objpdf->text($xcol+   4,$setaut+11,'CGM');
	  $this->objpdf->text($xcol+30.5,$setaut+11,'NOME/RAZÃO SOCIAL');
	  $this->objpdf->text($xcol+ 103,$setaut+11,'ENDEREÇO');
	  $this->objpdf->text($xcol+ 155,$setaut+11,'MUNICÍPIO');
	  $this->objpdf->text($xcol+184.5,$setaut+11,'TELEFONE');

	  // Seta altura nova para impressão dos dados
	  $this->objpdf->sety($setaut+13.8);
	  $this->objpdf->setx($xcol);
	  $this->objpdf->setleftmargin(4);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->SetAligns(array('C','L','L','L','C'));
	  $this->objpdf->SetWidths(array(15,64,63,40,20));
	  for($i=0;$i<$this->linhasdosfornec;$i++){
	    db_fieldsmemory($this->recorddosfornec,$i);
	    $cgmforn   = trim(pg_result($this->recorddosfornec,$i,$this->cgmforn));
	    $nomeforn  = trim(pg_result($this->recorddosfornec,$i,$this->nomeforn));
	    $enderforn = trim(pg_result($this->recorddosfornec,$i,$this->enderforn));
	    $numforn   = trim(pg_result($this->recorddosfornec,$i,$this->numforn));
	    $municforn = trim(pg_result($this->recorddosfornec,$i,$this->municforn));
	    $foneforn  = trim(pg_result($this->recorddosfornec,$i,$this->foneforn));
	    $this->objpdf->Row(array($cgmforn,$nomeforn,$enderforn.", ".$numforn,$municforn,$foneforn),4,false,4);
	  }
	  $getdoy = $this->objpdf->gety();
	  $getdoy+= 0.8;
	  $getdoy = $getdoy-$xlin;
	  $contafornec+= 8;
	}else{
	  $getdoy += 4.8;
          if(($oldsetaut+8)>64){
            $getdoy += 8;
          }
	}

        // Caixas dos label's
	$this->objpdf->rect($xcol    ,$xlin+$getdoy,10,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 10,$xlin+$getdoy,12,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 22,$xlin+$getdoy,22,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 44,$xlin+$getdoy,98,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+$getdoy,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+$getdoy,30,6,2,'DF','12');

	$menos = 16.9;
	if($this->linhasdosfornec==0){
	  $menos = 11;
	}
	if(isset($tiramenos)){
	  $menos = $menos+$tiramenos;
	  if($menos<0){
	    $menos = -$menos;
	  }
	}
        // Caixa dos itens
	$this->objpdf->rect($xcol,    $xlin+$getdoy+6,10,193-$contafornec-$menos,2,'DF','34');
        // Caixa da quantidade
	$this->objpdf->rect($xcol+ 10,$xlin+$getdoy+6,12,193-$contafornec-$menos,2,'DF','34');
	
	$this->objpdf->rect($xcol+ 22,$xlin+$getdoy+6,22,193-$contafornec-$menos,2,'DF','34');
        // Caixa dos materiais ou serviços
	$this->objpdf->rect($xcol+ 44,$xlin+$getdoy+6,98,193-$contafornec-$menos,2,'DF','34');
        // Caixa dos valores unitários
	$this->objpdf->rect($xcol+142,$xlin+$getdoy+6,30,193-$contafornec-$menos,2,'DF','');
        // Caixa dos valores totais dos itens
	$this->objpdf->rect($xcol+172,$xlin+$getdoy+6,30,193-$contafornec-$menos,2,'DF','34');

   	$this->objpdf->sety($xlin+28);
	
        // Label das colunas
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+   2,$xlin+$getdoy+4,'ITEM');
	$this->objpdf->text($xcol+11,$xlin+$getdoy+4,'QUANT');
	$this->objpdf->text($xcol+30,$xlin+$getdoy+4,'REF');
	$this->objpdf->text($xcol+  70,$xlin+$getdoy+4,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+$getdoy+4,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 176,$xlin+$getdoy+4,'VALOR TOTAL');
        $maiscol = 0;

	$this->objpdf->setleftmargin(3);
	$this->objpdf->sety($xlin+$getdoy+7);

	$xtotal = 0;
	$muda_pag = false;
	$index = 0;

	for($ii = 0;$ii < $this->linhasdositens ;$ii++){
	  $itemant = "";
          $pass = false;
	  $this->objpdf->SetWidths(array(10,12,24,95,30,30));
	  $this->objpdf->SetAligns(array('C','C','C','J','R','R'));
	  $pagina = $this->objpdf->PageNo();
	  db_fieldsmemory($this->recorddositens,$ii);
	  if($ii!=0 && $muda_pag==false){
	    $muda_pag = false;
            $this->objpdf->ln(0.3);
            $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
            $this->objpdf->ln(1.3);
	  }
	  
	  $codigo  = pg_result($this->recorddositens,$ii,"pc11_codigo");
	  $item  = pg_result($this->recorddositens,$ii,$this->item);
	  $quantitem = pg_result($this->recorddositens,$ii,$this->quantitem);
	  $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem);
	  $valoritem = db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),"f");
	  $valtot= pg_result($this->recorddositens,$ii,$this->svalortot);
	  $valimp= db_formatar($valtot,'f');
	  $prazo = pg_result($this->recorddositens,$ii,$this->sprazo);
	  $pgto  = pg_result($this->recorddositens,$ii,$this->spgto);
	  $resum = pg_result($this->recorddositens,$ii,$this->sresum);
	  $just  = pg_result($this->recorddositens,$ii,$this->sjust);
	  $unid  = pg_result($this->recorddositens,$ii,$this->sunidade);
	  $abrevunid  = pg_result($this->recorddositens,$ii,$this->sabrevunidade);
	  $servico    = pg_result($this->recorddositens,$ii,$this->sservico);
	  $quantunid  = pg_result($this->recorddositens,$ii,$this->squantunid);
	  $susaquant  = pg_result($this->recorddositens,$ii,$this->susaquant);
	  $scodpcmater= pg_result($this->recorddositens,$ii,$this->scodpcmater);
	  $selemento  = pg_result($this->recorddositens,$ii,$this->selemento);
	  $sdelemento = pg_result($this->recorddositens,$ii,$this->sdelemento);

	  $xtotal += $valtot;

	  if((isset($descricaoitem) && (trim($descricaoitem)=="" || $descricaoitem==null)) || !isset($descricaoitem)){
	    $descricaoitem=$resum;
	    unset($resum);
	  }

	  if(isset($scodpcmater) && trim($scodpcmater)!=""){
	    $scodpcmater = trim($scodpcmater)." - ";
	  }
	  if(isset($prazo) && trim($prazo)!=""){
	    $prazo = "\nPRAZO: ".trim($prazo);
	  }
	  if(isset($pgto) && trim($pgto)!=""){
	    $pgto = "\nCONDIÇÃO: ".trim($pgto);
	  }
	  if(isset($resum) && trim($resum)!=""){
	    $resum = "\nRESUMO: ".trim($resum);
	  }
	  if(isset($just) && trim($just)!=""){
	    $just = "\nJUSTIFICATIVA: ".trim($just);
	  }	  

	  if((isset($servico) && (trim($servico)=="f" || trim($servico)=="")) || !isset($servico)){
	    $unid = trim(substr($unid,0,10));
	    if($susaquant=="t"){
	      $unid .= " \n$quantunid UNIDADES\n";
	    }
	  }else{
	    $unid = "SERVIÇO";
	  }

//	  $descricaoitem .= " - ".$unid;
	    
          $distanciar = 0;
	  if((isset($prazo) && trim($prazo)=="") && (isset($pgto) && trim($pgto)=="") && (isset($resum) && trim($resum)=="") && (isset($just) && trim($just)=="")){
	    $distanciar = 4;
	  }else{
	  }

	  $this->objpdf->Setfont('Arial','B',7);
	  if(isset($selemento) && trim($selemento)!=""){
	    $this->objpdf->Row(array('','','',db_formatar($selemento,'elemento')." - ".$sdelemento,'',''),3,false,4);
	  }

	  $this->objpdf->Row(array($item,
	      		     $quantitem,
			     $unid,
	      		     $scodpcmater.$descricaoitem,
	      		     $valoritem,
	      		     $valimp),3,false,$distanciar);

	  $dist = 2.7;
	  $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  
//	  if(isset($unid) && $unid!=""){
//	    $this->objpdf->Row(array('','',$unid,'',''),3,false,$dist);
//	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
//	  }
	  
	  $this->objpdf->Setfont('Arial','',7);

	  $mostraunid = false;
	  if(isset($prazo) && $prazo!=""){
	    $this->objpdf->Row(array('','',$unid,$prazo,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	    
	  if(isset($pgto) && $pgto!=""){
	    $this->objpdf->Row(array('','','',$pgto,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	 
	  if(isset($resum) && $resum!="" && (isset($scodpcmater) && trim($scodpcmater)!="")){
	    $this->objpdf->Row(array('','','',$resum,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	    
	  if(isset($just) && $just!=""){
	    $this->objpdf->Row(array('','','',$just,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }

	  $this->objpdf->SetWidths(array(10,12,24,95,30,30));
	  $this->objpdf->SetWidths(array(10,12,24,23.7,23.7,23.7,23.7,0.2,30,30));
	  $arr_dotac = array();
	  for($i=0;$i<$this->linhasdasdotac;$i++){
	    db_fieldsmemory($this->recorddasdotac,$i);
	    if(pg_result($this->recorddasdotac,$i,$this->dcodigo)==$codigo && !in_array(pg_result($this->recorddasdotac,$i,$this->dcoddot),$arr_dotac)){
              if($item!=$itemant){
	        $pass = true;
		$this->objpdf->Setfont('Arial','B',7);
		$distc = 3.5;
		$distb = 3;
		$this->objpdf->SetAligns(array('C','C','C','C','C','C','C','C','R','R'));
		$this->objpdf->Row(array('','',"\n",'',''),3,false,$dist);
		$this->objpdf->Row(array('','','',"DOTAÇÃO","ANO","ELEMENTO","RESERVADO",'',''),3,false,$dist);
		$itemant = $item;
              }	      
	      
	      $this->objpdf->Setfont('Arial','',7);
	      $this->objpdf->SetAligns(array('C','C','C','C','C','C','C','C','R','R'));
	      $dquant   = pg_result($this->recorddasdotac,$i,$this->dquant);
	      $danousu  = pg_result($this->recorddasdotac,$i,$this->danousu);
	      $dcoddot  = pg_result($this->recorddasdotac,$i,$this->dcoddot);
	      $dvalor   = pg_result($this->recorddasdotac,$i,$this->dvalor);
	      $delemento= pg_result($this->recorddasdotac,$i,$this->delemento);
//	      $dreserva = pg_result($this->recorddasdotac,$i,$this->dreserva);
	      array_push($arr_dotac,$dcoddot);
	      if(isset($dcoddot) && trim($dcoddot)!=""){
		$result_orcreservasol = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,null,"o82_codres as codigodareserva,o80_valor as valorreserva","","o82_solicitem=$codigo and o80_coddot=$dcoddot"));
		$ddvalor = "NÃO";
		if($clorcreservasol->numrows>0){
		  db_fieldsmemory($result_orcreservasol,0);
		  global $valorreserva;
		  $valorreserva = db_formatar($valorreserva,"f");
		  if($valorreserva==$valimp){
		    $ddvalor = "TOTAL";
		  }else{		   
		    $ddvalor = $valorreserva;
		  }
		}
	        $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
		$this->objpdf->Row(array('',$dquant,'',$dcoddot,$danousu,$delemento,$ddvalor,'',db_formatar($dvalor/$dquant,"f"),db_formatar($dvalor,"f")),$distc,false,$distb);
              }
	    }else{
	      $pass = false;
	    }
	  }
	}
	$this->objpdf->Setfont('Arial','B',8);
	$maislin = 248;
        if($contapagina == 1){
	  $maislin = 211;
	}
        $this->objpdf->text(180,$xlin+$maislin+20,db_formatar($xtotal,'f'));
	if ($contapagina == 1){
  	  $this->objpdf->rect($xcol,    $xlin+224.7,142,10.8,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+224.7,30, 10.8,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+224.7,30, 10.8,2,'DF','34');
	  $this->objpdf->text($xcol+120,$xlin+230.7,'T O T A L');
	  if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
	    $this->objpdf->rect($xcol,$xlin+237,66,35,2,'DF','1234');
	    $this->objpdf->rect($xcol+68,$xlin+237,66,35,2,'DF','1234');
	    $this->objpdf->rect($xcol+136,$xlin+237,66,35,2,'DF','1234');
	    $this->objpdf->setfillcolor(0,0,0);

            $this->objpdf->setfillcolor(0,0,0);
	    $this->objpdf->text($xcol+5,$xlin+244,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	    $this->objpdf->text($xcol+20,$xlin+256,"AUTORIZO",0,4);
	    $this->objpdf->text($xcol+5,$xlin+268,substr($this->Sorgao,0,35));

            $this->objpdf->setfillcolor(0,0,0);
	    $this->objpdf->text($xcol+93,$xlin+256,"AUTORIZO",0,4);
	    if(strtoupper(trim($this->municpref)) != 'GUAIBA'){
	      $this->objpdf->text($xcol+83,$xlin+268,'DIV. DE ABASTECIMENTO',0,40);
	    }

            $this->objpdf->setfillcolor(0,0,0);
	    $this->objpdf->text($xcol+150,$xlin+256,"ORDENADOR DA DESPESA",0,4);
	  }else{
	    $this->objpdf->Setfont('Arial','B',7);
	    $this->objpdf->rect($xcol    ,$xlin+227,66,45,2,'DF','1234');
	    $this->objpdf->rect($xcol+ 68,$xlin+227,66,45,2,'DF','1234');
	    $this->objpdf->rect($xcol+136,$xlin+227,66,45,2,'DF','1234');

	    $this->objpdf->SetXY($xcol+08,$xlin+238);
	    $this->objpdf->multicell(66,4,"SOLICITANTE",0,"C");
	    $this->objpdf->SetXY($xcol+08,$xlin+243.5);
	    $this->objpdf->multicell(66,0.5,"_________________________________",0,"C");

	    $this->objpdf->SetXY($xcol+08,$xlin+249.5);
            //   SECRETÁRIO(A) DA SECRETARIA QUE SOLICITOU   //
	    $this->objpdf->multicell(66,4,"SECRETÁRIO",0,"C");
	    $this->objpdf->text($xcol+10,$xlin+254.5,substr($this->Sorgao,0,30),0,4);
	    ///////////////////////////////////////////////////
	    $this->objpdf->text($xcol+10,$xlin+260,"_________________________________",0,4);
//	    $this->objpdf->multicell(66,0.5,"_________________________________",0,"C");

	    $this->objpdf->text($xcol+10,$xlin+270,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');


//	    $this->objpdf->SetXY($xcol+68,$xlin+243.5);
//	    $this->objpdf->multicell(66,0.5,"_________________________________",0,"C");
	    $this->objpdf->SetXY($xcol+68,$xlin+238);
	    $this->objpdf->multicell(66,4,"CONTABILIDADE",0,"C");
	    $this->objpdf->SetXY($xcol+68,$xlin+249);
	    $this->objpdf->multicell(66,4,"HÁ RECURSOS FINANCEIROS",0,"C");



            $tamanho = strlen($this->secfaz);
	    $posicao = strpos($this->secfaz,"\n");
            $secretaria = $this->secfaz;
	    $deque   = "";
	    if($posicao!="" && $posicao!=0){
	      $secretaria = substr($this->secfaz,0,$posicao);
	      $deque = substr($this->secfaz,$posicao,$tamanho);
	    }
	    
	    $this->objpdf->text($xcol+85,$xlin+257,$secretaria,0,4);
	    $this->objpdf->text($xcol+84.5,$xlin+259.5,$deque,0,4);

	    $this->objpdf->text($xcol+92,$xlin+264,"CONFERIDO",0,4);
	    $this->objpdf->text($xcol+83.5,$xlin+270,"________/________/________",0,4);

	    $this->objpdf->SetXY($xcol+136,$xlin+240);
	    $this->objpdf->multicell(66,4,$this->nompre,0,"C");
	    $this->objpdf->SetXY($xcol+136,$xlin+252);
	    $this->objpdf->multicell(66,4,"AUTORIZO",0,"C");
	    $this->objpdf->text($xcol+150.5,$xlin+270,"________/________/________",0,4);

//	    $this->objpdf->text($xcol+14,$xlin+247,"________/________/________",0,4);
	    /*
	    $this->objpdf->text($xcol+20,$xlin+241,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	    $this->objpdf->rect($xcol,$xlin+237,100,35,2,'DF','1234');
	    $this->objpdf->rect($xcol+102,$xlin+237,100,35,2,'DF','1234');
	    
	    $this->objpdf->text($xcol+40,$xlin+256,"AUTORIZO",0,4);
	    $this->objpdf->text($xcol+20,$xlin+264,substr($this->Srespdepart,0,35));
	    $this->objpdf->text($xcol+20,$xlin+268,substr($this->Sdepart,0,35));
	    $this->objpdf->text($xcol+145,$xlin+256,"VISTO",0,4);
	    */
	  }
        }else{
	  $this->objpdf->rect($xcol,    $xlin+262,142, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+262,30, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+262,30, 10,2,'DF','34');
	  $this->objpdf->text($xcol+120,$xlin+268,'T O T A L');
	}	


    }else if ( $this->modelo == 111 ) {
        global $contapagina;
	$contapagina=1;
        if(!in_array("cl_orcreservasol",get_declared_classes())){
          include("classes/db_orcreservasol_classe.php"); 
	}
	$clorcreservasol = new cl_orcreservasol;
////////// MODELO 111  -  SOLICITAÇÃO DE COMPRA
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;

	// Imprime caixa externa
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

        // Imprime o cabeçalho com dados sobre a prefeitura
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,$xlin-13,'SOLICITAÇÃO DE COMPRA N'.CHR(176));
	$this->objpdf->text(185,$xlin-13,db_formatar($this->Snumero,'s','0',6,'e'));
        $this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->text(130,$xlin-9,'ORGAO');
	$this->objpdf->text(142,$xlin-9,': '.substr($this->Sorgao,0,35));
	$this->objpdf->text(130,$xlin-5,'UNIDADE');
	$this->objpdf->text(142,$xlin-5,': '.substr($this->Sunidade,0,35));
        $this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);

 	$this->objpdf->Setfont('Arial','B',8);
	// caixa para frases
	$this->objpdf->rect($xcol,$xlin+3,$xcol+198,9,2,'DF','1234');
	$this->objpdf->SetXY(4,$xlin+4);
	$this->objpdf->MultiCell(202,4,'QUANDO NECESSÁRIO FRETE, O MESMO CORRERÁ POR CONTA DO FORNECEDOR',0,"C",0);
	$this->objpdf->SetXY(4,$xlin+8);
	$this->objpdf->MultiCell(202,4,'TODO FRETE DEVERÁ SER PAGO PELA EMPRESA REMETENTE - O MATERIAL DEVERÁ SER DE PRIMEIRA QUALIDADE',0,"C",0);
	$this->objpdf->Setfont('Arial','',8);

        // Caixa com dados da solicitação
	$this->objpdf->rect($xcol,$xlin+13,$xcol+198,10,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+15,'Dados do Solicitação');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+  2,$xlin+18,'Departamento');
	$this->objpdf->text($xcol+109,$xlin+18,'Tipo');
	$this->objpdf->text($xcol+  2,$xlin+22,'Data');
	$this->objpdf->text($xcol+109,$xlin+22,'Val. Aprox.');

	// Imprime dados da solicitação
	$this->objpdf->text($xcol+ 23,$xlin+18,':  '.$this->Sdepart);
	if(isset($this->Sdata) && trim($this->Sdata)!=""){
	  $this->Sdata = db_formatar($this->Sdata,'d');
	}
	if(isset($this->Svalor) && trim($this->Svalor)!=""){
          $this->Svalor = db_formatar($this->Svalor,'f');
	}
	$this->objpdf->text($xcol+125,$xlin+18,':  '.$this->Stipcom);
	$this->objpdf->text($xcol+ 23,$xlin+22,':  '.$this->Sdata);
	$this->objpdf->text($xcol+125,$xlin+22,':  R$ '.$this->Svalor);

        $this->objpdf->text($xcol+  2,$xlin+27,'Resumo');
	$this->objpdf->setxy($xcol+22,$xlin+24);
	$this->objpdf->cell(3,4,':  ',0,0,"L",0);
	$this->objpdf->setxy($xcol+24.5,$xlin+24);
	$posini = $this->objpdf->gety();
	$this->objpdf->multicell(175,4,trim(AddSlashes($this->Sresumo)),0,"j");
	$setaut = $this->objpdf->gety();
	$this->objpdf->rect($xcol,$xlin+24,$xcol+198,$setaut-$posini,2,'DF','1234');

        $getdoy = 32;
	$contafornec = 0;
	if($this->linhasdosfornec>0){
	  $x = $this->muda_pag($pagina,$xlin,$xcol,"true",$contapagina);
	  for($i=0;$i<$this->linhasdosfornec;$i++){
	    $contafornec += 4;
	  }

	  $this->objpdf->Setfont('Arial','B',8);
	  // Caixa de texto para labels 
	  $this->objpdf->rect($xcol    ,$setaut+0.8,202,6,2,'DF','12');
	  $this->objpdf->text($xcol+   4,$setaut+4.2,'FORNECEDORES SUGERIDOS ');

	  $this->objpdf->rect($xcol    ,$setaut+6.8,15,6,2,'DF','12');
	  $this->objpdf->rect($xcol+15 ,$setaut+6.8,64,6,2,'DF','12');
	  $this->objpdf->rect($xcol+79 ,$setaut+6.8,63,6,2,'DF','12');
	  $this->objpdf->rect($xcol+142,$setaut+6.8,40,6,2,'DF','12');
	  $this->objpdf->rect($xcol+182,$setaut+6.8,20,6,2,'DF','12');
	  
	  $this->objpdf->rect($xcol    ,$setaut+12.8,15,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+15 ,$setaut+12.8,64,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+79 ,$setaut+12.8,63,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$setaut+12.8,40,$contafornec+1,2,'DF','34');
	  $this->objpdf->rect($xcol+182,$setaut+12.8,20,$contafornec+1,2,'DF','34');
	  $this->objpdf->sety($xlin+66);

	  // Label das colunas
	  $this->objpdf->text($xcol+   4,$setaut+11,'CGM');
	  $this->objpdf->text($xcol+30.5,$setaut+11,'NOME/RAZÃO SOCIAL');
	  $this->objpdf->text($xcol+ 103,$setaut+11,'ENDEREÇO');
	  $this->objpdf->text($xcol+ 155,$setaut+11,'MUNICÍPIO');
	  $this->objpdf->text($xcol+184.5,$setaut+11,'TELEFONE');

	  // Seta altura nova para impressão dos dados
	  $this->objpdf->sety($setaut+13.8);
	  $this->objpdf->setx($xcol);
	  $this->objpdf->setleftmargin(4);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->SetAligns(array('C','L','L','L','C'));
	  $this->objpdf->SetWidths(array(15,64,63,40,20));
	  for($i=0;$i<$this->linhasdosfornec;$i++){
	    db_fieldsmemory($this->recorddosfornec,$i);
	    $cgmforn   = trim(pg_result($this->recorddosfornec,$i,$this->cgmforn));
	    $nomeforn  = trim(pg_result($this->recorddosfornec,$i,$this->nomeforn));
	    $enderforn = trim(pg_result($this->recorddosfornec,$i,$this->enderforn));
	    $numforn   = trim(pg_result($this->recorddosfornec,$i,$this->numforn));
	    $municforn = trim(pg_result($this->recorddosfornec,$i,$this->municforn));
	    $foneforn  = trim(pg_result($this->recorddosfornec,$i,$this->foneforn));
	    $this->objpdf->Row(array($cgmforn,$nomeforn,$enderforn.", ".$numforn,$municforn,$foneforn),4,false,4);
	  }
	  $getdoy = $this->objpdf->gety();
	  $getdoy+= 0.8;
	  $getdoy = $getdoy-$xlin;
	  $contafornec+= 8;
	}else{
	  $getdoy += 4.8;
	}
        // Caixas dos label's
	$this->objpdf->rect($xcol    ,$xlin+$getdoy,15,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 15,$xlin+$getdoy,20,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 35,$xlin+$getdoy,107,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+$getdoy,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+$getdoy,30,6,2,'DF','12');

        // Caixa dos itens
	$this->objpdf->rect($xcol,    $xlin+$getdoy+6,15,193-$contafornec,2,'DF','34');
        // Caixa da quantidade
	$this->objpdf->rect($xcol+ 15,$xlin+$getdoy+6,20,193-$contafornec,2,'DF','34');
        // Caixa dos materiais ou serviços
	$this->objpdf->rect($xcol+ 35,$xlin+$getdoy+6,107,193-$contafornec,2,'DF','34');
        // Caixa dos valores unitários
	$this->objpdf->rect($xcol+142,$xlin+$getdoy+6,30,193-$contafornec,2,'DF','');
        // Caixa dos valores totais dos itens
	$this->objpdf->rect($xcol+172,$xlin+$getdoy+6,30,193-$contafornec,2,'DF','34');

   	$this->objpdf->sety($xlin+28);
	
        // Label das colunas
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+   4,$xlin+$getdoy+4,'ITEM');
	$this->objpdf->text($xcol+15.5,$xlin+$getdoy+4,'QUANTIDADE');
	$this->objpdf->text($xcol+  70,$xlin+$getdoy+4,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+$getdoy+4,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 176,$xlin+$getdoy+4,'VALOR TOTAL');
        $maiscol = 0;

	$this->objpdf->setleftmargin(8);
	$this->objpdf->sety($xlin+$getdoy+7);

	$xtotal = 0;
	$muda_pag = false;
	$index = 0;

	for($ii = 0;$ii < $this->linhasdositens ;$ii++){
	  $this->objpdf->SetWidths(array(10,22,22,95,30,30));
	  $this->objpdf->SetAligns(array('C','C','C','J','R','R'));
	  $pagina = $this->objpdf->PageNo();
	  db_fieldsmemory($this->recorddositens,$ii);
	  if($ii!=0 && $muda_pag==false){
	    $muda_pag = false;
            $this->objpdf->ln(0.3);
            $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
            $this->objpdf->ln(1.3);
	  }
	  
	  $codigo  = pg_result($this->recorddositens,$ii,"pc11_codigo");
	  $item  = pg_result($this->recorddositens,$ii,$this->item);
	  $quantitem = pg_result($this->recorddositens,$ii,$this->quantitem);
	  $descricaoitem = pg_result($this->recorddositens,$ii,$this->descricaoitem);
	  $valoritem = db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),"f");
	  $valtot= pg_result($this->recorddositens,$ii,$this->svalortot);
	  $valimp= db_formatar($valtot,'f');
	  $prazo = pg_result($this->recorddositens,$ii,$this->sprazo);
	  $pgto  = pg_result($this->recorddositens,$ii,$this->spgto);
	  $resum = pg_result($this->recorddositens,$ii,$this->sresum);
	  $just  = pg_result($this->recorddositens,$ii,$this->sjust);
	  $unid  = pg_result($this->recorddositens,$ii,$this->sunidade);
	  $servico    = pg_result($this->recorddositens,$ii,$this->sservico);
	  $quantunid  = pg_result($this->recorddositens,$ii,$this->squantunid);
	  $susaquant  = pg_result($this->recorddositens,$ii,$this->susaquant);
	  $scodpcmater= pg_result($this->recorddositens,$ii,$this->scodpcmater);

	  $xtotal += $valtot;

	  if((isset($descricaoitem) && (trim($descricaoitem)=="" || $descricaoitem==null)) || !isset($descricaoitem)){
	    $descricaoitem=$resum;
	  }

	  if(isset($scodpcmater) && trim($scodpcmater)!=""){
	    $scodpcmater = trim($scodpcmater)." - ";
	  }
	  if(isset($prazo) && trim($prazo)!=""){
	    $prazo = "\nPRAZO: ".trim($prazo);
	  }
	  if(isset($pgto) && trim($pgto)!=""){
	    $pgto = "\nCONDIÇÃO: ".trim($pgto);
	  }
	  if(isset($resum) && trim($resum)!=""){
	    $resum = "\nRESUMO: ".trim($resum);
	  }
	  if(isset($just) && trim($just)!=""){
	    $just = "\nJUSTIFICATIVA: ".trim($just);
	  }	  

          $barran = "";
	  if(strlen($scodpcmater.$descricaoitem)>53){
	    $barran = "\n";
	  }
	  if((isset($servico) && (trim($servico)=="f" || trim($servico)=="")) || !isset($servico)){
	    $unid = $barran."\n".trim($unid);
	    if($susaquant=="t"){
	      $unid .= " ($quantunid UNIDADES)\n";
	    }
	  }else{
	    $unid = $barran."\nSERVIÇO";
	  }

	  $descricaoitem .= " - ".$unid;
	    
          $distanciar = 0;
	  if((isset($prazo) && trim($prazo)=="") && (isset($pgto) && trim($pgto)=="") && (isset($resum) && trim($resum)=="") && (isset($just) && trim($just)=="")){
	    $distanciar = 4;
	  }

	  $this->objpdf->Setfont('Arial','B',9);
	  $this->objpdf->Row(array($item,
	      		     $quantitem,
	      		     $scodpcmater.$descricaoitem,
	      		     $valoritem,
	      		     $valimp),3,false,$distanciar);

	  $dist = 2.7;
	  $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  
	  if(isset($unid) && $unid!=""){
	    $this->objpdf->Row(array('','',$unid,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	  
	  $this->objpdf->Setfont('Arial','',7);

	  if(isset($prazo) && $prazo!=""){
	    $this->objpdf->Row(array('','',$prazo,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	    
	  if(isset($pgto) && $pgto!=""){
	    $this->objpdf->Row(array('','',$pgto,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	    
	  if(isset($resum) && $resum!=""){
	    $this->objpdf->Row(array('','',$resum,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }
	    
	  if(isset($just) && $just!=""){
	    $this->objpdf->Row(array('','',$just,'',''),3,false,$dist);
	    $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
	  }

	  $this->objpdf->SetWidths(array(10,22,26,26,26,26,'1',30,30));
	  $pass = false;
	  $arr_dotac = array();
	  for($i=0;$i<$this->linhasdasdotac;$i++){
	    db_fieldsmemory($this->recorddasdotac,$i);
	    if(pg_result($this->recorddasdotac,$i,$this->dcodigo)==$codigo && !in_array(pg_result($this->recorddasdotac,$i,$this->dcoddot),$arr_dotac)){
              if($pass==false){
	        $pass = true;
		$this->objpdf->Setfont('Arial','B',7);
		$distc = 3.5;
		$distb = 3;
		$this->objpdf->SetAligns(array('C','C','C','C','C','C','C','R','R'));
		$this->objpdf->Row(array('','',"\n",'',''),3,false,$dist);
		$this->objpdf->Row(array('','',"DOTAÇÃO","ANO","ELEMENTO","RESERVADO",'',''),3,false,$dist);
	      }	      
	      $this->objpdf->Setfont('Arial','',7);
	      $this->objpdf->SetAligns(array('C','C','C','C','C','C','C','R','R'));
	      $dquant   = pg_result($this->recorddasdotac,$i,$this->dquant);
	      $danousu  = pg_result($this->recorddasdotac,$i,$this->danousu);
	      $dcoddot  = pg_result($this->recorddasdotac,$i,$this->dcoddot);
	      $dvalor   = pg_result($this->recorddasdotac,$i,$this->dvalor);
	      $delemento= pg_result($this->recorddasdotac,$i,$this->delemento);
//	      $dreserva = pg_result($this->recorddasdotac,$i,$this->dreserva);
	      array_push($arr_dotac,$dcoddot);
	      if(isset($dcoddot) && trim($dcoddot)!=""){
		$result_orcreservasol = $clorcreservasol->sql_record($clorcreservasol->sql_query_orcreserva(null,null,"o82_codres as codigodareserva","","o82_solicitem=$codigo and o80_coddot=$dcoddot"));
		$ddvalor = "NÃO";
		if($clorcreservasol->numrows>0){
		  $ddvalor = "SIM";
		}
	        $x = $this->muda_pag($pagina,$xlin,$xcol,"false",$contapagina);
		$this->objpdf->Row(array('',$dquant,$dcoddot,$danousu,$delemento,$ddvalor,'',db_formatar($dvalor/$dquant,"f"),db_formatar($dvalor,"f")),$distc,false,$distb);
              }
	    }else{
	      $pass = false;
	    }
	  }
	}
	$this->objpdf->Setfont('Arial','B',8);
	$maislin = 248;
        if($contapagina == 1){
	  $maislin = 211;
	}
        $this->objpdf->text(180,$xlin+$maislin+20,db_formatar($xtotal,'f'));
	if ($contapagina == 1){
	  $this->objpdf->rect($xcol,    $xlin+224.7,142,10.8,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+224.7,30, 10.8,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+224.7,30, 10.8,2,'DF','34');
	  $this->objpdf->text($xcol+120,$xlin+230.7,'T O T A L');
	  
	  $this->objpdf->rect($xcol,$xlin+237,66,35,2,'DF','1234');
	  $this->objpdf->rect($xcol+68,$xlin+237,66,35,2,'DF','1234');
	  $this->objpdf->rect($xcol+136,$xlin+237,66,35,2,'DF','1234');
	  $this->objpdf->setfillcolor(0,0,0);

          $this->objpdf->setfillcolor(0,0,0);
	  $this->objpdf->text($xcol+5,$xlin+244,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	  $this->objpdf->text($xcol+20,$xlin+256,"AUTORIZO",0,4);
	  $this->objpdf->text($xcol+5,$xlin+268,substr($this->Sorgao,0,35));

          $this->objpdf->setfillcolor(0,0,0);
	  $this->objpdf->text($xcol+93,$xlin+256,"AUTORIZO",0,4);
	  if(strtoupper(trim($this->municpref)) != 'GUAIBA'){
	    $this->objpdf->text($xcol+83,$xlin+268,'DIV. DE ABASTECIMENTO',0,40);
	  }

          $this->objpdf->setfillcolor(0,0,0);
	  $this->objpdf->text($xcol+150,$xlin+256,"ORDENADOR DA DESPESA",0,4);
        }else{
	  $this->objpdf->rect($xcol,    $xlin+262,142, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+142,$xlin+262,30, 10,2,'DF','34');
	  $this->objpdf->rect($xcol+172,$xlin+262,30, 10,2,'DF','34');
	  $this->objpdf->text($xcol+120,$xlin+268,'T O T A L');
	}	
    }else if ( $this->modelo == 12 ) {     
      
////////// MODELO 12  -  ANULAÇÃO DE EMPENHO
	
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
	$this->objpdf->text(126,$xlin-13,'NOTA DE ANULAÇÃO N'.CHR(176).': ');
	$this->objpdf->text(175,$xlin-13,db_formatar($this->notaanulacao,'s','0',6,'e'));
	$this->objpdf->text(134,$xlin-8,'DATA DE EMISSÃO : ');
	$this->objpdf->text(175,$xlin-8,$this->emissao);

	$this->objpdf->text(134,$xlin-3,'        EMPENHO : ');
	$this->objpdf->text(175,$xlin-3,trim($this->codemp)."/".$this->anousu);



        $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin-8,$this->municpref);
	$this->objpdf->text(40,$xlin-5,$this->telefpref);
	$this->objpdf->text(40,$xlin-2,$this->emailpref);

        /// retangulo dos dados da dotação
	$this->objpdf->rect($xcol,$xlin+2,$xcol+100,50,2,'DF','1234');
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
	
	$this->objpdf->text($xcol+17,$xlin+30,':  '.db_formatar($this->sintetico,'elemento'));
	$this->objpdf->setxy($xcol+18,$xlin+31);
	$this->objpdf->multicell(90,3,$this->descr_sintetico,0,"L");
	
	$this->objpdf->text($xcol+17,$xlin+42,':  '.$this->recurso.' - '.$this->descr_recurso);
	
	$this->objpdf->text($xcol+17,$xlin+48,':  '.$this->descr_licitacao);
	
	
        //// retangulo dos dados do credor
	$this->objpdf->rect($xcol+106,$xlin+2,96,18,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+108,$xlin+4,'Dados do Credor:');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+107,$xlin+7,'Numcgm');
	$this->objpdf->text($xcol+107,$xlin+11,'Nome');
	$this->objpdf->text($xcol+107,$xlin+15,'Endereço');
	$this->objpdf->text($xcol+107,$xlin+19,'Município');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+124,$xlin+7,': '.$this->numcgm);
	$this->objpdf->text($xcol+124,$xlin+11,': '.$this->nome);
	$this->objpdf->text($xcol+124,$xlin+15,': '.$this->ender.'  '.$this->compl);
	$this->objpdf->text($xcol+124,$xlin+19,': '.$this->munic.'-'.$this->uf.'    CEP : '.$this->cep);
	
	///// retangulo dos valores
	$this->objpdf->rect($xcol+106,$xlin+21.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+21.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+106,$xlin+32.0,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+32.0,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+106,$xlin+42.5,47,9,2,'DF','1234');
	$this->objpdf->rect($xcol+155,$xlin+42.5,47,9,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+157,$xlin+23.5,'Valor Empenhado');
	$this->objpdf->text($xcol+108,$xlin+34.0,'Valor Orçado');
	$this->objpdf->text($xcol+157,$xlin+34.0,'Saldo Anterior');
	$this->objpdf->text($xcol+108,$xlin+44.5,'Valor Anulado');
	$this->objpdf->text($xcol+157,$xlin+44.5,'Saldo Atual');
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+108,$xlin+27,'SEQ. EMP. N'.chr(176).' '.db_formatar($this->numemp,'s','0',6,'e'));
//	$this->objpdf->text($xcol+108,$xlin+26.5,$this->texto);
	$this->objpdf->text($xcol+180,$xlin+27.5,db_formatar($this->empenhado,'f'));
	$this->objpdf->text($xcol+130,$xlin+38.0,db_formatar($this->orcado,'f'));
	$this->objpdf->text($xcol+180,$xlin+38.0,db_formatar($this->saldo_ant,'f'));
	$this->objpdf->text($xcol+130,$xlin+47.5,db_formatar($this->anulado,'f'));
	$this->objpdf->text($xcol+180,$xlin+47.5,db_formatar($this->saldo_ant + $this->anulado,'f'));
	
        /// retangulo do corpo do empenho 
	$this->objpdf->rect($xcol,$xlin+60,15,130,2,'DF','');
	$this->objpdf->rect($xcol+15,$xlin+60,137,130,2,'DF','');
	$this->objpdf->rect($xcol+152,$xlin+60,25,163,2,'DF','');
	$this->objpdf->rect($xcol+177,$xlin+60,25,163,2,'DF','');
	$this->objpdf->rect($xcol,$xlin+190,152,33,2,'DF','');
	
	//// retangulos do titulo do corpo do empenho
	$this->objpdf->Setfont('Arial','B',7);
	$this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	$this->objpdf->rect($xcol+15,$xlin+54,137,6,2,'DF','12');
	$this->objpdf->rect($xcol+152,$xlin+54,25,6,2,'DF','12');
	$this->objpdf->rect($xcol+177,$xlin+54,25,6,2,'DF','12');

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
        $ele = 0;
	$xtotal = 0;
	for($ii = 0;$ii < $this->linhasdositens ;$ii++) {
	  db_fieldsmemory($this->recorddositens,$ii);
	  $this->objpdf->Setfont('Arial','B',7);
          $this->objpdf->Row(array('',
	  			   db_formatar(pg_result($this->recorddositens,$ii,$this->analitico),'elemento').' - '.pg_result($this->recorddositens,$ii,$this->descr_analitico),
				   '',
				   db_formatar(pg_result($this->recorddositens,$ii,$this->valoritem),'f')),3,false,4);
	  $xtotal += pg_result($this->recorddositens,$ii,$this->valoritem);
 /////// troca de pagina
	    
	}

        if ($pagina == 1){
           $this->objpdf->rect($xcol,$xlin+223,152,6,2,'DF','34');
           $this->objpdf->rect($xcol+152,$xlin+223,25,6,2,'DF','34');
           $this->objpdf->rect($xcol+177,$xlin+223,25,6,2,'DF','34');
	   
//           $this->objpdf->rect($xcol,$xlin+197,60,47,2,'DF','34');
//           $this->objpdf->rect($xcol+60,$xlin+197,60,47,2,'DF','34');
//           $this->objpdf->rect($xcol+120,$xlin+197,82,47,2,'DF','34');
//           $this->objpdf->rect($xcol+120,$xlin+216,32,28,2,'DF','4');
	   
	   
//	   $this->objpdf->setfillcolor(0,0,0);
	   $this->objpdf->SetFont('Arial','',7);
	   $this->objpdf->text($xcol+2,$xlin+227,'DESTINO : ',0,1,'L',0);
	   $this->objpdf->text($xcol+30,$xlin+227,$this->destino,0,1,'L',0);
	  
	   $this->objpdf->setxy($xcol+1,$xlin+195);
	   $this->objpdf->text($xcol+2,$xlin+194,'MOTIVO : ',0,1,'L',0);
	   $this->objpdf->multicell(147,3.5,$this->resumo);
	   
	   $this->objpdf->text($xcol+159,$xlin+227,'T O T A L',0,1,'L',0);
	   $this->objpdf->setxy($xcol+185,$xlin+222);
	   $this->objpdf->cell(30,10,db_formatar($this->empenhado - $xtotal,'f'),0,0,'f');
/*
           $this->objpdf->rect($xcol,$xlin+191,60,6,2,'DF','12');
           $this->objpdf->rect($xcol+60,$xlin+191,60,6,2,'DF','12');
           $this->objpdf->rect($xcol+120,$xlin+191,82,6,2,'DF','12');
	   $this->objpdf->text($xcol+15,$xlin+195,'CONTADORIA GERAL');
	   $this->objpdf->text($xcol+82,$xlin+195,'PAGUE-SE');
	   $this->objpdf->text($xcol+150,$xlin+195,'TESOURARIA');
	   
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
	  
           $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
	   
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
*/
           $xlin = 169;
        }
    }else if ( $this->modelo == 13 ) {
      
////////// MODELO 13  -  SOLICITAÇÃO DE ORÇAMENTO
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
        $this->objpdf->text(130,$xlin-13,"ORÇAMENTO N".CHR(176));
	$this->objpdf->text(185,$xlin-13,db_formatar($this->orccodigo,'s','0',6,'e'));	
	$this->objpdf->text(130,$xlin-9,$this->labdados.CHR(176));
	$this->objpdf->text(185,$xlin-9,db_formatar($this->Snumero,'s','0',6,'e'));	
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);
	$this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));

        // Caixa com dados do orçamento e solicitação 
	$this->objpdf->rect($xcol,$xlin+3,$xcol+198,27,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+5,'Dados do Orçamento/'.$this->labtitulo);
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+  2,$xlin+ 8,'Orçamento');
	$this->objpdf->text($xcol+109,$xlin+ 8,'Data Limite');
	$this->objpdf->text($xcol+150,$xlin+ 8,'Hora Limite');
	$this->objpdf->text($xcol+  2,$xlin+13,$this->labtitulo);
	$this->objpdf->text($xcol+  2,$xlin+21,'Departamento');
	$this->objpdf->text($xcol+109,$xlin+17,$this->labtipo);
	$this->objpdf->text($xcol+  2,$xlin+17,'Data');
	$this->objpdf->text($xcol+  2,$xlin+25,'Resumo');
	$this->objpdf->Setfont('Arial','',8);
	
        // Imprime dados do orçamento e solicitação
	$this->objpdf->text($xcol+ 23,$xlin+ 8,':  '.$this->orccodigo);
	$this->objpdf->text($xcol+125,$xlin+ 8,':  '.$this->orcdtlim);
	$this->objpdf->text($xcol+166,$xlin+ 8,':  '.$this->orchrlim);
	$this->objpdf->text($xcol+ 23,$xlin+ 13,':  '.$this->Snumero);
	$this->objpdf->text($xcol+ 23,$xlin+ 21,':  '.$this->Sdepart);
	if(isset($this->Sdata) && trim($this->Sdata)!=""){
	  $this->Sdata = db_formatar($this->Sdata,'d');
	}
	if(trim($this->labtipo)!=""){
	  $this->objpdf->text($xcol+125,$xlin+17,':  '.$this->Stipcom);
	}
	$this->objpdf->text($xcol+ 23,$xlin+17,':  '.$this->Sdata);
	$this->objpdf->setxy($xcol+22,$xlin+22);
	$this->objpdf->cell(3,4,':  ',0,0,"L",0);
	$this->objpdf->setxy($xcol+24.5,$xlin+22);
	$Sresumo = $this->Sresumo;
	$Sresumo = str_replace("\n",". ",$Sresumo);
	$Sresumo = str_replace("\r","",$Sresumo);

	$this->objpdf->multicell(175,4,$Sresumo,0,"J");

        // Caixa com dados dos fornecedores
	$this->objpdf->rect($xcol,$xlin+32,$xcol+198,16,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+34,'Dados do Fornecedor');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
	$this->objpdf->text($xcol+150,$xlin+38,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+  2,$xlin+38,'Nome');
	$this->objpdf->text($xcol+  2,$xlin+42,'Endereço');
	$this->objpdf->text($xcol+102,$xlin+42,'Complemento');
	$this->objpdf->text($xcol+  2,$xlin+46,'Município');
	$this->objpdf->text($xcol+115,$xlin+46,'CEP');
	$this->objpdf->text($xcol+150,$xlin+46,'Telefone');
	$this->objpdf->Setfont('Arial','',8);

        // Imprime dados dos fornecedores
	$this->objpdf->text($xcol+ 18,$xlin+ 38,':  '.$this->nome);
	$this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
	$this->objpdf->text($xcol+163,$xlin+38,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')));
	$this->objpdf->text($xcol+ 18,$xlin+ 42,':  '.$this->ender);
	$this->objpdf->text($xcol+122,$xlin+42,':  '.$this->compl);
	$this->objpdf->text($xcol+ 18,$xlin+ 46,':  '.$this->munic.'-'.$this->uf);
	$this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
	$this->objpdf->text($xcol+163,$xlin+46,':  '.$this->telefone);

        $getdoy = 50;

	$contadepart = 0;
	$alturaini = 216;
	if($this->linhasdosdepart>0){
	  for($i=0;$i<$this->linhasdosdepart;$i++){
	    $contadepart += 4;
	  }
          $setaut = $xlin + $getdoy;
	  $alturaini -= ($contadepart+15);

	  $this->objpdf->Setfont('Arial','B',8);
	  // Caixa de texto para labels 
	  $this->objpdf->rect($xcol    ,$setaut,202,6,2,'DF','12');
	  $this->objpdf->text($xcol+  4,$setaut+4,'DEPARTAMENTOS DAS SOLICITAÇÕES');

	  $this->objpdf->rect($xcol    ,$setaut+6,30,6,2,'DF','12');
	  $this->objpdf->rect($xcol+30 ,$setaut+6,30,6,2,'DF','12');
	  $this->objpdf->rect($xcol+60 ,$setaut+6,142,6,2,'DF','12');
	  
	  $this->objpdf->rect($xcol    ,$setaut+12,30,$contadepart+1,2,'DF','34');
	  $this->objpdf->rect($xcol+30 ,$setaut+12,30,$contadepart+1,2,'DF','34');
	  $this->objpdf->rect($xcol+60 ,$setaut+12,142,$contadepart+1,2,'DF','34');
	  $this->objpdf->sety($xlin+66);

	  // Label das colunas
	  $this->objpdf->text($xcol+   6,$setaut+11,'SOLICITAÇÃO');
	  $this->objpdf->text($xcol+  39,$setaut+11,'CÓDIGO');
	  $this->objpdf->text($xcol+ 125,$setaut+11,'DESCRIÇÃO');

	  // Seta altura nova para impressão dos dados
	  $this->objpdf->sety($setaut+13);
	  $this->objpdf->setx($xcol);
	  $this->objpdf->setleftmargin(4);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->SetAligns(array('C','C','L'));
	  $this->objpdf->SetWidths(array(30,30,142));
	  for($i=0;$i<$this->linhasdosdepart;$i++){
	    db_fieldsmemory($this->recorddosdepart,$i);
	    $solicita  = trim(pg_result($this->recorddosdepart,$i,$this->Snumdepart));
	    $codigodep = trim(pg_result($this->recorddosdepart,$i,$this->Scoddepto));
	    $descrdep  = trim(pg_result($this->recorddosdepart,$i,$this->Sdescrdepto));
	    $this->objpdf->Row(array($solicita,$codigodep,$descrdep),4,false,4);
	  }
	  $getdoy = $this->objpdf->gety()+2-$xlin;
	}


        // Caixa com Labels item, quantidade, descrição, valor 
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->rect($xcol    ,$xlin+$getdoy,14,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 14,$xlin+$getdoy,12,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 26,$xlin+$getdoy,22,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 48,$xlin+$getdoy,98,6,2,'DF','12');
	$this->objpdf->rect($xcol+146,$xlin+$getdoy,28,6,2,'DF','12');
	$this->objpdf->rect($xcol+174,$xlin+$getdoy,28,6,2,'DF','12');

	$menos = 16.9;
	if($this->linhasdosfornec==0){
	  $menos = 11;
	}
	if(isset($tiramenos)){
	  $menos = $menos+$tiramenos;
	  if($menos<0){
	    $menos = -$menos;
	  }
	}
        // Caixa dos itens
	$this->objpdf->rect($xcol,    $xlin+$getdoy+6,14,$alturaini,2,'DF','34');
        // Caixa da quantidade
	$this->objpdf->rect($xcol+ 14,$xlin+$getdoy+6,12,$alturaini,2,'DF','34');
	
	$this->objpdf->rect($xcol+ 26,$xlin+$getdoy+6,22,$alturaini,2,'DF','34');
        // Caixa dos materiais ou serviços
	$this->objpdf->rect($xcol+ 48,$xlin+$getdoy+6,98,$alturaini,2,'DF','34');
        // Caixa dos valores unitários
	$this->objpdf->rect($xcol+146,$xlin+$getdoy+6,28,$alturaini,2,'DF','');
        // Caixa dos valores totais dos itens
	$this->objpdf->rect($xcol+174,$xlin+$getdoy+6,28,$alturaini,2,'DF','34');

   	$this->objpdf->sety($xlin+48);
	
	$alt = 4;
        // Label das colunas
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+   4,$xlin+$getdoy+4,'ITEM');
	$this->objpdf->text($xcol+  15,$xlin+$getdoy+4,'QUANT');
	$this->objpdf->text($xcol+  34,$xlin+$getdoy+4,'REF');
	$this->objpdf->text($xcol+  74,$xlin+$getdoy+4,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 148,$xlin+$getdoy+4,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 179,$xlin+$getdoy+4,'VALOR TOTAL');
        $maiscol = 0;
	$this->objpdf->SetWidths(array(14,12,22,95,28,28));
	$this->objpdf->SetAligns(array('C','C','C','J','R','R'));
	
	$this->objpdf->setleftmargin(4);
	$this->objpdf->sety($xlin+$getdoy+7);
	$this->objpdf->setfillcolor(235);

	$xtotal = 0;
        $muda_pagina = false;
	$pag = 1;
	for($ii = 0;$ii < $this->linhasdositens ;$ii++){
	  if($ii!=0 && $muda_pagina!=true){
	    $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
	  }	  
	  $this->objpdf->ln(2);
	  db_fieldsmemory($this->recorddositens,$ii);
	  $prazo = "";
	  $pgto  = "";
	  $resumo = "";

          $descricaoitem =trim(pg_result($this->recorddositens,$ii,$this->descricaoitem));
	  
	  if(trim(pg_result($this->recorddositens,$ii,$this->sprazo))!=""){
	    $prazo = pg_result($this->recorddositens,$ii,$this->sprazo);
	    $prazo = "PRAZO: ".trim($prazo);
	  }
	  if(trim(pg_result($this->recorddositens,$ii,$this->spgto))!=""){
	    $pgto  = pg_result($this->recorddositens,$ii,$this->spgto);
	    $pgto = "CONDIÇÃO: ".trim($pgto);
	  }
	  if(trim(pg_result($this->recorddositens,$ii,$this->sresum)!="")){
	    $resumo = "RESUMO: ".pg_result($this->recorddositens,$ii,$this->sresum);
	    if($descricaoitem == "" || $descricaoitem == null){
	      $descricaoitem = trim(pg_result($this->recorddositens,$ii,$this->sresum));
	      $resumo="";
	    }
	  }

	  if($muda_pagina == true){
	    $muda_pagina = false;
	    $this->objpdf->sety($xlin+12);
	  }
	  
	  $unid  = pg_result($this->recorddositens,$ii,$this->sunidade);
	  $codunid    = pg_result($this->recorddositens,$ii,$this->scodunid);
	  $servico    = pg_result($this->recorddositens,$ii,$this->sservico);
	  $quantunid  = pg_result($this->recorddositens,$ii,$this->squantunid);	  
	  $susaquant  = pg_result($this->recorddositens,$ii,$this->susaquant);
	  
	  $dist = 2.7;
	  if(trim($codunid)!=""){
	    $unid = trim(substr($unid,0,10));
	    if($susaquant=="t"){
	      $unid .= " \n$quantunid UNIDADES\n";
	      $resumo = str_replace("\n","",$resumo);
	    }
	  }else if($servico=="t"){
	    $unid = "SERVIÇO";
	  }

	  $this->objpdf->Setfont('Arial','',8);
          $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),
	                           pg_result($this->recorddositens,$ii,$this->quantitem),
				   $unid,
				   $descricaoitem,
				   '',
				   ''),3,false,3);
	  if(isset($resumo) && $resumo!=""){
	    $this->objpdf->Row(array('','','',$resumo,'',''),$dist,false,2.7);
	  }
	  if(isset($prazo) && $prazo!=""){
	    $this->objpdf->Row(array('','','',$prazo,'',''),$dist,false,2.7);
	  }	    
	  if(isset($pgto) && $pgto!=""){
	    $this->objpdf->Row(array('','','',$pgto,'',''),$dist,false,2.7);
	  }
	  
	  $this->objpdf->Setfont('Arial','B',8);
          /////// troca de pagina
	  if( $this->objpdf->gety() > $this->objpdf->h - 30){
	    if(($ii+1)!=$this->linhasdositens){
	      $pag++;
	      $muda_pagina=true;
	      $this->objpdf->Setfont('Arial','',7);
	      if ($pag != 1){
		$this->objpdf->Setfont('Arial','B',7);
		$this->objpdf->rect($xcol,    $xlin+262,146,10,2,'DF','34');
		$this->objpdf->rect($xcol+146,$xlin+262, 28,10,2,'DF','34');
		$this->objpdf->rect($xcol+174,$xlin+262, 28,10,2,'DF','34');
		$this->objpdf->text($xcol+118,$xlin+268,'T O T A L   P Á G I N A');
		$this->objpdf->Setfont('Arial','',7);
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
	      $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	      $this->objpdf->text(130,$xlin-13,"ORÇAMENTO N".CHR(176));
	      $this->objpdf->text(185,$xlin-13,db_formatar($this->orccodigo,'s','0',6,'e'));	
	      $this->objpdf->text(130,$xlin-9,$this->labdados.CHR(176));
	      $this->objpdf->text(185,$xlin-9,db_formatar($this->Snumero,'s','0',6,'e'));	
	      $this->objpdf->Setfont('Arial','B',9);
	      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	      $this->objpdf->Setfont('Arial','',7);
	      $this->objpdf->text(40,$xlin-11,$this->enderpref);
	      $this->objpdf->text(40,$xlin-8,$this->municpref);
	      $this->objpdf->text(40,$xlin-5,$this->telefpref);
	      $this->objpdf->text(40,$xlin-2,$this->emailpref);
	      $this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));
	      
	      $xlin = -30;
	      $this->objpdf->Setfont('Arial','B',8);


	      // Caixas dos label's
	      $this->objpdf->rect($xcol    ,$xlin+54,14,6,2,'DF','12');
	      $this->objpdf->rect($xcol+ 14,$xlin+54,12,6,2,'DF','12');
	      $this->objpdf->rect($xcol+ 26,$xlin+54,22,6,2,'DF','12');
	      $this->objpdf->rect($xcol+ 48,$xlin+54,98,6,2,'DF','12');
	      $this->objpdf->rect($xcol+146,$xlin+54,28,6,2,'DF','12');
	      $this->objpdf->rect($xcol+174,$xlin+54,28,6,2,'DF','12');


	      $this->objpdf->rect($xcol,    $xlin+54,14,268,2,'DF','34');
	      $this->objpdf->rect($xcol+ 14,$xlin+54,12,268,2,'DF','34');
	      $this->objpdf->rect($xcol+ 26,$xlin+54,22,268,2,'DF','34');
	      $this->objpdf->rect($xcol+ 48,$xlin+54,98,268,2,'DF','34');
	      $this->objpdf->rect($xcol+146,$xlin+54,28,268,2,'DF','34');
	      $this->objpdf->rect($xcol+174,$xlin+54,28,268,2,'DF','34');
		  
	      $this->objpdf->sety($xlin+66);
	      $alt = 4;

	      // Label das colunas
	      $this->objpdf->Setfont('Arial','B',8);
	      $this->objpdf->text($xcol+  4,$xlin+58,'ITEM');
	      $this->objpdf->text($xcol+ 15,$xlin+58,'QUANT');
	      $this->objpdf->text($xcol+ 34,$xlin+58,'REF');
	      $this->objpdf->text($xcol+ 74,$xlin+58,'MATERIAL OU SERVIÇO');
	      $this->objpdf->text($xcol+148,$xlin+58,'VALOR UNITÁRIO');
	      $this->objpdf->text($xcol+179,$xlin+58,'VALOR TOTAL');

  /*
	      $this->objpdf->text($xcol+   4,$xlin+58,'ITEM');
	      $this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
	      $this->objpdf->text($xcol+  70,$xlin+58,'MATERIAL OU SERVIÇO');
	      $this->objpdf->text($xcol+ 145,$xlin+58,'VALOR UNITÁRIO');
	      $this->objpdf->text($xcol+ 176,$xlin+58,'VALOR TOTAL');
  */
	      $maiscol = 0;
	      $xlin = 20;

	    }

	    $this->objpdf->ln(2);
	    if($ii+1==$this->linhasdositens){
	      $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
	    }
	  }
	}
        // caixas para total

	$this->objpdf->Setfont('Arial','B',8);
	
	$this->objpdf->rect($xcol,    $xlin+262,146,10,2,'DF','34');
	$this->objpdf->rect($xcol+146,$xlin+262, 28,10,2,'DF','34');
	$this->objpdf->rect($xcol+174,$xlin+262, 28,10,2,'DF','34');
	$this->objpdf->text($xcol+113 ,$xlin+268,'T O T A L   G E R A L');

	//	echo $this->numaut."<br>";
	//	echo $pagina;exit;
    
     

    }else if ( $this->modelo == 133 ) {
      
////////// MODELO 13  -  SOLICITAÇÃO DE ORÇAMENTO
	
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$pagina = 1;
	$xlin = 20;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
	$this->objpdf->setfillcolor(255,255,255);
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
        $this->objpdf->text(130,$xlin-13,"ORÇAMENTO N".CHR(176));
	$this->objpdf->text(185,$xlin-13,db_formatar($this->orccodigo,'s','0',6,'e'));	
	$this->objpdf->text(130,$xlin-9,$this->labdados.CHR(176));
	$this->objpdf->text(185,$xlin-9,db_formatar($this->Snumero,'s','0',6,'e'));	
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);
	$this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));

        // Caixa com dados do orçamento e solicitação 
	$this->objpdf->rect($xcol,$xlin+3,$xcol+198,27,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+5,'Dados do Orçamento/'.$this->labtitulo);
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+  2,$xlin+ 8,'Orçamento');
	$this->objpdf->text($xcol+109,$xlin+ 8,'Data Limite');
	$this->objpdf->text($xcol+150,$xlin+ 8,'Hora Limite');
	$this->objpdf->text($xcol+  2,$xlin+13,$this->labtitulo);
	$this->objpdf->text($xcol+  2,$xlin+21,'Departamento');
	$this->objpdf->text($xcol+109,$xlin+17,$this->labtipo);
	$this->objpdf->text($xcol+  2,$xlin+17,'Data');
	$this->objpdf->text($xcol+  2,$xlin+25,'Resumo');
	$this->objpdf->Setfont('Arial','',8);
	
        // Imprime dados do orçamento e solicitação
	$this->objpdf->text($xcol+ 23,$xlin+ 8,':  '.$this->orccodigo);
	$this->objpdf->text($xcol+125,$xlin+ 8,':  '.$this->orcdtlim);
	$this->objpdf->text($xcol+166,$xlin+ 8,':  '.$this->orchrlim);
	$this->objpdf->text($xcol+ 23,$xlin+ 13,':  '.$this->Snumero);
	$this->objpdf->text($xcol+ 23,$xlin+ 21,':  '.$this->Sdepart);
	if(isset($this->Sdata) && trim($this->Sdata)!=""){
	  $this->Sdata = db_formatar($this->Sdata,'d');
	}
	if(trim($this->labtipo)!=""){
	  $this->objpdf->text($xcol+125,$xlin+17,':  '.$this->Stipcom);
	}
	$this->objpdf->text($xcol+ 23,$xlin+17,':  '.$this->Sdata);
	$this->objpdf->setxy($xcol+22,$xlin+22);
	$this->objpdf->cell(3,4,':  ',0,0,"L",0);
	$this->objpdf->setxy($xcol+24.5,$xlin+22);
	$Sresumo = $this->Sresumo;
	$Sresumo = str_replace("\n",". ",$Sresumo);
	$Sresumo = str_replace("\r","",$Sresumo);
	$this->objpdf->multicell(175,4,$Sresumo,0,"J");

        // Caixa com dados dos fornecedores
	$this->objpdf->rect($xcol,$xlin+32,$xcol+198,16,2,'DF','1234');
	$this->objpdf->Setfont('Arial','',6);
	$this->objpdf->text($xcol+2,$xlin+34,'Dados do Fornecedor');
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+109,$xlin+38,'Numcgm');
	$this->objpdf->text($xcol+150,$xlin+38,(strlen($this->cnpj) == 11?'CPF':'CNPJ'));
	$this->objpdf->text($xcol+  2,$xlin+38,'Nome');
	$this->objpdf->text($xcol+  2,$xlin+42,'Endereço');
	$this->objpdf->text($xcol+102,$xlin+42,'Complemento');
	$this->objpdf->text($xcol+  2,$xlin+46,'Município');
	$this->objpdf->text($xcol+115,$xlin+46,'CEP');
	$this->objpdf->text($xcol+150,$xlin+46,'Telefone');
	$this->objpdf->Setfont('Arial','',8);

        // Imprime dados dos fornecedores
	$this->objpdf->text($xcol+18,$xlin+ 38,':  '.$this->nome);
	$this->objpdf->text($xcol+122,$xlin+38,':  '.$this->numcgm);
	$this->objpdf->text($xcol+163,$xlin+38,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')));
	$this->objpdf->text($xcol+18,$xlin+ 42,':  '.$this->ender);
	$this->objpdf->text($xcol+122,$xlin+42,':  '.$this->compl);
	$this->objpdf->text($xcol+18,$xlin+ 46,':  '.$this->munic.'-'.$this->uf);
	$this->objpdf->text($xcol+122,$xlin+46,':  '.$this->cep);
	$this->objpdf->text($xcol+163,$xlin+46,':  '.$this->telefone);

        $getdoy = 50;

	$contadepart = 0;
	$alturaini = 216;
	if($this->linhasdosdepart>0){
	  for($i=0;$i<$this->linhasdosdepart;$i++){
	    $contadepart += 4;
	  }
          $setaut = $xlin + $getdoy;
	  $alturaini -= ($contadepart+15);

	  $this->objpdf->Setfont('Arial','B',8);
	  // Caixa de texto para labels 
	  $this->objpdf->rect($xcol    ,$setaut,202,6,2,'DF','12');
	  $this->objpdf->text($xcol+  4,$setaut+4,'DEPARTAMENTOS DAS SOLICITAÇÕES');

	  $this->objpdf->rect($xcol    ,$setaut+6,30,6,2,'DF','12');
	  $this->objpdf->rect($xcol+30 ,$setaut+6,30,6,2,'DF','12');
	  $this->objpdf->rect($xcol+60 ,$setaut+6,142,6,2,'DF','12');
	  
	  $this->objpdf->rect($xcol    ,$setaut+12,30,$contadepart+1,2,'DF','34');
	  $this->objpdf->rect($xcol+30 ,$setaut+12,30,$contadepart+1,2,'DF','34');
	  $this->objpdf->rect($xcol+60 ,$setaut+12,142,$contadepart+1,2,'DF','34');
	  $this->objpdf->sety($xlin+66);

	  // Label das colunas
	  $this->objpdf->text($xcol+   6,$setaut+11,'SOLICITAÇÃO');
	  $this->objpdf->text($xcol+  39,$setaut+11,'CÓDIGO');
	  $this->objpdf->text($xcol+ 125,$setaut+11,'DESCRIÇÃO');

	  // Seta altura nova para impressão dos dados
	  $this->objpdf->sety($setaut+13);
	  $this->objpdf->setx($xcol);
	  $this->objpdf->setleftmargin(4);
	  $this->objpdf->Setfont('Arial','',7);
	  $this->objpdf->SetAligns(array('C','C','L'));
	  $this->objpdf->SetWidths(array(30,30,142));
	  for($i=0;$i<$this->linhasdosdepart;$i++){
	    db_fieldsmemory($this->recorddosdepart,$i);
	    $solicita  = trim(pg_result($this->recorddosdepart,$i,$this->Snumdepart));
	    $codigodep = trim(pg_result($this->recorddosdepart,$i,$this->Scoddepto));
	    $descrdep  = trim(pg_result($this->recorddosdepart,$i,$this->Sdescrdepto));
	    $this->objpdf->Row(array($solicita,$codigodep,$descrdep),4,false,4);
	  }
	  $getdoy = $this->objpdf->gety()+2-$xlin;
	}


        // Caixa com Labels item, quantidade, descrição, valor 
	$this->objpdf->Setfont('Arial','B',8);

        // Caixas dos label's
	$this->objpdf->rect($xcol    ,$xlin+$getdoy,10,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 10,$xlin+$getdoy,12,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 22,$xlin+$getdoy,22,6,2,'DF','12');
	$this->objpdf->rect($xcol+ 44,$xlin+$getdoy,98,6,2,'DF','12');
	$this->objpdf->rect($xcol+142,$xlin+$getdoy,30,6,2,'DF','12');
	$this->objpdf->rect($xcol+172,$xlin+$getdoy,30,6,2,'DF','12');

	$menos = 16.9;
	if($this->linhasdosfornec==0){
	  $menos = 11;
	}
	if(isset($tiramenos)){
	  $menos = $menos+$tiramenos;
	  if($menos<0){
	    $menos = -$menos;
	  }
	}
        // Caixa dos itens
	$this->objpdf->rect($xcol,    $xlin+$getdoy+6,10,$alturaini,2,'DF','34');
        // Caixa da quantidade
	$this->objpdf->rect($xcol+ 10,$xlin+$getdoy+6,12,$alturaini,2,'DF','34');
	
	$this->objpdf->rect($xcol+ 22,$xlin+$getdoy+6,22,$alturaini,2,'DF','34');
        // Caixa dos materiais ou serviços
	$this->objpdf->rect($xcol+ 44,$xlin+$getdoy+6,98,$alturaini,2,'DF','34');
        // Caixa dos valores unitários
	$this->objpdf->rect($xcol+142,$xlin+$getdoy+6,30,$alturaini,2,'DF','');
        // Caixa dos valores totais dos itens
	$this->objpdf->rect($xcol+172,$xlin+$getdoy+6,30,$alturaini,2,'DF','34');

   	$this->objpdf->sety($xlin+48);
	
	$alt = 4;
        // Label das colunas
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+   2,$xlin+$getdoy+4,'ITEM');
	$this->objpdf->text($xcol+  11,$xlin+$getdoy+4,'QUANT');
	$this->objpdf->text($xcol+  30,$xlin+$getdoy+4,'REF');
	$this->objpdf->text($xcol+  70,$xlin+$getdoy+4,'MATERIAL OU SERVIÇO');
	$this->objpdf->text($xcol+ 145,$xlin+$getdoy+4,'VALOR UNITÁRIO');
	$this->objpdf->text($xcol+ 176,$xlin+$getdoy+4,'VALOR TOTAL');
        $maiscol = 0;
	$this->objpdf->SetWidths(array(10,12,22,95,30,30));
	$this->objpdf->SetAligns(array('C','C','C','J','R','R'));
	
	$this->objpdf->setleftmargin(4);
	$this->objpdf->sety($xlin+$getdoy+7);
	$this->objpdf->setfillcolor(235);

	$xtotal = 0;
        $muda_pagina = false;
	$pag = 1;
	for($ii = 0;$ii < $this->linhasdositens ;$ii++){
	  if($ii!=0 && $muda_pagina!=true){
	    $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
	  }	  
	  $this->objpdf->ln(2);
	  db_fieldsmemory($this->recorddositens,$ii);
	  $prazo = "";
	  $pgto  = "";
	  $resumo = "";

          $descricaoitem =trim(pg_result($this->recorddositens,$ii,$this->descricaoitem));
	  
	  if(trim(pg_result($this->recorddositens,$ii,$this->sprazo))!=""){
	    $prazo = pg_result($this->recorddositens,$ii,$this->sprazo);
	    $prazo = "PRAZO: ".trim($prazo);
	  }
	  if(trim(pg_result($this->recorddositens,$ii,$this->spgto))!=""){
	    $pgto  = pg_result($this->recorddositens,$ii,$this->spgto);
	    $pgto = "CONDIÇÃO: ".trim($pgto);
	  }
	  if(trim(pg_result($this->recorddositens,$ii,$this->sresum)!="")){
	    $resumo = "RESUMO: ".pg_result($this->recorddositens,$ii,$this->sresum);
	    if($descricaoitem == "" || $descricaoitem == null){
	      $descricaoitem = trim(pg_result($this->recorddositens,$ii,$this->sresum));
	      $resumo="";
	    }
	  }

	  if($muda_pagina == true){
	    $muda_pagina = false;
	    $this->objpdf->sety($xlin+12);
	  }
	  
	  $unid  = pg_result($this->recorddositens,$ii,$this->sunidade);
	  $codunid    = pg_result($this->recorddositens,$ii,$this->scodunid);
	  $servico    = pg_result($this->recorddositens,$ii,$this->sservico);
	  $quantunid  = pg_result($this->recorddositens,$ii,$this->squantunid);	  
	  $susaquant  = pg_result($this->recorddositens,$ii,$this->susaquant);
	  
	  $dist = 2.7;
	  if(trim($codunid)!=""){
	    $unid = trim(substr($unid,0,10));
	    if($susaquant=="t"){
	      $unid .= " \n$quantunid UNIDADES\n";
	      $resumo = str_replace("\n","",$resumo);
	    }
	  }else if($servico=="t"){
	    $unid = "SERVIÇO";
	  }

	  $this->objpdf->Setfont('Arial','',8);
          $this->objpdf->Row(array(pg_result($this->recorddositens,$ii,$this->item),
	                           pg_result($this->recorddositens,$ii,$this->quantitem),
				   $unid,
				   $descricaoitem,
				   '',
				   ''),3,false,3);
	  if(isset($resumo) && $resumo!=""){
	    $this->objpdf->Row(array('','','',$resumo,'',''),$dist,false,2.7);
	  }
	  if(isset($prazo) && $prazo!=""){
	    $this->objpdf->Row(array('','','',$prazo,'',''),$dist,false,2.7);
	  }	    
	  if(isset($pgto) && $pgto!=""){
	    $this->objpdf->Row(array('','','',$pgto,'',''),$dist,false,2.7);
	  }
	  
	  $this->objpdf->Setfont('Arial','B',8);
          /////// troca de pagina
	  if( $this->objpdf->gety() > $this->objpdf->h - 30){
	    $pag++;
	    $muda_pagina=true;
	    $this->objpdf->Setfont('Arial','',7);
	    if ($pag != 1){
	      $this->objpdf->Setfont('Arial','B',7);
	      $this->objpdf->rect($xcol,    $xlin+262,142, 10,2,'DF','34');
	      $this->objpdf->rect($xcol+142,$xlin+262,30, 10,2,'DF','34');
	      $this->objpdf->rect($xcol+172,$xlin+262,30, 10,2,'DF','34');
	      $this->objpdf->text($xcol+114 ,$xlin+268,'T O T A L   P Á G I N A');
	      $this->objpdf->Setfont('Arial','',7);
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
	    $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	    $this->objpdf->text(130,$xlin-13,"ORÇAMENTO N".CHR(176));
	    $this->objpdf->text(185,$xlin-13,db_formatar($this->orccodigo,'s','0',6,'e'));	
	    $this->objpdf->text(130,$xlin-9,$this->labdados.CHR(176));
	    $this->objpdf->text(185,$xlin-9,db_formatar($this->Snumero,'s','0',6,'e'));	
	    $this->objpdf->Setfont('Arial','B',9);
	    $this->objpdf->text(40,$xlin-15,$this->prefeitura);
	    $this->objpdf->Setfont('Arial','',7);
	    $this->objpdf->text(40,$xlin-11,$this->enderpref);
	    $this->objpdf->text(40,$xlin-8,$this->municpref);
	    $this->objpdf->text(40,$xlin-5,$this->telefpref);
	    $this->objpdf->text(40,$xlin-2,$this->emailpref);
	    $this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));
	    
            $xlin = -30;
	    $this->objpdf->Setfont('Arial','B',8);

  	    $this->objpdf->rect($xcol,$xlin+54,15,6,2,'DF','12');
	    $this->objpdf->rect($xcol+15,$xlin+54,20,6,2,'DF','12');
	    $this->objpdf->rect($xcol+35,$xlin+54,107,6,2,'DF','12');
	    $this->objpdf->rect($xcol+142,$xlin+54,30,6,2,'DF','12');
	    $this->objpdf->rect($xcol+172,$xlin+54,30,6,2,'DF','12');

	    $this->objpdf->rect($xcol,$xlin+60,15,262,2,'DF','34');
	    $this->objpdf->rect($xcol+15,$xlin+60,20,262,2,'DF','34');
	    $this->objpdf->rect($xcol+35,$xlin+60,107,262,2,'DF','34');
	    $this->objpdf->rect($xcol+142,$xlin+60,30,262,2,'DF','34');
	    $this->objpdf->rect($xcol+172,$xlin+60,30,262,2,'DF','34');

	    $this->objpdf->sety($xlin+66);
	    $alt = 4;

	    $this->objpdf->text($xcol+4,$xlin+58,'ITEM');
	    $this->objpdf->text($xcol+15.5,$xlin+58,'QUANTIDADE');
	    $this->objpdf->text($xcol+70,$xlin+58,'MATERIAL OU SERVIÇO');
	    $this->objpdf->text($xcol+145,$xlin+58,'VALOR UNITÁRIO');
	    $this->objpdf->text($xcol+176,$xlin+58,'VALOR TOTAL');

	    $maiscol = 0;
            $xlin = 20;

	  }

	  $this->objpdf->ln(2);
	  if($ii+1==$this->linhasdositens){
	    $this->objpdf->rect(4,$this->objpdf->gety(),202,0,1,'DF','1234');
	  }
	}
        // caixas para total

	$this->objpdf->Setfont('Arial','B',8);
	
	$this->objpdf->rect($xcol,    $xlin+262,142, 10,2,'DF','34');
	$this->objpdf->rect($xcol+142,$xlin+262,30, 10,2,'DF','34');
	$this->objpdf->rect($xcol+172,$xlin+262,30, 10,2,'DF','34');
	$this->objpdf->text($xcol+113 ,$xlin+268,'T O T A L   G E R A L');

	//	echo $this->numaut."<br>";
	//	echo $pagina;exit;
    
     
    }else if ( $this->modelo == 14 ) {     
      
////////// MODELO 14  -  AIDOF
	
	$this->objpdf->AliasNbPages();
	$this->objpdf->AddPage();
	$this->objpdf->settopmargin(1);
	$this->objpdf->setleftmargin(4);
	$pagina = 1;
	$xlin = 30;
	$xcol = 4;
	
	$this->objpdf->setfillcolor(245);
	$this->objpdf->rect($xcol-2,$xlin-28,206,292,2,'DF','1234');
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(130,$xlin-13,'AUTORIZAÇÃO  N'.CHR(176));
	$this->objpdf->text(165,$xlin-13,db_formatar($this->codaidof,'s','0',6,'e'));
	$this->objpdf->text(180,$xlin-13,"/".$this->ano);
        $this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
	$this->objpdf->Setfont('Arial','B',9);
	$this->objpdf->text(40,$xlin-15,$this->prefeitura);
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(40,$xlin-11,$this->enderpref);
	$this->objpdf->text(40,$xlin- 8,$this->municpref);
	$this->objpdf->text(40,$xlin- 5,$this->telefpref);
	$this->objpdf->text(40,$xlin- 2,$this->emailpref);
	
	$this->objpdf->Setfont('Arial','b',12);
	$this->objpdf->text($xcol+50,$xlin+6,"AUTORIZAÇÃO DE IMPRESSÃO DE DOCUMENTOS");
	$this->objpdf->text($xcol+60,$xlin+12,"FISCAIS DO IMPOSTO SOBRE SERVIÇOS");
	
	$this->objpdf->Setfont('Arial','b',8);
	$this->objpdf->text($xcol+80,$xlin+19,"ESTABELECIMENTO GRÁFICO");
	$this->objpdf->rect($xcol,$xlin+20,$xcol+198,30,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+20,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+30,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+40,$xcol+96,10,2,'','1234');
	$this->objpdf->rect($xcol+100,$xlin+40,$xcol+98,10,2,'','1234');
	$this->objpdf->Setfont('Arial','b',8);
	$this->objpdf->text($xcol+2,$xlin+28,"NOME:");
	$this->objpdf->text($xcol+2,$xlin+38,"ENDEREÇO:");
	$this->objpdf->text($xcol+2,$xlin+48,"INSCRIÇÃO MUNICIPAL:");
	$this->objpdf->text($xcol+102,$xlin+48,"INSCRIÇÃO DO CNPJ:");
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+12,$xlin+28,$this->nome_graf);
	$this->objpdf->text($xcol+22,$xlin+38,$this->ender_graf);
	$this->objpdf->text($xcol+38,$xlin+48,$this->inscr_graf);
	$this->objpdf->text($xcol+138,$xlin+48,$this->cnpj_graf);

	$this->objpdf->Setfont('Arial','B',8);
        $this->objpdf->text($xcol+81,$xlin+54,"ESTABELECIMENTO USUÁRIO");
	$this->objpdf->rect($xcol,$xlin+55,$xcol+198,30,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+55,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+65,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol+100,$xlin+75,$xcol+98,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+75,$xcol+96,10,2,'','1234');
	$this->objpdf->rect($xcol+100,$xlin+75,$xcol+98,10,2,'','1234');
        $this->objpdf->text($xcol+2,$xlin+63,"NOME:");
	$this->objpdf->text($xcol+2,$xlin+73,"ENDEREÇO:");
	$this->objpdf->text($xcol+2,$xlin+83,"INSCRIÇÃO MUNICIPAL:");
	$this->objpdf->text($xcol+102,$xlin+83,"INSCRIÇÃO DO CNPJ:");
	$this->objpdf->Setfont('Arial','',8);
	$this->objpdf->text($xcol+12,$xlin+63,$this->nome_usu);
	$this->objpdf->text($xcol+22,$xlin+73,$this->ender_usu);
	$this->objpdf->text($xcol+38,$xlin+83,$this->inscr_usu);
	$this->objpdf->text($xcol+138,$xlin+83,$this->cnpj_usu);
	
	
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+80,$xlin+89,"DOCUMENTO A SER IMPRESSO");
	$this->objpdf->rect($xcol,$xlin+90,$xcol+198,50,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+90,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+100,$xcol+198,10,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+90,$xcol+65,20,2,'','1234');
	$this->objpdf->rect($xcol+110,$xlin+90,$xcol+88,20,2,'','1234');
        $this->objpdf->text($xcol+25,$xlin+96,"NUMERAÇÃO");
	$this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+20,$xlin+108,$this->notaini."   A   ".$this->notafin);
	$this->objpdf->Setfont('Arial','B',8);
        $this->objpdf->text($xcol+78,$xlin+96,"QUANTIDADE");
        $this->objpdf->text($xcol+150,$xlin+96,"ESPÉCIE");
        $this->objpdf->text($xcol+2,$xlin+115,"OBSERVAÇÕES:");
	$this->objpdf->Setfont('Arial','',8);
        $this->objpdf->text($xcol+85,$xlin+108,$this->quant);
        $this->objpdf->text($xcol+115,$xlin+108,$this->especie);
	$this->objpdf->SetXY($xcol+2,$xlin+117);
	$this->objpdf->Setfont('Arial','',7);
	$this->objpdf->multicell(198,4,$this->obs,0,"L");   

	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+40,$xlin+144,"PEDIDO");
	$this->objpdf->rect($xcol,$xlin+145,$xcol+95,80,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+145,$xcol+95,8,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+153,$xcol+95,16,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+169,$xcol+95,8,2,'','1234');
	$this->objpdf->rect($xcol,$xlin+177,$xcol+95,24,2,'','1234');

        $this->objpdf->text($xcol+140,$xlin+144,"ENTREGA");
   	$this->objpdf->rect($xcol+103,$xlin+145,$xcol+95,48,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+145,$xcol+95,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+153,$xcol+95,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+153,$xcol+47,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+161,$xcol+95,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+169,$xcol+95,8,2,'','1234');
   	$this->objpdf->rect($xcol+103,$xlin+177,$xcol+95,16,2,'','1234');
	
        $this->objpdf->text($xcol+137,$xlin+197,"REPARTIÇÃO FISCAL");
        $this->objpdf->text($xcol+141,$xlin+203,"AUTORIZAMOS");
	$this->objpdf->rect($xcol+103,$xlin+198,$xcol+95,27,2,'','1234');
	
	$this->objpdf->Setfont('Arial','',7);
        
	$this->objpdf->text($xcol+2,$xlin+151,"DATA:________________DE______________________________DE_____________");
      	$this->objpdf->text($xcol+1,$xlin+156,"NOME DO RESPONSÁVEL PELO ESTABELECIMENTO USUÁRIO");
      	$this->objpdf->text($xcol+3,$xlin+165,"___________________________________________________________________");
      	$this->objpdf->text($xcol+1,$xlin+172,"DOCUMENTO DE IDENTIDADE");
      	$this->objpdf->text($xcol+1,$xlin+200,"ASSINATURA DO RESPONSÁVEL PELO ESTABELECIMENTO USUÁRIO");
      	$this->objpdf->text($xcol+1,$xlin+224,"ASSINATURA DO RESPONSÁVEL PELO ESTABELECIMENTO GRÁFICO");
     	
       
        $this->objpdf->text($xcol+105,$xlin+151,"DATA:________________DE______________________________DE_____________");
        $this->objpdf->text($xcol+104,$xlin+156,"DOC. FISCAL N°.:");
        $this->objpdf->text($xcol+155,$xlin+156,"SÉRIE:");
        $this->objpdf->text($xcol+104,$xlin+164,"RECEBIDO POR");	
        $this->objpdf->text($xcol+104,$xlin+172,"DOCUMENTO DE IDENTIDADE");	
      	$this->objpdf->text($xcol+116,$xlin+192,"ASSINATURA DO RESPONSÁVEL PELO RECEBIMENTO");
        
	$this->objpdf->text($xcol+105,$xlin+211,"EM________________DE______________________________DE_____________");
      	$this->objpdf->text($xcol+115,$xlin+224,"ASSINATURA E CARIMBO DA AUTORIDADE COMPETENTE");
	
	$this->objpdf->Setfont('Arial','B',8);
	$this->objpdf->text($xcol+90,$xlin+229,"IMPORTANTE");
	$this->objpdf->rect($xcol,$xlin+230,$xcol+198,30,2,'','1234');
        $this->objpdf->Setfont('Arial','b',7);	 
        $this->objpdf->text($xcol+10,$xlin+236,"NO RODAPÉ DAS NOTAS FISCAIS DEVERÁ CONSTAR OBRIGATORIAMENTE:");
        $this->objpdf->text($xcol+10,$xlin+242,"- A QUANTIDADE DE TALÕES IMPRESSOS:");
        $this->objpdf->text($xcol+10,$xlin+246,"- A NUMERAÇÃO:");
        $this->objpdf->text($xcol+10,$xlin+250,"- O NUMERO DA AUTORIZAÇÃO PARA IMPRESSÃO:");
        $this->objpdf->text($xcol+10,$xlin+254,"- O CNPJ E O NÚMERO DE INSCRIÇÃO MUNICIPAL DA GRÁFICA:");


    }else if ( $this->modelo == 15 ) {     
 
 ////////// MODELO 15  -  ESTORNO DE EMPENHO
	
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
	$this->objpdf->Image('imagens/files/logo_boleto.png',15,$xlin-17,12); //.$this->logo
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
	$this->objpdf->text($xcol+149,$xlin+7,':  '.(strlen($this->cnpj) == 11?db_formatar($this->cnpj,'cpf'):db_formatar($this->cnpj,'cnpj')).'   Fone: '.$this->telefone);
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
	   $this->objpdf->text($xcol+84,$xlin+195,'GABINETE DO PREFEITO');
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
           $this->objpdf->text($xcol+29-(strlen($this->assinatura3)/2),$xlin+227,$this->assinatura3);
         
           $this->objpdf->text($xcol+99-(strlen($this->assinaturaprefeito)/2),$xlin+227,$this->assinaturaprefeito);
																							    
	   $this->objpdf->line($xcol+141,$xlin+225,$xcol+195,$xlin+225);
	   $this->objpdf->text($xcol+160,$xlin+227,'TESOUREIRO');
	  
//           $this->objpdf->rect($xcol,$xlin+246,202,26,2,'DF','1234');
	   
	   $this->objpdf->SetFont('Arial','',4);
           $this->objpdf->Text(2,296,$this->texto); // texto no canhoto do carne
  	   $this->objpdf->SetFont('Arial','',6);
           $this->objpdf->Text(200,296,($xxx+1).' via'); // texto no canhoto do carne
	   $this->objpdf->setfont('Arial','',11);
           $xlin = 169;
            
      }

     }else{
	echo "<script>alert('Modelo No. $this->modelo não definido no sistema. Contate suporte.')</script>";
	      exit;
    }
  }
}
?>
