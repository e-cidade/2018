<?php
/**
 * Classe para RecadastroImobiliarioLogradouros do Recadastramento Imobiliario
 * 
 * @version  $Revision: 1.3 $
 * @author   Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @revision $Author: dbrafael.nery $
 */
class RecadastroImobiliarioLogradouros {

  private $oArquivo;
  private $oCabecalhoArquivo;
  private $oRodapeArquivo;
  private $aRegistrosArquivo    = array();
  private $aTabelas             = array();
  private $oConfiguracao;
  private $oLog;
  private $aTiposPadraoSistema  = array();
  private $iRegistrosArquivo    = 0;
  /**
   * Construtor da Classe 
   * @param mixed $sCaminhoArquivo 
   */
  public function __construct( $sCaminhoArquivo ) {


    $this->oConfiguracao  = (object)parse_ini_file( PATH_IMPORTACAO . "libs/configuracoes_importacao.ini",true);
    $this->oArquivo       = fopen($sCaminhoArquivo, 'r');
    $this->oLog           = new DBLog("TXT", PATH_IMPORTACAO . "log/log_logradouros_" . str_ireplace("/", "_",$sCaminhoArquivo) . date("Y_m_d"));
    $rsTiposLogradouro    = pg_query(Conexao::getInstancia()->getConexao(), "select * from cadastro.ruastipo;");

    if ( !$rsTiposLogradouro ) {

      $this->oLog->escreverLog("Erro ao Buscar os Tipos de Logradouro do e-Cidade.", DBLog::LOG_INFO);
      $this->oLog->escreverLog("Descricao do Erro: ".pg_last_error(),                DBLog::LOG_INFO);
      throw new Exception("Erro ao Buscar Tipos de Logradouro do Sistema:" . pg_last_error());
    }

    $this->aTiposPadraoSistema = db_utils::getCollectionByRecord($rsTiposLogradouro);
  }

  /**
   * Funcção para validar os Tipos de Logradouro 
   * 
   * @param mixed $sSiglaTipoLogradouro 
   * @return void
   */
  public function getTipoLogradouro( $sSiglaTipoLogradouro ) {

    $iTipoLogradouroNaoInformado = 0;

    foreach ( $this->aTiposPadraoSistema as $oTipoLogradouro) {

      if ( $oTipoLogradouro->j88_sigla == $sSiglaTipoLogradouro ) {
        return $oTipoLogradouro->j88_codigo;
      }
      if ( $oTipoLogradouro->j88_sigla == "NI" ) {
        $iTipoLogradouroNaoInformado = $oTipoLogradouro->j88_codigo;
      }
    }
    return $iTipoLogradouroNaoInformado;
  }

