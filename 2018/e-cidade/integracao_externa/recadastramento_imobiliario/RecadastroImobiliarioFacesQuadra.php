<?php 
/**
 * 
 * @author dbseller
 *
 */
class RecadastroImobiliarioFacesQuadra {

  private $oArquivo;
  private $oCabecalhoArquivo;
  private $oRodapeArquivo;
  private $aRegistrosArquivo    = array();
  private $aTabelas             = array();
  private $oConfiguracao;
  private $oLog;
  private $oJson;

  /**
   * Construtor da Classe
   * @param unknown_type $sCaminhoArquivo
   */
  public function __construct( $sCaminhoArquivo ) {

    $this->oConfiguracao         = (object)parse_ini_file( PATH_IMPORTACAO . "libs/configuracoes_importacao.ini",true);
    $this->oArquivo              = fopen($sCaminhoArquivo, 'r');
    $this->oLog                  = new DBLog("TXT",PATH_IMPORTACAO . "log/log_faces_" . str_ireplace("/", "_",$sCaminhoArquivo) . date("Y_m_d") ."log");
    $this->oJson                 = new services_json();
    $this->aDadosCaracteristicas = $this->oJson->decode(str_replace("\\","", file_get_contents(PATH_IMPORTACAO . 'libs/caracteristicas.json')));
    $this->aFacesValidacao       = array();
    
    $sDataHora = date('YmdHi');
    
    $this->oLog->escreverLog("Criando backup dos registros da tabela face na nova tabela: w_face_$sDataHora", DBLog::LOG_INFO);
    pg_query(Conexao::getInstancia()->getConexao(), "drop table if exists w_face_$sDataHora");
    pg_query(Conexao::getInstancia()->getConexao(), "create table w_face_$sDataHora as select * from face;");
    
    $this->oLog->escreverLog("Criando backup dos registros da tabela carface na nova tabela: w_carface_$sDataHora", DBLog::LOG_INFO);
    pg_query(Conexao::getInstancia()->getConexao(), "drop table if exists w_carface_$sDataHora");
    pg_query(Conexao::getInstancia()->getConexao(), "create table w_carface_$sDataHora as select * from carface;");
    
  }

