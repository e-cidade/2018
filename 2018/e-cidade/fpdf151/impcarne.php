<?php
if ( !class_exists('cl_assinatura') ) {
  require_once(modification("fpdf151/assinatura.php"));
}
/**
 * Classe para impressão de modelos pdf
 *
 *
 *
 *
 *
 *
 * +=======================================================================================+
 * |      ALTERAÇÕES NA CLASSE impcarne                                                    |
 * |                                                                                       |
 * | 1 - OS NOVOS MODELO INCLUIDOS NAO DEVERRÃO SER DESENVOLVIDOS DIRETAMENTE NA CLASSE    |
 * | 2 - APENAS SERA INCLUIDO UM ARQUIVO EXTERNO POR MEIO DE include_once                  |
 * | 3 - OS MODELOS NOVOS E ANTIGOS VÃO FICAR NA PASTA fpdf151/impmodelos/                 |
 * | 4 - COLABORE, ORGANIZE O CODIGO, SIGA O PADRÃO                                        |
 * |                                                                                       |
 * +=======================================================================================+
 * |DIRETORIO DOS MODELOS        ===>>> 	fpdf151/impmodelos/                              |
 * |PADRÃO PARA NOME DOS MODELOS ===>>>  mod_imprime<xx>.php ex: mod_imprime1.php          |
 * +=======================================================================================+
 *
 *  MODELO 1   - CARNES DE PARCELAMENTO
 *  MODELO 2   - RECIBO DE PAGAMENTO ( 2 VIAS )
 *  MODELO 3   - ALVARA NÃO DEFINIDO
 *  MODELO 4   - FICHA DE COMPENSACAO
 *  MODELO 5   - AUTORIZAÇÃO DE EMPENHO
 *  MODELO 6   - NOTA DE EMPENHO
 *  MODELO 7   - ORDEM DE PAGAMENTO
 *  MODELO 8   - FICHA DE TRANSFERENCIA DE BENS
 *  MODELO 9   - ALVARÁ DE LOCALIZAÇÃO METADE A4
 *  MODELO 10  - ORDEM DE COMPRA
 *  MODELO 11  - SOLICITAÇÃO DE COMPRA  Itens/Dotaïções
 *  MODELO 12  - ANULAÇÃO DE EMPENHO
 *  MODELO 13  - SOLICITAÇÃO DE ORÇAMENTO
 *  MODELO 14  - AIDOF
 *  MODELO 15  - ESTORNO DE PAGAMENTO
 *  MODELO 16  - CONTRA-CHEQUE 1
 *  MODELO 17  - SOLICITAï¿½ï¿½O DE COMPRA  Dotaï¿½ï¿½es/Itens
 *  MODELO 18  - REQUISIï¿½ï¿½O DE SAï¿½DA DE MATERIAIS
 *  MODELO 19  - EXTRATO DO RPPS
 *  MODELO 20  - ALVARA SANITARIO A4
 *  MODELO 21  - ALVARA SANITARIO METADE A4
 *  MODELO 22  - RECIBO DE PAGAMENTO ( 1 VIAS )
 *  MODELO 23  - ALVARA DE LICENSA PEQUENO
 *  MODELO 24  - ALVARA DE LICENSA GRANDE
 *  MODELO 25  - GUIA RECOLHIMENTO PREVIDENCIA
 *  MODELO 26  - ALVARA PRE IMPRESSO (BAGE)
 *  MODELO 27  - TERMO DE TRANSFERï¿½NCIA DE MATERIAIS(ALMOXARIFADO)
 *  MODELO 28  - Carne de IPTU parcela unica
 *  MODELO 29  - Certidï¿½o de isenï¿½ï¿½o
 *  MODELO 30  - CARNES DE PARCELAMENTO MODELO DETALHADO
 *  MODELO 31  - CARNES PARA DAEB
 *  MODELO 32  - CARNES PARA guaiba ainda naun liberado (TARCISIO
 *  MODELO 33  - S PRE-IMPRESSO MODELO DE BAGE
 *  MODELO 34  - TERMO DE RESCISAO
 *  MODELO 35  - ALVARA DE LICENCA GRANDE (modelo alternativo para carazino)
 *  MODELO 36  - SLIP ANTIGO - 2 PARTES
 *  MODELO 37  - SLIP NOVO - 1 PARTE - COM ASSINATURAS
 *  MODELO 38  - SLIP NOVO MODELO 2 DE OSORIO - SEMELHANTE AO SISTEMA ANTIGO DELES - 1 PARTE - COM ASSINATURAS
 *  MODELO 381 - SLIP - IGUAL AO MODELO 2, POREM IMPRIME OS RECURSOS
 *  MODELO 39  - NOTA DE LIQUIDAÇÃO DE EMPENHO
 *  MODELO 40  - CAPA DO PROCESSO DE PROTOCOLO MODELO PADRÃO
 *  MODELO 41  - CAPA DO PROCESSO DE PROTOCOLO MODELO 1 - ALEGRETE
 *  MODELO 42  - CAPA DO PROCESSO DE PROTOCOLO MODELO 2 - OSORIO
 *  MODELO 43  - ANULAR DE DESPESA (ESTORNO DE PAGAMENTO)
 *  MODELO 44  - ANULAR DE RECEITA (ESTORNO DE RECEITA)
 *  MODELO 45  - RECIBO DE ITBI MODELO PADRAO
 *  MODELO 46  - COMPROVANTE DE RENDIMENTOS
 *  MODELO 47  - GUIA ITBI COM FICHA DE COMPENSAÇÃO
 *  MODELO 48  - FICHA DE COMPENSAÇÃO
 *  MODELO 49  - NOTA FISCAL AVULSA
 *  MODELO 50  - ALVARA PRE-IMPRESSO TAMANHO A4
 *  MODELO 51  -
 *  MODELO 52  -
 *  MODELO 53  -
 *  MODELO 54  -
 *  MODELO 55  -
 *  MODELO 56  - CAPA DO CARNE DE IPTU OU ISSQN
 *  MODELO 57  -
 *  MODELO 58  -
 *  MODELO 59  - ALVARA PRE-IMPRESSO TAMANHO A4 (codigo cnae ao inves de atividades secundarias)
 *  MODELO 60  -
 *  MODELO 61  -
 *  MODELO 62  -
 *  MODELO 63  -
 *  MODELO 64  -
 *  MODELO 65  -
 *  MODELO 66  - FICHA COMPENS. MARICÁ
 *  MODELO 666 - FICHA COMPENS. MARICÁ
 *  MODELO 67  -
 *  MODELO 68  -
 *  MODELO 69  -
 *  MODELO 70  -
 *  MODELO 71  -
 *  MODELO 72  -
 *  MODELO 73  -
 *  MODELO 74  -
 *  MODELO 75  -
 *  MODELO 76  -
 *  MODELO 77  -
 *  MODELO 78  - EMPENHOS FOLHA
 *
 */
