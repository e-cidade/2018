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
 * Etapas de ensino
 * @author Iuri Guntchnigg;
 * @package Educacao;
 */
class Etapa {

  protected $iCodigo;

  protected $oEnsino;

  protected $sNome;

  protected $sNomeAbreviado;

  protected $iOrdem;

  /**
   * Codigo da etapa no censo
   * @var integer
   */
  protected $iEtapaCenso;

  /**
   * Método Construtor
   * @param integer $iEtapa código da etapa
   */
  function __construct($iEtapa = null) {

    if (!empty($iEtapa)) {

      $oDaoEtapa = new cl_serie;
      $sSqlEtapa = $oDaoEtapa->sql_query($iEtapa);
      $rsEtapa   = $oDaoEtapa->sql_record($sSqlEtapa);
      if ($oDaoEtapa->numrows > 0) {

        $oDadosEtapa = db_utils::fieldsMemory($rsEtapa, 0);
        $oEnsino     = EnsinoRepository::getEnsinoByCodigo($oDadosEtapa->ed11_i_ensino);
        $oEnsino->setNome(trim($oDadosEtapa->ed10_c_descr));
        $this->setEnsino($oEnsino);
        $this->setNome($oDadosEtapa->ed11_c_descr);
        $this->setNomeAbreviado($oDadosEtapa->ed11_c_abrev);
        $this->setOrdem($oDadosEtapa->ed11_i_sequencia);
        $this->setEtapaCenso($oDadosEtapa->ed11_i_codcenso);
        $this->iCodigo = $oDadosEtapa->ed11_i_codigo;
        unset($oEtapa);
      }
    }
  }
  /**
   * Retorna o codigo da etapa
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Retorna a ordem da etapa
   * @return integer
   */
  public function getOrdem() {
    return $this->iOrdem;
  }

  /**
   * Define a ordem da Etapa
   * @param integer $iOrdem
   */
  public function setOrdem($iOrdem) {
    $this->iOrdem = $iOrdem;
  }

  /**
   * Retorna o nivel de ensino da etapa
   * @return Ensino
   */
  public function getEnsino() {
    return $this->oEnsino;
  }

  /**
   * Define o nivel de Ensino da Etapa
   * @param Ensino $oEnsino
   */
  public function setEnsino(Ensino $oEnsino) {
    $this->oEnsino = $oEnsino;
  }

  /**
   * Retorna o nome da Etapa
   * @return string
   */
  public function getNome() {
    return $this->sNome;
  }

  /**
   * Define o nome da Etapa
   * @param string $sNome
   */
  public function setNome($sNome) {

    $this->sNome = $sNome;
  }

  /**
   * Retorna o nome abreviado da etapa
   * @return string
   */
  public function getNomeAbreviado() {
    return $this->sNomeAbreviado;
  }

  /**
   * Define o nome abreviado da etapa
   * @param string $sNomeAbreviado nome abreviado da etapa
   */
  public function setNomeAbreviado($sNomeAbreviado) {
    $this->sNomeAbreviado = $sNomeAbreviado;
  }

  /**
   * Retorna o codigo da etapa no censo
   * @return integer
   */
  public function getEtapaCenso() {
    return $this->iEtapaCenso;
  }

  /**
   * Seta o codigo da etapa no censo
   * @param integer $iEtapaCenso
   */
  public function setEtapaCenso($iEtapaCenso) {
    $this->iEtapaCenso = $iEtapaCenso;
  }

  /**
   * Busca as etapas equivalentes de uma Etapa
   * @throws DBException
   * @return Etapa[]
   */
  public function buscaEtapaEquivalente() {

    $sCampos = " serieequiv.ed234_i_serieequiv ";
    $sWhere  = " ed234_i_serie = {$this->getCodigo()} ";

    $oDaoSerieEquiv  = new cl_serieequiv();
    $sSqlEquivalente = $oDaoSerieEquiv->sql_query( null, $sCampos, null, $sWhere );
    $rsEquivalente   = db_query( $sSqlEquivalente );

    if ( !$rsEquivalente ) {
      throw new DBException( "Error ao executar query: Não foi possível buscar equivalências" );
    }

    $iLinhas             = pg_num_rows( $rsEquivalente );
    $aSeriesEquivalentes = array();

    for ($i = 0; $i < $iLinhas; $i++) {
      $aSeriesEquivalentes[] = EtapaRepository::getEtapaByCodigo(db_utils::fieldsMemory($rsEquivalente, $i)->ed234_i_serieequiv);
    }

    return $aSeriesEquivalentes;
  }
}