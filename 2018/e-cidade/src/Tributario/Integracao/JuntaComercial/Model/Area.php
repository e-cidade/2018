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

class Area
{
  /**
   * Código de identifcação da área ou secretaria no REGIN.
   * Não obrigatório quando for o alvará, cadastro imobiliário ou cadastro mobiliario.
   * @field CODIGO_AREA
   * @max 99999
   * @var integer $iCodigoArea
   */
  private $iCodigoArea;

  /**
   * Data da analise realizada pela área ou secretaria da instituição.
   * @field DATA_ANALISE
   * @size 8
   * @var integer $iDataAnalise
   */
  private $iDataAnalise;

  /**
   * Status da análise
   * @field STATUS_ANALISE
   * @size 1
   * @var integer $iStatusAnalise
   */
  private $iStatusAnalise;

  /**
   * Justifcativa para o status da análise.
   * @field JUSTIFICATIVA_ANALISE
   * @size 10000
   * @var string $sJustificaAnalise
   */
  private $sJustificaAnalise;

  /**
   * CPF do usuário responsável pela análise.
   * Não é obrigatório. Quando informado, o CPF do usuário deve estar cadastrado na Junta Comercial.
   * @field CPF_USUARIO_ANALISE
   * @size 15
   * @var string $sCPFUsuarioAnalise
   */
  private $sCPFUsuarioAnalise;

  /**
   * @return int
   */
  public function getCodigoArea()
  {
    return $this->iCodigoArea;
  }

  /**
   * @param int $iCodigoArea
   */
  public function setCodigoArea($iCodigoArea)
  {
    $this->iCodigoArea = $iCodigoArea;
  }

  /**
   * @return int
   */
  public function getDataAnalise()
  {
    return $this->iDataAnalise;
  }

  /**
   * @param int $iDataAnalise
   */
  public function setDataAnalise($iDataAnalise)
  {
    $this->iDataAnalise = $iDataAnalise;
  }

  /**
   * @return int
   */
  public function getStatusAnalise()
  {
    return $this->iStatusAnalise;
  }

  /**
   * @param int $iStatusAnalise
   */
  public function setStatusAnalise($iStatusAnalise)
  {
    $this->iStatusAnalise = $iStatusAnalise;
  }

  /**
   * @return string
   */
  public function getJustificaAnalise()
  {
    return $this->sJustificaAnalise;
  }

  /**
   * @param string $sJustificaAnalise
   */
  public function setJustificaAnalise($sJustificaAnalise)
  {
    $this->sJustificaAnalise = $sJustificaAnalise;
  }

  /**
   * @return string
   */
  public function getCPFUsuarioAnalise()
  {
    return $this->sCPFUsuarioAnalise;
  }

  /**
   * @param string $sCPFUsuarioAnalise
   */
  public function setCPFUsuarioAnalise($sCPFUsuarioAnalise)
  {
    $this->sCPFUsuarioAnalise = $sCPFUsuarioAnalise;
  }

  /**
   * Area constructor.
   * @param int $iCodigoArea
   * @param int $iDataAnalise
   * @param int $iStatusAnalise
   * @param string $sJustificaAnalise
   * @param string $sCPFUsuarioAnalise
   */
  public function __construct($iCodigoArea, $iDataAnalise, $iStatusAnalise, $sJustificaAnalise, $sCPFUsuarioAnalise)
  {
    $this->iCodigoArea = $iCodigoArea;
    $this->iDataAnalise = $iDataAnalise;
    $this->iStatusAnalise = $iStatusAnalise;
    $this->sJustificaAnalise = $sJustificaAnalise;
    $this->sCPFUsuarioAnalise = $sCPFUsuarioAnalise;
  }

}