class db_impcarne extends cl_assinatura {

/////   VARIÁVEIS PARA EMISSAO DE CARNES DE PARCELAMENTO - MODELO 1
  var $imprimecapa  = null;
  var $mod_rodape   = 1;
  var $modelo       = 1;
  var $qtdcarne     = null;
  var $dtparapag    = null;
  var $confirmdtpag = 'f';

  var $tipodebito= 'TIPO DE DÉBITO';
  var $tipoinscr1= null;
  var $prefeitura= 'PREFEITURA DBSELLER';
  var $secretaria= 'SECRETARIA DE FAZENDA';
  var $debito    = null;
  var $logo      = null;
  var $motivo    = null;
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

  var $titulo17  = null;
  var $titulo18  = null;
  var $descr17   = null;
  var $descr18   = null;

  var $linha_digitavel = null;
  var $codigo_barras   = null;
  var $objpdf          = null;
  var $impmodelo       = null;
  var $linhadigitavel  = null;

  var $arraycodreceitas    = null;
  var $arrayreduzreceitas  = null;
  var $arraydescrreceitas  = null;
  var $arrayvalreceitas    = null;
  var $arraycodhist        = null;
  var $arraycodtipo        = null;

  var $tipo_convenio       = null;

  var $atualizaquant       = true;

  // MODELO 56  - CAPA DO CARNE DE IPTU OU ISSQN
  var $cepcapa  = null;
  var $dados1   = null;
  var $dados2   = null;
  var $dados3   = null;
  var $dados4   = null;
  var $dados5   = null;
  var $dados6   = null;
  var $dados7   = null;
  var $dados8   = null;
  var $dados9   = null;
  var $dados10  = null;
  var $dados11  = null;
  var $dados12  = null;
  var $dados13  = null;

//////  VARIï¿½VEIS PARA EMISSAO DE CONTRA-CHEQUES

  var $lotacao     	    = null;
  var $descr_lota  	    = null;
  var $funcao      	    = null;
  var $descr_funcao	    = null;
  var $mensagem    	    = null;
  var $recordenvelope  	= 0;
  var $linhasenvelope 	= 0;
  var $quantidade	      = null;
  var $valor		        = null;
  var $tipo		          = null;
  var $rubrica		      = null;
  var $descr_rub	      = null;

//////  VARIÁVEIS PARA EMISSAO DE RECIBO DE PAGAMENTO - MODELO 2
  var $cgccpf           = null;
  var $identifica_dados = "";
  var $enderpref        = null;
  var $cgcpref          = null;
  var $tipocompl        = null;
  var $tipolograd       = null;
  var $tipobairro       = null;
  var $municpref        = null;
  var $telefpref        = null;
  var $faxpref          = null;
  var $emailpref        = null;
  var $nome             = null;
  var $ender            = null;
  var $compl            = null;
  var $munic            = null;
  var $uf               = null;
  var $fax              = null;
  var $contato          = null;
  var $cep              = null;
  var $tipoinscr        = 'Matr/Inscr';
  var $nrinscr          = null;
  var $nome_fantasia    = null;
  var $nrinscr1         = null;
  var $ip               = null;
  var $nomepri          = '';
  var $nomepriimo       = '';
  var $nrpri            = '';
  var $complpri         = '';
  var $bairropri        = null;
  var $datacalc         = null;
  var $taxabanc         = 0;
  var $rowspagto        = 0;
  var $receita          = null;
  var $receitared       = null;
  var $dreceita         = null;
  var $ddreceita        = null;
  var $historico        = null;
  var $histparcel       = null;
  var $recorddadospagto = 0;
  var $linhasdadospagto = 0;
  var $dtvenc           = null;
  var $numpre           = null;
  var $valtotal         = null;

//////  VARIÁVEIS PARA EMISSAO DE ALVARÁ

  var $tipoalvara     = null;
  var $obs            = null;
  var $ativ           = null;
  var $numbloco       = null;
  var $descrativ      = null;
  var $outrasativs    = null;
  var $aCodigosCnae   = null;
  var $iAtivPrincCnae = null;
  var $q02_memo       = null;
  var $numero         = null;
  var $q02_obs        = null;
  var $q03_atmemo     = null; // obs das atividades
  var $obsativ        = null; // obs da atividade principal
  var $processo       = null;
  var $datainc        = null;
  var $datafim        = null;
  var $dtiniativ      = null; // data de inicio das atividades
  var $dtfimativ      = null; // data de fim das atividades
  var $impdatas       = null; // se imprime as datas de inicio e fim das atividades
  var $impobsativ     = null; // se imprime a observasï¿½o das atividades
  var $impcodativ     = null; // se imprime o codigo das atividades
  var $impobslanc     = null; // se imprime a observaï¿½ï¿½o do lanï¿½amento
  var $permanente     = null; // se permanente ou provisorio
  var $cnpjcpf        = null;
  var $assalvara      = null; // assinatura do alvara
  var $lancobs        = null; // observaï¿½ï¿½o do lanï¿½amento do alvara de sanitario


//////  FICHA DE COMPENSACAO

