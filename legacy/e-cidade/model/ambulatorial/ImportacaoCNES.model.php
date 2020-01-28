<?php

/**
 * classe para importação de base CNES
 * @package   ambulatorial
 * @author    Andrio Costa - andrio.costa@gmail.com
 * @version   $Revision: 1.13 $
 */
class ImportacaoCNES {

  const MSG_IMPORTACAOCNES            = "saude.ambulatorial.ImportacaoCNES.";
  const LOG_TIPO_ESTABELECIMENTO      = 1;
  const LOG_TIPO_PROFISSIONAL         = 2;
  const LOG_TIPO_PROFISSIONAL_VINCULO = 3;

  /**
   * Array com todas as unidades que não foi encontrada no banco de dados buscando pelo cnes
   * @var array
   */
  private $aUnidadesNaoLocalizadas = array();

  /**
   * Guarda os CNES encontrados nas unidades existentes
   * @var array
   */
  private $aCNESVinculados = array();

  /**
   * Valida se houve um erro ou inconsistencia nas informações.
   * @var boolean
   */
  private $lInconsistencia = false;

  /**
   * Base xml do CNES
   * @var DOMDocument
   */
  private $oArquivoXML;

  /**
   * [$oLog description]
   * @var [type]
   */
  private $oLog;

  /**
   * Array de escolaridades
   * @var array
   */
  private $aEscolaridade = array (
                                  '0' => 'SEM DEFINIÇÃO',
                                  '1' => 'ANALFABETO',
                                  '2' => 'FUNDAMENTAL INCOMPLETO',
                                  '3' => 'FUNDAMENTAL COMPLETO',
                                  '4' => 'ENSINO MÉDIO INCOMPLETO',
                                  '5' => 'ENSINO MÉDIO COMPLETO',
                                  '6' => 'ENSINO SUPERIOR INCOMPLETO',
                                  '7' => 'ENSINO SUPERIOR COMPLETO',
                                  '8' => 'MESTRADO',
                                  '9' => 'DOUTORADO'
                                );

  private $aEstadoCivil = array (
                                 "1" => "Solteiro",
                                 "2" => "Casado",
                                 "3" => "Viúvo",
                                 "4" => "Divorciado",
                                 "5" => "Separado Consensual",
                                 "6" => "Separado Judicial",
                                 "7" => "União Estavel"
                                );

  /**
   * Array com os códigos de municipios do IBGE
   * @var array
   */
  private $aMunicipioIBGE = array();

  /**
   * Arrays com dos dados da Caracterização da Unidade
   * Usados para validar se os dados do arquivo xml existem na base de dados do e-cidade
   * @var array
   */
  private $aTiposUnidades        = array();
  private $aEsferaAdministrativa = array();
  private $aAtividade            = array();
  private $aRetencaoTributos     = array();
  private $aNaturezaOrganizacao  = array();
  private $aFluxoClientela       = array();
  private $aNivelHierarquia      = array();
  private $aTurnoAtendimento     = array();

  /**
   * Array comn os códigos dos CBO no e-cidade
   * @var array
   */
  private $aCbo = array();

  /**
   *
   * @param string $sPachtXml caminho onde esta a base xml
   * @param DBLog $oLog       instancia do arquivo de log
   */
  function __construct($sPachtXml, DBLog $oLog) {

    $oMsgErro           = new stdClass();
    $oMsgErro->sArquivo = $sPachtXml;

    if ( !file_exists($sPachtXml) ) {
      throw new BusinessException( _M( self::MSG_IMPORTACAOCNES."arquivo_nao_existe", $oMsgErro) );
    }

    if ( !is_readable($sPachtXml) ) {
      throw new BusinessException( _M( self::MSG_IMPORTACAOCNES."arquivo_nao_pode_ser_lido", $oMsgErro) );
    }

    $oArquivoXML = new DOMDocument();
    $oArquivoXML->load($sPachtXml);

    $oDaoUnidades      = new cl_unidades();
    $aEstabelecimentos = $oArquivoXML->getElementsByTagName('DADOS_GERAIS_ESTABELECIMENTOS');
    foreach ($aEstabelecimentos as $oEstabelecimento) {

      $iCNES             = $oEstabelecimento->getAttribute('CNES');
      $sWhere            = " sd02_v_cnes = '$iCNES' ";
      $sSqlValidaUnidade = $oDaoUnidades->sql_query_file(null, "1", null, $sWhere);
      $rsValidaUnidade   = db_query($sSqlValidaUnidade);

      if ($rsValidaUnidade && pg_num_rows($rsValidaUnidade) == 0) {

        $oDados                                = new stdClass();
        $oDados->sCNES                         = $iCNES;
        $oDados->sNomeDepartamento             = $oEstabelecimento->getAttribute('NOME_FANTA');
        $this->aUnidadesNaoLocalizadas[$iCNES] = $oDados;
      } else {
        $this->aCNESVinculados[] = $iCNES;
      }
    }

    $this->oArquivoXML = $oArquivoXML;
    $this->oLog        = $oLog;

  }

  /**
   *
   * @return aUnidadesNaoLocalizadas[]
   */
  public function getUnidadesSemVinculo() {
    return $this->aUnidadesNaoLocalizadas;
  }

