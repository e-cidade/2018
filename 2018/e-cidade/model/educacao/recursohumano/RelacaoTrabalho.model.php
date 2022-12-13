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
 * Modelo de Relação de Trabalho
 * @package   Educacao
 * @author    André Mello - andre.mello@dbseller.com.br
 */

define("MENSAGEM_RELACAOTRABALHO", "educacao.escola.RelacaoTrabalho.");

class RelacaoTrabalho {

  /**
   * Código da Relação de Trabalho
   * @var integer
   */
  private $iCodigo = null;

  /**
   * Número da Relação de Trabalho
   * @var integer
   */
  private $iNumero = null;

  /**
   * Instância de Profissional da Escola (rechumanoescola)
   * @var ProfissionalEscola
   */
  private $oProfissionalEscola = null;

  /**
   * Código do Regime de Trabalho
   * @var integer
   */
  private $iRegimeTrabalho = null;

  /**
   * Código da Área de Trabalho
   * @var integer
   */
  private $iAreaTrabalho = null;

  /**
   * Instância de Disciplina
   * @var Disciplina
   */
  private $oDisciplina = null;

  /**
   * Instância de Tipo de Hora de Trabalho
   * @var TipoHoraTrabalho
   */
  private $oTipoHoraTrabalho = null;

  /**
   * Controla se a Relação de Trabalho está ativa
   * @var boolean
   */
  private $lAtivo = false;

  /**
   * Descrição do regime de trabalho vinculado a relação
   * @var string
   */
  private $sDescricaoRegimeTrabalho = '';

  /**
   * Construtor da classe. Recebe o codigo como parâmetro, buscando as demais informações referentes as relaçoes
   * de trabalho
   * @param integer $iCodigo
   * @throws DBException
   */
  public function __construct( $iCodigo = null ) {

    if( empty( $iCodigo ) ) {
      return null;
    }

    $oDaoRelacaoTrabalho   = new cl_relacaotrabalho();
    $sWhereRelacaoTrabalho = "ed23_i_codigo = {$iCodigo}";
    $sSqlRelacaoTrabalho   = $oDaoRelacaoTrabalho->sql_query_file(null, "*", null, $sWhereRelacaoTrabalho);
    $rsRelacaoTrabalho     = db_query($sSqlRelacaoTrabalho);

    $oErro = new stdClass();
    if (!$rsRelacaoTrabalho) {

      $oErro->sErro = pg_last_error();
      throw new DBException( _M(MENSAGEM_RELACAOTRABALHO . "erro_buscar_relacao_trabalho", $oErro ) );
    }

    if ( pg_num_rows($rsRelacaoTrabalho) == 0 ) {
      return null;
    }

    $oDadosRelacaoTrabalho      = db_utils::fieldsMemory( $rsRelacaoTrabalho, 0);
    $this->iCodigo              = $iCodigo;
    $this->iNumero              = $oDadosRelacaoTrabalho->ed23_i_numero;
    $this->iRegimeTrabalho      = $oDadosRelacaoTrabalho->ed23_i_regimetrabalho;
    $this->iAreaTrabalho        = $oDadosRelacaoTrabalho->ed23_i_areatrabalho;
    $this->oDisciplina          = DisciplinaRepository::getDisciplinaByCodigo( $oDadosRelacaoTrabalho->ed23_i_disciplina );
    $this->oTipoHoraTrabalho    = TipoHoraTrabalhoRepository::getByCodigo( $oDadosRelacaoTrabalho->ed23_tipohoratrabalho );
    $this->lAtivo               = $oDadosRelacaoTrabalho->ed23_ativo == 't';
    $this->oProfissionalEscola  = ProfissionalEscolaRepository::getByCodigo( $oDadosRelacaoTrabalho->ed23_i_rechumanoescola );
  }

  /**
   * Retorna o código da Relação de Trabalho
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o número da Relação de Trabalho
   * @param integer $iNumero
   */
  public function setNumero( $iNumero ) {
    $this->iNumero = $iNumero;
  }

  /**
   * Retorna o número da Relação de Trabalho
   * @return integer
   */
  public function getNumero() {
    return $this->iNumero;
  }

  /**
   * Retorna uma instância de ProfissionalEscola
   * @return ProfissionalEscola
   */
  public function getProfissionalEscola() {
    return $this->oProfissionalEscola;
  }

  /**
   * Define um ProfissionalEscola
   * @param ProfissionalEscola $oProfissionalEscola
   */
  public function setProfissionalEscola( ProfissionalEscola $oProfissionalEscola ) {
    $this->oProfissionalEscola = $oProfissionalEscola;
  }
  /**
   * Define o código do Regime de Trabalho
   * @param integer $iRegimeTrabalho
   */
  public function setRegimeTrabalho( $iRegimeTrabalho )  {
    $this->iRegimeTrabalho = $iRegimeTrabalho;
  }

