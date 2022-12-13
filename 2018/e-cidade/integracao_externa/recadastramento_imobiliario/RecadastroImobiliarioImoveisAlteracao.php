<?php
require_once(PATH_IMPORTACAO . "RecadastroImobiliarioImoveis.interface.php");
require_once(PATH_IMPORTACAO . "libs/caracteristicas_imovel.php");

/**
 * Classe para processamento da Alteração de Imoveis no Recadastro imobiliario
 *
 * @uses     RecadastroImobiliarioImoveisInterface
 * @package  Recadastro Imobiliario
 * @author   Rafael Serpa Nery <rafael.nery@dbseller.com.br> 
 * @revision $Author: dbalberto $
 * @version  $Revision: 1.11 $
 */
class RecadastroImobiliarioImoveisAlteracao  implements RecadastroImobiliarioImoveisInterface {

  /**
   * Matricula do Imovel
   *
   * @var integer
   * @access public
   */
  private $iMatricula;

  /**
   * iCodigoConstrucao 
   * 
   * @var mixed
   * @access private
   */
  private $iCodigoConstrucao;

  /**
   * aTabelas 
   * 
   * @var array
   * @access private
   */
  private $aTabelas = array();

  /**
   * Dados da Linha do Arquivo
   * 
   * @var stdClass
   * @access public
   */
  public $oRegistro;

  /**
   * sLog 
   * 
   * @var mixed
   * @access public
   */
  public $sLog;
  
  /**
   * Dados Atuais do Imovel 
   *
   * @var mixed
   * @access public
   */
  public $oDadosAtuais;


  public $lUnidadeSecundaria = false;
  /**
   * Construtor da Classe 
   * 
   * @param mixed $oRegistro 
   * @access public
   * @return void
   */
  public function __construct($oRegistro) {

    require_once(PATH_IMPORTACAO . "libs/RecadastramentoImobiliarioSQLUtils.php");
    
    if (!is_object($oRegistro)) {
      throw new Exception('Registro inválido para exclusão.');
    }
    $this->oRegistro        = $oRegistro;
    $this->iMatricula       = (int)$oRegistro->iMatricula;
    $this->dDataInclusao    = $oRegistro->oDataEnvio->getDate();

    /**
     * Tabelas Manipuladas pelo dataManager
     */
    $this->aTabelas['carlote']    = new tableDataManager(Conexao::getInstancia()->getConexao(),"cadastro.carlote"   , ""          , true, 1);
    $this->aTabelas['loteam']     = new tableDataManager(Conexao::getInstancia()->getConexao(),"cadastro.loteam"    , "j34_loteam", true, 1,"loteam_j34_loteam_seq");
    $this->aTabelas['setorloc']   = new tableDataManager(Conexao::getInstancia()->getConexao(),"cadastro.setorloc"  , "j05_codigo", true, 1);

    $this->aTabelas['matricobs']  = new tableDataManager(Conexao::getInstancia()->getConexao(),"cadastro.matricobs" , ""          , true, 1);
    $this->aTabelas['iptuconstr'] = new tableDataManager(Conexao::getInstancia()->getConexao(),"cadastro.iptuconstr", ""          , true, 1);
    $this->aTabelas['carconstr']  = new tableDataManager(Conexao::getInstancia()->getConexao(),"cadastro.carconstr" , ""          , true, 1);
    $this->aTabelas['iptuant']    = new tableDataManager(Conexao::getInstancia()->getConexao(),"cadastro.iptuant"   , ""          , true, 1);


    if ( !in_array( $this->oRegistro->sUnidadeImobiliariaAnterior, array('000','001') ) ) {
      $this->lUnidadeSecundaria = true;
    }
  }