  var $numbanco		    = '';
  var $bairrocontri	    = '';
  var $localpagamento   = '';
  var $cedente		    = '';
  var $agencia_cedente	= '';
  var $data_documento	= '';
  var $numero_documento = '';
  var $especie_doc	    = '';
  var $aceite		    = '';
  var $data_processamento = '';
  var $nosso_numero  	= '';
  var $codigo_cedente	= '';
  var $codigo_rua	= '';
  var $carteira		    = '';
  var $especie		    = '';
  var $valor_documento	= '';
  var $instrucoes1  	= '';
  var $instrucoes2	    = '';
  var $instrucoes3  	= '';
  var $totaldesc      = 0;
  var $totalrec       = 0;
  var $totalacres     = 0;
  var $instrucoes4  	= '';
  var $instrucoes5	    = '';
  var $desconto_abatimento = '';
  var $outras_deducoes	= '';
  var $mora_multa	    = '';
  var $outros_acrecimos	= '';
  var $valor_cobrado	= '';
  var $sacado1		    = '';
  var $sacado2		    = '';
  var $sacado3		    = '';
  //var $dtparapag        = '';
  //var $descr10          = '';
  var $uf_config        = '';

//// vairaveis para o orcamento
  var $orccodigo        = '';
  var $orcdtlim         = '';
  var $orchrlim         = '';
  var $faxforne         = '';
  var $imagemlogo = '';



//// variaveis para a solicitaï¿½ï¿½o de compras
  var $secfaz           = null;  //Nome do secretï¿½rio da fazenda
  var $nompre           = null;  //Nome do prefeiro

  var $fonedepto        = null;
  var $faxdepto         = null;
  var $ramaldepto       = null;
  var $emaildepto       = null;

  // solicita
  var $Snumero          = null;  //nï¿½mero da solicitaï¿½ï¿½o
  var $Snumero_ant      = null;  //nï¿½mero da solicitaï¿½ï¿½o
  var $Sdata            = null;  //data da solicitaï¿½ï¿½o
  var $Svalor           = null;  //valor aproximado da solicitaï¿½ï¿½o
  var $Sorgao           = null;  //orgï¿½o
  var $Sunidade         = null;  //unidade
  var $sabrevunidade    = null;  //unidade abreviada
  var $Sresumo          = '';    //resumo da solicitaï¿½ï¿½o
  var $Stipcom          = '';    //tipo de compra da solicitaï¿½ï¿½o
  var $Sdepart          = '';    //departamento da solicitaï¿½ï¿½o
  var $Srespdepart      = '';    //responsï¿½vel pelo departamento
  var $Susuarioger      = '';    //Usuï¿½rio que gerou a solicitaï¿½ï¿½o

  var $cod_concarpeculiar   = null;  // Codigo da caracteristica peculiar
  var $descr_concarpeculiar = null;  // Descricao da caracteristica peculiar

  var $Scoddepto        = '';    //responsï¿½vel pelo departamento
  var $Sdescrdepto      = '';    //responsï¿½vel pelo departamento
  var $Snumdepart       = '';    //responsï¿½vel pelo departamento
  var $linhasdosdepart  = '';    //responsï¿½vel pelo departamento
  var $resultdosdepart  = '';    //responsï¿½vel pelo departamento

  // solicitem
  var $scodpcmater      = null;  //codigo do pcmater (quando for informado)
  var $scodunid         = null;  //codigo da unidade do item
  var $squantunid       = null;  //quantidade de cada unidade (caixa com 10 unidades)
  var $sprazo           = '';    //prazo de entrega do item
  var $spgto            = '';    //condiï¿½ï¿½es de pagamento do item
  var $sresum           = '';    //resumo do item
  var $sjust            = '';    //justificativa para a compra do item
  var $sunidade         = '';    //unidade (caixa,unitï¿½rio, etc...)
  var $sservico         = '';    //se ï¿½ serviï¿½o ou material
  var $svalortot        = '';    //valor total (quantidade * valor)
  var $susaquant        = '';    //se usa a quantidade ex. caixa (usa quant),unitï¿½rio(nï¿½o usa)
  var $selemento        = '';    //elemento do item da solicitaï¿½ï¿½o
  var $sdelemento       = '';    //descriï¿½aï¿½ do elemento do item da solicitaï¿½ï¿½o

  // pcdotac
  var $dcodigo          = null;  //cï¿½digo da dotaï¿½ï¿½o
  var $dcoddot          = null;  //cï¿½digo da dotaï¿½ï¿½o
  var $danousu          = null;  //ano da dotaï¿½ï¿½o
  var $dquant           = null;  //quantidade do item na dotaï¿½ï¿½o
  var $dvalor           = null;  //valor da dotaï¿½ï¿½o
  var $delemento        = '';    //elemento da dotaï¿½ï¿½o
  var $dvalortot        = '';    //valor total (quantidade * valor)
  var $dreserva         = '';    //se o valor da dotaï¿½ï¿½o foi reservado
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

