<?
//include("cabec_rodape.php");
include("fpdf151/assinatura.php");

//========================================================================================//
//       ALTERAÇÕES NA CLASSE impcarne                                                    //
//                                                                                        //
//  1 - OS NOVOS MODELO INCLUIDOS NAO DEVERRÃO SER DESENVOLVIDOS DIRETAMENTE NA CLASSE     //
//  2 - APENAS SERA INCLUIDO UM ARQUIVO EXTERNO POR MEIO DE include_once                  //  
//  3 - OS MODELOS NOVOS E ANTIGOS VÃO FICAR NA PASTA fpdf151/impmodelos/                 //
//  4 - COLABORE, ORGANIZE O CODIGO, SIGA O PADRÃO                                        //
//                                                                                        //
//========================================================================================//
// DIRETORIO DOS MODELOS        ===>>> 	fpdf151/impmodelos/                               // 
// PADRÃO PARA NOME DOS MODELOS ===>>>  mod_imprime<xx>.php ex: mod_imprime1.php          //
//========================================================================================//

// MODELO 888   - relatorio da farmacia
// MODELO 887   - Relatorio do laboratorio

class db_impcarne extends cl_assinatura {
//class db_impcarne {

  var $cgccpf = null;
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
  

  

  var $Sdescrdepto      = '';    //responsï¿½vel pelo departamento
  var $Snumdepart       = '';    //responsï¿½vel pelo departamento
  


// Variáveis necessárias para requisição de retirada de medicamentos da farmacia
  var $Rnumero            = null;
  var $Ratendrequi        = null;
  var $Rdata		          = null;
  var $Rdepart            = null;
  var $Rhora              = null;
  var $Rresumo            = null;
  var $Rnomeus            = null;
  var $Rreceita           = null;
  var $Rdtvalidadereceita = null;
  var $Rtpreceita         = null;
  var $Rrequisitante      = null;
  var $Rident             = null;
  var $Rendereco          = null;
  var $Rnumeros           = null;  
  var $rcodmaterial       = null;
  var $rdescmaterial      = null;
  var $runidadesaida      = null;
  var $rquantdeitens      = null;  
  var $casadec            = null;
  var $ratendente         = null;
  var $rcodatend          = null;
  var $rcodprof           = null;
  var $rprofissional      = null;
  var $rlocalizacao       = null;
  var $robsdositens       = null;
  var $Rcoddepart         = null;
  

//variaveis do modulo laboratorio para emitir o comprovante de requisicao de exames
//****************************************************************//
  var $Rmedico         = null; 
  var $Rusuario        = null;
  var $Rpaciente       = null;
  var $Rresponsavel    = null;
  var $Rlaboratorio    = null;
  var $Rnomeusuario    = null;
  var $Rrequisito      = null;
  var $rcodrequisicao  = null;
  var $rsetor          = null;
  var $rexame          = null;
  var $rdata           = null;
  var $rhora           = null;
  var $rentrega        = null;

//variaveis do modulo tfd para emitir o recibo de ajuda de custo
//****************************************************************//

  var $Rretirou      = null;
  var $Rcgsretirou = null;
  var $Rcpf          = null;
  var $Ridentidade   = null;
  var $Rhoratfd         = null;
  var $Rdatatfd         = null;
  var $Rcodatendente = null;
  var $Ratendente    = null;
  var $rcodcgs       = null;
  var $rbeneficiado  = null;
  var $rvalor        = null;       
  var $rprocedimento =null;
  // VARIAVEIS DO RETÂNGULO PACIENTE
  var $sRNomePaciente     = null;
  var $iRsCnsPaciente     = null;
  var $dRNascPaciente     = null;
  var $sRIdentPaciente    = null;
  var $sRCpfPaciente      = null;
  var $sRCartSusPaciente  = null;
  var $sRMaePaciente      = null;
  var $sRSexoPaciente     = null;
  var $sREnderecoPaciente = null;
  var $sRNumeroPaciente   = null; 
  var $sRComplPaciente    = null;
  var $sRBairroPaciente   = null;
  var $sRMunicPaciente    = null;
  var $sRUfPaciente       = null;
  var $sRCepPaciente      = null;
  var $sRTelPaciente      = null;
  var $sRCelPaciente      = null;
  