  /**
   * Executa Processamento do Registro 
   * 
   * @access public
   * @return boolean
   */
  public function processar() {

    $oConexao = Conexao::getInstancia();
    
    $this->logBanco(" |------Iniciando Processamento Matricula: {$this->iMatricula} ", DBLog::LOG_INFO);
    /**
     * Primeiramente validar se a matricula informada existe
     */
    if ( !RecadastramentoSQLUtils::getDadosIPTUBase($this->iMatricula, 1) ) {
      $this->logBanco("  - Nao Foi encontrada Matricula com o Codigo Informado. Registro Nao Vai ser Processado.", DBLog::LOG_INFO);
      return false;
    }

    if ( !$this->setConstrucaoProcessamento() ) {
      return false;
    }

    $iCaracteristicaPropriedadeAnterior         = getCaracteristicasLote('propriedade',             $this->oRegistro->iCaracteristicaPropriedadeAnterior         );
    $iCaracteristicaSituacaoAnterior            = getCaracteristicasLote('situacao',                $this->oRegistro->iCaracteristicaSituacaoAnterior            );
    $iCaracteristicaAnterior                    = getCaracteristicasLote('caracteristica',          $this->oRegistro->iCaracteristicaAnterior                    );
    $iCaracteristicaNivelAnterior               = getCaracteristicasLote('nivel',                   $this->oRegistro->iCaracteristicaNivelAnterior               );
    $iCaracteristicaNumeroFrentesAnterior       = getCaracteristicasLote('frentes',                 $this->oRegistro->iCaracteristicaNumeroFrentesAnterior       );
    $iCaracteristicaOcupacaoAnterior            = getCaracteristicasLote('ocupacao',                $this->oRegistro->iCaracteristicaOcupacaoAnterior            );
    $iCaracteristicaUtilizacaoAnterior          = getCaracteristicasConstrucao('utilizacao',        $this->oRegistro->iCaracteristicaUtilizacaoAnterior          );
    $iCaracteristicaNumeroPavimentosAnterior    = getCaracteristicasConstrucao('pavimento',         $this->oRegistro->iNumeroPavimentosAnterior                  );
    $iCaracteristicaLocalizacaoUnidadeAnterior  = getCaracteristicasConstrucao('localizacao',       $this->oRegistro->iCaracteristicaLocalizacaoUnidadeAnterior  );
    $iCaracteristicaTipoAnterior                = getCaracteristicasConstrucao('tipo',              $this->oRegistro->iCaracteristicaTipoAnterior                );
    $iCaracteristicaPadraoConstrutivoAnterior   = getCaracteristicasConstrucao('padraoconstrutivo', $this->oRegistro->iCaracteristicaPadraoConstrutivoAnterior   );
    $iCaracteristicaConservacaoAnterior         = getCaracteristicasConstrucao('conservacao',       $this->oRegistro->iCaracteristicaConservacaoAnterior         );
    $iCaracteristicaUsoAnterior                 = getCaracteristicasConstrucao('uso',               $this->oRegistro->iCaracteristicaUsoAnterior                 );
    $iCaracteristicaEstruturaAnterior           = getCaracteristicasConstrucao('estrutura',         $this->oRegistro->iCaracteristicaEstruturaAnterior           );
    $iCaracteristicaAguaAnterior                = getCaracteristicasConstrucao('agua',              $this->oRegistro->iCaracteristicaAguaAnterior                );
    $iCaracteristicaEsgotoAnterior              = getCaracteristicasConstrucao('esgoto',            $this->oRegistro->iCaracteristicaEsgotoAnterior              );
    $iCaracteristicaEnergiaEletricaAnterior     = getCaracteristicasConstrucao('eletrica',          $this->oRegistro->iCaracteristicaEnergiaEletricaAnterior     );
    $iCaracteristicaInstalacaoSanitariaAnterior = getCaracteristicasConstrucao('sanitaria',         $this->oRegistro->iCaracteristicaInstalacaoSanitariaAnterior );
    $iCaracteristicaCoberturaAnterior           = getCaracteristicasConstrucao('cobertura',         $this->oRegistro->iCaracteristicaCoberturaAnterior           );
    $iCaracteristicaEsquadriaAnterior           = getCaracteristicasConstrucao('esquadria',         $this->oRegistro->iCaracteristicaEsquadriaAnterior           );
    $iCaracteristicaPisoAnterior                = getCaracteristicasConstrucao('piso',              $this->oRegistro->iCaracteristicaPisoAnterior                );
    $iCaracteristicaRevestimentoExternoAnterior = getCaracteristicasConstrucao('revestimento',      $this->oRegistro->iCaracteristicaRevestimentoExternoAnterior );

    /**
     * Após definir a contrução encontrada anteriormente, 
     * compara os dados encontrado no e-Cidade com os Dados Vindos do Arquivo
     */
    $oDadosImovel = RecadastramentoSQLUtils::getDadosAtuaisImovel($this->iMatricula, $this->iCodigoConstrucao);
    
    if ( !$oDadosImovel ) {
       
      $this->logBanco(
        " Nao foram encontrados os Dados do Imovel. S/Q/L : ".
        $this->oRegistro->sSetorCartograficoAnterior  ."/"   .
        $this->oRegistro->sQuadraCartograficaAnterior ."/"   .
        $this->oRegistro->sLoteCartograficoAnterior, 
        DBLog::LOG_ERROR);       
      return false;      
       
    }

    $sSqlLogradouro = "Select 1 from ruas where j14_codigo = {$this->oRegistro->iCodigoLogradouroNovo}"; 
    $rsRuas         = pg_query($sSqlLogradouro); 
    $oComparacoes                                                     = new stdClass();
    $oComparacoes->{"Logradouro Inexistente"}                         = pg_num_rows($rsRuas) > 0;
    $oComparacoes->codigo_logradouro_lote                             = $oDadosImovel->codigo_logradouro_lote                          == $this->oRegistro->iCodigoLogradouroAnterior;
    
    if (!$this->lUnidadeSecundaria) {
      $oComparacoes->area_terreno                                       = $oDadosImovel->area_terreno                                    == $this->oRegistro->nAreaTerrenoAnterior;
      $oComparacoes->caracteristica_lote_situacao                       = $oDadosImovel->caracteristica_lote_situacao                    == $iCaracteristicaSituacaoAnterior;
      $oComparacoes->caracteristica_lote_caracteristica                 = $oDadosImovel->caracteristica_lote_caracteristica              == $iCaracteristicaAnterior;
      $oComparacoes->caracteristica_lote_nivel                          = $oDadosImovel->caracteristica_lote_nivel                       == $iCaracteristicaNivelAnterior;
      $oComparacoes->caracteristica_lote_numero_frentes                 = $oDadosImovel->caracteristica_lote_numero_frentes              == $iCaracteristicaNumeroFrentesAnterior;
      $oComparacoes->caracteristica_lote_ocupacao                       = $oDadosImovel->caracteristica_lote_ocupacao                    == $iCaracteristicaOcupacaoAnterior;
    }

    $oComparacoes->caracteristica_construcao_tipo                     = $oDadosImovel->caracteristica_construcao_tipo                  == $iCaracteristicaTipoAnterior;
    $oComparacoes->caracteristica_construcao_estrutura                = $oDadosImovel->caracteristica_construcao_estrutura             == $iCaracteristicaEstruturaAnterior;
    $oComparacoes->caracteristica_construcao_energia_eletrica         = $oDadosImovel->caracteristica_construcao_energia_eletrica      == $iCaracteristicaEnergiaEletricaAnterior;
    $oComparacoes->caracteristica_construcao_instalacao_sanitaria     = $oDadosImovel->caracteristica_construcao_instalacao_sanitaria  == $iCaracteristicaInstalacaoSanitariaAnterior;
    $oComparacoes->caracteristica_construcao_cobertura                = $oDadosImovel->caracteristica_construcao_cobertura             == $iCaracteristicaCoberturaAnterior;
    $oComparacoes->caracteristica_construcao_esquadria                = $oDadosImovel->caracteristica_construcao_esquadria             == $iCaracteristicaEsquadriaAnterior;
    $oComparacoes->caracteristica_construcao_piso                     = $oDadosImovel->caracteristica_construcao_piso                  == $iCaracteristicaPisoAnterior;
    $oComparacoes->caracteristica_construcao_revestimento_externo     = $oDadosImovel->caracteristica_construcao_revestimento_externo  == $iCaracteristicaRevestimentoExternoAnterior;
   
    $oComparacoes->areaconstruida                                     = (float)$oDadosImovel->areaconstruida                           == (float)str_ireplace(",",".", $this->oRegistro->nAreaConstruidaAnterior);
   
    /**
     * Após validar cada item os percorre encontrando inconsistencias
     */
    $lContinuaProcessamento = true;
    $this->oDadosAtuais     = $oDadosImovel;
    foreach ( $oComparacoes as $sComparacao => $lValidou ) {
     
      if ( !$lValidou ) {
        $lContinuaProcessamento = false;
        $this->logBanco("  - Encontrou Direfença na Comparação: $sComparacao.", DBLog::LOG_ERROR);
      }
    }
    
    /**
     * Continua apenas se não houver diferenças nos dados
     */
    if ( !$lContinuaProcessamento ) {
      
      $this->logBanco("  - Os dados Anteriores Informados não conferem com os Dados Atuais no e-Cidade.", DBLog::LOG_ERROR);
      $this->logBanco("", DBLog::LOG_ERROR);
      return false;
    }
    $this->logBanco("  - Os dados Anteriores conferem com os Dados Atuais no e-Cidade, seguindo processamento.");
    $this->logBanco("   + Processando Tabelas Referentes ao Lote e seuas Caracteristicas.");
    $this->processarAlteracaoLote();

    $this->logBanco("   + Processando Tabela  Referente  ao Imóvel.");
    $this->processarAlteracaoImovel();
    
    /**
     * Caso exista construcao nova
     */
    $lProcessa = true;
    if ( empty($this->iCodigoConstrucao) && !empty($this->oRegistro->nAreaConstruidaNova) ) {
     
      $lIncluiNovoRegistro = true;//'inclusao';
      $this->logBanco("   + Processando Inclusao  nas Tabelas Referentes as Construções.(iptuconstr, carconstr)");
    } elseif (  empty($this->iCodigoConstrucao) &&  empty($this->oRegistro->nAreaConstruidaNova) ) {
      
      $lProcessa = false;
      $this->logBanco("   + Sem modificações na Construção");
    } elseif ( !empty($this->iCodigoConstrucao) && !empty($this->oRegistro->nAreaConstruidaNova) ) {
     
      $lIncluiNovoRegistro = false;
      $this->logBanco("   + Processando Alteração nas Tabelas Referentes as Construções.(iptuconstr, carconstr)");
    } else {
      
      $this->logBanco("   Ocorrencia nao Caracteriza alteracao, processamento cancelado.", DBLog::LOG_ERROR);
      return false;
    }
     
    if ( $lProcessa ) {
      $this->processarAlteracaoConstrucao( !$lIncluiNovoRegistro ); 
    }
    
    /**
     * Após termino do processamento grava as alterações encontradas e define o registro como processado
     */
    $oDadosProcessamento      = (object)array("ie28_observacoes"=> $this->sLog, "ie28_processado"=>'t');
    $sWhereProcessamento      = "ie28_sequencial = {$this->oRegistro->iCodigoRegistro}";
    $rsAlteracaoProcessamento = RecadastramentoSQLUtils::alterar("recadastroimobiliarioimoveis", $oDadosProcessamento, $sWhereProcessamento);
    
    if ( !$rsAlteracaoProcessamento ) {
      
      $sMensagem = "Erro ao Gravar Registro da Alteração. Detalhe:".Conexao::getInstancia()->getLastError();
      $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
      throw new Exception($sMensagem);
    }
    
    /**
     * Alterarando Codigo REf Anterior
     */
    $rsRemocaoCaracteristicas = RecadastramentoSQLUtils::excluir("iptuant", null, " j40_matric = {$this->iMatricula} ");
    $oDadosRefAnt             = (object)array("j40_matric"=>$this->iMatricula,"j40_refant"=> $this->getCodigoAnteriorConstrucao(false,false));
    $this->aTabelas['iptuant']->setByLineOfDBUtils( $oDadosRefAnt, true );
    return true;
  }