  public function carregarArquivo() {

    $aRuasArquivo                    = array();
    $sLinhaCabecalhoArquivo          = fgets($this->oArquivo);
    $oCabecalhoArquivo               = new stdClass();
    $oCabecalhoArquivo->iAnoArquivo  = trim( substr($sLinhaCabecalhoArquivo, 0,  4) );  //001-004 Ano
    $oCabecalhoArquivo->iMesArquivo  = trim( substr($sLinhaCabecalhoArquivo, 4,  2) );  //005-006 MÃªs
    $oCabecalhoArquivo->iDiaArquivo  = trim( substr($sLinhaCabecalhoArquivo, 6,  2) );  //007-008 Dia
    $oCabecalhoArquivo->sNomeArquivo = trim( substr($sLinhaCabecalhoArquivo, 8, 92) );  //009-100 Nome do arquivos

    $this->oCabecalhoArquivo         = $oCabecalhoArquivo;

    while ( $sLinhaArquivo = fgets( $this->oArquivo ) ) {

      if ( !validarUltimaLinhaArquivo($this->oArquivo) ) {

        $oPosicoesArquivo                          = new stdClass();
        $oPosicoesArquivo->oDataArquivo            = trim( substr($sLinhaArquivo,   0,   8) ); // 001-008 - Quantidade  8- Data do envio
        $oPosicoesArquivo->sSequencial             = trim( substr($sLinhaArquivo,   8,   6) ); // 009-014 - Quantidade  6- sequencial
        $oPosicoesArquivo->iCodigoLogradouro       = trim( substr($sLinhaArquivo,  14,   6) ); // 015-020 - Quantidade  6- CÃ³digo do logradouro
        $oPosicoesArquivo->iTipoLogradouro         = trim( substr($sLinhaArquivo,  20,   3) ); // 021-023 - Quantidade  3- Tipo do logradouro
        $oPosicoesArquivo->sTituloLogradouro       = trim( substr($sLinhaArquivo,  23,   5) ); // 024-028 - Quantidade  5- TÃ­tulo do logradouro
        $oPosicoesArquivo->sNomeLogradouro         = trim( substr($sLinhaArquivo,  28,  30) ); // 029-058 - Quantidade 30- Nome do logradouro
        $oPosicoesArquivo->sNomeAnteriorLogradouro = trim( substr($sLinhaArquivo,  58, 200) ); // 059-258 - Quantidade200- Nome anterior do logradouro
        $oPosicoesArquivo->sLei                    = trim( substr($sLinhaArquivo, 258,  10) ); // 259-268 - Quantidade 10- Lei que atribuiu o nome
        $oPosicoesArquivo->oDataLei                = trim( substr($sLinhaArquivo, 268,  10) ); // 269-278 - Quantidade 10- Data da lei
        $oPosicoesArquivo->iCodigoLogradouroInicio = trim( substr($sLinhaArquivo, 278,   6) ); // 279-284 - Quantidade  6- CÃ³digo do logradouro de inÃ­cio
        $oPosicoesArquivo->iCodigoLogradouroFim    = trim( substr($sLinhaArquivo, 284,   6) ); // 285-290 - Quantidade  6- CÃ³digo do logradouro de fim
        
        if (!isset($aRegistrosArquivo[$oPosicoesArquivo->iCodigoLogradouro])){
          
          $aRegistrosArquivo[$oPosicoesArquivo->iCodigoLogradouro] = $oPosicoesArquivo->iCodigoLogradouro;
          $this->aRegistrosArquivo[]                 = $oPosicoesArquivo;
        } else {
          $this->oLog->escreverLog("Logradouro Código:  $oPosicoesArquivo->iCodigoLogradouro, Linha do Arquivo: ".$this->iRegistrosArquivo + 2, DBLog::LOG_NOTICE);
        }
        $this->iRegistrosArquivo++;
      } else {

        $oRodapeArquivo                            = new stdClass();
        $oRodapeArquivo->iAnoArquivo               = substr($sLinhaArquivo,  0, 4);//001-004 Ano
        $oRodapeArquivo->iMesArquivo               = substr($sLinhaArquivo,  4, 2);//005-006 MÃªs
        $oRodapeArquivo->iDiaArquivo               = substr($sLinhaArquivo,  6, 2);//007-008 Dia
        $oRodapeArquivo->sIdentificador            = substr($sLinhaArquivo,  8, 6);//009-014 Identificador
        $oRodapeArquivo->iQuantidadeRegistros      = (int)substr($sLinhaArquivo, 14, 6);//015-020 NÃºmero de linhas de dado do arquivo
        $this->oRodapeArquivo                      = $oRodapeArquivo;
      }
    }
    return true;
  }

