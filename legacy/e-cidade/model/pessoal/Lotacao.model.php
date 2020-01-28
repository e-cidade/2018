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
 * Classe que representa uma lotacao de um usuário
 *
 * @package folha
 * @author  Renan Silva  <renan.silva@dbseller.com.br>
 */

class Lotacao extends DBEstrutura{

  /**
   * Código da lotação
   *
   * @var iCodigoLotacao
   * @access private
   */
  private $iCodigoLotacao;

  /**
   * Usuários
   *
   * @var aUsuariosPermissaoLotacao
   * @access private
   */
  private $aUsuariosPermissaoLotacao;

  /**
   * Descrição da lotação
   *
   * @var $sDescricao
   * @access private
   */
  private $sDescricaoLotacao;

  /**
   * Instituição
   *
   * @var oInstituicao
   * @access private
   */
  private $oInstituicao;

  /**
   * Característica peculiar
   *
   * @var oCarPeculiar
   * @access private
   */
  private $oCarPeculiar;

  /**
   * Informa se é analítica
   *
   * @var lAnalitica
   * @access private
   */
  private $lAnalitica;

  /**
   * Informa se está ativa a lotação
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
   * O CGM da lotação
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
   * Define uma lista de Usuários
   *
   * @param array $aUsuariosPermissaoLotacao
   * @access public
   * @return void
   */
  public function setUsuarios($aUsuariosPermissaoLotacao) {
    $this->aUsuariosPermissaoLotacao = $aUsuariosPermissaoLotacao;
  }

  /**
   * Define a descrição da lotação
   *
   * @param string slotacao
   * @access public
   * @return void
   */
  public function setDescricaoLotacao($slotacao) {
    $this->sDescricaoLotacao = $slotacao;
  }

  /**
   * Define uma instituição
   *
   * @param Instituição $oIstituicao
   * @access public
   * @return void
   */
  public function setInstituicao($oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * Define uma característica peculiar
   *
   * @param CaracteristicaPeculiar $oCarPeculiar
   * @access public
   * @return void
   */
  public function setCaracteristicaPeculiar($oCarPeculiar) {
    $this->oCarPeculiar = $oCarPeculiar;
  }

  /**
   * Define se a lotação é analítica
   *
   * @param boolean $lAnalitica
   * @access public
   * @return void
   */
  public function setAnalitica($lAnalitica) {
    $this->lAnalitica = $lAnalitica;
  }

  /**
   * Define se a lotação está ativa
   *
   * @param boolean $lAtiva
   * @access public
   * @return void
   */
  public function setAtiva($lAtiva) {
    $this->lAtiva = $lAtiva;
  }

  /**
   * Define a string do estrutural da lotação
   *
   * @param String $sEstrutural
   * @access public
   * @return void
   */
  public function setStringEstrutural($sEstrutural) {
    $this->sEstrutural = $sEstrutural;
  }

  /**
   * Define o CGM da lotação
   *
   * @param Cgm $oCgm
   * @access public
   * @return void
   */
  public function setCgm($oCgm) {
    $this->oCgm = $oCgm;
  }

  /**
   * Retorna um codigo de lotação
   *
   * @access public
   * @return Integer Código de Lotação
   */
  public function getCodigoLotacao(){
    return $this->iCodigoLotacao;
  }

  /**
   * Retorna uma instituição
   *
   * @access public
   * @return Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * Retorna uma lista de Usuários
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
   * Retorna uma característica peculiar
   *
   * @access public
   * @return CaracteristicaPeculiar
   */
  public function getCaracteristicaPeculiar() {
    return $this->oCarPeculiar;
  }

  /**
   * Retorna se a lotação é analítica
   *
   * @access public
   * @return Boolean
   */
  public function isAnalitica() {
    return (boolean)$this->lAnalitica;
  }

  /**
   * Retorna se a lotação está ativa
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
   * Retorna o Cgm da Lotação
   *
   * @access public
   * @return Cgm
   */
  public function getCgm() {
    return $this->oCgm;
  }

  /**
   * Retorna um JSON com os Dados da Lotação.
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