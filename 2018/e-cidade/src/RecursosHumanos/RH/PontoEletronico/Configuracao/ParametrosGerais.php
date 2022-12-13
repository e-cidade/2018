<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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

namespace ECidade\RecursosHumanos\RH\PontoEletronico\Configuracao;

class ParametrosGerais {

  /**
   * @var int
   */
  private $iCodigo;

  /**
   * @var \Instituicao
   */
  private $oInstituicao;

  /**
   * @var null|\TipoAssentamento
   */
  private $oTipoAssentamentoExtra50Diurna;

  /**
   * @var null|\TipoAssentamento
   */
  private $oTipoAssentamentoExtra75Diurna;

  /**
   * @var null|\TipoAssentamento
   */
  private $oTipoAssentamentoExtra100Diurna;

  /**
   * @var null|\TipoAssentamento
   */
  private $oTipoAssentamentoExtra50Noturna;

  /**
   * @var null|\TipoAssentamento
   */
  private $oTipoAssentamentoExtra75Noturna;

  /**
   * @var null|\TipoAssentamento
   */
  private $oTipoAssentamentoExtra100Noturna;

  /**
   * @var null|\TipoAssentamento
   */
  private $oTipoAssentamentoAdicionalNoturno;

  /**
   * @var null|\TipoAssentamento
   */
  private $oTipoAssentamentoFalta;

  /**
   * @var bool
   */
  private $lHoraExtraSomenteComAutorizacao = true;

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
   * @param \Instituicao $oInstituicao
   */
  public function setInstituicao(\Instituicao $oInstituicao) {
    $this->oInstituicao = $oInstituicao;
  }

  /**
   * @return \Instituicao
   */
  public function getInstituicao() {
    return $this->oInstituicao;
  }

  /**
   * @param null|\TipoAssentamento $oTipoAssentamentoExtra50Diurna
   */
  public function setTipoAssentamentoExtra50Diurna($oTipoAssentamentoExtra50Diurna) {
    $this->oTipoAssentamentoExtra50Diurna = $oTipoAssentamentoExtra50Diurna;
  }

  /**
   * @return null|\TipoAssentamento
   */
  public function getTipoAssentamentoExtra50Diurna() {
    return $this->oTipoAssentamentoExtra50Diurna;
  }

  /**
   * @param null|\TipoAssentamento $oTipoAssentamentoExtra75Diurna
   */
  public function setTipoAssentamentoExtra75Diurna($oTipoAssentamentoExtra75Diurna) {
    $this->oTipoAssentamentoExtra75Diurna = $oTipoAssentamentoExtra75Diurna;
  }

  /**
   * @return null|\TipoAssentamento
   */
  public function getTipoAssentamentoExtra75Diurna() {
    return $this->oTipoAssentamentoExtra75Diurna;
  }

  /**
   * @param null|\TipoAssentamento $oTipoAssentamentoExtra100Diurna
   */
  public function setTipoAssentamentoExtra100Diurna($oTipoAssentamentoExtra100Diurna) {
    $this->oTipoAssentamentoExtra100Diurna = $oTipoAssentamentoExtra100Diurna;
  }

  /**
   * @return null|\TipoAssentamento
   */
  public function getTipoAssentamentoExtra100Diurna() {
    return $this->oTipoAssentamentoExtra100Diurna;
  }

  /**
   * @param null|\TipoAssentamento $oTipoAssentamentoExtra50Noturna
   */
  public function setTipoAssentamentoExtra50Noturna($oTipoAssentamentoExtra50Noturna) {
    $this->oTipoAssentamentoExtra50Noturna = $oTipoAssentamentoExtra50Noturna;
  }

  /**
   * @return null|\TipoAssentamento
   */
  public function getTipoAssentamentoExtra50Noturna() {
    return $this->oTipoAssentamentoExtra50Noturna;
  }

  /**
   * @param null|\TipoAssentamento $oTipoAssentamentoExtra75Noturna
   */
  public function setTipoAssentamentoExtra75Noturna($oTipoAssentamentoExtra75Noturna) {
    $this->oTipoAssentamentoExtra75Noturna = $oTipoAssentamentoExtra75Noturna;
  }

  /**
   * @return null|\TipoAssentamento
   */
  public function getTipoAssentamentoExtra75Noturna() {
    return $this->oTipoAssentamentoExtra75Noturna;
  }

  /**
   * @param null|\TipoAssentamento $oTipoAssentamentoExtra100Noturna
   */
  public function setTipoAssentamentoExtra100Noturna($oTipoAssentamentoExtra100Noturna) {
    $this->oTipoAssentamentoExtra100Noturna = $oTipoAssentamentoExtra100Noturna;
  }

  /**
   * @return null|\TipoAssentamento
   */
  public function getTipoAssentamentoExtra100Noturna() {
    return $this->oTipoAssentamentoExtra100Noturna;
  }

  /**
   * @param null|\TipoAssentamento $oTipoAssentamentoAdicionalNoturno
   */
  public function setTipoAssentamentoAdicionalNoturno($oTipoAssentamentoAdicionalNoturno) {
    $this->oTipoAssentamentoAdicionalNoturno = $oTipoAssentamentoAdicionalNoturno;
  }

  /**
   * @return null|\TipoAssentamento
   */
  public function getTipoAssentamentoAdicionalNoturno() {
    return $this->oTipoAssentamentoAdicionalNoturno;
  }

  /**
   * @param null|\TipoAssentamento $oTipoAssentamentoFalta
   */
  public function setTipoAssentamentoFalta($oTipoAssentamentoFalta) {
    $this->oTipoAssentamentoFalta = $oTipoAssentamentoFalta;
  }

  /**
   * @return null|\TipoAssentamento
   */
  public function getTipoAssentamentoFalta() {
    return $this->oTipoAssentamentoFalta;
  }

  /**
   * @param bool $lHoraExtraSomenteComAutorizacao
   */
  public function setHoraExtraSomenteComAutorizacao($lHoraExtraSomenteComAutorizacao) {
    $this->lHoraExtraSomenteComAutorizacao = $lHoraExtraSomenteComAutorizacao;
  }

  /**
   * @return bool
   */
  public function horaExtraSomenteComAutorizacao() {
    return $this->lHoraExtraSomenteComAutorizacao;
  }
}
