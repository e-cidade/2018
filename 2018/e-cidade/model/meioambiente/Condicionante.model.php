<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBseller Servicos de Informatica
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
 * Condicionante para Parecer Técnico
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package meioambiente
 */
class Condicionante {

  /**
   * Define constante com o caminho do arquivo de mensagens
   */
  const ARQUIVO_MENSAGEM = 'tributario.meioambiente.Condicionante.';

  /**
   * Código sequencial
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Descrição da Condicionante
   * @var string
   */
  private $sDescricao = null;

  /**
   * Variável que define se esta condicionante é padrão
   * @var boolean
   */
  private $lPadrao = false;

  /**
   * Variável que define se esta condicionante é vinculada a todas as atividades
   * @var boolean
   */
  private $lVinculaTodasAtividades = false;

  /**
   * Método construtor
   *
   * @param  integer $iSequencial
   * @access public
   */
  public function __construct( $iSequencial = null ) {

    $oDaoCondicionante = new cl_condicionante();
    $rsCondicionante   = null;

    if ( !empty($iSequencial) ) {

      $sSql            = $oDaoCondicionante->sql_query($iSequencial);
      $rsCondicionante = $oDaoCondicionante->sql_record($sSql);
    }

    if ( !empty($rsCondicionante) ) {

      $oDados = db_utils::fieldsMemory($rsCondicionante, 0);

      $this->iSequencial             = $oDados->am10_sequencial;
      $this->sDescricao              = $oDados->am10_descricao;
      $this->lPadrao                 = $oDados->am10_padrao;
      $this->lVinculaTodasAtividades = $oDados->am10_vinculatodasatividades;
    }
  }

  /**
   * Busca o sequencial da Condicionante
   *
   * @return integer $iSequencial
   * @access public
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Busca a descrição da Condicionante
   *
   * @return string $sDescricao
   * @access public
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Altera a descrição da Condicionante
   *
   * @param  string $sDescricao
   * @access public
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Busca o padrao da Condicionante
   *
   * @return boolean $lPadrao
   * @access public
   */
  public function getPadrao() {
    return $this->lPadrao;
  }

  /**
   * Altera o padrao da Condicionante
   *
   * @param  boolean $lPadrao
   * @access public
   */
  public function setPadrao($lPadrao) {
    $this->lPadrao = $lPadrao;
  }

  /**
   * Get am10_vinculatodasatividades
   *
   * @return boolean $lVinculaTodasAtividades
   * @access public
   */
  public function getVinculaTodasAtividades() {
    return $this->lVinculaTodasAtividades;
  }

  /**
   * Set am10_vinculatodasatividades
   *
   * @param  boolean $lVinculaTodasAtividades
   * @access public
   */
  public function setVinculaTodasAtividades( $lVinculaTodasAtividades ) {
    $this->lVinculaTodasAtividades = $lVinculaTodasAtividades;
  }

