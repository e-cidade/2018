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
 * Classe que representa uma lotacao de um usu�rio
 *
 * @package folha
 * @author  Renan Silva  <renan.silva@dbseller.com.br>
 */

class Lotacao extends DBEstrutura{

  /**
   * C�digo da lota��o
   *
   * @var iCodigoLotacao
   * @access private
   */
  private $iCodigoLotacao;

  /**
   * Usu�rios
   *
   * @var aUsuariosPermissaoLotacao
   * @access private
   */
  private $aUsuariosPermissaoLotacao;

  /**
   * Descri��o da lota��o
   *
   * @var $sDescricao
   * @access private
   */
  private $sDescricaoLotacao;

  /**
   * Institui��o
   *
   * @var oInstituicao
   * @access private
   */
  private $oInstituicao;

  /**
   * Caracter�stica peculiar
   *
   * @var oCarPeculiar
   * @access private
   */
  private $oCarPeculiar;

  /**
   * Informa se � anal�tica
   *
   * @var lAnalitica
   * @access private
   */
  private $lAnalitica;

  /**
   * Informa se est� ativa a lota��o
   *
   * @var lAtiva
   * @access private
   */
  private $lAtiva;

  /**
   * O estrutural em si
   *
   * @var sEstrutural
   * @access private
   */
  private $sEstrutural;

  /**
   * O CGM da lota��o
   *
   * @var oCgm
   * @access private
   */
  private $oCgm;

  /**
   * Construtor da classe
   */
  function __construct($iCodigoEstrutura = null){

    if (!empty($iCodigoEstrutura)){
      parent::__construct(null);
    }
  }

  /**
   * Define o codigo da lotacao
   *
   * @param Integer $iCodigoLotacao
   * @access public
   * @return void
   */
  public function setCodigoLotacao($iCodigoLotacao) {
    $this->iCodigoLotacao = $iCodigoLotacao;
  }

  /**
   * Define uma lista de Usu�rios
   *
   * @param array $aUsuariosPermissaoLotacao
   * @access public
   * @return void
   */
  public function setUsuarios($aUsuariosPermissaoLotacao) {
    $this->aUsuariosPermissaoLotacao = $aUsuariosPermissaoLotacao;
  }

  /**
   * Define a descri��o da lota��o
   *
   * @param string slotacao
   * @access public
   * @return void
   */
  public function setDescricaoLotacao($slotacao) {
    $this->sDescricaoLotacao = $slotacao;
  }

  /**
   * Define uma institui��o
   *
   * @param Institui��o $oIstituicao
   * @access public
   * @return void
   */
  public function setInstituicao($oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Define uma caracter�stica peculiar
   *
   * @param CaracteristicaPeculiar $oCarPeculiar
   * @access public
   * @return void
   */
  public function setCaracteristicaPeculiar($oCarPeculiar) {
    $this->oCarPeculiar = $oCarPeculiar;
  }

  /**
   * Define se a lota��o � anal�tica
   *
   * @param boolean $lAnalitica
   * @access public
   * @return void
   */
  public function setAnalitica($lAnalitica) {
    $this->lAnalitica = $lAnalitica;
  }

  /**
   * Define se a lota��o est� ativa
   *
   * @param boolean $lAtiva
   * @access public
   * @return void
   */
  public function setAtiva($lAtiva) {
    $this->lAtiva = $lAtiva;
  }

  /**
   * Define a string do estrutural da lota��o
   *
   * @param String $sEstrutural
   * @access public
   * @return void
   */
  public function setStringEstrutural($sEstrutural) {
    $this->sEstrutural = $sEstrutural;
  }

  /**
   * Define o CGM da lota��o
   *
   * @param Cgm $oCgm
   * @access public
   * @return void
   */
  public function setCgm($oCgm) {
    $this->oCgm = $oCgm;
  }

  /**
   * Retorna um codigo de lota��o
   *
   * @access public
   * @return Integer C�digo de Lota��o
   */
  public function getCodigoLotacao(){
    return $this->iCodigoLotacao;
  }

  /**
   * Retorna uma institui��o
   *
   * @access public
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Retorna uma lista de Usu�rios
   *
   * @access public
   * @return array
   */
  public function getUsuarios() {
    return $this->aUsuariosPermissaoLotacao;
  }

  /**
   * Get descricao lotacao
   *
   * @access public
   * @return String
   */
  public function getDescricaoLotacao() {
    return $this->sDescricaoLotacao;
  }

  /**
   * Retorna uma caracter�stica peculiar
   *
   * @access public
   * @return CaracteristicaPeculiar
   */
  public function getCaracteristicaPeculiar() {
    return $this->oCarPeculiar;
  }

  /**
   * Retorna se a lota��o � anal�tica
   *
   * @access public
   * @return Boolean
   */
  public function isAnalitica() {
    return (boolean)$this->lAnalitica;
  }

  /**
   * Retorna se a lota��o est� ativa
   * @access public
   * @return Boolean
   */
  public function isAtiva() {
    return (boolean)$this->lAtiva;
  }

  /**
   * Retorna a String do estrutural da lotacao
   *
   * @access public
   * @return String
   */
  public function getStringEstrutural() {
    return $this->sEstrutural;
  }

  /**
   * Retorna o Cgm da Lota��o
   *
   * @access public
   * @return Cgm
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Retorna um JSON com os Dados da Lota��o.
   *
   * @return String
   */
  public function toJSON(){

    $oLotacoes             = new stdClass();
    $oLotacoes->codigo     = $this->getCodigoLotacao();
    $oLotacoes->estrutural = $this->getStringEstrutural();
    $oLotacoes->descricao  = urlencode($this->getDescricaoLotacao());

    return $oLotacoes;
  }

}