  /**
   * Gera Ocorrencia Para a Matricula
   * @return boolean
   */
  public function registrarOcorrencia() {

    $oConfiguracoes         = (object)parse_ini_file(PATH_IMPORTACAO . "libs/configuracoes_importacao.ini",true);
    $iInstituicao           = $aConfiguracoes->sistema['instituicao_prefeitura'];
    $this->logBanco( "Incluindo histórico para a matrícula {$this->iMatricula}.");

    $sMensagemOcorrencia    = "Imóvel ReIncluido pelo recadastramento. Nome do arquivo: {$this->oRegistro->sNomeArquivo}";
    $sInsertHistocorrencia  = "insert into histocorrencia                                    ";
    $sInsertHistocorrencia .= "       (ar23_sequencial  ,                                    ";
    $sInsertHistocorrencia .= "        ar23_id_usuario  ,                                    ";
    $sInsertHistocorrencia .= "        ar23_instit      ,                                    ";
    $sInsertHistocorrencia .= "        ar23_modulo      ,                                    ";
    $sInsertHistocorrencia .= "        ar23_id_itensmenu,                                    ";
    $sInsertHistocorrencia .= "        ar23_data        ,                                    ";
    $sInsertHistocorrencia .= "        ar23_hora        ,                                    ";
    $sInsertHistocorrencia .= "        ar23_tipo        ,                                    ";
    $sInsertHistocorrencia .= "        ar23_descricao   ,                                    ";
    $sInsertHistocorrencia .= "        ar23_ocorrencia)                                      ";
    $sInsertHistocorrencia .= " values (nextval('histocorrencia_ar23_sequencial_seq'),       ";
    $sInsertHistocorrencia .= "         1,                                                   ";
    $sInsertHistocorrencia .= "         {$iInstituicao},                                     ";
    $sInsertHistocorrencia .= "         578,                                                 ";
    $sInsertHistocorrencia .= "         1722,                                                ";
    $sInsertHistocorrencia .= "         '{$this->oRegistro->oDataEnvio->getDate()}',         ";
    $sInsertHistocorrencia .= "         '00:00',                                             ";
    $sInsertHistocorrencia .= "         2,                                                   ";
    $sInsertHistocorrencia .= "         '$sMensagemOcorrencia.',                             ";
    $sInsertHistocorrencia .= "         '$sMensagemOcorrencia.')                             ";

    if (pg_query ($sInsertHistocorrencia)) {

      $sInsertHistocorrenciaMatric  = "insert into histocorrenciamatric                            ";
      $sInsertHistocorrenciaMatric .= "       (ar25_sequencial   ,                                 ";
      $sInsertHistocorrenciaMatric .= "        ar25_matric       ,                                 ";
      $sInsertHistocorrenciaMatric .= "        ar25_histocorrencia)                                ";
      $sInsertHistocorrenciaMatric .= "values (nextval('histocorrenciamatric_ar25_sequencial_seq'),";
      $sInsertHistocorrenciaMatric .= "        {$this->iMatricula},                                ";
      $sInsertHistocorrenciaMatric .= "        currval('histocorrencia_ar23_sequencial_seq'))      ";

      if (pg_query($sInsertHistocorrenciaMatric)) {
        return true;
      }
    }

    $this->logBanco( "Incluindo histórico de ocorrência para a matrícula {$this->iMatricula}." );

    return false;

  }

