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

namespace ECidade\Tributario\Integracao\JuntaComercial\Model;

/**
 * Class Atividade
 * @package ECidade\Tributario\Integracao\JuntaComercial\Model
 */
class Atividade
{
  /**
   * @var integer $iInscricao
   */
  private $iInscricao;

  /**
   * @var integer
   */
  private $iCodigo;

  /**
   * @var \DateTime
   */
  private $oDataInicio = null;

  /**
   * @var \DateTime
   */
  private $oDataFim = null;

  /**
   * @var \DateTime
   */
  private $oDataBaixa = null;

  /**
   * @var integer
   */
  private $iQuantidade = 1;

  /**
   * @var string
   */
  private $sTipoBaixa =  "0";

  /**
   * @var boolean
   */
  private $lPermanente = true;

  /**
   * @var string
   */
  private $sHoraInicio = "";

  /**
   * @var string
   */
  private $sHoraFim = "";

  /**
   * @var boolean $lAtividadePrincipal
   */
  private $lAtividadePrincipal = false;

  /**
   * @var integer $iSequencial
   */
  private $iSequencial;

  /**
   * Atividade constructor.
   * @param int $iInscricao
   */
  public function __construct($iInscricao)
  {
    $this->iInscricao = $iInscricao;
  }

  /**
   * @return bool
   */
  public function isAtividadePrincipal()
  {
    return $this->lAtividadePrincipal;
  }

  /**
   * @param bool $lAtividadePrincipal
   */
  public function setAtividadePrincipal($lAtividadePrincipal)
  {
    $this->lAtividadePrincipal = $lAtividadePrincipal;
  }

  /**
   * @return integer
   */
  public function getSequencial()
  {
    return $this->iSequencial;
  }

  /**
   * @param integer $iSequencial
   */
  public function setSequencial($iSequencial)
  {
    $this->iSequencial = $iSequencial;
  }

  /**
   * @return int
   */
  public function getInscricao()
  {
    return $this->iInscricao;
  }

  /**
   * @param int $iInscricao
   */
  public function setInscricao($iInscricao)
  {
    $this->iInscricao = $iInscricao;
  }

  /**
   * @return int
   */
  public function getCodigo()
  {
    return $this->iCodigo;
  }

  /**
   * @param int $iCodigo
   */
  public function setCodigo($iCodigo)
  {
    $this->iCodigo = $iCodigo;
  }

  /**
   * @return \DateTime
   */
  public function getDataInicio()
  {
    return $this->oDataInicio;
  }

  /**
   * @param \DateTime $oDataInicio
   */
  public function setDataInicio($oDataInicio)
  {
    $this->oDataInicio = $oDataInicio;
  }

  /**
   * @return \DateTime
   */
  public function getDataFim()
  {
    return $this->oDataFim;
  }

  /**
   * @param \DateTime $oDataFim
   */
  public function setDataFim($oDataFim)
  {
    $this->oDataFim = $oDataFim;
  }

  /**
   * @return \DateTime
   */
  public function getDataBaixa()
  {
    return $this->oDataBaixa;
  }

  /**
   * @param \DateTime $oDataBaixa
   */
  public function setDataBaixa($oDataBaixa)
  {
    $this->oDataBaixa = $oDataBaixa;
  }

  /**
   * @return int
   */
  public function getQuantidade()
  {
    return $this->iQuantidade;
  }

  /**
   * @param int $iQuantidade
   */
  public function setQuantidade($iQuantidade)
  {
    $this->iQuantidade = $iQuantidade;
  }

  /**
   * @return string
   */
  public function getTipoBaixa()
  {
    return $this->sTipoBaixa;
  }

  /**
   * @param string $sTipoBaixa
   */
  public function setTipoBaixa($sTipoBaixa)
  {
    $this->sTipoBaixa = $sTipoBaixa;
  }

  /**
   * @return bool
   */
  public function isPermanente()
  {
    return $this->lPermanente;
  }

  /**
   * @param bool $lPermanente
   */
  public function setPermanente($lPermanente)
  {
    $this->lPermanente = $lPermanente;
  }

  /**
   * @return string
   */
  public function getHoraInicio()
  {
    return $this->sHoraInicio;
  }

  /**
   * @param string $sHoraInicio
   */
  public function setHoraInicio($sHoraInicio)
  {
    $this->sHoraInicio = $sHoraInicio;
  }

  /**
   * @return string
   */
  public function getHoraFim()
  {
    return $this->sHoraFim;
  }

  /**
   * @param string $sHoraFim
   */
  public function setHoraFim($sHoraFim)
  {
    $this->sHoraFim = $sHoraFim;
  }

}
