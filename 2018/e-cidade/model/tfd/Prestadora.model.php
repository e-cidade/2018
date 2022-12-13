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

define( 'MENSAGENS_PRESTADORA_MODEL', 'saude.tfd.Prestadora.' );

/**
 * Classe referente as informações da Prestadora do TFD
 * Class Prestadora
 */
class Prestadora {

  private $iCodigo = null;

  private $oCgm = null;

  private $oDestino = null;

  /**
   * Construtor da classe. Recebe como parâmetro o código sequencial da tabela tfd_prestadora
   *
   * @param null|integer $iCodigo
   * @throws BusinessException
   * @throws DBException
   * @throws Exception
   */
  public function __construct( $iCodigo = null ) {

    if( empty( $iCodigo ) ) {
      return null;
    }

    $oDaoPrestadora    = new cl_tfd_prestadora();
    $sCamposPrestadora = "tf25_i_codigo, tf25_i_cgm, tf25_i_destino, tf03_c_descr";
    $sSqlPrestadora    = $oDaoPrestadora->sql_query( $iCodigo, $sCamposPrestadora );
    $rsPrestadora      = db_query( $sSqlPrestadora );

    if( !is_resource( $rsPrestadora ) ) {

      $oErro        = new stdClass();
      $oErro->sErro = pg_last_error();

      throw new DBException( _M( MENSAGENS_PRESTADORA_MODEL . 'erro_buscar_prestadora', $oErro ) );
    }

    if( pg_num_rows( $rsPrestadora ) == 0 ) {
      throw new BusinessException( _M( MENSAGENS_PRESTADORA_MODEL . 'prestadora_nao_encontrada' ) );
    }

    $oDadosPrestadora = db_utils::fieldsMemory( $rsPrestadora, 0 );
    $this->iCodigo    = $iCodigo;
    $this->oCgm       = CgmFactory::getInstanceByCgm( $oDadosPrestadora->tf25_i_cgm );

    $oDadosDestino             = new stdClass();
    $oDadosDestino->iCodigo    = $oDadosPrestadora->tf25_i_destino;
    $oDadosDestino->sDescricao = $oDadosPrestadora->tf03_c_descr;
    $this->oDestino            = $oDadosDestino;
  }

  /**
   * Retorna o código da Prestadora
   * @return int|null
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna uma instância de CGM
   * @return CgmBase|CgmFisico|CgmJuridico|null
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Retorna um objeto com dados do Destino
   * @return null|stdClass
   */
  public function getDestino() {
    return $this->oDestino;
  }
}