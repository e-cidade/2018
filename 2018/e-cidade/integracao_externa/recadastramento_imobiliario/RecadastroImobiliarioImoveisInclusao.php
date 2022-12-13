<?php
/**
 * Classe para processamento de inclusão de Imóveis no Recadastro Imobiliario
 * 
 * @uses     RecadastroImobiliarioImoveisInterface
 * @package  Recadastro Imobiliario
 * @author   Alberto Ferri Neto <alberto@dbseller.com.br> 
 * @revision $Author dbalberto $
 * @version  $Revision: 1.12 $
 */
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveis.interface.php");

class RecadastroImobiliarioImoveisInclusao implements RecadastroImobiliarioImoveisInterface {

  public $dDataInclusao;

  public $sNomeArquivo;   
  
  public $oRegistroArquivo;

  public $sSQL;     
  
  public $iCodigoLinha;
  
  public $sMensagemLog = '';

  public $iCodigoRegistro;
  
  public function log($sMensagem, $iTipoLog = DBLog::LOG_INFO) {
    
    $this->sMensagemLog .= $sMensagem."\n";
    
    RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog($sMensagem, $iTipoLog);
    
  }
  
  public function __construct($oRegistroArquivo) {
    
    $this->iCodigoRegistro = $oRegistroArquivo->iCodigoRegistro;
    
    if (!is_object($oRegistroArquivo)) {
      throw new Exception("#-- Erro --# - ".'Registro inválido para inclusão.');
    }

    $this->oRegistroArquivo = $oRegistroArquivo;
    $this->dDataInclusao    = $oRegistroArquivo->oDataEnvio->getDate();
    $this->sNomeArquivo     = $oRegistroArquivo->sNomeArquivo;
    $this->sSQL             = $oRegistroArquivo->sSetorCartograficoNovo  . "/" ; 
    $this->sSQL            .= $oRegistroArquivo->sQuadraCartograficaNovo . "/" ;
    $this->sSQL            .= $oRegistroArquivo->sLoteCartograficoNovo;
    $this->iCodigoLinha     = $oRegistroArquivo->iSequencial;
    
    $this->log("+------------------------------------------------------------------------------+", DBLog::LOG_INFO);      
    $this->log("| PROCESSANDO INFORMAÇÕES DE INCLUSÃO DO CÓDIGO SEQUENCIAL [{$this->iCodigoLinha}] DO ARQUIVO |", DBLog::LOG_INFO);     
    $this->log("+------------------------------------------------------------------------------+", DBLog::LOG_INFO);
           
  }

  public function processar() {

    $lExisteReferenciaAnterior = $this->validarExistenciaLote();

    if ( $lExisteReferenciaAnterior ) {

      $this->log("Já Existe Imovel cadastrado com esta referencia Anterior" . $this->getCodigoAnteriorConstrucao(true, false), DBLog::LOG_ERROR);
      return false;
    }

    if (!$iCodigoLote = $this->getLote()) {
      
      $sMensagem = "Erro ao incluir idbql para o setor/quadra/lote: " . $this->sSQL;
      $sMensagem.= " Descricao do Erro: " . pg_last_error();
      $this->log($sMensagem, DBLog::LOG_ERROR);
      throw new Exception ("#-- Erro --# - ".$sMensagem);
    }

    $this->log("Idbql {$iCodigoLote} para o setor/quadra/lote: " . $this->sSQL  , DBLog::LOG_INFO );  

    if (!$iCodigoCgm = $this->getCgm($iCodigoLote)) {

      $this->log("#-- Erro --# - Não encontrado cgm para o CPF do setor/quadra/lote: " . $this->sSQL . ". Ignorando cadastro...", DBLog::LOG_NOTICE );
      return false;
    }

    $this->log("CGM {$iCodigoCgm} para cadastro do setor/quadra/lote " . $this->sSQL, DBLog::LOG_INFO );  
    
    if ( !$iMatricula = $this->novaMatricula($iCodigoLote, $iCodigoCgm) ) {
      
      $sMensagem = "Erro ao incluir matrícula para o setor/quadra/lote: " . $this->sSQL;

      $this->log($sMensagem , DBLog::LOG_ERROR );

      throw new Exception ("#-- Erro --# - ".$sMensagem); 

    }      
    
    $this->log("Incluindo matrícula {$iMatricula} para o setor/quadra/lote: " . $this->sSQL , DBLog::LOG_INFO );
    
    $this->incluirReferenciaAnterior($iMatricula);

    $this->iMatricula = $iMatricula;

    $this->registrarOcorrencia();
    
    $this->registraLog();
    
    return true;
  }
  
  public function registraLog() {
    
    $this->sMensagemLog = pg_escape_string(Conexao::getInstancia()->getConexao(), $this->sMensagemLog);
    
    $sUpdateRecadastroImobiliarioImoveis  = "update recadastroimobiliarioimoveis                 ";
    $sUpdateRecadastroImobiliarioImoveis .= "   set ie28_processado  = 't',                      ";
    $sUpdateRecadastroImobiliarioImoveis .= "       ie28_observacoes = '{$this->sMensagemLog}'   ";
    $sUpdateRecadastroImobiliarioImoveis .= " where ie28_sequencial  =  {$this->iCodigoRegistro} ";
    
    if (!pg_query(Conexao::getInstancia()->getConexao(), $sUpdateRecadastroImobiliarioImoveis)) {
      
      $sMensagem = "Erro ao salvar log das operações do setor/quadra/lote: {$this->sSQL}.";
      
      $this->log($sMensagem, DBLog::LOG_ERROR);
      
      throw new Exception("#-- Erro --# - ".$sMensagem);
      
    }
    
    $this->log("Log do registro das operações executadas para o setor/quadra/lote {$this->sSQL} salvo nas observações do registro {$this->iCodigoRegistro}.", DBLog::LOG_INFO);
    
    return true;
    
  }
   
