<?php 
/**
 * Classe para processamento do arquivo de Imoveis no Recadastro imobiliario
 *
 * @uses     RecadastroImobiliarioImoveisInterface
 * @package  Recadastro Imobiliario
 * @author   Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 * @version  $Revision: 1.12 $ - $Author: dbrafael.nery $
 */
class RecadastroImobiliarioImoveisArquivo {

  private $oCabecalho;

  private $aRegistros = array();
  
  private $oRodape;
  
  private $aTabelas   = array();
  
  static public $oLog;
  
  private $pArquivo;

  private $sCaminhoArquivo;
  
  /**
   * Construtor da Classe
   * @param string $sCaminhoArquivo
   */
  public function __construct( $sCaminhoArquivo ) {

    $aConfiguracoes = (object)parse_ini_file(PATH_IMPORTACAO . "libs/configuracoes_importacao.ini",true);
    $this->sCaminhoArquivo       = $sCaminhoArquivo;
    $this->pArquivo              = fopen($sCaminhoArquivo, 'r');
    self::$oLog                  = new DBLog("TXT",PATH_IMPORTACAO . "log/log_imoveis_".str_ireplace("/", "_",$sCaminhoArquivo). date("Y_m_d"));
  }

  /**
   * Carrega arquivo txt para a memória
   * @return boolean
   */
  public function carregarConteudoArquivo() {

    $sLinhaCabecalho          = fgets($this->pArquivo);
    $oCabecalho               = new stdClass();
    $oCabecalho->iAnoArquivo  = (int)    trim(substr($sLinhaCabecalho, 0,  4));  
    $oCabecalho->iMesArquivo  = (int)    trim(substr($sLinhaCabecalho, 4,  2));  
    $oCabecalho->iDiaArquivo  = (int)    trim(substr($sLinhaCabecalho, 6,  2));  
    $oCabecalho->sNomeArquivo = (string) trim(substr($sLinhaCabecalho, 8, 92));  
    $oCabecalho->iTipoArquivo = 3;
    
    self::$oLog->escreverLog("Carregando registros do arquivo {$oCabecalho->sNomeArquivo}.", DBLog::LOG_INFO);

    $this->oCabecalho         = $oCabecalho;

    $iNumeroLinha = 0;
    
    while ($sLinhaRegistro = fgets($this->pArquivo)) {
      
      self::$oLog->escreverLog("Carregando registros da linha " . ++$iNumeroLinha . " do arquivo.", DBLog::LOG_INFO);

      if (!$this->validarUltimaLinhaArquivo($this->pArquivo)) {

        $aNomeArquivo = explode('/', $this->sCaminhoArquivo); 

        $oRegistro              = new stdClass();
        $oRegistro->sLinha      = $sLinhaRegistro;
        $oRegistro->sVersao     = substr($aNomeArquivo[count($aNomeArquivo) - 1], 0, 20);
        $oRegistro->lProcessado = 'false';

        $this->aRegistros[]     = $oRegistro;

      } else {

        $oRodape                       = new stdClass();
        $oRodape->iAnoArquivo          = (int)    trim(substr($sLinhaRegistro,  0, 4));
        $oRodape->iMesArquivo          = (int)    trim(substr($sLinhaRegistro,  4, 2));
        $oRodape->iDiaArquivo          = (int)    trim(substr($sLinhaRegistro,  6, 2));
        $oRodape->sIdentificador       = (string) trim(substr($sLinhaRegistro,  8, 6));
        $oRodape->iQuantidadeRegistros = (int)    trim(substr($sLinhaRegistro, 14, 6));
        
        $this->oRodape                 = $oRodape;
        
      }

    }
    
    return true;

  }
  
