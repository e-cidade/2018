<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
 *  Classe representa o Repository da classe Lotação.
 *  @package Folha
 *  @author Renan Melo <renan@dbseller.com.br>
 */
class LotacaoRepository {
  
  /**
   * Arquivo de Mensagens.
   */
  const MENSAGEM = "recursoshumanos.pessoal.LotacaoRepository";

  /**
   * Instância do Repository
   * @var LotacaoRepository
   */
  private static $oInstance;

  private $aLotacoes = array();

  private function __construct(){}

  private function __clone(){}

  /**
   * Retorna a instância do Repository.
   *
   * @static
   * @access protected
   * @return LotacaoRepository
   */
  private static function getInstance() {

    if (self::$oInstance === null) {
      self::$oInstance = new LotacaoRepository();
    }

    return self::$oInstance;
  }

  /**
   * Monta o Objeto Lotacao a partir do Código da lotação informado.
   *
   * @static
   * @access public
   * @param  integer $iSequencial Sequencial da Lotação
   * @return Lotacao
   */
  public static function make($iSequencial) {

    /**
     * Retorna a lotação a partir do sequencial informado.
     */
    $oDaoLotacao = new cl_rhlota();
    $sSqlLotacao = $oDaoLotacao->sql_query_file($iSequencial);
    $rsLotacao   = db_query($sSqlLotacao);

    if (!$rsLotacao) {
      throw new DBException(_M(self::MENSAGEM.'erro_buscar_lotacao'));
    }

    if (pg_num_rows($rsLotacao) == 0) {
      throw new BusinessException(_M(self::MENSAGEM.'nenhuma_lotacao_encontrada'));
    }

    $oDadosLotacao = db_utils::fieldsMemory($rsLotacao, 0);

    $oLotacao = new Lotacao($oDadosLotacao->r70_codestrut);
    $oLotacao->setCodigoLotacao($oDadosLotacao->r70_codigo);
    $oLotacao->setUsuarios(self::getUsuariosByLotacao($oLotacao));
    $oLotacao->setDescricaoLotacao($oDadosLotacao->r70_descr);
    $oLotacao->setInstituicao(InstituicaoRepository::getInstituicaoByCodigo($oDadosLotacao->r70_instit));
    $oLotacao->setCaracteristicaPeculiar(new CaracteristicaPeculiar($oDadosLotacao->r70_concarpeculiar));
    $oLotacao->setAnalitica($oDadosLotacao->r70_codestrut == 't');
    $oLotacao->setAtiva($oDadosLotacao->r70_ativo == 't');
    $oLotacao->setStringEstrutural($oDadosLotacao->r70_estrut);
    $oLotacao->setCgm(CgmRepository::getByCodigo($oDadosLotacao->r70_numcgm));

    return $oLotacao;
  }

  /**
   * Retorna uma instância de lotação por instituição
   *
   * @static
   * @access public
   * @return Lotacao instância de lotação
   */
  public static function getLotacoesByInstituicao(Instituicao $oInstituicao = null, $lAtivas = null) {

    if (empty($oInstituicao)){
      $oInstituicao = new Instituicao(db_getsession('DB_instit'));
    }

    $oDaoLotacao     = new cl_rhlota();
    $sCamposLotacao  = " r70_codigo, r70_codestrut, r70_estrut, r70_descr, r70_analitica::integer, r70_instit, ";
    $sCamposLotacao .= " r70_ativo::integer, r70_numcgm, r70_concarpeculiar";
    $sWhereLotacao   = " r70_instit = {$oInstituicao->getCodigo()}";

    if ( $lAtivas != null ) {

      $lAtivo = 't';
      if ( $lAtivas === false ){
        $lAtivo = 'f';
      }

      $sWhereLotacao .= " and r70_ativo = '{$lAtivo}'";
    }

    $sSqlLotacao     = $oDaoLotacao->sql_query_file(null, $sCamposLotacao, null, $sWhereLotacao);
    $rsLotacao       = db_query($sSqlLotacao);

    if (!$rsLotacao) {

      $oAtributosErro                  = new stdClass();
      $oAtributosErro->nomeInstituicao = $oInstituicao->getDescricao();
      throw new BusinessException(_M(self::MENSAGEM ."erro_buscar_lotacoes_por_insituicao", $oAtributosErro));

    }

    $aLotacoesInstituicao  = array();
    $aColecaoLotacao       = db_utils::getColectionByRecord($rsLotacao);

    if ( count($aColecaoLotacao) > 0 ){

      foreach ($aColecaoLotacao as $oStdLotacao) {
        $aLotacoesInstituicao[] = LotacaoRepository::getInstanceByCodigo($oStdLotacao->r70_codigo);
      }
    }

    return $aLotacoesInstituicao;
  }
  
  /**
   * Adiciona uma instancia de Lotacao no repository.
   * 
   * @param  Lotacao $oLotacao 
   */
  public static function adicionar(Lotacao $oLotacao) {
    LotacaoRepository::getInstance()->aLotacoes[$oLotacao->getCodigoLotacao()] = $oLotacao; 
  }
  

  /**
   * Instancia uma lotação pelo código
   * 
   * @param  integer $iCodigo
   */
  public static function getInstanceByCodigo($iCodigo) {

    if ( empty(LotacaoRepository::getInstance()->aLotacoes[$iCodigo]) ) {
      LotacaoRepository::adicionar(LotacaoRepository::make($iCodigo));
    }

    return LotacaoRepository::getInstance()->aLotacoes[$iCodigo]; 

  }
 
