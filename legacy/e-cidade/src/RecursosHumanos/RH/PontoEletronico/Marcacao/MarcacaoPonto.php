<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBseller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Marcacao;

use \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa as JustificativaModel;

/**
 * Classe que representa uma marcação de horário ponto
 * Class MarcacaoPonto
 * @package ECidade\RecursosHumanos\RH\PontoEletronico\Calculo\Model
 * @author Renan Silva <renan.silva@dbseller.com.br>
 */
class MarcacaoPonto {

  const ENTRADA_1 = 1;
  const SAIDA_1   = 2;
  const ENTRADA_2 = 3;
  const SAIDA_2   = 4;
  const ENTRADA_3 = 5;
  const SAIDA_3   = 6;

  /**
   * Construtor da classe
   *
   * @param \DateTime $oMarcacao
   * @param Integer $iTipoMarcacao
   */
  public function __construct($oMarcacao = null, $iTipoMarcacao = null) {

    if(!empty($oMarcacao)) {

      $this->oMarcacao           = $oMarcacao;
      $this->lTemMarcacaoLancada = false;
    }

    if(!empty($iTipoMarcacao)) {
      $this->iTipoMarcacao = $iTipoMarcacao;
    }
  }

  /**
   * @var \DateTime $oMarcacao
   */
  protected $oMarcacao;

  /**
   * @var integer $iTipo
   */
  private $iTipoMarcacao;

  /**
   * @var int
   */
  private $iCodigo;

  /**
   * #@var boolean
   */
  private $lManual = false;

  /**
   * @var \DBDate
   */
  private $oData;

  /**
   * @var \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa
   */
  private $oJustificativa;

  /**
   * @var bool
   */
  private $lTemMarcacaoLancada = true;

  /**
   * Define a hora da marcação
   *
   * @param \DateTime $oMarcacao
   */
  public function setMarcacao(\DateTime $oMarcacao) {
    $this->oMarcacao = $oMarcacao;
  }

  /**
   * Retorna a hora da marcação
   *
   * @return \DateTime $oMarcacao 
   */
  public function getMarcacao() {
    return $this->oMarcacao;
  }

  /**
   * Define o tipo de marcação
   *
   * @param Integer
   */
  public function setTipo($iTipoMarcacao) {
    $this->iTipoMarcacao = $iTipoMarcacao;
  }

  /**
   * Retorna o tipo de marcação
   *
   * @return Integer
   */
  public function getTipo() {
    return $this->iTipoMarcacao;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return int
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define se a marcação é ou não manual
   * @param boolean $lManual
   */
  public function setManual($lManual) {
    $this->lManual = $lManual;
  }

  /** 
   * Retorna se é ou não manual a marcação
   * @return boolean
   */
  public function isManual() {
    return $this->lManual;
  }

  /**
   * @return \DBDate
   */
  public function getData() {
    return $this->oData;
  }

  /**
   * @param \DBDate $oData
   */
  public function setData(\DBDate $oData) {
    $this->oData = $oData;
  }

  /**
   * @return \ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao\Model\Justificativa
   */
  public function getJustificativa() {
    return $this->oJustificativa;
  }

  /**
   * @param JustificativaModel $oJustificativa
   */
  public function setJustificativa(JustificativaModel $oJustificativa) {
    $this->oJustificativa = $oJustificativa;
  }

  /**
   * @return bool
   */
  public function hasMarcacaoLancada() {
    return $this->lTemMarcacaoLancada;
  }

  /**
   * Limpa a hora da marcação
   */
  public function limparHoraMarcacao() {
    $this->oMarcacao = null;
  }
}