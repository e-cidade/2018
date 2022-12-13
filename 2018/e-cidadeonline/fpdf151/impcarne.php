<?
//include("cabec_rodape.php");
include("assinatura.php");

//========================================================================================//
//       ALTERA��ES NA CLASSE impcarne                                                    //
//                                                                                        //
//  1 - OS NOVOS MODELO INCLUIDOS N�O DEVER�O SER DESENVOLVIDOS DIRETAMENTE NA CLASSE     //
//  2 - APENAS SERA INCLUIDO UM ARQUIVO EXTERNO POR MEIO DE include_once                  //  
//  3 - OS MODELOS NOVOS E ANTIGOS V�O FICAR NA PASTA fpdf151/impmodelos/                 //
//  4 - COLABORE, ORGANIZE O CODIGO, SIGA O PADR�O                                        //
//                                                                                        //
//========================================================================================//
// DIRETORIO DOS MODELOS        ===>>> 	fpdf151/impmodelos/                               // 
// PADR�O PARA NOME DOS MODELOS ===>>>  mod_imprime<xx>.php ex: mod_imprime1.php          //
//========================================================================================//

// MODELO 1  - CARNES DE PARCELAMENTO
// MODELO 2  - RECIBO DE PAGAMENTO ( 2 VIAS )
// MODELO 3  - ALVARA N�O DEFINIDO 
// MODELO 4  - BOLETO
// MODELO 5  - AUTORIZA��O DE EMPENHO
// MODELO 6  - NOTA DE EMPENHO
// MODELO 7  - ORDEM DE PAGAMENTO
// MODELO 8  - FICHA DE TRANSFERENCIA DE BENS
// MODELO 9  - ALVAR� DE LOCALIZA��O METADE A4
// MODELO 10 - ORDEM DE COMPRA
// MODELO 11 - SOLICITA��O DE COMPRA  Itens/Dota��es
// MODELO 12 - ANULA��O DE EMPENHO
// MODELO 13 - SOLICITA��O DE OR�AMENTO
// MODELO 14 - AIDOF
// MODELO 15 - ESTORNO DE PAGAMENTO
// MODELO 16 - CONTRA-CHEQUE 1          
// MODELO 17 - SOLICITA��O DE COMPRA  Dota��es/Itens          
// MODELO 18 - REQUISI��O DE SA�DA DE MATERIAIS
// MODELO 19 - EXTRATO DO RPPS
// MODELO 20 - ALVARA SANITARIO A4
// MODELO 21 - ALVARA SANITARIO METADE A4
// MODELO 22 - RECIBO DE PAGAMENTO ( 1 VIAS )
// MODELO 23 - ALVARA DE LICENSA PEQUENO
// MODELO 24 - ALVARA DE LICENSA GRANDE
// MODELO 25 - GUIA RECOLHIMENTO PREVIDENCIA
// MODELO 26 - ALVARA PRE IMPRESSO (BAGE)
// MODELO 27 - TERMO DE TRANSFER�NCIA DE MATERIAIS(ALMOXARIFADO)
// MODELO 28 - Carne de IPTU parcela unica

class db_impcarne extends cl_assinatura {
//class db_impcarne {

/////   VARI�VEIS PARA EMISSAO DE CARNES DE PARCELAMENTO - MODELO 1

  var $mod_rodape= 1;
  var $modelo    = 1;

  var $qtdcarne  = null;
  var $tipodebito= 'TIPO DE D�BITO';
  //var $tipoinscr = null;
  var $tipoinscr1= null;
  var $prefeitura= 'PREFEITURA DBSELLER';
  var $secretaria= 'SECRETARIA DE FAZENDA';
  var $debito    = null;
  var $logo      = null;
  var $parcela   = null;
  var $titulo1   = '';
  var $descr1    = null;
  var $titulo2   = 'C�d de Arrecada��o';
  var $descr2    = null;
  var $titulo3   = 'Contribuinte/Endere�o';
  var $descr3_1  = null;
  var $descr3_2  = null;
  var $titulo4   = 'Instru��es';
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
  var $titulo9   = 'C�d. de Arrecada��o';
  var $descr9    = null;
  var $titulo10  = 'Parcela';
  var $descr10   = null;
  var $titulo11  = 'Contribuinte/Endere�o';
  var $descr11_1 = null;
  var $descr11_2 = null;
  var $titulo12  = 'Instru��es';
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
  var $objpdf    = null;
  var $impmodelo = null;
 
//////  VARI�VEIS PARA EMISSAO DE CONTRA-CHEQUES

