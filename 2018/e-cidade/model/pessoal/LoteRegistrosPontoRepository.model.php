<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

/**
 * Classe representa o repository da classe LoteRegistroPonto.
 * 
 * @package Pessoal
 * @author $Author: dbiuri $
 * @version $Revision: 1.41 $
 */
class LoteRegistrosPontoRepository {

  const MENSAGEM = 'recursoshumanos.pessoal.LoteRegistrosPontoRepository.';

  /**
   * Representa o collection de LoteRegistrosPonto.
   * 
   * @var LoteRegistrosPonto[]
   */
  private $aLotesRegistrosPonto = array();

  /**
   * Representa a instância da classe.
   * 
   * @var LoteRegistrosPontoRepository
   */
  private static $oInstance;

  private function __construct() { }

  private function __clone() { }

  /**
   * Retorna a instância do repository.
   * 
   * @static
   * @access protected
   * @return LoteRegistrosPontoRepository
   */
  protected static function getInstance() {

    if (self::$oInstance == null) {          
      self::$oInstance = new LoteRegistrosPontoRepository();
    }

    return self::$oInstance;
  }

  /**
   * Monta o Objeto LoteRegistrosPonto a partir do código informado como parâmetro
   * 
   * @param  integer $iSequencial
   * @return LoteRegistrosPonto
   */
  public static function make($iSequencial) {

    $oDaoLoteRegistroPonto = new cl_loteregistroponto();
    $sSqlLoteRegistroPonto = $oDaoLoteRegistroPonto->sql_query_file($iSequencial);
    $rsLoteRegistroPonto   = db_query($sSqlLoteRegistroPonto);

    if (!$rsLoteRegistroPonto) {
      throw new DBException(_M(self::MENSAGEM . "erro_pesquisar_lote"));
    }

    $aLoteRegistroEncontrado = db_utils::fieldsMemory($rsLoteRegistroPonto,0);
    $oLoteRegistrosPonto     = new LoteRegistrosPonto($iSequencial);

    $oLoteRegistrosPonto->setCompetencia(new DBCompetencia($aLoteRegistroEncontrado->rh155_ano, $aLoteRegistroEncontrado->rh155_mes));
    $oLoteRegistrosPonto->setInstituicao(new Instituicao($aLoteRegistroEncontrado->rh155_instit));
    $oLoteRegistrosPonto->setDescricao($aLoteRegistroEncontrado->rh155_descricao);
    $oLoteRegistrosPonto->setSituacao($aLoteRegistroEncontrado->rh155_situacao); 
    $oLoteRegistrosPonto->setUsuario(UsuarioSistemaRepository::getPorCodigo($aLoteRegistroEncontrado->rh155_usuario));
    $oLoteRegistrosPonto->setRegistroPonto(LoteRegistrosPontoRepository::getRegistrosLote($oLoteRegistrosPonto));

    return $oLoteRegistrosPonto;
  }

  /**
   * Adiciona um lote do registro do ponto no repository. 
   * 
   * @static
   * @access public
   * @param LoteRegistrosPonto $oLoteRegistroPonto
   */
  public static function adicionar(LoteRegistrosPonto $oLoteRegistroPonto) {
    LoteRegistrosPontoRepository::getInstance()->aLotesRegistrosPonto[$oLoteRegistroPonto->getSequencial()] = $oLoteRegistroPonto; 
  }

  /**
   * Remove um lote do registro do ponto no repository.
   * 
   * @static
   * @access public
   * @param LoteRegistrosPonto $oLoteRegistroPonto
   * @throws DBException
   */
  public static function remover(LoteRegistrosPonto $oLoteRegistroPonto) {

    LoteRegistrosPontoRepository::getInstance()->removerRegistroPonto($oLoteRegistroPonto);
    LoteRegistrosPontoRepository::getInstance()->removerLote($oLoteRegistroPonto);
    unset(LoteRegistrosPontoRepository::getInstance()->aLotesRegistrosPonto[$oLoteRegistroPonto->getSequencial()]);
    return true;
  }