  /**
   * Retorna os CNES do arquivo e vinculados a unidades
   * @return array
   */
  public function getCNESVinculados() {
    return $this->aCNESVinculados;
  }

  public function processar() {

    $oDaoUnidades      = new cl_unidades();
    $aEstabelecimentos = $this->oArquivoXML->getElementsByTagName('DADOS_GERAIS_ESTABELECIMENTOS');
    foreach ($aEstabelecimentos as $oEstabelecimento) {

      $iCNES             = $oEstabelecimento->getAttribute('CNES');
      $sWhere            = " sd02_v_cnes = '$iCNES' ";
      $sSqlValidaUnidade = $oDaoUnidades->sql_query_file(null, "sd02_i_codigo", null, $sWhere);
      $rsValidaUnidade   = db_query($sSqlValidaUnidade);

      if ($rsValidaUnidade && pg_num_rows($rsValidaUnidade) == 0) {
        continue;
      }

      $iCodigoDepartamento = db_utils::fieldsMemory($rsValidaUnidade, 0)->sd02_i_codigo;
      $this->processarUnidade($oEstabelecimento, $iCodigoDepartamento, true);
    }
  }

  public function processarNovosVinculos($aNovosVinculos = array()) {

    $oDaoUnidades   = new cl_unidades();

    foreach ($aNovosVinculos as $oNovoVinculo) {

      $sSqlValidaUnidade = $oDaoUnidades->sql_query_file($oNovoVinculo->iDepartamento, "1");
      $rsValidaUnidade   = db_query($sSqlValidaUnidade);
      $lIsUPS            = false;
      if ($rsValidaUnidade && pg_num_rows($rsValidaUnidade) > 0) {
        $lIsUPS = true;
      }

      $aEstabelecimentos = $this->oArquivoXML->getElementsByTagName('DADOS_GERAIS_ESTABELECIMENTOS');
      foreach ($aEstabelecimentos as $oEstabelecimento) {

        $iCNES = $oEstabelecimento->getAttribute('CNES');
        if ( $oNovoVinculo->iCnes == $iCNES ) {

          $this->processarUnidade($oEstabelecimento, $oNovoVinculo->iDepartamento, $lIsUPS);
          continue 2;
        }
      }
    }
  }

  protected function processarUnidade($oEstabelecimentoXML, $iCodigoDepartamento, $lIsUPS = false) {

    if ( $this->validaDadosUnidade($oEstabelecimentoXML) ) {
      return;
    }

    $oDepartamento = DBDepartamentoRepository::getDBDepartamentoByCodigo($iCodigoDepartamento);
    $oUPS          = new UnidadeProntoSocorro();
    $oUPS->setDistrito(1); // valor default para inclusão de unidades
    if ($lIsUPS) {
      $oUPS = UnidadeProntoSocorroRepository::getUnidadeProntoSocorroByCodigo($iCodigoDepartamento);
    }

    $sCNPJCPF     = $oEstabelecimentoXML->getAttribute('CNPJ_MANT');
    $oResponsavel = CgmFactory::getInstanceByCnpjCpf($sCNPJCPF);


    /**
     * 1 - quando 3 no arquivo = a 2 no sistema
     * 2 - Só seta o CNPJ / CPF da mantenedora quando estabelecimento = a MANTIDO
     */
    $iSituacao = 1;
    $oUPS->setCNPJCPF("");
    if ($oEstabelecimentoXML->getAttribute('NIVEL_DEP') == 3) {

      $iSituacao = 2;
      $oUPS->setCNPJCPF($sCNPJCPF);
    }

    $oUPS->setSituacao($iSituacao);
    $oUPS->setResponsavel($oResponsavel);
    $oUPS->setSIASUS($oEstabelecimentoXML->getAttribute('COD_SIASUS'));
    $oUPS->setRegiao( (int) $oEstabelecimentoXML->getAttribute('REG_SAUDE') );
    $oUPS->setMicroRegiao($oEstabelecimentoXML->getAttribute('MICRO_REG'));
    $oUPS->setOrgaoExpedicaoAlvara($oEstabelecimentoXML->getAttribute('IND_ORGEXP'));
    $oUPS->setIBGE($oEstabelecimentoXML->getAttribute('CODMUNGEST'));
    $oUPS->setCNES($oEstabelecimentoXML->getAttribute('CNES'));
    $oUPS->setAlvara($oEstabelecimentoXML->getAttribute('NUM_ALVARA'));
    $oUPS->setCodigoTipoUnidade((int) $oEstabelecimentoXML->getAttribute('TP_UNID_ID'));
    $oUPS->setCodigoAtividade((int) $oEstabelecimentoXML->getAttribute('COD_ATIV'));
    $oUPS->setCodigoRetencaoTributos((int) $oEstabelecimentoXML->getAttribute('RETEN_TRIB'));
    $oUPS->setCodigoEsferaAdministrativa((int) $oEstabelecimentoXML->getAttribute('COD_ESFADM'));
    $oUPS->setCodigoNaturezaOrganizacao((int) $oEstabelecimentoXML->getAttribute('COD_NATORG'));
    $oUPS->setCodigoFluxoClientela((int) $oEstabelecimentoXML->getAttribute('COD_CLIENT'));
    $oUPS->setCodigoTurnoAtendimento((int) $oEstabelecimentoXML->getAttribute('COD_TURNAT'));
    $oUPS->setCodigoNivelHierarquia((int) $oEstabelecimentoXML->getAttribute('CODNIVHIER'));

    $sDtExpedicaoAlvara = $oEstabelecimentoXML->getAttribute('DATA_EXPED');
    if ( !empty($sDtExpedicaoAlvara) ) {
      $oUPS->setExpedicaoAlvara( new DBDate($sDtExpedicaoAlvara) );
    }

    $oUPS->salvar($oDepartamento);

    $aProfissionaisXML = $oEstabelecimentoXML->getElementsByTagName('PROFISSIONAIS');
    foreach ($aProfissionaisXML as $oProfissionalXML) {

      $aDadosProfissionalXML = $oProfissionalXML->getElementsByTagName('DADOS_PROFISSIONAIS');
      foreach ($aDadosProfissionalXML as $oDadosProfissionalXML) {
        $this->processarProfissionais($oDadosProfissionalXML, $oUPS);
      }
    }
  }