  public function salvar() {

    if ( !$this->testarCarregamentoArquivo() ) {
      throw new Exception("Inconsistencias foram encontradas ao Carregar o Arquivo.");
    }
    $pConexao                                           = Conexao::getInstancia()->getConexao();
    $this->aTabelas['recadastroimobiliarioarquivos']    = new tableDataManager($pConexao,"recadastroimobiliarioarquivos"   , "ie24_sequencial", true,1);
    $this->aTabelas['recadastroimobiliariologradouros'] = new tableDataManager($pConexao,"recadastroimobiliariologradouros", "ie25_sequencial", true,1000);
    $this->aTabelas['ruas']                             = new tableDataManager($pConexao,"cadastro.ruas"                   , "",                true,1000);
    $this->aTabelas['recadastrologradouroshistorico']   = new tableDataManager($pConexao,"recadastrologradouroshistorico"  , "ie27_sequencial", true,1000);
    
    $oRecadastroArquivos                                = $this->aTabelas['recadastroimobiliarioarquivos'];
    $oRecadastroLogradouros                             = $this->aTabelas['recadastroimobiliariologradouros'];

    $dDataImportacao  = $this->oCabecalhoArquivo->iAnoArquivo . "-" . $this->oCabecalhoArquivo->iMesArquivo."-".$this->oCabecalhoArquivo->iDiaArquivo;

    /**
     * Salvando dadso da Importacao do Arquivo
     */
    $oDadosInclusaoArquivo                              = new stdClass();
    $oDadosInclusaoArquivo->ie24_nomearquivo            = $this->oCabecalhoArquivo->sNomeArquivo;
    $oDadosInclusaoArquivo->ie24_dataimportacao         = $dDataImportacao;
    $oDadosInclusaoArquivo->ie24_quantidaderegistros    = $this->oRodapeArquivo->iQuantidadeRegistros;
    $oDadosInclusaoArquivo->ie24_tipoarquivo            = 1; // TIPO LOGRADOURO
    $oRecadastroArquivos->setByLineOfDBUtils($oDadosInclusaoArquivo);
    $iCodigoImportacao                                  = $oRecadastroArquivos->insertValue();
    $oRecadastroArquivos->persist();

    $this->oLog->escreverLog(" Salvando Informacoes do Arquivo:  $iCodigoImportacao", DBLog::LOG_INFO);
    /**
     * Limpando Memoria
     */
    unset($oDadosInclusaoArquivo);
    $this->oLog->escreverLog("Preparando para Salvar no Banco de Dados os Registros Importados.", DBLog::LOG_INFO);
    $this->oLog->escreverLog("Total de Registros: ".count($this->aRegistrosArquivo), DBLog::LOG_INFO);


    /**
     * Percorre linhas do arquivo
     */
    foreach ( $this->aRegistrosArquivo as $oLinhaArquivo ) {

      $this->oLog->escreverLog(" Incluindo Registro para Processamento:  {$oLinhaArquivo->iCodigoLogradouro}", DBLog::LOG_INFO);

      $oDadosInclusao                                       = new stdClass();
      $oDadosInclusao->ie25_recadastroimobiliarioarquivos   = pg_escape_string($iCodigoImportacao);
      $oDadosInclusao->ie25_sequencialregistro              = pg_escape_string(trim($oLinhaArquivo->sSequencial));
      $oDadosInclusao->ie25_codigologradouro                = pg_escape_string((int)trim($oLinhaArquivo->iCodigoLogradouro));
      $oDadosInclusao->ie25_tipologradouro                  = pg_escape_string(trim($oLinhaArquivo->iTipoLogradouro));
      $oDadosInclusao->ie25_nomelogradouro                  = pg_escape_string(trim($oLinhaArquivo->sTituloLogradouro . " " . $oLinhaArquivo->sNomeLogradouro));
      $oDadosInclusao->ie25_nomelogradouroanterior          = pg_escape_string(trim($oLinhaArquivo->sNomeAnteriorLogradouro));
      $oDadosInclusao->ie25_lei                             = pg_escape_string(trim($oLinhaArquivo->sLei));
      $oDadosInclusao->ie25_datalei                         = pg_escape_string(trim($oLinhaArquivo->oDataLei));
      $oDadosInclusao->ie25_codigologradouroinicio          = pg_escape_string((int)trim($oLinhaArquivo->iCodigoLogradouroInicio));
      $oDadosInclusao->ie25_codigologradourofim             = pg_escape_string((int)trim($oLinhaArquivo->iCodigoLogradouroFim));
      $oRecadastroLogradouros->setByLineOfDBUtils($oDadosInclusao);
      $oRecadastroLogradouros->insertValue();
    }


    $this->oLog->escreverLog("Persistindo Dados Importados", DBLog::LOG_INFO);
    $oRecadastroLogradouros->persist();
    return $iCodigoImportacao;
  }