  /**
   * Responsável por remover todos os pontos do lote.
   * 
   * @static
   * @access public
   * @param LoteRegistrosPonto $oLoteRegistroPonto
   * @throws DBException
   */
  public static function cancelarConfirmacao(LoteRegistrosPonto $oLoteRegistroPonto) {
    LoteRegistrosPontoRepository::getInstance()->removerPonto($oLoteRegistroPonto);
  }

  /**
   * Responsável por remover o lote do registro do ponto.
   * 
   * @access private
   * @param LoteRegistrosPonto $oLoteRegistroPonto
   * @throws DBException
   */
  private function removerLote(LoteRegistrosPonto $oLoteRegistroPonto) {

    $oDaoLoteRegistroPonto = new cl_loteregistroponto();
    $oDaoLoteRegistroPonto->excluir($oLoteRegistroPonto->getSequencial());

    if ($oDaoLoteRegistroPonto->erro_status == "0") {
      throw new DBException(_M(self::MENSAGEM . 'erro_excluir_lote'));
    }

  }

  /**
   * Responsável por remover os registros do ponto do lote.
   * 
   * @access private
   * @param LoteRegistrosPonto $oLoteRegistroPonto
   * @throws DBException
   */
  private function removerRegistroPonto(LoteRegistrosPonto $oLoteRegistroPonto) {

    $aSequencialPrePonto = array();

    /**
     * Excluí os vínculos das tabelas "loteregistroponto" e "rhpreponto".
     */
    $oDaoLotePrePonto = new cl_rhprepontoloteregistroponto();
    $sCampos          = "rh156_sequencial, rh156_rhpreponto";
    $sWhere           = "rh156_loteregistroponto = {$oLoteRegistroPonto->getSequencial()}";
    $sSqlLotePrePonto = $oDaoLotePrePonto->sql_query_file(null, $sCampos, null, $sWhere);
    $rsLotePrePonto   = db_query($sSqlLotePrePonto);

    if (!$rsLotePrePonto) {
      throw new DBException(_M(self::MENSAGEM . 'erro_consultar_vinculo_registro_ponto'));
    }

    for ($iIndice = 0; $iIndice < pg_num_rows($rsLotePrePonto); $iIndice++) {

      $oDadosLotePrePonto    = db_utils::fieldsMemory($rsLotePrePonto, $iIndice);  
      $aSequencialPrePonto[] = $oDadosLotePrePonto->rh156_rhpreponto;
      $oDaoLotePrePonto->excluir($oDadosLotePrePonto->rh156_sequencial);

      if ($oDaoLotePrePonto->erro_status == "0") {
        throw new DBException(_M(self::MENSAGEM . 'erro_excluir_vinculo_registro_ponto'));
      }
    }

    /*
     * Excluí os registros pontos.
     */
    foreach ($aSequencialPrePonto as $iSequencialPrePonto) {

      $oDaoPrePonto = new cl_rhpreponto();
      $oDaoPrePonto->excluir($iSequencialPrePonto);

      if ($oDaoPrePonto->erro_status == "0") {
        throw new DBException(_M(self::MENSAGEM . 'erro_excluir_registro_ponto'));
      }
    }

  }

  /**
   * Responsável por remover os registros do ponto do lote no ponto de salário.
   * 
   * @access private
   * @param LoteRegistrosPonto $oLoteRegistroPonto
   * @throws Exception
   */
  private function removerPonto(LoteRegistrosPonto $oLoteRegistroPonto) {

    $sPonto = Ponto::SALARIO;

    if ($oLoteRegistroPonto->getFolhaPagamento() && $oLoteRegistroPonto->getFolhaPagamento()->getTipoFolha() == FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR ) {
      $sPonto = Ponto::COMPLEMENTAR;
    }
    
    $aRegistrosPonto = $oLoteRegistroPonto->getRegistroPonto();

    foreach ($aRegistrosPonto as $oRegistroPonto) {

      $oPontoSalario = $oRegistroPonto->getServidor()->getPonto($sPonto);
      $oPontoSalario->limpar($oRegistroPonto->getRubrica()->getCodigo());

      /**
       * Remove rubrica da rhhistoricoponto
       */
      if (DBPessoal::verificarUtilizacaoEstruturaSuplementar() && $oLoteRegistroPonto->getFolhaPagamento()) {

        $oFolha = FolhaPagamentoFactory::construirPeloCodigo(FolhaPagamento::getCodigoFolha($oLoteRegistroPonto->getFolhaPagamento()->getTipoFolha()));

        if ($oFolha){
          $oFolha->excluirRubricaHistoricoPonto($oRegistroPonto->getServidor()->getMatricula(), $oRegistroPonto->getRubrica()->getCodigo());
        }
      }
    }
  }