  /**
   * Salva registros do arquivo em tabelas
   * Retorna o código da importação 
   * @return integer 
   */
  public function salvar() {

    $this->carregarConteudoArquivo();

    $pConexao                      = Conexao::getInstancia()->getConexao();
    $oRecadastroImobiliarioArquivo = new tableDataManager($pConexao, "recadastroimobiliarioarquivos", "ie24_sequencial", false);
    $oRecadastroImobiliarioImoveis = new tableDataManager($pConexao, "recadastroimobiliarioimoveis" , "ie28_sequencial", false);

    $oDadosInclusaoArquivo                           = new stdClass();
    $oDadosInclusaoArquivo->ie24_nomearquivo         = $this->oCabecalho->sNomeArquivo;
    $oDadosInclusaoArquivo->ie24_tipoarquivo         = $this->oCabecalho->iTipoArquivo;
    $oDadosInclusaoArquivo->ie24_quantidaderegistros = $this->oRodape->iQuantidadeRegistros;

    $oDadosInclusaoArquivo->ie24_dataimportacao      = $this->oCabecalho->iAnoArquivo . '-' .
                                                       $this->oCabecalho->iMesArquivo . '-' .
                                                       $this->oCabecalho->iDiaArquivo       ;

    $oRecadastroImobiliarioArquivo->setByLineOfDBUtils($oDadosInclusaoArquivo);
    
    $iCodigoImportacao = $oRecadastroImobiliarioArquivo->insertValue();
    
    $oRecadastroImobiliarioArquivo->persist();
    
    self::$oLog->escreverLog("Salvando arquivo {$this->oCabecalho->sNomeArquivo} na base de dados do recadastro. Código da importação: {$iCodigoImportacao}" , DBLog::LOG_INFO);


    foreach ($this->aRegistros as $iChave => $oRegistro) {

      $oDadosInclusaoRegistros                                     = new stdClass();
      $oDadosInclusaoRegistros->ie28_recadastroimobiliarioarquivos = $iCodigoImportacao;
      $oDadosInclusaoRegistros->ie28_linhaarquivo                  = str_replace("\r\n", "", $oRegistro->sLinha);
      $oDadosInclusaoRegistros->ie28_versao                        = $oRegistro->sVersao;
      $oDadosInclusaoRegistros->ie28_processado                    = $oRegistro->lProcessado;
      $oDadosInclusaoRegistros->ie28_observacoes                   = '';
      
      
      $oRecadastroImobiliarioImoveis->setByLineOfDBUtils($oDadosInclusaoRegistros);
      
      $this->aRegistros[$iChave]->iCodigoRegistro = $oRecadastroImobiliarioImoveis->insertValue();
      
      self::$oLog->escreverLog("Salvando linha de código sequencial " . substr($oRegistro->sLinha,   8,   6) . " na base de dados do recadastro. Código da importação: {$iCodigoImportacao}" , DBLog::LOG_INFO);
      
    }
    self::$oLog->escreverLog("");
    self::$oLog->escreverLog("");
    self::$oLog->escreverLog("");
    $oRecadastroImobiliarioImoveis->persist();
    
    return true;
  }

  /**
   * Retorna verdadeiro quando for lida a última linha do arquivo 
   * @param ponteiro $pArquivo
   * @return boolean
   */
  public function validarUltimaLinhaArquivo($pArquivo) {

    $iPosicaoCorrente = ftell($pArquivo);
    $lSemLinhasApos   = fgets($pArquivo) ? false : true;
    fseek($pArquivo, $iPosicaoCorrente);
    
    return $lSemLinhasApos;

  }

  /**
   * Processa informações importando dados para o e-cidade
   * @throws Exception
   */
  public function processar() {
    
    /**
     * Retorna Intancia atual da conexao
     */
    $oConexao = Conexao::getInstancia();
    $oLog     = RecadastroImobiliarioImoveisArquivo::$oLog;
    $oLog->escreverLog("|-------------------------- Início do Processamento --------------------------|"); 
    
    $oConexao->begin();

    if ( $this->salvar() ) {
    
      $oConexao->end(Conexao::COMMIT);

      $oBarraProgresso = new BarraProgressoCli( count($this->aRegistros) );
      
      foreach ($this->aRegistros as $oRegistro) {

        $oBarraProgresso->atualizar();
        $oDadosRegistros                  = self::getDadosLinha($oRegistro->sLinha, $this->oCabecalho->sNomeArquivo);
        $oDadosRegistros->iCodigoRegistro = $oRegistro->iCodigoRegistro;
        $oProcessamentoLinha              = new RecadastroImobiliarioImoveisStrategy($oDadosRegistros);


        try {

          $oConexao->begin();
          $lProcessou = $oProcessamentoLinha->processar(); 

          if ( !$lProcessou ) {
            throw new Exception("Erro ao Processar");
          }
        } catch (Exception $eErro ) {
         
            $oConexao->end(Conexao::ROLLBACK);
            $oLog->escreverLog($eErro->getMessage());
            $oDadosProcessamento      = (object)array("ie28_observacoes"=> "Erro ao Processar Registro\n" . $oProcessamentoLinha->getLog() );
            $sWhereProcessamento      = "ie28_sequencial = {$oDadosRegistros->iCodigoRegistro}";
            $rsAlteracaoProcessamento = RecadastramentoSQLUtils::alterar("recadastroimobiliarioimoveis", $oDadosProcessamento, $sWhereProcessamento);
            continue;
        }  
        $oConexao->end(Conexao::COMMIT);
        $oLog->escreverLog("");
      }
      echo "\n\n";
    } else {
      $oConexao->end(Conexao::ROLLBACK);
    }
    $oLog->escreverLog("|-------------------------- Fim do Processamento --------------------------|");
    return true;
  }
  