  /**
   * PreProcessa o Arquivo, validando e Corrigindo possiveis irregularidades 
   * @access public
   * @return void
   */
  public function testarCarregamentoArquivo() {


    $this->oLog->escreverLog("#######################################", DBLog::LOG_INFO);
    $this->oLog->escreverLog("Testando Registro importados do Arquivo", DBLog::LOG_INFO);
    $this->oLog->escreverLog("#######################################", DBLog::LOG_INFO);
    $this->oLog->escreverLog("", DBLog::LOG_INFO);

    $oHeader     = $this->oCabecalhoArquivo;

    $lAnoVazio   = empty($oHeader->iAnoArquivo);
    $lMesVazio   = empty($oHeader->iMesArquivo);
    $lDiaVazio   = empty($oHeader->iDiaArquivo);
    $lNomeValido = empty($oHeader->sNomeArquivo);


    if ( $lAnoVazio || $lMesVazio || $lDiaVazio || $lNomeValido ) {

      $sErro        = "CABECALHO DO ARQUIVO INCONSISTENTE";
      $this->oLog->escreverLog($sErro, DBLog::LOG_ERROR);

      if ( $lAnoVazio ) {
        $this->oLog->escreverLog("  ANO VAZIO", DBLog::LOG_ERROR);
      }
      if ( $lMesVazio ) {
        $this->oLog->escreverLog("  MES VAZIO", DBLog::LOG_ERROR);
      }
      if ( $lDiaVazio ) {
        $this->oLog->escreverLog("  DIA VAZIO", DBLog::LOG_ERROR);
      }
      if ( $lNomeValido ) {
        $this->oLog->escreverLog("  NOME DO LOTE VAZIO OU DIFERENTE DO NOME DO ARQUIVO", DBLog::LOG_ERROR);
      }
      return false;
    }

    $this->oLog->escreverLog("CABECALHO DO ARQUIVO SEM PROBLEMAS", DBLog::LOG_INFO);


    /**
     * Testando Registros
     */
    $iTotalRegistros = $this->iRegistrosArquivo;

    if ( $this->oRodapeArquivo->iQuantidadeRegistros <> $iTotalRegistros ) {

      $this->oLog->escreverLog("Quantidade de Registros do Arquivo: {$iTotalRegistros}, Diferente da Quantidade Informada: {$this->oRodapeArquivo->iQuantidadeRegistros}", DBLog::LOG_ERROR);
      return false;
    }
  
  
    $this->oLog->escreverLog("", DBLog::LOG_INFO);
    $this->oLog->escreverLog("Total de Registros: {$iTotalRegistros}", DBLog::LOG_INFO);

    /**
     * Percorrendo registros e os validando
     */
    foreach ( $this->aRegistrosArquivo as $iCodigoRegistro => $oRegistro )  {

      $iLinha                 = $iCodigoRegistro + 2;//Linha + Linha Cabecalho + Array que começa em 0(zero)
      $sSequencial            = trim( $oRegistro->sSequencial );
      $sCodigoLogradouro      = trim( $oRegistro->iCodigoLogradouro );
      $sNomeLogradouro        = trim( $oRegistro->sNomeLogradouro );

      $lSequencialVazio       = empty( $sSequencial ); 
      $lCodigoLogradouroVazio = empty( $sCodigoLogradouro );
      $lNomeLogradouroVazio   = empty( $sNomeLogradouro );

      if ( $lSequencialVazio || $lCodigoLogradouroVazio || $lNomeLogradouroVazio ) {

        $this->oLog->escreverLog("[{$oHeader->sNomeArquivo}:$iLinha] - Removendo registro inconsistente da Fila de Processamento", DBLog::LOG_NOTICE);
        if ( $lSequencialVazio ) {
          $this->oLog->escreverLog("  Sequencial do Registro Vazio", DBLog::LOG_NOTICE);
        }

        if ( $lCodigoLogradouroVazio ) {
          $this->oLog->escreverLog("  Codigo do Logradouro Vazio ", DBLog::LOG_NOTICE);
        }

        if ( $lNomeLogradouroVazio ) {
          $this->oLog->escreverLog("  Nome do Logradouro Vazio", DBLog::LOG_NOTICE);
        }

        unset( $this->aRegistrosArquivo[$iCodigoRegistro] );
        continue;
      }


      /**
       * Definindo o Tipo de Logradouro atraves da Sigla
       */
      $oRegistro->iTipoLogradouro = $this->getTipoLogradouro($oRegistro->iTipoLogradouro);
    }
    $this->oLog->escreverLog("#######################################", DBLog::LOG_INFO);
    $this->oLog->escreverLog("      Teste Finalizado com Sucesso.    ", DBLog::LOG_INFO);
    $this->oLog->escreverLog("#######################################", DBLog::LOG_INFO);
    $this->oLog->escreverLog("", DBLog::LOG_INFO);
    $this->oLog->escreverLog("", DBLog::LOG_INFO);
    return true;
  }

