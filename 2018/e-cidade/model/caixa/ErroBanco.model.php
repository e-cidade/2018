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

class ErroBanco {

  /**
   * Código sequencial do erro
   * @var integer
   */
  private $iCodigo;

  /**
   * Código alfa numérico do erro
   * @var string
   */
  private $sErro;

  /**
   * Descrição do erro
   * @var string
   */
  private $sDescricao;

  /**
   * Indica se movimento deve ser pago ou não
   * @var boolean
   */
  private $lProcessa;

  /**
   * Código do tipo de transmissão vinculado ao erro
   * @var integer
   */
  private $iTipoTransmissao;

  public function __construct($iCodigo = null) {

    if (!empty($iCodigo)) {

      $oDaoErroBanco = new cl_errobanco;

      $sSql    = $oDaoErroBanco->sql_query_file($iCodigo);
      $rsDados = db_query($sSql);

      if (!$rsDados || pg_num_rows($rsDados) === 0) {
        throw new DBException('Não foi possível consultar o código de erro.');
      }

      $oDados = db_utils::fieldsMemory($rsDados, 0);

      $this->iCodigo          = $iCodigo;
      $this->sErro            = $oDados->e92_coderro;
      $this->sDescricao       = $oDados->e92_descrerro;
      $this->lProcessa        = $oDados->e92_processa == 't';
      $this->iTipoTransmissao = (int) $oDados->e92_empagetipotransmissao;
    }
  }

  /**
   * @return integer $iCodigo
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer $iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return string $sErro
   */
  public function getErro() {
    return $this->sErro;
  }

  /**
   * @param string $sErro
   */
  public function setErro($sErro) {
    $this->sErro = $sErro;
  }

  /**
   * @return string $sDescricao
   */
  public function getDescricao() {
    return $this->sDescricao;
  }

  /**
   * @param string $sDescricao
   */
  public function setDescricao($sDescricao) {
    $this->sDescricao = $sDescricao;
  }

  /**
   * @return boolean $lProcessa
   */
  public function getProcessa() {
    return $this->lProcessa;
  }

  /**
   * @param boolean $lProcessa
   */
  public function setProcessa($lProcessa) {
    $this->lProcessa = $lProcessa;
  }

  /**
   * @return integer $iTipoTransmissao
   */
  public function getTipoTransmissao() {
    return $this->iTipoTransmissao;
  }

  /**
   * @param integer $iTipoTransmissao
   */
  public function setTipoTransmissao($iTipoTransmissao) {
    $this->iTipoTransmissao = $iTipoTransmissao;
  }

}