  /**
   * Retorna o código do Regime de Trabalho
   * @return integer
   */
  public function getRegimeTrabalho() {
    return $this->iRegimeTrabalho;
  }

  /**
   * Define o código da Área de Trabalho
   * @param integer $iAreaTrabalho
   */
  public function setAreaTrabalho( $iAreaTrabalho ) {
    $this->iAreaTrabalho = $iAreaTrabalho;
  }

  /**
   * Retorna o código da Área de Trabalho
   * @return integer
   */
  public function getAreaTrabalho() {
    return $this->iAreaTrabalho;
  }

  /**
   * Define a Disciplina
   * @param Disciplina $oDisciplina
   */
  public function setDisciplina( Disciplina $oDisciplina ) {
    $this->oDisciplina = $oDisciplina;
  }

  /**
   * Retorna a Disciplina
   * @return Disciplina
   */
  public function getDisciplina() {
    return $this->oDisciplina;
  }

  /**
   * Define o Tipo de Hora de Trabalho
   * @param TipoHoraTrabalho $oTipoHoraTrabalho
   */
  public function setTipoHoraTrabalho( TipoHoraTrabalho $oTipoHoraTrabalho ) {
    $this->oTipoHoraTrabalho = $oTipoHoraTrabalho;
  }

  /**
   * Retorna o Tipo de Hora de Trabalho
   * @return TipoHoraTrabalho
   */
  public function getTipoHoraTrabalho() {
    return $this->oTipoHoraTrabalho;
  }

  /**
   * Define se a Relação de Trabalho está Ativa ou não
   * @param boolean $lAtivo
   */
  public function setAtivo( $lAtivo ) {
    $this->lAtivo = $lAtivo;
  }

  /**
   * Retorna se a Relação de Trabalho está Ativa ou não
   * @return boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }


  /**
   * Inclui ou altera uma Relação de Trabalho de acordo com os dados setados
   */
  public function salvar() {

    if (!db_utils::inTransaction()) {
      throw new DBException( _M(MENSAGEM_RELACAOTRABALHO . "sem_transacao") );
    }

    $oDaoRelacaoTrabalho = new cl_relacaotrabalho();
    $oDaoRelacaoTrabalho->ed23_i_codigo          = $this->iCodigo;
    $oDaoRelacaoTrabalho->ed23_i_rechumanoescola = $this->getProfissionalEscola()->getCodigo();
    $oDaoRelacaoTrabalho->ed23_i_numero          = $this->iNumero;
    $oDaoRelacaoTrabalho->ed23_i_regimetrabalho  = $this->iRegimeTrabalho;
    $oDaoRelacaoTrabalho->ed23_i_areatrabalho    = $this->iAreaTrabalho;
    $oDaoRelacaoTrabalho->ed23_i_disciplina      = $this->oDisciplina->getCodigoDisciplina();
    $oDaoRelacaoTrabalho->ed23_tipohoratrabalho  = $this->oTipoHoraTrabalho->getCodigo();
    $oDaoRelacaoTrabalho->ed23_ativo             = $this->lAtivo ? 'true' : 'false';

    if ( empty( $this->iCodigo ) ) {
      $oDaoRelacaoTrabalho->incluir( null );
    } else {
      $oDaoRelacaoTrabalho->alterar( $this->iCodigo );
    }

    if ( $oDaoRelacaoTrabalho->erro_status == "0" ) {

      $oErro = new stdClass();
      $oErro->sErro = $oDaoRelacaoTrabalho->erro_msg;
      throw new DBException( _M(MENSAGEM_RELACAOTRABALHO . "erro_salvar", $oErro) );
    }

    $this->iCodigo = $oDaoRelacaoTrabalho->ed23_i_codigo;
  }

  /**
   * Retorna a descrição do regime de trabalho vinculado a relação
   * @return string
   * @throws DBException
   */
  public function getDescricaoRegimeTrabalho() {

    if ( !empty($this->sDescricaoRegimeTrabalho) ) {
      return $this->sDescricaoRegimeTrabalho;
    }

    $oDaoRegimeTrabalho   = new cl_regimetrabalho();
    $sWhereRegimeTrabalho = "ed24_i_codigo = {$this->iRegimeTrabalho}";
    $sSqlRegimeTrabalho   = $oDaoRegimeTrabalho->sql_query_file( null, 'ed24_c_descr', null, $sWhereRegimeTrabalho );
    $rsRegimeTrabalho     = db_query( $sSqlRegimeTrabalho );

    if( !$rsRegimeTrabalho ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();
      throw new DBException( _M( MENSAGEM_RELACAOTRABALHO . 'erro_buscar_descricao_regime', $oErro ) );
    }

    if ( pg_num_rows($rsRegimeTrabalho) > 0 ) {
      $this->sDescricaoRegimeTrabalho = db_utils::fieldsMemory( $rsRegimeTrabalho, 0 )->ed24_c_descr;
    }

    return $this->sDescricaoRegimeTrabalho;
  }
}