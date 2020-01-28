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
 * Classe que controla os recibos gerados das CDA's para os cartórios extrajudiciais
 *
 * @package divida
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class CertidCartorioReciboPaga {

  /**
   * Sequencial
   * @var null
   */
  private $iSequencial = null;

  /**
   * Código do vínculo entre a certidão e o cartório
   * @var CartidCartorio
   */
  private $oCartidCartorio = null;

  /**
   * Numnov do ReciboPaga
   * @var integer
   */
  private $iNumnov = null;

  /**
   * Método Construtor
   * @param integer $iSequencial
   */
  public function __construct( $iSequencial = null ) {

    $oDaoCertidCartorioReciboPaga = new cl_certidcartoriorecibopaga;
    $rsCertidCartorioReciboPaga   = null;

    if ( !is_null($iSequencial) ) {

      $sSqlCertidCartorioReciboPaga = $oDaoCertidCartorioReciboPaga->sql_query_file( $iSequencial );
      $rsCertidCartorioReciboPaga   = $oDaoCertidCartorioReciboPaga->sql_record( $sSqlCertidCartorioReciboPaga );
    }

    if ( !empty( $rsCertidCartorioReciboPaga ) ) {

      $oCertidCartorioReciboPaga = db_utils::fieldsMemory( $rsCertidCartorioReciboPaga, 0 );

      $this->iSequencial     = $oCertidCartorioReciboPaga->v33_sequencial;
      $this->oCartidCartorio = $oCertidCartorioReciboPaga->v33_certidcartorio;
      $this->iNumnov         = $oCertidCartorioReciboPaga->v33_numnov;
    }
  }

 /**
  * Incluimos um novo certidcartoriorecibopaga
  *
  * @param  integer $iSequencial
  */
  public function incluir($iSequencial = null) {

    try {

      $oDaoCertidCartorioReciboPaga = new cl_certidcartoriorecibopaga;
      $oDaoCertidCartorioReciboPaga->v33_certidcartorio = $this->oCartidCartorio->getSequencial();
      $oDaoCertidCartorioReciboPaga->v33_numnov         = $this->iNumnov;
      $oDaoCertidCartorioReciboPaga->incluir($iSequencial);

      $this->iSequencial = $oDaoCertidCartorioReciboPaga->v33_sequencial;
    } catch (Exception $oErro) {
      throw new DBException( $oErro->getMessage() );
    }
  }

  /**
   * Busca o Sequencial
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Busca o código do vínculo entre a certidão e o cartório
   * @return integer
   */
  public function getCertidCartorio() {
    return $this->oCartidCartorio;
  }

  /**
   * Altera o código do vínculo entre a certidão e o cartório
   * @param CartidCartorio $oCartidCartorio
   */
  public function setCertidCartorio( CertidCartorio $oCartidCartorio ) {
    $this->oCartidCartorio = $oCartidCartorio;
  }

  /**
   * Buscar o numnov do recibopaga
   * @return integer
   */
  public function getNumnov() {
    return $this->iNumnov;
  }

  /**
   * Altera o numnov do recibopaga
   *
   * @param integer $iNumnov
   */
  public function setNumnov( $iNumnov ) {
    $this->iNumnov = $iNumnov;
  }
}