  var $lotacao     	= null;
  var $descr_lota  	= null;
  var $funcao      	= null;
  var $descr_funcao	= null;
  var $mensagem    	= null;
  var $recordenvelope  	= 0;
  var $linhasenvelope 	= 0;
  var $quantidade	= null;
  var $valor		= null;
  var $tipo		= null;
  var $rubrica		= null;
  var $descr_rub	= null;

//////  VARI�VEIS PARA EMISSAO DE RECIBO DE PAGAMENTO - MODELO 2
  var $cgccpf = null;
  var $identifica_dados = ""; 
  var $enderpref = null;
  var $cgcpref   = null;
  var $tipocompl = null;
  var $tipolograd= null;
  var $tipobairro= null;
  var $municpref = null;
  var $telefpref = null;
  var $faxpref   = null;
  var $emailpref = null;
  var $nome      = null;
  var $ender     = null;
  var $compl     = null;
  var $munic     = null;
  var $uf        = null;
  var $fax       = null;
  var $contato   = null;
  var $cep       = null;
  var $tipoinscr = 'Matr/Inscr';
  var $nrinscr   = null;
  var $nrinscr1  = null;
  var $ip        = null;
  var $nomepri   = '';
  var $nomepriimo= '';
  var $totaldesc = 0 ;
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
  var $historico = null;
  var $histparcel= null;
  var $recorddadospagto = 0;
  var $linhasdadospagto = 0;
  var $dtvenc    = null;
  var $numpre    = null;
  var $valtotal  = null;
 
//////  VARI�VEIS PARA EMISSAO DE ALVAR�

  var $tipoalvara  = null;
  var $obs         = null;
  var $ativ        = null;
  var $numbloco    = null;
  var $descrativ   = null;
  var $outrasativs = null;
  var $q02_memo    = null;
  var $numero      = null;
  var $q02_obs     = null;
  var $q03_atmemo  = null; // obs das atividades
  var $obsativ     = null; // obs da atividade principal
  var $processo    = null;
  var $datainc     = null;
  var $datafim     = null;
  var $dtiniativ   = null; // data de inicio das atividades
  var $dtfimativ   = null; // data de fim das atividades
  var $impdatas    = null; // se imprime as datas de inicio e fim das atividades
  var $impobsativ  = null; // se imprime a observas�o das atividades
  var $impcodativ  = null; // se imprime o codigo das atividades
  var $impobslanc  = null; // se imprime a observa��o do lan�amento
  var $permanente  = null; // se permanente ou provisorio 
  var $cnpjcpf     = null;
  var $assalvara   = null; // assinatura do alvara
  var $lancobs     = null; // observa��o do lan�amento do alvara de sanitario
  

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
  var $dtparapag    =''; 
  var $imagemlogo = '';
//// vairaveis para o orcamento
  var $orccodigo        = '';
  var $orcdtlim         = '';
  var $orchrlim         = '';
  var $faxforne         = '';
  var $uf_config        = '';


//// variaveis para a solicita��o de compras
  var $secfaz           = null;  //Nome do secret�rio da fazenda
  var $nompre           = null;  //Nome do prefeiro
  
  var $fonedepto        = null;
  var $faxdepto         = null;
  var $ramaldepto       = null;
  var $emaildepto       = null;