  /**
   * Consiste dos dados do arquivo com a base do e-cidade
   * @param  [type] $oEstabelecimentoXML [description]
   * @return boolean                     True se a unidade pode ser atualizada
   */
  public function validaDadosUnidade($oEstabelecimentoXML) {

    $oDadosLog        = new stdClass();
    $oDadosLog->iTipo = self::LOG_TIPO_ESTABELECIMENTO;
    $oDadosLog->sCNES = $oEstabelecimentoXML->getAttribute('CNES');
    $lInconsistencia  = false;

    $iTipoUnidade                = (int) $oEstabelecimentoXML->getAttribute('TP_UNID_ID');
    $iCodigoEsferaAdministrativa = (int) $oEstabelecimentoXML->getAttribute('COD_ESFADM');
    $iCodigoAtividade            = (int) $oEstabelecimentoXML->getAttribute('COD_ATIV');
    $iCodigoRetencaoTributos     = (int) $oEstabelecimentoXML->getAttribute('RETEN_TRIB');
    $iCodigoNaturezaOrganizacao  = (int) $oEstabelecimentoXML->getAttribute('COD_NATORG');
    $iCodigoFluxoClientela       = (int) $oEstabelecimentoXML->getAttribute('COD_CLIENT');
    $iCodigoNivelHierarquia      = (int) $oEstabelecimentoXML->getAttribute('CODNIVHIER');
    $iCodigoTurnoAtendimento     = (int) $oEstabelecimentoXML->getAttribute('COD_TURNAT');

    $sMsgValida   = "não encontrada na base de dados";
    $sCNPJCPF     = $oEstabelecimentoXML->getAttribute('CNPJ_MANT');

    if ( !$this->validarCnpfCpfCgm($sCNPJCPF) ) {

      $lInconsistencia      = true;
      $oDadosLog->sMensagem = urlencode("CNPJ/CPF [$sCNPJCPF] do estabelecimento responsável {$sMsgValida}.");
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }

    if ( !$this->validaTipoUnidade($iTipoUnidade) ) {

      $oDadosLog->sMensagem  = urlencode("Tipo de unidade [{$iTipoUnidade}] {$sMsgValida}.");
      $lInconsistencia       = true;
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }
    if ( !empty($iCodigoAtividade) && !$this->validaEsferaAdministrativa($iCodigoEsferaAdministrativa) ) {

      $oDadosLog->sMensagem  = urlencode("Esfera Administrativa [{$iCodigoEsferaAdministrativa}] {$sMsgValida}.");
      $lInconsistencia       = true;
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }

    if ( !empty($iCodigoAtividade) && !$this->validarAtividade($iCodigoAtividade) ) {

      $oDadosLog->sMensagem  = urlencode("Atividade de Ensino [{$iCodigoAtividade}] {$sMsgValida}.");
      $lInconsistencia       = true;
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }

    if ( !empty($iCodigoRetencaoTributos) && !$this->validaRetencaoTributos($iCodigoRetencaoTributos)) {

      $oDadosLog->sMensagem  = urlencode("Retenção de Tributos [{$iCodigoRetencaoTributos}] {$sMsgValida}.");
      $lInconsistencia       = true;
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }

    if ( !empty($iCodigoNaturezaOrganizacao) && !$this->validaNaturezaOrganizacao($iCodigoNaturezaOrganizacao)) {

      $oDadosLog->sMensagem  = urlencode("Natureza da Organização [{$iCodigoNaturezaOrganizacao}] {$sMsgValida}.");
      $lInconsistencia       = true;
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }

    if ( !empty($iCodigoFluxoClientela) && !$this->validaFluxoClientela($iCodigoFluxoClientela)) {

      $oDadosLog->sMensagem  = urlencode("Fluxo Clientela [{$iCodigoFluxoClientela}] {$sMsgValida}.");
      $lInconsistencia       = true;
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }

    if ( !empty($iCodigoNivelHierarquia) && !$this->validaNivelHierarquia($iCodigoNivelHierarquia)) {

      $oDadosLog->sMensagem  = urlencode("Nível de Hierarquia [{$iCodigoNivelHierarquia}] {$sMsgValida}.");
      $lInconsistencia       = true;
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }
    if ( !empty($iCodigoTurnoAtendimento) && !$this->validaTurnoAtendimento($iCodigoTurnoAtendimento)) {

      $oDadosLog->sMensagem  = urlencode("Turno de Atendimento [{$iCodigoTurnoAtendimento}] {$sMsgValida}.");
      $lInconsistencia       = true;
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }


    if ( empty($this->iCodigoEsferaAdministrativa) || empty($this->iCodigoAtividade) ||
         empty($this->iCodigoRetencaoTributos)     || empty($this->iCodigoNaturezaOrganizacao) ||
         empty($this->iCodigoFluxoClientela)       || empty($this->iCodigoNivelHierarquia) ||
         empty($this->iCodigoTurnoAtendimento) ) {

      $sMsg  = "Para ser importado os dados da \"Caracterização\" é necessário que todas as seguintes informações ";
      $sMsg .= "estejam informadas: ";
      $sMsg .= "Esfera Administrativa, Atividade de Ensino, Retenção de Tributos, Natureza da Organização ";
      $sMsg .= "Fluxo de Clientela, Nível de Hierarquia e Turno de Atendimento ";

      $oDadosLog->sMensagem  = urlencode($sMsg);
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_INFO);
    }