  //labels dos itens do processo do orï¿½amento do processo de compras e orï¿½amento de solicitaï¿½ï¿½o
  var $labtitulo        = '';         // se ï¿½ orï¿½amento de solicitaï¿½ï¿½o ou PC
  var $labdados         = '';         // se ï¿½ orï¿½amento de solicitaï¿½ï¿½o ou PC
  var $labsolproc       = '';         // cï¿½digo do orï¿½amento ou solicitaï¿½ï¿½o
  var $labtipo          = '';         // se for solicitaï¿½ï¿½o, label do tipo
  var $declaracao       = "";         // Usado para imprimir declaracao no orï¿½amento (OSORIO)

//// variaveis para a autorizaï¿½ï¿½o de empenho E ORDEM DE COMPRA
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
  var $subfuncao       = null;     // codigo da subfuncao
  var $descr_subfuncao = null;   // descricao da subfuncao
  var $programa        = null;     // codigo do programa
  var $descr_programa  = null;   // descricao do programa
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
  var $destino		= null;		// destino do material ou serviï¿½o
  var $resumo		= null;		// destino do material ou serviï¿½o
  var $licitacao  	= null;		// tipo de licitaï¿½ï¿½o
  var $num_licitacao  	= null;		// numero da licitaï¿½ï¿½o
  var $descr_licitacao 	= null;		// descriï¿½ï¿½o do tipo de licitaï¿½ï¿½o
  var $descr_tipocompra	= null;		// descriï¿½ï¿½o do tipo de compra
  var $prazo_ent  	= null;		// prazo de entrega
//  var $obs		= null;		// observaï¿½ï¿½es
  var $cond_pag		= null;		// condiï¿½ï¿½es de pagamento
  var $out_cond		= null;		// outras condiï¿½ï¿½es de pagamento
  var $telef_cont 	= null;		// telefone do contato
  var $recorddositens 	= null;		// record set dos itens
  var $linhasdositens 	= null;		// numero de itens da autorizacao
  var $item	    	= null;		// codigo do item
  var $quantitem    	= null;		// quantidade do item
  var $valoritem    	= null;		// valor unitï¿½rio do item
  var $empempenho       = null;         // cod empenho para emissï¿½o de ordem de compra
  var $dataordem        = null;         // data da geraï¿½ï¿½o da ordem de compra
  var $observacaoitem   = null;
  var $descricaoitem    = null;
  var $ordpag		= null;		// numero da ordem de pagamento
  var $elemento		= null;		// elemento da despesa
  var $descr_elemento	= null;		// descriï¿½ï¿½o do elemento da despesa
  var $elementoitem	= null;		// elemento do item da ordem de pagamento
  var $descr_elementoitem= null;	// descriï¿½ï¿½o do elemento do item da ordem de pagamento
  var $outrasordens     = null;		// saldo das outras ordens de pagamento do empenho
  var $vlrrec           = null;		// valor das receitas de retenï¿½oes
  var $cnpj             = null;         // cpf ou cnpj do credor
  var $anulado		= null;         // valor anulado
  var $vlr_anul         = null;         // valor anulado
  var $data_est         = null;         // data estorno
  var $descr_anu        = null;         // descriï¿½ï¿½o da anulaï¿½ï¿½o
  var $Scodemp          = null;         // descriï¿½ï¿½o da anulaï¿½ï¿½o
  var $resumo_item      = null;         // resumo de item de SC em aut. de licitaï¿½ï¿½o
  var $informa_adic     = null;         // informaï¿½ï¿½es adicionais de autorizaï¿½ï¿½o: PC - aut. de processo de compras
                                        //                                        AU - somente autorizaï¿½ï¿½o
  var $obs_ordcom_orcamval = null;      // Observacao de ordem de compra lanï¿½a valores

// Variï¿½veis necessï¿½rias para requisiï¿½ï¿½o de saï¿½da de materiais
  var $Rnumero          = null;
  var $Ratendrequi      = null;
  var $Rdata		        = null;
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

// VARIAVEIS PARA EMISSï¿½O DO CARNE DE IPTU PARCELA UNICA guaiba

    var $iptj23_anousu      = '';
    var $iptz01_nome        = '';
    var $iptz01_numcgm      = '';
    var $iptz01_cgccpf      = '';
    var $iptz01_munic       = '';
    var $iptz01_cidade      = '';
    var $iptz01_bairro      = '';
    var $iptbairroimo       = '';
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
    var $iptcodpri          = '';
    var $iptproprietario    = '';
    var $iptuvlrdesconto    = '';
    var $iptbql             = '';
    var $iptcodigo_barras   = '';
    var $iptlinha_digitavel = '';
    var $iptdataemis        = '';
    var $iptprefeitura      = '';
    var $iptdebant          = '';
    var $iptsubtitulo       = '';

// VARIAVEIS PARA EMISSï¿½O DA CERTIDï¿½O DE ISENï¿½ï¿½O