  // solicita
  var $Snumero          = null;  //n�mero da solicita��o
  var $Snumero_ant      = null;  //n�mero da solicita��o
  var $Sdata            = null;  //data da solicita��o
  var $Svalor           = null;  //valor aproximado da solicita��o
  var $Sorgao           = null;  //org�o
  var $Sunidade         = null;  //unidade
  var $sabrevunidade    = null;  //unidade abreviada
  var $Sresumo          = '';    //resumo da solicita��o
  var $Stipcom          = '';    //tipo de compra da solicita��o
  var $Sdepart          = '';    //departamento da solicita��o
  var $Srespdepart      = '';    //respons�vel pelo departamento
  var $Susuarioger      = '';    //Usu�rio que gerou a solicita��o
  
  var $Scoddepto        = '';    //respons�vel pelo departamento
  var $Sdescrdepto      = '';    //respons�vel pelo departamento
  var $Snumdepart       = '';    //respons�vel pelo departamento
  var $linhasdosdepart  = '';    //respons�vel pelo departamento
  var $resultdosdepart  = '';    //respons�vel pelo departamento
  
  // solicitem
  var $scodpcmater      = null;  //codigo do pcmater (quando for informado)
  var $scodunid         = null;  //codigo da unidade do item
  var $squantunid       = null;  //quantidade de cada unidade (caixa com 10 unidades)
  var $sprazo           = '';    //prazo de entrega do item
  var $spgto            = '';    //condi��es de pagamento do item
  var $sresum           = '';    //resumo do item
  var $sjust            = '';    //justificativa para a compra do item
  var $sunidade         = '';    //unidade (caixa,unit�rio, etc...)
  var $sservico         = '';    //se � servi�o ou material
  var $svalortot        = '';    //valor total (quantidade * valor)
  var $susaquant        = '';    //se usa a quantidade ex. caixa (usa quant),unit�rio(n�o usa)
  var $selemento        = '';    //elemento do item da solicita��o
  var $sdelemento       = '';    //descri�a� do elemento do item da solicita��o

  // pcdotac
  var $dcodigo          = null;  //c�digo da dota��o
  var $dcoddot          = null;  //c�digo da dota��o
  var $danousu          = null;  //ano da dota��o
  var $dquant           = null;  //quantidade do item na dota��o
  var $dvalor           = null;  //valor da dota��o  
  var $delemento        = '';    //elemento da dota��o
  var $dvalortot        = '';    //valor total (quantidade * valor)
  var $dreserva         = '';    //se o valor da dota��o foi reservado
  var $resultdasdotac   = null;  // recordset com dados dos fornecedores
  var $linhasdasdotac   = null;  // numero de linhas retornadas no recordsert
  var $dcprojativ       = '';
  var $dctiporec        = '';
  var $dprojativ        = '';
  var $dtiporec         = '';
  var $ddescrest        = '';

  //pcsugforn
  var $cgmforn          = null;       // cgm do fornecedor  
  var $nomeforn         = '';         // nome do fornecedor
  var $enderforn        = '';         // endereco do fornecedor
  var $municforn        = '';         // municipio do fornecedor
  var $foneforn         = '';         // telefone do fornecedor
  var $numforn          = '';         // numforn
  var $resultdosfornec  = null;       // recordset com dados dos fornecedores
  var $linhasdosfornec  = null;       // numero de linhas retornadas no recordsert

  //labels dos itens do processo do or�amento do processo de compras e or�amento de solicita��o
  var $labtitulo        = '';         // se � or�amento de solicita��o ou PC
  var $labdados         = '';         // se � or�amento de solicita��o ou PC
  var $labsolproc       = '';         // c�digo do or�amento ou solicita��o
  var $labtipo          = '';         // se for solicita��o, label do tipo

//// variaveis para a autoriza��o de empenho E ORDEM DE COMPRA
  var $assinatura1       = 'VISTO';
  