  /**
   * Verifica se o lote do registro do ponto está no repository.
   * 
   * @static
   * @param LoteRegistrosPonto $oLoteRegistroPonto
   * @return Boolean
   */
  public static function verificar(LoteRegistrosPonto $oLoteRegistroPonto) {
    return array_key_exists($oLoteRegistroPonto->getSequencial(),LoteRegistrosPontoRepository::getInstance()->aLotesRegistrosPonto);
  }

  /**
   * Instancia todos lotes da competência
   * 
   * @param  DBCompetencia $oCompetencia
   * @param  Instituicao   $oInstituicao
   * @return LoteRegistrosPonto[] $aLotes
   */

  public static function getLotesByCompetencia(DBCompetencia $oCompetencia, $lTodos = true, Instituicao $oInstituicao = null, $sSituacao = null) {

    if (!$oInstituicao) {
      $oInstituicao = new Instituicao(db_getsession('DB_instit'));
    }

    $oDaoLoteRegistroPonto    = new cl_loteregistroponto();
    $sWhereLoteRegistroPonto  = "     rh155_ano    = {$oCompetencia->getAno()}";
    $sWhereLoteRegistroPonto .= " and rh155_mes    = {$oCompetencia->getMes()}";
    $sWhereLoteRegistroPonto .= " and rh155_instit = {$oInstituicao->getCodigo()}";
    $sWhereLoteRegistroPonto .= " and rh155_sequencial not in (select rh160_loteregistroponto from assentaloteregistroponto)";

    if (!$lTodos) {
      $sWhereLoteRegistroPonto .= " and rh155_situacao <> 'A'";
    }

    if (!empty($sSituacao) && $lTodos === true) {
      $sWhereLoteRegistroPonto .= " and rh155_situacao = '{$sSituacao}'";
    }

    $sSqlLoteRegistroPonto   = $oDaoLoteRegistroPonto->sql_query_file(null, '*',null, $sWhereLoteRegistroPonto);
    $rsLoteRegistroPonto   = db_query($sSqlLoteRegistroPonto);

    if (!$rsLoteRegistroPonto) {
      throw new DBException(_M(self::MENSAGEM . "erro_pesquisar_lote_competencia"));
    }

    $aLoteRegistrosEncontrados = db_utils::getCollectionByRecord($rsLoteRegistroPonto);
    $aLotes                    = array();

    foreach ($aLoteRegistrosEncontrados as $oLote) {
      $aLotes[] = LoteRegistrosPontoRepository::getInstanceByCodigo($oLote->rh155_sequencial);
    }

    return $aLotes;
  }