  /**
   * Processa importaÃ§ção com Base nos dados migrados do Arquivo 
   * @access public
   * @return boolean
   */
  public function processarImportacao() {

    $iCodigoImportacao   = $this->salvar();
    
    $sSqlDadosImportados = "select * from recadastroimobiliariologradouros where  ie25_recadastroimobiliarioarquivos = {$iCodigoImportacao}";
    $rsDadosImportados   = pg_query( Conexao::getInstancia()->getConexao(), " $sSqlDadosImportados" );

    if (!$rsDadosImportados) {

      $this->oLog->escreverLog("Erro ao Buscar dados Importados do Arquivo TXT.", DBLog::LOG_ERROR);
      $this->oLog->escreverLog("Descricao do Erro: ".pg_last_error(),             DBLog::LOG_ERROR);
      throw new Exception("Erro ao Buscar Tipos de Logradouro do Sistema:" . pg_last_error());
    }
    $aTotalRegistros =  db_utils::getCollectionByRecord($rsDadosImportados);
    $iTotalRegistros = count($aTotalRegistros);
    $iRegistroAtual  = 0;
    $oBarraProgresso = new BarraProgressoCli($iTotalRegistros);
    echo "Processamento Logradouros: \n";
    foreach ( db_utils::getCollectionByRecord($rsDadosImportados) as $oLogradouroImportado ) {
     
      $iRegistroAtual++;
      $oBarraProgresso->atualizar($iRegistroAtual);

      $oDadosNovosLogradouro                     = new stdClass();
      $oDadosNovosLogradouro->j14_codigo         = $oLogradouroImportado->ie25_codigologradouro;
      $oDadosNovosLogradouro->j14_nome           = $oLogradouroImportado->ie25_nomelogradouro;
      $oDadosNovosLogradouro->j14_tipo           = $oLogradouroImportado->ie25_tipologradouro;
      $oDadosNovosLogradouro->j14_rural          = 'f';
      $oDadosNovosLogradouro->j14_lei            = $oLogradouroImportado->ie25_lei;
      $oDadosNovosLogradouro->j14_dtlei          = $oLogradouroImportado->ie25_datalei;
      $oDadosNovosLogradouro->j14_bairro         = "";

      $oDadosHistoricoArquivo                    = new stdClass();
      $oDadosHistoricoArquivo->ie27_ruas         = $oLogradouroImportado->ie25_codigologradouro;
      $oDadosHistoricoArquivo->ie27_tipo         = $oLogradouroImportado->ie25_tipologradouro;
      $oDadosHistoricoArquivo->ie27_nomeanterior = $oLogradouroImportado->ie25_nomelogradouroanterior;
      $oDadosHistoricoArquivo->ie27_data         = date('Y-m-d');
      $oDadosHistoricoArquivo->ie27_lei          = $oLogradouroImportado->ie25_lei;
      $oDadosHistoricoArquivo->ie27_datalei      = $oLogradouroImportado->ie25_datalei;

      $oDadosAntigosLogradouro           = $this->validarExistencialogradouro($oLogradouroImportado->ie25_codigologradouro);

      if ( $oDadosAntigosLogradouro ) {
         
        $this->oLog->escreverLog("Registro {$oLogradouroImportado->ie25_codigologradouro}: Alteracao.", DBLog::LOG_INFO);
        /**
         * AlteraÃ§ção
         *
         * Valida se esta vindo do um tipo "Nao Informado" e o anterior seja diferente, mantem o anterior
         */
        if ( $oDadosAntigosLogradouro->j14_tipo <> 1 && $oDadosNovosLogradouro->j14_tipo == 1) {
          $oDadosNovosLogradouro->j14_tipo = $oDadosAntigosLogradouro->j14_tipo;
        }


        if ( $oLogradouroImportado->ie25_nomelogradouroanterior != '' ) {

          $this->oLog->escreverLog("Registro {$oLogradouroImportado->ie25_codigologradouro}: Gravando historico encontrado no arquivo.", DBLog::LOG_INFO);
          $this->aTabelas['recadastrologradouroshistorico']->setByLineOfDBUtils( $oDadosHistoricoArquivo );
          $this->aTabelas['recadastrologradouroshistorico']->insertValue();
        } 

        /**
         * Nomes Iguais e Tipos Iguais Não executa nada
         */
        $lNomeLogradouroIgual = $oDadosAntigosLogradouro->j14_nome == $oDadosNovosLogradouro->j14_nome;
        $lTipoLogradouroIgual = $oDadosAntigosLogradouro->j14_tipo == $oDadosNovosLogradouro->j14_tipo;

        if ( $lNomeLogradouroIgual && $lTipoLogradouroIgual ) {

          $this->oLog->escreverLog("Registro {$oLogradouroImportado->ie25_codigologradouro}: Sem Modificacoes.", DBLog::LOG_INFO);
          $this->oLog->escreverLog("Logradouro: {$oDadosAntigosLogradouro->j14_nome} sem Modificacoes.", DBLog::LOG_NOTICE);
          continue;
        }

        $sDataLei = empty($oDadosNovosLogradouro->j14_dtlei) ? 'null' : "'{$oDadosNovosLogradouro->j14_dtlei}'";
        $sSqlAlteracao = "update cadastro.ruas                                       \n";
        $sSqlAlteracao.= "   set j14_nome   = '{$oDadosNovosLogradouro->j14_nome}',  \n";
        $sSqlAlteracao.= "       j14_tipo   = '{$oDadosNovosLogradouro->j14_tipo}',  \n";
        $sSqlAlteracao.= "       j14_rural  = '{$oDadosNovosLogradouro->j14_rural}', \n";
        $sSqlAlteracao.= "       j14_lei    = '{$oDadosNovosLogradouro->j14_lei}',   \n";
        $sSqlAlteracao.= "       j14_dtlei  =  {$sDataLei}, \n";
        $sSqlAlteracao.= "       j14_bairro = '{$oDadosNovosLogradouro->j14_bairro}' \n";
        $sSqlAlteracao.= " where j14_codigo =  {$oDadosNovosLogradouro->j14_codigo}; \n";
        $rsAlteracao   = pg_query(Conexao::getInstancia()->getConexao(), $sSqlAlteracao);

        if ( !$rsAlteracao ) {

          $this->oLog->escreverLog("Erro ao Alterar dados do Logradouro.", DBLog::LOG_ERROR);
          $this->oLog->escreverLog("Descricao do Erro: ".pg_last_error() , DBLog::LOG_ERROR);
          throw new Exception("Erro ao Alterar dados do Logradouro." . pg_last_error());
        }


        $oDadosHistoricoArquivo                    = new stdClass();
        $oDadosHistoricoArquivo->ie27_ruas         = $oLogradouroImportado->ie25_codigologradouro;
        $oDadosHistoricoArquivo->ie27_tipo         = $oDadosAntigosLogradouro->j14_tipo;
        $oDadosHistoricoArquivo->ie27_nomeanterior = $oDadosAntigosLogradouro->j14_nome;
        $oDadosHistoricoArquivo->ie27_data         = date('Y-m-d');
        $oDadosHistoricoArquivo->ie27_lei          = $oDadosAntigosLogradouro->j14_lei;
        $oDadosHistoricoArquivo->ie27_datalei      = $oDadosAntigosLogradouro->j14_dtlei;

        $this->oLog->escreverLog("Registro {$oLogradouroImportado->ie25_codigologradouro}: Gravando historico da Alteracao Efetuada.", DBLog::LOG_INFO);
        $this->aTabelas['recadastrologradouroshistorico']->setByLineOfDBUtils( $oDadosHistoricoArquivo );
        $this->aTabelas['recadastrologradouroshistorico']->insertValue();
      } else {

        /**
         * Inclusao
         */
        
        $this->oLog->escreverLog("Registro {$oLogradouroImportado->ie25_codigologradouro}: Sera Incluido.", DBLog::LOG_INFO);
        $this->aTabelas['ruas']->setByLineOfDBUtils($oDadosNovosLogradouro);
        $this->aTabelas['ruas']->insertValue();

        if ( $oLogradouroImportado->ie25_nomelogradouroanterior != '' ) {
          $this->oLog->escreverLog("Registro {$oLogradouroImportado->ie25_codigologradouro}: Gravando historico encontrado no arquivo.", DBLog::LOG_INFO);
          $this->aTabelas['recadastrologradouroshistorico']->setByLineOfDBUtils( $oDadosHistoricoArquivo );
          $this->aTabelas['recadastrologradouroshistorico']->insertValue();;
        }
      }
    }

    $this->oLog->escreverLog("Persistindo Dados da Tabela Ruas", DBLog::LOG_INFO);
    $this->aTabelas['ruas']->persist();

    $this->oLog->escreverLog("Persistindo dados do Historico das Alteracoes.", DBLog::LOG_INFO);
    $this->aTabelas['recadastrologradouroshistorico']->persist();
  }