  var $assinatura2       = 'T�CNICO CONT�BIL'; 
  var $assinatura3       = 'SECRET�RIO(A) DA FAZENDA';
  var $assinatura4       = 'SECRET�RIO DA FAZENDA';
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
  var $agenciadv	= null;		// agencia
  var $conta  		= null;		// conta
  var $contadv 		= null;		// conta
  var $dotacao 		= null;		// dotacao orcamentaria (orgao,unidade,funcao,subfuncao,programa,projativ,elemento,recurso)
  var $descrdotacao 	= null;		// descricao da dotacao
  var $coddot		= null;		// codigo reduzido da despesa
  var $destino		= null;		// destino do material ou servi�o
  var $resumo		= null;		// destino do material ou servi�o
  var $licitacao  	= null;		// tipo de licita��o
  var $num_licitacao  	= null;		// numero da licita��o
  var $descr_licitacao 	= null;		// descri��o do tipo de licita��o
  var $descr_tipocompra	= null;		// descri��o do tipo de compra
  var $prazo_ent  	= null;		// prazo de entrega
//  var $obs		= null;		// observa��es
  var $cond_pag		= null;		// condi��es de pagamento
  var $out_cond		= null;		// outras condi��es de pagamento
  var $telef_cont 	= null;		// telefone do contato
  var $recorddositens 	= null;		// record set dos itens
  var $linhasdositens 	= null;		// numero de itens da autorizacao
  var $item	    	= null;		// codigo do item
  var $quantitem    	= null;		// quantidade do item
  var $valoritem    	= null;		// valor unit�rio do item
  var $empempenho       = null;         // cod empenho para emiss�o de ordem de compra
  var $dataordem        = null;         // data da gera��o da ordem de compra
  var $observacaoitem   = null;
  var $descricaoitem    = null;
  var $ordpag		= null;		// numero da ordem de pagamento
  var $elemento		= null;		// elemento da despesa
  var $descr_elemento	= null;		// descri��o do elemento da despesa
  var $elementoitem	= null;		// elemento do item da ordem de pagamento
  var $descr_elementoitem= null;	// descri��o do elemento do item da ordem de pagamento
  var $outrasordens     = null;		// saldo das outras ordens de pagamento do empenho
  var $vlrrec           = null;		// valor das receitas de reten�oes
  var $cnpj             = null;         // cpf ou cnpj do credor
  var $anulado		= null;         // valor anulado
  var $vlr_anul         = null;         // valor anulado
  var $data_est         = null;         // data estorno
  var $descr_anu        = null;         // descri��o da anula��o
  var $Scodemp          = null;         // descri��o da anula��o

// Vari�veis necess�rias para requisi��o de sa�da de materiais
  var $Rnumero          = null;
  var $Rdata		    = null;
  var $Rdepart          = null;
  var $Rhora            = null;
  var $Rresumo          = null;
  var $Rnomeus          = null;
  
  var $rcodmaterial     = null;
  var $rdescmaterial    = null;
  var $runidadesaida    = null;
  var $rquantdeitens    = null;
  var $robsdositens     = null;
  var $casadec          = null;

// VARIAVEIS PARA EMISS�O DO CARNE DE IPTU PARCELA UNICA
 
