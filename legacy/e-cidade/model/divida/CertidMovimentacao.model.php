<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2015  DBSeller Servicos de Informatica
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
 * Classe que contrala as movimentações das Certidões
 *
 * @author  Roberto Carneiro <roberto@dbseller.com.br>
 * @package divida
 */
class CertidMovimentacao {

  /**
   * Constante para definir o tipo de movimentação (ENVIADO)
   */
  const TIPO_MOVIMENTACAO_ENVIADO    = 1;

  /**
   * Constante para definir o tipo de movimentação (PROTESTADO)
   */
  const TIPO_MOVIMENTACAO_PROTESTADO = 2;

  /**
   * Constante para definir o tipo de movimentação (RESGATADO)
   */
  const TIPO_MOVIMENTACAO_RESGATADO  = 3;

  /**
   * Sequencial
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Vínculo entre Certidão e Cartório
   * @var CertidCartorio
   */
  private $oCertidCartorio = null;

  /**
   * Data de Movimentação
   * @var DBDate
   */
  private $oDataMovimentacao = null;

  /**
   * Tipo de movimentação da Certidão
   * @var integer
   */
  private $iTipo = null;

  /**
   * Método construtor
   * @param integer $iSequencial
   */
  public function __construct( $iSequencial = null ) {

    $oDaoCertidMovimentacao = new cl_certidmovimentacao;
    $rsCertidMovimentacao   = null;

    if ( !is_null($iSequencial) ) {

      $sSqlCertidMovimentacao = $oDaoCertidMovimentacao->sql_query_file( $iSequencial );
      $rsCertidMovimentacao   = $oDaoCertidMovimentacao->sql_record($sSqlCertidMovimentacao);
    }

    if ( !empty($rsCertidMovimentacao) ) {

      $oCertidMovimentacao = db_utils::fieldsMemory( $rsCertidMovimentacao, 0 );

      $this->iSequencial       = $oCertidMovimentacao->v32_sequencial;
      $this->oCertidCartorio   = new CertidCartorio( $oCertidMovimentacao->v32_certidcartorio );
      $this->oDataMovimentacao = new DBDate( $oCertidMovimentacao->v32_datamovimentacao );
      $this->iTipo             = $oCertidMovimentacao->v32_tipo;
    }
  }

  /**
   * Incluimos um novo certidmovimentacao
   *
   * @param  integer $iSequencial
   */
  public function incluir($iSequencial = null) {

    try {

      $oDaoCertidMovimentacao                       = new cl_certidmovimentacao;
      $oDaoCertidMovimentacao->v32_certidcartorio   = $this->oCertidCartorio->getSequencial();
      $oDaoCertidMovimentacao->v32_datamovimentacao = $this->oDataMovimentacao->getDate();
      $oDaoCertidMovimentacao->v32_tipo             = $this->iTipo;
      $oDaoCertidMovimentacao->incluir($iSequencial);

      $this->iSequencial = $oDaoCertidMovimentacao->v32_sequencial;
    } catch (Exception $oErro) {
      throw new DBException( $oErro->getMessage() );
    }
  }

  /**
   * Busca o sequencial
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Busca o vínculo entre a Certidão e Cartório
   * @return integer
   */
  public function getCertidCartorio() {
    return $this->oCertidCartorio;
  }

  /**
   * Alterar o vínculo entre a Certidão e Cartório
   * @param CertidCartorio $oCertidCartorio
   */
  public function setCertidCartorio( CertidCartorio $oCertidCartorio ) {
    $this->oCertidCartorio = $oCertidCartorio;
  }

  /**
   * Busca a Data de Movimentação
   * @return DBDate
   */
  public function getDataMovimentacao() {
    return $this->oDataMovimentacao;
  }

  /**
   * Altera a Data de Movimentação
   * @param DBDate $oDataMovimentacao
   */
  public function setDataMovimentacao(  DBDate $oDataMovimentacao ) {
    $this->oDataMovimentacao = $oDataMovimentacao;
  }

  /**
   * Busca o tipo da movimentação
   * @return integer
   */
  public function getTipo() {
    return $this->iTipo;
  }

  /**
   * Altera o tipo de movimentacao
   * @param integer $iTipo
   */
  public function setTipo( $iTipo ) {
    $this->iTipo = $iTipo;
  }
}