    // Se houve uma inconsistencia em alguma unidade
    if ($lInconsistencia) {
      $this->lInconsistencia = true;
    }
    return $lInconsistencia;
  }

  private function validarCnpfCpfCgm($sCNPJCPF) {

    $oResponsavel = CgmFactory::getInstanceByCnpjCpf($sCNPJCPF);
    if ( $oResponsavel instanceof CgmBase ) {
      return true;
    }
    return false;
  }

  private function validaTurnoAtendimento($iTurnoAtendimento) {

    if (count($this->aTurnoAtendimento) == 0) {

      $oDao = new cl_sau_turnoatend();
      $rs   = db_query($oDao->sql_query_file(null, "sd43_cod_turnat"));

      if (!$rs) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_buscar_turno_atendimento", $oMsgErro) );
      }
      $iLinhas = pg_num_rows($rs);
      for ($i = 0; $i < $iLinhas; $i++) {
        $this->aTurnoAtendimento[] = db_utils::fieldsMemory($rs, $i)->sd43_cod_turnat;
      }
    }

    if (in_array($iTurnoAtendimento, $this->aTurnoAtendimento)) {
      return true;
    }
    return false;

  }

  private function validaNivelHierarquia($iNivelHierarquia) {

    if (count($this->aNivelHierarquia) == 0) {

      $oDao = new cl_sau_nivelhier();
      $rs   = db_query($oDao->sql_query_file(null, "sd44_i_codnivhier"));

      if (!$rs) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_buscar_nivel_hierarquia", $oMsgErro) );
      }
      $iLinhas = pg_num_rows($rs);
      for ($i = 0; $i < $iLinhas; $i++) {
        $this->aNivelHierarquia[] = db_utils::fieldsMemory($rs, $i)->sd44_i_codnivhier;
      }
    }

    if (in_array($iNivelHierarquia, $this->aNivelHierarquia)) {
      return true;
    }
    return false;
  }

  private function validaFluxoClientela($iFluxoClientela) {

    if (count($this->aFluxoClientela) == 0) {

      $oDao = new cl_sau_fluxocliente();
      $rs   = db_query($oDao->sql_query_file(null, "sd41_i_cod_cliente"));

      if (!$rs) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_buscar_fluxo_cliente", $oMsgErro) );
      }
      $iLinhas = pg_num_rows($rs);
      for ($i = 0; $i < $iLinhas; $i++) {
        $this->aFluxoClientela[] = db_utils::fieldsMemory($rs, $i)->sd41_i_cod_cliente;
      }
    }

    if (in_array($iFluxoClientela, $this->aFluxoClientela)) {
      return true;
    }
    return false;
  }

  private function validaNaturezaOrganizacao($iNaturezaOrganizacao) {

    if (count($this->aNaturezaOrganizacao) == 0) {

      $oDao = new cl_sau_natorg();
      $rs   = db_query($oDao->sql_query_file(null, "sd40_i_cod_natorg"));

      if (!$rs) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_buscar_natureza_organizacao", $oMsgErro) );
      }
      $iLinhas = pg_num_rows($rs);
      for ($i = 0; $i < $iLinhas; $i++) {
        $this->aNaturezaOrganizacao[] = db_utils::fieldsMemory($rs, $i)->sd40_i_cod_natorg;
      }
    }

    if (in_array($iNaturezaOrganizacao, $this->aNaturezaOrganizacao)) {
      return true;
    }
    return false;
  }

  private function validaRetencaoTributos($iRetencaoTributos) {

    if (count($this->aRetencaoTributos) == 0) {

      $oDao = new cl_sau_retentributo();
      $rs   = db_query($oDao->sql_query_file(null, "sd39_i_cod_reten"));

      if (!$rs) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_buscar_retencao_tributos", $oMsgErro) );
      }
      $iLinhas = pg_num_rows($rs);
      for ($i = 0; $i < $iLinhas; $i++) {
        $this->aRetencaoTributos[] = db_utils::fieldsMemory($rs, $i)->sd39_i_cod_reten;
      }
    }

    if (in_array($iRetencaoTributos, $this->aRetencaoTributos)) {
      return true;
    }
    return false;

  }


  private function validarAtividade($iCodigoAtividade) {

    if (count($this->aAtividade) == 0 ) {

      $oDao        = new  cl_sau_atividadeensino();
      $rsAtividade = db_query($oDao->sql_query_file(null, "sd38_i_cod_ativid"));

      if (!$rsAtividade) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_buscar_atividade", $oMsgErro) );
      }
      $iLinhas = pg_num_rows($rsAtividade);
      for ($i = 0; $i < $iLinhas; $i++) {
        $this->aAtividade[] = db_utils::fieldsMemory($rsAtividade, $i)->sd38_i_cod_ativid;
      }
    }
    if (in_array($iCodigoAtividade, $this->aAtividade)) {
      return true;
    }
    return false;

  }


  private function validaEsferaAdministrativa($iCodigoEsferaAdministrativa='') {

    if (count($this->aEsferaAdministrativa) == 0 ) {

      $oDao     = new cl_sau_esferaadmin();
      $rsEsfera = db_query($oDao->sql_query_file(null, "sd37_i_cod_esfadm"));

      if (!$rsEsfera) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_buscar_esfera_administrativa", $oMsgErro) );
      }
      $iLinhas = pg_num_rows($rsEsfera);
      for ($i = 0; $i < $iLinhas; $i++) {
        $this->aEsferaAdministrativa[] = db_utils::fieldsMemory($rsEsfera, $i)->sd37_i_cod_esfadm;
      }
    }

    if (in_array($iCodigoEsferaAdministrativa, $this->aEsferaAdministrativa)) {
      return true;
    }
    return false;
  }

  private function validaTipoUnidade($iTipoUnidade) {

    if (count($this->aTiposUnidades) == 0 ) {

      $oDaoTipoUnidade = new cl_sau_tipounidade();
      $rsTipoUnidade   = db_query($oDaoTipoUnidade->sql_query_file(null, "sd42_i_tp_unid_id"));
      if ( !$rsTipoUnidade ) {

        $oMsgErro        = new stdClass();
        $oMsgErro->sErro = pg_last_error();
        throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_buscar_tipo_unidade", $oMsgErro) );
      }
      $iLinhas = pg_num_rows($rsTipoUnidade);
      for ($i = 0; $i < $iLinhas; $i++) {
        $this->aTiposUnidades[] = db_utils::fieldsMemory($rsTipoUnidade, $i)->sd42_i_tp_unid_id;
      }
    }

    if (in_array($iTipoUnidade, $this->aTiposUnidades)) {
      return true;
    }
    return false;
  }


  private function buscaMunicipioIBGE( $iIBGE ) {

    if ( !array_key_exists($iIBGE, $this->aMunicipioIBGE) ) {

      $sWhere  = "     db125_db_sistemaexterno = 4 ";
      $sWhere .= " and db125_codigosistema = '{$iIBGE}'";
      $sCampos = " db72_descricao as municipio, db71_sigla as uf";

      $oDao        = new cl_cadendermunicipiosistema();
      $rsMunicipio = db_query($oDao->sql_query(null, $sCampos, null, $sWhere));

      /**
       * Case não encontre na base do IBGE, retorna municipio e UF da instituição logada
       */
      if ( !$rsMunicipio || pg_num_rows($rsMunicipio) == 0) {

        $oDaoDBConfig = new cl_db_config();
        $sCampos      = " munic as municipio, uf ";
        $sSqlConfig   = $oDaoDBConfig->sql_query_file(db_getsession('DB_instit'), $sCampos);
        $rsConfig     = $oDaoDBConfig->sql_record($sSqlConfig);

        $this->aMunicipioIBGE[$iIBGE] = db_utils::fieldsMemory($rsConfig, 0);
      } else{
        $this->aMunicipioIBGE[$iIBGE] = db_utils::fieldsMemory($rsMunicipio, 0);
      }

    }
    return $this->aMunicipioIBGE[$iIBGE];
  }

  /**
   *
   * @param  DOMElement           $oDadosProfissionalXML
   * @param  UnidadeProntoSocorro $oUPS
   * @return boolean
   */
  protected function processarProfissionais($oDadosProfissionalXML, UnidadeProntoSocorro $oUPS) {

    $oCgm     = $this->salvarDadosCGM( $oDadosProfissionalXML );

    $oDaoMedico = new cl_medicos();
    $sSqlMedico = $oDaoMedico->sql_query_file(null, "sd03_i_codigo, sd03_i_cgm", null, " sd03_i_cgm = {$oCgm->getCodigo()} ");
    $rsMedico   = db_query($sSqlMedico);

    if ( !$rsMedico ) {

      $oMsgErro        = new stdClass();
      $oMsgErro->sErro = pg_last_error();
      throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_busca_medico") );
    }

    $iCodigoMedico = null;

    $oDadosLog            = new stdClass();
    $oDadosLog->iTipo     = self::LOG_TIPO_PROFISSIONAL;
    $oDadosLog->iCpf      = $oDadosProfissionalXML->getAttribute('CPF_PROF');
    $oDadosLog->sNome     = urlencode($oDadosProfissionalXML->getAttribute('NOME_PROF'));
    $oDadosLog->sMensagem = urlencode("Não foi possivel incluir o médico.");

    if ( pg_num_rows($rsMedico) == 0 ) {

      $oDaoMedico->sd03_i_codigo = null;
      $oDaoMedico->sd03_i_cgm    = $oCgm->getCodigo();
      $oDaoMedico->sd03_i_tipo   = 1;
      $oDaoMedico->incluir(null);

      $iCodigoMedico = $oDaoMedico->sd03_i_codigo;

      if ($oDaoMedico->erro_status == 0) {

        $oDadosLog->sMensagem .= " " . $oDaoMedico->erro_sql;
        $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
        $this->lInconsistencia = true;
      }

    } else {
      $iCodigoMedico = db_utils::fieldsMemory( $rsMedico, 0 )->sd03_i_codigo;
    }

    if ( empty($iCodigoMedico) ) {

      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
      $this->lInconsistencia = true;
    }

    if ( $oDadosProfissionalXML->getAttribute('DATA_NASC') == '' ) {

      $this->lInconsistencia = true;
      $oDadosLog->sMensagem  = urlencode("Profissional não possui data de nascimento informado no arquivo XML(CNES).");
      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
    }

    $aDadosVinculoXML = $oDadosProfissionalXML->getElementsByTagName('DADOS_VINC_PROF');
    foreach ( $aDadosVinculoXML as $oDadosVinculoXML) {

      $sCbo      = $oDadosVinculoXML->getAttribute('COD_CBO');
      $oDadosCbo = $this->buscaSequencialCbo($sCbo);
      if ( $oDadosCbo === false ) {

        $oDadosLog->iTipo     = self::LOG_TIPO_PROFISSIONAL_VINCULO;
        $oDadosLog->sMensagem = urlencode("CBO [$sCbo] inexistente na base de dados.");
        $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
        $this->lInconsistencia = true;
        return ;
      }

      $sWhere  = "     sd04_i_medico = {$iCodigoMedico} ";
      $sWhere .= " and sd04_i_unidade = " . $oUPS->getDepartamento()->getCodigo();

      $oDaoUnidadeMedico = new cl_unidademedicos();
      $sSqlUnidadeMedico = $oDaoUnidadeMedico->sql_query_file(null, " sd04_i_codigo ", null, $sWhere);
      $rsUnidadeMedico   = db_query($sSqlUnidadeMedico);

      if ( !$rsUnidadeMedico ) {

        $oMsgErro         = new stdClass();
        $oMsgErro->sErro  = pg_last_error();
        throw new Exception( _M(self::MSG_IMPORTACAOCNES . "erro_busca_vinculo_medico_unidade", $oMsgErro) );
      }

      $sHoraAmbulatorio = $oDadosVinculoXML->getAttribute('CG_HORAAMB');
      $sHoraHospitalar  = $oDadosVinculoXML->getAttribute('CGHORAHOSP');
      $sHoraOutras      = $oDadosVinculoXML->getAttribute('CGHORAOUTR');

      $oDaoUnidadeMedico->sd04_i_codigo     = null;
      $oDaoUnidadeMedico->sd04_i_unidade    = $oUPS->getDepartamento()->getCodigo();
      $oDaoUnidadeMedico->sd04_i_medico     = $iCodigoMedico;
      $oDaoUnidadeMedico->sd04_i_horaamb    = empty($sHoraAmbulatorio) ? '0' : "{$sHoraAmbulatorio}";
      $oDaoUnidadeMedico->sd04_i_horahosp   = empty($sHoraHospitalar)  ? '0' : "{$sHoraHospitalar}";
      $oDaoUnidadeMedico->sd04_i_horaoutros = empty($sHoraOutras)      ? '0' : "{$sHoraOutras}";
      $oDaoUnidadeMedico->sd04_c_sus        = $oDadosVinculoXML->getAttribute('VINCULO_SUS');
      $oDaoUnidadeMedico->sd04_c_situacao   = 'A';

      $iUnidadeMedico = null;
      if ( pg_num_rows($rsUnidadeMedico) == 0 ) {

        $oDaoUnidadeMedico->sd04_i_numerodias   = null;
        $oDaoUnidadeMedico->sd04_i_orgaoemissor = null;
        $oDaoUnidadeMedico->sd04_i_vinculo      = null;
        $oDaoUnidadeMedico->sd04_i_tipovinc     = null;
        $oDaoUnidadeMedico->sd04_i_subtipovinc  = null;
        $oDaoUnidadeMedico->sd04_d_folgaini     = null;
        $oDaoUnidadeMedico->sd04_d_folgafim     = null;

        $oDaoUnidadeMedico->incluir($iUnidadeMedico);
        $iUnidadeMedico = $oDaoUnidadeMedico->sd04_i_codigo;
      } else {

        $iUnidadeMedico                   = db_utils::fieldsMemory( $rsUnidadeMedico, 0 )->sd04_i_codigo;
        $oDaoUnidadeMedico->sd04_i_codigo = $iUnidadeMedico;
        $oDaoUnidadeMedico->alterar( $iUnidadeMedico );
      }

      if ( $oDaoUnidadeMedico->erro_status == 0 ) {


        $oDadosLog->iTipo      = self::LOG_TIPO_PROFISSIONAL_VINCULO;
        $oDadosLog->sMensagem  = urlencode("Não foi possível vincular o médico a unidade.");
        $oDadosLog->sMensagem .= " " . urlencode( $oDaoUnidadeMedico->erro_sql );
        $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
        $this->lInconsistencia = true;
        return ;

      }

      $sWhere  = "     sd27_i_rhcbo  = {$oDadosCbo->sequencial} ";
      $sWhere .= " and sd27_i_undmed = {$iUnidadeMedico} ";

      $oDaoEspecMedico  = new cl_especmedico();
      $sSqlEspecMedico  = $oDaoEspecMedico->sql_query_file(null, '1', null, $sWhere);
      $rsEspecMedico    = db_query( $sSqlEspecMedico );

      if ( $rsEspecMedico && pg_num_rows($rsEspecMedico) == 0 ) {

        $oDaoEspecMedico->sd27_i_rhcbo     = $oDadosCbo->sequencial;
        $oDaoEspecMedico->sd27_i_undmed    = $iUnidadeMedico;
        $oDaoEspecMedico->sd27_b_principal = 't';
        $oDaoEspecMedico->sd27_c_situacao  = 'A';

        $oDaoEspecMedico->incluir(null);

        if ($oDaoEspecMedico->erro_status == 0) {

          $oDadosLog->iTipo      = self::LOG_TIPO_PROFISSIONAL_VINCULO;
          $oDadosLog->sMensagem  = urlencode("Não foi possível incluir CBO para o médico.");
          $oDadosLog->sMensagem .= " " . urlencode( $oDaoEspecMedico->erro_sql );
          $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
          $this->lInconsistencia = true;
          return ;
        }
      }
    }

    return true;
  }

  private function buscaSequencialCbo( $sCbo ) {

    if ( !array_key_exists($sCbo, $this->aCbo) ) {

      $sWhere  = "     rh70_estrutural = '{$sCbo}' ";
      $sWhere .= " and rh70_tipo       = 4";
      $sCampos = " rh70_sequencial as sequencial, rh70_estrutural as cbo, rh70_descr as descricao";

      $oDao    = new cl_rhcbo();
      $rsCbo   = db_query($oDao->sql_query_file(null, $sCampos, null, $sWhere));

      if ( !$rsCbo || pg_num_rows($rsCbo) == 0) {
        return false;
      }

      $this->aCbo[$sCbo] = db_utils::fieldsMemory( $rsCbo, 0 );
    }

    return $this->aCbo[$sCbo];
  }

  private function salvarDadosCGM($oDadosProfissionalXML) {

    $oDadosMunicipio = $this->buscaMunicipioIBGE($oDadosProfissionalXML->getAttribute('COD_MUN'));

    $iCpf = $oDadosProfissionalXML->getAttribute('CPF_PROF');
    $oCgm = new CgmFisico();
    if ( $this->validarCnpfCpfCgm($oDadosProfissionalXML->getAttribute('CPF_PROF')) ) {
      $oCgm = CgmFactory::getInstanceByCnpjCpf($iCpf);
    }

    $sNomeCGM = $oDadosProfissionalXML->getAttribute('NOME_PROF');
    $oCgm->setNomeCompleto($sNomeCGM);
    $oCgm->setCpf($iCpf);
    $oCgm->setPIS($oDadosProfissionalXML->getAttribute('PISPASEP'));

    if ( strlen($sNomeCGM) > 40 ) {
      $sNomeCGM = DBString::abreviaSobrenome($sNomeCGM);
    }

    $oCgm->setNome( $sNomeCGM );
    $sNomeMae = $oDadosProfissionalXML->getAttribute('NOME_MAE');
    if ( strlen($sNomeMae) > 40 ) {
      $sNomeMae = DBString::abreviaSobrenome($sNomeMae);
    }
    $oCgm->setNomeMae( $sNomeMae );

    $sDataNascimento = '';

    if ( $oDadosProfissionalXML->getAttribute('DATA_NASC') != '' ) {

      $oDtNascimento   = new DBDate( $oDadosProfissionalXML->getAttribute('DATA_NASC') );
      $sDataNascimento = $oDtNascimento->getDate();
    }
    


    $oCgm->setDataNascimento( $sDataNascimento );
    $oCgm->setSexo($oDadosProfissionalXML->getAttribute('SEXO'));
    $oCgm->setIdentidade($oDadosProfissionalXML->getAttribute('NUM_IDENT'));

    $oCgm->setEscolaridade(0);
    if ( array_key_exists( (int)$oDadosProfissionalXML->getAttribute('CODESCOLAR'), $this->aEscolaridade ) ) {
      $oCgm->setEscolaridade( (int)$oDadosProfissionalXML->getAttribute('CODESCOLAR') );
    }
    $oCgm->setNacionalidade($oDadosProfissionalXML->getAttribute('IND_NACIO'));
    $oCgm->setEstadoCivil(1);
    if (array_key_exists( (int) $oDadosProfissionalXML->getAttribute('CD_SIT_FAM'), $this->aEstadoCivil)) {
      $oCgm->setEstadoCivil((int) $oDadosProfissionalXML->getAttribute('CD_SIT_FAM'));
    }

    $sNomePai = $oDadosProfissionalXML->getAttribute('NOME_PAI');
    if ( strlen($sNomePai) > 40 ) {
      $sNomePai = DBString::abreviaSobrenome($sNomePai);
    }
    $oCgm->setNomePai( $sNomePai );

    $oCgm->setCep( $oDadosProfissionalXML->getAttribute('COD_CEP') );
    $oCgm->setLogradouro( $oDadosProfissionalXML->getAttribute('LOGRADOURO') );

    $oCgm->setNumero( (int) $oDadosProfissionalXML->getAttribute('NUMERO') );
    $oCgm->setComplemento( $oDadosProfissionalXML->getAttribute('COMPLEMENT') );
    $oCgm->setBairro( $oDadosProfissionalXML->getAttribute('BAIRRODIST') );
    $oCgm->setUf( $oDadosMunicipio->uf );
    $oCgm->setMunicipio( $oDadosMunicipio->municipio );

    $sDataExpedicaoIdentidade = "";
    if ( $oDadosProfissionalXML->getAttribute('DTEMIIDENT') != '' ) {

      $oDtIdentidade            = new DBDate( $oDadosProfissionalXML->getAttribute('DTEMIIDENT') );
      $sDataExpedicaoIdentidade = $oDtIdentidade->getDate();
    }

    $oCgm->setIdentDataExp($sDataExpedicaoIdentidade);
    $oCgm->save();

    $oDaoCgmDoc = new cl_cgmdoc();
    $sWhereDocumento  = " z02_i_cgm = {$oCgm->getCodigo()} ";
    $sSqlDocumento    = $oDaoCgmDoc->sql_query_file(null, "z02_i_sequencial", null, $sWhereDocumento);
    $rsDocumento      = db_query( $sSqlDocumento );

    $oDaoCgmDoc->z02_c_certidaolivro    = $oDadosProfissionalXML->getAttribute('NUM_LIVRO');
    $oDaoCgmDoc->z02_c_folha            = $oDadosProfissionalXML->getAttribute('NUM_FOLHA');
    $oDaoCgmDoc->z02_c_termo            = $oDadosProfissionalXML->getAttribute('NUM_TERMO');
    $oDaoCgmDoc->z02_d_certidaodata     = '';
    if ( $oDadosProfissionalXML->getAttribute('DATA_EMISS') != '' ) {

      $oDtCertidao = new DBDate( $oDadosProfissionalXML->getAttribute('DATA_EMISS') );
      $oDaoCgmDoc->z02_d_certidaodata   = $oDtCertidao->getDate();
    }

    $oDaoCgmDoc->z02_c_identorgao       = $oDadosProfissionalXML->getAttribute('CODORGEMIS');
    $oDaoCgmDoc->z02_d_identdata        = $sDataExpedicaoIdentidade;
    $oDaoCgmDoc->z02_c_identuf          = $oDadosProfissionalXML->getAttribute('SIGLA_EST');
    $oDaoCgmDoc->z02_c_ctpsnum          = $oDadosProfissionalXML->getAttribute('CTPS_NUMER');
    $oDaoCgmDoc->z02_c_ctpsserie        = $oDadosProfissionalXML->getAttribute('SERIE');
    $oDaoCgmDoc->z02_c_ctpsuf           = $oDadosProfissionalXML->getAttribute('SIGESTCTPS');
    $oDaoCgmDoc->z02_d_ctpsdata         = '';
    if ( $oDadosProfissionalXML->getAttribute('DTEMISCTPS') != '') {

      $oDtEmissaoCTPS = new DBDate( $oDadosProfissionalXML->getAttribute('DTEMISCTPS') );
      $oDaoCgmDoc->z02_d_ctpsdata       = $oDtEmissaoCTPS->getDate();
    }
    $oDaoCgmDoc->z02_i_cgm        = $oCgm->getCodigo();
    $oDaoCgmDoc->z02_i_cns        = $oDadosProfissionalXML->getAttribute('COD_CNS');
    $oDaoCgmDoc->z02_i_sequencial = null;
    if ( $rsDocumento &&  pg_num_rows($rsDocumento) > 0 ) {

      $oDaoCgmDoc->z02_i_sequencial = db_utils::fieldsMemory( $rsDocumento, 0 )->z02_i_sequencial;
      $oDaoCgmDoc->alterar( $oDaoCgmDoc->z02_i_sequencial );
    } else {
      $oDaoCgmDoc->incluir(null);
    }

    if ( $oDaoCgmDoc->erro_status == 0 ) {

      $oDadosLog             = new stdClass();
      $oDadosLog->iTipo      = self::LOG_TIPO_PROFISSIONAL;
      $oDadosLog->iCpf       = $iCpf;
      $oDadosLog->sMensagem  = urlencode("Não foi possivel incluir o documento.");
      $oDadosLog->sMensagem .= " ". $oDaoCgmDoc->erro_sql;

      $this->oLog->escreverLog($oDadosLog, DBLog::LOG_ERROR);
      $this->lInconsistencia = true;
    }

    return $oCgm;
  }

  public function temInconsistencia() {
    return $this->lInconsistencia;
  }
}