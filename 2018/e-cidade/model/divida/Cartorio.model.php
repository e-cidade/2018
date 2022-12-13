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
 * Classe de controle dos cart�rios
 *
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 * @package meioambiente
 */
class Cartorio {

  /**
   * Sequencial do cart�rio
   * @var integer
   */
  private $iSequencial = null;

  /**
   * Descri��o
   * @var string
   */
  private $sDescricao = null;

  /**
   * CGM
   * @var CgmBase
   */
  private $oCgm = null;

  /**
   * Observa��es
   * @var string
   */
  private $sObservacoes = null;

  /**
   * Vari�vel que define se o cart�rio � extrajudcial ou n�o
   * @var boolean
   */
  private $lExtrajudicial = null;

  /**
   * M�todo construtor
   * @param integer $iSequencial
   */
  public function __construct( $iSequencial = null ) {

    $oDaoCartorio = new cl_cartorio;
    $rsCartorio   = null;

    if ( !is_null($iSequencial) ) {

      $sSqlCartorio = $oDaoCartorio->sql_query_file($iSequencial);
      $rsCartorio   = $oDaoCartorio->sql_record($sSqlCartorio);
    }

    if ( !empty($rsCartorio) ) {

      $oCartorio = db_utils::fieldsMemory($rsCartorio, 0);

      $this->iSequencial    = $oCartorio->v82_sequencial;
      $this->sDescricao     = $oCartorio->v82_descricao;
      $this->oCgm           = CgmFactory::getInstanceByCgm( $oCartorio->v82_numcgm );
      $this->sObservacoes   = $oCartorio->v82_obs;
      $this->lExtrajudicial = $oCartorio->v82_extrajudicial;
    }
  }

  /**
   * Busca c�digo do cart�rio
   * @return integer
   */
  public function getSequencial() {
    return $this->iSequencial;
  }

  /**
   * Busca a descri��o
   * @return string
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * Altera a Descri��o
   * @param srting $sDescricao
   */
  public function setDescricao( $sDescricao ) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * Busca o CGM
   * @return CgmBase
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Altera o CGM
   * @param CgmBase $oCgm
   */
  public function setCgm( $oCgm ) {
    return $this->oCgm;
  }

  /**
   * Busca as observa��es
   * @return string
   */
  public function getObservacoes() {
    return $this->sObservacoes;
  }

  /**
   * Altera as observa��es
   * @param string $sObservacoes
   */
  public function setObservacoes( $sObservacoes ) {
    $this->sObservacoes = $sObservacoes;
  }

  /**
   * Verica se � extrajudicial
   * @return boolean
   */
  public function getExtrajudicial() {
    return $this->lExtrajudicial;
  }

  /**
   * Altera o tipo extrajudicial do cart�rio
   * @param boolean $lExtrajudicial
   */
  public function setExtrajudicial( $lExtrajudicial ) {
    $this->lExtrajudicial = $lExtrajudicial;
  }
}