  public function incluirCaracteristicasConstrucao ($iMatricula, $iCodigoConstrucao) {
    
    $aCodigoCaracteristicaArquivo                      = array();
    $aCodigoCaracteristicaArquivo['utilizacao']        = (int) $this->oRegistroArquivo->iCaracteristicaUtilizacaoNovo         ; //Utilização novo
    $aCodigoCaracteristicaArquivo['localizacao']       = (int) $this->oRegistroArquivo->iCaracteristicaLocalizacaoUnidadeNovo ; //Localização da unidade novo
    $aCodigoCaracteristicaArquivo['tipo']              = (int) $this->oRegistroArquivo->iCaracteristicaTipoNovo               ; //Tipo novo
    $aCodigoCaracteristicaArquivo['padraoconstrutivo'] = (int) $this->oRegistroArquivo->iCaracteristicaPadraoConstrutivoNovo  ; //Padrão construtivo novo
    $aCodigoCaracteristicaArquivo['conservacao']       = (int) $this->oRegistroArquivo->iCaracteristicaConservacaoNovo        ; //Conservação novo
    $aCodigoCaracteristicaArquivo['uso']               = (int) $this->oRegistroArquivo->iCaracteristicaUsoNovo                ; //Uso novo
    $aCodigoCaracteristicaArquivo['estrutura']         = (int) $this->oRegistroArquivo->iCaracteristicaEstruturaNovo          ; //Estrutura novo
    $aCodigoCaracteristicaArquivo['agua']              = (int) $this->oRegistroArquivo->iCaracteristicaAguaNovo               ; //Água novo
    $aCodigoCaracteristicaArquivo['esgoto']            = (int) $this->oRegistroArquivo->iCaracteristicaEsgotoNovo             ; //Esgoto novo
    $aCodigoCaracteristicaArquivo['eletrica']          = (int) $this->oRegistroArquivo->iCaracteristicaEnergiaEletricaNovo    ; //Instalação elétrica novo
    $aCodigoCaracteristicaArquivo['sanitaria']         = (int) $this->oRegistroArquivo->iCaracteristicaInstalacaoSanitariaNovo; //Instalação sanitária novo
    $aCodigoCaracteristicaArquivo['cobertura']         = (int) $this->oRegistroArquivo->iCaracteristicaCoberturaNovo          ; //Cobertura novo
    $aCodigoCaracteristicaArquivo['esquadria']         = (int) $this->oRegistroArquivo->iCaracteristicaEsquadriaNovo          ; //Esquadria novo
    $aCodigoCaracteristicaArquivo['piso']              = (int) $this->oRegistroArquivo->iCaracteristicaPisoNovo               ; //Piso novo
    $aCodigoCaracteristicaArquivo['revestimento']      = (int) $this->oRegistroArquivo->iCaracteristicaRevestimentoExternoNovo; //Revestimento
    $aCodigoCaracteristicaArquivo['pavimento']         = (int) $this->oRegistroArquivo->iNumeroPavimentosNovo                 ; //Código da característica pavimento
    
    $aCaracteristicas = getCaracteristicasConstrucao();
     
    /**
     * Todos imóveis devem possuir essa característica na construção
     */
    $sInsertCaracteristica     = "insert into carconstr                    ";
    $sInsertCaracteristica    .= "       (j48_matric,                      ";
    $sInsertCaracteristica    .= "        j48_idcons,                      ";
    $sInsertCaracteristica    .= "        j48_caract)                      ";
    $sInsertCaracteristica    .= "values ($iMatricula,                     ";
    $sInsertCaracteristica    .= "        1,                               ";
    $sInsertCaracteristica    .= "        710)";
    
    if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertCaracteristica)) {
    
      $sMensagem = "ERRO ao incluir característica de construção para a matrícula {$iMatricula} do setor/quadra/lote:" . $this->sSQL;
    
      $this->log($sMensagem, DBLog::LOG_ERROR );
    
      throw new Exception("#-- Erro --# - ".$sMensagem);
    
    }
    
    foreach ($aCaracteristicas as $sGrupo=>$aCaracteristica) {
      
      /**
       * $sGrupo = nome do grupo
       * $aCodigoCaracteristica[$sGrupo] = Código do caracteristica informada no arquivo
       * $aCaracteristica[$aCodigoCaracteristica[$sGrupo]] = Código do sistema e-cidade
       */
  //    if ( !isset($aCodigoCaracteristicaArquivo[$sGrupo]) || $aCodigoCaracteristicaArquivo[$sGrupo]  == '') {
      if ( $aCodigoCaracteristicaArquivo[$sGrupo]  == '' ) {

        $this->log("Construção da matrícula {$iMatricula}, setor/quadra/lote: {$this->sSQL} sem caracteristica {$sGrupo}. Ignorando...", DBLog::LOG_INFO);

        continue;

      }

      

      if ( !isset( $aCaracteristica[$aCodigoCaracteristicaArquivo[$sGrupo]] ) ) {
         $this->log("Caracteristica do grupo $sGrupo nao encontrada para o S/Q/L".
         
              $this->oRegistroArquivo->sSetorCartograficoAnterior."/".
              $this->oRegistroArquivo->sQuadraCartograficaAnterior."/".
              $this->oRegistroArquivo->sLoteCartograficoAnterior, DBLog::LOG_ERROR);
         continue;
      }

      $iCodigoCaracteristicaConstrucao = (int) $aCaracteristica[$aCodigoCaracteristicaArquivo[$sGrupo]];
      
      if ($iCodigoCaracteristicaConstrucao == 710) {
        continue;
      }
                                                   
      $sInsertCaracteristica     = "insert into carconstr                    ";
      $sInsertCaracteristica    .= "       (j48_matric,                      ";
      $sInsertCaracteristica    .= "        j48_idcons,                      ";
      $sInsertCaracteristica    .= "        j48_caract)                      ";
      $sInsertCaracteristica    .= "values ($iMatricula,                     ";
      $sInsertCaracteristica    .= "        1,                               ";
      $sInsertCaracteristica    .= "        $iCodigoCaracteristicaConstrucao)";


      if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertCaracteristica)) {

        $sMensagem = "ERRO ao incluir característica de construção para a matrícula {$iMatricula} do setor/quadra/lote:" . $this->sSQL;
        
        $this->log($sMensagem, DBLog::LOG_ERROR );
        
        throw new Exception("#-- Erro --# - ".$sMensagem);
        
      }        
      
      $this->log("Incluindo característica de construção '{$iCodigoCaracteristicaConstrucao} - {$sGrupo}' para a matrícula {$iMatricula} do setor/quadra/lote:" . $this->sSQL, DBLog::LOG_INFO );
      
    }
    
    return true;

  }

  public function incluirCaracteristicasLote ($iCodigoLote) {

    $aCodigoCaracteristicaArquivo                   = array();
    $aCodigoCaracteristicaArquivo['propriedade']    = (int) $this->oRegistroArquivo->iCaracteristicaPropriedadeNova  ;
    $aCodigoCaracteristicaArquivo['situacao']       = (int) $this->oRegistroArquivo->iCaracteristicaSituacaoNovo     ;
    $aCodigoCaracteristicaArquivo['caracteristica'] = (int) $this->oRegistroArquivo->iCaracteristicaNovo             ;
    $aCodigoCaracteristicaArquivo['nivel']          = (int) $this->oRegistroArquivo->iCaracteristicaNivelNovo        ;
    $aCodigoCaracteristicaArquivo['frentes']        = (int) $this->oRegistroArquivo->iCaracteristicaNumeroFrentesNovo;
    $aCodigoCaracteristicaArquivo['ocupacao']       = (int) $this->oRegistroArquivo->iCaracteristicaOcupacaoNovo     ;
    
    $aCaracteristicas = getCaracteristicasLote();
    
    foreach ($aCaracteristicas as $sGrupo=>$aCaracteristica) {
      
      /**
       * $sGrupo = nome do grupo
       * $aCodigoCaracteristica[$sGrupo] = Código do caracteristica informada no arquivo
       * $aCaracteristica[$aCodigoCaracteristica[$sGrupo]] = Código do sistema e-cidade
       */
      if ($aCodigoCaracteristicaArquivo[$sGrupo] == '') {
        $this->log("Sem caracteristica {$sGrupo} para o setor/quadra/lote: " . $this->sSQL . ". Ignorando...", DBLog::LOG_INFO);
        continue;
      }

      $iCodigoCaracteristicaLote = $aCaracteristica[$aCodigoCaracteristicaArquivo[$sGrupo]];
                                                   
      $sInsertCaracteristica     = "insert into carlote (j35_idbql,                 ";
      $sInsertCaracteristica    .= "                     j35_caract,                ";
      $sInsertCaracteristica    .= "                     j35_dtlanc)                ";
      $sInsertCaracteristica    .= "values              ($iCodigoLote,              ";
      $sInsertCaracteristica    .= "                     $iCodigoCaracteristicaLote,";
      $sInsertCaracteristica    .= "                     '$this->dDataInclusao')    ";                                     
      
      if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertCaracteristica)) {
        
        $sMensagem = "ERRO ao incluir características {$sGrupo} para o setor/quadra/lote: " . $this->sSQL;

        $this->log($sMensagem, DBLog::LOG_ERROR );

        throw new Exception("#-- Erro --# - ".$sMensagem);
        
      }        
      
      $this->log("Incluindo caracteristica de lote '{$iCodigoCaracteristicaLote} - {$sGrupo}' para o setor/quadra/lote: ". $this->sSQL, DBLog::LOG_INFO );
      
    }
    
    /**
     * Caso seja predial, deve-se incluir a posição fiscal 10 (510).
     * Caso seja territorial, deve-se incluir a posição fiscal 20 (520).
     */
    $iCodigoCaracteristicaLote = $this->oRegistroArquivo->lExisteAreaContruida ? 510 : 520;
    
    $sInsertCaracteristica     = "insert into carlote (j35_idbql,                 ";
    $sInsertCaracteristica    .= "                     j35_caract,                ";
    $sInsertCaracteristica    .= "                     j35_dtlanc)                ";
    $sInsertCaracteristica    .= "values              ($iCodigoLote,              ";
    $sInsertCaracteristica    .= "                     $iCodigoCaracteristicaLote,";
    $sInsertCaracteristica    .= "                     '$this->dDataInclusao')    ";
    
    if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertCaracteristica)) {
      $sMensagem = "ERRO ao incluir características Posição Fiscal para o setor/quadra/lote: " . $this->sSQL;
      $this->log($sMensagem, DBLog::LOG_ERROR );
      throw new Exception("#-- Erro --# - ".$sMensagem);
    }
    
    /**
     * Caso possua a característca 44 do grupo 21 (Gleba), inclui a característica 600 (área) do grupo 45
     * Caso contrário, incluir a característica 601 (lote)
     */
    $iCodigoCaracteristicaLote = $this->oRegistroArquivo->lExisteAreaContruida ? 510 : 520;
    
    $iCodigoCaracteristicaLote = $aCodigoCaracteristicaArquivo['situacao'] ;
    
    $sInsertCaracteristica     = "insert into carlote (j35_idbql,                 ";
    $sInsertCaracteristica    .= "                     j35_caract,                ";
    $sInsertCaracteristica    .= "                     j35_dtlanc)                ";
    $sInsertCaracteristica    .= "values              ($iCodigoLote,              ";
    $sInsertCaracteristica    .= "                     $iCodigoCaracteristicaLote,";
    $sInsertCaracteristica    .= "                     '$this->dDataInclusao')    ";
    
    if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertCaracteristica)) {
      $sMensagem = "ERRO ao incluir características Posição Fiscal para o setor/quadra/lote: " . $this->sSQL;
      $this->log($sMensagem, DBLog::LOG_ERROR );
      throw new Exception("#-- Erro --# - ".$sMensagem);
    }
    
    
    return true;

  }
  
  public function novaMatricula($iCodigoLote, $iCodigoCgm) {
     
    $rsMatricula       = pg_query(Conexao::getInstancia()->getConexao(), "select nextval('iptubase_j01_matric_seq') as matricula");

    $iMatricula        = db_utils::fieldsMemory($rsMatricula, 0)->matricula; 

    $sInsertMatricula  = "insert into iptubase           ";
    $sInsertMatricula .= "       (j01_matric,            ";
    $sInsertMatricula .= "        j01_numcgm,            ";
    $sInsertMatricula .= "        j01_idbql ,            ";
    $sInsertMatricula .= "        j01_baixa ,            ";
    $sInsertMatricula .= "        j01_codave,            ";
    $sInsertMatricula .= "        j01_fracao)            ";
    $sInsertMatricula .= "values ('$iMatricula' ,        ";
    $sInsertMatricula .= "        '$iCodigoCgm' ,        ";
    $sInsertMatricula .= "        '$iCodigoLote',        ";
    $sInsertMatricula .= "        null,                  ";
    $sInsertMatricula .= "        1,                     ";
    $sInsertMatricula .= "        0)                     ";

    if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertMatricula)) {

      $sMensagem = "Não foi possível gerar uma matricula para o novo setor/quadra/lote: " . $this->sSQL;
      
      $this->log($sMensagem, DBLog::LOG_ERROR );

      throw new Exception ($sMensagem);

    }
    

    if ($this->oRegistroArquivo->lExisteAreaContruida) {

      $this->incluirConstrucao($iMatricula);
      
    }

    return $iMatricula;      

  }
   
  public function incluirConstrucao($iMatricula) {

    $iCodigoConstrucao        = 1;
    
    $iAnoConstrucao           = date('Y', time($this->dDataInclusao));
    
    $nAreaConstruida          = (float)  $this->oRegistroArquivo->nAreaConstruidaNova;
    $dDataLancamento          =          $this->dDataInclusao;
    $iCodigoLogradouro        = (int)    $this->oRegistroArquivo->iCodigoLogradouroNovo;
    $iNumeroConstrucao        = (int)    $this->oRegistroArquivo->sNumeroPortaNovo;
    $sComplemento             = (string) $this->oRegistroArquivo->sComplementoNovo;
    $iNumeroPavimentos        = (int)    $this->oRegistroArquivo->iNumeroPavimentosNovo; 
    
    $lConstrucaoPrincipal     = 't';
    $sObservacao              = "Construção incluída pelo recadastramento do arquivo \'{$this->oRegistroArquivo->sNomeArquivo}\'.";

    $sInsertIptuconstr        = "insert into iptuconstr           ";
    $sInsertIptuconstr       .= "       (j39_matric,              ";
    $sInsertIptuconstr       .= "        j39_idcons,              ";
    $sInsertIptuconstr       .= "        j39_ano,                 ";
    $sInsertIptuconstr       .= "        j39_area,                ";
    $sInsertIptuconstr       .= "        j39_dtlan,               ";
    $sInsertIptuconstr       .= "        j39_codigo,              ";
    $sInsertIptuconstr       .= "        j39_numero,              ";
    $sInsertIptuconstr       .= "        j39_compl,               ";
    $sInsertIptuconstr       .= "        j39_pavim,               ";
    $sInsertIptuconstr       .= "        j39_idprinc,             ";
    $sInsertIptuconstr       .= "        j39_obs)                 ";
    $sInsertIptuconstr       .= "values ('$iMatricula'          , ";
    $sInsertIptuconstr       .= "        '$iCodigoConstrucao'   , ";
    $sInsertIptuconstr       .= "        '$iAnoConstrucao'      , ";
    $sInsertIptuconstr       .= "        '$nAreaConstruida'     , ";
    $sInsertIptuconstr       .= "        '$dDataLancamento'     , ";
    $sInsertIptuconstr       .= "        '$iCodigoLogradouro'   , ";
    $sInsertIptuconstr       .= "        '$iNumeroConstrucao'   , ";
    $sInsertIptuconstr       .= "        '$sComplemento'        , ";
    $sInsertIptuconstr       .= "        '$iNumeroPavimentos'   , ";
    $sInsertIptuconstr       .= "        true, ";
    $sInsertIptuconstr       .= "        '$sObservacao')          ";

    if ( !pg_query(Conexao::getInstancia()->getConexao(), $sInsertIptuconstr)) {
      
      $sMensagem = "#-- Erro --# - Não foi possível incluir a construção {$iCodigoConstrucao} para a matrícula {$iMatricula} do setor/quadra/lote: " . $this->sSQL;
      $this->log($sMensagem, DBLog::LOG_ERROR );
      throw new Exception("#-- Erro --# - ".$sMensagem);

    }

    $this->log("Incluindo construção {$iCodigoConstrucao} para a matrícula {$iMatricula} do setor/quadra/lote: " . $this->sSQL, DBLog::LOG_INFO );
    $this->incluirCaracteristicasConstrucao ($iMatricula, $iCodigoConstrucao);

  }

  public function getCgm($iCodigoLote = null) {
    
    if($iCodigoLote != '') {

      $sSqlCgm = "select distinct j01_numcgm from iptubase where j01_idbql = {$iCodigoLote}";

      $rsCgm   = pg_query(Conexao::getInstancia()->getConexao(), $sSqlCgm);

      /**
       *  A matrícula será vinculada ao cgm apenas se o lote for vinculado a 1(um) proprietário
       */ 
      if (pg_num_rows($rsCgm) == 1) {
        return db_utils::fieldsMemory($rsCgm, 0)->j01_numcgm;
      }

    }

    if (trim($this->oRegistroArquivo->sCPFProprietarioNovo) != '') {

      $sSqlCgm  = "select z01_numcgm                                                                 ";
      $sSqlCgm .= "  from cgm                                                                        ";
      $sSqlCgm .= " where cgm.z01_cgccpf = '".trim($this->oRegistroArquivo->sCPFProprietarioNovo)."' ";

      $rsCgm    = pg_query(Conexao::getInstancia()->getConexao(), $sSqlCgm);

      if ( pg_num_rows($rsCgm) > 0) {
        return db_utils::fieldsMemory($rsCgm, 0)->z01_numcgm;
      }

    }

    /**
     * Alterar por cgm definido pela prefeitura no arquivo de configuracoes
     */ 
    
    $aConfiguracoes = (object)parse_ini_file(PATH_IMPORTACAO . "libs/configuracoes_importacao.ini",true);

    $sMensagem = "Erro na configuração de cgm para recadastramento da prefeitura. Favor informar um cgm válido no arquivo de configurações, campo 'cgm_recadastramento'.";

    if (trim($aConfiguracoes->geral['cgm_recadastramento']) == '') {

      $this->log("#-- Erro --# - ". $sMensagem, DBLog::LOG_ERROR );
      throw new Exception($sMensagem);   
    }
    
    $sSqlCgm        = "select * from cgm where z01_numcgm = '".$aConfiguracoes->geral['cgm_recadastramento']."'";

    $rsCgm          = pg_query(Conexao::getInstancia()->getConexao(), $sSqlCgm);

    if (pg_num_rows($rsCgm) == 0) {

      $this->log("#-- Erro --# - ".$sMensagem, DBLog::LOG_ERROR );
      throw new Exception($sMensagem);  
    }


    return $aConfiguracoes->geral['cgm_recadastramento']; 

  }

  public function getLote () {

    $sSetor  = $this->oRegistroArquivo->sSetorCartograficoNovo ;
    $sQuadra = $this->oRegistroArquivo->sQuadraCartograficaNovo;
    $sLote   = $this->oRegistroArquivo->sLoteCartograficoNovo  ;
    
    $sSqlLote  = "select j34_idbql               ";
    $sSqlLote .= "  from lote                    ";
    $sSqlLote .= " where j34_setor  = '$sSetor'  ";
    $sSqlLote .= "   and j34_quadra = '$sQuadra' ";
    $sSqlLote .= "   and j34_lote   = '$sLote'   ";

    $rsLote    = pg_query(Conexao::getInstancia()->getConexao(), $sSqlLote);

    if ( pg_num_rows($rsLote) > 0) {
      return db_utils::fieldsMemory($rsLote, 0)->j34_idbql;
    } 

    return $this->incluirLote();
  }

  public function incluirLote() {

    $rsLote       = pg_query(Conexao::getInstancia()->getConexao(), "select nextval('lote_j34_idbql_seq') as codigo_lote");
    $iCodigoLote  = db_utils::fieldsMemory($rsLote, 0)->codigo_lote;
    
    $sSetor          = (string) $this->oRegistroArquivo->sSetorCartograficoNovo ;
    $sQuadra         = (string) $this->oRegistroArquivo->sQuadraCartograficaNovo;
    $sLote           = (string) $this->oRegistroArquivo->sLoteCartograficoNovo  ;
    $nArea           = (float)  $this->oRegistroArquivo->nAreaTerrenoNova       ;
    $iCodigoBairro   = (int)    $this->oRegistroArquivo->sBairroNovo            ;
    $nAreaTerreno    = (float)  $this->oRegistroArquivo->nAreaTerrenoNova       ;
    $nAreaConstruida = (float)  $this->oRegistroArquivo->nAreaConstruidaNova    ;

    $sInsertLote  = "insert into lote                 ";
    $sInsertLote .= "       (j34_idbql,               ";
    $sInsertLote .= "        j34_setor,               ";
    $sInsertLote .= "        j34_quadra,              ";
    $sInsertLote .= "        j34_lote,                ";
    $sInsertLote .= "        j34_area,                ";
    $sInsertLote .= "        j34_bairro,              ";
    $sInsertLote .= "        j34_areal,               ";
    $sInsertLote .= "        j34_totcon,              ";
    $sInsertLote .= "        j34_zona,                ";
    $sInsertLote .= "        j34_quamat,              ";
    $sInsertLote .= "        j34_areapreservada)      ";
    $sInsertLote .= "values ('{$iCodigoLote        }',";
    $sInsertLote .= "        '{$sSetor             }',";
    $sInsertLote .= "        '{$sQuadra            }',";
    $sInsertLote .= "        '{$sLote              }',";
    $sInsertLote .= "        '{$nArea              }',";
    $sInsertLote .= "        '{$iCodigoBairro      }',";
    $sInsertLote .= "        '{$nAreaTerreno       }',";
    $sInsertLote .= "        '{$nAreaConstruida    }',"; 
    $sInsertLote .= "        '99',                    ";
    $sInsertLote .= "        '0',                     ";
    $sInsertLote .= "        '0');                    ";

    if ( pg_query(Conexao::getInstancia()->getConexao(), $sInsertLote) ){

      $this->incluirTestadaLote($iCodigoLote);
      
      $this->incluirCaracteristicasLote($iCodigoLote);

      $this->incluirLoteLocalizacao($iCodigoLote);

      $this->incluirLoteamento($iCodigoLote);

      $this->incluirLoteFiscal($iCodigoLote);

      return $iCodigoLote;
      
    }  
    $this->log("#-- Erro --# - Não Foi Possivel Incluir o Lote");
    return false; 
  }

  public function incluirLoteamento($iCodigoLote) {
    
    $sSqlLoteamento  = "select *                                                                           ";
    $sSqlLoteamento .= "  from loteam                                                                      ";
    $sSqlLoteamento .= " where j34_descr ~ '{$this->oRegistroArquivo->iPlantaLoteamentoNovo}'";
    
    $rsLoteamento    = pg_query(Conexao::getInstancia()->getConexao(), $sSqlLoteamento);
  
    if (pg_num_rows($rsLoteamento) == 0) {
      
      $rsCodigoLoteamento = pg_query(Conexao::getInstancia()->getConexao(), "select nextval('loteam_j34_loteam_seq') as codigo_loteamento");

      $iCodigoLoteamento  = db_utils::fieldsMemory($rsCodigoLoteamento, 0)->codigo_loteamento;
      
      $sInsertLoteamento  = "insert into loteam                                                           ";
      $sInsertLoteamento .= "       (j34_loteam,                                                           ";
      $sInsertLoteamento .= "        j34_descr,                                                           ";
      $sInsertLoteamento .= "        j34_areacc,                                                          ";
      $sInsertLoteamento .= "        j34_areapc,                                                          ";
      $sInsertLoteamento .= "        j34_areato)                                                          ";
      $sInsertLoteamento .= "values ($iCodigoLoteamento,                                                  ";
      $sInsertLoteamento .= "        '{$this->oRegistroArquivo->iPlantaLoteamentoNovo} - RECADASTRAMENTO',";
      $sInsertLoteamento .= "        0,                                                                   ";
      $sInsertLoteamento .= "        0,                                                                   ";
      $sInsertLoteamento .= "        0)                                                                   ";

      if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertLoteamento)) {                            
      
        $sMensagem = "[0]Erro ao incluir novo loteamento para o setor/quadra/lote: " .$this->sSQL. "Descricao do Erro : ".pg_last_error();
        
        $this->log($sMensagem, DBLog::LOG_ERROR);
        
        throw new Exception($sMensagem);

      }

    } else {

      $iCodigoLoteamento      = db_utils::fieldsMemory($rsLoteamento, 0)->j34_loteam; 

    }
   
    
    $sInsertLoteLoteam  = "insert into loteloteam (j34_idbql,    j34_loteam)        ";
    $sInsertLoteLoteam .= "                values ($iCodigoLote, $iCodigoLoteamento)"; 

    if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertLoteLoteam)) {                            

      $sMensagem = "Erro ao vincular lote ao loteamento para o setor/quadra/lote: " . $this->sSQL . "Descricao do Erro : ".pg_last_error();

      $this->log($sMensagem, DBLog::LOG_ERROR);

      throw new Exception($sMensagem);

    }
    
    $this->log("Incluido loteamento para o setor/quadra/lote: " . $this->sSQL, DBLog::LOG_INFO);   

    return true;
  
  }

  public function incluirLoteLocalizacao ($iCodigoLote) {
    
    $rsLoteloc = pg_query(Conexao::getInstancia()->getConexao(), "select * from loteloc where j06_idbql = {$iCodigoLote}");

    if (pg_num_rows($rsLoteloc) > 0) {

      $this->log("Lote de localização já cadastrado para o setor/quadra/lote: " . $this->sSQL . ". Ignorando...", DBLog::LOG_INFO );   

      return true;

    }

    $iCodigoSetorLocalizacao = $this->getSetorLocalizacao($iCodigoLote);
    
    $sQuadra = (string) $this->oRegistroArquivo->iQuadraLoteamentoNovo;
    $sLote   = (string) $this->oRegistroArquivo->iLoteLoteamentoNovo;
    
    $sInsertLoteloc  = "insert into loteloc                                        ";
    $sInsertLoteloc .= "       (j06_idbql,                                         ";
    $sInsertLoteloc .= "        j06_setorloc,                                      ";
    $sInsertLoteloc .= "        j06_quadraloc,                                     ";
    $sInsertLoteloc .= "        j06_lote)                                          ";
    $sInsertLoteloc .= "values ('{$iCodigoLote}',                                  ";
    $sInsertLoteloc .= "        '{$iCodigoSetorLocalizacao}',                      ";
    $sInsertLoteloc .= "        '{$this->oRegistroArquivo->iQuadraLoteamentoNovo}',";
    $sInsertLoteloc .= "        '{$this->oRegistroArquivo->iLoteLoteamentoNovo}')  ";

    if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertLoteloc)) {

      $sMensagem = "Erro ao incluir Lote de Localização o setor/quadra/lote: " . $this->sSQL; 

      $this->log($sMensagem, DBLog::LOG_ERROR);

      throw new Exception($sMensagem);

    }

    $this->log("Lote de localização incluído com sucesso para o setor/quadra/lote: " . $this->sSQL, DBLog::LOG_INFO); 

  }  

  public function incluirLoteFiscal($iCodigoLote) {

    $sInsertSetorFiscal  = "insert into lotesetorfiscal ";
    $sInsertSetorFiscal .= "       (j91_idbql,          ";
    $sInsertSetorFiscal .= "        j91_codigo)         ";
    $sInsertSetorFiscal .= "values ($iCodigoLote,       ";
    $sInsertSetorFiscal .= "        99)                 ";

    if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertSetorFiscal)) {
    
      $sMensagem = "Erro ao incluir Lote Setor Fiscal para o setor/quadra/lote: " . $this->sSQL;
      
      $this->log($sMensagem, DBLog::LOG_ERROR );   

      throw new Exception ($sMensagem);

    }  
    
    $this->log("Incluído Lote Setor Fiscal para o setor/quadra/lote: " . $this->sSQL, DBLog::LOG_INFO);

  }  

  public function getSetorLocalizacao($iCodigoLote) {

    $sSqlSetorLoc  = "select j05_codigo as codigo_setorloc                                         ";
    $sSqlSetorLoc .= "  from setorloc                                                              ";
    $sSqlSetorLoc .= " where j05_codigoproprio = '{$this->oRegistroArquivo->iPlantaLoteamentoNovo}'";
    
    $rsSetorLoc    = pg_query(Conexao::getInstancia()->getConexao(), $sSqlSetorLoc);

    if (!$rsSetorLoc) {              

      $sMensagem = "Erro ao consultar Setor de Localização para o setor/quadra/lote: " . $this->sSQL;

      $this->log($sMensagem, DBLog::LOG_ERROR );   

      throw new Exception ($sMensagem);

    }

    if (pg_num_rows($rsSetorLoc) == 0) {

      $rsSetorLoc       = pg_query(Conexao::getInstancia()->getConexao(), "select max(j05_codigo) + 1 as codigo_setorloc from setorloc");
      $iCodigoSetorLoc  = db_utils::fieldsMemory($rsSetorLoc, 0)->codigo_setorloc;

      $sInsertSetorloc  = "insert into setorloc                                                         ";
      $sInsertSetorloc .= "       (j05_codigo,                                                          ";
      $sInsertSetorloc .= "        j05_descr,                                                           ";
      $sInsertSetorloc .= "        j05_codigoproprio)                                                   ";
      $sInsertSetorloc .= "values ('{$iCodigoSetorLoc}',                                                ";
      $sInsertSetorloc .= "        '{$this->oRegistroArquivo->iPlantaLoteamentoNovo} - RECADASTRAMENTO',";
      $sInsertSetorloc .= "        '{$this->oRegistroArquivo->iPlantaLoteamentoNovo}')                  ";
       
      if (!pg_query( Conexao::getInstancia()->getConexao() , $sInsertSetorloc)) {

        $sMensagem = "Erro ao incluir Setor de Localização para o setor/quadra/lote: " . $this->sSQL;
       
        $this->log($sMensagem, DBLog::LOG_ERROR );   

        throw new Exception ($sMensagem); 

      }

      $this->log("Incluído Setor de Localização para o setor/quadra/lote: " . $this->sSQL, DBLog::LOG_INFO);    
     
    } else {

      $iCodigoSetorLoc  = db_utils::fieldsMemory($rsSetorLoc, 0)->codigo_setorloc; 

    }

    return $iCodigoSetorLoc;

  }

  public function incluirTestadaLote($iCodigoLote) {
     
    /**
     * incluir testada 
     */
      
    $this->log("Incluindo testada para o setor/quadra/lote: " . $this->sSQL, DBLog::LOG_INFO );

    if ($iCodigoFace = $this->getFace($this->oRegistroArquivo->sSetorCartograficoNovo, 
                                      $this->oRegistroArquivo->sQuadraCartograficaNovo,
                                      $this->oRegistroArquivo->iCodigoLogradouroNovo)) {
      
      $iCodigoLogradouro = (int)   $this->oRegistroArquivo->iCodigoLogradouroNovo;                                                                                   
      $nValorTestada     = (float) $this->oRegistroArquivo->nValorTestadaPrincipalNova;
      $iNumero           = (int)   $this->oRegistroArquivo->sNumeroPortaNovo;
      $sComplemento      = (string)$this->oRegistroArquivo->sComplementoNovo;
      
      $sInsertTestada  = "insert into testada              ";
      $sInsertTestada .= "       (j36_idbql,               ";
      $sInsertTestada .= "        j36_face,                ";
      $sInsertTestada .= "        j36_codigo,              ";
      $sInsertTestada .= "        j36_testad,              ";
      $sInsertTestada .= "        j36_testle)              ";
      $sInsertTestada .= "values ({$iCodigoLote},          ";
      $sInsertTestada .= "        {$iCodigoFace},          ";
      $sInsertTestada .= "        {$iCodigoLogradouro},    ";
      $sInsertTestada .= "        {$nValorTestada},        ";
      $sInsertTestada .= "        0)                       ";

      if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertTestada)) {

        $sMensagem = "Erro ao incluir testada para o setor/quadra/lote: " . $this->sSQL;
        $this->log("#-- Erro --# - " . $sMensagem, DBLog::LOG_ERROR);
        throw new Exception($sMensagem);
      }

      $sInsertTestpri  = "insert into testpri                                      ";
      $sInsertTestpri .= "       (j49_idbql,                                       ";
      $sInsertTestpri .= "        j49_face,                                        ";
      $sInsertTestpri .= "        j49_codigo)                                      ";
      $sInsertTestpri .= "values ({$iCodigoLote},                                  ";
      $sInsertTestpri .= "        {$iCodigoFace},                                  ";
      $sInsertTestpri .= "        {$iCodigoLogradouro})";

      if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertTestpri)) {

        $sMensagem = "Erro ao incluir testpri para o setor/quadra/lote: " . $this->sSQL;

        $this->log($sMensagem, DBLog::LOG_ERROR );

        throw new Exception("#-- Erro --# - ".$sMensagem);
      
      }
                                       
      $sInsertTestadaNumero  = "insert into testadanumero                                                                        ";
      $sInsertTestadaNumero .= "       (j15_codigo,                                                                              ";
      $sInsertTestadaNumero .= "        j15_idbql ,                                                                              ";
      $sInsertTestadaNumero .= "        j15_face  ,                                                                              ";
      $sInsertTestadaNumero .= "        j15_numero,                                                                              ";
      $sInsertTestadaNumero .= "        j15_compl ,                                                                              ";
      $sInsertTestadaNumero .= "        j15_obs)                                                                                 ";
      $sInsertTestadaNumero .= "values (nextval('testadanumero_j15_codigo_seq'),                                                 ";
      $sInsertTestadaNumero .= "        {$iCodigoLote},                                                                          ";
      $sInsertTestadaNumero .= "        {$iCodigoFace},                                                                          ";
      $sInsertTestadaNumero .= "        {$iNumero},                                                                              ";
      $sInsertTestadaNumero .= "        '{$sComplemento}',                                                                       ";
      $sInsertTestadaNumero .= "        'Testada número incluído pelo recadastramento. Nome do arquivo: {$this->sNomeArquivo}.') ";

      if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertTestadaNumero)) {
                      
        $sMensagem = "Erro ao incluir testadanumero para o setor/quadra/lote: " . $this->sSQL;

        $this->log($sMensagem, DBLog::LOG_ERROR );
        throw new Exception("#-- Erro --# - ".$sMensagem);
      
      } 

    }

  }  

  public function getFace ($iCodigoSetor, $iCodigoQuadra, $iCodigoLogradouro) {

    $sSqlFace  = "select j37_face                           ";
    $sSqlFace .= "  from face                               ";
    $sSqlFace .= " where j37_setor  = '{$iCodigoSetor}'     ";
    $sSqlFace .= "   and j37_quadra = '{$iCodigoQuadra}'    ";
    $sSqlFace .= "   and j37_codigo = {$iCodigoLogradouro}  ";

    $rsFace  = pg_query(Conexao::getInstancia()->getConexao(), $sSqlFace);

    if (!$rsFace  || pg_num_rows($rsFace) == 0) {
    
      $sMensagem = "Face de quadra para o setor/quadra/lote: " . $this->sSQL . " não encontrada";
      $this->log($sMensagem, DBLog::LOG_ERROR );
      return false; //throw new Exception ($sMensagem);  
    }
    return db_utils::fieldsMemory($rsFace, 0)->j37_face;
  }


  public function registrarOcorrencia () {

    $aConfiguracoes         = (object)parse_ini_file(PATH_IMPORTACAO . "libs/configuracoes_importacao.ini",true);
    $iInstituicao           = $aConfiguracoes->sistema['instituicao_prefeitura'];

    $sInsertHistocorrencia  = "insert into histocorrencia                                                              "; 
    $sInsertHistocorrencia .= "       (ar23_sequencial  ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_id_usuario  ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_instit      ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_modulo      ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_id_itensmenu,                                                              ";
    $sInsertHistocorrencia .= "        ar23_data        ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_hora        ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_tipo        ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_descricao   ,                                                              ";
    $sInsertHistocorrencia .= "        ar23_ocorrencia)                                                                ";
    $sInsertHistocorrencia .= " values (nextval('histocorrencia_ar23_sequencial_seq'),                                 ";
    $sInsertHistocorrencia .= "         1,                                                                             ";
    $sInsertHistocorrencia .= "         {$iInstituicao},                                                               ";
    $sInsertHistocorrencia .= "         578,                                                                           ";
    $sInsertHistocorrencia .= "         1721,                                                                          ";
    $sInsertHistocorrencia .= "         '{$this->dDataInclusao}',                                                      ";
    $sInsertHistocorrencia .= "         '00:00',                                                                       ";
    $sInsertHistocorrencia .= "         2,                                                                             ";
    $sInsertHistocorrencia .= "         'Imóvel incluído pelo recadastramento. Nome do arquivo: {$this->sNomeArquivo}.',";
    $sInsertHistocorrencia .= "         'Imóvel incluído pelo recadastramento. Nome do arquivo: {$this->sNomeArquivo}.')";

    if (pg_query (Conexao::getInstancia()->getConexao(), $sInsertHistocorrencia)) {

      $sInsertHistocorrenciaMatric  = "insert into histocorrenciamatric                            ";
      $sInsertHistocorrenciaMatric .= "       (ar25_sequencial   ,                                 ";
      $sInsertHistocorrenciaMatric .= "        ar25_matric       ,                                 ";
      $sInsertHistocorrenciaMatric .= "        ar25_histocorrencia)                                ";
      $sInsertHistocorrenciaMatric .= "values (nextval('histocorrenciamatric_ar25_sequencial_seq'),";
      $sInsertHistocorrenciaMatric .= "        {$this->iMatricula},                                ";
      $sInsertHistocorrenciaMatric .= "        currval('histocorrencia_ar23_sequencial_seq'))      ";

      if (pg_query(Conexao::getInstancia()->getConexao(), $sInsertHistocorrenciaMatric)) {

        $this->log( "Incluindo histórico de ocorrência para a matrícula {$this->iMatricula} do setor/quadra/lote: " . $this->sSQL, DBLog::LOG_INFO );

        return true;

      }              

    }
    
    $this->log( "Erro ao incluir histórico de ocorrência para a matrícula {$this->iMatricula} do setor/quadra/lote: " . $this->sSQL. ". Continuando...'", DBLog::LOG_ERROR );

    return false;

  }

  public function incluirReferenciaAnterior($iMatricula) {
    
    $this->log("Incluindo referência anterior da matrícula {$iMatricula} para o setor/quadra/lote: " . $this->sSQL, DBLog::LOG_INFO ); 

    $sCodigoReferenciaAnterior = $this->getCodigoAnteriorConstrucao(false, false);

    $sInsertIptuRefAnt  = "insert into iptuant                   \n";
    $sInsertIptuRefAnt .= "       (j40_matric,                   \n";
    $sInsertIptuRefAnt .= "        j40_refant)                   \n";
    $sInsertIptuRefAnt .= "values ({$iMatricula},                \n";
    $sInsertIptuRefAnt .= "       '{$sCodigoReferenciaAnterior}')\n";

    if (!pg_query(Conexao::getInstancia()->getConexao(), $sInsertIptuRefAnt)) {

      $sMensagem = "Erro ao incluir referencia anterior para a matrícula {$this->iMatricula} do setor/quadra/lote: " . $this->sSQL; 

      $this->log($sMensagem, DBLog::LOG_INFO );

      throw new Exception("#-- Erro --# - ".$sMensagem);

    }

  }

  private function getCodigoAnteriorConstrucao( $lComparacao = false, $lDadosAntigos = false ) {

    /**
     * Código de Referencia Anterior do Imovel/Construcao
     *
     *       ??904234450603001
     *       |||  ||  ||  || |
     *       |||  ||  ||  |+-+---> Unidade Imobiliaria Novo
     *       |||  ||  |+--+------> Lote Cartografico Novo
     *       |||  |+--+----------> Quadra Cartografica Novo
     *       ||+--+--------------> Setor Cartografico Novo
     *       |+------------------> Fixo "2"
     *       +-------------------> Distrito Novo do Imóvel
     */
    $sCodigoReferenciaAnterior = "";

    if ( !$lComparacao ) {

      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sDistritoNovo)          , 1,"0", STR_PAD_LEFT );
      $sCodigoReferenciaAnterior .= "2";
    }

    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sSetorCartograficoNovo ), 4,"0", STR_PAD_LEFT );
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sQuadraCartograficaNovo), 4,"0", STR_PAD_LEFT );
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sLoteCartograficoNovo  ), 4,"0", STR_PAD_LEFT );
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sUnidadeImobiliariaNova), 3,"0", STR_PAD_LEFT );

    if ( $lDadosAntigos ) {

      $sCodigoReferenciaAnterior = "";
      if ( !$lComparacao ) {

        $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sDistritoNovo)          , 1,"0", STR_PAD_LEFT );
        $sCodigoReferenciaAnterior .= "2";
      }

      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sSetorCartograficoAnterior ) , 4,"0", STR_PAD_LEFT );
      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sQuadraCartograficaAnterior ), 4,"0", STR_PAD_LEFT );
      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sLoteCartograficoAnterior )  , 4,"0", STR_PAD_LEFT );
      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistroArquivo->sUnidadeImobiliariaAnterior) , 3,"0", STR_PAD_LEFT );
    }
    return $sCodigoReferenciaAnterior;
  }


  /**
   * validarExistenciaLote
   * 
   * @access public
   * @return void
   */
  public function validarExistenciaLote() {

    $sSql  = "select * from iptuant where j40_refant ~ '{$this->getCodigoAnteriorConstrucao(true, false)}'";
    $rsSql = pg_query($sSql);

    if ( !$rsSql ) {
      throw new Exception("#-- Erro --# - ".'Erro ao Buscar dados da Referencia Anterior'); 
    }

    if ( pg_num_rows($rsSql)  == 0 ) {
      
      $this->log("Lote Nao Encontrado: j40_refant" . $this->getCodigoAnteriorConstrucao(true, false));
      return false;
    }
    return true;
  }

 /**
  * getLog 
  * 
  * @access public
  * @return void
  */
 public function getLog() {
   return $this->sMensagemLog;
 }
}