  /**
   * Carrega arquivo txt para a memória
   * @return boolean
   */
  public function carregarArquivo() {

    $sLinhaCabecalhoArquivo          = fgets($this->oArquivo);
    $oCabecalhoArquivo               = new stdClass();
    $oCabecalhoArquivo->iAnoArquivo  = (int)    trim(substr($sLinhaCabecalhoArquivo, 0,  4));  
    $oCabecalhoArquivo->iMesArquivo  = (int)    trim(substr($sLinhaCabecalhoArquivo, 4,  2));  
    $oCabecalhoArquivo->iDiaArquivo  = (int)    trim(substr($sLinhaCabecalhoArquivo, 6,  2));  
    $oCabecalhoArquivo->sNomeArquivo = (string) trim(substr($sLinhaCabecalhoArquivo, 8, 92));  
    $oCabecalhoArquivo->iTipoArquivo = 2;

    $this->oCabecalhoArquivo         = $oCabecalhoArquivo;

    while ( $sLinhaArquivo = fgets( $this->oArquivo ) ) {

      if ( !$this->validarUltimaLinhaArquivo($this->oArquivo) ) {
        
        $oPosicoesArquivo                      = new stdClass();
        $oPosicoesArquivo->sDataArquivo        = (string) trim(substr($sLinhaArquivo,   0,  8));
        $oPosicoesArquivo->iSequencial         = (int)    trim(substr($sLinhaArquivo,   8,  6));
        $oPosicoesArquivo->sSetorCartografico  = (string) trim(substr($sLinhaArquivo,  14,  4));
        $oPosicoesArquivo->sQuadraCartografica = (string) trim(substr($sLinhaArquivo,  18,  4)); 
        $oPosicoesArquivo->iCodigoLogradouro   = (int)    trim(substr($sLinhaArquivo,  22,  6));
        $oPosicoesArquivo->lPavimentacao       =          trim(substr($sLinhaArquivo,  28,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->lGaleriasPluviais   =          trim(substr($sLinhaArquivo,  29,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->lRedeAgua           =          trim(substr($sLinhaArquivo,  30,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->lRedeEsgoto         =          trim(substr($sLinhaArquivo,  31,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->lRedeEletrica       =          trim(substr($sLinhaArquivo,  32,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->lIluminacao         =          trim(substr($sLinhaArquivo,  33,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->lRedeTelefonica     =          trim(substr($sLinhaArquivo,  34,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->lColetaLixo         =          trim(substr($sLinhaArquivo,  35,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->lVarricao           =          trim(substr($sLinhaArquivo,  36,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->lSujeitoInundacoes  =          trim(substr($sLinhaArquivo,  37,  1)) == 'S' ? 'true' : 'false';
        $oPosicoesArquivo->nValorM2Terreno     = (float)  trim(substr($sLinhaArquivo,  38, 10));
        $oPosicoesArquivo->sCep                = (string) trim(str_replace('-', '', substr($sLinhaArquivo,  48,  9)));
        
        $sBaseValidacao    = $oPosicoesArquivo->sSetorCartografico  . "_" ;
        $sBaseValidacao   .= $oPosicoesArquivo->sQuadraCartografica . "_" ;
        $sBaseValidacao   .= $oPosicoesArquivo->iCodigoLogradouro         ;
        
        $lExisteLogradouro = $this->verificaLogradouro($oPosicoesArquivo->iCodigoLogradouro, $oPosicoesArquivo->iSequencial);
        $lExisteSetor      = $this->verificaSetor($oPosicoesArquivo->sSetorCartografico, $oPosicoesArquivo->iSequencial);
         
        if ( !isset($this->aFacesValidacao[$sBaseValidacao]) and $lExisteLogradouro and $lExisteSetor )  {
          
          $this->aRegistrosArquivo[]              = $oPosicoesArquivo;
          $this->aFacesValidacao[$sBaseValidacao] = $sBaseValidacao;
          
        }

      } else {

        $oRodapeArquivo                            = new stdClass();
        $oRodapeArquivo->iAnoArquivo               = (int)    trim(substr($sLinhaArquivo,  0, 4));
        $oRodapeArquivo->iMesArquivo               = (int)    trim(substr($sLinhaArquivo,  4, 2));
        $oRodapeArquivo->iDiaArquivo               = (int)    trim(substr($sLinhaArquivo,  6, 2));
        $oRodapeArquivo->sIdentificador            = (string) trim(substr($sLinhaArquivo,  8, 6));
        $oRodapeArquivo->iQuantidadeRegistros      = (int)    trim(substr($sLinhaArquivo, 14, 6));
        $this->oRodapeArquivo                      = $oRodapeArquivo;
        
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

    $pConexao                                           = Conexao::getInstancia()->getConexao();
    $this->aTabelas['recadastroimobiliarioarquivos']    = new tableDataManager($pConexao,"recadastroimobiliarioarquivos"   , "ie24_sequencial", true, 1);
    $this->aTabelas['recadastroimobiliariofacesquadra'] = new tableDataManager($pConexao,"recadastroimobiliariofacesquadra", "ie26_sequencial", true, 1000);

    $oRecadastroArquivos                                = $this->aTabelas['recadastroimobiliarioarquivos'];
    $oRecadastroFacesQuadra                             = $this->aTabelas['recadastroimobiliariofacesquadra'];

    $dDataImportacao  = $this->oCabecalhoArquivo->iAnoArquivo . "-" . $this->oCabecalhoArquivo->iMesArquivo."-".$this->oCabecalhoArquivo->iDiaArquivo;

    /**
     * Salvando dadso da Importacao do Arquivo
     */
    $oDadosInclusaoArquivo                           = new stdClass();
    $oDadosInclusaoArquivo->ie24_nomearquivo         = $this->oCabecalhoArquivo->sNomeArquivo;
    $oDadosInclusaoArquivo->ie24_tipoarquivo         = $this->oCabecalhoArquivo->iTipoArquivo;
    $oDadosInclusaoArquivo->ie24_dataimportacao      = $dDataImportacao;
    $oDadosInclusaoArquivo->ie24_quantidaderegistros = $this->oRodapeArquivo->iQuantidadeRegistros;

    $oRecadastroArquivos->setByLineOfDBUtils($oDadosInclusaoArquivo);

    $iCodigoImportacao                               = $oRecadastroArquivos->insertValue();

    $oRecadastroArquivos->persist();

    $this->oLog->escreverLog("Salvando Informacoes do Arquivo:  $iCodigoImportacao", DBLog::LOG_INFO);

    /**
     * Limpando Memoria
     */
    unset($oDadosInclusaoArquivo);
    
    $this->oLog->escreverLog("Preparando para Salvar no Banco de Dados os Registros do Arquivo.", DBLog::LOG_INFO);
    $this->oLog->escreverLog("Total de Registros: ".count($this->aRegistrosArquivo), DBLog::LOG_INFO);


    /**
     * Percorre linhas do arquivo
     */
    foreach ( $this->aRegistrosArquivo as $oLinhaArquivo ) {

      $this->oLog->escreverLog("Salvando linha {$oLinhaArquivo->iSequencial} do arquivo.", DBLog::LOG_INFO);

      $oDadosInclusao                                       = new stdClass();

      $oDadosInclusao->ie26_recadastroimobiliarioarquivos = pg_escape_string($iCodigoImportacao                 );
      $oDadosInclusao->ie26_sequencialregistro            = pg_escape_string($oLinhaArquivo->iSequencial        );
      $oDadosInclusao->ie26_setorcartografico             = pg_escape_string($oLinhaArquivo->sSetorCartografico );
      $oDadosInclusao->ie26_quadracartografica            = pg_escape_string($oLinhaArquivo->sQuadraCartografica);
      $oDadosInclusao->ie26_codigologradouro              = pg_escape_string($oLinhaArquivo->iCodigoLogradouro  );
      $oDadosInclusao->ie26_pavimentacao                  = pg_escape_string($oLinhaArquivo->lPavimentacao      );
      $oDadosInclusao->ie26_galeriaspluviais              = pg_escape_string($oLinhaArquivo->lGaleriasPluviais  );
      $oDadosInclusao->ie26_redeagua                      = pg_escape_string($oLinhaArquivo->lRedeAgua          );
      $oDadosInclusao->ie26_redeesgoto                    = pg_escape_string($oLinhaArquivo->lRedeEsgoto        );
      $oDadosInclusao->ie26_redeeletrica                  = pg_escape_string($oLinhaArquivo->lRedeEletrica      );
      $oDadosInclusao->ie26_iluminacao                    = pg_escape_string($oLinhaArquivo->lIluminacao        );
      $oDadosInclusao->ie26_redetelefonica                = pg_escape_string($oLinhaArquivo->lRedeTelefonica    );
      $oDadosInclusao->ie26_coletalixo                    = pg_escape_string($oLinhaArquivo->lColetaLixo        );
      $oDadosInclusao->ie26_varricao                      = pg_escape_string($oLinhaArquivo->lVarricao          );
      $oDadosInclusao->ie26_sujeitoinundacoes             = pg_escape_string($oLinhaArquivo->lSujeitoInundacoes );
      $oDadosInclusao->ie26_valorm2terreno                = pg_escape_string($oLinhaArquivo->nValorM2Terreno    );
      $oDadosInclusao->ie26_cep                           = pg_escape_string($oLinhaArquivo->sCep               );

      $oRecadastroFacesQuadra->setByLineOfDBUtils($oDadosInclusao);
      $oRecadastroFacesQuadra->insertValue();
      
    }

    $this->oLog->escreverLog("Persistindo Dados Importados", DBLog::LOG_INFO);
    $oRecadastroFacesQuadra->persist();

    return $iCodigoImportacao;
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
  public function processarInformacoes() {

    
    $this->aTabelas['carface'] = new tableDataManager(Conexao::getInstancia()->getConexao(), "cadastro.carface", ""        , false);
    $this->aTabelas['face']    = new tableDataManager(Conexao::getInstancia()->getConexao(), "cadastro.face"   , "j37_face", false);

    $iCodigoImportacao         = $this->salvar();
    $sSqlDadosImportados       = "select * from recadastroimobiliariofacesquadra where ie26_recadastroimobiliarioarquivos = {$iCodigoImportacao}";
    $rsDadosImportados         = pg_query( Conexao::getInstancia()->getConexao(), " $sSqlDadosImportados" );

    if (!$rsDadosImportados) {
      $this->oLog->escreverLog("Erro ao consultar registros do arquivo {$iCodigoImportacao}.", DBLog::LOG_ERROR);
      throw new Exception('Erro ao consultar registros do arquivo');
    } 

    $this->oLog->escreverLog("Consultando registros do arquivo {$iCodigoImportacao}.", DBLog::LOG_INFO);

    $aDadosImportados = db_utils::getCollectionByRecord($rsDadosImportados);
    $iTotalRegistros = count($aDadosImportados);
    $oBarraProgresso = new BarraProgressoCli($iTotalRegistros);
    
    foreach ($aDadosImportados as $oRegistroImportado) {
      
      $oBarraProgresso->atualizar();
      $oDadosNovosFaceQuadra             = new stdClass();
      $oDadosNovosFaceQuadra->j37_setor  = $oRegistroImportado->ie26_setorcartografico;
      $oDadosNovosFaceQuadra->j37_quadra = $oRegistroImportado->ie26_quadracartografica;
      $oDadosNovosFaceQuadra->j37_codigo = $oRegistroImportado->ie26_codigologradouro;
      $oDadosNovosFaceQuadra->j37_lado   = "";
      $oDadosNovosFaceQuadra->j37_valor  = $oRegistroImportado->ie26_valorm2terreno;
      $oDadosNovosFaceQuadra->j37_exten  = "";
      $oDadosNovosFaceQuadra->j37_profr  = "";
      $oDadosNovosFaceQuadra->j37_outros = "";
      $oDadosNovosFaceQuadra->j37_vlcons = 0;
      $oDadosNovosFaceQuadra->j37_zona   = 0;       
      
      /**
       * Caso exista a face de quadra, deve ser alterada, caso contrario deve incluida
       */
      
      $oDadosAntigosFaceQuadra           = $this->getFaceQuadraSistema( $oRegistroImportado->ie26_setorcartografico, 
                                                                        $oRegistroImportado->ie26_quadracartografica, 
                                                                        $oRegistroImportado->ie26_codigologradouro);
      
      if ( $oDadosAntigosFaceQuadra ) {

        $iCodigoFace = $oDadosAntigosFaceQuadra->j37_face;

        //$this->oLog->escreverLog("Alterando registros da face de quadra: {$iCodigoFace}", DBLog::LOG_INFO);
  
        /**
         * Somente Executa Alteracao quando valor for diferente 
         */
        if ($oDadosAntigosFaceQuadra->j37_valor != $oRegistroImportado->ie26_valorm2terreno) {

          /**
           * Alteracao dos dados da face de quadra 
           */
          $sUpdate                = "update cadastro.face                                         ";
          $sUpdate               .= "   set j37_valor = {$oRegistroImportado->ie26_valorm2terreno}";
          $sUpdate               .= " where j37_face  = {$iCodigoFace}                            ";
          $rsUpdate               = pg_query(Conexao::getInstancia()->getConexao(), $sUpdate);

          if ( !$rsUpdate ) {

            $this->oLog->escreverLog("Erro ao alterar registros da face de quadra: {$iCodigoFace}", DBLog::LOG_ERROR);
            
            throw new Exception("Erro ao alterar registros da face de quadra: {$iCodigoFace}: ". pg_last_error());
            
          }
          
          $this->oLog->escreverLog("Alterando registros da face de quadra {$iCodigoFace}: Valor terreno = {$oRegistroImportado->ie26_valorm2terreno}", DBLog::LOG_INFO);
          
        }

        /**
         * Exclusao das caracteristicas da face 
         */
        
        $this->oLog->escreverLog("Excluindo caracteristicas da face de quadra {$iCodigoFace}.", DBLog::LOG_INFO);
        
        $sDeleteCaracteristicas  = "delete from carface where j38_face = {$iCodigoFace}";
        $rsDeleteCaracteristicas = pg_query(conexao::getinstancia()->getconexao(), $sDeleteCaracteristicas);

        if ( !$rsDeleteCaracteristicas ) {
          
          $this->oLog->escreverLog("Erro ao excluir caracteristicas da face de quadra {$iCodigoFace}.", DBLog::LOG_ERROR);

          throw new Exception("Erro ao excluir caracteristicas da face de quadra {$iCodigoFace}.". pg_last_error());
          
        }

      } else {

        /**
         * Inclusão dos Dados
         */
        $this->aTabelas['face']->setByLineOfDBUtils($oDadosNovosFaceQuadra);
        $iCodigoFace = $this->aTabelas['face']->insertValue();
        $this->oLog->escreverLog("Adicionando Nova Face de Quadra: {$iCodigoFace}.", DBLog::LOG_INFO);
      }
      
      /**
       * Caracteristicas
       * 
       * ApÃ³s todas as caracteristicas estarem limpas na alteracao inclui as novas 
       * caracteristicas para o registro alterado e novo
       */
      
      $this->oLog->escreverLog("Adicionando novas características para face de quadra {$iCodigoFace}.", DBLog::LOG_INFO);

      
      /**       
       * Pavimentação
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_pavimentacao        == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(67, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();

      /**
       * Galerias Pluviais 
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_galeriaspluviais    == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(65, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();

      /**
       * Rede de Ãgua
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_redeagua            == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(64, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();

      /**
       * Rede de Esgoto 
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_redeesgoto          == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(70, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();

      /**
       * Rede Eletrica  
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_redeeletrica        == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(62, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();
      
      /**
       * IluminaÃ§Ã£o 
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_iluminacao          == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(61, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();

      /**
       * Rede telefonica 
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_redetelefonica      == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(63, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();

      /**
       * Coleta de Lixo 
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_coletalixo          == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(68, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();

      /**
       * VarriÃ§Ã£o 
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_varricao            == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(69, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();

      /**
       * Sujeito InundaÃ§Ãµes
       */
      $lTemCaracteristica                    = $oRegistroImportado->ie26_sujeitoinundacoes   == "t" ? true : false;
      $iCaracteristica                       = $this->getCaracteristica(66, $lTemCaracteristica);
      $oCaracteristicaFace                   = new stdClass();
      $oCaracteristicaFace->j38_face         = $iCodigoFace;
      $oCaracteristicaFace->j38_caract       = $iCaracteristica;
      $this->aTabelas['carface']->setByLineOfDBUtils($oCaracteristicaFace);
      $this->aTabelas['carface']->insertValue();

    }
    
    $this->aTabelas['face']->persist();
    $this->aTabelas['carface']->persist();
    $this->oLog->escreverLog("Finalizando processamento", DBLog::LOG_INFO);
    return true;
  }

  /**
   * Retorna cÃ³digo da Caracteristica 
   * 
   * @param  integer $iCodigoGrupo            
   * @param  bool    $lPossuiCaracteristica 
   * @access public
   * @return void
   */
  public function getCaracteristica ($iCodigoGrupo, $lPossuiCaracteristica) {
    
    foreach ($this->aDadosCaracteristicas as $oGrupo) {

      /**
       * Procurando grupo informado 
       */
      if ($oGrupo->codigo_grupo != $iCodigoGrupo) {
        continue;
      }

      if ($lPossuiCaracteristica) {
        return $oGrupo->codigo_possui_caracteristica;
      } else {
        return $oGrupo->codigo_nao_possui_caracteristica;
      }

    }

    throw new Exception("Grupo Informado não está configurado.");
  }

  /**
   * Retorna Face de Quadra AtravÃ©s Setor, Quadra e Logradouro
   * 
   * @param mixed $iCodigoSetor      - Setor da face de Quadra 
   * @param mixed $iCodigoQuadra     - Quadra da face de Quadra
   * @param mixed $iCodigoLogradouro - Logradouro da face de Quadra
   * @access public
   * @return mixed 
   */
  public function getFaceQuadraSistema( $iCodigoSetor, $iCodigoQuadra, $iCodigoLogradouro) {

    $sSqlFace = "select *                                 ";
    $sSqlFace.= "  from face                              ";
    $sSqlFace.= " where j37_setor  = '{$iCodigoSetor}'    ";
    $sSqlFace.= "   and j37_quadra = '{$iCodigoQuadra}'   ";
    $sSqlFace.= "   and j37_codigo = {$iCodigoLogradouro} ";


    $rsFaceQuadra = pg_query( Conexao::getInstancia()->getConexao(), $sSqlFace );

    if ( !$rsFaceQuadra ) {

      $this->oLog->escreverLog("Erro Buscar dados da Face de Quadra.", DBLog::LOG_ERROR);

      throw new Exception("Erro Buscar dados da Face de Quadra.".pg_last_error());
      
    }

    if ( pg_num_rows($rsFaceQuadra) > 0 ) {
      return db_utils::fieldsMemory($rsFaceQuadra, 0);
    }

    return false; 

  }
  
  public function verificaLogradouro ($iCodigoLogradouro, $iSequencialArquivo) {
    
    $sSqlLogradouro = "select * from ruas where j14_codigo = $iCodigoLogradouro";
    
    $rsLogradouro   = pg_query($sSqlLogradouro);
    
    if (!$rsLogradouro || pg_num_rows($rsLogradouro) == 0) {
      
      $this->oLog->escreverLog("Logradouro {$iCodigoLogradouro} não encontrado. Ignorando registro {$iSequencialArquivo} do arquivo.", DBLog::LOG_NOTICE);
      
      return false;
      
    }
    
    return true;
    
  }
  
  public function verificaSetor ($iCodigoSetor, $iSequencialArquivo) {
    
    $sSqlSetor = "select * from setor where j30_codi::varchar = '$iCodigoSetor'::varchar";
    
    $rsSetor   = pg_query($sSqlSetor);
    
    if (!$rsSetor || pg_num_rows($rsSetor) == 0) {
      
      $this->oLog->escreverLog("Setor {$iCodigoSetor} não encontrado. Ignorando registro {$iSequencialArquivo} do arquivo.", DBLog::LOG_NOTICE);
    
      return false;
    
    }
    
    return true;
    
    
  }
  
}

?>