  /**
   * Inclui os dados da Condicionante
   *
   * @access public
   * @throws Exception
   */
  public function incluir() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( self::ARQUIVO_MENSAGEM . "sem_transacao_ativa" ) );
    }

    if (empty($this->sDescricao)) {
      throw new Exception( _M( self::ARQUIVO_MENSAGEM . "descricao_obrigatorio") );
    }

    if (empty($this->lPadrao)) {
      throw new Exception( _M( self::ARQUIVO_MENSAGEM . "padrao_obrigatorio") );
    }

    $oDaoCondicionante = new cl_condicionante();
    $oDaoCondicionante->am10_descricao              = $this->sDescricao;
    $oDaoCondicionante->am10_padrao                 = $this->lPadrao;
    $oDaoCondicionante->am10_vinculatodasatividades = $this->lVinculaTodasAtividades;
    $oDaoCondicionante->incluir();

    if ($oDaoCondicionante->erro_status == 0) {
      throw new Exception( $oDaoCondicionante->erro_msg );
    }

    $this->iSequencial = $oDaoCondicionante->am10_sequencial;
  }

  /**
   * Exclui a Condicionante
   *
   * @access public
   * @throws DBException
   * @throws BusinessException
   */
  public function excluir() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( self::ARQUIVO_MENSAGEM . "sem_transacao_ativa" ) );
    }

    /**
     * Excluimos os vinculos com pareceres
     */
    $oDaoParecerTecnicoCondicionante = new cl_parecertecnicocondicionante();
    $sWhere                          = "am12_condicionante = {$this->getSequencial()}";
    $oDaoParecerTecnicoCondicionante->excluir(null, $sWhere);

    /**
     * Excluimos as atividades vinculadas a condicionante
     */
    CondicionanteAtividadeImpacto::excluir( $this->getSequencial() );

    /**
     * Excluimos os Tipos de Licença vinculados a condicionante e incluimos novamente
     */
    CondicionanteTipoLicenca::excluirVinculoCondicionante( $this->getSequencial() );

    /**
     * Excluimos a condicionante
     */
    $oDaoCondicionante = new cl_condicionante();
    $oDaoCondicionante->excluir( $this->getSequencial() );
    if ( $oDaoCondicionante->erro_status == 0 ) {
      throw new BusinessException( _M( self::ARQUIVO_MENSAGEM . "erro_remover_condicionante" ) );
    }
  }

  /**
   * Altera os dados da Condicionante
   *
   * @access public
   * @throws Exception
   */
  public function alterar() {

    if ( !db_utils::inTransaction() ) {
      throw new DBException( _M( self::ARQUIVO_MENSAGEM . "sem_transacao_ativa" ) );
    }

    if (empty($this->sDescricao)) {
      throw new Exception( _M( MENSAGENS . 'descricao_obrigatorio') );
    }

    if (empty($this->lPadrao)) {
      throw new Exception( _M( MENSAGENS . 'padrao_obrigatorio') );
    }

    $oDaoCondicionante = new cl_condicionante();
    $oDaoCondicionante->am10_sequencial             = $this->iSequencial;
    $oDaoCondicionante->am10_descricao              = $this->sDescricao;
    $oDaoCondicionante->am10_padrao                 = $this->lPadrao;
    $oDaoCondicionante->am10_vinculatodasatividades = $this->lVinculaTodasAtividades;
    $oDaoCondicionante->alterar($this->iSequencial);

    if ($oDaoCondicionante->erro_status == 0) {
      throw new Exception($oDaoCondicionante->erro_msg);
    }
  }

  /**
   * Buscamos as ativadades vinculadas à Condicionante
   *
   * @return array
   * @throws Exception
   */
  public function getAtividades() {

    try {

      $oDaoCondicionanteAtividadeImpacto = new cl_condicionanteatividadeimpacto();
      $sSql                              = $oDaoCondicionanteAtividadeImpacto->sql_query_atividades($this->iSequencial);
      $rsCondicionanteAtividadeImpacto   = $oDaoCondicionanteAtividadeImpacto->sql_record($sSql);
      $aAtividadeImpacto                 = db_utils::getCollectionByRecord($rsCondicionanteAtividadeImpacto);

      return $aAtividadeImpacto;
    } catch (Exception $oError) {
      throw $oError;
    }
  }

  /**
   * Buscamos os tipos de licença vinculados à Condicionante
   *
   * @return array
   * @throws Exception
   */
  public function getTipoLicenca() {

    try {

      $sWhere = "am17_condicionante = {$this->iSequencial}";
      $oDaoCondicionanteTipoLicenca = new cl_condicionantetipolicenca();
      $sSqlTipoLicenca              = $oDaoCondicionanteTipoLicenca->sql_query_file( null,
                                                                                     "am17_tipolicenca",
                                                                                     null,
                                                                                     $sWhere);
      $rsTipoLicenca = $oDaoCondicionanteTipoLicenca->sql_record($sSqlTipoLicenca);
      $aTipoLicenca  = db_utils::getCollectionByRecord( $rsTipoLicenca );

      return $aTipoLicenca;

    } catch ( Exception $oErro ) {
      throw $oError;
    }
  }
}