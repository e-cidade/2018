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

define( "MENSAGENS_CID_MODEL", "saude.laboratorio.CID." );

/**
 * Classe referente aos campos e ações do CID (sau_cid)
 * @package laboratorio
 * @author  Fábio Esteves <fabio.esteves@dbseller.com.br>
 */
class CID {

  /**
   * Código do CID
   * @var integer
   */
  private $iCodigo;

  /**
   * Descrição do CID
   * @var string
   */
  private $sNome;

  /**
   * Código de identificação do CID
   * @var string
   */
  private $sCID;

  /**
   * Construtor da classe. Recebe o sequencial da tabela
   * @param integer $iCodigo
   */
  public function __construct( $iCodigo = null ) {

    if ( !empty( $iCodigo ) ) {

      $oDaoCID = new cl_sau_cid();
      $sSqlCID = $oDaoCID->sql_query_file( $iCodigo );
      $rsCID   = db_query( $sSqlCID );

      if ( !$rsCID ) {

        $oMensagem        = new stdClass();
        $oMensagem->sErro = pg_result_error( $oMensagem );
        throw new DBException( _M( MENSAGENS_CID_MODEL . "erro_buscar_cid" ) );
      }

      if ( pg_num_rows( $rsCID ) == 0 ) {

        $oMensagem          = new stdClass();
        $oMensagem->iCodigo = $iCodigo;
        throw new DBException( _M( MENSAGENS_CID_MODEL . "codigo_nao_encontrado" ) );
      }

      $oDadosCID     = db_utils::fieldsMemory( $rsCID, 0 );
      $this->iCodigo = $oDadosCID->sd70_i_codigo;
      $this->sNome   = $oDadosCID->sd70_c_nome;
      $this->sCID    = $oDadosCID->sd70_c_cid;
    }
  }

  /**
   * Retorna o código do CID
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a nome do CID
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Seta o nome do CID
   * @param string $sNome
   */
  public function setNome( $sNome ) {
    $this->sNome = $sNome;
  }

  /**
   * Retorna o código de identificação do CID
   * @return string
   */
  public function getCID() {
    return $this->sCID;
  }

  /**
   * Seta o código de identificação do CID
   * @param string $sCID
   */
  public function setCID( $sCID ) {
    $this->sCID = $sCID;
  }
}
?>