    var $iptj23_anousu      = '';
    var $iptz01_nome        = '';
    var $iptz01_numcgm      = '';
    var $iptz01_cgccpf      = '';
    var $iptz01_cidade      = '';
    var $iptz01_bairro      = '';
    var $iptz01_cep         = '';
    var $iptz01_ender       = '';
    var $iptj01_matric      = '';
    var $iptj23_vlrter      = '';
    var $iptj23_aliq        = '';
    var $iptk00_percdes     = '';
    var $iptj43_cep         = ''; 
    var $iptdtvencunic      = '';
    var $iptuvlrcor         = '';
    var $ipttotal           = '';
    var $iptnomepri         = '';
    var $iptproprietario    = '';
    var $iptuvlrdesconto    = '';
    var $iptbql             = '';
    var $iptcodigo_barras   = '';
    var $iptlinha_digitavel = '';
    var $iptdataemis        = '';
    var $iptprefeitura      = '';
    var $iptdebant          = '';  
    var $totalacres         = '';
// variaveis para a nota de empenho
  function db_impcarne($objpdf,$impmodelo){
    $this->objpdf = $objpdf;
    $this->impmodelo = $impmodelo; 
  }
  function muda_pag($pagina,$xlin,$xcol,$fornec="false",&$contapagina,$mais=1){
    $x = false;
    $valor_da_posicao_atual = $this->objpdf->gety();
    $valor_da_posicao_atual+= ($mais*5);
    $valor_da_posicao_atual = (int)$valor_da_posicao_atual;


    $valor_do_tamanho_pagin = $this->objpdf->h;
    $valor_do_tamanho_pagin-= 58;
    $valor_do_tamanho_pagin = (int)$valor_do_tamanho_pagin;

    $valor_do_tamanho_mpagi = $this->objpdf->h;
    $valor_do_tamanho_mpagi-= 30;
    $valor_do_tamanho_mpagi = (int)$valor_do_tamanho_mpagi;

    if((($valor_da_posicao_atual > $valor_do_tamanho_pagin) && $contapagina == 1 ) ||  (($valor_da_posicao_atual > $valor_do_tamanho_mpagi) && $contapagina != 1)){
      if($contapagina == 1){
	  if(strtoupper(trim($this->municpref)) != 'SAPIRANGA'){
	    $this->objpdf->setfillcolor(0,0,0);
	    $this->objpdf->Setfont('Arial','B',7);
	    $this->objpdf->rect($xcol    ,$xlin+237,66,35,2,'DF','1234');
	    $this->objpdf->rect($xcol+68 ,$xlin+237,66,35,2,'DF','1234');
	    $this->objpdf->rect($xcol+136,$xlin+237,66,35,2,'DF','1234');

        $this->objpdf->text($xcol+5,$xlin+244,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');

        if(strtoupper(trim($this->municpref)) == 'GUAIBA'){
          $this->objpdf->text($xcol+25,$xlin+260,'REQUERENTE',0,40);
        }else{
          $this->objpdf->text($xcol+20,$xlin+256,"AUTORIZO",0,4);
	      $this->objpdf->text($xcol+5,$xlin+268,substr($this->Sorgao,0,35));
        }

	    $this->objpdf->text($xcol+93,$xlin+256,"AUTORIZO",0,4);
	    if(strtoupper(trim($this->municpref)) != 'GUAIBA'){
	      $this->objpdf->text($xcol+83,$xlin+268,'DIV. DE ABASTECIMENTO',0,40);
	    }
	    $this->objpdf->text($xcol+150,$xlin+256,"ORDENADOR DA DESPESA",0,4);
	    $xlin += 10;
	  }else{
	    $this->objpdf->setfillcolor(0,0,0);
	    $this->objpdf->Setfont('Arial','B',7);
	    $this->objpdf->rect($xcol    ,$xlin+227,66,45,2,'DF','1234');
	    $this->objpdf->rect($xcol+ 68,$xlin+227,66,45,2,'DF','1234');
	    $this->objpdf->rect($xcol+136,$xlin+227,66,45,2,'DF','1234');

	    $this->objpdf->SetXY($xcol+08,$xlin+228);
	    $this->objpdf->multicell(66,4,"SOLICITANTE",0,"C");
	    $this->objpdf->SetXY($xcol+08,$xlin+235.5);
	    $this->objpdf->multicell(66,0.5,"_________________________________",0,"C");

	    $this->objpdf->SetXY($xcol+08,$xlin+247.5);
            //   SECRET�RIO(A) DA SECRETARIA QUE SOLICITOU   //
	    $this->objpdf->multicell(66,4,"SECRET�RIO",0,"C");
	    $this->objpdf->SetXY($xcol+08,$xlin+251.5);
	    $this->objpdf->multicell(66,4,substr($this->Sorgao,0,30),0,"C");
	    ///////////////////////////////////////////////////
	    $this->objpdf->text($xcol+10,$xlin+261,"_________________________________",0,4);

	    $this->objpdf->text($xcol+10,$xlin+270,strtoupper($this->municpref).', '.substr($this->emissao,8,2).' DE '.strtoupper(db_mes(substr($this->emissao,5,2))).' DE '.substr($this->emissao,0,4).'.');
	    $this->objpdf->SetXY($xcol+68,$xlin+228);
	    $this->objpdf->multicell(66,4,"CONTABILIDADE",0,"C");
            $this->objpdf->SetXY($xcol+68,$xlin+235.5);
            $this->objpdf->multicell(66,0.5,"_________________________________",0,"C");
	    $this->objpdf->SetXY($xcol+68,$xlin+242);
	    $this->objpdf->multicell(66,4,"H� RECURSOS FINANCEIROS",0,"C");



            $tamanho = strlen($this->secfaz);
	    $posicao = strpos($this->secfaz,"\n");
            $secretaria = $this->secfaz;
	    $deque   = "";
	    if($posicao!="" && $posicao!=0){
	      $secretaria = substr($this->secfaz,0,$posicao);
	      $deque = substr($this->secfaz,$posicao,$tamanho);
	    }
	    
	    $this->objpdf->text($xcol+85,$xlin+252,trim($secretaria),0,4);
	    $this->objpdf->text($xcol+85,$xlin+254.5,trim($deque),0,4);

	    $this->objpdf->text($xcol+92,$xlin+264,"CONFERIDO",0,4);
	    $this->objpdf->text($xcol+83.5,$xlin+270,"________/________/________",0,4);

	    $this->objpdf->SetXY($xcol+136,$xlin+242);
	    $this->objpdf->multicell(66,4,$this->nompre,0,"C");
	    $this->objpdf->text($xcol+163,$xlin+264,"AUTORIZA",0,4);
	    $this->objpdf->text($xcol+152.5,$xlin+270,"________/________/________",0,4);
	  }
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(111.2,$xlin+224,'Continua na P�gina '.($contapagina+1));
	$this->objpdf->setfillcolor(0,0,0);

	$this->objpdf->SetFont('Arial','',4);
	$this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
	$this->objpdf->setfont('Arial','',11);
      }else{
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(112.5,$xlin+271,'Continua na P�gina '.($contapagina+1));
      }
      $contapagina+=1;
      $this->objpdf->addpage();
      $pagina += 1;	   
      $muda_pag = true;
      
      $this->objpdf->settopmargin(1);
      $xlin = 20;
      $xcol = 4;
  
      // Imprime cabe�alho com dados sobre a prefeitura se mudar de p�gina
      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(130,$xlin-13,'SOLICITA��O DE COMPRA N'.CHR(176));
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
//      $this->objpdf->text(40,$xlin+2,'Continua��o da P�gina '.($contapagina-1));
      $this->objpdf->text(130,$xlin+2,'P�gina '.$contapagina);
      
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

    /*    
        if(strtoupper(trim($this->municpref)) == 'SAPIRANGA'){
	  $xlin -= 10;
	}
	*/

        // Caixa dos itens
	$this->objpdf->rect($xcol,    $xlin+30,10,262,2,'DF','34');
        // Caixa da quantidade
	$this->objpdf->rect($xcol+ 10,$xlin+30,12,262,2,'DF','34');
	
	$this->objpdf->rect($xcol+ 22,$xlin+30,22,262,2,'DF','34');
        // Caixa dos materiais ou servi�os
	$this->objpdf->rect($xcol+ 44,$xlin+30,98,262,2,'DF','34');
        // Caixa dos valores unit�rio3
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
	$this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVI�O');
	$this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNIT�RIO');
	$this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');

      }else if(isset($fornec) && $fornec=="true"){
      }
      $maiscol = 0;
      $xlin = 20;
      // Seta altura nova para impress�o dos dados
      $this->objpdf->sety($xlin+11);
      $this->objpdf->setleftmargin(3);
      $x = true;
      $this->objpdf->Setfont('Arial','',7);
    }
    return $x;
  }
  
  function imprime(){
 		include("impmodelos/mod_imprime".$this->impmodelo.".php");
  }
 }
?>