  /**
   * Instancia todos lotes do usuário
   * 
   * @param  UsuarioSistema      $oUsuario
   * @param  DBCompetencia       $oCompetencia
   * @param  Instituicao         $oInstituicao
   * @return LoteRegistrosPonto[] $aLotes
   */
  public static function getLotesByUsuario(UsuarioSistema $oUsuario, DBCompetencia $oCompetencia = null, Instituicao $oInstituicao = null) {

    if (!$oInstituicao) {
      $oInstituicao = new Instituicao(db_getsession('DB_instit'));
    }

    $oDaoLoteRegistroPonto = new cl_loteregistroponto();
    $sWhereLoteRegistro  = "    rh155_usuario = {$oUsuario->getCodigo()} ";
    $sWhereLoteRegistro .= "and rh155_instit  = {$oInstituicao->getCodigo()} ";
    $sWhereLoteRegistro .= "and rh155_sequencial not in (select rh160_loteregistroponto from assentaloteregistroponto)";

    $sSqlLoteRegistroPonto = $oDaoLoteRegistroPonto->sql_query_file(null, '*', 'rh155_sequencial desc', $sWhereLoteRegistro);

    if ($oCompetencia != null) {

      $sWhereLoteRegistro  = "    rh155_usuario = {$oUsuario->getCodigo()}    ";
      $sWhereLoteRegistro .= "and rh155_ano     = {$oCompetencia->getAno()}   "; 
      $sWhereLoteRegistro .= "and rh155_mes     = {$oCompetencia->getMes()}   "; 
      $sWhereLoteRegistro .= "and rh155_instit  = {$oInstituicao->getCodigo()}"; 
      $sWhereLoteRegistro .= "and rh155_sequencial not in (select rh160_loteregistroponto from assentaloteregistroponto)";

      $sSqlLoteRegistroPonto = $oDaoLoteRegistroPonto->sql_query_file(null, '*', 'rh155_sequencial desc', $sWhereLoteRegistro);
    }

    $rsLoteRegistroPonto   = db_query($sSqlLoteRegistroPonto);

    if (!$rsLoteRegistroPonto) {
      throw new DBException(_M(self::MENSAGEM . "erro_pesquisar_lote_usuario"));
    }

    $aLoteRegistrosEncontrados = db_utils::getCollectionByRecord($rsLoteRegistroPonto);
    $aLotes                = array();

    foreach ($aLoteRegistrosEncontrados as $oLote) {
      $aLotes[] = LoteRegistrosPontoRepository::getInstanceByCodigo($oLote->rh155_sequencial);
    }

    return $aLotes;
  }

  /**
   * Retornamos todos os Lotes que foram criados a partir de um Assentamento de Substituição.
   *
   * @param   $iMatricula
   * @param   DBCompetencia $oCompetencia 
   * @return  Array|LoteRegistroPonto
   */
  public static function getLotesAssentamentosByMatricula($iMatricula, DBCompetencia $oCompetencia = null, $sNaturezaAssentamento = null) {

    if (is_null($oCompetencia)) {
      $oCompetencia = DBPessoal::getCompetenciaFolha();
    }

    $oDaoRhPrePontoLoteRegistroPonto      = new cl_rhprepontoloteregistroponto();

    $sCamposPrePontoLoteRegistroPonto     = "distinct rh155_sequencial";

    if(!empty($oTipoAssentamento)) {
      $sCamposPrePontoLoteRegistroPonto  .= ", rh160_assentamento";
    }

    $sWhereRhprepontoloteregistroponto    = "    rh149_regist  = {$iMatricula}               ";
    $sWhereRhprepontoloteregistroponto   .= " and rh155_ano    = {$oCompetencia->getAno()}   ";
    $sWhereRhprepontoloteregistroponto   .= " and rh155_mes    = {$oCompetencia->getMes()}   ";

    if(!empty($sNaturezaAssentamento)) {
      $sWhereRhprepontoloteregistroponto .= " and h12_natureza = {$sNaturezaAssentamento}  ";
    }

    $sSqlRhprepontoloteregistroponto      = $oDaoRhPrePontoLoteRegistroPonto->sql_query(null, $sCamposPrePontoLoteRegistroPonto, ' rh155_sequencial', $sWhereRhprepontoloteregistroponto);
    $rsRhprepontoloteregistroponto        = db_query($sSqlRhprepontoloteregistroponto);

    if (!$rsRhprepontoloteregistroponto) {
      throw new DBException(_M(self::MENSAGEM . "erro_pesquisar_lote_competencia"));
    }

    $aLotes = array();

    for ($iLote = 0; $iLote <  pg_num_rows($rsRhprepontoloteregistroponto); $iLote++ ) {

      $oStdLote      = db_utils::fieldsMemory($rsRhprepontoloteregistroponto, $iLote);

      if( isset($iSequencialAnterior) && $iSequencialAnterior == $oStdLote->rh155_sequencial ){
        continue;
      }

      if(!empty($sNaturezaAssentamento)) {

        $oAssentamento = AssentamentoFactory::getByCodigo($oStdLote->rh160_assentamento);

        if($sNaturezaAssentamento == Assentamento::NATUREZA_SUBSTITUICAO) {
          $aLotes[] = LoteRegistrosPontoRepository::getInstanceByCodigo($oStdLote->rh155_sequencial);
        }

      } else {
        $aLotes[] = LoteRegistrosPontoRepository::getInstanceByCodigo($oStdLote->rh155_sequencial);
      }

      $iSequencialAnterior = $oStdLote->rh155_sequencial;
    }

    return $aLotes;
  }

