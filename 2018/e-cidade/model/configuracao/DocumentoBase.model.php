<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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
define( 'MENSAGENS_DOCUMENTOBASE', 'configuracao.configuracao.DocumentoBase.' );

/**
 * Representa�ao de um documento( caddocumento )
 *
 * Class DocumentoBase
 * @author     F�bio Esteves <fabio.esteves@dbseller.com.br>
 * @package    configuracao
 * @subpackage configuracao
 */
class DocumentoBase {

  /**
   * C�digo sequencial da tabela
   * @var integer|null
   */
  private $iCodigo;

  /**
   * Descri��o do documento
   * @var string
   */
  private $sDescricao;

  /**
   * Tipo de documento( cadtipodocumento )
   * @var integer
   */
  private $iTipoDocumento;

  private $iDocumento = null;

  /**
   * Constantes com os tipos de documentos existentes
   */
  const CGM   = 1;
  const ISSQN = 2;
  const CGS   = 3;

  /**
   * Construtor da classe
   *
   * @param  integer|null $iCodigo
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
  public function __construct( $iCodigo = null ) {

    if( empty( $iCodigo ) ) {
      throw new ParameterException( _M( MENSAGENS_DOCUMENTOBASE . 'codigo_nao_informado' ) );
    }

    $oDaoCadDocumento = new cl_caddocumento();
    $sSqlCadDocumento = $oDaoCadDocumento->sql_query_file( $iCodigo );
    $rsCadDocumento   = db_query( $sSqlCadDocumento );

    if( !is_resource( $rsCadDocumento ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_DOCUMENTOBASE . 'erro_buscar_documento', $oErro ) );
    }

    if( pg_num_rows( $rsCadDocumento ) == 0 ) {

      $oErro          = new stdClass();
      $oErro->iCodigo = $iCodigo;

      throw new BusinessException( _M( MENSAGENS_DOCUMENTOBASE . 'documento_nao_encontrado', $oErro ) );
    }

    $oDadosCadDocumento   = db_utils::fieldsMemory( $rsCadDocumento, 0 );
    $this->iCodigo        = $iCodigo;
    $this->sDescricao     = $oDadosCadDocumento->db44_descricao;
    $this->iTipoDocumento = $oDadosCadDocumento->db44_cadtipodocumento;
  }

  /**
   * Retorna o c�digo sequencial
   * @return int|null
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a descri��o do documento
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Retorna os atributos do documento(caddocumento). Caso seja informado o c�digo do documento(documento), retorna
   * tamb�m os seus valores
   *
   * @return array|stdClass[]
   * @throws DBException
   */
  public function getAtributos() {
    return Documento::getAtributosByCadDocumento( $this->iCodigo );
  }

  /**
   * Retorna o c�digo do documento que possui as respostas dos atributos
   * @return int|null
   */
  public function getDocumento() {
    return $this->iDocumento;
  }

  /**
   * Seta o c�digo do documento que possui os valores dos atributos
   * @param $iDocumento
   */
  public function setDocumento( $iDocumento ) {
    $this->iDocumento = $iDocumento;
  }
}