  /**
   * Tenta Encontrar Construcao através da matricula e geração do codigo da Matricula.
   *
   * @static
   * @access public
   * @return void
   */
  private function getCodigoAnteriorConstrucao( $lComparacao = true, $lDadosAntigos = true ) {

    
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
      
      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sDistritoNovo)        , 1,"0", STR_PAD_LEFT );
      $sCodigoReferenciaAnterior .= "2";
    }
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sSetorCartograficoNovo ), 4,"0", STR_PAD_LEFT );
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sQuadraCartograficaNovo), 4,"0", STR_PAD_LEFT );
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sLoteCartograficoNovo  ), 4,"0", STR_PAD_LEFT );
    $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sUnidadeImobiliariaNova), 3,"0", STR_PAD_LEFT );
    
    if ( $lDadosAntigos ) {
      
    $sCodigoReferenciaAnterior = "";
      if ( !$lComparacao ) {
       
        $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sDistritoNovo)             , 1,"0", STR_PAD_LEFT );
        $sCodigoReferenciaAnterior .= "2";
      }
      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sSetorCartograficoAnterior ) , 4,"0", STR_PAD_LEFT );
      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sQuadraCartograficaAnterior ), 4,"0", STR_PAD_LEFT );
      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sLoteCartograficoAnterior )  , 4,"0", STR_PAD_LEFT );
      $sCodigoReferenciaAnterior .= str_pad( trim($this->oRegistro->sUnidadeImobiliariaAnterior) , 3,"0", STR_PAD_LEFT );
    }
    return $sCodigoReferenciaAnterior;
  }

  /**
   * getCodigoConstrucaoByReferenciaAnterior
   *
   * @param  string $sReferenciaAnterior
   * @access private
   * @return void
   */
  private function getCodigoConstrucaoByReferenciaAnterior( $sReferenciaAnterior ) {

    $sSql     = "select j39_matric, j39_idcons from iptuconstr where j39_obs ~ '{$sReferenciaAnterior}'";
    $rsCodigo = pg_query($sSql);

    if (!$rsCodigo) {
      throw new Exception("Erro ao buscar os dados da Construcao");
    }

    /**
     * Caso a Quantidade de Construções encotradas referenciar a mais de uma construcao ou a nenhuma 
     * Nao retorna o Codigo.
     */
    if( pg_num_rows($rsCodigo) <> 1 ) {
      return false;
    }

    $oResultado = db_utils::fieldsMemory($rsCodigo, 0);

    /**
     * Caso o ID encontrado seja de Outra matricula não retorna o Codigo
     */
    if ( (int)$this->iMatricula <> (int)$oResultado->j39_matric ) {
      return false;
    }
    return $oResultado->j39_idcons;
  }

  /**
   * setConstrucaoProcessamento 
   * 
   * @access private
   * @return void
   */
  private function setConstrucaoProcessamento() {
    /**
     * Valida se a Matricula informada tem construções
     * Quando nao Houver construções o registro  não será processado
     */
    $sQueryValidacao = "  select j01_matric                                                 as matricula,    ";
    $sQueryValidacao.= "         count( distinct j39_idcons)                                as construcoes,  ";
    $sQueryValidacao.= "         array_to_string( array_accum( distinct j39_idcons ), ', ') as id_construcao ";
    $sQueryValidacao.= "    from iptubase                                                                    ";
    $sQueryValidacao.= "         left  join iptuconstr on j39_matric = j01_matric                            ";
    $sQueryValidacao.= "         left  join carconstr  on j48_matric = j39_matric                            ";
    $sQueryValidacao.= "                              and j48_idcons = j39_idcons                            ";
    $sQueryValidacao.= "   where j01_matric = {$this->iMatricula}                                            ";
    $sQueryValidacao.= "group by j01_matric                                                                  ";
    $oDados          = db_utils::fieldsMemory( pg_query( $sQueryValidacao ), 0);


    if ( $oDados->construcoes == 0 ) {
      $this->logBanco("  - Atualmente sem Construcoes.");
    } elseif ($oDados->construcoes == 1) {

      $this->iCodigoConstrucao = $oDados->id_construcao;
      $this->logBanco("  - ID da Construcao: $this->iCodigoConstrucao.");
    } else {

      $iCodigo = $this->getCodigoConstrucaoByReferenciaAnterior($this->getCodigoAnteriorConstrucao());

      if ( !$iCodigo ) {

        $this->logBanco("  * Nao Foi possivel definir o ID da Construção da Matricula.");
        return false;
      }

      $this->logBanco("  - ID da Construcao da Matricula: " .$iCodigo);
      $this->iCodigoConstrucao = $iCodigo;
    }

    return true;
  }

  /**
   * Processa a alteração dos dados do Lote
   * @return boolean
   */
  private function processarAlteracaoLote() {

    
    $lProcessaDemolicao = false;
    /**
     * Tabela : Lote
     */
    $iIDBQL                           = RecadastramentoSQLUtils::getDadosIPTUBase($this->iMatricula,"j01_idbql")->j01_idbql;
    $this->logBanco("    - IDBQL da Matricula: $iIDBQL");
    $oCampos                          = new stdClass();
    $oCampos->j34_setor               = $this->oRegistro->sSetorCartograficoNovo;
    $oCampos->j34_quadra              = $this->oRegistro->sQuadraCartograficaNovo;
    $oCampos->j34_lote                = $this->oRegistro->sLoteCartograficoNovo;
    if ( !$this->lUnidadeSecundaria ) {
      $oCampos->j34_area                = $this->oRegistro->nAreaTerrenoNova;
    }
    $oCampos->j34_bairro              = $this->oRegistro->sBairroNovo;
    $rsAlteracaoLote                  = RecadastramentoSQLUtils::alterar("lote", $oCampos, "j34_idbql = {$iIDBQL}");

    if ( !$rsAlteracaoLote ) {

      $sMensagem = "Erro ao Alterar os Dadso da Tabela lote. Detalhe:".Conexao::getInstancia()->getLastError();
      $this->logBanco($sMensagem,DBLog::LOG_ERROR);
      throw new Exception($sMensagem);
    }
    /**
     * Tabela : Carlote
     * Não exclui carateristicas da zona fiscal (Grupo 44)
     */
    $sWhereExclusao                   = "    carlote.j35_idbql  = {$iIDBQL}                    ";
    $sWhereExclusao                  .= "and carlote.j35_caract not in (select j31_codigo      ";
    $sWhereExclusao                  .= "                                 from caracter        ";
    $sWhereExclusao                  .= "                                where j31_grupo = 44) ";
    
    $rsRemocaoCaracteristicas         = RecadastramentoSQLUtils::excluir("carlote", "", $sWhereExclusao);

    if ( !$rsRemocaoCaracteristicas ) {

      $sMensagem = "Erro ao Remover as Caracteristicas do Lote. Detalhe:".Conexao::getInstancia()->getLastError();
      $this->logBanco($sMensagem,DBLog::LOG_ERROR);
      throw new Exception($sMensagem);
    }

    if ( !$this->lUnidadeSecundaria ) {
   
      $oCaracteristicaLote->PropriedadeNovo   = getCaracteristicasLote('propriedade',   $this->oRegistro->iCaracteristicaPropriedadeNova   );
      $oCaracteristicaLote->SituacaoNovo      = getCaracteristicasLote('situacao',      $this->oRegistro->iCaracteristicaSituacaoNovo      );
      $oCaracteristicaLote->Novo              = getCaracteristicasLote('caracteristica',$this->oRegistro->iCaracteristicaNovo              );
      $oCaracteristicaLote->NivelNovo         = getCaracteristicasLote('nivel',         $this->oRegistro->iCaracteristicaNivelNovo         );
      $oCaracteristicaLote->NumeroFrentesNovo = getCaracteristicasLote('frentes',       $this->oRegistro->iCaracteristicaNumeroFrentesNovo );
      $oCaracteristicaLote->OcupacaoNovo      = getCaracteristicasLote('ocupacao',      $this->oRegistro->iCaracteristicaOcupacaoNovo      );
     
      foreach ( $oCaracteristicaLote as $sGrupo => $iCodigoCaracateristicaLote) {

        /**
         * Caso Caracteristica seja 60 (VAGO) do Grupo 24(OCUPACAO)
         * Executa demolição para TODAS as Construções do Lote
         */
        if ( $iCodigoCaracateristicaLote == 60 ) {
          $lProcessaDemolicao = true;
        }
        
        /**
         * Caso não seja especificada nenhuma caracteristica para o Grupo passa para o Proximo
         * Item
         */
        if ($iCodigoCaracateristicaLote == 0) {

          $this->logBanco("    - Caracteristica Nao Definida: {$oDadosCarlote->j35_caract}, Grupo: $sGrupo");
          continue;
        }

        $oDadosCarlote = (object)array("j35_idbql"  => $iIDBQL,
                                       "j35_caract" => $iCodigoCaracateristicaLote,
                                       "j35_dtlanc" => '');
        $this->aTabelas['carlote']->setByLineOfDBUtils($oDadosCarlote, true);
        $this->logBanco("    - Definindo Caracteristica: {$oDadosCarlote->j35_caract}, Grupo: $sGrupo");
      }
    }
    /**
     * Definições de face e testadas
     */
    $sFaceQuadra       = "select j37_face                                                   \n";
    $sFaceQuadra      .= "  from face                                                       \n";
    $sFaceQuadra      .= " where j37_setor  = '{$this->oRegistro->sSetorCartograficoNovo}'  \n";
    $sFaceQuadra      .= "   and j37_quadra = '{$this->oRegistro->sQuadraCartograficaNovo}' \n";
    $sFaceQuadra      .= "   and j37_codigo = '{$this->oRegistro->iCodigoLogradouroNovo}'   \n";
    $rsFaceQuadra      = pg_query($sFaceQuadra);

    /**
     * Caso houver erro de query
     */
    if ( !$rsFaceQuadra ) {

      $sMensagem = "Erro ao Buscar os Dados da Face de Quadra. ";
      $this->logBanco("     $sMensagem" );
      $this->logBanco("      Setor     : " . $this->oRegistro->sSetorCartograficoNovo  );
      $this->logBanco("      Quadra    : " . $this->oRegistro->sQuadraCartograficaNovo );
      $this->logBanco("      logradouro: " . $this->oRegistro->iCodigoLogradouroNovo   );
      throw new Exeption( $sMensagem );
    }

    /**
     * Caso não encontrar a face de Quadra
     */
    if ( pg_num_rows($rsFaceQuadra) == 0 ) {

      $sMensagem = "Face de Quadra inexistente. ";
      $this->logBanco("     $sMensagem" );
      $this->logBanco("      Setor     : " . $this->oRegistro->sSetorCartograficoNovo  );
      $this->logBanco("      Quadra    : " . $this->oRegistro->sQuadraCartograficaNovo );
      $this->logBanco("      logradouro: " . $this->oRegistro->iCodigoLogradouroNovo   );
      return false;
    }

    $iCodigoFaceQuadra = db_utils::fieldsMemory($rsFaceQuadra,0)->j37_face;
    $this->logBanco("    - Face de Quadra Definida Pelo Arquivo: $iCodigoFaceQuadra");

    /**
     * Valida se existe testada pelo IDBQL e FACE Informados
     */

    $sSqlTestaTestada  = "select quantidade_faces,                                                 ";
    $sSqlTestaTestada .= "       face_principal,                                                   ";
    $sSqlTestaTestada .= "       (select j15_codigo                                                ";
    $sSqlTestaTestada .= "          from testadanumero                                             ";
    $sSqlTestaTestada .= "         where j15_idbql = j36_idbql                                     ";
    $sSqlTestaTestada .= "           and j15_face  = face_principal) as sequencial_testada_numero  ";
    $sSqlTestaTestada .= "  from (select count(j36_face)   as quantidade_faces,                    ";
    $sSqlTestaTestada .= "               max(j49_face)   as face_principal,                        ";
    $sSqlTestaTestada .= "               j36_idbql                                                 ";
    $sSqlTestaTestada .= "          from testada                                                   ";
    $sSqlTestaTestada .= "               left join testpri  on j49_idbql = j36_idbql               ";
    $sSqlTestaTestada .= "                                 and j49_face  = j36_face                ";
    $sSqlTestaTestada .= "         where j36_idbql = $iIDBQL                                       ";
    $sSqlTestaTestada .= "      group by j36_idbql) as x;                                          ";
    $rsTestaTestada    = Conexao::getInstancia()->query($sSqlTestaTestada);
    $rsExisteTestada   = Conexao::getInstancia()->query("select 1 from testada where j36_idbql = $iIDBQL and j36_face = $iCodigoFaceQuadra");
    $rsExisteTestadaNumero  = Conexao::getInstancia()->query("select 1 from testadanumero where j15_idbql = $iIDBQL and j15_face = $iCodigoFaceQuadra");
    
    if ( !$rsTestaTestada || !$rsExisteTestada || !$rsExisteTestadaNumero ) {

      $sMensagem = "Erro ao Buscar Dados da TestadaAlterar os Dadso da Tabela lote. Detalhe:".Conexao::getInstancia()->getLastError();
      $this->logBanco($sMensagem,DBLog::LOG_ERROR);
      throw new Exception($sMensagem);
    }
    $lExisteTestada       = pg_num_rows($rsExisteTestada) > 0;
    $lExisteTestadaNumero = pg_num_rows($rsExisteTestadaNumero) > 0; 
    if ( pg_num_rows($rsTestaTestada) == 0 ) {
      
      $sMensagem = "Testada Inexistente. ";                 
      $this->logBanco("     $sMensagem" );    
      $this->logBanco("      Setor         : " . $this->oRegistro->sSetorCartograficoNovo  );
      $this->logBanco("      Quadra        : " . $this->oRegistro->sQuadraCartograficaNovo );
      $this->logBanco("      logradouro    : " . $this->oRegistro->iCodigoLogradouroNovo   );
      $this->logBanco("      Face de Quadra: " . $iCodigoFaceQuadra);
      return false;                     
    }

    $oTesteTestada      = db_utils::fieldsMemory( $rsTestaTestada, 0 );
    $lIncluiNovaTestada = ( $oTesteTestada->face_principal != $iCodigoFaceQuadra ) && !$lExisteTestada;

    $lExisteTestadaNumeroAntiga = true;
    if ( $oTesteTestada->face_principal != '' ) {
      $rsExisteTestadaNumeroAntiga  = Conexao::getInstancia()->query("select 1 from testadanumero where j15_idbql = $iIDBQL and j15_face = {$oTesteTestada->face_principal}");
      
    }
    
    
    /**
     * Caso Encontrar Nao encontrar a testada a Insere
     */
    if ( $lIncluiNovaTestada ) {
     
      $this->logBanco("    - Ira Incluir nova Testadalogradouro    : " . $this->oRegistro->iCodigoLogradouroNovo   );
      $rsInsereTestada = Conexao::getInstancia()->query("insert  
                                                           into testada (j36_idbql,
                                                                         j36_face,
                                                                         j36_codigo,
                                                                         j36_testad,
                                                                         j36_testle)
                                                                 values ({$iIDBQL},
                                                                         {$iCodigoFaceQuadra},
                                                                         {$this->oRegistro->iCodigoLogradouroNovo},
                                                                         {$this->oRegistro->nValorTestadaPrincipalNova},
                                                                         0)");
      if ( !$rsInsereTestada ) {
        
        $sMensagem = " Erro ao Incluir dados da Testada. Detalhe: ".Conexao::getInstancia()->getLastError();
        $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
        throw new Exception( $sMensagem );
      }
      
      $rsInsereTestPri = Conexao::getInstancia()->query("insert into 
                                                        testpri (j49_idbql,j49_face,j49_codigo)
                                                         values ({$iIDBQL}, {$iCodigoFaceQuadra}, {$this->oRegistro->iCodigoLogradouroNovo});");
      if ( !$rsInsereTestPri ) {
        
        $sMensagem = " Erro ao Incluir dados da TestPri. Detalhe: ".Conexao::getInstancia()->getLastError();
        $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
        throw new Exception( $sMensagem );
      }
    } else {
      
      $this->logBanco("    - Alterando Valor Testada:{$this->oRegistro->nValorTestadaPrincipalNova} " );

      $oCampos                          = new stdClass();
      $oCampos->j36_codigo              = $this->oRegistro->iCodigoLogradouroNovo;
      if ( !$this->lUnidadeSecundaria ) {
        $oCampos->j36_testad              = $this->oRegistro->nValorTestadaPrincipalNova;
      }
      $rsAlteracaoTestad                = RecadastramentoSQLUtils::alterar("testada", $oCampos, "j36_idbql = {$iIDBQL} and j36_face = {$iCodigoFaceQuadra}");
      if ( !$rsAlteracaoTestad ) {
        
        $sMensagem = " Erro ao Alterar os Dados da Testada. j36_idbql = {$iIDBQL} and j36_face = {$iCodigoFaceQuadra}. Detalhe: ".Conexao::getInstancia()->getLastError();
        $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
        throw new Exception( $sMensagem );
      }
    }
    
    $iSequencialTestadaNumero= $oTesteTestada->sequencial_testada_numero;
    if ( $iSequencialTestadaNumero == "" || !$lExisteTestadaNumero ) {

      $this->logBanco("     Achou TestadaNumero: $iSequencialTestadaNumero"  );
      $iTestadaNumero        = trim($this->oRegistro->sNumeroPortaNovo) == "" ? 0 : $this->oRegistro->sNumeroPortaNovo;
      $rsInsereTestadaNumero = Conexao::getInstancia()->query("insert  
                                                                 into testadanumero ( j15_codigo,
                                                                                      j15_idbql ,
                                                                                      j15_face  ,
                                                                                      j15_numero,
                                                                                      j15_compl ,
                                                                                      j15_obs)
                                                                             values ( nextval('testadanumero_j15_codigo_seq'),
                                                                                      '{$iIDBQL}',
                                                                                      '{$iCodigoFaceQuadra}',
                                                                                      $iTestadaNumero,
                                                                                      '{$this->oRegistro->sComplementoNovo}',
                                                                                      'Incluido Pelo recadastramento (Arquivo - {$this->oRegistro->sNomeArquivo}')");
      if ( !$rsInsereTestadaNumero ) {                                       
        
        $sMensagem = " Erro ao Incluir dados da TestadaNumero. Detalhe: ".Conexao::getInstancia()->getLastError();
        $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
        throw new Exception( $sMensagem );
      }
    } else {
       
      $oDadosTestadaNumero = new stdClass();
      
      if ( !$lExisteTestadaNumero ) {
        $oDadosTestadaNumero->j15_face   = $iCodigoFaceQuadra;
      }
      $oDadosTestadaNumero->j15_compl  = $this->oRegistro->sComplementoNovo;
      $rsAlteracaoTestadaNumero = RecadastramentoSQLUtils::alterar("testadanumero", $oDadosTestadaNumero, " j15_codigo = {$oTesteTestada->sequencial_testada_numero}");
      
      if ( !$rsAlteracaoTestadaNumero ) {                                       
        
        $sMensagem = " Erro ao Alterar dados da TestadaNumero. Detalhe: ".Conexao::getInstancia()->getLastError();
        $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
        throw new Exception( $sMensagem );
      }
      /**
       * Tabela : TESTPRI
       */
      $oCamposTestPri              = (object)array("j49_codigo"  => $this->oRegistro->iCodigoLogradouroNovo);
      $rsAlteracaoTestadaPrincipal = RecadastramentoSQLUtils::alterar("testpri", $oCamposTestPri, " j49_idbql = {$iIDBQL} and j49_face = $iCodigoFaceQuadra");

      if ( !$rsAlteracaoTestadaPrincipal ) {

        $sMensagem = "Erro ao Alterar Dados da Testada Principal do Lote. Detalhe:".Conexao::getInstancia()->getLastError();
        $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
        throw new Exception( $sMensagem );
      }                                                  
    } 

    /**
     * Caso t6enha incluido nova testada exclui a antiga
     */
    if ( $lIncluiNovaTestada ) {

      $rsExcluiTestada = RecadastramentoSQLUtils::excluir("testada", null, " j36_face = {$oTesteTestada->face_principal} and j36_idbql = {$iIDBQL} 
        and (j36_idbql, j36_face) not in (select j15_idbql, j15_face from testadanumero where j15_idbql =  {$iIDBQL} and j15_face = {$oTesteTestada->face_principal})      
      ");
      if ( !$rsExcluiTestada ) {                                       

        $sMensagem = " Erro ao Excluida dados antigos da Testada: ".Conexao::getInstancia()->getLastError();
        $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
        throw new Exception( $sMensagem );
      }
    }


    
    /**
     * Tabela : LOTEAM
     */
    $sSqlLoteamento = "select j34_loteam                                                      ";
    $sSqlLoteamento.= "  from loteam                                                 ";
    $sSqlLoteamento.= " where j34_descr ~ '{$this->oRegistro->iPlantaLoteamentoNovo}'";
    $rsLoteamento   = pg_query($sSqlLoteamento);

    if ( !$rsLoteamento ) { //Erro de Query

      $sMensagem = "Erro ao Buscar os Dados do Loteamento. Detalhe do Erro: ".Conexao::getInstancia()->getLastError();
      throw new Exeption( $sMensagem );
    }

    if ( pg_num_rows($rsLoteamento) == 0 ) {

      $this->logBanco("    - Loteamento Nao Encontrado: " . $this->oRegistro->iPlantaLoteamentoNovo );

      $oDadosLoteamento      = (object)array(
        "j34_descr"  => $this->oRegistro->iPlantaLoteamentoNovo. " - Recadastramento",
        "j34_areacc" => '0',
        "j34_areapc" => '0',
        "j34_areato" => '0'
       );

      $iSequencialLoteamento  = $this->aTabelas['loteam']->setByLineOfDBUtils( $oDadosLoteamento, true );
      $this->logBanco("    - Incluido Novo codigo de Loteamento: {$iSequencialLoteamento} - {$this->oRegistro->iPlantaLoteamentoNovo}");
    } else {
      $iSequencialLoteamento  = db_utils::fieldsMemory($rsLoteamento,0)->j34_loteam;
    }

    /**
     * Tabela : LOTELOTEAM
     */
    $oDadosLoteamentoLote       = (object)array("j34_loteam" => $iSequencialLoteamento);
    $rsAlteracaoLoteamentoLote  = RecadastramentoSQLUtils::alterar("loteloteam", $oDadosLoteamentoLote, " j34_idbql = {$iIDBQL} ");

    if ( !$rsAlteracaoLoteamentoLote ) { //Erro de Query
    
      $sMensagem = "Erro ao Alterar os Dados do Lote do Loteament. Detalhe do Erro: ".Conexao::getInstancia()->getLastError();
      $this->logBanco("     $sMensagem" );
      $this->logBanco("      Setor de Localização: " . $this->oRegistro->iPlantaLoteamentoNovo  );
      throw new Exeption( $sMensagem );
    }
    
    /**
     * Tabela : LOTELOC
     */
    $sSqlLoteLoc  = "select j05_codigo                                                      \n";
    $sSqlLoteLoc .= "  from setorloc                                                        \n";
    $sSqlLoteLoc .= " where j05_codigoproprio ~ '{$this->oRegistro->iPlantaLoteamentoNovo}' \n";
    $rsLoteLoc    = pg_query($sSqlLoteLoc);

    if ( !$rsLoteLoc ) { ///Erro de Query

      $sMensagem = "Erro ao Buscar os Dados Setor de Localizacao. Detalhe do Erro: ".Conexao::getInstancia()->getLastError();;
      $this->logBanco("     $sMensagem" );
      $this->logBanco("      Setor de Localização: " . $this->oRegistro->iPlantaLoteamentoNovo  );
      throw new Exeption( $sMensagem );
    }

    /**
     * Caso não encontrar a face de Quadra
     * Tabbela SETORLOC
     */
    if ( pg_num_rows($rsLoteLoc) == 0 ) { 

      $this->logBanco("    - Código Próprio Nao Encontrado: " . $this->oRegistro->sSetorCartograficoNovo  );
      $oDadosSetorLoc              = (object)array("j05_descr"         => "Recadastramento: {$this->oRegistro->iPlantaLoteamentoNovo}",
                                                   "j05_codigoproprio" => $this->oRegistro->iPlantaLoteamentoNovo);
      $iSequencialSetorLocalizacao = $this->aTabelas['setorloc']->setByLineOfDBUtils($oDadosSetorLoc, true);
      $this->logBanco("    - Incluido Novo codigo proprio: {$iSequencialSetorLocalizacao} - {$this->oRegistro->iPlantaLoteamentoNovo}" );
    } else {
      $iSequencialSetorLocalizacao = db_utils::fieldsMemory($rsLoteLoc,0)->j05_codigo;
    }

    //
    $this->logBanco("    - Código Setor de Localização: $iSequencialSetorLocalizacao" );

    /**
     * Tabela : LOTELOC
     */
    $oCamposLoteLoc                = new stdClass();
    $oCamposLoteLoc->j06_setorloc  = $iSequencialSetorLocalizacao; 
    $oCamposLoteLoc->j06_quadraloc = $this->oRegistro->iQuadraLoteamentoNovo;
    $oCamposLoteLoc->j06_lote      = $this->oRegistro->iLoteLoteamentoNovo;

    $rsAlteracaoLoteLoc            = RecadastramentoSQLUtils::alterar("loteloc", $oCamposLoteLoc, " j06_idbql = {$iIDBQL} ");    

    if ( !$rsAlteracaoLoteLoc ) {

      $sMensagem = "Erro ao Alterar Dados do Lote de Localização. Detalhe:".Conexao::getInstancia()->getLastError();
      $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
      throw new Exception( $sMensagem );
    }

    if ( $lProcessaDemolicao ) {
       $this->demolirConstrucoes();
    }
    return true;
  }

  /**
   * Processa a alteração dos dados do Imovel
   * @return boolean
   */
  private function processarAlteracaoImovel() {
    
   
    /**
     * Gravar Matric OBS
     **/
    $rsSql = pg_query("select j26_obs from matricobs where j26_matric = {$this->iMatricula}");

    if (!$rsSql ) {

      $sMensagem = "Erro ao Buscar dados da observação da matricula. Detalhe:".Conexao::getInstancia()->getLastError();
      $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
      throw new Exception( $sMensagem );
    }
    
    
    if ( pg_num_rows( $rsSql ) > 0 ) {
     
      $sObservacao            = db_utils::fieldsMemory($rsSql, 0)->j26_obs;
      $sObservacao           .=  "\\nAlteração efetuada pelo recadastramento.";
      $sObservacao           .=  "\\nObservações do Arquivo: {$this->oRegistro->sObservacaoNova}";
      $sObservacao           .=  "\\nNome do Proprietario no Arquivo({$this->oRegistro->sNomeArquivo}): {$this->oRegistro->sNomeProprietarioNovo}";
     
      $oDadosObservacao       = (object)array("j26_obs" => $sObservacao);
      $rsAlteracaoObservacoes = RecadastramentoSQLUtils::alterar("matricobs", $oDadosObservacao, " j26_matric = {$this->iMatricula}");
      
      if (!$rsAlteracaoObservacoes ) {
        
        $sMensagem = "Erro ao Alterar Observação da Matricula. Detalhe:".Conexao::getInstancia()->getLastError();
        $this->logBanco( $sMensagem, DBLog::LOG_ERROR );
        throw new Exception( $sMensagem );
      }
      $this->logBanco("    - ALTEROU Observações da Matricula com o Nome Vindo do Arquivo: " . $this->oRegistro->sNomeProprietarioNovo);
      
    } else {
     
      $sObservacao            = "Alteração efetuada pelo recadastramento.";
      $sObservacao           .= "\\n Observações do Arquivo: {$this->oRegistro->sObservacaoNova}";
      $sObservacao           .= "\\n Nome do Proprietario no Arquivo({$this->oRegistro->sNomeArquivo}): {$this->oRegistro->sNomeProprietarioNovo}"; 
      $oDadosObservacao       = (object)array("j26_matric" => $this->iMatricula, "j26_obs" => $sObservacao);
      $this->aTabelas['matricobs']->setByLineOfDBUtils($oDadosObservacao, true);
    }
    $this->logBanco("    - Incluiu Observações da Matricula com o Nome Vindo do Arquivo: " . $this->oRegistro->sNomeProprietarioNovo);
    return true;
  }

  /**
   * processarAlteracaoConstrucao 
   * 
   * @param bool $lAltera 
   * @access private
   * @return void
   */
  private function processarAlteracaoConstrucao($lAltera = true) {

   /**
     * Validações para modificar a área construida
     */
    $nAreaAtual            = (float)$this->oDadosAtuais->areaconstruida;
    $nAreaBaseComparacao   = $nAreaAtual / 2;
    $nAreaProposta         = $this->oRegistro->nAreaConstruidaNova;


    $lExisteAreaConstruida = $this->oRegistro->lExisteAreaContruida == 1;
    $lAreaConstruidaValida = $nAreaProposta > 1;

    $iAnoConstrucao        = date('Y', time($this->dDataInclusao));

    $nAreaConstruida       = (float)  $this->oRegistro->nAreaConstruidaNova;
    $dDataLancamento       =          $this->dDataInclusao;
    $iCodigoLogradouro     = (int)    $this->oRegistro->iCodigoLogradouroNovo;
    $iNumeroConstrucao     = (int)    $this->oRegistro->sNumeroPortaNovo;
    $sComplemento          = (string) $this->oRegistro->sComplementoNovo;
    $iNumeroPavimentos     = (int)    $this->oRegistro->iNumeroPavimentosNovo; 

    if ($lAltera) {
      /**
       * Alteracao da Tabela : IPTUConstr 
       */
      $oDadosIPTUConstr                = new stdClass();
      $oDadosIPTUConstr->j39_idcons    = $this->iCodigoConstrucao;

      if ( $lAreaConstruidaValida && $lExisteAreaConstruida ) {
        $oDadosIPTUConstr->j39_area    = $this->oRegistro->nAreaConstruidaNova;
      }
      if ( !empty($this->oRegistro->iCodigoLogradouroNovo) ) {
        $oDadosIPTUConstr->j39_codigo    = $this->oRegistro->iCodigoLogradouroNovo;
      }
      if ( !empty($this->oRegistro->sNumeroPortaNovo) ) {
        $oDadosIPTUConstr->j39_numero    = $this->oRegistro->sNumeroPortaNovo;
      }
      if ( !empty($this->oRegistro->sComplementoNovo) ) {
        $oDadosIPTUConstr->j39_compl     = $this->oRegistro->sComplementoNovo;
      }
      $rsAlteracaoIPTUConstr           = RecadastramentoSQLUtils::alterar("iptuconstr", 
                                                                          $oDadosIPTUConstr,
                                                                          "    j39_matric    = {$this->iMatricula} 
                                                                           and j39_idcons    = {$this->iCodigoConstrucao}");
      if ( !$rsAlteracaoIPTUConstr ) {

        $sMensagem = "Erro ao Alterar os Dados da Construcao. Detalhe:".Conexao::getInstancia()->getLastError();
        $this->logBanco($sMensagem,DBLog::LOG_ERROR);
        throw new Exception($sMensagem);
      } 
      /**
       * Tabela : Carlote
       */
      $sWhereExclusao                   = "      caracter.j31_grupo in (30,31,32,33,34,35,            \n";
      $sWhereExclusao                  .= "                             36,37,38,39,40,41,            \n";
      $sWhereExclusao                  .= "                             42,43,48,49)                  \n";
      $sWhereExclusao                  .= "  and carconstr.j48_matric = {$this->iMatricula}           \n";
      $sWhereExclusao                  .= "  and carconstr.j48_idcons = {$this->iCodigoConstrucao}    \n";
      $sWhereExclusao                  .= "  and carconstr.j48_caract = caracter.j31_codigo \n";
      $rsRemocaoCaracteristicas         = RecadastramentoSQLUtils::excluir("carconstr", "caracter", $sWhereExclusao);

      if ( !$rsRemocaoCaracteristicas ) {

        $sMensagem = "Erro ao Remover as Caracteristicas do Lote. Detalhe:".Conexao::getInstancia()->getLastError();
        $this->logBanco($sMensagem,DBLog::LOG_ERROR);
        throw new Exception($sMensagem);
      }
    } else {

      $this->iCodigoConstrucao             = 1;
      $lConstrucaoPrincipal                = 'true';
      $sObservacao                         = "Construção incluída pelo recadastramento do arquivo \'{$this->oRegistro->sNomeArquivo}\'."; 
      $oDadosIptuconstr                    = new stdClass();
      $oDadosIptuconstr->j39_matric        = $this->iMatricula;
      $oDadosIptuconstr->j39_idcons        = $this->iCodigoConstrucao;
      $oDadosIptuconstr->j39_ano           = $iAnoConstrucao;
      $oDadosIptuconstr->j39_area          = $nAreaConstruida;
      $oDadosIptuconstr->j39_areap         = 0.00;
      $oDadosIptuconstr->j39_dtlan         = $dDataLancamento;
      $oDadosIptuconstr->j39_codigo        = $iCodigoLogradouro;
      $oDadosIptuconstr->j39_numero        = $iNumeroConstrucao;
      $oDadosIptuconstr->j39_compl         = $sComplemento;
      $oDadosIptuconstr->j39_dtdemo        = "";
      $oDadosIptuconstr->j39_idaument      = "";
      $oDadosIptuconstr->j39_idprinc       = true;
      $oDadosIptuconstr->j39_habite        = "";
      $oDadosIptuconstr->j39_pavim         = $iNumeroPavimentos;
      $oDadosIptuconstr->j39_codprotdemo   = "";
      $oDadosIptuconstr->j39_obs           = $sObservacao;
      $this->aTabelas['iptuconstr']->setByLineOfDBUtils($oDadosIptuconstr, true);
    }
   
    $aCaracteristicaConstrucao->Utilizacao          = getCaracteristicasConstrucao('utilizacao',        $this->oRegistro->iCaracteristicaUtilizacaoNovo          );
    $aCaracteristicaConstrucao->iNumeroPavimentos   = getCaracteristicasConstrucao('pavimento',         $this->oRegistro->iNumeroPavimentosNovo                  );
    $aCaracteristicaConstrucao->LocalizacaoUnidade  = getCaracteristicasConstrucao('localizacao',       $this->oRegistro->iCaracteristicaLocalizacaoUnidadeNovo  );
    $aCaracteristicaConstrucao->Tipo                = getCaracteristicasConstrucao('tipo',              $this->oRegistro->iCaracteristicaTipoNovo                );
    $aCaracteristicaConstrucao->PadraoConstrutivo   = getCaracteristicasConstrucao('padraoconstrutivo', $this->oRegistro->iCaracteristicaPadraoConstrutivoNovo   );
    $aCaracteristicaConstrucao->Conservacao         = getCaracteristicasConstrucao('conservacao',       $this->oRegistro->iCaracteristicaConservacaoNovo         );
    $aCaracteristicaConstrucao->Uso                 = getCaracteristicasConstrucao('uso',               $this->oRegistro->iCaracteristicaUsoNovo                 );
    $aCaracteristicaConstrucao->Estrutura           = getCaracteristicasConstrucao('estrutura',         $this->oRegistro->iCaracteristicaEstruturaNovo           );
    $aCaracteristicaConstrucao->Agua                = getCaracteristicasConstrucao('agua',              $this->oRegistro->iCaracteristicaAguaNovo                );
    $aCaracteristicaConstrucao->Esgoto              = getCaracteristicasConstrucao('esgoto',            $this->oRegistro->iCaracteristicaEsgotoNovo              );
    $aCaracteristicaConstrucao->EnergiaEletrica     = getCaracteristicasConstrucao('eletrica',          $this->oRegistro->iCaracteristicaEnergiaEletricaNovo     );
    $aCaracteristicaConstrucao->InstalacaoSanitaria = getCaracteristicasConstrucao('sanitaria',         $this->oRegistro->iCaracteristicaInstalacaoSanitariaNovo );
    $aCaracteristicaConstrucao->Cobertura           = getCaracteristicasConstrucao('cobertura',         $this->oRegistro->iCaracteristicaCoberturaNovo           );
    $aCaracteristicaConstrucao->Esquadria           = getCaracteristicasConstrucao('esquadria',         $this->oRegistro->iCaracteristicaEsquadriaNovo           );
    $aCaracteristicaConstrucao->Piso                = getCaracteristicasConstrucao('piso',              $this->oRegistro->iCaracteristicaPisoNovo                );
    $aCaracteristicaConstrucao->RevestimentoExterno = getCaracteristicasConstrucao('revestimento',      $this->oRegistro->iCaracteristicaRevestimentoExternoNovo );

    foreach ($aCaracteristicaConstrucao as $sGrupo => $iCodigoCaracteristica ) {
    
      if ($iCodigoCaracteristica == 0) {
        continue;
      }
      
      $oDados = (object)array("j48_matric" => $this->iMatricula,
                              "j48_idcons" => $this->iCodigoConstrucao,
                              "j48_caract" => $iCodigoCaracteristica);
      $this->aTabelas['carconstr']->setByLineOfDBUtils($oDados, true);
      $this->logBanco("    - Definindo Caracteristica da Construção: $iCodigoCaracteristica, Grupo: $sGrupo");
    }
    //unset($aCamposCarLote, $rsRemocaoCaracteristicas, $rsAlteracaoLote, $oDadosCarlote); 
    return true;
  }

  /**
   * Escreve Log da Alteracao
   *
   * @param string  $sMensagem 
   * @param integer $iTipoLog 
   * @access private
   * @return void
   */
  private function log( $sMensagem = "", $iTipoLog = DBLog::LOG_INFO ) {
    
    /**
     * Logar no banco as alteracoes
     */
    RecadastroImobiliarioImoveisArquivo::$oLog->escreverLog( $sMensagem, $iTipoLog );
  } 
  
  /**
   * Executa log do processamento
   * 
   * @param mixed $sMensagem 
   * @access private
   * @return void
   */
  private function logBanco( $sMensagem ) {
    
    $sMensagem   = "\n".str_ireplace("\n", "", $sMensagem);
    $this->sLog .= $sMensagem;
    $this->log( str_ireplace("\n", "", $sMensagem) );
  }

  public function getLog() {
    return $this->sLog;  
  }
  
  /**
   * Executa demolição em TODAS as Construções da Matricula Atual
   * @throws Exception
   * @return boolean
   */
  private function demolirConstrucoes() {
    
    $iMatricula = $this->iMatricula;
    $oConexao   = Conexao::getInstancia();

    db_app::import("cadastro.Imovel");
    db_app::import("cadastro.Construcao");
    
    $oImovel = new Imovel($iMatricula);
    $aConstrucoes = $oImovel->getConstrucoes( true );
    
    $this->logBanco("   - Existem ". count($aConstrucoes) . " construções a Demolir", DBLog::LOG_ERROR);
    
    foreach ( $aConstrucoes as $oConstrucao ) {
      
      $sWhereDemolicao = "     j39_matric = {$oConstrucao->getCodigoConstrucao()} ";
      $sWhereDemolicao.= " and j39_idcons = {$oConstrucao->getMatricula()}        ";
      $oDadosDemolicao = new stdClass();
      $oDadosDemolicao->j39_dtdemo = $this->oRegistro->oDataEnvio->getDate();
      $oDadosDemolicao->j39_obs = "' || j39_obs || ' \\n Demolição efetuada Pelo Recadastramento. (Arquivo :{$this->oRegistro->sNomeArquivo}).";
      
      if ( !RecadastramentoSQLUtils::alterar("iptuconstr", $oDadosDemolicao, $sWhereDemolicao) ) {
        
        $sMensagem = "Erro ao Demolir Construcao. Detalhe:".Conexao::getInstancia()->getLastError();
        $this->logBanco($sMensagem, DBLog::LOG_ERROR);
        throw new Exception($sMensagem);
      } 
      $this->logBanco("    - Construcao {$oConstrucao->getCodigoConstrucao()} Demolida.", DBLog::LOG_ERROR);
    }
    return true; 
  }
}
