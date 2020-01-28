<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBSeller Servicos de Informatica
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

namespace ECidade\Tributario\Arrecadacao\EmissaoGeral\Registro;

use ECidade\Tributario\Arrecadacao\EmissaoGeral\EmissaoGeral;

class Padrao {

  const SITUACAO_PENDENTE     = 1;
  const SITUACAO_VALIDO       = 2;
  const SITUACAO_SEM_COBRANCA = 3;

  /**
   * @var integer
   */
  protected $iCodigo;

  /**
   * @var EmissaoGeral
   */
  protected $oEmissao;

  /**
   * @var integer
   */
  protected $iNumpre;

  /**
   * @var integer
   */
  protected $iCgm;

  /**
   * @var integer
   */
  protected $iParcela;

  /**
   * @var integer
   */
  protected $iSituacao;

  /**
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * @param integer iCodigo
   */
  public function setCodigo($iCodigo) {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return EmissaoGeral
   */
  public function getEmissao() {
    return $this->oEmissao;
  }

  /**
   * @param EmissaoGeral oEmissao
   */
  public function setEmissao(EmissaoGeral $oEmissao) {
    $this->oEmissao = $oEmissao;
  }

  /**
   * @return integer
   */
  public function getNumpre() {
    return $this->iNumpre;
  }

  /**
   * @param integer iNumpre
   */
  public function setNumpre($iNumpre) {
    $this->iNumpre = $iNumpre;
  }

  /**
   * @return integer
   */
  public function getCgm() {
    return $this->iCgm;
  }

  /**
   * @param integer iCgm
   */
  public function setCgm($iCgm) {
    $this->iCgm = $iCgm;
  }

  /**
   * @return integer
   */
  public function getParcela() {
    return $this->iParcela;
  }

  /**
   * @param integer iParcela
   */
  public function setParcela($iParcela) {
    $this->iParcela = $iParcela;
  }

  /**
   * @return integer
   */
  public function getSituacao() {
    return $this->iSituacao;
  }

  /**
   * @param integer iSituacao
   */
  public function setSituacao($iSituacao) {
    $this->iSituacao = $iSituacao;
  }
}
