<?php
/**
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
 * Class AutenticacaoTesouraria
 */
class AutenticacaoTesouraria {

  /**
   * @var int
   */
  protected $iTerminal;

  /**
   * @var DBDate
   */
  protected $dtData;

  /**
   * @var int
   */
  protected $iAutenticacao;

  /**
   * @var ContaPlano
   */
  protected $oContaPagadora;

  /**
   * @var float
   */
  protected $nValor;

  /**
   * @var boolean
   */
  protected $lEstorno;


  public function __construct() {

  }

  /**
   * @param DBDate $dtData
   */
  public function setData(DBDate $dtData) {
    $this->dtData = $dtData;
  }

  /**
   * @return DBDate
   */
  public function getData() {
    return $this->dtData;
  }

  /**
   * @param int $iAutenticacao
   */
  public function setAutenticacao($iAutenticacao) {
    $this->iAutenticacao = $iAutenticacao;
  }

  /**
   * @return int
   */
  public function getAutenticacao() {
    return $this->iAutenticacao;
  }

  /**
   * @param int $iTerminal
   */
  public function setTerminal($iTerminal) {
    $this->iTerminal = $iTerminal;
  }

  /**
   * @return int
   */
  public function getTerminal() {
    return $this->iTerminal;
  }

  /**
   * @param ContaPlano $oContaPagadora
   */
  public function setContaPagadora(ContaPlano $oContaPagadora) {
    $this->oContaPagadora = $oContaPagadora;
  }

  /**
   * @return ContaPlano
   */
  public function getContaPagadora() {
    return $this->oContaPagadora;
  }

  /**
   * @param float $nValor
   */
  public function setValor($nValor) {
    $this->nValor = $nValor;
  }

  /**
   * @return mixed
   */
  public function getValor() {
    return $this->nValor;
  }

  /**
   * @param $lEstorno
   */
  public function setEstorno($lEstorno) {
    $this->lEstorno = $lEstorno;
  }

  /**
   * @return bool
   */
  public function estorno() {
    return $this->lEstorno;
  }
}