  public function validarExistencialogradouro( $iCodigoLogradouro ) {
    
    $sSqlDadosLogradouro = "select * from cadastro.ruas where  j14_codigo = {$iCodigoLogradouro}";
    $rsDadosLogradouro   = pg_query( Conexao::getInstancia()->getConexao(),  $sSqlDadosLogradouro );

    if (!$rsDadosLogradouro) {

      $this->oLog->escreverLog("Erro ao Buscar dados Importados do Arquivo TXT.", DBLog::LOG_ERROR);
      $this->oLog->escreverLog("Descricao do Erro: ".pg_last_error(),             DBLog::LOG_ERROR);
      throw new Exception("Erro ao Buscar Tipos de Logradouro do Sistema:" . pg_last_error() );
    }

    if ( pg_num_rows($rsDadosLogradouro) > 0 ) {
      return db_utils::fieldsMemory($rsDadosLogradouro, 0);
    }
    return false;

  }


}
function validarUltimaLinhaArquivo($pArquivo) {

  /**   
   * Mostra posicao atual
   */
  $iPosicaoCorrente = ftell($pArquivo);
  /**
   * Tenta resgatar o conteudo da linha
   */
  $lSemLinhasApos   = fgets($pArquivo) ? false : true;
  /**
   * Volta a Posicao Corrente
   */
  fseek($pArquivo, $iPosicaoCorrente);
  return $lSemLinhasApos;
}