  var $sRtf15_observacao  = null;
  var $sRtf12_descricao   = null;
  var $iRtf01_i_codigo    = null;
  
  // VARIAVEIS DA CAPA DE PROCESSO
  var $result_vars;

//************************************************************//
    
  function db_impcarne($objpdf,$impmodelo){
    $this->objpdf = $objpdf;
    $this->impmodelo = $impmodelo; 
  }
  function muda_pag($pagina,$xlin,$xcol,$fornec="false",&$contapagina,$mais=1){
    global $resparag, $resparagpadrao, $db61_texto, $db02_texto, $maislin, $xtotal, $flag_rodape;

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
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(111.2,$xlin+224,'Continua na Página '.($contapagina+1));
	$this->objpdf->setfillcolor(0,0,0);

	$this->objpdf->SetFont('Arial','',4);
	$this->objpdf->TextWithDirection(1.5,$xlin+60,$this->texto,'U'); // texto no canhoto do carne
	$this->objpdf->setfont('Arial','',11);
      }else{
	$this->objpdf->Setfont('Arial','',9);
	$this->objpdf->text(112.5,$xlin+271,'Continua na Página '.($contapagina+1));
      }

      if ($contapagina == 1) {
        $this->objpdf->Setfont('Arial','B',7);
        $sqlparag = "select db02_texto
	                   from db_documento
                 	   	    inner join db_docparag on db03_docum = db04_docum
                    	    inner join db_tipodoc on db08_codigo  = db03_tipodoc
                	        inner join db_paragrafo on db04_idparag = db02_idparag
               	     where db03_tipodoc = 1400 and db03_instit = " . db_getsession("DB_instit")." order by db04_ordem ";
			 
        $resparag = @db_query($sqlparag);

        if(@pg_numrows($resparag) > 0){
            db_fieldsmemory($resparag,0);

            eval($db02_texto);
            $flag_rodape = true;
        } else {
            $sqlparagpadrao = "select db61_texto
	                             from db_documentopadrao
                  	   	            inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc
                     	              inner join db_tipodoc         on db08_codigo   = db60_tipodoc
                 	                  inner join db_paragrafopadrao on db61_codparag = db62_codparag
               	               where db60_tipodoc = 1400 and db60_instit = " . db_getsession("DB_instit")." order by db62_ordem";
			 
            $resparagpadrao = @db_query($sqlparagpadrao);
            
            if(@pg_numrows($resparagpadrao) > 0){
                db_fieldsmemory($resparagpadrao,0);

                eval($db61_texto);
                $flag_rodape = true;
            }
        }
      }  
      $contapagina+=1;
      $this->objpdf->addpage();
      $pagina += 1;	   
      $muda_pag = true;
      
      $this->objpdf->settopmargin(1);
      $xlin = 20;
      $xcol = 4;
  
      
      $getlogo = db_getnomelogo();
			$logo    = ($getlogo==false?'':$getlogo);
			
			// Imprime cabeï¿½alho com dados sobre a prefeitura se mudar de pï¿½gina
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
			$this->objpdf->Image('imagens/files/'.$logo,15,$xlin-17,12);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text(40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',9);
      $this->objpdf->text(40,$xlin-11,$this->enderpref);
      $this->objpdf->text(40,$xlin-8,$this->municpref);
      $this->objpdf->text(40,$xlin-5,$this->telefpref);
      $this->objpdf->text(40,$xlin-2,$this->emailpref);
      $this->objpdf->text(40,$xlin+ 1,db_formatar($this->cgcpref,'cnpj'));
//      $this->objpdf->text(40,$xlin+2,'Continuaï¿½ï¿½o da Pï¿½gina '.($contapagina-1));
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
        // Caixa dos materiais ou serviï¿½os
	$this->objpdf->rect($xcol+ 44,$xlin+30,98,262,2,'DF','34');
        // Caixa dos valores unitï¿½rio3
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
      $this->objpdf->Setfont('Arial','',7);
    }
    return $x;
  }
  
  function imprime(){
  	include("fpdf151/impmodelos/mod_imprime".$this->impmodelo.".php");
  }
 }
?>