  /**
   * Instancia lote pelo código
   * 
   * @param  integer $iSequencial
   * @return LoteRegistrosPonto
   */

  public static function getInstanceByCodigo($iSequencial) {

    if ( $iSequencial == 0 ) {
      throw new BusinessException(_M(self::MENSAGEM . "sequencial_invalido"));
    }

    if ( empty(LoteRegistrosPontoRepository::getInstance()->aLotesRegistrosPonto[$iSequencial]) ) {
      LoteRegistrosPontoRepository::adicionar(LoteRegistrosPontoRepository::make($iSequencial));
    }

    return LoteRegistrosPontoRepository::getInstance()->aLotesRegistrosPonto[$iSequencial];

  }

  /**
   * Carrega todos os registros do lote
   * 
   * @param  LoteRegistrosPonto $oLoteRegistrosPonto
   * @return array $aRegistrosLote
   */
  public static function getRegistrosLote(LoteRegistrosPonto $oLoteRegistroPonto) {

    $oDaoRhPrePontoLoteRegistroPonto   =  new cl_rhprepontoloteregistroponto();
    $sSqlRhPrePontoLoteRegistroPonto   =  $oDaoRhPrePontoLoteRegistroPonto->sql_query_file(null, "rh156_rhpreponto", null, "rh156_loteregistroponto  = {$oLoteRegistroPonto->getSequencial()}");
    $rsRhPrePontoLoteRegistroPonto     = db_query($sSqlRhPrePontoLoteRegistroPonto);

    if ( !$rsRhPrePontoLoteRegistroPonto ) {
      throw new BusinessException(_M(self::MENSAGEM . 'erro_consultar_vinculo_registro_ponto'));
    }
    $aRegistrosLote                    = array();
    $aVinculoPrePontoLoteRegistroPonto = db_utils::getCollectionByRecord($rsRhPrePontoLoteRegistroPonto);

    foreach ($aVinculoPrePontoLoteRegistroPonto as $iRegistroPrePonto => $oRegistroPrePonto) {

      $oDaoRhPrePonto = new cl_rhpreponto();
      $sSqlRhPrePonto = $oDaoRhPrePonto->sql_query_file($oRegistroPrePonto->rh156_rhpreponto);
      $rsRhPrePonto   = db_query($sSqlRhPrePonto);

      if ( !$rsRhPrePonto ) {
        throw new BusinessException(_M(self::MENSAGEM . 'erro_consultar_pre_ponto'));
      }

      $oStdRegistroLote = db_utils::fieldsMemory($rsRhPrePonto,0);

      $oRegistroRecuperado  = new RegistroLoteRegistrosPonto();

      $oRegistroRecuperado->setCodigo($oStdRegistroLote->rh149_sequencial);
      $oRegistroRecuperado->setCodigoLote($oLoteRegistroPonto->getSequencial());
      $oRegistroRecuperado->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($oStdRegistroLote->rh149_instit));
      $oRegistroRecuperado->setServidor(ServidorRepository::getInstanciaByCodigo($oStdRegistroLote->rh149_regist,
                                                                                 $oLoteRegistroPonto->getCompetencia()->getAno(),
                                                                                 $oLoteRegistroPonto->getCompetencia()->getMes()));

      $oRegistroRecuperado->setRubrica(RubricaRepository::getInstanciaByCodigo($oStdRegistroLote->rh149_rubric));
      $oRegistroRecuperado->setCompetencia($oStdRegistroLote->rh149_competencia);
      $oRegistroRecuperado->setQuantidade($oStdRegistroLote->rh149_quantidade);
      $oRegistroRecuperado->setValor($oStdRegistroLote->rh149_valor);
      $oRegistroRecuperado->setFolhaPagamento(FolhaPagamentoFactory::construirPeloTipo($oStdRegistroLote->rh149_tipofolha));

      $aRegistrosLote[] = $oRegistroRecuperado;
    }