    var $isenmatric      = '';
    var $isennome        = '';
    var $isencgc         = '';
    var $isenender       = '';
    var $isenbairro      = '';
    var $isendtini       = '';
    var $isendtfim       = '';
    var $isenproc        = '';
    var $isenmsg1        = '';
    var $isenmsg2        = '';
    var $isenmsg3        = '';
    var $isenassinatura  = '';
    var $isenassinatura2 = '';
    var $isenprefeitura  = '';
    var $isensetor       = '';
    var $isenquadra      = '';
    var $isenlote        = '';
  	var $cabec_sec		 = '';

// VARIAVEIS PARA EMISSAO DE CARNE PRE-IMPRESSO MODELO DE BAGE

var $predescr3_1         = "";  // contribuinte
var $predescr3_2         = "";  // endereco
var $premunic            = "";  // municipio
var $precep              = "";  // cep
var $precgccpf           = "";  // cgccpf
var $predatacalc         = "";  // data do recibo
var $pretitulo8          = "";  // titulo matricula ou inscricao
var $predescr8           = "";  // descr matricula ou inscricao
var $pretipolograd       = "";  // titulo do logradouro
var $prenomepri          = "";  // nome do logradouro
var $prenrpri            = "";
var $precomplpri         = "";  // numero e complemento
var $prebairropri        = "";  // nome do bairro
var $pretipobairro       = "";  //
var $pretipocompl        = "";
var $pretipodebito       = "";
var $prehistoricoparcela = "";
var $predescr4_2         = "";
var $predescr16_1        = "";
var $predescr16_2        = "";
var $predescr16_3        = "";
var $premsgunica         = "";
var $predescr6           = "";  // Data de Vencimento
var $predescr7           = "";  // qtd de URM ou valor
var $predescr9           = "";


var $loteamento         = "";

// 	VARIAVEIS PARA GUIA ITBI
var $z01_nome           ="";
var $logoitbi           = "";
var $nomeinst           = "";
var $tipoitbi           = "";
var $datavencimento     = "";
var $it04_descr         = "";
var $numpreitbi         = "";
//var $ano                = "";
var $itbi               = "";
var $nomecompprinc      = "";
var $outroscompradores  = "";
var $z01_cgccpf         = "";
var $cgccpfcomprador    = "";
var $z01_ender          = "";
var $z01_bairro         = "";
var $enderecocomprador  = "";
var $numerocomprador    = "";
var $complcomprador     = "";
var $z01_munic          = "";
var $z01_uf             = "";
var $z01_cep            = "";
var $municipiocomprador = "";
var $ufcomprador        = "";
var $cepcomprador       = "";
var $bairrocomprador    = "";
var $it06_matric        = "";
var $j39_numero         = "";
var $j34_setor          = "";
var $j34_quadra         = "";
var $matriz             = "";
var $j34_lote           = "";
var $j13_descr          = "";
var $j14_tipo           = "";
var $j14_nome           = "";
var $it07_descr         = "";
var $it05_frente        = "";
var $it05_fundos        = "";
var $it05_esquerdo      = "";
var $it05_direito       = "";
var $it18_frente        = "";
var $it18_fundos        = "";
var $it18_prof          = "";
var $areaterreno        = "";
var $areatran           = "";
var $areaterrenomat     = "";
//var $areatotal= "";
var $areaedificadamat   = "";
var $areatotal          = "";
//var $areaedificadamat= "";
var $areatrans          = "";
var $arrayj13_descr     = "";
var $arrayj13_valor     = "";
var $linhasresultcons   = "";
var $arrayit09_codigo   = "";
var $arrayit10_codigo   = "";
var $arrayit08_area     = "";
var $arrayit08_areatrans= "";
var $arrayit08_ano      = "";
var $tx_banc            = "";
var $propri             = "";
var $proprietarios      = "";
var $it14_valoravalter  = "";
var $it14_valoravalconstr= "";
var $it14_valoraval     = "";
var $it14_valoravalterfinanc= "";
var $it14_valoravalconstrfinanc= "";
var $it14_valoravalfinanc= "";
var $it01_valortransacao= "";
var $it04_aliquotafinanc= "";
var $it04_aliquota      = "";
var $it14_desc          = "";
var $it14_valorpaga     = "";
//var $arrayj13_descr     = "";
var $arrayit19_valor    = "";
var $it01_data          = "";
var $linhasitbiruralcaract= "";
var $outrostransmitentes="";
var $it01_obs           ="";

// VARIAVEIS DA CAPA DE PROCESSO
var $result_vars;


var $lUtilizaModeloDefault = true;

  /**
   * Variáveis de controle da marca d'agua
   */
  private $lWaterMark = false;
  private $aWaterMark = array();

  /**
   * Limpa os dados da marca d'agua
   */
  public function clearWaterMark() {

    $this->lWaterMark = false;
    $this->aWaterMark = array();
  }

  /**
   * Seta uma marca d'agua
   *
   * @param integer $x - Posição inicial
   * @param integer $y - Posição inicial
   * @param string  $sTexto - Texto a ser impresso
   * @param float   $nDirection - Ângulo de inclinação do texto
   * @param integer $iFontSize - Tamanho da fonte
   * @param integer $iFillColor - Cor de preenchimento
   */
  public function setWaterMark($x, $y, $sTexto, $nDirection, $iFontSize = 150, $iFillColor = 178) {

    $this->lWaterMark = true;
    $this->aWaterMark = array(
        'x' => $x,
        'y' => $y,
        'text' => $sTexto,
        'direction' => $nDirection,
        'font' => $iFontSize,
        'fillcolor' => $iFillColor
      );
  }

