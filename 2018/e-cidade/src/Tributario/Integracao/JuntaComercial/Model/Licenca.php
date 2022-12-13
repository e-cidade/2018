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

class Licenca
{
  /**
   * C�digo de identifca��o da �rea ou secretaria no REGIN.
   * N�o obrigat�rio quando for o alvar�, cadastro imobili�rio ou cadastro mobiliario.
   * @field CODIGO_AREA
   * @max 99999
   * @var integer $iCodigoArea
   */
  private $iCodigoArea;

  /**
   * Identifica o tipo de licen�a
   * @field CODIGO_LICENCA
   * @max 99
   * @values [1 - Alvar�, 2 - Vigil�ncia Sanit�ria, 3 - Corpo de bombeiros, 4 - Cadastro mobili�rio, 5 - Cadastro imobili�rio]
   * @var integer $iCodigoLicenca
   */
  private $iCodigoLicenca;

  /**
   * Indica perman�ncia da licen�a
   * @field LICENCA_DEFINITIVA
   * @size 1
   * @values [0 - Provis�ria, 1 - Definitiva]
   * @var integer $iLicencaDefinitiva
   */
  private $iLicencaDefinitiva;

  /**
   * N�mero da licen�a concedida.
   * @field NUMERO_LICENCA
   * @size 24
   * @var string $sNumeroLicenca
   */
  private $sNumeroLicenca;

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
  public function getCodigoLicenca()
  {
    return $this->iCodigoLicenca;
  }

  /**
   * @param int $iCodigoLicenca
   */
  public function setCodigoLicenca($iCodigoLicenca)
  {
    $this->iCodigoLicenca = $iCodigoLicenca;
  }

  /**
   * @return int
   */
  public function getLicencaDefinitiva()
  {
    return $this->iLicencaDefinitiva;
  }

  /**
   * @param int $iLicencaDefinitiva
   */
  public function setLicencaDefinitiva($iLicencaDefinitiva)
  {
    $this->iLicencaDefinitiva = $iLicencaDefinitiva;
  }

  /**
   * @return string
   */
  public function getNumeroLicenca()
  {
    return $this->sNumeroLicenca;
  }

  /**
   * @param string $sNumeroLicenca
   */
  public function setNumeroLicenca($sNumeroLicenca)
  {
    $this->sNumeroLicenca = $sNumeroLicenca;
  }

  const TIPO_LICENCA_DEFINITIVA = 1;
  const TIPO_LICENCA_PROVISORIA = 0;

  const LICENCA_ALVARA = 1;
  const LICENCA_SANITARIA = 2;
  const LICENCA_BOMBEIROS = 3;
  const LICENCA_MOBILIARIO = 4;
  const LICENCA_IMOBILIARIO = 5;

  /**
   * Licenca constructor.
   * @param int $iCodigoArea
   * @param int $iCodigoLicenca
   * @param int $iLicencaDefinitiva
   * @param string $sNumeroLicenca
   */
  public function __construct($iCodigoArea, $iCodigoLicenca, $iLicencaDefinitiva, $sNumeroLicenca)
  {
    $this->iCodigoArea = $iCodigoArea;
    $this->iCodigoLicenca = $iCodigoLicenca;
    $this->iLicencaDefinitiva = $iLicencaDefinitiva;
    $this->sNumeroLicenca = $sNumeroLicenca;
  }

}
