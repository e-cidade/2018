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
 * Classe que controla o vínculo entre certidões e cartórios
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package divida
 */
class CertidCartorio {

  /**
   * Código sequencial
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Certidão
   * @var Certidao
   */
  private $oCertidao = null;

  /**
   * Cartório
   * @var Cartorio
   */
  private $oCartorio = null;

  /**
   * Método contrutor
   * @var integer
   */
  public function __construct( $iSequencial = null, $iCertidao = null, $iCartorio = null ) {

    $oDaoCertidCartorio = new cl_certidcartorio;
    $rsCertidCartorio   = null;

    if ( !is_null($iSequencial) ) {

      $sSqlCertidCartorio = $oDaoCertidCartorio->sql_query_file($iSequencial);
      $rsCertidCartorio   = $oDaoCertidCartorio->sql_record($sSqlCertidCartorio);
    }

    if ( is_null($iSequencial) ) {

      $sWhere = "";
      if ( !is_null($iCertidao) ) {
        $sWhere .= "v31_certid = {$iCertidao}";
      }

      if ( !is_null($iCartorio) ) {

        if ( !empty($sWhere) ) {
          $sWhere .= " and ";
        }

        $sWhere .= "v31_cartorio = {$iCartorio}";
      }

      $sSqlCertidCartorio = $oDaoCertidCartorio->sql_query_file(null , "*", "v31_sequencial desc", $sWhere);
      $rsCertidCartorio   = $oDaoCertidCartorio->sql_record($sSqlCertidCartorio);
    }

    if ( !empty($rsCertidCartorio) ) {

      $oCertidCartorio = db_utils::fieldsMemory($rsCertidCartorio, 0);

      $this->iSequencial = $oCertidCartorio->v31_sequencial;
      $this->oCertidao   = new Certidao( $oCertidCartorio->v31_certid );
      $this->oCartorio   = new Cartorio( $oCertidCartorio->v31_cartorio );
    }
  }

  /**
   * Incluimos um novo certidcartorio
   *
   * @param  integer $iSequencial
   */
  public function incluir($iSequencial = null) {

    try{

      $oDaoCertidCartorio = new cl_certidcartorio;
      $oDaoCertidCartorio->v31_certid   = $this->oCertidao->getSequencial();
      $oDaoCertidCartorio->v31_cartorio = $this->oCartorio->getSequencial();
      $oDaoCertidCartorio->incluir($iSequencial);

      $this->iSequencial = $oDaoCertidCartorio->v31_sequencial;
    } catch (Exception $oErro) {
      throw new DBException( $oErro->getMessage() );
    }
  }

  /**
   * Buscamos Recibo
   *
   * @param  string $sCampos
   * @return array
   */
  public function buscaRecibo($sCampos = "*") {

    $oDaoCertidCartorio = new cl_certidcartorio;
    $sSqlCertidCartorio = $oDaoCertidCartorio->sql_query_recibo($this->iSequencial, $sCampos);
    $rsCertidCartorio   = $oDaoCertidCartorio->sql_record($sSqlCertidCartorio);

    if ( !empty($rsCertidCartorio) ) {

      $oCertidCartorio = db_utils::fieldsMemory($rsCertidCartorio, 0);

      if ( $oCertidCartorio->v32_tipo == CertidMovimentacao::TIPO_MOVIMENTACAO_RESGATADO ) {
        return false;
      }

      return $oCertidCartorio;
    }

    return false;
  }

  /**
   * Busca sequencial
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Busca Certidão
   * @return Certidao
   */
  public function getCertidao() {
    return $this->oCertidao;
  }

  /**
   * Altera Certidão
   * @param Certidao $oCertidao
   */
  public function setCertidao( Certidao $oCertidao ) {
    $this->oCertidao = $oCertidao;
  }

  /**
   * Busca Cartório
   * @return Cartorio
   */
  public function getCartorio() {
    return $this->oCartorio;
  }

  /**
   * Altera Cartório
   * @param Cartorio $oCartorio
   */
  public function setCartorio( Cartorio $oCartorio ) {
    $this->oCartorio = $oCartorio;
  }
}