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
define( 'MENSAGENS_DDOCUMENTOBASE_REPOSITORY', 'configuracao.configuracao.DocumentoBaseRepository.' );

/**
 * Repository para DocumentoBase
 * @author     Fábio Esteves <fabio.esteves@dbseller.com.br>
 * @package    configuracao
 * @subpackage configuracao
 */
class DocumentoBaseRepository {
	
  private $aDocumentoBase = array();
  private static $oInstance;
  
  private function __construct() {}
  
  private function __clone() {}
  
  /**
   * Retorna a instância do Repositorio
   * @return DocumentoBaseRepository
   */
  protected static function getInstance() {
  	
    if (self::$oInstance == null) {
    	self::$oInstance = new DocumentoBaseRepository();
    }
    return self::$oInstance;
  }
  
  /**
   * Retorna a instância de DocumentoBase, adicionando a mesma ao repositório, caso não exista
   * @param integer $iCodigo
   * @return DocumentoBase
   */
  public static function getDocumentoBaseByCodigo($iCodigo) {
  	
    if (!array_key_exists($iCodigo, DocumentoBaseRepository::getInstance()->aDocumentoBase)) {
      DocumentoBaseRepository::getInstance()->aDocumentoBase[$iCodigo] = new DocumentoBase($iCodigo);
    }

    return DocumentoBaseRepository::getInstance()->aDocumentoBase[$iCodigo];
  }
  
  /**
   * Remove uma instância de DocumentoBase do repository
   * @param DocumentoBase $oDocumentoBase
   * @return boolean
   */
  public static function removerDocumentoBase(DocumentoBase $oDocumentoBase) {
  	
    if ( array_key_exists($oDocumentoBase->getCodigo(), DocumentoBaseRepository::getInstance()->aDocumentoBase) ) {
    	unset(DocumentoBaseRepository::getInstance()->aDocumentoBase[$oDocumentoBase->getCodigo()]);
    }

    return true;
  }

  public static function getAllDocumentoBase() {

    $oDaoCadDocumento   = new cl_caddocumento();
    $sWhereCadDocumento = "db44_cadtipodocumento = " . DocumentoBase::CGS;
    $sSqlCadDocumento   = $oDaoCadDocumento->sql_query_file( null, 'db44_sequencial', null, $sWhereCadDocumento );
    $rsCadDocumento     = db_query( $sSqlCadDocumento );

    if( !is_resource( $rsCadDocumento ) ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_DDOCUMENTOBASE_REPOSITORY . 'erro_buscar_caddocumento', $oMensagem ) );
    }

    $iLinhasCadDocumento = pg_num_rows( $rsCadDocumento );

    if( $iLinhasCadDocumento == 0 ) {
      return null;
    }

    $aCadDocumentos = array();

    for( $iContador = 0; $iContador < $iLinhasCadDocumento; $iContador++ ) {

      $iCadDocumento    = db_utils::fieldsMemory( $rsCadDocumento, $iContador )->db44_sequencial;
      $aCadDocumentos[] = DocumentoBaseRepository::getDocumentoBaseByCodigo( $iCadDocumento );
    }

    return $aCadDocumentos;
  }

  public static function getDocumentosBaseCgsDocumento( Cgs $oCgs ) {

    $oDaoCgsValor    = new cl_caddocumento();
    $sWhereCgsValor  = "     sd108_cgs_und         = {$oCgs->getCodigo()}";
    $sWhereCgsValor .= " AND db44_cadtipodocumento = " . DocumentoBase::CGS;
    $sCamposCgsValor = "distinct db44_sequencial, sd108_documento";
    $sSqlCgsValor    = $oDaoCgsValor->sqlAtributosValor( null, $sCamposCgsValor, null, $sWhereCgsValor );
    $rsCgsValor      = db_query( $sSqlCgsValor );

    if( !is_resource( $rsCgsValor ) ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_DDOCUMENTOBASE_REPOSITORY . 'erro_buscar_valor_cgs', $oMensagem ) );
    }

    DocumentoBaseRepository::getAllDocumentoBase();
    $iLinhasCgsValor = pg_num_rows( $rsCgsValor );

    for( $iContador = 0; $iContador < $iLinhasCgsValor; $iContador++ ) {

      $oDadosCgsValor = db_utils::fieldsMemory( $rsCgsValor, $iContador );
      DocumentoBaseRepository::getInstance()->aDocumentoBase[ $oDadosCgsValor->db44_sequencial ]
                                            ->setDocumento( $oDadosCgsValor->sd108_documento );
    }

    return DocumentoBaseRepository::getAllDocumentoBase();
  }
}