  /**
   * Imprime a marca d'agua na página atual
   */
  public function printWaterMark() {

    if (!$this->lWaterMark) {
      return false;
    }

    $this->objpdf->SetFont('Arial', 'B', $this->aWaterMark['font']);
    $this->objpdf->SetFillColor($this->aWaterMark['fillcolor']);
    $this->objpdf->TextWithRotation($this->aWaterMark['x'], $this->aWaterMark['y'], $this->aWaterMark['text'], $this->aWaterMark['direction']);
  }

//************************************************************//

// variaveis para a nota de empenho
/**
 * Construtor da classe
 * @param object  $objpdf    - Instancia da classe FPDF
 * @param integer $impmodelo - Numero do Modelo a ser utilizado.
 */
  function __construct(&$objpdf,$impmodelo){
    $this->objpdf = $objpdf;
    $this->impmodelo = $impmodelo;
  }
  function muda_pag($pagina, $xlin, $xcol, $fornec="false", &$contapagina, $mais=1, $mod=1) {
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

//    echo "$valor_da_posicao_atual > $valor_do_tamanho_pagin <br>";
    $valor_do_tamanho_pagin = $valor_do_tamanho_pagin-20;
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
               	     where db03_tipodoc = 1400 and db03_instit = cast(" . db_getsession("DB_instit")." as integer) order by db04_ordem ";

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
               	               where db60_tipodoc = 1400 and db60_instit = cast(" . db_getsession("DB_instit")." as integer) order by db62_ordem";

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

      if ($this->lWaterMark) {
        $this->printWaterMark();
      }

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

      $lImprimeTipo = false;

      if( !empty($this->StipoSolicitacao) ) {

        $sDescricaoTipo   = 'SOLICITAÇÃO DE COMPRA N';
        $iLicitacaoTipo   = substr($this->StipoSolicitacao, 0, 1);

        switch ($iLicitacaoTipo) {

          case '3':
          case '4':
          case '6':

            $sDescricaoTipo   = substr($this->StipoSolicitacao, 1, 40);
            $sDescricaoTipo   = mb_convert_case(str_replace('ã','Ã',$sDescricaoTipo), MB_CASE_UPPER, "ISO-8859-1");
            $sDescricaoTipo   = mb_convert_case(str_replace('ç','Ç',$sDescricaoTipo), MB_CASE_UPPER, "ISO-8859-1");
            $sRodapeCabecalho = 'SOLICITAÇÃO DE COMPRA N'.CHR(176);
            $lImprimeTipo     = true;
            break;

          default:break;
        }

        if ($lImprimeTipo) {
          $this->objpdf->text(130,$xlin-13, $sDescricaoTipo);
        } else {

          $this->objpdf->text(130,$xlin-13,'SOLICITAÇÃO DE COMPRA N'.CHR(176));
          $this->objpdf->text(185,$xlin-13,db_formatar($this->Snumero,'s','0',6,'e'));
        }
      }

      $this->objpdf->Setfont('Arial','B',7);
      $this->objpdf->text(130,$xlin-9,'ORGAO');
      $this->objpdf->text(142,$xlin-9,': '.substr($this->Sorgao,0,35));
      $this->objpdf->text(130,$xlin-5,'UNIDADE');
      $this->objpdf->text(142,$xlin-5,': '.substr($this->Sunidade,0,35));

      if ($lImprimeTipo) {

        $this->objpdf->text(130, $xlin - 2,'SOLICITAÇÃO DE COMPRA N'.CHR(176));
        $this->objpdf->text(185, $xlin - 2,db_formatar($this->Snumero,'s','0',6,'e'));

      }

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

        if ($mod == 1) {

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
           // Caixa dos materiais ou servicos
           $this->objpdf->rect($xcol+ 44,$xlin+30,98,262,2,'DF','34');
           // Caixa dos valores unitario
           $this->objpdf->rect($xcol+142,$xlin+30,30,262,2,'DF','');
           // Caixa dos valores totais dos iten
           $this->objpdf->rect($xcol+172,$xlin+30,30,262,2,'DF','34');

           $this->objpdf->sety($xlin+66);
           $alt = 4;

           $this->objpdf->Setfont('Arial','B',8);
           $this->objpdf->text($xcol+   2,$xlin+28,'ITEM');
           $this->objpdf->text($xcol+  11,$xlin+28,'QUANT');
           $this->objpdf->text($xcol+  30,$xlin+28,'REF');
           $this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVIÇO');
           $this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNITÁRIO');
           $this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');

           $maiscol = 0;
           $xlin = 20;
           // Seta altura nova para impressão dos dados
           $this->objpdf->sety($xlin+11);
           $this->objpdf->setleftmargin(3);
           $x = true;
           $this->objpdf->Setfont('Arial','',7);

        } else {

          	// Caixas dos label's
        	 $this->objpdf->rect($xcol    , $xlin + 32, 10, 6, 2, 'DF', '12');
           $this->objpdf->rect($xcol +10, $xlin + 32, 30, 6, 2, 'DF', '12');
           $this->objpdf->rect($xcol +40, $xlin + 32, 25, 6, 2, 'DF', '12');
           $this->objpdf->rect($xcol +65, $xlin + 32, 107,6, 2, 'DF', '12');
           $this->objpdf->rect($xcol +172,$xlin + 32, 30, 6, 2, 'DF', '12');

        	 $menos = 0;
        	 $getdoy = 32;

           $this->objpdf->rect($xcol     , $xlin + 32, 10, 262, 2, 'DF', '34');
           $this->objpdf->rect($xcol +10 , $xlin + 32, 30, 262, 2, 'DF', '34');
           $this->objpdf->rect($xcol +40 , $xlin + 32, 25, 262, 2, 'DF', '34');
           $this->objpdf->rect($xcol +65 , $xlin + 32, 107, 262,2, 'DF', '34');
           $this->objpdf->rect($xcol +172, $xlin + 32, 30, 262, 2, 'DF', '34');

           $this->objpdf->sety($xlin +28);

           // Label das colunas
           $this->objpdf->Setfont('Arial', 'B', 8);
           $this->objpdf->text($xcol +2, $xlin + $getdoy +4, 'ITEM');
           $this->objpdf->text($xcol +14, $xlin + $getdoy +4, 'QUANTIDADES');
           $this->objpdf->text($xcol +50, $xlin + $getdoy +4, 'REF');
           $this->objpdf->text($xcol +105, $xlin + $getdoy +4, 'MATERIAL OU SERVIÇO');
           $this->objpdf->text($xcol +176, $xlin + $getdoy +4, 'VALOR TOTAL');

           $maiscol = 0;
           $xlin = 20;
           // Seta altura nova para impressão dos dados
           $this->objpdf->sety($xlin+20);
           $this->objpdf->setleftmargin(3);
           $x = true;
           $this->objpdf->Setfont('Arial','',7);

        }

      }else if(isset($fornec) && $fornec=="true"){
      }

    }
    return $x;
  }

  function muda_pag2($pagina,$xlin,$xcol,&$contapagina,$mais=1,$linha){
    global $resparag, $resparagpadrao, $db61_texto, $db02_texto, $maislin, $xtotal, $flag_rodape;

    $x = false;

    $valor_da_posicao_atual = $this->objpdf->gety();
    $valor_da_posicao_atual+= ($mais);
    $valor_da_posicao_atual = (int)$valor_da_posicao_atual;


    $valor_do_tamanho_pagin = $this->objpdf->h;
    $valor_do_tamanho_pagin-= 60;
    $valor_do_tamanho_pagin = (int)$valor_do_tamanho_pagin;

    $valor_do_tamanho_mpagi = $this->objpdf->h;
    $valor_do_tamanho_mpagi-= 30;
    $valor_do_tamanho_mpagi = (int)$valor_do_tamanho_mpagi;


    if ((($valor_da_posicao_atual > $valor_do_tamanho_pagin) && $contapagina == 1 ) ||  (($valor_da_posicao_atual > $valor_do_tamanho_mpagi) && $contapagina != 1)) {

    	$this->objpdf->text(111.2,$xlin+240,'Continua na Página '.($contapagina+1));

      if ($contapagina == 1) {
        $this->objpdf->Setfont('Arial','B',7);
        $sqlparag = "select db02_texto
                     from db_documento
                          inner join db_docparag on db03_docum = db04_docum
                          inner join db_tipodoc on db08_codigo  = db03_tipodoc
                          inner join db_paragrafo on db04_idparag = db02_idparag
                     where db03_tipodoc = 1202 and db03_instit = cast(" . db_getsession("DB_instit")." as integer) order by db04_ordem ";

        $resparag = @db_query($sqlparag);

        if (@pg_numrows($resparag) > 0) {
            db_fieldsmemory($resparag,0);

            eval($db02_texto);
            $flag_rodape = true;
        } else {
            $sqlparagpadrao = "select db61_texto
                               from db_documentopadrao
                                    inner join db_docparagpadrao  on db62_coddoc   = db60_coddoc
                                    inner join db_tipodoc         on db08_codigo   = db60_tipodoc
                                    inner join db_paragrafopadrao on db61_codparag = db62_codparag
                               where-30 db60_tipodoc = 1202 and db60_instit = cast(" . db_getsession("DB_instit")." as integer) order by db62_ordem";

            $resparagpadrao = @db_query($sqlparagpadrao);

            if (@pg_numrows($resparagpadrao) > 0) {
                db_fieldsmemory($resparagpadrao,0);

                eval($db61_texto);
                $flag_rodape = true;
            }
        }
      }
      $pagina += 1;
      $this->objpdf->addpage();

      $muda_pagina = true;
      $contapagina++;
      $this->objpdf->settopmargin(1);
      $this->objpdf->setleftmargin(4);
      $this->objpdf->sety(16);

      $xlin = 20;
      $xcol = 4;
      $dif = 0;

      $this->objpdf->setfillcolor(245);
      $this->objpdf->rect($xcol-2,$xlin-18,206,292,2,'DF','1234');

      $getlogo = db_getnomelogo();
      $logo    = ($getlogo==false?'':$getlogo);

      // Imprime cabeï¿½alho com dados sobre a prefeitura se mudar de pï¿½gina
      $this->objpdf->setfillcolor(255,255,255);
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->Image('imagens/files/'.$this->logo,15,$xlin-17,12);
      $this->objpdf->text(130,$xlin-15,"ORÇAMENTO N".CHR(176));
      $this->objpdf->text(185,$xlin-15,db_formatar($this->orccodigo,'s','0',6,'e'));
      $this->objpdf->text(130,$xlin-11,$this->labdados.CHR(176));
      $this->objpdf->text(185,$xlin-11,db_formatar($this->Snumero,'s','0',6,'e'));
      $this->objpdf->Setfont('Arial','',7);
      $this->objpdf->text(130,$xlin- 8,"Departamento");
      $this->objpdf->text(130,$xlin- 5,"Fone / Ramal");
      $this->objpdf->text(130,$xlin- 2,"Fax");
      $this->objpdf->text(146,$xlin- 8,":".$this->coddepto);
      $this->objpdf->text(151,$xlin- 8,"-".$this->Sdepart);
      $this->objpdf->text(146,$xlin- 5,": ".$this->fonedepto." / ".$this->ramaldepto);
      $this->objpdf->text(146,$xlin- 2,": ".$this->faxdepto);
      $this->objpdf->text(130,$xlin+ 1,$this->emaildepto);
      $this->objpdf->text(195,$xlin+ 1,"Página ".$this->objpdf->PageNo());
      $this->objpdf->Setfont('Arial','B',9);
      $this->objpdf->text( 40,$xlin-15,$this->prefeitura);
      $this->objpdf->Setfont('Arial','',7);
      $this->objpdf->text( 40,$xlin-11,$this->enderpref);
      $this->objpdf->text( 40,$xlin- 7,$this->municpref);
      $this->objpdf->text( 40,$xlin- 3,$this->emailpref);
      $this->objpdf->text( 40,$xlin+ 1,"CNPJ:" .db_formatar($this->cgcpref,'cnpj'));

      $this->objpdf->Setfont('Arial','B',8);
      $dif = 10;

      // Caixas dos label's
      $this->objpdf->rect($xcol    ,22,12, 6,2,'DF','12');
      $this->objpdf->rect($xcol+ 12,22,15, 6,2,'DF','12');
      $this->objpdf->rect($xcol+ 27,22,113,6,2,'DF','12');
      $this->objpdf->rect($xcol+140,22,24, 6,2,'DF','12');
      $this->objpdf->rect($xcol+164,22,19, 6,2,'DF','12');
      $this->objpdf->rect($xcol+183,22,19, 6,2,'DF','12');

      $this->objpdf->rect($xcol,    22,12 ,$linha-$dif,2,'DF','34');
      $this->objpdf->rect($xcol+ 12,22,15 ,$linha-$dif,2,'DF','34');
      $this->objpdf->rect($xcol+ 27,22,113,$linha-$dif,2,'DF','34');
      $this->objpdf->rect($xcol+140,22,24 ,$linha-$dif,2,'DF','34');
      $this->objpdf->rect($xcol+164,22,19 ,$linha-$dif,2,'DF','34');
      $this->objpdf->rect($xcol+183,22,19 ,$linha-$dif,2,'DF','34');

      $this->objpdf->text($xcol+   2,$xlin+6,'SEQ');
      $this->objpdf->text($xcol+  13,$xlin+6,'QUANT');
      $this->objpdf->text($xcol+  56,$xlin+6,'MATERIAL OU SERVIÇO');
      $this->objpdf->text($xcol+ 145,$xlin+6,'MARCA');
      $this->objpdf->text($xcol+ 165,$xlin+6,'VLR UNIT.');
      $this->objpdf->text($xcol+ 184,$xlin+6,'VLR TOT.');

      // Seta altura nova para impressão dos dados
      $this->objpdf->sety($xlin+8);
      $this->objpdf->setleftmargin(3);
      $x = true;
      $this->objpdf->Setfont('Arial','',8);

    return $x;
  }
 }

 function muda_pag3($pagina, $xlin, $xcol, $fornec="false", &$contapagina, $mais=1, $mod=1) {
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
                	     where db03_tipodoc = 1400 and db03_instit = cast(" . db_getsession("DB_instit")." as integer) order by db04_ordem ";

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
                	               where db60_tipodoc = 1400 and db60_instit = cast(" . db_getsession("DB_instit")." as integer) order by db62_ordem";

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
     $this->objpdf->text(130,$xlin-13,'PROCESSO DE COMPRA N'.CHR(176));
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

       if ($mod == 1) {

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
         // Caixa dos materiais ou servicos
         $this->objpdf->rect($xcol+ 44,$xlin+30,98,262,2,'DF','34');
         // Caixa dos valores unitario
         $this->objpdf->rect($xcol+142,$xlin+30,30,262,2,'DF','');
         // Caixa dos valores totais dos iten
         $this->objpdf->rect($xcol+172,$xlin+30,30,262,2,'DF','34');

         $this->objpdf->sety($xlin+66);
         $alt = 4;

         $this->objpdf->Setfont('Arial','B',8);
         $this->objpdf->text($xcol+   2,$xlin+28,'ITEM');
         $this->objpdf->text($xcol+  11,$xlin+28,'QUANT');
         $this->objpdf->text($xcol+  30,$xlin+28,'REF');
         $this->objpdf->text($xcol+  70,$xlin+28,'MATERIAL OU SERVIÇO');
         $this->objpdf->text($xcol+ 145,$xlin+28,'VALOR UNITÁRIO');
         $this->objpdf->text($xcol+ 176,$xlin+28,'VALOR TOTAL');

         $maiscol = 0;
         $xlin = 20;
         // Seta altura nova para impressão dos dados
         $this->objpdf->sety($xlin+11);
         $this->objpdf->setleftmargin(3);
         $x = true;
         $this->objpdf->Setfont('Arial','',7);

       } else {

         // Caixas dos label's
         $this->objpdf->rect($xcol    , $xlin + 32, 10, 6, 2, 'DF', '12');
         $this->objpdf->rect($xcol +10, $xlin + 32, 30, 6, 2, 'DF', '12');
         $this->objpdf->rect($xcol +40, $xlin + 32, 25, 6, 2, 'DF', '12');
         $this->objpdf->rect($xcol +65, $xlin + 32, 107,6, 2, 'DF', '12');
         $this->objpdf->rect($xcol +172,$xlin + 32, 30, 6, 2, 'DF', '12');

         $menos = 0;
         $getdoy = 32;

         $this->objpdf->rect($xcol     , $xlin + 32, 10, 262, 2, 'DF', '34');
         $this->objpdf->rect($xcol +10 , $xlin + 32, 30, 262, 2, 'DF', '34');
         $this->objpdf->rect($xcol +40 , $xlin + 32, 25, 262, 2, 'DF', '34');
         $this->objpdf->rect($xcol +65 , $xlin + 32, 107, 262,2, 'DF', '34');
         $this->objpdf->rect($xcol +172, $xlin + 32, 30, 262, 2, 'DF', '34');

         $this->objpdf->sety($xlin +28);

         // Label das colunas
         $this->objpdf->Setfont('Arial', 'B', 8);
         $this->objpdf->text($xcol +2, $xlin + $getdoy +4, 'ITEM');
         $this->objpdf->text($xcol +14, $xlin + $getdoy +4, 'QUANTIDADES');
         $this->objpdf->text($xcol +50, $xlin + $getdoy +4, 'REF');
         $this->objpdf->text($xcol +105, $xlin + $getdoy +4, 'MATERIAL OU SERVIÇO');
         $this->objpdf->text($xcol +176, $xlin + $getdoy +4, 'VALOR TOTAL');

         $maiscol = 0;
         $xlin = 20;
         // Seta altura nova para impressão dos dados
         $this->objpdf->sety($xlin+20);
         $this->objpdf->setleftmargin(3);
         $x = true;
         $this->objpdf->Setfont('Arial','',7);

       }

     }else if(isset($fornec) && $fornec=="true"){
     }

   }
   return $x;
 }

 function imprime(){

 	$sSqlConfig  = "select db21_codcli from db_config where prefeitura is true";
 	$rsSqlConfig = db_query($sSqlConfig);
 	$sCodCliente = pg_fetch_object($rsSqlConfig,0);
 	$sCodCliente = $sCodCliente->db21_codcli;
 	$sCodCliente = str_pad($sCodCliente,6,0,STR_PAD_LEFT);
 	$sInstit     = str_pad(db_getsession("DB_instit"), 2,0,STR_PAD_LEFT);

  /** Extensao : Inicio [guia-itbi-recibo-mensagem-modelo-codcli15] */
  /** Extensao : Fim [guia-itbi-recibo-mensagem-modelo-codcli15] */

 	/**
 	 * Valida se existe modelo especifico
 	 */
 	if (file_exists("fpdf151/impmodelos/especificos/mod_imprime_especifico_{$this->impmodelo}_{$sCodCliente}{$sInstit}.php") ) {
 		include(modification(Modification::getFile("fpdf151/impmodelos/especificos/mod_imprime_especifico_{$this->impmodelo}_{$sCodCliente}{$sInstit}.php")));
 	}

 	/**
 	 * Valida se utiliza modelo default junto com especifico
 	 */
 	if ($this->lUtilizaModeloDefault) {

 	  include(modification(Modification::getFile("fpdf151/impmodelos/mod_imprime".$this->impmodelo.".php")));
 	}

 }
}