  /**
   * 
   * @param string $sLinha
   * @return stdClass
   */
  static public function getDadosLinha($sLinha, $sNomeArquivo) {
    
    $oLinha                                               = new stdClass();

    $oLinha->sLinhaArquivo                                = $sLinha;
    $oLinha->sNomeArquivo                                 = $sNomeArquivo;
    $oLinha->oDataEnvio                                   = new DBDate(substr($sLinha, 0, 4) .'-'. 
                                                                       substr($sLinha, 4, 2) .'-'. 
                                                                       substr($sLinha, 6, 2));

    $oLinha->iSequencial                                  = (int)  trim(substr($sLinha,   8,   6));
    $oLinha->iMatricula                                   = (int)  trim(substr($sLinha,  14,   6)); // campo controle anterior no layout do arquivo
    $oLinha->iTipoOcorrencia                              = (int)  trim(substr($sLinha,  20,   1));
    $oLinha->sSetorCartograficoAnterior                   =        trim(substr($sLinha,  21,   4));
    $oLinha->sSetorCartograficoNovo                       =        trim(substr($sLinha,  25,   4));
    $oLinha->sQuadraCartograficaAnterior                  =        trim(substr($sLinha,  29,   4));
    $oLinha->sQuadraCartograficaNovo                      =        trim(substr($sLinha,  33,   4));
    $oLinha->sLoteCartograficoAnterior                    =        trim(substr($sLinha,  37,   4));
    $oLinha->sLoteCartograficoNovo                        =        trim(substr($sLinha,  41,   4));
    $oLinha->sUnidadeImobiliariaAnterior                  =        trim(substr($sLinha,  45,   3));
    $oLinha->sUnidadeImobiliariaNova                      =        trim(substr($sLinha,  48,   3));
    $oLinha->sNomeProprietarioAnterior                    =        trim(substr($sLinha,  51, 100));
    $oLinha->sNomeProprietarioNovo                        =        trim(substr($sLinha, 151, 100));
    $oLinha->iTipoProprietario                            = (int)  trim(substr($sLinha, 251,   1));
    $oLinha->sCPFProprietarioAnterior                     =        trim(substr($sLinha, 252,   1));
    $oLinha->sCPFProprietarioNovo                         =        trim(substr($sLinha, 253,  11));
    $oLinha->sCNPJProprietarioAnterior                    =        trim(substr($sLinha, 264,   0));
    $oLinha->sCNPJProprietarioNovo                        =        trim(substr($sLinha, 265,  14));
    $oLinha->sTelefoneProprietarioAnterior                =        trim(substr($sLinha, 279,   1));
    $oLinha->sTelefoneProprietarioNovo                    =        trim(substr($sLinha, 280,  14));
    $oLinha->iCodigoLogradouroAnterior                    = (int)  trim(substr($sLinha, 294,   6));
    $oLinha->iCodigoLogradouroNovo                        = (int)  trim(substr($sLinha, 300,   6));
    $oLinha->sNumeroPortaAnterior                         =        trim(substr($sLinha, 306,   5));
    $oLinha->sNumeroPortaNovo                             =        trim(substr($sLinha, 311,   5));
    $oLinha->sComplementoAnterior                         =        trim(substr($sLinha, 316,  20));
    $oLinha->sComplementoNovo                             =        trim(substr($sLinha, 336,  20));
    $oLinha->nValorTestadaPrincipalAnterior               = (float)trim(substr($sLinha, 356,   5));
    $oLinha->nValorTestadaPrincipalNova                   = (float)trim(substr($sLinha, 361,   5));
    $oLinha->nAreaTerrenoAnterior                         = (float)trim(substr($sLinha, 366,  12));
    $oLinha->nAreaTerrenoNova                             = (float)trim(substr($sLinha, 378,  12));
    $oLinha->iCaracteristicaPropriedadeAnterior           = (int)  trim(substr($sLinha, 390,   1));
    $oLinha->iCaracteristicaPropriedadeNova               = (int)  trim(substr($sLinha, 391,   1));
    $oLinha->iCaracteristicaSituacaoAnterior              = (int)  trim(substr($sLinha, 392,   1));
    $oLinha->iCaracteristicaSituacaoNovo                  = (int)  trim(substr($sLinha, 393,   1));
    $oLinha->iCaracteristicaAnterior                      = (int)  trim(substr($sLinha, 394,   1));
    $oLinha->iCaracteristicaNovo                          = (int)  trim(substr($sLinha, 395,   1));
    $oLinha->iCaracteristicaNivelAnterior                 = (int)  trim(substr($sLinha, 396,   1));
    $oLinha->iCaracteristicaNivelNovo                     = (int)  trim(substr($sLinha, 397,   1));
    $oLinha->iCaracteristicaNumeroFrentesAnterior         = (int)  trim(substr($sLinha, 398,   1));
    $oLinha->iCaracteristicaNumeroFrentesNovo             = (int)  trim(substr($sLinha, 399,   1));
    $oLinha->iCaracteristicaOcupacaoAnterior              = (int)  trim(substr($sLinha, 400,   1));
    $oLinha->iCaracteristicaOcupacaoNovo                  = (int)  trim(substr($sLinha, 401,   1));
    $oLinha->iCaracteristicaUtilizacaoAnterior            = (int)  trim(substr($sLinha, 402,   1));
    $oLinha->iCaracteristicaUtilizacaoNovo                = (int)  trim(substr($sLinha, 403,   1));
    $oLinha->iNumeroPavimentosAnterior                    = (int)  trim(substr($sLinha, 404,   2));
    $oLinha->iNumeroPavimentosNovo                        = (int)  trim(substr($sLinha, 406,   2));
    $oLinha->iCaracteristicaLocalizacaoUnidadeAnterior    = (int)  trim(substr($sLinha, 408,   2));
    $oLinha->iCaracteristicaLocalizacaoUnidadeNovo        = (int)  trim(substr($sLinha, 410,   2));
    $oLinha->iCaracteristicaTipoAnterior                  = (int)  trim(substr($sLinha, 412,   2));
    $oLinha->iCaracteristicaTipoNovo                      = (int)  trim(substr($sLinha, 414,   2));
    $oLinha->iCaracteristicaPadraoConstrutivoAnterior     = (int)  trim(substr($sLinha, 416,   1));
    $oLinha->iCaracteristicaPadraoConstrutivoNovo         = (int)  trim(substr($sLinha, 417,   1));
    $oLinha->iCaracteristicaConservacaoAnterior           = (int)  trim(substr($sLinha, 418,   1));
    $oLinha->iCaracteristicaConservacaoNovo               = (int)  trim(substr($sLinha, 419,   1));
    $oLinha->iCaracteristicaUsoAnterior                   = (int)  trim(substr($sLinha, 420,   1));
    $oLinha->iCaracteristicaUsoNovo                       = (int)  trim(substr($sLinha, 421,   1));
    $oLinha->iCaracteristicaEstruturaAnterior             = (int)  trim(substr($sLinha, 422,   1));
    $oLinha->iCaracteristicaEstruturaNovo                 = (int)  trim(substr($sLinha, 423,   1));
    $oLinha->iCaracteristicaAguaAnterior                  = (int)  trim(substr($sLinha, 424,   1));
    $oLinha->iCaracteristicaAguaNovo                      = (int)  trim(substr($sLinha, 425,   1));
    $oLinha->iCaracteristicaEsgotoAnterior                = (int)  trim(substr($sLinha, 426,   1));
    $oLinha->iCaracteristicaEsgotoNovo                    = (int)  trim(substr($sLinha, 427,   1));
    $oLinha->iCaracteristicaEnergiaEletricaAnterior       = (int)  trim(substr($sLinha, 428,   1));
    $oLinha->iCaracteristicaEnergiaEletricaNovo           = (int)  trim(substr($sLinha, 429,   1));
    $oLinha->iCaracteristicaInstalacaoSanitariaAnterior   = (int)  trim(substr($sLinha, 430,   1));
    $oLinha->iCaracteristicaInstalacaoSanitariaNovo       = (int)  trim(substr($sLinha, 431,   1));
    $oLinha->iCaracteristicaCoberturaAnterior             = (int)  trim(substr($sLinha, 432,   1));
    $oLinha->iCaracteristicaCoberturaNovo                 = (int)  trim(substr($sLinha, 433,   1));
    $oLinha->iCaracteristicaEsquadriaAnterior             = (int)  trim(substr($sLinha, 434,   1));
    $oLinha->iCaracteristicaEsquadriaNovo                 = (int)  trim(substr($sLinha, 435,   1));
    $oLinha->iCaracteristicaPisoAnterior                  = (int)  trim(substr($sLinha, 436,   1));
    $oLinha->iCaracteristicaPisoNovo                      = (int)  trim(substr($sLinha, 437,   1));
    $oLinha->iCaracteristicaRevestimentoExternoAnterior   = (int)  trim(substr($sLinha, 438,   1));
    $oLinha->iCaracteristicaRevestimentoExternoNovo       = (int)  trim(substr($sLinha, 439,   1));
    $oLinha->iPlantaLoteamentoAnterior                    =        trim(substr($sLinha, 440,   4));
    $oLinha->iPlantaLoteamentoNovo                        =        trim(substr($sLinha, 444,   4));
    $oLinha->iQuadraLoteamentoAnterior                    =        trim(substr($sLinha, 448,   4));
    $oLinha->iQuadraLoteamentoNovo                        =        trim(substr($sLinha, 452,   4));
    $oLinha->iLoteLoteamentoAnterior                      =        trim(substr($sLinha, 456,  10));
    $oLinha->iLoteLoteamentoNovo                          =        trim(substr($sLinha, 466,  10));
    $oLinha->iCNPJMobiliarioAnterior                      = (int)  trim(substr($sLinha, 476,   1));
    $oLinha->iCNPJMobiliarioNovo                          = (int)  trim(substr($sLinha, 477,  14));
    $oLinha->iCodigoAtividadeMobiliarioAnterior           = (int)  trim(substr($sLinha, 491,   1));
    $oLinha->iCodigoAtividadeMobiliarioNovo               = (int)  trim(substr($sLinha, 492,  10));
    $oLinha->iCNAEMobiliarioAnterior                      = (int)  trim(substr($sLinha, 502,   1));
    $oLinha->iCNAEMobiliarioNovo                          = (int)  trim(substr($sLinha, 503,  10));
    $oLinha->iInscricaoMunicipalMobiliarioAnterior        = (int)  trim(substr($sLinha, 513,   1));
    $oLinha->iInscricaoMunicipalMobiliarioNovo            = (int)  trim(substr($sLinha, 514,  10));
    $oLinha->sRazaoSocialAnterior                         =        trim(substr($sLinha, 524,   1));
    $oLinha->sRazaoSocialNovo                             =        trim(substr($sLinha, 525,  30));
    $oLinha->sObservacaoAnterior                          =        trim(substr($sLinha, 555,   1));
    $oLinha->sObservacaoNova                              =        trim(substr($sLinha, 556, 100));
    $oLinha->sDistritoAnterior                            =        trim(substr($sLinha, 656,   1));
    $oLinha->sDistritoNovo                                =        trim(substr($sLinha, 657,   1));
    $oLinha->sBairroAnterior                              = (int)  trim(substr($sLinha, 658,   2));
    $oLinha->sBairroNovo                                  = (int)  trim(substr($sLinha, 660,   2));
    $oLinha->lExisteAreaContruida                         =        trim(substr($sLinha, 662,   1));
    $oLinha->nAreaConstruidaAnterior                      =        trim(substr($sLinha, 663,  10));
    $oLinha->nAreaConstruidaNova                          =        trim(substr($sLinha, 673,  10));
    $oLinha->iNumeroFotografiasUnidade                    = (int)  trim(substr($sLinha, 683,   1));
    return $oLinha;
  }
}