 /**
  * Retorna um array de lotacoes por usuário
  * 
  * @param  UsuarioSistema $oUsuario 
  * @return array          $aLotacoesUsuario
  */
  public static function getLotacoesByUsuario(UsuarioSistema $oUsuario, Instituicao $oInstituicao = null) {

    $oDaoUsuariosRhLota   = new cl_db_usuariosrhlota;

    $sWhere = "rh157_usuario = {$oUsuario->getCodigo()}";

    if (!is_null($oInstituicao)) {
      $sWhere .= " and r70_instit = {$oInstituicao->getCodigo()}";
    }

    $sSqlLotacoesUsuario  = $oDaoUsuariosRhLota->sql_query(null,'*',null, $sWhere);
    $rsLotacoesUsuario    = db_query($sSqlLotacoesUsuario);
    $aLotacoesUsuario     = array();

    if (!$rsLotacoesUsuario) {
      throw new DBException(_M(self::MENSAGEM.'erro_buscar_lotacao_usuario'));
    }

    $aLotacoesEncontradas = db_utils::getCollectionByRecord($rsLotacoesUsuario);

    foreach ($aLotacoesEncontradas as $oLotacao) {
      $aLotacoesUsuario[] = LotacaoRepository::getInstanceByCodigo($oLotacao->rh157_lotacao);
    } 

    return $aLotacoesUsuario;
   }

  /**
   * Retorna os usuários da Lotação informada.
   * 
   * @static
   * @access public
   * @param  Lotacao $oLotacao  Objeto Lotação.
   * @return Array   $aUsuarios usuarios pertecentes a lotação informada.
   */
  public static function getUsuariosByLotacao(Lotacao $oLotacao) {

    $oDaoUsuariosLotacao = new cl_db_usuariosrhlota();
    $sSqlUsuariosLotacao = $oDaoUsuariosLotacao->sql_query_file(null, "rh157_usuario", null, "rh157_lotacao = {$oLotacao->getCodigoLotacao()}");
    $rsUsuariosLotacao   = db_query($sSqlUsuariosLotacao);

    if (!$rsUsuariosLotacao) {
      throw new DBException(_M(self::MENSAGEM.'erro_buscar_usuario_lotacao'));
    }

    $aUsuarios = array();

    for ($iUsuario = 0; $iUsuario < pg_num_rows($rsUsuariosLotacao); $iUsuario++) {
      $aUsuarios[] = db_utils::fieldsMemory($rsUsuariosLotacao, $iUsuario)->rh157_usuario;
    }

    return $aUsuarios;
  }

  /**
   * Persist no banco os dados da Lotacao, persist apenas na tabela db_usuariosrhlota.
   *
   * @static
   * @access public
   * @param  Lotacao $oLotacao 
   */
  public static function persist(Lotacao $oLotacao) {

    $oDaoUsuariosLotacao = new cl_db_usuariosrhlota();
    $oDaoUsuariosLotacao->rh157_sequencial = null;
    $oDaoUsuariosLotacao->rh157_lotacao    = $oLotacao->getCodigoLotacao();

    foreach ($oLotacao->getUsuarios() as $iMatricula) {

      $oDaoUsuariosLotacao->rh157_usuario = $iMatricula;
      $oDaoUsuariosLotacao->incluir(null);

      if ($oDaoUsuariosLotacao->erro_status == "0") {
        throw new DBException(_M(self::MENSAGEM.'erro_salvar_usuarios_lotacao'));
      }
    }

    return true;

    /**
     * @todo persistir da rhlota quando for necessario.
     */
  }

  /**
   * Exclui o vinculo  do usuario do sistema com a Lotação informada por parâmetro.
   * 
   * @static
   * @access public
   * @param  Lotacao $oLotacao Lotação a ser excluida.
   */
  public static function excluir(Lotacao $oLotacao = null, UsuarioSistema $oUsuario = null) {

    $oDaoUsuariosLotacao = new cl_db_usuariosrhlota();

    if (!is_null($oLotacao)) {
      $sWhere = "rh157_lotacao = {$oLotacao->getCodigoLotacao()}";
    }
    
    if (!is_null($oUsuario)) {
      $sWhere = "rh157_usuario = {$oUsuario->getCodigo()}";
    }

    $oDaoUsuariosLotacao->excluir(null, $sWhere);

    if ($oDaoUsuariosLotacao->erro_status == "0" ) {
      throw new DBException(_M(self::MENSAGEM.'erro_excluir_usuarios_lotacao'));
    }

    /**
     * @todo excluir da rhlota quando for necessario.
     */
  }

  public static function getInstanceByEstrutural($sEstrutural) {

    if (empty($sEstrutural)) {
      throw new BusinessException(_M(self::MENSAGEM.'estrutural_não_informado'));
    }

    $oDaoLotacao = new cl_rhlota();
    $sSqlLotacao = $oDaoLotacao->sql_query_file(null, "*", null, "r70_estrut = '{$sEstrutural}'");
    $rsLotacao   = $oDaoLotacao->sql_record($sSqlLotacao);

    if (!$rsLotacao) {
      throw new DBException(_M(self::MENSAGEM.'erro_buscar_lotacao_estrutural'));
    }

    if ( pg_num_rows($rsLotacao) > 1 ){
      throw new BusinessException(_M(self::MENSAGEM.'estrutural_duplicado'));
    }

    $oLotacao = LotacaoRepository::getInstanceByCodigo(db_utils::fieldsMemory($rsLotacao, 0)->r70_codigo);

    return $oLotacao;
  }


}