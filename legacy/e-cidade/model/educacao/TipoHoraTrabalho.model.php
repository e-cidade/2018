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
 * @package educacao
 * @version $Revision: 1.4 $
 */
class TipoHoraTrabalho {

  const MENSAGENS_TIPOHORATRABALHO = 'educacao.escola.TipoHoraTrabalho.';
  /**
   * Constantes referentes aos tipos de efetividades padrão
   */
  const EFETIVIDADE_AMBOS       = 1;
  const EFETIVIDADE_PROFESSOR   = 2;
  const EFETIVIDADE_FUNCIONARIO = 3;

  /**
   * Código de tipohoratrabalho
   * @var integer
   */
  private $iCodigo;

  /**
   * Descrição do tipo de hora de trabalho
   * @var string
   */
  private $sDescricao;

  /**
   * Abreviatura referente ao tipo de hora de trabalho
   * @var string
   */
  private $sAbreviatura;

  /**
   * Tipo de efetividade permitido para o tipo de hora
   * @var integer
   */
  private $iEfetividade;

  /**
   * Controla se o tipo de hora esta ativo
   * @var boolean
   */
  private $lAtivo;

  /**
   * Construtor da classe. Recebe o codigo como parâmetro, buscando as demais informações referentes ao tipo de hora
   * de trabalho
   * @param integer $iCodigo
   * @throws DBException
   */
  public function __construct( $iCodigo = null ) {

    if( !empty( $iCodigo ) ) {

      $oDaoTipoHoraTrabalho = new cl_tipohoratrabalho();
      $sSqlTipoHoraTrabalho = $oDaoTipoHoraTrabalho->sql_query_file( $iCodigo );
      $rsTipoHoraTrabalho   = db_query( $sSqlTipoHoraTrabalho );

      if( !$rsTipoHoraTrabalho ) {

        $oErro        = new stdClass();
        $oErro->sErro = pg_last_error();
        throw new DBException( _M( self::MENSAGENS_TIPOHORATRABALHO . 'erro_buscar_tipo_hora', $oErro ) );
      }

      $oDadosRetorno = db_utils::fieldsMemory( $rsTipoHoraTrabalho, 0 );

      $this->iCodigo      = $iCodigo;
      $this->sDescricao   = $oDadosRetorno->ed128_descricao;
      $this->sAbreviatura = $oDadosRetorno->ed128_abreviatura;
      $this->iEfetividade = $oDadosRetorno->ed128_tipoefetividade;
      $this->lAtivo       = $oDadosRetorno->ed128_ativo == 't';
    }
  }

  /**
   * Retorna o código do tipo de hora de trabalho
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna o código da efetividade
   * @return int
   */
  public function getEfetividade() {
    return $this->iEfetividade;
  }

  /**
   * Seta o tipo de efetividade
   * @param int $iEfetividade
   */
  public function setEfetividade( $iEfetividade ) {
    $this->iEfetividade = $iEfetividade;
  }

  /**
   * Retorna a abreviatura
   * @return string
   */
  public function getAbreviatura() {
    return $this->sAbreviatura;
  }

  /**
   * Seta a abreviatura
   * @param string $sAbreviatura
   */
  public function setAbreviatura( $sAbreviatura ) {
    $this->sAbreviatura = $sAbreviatura;
  }

  /**
   * Retorna a descrição
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Seta a descrição
   * @param string $sDescricao
   */
  public function setDescricao( $sDescricao ) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Retorna se o tipo de hora de trabalho esta ativo
   * @return boolean
   */
  public function isAtivo() {
    return $this->lAtivo;
  }

  /**
   * Seta se o tipo de hora de trabalho está ativo
   * @param boolean $lAtivo
   */
  public function setAtivo( $lAtivo ) {
    $this->lAtivo = $lAtivo;
  }

  /**
   * Salva as informações referentes a um tipo de hora de trabalho
   * @throws DBException
   */
  public function salvar() {

    $oDaoTipoHoraTrabalho                        = new cl_tipohoratrabalho();
    $oDaoTipoHoraTrabalho->ed128_descricao       = $this->sDescricao;
    $oDaoTipoHoraTrabalho->ed128_abreviatura     = $this->sAbreviatura;
    $oDaoTipoHoraTrabalho->ed128_tipoefetividade = $this->iEfetividade;
    $oDaoTipoHoraTrabalho->ed128_ativo           = $this->lAtivo ? 'true' : 'false';

    if( !empty( $this->iCodigo ) ) {

      $oDaoTipoHoraTrabalho->ed128_codigo = $this->iCodigo;
      $oDaoTipoHoraTrabalho->alterar( $this->iCodigo );
    } else {
      $oDaoTipoHoraTrabalho->incluir( null );
    }

    if( $oDaoTipoHoraTrabalho->erro_status == '0' ) {

      $oErro        = new stdClass();
      $oErro->sErro =  $oDaoTipoHoraTrabalho->erro_msg;
      throw new DBException( _M( self::MENSAGENS_TIPOHORATRABALHO . 'erro_salvar_tipo_hora', $oErro ) );
    }
  }
}