    return $aRegistrosLote;
  }

  /**
   * Persist no banco um lote de registros no ponto
   *
   * @param LoteRegistroPonto
   * @return LoteRegistroPonto
   */
  public static function persist(LoteRegistrosPonto $oLoteRegistroPonto) {

    $oDaoLoteRegistroPonto = new cl_loteregistroponto();

    $oLoteRegistroPonto->setDescricao(pg_escape_string($oLoteRegistroPonto->getDescricao()));

    $oDaoLoteRegistroPonto->rh155_sequencial   = $oLoteRegistroPonto->getSequencial();
    $oDaoLoteRegistroPonto->rh155_descricao    = $oLoteRegistroPonto->getDescricao();
    $oDaoLoteRegistroPonto->rh155_ano          = $oLoteRegistroPonto->getCompetencia()->getAno();
    $oDaoLoteRegistroPonto->rh155_mes          = $oLoteRegistroPonto->getCompetencia()->getMes();
    $oDaoLoteRegistroPonto->rh155_situacao     = $oLoteRegistroPonto->getSituacao();
    $oDaoLoteRegistroPonto->rh155_instit       = $oLoteRegistroPonto->getInstituicao()->getSequencial();
    $oDaoLoteRegistroPonto->rh155_usuario      = $oLoteRegistroPonto->getUsuario()->getCodigo();

    if ( $oLoteRegistroPonto->getSequencial() == null || $oLoteRegistroPonto->getSequencial() == '' ) {
      $oDaoLoteRegistroPonto->incluir(null);
      $oLoteRegistroPonto->setSequencial($oDaoLoteRegistroPonto->rh155_sequencial);

    } else {
      $oDaoLoteRegistroPonto->alterar($oLoteRegistroPonto->getSequencial());
    }

    if ( $oDaoLoteRegistroPonto->erro_status == "0" ){
      throw new BusinessException(_M(self::MENSAGEM . 'erro_persistir_lote'));
    }

    $oDaoRhprepontoloteregistroponto   =  new cl_rhprepontoloteregistroponto();
    $sWhereRhprepontoloteregistroponto = "rh156_loteregistroponto  = {$oLoteRegistroPonto->getSequencial()}";
    $sSqlRhprepontoloteregistroponto   =  $oDaoRhprepontoloteregistroponto->sql_query_file(null, 
                                                                                           "rh156_rhpreponto",
                                                                                           null,
                                                                                           $sWhereRhprepontoloteregistroponto);
    $rsRhprepontoloteregistroponto     = db_query($sSqlRhprepontoloteregistroponto);

    /**
     * Valida o erro de query
     */
    if ( !$rsRhprepontoloteregistroponto ) {
      throw new DBException(_M(self::MENSAGEM . 'erro_consultar_vinculo_registro_ponto'));
    }
     
    /**
     * Nenhum registro para lote confirmado
     */
    if ( count($oLoteRegistroPonto->getRegistroPonto()) == 0 && $oLoteRegistroPonto->getSituacao() == LoteRegistrosPonto::CONFIRMADO ) {
      throw new BusinessException(_M(self::MENSAGEM . 'erro_confirmar_lote_vazio')); 
    }

    $lSemRegistrosPontoComRegistrosPrePonto = ( count($oLoteRegistroPonto->getRegistroPonto()) == 0 && pg_num_rows($rsRhprepontoloteregistroponto) > 0 );
    $lComRegistrosPontoComRegistrosPrePonto = ( count($oLoteRegistroPonto->getRegistroPonto())  > 0 && pg_num_rows($rsRhprepontoloteregistroponto) > count($oLoteRegistroPonto->getRegistroPonto()) );

    if ( $lSemRegistrosPontoComRegistrosPrePonto || $lComRegistrosPontoComRegistrosPrePonto ) {

      for ($indResult=0; $indResult < pg_num_rows($rsRhprepontoloteregistroponto); $indResult++) { 

        $oRecuperadoRhprepontoloteregistroponto  = db_utils::fieldsMemory($rsRhprepontoloteregistroponto, $indResult);

        $oDaoRhpreponto = new cl_rhpreponto();
        $oDaoRhpreponto->excluir("{$oRecuperadoRhprepontoloteregistroponto->rh156_rhpreponto}", null);

        if ( $oDaoRhpreponto->erro_status == 0 ) {
          throw new BusinessException(_M(self::MENSAGEM . 'erro_excluir_registro_ponto'));
        }
      }

      $oDaoRhprepontoloteregistroponto->excluir(null, "rh156_loteregistroponto  = {$oLoteRegistroPonto->getSequencial()}");
      if ( $oDaoRhprepontoloteregistroponto->erro_status == 0 ) {
        throw new BusinessException(_M(self::MENSAGEM . 'erro_excluir_vinculo_registro_ponto'));
      }
    }

    if ( count($oLoteRegistroPonto->getRegistroPonto()) == 0 ) {
      return $oLoteRegistroPonto;
    }
    
    $aRegistrosPonto = $oLoteRegistroPonto->getRegistroPontoServidor();

    foreach ( $aRegistrosPonto as $iMatricula => $oRegistroLoteRegistrosPontoServidor) {

      $iTipoFolha = $oRegistroLoteRegistrosPontoServidor[0]->getFolhaPagamento()->getTipoFolha();
      $sPonto     =  Ponto::SALARIO;

      if ($iTipoFolha == FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR) {
        $sPonto   =  Ponto::COMPLEMENTAR;  
      }

      $oPonto = $oRegistroLoteRegistrosPontoServidor[0]->getServidor()->getPonto($sPonto);
      /**
       * Limpa as rubricas antes de inclui-las no ponto
       */
      foreach ($oRegistroLoteRegistrosPontoServidor as $oRegLoteRegsPonto) {
        $oPonto->carregarRegistros(array($oRegLoteRegsPonto->getRubrica()->getCodigo()));
        $oPonto->limpar($oRegLoteRegsPonto->getRubrica()->getCodigo());
      }

      foreach ($oRegistroLoteRegistrosPontoServidor as $sRubrica => $oRegistroLoteRegistrosPonto) {

        $oRegistroLoteRegistrosPontoSalvo = RegistroLoteRegistrosPontoRepository::persist($oRegistroLoteRegistrosPonto);

        if ( $oRegistroLoteRegistrosPontoSalvo === false ) {
          throw new BusinessException(_M(self::MENSAGEM . 'erro_persistir_registro'));
        }

        /**
         * Rubricas já existentes no ponto de salário do servidor, serão substituídas pela do lote.
         */
        if ( $oLoteRegistroPonto->getSituacao() == LoteRegistrosPonto::CONFIRMADO ) {
          $oPonto->adicionarRegistro($oRegistroLoteRegistrosPontoSalvo, $oLoteRegistroPonto->getTipoLancamentoPonto());
        }
      }
      if ( $oLoteRegistroPonto->getSituacao() == LoteRegistrosPonto::CONFIRMADO ) {
        $oPonto->salvar();
      }
    }


    return $oLoteRegistroPonto;
  }
}
